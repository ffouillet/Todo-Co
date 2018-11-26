<?php
namespace App\Repository;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TaskRepository extends PagineableRepository {

    public function findAllPaginated($pageNumber = 1, $limitPerPage = 5, $orderAttribute = 'createdAt', $orderValue = 'DESC')
    {
        $entityAlias = 't';

        $qb = $this->createQueryBuilder($entityAlias)
            ->select('t')
            ->orderBy($entityAlias.'.'.$orderAttribute, $orderValue);

        $query = $qb->getQuery();

        $pagination = $this->paginator->paginate($query, $pageNumber, $limitPerPage);

        return $pagination;
    }
}