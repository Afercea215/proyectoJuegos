<?php

namespace App\Form;

use App\Entity\Evento;
use App\Repository\EventoRepository;
use DateTime;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;


class EventoType extends AbstractType
{
    private $er;

    public function __construct(EventoRepository $er)
    {
        $this->er=$er;    
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre', TextType::class,
                ['required' => true
                ,'label' => 'Nombre',])
            ->add('descrip', TextareaType::class,
                ['required' => true
                ,'label' => 'Descripcion',])
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
            ->add('fecha', DateTimeType::class,
                ['required' => true
                ,'label' => 'Fecha',])
            ->add('juegos', ChoiceType::class,[
                //'expanded' => true,
                'required' => true
                ,'label' => 'Selecciona el juego',
                'choices' => $this->er->findAll(),
                /* 'choice_label' => function (?Evento $evento) {
                    return $evento->getImg();
                }, */
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evento::class,
        ]);
    }
}
