<?php

namespace App\Form;

use App\Entity\Tareas;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;



class TareasUsuariosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre', TextType::class, [
                'label'=> 'Nombre',
                'required' => true,
            ])
            ->add('descripcion', TextType::class, [
                'label' => 'Descripción',
                'required'=> true,
            ])
            ->add('prioridad', NumberType::class, [
                'label' => 'Prioridad',
                'required' => true,
            ])
            ->add('fecha', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Fecha',
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tareas::class,
        ]);
    }
}
