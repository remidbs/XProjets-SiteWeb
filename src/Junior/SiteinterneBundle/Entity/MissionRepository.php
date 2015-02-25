<?php

namespace Junior\SiteinterneBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Junior\SiteinterneBundle\Entity\User;


/**
 * MissionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MissionRepository extends EntityRepository
{
	public function getMissionAvecCategories()
	{
		$qb=$this
			->createQueryBuilder('a')
			->orderBy('a.numSiaje', 'ASC')
			->leftJoin('a.categories', 'c')
			->addSelect('c')
			->leftJoin('a.competences', 'co')
			->addSelect('co')
		;
		
		return $qb
				->getQuery()
				->getResult()
		;
	}
	
	public function getMissionEnTantQueCDP(\Junior\SiteinterneBundle\Entity\User $user = null)
	{
		$qb=$this
			->createQueryBuilder('a')
			->leftJoin('a.chefDeProjet', 'cdp')
			->addSelect('cdp')
			->Where('cdp.id = :id')
			->setParameter('id', $user->getId())
		;
		
		return $qb
				->getQuery()
				->getResult()
		;
	}
	
	public function getMissionEnTantQuIntervenant(\Junior\SiteinterneBundle\Entity\User $user = null)
	{
		$qb=$this
			->createQueryBuilder('a');
		$qb->leftJoin('a.intervenants', 'int')
			->addSelect('int')
			->orWhere($qb->expr()->in('int.id', $user->getId()))
		;
		
		return $qb
				->getQuery()
				->getResult()
		;
	}
	
	public function getProchainNum()
	{
		$missions=$this
			->createQueryBuilder('a')
			->getQuery()
			->getResult()
		;
		$year = date("y")*1000;
		$max = $year;
		foreach($missions as $m){
			if($max < $m->getNumSiaje() && ($m->getNumSiaje()-$year) < 1000 ){
				$max = $m->getNumSiaje();
			}
		}
		return $max+1;
	}
	
	public function getDernierReferent()
	{
		$derniereMission=$this
			->createQueryBuilder('a')
			->orderBy('a.ajouteLe', 'DESC')
			->setMaxResults(1)
			->getQuery()
			->getSingleResult()
		;
		if($derniereMission){
			$dernierReferent= $derniereMission->getReferent();
		}
		
		return $dernierReferent;
		
	}
}
