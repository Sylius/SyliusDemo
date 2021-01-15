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

namespace App\MediaProvider;

use BitBag\SyliusCmsPlugin\Entity\MediaInterface;
use BitBag\SyliusCmsPlugin\MediaProvider\ProviderInterface;
use BitBag\SyliusCmsPlugin\Uploader\MediaUploaderInterface;
use Twig\Environment;

final class GenericProvider implements ProviderInterface
{
    /** @var MediaUploaderInterface */
    private $uploader;

    /** @var Environment */
    private $twig;

    /** @var string */
    private $template;

    /** @var string */
    private $pathPrefix;

    public function __construct(
        MediaUploaderInterface $uploader,
        Environment $twig,
        string $template,
        string $pathPrefix
    ) {
        $this->uploader = $uploader;
        $this->twig = $twig;
        $this->template = $template;
        $this->pathPrefix = $pathPrefix;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function render(MediaInterface $media, ?string $template = null, array $options = []): string
    {
        return $this->twig->render($template ?? $this->template, array_merge(['media' => $media], $options));
    }

    public function upload(MediaInterface $media): void
    {
        $this->uploader->upload($media, $this->pathPrefix);
    }
}
