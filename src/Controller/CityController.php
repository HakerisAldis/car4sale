<?php

namespace App\Controller;

use App\Entity\City;
use App\Form\NewCityType;
use App\Repository\CityRepository;
use Doctrine\Persistence\ManagerRegistry;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CityController extends AbstractFOSRestController {
    Private CityRepository $cityRepository;

    public function __construct(CityRepository $cityRepository) {
        $this->cityRepository = $cityRepository;
    }

    #[Route('/api/city', name: 'app_api_city_get_all', methods: ['GET'])]
    public function getAll(): Response
    {
        $cityArray = $this->cityRepository->findAll();

        $data = [];

        foreach ($cityArray as $item) {
            $data[] = [
                'id' => $item->getId(),
                'name' => $item->getName()
            ];
        }

        $view = $this->view($data);

        return $this->handleView($view);
    }

    #[Route('/api/city/{id}', name: 'app_api_city_get', methods: ['GET'])]
    public function get(int $id): Response
    {
        $city = $this->cityRepository->findOneBy(['id' => $id]);

        if (!$city) {
            return $this->handleView($this->view(sprintf('No city found with id %d', $id), 404));
        }

        $view = $this->view($city);

        return $this->handleView($view);
    }

    /**
     * @throws \JsonException
     */
    #[Route('/api/city', name: 'app_api_city_new', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(ManagerRegistry $managerRegistry, Request $request): Response
    {
        $city = new City();

        $form = $this->createForm(NewCityType::class, $city);
        $form->submit(json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR));
        $view = $this->view($form);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $managerRegistry->getManager();
            $entityManager->persist($city);
            $entityManager->flush();

            $view = $this->view(null, 204);
        }

        return $this->handleView($view);
    }

    /**
     * @throws \JsonException
     */
    #[Route('/api/city/{id}', name: 'app_api_city_edit', methods: ['PUT'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(int $id, ManagerRegistry $managerRegistry, Request $request): Response
    {
        $city = $this->cityRepository->findOneBy(['id' => $id]);

        if (!$city) {
            return $this->handleView($this->view(sprintf('No city found with id %d', $id), 404));
        }

        $form = $this->createForm(NewCityType::class, $city);
        $form->submit(json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR));
        $view = $this->view($form);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $managerRegistry->getManager();
            $entityManager->flush();

            $view = $this->view(null, 204);
        }

        return $this->handleView($view);
    }

    #[Route('/api/city/{id}', name: 'app_api_city_remove', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN')]
    public function remove(int $id, ManagerRegistry $managerRegistry, Request $request): Response
    {
        $city = $this->cityRepository->findOneBy(['id' => $id]);

        if (!$city) {
            return $this->handleView($this->view(sprintf('No city found with id %d', $id), 404));
        }

        $entityManager = $managerRegistry->getManager();
        $entityManager->remove($city);
        $entityManager->flush();

        $view = $this->view(null, 204);

        return $this->handleView($view);
    }
}