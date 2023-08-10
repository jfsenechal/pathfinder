<?php

namespace AfmLibre\Pathfinder\Item\Repository;

use AfmLibre\Pathfinder\Doctrine\OrmCrudTrait;
use AfmLibre\Pathfinder\Entity\ItemCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ItemCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method ItemCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method ItemCategory[]    findAll()
 * @method ItemCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemCategoryRepository extends ServiceEntityRepository
{
    use OrmCrudTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ItemCategory::class);
    }

    /**
     * @return ItemCategory[]
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
     * @return ItemCategory[]
     */
    public function findRoots(): array
    {
        return $this->createQb()
            ->andWhere('category.parent IS NULL')
            ->getQuery()->getResult();
    }

    /**
     * @return ItemCategory[]
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
     * @return ItemCategory[]
     */
    public function findChildren(ItemCategory $category): array
    {
        return $this->createQb()
            ->andWhere('category.parent = :cat')
            ->setParameter('cat', $category)
            ->getQuery()->getResult();
    }

    /**
     * @return ItemCategory[]
     */
    public function findAllOrdered(): array
    {
        return $this->createQb()->getQuery()->getResult();
    }

    public function findOneByName(string $name): ?ItemCategory
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
