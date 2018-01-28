<?php
namespace UserEx\Todo\Core;

/**
 * @author ildar
 */
class JsonResponse extends Response
{
    /**
     * @var string
     */
    protected $content = '';
    
    /**
     * @param mixed $json
     * @param int   $code
     */
    public function __construct($json, $code = Response::S200_OK)
    {
        if (is_array($json)) {
            $json = json_encode($json);
        }
        
        parent::__construct($json, $code);
        
        $this->setHeader('Content-Type', 'application/json');
    }
}