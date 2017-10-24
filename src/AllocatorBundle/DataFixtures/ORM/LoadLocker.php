<?php
// AllocatorBundle/DataFixtures/ORM/LoadLocker.php

namespace AllocatorBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\FixtureInterface as Fix;
use Doctrine\Common\Persistence\ObjectManager;
use AllocatorBundle\Entity\Locker as Locker;

class LoadCategory implements Fix
{
  // Dans l'argument de la méthode load, l'objet $manager est l'EntityManager
  public function load(ObjectManager $manager)
  {
    // Liste des noms de catégorie à ajouter
    $nums = range ( 300, 700);

    foreach ($nums as $num) {
      // on cree des lockers à la volé
    $locker = new Locker();
    $locker->setLocation('3 étage')->setNumber($num)->setNumberKey($num)->setSite('Rue de paris');


      // On la persiste
      $manager->persist($locker);
    }


    $manager->flush();
  }
}