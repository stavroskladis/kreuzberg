<?php

declare(strict_types=1);

// osfsl-/Users/naamanhirschfeld/workspace/kreuzberg-dev/kreuzberg/packages/php/stubs/kreuzberg_extension.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Kreuzberg\ExtractionConfig
return \PHPStan\Cache\CacheItem::__set_state([
    'variableKey' => 'v2-3119307c419b0b68ce5de99f015c90205808f8d7e877aa5ace59d62568086491-8.4.20-6.70.0.0',
    'data'
    => [
        'locatedSource'
         => [
             'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
             'data'
              => [
                  'name' => 'Kreuzberg\\ExtractionConfig',
                  'filename' => '/Users/naamanhirschfeld/workspace/kreuzberg-dev/kreuzberg/packages/php/stubs/kreuzberg_extension.php',
              ],
         ],
        'namespace' => 'Kreuzberg',
        'name' => 'Kreuzberg\\ExtractionConfig',
        'shortName' => 'ExtractionConfig',
        'isInterface' => false,
        'isTrait' => false,
        'isEnum' => false,
        'isBackedEnum' => false,
        'modifiers' => 0,
        'docComment' => '/**
 * Main extraction configuration.
 *
 * This struct contains all configuration options for the extraction process.
 * It can be loaded from TOML, YAML, or JSON files, or created programmatically.
 *
 * # Example
 *
 * ```rust
 * use kreuzberg::core::config::ExtractionConfig;
 *
 * // Create with defaults
 * let config = ExtractionConfig::default();
 *
 * // Load from TOML file
 * // let config = ExtractionConfig::from_toml_file("kreuzberg.toml")?;
 * ```
 */',
        'attributes'
         => [
         ],
        'startLine' => 174,
        'endLine' => 281,
        'startColumn' => 5,
        'endColumn' => 5,
        'parentClassName' => null,
        'implementsClassNames'
         => [
         ],
        'traitClassNames'
         => [
         ],
        'immediateConstants'
         => [
         ],
        'immediateProperties'
         => [
             'use_cache'
              => [
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'name' => 'use_cache',
                  'modifiers' => 1,
                  'type'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                       'data'
                        => [
                            'name' => 'bool',
                            'isIdentifier' => true,
                        ],
                   ],
                  'default' => null,
                  'docComment' => null,
                  'attributes'
                   => [
                   ],
                  'startLine' => 176,
                  'endLine' => 176,
                  'startColumn' => 9,
                  'endColumn' => 31,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'enable_quality_processing'
              => [
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'name' => 'enable_quality_processing',
                  'modifiers' => 1,
                  'type'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                       'data'
                        => [
                            'name' => 'bool',
                            'isIdentifier' => true,
                        ],
                   ],
                  'default' => null,
                  'docComment' => null,
                  'attributes'
                   => [
                   ],
                  'startLine' => 177,
                  'endLine' => 177,
                  'startColumn' => 9,
                  'endColumn' => 47,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'ocr'
              => [
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'name' => 'ocr',
                  'modifiers' => 1,
                  'type'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                       'data'
                        => [
                            'types'
                             => [
                                 0
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'Kreuzberg\\OcrConfig',
                                           'isIdentifier' => false,
                                       ],
                                  ],
                                 1
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'null',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                             ],
                        ],
                   ],
                  'default' => null,
                  'docComment' => null,
                  'attributes'
                   => [
                   ],
                  'startLine' => 178,
                  'endLine' => 178,
                  'startColumn' => 9,
                  'endColumn' => 31,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'force_ocr'
              => [
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'name' => 'force_ocr',
                  'modifiers' => 1,
                  'type'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                       'data'
                        => [
                            'name' => 'bool',
                            'isIdentifier' => true,
                        ],
                   ],
                  'default' => null,
                  'docComment' => null,
                  'attributes'
                   => [
                   ],
                  'startLine' => 179,
                  'endLine' => 179,
                  'startColumn' => 9,
                  'endColumn' => 31,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'force_ocr_pages'
              => [
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'name' => 'force_ocr_pages',
                  'modifiers' => 1,
                  'type'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                       'data'
                        => [
                            'types'
                             => [
                                 0
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'array',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                                 1
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'null',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                             ],
                        ],
                   ],
                  'default' => null,
                  'docComment' => '/** @var ?array<int> */',
                  'attributes'
                   => [
                   ],
                  'startLine' => 181,
                  'endLine' => 181,
                  'startColumn' => 9,
                  'endColumn' => 39,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'disable_ocr'
              => [
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'name' => 'disable_ocr',
                  'modifiers' => 1,
                  'type'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                       'data'
                        => [
                            'name' => 'bool',
                            'isIdentifier' => true,
                        ],
                   ],
                  'default' => null,
                  'docComment' => null,
                  'attributes'
                   => [
                   ],
                  'startLine' => 182,
                  'endLine' => 182,
                  'startColumn' => 9,
                  'endColumn' => 33,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'chunking'
              => [
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'name' => 'chunking',
                  'modifiers' => 1,
                  'type'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                       'data'
                        => [
                            'types'
                             => [
                                 0
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'Kreuzberg\\ChunkingConfig',
                                           'isIdentifier' => false,
                                       ],
                                  ],
                                 1
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'null',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                             ],
                        ],
                   ],
                  'default' => null,
                  'docComment' => null,
                  'attributes'
                   => [
                   ],
                  'startLine' => 183,
                  'endLine' => 183,
                  'startColumn' => 9,
                  'endColumn' => 41,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'content_filter'
              => [
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'name' => 'content_filter',
                  'modifiers' => 1,
                  'type'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                       'data'
                        => [
                            'types'
                             => [
                                 0
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'Kreuzberg\\ContentFilterConfig',
                                           'isIdentifier' => false,
                                       ],
                                  ],
                                 1
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'null',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                             ],
                        ],
                   ],
                  'default' => null,
                  'docComment' => null,
                  'attributes'
                   => [
                   ],
                  'startLine' => 184,
                  'endLine' => 184,
                  'startColumn' => 9,
                  'endColumn' => 52,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'images'
              => [
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'name' => 'images',
                  'modifiers' => 1,
                  'type'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                       'data'
                        => [
                            'types'
                             => [
                                 0
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'Kreuzberg\\ImageExtractionConfig',
                                           'isIdentifier' => false,
                                       ],
                                  ],
                                 1
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'null',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                             ],
                        ],
                   ],
                  'default' => null,
                  'docComment' => null,
                  'attributes'
                   => [
                   ],
                  'startLine' => 185,
                  'endLine' => 185,
                  'startColumn' => 9,
                  'endColumn' => 46,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'pdf_options'
              => [
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'name' => 'pdf_options',
                  'modifiers' => 1,
                  'type'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                       'data'
                        => [
                            'types'
                             => [
                                 0
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'Kreuzberg\\PdfConfig',
                                           'isIdentifier' => false,
                                       ],
                                  ],
                                 1
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'null',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                             ],
                        ],
                   ],
                  'default' => null,
                  'docComment' => null,
                  'attributes'
                   => [
                   ],
                  'startLine' => 186,
                  'endLine' => 186,
                  'startColumn' => 9,
                  'endColumn' => 39,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'token_reduction'
              => [
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'name' => 'token_reduction',
                  'modifiers' => 1,
                  'type'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                       'data'
                        => [
                            'types'
                             => [
                                 0
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'Kreuzberg\\TokenReductionOptions',
                                           'isIdentifier' => false,
                                       ],
                                  ],
                                 1
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'null',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                             ],
                        ],
                   ],
                  'default' => null,
                  'docComment' => null,
                  'attributes'
                   => [
                   ],
                  'startLine' => 187,
                  'endLine' => 187,
                  'startColumn' => 9,
                  'endColumn' => 55,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'language_detection'
              => [
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'name' => 'language_detection',
                  'modifiers' => 1,
                  'type'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                       'data'
                        => [
                            'types'
                             => [
                                 0
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'Kreuzberg\\LanguageDetectionConfig',
                                           'isIdentifier' => false,
                                       ],
                                  ],
                                 1
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'null',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                             ],
                        ],
                   ],
                  'default' => null,
                  'docComment' => null,
                  'attributes'
                   => [
                   ],
                  'startLine' => 188,
                  'endLine' => 188,
                  'startColumn' => 9,
                  'endColumn' => 60,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'pages'
              => [
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'name' => 'pages',
                  'modifiers' => 1,
                  'type'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                       'data'
                        => [
                            'types'
                             => [
                                 0
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'Kreuzberg\\PageConfig',
                                           'isIdentifier' => false,
                                       ],
                                  ],
                                 1
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'null',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                             ],
                        ],
                   ],
                  'default' => null,
                  'docComment' => null,
                  'attributes'
                   => [
                   ],
                  'startLine' => 189,
                  'endLine' => 189,
                  'startColumn' => 9,
                  'endColumn' => 34,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'postprocessor'
              => [
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'name' => 'postprocessor',
                  'modifiers' => 1,
                  'type'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                       'data'
                        => [
                            'types'
                             => [
                                 0
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'Kreuzberg\\PostProcessorConfig',
                                           'isIdentifier' => false,
                                       ],
                                  ],
                                 1
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'null',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                             ],
                        ],
                   ],
                  'default' => null,
                  'docComment' => null,
                  'attributes'
                   => [
                   ],
                  'startLine' => 190,
                  'endLine' => 190,
                  'startColumn' => 9,
                  'endColumn' => 51,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'html_options'
              => [
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'name' => 'html_options',
                  'modifiers' => 1,
                  'type'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                       'data'
                        => [
                            'types'
                             => [
                                 0
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'string',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                                 1
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'null',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                             ],
                        ],
                   ],
                  'default' => null,
                  'docComment' => null,
                  'attributes'
                   => [
                   ],
                  'startLine' => 191,
                  'endLine' => 191,
                  'startColumn' => 9,
                  'endColumn' => 37,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'html_output'
              => [
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'name' => 'html_output',
                  'modifiers' => 1,
                  'type'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                       'data'
                        => [
                            'types'
                             => [
                                 0
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'Kreuzberg\\HtmlOutputConfig',
                                           'isIdentifier' => false,
                                       ],
                                  ],
                                 1
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'null',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                             ],
                        ],
                   ],
                  'default' => null,
                  'docComment' => null,
                  'attributes'
                   => [
                   ],
                  'startLine' => 192,
                  'endLine' => 192,
                  'startColumn' => 9,
                  'endColumn' => 46,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'extraction_timeout_secs'
              => [
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'name' => 'extraction_timeout_secs',
                  'modifiers' => 1,
                  'type'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                       'data'
                        => [
                            'types'
                             => [
                                 0
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'int',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                                 1
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'null',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                             ],
                        ],
                   ],
                  'default' => null,
                  'docComment' => null,
                  'attributes'
                   => [
                   ],
                  'startLine' => 193,
                  'endLine' => 193,
                  'startColumn' => 9,
                  'endColumn' => 45,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'max_concurrent_extractions'
              => [
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'name' => 'max_concurrent_extractions',
                  'modifiers' => 1,
                  'type'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                       'data'
                        => [
                            'types'
                             => [
                                 0
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'int',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                                 1
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'null',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                             ],
                        ],
                   ],
                  'default' => null,
                  'docComment' => null,
                  'attributes'
                   => [
                   ],
                  'startLine' => 194,
                  'endLine' => 194,
                  'startColumn' => 9,
                  'endColumn' => 48,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'result_format'
              => [
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'name' => 'result_format',
                  'modifiers' => 1,
                  'type'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                       'data'
                        => [
                            'name' => 'string',
                            'isIdentifier' => true,
                        ],
                   ],
                  'default' => null,
                  'docComment' => null,
                  'attributes'
                   => [
                   ],
                  'startLine' => 195,
                  'endLine' => 195,
                  'startColumn' => 9,
                  'endColumn' => 37,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'security_limits'
              => [
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'name' => 'security_limits',
                  'modifiers' => 1,
                  'type'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                       'data'
                        => [
                            'types'
                             => [
                                 0
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'string',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                                 1
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'null',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                             ],
                        ],
                   ],
                  'default' => null,
                  'docComment' => null,
                  'attributes'
                   => [
                   ],
                  'startLine' => 196,
                  'endLine' => 196,
                  'startColumn' => 9,
                  'endColumn' => 40,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'output_format'
              => [
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'name' => 'output_format',
                  'modifiers' => 1,
                  'type'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                       'data'
                        => [
                            'name' => 'string',
                            'isIdentifier' => true,
                        ],
                   ],
                  'default' => null,
                  'docComment' => null,
                  'attributes'
                   => [
                   ],
                  'startLine' => 197,
                  'endLine' => 197,
                  'startColumn' => 9,
                  'endColumn' => 37,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'layout'
              => [
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'name' => 'layout',
                  'modifiers' => 1,
                  'type'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                       'data'
                        => [
                            'types'
                             => [
                                 0
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'Kreuzberg\\LayoutDetectionConfig',
                                           'isIdentifier' => false,
                                       ],
                                  ],
                                 1
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'null',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                             ],
                        ],
                   ],
                  'default' => null,
                  'docComment' => null,
                  'attributes'
                   => [
                   ],
                  'startLine' => 198,
                  'endLine' => 198,
                  'startColumn' => 9,
                  'endColumn' => 46,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'include_document_structure'
              => [
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'name' => 'include_document_structure',
                  'modifiers' => 1,
                  'type'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                       'data'
                        => [
                            'name' => 'bool',
                            'isIdentifier' => true,
                        ],
                   ],
                  'default' => null,
                  'docComment' => null,
                  'attributes'
                   => [
                   ],
                  'startLine' => 199,
                  'endLine' => 199,
                  'startColumn' => 9,
                  'endColumn' => 48,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'acceleration'
              => [
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'name' => 'acceleration',
                  'modifiers' => 1,
                  'type'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                       'data'
                        => [
                            'types'
                             => [
                                 0
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'Kreuzberg\\AccelerationConfig',
                                           'isIdentifier' => false,
                                       ],
                                  ],
                                 1
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'null',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                             ],
                        ],
                   ],
                  'default' => null,
                  'docComment' => null,
                  'attributes'
                   => [
                   ],
                  'startLine' => 200,
                  'endLine' => 200,
                  'startColumn' => 9,
                  'endColumn' => 49,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'cache_namespace'
              => [
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'name' => 'cache_namespace',
                  'modifiers' => 1,
                  'type'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                       'data'
                        => [
                            'types'
                             => [
                                 0
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'string',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                                 1
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'null',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                             ],
                        ],
                   ],
                  'default' => null,
                  'docComment' => null,
                  'attributes'
                   => [
                   ],
                  'startLine' => 201,
                  'endLine' => 201,
                  'startColumn' => 9,
                  'endColumn' => 40,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'cache_ttl_secs'
              => [
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'name' => 'cache_ttl_secs',
                  'modifiers' => 1,
                  'type'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                       'data'
                        => [
                            'types'
                             => [
                                 0
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'int',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                                 1
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'null',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                             ],
                        ],
                   ],
                  'default' => null,
                  'docComment' => null,
                  'attributes'
                   => [
                   ],
                  'startLine' => 202,
                  'endLine' => 202,
                  'startColumn' => 9,
                  'endColumn' => 36,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'email'
              => [
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'name' => 'email',
                  'modifiers' => 1,
                  'type'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                       'data'
                        => [
                            'types'
                             => [
                                 0
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'Kreuzberg\\EmailConfig',
                                           'isIdentifier' => false,
                                       ],
                                  ],
                                 1
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'null',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                             ],
                        ],
                   ],
                  'default' => null,
                  'docComment' => null,
                  'attributes'
                   => [
                   ],
                  'startLine' => 203,
                  'endLine' => 203,
                  'startColumn' => 9,
                  'endColumn' => 35,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'concurrency'
              => [
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'name' => 'concurrency',
                  'modifiers' => 1,
                  'type'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                       'data'
                        => [
                            'types'
                             => [
                                 0
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'string',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                                 1
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'null',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                             ],
                        ],
                   ],
                  'default' => null,
                  'docComment' => null,
                  'attributes'
                   => [
                   ],
                  'startLine' => 204,
                  'endLine' => 204,
                  'startColumn' => 9,
                  'endColumn' => 36,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'max_archive_depth'
              => [
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'name' => 'max_archive_depth',
                  'modifiers' => 1,
                  'type'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                       'data'
                        => [
                            'name' => 'int',
                            'isIdentifier' => true,
                        ],
                   ],
                  'default' => null,
                  'docComment' => null,
                  'attributes'
                   => [
                   ],
                  'startLine' => 205,
                  'endLine' => 205,
                  'startColumn' => 9,
                  'endColumn' => 38,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'tree_sitter'
              => [
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'name' => 'tree_sitter',
                  'modifiers' => 1,
                  'type'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                       'data'
                        => [
                            'types'
                             => [
                                 0
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'Kreuzberg\\TreeSitterConfig',
                                           'isIdentifier' => false,
                                       ],
                                  ],
                                 1
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'null',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                             ],
                        ],
                   ],
                  'default' => null,
                  'docComment' => null,
                  'attributes'
                   => [
                   ],
                  'startLine' => 206,
                  'endLine' => 206,
                  'startColumn' => 9,
                  'endColumn' => 46,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'structured_extraction'
              => [
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'name' => 'structured_extraction',
                  'modifiers' => 1,
                  'type'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                       'data'
                        => [
                            'types'
                             => [
                                 0
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'Kreuzberg\\StructuredExtractionConfig',
                                           'isIdentifier' => false,
                                       ],
                                  ],
                                 1
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'null',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                             ],
                        ],
                   ],
                  'default' => null,
                  'docComment' => null,
                  'attributes'
                   => [
                   ],
                  'startLine' => 207,
                  'endLine' => 207,
                  'startColumn' => 9,
                  'endColumn' => 66,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'cancel_token'
              => [
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'name' => 'cancel_token',
                  'modifiers' => 1,
                  'type'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                       'data'
                        => [
                            'types'
                             => [
                                 0
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'string',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                                 1
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'null',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                             ],
                        ],
                   ],
                  'default' => null,
                  'docComment' => null,
                  'attributes'
                   => [
                   ],
                  'startLine' => 208,
                  'endLine' => 208,
                  'startColumn' => 9,
                  'endColumn' => 37,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
         ],
        'immediateMethods'
         => [
             '__construct'
              => [
                  'name' => '__construct',
                  'parameters'
                   => [
                       'use_cache'
                        => [
                            'name' => 'use_cache',
                            'default' => null,
                            'type'
                             => [
                                 'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                 'data'
                                  => [
                                      'name' => 'bool',
                                      'isIdentifier' => true,
                                  ],
                             ],
                            'isVariadic' => false,
                            'byRef' => false,
                            'isPromoted' => false,
                            'attributes'
                             => [
                             ],
                            'startLine' => 214,
                            'endLine' => 214,
                            'startColumn' => 13,
                            'endColumn' => 27,
                            'parameterIndex' => 0,
                            'isOptional' => false,
                        ],
                       'enable_quality_processing'
                        => [
                            'name' => 'enable_quality_processing',
                            'default' => null,
                            'type'
                             => [
                                 'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                 'data'
                                  => [
                                      'name' => 'bool',
                                      'isIdentifier' => true,
                                  ],
                             ],
                            'isVariadic' => false,
                            'byRef' => false,
                            'isPromoted' => false,
                            'attributes'
                             => [
                             ],
                            'startLine' => 215,
                            'endLine' => 215,
                            'startColumn' => 13,
                            'endColumn' => 43,
                            'parameterIndex' => 1,
                            'isOptional' => false,
                        ],
                       'force_ocr'
                        => [
                            'name' => 'force_ocr',
                            'default' => null,
                            'type'
                             => [
                                 'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                 'data'
                                  => [
                                      'name' => 'bool',
                                      'isIdentifier' => true,
                                  ],
                             ],
                            'isVariadic' => false,
                            'byRef' => false,
                            'isPromoted' => false,
                            'attributes'
                             => [
                             ],
                            'startLine' => 216,
                            'endLine' => 216,
                            'startColumn' => 13,
                            'endColumn' => 27,
                            'parameterIndex' => 2,
                            'isOptional' => false,
                        ],
                       'disable_ocr'
                        => [
                            'name' => 'disable_ocr',
                            'default' => null,
                            'type'
                             => [
                                 'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                 'data'
                                  => [
                                      'name' => 'bool',
                                      'isIdentifier' => true,
                                  ],
                             ],
                            'isVariadic' => false,
                            'byRef' => false,
                            'isPromoted' => false,
                            'attributes'
                             => [
                             ],
                            'startLine' => 217,
                            'endLine' => 217,
                            'startColumn' => 13,
                            'endColumn' => 29,
                            'parameterIndex' => 3,
                            'isOptional' => false,
                        ],
                       'result_format'
                        => [
                            'name' => 'result_format',
                            'default' => null,
                            'type'
                             => [
                                 'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                 'data'
                                  => [
                                      'name' => 'string',
                                      'isIdentifier' => true,
                                  ],
                             ],
                            'isVariadic' => false,
                            'byRef' => false,
                            'isPromoted' => false,
                            'attributes'
                             => [
                             ],
                            'startLine' => 218,
                            'endLine' => 218,
                            'startColumn' => 13,
                            'endColumn' => 33,
                            'parameterIndex' => 4,
                            'isOptional' => false,
                        ],
                       'output_format'
                        => [
                            'name' => 'output_format',
                            'default' => null,
                            'type'
                             => [
                                 'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                 'data'
                                  => [
                                      'name' => 'string',
                                      'isIdentifier' => true,
                                  ],
                             ],
                            'isVariadic' => false,
                            'byRef' => false,
                            'isPromoted' => false,
                            'attributes'
                             => [
                             ],
                            'startLine' => 219,
                            'endLine' => 219,
                            'startColumn' => 13,
                            'endColumn' => 33,
                            'parameterIndex' => 5,
                            'isOptional' => false,
                        ],
                       'include_document_structure'
                        => [
                            'name' => 'include_document_structure',
                            'default' => null,
                            'type'
                             => [
                                 'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                 'data'
                                  => [
                                      'name' => 'bool',
                                      'isIdentifier' => true,
                                  ],
                             ],
                            'isVariadic' => false,
                            'byRef' => false,
                            'isPromoted' => false,
                            'attributes'
                             => [
                             ],
                            'startLine' => 220,
                            'endLine' => 220,
                            'startColumn' => 13,
                            'endColumn' => 44,
                            'parameterIndex' => 6,
                            'isOptional' => false,
                        ],
                       'max_archive_depth'
                        => [
                            'name' => 'max_archive_depth',
                            'default' => null,
                            'type'
                             => [
                                 'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                 'data'
                                  => [
                                      'name' => 'int',
                                      'isIdentifier' => true,
                                  ],
                             ],
                            'isVariadic' => false,
                            'byRef' => false,
                            'isPromoted' => false,
                            'attributes'
                             => [
                             ],
                            'startLine' => 221,
                            'endLine' => 221,
                            'startColumn' => 13,
                            'endColumn' => 34,
                            'parameterIndex' => 7,
                            'isOptional' => false,
                        ],
                       'ocr'
                        => [
                            'name' => 'ocr',
                            'default'
                             => [
                                 'code' => 'null',
                                 'attributes'
                                  => [
                                      'startLine' => 222,
                                      'endLine' => 222,
                                      'startTokenPos' => 712,
                                      'startFilePos' => 6777,
                                      'endTokenPos' => 712,
                                      'endFilePos' => 6780,
                                  ],
                             ],
                            'type'
                             => [
                                 'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                                 'data'
                                  => [
                                      'types'
                                       => [
                                           0
                                            => [
                                                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                                'data'
                                                 => [
                                                     'name' => 'Kreuzberg\\OcrConfig',
                                                     'isIdentifier' => false,
                                                 ],
                                            ],
                                           1
                                            => [
                                                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                                'data'
                                                 => [
                                                     'name' => 'null',
                                                     'isIdentifier' => true,
                                                 ],
                                            ],
                                       ],
                                  ],
                             ],
                            'isVariadic' => false,
                            'byRef' => false,
                            'isPromoted' => false,
                            'attributes'
                             => [
                             ],
                            'startLine' => 222,
                            'endLine' => 222,
                            'startColumn' => 13,
                            'endColumn' => 34,
                            'parameterIndex' => 8,
                            'isOptional' => true,
                        ],
                       'force_ocr_pages'
                        => [
                            'name' => 'force_ocr_pages',
                            'default'
                             => [
                                 'code' => 'null',
                                 'attributes'
                                  => [
                                      'startLine' => 223,
                                      'endLine' => 223,
                                      'startTokenPos' => 722,
                                      'startFilePos' => 6821,
                                      'endTokenPos' => 722,
                                      'endFilePos' => 6824,
                                  ],
                             ],
                            'type'
                             => [
                                 'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                                 'data'
                                  => [
                                      'types'
                                       => [
                                           0
                                            => [
                                                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                                'data'
                                                 => [
                                                     'name' => 'array',
                                                     'isIdentifier' => true,
                                                 ],
                                            ],
                                           1
                                            => [
                                                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                                'data'
                                                 => [
                                                     'name' => 'null',
                                                     'isIdentifier' => true,
                                                 ],
                                            ],
                                       ],
                                  ],
                             ],
                            'isVariadic' => false,
                            'byRef' => false,
                            'isPromoted' => false,
                            'attributes'
                             => [
                             ],
                            'startLine' => 223,
                            'endLine' => 223,
                            'startColumn' => 13,
                            'endColumn' => 42,
                            'parameterIndex' => 9,
                            'isOptional' => true,
                        ],
                       'chunking'
                        => [
                            'name' => 'chunking',
                            'default'
                             => [
                                 'code' => 'null',
                                 'attributes'
                                  => [
                                      'startLine' => 224,
                                      'endLine' => 224,
                                      'startTokenPos' => 732,
                                      'startFilePos' => 6867,
                                      'endTokenPos' => 732,
                                      'endFilePos' => 6870,
                                  ],
                             ],
                            'type'
                             => [
                                 'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                                 'data'
                                  => [
                                      'types'
                                       => [
                                           0
                                            => [
                                                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                                'data'
                                                 => [
                                                     'name' => 'Kreuzberg\\ChunkingConfig',
                                                     'isIdentifier' => false,
                                                 ],
                                            ],
                                           1
                                            => [
                                                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                                'data'
                                                 => [
                                                     'name' => 'null',
                                                     'isIdentifier' => true,
                                                 ],
                                            ],
                                       ],
                                  ],
                             ],
                            'isVariadic' => false,
                            'byRef' => false,
                            'isPromoted' => false,
                            'attributes'
                             => [
                             ],
                            'startLine' => 224,
                            'endLine' => 224,
                            'startColumn' => 13,
                            'endColumn' => 44,
                            'parameterIndex' => 10,
                            'isOptional' => true,
                        ],
                       'content_filter'
                        => [
                            'name' => 'content_filter',
                            'default'
                             => [
                                 'code' => 'null',
                                 'attributes'
                                  => [
                                      'startLine' => 225,
                                      'endLine' => 225,
                                      'startTokenPos' => 742,
                                      'startFilePos' => 6924,
                                      'endTokenPos' => 742,
                                      'endFilePos' => 6927,
                                  ],
                             ],
                            'type'
                             => [
                                 'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                                 'data'
                                  => [
                                      'types'
                                       => [
                                           0
                                            => [
                                                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                                'data'
                                                 => [
                                                     'name' => 'Kreuzberg\\ContentFilterConfig',
                                                     'isIdentifier' => false,
                                                 ],
                                            ],
                                           1
                                            => [
                                                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                                'data'
                                                 => [
                                                     'name' => 'null',
                                                     'isIdentifier' => true,
                                                 ],
                                            ],
                                       ],
                                  ],
                             ],
                            'isVariadic' => false,
                            'byRef' => false,
                            'isPromoted' => false,
                            'attributes'
                             => [
                             ],
                            'startLine' => 225,
                            'endLine' => 225,
                            'startColumn' => 13,
                            'endColumn' => 55,
                            'parameterIndex' => 11,
                            'isOptional' => true,
                        ],
                       'images'
                        => [
                            'name' => 'images',
                            'default'
                             => [
                                 'code' => 'null',
                                 'attributes'
                                  => [
                                      'startLine' => 226,
                                      'endLine' => 226,
                                      'startTokenPos' => 752,
                                      'startFilePos' => 6975,
                                      'endTokenPos' => 752,
                                      'endFilePos' => 6978,
                                  ],
                             ],
                            'type'
                             => [
                                 'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                                 'data'
                                  => [
                                      'types'
                                       => [
                                           0
                                            => [
                                                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                                'data'
                                                 => [
                                                     'name' => 'Kreuzberg\\ImageExtractionConfig',
                                                     'isIdentifier' => false,
                                                 ],
                                            ],
                                           1
                                            => [
                                                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                                'data'
                                                 => [
                                                     'name' => 'null',
                                                     'isIdentifier' => true,
                                                 ],
                                            ],
                                       ],
                                  ],
                             ],
                            'isVariadic' => false,
                            'byRef' => false,
                            'isPromoted' => false,
                            'attributes'
                             => [
                             ],
                            'startLine' => 226,
                            'endLine' => 226,
                            'startColumn' => 13,
                            'endColumn' => 49,
                            'parameterIndex' => 12,
                            'isOptional' => true,
                        ],
                       'pdf_options'
                        => [
                            'name' => 'pdf_options',
                            'default'
                             => [
                                 'code' => 'null',
                                 'attributes'
                                  => [
                                      'startLine' => 227,
                                      'endLine' => 227,
                                      'startTokenPos' => 762,
                                      'startFilePos' => 7019,
                                      'endTokenPos' => 762,
                                      'endFilePos' => 7022,
                                  ],
                             ],
                            'type'
                             => [
                                 'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                                 'data'
                                  => [
                                      'types'
                                       => [
                                           0
                                            => [
                                                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                                'data'
                                                 => [
                                                     'name' => 'Kreuzberg\\PdfConfig',
                                                     'isIdentifier' => false,
                                                 ],
                                            ],
                                           1
                                            => [
                                                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                                'data'
                                                 => [
                                                     'name' => 'null',
                                                     'isIdentifier' => true,
                                                 ],
                                            ],
                                       ],
                                  ],
                             ],
                            'isVariadic' => false,
                            'byRef' => false,
                            'isPromoted' => false,
                            'attributes'
                             => [
                             ],
                            'startLine' => 227,
                            'endLine' => 227,
                            'startColumn' => 13,
                            'endColumn' => 42,
                            'parameterIndex' => 13,
                            'isOptional' => true,
                        ],
                       'token_reduction'
                        => [
                            'name' => 'token_reduction',
                            'default'
                             => [
                                 'code' => 'null',
                                 'attributes'
                                  => [
                                      'startLine' => 228,
                                      'endLine' => 228,
                                      'startTokenPos' => 772,
                                      'startFilePos' => 7079,
                                      'endTokenPos' => 772,
                                      'endFilePos' => 7082,
                                  ],
                             ],
                            'type'
                             => [
                                 'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                                 'data'
                                  => [
                                      'types'
                                       => [
                                           0
                                            => [
                                                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                                'data'
                                                 => [
                                                     'name' => 'Kreuzberg\\TokenReductionOptions',
                                                     'isIdentifier' => false,
                                                 ],
                                            ],
                                           1
                                            => [
                                                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                                'data'
                                                 => [
                                                     'name' => 'null',
                                                     'isIdentifier' => true,
                                                 ],
                                            ],
                                       ],
                                  ],
                             ],
                            'isVariadic' => false,
                            'byRef' => false,
                            'isPromoted' => false,
                            'attributes'
                             => [
                             ],
                            'startLine' => 228,
                            'endLine' => 228,
                            'startColumn' => 13,
                            'endColumn' => 58,
                            'parameterIndex' => 14,
                            'isOptional' => true,
                        ],
                       'language_detection'
                        => [
                            'name' => 'language_detection',
                            'default'
                             => [
                                 'code' => 'null',
                                 'attributes'
                                  => [
                                      'startLine' => 229,
                                      'endLine' => 229,
                                      'startTokenPos' => 782,
                                      'startFilePos' => 7144,
                                      'endTokenPos' => 782,
                                      'endFilePos' => 7147,
                                  ],
                             ],
                            'type'
                             => [
                                 'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                                 'data'
                                  => [
                                      'types'
                                       => [
                                           0
                                            => [
                                                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                                'data'
                                                 => [
                                                     'name' => 'Kreuzberg\\LanguageDetectionConfig',
                                                     'isIdentifier' => false,
                                                 ],
                                            ],
                                           1
                                            => [
                                                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                                'data'
                                                 => [
                                                     'name' => 'null',
                                                     'isIdentifier' => true,
                                                 ],
                                            ],
                                       ],
                                  ],
                             ],
                            'isVariadic' => false,
                            'byRef' => false,
                            'isPromoted' => false,
                            'attributes'
                             => [
                             ],
                            'startLine' => 229,
                            'endLine' => 229,
                            'startColumn' => 13,
                            'endColumn' => 63,
                            'parameterIndex' => 15,
                            'isOptional' => true,
                        ],
                       'pages'
                        => [
                            'name' => 'pages',
                            'default'
                             => [
                                 'code' => 'null',
                                 'attributes'
                                  => [
                                      'startLine' => 230,
                                      'endLine' => 230,
                                      'startTokenPos' => 792,
                                      'startFilePos' => 7183,
                                      'endTokenPos' => 792,
                                      'endFilePos' => 7186,
                                  ],
                             ],
                            'type'
                             => [
                                 'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                                 'data'
                                  => [
                                      'types'
                                       => [
                                           0
                                            => [
                                                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                                'data'
                                                 => [
                                                     'name' => 'Kreuzberg\\PageConfig',
                                                     'isIdentifier' => false,
                                                 ],
                                            ],
                                           1
                                            => [
                                                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                                'data'
                                                 => [
                                                     'name' => 'null',
                                                     'isIdentifier' => true,
                                                 ],
                                            ],
                                       ],
                                  ],
                             ],
                            'isVariadic' => false,
                            'byRef' => false,
                            'isPromoted' => false,
                            'attributes'
                             => [
                             ],
                            'startLine' => 230,
                            'endLine' => 230,
                            'startColumn' => 13,
                            'endColumn' => 37,
                            'parameterIndex' => 16,
                            'isOptional' => true,
                        ],
                       'postprocessor'
                        => [
                            'name' => 'postprocessor',
                            'default'
                             => [
                                 'code' => 'null',
                                 'attributes'
                                  => [
                                      'startLine' => 231,
                                      'endLine' => 231,
                                      'startTokenPos' => 802,
                                      'startFilePos' => 7239,
                                      'endTokenPos' => 802,
                                      'endFilePos' => 7242,
                                  ],
                             ],
                            'type'
                             => [
                                 'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                                 'data'
                                  => [
                                      'types'
                                       => [
                                           0
                                            => [
                                                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                                'data'
                                                 => [
                                                     'name' => 'Kreuzberg\\PostProcessorConfig',
                                                     'isIdentifier' => false,
                                                 ],
                                            ],
                                           1
                                            => [
                                                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                                'data'
                                                 => [
                                                     'name' => 'null',
                                                     'isIdentifier' => true,
                                                 ],
                                            ],
                                       ],
                                  ],
                             ],
                            'isVariadic' => false,
                            'byRef' => false,
                            'isPromoted' => false,
                            'attributes'
                             => [
                             ],
                            'startLine' => 231,
                            'endLine' => 231,
                            'startColumn' => 13,
                            'endColumn' => 54,
                            'parameterIndex' => 17,
                            'isOptional' => true,
                        ],
                       'html_options'
                        => [
                            'name' => 'html_options',
                            'default'
                             => [
                                 'code' => 'null',
                                 'attributes'
                                  => [
                                      'startLine' => 232,
                                      'endLine' => 232,
                                      'startTokenPos' => 812,
                                      'startFilePos' => 7281,
                                      'endTokenPos' => 812,
                                      'endFilePos' => 7284,
                                  ],
                             ],
                            'type'
                             => [
                                 'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                                 'data'
                                  => [
                                      'types'
                                       => [
                                           0
                                            => [
                                                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                                'data'
                                                 => [
                                                     'name' => 'string',
                                                     'isIdentifier' => true,
                                                 ],
                                            ],
                                           1
                                            => [
                                                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                                'data'
                                                 => [
                                                     'name' => 'null',
                                                     'isIdentifier' => true,
                                                 ],
                                            ],
                                       ],
                                  ],
                             ],
                            'isVariadic' => false,
                            'byRef' => false,
                            'isPromoted' => false,
                            'attributes'
                             => [
                             ],
                            'startLine' => 232,
                            'endLine' => 232,
                            'startColumn' => 13,
                            'endColumn' => 40,
                            'parameterIndex' => 18,
                            'isOptional' => true,
                        ],
                       'html_output'
                        => [
                            'name' => 'html_output',
                            'default'
                             => [
                                 'code' => 'null',
                                 'attributes'
                                  => [
                                      'startLine' => 233,
                                      'endLine' => 233,
                                      'startTokenPos' => 822,
                                      'startFilePos' => 7332,
                                      'endTokenPos' => 822,
                                      'endFilePos' => 7335,
                                  ],
                             ],
                            'type'
                             => [
                                 'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                                 'data'
                                  => [
                                      'types'
                                       => [
                                           0
                                            => [
                                                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                                'data'
                                                 => [
                                                     'name' => 'Kreuzberg\\HtmlOutputConfig',
                                                     'isIdentifier' => false,
                                                 ],
                                            ],
                                           1
                                            => [
                                                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                                'data'
                                                 => [
                                                     'name' => 'null',
                                                     'isIdentifier' => true,
                                                 ],
                                            ],
                                       ],
                                  ],
                             ],
                            'isVariadic' => false,
                            'byRef' => false,
                            'isPromoted' => false,
                            'attributes'
                             => [
                             ],
                            'startLine' => 233,
                            'endLine' => 233,
                            'startColumn' => 13,
                            'endColumn' => 49,
                            'parameterIndex' => 19,
                            'isOptional' => true,
                        ],
                       'extraction_timeout_secs'
                        => [
                            'name' => 'extraction_timeout_secs',
                            'default'
                             => [
                                 'code' => 'null',
                                 'attributes'
                                  => [
                                      'startLine' => 234,
                                      'endLine' => 234,
                                      'startTokenPos' => 832,
                                      'startFilePos' => 7382,
                                      'endTokenPos' => 832,
                                      'endFilePos' => 7385,
                                  ],
                             ],
                            'type'
                             => [
                                 'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                                 'data'
                                  => [
                                      'types'
                                       => [
                                           0
                                            => [
                                                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                                'data'
                                                 => [
                                                     'name' => 'int',
                                                     'isIdentifier' => true,
                                                 ],
                                            ],
                                           1
                                            => [
                                                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                                'data'
                                                 => [
                                                     'name' => 'null',
                                                     'isIdentifier' => true,
                                                 ],
                                            ],
                                       ],
                                  ],
                             ],
                            'isVariadic' => false,
                            'byRef' => false,
                            'isPromoted' => false,
                            'attributes'
                             => [
                             ],
                            'startLine' => 234,
                            'endLine' => 234,
                            'startColumn' => 13,
                            'endColumn' => 48,
                            'parameterIndex' => 20,
                            'isOptional' => true,
                        ],
                       'max_concurrent_extractions'
                        => [
                            'name' => 'max_concurrent_extractions',
                            'default'
                             => [
                                 'code' => 'null',
                                 'attributes'
                                  => [
                                      'startLine' => 235,
                                      'endLine' => 235,
                                      'startTokenPos' => 842,
                                      'startFilePos' => 7435,
                                      'endTokenPos' => 842,
                                      'endFilePos' => 7438,
                                  ],
                             ],
                            'type'
                             => [
                                 'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                                 'data'
                                  => [
                                      'types'
                                       => [
                                           0
                                            => [
                                                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                                'data'
                                                 => [
                                                     'name' => 'int',
                                                     'isIdentifier' => true,
                                                 ],
                                            ],
                                           1
                                            => [
                                                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                                'data'
                                                 => [
                                                     'name' => 'null',
                                                     'isIdentifier' => true,
                                                 ],
                                            ],
                                       ],
                                  ],
                             ],
                            'isVariadic' => false,
                            'byRef' => false,
                            'isPromoted' => false,
                            'attributes'
                             => [
                             ],
                            'startLine' => 235,
                            'endLine' => 235,
                            'startColumn' => 13,
                            'endColumn' => 51,
                            'parameterIndex' => 21,
                            'isOptional' => true,
                        ],
                       'security_limits'
                        => [
                            'name' => 'security_limits',
                            'default'
                             => [
                                 'code' => 'null',
                                 'attributes'
                                  => [
                                      'startLine' => 236,
                                      'endLine' => 236,
                                      'startTokenPos' => 852,
                                      'startFilePos' => 7480,
                                      'endTokenPos' => 852,
                                      'endFilePos' => 7483,
                                  ],
                             ],
                            'type'
                             => [
                                 'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                                 'data'
                                  => [
                                      'types'
                                       => [
                                           0
                                            => [
                                                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                                'data'
                                                 => [
                                                     'name' => 'string',
                                                     'isIdentifier' => true,
                                                 ],
                                            ],
                                           1
                                            => [
                                                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                                'data'
                                                 => [
                                                     'name' => 'null',
                                                     'isIdentifier' => true,
                                                 ],
                                            ],
                                       ],
                                  ],
                             ],
                            'isVariadic' => false,
                            'byRef' => false,
                            'isPromoted' => false,
                            'attributes'
                             => [
                             ],
                            'startLine' => 236,
                            'endLine' => 236,
                            'startColumn' => 13,
                            'endColumn' => 43,
                            'parameterIndex' => 22,
                            'isOptional' => true,
                        ],
                       'layout'
                        => [
                            'name' => 'layout',
                            'default'
                             => [
                                 'code' => 'null',
                                 'attributes'
                                  => [
                                      'startLine' => 237,
                                      'endLine' => 237,
                                      'startTokenPos' => 862,
                                      'startFilePos' => 7531,
                                      'endTokenPos' => 862,
                                      'endFilePos' => 7534,
                                  ],
                             ],
                            'type'
                             => [
                                 'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                                 'data'
                                  => [
                                      'types'
                                       => [
                                           0
                                            => [
                                                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                                'data'
                                                 => [
                                                     'name' => 'Kreuzberg\\LayoutDetectionConfig',
                                                     'isIdentifier' => false,
                                                 ],
                                            ],
                                           1
                                            => [
                                                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                                'data'
                                                 => [
                                                     'name' => 'null',
                                                     'isIdentifier' => true,
                                                 ],
                                            ],
                                       ],
                                  ],
                             ],
                            'isVariadic' => false,
                            'byRef' => false,
                            'isPromoted' => false,
                            'attributes'
                             => [
                             ],
                            'startLine' => 237,
                            'endLine' => 237,
                            'startColumn' => 13,
                            'endColumn' => 49,
                            'parameterIndex' => 23,
                            'isOptional' => true,
                        ],
                       'acceleration'
                        => [
                            'name' => 'acceleration',
                            'default'
                             => [
                                 'code' => 'null',
                                 'attributes'
                                  => [
                                      'startLine' => 238,
                                      'endLine' => 238,
                                      'startTokenPos' => 872,
                                      'startFilePos' => 7585,
                                      'endTokenPos' => 872,
                                      'endFilePos' => 7588,
                                  ],
                             ],
                            'type'
                             => [
                                 'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                                 'data'
                                  => [
                                      'types'
                                       => [
                                           0
                                            => [
                                                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                                'data'
                                                 => [
                                                     'name' => 'Kreuzberg\\AccelerationConfig',
                                                     'isIdentifier' => false,
                                                 ],
                                            ],
                                           1
                                            => [
                                                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                                'data'
                                                 => [
                                                     'name' => 'null',
                                                     'isIdentifier' => true,
                                                 ],
                                            ],
                                       ],
                                  ],
                             ],
                            'isVariadic' => false,
                            'byRef' => false,
                            'isPromoted' => false,
                            'attributes'
                             => [
                             ],
                            'startLine' => 238,
                            'endLine' => 238,
                            'startColumn' => 13,
                            'endColumn' => 52,
                            'parameterIndex' => 24,
                            'isOptional' => true,
                        ],
                       'cache_namespace'
                        => [
                            'name' => 'cache_namespace',
                            'default'
                             => [
                                 'code' => 'null',
                                 'attributes'
                                  => [
                                      'startLine' => 239,
                                      'endLine' => 239,
                                      'startTokenPos' => 882,
                                      'startFilePos' => 7630,
                                      'endTokenPos' => 882,
                                      'endFilePos' => 7633,
                                  ],
                             ],
                            'type'
                             => [
                                 'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                                 'data'
                                  => [
                                      'types'
                                       => [
                                           0
                                            => [
                                                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                                'data'
                                                 => [
                                                     'name' => 'string',
                                                     'isIdentifier' => true,
                                                 ],
                                            ],
                                           1
                                            => [
                                                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                                'data'
                                                 => [
                                                     'name' => 'null',
                                                     'isIdentifier' => true,
                                                 ],
                                            ],
                                       ],
                                  ],
                             ],
                            'isVariadic' => false,
                            'byRef' => false,
                            'isPromoted' => false,
                            'attributes'
                             => [
                             ],
                            'startLine' => 239,
                            'endLine' => 239,
                            'startColumn' => 13,
                            'endColumn' => 43,
                            'parameterIndex' => 25,
                            'isOptional' => true,
                        ],
                       'cache_ttl_secs'
                        => [
                            'name' => 'cache_ttl_secs',
                            'default'
                             => [
                                 'code' => 'null',
                                 'attributes'
                                  => [
                                      'startLine' => 240,
                                      'endLine' => 240,
                                      'startTokenPos' => 892,
                                      'startFilePos' => 7671,
                                      'endTokenPos' => 892,
                                      'endFilePos' => 7674,
                                  ],
                             ],
                            'type'
                             => [
                                 'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                                 'data'
                                  => [
                                      'types'
                                       => [
                                           0
                                            => [
                                                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                                'data'
                                                 => [
                                                     'name' => 'int',
                                                     'isIdentifier' => true,
                                                 ],
                                            ],
                                           1
                                            => [
                                                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                                'data'
                                                 => [
                                                     'name' => 'null',
                                                     'isIdentifier' => true,
                                                 ],
                                            ],
                                       ],
                                  ],
                             ],
                            'isVariadic' => false,
                            'byRef' => false,
                            'isPromoted' => false,
                            'attributes'
                             => [
                             ],
                            'startLine' => 240,
                            'endLine' => 240,
                            'startColumn' => 13,
                            'endColumn' => 39,
                            'parameterIndex' => 26,
                            'isOptional' => true,
                        ],
                       'email'
                        => [
                            'name' => 'email',
                            'default'
                             => [
                                 'code' => 'null',
                                 'attributes'
                                  => [
                                      'startLine' => 241,
                                      'endLine' => 241,
                                      'startTokenPos' => 902,
                                      'startFilePos' => 7711,
                                      'endTokenPos' => 902,
                                      'endFilePos' => 7714,
                                  ],
                             ],
                            'type'
                             => [
                                 'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                                 'data'
                                  => [
                                      'types'
                                       => [
                                           0
                                            => [
                                                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                                'data'
                                                 => [
                                                     'name' => 'Kreuzberg\\EmailConfig',
                                                     'isIdentifier' => false,
                                                 ],
                                            ],
                                           1
                                            => [
                                                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                                'data'
                                                 => [
                                                     'name' => 'null',
                                                     'isIdentifier' => true,
                                                 ],
                                            ],
                                       ],
                                  ],
                             ],
                            'isVariadic' => false,
                            'byRef' => false,
                            'isPromoted' => false,
                            'attributes'
                             => [
                             ],
                            'startLine' => 241,
                            'endLine' => 241,
                            'startColumn' => 13,
                            'endColumn' => 38,
                            'parameterIndex' => 27,
                            'isOptional' => true,
                        ],
                       'concurrency'
                        => [
                            'name' => 'concurrency',
                            'default'
                             => [
                                 'code' => 'null',
                                 'attributes'
                                  => [
                                      'startLine' => 242,
                                      'endLine' => 242,
                                      'startTokenPos' => 912,
                                      'startFilePos' => 7752,
                                      'endTokenPos' => 912,
                                      'endFilePos' => 7755,
                                  ],
                             ],
                            'type'
                             => [
                                 'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                                 'data'
                                  => [
                                      'types'
                                       => [
                                           0
                                            => [
                                                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                                'data'
                                                 => [
                                                     'name' => 'string',
                                                     'isIdentifier' => true,
                                                 ],
                                            ],
                                           1
                                            => [
                                                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                                'data'
                                                 => [
                                                     'name' => 'null',
                                                     'isIdentifier' => true,
                                                 ],
                                            ],
                                       ],
                                  ],
                             ],
                            'isVariadic' => false,
                            'byRef' => false,
                            'isPromoted' => false,
                            'attributes'
                             => [
                             ],
                            'startLine' => 242,
                            'endLine' => 242,
                            'startColumn' => 13,
                            'endColumn' => 39,
                            'parameterIndex' => 28,
                            'isOptional' => true,
                        ],
                       'tree_sitter'
                        => [
                            'name' => 'tree_sitter',
                            'default'
                             => [
                                 'code' => 'null',
                                 'attributes'
                                  => [
                                      'startLine' => 243,
                                      'endLine' => 243,
                                      'startTokenPos' => 922,
                                      'startFilePos' => 7803,
                                      'endTokenPos' => 922,
                                      'endFilePos' => 7806,
                                  ],
                             ],
                            'type'
                             => [
                                 'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                                 'data'
                                  => [
                                      'types'
                                       => [
                                           0
                                            => [
                                                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                                'data'
                                                 => [
                                                     'name' => 'Kreuzberg\\TreeSitterConfig',
                                                     'isIdentifier' => false,
                                                 ],
                                            ],
                                           1
                                            => [
                                                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                                'data'
                                                 => [
                                                     'name' => 'null',
                                                     'isIdentifier' => true,
                                                 ],
                                            ],
                                       ],
                                  ],
                             ],
                            'isVariadic' => false,
                            'byRef' => false,
                            'isPromoted' => false,
                            'attributes'
                             => [
                             ],
                            'startLine' => 243,
                            'endLine' => 243,
                            'startColumn' => 13,
                            'endColumn' => 49,
                            'parameterIndex' => 29,
                            'isOptional' => true,
                        ],
                       'structured_extraction'
                        => [
                            'name' => 'structured_extraction',
                            'default'
                             => [
                                 'code' => 'null',
                                 'attributes'
                                  => [
                                      'startLine' => 244,
                                      'endLine' => 244,
                                      'startTokenPos' => 932,
                                      'startFilePos' => 7874,
                                      'endTokenPos' => 932,
                                      'endFilePos' => 7877,
                                  ],
                             ],
                            'type'
                             => [
                                 'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                                 'data'
                                  => [
                                      'types'
                                       => [
                                           0
                                            => [
                                                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                                'data'
                                                 => [
                                                     'name' => 'Kreuzberg\\StructuredExtractionConfig',
                                                     'isIdentifier' => false,
                                                 ],
                                            ],
                                           1
                                            => [
                                                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                                'data'
                                                 => [
                                                     'name' => 'null',
                                                     'isIdentifier' => true,
                                                 ],
                                            ],
                                       ],
                                  ],
                             ],
                            'isVariadic' => false,
                            'byRef' => false,
                            'isPromoted' => false,
                            'attributes'
                             => [
                             ],
                            'startLine' => 244,
                            'endLine' => 244,
                            'startColumn' => 13,
                            'endColumn' => 69,
                            'parameterIndex' => 30,
                            'isOptional' => true,
                        ],
                       'cancel_token'
                        => [
                            'name' => 'cancel_token',
                            'default'
                             => [
                                 'code' => 'null',
                                 'attributes'
                                  => [
                                      'startLine' => 245,
                                      'endLine' => 245,
                                      'startTokenPos' => 942,
                                      'startFilePos' => 7916,
                                      'endTokenPos' => 942,
                                      'endFilePos' => 7919,
                                  ],
                             ],
                            'type'
                             => [
                                 'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                                 'data'
                                  => [
                                      'types'
                                       => [
                                           0
                                            => [
                                                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                                'data'
                                                 => [
                                                     'name' => 'string',
                                                     'isIdentifier' => true,
                                                 ],
                                            ],
                                           1
                                            => [
                                                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                                'data'
                                                 => [
                                                     'name' => 'null',
                                                     'isIdentifier' => true,
                                                 ],
                                            ],
                                       ],
                                  ],
                             ],
                            'isVariadic' => false,
                            'byRef' => false,
                            'isPromoted' => false,
                            'attributes'
                             => [
                             ],
                            'startLine' => 245,
                            'endLine' => 245,
                            'startColumn' => 13,
                            'endColumn' => 40,
                            'parameterIndex' => 31,
                            'isOptional' => true,
                        ],
                   ],
                  'returnsReference' => false,
                  'returnType' => null,
                  'attributes'
                   => [
                   ],
                  'docComment' => '/**
 * @param ?array<int> $force_ocr_pages
 */',
                  'startLine' => 213,
                  'endLine' => 246,
                  'startColumn' => 9,
                  'endColumn' => 12,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'currentClassName' => 'Kreuzberg\\ExtractionConfig',
                  'aliasName' => null,
              ],
             'getUseCache'
              => [
                  'name' => 'getUseCache',
                  'parameters'
                   => [
                   ],
                  'returnsReference' => false,
                  'returnType'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                       'data'
                        => [
                            'name' => 'bool',
                            'isIdentifier' => true,
                        ],
                   ],
                  'attributes'
                   => [
                   ],
                  'docComment' => null,
                  'startLine' => 248,
                  'endLine' => 248,
                  'startColumn' => 9,
                  'endColumn' => 46,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'currentClassName' => 'Kreuzberg\\ExtractionConfig',
                  'aliasName' => null,
              ],
             'getEnableQualityProcessing'
              => [
                  'name' => 'getEnableQualityProcessing',
                  'parameters'
                   => [
                   ],
                  'returnsReference' => false,
                  'returnType'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                       'data'
                        => [
                            'name' => 'bool',
                            'isIdentifier' => true,
                        ],
                   ],
                  'attributes'
                   => [
                   ],
                  'docComment' => null,
                  'startLine' => 249,
                  'endLine' => 249,
                  'startColumn' => 9,
                  'endColumn' => 61,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'currentClassName' => 'Kreuzberg\\ExtractionConfig',
                  'aliasName' => null,
              ],
             'getOcr'
              => [
                  'name' => 'getOcr',
                  'parameters'
                   => [
                   ],
                  'returnsReference' => false,
                  'returnType'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                       'data'
                        => [
                            'types'
                             => [
                                 0
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'Kreuzberg\\OcrConfig',
                                           'isIdentifier' => false,
                                       ],
                                  ],
                                 1
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'null',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                             ],
                        ],
                   ],
                  'attributes'
                   => [
                   ],
                  'docComment' => null,
                  'startLine' => 250,
                  'endLine' => 250,
                  'startColumn' => 9,
                  'endColumn' => 47,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'currentClassName' => 'Kreuzberg\\ExtractionConfig',
                  'aliasName' => null,
              ],
             'getForceOcr'
              => [
                  'name' => 'getForceOcr',
                  'parameters'
                   => [
                   ],
                  'returnsReference' => false,
                  'returnType'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                       'data'
                        => [
                            'name' => 'bool',
                            'isIdentifier' => true,
                        ],
                   ],
                  'attributes'
                   => [
                   ],
                  'docComment' => null,
                  'startLine' => 251,
                  'endLine' => 251,
                  'startColumn' => 9,
                  'endColumn' => 46,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'currentClassName' => 'Kreuzberg\\ExtractionConfig',
                  'aliasName' => null,
              ],
             'getForceOcrPages'
              => [
                  'name' => 'getForceOcrPages',
                  'parameters'
                   => [
                   ],
                  'returnsReference' => false,
                  'returnType'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                       'data'
                        => [
                            'types'
                             => [
                                 0
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'array',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                                 1
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'null',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                             ],
                        ],
                   ],
                  'attributes'
                   => [
                   ],
                  'docComment' => '/** @return ?array<int> */',
                  'startLine' => 253,
                  'endLine' => 253,
                  'startColumn' => 9,
                  'endColumn' => 53,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'currentClassName' => 'Kreuzberg\\ExtractionConfig',
                  'aliasName' => null,
              ],
             'getDisableOcr'
              => [
                  'name' => 'getDisableOcr',
                  'parameters'
                   => [
                   ],
                  'returnsReference' => false,
                  'returnType'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                       'data'
                        => [
                            'name' => 'bool',
                            'isIdentifier' => true,
                        ],
                   ],
                  'attributes'
                   => [
                   ],
                  'docComment' => null,
                  'startLine' => 254,
                  'endLine' => 254,
                  'startColumn' => 9,
                  'endColumn' => 48,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'currentClassName' => 'Kreuzberg\\ExtractionConfig',
                  'aliasName' => null,
              ],
             'getChunking'
              => [
                  'name' => 'getChunking',
                  'parameters'
                   => [
                   ],
                  'returnsReference' => false,
                  'returnType'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                       'data'
                        => [
                            'types'
                             => [
                                 0
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'Kreuzberg\\ChunkingConfig',
                                           'isIdentifier' => false,
                                       ],
                                  ],
                                 1
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'null',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                             ],
                        ],
                   ],
                  'attributes'
                   => [
                   ],
                  'docComment' => null,
                  'startLine' => 255,
                  'endLine' => 255,
                  'startColumn' => 9,
                  'endColumn' => 57,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'currentClassName' => 'Kreuzberg\\ExtractionConfig',
                  'aliasName' => null,
              ],
             'getContentFilter'
              => [
                  'name' => 'getContentFilter',
                  'parameters'
                   => [
                   ],
                  'returnsReference' => false,
                  'returnType'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                       'data'
                        => [
                            'types'
                             => [
                                 0
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'Kreuzberg\\ContentFilterConfig',
                                           'isIdentifier' => false,
                                       ],
                                  ],
                                 1
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'null',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                             ],
                        ],
                   ],
                  'attributes'
                   => [
                   ],
                  'docComment' => null,
                  'startLine' => 256,
                  'endLine' => 256,
                  'startColumn' => 9,
                  'endColumn' => 67,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'currentClassName' => 'Kreuzberg\\ExtractionConfig',
                  'aliasName' => null,
              ],
             'getImages'
              => [
                  'name' => 'getImages',
                  'parameters'
                   => [
                   ],
                  'returnsReference' => false,
                  'returnType'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                       'data'
                        => [
                            'types'
                             => [
                                 0
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'Kreuzberg\\ImageExtractionConfig',
                                           'isIdentifier' => false,
                                       ],
                                  ],
                                 1
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'null',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                             ],
                        ],
                   ],
                  'attributes'
                   => [
                   ],
                  'docComment' => null,
                  'startLine' => 257,
                  'endLine' => 257,
                  'startColumn' => 9,
                  'endColumn' => 62,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'currentClassName' => 'Kreuzberg\\ExtractionConfig',
                  'aliasName' => null,
              ],
             'getPdfOptions'
              => [
                  'name' => 'getPdfOptions',
                  'parameters'
                   => [
                   ],
                  'returnsReference' => false,
                  'returnType'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                       'data'
                        => [
                            'types'
                             => [
                                 0
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'Kreuzberg\\PdfConfig',
                                           'isIdentifier' => false,
                                       ],
                                  ],
                                 1
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'null',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                             ],
                        ],
                   ],
                  'attributes'
                   => [
                   ],
                  'docComment' => null,
                  'startLine' => 258,
                  'endLine' => 258,
                  'startColumn' => 9,
                  'endColumn' => 54,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'currentClassName' => 'Kreuzberg\\ExtractionConfig',
                  'aliasName' => null,
              ],
             'getTokenReduction'
              => [
                  'name' => 'getTokenReduction',
                  'parameters'
                   => [
                   ],
                  'returnsReference' => false,
                  'returnType'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                       'data'
                        => [
                            'types'
                             => [
                                 0
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'Kreuzberg\\TokenReductionOptions',
                                           'isIdentifier' => false,
                                       ],
                                  ],
                                 1
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'null',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                             ],
                        ],
                   ],
                  'attributes'
                   => [
                   ],
                  'docComment' => null,
                  'startLine' => 259,
                  'endLine' => 259,
                  'startColumn' => 9,
                  'endColumn' => 70,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'currentClassName' => 'Kreuzberg\\ExtractionConfig',
                  'aliasName' => null,
              ],
             'getLanguageDetection'
              => [
                  'name' => 'getLanguageDetection',
                  'parameters'
                   => [
                   ],
                  'returnsReference' => false,
                  'returnType'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                       'data'
                        => [
                            'types'
                             => [
                                 0
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'Kreuzberg\\LanguageDetectionConfig',
                                           'isIdentifier' => false,
                                       ],
                                  ],
                                 1
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'null',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                             ],
                        ],
                   ],
                  'attributes'
                   => [
                   ],
                  'docComment' => null,
                  'startLine' => 260,
                  'endLine' => 260,
                  'startColumn' => 9,
                  'endColumn' => 75,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'currentClassName' => 'Kreuzberg\\ExtractionConfig',
                  'aliasName' => null,
              ],
             'getPages'
              => [
                  'name' => 'getPages',
                  'parameters'
                   => [
                   ],
                  'returnsReference' => false,
                  'returnType'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                       'data'
                        => [
                            'types'
                             => [
                                 0
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'Kreuzberg\\PageConfig',
                                           'isIdentifier' => false,
                                       ],
                                  ],
                                 1
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'null',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                             ],
                        ],
                   ],
                  'attributes'
                   => [
                   ],
                  'docComment' => null,
                  'startLine' => 261,
                  'endLine' => 261,
                  'startColumn' => 9,
                  'endColumn' => 50,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'currentClassName' => 'Kreuzberg\\ExtractionConfig',
                  'aliasName' => null,
              ],
             'getPostprocessor'
              => [
                  'name' => 'getPostprocessor',
                  'parameters'
                   => [
                   ],
                  'returnsReference' => false,
                  'returnType'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                       'data'
                        => [
                            'types'
                             => [
                                 0
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'Kreuzberg\\PostProcessorConfig',
                                           'isIdentifier' => false,
                                       ],
                                  ],
                                 1
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'null',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                             ],
                        ],
                   ],
                  'attributes'
                   => [
                   ],
                  'docComment' => null,
                  'startLine' => 262,
                  'endLine' => 262,
                  'startColumn' => 9,
                  'endColumn' => 67,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'currentClassName' => 'Kreuzberg\\ExtractionConfig',
                  'aliasName' => null,
              ],
             'getHtmlOptions'
              => [
                  'name' => 'getHtmlOptions',
                  'parameters'
                   => [
                   ],
                  'returnsReference' => false,
                  'returnType'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                       'data'
                        => [
                            'types'
                             => [
                                 0
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'string',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                                 1
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'null',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                             ],
                        ],
                   ],
                  'attributes'
                   => [
                   ],
                  'docComment' => null,
                  'startLine' => 263,
                  'endLine' => 263,
                  'startColumn' => 9,
                  'endColumn' => 52,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'currentClassName' => 'Kreuzberg\\ExtractionConfig',
                  'aliasName' => null,
              ],
             'getHtmlOutput'
              => [
                  'name' => 'getHtmlOutput',
                  'parameters'
                   => [
                   ],
                  'returnsReference' => false,
                  'returnType'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                       'data'
                        => [
                            'types'
                             => [
                                 0
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'Kreuzberg\\HtmlOutputConfig',
                                           'isIdentifier' => false,
                                       ],
                                  ],
                                 1
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'null',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                             ],
                        ],
                   ],
                  'attributes'
                   => [
                   ],
                  'docComment' => null,
                  'startLine' => 264,
                  'endLine' => 264,
                  'startColumn' => 9,
                  'endColumn' => 61,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'currentClassName' => 'Kreuzberg\\ExtractionConfig',
                  'aliasName' => null,
              ],
             'getExtractionTimeoutSecs'
              => [
                  'name' => 'getExtractionTimeoutSecs',
                  'parameters'
                   => [
                   ],
                  'returnsReference' => false,
                  'returnType'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                       'data'
                        => [
                            'types'
                             => [
                                 0
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'int',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                                 1
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'null',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                             ],
                        ],
                   ],
                  'attributes'
                   => [
                   ],
                  'docComment' => null,
                  'startLine' => 265,
                  'endLine' => 265,
                  'startColumn' => 9,
                  'endColumn' => 59,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'currentClassName' => 'Kreuzberg\\ExtractionConfig',
                  'aliasName' => null,
              ],
             'getMaxConcurrentExtractions'
              => [
                  'name' => 'getMaxConcurrentExtractions',
                  'parameters'
                   => [
                   ],
                  'returnsReference' => false,
                  'returnType'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                       'data'
                        => [
                            'types'
                             => [
                                 0
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'int',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                                 1
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'null',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                             ],
                        ],
                   ],
                  'attributes'
                   => [
                   ],
                  'docComment' => null,
                  'startLine' => 266,
                  'endLine' => 266,
                  'startColumn' => 9,
                  'endColumn' => 62,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'currentClassName' => 'Kreuzberg\\ExtractionConfig',
                  'aliasName' => null,
              ],
             'getResultFormat'
              => [
                  'name' => 'getResultFormat',
                  'parameters'
                   => [
                   ],
                  'returnsReference' => false,
                  'returnType'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                       'data'
                        => [
                            'name' => 'string',
                            'isIdentifier' => true,
                        ],
                   ],
                  'attributes'
                   => [
                   ],
                  'docComment' => null,
                  'startLine' => 267,
                  'endLine' => 267,
                  'startColumn' => 9,
                  'endColumn' => 52,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'currentClassName' => 'Kreuzberg\\ExtractionConfig',
                  'aliasName' => null,
              ],
             'getSecurityLimits'
              => [
                  'name' => 'getSecurityLimits',
                  'parameters'
                   => [
                   ],
                  'returnsReference' => false,
                  'returnType'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                       'data'
                        => [
                            'types'
                             => [
                                 0
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'string',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                                 1
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'null',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                             ],
                        ],
                   ],
                  'attributes'
                   => [
                   ],
                  'docComment' => null,
                  'startLine' => 268,
                  'endLine' => 268,
                  'startColumn' => 9,
                  'endColumn' => 55,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'currentClassName' => 'Kreuzberg\\ExtractionConfig',
                  'aliasName' => null,
              ],
             'getOutputFormat'
              => [
                  'name' => 'getOutputFormat',
                  'parameters'
                   => [
                   ],
                  'returnsReference' => false,
                  'returnType'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                       'data'
                        => [
                            'name' => 'string',
                            'isIdentifier' => true,
                        ],
                   ],
                  'attributes'
                   => [
                   ],
                  'docComment' => null,
                  'startLine' => 269,
                  'endLine' => 269,
                  'startColumn' => 9,
                  'endColumn' => 52,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'currentClassName' => 'Kreuzberg\\ExtractionConfig',
                  'aliasName' => null,
              ],
             'getLayout'
              => [
                  'name' => 'getLayout',
                  'parameters'
                   => [
                   ],
                  'returnsReference' => false,
                  'returnType'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                       'data'
                        => [
                            'types'
                             => [
                                 0
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'Kreuzberg\\LayoutDetectionConfig',
                                           'isIdentifier' => false,
                                       ],
                                  ],
                                 1
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'null',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                             ],
                        ],
                   ],
                  'attributes'
                   => [
                   ],
                  'docComment' => null,
                  'startLine' => 270,
                  'endLine' => 270,
                  'startColumn' => 9,
                  'endColumn' => 62,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'currentClassName' => 'Kreuzberg\\ExtractionConfig',
                  'aliasName' => null,
              ],
             'getIncludeDocumentStructure'
              => [
                  'name' => 'getIncludeDocumentStructure',
                  'parameters'
                   => [
                   ],
                  'returnsReference' => false,
                  'returnType'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                       'data'
                        => [
                            'name' => 'bool',
                            'isIdentifier' => true,
                        ],
                   ],
                  'attributes'
                   => [
                   ],
                  'docComment' => null,
                  'startLine' => 271,
                  'endLine' => 271,
                  'startColumn' => 9,
                  'endColumn' => 62,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'currentClassName' => 'Kreuzberg\\ExtractionConfig',
                  'aliasName' => null,
              ],
             'getAcceleration'
              => [
                  'name' => 'getAcceleration',
                  'parameters'
                   => [
                   ],
                  'returnsReference' => false,
                  'returnType'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                       'data'
                        => [
                            'types'
                             => [
                                 0
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'Kreuzberg\\AccelerationConfig',
                                           'isIdentifier' => false,
                                       ],
                                  ],
                                 1
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'null',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                             ],
                        ],
                   ],
                  'attributes'
                   => [
                   ],
                  'docComment' => null,
                  'startLine' => 272,
                  'endLine' => 272,
                  'startColumn' => 9,
                  'endColumn' => 65,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'currentClassName' => 'Kreuzberg\\ExtractionConfig',
                  'aliasName' => null,
              ],
             'getCacheNamespace'
              => [
                  'name' => 'getCacheNamespace',
                  'parameters'
                   => [
                   ],
                  'returnsReference' => false,
                  'returnType'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                       'data'
                        => [
                            'types'
                             => [
                                 0
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'string',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                                 1
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'null',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                             ],
                        ],
                   ],
                  'attributes'
                   => [
                   ],
                  'docComment' => null,
                  'startLine' => 273,
                  'endLine' => 273,
                  'startColumn' => 9,
                  'endColumn' => 55,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'currentClassName' => 'Kreuzberg\\ExtractionConfig',
                  'aliasName' => null,
              ],
             'getCacheTtlSecs'
              => [
                  'name' => 'getCacheTtlSecs',
                  'parameters'
                   => [
                   ],
                  'returnsReference' => false,
                  'returnType'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                       'data'
                        => [
                            'types'
                             => [
                                 0
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'int',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                                 1
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'null',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                             ],
                        ],
                   ],
                  'attributes'
                   => [
                   ],
                  'docComment' => null,
                  'startLine' => 274,
                  'endLine' => 274,
                  'startColumn' => 9,
                  'endColumn' => 50,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'currentClassName' => 'Kreuzberg\\ExtractionConfig',
                  'aliasName' => null,
              ],
             'getEmail'
              => [
                  'name' => 'getEmail',
                  'parameters'
                   => [
                   ],
                  'returnsReference' => false,
                  'returnType'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                       'data'
                        => [
                            'types'
                             => [
                                 0
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'Kreuzberg\\EmailConfig',
                                           'isIdentifier' => false,
                                       ],
                                  ],
                                 1
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'null',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                             ],
                        ],
                   ],
                  'attributes'
                   => [
                   ],
                  'docComment' => null,
                  'startLine' => 275,
                  'endLine' => 275,
                  'startColumn' => 9,
                  'endColumn' => 51,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'currentClassName' => 'Kreuzberg\\ExtractionConfig',
                  'aliasName' => null,
              ],
             'getConcurrency'
              => [
                  'name' => 'getConcurrency',
                  'parameters'
                   => [
                   ],
                  'returnsReference' => false,
                  'returnType'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                       'data'
                        => [
                            'types'
                             => [
                                 0
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'string',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                                 1
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'null',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                             ],
                        ],
                   ],
                  'attributes'
                   => [
                   ],
                  'docComment' => null,
                  'startLine' => 276,
                  'endLine' => 276,
                  'startColumn' => 9,
                  'endColumn' => 52,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'currentClassName' => 'Kreuzberg\\ExtractionConfig',
                  'aliasName' => null,
              ],
             'getMaxArchiveDepth'
              => [
                  'name' => 'getMaxArchiveDepth',
                  'parameters'
                   => [
                   ],
                  'returnsReference' => false,
                  'returnType'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                       'data'
                        => [
                            'name' => 'int',
                            'isIdentifier' => true,
                        ],
                   ],
                  'attributes'
                   => [
                   ],
                  'docComment' => null,
                  'startLine' => 277,
                  'endLine' => 277,
                  'startColumn' => 9,
                  'endColumn' => 52,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'currentClassName' => 'Kreuzberg\\ExtractionConfig',
                  'aliasName' => null,
              ],
             'getTreeSitter'
              => [
                  'name' => 'getTreeSitter',
                  'parameters'
                   => [
                   ],
                  'returnsReference' => false,
                  'returnType'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                       'data'
                        => [
                            'types'
                             => [
                                 0
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'Kreuzberg\\TreeSitterConfig',
                                           'isIdentifier' => false,
                                       ],
                                  ],
                                 1
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'null',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                             ],
                        ],
                   ],
                  'attributes'
                   => [
                   ],
                  'docComment' => null,
                  'startLine' => 278,
                  'endLine' => 278,
                  'startColumn' => 9,
                  'endColumn' => 61,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'currentClassName' => 'Kreuzberg\\ExtractionConfig',
                  'aliasName' => null,
              ],
             'getStructuredExtraction'
              => [
                  'name' => 'getStructuredExtraction',
                  'parameters'
                   => [
                   ],
                  'returnsReference' => false,
                  'returnType'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                       'data'
                        => [
                            'types'
                             => [
                                 0
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'Kreuzberg\\StructuredExtractionConfig',
                                           'isIdentifier' => false,
                                       ],
                                  ],
                                 1
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'null',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                             ],
                        ],
                   ],
                  'attributes'
                   => [
                   ],
                  'docComment' => null,
                  'startLine' => 279,
                  'endLine' => 279,
                  'startColumn' => 9,
                  'endColumn' => 81,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'currentClassName' => 'Kreuzberg\\ExtractionConfig',
                  'aliasName' => null,
              ],
             'getCancelToken'
              => [
                  'name' => 'getCancelToken',
                  'parameters'
                   => [
                   ],
                  'returnsReference' => false,
                  'returnType'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
                       'data'
                        => [
                            'types'
                             => [
                                 0
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'string',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                                 1
                                  => [
                                      'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                      'data'
                                       => [
                                           'name' => 'null',
                                           'isIdentifier' => true,
                                       ],
                                  ],
                             ],
                        ],
                   ],
                  'attributes'
                   => [
                   ],
                  'docComment' => null,
                  'startLine' => 280,
                  'endLine' => 280,
                  'startColumn' => 9,
                  'endColumn' => 52,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ExtractionConfig',
                  'implementingClassName' => 'Kreuzberg\\ExtractionConfig',
                  'currentClassName' => 'Kreuzberg\\ExtractionConfig',
                  'aliasName' => null,
              ],
         ],
        'traitsData'
         => [
             'aliases'
              => [
              ],
             'modifiers'
              => [
              ],
             'precedences'
              => [
              ],
             'hashes'
              => [
              ],
         ],
    ],
]);
