<?php
/**
 * Created by PhpStorm.
 * User: nikit
 * Date: 01.10.2018
 * Time: 12:05
 */

namespace esas\cmsgate\epos;


use esas\cmsgate\CmsConnectorWoo;
use esas\cmsgate\view\admin\AdminViewFields;
use esas\cmsgate\view\admin\ConfigFormWoo;
use esas\cmsgate\epos\view\client\CompletionPanelWoo;

class RegistryEposWoo extends RegistryEpos
{
    /**
     * RegistryHutkigroshWoo constructor.
     */
    public function __construct()
    {
        $this->cmsConnector = new CmsConnectorWoo();
        $this->paysystemConnector = new PaysystemConnectorEpos();
    }

    /**
     * Переопделение для упрощения типизации
     * @return RegistryEposWoo
     */
    public static function getRegistry()
    {
        return parent::getRegistry();
    }

    public function createConfigForm()
    {
        $managedFields = $this->getManagedFieldsFactory()->getManagedFieldsExcept(AdminViewFields::CONFIG_FORM_COMMON, [ConfigFieldsEpos::shopName()]);
        $configForm = new ConfigFormWoo(
            AdminViewFields::CONFIG_FORM_COMMON,
            $managedFields
        );
        $configForm->addCmsManagedFields();
        return $configForm;
    }

    public function getCompletionPanel($orderWrapper)
    {
        $completionPanel = new CompletionPanelWoo($orderWrapper);
        return $completionPanel;
    }

    function getUrlWebpay($orderId)
    {
        $order = wc_get_order($orderId);
        return $order->get_checkout_order_received_url();
    }
}