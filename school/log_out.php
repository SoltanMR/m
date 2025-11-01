<?php
include './session/init.php';

$cookies_to_clear = ['userName', 'password', 'remember_me', 'userNameHonaramoz', 'logedIn'];

foreach ($cookies_to_clear as $cookie) {
    if(isset($_COOKIE[$cookie])) {
        setcookie($cookie, "", time() - 3600, "/", "", true, true);
    }
}

session_unset();
session_destroy();

header("Location: ../");
exit;
?>