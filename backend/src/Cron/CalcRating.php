<?php


namespace App\Cron;


use App\Entity\House;
use App\Entity\Organisation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CalcRating extends Command
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

        $this->setName('cron:rate')->setDescription('')->setHelp("");
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $orgs = $this->entityManager->getRepository(Organisation::class);

        /** @var Organisation[] $ors */
        $ors = $orgs->findAll();
        $maxClaims = 0;
        $maxPersonalPerHouse = 0;
        $maxRejected = 0;
        foreach ($ors as $organisation) {
            $organisation->getClaimsCount();
            if($organisation->getClaimsCount() > $maxClaims) {
                $maxClaims = $organisation->getClaimsCount();
            }
            if($organisation->getRejectedHousesCount() > $maxRejected) {
                $maxRejected = $organisation->getRejectedHousesCount();
            }
            $c = (float) $organisation->getHouseCount() == 0 ? 0 : (((float)$organisation->getStaffCount()) / (float) $organisation->getHouseCount());
            if($c > $maxPersonalPerHouse && $c < 10) {
                $maxPersonalPerHouse = $c;
                if($c == 57) {
                    $output->write("xxxxxxxxxxxxxxxxxxx\n" . $organisation->getStaffCount() . " " .(float) $organisation->getHouseCount() . "\n\n");
                }
            }
        }

        foreach ($ors as $organisation) {
            $x1 = 1 - (((float)$organisation->getClaimsCount()) / $maxClaims);
            $x2 =1 - (((float)$organisation->getRejectedHousesCount()) / $maxRejected) ;
            $c =  $organisation->getHouseCount() == 0 ? 0 : (((float)$organisation->getStaffCount()) / (float) $organisation->getHouseCount());
            $x3 = ((float)($c > 9 ? 9 : $c)  ) / $maxPersonalPerHouse;

            $rate = (0.6 * ($x1) + 0.2 * $x2 + 0.2 * $x3) * 5 + 3.5;

            $organisation->setRespectIndex($rate);

        }

        usort(/**
         * @param Organisation $v1
         * @param Organisation $v2
         * @return mixed
         */ $ors, function ($v1, $v2) {
            /** @var Organisation $v1 */
            return $v1->getRespectIndex() < $v2->getRespectIndex();
    });

        foreach ($ors as $k => $organisation) {
            $organisation->setRatingPosition($k);
            $this->entityManager->persist($organisation);
        }

        $this->entityManager->flush();

        return 1;
    }

}