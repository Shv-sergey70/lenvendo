<?php

namespace App\Validator;

use App\Repository\BookmarkRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueUrlValidator extends ConstraintValidator
{
    /** @var BookmarkRepository */
    private $bookmarkRepository;

    /**
     * @param BookmarkRepository $bookmarkRepository
     */
    public function __construct(BookmarkRepository $bookmarkRepository)
    {
        $this->bookmarkRepository = $bookmarkRepository;
    }

    /**
     * @param mixed      $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint UniqueUrl */

        if (null === $value || '' === $value) {
            return;
        }

        if (! $this->bookmarkRepository->findOneBy(['url' => $value])) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->addViolation();
    }
}
