<?php

use \Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\CreatesApplication;
use Tests\TestsHelper;

class FeatureTestCase extends \Laravel\BrowserKitTesting\TestCase
{

    use CreatesApplication, TestsHelper, DatabaseTransactions;

    public function seeErrors(array $fields)
    {
        foreach ($fields as $name => $errors){
            foreach ((array) $errors as $message){
                $this->seeInElement(
                    "#field_{$name} .help-block",$message
                );
            }
        }
    }

}