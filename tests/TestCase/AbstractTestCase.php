<?php

namespace Elkadrey\Compoships\Tests\TestCase;

use Elkadrey\Compoships\Tests\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

abstract class AbstractTestCase extends \PHPUnit\Framework\TestCase
{
    protected function setupDatabase()
    {
        Model::reguard();
        Carbon::setTestNow('2020-10-29 23:59:59');

        $capsuleManager = new Capsule();
        $capsuleManager->addConnection([
            'driver'    => 'sqlite',
            'database'  => ':memory:',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ]);
        $capsuleManager->setAsGlobal();
        $capsuleManager->bootEloquent();

        (new Migration())->up();
    }
}
