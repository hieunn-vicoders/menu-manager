<?php

namespace VCComponent\Laravel\Menu\Test;


use Orchestra\Testbench\TestCase as OrchestraTestCase;
use VCComponent\Laravel\Menu\Providers\MenuComponentProvider;
use Dingo\Api\Provider\LaravelServiceProvider;


class TestCase extends OrchestraTestCase
{
    /**
     * Load package service provider
     *
     * @param  \Illuminate\Foundation\Application $app
     *
     * @return HaiCS\Laravel\Generator\Providers\GeneratorServiceProvider
     */
    protected function getPackageProviders($app)
    {
        return [
            LaravelServiceProvider::class,
            MenuComponentProvider::class,
        ];
    }

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->withFactories(__DIR__ . '/../tests/Stubs/Factory');
        $this->loadMigrationsFrom(__DIR__ . '/../src/database/migrations');
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.key', 'base64:TEQ1o2POo+3dUuWXamjwGSBx/fsso+viCCg9iFaXNUA=');
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
        $app['config']->set('menu.namespace', 'menu-management');
        $app['config']->set('menu.models', [
            'menu' => \VCComponent\Laravel\Menu\Entities\Menu::class,

        ]);
        $app['config']->set('menu.transformers', [
            'menu' => \VCComponent\Laravel\Menu\Transformers\MenuTransformer::class,
        ]);
        $app['config']->set('menu.auth_middleware', [
            'admin'    => [
                'middleware' => ''
            ],
            'frontend' => [
                'middleware' => ''
            ],
        ]);

        $app['config']->set('menu', [
            'page' => [
                'header'    => [
                    'label'    => 'header',
                    'position' => [
                        'position-1' => 'Vi tri 1',
                        'position-2' => 'Vi tri 2',
                    ],
                ],
                'footer' => [
                    'label'    => 'footer',
                    'position' => [
                        'position-1' => 'Vị trí 1',
                        'position-2' => 'Vị trí 2',
                    ],
                ],
            ],

        ]);
        $app['config']->set('api', [
            'standardsTree'      => 'x',
            'subtype'            => '',
            'version'            => 'v1',
            'prefix'             => 'api',
            'domain'             => null,
            'name'               => null,
            'conditionalRequest' => true,
            'strict'             => false,
            'debug'              => true,
            'errorFormat'        => [
                'message'     => ':message',
                'errors'      => ':errors',
                'code'        => ':code',
                'status_code' => ':status_code',
                'debug'       => ':debug',
            ],
            'middleware'         => [
            ],
            'auth'               => [
            ],
            'throttling'         => [
            ],
            'transformer'        => \Dingo\Api\Transformer\Adapter\Fractal::class,
            'defaultFormat'      => 'json',
            'formats'            => [
                'json' => \Dingo\Api\Http\Response\Format\Json::class,
            ],
            'formatsOptions'     => [
                'json' => [
                    'pretty_print' => false,
                    'indent_style' => 'space',
                    'indent_size'  => 2,
                ],
            ],
        ]);
    }
    public function assertValidation($response, $error_code, $field, $error_message)
    {
        $response->assertStatus($error_code);
        $response->assertJson([
            'message' => 'The given data was invalid.',
            'message' => '{"'.$field .'":["'.$error_message.'"]}',
        ]);
    }
}
