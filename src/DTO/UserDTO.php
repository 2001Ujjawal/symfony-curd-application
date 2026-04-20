<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UserDTO
{
    #[Assert\NotBlank(message: "Name is required")]
    public ?string $name = null;

    #[Assert\NotBlank(message: "Email is required")]
    #[Assert\Email(message: "Invalid email format")]
    public ?string $email = null;


    #[Assert\NotBlank(message: "Password is required")]
    #[Assert\Type(type: 'string', message: 'Password must be string')]
    #[Assert\Length(min: 6, minMessage: "Password must be at least 6 characters")]
    public ?string $password = null;

    // #[Assert\NotBlank(message: "Image is required")]
    #[Assert\Type(type: 'string', message: 'Image must be string')]
    public ?string $image = null;

    #[Assert\NotBlank(message: "phone number is required")]
    #[Assert\Type(type: 'string', message: 'phone number must be string')]
    #[Assert\Length(min: 10, minMessage: "phone number must be at least 6 characters")]
    public ?string $phone_no = null;
}
