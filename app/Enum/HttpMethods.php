<?php

namespace App\Enum;

enum HttpMethods: string
{
    case METHOD_GET = 'get';

    case METHOD_DELETE = 'delete';

    case METHOD_PUT = 'put';
}
