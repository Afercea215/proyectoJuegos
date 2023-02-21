<?php

namespace App\Form;

use App\Entity\Evento;
use App\Repository\EventoRepository;
use App\Repository\JuegoRepository;
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


class EventoJuegosType extends AbstractType
{
    private $jr;

    public function __construct(JuegoRepository $er)
    {
        $this->jr=$er;    
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('juegos', ChoiceType::class,[
                //'expanded' => true,
                'required' => true
                ,'label' => 'Selecciona el juego',
                'choices' => $this->jr->findAll(),
                'multiple' => true,
                // 'choice_label' => function (?Evento $evento) {
                //    return $evento->getImg();
                //},
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
    
    }
}
