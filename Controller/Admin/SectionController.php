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
use Thelia\Tools\URL;

class SectionController extends BaseAdminController
{


    /**
     * @return Thelia\Core\HttpFoundation\Response the response
     */
    public function viewAll()
    {
        if (null !== $response = $this->checkAuth([], ['Section'], AccessManager::VIEW)) {
            return $response;
        }

        return $this->render('sections');
    }

    /**
     * @return Thelia\Core\HttpFoundation\Response the response
     */
    public function create()
    {
        if (null !== $response = $this->checkAuth([], ['Section'], AccessManager::CREATE)) {
            return $response;
        }

        $form = new SectionCreateForm($this->getRequest());

        try {
            $sectionForm = $this->validateForm($form);

            $section = (new Section())
                ->setLocale($sectionForm->get('locale')->getData())
                ->setTitle($sectionForm->get('title')->getData())
                ->setVisible($sectionForm->get('visible')->getData())
                ->save();
        } catch(\Exception $e) {
            $this->setupFormErrorContext(
                $this->getTranslator()->trans('Manage sections', [], 'section.bo.default'),
                $e->getMessage(),
                $form,
                $e
            );
        }

        return $this->render('sections');
    }

    /**ss
     * @return Thelia\Core\HttpFoundation\Response the response
     */
    public function edit()
    {
        if (null !== $response = $this->checkAuth([], ['Section'], AccessManager::UPDATE)) {
            return $response;
        }

        $section_id = $this->getRequest()->query->get('section_id');

        if (null !== $section = $this->getExistingObject($section_id)) {

            $data = array(
                'id'           => $section->getId(),
                'locale'       => $section->getLocale(),
                'title'        => $section->getTitle(),
                'description'  => $section->getDescription(),
                'visible'      => $section->getVisible()
            );

            $editForm = new SectionUpdateForm($this->getRequest(), 'form', $data);

            $this->getParserContext()->addForm($editForm);
        }

        return $this->render('section-edit', ['section_id' => $section_id]);
    }

    /**
     * @return Thelia\Core\HttpFoundation\Response the response
     */
    public function update()
    {
        if (null !== $response = $this->checkAuth([], ['Section'], AccessManager::UPDATE)) {
            return $response;
        }

        $error_msg = null;

        $section_id = $this->getRequest()->request->get('section_id');

        $sectionUpdateForm = new SectionUpdateForm($this->getRequest());

        try {
            if (null === $section = $this->getExistingObject($section_id)) {
                throw new \InvalidArgumentException(sprintf("%d section id does not exist", $section_id));
            }

            $form = $this->validateForm($sectionUpdateForm);

            $section
                ->setLocale($form->get('locale')->getData())
                ->setTitle($form->get('title')->getData())
                ->setVisible($form->get('visible')->getData())
                ->setDescription($form->get('description')->getData())
                ->save();

            $this->adminLogAppend('section', AccessManager::UPDATE, 'Section updated');
        } catch (FormValidationException $e) {
            $error_msg = $this->createStandardFormValidationErrorMessage($e);
        } catch (\Exception $e) {
            $error_msg = $e->getMessage();
        }

        if (null !== $error_msg) {
            $this->setupFormErrorContext('Section update', $error_msg, $sectionUpdateForm);
        }

        if ($this->getRequest()->get('save_mode') == 'stay' || null !== $error_msg) {
            $response = RedirectResponse::create(URL::getInstance()->absoluteUrl('/admin/module/Section/edit', ['section_id' => $section_id]));
        } else {
            $response = RedirectResponse::create(URL::getInstance()->absoluteUrl('/admin/module/Section'));
        }

        return $response;
    }

    /**
     * @return Thelia\Core\HttpFoundation\Response the response
     */
    public function delete()
    {
        if (null !== $response = $this->checkAuth([], ['Section'], AccessManager::DELETE)) {
            return $response;
        }

        $section_id = $this->getRequest()->request->get('section_id');

        if (null !== $section = $this->getExistingObject($section_id)) {
            $section->delete();
        }

        return $this->render('sections');
    }

    /**
     * @return Thelia\Core\HttpFoundation\Response the response
     */
    public function setToggleVisibility()
    {
        if (null !== $response = $this->checkAuth([], ['Section'], AccessManager::UPDATE)) {
            return $response;
        }

        $section_id = $this->getRequest()->query->get('section_id');

        if (null !== $section = $this->getExistingObject($section_id)) {
            $section
                ->setVisible(!$section->getVisible())
                ->save();
        }

        return $this->render('sections');
    }

    /**
     * LOAD Section FROM THE DATABASE
     * @param int $section_id
     * @return Section Object Model
     */
    protected function getExistingObject($section_id = 0)
    {
        $section = SectionQuery::create()->findPk( (int)$section_id );

        if (null !== $section) {
            $section->setLocale($this->getCurrentEditionLocale());
        }

        return $section;
    }

}
