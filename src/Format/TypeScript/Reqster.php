<?php

namespace SocialConnect\SCG\Format\TypeScript;

use SocialConnect\SCG\Format\FormatInterface;

class Reqster implements FormatInterface
{
    const NAME = 'ts-reqster';

    public function getFileExt()
    {
        return 'ts';
    }

    public function getName()
    {
        return self::NAME;
    }
}
