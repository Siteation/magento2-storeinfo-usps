<?php

declare(strict_types=1);

namespace Siteation\StoreInfoUsps\Test\Unit\ViewModel;

use Magento\Framework\App\Config\ScopeConfigInterface;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Siteation\StoreInfoUsps\ViewModel\StoreInfoUsps;

class StoreInfoUspsTest extends TestCase
{
    private ScopeConfigInterface&Stub $scopeConfig;
    private StoreInfoUsps $viewModel;

    protected function setUp(): void
    {
        $this->scopeConfig = $this->createStub(ScopeConfigInterface::class);
        $this->viewModel = $this->viewModelFor($this->scopeConfig);
    }

    private function viewModelFor(ScopeConfigInterface $config): StoreInfoUsps
    {
        return new StoreInfoUsps($config);
    }

    public function testJsonConfigIsDecodedToNestedArrays(): void
    {
        $config = $this->createMock(ScopeConfigInterface::class);
        $config->expects($this->once())
            ->method('getValue')
            ->with('siteation_storeinfo_usps/header/usps')
            ->willReturn('{"_1":{"icon_name":"check","content":"Free returns"}}');

        $this->assertSame(
            ['_1' => ['icon_name' => 'check', 'content' => 'Free returns']],
            $this->viewModelFor($config)->getStoreUsps('header')
        );
    }

    public function testArrayConfigIsReturnedAsIs(): void
    {
        $this->scopeConfig->method('getValue')->willReturn([['content' => 'x']]);

        $this->assertSame([['content' => 'x']], $this->viewModel->getStoreUsps('footer'));
    }

    public function testMissingConfigIsAnEmptyArray(): void
    {
        $this->scopeConfig->method('getValue')->willReturn(null);

        $this->assertSame([], $this->viewModel->getStoreUsps('cart'));
    }

    public function testPlacementAccessorsReadTheirOwnPath(): void
    {
        $paths = [];
        $this->scopeConfig->method('getValue')->willReturnCallback(
            function (string $path) use (&$paths) {
                $paths[] = $path;
                return null;
            }
        );

        $this->viewModel->getHeaderUsps();
        $this->viewModel->getFooterUsps();
        $this->viewModel->getCategoryUsps();
        $this->viewModel->getProductUsps();
        $this->viewModel->getCartUsps();
        $this->viewModel->getCustom1Usps();
        $this->viewModel->getCustom2Usps();

        $this->assertSame([
            'siteation_storeinfo_usps/header/usps',
            'siteation_storeinfo_usps/footer/usps',
            'siteation_storeinfo_usps/category/usps',
            'siteation_storeinfo_usps/product/usps',
            'siteation_storeinfo_usps/cart/usps',
            'siteation_storeinfo_usps/custom_1/usps',
            'siteation_storeinfo_usps/custom_2/usps',
        ], $paths);
    }
}
