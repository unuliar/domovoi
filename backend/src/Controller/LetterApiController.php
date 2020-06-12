<?php


namespace App\Controller;


use App\Entity\Attachment;
use App\Entity\House;
use App\Entity\Meeting;
use App\Entity\MeetingQuestion;
use App\Entity\Poll;
use Symfony\Component\HttpFoundation\Response;

class LetterApiController
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
        $data = $request->request->all();

        $data["plannedDate"] =  new \DateTime($request->get('plannedDate')) ?? new \DateTime();
        $data["plannedEndDate"] =  new \DateTime($request->get('plannedEndDate')) ?? new \DateTime("12.12.2099");

        $houseRep = $this->getDoctrine()->getRepository(House::class);
        /** @var House $house */
        $house = $houseRep->findOneBy(["id" => $data["house"]]);

        $data["house"] = $house;


        $rep = $this->getDoctrine()->getRepository(Meeting::class);
        /** @var Meeting $result */
        $result = $rep->createByArray($data);
        $this->em->persist($result);

        foreach ($data["meetingQuestions"] as $question) {
            $q = new MeetingQuestion();
            $q->setBody($question["body"]);
            $q->setMeeting($result);
            $q->setPoll($question["poll"] ? new Poll() : null);
            $q->setSubject($question["subject"]);

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