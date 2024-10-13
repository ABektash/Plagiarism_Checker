<?php 



class HomeController extends Controller
{
    public function index()
    {
        $data = ["name"=>"Youssef Abdelshahid and Adham"];
        $this->view('home',$data);
    }
}