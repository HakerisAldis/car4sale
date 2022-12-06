<?php

namespace App\Tests\IntegrationTests;

use App\Entity\City;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManager;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTEncodeFailureException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class CityControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    /**
     * @throws Exception
     * @throws JWTEncodeFailureException
     */
    public function setUp(): void
    {
        $this->client = self::createAuthenticatedClient(['username' => 'admin@test.com', 'password' => 'test123']);
        self::bootKernel();

        $this->truncateEntities();
    }

    /**
     * @throws JWTEncodeFailureException
     */
    protected static function createAuthenticatedClient(array $claims)
    {
        $client = self::createClient();

        $encoder = $client->getContainer()->get(JWTEncoderInterface::class);

        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $encoder->encode($claims)));

        return $client;
    }

    /**
     * @dataProvider getAllCitiesDataProvider
     * @throws \JsonException
     */
    public function testGetAllCities(array $cities): void
    {
        foreach ($cities as $city) {
            $this->client->request('POST', '/api/city', $city);

            self::assertResponseIsSuccessful();
        }

        $this->client->request('GET', '/api/city');
        self::assertResponseIsSuccessful();

        self::assertCount(count($cities), json_decode($this->client->getResponse()->getContent(), false, 512, JSON_THROW_ON_ERROR));
    }

    public function getAllCitiesDataProvider(): array
    {
        return [
            [
                [
                    [
                        'name' => 'Test City 1',
                    ],
                    [
                        'name' => 'Test City 2',
                    ],
                    [
                        'name' => 'Test City 3',
                    ],
                ],
                [
                    [
                        'name' => 'Test City 1',
                    ],
                    [
                        'name' => 'Test City 2',
                    ],
                ],
                [
                    [
                        'name' => 'Test City 1',
                    ],
                ],
                [],
            ]
        ];
    }

    /**
     * @throws Exception
     */
    private function truncateEntities(): void
    {
        $entities = [
            City::class
        ];
        $connection = $this->getEntityManager()->getConnection();
        $databasePlatform = $connection->getDatabasePlatform();

        foreach ($entities as $entity) {
            $query = $databasePlatform->getTruncateTableSQL(
                $this->getEntityManager()->getClassMetadata($entity)->getTableName(),
                cascade: true,
            );

            $connection->executeQuery($query);
        }
    }

    private function getEntityManager(): EntityManager
    {
        return self::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }
}