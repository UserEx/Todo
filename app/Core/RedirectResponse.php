<?php
namespace UserEx\Todo\Core;


class RedirectResponse extends Response
{
    protected $content = '';
    
    public function __construct(string $url, $code = Response::S302_FOUND)
    {   
        parent::__construct('', $code);
        
        $this->addHeader('Location', $url);        
    }
}