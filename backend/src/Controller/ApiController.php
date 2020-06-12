<?php

namespace App\Controller;
use App\Entity\Account;
use App\Vk\Api;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Security\Core\User\User;

class ApiController extends \FOS\RestBundle\Controller\AbstractFOSRestController
{
    private $em;

    public function __construct(\Doctrine\ORM\EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @Rest\Get("/api/event")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return Response
     */
    public function getEvent(\Symfony\Component\HttpFoundation\Request $request)
    {

        //$existing = $userRep->findOneBy(["vkId" => "12"]);
        return $this->handleView($this->view(['status' => 'ok', 'id' => "12"]));
    }


    /** //https://oauth.vk.com/authorize?client_id=7508602&display=page&redirect_uri=http://localhost:81/api/vkAuthCallback&scope=notifications,email&response_type=code&v=5.110
     * @Rest\Get("/api/vkAuthCallback")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return Response
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function getCB(\Symfony\Component\HttpFoundation\Request $request)
    {
        $code = $request->get("code");
        $vkApi = new Api($this->getParameter("VK_SECRET"));
        $tokenRes = $vkApi->getAuthToken($code);
        $userData = $vkApi->getUserData($tokenRes["access_token"])["response"][0];

       // return $this->handleView($this->view(['status' => 'ok', 'result' => $userData]));
        $userRep = $this->getDoctrine()->getRepository(Account::class);
        /** @var Account $existing */
        $existing = $userRep->findOneBy(["vkId" => $userData["id"]]);
        if(!$existing) {
            $existing = new Account();
            $existing->setVkToken($tokenRes["access_token"])
            ->setCity($userData["city"]["title"])
            ->setVkId($userData["id"])
            ->setFirstName($userData["first_name"])
            ->setLastName($userData["last_name"])
            ->setPhotoUrl($userData["photo_100"])
            ->setBirthDate(new \DateTime($userData["bdate"]));
        }
        $existing->setVkToken($tokenRes["access_token"]);
        $this->em->persist($existing);
        $this->em->flush();

        return $this->handleView($this->view(['status' => 'ok', 'result' => $userData]));
    }
}