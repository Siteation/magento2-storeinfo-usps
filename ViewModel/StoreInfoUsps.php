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

    private function objectToArray(object $data): array
    {
        $result = [];
        foreach ($data as $key => $value) {
            $result[$key] = (is_array($value) || is_object($value))
                ? $this->objectToArray($value)
                : $value;
        }
        return $result;
    }

    private function flattenArray(array $array): array
    {
        $flatArray = array_map(function($item) {
            if (!is_array($item) || !array_key_exists('content', $item)) {
                throw new \InvalidArgumentException('Each item in the array should be an array with a "content" key.');
            }
            return $item['content'];
        }, $array);

        return array_values($flatArray);
    }

    public function getStoreUsps(string $attribute): array
    {
        $path = sprintf('siteation_storeinfo_usps/%s/usps', $attribute);
        $value = $this->scopeConfig->getValue($path, ScopeInterface::SCOPE_STORE);

        if (is_string($value)) {
            return (array) $this->objectToArray(json_decode($value));
        }

        return (array) $value;
    }

    public function getHeaderUsps(): array
    {
        $usps = $this->getStoreUsps('header');
        return !empty($usps) ? $this->flattenArray($usps) : [];
    }

    public function getFooterUsps()
    {
        $usps = $this->getStoreUsps('footer');
        return !empty($usps) ? $this->flattenArray($usps) : [];
    }

    public function getCategoryUsps(): array
    {
        $usps = $this->getStoreUsps('category');
        return !empty($usps) ? $this->flattenArray($usps) : [];
    }

    public function getProductUsps(): array
    {
        $usps = $this->getStoreUsps('product');
        return !empty($usps) ? $this->flattenArray($usps) : [];
    }

    public function getCustom1Usps(): array
    {
        $usps = $this->getStoreUsps('custom_1');
        return !empty($usps) ? $this->flattenArray($usps) : [];
    }

    public function getCustom2Usps(): array
    {
        $usps = $this->getStoreUsps('custom_2');
        return !empty($usps) ? $this->flattenArray($usps) : [];
    }
}
