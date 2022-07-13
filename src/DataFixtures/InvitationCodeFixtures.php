<?php

namespace App\DataFixtures;

use App\Entity\InvitationCode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class InvitationCodeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $code = (new InvitationCode)
            ->setDescription("Code de test")
            ->setCode("54321")
            ->setExpireAt(new \DateTime("+1 year"));
        $manager->persist($code);
        $manager->flush();
    }
}
