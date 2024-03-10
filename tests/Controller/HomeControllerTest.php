<?php

namespace App\Test\Controller;

use App\Entity\Destination;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/';

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

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);
        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Destinations Home');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    /**
     * @dataProvider urlProvider
     */
    public function testPageIsSuccessful($url): void
    {
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('admin@mail.com');
        $this->client->loginUser($testUser);
        $fixture = new Destination();
        $fixture->setName('Destination1');
        $fixture->setDescription('Description');
        $fixture->setPrice(20.4);
        $fixture->setDuration(4);
        $fixture->setImage('image');
        $fixture->setType('other');

        $this->manager->persist($fixture);
        $this->manager->flush();
        
        $this->client->request('GET', sprintf($url , $fixture->getId()));

        $this->assertResponseIsSuccessful();
    }

    public function urlProvider(): \Generator
    {
        yield ['/'];
        yield ['/destination/%u'];
        yield ['/login'];
        yield ['/admin/destination/'];
        yield ['/admin/destination/new'];
        yield ['/admin/destination/%u/edit'];
    }




    public function testDetails(): void
    {
        //$this->markTestIncomplete();
        $fixture = new Destination();
        $fixture->setName('Destination1');
        $fixture->setDescription('Description');
        $fixture->setPrice(20.4);
        $fixture->setDuration(4);
        $fixture->setImage('image');
        $fixture->setType('other');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s/destination/', $this->path , $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Destination');

        // Use assertions to check that the properties are properly displayed.
    }

}
