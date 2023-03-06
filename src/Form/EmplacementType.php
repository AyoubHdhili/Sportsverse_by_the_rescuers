<?php

namespace App\Form;

use App\Entity\Emplacement;
use App\Repository\EmplacementChoixRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmplacementType extends AbstractType
{
    private $EmplacementChoixRepository;
    public function __construct(EmplacementChoixRepository $emplacement_choix)
    {
        $this->EmplacementChoixRepository=$emplacement_choix;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('governorat',ChoiceType::class,['choices'=>$this->EmplacementChoixRepository->findGovernorat(), 'placeholder' => 'Choisir governorat'])
            ->add('delegation',ChoiceType::class,['choices'=>$this->EmplacementChoixRepository->findDelegation(), 'placeholder' => 'Choisir delegation'])
            ->add('Localite',ChoiceType::class,['choices'=>$this->EmplacementChoixRepository->findLocalite(), 'placeholder' => 'Choisir localite'])
            ->add('type',ChoiceType::class,['choices'=>['Maison' =>'Maison','Park'=>'Park','salle de sport'=>'salle_de_sport'], 'placeholder' => 'Type emplacement'])
            ->add('adresse')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Emplacement::class,
        ]);
    }
}
