<?php declare(strict_types=1);

/**
 * @author Siteation (https://siteation.dev/)
 * @copyright Copyright 2023 Siteation (https://siteation.dev/)
 * @license MIT
 */

namespace Siteation\StoreInfoUsps\ViewModel;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Store\Model\ScopeInterface;

class StoreInfoUsps implements ArgumentInterface
{
    protected $scopeConfig;

    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    private function objectToArray($data): array
    {
        $result = [];

        foreach ($data as $key => $value) {
            if (is_object($value) || is_array($value)) {
                $result[$key] = $this->objectToArray($value);
            } else {
                $result[$key] = $value;
            }
        }
        return $result;
    }

    public function getStoreUsps(string $attribute): array
    {
        $path = sprintf('siteation_storeinfo_usps/%s/usps', $attribute);
        $configValue = $this->scopeConfig->getValue($path, ScopeInterface::SCOPE_STORE);

        if (empty($configValue)) {
            return [];
        }

        $decodedJson = json_decode((string)$configValue);

        if ($decodedJson === null && json_last_error() !== JSON_ERROR_NONE) {
            if (is_array($configValue) || is_object($configValue)) {
                return $this->objectToArray($configValue);
            }
            return [];
        }

        if ($decodedJson === null) {
            return [];
        }

        return $this->objectToArray($decodedJson);
    }

    public function getHeaderUsps(): array
    {
        return $this->getStoreUsps('header');
    }

    public function getFooterUsps(): array
    {
        return $this->getStoreUsps('footer');
    }

    public function getCategoryUsps(): array
    {
        return $this->getStoreUsps('category');
    }

    public function getProductUsps(): array
    {
        return $this->getStoreUsps('product');
    }

    public function getCustom1Usps(): array
    {
        return $this->getStoreUsps('custom_1');
    }

    public function getCustom2Usps(): array
    {
        return $this->getStoreUsps('custom_2');
    }
}
