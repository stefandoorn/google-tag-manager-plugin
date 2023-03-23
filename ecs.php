<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\ControlStructure\TrailingCommaInMultilineFixer;
use PhpCsFixer\Fixer\FunctionNotation\NativeFunctionInvocationFixer;
use PhpCsFixer\Fixer\ClassNotation\VisibilityRequiredFixer;
use PhpCsFixer\Fixer\Operator\BinaryOperatorSpacesFixer;

return static function (ECSConfig $config): void {
    $config->import('vendor/sylius-labs/coding-standard/ecs.php');

    $config->skip([
        VisibilityRequiredFixer::class => ['*Spec.php'],
        'tests/Application/*',
    ]);

    $config->ruleWithConfiguration(BinaryOperatorSpacesFixer::class, []);

    $services = $config->services();
    $services->set(
        NativeFunctionInvocationFixer::class
    )->call('configure', [['include' => ['@all'], 'scope' => 'all', 'strict' => \true]]);
    $services->set(
        TrailingCommaInMultilineFixer::class
    )->call('configure', [['elements' => ['arrays']]]);
};
