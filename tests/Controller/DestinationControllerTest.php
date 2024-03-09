<?php

namespace App\Test\Controller;

use App\Entity\Destination;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DestinationControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/admin/destination/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Destination::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
        
    }

    public function logUser(){

        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('admin@mail.com');

        // simulate $testUser being logged in
        $this->client->loginUser($testUser);
    }

    public function testIndex(): void
    {
        $this->logUser();
        $crawler = $this->client->request('GET', $this->path);
        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Destination index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
       // $this->markTestIncomplete();
       $this->logUser();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $uploadedFile = new UploadedFile(
            __DIR__.'/../../public/tests/destinationImg1Test.jpg',
            'destinationImg1Test.jpg'
        );


        $this->client->submitForm('Save', [
            'destination[name]' => 'Testing',
            'destination[description]' => 'Testing',
            'destination[price]' => '55.00',
            'destination[duration]' => '3',
            'destination[type]' => 'honey_moon',
            'destination[image]' => $uploadedFile,
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }


    public function testEdit(): void
    {
        //$this->markTestIncomplete();
        $this->logUser();
        $fixture = new Destination();
        $fixture->setName('Value');
        $fixture->setDescription('Value');
        $fixture->setPrice(100.00);
        $fixture->setDuration(5);
        $fixture->setImage('Value');
        $fixture->setType('honey_moon');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'destination[name]' => 'editValue',
            'destination[description]' => 'editValue',
            'destination[price]' => '45.00',
            'destination[duration]' => '6',
            'destination[image]' => 'editValue',
            'destination[type]' => 'honey_moon',
        ]);

        self::assertResponseRedirects('/admin/destination/');

        $fixture = $this->repository->findAll();

        // self::assertSame('Value', $fixture[0]->getName());
        // self::assertSame('Somethingdesc', $fixture[0]->getDescription());
        // self::assertSame('66.00', $fixture[0]->getPrice());
        // self::assertSame('7', $fixture[0]->getDuration());
        // self::assertSame('other', $fixture[0]->getType());
        // self::assertSame('img', $fixture[0]->getImage());
    }

    public function testRemove(): void
    {
        //$this->markTestIncomplete();
        $this->logUser();
        $fixture = new Destination();
        $fixture->setName('ValueDelete');
        $fixture->setDescription('ValueDelete');
        $fixture->setPrice(100.00);
        $fixture->setDuration(5);
        $fixture->setImage('ValueDelete');
        $fixture->setType('honey_moon');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/admin/destination/');
        self::assertSame(0, $this->repository->count([]));
    }
}
