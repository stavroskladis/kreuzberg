<?php

declare(strict_types=1);

// osfsl-/Users/naamanhirschfeld/workspace/kreuzberg-dev/kreuzberg/packages/php/stubs/kreuzberg_extension.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Kreuzberg\KeywordConfig
return \PHPStan\Cache\CacheItem::__set_state([
    'variableKey' => 'v2-3119307c419b0b68ce5de99f015c90205808f8d7e877aa5ace59d62568086491-8.4.20-6.70.0.0',
    'data'
    => [
        'locatedSource'
         => [
             'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
             'data'
              => [
                  'name' => 'Kreuzberg\\KeywordConfig',
                  'filename' => '/Users/naamanhirschfeld/workspace/kreuzberg-dev/kreuzberg/packages/php/stubs/kreuzberg_extension.php',
              ],
         ],
        'namespace' => 'Kreuzberg',
        'name' => 'Kreuzberg\\KeywordConfig',
        'shortName' => 'KeywordConfig',
        'isInterface' => false,
        'isTrait' => false,
        'isEnum' => false,
        'isBackedEnum' => false,
        'modifiers' => 0,
        'docComment' => '/**
 * Keyword extraction configuration.
 */',
        'attributes'
         => [
         ],
        'startLine' => 5024,
        'endLine' => 5056,
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
             'algorithm'
              => [
                  'declaringClassName' => 'Kreuzberg\\KeywordConfig',
                  'implementingClassName' => 'Kreuzberg\\KeywordConfig',
                  'name' => 'algorithm',
                  'modifiers' => 1,
                  'type'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                       'data'
                        => [
                            'name' => 'Kreuzberg\\KeywordAlgorithm',
                            'isIdentifier' => false,
                        ],
                   ],
                  'default' => null,
                  'docComment' => null,
                  'attributes'
                   => [
                   ],
                  'startLine' => 5026,
                  'endLine' => 5026,
                  'startColumn' => 9,
                  'endColumn' => 43,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'max_keywords'
              => [
                  'declaringClassName' => 'Kreuzberg\\KeywordConfig',
                  'implementingClassName' => 'Kreuzberg\\KeywordConfig',
                  'name' => 'max_keywords',
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
                  'startLine' => 5027,
                  'endLine' => 5027,
                  'startColumn' => 9,
                  'endColumn' => 33,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'min_score'
              => [
                  'declaringClassName' => 'Kreuzberg\\KeywordConfig',
                  'implementingClassName' => 'Kreuzberg\\KeywordConfig',
                  'name' => 'min_score',
                  'modifiers' => 1,
                  'type'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                       'data'
                        => [
                            'name' => 'float',
                            'isIdentifier' => true,
                        ],
                   ],
                  'default' => null,
                  'docComment' => null,
                  'attributes'
                   => [
                   ],
                  'startLine' => 5028,
                  'endLine' => 5028,
                  'startColumn' => 9,
                  'endColumn' => 32,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'ngram_range'
              => [
                  'declaringClassName' => 'Kreuzberg\\KeywordConfig',
                  'implementingClassName' => 'Kreuzberg\\KeywordConfig',
                  'name' => 'ngram_range',
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
                  'docComment' => '/** @var array<int> */',
                  'attributes'
                   => [
                   ],
                  'startLine' => 5030,
                  'endLine' => 5030,
                  'startColumn' => 9,
                  'endColumn' => 34,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'language'
              => [
                  'declaringClassName' => 'Kreuzberg\\KeywordConfig',
                  'implementingClassName' => 'Kreuzberg\\KeywordConfig',
                  'name' => 'language',
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
                  'startLine' => 5031,
                  'endLine' => 5031,
                  'startColumn' => 9,
                  'endColumn' => 33,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'yake_params'
              => [
                  'declaringClassName' => 'Kreuzberg\\KeywordConfig',
                  'implementingClassName' => 'Kreuzberg\\KeywordConfig',
                  'name' => 'yake_params',
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
                                           'name' => 'Kreuzberg\\YakeParams',
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
                  'startLine' => 5032,
                  'endLine' => 5032,
                  'startColumn' => 9,
                  'endColumn' => 40,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'rake_params'
              => [
                  'declaringClassName' => 'Kreuzberg\\KeywordConfig',
                  'implementingClassName' => 'Kreuzberg\\KeywordConfig',
                  'name' => 'rake_params',
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
                                           'name' => 'Kreuzberg\\RakeParams',
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
                  'startLine' => 5033,
                  'endLine' => 5033,
                  'startColumn' => 9,
                  'endColumn' => 40,
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
                       'algorithm'
                        => [
                            'name' => 'algorithm',
                            'default' => null,
                            'type'
                             => [
                                 'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                 'data'
                                  => [
                                      'name' => 'Kreuzberg\\KeywordAlgorithm',
                                      'isIdentifier' => false,
                                  ],
                             ],
                            'isVariadic' => false,
                            'byRef' => false,
                            'isPromoted' => false,
                            'attributes'
                             => [
                             ],
                            'startLine' => 5039,
                            'endLine' => 5039,
                            'startColumn' => 13,
                            'endColumn' => 39,
                            'parameterIndex' => 0,
                            'isOptional' => false,
                        ],
                       'max_keywords'
                        => [
                            'name' => 'max_keywords',
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
                            'startLine' => 5040,
                            'endLine' => 5040,
                            'startColumn' => 13,
                            'endColumn' => 29,
                            'parameterIndex' => 1,
                            'isOptional' => false,
                        ],
                       'min_score'
                        => [
                            'name' => 'min_score',
                            'default' => null,
                            'type'
                             => [
                                 'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                 'data'
                                  => [
                                      'name' => 'float',
                                      'isIdentifier' => true,
                                  ],
                             ],
                            'isVariadic' => false,
                            'byRef' => false,
                            'isPromoted' => false,
                            'attributes'
                             => [
                             ],
                            'startLine' => 5041,
                            'endLine' => 5041,
                            'startColumn' => 13,
                            'endColumn' => 28,
                            'parameterIndex' => 2,
                            'isOptional' => false,
                        ],
                       'ngram_range'
                        => [
                            'name' => 'ngram_range',
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
                            'startLine' => 5042,
                            'endLine' => 5042,
                            'startColumn' => 13,
                            'endColumn' => 30,
                            'parameterIndex' => 3,
                            'isOptional' => false,
                        ],
                       'language'
                        => [
                            'name' => 'language',
                            'default'
                             => [
                                 'code' => 'null',
                                 'attributes'
                                  => [
                                      'startLine' => 5043,
                                      'endLine' => 5043,
                                      'startTokenPos' => 27671,
                                      'startFilePos' => 158802,
                                      'endTokenPos' => 27671,
                                      'endFilePos' => 158805,
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
                            'startLine' => 5043,
                            'endLine' => 5043,
                            'startColumn' => 13,
                            'endColumn' => 36,
                            'parameterIndex' => 4,
                            'isOptional' => true,
                        ],
                       'yake_params'
                        => [
                            'name' => 'yake_params',
                            'default'
                             => [
                                 'code' => 'null',
                                 'attributes'
                                  => [
                                      'startLine' => 5044,
                                      'endLine' => 5044,
                                      'startTokenPos' => 27681,
                                      'startFilePos' => 158847,
                                      'endTokenPos' => 27681,
                                      'endFilePos' => 158850,
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
                                                     'name' => 'Kreuzberg\\YakeParams',
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
                            'startLine' => 5044,
                            'endLine' => 5044,
                            'startColumn' => 13,
                            'endColumn' => 43,
                            'parameterIndex' => 5,
                            'isOptional' => true,
                        ],
                       'rake_params'
                        => [
                            'name' => 'rake_params',
                            'default'
                             => [
                                 'code' => 'null',
                                 'attributes'
                                  => [
                                      'startLine' => 5045,
                                      'endLine' => 5045,
                                      'startTokenPos' => 27691,
                                      'startFilePos' => 158892,
                                      'endTokenPos' => 27691,
                                      'endFilePos' => 158895,
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
                                                     'name' => 'Kreuzberg\\RakeParams',
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
                            'startLine' => 5045,
                            'endLine' => 5045,
                            'startColumn' => 13,
                            'endColumn' => 43,
                            'parameterIndex' => 6,
                            'isOptional' => true,
                        ],
                   ],
                  'returnsReference' => false,
                  'returnType' => null,
                  'attributes'
                   => [
                   ],
                  'docComment' => '/**
 * @param array<int> $ngram_range
 */',
                  'startLine' => 5038,
                  'endLine' => 5046,
                  'startColumn' => 9,
                  'endColumn' => 12,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\KeywordConfig',
                  'implementingClassName' => 'Kreuzberg\\KeywordConfig',
                  'currentClassName' => 'Kreuzberg\\KeywordConfig',
                  'aliasName' => null,
              ],
             'getAlgorithm'
              => [
                  'name' => 'getAlgorithm',
                  'parameters'
                   => [
                   ],
                  'returnsReference' => false,
                  'returnType'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                       'data'
                        => [
                            'name' => 'Kreuzberg\\KeywordAlgorithm',
                            'isIdentifier' => false,
                        ],
                   ],
                  'attributes'
                   => [
                   ],
                  'docComment' => null,
                  'startLine' => 5048,
                  'endLine' => 5048,
                  'startColumn' => 9,
                  'endColumn' => 59,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\KeywordConfig',
                  'implementingClassName' => 'Kreuzberg\\KeywordConfig',
                  'currentClassName' => 'Kreuzberg\\KeywordConfig',
                  'aliasName' => null,
              ],
             'getMaxKeywords'
              => [
                  'name' => 'getMaxKeywords',
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
                  'startLine' => 5049,
                  'endLine' => 5049,
                  'startColumn' => 9,
                  'endColumn' => 48,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\KeywordConfig',
                  'implementingClassName' => 'Kreuzberg\\KeywordConfig',
                  'currentClassName' => 'Kreuzberg\\KeywordConfig',
                  'aliasName' => null,
              ],
             'getMinScore'
              => [
                  'name' => 'getMinScore',
                  'parameters'
                   => [
                   ],
                  'returnsReference' => false,
                  'returnType'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                       'data'
                        => [
                            'name' => 'float',
                            'isIdentifier' => true,
                        ],
                   ],
                  'attributes'
                   => [
                   ],
                  'docComment' => null,
                  'startLine' => 5050,
                  'endLine' => 5050,
                  'startColumn' => 9,
                  'endColumn' => 47,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\KeywordConfig',
                  'implementingClassName' => 'Kreuzberg\\KeywordConfig',
                  'currentClassName' => 'Kreuzberg\\KeywordConfig',
                  'aliasName' => null,
              ],
             'getNgramRange'
              => [
                  'name' => 'getNgramRange',
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
                  'docComment' => '/** @return array<int> */',
                  'startLine' => 5052,
                  'endLine' => 5052,
                  'startColumn' => 9,
                  'endColumn' => 49,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\KeywordConfig',
                  'implementingClassName' => 'Kreuzberg\\KeywordConfig',
                  'currentClassName' => 'Kreuzberg\\KeywordConfig',
                  'aliasName' => null,
              ],
             'getLanguage'
              => [
                  'name' => 'getLanguage',
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
                  'startLine' => 5053,
                  'endLine' => 5053,
                  'startColumn' => 9,
                  'endColumn' => 49,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\KeywordConfig',
                  'implementingClassName' => 'Kreuzberg\\KeywordConfig',
                  'currentClassName' => 'Kreuzberg\\KeywordConfig',
                  'aliasName' => null,
              ],
             'getYakeParams'
              => [
                  'name' => 'getYakeParams',
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
                                           'name' => 'Kreuzberg\\YakeParams',
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
                  'startLine' => 5054,
                  'endLine' => 5054,
                  'startColumn' => 9,
                  'endColumn' => 55,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\KeywordConfig',
                  'implementingClassName' => 'Kreuzberg\\KeywordConfig',
                  'currentClassName' => 'Kreuzberg\\KeywordConfig',
                  'aliasName' => null,
              ],
             'getRakeParams'
              => [
                  'name' => 'getRakeParams',
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
                                           'name' => 'Kreuzberg\\RakeParams',
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
                  'startLine' => 5055,
                  'endLine' => 5055,
                  'startColumn' => 9,
                  'endColumn' => 55,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\KeywordConfig',
                  'implementingClassName' => 'Kreuzberg\\KeywordConfig',
                  'currentClassName' => 'Kreuzberg\\KeywordConfig',
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
