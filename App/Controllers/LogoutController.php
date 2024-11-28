<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class LogoutController extends Controller
{
    public function index()
    {
        session_start();
        session_unset();
        session_destroy();

        $this->view('home');
    }
}
