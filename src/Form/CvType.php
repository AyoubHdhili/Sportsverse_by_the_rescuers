<?php

namespace App\Form;

use App\Entity\Activite;
use App\Entity\Cv;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CvType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('certification')
            ->add('description')
            ->add('tarif')
            ->add('image')
            ->add('activites')
            ->add('duree_experience')
            ->add('level',  ChoiceType::class, [
                'choices' => [
                    'Pro ' => 'pro',
                    'Debutant ' => 'debutant',
                    'Intermediaire ' => 'intermediaire',
                ],
                'expanded' => true,
                'multiple' => false,
                'placeholder' => 'Ton niveau de experience',
            ])
            ->add('user_id');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cv::class,
        ]);
    }
}
