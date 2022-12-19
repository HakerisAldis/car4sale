<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class NewVehicleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('make', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('model', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('dateOfManufacture', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('fuelType', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('gearBox', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('engineCapacity', NumberType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('price', NumberType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
            ])
        ;
    }
}