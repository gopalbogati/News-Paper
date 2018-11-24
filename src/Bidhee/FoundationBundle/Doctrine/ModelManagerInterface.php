<?php
/**
 * @Author Bhaktaraz Bhatta
 */

namespace Bidhee\FoundationBundle\Doctrine;


interface ModelManagerInterface
{
    /**
     * Creates an empty object instance.
     *
     * @return object
     */
    function create();

    /**
     * Saves an object.
     *
     * @param object $object An object instance
     * @param boolean $andFlush Whether to flush the changes (default true)
     *
     * @return void
     */
    function save($object, $andFlush = true);

    /**
     * Removes an object.
     *
     * @param object $object An object instance
     * @param boolean $andFlush Whether to flush the changes (default true)
     *
     * @return void
     */
    function remove($object, $andFlush = true);

    /**
     * Finds many objects by the given criteria.
     *
     * @param array $criteria
     *
     * @return array
     */
    function findBy(array $criteria = array());

    /**
     * Finds one object by the given criteria.
     *
     * @param array $criteria
     *
     * @return object|null
     */
    function findOneBy(array $criteria = array());

    /**
     * Finds an object by its primary key / identifier.
     *
     * @param mixed $id The identifier
     *
     * @return object
     */
    function find($id);

    /**
     * Write all changes to the database.
     *
     * @return void
     */
    function flushAllChanges();

    /**
     * Returns the objects's fully qualified class name.
     *
     * @return string
     */
    function getClass();
}
