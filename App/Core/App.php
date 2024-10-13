<?php 



class App 
{
    // controller
    protected $controller = "HomeController";
    // method 
    protected $action = "index";
    // params 
    protected $params=[];

    public function __construct()
    {
        $this->prepareURL($_SERVER['REQUEST_URI']);
        // invoke controller and method
        $this->render();

    }



    /**
     * extract controller and method and all parameters
     * @param string $url -> request from url path 
     * @return 
     */
    private function prepareURL($url)
    {
        $url = $this->removePublicAndBefore($url);
        $url = trim($url,"/");
        if(!empty($url))
        {
            $url = explode('/',$url);
            // define controller 
            $this->controller = isset($url[0]) ? ucwords($url[0])."Controller":"HomeController";
            // define method 
            $this->action = isset($url[1]) ? $url[1]:"index";
            // define parameters 
            unset($url[0],$url[1]);

            $this->params = !empty($url) ? array_values($url) : [];
        }
        
    }




    private function removePublicAndBefore($url) {
        // Find the position of "/public" in the URL
        $publicPos = strpos($url, '/public');
    
        // If "/public" is found, remove everything before and including "/public"
        if ($publicPos !== false) {
            // Extract the part of the URL after "/public"
            return substr($url, $publicPos + strlen('/public'));
        }
    
        // Return the original URL if "/public" is not found
        return $url;
    }



    /**
     * render controller and method and send parameters 
     * @return function 
     */

    private function render()
    {
        
        // chaeck if controller is exist
        if(class_exists($this->controller))
        {
            $controller = new $this->controller;
            // check if methos is exist
            if(method_exists($controller,$this->action))
            {
                call_user_func_array([$controller,$this->action],$this->params);
            }
            else 
            {
                // echo "Method : " . $this->action ." does not Exist";
                new View('error');
            }
        }
        
        else 
        {
            // echo "Class : ".$this->controller." Not Found";
            new View('error');
        }  
        
    }
}