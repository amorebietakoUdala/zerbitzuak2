<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AMREU\UserBundle\Form\UserType as BaseUserType;
use App\Entity\Enpresa;
use App\Entity\User;
use App\Repository\EnpresaRepository;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

/**
 * Description of UserFormType
 *
 * @author ibilbao
 */
class UserFormType extends BaseUserType {


	public function __construct($allowedRoles)	{
		parent::__construct(User::class, $allowedRoles);
	}	

    public function buildForm(FormBuilderInterface $builder, array $options) {
		$profile = $options['profile'];
		$readonly = $options['readonly'];
		$builder
			->add('telefonoa')
			->add('telefonoa2');
	
		if ( !$profile) {
			$builder
				->add('roles', ChoiceType::class, [
					'label' => false,
					'choices' => $this->allowedRoles,
					'label_attr' => ['class' => 'checkbox-inline'],
					'choice_attr' => function($choice, $key, $value) {
						return ['class' => 'ml-1'];
				  	},
					'expanded' => true,
					'multiple' => true,
					'constraints' => [new NotBlank(),],
					'choice_translation_domain' => null,
					'disabled' => $readonly,
				])
				->add('ordena')
				->add('enpresa', EntityType::class,[
					'placeholder'=>'messages.hautatu_enpresa',
					'class' => Enpresa::class,
					'query_builder' => function (EnpresaRepository $repo) {
						return $repo->createAlphabeticalQueryBuilder();
					}
				])
				->add('activated', CheckboxType::class,[
					'data' => true,
					'label_attr' => ['class' => 'checkbox-inline']
				])
			;
		}
	 }
	 
   public function getParent() {
      return BaseUserType::class;
   }

   public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults([
			'data_class' => 'App\Entity\User',
			'profile' => false,
			'readonly' => false
		]);
   }

}
