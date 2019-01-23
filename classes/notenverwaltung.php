<?php
include 'dbc.php';

/**
 * Created by PhpStorm.
 * User: firetailor
 * Date: 12.06.2017
 * Time: 23:38
 */
class notenverwaltung
{
    private $isLoggedInUser;

    public function doLogout()
    {
        if (isset($_COOKIE['username'])) {
            unset($_COOKIE['username']);
            setcookie('username', '', time() - 3600, '/');
        }
        if (isset($_COOKIE['loginToken'])) {
            $dbc = dbc::getObj();
            $dbc->deleteToken($_COOKIE['loginToken']);
            unset($_COOKIE['loginToken']);
            setcookie('loginToken', '', time() - 3600, '/');
        }
        if (isset($_COOKIE['isLoggedIn'])) {
            unset($_COOKIE['isLoggedIn']);
            setcookie('isLoggedIn', '', time() - 3600, '/');
        }
        header("Location: https://demo-nv.r3ne.de");
        exit;
    }

    public function __construct()
    {

        if (isset($_GET['logout'])) {
            $this->doLogout();
        }
        if (isset($_POST['setMaintenance'])) {
            $dbc = dbc::getObj();
            if ($dbc->getAttribute('maintenanceMode') == true) {
                $dbc->setAttribute('maintenanceMode', 0);
            } else {
                $dbc->setAttribute('maintenanceMode', 1);
            }

        }
        if (isset($_POST['setArchive'])) {

            $dbc = dbc::getObj();
            $dbc->switchUserArchiveMode($_COOKIE['username']);
        }
        if (isset($_GET['magicToken']) & !isset($_COOKIE['isLoggedIn'])) {
            $dbc = dbc::getObj();
            $res = $dbc->getResultFromQuery('SELECT token FROM login_token WHERE isPermanent IS TRUE');
            if ($res->num_rows > 0) {
                foreach ($res as $re) {
                    if ($re['token'] == $_GET['magicToken']) {
                        $token = $this->initToken('mtoken');
                        setcookie('username', 'mtoken', time() + (86400 / 24), "/");
                        setcookie('loginToken', $token, time() + (86400 / 24), "/");
                        setcookie('isLoggedIn', true, time() + (86400 / 24), "/");
                        header("Refresh:0");
                    }
                }
            }
        } else
            if (isset($_COOKIE['isLoggedIn'])) {
                if (isset($_COOKIE['loginToken'])) {
                    $dbc = dbc::getObj();
                    $res = $dbc->getResultFromQuery('SELECT token FROM login_token');
                    $this->isLoggedInUser = false;
                    if ($res->num_rows > 0) {
                        foreach ($res as $re) {
                            if ($re['token'] == $_COOKIE['loginToken']) {
                                $this->isLoggedInUser = true;

                            }
                        }
                    }
                    if (!$this->isLoggedInUser)
                        $this->doLogout();
                } else {
                    $this->doLogout();
                }
            }


        if (isset($_POST['username']) & isset($_POST['password'])) {
            $dbc = dbc::getObj();

            if ($dbc->checkUsername($_POST['username']) == true) {
                if ($dbc->checkPassword($_POST['username'], $_POST['password']) == true) {
                    $token = $this->initToken($_POST['username']);
                    setcookie('username', $_POST['username'], time() + (86400 * 30), "/");
                    setcookie('loginToken', $token, time() + (86400 * 30), "/");
                    setcookie('isLoggedIn', true, time() + (86400 * 30), "/");
                    header("Refresh:0");
                }
            }
        }

        if (isset($_POST['editDone'])) {
            $dbc->editEntry($_POST['editDone'], $_POST['komponist'], $_POST['werk'], $_POST['kategorie']);
            header("Refresh:0");
        } else {
            if (isset($_POST['edit'])) {
                $this->getEdit($_POST['edit']);
            } else {
                if (isset($_POST['delete'])) {

                    $dbc = dbc::getObj();
                    $dbc->deleteEntry($_POST['delete']);
                    header("Refresh:0");
                } else {
                    if (isset($_POST['create'])) {

                        $dbc->createEntry($_POST['komponist'], $_POST['werk'], $_POST['kategorie']);
                        header("Refresh:0");

                    } else {
                        if (isset($_POST['new'])) {
                            $this->getNew();
                        } else {
                            if ($this->isLoggedInUser) {
                                if ($dbc->getAttribute('maintenanceMode') != 1) {
                                    $this->getVerwaltung();
                                } else {
                                    if ($dbc->isUserDev($_COOKIE['username']))
                                        $this->getVerwaltung();
                                    else
                                        readfile("wartung.html");
                                }
                            } else {
                                $this->getLogin();
                            }
                        }
                    }
                }
            }
        }
    }

    private function initToken($username)
    {
        $dbc = dbc::getObj();
        $token = rand(1000000000, 9999999999);
        $query = 'INSERT INTO login_token (userID, token, date) VALUES (' . $dbc->getUserIDForName($username) . ', ' . $token . ', NOW())';

        $dbc->execQuery($query);

        return $token;
    }

