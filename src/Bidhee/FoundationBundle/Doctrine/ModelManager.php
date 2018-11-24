<?php
/**
 * @Author Bhaktaraz Bhatta
 */

namespace Bidhee\FoundationBundle\Doctrine;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

abstract class ModelManager implements ModelManagerInterface
{
    /**
     * The fully qualified class name.
     *
     * @var string
     */
    protected $class;
    /**
     * @var EntityManager
     */
    protected $em;
    /**
     * @var EntityRepository
     */
    protected $repository;
    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------
    /**
     * Ctor.
     *
     * @param EntityManager $em An EntityManager instance
     * @param string $class The class name
     */
    public function __construct(EntityManager $em, $class)
    {
        $this->em = $em;
        $this->repository = $em->getRepository($class);
        $metadata = $em->getClassMetadata($class);
        $this->class = $metadata->getName();
    }
    //-------------------------------------------------
    // ModelManagerInterface
    //-------------------------------------------------
    /**
     * {@inheritDoc}
     */
    public function create()
    {
        $class = $this->class;
        $object = new $class();
        return $object;
    }

    /**
     * {@inheritDoc}
     */
    public function save($object, $andFlush = true)
    {
        $this->em->persist($object);
        if (true === $andFlush) {
            $this->flushAllChanges();
        }
    }

    /**
     * {@inheritDoc}
     */
    public function remove($object, $andFlush = true)
    {
        $this->em->remove($object);
        if (true === $andFlush) {
            $this->flushAllChanges();
        }
    }

    /**
     * {@inheritDoc}
     */
    public function findBy(array $criteria = array())
    {
        return $this->repository->findBy($criteria);
    }

    /**
     * {@inheritDoc}
     */
    public function findOneBy(array $criteria = array())
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * {@inheritDoc}
     */
    public function find($id)
    {
        return $this->repository->find($id);
    }

    /**
     * {@inheritDoc}
     */
    public function flushAllChanges()
    {
        $this->em->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getClass()
    {
        return $this->class;
    }
}
