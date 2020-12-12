<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class CentsTransformer implements DataTransformerInterface {

    // On price field, when we will receive Data, we will divide by 100 (to get price in euros)
    public function transform($value) 
    {
        if ($value === null) {
            return;
        }
        return $value / 100;
    }

    // When user will send Data, we will multiply by 100 (to send the price in cents on database)
    public function reverseTransform($value)
    {
        if ($value === null) {
            return;
        }
        return $value * 100;
    }

}