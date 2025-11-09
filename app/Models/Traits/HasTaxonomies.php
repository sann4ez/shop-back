<?php

namespace App\Models\Traits;

use App\Models\Term;

trait HasTaxonomies
{
    use \Fomvasss\SimpleTaxonomy\Models\Traits\HasTaxonomies;

    /**
     * @param array $vocabularyTerms
     * @param array $forceAddedTerms
     * @return array
     */
    public function syncTerms(array $vocabularyTerms = [], array $forceAddedTerms = []): array
    {
        $ids = [];
        $tagIds = [];

        foreach ($vocabularyTerms as $vocabulary => $terms) {
            $terms = \Arr::wrap($terms);

            if ($vocabulary === 'tags') {
                //$this->txTerms()->detach($post->txTerms->pluck('id')->toArray());
                foreach ($terms as $value) {
                    // добавляємо тег по Імені
                    if (is_string($value) || empty($value['id']) && !empty($value['name'])) {
                        $name = is_string($value) ? $value : $value['name'];
                        $term = Term::whereVocabulary(Term::VOCABULARY_TAGS)->whereTranslation('name', trim($name))->first() ?: Term::create([
                            'name' => trim($name),
                            'vocabulary' => Term::VOCABULARY_TAGS,
                        ]);

                        $tagIds[] = $term->id;
                    }
                    // добавляємо тег по ID
                    elseif (!empty($value['id'])) {
                        $tagIds[] = $value['id'];
                    }
                }
                //$this->terms()->attach($tagIds);
            } else {
                $ids = array_merge($ids, $terms);
            }
        }

        $ids = array_unique(array_merge($ids, $tagIds, array_filter($forceAddedTerms)));

        // Sync
        return $this->terms()->sync($ids);
    }

}
