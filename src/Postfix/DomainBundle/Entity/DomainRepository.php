<?php

namespace Postfix\DomainBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * DomainRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DomainRepository extends EntityRepository
{
	public function search($search)
	{
		return $this->createQueryBuilder('a')
								->leftJoin('a.author', 'u')
								->addSelect('u')
								->leftJoin('a.categories', 'c')
								->addSelect('c')
								->where('a.title LIKE :search')
								->orWhere('a.content LIKE :search')
								->orWhere('u.username LIKE :search')
								->setParameter('search', '%' . $search . '%')
								->orderBy('a.create' , 'DESC')
								->getQuery()
								->getResult();
	}
	public function paginedList($limit, $page)
	{
		if ($page < 1) {
			throw new \InvalidArgumentException('L\'argument $page ne peut être inférieur à 1 (valeur : "'.$page.'").');
		}

		$query = $this->createQueryBuilder('a')
						->leftJoin('a.categories', 'c')
						->addSelect('c')
						->orderBy('a.create', 'DESC')
						->getQuery();

		$query->setFirstResult(($page-1) * $limit)
		->setMaxResults($limit);

		return new Paginator($query);
	}
	public function paginedListByCategory($limit, $page, $category)
	{
		if ($page < 1) {
			throw new \InvalidArgumentException('L\'argument $page ne peut être inférieur à 1 (valeur : "'.$page.'").');
		}

		$query = $this->createQueryBuilder('a')
						->leftJoin('a.categories', 'c')
						->addSelect('c')
						->where(':category MEMBER OF a.categories')
						->setParameter('category', $category)
						->orderBy('a.create', 'DESC')
						->getQuery();

		$query->setFirstResult(($page-1) * $limit)
		->setMaxResults($limit);

		return new Paginator($query);
	}
}