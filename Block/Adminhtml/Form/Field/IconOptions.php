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
            [ 'label' => 'Check', 'value' => 'check' ],
            [ 'label' => 'Shipping', 'value' => 'shipping' ],
            [ 'label' => 'Clock', 'value' => 'clock' ],
            [ 'label' => 'Percent', 'value' => 'percent' ],
            [ 'label' => 'Messages', 'value' => 'messages' ],
            [ 'label' => 'Cart', 'value' => 'cart' ],
            [ 'label' => 'Package', 'value' => 'package' ],
            [ 'label' => 'Return', 'value' => 'return' ],
            [ 'label' => 'Bell', 'value' => 'bell' ],
            [ 'label' => 'Headset', 'value' => 'headset' ],
            [ 'label' => 'Shield', 'value' => 'shield' ],
            [ 'label' => 'Custom', 'value' => 'custom' ]
        ];
    }
}
