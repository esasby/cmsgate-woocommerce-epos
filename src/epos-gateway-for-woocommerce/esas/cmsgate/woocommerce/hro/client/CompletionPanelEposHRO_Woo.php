<?php
/**
 * Created by IntelliJ IDEA.
 * User: nikit
 * Date: 24.06.2019
 * Time: 14:11
 */

namespace esas\cmsgate\woocommerce\hro\client;

use esas\cmsgate\epos\hro\client\CompletionPanelEposHRO_v1;
use esas\cmsgate\hro\panels\MessagesPanelHROFactory;
use esas\cmsgate\utils\htmlbuilder\Attributes as attribute;
use esas\cmsgate\utils\htmlbuilder\Elements as element;

class CompletionPanelEposHRO_Woo extends CompletionPanelEposHRO_v1
{
    public static function builder() {
        return new CompletionPanelEposHRO_Woo();
    }

    public function build()
    {
        if (!$this->orderCanBePayed) {
            return MessagesPanelHROFactory::findBuilder()->build();
        }
        $completionPanel = element::content(
            element::div(
                attribute::id("completion-text"),
                attribute::clazz($this->getCssClass4CompletionTextDiv()),
                element::content($this->completionText)
            ),
            element::div(
                attribute::id("payment"),
                attribute::clazz($this->getCssClass4TabsGroup()),
                element::ul(
                    attribute::clazz("wc_payment_methods payment_methods methods"),
                    $this->addTabs()
                )
            ),
            $this->addCss() // CSS заданный администратором в настройках модуля
        );
        return $completionPanel;
    }

    public function elementTab($key, $header, $body)
    {
        return
            element::li(
                attribute::id("tab-" . $key),
                attribute::clazz("tab wc_payment_method"),
                element::input(
                    attribute::id("input-" . $key),
                    attribute::type("radio"),
                    attribute::name("tabs2"),
                    attribute::clazz("input-radio"),
                    ($this->isOnlyOneTabEnabled() ? attribute::style("display: none") : ""),
                    attribute::checked($this->isTabChecked($key))
                ),
                element::label(
                    attribute::forr("input-" . $key),
                    attribute::clazz($this->getCssClass4TabHeaderLabel()),
                    element::content($header)
                ),
                $this->elementTabBody($key, $body)
            )->__toString();
    }

    public function getCssClass4TabBodyContent()
    {
        return "payment_box";
    }


    public function getCssClass4MsgSuccess()
    {
        return "woocommerce-message";
    }

    public function getCssClass4MsgUnsuccess()
    {
        return "woocommerce-error";
    }

    public function getCssClass4Button()
    {
        return "button";
    }

    public function getModuleCSSFilePath()
    {
        return dirname(__FILE__) . "/liCorrection.css";
    }

}