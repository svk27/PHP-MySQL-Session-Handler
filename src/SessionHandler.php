<?php

namespace Programster\SessionHandler;


class SessionHandler implements \SessionHandlerInterface
{
    protected $dbConnection; # the mysqli connection
    protected $dbTable; # name of the db table to store sessions in
    
    
    /**
     * Create the session handler.
     * @param \mysqli $mysqli - the database connection to store sessions in.
     * @param string $tableName - the table within the database to store session data in.
     */
    public function __construct(\mysqli $mysqli, string $tableName)
    {
        $this->dbConnection = $mysqli;
        $this->dbTable = $tableName;
        
        
        $createSessionsTableQuery = 
            "CREATE TABLE IF NOT EXISTS `" . $this->dbTable . "` (
                `id` varchar(32) NOT NULL,
                `timestamp` int(10) unsigned DEFAULT NULL,
                `data` mediumtext,
                PRIMARY KEY (`id`),
                KEY `timestamp` (`timestamp`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        
        $this->dbConnection->query($createSessionsTableQuery);
    }
    
    
    public function open($savePath, $sessionName)
    {
        $limit = time() - (3600 * 24);
        $sql = sprintf("DELETE FROM %s WHERE timestamp < %s", $this->dbTable, $limit);
        return $this->dbConnection->query($sql);
    }


    public function close()
    {
        return $this->dbConnection->close();
    }


    public function read($id)
    {
        $sql = 
            "SELECT `data`" . 
            " FROM `" . $this->dbTable . "`" . 
            " WHERE `id` = '" . $id . "'";
        
        $result = $this->dbConnection->query($sql);
        return $result;
    }
    
    
    public function write($id, $data)
    {
        $sql = "REPLACE INTO $this->dbTable (id, data, timestamp)" . 
               " VALUES('" . $id . "', '" . $data . "', '" . time() . "')";
        
        return $this->dbConnection->query($sql);
    }
    
    
    public function destroy($id)
    {
        $sql = "DELETE FROM `" . $this->dbTable . " WHERE `id` = '" . $id . "'";
        return $this->dbConnection->query($sql, $params);
    }
    
    
    public function gc($maxlifetime)
    {
        $sql = sprintf(
            "DELETE FROM %s WHERE `timestamp` < '%s'", 
            $this->dbTable, 
            time() - intval($maxlifetime)
        );
        
        return $this->dbConnection->query($sql);
    }
}