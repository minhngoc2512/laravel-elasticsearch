<?php

namespace Ngocnm\ElasticQuery;

use Illuminate\Support\ServiceProvider;
use Elasticsearch\ClientBuilder;

class ElasticsearchServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
//        $this->mer
        $this->publishes([
            __DIR__.'/config/elasticsearch.php' => config_path('elasticsearch.php'),
        ],'config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/elasticsearch.php', 'elasticsearch'
        );
        if(config('debug',false)===true&&class_exists('Barryvdh\Debugbar\Facade')){
            \Barryvdh\Debugbar\Facade::addCollector(new \DebugBar\DataCollector\MessagesCollector('elasticsearch'));
        }
//        if(!defined('ELASTICSEARCH_LOG_DEBUGBAR')) define('ELASTICSEARCH_LOG_DEBUGBAR',false);
//        define('ELASTICSEARCH_INDEX_PREFIX',env('ELASTIC_INDEX_PREFIX',''));
        $this->app->singleton('elastic_query', function ($app) {
            $hosts = config('elasticsearch.host');
            $hosts_config = [
                'port'=>config('elasticsearch.post'),
                'scheme'=>config('elasticsearch.scheme'),
                'host'=>$hosts
            ];
            if(!empty(config('elasticsearch.password'))) $hosts_config['pass'] = config('elasticsearch.password');
            if(!empty(config('elasticsearch.user'))) $hosts_config['user'] = config('elasticsearch.user');
            if(!empty(config('elasticsearch.path'))) $hosts_config['path'] = config('elasticsearch.path');
            if(strpos($hosts,',')!==false){
                $hosts = explode(',',$hosts);
            }
            if(is_array($hosts)){
                $hosts = array_map(function ($host)use($hosts_config){
                    $hosts_config['host'] = $host;
                    return $hosts_config;
                },$hosts);
            }else{
                $hosts = [$hosts_config];
            }
            $client = ClientBuilder::create()->setHosts($hosts)->build();
            return  $client;
        });
    }
}
