<?php

namespace StevenBerg\ResponsiveImages\Tests;

use PHPUnit\Framework\TestCase;
use StevenBerg\ResponsiveImages\Image;
use StevenBerg\ResponsiveImages\SizeRange;
use StevenBerg\ResponsiveImages\Square;
use StevenBerg\ResponsiveImages\Tall;
use StevenBerg\ResponsiveImages\Urls\Simple;
use StevenBerg\ResponsiveImages\Values\Gravity;
use StevenBerg\ResponsiveImages\Values\Name;
use StevenBerg\ResponsiveImages\Values\Shape;
use StevenBerg\ResponsiveImages\Values\Size;
use StevenBerg\ResponsiveImages\Wide;

class ImageTest extends TestCase
{
    protected function setUp()
    {
        $this->image = new Image(
            Name::value('test.jpg'),
            Gravity::value('auto'),
            new Simple('https://example.com')
        );
    }

    public function testSource()
    {
        $this->assertEquals(
            'https://example.com/width-100_test.jpg',
            $this->image->source(Size::value(100))
        );
    }

    public function testSourceSet()
    {
        $expected = implode(', ', [
            'https://example.com/width-100_test.jpg 100w',
            'https://example.com/width-200_test.jpg 200w',
            'https://example.com/width-300_test.jpg 300w',
            'https://example.com/width-400_test.jpg 400w',
            'https://example.com/width-500_test.jpg 500w',
            'https://example.com/width-600_test.jpg 600w',
            'https://example.com/width-700_test.jpg 700w',
            'https://example.com/width-800_test.jpg 800w',
            'https://example.com/width-900_test.jpg 900w',
            'https://example.com/width-1000_test.jpg 1000w',
        ]);

        $range = SizeRange::from(100, 1000, 100);

        $this->assertEquals($expected, $this->image->sourceSet($range));
    }

    public function testTag()
    {
        $expected = "<img alt='' sizes='100vw' src='https://example.com/width-100_test.jpg' srcset='https://example.com/width-100_test.jpg 100w, https://example.com/width-200_test.jpg 200w, https://example.com/width-300_test.jpg 300w, https://example.com/width-400_test.jpg 400w, https://example.com/width-500_test.jpg 500w, https://example.com/width-600_test.jpg 600w, https://example.com/width-700_test.jpg 700w, https://example.com/width-800_test.jpg 800w, https://example.com/width-900_test.jpg 900w, https://example.com/width-1000_test.jpg 1000w'>";

        $range = SizeRange::from(100, 1000, 100);
        $defaultSize = Size::value(100);

        $this->assertEquals($expected, $this->image->tag($range, $defaultSize));
    }

    public function testFromShape()
    {
        $name = Name::value('test.jpg');
        $gravity = Gravity::value('auto');
        $maker = new Simple('https://exmaple.com');

        $values = [
            'original' => Image::class,
            'square' => Square::class,
            'tall' => Tall::class,
            'wide' => Wide::class,
        ];

        foreach ($values as $value => $class) {
            $shape = Shape::value($value);

            $this->assertInstanceOf($class, Image::fromShape($shape, $name, $gravity, $maker));
        }
    }
}