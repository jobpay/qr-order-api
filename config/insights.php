<?php

declare(strict_types=1);

use NunoMaduro\PhpInsights\Domain\Insights\ForbiddenDefineFunctions;
use NunoMaduro\PhpInsights\Domain\Insights\ForbiddenFinalClasses;
use NunoMaduro\PhpInsights\Domain\Insights\ForbiddenNormalClasses;
use NunoMaduro\PhpInsights\Domain\Insights\ForbiddenPrivateMethods;
use NunoMaduro\PhpInsights\Domain\Insights\ForbiddenTraits;
use NunoMaduro\PhpInsights\Domain\Metrics\Architecture\Classes;
use PhpCsFixer\Fixer\Basic\BracesPositionFixer;
use PhpCsFixer\Fixer\CastNotation\CastSpacesFixer;
use PhpCsFixer\Fixer\ControlStructure\ControlStructureBracesFixer;
use PhpCsFixer\Fixer\ControlStructure\ControlStructureContinuationPositionFixer;
use PhpCsFixer\Fixer\ControlStructure\NoUselessElseFixer;
use PhpCsFixer\Fixer\FunctionNotation\FunctionTypehintSpaceFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocTrimFixer;
use PhpCsFixer\Fixer\ReturnNotation\ReturnAssignmentFixer;
use SlevomatCodingStandard\Sniffs\Commenting\UselessFunctionDocCommentSniff;
use SlevomatCodingStandard\Sniffs\Namespaces\AlphabeticallySortedUsesSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\DeclareStrictTypesSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\DisallowMixedTypeHintSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\ParameterTypeHintSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\PropertyTypeHintSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\ReturnTypeHintSniff;
use SlevomatCodingStandard\Sniffs\Variables\UselessVariableSniff;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Preset
    |--------------------------------------------------------------------------
    |
    | This option controls the default preset that will be used by PHP Insights
    | to make your code reliable, simple, and clean. However, you can always
    | adjust the `Metrics` and `Insights` below in this configuration file.
    |
    | Supported: "default", "laravel", "symfony", "magento2", "drupal", "wordpress"
    |
    */

    'preset' => 'laravel',

    /*
    |--------------------------------------------------------------------------
    | IDE
    |--------------------------------------------------------------------------
    |
    | This options allow to add hyperlinks in your terminal to quickly open
    | files in your favorite IDE while browsing your PhpInsights report.
    |
    | Supported: "textmate", "macvim", "emacs", "sublime", "phpstorm",
    | "atom", "vscode".
    |
    | If you have another IDE that is not in this list but which provide an
    | url-handler, you could fill this config with a pattern like this:
    |
    | myide://open?url=file://%f&line=%l
    |
    */

    'ide' => null,

    /*
    |--------------------------------------------------------------------------
    | Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may adjust all the various `Insights` that will be used by PHP
    | Insights. You can either add, remove or configure `Insights`. Keep in
    | mind, that all added `Insights` must belong to a specific `Metric`.
    |
    */

    'exclude' => [
        'config',
        'routes',
        'routes/auth.php',
        'database/migrations',
        'tests',
        'app/Notifications',
        'app/Providers',
        'app/Http/Middleware',
        'app/Models',
        'app/Http/Controllers',
    ],

    'add' => [
        Classes::class => [
            ForbiddenFinalClasses::class,
        ],
    ],

    'remove' => [
        AlphabeticallySortedUsesSniff::class,             // use文をアルファベット順にソートすることを強制
        DeclareStrictTypesSniff::class,                   // ファイルの先頭にdeclare(strict_types=1)を強制
        DisallowMixedTypeHintSniff::class,                // mixed型の使用を禁止
        ForbiddenDefineFunctions::class,                  // define()関数の使用を禁止
        ForbiddenNormalClasses::class,                    // 通常のクラス（finalでないクラス）の使用を禁止
        ForbiddenTraits::class,                           // traitの使用を禁止
        ParameterTypeHintSniff::class,                    // 関数・メソッドのパラメータに型ヒントを強制
        PropertyTypeHintSniff::class,                     // プロパティに型ヒントを強制
        ReturnTypeHintSniff::class,                       // 関数・メソッドの戻り値に型ヒントを強制
        UselessFunctionDocCommentSniff::class,            // 不要な関数のドキュメントコメントを禁止
        UselessVariableSniff::class,                      // 不要な変数（return assignment）の警告を除外
        CastSpacesFixer::class,                           // キャストのスペースを強制
        ReturnAssignmentFixer::class,                     // 不要な変数への代入を禁止（return $result = foo(); のような書き方を禁止）
        FunctionTypehintSpaceFixer::class,                // 関数型ヒントのスペースを強制
        PhpdocTrimFixer::class,                           // ドキュメントコメントのトリミングを強制
        ControlStructureBracesFixer::class,               // 制御構造のブレースの位置を強制
        ControlStructureContinuationPositionFixer::class, // 制御構造の継続行の位置を強制
        BracesPositionFixer::class,                       // ブレースの位置を強制
        NoUselessElseFixer::class,
    ],

    'config' => [
        ForbiddenPrivateMethods::class => [
            'title' => 'The usage of private methods is not idiomatic in Laravel.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Requirements
    |--------------------------------------------------------------------------
    |
    | Here you may define a level you want to reach per `Insights` category.
    | When a score is lower than the minimum level defined, then an error
    | code will be returned. This is optional and individually defined.
    |
    */

    'requirements' => [
        'min-quality' => 70,        // コード品質の最小スコアを70%に設定
        'min-complexity' => 80,     // 複雑度の最小スコアを80%に設定
        'min-architecture' => 60,   // アーキテクチャの最小スコアを60%に設定（Function lengthなどの厳格なルールを緩和）
        'min-style' => 60,          // スタイルの最小スコアを60%に設定（厳格なルールを緩和）
        'disable-security-check' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Threads
    |--------------------------------------------------------------------------
    |
    | Here you may adjust how many threads (core) PHPInsights can use to perform
    | the analysis. This is optional, don't provide it and the tool will guess
    | the max core number available. It accepts null value or integer > 0.
    |
    */

    'threads' => null,

    /*
    |--------------------------------------------------------------------------
    | Timeout
    |--------------------------------------------------------------------------
    | Here you may adjust the timeout (in seconds) for PHPInsights to run before
    | a ProcessTimedOutException is thrown.
    | This accepts an int > 0. Default is 60 seconds, which is the default value
    | of Symfony's setTimeout function.
    |
    */

    'timeout' => 60,
];
