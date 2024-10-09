<?php

namespace App\RequestValidator\User;

use App\RequestValidator\AbstractRequestValidator;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\User;

class UserRequestValidator extends AbstractRequestValidator
{
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Email(
        message: 'The email {{ value }} is not a valid email.',
    )]
    public string $email;

    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(
        min: 5,
        max: 10,
        minMessage: 'Password must be at least {{ limit }} characters long',
        maxMessage: 'Password cannot be longer than {{ limit }} characters',
    )]
    public string $password;

    #[Assert\Choice(
        choices: User::ROLES,
        message: 'Choose a valid role.',
        multiple: true
    )]
    #[Assert\NotNull]
    public array $roles;
}
