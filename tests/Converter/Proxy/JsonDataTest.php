<?php
declare(strict_types=1);

namespace Smile\Anonymizer\Tests\Converter\Proxy;

use Smile\Anonymizer\Converter\Proxy\JsonData;
use Smile\Anonymizer\Tests\Converter\Dummy;
use Smile\Anonymizer\Tests\TestCase;

class JsonDataTest extends TestCase
{
    /**
     * Test the converter.
     */
    public function testConverter()
    {
        $parameters = [
            'converters' => [
                'customer.firstname' => new Dummy(),
                'customer.lastname' => new Dummy(),
                'customer.notExists' => new Dummy(), // should not trigger an exception
            ],
        ];

        $converter = new JsonData($parameters);

        $value = $converter->convert($this->getJsonData());
        $this->assertSame($this->getExpectedData(), $value);
    }

    /**
     * Check if the converter ignores the value when it is not a JSON-encoded array.
     */
    public function testInvalidJsonData()
    {
        $jsonData = json_encode('stringValue');

        $parameters = [
            'converters' => ['email' => new Dummy()]
        ];

        $converter = new JsonData($parameters);

        $value = $converter->convert($jsonData);
        $this->assertSame($jsonData, $value);
    }

    /**
     * Check if an exception is thrown when the converters are not set.
     *
     * @expectedException \InvalidArgumentException
     */
    public function testConvertersNotSet()
    {
        new JsonData([]);
    }

    /**
     * Get the JSON data to anonymize.
     *
     * @return string
     */
    private function getJsonData(): string
    {
        return json_encode([
            'customer' => [
                'firstname' => 'John',
                'lastname' => 'Doe',
            ],
        ]);
    }

    /**
     * Get the expected anonymized data.
     *
     * @return string
     */
    private function getExpectedData(): string
    {
        return json_encode([
            'customer' => [
                'firstname' => 'test_John',
                'lastname' => 'test_Doe',
            ],
        ]);
    }
}