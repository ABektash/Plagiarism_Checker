<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class HomeController extends Controller
{
    public function index()
    {
        $this->view('home');
    }
}
