<?php
if (!class_exists("esas\cmsgate\CmsPlugin"))
    require_once(dirname(__FILE__) . '/vendor/esas/cmsgate-core/src/esas/cmsgate/CmsPlugin.php');

use esas\cmsgate\CmsPlugin;
use esas\cmsgate\epos\RegistryEposWoo;


(new CmsPlugin(dirname(__FILE__) . '/vendor', dirname(__FILE__)))
    ->setRegistry(new RegistryEposWoo())
    ->init();
