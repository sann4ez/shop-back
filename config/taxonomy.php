<?php

return [

    /*
     * This model will be used to Term.
     */
    'term_model' => \App\Models\Term::class,

    /*
     * Apply global scopes for model terms.
     */
    'term_prepare_scopes' => [
        \App\Models\Scopes\WeightScope::class,
    ],
];
