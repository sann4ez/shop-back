<?php

namespace App\Actions\Terms;

use App\Models\Post;
use App\Models\Shop\Product;
use App\Models\Term;
use Lorisleiva\Actions\Concerns\AsAction;

final class ReindexTermAction
{
    use AsAction;

    public function handle(Term $term)
    {
        $translateIndexes = [];
        if (!Term::isDisableTrans()) {
            $locales = array_keys($term->getTranslationsArray());

            foreach ($locales as $key) {
                $name = $term->getTranslationsArray()[$key]['name'] ?? '';
                $translateIndexes['name_'.$key] = mb_strtolower($name);
                $body = strip_tags($term->getTranslationsArray()[$key]['body'] ?? '');
                $translateIndexes['q_'.$key] = mb_strtolower($name . ' ' . $body);
            }
        } else {
            $locales = $term->domain ? $term->domain->getLocales() : [app()->getLocale()];

            foreach ($locales as $key) {
                $translateIndexes['name_'.$key] = mb_strtolower($term->name);
                $translateIndexes['q'.$key] = mb_strtolower($term->name . ' ' . strip_tags($term->body ?: ''));
            }
        }

        $term->setAttribute('index', [
                'domain' => $term->domain_id,
                //            'name' => mb_strtolower($term->name),
                //            'q' => mb_strtolower($term->name . ' ' . strip_tags($term->body)),
                'created_at' => $term->created_at,
            ] + $translateIndexes)->saveQuietly();
    }
}