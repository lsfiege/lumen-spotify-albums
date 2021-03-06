<?php

/*
 * First we are going to select the files on which we are going to run
 * automatic codestyling.
 *
 * First we're going to excluded some directories that shouldn't be touched.
 * Next we are going to include every file with a `php` extension except
 * `blade.php` files (there are the view templates of a Laravel app).
 */
$finder = Symfony\Component\Finder\Finder::create()
    ->notPath('vendor')
    ->notPath('bootstrap')
    ->notPath('storage')
    ->in(__DIR__)
    ->name('*.php')
    ->notName('index.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return PhpCsFixer\Config::create()
    ->setRules([
        '@PSR2' => true,
        'array_syntax' => ['syntax' => 'short'],
        'no_unused_imports' => true,
        'ordered_imports' => ['sortAlgorithm' => 'alpha'],
        'phpdoc_separation' => true,
    ])
    ->setFinder($finder);
