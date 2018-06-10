<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace SocialConnect\SCG\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

function flowTypeEscape($type, $values) {
        $values = array_map(
            function ($value) use ($type) {
                if ($type == 'string') {
                    return '"' . $value . '"';
                }

                return $value;
            },
            $values
        );

        return implode(' | ', $values);
}

function stripDefinitions($value) {
    if (strpos($value, '#/definitions/') === 0) {
        return substr($value, 14);
    }

    return $value;
}

function stripParameters($value) {
    if (strpos($value, '#/parameters/') === 0) {
        return substr($value, 13);
    }

    return $value;
}

function handleFlowParameterType(\Swagger\Annotations\Parameter $parameter) {
    if ($parameter->schema) {
        if ($parameter->schema->ref) {
            return stripDefinitions($parameter->schema->ref);
        }

        // @todo Think about it
        return 'any';
    }

    if ($parameter->enum) {
        return flowTypeEscape($parameter->type, $parameter->enum);
    }

    switch ($parameter->type) {
        case 'file':
            return 'File';
        case 'integer':
            return 'number';
        case 'array':
            if ($parameter->items) {
                return "Array<{$parameter->items->type}>";
            }

            return "Array<any>";
        default:
            return $parameter->type;
    }
}

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

        // Better thing ;)
        foreach ($swagger->definitions as $definition) {
            if ($definition->required) {
                /** @var \Swagger\Annotations\Property $property */
                foreach ($definition->properties as $property) {
                    if (in_array($property->property, $definition->required)) {
                        $property->required = true;
                    }
                }
            }
        }

        $loader = new \Twig_Loader_Filesystem(
            [
                realpath(__DIR__ . '/../') . '/resource/js/'
            ]
        );

        $twig = new \Twig_Environment(
            $loader,
            [
                'autoescape' => false
            ]
        );

        $twig->addFilter(
            new \Twig_Filter(
                'flowFieldEscape',
                function ($value) {
                    if ($value == 'new' || strpos($value, '-') !== false) {
                        return '"' . $value . '"';
                    }

                    return $value;
                }
            )
        );

        $twig->addFunction(
            new \Twig_Function(
                'flowParameterType',
                function (\Swagger\Annotations\Parameter $parameter) use ($swagger) {
                    if ($parameter->enum) {
                        return flowTypeEscape($parameter->type, $parameter->enum);
                    }

                    return handleFlowParameterType($parameter);
                }
            )
        );

        $twig->addFunction(
            new \Twig_Function(
                'extractParameterByRef',
                function (\Swagger\Annotations\Parameter $parameter) use ($swagger) {
                    $stripped = stripParameters($parameter->ref);
                    foreach ($swagger->parameters as $param) {
                        if ($stripped === $param->name) {
                            return $param;
                        }
                    }
                }
            )
        );

        $twig->addFunction(
            new \Twig_Function(
                'makePath',
                function (\Swagger\Annotations\Operation $operation) {
                    $path = $operation->path;

                    if ($operation->parameters) {
                        foreach ($operation->parameters as $parameter) {
                            if ($parameter->in === 'path') {
                                $path = str_replace(
                                    '{' . $parameter->name . '}',
                                    '${' . $parameter->name . '}',
                                    $path
                                );
                            }
                        }
                    }

                    return "`{$path}`";
                }
            )
        );

        $twig->addFunction(
            new \Twig_Function(
                'flowPropertyType',
                function (\Swagger\Annotations\Property $parameter) {
                    if ($parameter->enum) {
                        return flowTypeEscape($parameter->type, $parameter->enum);
                    }

                    switch ($parameter->type) {
                        case 'object':
                            if ($parameter->ref) {
                                return stripDefinitions($parameter->ref);
                            }

                            return 'Object';
                        case 'integer':
                            return 'number';
                        case 'array':
                            if ($parameter->items) {
                                if ($parameter->items->type) {
                                    return "Array<{$parameter->items->type}>";
                                }

                                if ($parameter->items->ref) {
                                    $ref = stripDefinitions($parameter->items->ref);
                                    return "Array<{$ref}>";
                                }
                            }

                            return "Array<any>";
                        default:
                            return stripDefinitions($parameter->type);
                    }
                }
            )
        );

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
            $imports = [];

            /** @var \Swagger\Annotations\Post $path */
            foreach ($paths as $path) {
                $path->return = 'any';

                if ($path->responses) {
                    /** @var \Swagger\Annotations\Response $response */
                    foreach ($path->responses as $response) {
                        if ($response->schema) {
                            $schema = $response->schema;

                            if ($schema->type === 'array') {
                                if ($schema->items) {
                                    if ($schema->items->ref) {
                                        $definition = stripDefinitions($schema->items->ref);
                                        $imports[$definition] = $definition;

                                        $path->return = "Array<{$definition}>";
                                        continue;
                                    }

                                    if ($schema->items->anyOf) {
                                        $definitions = [];

                                        foreach ($schema->items->anyOf as $any) {
                                            if ($any->{'$ref'}) {
                                                $definitions[] = $definition = stripDefinitions(
                                                    $any->{'$ref'}
                                                );

                                                $imports[$definition] = $definition;
                                            }
                                        }

                                        if ($definitions) {
                                            $path->return = 'Array<' . implode('|', $definitions) . '>';
                                            continue;
                                        }
                                    }
                                }

                                $path->return = "Array<any>";
                            }

                            if ($schema->ref) {
                                $definition = stripDefinitions($schema->ref);
                                $imports[$definition] = $definition;

                                $path->return = $definition;
                            }
                        }
                    }
                }
            }

            $result = $twig->render(
                'tag.twig',
                [
                    'imports' => $imports,
                    'paths' => $paths
                ]
            );

            file_put_contents($outputPath . '/' . $tag . '.js', $result);
        }

        $result = $twig->render(
            'definitions.twig',
            [
                'definitions' => $swagger->definitions
            ]
        );

        file_put_contents($outputPath . '/definitions.js', $result);


        $protocol = 'https';

        if ($swagger->schemes) {
            $protocol = current($swagger->schemes);
        }

        $path = $protocol . '://' . $swagger->host;

        if ($swagger->basePath) {
            $path .= $swagger->basePath;
        }

        $result = $twig->render(
            'Client.twig',
            [
                'path' => $path
            ]
        );

        file_put_contents($outputPath . '/Client.js', $result);

        $result = $twig->render(
            'index.twig',
            [
                'files' => array_keys($tags)
            ]
        );

        file_put_contents($outputPath . '/index.js', $result);
    }
}
