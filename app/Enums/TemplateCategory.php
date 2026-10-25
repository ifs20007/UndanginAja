<?php

namespace App\Enums;

enum TemplateCategory: string
{
    case Elegant = 'elegant';
    case Rustic = 'rustic';
    case Modern = 'modern';
    case Minimalist = 'minimalist';
    case Wedding = 'wedding';
    case Birthday = 'birthday';
    case Engagement = 'engagement';
    case Corporate = 'corporate';
    case Other = 'other';
}
