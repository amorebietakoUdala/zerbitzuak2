<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Controller\Web\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseRegistrationFormType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Entity\Enpresa;
use AppBundle\Repository\EnpresaRepository;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * Description of ErabiltzaileaFormType
 *
 * @author ibilbao
 */
class ErabiltzaileaFormType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
	$profile = $options['profile'];
	$builder
	    ->add('izena')
	    ->add('email', EmailType::class)
	    ->add('telefonoa')
	    ->add('telefonoa2');
	
	if ( !$profile) {
	    $builder->add('ordena')
            ->add('roles', ChoiceType::class,[
                'choices' => [
                    'ROLE_ADMIN' => 'ROLE_ADMIN',
                    'ROLE_ARDURADUNA' => 'ROLE_ARDURADUNA',
                    'ROLE_INFORMATZAILEA' => 'ROLE_INFORMATZAILEA',
                    'ROLE_KANPOKO_TEKNIKARIA' => 'ROLE_KANPOKO_TEKNIKARIA'
                ],
		'choice_attr' => ['class' => 'form-inline'],
		'expanded' => true,
		'multiple' => true,
		'constraints' => [new NotBlank(),],
		'choice_translation_domain' => null,
            ])
	    ->add('enpresa', EntityType::class,[
		'placeholder'=>'messages.hautatu_enpresa',
		'class' => Enpresa::class,
		'query_builder' => function (EnpresaRepository $repo) {
		    return $repo->createAlphabeticalQueryBuilder();
		}
	    ])
	    ->add('enabled', CheckboxType::class,[
                'data' => true
            ])
	    ;
	}
    }
    public function getParent() {
        return BaseRegistrationFormType::class;
    }

    public function configureOptions(OptionsResolver $resolver) {
	$resolver->setDefaults([
	    'data_class' => '\AppBundle\Entity\Erabiltzailea',
	    'profile' => false
	]);
    }

}
