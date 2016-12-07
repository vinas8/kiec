<?php
/**
 * Created by PhpStorm.
 * User: robertas
 * Date: 16.12.5
 * Time: 19.34
 */

namespace AppBundle\DBAL\Types;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

final class OriginType extends AbstractEnumType
{
    const NATIVE = 'native';
    const CREATED = 'created';

    protected static $choices = [
        self::NATIVE => 'Bendra rungtis',
        self::CREATED => 'Individuali rungtis'
    ];
}
