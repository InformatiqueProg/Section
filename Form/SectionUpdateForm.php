<?php
/*************************************************************************************/
/*      Module Sections pour Thelia                                                  */
/*                                                                                   */
/*      Copyright (©) Informatique Prog                                              */
/*      email : contact@informatiqueprog.net                                         */
/*                                                                                   */
/*                                                         test utf-8 ä,ü,ö,ç,é,â,µ  */
/*************************************************************************************/

namespace Section\Form;

use Symfony\Component\Validator\Constraints\GreaterThan;
use Thelia\Form\StandardDescriptionFieldsTrait;

class SectionUpdateForm extends SectionCreateForm
{

    use StandardDescriptionFieldsTrait;

    /**
     *
     * in this function you add all the fields you need for your Form.
     * Form this you have to call add method on $this->formBuilder attribute :
     *
     * $this->formBuilder->add("name", "text")
     *   ->add("email", "email", array(
     *           "attr" => array(
     *               "class" => "field"
     *           ),
     *           "label" => "email",
     *           "constraints" => array(
     *               new \Symfony\Component\Validator\Constraints\NotBlank()
     *           )
     *       )
     *   )
     *   ->add('age', 'integer');
     *
     * @return null
     */
    protected function buildForm()
    {
        parent::buildForm();

        $this->formBuilder->add('id', 'hidden', array('constraints' => array(new GreaterThan(array('value' => 0)))));

        // Add standard description fields, excluding title and locale, which a re defined in parent class
        $this->addStandardDescFields(array('title', 'locale', 'chapo', 'postscriptum'));
    }

    /**
     * @return string the name of you form. This name must be unique
     */
    public function getName()
    {
        return 'section_update_form';
    }
}
