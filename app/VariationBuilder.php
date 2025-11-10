<?php

namespace App;

use App\Models\Attribute;
use App\Models\Property;
use App\Models\ProductVariation;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class VariationBuilder
{
    /** @var ProductVariation[] */
    protected array $variations;

    public function __construct(array $variations)
    {
        $this->variations = $variations;
    }

    public function getVariationProperties(ProductVariation $variation)
    {
        $result = [];
        foreach ($variation->properties as $property) {
            if ($property->attribute->in_variant) {
                $result[$property->attribute->slug] = $property->slug;
            }
        }

        return $result;
    }

    public function getLikeness(array $a, array $b): float
    {
        $count = 0;
        $keys = array_unique(array_merge(array_keys($a), array_keys($b)));
        foreach ($keys as $key) {
            if (($a[$key] ?? null) === ($b[$key] ?? null)) {
                ++$count;
            }
        }
        return ((float) $count) / count($keys);
    }

    public function buildVariations(ProductVariation $variation)
    {
        $possibleProps = [];
        foreach ($this->variations as $otherVariation) {
            $props = $this->getVariationProperties($otherVariation);
            foreach ($props as $propName => $propValue) {
                $possibleProps[$propName][$propValue] = null;
            }
        }

        $current = $this->getVariationProperties($variation);

        foreach ($possibleProps as $propName => &$propValues) {
            foreach ($propValues as $propValue => &$currentVariation) {
                $newProps = $current;

                if (($newProps[$propName] ?? null) === $propValue) {
                    $propValue = null;
                }
                $newProps[$propName] = $propValue;

                $currentLikeness = 0;
                foreach ($this->variations as $otherVariation) {
                    $props = $this->getVariationProperties($otherVariation);
                    $newLikeness = $this->getLikeness($props, $newProps) + ($propValue == ($props[$propName] ?? null) ? 1 : 0);
                    if ($newLikeness >= $currentLikeness) {
                        $currentLikeness = $newLikeness;
                        $currentVariation = $otherVariation;
                    }
                }
            }
        }

        return $possibleProps;
    }

    public function buildVariationsList(ProductVariation $currentVariation)
    {
        //$currentVariation = $currentVariation ?: $product->variation ?: $product->variations->first();

        $variationsList = [];
        foreach ($this->variations as $variation) {
            foreach ($variation->properties as $property) {
                if ($property->attribute === null) {
                    Log::error(__METHOD__, ['msg' => $property->slug . ' empty attr']);
                    continue;
                }
                if (!$property->attribute->in_variant) {
                    continue;
                }
                $variationsList['attributes'][$property->attribute_id] = $property->attribute;
                //$variationsList['properties'][$property->attribute_slug][$property->id] = $property;
                $variationsList['properties'][$property->id] = $property;
                $property->is_current = $currentVariation->properties->contains('id', $property->id);
            }
        }

        $attributes = array_values(Arr::sort($variationsList['attributes'] ?? [], function ($v) {
            return $v->weight;
        }));

        $properties = array_values(Arr::sort($variationsList['properties'] ?? [], function ($v) {
            return $v->weight;
        }));

        //return $variationsList;
        return [
            'attributes' => $attributes,
            'properties' => collect($properties),
        ];
    }

    public function buildSwitchingArray($variation): array
    {
        $variationsList = $this->buildVariationsList($variation);
        $variationValues = $this->buildVariations($variation);

        $res = [];
        /** @var Attribute $attribute */
        foreach($variationsList['attributes'] ?? [] as $attribute) {
            $properties = [];
            /** @var Property $property */
            foreach ($variationsList['properties']->where('attribute_id', $attribute->id) as $property) {
                $properties[] = [
                    //'weight' => $property->weight,
                    'property' => $this->getPropertyArray($property),
                    'variation' => $this->getVariationArray($variationValues[$attribute->slug][$property->slug]),
                    'is_current' => boolval($property->is_current),
                ];
            }

            $res[] = [
                'attribute' => $this->getAttributeArray($attribute),
                'properties' => $properties,
            ];
        }

        return $res;
    }

    protected function getVariationArray(ProductVariation $variation): array
    {
        $res = [
            'id' => $variation->id,
            'name' => $variation->getName(),
            'url' => $variation->getUrlClient(), // TODO: Deprecated homemama, plante
            'slug' => $variation->slug,
            'stock_qty' => $variation->stock_qty,
        ];

        return $res;
    }

    protected function getPropertyArray(Property $property): array
    {
        $res = [
            'id' => $property->id,
            'slug' => $property->slug,
            'value' => $property->getValue(),
            'color' => $property->color,
        ];

        if ($property->relationLoaded('media')) {
            $res['image'] = $property->getFirstMediaUrl('image');
        }

        return $res;
    }

    protected function getAttributeArray(Attribute $attribute): array
    {
        $res = [
            'id' => $attribute->id,
            'slug' => $attribute->slug,
            'name' => $attribute->getName(),
            'has_image' => $attribute->has_image,
            'format' => $attribute->has_image ? 'image' : 'text',
        ];

        if ($attribute->relationLoaded('properties')) {
            foreach ($attribute->properties->sortBy('weight') as $property) {
                $res['properties'][] = $this->getPropertyArray($property);
            }
        }

        return $res;
    }
}
