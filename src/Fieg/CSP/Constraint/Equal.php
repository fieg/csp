<?php

/*
 * @author Jeroen Fiege <jeroen@webcreate.nl>
 * @copyright Webcreate (http://webcreate.nl)
 */

namespace Fieg\CSP\Constraint;

use Fieg\CSP\Constraint;

class Equal extends Constraint
{
    /**
     * @var mixed
     */
    protected $x;

    /**
     * @var mixed
     */
    protected $y;

    /**
     * Constructor.
     *
     * @param mixed $x
     * @param mixed $y
     */
    public function __construct($x, $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    /**
     * @inheritdoc
     */
    public function evaluate()
    {
        return ($this->resolve($this->x) === $this->resolve($this->y));
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('%s = %s', (string) $this->x, (string) $this->y);
    }
}
