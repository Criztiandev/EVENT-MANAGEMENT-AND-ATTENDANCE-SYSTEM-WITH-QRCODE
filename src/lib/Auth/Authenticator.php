<?php

namespace lib\Auth;

use config\Database;
use lib\Utils\Session;

class Authenticator
{

    public function attempt($email, $password)
    {


    }

    public function createSession($UID, $credentials = [])
    {
        Session::insert('UID', $UID);
        Session::insert('user', $credentials);
        session_regenerate_id(true);
    }

    public function logout()
    {
        Session::flush();
        Session::destroy();
        return true;
    }
}