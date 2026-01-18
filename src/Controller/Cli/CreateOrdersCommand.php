<?php

namespace App\Controller\Cli;

use App\Domain\Model\CreateBuyOrderModel;
use App\Domain\Services\AccountService;
use App\Domain\Services\MakeModelService;
use App\Domain\Services\OrderService;
use App\Domain\Services\UserService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateOrdersCommand extends Command
{

    public function __construct(
        private readonly UserService $userService,
        private readonly OrderService $orderService,
        private readonly AccountService $accountService,
        private readonly MakeModelService $makeModelService
    )
    {
        parent::__construct();
    }
    public function configure()
    {
        $this->setName('orders:add')
            ->setDescription('Adds orders to user')
            ->addArgument('userId', InputArgument::REQUIRED, 'ID of user')
            ->addArgument('accountId', InputArgument::REQUIRED, 'Id of users account')
            ->addArgument('amount', InputArgument::REQUIRED, 'Order amount')
            ->addArgument('count', InputArgument::REQUIRED, 'How many orders should be added');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $userId = (int)$input->getArgument('userId');
        $user = $this->userService->findUserById($userId);
        if ($user === null) {
            $output->write("<error>User with ID $userId doesn't exist</error>\n");
            return self::FAILURE;
        }
        $accountId = (int)$input->getArgument('accountId');
        $account = $this->accountService->findAccountById($accountId);
        if ($account === null) {
            $output->write("<error>Account with ID $accountId doesn't exist</error>\n");
            return self::FAILURE;
        }
        $count = (int)$input->getArgument('count');
        if ($count <= 0) {
            $output->write("<error>Count should be positive integer</error>\n");
            return self::FAILURE;
        }
        $amount= (int)$input->getArgument('amount');
        if ($amount < 0) {
            $output->write("<error>Amount should be positive integer</error>\n");
            return self::FAILURE;
        }
        $amountPerOrder = $amount/$count;
        $output->write('<info>Started</info>');
        for ($i = 0; $i < $count; $i++) {
            $model = $this->makeModelService->makeModel(CreateBuyOrderModel::class, $userId, $accountId, $amountPerOrder);
            $this->orderService->createBuyOrder($model);
        }
        $output->write("<info>$count orders created</info>\n");
        return self::SUCCESS;
    }


}
