<?
/*************************************************************************************/
/*      Module Sections pour Thelia                                                  */
/*                                                                                   */
/*      Copyright (©) Informatique Prog                                              */
/*      email : contact@informatiqueprog.net                                         */
/*                                                                                   */
/*                                                         test utf-8 ä,ü,ö,ç,é,â,µ  */
/*************************************************************************************/

namespace Section\SmartyFilter\Filter;

use Section\Section;
use Section\Model\SectionQuery;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Thelia\Core\HttpFoundation\Request;
use Thelia\Core\Template\ParserInterface;
use Thelia\Core\Template\TemplateDefinition;

class SectionFilter
{

    protected $parser;
    protected $request;

    public function __construct(ParserInterface $parser, Request $request)
    {
        $this->parser = $parser;
        $this->request = $request;
    }

    public function filter($tpl_output, $smarty)
    {
        if ($this->parser->getTemplateDefinition()->getType() == TemplateDefinition::FRONT_OFFICE) {
            $sectionTags = [];

            if (!preg_match_all(Section::SECTIONTAG_REGEX, $tpl_output, $sectionTags)) {
                return $tpl_output;
            }

            $sectionTags = array_unique($sectionTags[1]);

            $sectionHtmlCodes = [];

            foreach ($sectionTags as $sectionId) {
                $section = SectionQuery::create()->filterByVisible(1)->findOneById($sectionId);

                if ($section) {
                    $section->setLocale($this->request->getSession()->getLang()->getLocale());

                    $sectionHtmlCodes[Section::makeSectionTag($sectionId)] = $section->getDescription();
                } else {
                    $sectionHtmlCodes[Section::makeSectionTag($sectionId)] = '';
                }
            }

            return str_replace(array_keys($sectionHtmlCodes), array_values($sectionHtmlCodes), $tpl_output);
        }

        return $tpl_output;
    }
}