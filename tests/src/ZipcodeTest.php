<?php

namespace BlancoHugo\Correios;

use PHPUnit\Framework\TestCase;

class ZipcodeTest extends TestCase
{
    public function validZipcodeProvider(): array
    {
        return [
            'with 8 digits and dash' => ['12345-678'],
            'with 8 digits' => ['12345678'],
        ];
    }

    public function invalidZipcodeProvider(): array
    {
        return [
            'with less than 8 digits' => ['1234567'],
            'with more than 8 digits' => ['123456789'],
            'with space' => ['12345 678']
        ];
    }

    public function untrimmedZipcodeProvider(): array
    {
        return [
            'with spaces after number' => ['12345678   ', '12345678'],
            'with spaces before number' => ['   12345678', '12345678'],
            'with spaces after and before number' => ['   12345678     ', '12345678'],
        ];
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function objectCanBeConstructed()
    {
        $zipcode = new Zipcode('12345-678');
    }

    /**
     * @test
     */
    public function dataCanBeRetrieved()
    {
        $number = '12345-678';
        $zipcode = new Zipcode($number);

        $this->assertEquals($number, $zipcode->getNumber());
    }

    /**
     * @test
     * @dataProvider untrimmedZipcodeProvider
     */
    public function numberShouldBeTrimmed(string $untrimmedNumber, string $trimmedNumber)
    {
        $zipcode = new Zipcode($untrimmedNumber);
        $this->assertEquals($trimmedNumber, $zipcode->getNumber());
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function constructionWithEmptyStringShouldThrowException()
    {
        $zipcode = new Zipcode('');
    }

    /**
     * @test
     * @dataProvider validZipcodeProvider
     */
    public function validZipcodes(string $number)
    {
        $zipcode = new Zipcode($number);
        $this->assertTrue($zipcode->isValid());
    }

    /**
     * @test
     * @dataProvider invalidZipcodeProvider
     */
    public function invalidZipcodes(string $number)
    {
        $zipcode = new Zipcode($number);
        $this->assertFalse($zipcode->isValid());
    }

    /**
     * @test
     */
    public function objectCanBeParsedToString()
    {
        $zipcode = new Zipcode('12345-678');
        $this->assertEquals((string) $zipcode, '12345-678');
    }
}
