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

use Section\Form\SectionCreateForm;
use Section\Form\SectionUpdateForm;
use Section\Model\Section;
use Section\Model\SectionQuery;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\Security\AccessManager;
use Thelia\Core\Security\Resource\AdminResources;
use Thelia\Tools\URL;

class SectionController extends BaseAdminController
{
    
    /**
     * CREATE ACTION
     * 
     * @return Thelia\Core\HttpFoundation\Response the response
     */
    public function createAction()
    {
        if (null !== $response = $this->checkAuth([''], ['Section'], AccessManager::CREATE)) {
            return $response;
        }

        $form = new SectionCreateForm($this->getRequest());

        try {
            $sectionForm = $this->validateForm($form);

            $Section = (new Section())
                ->setLocale($sectionForm->get('locale')->getData())
                ->setTitle($sectionForm->get('title')->getData())
                ->setVisible($sectionForm->get('visible')->getData())
                ->save();

        } catch(\Exception $e) {
            $this->setupFormErrorContext(
                $this->getTranslator()->trans('Manage sections'),
                $e->getMessage(),
                $form,
                $e
            );

        }

        // Render
        return $this->render('module-configure', ['module_code' => 'Section']);
    }

    /**
     * EDIT ACTION
     * 
     * @return Thelia\Core\HttpFoundation\Response the response
     */
    public function editAction()
    {
        if (null !== $response = $this->checkAuth([''], ['Section'], AccessManager::UPDATE)) {
            return $response;
        }

        $section_id = $this->getRequest()->query->get('section_id');

        // Load Section if exist
        if (null !== $Section = $this->getExistingObject($section_id)) {

            // Prepare the data that will hydrate the form
            $data = array(
                'id'           => $Section->getId(),
                'locale'       => $Section->getLocale(),
                'title'        => $Section->getTitle(),
                'description'  => $Section->getDescription(),
                'visible'      => $Section->getVisible()
            );

            // Setup the Section form
            $editForm = new SectionUpdateForm($this->getRequest(), 'form', $data);

            // Pass it to the parser
            $this->getParserContext()->addForm($editForm);
        }

        // Render
        return $this->render('section-edit', ['section_id' => $section_id]);
    }

    /**
     * UPDATE ACTION
     * 
     * @return Thelia\Core\HttpFoundation\Response the response
     */
    public function updateAction()
    {
        if (null !== $response = $this->checkAuth([''], ['Section'], AccessManager::UPDATE)) {
            return $response;
        }

        $error_msg = null;

        $section_id = $this->getRequest()->request->get('section_id');

        $SectionUpdateForm = new SectionUpdateForm($this->getRequest());

        try {
            if (null === $Section = $this->getExistingObject($section_id)) {
                throw new \InvalidArgumentException(sprintf("%d section id does not exist", $section_id));
            }

            // Validate form
            $form = $this->validateForm($SectionUpdateForm);

            // Update
            $Section
                ->setLocale($form->get('locale')->getData())
                ->setTitle($form->get('title')->getData())
                ->setVisible($form->get('visible')->getData())
                ->setDescription($form->get('description')->getData())
                ->save();

            // AdminLog
            $this->adminLogAppend('section', AccessManager::UPDATE, 'Section updated');

        } catch (FormValidationException $e) {
            $error_msg = $this->createStandardFormValidationErrorMessage($e);
        } catch (\Exception $e) {
            $error_msg = $e->getMessage();
        }

        // Error
        if (null !== $error_msg) {
            $this->setupFormErrorContext('Section update', $error_msg, $SectionUpdateForm);
        }

        // STAY OR NOT
        if ($this->getRequest()->get('save_mode') == 'stay' || null !== $error_msg) {
            $response = RedirectResponse::create(URL::getInstance()->absoluteUrl('/admin/module/Section/edit', ['section_id' => $section_id]));
        } else {
            $response = RedirectResponse::create(URL::getInstance()->absoluteUrl('/admin/module/Section'));
        }

        return $response;
    }

    /**
     * DELETE ACTION
     * 
     * @return Thelia\Core\HttpFoundation\Response the response
     */
    public function deleteAction()
    {
        if (null !== $response = $this->checkAuth([''], ['Section'], AccessManager::DELETE)) {
            return $response;
        }

        $section_id = $this->getRequest()->request->get('section_id');

        if (null !== $Section = $this->getExistingObject($section_id)) {
            $Section->delete();
        }

        // Render
        return $this->render('module-configure', ['module_code' => 'Section']);
    }

    /**
     * TOGGLE VISIBLE ACTION
     * 
     * @return Thelia\Core\HttpFoundation\Response the response
     */
    public function setToggleVisibilityAction()
    {
        if (null !== $response = $this->checkAuth([''], ['Section'], AccessManager::UPDATE)) {
            return $response;
        }

        $section_id = $this->getRequest()->query->get('section_id');

        if (null !== $Section = $this->getExistingObject($section_id)) {
            $Section
                ->setVisible(!$Section->getVisible())
                ->save();
        }

        // Render
        return $this->render('module-configure', ['module_code' => 'Section']);
    }

    /**
     * LOAD Section FROM THE DATABASE
     * 
     * @param int $section_id
     * @return Section Object Model
     */
    protected function getExistingObject($section_id=0)
    {
        $Section = SectionQuery::create()->findPk( (int)$section_id );

        if (null !== $Section) {
            $Section->setLocale($this->getCurrentEditionLocale());
        }

        return $Section;
    }

}
