<?php

declare(strict_types=1);

// osfsl-/Users/naamanhirschfeld/workspace/kreuzberg-dev/kreuzberg/packages/php/stubs/kreuzberg_extension.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Kreuzberg\TextAnnotation
return \PHPStan\Cache\CacheItem::__set_state([
    'variableKey' => 'v2-3119307c419b0b68ce5de99f015c90205808f8d7e877aa5ace59d62568086491-8.4.20-6.70.0.0',
    'data'
    => [
        'locatedSource'
         => [
             'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
             'data'
              => [
                  'name' => 'Kreuzberg\\TextAnnotation',
                  'filename' => '/Users/naamanhirschfeld/workspace/kreuzberg-dev/kreuzberg/packages/php/stubs/kreuzberg_extension.php',
              ],
         ],
        'namespace' => 'Kreuzberg',
        'name' => 'Kreuzberg\\TextAnnotation',
        'shortName' => 'TextAnnotation',
        'isInterface' => false,
        'isTrait' => false,
        'isEnum' => false,
        'isBackedEnum' => false,
        'modifiers' => 0,
        'docComment' => '/**
 * Inline text annotation — byte-range based formatting and links.
 *
 * Annotations reference byte offsets into the node\'s text content,
 * enabling precise identification of formatted regions.
 */',
        'attributes'
         => [
         ],
        'startLine' => 2096,
        'endLine' => 2111,
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
             'start'
              => [
                  'declaringClassName' => 'Kreuzberg\\TextAnnotation',
                  'implementingClassName' => 'Kreuzberg\\TextAnnotation',
                  'name' => 'start',
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
                  'startLine' => 2098,
                  'endLine' => 2098,
                  'startColumn' => 9,
                  'endColumn' => 26,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'end'
              => [
                  'declaringClassName' => 'Kreuzberg\\TextAnnotation',
                  'implementingClassName' => 'Kreuzberg\\TextAnnotation',
                  'name' => 'end',
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
                  'startLine' => 2099,
                  'endLine' => 2099,
                  'startColumn' => 9,
                  'endColumn' => 24,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'kind'
              => [
                  'declaringClassName' => 'Kreuzberg\\TextAnnotation',
                  'implementingClassName' => 'Kreuzberg\\TextAnnotation',
                  'name' => 'kind',
                  'modifiers' => 1,
                  'type'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                       'data'
                        => [
                            'name' => 'Kreuzberg\\AnnotationKind',
                            'isIdentifier' => false,
                        ],
                   ],
                  'default' => null,
                  'docComment' => null,
                  'attributes'
                   => [
                   ],
                  'startLine' => 2100,
                  'endLine' => 2100,
                  'startColumn' => 9,
                  'endColumn' => 36,
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
                       'start'
                        => [
                            'name' => 'start',
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
                            'startLine' => 2103,
                            'endLine' => 2103,
                            'startColumn' => 13,
                            'endColumn' => 22,
                            'parameterIndex' => 0,
                            'isOptional' => false,
                        ],
                       'end'
                        => [
                            'name' => 'end',
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
                            'startLine' => 2104,
                            'endLine' => 2104,
                            'startColumn' => 13,
                            'endColumn' => 20,
                            'parameterIndex' => 1,
                            'isOptional' => false,
                        ],
                       'kind'
                        => [
                            'name' => 'kind',
                            'default' => null,
                            'type'
                             => [
                                 'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                 'data'
                                  => [
                                      'name' => 'Kreuzberg\\AnnotationKind',
                                      'isIdentifier' => false,
                                  ],
                             ],
                            'isVariadic' => false,
                            'byRef' => false,
                            'isPromoted' => false,
                            'attributes'
                             => [
                             ],
                            'startLine' => 2105,
                            'endLine' => 2105,
                            'startColumn' => 13,
                            'endColumn' => 32,
                            'parameterIndex' => 2,
                            'isOptional' => false,
                        ],
                   ],
                  'returnsReference' => false,
                  'returnType' => null,
                  'attributes'
                   => [
                   ],
                  'docComment' => null,
                  'startLine' => 2102,
                  'endLine' => 2106,
                  'startColumn' => 9,
                  'endColumn' => 12,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\TextAnnotation',
                  'implementingClassName' => 'Kreuzberg\\TextAnnotation',
                  'currentClassName' => 'Kreuzberg\\TextAnnotation',
                  'aliasName' => null,
              ],
             'getStart'
              => [
                  'name' => 'getStart',
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
                  'startLine' => 2108,
                  'endLine' => 2108,
                  'startColumn' => 9,
                  'endColumn' => 42,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\TextAnnotation',
                  'implementingClassName' => 'Kreuzberg\\TextAnnotation',
                  'currentClassName' => 'Kreuzberg\\TextAnnotation',
                  'aliasName' => null,
              ],
             'getEnd'
              => [
                  'name' => 'getEnd',
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
                  'startLine' => 2109,
                  'endLine' => 2109,
                  'startColumn' => 9,
                  'endColumn' => 40,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\TextAnnotation',
                  'implementingClassName' => 'Kreuzberg\\TextAnnotation',
                  'currentClassName' => 'Kreuzberg\\TextAnnotation',
                  'aliasName' => null,
              ],
             'getKind'
              => [
                  'name' => 'getKind',
                  'parameters'
                   => [
                   ],
                  'returnsReference' => false,
                  'returnType'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                       'data'
                        => [
                            'name' => 'Kreuzberg\\AnnotationKind',
                            'isIdentifier' => false,
                        ],
                   ],
                  'attributes'
                   => [
                   ],
                  'docComment' => null,
                  'startLine' => 2110,
                  'endLine' => 2110,
                  'startColumn' => 9,
                  'endColumn' => 52,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\TextAnnotation',
                  'implementingClassName' => 'Kreuzberg\\TextAnnotation',
                  'currentClassName' => 'Kreuzberg\\TextAnnotation',
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