    private
    function getEdit($ID)
    {
        $dbc = dbc::getObj();

        echo '<div class="row page-login">
    <div class="col s12">
        <div id="login" class="card-login">
            <div id="login-content" class="row">
            <h5>Note Nr. ' . $ID . '</h5>
                <br>
                <form class="login-form" method="post" action="index.php">
                    <div class="row margin">
                        <div class="input-field col s12">
                            <input id="editDone" name="editDone" value="' . $ID . '" type="hidden">
                            <input id="komponist" name="komponist" type="text" required value="' . $dbc->getKomponist($ID) . '">
                            <label for="komponist" class="center-align active" value="" >Komponist:</label>
                        </div>
                    </div>
                    <div class="row margin">
                        <div class="input-field col s12">
                            <input id="werk" name="werk" type="text" required value="' . $dbc->getWerk($ID) . '">
                            <label for="werk" class="center-align active" value="" >Werk:</label>
                        </div>
                    </div>
                        <br>
                    <div class="row margin">
                        <div class="input-field col s12">
                            <input  id="1" name="kategorie" type="radio" value="1" ';
        if ($dbc->isMotette($ID)) {
            echo 'checked';
        }
        echo '>
                            <label for="1" class="center-align active"  >Motette</label>
                            &nbsp;
                            <input  id="2" name="kategorie" type="radio" value="2" ';
        if (!$dbc->isMotette($ID)) {
            echo 'checked';
        }
        echo '>
                            <label for="2" class="center-align active"  >Messe</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12 login1">
                            <button class="waves-effect waves-light btn col s12" id="btn-login" type="submit" onclick="return confirm(\'Änderungen übernehmen?\')">Ändern</button>
                        </div>
                    </div>
                </form>
                <form class="login-form" action="index.php" method="post">
                    <div class="row">
                        <div class="input-field col s12 login1">
                            <button class="waves-effect waves-light btn col s12" id="btn-login" type="submit">Abbrechen</button>
                        </div>
                </form>
            </div>
        </div>
    </div>
</div>';
    }

    private
    function getNew()
    {
        echo '<div class="row page-login">
    <div class="col s12">
        <div id="login" class="card-login">
            <div id="login-content" class="row">
            <h5>Notenerfassung</h5>
                <br>
                <form class="login-form" method="post" action="index.php">
                    <div class="row margin">
                        <div class="input-field col s12">
                            <input id="create" name="create" type="hidden">
                            <input id="komponist" name="komponist" type="text" required>
                            <label for="komponist" class="center-align active" value="" >Komponist:</label>
                        </div>
                    </div>
                    <div class="row margin">
                        <div class="input-field col s12">
                            <input id="werk" name="werk" type="text" required>
                            <label for="werk" class="center-align active" value="" >Werk:</label>
                        </div>
                    </div>
                        <br>
                    <div class="row margin">
                        <div class="input-field col s12">
                            <input  id="1" name="kategorie" type="radio" value="1">
                            <label for="1" class="center-align active"  >Motette</label>
                            &nbsp;
                            <input  id="2" name="kategorie" type="radio" value="2">
                            <label for="2" class="center-align active"  >Messe</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12 login1">
                            <button class="waves-effect waves-light btn col s12" id="btn-login" type="submit">Anlegen</button>
                        </div>
                        <div class="input-field col s12 login1">
                            <button class="waves-effect waves-light btn col s12" id="btn-login" type="reset">Reset</button>
                        </div>
                    </div>
                </form>
                <form class="login-form" action="index.php" method="post">
                    <div class="row">
                        <div class="input-field col s12 login1">
                            <button class="waves-effect waves-light btn col s12" id="btn-login" type="submit">Abbrechen</button>
                        </div>
                </form>
            </div>
        </div>
    </div>
</div>';
    }

    private
    function getVerwaltung()
    {
        $dbc = dbc::getObj();

        if (!$dbc->isUserReadOnly($_COOKIE['username'])) {
            readfile('js/tableEditMode.js');
        } else {
            readfile('js/tableReadOnly.js');
        }
        $this->getTableHeader();


        $query = 'SELECT * FROM ' . $dbc->table;

        if (isset($_GET['search'])) {
            if ($_GET['search'] != "") {
                $query .= " WHERE id LIKE '%" . $_GET['search'] . "%' OR ";
                $query .= "komponist LIKE '%" . $_GET['search'] . "%' OR ";
                $query .= "werk LIKE '%" . $_GET['search'] . "%' OR ";
                $query .= "kategorie LIKE '%" . $_GET['search'] . "%'";
            }

        }

        $query .= ' ORDER BY ID';

        $res = $dbc->getResultFromQuery($query);
        if ($res->num_rows != 0) {
            foreach ($res as $re) {
                $Kategorie = "";
                switch ($re['Kategorie']) {
                    case 1:
                        $Kategorie = "Motette";
                        break;
                    case 2:
                        $Kategorie = "Messe";
                        break;
                }

                echo ' <tr>
                    <td> ' . $re['ID'] . '</td>
                    <td> ' . $re['Komponist'] . '</td>
                    <td> ' . $re['Werk'] . '</td>
                    <td> ' . $Kategorie . '</td>';
                $dbc = dbc::getObj();
                if (!$dbc->isUserReadOnly($_COOKIE['username'])) {
                    echo '<td><form action="index.php" method="post"><button class="btn-table " type="submit"><input type="hidden" name="edit" value="' . $re['ID'] . '"> <i class="material-icons">mode_edit</i></button></form></td>';
                    echo '<td><form action="index.php" method="post"><button class="btn-table" type="submit" onclick="return confirm(\'Wirklich löschen?\')"> <input type="hidden" name="delete" value="' . $re['ID'] . '"><i class="material-icons">delete</i></button></form></td>';
                }
                echo '</tr>';
            }
        } else {

        }

        $this->getTableFooter();
    }

