<?php

namespace App\RequestValidator\Task;

use App\RequestValidator\AbstractRequestValidator;
use Symfony\Component\Validator\Constraints as Assert;

class TaskRequestValidator extends AbstractRequestValidator
{
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(
        min: 5,
        max: 255,
        minMessage: 'Password must be at least {{ limit }} characters long',
        maxMessage: 'Password cannot be longer than {{ limit }} characters',
    )]
    public string $title;

    public ?string $description = null;

    #[Assert\NotBlank]
    #[Assert\NotNull]
    public string $dueDate;

    public ?int $userId = null;

}
