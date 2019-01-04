<?php

namespace Larafun\Suite\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class BuildModelCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'build:model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new model';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Model';


    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->option('all')) {
            $this->input->setOption('factory', true);
            $this->input->setOption('migration', true);
            $this->input->setOption('controller', true);
            $this->input->setOption('filter', true);
            $this->input->setOption('transformer', true);
        }

        if (parent::handle() === false && !$this->option('force')) {
            return;
        }

        if ($this->option('factory')) {
            $this->createFactory();
        }

        if ($this->option('migration')) {
            $this->createMigration();
        }

        if ($this->option('controller')) {
            $this->createController();
        }

        if ($this->option('filter')) {
            $this->createFilter();
        }

        if ($this->option('transformer')) {
            $this->createTransformer();
        }
    }

    /**
     * Create a model factory for the model.
     *
     * @return void
     */
    protected function createFactory()
    {
        $factory = Str::studly(class_basename($this->argument('name')));

        $this->call('make:factory', [
            'name' => "{$factory}Factory",
            '--model' => $this->qualifyClass($this->getNameInput()),
        ]);
    }

    /**
     * Create a migration file for the model.
     *
     * @return void
     */
    protected function createMigration()
    {
        $table = Str::plural(Str::snake(str_replace(['/', '\\', ' '], '', $this->argument('name'))));

        $this->call('make:migration', [
            'name' => "create_{$table}_table",
            '--create' => $table,
        ]);
    }

    /**
     * Create a controller for the model.
     *
     * @return void
     */
    protected function createController()
    {
        $controller = config('suite.path.controllers', 'Api') . '/' . $this->getNameInput() . 'Controller';
        $this->call('make:controller', [
            'name' => $controller,
            '--model' => $this->getModelClass(),
            '--api' => true
        ]);
    }

    /**
     * Create a filter for the model.
     *
     * @return void
     */
    protected function createFilter()
    {
        $this->call('build:filter', [
            'name' => $this->getNameInput() . 'Filter',
        ]);
    }

    /**
     * Create a transformer for the model.
     *
     * @return void
     */
    protected function createTransformer()
    {
        $this->call('build:transformer', [
            'name' => $this->getNameInput() . 'Transformer',
            '--model' => $this->getModelClass(),
        ]);
    }

    protected function getTransformerClass()
    {
        return str_replace('/', '\\', $this->rootNamespace()
            . config('suite.path.transformers', 'Transformers') . '\\'
            . $this->getNameInput() . 'Transformer');
    }

    protected function getFilterClass()
    {
        return str_replace('/', '\\', $this->rootNamespace()
            . config('suite.path.filters', 'Filters') . '\\'
            . $this->getNameInput() . 'Filter');
    }

    protected function getModelClass()
    {
        return $this->qualifyClass($this->getNameInput());
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

        $replace = $this->buildModelReplacements($replace);

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
        $transformer = $this->getTransformerClass();
        $filter = $this->getFilterClass();

        return array_merge($replace, [
            'DummyFilterNamespace' => $filter,
            'DummyFilterClass' => class_basename($filter),
            'DummyTransformerNamespace' => $transformer,
            'DummyTransformerClass' => class_basename($transformer),
        ]);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        $stub = '/stubs/model.';
        if ($this->option('filter')) {
            $stub .= 'filter.';
        }
        if ($this->option('transformer')) {
            $stub .= 'transformer.';
        }
        return __DIR__ . $stub . 'stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\\'.str_replace('/', '\\', config('suite.path.models', 'Models'));
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the model'],
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
            ['all', 'a', InputOption::VALUE_NONE, 'Generate a migration, factory, and resource controller for the model'],

            ['controller', 'c', InputOption::VALUE_NONE, 'Create a new controller for the model'],

            ['factory', null, InputOption::VALUE_NONE, 'Create a new factory for the model'],

            ['force', null, InputOption::VALUE_NONE, 'Create the class even if the model already exists'],

            ['migration', 'm', InputOption::VALUE_NONE, 'Create a new migration file for the model'],

            ['filter', 'f', InputOption::VALUE_NONE, 'Create a new filter for the model'],

            ['transformer', 't', InputOption::VALUE_NONE, 'Create a new transformer for the model'],
        ];
    }
}
