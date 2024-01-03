<?php

namespace App\Form;

use App\Entity\Eleve;
use App\Entity\Seances;
use App\Entity\Presence;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PresenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_cours')
            ->add('validation_presence')
            ->add('eleve', EntityType::class, [
                'class' => Eleve::class,
                // On utilise le choice label getNomComplet() qui a été créer directement dans l'entité Eleve
                'choice_label' => 'nom_complet',
            ])
            ->add('seances', EntityType::class, [
                'class' => Seances::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Presence::class,
        ]);
    }
}
