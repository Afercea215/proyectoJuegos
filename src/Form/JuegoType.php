<?php

namespace App\Form;

use App\Entity\Juego;
use Doctrine\DBAL\Types\StringType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class JuegoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ancho', IntegerType::class,
                ['required' => true
                ,'label' => 'Ancho Juego',])
            ->add('longitud', IntegerType::class,
                ['required' => true
                ,'label' => 'Longitud Juego',])
            ->add('minJuga', IntegerType::class,
                ['required' => true
                ,'label' => 'Minimio Jugadores',])
            ->add('maxJuga', IntegerType::class,
                ['required' => true
                ,'label' => 'Maximo Jugadores',])
            ->add('nombre')
            ->add('img',FileType::class, [
                'data_class' => null,
                'constraints' => [
                    new File([
                        'maxSize' => '2048k',
                        'mimeTypes' => [
                            'image/jpg',
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Img no valida, debe ser JPG o PNG',
                    ])
                ],
            ])
            ->add('editorial')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Juego::class,
            'id' => 'formNewJuego',
        ]);
    }
}
