<?php
/**
 * Created by PhpStorm.
 * User: nikit
 * Date: 01.10.2018
 * Time: 12:05
 */

namespace esas\cmsgate\epos;


use esas\cmsgate\CmsConnectorWoo;
use esas\cmsgate\descriptors\ModuleDescriptor;
use esas\cmsgate\descriptors\VendorDescriptor;
use esas\cmsgate\descriptors\VersionDescriptor;
use esas\cmsgate\view\admin\AdminViewFields;
use esas\cmsgate\view\admin\ConfigFormWoo;
use esas\cmsgate\epos\view\client\CompletionPanelWoo;
use esas\cmsgate\wrappers\OrderWrapper;

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
        $managedFields = $this->getManagedFieldsFactory()->getManagedFieldsExcept(AdminViewFields::CONFIG_FORM_COMMON, [
            ConfigFieldsEpos::shopName(),
            ConfigFieldsEpos::paymentMethodNameWebpay(),
            ConfigFieldsEpos::paymentMethodDetailsWebpay(),
            ConfigFieldsEpos::useOrderNumber()]);
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

    /**
     * @param OrderWrapper $orderWrapper
     * @return string
     */
    function getUrlWebpay($orderWrapper)
    {
        $order = wc_get_order($orderWrapper->getOrderId());
        return $order->get_checkout_order_received_url();
    }

    public function createModuleDescriptor()
    {
        return new ModuleDescriptor(
            "epos",
            new VersionDescriptor("1.13.0", "2020-10-20"),
            "Прием платежей через ЕРИП (сервис EPOS)",
            "https://bitbucket.esas.by/projects/CG/repos/cmsgate-woocommerce-epos/browse",
            VendorDescriptor::esas(),
            "Выставление пользовательских счетов в ЕРИП"
        );
    }
}