<?php

/*
 * @author Jeroen Fiege <jeroen@webcreate.nl>
 * @copyright Webcreate (http://webcreate.nl)
 */

use Fieg\CSP\Constraint;
use Fieg\CSP\Feature;

class ConstraintTest extends \PHPUnit_Framework_TestCase
{
    /** @var Feature[] */
    protected $features;

    public function setUp()
    {
        $domain = new \Fieg\CSP\Domain(array(1,2,3,4));

        $a = new \Fieg\CSP\Feature('A', $domain);
        $b = new \Fieg\CSP\Feature('B', $domain);
        $c = new \Fieg\CSP\Feature('C', $domain);

        $this->features = array(
            'a' => $a,
            'b' => $b,
            'c' => $c
        );
    }

    /**
     * @dataProvider evaluateProvider
     */
    public function testEvaluate(Constraint $constraint, $features, $values, $expected)
    {
        foreach($features as $i => $f) {
            $f->setValue($values[$i]);
        }

        $result = $constraint->evaluate();

        $this->assertEquals($expected, $result);
    }

    public function evaluateProvider()
    {
        $cb = new \Fieg\CSP\ConstraintBuilder();

        $domain = new \Fieg\CSP\Domain(array(1,2,3,4));

        $a = new \Fieg\CSP\Feature('A', $domain);
        $b = new \Fieg\CSP\Feature('B', $domain);
        $c = new \Fieg\CSP\Feature('C', $domain);

        return array(
            array(
                $cb->lt($a, $b)->lt($b, $c)->getExpr(),
                array($a, $b, $c),
                array(1, 2, 3),
                true
            ),
            array(
                $cb->lt($a, $b)->lt($b, $c)->getExpr(),
                array($a, $b, $c),
                array(1, 1, 1),
                false
            ),
        );
    }
}
