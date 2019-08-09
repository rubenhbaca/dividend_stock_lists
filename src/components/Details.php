<?php

class Details extends stdClass
{

    private $details = null;

    public function __construct(array $details)
    {
        $this->details = $details;
    }

    public function getDetails(string $ticker) 
    {
        if (!array_key_exists($ticker, $this->details)) {
            return null;
        }

        return $this->details[$ticker];
    }

    public function getSection(string $ticker, string $section)
    {
        $details = $this->getDetails($ticker);

        if (is_null($details) || !array_key_exists($section, $details)) {
            return null;
        }

        return $details[$section];
    }

    public function getProperty(string $ticker, string $section, string $property)
    {
        $section = $this->getSection($ticker, $section);

        if(is_null($section) || !array_key_exists($property, $section)){
            return null;
        }

        return $section[$property];
    }
}
