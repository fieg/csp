<?php

/*
 * @author Jeroen Fiege <jeroen@webcreate.nl>
 * @copyright Webcreate (http://webcreate.nl)
 */

namespace Fieg\CSP;

abstract class CompoundConstraint extends Constraint
{
    /**
     * @var Constraint[]
     */
    protected $constraints = array();

    /**
     * @param Constraint $constraint
     */
    public function addConstraint(Constraint $constraint)
    {
        $this->constraints[] = $constraint;
    }

    /**
     * @return Constraint[]
     */
    public function getConstraints()
    {
        return $this->constraints;
    }

    /**
     * @param Constraint[] $constraints
     */
    public function setConstraints(array $constraints)
    {
        $this->constraints = array();

        foreach($constraints as $constraint) {
            $this->addConstraint($constraint);
        }
    }

    /**
     * @return Constraint
     */
    public function pop()
    {
        return array_pop($this->constraints);
    }
}
