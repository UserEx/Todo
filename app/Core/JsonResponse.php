<?php
namespace UserEx\Todo\Core;


class JsonResponse extends Response
{
    protected $content = '';
    
    public function __construct($json, $code = Response::S200_OK)
    {
        if (is_array($json)) {
            $json = json_encode($json);
        }
        
        parent::__construct($json, $code);
        
        $this->setHeader('Content-Type', 'application/json');
    }
}