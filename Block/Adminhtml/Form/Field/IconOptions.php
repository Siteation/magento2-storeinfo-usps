<?php declare(strict_types=1);

/**
 * @author Siteation (https://siteation.dev/)
 * @copyright Copyright 2023 Siteation (https://siteation.dev/)
 * @license MIT
 */

namespace Siteation\StoreInfoUsps\Block\Adminhtml\Form\Field;

use Magento\Framework\View\Element\Html\Select;

class IconOptions extends Select
{
    public function setInputName($value)
    {
        return $this->setName($value);
    }

    public function setInputId($value)
    {
        return $this->setId($value);
    }

    public function _toHtml(): string
    {
        if (!$this->getOptions()) {
            $this->setOptions($this->getSourceOptions());
        }
        return parent::_toHtml();
    }

    private function getSourceOptions(): array
    {
        return [
            [ 'label' => __('Check'), 'value' => 'check' ],
            [ 'label' => __('Shipping'), 'value' => 'shipping' ],
            [ 'label' => __('Clock'), 'value' => 'clock' ],
            [ 'label' => __('Percent'), 'value' => 'percent' ],
            [ 'label' => __('Messages'), 'value' => 'messages' ],
            [ 'label' => __('Cart'), 'value' => 'cart' ],
            [ 'label' => __('Package'), 'value' => 'package' ],
            [ 'label' => __('Return'), 'value' => 'return' ],
            [ 'label' => __('Bell'), 'value' => 'bell' ],
            [ 'label' => __('Headset'), 'value' => 'headset' ],
            [ 'label' => __('Shield'), 'value' => 'shield' ],
            [ 'label' => __('Custom'), 'value' => 'custom' ]
        ];
    }
}
