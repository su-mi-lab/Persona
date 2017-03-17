<?php

namespace Persona;

class Persona
{
    /**
     * @var array
     */
    private static $bindList = [];

    /**
     * @var array
     */
    private static $singletonList = [];

    /**
     * @var array
     */
    private static $singletonObject = [];

    /**
     * @param $interface
     * @param $instance
     */
    public static function bind($interface, $instance)
    {
        self::$bindList[$interface] = $instance;
    }

    /**
     * @param $interface
     * @param $instance
     */
    public static function singleton($interface, $instance)
    {
        self::$singletonList[$interface] = $instance;
    }

    /**
     * @return array
     */
    public static function getBindList()
    {
        return self::$bindList;
    }

    /**
     * @return array
     */
    public static function getSingletonList()
    {
        return self::$singletonList;
    }

    /**
     * @param $instance
     * @return object
     */
    public static function make($instance)
    {
        if (isset(self::$singletonObject[$instance])) {
            return self::$singletonObject[$instance];
        }

        $persona = new PersonaReflection;
        $reflection = $persona->getReflectionClass($instance, self::getInterfaceList());

        $obj = $persona->newInstance($reflection, self::getInterfaceList());

        if (isset(self::$singletonList[$instance])) {
            self::$singletonObject[$instance] = $obj;

            return self::$singletonObject[$instance];
        }

        return $obj;
    }

    /**
     * @param $method
     * @param $instance
     * @param $option
     * @return mixed
     */
    public static function call($method, $instance, $option)
    {
        $obj = self::make($instance);
        $persona = new PersonaReflection;
        return $persona->invoke($method, $obj, self::getInterfaceList($option));
    }

    /**
     * @param array $option
     * @return array
     */
    private static function getInterfaceList(array $option = [])
    {
        return array_merge(self::$bindList, self::$singletonList, $option);
    }
}