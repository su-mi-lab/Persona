<?php

use Persona\Persona;

class PersonaContainerTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        parent::setUp();
        Persona::bind(ServiceInterface::class, Service::class);
        Persona::bind(RepositoryInterface::class, Repository::class);
        Persona::singleton(Singleton::class, Singleton::class);
    }

    public function testGetBindList()
    {
        $list = Persona::getBindList();
        $this->assertEquals(count($list), 2);
    }

    public function testGetSingletonList()
    {
        $list = Persona::getSingletonList();
        $this->assertEquals(count($list), 1);
    }

    public function testMake()
    {
        /** @var Service $serviceInterface */
        $serviceInterface = Persona::make(ServiceInterface::class);
        $this->assertEquals($serviceInterface instanceof Service, true);
        $this->assertEquals($serviceInterface->get(1) instanceof Order, true);
    }

    public function testCall()
    {
        /** @var Order $order */
        $order = Persona::call('indexAction', Controller::class, [
            'order_id' => 1
        ]);

        $item = $order->getItem();
        $user = $order->getUser();
        $job = $user->getJob();

        $this->assertEquals($order->order_id, 1);
        $this->assertEquals($item->name, 'Item Name');
        $this->assertEquals($user->name, 'User Name');
        $this->assertEquals($job->job, 'PG');
    }

    public function testSingleton()
    {
        /** @var Singleton $singleton */
        $singleton = Persona::make(Singleton::class);

        $this->assertEquals($singleton->getCount(), 0);

        $singleton->countUp();

        /** @var Singleton $singleton */
        $singleton2 = Persona::make(Singleton::class);

        $this->assertEquals($singleton2->getCount(), 1);
    }
}