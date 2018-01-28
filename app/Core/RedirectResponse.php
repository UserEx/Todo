<?php
namespace UserEx\Todo\Core;

/**
 * @author ildar
 */
class RedirectResponse extends Response
{
    /**
     * @var string
     */
    protected $content = '';
    
    /**
     * @param string $url
     * @param int    $code
     */
    public function __construct(string $url, $code = Response::S302_FOUND)
    {   
        parent::__construct('', $code);
        
        $this->addHeader('Location', $url);        
    }
}