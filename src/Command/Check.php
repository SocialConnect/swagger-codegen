<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace SocialConnect\SCG\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Check extends \Symfony\Component\Console\Command\Command
{
    public function configure()
    {
        $this->setName('check')
             ->addArgument('swagger-path', InputArgument::REQUIRED, 'Path to swagger file');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $swaggerPath = $input->getArgument('swagger-path');

        $swaggerPathContent = file_get_contents($swaggerPath);

        $swaggerSerializer = new \Swagger\Serializer();

        ini_set('display_errors', 1);
        error_reporting(-1);

        set_error_handler(
            function ($errno, $errstr, $errfile, $errline, $errcontext) use ($output) {
                $output->writeln("<error>{$errstr}</error>");
                $output->writeln('');
            }
        );

        $swaggerSerializer->deserialize(
            $swaggerPathContent,
            \Swagger\Annotations\Swagger::class
        );
    }
}
