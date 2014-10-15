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

use Propel\Runtime\Connection\ConnectionInterface;
use Thelia\Install\Database;
use Thelia\Module\BaseModule;

class Section extends BaseModule
{

    /**
     * This method is called just after the module was successfully activated.
     *
     * @param ConnectionInterface $con
     */
    public function postActivation(ConnectionInterface $con = null)
    {
        $query = "SHOW TABLES LIKE 'section'";
        $connection = $con->getWrappedConnection();
        $stmt = $connection->prepare($query);
        if ($stmt === false) {
            throw new \RuntimeException("Failed to prepare statement for $query: " . print_r($connection->errorInfo(), 1));
        }
        $success = $stmt->execute();
        if ($success === false || $stmt->errorCode() != 0) {
            throw new \RuntimeException("Failed to execute SQL '$query', error:".print_r($stmt->errorInfo(), 1));
        }
        if ($stmt->rowCount() == 0) {
            $database = new Database($connection);
            $database->insertSql(null, array(__DIR__ . DS . 'Config' . DS .'thelia.sql'));
        }
    }

}
