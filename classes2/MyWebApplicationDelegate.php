<?php

require_once(__DIR__.'/rerouter/RerouterInvocationPath.php');

//...

class AppUser_LoginDelegate
{
    //...

    function rerouteInvocationPath($invocationPath)
    {
        $rerouter = new RerouterInvocationPath();
        return $rerouter->handle($invocationPath);
    }
}
