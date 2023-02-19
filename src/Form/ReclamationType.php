<?php

namespace App\Form;

use App\Entity\Reclamation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\User;



class ReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('sujet')
            ->add('description', TextareaType::class, [
                    'label' => 'description',
                        'attr' => [ 'rows' => 10, // nombre de lignes visibles
                                    'placeholder' => 'Entrez votre message ici...',
                        ],] )
           
            ->add('date')
            ->add('nom_client')
            ->add('idUser',EntityType::class,['class'=> User::class,
           'choice_label'=>'id',
           'label'=>'userId'],)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
        ]);
    }
}

