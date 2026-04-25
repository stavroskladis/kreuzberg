<?php

declare(strict_types=1);

// osfsl-/Users/naamanhirschfeld/workspace/kreuzberg-dev/kreuzberg/packages/php/stubs/kreuzberg_extension.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Kreuzberg\EmailExtractionResult
return \PHPStan\Cache\CacheItem::__set_state([
    'variableKey' => 'v2-3119307c419b0b68ce5de99f015c90205808f8d7e877aa5ace59d62568086491-8.4.20-6.70.0.0',
    'data'
    => [
        'locatedSource'
         => [
             'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
             'data'
              => [
                  'name' => 'Kreuzberg\\EmailExtractionResult',
                  'filename' => '/Users/naamanhirschfeld/workspace/kreuzberg-dev/kreuzberg/packages/php/stubs/kreuzberg_extension.php',
              ],
         ],
        'namespace' => 'Kreuzberg',
        'name' => 'Kreuzberg\\EmailExtractionResult',
        'shortName' => 'EmailExtractionResult',
        'isInterface' => false,
        'isTrait' => false,
        'isEnum' => false,
        'isBackedEnum' => false,
        'modifiers' => 0,
        'docComment' => '/**
 * Email extraction result.
 *
 * Complete representation of an extracted email message (.eml or .msg)
 * including headers, body content, and attachments.
 */',
        'attributes'
         => [
         ],
        'startLine' => 2730,
        'endLine' => 2789,
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
             'subject'
              => [
                  'declaringClassName' => 'Kreuzberg\\EmailExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\EmailExtractionResult',
                  'name' => 'subject',
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
                  'startLine' => 2732,
                  'endLine' => 2732,
                  'startColumn' => 9,
                  'endColumn' => 32,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'from_email'
              => [
                  'declaringClassName' => 'Kreuzberg\\EmailExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\EmailExtractionResult',
                  'name' => 'from_email',
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
                  'startLine' => 2733,
                  'endLine' => 2733,
                  'startColumn' => 9,
                  'endColumn' => 35,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'to_emails'
              => [
                  'declaringClassName' => 'Kreuzberg\\EmailExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\EmailExtractionResult',
                  'name' => 'to_emails',
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
                  'startLine' => 2735,
                  'endLine' => 2735,
                  'startColumn' => 9,
                  'endColumn' => 32,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'cc_emails'
              => [
                  'declaringClassName' => 'Kreuzberg\\EmailExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\EmailExtractionResult',
                  'name' => 'cc_emails',
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
                  'startLine' => 2737,
                  'endLine' => 2737,
                  'startColumn' => 9,
                  'endColumn' => 32,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'bcc_emails'
              => [
                  'declaringClassName' => 'Kreuzberg\\EmailExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\EmailExtractionResult',
                  'name' => 'bcc_emails',
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
                  'startLine' => 2739,
                  'endLine' => 2739,
                  'startColumn' => 9,
                  'endColumn' => 33,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'date'
              => [
                  'declaringClassName' => 'Kreuzberg\\EmailExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\EmailExtractionResult',
                  'name' => 'date',
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
                  'startLine' => 2740,
                  'endLine' => 2740,
                  'startColumn' => 9,
                  'endColumn' => 29,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'message_id'
              => [
                  'declaringClassName' => 'Kreuzberg\\EmailExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\EmailExtractionResult',
                  'name' => 'message_id',
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
                  'startLine' => 2741,
                  'endLine' => 2741,
                  'startColumn' => 9,
                  'endColumn' => 35,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'plain_text'
              => [
                  'declaringClassName' => 'Kreuzberg\\EmailExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\EmailExtractionResult',
                  'name' => 'plain_text',
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
                  'startLine' => 2742,
                  'endLine' => 2742,
                  'startColumn' => 9,
                  'endColumn' => 35,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'html_content'
              => [
                  'declaringClassName' => 'Kreuzberg\\EmailExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\EmailExtractionResult',
                  'name' => 'html_content',
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
                  'startLine' => 2743,
                  'endLine' => 2743,
                  'startColumn' => 9,
                  'endColumn' => 37,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'cleaned_text'
              => [
                  'declaringClassName' => 'Kreuzberg\\EmailExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\EmailExtractionResult',
                  'name' => 'cleaned_text',
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
                  'startLine' => 2744,
                  'endLine' => 2744,
                  'startColumn' => 9,
                  'endColumn' => 36,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'attachments'
              => [
                  'declaringClassName' => 'Kreuzberg\\EmailExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\EmailExtractionResult',
                  'name' => 'attachments',
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
                  'docComment' => '/** @var array<EmailAttachment> */',
                  'attributes'
                   => [
                   ],
                  'startLine' => 2746,
                  'endLine' => 2746,
                  'startColumn' => 9,
                  'endColumn' => 34,
                  'isPromoted' => false,
                  'declaredAtCompileTime' => true,
                  'immediateVirtual' => false,
                  'immediateHooks'
                   => [
                   ],
              ],
             'metadata'
              => [
                  'declaringClassName' => 'Kreuzberg\\EmailExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\EmailExtractionResult',
                  'name' => 'metadata',
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
                  'docComment' => '/** @var array<string, string> */',
                  'attributes'
                   => [
                   ],
                  'startLine' => 2748,
                  'endLine' => 2748,
                  'startColumn' => 9,
                  'endColumn' => 31,
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
                       'to_emails'
                        => [
                            'name' => 'to_emails',
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
                            'startLine' => 2758,
                            'endLine' => 2758,
                            'startColumn' => 13,
                            'endColumn' => 28,
                            'parameterIndex' => 0,
                            'isOptional' => false,
                        ],
                       'cc_emails'
                        => [
                            'name' => 'cc_emails',
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
                            'startLine' => 2759,
                            'endLine' => 2759,
                            'startColumn' => 13,
                            'endColumn' => 28,
                            'parameterIndex' => 1,
                            'isOptional' => false,
                        ],
                       'bcc_emails'
                        => [
                            'name' => 'bcc_emails',
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
                            'startLine' => 2760,
                            'endLine' => 2760,
                            'startColumn' => 13,
                            'endColumn' => 29,
                            'parameterIndex' => 2,
                            'isOptional' => false,
                        ],
                       'cleaned_text'
                        => [
                            'name' => 'cleaned_text',
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
                            'startLine' => 2761,
                            'endLine' => 2761,
                            'startColumn' => 13,
                            'endColumn' => 32,
                            'parameterIndex' => 3,
                            'isOptional' => false,
                        ],
                       'attachments'
                        => [
                            'name' => 'attachments',
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
                            'startLine' => 2762,
                            'endLine' => 2762,
                            'startColumn' => 13,
                            'endColumn' => 30,
                            'parameterIndex' => 4,
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
                            'startLine' => 2763,
                            'endLine' => 2763,
                            'startColumn' => 13,
                            'endColumn' => 27,
                            'parameterIndex' => 5,
                            'isOptional' => false,
                        ],
                       'subject'
                        => [
                            'name' => 'subject',
                            'default'
                             => [
                                 'code' => 'null',
                                 'attributes'
                                  => [
                                      'startLine' => 2764,
                                      'endLine' => 2764,
                                      'startTokenPos' => 15621,
                                      'startFilePos' => 91075,
                                      'endTokenPos' => 15621,
                                      'endFilePos' => 91078,
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
                            'startLine' => 2764,
                            'endLine' => 2764,
                            'startColumn' => 13,
                            'endColumn' => 35,
                            'parameterIndex' => 6,
                            'isOptional' => true,
                        ],
                       'from_email'
                        => [
                            'name' => 'from_email',
                            'default'
                             => [
                                 'code' => 'null',
                                 'attributes'
                                  => [
                                      'startLine' => 2765,
                                      'endLine' => 2765,
                                      'startTokenPos' => 15631,
                                      'startFilePos' => 91115,
                                      'endTokenPos' => 15631,
                                      'endFilePos' => 91118,
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
                            'startLine' => 2765,
                            'endLine' => 2765,
                            'startColumn' => 13,
                            'endColumn' => 38,
                            'parameterIndex' => 7,
                            'isOptional' => true,
                        ],
                       'date'
                        => [
                            'name' => 'date',
                            'default'
                             => [
                                 'code' => 'null',
                                 'attributes'
                                  => [
                                      'startLine' => 2766,
                                      'endLine' => 2766,
                                      'startTokenPos' => 15641,
                                      'startFilePos' => 91149,
                                      'endTokenPos' => 15641,
                                      'endFilePos' => 91152,
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
                            'startLine' => 2766,
                            'endLine' => 2766,
                            'startColumn' => 13,
                            'endColumn' => 32,
                            'parameterIndex' => 8,
                            'isOptional' => true,
                        ],
                       'message_id'
                        => [
                            'name' => 'message_id',
                            'default'
                             => [
                                 'code' => 'null',
                                 'attributes'
                                  => [
                                      'startLine' => 2767,
                                      'endLine' => 2767,
                                      'startTokenPos' => 15651,
                                      'startFilePos' => 91189,
                                      'endTokenPos' => 15651,
                                      'endFilePos' => 91192,
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
                            'startLine' => 2767,
                            'endLine' => 2767,
                            'startColumn' => 13,
                            'endColumn' => 38,
                            'parameterIndex' => 9,
                            'isOptional' => true,
                        ],
                       'plain_text'
                        => [
                            'name' => 'plain_text',
                            'default'
                             => [
                                 'code' => 'null',
                                 'attributes'
                                  => [
                                      'startLine' => 2768,
                                      'endLine' => 2768,
                                      'startTokenPos' => 15661,
                                      'startFilePos' => 91229,
                                      'endTokenPos' => 15661,
                                      'endFilePos' => 91232,
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
                            'startLine' => 2768,
                            'endLine' => 2768,
                            'startColumn' => 13,
                            'endColumn' => 38,
                            'parameterIndex' => 10,
                            'isOptional' => true,
                        ],
                       'html_content'
                        => [
                            'name' => 'html_content',
                            'default'
                             => [
                                 'code' => 'null',
                                 'attributes'
                                  => [
                                      'startLine' => 2769,
                                      'endLine' => 2769,
                                      'startTokenPos' => 15671,
                                      'startFilePos' => 91271,
                                      'endTokenPos' => 15671,
                                      'endFilePos' => 91274,
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
                            'startLine' => 2769,
                            'endLine' => 2769,
                            'startColumn' => 13,
                            'endColumn' => 40,
                            'parameterIndex' => 11,
                            'isOptional' => true,
                        ],
                   ],
                  'returnsReference' => false,
                  'returnType' => null,
                  'attributes'
                   => [
                   ],
                  'docComment' => '/**
 * @param array<string> $to_emails
 * @param array<string> $cc_emails
 * @param array<string> $bcc_emails
 * @param array<EmailAttachment> $attachments
 * @param array<string, string> $metadata
 */',
                  'startLine' => 2757,
                  'endLine' => 2770,
                  'startColumn' => 9,
                  'endColumn' => 12,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\EmailExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\EmailExtractionResult',
                  'currentClassName' => 'Kreuzberg\\EmailExtractionResult',
                  'aliasName' => null,
              ],
             'getSubject'
              => [
                  'name' => 'getSubject',
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
                  'startLine' => 2772,
                  'endLine' => 2772,
                  'startColumn' => 9,
                  'endColumn' => 48,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\EmailExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\EmailExtractionResult',
                  'currentClassName' => 'Kreuzberg\\EmailExtractionResult',
                  'aliasName' => null,
              ],
             'getFromEmail'
              => [
                  'name' => 'getFromEmail',
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
                  'startLine' => 2773,
                  'endLine' => 2773,
                  'startColumn' => 9,
                  'endColumn' => 50,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\EmailExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\EmailExtractionResult',
                  'currentClassName' => 'Kreuzberg\\EmailExtractionResult',
                  'aliasName' => null,
              ],
             'getToEmails'
              => [
                  'name' => 'getToEmails',
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
                  'startLine' => 2775,
                  'endLine' => 2775,
                  'startColumn' => 9,
                  'endColumn' => 47,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\EmailExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\EmailExtractionResult',
                  'currentClassName' => 'Kreuzberg\\EmailExtractionResult',
                  'aliasName' => null,
              ],
             'getCcEmails'
              => [
                  'name' => 'getCcEmails',
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
                  'startLine' => 2777,
                  'endLine' => 2777,
                  'startColumn' => 9,
                  'endColumn' => 47,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\EmailExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\EmailExtractionResult',
                  'currentClassName' => 'Kreuzberg\\EmailExtractionResult',
                  'aliasName' => null,
              ],
             'getBccEmails'
              => [
                  'name' => 'getBccEmails',
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
                  'startLine' => 2779,
                  'endLine' => 2779,
                  'startColumn' => 9,
                  'endColumn' => 48,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\EmailExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\EmailExtractionResult',
                  'currentClassName' => 'Kreuzberg\\EmailExtractionResult',
                  'aliasName' => null,
              ],
             'getDate'
              => [
                  'name' => 'getDate',
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
                  'startLine' => 2780,
                  'endLine' => 2780,
                  'startColumn' => 9,
                  'endColumn' => 45,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\EmailExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\EmailExtractionResult',
                  'currentClassName' => 'Kreuzberg\\EmailExtractionResult',
                  'aliasName' => null,
              ],
             'getMessageId'
              => [
                  'name' => 'getMessageId',
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
                  'startLine' => 2781,
                  'endLine' => 2781,
                  'startColumn' => 9,
                  'endColumn' => 50,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\EmailExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\EmailExtractionResult',
                  'currentClassName' => 'Kreuzberg\\EmailExtractionResult',
                  'aliasName' => null,
              ],
             'getPlainText'
              => [
                  'name' => 'getPlainText',
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
                  'startLine' => 2782,
                  'endLine' => 2782,
                  'startColumn' => 9,
                  'endColumn' => 50,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\EmailExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\EmailExtractionResult',
                  'currentClassName' => 'Kreuzberg\\EmailExtractionResult',
                  'aliasName' => null,
              ],
             'getHtmlContent'
              => [
                  'name' => 'getHtmlContent',
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
                  'startLine' => 2783,
                  'endLine' => 2783,
                  'startColumn' => 9,
                  'endColumn' => 52,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\EmailExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\EmailExtractionResult',
                  'currentClassName' => 'Kreuzberg\\EmailExtractionResult',
                  'aliasName' => null,
              ],
             'getCleanedText'
              => [
                  'name' => 'getCleanedText',
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
                  'startLine' => 2784,
                  'endLine' => 2784,
                  'startColumn' => 9,
                  'endColumn' => 51,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\EmailExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\EmailExtractionResult',
                  'currentClassName' => 'Kreuzberg\\EmailExtractionResult',
                  'aliasName' => null,
              ],
             'getAttachments'
              => [
                  'name' => 'getAttachments',
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
                  'docComment' => '/** @return array<EmailAttachment> */',
                  'startLine' => 2786,
                  'endLine' => 2786,
                  'startColumn' => 9,
                  'endColumn' => 50,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\EmailExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\EmailExtractionResult',
                  'currentClassName' => 'Kreuzberg\\EmailExtractionResult',
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
                            'name' => 'array',
                            'isIdentifier' => true,
                        ],
                   ],
                  'attributes'
                   => [
                   ],
                  'docComment' => '/** @return array<string, string> */',
                  'startLine' => 2788,
                  'endLine' => 2788,
                  'startColumn' => 9,
                  'endColumn' => 47,
                  'couldThrow' => false,
                  'isClosure' => false,
                  'isGenerator' => false,
                  'isVariadic' => false,
                  'modifiers' => 1,
                  'namespace' => 'Kreuzberg',
                  'declaringClassName' => 'Kreuzberg\\EmailExtractionResult',
                  'implementingClassName' => 'Kreuzberg\\EmailExtractionResult',
                  'currentClassName' => 'Kreuzberg\\EmailExtractionResult',
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
