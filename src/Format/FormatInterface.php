<?php
declare(strict_types=1);

namespace SocialConnect\SCG\Format;

interface FormatInterface
{
    function getFileExt();

    function getName();
}
