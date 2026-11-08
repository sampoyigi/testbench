<?php

namespace SamPoyigi\Testbench;

use Pest\Rector\Set\PestSetList;
use Rector\CodingStyle\Rector\Catch_\CatchExceptionNameMatchingTypeRector;
use Rector\CodingStyle\Rector\ClassMethod\NewlineBeforeNewAssignSetRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnNeverTypeRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNewArrayRector;

class RectorConfig
{
    public static function configure(
        $php83 = false,
        $php84 = false,
        $php85 = true,
        $deadCode = true,
        $codeQuality = true,
        $codingStyle = true,
        $typeDeclarations = true,
    ): \Rector\Configuration\RectorConfigBuilder
    {
        return \Rector\Config\RectorConfig::configure()
            ->withImportNames()
            ->withComposerBased(laravel: true)
            ->withPhpSets(php83: $php83, php84: $php84, php85: $php85)
            ->withPreparedSets(
                deadCode: $deadCode,
                codeQuality: $codeQuality,
                codingStyle: $codingStyle,
                typeDeclarations: $typeDeclarations,
            )
            ->withSets([
                PestSetList::CODING_STYLE,
            ])
            ->withSkip([
                CatchExceptionNameMatchingTypeRector::class,
                ReturnTypeFromStrictNewArrayRector::class,
                ReturnNeverTypeRector::class,
                NewlineBeforeNewAssignSetRector::class,
            ]);
    }
}
