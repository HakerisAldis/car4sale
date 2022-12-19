<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserRegisterType;
use App\Repository\UserRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @method User getUser()
 */
class UserController extends AbstractFOSRestController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    /**
     * @throws \JsonException
     */
    #[Route('/api/user/register', name: 'app_user_register', methods: ['POST'])]
    public function register(Request $request): Response
    {
        // dd(json_decode($request->getContent(), true));
        $form = $this->createForm(UserRegisterType::class);
        $form->submit(json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR));
        $view = $this->view($form);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->userRepository->findOneBy(['email' => $form->get('email')->getData()])) {
                $view = $this->view('User with this email already exists', Response::HTTP_CONFLICT);
            } else {
                $user = (new User())
                    ->setEmail($form->get('email')->getData())
                    ->setPassword(password_hash($form->get('password')->getData(), PASSWORD_BCRYPT))
                    ->setRoles(['ROLE_USER'])
                ;
                $this->userRepository->save($user, true);
                $view = $this->view('User created successfully', 201);
            }
        }

        return $this->handleView($view);
    }

    #[Route('/api/user', name: 'app_user_get', methods: ['GET'])]
    public function details(): Response
    {
        $user = $this->getUser();

        return $this->handleView($this->view([
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
        ], Response::HTTP_OK));
    }

    #[Route('/api/user/vehicles', name: 'app_user_vehicles_get', methods: ['GET'])]
    public function getAllVehicles(): Response
    {
        $user = $this->getUser();
        $userVehicles = $user->getVehicles();
        $data = [];

        foreach ($userVehicles as $item) {
            $data[] = [
                'id' => $item->getId(),
                'make' => $item->getMake(),
                'model' => $item->getModel(),
                'dateOfManufacture' => $item->getDateOfManufacture(),
                'fuelType' => $item->getFuelType(),
                'gearBox' => $item->getGearbox(),
                'engineCapacity' => $item->getEngineCapacity(),
                'price' => $item->getPrice(),
                'cityId' => $item->getLot()->getCity()->getId(),
                'lotId' => $item->getLot()->getId(),
            ];
        }

        $view = $this->view($data);

        return $this->handleView($view);
    }
}
