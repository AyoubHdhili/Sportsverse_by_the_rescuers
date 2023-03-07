<?php

namespace App\Data;

use App\Entity\Cv;

class SearchData
{
    public ?string $q = "";
    public ?int $max;
    public ?int $min;
}
