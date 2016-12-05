<?php
/**
 * Created by PhpStorm.
 * User: robertas
 * Date: 16.12.5
 * Time: 19.34
 */

namespace AppBundle\DBAL\Types;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

final class BestResultDeterminationType extends AbstractEnumType
{
    const MAX = 'max';
    const MIN = 'min';

    protected static $choices = [
        self::MAX => 'Didžiausias rezultatas',
        self::MIN => 'Mažiausias rezultatas'
    ];
}
