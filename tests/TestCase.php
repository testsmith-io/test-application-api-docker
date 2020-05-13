<?php

use Illuminate\Contracts\Console\Kernel;
use Laravel\Lumen\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{

    use \Laravel\Lumen\Testing\DatabaseMigrations;
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    protected function headers($user = null)
    {
        $headers = ['Accept' => 'application/json'];

        if (!is_null($user)) {
            $token = app('auth')->fromUser($user);
            $headers['authorization'] = 'Bearer '.$token;
            //var_dump($headers);
        }

        return $headers;
    }
}
