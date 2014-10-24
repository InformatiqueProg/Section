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

use Symfony\Component\Validator\Constraints\NotBlank;
use Thelia\Core\Translation\Translator;
use Thelia\Form\BaseForm;

class SectionCreateForm extends BaseForm
{

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
        $Translator = Translator::getInstance();
        $this->formBuilder
            ->add('title', 'text', array(
                'constraints' => array(
                    new NotBlank()
                ),
                'label' => $Translator->trans('Title'),
                'label_attr' => array(
                    'for' => 'sections_title'
                )
            ))
            ->add('description', 'text', array(
                'label' => $Translator->trans('Code HTML', [], 'section'),
                'label_attr' => array(
                    'for' => 'sections_description'
                ),
                'required' => false
            ))
            ->add('visible', 'integer', array(
                'label' => $Translator->trans('Visible ?'),
                'label_attr' => array(
                    'for' => 'sections_visible'
                )
            ))
            ->add('locale', 'text', array(
                'constraints' => array(
                    new NotBlank()
                )
            ))
        ;
    }

    /**
     * @return string the name of you form. This name must be unique
     */
    public function getName()
    {
        return 'section_create_form';
    }
}
