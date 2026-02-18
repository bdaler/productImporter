<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('var')
    ->exclude('vendor');

return (new PhpCsFixer\Config())
    ->setRules([
        '@PHP83Migration' => true,
        '@PhpCsFixer' => true,
        '@Symfony' => true,
        '@PSR12' => true,
        'line_ending' => false,
        'single_line_throw' => false, // Disable forcing throw to be single line
        'not_operator_with_successor_space' => false,
        'not_operator_with_space' => false,
        'simplified_if_return' => true,
        'cast_spaces' => [
            'space' => 'none',
        ],
        'yoda_style' => [
            'equal' => false,
            'identical' => false,
            'less_and_greater' => false,
        ],
        'concat_space' => [
            'spacing' => 'one',
        ],
        'multiline_whitespace_before_semicolons' => [
            'strategy' => 'no_multi_line',
        ],
        'binary_operator_spaces' => [
            'operators' => [
                '+=' => 'align_single_space',
                '===' => 'align_single_space_minimal',
                '|' => 'no_space',
                '=>' => 'single_space',
                '??=' => 'single_space',
                '??' => 'single_space',
            ],
        ],
        'increment_style' => [
            'style' => 'post',
        ],
        'explicit_string_variable' => false,
    ])
    ->setFinder($finder);
