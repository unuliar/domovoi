<?php

namespace App\Controller;
use App\Entity\Account;

use App\Entity\AccountPollResult;
use App\Entity\Attachment;
use App\Entity\House;
use App\Entity\Meeting;
use App\Entity\MeetingQuestion;
use App\Entity\Organisation;
use App\Entity\PersonClaim;
use App\Entity\Poll;
use App\Entity\Post;
use App\Repository\HouseRepository;
use App\Vk\Api;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\User\User;

class ApiController extends \FOS\RestBundle\Controller\AbstractFOSRestController
{
    protected $em;

    public function __construct(\Doctrine\ORM\EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    protected function processFiles(Request $req) {
        /** @var UploadedFile[] $uploaded */
        $uploaded = $req->files->all();
        $res = [];
        foreach ($uploaded as $file) {
            $f = new Attachment();
            $f->setFile($file->getRealPath());
            $f->setName($file->getFilename());
            $res[] = $f;
        }
        return $res;
    }

    /**
     * @Rest\Post("/api/post/create")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return Response
     * @throws \Exception
     */
    public function postPost(\Symfony\Component\HttpFoundation\Request $request)
    {
        $data = $request->request->all();
        $rOrg = $this->getDoctrine()->getRepository(Organisation::class);
        $rUser = $this->getDoctrine()->getRepository(Account::class);

        /** @var Organisation $org */
        $org = $data["org"] != null ? $rOrg->findOneBy(["id" => $data["org"]]) : null;
        /** @var Account $creator */
        $creator = $rUser->findOneBy(["vkToken" => $data["token"]]);

        $post = new Post();
        $post->setOrg($org);
        $post->setContent($data["content"]);
        $post->setCreated(new \DateTime());
        $post->setCreator($creator);

        foreach ($this->processFiles($request) as $file) {
            $file->setPost($post);
            $this->em->persist($file);
        }
        //$post->addAttachment()
        $this->em->persist($post);
        $this->em->flush();

        return $this->handleView($this->view(['status' => 'ok', 'id' => $post->getId()], Response::HTTP_CREATED));
    }

    /**
     * @Rest\Get("/api/organisation/getByToken")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return Response
     */
    public function getOrgByToken(\Symfony\Component\HttpFoundation\Request $request)
    {
        $token = $request->get("token");
        $userRep = $this->getDoctrine()->getRepository(Account::class);
        /** @var Account $user */
        $user = $userRep->findOneBy(["vkToken" => $token]);
        if($user) {
            $owns = $user->getOwnings();
            if($owns->isEmpty()) {
                return $this->handleView($this->view(['status' => 'error', 'descr' => "No properties detected"], Response::HTTP_NOT_FOUND));
            } else {
                return $this->handleView($this->view(['status' => 'ok', 'org' => $owns[0]->getHouse()->getOrg()]));
            }
        } else {
            return $this->handleView($this->view(['status' => 'error', 'descr' => "unathorized"], Response::HTTP_NOT_FOUND));
        }

    }

    /**
     * @Rest\Get("/api/organisations/get")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return Response
     */
    public function getOrgs(\Symfony\Component\HttpFoundation\Request $request)
    {
       $orgRepo = $this->getDoctrine()->getRepository(Organisation::class);

       $orgs = $orgRepo->findAll();

        usort(/**
         * @param Organisation $v1
         * @param Organisation $v2
         * @return mixed
         */ $orgs, function ($v1, $v2) {
            /** @var Organisation $v1 */
            return $v1->getRespectIndex() < $v2->getRespectIndex();
         });

         return $this->handleView($this->view(['status' => 'ok', 'orgs' => $orgs], Response::HTTP_OK));
    }
}