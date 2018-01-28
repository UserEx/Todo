<?php
namespace UserEx\Todo\Core;

use Nette\Http\Response as BaseResponse;

/**
 * @author ildar
 */
class Response extends BaseResponse
{
    protected $content = '';
    
    /**
     * @param string $content
     * @param int    $code
     */
    public function __construct(string $content, $code = Response::S200_OK) 
    {
        parent::__construct();
        
        $this->setContent($content);
        $this->setCode($code);
                
    }
    
    /**
     * @param string $content
     */
    public function setContent(string $content)
    {
        $this->content = $content;
    }
    
    /**
     * @return string|\UserEx\Todo\Core\string
     */
    public function getContent()
    {
        return $this->content;
    }
       
    /**
     */
    public function send() 
    {
        echo $this->content;
    }
}