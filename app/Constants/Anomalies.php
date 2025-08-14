<?php

namespace App\Constants;

enum Anomalies: string
{
    case NO_FACE = 'no_face';
    case LESS_FACE = 'less_face';
    case MORE_FACE = 'more_face';
}
