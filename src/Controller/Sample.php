<?php
/**
 * Created by PhpStorm.
 * User: alexb
 * Date: 2019-03-16
 * Time: 22:00
 */

namespace App\Controller;


use App\Entity\SampleEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Sample extends AbstractController {


	public function sampleAction(): \Symfony\Component\HttpFoundation\Response {

		$entityManager = $this->getDoctrine()->getManager();

		$sample = new SampleEntity();
		$sample->setName(time());

		$entityManager->persist($sample);
		$entityManager->flush();

		$sampleEntities = $entityManager->getRepository(SampleEntity::class)->findAll();

		return $this->render('sample/sampleAction.html.twig', [
			'sampleEntities' => $sampleEntities,
			'count' => count($sampleEntities)
		]);
	}

}