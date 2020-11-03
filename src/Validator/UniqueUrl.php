<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueUrl extends Constraint
{
    public $message = 'Закладка с url-адресом "{{ value }}" уже существет';
}
