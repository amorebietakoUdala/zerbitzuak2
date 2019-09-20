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
use Vich\UploaderBundle\Form\Type\VichFileType;
use AppBundle\Entity\Eranskina;


/**
 * Description of EranskinaFormType
 *
 * @author ibilbao
 */
class EranskinaFormType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
	$builder
	    ->add('eranskinaFile', VichFileType::class,[
		'required' => false,
		'allow_delete' => true,
		'download_uri' => true,
		'download_label' => function (Eranskina $eranskina) {
		    return $eranskina->getEranskinaName();
		},
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
	    'data_class' => '\AppBundle\Entity\Eranskina',
	    'csrf_protection' => false,
	]);
    }

}
