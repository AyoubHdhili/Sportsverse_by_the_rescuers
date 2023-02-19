<?php

namespace App\Form;

use App\Entity\Seance;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date',DateTimeType::class,['widget'=>'single_text','placeholder'=>'Choisir la date de la séance'])
            ->add('duree',ChoiceType::class,['choices'=>['1 heure'=>'1 heure','2 heure'=>'2 heure'], 'placeholder' => 'Duree de la séance'])
            ->add('coach_id')
            ->add('Emplacement',ChoiceType::class,)
            ->add('message',TextareaType::class,['attr'=>['placeholder'=>'Votre message']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Seance::class,
        ]);
    }
}
