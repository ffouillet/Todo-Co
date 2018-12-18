<?php
namespace App\Tests\Entity\Constraints;

use App\Tests\Utils\FixturesAwareKernelTestCase;

abstract class AbstractConstraintsTest extends FixturesAwareKernelTestCase
{

    const EXPECTED_CONSTRAINT_VIOLATION_EXCEPTION_MESSAGE = 'A constraint violation was expected but there were none';

    protected $validator;

    public function setUp()
    {
        self::bootKernel();

        // Warning, LiipFunctionalTestBundle overrides symfony 'validator' id.
        // Here we are getting an Liip\FunctionalTestBundle\Validator\DataCollectingValidator object.
        $this->validator = self::$kernel->getContainer()->get('validator');
    }

    protected function tearDown()
    {
        $this->validator = null;
    }

    protected function notBlankValidationConstraintTest($entity = null, $attribute) {

        if (!is_string($attribute) || $attribute == "") {
            throw new \RuntimeException(
                'Cannot validate this attribute as the attribute parameter is not a string or is empty.');
        }

        if(null === $entity) {
            throw new \RuntimeException(
                'Entity parameter cannot be null to test its attribute "'.$attribute.'" NotBlank validation constraint.');
        }

        $setterMethodName = 'set' . ucfirst($attribute);

        if (!method_exists($entity, $setterMethodName)) {
            throw new \RuntimeException(
                'Method "' . $setterMethodName . '" doesn\'t exists in class '.get_class($entity)
                . ', cannot pursue the empty attribute constraint validation');
        }

        $entity->$setterMethodName('');

        $errors = $this->validator->validate($entity);

        if (\count($errors) == 0) {
            throw new \Exception(self::EXPECTED_CONSTRAINT_VIOLATION_EXCEPTION_MESSAGE);
        }

        $this->assertEquals(get_class($errors[0]->getConstraint()), 'Symfony\Component\Validator\Constraints\NotBlank');
        $this->assertEquals($errors[0]->getPropertyPath(),$attribute);

    }

}