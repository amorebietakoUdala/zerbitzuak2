<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;


/**
 * Description of ArgazkiaFormType
 *
 * @author ibilbao
 */
class ArgazkiaFormType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
	$builder
	    ->add('imageFile', VichImageType::class,[
		'required' => false,
		'by_reference' => false,
		'allow_delete' => true,
		'download_uri' => false,
//		'download_label' => 'download_file',
//		'image_uri' => true,
		'attr' => ['class' => 'js-file'],
		'constraints' => [
//		    new NotBlank(),
		    ],
	    ])
//	    ->add('imageName',null,[
//		'attr' => ['readonly' => true ],
//	    ])
	;
    }

    public function configureOptions(OptionsResolver $resolver) {
	$resolver->setDefaults([
	    'data_class' => '\AppBundle\Entity\Argazkia',
	    'csrf_protection' => false,
	]);
    }

}
