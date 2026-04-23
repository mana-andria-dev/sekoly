<?php

declare(strict_types=1);

use Stancl\Tenancy\Database\Models\Domain;
use Stancl\Tenancy\Database\Models\Tenant;

// config/tenancy.php

return [
    'tenant_model' => \App\Models\Tenant::class,
    
    'id_generator' => Stancl\Tenancy\UUIDGenerator::class,

    // config/tenancy.php
    'database' => [
        'central_connection' => 'central',
        'tenant_connection' => 'tenant',
        'auto_create_tenant_database' => true,
        'auto_create_tenant_database_if_not_exists' => true,
        'auto_migrate_tenant_database' => false,
        'auto_delete_tenant_database' => false,
        'migrate_refresh_seed_on_tenancy_init' => false,
        'suffix' => null,
        'prefix' => 'tenant_',  // ← Changement ici : 'tenant_' au lieu de 'sekoly_'
    ], 

    'identification' => [
        'resolvers' => [
            'domain' => Stancl\Tenancy\Resolvers\DomainTenantResolver::class,
        ],
        'resolver' => 'domain',
    ],
    
    'bootstrappers' => [
        Stancl\Tenancy\Bootstrappers\DatabaseTenancyBootstrapper::class,
        Stancl\Tenancy\Bootstrappers\CacheTenancyBootstrapper::class,
        Stancl\Tenancy\Bootstrappers\FilesystemTenancyBootstrapper::class,
        Stancl\Tenancy\Bootstrappers\QueueTenancyBootstrapper::class,
        // Ajoute Redis si tu l'utilises
        // Stancl\Tenancy\Bootstrappers\RedisTenancyBootstrapper::class,
    ],
    
    'features' => [
        Stancl\Tenancy\Features\UserImpersonation::class,
        Stancl\Tenancy\Features\TelescopeTags::class,
        Stancl\Tenancy\Features\UniversalRoutes::class,
    ],
];
