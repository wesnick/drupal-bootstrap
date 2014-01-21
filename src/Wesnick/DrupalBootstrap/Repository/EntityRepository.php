<?php

namespace Wesnick\DrupalBootstrap\Wrapper;


/**
 * Class EntityRepository
 * 
 * @author Wesley O. Nichols <wesley.o.nichols@gmail.com>
 */
interface EntityRepository
{

    /**
     * Finds an entity by its primary key / identifier.
     *
     * @param int $id The identifier.
     *
     * @return EntityWrapper The object.
     */
    public function find($id);

    /**
     * Finds all entities in the repository.
     *
     * @return EntityWrapper[] The objects.
     */
    public function findAll();

    /**
     * Finds objects by a set of criteria.
     *
     * Optionally sorting and limiting details can be passed. An implementation may throw
     * an UnexpectedValueException if certain values of the sorting or limiting details are
     * not supported.
     *
     * @param array      $criteria
     * @param array|null $orderBy
     * @param int|null   $limit
     * @param int|null   $offset
     *
     * @return array The objects.
     *
     * @throws \UnexpectedValueException
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null);

    /**
     * Finds a single object by a set of criteria.
     *
     * @param array $criteria The criteria.
     *
     * @return object The object.
     */
    public function findOneBy(array $criteria);

    /**
     * Returns the class name of the object managed by the repository.
     *
     * @return string
     */
    public function getClassName();

}
