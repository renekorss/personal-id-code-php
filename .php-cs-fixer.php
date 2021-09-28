<?php
$finder = Symfony\Component\Finder\Finder::create()
    ->notPath('vendor')
    ->notPath('docs')
    ->in(__DIR__)
    ->name('*.php');

return (new PhpCsFixer\Config)
    ->setRules([
        '@PSR2' => true,
        'array_syntax' => ['syntax' => 'short'],
        'ordered_imports' => ['sortAlgorithm' => 'alpha'],
        'no_unused_imports' => true,
    ])
    ->setUsingCache(false)
    ->setFinder($finder);
