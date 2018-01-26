<?php
namespace UserEx\Todo\Core;

use Nette\Http\Response as BaseResponse;

class Response extends BaseResponse
{
    protected $content = '';
    
    public function __construct(string $content, $code = Response::S200_OK) 
    {
        parent::__construct();
        
        $this->setContent($content);
        $this->setCode($code);
                
    }
    
    public function setContent(string $content)
    {
        $this->content = $content;
    }
    
    public function getContent()
    {
        return $this->content;
    }
        
    public function send() 
    {
        echo $this->content;
    }
}