<?php
namespace App\Repository;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TaskRepository extends PagineableRepository {

    public function findByIsDonePaginated($pageNumber, $limitPerPage, $isDone = null, $orderAttribute = 'createdAt', $orderValue = 'DESC') {
        $entityAlias = 't';

        $qb = $this->createQueryBuilder($entityAlias)
            ->select('t')
            ->orderBy($entityAlias.'.'.$orderAttribute, $orderValue);

        // Also get task's owner.
        $qb->join('t.author', 'author');
        $qb->addSelect('author');

        // Get completed tasks (or not)
        if (true === $isDone) {
            $qb->where($entityAlias.'.isDone = true');
        } else if (false === $isDone){
            $qb->where($entityAlias.'.isDone = false');
        }

        $query = $qb->getQuery();

        $pagination = $this->paginator->paginate($query, $pageNumber, $limitPerPage);

        return $pagination;
    }
}