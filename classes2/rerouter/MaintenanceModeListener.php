<?php

require_once(__DIR__.'/RerouteListenerInterface.php');

class MaintenanceModeListener implements RerouteListenerInterface
{
    public function handle($invocationPath)
    {
        $maintenanceMode = constant(VirtualTourApp::MAINTENANCE_MODE_DEF_NAME);
        if (VirtualTourApp::maintenanceModeBreakthroughEnabled())
        {
            $maintenanceMode = VirtualTourApp::MAINTENANCE_MODE_DISABLED;
        }
        switch ($maintenanceMode) {
        case VirtualTourApp::MAINTENANCE_MODE_DISABLED:
            // normal operational mode; do nothing special
            break;
        case VirtualTourApp::MAINTENANCE_MODE_FULL:
            return "public/pages/maintenance_full";
            break;
        case VirtualTourApp::MAINTENANCE_MODE_READONLY:
            $uri = $_SERVER['REQUEST_URI'];
            if (
                    strncmp($uri, '/panel/',        7 ) === 0     // backend proper
                 or strncmp($uri, '/client/',       7 ) === 0     // internal admin
                 or strncmp($uri, '/admin/',        6 ) === 0     // internal admin
                 or strncmp($uri, '/login',         6 ) === 0     // backend login
                 or strncmp($uri, '/public/api',    11) === 0     // API
             )
            {
                return "public/pages/maintenance_readonly";
            }
            break;
        default:
            throw new Exception("Unexpected MAINTENANCE_MODE '{$maintenanceMode}'");
        }

        return NULL;
    }
}
