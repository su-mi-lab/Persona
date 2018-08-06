<?php

use Persona\Container;
use Persona\Exception\PersonaException;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        Container::bind(ServiceInterface::class, Service::class);
        Container::bind(RepositoryInterface::class, Repository::class);
        Container::bind(Singleton::class, Singleton::class, true);
    }

    /**
     * @throws PersonaException
     */
    public function testGet()
    {
        $container = new Container();

        /** @var Service $serviceInterface */
        $serviceInterface = $container->get(ServiceInterface::class);
        $this->assertEquals($serviceInterface instanceof Service, true);
        $this->assertEquals($serviceInterface->get(1) instanceof Order, true);
    }

    /**
     * @throws PersonaException
     */
    public function testCall()
    {
        $container = new Container();

        /** @var Order $order */
        $order = $container->call(Controller::class, 'indexAction', [
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
     * @throws PersonaException
     */
    public function testSingleton()
    {
        $container = new Container();

        /** @var Singleton $singleton */
        $singleton = $container->get(Singleton::class);

        $this->assertEquals($singleton->getCount(), 0);

        $singleton->countUp();

        /** @var Singleton $singleton */
        $singleton2 = $container->get(Singleton::class);

        $this->assertEquals($singleton2->getCount(), 1);
    }
}