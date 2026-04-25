<?php

declare(strict_types=1);

// osfsl-/Users/naamanhirschfeld/workspace/kreuzberg-dev/kreuzberg/packages/php/stubs/kreuzberg_extension.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Kreuzberg\Element
return \PHPStan\Cache\CacheItem::__set_state([
    'variableKey' => 'v2-3119307c419b0b68ce5de99f015c90205808f8d7e877aa5ace59d62568086491-8.4.20-6.70.0.0',
    'data'
    => [
        'locatedSource'
         => [
             'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
             'data'
              => [
                  'name' => 'Kreuzberg\\Element',
                  'filename' => '/Users/naamanhirschfeld/workspace/kreuzberg-dev/kreuzberg/packages/php/stubs/kreuzberg_extension.php',
              ],
         ],
        'namespace' => 'Kreuzberg',
        'name' => 'Kreuzberg\\Element',
        'shortName' => 'Element',
        'isInterface' => false,
        'isTrait' => false,
        'isEnum' => false,
        'isBackedEnum' => false,
        'modifiers' => 0,
        'docComment' => '/**
 * Semantic element extracted from document.
 *
 * Represents a logical unit of content with semantic classification,
 * unique identifier, and metadata for tracking origin and position.
 */',
        'attributes'
         => [
         ],
        'startLine' => 2505,
        'endLine' => 2523,
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
             'element_id'
              => [
                  'declaringClassName' => 'Kreuzberg\\Element',
                  'implementingClassName' => 'Kreuzberg\\Element',
                  'name' => 'element_id',
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
                  'startLine' => 2507,
                  'endLine' => 2507,
                  'startColumn' => 9,
                  'endColumn' => 34,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'element_type'
              => [
                  'declaringClassName' => 'Kreuzberg\\Element',
                  'implementingClassName' => 'Kreuzberg\\Element',
                  'name' => 'element_type',
                  'modifiers' => 1,
                  'type'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                       'data'
                        => [
                            'name' => 'Kreuzberg\\ElementType',
                            'isIdentifier' => false,
                        ],
                   ],
                  'default' => null,
                  'docComment' => null,
                  'attributes'
                   => [
                   ],
                  'startLine' => 2508,
                  'endLine' => 2508,
                  'startColumn' => 9,
                  'endColumn' => 41,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'text'
              => [
                  'declaringClassName' => 'Kreuzberg\\Element',
                  'implementingClassName' => 'Kreuzberg\\Element',
                  'name' => 'text',
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
                  'startLine' => 2509,
                  'endLine' => 2509,
                  'startColumn' => 9,
                  'endColumn' => 28,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'metadata'
              => [
                  'declaringClassName' => 'Kreuzberg\\Element',
                  'implementingClassName' => 'Kreuzberg\\Element',
                  'name' => 'metadata',
                  'modifiers' => 1,
                  'type'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                       'data'
                        => [
                            'name' => 'Kreuzberg\\ElementMetadata',
                            'isIdentifier' => false,
                        ],
                   ],
                  'default' => null,
                  'docComment' => null,
                  'attributes'
                   => [
                   ],
                  'startLine' => 2510,
                  'endLine' => 2510,
                  'startColumn' => 9,
                  'endColumn' => 41,
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
                       'element_id'
                        => [
                            'name' => 'element_id',
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
                            'startLine' => 2513,
                            'endLine' => 2513,
                            'startColumn' => 13,
                            'endColumn' => 30,
                            'parameterIndex' => 0,
                            'isOptional' => false,
                        ],
                       'element_type'
                        => [
                            'name' => 'element_type',
                            'default' => null,
                            'type'
                             => [
                                 'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                                 'data'
                                  => [
                                      'name' => 'Kreuzberg\\ElementType',
                                      'isIdentifier' => false,
                                  ],
                             ],
                            'isVariadic' => false,
                            'byRef' => false,
                            'isPromoted' => false,
                            'attributes'
                             => [
                             ],
                            'startLine' => 2514,
                            'endLine' => 2514,
                            'startColumn' => 13,
                            'endColumn' => 37,
                            'parameterIndex' => 1,
                            'isOptional' => false,
                        ],
                       'text'
                        => [
                            'name' => 'text',
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
                            'startLine' => 2515,
                            'endLine' => 2515,
                            'startColumn' => 13,
                            'endColumn' => 24,
                            'parameterIndex' => 2,
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
                                      'name' => 'Kreuzberg\\ElementMetadata',
                                      'isIdentifier' => false,
                                  ],
                             ],
                            'isVariadic' => false,
                            'byRef' => false,
                            'isPromoted' => false,
                            'attributes'
                             => [
                             ],
                            'startLine' => 2516,
                            'endLine' => 2516,
                            'startColumn' => 13,
                            'endColumn' => 37,
                            'parameterIndex' => 3,
                            'isOptional' => false,
                        ],
                   ],
                  'returnsReference' => false,
                  'returnType' => null,
                  'attributes'
                   => [
                   ],
                  'docComment' => null,
                  'startLine' => 2512,
                  'endLine' => 2517,
                  'startColumn' => 9,
                  'endColumn' => 12,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\Element',
                  'implementingClassName' => 'Kreuzberg\\Element',
                  'currentClassName' => 'Kreuzberg\\Element',
                  'aliasName' => null,
              ],
             'getElementId'
              => [
                  'name' => 'getElementId',
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
                  'startLine' => 2519,
                  'endLine' => 2519,
                  'startColumn' => 9,
                  'endColumn' => 49,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\Element',
                  'implementingClassName' => 'Kreuzberg\\Element',
                  'currentClassName' => 'Kreuzberg\\Element',
                  'aliasName' => null,
              ],
             'getElementType'
              => [
                  'name' => 'getElementType',
                  'parameters'
                   => [
                   ],
                  'returnsReference' => false,
                  'returnType'
                   => [
                       'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                       'data'
                        => [
                            'name' => 'Kreuzberg\\ElementType',
                            'isIdentifier' => false,
                        ],
                   ],
                  'attributes'
                   => [
                   ],
                  'docComment' => null,
                  'startLine' => 2520,
                  'endLine' => 2520,
                  'startColumn' => 9,
                  'endColumn' => 56,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\Element',
                  'implementingClassName' => 'Kreuzberg\\Element',
                  'currentClassName' => 'Kreuzberg\\Element',
                  'aliasName' => null,
              ],
             'getText'
              => [
                  'name' => 'getText',
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
                  'startLine' => 2521,
                  'endLine' => 2521,
                  'startColumn' => 9,
                  'endColumn' => 44,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\Element',
                  'implementingClassName' => 'Kreuzberg\\Element',
                  'currentClassName' => 'Kreuzberg\\Element',
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
                            'name' => 'Kreuzberg\\ElementMetadata',
                            'isIdentifier' => false,
                        ],
                   ],
                  'attributes'
                   => [
                   ],
                  'docComment' => null,
                  'startLine' => 2522,
                  'endLine' => 2522,
                  'startColumn' => 9,
                  'endColumn' => 57,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\Element',
                  'implementingClassName' => 'Kreuzberg\\Element',
                  'currentClassName' => 'Kreuzberg\\Element',
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
