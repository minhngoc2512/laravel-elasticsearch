<?php
return[
    'debugbar' => env('ELASTICSEARCH_LOG_DEBUGBAR',false),
    'prefix' => env('ELASTICSEARCH_INDEX_PREFIX',''),
    'host' => env('ELASTIC_HOST','localhost'),
    'port' => env('ELASTIC_PORT',9200),
    'user' => env('ELASTIC_USERNAME',null),
    'password' => env('ELASTIC_PASSWORD',null),
    'scheme' => env('ELASTIC_SCHEME','http'),
    'path' => env('ELASTIC_PATH',null)
];
