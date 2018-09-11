<?php

namespace BlancoHugo\Correios;

use PHPUnit\Framework\TestCase;

class AddressTest extends TestCase
{
    private function getMockedZipcode()
    {
        $zipcode = $this->getMockBuilder(Zipcode::class)
            ->disableOriginalConstructor()
            ->setMethods(['__toString'])
            ->getMock();

        return $zipcode;
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function objectCanBeConstructed()
    {
        $address = new Address(
            '12345-789',
            'Rua da Piraporinha',
            'Sao Paulo',
            'SP',
            'Vila Madalena',
            ['Sala 7']
        );

        return $address;
    }

    /**
     * @test
     * @depends objectCanBeConstructed
     */
    public function dataCanBeRetrieved(Address $address)
    {
        $this->assertEquals('12345-789', $address->getZipcode());
        $this->assertEquals('Rua da Piraporinha', $address->getStreet());
        $this->assertEquals('Sao Paulo', $address->getCity());
        $this->assertEquals('SP', $address->getState());
        $this->assertEquals('Vila Madalena', $address->getDistrict());
        $this->assertEquals(['Sala 7'], $address->getComplement());
    }

    /**
     * @test
     * @depends objectCanBeConstructed
     */
    public function objectCanBeConstructedWithSigepData()
    {
        $data = [
            'street' => 'Rua da Piraporinha',
            'city' => 'Sao Paulo',
            'uf' => 'SP',
            'district' => 'Vila Madalena',
            'complemento' => ['Sala 7'],
            'complemento2' => ['Terceiro andar']
        ];

        $address = Address::fromSigepData($this->getMockedZipcode(), $data);
        $this->assertInstanceOf(Address::class, $address);
    }

    /**
     * @test
     * @depends objectCanBeConstructed
     */
    public function objectCanBeConstructedWithPartialSigepData()
    {
        $data = [
            'city' => 'Sao Paulo',
            'uf' => 'SP'
        ];

        $address = Address::fromSigepData($this->getMockedZipcode(), $data);
        $this->assertInstanceOf(Address::class, $address);
    }
}
