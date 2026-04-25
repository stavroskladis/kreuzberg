<?php

declare(strict_types=1);

// osfsl-/Users/naamanhirschfeld/workspace/kreuzberg-dev/kreuzberg/packages/php/stubs/kreuzberg_extension.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Kreuzberg\ExtractionResult
return \PHPStan\Cache\CacheItem::__set_state([
    'variableKey' => 'v2-3119307c419b0b68ce5de99f015c90205808f8d7e877aa5ace59d62568086491-8.4.20-6.70.0.0',
    'data'
    => [
        'locatedSource'
         => [
             'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
             'data'
              => [
                  'name' => 'Kreuzberg\\ExtractionResult',
                  'filename' => '/Users/naamanhirschfeld/workspace/kreuzberg-dev/kreuzberg/packages/php/stubs/kreuzberg_extension.php',
              ],
         ],
        'namespace' => 'Kreuzberg',
        'name' => 'Kreuzberg\\ExtractionResult',
        'shortName' => 'ExtractionResult',
        'isInterface' => false,
        'isTrait' => false,
        'isEnum' => false,
        'isBackedEnum' => false,
        'modifiers' => 0,
        'docComment' => '/**
 * General extraction result used by the core extraction API.
 *
 * This is the main result type returned by all extraction functions.
 */',
        'attributes'
         => [
         ],
        'startLine' => 2118,
        'endLine' => 2228,
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
             'content'
              => [
                  'declaringClassName' => 'Kreuzberg\\ExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\ExtractionResult',
                  'name' => 'content',
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
                  'startLine' => 2120,
                  'endLine' => 2120,
                  'startColumn' => 9,
                  'endColumn' => 31,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'mime_type'
              => [
                  'declaringClassName' => 'Kreuzberg\\ExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\ExtractionResult',
                  'name' => 'mime_type',
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
                  'startLine' => 2121,
                  'endLine' => 2121,
                  'startColumn' => 9,
                  'endColumn' => 33,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'metadata'
              => [
                  'declaringClassName' => 'Kreuzberg\\ExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\ExtractionResult',
                  'name' => 'metadata',
                  'modifiers' => 1,
                  'type'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                       'data'
                        => [
                            'name' => 'Kreuzberg\\Metadata',
                            'isIdentifier' => false,
                        ],
                   ],
                  'default' => null,
                  'docComment' => null,
                  'attributes'
                   => [
                   ],
                  'startLine' => 2122,
                  'endLine' => 2122,
                  'startColumn' => 9,
                  'endColumn' => 34,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'tables'
              => [
                  'declaringClassName' => 'Kreuzberg\\ExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\ExtractionResult',
                  'name' => 'tables',
                  'modifiers' => 1,
                  'type'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                       'data'
                        => [
                            'name' => 'array',
                            'isIdentifier' => true,
                        ],
                   ],
                  'default' => null,
                  'docComment' => '/** @var array<string> */',
                  'attributes'
                   => [
                   ],
                  'startLine' => 2124,
                  'endLine' => 2124,
                  'startColumn' => 9,
                  'endColumn' => 29,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'detected_languages'
              => [
                  'declaringClassName' => 'Kreuzberg\\ExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\ExtractionResult',
                  'name' => 'detected_languages',
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
                  'docComment' => '/** @var ?array<string> */',
                  'attributes'
                   => [
                   ],
                  'startLine' => 2126,
                  'endLine' => 2126,
                  'startColumn' => 9,
                  'endColumn' => 42,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'chunks'
              => [
                  'declaringClassName' => 'Kreuzberg\\ExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\ExtractionResult',
                  'name' => 'chunks',
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
                  'docComment' => '/** @var ?array<Chunk> */',
                  'attributes'
                   => [
                   ],
                  'startLine' => 2128,
                  'endLine' => 2128,
                  'startColumn' => 9,
                  'endColumn' => 30,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'images'
              => [
                  'declaringClassName' => 'Kreuzberg\\ExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\ExtractionResult',
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
                  'docComment' => '/** @var ?array<ExtractedImage> */',
                  'attributes'
                   => [
                   ],
                  'startLine' => 2130,
                  'endLine' => 2130,
                  'startColumn' => 9,
                  'endColumn' => 30,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'pages'
              => [
                  'declaringClassName' => 'Kreuzberg\\ExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\ExtractionResult',
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
                  'docComment' => '/** @var ?array<PageContent> */',
                  'attributes'
                   => [
                   ],
                  'startLine' => 2132,
                  'endLine' => 2132,
                  'startColumn' => 9,
                  'endColumn' => 29,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'elements'
              => [
                  'declaringClassName' => 'Kreuzberg\\ExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\ExtractionResult',
                  'name' => 'elements',
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
                  'docComment' => '/** @var ?array<Element> */',
                  'attributes'
                   => [
                   ],
                  'startLine' => 2134,
                  'endLine' => 2134,
                  'startColumn' => 9,
                  'endColumn' => 32,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'djot_content'
              => [
                  'declaringClassName' => 'Kreuzberg\\ExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\ExtractionResult',
                  'name' => 'djot_content',
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
                                           'name' => 'Kreuzberg\\DjotContent',
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
                  'startLine' => 2135,
                  'endLine' => 2135,
                  'startColumn' => 9,
                  'endColumn' => 42,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'ocr_elements'
              => [
                  'declaringClassName' => 'Kreuzberg\\ExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\ExtractionResult',
                  'name' => 'ocr_elements',
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
                  'docComment' => '/** @var ?array<OcrElement> */',
                  'attributes'
                   => [
                   ],
                  'startLine' => 2137,
                  'endLine' => 2137,
                  'startColumn' => 9,
                  'endColumn' => 36,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'document'
              => [
                  'declaringClassName' => 'Kreuzberg\\ExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\ExtractionResult',
                  'name' => 'document',
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
                                           'name' => 'Kreuzberg\\DocumentStructure',
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
                  'startLine' => 2138,
                  'endLine' => 2138,
                  'startColumn' => 9,
                  'endColumn' => 44,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'quality_score'
              => [
                  'declaringClassName' => 'Kreuzberg\\ExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\ExtractionResult',
                  'name' => 'quality_score',
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
                                           'name' => 'float',
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
                  'startLine' => 2139,
                  'endLine' => 2139,
                  'startColumn' => 9,
                  'endColumn' => 37,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'processing_warnings'
              => [
                  'declaringClassName' => 'Kreuzberg\\ExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\ExtractionResult',
                  'name' => 'processing_warnings',
                  'modifiers' => 1,
                  'type'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                       'data'
                        => [
                            'name' => 'array',
                            'isIdentifier' => true,
                        ],
                   ],
                  'default' => null,
                  'docComment' => '/** @var array<ProcessingWarning> */',
                  'attributes'
                   => [
                   ],
                  'startLine' => 2141,
                  'endLine' => 2141,
                  'startColumn' => 9,
                  'endColumn' => 42,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'annotations'
              => [
                  'declaringClassName' => 'Kreuzberg\\ExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\ExtractionResult',
                  'name' => 'annotations',
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
                  'docComment' => '/** @var ?array<PdfAnnotation> */',
                  'attributes'
                   => [
                   ],
                  'startLine' => 2143,
                  'endLine' => 2143,
                  'startColumn' => 9,
                  'endColumn' => 35,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'children'
              => [
                  'declaringClassName' => 'Kreuzberg\\ExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\ExtractionResult',
                  'name' => 'children',
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
                  'docComment' => '/** @var ?array<ArchiveEntry> */',
                  'attributes'
                   => [
                   ],
                  'startLine' => 2145,
                  'endLine' => 2145,
                  'startColumn' => 9,
                  'endColumn' => 32,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'uris'
              => [
                  'declaringClassName' => 'Kreuzberg\\ExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\ExtractionResult',
                  'name' => 'uris',
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
                  'docComment' => '/** @var ?array<Uri> */',
                  'attributes'
                   => [
                   ],
                  'startLine' => 2147,
                  'endLine' => 2147,
                  'startColumn' => 9,
                  'endColumn' => 28,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'structured_output'
              => [
                  'declaringClassName' => 'Kreuzberg\\ExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\ExtractionResult',
                  'name' => 'structured_output',
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
                  'startLine' => 2148,
                  'endLine' => 2148,
                  'startColumn' => 9,
                  'endColumn' => 42,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'code_intelligence'
              => [
                  'declaringClassName' => 'Kreuzberg\\ExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\ExtractionResult',
                  'name' => 'code_intelligence',
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
                  'startLine' => 2149,
                  'endLine' => 2149,
                  'startColumn' => 9,
                  'endColumn' => 42,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'llm_usage'
              => [
                  'declaringClassName' => 'Kreuzberg\\ExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\ExtractionResult',
                  'name' => 'llm_usage',
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
                  'docComment' => '/** @var ?array<LlmUsage> */',
                  'attributes'
                   => [
                   ],
                  'startLine' => 2151,
                  'endLine' => 2151,
                  'startColumn' => 9,
                  'endColumn' => 33,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'formatted_content'
              => [
                  'declaringClassName' => 'Kreuzberg\\ExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\ExtractionResult',
                  'name' => 'formatted_content',
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
                  'startLine' => 2152,
                  'endLine' => 2152,
                  'startColumn' => 9,
                  'endColumn' => 42,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'ocr_internal_document'
              => [
                  'declaringClassName' => 'Kreuzberg\\ExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\ExtractionResult',
                  'name' => 'ocr_internal_document',
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
                  'startLine' => 2153,
                  'endLine' => 2153,
                  'startColumn' => 9,
                  'endColumn' => 46,
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
                       'content'
                        => [
                            'name' => 'content',
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
                            'startLine' => 2170,
                            'endLine' => 2170,
                            'startColumn' => 13,
                            'endColumn' => 27,
                            'parameterIndex' => 0,
                            'isOptional' => false,
                        ],
                       'mime_type'
                        => [
                            'name' => 'mime_type',
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
                            'startLine' => 2171,
                            'endLine' => 2171,
                            'startColumn' => 13,
                            'endColumn' => 29,
                            'parameterIndex' => 1,
                            'isOptional' => false,
                        ],
                       'metadata'
                        => [
                            'name' => 'metadata',
                            'default' => null,
                            'type'
                             => [
                                 'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                 'data'
                                  => [
                                      'name' => 'Kreuzberg\\Metadata',
                                      'isIdentifier' => false,
                                  ],
                             ],
                            'isVariadic' => false,
                            'byRef' => false,
                            'isPromoted' => false,
                            'attributes'
                             => [
                             ],
                            'startLine' => 2172,
                            'endLine' => 2172,
                            'startColumn' => 13,
                            'endColumn' => 30,
                            'parameterIndex' => 2,
                            'isOptional' => false,
                        ],
                       'tables'
                        => [
                            'name' => 'tables',
                            'default' => null,
                            'type'
                             => [
                                 'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                 'data'
                                  => [
                                      'name' => 'array',
                                      'isIdentifier' => true,
                                  ],
                             ],
                            'isVariadic' => false,
                            'byRef' => false,
                            'isPromoted' => false,
                            'attributes'
                             => [
                             ],
                            'startLine' => 2173,
                            'endLine' => 2173,
                            'startColumn' => 13,
                            'endColumn' => 25,
                            'parameterIndex' => 3,
                            'isOptional' => false,
                        ],
                       'processing_warnings'
                        => [
                            'name' => 'processing_warnings',
                            'default' => null,
                            'type'
                             => [
                                 'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                 'data'
                                  => [
                                      'name' => 'array',
                                      'isIdentifier' => true,
                                  ],
                             ],
                            'isVariadic' => false,
                            'byRef' => false,
                            'isPromoted' => false,
                            'attributes'
                             => [
                             ],
                            'startLine' => 2174,
                            'endLine' => 2174,
                            'startColumn' => 13,
                            'endColumn' => 38,
                            'parameterIndex' => 4,
                            'isOptional' => false,
                        ],
                       'detected_languages'
                        => [
                            'name' => 'detected_languages',
                            'default'
                             => [
                                 'code' => 'null',
                                 'attributes'
                                  => [
                                      'startLine' => 2175,
                                      'endLine' => 2175,
                                      'startTokenPos' => 12324,
                                      'startFilePos' => 72029,
                                      'endTokenPos' => 12324,
                                      'endFilePos' => 72032,
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
                            'startLine' => 2175,
                            'endLine' => 2175,
                            'startColumn' => 13,
                            'endColumn' => 45,
                            'parameterIndex' => 5,
                            'isOptional' => true,
                        ],
                       'chunks'
                        => [
                            'name' => 'chunks',
                            'default'
                             => [
                                 'code' => 'null',
                                 'attributes'
                                  => [
                                      'startLine' => 2176,
                                      'endLine' => 2176,
                                      'startTokenPos' => 12334,
                                      'startFilePos' => 72064,
                                      'endTokenPos' => 12334,
                                      'endFilePos' => 72067,
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
                            'startLine' => 2176,
                            'endLine' => 2176,
                            'startColumn' => 13,
                            'endColumn' => 33,
                            'parameterIndex' => 6,
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
                                      'startLine' => 2177,
                                      'endLine' => 2177,
                                      'startTokenPos' => 12344,
                                      'startFilePos' => 72099,
                                      'endTokenPos' => 12344,
                                      'endFilePos' => 72102,
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
                            'startLine' => 2177,
                            'endLine' => 2177,
                            'startColumn' => 13,
                            'endColumn' => 33,
                            'parameterIndex' => 7,
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
                                      'startLine' => 2178,
                                      'endLine' => 2178,
                                      'startTokenPos' => 12354,
                                      'startFilePos' => 72133,
                                      'endTokenPos' => 12354,
                                      'endFilePos' => 72136,
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
                            'startLine' => 2178,
                            'endLine' => 2178,
                            'startColumn' => 13,
                            'endColumn' => 32,
                            'parameterIndex' => 8,
                            'isOptional' => true,
                        ],
                       'elements'
                        => [
                            'name' => 'elements',
                            'default'
                             => [
                                 'code' => 'null',
                                 'attributes'
                                  => [
                                      'startLine' => 2179,
                                      'endLine' => 2179,
                                      'startTokenPos' => 12364,
                                      'startFilePos' => 72170,
                                      'endTokenPos' => 12364,
                                      'endFilePos' => 72173,
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
                            'startLine' => 2179,
                            'endLine' => 2179,
                            'startColumn' => 13,
                            'endColumn' => 35,
                            'parameterIndex' => 9,
                            'isOptional' => true,
                        ],
                       'djot_content'
                        => [
                            'name' => 'djot_content',
                            'default'
                             => [
                                 'code' => 'null',
                                 'attributes'
                                  => [
                                      'startLine' => 2180,
                                      'endLine' => 2180,
                                      'startTokenPos' => 12374,
                                      'startFilePos' => 72217,
                                      'endTokenPos' => 12374,
                                      'endFilePos' => 72220,
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
                                                     'name' => 'Kreuzberg\\DjotContent',
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
                            'startLine' => 2180,
                            'endLine' => 2180,
                            'startColumn' => 13,
                            'endColumn' => 45,
                            'parameterIndex' => 10,
                            'isOptional' => true,
                        ],
                       'ocr_elements'
                        => [
                            'name' => 'ocr_elements',
                            'default'
                             => [
                                 'code' => 'null',
                                 'attributes'
                                  => [
                                      'startLine' => 2181,
                                      'endLine' => 2181,
                                      'startTokenPos' => 12384,
                                      'startFilePos' => 72258,
                                      'endTokenPos' => 12384,
                                      'endFilePos' => 72261,
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
                            'startLine' => 2181,
                            'endLine' => 2181,
                            'startColumn' => 13,
                            'endColumn' => 39,
                            'parameterIndex' => 11,
                            'isOptional' => true,
                        ],
                       'document'
                        => [
                            'name' => 'document',
                            'default'
                             => [
                                 'code' => 'null',
                                 'attributes'
                                  => [
                                      'startLine' => 2182,
                                      'endLine' => 2182,
                                      'startTokenPos' => 12394,
                                      'startFilePos' => 72307,
                                      'endTokenPos' => 12394,
                                      'endFilePos' => 72310,
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
                                                     'name' => 'Kreuzberg\\DocumentStructure',
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
                            'startLine' => 2182,
                            'endLine' => 2182,
                            'startColumn' => 13,
                            'endColumn' => 47,
                            'parameterIndex' => 12,
                            'isOptional' => true,
                        ],
                       'quality_score'
                        => [
                            'name' => 'quality_score',
                            'default'
                             => [
                                 'code' => 'null',
                                 'attributes'
                                  => [
                                      'startLine' => 2183,
                                      'endLine' => 2183,
                                      'startTokenPos' => 12404,
                                      'startFilePos' => 72349,
                                      'endTokenPos' => 12404,
                                      'endFilePos' => 72352,
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
                                                     'name' => 'float',
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
                            'startLine' => 2183,
                            'endLine' => 2183,
                            'startColumn' => 13,
                            'endColumn' => 40,
                            'parameterIndex' => 13,
                            'isOptional' => true,
                        ],
                       'annotations'
                        => [
                            'name' => 'annotations',
                            'default'
                             => [
                                 'code' => 'null',
                                 'attributes'
                                  => [
                                      'startLine' => 2184,
                                      'endLine' => 2184,
                                      'startTokenPos' => 12414,
                                      'startFilePos' => 72389,
                                      'endTokenPos' => 12414,
                                      'endFilePos' => 72392,
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
                            'startLine' => 2184,
                            'endLine' => 2184,
                            'startColumn' => 13,
                            'endColumn' => 38,
                            'parameterIndex' => 14,
                            'isOptional' => true,
                        ],
                       'children'
                        => [
                            'name' => 'children',
                            'default'
                             => [
                                 'code' => 'null',
                                 'attributes'
                                  => [
                                      'startLine' => 2185,
                                      'endLine' => 2185,
                                      'startTokenPos' => 12424,
                                      'startFilePos' => 72426,
                                      'endTokenPos' => 12424,
                                      'endFilePos' => 72429,
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
                            'startLine' => 2185,
                            'endLine' => 2185,
                            'startColumn' => 13,
                            'endColumn' => 35,
                            'parameterIndex' => 15,
                            'isOptional' => true,
                        ],
                       'uris'
                        => [
                            'name' => 'uris',
                            'default'
                             => [
                                 'code' => 'null',
                                 'attributes'
                                  => [
                                      'startLine' => 2186,
                                      'endLine' => 2186,
                                      'startTokenPos' => 12434,
                                      'startFilePos' => 72459,
                                      'endTokenPos' => 12434,
                                      'endFilePos' => 72462,
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
                            'startLine' => 2186,
                            'endLine' => 2186,
                            'startColumn' => 13,
                            'endColumn' => 31,
                            'parameterIndex' => 16,
                            'isOptional' => true,
                        ],
                       'structured_output'
                        => [
                            'name' => 'structured_output',
                            'default'
                             => [
                                 'code' => 'null',
                                 'attributes'
                                  => [
                                      'startLine' => 2187,
                                      'endLine' => 2187,
                                      'startTokenPos' => 12444,
                                      'startFilePos' => 72506,
                                      'endTokenPos' => 12444,
                                      'endFilePos' => 72509,
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
                            'startLine' => 2187,
                            'endLine' => 2187,
                            'startColumn' => 13,
                            'endColumn' => 45,
                            'parameterIndex' => 17,
                            'isOptional' => true,
                        ],
                       'code_intelligence'
                        => [
                            'name' => 'code_intelligence',
                            'default'
                             => [
                                 'code' => 'null',
                                 'attributes'
                                  => [
                                      'startLine' => 2188,
                                      'endLine' => 2188,
                                      'startTokenPos' => 12454,
                                      'startFilePos' => 72553,
                                      'endTokenPos' => 12454,
                                      'endFilePos' => 72556,
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
                            'startLine' => 2188,
                            'endLine' => 2188,
                            'startColumn' => 13,
                            'endColumn' => 45,
                            'parameterIndex' => 18,
                            'isOptional' => true,
                        ],
                       'llm_usage'
                        => [
                            'name' => 'llm_usage',
                            'default'
                             => [
                                 'code' => 'null',
                                 'attributes'
                                  => [
                                      'startLine' => 2189,
                                      'endLine' => 2189,
                                      'startTokenPos' => 12464,
                                      'startFilePos' => 72591,
                                      'endTokenPos' => 12464,
                                      'endFilePos' => 72594,
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
                            'startLine' => 2189,
                            'endLine' => 2189,
                            'startColumn' => 13,
                            'endColumn' => 36,
                            'parameterIndex' => 19,
                            'isOptional' => true,
                        ],
                       'formatted_content'
                        => [
                            'name' => 'formatted_content',
                            'default'
                             => [
                                 'code' => 'null',
                                 'attributes'
                                  => [
                                      'startLine' => 2190,
                                      'endLine' => 2190,
                                      'startTokenPos' => 12474,
                                      'startFilePos' => 72638,
                                      'endTokenPos' => 12474,
                                      'endFilePos' => 72641,
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
                            'startLine' => 2190,
                            'endLine' => 2190,
                            'startColumn' => 13,
                            'endColumn' => 45,
                            'parameterIndex' => 20,
                            'isOptional' => true,
                        ],
                       'ocr_internal_document'
                        => [
                            'name' => 'ocr_internal_document',
                            'default'
                             => [
                                 'code' => 'null',
                                 'attributes'
                                  => [
                                      'startLine' => 2191,
                                      'endLine' => 2191,
                                      'startTokenPos' => 12484,
                                      'startFilePos' => 72689,
                                      'endTokenPos' => 12484,
                                      'endFilePos' => 72692,
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
                            'startLine' => 2191,
                            'endLine' => 2191,
                            'startColumn' => 13,
                            'endColumn' => 49,
                            'parameterIndex' => 21,
                            'isOptional' => true,
                        ],
                   ],
                  'returnsReference' => false,
                  'returnType' => null,
                  'attributes'
                   => [
                   ],
                  'docComment' => '/**
 * @param array<string> $tables
 * @param array<ProcessingWarning> $processing_warnings
 * @param ?array<string> $detected_languages
 * @param ?array<Chunk> $chunks
 * @param ?array<ExtractedImage> $images
 * @param ?array<PageContent> $pages
 * @param ?array<Element> $elements
 * @param ?array<OcrElement> $ocr_elements
 * @param ?array<PdfAnnotation> $annotations
 * @param ?array<ArchiveEntry> $children
 * @param ?array<Uri> $uris
 * @param ?array<LlmUsage> $llm_usage
 */',
                  'startLine' => 2169,
                  'endLine' => 2192,
                  'startColumn' => 9,
                  'endColumn' => 12,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\ExtractionResult',
                  'currentClassName' => 'Kreuzberg\\ExtractionResult',
                  'aliasName' => null,
              ],
             'getContent'
              => [
                  'name' => 'getContent',
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
                  'startLine' => 2194,
                  'endLine' => 2194,
                  'startColumn' => 9,
                  'endColumn' => 47,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\ExtractionResult',
                  'currentClassName' => 'Kreuzberg\\ExtractionResult',
                  'aliasName' => null,
              ],
             'getMimeType'
              => [
                  'name' => 'getMimeType',
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
                  'startLine' => 2195,
                  'endLine' => 2195,
                  'startColumn' => 9,
                  'endColumn' => 48,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\ExtractionResult',
                  'currentClassName' => 'Kreuzberg\\ExtractionResult',
                  'aliasName' => null,
              ],
             'getMetadata'
              => [
                  'name' => 'getMetadata',
                  'parameters'
                   => [
                   ],
                  'returnsReference' => false,
                  'returnType'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                       'data'
                        => [
                            'name' => 'Kreuzberg\\Metadata',
                            'isIdentifier' => false,
                        ],
                   ],
                  'attributes'
                   => [
                   ],
                  'docComment' => null,
                  'startLine' => 2196,
                  'endLine' => 2196,
                  'startColumn' => 9,
                  'endColumn' => 50,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\ExtractionResult',
                  'currentClassName' => 'Kreuzberg\\ExtractionResult',
                  'aliasName' => null,
              ],
             'getTables'
              => [
                  'name' => 'getTables',
                  'parameters'
                   => [
                   ],
                  'returnsReference' => false,
                  'returnType'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                       'data'
                        => [
                            'name' => 'array',
                            'isIdentifier' => true,
                        ],
                   ],
                  'attributes'
                   => [
                   ],
                  'docComment' => '/** @return array<string> */',
                  'startLine' => 2198,
                  'endLine' => 2198,
                  'startColumn' => 9,
                  'endColumn' => 45,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\ExtractionResult',
                  'currentClassName' => 'Kreuzberg\\ExtractionResult',
                  'aliasName' => null,
              ],
             'getDetectedLanguages'
              => [
                  'name' => 'getDetectedLanguages',
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
                  'docComment' => '/** @return ?array<string> */',
                  'startLine' => 2200,
                  'endLine' => 2200,
                  'startColumn' => 9,
                  'endColumn' => 57,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\ExtractionResult',
                  'currentClassName' => 'Kreuzberg\\ExtractionResult',
                  'aliasName' => null,
              ],
             'getChunks'
              => [
                  'name' => 'getChunks',
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
                  'docComment' => '/** @return ?array<Chunk> */',
                  'startLine' => 2202,
                  'endLine' => 2202,
                  'startColumn' => 9,
                  'endColumn' => 46,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\ExtractionResult',
                  'currentClassName' => 'Kreuzberg\\ExtractionResult',
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
                  'docComment' => '/** @return ?array<ExtractedImage> */',
                  'startLine' => 2204,
                  'endLine' => 2204,
                  'startColumn' => 9,
                  'endColumn' => 46,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\ExtractionResult',
                  'currentClassName' => 'Kreuzberg\\ExtractionResult',
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
                  'docComment' => '/** @return ?array<PageContent> */',
                  'startLine' => 2206,
                  'endLine' => 2206,
                  'startColumn' => 9,
                  'endColumn' => 45,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\ExtractionResult',
                  'currentClassName' => 'Kreuzberg\\ExtractionResult',
                  'aliasName' => null,
              ],
             'getElements'
              => [
                  'name' => 'getElements',
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
                  'docComment' => '/** @return ?array<Element> */',
                  'startLine' => 2208,
                  'endLine' => 2208,
                  'startColumn' => 9,
                  'endColumn' => 48,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\ExtractionResult',
                  'currentClassName' => 'Kreuzberg\\ExtractionResult',
                  'aliasName' => null,
              ],
             'getDjotContent'
              => [
                  'name' => 'getDjotContent',
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
                                           'name' => 'Kreuzberg\\DjotContent',
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
                  'startLine' => 2209,
                  'endLine' => 2209,
                  'startColumn' => 9,
                  'endColumn' => 57,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\ExtractionResult',
                  'currentClassName' => 'Kreuzberg\\ExtractionResult',
                  'aliasName' => null,
              ],
             'getOcrElements'
              => [
                  'name' => 'getOcrElements',
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
                  'docComment' => '/** @return ?array<OcrElement> */',
                  'startLine' => 2211,
                  'endLine' => 2211,
                  'startColumn' => 9,
                  'endColumn' => 51,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\ExtractionResult',
                  'currentClassName' => 'Kreuzberg\\ExtractionResult',
                  'aliasName' => null,
              ],
             'getDocument'
              => [
                  'name' => 'getDocument',
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
                                           'name' => 'Kreuzberg\\DocumentStructure',
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
                  'startLine' => 2212,
                  'endLine' => 2212,
                  'startColumn' => 9,
                  'endColumn' => 60,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\ExtractionResult',
                  'currentClassName' => 'Kreuzberg\\ExtractionResult',
                  'aliasName' => null,
              ],
             'getQualityScore'
              => [
                  'name' => 'getQualityScore',
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
                                           'name' => 'float',
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
                  'startLine' => 2213,
                  'endLine' => 2213,
                  'startColumn' => 9,
                  'endColumn' => 52,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\ExtractionResult',
                  'currentClassName' => 'Kreuzberg\\ExtractionResult',
                  'aliasName' => null,
              ],
             'getProcessingWarnings'
              => [
                  'name' => 'getProcessingWarnings',
                  'parameters'
                   => [
                   ],
                  'returnsReference' => false,
                  'returnType'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                       'data'
                        => [
                            'name' => 'array',
                            'isIdentifier' => true,
                        ],
                   ],
                  'attributes'
                   => [
                   ],
                  'docComment' => '/** @return array<ProcessingWarning> */',
                  'startLine' => 2215,
                  'endLine' => 2215,
                  'startColumn' => 9,
                  'endColumn' => 57,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\ExtractionResult',
                  'currentClassName' => 'Kreuzberg\\ExtractionResult',
                  'aliasName' => null,
              ],
             'getAnnotations'
              => [
                  'name' => 'getAnnotations',
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
                  'docComment' => '/** @return ?array<PdfAnnotation> */',
                  'startLine' => 2217,
                  'endLine' => 2217,
                  'startColumn' => 9,
                  'endColumn' => 51,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\ExtractionResult',
                  'currentClassName' => 'Kreuzberg\\ExtractionResult',
                  'aliasName' => null,
              ],
             'getChildren'
              => [
                  'name' => 'getChildren',
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
                  'docComment' => '/** @return ?array<ArchiveEntry> */',
                  'startLine' => 2219,
                  'endLine' => 2219,
                  'startColumn' => 9,
                  'endColumn' => 48,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\ExtractionResult',
                  'currentClassName' => 'Kreuzberg\\ExtractionResult',
                  'aliasName' => null,
              ],
             'getUris'
              => [
                  'name' => 'getUris',
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
                  'docComment' => '/** @return ?array<Uri> */',
                  'startLine' => 2221,
                  'endLine' => 2221,
                  'startColumn' => 9,
                  'endColumn' => 44,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\ExtractionResult',
                  'currentClassName' => 'Kreuzberg\\ExtractionResult',
                  'aliasName' => null,
              ],
             'getStructuredOutput'
              => [
                  'name' => 'getStructuredOutput',
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
                  'startLine' => 2222,
                  'endLine' => 2222,
                  'startColumn' => 9,
                  'endColumn' => 57,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\ExtractionResult',
                  'currentClassName' => 'Kreuzberg\\ExtractionResult',
                  'aliasName' => null,
              ],
             'getCodeIntelligence'
              => [
                  'name' => 'getCodeIntelligence',
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
                  'startLine' => 2223,
                  'endLine' => 2223,
                  'startColumn' => 9,
                  'endColumn' => 57,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\ExtractionResult',
                  'currentClassName' => 'Kreuzberg\\ExtractionResult',
                  'aliasName' => null,
              ],
             'getLlmUsage'
              => [
                  'name' => 'getLlmUsage',
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
                  'docComment' => '/** @return ?array<LlmUsage> */',
                  'startLine' => 2225,
                  'endLine' => 2225,
                  'startColumn' => 9,
                  'endColumn' => 48,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\ExtractionResult',
                  'currentClassName' => 'Kreuzberg\\ExtractionResult',
                  'aliasName' => null,
              ],
             'getFormattedContent'
              => [
                  'name' => 'getFormattedContent',
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
                  'startLine' => 2226,
                  'endLine' => 2226,
                  'startColumn' => 9,
                  'endColumn' => 57,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\ExtractionResult',
                  'currentClassName' => 'Kreuzberg\\ExtractionResult',
                  'aliasName' => null,
              ],
             'getOcrInternalDocument'
              => [
                  'name' => 'getOcrInternalDocument',
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
                  'startLine' => 2227,
                  'endLine' => 2227,
                  'startColumn' => 9,
                  'endColumn' => 60,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\ExtractionResult',
                  'currentClassName' => 'Kreuzberg\\ExtractionResult',
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
