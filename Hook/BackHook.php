<?php
/*************************************************************************************/
/*      Module Sections pour Thelia                                                  */
/*                                                                                   */
/*      Copyright (©) Informatique Prog                                              */
/*      email : contact@informatiqueprog.net                                         */
/*                                                                                   */
/*                                                         test utf-8 ä,ü,ö,ç,é,â,µ  */
/*************************************************************************************/

namespace Section\Hook;

use Thelia\Core\Event\Hook\HookRenderBlockEvent;
use Thelia\Core\Hook\BaseHook;
use Thelia\Tools\URL;

class BackHook extends BaseHook
{

    /**
     * @param HookRenderBlockEvent $event
     */
    public function onTopMenuTools(HookRenderBlockEvent $event)
    {
        $event->add([
            'id'    => 'menu-tools-resa',
            'class' => '',
            'url'   => URL::getInstance()->absoluteUrl('/admin/module/Section'),
            'title' => 'Section',
        ]);
    }

}