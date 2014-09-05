<?php
/*************************************************************************************/
/*      Module Sections pour Thelia                                                  */
/*                                                                                   */
/*      Copyright (©) Informatique Prog                                              */
/*      email : contact@informatiqueprog.net                                         */
/*                                                                                   */
/*                                                         test utf-8 ä,ü,ö,ç,é,â,µ  */
/*************************************************************************************/

namespace Section\Controller\Admin;

use Section\Event\SectionDeleteEvent;
use Section\Event\SectionEvents;
use Section\Event\SectionToggleVisibilityEvent;
use Section\Event\SectionUpdateEvent;
use Section\Form\SectionAddForm;
use Section\Form\SectionUpdateForm;
use Section\Model\SectionQuery;
use Thelia\Controller\Admin\AbstractCrudController;
use Thelia\Controller\Admin\unknown;

class SectionController extends AbstractCrudController
{

    /**
     * @param string $objectName the lower case object name. Example. "message"
     *
     * @param string $defaultListOrder          the default object list order, or null if list is not sortable. Example: manual
     * @param string $orderRequestParameterName Name of the request parameter that set the list order (null if list is not sortable)
     *
     * @param string $resourceCode the 'resource' code. Example: "admin.configuration.message"
     *
     * @param string $createEventIdentifier the dispatched create TheliaEvent identifier. Example: TheliaEvents::MESSAGE_CREATE
     * @param string $updateEventIdentifier the dispatched update TheliaEvent identifier. Example: TheliaEvents::MESSAGE_UPDATE
     * @param string $deleteEventIdentifier the dispatched delete TheliaEvent identifier. Example: TheliaEvents::MESSAGE_DELETE
     *
     * @param string $visibilityToggleEventIdentifier the dispatched visibility toggle TheliaEvent identifier, or null if the object has no visible options. Example: TheliaEvents::MESSAGE_TOGGLE_VISIBILITY
     * @param string $changePositionEventIdentifier   the dispatched position change TheliaEvent identifier, or null if the object has no position. Example: TheliaEvents::MESSAGE_UPDATE_POSITION
     */
    public function __construct()
    {
        parent::__construct(
            'section',
            null,
            null,
            'admin.section',
            SectionEvents::SECTION_CREATE,
            SectionEvents::SECTION_UPDATE,
            SectionEvents::SECTION_DELETE,
            SectionEvents::SECTION_TOGGLE_VISIBILITY,
            null
        );
    }

    public function viewAction()
    {
        if (null !== $this->getExistingObject()) {
            $section = $this->getExistingObject();

            return $this->render('section-view', array('section_id' => $section->getId()));
        }
    }

    /**
     * Return the creation form for this object
     */
    protected function getCreationForm()
    {
        return new SectionAddForm($this->getRequest());
    }

    /**
     * Return the update form for this object
     */
    protected function getUpdateForm()
    {
        return new SectionUpdateForm($this->getRequest());
    }

    /**
     * @return KeywordToggleVisibilityEvent|void
     */
    protected function createToggleVisibilityEvent()
    {
        return new SectionToggleVisibilityEvent($this->getExistingObject());
    }

    /**
     * Hydrate the update form for this object, before passing it to the update template
     *
     * @param  unknown                                    $object
     * @return \Section\Form\SectionUpdateForm
     */
    protected function hydrateObjectForm($object)
    {
        // Prepare the data that will hydrate the form
        $data = array(
            'id'           => $object->getId(),
            'locale'       => $object->getLocale(),
            'title'        => $object->getTitle(),
            'description'  => $object->getDescription(),
            'visible'      => $object->getVisible()
        );

        // Setup the object form
        return new SectionUpdateForm($this->getRequest(), 'form', $data);
    }

    /**
     * Creates the creation event with the provided form data
     *
     * @param unknown $formData
     */
    protected function getCreationEvent($formData)
    {
        $sectionCreateEvent = new SectionEvents($formData['title'], $formData['visible'], $formData['locale']);

        return $sectionCreateEvent;
    }

    /**
     * Creates the update event with the provided form data
     *
     * @param unknown $formData
     */
    protected function getUpdateEvent($formData)
    {
        $sectionUpdateEvent = new SectionUpdateEvent($formData['id']);

        $sectionUpdateEvent
            ->setLocale($formData['locale'])
            ->setTitle($formData['title'])
            ->setDescription($formData['description'])
            ->setVisible($formData['visible']);

        return $sectionUpdateEvent;
    }

    /**
     * Creates the delete event with the provided form data
     */
    protected function getDeleteEvent()
    {
        return new SectionDeleteEvent($this->getRequest()->get('section_id'), 0);
    }

    /**
     * Return true if the event contains the object, e.g. the action has updated the object in the event.
     *
     * @param  \Section\Event\SectionEvents $event
     * @return bool
     */
    protected function eventContainsObject($event)
    {
        return $event->hasSection();
    }

    /**
     * Get the created object from an event.
     *
     * @param unknown $event
     */
    protected function getObjectFromEvent($event)
    {
        // TODO: Implement getObjectFromEvent() method.
    }

    /**
     * Load an existing object from the database
     */
    protected function getExistingObject()
    {
        $section = SectionQuery::create()
            ->findOneById($this->getRequest()->get('section_id', 0));

        if (null !== $section) {
            $section->setLocale($this->getCurrentEditionLocale());
        }

        return $section;
    }

    /**
     * Returns the object label form the object event (name, title, etc.)
     *
     * @param unknown $object
     */
    protected function getObjectLabel($object)
    {
        // TODO: Implement getObjectLabel() method.
    }

    /**
     * Returns the object ID from the object
     *
     * @param unknown $object
     */
    protected function getObjectId($object)
    {
        // TODO: Implement getObjectId() method.
    }

    /**
     * Render the main list template
     *
     * @param unknown $currentSection , if any, null otherwise.
     */
    protected function renderListTemplate($currentSection)
    {
        return $this->render('module-configure',
            array(
                'module_code' => 'Section',
                'code' => 'section',
                'section_order' => $currentSection
            ));
    }

    protected function getEditionArguments()
    {
        return array(
            'section_id' => $this->getRequest()->get('section_id', 0)
        );
    }

    /**
     * Render the edition template
     */
    protected function renderEditionTemplate()
    {
        return $this->render('section-edit', $this->getEditionArguments());
    }

    /**
     * Redirect to the edition template
     */
    protected function redirectToEditionTemplate()
    {
        $args = $this->getEditionArguments();
        $this->redirect('/admin/module/Section/update?section_id='.$args['section_id']);
    }

    /**
     * Redirect to the list template
     */
    protected function redirectToListTemplate()
    {
        $this->redirect('/admin/module/Section');
    }

    protected function performAdditionalUpdateAction($updateEvent)
    {
        if ($this->getRequest()->get('save_mode') != 'stay') {
            $this->redirectToListTemplate();
        }
    }

}
