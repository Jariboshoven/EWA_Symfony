<?php

namespace App\Controller;

use App\Entity\WebsiteVar;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class WebsiteVarController extends AbstractController {
	private $serializer;
	private $websiteVarRepository;

	public function __construct(EntityManagerInterface $em)
	{
		$encoders = [new XmlEncoder(), new JsonEncoder()];
		$normalizers = [new ObjectNormalizer()];
		$this->serializer = new Serializer($normalizers, $encoders);

		$this->websiteVarRepository = $em->getRepository(WebsiteVar::class);
	}

	/**
	 * @Route("/api/components", name="get_website_components", methods={"GET"})
	 *
	 * @return Response
	 */
	public function getAllWebsiteVars(): Response
	{
		$vars = $this->websiteVarRepository->findAll();
		$response = $this->serializer->serialize($vars, 'json');
		return new Response($response);
	}

	/**
	 * @Route("/api/component/{component}", name="get_website_vars", methods={"GET"})
	 *
	 * @param $component
	 *
	 * @return Response
	 */
	public function getWebsiteVarsByComponent($component): Response
	{
		$vars = $this->websiteVarRepository->findVarsByComponent($component);
		$response = $this->serializer->serialize($vars, 'json');
		return new Response($response);
	}

	/**
	 *
	 * @param int $postId
	 *
	 * @return array
	 */
	public function getEmailVars(): array
	{
		/** @var $mailComponents WebsiteVar[] */
		$mailComponents = $this->websiteVarRepository->findVarsByComponent('NewsArticleEmail');

		$mailTemplateInfo = [];
		foreach($mailComponents as $mailComponent)
		{
			if(isset($mailTemplateInfo[$mailComponent->getTitle()]))
			{
				continue;
			}

			$mailTemplateInfo[$mailComponent->getTitle()] = $mailComponent->getValue();
		}
		return $mailTemplateInfo;
	}
}