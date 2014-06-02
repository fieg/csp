<?php

/*
 * @author Jeroen Fiege <jeroen@webcreate.nl>
 * @copyright Webcreate (http://webcreate.nl)
 */

namespace Fieg\CSP;

class Feature
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var Domain
     */
    protected $domain;

    /**
     * @var mixed
     */
    protected $value = null;

    /**
     * @param string $name
     * @param Domain $domain
     */
    public function __construct($name, Domain $domain)
    {
        $this->name = $name;
        $this->domain = $domain;
    }

    /**
     * @param mixed $value
     * @return Feature
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @throws \LogicException
     * @return mixed
     */
    public function getValue()
    {
        if (null === $this->value) {
            throw new \LogicException(sprintf('Value not set for feature with name "%s"', (string) $this));
        }

        return $this->value;
    }

    /**
     * @return Domain
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }
}
