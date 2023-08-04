<?php

namespace AfmLibre\Pathfinder\Armor\Repository;

use AfmLibre\Pathfinder\Doctrine\OrmCrudTrait;
use AfmLibre\Pathfinder\Entity\ArmorCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ArmorCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method ArmorCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method ArmorCategory[]    findAll()
 * @method ArmorCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArmorCategoryRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ArmorCategory::class);
    }

    /**
     * @return ArmorCategory[]
     */
    public function search(iterable $args): array
    {
        $nom = $args['name'] ?? null;
        $qb = $this->createQb();

        if ($nom) {
            $qb->andWhere('category.name LIKE :name')
                ->setParameter('name', '%'.$nom.'%');
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * @return ArmorCategory[]
     */
    public function findRoots(): array
    {
        return $this->createQb()
            ->andWhere('category.parent IS NULL')
            ->getQuery()->getResult();
    }

    /**
     * @return ArmorCategory[]
     */
    public function findAllGroupByParent(): array
    {
        $categories = [];
        $roots = $this->findRoots();
        foreach ($roots as $category) {
            $children = $this->findChildren($category);
            $categories[$category->name] = $children;
        }

        return $categories;
    }

    public function findRootsQb(): QueryBuilder
    {
        return $this->createQb()
            ->andWhere('category.parent IS NULL');
    }

    public function findForListQb(): QueryBuilder
    {
        return $this->createQb();
    }

    /**
     * @return ArmorCategory[]
     */
    public function findChildren(ArmorCategory $category): array
    {
        return $this->createQb()
            ->andWhere('category.parent = :cat')
            ->setParameter('cat', $category)
            ->getQuery()->getResult();
    }

    /**
     * @return ArmorCategory[]
     */
    public function findAllOrdered(): array
    {
        return $this->createQb()->getQuery()->getResult();
    }

    public function findOneByName(string $name): ?ArmorCategory
    {
        return $this->createQb()
            ->andWhere('category.name = :name')
            ->setParameter('name', $name)
            ->getQuery()->getOneOrNullResult();
    }

    private function createQb(): QueryBuilder
    {
        return $this->createQueryBuilder('category')
            ->leftJoin('category.parent', 'parent', 'WITH')
            ->addSelect('parent')
            ->addOrderBy('category.name', Criteria::ASC);
    }

}
