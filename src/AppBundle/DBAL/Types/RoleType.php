<?php

namespace AppBundle\DBAL\Types;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

final class RoleType extends AbstractEnumType
{
    const TEACHER = 'ROLE_TEACHER';
    const STUDENT = 'ROLE_STUDENT';

    protected static $choices = [
        self::TEACHER => 'Mokytojas',
        self::STUDENT => 'Mokinys'
    ];
}
