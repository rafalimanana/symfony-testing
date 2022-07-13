<?php
namespace App\Tests\Repository;

use App\Entity\User;
use App\DataFixtures\UserFixtures;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;

class UserRepositoryTest extends KernelTestCase 
{
	protected $em;
	protected $databaseTool;

	public function setUp(): void
    {
        parent::setUp();

		$kernel = self::bootKernel();

        $this->em = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $this->databaseTool = $kernel->getContainer()
        	->get(DatabaseToolCollection::class)
        	->get();
    }

	public function testCount()
	{
		$this->databaseTool->loadFixtures([
			UserFixtures::class
		]);
		$users = $this->em->getRepository(User::class)->count([]);
		$this->assertEquals(10, $users);
	}

	protected function tearDown(): void
	{
		parent::tearDown();
		unset($this->databaseTool);

		// doing this is recommended to avoid memory leaks
        $this->em->close();
        $this->em = null;
	}
	
}