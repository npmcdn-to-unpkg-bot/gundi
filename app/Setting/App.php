<?php

use Core\Library\Gundi\Gundi;
use Illuminate\Database\Capsule\Manager as Capsule;

/**
 * bind system services
 */
$oGundi = new Gundi();
$oContainer = new Illuminate\Container\Container();

//Dependencies
$oContainer->singleton([Core\Library\Setting\Setting::class => 'Setting']);
$oContainer->singleton([Core\Contract\Request\IRequest::class => 'Request'], Core\Library\Request\Request::class);
$oContainer->singleton([Core\Library\Util\Url::class => 'Url']);
$oContainer->singleton([Core\Library\Theme\Theme::class => 'Theme']);
$oContainer->singleton([Core\Library\View\Html\Extension\Asset::class => 'Asset']);
$oContainer->singleton([Core\Library\Token\Token::class => 'Token']);
$oContainer->singleton([Core\Library\Session\Session::class => 'Session']);
$oContainer->singleton([Core\Library\Dispatch\Dispatch::class => 'Dispatch']);
$oContainer->singleton([Core\Library\View\Html\Extension\Block::class => 'Block']);
$oContainer->singleton([Core\Library\Router\Router::class => 'Router']);
$oContainer->singleton([Core\Library\View\Html\Extension\URI::class => 'Uri']);
$oContainer->singleton([Core\Library\View\Html\Extension\File::class => 'File']);
$oContainer->singleton(Core\Contract\View\IViewFactory::class, Core\Library\View\Factory::class);
$oContainer->singleton([Core\Library\Error\Error::class => 'Error']);
$oContainer->singleton([\Core\Library\Event\Dispatcher::class => 'EventDispatcher']);
$oContainer->instance(Illuminate\Container\Container::class, $oContainer);

/**
 * load system extensions for view
 */
$oContainer['EventDispatcher']->listen(\Core\Library\View\Events::HTML_CREATED, function (\Core\Library\View\Html\View $oView) use ($oContainer) {
    $aExtension = [
        $oContainer['Uri'],
        $oContainer['Token'],
        $oContainer['Asset'],
        $oContainer['File'],
        $oContainer['Block'],//this extension must be register last else will be other extension not available
    ];

    foreach ($aExtension as &$oExtension) {
        $oView->loadExtension($oExtension);
    }
});

/**
 * bind DB connection
 */

$oCapsule = new Capsule($oContainer);

$oCapsule->addConnection([
    'driver' => $oContainer['Setting']->getParam('db.driver'),
    'host' => $oContainer['Setting']->getParam('db.host'),
    'database' => $oContainer['Setting']->getParam('db.name'),
    'username' => $oContainer['Setting']->getParam('db.user'),
    'password' => $oContainer['Setting']->getParam('db.pass'),
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => ''
]);

$oCapsule->setAsGlobal();
$oCapsule->bootEloquent();

$oContainer->instance(['\Illuminate\Database\Connection' => 'Connection'], $oCapsule);

$oContainer['Router']->setBasePath($oContainer['Setting']->getParam('core.folder') . GUNDI_INDEX_FILE);
$oGundi->setDIContainer($oContainer);