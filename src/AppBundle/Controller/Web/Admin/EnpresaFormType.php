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
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use AppBundle\Repository\ErabiltzaileaRepository;
use AppBundle\Entity\Enpresa;
use AppBundle\Entity\Erabiltzailea;


/**
 * Description of EmpresaFormType
 *
 * @author ibilbao
 */
class EnpresaFormType extends AbstractType {
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
	$builder
	    ->add('izena')
	    ->add('ordena')
            ->add('aktibatua', CheckboxType::class,[
                'data' => true
            ])
	;
    }

    public function configureOptions(OptionsResolver $resolver) {
	$resolver->setDefaults([
	    'data_class' => '\AppBundle\Entity\Enpresa'
	]);
    }

}
