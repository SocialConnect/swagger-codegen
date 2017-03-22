<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace SocialConnect\SCG\Command;

class Generate extends \Symfony\Component\Console\Command\Command
{
    public function configure()
    {
        $this->setName('generate');
    }
}
