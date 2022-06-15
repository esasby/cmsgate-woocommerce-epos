<?php

use esas\cmsgate\epos\controllers\ControllerEposAddInvoice;
use esas\cmsgate\epos\controllers\ControllerEposCallback;
use esas\cmsgate\epos\controllers\ControllerEposCompletionPanel;
use esas\cmsgate\utils\Logger;
use esas\cmsgate\woocommerce\WcCmsgate;
use esas\cmsgate\wrappers\OrderWrapper;

require_once('init.php');


if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class WcCmsgateEpos extends WcCmsgate
{
    function __construct()
    {
        parent::__construct();
        add_action('woocommerce_api_gateway_epos', array($this, 'epos_callback'));
        add_filter('woocommerce_thankyou_' . $this->id, array($this, 'epos_thankyou_text'));
    }

    // Submit payment and handle response

    /**
     * @param OrderWrapper $orderWrapper
     * @throws Throwable
     */
    protected function process_payment_safe($orderWrapper)
    {
        if (empty($orderWrapper->getExtId())) {
            $controller = new ControllerEposAddInvoice();
            $controller->process($orderWrapper);
        }
    }

    function epos_thankyou_text($order_id)
    {
        try {
            $controller = new ControllerEposCompletionPanel();
            $completionPanel = $controller->process($order_id);
            $completionPanel->render();
        } catch (Throwable $e) {
            Logger::getLogger("payment")->error("Exception:", $e);
        }
    }

    public function epos_callback()
    {
        try {
            $controller = new ControllerEposCallback();
            $controller->process();
        } catch (Throwable $e) {
            Logger::getLogger("callback")->error("Exception:", $e);
        }
    }
}