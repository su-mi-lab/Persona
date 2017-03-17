<?php

use Persona\PersonaReflection;

class PersonaReflectionTest extends PHPUnit_Framework_TestCase
{
    public function testNewInstance()
    {
        $persona = new PersonaReflection;

        $instance = $persona->newInstance(new \ReflectionClass(Service::class), [
            RepositoryInterface::class => Repository::class
        ]);

        $this->assertEquals($instance instanceof Service, true);
    }

    public function testInvoke()
    {
        $controller = new Controller;

        $persona = new PersonaReflection;
        $id = $persona->invoke('test', $controller, [
            'id' => 4
        ]);
        
        $this->assertEquals($id, 4);
    }

    public function testGetReflectionClass()
    {
        $persona = new PersonaReflection;

        $reflection = $persona->getReflectionClass(RepositoryInterface::class, [
            RepositoryInterface::class => Repository::class
        ]);

        $this->assertEquals($reflection->getName(), 'Repository');

        $reflection = $persona->getReflectionClass(RepositoryInterface::class, []);
        $this->assertEquals($reflection->getName(), 'RepositoryInterface');
    }
}