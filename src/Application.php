<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace SocialConnect\SCG;

class Application extends \Symfony\Component\Console\Application
{
    public function __construct()
    {
        parent::__construct('Swagger CodeGen', "0.2.3");

        $this->add(new Command\Check());
        $this->add(new Command\Generate());
    }
}
