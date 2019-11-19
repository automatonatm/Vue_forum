<?php

namespace  App\Inspections;

use Exception;

/**
 * Class InvalidKeyWords
 * @package App\Inspections
 */
class InvalidKeyWords {

    /**
     * @var array
     */
    protected  $keywords = [
        'Yahoo Customer Support',
    ];

    /**
     * @param $body
     * @throws Exception
     */
    public function detect($body)
    {



        foreach ($this->keywords as $keyword) {
            if(stripos($body, $keyword) !== false) {
                throw new Exception('Your content a contains spam');
            }
        }
    }
}