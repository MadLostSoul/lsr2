<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    session_start();

    $username = $_POST['username'];
    $passwort = $_POST['passwort'];

    $hostname = $_SERVER['HTTP_HOST'];
    $path = dirname($_SERVER['PHP_SELF']);

    if ($path == "\\")
        $path = "/";

    if (isset($_SERVER['HTTPS'])) {
        $protocol = "https://";
    } else {
        $protocol = "http://";
    }

    if (strstr($path, "/auth")) {
        $path = str_ireplace("/auth", "/", $path);
    }

    /* Erweiterung zur Abfrage des Userpasswortes
     * Benutze Server $mysql_host, $mysql_user und $mysql_pass aus vhs.inc
     */

    include "../includes/config.inc.php";

    $link = mysqli_connect($mysql_host, $mysql_user, $mysql_pass, $mysql_db);

/*
 // Ausweichverfahren bei ChromePHP Fehlern...
 print_r("<pre>");
 print_r($link);
 print_r("</pre>");
*/

    if (!$link) {
        die('Verbindung mit Server <span class="mysql_err">' . $mysql_host . '</span> fehlgeschlagen. MySQL-Error: ' . mysqli_connect_error() . "/" . mysqli_connect_errno() . "\n\r<br>");
    }

    // Benutze Datenbank $mysql_db aus vhs.inc
    $db_selected = mysqli_select_db($link, $mysql_db);
    if (!$db_selected) {
        die('Verbindung mit Datenbank <span class="mysql_err">' . $mysql_db . '</span> fehlgeschlagen. MySQL-Error: ' . mysqli_connect_error() . "/" . mysqli_connect_errno() . "\n\r<br>");
    }

    // Benutze Codepage $login_charset aus vhs.inc
    $ch_ch_b = mysqli_set_charset($link, $login_charset);

        if ($GLOBALS["debug"] > 0) {
        echo '<span class="debug">DBG[' . $GLOBALS["debug"] . ']: Codepage: <span class="mysql_ok">' . $login_charset . "</span></span><br>\n\r";
        if (!$ch_ch_b) {
            echo '<span class="debug">DBG[' . $GLOBALS["debug"] . ']: Charset konnte NICHT gesetzt werden. Wert: <span class="mysql_err">' . $login_charset . "</span> fehlgeschlagen.\n\r<br>";
        } else {
            echo '<span class="debug">DBG[' . $GLOBALS["debug"] . ']: Charset erfolgreich gesetzt auf <span class="mysql_ok">' . $login_charset . "</span></span><br>\n\r";
        }
    }

    if ($GLOBALS["debug"])
        echo '<span class="debug">DBG[' . $GLOBALS["debug"] . ']: Cookies: ';
    if ($_COOKIE == TRUE) {
        if ($GLOBALS["debug"])
            echo "Es gibt <span class='mysql_ok'>Cookies</span> für die Seite.</span><br>\n\r";
        if ($GLOBALS["debug"])
            echo "<pre class='debug'>" . var_export($_COOKIE, TRUE) . "</pre><br>\n\r";
    } else {
        if ($GLOBALS["debug"])
            echo "Es gibt <span class='mysql_err'>KEINE gesetzten Cookies</span> für die Seite.<br>\n\r";
    }

    $result = mysqli_query($link, "SELECT * FROM `" . $tbl_ls_user . "` where login_name = '" . $username . "';");

    $ergebnis_list = mysqli_fetch_array($result, MYSQLI_BOTH);

    if (!$ergebnis_list) {
        $res_username = "Unbekannter Benutzer !";
    } else {
            if ($GLOBALS["debug"] > 0) {
            echo '<span class="debug">DBG[' . $GLOBALS["debug"] . ']: Login_Name = ' . $ergebnis_list['login_name'] . ' / Login_PW = ' . $ergebnis_list['login_pw'] . " / Userlevel= " . $ergebnis_list['level_id'] . "</span><br>\n\r";
        }
        $res_username = "Falsches Passwort oder Unbekannter Benutzer !";

        // Benutzername und Passwort werden überprüft
        if ($username == $ergebnis_list['login_name'] && md5($passwort, FALSE) == $ergebnis_list['login_pw']) {
            $_SESSION['logged_in'] = TRUE;
            $_SESSION['user_id'] = $ergebnis_list['user_id'];
            $_SESSION['user_name'] = $ergebnis_list['user_nick'];
            $_SESSION['login_name'] = $ergebnis_list['login_name'];
            $_SESSION['level_id'] = $ergebnis_list['level_id'];
            $_SESSION['rtb_state'] = $ergebnis_list['rtb_state'];

            $result_loc = mysqli_query($link, "SELECT * FROM `" . $tbl_ls_user_level . "` where level_id = '" . $ergebnis_list['level_id'] . "';");
            $level_list = mysqli_fetch_array($result_loc, MYSQLI_BOTH);
            $_SESSION['level'] = $level_list['level'];

            if ($level_list == NULL && $ergebnis_list['level'] > 0) {
                $_SESSION['user_type_comment'] = "Keinen entsprechenden Userlevel gefunden...";
            } else {
                $_SESSION['user_type_comment'] = $level_list['level_comment'] . " (Level: " . $level_list['level'] . ")";
            }

            // Weiterleitung zur geschützten Startseite
            if ($_SERVER['SERVER_PROTOCOL'] == 'HTTP/1.1') {
                if (php_sapi_name() == 'cgi') {
                    header('Status: 303 See Other');
                } else {
                    header('HTTP/1.1 303 See Other');
                }
            }

            // Version mit Loginfiles im selben Verzeichnis wie die geschützten Dateien
            # header('Location: http://' . $hostname . ($path == '/' ? '' : $path) . 'index.php');

            // Version mit Loginfiles im selben Verzeichnis wie die geschützten Dateien
            header('Location: ' . $protocol . $hostname . ($path == '/' ? '' : $path) . 'index.php');
            exit ;
        }
    }
}

