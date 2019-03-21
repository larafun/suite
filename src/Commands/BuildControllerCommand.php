<?php

namespace Larafun\Suite\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class BuildControllerCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'build:controller';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new controller';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Controller';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        $stub = '/stubs/controller';
        if ($this->option('model')) {
            $stub .= '.model';
        }
        if ($this->option('filter')) {
            $stub .= '.filter';
        }
        return __DIR__ . $stub . '.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\\Http\\Controllers\\'.str_replace('/', '\\', config('suite.path.controllers', 'Api'));
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $replace = [];

        if ($this->option('model')) {
            $replace = $this->buildModelReplacements($replace);
        }

        if ($this->option('filter')) {
            $replace = $this->buildFilterReplacements($replace);
        }

        return str_replace(
            array_keys($replace),
            array_values($replace),
            parent::buildClass($name)
        );
    }

    /**
     * Build the model replacement values.
     *
     * @param  array  $replace
     * @return array
     */
    protected function buildModelReplacements(array $replace)
    {
        $modelClass = $this->parseClassName($this->option('model'));

        return array_merge($replace, [
            'DummyFullModelClass' => $modelClass,
            'DummyModelClass' => class_basename($modelClass),
            'DummyModelVariable' => lcfirst(class_basename($modelClass)),
        ]);
    }

    /**
     * Build the filter replacement values.
     *
     * @param  array  $replace
     * @return array
     */
    protected function buildFilterReplacements(array $replace)
    {
        $filterClass = $this->parseClassName($this->option('filter'));

        return array_merge($replace, [
            'DummyFilterFullClass'  => $filterClass,
            'DummyFilterClass'      => class_basename(($filterClass)),
            'DummyFilterVariable'   => lcfirst(class_basename($filterClass)),
        ]);
    }

    /**
     * Get the fully-qualified class name.
     *
     * @param  string  $class
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    protected function parseClassName($class)
    {
        if (preg_match('([^A-Za-z0-9_/\\\\])', $class)) {
            throw new InvalidArgumentException('Class name contains invalid characters.');
        }

        $class = trim(str_replace('/', '\\', $class), '\\');

        if (!Str::startsWith($class, $rootNamespace = $this->rootNamespace())) {
            $class = $rootNamespace . $class;
        }

        return $class;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the controller'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['model', 'm', InputOption::VALUE_OPTIONAL, 'Generate a controller for the given model.'],
            ['filter', 'f', InputOption::VALUE_OPTIONAL, 'Generate a controller for the given filter.'],
        ];
    }
}
