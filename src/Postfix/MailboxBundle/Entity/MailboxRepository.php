<?php

namespace Postfix\MailboxBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * MailboxRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MailboxRepository extends EntityRepository
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
	public function secondaryAliasExist($alias , $domain , $mailbox)
	{
		return $this->createQueryBuilder('m')
								->leftJoin('m.redirects', 'r')
								->addSelect('r')
								->where('m.alias = :alias_mailbox or r.source LIKE :alias_redirect')
								->andWhere('m.domain = :domain')
								->andWhere('r.mailbox = :mailbox')
								->setParameter('domain', $domain)
								->setParameter('mailbox', $mailbox)
								->setParameter('alias_mailbox', $alias)
								->setParameter('alias_redirect', '%' . $alias . '%')
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