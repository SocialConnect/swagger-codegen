<?php
declare(strict_types=1);

namespace SocialConnect\SCG\Format;

use SocialConnect\SCG\Format\JS\Flow;
use SocialConnect\SCG\Format\TypeScript\Reqster;

class FormatFactory
{
    public static function factory($id)
    {
        switch (strtolower($id)) {
            case Flow::NAME:
                return new Flow();
            case Reqster::NAME:
                return new Reqster();
            default:
                throw new \InvalidArgumentException('Unsupported template format');
        }
    }
}
