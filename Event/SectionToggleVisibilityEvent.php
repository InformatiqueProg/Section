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

class SectionToggleVisibilityEvent extends SectionEvents
{

    protected $section;

    /**
     * @param  \Section\Model\Section $section
     */
    public function __construct(Section $section)
    {
        $this->section = $section;
    }

}
