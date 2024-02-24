<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $em;

    public function __construct(
        ManagerRegistry        $registry,
        EntityManagerInterface $em
    )
    {
        parent::__construct($registry, User::class);

        $this->em = $em;
    }

    public function create(string $firstName, string $lastName, string $email, string $password): User
    {
        $user = new User();

        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setEmail($email);
        $user->setPassword($password);

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }
}
