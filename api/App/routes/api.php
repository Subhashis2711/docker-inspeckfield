<?php
    
    require_once __DIR__.'/../Controller/InsuredInfoMapController.php';
    require_once __DIR__.'/../Controller/TokenController.php';
    require_once __DIR__.'/../Controller/UserController.php';
    require_once __DIR__.'/../Controller/InspectionController.php';

    $base  = dirname($_SERVER['PHP_SELF']);

    if(ltrim($base, '/')){ 

        $_SERVER['REQUEST_URI'] = substr($_SERVER['REQUEST_URI'], strlen($base));
    }

    $Klein = new \Klein\Klein();

    //API endpoints
    $Klein->respond('POST', '/map-insured-info', [ new InsuredInfoMapController(), 'mapInsuredInfo' ]);
    $Klein->respond('POST', '/get-token', [ new TokenController(), 'getToken' ]);
    $Klein->respond('POST', '/update-status', [ new UserController(), 'updateUserStatus' ]);
    $Klein->respond('POST', '/update-user', [ new UserController(), 'updateUserInfo' ]);
    $Klein->respond('POST', '/update-inspection-status', [ new InspectionController(), 'updateInspectionStatus' ]);

    $Klein->dispatch();
?>
