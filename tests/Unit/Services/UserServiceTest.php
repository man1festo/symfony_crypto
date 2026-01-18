<?php

namespace Unit\Services;

use App\Domain\Entity\User;
use App\Domain\Model\CreateUserModel;
use App\Domain\Services\UserService;
use App\Infrastructure\Repository\UserRepository;
use Codeception\Test\Unit;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserServiceTest extends Unit
{
    private const PASSWORD_HASH = 'my_hash';
    private const DEFAULT_ROLES = ['ROLE_USER'];

    /**
     * @dataProvider createTestCases
     * @param CreateUserModel $createUserModel
     * @param array $expectedData
     * @return void
     */
    public function testCreate(CreateUserModel $createUserModel, array $expectedData): void
    {
        $userService = $this->prepareUserService();

        $user = $userService->create($createUserModel);
        $actualData = [
            'class' => get_class($user),
            'login' => $user->getLogin(),
            'passwordHash' => $user->getPassword(),
            'roles' => $user->getRoles(),
        ];
        static::assertSame($expectedData, $actualData);
    }

    protected function createTestCases(): \Generator
    {
        yield [
            new CreateUserModel(
                'someLogin',
                'somePassword',
            ),
            [
                'class' => User::class,
                'login' => 'someLogin',
                'passwordHash' => self::PASSWORD_HASH,
                'roles' => self::DEFAULT_ROLES,
            ]
        ];

        yield [
            new CreateUserModel(
                'otherLogin',
                'somePassword',
            ),
            [
                'class' => User::class,
                'login' => 'otherLogin',
                'passwordHash' => self::PASSWORD_HASH,
                'roles' => self::DEFAULT_ROLES,
            ]
        ];
    }

    private function prepareUserService(): UserService
    {
        $userRepository = \Mockery::mock(UserRepository::class);
        $userRepository->shouldReceive('create')->with(
            \Mockery::on(function ($user): bool {
                $user->setId(1);
                $user->setCreatedAt();
                $user->setUpdatedAt();
                return true;
            }
        ));
        $userPasswordHasher = \Mockery::mock(UserPasswordHasherInterface::class);
        $userPasswordHasher->shouldReceive('hashPassword')->andReturn(self::PASSWORD_HASH);
        return new UserService($userRepository, $userPasswordHasher);

    }
}
