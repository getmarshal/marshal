<?php

declare(strict_types=1);

namespace Marshal\Utils\Trait;

use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;

trait CommandInputValidatorTrait
{
    private function validateInput(InputInterface $input): void
    {
        // validate arguments
        $input->validate();

        // validate options
        $definition = $this->getDefinition();
        \assert($definition instanceof InputDefinition);

        // filter missing required options
        $missingOptions = array_filter(
            array_keys($definition->getOptions()),
            fn ($option): bool => (! \array_key_exists($option, $input->getOptions()) || null === $input->getOption($option)) && $definition->getOption($option)->isValueRequired()
        );

        if (\count($missingOptions) > 0) {
            throw new \RuntimeException(\sprintf('Not enough required options (missing: "%s").', implode(', ', $missingOptions)));
        }
    }
}
