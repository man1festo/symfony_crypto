<?php

namespace App\Domain\Services;

use App\Controller\Web\CreateComment\v1\Output\CreatedCommentDTO;
use App\Domain\Bus\PublishCommentBusInterface;
use App\Domain\DTO\CreateCommentDTO;
use App\Domain\Entity\Comment;
use App\Domain\Model\CommentModel;
use App\Domain\Model\CreateCommentModel;
use App\Infrastructure\Repository\CommentRepository;

class CommentService
{
    public function __construct(private readonly CommentRepository $commentRepository, private readonly PublishCommentBusInterface $publishCommentBus)
    {

    }
    public function getByOrder(int $orderId): array
    {
        return $this->commentRepository->getByOrder($orderId);
    }

    public function create(CreateCommentModel $createCommentModel): int
    {
        $comment = new Comment();
        $comment->setContent($createCommentModel->comment);
        $comment->setOrderId($createCommentModel->orderId);
        $comment->setUserId($createCommentModel->userId);
        $id = $this->commentRepository->create($comment);
        $createCommentModel = new CommentModel($id, $createCommentModel->orderId, $createCommentModel->userId, $createCommentModel->comment);
        $this->publishCommentBus->sendPublishCommentMessage($createCommentModel);
        return $id;
    }
}
