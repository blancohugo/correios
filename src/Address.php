<?php

namespace BlancoHugo\Correios;

/**
 * An address data object created from the sigep response
 */
class Address
{
    /**
     * Address zipcode
     *
     * @var string
     */
    protected $zipcode;

    /**
     * Street
     *
     * @var string
     */
    protected $street;

    /**
     * City name
     *
     * @var string
     */
    protected $city;

    /**
     * State name (UF)
     *
     * @var string
     */
    protected $state;

    /**
     * Address district
     *
     * @var string
     */
    protected $district;

    /**
     * Address complement (aparment number, room number etc.)
     *
     * @var array
     */
    protected $complement;

    /**
     * Constructor
     *
     * @param string $zipcode
     * @param string|null $street
     * @param string|null $city
     * @param string|null $state
     * @param string|null $district
     * @param array $complement
     */
    public function __construct(
        string $zipcode,
        ?string $street,
        ?string $city,
        ?string $state,
        ?string $district,
        array $complement = []
    ) {
        $this->zipcode = $zipcode;
        $this->street = $street;
        $this->city = $city;
        $this->state = $state;
        $this->district = $district;
        $this->complement = $complement;
    }

    /**
     * Create an address object from sigep response array
     *
     * @param Zipcode $zipcode
     * @param array $data
     * @return self
     */
    public static function fromSigepData(Zipcode $zipcode, array $data): self
    {
        $complement = array_values(
            array_filter([
                $data['complemento'] ?? [],
                $data['complemento2'] ?? []
            ])
        );

        return new Address(
            (string) $zipcode,
            $data['end'] ?? null,
            $data['cidade'] ?? null,
            $data['uf'] ?? null,
            $data['bairro'] ?? null,
            $complement
        );
    }

    /**
     * Returns the zipcode number
     *
     * @return string
     */
    public function getZipcode(): string
    {
        return $this->zipcode;
    }

    /**
     * Returns the address street
     *
     * @return string|null
     */
    public function getStreet(): ?string
    {
        return $this->street;
    }

    /**
     * Returns the city name
     *
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * Returns the state name
     *
     * @return string|null
     */
    public function getState(): ?string
    {
        return $this->state;
    }

    /**
     * Returns the address district
     *
     * @return string|null
     */
    public function getDistrict(): ?string
    {
        return $this->district;
    }

    /**
     * Returns the address complement
     *
     * @return array
     */
    public function getComplement(): array
    {
        return $this->complement;
    }
}
