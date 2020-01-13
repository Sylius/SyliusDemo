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

use BitBag\SyliusCmsPlugin\Entity\BlockInterface;
use BitBag\SyliusCmsPlugin\Resolver\BlockResourceResolverInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class ReturnCmsBlockExtension extends AbstractExtension
{
    /** @var BlockResourceResolverInterface */
    private $blockResourceResolver;

    public function __construct(BlockResourceResolverInterface $blockResourceResolver)
    {
        $this->blockResourceResolver = $blockResourceResolver;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('sylius_cms_return_block', [$this, 'returnBlock']),
        ];
    }

    public function returnBlock(string $code): BlockInterface
    {
        /** @var BlockInterface $block */
        $block = $this->blockResourceResolver->findOrLog($code);

        return $block;

    }
}
