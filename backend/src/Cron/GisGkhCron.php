<?php


namespace App\Cron;
require dirname(__DIR__) . "/../vendor/autoload.php";

use App\Cron\Gis\Api;
use App\Cron\Gis\FactualAddress;
use App\Entity\House;
use App\Entity\Organisation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GisGkhCron extends Command
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        //(new Dotenv())->bootEnv(dirname(__DIR__).'/../.env2');
        $this->entityManager = $entityManager;


        parent::__construct();
    }

    protected function configure()
    {
        //error_log(print_r('test',true), 3, "/tmp/error.log");

        $this->setName('cron:parseg')->setDescription('')->setHelp("");
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'start',
            '============',
            '',
        ]);
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);

        $api = new Api();
        $ids = $api->getOrenOrgGuids();

        $houseEm = $this->entityManager->getRepository(House::class);

        foreach ($ids as $k =>  $id) {
            if($k < 118) {
                continue;
            }

                $org = new Organisation();
                $root = $api->getOrgRootIdByGuid($id);

                $data = $api->getOrgByRootId($root);


                $org->setRootObjId($root);
                $org->setEmail($data["detailInfo"]["email"])
                    ->setAddress($data["detailInfo"]["managingAuthorityAddress"]);
                $org->setFullName($data["detailInfo"]["fullName"]);
                $org->setName($data["detailInfo"]["name"]);
                $org->setHouseCount($data["detailInfo"]["managedHouseCount"]);
                $org->setManagedSquare($data["detailInfo"]["managedHouseSquare"]);
                $org->setPhone($data["detailInfo"]["phone"]);
                $org->setSite($data["detailInfo"]["www"]);
                $org->setStaffCount($data["staffInfo"]["totalStaffCount"]);
                //$org->setSchedule($data["schedule"]);
                $org->setLicenseDate(new \DateTime($data["licenses"][0]["licenseDate"]));
                $org->setSvcPhones($data["detailInfo"]["dispatchingServicePhones"]);
                $org->setRejectedHousesCount(0);
            try {
                $org->setClaimsCount(count($api->getOrgViolationsByRoot($root)["administrationViolationList"]));
            }catch (\Exception $e) {
          /*      if(strpos($e->getMessage(), "Time-out") === false && strpos($e->getMessage(), "timeout") === false) {
                    throw $e;
                }*/
                $output->write("failed getting violations" . "\n\n");
            }



                $houses = $api->getOrgHouses($root, true);

                foreach ($houses as $terminated) {
                    if(time() - strtotime($terminated["managedPeriod"]["endDate"]) < 60 * 60 * 24 * 365) {
                        $org->setRejectedHousesCount($org->getRejectedHousesCount()+1);
                    }
                }

                $houses = $api->getOrgHouses($root, false);

                foreach ($houses as $working) {
                    $h = new House();
                    $h->setGuid($working["guid"]);
                    $h->setAddress($working["address"]);
                    $h->setBuildingYear(0);
                    if(trim($working["guid"]) != '') {
                        /** @var House $res */
                        if($working["guid"] == "3f803765-82a1-4d01-a3b4-d6f5829657ff") {
                            continue;
                        }
                        $houseData = $api->getHouseByGuid($working["guid"]);
                        $h->setBuildingYear((int)$houseData["info"]["buildingYear"]);
                        $h->setResidentalPremiseCount((int) ($houseData["info"]["residentialPremiseCount"] ?? 0));
                        $h->setResidentialPremiseTotalSquare((int)($houseData["info"]["residentialPremiseTotalSquare"] ?? 0));
                        $h->setOrg($org);
                    }
                    $this->entityManager->persist($h);

                    //$org->addHouse($h);
                }
                $this->entityManager->persist($org);
                $this->entityManager->flush();
                $output->write($k . "\n\n");

        }





      //  $output->write($serializer->serialize($org, 'json'));

        return 1;
    }
}



/*$hids = $api->getOrgHousesByGuid($ids[0], true);
var_dump($hids[0]);
var_dump($api->getHouseByGuid($hids[0]["guid"]));*/

//https://dom.gosuslugi.ru/ppa/api/rest/services/ppa/public/organizations/orgByGuid?organizationGuid=c684ae62-c3fc-4125-940d-b165f26d7900
//['registryOrganizationRootEntityGuid']
//https://dom.gosuslugi.ru/information-disclosure/api/rest/services/disclosures/org/common/1a664a06-56c2-491a-b3f3-ffdf6606bc38

//https://dom.gosuslugi.ru/#!/org-info-mkd?orgRootGuid=1a664a06-56c2-491a-b3f3-ffdf6606bc38&orgGuid=c684ae62-c3fc-4125-940d-b165f26d7900

//https://dom.gosuslugi.ru/information-disclosure/api/rest/services/disclosures/mkd/house-info?houseGuid=6b579ebc-abc0-45d4-8911-747048c8cdee