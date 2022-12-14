<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Vehicle;
use App\Form\NewVehicleType;
use App\Repository\CityRepository;
use App\Repository\LotRepository;
use App\Repository\VehicleRepository;
use Doctrine\Persistence\ManagerRegistry;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * @method User getUser()
 */
#[Route('/api/city/{cityId}/lot/{lotId}')]
class VehicleController extends AbstractFOSRestController
{
    private VehicleRepository $vehicleRepository;
    private LotRepository $lotRepository;
    private CityRepository $cityRepository;
    private AuthorizationCheckerInterface $authorizationChecker;

    public function __construct(LotRepository $lotRepository, CityRepository $cityRepository, VehicleRepository $vehicleRepository, AuthorizationCheckerInterface $authorizationChecker) {
        $this->lotRepository = $lotRepository;
        $this->cityRepository = $cityRepository;
        $this->vehicleRepository = $vehicleRepository;
        $this->authorizationChecker = $authorizationChecker;
    }

    #[Route('/vehicle', name: 'app_api_city_lot_vehicle_get_all', methods: ['GET'])]
    public function getAll(int $cityId, int $lotId): Response
    {
        $lot = $this->lotRepository->findOneBy(['city' => $cityId, 'id' => $lotId]);

        if (!$lot) {
            return $this->handleView($this->view(sprintf('No lot found with id %d in city with id %d', $lotId, $cityId), 404));
        }

        $vehicleArray = $this->vehicleRepository->findBy(['lot' => $lotId]);

        $data = [];

        foreach ($vehicleArray as $item) {
            $data[] = [
                'id' => $item->getId(),
                'make' => $item->getMake(),
                'model' => $item->getModel(),
                'dateOfManufacture' => $item->getDateOfManufacture(),
                'fuelType' => $item->getFuelType(),
                'gearbox' => $item->getGearbox(),
                'engineCapacity' => $item->getEngineCapacity(),
                'price' => $item->getPrice(),
            ];
        }

        $view = $this->view($data);

        return $this->handleView($view);
    }

    #[Route('/vehicle/{vehicleId}', name: 'app_api_city_lot_vehicle_get', methods: ['GET'])]
    public function get(int $cityId, int $lotId, int $vehicleId): Response
    {
        $lot = $this->lotRepository->findOneBy(['city' => $cityId, 'id' => $lotId]);

        if (!$lot) {
            return $this->handleView($this->view(sprintf('No lot found with id %d in city with id %d', $lotId, $cityId), 404));
        }

        $vehicle = $this->vehicleRepository->findOneBy(['lot' => $lotId, 'id' => $vehicleId]);

        if (!$vehicle) {
            return $this->handleView($this->view(sprintf('No vehicle found with id %d in lot with id %d', $vehicleId, $lotId), 404));
        }

        $data[] = [
            'id' => $vehicle->getId(),
            'make' => $vehicle->getMake(),
            'model' => $vehicle->getModel(),
            'dateOfManufacture' => $vehicle->getDateOfManufacture(),
            'fuelType' => $vehicle->getFuelType(),
            'gearBox' => $vehicle->getGearbox(),
            'engineCapacity' => $vehicle->getEngineCapacity(),
            'price' => $vehicle->getPrice(),
        ];

        $view = $this->view($data);

        return $this->handleView($view);
    }

    /**
     * @throws \JsonException
     */
    #[Route('/vehicle', name: 'app_api_city_lot_vehicle_new', methods: ['POST'])]
    public function new(int $cityId, int $lotId, ManagerRegistry $managerRegistry, Request $request): Response
    {
        if (!$this->authorizationChecker->isGranted('ROLE_USER') && !$this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            return $this->handleView($this->view('You are not authorized to access this resource', 403));
        }

        $user = $this->getUser();
        $lot = $this->lotRepository->findOneBy(['city' => $cityId, 'id' => $lotId]);

        if (!$lot) {
            return $this->handleView($this->view(sprintf('No lot found with id %d in city with id %d', $lotId, $cityId), 404));
        }

        $vehicle = new Vehicle();

        $form = $this->createForm(NewVehicleType::class, $vehicle);
        $form->submit(json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR));
        $view = $this->view($form);

        if ($form->isSubmitted() && $form->isValid()) {
            $lot = $this->lotRepository->findOneBy(['id' => $lotId]);

            if (!$lot) {
                return $this->handleView($this->view(sprintf('No lot found with id %d', $lotId), 404));
            }

            $vehicle->setLot($lot);
            $vehicle->setOwner($user);

            $entityManager = $managerRegistry->getManager();
            $entityManager->persist($vehicle);
            $entityManager->flush();

            $view = $this->view(null, 204);
        }

        return $this->handleView($view);
    }

    /**
     * @throws \JsonException
     */
    #[Route('/vehicle/{vehicleId}', name: 'app_api_city_lot_vehicle_edit', methods: ['PUT'])]
    public function edit(int $cityId, int $lotId, int $vehicleId, ManagerRegistry $managerRegistry, Request $request): Response
    {
        $lot = $this->lotRepository->findOneBy(['city' => $cityId, 'id' => $lotId]);

        if (!$lot) {
            return $this->handleView($this->view(sprintf('No lot found with id %d in city with id %d', $lotId, $cityId), 404));
        }

        $vehicle = $this->vehicleRepository->findOneBy(['lot' => $lotId, 'id' => $vehicleId]);

        if (!$vehicle) {
            return $this->handleView($this->view(sprintf('No vehicle found with id %d in lot with id %d', $vehicleId, $lotId), 404));
        }

        $form = $this->createForm(NewVehicleType::class, $vehicle);
        $form->submit(json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR));
        $view = $this->view($form);

        if ($form->isSubmitted() && $form->isValid()) {
            $lot = $this->lotRepository->findOneBy(['id' => $lotId]);

            if (!$lot) {
                return $this->handleView($this->view(sprintf('No lot found with id %d', $lotId), 404));
            }

            $vehicle->setLot($lot);

            $entityManager = $managerRegistry->getManager();
            $entityManager->flush();

            $view = $this->view(null, 204);
        }

        return $this->handleView($view);
    }

    #[Route('/vehicle/{vehicleId}', name: 'app_api_city_lot_vehicle_remove', methods: ['DELETE'])]
    public function remove(int $cityId, int $lotId, int $vehicleId, ManagerRegistry $managerRegistry, Request $request): Response
    {
        $lot = $this->lotRepository->findOneBy(['city' => $cityId, 'id' => $lotId]);

        if (!$lot) {
            return $this->handleView($this->view(sprintf('No lot found with id %d in city with id %d', $lotId, $cityId), 404));
        }

        $vehicle = $this->vehicleRepository->findOneBy(['lot' => $lotId, 'id' => $vehicleId]);

        if (!$vehicle) {
            return $this->handleView($this->view(sprintf('No vehicle found with id %d in lot with id %d', $vehicleId, $lotId), 404));
        }

        $entityManager = $managerRegistry->getManager();
        $entityManager->remove($vehicle);
        $entityManager->flush();

        $view = $this->view(null, 204);

        return $this->handleView($view);
    }
}