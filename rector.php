<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\SetList;
use Rector\Set\ValueObject\LevelSetList;

// Skip Rules
use Rector\Naming\Rector\Foreach_\RenameForeachValueVariableToMatchExprVariableRector;
use Rector\CodeQuality\Rector\Array_\ArrayThisCallToThisMethodCallRector;
use Rector\CodeQuality\Rector\Array_\CallableThisArrayToAnonymousFunctionRector;
use Rector\CodingStyle\Rector\ClassConst\RemoveFinalFromConstRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveUnusedPromotedPropertyRector;
use Rector\Php74\Rector\FuncCall\ArraySpreadInsteadOfArrayMergeRector;
use Rector\Php55\Rector\String_\StringClassNameToClassConstantRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__
    ]);

    // is there a file you need to skip?
    $rectorConfig->skip([
        // __DIR__ . '/node_modules',
        // __DIR__ . '/dist',
        // CallableThisArrayToAnonymousFunctionRector::class,
        // RenameForeachValueVariableToMatchExprVariableRector::class, // Foreach single var
        // ArrayThisCallToThisMethodCallRector::class, // Transform add_action + add_filter
        // RemoveUnusedPromotedPropertyRector::class, // Rule PHP8.0
        // RemoveFinalFromConstRector::class, // Rule PHP8.1
        // ArraySpreadInsteadOfArrayMergeRector::class // array_merge is easier to read than array_spread
        StringClassNameToClassConstantRector::class // defined('WP_CLI') replace defined(\WP_CLI::class)
    ]);

    // define sets of rules
    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_81,
        SetList::TYPE_DECLARATION,
        SetList::CODE_QUALITY,
        SetList::DEAD_CODE,
        SetList::CODING_STYLE,
        SetList::NAMING,
    ]);
};
