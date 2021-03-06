<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ChangePasswordFormType extends AbstractType
{
    public const CURRENT_PASSWORD_REQUIRED_OPTION_NAME = 'current_password_is_required';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        if ($options[self::CURRENT_PASSWORD_REQUIRED_OPTION_NAME]) {
            $builder
                ->add('currentPassword', PasswordType::class, [
                    'label'    => 'Current password',
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Please enter current password',
                        ]),
                        new UserPassword([
                            'message' => 'Invalid current password',
                        ])
                    ]
                ]);
        }

        $builder->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Please enter a password',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Your password should be at least {{ limit }} characters',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                    ],
                    'label' => 'New password',
                ],
                'second_options' => [
                    'label' => 'Repeat Password',
                ],
                'invalid_message' => 'The password fields must match.',
                // Instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            self::CURRENT_PASSWORD_REQUIRED_OPTION_NAME => false,
        ]);

        $resolver->setAllowedTypes(self::CURRENT_PASSWORD_REQUIRED_OPTION_NAME, 'bool');
    }
}
