<?php
declare(strict_types=1);

namespace SocialConnect\SCG\Format\JS;

use SocialConnect\SCG\Format\FormatInterface;

class Flow implements FormatInterface
{
    const NAME = 'js-flow';

    public function getFileExt()
    {
        return 'ts';
    }

    public function getName()
    {
        return self::NAME;
    }
}
