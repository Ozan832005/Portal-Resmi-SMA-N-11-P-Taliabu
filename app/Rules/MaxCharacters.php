<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MaxCharacters implements Rule
{
    protected $max;

    public function __construct($max = 50)
    {
        $this->max = $max;
    }

    public function passes($attribute, $value)
    {
        return strlen(preg_replace('/\s+/', ' ', $value)) <= $this->max;
    }

    public function message()
    {
        return "Karakter yang dimasukan tidak boleh lebih besar dari {$this->max}.";
    }
}
