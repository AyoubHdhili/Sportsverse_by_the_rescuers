<?php

namespace App\Form;

use App\Entity\Activite;
use App\Entity\Cv;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class CvType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('certification')
            ->add('description')
            ->add('tarif')
            ->add('image', FileType::class, [
                'label' => 'Votre photo de profile (Des fichier images seulement)',
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => ['image/gif ', 'image/jpg', 'image/jpeg',],
                        'mimeTypesMessage' => 'Please upload a valid Image document',
                    ])
                ],
            ])
            ->add(
                'activites',
                EntityType::class,
                [
                    'class' => Activite::class,
                    'choice_label' => 'nom',
                    'expanded' => false,
                    'multiple' => true,
                    'attr' => [
                        'class' => 'select2'
                    ],
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('a')
                            ->orderBy('a.nom', 'ASC');
                    },
                    'placeholder' => 'Choisir les activites',
                ]
            )
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
            ->add(
                'user_id',
                EntityType::class,
                [
                    'class' => User::class,
                    'choice_label' => 'Prenom',
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cv::class,
        ]);
    }
}
