<?php

namespace App\Controller;

use App\Entity\Lot;
use App\Form\NewLotType;
use App\Repository\CityRepository;
use App\Repository\LotRepository;
use Doctrine\Persistence\ManagerRegistry;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/city/{cityId}')]
class LotController extends AbstractFOSRestController
{
    private LotRepository $lotRepository;
    private CityRepository $cityRepository;

    public function __construct(LotRepository $lotRepository, CityRepository $cityRepository) {
        $this->lotRepository = $lotRepository;
        $this->cityRepository = $cityRepository;
    }

    #[Route('/lot', name: 'app_api_city_lot_get_all', methods: ['GET'])]
    public function getAll(int $cityId): Response
    {
        $city = $this->cityRepository->findOneBy(['id' => $cityId]);

        if (!$city){
            return $this->handleView($this->view(sprintf('No city found with id %d', $cityId), 404));
        }

        $lotArray = $this->lotRepository->findBy(['city' => $cityId]);

        $data = [];

        foreach ($lotArray as $item) {
            $data[] = [
                'id' => $item->getId(),
                'name' => $item->getName(),
                'address' => $item->getAddress(),
                'phone' => $item->getPhone(),
                'maxNumberOfCars' => $item->getMaxNumberOfCars(),
                'email' => $item->getEmail()
            ];
        }

        $view = $this->view($data);

        return $this->handleView($view);
    }

    #[Route('/lot/{lotId}', name: 'app_api_city_lot_get', methods: ['GET'])]
    public function get(int $cityId, int $lotId): Response
    {
        $lot = $this->lotRepository->findOneBy(['city' => $cityId, 'id' => $lotId]);

        if (!$lot) {
            return $this->handleView($this->view(sprintf('No lot found with id %d in city with id %d', $lotId, $cityId), 404));
        }

        $data = [
            'id' => $lot->getId(),
            'name' => $lot->getName(),
            'address' => $lot->getAddress(),
            'phone' => $lot->getPhone(),
            'maxNumberOfCars' => $lot->getMaxNumberOfCars(),
            'email' => $lot->getEmail()
        ];

        $view = $this->view($data);

        return $this->handleView($view);
    }

    #[Route('/lot', name: 'app_api_city_lot_new', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(int $cityId, ManagerRegistry $managerRegistry, Request $request): Response
    {
        $lot = new Lot();

        $form = $this->createForm(NewLotType::class, $lot);
        $form->submit($request->request->all());
        $view = $this->view($form);

        if ($form->isSubmitted() && $form->isValid()) {
            $city = $this->cityRepository->findOneBy(['id' => $cityId]);

            if (!$city) {
                return $this->handleView($this->view(sprintf('No city found with id %d', $cityId), 404));
            }

            $lot->setCity($city);

            $entityManager = $managerRegistry->getManager();
            $entityManager->persist($lot);
            $entityManager->flush();

            $view = $this->view(null, 204);
        }

        return $this->handleView($view);
    }

    #[Route('/lot/{lotId}', name: 'app_api_city_lot_edit', methods: ['PUT'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(int $cityId, int $lotId, ManagerRegistry $managerRegistry, Request $request): Response
    {
        $lot = $this->lotRepository->findOneBy(['city' => $cityId, 'id' => $lotId]);

        if (!$lot) {
            return $this->handleView($this->view(sprintf('No lot found with id %d in city with id %d', $lotId, $cityId), 404));
        }

        $form = $this->createForm(NewLotType::class, $lot);
        $form->submit($request->request->all());
        $view = $this->view($form);

        if ($form->isSubmitted() && $form->isValid()) {
            $city = $this->cityRepository->findOneBy(['id' => $cityId]);

            if (!$city) {
                return $this->handleView($this->view(sprintf('No city found with id %d', $cityId), 404));
            }

            $entityManager = $managerRegistry->getManager();
            $entityManager->persist($lot);

            $view = $this->view(null, 204);
        }

        return $this->handleView($view);
    }

    #[Route('/lot/{lotId}', name: 'app_api_city_lot_remove', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN')]
    public function remove(int $cityId, int $lotId, ManagerRegistry $managerRegistry, Request $request): Response
    {
        $lot = $this->lotRepository->findOneBy(['city' => $cityId, 'id' => $lotId]);

        if (!$lot) {
            return $this->handleView($this->view(sprintf('No lot found with id %d in city with id %d', $lotId, $cityId), 404));
        }

        $entityManager = $managerRegistry->getManager();
        $entityManager->remove($lot);
        $entityManager->flush();

        $view = $this->view(null, 204);

        return $this->handleView($view);
    }
}