<?php

namespace App\Form;

use App\Entity\Pin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Vich\UploaderBundle\Form\Type\VichImageType;

class PinType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title',
                TextType::class,
                [
                    'required' => true,
                    'label'    => 'Title: '
                ]
            )
            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => true,
                'delete_label' => 'Remove image',
                'download_label' => 'Download',
                'download_uri' => true,
                'image_uri' => true,
                'imagine_pattern' => null,
                'asset_helper' => true,
                'constraints' => [
                    new Image(['maxSize' => '5M'])
                ]
            ])
            ->add(
                'description',
                TextareaType::class,
                [
                    'required' => true,
                    'label'    => 'Description: '
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'      => Pin::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
        ]);
    }
}
