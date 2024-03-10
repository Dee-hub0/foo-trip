<?php
namespace App\Command;

use App\Repository\DestinationRepository;
use App\Service\ImageUploader;
use App\Entity\Destination;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Contracts\HttpClient\HttpClientInterface;

// the name of the command is what users type after "php bin/console"
#[AsCommand(name: 'app:csv-destinations')]
class DestinationListCommand extends Command
{
    public function __construct(
        private HttpClientInterface $client, private ImageUploader $imageUploader
    ) {
        parent::__construct();
    }

    

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $headers = array(        
            'Content-Type: text/csv',
            'Accept: text/csv'
        );
        // Call the Api end point to retreive all destinations
        $response = $this->client->request(
            'GET',
            'https://127.0.0.1:8000/api/destinations',[
                'verify_peer' => false,
                'headers' => $headers,
            ]
        );
        // Writes The Csv File content retreived by the Api inside the 'destnation.csv' File
        $fileHandler = fopen('destinations.csv', 'w');
        fwrite($fileHandler, $response->getContent());
        // Open the file for read/write and lunch windows open file cmd
        $fileHandler = fopen('D:\workspace\the_foo_trip\destinations.csv', 'r+');
        exec('start excel.exe D:\workspace\the_foo_trip\destinations.csv');

        // Encode the csv data to an array
        $encoder = new CsvEncoder();
        $arrayContent = $encoder->decode($response->getContent(), 'csv',[]);
        
        $encoders = [new CsvEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        // Render a Table with all destination in the console
        $table = new Table($output);
        $table
            ->setHeaders(['Name', 'Description', 'Price', 'Duration']);
            foreach ($arrayContent  as $key => $value) {
                // Denormalizes the encoded array to a 'Destination' Object
                $destination = $serializer->denormalize($value, Destination::class);
                $table
                    ->addRow([''.$destination->getName().'', ''.$destination->getDescription().'',
                    ''.$destination->getPrice().'', ''.$destination->getDuration().' DAYS(S)']);
                    if($key !== array_key_last($arrayContent))
                        $table->addRow(new TableSeparator());
                           
            }
        $table->setColumnMaxWidth(1, 50);    
        $table->setStyle('box');
        $table->render();

        
       return Command::SUCCESS;
    }
}