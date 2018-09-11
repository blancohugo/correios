<?php

namespace BlancoHugo\Correios;

/**
 * Zipcode object used to query
 */
class Zipcode
{
    /**
     * Zipcode number
     *
     * @var string
     */
    protected $number;

    /**
     * Constructor
     *
     * @param string $number
     */
    public function __construct(string $number)
    {
        $number = trim($number);

        if (empty($number)) {
            throw new \InvalidArgumentException('Zipcode cannot be empty');
        }

        $this->number = $number;
    }

    /**
     * Returns zipcode number
     *
     * @return string
     */
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * Check if zipcode number is valid
     *
     * @return boolean
     */
    public function isValid(): bool
    {
        if (preg_match("/^[0-9]{5}[\-]?[0-9]{3}$/", $this->number)) {
            return true;
        }

        return false;
    }

    /**
     * Converts object to string
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->number;
    }
}
