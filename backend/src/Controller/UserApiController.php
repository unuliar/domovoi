<?php


namespace App\Controller;


use App\Entity\Account;
use App\Entity\House;
use App\Entity\PersonClaim;
use App\Repository\HouseRepository;
use App\Vk\Api;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
class UserApiController extends ApiController
{

    /**
     * @Rest\Get("/api/token/check")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return Response
     */
    public function getTokenValid(\Symfony\Component\HttpFoundation\Request $request)
    {
        $token = $request->get("token");

        $vkApi = new Api($this->getParameter("VK_SECRET"),$this->getParameter("BACK_URL"));
        $userData = $vkApi->getUserData($token);

        if(isset($userData) && isset($userData["response"]) && isset($userData[0]) && trim($userData[0]["id"]) != '') {
            return $this->handleView($this->view(['status' => 'ok', 'result' => true]));
        } else {
            return $this->handleView($this->view(['status' => 'ok', 'result' => false]));
        }

    }

    /**
     * @Rest\Get("/api/user/getByToken")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return Response
     */
    public function getUserByToken(\Symfony\Component\HttpFoundation\Request $request)
    {
        $token = $request->get("token");
        $userRep = $this->getDoctrine()->getRepository(Account::class);
        /** @var Account $user */
        $user = $userRep->findOneBy(["vkToken" => $token]);
        $view = $this->view(['status' => 'ok', 'org' => $user]);
        $view->getContext()->setGroups(array('Default'));

        if($user) {
            return $this->handleView($view);
        } else {
            return $this->handleView($this->view(['status' => 'error', 'descr' => "unathorized"], Response::HTTP_NOT_FOUND));
        }
    }


    /** //https://oauth.vk.com/authorize?client_id=7508602&display=page&redirect_uri=http://localhost:81/api/vkAuthCallback&scope=notifications,email&response_type=code&v=5.110
     * @Rest\Get("/api/vkAuthCallback")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return Response
     * @throws \Exception
     */
    public function getCB(\Symfony\Component\HttpFoundation\Request $request)
    {
        $code = $request->get("code");
        $vkApi = new Api($this->getParameter("VK_SECRET"),$this->getParameter("BACK_URL"));
        $tokenRes = $vkApi->getAuthToken($code);
        $userData = $vkApi->getUserData($tokenRes["access_token"])["response"][0];

        // return $this->handleView($this->view(['status' => 'ok', 'result' => $userData]));
        $userRep = $this->getDoctrine()->getRepository(Account::class);
        /** @var Account $existing */
        $existing = $userRep->findOneBy(["vkId" => $userData["id"]]);
        if(!$existing) {
            $existing = new Account();
            $existing->setVkToken($tokenRes["access_token"])
                ->setCity($userData["city"] ?? 'Не указан')
                ->setVkId($userData["id"])
                ->setFirstName($userData["first_name"])
                ->setLastName($userData["last_name"])
                ->setPhotoUrl($userData["photo_100"])
                ->setBirthDate(new \DateTime($userData["bdate"]));

            $p = mt_rand(1,9);
            $claim = new PersonClaim();
            $claim->addAccount($existing);
            $claim->setDetailedAddress("подъезд " . $p . ", кв " . $p * mt_rand(8,50));
            $claim->setSize(mt_rand(40, 100));
            /** @var HouseRepository $hRep */
            $hRep = $this->getDoctrine()->getRepository(House::class);
            $claim->setHouse($hRep->getRandom()[0]);

            $this->em->persist($claim);
        }
        $existing->setVkToken($tokenRes["access_token"]);
        $this->em->persist($existing);

        $this->em->flush();

        $response = new RedirectResponse($this->getParameter("FRONT_URL"));
        $cookie = new Cookie('token', $tokenRes["access_token"], time()+36000, "/", null, null, false);
        $response->headers->setCookie($cookie);

        return $response;
    }
}