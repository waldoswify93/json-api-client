<?php

namespace Art4\JsonApiClient\Resource\Tests;

use Art4\JsonApiClient\Resource\Collection;
use Art4\JsonApiClient\Tests\Fixtures\JsonValueTrait;

class CollectionTest extends \PHPUnit_Framework_TestCase
{
	use JsonValueTrait;

	/**
	 * @test create with empty array
	 */
	public function testCreateWithEmptyArray()
	{
		$collection = new Collection(array());

		$this->assertInstanceOf('Art4\JsonApiClient\Resource\Collection', $collection);

		$this->assertTrue($collection->isCollection());
		$this->assertTrue( count($collection->asArray()) === 0);
	}

	/**
	 * @test create with identifier object
	 */
	public function testCreateWithIdentifier()
	{
		$object = new \stdClass();
		$object->type = 'type';
		$object->id = 789;
		$object->meta = new \stdClass();

		$collection = new Collection(array($object));

		$this->assertInstanceOf('Art4\JsonApiClient\Resource\Collection', $collection);

		$this->assertTrue($collection->isCollection());
		$this->assertTrue( count($collection->asArray()) === 1);

		foreach ($collection->asArray() as $resource)
		{
			$this->assertInstanceOf('Art4\JsonApiClient\Resource\ResourceInterface', $resource);
			$this->assertInstanceOf('Art4\JsonApiClient\Resource\Identifier', $resource);
		}
	}

	/**
	 * @test create with item object
	 */
	public function testCreateWithItem()
	{
		$object = new \stdClass();
		$object->type = 'type';
		$object->id = 789;
		$object->attributes = new \stdClass();
		$object->relationships = new \stdClass();
		$object->links = new \stdClass();

		$collection = new Collection(array($object));

		$this->assertInstanceOf('Art4\JsonApiClient\Resource\Collection', $collection);

		$this->assertTrue($collection->isCollection());
		$this->assertTrue( count($collection->asArray()) === 1);

		foreach ($collection->asArray() as $resource)
		{
			$this->assertInstanceOf('Art4\JsonApiClient\Resource\ResourceInterface', $resource);
			$this->assertInstanceOf('Art4\JsonApiClient\Resource\Item', $resource);
		}
	}

	/**
	 * @dataProvider jsonValuesProvider
	 */
	public function testCreateWithoutArrayThrowsException($input)
	{
		// Input must be an array
		if ( gettype($input) === 'array' )
		{
			$this->assertInstanceOf('Art4\JsonApiClient\Resource\Collection', new Collection($input));

			return;
		}

		$this->setExpectedException('Art4\JsonApiClient\Exception\ValidationException');

		$collection = new Collection($input);
	}
}
