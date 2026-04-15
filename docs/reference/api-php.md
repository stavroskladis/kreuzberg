---
title: "PHP API Reference"
---

# PHP API Reference <span class="version-badge">v4.8.5</span>

## Functions

### isBatchMode()

Check if we're currently in batch processing mode.

Returns `false` if the task-local is not set (single-file mode).

**Signature:**

```php
public static function isBatchMode(): bool
```

**Returns:** `bool`


---

### resolveThreadBudget()

Resolve the effective thread budget from config or auto-detection.

User-set `max_threads` takes priority. Otherwise auto-detects from `num_cpus`,
capped at 8 for sane defaults in serverless environments.

**Signature:**

```php
public static function resolveThreadBudget(?ConcurrencyConfig $config = null): int
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `config` | `?ConcurrencyConfig` | No | The configuration options |

**Returns:** `int`


---

### initThreadPools()

Initialize the global Rayon thread pool with the given budget.

Safe to call multiple times — only the first call takes effect (subsequent
calls are silently ignored).

**Signature:**

```php
public static function initThreadPools(int $budget): void
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `budget` | `int` | Yes | The budget |

**Returns:** `void`


---

### mergeConfigJson()

Merge extraction configuration using JSON-level field override.

Serializes the base config to JSON, merges each field from the override JSON
(top-level only), and deserializes back. This correctly handles boolean fields
explicitly set to their default values — the override always wins for any field
present in `override_json`.

Fields **not** present in `override_json` are preserved from `base`.

**Errors:**

Returns `Err` if the base config cannot be serialized, or if the merged JSON
cannot be deserialized back into `ExtractionConfig` (e.g., wrong field types).

**Signature:**

```php
public static function mergeConfigJson(ExtractionConfig $base, mixed $overrideJson): ExtractionConfig
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `base` | `ExtractionConfig` | Yes | The extraction config |
| `overrideJson` | `mixed` | Yes | The override json |

**Returns:** `ExtractionConfig`

**Errors:** Throws `String`.


---

### buildConfigFromJson()

Build extraction config by optionally merging JSON overrides into a base config.

If `override_json` is `null`, returns a clone of `base`. Otherwise delegates
to `merge_config_json`.

**Signature:**

```php
public static function buildConfigFromJson(ExtractionConfig $base, ?mixed $overrideJson = null): ExtractionConfig
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `base` | `ExtractionConfig` | Yes | The extraction config |
| `overrideJson` | `?mixed` | No | The override json |

**Returns:** `ExtractionConfig`

**Errors:** Throws `String`.


---

### isValidFormatField()

Validates whether a field name is in the known formats registry.

This uses a pre-built hash set for O(1) lookups instead of linear search,
providing significant performance improvements for repeated validations.

**Returns:**

`true` if the field is in KNOWN_FORMATS, `false` otherwise.

**Signature:**

```php
public static function isValidFormatField(string $field): bool
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `field` | `string` | Yes | The field name to validate |

**Returns:** `bool`


---

### openFileBytes()

Open a file and return its bytes with zero-copy for large files.

On non-WASM targets, files larger than `MMAP_THRESHOLD_BYTES` are
memory-mapped so that the file contents are never copied to the heap.
The mapping is read-only; the file must not be modified while the returned
`FileBytes` is alive, which is safe for document extraction.

On WASM or for small files, falls back to a plain `std::fs::read`.

**Errors:**

Returns `KreuzbergError::Io` for any I/O failure.

**Signature:**

```php
public static function openFileBytes(string $path): FileBytes
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `path` | `string` | Yes | Path to the file |

**Returns:** `FileBytes`

**Errors:** Throws `Error`.


---

### readFileAsync()

Read a file asynchronously.

**Returns:**

The file contents as bytes.

**Errors:**

Returns `KreuzbergError::Io` for I/O errors (these always bubble up).

**Signature:**

```php
public static function readFileAsync(Path $path): string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `path` | `Path` | Yes | Path to the file to read |

**Returns:** `string`

**Errors:** Throws `Error`.


---

### readFileSync()

Read a file synchronously.

**Returns:**

The file contents as bytes.

**Errors:**

Returns `KreuzbergError::Io` for I/O errors (these always bubble up).

**Signature:**

```php
public static function readFileSync(Path $path): string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `path` | `Path` | Yes | Path to the file to read |

**Returns:** `string`

**Errors:** Throws `Error`.


---

### fileExists()

Check if a file exists.

**Returns:**

`true` if the file exists, `false` otherwise.

**Signature:**

```php
public static function fileExists(Path $path): bool
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `path` | `Path` | Yes | Path to check |

**Returns:** `bool`


---

### validateFileExists()

Validate that a file exists.

**Errors:**

Returns `KreuzbergError::Io` if file doesn't exist.

**Signature:**

```php
public static function validateFileExists(Path $path): void
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `path` | `Path` | Yes | Path to validate |

**Returns:** `void`

**Errors:** Throws `Error`.


---

### findFilesByExtension()

Get all files in a directory with a specific extension.

**Returns:**

Vector of file paths with the specified extension.

**Errors:**

Returns `KreuzbergError::Io` for I/O errors.

**Signature:**

```php
public static function findFilesByExtension(Path $dir, string $extension, bool $recursive): array<string>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `dir` | `Path` | Yes | Directory to search |
| `extension` | `string` | Yes | File extension to match (without the dot) |
| `recursive` | `bool` | Yes | Whether to recursively search subdirectories |

**Returns:** `array<string>`

**Errors:** Throws `Error`.


---

### detectMimeType()

Detect MIME type from a file path.

Uses file extension to determine MIME type. Falls back to `mime_guess` crate
if extension-based detection fails.

**Returns:**

The detected MIME type string.

**Errors:**

Returns `KreuzbergError::Io` if file doesn't exist (when `check_exists` is true).
Returns `KreuzbergError::UnsupportedFormat` if MIME type cannot be determined.

**Signature:**

```php
public static function detectMimeType(Path $path, bool $checkExists): string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `path` | `Path` | Yes | Path to the file |
| `checkExists` | `bool` | Yes | Whether to verify file existence |

**Returns:** `string`

**Errors:** Throws `Error`.


---

### validateMimeType()

Validate that a MIME type is supported.

**Returns:**

The validated MIME type (may be normalized).

**Errors:**

Returns `KreuzbergError::UnsupportedFormat` if not supported.

**Signature:**

```php
public static function validateMimeType(string $mimeType): string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `mimeType` | `string` | Yes | The MIME type to validate |

**Returns:** `string`

**Errors:** Throws `Error`.


---

### detectOrValidate()

Detect or validate MIME type.

If `mime_type` is provided, validates it. Otherwise, detects from `path`.

**Returns:**

The validated MIME type string.

**Signature:**

```php
public static function detectOrValidate(?string $path = null, ?string $mimeType = null): string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `path` | `?string` | No | Optional path to detect MIME type from |
| `mimeType` | `?string` | No | Optional explicit MIME type to validate |

**Returns:** `string`

**Errors:** Throws `Error`.


---

### detectMimeTypeFromBytes()

Detect MIME type from raw file bytes.

Uses magic byte signatures to detect file type from content.
Falls back to `infer` crate for comprehensive detection.

For ZIP-based files, inspects contents to distinguish Office Open XML
formats (DOCX, XLSX, PPTX) from plain ZIP archives.

**Returns:**

The detected MIME type string.

**Errors:**

Returns `KreuzbergError::UnsupportedFormat` if MIME type cannot be determined.

**Signature:**

```php
public static function detectMimeTypeFromBytes(string $content): string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `content` | `string` | Yes | Raw file bytes |

**Returns:** `string`

**Errors:** Throws `Error`.


---

### getExtensionsForMime()

Get file extensions for a given MIME type.

Returns all known file extensions that map to the specified MIME type.

**Returns:**

A vector of file extensions (without leading dot) for the MIME type.

**Signature:**

```php
public static function getExtensionsForMime(string $mimeType): array<string>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `mimeType` | `string` | Yes | The MIME type to look up |

**Returns:** `array<string>`

**Errors:** Throws `Error`.


---

### listSupportedFormats()

List all supported document formats.

Returns a list of all file extensions and their corresponding MIME types
that Kreuzberg can process. Derived from the centralized `FORMATS` registry.

The list is sorted alphabetically by file extension.

**Signature:**

```php
public static function listSupportedFormats(): array<SupportedFormat>
```

**Returns:** `array<SupportedFormat>`


---

### runPipeline()

Run the post-processing pipeline on an `InternalDocument`.

Derives `ExtractionResult` from `InternalDocument` via the derivation pipeline,
then executes post-processing in the following order:
1. Post-Processors - Execute by stage (Early, Middle, Late) to modify/enhance the result
2. Quality Processing - Text cleaning and quality scoring
3. Chunking - Text splitting if enabled
4. Validators - Run validation hooks on the processed result (can fail fast)

**Returns:**

The processed extraction result.

**Errors:**

- Validator errors bubble up immediately
- Post-processor errors are caught and recorded in metadata
- System errors (IO, RuntimeError equivalents) always bubble up

**Signature:**

```php
public static function runPipeline(InternalDocument $doc, ExtractionConfig $config): ExtractionResult
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `doc` | `InternalDocument` | Yes | The internal document produced by the extractor |
| `config` | `ExtractionConfig` | Yes | Extraction configuration |

**Returns:** `ExtractionResult`

**Errors:** Throws `Error`.


---

### runPipelineSync()

Run the post-processing pipeline synchronously (WASM-compatible version).

This is a synchronous implementation for WASM and non-async contexts.
It performs a subset of the full async pipeline, excluding async post-processors
and validators.

**Returns:**

The processed extraction result.

**Notes:**

This function is only available when the `tokio-runtime` feature is disabled.
It handles:
- Quality processing (if enabled)
- Chunking (if enabled)
- Language detection (if enabled)

It does NOT handle:
- Async post-processors
- Async validators

**Signature:**

```php
public static function runPipelineSync(InternalDocument $doc, ExtractionConfig $config): ExtractionResult
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `doc` | `InternalDocument` | Yes | The internal document produced by the extractor |
| `config` | `ExtractionConfig` | Yes | Extraction configuration |

**Returns:** `ExtractionResult`

**Errors:** Throws `Error`.


---

### isPageTextBlank()

Determine if a page's text content indicates a blank page.

A page is blank if it has fewer than `MIN_NON_WHITESPACE_CHARS` non-whitespace characters.

**Returns:**

`true` if the page is considered blank, `false` otherwise

**Signature:**

```php
public static function isPageTextBlank(string $text): bool
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `text` | `string` | Yes | The extracted text content of the page |

**Returns:** `bool`


---

### resolveRelationships()

Resolve `RelationshipTarget::Key` entries to `RelationshipTarget::Index`.

Builds an anchor index from elements with non-`null` anchors, then resolves
each key-based relationship target. Unresolvable keys are logged and skipped
(the relationship is left as `Key` — it will be excluded from the final
`DocumentStructure` relationships).

**Signature:**

```php
public static function resolveRelationships(InternalDocument $doc): void
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `doc` | `InternalDocument` | Yes | The internal document |

**Returns:** `void`


---

### deriveDocumentStructure()

Derive a hierarchical `DocumentStructure` from the flat internal document.

Calls `resolve_relationships` first to resolve any key-based relationship targets,
then builds the tree.

# Algorithm

1. Walk elements in reading order, maintaining a stack of `(depth, NodeIndex)`.
2. Container start markers (`ListStart`, `QuoteStart`, `GroupStart`) push
   onto the stack; their matching end markers pop.
3. Headings pop the stack to a shallower depth, then create a `Group` node
   with a `Heading` child and push the group.
4. All other elements are parented under the current stack top.
5. Resolved relationships are mapped from element indices to node indices.

**Signature:**

```php
public static function deriveDocumentStructure(InternalDocument $doc): DocumentStructure
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `doc` | `InternalDocument` | Yes | The internal document |

**Returns:** `DocumentStructure`


---

### deriveExtractionResult()

Derive a complete `ExtractionResult` from an `InternalDocument`.

This is the main entry point for the derivation pipeline. It:
1. Resolves relationships (needed by renderers for footnotes)
2. Renders plain-text content (for post-processors)
3. Pre-renders formatted content if output_format != Plain
4. Groups elements by page into `PageContent`
5. Extracts OCR elements for backward compatibility
6. Optionally derives `DocumentStructure` (assumes relationships resolved)
7. Assembles the final `ExtractionResult`

**Signature:**

```php
public static function deriveExtractionResult(InternalDocument $doc, bool $includeDocumentStructure, OutputFormat $outputFormat): ExtractionResult
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `doc` | `InternalDocument` | Yes | The internal document |
| `includeDocumentStructure` | `bool` | Yes | The include document structure |
| `outputFormat` | `OutputFormat` | Yes | The output format |

**Returns:** `ExtractionResult`


---

### parseJson()

**Signature:**

```php
public static function parseJson(string $data, ?JsonExtractionConfig $config = null): StructuredDataResult
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `data` | `string` | Yes | The data |
| `config` | `?JsonExtractionConfig` | No | The configuration options |

**Returns:** `StructuredDataResult`

**Errors:** Throws `Error`.


---

### parseJsonl()

Parse JSONL (newline-delimited JSON) into a structured data result.

Each non-empty line is parsed as an independent JSON value. Blank lines
and whitespace-only lines are skipped. The output is a pretty-printed
JSON array of all parsed objects.

**Errors:**

Returns an error if any line contains invalid JSON (with 1-based line number)
or if the input is not valid UTF-8.

**Signature:**

```php
public static function parseJsonl(string $data, ?JsonExtractionConfig $config = null): StructuredDataResult
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `data` | `string` | Yes | The data |
| `config` | `?JsonExtractionConfig` | No | The configuration options |

**Returns:** `StructuredDataResult`

**Errors:** Throws `Error`.


---

### parseYaml()

**Signature:**

```php
public static function parseYaml(string $data): StructuredDataResult
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `data` | `string` | Yes | The data |

**Returns:** `StructuredDataResult`

**Errors:** Throws `Error`.


---

### parseToml()

**Signature:**

```php
public static function parseToml(string $data): StructuredDataResult
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `data` | `string` | Yes | The data |

**Returns:** `StructuredDataResult`

**Errors:** Throws `Error`.


---

### parseText()

**Signature:**

```php
public static function parseText(string $textBytes, bool $isMarkdown): TextExtractionResult
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `textBytes` | `string` | Yes | The text bytes |
| `isMarkdown` | `bool` | Yes | The is markdown |

**Returns:** `TextExtractionResult`

**Errors:** Throws `Error`.


---

### transformExtractionResultToElements()

Transform an extraction result into semantic elements.

This function takes a reference to an ExtractionResult and generates
a vector of Element structs representing semantic blocks in the document.
It detects content sections, list items, page breaks, and other structural
elements to create an Unstructured-compatible element-based output.

Handles:
- PDF hierarchy → Title/Heading elements
- Multi-page documents with correct page numbers
- Table and Image extraction
- PageBreak interleaving
- Bounding box coordinates
- Paragraph detection for NarrativeText

**Returns:**

A vector of Elements with proper semantic types and metadata.

**Signature:**

```php
public static function transformExtractionResultToElements(ExtractionResult $result): array<Element>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `result` | `ExtractionResult` | Yes | Reference to the ExtractionResult to transform |

**Returns:** `array<Element>`


---

### parseBodyText()

Parse a raw (possibly compressed) BodyText/SectionN stream.

Returns the list of sections found. Each section contains zero or more
paragraphs that carry the plain-text content.

**Signature:**

```php
public static function parseBodyText(string $data, bool $isCompressed): array<Section>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `data` | `string` | Yes | The data |
| `isCompressed` | `bool` | Yes | The is compressed |

**Returns:** `array<Section>`

**Errors:** Throws `Error`.


---

### decompressStream()

Decompress a raw-deflate stream from an HWP section.

HWP 5.0 compresses sections with raw deflate (no zlib header). Falls back
to zlib if raw deflate fails, and returns the data as-is if both fail.

**Signature:**

```php
public static function decompressStream(string $data): string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `data` | `string` | Yes | The data |

**Returns:** `string`

**Errors:** Throws `Error`.


---

### extractHwpText()

Extract all plain text from an HWP 5.0 document given its raw bytes.

**Errors:**

Returns `HwpError` if the bytes do not form a valid HWP 5.0 compound file,
if the document is password-encrypted, or if a critical parsing step fails.

**Signature:**

```php
public static function extractHwpText(string $bytes): string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `bytes` | `string` | Yes | The bytes |

**Returns:** `string`

**Errors:** Throws `Error`.


---

### loadImageForOcr()

Load image bytes for OCR, with JPEG 2000 and JBIG2 fallback support.

The standard `image` crate does not support JPEG 2000 or JBIG2 formats.
This function detects these formats by magic bytes and uses `hayro-jpeg2000`
/ `hayro-jbig2` for decoding, falling back to the standard `image` crate
for all other formats.

**Signature:**

```php
public static function loadImageForOcr(string $imageBytes): DynamicImage
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `imageBytes` | `string` | Yes | The image bytes |

**Returns:** `DynamicImage`

**Errors:** Throws `Error`.


---

### extractImageMetadata()

Extract metadata from image bytes.

Extracts dimensions, format, and EXIF data from the image.
Attempts to decode using the standard image crate first, then falls back to
pure Rust JP2 box parsing for JPEG 2000 formats if the standard decoder fails.

**Signature:**

```php
public static function extractImageMetadata(string $bytes): ImageMetadata
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `bytes` | `string` | Yes | The bytes |

**Returns:** `ImageMetadata`

**Errors:** Throws `Error`.


---

### extractTextFromImageWithOcr()

Extract text from image bytes using OCR with optional page tracking for multi-frame TIFFs.

This function:
- Detects if the image is a multi-frame TIFF
- For multi-frame TIFFs with PageConfig enabled, iterates frames and tracks boundaries
- For single-frame images or when page tracking is disabled, runs OCR on the whole image
- Returns (content, boundaries, page_contents) tuple

**Returns:**
ImageOcrResult with content and optional boundaries for pagination

**Signature:**

```php
public static function extractTextFromImageWithOcr(string $bytes, string $mimeType, string $ocrResult, ?PageConfig $pageConfig = null): ImageOcrResult
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `bytes` | `string` | Yes | Image file bytes |
| `mimeType` | `string` | Yes | MIME type (e.g., "image/tiff") |
| `ocrResult` | `string` | Yes | OCR backend result containing the text |
| `pageConfig` | `?PageConfig` | No | Optional page configuration for boundary tracking |

**Returns:** `ImageOcrResult`

**Errors:** Throws `Error`.


---

### estimateContentCapacity()

Estimate the capacity needed for content extracted from a file.

Returns an estimated byte capacity for a string buffer that will accumulate
extracted content. The estimation is based on:
- The original file size
- The content type/format
- Empirical ratios of final content size to original file size

**Returns:**

An estimated capacity in bytes suitable for `String::with_capacity()`

# Minimum Capacity

All estimates have a minimum of 64 bytes to prevent over-optimization for very
small files where the overhead of capacity estimation outweighs benefits.

**Signature:**

```php
public static function estimateContentCapacity(int $fileSize, string $format): int
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `fileSize` | `int` | Yes | The size of the original file in bytes |
| `format` | `string` | Yes | The file format/extension (e.g., "txt", "html", "docx", "xlsx", "pptx") |

**Returns:** `int`


---

### estimateHtmlMarkdownCapacity()

Estimate capacity for HTML to Markdown conversion.

HTML documents typically convert to Markdown with 60-70% of the original size.
This function estimates capacity specifically for HTML→Markdown conversion.

**Returns:**

An estimated capacity for the Markdown output

**Signature:**

```php
public static function estimateHtmlMarkdownCapacity(int $htmlSize): int
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `htmlSize` | `int` | Yes | The size of the HTML file in bytes |

**Returns:** `int`


---

### estimateSpreadsheetCapacity()

Estimate capacity for cell extraction from spreadsheets.

When extracting cell data from Excel/ODS files, the extracted cells are typically
40% of the compressed file size (since the file is ZIP-compressed).

**Returns:**

An estimated capacity for cell value accumulation

**Signature:**

```php
public static function estimateSpreadsheetCapacity(int $fileSize): int
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `fileSize` | `int` | Yes | Size of the spreadsheet file (XLSX, ODS, etc.) |

**Returns:** `int`


---

### estimatePresentationCapacity()

Estimate capacity for slide content extraction from presentations.

PPTX files when extracted have slide content at approximately 35% of the file size.
This accounts for XML overhead, compression, and embedded assets.

**Returns:**

An estimated capacity for slide content accumulation

**Signature:**

```php
public static function estimatePresentationCapacity(int $fileSize): int
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `fileSize` | `int` | Yes | Size of the PPTX file in bytes |

**Returns:** `int`


---

### estimateTableMarkdownCapacity()

Estimate capacity for markdown table generation.

Markdown tables have predictable size: ~12 bytes per cell on average
(accounting for separators, pipes, padding, and cell content).

**Returns:**

An estimated capacity for the markdown table output

**Signature:**

```php
public static function estimateTableMarkdownCapacity(int $rowCount, int $colCount): int
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `rowCount` | `int` | Yes | Number of rows in the table |
| `colCount` | `int` | Yes | Number of columns in the table |

**Returns:** `int`


---

### parseEmlContent()

Parse .eml file content (RFC822 format)

**Signature:**

```php
public static function parseEmlContent(string $data): EmailExtractionResult
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `data` | `string` | Yes | The data |

**Returns:** `EmailExtractionResult`

**Errors:** Throws `Error`.


---

### parseMsgContent()

Parse .msg file content (Outlook format).

Reads MSG files directly via the CFB (OLE Compound Document) format,
extracting text properties and attachment metadata without the overhead
of hex-encoding attachment binary data (which caused hangs on large files
with the previous `msg_parser` dependency).

Some MSG files have FAT headers declaring more sectors than the file
actually contains.  The strict `cfb` crate rejects these.  When that
happens we pad the data with zero bytes so the sector count matches
the FAT and retry – the real streams are still within the original
data range and parse correctly.

**Signature:**

```php
public static function parseMsgContent(string $data, ?int $fallbackCodepage = null): EmailExtractionResult
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `data` | `string` | Yes | The data |
| `fallbackCodepage` | `?int` | No | The fallback codepage |

**Returns:** `EmailExtractionResult`

**Errors:** Throws `Error`.


---

### extractEmailContent()

Extract email content from either .eml or .msg format

**Signature:**

```php
public static function extractEmailContent(string $data, string $mimeType, ?int $fallbackCodepage = null): EmailExtractionResult
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `data` | `string` | Yes | The data |
| `mimeType` | `string` | Yes | The mime type |
| `fallbackCodepage` | `?int` | No | The fallback codepage |

**Returns:** `EmailExtractionResult`

**Errors:** Throws `Error`.


---

### buildEmailTextOutput()

Build text output from email extraction result

**Signature:**

```php
public static function buildEmailTextOutput(EmailExtractionResult $result): string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `result` | `EmailExtractionResult` | Yes | The email extraction result |

**Returns:** `string`


---

### extractPstMessages()

Extract all email messages from a PST file.

Opens the PST file and traverses the full folder hierarchy, extracting
every message including subject, sender, recipients, and body text.

**Returns:**

A vector of `EmailExtractionResult`, one per message found.

**Errors:**

Returns an error if the PST data cannot be written to a temporary file,
or if the PST format is invalid.

**Signature:**

```php
public static function extractPstMessages(string $pstData): VecEmailExtractionResultVecProcessingWarning
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pstData` | `string` | Yes | Raw bytes of the PST file |

**Returns:** `VecEmailExtractionResultVecProcessingWarning`

**Errors:** Throws `Error`.


---

### readExcelFile()

**Signature:**

```php
public static function readExcelFile(string $filePath): ExcelWorkbook
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `filePath` | `string` | Yes | Path to the file |

**Returns:** `ExcelWorkbook`

**Errors:** Throws `Error`.


---

### readExcelBytes()

**Signature:**

```php
public static function readExcelBytes(string $data, string $fileExtension): ExcelWorkbook
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `data` | `string` | Yes | The data |
| `fileExtension` | `string` | Yes | The file extension |

**Returns:** `ExcelWorkbook`

**Errors:** Throws `Error`.


---

### excelToText()

Convert an Excel workbook to plain text (space-separated cells, one row per line).

Each sheet is separated by a blank line. Sheet names are included as headers.
This produces text suitable for quality scoring against ground truth.

**Signature:**

```php
public static function excelToText(ExcelWorkbook $workbook): string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `workbook` | `ExcelWorkbook` | Yes | The excel workbook |

**Returns:** `string`


---

### excelToMarkdown()

**Signature:**

```php
public static function excelToMarkdown(ExcelWorkbook $workbook): string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `workbook` | `ExcelWorkbook` | Yes | The excel workbook |

**Returns:** `string`


---

### extractDocText()

Extract text from DOC bytes.

Parses the OLE/CFB compound document, reads the FIB (File Information Block),
and extracts text from the piece table.

**Signature:**

```php
public static function extractDocText(string $content): DocExtractionResult
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `content` | `string` | Yes | The content to process |

**Returns:** `DocExtractionResult`

**Errors:** Throws `Error`.


---

### parseDrawing()

Parse a drawing object starting after the `<w:drawing>` Start event.

This function reads events until it encounters the closing `</w:drawing>` tag,
parsing the drawing type (inline or anchored), extent, properties, and image references.

**Signature:**

```php
public static function parseDrawing(Reader $reader): Drawing
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `reader` | `Reader` | Yes | The reader |

**Returns:** `Drawing`


---

### collectAndConvertOmathPara()

Collect an `m:oMathPara` subtree and convert to LaTeX (display math).
The reader should be positioned right after the `<m:oMathPara>` start tag.

**Signature:**

```php
public static function collectAndConvertOmathPara(Reader $reader): string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `reader` | `Reader` | Yes | The reader |

**Returns:** `string`


---

### collectAndConvertOmath()

Collect an `m:oMath` subtree and convert to LaTeX (inline math).
The reader should be positioned right after the `<m:oMath>` start tag.

**Signature:**

```php
public static function collectAndConvertOmath(Reader $reader): string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `reader` | `Reader` | Yes | The reader |

**Returns:** `string`


---

### parseDocument()

Parse a DOCX document from bytes and return the structured document.

**Signature:**

```php
public static function parseDocument(string $bytes): Document
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `bytes` | `string` | Yes | The bytes |

**Returns:** `Document`

**Errors:** Throws `Error`.


---

### extractTextFromBytes()

Extract text from DOCX bytes.

**Signature:**

```php
public static function extractTextFromBytes(string $bytes): string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `bytes` | `string` | Yes | The bytes |

**Returns:** `string`

**Errors:** Throws `Error`.


---

### parseSectionProperties()

Parse a `w:sectPr` XML element (roxmltree node) into `SectionProperties`.

**Signature:**

```php
public static function parseSectionProperties(Node $node): SectionProperties
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `node` | `Node` | Yes | The node |

**Returns:** `SectionProperties`


---

### parseSectionPropertiesStreaming()

Parse section properties from a quick_xml event stream.

Reads events from the reader until `</w:sectPr>` is encountered,
extracting the same properties as the roxmltree parser.

**Important:** This function advances the reader past the closing `</w:sectPr>` tag.
The caller must not attempt to process the `w:sectPr` end event again.

**Signature:**

```php
public static function parseSectionPropertiesStreaming(Reader $reader): SectionProperties
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `reader` | `Reader` | Yes | The reader |

**Returns:** `SectionProperties`


---

### parseStylesXml()

Parse `word/styles.xml` content into a `StyleCatalog`.

Uses `roxmltree` for tree-based XML parsing, consistent with the
office metadata parsing approach used elsewhere in the codebase.

**Signature:**

```php
public static function parseStylesXml(string $xml): StyleCatalog
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `xml` | `string` | Yes | The xml |

**Returns:** `StyleCatalog`

**Errors:** Throws `Error`.


---

### parseTableProperties()

Parse table-level properties from streaming XML reader.

Expects the reader to be positioned just after the `<w:tblPr>` start tag.
Reads all child elements until the matching `</w:tblPr>` end tag.

**Signature:**

```php
public static function parseTableProperties(Reader $reader): TableProperties
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `reader` | `Reader` | Yes | The reader |

**Returns:** `TableProperties`


---

### parseRowProperties()

Parse row-level properties from streaming XML reader.

Expects the reader to be positioned just after the `<w:trPr>` start tag.

**Signature:**

```php
public static function parseRowProperties(Reader $reader): RowProperties
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `reader` | `Reader` | Yes | The reader |

**Returns:** `RowProperties`


---

### parseCellProperties()

Parse cell-level properties from streaming XML reader.

Expects the reader to be positioned just after the `<w:tcPr>` start tag.

**Signature:**

```php
public static function parseCellProperties(Reader $reader): CellProperties
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `reader` | `Reader` | Yes | The reader |

**Returns:** `CellProperties`


---

### parseTableGrid()

Parse table grid (column widths) from streaming XML reader.

Expects the reader to be positioned just after the `<w:tblGrid>` start tag.

**Signature:**

```php
public static function parseTableGrid(Reader $reader): TableGrid
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `reader` | `Reader` | Yes | The reader |

**Returns:** `TableGrid`


---

### parseThemeXml()

Parse `word/theme/theme1.xml` content into a `Theme`.

Uses `roxmltree` for tree-based XML parsing of DrawingML theme elements.

**Returns:**
* `Ok(Theme)` - The parsed theme
* `Err(KreuzbergError)` - If parsing fails

**Signature:**

```php
public static function parseThemeXml(string $xml): Theme
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `xml` | `string` | Yes | The theme XML content as a string |

**Returns:** `Theme`

**Errors:** Throws `Error`.


---

### extractText()

Extract text from DOCX bytes.

**Signature:**

```php
public static function extractText(string $bytes): string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `bytes` | `string` | Yes | The bytes |

**Returns:** `string`

**Errors:** Throws `Error`.


---

### extractTextWithPageBreaks()

Extract text and page boundaries from DOCX bytes.

Detects explicit page breaks (`<w:br w:type="page"/>`) in the document XML and maps them to
character offsets in the extracted text. This is a best-effort approach that only detects
explicit page breaks, not automatic pagination.

**Returns:**
* `Ok((String, Option<Vec<PageBoundary>>))` - Extracted text and optional page boundaries
* `Err(KreuzbergError)` - If extraction fails

# Limitations
- Only detects explicit page breaks, not reflowed content
- Page numbers are estimates, not guaranteed accurate
- Word's pagination may differ from detected breaks
- No page dimensions available (would require layout engine)

# Performance
Performs two passes: one with docx-lite for text extraction and one for page break detection.

**Signature:**

```php
public static function extractTextWithPageBreaks(string $bytes): StringOptionVecPageBoundary
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `bytes` | `string` | Yes | The DOCX file contents as bytes |

**Returns:** `StringOptionVecPageBoundary`

**Errors:** Throws `Error`.


---

### detectPageBreaksFromDocx()

Detect explicit page break positions in document.xml and extract full text with page boundaries.

This is a convenience function for the extractor that combines text extraction with page
break detection. It returns the extracted text along with page boundaries.

**Returns:**
* `Ok(Option<Vec<PageBoundary>>)` - Optional page boundaries
* `Err(KreuzbergError)` - If extraction fails

# Limitations
- Only detects explicit page breaks, not reflowed content
- Page numbers are estimates based on detected breaks

**Signature:**

```php
public static function detectPageBreaksFromDocx(string $bytes): ?array<PageBoundary>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `bytes` | `string` | Yes | The DOCX file contents (ZIP archive) |

**Returns:** `?array<PageBoundary>`

**Errors:** Throws `Error`.


---

### extractOoxmlEmbeddedObjects()

Extract embedded objects from an OOXML ZIP archive and recursively process them.

Scans the given `embeddings_prefix` directory (e.g. `word/embeddings/` or
`ppt/embeddings/`) inside the ZIP archive for embedded files. Known formats
(.xlsx, .pdf, .docx, .pptx, etc.) are recursively extracted. OLE compound
files (oleObject*.bin) are skipped with a warning unless their format can be
identified.

Returns `(children, warnings)` suitable for attaching to `InternalDocument`.

**Signature:**

```php
public static function extractOoxmlEmbeddedObjects(string $zipBytes, string $embeddingsPrefix, string $sourceLabel, ExtractionConfig $config): VecArchiveEntryVecProcessingWarning
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `zipBytes` | `string` | Yes | The zip bytes |
| `embeddingsPrefix` | `string` | Yes | The embeddings prefix |
| `sourceLabel` | `string` | Yes | The source label |
| `config` | `ExtractionConfig` | Yes | The configuration options |

**Returns:** `VecArchiveEntryVecProcessingWarning`


---

### detectImageFormat()

Detect image format from raw bytes using magic byte signatures.

Returns a format string like "jpeg", "png", etc. Used by both DOCX and PPTX extractors.

**Signature:**

```php
public static function detectImageFormat(string $data): Str
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `data` | `string` | Yes | The data |

**Returns:** `Str`


---

### processImagesWithOcr()

Process extracted images with OCR if configured.

For each image, spawns a blocking OCR task and stores the result
in `image.ocr_result`. If OCR is not configured or fails for an
individual image, that image's `ocr_result` remains `null`.

This function is the single shared implementation used by all
document extractors (DOCX, PPTX, Jupyter, Markdown, etc.).

# Recursion Safety

The produced `ExtractionResult` for each image explicitly sets
`images: None`, preventing further image extraction cycles when
OCR results are consumed by archive or recursive extraction paths.

# Concurrency

Concurrency is bounded by the configured thread budget
using a semaphore to prevent resource exhaustion.

**Signature:**

```php
public static function processImagesWithOcr(array<ExtractedImage> $images, ExtractionConfig $config): array<ExtractedImage>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `images` | `array<ExtractedImage>` | Yes | The images |
| `config` | `ExtractionConfig` | Yes | The configuration options |

**Returns:** `array<ExtractedImage>`

**Errors:** Throws `Error`.


---

### extractPptText()

Extract text from PPT bytes.

Parses the OLE/CFB compound document, reads the "PowerPoint Document" stream,
and extracts text from TextCharsAtom and TextBytesAtom records.

When `include_master_slides` is `true`, master slide content (placeholder text
like "Click to edit Master title style") is included instead of being skipped.

**Signature:**

```php
public static function extractPptText(string $content): PptExtractionResult
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `content` | `string` | Yes | The content to process |

**Returns:** `PptExtractionResult`

**Errors:** Throws `Error`.


---

### extractPptTextWithOptions()

Extract text from PPT bytes with configurable master slide inclusion.

When `include_master_slides` is `true`, `RT_MAIN_MASTER` containers are not
skipped, so master slide placeholder text is included in the output.

**Signature:**

```php
public static function extractPptTextWithOptions(string $content, bool $includeMasterSlides): PptExtractionResult
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `content` | `string` | Yes | The content to process |
| `includeMasterSlides` | `bool` | Yes | The include master slides |

**Returns:** `PptExtractionResult`

**Errors:** Throws `Error`.


---

### extractPptxFromPath()

Extract PPTX content from a file path.

**Returns:**

A `PptxExtractionResult` containing extracted content, metadata, and images.

**Signature:**

```php
public static function extractPptxFromPath(string $path, PptxExtractionOptions $options): PptxExtractionResult
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `path` | `string` | Yes | Path to the PPTX file |
| `options` | `PptxExtractionOptions` | Yes | Extraction options controlling image extraction, formatting, etc. |

**Returns:** `PptxExtractionResult`

**Errors:** Throws `Error`.


---

### extractPptxFromBytes()

Extract PPTX content from a byte buffer.

**Returns:**

A `PptxExtractionResult` containing extracted content, metadata, and images.

**Signature:**

```php
public static function extractPptxFromBytes(string $data, PptxExtractionOptions $options): PptxExtractionResult
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `data` | `string` | Yes | Raw PPTX file bytes |
| `options` | `PptxExtractionOptions` | Yes | Extraction options controlling image extraction, formatting, etc. |

**Returns:** `PptxExtractionResult`

**Errors:** Throws `Error`.


---

### parseXmlSvg()

Parse XML with optional SVG mode.

In SVG mode, only text from SVG text-bearing elements (`<text>`, `<tspan>`,
`<title>`, `<desc>`, `<textPath>`) is extracted, without element name prefixes.
Attribute values are also omitted in SVG mode.

**Signature:**

```php
public static function parseXmlSvg(string $xmlBytes, bool $preserveWhitespace): XmlExtractionResult
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `xmlBytes` | `string` | Yes | The xml bytes |
| `preserveWhitespace` | `bool` | Yes | The preserve whitespace |

**Returns:** `XmlExtractionResult`

**Errors:** Throws `Error`.


---

### parseXml()

**Signature:**

```php
public static function parseXml(string $xmlBytes, bool $preserveWhitespace): XmlExtractionResult
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `xmlBytes` | `string` | Yes | The xml bytes |
| `preserveWhitespace` | `bool` | Yes | The preserve whitespace |

**Returns:** `XmlExtractionResult`

**Errors:** Throws `Error`.


---

### cellsToText()

Converts a 2D vector of cell strings into a GitHub-Flavored Markdown table.

# Behavior

- The first row is treated as the header row
- A separator row is inserted after the header
- Pipe characters (`|`) in cell content are automatically escaped with backslash
- Irregular tables (rows with varying column counts) are padded with empty cells to match the header
- Returns an empty string for empty input

**Returns:**

A `String` containing the GFM markdown table representation

Converts a 2D vector of cell strings into plain text with tab-separated columns.

# Behavior

- Rows are separated by newlines
- Cells within a row are separated by tab characters
- No pipe delimiters or separator rows (unlike markdown tables)
- Returns an empty string for empty input

**Returns:**

A `String` containing the plain text table representation

**Signature:**

```php
public static function cellsToText(array<array<string>> $cells): string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `cells` | `array<array<string>>` | Yes | A slice of vectors representing table rows, where each inner vector contains cell values |

**Returns:** `string`


---

### cellsToMarkdown()

**Signature:**

```php
public static function cellsToMarkdown(array<array<string>> $cells): string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `cells` | `array<array<string>>` | Yes | The cells |

**Returns:** `string`


---

### parseJotdownAttributes()

Parse jotdown attributes into our Attributes representation.

Converts jotdown's internal attribute representation to Kreuzberg's
standardized Attributes struct, handling IDs, classes, and key-value pairs.

**Signature:**

```php
public static function parseJotdownAttributes(Attributes $attrs): Attributes
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `attrs` | `Attributes` | Yes | The attributes |

**Returns:** `Attributes`


---

### renderAttributes()

Render attributes to djot attribute syntax.

Converts Kreuzberg's Attributes struct back to djot attribute syntax:
{.class #id key="value"}

**Signature:**

```php
public static function renderAttributes(Attributes $attrs): string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `attrs` | `Attributes` | Yes | The attributes |

**Returns:** `string`


---

### djotContentToDjot()

Convert DjotContent back to djot markup.

This function takes a `DjotContent` structure and generates valid djot markup
from it, preserving:
- Block structure (headings, code blocks, lists, blockquotes, etc.)
- Inline formatting (strong, emphasis, highlight, subscript, superscript, etc.)
- Attributes where present ({.class #id key="value"})

**Returns:**

A String containing valid djot markup

**Signature:**

```php
public static function djotContentToDjot(DjotContent $content): string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `content` | `DjotContent` | Yes | The DjotContent to convert |

**Returns:** `string`


---

### extractionResultToDjot()

Convert any ExtractionResult to djot format.

This function converts an `ExtractionResult` to djot markup:
- If `djot_content` is `Some`, uses `djot_content_to_djot` for full fidelity conversion
- Otherwise, wraps the plain text content in paragraphs

**Returns:**

A `Result` containing the djot markup string

**Signature:**

```php
public static function extractionResultToDjot(ExtractionResult $result): string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `result` | `ExtractionResult` | Yes | The ExtractionResult to convert |

**Returns:** `string`

**Errors:** Throws `Error`.


---

### djotToHtml()

Render djot content to HTML.

This function takes djot source text and renders it to HTML using jotdown's
built-in HTML renderer.

**Returns:**

A `Result` containing the rendered HTML string

**Signature:**

```php
public static function djotToHtml(string $djotSource): string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `djotSource` | `string` | Yes | The djot markup text to render |

**Returns:** `string`

**Errors:** Throws `Error`.


---

### renderBlockToDjot()

Render a single block to djot markup.

**Signature:**

```php
public static function renderBlockToDjot(string $output, FormattedBlock $block, int $indentLevel): void
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `output` | `string` | Yes | The output destination |
| `block` | `FormattedBlock` | Yes | The formatted block |
| `indentLevel` | `int` | Yes | The indent level |

**Returns:** `void`


---

### renderListItem()

Render a list item with the given marker.

**Signature:**

```php
public static function renderListItem(string $output, FormattedBlock $item, string $indent, string $marker): void
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `output` | `string` | Yes | The output destination |
| `item` | `FormattedBlock` | Yes | The formatted block |
| `indent` | `string` | Yes | The indent |
| `marker` | `string` | Yes | The marker |

**Returns:** `void`


---

### renderInlineContent()

Render inline content to djot markup.

**Signature:**

```php
public static function renderInlineContent(string $output, array<InlineElement> $elements): void
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `output` | `string` | Yes | The output destination |
| `elements` | `array<InlineElement>` | Yes | The elements |

**Returns:** `void`


---

### extractFrontmatter()

Extract YAML frontmatter from document content.

Frontmatter is expected to be delimited by `---` or `...` at the start of the document.
This implementation properly handles edge cases:
- `---` appearing within YAML strings or arrays
- Both `---` and `...` as end delimiters (YAML spec compliant)
- Multiline YAML values containing dashes

Returns a tuple of (parsed YAML value, remaining content after frontmatter).

**Signature:**

```php
public static function extractFrontmatter(string $content): OptionYamlValueString
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `content` | `string` | Yes | The content to process |

**Returns:** `OptionYamlValueString`


---

### extractMetadataFromYaml()

Extract metadata from YAML frontmatter.

Extracts the following YAML fields into Kreuzberg metadata:
- **Standard fields**: title, author, date, description (as subject)
- **Extended fields**: abstract, subject, category, tags, language, version
- **Array fields** (keywords, tags): stored as `Vec<String>` in typed fields

**Returns:**

A `Metadata` struct populated with extracted fields

**Signature:**

```php
public static function extractMetadataFromYaml(YamlValue $yaml): Metadata
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `yaml` | `YamlValue` | Yes | The parsed YAML value from frontmatter |

**Returns:** `Metadata`


---

### extractTitleFromContent()

Extract first heading as title from content.

Searches for the first level-1 heading (# Title) in the content
and returns it as a potential title if no title was found in frontmatter.

**Returns:**

Some(title) if a heading is found, None otherwise

**Signature:**

```php
public static function extractTitleFromContent(string $content): ?string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `content` | `string` | Yes | The document content to search |

**Returns:** `?string`


---

### collectIwaPaths()

Collects all .iwa file paths from a ZIP archive.

Opens the ZIP from `content`, iterates every entry, and returns the names of
all entries whose path ends with `.iwa`. Entries that cannot be read are
silently skipped (consistent with the per-extractor `filter_map` pattern).

**Signature:**

```php
public static function collectIwaPaths(string $content): array<string>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `content` | `string` | Yes | The content to process |

**Returns:** `array<string>`

**Errors:** Throws `Error`.


---

### readIwaFile()

Read and Snappy-decompress a single `.iwa` file from the ZIP archive.

Apple IWA files use a custom framing format:
Each block in the file is: `[type: u8][length: u24 LE][payload: length bytes]`
- type `0x00`: Snappy-compressed block → decompress payload with raw Snappy
- type `0x01`: Uncompressed block → use payload as-is

Multiple blocks are concatenated to form the decompressed IWA stream.

**Signature:**

```php
public static function readIwaFile(string $content, string $path): string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `content` | `string` | Yes | The content to process |
| `path` | `string` | Yes | Path to the file |

**Returns:** `string`

**Errors:** Throws `Error`.


---

### decodeIwaStream()

Decode an Apple IWA byte stream into the raw protobuf payload.

IWA framing: each block = 1 byte type + 3 bytes LE length + N bytes payload
- type 0x00 → Snappy-compressed, decompress with `snap::raw::Decoder`
- type 0x01 → Uncompressed, use as-is

**Signature:**

```php
public static function decodeIwaStream(string $data): string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `data` | `string` | Yes | The data |

**Returns:** `string`

**Errors:** Throws `String`.


---

### extractTextFromProto()

Extract all UTF-8 text strings from a raw protobuf byte slice.

This uses a simple wire-format scanner without a full schema:
- Field type 2 (length-delimited) with a valid UTF-8 payload of ≥3 bytes is
  treated as a text string candidate.
- We skip binary blobs (non-UTF-8) and very short noise strings.

This approach avoids the need for `prost-build` and generated proto code while
still extracting human-readable text reliably from iWork documents.

**Signature:**

```php
public static function extractTextFromProto(string $data): array<string>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `data` | `string` | Yes | The data |

**Returns:** `array<string>`


---

### extractTextFromIwaFiles()

Extract all text from an iWork ZIP archive by reading specified IWA entries.

`iwa_paths` should list the IWA file paths to read (e.g. `["Index/Document.iwa"]`).
Returns a flat joined string of all text found across all IWA files.

**Signature:**

```php
public static function extractTextFromIwaFiles(string $content, array<string> $iwaPaths): string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `content` | `string` | Yes | The content to process |
| `iwaPaths` | `array<string>` | Yes | The iwa paths |

**Returns:** `string`

**Errors:** Throws `Error`.


---

### extractMetadataFromZip()

Extract metadata from an iWork ZIP archive.

Attempts to read `Metadata/Properties.plist` and
`Metadata/BuildVersionHistory.plist` from the ZIP. These files are XML plists
containing authorship and creation information. If the files cannot be read
or parsed, an empty `Metadata` is returned.

**Signature:**

```php
public static function extractMetadataFromZip(string $content): Metadata
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `content` | `string` | Yes | The content to process |

**Returns:** `Metadata`


---

### dedupText()

Deduplicate a list of text strings while preserving order.
Adjacent duplicates and near-duplicates are removed.

**Signature:**

```php
public static function dedupText(array<string> $texts): array<string>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `texts` | `array<string>` | Yes | The texts |

**Returns:** `array<string>`


---

### ensureInitialized()

Ensure built-in extractors are registered.

This function is called automatically on first extraction operation.
It's safe to call multiple times - registration only happens once,
unless the registry was cleared, in which case extractors are re-registered.

**Signature:**

```php
public static function ensureInitialized(): void
```

**Returns:** `void`

**Errors:** Throws `Error`.


---

### registerDefaultExtractors()

Register all built-in extractors with the global registry.

This function should be called once at application startup to register
the default extractors (PlainText, Markdown, XML, etc.).

**Note:** This is called automatically on first extraction operation.
Explicit calling is optional.

**Signature:**

```php
public static function registerDefaultExtractors(): void
```

**Returns:** `void`

**Errors:** Throws `Error`.


---

### extractPanicMessage()

Extracts a human-readable message from a panic payload.

Attempts to downcast the panic payload to common types (String, &str)
to extract a meaningful error message.

Message is truncated to 4KB to prevent DoS attacks via extremely large panic messages.

**Returns:**

A string representation of the panic message (truncated if necessary)

**Signature:**

```php
public static function extractPanicMessage(Any $panicInfo): string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `panicInfo` | `Any` | Yes | The panic payload from catch_unwind |

**Returns:** `string`


---

### getOcrBackendRegistry()

Get the global OCR backend registry.

**Signature:**

```php
public static function getOcrBackendRegistry(): RwLock
```

**Returns:** `RwLock`


---

### getDocumentExtractorRegistry()

Get the global document extractor registry.

**Signature:**

```php
public static function getDocumentExtractorRegistry(): RwLock
```

**Returns:** `RwLock`


---

### getPostProcessorRegistry()

Get the global post-processor registry.

**Signature:**

```php
public static function getPostProcessorRegistry(): RwLock
```

**Returns:** `RwLock`


---

### getValidatorRegistry()

Get the global validator registry.

**Signature:**

```php
public static function getValidatorRegistry(): RwLock
```

**Returns:** `RwLock`


---

### getRendererRegistry()

Get the global renderer registry.

**Signature:**

```php
public static function getRendererRegistry(): RwLock
```

**Returns:** `RwLock`


---

### validatePluginsAtStartup()

Validate plugin registries at startup and emit diagnostic logs.

This function is designed to be called when the API server starts
to help diagnose configuration issues early. It checks:

- Whether OCR backends are registered (warns if none)
- Whether document extractors are registered (warns if none)
- Environment variables that might affect plugin initialization
- File permission issues in containerized environments

For Kubernetes deployments, this logs information that helps with
troubleshooting in the container logs.

**Returns:**

- `Ok(PluginHealthStatus)` with diagnostic information
- `Err(KreuzbergError)` if critical issues are detected (currently always succeeds)

**Signature:**

```php
public static function validatePluginsAtStartup(): PluginHealthStatus
```

**Returns:** `PluginHealthStatus`

**Errors:** Throws `Error`.


---

### sanitizeFilename()

Sanitize a file path to return only the filename (no directory).

Prevents PII from appearing in traces.

**Signature:**

```php
public static function sanitizeFilename(string $path): string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `path` | `string` | Yes | Path to the file |

**Returns:** `string`


---

### getMetrics()

Get the global extraction metrics, initialising on first call.

Uses the global `opentelemetry::global::meter` to create instruments.

**Signature:**

```php
public static function getMetrics(): ExtractionMetrics
```

**Returns:** `ExtractionMetrics`


---

### recordErrorOnCurrentSpan()

Record an error on the current span using semantic conventions.

Sets `otel.status_code = "ERROR"`, `kreuzberg.error.type`, and `error.message`.

**Signature:**

```php
public static function recordErrorOnCurrentSpan(KreuzbergError $error): void
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `error` | `KreuzbergError` | Yes | The kreuzberg error |

**Returns:** `void`


---

### recordSuccessOnCurrentSpan()

Record extraction success on the current span.

**Signature:**

```php
public static function recordSuccessOnCurrentSpan(): void
```

**Returns:** `void`


---

### sanitizePath()

Sanitize a file path to return only the filename.

Prevents PII (personally identifiable information) from appearing in
traces by only recording filenames instead of full paths.

**Signature:**

```php
public static function sanitizePath(string $path): string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `path` | `string` | Yes | Path to the file |

**Returns:** `string`


---

### extractorSpan()

Create an extractor-level span with semantic convention fields.

Returns a `tracing::Span` with all `kreuzberg.extractor.*` and
`kreuzberg.document.*` fields pre-allocated (set to `Empty` for
lazy recording).

**Signature:**

```php
public static function extractorSpan(string $extractorName, string $mimeType, int $sizeBytes): Span
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `extractorName` | `string` | Yes | The extractor name |
| `mimeType` | `string` | Yes | The mime type |
| `sizeBytes` | `int` | Yes | The size bytes |

**Returns:** `Span`


---

### pipelineStageSpan()

Create a pipeline stage span.

**Signature:**

```php
public static function pipelineStageSpan(string $stage): Span
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `stage` | `string` | Yes | The stage |

**Returns:** `Span`


---

### pipelineProcessorSpan()

Create a pipeline processor span.

**Signature:**

```php
public static function pipelineProcessorSpan(string $stage, string $processorName): Span
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `stage` | `string` | Yes | The stage |
| `processorName` | `string` | Yes | The processor name |

**Returns:** `Span`


---

### ocrSpan()

Create an OCR operation span.

**Signature:**

```php
public static function ocrSpan(string $backend, string $language): Span
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `backend` | `string` | Yes | The backend |
| `language` | `string` | Yes | The language |

**Returns:** `Span`


---

### modelInferenceSpan()

Create a model inference span.

**Signature:**

```php
public static function modelInferenceSpan(string $modelName): Span
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `modelName` | `string` | Yes | The model name |

**Returns:** `Span`


---

### fromUtf8()

Validates and converts bytes to string using SIMD when available.

This function attempts to use SIMD UTF-8 validation if the `simd-utf8` feature
is enabled and the platform supports it. Otherwise, it falls back to the standard
`std::str::from_utf8()` validation.

**Returns:**

`Ok(&str)` if the bytes are valid UTF-8, `Err(std::str::Utf8Error)` otherwise.

**Safety:**

This function is safe and does not use any unsafe code directly. The underlying
SIMD validation (when enabled) is contained within the simdutf8 crate and is safe.

**Signature:**

```php
public static function fromUtf8(string $bytes): string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `bytes` | `string` | Yes | The byte slice to validate and convert |

**Returns:** `string`

**Errors:** Throws `Utf8Error`.


---

### stringFromUtf8()

Validates and converts owned bytes to String using SIMD when available.

This function converts bytes to an owned String, validating UTF-8 using SIMD
when available. The caller's bytes are consumed to create the String.

**Returns:**

`Ok(String)` if the bytes are valid UTF-8, `Err(std::string::FromUtf8Error)` otherwise.

# Performance

When enabled, SIMD validation significantly reduces the time spent on validation,
especially for large text documents.

**Signature:**

```php
public static function stringFromUtf8(string $bytes): string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `bytes` | `string` | Yes | The byte vector to validate and convert |

**Returns:** `string`

**Errors:** Throws `FromUtf8Error`.


---

### isValidUtf8()

Validates bytes as UTF-8 without conversion to string slice.

Returns `true` if the bytes represent valid UTF-8, `false` otherwise.
This is useful when you only need to check validity without constructing a string.

**Returns:**

`true` if valid UTF-8, `false` otherwise.

# Performance

This function is optimized for early exit on invalid sequences.

**Signature:**

```php
public static function isValidUtf8(string $bytes): bool
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `bytes` | `string` | Yes | The byte slice to validate |

**Returns:** `bool`


---

### calculateQualityScore()

**Signature:**

```php
public static function calculateQualityScore(string $text, ?AHashMap $metadata = null): float
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `text` | `string` | Yes | The text |
| `metadata` | `?AHashMap` | No | The a hash map |

**Returns:** `float`


---

### cleanExtractedText()

**Signature:**

```php
public static function cleanExtractedText(string $text): string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `text` | `string` | Yes | The text |

**Returns:** `string`


---

### normalizeSpaces()

**Signature:**

```php
public static function normalizeSpaces(string $text): string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `text` | `string` | Yes | The text |

**Returns:** `string`


---

### reduceTokens()

Reduces token count in text while preserving meaning and structure.

This function removes stopwords, redundancy, and applies compression techniques
based on the specified reduction level. Supports 64 languages with automatic
stopword removal and optional semantic clustering.

**Returns:**

Returns the reduced text with preserved structure (markdown, code blocks).

**Errors:**

Returns an error if the language hint is invalid or stopwords cannot be loaded.

**Signature:**

```php
public static function reduceTokens(string $text, TokenReductionConfig $config, ?string $languageHint = null): string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `text` | `string` | Yes | The input text to reduce |
| `config` | `TokenReductionConfig` | Yes | Configuration specifying reduction level and options |
| `languageHint` | `?string` | No | Optional ISO 639-3 language code (e.g., "eng", "spa") |

**Returns:** `string`

**Errors:** Throws `Error`.


---

### batchReduceTokens()

Reduces token count for multiple texts efficiently using parallel processing.

This function processes multiple texts in parallel using Rayon, providing
significant performance improvements for batch operations. All texts use the
same configuration and language hint for consistency.

**Returns:**

Returns a vector of reduced texts in the same order as the input.

**Errors:**

Returns an error if the language hint is invalid or stopwords cannot be loaded.

**Signature:**

```php
public static function batchReduceTokens(array<string> $texts, TokenReductionConfig $config, ?string $languageHint = null): array<string>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `texts` | `array<string>` | Yes | Slice of text references to reduce |
| `config` | `TokenReductionConfig` | Yes | Configuration specifying reduction level and options |
| `languageHint` | `?string` | No | Optional ISO 639-3 language code (e.g., "eng", "spa") |

**Returns:** `array<string>`

**Errors:** Throws `Error`.


---

### getReductionStatistics()

Calculates detailed statistics comparing original and reduced text.

Provides comprehensive metrics including reduction percentages and absolute
counts for both characters and tokens. Useful for analyzing the effectiveness
of token reduction and monitoring compression ratios.

**Returns:**

Returns a tuple with the following statistics (in order):
1. `char_reduction` (f64) - Character reduction ratio (0.0 to 1.0)
2. `token_reduction` (f64) - Token reduction ratio (0.0 to 1.0)
3. `original_chars` (usize) - Original character count
4. `reduced_chars` (usize) - Reduced character count
5. `original_tokens` (usize) - Original token count (whitespace-delimited)
6. `reduced_tokens` (usize) - Reduced token count (whitespace-delimited)

**Signature:**

```php
public static function getReductionStatistics(string $original, string $reduced): F64F64UsizeUsizeUsizeUsize
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `original` | `string` | Yes | The original text before reduction |
| `reduced` | `string` | Yes | The reduced text after applying token reduction |

**Returns:** `F64F64UsizeUsizeUsizeUsize`


---

### bold()

Create a bold annotation for the given byte range.

**Signature:**

```php
public static function bold(int $start, int $end): TextAnnotation
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `start` | `int` | Yes | The start |
| `end` | `int` | Yes | The end |

**Returns:** `TextAnnotation`


---

### italic()

Create an italic annotation for the given byte range.

**Signature:**

```php
public static function italic(int $start, int $end): TextAnnotation
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `start` | `int` | Yes | The start |
| `end` | `int` | Yes | The end |

**Returns:** `TextAnnotation`


---

### underline()

Create an underline annotation for the given byte range.

**Signature:**

```php
public static function underline(int $start, int $end): TextAnnotation
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `start` | `int` | Yes | The start |
| `end` | `int` | Yes | The end |

**Returns:** `TextAnnotation`


---

### link()

Create a link annotation for the given byte range.

**Signature:**

```php
public static function link(int $start, int $end, string $url, ?string $title = null): TextAnnotation
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `start` | `int` | Yes | The start |
| `end` | `int` | Yes | The end |
| `url` | `string` | Yes | The URL to fetch |
| `title` | `?string` | No | The title |

**Returns:** `TextAnnotation`


---

### code()

Create a code (inline) annotation for the given byte range.

**Signature:**

```php
public static function code(int $start, int $end): TextAnnotation
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `start` | `int` | Yes | The start |
| `end` | `int` | Yes | The end |

**Returns:** `TextAnnotation`


---

### strikethrough()

Create a strikethrough annotation for the given byte range.

**Signature:**

```php
public static function strikethrough(int $start, int $end): TextAnnotation
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `start` | `int` | Yes | The start |
| `end` | `int` | Yes | The end |

**Returns:** `TextAnnotation`


---

### subscript()

Create a subscript annotation for the given byte range.

**Signature:**

```php
public static function subscript(int $start, int $end): TextAnnotation
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `start` | `int` | Yes | The start |
| `end` | `int` | Yes | The end |

**Returns:** `TextAnnotation`


---

### superscript()

Create a superscript annotation for the given byte range.

**Signature:**

```php
public static function superscript(int $start, int $end): TextAnnotation
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `start` | `int` | Yes | The start |
| `end` | `int` | Yes | The end |

**Returns:** `TextAnnotation`


---

### fontSize()

Create a font size annotation for the given byte range.

**Signature:**

```php
public static function fontSize(int $start, int $end, string $value): TextAnnotation
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `start` | `int` | Yes | The start |
| `end` | `int` | Yes | The end |
| `value` | `string` | Yes | The value |

**Returns:** `TextAnnotation`


---

### color()

Create a color annotation for the given byte range.

**Signature:**

```php
public static function color(int $start, int $end, string $value): TextAnnotation
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `start` | `int` | Yes | The start |
| `end` | `int` | Yes | The end |
| `value` | `string` | Yes | The value |

**Returns:** `TextAnnotation`


---

### highlight()

Create a highlight annotation for the given byte range.

**Signature:**

```php
public static function highlight(int $start, int $end): TextAnnotation
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `start` | `int` | Yes | The start |
| `end` | `int` | Yes | The end |

**Returns:** `TextAnnotation`


---

### classifyUri()

Classify a URL string into the appropriate `UriKind`.

- `mailto:` → `Email`
- `#` prefix → `Anchor`
- everything else → `Hyperlink`

**Signature:**

```php
public static function classifyUri(string $url): UriKind
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `url` | `string` | Yes | The URL to fetch |

**Returns:** `UriKind`


---

### safeDecode()

Decode raw bytes into UTF-8, using heuristics and fallback encodings when necessary.

The function prefers an explicit `encoding`, falls back to the cached guess, probes
an encoding detector, and finally tries a small curated list before returning a
mojibake-cleaned string.

**Signature:**

```php
public static function safeDecode(string $byteData, ?string $encoding = null): string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `byteData` | `string` | Yes | The byte data |
| `encoding` | `?string` | No | The encoding |

**Returns:** `string`


---

### calculateTextConfidence()

Estimate how trustworthy a decoded string is on a 0.0–1.0 scale.

Scores close to 1.0 indicate mostly printable characters, whereas lower scores
point to mojibake, control characters, or suspicious character mixes.

**Signature:**

```php
public static function calculateTextConfidence(string $text): float
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `text` | `string` | Yes | The text |

**Returns:** `float`


---

### fixMojibake()

Strip control characters and replacement glyphs that typically arise from mojibake.

**Signature:**

```php
public static function fixMojibake(string $text): Str
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `text` | `string` | Yes | The text |

**Returns:** `Str`


---

### snakeToCamel()

Recursively convert snake_case keys in a JSON Value to camelCase.

This is used by language bindings (Node.js, Go, Java, C#, etc.) to provide
a consistent camelCase API for consumers even though the Rust core uses snake_case.

**Signature:**

```php
public static function snakeToCamel(Value $val): Value
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `val` | `Value` | Yes | The value |

**Returns:** `Value`


---

### camelToSnake()

Recursively convert camelCase keys in a JSON Value to snake_case.

This is the inverse of `snake_to_camel`. Used by WASM bindings to accept
camelCase config from JavaScript while the Rust core expects snake_case.

**Signature:**

```php
public static function camelToSnake(Value $val): Value
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `val` | `Value` | Yes | The value |

**Returns:** `Value`


---

### createStringBufferPool()

Create a pre-configured string buffer pool for batch processing.

**Returns:**

A pool configured for text accumulation with reasonable defaults.

**Signature:**

```php
public static function createStringBufferPool(int $poolSize, int $bufferCapacity): StringBufferPool
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `poolSize` | `int` | Yes | Maximum number of buffers to keep in the pool |
| `bufferCapacity` | `int` | Yes | Initial capacity for each buffer in bytes |

**Returns:** `StringBufferPool`


---

### createByteBufferPool()

Create a pre-configured byte buffer pool for batch processing.

**Returns:**

A pool configured for binary data handling with reasonable defaults.

**Signature:**

```php
public static function createByteBufferPool(int $poolSize, int $bufferCapacity): ByteBufferPool
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `poolSize` | `int` | Yes | Maximum number of buffers to keep in the pool |
| `bufferCapacity` | `int` | Yes | Initial capacity for each buffer in bytes |

**Returns:** `ByteBufferPool`


---

### estimatePoolSize()

Estimate optimal pool sizing based on file size and document type.

This function uses the file size and MIME type to estimate how many
buffers and what capacity they should have. The estimates are conservative
to avoid starving large document processing.

**Returns:**

A `PoolSizeHint` with recommended pool configuration

**Signature:**

```php
public static function estimatePoolSize(int $fileSize, string $mimeType): PoolSizeHint
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `fileSize` | `int` | Yes | Size of the file in bytes |
| `mimeType` | `string` | Yes | MIME type of the document (e.g., "application/pdf") |

**Returns:** `PoolSizeHint`


---

### xmlTagName()

Converts XML tag name bytes to a string, avoiding allocation when possible.

**Signature:**

```php
public static function xmlTagName(string $name): Str
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `name` | `string` | Yes | The name |

**Returns:** `Str`


---

### escapeHtmlEntities()

Escape `&`, `<`, and `>` in text destined for markdown/HTML output.

Underscores are intentionally **not** escaped. In extracted PDF text they are
literal content (e.g. identifiers like `CTC_ARP_01`), not markdown italic
delimiters.

Uses a single-pass scan: if no special characters are found, returns a
borrowed `Cow` with no allocation.

**Signature:**

```php
public static function escapeHtmlEntities(string $text): Str
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `text` | `string` | Yes | The text |

**Returns:** `Str`


---

### normalizeWhitespace()

Normalizes whitespace by collapsing multiple whitespace characters into single spaces.
Returns Cow::Borrowed if no normalization needed.

**Signature:**

```php
public static function normalizeWhitespace(string $s): Str
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `s` | `string` | Yes | The s |

**Returns:** `Str`


---

### detectColumns()

Detect column positions from word x-coordinates.

Groups words by approximate x-position (within `column_threshold` pixels)
and returns the median x-position for each detected column, sorted left to right.

**Signature:**

```php
public static function detectColumns(array<HocrWord> $words, int $columnThreshold): array<int>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `words` | `array<HocrWord>` | Yes | The words |
| `columnThreshold` | `int` | Yes | The column threshold |

**Returns:** `array<int>`


---

### detectRows()

Detect row positions from word y-coordinates.

Groups words by their vertical center position and returns the median
y-position for each detected row. The `row_threshold_ratio` is multiplied
by the median word height to determine the grouping threshold.

**Signature:**

```php
public static function detectRows(array<HocrWord> $words, float $rowThresholdRatio): array<int>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `words` | `array<HocrWord>` | Yes | The words |
| `rowThresholdRatio` | `float` | Yes | The row threshold ratio |

**Returns:** `array<int>`


---

### reconstructTable()

Reconstruct a table grid from words with bounding box positions.

Takes detected words and reconstructs a 2D table by:
1. Detecting column positions (grouping by x-coordinate within `column_threshold`)
2. Detecting row positions (grouping by y-center within `row_threshold_ratio` * median height)
3. Assigning words to cells based on closest row/column
4. Combining words within the same cell

Returns a `Vec<Vec<String>>` where each inner `Vec` is a row of cell texts.

**Signature:**

```php
public static function reconstructTable(array<HocrWord> $words, int $columnThreshold, float $rowThresholdRatio): array<array<string>>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `words` | `array<HocrWord>` | Yes | The words |
| `columnThreshold` | `int` | Yes | The column threshold |
| `rowThresholdRatio` | `float` | Yes | The row threshold ratio |

**Returns:** `array<array<string>>`


---

### tableToMarkdown()

Convert a table grid to markdown format.

The first row is treated as the header row, with a separator line added after it.
Pipe characters in cell content are escaped.

**Signature:**

```php
public static function tableToMarkdown(array<array<string>> $table): string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `table` | `array<array<string>>` | Yes | The table |

**Returns:** `string`


---

### openapiJson()

Generate OpenAPI JSON schema.

Returns the complete OpenAPI 3.1 specification as a JSON string.

**Signature:**

```php
public static function openapiJson(): string
```

**Returns:** `string`


---

### validatePageBoundaries()

Validates the consistency and correctness of page boundaries.

# Validation Rules

1. Boundaries must be sorted by byte_start (monotonically increasing)
2. Boundaries must not overlap (byte_end[i] <= byte_start[i+1])
3. Each boundary must have byte_start < byte_end

**Returns:**

Returns `Ok(())` if all boundaries are valid.
Returns `KreuzbergError::Validation` if any boundary is invalid.

**Signature:**

```php
public static function validatePageBoundaries(array<PageBoundary> $boundaries): void
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `boundaries` | `array<PageBoundary>` | Yes | Page boundary markers to validate |

**Returns:** `void`

**Errors:** Throws `Error`.


---

### calculatePageRange()

Calculate which pages a byte range spans.

**Returns:**

A tuple of (first_page, last_page) where page numbers are 1-indexed.
Returns (None, None) if boundaries are empty or chunk doesn't overlap any page.

**Errors:**

Returns `KreuzbergError::Validation` if boundaries are invalid.

**Signature:**

```php
public static function calculatePageRange(int $byteStart, int $byteEnd, array<PageBoundary> $boundaries): OptionUsizeOptionUsize
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `byteStart` | `int` | Yes | Starting byte offset of the chunk |
| `byteEnd` | `int` | Yes | Ending byte offset of the chunk |
| `boundaries` | `array<PageBoundary>` | Yes | Page boundary markers from the document |

**Returns:** `OptionUsizeOptionUsize`

**Errors:** Throws `Error`.


---

### classifyChunk()

Classify a single chunk based on its content and optional heading context.

Rules are evaluated in priority order. The first matching rule determines
the returned `ChunkType`. When no rule matches, `ChunkType::Unknown`
is returned.

  (only available when using `ChunkerType::Markdown`).

**Signature:**

```php
public static function classifyChunk(string $content, ?HeadingContext $headingContext = null): ChunkType
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `content` | `string` | Yes | The text content of the chunk (may be trimmed or raw). |
| `headingContext` | `?HeadingContext` | No | Optional heading hierarchy this chunk falls under |

**Returns:** `ChunkType`


---

### chunkText()

Split text into chunks with optional page boundary tracking.

This is the primary API function for chunking text. It supports both plain text
and Markdown with configurable chunk size, overlap, and page boundary mapping.

**Returns:**

A ChunkingResult containing all chunks and their metadata.

**Signature:**

```php
public static function chunkText(string $text, ChunkingConfig $config, ?array<PageBoundary> $pageBoundaries = null): ChunkingResult
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `text` | `string` | Yes | The text to split into chunks |
| `config` | `ChunkingConfig` | Yes | Chunking configuration (max size, overlap, type) |
| `pageBoundaries` | `?array<PageBoundary>` | No | Optional page boundary markers for mapping chunks to pages |

**Returns:** `ChunkingResult`

**Errors:** Throws `Error`.


---

### chunkTextWithHeadingSource()

Chunk text with an optional separate markdown source for heading context resolution.

When `heading_source` is provided, it is used instead of `text` for building the
heading map. This is needed when `text` is plain text (no markdown headings) but
the original document had headings that were stripped during rendering.

**Signature:**

```php
public static function chunkTextWithHeadingSource(string $text, ChunkingConfig $config, ?array<PageBoundary> $pageBoundaries = null, ?string $headingSource = null): ChunkingResult
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `text` | `string` | Yes | The text |
| `config` | `ChunkingConfig` | Yes | The configuration options |
| `pageBoundaries` | `?array<PageBoundary>` | No | The page boundaries |
| `headingSource` | `?string` | No | The heading source |

**Returns:** `ChunkingResult`

**Errors:** Throws `Error`.


---

### chunkTextWithType()

Chunk text with explicit type specification.

This is a convenience function that constructs a ChunkingConfig from individual
parameters and calls `chunk_text`.

**Returns:**

A ChunkingResult containing all chunks and their metadata.

**Signature:**

```php
public static function chunkTextWithType(string $text, int $maxCharacters, int $overlap, bool $trim, ChunkerType $chunkerType): ChunkingResult
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `text` | `string` | Yes | The text to split into chunks |
| `maxCharacters` | `int` | Yes | Maximum characters per chunk |
| `overlap` | `int` | Yes | Character overlap between consecutive chunks |
| `trim` | `bool` | Yes | Whether to trim whitespace from boundaries |
| `chunkerType` | `ChunkerType` | Yes | Type of chunker to use (Text or Markdown) |

**Returns:** `ChunkingResult`

**Errors:** Throws `Error`.


---

### chunkTextsBatch()

Batch process multiple texts with the same configuration.

This convenience function applies the same chunking configuration to multiple
texts in sequence.

**Returns:**

A vector of ChunkingResult objects, one per input text.

**Errors:**

Returns an error if chunking any individual text fails.

**Signature:**

```php
public static function chunkTextsBatch(array<string> $texts, ChunkingConfig $config): array<ChunkingResult>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `texts` | `array<string>` | Yes | Slice of text strings to chunk |
| `config` | `ChunkingConfig` | Yes | Chunking configuration to apply to all texts |

**Returns:** `array<ChunkingResult>`

**Errors:** Throws `Error`.


---

### precomputeUtf8Boundaries()

Pre-computes valid UTF-8 character boundaries for a text string.

This function performs a single O(n) pass through the text to identify all valid
UTF-8 character boundaries, storing them in a BitVec for O(1) lookups.

**Returns:**

A BitVec where each bit represents whether a byte offset is a valid UTF-8 character boundary.
The BitVec has length `text.len() + 1` (includes the end position).

**Signature:**

```php
public static function precomputeUtf8Boundaries(string $text): BitVec
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `text` | `string` | Yes | The text to analyze |

**Returns:** `BitVec`


---

### validateUtf8Boundaries()

Validates that byte offsets in page boundaries fall on valid UTF-8 character boundaries.

This function ensures that all page boundary positions are at valid UTF-8 character
boundaries within the text. This is CRITICAL to prevent text corruption when boundaries
are created from language bindings or external sources, particularly with multibyte
UTF-8 characters (emoji, CJK characters, combining marks, etc.).

**Performance Strategy**: Uses adaptive validation to optimize for different boundary counts:
- **Small sets (≤10 boundaries)**: O(k) approach using Rust's native `is_char_boundary()` for each position
- **Large sets (>10 boundaries)**: O(n) precomputation with O(1) lookups via BitVec

For typical PDF documents with 1-10 page boundaries, the fast path provides 30-50% faster
validation than always precomputing. For documents with 100+ boundaries, batch precomputation
is 2-4% faster overall due to amortized costs. This gives ~2-4% improvement across all scenarios.

**Returns:**

Returns `Ok(())` if all boundaries are at valid UTF-8 character boundaries.
Returns `KreuzbergError::Validation` if any boundary is at an invalid position.

# UTF-8 Boundary Safety

Rust strings use UTF-8 encoding where characters can be 1-4 bytes. For example:
- ASCII letters: 1 byte each
- Emoji (🌍): 4 bytes but 1 character
- CJK characters (中): 3 bytes but 1 character

This function checks that all byte_start and byte_end values are at character boundaries
using an adaptive strategy: direct calls for small boundary sets, or precomputed BitVec
for large sets.

**Signature:**

```php
public static function validateUtf8Boundaries(string $text, array<PageBoundary> $boundaries): void
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `text` | `string` | Yes | The text being chunked |
| `boundaries` | `array<PageBoundary>` | Yes | Page boundary markers to validate |

**Returns:** `void`

**Errors:** Throws `Error`.


---

### registerChunkingProcessor()

Register the chunking processor with the global registry.

This function should be called once at application startup to register
the chunking post-processor.

**Note:** This is called automatically on first use.
Explicit calling is optional.

**Signature:**

```php
public static function registerChunkingProcessor(): void
```

**Returns:** `void`

**Errors:** Throws `Error`.


---

### createClient()

Create a liter-llm `DefaultClient` from kreuzberg's `LlmConfig`.

The `model` field from the config is passed as a model hint so that
liter-llm can resolve the correct provider automatically.

When `api_key` is `null`, liter-llm falls back to the provider's standard
environment variable (e.g., `OPENAI_API_KEY`).

**Signature:**

```php
public static function createClient(LlmConfig $config): DefaultClient
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `config` | `LlmConfig` | Yes | The configuration options |

**Returns:** `DefaultClient`

**Errors:** Throws `Error`.


---

### renderTemplate()

Render a Jinja2 template with the given context variables.

**Signature:**

```php
public static function renderTemplate(string $template, Value $context): string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `template` | `string` | Yes | The template |
| `context` | `Value` | Yes | The value |

**Returns:** `string`

**Errors:** Throws `Error`.


---

### extractStructured()

Extract structured data from document content using an LLM with JSON schema.

Sends the document content to the configured LLM with a JSON schema constraint,
returning structured data that conforms to the schema.

**Returns:**

A `serde_json::Value` conforming to the provided JSON schema.

**Errors:**

Returns an error if:
- The LLM client cannot be created (invalid provider/credentials).
- The LLM request fails (network, rate-limit, etc.).
- The LLM response cannot be parsed as valid JSON.

**Signature:**

```php
public static function extractStructured(string $content, StructuredExtractionConfig $config): LlmUsage
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `content` | `string` | Yes | The extracted document text to send to the LLM. |
| `config` | `StructuredExtractionConfig` | Yes | Structured extraction configuration including schema and LLM settings. |

**Returns:** `LlmUsage`

**Errors:** Throws `Error`.


---

### vlmOcr()

Perform OCR on an image using a vision language model.

Sends the image to a VLM (e.g., GPT-4o, Claude) which extracts text.
The language hint is included in the prompt when the document language
is not English.

  (e.g., `"eng"`, `"de"`, `"fra"`)
* `config` - LLM provider/model configuration

**Returns:**

Extracted text from the image, or an error if the VLM call fails.

**Errors:**

- `KreuzbergError::Ocr` if the VLM returns no content or the API call fails
- `KreuzbergError::MissingDependency` if the liter-llm client cannot be created

**Signature:**

```php
public static function vlmOcr(string $imageBytes, string $imageMimeType, string $language, LlmConfig $config): LlmUsage
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `imageBytes` | `string` | Yes | Raw image data (JPEG, PNG, WebP, etc.) |
| `imageMimeType` | `string` | Yes | MIME type of the image (e.g., `"image/png"`) |
| `language` | `string` | Yes | ISO 639 language code or Tesseract language name |
| `config` | `LlmConfig` | Yes | LLM provider/model configuration |

**Returns:** `LlmUsage`

**Errors:** Throws `Error`.


---

### normalize()

L2-normalize a vector.

**Signature:**

```php
public static function normalize(array<float> $v): array<float>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `v` | `array<float>` | Yes | The v |

**Returns:** `array<float>`


---

### getPreset()

Get a preset by name.

**Signature:**

```php
public static function getPreset(string $name): ?EmbeddingPreset
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `name` | `string` | Yes | The name |

**Returns:** `?EmbeddingPreset`


---

### listPresets()

List all available preset names.

**Signature:**

```php
public static function listPresets(): array<string>
```

**Returns:** `array<string>`


---

### warmModel()

Eagerly download and cache an embedding model without returning the handle.

This triggers the same download and initialization as `get_or_init_engine`
but discards the result, making it suitable for cache-warming scenarios
where the caller doesn't need to use the model immediately.

**Note**: This function downloads AND initializes the ONNX model, which
requires ONNX Runtime and uses significant memory. For download-only
scenarios (e.g., init containers), use `download_model` instead.

**Signature:**

```php
public static function warmModel(EmbeddingModelType $modelType, ?string $cacheDir = null): void
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `modelType` | `EmbeddingModelType` | Yes | The embedding model type |
| `cacheDir` | `?string` | No | The cache dir |

**Returns:** `void`

**Errors:** Throws `Error`.


---

### downloadModel()

Download an embedding model's files without initializing ONNX Runtime.

Downloads the model files (ONNX model, tokenizer, config) from HuggingFace
to the cache directory. Subsequent calls to `warm_model` or
`get_or_init_engine` will find the files cached and skip the download step.

This is ideal for init containers or CI environments where you want to
pre-populate the cache without loading models into memory.

**Signature:**

```php
public static function downloadModel(EmbeddingModelType $modelType, ?string $cacheDir = null): void
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `modelType` | `EmbeddingModelType` | Yes | The embedding model type |
| `cacheDir` | `?string` | No | The cache dir |

**Returns:** `void`

**Errors:** Throws `Error`.


---

### generateEmbeddingsForChunks()

Generate embeddings for text chunks using the specified configuration.

This function modifies chunks in-place, populating their `embedding` field
with generated embedding vectors. It uses batch processing for efficiency.

**Returns:**

Returns `Ok(())` if embeddings were generated successfully, or an error if
model initialization or embedding generation fails.

**Signature:**

```php
public static function generateEmbeddingsForChunks(array<Chunk> $chunks, EmbeddingConfig $config): void
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `chunks` | `array<Chunk>` | Yes | Mutable reference to vector of chunks to generate embeddings for |
| `config` | `EmbeddingConfig` | Yes | Embedding configuration specifying model and parameters |

**Returns:** `void`

**Errors:** Throws `Error`.


---

### calculateSmartDpi()

Calculate smart DPI based on page dimensions, memory constraints, and target DPI

**Signature:**

```php
public static function calculateSmartDpi(float $pageWidth, float $pageHeight, int $targetDpi, int $maxDimension, float $maxMemoryMb): int
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pageWidth` | `float` | Yes | The page width |
| `pageHeight` | `float` | Yes | The page height |
| `targetDpi` | `int` | Yes | The target dpi |
| `maxDimension` | `int` | Yes | The max dimension |
| `maxMemoryMb` | `float` | Yes | The max memory mb |

**Returns:** `int`


---

### calculateOptimalDpi()

Calculate optimal DPI with min/max constraints

**Signature:**

```php
public static function calculateOptimalDpi(float $pageWidth, float $pageHeight, int $targetDpi, int $maxDimension, int $minDpi, int $maxDpi): int
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pageWidth` | `float` | Yes | The page width |
| `pageHeight` | `float` | Yes | The page height |
| `targetDpi` | `int` | Yes | The target dpi |
| `maxDimension` | `int` | Yes | The max dimension |
| `minDpi` | `int` | Yes | The min dpi |
| `maxDpi` | `int` | Yes | The max dpi |

**Returns:** `int`


---

### normalizeImageDpi()

Normalize image DPI based on extraction configuration

**Returns:**
* `NormalizeResult` containing processed image data and metadata

**Signature:**

```php
public static function normalizeImageDpi(string $rgbData, int $width, int $height, ExtractionConfig $config, ?float $currentDpi = null): NormalizeResult
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `rgbData` | `string` | Yes | RGB image data as a flat `Vec<u8>` (height * width * 3 bytes, row-major) |
| `width` | `int` | Yes | Image width in pixels |
| `height` | `int` | Yes | Image height in pixels |
| `config` | `ExtractionConfig` | Yes | Extraction configuration containing DPI settings |
| `currentDpi` | `?float` | No | Optional current DPI of the image (defaults to 72 if None) |

**Returns:** `NormalizeResult`

**Errors:** Throws `Error`.


---

### resizeImage()

Resize an image using fast_image_resize with appropriate algorithm based on scale factor

**Signature:**

```php
public static function resizeImage(DynamicImage $image, int $newWidth, int $newHeight, float $scaleFactor): DynamicImage
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `image` | `DynamicImage` | Yes | The dynamic image |
| `newWidth` | `int` | Yes | The new width |
| `newHeight` | `int` | Yes | The new height |
| `scaleFactor` | `float` | Yes | The scale factor |

**Returns:** `DynamicImage`

**Errors:** Throws `Error`.


---

### detectLanguages()

Detect languages in text using whatlang.

Returns a list of detected language codes (ISO 639-3 format).
Returns `null` if no languages could be detected with sufficient confidence.

**Signature:**

```php
public static function detectLanguages(string $text, LanguageDetectionConfig $config): ?array<string>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `text` | `string` | Yes | The text to analyze for language detection |
| `config` | `LanguageDetectionConfig` | Yes | Optional configuration for language detection |

**Returns:** `?array<string>`

**Errors:** Throws `Error`.


---

### registerLanguageDetectionProcessor()

Register the language detection processor with the global registry.

This function should be called once at application startup to register
the language detection post-processor.

**Note:** This is called automatically on first use.
Explicit calling is optional.

**Signature:**

```php
public static function registerLanguageDetectionProcessor(): void
```

**Returns:** `void`

**Errors:** Throws `Error`.


---

### getStopwords()

Get stopwords for a language with normalization.

This function provides a user-friendly interface to the stopwords registry with:
- **Case-insensitive lookup**: "EN", "en", "En" all work
- **Locale normalization**: "en-US", "en_GB", "es-ES" extract to "en", "es"
- **Consistent behavior**: Returns `null` for unsupported languages

# Language Code Format

Accepts multiple formats:
- ISO 639-1 two-letter codes: `"en"`, `"es"`, `"de"`, etc.
- Uppercase variants: `"EN"`, `"ES"`, `"DE"`
- Locale codes with hyphen: `"en-US"`, `"es-ES"`, `"pt-BR"`
- Locale codes with underscore: `"en_US"`, `"es_ES"`, `"pt_BR"`

All formats are normalized to lowercase two-letter ISO 639-1 codes.

**Returns:**

- `Some(&HashSet<String>)` if the language is supported (64 languages available)
- `null` if the language is not supported

# Performance

This function performs two operations:
1. String normalization (lowercase + truncate) - O(1) for typical language codes
2. HashMap lookup in STOPWORDS - O(1) average case

Total overhead is negligible (~10-50ns on modern CPUs).

**Signature:**

```php
public static function getStopwords(string $lang): ?AHashSet
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `lang` | `string` | Yes | The lang |

**Returns:** `?AHashSet`


---

### getStopwordsWithFallback()

Get stopwords for a language with fallback support.

This function attempts to retrieve stopwords for the primary language,
and if not available, falls back to a secondary language. This is useful
for handling scenarios where:
- A detected language isn't supported
- You want to use English as a fallback for unknown languages
- You need graceful degradation for multilingual content

Both language codes support the same normalization as `get_stopwords()`:
- Case-insensitive lookup (EN, en, En all work)
- Locale codes normalized (en-US, en_GB extract to "en")

**Returns:**

- `Some(&HashSet<String>)` if either language is supported
- `null` if neither language is supported

# Common Patterns


# Performance

This function performs at most two HashMap lookups:
1. Try primary language (O(1) average case)
2. If None, try fallback language (O(1) average case)

Total overhead is negligible (~10-100ns on modern CPUs).

**Signature:**

```php
public static function getStopwordsWithFallback(string $language, string $fallback): ?AHashSet
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `language` | `string` | Yes | Primary language code to try first |
| `fallback` | `string` | Yes | Fallback language code to use if primary not available |

**Returns:** `?AHashSet`


---

### extractKeywords()

Extract keywords from text using the specified algorithm.

This is the unified entry point for keyword extraction. The algorithm
used is determined by `config.algorithm`.

**Returns:**

A vector of keywords sorted by relevance (highest score first).

**Errors:**

Returns an error if:
- The specified algorithm feature is not enabled
- Keyword extraction fails

**Signature:**

```php
public static function extractKeywords(string $text, KeywordConfig $config): array<Keyword>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `text` | `string` | Yes | The text to extract keywords from |
| `config` | `KeywordConfig` | Yes | Keyword extraction configuration |

**Returns:** `array<Keyword>`

**Errors:** Throws `Error`.


---

### registerKeywordProcessor()

Register the keyword extraction processor with the global registry.

This function should be called once at application startup to register
the keyword extraction post-processor.

**Note:** This is called automatically on first use.
Explicit calling is optional.

**Signature:**

```php
public static function registerKeywordProcessor(): void
```

**Returns:** `void`

**Errors:** Throws `Error`.


---

### textBlockToElement()

Convert a PaddleOCR TextBlock to a unified OcrElement.

Preserves all spatial information including:
- 4-point quadrilateral bounding box
- Detection and recognition confidence scores
- Rotation angle and confidence

**Returns:**

A fully populated `OcrElement` with all available metadata.

**Errors:**

Returns an error if:
- `box_points` has fewer than 4 points (malformed detection)
- `angle_index` is outside the valid range (0-3)

Returns `Ok(None)` if the detection is filtered out due to low `box_score`.

**Signature:**

```php
public static function textBlockToElement(TextBlock $block, int $pageNumber): ?OcrElement
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `block` | `TextBlock` | Yes | PaddleOCR TextBlock containing OCR results |
| `pageNumber` | `int` | Yes | 1-indexed page number |

**Returns:** `?OcrElement`

**Errors:** Throws `Error`.


---

### tsvRowToElement()

Convert a Tesseract TSV row to a unified OcrElement.

Preserves:
- Axis-aligned bounding box
- Recognition confidence (Tesseract doesn't have separate detection confidence)
- Hierarchical level information

**Returns:**

An `OcrElement` with rectangle geometry and Tesseract metadata.

**Signature:**

```php
public static function tsvRowToElement(TsvRow $row): OcrElement
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `row` | `TsvRow` | Yes | Parsed TSV row from Tesseract output |

**Returns:** `OcrElement`


---

### iteratorWordToElement()

Convert a Tesseract iterator WordData to a unified OcrElement with rich metadata.

Unlike `tsv_row_to_element` which only has text, bbox, and confidence,
this populates font attributes (bold, italic, monospace, pointsize) and
block/paragraph context from the Tesseract layout analysis.

**Returns:**

An `OcrElement` at `Word` level with all available font and layout metadata.

**Signature:**

```php
public static function iteratorWordToElement(WordData $word, ?TessPolyBlockType $blockType = null, ?ParaInfo $paraInfo = null, int $pageNumber): OcrElement
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `word` | `WordData` | Yes | WordData from the Tesseract result iterator |
| `blockType` | `?TessPolyBlockType` | No | Optional block type from Tesseract layout analysis |
| `paraInfo` | `?ParaInfo` | No | Optional paragraph metadata (justification, list item flag) |
| `pageNumber` | `int` | Yes | 1-indexed page number |

**Returns:** `OcrElement`


---

### elementToHocrWord()

Convert an OcrElement to an HocrWord for table reconstruction.

This enables reuse of the existing table detection algorithms from
html-to-markdown-rs with PaddleOCR results.

**Returns:**

An `HocrWord` suitable for table reconstruction algorithms.

**Signature:**

```php
public static function elementToHocrWord(OcrElement $element): HocrWord
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `element` | `OcrElement` | Yes | Unified OCR element with geometry and text |

**Returns:** `HocrWord`


---

### elementsToHocrWords()

Convert a vector of OcrElements to HocrWords for batch table processing.

Filters to word-level elements only, as table reconstruction
works best with word-level granularity.

**Returns:**

A vector of HocrWords filtered by confidence and element level.

**Signature:**

```php
public static function elementsToHocrWords(array<OcrElement> $elements, float $minConfidence): array<HocrWord>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `elements` | `array<OcrElement>` | Yes | Slice of OCR elements to convert |
| `minConfidence` | `float` | Yes | Minimum recognition confidence threshold (0.0-1.0) |

**Returns:** `array<HocrWord>`


---

### parseHocrToInternalDocument()

Parse hOCR HTML into an `InternalDocument` with full spatial and confidence metadata.

This is the primary entry point. It replaces the older `convert_hocr_to_markdown` path
by producing structured `InternalElement`s directly, preserving OCR geometry and
confidence that the markdown conversion discards.

# Output mapping

| hOCR element   | kreuzberg element                             |
|---------------|-----------------------------------------------|
| `ocr_page`    | `PageBreak` between consecutive pages         |
| `ocr_par`     | `OcrText { level: Block }` with union bbox    |
| `ocr_line`    | newline separator within a paragraph          |
| `ocrx_word`   | word text, bbox, `x_wconf` → `OcrConfidence` |

Page numbers come from the `ppageno` title property (converted to 1-indexed).

**Signature:**

```php
public static function parseHocrToInternalDocument(string $hocrHtml): InternalDocument
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `hocrHtml` | `string` | Yes | The hocr html |

**Returns:** `InternalDocument`


---

### assembleOcrMarkdown()

Assemble structured markdown from OCR elements using layout detection results.

Both inputs must be in the same pixel coordinate space (from the same
rendered page image). Returns plain text join when `detection` is `null`.

`recognized_tables` provides pre-computed markdown for Table regions
(from TATR or other table structure recognizer). When empty, Table
regions fall back to heuristic grid reconstruction from OCR elements.

**Signature:**

```php
public static function assembleOcrMarkdown(array<OcrElement> $elements, ?DetectionResult $detection = null, int $imgWidth, int $imgHeight, array<RecognizedTable> $recognizedTables): string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `elements` | `array<OcrElement>` | Yes | The elements |
| `detection` | `?DetectionResult` | No | The detection result |
| `imgWidth` | `int` | Yes | The img width |
| `imgHeight` | `int` | Yes | The img height |
| `recognizedTables` | `array<RecognizedTable>` | Yes | The recognized tables |

**Returns:** `string`


---

### recognizePageTables()

Run TATR table recognition for all Table regions in a page.

For each Table detection, crops the page image, runs TATR inference,
matches OCR elements to cells, and produces markdown tables.

**Signature:**

```php
public static function recognizePageTables(RgbImage $pageImage, DetectionResult $detection, array<OcrElement> $elements, TatrModel $tatrModel): array<RecognizedTable>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pageImage` | `RgbImage` | Yes | The rgb image |
| `detection` | `DetectionResult` | Yes | The detection result |
| `elements` | `array<OcrElement>` | Yes | The elements |
| `tatrModel` | `TatrModel` | Yes | The tatr model |

**Returns:** `array<RecognizedTable>`


---

### extractWordsFromTsv()

Extract words from Tesseract TSV output and convert to HocrWord format.

This parses Tesseract's TSV format (level, page_num, block_num, ...) and
converts it to the HocrWord format used for table reconstruction.

**Signature:**

```php
public static function extractWordsFromTsv(string $tsvData, float $minConfidence): array<HocrWord>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `tsvData` | `string` | Yes | The tsv data |
| `minConfidence` | `float` | Yes | The min confidence |

**Returns:** `array<HocrWord>`

**Errors:** Throws `OcrError`.


---

### computeHash()

Compute a blake3 hash string from input data.

Returns a 32-character hex string (128 bits of blake3 output).

**Signature:**

```php
public static function computeHash(string $data): string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `data` | `string` | Yes | The data |

**Returns:** `string`


---

### validateLanguageCode()

**Signature:**

```php
public static function validateLanguageCode(string $langCode): void
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `langCode` | `string` | Yes | The lang code |

**Returns:** `void`

**Errors:** Throws `OcrError`.


---

### validateTesseractVersion()

**Signature:**

```php
public static function validateTesseractVersion(int $version): void
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `version` | `int` | Yes | The version |

**Returns:** `void`

**Errors:** Throws `OcrError`.


---

### ensureOrtAvailable()

Ensure ONNX Runtime is discoverable. Safe to call multiple times (no-op after first).

When the `ort-bundled` feature is enabled the ORT binaries are embedded via the
official Microsoft release and no system library search is needed.

**Signature:**

```php
public static function ensureOrtAvailable(): void
```

**Returns:** `void`


---

### isLanguageSupported()

Check if a language code is supported by PaddleOCR.

**Signature:**

```php
public static function isLanguageSupported(string $lang): bool
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `lang` | `string` | Yes | The lang |

**Returns:** `bool`


---

### languageToScriptFamily()

Map a PaddleOCR language code to its script family.

Script families group languages that share a single recognition model.
For example, French, German, and Spanish all use the `latin` rec model.
Chinese simplified, traditional, and Japanese share the `chinese` rec model.

# Script Families (11, all PP-OCRv5)

| Family | Languages |
|---|---|
| `english` | English |
| `chinese` | Chinese (simplified+traditional), Japanese |
| `latin` | French, German, Spanish, Italian, 40+ more |
| `korean` | Korean |
| `eslav` | Russian, Ukrainian, Belarusian |
| `thai` | Thai |
| `greek` | Greek |
| `arabic` | Arabic, Persian, Urdu |
| `devanagari` | Hindi, Marathi, Sanskrit, Nepali |
| `tamil` | Tamil |
| `telugu` | Telugu |

**Signature:**

```php
public static function languageToScriptFamily(string $paddleLang): string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `paddleLang` | `string` | Yes | The paddle lang |

**Returns:** `string`


---

### mapLanguageCode()

Map Kreuzberg language codes to PaddleOCR language codes.

**Signature:**

```php
public static function mapLanguageCode(string $kreuzbergCode): ?string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `kreuzbergCode` | `string` | Yes | The kreuzberg code |

**Returns:** `?string`


---

### resolveCacheDir()

Resolve the cache directory for the auto-rotate model.

**Signature:**

```php
public static function resolveCacheDir(): string
```

**Returns:** `string`


---

### detectAndRotate()

Detect orientation and return a corrected image if rotation is needed.

Returns `Ok(Some(rotated_bytes))` if rotation was applied,
`Ok(None)` if no rotation needed (0° or low confidence).

**Signature:**

```php
public static function detectAndRotate(DocOrientationDetector $detector, string $imageBytes): ?string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `detector` | `DocOrientationDetector` | Yes | The doc orientation detector |
| `imageBytes` | `string` | Yes | The image bytes |

**Returns:** `?string`

**Errors:** Throws `Error`.


---

### buildCellGrid()

Build a 2D cell grid from TATR detections.

The grid is `[num_rows][num_cols]` where each cell is the intersection
of a row bounding box and a column bounding box.

Processing steps:
1. Widen all rows to span the full table width (min x1 to max x2 across rows)
2. Apply NMS using IoB: sort by confidence descending, remove detections
   whose IoB with any higher-confidence detection exceeds `NMS_IOB_THRESHOLD`
3. For each (row, column) pair, compute the intersection rectangle

If `table_bbox` is provided, it is used to clip the row widening bounds.

**Signature:**

```php
public static function buildCellGrid(TatrResult $result, ?F324 $tableBbox = null): array<array<CellBBox>>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `result` | `TatrResult` | Yes | The tatr result |
| `tableBbox` | `?F324` | No | The [f32;4] |

**Returns:** `array<array<CellBBox>>`


---

### applyHeuristics()

Apply Docling-style postprocessing heuristics to raw detections.

This implements the key heuristics from `docling/utils/layout_postprocessor.py`:
1. Per-class confidence thresholds
2. Full-page picture removal (>90% page area)
3. Overlap resolution (IoU > 0.8 or containment > 0.8)
4. Cross-type overlap handling (KVR vs Table)

**Signature:**

```php
public static function applyHeuristics(array<LayoutDetection> $detections, float $pageWidth, float $pageHeight): void
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `detections` | `array<LayoutDetection>` | Yes | The detections |
| `pageWidth` | `float` | Yes | The page width |
| `pageHeight` | `float` | Yes | The page height |

**Returns:** `void`


---

### greedyNms()

Standard greedy Non-Maximum Suppression.

Sorts detections by confidence (descending), then iteratively removes
detections that have IoU > `iou_threshold` with any higher-confidence detection.

This is required for YOLO models. RT-DETR is NMS-free.

**Signature:**

```php
public static function greedyNms(array<LayoutDetection> $detections, float $iouThreshold): void
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `detections` | `array<LayoutDetection>` | Yes | The detections |
| `iouThreshold` | `float` | Yes | The iou threshold |

**Returns:** `void`


---

### preprocessImagenet()

Preprocess an image for models using ImageNet normalization (e.g., RT-DETR).

Pipeline: resize to target_size x target_size (bilinear) -> rescale /255 -> ImageNet normalize -> NCHW f32.

Uses a single vectorized pass over contiguous pixel data for maximum throughput.

**Signature:**

```php
public static function preprocessImagenet(RgbImage $img, int $targetSize): Array4
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `img` | `RgbImage` | Yes | The rgb image |
| `targetSize` | `int` | Yes | The target size |

**Returns:** `Array4`


---

### preprocessImagenetLetterbox()

Preprocess with aspect-preserving letterbox and ImageNet normalization.

Pipeline: letterbox-resize to target_size × target_size (Lanczos3, aspect-preserving)
          → rescale /255 → ImageNet normalize → NCHW f32.

Unlike `preprocess_imagenet` which squashes the image to a square (distorting
aspect ratio), this preserves the original proportions and pads with the ImageNet
mean color. This produces more accurate detection coordinates because the model
sees undistorted geometry.

Returns `(tensor, scale, pad_x, pad_y)`:
- `scale`: resize factor applied (for mapping detections back)
- `pad_x`, `pad_y`: top-left offset of the resized image within the padded square

**Signature:**

```php
public static function preprocessImagenetLetterbox(RgbImage $img, int $targetSize): Array4F32F32U32U32
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `img` | `RgbImage` | Yes | The rgb image |
| `targetSize` | `int` | Yes | The target size |

**Returns:** `Array4F32F32U32U32`


---

### preprocessRescale()

Preprocess with rescale only (no ImageNet normalization).

Pipeline: resize to target_size x target_size -> rescale /255 -> NCHW f32.

**Signature:**

```php
public static function preprocessRescale(RgbImage $img, int $targetSize): Array4
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `img` | `RgbImage` | Yes | The rgb image |
| `targetSize` | `int` | Yes | The target size |

**Returns:** `Array4`


---

### preprocessLetterbox()

Letterbox preprocessing for YOLOX-style models.

Resizes the image to fit within (target_width x target_height) while maintaining
aspect ratio, padding the remaining area with value 114.0 (raw pixel value).
No normalization — values are 0-255 as YOLOX expects.

Returns the NCHW tensor and the scale ratio (for rescaling detections back).

**Signature:**

```php
public static function preprocessLetterbox(RgbImage $img, int $targetWidth, int $targetHeight): Array4F32F32
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `img` | `RgbImage` | Yes | The rgb image |
| `targetWidth` | `int` | Yes | The target width |
| `targetHeight` | `int` | Yes | The target height |

**Returns:** `Array4F32F32`


---

### buildSession()

Build an optimized ORT session from an ONNX model file.

`thread_budget` controls the number of intra-op threads for this session.
Pass the result of `crate::core::config::concurrency::resolve_thread_budget`
to respect the user's `ConcurrencyConfig`.

When `accel` is `null` or `Auto`, uses platform defaults:
- macOS: CoreML (Neural Engine / GPU)
- Linux: CUDA (GPU)
- Others: CPU only

ORT silently falls back to CPU if the requested EP is unavailable.

**Signature:**

```php
public static function buildSession(string $path, ?AccelerationConfig $accel = null, int $threadBudget): Session
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `path` | `string` | Yes | Path to the file |
| `accel` | `?AccelerationConfig` | No | The acceleration config |
| `threadBudget` | `int` | Yes | The thread budget |

**Returns:** `Session`

**Errors:** Throws `LayoutError`.


---

### configFromExtraction()

Convert a `LayoutDetectionConfig` into a `LayoutEngineConfig`.

**Signature:**

```php
public static function configFromExtraction(LayoutDetectionConfig $layoutConfig): LayoutEngineConfig
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `layoutConfig` | `LayoutDetectionConfig` | Yes | The layout detection config |

**Returns:** `LayoutEngineConfig`


---

### createEngine()

Create a `LayoutEngine` from a `LayoutDetectionConfig`.

Ensures ORT is available, then creates the engine with model download.

**Signature:**

```php
public static function createEngine(LayoutDetectionConfig $layoutConfig): LayoutEngine
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `layoutConfig` | `LayoutDetectionConfig` | Yes | The layout detection config |

**Returns:** `LayoutEngine`

**Errors:** Throws `LayoutError`.


---

### takeOrCreateEngine()

Take the cached layout engine, or create a new one if the cache is empty.

The caller owns the engine for the duration of its work and should
return it via `return_engine` when done. This avoids holding the
global mutex during inference.

**Signature:**

```php
public static function takeOrCreateEngine(LayoutDetectionConfig $layoutConfig): LayoutEngine
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `layoutConfig` | `LayoutDetectionConfig` | Yes | The layout detection config |

**Returns:** `LayoutEngine`

**Errors:** Throws `LayoutError`.


---

### returnEngine()

Return a layout engine to the global cache for reuse by future extractions.

**Signature:**

```php
public static function returnEngine(LayoutEngine $engine): void
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `engine` | `LayoutEngine` | Yes | The layout engine |

**Returns:** `void`


---

### takeOrCreateTatr()

Take the cached TATR model, or create a new one if the cache is empty.

Returns `null` if the model cannot be loaded. Once a load attempt fails,
subsequent calls return `null` immediately without retrying, avoiding
repeated download attempts and redundant warning logs.

**Signature:**

```php
public static function takeOrCreateTatr(): ?TatrModel
```

**Returns:** `?TatrModel`


---

### returnTatr()

Return a TATR model to the global cache for reuse.

**Signature:**

```php
public static function returnTatr(TatrModel $model): void
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `model` | `TatrModel` | Yes | The tatr model |

**Returns:** `void`


---

### takeOrCreateSlanet()

Take a cached SLANeXT model for the given variant, or create a new one.

**Signature:**

```php
public static function takeOrCreateSlanet(string $variant): ?SlanetModel
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `variant` | `string` | Yes | The variant |

**Returns:** `?SlanetModel`


---

### returnSlanet()

Return a SLANeXT model to the global cache for reuse.

**Signature:**

```php
public static function returnSlanet(string $variant, SlanetModel $model): void
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `variant` | `string` | Yes | The variant |
| `model` | `SlanetModel` | Yes | The slanet model |

**Returns:** `void`


---

### takeOrCreateTableClassifier()

Take a cached table classifier, or create a new one.

**Signature:**

```php
public static function takeOrCreateTableClassifier(): ?TableClassifier
```

**Returns:** `?TableClassifier`


---

### returnTableClassifier()

Return a table classifier to the global cache for reuse.

**Signature:**

```php
public static function returnTableClassifier(TableClassifier $model): void
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `model` | `TableClassifier` | Yes | The table classifier |

**Returns:** `void`


---

### extractAnnotationsFromDocument()

Extract annotations from all pages of a PDF document.

Iterates over every page and every annotation on each page, mapping
pdfium annotation subtypes to `PdfAnnotationType` and collecting
content text and bounding boxes where available.

Annotations that cannot be read are silently skipped.

**Returns:**

A `Vec<PdfAnnotation>` containing all successfully extracted annotations.

**Signature:**

```php
public static function extractAnnotationsFromDocument(PdfDocument $document): array<PdfAnnotation>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `document` | `PdfDocument` | Yes | A reference to the loaded pdfium `PdfDocument`. |

**Returns:** `array<PdfAnnotation>`


---

### extractBookmarks()

Extract bookmarks (outlines) from a PDF document loaded via lopdf.

Walks the `/Outlines` tree in the document catalog, collecting each bookmark's
title and destination. Returns an empty `Vec` if the document has no outlines.

**Signature:**

```php
public static function extractBookmarks(Document $document): array<Uri>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `document` | `Document` | Yes | The document |

**Returns:** `array<Uri>`


---

### extractBundledPdfium()

Extract bundled PDFium library to temporary directory.

# Behavior

- Embeds PDFium library using `include_bytes!`
- Extracts to `$TMPDIR/kreuzberg-pdfium/` (non-WASM only)
- Reuses extracted library if size matches
- Sets permissions to 0755 on Unix
- Returns path to extracted library
- **Thread-safe**: Synchronized with a global `Mutex` to prevent concurrent writes

# Concurrency

This function is fully thread-safe. When multiple threads call it simultaneously,
only the first thread performs the actual extraction while others wait. This prevents
the "file too short" error that occurs when one thread reads a partially-written file.

# WASM Handling

On WASM targets (wasm32-*), this function returns an error with a helpful
message directing users to use WASM-specific initialization. WASM PDFium
is initialized through the runtime, not via file extraction.

**Errors:**

Returns `std::io::Error` if:
- Cannot create extraction directory
- Cannot write library file
- Cannot set file permissions (Unix only)
- Target is WASM (filesystem access not available)

# Platform-Specific Library Names

- Linux: `libpdfium.so`
- macOS: `libpdfium.dylib`
- Windows: `pdfium.dll`

**Signature:**

```php
public static function extractBundledPdfium(): string
```

**Returns:** `string`

**Errors:** Throws `Error`.


---

### extractEmbeddedFiles()

Extract embedded file descriptors from a PDF document loaded via lopdf.

Walks the `/Names` → `/EmbeddedFiles` name tree in the catalog.
Returns an empty `Vec` if the document has no embedded files.

**Signature:**

```php
public static function extractEmbeddedFiles(Document $document): array<EmbeddedFile>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `document` | `Document` | Yes | The document |

**Returns:** `array<EmbeddedFile>`


---

### extractAndProcessEmbeddedFiles()

Extract embedded files from PDF bytes and recursively process them.

Returns `(children, warnings)`. The children are `ArchiveEntry` values
suitable for attaching to `InternalDocument.children`.

**Signature:**

```php
public static function extractAndProcessEmbeddedFiles(string $pdfBytes, ExtractionConfig $config): VecArchiveEntryVecProcessingWarning
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pdfBytes` | `string` | Yes | The pdf bytes |
| `config` | `ExtractionConfig` | Yes | The configuration options |

**Returns:** `VecArchiveEntryVecProcessingWarning`


---

### initializeFontCache()

Initialize the global font cache.

On first call, discovers and loads all system fonts. Subsequent calls are no-ops.
Caching is thread-safe via RwLock; concurrent reads during PDF processing are efficient.

**Returns:**

Ok if initialization succeeds or cache is already initialized, or PdfError if font discovery fails.

# Performance

- First call: 50-100ms (system font discovery + loading)
- Subsequent calls: < 1μs (no-op, just checks initialized flag)

**Signature:**

```php
public static function initializeFontCache(): void
```

**Returns:** `void`

**Errors:** Throws `PdfError`.


---

### getFontDescriptors()

Get cached font descriptors for Pdfium configuration.

Ensures the font cache is initialized, then returns font descriptors
derived from the cached fonts. This call is fast after the first invocation.

**Returns:**

A Vec of FontDescriptor objects suitable for `PdfiumConfig::set_font_provider()`.

# Performance

- First call: ~50-100ms (includes font discovery)
- Subsequent calls: < 1ms (reads from cache)

**Signature:**

```php
public static function getFontDescriptors(): array<FontDescriptor>
```

**Returns:** `array<FontDescriptor>`

**Errors:** Throws `PdfError`.


---

### cachedFontCount()

Get the number of cached fonts.

Useful for diagnostics and testing.

**Returns:**

Number of fonts in the cache, or 0 if not initialized.

**Signature:**

```php
public static function cachedFontCount(): int
```

**Returns:** `int`


---

### clearFontCache()

Clear the font cache (for testing purposes).

**Panics:**

Panics if the cache lock is poisoned, which should only happen in test scenarios
with deliberate panic injection.

**Signature:**

```php
public static function clearFontCache(): void
```

**Returns:** `void`


---

### extractImagesFromPdf()

**Signature:**

```php
public static function extractImagesFromPdf(string $pdfBytes): array<PdfImage>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pdfBytes` | `string` | Yes | The pdf bytes |

**Returns:** `array<PdfImage>`

**Errors:** Throws `Error`.


---

### extractImagesFromPdfWithPassword()

**Signature:**

```php
public static function extractImagesFromPdfWithPassword(string $pdfBytes, string $password): array<PdfImage>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pdfBytes` | `string` | Yes | The pdf bytes |
| `password` | `string` | Yes | The password |

**Returns:** `array<PdfImage>`

**Errors:** Throws `Error`.


---

### reextractRawImagesViaPdfium()

Re-extract images that have unusable formats (`"raw"`, `"ccitt"`, `"jbig2"`) by
rendering them through pdfium's bitmap pipeline, which handles all PDF filter
chains internally.

Returns the number of images successfully re-extracted.

**Signature:**

```php
public static function reextractRawImagesViaPdfium(string $pdfBytes, array<PdfImage> $images): int
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pdfBytes` | `string` | Yes | The pdf bytes |
| `images` | `array<PdfImage>` | Yes | The images |

**Returns:** `int`

**Errors:** Throws `Error`.


---

### detectLayoutForDocument()

Run layout detection on all pages of a PDF document.

Under the hood, this uses batched layout detection to prevent holding too many
full-resolution page images in memory simultaneously before detection.

**Signature:**

```php
public static function detectLayoutForDocument(string $pdfBytes, LayoutEngine $engine): DynamicImage
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pdfBytes` | `string` | Yes | The pdf bytes |
| `engine` | `LayoutEngine` | Yes | The layout engine |

**Returns:** `DynamicImage`

**Errors:** Throws `Error`.


---

### detectLayoutForImages()

Run layout detection on pre-rendered images.

Returns pixel-space `DetectionResult`s — no PDF coordinate conversion.
Use this when images are already available (e.g., from the OCR rendering
path) to avoid redundant PDF re-rendering.

**Signature:**

```php
public static function detectLayoutForImages(array<DynamicImage> $images, LayoutEngine $engine): array<DetectionResult>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `images` | `array<DynamicImage>` | Yes | The images |
| `engine` | `LayoutEngine` | Yes | The layout engine |

**Returns:** `array<DetectionResult>`

**Errors:** Throws `Error`.


---

### extractMetadata()

Extract PDF-specific metadata from raw bytes.

Returns only PDF-specific metadata (version, producer, encryption status, dimensions).

**Signature:**

```php
public static function extractMetadata(string $pdfBytes): PdfMetadata
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pdfBytes` | `string` | Yes | The pdf bytes |

**Returns:** `PdfMetadata`

**Errors:** Throws `Error`.


---

### extractMetadataWithPassword()

Extract PDF-specific metadata from raw bytes with optional password.

Returns only PDF-specific metadata (version, producer, encryption status, dimensions).

**Signature:**

```php
public static function extractMetadataWithPassword(string $pdfBytes, ?string $password = null): PdfMetadata
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pdfBytes` | `string` | Yes | The pdf bytes |
| `password` | `?string` | No | The password |

**Returns:** `PdfMetadata`

**Errors:** Throws `Error`.


---

### extractMetadataWithPasswords()

**Signature:**

```php
public static function extractMetadataWithPasswords(string $pdfBytes, array<string> $passwords): PdfMetadata
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pdfBytes` | `string` | Yes | The pdf bytes |
| `passwords` | `array<string>` | Yes | The passwords |

**Returns:** `PdfMetadata`

**Errors:** Throws `Error`.


---

### extractMetadataFromDocument()

Extract complete PDF metadata from a document.

Extracts common fields (title, subject, authors, keywords, dates, creator),
PDF-specific metadata, and optionally builds a PageStructure with boundaries.

  If provided, a PageStructure will be built with these boundaries.
* `content` - Optional extracted text content, used for blank page detection.
  If provided, `PageInfo.is_blank` will be populated based on text content analysis.
  If `null`, `is_blank` will be `null` for all pages.

**Returns:**

Returns a `PdfExtractionMetadata` struct containing all extracted metadata,
including page structure if boundaries were provided.

**Signature:**

```php
public static function extractMetadataFromDocument(PdfDocument $document, ?array<PageBoundary> $pageBoundaries = null, ?string $content = null): PdfExtractionMetadata
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `document` | `PdfDocument` | Yes | The PDF document to extract metadata from |
| `pageBoundaries` | `?array<PageBoundary>` | No | Optional vector of PageBoundary entries for building PageStructure. |
| `content` | `?string` | No | Optional extracted text content, used for blank page detection. |

**Returns:** `PdfExtractionMetadata`

**Errors:** Throws `Error`.


---

### extractCommonMetadataFromDocument()

Extract common metadata from a PDF document.

Returns common fields (title, authors, keywords, dates) that are now stored
in the base `Metadata` struct instead of format-specific metadata.

This function uses batch fetching with caching to optimize metadata extraction
by reducing repeated dictionary lookups. All metadata tags are fetched once and
cached in a single pass.

**Signature:**

```php
public static function extractCommonMetadataFromDocument(PdfDocument $document): CommonPdfMetadata
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `document` | `PdfDocument` | Yes | The pdf document |

**Returns:** `CommonPdfMetadata`

**Errors:** Throws `Error`.


---

### renderPageToImage()

**Signature:**

```php
public static function renderPageToImage(string $pdfBytes, int $pageIndex, PageRenderOptions $options): DynamicImage
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pdfBytes` | `string` | Yes | The pdf bytes |
| `pageIndex` | `int` | Yes | The page index |
| `options` | `PageRenderOptions` | Yes | The options to use |

**Returns:** `DynamicImage`

**Errors:** Throws `Error`.


---

### renderPdfPageToPng()

Render a single PDF page to a PNG-encoded byte buffer.

**Errors:**

Returns an error if the PDF is invalid, the page index is out of bounds,
or if the page fails to render.

**Signature:**

```php
public static function renderPdfPageToPng(string $pdfBytes, int $pageIndex, ?int $dpi = null, ?string $password = null): string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pdfBytes` | `string` | Yes | The pdf bytes |
| `pageIndex` | `int` | Yes | The page index |
| `dpi` | `?int` | No | The dpi |
| `password` | `?string` | No | The password |

**Returns:** `string`

**Errors:** Throws `Error`.


---

### extractWordsFromPage()

Extract words with positions from PDF page for table detection.

Groups adjacent characters into words based on spacing heuristics,
then converts to HocrWord format for table reconstruction.

**Returns:**

Vector of HocrWord objects with text and bounding box information.

**Note:**
This function requires the "ocr" feature to be enabled. Without it, returns an error.

**Signature:**

```php
public static function extractWordsFromPage(PdfPage $page, float $minConfidence): array<HocrWord>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `page` | `PdfPage` | Yes | PDF page to extract words from |
| `minConfidence` | `float` | Yes | Minimum confidence threshold (0.0-100.0). PDF text has high confidence (95.0). |

**Returns:** `array<HocrWord>`

**Errors:** Throws `Error`.


---

### segmentToHocrWord()

Convert a PDF `SegmentData` to an `HocrWord` for table reconstruction.

`SegmentData` uses PDF coordinates (y=0 at bottom, increases upward).
`HocrWord` uses image coordinates (y=0 at top, increases downward).

**Signature:**

```php
public static function segmentToHocrWord(SegmentData $seg, float $pageHeight): HocrWord
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `seg` | `SegmentData` | Yes | The segment data |
| `pageHeight` | `float` | Yes | The page height |

**Returns:** `HocrWord`


---

### splitSegmentToWords()

Split a `SegmentData` into word-level `HocrWord`s for table reconstruction.

Pdfium segments can contain multiple whitespace-separated words (merged by
shared baseline + font). For table cell matching, each word needs its own
bounding box so it can be assigned to the correct column/cell.

Single-word segments use `segment_to_hocr_word` directly (fast path).
Multi-word segments get proportional bbox estimation per word based on
byte offset within the segment text.

**Signature:**

```php
public static function splitSegmentToWords(SegmentData $seg, float $pageHeight): array<HocrWord>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `seg` | `SegmentData` | Yes | The segment data |
| `pageHeight` | `float` | Yes | The page height |

**Returns:** `array<HocrWord>`


---

### segmentsToWords()

Convert a page's segments to word-level `HocrWord`s for table extraction.

Splits multi-word segments into individual words with proportional bounding
boxes, ensuring each word can be independently matched to table cells.

**Signature:**

```php
public static function segmentsToWords(array<SegmentData> $segments, float $pageHeight): array<HocrWord>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `segments` | `array<SegmentData>` | Yes | The segments |
| `pageHeight` | `float` | Yes | The page height |

**Returns:** `array<HocrWord>`


---

### postProcessTable()

Post-process a raw table grid to validate structure and clean up.

Returns `null` if the table fails structural validation.

When `layout_guided` is true, the layout model already confirmed this is
a table, so validation thresholds are relaxed:
- Minimum columns: 3 → 2
- Column sparsity: 75% → 95%
- Overall density: 40% → 15%
- Prose detection: reject if >70% cells >100 chars (vs >50% >60 chars)
- Prose detection: reject if avg cell >80 chars (vs >50 chars)
- Single-word cell: reject if >85% single-word (vs >70%)
- Content asymmetry: reject if one col >92% of text (vs >85%)
- Column-text-flow: applied equally (reject if >60% rows flow through)

**Signature:**

```php
public static function postProcessTable(array<array<string>> $table, bool $layoutGuided, bool $allowSingleColumn): ?array<array<string>>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `table` | `array<array<string>>` | Yes | The table |
| `layoutGuided` | `bool` | Yes | The layout guided |
| `allowSingleColumn` | `bool` | Yes | The allow single column |

**Returns:** `?array<array<string>>`


---

### isWellFormedTable()

Validate whether a reconstructed table grid represents a well-formed table
rather than multi-column prose or a repeated page element.

Returns `true` if the grid looks like a real table, `false` if it should be
rejected and its content emitted as paragraph text instead.

The checks catch cases the layout model misidentifies as tables:
- Multi-column prose split into a grid (detected via row coherence and column uniformity)
- Repeated page elements (headers/footers detected as tables on every page)
- Low-vocabulary repetitive content (same few words in every row)

**Signature:**

```php
public static function isWellFormedTable(array<array<string>> $grid): bool
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `grid` | `array<array<string>>` | Yes | The grid |

**Returns:** `bool`


---

### extractTextFromPdf()

**Signature:**

```php
public static function extractTextFromPdf(string $pdfBytes): string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pdfBytes` | `string` | Yes | The pdf bytes |

**Returns:** `string`

**Errors:** Throws `Error`.


---

### extractTextFromPdfWithPassword()

**Signature:**

```php
public static function extractTextFromPdfWithPassword(string $pdfBytes, string $password): string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pdfBytes` | `string` | Yes | The pdf bytes |
| `password` | `string` | Yes | The password |

**Returns:** `string`

**Errors:** Throws `Error`.


---

### extractTextFromPdfWithPasswords()

**Signature:**

```php
public static function extractTextFromPdfWithPasswords(string $pdfBytes, array<string> $passwords): string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pdfBytes` | `string` | Yes | The pdf bytes |
| `passwords` | `array<string>` | Yes | The passwords |

**Returns:** `string`

**Errors:** Throws `Error`.


---

### extractTextAndMetadataFromPdfDocument()

Extract text and metadata from PDF document in a single pass.

This is an optimized function that extracts both text and metadata in one pass
through the document, avoiding redundant document parsing. It combines the
functionality of `extract_text_from_pdf_document` and
`extract_metadata_from_document` into a single unified operation.

**Returns:**

A tuple containing:
- The extracted text content (String)
- Optional page boundaries when page tracking is enabled (Vec<PageBoundary>)
- Optional per-page content when extract_pages is enabled (Vec<PageContent>)
- Complete extraction metadata (PdfExtractionMetadata)

# Performance

This function is optimized for single-pass extraction. It performs all document
scanning in one iteration, avoiding redundant pdfium operations compared to
calling text and metadata extraction separately.

**Signature:**

```php
public static function extractTextAndMetadataFromPdfDocument(PdfDocument $document, ?ExtractionConfig $extractionConfig = null): PdfUnifiedExtractionResult
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `document` | `PdfDocument` | Yes | The PDF document to extract from |
| `extractionConfig` | `?ExtractionConfig` | No | Optional extraction configuration for hierarchy and page tracking |

**Returns:** `PdfUnifiedExtractionResult`

**Errors:** Throws `Error`.


---

### extractTextFromPdfDocument()

Extract text from PDF document with optional page boundary tracking.

**Returns:**

A tuple containing:
- The extracted text content (String)
- Optional page boundaries when page tracking is enabled (Vec<PageBoundary>)
- Optional per-page content when extract_pages is enabled (Vec<PageContent>)

# Implementation Details

Uses lazy page-by-page iteration to reduce memory footprint. Pages are processed
one at a time and released after extraction, rather than accumulating all pages
in memory. This approach saves 40-50MB for large documents while improving
performance by 15-25% through reduced upfront work.

When page_config is None, uses fast path with minimal overhead.
When page_config is Some, tracks byte offsets using .len() for O(1) performance (UTF-8 valid boundaries).

**Signature:**

```php
public static function extractTextFromPdfDocument(PdfDocument $document, ?PageConfig $pageConfig = null, ?ExtractionConfig $extractionConfig = null): PdfTextExtractionResult
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `document` | `PdfDocument` | Yes | The PDF document to extract text from |
| `pageConfig` | `?PageConfig` | No | Optional page configuration for boundary tracking and page markers |
| `extractionConfig` | `?ExtractionConfig` | No | Optional extraction configuration for hierarchy detection |

**Returns:** `PdfTextExtractionResult`

**Errors:** Throws `Error`.


---

### serializeToToon()

Serialize an `ExtractionResult` to TOON (Token-Oriented Object Notation).

TOON is a token-efficient alternative to JSON for LLM prompts.
Losslessly convertible to/from JSON but uses fewer tokens.

**Signature:**

```php
public static function serializeToToon(ExtractionResult $result): string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `result` | `ExtractionResult` | Yes | The extraction result |

**Returns:** `string`

**Errors:** Throws `Error`.


---

### serializeToJson()

Serialize an `ExtractionResult` to pretty-printed JSON.

**Signature:**

```php
public static function serializeToJson(ExtractionResult $result): string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `result` | `ExtractionResult` | Yes | The extraction result |

**Returns:** `string`

**Errors:** Throws `Error`.


---

## Types

### AccelerationConfig

Hardware acceleration configuration for ONNX Runtime models.

Controls which execution provider (CPU, CoreML, CUDA, TensorRT) is used
for inference in layout detection and embedding generation.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `provider` | `ExecutionProviderType` | `ExecutionProviderType::Auto` | Execution provider to use for ONNX inference. |
| `deviceId` | `int` | `null` | GPU device ID (for CUDA/TensorRT). Ignored for CPU/CoreML/Auto. |


---

### AnchorProperties

Properties for anchored drawings.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `behindDoc` | `bool` | `null` | Behind doc |
| `layoutInCell` | `bool` | `null` | Layout in cell |
| `relativeHeight` | `?int` | `null` | Relative height |
| `positionH` | `?Position` | `null` | Position h (position) |
| `positionV` | `?Position` | `null` | Position v (position) |
| `wrapType` | `WrapType` | `WrapType::None` | Wrap type (wrap type) |


---

### ApiDoc

OpenAPI documentation structure.

Defines all endpoints, request/response schemas, and examples
for the Kreuzberg document extraction API.


---

### ArchiveEntry

A single file extracted from an archive.

When archives (ZIP, TAR, 7Z, GZIP) are extracted with recursive extraction
enabled, each processable file produces its own full `ExtractionResult`.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `path` | `string` | — | Archive-relative file path (e.g. "folder/document.pdf"). |
| `mimeType` | `string` | — | Detected MIME type of the file. |
| `result` | `ExtractionResult` | — | Full extraction result for this file. |


---

### ArchiveMetadata

Archive (ZIP/TAR/7Z) metadata.

Extracted from compressed archive files containing file lists and size information.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `format` | `Str` | — | Archive format ("ZIP", "TAR", "7Z", etc.) |
| `fileCount` | `int` | — | Total number of files in the archive |
| `fileList` | `array<string>` | — | List of file paths within the archive |
| `totalSize` | `int` | — | Total uncompressed size in bytes |
| `compressedSize` | `?int` | `null` | Compressed size in bytes (if available) |


---

### Attributes

Element attributes in Djot.

Represents the attributes attached to elements using {.class #id key="value"} syntax.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `id` | `?string` | `null` | Element ID (#identifier) |
| `classes` | `array<string>` | `[]` | CSS classes (.class1 .class2) |
| `keyValues` | `array<StringString>` | `[]` | Key-value pairs (key="value") |


---

### BBox

Bounding box in original image coordinates (x1, y1) top-left, (x2, y2) bottom-right.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `x1` | `float` | — | X1 |
| `y1` | `float` | — | Y1 |
| `x2` | `float` | — | X2 |
| `y2` | `float` | — | Y2 |

#### Methods

##### width()

**Signature:**

```php
public function width(): float
```

##### height()

**Signature:**

```php
public function height(): float
```

##### area()

**Signature:**

```php
public function area(): float
```

##### center()

**Signature:**

```php
public function center(): F32F32
```

##### intersectionArea()

Area of intersection with another bounding box.

**Signature:**

```php
public function intersectionArea(BBox $other): float
```

##### iou()

Intersection over Union with another bounding box.

**Signature:**

```php
public function iou(BBox $other): float
```

##### containmentOf()

Fraction of `other` that is contained within `self`.
Returns 0.0..=1.0 where 1.0 means `other` is fully inside `self`.

**Signature:**

```php
public function containmentOf(BBox $other): float
```

##### pageCoverage()

Fraction of page area this bbox covers.

**Signature:**

```php
public function pageCoverage(float $pageWidth, float $pageHeight): float
```

##### fmt()

**Signature:**

```php
public function fmt(Formatter $f): Unknown
```


---

### BatchItemResult

Batch item result for processing multiple files

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `filePath` | `string` | — | File path |
| `success` | `bool` | — | Success |
| `result` | `?OcrExtractionResult` | `null` | Result (ocr extraction result) |
| `error` | `?string` | `null` | Error |


---

### BatchProcessor

Batch processor that manages object pools for optimized extraction.

This struct manages the lifecycle of reusable object pools used during
batch extraction. Pools are created lazily on first use and reused across
all documents processed by this batch processor.

# Lazy Initialization

Pools are initialized on demand to reduce memory usage for applications
that may not use batch processing immediately or at all.

#### Methods

##### withConfig()

Create a new batch processor with custom pool configuration.

Pools are not created immediately but lazily on first access.

**Returns:**

A new `BatchProcessor` configured with the provided settings.

**Signature:**

```php
public static function withConfig(BatchProcessorConfig $config): BatchProcessor
```

##### withPoolHint()

Create a batch processor with pool sizes optimized for a specific document.

This method uses a `PoolSizeHint` (derived from file size and MIME type)
to create a batch processor with appropriately sized pools. This reduces
memory waste by tailoring pool allocation to actual document complexity.

**Returns:**

A new `BatchProcessor` configured with the hint-based pool sizes

**Signature:**

```php
public static function withPoolHint(PoolSizeHint $hint): BatchProcessor
```

##### stringPool()

Get a reference to the string buffer pool.

Creates the pool lazily on first access.
Useful for custom pooling implementations that need direct pool access.

**Signature:**

```php
public function stringPool(): StringBufferPool
```

##### bytePool()

Get a reference to the byte buffer pool.

Creates the pool lazily on first access.
Useful for custom pooling implementations that need direct pool access.

**Signature:**

```php
public function bytePool(): ByteBufferPool
```

##### config()

Get the current configuration.

**Signature:**

```php
public function config(): BatchProcessorConfig
```

##### stringPoolSize()

Get the number of pooled string buffers currently available.

**Signature:**

```php
public function stringPoolSize(): int
```

##### bytePoolSize()

Get the number of pooled byte buffers currently available.

**Signature:**

```php
public function bytePoolSize(): int
```

##### clearPools()

Clear all pooled objects, forcing new allocations on next acquire.

Useful for memory-constrained environments or to reclaim memory
after processing large batches.

**Signature:**

```php
public function clearPools(): void
```

##### default()

**Signature:**

```php
public static function default(): BatchProcessor
```


---

### BatchProcessorConfig

Configuration for batch processing with pooling optimizations.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `stringPoolSize` | `int` | `10` | Maximum number of string buffers to maintain in the pool |
| `stringBufferCapacity` | `int` | `8192` | Initial capacity for pooled string buffers in bytes |
| `bytePoolSize` | `int` | `10` | Maximum number of byte buffers to maintain in the pool |
| `byteBufferCapacity` | `int` | `65536` | Initial capacity for pooled byte buffers in bytes |
| `maxConcurrent` | `?int` | `null` | Maximum concurrent extractions (for concurrency control) |

#### Methods

##### default()

**Signature:**

```php
public static function default(): BatchProcessorConfig
```


---

### BibtexExtractor

BibTeX bibliography extractor.

Parses BibTeX files and extracts structured bibliography data including
entries, authors, publication years, and entry type distribution.

#### Methods

##### default()

**Signature:**

```php
public static function default(): BibtexExtractor
```

##### name()

**Signature:**

```php
public function name(): string
```

##### version()

**Signature:**

```php
public function version(): string
```

##### initialize()

**Signature:**

```php
public function initialize(): void
```

##### shutdown()

**Signature:**

```php
public function shutdown(): void
```

##### description()

**Signature:**

```php
public function description(): string
```

##### author()

**Signature:**

```php
public function author(): string
```

##### extractBytes()

**Signature:**

```php
public function extractBytes(string $content, string $mimeType, ExtractionConfig $config): InternalDocument
```

##### supportedMimeTypes()

**Signature:**

```php
public function supportedMimeTypes(): array<string>
```

##### priority()

**Signature:**

```php
public function priority(): int
```


---

### BibtexMetadata

BibTeX bibliography metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `entryCount` | `int` | `null` | Number of entry |
| `citationKeys` | `array<string>` | `[]` | Citation keys |
| `authors` | `array<string>` | `[]` | Authors |
| `yearRange` | `?YearRange` | `null` | Year range (year range) |
| `entryTypes` | `?array<string, int>` | `{}` | Entry types |


---

### BorderStyle

A single border specification.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `style` | `string` | — | Style |
| `size` | `?int` | `null` | Size in bytes |
| `color` | `?string` | `null` | Color |
| `space` | `?int` | `null` | Space |


---

### BoundingBox

Bounding box coordinates for element positioning.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `x0` | `float` | — | Left x-coordinate |
| `y0` | `float` | — | Bottom y-coordinate |
| `x1` | `float` | — | Right x-coordinate |
| `y1` | `float` | — | Top y-coordinate |


---

### ByteBufferPool

Convenience type alias for a pooled Vec<u8>.


---

### CacheStats

Cache statistics.

Provides information about the extraction result cache,
including size, file count, and age distribution.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `totalFiles` | `int` | — | Total number of cached files |
| `totalSizeMb` | `float` | — | Total cache size in megabytes |
| `availableSpaceMb` | `float` | — | Available disk space in megabytes |
| `oldestFileAgeDays` | `float` | — | Age of the oldest cached file in days |
| `newestFileAgeDays` | `float` | — | Age of the newest cached file in days |


---

### CellBBox

A cell bounding box within the reconstructed table grid.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `x1` | `float` | — | X1 |
| `y1` | `float` | — | Y1 |
| `x2` | `float` | — | X2 |
| `y2` | `float` | — | Y2 |


---

### CellBorders

Per-cell borders (4 sides).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `top` | `?BorderStyle` | `null` | Top (border style) |
| `bottom` | `?BorderStyle` | `null` | Bottom (border style) |
| `left` | `?BorderStyle` | `null` | Left (border style) |
| `right` | `?BorderStyle` | `null` | Right (border style) |


---

### CellMargins

Cell margins (used for both table-level defaults and per-cell overrides).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `top` | `?int` | `null` | Top |
| `bottom` | `?int` | `null` | Bottom |
| `left` | `?int` | `null` | Left |
| `right` | `?int` | `null` | Right |


---

### CellProperties

Cell-level properties from `<w:tcPr>`.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `width` | `?TableWidth` | `null` | Width (table width) |
| `gridSpan` | `?int` | `null` | Grid span |
| `vMerge` | `?VerticalMerge` | `VerticalMerge::Restart` | V merge (vertical merge) |
| `borders` | `?CellBorders` | `null` | Borders (cell borders) |
| `shading` | `?CellShading` | `null` | Shading (cell shading) |
| `margins` | `?CellMargins` | `null` | Margins (cell margins) |
| `verticalAlign` | `?string` | `null` | Vertical align |
| `textDirection` | `?string` | `null` | Text direction |
| `noWrap` | `bool` | `null` | No wrap |


---

### CellShading

Cell shading/background.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `fill` | `?string` | `null` | Fill |
| `color` | `?string` | `null` | Color |
| `val` | `?string` | `null` | Val |


---

### CfbReader

#### Methods

##### fromBytes()

Open a CFB compound file from raw bytes.

**Signature:**

```php
public static function fromBytes(string $bytes): CfbReader
```


---

### Chunk

A text chunk with optional embedding and metadata.

Chunks are created when chunking is enabled in `ExtractionConfig`. Each chunk
contains the text content, optional embedding vector (if embedding generation
is configured), and metadata about its position in the document.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `content` | `string` | — | The text content of this chunk. |
| `chunkType` | `ChunkType` | — | Semantic structural classification of this chunk. Assigned by the heuristic classifier based on content patterns and heading context. Defaults to `ChunkType.Unknown` when no rule matches. |
| `embedding` | `?array<float>` | `null` | Optional embedding vector for this chunk. Only populated when `EmbeddingConfig` is provided in chunking configuration. The dimensionality depends on the chosen embedding model. |
| `metadata` | `ChunkMetadata` | — | Metadata about this chunk's position and properties. |


---

### ChunkMetadata

Metadata about a chunk's position in the original document.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `byteStart` | `int` | — | Byte offset where this chunk starts in the original text (UTF-8 valid boundary). |
| `byteEnd` | `int` | — | Byte offset where this chunk ends in the original text (UTF-8 valid boundary). |
| `tokenCount` | `?int` | `null` | Number of tokens in this chunk (if available). This is calculated by the embedding model's tokenizer if embeddings are enabled. |
| `chunkIndex` | `int` | — | Zero-based index of this chunk in the document. |
| `totalChunks` | `int` | — | Total number of chunks in the document. |
| `firstPage` | `?int` | `null` | First page number this chunk spans (1-indexed). Only populated when page tracking is enabled in extraction configuration. |
| `lastPage` | `?int` | `null` | Last page number this chunk spans (1-indexed, equal to first_page for single-page chunks). Only populated when page tracking is enabled in extraction configuration. |
| `headingContext` | `?HeadingContext` | `null` | Heading context when using Markdown chunker. Contains the heading hierarchy this chunk falls under. Only populated when `ChunkerType.Markdown` is used. |


---

### ChunkingConfig

Chunking configuration.

Configures text chunking for document content, including chunk size,
overlap, trimming behavior, and optional embeddings.

Use `..the default constructor` when constructing to allow for future field additions:

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `maxCharacters` | `int` | `1000` | Maximum size per chunk (in units determined by `sizing`). When `sizing` is `Characters` (default), this is the max character count. When using token-based sizing, this is the max token count. Default: 1000 |
| `overlap` | `int` | `200` | Overlap between chunks (in units determined by `sizing`). Default: 200 |
| `trim` | `bool` | `true` | Whether to trim whitespace from chunk boundaries. Default: true |
| `chunkerType` | `ChunkerType` | `ChunkerType::Text` | Type of chunker to use (Text or Markdown). Default: Text |
| `embedding` | `?EmbeddingConfig` | `null` | Optional embedding configuration for chunk embeddings. |
| `preset` | `?string` | `null` | Use a preset configuration (overrides individual settings if provided). |
| `sizing` | `ChunkSizing` | `ChunkSizing::Characters` | How to measure chunk size. Default: `Characters` (Unicode character count). Enable `chunking-tiktoken` or `chunking-tokenizers` features for token-based sizing. |
| `prependHeadingContext` | `bool` | `false` | When `True` and `chunker_type` is `Markdown`, prepend the heading hierarchy path (e.g. `"# Title > ## Section\n\n"`) to each chunk's content string. This is useful for RAG pipelines where each chunk needs self-contained context about its position in the document structure. Default: `False` |

#### Methods

##### withChunkerType()

Set the chunker type.

**Signature:**

```php
public function withChunkerType(ChunkerType $chunkerType): ChunkingConfig
```

##### withSizing()

Set the sizing strategy.

**Signature:**

```php
public function withSizing(ChunkSizing $sizing): ChunkingConfig
```

##### withPrependHeadingContext()

Enable or disable prepending heading context to chunk content.

**Signature:**

```php
public function withPrependHeadingContext(bool $prepend): ChunkingConfig
```

##### default()

**Signature:**

```php
public static function default(): ChunkingConfig
```


---

### ChunkingProcessor

Post-processor that chunks text in document content.

This processor:
- Runs in the Middle processing stage
- Only processes when `config.chunking` is configured
- Stores chunks in `result.chunks`
- Uses configurable chunk size and overlap

#### Methods

##### name()

**Signature:**

```php
public function name(): string
```

##### version()

**Signature:**

```php
public function version(): string
```

##### initialize()

**Signature:**

```php
public function initialize(): void
```

##### shutdown()

**Signature:**

```php
public function shutdown(): void
```

##### process()

**Signature:**

```php
public function process(ExtractionResult $result, ExtractionConfig $config): void
```

##### processingStage()

**Signature:**

```php
public function processingStage(): ProcessingStage
```

##### shouldProcess()

**Signature:**

```php
public function shouldProcess(ExtractionResult $result, ExtractionConfig $config): bool
```

##### estimatedDurationMs()

**Signature:**

```php
public function estimatedDurationMs(ExtractionResult $result): int
```


---

### ChunkingResult

Result of a text chunking operation.

Contains the generated chunks and metadata about the chunking.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `chunks` | `array<Chunk>` | — | List of text chunks |
| `chunkCount` | `int` | — | Total number of chunks generated |


---

### CitationExtractor

Citation format extractor for RIS, PubMed/MEDLINE, and EndNote XML formats.

Parses citation files and extracts structured bibliography data including
entries, authors, publication years, and format-specific metadata.

#### Methods

##### default()

**Signature:**

```php
public static function default(): CitationExtractor
```

##### name()

**Signature:**

```php
public function name(): string
```

##### version()

**Signature:**

```php
public function version(): string
```

##### initialize()

**Signature:**

```php
public function initialize(): void
```

##### shutdown()

**Signature:**

```php
public function shutdown(): void
```

##### description()

**Signature:**

```php
public function description(): string
```

##### author()

**Signature:**

```php
public function author(): string
```

##### extractBytes()

**Signature:**

```php
public function extractBytes(string $content, string $mimeType, ExtractionConfig $config): InternalDocument
```

##### supportedMimeTypes()

**Signature:**

```php
public function supportedMimeTypes(): array<string>
```

##### priority()

**Signature:**

```php
public function priority(): int
```


---

### CitationMetadata

Citation file metadata (RIS, PubMed, EndNote).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `citationCount` | `int` | `null` | Number of citation |
| `format` | `?string` | `null` | Format |
| `authors` | `array<string>` | `[]` | Authors |
| `yearRange` | `?YearRange` | `null` | Year range (year range) |
| `dois` | `array<string>` | `[]` | Dois |
| `keywords` | `array<string>` | `[]` | Keywords |


---

### CodeExtractor

Source code extractor using tree-sitter language pack.

Detects the programming language from the file extension or shebang line,
then uses tree-sitter to parse and extract structural information.

#### Methods

##### default()

**Signature:**

```php
public static function default(): CodeExtractor
```

##### name()

**Signature:**

```php
public function name(): string
```

##### version()

**Signature:**

```php
public function version(): string
```

##### initialize()

**Signature:**

```php
public function initialize(): void
```

##### shutdown()

**Signature:**

```php
public function shutdown(): void
```

##### description()

**Signature:**

```php
public function description(): string
```

##### author()

**Signature:**

```php
public function author(): string
```

##### extractBytes()

**Signature:**

```php
public function extractBytes(string $content, string $mimeType, ExtractionConfig $config): InternalDocument
```

##### extractFile()

**Signature:**

```php
public function extractFile(string $path, string $mimeType, ExtractionConfig $config): InternalDocument
```

##### supportedMimeTypes()

**Signature:**

```php
public function supportedMimeTypes(): array<string>
```

##### priority()

**Signature:**

```php
public function priority(): int
```

##### asSyncExtractor()

**Signature:**

```php
public function asSyncExtractor(): ?SyncExtractor
```

##### extractSync()

**Signature:**

```php
public function extractSync(string $content, string $mimeType, ExtractionConfig $config): InternalDocument
```


---

### ColorScheme

Color scheme containing all 12 standard Office theme colors.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `name` | `string` | `null` | Color scheme name. |
| `dk1` | `?ThemeColor` | `ThemeColor::Rgb` | Dark 1 (dark background) color. |
| `lt1` | `?ThemeColor` | `ThemeColor::Rgb` | Light 1 (light background) color. |
| `dk2` | `?ThemeColor` | `ThemeColor::Rgb` | Dark 2 color. |
| `lt2` | `?ThemeColor` | `ThemeColor::Rgb` | Light 2 color. |
| `accent1` | `?ThemeColor` | `ThemeColor::Rgb` | Accent color 1. |
| `accent2` | `?ThemeColor` | `ThemeColor::Rgb` | Accent color 2. |
| `accent3` | `?ThemeColor` | `ThemeColor::Rgb` | Accent color 3. |
| `accent4` | `?ThemeColor` | `ThemeColor::Rgb` | Accent color 4. |
| `accent5` | `?ThemeColor` | `ThemeColor::Rgb` | Accent color 5. |
| `accent6` | `?ThemeColor` | `ThemeColor::Rgb` | Accent color 6. |
| `hlink` | `?ThemeColor` | `ThemeColor::Rgb` | Hyperlink color. |
| `folHlink` | `?ThemeColor` | `ThemeColor::Rgb` | Followed hyperlink color. |


---

### ColumnLayout

Column layout configuration.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `count` | `?int` | `null` | Number of columns. |
| `spaceTwips` | `?int` | `null` | Space between columns in twips. |
| `equalWidth` | `?bool` | `null` | Whether columns have equal width. |


---

### CommonPdfMetadata

Common metadata fields extracted from a PDF.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `title` | `?string` | `null` | Title |
| `subject` | `?string` | `null` | Subject |
| `authors` | `?array<string>` | `null` | Authors |
| `keywords` | `?array<string>` | `null` | Keywords |
| `createdAt` | `?string` | `null` | Created at |
| `modifiedAt` | `?string` | `null` | Modified at |
| `createdBy` | `?string` | `null` | Created by |


---

### ConcurrencyConfig

Controls thread usage for constrained environments.

Set `max_threads` to cap all internal thread pools (Rayon, ONNX Runtime
intra-op) and batch concurrency to a single limit.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `maxThreads` | `?int` | `null` | Maximum number of threads for all internal thread pools. Caps Rayon global pool size, ONNX Runtime intra-op threads, and (when `max_concurrent_extractions` is unset) the batch concurrency semaphore. When `None`, system defaults are used. |


---

### ContentFilterConfig

Cross-extractor content filtering configuration.

Controls whether "furniture" content (headers, footers, page numbers,
watermarks, repeating text) is included in or stripped from extraction
results. Applies across all extractors (PDF, DOCX, RTF, ODT, HTML, etc.)
with format-specific implementation.

When `null` on `ExtractionConfig`, each extractor uses its current
default behavior unchanged.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `includeHeaders` | `bool` | `false` | Include running headers in extraction output. - PDF: Disables top-margin furniture stripping and prevents the layout model from treating `PageHeader`-classified regions as furniture. - DOCX: Includes document headers in text output. - RTF/ODT: Headers already included; this is a no-op when true. - HTML/EPUB: Keeps `<header>` element content. Default: `False` (headers are stripped or excluded). |
| `includeFooters` | `bool` | `false` | Include running footers in extraction output. - PDF: Disables bottom-margin furniture stripping and prevents the layout model from treating `PageFooter`-classified regions as furniture. - DOCX: Includes document footers in text output. - RTF/ODT: Footers already included; this is a no-op when true. - HTML/EPUB: Keeps `<footer>` element content. Default: `False` (footers are stripped or excluded). |
| `stripRepeatingText` | `bool` | `true` | Enable the heuristic cross-page repeating text detector. When `True` (default), text that repeats verbatim across a supermajority of pages is classified as furniture and stripped.  Disable this if brand names or repeated headings are being incorrectly removed by the heuristic. Note: when a layout-detection model is active, the model may independently classify page-header / page-footer regions as furniture on a per-page basis. To preserve those regions, set `include_headers = true` and/or `include_footers = true` in addition to disabling this flag. Primarily affects PDF extraction. Default: `True`. |
| `includeWatermarks` | `bool` | `false` | Include watermark text in extraction output. - PDF: Keeps watermark artifacts and arXiv identifiers. - Other formats: No effect currently. Default: `False` (watermarks are stripped). |

#### Methods

##### default()

**Signature:**

```php
public static function default(): ContentFilterConfig
```


---

### ContributorRole

JATS contributor with role.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `name` | `string` | — | The name |
| `role` | `?string` | `null` | Role |


---

### CoreProperties

Dublin Core metadata from docProps/core.xml

Contains standard metadata fields defined by the Dublin Core standard
and Office-specific extensions.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `title` | `?string` | `null` | Document title |
| `subject` | `?string` | `null` | Document subject/topic |
| `creator` | `?string` | `null` | Document creator/author |
| `keywords` | `?string` | `null` | Keywords or tags |
| `description` | `?string` | `null` | Document description/abstract |
| `lastModifiedBy` | `?string` | `null` | User who last modified the document |
| `revision` | `?string` | `null` | Revision number |
| `created` | `?string` | `null` | Creation timestamp (ISO 8601) |
| `modified` | `?string` | `null` | Last modification timestamp (ISO 8601) |
| `category` | `?string` | `null` | Document category |
| `contentStatus` | `?string` | `null` | Content status (Draft, Final, etc.) |
| `language` | `?string` | `null` | Document language |
| `identifier` | `?string` | `null` | Unique identifier |
| `version` | `?string` | `null` | Document version |
| `lastPrinted` | `?string` | `null` | Last print timestamp (ISO 8601) |


---

### CsvExtractor

CSV/TSV extractor with proper field parsing.

Replaces raw text passthrough with structured CSV parsing,
producing space-separated text output and populated `tables` field.

#### Methods

##### default()

**Signature:**

```php
public static function default(): CsvExtractor
```

##### name()

**Signature:**

```php
public function name(): string
```

##### version()

**Signature:**

```php
public function version(): string
```

##### initialize()

**Signature:**

```php
public function initialize(): void
```

##### shutdown()

**Signature:**

```php
public function shutdown(): void
```

##### description()

**Signature:**

```php
public function description(): string
```

##### author()

**Signature:**

```php
public function author(): string
```

##### extractBytes()

**Signature:**

```php
public function extractBytes(string $content, string $mimeType, ExtractionConfig $config): InternalDocument
```

##### supportedMimeTypes()

**Signature:**

```php
public function supportedMimeTypes(): array<string>
```

##### priority()

**Signature:**

```php
public function priority(): int
```


---

### CsvMetadata

CSV/TSV file metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `rowCount` | `int` | `null` | Number of row |
| `columnCount` | `int` | `null` | Number of column |
| `delimiter` | `?string` | `null` | Delimiter |
| `hasHeader` | `bool` | `null` | Whether header |
| `columnTypes` | `?array<string>` | `[]` | Column types |


---

### CustomProperties

Custom properties from docProps/custom.xml

Maps property names to their values. Values are converted to JSON types
based on the VT (Variant Type) specified in the XML.


---

### DbfExtractor

Extractor for dBASE (.dbf) files.

Reads all records and formats them as a markdown table with
column headers derived from field names.

#### Methods

##### default()

**Signature:**

```php
public static function default(): DbfExtractor
```

##### name()

**Signature:**

```php
public function name(): string
```

##### version()

**Signature:**

```php
public function version(): string
```

##### initialize()

**Signature:**

```php
public function initialize(): void
```

##### shutdown()

**Signature:**

```php
public function shutdown(): void
```

##### description()

**Signature:**

```php
public function description(): string
```

##### author()

**Signature:**

```php
public function author(): string
```

##### extractBytes()

**Signature:**

```php
public function extractBytes(string $content, string $mimeType, ExtractionConfig $config): InternalDocument
```

##### supportedMimeTypes()

**Signature:**

```php
public function supportedMimeTypes(): array<string>
```

##### priority()

**Signature:**

```php
public function priority(): int
```


---

### DbfFieldInfo

dBASE field information.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `name` | `string` | — | The name |
| `fieldType` | `string` | — | Field type |


---

### DbfMetadata

dBASE (DBF) file metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `recordCount` | `int` | `null` | Number of record |
| `fieldCount` | `int` | `null` | Number of field |
| `fields` | `array<DbfFieldInfo>` | `[]` | Fields |


---

### DepthValidator

Helper struct for validating nesting depth.

#### Methods

##### push()

Push a level (increase depth).

**Returns:**
* `Ok(())` if depth is within limits
* `Err(SecurityError)` if depth exceeds limit

**Signature:**

```php
public function push(): void
```

##### pop()

Pop a level (decrease depth).

**Signature:**

```php
public function pop(): void
```

##### currentDepth()

Get current depth.

**Signature:**

```php
public function currentDepth(): int
```


---

### DetectTimings

Granular timing breakdown for a single `detect()` call.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `preprocessMs` | `float` | `null` | Time spent in image preprocessing (resize, letterbox, normalize, tensor allocation). |
| `onnxMs` | `float` | `null` | Time for the ONNX `session.run()` call (actual neural network computation). |
| `modelTotalMs` | `float` | `null` | Total time from start of model call to end of raw output decoding. |
| `postprocessMs` | `float` | `null` | Time spent in postprocessing heuristics (confidence filtering, overlap resolution). |


---

### DetectionResult

Page-level detection result containing all detections and page metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `pageWidth` | `int` | — | Page width |
| `pageHeight` | `int` | — | Page height |
| `detections` | `array<LayoutDetection>` | — | Detections |


---

### DjotContent

Comprehensive Djot document structure with semantic preservation.

This type captures the full richness of Djot markup, including:
- Block-level structures (headings, lists, blockquotes, code blocks, etc.)
- Inline formatting (emphasis, strong, highlight, subscript, superscript, etc.)
- Attributes (classes, IDs, key-value pairs)
- Links, images, footnotes
- Math expressions (inline and display)
- Tables with full structure

Available when the `djot` feature is enabled.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `plainText` | `string` | — | Plain text representation for backwards compatibility |
| `blocks` | `array<FormattedBlock>` | — | Structured block-level content |
| `metadata` | `Metadata` | — | Metadata from YAML frontmatter |
| `tables` | `array<Table>` | — | Extracted tables as structured data |
| `images` | `array<DjotImage>` | — | Extracted images with metadata |
| `links` | `array<DjotLink>` | — | Extracted links with URLs |
| `footnotes` | `array<Footnote>` | — | Footnote definitions |
| `attributes` | `array<StringAttributes>` | — | Attributes mapped by element identifier (if present) |


---

### DjotExtractor

Djot markup extractor with metadata and table support.

Parses Djot documents with YAML frontmatter, extracting:
- Metadata from YAML frontmatter
- Plain text content
- Tables as structured data
- Document structure (headings, links, code blocks)

#### Methods

##### buildInternalDocument()

Build an `InternalDocument` from jotdown events.

**Signature:**

```php
public static function buildInternalDocument(array<Event> $events): InternalDocument
```

##### default()

**Signature:**

```php
public static function default(): DjotExtractor
```

##### name()

**Signature:**

```php
public function name(): string
```

##### version()

**Signature:**

```php
public function version(): string
```

##### initialize()

**Signature:**

```php
public function initialize(): void
```

##### shutdown()

**Signature:**

```php
public function shutdown(): void
```

##### description()

**Signature:**

```php
public function description(): string
```

##### author()

**Signature:**

```php
public function author(): string
```

##### extractBytes()

**Signature:**

```php
public function extractBytes(string $content, string $mimeType, ExtractionConfig $config): InternalDocument
```

##### extractFile()

**Signature:**

```php
public function extractFile(string $path, string $mimeType, ExtractionConfig $config): InternalDocument
```

##### supportedMimeTypes()

**Signature:**

```php
public function supportedMimeTypes(): array<string>
```

##### priority()

**Signature:**

```php
public function priority(): int
```


---

### DjotImage

Image element in Djot.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `src` | `string` | — | Image source URL or path |
| `alt` | `string` | — | Alternative text |
| `title` | `?string` | `null` | Optional title |
| `attributes` | `?Attributes` | `null` | Element attributes |


---

### DjotLink

Link element in Djot.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `url` | `string` | — | Link URL |
| `text` | `string` | — | Link text content |
| `title` | `?string` | `null` | Optional title |
| `attributes` | `?Attributes` | `null` | Element attributes |


---

### DocExtractionResult

Result of DOC text extraction.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `text` | `string` | — | Extracted text content. |
| `metadata` | `DocMetadata` | — | Document metadata. |


---

### DocExtractor

Native DOC extractor using OLE/CFB parsing.

This extractor handles Word 97-2003 binary (.doc) files without
requiring LibreOffice, providing ~50x faster extraction.

#### Methods

##### default()

**Signature:**

```php
public static function default(): DocExtractor
```

##### name()

**Signature:**

```php
public function name(): string
```

##### version()

**Signature:**

```php
public function version(): string
```

##### initialize()

**Signature:**

```php
public function initialize(): void
```

##### shutdown()

**Signature:**

```php
public function shutdown(): void
```

##### description()

**Signature:**

```php
public function description(): string
```

##### author()

**Signature:**

```php
public function author(): string
```

##### extractBytes()

**Signature:**

```php
public function extractBytes(string $content, string $mimeType, ExtractionConfig $config): InternalDocument
```

##### supportedMimeTypes()

**Signature:**

```php
public function supportedMimeTypes(): array<string>
```

##### priority()

**Signature:**

```php
public function priority(): int
```


---

### DocMetadata

Metadata extracted from DOC files.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `title` | `?string` | `null` | Title |
| `subject` | `?string` | `null` | Subject |
| `author` | `?string` | `null` | Author |
| `lastAuthor` | `?string` | `null` | Last author |
| `created` | `?string` | `null` | Created |
| `modified` | `?string` | `null` | Modified |
| `revisionNumber` | `?string` | `null` | Revision number |


---

### DocOrientationDetector

Detects document page orientation using the PP-LCNet model.

Thread-safe: uses unsafe pointer cast for ONNX session (same pattern as embedding engine).
The model is downloaded from HuggingFace on first use and cached locally.

#### Methods

##### detect()

Detect document page orientation.

Returns the detected orientation (0°, 90°, 180°, 270°) and confidence.
Thread-safe: can be called concurrently from multiple pages.

**Signature:**

```php
public function detect(RgbImage $image): OrientationResult
```


---

### DocProperties

Document properties from `<wp:docPr>`.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `id` | `?string` | `null` | Unique identifier |
| `name` | `?string` | `null` | The name |
| `description` | `?string` | `null` | Human-readable description |


---

### DocbookExtractor

DocBook document extractor.

Supports both DocBook 4.x (no namespace) and 5.x (with namespace) formats.

#### Methods

##### default()

**Signature:**

```php
public static function default(): DocbookExtractor
```

##### name()

**Signature:**

```php
public function name(): string
```

##### version()

**Signature:**

```php
public function version(): string
```

##### initialize()

**Signature:**

```php
public function initialize(): void
```

##### shutdown()

**Signature:**

```php
public function shutdown(): void
```

##### extractBytes()

**Signature:**

```php
public function extractBytes(string $content, string $mimeType, ExtractionConfig $config): InternalDocument
```

##### supportedMimeTypes()

**Signature:**

```php
public function supportedMimeTypes(): array<string>
```

##### priority()

**Signature:**

```php
public function priority(): int
```


---

### Document

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `paragraphs` | `array<Paragraph>` | `[]` | Paragraphs |
| `tables` | `array<Table>` | `[]` | Tables extracted from the document |
| `headers` | `array<HeaderFooter>` | `[]` | Headers |
| `footers` | `array<HeaderFooter>` | `[]` | Footers |
| `footnotes` | `array<Note>` | `[]` | Footnotes |
| `endnotes` | `array<Note>` | `[]` | Endnotes |
| `numberingDefs` | `AHashMap` | `null` | Numbering defs (a hash map) |
| `elements` | `array<DocumentElement>` | `[]` | Document elements in their original order. |
| `styleCatalog` | `?StyleCatalog` | `null` | Parsed style catalog from `word/styles.xml`, if available. |
| `theme` | `?Theme` | `null` | Parsed theme from `word/theme/theme1.xml`, if available. |
| `sections` | `array<SectionProperties>` | `[]` | Section properties parsed from `w:sectPr` elements. |
| `drawings` | `array<Drawing>` | `[]` | Drawing objects parsed from `w:drawing` elements. |
| `imageRelationships` | `AHashMap` | `null` | Image relationships (rId → target path) for image extraction. |

#### Methods

##### resolveHeadingLevel()

Resolve heading level for a paragraph style using the StyleCatalog.

Walks the style inheritance chain to find `outline_level`.
Falls back to string-matching on style name/ID if no StyleCatalog is available.
Returns 1-6 (markdown heading levels).

**Signature:**

```php
public function resolveHeadingLevel(string $styleId): ?int
```

##### extractText()

**Signature:**

```php
public function extractText(): string
```

##### toMarkdown()

Render the document as markdown.

When `inject_placeholders` is `true`, drawings that reference an image
emit `![alt](image)` placeholders. When `false` they are silently
skipped, which is useful when the caller only wants text.

**Signature:**

```php
public function toMarkdown(bool $injectPlaceholders): string
```

##### toPlainText()

Render the document as plain text (no markdown formatting).

**Signature:**

```php
public function toPlainText(): string
```


---

### DocumentNode

A single node in the document tree.

Each node has deterministic `id`, typed `content`, optional `parent`/`children`
for tree structure, and metadata like page number, bounding box, and content layer.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `id` | `NodeId` | — | Deterministic identifier (hash of content + position). |
| `content` | `NodeContent` | — | Node content — tagged enum, type-specific data only. |
| `parent` | `?int` | `null` | Parent node index (`None` = root-level node). |
| `children` | `array<int>` | — | Child node indices in reading order. |
| `contentLayer` | `ContentLayer` | — | Content layer classification. |
| `page` | `?int` | `null` | Page number where this node starts (1-indexed). |
| `pageEnd` | `?int` | `null` | Page number where this node ends (for multi-page tables/sections). |
| `bbox` | `?BoundingBox` | `null` | Bounding box in document coordinates. |
| `annotations` | `array<TextAnnotation>` | — | Inline annotations (formatting, links) on this node's text content. Only meaningful for text-carrying nodes; empty for containers. |
| `attributes` | `?array<string, string>` | `null` | Format-specific key-value attributes. Extensible bag for data that doesn't warrant a typed field: CSS classes, LaTeX environment names, Excel cell formulas, slide layout names, etc. |


---

### DocumentRelationship

A resolved relationship between two nodes in the document tree.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `source` | `int` | — | Source node index (the referencing node). |
| `target` | `int` | — | Target node index (the referenced node). |
| `kind` | `RelationshipKind` | — | Semantic kind of the relationship. |


---

### DocumentStructure

Top-level structured document representation.

A flat array of nodes with index-based parent/child references forming a tree.
Root-level nodes have `parent: None`. Use `body_roots()` and `furniture_roots()`
to iterate over top-level content by layer.

# Validation

Call `validate()` after construction to verify all node indices are in bounds
and parent-child relationships are bidirectionally consistent.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `nodes` | `array<DocumentNode>` | `[]` | All nodes in document/reading order. |
| `sourceFormat` | `?string` | `null` | Origin format identifier (e.g. "docx", "pptx", "html", "pdf"). Allows renderers to apply format-aware heuristics when converting the document tree to output formats. |
| `relationships` | `array<DocumentRelationship>` | `[]` | Resolved relationships between nodes (footnote refs, citations, anchor links, etc.). Populated during derivation from the internal document representation. Empty when no relationships are detected. |

#### Methods

##### withCapacity()

Create a `DocumentStructure` with pre-allocated capacity.

**Signature:**

```php
public static function withCapacity(int $capacity): DocumentStructure
```

##### pushNode()

Push a node and return its `NodeIndex`.

**Signature:**

```php
public function pushNode(DocumentNode $node): int
```

##### addChild()

Add a child to an existing parent node.

Updates both the parent's `children` list and the child's `parent` field.

**Panics:**

Panics if either index is out of bounds.

**Signature:**

```php
public function addChild(int $parent, int $child): void
```

##### validate()

Validate all node indices are in bounds and parent-child relationships
are bidirectionally consistent.

**Errors:**

Returns a descriptive error string if validation fails.

**Signature:**

```php
public function validate(): void
```

##### bodyRoots()

Iterate over root-level body nodes (content_layer == Body, parent == None).

**Signature:**

```php
public function bodyRoots(): Iterator
```

##### furnitureRoots()

Iterate over root-level furniture nodes (non-Body content_layer, parent == None).

**Signature:**

```php
public function furnitureRoots(): Iterator
```

##### get()

Get a node by index.

**Signature:**

```php
public function get(int $index): ?DocumentNode
```

##### len()

Get the total number of nodes.

**Signature:**

```php
public function len(): int
```

##### isEmpty()

Check if the document structure is empty.

**Signature:**

```php
public function isEmpty(): bool
```

##### default()

**Signature:**

```php
public static function default(): DocumentStructure
```


---

### DocumentStructureBuilder

Builder for constructing `DocumentStructure` trees with automatic
heading-driven section nesting.

The builder maintains an internal section stack: when you push a heading,
it automatically creates a `Group` container and nests subsequent content
under it. Higher-level headings pop deeper sections off the stack.

#### Methods

##### withCapacity()

Create a builder with pre-allocated node capacity.

**Signature:**

```php
public static function withCapacity(int $capacity): DocumentStructureBuilder
```

##### sourceFormat()

Set the source format identifier (e.g. "docx", "html", "pptx").

**Signature:**

```php
public function sourceFormat(string $format): DocumentStructureBuilder
```

##### build()

Consume the builder and return the constructed `DocumentStructure`.

**Signature:**

```php
public function build(): DocumentStructure
```

##### pushHeading()

Push a heading, creating a `Group` container with automatic section nesting.

Headings at the same or deeper level pop existing sections. Content
pushed after this heading will be nested under its `Group` node.

Returns the `NodeIndex` of the `Group` node (not the heading child).

**Signature:**

```php
public function pushHeading(int $level, string $text, int $page, BoundingBox $bbox): int
```

##### pushParagraph()

Push a paragraph node. Nested under current section if one exists.

**Signature:**

```php
public function pushParagraph(string $text, array<TextAnnotation> $annotations, int $page, BoundingBox $bbox): int
```

##### pushList()

Push a list container. Returns the `NodeIndex` to use with `push_list_item`.

**Signature:**

```php
public function pushList(bool $ordered, int $page): int
```

##### pushListItem()

Push a list item as a child of the given list node.

**Signature:**

```php
public function pushListItem(int $list, string $text, int $page): int
```

##### pushTable()

Push a table node with a structured grid.

**Signature:**

```php
public function pushTable(TableGrid $grid, int $page, BoundingBox $bbox): int
```

##### pushTableFromCells()

Push a table from a simple cell grid (`Vec<Vec<String>>`).

Assumes the first row is the header row.

**Signature:**

```php
public function pushTableFromCells(array<array<string>> $cells, int $page): int
```

##### pushCode()

Push a code block.

**Signature:**

```php
public function pushCode(string $text, string $language, int $page): int
```

##### pushFormula()

Push a math formula node.

**Signature:**

```php
public function pushFormula(string $text, int $page): int
```

##### pushImage()

Push an image reference node.

**Signature:**

```php
public function pushImage(string $description, int $imageIndex, int $page, BoundingBox $bbox): int
```

##### pushImageWithSrc()

Push an image node with source URL.

**Signature:**

```php
public function pushImageWithSrc(string $description, string $src, int $imageIndex, int $page, BoundingBox $bbox): int
```

##### pushQuote()

Push a block quote container and enter it.

Subsequent body nodes will be parented under this quote until
`exit_container` is called.

**Signature:**

```php
public function pushQuote(int $page): int
```

##### pushFootnote()

Push a footnote node.

**Signature:**

```php
public function pushFootnote(string $text, int $page): int
```

##### pushPageBreak()

Push a page break marker (always root-level, never nested under sections).

**Signature:**

```php
public function pushPageBreak(int $page): int
```

##### pushSlide()

Push a slide container (PPTX) and enter it.

Clears the section stack and container stack so the slide starts
fresh. Subsequent body nodes will be parented under this slide
until `exit_container` is called or a new
slide is pushed.

**Signature:**

```php
public function pushSlide(int $number, string $title): int
```

##### pushDefinitionList()

Push a definition list container. Use `push_definition_item` for entries.

**Signature:**

```php
public function pushDefinitionList(int $page): int
```

##### pushDefinitionItem()

Push a definition item as a child of the given definition list.

**Signature:**

```php
public function pushDefinitionItem(int $list, string $term, string $definition, int $page): int
```

##### pushCitation()

Push a citation / bibliographic reference.

**Signature:**

```php
public function pushCitation(string $key, string $text, int $page): int
```

##### pushAdmonition()

Push an admonition container (note, warning, tip, etc.) and enter it.

Subsequent body nodes will be parented under this admonition until
`exit_container` is called.

**Signature:**

```php
public function pushAdmonition(string $kind, string $title, int $page): int
```

##### pushRawBlock()

Push a raw block preserved verbatim from the source format.

**Signature:**

```php
public function pushRawBlock(string $format, string $content, int $page): int
```

##### pushMetadataBlock()

Push a metadata block (email headers, frontmatter key-value pairs).

**Signature:**

```php
public function pushMetadataBlock(array<StringString> $entries, int $page): int
```

##### pushHeader()

Push a header paragraph (running page header).

**Signature:**

```php
public function pushHeader(string $text, int $page): int
```

##### pushFooter()

Push a footer paragraph (running page footer).

**Signature:**

```php
public function pushFooter(string $text, int $page): int
```

##### setAttributes()

Set format-specific attributes on an existing node.

**Signature:**

```php
public function setAttributes(int $index, AHashMap $attrs): void
```

##### addChild()

Add a child node to an existing parent (for container nodes like Quote, Slide, Admonition).

**Signature:**

```php
public function addChild(int $parent, int $child): void
```

##### pushRaw()

Push a raw `NodeContent` with full control over content layer and annotations.
Nests under current section unless the content type is a root-level type.

**Signature:**

```php
public function pushRaw(NodeContent $content, int $page, BoundingBox $bbox, ContentLayer $layer, array<TextAnnotation> $annotations): int
```

##### clearSections()

Reset the section stack (e.g. when starting a new page).

**Signature:**

```php
public function clearSections(): void
```

##### enterContainer()

Manually push a node onto the container stack.

Subsequent body nodes will be parented under this container
until `exit_container` is called.

**Signature:**

```php
public function enterContainer(int $container): void
```

##### exitContainer()

Pop the most recent container from the container stack.

Body nodes will resume parenting under the next container on the
stack, or under the section stack if the container stack is empty.

**Signature:**

```php
public function exitContainer(): void
```

##### default()

**Signature:**

```php
public static function default(): DocumentStructureBuilder
```


---

### DocxAppProperties

Application properties from docProps/app.xml for DOCX

Contains Word-specific document statistics and metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `application` | `?string` | `null` | Application name (e.g., "Microsoft Office Word") |
| `appVersion` | `?string` | `null` | Application version |
| `template` | `?string` | `null` | Template filename |
| `totalTime` | `?int` | `null` | Total editing time in minutes |
| `pages` | `?int` | `null` | Number of pages |
| `words` | `?int` | `null` | Number of words |
| `characters` | `?int` | `null` | Number of characters (excluding spaces) |
| `charactersWithSpaces` | `?int` | `null` | Number of characters (including spaces) |
| `lines` | `?int` | `null` | Number of lines |
| `paragraphs` | `?int` | `null` | Number of paragraphs |
| `company` | `?string` | `null` | Company name |
| `docSecurity` | `?int` | `null` | Document security level |
| `scaleCrop` | `?bool` | `null` | Scale crop flag |
| `linksUpToDate` | `?bool` | `null` | Links up to date flag |
| `sharedDoc` | `?bool` | `null` | Shared document flag |
| `hyperlinksChanged` | `?bool` | `null` | Hyperlinks changed flag |


---

### DocxExtractor

High-performance DOCX extractor.

This extractor provides:
- Fast text extraction via streaming XML parsing
- Comprehensive metadata extraction (core.xml, app.xml, custom.xml)

#### Methods

##### default()

**Signature:**

```php
public static function default(): DocxExtractor
```

##### name()

**Signature:**

```php
public function name(): string
```

##### version()

**Signature:**

```php
public function version(): string
```

##### initialize()

**Signature:**

```php
public function initialize(): void
```

##### shutdown()

**Signature:**

```php
public function shutdown(): void
```

##### description()

**Signature:**

```php
public function description(): string
```

##### author()

**Signature:**

```php
public function author(): string
```

##### extractBytes()

**Signature:**

```php
public function extractBytes(string $content, string $mimeType, ExtractionConfig $config): InternalDocument
```

##### supportedMimeTypes()

**Signature:**

```php
public function supportedMimeTypes(): array<string>
```

##### priority()

**Signature:**

```php
public function priority(): int
```


---

### DocxMetadata

Word document metadata.

Extracted from DOCX files using shared Office Open XML metadata extraction.
Integrates with `office_metadata` module for core/app/custom properties.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `coreProperties` | `?CoreProperties` | `null` | Core properties from docProps/core.xml (Dublin Core metadata) Contains title, creator, subject, keywords, dates, etc. Shared format across DOCX/PPTX/XLSX documents. |
| `appProperties` | `?DocxAppProperties` | `null` | Application properties from docProps/app.xml (Word-specific statistics) Contains word count, page count, paragraph count, editing time, etc. DOCX-specific variant of Office application properties. |
| `customProperties` | `?array<string, mixed>` | `null` | Custom properties from docProps/custom.xml (user-defined properties) Contains key-value pairs defined by users or applications. Values can be strings, numbers, booleans, or dates. |


---

### Drawing

A drawing object extracted from `<w:drawing>`.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `drawingType` | `DrawingType` | — | Drawing type (drawing type) |
| `extent` | `?Extent` | `null` | Extent (extent) |
| `docProperties` | `?DocProperties` | `null` | Doc properties (doc properties) |
| `imageRef` | `?string` | `null` | Image ref |


---

### Element

Semantic element extracted from document.

Represents a logical unit of content with semantic classification,
unique identifier, and metadata for tracking origin and position.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `elementId` | `ElementId` | — | Unique element identifier |
| `elementType` | `ElementType` | — | Semantic type of this element |
| `text` | `string` | — | Text content of the element |
| `metadata` | `ElementMetadata` | — | Metadata about the element |


---

### ElementId

Unique identifier for semantic elements.

Wraps a string identifier that is deterministically generated
from element type, content, and page number.

#### Methods

##### new()

Create a new ElementId from a string.

**Errors:**

Returns error if the string is not valid.

**Signature:**

```php
public static function new(string $hexStr): ElementId
```

##### asRef()

**Signature:**

```php
public function asRef(): string
```

##### fmt()

**Signature:**

```php
public function fmt(Formatter $f): Unknown
```


---

### ElementMetadata

Metadata for a semantic element.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `pageNumber` | `?int` | `null` | Page number (1-indexed) |
| `filename` | `?string` | `null` | Source filename or document name |
| `coordinates` | `?BoundingBox` | `null` | Bounding box coordinates if available |
| `elementIndex` | `?int` | `null` | Position index in the element sequence |
| `additional` | `array<string, string>` | — | Additional custom metadata |


---

### EmailAttachment

Email attachment representation.

Contains metadata and optionally the content of an email attachment.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `name` | `?string` | `null` | Attachment name (from Content-Disposition header) |
| `filename` | `?string` | `null` | Filename of the attachment |
| `mimeType` | `?string` | `null` | MIME type of the attachment |
| `size` | `?int` | `null` | Size in bytes |
| `isImage` | `bool` | — | Whether this attachment is an image |
| `data` | `?string` | `null` | Attachment data (if extracted). Uses `bytes.Bytes` for cheap cloning of large buffers. |


---

### EmailConfig

Configuration for email extraction.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `msgFallbackCodepage` | `?int` | `null` | Windows codepage number to use when an MSG file contains no codepage property. Defaults to `None`, which falls back to windows-1252. If an unrecognized or invalid codepage number is supplied (including 0), the behavior silently falls back to windows-1252 — the same as when the MSG file itself contains an unrecognized codepage. No error or warning is emitted. Users should verify output when supplying unusual values. Common values: - 1250: Central European (Polish, Czech, Hungarian, etc.) - 1251: Cyrillic (Russian, Ukrainian, Bulgarian, etc.) - 1252: Western European (default) - 1253: Greek - 1254: Turkish - 1255: Hebrew - 1256: Arabic - 932:  Japanese (Shift-JIS) - 936:  Simplified Chinese (GBK) |


---

### EmailExtractionResult

Email extraction result.

Complete representation of an extracted email message (.eml or .msg)
including headers, body content, and attachments.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `subject` | `?string` | `null` | Email subject line |
| `fromEmail` | `?string` | `null` | Sender email address |
| `toEmails` | `array<string>` | — | Primary recipient email addresses |
| `ccEmails` | `array<string>` | — | CC recipient email addresses |
| `bccEmails` | `array<string>` | — | BCC recipient email addresses |
| `date` | `?string` | `null` | Email date/timestamp |
| `messageId` | `?string` | `null` | Message-ID header value |
| `plainText` | `?string` | `null` | Plain text version of the email body |
| `htmlContent` | `?string` | `null` | HTML version of the email body |
| `cleanedText` | `string` | — | Cleaned/processed text content |
| `attachments` | `array<EmailAttachment>` | — | List of email attachments |
| `metadata` | `array<string, string>` | — | Additional email headers and metadata |


---

### EmailExtractor

Email message extractor.

Supports: .eml, .msg

#### Methods

##### default()

**Signature:**

```php
public static function default(): EmailExtractor
```

##### name()

**Signature:**

```php
public function name(): string
```

##### version()

**Signature:**

```php
public function version(): string
```

##### initialize()

**Signature:**

```php
public function initialize(): void
```

##### shutdown()

**Signature:**

```php
public function shutdown(): void
```

##### extractSync()

**Signature:**

```php
public function extractSync(string $content, string $mimeType, ExtractionConfig $config): InternalDocument
```

##### extractBytes()

**Signature:**

```php
public function extractBytes(string $content, string $mimeType, ExtractionConfig $config): InternalDocument
```

##### supportedMimeTypes()

**Signature:**

```php
public function supportedMimeTypes(): array<string>
```

##### priority()

**Signature:**

```php
public function priority(): int
```

##### asSyncExtractor()

**Signature:**

```php
public function asSyncExtractor(): ?SyncExtractor
```


---

### EmailMetadata

Email metadata extracted from .eml and .msg files.

Includes sender/recipient information, message ID, and attachment list.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `fromEmail` | `?string` | `null` | Sender's email address |
| `fromName` | `?string` | `null` | Sender's display name |
| `toEmails` | `array<string>` | — | Primary recipients |
| `ccEmails` | `array<string>` | — | CC recipients |
| `bccEmails` | `array<string>` | — | BCC recipients |
| `messageId` | `?string` | `null` | Message-ID header value |
| `attachments` | `array<string>` | — | List of attachment filenames |


---

### EmbeddedFile

Embedded file descriptor extracted from the PDF name tree.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `name` | `string` | — | The filename as stored in the PDF name tree. |
| `data` | `string` | — | Raw file bytes from the embedded stream. |
| `mimeType` | `?string` | `null` | MIME type if specified in the filespec, otherwise `None`. |


---

### EmbeddingConfig

Embedding configuration for text chunks.

Configures embedding generation using ONNX models via the vendored embedding engine.
Requires the `embeddings` feature to be enabled.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `model` | `EmbeddingModelType` | `EmbeddingModelType::Preset` | The embedding model to use (defaults to "balanced" preset if not specified) |
| `normalize` | `bool` | `true` | Whether to normalize embedding vectors (recommended for cosine similarity) |
| `batchSize` | `int` | `32` | Batch size for embedding generation |
| `showDownloadProgress` | `bool` | `false` | Show model download progress |
| `cacheDir` | `?string` | `null` | Custom cache directory for model files Defaults to `~/.cache/kreuzberg/embeddings/` if not specified. Allows full customization of model download location. |

#### Methods

##### default()

**Signature:**

```php
public static function default(): EmbeddingConfig
```


---

### EmbeddingEngine

Text embedding model with thread-safe inference.

The `embed()` method takes `&self` instead of `&mut self`, allowing it to
be shared across threads via `Arc<EmbeddingEngine>` without mutex contention.


---

### EmbeddingPreset

Preset configurations for common RAG use cases.

Each preset combines chunk size, overlap, and embedding model
to provide an optimized configuration for specific scenarios.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `name` | `string` | — | The name |
| `chunkSize` | `int` | — | Chunk size |
| `overlap` | `int` | — | Overlap |
| `modelRepo` | `string` | — | HuggingFace repository name for the model. |
| `pooling` | `string` | — | Pooling strategy: "cls" or "mean". |
| `modelFile` | `string` | — | Path to the ONNX model file within the repo. |
| `dimensions` | `int` | — | Dimensions |
| `description` | `string` | — | Human-readable description |


---

### EntityValidator

Helper struct for validating entity/string length.

#### Methods

##### validate()

Validate entity length.

**Returns:**
* `Ok(())` if length is within limits
* `Err(SecurityError)` if length exceeds limit

**Signature:**

```php
public function validate(string $content): void
```


---

### EpubExtractor

EPUB format extractor using permissive-licensed dependencies.

Extracts content and metadata from EPUB files (both EPUB2 and EPUB3)
using native Rust parsing without GPL-licensed dependencies.

#### Methods

##### default()

**Signature:**

```php
public static function default(): EpubExtractor
```

##### name()

**Signature:**

```php
public function name(): string
```

##### version()

**Signature:**

```php
public function version(): string
```

##### initialize()

**Signature:**

```php
public function initialize(): void
```

##### shutdown()

**Signature:**

```php
public function shutdown(): void
```

##### description()

**Signature:**

```php
public function description(): string
```

##### author()

**Signature:**

```php
public function author(): string
```

##### extractBytes()

**Signature:**

```php
public function extractBytes(string $content, string $mimeType, ExtractionConfig $config): InternalDocument
```

##### supportedMimeTypes()

**Signature:**

```php
public function supportedMimeTypes(): array<string>
```

##### priority()

**Signature:**

```php
public function priority(): int
```


---

### EpubMetadata

EPUB metadata (Dublin Core extensions).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `coverage` | `?string` | `null` | Coverage |
| `dcFormat` | `?string` | `null` | Dc format |
| `relation` | `?string` | `null` | Relation |
| `source` | `?string` | `null` | Source |
| `dcType` | `?string` | `null` | Dc type |
| `coverImage` | `?string` | `null` | Cover image |


---

### ErrorMetadata

Error metadata (for batch operations).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `errorType` | `string` | — | Error type |
| `message` | `string` | — | Message |


---

### ExcelExtractor

Excel spreadsheet extractor using calamine.

Supports: .xlsx, .xlsm, .xlam, .xltm, .xls, .xla, .xlsb, .ods

# Limitations

- **Hyperlinks**: calamine (v0.34) does not expose cell hyperlink data in its
  public API. Excel files may contain hyperlinks via the `HYPERLINK()` formula
  or via the relationships XML, but neither is accessible through the crate.
  This would require either a calamine upstream change or manual OOXML parsing.

#### Methods

##### default()

**Signature:**

```php
public static function default(): ExcelExtractor
```

##### name()

**Signature:**

```php
public function name(): string
```

##### version()

**Signature:**

```php
public function version(): string
```

##### initialize()

**Signature:**

```php
public function initialize(): void
```

##### shutdown()

**Signature:**

```php
public function shutdown(): void
```

##### extractSync()

**Signature:**

```php
public function extractSync(string $content, string $mimeType, ExtractionConfig $config): InternalDocument
```

##### extractBytes()

**Signature:**

```php
public function extractBytes(string $content, string $mimeType, ExtractionConfig $config): InternalDocument
```

##### extractFile()

**Signature:**

```php
public function extractFile(string $path, string $mimeType, ExtractionConfig $config): InternalDocument
```

##### supportedMimeTypes()

**Signature:**

```php
public function supportedMimeTypes(): array<string>
```

##### priority()

**Signature:**

```php
public function priority(): int
```

##### asSyncExtractor()

**Signature:**

```php
public function asSyncExtractor(): ?SyncExtractor
```


---

### ExcelMetadata

Excel/spreadsheet metadata.

Contains information about sheets in Excel, OpenDocument Calc, and other
spreadsheet formats (.xlsx, .xls, .ods, etc.).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `sheetCount` | `int` | — | Total number of sheets in the workbook |
| `sheetNames` | `array<string>` | — | Names of all sheets in order |


---

### ExcelSheet

Single Excel worksheet.

Represents one sheet from an Excel workbook with its content
converted to Markdown format and dimensional statistics.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `name` | `string` | — | Sheet name as it appears in Excel |
| `markdown` | `string` | — | Sheet content converted to Markdown tables |
| `rowCount` | `int` | — | Number of rows |
| `colCount` | `int` | — | Number of columns |
| `cellCount` | `int` | — | Total number of non-empty cells |
| `tableCells` | `?array<array<string>>` | `null` | Pre-extracted table cells (2D vector of cell values) Populated during markdown generation to avoid re-parsing markdown. None for empty sheets. |


---

### ExcelWorkbook

Excel workbook representation.

Contains all sheets from an Excel file (.xlsx, .xls, etc.) with
extracted content and metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `sheets` | `array<ExcelSheet>` | — | All sheets in the workbook |
| `metadata` | `array<string, string>` | — | Workbook-level metadata (author, creation date, etc.) |


---

### Extent

Size in EMUs (English Metric Units, 1 inch = 914400 EMU).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `cx` | `int` | `null` | Cx |
| `cy` | `int` | `null` | Cy |

#### Methods

##### widthInches()

Convert width to inches.

**Signature:**

```php
public function widthInches(): float
```

##### heightInches()

Convert height to inches.

**Signature:**

```php
public function heightInches(): float
```


---

### ExtractedImage

Extracted image from a document.

Contains raw image data, metadata, and optional nested OCR results.
Raw bytes allow cross-language compatibility - users can convert to
PIL.Image (Python), Sharp (Node.js), or other formats as needed.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `data` | `string` | — | Raw image data (PNG, JPEG, WebP, etc. bytes). Uses `bytes.Bytes` for cheap cloning of large buffers. |
| `format` | `Str` | — | Image format (e.g., "jpeg", "png", "webp") Uses Cow<'static, str> to avoid allocation for static literals. |
| `imageIndex` | `int` | — | Zero-indexed position of this image in the document/page |
| `pageNumber` | `?int` | `null` | Page/slide number where image was found (1-indexed) |
| `width` | `?int` | `null` | Image width in pixels |
| `height` | `?int` | `null` | Image height in pixels |
| `colorspace` | `?string` | `null` | Colorspace information (e.g., "RGB", "CMYK", "Gray") |
| `bitsPerComponent` | `?int` | `null` | Bits per color component (e.g., 8, 16) |
| `isMask` | `bool` | — | Whether this image is a mask image |
| `description` | `?string` | `null` | Optional description of the image |
| `ocrResult` | `?ExtractionResult` | `null` | Nested OCR extraction result (if image was OCRed) When OCR is performed on this image, the result is embedded here rather than in a separate collection, making the relationship explicit. |
| `boundingBox` | `?BoundingBox` | `null` | Bounding box of the image on the page (PDF coordinates: x0=left, y0=bottom, x1=right, y1=top). Only populated for PDF-extracted images when position data is available from pdfium. |
| `sourcePath` | `?string` | `null` | Original source path of the image within the document archive (e.g., "media/image1.png" in DOCX). Used for rendering image references when the binary data is not extracted. |


---

### ExtractionConfig

Main extraction configuration.

This struct contains all configuration options for the extraction process.
It can be loaded from TOML, YAML, or JSON files, or created programmatically.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `useCache` | `bool` | `true` | Enable caching of extraction results |
| `enableQualityProcessing` | `bool` | `true` | Enable quality post-processing |
| `ocr` | `?OcrConfig` | `null` | OCR configuration (None = OCR disabled) |
| `forceOcr` | `bool` | `false` | Force OCR even for searchable PDFs |
| `forceOcrPages` | `?array<int>` | `[]` | Force OCR on specific pages only (1-indexed page numbers, must be >= 1). When set, only the listed pages are OCR'd regardless of text layer quality. Unlisted pages use native text extraction. Ignored when `force_ocr` is `True`. Only applies to PDF documents. Duplicates are automatically deduplicated. An `ocr` config is recommended for backend/language selection; defaults are used if absent. |
| `disableOcr` | `bool` | `false` | Disable OCR entirely, even for images. When `True`, OCR is skipped for all document types. Images return metadata only (dimensions, format, EXIF) without text extraction. PDFs use only native text extraction without OCR fallback. Cannot be `True` simultaneously with `force_ocr`. *Added in v4.7.0.* |
| `chunking` | `?ChunkingConfig` | `null` | Text chunking configuration (None = chunking disabled) |
| `contentFilter` | `?ContentFilterConfig` | `null` | Content filtering configuration (None = use extractor defaults). Controls whether document "furniture" (headers, footers, watermarks, repeating text) is included in or stripped from extraction results. See `ContentFilterConfig` for per-field documentation. |
| `images` | `?ImageExtractionConfig` | `null` | Image extraction configuration (None = no image extraction) |
| `pdfOptions` | `?PdfConfig` | `null` | PDF-specific options (None = use defaults) |
| `tokenReduction` | `?TokenReductionConfig` | `null` | Token reduction configuration (None = no token reduction) |
| `languageDetection` | `?LanguageDetectionConfig` | `null` | Language detection configuration (None = no language detection) |
| `pages` | `?PageConfig` | `null` | Page extraction configuration (None = no page tracking) |
| `postprocessor` | `?PostProcessorConfig` | `null` | Post-processor configuration (None = use defaults) |
| `htmlOptions` | `?ConversionOptions` | `null` | HTML to Markdown conversion options (None = use defaults) Configure how HTML documents are converted to Markdown, including heading styles, list formatting, code block styles, and preprocessing options. |
| `htmlOutput` | `?HtmlOutputConfig` | `null` | Styled HTML output configuration. When set alongside `output_format = OutputFormat.Html`, the extraction pipeline uses `StyledHtmlRenderer` which emits stable `kb-*` CSS class hooks on every structural element and optionally embeds theme CSS or user-supplied CSS in a `<style>` block. When `None`, the existing plain comrak-based HTML renderer is used. |
| `extractionTimeoutSecs` | `?int` | `null` | Default per-file timeout in seconds for batch extraction. When set, each file in a batch will be canceled after this duration unless overridden by `FileExtractionConfig.timeout_secs`. `None` means no timeout (unbounded extraction time). |
| `maxConcurrentExtractions` | `?int` | `null` | Maximum concurrent extractions in batch operations (None = (num_cpus × 1.5).ceil()). Limits parallelism to prevent resource exhaustion when processing large batches. Defaults to (num_cpus × 1.5).ceil() when not set. |
| `resultFormat` | `OutputFormat` | `OutputFormat::Plain` | Result structure format Controls whether results are returned in unified format (default) with all content in the `content` field, or element-based format with semantic elements (for Unstructured-compatible output). |
| `securityLimits` | `?SecurityLimits` | `null` | Security limits for archive extraction. Controls maximum archive size, compression ratio, file count, and other security thresholds to prevent decompression bomb attacks. When `None`, default limits are used (500MB archive, 100:1 ratio, 10K files). |
| `outputFormat` | `OutputFormat` | `OutputFormat::Plain` | Content text format (default: Plain). Controls the format of the extracted content: - `Plain`: Raw extracted text (default) - `Markdown`: Markdown formatted output - `Djot`: Djot markup format (requires djot feature) - `Html`: HTML formatted output When set to a structured format, extraction results will include formatted output. The `formatted_content` field may be populated when format conversion is applied. |
| `layout` | `?LayoutDetectionConfig` | `null` | Layout detection configuration (None = layout detection disabled). When set, PDF pages and images are analyzed for document structure (headings, code, formulas, tables, figures, etc.) using RT-DETR models via ONNX Runtime. For PDFs, layout hints override paragraph classification in the markdown pipeline. For images, per-region OCR is performed with markdown formatting based on detected layout classes. Requires the `layout-detection` feature. |
| `includeDocumentStructure` | `bool` | `false` | Enable structured document tree output. When true, populates the `document` field on `ExtractionResult` with a hierarchical `DocumentStructure` containing heading-driven section nesting, table grids, content layer classification, and inline annotations. Independent of `result_format` — can be combined with Unified or ElementBased. |
| `acceleration` | `?AccelerationConfig` | `null` | Hardware acceleration configuration for ONNX Runtime models. Controls execution provider selection for layout detection and embedding models. When `None`, uses platform defaults (CoreML on macOS, CUDA on Linux, CPU on Windows). |
| `cacheNamespace` | `?string` | `null` | Cache namespace for tenant isolation. When set, cache entries are stored under `{cache_dir}/{namespace}/`. Must be alphanumeric, hyphens, or underscores only (max 64 chars). Different namespaces have isolated cache spaces on the same filesystem. |
| `cacheTtlSecs` | `?int` | `null` | Per-request cache TTL in seconds. Overrides the global `max_age_days` for this specific extraction. When `0`, caching is completely skipped (no read or write). When `None`, the global TTL applies. |
| `email` | `?EmailConfig` | `null` | Email extraction configuration (None = use defaults). Currently supports configuring the fallback codepage for MSG files that do not specify one. See `crate.core.config.EmailConfig` for details. |
| `concurrency` | `?ConcurrencyConfig` | `null` | Concurrency limits for constrained environments (None = use defaults). Controls Rayon thread pool size, ONNX Runtime intra-op threads, and (when `max_concurrent_extractions` is unset) the batch concurrency semaphore. See `crate.core.config.ConcurrencyConfig` for details. |
| `maxArchiveDepth` | `int` | `null` | Maximum recursion depth for archive extraction (default: 3). Set to 0 to disable recursive extraction (legacy behavior). |
| `treeSitter` | `?TreeSitterConfig` | `null` | Tree-sitter language pack configuration (None = tree-sitter disabled). When set, enables code file extraction using tree-sitter parsers. Controls grammar download behavior and code analysis options. |
| `structuredExtraction` | `?StructuredExtractionConfig` | `null` | Structured extraction via LLM (None = disabled). When set, the extracted document content is sent to an LLM with the provided JSON schema. The structured response is stored in `ExtractionResult.structured_output`. |

#### Methods

##### default()

**Signature:**

```php
public static function default(): ExtractionConfig
```

##### withFileOverrides()

Create a new `ExtractionConfig` by applying per-file overrides from a
`FileExtractionConfig`. Fields that are `Some` in the override replace the
corresponding field in `self`; `null` fields keep the original value.

Batch-level fields (`max_concurrent_extractions`, `use_cache`, `acceleration`,
`security_limits`) are never affected by overrides.

**Signature:**

```php
public function withFileOverrides(FileExtractionConfig $overrides): ExtractionConfig
```

##### normalized()

Normalize configuration for implicit requirements.

Currently handles:
- Auto-enabling `extract_pages` when `result_format` is `ElementBased`, because
  the element transformation requires per-page data to assign correct page numbers.
  Without this, all elements would incorrectly get `page_number=1`.
- Auto-enabling `extract_pages` when chunking is configured, because the chunker
  needs page boundaries to assign correct page numbers to chunks.

**Signature:**

```php
public function normalized(): ExtractionConfig
```

##### validate()

Validate the configuration, returning an error if any settings are invalid.

Checks:
- OCR backend name is supported (catches typos early)
- VLM backend config is present when backend is "vlm"
- Pipeline stage backends and VLM configs are valid
- Structured extraction schema and LLM model are non-empty

**Signature:**

```php
public function validate(): void
```

##### needsImageProcessing()

Check if image processing is needed by examining OCR and image extraction settings.

Returns `true` if either OCR is enabled or image extraction is configured,
indicating that image decompression and processing should occur.
Returns `false` if both are disabled, allowing optimization to skip unnecessary
image decompression for text-only extraction workflows.

# Optimization Impact
For text-only extractions (no OCR, no image extraction), skipping image
decompression can improve CPU utilization by 5-10% by avoiding wasteful
image I/O and processing when results won't be used.

**Signature:**

```php
public function needsImageProcessing(): bool
```


---

### ExtractionMetrics

Collection of all kreuzberg metric instruments.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `extractionTotal` | `Counter` | — | Total extractions (attributes: mime_type, extractor, status). |
| `cacheHits` | `Counter` | — | Cache hits. |
| `cacheMisses` | `Counter` | — | Cache misses. |
| `batchTotal` | `Counter` | — | Total batch requests (attributes: status). |
| `extractionDurationMs` | `Histogram` | — | Extraction wall-clock duration in milliseconds (attributes: mime_type, extractor). |
| `extractionInputBytes` | `Histogram` | — | Input document size in bytes (attributes: mime_type). |
| `extractionOutputBytes` | `Histogram` | — | Output content size in bytes (attributes: mime_type). |
| `pipelineDurationMs` | `Histogram` | — | Pipeline stage duration in milliseconds (attributes: stage). |
| `ocrDurationMs` | `Histogram` | — | OCR duration in milliseconds (attributes: backend, language). |
| `batchDurationMs` | `Histogram` | — | Batch total duration in milliseconds. |
| `concurrentExtractions` | `UpDownCounter` | — | Currently in-flight extractions. |


---

### ExtractionRequest

A request to extract content from a single document.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `source` | `ExtractionSource` | — | Where to read the document from. |
| `config` | `ExtractionConfig` | — | Base extraction configuration. |
| `fileOverrides` | `?FileExtractionConfig` | `null` | Optional per-file overrides (merged on top of `config`). |

#### Methods

##### file()

Create a file-based extraction request.

**Signature:**

```php
public static function file(string $path, ExtractionConfig $config): ExtractionRequest
```

##### fileWithMime()

Create a file-based extraction request with a MIME type hint.

**Signature:**

```php
public static function fileWithMime(string $path, string $mimeHint, ExtractionConfig $config): ExtractionRequest
```

##### bytes()

Create a bytes-based extraction request.

**Signature:**

```php
public static function bytes(string $data, string $mimeType, ExtractionConfig $config): ExtractionRequest
```

##### withOverrides()

Set per-file overrides on this request.

**Signature:**

```php
public function withOverrides(FileExtractionConfig $overrides): ExtractionRequest
```


---

### ExtractionResult

General extraction result used by the core extraction API.

This is the main result type returned by all extraction functions.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `content` | `string` | `null` | The extracted text content |
| `mimeType` | `Str` | `null` | The detected MIME type |
| `metadata` | `Metadata` | `null` | Document metadata |
| `tables` | `array<Table>` | `[]` | Tables extracted from the document |
| `detectedLanguages` | `?array<string>` | `[]` | Detected languages |
| `chunks` | `?array<Chunk>` | `[]` | Text chunks when chunking is enabled. When chunking configuration is provided, the content is split into overlapping chunks for efficient processing. Each chunk contains the text, optional embeddings (if enabled), and metadata about its position. |
| `images` | `?array<ExtractedImage>` | `[]` | Extracted images from the document. When image extraction is enabled via `ImageExtractionConfig`, this field contains all images found in the document with their raw data and metadata. Each image may optionally contain a nested `ocr_result` if OCR was performed. |
| `pages` | `?array<PageContent>` | `[]` | Per-page content when page extraction is enabled. When page extraction is configured, the document is split into per-page content with tables and images mapped to their respective pages. |
| `elements` | `?array<Element>` | `[]` | Semantic elements when element-based result format is enabled. When result_format is set to ElementBased, this field contains semantic elements with type classification, unique identifiers, and metadata for Unstructured-compatible element-based processing. |
| `djotContent` | `?DjotContent` | `null` | Rich Djot content structure (when extracting Djot documents). When extracting Djot documents with structured extraction enabled, this field contains the full semantic structure including: - Block-level elements with nesting - Inline formatting with attributes - Links, images, footnotes - Math expressions - Complete attribute information The `content` field still contains plain text for backward compatibility. Always `None` for non-Djot documents. |
| `ocrElements` | `?array<OcrElement>` | `[]` | OCR elements with full spatial and confidence metadata. When OCR is performed with element extraction enabled, this field contains the structured representation of detected text including: - Bounding geometry (rectangles or quadrilaterals) - Confidence scores (detection and recognition) - Rotation information - Hierarchical relationships (Tesseract only) This field preserves all metadata that would otherwise be lost when converting to plain text or markdown output formats. Only populated when `OcrElementConfig.include_elements` is true. |
| `document` | `?DocumentStructure` | `null` | Structured document tree (when document structure extraction is enabled). When `include_document_structure` is true in `ExtractionConfig`, this field contains the full hierarchical representation of the document including: - Heading-driven section nesting - Table grids with cell-level metadata - Content layer classification (body, header, footer, footnote) - Inline text annotations (formatting, links) - Bounding boxes and page numbers Independent of `result_format` — can be combined with Unified or ElementBased. |
| `qualityScore` | `?float` | `null` | Document quality score from quality analysis. A value between 0.0 and 1.0 indicating the overall text quality. Previously stored in `metadata.additional["quality_score"]`. |
| `processingWarnings` | `array<ProcessingWarning>` | `[]` | Non-fatal warnings collected during processing pipeline stages. Captures errors from optional pipeline features (embedding, chunking, language detection, output formatting) that don't prevent extraction but may indicate degraded results. Previously stored as individual keys in `metadata.additional`. |
| `annotations` | `?array<PdfAnnotation>` | `[]` | PDF annotations extracted from the document. When annotation extraction is enabled via `PdfConfig.extract_annotations`, this field contains text notes, highlights, links, stamps, and other annotations found in PDF documents. |
| `children` | `?array<ArchiveEntry>` | `[]` | Nested extraction results from archive contents. When extracting archives, each processable file inside produces its own full extraction result. Set to `None` for non-archive formats. Use `max_archive_depth` in config to control recursion depth. |
| `uris` | `?array<Uri>` | `[]` | URIs/links discovered during document extraction. Contains hyperlinks, image references, citations, email addresses, and other URI-like references found in the document. Always extracted when present in the source document. |
| `structuredOutput` | `?mixed` | `null` | Structured extraction output from LLM-based JSON schema extraction. When `structured_extraction` is configured in `ExtractionConfig`, the extracted document content is sent to a VLM with the provided JSON schema. The response is parsed and stored here as a JSON value matching the schema. |
| `codeIntelligence` | `?ProcessResult` | `null` | Code intelligence results from tree-sitter analysis. Populated when extracting source code files with the `tree-sitter` feature. Contains metrics, structural analysis, imports/exports, comments, docstrings, symbols, diagnostics, and optionally chunked code segments. |
| `llmUsage` | `?array<LlmUsage>` | `[]` | LLM token usage and cost data for all LLM calls made during this extraction. Contains one entry per LLM call. Multiple entries are produced when VLM OCR, structured extraction, and/or LLM embeddings all run during the same extraction. `None` when no LLM was used. |
| `formattedContent` | `?string` | `null` | Pre-rendered content in the requested output format. Populated during `derive_extraction_result` before tree derivation consumes element data. `apply_output_format` swaps this into `content` at the end of the pipeline, after post-processors have operated on plain text. |
| `ocrInternalDocument` | `?InternalDocument` | `null` | Structured hOCR document for the OCR+layout pipeline. When tesseract produces hOCR output, the parsed `InternalDocument` carries paragraph structure with bounding boxes and confidence scores. The layout classification step enriches these elements before final rendering. |


---

### ExtractionServiceBuilder

Builder for composing an extraction service with Tower middleware layers.

Layers are applied in the order: Tracing → Metrics → Timeout → ConcurrencyLimit → Service.

#### Methods

##### default()

**Signature:**

```php
public static function default(): ExtractionServiceBuilder
```

##### withTimeout()

Add a per-request timeout.

**Signature:**

```php
public function withTimeout(float $duration): ExtractionServiceBuilder
```

##### withConcurrencyLimit()

Limit concurrent in-flight extractions.

**Signature:**

```php
public function withConcurrencyLimit(int $max): ExtractionServiceBuilder
```

##### withTracing()

Add a tracing span to each extraction request.

**Signature:**

```php
public function withTracing(): ExtractionServiceBuilder
```

##### withMetrics()

Add metrics recording to each extraction request.

Requires the `otel` feature. This is a no-op when `otel` is not enabled.

**Signature:**

```php
public function withMetrics(): ExtractionServiceBuilder
```

##### build()

Build the service stack, returning a type-erased cloneable service.

Layer order (outermost to innermost):
`Tracing → Metrics → Timeout → ConcurrencyLimit → ExtractionService`

**Signature:**

```php
public function build(): BoxCloneService
```


---

### FictionBookExtractor

FictionBook document extractor.

Supports FictionBook 2.0 format with proper section hierarchy and inline formatting.

#### Methods

##### default()

**Signature:**

```php
public static function default(): FictionBookExtractor
```

##### name()

**Signature:**

```php
public function name(): string
```

##### version()

**Signature:**

```php
public function version(): string
```

##### initialize()

**Signature:**

```php
public function initialize(): void
```

##### shutdown()

**Signature:**

```php
public function shutdown(): void
```

##### description()

**Signature:**

```php
public function description(): string
```

##### author()

**Signature:**

```php
public function author(): string
```

##### extractBytes()

**Signature:**

```php
public function extractBytes(string $content, string $mimeType, ExtractionConfig $config): InternalDocument
```

##### supportedMimeTypes()

**Signature:**

```php
public function supportedMimeTypes(): array<string>
```

##### priority()

**Signature:**

```php
public function priority(): int
```


---

### FictionBookMetadata

FictionBook (FB2) metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `genres` | `array<string>` | `[]` | Genres |
| `sequences` | `array<string>` | `[]` | Sequences |
| `annotation` | `?string` | `null` | Annotation |


---

### FileBytes

An owned buffer of file bytes.

On non-WASM platforms this may be backed by a memory-mapped file (zero heap
allocation for the file contents) or by a `Vec<u8>` for small files.
On WASM it is always a `Vec<u8>`.

Implements `Deref<Target = [u8]>` so callers can pass `&FileBytes` as `&[u8]`
without any additional copy.

#### Methods

##### deref()

**Signature:**

```php
public function deref(): string
```

##### asRef()

**Signature:**

```php
public function asRef(): string
```


---

### FileExtractionConfig

Per-file extraction configuration overrides for batch processing.

All fields are `Option<T>` — `null` means "use the batch-level default."
This type is used with `crate::batch_extract_file` and
`crate::batch_extract_bytes` to allow heterogeneous
extraction settings within a single batch.

# Excluded Fields

The following `super::ExtractionConfig` fields are batch-level only and
cannot be overridden per file:
- `max_concurrent_extractions` — controls batch parallelism
- `use_cache` — global caching policy
- `acceleration` — shared ONNX execution provider
- `security_limits` — global archive security policy

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `enableQualityProcessing` | `?bool` | `null` | Override quality post-processing for this file. |
| `ocr` | `?OcrConfig` | `null` | Override OCR configuration for this file (None in the Option = use batch default). |
| `forceOcr` | `?bool` | `null` | Override force OCR for this file. |
| `forceOcrPages` | `?array<int>` | `[]` | Override force OCR pages for this file (1-indexed page numbers). |
| `disableOcr` | `?bool` | `null` | Override disable OCR for this file. |
| `chunking` | `?ChunkingConfig` | `null` | Override chunking configuration for this file. |
| `contentFilter` | `?ContentFilterConfig` | `null` | Override content filtering configuration for this file. |
| `images` | `?ImageExtractionConfig` | `null` | Override image extraction configuration for this file. |
| `pdfOptions` | `?PdfConfig` | `null` | Override PDF options for this file. |
| `tokenReduction` | `?TokenReductionConfig` | `null` | Override token reduction for this file. |
| `languageDetection` | `?LanguageDetectionConfig` | `null` | Override language detection for this file. |
| `pages` | `?PageConfig` | `null` | Override page extraction for this file. |
| `postprocessor` | `?PostProcessorConfig` | `null` | Override post-processor for this file. |
| `htmlOptions` | `?ConversionOptions` | `null` | Override HTML conversion options for this file. |
| `resultFormat` | `?OutputFormat` | `OutputFormat::Plain` | Override result format for this file. |
| `outputFormat` | `?OutputFormat` | `OutputFormat::Plain` | Override output content format for this file. |
| `includeDocumentStructure` | `?bool` | `null` | Override document structure output for this file. |
| `layout` | `?LayoutDetectionConfig` | `null` | Override layout detection for this file. |
| `timeoutSecs` | `?int` | `null` | Override per-file extraction timeout in seconds. When set, the extraction for this file will be canceled after the specified duration. A timed-out file produces an error result without affecting other files in the batch. |
| `treeSitter` | `?TreeSitterConfig` | `null` | Override tree-sitter configuration for this file. |
| `structuredExtraction` | `?StructuredExtractionConfig` | `null` | Override structured extraction configuration for this file. When set, enables LLM-based structured extraction with a JSON schema for this specific file. The extracted content is sent to a VLM/LLM and the response is parsed according to the provided schema. |


---

### FileHeader

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `flags` | `int` | — | Flags |

#### Methods

##### parse()

**Signature:**

```php
public static function parse(string $data): FileHeader
```

##### isCompressed()

Whether section streams are zlib/deflate-compressed.

**Signature:**

```php
public function isCompressed(): bool
```

##### isEncrypted()

Whether the document is password-encrypted.

**Signature:**

```php
public function isEncrypted(): bool
```

##### isDistribute()

Whether the document is a distribution document (text in ViewText/).

**Signature:**

```php
public function isDistribute(): bool
```


---

### FontScheme

Font scheme containing major (heading) and minor (body) fonts.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `name` | `string` | `null` | Font scheme name. |
| `majorLatin` | `?string` | `null` | Major (heading) font - Latin script. |
| `majorEastAsian` | `?string` | `null` | Major (heading) font - East Asian script. |
| `majorComplexScript` | `?string` | `null` | Major (heading) font - Complex script. |
| `minorLatin` | `?string` | `null` | Minor (body) font - Latin script. |
| `minorEastAsian` | `?string` | `null` | Minor (body) font - East Asian script. |
| `minorComplexScript` | `?string` | `null` | Minor (body) font - Complex script. |


---

### Footnote

Footnote in Djot.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `label` | `string` | — | Footnote label |
| `content` | `array<FormattedBlock>` | — | Footnote content blocks |


---

### FormattedBlock

Block-level element in a Djot document.

Represents structural elements like headings, paragraphs, lists, code blocks, etc.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `blockType` | `BlockType` | — | Type of block element |
| `level` | `?int` | `null` | Heading level (1-6) for headings, or nesting level for lists |
| `inlineContent` | `array<InlineElement>` | — | Inline content within the block |
| `attributes` | `?Attributes` | `null` | Element attributes (classes, IDs, key-value pairs) |
| `language` | `?string` | `null` | Language identifier for code blocks |
| `code` | `?string` | `null` | Raw code content for code blocks |
| `children` | `array<FormattedBlock>` | — | Nested blocks for containers (blockquotes, list items, divs) |


---

### GenericCache

#### Methods

##### new()

**Signature:**

```php
public static function new(string $cacheType, string $cacheDir, float $maxAgeDays, float $maxCacheSizeMb, float $minFreeSpaceMb): GenericCache
```

##### get()

**Signature:**

```php
public function get(string $cacheKey, string $sourceFile, string $namespace, int $ttlOverrideSecs): ?string
```

##### getDefault()

Backward-compatible get without namespace/TTL.

**Signature:**

```php
public function getDefault(string $cacheKey, string $sourceFile): ?string
```

##### set()

**Signature:**

```php
public function set(string $cacheKey, string $data, string $sourceFile, string $namespace, int $ttlSecs): void
```

##### setDefault()

Backward-compatible set without namespace/TTL.

**Signature:**

```php
public function setDefault(string $cacheKey, string $data, string $sourceFile): void
```

##### isProcessing()

**Signature:**

```php
public function isProcessing(string $cacheKey): bool
```

##### markProcessing()

**Signature:**

```php
public function markProcessing(string $cacheKey): void
```

##### markComplete()

**Signature:**

```php
public function markComplete(string $cacheKey): void
```

##### clear()

**Signature:**

```php
public function clear(): UsizeF64
```

##### deleteNamespace()

Delete all cache entries under a namespace.

Removes the namespace subdirectory and all its contents.
Returns (files_removed, mb_freed).

**Signature:**

```php
public function deleteNamespace(string $namespace): UsizeF64
```

##### getStats()

**Signature:**

```php
public function getStats(): CacheStats
```

##### getStatsFiltered()

Get cache stats, optionally filtered to a specific namespace.

**Signature:**

```php
public function getStatsFiltered(string $namespace): CacheStats
```

##### cacheDir()

**Signature:**

```php
public function cacheDir(): string
```

##### cacheType()

**Signature:**

```php
public function cacheType(): string
```


---

### GridCell

Individual grid cell with position and span metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `content` | `string` | — | Cell text content. |
| `row` | `int` | — | Zero-indexed row position. |
| `col` | `int` | — | Zero-indexed column position. |
| `rowSpan` | `int` | — | Number of rows this cell spans. |
| `colSpan` | `int` | — | Number of columns this cell spans. |
| `isHeader` | `bool` | — | Whether this is a header cell. |
| `bbox` | `?BoundingBox` | `null` | Bounding box for this cell (if available). |


---

### GzipExtractor

Gzip archive extractor.

Decompresses gzip files and extracts text content from the compressed data.

#### Methods

##### default()

**Signature:**

```php
public static function default(): GzipExtractor
```

##### name()

**Signature:**

```php
public function name(): string
```

##### version()

**Signature:**

```php
public function version(): string
```

##### initialize()

**Signature:**

```php
public function initialize(): void
```

##### shutdown()

**Signature:**

```php
public function shutdown(): void
```

##### description()

**Signature:**

```php
public function description(): string
```

##### author()

**Signature:**

```php
public function author(): string
```

##### extractBytes()

**Signature:**

```php
public function extractBytes(string $content, string $mimeType, ExtractionConfig $config): InternalDocument
```

##### supportedMimeTypes()

**Signature:**

```php
public function supportedMimeTypes(): array<string>
```

##### priority()

**Signature:**

```php
public function priority(): int
```

##### asSyncExtractor()

**Signature:**

```php
public function asSyncExtractor(): ?SyncExtractor
```

##### extractSync()

**Signature:**

```php
public function extractSync(string $content, string $mimeType, ExtractionConfig $config): InternalDocument
```


---

### HeaderFooter

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `paragraphs` | `array<Paragraph>` | `[]` | Paragraphs |
| `tables` | `array<Table>` | `[]` | Tables extracted from the document |
| `headerType` | `HeaderFooterType` | `HeaderFooterType::Default` | Header type (header footer type) |


---

### HeaderMetadata

Header/heading element metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `level` | `int` | — | Header level: 1 (h1) through 6 (h6) |
| `text` | `string` | — | Normalized text content of the header |
| `id` | `?string` | `null` | HTML id attribute if present |
| `depth` | `int` | — | Document tree depth at the header element |
| `htmlOffset` | `int` | — | Byte offset in original HTML document |


---

### HeadingContext

Heading context for a chunk within a Markdown document.

Contains the heading hierarchy from document root to this chunk's section.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `headings` | `array<HeadingLevel>` | — | The heading hierarchy from document root to this chunk's section. Index 0 is the outermost (h1), last element is the most specific. |


---

### HeadingLevel

A single heading in the hierarchy.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `level` | `int` | — | Heading depth (1 = h1, 2 = h2, etc.) |
| `text` | `string` | — | The text content of the heading. |


---

### HierarchicalBlock

A text block with hierarchy level assignment.

Represents a block of text with semantic heading information extracted from
font size clustering and hierarchical analysis.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `text` | `string` | — | The text content of this block |
| `fontSize` | `float` | — | The font size of the text in this block |
| `level` | `string` | — | The hierarchy level of this block (H1-H6 or Body) Levels correspond to HTML heading tags: - "h1": Top-level heading - "h2": Secondary heading - "h3": Tertiary heading - "h4": Quaternary heading - "h5": Quinary heading - "h6": Senary heading - "body": Body text (no heading level) |
| `bbox` | `?F32F32F32F32` | `null` | Bounding box information for the block Contains coordinates as (left, top, right, bottom) in PDF units. |


---

### HierarchyConfig

Hierarchy extraction configuration for PDF text structure analysis.

Enables extraction of document hierarchy levels (H1-H6) based on font size
clustering and semantic analysis. When enabled, hierarchical blocks are
included in page content.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `enabled` | `bool` | `true` | Enable hierarchy extraction |
| `kClusters` | `int` | `3` | Number of font size clusters to use for hierarchy levels (1-7) Default: 6, which provides H1-H6 heading levels with body text. Larger values create more fine-grained hierarchy levels. |
| `includeBbox` | `bool` | `true` | Include bounding box information in hierarchy blocks |
| `ocrCoverageThreshold` | `?float` | `null` | OCR coverage threshold for smart OCR triggering (0.0-1.0) Determines when OCR should be triggered based on text block coverage. OCR is triggered when text blocks cover less than this fraction of the page. Default: 0.5 (trigger OCR if less than 50% of page has text) |

#### Methods

##### default()

**Signature:**

```php
public static function default(): HierarchyConfig
```


---

### HocrWord

Represents a word extracted from hOCR (or any source) with position and confidence information.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `text` | `string` | — | Text |
| `left` | `int` | — | Left |
| `top` | `int` | — | Top |
| `width` | `int` | — | Width |
| `height` | `int` | — | Height |
| `confidence` | `float` | — | Confidence |

#### Methods

##### right()

Get the right edge position.

**Signature:**

```php
public function right(): int
```

##### bottom()

Get the bottom edge position.

**Signature:**

```php
public function bottom(): int
```

##### yCenter()

Get the vertical center position.

**Signature:**

```php
public function yCenter(): float
```

##### xCenter()

Get the horizontal center position.

**Signature:**

```php
public function xCenter(): float
```


---

### HtmlExtractor

HTML document extractor using html-to-markdown.

#### Methods

##### default()

**Signature:**

```php
public static function default(): HtmlExtractor
```

##### name()

**Signature:**

```php
public function name(): string
```

##### version()

**Signature:**

```php
public function version(): string
```

##### initialize()

**Signature:**

```php
public function initialize(): void
```

##### shutdown()

**Signature:**

```php
public function shutdown(): void
```

##### extractSync()

**Signature:**

```php
public function extractSync(string $content, string $mimeType, ExtractionConfig $config): InternalDocument
```

##### extractBytes()

**Signature:**

```php
public function extractBytes(string $content, string $mimeType, ExtractionConfig $config): InternalDocument
```

##### supportedMimeTypes()

**Signature:**

```php
public function supportedMimeTypes(): array<string>
```

##### priority()

**Signature:**

```php
public function priority(): int
```

##### asSyncExtractor()

**Signature:**

```php
public function asSyncExtractor(): ?SyncExtractor
```


---

### HtmlMetadata

HTML metadata extracted from HTML documents.

Includes document-level metadata, Open Graph data, Twitter Card metadata,
and extracted structural elements (headers, links, images, structured data).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `title` | `?string` | `null` | Document title from `<title>` tag |
| `description` | `?string` | `null` | Document description from `<meta name="description">` tag |
| `keywords` | `array<string>` | `[]` | Document keywords from `<meta name="keywords">` tag, split on commas |
| `author` | `?string` | `null` | Document author from `<meta name="author">` tag |
| `canonicalUrl` | `?string` | `null` | Canonical URL from `<link rel="canonical">` tag |
| `baseHref` | `?string` | `null` | Base URL from `<base href="">` tag for resolving relative URLs |
| `language` | `?string` | `null` | Document language from `lang` attribute |
| `textDirection` | `?TextDirection` | `TextDirection::LeftToRight` | Document text direction from `dir` attribute |
| `openGraph` | `array<string, string>` | `{}` | Open Graph metadata (og:* properties) for social media Keys like "title", "description", "image", "url", etc. |
| `twitterCard` | `array<string, string>` | `{}` | Twitter Card metadata (twitter:* properties) Keys like "card", "site", "creator", "title", "description", "image", etc. |
| `metaTags` | `array<string, string>` | `{}` | Additional meta tags not covered by specific fields Keys are meta name/property attributes, values are content |
| `headers` | `array<HeaderMetadata>` | `[]` | Extracted header elements with hierarchy |
| `links` | `array<LinkMetadata>` | `[]` | Extracted hyperlinks with type classification |
| `images` | `array<ImageMetadataType>` | `[]` | Extracted images with source and dimensions |
| `structuredData` | `array<StructuredData>` | `[]` | Extracted structured data blocks |

#### Methods

##### isEmpty()

Check if metadata is empty (no meaningful content extracted).

**Signature:**

```php
public function isEmpty(): bool
```

##### from()

**Signature:**

```php
public static function from(HtmlMetadata $metadata): HtmlMetadata
```


---

### HtmlOutputConfig

Configuration for styled HTML output.

When set on `ExtractionConfig::html_output` alongside
`output_format = OutputFormat::Html`, the pipeline builds a
`StyledHtmlRenderer` instead of
the plain comrak-based renderer.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `css` | `?string` | `null` | Inline CSS string injected into the output after the theme stylesheet. Concatenated after `css_file` content when both are set. |
| `cssFile` | `?string` | `null` | Path to a CSS file loaded once at renderer construction time. Concatenated before `css` when both are set. |
| `theme` | `HtmlTheme` | `HtmlTheme::Unstyled` | Built-in colour/typography theme. Default: `HtmlTheme.Unstyled`. |
| `classPrefix` | `string` | `null` | CSS class prefix applied to every emitted class name. Default: `"kb-"`. Change this if your host application already uses classes that start with `kb-`. |
| `embedCss` | `bool` | `true` | When `True` (default), write the resolved CSS into a `<style>` block immediately after the opening `<div class="{prefix}doc">`. Set to `False` to emit only the structural markup and wire up your own stylesheet targeting the `kb-*` class names. |

#### Methods

##### default()

**Signature:**

```php
public static function default(): HtmlOutputConfig
```


---

### HwpDocument

An extracted HWP document, consisting of one or more body-text sections.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `sections` | `array<Section>` | `[]` | All sections from all BodyText/SectionN streams. |

#### Methods

##### extractText()

Concatenate the text of every paragraph in every section, separated by
newlines.

**Signature:**

```php
public function extractText(): string
```


---

### HwpExtractor

Extractor for Hangul Word Processor (.hwp) files.

Supports HWP 5.0 format, the standard document format in South Korea.

#### Methods

##### default()

**Signature:**

```php
public static function default(): HwpExtractor
```

##### name()

**Signature:**

```php
public function name(): string
```

##### version()

**Signature:**

```php
public function version(): string
```

##### initialize()

**Signature:**

```php
public function initialize(): void
```

##### shutdown()

**Signature:**

```php
public function shutdown(): void
```

##### description()

**Signature:**

```php
public function description(): string
```

##### author()

**Signature:**

```php
public function author(): string
```

##### extractBytes()

**Signature:**

```php
public function extractBytes(string $content, string $mimeType, ExtractionConfig $config): InternalDocument
```

##### supportedMimeTypes()

**Signature:**

```php
public function supportedMimeTypes(): array<string>
```

##### priority()

**Signature:**

```php
public function priority(): int
```


---

### ImageDpiConfig

Image extraction DPI configuration (internal use).

**Note:** This is an internal type used for image preprocessing.
For the main extraction configuration, see `crate::core::config::ExtractionConfig`.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `targetDpi` | `int` | `300` | Target DPI for image normalization |
| `maxImageDimension` | `int` | `4096` | Maximum image dimension (width or height) |
| `autoAdjustDpi` | `bool` | `true` | Whether to auto-adjust DPI based on content |
| `minDpi` | `int` | `72` | Minimum DPI threshold |
| `maxDpi` | `int` | `600` | Maximum DPI threshold |

#### Methods

##### default()

**Signature:**

```php
public static function default(): ImageDpiConfig
```


---

### ImageExtractionConfig

Image extraction configuration.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `extractImages` | `bool` | `null` | Extract images from documents |
| `targetDpi` | `int` | `null` | Target DPI for image normalization |
| `maxImageDimension` | `int` | `null` | Maximum dimension for images (width or height) |
| `injectPlaceholders` | `bool` | `null` | Whether to inject image reference placeholders into markdown output. When `True` (default), image references like `![Image 1](embedded:p1_i0)` are appended to the markdown. Set to `False` to extract images as data without polluting the markdown output. |
| `autoAdjustDpi` | `bool` | `null` | Automatically adjust DPI based on image content |
| `minDpi` | `int` | `null` | Minimum DPI threshold |
| `maxDpi` | `int` | `null` | Maximum DPI threshold |


---

### ImageExtractor

Image extractor for various image formats.

Supports: PNG, JPEG, WebP, BMP, TIFF, GIF.
Extracts dimensions, format, and EXIF metadata.
Optionally runs OCR when configured.
When layout detection is also enabled, uses per-region OCR with
markdown formatting based on detected layout classes.

#### Methods

##### default()

**Signature:**

```php
public static function default(): ImageExtractor
```

##### name()

**Signature:**

```php
public function name(): string
```

##### version()

**Signature:**

```php
public function version(): string
```

##### initialize()

**Signature:**

```php
public function initialize(): void
```

##### shutdown()

**Signature:**

```php
public function shutdown(): void
```

##### description()

**Signature:**

```php
public function description(): string
```

##### author()

**Signature:**

```php
public function author(): string
```

##### extractBytes()

**Signature:**

```php
public function extractBytes(string $content, string $mimeType, ExtractionConfig $config): InternalDocument
```

##### supportedMimeTypes()

**Signature:**

```php
public function supportedMimeTypes(): array<string>
```

##### priority()

**Signature:**

```php
public function priority(): int
```


---

### ImageMetadata

Image metadata extracted from image files.

Includes dimensions, format, and EXIF data.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `width` | `int` | — | Image width in pixels |
| `height` | `int` | — | Image height in pixels |
| `format` | `string` | — | Image format (e.g., "PNG", "JPEG", "TIFF") |
| `exif` | `array<string, string>` | — | EXIF metadata tags |


---

### ImageMetadataType

Image element metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `src` | `string` | — | Image source (URL, data URI, or SVG content) |
| `alt` | `?string` | `null` | Alternative text from alt attribute |
| `title` | `?string` | `null` | Title attribute |
| `dimensions` | `?U32U32` | `null` | Image dimensions as (width, height) if available |
| `imageType` | `ImageType` | — | Image type classification |
| `attributes` | `array<StringString>` | — | Additional attributes as key-value pairs |


---

### ImageOcrResult

Result of OCR extraction from an image with optional page tracking.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `content` | `string` | — | Extracted text content |
| `boundaries` | `?array<PageBoundary>` | `null` | Character byte boundaries per frame (for multi-frame TIFFs) |
| `pageContents` | `?array<PageContent>` | `null` | Per-frame content information |


---

### ImagePreprocessingConfig

Image preprocessing configuration for OCR.

These settings control how images are preprocessed before OCR to improve
text recognition quality. Different preprocessing strategies work better
for different document types.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `targetDpi` | `int` | `300` | Target DPI for the image (300 is standard, 600 for small text). |
| `autoRotate` | `bool` | `true` | Auto-detect and correct image rotation. |
| `deskew` | `bool` | `true` | Correct skew (tilted images). |
| `denoise` | `bool` | `false` | Remove noise from the image. |
| `contrastEnhance` | `bool` | `false` | Enhance contrast for better text visibility. |
| `binarizationMethod` | `string` | `"otsu"` | Binarization method: "otsu", "sauvola", "adaptive". |
| `invertColors` | `bool` | `false` | Invert colors (white text on black → black on white). |

#### Methods

##### default()

**Signature:**

```php
public static function default(): ImagePreprocessingConfig
```


---

### ImagePreprocessingMetadata

Image preprocessing metadata.

Tracks the transformations applied to an image during OCR preprocessing,
including DPI normalization, resizing, and resampling.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `originalDimensions` | `UsizeUsize` | — | Original image dimensions (width, height) in pixels |
| `originalDpi` | `F64F64` | — | Original image DPI (horizontal, vertical) |
| `targetDpi` | `int` | — | Target DPI from configuration |
| `scaleFactor` | `float` | — | Scaling factor applied to the image |
| `autoAdjusted` | `bool` | — | Whether DPI was auto-adjusted based on content |
| `finalDpi` | `int` | — | Final DPI after processing |
| `newDimensions` | `?UsizeUsize` | `null` | New dimensions after resizing (if resized) |
| `resampleMethod` | `string` | — | Resampling algorithm used ("LANCZOS3", "CATMULLROM", etc.) |
| `dimensionClamped` | `bool` | — | Whether dimensions were clamped to max_image_dimension |
| `calculatedDpi` | `?int` | `null` | Calculated optimal DPI (if auto_adjust_dpi enabled) |
| `skippedResize` | `bool` | — | Whether resize was skipped (dimensions already optimal) |
| `resizeError` | `?string` | `null` | Error message if resize failed |


---

### InlineElement

Inline element within a block.

Represents text with formatting, links, images, etc.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `elementType` | `InlineType` | — | Type of inline element |
| `content` | `string` | — | Text content |
| `attributes` | `?Attributes` | `null` | Element attributes |
| `metadata` | `?array<string, string>` | `null` | Additional metadata (e.g., href for links, src/alt for images) |


---

### Instant

A platform-aware instant for measuring elapsed time.

On native targets this delegates to `std::time::Instant`.
On `wasm32` targets it is a zero-cost no-op to avoid the `unreachable` trap.

#### Methods

##### now()

Capture the current instant.

**Signature:**

```php
public static function now(): Instant
```

##### elapsedSecsF64()

Seconds elapsed since this instant was captured (as `f64`).

**Signature:**

```php
public function elapsedSecsF64(): float
```

##### elapsedMs()

Milliseconds elapsed since this instant was captured (as `f64`).

**Signature:**

```php
public function elapsedMs(): float
```

##### elapsedMillis()

Milliseconds elapsed as `u128` (mirrors `Duration::as_millis`).

**Signature:**

```php
public function elapsedMillis(): U128
```


---

### InternalDocument

The internal flat document representation.

All extractors output this structure. It is converted to the public
`ExtractionResult` and
`DocumentStructure` in the pipeline.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `elements` | `array<InternalElement>` | — | All elements in reading order. Append-only during extraction. |
| `relationships` | `array<Relationship>` | — | Relationships between elements (source index → target). Stored separately from elements for cache-friendly iteration. |
| `sourceFormat` | `Str` | — | Source format identifier (e.g., "pdf", "docx", "html", "markdown"). |
| `metadata` | `Metadata` | — | Document-level metadata (title, author, dates, etc.). |
| `images` | `array<ExtractedImage>` | — | Extracted images (binary data). Referenced by index from `ElementKind.Image`. |
| `tables` | `array<Table>` | — | Extracted tables (structured data). Referenced by index from `ElementKind.Table`. |
| `uris` | `array<Uri>` | — | URIs/links discovered during extraction (hyperlinks, image refs, citations, etc.). |
| `children` | `?array<ArchiveEntry>` | `null` | Archive children: fully-extracted results for files within an archive. Only populated by archive extractors (ZIP, TAR, 7z, GZIP) when recursive extraction is enabled. Each entry contains the full `ExtractionResult` for a child file that was extracted through the public pipeline. |
| `mimeType` | `Str` | — | MIME type of the source document (e.g., "application/pdf", "text/html"). |
| `processingWarnings` | `array<ProcessingWarning>` | — | Non-fatal warnings collected during extraction. |
| `annotations` | `?array<PdfAnnotation>` | `null` | PDF annotations (links, highlights, notes). |
| `prebuiltPages` | `?array<PageContent>` | `null` | Pre-built per-page content (set by extractors that track page boundaries natively). When populated, `derive_extraction_result` uses this directly instead of attempting to reconstruct pages from element-level page numbers. |
| `preRenderedContent` | `?string` | `null` | Pre-rendered formatted content produced by the extractor itself. When an extractor has direct access to high-quality formatted output (e.g., html-to-markdown produces GFM markdown), it can store that here to bypass the lossy InternalDocument → renderer round-trip. `derive_extraction_result` will use this directly when the requested output format matches `metadata.output_format`. |

#### Methods

##### pushElement()

Push an element and return its index.

**Signature:**

```php
public function pushElement(InternalElement $element): int
```

##### pushRelationship()

Push a relationship.

**Signature:**

```php
public function pushRelationship(Relationship $relationship): void
```

##### pushTable()

Push a table and return its index (for use in `ElementKind::Table`).

**Signature:**

```php
public function pushTable(Table $table): int
```

##### pushImage()

Push an image and return its index (for use in `ElementKind::Image`).

**Signature:**

```php
public function pushImage(ExtractedImage $image): int
```

##### pushUri()

Push a URI discovered during extraction.
Silently drops URIs beyond `MAX_URIS` to prevent unbounded memory growth.

**Signature:**

```php
public function pushUri(Uri $uri): void
```

##### content()

Concatenate all element text into a single string, separated by newlines.

**Signature:**

```php
public function content(): string
```


---

### InternalDocumentBuilder

Builder for constructing `InternalDocument` with an ergonomic push-based API.

Tracks nesting depth automatically for list and quote containers,
and generates deterministic element IDs via blake3 hashing.

#### Methods

##### sourceFormat()

Set the source format identifier (e.g. "docx", "html", "pptx").

**Signature:**

```php
public function sourceFormat(Str $format): void
```

##### setMetadata()

Set document-level metadata.

**Signature:**

```php
public function setMetadata(Metadata $metadata): void
```

##### setMimeType()

Set the MIME type of the source document.

**Signature:**

```php
public function setMimeType(Str $mimeType): void
```

##### addWarning()

Add a non-fatal processing warning.

**Signature:**

```php
public function addWarning(ProcessingWarning $warning): void
```

##### setPdfAnnotations()

Set document-level PDF annotations (links, highlights, notes).

**Signature:**

```php
public function setPdfAnnotations(array<PdfAnnotation> $annotations): void
```

##### pushUri()

Push a URI discovered during extraction.

**Signature:**

```php
public function pushUri(Uri $uri): void
```

##### build()

Consume the builder and return the constructed `InternalDocument`.

**Signature:**

```php
public function build(): InternalDocument
```

##### pushHeading()

Push a heading element.

Auto-sets depth from the heading level and generates an anchor slug
from the heading text.

**Signature:**

```php
public function pushHeading(int $level, string $text, int $page, BoundingBox $bbox): int
```

##### pushParagraph()

Push a paragraph element.

**Signature:**

```php
public function pushParagraph(string $text, array<TextAnnotation> $annotations, int $page, BoundingBox $bbox): int
```

##### pushList()

Push a `ListStart` marker and increment depth.

**Signature:**

```php
public function pushList(bool $ordered): void
```

##### endList()

Push a `ListEnd` marker and decrement depth.

**Signature:**

```php
public function endList(): void
```

##### pushListItem()

Push a list item element at the current depth.

**Signature:**

```php
public function pushListItem(string $text, bool $ordered, array<TextAnnotation> $annotations, int $page, BoundingBox $bbox): int
```

##### pushTable()

Push a table element. The table data is stored separately in
`InternalDocument::tables` and referenced by index.

**Signature:**

```php
public function pushTable(Table $table, int $page, BoundingBox $bbox): int
```

##### pushTableFromCells()

Push a table element from a 2D cell grid, building a `Table` struct automatically.

**Signature:**

```php
public function pushTableFromCells(array<array<string>> $cells, int $page, BoundingBox $bbox): int
```

##### pushImage()

Push an image element. The image data is stored separately in
`InternalDocument::images` and referenced by index.

**Signature:**

```php
public function pushImage(string $description, ExtractedImage $image, int $page, BoundingBox $bbox): int
```

##### pushCode()

Push a code block element. Language is stored in attributes.

**Signature:**

```php
public function pushCode(string $text, string $language, int $page, BoundingBox $bbox): int
```

##### pushFormula()

Push a math formula element.

**Signature:**

```php
public function pushFormula(string $text, int $page, BoundingBox $bbox): int
```

##### pushFootnoteRef()

Push a footnote reference marker.

Creates a `FootnoteRef` element with `anchor = key` and also records
a `Relationship` with `RelationshipTarget::Key(key)` so the derivation
step can resolve it to the definition.

**Signature:**

```php
public function pushFootnoteRef(string $marker, string $key, int $page): int
```

##### pushFootnoteDefinition()

Push a footnote definition element with `anchor = key`.

**Signature:**

```php
public function pushFootnoteDefinition(string $text, string $key, int $page): int
```

##### pushCitation()

Push a citation / bibliographic reference element.

**Signature:**

```php
public function pushCitation(string $text, string $key, int $page): int
```

##### pushQuoteStart()

Push a `QuoteStart` marker and increment depth.

**Signature:**

```php
public function pushQuoteStart(): void
```

##### pushQuoteEnd()

Push a `QuoteEnd` marker and decrement depth.

**Signature:**

```php
public function pushQuoteEnd(): void
```

##### pushPageBreak()

Push a page break marker at depth 0.

**Signature:**

```php
public function pushPageBreak(): void
```

##### pushSlide()

Push a slide element.

**Signature:**

```php
public function pushSlide(int $number, string $title, int $page): int
```

##### pushAdmonition()

Push an admonition / callout element (note, warning, tip, etc.).
Kind and optional title are stored in attributes.

**Signature:**

```php
public function pushAdmonition(string $kind, string $title, int $page): int
```

##### pushRawBlock()

Push a raw block preserved verbatim. Format is stored in attributes.

**Signature:**

```php
public function pushRawBlock(string $format, string $content, int $page): int
```

##### pushMetadataBlock()

Push a structured metadata block (frontmatter, email headers).
Entries are stored in attributes.

**Signature:**

```php
public function pushMetadataBlock(array<StringString> $entries, int $page): int
```

##### pushTitle()

Push a title element.

**Signature:**

```php
public function pushTitle(string $text, int $page, BoundingBox $bbox): int
```

##### pushDefinitionTerm()

Push a definition term element.

**Signature:**

```php
public function pushDefinitionTerm(string $text, int $page): int
```

##### pushDefinitionDescription()

Push a definition description element.

**Signature:**

```php
public function pushDefinitionDescription(string $text, int $page): int
```

##### pushOcrText()

Push an OCR text element with OCR-specific fields populated.

**Signature:**

```php
public function pushOcrText(string $text, OcrElementLevel $level, OcrBoundingGeometry $geometry, OcrConfidence $confidence, OcrRotation $rotation, int $page, BoundingBox $bbox): int
```

##### pushGroupStart()

Push a `GroupStart` marker and increment depth.

**Signature:**

```php
public function pushGroupStart(string $label, int $page): void
```

##### pushGroupEnd()

Push a `GroupEnd` marker and decrement depth.

**Signature:**

```php
public function pushGroupEnd(): void
```

##### pushRelationship()

Push a relationship between two elements.

**Signature:**

```php
public function pushRelationship(int $source, RelationshipTarget $target, RelationshipKind $kind): void
```

##### setAnchor()

Set the anchor on an already-pushed element.

**Signature:**

```php
public function setAnchor(int $index, string $anchor): void
```

##### setLayer()

Set the content layer on an already-pushed element.

**Signature:**

```php
public function setLayer(int $index, ContentLayer $layer): void
```

##### setAttributes()

Set attributes on an already-pushed element.

**Signature:**

```php
public function setAttributes(int $index, AHashMap $attributes): void
```

##### setAnnotations()

Set annotations on an already-pushed element.

**Signature:**

```php
public function setAnnotations(int $index, array<TextAnnotation> $annotations): void
```

##### setText()

Set the text content of an already-pushed element.

**Signature:**

```php
public function setText(int $index, string $text): void
```

##### pushElement()

Push a pre-constructed `InternalElement` directly.

Useful when the caller needs to construct an element with fields
that the builder's convenience methods don't cover (e.g. an image
element without `ExtractedImage` data).

**Signature:**

```php
public function pushElement(InternalElement $element): int
```


---

### InternalElement

A single element in the internal flat document.

Elements are appended in reading order during extraction. The `depth` field
and optional container markers enable tree reconstruction in the derivation step.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `id` | `InternalElementId` | — | Deterministic identifier. |
| `kind` | `ElementKind` | — | What kind of content this element represents. |
| `text` | `string` | — | Primary text content. Empty for non-text elements (images, page breaks). |
| `depth` | `int` | — | Nesting depth (0 = root level). Extractors set this based on heading level, list indent, blockquote depth, etc. The tree derivation step uses depth changes to reconstruct parent-child relationships. |
| `page` | `?int` | `null` | Page number (1-indexed). `None` for non-paginated formats. |
| `bbox` | `?BoundingBox` | `null` | Bounding box in document coordinates. |
| `layer` | `ContentLayer` | — | Content layer classification (Body, Header, Footer, Footnote). |
| `annotations` | `array<TextAnnotation>` | — | Inline annotations (formatting, links) on this element's text content. Byte-range based, reuses the existing `TextAnnotation` type. |
| `attributes` | `?AHashMap` | `null` | Format-specific key-value attributes. Used for CSS classes, LaTeX env names, slide layout names, etc. |
| `anchor` | `?string` | `null` | Optional anchor/key for this element. Used by the relationship resolver to match references to targets. Examples: heading slug `"introduction"`, footnote label `"fn1"`, citation key `"smith2024"`, figure label `"fig:diagram"`. |
| `ocrGeometry` | `?OcrBoundingGeometry` | `null` | OCR bounding geometry (rectangle or quadrilateral). |
| `ocrConfidence` | `?OcrConfidence` | `null` | OCR confidence scores (detection + recognition). |
| `ocrRotation` | `?OcrRotation` | `null` | OCR rotation metadata. |

#### Methods

##### text()

Create a simple text element with minimal fields.

**Signature:**

```php
public static function text(ElementKind $kind, string $text, int $depth): InternalElement
```

##### withPage()

Set the page number.

**Signature:**

```php
public function withPage(int $page): InternalElement
```

##### withBbox()

Set the bounding box.

**Signature:**

```php
public function withBbox(BoundingBox $bbox): InternalElement
```

##### withLayer()

Set the content layer.

**Signature:**

```php
public function withLayer(ContentLayer $layer): InternalElement
```

##### withAnchor()

Set the anchor key.

**Signature:**

```php
public function withAnchor(string $anchor): InternalElement
```

##### withAnnotations()

Set annotations.

**Signature:**

```php
public function withAnnotations(array<TextAnnotation> $annotations): InternalElement
```

##### withAttributes()

Set attributes.

**Signature:**

```php
public function withAttributes(AHashMap $attributes): InternalElement
```

##### withIndex()

Regenerate the ID with the correct index (call after pushing to the document).

**Signature:**

```php
public function withIndex(int $index): InternalElement
```


---

### InternalElementId

Deterministic element identifier, generated via blake3 hashing.

Format: `"ie-{12 hex chars}"` (48 bits from blake3, ~281 trillion address space).
Same input always produces the same ID, enabling diffing and caching.

#### Methods

##### generate()

Generate a deterministic ID from element content.

Hashes the element kind discriminant, text content, page number, and
positional index using blake3. Takes 48 bits (6 bytes) of the hash.

**Signature:**

```php
public static function generate(string $kindDiscriminant, string $text, int $page, int $index): InternalElementId
```

##### asStr()

Get the ID as a string slice.

**Signature:**

```php
public function asStr(): string
```

##### fmt()

**Signature:**

```php
public function fmt(Formatter $f): Unknown
```

##### asRef()

**Signature:**

```php
public function asRef(): string
```


---

### IterationValidator

Helper struct for validating iteration counts.

#### Methods

##### checkIteration()

Validate and increment iteration count.

**Returns:**
* `Ok(())` if count is within limits
* `Err(SecurityError)` if count exceeds limit

**Signature:**

```php
public function checkIteration(): void
```

##### currentCount()

Get current iteration count.

**Signature:**

```php
public function currentCount(): int
```


---

### JatsExtractor

JATS document extractor.

Supports JATS (Journal Article Tag Suite) XML documents in various versions,
handling both the full article structure and minimal JATS subsets.

#### Methods

##### default()

**Signature:**

```php
public static function default(): JatsExtractor
```

##### name()

**Signature:**

```php
public function name(): string
```

##### version()

**Signature:**

```php
public function version(): string
```

##### initialize()

**Signature:**

```php
public function initialize(): void
```

##### shutdown()

**Signature:**

```php
public function shutdown(): void
```

##### extractBytes()

**Signature:**

```php
public function extractBytes(string $content, string $mimeType, ExtractionConfig $config): InternalDocument
```

##### supportedMimeTypes()

**Signature:**

```php
public function supportedMimeTypes(): array<string>
```

##### priority()

**Signature:**

```php
public function priority(): int
```


---

### JatsMetadata

JATS (Journal Article Tag Suite) metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `copyright` | `?string` | `null` | Copyright |
| `license` | `?string` | `null` | License |
| `historyDates` | `array<string, string>` | `{}` | History dates |
| `contributorRoles` | `array<ContributorRole>` | `[]` | Contributor roles |


---

### JsonExtractionConfig

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `extractSchema` | `bool` | `false` | Extract schema |
| `maxDepth` | `int` | `20` | Maximum depth |
| `arrayItemLimit` | `int` | `500` | Array item limit |
| `includeTypeInfo` | `bool` | `false` | Include type info |
| `flattenNestedObjects` | `bool` | `true` | Flatten nested objects |
| `customTextFieldPatterns` | `array<string>` | `[]` | Custom text field patterns |

#### Methods

##### default()

**Signature:**

```php
public static function default(): JsonExtractionConfig
```


---

### JupyterExtractor

Jupyter Notebook extractor.

Extracts content from Jupyter notebook JSON files, including:
- Notebook metadata (kernel, language, nbformat version)
- Cell content (code and markdown)
- Cell outputs (text, HTML, etc.)
- Cell-level metadata (tags, execution counts)

#### Methods

##### default()

**Signature:**

```php
public static function default(): JupyterExtractor
```

##### name()

**Signature:**

```php
public function name(): string
```

##### version()

**Signature:**

```php
public function version(): string
```

##### initialize()

**Signature:**

```php
public function initialize(): void
```

##### shutdown()

**Signature:**

```php
public function shutdown(): void
```

##### description()

**Signature:**

```php
public function description(): string
```

##### author()

**Signature:**

```php
public function author(): string
```

##### extractBytes()

**Signature:**

```php
public function extractBytes(string $content, string $mimeType, ExtractionConfig $config): InternalDocument
```

##### supportedMimeTypes()

**Signature:**

```php
public function supportedMimeTypes(): array<string>
```

##### priority()

**Signature:**

```php
public function priority(): int
```


---

### KeynoteExtractor

Apple Keynote presentation extractor.

Supports `.key` files (modern iWork format, 2013+).

Extracts slide text and speaker notes from the IWA container:
ZIP → Snappy → protobuf text fields.

#### Methods

##### default()

**Signature:**

```php
public static function default(): KeynoteExtractor
```

##### name()

**Signature:**

```php
public function name(): string
```

##### version()

**Signature:**

```php
public function version(): string
```

##### initialize()

**Signature:**

```php
public function initialize(): void
```

##### shutdown()

**Signature:**

```php
public function shutdown(): void
```

##### description()

**Signature:**

```php
public function description(): string
```

##### author()

**Signature:**

```php
public function author(): string
```

##### extractBytes()

**Signature:**

```php
public function extractBytes(string $content, string $mimeType, ExtractionConfig $config): InternalDocument
```

##### supportedMimeTypes()

**Signature:**

```php
public function supportedMimeTypes(): array<string>
```

##### priority()

**Signature:**

```php
public function priority(): int
```


---

### Keyword

Extracted keyword with metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `text` | `string` | — | The keyword text. |
| `score` | `float` | — | Relevance score (higher is better, algorithm-specific range). |
| `algorithm` | `KeywordAlgorithm` | — | Algorithm that extracted this keyword. |
| `positions` | `?array<int>` | `null` | Optional positions where keyword appears in text (character offsets). |

#### Methods

##### withPositions()

Create a new keyword with positions.

**Signature:**

```php
public static function withPositions(string $text, float $score, KeywordAlgorithm $algorithm, array<int> $positions): Keyword
```


---

### KeywordConfig

Keyword extraction configuration.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `algorithm` | `KeywordAlgorithm` | `KeywordAlgorithm::Yake` | Algorithm to use for extraction. |
| `maxKeywords` | `int` | `10` | Maximum number of keywords to extract (default: 10). |
| `minScore` | `float` | `0` | Minimum score threshold (0.0-1.0, default: 0.0). Keywords with scores below this threshold are filtered out. Note: Score ranges differ between algorithms. |
| `ngramRange` | `UsizeUsize` | `null` | N-gram range for keyword extraction (min, max). (1, 1) = unigrams only (1, 2) = unigrams and bigrams (1, 3) = unigrams, bigrams, and trigrams (default) |
| `language` | `?string` | `null` | Language code for stopword filtering (e.g., "en", "de", "fr"). If None, no stopword filtering is applied. |
| `yakeParams` | `?YakeParams` | `null` | YAKE-specific tuning parameters. |
| `rakeParams` | `?RakeParams` | `null` | RAKE-specific tuning parameters. |

#### Methods

##### default()

**Signature:**

```php
public static function default(): KeywordConfig
```

##### withMaxKeywords()

Set maximum number of keywords to extract.

**Signature:**

```php
public function withMaxKeywords(int $max): KeywordConfig
```

##### withMinScore()

Set minimum score threshold.

**Signature:**

```php
public function withMinScore(float $score): KeywordConfig
```

##### withNgramRange()

Set n-gram range.

**Signature:**

```php
public function withNgramRange(int $min, int $max): KeywordConfig
```

##### withLanguage()

Set language for stopword filtering.

**Signature:**

```php
public function withLanguage(string $lang): KeywordConfig
```


---

### KeywordExtractor

Post-processor that extracts keywords from document content.

This processor:
- Runs in the Middle processing stage
- Only processes when `config.keywords` is configured
- Stores extracted keywords in `metadata.additional["keywords"]`
- Uses the configured algorithm (YAKE or RAKE)

#### Methods

##### name()

**Signature:**

```php
public function name(): string
```

##### version()

**Signature:**

```php
public function version(): string
```

##### initialize()

**Signature:**

```php
public function initialize(): void
```

##### shutdown()

**Signature:**

```php
public function shutdown(): void
```

##### process()

**Signature:**

```php
public function process(ExtractionResult $result, ExtractionConfig $config): void
```

##### processingStage()

**Signature:**

```php
public function processingStage(): ProcessingStage
```

##### shouldProcess()

**Signature:**

```php
public function shouldProcess(ExtractionResult $result, ExtractionConfig $config): bool
```

##### estimatedDurationMs()

**Signature:**

```php
public function estimatedDurationMs(ExtractionResult $result): int
```


---

### LanguageDetectionConfig

Language detection configuration.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `enabled` | `bool` | — | Enable language detection |
| `minConfidence` | `float` | — | Minimum confidence threshold (0.0-1.0) |
| `detectMultiple` | `bool` | — | Detect multiple languages in the document |


---

### LanguageDetector

Post-processor that detects languages in document content.

This processor:
- Runs in the Early processing stage
- Only processes when `config.language_detection` is configured
- Stores detected languages in `result.detected_languages`
- Uses the whatlang library for detection

#### Methods

##### name()

**Signature:**

```php
public function name(): string
```

##### version()

**Signature:**

```php
public function version(): string
```

##### initialize()

**Signature:**

```php
public function initialize(): void
```

##### shutdown()

**Signature:**

```php
public function shutdown(): void
```

##### process()

**Signature:**

```php
public function process(ExtractionResult $result, ExtractionConfig $config): void
```

##### processingStage()

**Signature:**

```php
public function processingStage(): ProcessingStage
```

##### shouldProcess()

**Signature:**

```php
public function shouldProcess(ExtractionResult $result, ExtractionConfig $config): bool
```

##### estimatedDurationMs()

**Signature:**

```php
public function estimatedDurationMs(ExtractionResult $result): int
```


---

### LanguageRegistry

Language support registry for OCR backends.

Maintains a mapping of OCR backend names to their supported language codes.
This is the single source of truth for language support across all bindings.

#### Methods

##### global()

Get the default global registry instance.

The registry is created on first access and reused for all subsequent calls.

**Returns:**

A reference to the global `LanguageRegistry` instance.

**Signature:**

```php
public static function global(): LanguageRegistry
```

##### getSupportedLanguages()

Get supported languages for a specific OCR backend.

**Returns:**

`Some(&[String])` if the backend is registered, `null` otherwise.

**Signature:**

```php
public function getSupportedLanguages(string $backend): ?array<string>
```

##### isLanguageSupported()

Check if a language is supported by a specific backend.

**Returns:**

`true` if the language is supported, `false` otherwise.

**Signature:**

```php
public function isLanguageSupported(string $backend, string $language): bool
```

##### getBackends()

Get all registered backend names.

**Returns:**

A vector of backend names in the registry.

**Signature:**

```php
public function getBackends(): array<string>
```

##### getLanguageCount()

Get language count for a specific backend.

**Returns:**

Number of supported languages for the backend, or 0 if backend not found.

**Signature:**

```php
public function getLanguageCount(string $backend): int
```

##### default()

**Signature:**

```php
public static function default(): LanguageRegistry
```


---

### LatexExtractor

LaTeX document extractor

#### Methods

##### buildInternalDocument()

Build an `InternalDocument` from LaTeX source.

Captures `\label{}` as anchors, `\ref{}` as CrossReference relationships,
`\cite{}` as CitationReference relationships, and footnotes.

**Signature:**

```php
public static function buildInternalDocument(string $source, bool $injectPlaceholders): InternalDocument
```

##### default()

**Signature:**

```php
public static function default(): LatexExtractor
```

##### name()

**Signature:**

```php
public function name(): string
```

##### version()

**Signature:**

```php
public function version(): string
```

##### initialize()

**Signature:**

```php
public function initialize(): void
```

##### shutdown()

**Signature:**

```php
public function shutdown(): void
```

##### description()

**Signature:**

```php
public function description(): string
```

##### author()

**Signature:**

```php
public function author(): string
```

##### extractBytes()

**Signature:**

```php
public function extractBytes(string $content, string $mimeType, ExtractionConfig $config): InternalDocument
```

##### extractFile()

**Signature:**

```php
public function extractFile(string $path, string $mimeType, ExtractionConfig $config): InternalDocument
```

##### supportedMimeTypes()

**Signature:**

```php
public function supportedMimeTypes(): array<string>
```

##### priority()

**Signature:**

```php
public function priority(): int
```


---

### LayoutDetection

A single layout detection result.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `class` | `LayoutClass` | — | Class (layout class) |
| `confidence` | `float` | — | Confidence |
| `bbox` | `BBox` | — | Bbox (b box) |

#### Methods

##### sortByConfidenceDesc()

Sort detections by confidence in descending order.

**Signature:**

```php
public static function sortByConfidenceDesc(array<LayoutDetection> $detections): void
```

##### fmt()

**Signature:**

```php
public function fmt(Formatter $f): Unknown
```


---

### LayoutDetectionConfig

Layout detection configuration.

Controls layout detection behavior in the extraction pipeline.
When set on `ExtractionConfig`, layout detection
is enabled for PDF extraction.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `confidenceThreshold` | `?float` | `null` | Confidence threshold override (None = use model default). |
| `applyHeuristics` | `bool` | `true` | Whether to apply postprocessing heuristics (default: true). |
| `tableModel` | `TableModel` | `TableModel::Tatr` | Table structure recognition model. Controls which model is used for table cell detection within layout-detected table regions. Defaults to `TableModel.Tatr`. |

#### Methods

##### default()

**Signature:**

```php
public static function default(): LayoutDetectionConfig
```


---

### LayoutEngine

High-level layout detection engine.

Wraps model loading, inference, and postprocessing into a single
reusable object. Models are downloaded and cached on first use.

#### Methods

##### fromConfig()

Create a layout engine from a full config.

**Signature:**

```php
public static function fromConfig(LayoutEngineConfig $config): LayoutEngine
```

##### detect()

Run layout detection on an image.

Returns a `DetectionResult` with bounding boxes, classes, and confidence scores.
If `apply_heuristics` is enabled in config, postprocessing is applied automatically.

**Signature:**

```php
public function detect(RgbImage $img): DetectionResult
```

##### detectTimed()

Run layout detection on an image and return granular timing data.

Identical to `detect` but also returns a `DetectTimings` breakdown.
Use this when you need per-step profiling (preprocess / onnx / postprocess).

**Signature:**

```php
public function detectTimed(RgbImage $img): DetectionResultDetectTimings
```

##### detectBatch()

Run layout detection on a batch of images in a single model call.

Returns one `(DetectionResult, DetectTimings)` tuple per input image.
Postprocessing heuristics are applied per image when enabled in config.

Timing note: `preprocess_ms` and `onnx_ms` in each `DetectTimings` are the
amortized per-image share of the batch operation (total / N), not independent
per-image measurements.

**Signature:**

```php
public function detectBatch(array<RgbImage> $images): array<DetectionResultDetectTimings>
```

##### modelName()

Get the model name.

**Signature:**

```php
public function modelName(): string
```

##### config()

Return a reference to the engine's configuration.

Used by callers (e.g. parallel layout runners) that need to create
additional engines with identical settings.

**Signature:**

```php
public function config(): LayoutEngineConfig
```


---

### LayoutEngineConfig

Full configuration for the layout engine.

Provides fine-grained control over model selection, thresholds, and
postprocessing.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `backend` | `ModelBackend` | `ModelBackend::RtDetr` | Which model backend to use. |
| `confidenceThreshold` | `?float` | `null` | Confidence threshold override (None = use model default). |
| `applyHeuristics` | `bool` | `true` | Whether to apply postprocessing heuristics. |
| `cacheDir` | `?string` | `null` | Custom cache directory for model files (None = default). |

#### Methods

##### default()

**Signature:**

```php
public static function default(): LayoutEngineConfig
```


---

### LayoutModel

Common interface for all layout detection model backends.

#### Methods

##### detect()

Run layout detection on an image using the default confidence threshold.

**Signature:**

```php
public function detect(RgbImage $img): array<LayoutDetection>
```

##### detectWithThreshold()

Run layout detection with a custom confidence threshold.

**Signature:**

```php
public function detectWithThreshold(RgbImage $img, float $threshold): array<LayoutDetection>
```

##### detectBatch()

Run layout detection on a batch of images in a single model call.

Returns one `Vec<LayoutDetection>` per input image (same order).
`threshold` overrides the model's default confidence cutoff when `Some`.

The default implementation is a sequential fallback: models that support
true batched inference (e.g. `rtdetr::RtDetrModel`) override this.

**Signature:**

```php
public function detectBatch(array<RgbImage> $images, float $threshold): array<array<LayoutDetection>>
```

##### name()

Human-readable model name.

**Signature:**

```php
public function name(): string
```


---

### LayoutTimingReport

Timing breakdown for the entire layout detection run.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `totalMs` | `float` | — | Total ms |
| `perPage` | `array<PageTiming>` | — | Per page |

#### Methods

##### avgRenderMs()

**Signature:**

```php
public function avgRenderMs(): float
```

##### avgInferenceMs()

**Signature:**

```php
public function avgInferenceMs(): float
```

##### avgPreprocessMs()

**Signature:**

```php
public function avgPreprocessMs(): float
```

##### avgOnnxMs()

**Signature:**

```php
public function avgOnnxMs(): float
```

##### avgPostprocessMs()

**Signature:**

```php
public function avgPostprocessMs(): float
```

##### totalInferenceMs()

**Signature:**

```php
public function totalInferenceMs(): float
```

##### totalRenderMs()

**Signature:**

```php
public function totalRenderMs(): float
```

##### totalPreprocessMs()

**Signature:**

```php
public function totalPreprocessMs(): float
```

##### totalOnnxMs()

**Signature:**

```php
public function totalOnnxMs(): float
```

##### totalPostprocessMs()

**Signature:**

```php
public function totalPostprocessMs(): float
```


---

### LinkMetadata

Link element metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `href` | `string` | — | The href URL value |
| `text` | `string` | — | Link text content (normalized) |
| `title` | `?string` | `null` | Optional title attribute |
| `linkType` | `LinkType` | — | Link type classification |
| `rel` | `array<string>` | — | Rel attribute values |
| `attributes` | `array<StringString>` | — | Additional attributes as key-value pairs |


---

### LlmConfig

Configuration for an LLM provider/model via liter-llm.

Each feature (VLM OCR, VLM embeddings, structured extraction) carries
its own `LlmConfig`, allowing different providers per feature.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `model` | `string` | — | Provider/model string using liter-llm routing format. Examples: `"openai/gpt-4o"`, `"anthropic/claude-sonnet-4-20250514"`, `"groq/llama-3.1-70b-versatile"`. |
| `apiKey` | `?string` | `null` | API key for the provider. When `None`, liter-llm falls back to the provider's standard environment variable (e.g., `OPENAI_API_KEY`). |
| `baseUrl` | `?string` | `null` | Custom base URL override for the provider endpoint. |
| `timeoutSecs` | `?int` | `null` | Request timeout in seconds (default: 60). |
| `maxRetries` | `?int` | `null` | Maximum retry attempts (default: 3). |
| `temperature` | `?float` | `null` | Sampling temperature for generation tasks. |
| `maxTokens` | `?int` | `null` | Maximum tokens to generate. |


---

### LlmUsage

Token usage and cost data for a single LLM call made during extraction.

Populated when VLM OCR, structured extraction, or LLM-based embeddings
are used. Multiple entries may be present when multiple LLM calls occur
within one extraction (e.g. VLM OCR + structured extraction).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `model` | `string` | `null` | The LLM model identifier (e.g. "openai/gpt-4o", "anthropic/claude-sonnet-4-20250514"). |
| `source` | `string` | `null` | The pipeline stage that triggered this LLM call (e.g. "vlm_ocr", "structured_extraction", "embeddings"). |
| `inputTokens` | `?int` | `null` | Number of input/prompt tokens consumed. |
| `outputTokens` | `?int` | `null` | Number of output/completion tokens generated. |
| `totalTokens` | `?int` | `null` | Total tokens (input + output). |
| `estimatedCost` | `?float` | `null` | Estimated cost in USD based on the provider's published pricing. |
| `finishReason` | `?string` | `null` | Why the model stopped generating (e.g. "stop", "length", "content_filter"). |


---

### MarkdownExtractor

Markdown extractor with metadata and table support.

Parses markdown documents with YAML frontmatter, extracting:
- Metadata from YAML frontmatter
- Plain text content
- Tables as structured data
- Document structure (headings, links, code blocks)
- Images from data URIs

#### Methods

##### buildInternalDocument()

Build an `InternalDocument` from pulldown-cmark events and optional YAML frontmatter.

**Signature:**

```php
public static function buildInternalDocument(array<Event> $events, Value $yaml): InternalDocument
```

##### default()

**Signature:**

```php
public static function default(): MarkdownExtractor
```

##### name()

**Signature:**

```php
public function name(): string
```

##### version()

**Signature:**

```php
public function version(): string
```

##### initialize()

**Signature:**

```php
public function initialize(): void
```

##### shutdown()

**Signature:**

```php
public function shutdown(): void
```

##### description()

**Signature:**

```php
public function description(): string
```

##### author()

**Signature:**

```php
public function author(): string
```

##### extractBytes()

**Signature:**

```php
public function extractBytes(string $content, string $mimeType, ExtractionConfig $config): InternalDocument
```

##### extractFile()

**Signature:**

```php
public function extractFile(string $path, string $mimeType, ExtractionConfig $config): InternalDocument
```

##### supportedMimeTypes()

**Signature:**

```php
public function supportedMimeTypes(): array<string>
```

##### priority()

**Signature:**

```php
public function priority(): int
```


---

### MdxExtractor

MDX extractor with JSX stripping and Markdown processing.

Strips MDX-specific syntax (imports, exports, JSX component tags,
inline expressions) and processes the remaining content as Markdown,
extracting metadata from YAML frontmatter and tables.

#### Methods

##### buildInternalDocument()

Build an `InternalDocument` from pulldown-cmark events after JSX stripping.

JSX blocks that were stripped are recorded as raw blocks in the internal document.

**Signature:**

```php
public static function buildInternalDocument(array<Event> $events, Value $yaml, array<string> $rawJsxBlocks): InternalDocument
```

##### default()

**Signature:**

```php
public static function default(): MdxExtractor
```

##### name()

**Signature:**

```php
public function name(): string
```

##### version()

**Signature:**

```php
public function version(): string
```

##### initialize()

**Signature:**

```php
public function initialize(): void
```

##### shutdown()

**Signature:**

```php
public function shutdown(): void
```

##### description()

**Signature:**

```php
public function description(): string
```

##### author()

**Signature:**

```php
public function author(): string
```

##### extractBytes()

**Signature:**

```php
public function extractBytes(string $content, string $mimeType, ExtractionConfig $config): InternalDocument
```

##### extractFile()

**Signature:**

```php
public function extractFile(string $path, string $mimeType, ExtractionConfig $config): InternalDocument
```

##### supportedMimeTypes()

**Signature:**

```php
public function supportedMimeTypes(): array<string>
```

##### priority()

**Signature:**

```php
public function priority(): int
```


---

### Metadata

Extraction result metadata.

Contains common fields applicable to all formats, format-specific metadata
via a discriminated union, and additional custom fields from postprocessors.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `title` | `?string` | `null` | Document title |
| `subject` | `?string` | `null` | Document subject or description |
| `authors` | `?array<string>` | `[]` | Primary author(s) - always Vec for consistency |
| `keywords` | `?array<string>` | `[]` | Keywords/tags - always Vec for consistency |
| `language` | `?string` | `null` | Primary language (ISO 639 code) |
| `createdAt` | `?string` | `null` | Creation timestamp (ISO 8601 format) |
| `modifiedAt` | `?string` | `null` | Last modification timestamp (ISO 8601 format) |
| `createdBy` | `?string` | `null` | User who created the document |
| `modifiedBy` | `?string` | `null` | User who last modified the document |
| `pages` | `?PageStructure` | `null` | Page/slide/sheet structure with boundaries |
| `format` | `?FormatMetadata` | `FormatMetadata::Pdf` | Format-specific metadata (discriminated union) Contains detailed metadata specific to the document format. Serializes with a `format_type` discriminator field. |
| `imagePreprocessing` | `?ImagePreprocessingMetadata` | `null` | Image preprocessing metadata (when OCR preprocessing was applied) |
| `jsonSchema` | `?mixed` | `null` | JSON schema (for structured data extraction) |
| `error` | `?ErrorMetadata` | `null` | Error metadata (for batch operations) |
| `extractionDurationMs` | `?int` | `null` | Extraction duration in milliseconds (for benchmarking). This field is populated by batch extraction to provide per-file timing information. It's `None` for single-file extraction (which uses external timing). |
| `category` | `?string` | `null` | Document category (from frontmatter or classification). |
| `tags` | `?array<string>` | `[]` | Document tags (from frontmatter). |
| `documentVersion` | `?string` | `null` | Document version string (from frontmatter). |
| `abstractText` | `?string` | `null` | Abstract or summary text (from frontmatter). |
| `outputFormat` | `?string` | `null` | Output format identifier (e.g., "markdown", "html", "text"). Set by the output format pipeline stage when format conversion is applied. Previously stored in `metadata.additional["output_format"]`. |
| `additional` | `AHashMap` | `null` | Additional custom fields from postprocessors. **Deprecated**: Prefer using typed fields on `ExtractionResult` and `Metadata` instead of inserting into this map. Typed fields provide better cross-language compatibility and type safety. This field will be removed in a future major version. This flattened map allows Python/TypeScript postprocessors to add arbitrary fields (entity extraction, keyword extraction, etc.). Fields are merged at the root level during serialization. Uses `Cow<'static, str>` keys so static string keys avoid allocation. |


---

### MetricsLayer

A `tower::Layer` that records service-level extraction metrics.

#### Methods

##### layer()

**Signature:**

```php
public function layer(S $inner): Service
```


---

### ModelCache

#### Methods

##### put()

Return a model to the cache for reuse.

If the cache already holds a model (e.g. from a concurrent caller),
the returned model is silently dropped.

**Signature:**

```php
public function put(T $model): void
```

##### take()

Take the cached model if one exists, without creating a new one.

**Signature:**

```php
public function take(): ?T
```


---

### NodeId

Deterministic node identifier.

Generated from a hash of `node_type + text + page`. The same document
always produces the same IDs, making them useful for diffing, caching,
and external references.

#### Methods

##### generate()

Generate a deterministic `NodeId` from node content.

Uses wrapping multiplication hashing on the node type discriminant,
text content, page number, and node index to produce a stable hex identifier.
The index parameter ensures uniqueness for duplicate content on the same page.

# Parameters

- `node_type`: The node type discriminant (e.g., "paragraph", "heading")
- `text`: The text content of the node
- `page`: The page number (None becomes u64::MAX for hashing)
- `index`: The position of this node in the document's nodes array

**Signature:**

```php
public static function generate(string $nodeType, string $text, int $page, int $index): NodeId
```

##### asRef()

**Signature:**

```php
public function asRef(): string
```

##### fmt()

**Signature:**

```php
public function fmt(Formatter $f): Unknown
```


---

### NormalizeResult

Result of image normalization

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `rgbData` | `string` | — | Processed RGB image data (height * width * 3 bytes) |
| `dimensions` | `UsizeUsize` | — | Image dimensions (width, height) |
| `metadata` | `ImagePreprocessingMetadata` | — | Preprocessing metadata |


---

### Note

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `id` | `string` | — | Unique identifier |
| `noteType` | `NoteType` | — | Note type (note type) |
| `paragraphs` | `array<Paragraph>` | — | Paragraphs |


---

### NumbersExtractor

Apple Numbers spreadsheet extractor.

Supports `.numbers` files (modern iWork format, 2013+).

Extracts cell string values and sheet names from the IWA container:
ZIP → Snappy → protobuf text fields. Output is formatted as plain text
with one text token per line (representing cell values and labels).

#### Methods

##### default()

**Signature:**

```php
public static function default(): NumbersExtractor
```

##### name()

**Signature:**

```php
public function name(): string
```

##### version()

**Signature:**

```php
public function version(): string
```

##### initialize()

**Signature:**

```php
public function initialize(): void
```

##### shutdown()

**Signature:**

```php
public function shutdown(): void
```

##### description()

**Signature:**

```php
public function description(): string
```

##### author()

**Signature:**

```php
public function author(): string
```

##### extractBytes()

**Signature:**

```php
public function extractBytes(string $content, string $mimeType, ExtractionConfig $config): InternalDocument
```

##### supportedMimeTypes()

**Signature:**

```php
public function supportedMimeTypes(): array<string>
```

##### priority()

**Signature:**

```php
public function priority(): int
```


---

### OcrCache

#### Methods

##### new()

**Signature:**

```php
public static function new(string $cacheDir): OcrCache
```

##### getCachedResult()

**Signature:**

```php
public function getCachedResult(string $imageHash, string $backend, string $config): ?OcrExtractionResult
```

##### setCachedResult()

**Signature:**

```php
public function setCachedResult(string $imageHash, string $backend, string $config, OcrExtractionResult $result): void
```

##### clear()

**Signature:**

```php
public function clear(): void
```

##### getStats()

**Signature:**

```php
public function getStats(): OcrCacheStats
```


---

### OcrCacheStats

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `totalFiles` | `int` | `null` | Total files |
| `totalSizeMb` | `float` | `null` | Total size mb |


---

### OcrConfidence

Confidence scores for an OCR element.

Separates detection confidence (how confident that text exists at this location)
from recognition confidence (how confident about the actual text content).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `detection` | `?float` | `null` | Detection confidence: how confident the OCR engine is that text exists here. PaddleOCR provides this as `box_score`, Tesseract doesn't have a direct equivalent. Range: 0.0 to 1.0 (or None if not available). |
| `recognition` | `float` | — | Recognition confidence: how confident about the text content. Range: 0.0 to 1.0. |

#### Methods

##### fromTesseract()

Create confidence from Tesseract's single confidence value.

Tesseract provides confidence as 0-100, which we normalize to 0.0-1.0.

**Signature:**

```php
public static function fromTesseract(float $confidence): OcrConfidence
```

##### fromPaddle()

Create confidence from PaddleOCR scores.

Both scores should be in 0.0-1.0 range, but PaddleOCR may occasionally return
values slightly above 1.0 due to model calibration. This method clamps both
values to ensure they stay within the valid 0.0-1.0 range.

**Signature:**

```php
public static function fromPaddle(float $boxScore, float $textScore): OcrConfidence
```


---

### OcrConfig

OCR configuration.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `backend` | `string` | `null` | OCR backend: tesseract, easyocr, paddleocr |
| `language` | `string` | `null` | Language code (e.g., "eng", "deu") |
| `tesseractConfig` | `?TesseractConfig` | `null` | Tesseract-specific configuration (optional) |
| `outputFormat` | `?OutputFormat` | `OutputFormat::Plain` | Output format for OCR results (optional, for format conversion) |
| `paddleOcrConfig` | `?mixed` | `null` | PaddleOCR-specific configuration (optional, JSON passthrough) |
| `elementConfig` | `?OcrElementConfig` | `null` | OCR element extraction configuration |
| `qualityThresholds` | `?OcrQualityThresholds` | `null` | Quality thresholds for the native-text-to-OCR fallback decision. When None, uses compiled defaults (matching previous hardcoded behavior). |
| `pipeline` | `?OcrPipelineConfig` | `null` | Multi-backend OCR pipeline configuration. When set, enables weighted fallback across multiple OCR backends based on output quality. When None, uses the single `backend` field (same as today). |
| `autoRotate` | `bool` | `false` | Enable automatic page rotation based on orientation detection. When enabled, uses Tesseract's `DetectOrientationScript()` to detect page orientation (0/90/180/270 degrees) before OCR. If the page is rotated with high confidence, the image is corrected before recognition. This is critical for handling rotated scanned documents. |
| `vlmConfig` | `?LlmConfig` | `null` | VLM (Vision Language Model) OCR configuration. Required when `backend` is `"vlm"`. Uses liter-llm to send page images to a vision model for text extraction. |
| `vlmPrompt` | `?string` | `null` | Custom Jinja2 prompt template for VLM OCR. When `None`, uses the default template. Available variables: - `{{ language }}` — The document language code (e.g., "eng", "deu"). |

#### Methods

##### default()

**Signature:**

```php
public static function default(): OcrConfig
```

##### validate()

Validates that the configured backend is supported.

This method checks that the backend name is one of the supported OCR backends:
- tesseract
- easyocr
- paddleocr

Typos in backend names are caught at configuration validation time, not at runtime.
Also validates pipeline stage backends when a pipeline is configured.

**Signature:**

```php
public function validate(): void
```

##### effectiveThresholds()

Returns the effective quality thresholds, using configured values or defaults.

**Signature:**

```php
public function effectiveThresholds(): OcrQualityThresholds
```

##### effectivePipeline()

Returns the effective pipeline config.

- If `pipeline` is explicitly set, returns it.
- If `paddle-ocr` feature is compiled in and no explicit pipeline is set,
  auto-constructs a default pipeline: primary backend (priority 100) + paddleocr (priority 50).
- Otherwise returns `null` (single-backend mode, same as today).

**Signature:**

```php
public function effectivePipeline(): ?OcrPipelineConfig
```


---

### OcrElement

A unified OCR element representing detected text with full metadata.

This is the primary type for structured OCR output, preserving all information
from both Tesseract and PaddleOCR backends.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `text` | `string` | — | The recognized text content. |
| `geometry` | `OcrBoundingGeometry` | — | Bounding geometry (rectangle or quadrilateral). |
| `confidence` | `OcrConfidence` | — | Confidence scores for detection and recognition. |
| `level` | `OcrElementLevel` | — | Hierarchical level (word, line, block, page). |
| `rotation` | `?OcrRotation` | `null` | Rotation information (if detected). |
| `pageNumber` | `int` | — | Page number (1-indexed). |
| `parentId` | `?string` | `null` | Parent element ID for hierarchical relationships. Only used for Tesseract output which has word -> line -> block hierarchy. |
| `backendMetadata` | `array<string, mixed>` | — | Backend-specific metadata that doesn't fit the unified schema. |

#### Methods

##### withLevel()

Set the hierarchical level.

**Signature:**

```php
public function withLevel(OcrElementLevel $level): OcrElement
```

##### withRotation()

Set rotation information.

**Signature:**

```php
public function withRotation(OcrRotation $rotation): OcrElement
```

##### withPageNumber()

Set page number.

**Signature:**

```php
public function withPageNumber(int $pageNumber): OcrElement
```

##### withParentId()

Set parent element ID.

**Signature:**

```php
public function withParentId(string $parentId): OcrElement
```

##### withMetadata()

Add backend-specific metadata.

**Signature:**

```php
public function withMetadata(string $key, mixed $value): OcrElement
```

##### withRotationOpt()

**Signature:**

```php
public function withRotationOpt(OcrRotation $rotation): OcrElement
```


---

### OcrElementConfig

Configuration for OCR element extraction.

Controls how OCR elements are extracted and filtered.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `includeElements` | `bool` | `null` | Whether to include OCR elements in the extraction result. When true, the `ocr_elements` field in `ExtractionResult` will be populated. |
| `minLevel` | `OcrElementLevel` | `OcrElementLevel::Line` | Minimum hierarchical level to include. Elements below this level (e.g., words when min_level is Line) will be excluded. |
| `minConfidence` | `float` | `null` | Minimum recognition confidence threshold (0.0-1.0). Elements with confidence below this threshold will be filtered out. |
| `buildHierarchy` | `bool` | `null` | Whether to build hierarchical relationships between elements. When true, `parent_id` fields will be populated based on spatial containment. Only meaningful for Tesseract output. |


---

### OcrExtractionResult

OCR extraction result.

Result of performing OCR on an image or scanned document,
including recognized text and detected tables.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `content` | `string` | — | Recognized text content |
| `mimeType` | `string` | — | Original MIME type of the processed image |
| `metadata` | `array<string, mixed>` | — | OCR processing metadata (confidence scores, language, etc.) |
| `tables` | `array<OcrTable>` | — | Tables detected and extracted via OCR |
| `ocrElements` | `?array<OcrElement>` | `null` | Structured OCR elements with bounding boxes and confidence scores. Available when TSV output is requested or table detection is enabled. |
| `internalDocument` | `?InternalDocument` | `null` | Structured document produced from hOCR parsing. Carries paragraph structure, bounding boxes, and confidence scores that the flattened `content` string discards. |


---

### OcrMetadata

OCR processing metadata.

Captures information about OCR processing configuration and results.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `language` | `string` | — | OCR language code(s) used |
| `psm` | `int` | — | Tesseract Page Segmentation Mode (PSM) |
| `outputFormat` | `string` | — | Output format (e.g., "text", "hocr") |
| `tableCount` | `int` | — | Number of tables detected |
| `tableRows` | `?int` | `null` | Table rows |
| `tableCols` | `?int` | `null` | Table cols |


---

### OcrPipelineConfig

Multi-backend OCR pipeline with quality-based fallback.

Backends are tried in priority order (highest first). After each backend
produces output, quality is evaluated. If it meets `quality_thresholds.pipeline_min_quality`,
the result is accepted. Otherwise the next backend is tried.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `stages` | `array<OcrPipelineStage>` | — | Ordered list of backends to try. Sorted by priority (descending) at runtime. |
| `qualityThresholds` | `OcrQualityThresholds` | — | Quality thresholds for deciding whether to accept a result or try the next backend. |


---

### OcrPipelineStage

A single backend stage in the OCR pipeline.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `backend` | `string` | — | Backend name: "tesseract", "paddleocr", "easyocr", or a custom registered name. |
| `priority` | `int` | — | Priority weight (higher = tried first). Stages are sorted by priority descending. |
| `language` | `?string` | `null` | Language override for this stage (None = use parent OcrConfig.language). |
| `tesseractConfig` | `?TesseractConfig` | `null` | Tesseract-specific config override for this stage. |
| `paddleOcrConfig` | `?mixed` | `null` | PaddleOCR-specific config for this stage. |
| `vlmConfig` | `?LlmConfig` | `null` | VLM config override for this pipeline stage. |


---

### OcrProcessor

#### Methods

##### new()

**Signature:**

```php
public static function new(string $cacheDir): OcrProcessor
```

##### processImage()

**Signature:**

```php
public function processImage(string $imageBytes, TesseractConfig $config): OcrExtractionResult
```

##### processImageWithFormat()

Process an image with OCR and respect the output format from ExtractionConfig.

This variant allows specifying an output format (Plain, Markdown, Djot) which
affects how the OCR result's mime_type is set when markdown output is requested.

**Signature:**

```php
public function processImageWithFormat(string $imageBytes, TesseractConfig $config, OutputFormat $outputFormat): OcrExtractionResult
```

##### clearCache()

**Signature:**

```php
public function clearCache(): void
```

##### getCacheStats()

**Signature:**

```php
public function getCacheStats(): OcrCacheStats
```

##### processImageFile()

**Signature:**

```php
public function processImageFile(string $filePath, TesseractConfig $config): OcrExtractionResult
```

##### processImageFileWithFormat()

Process a file with OCR and respect the output format from ExtractionConfig.

This variant allows specifying an output format (Plain, Markdown, Djot) which
affects how the OCR result's mime_type is set when markdown output is requested.

**Signature:**

```php
public function processImageFileWithFormat(string $filePath, TesseractConfig $config, OutputFormat $outputFormat): OcrExtractionResult
```

##### processImageFilesBatch()

Process multiple image files in parallel using Rayon.

This method processes OCR operations in parallel across CPU cores for improved throughput.
Results are returned in the same order as the input file paths.

**Signature:**

```php
public function processImageFilesBatch(array<string> $filePaths, TesseractConfig $config): array<BatchItemResult>
```


---

### OcrQualityThresholds

Quality thresholds for OCR fallback decisions and pipeline quality gating.

All fields default to the values that match the previous hardcoded behavior,
so `OcrQualityThresholds::default()` preserves existing semantics exactly.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `minTotalNonWhitespace` | `int` | `64` | Minimum total non-whitespace characters to consider text substantive. |
| `minNonWhitespacePerPage` | `float` | `32` | Minimum non-whitespace characters per page on average. |
| `minMeaningfulWordLen` | `int` | `4` | Minimum character count for a word to be "meaningful". |
| `minMeaningfulWords` | `int` | `3` | Minimum count of meaningful words before text is accepted. |
| `minAlnumRatio` | `float` | `0.3` | Minimum alphanumeric ratio (non-whitespace chars that are alphanumeric). |
| `minGarbageChars` | `int` | `5` | Minimum Unicode replacement characters (U+FFFD) to trigger OCR fallback. |
| `maxFragmentedWordRatio` | `float` | `0.6` | Maximum fraction of short (1-2 char) words before text is considered fragmented. |
| `criticalFragmentedWordRatio` | `float` | `0.8` | Critical fragmentation threshold — triggers OCR regardless of meaningful words. Normal English text has ~20-30% short words. 80%+ is definitive garbage. |
| `minAvgWordLength` | `float` | `2` | Minimum average word length. Below this with enough words indicates garbled extraction. |
| `minWordsForAvgLengthCheck` | `int` | `50` | Minimum word count before average word length check applies. |
| `minConsecutiveRepeatRatio` | `float` | `0.08` | Minimum consecutive word repetition ratio to detect column scrambling. |
| `minWordsForRepeatCheck` | `int` | `50` | Minimum word count before consecutive repetition check is applied. |
| `substantiveMinChars` | `int` | `100` | Minimum character count for "substantive markdown" OCR skip gate. |
| `nonTextMinChars` | `int` | `20` | Minimum character count for "non-text content" OCR skip gate. |
| `alnumWsRatioThreshold` | `float` | `0.4` | Alphanumeric+whitespace ratio threshold for skip decisions. |
| `pipelineMinQuality` | `float` | `0.5` | Minimum quality score (0.0-1.0) for a pipeline stage result to be accepted. If the result from a backend scores below this, try the next backend. |

#### Methods

##### default()

**Signature:**

```php
public static function default(): OcrQualityThresholds
```


---

### OcrRotation

Rotation information for an OCR element.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `angleDegrees` | `float` | — | Rotation angle in degrees (0, 90, 180, 270 for PaddleOCR). |
| `confidence` | `?float` | `null` | Confidence score for the rotation detection. |

#### Methods

##### fromPaddle()

Create rotation from PaddleOCR angle classification.

PaddleOCR uses angle_index (0-3) representing 0, 90, 180, 270 degrees.

**Errors:**

Returns an error if `angle_index` is not in the valid range (0-3).

**Signature:**

```php
public static function fromPaddle(int $angleIndex, float $angleScore): OcrRotation
```


---

### OcrTable

Table detected via OCR.

Represents a table structure recognized during OCR processing.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `cells` | `array<array<string>>` | — | Table cells as a 2D vector (rows × columns) |
| `markdown` | `string` | — | Markdown representation of the table |
| `pageNumber` | `int` | — | Page number where the table was found (1-indexed) |
| `boundingBox` | `?OcrTableBoundingBox` | `null` | Bounding box of the table in pixel coordinates (from OCR word positions). |


---

### OcrTableBoundingBox

Bounding box for an OCR-detected table in pixel coordinates.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `left` | `int` | — | Left x-coordinate (pixels) |
| `top` | `int` | — | Top y-coordinate (pixels) |
| `right` | `int` | — | Right x-coordinate (pixels) |
| `bottom` | `int` | — | Bottom y-coordinate (pixels) |


---

### OdtExtractor

High-performance ODT extractor using native Rust XML parsing.

This extractor provides:
- Fast text extraction via roxmltree XML parsing
- Comprehensive metadata extraction from meta.xml
- Table extraction with row and cell support
- Formatting preservation (bold, italic, strikeout)
- Support for headings, paragraphs, and special elements

#### Methods

##### default()

**Signature:**

```php
public static function default(): OdtExtractor
```

##### name()

**Signature:**

```php
public function name(): string
```

##### version()

**Signature:**

```php
public function version(): string
```

##### initialize()

**Signature:**

```php
public function initialize(): void
```

##### shutdown()

**Signature:**

```php
public function shutdown(): void
```

##### description()

**Signature:**

```php
public function description(): string
```

##### author()

**Signature:**

```php
public function author(): string
```

##### extractBytes()

**Signature:**

```php
public function extractBytes(string $content, string $mimeType, ExtractionConfig $config): InternalDocument
```

##### supportedMimeTypes()

**Signature:**

```php
public function supportedMimeTypes(): array<string>
```

##### priority()

**Signature:**

```php
public function priority(): int
```


---

### OdtProperties

OpenDocument metadata from meta.xml

Contains metadata fields defined by the OASIS OpenDocument Format standard.
Uses Dublin Core elements (dc:) and OpenDocument meta elements (meta:).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `title` | `?string` | `null` | Document title (dc:title) |
| `subject` | `?string` | `null` | Document subject/topic (dc:subject) |
| `creator` | `?string` | `null` | Current document creator/author (dc:creator) |
| `initialCreator` | `?string` | `null` | Initial creator of the document (meta:initial-creator) |
| `keywords` | `?string` | `null` | Keywords or tags (meta:keyword) |
| `description` | `?string` | `null` | Document description (dc:description) |
| `date` | `?string` | `null` | Current modification date (dc:date) |
| `creationDate` | `?string` | `null` | Initial creation date (meta:creation-date) |
| `language` | `?string` | `null` | Document language (dc:language) |
| `generator` | `?string` | `null` | Generator/application that created the document (meta:generator) |
| `editingDuration` | `?string` | `null` | Editing duration in ISO 8601 format (meta:editing-duration) |
| `editingCycles` | `?string` | `null` | Number of edits/revisions (meta:editing-cycles) |
| `pageCount` | `?int` | `null` | Document statistics - page count (meta:page-count) |
| `wordCount` | `?int` | `null` | Document statistics - word count (meta:word-count) |
| `characterCount` | `?int` | `null` | Document statistics - character count (meta:character-count) |
| `paragraphCount` | `?int` | `null` | Document statistics - paragraph count (meta:paragraph-count) |
| `tableCount` | `?int` | `null` | Document statistics - table count (meta:table-count) |
| `imageCount` | `?int` | `null` | Document statistics - image count (meta:image-count) |


---

### OrgModeExtractor

Org Mode document extractor.

Provides native Rust-based Org Mode extraction using the `org` library,
extracting structured content and metadata.

#### Methods

##### buildInternalDocument()

Build an `InternalDocument` from Org Mode source text.

Handles headings, paragraphs, lists, code blocks, tables, inline links,
and footnote references.

**Signature:**

```php
public static function buildInternalDocument(string $orgText): InternalDocument
```

##### default()

**Signature:**

```php
public static function default(): OrgModeExtractor
```

##### name()

**Signature:**

```php
public function name(): string
```

##### version()

**Signature:**

```php
public function version(): string
```

##### initialize()

**Signature:**

```php
public function initialize(): void
```

##### shutdown()

**Signature:**

```php
public function shutdown(): void
```

##### description()

**Signature:**

```php
public function description(): string
```

##### author()

**Signature:**

```php
public function author(): string
```

##### extractBytes()

**Signature:**

```php
public function extractBytes(string $content, string $mimeType, ExtractionConfig $config): InternalDocument
```

##### extractFile()

**Signature:**

```php
public function extractFile(string $path, string $mimeType, ExtractionConfig $config): InternalDocument
```

##### supportedMimeTypes()

**Signature:**

```php
public function supportedMimeTypes(): array<string>
```

##### priority()

**Signature:**

```php
public function priority(): int
```


---

### OrientationResult

Document orientation detection result.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `degrees` | `int` | — | Detected orientation in degrees (0, 90, 180, or 270). |
| `confidence` | `float` | — | Confidence score (0.0-1.0). |


---

### PageBoundary

Byte offset boundary for a page.

Tracks where a specific page's content starts and ends in the main content string,
enabling mapping from byte positions to page numbers. Offsets are guaranteed to be
at valid UTF-8 character boundaries when using standard String methods (push_str, push, etc.).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `byteStart` | `int` | — | Byte offset where this page starts in the content string (UTF-8 valid boundary, inclusive) |
| `byteEnd` | `int` | — | Byte offset where this page ends in the content string (UTF-8 valid boundary, exclusive) |
| `pageNumber` | `int` | — | Page number (1-indexed) |


---

### PageConfig

Page extraction and tracking configuration.

Controls how pages are extracted, tracked, and represented in the extraction results.
When `null`, page tracking is disabled.

Page range tracking in chunk metadata (first_page/last_page) is automatically enabled
when page boundaries are available and chunking is configured.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `extractPages` | `bool` | `false` | Extract pages as separate array (ExtractionResult.pages) |
| `insertPageMarkers` | `bool` | `false` | Insert page markers in main content string |
| `markerFormat` | `string` | `"

<!-- PAGE {page_num} -->

"` | Page marker format (use {page_num} placeholder) Default: "\n\n<!-- PAGE {page_num} -->\n\n" |

#### Methods

##### default()

**Signature:**

```php
public static function default(): PageConfig
```


---

### PageContent

Content for a single page/slide.

When page extraction is enabled, documents are split into per-page content
with associated tables and images mapped to each page.

# Performance

Uses Arc-wrapped tables and images for memory efficiency:
- `Vec<Arc<Table>>` enables zero-copy sharing of table data
- `Vec<Arc<ExtractedImage>>` enables zero-copy sharing of image data
- Maintains exact JSON compatibility via custom Serialize/Deserialize

This reduces memory overhead for documents with shared tables/images
by avoiding redundant copies during serialization.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `pageNumber` | `int` | — | Page number (1-indexed) |
| `content` | `string` | — | Text content for this page |
| `tables` | `array<Table>` | — | Tables found on this page (uses Arc for memory efficiency) Serializes as Vec<Table> for JSON compatibility while maintaining Arc semantics in-memory for zero-copy sharing. |
| `images` | `array<ExtractedImage>` | — | Images found on this page (uses Arc for memory efficiency) Serializes as Vec<ExtractedImage> for JSON compatibility while maintaining Arc semantics in-memory for zero-copy sharing. |
| `hierarchy` | `?PageHierarchy` | `null` | Hierarchy information for the page (when hierarchy extraction is enabled) Contains text hierarchy levels (H1-H6) extracted from the page content. |
| `isBlank` | `?bool` | `null` | Whether this page is blank (no meaningful text content) Determined during extraction based on text content analysis. A page is blank if it has fewer than 3 non-whitespace characters and contains no tables or images. |


---

### PageHierarchy

Page hierarchy structure containing heading levels and block information.

Used when PDF text hierarchy extraction is enabled. Contains hierarchical
blocks with heading levels (H1-H6) for semantic document structure.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `blockCount` | `int` | — | Number of hierarchy blocks on this page |
| `blocks` | `array<HierarchicalBlock>` | — | Hierarchical blocks with heading levels |


---

### PageInfo

Metadata for individual page/slide/sheet.

Captures per-page information including dimensions, content counts,
and visibility state (for presentations).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `number` | `int` | — | Page number (1-indexed) |
| `title` | `?string` | `null` | Page title (usually for presentations) |
| `dimensions` | `?F64F64` | `null` | Dimensions in points (PDF) or pixels (images): (width, height) |
| `imageCount` | `?int` | `null` | Number of images on this page |
| `tableCount` | `?int` | `null` | Number of tables on this page |
| `hidden` | `?bool` | `null` | Whether this page is hidden (e.g., in presentations) |
| `isBlank` | `?bool` | `null` | Whether this page is blank (no meaningful text, no images, no tables) A page is considered blank if it has fewer than 3 non-whitespace characters and contains no tables or images. This is useful for filtering out empty pages in scanned documents or PDFs with blank separator pages. |


---

### PageLayoutRegion

A detected layout region mapped to PDF coordinate space.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `class` | `LayoutClass` | — | Class (layout class) |
| `confidence` | `float` | — | Confidence |
| `bbox` | `PdfLayoutBBox` | — | Bbox (pdf layout b box) |


---

### PageLayoutResult

Layout detection results for a single page.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `pageIndex` | `int` | — | Page index |
| `regions` | `array<PageLayoutRegion>` | — | Regions |
| `pageWidthPts` | `float` | — | Page width pts |
| `pageHeightPts` | `float` | — | Page height pts |
| `renderWidthPx` | `int` | — | Width of the rendered image used for layout detection (pixels). |
| `renderHeightPx` | `int` | — | Height of the rendered image used for layout detection (pixels). |


---

### PageMargins

Page margins in twips (twentieths of a point).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `top` | `?int` | `null` | Top margin in twips. |
| `right` | `?int` | `null` | Right margin in twips. |
| `bottom` | `?int` | `null` | Bottom margin in twips. |
| `left` | `?int` | `null` | Left margin in twips. |
| `header` | `?int` | `null` | Header offset in twips. |
| `footer` | `?int` | `null` | Footer offset in twips. |
| `gutter` | `?int` | `null` | Gutter margin in twips. |

#### Methods

##### toPoints()

Convert all margins from twips to points.

Conversion factor: 1 twip = 1/20 point, or equivalently divide by 20.

**Signature:**

```php
public function toPoints(): PageMarginsPoints
```


---

### PageMarginsPoints

Page margins converted to points (1/72 inch).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `top` | `?float` | `null` | Top |
| `right` | `?float` | `null` | Right |
| `bottom` | `?float` | `null` | Bottom |
| `left` | `?float` | `null` | Left |
| `header` | `?float` | `null` | Header |
| `footer` | `?float` | `null` | Footer |
| `gutter` | `?float` | `null` | Gutter |


---

### PageRenderOptions

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `targetDpi` | `int` | `300` | Target dpi |
| `maxImageDimension` | `int` | `65536` | Maximum image dimension |
| `autoAdjustDpi` | `bool` | `true` | Auto adjust dpi |
| `minDpi` | `int` | `72` | Minimum dpi |
| `maxDpi` | `int` | `600` | Maximum dpi |

#### Methods

##### default()

**Signature:**

```php
public static function default(): PageRenderOptions
```


---

### PageStructure

Unified page structure for documents.

Supports different page types (PDF pages, PPTX slides, Excel sheets)
with character offset boundaries for chunk-to-page mapping.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `totalCount` | `int` | — | Total number of pages/slides/sheets |
| `unitType` | `PageUnitType` | — | Type of paginated unit |
| `boundaries` | `?array<PageBoundary>` | `null` | Character offset boundaries for each page Maps character ranges in the extracted content to page numbers. Used for chunk page range calculation. |
| `pages` | `?array<PageInfo>` | `null` | Detailed per-page metadata (optional, only when needed) |


---

### PageTiming

Timing breakdown for a single page.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `renderMs` | `float` | — | Time to render the PDF page to a raster image (amortized from batch render). |
| `preprocessMs` | `float` | — | Time spent in image preprocessing (resize, normalize, tensor construction). |
| `onnxMs` | `float` | — | Time for the ONNX model session.run() call (actual neural network inference). |
| `inferenceMs` | `float` | — | Total model inference time (preprocess + onnx), as measured by the engine. |
| `postprocessMs` | `float` | — | Time spent in postprocessing (confidence filtering, overlap resolution). |
| `mappingMs` | `float` | — | Time to map pixel-space bounding boxes to PDF coordinate space. |


---

### PagesExtractor

Apple Pages document extractor.

Supports `.pages` files (modern iWork format, 2013+).

Extracts all text content from the document by parsing the IWA
(iWork Archive) container: ZIP → Snappy → protobuf text fields.

#### Methods

##### default()

**Signature:**

```php
public static function default(): PagesExtractor
```

##### name()

**Signature:**

```php
public function name(): string
```

##### version()

**Signature:**

```php
public function version(): string
```

##### initialize()

**Signature:**

```php
public function initialize(): void
```

##### shutdown()

**Signature:**

```php
public function shutdown(): void
```

##### description()

**Signature:**

```php
public function description(): string
```

##### author()

**Signature:**

```php
public function author(): string
```

##### extractBytes()

**Signature:**

```php
public function extractBytes(string $content, string $mimeType, ExtractionConfig $config): InternalDocument
```

##### supportedMimeTypes()

**Signature:**

```php
public function supportedMimeTypes(): array<string>
```

##### priority()

**Signature:**

```php
public function priority(): int
```


---

### PanicContext

Context information captured when a panic occurs.

This struct stores detailed information about where and when a panic happened,
enabling better error reporting across FFI boundaries.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `file` | `string` | — | Source file where the panic occurred |
| `line` | `int` | — | Line number where the panic occurred |
| `function` | `string` | — | Function name where the panic occurred |
| `message` | `string` | — | Panic message extracted from the panic payload |
| `timestamp` | `SystemTime` | — | Timestamp when the panic was captured |

#### Methods

##### format()

Formats the panic context as a human-readable string.

**Signature:**

```php
public function format(): string
```


---

### ParaText

Plain text content decoded from a ParaText record (tag 0x43).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `content` | `string` | — | The extracted text content |

#### Methods

##### fromRecord()

Decode a ParaText record from raw bytes.

The data field of a TAG_PARA_TEXT record is a sequence of UTF-16LE code
units.  Control characters < 0x0020 are mapped to whitespace or skipped;
characters in the private-use range 0xF020–0xF07F (HWP internal controls)
are discarded.

**Signature:**

```php
public static function fromRecord(Record $record): ParaText
```


---

### Paragraph

A single paragraph; may or may not carry a text payload.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `text` | `?ParaText` | `null` | Text (para text) |


---

### ParagraphProperties

Paragraph-level formatting properties (alignment, spacing, indentation, etc.).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `alignment` | `?string` | `null` | `"left"`, `"center"`, `"right"`, `"both"` (justified). |
| `spacingBefore` | `?int` | `null` | Spacing before paragraph in twips. |
| `spacingAfter` | `?int` | `null` | Spacing after paragraph in twips. |
| `spacingLine` | `?int` | `null` | Line spacing in twips or 240ths of a line. |
| `spacingLineRule` | `?string` | `null` | Line spacing rule: "auto", "exact", or "atLeast". |
| `indentLeft` | `?int` | `null` | Left indentation in twips. |
| `indentRight` | `?int` | `null` | Right indentation in twips. |
| `indentFirstLine` | `?int` | `null` | First-line indentation in twips. |
| `indentHanging` | `?int` | `null` | Hanging indentation in twips. |
| `outlineLevel` | `?int` | `null` | Outline level 0-8 for heading levels. |
| `keepNext` | `?bool` | `null` | Keep with next paragraph on same page. |
| `keepLines` | `?bool` | `null` | Keep all lines of paragraph on same page. |
| `pageBreakBefore` | `?bool` | `null` | Force page break before paragraph. |
| `widowControl` | `?bool` | `null` | Prevent widow/orphan lines. |
| `suppressAutoHyphens` | `?bool` | `null` | Suppress automatic hyphenation. |
| `bidi` | `?bool` | `null` | Right-to-left paragraph direction. |
| `shadingFill` | `?string` | `null` | Background color hex value (from w:shd w:fill). |
| `shadingVal` | `?string` | `null` | Shading pattern value (from w:shd w:val). |
| `borderTop` | `?string` | `null` | Top border style (from w:pBdr/w:top w:val). |
| `borderBottom` | `?string` | `null` | Bottom border style (from w:pBdr/w:bottom w:val). |
| `borderLeft` | `?string` | `null` | Left border style (from w:pBdr/w:left w:val). |
| `borderRight` | `?string` | `null` | Right border style (from w:pBdr/w:right w:val). |


---

### PdfAnnotation

A PDF annotation extracted from a document page.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `annotationType` | `PdfAnnotationType` | — | The type of annotation. |
| `content` | `?string` | `null` | Text content of the annotation (e.g., comment text, link URL). |
| `pageNumber` | `int` | — | Page number where the annotation appears (1-indexed). |
| `boundingBox` | `?BoundingBox` | `null` | Bounding box of the annotation on the page. |


---

### PdfConfig

PDF-specific configuration.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `backend` | `PdfBackend` | `PdfBackend::Pdfium` | PDF extraction backend. Default: `Pdfium`. |
| `extractImages` | `bool` | `false` | Extract images from PDF |
| `passwords` | `?array<string>` | `[]` | List of passwords to try when opening encrypted PDFs |
| `extractMetadata` | `bool` | `true` | Extract PDF metadata |
| `hierarchy` | `?HierarchyConfig` | `null` | Hierarchy extraction configuration (None = hierarchy extraction disabled) |
| `extractAnnotations` | `bool` | `false` | Extract PDF annotations (text notes, highlights, links, stamps). Default: false |
| `topMarginFraction` | `?float` | `null` | Top margin fraction (0.0–1.0) of page height to exclude headers/running heads. Default: 0.06 (6%) |
| `bottomMarginFraction` | `?float` | `null` | Bottom margin fraction (0.0–1.0) of page height to exclude footers/page numbers. Default: 0.05 (5%) |
| `allowSingleColumnTables` | `bool` | `false` | Allow single-column pseudo tables in extraction results. By default, tables with fewer than 2 columns (layout-guided) or 3 columns (heuristic) are rejected. When `True`, the minimum column count is relaxed to 1, allowing single-column structured data (glossaries, itemized lists) to be emitted as tables. Other quality filters (density, sparsity, prose detection) still apply. |

#### Methods

##### default()

**Signature:**

```php
public static function default(): PdfConfig
```


---

### PdfExtractionMetadata

Complete PDF extraction metadata including common and PDF-specific fields.

This struct combines common document fields (title, authors, dates) with
PDF-specific metadata and optional page structure information. It is returned
by `extract_metadata_from_document()` when page boundaries are provided.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `title` | `?string` | `null` | Document title |
| `subject` | `?string` | `null` | Document subject or description |
| `authors` | `?array<string>` | `null` | Document authors (parsed from PDF Author field) |
| `keywords` | `?array<string>` | `null` | Document keywords (parsed from PDF Keywords field) |
| `createdAt` | `?string` | `null` | Creation timestamp (ISO 8601 format) |
| `modifiedAt` | `?string` | `null` | Last modification timestamp (ISO 8601 format) |
| `createdBy` | `?string` | `null` | Application or user that created the document |
| `pdfSpecific` | `PdfMetadata` | — | PDF-specific metadata |
| `pageStructure` | `?PageStructure` | `null` | Page structure with boundaries and optional per-page metadata |


---

### PdfExtractor

PDF document extractor using pypdfium2 and playa-pdf.

#### Methods

##### default()

**Signature:**

```php
public static function default(): PdfExtractor
```

##### name()

**Signature:**

```php
public function name(): string
```

##### version()

**Signature:**

```php
public function version(): string
```

##### initialize()

**Signature:**

```php
public function initialize(): void
```

##### shutdown()

**Signature:**

```php
public function shutdown(): void
```

##### extractBytes()

**Signature:**

```php
public function extractBytes(string $content, string $mimeType, ExtractionConfig $config): InternalDocument
```

##### supportedMimeTypes()

**Signature:**

```php
public function supportedMimeTypes(): array<string>
```


---

### PdfImage

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `pageNumber` | `int` | — | Page number |
| `imageIndex` | `int` | — | Image index |
| `width` | `int` | — | Width |
| `height` | `int` | — | Height |
| `colorSpace` | `?string` | `null` | Color space |
| `bitsPerComponent` | `?int` | `null` | Bits per component |
| `filters` | `array<string>` | — | Original PDF stream filters (e.g. `["FlateDecode"]`, `["DCTDecode"]`). |
| `data` | `string` | — | The decoded image bytes in a standard format (JPEG, PNG, etc.). |
| `decodedFormat` | `string` | — | The format of `data` after decoding: `"jpeg"`, `"png"`, `"jpeg2000"`, `"ccitt"`, or `"raw"`. |


---

### PdfImageExtractor

#### Methods

##### new()

**Signature:**

```php
public static function new(string $pdfBytes): PdfImageExtractor
```

##### newWithPassword()

**Signature:**

```php
public static function newWithPassword(string $pdfBytes, string $password): PdfImageExtractor
```

##### extractImages()

**Signature:**

```php
public function extractImages(): array<PdfImage>
```

##### extractImagesFromPage()

**Signature:**

```php
public function extractImagesFromPage(int $pageNumber): array<PdfImage>
```

##### getImageCount()

**Signature:**

```php
public function getImageCount(): int
```


---

### PdfLayoutBBox

Bounding box in PDF coordinate space (points, y=0 at bottom of page).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `left` | `float` | — | Left |
| `bottom` | `float` | — | Bottom |
| `right` | `float` | — | Right |
| `top` | `float` | — | Top |

#### Methods

##### width()

**Signature:**

```php
public function width(): float
```

##### height()

**Signature:**

```php
public function height(): float
```


---

### PdfMetadata

PDF-specific metadata.

Contains metadata fields specific to PDF documents that are not in the common
`Metadata` structure. Common fields like title, authors, keywords, and dates
are now at the `Metadata` level.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `pdfVersion` | `?string` | `null` | PDF version (e.g., "1.7", "2.0") |
| `producer` | `?string` | `null` | PDF producer (application that created the PDF) |
| `isEncrypted` | `?bool` | `null` | Whether the PDF is encrypted/password-protected |
| `width` | `?int` | `null` | First page width in points (1/72 inch) |
| `height` | `?int` | `null` | First page height in points (1/72 inch) |
| `pageCount` | `?int` | `null` | Total number of pages in the PDF document |


---

### PdfPageIterator

Lazy page-by-page PDF renderer.

Reads the file once at construction and yields one PNG-encoded page per
`next()` call. Only one rendered page is held in memory at a time.

The PDFium mutex is acquired and released per page, so other PDF
operations can proceed between iterations. This makes the iterator
safe to use in long-running loops (e.g., sending each page to a vision
model for OCR) without blocking all PDF processing.

Use the iterator when memory is a concern or when you want to process
pages as they are rendered.

#### Methods

##### new()

Create an iterator from raw PDF bytes.

Validates the PDF and determines the page count. The PDF bytes are
owned by the iterator — the file is not re-read from disk.

**Errors:**

Returns an error if the PDF is invalid or password-protected without
the correct password.

**Signature:**

```php
public static function new(string $pdfBytes, int $dpi, string $password): PdfPageIterator
```

##### fromFile()

Create an iterator from a file path.

Reads the file into memory once. Subsequent iterations render from
the owned bytes without re-reading the file.

**Errors:**

Returns an error if the file cannot be read or the PDF is invalid.

**Signature:**

```php
public static function fromFile(Path $path, int $dpi, string $password): PdfPageIterator
```

##### pageCount()

Number of pages in the PDF.

**Signature:**

```php
public function pageCount(): int
```

##### next()

**Signature:**

```php
public function next(): ?Item
```

##### sizeHint()

**Signature:**

```php
public function sizeHint(): UsizeOptionUsize
```


---

### PdfRenderer

#### Methods

##### new()

**Signature:**

```php
public static function new(): PdfRenderer
```


---

### PdfTextExtractor

#### Methods

##### new()

**Signature:**

```php
public static function new(): PdfTextExtractor
```


---

### PdfUnifiedExtractionResult

Result type for unified PDF text and metadata extraction.

Contains text, optional page boundaries, optional per-page content, and metadata.


---

### PlainTextExtractor

Plain text extractor.

Extracts content from plain text files (.txt).

#### Methods

##### default()

**Signature:**

```php
public static function default(): PlainTextExtractor
```

##### name()

**Signature:**

```php
public function name(): string
```

##### version()

**Signature:**

```php
public function version(): string
```

##### initialize()

**Signature:**

```php
public function initialize(): void
```

##### shutdown()

**Signature:**

```php
public function shutdown(): void
```

##### description()

**Signature:**

```php
public function description(): string
```

##### author()

**Signature:**

```php
public function author(): string
```

##### extractBytes()

**Signature:**

```php
public function extractBytes(string $content, string $mimeType, ExtractionConfig $config): InternalDocument
```

##### supportedMimeTypes()

**Signature:**

```php
public function supportedMimeTypes(): array<string>
```

##### priority()

**Signature:**

```php
public function priority(): int
```


---

### Plugin

Base trait that all plugins must implement.

This trait provides common functionality for plugin lifecycle management,
identification, and metadata.

# Thread Safety

All plugins must be `Send + Sync` to support concurrent usage across threads.

#### Methods

##### name()

Returns the unique name/identifier for this plugin.

The name should be:
- Unique across all plugins
- Lowercase with hyphens (e.g., "my-custom-plugin")
- URL-safe characters only

**Signature:**

```php
public function name(): string
```

##### version()

Returns the semantic version of this plugin.

Should follow semver format: `MAJOR.MINOR.PATCH`

**Signature:**

```php
public function version(): string
```

##### initialize()

Initialize the plugin.

Called once when the plugin is registered. Use this to:
- Load configuration
- Initialize resources (connections, caches, etc.)
- Validate dependencies

# Thread Safety

This method takes `&self` instead of `&mut self` to work with `Arc<dyn Plugin>`.
Plugins needing mutable state during initialization should use interior mutability
patterns (Mutex, RwLock, OnceCell, etc.).

**Errors:**

Should return an error if initialization fails. The plugin will not be
registered if this method returns an error.

**Signature:**

```php
public function initialize(): void
```

##### shutdown()

Shutdown the plugin.

Called when the plugin is being unregistered or the application is shutting down.
Use this to:
- Close connections
- Flush caches
- Release resources

# Thread Safety

This method takes `&self` instead of `&mut self` to work with `Arc<dyn Plugin>`.
Plugins needing mutable state during shutdown should use interior mutability
patterns (Mutex, RwLock, etc.).

**Errors:**

Errors during shutdown are logged but don't prevent the shutdown process.

**Signature:**

```php
public function shutdown(): void
```

##### description()

Optional plugin description for debugging and logging.

Defaults to empty string if not overridden.

**Signature:**

```php
public function description(): string
```

##### author()

Optional plugin author information.

Defaults to empty string if not overridden.

**Signature:**

```php
public function author(): string
```


---

### PluginHealthStatus

Plugin health status information.

Contains diagnostic information about registered plugins for each type.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `ocrBackendsCount` | `int` | — | Number of registered OCR backends |
| `ocrBackends` | `array<string>` | — | Names of registered OCR backends |
| `extractorsCount` | `int` | — | Number of registered document extractors |
| `extractors` | `array<string>` | — | Names of registered document extractors |
| `postProcessorsCount` | `int` | — | Number of registered post-processors |
| `postProcessors` | `array<string>` | — | Names of registered post-processors |
| `validatorsCount` | `int` | — | Number of registered validators |
| `validators` | `array<string>` | — | Names of registered validators |

#### Methods

##### check()

Check plugin health and return status.

This function reads all plugin registries and collects information
about registered plugins. It logs warnings if critical plugins are missing.

**Returns:**

`PluginHealthStatus` with counts and names of all registered plugins.

**Signature:**

```php
public static function check(): PluginHealthStatus
```


---

### Pool

#### Methods

##### acquire()

Acquire an object from the pool or create a new one if empty.

**Returns:**

A `PoolGuard<T>` that will return the object to the pool when dropped.

**Panics:**

Panics if the mutex is already locked by the current thread (deadlock).
This is a safety mechanism provided by parking_lot to prevent subtle bugs.

**Signature:**

```php
public function acquire(): PoolGuard
```

##### size()

Get the current number of objects in the pool.

**Signature:**

```php
public function size(): int
```

##### clear()

Clear the pool, discarding all pooled objects.

**Signature:**

```php
public function clear(): void
```


---

### PoolMetrics

Metrics tracking for pool allocations and reuse patterns.

These metrics help identify pool efficiency and allocation patterns.
Only available when the `pool-metrics` feature is enabled.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `totalAcquires` | `AtomicUsize` | `null` | Total number of acquire calls on this pool |
| `totalCacheHits` | `AtomicUsize` | `null` | Total number of cache hits (reused objects from pool) |
| `peakItemsStored` | `AtomicUsize` | `null` | Peak number of objects stored simultaneously in this pool |
| `totalCreations` | `AtomicUsize` | `null` | Total number of objects created by the factory function |

#### Methods

##### hitRate()

Calculate the cache hit rate as a percentage (0.0-100.0).

**Signature:**

```php
public function hitRate(): float
```

##### snapshot()

Get all metrics as a struct for reporting.

**Signature:**

```php
public function snapshot(): PoolMetricsSnapshot
```

##### reset()

Reset all metrics to zero.

**Signature:**

```php
public function reset(): void
```

##### default()

**Signature:**

```php
public static function default(): PoolMetrics
```


---

### PoolMetricsSnapshot

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `totalAcquires` | `int` | — | Total acquires |
| `totalCacheHits` | `int` | — | Total cache hits |
| `peakItemsStored` | `int` | — | Peak items stored |
| `totalCreations` | `int` | — | Total creations |


---

### PoolSizeHint

Hint for optimal pool sizing based on document characteristics.

This struct contains the estimated sizes for string and byte buffers
that should be allocated in the pool to handle extraction without
excessive reallocation.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `estimatedTotalSize` | `int` | — | Estimated total string buffer pool size in bytes |
| `stringBufferCount` | `int` | — | Recommended number of string buffers |
| `stringBufferCapacity` | `int` | — | Recommended capacity per string buffer in bytes |
| `byteBufferCount` | `int` | — | Recommended number of byte buffers |
| `byteBufferCapacity` | `int` | — | Recommended capacity per byte buffer in bytes |

#### Methods

##### estimatedStringPoolMemory()

Calculate the estimated string pool memory in bytes.

This is the total estimated memory for all string buffers.

**Signature:**

```php
public function estimatedStringPoolMemory(): int
```

##### estimatedBytePoolMemory()

Calculate the estimated byte pool memory in bytes.

This is the total estimated memory for all byte buffers.

**Signature:**

```php
public function estimatedBytePoolMemory(): int
```

##### totalPoolMemory()

Calculate the total estimated pool memory in bytes.

This includes both string and byte buffer pools.

**Signature:**

```php
public function totalPoolMemory(): int
```


---

### Position

Horizontal or vertical position.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `relativeFrom` | `string` | — | Relative from |
| `offset` | `?int` | `null` | Offset |


---

### PostProcessorConfig

Post-processor configuration.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `enabled` | `bool` | `true` | Enable post-processors |
| `enabledProcessors` | `?array<string>` | `[]` | Whitelist of processor names to run (None = all enabled) |
| `disabledProcessors` | `?array<string>` | `[]` | Blacklist of processor names to skip (None = none disabled) |
| `enabledSet` | `?AHashSet` | `null` | Pre-computed AHashSet for O(1) enabled processor lookup |
| `disabledSet` | `?AHashSet` | `null` | Pre-computed AHashSet for O(1) disabled processor lookup |

#### Methods

##### buildLookupSets()

Pre-compute HashSets for O(1) processor name lookups.

This method converts the enabled/disabled processor Vec to HashSet
for constant-time lookups in the pipeline.

**Signature:**

```php
public function buildLookupSets(): void
```

##### default()

**Signature:**

```php
public static function default(): PostProcessorConfig
```


---

### PptExtractionResult

Result of PPT text extraction.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `text` | `string` | — | Extracted text content, with slides separated by double newlines. |
| `slideCount` | `int` | — | Number of slides found. |
| `metadata` | `PptMetadata` | — | Document metadata. |
| `speakerNotes` | `array<string>` | — | Speaker notes text per slide (if available). |


---

### PptExtractor

Native PPT extractor using OLE/CFB parsing.

This extractor handles PowerPoint 97-2003 binary (.ppt) files without
requiring LibreOffice, providing ~50x faster extraction.

#### Methods

##### default()

**Signature:**

```php
public static function default(): PptExtractor
```

##### name()

**Signature:**

```php
public function name(): string
```

##### version()

**Signature:**

```php
public function version(): string
```

##### initialize()

**Signature:**

```php
public function initialize(): void
```

##### shutdown()

**Signature:**

```php
public function shutdown(): void
```

##### description()

**Signature:**

```php
public function description(): string
```

##### author()

**Signature:**

```php
public function author(): string
```

##### extractBytes()

**Signature:**

```php
public function extractBytes(string $content, string $mimeType, ExtractionConfig $config): InternalDocument
```

##### supportedMimeTypes()

**Signature:**

```php
public function supportedMimeTypes(): array<string>
```

##### priority()

**Signature:**

```php
public function priority(): int
```


---

### PptMetadata

Metadata extracted from PPT files.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `title` | `?string` | `null` | Title |
| `subject` | `?string` | `null` | Subject |
| `author` | `?string` | `null` | Author |
| `lastAuthor` | `?string` | `null` | Last author |


---

### PptxAppProperties

Application properties from docProps/app.xml for PPTX

Contains PowerPoint-specific document metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `application` | `?string` | `null` | Application name (e.g., "Microsoft Office PowerPoint") |
| `appVersion` | `?string` | `null` | Application version |
| `totalTime` | `?int` | `null` | Total editing time in minutes |
| `company` | `?string` | `null` | Company name |
| `docSecurity` | `?int` | `null` | Document security level |
| `scaleCrop` | `?bool` | `null` | Scale crop flag |
| `linksUpToDate` | `?bool` | `null` | Links up to date flag |
| `sharedDoc` | `?bool` | `null` | Shared document flag |
| `hyperlinksChanged` | `?bool` | `null` | Hyperlinks changed flag |
| `slides` | `?int` | `null` | Number of slides |
| `notes` | `?int` | `null` | Number of notes |
| `hiddenSlides` | `?int` | `null` | Number of hidden slides |
| `multimediaClips` | `?int` | `null` | Number of multimedia clips |
| `presentationFormat` | `?string` | `null` | Presentation format (e.g., "Widescreen", "Standard") |
| `slideTitles` | `array<string>` | `[]` | Slide titles |


---

### PptxExtractionOptions

Options for PPTX content extraction.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `extractImages` | `bool` | `true` | Whether to extract embedded images. |
| `pageConfig` | `?PageConfig` | `null` | Optional page configuration for boundary tracking. |
| `plain` | `bool` | `false` | Whether to output plain text (no markdown). |
| `includeStructure` | `bool` | `false` | Whether to build the `DocumentStructure` tree. |
| `injectPlaceholders` | `bool` | `true` | Whether to emit `![alt](target)` references in markdown output. |

#### Methods

##### default()

**Signature:**

```php
public static function default(): PptxExtractionOptions
```


---

### PptxExtractionResult

PowerPoint (PPTX) extraction result.

Contains extracted slide content, metadata, and embedded images/tables.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `content` | `string` | — | Extracted text content from all slides |
| `metadata` | `PptxMetadata` | — | Presentation metadata |
| `slideCount` | `int` | — | Total number of slides |
| `imageCount` | `int` | — | Total number of embedded images |
| `tableCount` | `int` | — | Total number of tables |
| `images` | `array<ExtractedImage>` | — | Extracted images from the presentation |
| `pageStructure` | `?PageStructure` | `null` | Slide structure with boundaries (when page tracking is enabled) |
| `pageContents` | `?array<PageContent>` | `null` | Per-slide content (when page tracking is enabled) |
| `document` | `?DocumentStructure` | `null` | Structured document representation |
| `hyperlinks` | `array<StringOptionString>` | — | Hyperlinks discovered in slides as (url, optional_label) pairs. |
| `officeMetadata` | `array<string, string>` | — | Office metadata extracted from docProps/core.xml and docProps/app.xml. Contains keys like "title", "author", "created_by", "subject", "keywords", "modified_by", "created_at", "modified_at", etc. |


---

### PptxExtractor

PowerPoint presentation extractor.

Supports: .pptx, .pptm, .ppsx

#### Methods

##### default()

**Signature:**

```php
public static function default(): PptxExtractor
```

##### name()

**Signature:**

```php
public function name(): string
```

##### version()

**Signature:**

```php
public function version(): string
```

##### initialize()

**Signature:**

```php
public function initialize(): void
```

##### shutdown()

**Signature:**

```php
public function shutdown(): void
```

##### extractBytes()

**Signature:**

```php
public function extractBytes(string $content, string $mimeType, ExtractionConfig $config): InternalDocument
```

##### extractFile()

**Signature:**

```php
public function extractFile(string $path, string $mimeType, ExtractionConfig $config): InternalDocument
```

##### supportedMimeTypes()

**Signature:**

```php
public function supportedMimeTypes(): array<string>
```

##### priority()

**Signature:**

```php
public function priority(): int
```


---

### PptxMetadata

PowerPoint presentation metadata.

Extracted from PPTX files containing slide counts and presentation details.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `slideCount` | `int` | — | Total number of slides in the presentation |
| `slideNames` | `array<string>` | — | Names of slides (if available) |
| `imageCount` | `?int` | `null` | Number of embedded images |
| `tableCount` | `?int` | `null` | Number of tables |


---

### ProcessingWarning

A non-fatal warning from a processing pipeline stage.

Captures errors from optional features that don't prevent extraction
but may indicate degraded results.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `source` | `Str` | — | The pipeline stage or feature that produced this warning (e.g., "embedding", "chunking", "language_detection", "output_format"). |
| `message` | `Str` | — | Human-readable description of what went wrong. |


---

### PstExtractor

PST file extractor.

Supports: .pst (Microsoft Outlook Personal Folders)

#### Methods

##### default()

**Signature:**

```php
public static function default(): PstExtractor
```

##### name()

**Signature:**

```php
public function name(): string
```

##### version()

**Signature:**

```php
public function version(): string
```

##### initialize()

**Signature:**

```php
public function initialize(): void
```

##### shutdown()

**Signature:**

```php
public function shutdown(): void
```

##### extractSync()

**Signature:**

```php
public function extractSync(string $content, string $mimeType, ExtractionConfig $config): InternalDocument
```

##### extractBytes()

**Signature:**

```php
public function extractBytes(string $content, string $mimeType, ExtractionConfig $config): InternalDocument
```

##### supportedMimeTypes()

**Signature:**

```php
public function supportedMimeTypes(): array<string>
```

##### priority()

**Signature:**

```php
public function priority(): int
```

##### asSyncExtractor()

**Signature:**

```php
public function asSyncExtractor(): ?SyncExtractor
```


---

### PstMetadata

Outlook PST archive metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `messageCount` | `int` | `null` | Number of message |


---

### QualityProcessor

Post-processor that calculates quality score and cleans text.

This processor:
- Runs in the Early processing stage
- Calculates quality score when `config.enable_quality_processing` is true
- Stores quality score in `metadata.additional["quality_score"]`
- Cleans and normalizes extracted text

#### Methods

##### name()

**Signature:**

```php
public function name(): string
```

##### version()

**Signature:**

```php
public function version(): string
```

##### initialize()

**Signature:**

```php
public function initialize(): void
```

##### shutdown()

**Signature:**

```php
public function shutdown(): void
```

##### process()

**Signature:**

```php
public function process(ExtractionResult $result, ExtractionConfig $config): void
```

##### processingStage()

**Signature:**

```php
public function processingStage(): ProcessingStage
```

##### shouldProcess()

**Signature:**

```php
public function shouldProcess(ExtractionResult $result, ExtractionConfig $config): bool
```

##### estimatedDurationMs()

**Signature:**

```php
public function estimatedDurationMs(ExtractionResult $result): int
```


---

### RakeParams

RAKE-specific parameters.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `minWordLength` | `int` | `1` | Minimum word length to consider (default: 1). |
| `maxWordsPerPhrase` | `int` | `3` | Maximum words in a keyword phrase (default: 3). |

#### Methods

##### default()

**Signature:**

```php
public static function default(): RakeParams
```


---

### RecognizedTable

Pre-computed table markdown for a table detection region.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `detectionBbox` | `BBox` | — | Detection bbox that this table corresponds to (for matching). |
| `cells` | `array<array<string>>` | — | Table cells as a 2D vector (rows x columns). |
| `markdown` | `string` | — | Rendered markdown table. |


---

### Record

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `tagId` | `int` | — | Tag id |
| `data` | `string` | — | Data |

#### Methods

##### parse()

**Signature:**

```php
public static function parse(StreamReader $reader): Record
```

##### dataReader()

Return a fresh `StreamReader` over this record's data bytes.

**Signature:**

```php
public function dataReader(): StreamReader
```


---

### Recyclable

Trait for types that can be pooled and reused.

Implementing this trait allows a type to be used with `Pool<T>`.
The `reset()` method should clear the object's state for reuse.

#### Methods

##### reset()

Reset the object to a reusable state.

This is called when returning an object to the pool.
Should clear any internal data while preserving capacity.

**Signature:**

```php
public function reset(): void
```


---

### Relationship

A relationship between two elements in the document.

During extraction, targets may be unresolved keys (`RelationshipTarget::Key`).
The derivation step resolves these to indices using the element anchor index.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `source` | `int` | — | Index of the source element in `InternalDocument.elements`. |
| `target` | `RelationshipTarget` | — | Target of the relationship (resolved index or unresolved key). |
| `kind` | `RelationshipKind` | — | Semantic kind of the relationship. |


---

### ResolvedStyle

Fully resolved (flattened) style after walking the inheritance chain.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `paragraphProperties` | `ParagraphProperties` | `null` | Paragraph properties (paragraph properties) |
| `runProperties` | `RunProperties` | `null` | Run properties (run properties) |


---

### RowProperties

Row-level properties from `<w:trPr>`.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `height` | `?int` | `null` | Height |
| `heightRule` | `?string` | `null` | Height rule |
| `isHeader` | `bool` | `null` | Whether header |
| `cantSplit` | `bool` | `null` | Cant split |


---

### RstExtractor

Native Rust reStructuredText extractor.

Parses RST documents using document tree parsing and extracts:
- Metadata from field lists
- Document structure (headings, sections)
- Text content and inline formatting
- Code blocks and directives
- Tables and lists

#### Methods

##### buildInternalDocument()

Build an `InternalDocument` from RST content.

Handles sections, paragraphs, code blocks, tables, footnotes, citations,
and cross-references.

**Signature:**

```php
public static function buildInternalDocument(string $content, bool $injectPlaceholders): InternalDocument
```

##### default()

**Signature:**

```php
public static function default(): RstExtractor
```

##### name()

**Signature:**

```php
public function name(): string
```

##### version()

**Signature:**

```php
public function version(): string
```

##### initialize()

**Signature:**

```php
public function initialize(): void
```

##### shutdown()

**Signature:**

```php
public function shutdown(): void
```

##### description()

**Signature:**

```php
public function description(): string
```

##### author()

**Signature:**

```php
public function author(): string
```

##### extractBytes()

**Signature:**

```php
public function extractBytes(string $content, string $mimeType, ExtractionConfig $config): InternalDocument
```

##### extractFile()

**Signature:**

```php
public function extractFile(string $path, string $mimeType, ExtractionConfig $config): InternalDocument
```

##### supportedMimeTypes()

**Signature:**

```php
public function supportedMimeTypes(): array<string>
```

##### priority()

**Signature:**

```php
public function priority(): int
```


---

### RtDetrModel

Docling RT-DETR v2 layout detection model.

This model is NMS-free (transformer-based end-to-end detection).

Input tensors:
  - `images`:            f32 [batch, 3, 640, 640]  (preprocessed pixel data)
  - `orig_target_sizes`: i64 [batch, 2]            ([height, width] of original image)

Output tensors:
  - `labels`: i64 [batch, num_queries]   (class IDs, 0-16)
  - `boxes`:  f32 [batch, num_queries, 4] (bounding boxes in original image coordinates)
  - `scores`: f32 [batch, num_queries]   (confidence scores)

#### Methods

##### fromFile()

Load a Docling RT-DETR ONNX model from a file.

**Signature:**

```php
public static function fromFile(string $path): RtDetrModel
```

##### detect()

**Signature:**

```php
public function detect(RgbImage $img): array<LayoutDetection>
```

##### detectWithThreshold()

**Signature:**

```php
public function detectWithThreshold(RgbImage $img, float $threshold): array<LayoutDetection>
```

##### detectBatch()

**Signature:**

```php
public function detectBatch(array<RgbImage> $images, float $threshold): array<array<LayoutDetection>>
```

##### name()

**Signature:**

```php
public function name(): string
```


---

### RtfExtractor

Native Rust RTF extractor.

Extracts text content, metadata, and structure from RTF documents

#### Methods

##### default()

**Signature:**

```php
public static function default(): RtfExtractor
```

##### name()

**Signature:**

```php
public function name(): string
```

##### version()

**Signature:**

```php
public function version(): string
```

##### initialize()

**Signature:**

```php
public function initialize(): void
```

##### shutdown()

**Signature:**

```php
public function shutdown(): void
```

##### description()

**Signature:**

```php
public function description(): string
```

##### author()

**Signature:**

```php
public function author(): string
```

##### extractBytes()

**Signature:**

```php
public function extractBytes(string $content, string $mimeType, ExtractionConfig $config): InternalDocument
```

##### supportedMimeTypes()

**Signature:**

```php
public function supportedMimeTypes(): array<string>
```

##### priority()

**Signature:**

```php
public function priority(): int
```


---

### Run

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `text` | `string` | `null` | Text |
| `bold` | `bool` | `null` | Bold |
| `italic` | `bool` | `null` | Italic |
| `underline` | `bool` | `null` | Underline |
| `strikethrough` | `bool` | `null` | Strikethrough |
| `subscript` | `bool` | `null` | Subscript |
| `superscript` | `bool` | `null` | Superscript |
| `fontSize` | `?int` | `null` | Font size in half-points (from `w:sz`). |
| `fontColor` | `?string` | `null` | Font color as "RRGGBB" hex (from `w:color`). |
| `highlight` | `?string` | `null` | Highlight color name (from `w:highlight`). |
| `hyperlinkUrl` | `?string` | `null` | Hyperlink url |
| `mathLatex` | `?StringBool` | `null` | LaTeX math content: (latex_source, is_display_math). When set, this run represents an equation and `text` is ignored. |

#### Methods

##### toMarkdown()

Render this run as markdown with formatting markers.

**Signature:**

```php
public function toMarkdown(): string
```


---

### RunProperties

Run-level formatting properties (bold, italic, font, size, color, etc.).

All fields are `Option` so that inheritance resolution can distinguish
"not set" (`null`) from "explicitly set" (`Some`).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `bold` | `?bool` | `null` | Bold |
| `italic` | `?bool` | `null` | Italic |
| `underline` | `?bool` | `null` | Underline |
| `strikethrough` | `?bool` | `null` | Strikethrough |
| `color` | `?string` | `null` | Hex RGB color, e.g. `"2F5496"`. |
| `fontSizeHalfPoints` | `?int` | `null` | Font size in half-points (`w:sz` val). Divide by 2 to get points. |
| `fontAscii` | `?string` | `null` | ASCII font family (`w:rFonts w:ascii`). |
| `fontAsciiTheme` | `?string` | `null` | ASCII theme font (`w:rFonts w:asciiTheme`). |
| `vertAlign` | `?string` | `null` | Vertical alignment: "superscript", "subscript", or "baseline". |
| `fontHAnsi` | `?string` | `null` | High ANSI font family (w:rFonts w:hAnsi). |
| `fontCs` | `?string` | `null` | Complex script font family (w:rFonts w:cs). |
| `fontEastAsia` | `?string` | `null` | East Asian font family (w:rFonts w:eastAsia). |
| `highlight` | `?string` | `null` | Highlight color name (e.g., "yellow", "green", "cyan"). |
| `caps` | `?bool` | `null` | All caps text transformation. |
| `smallCaps` | `?bool` | `null` | Small caps text transformation. |
| `shadow` | `?bool` | `null` | Text shadow effect. |
| `outline` | `?bool` | `null` | Text outline effect. |
| `emboss` | `?bool` | `null` | Text emboss effect. |
| `imprint` | `?bool` | `null` | Text imprint (engrave) effect. |
| `charSpacing` | `?int` | `null` | Character spacing in twips (from w:spacing w:val). |
| `position` | `?int` | `null` | Vertical position offset in half-points (from w:position w:val). |
| `kern` | `?int` | `null` | Kerning threshold in half-points (from w:kern w:val). |
| `themeColor` | `?string` | `null` | Theme color reference (e.g., "accent1", "dk1"). |
| `themeTint` | `?string` | `null` | Theme color tint modification (hex value). |
| `themeShade` | `?string` | `null` | Theme color shade modification (hex value). |


---

### Section

A body-text section containing a flat list of paragraphs.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `paragraphs` | `array<Paragraph>` | `[]` | Paragraphs |


---

### SectionProperties

DOCX section properties parsed from `w:sectPr` element.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `pageWidthTwips` | `?int` | `null` | Page width in twips (from `w:pgSz w:w`). |
| `pageHeightTwips` | `?int` | `null` | Page height in twips (from `w:pgSz w:h`). |
| `orientation` | `?Orientation` | `Orientation::Portrait` | Page orientation (from `w:pgSz w:orient`). |
| `margins` | `PageMargins` | `null` | Page margins (from `w:pgMar`). |
| `columns` | `ColumnLayout` | `null` | Column layout (from `w:cols`). |
| `docGridLinePitch` | `?int` | `null` | Document grid line pitch in twips (from `w:docGrid w:linePitch`). |

#### Methods

##### pageWidthPoints()

Convert page width from twips to points.

**Signature:**

```php
public function pageWidthPoints(): ?float
```

##### pageHeightPoints()

Convert page height from twips to points.

**Signature:**

```php
public function pageHeightPoints(): ?float
```


---

### SecurityLimits

Configuration for security limits across extractors.

All limits are intentionally conservative to prevent DoS attacks
while still supporting legitimate documents.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `maxArchiveSize` | `int` | `null` | Maximum uncompressed size for archives (500 MB) |
| `maxCompressionRatio` | `int` | `100` | Maximum compression ratio before flagging as potential bomb (100:1) |
| `maxFilesInArchive` | `int` | `10000` | Maximum number of files in archive (10,000) |
| `maxNestingDepth` | `int` | `100` | Maximum nesting depth for structures (100) |
| `maxEntityLength` | `int` | `32` | Maximum entity/string length (32) |
| `maxContentSize` | `int` | `null` | Maximum string growth per document (100 MB) |
| `maxIterations` | `int` | `10000000` | Maximum iterations per operation |
| `maxXmlDepth` | `int` | `100` | Maximum XML depth (100 levels) |
| `maxTableCells` | `int` | `100000` | Maximum cells per table (100,000) |

#### Methods

##### default()

**Signature:**

```php
public static function default(): SecurityLimits
```


---

### ServerConfig

API server configuration.

This struct holds all configuration options for the Kreuzberg API server,
including host/port settings, CORS configuration, and upload limits.

# Defaults

- `host`: "127.0.0.1" (localhost only)
- `port`: 8000
- `cors_origins`: empty vector (allows all origins)
- `max_request_body_bytes`: 104_857_600 (100 MB)
- `max_multipart_field_bytes`: 104_857_600 (100 MB)

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `host` | `string` | `null` | Server host address (e.g., "127.0.0.1", "0.0.0.0") |
| `port` | `int` | `null` | Server port number |
| `corsOrigins` | `array<string>` | `[]` | CORS allowed origins. Empty vector means allow all origins. If this is an empty vector, the server will accept requests from any origin. If populated with specific origins (e.g., ["https://example.com"]), only those origins will be allowed. |
| `maxRequestBodyBytes` | `int` | `null` | Maximum size of request body in bytes (default: 100 MB) |
| `maxMultipartFieldBytes` | `int` | `null` | Maximum size of multipart fields in bytes (default: 100 MB) |

#### Methods

##### default()

**Signature:**

```php
public static function default(): ServerConfig
```

##### listenAddr()

Get the server listen address (host:port).

**Signature:**

```php
public function listenAddr(): string
```

##### corsAllowsAll()

Check if CORS allows all origins.

Returns `true` if the `cors_origins` vector is empty, meaning all origins
are allowed. Returns `false` if specific origins are configured.

**Signature:**

```php
public function corsAllowsAll(): bool
```

##### isOriginAllowed()

Check if a given origin is allowed by CORS configuration.

Returns `true` if:
- CORS allows all origins (empty origins list), or
- The given origin is in the allowed origins list

**Signature:**

```php
public function isOriginAllowed(string $origin): bool
```

##### maxRequestBodyMb()

Get maximum request body size in megabytes (rounded up).

**Signature:**

```php
public function maxRequestBodyMb(): int
```

##### maxMultipartFieldMb()

Get maximum multipart field size in megabytes (rounded up).

**Signature:**

```php
public function maxMultipartFieldMb(): int
```

##### applyEnvOverrides()

Apply environment variable overrides to the configuration.

Reads the following environment variables and overrides config values if set:

- `KREUZBERG_HOST` - Server host address
- `KREUZBERG_PORT` - Server port number (parsed as u16)
- `KREUZBERG_CORS_ORIGINS` - Comma-separated list of allowed origins
- `KREUZBERG_MAX_REQUEST_BODY_BYTES` - Max request body size in bytes
- `KREUZBERG_MAX_MULTIPART_FIELD_BYTES` - Max multipart field size in bytes

**Errors:**

Returns `KreuzbergError::Validation` if:
- `KREUZBERG_PORT` cannot be parsed as u16
- `KREUZBERG_MAX_REQUEST_BODY_BYTES` cannot be parsed as usize
- `KREUZBERG_MAX_MULTIPART_FIELD_BYTES` cannot be parsed as usize

**Signature:**

```php
public function applyEnvOverrides(): void
```

##### fromFile()

Load server configuration from a file.

Automatically detects the file format based on extension:
- `.toml` - TOML format
- `.yaml` or `.yml` - YAML format
- `.json` - JSON format

This function handles two config file formats:
1. Flat format: Server config at root level
2. Nested format: Server config under `[server]` section (combined with ExtractionConfig)

**Errors:**

Returns `KreuzbergError::Validation` if:
- File doesn't exist or cannot be read
- File extension is not recognized
- File content is invalid for the detected format

**Signature:**

```php
public static function fromFile(Path $path): ServerConfig
```

##### fromTomlFile()

Load server configuration from a TOML file.

**Errors:**

Returns `KreuzbergError::Validation` if the file doesn't exist or is invalid TOML.

**Signature:**

```php
public static function fromTomlFile(Path $path): ServerConfig
```

##### fromYamlFile()

Load server configuration from a YAML file.

**Errors:**

Returns `KreuzbergError::Validation` if the file doesn't exist or is invalid YAML.

**Signature:**

```php
public static function fromYamlFile(Path $path): ServerConfig
```

##### fromJsonFile()

Load server configuration from a JSON file.

**Errors:**

Returns `KreuzbergError::Validation` if the file doesn't exist or is invalid JSON.

**Signature:**

```php
public static function fromJsonFile(Path $path): ServerConfig
```


---

### SevenZExtractor

7z archive extractor.

Extracts file lists and text content from 7z archives.

#### Methods

##### default()

**Signature:**

```php
public static function default(): SevenZExtractor
```

##### name()

**Signature:**

```php
public function name(): string
```

##### version()

**Signature:**

```php
public function version(): string
```

##### initialize()

**Signature:**

```php
public function initialize(): void
```

##### shutdown()

**Signature:**

```php
public function shutdown(): void
```

##### description()

**Signature:**

```php
public function description(): string
```

##### author()

**Signature:**

```php
public function author(): string
```

##### extractBytes()

**Signature:**

```php
public function extractBytes(string $content, string $mimeType, ExtractionConfig $config): InternalDocument
```

##### supportedMimeTypes()

**Signature:**

```php
public function supportedMimeTypes(): array<string>
```

##### priority()

**Signature:**

```php
public function priority(): int
```

##### asSyncExtractor()

**Signature:**

```php
public function asSyncExtractor(): ?SyncExtractor
```

##### extractSync()

**Signature:**

```php
public function extractSync(string $content, string $mimeType, ExtractionConfig $config): InternalDocument
```


---

### SlanetCell

A single cell detected by SLANeXT.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `polygon` | `F328` | — | Bounding box polygon in image pixel coordinates. Format: [x1, y1, x2, y2, x3, y3, x4, y4] (4 corners, clockwise from top-left). |
| `bbox` | `F324` | — | Axis-aligned bounding box derived from polygon: [left, top, right, bottom]. |
| `row` | `int` | — | Row index in the table (0-based). |
| `col` | `int` | — | Column index within the row (0-based). |


---

### SlanetModel

SLANeXT table structure recognition model.

Wraps an ORT session for SLANeXT ONNX model and provides preprocessing,
inference, and post-processing in a single `recognize` call.

#### Methods

##### fromFile()

Load a SLANeXT ONNX model from a file path.

**Signature:**

```php
public static function fromFile(string $path): SlanetModel
```

##### recognize()

Recognize table structure from a cropped table image.

Returns a `SlanetResult` with detected cells, grid dimensions,
and structure tokens.

**Signature:**

```php
public function recognize(RgbImage $tableImg): SlanetResult
```


---

### SlanetResult

SLANeXT recognition result for a single table image.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `cells` | `array<SlanetCell>` | — | Detected cells with bounding boxes and grid positions. |
| `numRows` | `int` | — | Number of rows in the table. |
| `numCols` | `int` | — | Maximum number of columns across all rows. |
| `confidence` | `float` | — | Average structure prediction confidence. |
| `structureTokens` | `array<string>` | — | Raw HTML structure tokens (for debugging). |


---

### StreamReader

#### Methods

##### readU8()

**Signature:**

```php
public function readU8(): int
```

##### readU16()

**Signature:**

```php
public function readU16(): int
```

##### readU32()

**Signature:**

```php
public function readU32(): int
```

##### readBytes()

**Signature:**

```php
public function readBytes(int $len): string
```

##### position()

Current byte position within the stream.

**Signature:**

```php
public function position(): int
```

##### remaining()

Number of bytes remaining from the current position to the end.

**Signature:**

```php
public function remaining(): int
```


---

### StringBufferPool

Convenience type alias for a pooled String.


---

### StringGrowthValidator

Helper struct for tracking and validating string growth.

#### Methods

##### checkAppend()

Validate and update size after appending.

**Returns:**
* `Ok(())` if size is within limits
* `Err(SecurityError)` if size exceeds limit

**Signature:**

```php
public function checkAppend(int $len): void
```

##### currentSize()

Get current size.

**Signature:**

```php
public function currentSize(): int
```


---

### StructuredData

Structured data (Schema.org, microdata, RDFa) block.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `dataType` | `StructuredDataType` | — | Type of structured data |
| `rawJson` | `string` | — | Raw JSON string representation |
| `schemaType` | `?string` | `null` | Schema type if detectable (e.g., "Article", "Event", "Product") |


---

### StructuredDataResult

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `content` | `string` | — | The extracted text content |
| `format` | `Str` | — | Format (str) |
| `metadata` | `array<string, string>` | — | Document metadata |
| `textFields` | `array<string>` | — | Text fields |


---

### StructuredExtractionConfig

Configuration for LLM-based structured data extraction.

Sends extracted document content to a VLM with a JSON schema,
returning structured data that conforms to the schema.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `schema` | `mixed` | — | JSON Schema defining the desired output structure. |
| `schemaName` | `string` | — | Schema name passed to the LLM's structured output mode. |
| `schemaDescription` | `?string` | `null` | Optional schema description for the LLM. |
| `strict` | `bool` | — | Enable strict mode — output must exactly match the schema. |
| `prompt` | `?string` | `null` | Custom Jinja2 extraction prompt template. When `None`, a default template is used. Available template variables: - `{{ content }}` — The extracted document text. - `{{ schema }}` — The JSON schema as a formatted string. - `{{ schema_name }}` — The schema name. - `{{ schema_description }}` — The schema description (may be empty). |
| `llm` | `LlmConfig` | — | LLM configuration for the extraction. |


---

### StructuredExtractor

Structured data extractor supporting JSON, JSONL/NDJSON, YAML, and TOML.

#### Methods

##### default()

**Signature:**

```php
public static function default(): StructuredExtractor
```

##### name()

**Signature:**

```php
public function name(): string
```

##### version()

**Signature:**

```php
public function version(): string
```

##### initialize()

**Signature:**

```php
public function initialize(): void
```

##### shutdown()

**Signature:**

```php
public function shutdown(): void
```

##### extractBytes()

**Signature:**

```php
public function extractBytes(string $content, string $mimeType, ExtractionConfig $config): InternalDocument
```

##### supportedMimeTypes()

**Signature:**

```php
public function supportedMimeTypes(): array<string>
```

##### priority()

**Signature:**

```php
public function priority(): int
```


---

### StyleCatalog

Catalog of all styles parsed from `word/styles.xml`, plus document defaults.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `styles` | `AHashMap` | `null` | Styles (a hash map) |
| `defaultParagraphProperties` | `ParagraphProperties` | `null` | Default paragraph properties (paragraph properties) |
| `defaultRunProperties` | `RunProperties` | `null` | Default run properties (run properties) |

#### Methods

##### resolveStyle()

Resolve a style by walking its `basedOn` inheritance chain.

The resolution order is:
1. Document defaults (`<w:docDefaults>`)
2. Base style chain (walking `basedOn` from root to leaf)
3. The style itself

For `Option` fields, a child value of `Some(x)` overrides the parent.
A value of `null` inherits from the parent. For boolean toggle properties,
`Some(false)` explicitly disables the property.

The chain depth is limited to 20 to prevent infinite loops from circular references.

**Signature:**

```php
public function resolveStyle(string $styleId): ResolvedStyle
```


---

### StyleDefinition

A single style definition parsed from `<w:style>` in `word/styles.xml`.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `id` | `string` | — | The style ID (`w:styleId` attribute). |
| `name` | `?string` | `null` | Human-readable name (`<w:name w:val="..."/>`). |
| `styleType` | `StyleType` | — | Style type: paragraph, character, table, or numbering. |
| `basedOn` | `?string` | `null` | ID of the parent style (`<w:basedOn w:val="..."/>`). |
| `nextStyle` | `?string` | `null` | ID of the style to apply to the next paragraph (`<w:next w:val="..."/>`). |
| `isDefault` | `bool` | — | Whether this is the default style for its type. |
| `paragraphProperties` | `ParagraphProperties` | — | Paragraph properties defined directly on this style. |
| `runProperties` | `RunProperties` | — | Run properties defined directly on this style. |


---

### StyledHtmlRenderer

Styled HTML renderer.

Implements the `Renderer` trait; registered as `"html"` when the
`html` feature is active. Configuration is baked in at
construction time — no per-render allocation for CSS resolution.

#### Methods

##### new()

**Signature:**

```php
public static function new(HtmlOutputConfig $config): StyledHtmlRenderer
```

##### name()

**Signature:**

```php
public function name(): string
```

##### render()

**Signature:**

```php
public function render(InternalDocument $doc): string
```


---

### SupportedFormat

A supported document format entry.

Represents a file extension and its corresponding MIME type that Kreuzberg can process.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `extension` | `string` | — | File extension (without leading dot), e.g., "pdf", "docx" |
| `mimeType` | `string` | — | MIME type string, e.g., "application/pdf" |


---

### SyncExtractor

Trait for extractors that can work synchronously (WASM-compatible).

This trait defines the synchronous extraction interface for WASM targets and other
environments where async/tokio runtimes are not available or desirable.

# Implementation

Extractors that need to support WASM should implement this trait in addition to
the async `DocumentExtractor` trait. This allows the same extractor to work in both
environments by delegating to the sync implementation.

# MIME Type Validation

The `mime_type` parameter is guaranteed to be already validated.

#### Methods

##### extractSync()

Extract content from a byte array synchronously.

This method performs extraction without requiring an async runtime.
It is called by `extract_bytes_sync()` when the `tokio-runtime` feature is disabled.

**Returns:**

An `InternalDocument` containing the extracted elements, metadata, and tables.

**Signature:**

```php
public function extractSync(string $content, string $mimeType, ExtractionConfig $config): InternalDocument
```


---

### Table

Extracted table structure.

Represents a table detected and extracted from a document (PDF, image, etc.).
Tables are converted to both structured cell data and Markdown format.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `cells` | `array<array<string>>` | — | Table cells as a 2D vector (rows × columns) |
| `markdown` | `string` | — | Markdown representation of the table |
| `pageNumber` | `int` | — | Page number where the table was found (1-indexed) |
| `boundingBox` | `?BoundingBox` | `null` | Bounding box of the table on the page (PDF coordinates: x0=left, y0=bottom, x1=right, y1=top). Only populated for PDF-extracted tables when position data is available. |


---

### TableBorders

Borders for a table (6 borders: top, bottom, left, right, insideH, insideV).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `top` | `?BorderStyle` | `null` | Top (border style) |
| `bottom` | `?BorderStyle` | `null` | Bottom (border style) |
| `left` | `?BorderStyle` | `null` | Left (border style) |
| `right` | `?BorderStyle` | `null` | Right (border style) |
| `insideH` | `?BorderStyle` | `null` | Inside h (border style) |
| `insideV` | `?BorderStyle` | `null` | Inside v (border style) |


---

### TableCell

Individual table cell with content and optional styling.

Future extension point for rich table support with cell-level metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `content` | `string` | — | Cell content as text |
| `rowSpan` | `int` | — | Row span (number of rows this cell spans) |
| `colSpan` | `int` | — | Column span (number of columns this cell spans) |
| `isHeader` | `bool` | — | Whether this is a header cell |


---

### TableClassifier

PP-LCNet table classifier model.

#### Methods

##### fromFile()

Load the table classifier ONNX model from a file path.

**Signature:**

```php
public static function fromFile(string $path): TableClassifier
```

##### classify()

Classify a cropped table image as wired or wireless.

**Signature:**

```php
public function classify(RgbImage $tableImg): TableType
```


---

### TableGrid

Structured table grid with cell-level metadata.

Stores row/column dimensions and a flat list of cells with position info.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `rows` | `int` | — | Number of rows in the table. |
| `cols` | `int` | — | Number of columns in the table. |
| `cells` | `array<GridCell>` | — | All cells in row-major order. |


---

### TableLook

Table look bitmask/flags controlling conditional formatting bands.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `firstRow` | `bool` | `null` | First row |
| `lastRow` | `bool` | `null` | Last row |
| `firstColumn` | `bool` | `null` | First column |
| `lastColumn` | `bool` | `null` | Last column |
| `noHBand` | `bool` | `null` | No h band |
| `noVBand` | `bool` | `null` | No v band |


---

### TableProperties

Table-level properties from `<w:tblPr>`.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `styleId` | `?string` | `null` | Style id |
| `width` | `?TableWidth` | `null` | Width (table width) |
| `alignment` | `?string` | `null` | Alignment |
| `layout` | `?string` | `null` | Layout |
| `look` | `?TableLook` | `null` | Look (table look) |
| `borders` | `?TableBorders` | `null` | Borders (table borders) |
| `cellMargins` | `?CellMargins` | `null` | Cell margins (cell margins) |
| `indent` | `?TableWidth` | `null` | Indent (table width) |
| `caption` | `?string` | `null` | Caption |


---

### TableRow

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `cells` | `array<TableCell>` | `[]` | Cells |
| `properties` | `?RowProperties` | `null` | Properties (row properties) |


---

### TableValidator

Helper struct for validating table cell counts.

#### Methods

##### addCells()

Add cells to table and validate.

**Returns:**
* `Ok(())` if cell count is within limits
* `Err(SecurityError)` if cell count exceeds limit

**Signature:**

```php
public function addCells(int $count): void
```

##### currentCells()

Get current cell count.

**Signature:**

```php
public function currentCells(): int
```


---

### TableWidth

Width specification used for tables and cells.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `value` | `int` | — | Value |
| `widthType` | `string` | — | Width type |


---

### TarExtractor

TAR archive extractor.

Extracts file lists and text content from TAR archives.

#### Methods

##### default()

**Signature:**

```php
public static function default(): TarExtractor
```

##### name()

**Signature:**

```php
public function name(): string
```

##### version()

**Signature:**

```php
public function version(): string
```

##### initialize()

**Signature:**

```php
public function initialize(): void
```

##### shutdown()

**Signature:**

```php
public function shutdown(): void
```

##### description()

**Signature:**

```php
public function description(): string
```

##### author()

**Signature:**

```php
public function author(): string
```

##### extractBytes()

**Signature:**

```php
public function extractBytes(string $content, string $mimeType, ExtractionConfig $config): InternalDocument
```

##### supportedMimeTypes()

**Signature:**

```php
public function supportedMimeTypes(): array<string>
```

##### priority()

**Signature:**

```php
public function priority(): int
```

##### asSyncExtractor()

**Signature:**

```php
public function asSyncExtractor(): ?SyncExtractor
```

##### extractSync()

**Signature:**

```php
public function extractSync(string $content, string $mimeType, ExtractionConfig $config): InternalDocument
```


---

### TatrDetection

A single TATR detection result.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `bbox` | `F324` | — | Bounding box in crop-pixel coordinates: `[x1, y1, x2, y2]`. |
| `confidence` | `float` | — | Detection confidence score (0.0..1.0). |
| `class` | `TatrClass` | — | Detected class. |


---

### TatrModel

TATR (Table Transformer) table structure recognition model.

Wraps an ORT session for the TATR ONNX model and provides preprocessing,
inference, and post-processing in a single `recognize` call.

#### Methods

##### fromFile()

Load a TATR ONNX model from a file path.

Uses the default execution provider selection from `build_session`
with a CPU-only fallback if the platform EP fails.

**Signature:**

```php
public static function fromFile(string $path): TatrModel
```

##### recognize()

Recognize table structure from a cropped table image.

Returns a `TatrResult` with detected rows, columns, headers, and
spanning cells in the input image's pixel coordinate space.

**Signature:**

```php
public function recognize(RgbImage $tableImg): TatrResult
```


---

### TatrResult

Aggregated TATR recognition result with detections separated by class.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `rows` | `array<TatrDetection>` | — | Detected rows, sorted top-to-bottom by `y2`. |
| `columns` | `array<TatrDetection>` | — | Detected columns, sorted left-to-right by `x2`. |
| `headers` | `array<TatrDetection>` | — | Detected headers (ColumnHeader and ProjectedRowHeader). |
| `spanning` | `array<TatrDetection>` | — | Detected spanning cells. |


---

### TessdataManager

Manages tessdata file downloading, caching, and manifest generation.

#### Methods

##### cacheDir()

Get the cache directory path.

**Signature:**

```php
public function cacheDir(): string
```

##### isLanguageCached()

Check if a specific language traineddata file is cached.

**Signature:**

```php
public function isLanguageCached(string $lang): bool
```


---

### TesseractBackend

Native Tesseract OCR backend.

This backend wraps the OcrProcessor and implements the OcrBackend trait,
allowing it to be used through the plugin system.

# Thread Safety

Uses Arc for shared ownership and is thread-safe (Send + Sync).

#### Methods

##### new()

Create a new Tesseract backend with default cache directory.

**Signature:**

```php
public static function new(): TesseractBackend
```

##### withCacheDir()

Create a new Tesseract backend with custom cache directory.

**Signature:**

```php
public static function withCacheDir(string $cacheDir): TesseractBackend
```

##### default()

**Signature:**

```php
public static function default(): TesseractBackend
```

##### name()

**Signature:**

```php
public function name(): string
```

##### version()

**Signature:**

```php
public function version(): string
```

##### initialize()

**Signature:**

```php
public function initialize(): void
```

##### shutdown()

**Signature:**

```php
public function shutdown(): void
```

##### processImage()

**Signature:**

```php
public function processImage(string $imageBytes, OcrConfig $config): ExtractionResult
```

##### processImageFile()

**Signature:**

```php
public function processImageFile(string $path, OcrConfig $config): ExtractionResult
```

##### supportsLanguage()

**Signature:**

```php
public function supportsLanguage(string $lang): bool
```

##### backendType()

**Signature:**

```php
public function backendType(): OcrBackendType
```

##### supportedLanguages()

**Signature:**

```php
public function supportedLanguages(): array<string>
```

##### supportsTableDetection()

**Signature:**

```php
public function supportsTableDetection(): bool
```


---

### TesseractConfig

Tesseract OCR configuration.

Provides fine-grained control over Tesseract OCR engine parameters.
Most users can use the defaults, but these settings allow optimization
for specific document types (invoices, handwriting, etc.).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `language` | `string` | `"eng"` | Language code (e.g., "eng", "deu", "fra") |
| `psm` | `int` | `3` | Page Segmentation Mode (0-13). Common values: - 3: Fully automatic page segmentation (default) - 6: Assume a single uniform block of text - 11: Sparse text with no particular order |
| `outputFormat` | `string` | `"markdown"` | Output format ("text" or "markdown") |
| `oem` | `int` | `3` | OCR Engine Mode (0-3). - 0: Legacy engine only - 1: Neural nets (LSTM) only (usually best) - 2: Legacy + LSTM - 3: Default (based on what's available) |
| `minConfidence` | `float` | `0` | Minimum confidence threshold (0.0-100.0). Words with confidence below this threshold may be rejected or flagged. |
| `preprocessing` | `?ImagePreprocessingConfig` | `null` | Image preprocessing configuration. Controls how images are preprocessed before OCR. Can significantly improve quality for scanned documents or low-quality images. |
| `enableTableDetection` | `bool` | `true` | Enable automatic table detection and reconstruction |
| `tableMinConfidence` | `float` | `0` | Minimum confidence threshold for table detection (0.0-1.0) |
| `tableColumnThreshold` | `int` | `50` | Column threshold for table detection (pixels) |
| `tableRowThresholdRatio` | `float` | `0.5` | Row threshold ratio for table detection (0.0-1.0) |
| `useCache` | `bool` | `true` | Enable OCR result caching |
| `classifyUsePreAdaptedTemplates` | `bool` | `true` | Use pre-adapted templates for character classification |
| `languageModelNgramOn` | `bool` | `false` | Enable N-gram language model |
| `tesseditDontBlkrejGoodWds` | `bool` | `true` | Don't reject good words during block-level processing |
| `tesseditDontRowrejGoodWds` | `bool` | `true` | Don't reject good words during row-level processing |
| `tesseditEnableDictCorrection` | `bool` | `true` | Enable dictionary correction |
| `tesseditCharWhitelist` | `string` | `""` | Whitelist of allowed characters (empty = all allowed) |
| `tesseditCharBlacklist` | `string` | `""` | Blacklist of forbidden characters (empty = none forbidden) |
| `tesseditUsePrimaryParamsModel` | `bool` | `true` | Use primary language params model |
| `textordSpaceSizeIsVariable` | `bool` | `true` | Variable-width space detection |
| `thresholdingMethod` | `bool` | `false` | Use adaptive thresholding method |

#### Methods

##### default()

**Signature:**

```php
public static function default(): TesseractConfig
```


---

### TextAnnotation

Inline text annotation — byte-range based formatting and links.

Annotations reference byte offsets into the node's text content,
enabling precise identification of formatted regions.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `start` | `int` | — | Start byte offset in the node's text content (inclusive). |
| `end` | `int` | — | End byte offset in the node's text content (exclusive). |
| `kind` | `AnnotationKind` | — | Annotation type. |


---

### TextExtractionResult

Plain text and Markdown extraction result.

Contains the extracted text along with statistics and,
for Markdown files, structural elements like headers and links.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `content` | `string` | — | Extracted text content |
| `lineCount` | `int` | — | Number of lines |
| `wordCount` | `int` | — | Number of words |
| `characterCount` | `int` | — | Number of characters |
| `headers` | `?array<string>` | `null` | Markdown headers (text only, Markdown files only) |
| `links` | `?array<StringString>` | `null` | Markdown links as (text, URL) tuples (Markdown files only) |
| `codeBlocks` | `?array<StringString>` | `null` | Code blocks as (language, code) tuples (Markdown files only) |


---

### TextMetadata

Text/Markdown metadata.

Extracted from plain text and Markdown files. Includes word counts and,
for Markdown, structural elements like headers and links.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `lineCount` | `int` | — | Number of lines in the document |
| `wordCount` | `int` | — | Number of words |
| `characterCount` | `int` | — | Number of characters |
| `headers` | `?array<string>` | `null` | Markdown headers (headings text only, for Markdown files) |
| `links` | `?array<StringString>` | `null` | Markdown links as (text, url) tuples (for Markdown files) |
| `codeBlocks` | `?array<StringString>` | `null` | Code blocks as (language, code) tuples (for Markdown files) |


---

### Theme

Complete theme with color scheme and font scheme.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `name` | `string` | `null` | Theme name (e.g., "Office Theme"). |
| `colorScheme` | `?ColorScheme` | `null` | Color scheme (12 standard colors). |
| `fontScheme` | `?FontScheme` | `null` | Font scheme (major and minor fonts). |


---

### TokenReductionConfig

Token reduction configuration.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `mode` | `string` | — | Reduction mode: "off", "light", "moderate", "aggressive", "maximum" |
| `preserveImportantWords` | `bool` | — | Preserve important words (capitalized, technical terms) |


---

### TracingLayer

A `tower::Layer` that wraps each extraction in a semantic tracing span.

#### Methods

##### layer()

**Signature:**

```php
public function layer(S $inner): Service
```


---

### TreeSitterConfig

Configuration for tree-sitter language pack integration.

Controls grammar download behavior and code analysis options.

# Example (TOML)

```toml
[tree_sitter]
languages = ["python", "rust"]
groups = ["web"]

[tree_sitter.process]
structure = true
comments = true
docstrings = true
```

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `enabled` | `bool` | `true` | Enable code intelligence processing (default: true). When `False`, tree-sitter analysis is completely skipped even if the config section is present. |
| `cacheDir` | `?string` | `null` | Custom cache directory for downloaded grammars. When `None`, uses the default: `~/.cache/tree-sitter-language-pack/v{version}/libs/`. |
| `languages` | `?array<string>` | `[]` | Languages to pre-download on init (e.g., `["python", "rust"]`). |
| `groups` | `?array<string>` | `[]` | Language groups to pre-download (e.g., `["web", "systems", "scripting"]`). |
| `process` | `TreeSitterProcessConfig` | `null` | Processing options for code analysis. |

#### Methods

##### default()

**Signature:**

```php
public static function default(): TreeSitterConfig
```


---

### TreeSitterProcessConfig

Processing options for tree-sitter code analysis.

Controls which analysis features are enabled when extracting code files.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `structure` | `bool` | `true` | Extract structural items (functions, classes, structs, etc.). Default: true. |
| `imports` | `bool` | `true` | Extract import statements. Default: true. |
| `exports` | `bool` | `true` | Extract export statements. Default: true. |
| `comments` | `bool` | `false` | Extract comments. Default: false. |
| `docstrings` | `bool` | `false` | Extract docstrings. Default: false. |
| `symbols` | `bool` | `false` | Extract symbol definitions. Default: false. |
| `diagnostics` | `bool` | `false` | Include parse diagnostics. Default: false. |
| `chunkMaxSize` | `?int` | `null` | Maximum chunk size in bytes. `None` disables chunking. |
| `contentMode` | `CodeContentMode` | `CodeContentMode::Chunks` | Content rendering mode for code extraction. |

#### Methods

##### default()

**Signature:**

```php
public static function default(): TreeSitterProcessConfig
```


---

### TsvRow

Tesseract TSV row data for conversion.

This struct represents a single row from Tesseract's TSV output format.
TSV format includes hierarchical information (block, paragraph, line, word)
along with bounding boxes and confidence scores.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `level` | `int` | — | Hierarchical level (1=block, 2=para, 3=line, 4=word, 5=symbol) |
| `pageNum` | `int` | — | Page number (1-indexed) |
| `blockNum` | `int` | — | Block number within page |
| `parNum` | `int` | — | Paragraph number within block |
| `lineNum` | `int` | — | Line number within paragraph |
| `wordNum` | `int` | — | Word number within line |
| `left` | `int` | — | Left x-coordinate in pixels |
| `top` | `int` | — | Top y-coordinate in pixels |
| `width` | `int` | — | Width in pixels |
| `height` | `int` | — | Height in pixels |
| `conf` | `float` | — | Confidence score (0-100) |
| `text` | `string` | — | Recognized text |


---

### TypstExtractor

Typst document extractor

#### Methods

##### default()

**Signature:**

```php
public static function default(): TypstExtractor
```

##### name()

**Signature:**

```php
public function name(): string
```

##### version()

**Signature:**

```php
public function version(): string
```

##### initialize()

**Signature:**

```php
public function initialize(): void
```

##### shutdown()

**Signature:**

```php
public function shutdown(): void
```

##### description()

**Signature:**

```php
public function description(): string
```

##### author()

**Signature:**

```php
public function author(): string
```

##### extractBytes()

**Signature:**

```php
public function extractBytes(string $content, string $mimeType, ExtractionConfig $config): InternalDocument
```

##### extractFile()

**Signature:**

```php
public function extractFile(string $path, string $mimeType, ExtractionConfig $config): InternalDocument
```

##### supportedMimeTypes()

**Signature:**

```php
public function supportedMimeTypes(): array<string>
```

##### priority()

**Signature:**

```php
public function priority(): int
```


---

### Uri

A URI extracted from a document.

Represents any link, reference, or resource pointer found during extraction.
The `kind` field classifies the URI semantically, while `label` carries
optional human-readable display text.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `url` | `string` | — | The URL or path string. |
| `label` | `?string` | `null` | Optional display text / label for the link. |
| `page` | `?int` | `null` | Optional page number where the URI was found (1-indexed). |
| `kind` | `UriKind` | — | Semantic classification of the URI. |

#### Methods

##### hyperlink()

Create a new hyperlink URI, auto-classifying `mailto:` as Email and `#` as Anchor.

**Signature:**

```php
public static function hyperlink(string $url, string $label): Uri
```

##### image()

Create a new image URI.

**Signature:**

```php
public static function image(string $url, string $label): Uri
```

##### citation()

Create a new citation URI (for DOIs, academic references).

**Signature:**

```php
public static function citation(string $url, string $label): Uri
```

##### anchor()

Create a new anchor/cross-reference URI.

**Signature:**

```php
public static function anchor(string $url, string $label): Uri
```

##### email()

Create a new email URI.

**Signature:**

```php
public static function email(string $url, string $label): Uri
```

##### reference()

Create a new reference URI.

**Signature:**

```php
public static function reference(string $url, string $label): Uri
```

##### withPage()

Set the page number.

**Signature:**

```php
public function withPage(int $page): Uri
```


---

### VlmOcrBackend

VLM-based OCR backend using liter-llm vision models.

This backend sends images to a vision language model (e.g., GPT-4o, Claude)
for text extraction, as an alternative to traditional OCR backends.

#### Methods

##### name()

**Signature:**

```php
public function name(): string
```

##### version()

**Signature:**

```php
public function version(): string
```

##### initialize()

**Signature:**

```php
public function initialize(): void
```

##### shutdown()

**Signature:**

```php
public function shutdown(): void
```

##### processImage()

**Signature:**

```php
public function processImage(string $imageBytes, OcrConfig $config): ExtractionResult
```

##### supportsLanguage()

**Signature:**

```php
public function supportsLanguage(string $lang): bool
```

##### backendType()

**Signature:**

```php
public function backendType(): OcrBackendType
```


---

### XlsxAppProperties

Application properties from docProps/app.xml for XLSX

Contains Excel-specific document metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `application` | `?string` | `null` | Application name (e.g., "Microsoft Excel") |
| `appVersion` | `?string` | `null` | Application version |
| `docSecurity` | `?int` | `null` | Document security level |
| `scaleCrop` | `?bool` | `null` | Scale crop flag |
| `linksUpToDate` | `?bool` | `null` | Links up to date flag |
| `sharedDoc` | `?bool` | `null` | Shared document flag |
| `hyperlinksChanged` | `?bool` | `null` | Hyperlinks changed flag |
| `company` | `?string` | `null` | Company name |
| `worksheetNames` | `array<string>` | `[]` | Worksheet names |


---

### XmlExtractionResult

XML extraction result.

Contains extracted text content from XML files along with
structural statistics about the XML document.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `content` | `string` | — | Extracted text content (XML structure filtered out) |
| `elementCount` | `int` | — | Total number of XML elements processed |
| `uniqueElements` | `array<string>` | — | List of unique element names found (sorted) |


---

### XmlExtractor

XML extractor.

Extracts text content from XML files, preserving element structure information.

#### Methods

##### default()

**Signature:**

```php
public static function default(): XmlExtractor
```

##### name()

**Signature:**

```php
public function name(): string
```

##### version()

**Signature:**

```php
public function version(): string
```

##### initialize()

**Signature:**

```php
public function initialize(): void
```

##### shutdown()

**Signature:**

```php
public function shutdown(): void
```

##### description()

**Signature:**

```php
public function description(): string
```

##### author()

**Signature:**

```php
public function author(): string
```

##### extractSync()

**Signature:**

```php
public function extractSync(string $content, string $mimeType, ExtractionConfig $config): InternalDocument
```

##### extractBytes()

**Signature:**

```php
public function extractBytes(string $content, string $mimeType, ExtractionConfig $config): InternalDocument
```

##### supportedMimeTypes()

**Signature:**

```php
public function supportedMimeTypes(): array<string>
```

##### priority()

**Signature:**

```php
public function priority(): int
```

##### asSyncExtractor()

**Signature:**

```php
public function asSyncExtractor(): ?SyncExtractor
```


---

### XmlMetadata

XML metadata extracted during XML parsing.

Provides statistics about XML document structure.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `elementCount` | `int` | — | Total number of XML elements processed |
| `uniqueElements` | `array<string>` | — | List of unique element tag names (sorted) |


---

### YakeParams

YAKE-specific parameters.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `windowSize` | `int` | `2` | Window size for co-occurrence analysis (default: 2). Controls the context window for computing co-occurrence statistics. |

#### Methods

##### default()

**Signature:**

```php
public static function default(): YakeParams
```


---

### YearRange

Year range for bibliographic metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `min` | `?int` | `null` | Min |
| `max` | `?int` | `null` | Max |
| `years` | `array<int>` | — | Years |


---

### YoloModel

YOLO-family layout detection model (YOLOv10, DocLayout-YOLO, YOLOX).

#### Methods

##### fromFile()

Load a YOLO ONNX model from a file.

For square-input models (YOLOv10, DocLayout-YOLO), pass the same value for both dimensions.
For YOLOX (unstructuredio), use width=768, height=1024.

**Signature:**

```php
public static function fromFile(string $path, YoloVariant $variant, int $inputWidth, int $inputHeight, string $modelName): YoloModel
```

##### detect()

**Signature:**

```php
public function detect(RgbImage $img): array<LayoutDetection>
```

##### detectWithThreshold()

**Signature:**

```php
public function detectWithThreshold(RgbImage $img, float $threshold): array<LayoutDetection>
```

##### name()

**Signature:**

```php
public function name(): string
```


---

### ZipBombValidator

Helper struct for validating ZIP archives for security issues.


---

### ZipExtractor

ZIP archive extractor.

Extracts file lists and text content from ZIP archives.

#### Methods

##### default()

**Signature:**

```php
public static function default(): ZipExtractor
```

##### name()

**Signature:**

```php
public function name(): string
```

##### version()

**Signature:**

```php
public function version(): string
```

##### initialize()

**Signature:**

```php
public function initialize(): void
```

##### shutdown()

**Signature:**

```php
public function shutdown(): void
```

##### description()

**Signature:**

```php
public function description(): string
```

##### author()

**Signature:**

```php
public function author(): string
```

##### extractBytes()

**Signature:**

```php
public function extractBytes(string $content, string $mimeType, ExtractionConfig $config): InternalDocument
```

##### supportedMimeTypes()

**Signature:**

```php
public function supportedMimeTypes(): array<string>
```

##### priority()

**Signature:**

```php
public function priority(): int
```

##### asSyncExtractor()

**Signature:**

```php
public function asSyncExtractor(): ?SyncExtractor
```

##### extractSync()

**Signature:**

```php
public function extractSync(string $content, string $mimeType, ExtractionConfig $config): InternalDocument
```


---

## Enums

### ExecutionProviderType

ONNX Runtime execution provider type.

Determines which hardware backend is used for model inference.
`Auto` (default) selects the best available provider per platform.

| Value | Description |
|-------|-------------|
| `Auto` | Auto-select: CoreML on macOS, CUDA on Linux, CPU elsewhere. |
| `Cpu` | CPU execution provider (always available). |
| `CoreMl` | Apple CoreML (macOS/iOS Neural Engine + GPU). |
| `Cuda` | NVIDIA CUDA GPU acceleration. |
| `TensorRt` | NVIDIA TensorRT (optimized CUDA inference). |


---

### OutputFormat

Output format for extraction results.

Controls the format of the `content` field in `ExtractionResult`.
When set to `Markdown`, `Djot`, or `Html`, the output will be formatted
accordingly. `Plain` returns the raw extracted text.
`Structured` returns JSON with full OCR element data including bounding
boxes and confidence scores.

| Value | Description |
|-------|-------------|
| `Plain` | Plain text content only (default) |
| `Markdown` | Markdown format |
| `Djot` | Djot markup format |
| `Html` | HTML format |
| `Json` | JSON tree format with heading-driven sections. |
| `Structured` | Structured JSON format with full OCR element metadata. |
| `Custom` | Custom renderer registered via the RendererRegistry. The string is the renderer name (e.g., "docx", "latex"). |


---

### HtmlTheme

Built-in HTML theme selection.

| Value | Description |
|-------|-------------|
| `Default` | Sensible defaults: system font stack, neutral colours, readable line measure. CSS custom properties (`--kb-*`) are all defined so user CSS can override individual values. |
| `GitHub` | GitHub Markdown-inspired palette and spacing. |
| `Dark` | Dark background, light text. |
| `Light` | Minimal light theme with generous whitespace. |
| `Unstyled` | No built-in stylesheet emitted. CSS custom properties are still defined on `:root` so user stylesheets can reference `var(--kb-*)` tokens. |


---

### TableModel

Which table structure recognition model to use.

Controls the model used for table cell detection within layout-detected
table regions.

| Value | Description |
|-------|-------------|
| `Tatr` | TATR (Table Transformer) -- default, 30MB, DETR-based row/column detection. |
| `SlanetWired` | SLANeXT wired variant -- 365MB, optimized for bordered tables. |
| `SlanetWireless` | SLANeXT wireless variant -- 365MB, optimized for borderless tables. |
| `SlanetPlus` | SLANet-plus -- 7.78MB, lightweight general-purpose. |
| `SlanetAuto` | Classifier-routed SLANeXT: auto-select wired/wireless per table. Uses PP-LCNet classifier (6.78MB) + both SLANeXT variants (730MB total). |
| `Disabled` | Disable table structure model inference entirely; use heuristic path only. |


---

### PdfBackend

PDF extraction backend selection.

Controls which PDF library is used for text extraction:
- `Pdfium`: pdfium-render (default, C++ based, mature)
- `PdfOxide`: pdf_oxide (pure Rust, faster, requires `pdf-oxide` feature)
- `Auto`: automatically select based on available features

| Value | Description |
|-------|-------------|
| `Pdfium` | Use pdfium-render backend (default). |
| `PdfOxide` | Use pdf_oxide backend (pure Rust). Requires `pdf-oxide` feature. |
| `Auto` | Automatically select the best available backend. |


---

### ChunkerType

Type of text chunker to use.

# Variants

* `Text` - Generic text splitter, splits on whitespace and punctuation
* `Markdown` - Markdown-aware splitter, preserves formatting and structure
* `Yaml` - YAML-aware splitter, creates one chunk per top-level key

| Value | Description |
|-------|-------------|
| `Text` | Text format |
| `Markdown` | Markdown format |
| `Yaml` | Yaml format |


---

### ChunkSizing

How chunk size is measured.

Defaults to `Characters` (Unicode character count). When using token-based sizing,
chunks are sized by token count according to the specified tokenizer.

Token-based sizing uses HuggingFace tokenizers loaded at runtime. Any tokenizer
available on HuggingFace Hub can be used, including OpenAI-compatible tokenizers
(e.g., `Xenova/gpt-4o`, `Xenova/cl100k_base`).

| Value | Description |
|-------|-------------|
| `Characters` | Size measured in Unicode characters (default). |
| `Tokenizer` | Size measured in tokens from a HuggingFace tokenizer. |


---

### EmbeddingModelType

Embedding model types supported by Kreuzberg.

| Value | Description |
|-------|-------------|
| `Preset` | Use a preset model configuration (recommended) |
| `Custom` | Use a custom ONNX model from HuggingFace |
| `Llm` | Provider-hosted embedding model via liter-llm. Uses the model specified in the nested `LlmConfig` (e.g., `"openai/text-embedding-3-small"`). |


---

### CodeContentMode

Content rendering mode for code extraction.

Controls how extracted code content is represented in the `content` field
of `ExtractionResult`.

| Value | Description |
|-------|-------------|
| `Chunks` | Use TSLP semantic chunks as content (default). |
| `Raw` | Use raw source code as content. |
| `Structure` | Emit function/class headings + docstrings (no code bodies). |


---

### SecurityError

Security validation errors.

| Value | Description |
|-------|-------------|
| `ZipBombDetected` | Potential ZIP bomb detected |
| `ArchiveTooLarge` | Archive exceeds maximum size |
| `TooManyFiles` | Archive contains too many files |
| `NestingTooDeep` | Nesting too deep |
| `ContentTooLarge` | Content exceeds maximum size |
| `EntityTooLong` | Entity/string too long |
| `TooManyIterations` | Too many iterations |
| `XmlDepthExceeded` | XML depth exceeded |
| `TooManyCells` | Too many table cells |


---

### PdfAnnotationType

Type of PDF annotation.

| Value | Description |
|-------|-------------|
| `Text` | Sticky note / text annotation |
| `Highlight` | Highlighted text region |
| `Link` | Hyperlink annotation |
| `Stamp` | Rubber stamp annotation |
| `Underline` | Underline text markup |
| `StrikeOut` | Strikeout text markup |
| `Other` | Any other annotation type |


---

### BlockType

Types of block-level elements in Djot.

| Value | Description |
|-------|-------------|
| `Paragraph` | Paragraph element |
| `Heading` | Heading element |
| `Blockquote` | Blockquote element |
| `CodeBlock` | Code block |
| `ListItem` | List item |
| `OrderedList` | Ordered list |
| `BulletList` | Bullet list |
| `TaskList` | Task list |
| `DefinitionList` | Definition list |
| `DefinitionTerm` | Definition term |
| `DefinitionDescription` | Definition description |
| `Div` | Div |
| `Section` | Section element |
| `ThematicBreak` | Thematic break |
| `RawBlock` | Raw block |
| `MathDisplay` | Math display |


---

### InlineType

Types of inline elements in Djot.

| Value | Description |
|-------|-------------|
| `Text` | Text format |
| `Strong` | Strong |
| `Emphasis` | Emphasis |
| `Highlight` | Highlight |
| `Subscript` | Subscript |
| `Superscript` | Superscript |
| `Insert` | Insert |
| `Delete` | Delete |
| `Code` | Code |
| `Link` | Link |
| `Image` | Image element |
| `Span` | Span |
| `Math` | Math |
| `RawInline` | Raw inline |
| `FootnoteRef` | Footnote ref |
| `Symbol` | Symbol |


---

### RelationshipKind

Semantic kind of a relationship between document elements.

| Value | Description |
|-------|-------------|
| `FootnoteReference` | Footnote marker -> footnote definition. |
| `CitationReference` | Citation marker -> bibliography entry. |
| `InternalLink` | Internal anchor link (`#id`) -> target heading/element. |
| `Caption` | Caption paragraph -> figure/table it describes. |
| `Label` | Label -> labeled element (HTML `<label for>`, LaTeX `\label{}`). |
| `TocEntry` | TOC entry -> target section. |
| `CrossReference` | Cross-reference (LaTeX `\ref{}`, DOCX cross-reference field). |


---

### ContentLayer

Content layer classification for document nodes.

Replaces separate body/furniture arrays with per-node granularity.

| Value | Description |
|-------|-------------|
| `Body` | Main document body content. |
| `Header` | Page/section header (running header). |
| `Footer` | Page/section footer (running footer). |
| `Footnote` | Footnote content. |


---

### NodeContent

Tagged enum for node content. Each variant carries only type-specific data.

Uses `#[serde(tag = "node_type")]` to avoid "type" keyword collision in
Go/Java/TypeScript bindings.

| Value | Description |
|-------|-------------|
| `Title` | Document title. |
| `Heading` | Section heading with level (1-6). |
| `Paragraph` | Body text paragraph. |
| `List` | List container — children are `ListItem` nodes. |
| `ListItem` | Individual list item. |
| `Table` | Table with structured cell grid. |
| `Image` | Image reference. |
| `Code` | Code block. |
| `Quote` | Block quote — container, children carry the quoted content. |
| `Formula` | Mathematical formula / equation. |
| `Footnote` | Footnote reference content. |
| `Group` | Logical grouping container (section, key-value area). `heading_level` + `heading_text` capture the section heading directly rather than relying on a first-child positional convention. |
| `PageBreak` | Page break marker. |
| `Slide` | Presentation slide container — children are the slide's content nodes. |
| `DefinitionList` | Definition list container — children are `DefinitionItem` nodes. |
| `DefinitionItem` | Individual definition list entry with term and definition. |
| `Citation` | Citation or bibliographic reference. |
| `Admonition` | Admonition / callout container (note, warning, tip, etc.). Children carry the admonition body content. |
| `RawBlock` | Raw block preserved verbatim from the source format. Used for content that cannot be mapped to a semantic node type (e.g. JSX in MDX, raw LaTeX in markdown, embedded HTML). |
| `MetadataBlock` | Structured metadata block (email headers, YAML frontmatter, etc.). |


---

### AnnotationKind

Types of inline text annotations.

| Value | Description |
|-------|-------------|
| `Bold` | Bold |
| `Italic` | Italic |
| `Underline` | Underline |
| `Strikethrough` | Strikethrough |
| `Code` | Code |
| `Subscript` | Subscript |
| `Superscript` | Superscript |
| `Link` | Link |
| `Highlight` | Highlighted text (PDF highlights, HTML `<mark>`). |
| `Color` | Text color (CSS-compatible value, e.g. "#ff0000", "red"). |
| `FontSize` | Font size with units (e.g. "12pt", "1.2em", "16px"). |
| `Custom` | Extensible annotation for format-specific styling. |


---

### ChunkType

Semantic structural classification of a text chunk.

Assigned by the heuristic classifier in `chunking::classifier`.
Defaults to `Unknown` when no rule matches.
Designed to be extended in future versions without breaking changes.

| Value | Description |
|-------|-------------|
| `Heading` | Section heading or document title. |
| `PartyList` | Party list: names, addresses, and signatories. |
| `Definitions` | Definition clause ("X means…", "X shall mean…"). |
| `OperativeClause` | Operative clause containing legal/contractual action verbs. |
| `SignatureBlock` | Signature block with signatures, names, and dates. |
| `Schedule` | Schedule, annex, appendix, or exhibit section. |
| `TableLike` | Table-like content with aligned columns or repeated patterns. |
| `Formula` | Mathematical formula or equation. |
| `CodeBlock` | Code block or preformatted content. |
| `Image` | Embedded or referenced image content. |
| `OrgChart` | Organizational chart or hierarchy diagram. |
| `Diagram` | Diagram, figure, or visual illustration. |
| `Unknown` | Unclassified or mixed content. |


---

### ElementType

Semantic element type classification.

Categorizes text content into semantic units for downstream processing.
Supports the element types commonly found in Unstructured documents.

| Value | Description |
|-------|-------------|
| `Title` | Document title |
| `NarrativeText` | Main narrative text body |
| `Heading` | Section heading |
| `ListItem` | List item (bullet, numbered, etc.) |
| `Table` | Table element |
| `Image` | Image element |
| `PageBreak` | Page break marker |
| `CodeBlock` | Code block |
| `BlockQuote` | Block quote |
| `Footer` | Footer text |
| `Header` | Header text |


---

### ElementKind

Semantic role of an internal element.

Superset of `NodeContent` variants
plus OCR and container markers.

| Value | Description |
|-------|-------------|
| `Title` | Document title. |
| `Heading` | Section heading with level (1-6). |
| `Paragraph` | Body text paragraph. |
| `ListItem` | List item. `ordered` indicates numbered vs bulleted. |
| `Code` | Code block. Language stored in element attributes. |
| `Formula` | Mathematical formula / equation. |
| `FootnoteDefinition` | Footnote content (the definition, not the reference marker). |
| `FootnoteRef` | Footnote reference marker in body text. |
| `Citation` | Citation or bibliographic reference. |
| `Slide` | Presentation slide container. |
| `DefinitionTerm` | Definition list term. |
| `DefinitionDescription` | Definition list description. |
| `Admonition` | Admonition / callout (note, warning, tip, etc.). Kind stored in attributes. |
| `RawBlock` | Raw block preserved verbatim. Format stored in attributes. |
| `MetadataBlock` | Structured metadata block (frontmatter, email headers). |
| `ListStart` | Start of a list container. |
| `ListEnd` | End of a list container. |
| `QuoteStart` | Start of a block quote. |
| `QuoteEnd` | End of a block quote. |
| `GroupStart` | Start of a generic group/section. |
| `GroupEnd` | End of a generic group/section. |
| `Table` | Table reference. `table_index` is an index into `InternalDocument.tables`. |
| `Image` | Image reference. `image_index` is an index into `InternalDocument.images`. |
| `PageBreak` | Page break marker. |
| `OcrText` | OCR-detected text at a given hierarchical level. |


---

### RelationshipTarget

Target of a relationship — either a resolved element index or an unresolved key.

| Value | Description |
|-------|-------------|
| `Index` | Resolved: index into `InternalDocument.elements`. |
| `Key` | Unresolved: key to be matched against element anchors during derivation. |


---

### FormatMetadata

Format-specific metadata (discriminated union).

Only one format type can exist per extraction result. This provides
type-safe, clean metadata without nested optionals.

| Value | Description |
|-------|-------------|
| `Pdf` | Pdf format |
| `Docx` | Docx format |
| `Excel` | Excel |
| `Email` | Email |
| `Pptx` | Pptx format |
| `Archive` | Archive |
| `Image` | Image element |
| `Xml` | Xml format |
| `Text` | Text format |
| `Html` | Html format |
| `Ocr` | Ocr |
| `Csv` | Csv format |
| `Bibtex` | Bibtex |
| `Citation` | Citation |
| `FictionBook` | Fiction book |
| `Dbf` | Dbf |
| `Jats` | Jats |
| `Epub` | Epub format |
| `Pst` | Pst |
| `Code` | Code |


---

### TextDirection

Text direction enumeration for HTML documents.

| Value | Description |
|-------|-------------|
| `LeftToRight` | Left-to-right text direction |
| `RightToLeft` | Right-to-left text direction |
| `Auto` | Automatic text direction detection |


---

### LinkType

Link type classification.

| Value | Description |
|-------|-------------|
| `Anchor` | Anchor link (#section) |
| `Internal` | Internal link (same domain) |
| `External` | External link (different domain) |
| `Email` | Email link (mailto:) |
| `Phone` | Phone link (tel:) |
| `Other` | Other link type |


---

### ImageType

Image type classification.

| Value | Description |
|-------|-------------|
| `DataUri` | Data URI image |
| `InlineSvg` | Inline SVG |
| `External` | External image URL |
| `Relative` | Relative path image |


---

### StructuredDataType

Structured data type classification.

| Value | Description |
|-------|-------------|
| `JsonLd` | JSON-LD structured data |
| `Microdata` | Microdata |
| `RdFa` | RDFa |


---

### OcrBoundingGeometry

Bounding geometry for an OCR element.

Supports both axis-aligned rectangles (from Tesseract) and 4-point quadrilaterals
(from PaddleOCR and rotated text detection).

| Value | Description |
|-------|-------------|
| `Rectangle` | Axis-aligned bounding box (typical for Tesseract output). |
| `Quadrilateral` | 4-point quadrilateral for rotated/skewed text (PaddleOCR). Points are in clockwise order starting from top-left: `[top_left, top_right, bottom_right, bottom_left]` |


---

### OcrElementLevel

Hierarchical level of an OCR element.

Maps to Tesseract's page segmentation hierarchy and provides
equivalent semantics for PaddleOCR.

| Value | Description |
|-------|-------------|
| `Word` | Individual word |
| `Line` | Line of text (default for PaddleOCR) |
| `Block` | Paragraph or text block |
| `Page` | Page-level element |


---

### PageUnitType

Type of paginated unit in a document.

Distinguishes between different types of "pages" (PDF pages, presentation slides, spreadsheet sheets).

| Value | Description |
|-------|-------------|
| `Page` | Standard document pages (PDF, DOCX, images) |
| `Slide` | Presentation slides (PPTX, ODP) |
| `Sheet` | Spreadsheet sheets (XLSX, ODS) |


---

### UriKind

Semantic classification of an extracted URI.

| Value | Description |
|-------|-------------|
| `Hyperlink` | A clickable hyperlink (web URL, file link). |
| `Image` | An image or media resource reference. |
| `Anchor` | An internal anchor or cross-reference target. |
| `Citation` | A citation or bibliographic reference (DOI, academic ref). |
| `Reference` | A general reference (e.g. `\ref{}` in LaTeX, `:ref:` in RST). |
| `Email` | An email address (`mailto:` link or bare email). |


---

### PoolError

Error type for pool operations.

| Value | Description |
|-------|-------------|
| `LockPoisoned` | The pool's internal mutex was poisoned. This indicates a panic occurred while holding the lock. The pool is in a locked state and cannot be recovered. |


---

### ExtractionSource

The source of a document to extract.

| Value | Description |
|-------|-------------|
| `File` | Extract from a filesystem path with an optional MIME type hint. |
| `Bytes` | Extract from in-memory bytes with a known MIME type. |


---

### KeywordAlgorithm

Keyword algorithm selection.

| Value | Description |
|-------|-------------|
| `Yake` | YAKE (Yet Another Keyword Extractor) - statistical approach |
| `Rake` | RAKE (Rapid Automatic Keyword Extraction) - co-occurrence based |


---

### OcrError

OCR-specific errors (pure Rust, no PyO3)

| Value | Description |
|-------|-------------|
| `TesseractInitializationFailed` | Tesseract initialization failed |
| `UnsupportedVersion` | Unsupported version |
| `InvalidConfiguration` | Invalid configuration |
| `InvalidLanguageCode` | Invalid language code |
| `ImageProcessingFailed` | Image processing failed |
| `ProcessingFailed` | Processing failed |
| `CacheError` | Cache error |
| `IoError` | I o error |


---

### PsmMode

Page Segmentation Mode for Tesseract OCR

| Value | Description |
|-------|-------------|
| `OsdOnly` | Osd only |
| `AutoOsd` | Auto osd |
| `AutoOnly` | Auto only |
| `Auto` | Auto |
| `SingleColumn` | Single column |
| `SingleBlockVertical` | Single block vertical |
| `SingleBlock` | Single block |
| `SingleLine` | Single line |
| `SingleWord` | Single word |
| `CircleWord` | Circle word |
| `SingleChar` | Single char |


---

### LayoutClass

The 17 canonical document layout classes.

All model backends (RT-DETR, YOLO, etc.) map their native class IDs
to this shared set. Models with fewer classes (DocLayNet: 11, PubLayNet: 5)
map to the closest equivalent.

| Value | Description |
|-------|-------------|
| `Caption` | Caption element |
| `Footnote` | Footnote element |
| `Formula` | Formula |
| `ListItem` | List item |
| `PageFooter` | Page footer |
| `PageHeader` | Page header |
| `Picture` | Picture |
| `SectionHeader` | Section header |
| `Table` | Table element |
| `Text` | Text format |
| `Title` | Title element |
| `DocumentIndex` | Document index |
| `Code` | Code |
| `CheckboxSelected` | Checkbox selected |
| `CheckboxUnselected` | Checkbox unselected |
| `Form` | Form |
| `KeyValueRegion` | Key value region |


---

### PdfError

| Value | Description |
|-------|-------------|
| `InvalidPdf` | Invalid pdf |
| `PasswordRequired` | Password required |
| `InvalidPassword` | Invalid password |
| `EncryptionNotSupported` | Encryption not supported |
| `PageNotFound` | Page not found |
| `TextExtractionFailed` | Text extraction failed |
| `RenderingFailed` | Rendering failed |
| `MetadataExtractionFailed` | Metadata extraction failed |
| `ExtractionFailed` | Extraction failed |
| `FontLoadingFailed` | Font loading failed |
| `IoError` | I o error |


---

### HwpError

Error type for HWP parsing.

| Value | Description |
|-------|-------------|
| `InvalidFormat` | The file does not match the HWP 5.0 format. |
| `UnsupportedVersion` | The HWP version or a feature is not supported (e.g. password-encrypted docs). |
| `Io` | An underlying I/O error occurred. |
| `Cfb` | A CFB compound-file error (stream not found, corrupt container, etc.). |
| `CompressionError` | Decompression of a zlib/deflate stream failed. |
| `ParseError` | The binary record stream could not be parsed. |
| `EncodingError` | A UTF-16LE string contained invalid data. |
| `NotFound` | A requested stream was not present in the compound file. |


---

### DrawingType

Whether the drawing is inline or anchored.

| Value | Description |
|-------|-------------|
| `Inline` | Inline |
| `Anchored` | Anchored |


---

### WrapType

Text wrapping type.

| Value | Description |
|-------|-------------|
| `None` | None |
| `Square` | Square |
| `Tight` | Tight |
| `TopAndBottom` | Top and bottom |
| `Through` | Through |


---

### FracType

| Value | Description |
|-------|-------------|
| `Bar` | Bar |
| `NoBar` | No bar |
| `Linear` | Linear |
| `Skewed` | Skewed |


---

### MathNode

| Value | Description |
|-------|-------------|
| `Run` | Plain text from m:r/m:t |
| `SSup` | Superscript: base^{sup} |
| `SSub` | Subscript: base_{sub} |
| `SSubSup` | Sub-superscript: base_{sub}^{sup} |
| `Frac` | Fraction: \frac{num}{den} |
| `Rad` | Radical: \sqrt{body} or \sqrt[deg]{body} |
| `Nary` | N-ary operator: \sum_{sub}^{sup}{body} |
| `Delim` | Delimiter: \left( ... \right) |
| `Func` | Function: \funcname{body} |
| `Acc` | Accent: \hat{body} |
| `EqArr` | Equation array: \begin{aligned}...\end{aligned} |
| `LimLow` | Lower limit: \underset{lim}{body} |
| `LimUpp` | Upper limit: \overset{lim}{body} |
| `Bar` | Bar (overline/underline) |
| `BorderBox` | Border box: \boxed{body} |
| `Matrix` | Matrix: \begin{matrix}...\end{matrix} |
| `Group` | Grouping container (m:box, m:phant, etc.) — passes through children |
| `SPre` | Pre-sub-superscript: {}_{sub}^{sup}{base} |


---

### DocumentElement

Tracks document element ordering (paragraphs, tables, and drawings interleaved).

| Value | Description |
|-------|-------------|
| `Paragraph` | Paragraph element |
| `Table` | Table element |
| `Drawing` | Drawing |


---

### ListType

| Value | Description |
|-------|-------------|
| `Bullet` | Bullet |
| `Numbered` | Numbered |


---

### HeaderFooterType

| Value | Description |
|-------|-------------|
| `Default` | Default |
| `First` | First |
| `Even` | Even |
| `Odd` | Odd |


---

### NoteType

| Value | Description |
|-------|-------------|
| `Footnote` | Footnote element |
| `Endnote` | Endnote |


---

### Orientation

Page orientation.

| Value | Description |
|-------|-------------|
| `Portrait` | Portrait |
| `Landscape` | Landscape |


---

### StyleType

The type of a style definition in DOCX.

| Value | Description |
|-------|-------------|
| `Paragraph` | Paragraph element |
| `Character` | Character |
| `Table` | Table element |
| `Numbering` | Numbering |


---

### VerticalMerge

Vertical merge state.

| Value | Description |
|-------|-------------|
| `Restart` | Restart |
| `Continue` | Continue |


---

### ThemeColor

A theme color definition, either direct RGB or a system color with fallback.

| Value | Description |
|-------|-------------|
| `Rgb` | Direct hex RGB color (e.g., "156082"). |
| `System` | System color with fallback RGB (e.g., "windowText" with lastClr "000000"). |


---

### Pooling

Pooling strategy for extracting a single vector from token embeddings.

| Value | Description |
|-------|-------------|
| `Cls` | Use the [CLS] token embedding (first token). |
| `Mean` | Mean of all token embeddings, weighted by attention mask. |


---

### EmbedError

Embedding engine errors.

| Value | Description |
|-------|-------------|
| `Tokenizer` | Tokenizer |
| `Ort` | Ort |
| `Shape` | Shape |
| `NoOutput` | No output |


---

### ModelBackend

Which underlying model architecture to use.

| Value | Description |
|-------|-------------|
| `YoloDocLayNet` | YOLO trained on DocLayNet (11 classes, 640x640 input). |
| `RtDetr` | RT-DETR v2 (17 classes, 640x640 input, NMS-free). |
| `Custom` | Custom model from a local file path. |


---

### CustomModelVariant

Variant selection for custom model paths.

| Value | Description |
|-------|-------------|
| `RtDetr` | Rt detr |
| `YoloDocLayNet` | Yolo doc lay net |
| `YoloDocStructBench` | Yolo doc struct bench |
| `Yolox` | Yolox |


---

### TableType

Table type classification result.

| Value | Description |
|-------|-------------|
| `Wired` | Bordered table with visible gridlines. |
| `Wireless` | Borderless table without visible gridlines. |


---

### TatrClass

TATR object detection class labels.

The 7 classes output by the Table Transformer model. `NoObject` (class 6)
is the background/padding class and is filtered out during post-processing.

| Value | Description |
|-------|-------------|
| `Table` | Full table bounding box (class 0). |
| `Column` | Table column (class 1). |
| `Row` | Table row (class 2). |
| `ColumnHeader` | Column header row (class 3). |
| `ProjectedRowHeader` | Projected row header column (class 4). |
| `SpanningCell` | Spanning cell covering multiple rows/columns (class 5). |


---

### YoloVariant

Which YOLO variant this model represents.

| Value | Description |
|-------|-------------|
| `DocLayNet` | YOLOv10/v8 trained on DocLayNet (11 classes). Output: [batch, num_dets, 6] = [x1, y1, x2, y2, score, class_id] |
| `DocStructBench` | DocLayout-YOLO trained on DocStructBench (10 classes). Output: [batch, num_dets, 4+num_classes] center-format, or [batch, num_dets, 6] decoded. |
| `Yolox` | YOLOX with letterbox preprocessing and grid decoding. Output: [batch, num_anchors, 5+num_classes] — needs grid decoding + NMS. Strides: [8, 16, 32], anchors decoded via (raw + grid_offset) * stride. |


---

## Errors

### KreuzbergError

Main error type for all Kreuzberg operations.

All errors in Kreuzberg use this enum, which preserves error chains
and provides context for debugging.

# Variants

- `Io` - File system and I/O errors (always bubble up)
- `Parsing` - Document parsing errors (corrupt files, unsupported features)
- `Ocr` - OCR processing errors
- `Validation` - Input validation errors (invalid paths, config, parameters)
- `Cache` - Cache operation errors (non-fatal, can be ignored)
- `ImageProcessing` - Image manipulation errors
- `Serialization` - JSON/MessagePack serialization errors
- `MissingDependency` - Missing optional dependencies (tesseract, etc.)
- `Plugin` - Plugin-specific errors
- `LockPoisoned` - Mutex/RwLock poisoning (should not happen in normal operation)
- `UnsupportedFormat` - Unsupported MIME type or file format
- `Other` - Catch-all for uncommon errors

| Variant | Description |
|---------|-------------|
| `Io` | IO error: {0} |
| `Parsing` | Parsing error: {message} |
| `Ocr` | OCR error: {message} |
| `Validation` | Validation error: {message} |
| `Cache` | Cache error: {message} |
| `ImageProcessing` | Image processing error: {message} |
| `Serialization` | Serialization error: {message} |
| `MissingDependency` | Missing dependency: {0} |
| `Plugin` | Plugin error in '{plugin_name}': {message} |
| `LockPoisoned` | Lock poisoned: {0} |
| `UnsupportedFormat` | Unsupported format: {0} |
| `Embedding` | Embedding error: {message} |
| `Timeout` | Extraction timed out after {elapsed_ms}ms (limit: {limit_ms}ms) |
| `Other` | {0} |


---

### LayoutError

| Variant | Description |
|---------|-------------|
| `Ort` | ORT error: {0} |
| `Image` | Image error: {0} |
| `SessionNotInitialized` | Session not initialized |
| `InvalidOutput` | Invalid model output: {0} |
| `ModelDownload` | Model download failed: {0} |


---

