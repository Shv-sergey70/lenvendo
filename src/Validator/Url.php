<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Url extends Constraint
{
    public $message = 'Значеине не является валидной ссылкой: "{{ value }}"';
}
