<?php

namespace App\Form;

use App\Entity\Astreinte;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Doctrine\ORM\EntityRepository;
use App\Entity\Utilisateur;

class AstreinteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('utilisateur', EntityType::class, [
                'class' => Utilisateur::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                                ->where('u.roles LIKE :roles')
                                ->setParameter('roles', '%USER%')
                                ->addOrderBy('u.prenom')
                                ->addOrderBy('u.nom');
                },
                'choice_label' => function($utilisateur){
                    return $utilisateur->toString();
                }
            ])
            ->add('commentaire', TextareaType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => "Ajouter un commentaire concernant cette astreinte (optionnel)"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Astreinte::class,
        ]);
    }
}
