<?php

/*
 * @author Jeroen Fiege <jeroen@webcreate.nl>
 * @copyright Webcreate (http://webcreate.nl)
 */

namespace Fieg\CSP;

use Fieg\CSP\Constraint\AndX;
use Fieg\CSP\Constraint\Equal;
use Fieg\CSP\Constraint\LowerThan;
use Fieg\CSP\Constraint\LowerThanEqual;
use Fieg\CSP\Constraint\NotX;
use Fieg\CSP\Constraint\OrX;

class ConstraintBuilder
{
    /**
     * @var Constraint
     */
    protected $constraint;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->constraint = new AndX();
    }

    /**
     * And
     *
     * @param null $x each parameter is a constraint
     * @return $this
     */
    public function andX($x = null)
    {
        $constraint = $this->compound(new AndX(), func_get_args());

        $this->constraint->addConstraint($constraint);

        return $this;
    }

    /**
     * Or
     *
     * @param null $x each parameter is a constraint
     * @return $this
     */
    public function orX($x = null)
    {
        $constraint = $this->compound(new OrX(), func_get_args());

        $this->constraint->addConstraint($constraint);

        return $this;
    }

    /**
     * Not
     *
     * @param null $x each parameter is a constraint
     * @return $this
     */
    public function notX($x = null)
    {
        $constraint = $this->compound(new AndX(), func_get_args());

        $c = new NotX();
        $c->addConstraint($constraint);

        $this->constraint->addConstraint($c);

        return $this;
    }

    /**
     * Less than
     *
     * @param mixed $x
     * @param mixed $y
     * @return $this
     */
    public function lt($x, $y)
    {
        $this->constraint->addConstraint(new LowerThan($x, $y));

        return $this;
    }

    /**
     * Less than or equal
     *
     * @param mixed $x
     * @param mixed $y
     * @return $this
     */
    public function lte($x, $y)
    {
        $this->constraint->addConstraint(new LowerThanEqual($x, $y));

        return $this;
    }

    /**
     * Equal
     *
     * @param mixed $x
     * @param mixed $y
     * @return $this
     */
    public function eq($x, $y)
    {
        $this->constraint->addConstraint(new Equal($x, $y));

        return $this;
    }

    /**
     * Returns the final constraint
     *
     * @return Constraint
     */
    public function getExpr()
    {
        return $this->canonicalize($this->constraint);
    }

    /**
     * Appends constraints to the compound constraint
     *
     * @param  CompoundConstraint        $constraint
     * @param  array                     $arguments
     * @return CompoundConstraint
     * @throws \InvalidArgumentException
     */
    protected function compound(CompoundConstraint $constraint, $arguments)
    {
        $constraints = array();

        foreach ($arguments as $value) {
            if ($this === $value) {
                $constraints[] = $this->constraint->pop();
            } else {
                throw new \InvalidArgumentException('Invalid argument given, should reuse the ConstraintBuilder');
            }
        }

        $constraints = array_reverse($constraints);

        foreach ($constraints as $c) {
            $constraint->addConstraint($c);
        }

        return $constraint;
    }

    /**
     * Simplifies compound constraints. For e.g. when an AndX
     * is inside an other AndX, it gets simplified to a single
     * AndX.
     *
     * @param  Constraint       $constraint
     * @return Constraint|mixed
     */
    protected function canonicalize(Constraint $constraint)
    {
        if ($constraint instanceof CompoundConstraint) {
            $children = $constraint->getConstraints();

            if (1 === count($children) && $constraint instanceof AndX) {
                return $constraint->pop();
            }

            foreach ($children as &$child) {
                $child = $this->canonicalize($child);
            }

            $constraint->setConstraints($children);
        }

        return $constraint;
    }
}
