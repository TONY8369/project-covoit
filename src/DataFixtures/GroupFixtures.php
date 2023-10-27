<?php

namespace App\DataFixtures;

use App\Entity\Group;
use Doctrine\Persistence\ObjectManager;

class GroupFixtures 
{
    public function load(ObjectManager $manager): void
    {
        $group = new Group();
        $group->setName("Metal-Group");
        $group->setHeadcount("4");
        $group->setDescription("Un groupe de dev");
        $group->setCreatedAt(new \DateTimeImmutable());

        $manager->persist(($group));
        $manager->flush();
    }
}
