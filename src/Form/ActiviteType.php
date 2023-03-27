<?php

namespace App\Form;

use App\Entity\Activite;
use App\Entity\Cv;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

use Symfony\Component\OptionsResolver\OptionsResolver;

class ActiviteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('description')
            ->add('type')
            ->add(
                'cvs',
                EntityType::class,
                [
                    'class' => Cv::class,
                    'choice_label' => 'user_id',
                    'expanded' => false,
                    'multiple' => true,
                    'attr' => [
                        'class' => 'select2'
                    ],
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('c')
                            ->orderBy('c.user_id', 'ASC');
                    },

                    'placeholder' => 'Choisir les Coachs',
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Activite::class,
        ]);
    }
}
