Larafun Suite provides convenient Generator artisan commands to easily build new Models, Filters or Resources.

## Models

Build new Models with their corresponding Controller, Filter, Resource, migration and factory.

`php artisan build:model Book -a`

Build a new Model with a Filter and Resource.

`php artisan build:model Book -fr`

## Resources

Build a new Resource.

`php artisan build:resource BookResource`

Build a new Resource for a given model.

`php artisan build:resource BookResource -m Book`

## Filters

Build a new Filter.

`php artisan build:filter BookFilter`

## Default locations

You can change the default locations for the generated files in the `config/suite.php` file after publishing it: `php artisan vendor:publish --provider=Larafun\\Suite\\ServiceProvider`
## Help

You can see all the available options using the `help` command:

`php artisan help build:model`