if (isset($res_username) == FALSE) {
    $res_username = "Bitte geben Sie Benutzernamen und Kennwort ein";

    if (isset($GLOBALS["debug"]) && $GLOBALS["debug"]) {
        echo '<span class="debug">DBG[' . $GLOBALS["debug"] . ']: Login wird initialisiert. <span class="mysql_ok">' . "FIRST SHOOT!</span></span><br>\n\r";
    }
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
        include "../includes/config.inc.php";
        if ($GLOBALS["debug"])
            echo '<span class="debug">DBG[' . $GLOBALS["debug"] . ']: <span class="mysql_ok">$_SESSION</span> wird initialisiert. Liste der <span class="mysql_ok">$_SESSION</span> Variable:<br><pre class="debug">' . var_export($_SESSION, TRUE) . "</pre></span><br>\n\r";
    }
}
?>
<HTML lang="de">
<head>
<title><?php echo $ls_script_info['ls_script_title']; ?>
:: Identifikation</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<link rel="shortcut icon" href="../img/favicon.ico">
<link rel="stylesheet" type="text/css" href="../css/liquidsoap.css">
<!--[if lt IE 9]>
<link rel="stylesheet" type="text/css" href="../css/iefonts_index.css"/>
<![endif]-->
</head>
<body>
<?php
echo $GLOBALS['control_button_style'] . "<a style=\" color: #FF0000;\" href=\"../auth/logout.php\"> NOT LOGGED IN</a> " . $GLOBALS['control_button_style_add'] . "(" . $_SERVER['REMOTE_ADDR'] . ")</span></div>";
?>
<h1>
<span id="request_link" lang="en"><?php echo $ls_script_info['ls_script_name'] . " " . $ls_script_info['ls_script_version']; ?>
</span>
<hr style="height: 2.5px; visibility: hidden; margin: 0px 0px 0px 0px;">
<span class="playlist" id="playlist_display">(<?php echo $ls_script_info['ls_script_name'] . " - Login"; ?>)
</span>
</h1>
<h2 class="output">
<table width="100%" style="background: transparent; border: 0px; margin: 0px; padding: 0px;">
<tr>
<th colspan="2" align="center" nowrap><?php echo $res_username; ?>
<br></th>
</tr>
<form action="login.php" method="post">
<tr>
<td align="right">Benutzername:</td>
<td align="left"><input type="text" name="username" autocomplete="username" /></td>
</tr>
<tr>
<td align="right">Kennwort:</td>
<td align="left"><input type="password" name="passwort" autocomplete="current-password" /></td>
</tr>
<tr>
<td colspan="2" align="center"><input type="submit" value="Anmelden" /></td>
</tr>
</form>
</table>
</h2>
<?php
include '../includes/footer.inc.php';
?>
</body>
</html>
