<?php

use Persona\Injection;
use PHPUnit\Framework\TestCase;

class InjectionTest extends TestCase
{
    public function testNewInstance()
    {
        $persona = new Injection;

        $instance = $persona->newInstance(new \ReflectionClass(Service::class), [
            RepositoryInterface::class => Repository::class
        ]);

        $this->assertEquals($instance instanceof Service, true);
    }

    /**
     * @throws ReflectionException
     */
    public function testInvoke()
    {
        $controller = new Controller;

        $persona = new Injection;
        $id = $persona->invoke('test', $controller, [
            'id' => 4
        ]);
        
        $this->assertEquals($id, 4);
    }

    /**
     * @throws ReflectionException
     */
    public function testGetReflectionClass()
    {
        $persona = new Injection;

        $reflection = $persona->getReflectionClass(RepositoryInterface::class, [
            RepositoryInterface::class => Repository::class
        ]);

        $this->assertEquals($reflection->getName(), 'Repository');

        $reflection = $persona->getReflectionClass(RepositoryInterface::class, []);
        $this->assertEquals($reflection->getName(), 'RepositoryInterface');
    }
}