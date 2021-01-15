<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Twig;

use BitBag\SyliusCmsPlugin\Entity\PageInterface;
use BitBag\SyliusCmsPlugin\Repository\PageRepositoryInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;

final class RenderProductPagesExtension extends AbstractExtension
{
    /** @var PageRepositoryInterface */
    private $pageRepository;

    /** @var ChannelContextInterface */
    private $channelContext;

    /** @var Environment */
    private $twig;

    public function __construct(
        PageRepositoryInterface $pageRepository,
        ChannelContextInterface $channelContext,
        Environment $twig
    ) {
        $this->pageRepository = $pageRepository;
        $this->channelContext = $channelContext;
        $this->twig = $twig;
    }

    public function getFunctions(): array
    {
        return [
            new \Twig_Function('bitbag_cms_render_product_pages', [$this, 'renderProductPages'], ['is_safe' => ['html']]),
        ];
    }

    public function renderProductPages(ProductInterface $product, string $sectionCode = null): string
    {
        $channelCode = $this->channelContext->getChannel()->getCode();

        if (null !== $sectionCode) {
            $pages = $this->pageRepository->findByProductAndSectionCode($product, $sectionCode, $channelCode);
        } else {
            $pages = $this->pageRepository->findByProduct($product, $channelCode);
        }

        $data = $this->sortBySections($pages);

        return $this->twig->render('@BitBagSyliusCmsPlugin/Shop/Product/_pagesBySection.html.twig', [
            'data' => $data,
        ]);
    }

    private function sortBySections(array $pages): array
    {
        $result = [];

        /** @var PageInterface $page */
        foreach ($pages as $page) {
            foreach ($page->getSections() as $section) {
                $sectionCode = $section->getCode();
                if (!array_key_exists($sectionCode, $result)) {
                    $result[$sectionCode] = [];
                    $result[$sectionCode]['section'] = $section;
                }

                $result[$sectionCode][] = $page;
            }
        }

        return $result;
    }
}
