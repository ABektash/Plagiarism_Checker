<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class ViewEssayController extends Controller
{
    public function index()
    {
        $id = $_SESSION['user']['ID'] ?? null;
        $userType = $_SESSION['user']['UserType_id'] ?? null;

        if (($id !== null) && ($userType == 1 || $userType == 2 || $userType == 3)) {

            $this->view('viewEssay');
        } else {

            $data = [
                "error_code" => 403,
                "error_message" => "We're sorry, You don't have access to this page.",
                "page_To_direct" => "home",
            ];

            $this->view('errorPage', $data);
        }
    }
}
