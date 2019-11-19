<?php


namespace App\Inspections;


/**
 * Class Spam
 * @package App
 */
class Spam
{

    protected  $inspections = [
        InvalidKeyWords::class,
        KeyHeldDown::class,

    ];

    /**
     * @param $body
     * @return bool
     * @throws \Exception
     */
    public function detect($body)
    {
        //Detect invalid Key Words

        foreach ($this->inspections as $inspection) {
            app($inspection)->detect($body);

        }

        return false;

    }





}