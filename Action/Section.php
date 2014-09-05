<?php
/*************************************************************************************/
/*      Module Sections pour Thelia                                                  */
/*                                                                                   */
/*      Copyright (©) Informatique Prog                                              */
/*      email : contact@informatiqueprog.net                                         */
/*                                                                                   */
/*                                                         test utf-8 ä,ü,ö,ç,é,â,µ  */
/*************************************************************************************/

namespace Section\Action;

use Section\Event\SectionDeleteEvent;
use Section\Event\SectionEvents;
use Section\Event\SectionToggleVisibilityEvent;
use Section\Event\SectionUpdateEvent;
use Section\Model\SectionQuery;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class Section implements EventSubscriberInterface
{

    public function createSection(SectionEvents $event)
    {
        $section = new \Section\Model\Section();
        $section
            ->setLocale($event->getLocale())
            ->setTitle($event->getTitle())
            ->setVisible($event->getVisible())
            ->save();

        $event->setSection($section);
    }

    public function deleteSection(SectionDeleteEvent $event)
    {
        if (null !== $section = SectionQuery::create()->findPk($event->getSectionId())) {

            $section->delete();

            $event->setSection($section);
        }
    }

    public function updateSection(SectionUpdateEvent $event)
    {
        if (null !== $section = SectionQuery::create()->findPk($event->getSectionId())) {

            $section
                ->setLocale($event->getLocale())
                ->setTitle($event->getTitle())
                ->setVisible($event->getVisible())
                ->setDescription($event->getDescription())
                ->save();

            $event->setSection($section);
        }
    }

    public function toggleVisibilitySection(SectionToggleVisibilityEvent $event)
    {
        $section = $event->getSection();

        $section
            ->setVisible(!$section->getVisible())
            ->save();

        $event->setSection($section);
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
     *
     * @return array The event names to listen to
     *
     * @api
     */
    public static function getSubscribedEvents()
    {
        return array(
            SectionEvents::SECTION_CREATE                       => array('createSection', 128),
            SectionEvents::SECTION_UPDATE                       => array('updateSection', 128),
            SectionEvents::SECTION_DELETE                       => array('deleteSection', 128),
            SectionEvents::SECTION_TOGGLE_VISIBILITY            => array('toggleVisibilitySection', 128)
        );
    }

}
