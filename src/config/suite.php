<?php

return [
    
    'model' => [

        /**
         * This is the default collection that the Eloquent Query Builder will
         * use to hydrate the models into. It can be overridden at model
         * level using the newCollection() method.
         * 
         * The default behaviour can be acheived using:
         *  Illuminate\Database\Eloquent\Collection::class
         */
        'collection'    => Larafun\Suite\Collection\PresentableCollection::class,

        /**
         * This is the default builder that the Eloquent model will use to
         * query the database. The Larafun Builder will pass the query
         * to a Queryable Collection so that it can be further used
         * (eg: for pagination)
         * The default behaviour can be acheived using:
         *  Illuminate\Database\Eloquent\Builder::class
         */
        'builder'       => Larafun\Suite\Builder::class,

        /**
         * The default Presenter that the Presentable Models will use
         * 
         * The default behaviour can be acheived using:
         *  Larafun\Suite\Presenters\PlainPresenter::class
         */
        'resource'      => Larafun\Suite\Resources\Resource::class,
    ],

    'collection' => [
        
        /**
         * The default Presenter that the Presentable Collections will use
         * 
         * The default behaviour can be acheived using:
         *  Larafun\Suite\Presenters\PlainPresenter::class
         */
        'resource' => Larafun\Suite\Resources\Resource::class,

        /**
         * The default Paginator that the Queryable Collections will use
         * 
         * The default behaviour can be acheived using:
         *  Larafun\Suite\Paginators\NullPaginator::class
         */
        'paginator' => Larafun\Suite\Paginators\QueryPaginator::class,
    ],

    /**
     * These paths are used by the generators and are relative to 
     * the project default values (eg: App, App/Http/Controllers, etc)
     * Update them to suit your project file structure
     */
    'path' => [
        'filters'       => 'Filters',
        'resources'     => 'Http/Resources',
        'models'        => 'Models',
        'controllers'   => 'Api',
    ]
];
