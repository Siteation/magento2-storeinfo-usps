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

    // New helper method to process array data
    private function processArrayData(array $arrayData): array
    {
        $processedArray = [];
        foreach ($arrayData as $itemKey => $itemValue) {
            if (is_object($itemValue)) {
                $processedArray[$itemKey] = $this->objectToArray($itemValue); // Convert object elements
            } elseif (is_array($itemValue)) {
                $processedArray[$itemKey] = $this->processArrayData($itemValue); // Recurse for nested arrays
            } else {
                $processedArray[$itemKey] = $itemValue; // Scalar value
            }
        }
        return $processedArray;
    }

    // Modified objectToArray method
    private function objectToArray(object $data): array
    {
        $result = [];
        foreach ($data as $key => $value) { // $data is an object
            if (is_object($value)) {
                $result[$key] = $this->objectToArray($value); // Recursive call for object property
            } elseif (is_array($value)) {
                $result[$key] = $this->processArrayData($value); // Use helper for array property
            } else {
                $result[$key] = $value; // Scalar property
            }
        }
        return $result;
    }

    public function getStoreUsps(string $attribute): array
    {
        $path = sprintf('siteation_storeinfo_usps/%s/usps', $attribute);
        $configValue = $this->scopeConfig->getValue($path, ScopeInterface::SCOPE_STORE);

        $dataToProcess = null;

        if (is_string($configValue)) {
            if (trim($configValue) === '') { // Handle empty string config
                return [];
            }
            $decodedJson = json_decode($configValue);

            if ($decodedJson === null && json_last_error() !== JSON_ERROR_NONE) {
                // Optionally log error: error_log('JSON decode error in StoreInfoUsps: ' . json_last_error_msg() . ' for attribute ' . $attribute . ' with value ' . $configValue);
                return [];
            }
            $dataToProcess = $decodedJson;
        } else {
            $dataToProcess = $configValue;
        }

        if ($dataToProcess === null) {
            return [];
        }

        if (is_object($dataToProcess)) {
            return $this->objectToArray($dataToProcess);
        } elseif (is_array($dataToProcess)) {
            return $this->processArrayData($dataToProcess);
        } else {
            // For scalar values, mimic original behavior of casting to array.
            return (array)$dataToProcess;
        }
    }

    public function getHeaderUsps(): array
    {
        return $this->getStoreUsps('header');
    }

    public function getFooterUsps()
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
