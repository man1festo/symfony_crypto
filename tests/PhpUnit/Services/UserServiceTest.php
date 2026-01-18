<?php
declare(strict_types=1);

namespace UnitTests\Services;

use App\Domain\Entity\User;
use App\Domain\Model\CreateUserModel;
use App\Domain\Services\UserService;
use App\Domain\ValueObject\UserRolesEnum;
use App\Infrastructure\Repository\UserRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserServiceTest extends TestCase
{
    private $userRepository;
    private $passwordHasher;
    private UserService $service;

    protected function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->passwordHasher = $this->createMock(UserPasswordHasherInterface::class);
        $this->service = new UserService($this->userRepository, $this->passwordHasher);
    }

    public function testCreateUserByLogin(): void
    {
        $login = 'testuser';
        $this->userRepository->expects($this->once())
            ->method('create')
            ->with($this->callback(fn($u) => $u instanceof User && $u->getLogin() === $login))
            ->willReturn(42);

        $user = $this->service->createUserByLogin($login);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals(42, $user->getId());
        $this->assertEquals($login, $user->getLogin());
    }

    public function testCreateNewUser(): void
    {
        $model = new CreateUserModel('newuser', 'plain');


        $this->passwordHasher->expects($this->once())
            ->method('hashPassword')
            ->willReturn('hashed-pass');

        $this->userRepository->expects($this->once())
            ->method('create')
            ->willReturn(7);

        $user = $this->service->create($model);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals(7, $user->getId());
        $this->assertEquals('newuser', $user->getLogin());
    }

    public function testCreateUpdateExistingUser(): void
    {
        $existing = new User();
        $existing->setId(10);
        $existing->setLogin('old');

        $model = new CreateUserModel('updated', 'newpass', 10);

        $this->userRepository->expects($this->once())
            ->method('find')
            ->with(10)
            ->willReturn($existing);

        $this->passwordHasher->expects($this->once())
            ->method('hashPassword')
            ->with($this->isInstanceOf(User::class), 'newpass')
            ->willReturn('new-hash');

        $this->userRepository->expects($this->once())
            ->method('create')
            ->with($this->callback(function (User $u) {
                return $u->getId() === 10 && $u->getLogin() === 'updated' && $u->getPassword() === 'new-hash';
            }))
            ->willReturn(10);

        $user = $this->service->create($model);

        $this->assertEquals(10, $user->getId());
        $this->assertEquals('updated', $user->getLogin());
    }

    public function testFindUserById(): void
    {
        $user = new User();
        $user->setId(5);

        $this->userRepository->expects($this->once())
            ->method('find')
            ->with(5)
            ->willReturn($user);

        $result = $this->service->findUserById(5);
        $this->assertSame($user, $result);
    }

    public function testApproveUser(): void
    {
        $user = new User();
        $this->userRepository->expects($this->once())
            ->method('approveUser')
            ->with($user);

        $this->assertTrue($this->service->approveUser($user));
    }

    public function testFindAll(): void
    {
        $list = [new User(), new User()];
        $this->userRepository->expects($this->once())
            ->method('findAll')
            ->willReturn($list);

        $this->assertSame($list, $this->service->findAll());
    }

    public function testCreateFromForm(): void
    {
        $user = new User();
        $this->userRepository->expects($this->once())
            ->method('create')
            ->with($user);

        $this->service->createFromForm($user);
        $this->assertTrue(true); // достижение сюда означает, что мок вызван корректно
    }

    public function testFindByLogin(): void
    {
        $u1 = new User();
        $u1->setLogin('a');
            $this->userRepository->expects($this->once())
            ->method('findUsersByLogin')
            ->with('a')
            ->willReturn([$u1]);

        $found = $this->service->findByLogin('a');
        $this->assertSame($u1, $found);
    }

    public function testFindByLoginNotFound()
    {
        $this->userRepository->expects($this->once())
            ->method('findUsersByLogin')
            ->with('b')
            ->willReturn([]);
        $notFound = $this->service->findByLogin('b');
        $this->assertNull($notFound);
    }

    public function testUpdateUserTokenNotFound(): void
    {
        $this->userRepository->expects($this->once())
            ->method('findUsersByLogin')
            ->with('missing')
            ->willReturn([]);

        $this->assertNull($this->service->updateUserToken('missing'));
    }

    public function testUpdateUserTokenSuccess(): void
    {
        $user = new User();
        $user->setLogin('tokuser');

        $this->userRepository->expects($this->once())
            ->method('findUsersByLogin')
            ->with('tokuser')
            ->willReturn([$user]);

        $this->userRepository->expects($this->once())
            ->method('updateUserToken')
            ->with($user)
            ->willReturn('new-token');

        $this->assertEquals('new-token', $this->service->updateUserToken('tokuser'));
    }

    public function testFindByToken(): void
    {
        $user = new User();
        $this->userRepository->expects($this->once())
            ->method('findByToken')
            ->with('tkn')
            ->willReturn($user);

        $this->assertSame($user, $this->service->findByToken('tkn'));
    }
}
