<?php

namespace App\Models;

use App\Models\Traits\HasUuidPrimaryKey;

class Media extends \Spatie\MediaLibrary\MediaCollections\Models\Media
{
    use HasUuidPrimaryKey;
}