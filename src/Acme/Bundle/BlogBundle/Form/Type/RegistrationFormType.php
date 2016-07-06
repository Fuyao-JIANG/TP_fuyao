<?php

namespace Acme\Bundle\BlogBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Util\LegacyFormHelper;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RegistrationFormType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('email', LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\EmailType'), array('label' => 'Email', 'translation_domain' => 'FOSUserBundle'))
            ->add('username', null, array('label' => 'Username', 'translation_domain' => 'FOSUserBundle'))
            ->add('plainPassword', LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\RepeatedType'), array(
                'type' => LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\PasswordType'),
                'options' => array('translation_domain' => 'FOSUserBundle'),
                'first_options' => array('label' => 'Password'),
                'second_options' => array('label' => 'Confirm the password'),
                'invalid_message' => 'The passwords mismatch',
            ))
            ->add('firstname')
            ->add('lastname')
        ;
    }


    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }
    

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }
}

?>