<?php

namespace App\Domain\Bus;

use App\Controller\Web\CreateComment\v1\Output\CreatedCommentDTO;
use App\Domain\Model\CommentModel;

interface PublishCommentBusInterface
{
    public function sendPublishCommentMessage(CommentModel $commentModel);
}
