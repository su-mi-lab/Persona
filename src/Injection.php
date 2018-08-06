<?php

namespace Persona;

class Injection
{
    /**
     * @param \ReflectionClass $reflection
     * @param $bindParams
     * @return mixed
     */
    public function newInstance(\ReflectionClass $reflection, array $bindParams)
    {
        $args = [];
        if ($reflection->hasMethod('__construct')) {
            $parameters = $reflection->getConstructor()->getParameters();
            $args = $this->getArgument($parameters, $bindParams);
        }

        return $reflection->newInstanceArgs($args);
    }

    /**
     * @param $method
     * @param $interface
     * @param array $bindParams
     * @return mixed|null
     * @throws \ReflectionException
     */
    public function invoke($method, $interface, array $bindParams)
    {
        $reflection = new \ReflectionClass(get_class($interface));

        if ($reflection->hasMethod($method)) {
            $parameters = $reflection->getMethod($method)->getParameters();
            $args = $this->getArgument($parameters, $bindParams);

            return $reflection->getMethod($method)->invokeArgs($interface, $args);
        }

        return null;
    }

    /**
     * @param $interface
     * @param array $bindParams
     * @return \ReflectionClass
     * @throws \ReflectionException
     */
    public function getReflectionClass($interface, array $bindParams)
    {
        if (isset($bindParams[$interface])) {
            return new \ReflectionClass($bindParams[$interface]);
        }

        return new \ReflectionClass($interface);
    }

    /**
     * @param \ReflectionParameter[] $parameters
     * @param array $args
     * @return array
     */
    private function getArgument(array $parameters, array $args): array
    {
        return array_reduce($parameters, function ($carry, $parameter) use ($args) {
            /**
             * @var \ReflectionParameter $parameter
             */

            /** @var \ReflectionClass $parameterClass */
            $parameterClass = $parameter->getClass();

            $carry[$parameter->getName()] = ($parameterClass) ? $this->getArgumentObject($parameterClass, $args) : $this->getArgumentValue($parameter, $args);

            return $carry;
        }, []);
    }

    /**
     * @param \ReflectionClass $reflection
     * @param array $args
     * @return mixed
     * @throws \ReflectionException
     */
    private function getArgumentObject(\ReflectionClass $reflection, array $args)
    {
        return $this->newInstance(
            $this->getReflectionClass($reflection->getName(), $args),
            $args
        );
    }

    /**
     * @param \ReflectionParameter $parameter
     * @param array $args
     * @return mixed|null
     */
    private function getArgumentValue(\ReflectionParameter $parameter, array $args)
    {
        $value = null;
        if (isset($args[$parameter->getName()])) {
            $value = $args[$parameter->getName()];
        } else if ($parameter->isDefaultValueAvailable()) {
            $value = $parameter->getDefaultValue();
        }

        return $value;
    }
}