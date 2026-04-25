<?php

declare(strict_types=1);

// osfsl-/Users/naamanhirschfeld/workspace/kreuzberg-dev/kreuzberg/packages/php/stubs/kreuzberg_extension.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Kreuzberg\ChunkingConfig
return \PHPStan\Cache\CacheItem::__set_state([
    'variableKey' => 'v2-3119307c419b0b68ce5de99f015c90205808f8d7e877aa5ace59d62568086491-8.4.20-6.70.0.0',
    'data'
    => [
        'locatedSource'
         => [
             'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
             'data'
              => [
                  'name' => 'Kreuzberg\\ChunkingConfig',
                  'filename' => '/Users/naamanhirschfeld/workspace/kreuzberg-dev/kreuzberg/packages/php/stubs/kreuzberg_extension.php',
              ],
         ],
        'namespace' => 'Kreuzberg',
        'name' => 'Kreuzberg\\ChunkingConfig',
        'shortName' => 'ChunkingConfig',
        'isInterface' => false,
        'isTrait' => false,
        'isEnum' => false,
        'isBackedEnum' => false,
        'modifiers' => 0,
        'docComment' => '/**
 * Chunking configuration.
 *
 * Configures text chunking for document content, including chunk size,
 * overlap, trimming behavior, and optional embeddings.
 *
 * Use `..Default::default()` when constructing to allow for future field additions:
 * ```rust
 * # use kreuzberg::ChunkingConfig;
 * let config = ChunkingConfig {
 *     max_characters: 500,
 *     ..Default::default()
 * };
 * ```
 */',
        'attributes'
         => [
         ],
        'startLine' => 934,
        'endLine' => 967,
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
             'max_characters'
              => [
                  'declaringClassName' => 'Kreuzberg\\ChunkingConfig',
                  'implementingClassName' => 'Kreuzberg\\ChunkingConfig',
                  'name' => 'max_characters',
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
                  'startLine' => 936,
                  'endLine' => 936,
                  'startColumn' => 9,
                  'endColumn' => 35,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'overlap'
              => [
                  'declaringClassName' => 'Kreuzberg\\ChunkingConfig',
                  'implementingClassName' => 'Kreuzberg\\ChunkingConfig',
                  'name' => 'overlap',
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
                  'startLine' => 937,
                  'endLine' => 937,
                  'startColumn' => 9,
                  'endColumn' => 28,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'trim'
              => [
                  'declaringClassName' => 'Kreuzberg\\ChunkingConfig',
                  'implementingClassName' => 'Kreuzberg\\ChunkingConfig',
                  'name' => 'trim',
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
                  'startLine' => 938,
                  'endLine' => 938,
                  'startColumn' => 9,
                  'endColumn' => 26,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'chunker_type'
              => [
                  'declaringClassName' => 'Kreuzberg\\ChunkingConfig',
                  'implementingClassName' => 'Kreuzberg\\ChunkingConfig',
                  'name' => 'chunker_type',
                  'modifiers' => 1,
                  'type'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                       'data'
                        => [
                            'name' => 'Kreuzberg\\ChunkerType',
                            'isIdentifier' => false,
                        ],
                   ],
                  'default' => null,
                  'docComment' => null,
                  'attributes'
                   => [
                   ],
                  'startLine' => 939,
                  'endLine' => 939,
                  'startColumn' => 9,
                  'endColumn' => 41,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'embedding'
              => [
                  'declaringClassName' => 'Kreuzberg\\ChunkingConfig',
                  'implementingClassName' => 'Kreuzberg\\ChunkingConfig',
                  'name' => 'embedding',
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
                                           'name' => 'Kreuzberg\\EmbeddingConfig',
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
                  'startLine' => 940,
                  'endLine' => 940,
                  'startColumn' => 9,
                  'endColumn' => 43,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'preset'
              => [
                  'declaringClassName' => 'Kreuzberg\\ChunkingConfig',
                  'implementingClassName' => 'Kreuzberg\\ChunkingConfig',
                  'name' => 'preset',
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
                  'startLine' => 941,
                  'endLine' => 941,
                  'startColumn' => 9,
                  'endColumn' => 31,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'sizing'
              => [
                  'declaringClassName' => 'Kreuzberg\\ChunkingConfig',
                  'implementingClassName' => 'Kreuzberg\\ChunkingConfig',
                  'name' => 'sizing',
                  'modifiers' => 1,
                  'type'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                       'data'
                        => [
                            'name' => 'Kreuzberg\\ChunkSizing',
                            'isIdentifier' => false,
                        ],
                   ],
                  'default' => null,
                  'docComment' => null,
                  'attributes'
                   => [
                   ],
                  'startLine' => 942,
                  'endLine' => 942,
                  'startColumn' => 9,
                  'endColumn' => 35,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'prepend_heading_context'
              => [
                  'declaringClassName' => 'Kreuzberg\\ChunkingConfig',
                  'implementingClassName' => 'Kreuzberg\\ChunkingConfig',
                  'name' => 'prepend_heading_context',
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
                  'startLine' => 943,
                  'endLine' => 943,
                  'startColumn' => 9,
                  'endColumn' => 45,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'topic_threshold'
              => [
                  'declaringClassName' => 'Kreuzberg\\ChunkingConfig',
                  'implementingClassName' => 'Kreuzberg\\ChunkingConfig',
                  'name' => 'topic_threshold',
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
                  'startLine' => 944,
                  'endLine' => 944,
                  'startColumn' => 9,
                  'endColumn' => 39,
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
                       'max_characters'
                        => [
                            'name' => 'max_characters',
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
                            'startLine' => 947,
                            'endLine' => 947,
                            'startColumn' => 13,
                            'endColumn' => 31,
                            'parameterIndex' => 0,
                            'isOptional' => false,
                        ],
                       'overlap'
                        => [
                            'name' => 'overlap',
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
                            'startLine' => 948,
                            'endLine' => 948,
                            'startColumn' => 13,
                            'endColumn' => 24,
                            'parameterIndex' => 1,
                            'isOptional' => false,
                        ],
                       'trim'
                        => [
                            'name' => 'trim',
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
                            'startLine' => 949,
                            'endLine' => 949,
                            'startColumn' => 13,
                            'endColumn' => 22,
                            'parameterIndex' => 2,
                            'isOptional' => false,
                        ],
                       'chunker_type'
                        => [
                            'name' => 'chunker_type',
                            'default' => null,
                            'type'
                             => [
                                 'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                 'data'
                                  => [
                                      'name' => 'Kreuzberg\\ChunkerType',
                                      'isIdentifier' => false,
                                  ],
                             ],
                            'isVariadic' => false,
                            'byRef' => false,
                            'isPromoted' => false,
                            'attributes'
                             => [
                             ],
                            'startLine' => 950,
                            'endLine' => 950,
                            'startColumn' => 13,
                            'endColumn' => 37,
                            'parameterIndex' => 3,
                            'isOptional' => false,
                        ],
                       'sizing'
                        => [
                            'name' => 'sizing',
                            'default' => null,
                            'type'
                             => [
                                 'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                 'data'
                                  => [
                                      'name' => 'Kreuzberg\\ChunkSizing',
                                      'isIdentifier' => false,
                                  ],
                             ],
                            'isVariadic' => false,
                            'byRef' => false,
                            'isPromoted' => false,
                            'attributes'
                             => [
                             ],
                            'startLine' => 951,
                            'endLine' => 951,
                            'startColumn' => 13,
                            'endColumn' => 31,
                            'parameterIndex' => 4,
                            'isOptional' => false,
                        ],
                       'prepend_heading_context'
                        => [
                            'name' => 'prepend_heading_context',
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
                            'startLine' => 952,
                            'endLine' => 952,
                            'startColumn' => 13,
                            'endColumn' => 41,
                            'parameterIndex' => 5,
                            'isOptional' => false,
                        ],
                       'embedding'
                        => [
                            'name' => 'embedding',
                            'default'
                             => [
                                 'code' => 'null',
                                 'attributes'
                                  => [
                                      'startLine' => 953,
                                      'endLine' => 953,
                                      'startTokenPos' => 5263,
                                      'startFilePos' => 33324,
                                      'endTokenPos' => 5263,
                                      'endFilePos' => 33327,
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
                                                     'name' => 'Kreuzberg\\EmbeddingConfig',
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
                            'startLine' => 953,
                            'endLine' => 953,
                            'startColumn' => 13,
                            'endColumn' => 46,
                            'parameterIndex' => 6,
                            'isOptional' => true,
                        ],
                       'preset'
                        => [
                            'name' => 'preset',
                            'default'
                             => [
                                 'code' => 'null',
                                 'attributes'
                                  => [
                                      'startLine' => 954,
                                      'endLine' => 954,
                                      'startTokenPos' => 5273,
                                      'startFilePos' => 33360,
                                      'endTokenPos' => 5273,
                                      'endFilePos' => 33363,
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
                            'startLine' => 954,
                            'endLine' => 954,
                            'startColumn' => 13,
                            'endColumn' => 34,
                            'parameterIndex' => 7,
                            'isOptional' => true,
                        ],
                       'topic_threshold'
                        => [
                            'name' => 'topic_threshold',
                            'default'
                             => [
                                 'code' => 'null',
                                 'attributes'
                                  => [
                                      'startLine' => 955,
                                      'endLine' => 955,
                                      'startTokenPos' => 5283,
                                      'startFilePos' => 33404,
                                      'endTokenPos' => 5283,
                                      'endFilePos' => 33407,
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
                            'startLine' => 955,
                            'endLine' => 955,
                            'startColumn' => 13,
                            'endColumn' => 42,
                            'parameterIndex' => 8,
                            'isOptional' => true,
                        ],
                   ],
                  'returnsReference' => false,
                  'returnType' => null,
                  'attributes'
                   => [
                   ],
                  'docComment' => null,
                  'startLine' => 946,
                  'endLine' => 956,
                  'startColumn' => 9,
                  'endColumn' => 12,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ChunkingConfig',
                  'implementingClassName' => 'Kreuzberg\\ChunkingConfig',
                  'currentClassName' => 'Kreuzberg\\ChunkingConfig',
                  'aliasName' => null,
              ],
             'getMaxCharacters'
              => [
                  'name' => 'getMaxCharacters',
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
                  'startLine' => 958,
                  'endLine' => 958,
                  'startColumn' => 9,
                  'endColumn' => 50,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ChunkingConfig',
                  'implementingClassName' => 'Kreuzberg\\ChunkingConfig',
                  'currentClassName' => 'Kreuzberg\\ChunkingConfig',
                  'aliasName' => null,
              ],
             'getOverlap'
              => [
                  'name' => 'getOverlap',
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
                  'startLine' => 959,
                  'endLine' => 959,
                  'startColumn' => 9,
                  'endColumn' => 44,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ChunkingConfig',
                  'implementingClassName' => 'Kreuzberg\\ChunkingConfig',
                  'currentClassName' => 'Kreuzberg\\ChunkingConfig',
                  'aliasName' => null,
              ],
             'getTrim'
              => [
                  'name' => 'getTrim',
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
                  'startLine' => 960,
                  'endLine' => 960,
                  'startColumn' => 9,
                  'endColumn' => 42,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ChunkingConfig',
                  'implementingClassName' => 'Kreuzberg\\ChunkingConfig',
                  'currentClassName' => 'Kreuzberg\\ChunkingConfig',
                  'aliasName' => null,
              ],
             'getChunkerType'
              => [
                  'name' => 'getChunkerType',
                  'parameters'
                   => [
                   ],
                  'returnsReference' => false,
                  'returnType'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                       'data'
                        => [
                            'name' => 'Kreuzberg\\ChunkerType',
                            'isIdentifier' => false,
                        ],
                   ],
                  'attributes'
                   => [
                   ],
                  'docComment' => null,
                  'startLine' => 961,
                  'endLine' => 961,
                  'startColumn' => 9,
                  'endColumn' => 56,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ChunkingConfig',
                  'implementingClassName' => 'Kreuzberg\\ChunkingConfig',
                  'currentClassName' => 'Kreuzberg\\ChunkingConfig',
                  'aliasName' => null,
              ],
             'getEmbedding'
              => [
                  'name' => 'getEmbedding',
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
                                           'name' => 'Kreuzberg\\EmbeddingConfig',
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
                  'startLine' => 962,
                  'endLine' => 962,
                  'startColumn' => 9,
                  'endColumn' => 59,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ChunkingConfig',
                  'implementingClassName' => 'Kreuzberg\\ChunkingConfig',
                  'currentClassName' => 'Kreuzberg\\ChunkingConfig',
                  'aliasName' => null,
              ],
             'getPreset'
              => [
                  'name' => 'getPreset',
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
                  'startLine' => 963,
                  'endLine' => 963,
                  'startColumn' => 9,
                  'endColumn' => 47,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ChunkingConfig',
                  'implementingClassName' => 'Kreuzberg\\ChunkingConfig',
                  'currentClassName' => 'Kreuzberg\\ChunkingConfig',
                  'aliasName' => null,
              ],
             'getSizing'
              => [
                  'name' => 'getSizing',
                  'parameters'
                   => [
                   ],
                  'returnsReference' => false,
                  'returnType'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                       'data'
                        => [
                            'name' => 'Kreuzberg\\ChunkSizing',
                            'isIdentifier' => false,
                        ],
                   ],
                  'attributes'
                   => [
                   ],
                  'docComment' => null,
                  'startLine' => 964,
                  'endLine' => 964,
                  'startColumn' => 9,
                  'endColumn' => 51,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ChunkingConfig',
                  'implementingClassName' => 'Kreuzberg\\ChunkingConfig',
                  'currentClassName' => 'Kreuzberg\\ChunkingConfig',
                  'aliasName' => null,
              ],
             'getPrependHeadingContext'
              => [
                  'name' => 'getPrependHeadingContext',
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
                  'startLine' => 965,
                  'endLine' => 965,
                  'startColumn' => 9,
                  'endColumn' => 59,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ChunkingConfig',
                  'implementingClassName' => 'Kreuzberg\\ChunkingConfig',
                  'currentClassName' => 'Kreuzberg\\ChunkingConfig',
                  'aliasName' => null,
              ],
             'getTopicThreshold'
              => [
                  'name' => 'getTopicThreshold',
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
                  'startLine' => 966,
                  'endLine' => 966,
                  'startColumn' => 9,
                  'endColumn' => 54,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ChunkingConfig',
                  'implementingClassName' => 'Kreuzberg\\ChunkingConfig',
                  'currentClassName' => 'Kreuzberg\\ChunkingConfig',
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