    private function getTableHeader()
    {
        $dbc = dbc::getObj();

        echo '<div class="row">
    <div id="admin" class="col s12">
        <div class="card material-table">
            <div class="table-header">
                <form class="input-field col s12" action="../index.php" method="post">
                      <button type="submit" class="btn-flat"><a href="index.php"><span class="table-title"><b>r3ne.de</b> media <b>No</b>ten<b>ve</b>rwaltung';
        if ($dbc->isUserInArchiveMode($_COOKIE['username']) === true)
            echo ' <b>A</b>rchiv';

        echo '</span> </a> </button><span>';

        echo '&nbsp; | &nbsp; Hallo ' . $dbc->getPrename($_COOKIE['username']) . '! </span>
                    </form>
                    
                <div class="actions">
                    <form class="input-field col s12" action="../index.php" method="get">
                          <input autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" id="search" name="search" type="text"> 
                          <label id="labSearch" for="search" class="center-align active" >Suche<br></label>
                    </form>
                   ';


        echo '&nbsp; <form class="input-field"  action="../index.php" method="post"><input type="hidden" name="setArchive">';
        if ($dbc->isUserInArchiveMode($_COOKIE['username']) == 1) {
            echo '<button type="submit" class="modal-trigger waves-effect btn-flat"><i class="material-icons">folder_open</i></button></form>';
        } else {
            echo '<button type="submit" class="modal-trigger waves-effect btn-flat"><i class="material-icons">folder</i></button></form>';
        }

        if ($dbc->isUserDev($_COOKIE['username'])) {
            echo '&nbsp; <form class="input-field"  action="../index.php" method="post"><input type="hidden" name="setMaintenance">';
            if ($dbc->getAttribute('maintenanceMode') == 1) {
                echo '<button type="submit" class="modal-trigger waves-effect btn-flat"><i class="material-icons">visibility_off</i></button></form>';
            } else {
                echo '<button type="submit" class="modal-trigger waves-effect btn-flat"><i class="material-icons">visibility</i></button></form>';
            }
        }

        if (!$dbc->isUserReadOnly($_COOKIE['username']))
            echo '&nbsp; <form class="input-field"  action="../index.php" method="post">
                         <input type="hidden" name="new">
                         <button type="submit" class="modal-trigger waves-effect btn-flat"><i class="material-icons">note_add</i></button>
                    </form>';
        echo '&nbsp;
                    <form class="input-field"  action="../index.php" method="get">
                         <input type="hidden" name="logout">
                         <button type="submit" class="modal-trigger waves-effect btn-flat "><i class="material-icons">lock_outline</i></button>
                    </form>
                </div>
            </div>
            <table id="datatable">';
    }

    private function getTableFooter()
    {

        echo '<thead>
                <tr>
                    <th> ID</th>
                    <th> Komponist</th>
                    <th> Werk</th>
                    <th> Kategorie</th> ';
        $dbc = dbc::getObj();
        if (!$dbc->isUserReadOnly($_COOKIE['username'])) {
            echo '<th> </th>';
            echo '<th> </th>';
        }
        echo '</tr>
                </thead>
            </table>
        </div>
    </div>
</div>';
    }

    private
    function getLogin()
    {
        echo '<div class="row page-login">
    <div class="col s12">
        <div id="login" class="card-login">
            <div id="login-content" class="row">
                <img src="img/logo/logoLogin.png" id="loginImage">
                <br>
                <form class="login-form" method="post" action="index.php">
                    <div class="row margin">
                        <div class="input-field col s12">
                            <input id="username" name="username" type="text" required>
                            <label for="username" class="center-align active" value="" >Username:</label>
                        </div>
                    </div>
                    <div class="row margin">
                        <div class="person input-field col s12">
                            <input id="password" name="password" type="password" required>
                            <label for="password" class="">Password:</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12 login1">
                            <button class="waves-effect waves-light btn col s12" id="btn-login" type="submit">Login</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>';
    }
}

function debug($data)
{
    $dbc = dbc::getObj();
    if (!$dbc->getAttribute['maintenanceMode'] === true) {
        $output = $data;
        if (is_array($output))
            $output = implode(',', $output);

        echo "<script>console.log( 'Debug Objects: " . $output . "' );</script>";
    }
}
