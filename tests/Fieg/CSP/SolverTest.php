<?php
/*
 * @author Jeroen Fiege <jeroen@webcreate.nl>
 * @copyright Webcreate (http://webcreate.nl)
 */

class SolverTest extends \PHPUnit_Framework_TestCase
{
    public function testSolve()
    {
        $domain = new \Fieg\CSP\Domain(array(1,2,3,4));

        $a = new \Fieg\CSP\Feature('A', $domain);
        $b = new \Fieg\CSP\Feature('B', $domain);
        $c = new \Fieg\CSP\Feature('C', $domain);

        $cb = new \Fieg\CSP\ConstraintBuilder();
        $constraint = $cb
            ->lt($a, $b)
            ->lt($b, $c)
            ->getExpr()
        ;

        $a->setValue(1);
        $b->setValue(2);
        $c->setValue(3);

        $solver = new \Fieg\CSP\Solver();

        $solver->setConstraint($constraint);

        $solver->addFeature($a);
        $solver->addFeature($b);
        $solver->addFeature($c);

        $result = $solver->solve();

        $solutions = array();

        foreach($result as $path) {
            array_shift($path); // remove the root
            $solutions[] = implode(', ', array_map('strval', $path));
        }

        $this->assertEquals(
            [
                'A,2, B,3, C,4',
                'A,1, B,3, C,4',
                'A,1, B,2, C,4',
                'A,1, B,2, C,3',
            ],
            $solutions
        );
    }
}
