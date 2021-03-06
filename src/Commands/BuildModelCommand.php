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
            $this->input->setOption('resource', true);
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

        if ($this->option('resource')) {
            $this->createResource();
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
        $controller = $this->getNameInput() . 'Controller';

        $options = [
            'name' => $controller,
            '--model' => $this->getModelClass(),
        ];
        
        if ($this->option('filter')) {
            $options = array_merge($options, [
                '--filter'  => $this->getFilterClass()
            ]);
        }

        $this->call('build:controller', $options);
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
     * Create a resource for the model.
     *
     * @return void
     */
    protected function createResource()
    {
        $this->call('build:resource', [
            'name' => $this->getNameInput() . 'Resource',
            '--model' => $this->getModelClass(),
        ]);
    }

    protected function getResourceClass()
    {
        return str_replace('/', '\\', $this->rootNamespace()
            . config('suite.path.resources', 'Resources') . '\\'
            . $this->getNameInput() . 'Resource');
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
        $resource = $this->getResourceClass();
        $filter = $this->getFilterClass();

        return array_merge($replace, [
            'DummyFilterNamespace' => $filter,
            'DummyFilterClass' => class_basename($filter),
            'DummyResourceNamespace' => $resource,
            'DummyResourceClass' => class_basename($resource),
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
        if ($this->option('resource')) {
            $stub .= 'resource.';
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

            ['resource', 'r', InputOption::VALUE_NONE, 'Create a new resource for the model'],
        ];
    }
}
