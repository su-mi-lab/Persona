<?php

use Persona\Container;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        Container::bind(ServiceInterface::class, Service::class);
        Container::bind(RepositoryInterface::class, Repository::class);
        Container::singleton(Singleton::class, Singleton::class);
    }

    public function testGetBindList()
    {
        $list = Container::getBindList();
        $this->assertEquals(count($list), 2);
    }

    public function testGetSingletonList()
    {
        $list = Container::getSingletonList();
        $this->assertEquals(count($list), 1);
    }

    /**
     * @throws ReflectionException
     */
    public function testMake()
    {
        /** @var Service $serviceInterface */
        $serviceInterface = Container::make(ServiceInterface::class);
        $this->assertEquals($serviceInterface instanceof Service, true);
        $this->assertEquals($serviceInterface->get(1) instanceof Order, true);
    }

    /**
     * @throws ReflectionException
     */
    public function testCall()
    {
        /** @var Order $order */
        $order = Container::call('indexAction', Controller::class, [
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

    /**
     * @throws ReflectionException
     */
    public function testSingleton()
    {
        /** @var Singleton $singleton */
        $singleton = Container::make(Singleton::class);

        $this->assertEquals($singleton->getCount(), 0);

        $singleton->countUp();

        /** @var Singleton $singleton */
        $singleton2 = Container::make(Singleton::class);

        $this->assertEquals($singleton2->getCount(), 1);
    }
}