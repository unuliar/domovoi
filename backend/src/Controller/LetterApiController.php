<?php


namespace App\Controller;


use App\Entity\Account;
use App\Entity\Attachment;
use App\Entity\House;
use App\Entity\Letter;
use App\Entity\LetterChanges;
use App\Entity\Meeting;
use App\Entity\MeetingQuestion;
use App\Entity\Poll;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;

class LetterApiController extends ApiController
{

    /**
     * @Rest\Post("/api/letter/create")
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return Response
     * @throws \Exception
     */
    public function postLetter(\Symfony\Component\HttpFoundation\Request $request)
    {
        $accRep = $this->getDoctrine()->getRepository(Account::class);

        $data = json_decode($request->get('body'), true);
        $data["creator"] = $accRep->findOneBy(["vkToken" => $request->get("token")]);
        $user = $data["creator"];

        if(isset($data['receiver']) && trim($data["reciever"]) != '') {
            $data["reciever"] = $accRep->findOneBy(["id" => $data["reciever"]]);
        } else {
            /** @var Account $user */
            $data["reciever"] = $accRep->findOneBy(["type" => "UK_ADMIN", "Org" => $user->getOwnings()[0]->getHouse()->getOrg()]);
        }
        $data["created"] = new \DateTime();
        if(isset($data['receiver']) && trim($data["house"]) != '') {
            $data["house"] = $accRep->findOneBy(["id" => $data["house"]]);
        } else {
            $data["house"] = $user->getOwnings()[0]->getHouse();
        }


        $rep = $this->getDoctrine()->getRepository(Letter::class);
        /** @var Letter $letter */
        $letter = $rep->createByArray($data);

        /** @var Attachment $file */
        foreach ($this->processFiles($request) as $file) {
            $file->setLetter($letter);
            $this->em->persist($file);
        }

        $this->em->flush();
        return $this->handleView($this->view(['status' => 'ok', 'id' => $letter->getId()], Response::HTTP_CREATED));
    }


    /**
     * @Rest\Post("/api/letter/sign")
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return Response
     * @throws \Exception
     */
    public function postSignLetter(\Symfony\Component\HttpFoundation\Request $request)
    {
        $accRep = $this->getDoctrine()->getRepository(Account::class);
        $letRep = $this->getDoctrine()->getRepository(Letter::class);

        $data = json_decode($request->get('body'), true);
        /** @var Account $acc */
        $acc = $accRep->findOneBy(["vkToken" => $data["token"]]);
        /** @var Letter $letter */
        $letter= $letRep->findOneBy(["id" => $data["letter"]]);

        if($letter->getType() == Letter::$TYPE_COMMUNITY) {
            $letter->addSignedAccount($acc);
            $this->em->persist($letter);
            $this->em->flush();

            return $this->handleView($this->view(['status' => 'ok'], Response::HTTP_CREATED));
        } else {
            return $this->handleView($this->view(['status' => 'error', "descr" => "individual letter"], Response::HTTP_CONFLICT));
        }
    }


    /**
     * @Rest\Post("/api/letter/assignWorker")
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return Response
     * @throws \Exception
     */
    public function postAssignWorker(\Symfony\Component\HttpFoundation\Request $request)
    {
        $accRep = $this->getDoctrine()->getRepository(Account::class);
        $letRep = $this->getDoctrine()->getRepository(Letter::class);

        $data = json_decode($request->get('body'), true);
        /** @var Account $acc */
        $acc = $accRep->findOneBy(["id" => $data["worker"]]);
        /** @var Letter $letter */
        $letter= $letRep->findOneBy(["id" => $data["letter"]]);
        $old = $letter->getWorker();
        $letter->setWorker($acc);
        $this->em->persist($letter);

        $letter->setStatus('Назначен исполнитель');

        $change = new LetterChanges();
        $change->setChangetype("Исполнитель изменен");
        $change->setLetter($letter);
        $change->setFromValue($old);
        $change->setDate(new \DateTime());
        $change->setToValue((string)sprintf("%s %s", $acc->getLastName(), $acc->getFirstName()));
        $this->em->persist($change);

        $this->em->flush();
        return $this->handleView($this->view(['status' => 'ok'], Response::HTTP_CREATED));
    }

    /**
     * @Rest\Post("/api/letter/changeStatus")
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return Response
     * @throws \Exception
     */
    public function postChangeStatus(\Symfony\Component\HttpFoundation\Request $request)
    {
        $letRep = $this->getDoctrine()->getRepository(Letter::class);

        $data = json_decode($request->get('body'), true);
        /** @var Letter $letter */
        $letter= $letRep->findOneBy(["id" => $data["letter"]]);
        $old = $letter->getStatus();
        $new = $data["status"];
        $letter->setStatus($new);
        $this->em->persist($letter);


        $change = new LetterChanges();
        $change->setChangetype("Статус изменен");
        $change->setLetter($letter);
        $change->setFromValue($old);
        $change->setDate(new \DateTime());
        $change->setToValue($new);
        $this->em->persist($change);

        $this->em->flush();
        return $this->handleView($this->view(['status' => 'ok'], Response::HTTP_CREATED));
    }
}