<?php declare(strict_types=1);

namespace Siteation\StoreUsps\ViewModel;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class StoreUsps implements ArgumentInterface
{
    protected $scopeConfig;

    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Get store information
     *
     * @param string $attribute
     * @return mixed
     */
    public function getStoreUsps(string $attribute)
    {
        $path = sprintf('general/store_usps/%s', $attribute);
        return $this->scopeConfig->getValue($path, ScopeInterface::SCOPE_STORE);
    }

    public function getUsp(int $id): string
    {
        return (string) $this->getStoreUsps('usp_' . $id);
    }

    public function getUsps(): array
    {
        $usps = [];

        // get each usps except if it is empty
        for ($i = 1; $i <= 5; $i++) {
            $usp = $this->getUsp($i);
            if (!empty($usp)) {
                $usps[] = $usp;
            }
        }

        return $usps;
    }
}
