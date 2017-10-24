<?php
// AppBundle/Repository/UserRepository.php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;


class UserRepository extends EntityRepository
{
    public function findUserLockers()
    {
        $qb = $this
            ->createQueryBuilder('user')
            ->leftJoin('AllocatorBundle\Entity\Locker', 'locker')
            ->addSelect('locker.id')
            ->addSelect('locker.user')
            ->addSelect('locker.number')
            ->addSelect('locker.location')
            ->addSelect('locker.site')
            ->addSelect('locker.numberKey')
            ->where('user.id = locker.user');

        return $qb
            ->getQuery()
            ->getResult();
    }


}
