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
        'presenter' => Larafun\Suite\Presenters\Presenter::class,
    ],

    'collection' => [
        
        /**
         * The default Presenter that the Presentable Collections will use
         * 
         * The default behaviour can be acheived using:
         *  Larafun\Suite\Presenters\PlainPresenter::class
         */
        'presenter' => Larafun\Suite\Presenters\Presenter::class,

        /**
         * The default Paginator that the Queryable Collections will use
         * 
         * The default behaviour can be acheived using:
         *  Larafun\Suite\Paginators\NullPaginator::class
         */
        'paginator' => Larafun\Suite\Paginators\QueryPaginator::class,
    ]
];