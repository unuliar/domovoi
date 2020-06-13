<?php


namespace App\Controller;


use App\Entity\Account;
use App\Entity\AccountPollResult;
use App\Entity\Attachment;
use App\Entity\House;
use App\Entity\Meeting;
use App\Entity\MeetingQuestion;
use App\Entity\Poll;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
class MettingsApiController extends ApiController
{

    /**
     * @Rest\Get("/api/meeting/getByUser")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return Response
     */
    public function getMeetsByUser(\Symfony\Component\HttpFoundation\Request $request)
    {
        $accRep = $this->getDoctrine()->getRepository(Account::class);

        /** @var Account $user */
        $user = $accRep->findOneBy(["vkToken" => $request->get("token")]);

        $view = $this->view(['status' => 'ok', 'meetings' =>  $user->getOwnings()[0]->getHouse()->getMeetings()]);
        $view->getContext()->setGroups(array('Default'));

        return $this->handleView($view);
    }

    /**
     * @Rest\Get("/api/meeting/get")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return Response
     */
    public function getMeetById(\Symfony\Component\HttpFoundation\Request $request)
    {
        $meetRep = $this->getDoctrine()->getRepository(Meeting::class);

        $meet = $meetRep->findOneBy(["id" =>  $request->get("id")]);

        $view = $this->view(['status' => 'ok', 'meeting' => $meet]);
        $view->getContext()->setGroups(array('Default'));

        return $this->handleView($view);
    }

    /**
     * @Rest\Get("/api/meeting/getAllByOrg")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return Response
     */
    public function getMeetsByOrg(\Symfony\Component\HttpFoundation\Request $request)
    {
        $org = $request->get("org");
        $meetRep = $this->getDoctrine()->getRepository(Meeting::class);
        $houseRep = $this->getDoctrine()->getRepository(House::class);
        /** @var House[] $houses */
        $houses = $houseRep->findBy(["org" =>$org]);
        $ids = [];
        foreach ($houses as $h) {
            $ids [] = $h->getId();
        }
        $meetings = $meetRep->findBy(["house" => $ids]);

        return $this->handleView($this->view(['status' => 'ok', 'meetings' => $meetings]));
    }

    /**
     * @Rest\Post("/api/meeting/vote")
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return Response
     * @throws \Exception
     */
    public function postVote(\Symfony\Component\HttpFoundation\Request $request)
    {
        $data = $request->request->all();
        $polRe = $this->getDoctrine()->getRepository(Poll::class);
        $accRe = $this->getDoctrine()->getRepository(Account::class);

        /** @var Poll $poll */
        $poll = $polRe->findOneBy(["id" => $data["poll"]]);

        /** @var Account $user */
        $user = $accRe->findOneBy(["vkToken" => $data["token"]]);

        $pollRes = new AccountPollResult();
        $pollRes->setPoll($poll);
        $pollRes->setAccount($user);
        $pollRes->setResult((int)$data["vote"]);


        $this->em->persist($pollRes);
        $this->em->flush();
        return $this->handleView($this->view(['status' => 'ok'], Response::HTTP_CREATED));
    }

    /**
     * @Rest\Post("/api/meeting/confirmParticipation")
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return Response
     * @throws \Exception
     */
    public function postParticipation(\Symfony\Component\HttpFoundation\Request $request)
    {
        $data = $request->request->all();
        $meetingRep = $this->getDoctrine()->getRepository(Meeting::class);
        $accRe = $this->getDoctrine()->getRepository(Account::class);

        /** @var Meeting $meeting */
        $meeting = $meetingRep->findOneBy(["id" => $data["meeting"]]);

        /** @var Account $user */
        $user = $accRe->findOneBy(["vkToken" => $data["token"]]);

        $meeting->addParticipant($user);
        $this->em->persist($meeting);
        $this->em->flush();
        return $this->handleView($this->view(['status' => 'ok'], Response::HTTP_CREATED));
    }


    /**
     * @Rest\Post("/api/meeting/create")
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return Response
     * @throws \Exception
     */
    public function postMeeting(\Symfony\Component\HttpFoundation\Request $request)
    {
        $data = $request->request->all();

        $data["plannedDate"] =  new \DateTime($request->get('date')) ?? new \DateTime();
        $data["plannedEndDate"] =  new \DateTime($request->get('date')) ?? new \DateTime("12.12.2099");

        $accountRep = $this->getDoctrine()->getRepository(Account::class);
        /** @var Account $house */
        $account = $accountRep->findOneBy(['vkToken' => $request->get('token')]);
        $house = $account->getOwnings()[0];

        $data["house"] = $house;
        $data["title"] = $request->get('title');
        $data["description"] = $request->get('desc');

        $rep = $this->getDoctrine()->getRepository(Meeting::class);
        /** @var Meeting $result */
        $result = $rep->createByArray($data);
        $this->em->persist($result);

        foreach ($data["meetingQuestions"] as $question) {
            $q = new MeetingQuestion();
            $q->setBody($question["body"]);
            $q->setMeeting($result);
            $q->setPoll($question["poll"] ? new Poll() : null);
            $q->setSubject($question["name"]);

            /** @var Attachment $file */
            foreach ($this->processFiles($request) as $file) {
                $file->setMeetingQuestion($q);
                $this->em->persist($file);
            }
            $this->em->persist($q);
        }


        $this->em->flush();
        return $this->handleView($this->view(['status' => 'ok', 'id' => $result->getId()], Response::HTTP_CREATED));
    }

}