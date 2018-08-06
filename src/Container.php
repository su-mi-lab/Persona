<?php

namespace Persona;

use Persona\Exception\PersonaException;

class Container
{
    /**
     * @var array
     */
    private static $bindList = [];

    /**
     * @var array
     */
    private static $closureList = [];

    /**
     * @var array
     */
    private static $singletonList = [];

    /**
     * @var array
     */
    private static $singletonObject = [];

    /**
     * @param $instance
     * @param string $method
     * @param array $params
     * @return mixed|null
     * @throws PersonaException
     */
    public function call(string $instance, string $method, array $params = [])
    {
        try {
            $obj = $this->get($instance, $params);
            $persona = new Injection;
            return $persona->invoke($method, $obj, self::getInterfaceList($params));
        } catch (\Exception $e) {
            throw new PersonaException($e);
        }
    }

    /**
     * @param string $instance
     * @param array $params
     * @return mixed
     * @throws PersonaException
     */
    public function get(string $instance, $params = [])
    {
        try {
            if (isset(self::$singletonObject[$instance])) {
                return self::$singletonObject[$instance];
            }

            $persona = new Injection;
            $reflection = $persona->getReflectionClass($instance, self::getInterfaceList());

            $obj = $persona->newInstance($reflection, self::getInterfaceList($params));

            if (isset(self::$singletonList[$instance])) {
                self::$singletonObject[$instance] = $obj;

                return self::$singletonObject[$instance];
            }

            return $obj;
        } catch (\Exception $e) {
            throw new PersonaException($e);
        }
    }

    /**
     * @param string $interface
     * @param $instance
     * @param bool $singleton
     */
    public static function bind(string $interface, $instance, bool $singleton = false): void
    {
        if ($singleton) {
            self::$singletonList[$interface] = $instance;
            return;
        }

        self::$bindList[$interface] = $instance;
    }

    /**
     * @param array $params
     * @return array
     */
    private static function getInterfaceList(array $params = [])
    {
        return array_merge(self::$bindList, self::$singletonList, self::$closureList, $params);
    }
}