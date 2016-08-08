<?php

namespace FR3D\SwaggerAssertionsTest\PhpUnit;

use FR3D\SwaggerAssertions\PhpUnit\RequestQueryConstraint;
use PHPUnit_Framework_ExpectationFailedException as ExpectationFailedException;
use PHPUnit_Framework_TestCase as TestCase;
use PHPUnit_Framework_TestFailure as TestFailure;

/**
 * @covers FR3D\SwaggerAssertions\PhpUnit\RequestQueryConstraint
 */
class RequestQueryConstraintTest extends TestCase
{
    /**
     * @var \PHPUnit_Framework_Constraint
     */
    protected $constraint;

    protected function setUp()
    {
        $schema = '[{"name":"tags","in":"query","description":"tags to filter by","required":false,"type":"array","items":{"type":"string"},"collectionFormat":"csv"},{"name":"limit","in":"query","description":"maximum number of results to return","required":true,"type":"integer","format":"int32"}]';
        $schema = json_decode($schema);

        $this->constraint = new RequestQueryConstraint($schema);
    }

    public function testConstraintDefinition()
    {
        self::assertEquals(1, count($this->constraint));
        self::assertEquals('is a valid request query', $this->constraint->toString());
    }

    public function testValidQuery()
    {
        $parameters = [
            'tags' => ['foo', 'bar'],
            'limit' => 1,
        ];

        self::assertTrue($this->constraint->evaluate($parameters, '', true), $this->constraint->evaluate($parameters));
    }

    public function testInvalidParameterType()
    {
        $parameters = [
            'tags' => ['foo', 1],
            'limit' => 1,
        ];

        self::assertFalse($this->constraint->evaluate($parameters, '', true));

        try {
            $this->constraint->evaluate($parameters);
            self::fail('Expected ExpectationFailedException to be thrown');
        } catch (ExpectationFailedException $e) {
            self::assertEquals(
                <<<EOF
Failed asserting that {"tags":["foo",1],"limit":1} is a valid request query.
[tags[1]] Integer value found, but a string is required

EOF
                ,
                TestFailure::exceptionToString($e)
            );
        }
    }

    public function testMissingParameter()
    {
        $parameters = [
            'tags' => ['foo', 'bar'],
        ];

        self::assertFalse($this->constraint->evaluate($parameters, '', true));

        try {
            $this->constraint->evaluate($parameters);
            self::fail('Expected ExpectationFailedException to be thrown');
        } catch (ExpectationFailedException $e) {
            self::assertEquals(
                <<<EOF
Failed asserting that {"tags":["foo","bar"]} is a valid request query.
[limit] The property limit is required

EOF
                ,
                TestFailure::exceptionToString($e)
            );
        }
    }
}
