<?php

namespace Doctrine\Bundle\DoctrineBundle\Repository;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityRepository;
use LogicException;
use Symfony\Component\HttpFoundation\Request;

/**
 * Optional EntityRepository base class with a simplified constructor (for autowiring).
 *
 * To use in your class, inject the "registry" service and call
 * the parent constructor. For example:
 *
 * class YourEntityRepository extends ServiceEntityRepository
 * {
 * public function __construct(RegistryInterface $registry)
 * {
 * parent::__construct($registry, YourEntity::class);
 * }
 * }
 */
class ServiceEntityRepository extends EntityRepository implements ServiceEntityRepositoryInterface
{
    /**
     * @param string $entityClass The class name of the entity this repository manages
     */
    public function __construct(ManagerRegistry $registry, $entityClass)
    {
        $manager = $registry->getManagerForClass($entityClass);

        if ($manager === null) {
            throw new LogicException(sprintf(
                'Could not find the entity manager for class "%s". Check your Doctrine configuration to make sure it is configured to load this entity’s metadata.',
                $entityClass
            ));
        }

        parent::__construct($manager, $manager->getClassMetadata($entityClass));
    }
    public function createByRequest(Request $request, &$obj = null ) {
        $ent = $this->getEntityName();
        $obj = $obj ?? new $ent();
        foreach ((new \ReflectionClass($ent))->getProperties() as $field) {
            $field = $field->name;
            if(is_callable([$obj, 'set' . ucfirst($field)]) && $request->query->get($field) !== null) {
                $obj->{'set' . ucfirst($field)}($request->query->get($field));
            }
        }
        $this->getEntityManager()->persist($obj);
        $this->getEntityManager()->flush($obj);
        return $obj;
    }

    public function createByArray(Array $request, &$obj = null ) {
        $ent = $this->getEntityName();
        $obj = $obj ?? new $ent();
        foreach ((new \ReflectionClass($ent))->getProperties() as $field) {
            $field = $field->name;
            if(is_callable([$obj, 'set' . ucfirst($field)]) && isset($request[$field])) {
                $obj->{'set' . ucfirst($field)}($request[$field]);
            }
        }
        $this->getEntityManager()->persist($obj);
        $this->getEntityManager()->flush($obj);
        return $obj;
    }
}