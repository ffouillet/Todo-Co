<?php
namespace App\Repository;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TaskRepository extends PagineableRepository {

    public function findAllPaginated($pageNumber = 1, $limitPerPage = 5)
    {
        $qb = $this->createQueryBuilder('t')
            ->select('t');

        $query = $qb->getQuery();

        $pagination = $this->paginator->paginate($query, $pageNumber, $limitPerPage);

        return $pagination;
    }
}