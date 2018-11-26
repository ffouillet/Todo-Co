<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

abstract class PagineableRepository extends ServiceEntityRepository {

    protected $paginator;

    public function __construct(RegistryInterface $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Task::class);

        $this->paginator = $paginator;
    }

    abstract protected function findAllPaginated($pageNumber = 1, $limitPerPage = 5, $orderAttribute, $orderValue);
}