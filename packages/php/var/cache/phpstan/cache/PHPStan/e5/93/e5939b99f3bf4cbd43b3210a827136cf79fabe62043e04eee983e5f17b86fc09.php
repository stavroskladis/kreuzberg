<?php

declare(strict_types=1);

// osfsl-/Users/naamanhirschfeld/workspace/kreuzberg-dev/kreuzberg/packages/php/stubs/kreuzberg_extension.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Kreuzberg\PageBoundary
return \PHPStan\Cache\CacheItem::__set_state([
    'variableKey' => 'v2-3119307c419b0b68ce5de99f015c90205808f8d7e877aa5ace59d62568086491-8.4.20-6.70.0.0',
    'data'
    => [
        'locatedSource'
         => [
             'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
             'data'
              => [
                  'name' => 'Kreuzberg\\PageBoundary',
                  'filename' => '/Users/naamanhirschfeld/workspace/kreuzberg-dev/kreuzberg/packages/php/stubs/kreuzberg_extension.php',
              ],
         ],
        'namespace' => 'Kreuzberg',
        'name' => 'Kreuzberg\\PageBoundary',
        'shortName' => 'PageBoundary',
        'isInterface' => false,
        'isTrait' => false,
        'isEnum' => false,
        'isBackedEnum' => false,
        'modifiers' => 0,
        'docComment' => '/**
 * Byte offset boundary for a page.
 *
 * Tracks where a specific page\'s content starts and ends in the main content string,
 * enabling mapping from byte positions to page numbers. Offsets are guaranteed to be
 * at valid UTF-8 character boundaries when using standard String methods (push_str, push, etc.).
 */',
        'attributes'
         => [
         ],
        'startLine' => 4105,
        'endLine' => 4120,
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
             'byte_start'
              => [
                  'declaringClassName' => 'Kreuzberg\\PageBoundary',
                  'implementingClassName' => 'Kreuzberg\\PageBoundary',
                  'name' => 'byte_start',
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
                  'startLine' => 4107,
                  'endLine' => 4107,
                  'startColumn' => 9,
                  'endColumn' => 31,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'byte_end'
              => [
                  'declaringClassName' => 'Kreuzberg\\PageBoundary',
                  'implementingClassName' => 'Kreuzberg\\PageBoundary',
                  'name' => 'byte_end',
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
                  'startLine' => 4108,
                  'endLine' => 4108,
                  'startColumn' => 9,
                  'endColumn' => 29,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'page_number'
              => [
                  'declaringClassName' => 'Kreuzberg\\PageBoundary',
                  'implementingClassName' => 'Kreuzberg\\PageBoundary',
                  'name' => 'page_number',
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
                  'startLine' => 4109,
                  'endLine' => 4109,
                  'startColumn' => 9,
                  'endColumn' => 32,
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
                       'byte_start'
                        => [
                            'name' => 'byte_start',
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
                            'startLine' => 4112,
                            'endLine' => 4112,
                            'startColumn' => 13,
                            'endColumn' => 27,
                            'parameterIndex' => 0,
                            'isOptional' => false,
                        ],
                       'byte_end'
                        => [
                            'name' => 'byte_end',
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
                            'startLine' => 4113,
                            'endLine' => 4113,
                            'startColumn' => 13,
                            'endColumn' => 25,
                            'parameterIndex' => 1,
                            'isOptional' => false,
                        ],
                       'page_number'
                        => [
                            'name' => 'page_number',
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
                            'startLine' => 4114,
                            'endLine' => 4114,
                            'startColumn' => 13,
                            'endColumn' => 28,
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
                  'startLine' => 4111,
                  'endLine' => 4115,
                  'startColumn' => 9,
                  'endColumn' => 12,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\PageBoundary',
                  'implementingClassName' => 'Kreuzberg\\PageBoundary',
                  'currentClassName' => 'Kreuzberg\\PageBoundary',
                  'aliasName' => null,
              ],
             'getByteStart'
              => [
                  'name' => 'getByteStart',
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
                  'startLine' => 4117,
                  'endLine' => 4117,
                  'startColumn' => 9,
                  'endColumn' => 46,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\PageBoundary',
                  'implementingClassName' => 'Kreuzberg\\PageBoundary',
                  'currentClassName' => 'Kreuzberg\\PageBoundary',
                  'aliasName' => null,
              ],
             'getByteEnd'
              => [
                  'name' => 'getByteEnd',
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
                  'startLine' => 4118,
                  'endLine' => 4118,
                  'startColumn' => 9,
                  'endColumn' => 44,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\PageBoundary',
                  'implementingClassName' => 'Kreuzberg\\PageBoundary',
                  'currentClassName' => 'Kreuzberg\\PageBoundary',
                  'aliasName' => null,
              ],
             'getPageNumber'
              => [
                  'name' => 'getPageNumber',
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
                  'startLine' => 4119,
                  'endLine' => 4119,
                  'startColumn' => 9,
                  'endColumn' => 47,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\PageBoundary',
                  'implementingClassName' => 'Kreuzberg\\PageBoundary',
                  'currentClassName' => 'Kreuzberg\\PageBoundary',
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
