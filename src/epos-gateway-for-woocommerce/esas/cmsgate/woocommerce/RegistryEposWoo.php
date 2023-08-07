<?php
/**
 * Created by PhpStorm.
 * User: nikit
 * Date: 01.10.2018
 * Time: 12:05
 */

namespace esas\cmsgate\woocommerce;


use esas\cmsgate\descriptors\ModuleDescriptor;
use esas\cmsgate\descriptors\VendorDescriptor;
use esas\cmsgate\descriptors\VersionDescriptor;
use esas\cmsgate\epos\ConfigFieldsEpos;
use esas\cmsgate\epos\hro\client\CompletionPanelEposHRO;
use esas\cmsgate\epos\PaysystemConnectorEpos;
use esas\cmsgate\epos\RegistryEpos;
use esas\cmsgate\hro\HROManager;
use esas\cmsgate\view\admin\AdminViewFields;
use esas\cmsgate\woocommerce\hro\client\CompletionPanelEposHRO_Woo;
use esas\cmsgate\woocommerce\view\admin\ConfigFormWoo;
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

    public function init() {
        parent::init();
//        $this->registerServicesFromProvider(new ServiceProviderWoo());
        HROManager::fromRegistry()->addImplementation(CompletionPanelEposHRO::class, CompletionPanelEposHRO_Woo::class);
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
            new VersionDescriptor("2.0.3", "2023-08-01"),
            "Прием платежей через ЕРИП (сервис EPOS)",
            "https://github.com/esasby/cmsgate-woocommerce-epos",
            VendorDescriptor::esas(),
            "Выставление пользовательских счетов в ЕРИП"
        );
    }
}