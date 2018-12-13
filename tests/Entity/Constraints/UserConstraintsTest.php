<?php
namespace App\Tests\AppBundle\Entity;

use App\DataFixtures\UserFixtures;
use App\Entity\User;

class UserConstraintsTest extends AbstractConstraintsTest
{

    private $user;

    public function setUp()
    {
        parent::setUp();

        $this->user = new User();

        $this->user->setUsername('demoUser');
        $this->user->setPassword('encodedPassword');
        $this->user->setEmail('user@entity-unit-test.com');
        $this->user->setRoles(['ROLE_USER']);

    }

    public function testEmptyUserNameValidation()
    {
        $this->notBlankValidationConstraintTest($this->user, 'username');
    }

    public function testEmptyEmailValidation()
    {
        $this->notBlankValidationConstraintTest($this->user, 'email');
    }

    public function testEmptyPasswordValidation()
    {
        $this->notBlankValidationConstraintTest($this->user, 'password');
    }

    public function testInvalidEmailValidation()
    {

        $this->user->setEmail('an@invalid@email.com');

        $errors = $this->validator->validate($this->user);

        if (\count($errors) == 0) {
            throw new \Exception(self::EXPECTED_CONSTRAINT_VIOLATION_EXCEPTION_MESSAGE);
        }

        $this->assertEquals(get_class($errors[0]->getConstraint()), 'Symfony\Component\Validator\Constraints\Email');
        $this->assertEquals($errors[0]->getPropertyPath(),'email');
    }

    public function testNotUniqueEmailValidation()
    {
        // Load fixtures (in order to get an user in DB for the test)
        $this->addFixture(new UserFixtures(static::$kernel->getContainer()->get('security.password_encoder')));
        $this->executeFixtures();

        $this->user->setEmail('testUser@functional-tests.test');

        $errors = $this->validator->validate($this->user);

        if (sizeof($errors) == 0) {
            throw new \Exception('A constraint violation was expected but there were none');
        }

        $this->assertEquals(get_class($errors[0]->getConstraint()), 'Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity');
        $this->assertEquals($errors[0]->getPropertyPath(),'email');
    }

}