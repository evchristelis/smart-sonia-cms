<?php

namespace TotalPollVendors\League\Container\Argument;
! defined( 'ABSPATH' ) && exit();


class RawArgument implements RawArgumentInterface
{
    /**
     * @var mixed
     */
    protected $value;

    /**
     * {@inheritdoc}
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getValue()
    {
        return $this->value;
    }
}
