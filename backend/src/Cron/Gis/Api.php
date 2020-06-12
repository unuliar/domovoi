<?php


namespace App\Cron\Gis;


use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class Api
{
    const ORG_LIST_URL = 'https://dom.gosuslugi.ru/ppa/api/rest/services/ppa/public/organizations/searchByOrg?pageIndex=1&elementsPerPage=1000';
    const ORG_BY_GUID = "https://dom.gosuslugi.ru/ppa/api/rest/services/ppa/public/organizations/orgByGuid?organizationGuid=";
    const HOUSE_BY_GUID = "https://dom.gosuslugi.ru/information-disclosure/api/rest/services/disclosures/mkd/house-info?houseGuid=";
    const ORG_BY_ROOT_ID = "https://dom.gosuslugi.ru/information-disclosure/api/rest/services/disclosures/org/common/";

    const HOUSES_BY_ORG = "https://dom.gosuslugi.ru/information-disclosure/api/rest/services/disclosures/org/mkd";
    const ORG_VIOLATIONS = "https://dom.gosuslugi.ru/information-disclosure/api/rest/services/disclosures/org/adm/violation/";
    private $httpClient;


//https://dom.gosuslugi.ru/ppa/api/rest/services/ppa/public/organizations/orgByGuid?organizationGuid=c684ae62-c3fc-4125-940d-b165f26d7900
//['registryOrganizationRootEntityGuid']
//https://dom.gosuslugi.ru/information-disclosure/api/rest/services/disclosures/org/common/1a664a06-56c2-491a-b3f3-ffdf6606bc38

//https://dom.gosuslugi.ru/#!/org-info-mkd?orgRootGuid=1a664a06-56c2-491a-b3f3-ffdf6606bc38&orgGuid=c684ae62-c3fc-4125-940d-b165f26d7900

//https://dom.gosuslugi.ru/information-disclosure/api/rest/services/disclosures/mkd/house-info?houseGuid=6b579ebc-abc0-45d4-8911-747048c8cdee

    public function __construct()
    {
        $this->httpClient = HttpClient::create();
    }

    public function getOrgViolationsByRoot($rootId) {
        $response = $this->httpClient->request('GET', self::ORG_VIOLATIONS . urlencode($rootId) );
        return $response->toArray();
    }

    public function getOrgHousesByGuid($guid, $term) {
        $root = $this->getOrgRootIdByGuid($guid);
        return $this->getOrgHouses($root, $term);
    }

    public function getOrgHouses($orgRoot, $terminated = false) {
        $houses = [];
        $elemsPP = 100;
        $data = [
            'json' => [
                "terminate" => $terminated,
                "pageIndex" => 1,
                "orgRootGuid" => $orgRoot,
                "address" => [
                    "regionCode" => "8bcec9d6-05bc-4e53-b45c-ba0c6f3a5c44",
                ],
                "elementsPerPage" => $elemsPP,
            ],
        ];
        $response = $this->httpClient->request('POST', self::HOUSES_BY_ORG , $data);
        $response = $response->toArray();

        while (count($response["managedHouses"]) != 0) {
            foreach ($response['managedHouses'] as $k => $v) {
                $houses[] = [
                    "guid" => $v["houseGuid"],
                    "fiasHouseCode" => $v["fiasHouseCode"],
                    "managedPeriod" => $v["managedPeriod"],//endDate
                    "address" => $v["address"]
                ];
            }
            $data['json']['pageIndex']++;
            $response = $this->httpClient->request('POST', self::HOUSES_BY_ORG , $data)->toArray();
        }
        return $houses;

    }

    public function getHouseByGuid($guid) : array {
        $response = $this->httpClient->request('GET', self::HOUSE_BY_GUID . urlencode($guid) );
        return $response->toArray();
    }

    public function getOrgByGuid($guid) : array  {
        return $this->getOrgByRootId($this->getOrgRootIdByGuid($guid));
    }

    public function getOrgRootIdByGuid($guid) : string {
        $response = $this->httpClient->request('GET', self::ORG_BY_GUID . urlencode($guid) );
        return $response->toArray()["registryOrganizationRootEntityGuid"];
    }

    public function getOrgByRootId($root) : array {
        $response = $this->httpClient->request('GET', self::ORG_BY_ROOT_ID . urlencode($root) );
        return $response->toArray();
    }


    public function getOrenOrgGuids() : array {
        $response = $this->httpClient->request('POST', self::ORG_LIST_URL , [
            'json' => [
                "factualAddress" => (new ObjectNormalizer())->normalize(new FactualAddress()),
                "okopfList" => [],
                "onlyHeadOrganization" => true,
                "organizationRoleList" => [
                    //data types
                    ["code" => "19", "guid" => "708b663b-8cb8-4114-84bc-e6b909c7f714"],
                    ["code" => "20", "guid" => "b9468598-0535-4d76-a462-32082dce86b7"],
                    ["code" => "21", "guid" => "d7625e16-19fd-4dea-a3e3-3999a195e1c9"],
                    ["code" => "22", "guid" => "5c408b08-0a95-4ea2-8d78-55af6e6a5ac5"],
                    ["code" => "1", "guid" => "52f5e0a4-7f4c-4fb1-9423-160e9178ddda"],
                ],
                "organizationStatuses" => [
                    "coll" => ["REGISTERED"],
                    "operand" => "OR"
                ],
                "roleRegionCode" => "8bcec9d6-05bc-4e53-b45c-ba0c6f3a5c44",
                "roleStatuses" => ["APPROVED"],
            ],
        ]);
        $result = [];
        foreach ($response->toArray()["organizationSummaryWithNsiList"] as $item) {
            $result[] = $item["guid"];
        }
        return $result;
    }
}