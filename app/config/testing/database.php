<?php

return array(

    'default' => 'sqlite',

    'connections' => array(

        'sqlite' => array(
            'driver'   => 'sqlite',
            'database' => ':memory:',
            //'database' => __DIR__.'/../../database/testing.sqlite',
            'prefix'   => '',
        ),
    ),
);