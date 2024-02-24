<?php

namespace App\Service;

use App\Entity\User;
use App\Exception\UserCreateException;
use App\Exception\UserNotFoundException;
use App\Repository\UserRepository;
use Exception;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserService
{
    private UserRepository $userRepository;
    private TranslatorInterface $translator;

    public function __construct(
        UserRepository $userRepository,
        TranslatorInterface $translator
    )
    {
        $this->userRepository = $userRepository;
        $this->translator = $translator;
    }

    /**
     * @throws UserCreateException
     */
    public function create(string $firstName, string $lastName, string $email, string $password): User
    {
        try {
            $factory = new PasswordHasherFactory([
                'common' => ['algorithm' => 'bcrypt'],
                'sodium' => ['algorithm' => 'sodium'],
            ]);

            $hasher = $factory->getPasswordHasher('common');
            $hashedPassword = $hasher->hash($password);

            return $this->userRepository->create($firstName, $lastName, $email, $hashedPassword);
        } catch (Exception $exception) {
            throw new UserCreateException(
                message: $this->translator->trans('errors.user_create'),
                previous: $exception->getPrevious()
            );
        }
    }

    public function index(): array
    {
        return $this->userRepository->findAll();
    }

    /**
     * @throws UserNotFoundException
     */
    public function show(int $id): User
    {
        $user = $this->userRepository->find($id);

        if ($user === null) {
            throw new UserNotFoundException($this->translator->trans('errors.user_not_found'));
        }

        return $user;
    }
}