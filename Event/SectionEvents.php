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

use Section\Model\Section;
use Thelia\Core\Event\ActionEvent;

class SectionEvents extends ActionEvent
{

    const SECTION_CREATE            = 'section.action.create';
    const SECTION_UPDATE            = 'section.action.update';
    const SECTION_DELETE            = 'section.action.delete';
    const SECTION_TOGGLE_VISIBILITY = 'section.action.toggleVisibility';

    protected $locale;
    protected $title;
    protected $visible;
    protected $sectionId;

    public function __construct($title, $visible, $locale)
    {
        $this->title = $title;
        $this->visible = $visible;
        $this->locale = $locale;
    }

    /**
     * @param mixed $locale
     *
     * @return $this
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLocale()
    {
        return $this->locale;
    }

     /**
     * @param mixed $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $visible
     *
     * @return $this
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * @param  \Section\Model\Section $section
     * @return $this
     */
    public function setSection(Section $section)
    {
        $this->section = $section;

        return $this;
    }

    /**
     * @return \Section\Model\Section
     */
    public function getSection()
    {
        return $this->section;
    }

    /**
     * check if section exists
     *
     * @return bool
     */
    public function hasSection()
    {
        return null !== $this->section;
    }

}
