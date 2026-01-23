<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use Rector\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector;
use Rector\CodingStyle\Rector\ClassMethod\UnSpaceableClassMethodReturnTypeRector;
use Rector\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
    ])
    ->withSkip([
        __DIR__ . '/vendor',
        __DIR__ . '/node_modules',
        __DIR__ . '/tests',
        __DIR__ . '/assets',
        __DIR__ . '/data',
        __DIR__ . '/examples',
        __DIR__ . '/schemas',
    ])
    ->withPhpSets(
        php82: true
    )
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        codingStyle: true,
        typeDeclarations: true,
        privatization: true,
        naming: true,
        instanceOf: true,
        earlyReturn: true,
        strictBooleans: true
    )
    ->withRules([
        InlineConstructorDefaultToPropertyRector::class,
        AddVoidReturnTypeWhereNoReturnRector::class,
    ]);
