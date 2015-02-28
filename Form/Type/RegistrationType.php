<?php
/**
 * This file is part of the zamocno package.
 *
 * Created by Rafał Lorenz <vardius@gmail.com>.
 */

namespace Vardius\Bundle\UserBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Vardius\Bundle\UserBundle\Form\Type\RegistrationType
 *
 * @author Rafał Lorenz <vardius@gmail.com>
 */
class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user', 'vardius_user', [
                'label' => false,
            ])
            ->add('terms', 'checkbox', [
                'property_path' => 'termsAccepted',
                'label' => 'registration.form.terms',
            ])
            ->add('register', 'submit', [
                'label' => 'registration.form.button',
            ]);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Vardius\Bundle\UserBundle\Form\Model\Registration',
            'cascade_validation' => true,
        ]);
    }

    public function getName()
    {
        return 'vardius_registration';
    }
}