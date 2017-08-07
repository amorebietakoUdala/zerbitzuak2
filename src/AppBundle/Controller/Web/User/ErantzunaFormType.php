<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Controller\Web\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Description of ErantzunaFormType
 *
 * @author ibilbao
 */
class ErantzunaFormType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
	$builder
	    ->add('erantzuna', TextareaType::class,[
//		'attr' => ['class' => 'tinymce'],
		'constraints' => [new NotBlank(),				    
				 ],
	    ])
//            ->add('noiz', DateType::class, [
//		'widget' => 'single_text',
//		'html5' => 'false',
//		'format' => 'yyyy-MM-dd',
//		'attr' => [ 'class' => 'hidden' ],
//		'constraints' => [new NotBlank(),]
//	    ])
//            ->add('noiz', HiddenType::class, [
//                'data' => date('Y-m-d H:i:s')
//	    ])
                
	;
    }

    public function configureOptions(OptionsResolver $resolver) {
	$resolver->setDefaults([
	    'data_class' => '\AppBundle\Entity\Erantzuna',
	    'csrf_protection' => false,
	]);
    }

}
