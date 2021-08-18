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

namespace App\Factory;

use Sylius\Bundle\CoreBundle\Fixture\Factory\AbstractExampleFactory;
use Sylius\Bundle\CoreBundle\Fixture\Factory\ExampleFactoryInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class OrderExampleFactory extends AbstractExampleFactory implements ExampleFactoryInterface
{
    /** @var ExampleFactoryInterface|AbstractExampleFactory */
    private $decoratedOrderExampleFactory;

    private Filesystem $filesystem;

    private string $invoicesDir;

    public function __construct(
        ExampleFactoryInterface $decoratedOrderExampleFactory,
        Filesystem $filesystem,
        string $invoicesDir
    ) {
        $this->decoratedOrderExampleFactory = $decoratedOrderExampleFactory;
        $this->filesystem = $filesystem;
        $this->invoicesDir = $invoicesDir;
    }

    public function create(array $options = [])
    {
        if ($this->filesystem->exists($this->invoicesDir)) {
            $this->filesystem->remove([$this->invoicesDir]);
        }

        return $this->decoratedOrderExampleFactory->create($options);
    }

    protected function configureOptions(OptionsResolver $resolver): void
    {
        $this->decoratedOrderExampleFactory->configureOptions($resolver);
    }
}
