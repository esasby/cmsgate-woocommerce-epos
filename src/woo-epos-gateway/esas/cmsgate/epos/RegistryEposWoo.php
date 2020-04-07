<?php
/**
 * Created by PhpStorm.
 * User: nikit
 * Date: 01.10.2018
 * Time: 12:05
 */

namespace esas\cmsgate\epos;


use esas\cmsgate\lang\LocaleLoaderWoo;
use esas\cmsgate\epos\lang\TranslatorEpos;
use esas\cmsgate\view\admin\AdminViewFields;
use esas\cmsgate\view\admin\ConfigFormWoo;
use esas\cmsgate\epos\view\admin\ManagedFieldsEpos;
use esas\cmsgate\wrappers\OrderWrapperFactoryWoo;
use esas\cmsgate\epos\view\client\CompletionPanelWoo;
use esas\cmsgate\epos\wrappers\ConfigWrapperEposWoo;
use WP_Post;

class RegistryEposWoo extends RegistryEpos
{
    /**
     * Переопделение для упрощения типизации
     * @return RegistryEposWoo
     */
    public static function getRegistry()
    {
        return parent::getRegistry();
    }

    /**
     * @return TranslatorEpos
     */
    public function createTranslator()
    {
        $localeLoader = new LocaleLoaderWoo();
        return new TranslatorEpos($localeLoader);
    }

    /**
     * @return OrderWrapperFactoryWoo
     */
    public function createOrderWrapperFactory()
    {
        return new OrderWrapperFactoryWoo();
    }

    public function createConfigForm()
    {
        $managedFields = new ManagedFieldsEpos();
        $managedFields->addAllExcept([ConfigFieldsEpos::shopName()]);
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

    public function createConfigWrapper()
    {
        return new ConfigWrapperEposWoo();
    }

}