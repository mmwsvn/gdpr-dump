<?php
declare(strict_types=1);

namespace Smile\Anonymizer\Tests\Converter;

use Faker\Factory as FakerFactory;
use Smile\Anonymizer\Converter\Faker;
use Smile\Anonymizer\Tests\TestCase;

class FakerTest extends TestCase
{
    /**
     * Test the converter.
     */
    public function testConverter()
    {
        $parameters = [
            'faker' => FakerFactory::create(),
            'formatter' => 'numberBetween',
            'arguments' => [1, 1],
        ];

        $converter = new Faker($parameters);

        $value = $converter->convert('notAnonymized');
        $this->assertSame(1, $value);
    }

    /**
     * Test the use of placeholder values.
     */
    public function testValuePlaceholder()
    {
        $parameters = [
            'faker' => FakerFactory::create(),
            'formatter' => 'numberBetween',
            'arguments' => ['{{value}}', '{{value}}'],
        ];

        $converter = new Faker($parameters);

        $value = $converter->convert(1);
        $this->assertSame(1, $value);
    }

    /**
     * Test if an exception is thrown when the Faker provider is not set.
     *
     * @expectedException \InvalidArgumentException
     */
    public function testProviderNotSet()
    {
        $parameters = ['formatter' => 'safeEmail'];
        new Faker($parameters);
    }

    /**
     * Test if an exception is thrown when the Faker formatter is not set.
     *
     * @expectedException \InvalidArgumentException
     */
    public function testFormatterNotSet()
    {
        $parameters = ['faker' => FakerFactory::create()];
        new Faker($parameters);
    }
}