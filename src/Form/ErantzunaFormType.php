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
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

/**
 * Description of ErantzunaFormType
 *
 * @author ibilbao
 */
class ErantzunaFormType extends AbstractType {

   public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
			->add('erantzuna', TextareaType::class,[
				'attr' => ['class' => 'tinymce'],
				'label' => false,
				'constraints' => [
						],
				])
		;
   }

   public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults([
			'data_class' => 'App\Entity\Erantzuna',
			'csrf_protection' => false,
		]);
   }

}
