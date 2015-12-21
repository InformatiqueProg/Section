<?php
/*************************************************************************************/
/*      Module Sections pour Thelia                                                  */
/*                                                                                   */
/*      Copyright (©) Informatique Prog                                              */
/*      email : contact@informatiqueprog.net                                         */
/*                                                                                   */
/*                                                         test utf-8 ä,ü,ö,ç,é,â,µ  */
/*************************************************************************************/

namespace Section;

use Section\Model\SectionQuery;

use Propel\Runtime\Connection\ConnectionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Thelia\Install\Database;
use Thelia\Module\BaseModule;

class Section extends BaseModule
{
    const SECTIONTAG_REGEX = '/\[moduleSection\_(\d)+\]/';

    /**
     * @param int $sectionId
     * @return string
     */
    public static function makeSectionTag($sectionId)
    {
        return '[moduleSection_' . $sectionId . ']';
    }

    /**
     * This method is called just after the module was successfully activated.
     *
     * @param ConnectionInterface $con
     */
    public function postActivation(ConnectionInterface $con = null)
    {
        try {
            SectionQuery::create()->findOne();
        } catch (\Exception $e) {
            $database = new Database($con);

            $database->insertSql(null, [__DIR__ . DS . 'Config' .DS . 'thelia.sql']);
        }
    }

    /**
     * This method is called just before the deletion of the module, giving the module an opportunity
     * to delete its data.
     *
     * @param ConnectionInterface $con
     * @param bool                $deleteModuleData if true, the module should remove all its data from the system.
     */
    public function destroy(ConnectionInterface $con = null, $deleteModuleData = false)
    {
        // $database = new Database($con);

        // $database->insertSql(null, [__DIR__ . DS . 'Config' . DS . 'destroy.sql']);
    }

    /**
     * @param string $currentVersion
     * @param string $newVersion
     * @param ConnectionInterface $con
     */
    public function update($currentVersion, $newVersion, ConnectionInterface $con = null)
    {
        $fileSystem = new Filesystem();

        $adminIncludesFolders = [
            __DIR__ . DS . 'AdminIncludes',
            __DIR__ . DS . 'I18n' . DS . 'AdminIncludes',
        ];

        foreach ($adminIncludesFolders as $folder) {
            if (file_exists($folder)) {
                $fileSystem->remove($folder);
            }
        }
    }
}
