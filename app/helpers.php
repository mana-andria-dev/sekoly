<?php
die('ee');
// Helper pour compatibilité avec l'ancien code
if (!function_exists('app')) {
    // Ne pas redéfinir app()
}

// Surcharge le comportement de app('tenant')
// Place ceci au début de ton fichier helpers.php
if (!function_exists('tenant_helper')) {
    function tenant_helper() {
        static $tenant = null;
        
        if ($tenant) {
            return $tenant;
        }
        
        if (function_exists('tenant') && tenant()) {
            $tenant = tenant();
            return $tenant;
        }
        
        return null;
    }
}

// Enregistre le binding au début de la requête
if (app()->bound('tenant')) {
    app()->instance('tenant', tenant_helper());
}