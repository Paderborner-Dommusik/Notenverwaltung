<?php
require 'db_data.php';
/**
 * Created by PhpStorm.
 * User: firetailor
 * Date: 13.06.2017
 * Time: 21:01
 */
class dbc
{
    private static $obj;
    public $connection;
    public $table;

    /**
     * dbc constructor.
     */
    private function __construct()
    {
        $this->connection = new mysqli(db_data::$hostname,db_data::$user,db_data::$password,db_data::$db);
        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
        $this->connection->set_charset("utf8");

        $this->updateTable();
    }

    public function updateTable()
    {
        if($this->isUserInArchiveMode($_COOKIE['username']) === true)
        {
            $this->table = 'archiv';
        }
        else
        {
            $this->table = 'note';
        }
    }

    public static function getObj()
    {
        if (dbc::$obj == null) {
            dbc::$obj = new dbc();
        }

        return dbc::$obj;
    }

    public function __destruct()
    {
        $this->connection->Close();
    }

    public function getResultFromQuery($query)
    {
        $result = $this->connection->query($query);
        return $result;
    }

    public function execQuery($query)
    {
        $this->connection->query($query);
    }

    public function deleteToken($token)
    {
        $this->connection->query("DELETE FROM login_token WHERE token LIKE '" . $token . "'");
    }

    public function getUserIDForName($username)
    {

        $res = $this->connection->query("SELECT id FROM users WHERE login LIKE  '" . $username . "'");
        foreach ($res as $re) {

            return $re['id'];
        }
    }

    public function checkUsername($username)
    {
        $res = $this->connection->query('SELECT login FROM users');


        foreach ($res as $re) {
            if ($re['login'] == $username) {
                return true;

            }
        }
        return false;

    }

    public function createEntry($komponist, $werk, $type)
    {
        $query = "INSERT INTO " . $this->table ." (Komponist, Werk, Kategorie, Raum) VALUES ('";
        $query .= $komponist . '\' ,\'' . $werk . '\',' . $type . ',';
        if ($type == 1) {
            $query .= '4';
        } else {
            $query .= '12';
        }

        $query .= ')';
        $this->connection->query($query);




    }

    public function deleteEntry($ID)
    {
        $query = "DELETE FROM ".$this->table ." WHERE ID = " . $ID;
        $this->connection->query($query);
    }

    public function editEntry($ID, $Komponist, $Werk, $Kategorie)
    {
        $query = "UPDATE ".$this->table ." SET Komponist = '" . $Komponist . "', Werk = '" . $Werk . "', Kategorie = " . $Kategorie . " WHERE ID = " . $ID;
        $this->connection->query($query);
    }

    public function isUserReadOnly($username)
    {
        $return = true;
        $res = $this->connection->query('SELECT login FROM users WHERE readOnly is FALSE; ');

        foreach ($res as $re) {
            if ($re['login'] === $username) {
                $return = false;
            }
        }

        return $return;
    }

    public function isUserDev($username)
    {
        $return = false;
        $res = $this->connection->query('SELECT login FROM users WHERE isDev is TRUE; ');

        foreach ($res as $re) {
            if ($re['login'] === $username) {
                $return = true;
            }
        }

        return $return;
    }

    public function isUserInArchiveMode($username)
    {
        $return = false;
        $res = $this->connection->query('SELECT login FROM users WHERE isInArchiveMode is TRUE; ');

        foreach ($res as $re) {
            if ($re['login'] === $username) {
                $return = true;
            }
        }

        return $return;


    }

    public function switchUserArchiveMode($username)
    {
        $query = 'UPDATE users SET isInArchiveMode = ';
        if($this->isUserInArchiveMode($username))
        {
            $query.= 'false';
        }
        else
        {
            $query.= 'true';
        }

        $query.= ' WHERE login LIKE "' . $username .'"';
        $this->connection->query($query);

        $this->updateTable();
    }

    public function checkPassword($username, $password)
    {
        $res = $this->connection->query("SELECT password FROM users WHERE login LIKE  '" . $username . "'");
        foreach ($res as $re) {
            if ($re['password'] == $password) {
                return true;
            }

        }
        return false;
    }

    public function getKomponist($ID)
    {
        $res = $this->connection->query("SELECT Komponist FROM ".$this->table ." WHERE ID =  " . $ID);
        foreach ($res as $re) {

            return $re['Komponist'];
        }
    }

    public function getWerk($ID)
    {
        $res = $this->connection->query("SELECT Werk FROM ".$this->table ." WHERE ID =  " . $ID);
        foreach ($res as $re) {

            return $re['Werk'];
        }
    }

    public function isMotette($ID)
    {
        $res = $this->connection->query("SELECT Kategorie FROM ".$this->table ." WHERE ID =  " . $ID);
        foreach ($res as $re) {

            if ($re['Kategorie'] == 1) return true;
            else return false;
        }
    }

    public function getPrename($Username)
    {
        $res = $this->connection->query("SELECT prename FROM users WHERE login Like '" . $Username . "'");
        foreach ($res as $re) {
            return ucfirst($re['prename']);
        }
    }

    public function getAttribute($attr)
    {
        $res = $this->connection->query("SELECT value FROM globalSettings WHERE globalSettings.attribute Like '" . $attr . "'");
        foreach ($res as $re) {
            return $re['value'];
        }
    }

    public function setAttribute($attr, $val){
        $this->connection->query("UPDATE globalSettings SET value = " . $val . " WHERE attribute LIKE '" . $attr . "'");
    }

}