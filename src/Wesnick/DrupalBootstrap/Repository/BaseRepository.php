<?php

namespace Wesnick\DrupalBootstrap\Wrapper;


/**
 * Class BaseRepository
 * 
 * @author Wesley O. Nichols <wesley.o.nichols@gmail.com>
 */
abstract class BaseRepository implements EntityRepository
{

    protected $type;
    protected $bundle;

    /**
     * @var \EntityFieldQuery
     */
    protected $query = null;

    function __construct($bundle, $type)
    {
        $this->bundle = $bundle;
        $this->type = $type;
    }

    /**
     * @param \EntityFieldQuery $query
     */
    public function setQuery($query)
    {
        $this->query = $query;
    }

    private function checkQuery()
    {
        return null !== $this->query;
    }

    /**
     * Finds an entity by its primary key / identifier.
     *
     * @param int $id The identifier.
     *
     * @return EntityWrapper The object.
     */
    public function find($id)
    {
//        $this->query->
        // TODO: Implement find() method.
    }

    /**
     * Finds all entities in the repository.
     *
     * @return EntityWrapper[] The objects.
     */
    public function findAll()
    {
        // TODO: Implement findAll() method.
    }

    /**
     * Finds objects by a set of criteria.
     *
     * Optionally sorting and limiting details can be passed. An implementation may throw
     * an UnexpectedValueException if certain values of the sorting or limiting details are
     * not supported.
     *
     * @param array $criteria
     * @param array|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return array The objects.
     *
     * @throws \UnexpectedValueException
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        // TODO: Implement findBy() method.
    }

    /**
     * Finds a single object by a set of criteria.
     *
     * @param array $criteria The criteria.
     *
     * @return object The object.
     */
    public function findOneBy(array $criteria)
    {

        // TODO: Implement findOneBy() method.
    }

    /**
     * Returns the class name of the object managed by the repository.
     *
     * @return string
     */
    public function getClassName()
    {
        return 'Wesnick\DrupalBootstrap\Wrapper\EntityWrapper';
    }


}
