<?php

namespace App\Models\Traits;

use App\Http\Client\Api\Resources\AttributeResource;
use App\Http\Client\Api\Resources\PropertyResource;
use App\Models\Attribute;
use App\Models\Property;
use Illuminate\Support\Arr;

trait HasProperties
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function properties(): \Illuminate\Database\Eloquent\Relations\MorphToMany
    {
        return $this->morphToMany(Property::class, 'model', 'propertyables');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function variationProperties(): \Illuminate\Database\Eloquent\Relations\MorphToMany
    {
        return $this->properties()
            ->whereHas('attribute', fn($a) => $a->where('in_variant', true));
    }

    /**
     * TODO: DEPRECATED
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\MorphToMany
     */
//    public function specificationProperties()
//    {
//        return $this->properties()
//            ->whereHas('attribute', fn($a) => $a->where('in_specification', true));
//    }

    /**
     *
     * https://i.imgur.com/d4HR47K.png
     *
     * attribute: [
     *  slug: "kolir-vyshyvky"
     *  //...
     *  properties: [
     *      slug: "chornyy-1"
 *          //...
     *  ]
     * ]
     *
     * @param string $propDelimiter
     * @param $propFullValue
     * @return array
     */
    public function getAttributesPropertiesList(string $propDelimiter = ', ', $propFullValue = true): array
    {
        $res = [];

        foreach ($this->properties->pluck('attribute')->unique()->sortBy('weight')->values() as $attr) {
            if ($attr) {
                $properties = $this->properties->sortBy('weight')->where('attribute_slug', $attr->slug);

                $res[] = [
                    'attribute' => $attr,
                    'properties' => $properties,
                    'properties_str' => $propFullValue
                        ? $properties->each(fn($p)=>$p->value = $p->getValue())->implode('value', $propDelimiter)
                        : $properties->implode('value', $propDelimiter),
                    'attribute_str' => $attr->getName(),
                ];
            }
        }

        return $res;
    }

    /**
     * https://i.imgur.com/mR3rkON.png
     *
     * @param string $propDelimiter
     * @param $propFullValue
     * @return array
     */
    public function getAttributesPropertiesList2(string $propDelimiter = ', ', $propFullValue = true)//: array
    {
        $res = [];

        foreach ($this->properties->pluck('attribute')->unique()->sortBy('weight')->values() as $attr) {
            if ($attr) {
                $properties = $this->properties->sortBy('weight')->where('attribute_id', $attr->id);

                $res[] = [
                    'attribute' => AttributeResource::make($attr),
                    'properties' => PropertyResource::collection($properties),
                ];
            }
        }

        return $res;
    }

    /**
     *
     * Колір вишивки: Чорний; Колір: Білий; Український розмір: 42; Міжнародний розмір: S; Тканина: Льон
     *
     * https://i.imgur.com/EDubwxO.png
     *
     * @param string $attrDelimiter
     * @param string $attrPropDelimiter
     * @param string $propDelimiter
     * @param bool $propFullValue
     * @return string
     */
    public function getAttributesPropertiesListStr(
        string $attrDelimiter = '; ',
        string $attrPropDelimiter = ': ',
        string $propDelimiter = ', ',
        bool $propFullValue = true
    ): string {
        $res = '';
        foreach ($this->getAttributesPropertiesList($propDelimiter, $propFullValue) as $item) {
            $res .= $item['attribute_str'] . $attrPropDelimiter . $item['properties_str'] . $attrDelimiter;
        }

        return trim($res, $attrDelimiter);
    }

    /**
     * https://i.imgur.com/6bpaQtY.png
     *
     * kolir-vyshyvky => [
     *  chornyy-1,
     * ],
     * rozmiry => [
     *  42,43,
     * ]
     *
     * @param string $fieldAttribute
     * @param string $fieldProperty
     * @return array
     */
    public function getAttributesPropertiesListArray(string $fieldAttribute = 'slug', string $fieldProperty = 'slug'): array
    {
        $res = [];
        foreach ($this->getAttributesPropertiesList() as $item) {
            $res[$item['attribute'][$fieldAttribute]] = Arr::pluck($item['properties'], $fieldProperty);
        }

        return $res;
    }

    /**
     * Метод який вертає колір url а не назвою (для кожного атрибуту одне property!)
     *
     * https://i.imgur.com/SPWMgFa.png
     *
     * "specification": [
     *    {
     *        "attribute": {
     *            "format": "text",
     *            "name": "Довжина"
     *        },
     *        "property": {
     *            "value": "34",
     *            "image": "http://home.mama.test/storage/f77881bd-0e89-45c7-ba0c-66ea383ad63a/kolir-cornii.jpg" || ""
     *        }
     *    }
     * ],
     *
     * @param string $fieldAttribute
     * @param string $fieldProperty
     * @return array
     */
    public function getAttributesPropertiesListArray2(string $fieldAttribute = 'slug', string $fieldProperty = 'slug'): array
    {
        $res = [];
        foreach ($this->getAttributesPropertiesList() as $item) {
            $res[] = [
                'attribute' => [
                    'slug' => $item['attribute']->slug,
                    'name' => $item['attribute']->getName(),
                    'format' => $item['attribute']->getFormat(),
                ],
                'property' => [
                    'slug' => $item['properties']->first()['slug'],
                    'value' => $item['properties']->first()[$fieldProperty],
                    'color' => $item['properties']->first()['color'] ?: null,
                    'image' => $item['properties']->first()->getFirstMediaUrl('image') ?: null,
                ],
            ];
        }

        return $res;
    }

    /**
     * Атрибути, їх значення, та каспомні характеристики з значеннями.
     * Для варіації (Rozetka).
     * https://i.imgur.com/etY8m7Y.png
     *
     * @param string $fieldAttribute
     * @param string $fieldProperty
     * @return array
     */
    public function getAllAttributesPropertiesCharacteristicsListArray(): array
    {
        $attrsExceptIds = \Domain::getOpt('extern.rozetka.attrs.except.ids', []);
        $attrExceptNames = \Domain::getOpt('extern.rozetka.attrs.except.names', []);
        $attrReplaceNames = \Domain::getOpt('extern.rozetka.attrs.name_replace', []);

        $res = [];
//        foreach ($this->getAttributesPropertiesListArray('name', 'value') as $attr => $props) {
//            $attr = transform_str_item($attr, ' :');
//            $props = array_transform_str_items($props, ' :');
//            $res[$attr] = array_merge($res[$attr] ?? [], $props);
//        }

        foreach ($this->getAttributesPropertiesList() as $item) {
            $attr = $item['attribute']->getName();

            if (!empty($attrReplaceNames[$attr])) {
                $attr = $attrReplaceNames[$attr];
            }

            if (in_array($item['attribute']->id, $attrsExceptIds)) {
                continue;
            }

            if (in_array($attr, $attrExceptNames)) {
                continue;
            }

            $props = Arr::pluck($item['properties'], 'value');
            $res[$attr] = array_merge($res[$attr] ?? [], $props);
        }

        foreach (Arr::wrap($this->getFields('characteristics', [])) as $field) {
            if (!empty($field['attribute']) && !empty($field['property'])) {
                $attr = transform_str_item($field['attribute'], ' :');

                if (!empty($attrReplaceNames[$attr])) {
                    $attr = $attrReplaceNames[$attr];
                }

                if (in_array($attr, $attrExceptNames)) {
                    continue;
                }

                $prop = transform_str_item($field['property'], ' :');
                $res[$attr] = array_merge($res[$attr] ?? [], [$prop]);
            }
        }

        foreach (Arr::wrap($this->product->getFields('characteristics', [])) as $field) {
            if (!empty($field['attribute']) && !empty($field['property'])) {
                $attr = transform_str_item($field['attribute'], ' :');

                if (!empty($attrReplaceNames[$attr])) {
                    $attr = $attrReplaceNames[$attr];
                }

                if (in_array($attr, $attrExceptNames)) {
                    continue;
                }

                $prop = transform_str_item($field['property'], ' :');
                $res[$attr] = array_merge($res[$attr] ?? [], [$prop]);
            }
        }

        $res2 = [];
        foreach ($res as $attr => $props) {
            $res2[$attr] = array_values(array_unique($props));
        }
        ksort($res2);

        return $res2;
    }


    /**
     * TODO Optimize
     *
     * @return mixed
     */
    public function getMainProperty()
    {
        if ($attributeId = $this->getAttributeGropedId()) {
            if ($prop = $this->properties->where('attribute_id', $attributeId)->first()) {
                return $prop->load('translations');
            }
        }

        if ($prop = $this->properties->filter(fn($p) => $p->attribute->in_variant)->first()) {
            return $prop->load('translations');
        }

        return $this->properties->first()?->load('translations');
    }

//    public function getPropertyByType(string $type, string $column = null)
//    {
//        $value = $this->properties->where('type', $type)->first();
//
//        return $column ? optional($value)->{$column} : $value;
//    }
//
//    public function isHasProperties($need)
//    {
//        return count(array_intersect($this->properties->pluck('id')->toArray(), is_array($need) ? $need : [$need]));
//    }
//
//    public function syncProperties(array $attributesValues = [], $syncIfEmpty = true)
//    {
//        $ids = [];
//        foreach ($attributesValues as $attribute => $values) {
//            if (! empty($values)) {
//                $values = is_array($values) ? $values : [$values];
//                $ids = array_merge($ids, $values);
//            }
//        }
//        if (count($ids) || $syncIfEmpty) {
//            $this->properties()->sync($ids);
//        }
//    }

}
