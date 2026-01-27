<?php

namespace App\Controller\Web\CreateComment\v1;

use App\Controller\Web\CreateComment\v1\Input\CreateCommentDTO;
use App\Controller\Web\CreateComment\v1\Output\CreatedCommentDTO;
use App\Domain\Model\CreateCommentModel;
use App\Domain\Services\CommentService;
use App\Domain\Services\MakeModelService;

class Manager
{
    public function __construct(private readonly CommentService $commentService, private readonly MakeModelService $makeModelService)
    {

    }

    public function createComment(CreateCommentDTO $createCommentDTO): CreatedCommentDTO
    {
        $model = $this->makeModelService->makeModel(CreateCommentModel::class, $createCommentDTO->orderId, $createCommentDTO->userId, $createCommentDTO->comment);
        $commentId = $this->commentService->create($model);
        return new CreatedCommentDTO($createCommentDTO->orderId, $createCommentDTO->comment, $createCommentDTO->userId, $commentId);
    }
}
