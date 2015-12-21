<?php
/*************************************************************************************/
/*      Module Sections pour Thelia                                                  */
/*                                                                                   */
/*      Copyright (©) Informatique Prog                                              */
/*      email : contact@informatiqueprog.net                                         */
/*                                                                                   */
/*                                                         test utf-8 ä,ü,ö,ç,é,â,µ  */
/*************************************************************************************/

namespace Section\Loop;

use Section\Model\SectionQuery;

use Propel\Runtime\ActiveQuery\Criteria;
use Thelia\Core\Template\Element\BaseI18nLoop;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Element\PropelSearchLoopInterface;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;
use Thelia\Type;

class SectionLoop extends BaseI18nLoop implements PropelSearchLoopInterface
{

    protected $timestampable = true;

    /**
     * @return \Thelia\Core\Template\Loop\Argument\ArgumentCollection
     */
    protected function getArgDefinitions()
    {
        return new ArgumentCollection(
            Argument::createIntListTypeArgument('id'),
            Argument::createBooleanOrBothTypeArgument('visible', 1)
        );
    }

     /**
     * this method returns a Propel ModelCriteria
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function buildModelCriteria()
    {
        $search = SectionQuery::create();

        $this->configureI18nProcessing($search, array('TITLE', 'DESCRIPTION'));

        $id = $this->getId();

        if (!is_null($id)) {
            $search->filterById($id, Criteria::IN);
        }

        $visible = $this->getVisible();

        if ($visible !== Type\BooleanOrBothType::ANY) {
            $search->filterByVisible($visible? 1:0);
        }

        return $search;
    }

    /**
     * @param LoopResult $loopResult
     *
     * @return LoopResult
     */
    public function parseResults(LoopResult $loopResult)
    {
        foreach ($loopResult->getResultDataCollection() as $section) {
            $loopResultRow = new LoopResultRow($section);

            $loopResultRow
                ->set('ID', $section->getId())
                ->set('IS_TRANSLATED', $section->getVirtualColumn('IS_TRANSLATED'))
                ->set('LOCALE', $this->locale)
                ->set('TITLE', $section->getVirtualColumn('i18n_TITLE'))
                ->set('DESCRIPTION', $section->getVirtualColumn('i18n_DESCRIPTION'))
                ->set('VISIBLE', $section->getVisible())
            ;

            $loopResult->addRow($loopResultRow);
        }

        return $loopResult;
    }

}
