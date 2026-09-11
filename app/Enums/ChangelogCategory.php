<?php

namespace App\Enums;

enum ChangelogCategory: string
{
    case Added = 'added';
    case Changed = 'changed';
    case Fixed = 'fixed';
    case Technical = 'technical';
}
