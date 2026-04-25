<?php

declare(strict_types=1);

// osfsl-/Users/naamanhirschfeld/workspace/kreuzberg-dev/kreuzberg/packages/php/stubs/kreuzberg_extension.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Kreuzberg\ChunkingResult
return \PHPStan\Cache\CacheItem::__set_state([
    'variableKey' => 'v2-3119307c419b0b68ce5de99f015c90205808f8d7e877aa5ace59d62568086491-8.4.20-6.70.0.0',
    'data'
    => [
        'locatedSource'
         => [
             'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
             'data'
              => [
                  'name' => 'Kreuzberg\\ChunkingResult',
                  'filename' => '/Users/naamanhirschfeld/workspace/kreuzberg-dev/kreuzberg/packages/php/stubs/kreuzberg_extension.php',
              ],
         ],
        'namespace' => 'Kreuzberg',
        'name' => 'Kreuzberg\\ChunkingResult',
        'shortName' => 'ChunkingResult',
        'isInterface' => false,
        'isTrait' => false,
        'isEnum' => false,
        'isBackedEnum' => false,
        'modifiers' => 0,
        'docComment' => '/**
 * Result of a text chunking operation.
 *
 * Contains the generated chunks and metadata about the chunking.
 */',
        'attributes'
         => [
         ],
        'startLine' => 4951,
        'endLine' => 4968,
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
             'chunks'
              => [
                  'declaringClassName' => 'Kreuzberg\\ChunkingResult',
                  'implementingClassName' => 'Kreuzberg\\ChunkingResult',
                  'name' => 'chunks',
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
                  'docComment' => '/** @var array<Chunk> */',
                  'attributes'
                   => [
                   ],
                  'startLine' => 4954,
                  'endLine' => 4954,
                  'startColumn' => 9,
                  'endColumn' => 29,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'chunk_count'
              => [
                  'declaringClassName' => 'Kreuzberg\\ChunkingResult',
                  'implementingClassName' => 'Kreuzberg\\ChunkingResult',
                  'name' => 'chunk_count',
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
                  'startLine' => 4955,
                  'endLine' => 4955,
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
                       'chunks'
                        => [
                            'name' => 'chunks',
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
                            'startLine' => 4961,
                            'endLine' => 4961,
                            'startColumn' => 13,
                            'endColumn' => 25,
                            'parameterIndex' => 0,
                            'isOptional' => false,
                        ],
                       'chunk_count'
                        => [
                            'name' => 'chunk_count',
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
                            'startLine' => 4962,
                            'endLine' => 4962,
                            'startColumn' => 13,
                            'endColumn' => 28,
                            'parameterIndex' => 1,
                            'isOptional' => false,
                        ],
                   ],
                  'returnsReference' => false,
                  'returnType' => null,
                  'attributes'
                   => [
                   ],
                  'docComment' => '/**
 * @param array<Chunk> $chunks
 */',
                  'startLine' => 4960,
                  'endLine' => 4963,
                  'startColumn' => 9,
                  'endColumn' => 12,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ChunkingResult',
                  'implementingClassName' => 'Kreuzberg\\ChunkingResult',
                  'currentClassName' => 'Kreuzberg\\ChunkingResult',
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
                  'docComment' => '/** @return array<Chunk> */',
                  'startLine' => 4966,
                  'endLine' => 4966,
                  'startColumn' => 9,
                  'endColumn' => 45,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ChunkingResult',
                  'implementingClassName' => 'Kreuzberg\\ChunkingResult',
                  'currentClassName' => 'Kreuzberg\\ChunkingResult',
                  'aliasName' => null,
              ],
             'getChunkCount'
              => [
                  'name' => 'getChunkCount',
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
                  'startLine' => 4967,
                  'endLine' => 4967,
                  'startColumn' => 9,
                  'endColumn' => 47,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\ChunkingResult',
                  'implementingClassName' => 'Kreuzberg\\ChunkingResult',
                  'currentClassName' => 'Kreuzberg\\ChunkingResult',
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
