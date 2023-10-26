<?php

namespace common\models\enums;

enum BookTypeEnum
{
    const TYPE_PRINT = 'print';
    const TYPE_DIGIT = 'digit';
    const TYPE_GRAPHIC = 'graphic';

    const TYPE_LIST = [
        self::TYPE_PRINT => 'Print Edition',
        self::TYPE_DIGIT => 'Digit Edition',
        self::TYPE_GRAPHIC => 'Graphic Edition',
    ];
}
