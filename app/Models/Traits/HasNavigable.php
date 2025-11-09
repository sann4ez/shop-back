<?php

namespace App\Models\Traits;

trait HasNavigable
{
    public function getPerPage()
    {
        return request('limit')
            ?: request('per_page')
                ?: session('per_page')
                    ?: $this->perPage;
    }
}
