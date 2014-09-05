<?php
/*************************************************************************************/
/*      Module Sections pour Thelia                                                  */
/*                                                                                   */
/*      Copyright (©) Informatique Prog                                              */
/*      email : contact@informatiqueprog.net                                         */
/*                                                                                   */
/*                                                         test utf-8 ä,ü,ö,ç,é,â,µ  */
/*************************************************************************************/

namespace Section\Event;

class SectionUpdateEvent extends SectionEvents
{

    protected $section_id;
    protected $description;

    /**
     * @param int $section_id
     */
    public function __construct($section_id)
    {
        $this->section_id = $section_id;
    }

    /**
     * @param int $section_id
     *
     * @return $this
     */
    public function setSectionId($section_id)
    {
        $this->section_id = $section_id;

        return $this;
    }

    /**
     * @return int
     */
    public function getSectionId()
    {
        return $this->section_id;
    }

    /**
     * @param mixed $description
     *
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

}
