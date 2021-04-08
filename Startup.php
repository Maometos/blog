<?php

namespace Application;

use DevNet\System\Configuration\IConfiguration;
use DevNet\System\Dependency\IServiceCollection;
use DevNet\Web\Dispatcher\IApplicationBuilder;
use DevNet\Web\Extensions\ServiceCollectionExtensions;
use DevNet\Web\Extensions\ApplicationBuilderExtensions;
use DevNet\Entity\Providers\EntityOptionsExtensions;
use Application\Models\DbManager;
use Application\Models\User;

class Startup
{
    private IConfiguration $Configuration;

    public function __construct(IConfiguration $configuration)
    {
        $this->Configuration = $configuration;
    }

    public function configureServices(IServiceCollection $services)
    {
        $services->addMvc();

        $services->addAuthentication();

        $services->addAuthorisation();

        $services->addEntityContext(DbManager::class, function($options)
        {
            $options->useMysql("//root:root@127.0.0.1/blog");
        });

        $services->addIdentity(User::class);
    }

    public function configure(IApplicationBuilder $app)
    {
        $app->UseExceptionHandler();

        $app->useRouter();

        $app->useAuthentication();
        
        $app->useAuthorization();
        
        $app->useEndpoint(function($routes)
        {
            $routes->mapRoute("default", "{controller=Home}/{action=Index}/{id?}");
        });
    }
}
