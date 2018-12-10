<?php declare(strict_types=1);

namespace DoctrineMigrations;

use App\Entity\User;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/*
* This Migration has been manually created in order to ensure that all users will have at least
* ROLE_USER stored in their roles attribute (in DB)
*/
final class Version20181205150000 extends AbstractMigration implements ContainerAwareInterface
{

    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function up(Schema $schema) : void
    {

        $entityManager = $this->container->get('doctrine.orm.entity_manager');

        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $users = $entityManager->getRepository(User::class)->findAll();

        foreach($users as $index => $user) {
            if(!in_array('ROLE_USER', $user->getRoles())) {
                $roles = $user->getRoles();
                $roles[] = 'ROLE_USER';
                $user->setRoles($roles);
            }

            if ($index%10 == 0) {
                $entityManager->flush();
            }
        }

        $entityManager->flush();
    }

    public function down(Schema $schema) : void
    {
        $entityManager = $this->container->get('doctrine.orm.entity_manager');

        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $users = $entityManager->getRepository(User::class)->findAll();

        foreach($users as $index => $user) {

            // Remove ROLE_USER key
            if((($key = array_search('ROLE_USER', $user->getRoles())) !== false) ) {
                $roles = $user->getRoles();
                unset($roles[$key]);
                $user->setRoles($roles);
            }

            if ($index%10 == 0) {
                $entityManager->flush();
            }
        }

        $entityManager->flush();
    }
}
