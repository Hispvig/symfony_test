<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;
readonly class TagDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Type(type: 'string')]
        #[Assert\Length(max: 255)]
        public string $name,
    ) {
    }
}