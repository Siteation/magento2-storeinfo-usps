<?php declare(strict_types=1);

/**
 * @author Siteation (https://siteation.dev/)
 * @copyright Copyright 2023 Siteation (https://siteation.dev/)
 * @license MIT
 */

namespace Siteation\StoreInfoUsps\Block\Adminhtml\Form\Field;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\DataObject;
use Magento\Framework\View\Helper\SecureHtmlRenderer;
use Siteation\StoreInfoUsps\Block\Adminhtml\Form\Field\IconOptions;

class Usps extends AbstractFieldArray
{
    private $iconRenderer;

    public function __construct(
        Context $context,
        SecureHtmlRenderer $secureRenderer,
        array $data = []
    ) {
        $this->secureRenderer = $secureRenderer;
        parent::__construct($context, $data);
    }

    protected function _prepareToRender()
    {
        $this->addColumn('icon_name', [
            'label' => __('Icon'),
            'renderer' => $this->getIconRenderer()
        ]);

        $this->addColumn('content', [
            'label' => __('Content'),
            'class' => 'required-entry'
        ]);

        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add');
    }

    protected function _prepareArrayRow(DataObject $row): void
    {
        $options = [];

        $icon = $row->getIcon();
        if ($icon !== null) {
            $options['option_' . $this->getIconRenderer()->calcOptionHash($icon)] = 'selected';
        }

        $row->setData('option_extra_attrs', $options);
    }

    private function getIconRenderer()
    {
        if (!$this->iconRenderer) {
            $this->iconRenderer = $this->getLayout()->createBlock(
                IconOptions::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
        }
        return $this->iconRenderer;
    }

    protected function _getElementHtml(AbstractElement $element): string
    {
        $id = $element['html_id'];
        $html = parent::_getElementHtml($element);

        $scriptString = <<<script
            require(['jquery', 'Magento_Theme/js/sortable'], function ($) {
                $('#$id').sortable({ containment: 'parent', items: 'tbody tr', tolerance: 'pointer' });
            });
        script;

        $html .= $this->secureRenderer->renderTag('script', [], $scriptString, false);

        return $html;
    }
}
