<?php

namespace Art4\JsonApiClient\Resource;

use Art4\JsonApiClient\Utils\AccessTrait;
use Art4\JsonApiClient\Utils\DataContainer;
use Art4\JsonApiClient\Utils\FactoryManagerInterface;
use Art4\JsonApiClient\Exception\AccessException;
use Art4\JsonApiClient\Exception\ValidationException;

/**
 * Resource Object
 *
 * @see http://jsonapi.org/format/#document-resource-objects
 */
final class IdentifierCollection implements IdentifierCollectionInterface, ResourceInterface
{
	use AccessTrait;

	/**
	 * @var DataContainerInterface
	 */
	protected $container;

	/**
	 * @var FactoryManagerInterface
	 */
	protected $manager;

	/**
	 * @param array $resources The resources as array
	 *
	 * @return self
	 *
	 * @throws ValidationException
	 */
	public function __construct($resources, FactoryManagerInterface $manager)
	{
		if ( ! is_array($resources) )
		{
			throw new ValidationException('Resources for a collection has to be in an array, "' . gettype($resources) . '" given.');
		}

		$this->manager = $manager;

		$this->container = new DataContainer();

		if ( count($resources) > 0 )
		{
			foreach ($resources as $resource)
			{
				$this->container->set('', $this->parseResource($resource));
			}
		}

		return $this;
	}

	/**
	 * Get a value by the key of this document
	 *
	 * @param string $key The key of the value
	 * @return mixed The value
	 */
	public function get($key)
	{
		try
		{
			return $this->container->get($key);
		}
		catch (AccessException $e)
		{
			throw new AccessException('"' . $key . '" doesn\'t exist in this resource.');
		}
	}

	/**
	 * Generate a new resource from an object
	 *
	 * @param object $data The resource data
	 * @return ResourceInterface The resource
	 */
	protected function parseResource($data)
	{
		return $resource = $this->manager->getFactory()->make(
			'Resource\Identifier',
			[$data, $this->manager]
		);
	}

	/**
	 * Is this Resource a null resource?
	 *
	 * @return boolean false
	 */
	public function isNull()
	{
		return false;
	}

	/**
	 * Is this Resource an identifier?
	 *
	 * @return boolean false
	 */
	public function isIdentifier()
	{
		return false;
	}

	/**
	 * Is this Resource an item?
	 *
	 * @return boolean false
	 */
	public function isItem()
	{
		return false;
	}

	/**
	 * Is this Resource a collection?
	 *
	 * @return boolean true
	 */
	public function isCollection()
	{
		return true;
	}
}
