<?php

namespace App\Tax;

class Tax
{
    protected $country;
    protected $territory;
    protected $rate;
    protected $total;

    public function __construct($country, $territory)
    {
        $this->country = $country;
        $this->territory = $territory;
        $this->rate = 0;
    }

    public function getRate()
    {   
        $this->rate = 0;

        if ($this->country == 'CA') {
            $rates = [
                'CA' => [
                    'ON' => 13,
                    'QC' => 14.975,
                    'NS' => 15,
                    'NB' => 15,
                    'MB' => 13,
                    'BC' => 12,
                    'PE' => 15,
                    'SK' => 11,
                    'AB' => 5,
                    'NL' => 15,
                    'NT' => 5,
                    'YT' => 5,
                    'NU' => 5,
                ],
            ];

            $this->rate = $rates[$this->country][$this->territory];
        }

        return $this->rate;
    }

    public function calculateTax($total)
    {
        $rate = $this->getRate();
        $tax = $total * $rate / 100;

        return $tax;
    }
}
