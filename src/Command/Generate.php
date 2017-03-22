<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace SocialConnect\SCG\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Generate extends \Symfony\Component\Console\Command\Command
{
    public function configure()
    {
        $this->setName('generate')
            ->addArgument('swagger-path', InputArgument::REQUIRED, 'Path to swagger file')
            ->addArgument('output-path', InputArgument::REQUIRED, 'Where we should put generate module');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $swaggerPath = $input->getArgument('swagger-path');
        $outputPath = $input->getArgument('output-path');


        $swaggerPathContent = file_get_contents($swaggerPath);

        $swaggerSerializer = new \Swagger\Serializer();

        /** @var \Swagger\Annotations\Swagger $swagger */
        $swagger = $swaggerSerializer->deserialize(
            $swaggerPathContent,
            \Swagger\Annotations\Swagger::class
        );

        $loader = new \Twig_Loader_Filesystem(
            [
                realpath(__DIR__ . '/../') . '/resource/js/'
            ]
        );

        $twig = new \Twig_Environment($loader);

        $tags = [];

        $populateTag = function ($path) use (&$tags) {
            $tag = current($path->tags);

            if (isset($tags[$tag])) {
                $tags[$tag][] = $path;
            } else {
                $tags[$tag] = [$path];
            }
        };

        foreach ($swagger->paths as $path) {
            if ($path->get) {
                $currentPath = $path->get;
                // fix path
                $currentPath->path = $path->path;

                $populateTag($currentPath);
            }

            if ($path->post) {
                $currentPath = $path->post;
                // fix path
                $currentPath->path = $path->path;

                $populateTag($currentPath);
            }

            if ($path->put) {
                $currentPath = $path->put;
                // fix path
                $currentPath->path = $path->path;

                $populateTag($currentPath);
            }

            if ($path->delete) {
                $currentPath = $path->delete;
                // fix path
                $currentPath->path = $path->path;

                $populateTag($currentPath);
            }
        }

        foreach ($tags as $tag => $paths) {
            $result = $twig->render(
                'tag.twig',
                [
                    'paths' => $paths
                ]
            );

            file_put_contents($outputPath . '/' . $tag . '.js', $result);
        }
    }
}
