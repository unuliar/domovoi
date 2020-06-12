<?php


namespace App\Cron\Gis;


class FactualAddress
{
    /**
     * @return null
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * @param null $area
     */
    public function setArea($area)
    {
        $this->area = $area;
    }

    /**
     * @return null
     */
    public function getBuildingNumber()
    {
        return $this->buildingNumber;
    }

    /**
     * @param null $buildingNumber
     */
    public function setBuildingNumber($buildingNumber)
    {
        $this->buildingNumber = $buildingNumber;
    }

    /**
     * @return null
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param null $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return null
     */
    public function getHouse()
    {
        return $this->house;
    }

    /**
     * @param null $house
     */
    public function setHouse($house)
    {
        $this->house = $house;
    }

    /**
     * @return null
     */
    public function getHouseNumber()
    {
        return $this->houseNumber;
    }

    /**
     * @param null $houseNumber
     */
    public function setHouseNumber($houseNumber)
    {
        $this->houseNumber = $houseNumber;
    }

    /**
     * @return null
     */
    public function getPlanningStructureElement()
    {
        return $this->planningStructureElement;
    }

    /**
     * @param null $planningStructureElement
     */
    public function setPlanningStructureElement($planningStructureElement)
    {
        $this->planningStructureElement = $planningStructureElement;
    }

    /**
     * @return null
     */
    public function getSettlement()
    {
        return $this->settlement;
    }

    /**
     * @param null $settlement
     */
    public function setSettlement($settlement)
    {
        $this->settlement = $settlement;
    }

    /**
     * @return null
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param null $region
     */
    public function setRegion($region)
    {
        $this->region = $region;
    }

    /**
     * @return null
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param null $street
     */
    public function setStreet($street)
    {
        $this->street = $street;
    }

    /**
     * @return null
     */
    public function getStructNumber()
    {
        return $this->structNumber;
    }

    /**
     * @param null $structNumber
     */
    public function setStructNumber($structNumber)
    {
        $this->structNumber = $structNumber;
    }
    private $area = null;
    private $buildingNumber = null;
    private $city = null;
    private $house = null;
    private $houseNumber = null;
    private $planningStructureElement = null;
    private $settlement = null;
    private $region = null;
    private $street = null;
    private $structNumber = null;


    public function __construct()
    {
    }
}