<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class ErrorPageController extends Controller
{
    public function index()
    {
        $this->view('ErrorPage');
    }
}
