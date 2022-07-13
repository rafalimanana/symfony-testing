<?php
namespace App\Tests\Entity;

use App\Entity\InvitationCode;
use App\DataFixtures\InvitationCodeFixtures;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;

class InvitationCodeTest extends KernelTestCase 
{
	protected $databaseTool;

	public function setUp(): void
    {
        parent::setUp();

		$kernel = self::bootKernel();

        $this->databaseTool = $kernel->getContainer()
        	->get(DatabaseToolCollection::class)
        	->get();
    }


	public function getEntity(): InvitationCode
	{
		return (new InvitationCode)
            ->setCode("12345")
            ->setDescription("Description de test")
            ->setExpireAt(new \DateTime())
            ;
	}

	public function assertHasErrors(InvitationCode $code, int $number = 0)
	{
		self::bootKernel();
        $errors = self::$container->get('validator')->validate($code);
        $messages = [];
        foreach ($errors as $error) {
        	$messages[] = $error->getPropertyPath()." => ".$error->getMessage();
        }
		$this->assertCount($number, $errors, implode(", ", $messages));
	}

	public function testValidEntity()
	{
        $this->assertHasErrors($this->getEntity());
	}

	public function testInvalidCodeEntity()
	{
        $this->assertHasErrors(
        	$this->getEntity()->setCode("1a345"),
        	1
        );
        $this->assertHasErrors(
        	$this->getEntity()->setCode("1345"),
        	1
        );
	}

	public function testInvalidBlankCodeEntity()
	{
        $this->assertHasErrors(
        	$this->getEntity()->setCode(""),
        	1
        );
	}

	public function testInvalidBlankDescriptionEntity()
	{
        $this->assertHasErrors(
        	$this->getEntity()->setDescription(""),
        	1
        );
	}
	public function testInvalidUsedCode ()
    {
        // $this->databaseTool->loadFixtureFiles([dirname(__DIR__) . '/fixtures/invitation_codes.yaml']);
		$this->databaseTool->loadFixtures([
			InvitationCodeFixtures::class
		]);//me
        $this->assertHasErrors($this->getEntity()->setCode('54321'), 1);
    }

	protected function tearDown(): void
	{
		parent::tearDown();
		unset($this->databaseTool);
	}
}