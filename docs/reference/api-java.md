---
title: "Java API Reference"
---

# Java API Reference <span class="version-badge">v4.8.5</span>

## Functions

### isBatchMode()

Check if we're currently in batch processing mode.

Returns `false` if the task-local is not set (single-file mode).

**Signature:**

```java
public static boolean isBatchMode()
```

**Returns:** `boolean`


---

### resolveThreadBudget()

Resolve the effective thread budget from config or auto-detection.

User-set `max_threads` takes priority. Otherwise auto-detects from `num_cpus`,
capped at 8 for sane defaults in serverless environments.

**Signature:**

```java
public static long resolveThreadBudget(ConcurrencyConfig config)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `config` | `Optional<ConcurrencyConfig>` | No | The configuration options |

**Returns:** `long`


---

### initThreadPools()

Initialize the global Rayon thread pool with the given budget.

Safe to call multiple times — only the first call takes effect (subsequent
calls are silently ignored).

**Signature:**

```java
public static void initThreadPools(long budget)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `budget` | `long` | Yes | The budget |

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

```java
public static ExtractionConfig mergeConfigJson(ExtractionConfig base, Object overrideJson) throws String
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `base` | `ExtractionConfig` | Yes | The extraction config |
| `overrideJson` | `Object` | Yes | The override json |

**Returns:** `ExtractionConfig`

**Errors:** Throws `String`.


---

### buildConfigFromJson()

Build extraction config by optionally merging JSON overrides into a base config.

If `override_json` is `null`, returns a clone of `base`. Otherwise delegates
to `merge_config_json`.

**Signature:**

```java
public static ExtractionConfig buildConfigFromJson(ExtractionConfig base, Object overrideJson) throws String
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `base` | `ExtractionConfig` | Yes | The extraction config |
| `overrideJson` | `Optional<Object>` | No | The override json |

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

```java
public static boolean isValidFormatField(String field)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `field` | `String` | Yes | The field name to validate |

**Returns:** `boolean`


---

### openFileBytes()

Open a file and return its bytes with zero-copy for large files.

On non-WASM targets, files larger than `MMAP_THRESHOLD_BYTES` are
memory-mapped so that the file contents are never copied to the heap.
The mapping is read-only; the file must not be modified while the returned
`FileBytes` is alive, which is safe for document extraction.

On WASM or for small files, falls back to a plain `std.fs.read`.

**Errors:**

Returns `KreuzbergError.Io` for any I/O failure.

**Signature:**

```java
public static FileBytes openFileBytes(String path) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `path` | `String` | Yes | Path to the file |

**Returns:** `FileBytes`

**Errors:** Throws `Error`.


---

### readFileAsync()

Read a file asynchronously.

**Returns:**

The file contents as bytes.

**Errors:**

Returns `KreuzbergError.Io` for I/O errors (these always bubble up).

**Signature:**

```java
public static byte[] readFileAsync(Path path) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `path` | `Path` | Yes | Path to the file to read |

**Returns:** `byte[]`

**Errors:** Throws `Error`.


---

### readFileSync()

Read a file synchronously.

**Returns:**

The file contents as bytes.

**Errors:**

Returns `KreuzbergError.Io` for I/O errors (these always bubble up).

**Signature:**

```java
public static byte[] readFileSync(Path path) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `path` | `Path` | Yes | Path to the file to read |

**Returns:** `byte[]`

**Errors:** Throws `Error`.


---

### fileExists()

Check if a file exists.

**Returns:**

`true` if the file exists, `false` otherwise.

**Signature:**

```java
public static boolean fileExists(Path path)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `path` | `Path` | Yes | Path to check |

**Returns:** `boolean`


---

### validateFileExists()

Validate that a file exists.

**Errors:**

Returns `KreuzbergError.Io` if file doesn't exist.

**Signature:**

```java
public static void validateFileExists(Path path) throws Error
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

Returns `KreuzbergError.Io` for I/O errors.

**Signature:**

```java
public static List<String> findFilesByExtension(Path dir, String extension, boolean recursive) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `dir` | `Path` | Yes | Directory to search |
| `extension` | `String` | Yes | File extension to match (without the dot) |
| `recursive` | `boolean` | Yes | Whether to recursively search subdirectories |

**Returns:** `List<String>`

**Errors:** Throws `Error`.


---

### detectMimeType()

Detect MIME type from a file path.

Uses file extension to determine MIME type. Falls back to `mime_guess` crate
if extension-based detection fails.

**Returns:**

The detected MIME type string.

**Errors:**

Returns `KreuzbergError.Io` if file doesn't exist (when `check_exists` is true).
Returns `KreuzbergError.UnsupportedFormat` if MIME type cannot be determined.

**Signature:**

```java
public static String detectMimeType(Path path, boolean checkExists) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `path` | `Path` | Yes | Path to the file |
| `checkExists` | `boolean` | Yes | Whether to verify file existence |

**Returns:** `String`

**Errors:** Throws `Error`.


---

### validateMimeType()

Validate that a MIME type is supported.

**Returns:**

The validated MIME type (may be normalized).

**Errors:**

Returns `KreuzbergError.UnsupportedFormat` if not supported.

**Signature:**

```java
public static String validateMimeType(String mimeType) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `mimeType` | `String` | Yes | The MIME type to validate |

**Returns:** `String`

**Errors:** Throws `Error`.


---

### detectOrValidate()

Detect or validate MIME type.

If `mime_type` is provided, validates it. Otherwise, detects from `path`.

**Returns:**

The validated MIME type string.

**Signature:**

```java
public static String detectOrValidate(String path, String mimeType) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `path` | `Optional<String>` | No | Optional path to detect MIME type from |
| `mimeType` | `Optional<String>` | No | Optional explicit MIME type to validate |

**Returns:** `String`

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

Returns `KreuzbergError.UnsupportedFormat` if MIME type cannot be determined.

**Signature:**

```java
public static String detectMimeTypeFromBytes(byte[] content) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `content` | `byte[]` | Yes | Raw file bytes |

**Returns:** `String`

**Errors:** Throws `Error`.


---

### getExtensionsForMime()

Get file extensions for a given MIME type.

Returns all known file extensions that map to the specified MIME type.

**Returns:**

A vector of file extensions (without leading dot) for the MIME type.

**Signature:**

```java
public static List<String> getExtensionsForMime(String mimeType) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `mimeType` | `String` | Yes | The MIME type to look up |

**Returns:** `List<String>`

**Errors:** Throws `Error`.


---

### listSupportedFormats()

List all supported document formats.

Returns a list of all file extensions and their corresponding MIME types
that Kreuzberg can process. Derived from the centralized `FORMATS` registry.

The list is sorted alphabetically by file extension.

**Signature:**

```java
public static List<SupportedFormat> listSupportedFormats()
```

**Returns:** `List<SupportedFormat>`


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

```java
public static ExtractionResult runPipeline(InternalDocument doc, ExtractionConfig config) throws Error
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

```java
public static ExtractionResult runPipelineSync(InternalDocument doc, ExtractionConfig config) throws Error
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

```java
public static boolean isPageTextBlank(String text)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `text` | `String` | Yes | The extracted text content of the page |

**Returns:** `boolean`


---

### resolveRelationships()

Resolve `RelationshipTarget.Key` entries to `RelationshipTarget.Index`.

Builds an anchor index from elements with non-`null` anchors, then resolves
each key-based relationship target. Unresolvable keys are logged and skipped
(the relationship is left as `Key` — it will be excluded from the final
`DocumentStructure` relationships).

**Signature:**

```java
public static void resolveRelationships(InternalDocument doc)
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

```java
public static DocumentStructure deriveDocumentStructure(InternalDocument doc)
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

```java
public static ExtractionResult deriveExtractionResult(InternalDocument doc, boolean includeDocumentStructure, OutputFormat outputFormat)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `doc` | `InternalDocument` | Yes | The internal document |
| `includeDocumentStructure` | `boolean` | Yes | The include document structure |
| `outputFormat` | `OutputFormat` | Yes | The output format |

**Returns:** `ExtractionResult`


---

### parseJson()

**Signature:**

```java
public static StructuredDataResult parseJson(byte[] data, JsonExtractionConfig config) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `data` | `byte[]` | Yes | The data |
| `config` | `Optional<JsonExtractionConfig>` | No | The configuration options |

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

```java
public static StructuredDataResult parseJsonl(byte[] data, JsonExtractionConfig config) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `data` | `byte[]` | Yes | The data |
| `config` | `Optional<JsonExtractionConfig>` | No | The configuration options |

**Returns:** `StructuredDataResult`

**Errors:** Throws `Error`.


---

### parseYaml()

**Signature:**

```java
public static StructuredDataResult parseYaml(byte[] data) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `data` | `byte[]` | Yes | The data |

**Returns:** `StructuredDataResult`

**Errors:** Throws `Error`.


---

### parseToml()

**Signature:**

```java
public static StructuredDataResult parseToml(byte[] data) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `data` | `byte[]` | Yes | The data |

**Returns:** `StructuredDataResult`

**Errors:** Throws `Error`.


---

### parseText()

**Signature:**

```java
public static TextExtractionResult parseText(byte[] textBytes, boolean isMarkdown) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `textBytes` | `byte[]` | Yes | The text bytes |
| `isMarkdown` | `boolean` | Yes | The is markdown |

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

```java
public static List<Element> transformExtractionResultToElements(ExtractionResult result)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `result` | `ExtractionResult` | Yes | Reference to the ExtractionResult to transform |

**Returns:** `List<Element>`


---

### parseBodyText()

Parse a raw (possibly compressed) BodyText/SectionN stream.

Returns the list of sections found. Each section contains zero or more
paragraphs that carry the plain-text content.

**Signature:**

```java
public static List<Section> parseBodyText(byte[] data, boolean isCompressed) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `data` | `byte[]` | Yes | The data |
| `isCompressed` | `boolean` | Yes | The is compressed |

**Returns:** `List<Section>`

**Errors:** Throws `Error`.


---

### decompressStream()

Decompress a raw-deflate stream from an HWP section.

HWP 5.0 compresses sections with raw deflate (no zlib header). Falls back
to zlib if raw deflate fails, and returns the data as-is if both fail.

**Signature:**

```java
public static byte[] decompressStream(byte[] data) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `data` | `byte[]` | Yes | The data |

**Returns:** `byte[]`

**Errors:** Throws `Error`.


---

### extractHwpText()

Extract all plain text from an HWP 5.0 document given its raw bytes.

**Errors:**

Returns `HwpError` if the bytes do not form a valid HWP 5.0 compound file,
if the document is password-encrypted, or if a critical parsing step fails.

**Signature:**

```java
public static String extractHwpText(byte[] bytes) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `bytes` | `byte[]` | Yes | The bytes |

**Returns:** `String`

**Errors:** Throws `Error`.


---

### loadImageForOcr()

Load image bytes for OCR, with JPEG 2000 and JBIG2 fallback support.

The standard `image` crate does not support JPEG 2000 or JBIG2 formats.
This function detects these formats by magic bytes and uses `hayro-jpeg2000`
/ `hayro-jbig2` for decoding, falling back to the standard `image` crate
for all other formats.

**Signature:**

```java
public static DynamicImage loadImageForOcr(byte[] imageBytes) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `imageBytes` | `byte[]` | Yes | The image bytes |

**Returns:** `DynamicImage`

**Errors:** Throws `Error`.


---

### extractImageMetadata()

Extract metadata from image bytes.

Extracts dimensions, format, and EXIF data from the image.
Attempts to decode using the standard image crate first, then falls back to
pure Rust JP2 box parsing for JPEG 2000 formats if the standard decoder fails.

**Signature:**

```java
public static ImageMetadata extractImageMetadata(byte[] bytes) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `bytes` | `byte[]` | Yes | The bytes |

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

```java
public static ImageOcrResult extractTextFromImageWithOcr(byte[] bytes, String mimeType, String ocrResult, PageConfig pageConfig) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `bytes` | `byte[]` | Yes | Image file bytes |
| `mimeType` | `String` | Yes | MIME type (e.g., "image/tiff") |
| `ocrResult` | `String` | Yes | OCR backend result containing the text |
| `pageConfig` | `Optional<PageConfig>` | No | Optional page configuration for boundary tracking |

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

An estimated capacity in bytes suitable for `String.with_capacity()`

# Minimum Capacity

All estimates have a minimum of 64 bytes to prevent over-optimization for very
small files where the overhead of capacity estimation outweighs benefits.

**Signature:**

```java
public static long estimateContentCapacity(long fileSize, String format)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `fileSize` | `long` | Yes | The size of the original file in bytes |
| `format` | `String` | Yes | The file format/extension (e.g., "txt", "html", "docx", "xlsx", "pptx") |

**Returns:** `long`


---

### estimateHtmlMarkdownCapacity()

Estimate capacity for HTML to Markdown conversion.

HTML documents typically convert to Markdown with 60-70% of the original size.
This function estimates capacity specifically for HTML→Markdown conversion.

**Returns:**

An estimated capacity for the Markdown output

**Signature:**

```java
public static long estimateHtmlMarkdownCapacity(long htmlSize)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `htmlSize` | `long` | Yes | The size of the HTML file in bytes |

**Returns:** `long`


---

### estimateSpreadsheetCapacity()

Estimate capacity for cell extraction from spreadsheets.

When extracting cell data from Excel/ODS files, the extracted cells are typically
40% of the compressed file size (since the file is ZIP-compressed).

**Returns:**

An estimated capacity for cell value accumulation

**Signature:**

```java
public static long estimateSpreadsheetCapacity(long fileSize)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `fileSize` | `long` | Yes | Size of the spreadsheet file (XLSX, ODS, etc.) |

**Returns:** `long`


---

### estimatePresentationCapacity()

Estimate capacity for slide content extraction from presentations.

PPTX files when extracted have slide content at approximately 35% of the file size.
This accounts for XML overhead, compression, and embedded assets.

**Returns:**

An estimated capacity for slide content accumulation

**Signature:**

```java
public static long estimatePresentationCapacity(long fileSize)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `fileSize` | `long` | Yes | Size of the PPTX file in bytes |

**Returns:** `long`


---

### estimateTableMarkdownCapacity()

Estimate capacity for markdown table generation.

Markdown tables have predictable size: ~12 bytes per cell on average
(accounting for separators, pipes, padding, and cell content).

**Returns:**

An estimated capacity for the markdown table output

**Signature:**

```java
public static long estimateTableMarkdownCapacity(long rowCount, long colCount)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `rowCount` | `long` | Yes | Number of rows in the table |
| `colCount` | `long` | Yes | Number of columns in the table |

**Returns:** `long`


---

### parseEmlContent()

Parse .eml file content (RFC822 format)

**Signature:**

```java
public static EmailExtractionResult parseEmlContent(byte[] data) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `data` | `byte[]` | Yes | The data |

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

```java
public static EmailExtractionResult parseMsgContent(byte[] data, int fallbackCodepage) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `data` | `byte[]` | Yes | The data |
| `fallbackCodepage` | `Optional<int>` | No | The fallback codepage |

**Returns:** `EmailExtractionResult`

**Errors:** Throws `Error`.


---

### extractEmailContent()

Extract email content from either .eml or .msg format

**Signature:**

```java
public static EmailExtractionResult extractEmailContent(byte[] data, String mimeType, int fallbackCodepage) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `data` | `byte[]` | Yes | The data |
| `mimeType` | `String` | Yes | The mime type |
| `fallbackCodepage` | `Optional<int>` | No | The fallback codepage |

**Returns:** `EmailExtractionResult`

**Errors:** Throws `Error`.


---

### buildEmailTextOutput()

Build text output from email extraction result

**Signature:**

```java
public static String buildEmailTextOutput(EmailExtractionResult result)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `result` | `EmailExtractionResult` | Yes | The email extraction result |

**Returns:** `String`


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

```java
public static VecEmailExtractionResultVecProcessingWarning extractPstMessages(byte[] pstData) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pstData` | `byte[]` | Yes | Raw bytes of the PST file |

**Returns:** `VecEmailExtractionResultVecProcessingWarning`

**Errors:** Throws `Error`.


---

### readExcelFile()

**Signature:**

```java
public static ExcelWorkbook readExcelFile(String filePath) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `filePath` | `String` | Yes | Path to the file |

**Returns:** `ExcelWorkbook`

**Errors:** Throws `Error`.


---

### readExcelBytes()

**Signature:**

```java
public static ExcelWorkbook readExcelBytes(byte[] data, String fileExtension) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `data` | `byte[]` | Yes | The data |
| `fileExtension` | `String` | Yes | The file extension |

**Returns:** `ExcelWorkbook`

**Errors:** Throws `Error`.


---

### excelToText()

Convert an Excel workbook to plain text (space-separated cells, one row per line).

Each sheet is separated by a blank line. Sheet names are included as headers.
This produces text suitable for quality scoring against ground truth.

**Signature:**

```java
public static String excelToText(ExcelWorkbook workbook)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `workbook` | `ExcelWorkbook` | Yes | The excel workbook |

**Returns:** `String`


---

### excelToMarkdown()

**Signature:**

```java
public static String excelToMarkdown(ExcelWorkbook workbook)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `workbook` | `ExcelWorkbook` | Yes | The excel workbook |

**Returns:** `String`


---

### extractDocText()

Extract text from DOC bytes.

Parses the OLE/CFB compound document, reads the FIB (File Information Block),
and extracts text from the piece table.

**Signature:**

```java
public static DocExtractionResult extractDocText(byte[] content) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `content` | `byte[]` | Yes | The content to process |

**Returns:** `DocExtractionResult`

**Errors:** Throws `Error`.


---

### parseDrawing()

Parse a drawing object starting after the `<w:drawing>` Start event.

This function reads events until it encounters the closing `</w:drawing>` tag,
parsing the drawing type (inline or anchored), extent, properties, and image references.

**Signature:**

```java
public static Drawing parseDrawing(Reader reader)
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

```java
public static String collectAndConvertOmathPara(Reader reader)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `reader` | `Reader` | Yes | The reader |

**Returns:** `String`


---

### collectAndConvertOmath()

Collect an `m:oMath` subtree and convert to LaTeX (inline math).
The reader should be positioned right after the `<m:oMath>` start tag.

**Signature:**

```java
public static String collectAndConvertOmath(Reader reader)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `reader` | `Reader` | Yes | The reader |

**Returns:** `String`


---

### parseDocument()

Parse a DOCX document from bytes and return the structured document.

**Signature:**

```java
public static Document parseDocument(byte[] bytes) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `bytes` | `byte[]` | Yes | The bytes |

**Returns:** `Document`

**Errors:** Throws `Error`.


---

### extractTextFromBytes()

Extract text from DOCX bytes.

**Signature:**

```java
public static String extractTextFromBytes(byte[] bytes) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `bytes` | `byte[]` | Yes | The bytes |

**Returns:** `String`

**Errors:** Throws `Error`.


---

### parseSectionProperties()

Parse a `w:sectPr` XML element (roxmltree node) into `SectionProperties`.

**Signature:**

```java
public static SectionProperties parseSectionProperties(Node node)
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

```java
public static SectionProperties parseSectionPropertiesStreaming(Reader reader)
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

```java
public static StyleCatalog parseStylesXml(String xml) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `xml` | `String` | Yes | The xml |

**Returns:** `StyleCatalog`

**Errors:** Throws `Error`.


---

### parseTableProperties()

Parse table-level properties from streaming XML reader.

Expects the reader to be positioned just after the `<w:tblPr>` start tag.
Reads all child elements until the matching `</w:tblPr>` end tag.

**Signature:**

```java
public static TableProperties parseTableProperties(Reader reader)
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

```java
public static RowProperties parseRowProperties(Reader reader)
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

```java
public static CellProperties parseCellProperties(Reader reader)
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

```java
public static TableGrid parseTableGrid(Reader reader)
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

```java
public static Theme parseThemeXml(String xml) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `xml` | `String` | Yes | The theme XML content as a string |

**Returns:** `Theme`

**Errors:** Throws `Error`.


---

### extractText()

Extract text from DOCX bytes.

**Signature:**

```java
public static String extractText(byte[] bytes) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `bytes` | `byte[]` | Yes | The bytes |

**Returns:** `String`

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

```java
public static StringOptionVecPageBoundary extractTextWithPageBreaks(byte[] bytes) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `bytes` | `byte[]` | Yes | The DOCX file contents as bytes |

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

```java
public static Optional<List<PageBoundary>> detectPageBreaksFromDocx(byte[] bytes) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `bytes` | `byte[]` | Yes | The DOCX file contents (ZIP archive) |

**Returns:** `Optional<List<PageBoundary>>`

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

```java
public static VecArchiveEntryVecProcessingWarning extractOoxmlEmbeddedObjects(byte[] zipBytes, String embeddingsPrefix, String sourceLabel, ExtractionConfig config)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `zipBytes` | `byte[]` | Yes | The zip bytes |
| `embeddingsPrefix` | `String` | Yes | The embeddings prefix |
| `sourceLabel` | `String` | Yes | The source label |
| `config` | `ExtractionConfig` | Yes | The configuration options |

**Returns:** `VecArchiveEntryVecProcessingWarning`


---

### detectImageFormat()

Detect image format from raw bytes using magic byte signatures.

Returns a format string like "jpeg", "png", etc. Used by both DOCX and PPTX extractors.

**Signature:**

```java
public static Str detectImageFormat(byte[] data)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `data` | `byte[]` | Yes | The data |

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

```java
public static List<ExtractedImage> processImagesWithOcr(List<ExtractedImage> images, ExtractionConfig config) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `images` | `List<ExtractedImage>` | Yes | The images |
| `config` | `ExtractionConfig` | Yes | The configuration options |

**Returns:** `List<ExtractedImage>`

**Errors:** Throws `Error`.


---

### extractPptText()

Extract text from PPT bytes.

Parses the OLE/CFB compound document, reads the "PowerPoint Document" stream,
and extracts text from TextCharsAtom and TextBytesAtom records.

When `include_master_slides` is `true`, master slide content (placeholder text
like "Click to edit Master title style") is included instead of being skipped.

**Signature:**

```java
public static PptExtractionResult extractPptText(byte[] content) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `content` | `byte[]` | Yes | The content to process |

**Returns:** `PptExtractionResult`

**Errors:** Throws `Error`.


---

### extractPptTextWithOptions()

Extract text from PPT bytes with configurable master slide inclusion.

When `include_master_slides` is `true`, `RT_MAIN_MASTER` containers are not
skipped, so master slide placeholder text is included in the output.

**Signature:**

```java
public static PptExtractionResult extractPptTextWithOptions(byte[] content, boolean includeMasterSlides) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `content` | `byte[]` | Yes | The content to process |
| `includeMasterSlides` | `boolean` | Yes | The include master slides |

**Returns:** `PptExtractionResult`

**Errors:** Throws `Error`.


---

### extractPptxFromPath()

Extract PPTX content from a file path.

**Returns:**

A `PptxExtractionResult` containing extracted content, metadata, and images.

**Signature:**

```java
public static PptxExtractionResult extractPptxFromPath(String path, PptxExtractionOptions options) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `path` | `String` | Yes | Path to the PPTX file |
| `options` | `PptxExtractionOptions` | Yes | Extraction options controlling image extraction, formatting, etc. |

**Returns:** `PptxExtractionResult`

**Errors:** Throws `Error`.


---

### extractPptxFromBytes()

Extract PPTX content from a byte buffer.

**Returns:**

A `PptxExtractionResult` containing extracted content, metadata, and images.

**Signature:**

```java
public static PptxExtractionResult extractPptxFromBytes(byte[] data, PptxExtractionOptions options) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `data` | `byte[]` | Yes | Raw PPTX file bytes |
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

```java
public static XmlExtractionResult parseXmlSvg(byte[] xmlBytes, boolean preserveWhitespace) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `xmlBytes` | `byte[]` | Yes | The xml bytes |
| `preserveWhitespace` | `boolean` | Yes | The preserve whitespace |

**Returns:** `XmlExtractionResult`

**Errors:** Throws `Error`.


---

### parseXml()

**Signature:**

```java
public static XmlExtractionResult parseXml(byte[] xmlBytes, boolean preserveWhitespace) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `xmlBytes` | `byte[]` | Yes | The xml bytes |
| `preserveWhitespace` | `boolean` | Yes | The preserve whitespace |

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

```java
public static String cellsToText(List<List<String>> cells)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `cells` | `List<List<String>>` | Yes | A slice of vectors representing table rows, where each inner vector contains cell values |

**Returns:** `String`


---

### cellsToMarkdown()

**Signature:**

```java
public static String cellsToMarkdown(List<List<String>> cells)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `cells` | `List<List<String>>` | Yes | The cells |

**Returns:** `String`


---

### parseJotdownAttributes()

Parse jotdown attributes into our Attributes representation.

Converts jotdown's internal attribute representation to Kreuzberg's
standardized Attributes struct, handling IDs, classes, and key-value pairs.

**Signature:**

```java
public static Attributes parseJotdownAttributes(Attributes attrs)
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

```java
public static String renderAttributes(Attributes attrs)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `attrs` | `Attributes` | Yes | The attributes |

**Returns:** `String`


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

```java
public static String djotContentToDjot(DjotContent content)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `content` | `DjotContent` | Yes | The DjotContent to convert |

**Returns:** `String`


---

### extractionResultToDjot()

Convert any ExtractionResult to djot format.

This function converts an `ExtractionResult` to djot markup:
- If `djot_content` is `Some`, uses `djot_content_to_djot` for full fidelity conversion
- Otherwise, wraps the plain text content in paragraphs

**Returns:**

A `Result` containing the djot markup string

**Signature:**

```java
public static String extractionResultToDjot(ExtractionResult result) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `result` | `ExtractionResult` | Yes | The ExtractionResult to convert |

**Returns:** `String`

**Errors:** Throws `Error`.


---

### djotToHtml()

Render djot content to HTML.

This function takes djot source text and renders it to HTML using jotdown's
built-in HTML renderer.

**Returns:**

A `Result` containing the rendered HTML string

**Signature:**

```java
public static String djotToHtml(String djotSource) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `djotSource` | `String` | Yes | The djot markup text to render |

**Returns:** `String`

**Errors:** Throws `Error`.


---

### renderBlockToDjot()

Render a single block to djot markup.

**Signature:**

```java
public static void renderBlockToDjot(String output, FormattedBlock block, long indentLevel)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `output` | `String` | Yes | The output destination |
| `block` | `FormattedBlock` | Yes | The formatted block |
| `indentLevel` | `long` | Yes | The indent level |

**Returns:** `void`


---

### renderListItem()

Render a list item with the given marker.

**Signature:**

```java
public static void renderListItem(String output, FormattedBlock item, String indent, String marker)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `output` | `String` | Yes | The output destination |
| `item` | `FormattedBlock` | Yes | The formatted block |
| `indent` | `String` | Yes | The indent |
| `marker` | `String` | Yes | The marker |

**Returns:** `void`


---

### renderInlineContent()

Render inline content to djot markup.

**Signature:**

```java
public static void renderInlineContent(String output, List<InlineElement> elements)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `output` | `String` | Yes | The output destination |
| `elements` | `List<InlineElement>` | Yes | The elements |

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

```java
public static OptionYamlValueString extractFrontmatter(String content)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `content` | `String` | Yes | The content to process |

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

```java
public static Metadata extractMetadataFromYaml(YamlValue yaml)
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

```java
public static Optional<String> extractTitleFromContent(String content)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `content` | `String` | Yes | The document content to search |

**Returns:** `Optional<String>`


---

### collectIwaPaths()

Collects all .iwa file paths from a ZIP archive.

Opens the ZIP from `content`, iterates every entry, and returns the names of
all entries whose path ends with `.iwa`. Entries that cannot be read are
silently skipped (consistent with the per-extractor `filter_map` pattern).

**Signature:**

```java
public static List<String> collectIwaPaths(byte[] content) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `content` | `byte[]` | Yes | The content to process |

**Returns:** `List<String>`

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

```java
public static byte[] readIwaFile(byte[] content, String path) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `content` | `byte[]` | Yes | The content to process |
| `path` | `String` | Yes | Path to the file |

**Returns:** `byte[]`

**Errors:** Throws `Error`.


---

### decodeIwaStream()

Decode an Apple IWA byte stream into the raw protobuf payload.

IWA framing: each block = 1 byte type + 3 bytes LE length + N bytes payload
- type 0x00 → Snappy-compressed, decompress with `snap.raw.Decoder`
- type 0x01 → Uncompressed, use as-is

**Signature:**

```java
public static byte[] decodeIwaStream(byte[] data) throws String
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `data` | `byte[]` | Yes | The data |

**Returns:** `byte[]`

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

```java
public static List<String> extractTextFromProto(byte[] data)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `data` | `byte[]` | Yes | The data |

**Returns:** `List<String>`


---

### extractTextFromIwaFiles()

Extract all text from an iWork ZIP archive by reading specified IWA entries.

`iwa_paths` should list the IWA file paths to read (e.g. `["Index/Document.iwa"]`).
Returns a flat joined string of all text found across all IWA files.

**Signature:**

```java
public static String extractTextFromIwaFiles(byte[] content, List<String> iwaPaths) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `content` | `byte[]` | Yes | The content to process |
| `iwaPaths` | `List<String>` | Yes | The iwa paths |

**Returns:** `String`

**Errors:** Throws `Error`.


---

### extractMetadataFromZip()

Extract metadata from an iWork ZIP archive.

Attempts to read `Metadata/Properties.plist` and
`Metadata/BuildVersionHistory.plist` from the ZIP. These files are XML plists
containing authorship and creation information. If the files cannot be read
or parsed, an empty `Metadata` is returned.

**Signature:**

```java
public static Metadata extractMetadataFromZip(byte[] content)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `content` | `byte[]` | Yes | The content to process |

**Returns:** `Metadata`


---

### dedupText()

Deduplicate a list of text strings while preserving order.
Adjacent duplicates and near-duplicates are removed.

**Signature:**

```java
public static List<String> dedupText(List<String> texts)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `texts` | `List<String>` | Yes | The texts |

**Returns:** `List<String>`


---

### ensureInitialized()

Ensure built-in extractors are registered.

This function is called automatically on first extraction operation.
It's safe to call multiple times - registration only happens once,
unless the registry was cleared, in which case extractors are re-registered.

**Signature:**

```java
public static void ensureInitialized() throws Error
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

```java
public static void registerDefaultExtractors() throws Error
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

```java
public static String extractPanicMessage(Any panicInfo)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `panicInfo` | `Any` | Yes | The panic payload from catch_unwind |

**Returns:** `String`


---

### getOcrBackendRegistry()

Get the global OCR backend registry.

**Signature:**

```java
public static RwLock getOcrBackendRegistry()
```

**Returns:** `RwLock`


---

### getDocumentExtractorRegistry()

Get the global document extractor registry.

**Signature:**

```java
public static RwLock getDocumentExtractorRegistry()
```

**Returns:** `RwLock`


---

### getPostProcessorRegistry()

Get the global post-processor registry.

**Signature:**

```java
public static RwLock getPostProcessorRegistry()
```

**Returns:** `RwLock`


---

### getValidatorRegistry()

Get the global validator registry.

**Signature:**

```java
public static RwLock getValidatorRegistry()
```

**Returns:** `RwLock`


---

### getRendererRegistry()

Get the global renderer registry.

**Signature:**

```java
public static RwLock getRendererRegistry()
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

```java
public static PluginHealthStatus validatePluginsAtStartup() throws Error
```

**Returns:** `PluginHealthStatus`

**Errors:** Throws `Error`.


---

### sanitizeFilename()

Sanitize a file path to return only the filename (no directory).

Prevents PII from appearing in traces.

**Signature:**

```java
public static String sanitizeFilename(String path)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `path` | `String` | Yes | Path to the file |

**Returns:** `String`


---

### getMetrics()

Get the global extraction metrics, initialising on first call.

Uses the global `opentelemetry.global.meter` to create instruments.

**Signature:**

```java
public static ExtractionMetrics getMetrics()
```

**Returns:** `ExtractionMetrics`


---

### recordErrorOnCurrentSpan()

Record an error on the current span using semantic conventions.

Sets `otel.status_code = "ERROR"`, `kreuzberg.error.type`, and `error.message`.

**Signature:**

```java
public static void recordErrorOnCurrentSpan(KreuzbergError error)
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

```java
public static void recordSuccessOnCurrentSpan()
```

**Returns:** `void`


---

### sanitizePath()

Sanitize a file path to return only the filename.

Prevents PII (personally identifiable information) from appearing in
traces by only recording filenames instead of full paths.

**Signature:**

```java
public static String sanitizePath(String path)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `path` | `String` | Yes | Path to the file |

**Returns:** `String`


---

### extractorSpan()

Create an extractor-level span with semantic convention fields.

Returns a `tracing.Span` with all `kreuzberg.extractor.*` and
`kreuzberg.document.*` fields pre-allocated (set to `Empty` for
lazy recording).

**Signature:**

```java
public static Span extractorSpan(String extractorName, String mimeType, long sizeBytes)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `extractorName` | `String` | Yes | The extractor name |
| `mimeType` | `String` | Yes | The mime type |
| `sizeBytes` | `long` | Yes | The size bytes |

**Returns:** `Span`


---

### pipelineStageSpan()

Create a pipeline stage span.

**Signature:**

```java
public static Span pipelineStageSpan(String stage)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `stage` | `String` | Yes | The stage |

**Returns:** `Span`


---

### pipelineProcessorSpan()

Create a pipeline processor span.

**Signature:**

```java
public static Span pipelineProcessorSpan(String stage, String processorName)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `stage` | `String` | Yes | The stage |
| `processorName` | `String` | Yes | The processor name |

**Returns:** `Span`


---

### ocrSpan()

Create an OCR operation span.

**Signature:**

```java
public static Span ocrSpan(String backend, String language)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `backend` | `String` | Yes | The backend |
| `language` | `String` | Yes | The language |

**Returns:** `Span`


---

### modelInferenceSpan()

Create a model inference span.

**Signature:**

```java
public static Span modelInferenceSpan(String modelName)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `modelName` | `String` | Yes | The model name |

**Returns:** `Span`


---

### fromUtf8()

Validates and converts bytes to string using SIMD when available.

This function attempts to use SIMD UTF-8 validation if the `simd-utf8` feature
is enabled and the platform supports it. Otherwise, it falls back to the standard
`std.str.from_utf8()` validation.

**Returns:**

`Ok(&str)` if the bytes are valid UTF-8, `Err(std.str.Utf8Error)` otherwise.

**Safety:**

This function is safe and does not use any unsafe code directly. The underlying
SIMD validation (when enabled) is contained within the simdutf8 crate and is safe.

**Signature:**

```java
public static String fromUtf8(byte[] bytes) throws Utf8Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `bytes` | `byte[]` | Yes | The byte slice to validate and convert |

**Returns:** `String`

**Errors:** Throws `Utf8Error`.


---

### stringFromUtf8()

Validates and converts owned bytes to String using SIMD when available.

This function converts bytes to an owned String, validating UTF-8 using SIMD
when available. The caller's bytes are consumed to create the String.

**Returns:**

`Ok(String)` if the bytes are valid UTF-8, `Err(std.string.FromUtf8Error)` otherwise.

# Performance

When enabled, SIMD validation significantly reduces the time spent on validation,
especially for large text documents.

**Signature:**

```java
public static String stringFromUtf8(byte[] bytes) throws FromUtf8Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `bytes` | `byte[]` | Yes | The byte vector to validate and convert |

**Returns:** `String`

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

```java
public static boolean isValidUtf8(byte[] bytes)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `bytes` | `byte[]` | Yes | The byte slice to validate |

**Returns:** `boolean`


---

### calculateQualityScore()

**Signature:**

```java
public static double calculateQualityScore(String text, AHashMap metadata)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `text` | `String` | Yes | The text |
| `metadata` | `Optional<AHashMap>` | No | The a hash map |

**Returns:** `double`


---

### cleanExtractedText()

**Signature:**

```java
public static String cleanExtractedText(String text)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `text` | `String` | Yes | The text |

**Returns:** `String`


---

### normalizeSpaces()

**Signature:**

```java
public static String normalizeSpaces(String text)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `text` | `String` | Yes | The text |

**Returns:** `String`


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

```java
public static String reduceTokens(String text, TokenReductionConfig config, String languageHint) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `text` | `String` | Yes | The input text to reduce |
| `config` | `TokenReductionConfig` | Yes | Configuration specifying reduction level and options |
| `languageHint` | `Optional<String>` | No | Optional ISO 639-3 language code (e.g., "eng", "spa") |

**Returns:** `String`

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

```java
public static List<String> batchReduceTokens(List<String> texts, TokenReductionConfig config, String languageHint) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `texts` | `List<String>` | Yes | Slice of text references to reduce |
| `config` | `TokenReductionConfig` | Yes | Configuration specifying reduction level and options |
| `languageHint` | `Optional<String>` | No | Optional ISO 639-3 language code (e.g., "eng", "spa") |

**Returns:** `List<String>`

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

```java
public static F64F64UsizeUsizeUsizeUsize getReductionStatistics(String original, String reduced)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `original` | `String` | Yes | The original text before reduction |
| `reduced` | `String` | Yes | The reduced text after applying token reduction |

**Returns:** `F64F64UsizeUsizeUsizeUsize`


---

### bold()

Create a bold annotation for the given byte range.

**Signature:**

```java
public static TextAnnotation bold(int start, int end)
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

```java
public static TextAnnotation italic(int start, int end)
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

```java
public static TextAnnotation underline(int start, int end)
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

```java
public static TextAnnotation link(int start, int end, String url, String title)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `start` | `int` | Yes | The start |
| `end` | `int` | Yes | The end |
| `url` | `String` | Yes | The URL to fetch |
| `title` | `Optional<String>` | No | The title |

**Returns:** `TextAnnotation`


---

### code()

Create a code (inline) annotation for the given byte range.

**Signature:**

```java
public static TextAnnotation code(int start, int end)
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

```java
public static TextAnnotation strikethrough(int start, int end)
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

```java
public static TextAnnotation subscript(int start, int end)
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

```java
public static TextAnnotation superscript(int start, int end)
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

```java
public static TextAnnotation fontSize(int start, int end, String value)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `start` | `int` | Yes | The start |
| `end` | `int` | Yes | The end |
| `value` | `String` | Yes | The value |

**Returns:** `TextAnnotation`


---

### color()

Create a color annotation for the given byte range.

**Signature:**

```java
public static TextAnnotation color(int start, int end, String value)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `start` | `int` | Yes | The start |
| `end` | `int` | Yes | The end |
| `value` | `String` | Yes | The value |

**Returns:** `TextAnnotation`


---

### highlight()

Create a highlight annotation for the given byte range.

**Signature:**

```java
public static TextAnnotation highlight(int start, int end)
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

```java
public static UriKind classifyUri(String url)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `url` | `String` | Yes | The URL to fetch |

**Returns:** `UriKind`


---

### safeDecode()

Decode raw bytes into UTF-8, using heuristics and fallback encodings when necessary.

The function prefers an explicit `encoding`, falls back to the cached guess, probes
an encoding detector, and finally tries a small curated list before returning a
mojibake-cleaned string.

**Signature:**

```java
public static String safeDecode(byte[] byteData, String encoding)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `byteData` | `byte[]` | Yes | The byte data |
| `encoding` | `Optional<String>` | No | The encoding |

**Returns:** `String`


---

### calculateTextConfidence()

Estimate how trustworthy a decoded string is on a 0.0–1.0 scale.

Scores close to 1.0 indicate mostly printable characters, whereas lower scores
point to mojibake, control characters, or suspicious character mixes.

**Signature:**

```java
public static double calculateTextConfidence(String text)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `text` | `String` | Yes | The text |

**Returns:** `double`


---

### fixMojibake()

Strip control characters and replacement glyphs that typically arise from mojibake.

**Signature:**

```java
public static Str fixMojibake(String text)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `text` | `String` | Yes | The text |

**Returns:** `Str`


---

### snakeToCamel()

Recursively convert snake_case keys in a JSON Value to camelCase.

This is used by language bindings (Node.js, Go, Java, C#, etc.) to provide
a consistent camelCase API for consumers even though the Rust core uses snake_case.

**Signature:**

```java
public static Value snakeToCamel(Value val)
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

```java
public static Value camelToSnake(Value val)
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

```java
public static StringBufferPool createStringBufferPool(long poolSize, long bufferCapacity)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `poolSize` | `long` | Yes | Maximum number of buffers to keep in the pool |
| `bufferCapacity` | `long` | Yes | Initial capacity for each buffer in bytes |

**Returns:** `StringBufferPool`


---

### createByteBufferPool()

Create a pre-configured byte buffer pool for batch processing.

**Returns:**

A pool configured for binary data handling with reasonable defaults.

**Signature:**

```java
public static ByteBufferPool createByteBufferPool(long poolSize, long bufferCapacity)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `poolSize` | `long` | Yes | Maximum number of buffers to keep in the pool |
| `bufferCapacity` | `long` | Yes | Initial capacity for each buffer in bytes |

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

```java
public static PoolSizeHint estimatePoolSize(long fileSize, String mimeType)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `fileSize` | `long` | Yes | Size of the file in bytes |
| `mimeType` | `String` | Yes | MIME type of the document (e.g., "application/pdf") |

**Returns:** `PoolSizeHint`


---

### xmlTagName()

Converts XML tag name bytes to a string, avoiding allocation when possible.

**Signature:**

```java
public static Str xmlTagName(byte[] name)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `name` | `byte[]` | Yes | The name |

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

```java
public static Str escapeHtmlEntities(String text)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `text` | `String` | Yes | The text |

**Returns:** `Str`


---

### normalizeWhitespace()

Normalizes whitespace by collapsing multiple whitespace characters into single spaces.
Returns Cow.Borrowed if no normalization needed.

**Signature:**

```java
public static Str normalizeWhitespace(String s)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `s` | `String` | Yes | The s |

**Returns:** `Str`


---

### detectColumns()

Detect column positions from word x-coordinates.

Groups words by approximate x-position (within `column_threshold` pixels)
and returns the median x-position for each detected column, sorted left to right.

**Signature:**

```java
public static List<Integer> detectColumns(List<HocrWord> words, int columnThreshold)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `words` | `List<HocrWord>` | Yes | The words |
| `columnThreshold` | `int` | Yes | The column threshold |

**Returns:** `List<Integer>`


---

### detectRows()

Detect row positions from word y-coordinates.

Groups words by their vertical center position and returns the median
y-position for each detected row. The `row_threshold_ratio` is multiplied
by the median word height to determine the grouping threshold.

**Signature:**

```java
public static List<Integer> detectRows(List<HocrWord> words, double rowThresholdRatio)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `words` | `List<HocrWord>` | Yes | The words |
| `rowThresholdRatio` | `double` | Yes | The row threshold ratio |

**Returns:** `List<Integer>`


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

```java
public static List<List<String>> reconstructTable(List<HocrWord> words, int columnThreshold, double rowThresholdRatio)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `words` | `List<HocrWord>` | Yes | The words |
| `columnThreshold` | `int` | Yes | The column threshold |
| `rowThresholdRatio` | `double` | Yes | The row threshold ratio |

**Returns:** `List<List<String>>`


---

### tableToMarkdown()

Convert a table grid to markdown format.

The first row is treated as the header row, with a separator line added after it.
Pipe characters in cell content are escaped.

**Signature:**

```java
public static String tableToMarkdown(List<List<String>> table)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `table` | `List<List<String>>` | Yes | The table |

**Returns:** `String`


---

### openapiJson()

Generate OpenAPI JSON schema.

Returns the complete OpenAPI 3.1 specification as a JSON string.

**Signature:**

```java
public static String openapiJson()
```

**Returns:** `String`


---

### validatePageBoundaries()

Validates the consistency and correctness of page boundaries.

# Validation Rules

1. Boundaries must be sorted by byte_start (monotonically increasing)
2. Boundaries must not overlap (byte_end[i] <= byte_start[i+1])
3. Each boundary must have byte_start < byte_end

**Returns:**

Returns `Ok(())` if all boundaries are valid.
Returns `KreuzbergError.Validation` if any boundary is invalid.

**Signature:**

```java
public static void validatePageBoundaries(List<PageBoundary> boundaries) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `boundaries` | `List<PageBoundary>` | Yes | Page boundary markers to validate |

**Returns:** `void`

**Errors:** Throws `Error`.


---

### calculatePageRange()

Calculate which pages a byte range spans.

**Returns:**

A tuple of (first_page, last_page) where page numbers are 1-indexed.
Returns (None, None) if boundaries are empty or chunk doesn't overlap any page.

**Errors:**

Returns `KreuzbergError.Validation` if boundaries are invalid.

**Signature:**

```java
public static OptionUsizeOptionUsize calculatePageRange(long byteStart, long byteEnd, List<PageBoundary> boundaries) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `byteStart` | `long` | Yes | Starting byte offset of the chunk |
| `byteEnd` | `long` | Yes | Ending byte offset of the chunk |
| `boundaries` | `List<PageBoundary>` | Yes | Page boundary markers from the document |

**Returns:** `OptionUsizeOptionUsize`

**Errors:** Throws `Error`.


---

### classifyChunk()

Classify a single chunk based on its content and optional heading context.

Rules are evaluated in priority order. The first matching rule determines
the returned `ChunkType`. When no rule matches, `ChunkType.Unknown`
is returned.

  (only available when using `ChunkerType.Markdown`).

**Signature:**

```java
public static ChunkType classifyChunk(String content, HeadingContext headingContext)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `content` | `String` | Yes | The text content of the chunk (may be trimmed or raw). |
| `headingContext` | `Optional<HeadingContext>` | No | Optional heading hierarchy this chunk falls under |

**Returns:** `ChunkType`


---

### chunkText()

Split text into chunks with optional page boundary tracking.

This is the primary API function for chunking text. It supports both plain text
and Markdown with configurable chunk size, overlap, and page boundary mapping.

**Returns:**

A ChunkingResult containing all chunks and their metadata.

**Signature:**

```java
public static ChunkingResult chunkText(String text, ChunkingConfig config, List<PageBoundary> pageBoundaries) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `text` | `String` | Yes | The text to split into chunks |
| `config` | `ChunkingConfig` | Yes | Chunking configuration (max size, overlap, type) |
| `pageBoundaries` | `Optional<List<PageBoundary>>` | No | Optional page boundary markers for mapping chunks to pages |

**Returns:** `ChunkingResult`

**Errors:** Throws `Error`.


---

### chunkTextWithHeadingSource()

Chunk text with an optional separate markdown source for heading context resolution.

When `heading_source` is provided, it is used instead of `text` for building the
heading map. This is needed when `text` is plain text (no markdown headings) but
the original document had headings that were stripped during rendering.

**Signature:**

```java
public static ChunkingResult chunkTextWithHeadingSource(String text, ChunkingConfig config, List<PageBoundary> pageBoundaries, String headingSource) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `text` | `String` | Yes | The text |
| `config` | `ChunkingConfig` | Yes | The configuration options |
| `pageBoundaries` | `Optional<List<PageBoundary>>` | No | The page boundaries |
| `headingSource` | `Optional<String>` | No | The heading source |

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

```java
public static ChunkingResult chunkTextWithType(String text, long maxCharacters, long overlap, boolean trim, ChunkerType chunkerType) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `text` | `String` | Yes | The text to split into chunks |
| `maxCharacters` | `long` | Yes | Maximum characters per chunk |
| `overlap` | `long` | Yes | Character overlap between consecutive chunks |
| `trim` | `boolean` | Yes | Whether to trim whitespace from boundaries |
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

```java
public static List<ChunkingResult> chunkTextsBatch(List<String> texts, ChunkingConfig config) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `texts` | `List<String>` | Yes | Slice of text strings to chunk |
| `config` | `ChunkingConfig` | Yes | Chunking configuration to apply to all texts |

**Returns:** `List<ChunkingResult>`

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

```java
public static BitVec precomputeUtf8Boundaries(String text)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `text` | `String` | Yes | The text to analyze |

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
Returns `KreuzbergError.Validation` if any boundary is at an invalid position.

# UTF-8 Boundary Safety

Rust strings use UTF-8 encoding where characters can be 1-4 bytes. For example:
- ASCII letters: 1 byte each
- Emoji (🌍): 4 bytes but 1 character
- CJK characters (中): 3 bytes but 1 character

This function checks that all byte_start and byte_end values are at character boundaries
using an adaptive strategy: direct calls for small boundary sets, or precomputed BitVec
for large sets.

**Signature:**

```java
public static void validateUtf8Boundaries(String text, List<PageBoundary> boundaries) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `text` | `String` | Yes | The text being chunked |
| `boundaries` | `List<PageBoundary>` | Yes | Page boundary markers to validate |

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

```java
public static void registerChunkingProcessor() throws Error
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

```java
public static DefaultClient createClient(LlmConfig config) throws Error
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

```java
public static String renderTemplate(String template, Value context) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `template` | `String` | Yes | The template |
| `context` | `Value` | Yes | The value |

**Returns:** `String`

**Errors:** Throws `Error`.


---

### extractStructured()

Extract structured data from document content using an LLM with JSON schema.

Sends the document content to the configured LLM with a JSON schema constraint,
returning structured data that conforms to the schema.

**Returns:**

A `serde_json.Value` conforming to the provided JSON schema.

**Errors:**

Returns an error if:
- The LLM client cannot be created (invalid provider/credentials).
- The LLM request fails (network, rate-limit, etc.).
- The LLM response cannot be parsed as valid JSON.

**Signature:**

```java
public static LlmUsage extractStructured(String content, StructuredExtractionConfig config) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `content` | `String` | Yes | The extracted document text to send to the LLM. |
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

- `KreuzbergError.Ocr` if the VLM returns no content or the API call fails
- `KreuzbergError.MissingDependency` if the liter-llm client cannot be created

**Signature:**

```java
public static LlmUsage vlmOcr(byte[] imageBytes, String imageMimeType, String language, LlmConfig config) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `imageBytes` | `byte[]` | Yes | Raw image data (JPEG, PNG, WebP, etc.) |
| `imageMimeType` | `String` | Yes | MIME type of the image (e.g., `"image/png"`) |
| `language` | `String` | Yes | ISO 639 language code or Tesseract language name |
| `config` | `LlmConfig` | Yes | LLM provider/model configuration |

**Returns:** `LlmUsage`

**Errors:** Throws `Error`.


---

### normalize()

L2-normalize a vector.

**Signature:**

```java
public static List<Float> normalize(List<Float> v)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `v` | `List<Float>` | Yes | The v |

**Returns:** `List<Float>`


---

### getPreset()

Get a preset by name.

**Signature:**

```java
public static Optional<EmbeddingPreset> getPreset(String name)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `name` | `String` | Yes | The name |

**Returns:** `Optional<EmbeddingPreset>`


---

### listPresets()

List all available preset names.

**Signature:**

```java
public static List<String> listPresets()
```

**Returns:** `List<String>`


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

```java
public static void warmModel(EmbeddingModelType modelType, String cacheDir) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `modelType` | `EmbeddingModelType` | Yes | The embedding model type |
| `cacheDir` | `Optional<String>` | No | The cache dir |

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

```java
public static void downloadModel(EmbeddingModelType modelType, String cacheDir) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `modelType` | `EmbeddingModelType` | Yes | The embedding model type |
| `cacheDir` | `Optional<String>` | No | The cache dir |

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

```java
public static void generateEmbeddingsForChunks(List<Chunk> chunks, EmbeddingConfig config) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `chunks` | `List<Chunk>` | Yes | Mutable reference to vector of chunks to generate embeddings for |
| `config` | `EmbeddingConfig` | Yes | Embedding configuration specifying model and parameters |

**Returns:** `void`

**Errors:** Throws `Error`.


---

### calculateSmartDpi()

Calculate smart DPI based on page dimensions, memory constraints, and target DPI

**Signature:**

```java
public static int calculateSmartDpi(double pageWidth, double pageHeight, int targetDpi, int maxDimension, double maxMemoryMb)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pageWidth` | `double` | Yes | The page width |
| `pageHeight` | `double` | Yes | The page height |
| `targetDpi` | `int` | Yes | The target dpi |
| `maxDimension` | `int` | Yes | The max dimension |
| `maxMemoryMb` | `double` | Yes | The max memory mb |

**Returns:** `int`


---

### calculateOptimalDpi()

Calculate optimal DPI with min/max constraints

**Signature:**

```java
public static int calculateOptimalDpi(double pageWidth, double pageHeight, int targetDpi, int maxDimension, int minDpi, int maxDpi)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pageWidth` | `double` | Yes | The page width |
| `pageHeight` | `double` | Yes | The page height |
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

```java
public static NormalizeResult normalizeImageDpi(byte[] rgbData, long width, long height, ExtractionConfig config, double currentDpi) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `rgbData` | `byte[]` | Yes | RGB image data as a flat `Vec<u8>` (height * width * 3 bytes, row-major) |
| `width` | `long` | Yes | Image width in pixels |
| `height` | `long` | Yes | Image height in pixels |
| `config` | `ExtractionConfig` | Yes | Extraction configuration containing DPI settings |
| `currentDpi` | `Optional<double>` | No | Optional current DPI of the image (defaults to 72 if None) |

**Returns:** `NormalizeResult`

**Errors:** Throws `Error`.


---

### resizeImage()

Resize an image using fast_image_resize with appropriate algorithm based on scale factor

**Signature:**

```java
public static DynamicImage resizeImage(DynamicImage image, int newWidth, int newHeight, double scaleFactor) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `image` | `DynamicImage` | Yes | The dynamic image |
| `newWidth` | `int` | Yes | The new width |
| `newHeight` | `int` | Yes | The new height |
| `scaleFactor` | `double` | Yes | The scale factor |

**Returns:** `DynamicImage`

**Errors:** Throws `Error`.


---

### detectLanguages()

Detect languages in text using whatlang.

Returns a list of detected language codes (ISO 639-3 format).
Returns `null` if no languages could be detected with sufficient confidence.

**Signature:**

```java
public static Optional<List<String>> detectLanguages(String text, LanguageDetectionConfig config) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `text` | `String` | Yes | The text to analyze for language detection |
| `config` | `LanguageDetectionConfig` | Yes | Optional configuration for language detection |

**Returns:** `Optional<List<String>>`

**Errors:** Throws `Error`.


---

### registerLanguageDetectionProcessor()

Register the language detection processor with the global registry.

This function should be called once at application startup to register
the language detection post-processor.

**Note:** This is called automatically on first use.
Explicit calling is optional.

**Signature:**

```java
public static void registerLanguageDetectionProcessor() throws Error
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

```java
public static Optional<AHashSet> getStopwords(String lang)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `lang` | `String` | Yes | The lang |

**Returns:** `Optional<AHashSet>`


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

```java
public static Optional<AHashSet> getStopwordsWithFallback(String language, String fallback)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `language` | `String` | Yes | Primary language code to try first |
| `fallback` | `String` | Yes | Fallback language code to use if primary not available |

**Returns:** `Optional<AHashSet>`


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

```java
public static List<Keyword> extractKeywords(String text, KeywordConfig config) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `text` | `String` | Yes | The text to extract keywords from |
| `config` | `KeywordConfig` | Yes | Keyword extraction configuration |

**Returns:** `List<Keyword>`

**Errors:** Throws `Error`.


---

### registerKeywordProcessor()

Register the keyword extraction processor with the global registry.

This function should be called once at application startup to register
the keyword extraction post-processor.

**Note:** This is called automatically on first use.
Explicit calling is optional.

**Signature:**

```java
public static void registerKeywordProcessor() throws Error
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

```java
public static Optional<OcrElement> textBlockToElement(TextBlock block, long pageNumber) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `block` | `TextBlock` | Yes | PaddleOCR TextBlock containing OCR results |
| `pageNumber` | `long` | Yes | 1-indexed page number |

**Returns:** `Optional<OcrElement>`

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

```java
public static OcrElement tsvRowToElement(TsvRow row)
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

```java
public static OcrElement iteratorWordToElement(WordData word, TessPolyBlockType blockType, ParaInfo paraInfo, long pageNumber)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `word` | `WordData` | Yes | WordData from the Tesseract result iterator |
| `blockType` | `Optional<TessPolyBlockType>` | No | Optional block type from Tesseract layout analysis |
| `paraInfo` | `Optional<ParaInfo>` | No | Optional paragraph metadata (justification, list item flag) |
| `pageNumber` | `long` | Yes | 1-indexed page number |

**Returns:** `OcrElement`


---

### elementToHocrWord()

Convert an OcrElement to an HocrWord for table reconstruction.

This enables reuse of the existing table detection algorithms from
html-to-markdown-rs with PaddleOCR results.

**Returns:**

An `HocrWord` suitable for table reconstruction algorithms.

**Signature:**

```java
public static HocrWord elementToHocrWord(OcrElement element)
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

```java
public static List<HocrWord> elementsToHocrWords(List<OcrElement> elements, double minConfidence)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `elements` | `List<OcrElement>` | Yes | Slice of OCR elements to convert |
| `minConfidence` | `double` | Yes | Minimum recognition confidence threshold (0.0-1.0) |

**Returns:** `List<HocrWord>`


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

```java
public static InternalDocument parseHocrToInternalDocument(String hocrHtml)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `hocrHtml` | `String` | Yes | The hocr html |

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

```java
public static String assembleOcrMarkdown(List<OcrElement> elements, DetectionResult detection, int imgWidth, int imgHeight, List<RecognizedTable> recognizedTables)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `elements` | `List<OcrElement>` | Yes | The elements |
| `detection` | `Optional<DetectionResult>` | No | The detection result |
| `imgWidth` | `int` | Yes | The img width |
| `imgHeight` | `int` | Yes | The img height |
| `recognizedTables` | `List<RecognizedTable>` | Yes | The recognized tables |

**Returns:** `String`


---

### recognizePageTables()

Run TATR table recognition for all Table regions in a page.

For each Table detection, crops the page image, runs TATR inference,
matches OCR elements to cells, and produces markdown tables.

**Signature:**

```java
public static List<RecognizedTable> recognizePageTables(RgbImage pageImage, DetectionResult detection, List<OcrElement> elements, TatrModel tatrModel)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pageImage` | `RgbImage` | Yes | The rgb image |
| `detection` | `DetectionResult` | Yes | The detection result |
| `elements` | `List<OcrElement>` | Yes | The elements |
| `tatrModel` | `TatrModel` | Yes | The tatr model |

**Returns:** `List<RecognizedTable>`


---

### extractWordsFromTsv()

Extract words from Tesseract TSV output and convert to HocrWord format.

This parses Tesseract's TSV format (level, page_num, block_num, ...) and
converts it to the HocrWord format used for table reconstruction.

**Signature:**

```java
public static List<HocrWord> extractWordsFromTsv(String tsvData, double minConfidence) throws OcrError
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `tsvData` | `String` | Yes | The tsv data |
| `minConfidence` | `double` | Yes | The min confidence |

**Returns:** `List<HocrWord>`

**Errors:** Throws `OcrError`.


---

### computeHash()

Compute a blake3 hash string from input data.

Returns a 32-character hex string (128 bits of blake3 output).

**Signature:**

```java
public static String computeHash(String data)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `data` | `String` | Yes | The data |

**Returns:** `String`


---

### validateLanguageCode()

**Signature:**

```java
public static void validateLanguageCode(String langCode) throws OcrError
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `langCode` | `String` | Yes | The lang code |

**Returns:** `void`

**Errors:** Throws `OcrError`.


---

### validateTesseractVersion()

**Signature:**

```java
public static void validateTesseractVersion(int version) throws OcrError
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

```java
public static void ensureOrtAvailable()
```

**Returns:** `void`


---

### isLanguageSupported()

Check if a language code is supported by PaddleOCR.

**Signature:**

```java
public static boolean isLanguageSupported(String lang)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `lang` | `String` | Yes | The lang |

**Returns:** `boolean`


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

```java
public static String languageToScriptFamily(String paddleLang)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `paddleLang` | `String` | Yes | The paddle lang |

**Returns:** `String`


---

### mapLanguageCode()

Map Kreuzberg language codes to PaddleOCR language codes.

**Signature:**

```java
public static Optional<String> mapLanguageCode(String kreuzbergCode)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `kreuzbergCode` | `String` | Yes | The kreuzberg code |

**Returns:** `Optional<String>`


---

### resolveCacheDir()

Resolve the cache directory for the auto-rotate model.

**Signature:**

```java
public static String resolveCacheDir()
```

**Returns:** `String`


---

### detectAndRotate()

Detect orientation and return a corrected image if rotation is needed.

Returns `Ok(Some(rotated_bytes))` if rotation was applied,
`Ok(None)` if no rotation needed (0° or low confidence).

**Signature:**

```java
public static Optional<byte[]> detectAndRotate(DocOrientationDetector detector, byte[] imageBytes) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `detector` | `DocOrientationDetector` | Yes | The doc orientation detector |
| `imageBytes` | `byte[]` | Yes | The image bytes |

**Returns:** `Optional<byte[]>`

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

```java
public static List<List<CellBBox>> buildCellGrid(TatrResult result, F324 tableBbox)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `result` | `TatrResult` | Yes | The tatr result |
| `tableBbox` | `Optional<F324>` | No | The [f32;4] |

**Returns:** `List<List<CellBBox>>`


---

### applyHeuristics()

Apply Docling-style postprocessing heuristics to raw detections.

This implements the key heuristics from `docling/utils/layout_postprocessor.py`:
1. Per-class confidence thresholds
2. Full-page picture removal (>90% page area)
3. Overlap resolution (IoU > 0.8 or containment > 0.8)
4. Cross-type overlap handling (KVR vs Table)

**Signature:**

```java
public static void applyHeuristics(List<LayoutDetection> detections, float pageWidth, float pageHeight)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `detections` | `List<LayoutDetection>` | Yes | The detections |
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

```java
public static void greedyNms(List<LayoutDetection> detections, float iouThreshold)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `detections` | `List<LayoutDetection>` | Yes | The detections |
| `iouThreshold` | `float` | Yes | The iou threshold |

**Returns:** `void`


---

### preprocessImagenet()

Preprocess an image for models using ImageNet normalization (e.g., RT-DETR).

Pipeline: resize to target_size x target_size (bilinear) -> rescale /255 -> ImageNet normalize -> NCHW f32.

Uses a single vectorized pass over contiguous pixel data for maximum throughput.

**Signature:**

```java
public static Array4 preprocessImagenet(RgbImage img, int targetSize)
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

```java
public static Array4F32F32U32U32 preprocessImagenetLetterbox(RgbImage img, int targetSize)
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

```java
public static Array4 preprocessRescale(RgbImage img, int targetSize)
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

```java
public static Array4F32F32 preprocessLetterbox(RgbImage img, int targetWidth, int targetHeight)
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
Pass the result of `crate.core.config.concurrency.resolve_thread_budget`
to respect the user's `ConcurrencyConfig`.

When `accel` is `null` or `Auto`, uses platform defaults:
- macOS: CoreML (Neural Engine / GPU)
- Linux: CUDA (GPU)
- Others: CPU only

ORT silently falls back to CPU if the requested EP is unavailable.

**Signature:**

```java
public static Session buildSession(String path, AccelerationConfig accel, long threadBudget) throws LayoutError
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `path` | `String` | Yes | Path to the file |
| `accel` | `Optional<AccelerationConfig>` | No | The acceleration config |
| `threadBudget` | `long` | Yes | The thread budget |

**Returns:** `Session`

**Errors:** Throws `LayoutError`.


---

### configFromExtraction()

Convert a `LayoutDetectionConfig` into a `LayoutEngineConfig`.

**Signature:**

```java
public static LayoutEngineConfig configFromExtraction(LayoutDetectionConfig layoutConfig)
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

```java
public static LayoutEngine createEngine(LayoutDetectionConfig layoutConfig) throws LayoutError
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

```java
public static LayoutEngine takeOrCreateEngine(LayoutDetectionConfig layoutConfig) throws LayoutError
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

```java
public static void returnEngine(LayoutEngine engine)
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

```java
public static Optional<TatrModel> takeOrCreateTatr()
```

**Returns:** `Optional<TatrModel>`


---

### returnTatr()

Return a TATR model to the global cache for reuse.

**Signature:**

```java
public static void returnTatr(TatrModel model)
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

```java
public static Optional<SlanetModel> takeOrCreateSlanet(String variant)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `variant` | `String` | Yes | The variant |

**Returns:** `Optional<SlanetModel>`


---

### returnSlanet()

Return a SLANeXT model to the global cache for reuse.

**Signature:**

```java
public static void returnSlanet(String variant, SlanetModel model)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `variant` | `String` | Yes | The variant |
| `model` | `SlanetModel` | Yes | The slanet model |

**Returns:** `void`


---

### takeOrCreateTableClassifier()

Take a cached table classifier, or create a new one.

**Signature:**

```java
public static Optional<TableClassifier> takeOrCreateTableClassifier()
```

**Returns:** `Optional<TableClassifier>`


---

### returnTableClassifier()

Return a table classifier to the global cache for reuse.

**Signature:**

```java
public static void returnTableClassifier(TableClassifier model)
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

```java
public static List<PdfAnnotation> extractAnnotationsFromDocument(PdfDocument document)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `document` | `PdfDocument` | Yes | A reference to the loaded pdfium `PdfDocument`. |

**Returns:** `List<PdfAnnotation>`


---

### extractBookmarks()

Extract bookmarks (outlines) from a PDF document loaded via lopdf.

Walks the `/Outlines` tree in the document catalog, collecting each bookmark's
title and destination. Returns an empty `Vec` if the document has no outlines.

**Signature:**

```java
public static List<Uri> extractBookmarks(Document document)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `document` | `Document` | Yes | The document |

**Returns:** `List<Uri>`


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

Returns `std.io.Error` if:
- Cannot create extraction directory
- Cannot write library file
- Cannot set file permissions (Unix only)
- Target is WASM (filesystem access not available)

# Platform-Specific Library Names

- Linux: `libpdfium.so`
- macOS: `libpdfium.dylib`
- Windows: `pdfium.dll`

**Signature:**

```java
public static String extractBundledPdfium() throws Error
```

**Returns:** `String`

**Errors:** Throws `Error`.


---

### extractEmbeddedFiles()

Extract embedded file descriptors from a PDF document loaded via lopdf.

Walks the `/Names` → `/EmbeddedFiles` name tree in the catalog.
Returns an empty `Vec` if the document has no embedded files.

**Signature:**

```java
public static List<EmbeddedFile> extractEmbeddedFiles(Document document)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `document` | `Document` | Yes | The document |

**Returns:** `List<EmbeddedFile>`


---

### extractAndProcessEmbeddedFiles()

Extract embedded files from PDF bytes and recursively process them.

Returns `(children, warnings)`. The children are `ArchiveEntry` values
suitable for attaching to `InternalDocument.children`.

**Signature:**

```java
public static VecArchiveEntryVecProcessingWarning extractAndProcessEmbeddedFiles(byte[] pdfBytes, ExtractionConfig config)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pdfBytes` | `byte[]` | Yes | The pdf bytes |
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

```java
public static void initializeFontCache() throws PdfError
```

**Returns:** `void`

**Errors:** Throws `PdfError`.


---

### getFontDescriptors()

Get cached font descriptors for Pdfium configuration.

Ensures the font cache is initialized, then returns font descriptors
derived from the cached fonts. This call is fast after the first invocation.

**Returns:**

A Vec of FontDescriptor objects suitable for `PdfiumConfig.set_font_provider()`.

# Performance

- First call: ~50-100ms (includes font discovery)
- Subsequent calls: < 1ms (reads from cache)

**Signature:**

```java
public static List<FontDescriptor> getFontDescriptors() throws PdfError
```

**Returns:** `List<FontDescriptor>`

**Errors:** Throws `PdfError`.


---

### cachedFontCount()

Get the number of cached fonts.

Useful for diagnostics and testing.

**Returns:**

Number of fonts in the cache, or 0 if not initialized.

**Signature:**

```java
public static long cachedFontCount()
```

**Returns:** `long`


---

### clearFontCache()

Clear the font cache (for testing purposes).

**Panics:**

Panics if the cache lock is poisoned, which should only happen in test scenarios
with deliberate panic injection.

**Signature:**

```java
public static void clearFontCache()
```

**Returns:** `void`


---

### extractImagesFromPdf()

**Signature:**

```java
public static List<PdfImage> extractImagesFromPdf(byte[] pdfBytes) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pdfBytes` | `byte[]` | Yes | The pdf bytes |

**Returns:** `List<PdfImage>`

**Errors:** Throws `Error`.


---

### extractImagesFromPdfWithPassword()

**Signature:**

```java
public static List<PdfImage> extractImagesFromPdfWithPassword(byte[] pdfBytes, String password) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pdfBytes` | `byte[]` | Yes | The pdf bytes |
| `password` | `String` | Yes | The password |

**Returns:** `List<PdfImage>`

**Errors:** Throws `Error`.


---

### reextractRawImagesViaPdfium()

Re-extract images that have unusable formats (`"raw"`, `"ccitt"`, `"jbig2"`) by
rendering them through pdfium's bitmap pipeline, which handles all PDF filter
chains internally.

Returns the number of images successfully re-extracted.

**Signature:**

```java
public static int reextractRawImagesViaPdfium(byte[] pdfBytes, List<PdfImage> images) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pdfBytes` | `byte[]` | Yes | The pdf bytes |
| `images` | `List<PdfImage>` | Yes | The images |

**Returns:** `int`

**Errors:** Throws `Error`.


---

### detectLayoutForDocument()

Run layout detection on all pages of a PDF document.

Under the hood, this uses batched layout detection to prevent holding too many
full-resolution page images in memory simultaneously before detection.

**Signature:**

```java
public static DynamicImage detectLayoutForDocument(byte[] pdfBytes, LayoutEngine engine) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pdfBytes` | `byte[]` | Yes | The pdf bytes |
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

```java
public static List<DetectionResult> detectLayoutForImages(List<DynamicImage> images, LayoutEngine engine) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `images` | `List<DynamicImage>` | Yes | The images |
| `engine` | `LayoutEngine` | Yes | The layout engine |

**Returns:** `List<DetectionResult>`

**Errors:** Throws `Error`.


---

### extractMetadata()

Extract PDF-specific metadata from raw bytes.

Returns only PDF-specific metadata (version, producer, encryption status, dimensions).

**Signature:**

```java
public static PdfMetadata extractMetadata(byte[] pdfBytes) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pdfBytes` | `byte[]` | Yes | The pdf bytes |

**Returns:** `PdfMetadata`

**Errors:** Throws `Error`.


---

### extractMetadataWithPassword()

Extract PDF-specific metadata from raw bytes with optional password.

Returns only PDF-specific metadata (version, producer, encryption status, dimensions).

**Signature:**

```java
public static PdfMetadata extractMetadataWithPassword(byte[] pdfBytes, String password) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pdfBytes` | `byte[]` | Yes | The pdf bytes |
| `password` | `Optional<String>` | No | The password |

**Returns:** `PdfMetadata`

**Errors:** Throws `Error`.


---

### extractMetadataWithPasswords()

**Signature:**

```java
public static PdfMetadata extractMetadataWithPasswords(byte[] pdfBytes, List<String> passwords) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pdfBytes` | `byte[]` | Yes | The pdf bytes |
| `passwords` | `List<String>` | Yes | The passwords |

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

```java
public static PdfExtractionMetadata extractMetadataFromDocument(PdfDocument document, List<PageBoundary> pageBoundaries, String content) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `document` | `PdfDocument` | Yes | The PDF document to extract metadata from |
| `pageBoundaries` | `Optional<List<PageBoundary>>` | No | Optional vector of PageBoundary entries for building PageStructure. |
| `content` | `Optional<String>` | No | Optional extracted text content, used for blank page detection. |

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

```java
public static CommonPdfMetadata extractCommonMetadataFromDocument(PdfDocument document) throws Error
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

```java
public static DynamicImage renderPageToImage(byte[] pdfBytes, long pageIndex, PageRenderOptions options) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pdfBytes` | `byte[]` | Yes | The pdf bytes |
| `pageIndex` | `long` | Yes | The page index |
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

```java
public static byte[] renderPdfPageToPng(byte[] pdfBytes, long pageIndex, int dpi, String password) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pdfBytes` | `byte[]` | Yes | The pdf bytes |
| `pageIndex` | `long` | Yes | The page index |
| `dpi` | `Optional<int>` | No | The dpi |
| `password` | `Optional<String>` | No | The password |

**Returns:** `byte[]`

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

```java
public static List<HocrWord> extractWordsFromPage(PdfPage page, double minConfidence) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `page` | `PdfPage` | Yes | PDF page to extract words from |
| `minConfidence` | `double` | Yes | Minimum confidence threshold (0.0-100.0). PDF text has high confidence (95.0). |

**Returns:** `List<HocrWord>`

**Errors:** Throws `Error`.


---

### segmentToHocrWord()

Convert a PDF `SegmentData` to an `HocrWord` for table reconstruction.

`SegmentData` uses PDF coordinates (y=0 at bottom, increases upward).
`HocrWord` uses image coordinates (y=0 at top, increases downward).

**Signature:**

```java
public static HocrWord segmentToHocrWord(SegmentData seg, float pageHeight)
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

```java
public static List<HocrWord> splitSegmentToWords(SegmentData seg, float pageHeight)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `seg` | `SegmentData` | Yes | The segment data |
| `pageHeight` | `float` | Yes | The page height |

**Returns:** `List<HocrWord>`


---

### segmentsToWords()

Convert a page's segments to word-level `HocrWord`s for table extraction.

Splits multi-word segments into individual words with proportional bounding
boxes, ensuring each word can be independently matched to table cells.

**Signature:**

```java
public static List<HocrWord> segmentsToWords(List<SegmentData> segments, float pageHeight)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `segments` | `List<SegmentData>` | Yes | The segments |
| `pageHeight` | `float` | Yes | The page height |

**Returns:** `List<HocrWord>`


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

```java
public static Optional<List<List<String>>> postProcessTable(List<List<String>> table, boolean layoutGuided, boolean allowSingleColumn)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `table` | `List<List<String>>` | Yes | The table |
| `layoutGuided` | `boolean` | Yes | The layout guided |
| `allowSingleColumn` | `boolean` | Yes | The allow single column |

**Returns:** `Optional<List<List<String>>>`


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

```java
public static boolean isWellFormedTable(List<List<String>> grid)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `grid` | `List<List<String>>` | Yes | The grid |

**Returns:** `boolean`


---

### extractTextFromPdf()

**Signature:**

```java
public static String extractTextFromPdf(byte[] pdfBytes) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pdfBytes` | `byte[]` | Yes | The pdf bytes |

**Returns:** `String`

**Errors:** Throws `Error`.


---

### extractTextFromPdfWithPassword()

**Signature:**

```java
public static String extractTextFromPdfWithPassword(byte[] pdfBytes, String password) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pdfBytes` | `byte[]` | Yes | The pdf bytes |
| `password` | `String` | Yes | The password |

**Returns:** `String`

**Errors:** Throws `Error`.


---

### extractTextFromPdfWithPasswords()

**Signature:**

```java
public static String extractTextFromPdfWithPasswords(byte[] pdfBytes, List<String> passwords) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pdfBytes` | `byte[]` | Yes | The pdf bytes |
| `passwords` | `List<String>` | Yes | The passwords |

**Returns:** `String`

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

```java
public static PdfUnifiedExtractionResult extractTextAndMetadataFromPdfDocument(PdfDocument document, ExtractionConfig extractionConfig) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `document` | `PdfDocument` | Yes | The PDF document to extract from |
| `extractionConfig` | `Optional<ExtractionConfig>` | No | Optional extraction configuration for hierarchy and page tracking |

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

```java
public static PdfTextExtractionResult extractTextFromPdfDocument(PdfDocument document, PageConfig pageConfig, ExtractionConfig extractionConfig) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `document` | `PdfDocument` | Yes | The PDF document to extract text from |
| `pageConfig` | `Optional<PageConfig>` | No | Optional page configuration for boundary tracking and page markers |
| `extractionConfig` | `Optional<ExtractionConfig>` | No | Optional extraction configuration for hierarchy detection |

**Returns:** `PdfTextExtractionResult`

**Errors:** Throws `Error`.


---

### serializeToToon()

Serialize an `ExtractionResult` to TOON (Token-Oriented Object Notation).

TOON is a token-efficient alternative to JSON for LLM prompts.
Losslessly convertible to/from JSON but uses fewer tokens.

**Signature:**

```java
public static String serializeToToon(ExtractionResult result) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `result` | `ExtractionResult` | Yes | The extraction result |

**Returns:** `String`

**Errors:** Throws `Error`.


---

### serializeToJson()

Serialize an `ExtractionResult` to pretty-printed JSON.

**Signature:**

```java
public static String serializeToJson(ExtractionResult result) throws Error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `result` | `ExtractionResult` | Yes | The extraction result |

**Returns:** `String`

**Errors:** Throws `Error`.


---

## Types

### AccelerationConfig

Hardware acceleration configuration for ONNX Runtime models.

Controls which execution provider (CPU, CoreML, CUDA, TensorRT) is used
for inference in layout detection and embedding generation.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `provider` | `ExecutionProviderType` | `ExecutionProviderType.Auto` | Execution provider to use for ONNX inference. |
| `deviceId` | `int` | `null` | GPU device ID (for CUDA/TensorRT). Ignored for CPU/CoreML/Auto. |


---

### AnchorProperties

Properties for anchored drawings.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `behindDoc` | `boolean` | `null` | Behind doc |
| `layoutInCell` | `boolean` | `null` | Layout in cell |
| `relativeHeight` | `Optional<long>` | `null` | Relative height |
| `positionH` | `Optional<Position>` | `null` | Position h (position) |
| `positionV` | `Optional<Position>` | `null` | Position v (position) |
| `wrapType` | `WrapType` | `WrapType.None` | Wrap type (wrap type) |


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
| `path` | `String` | — | Archive-relative file path (e.g. "folder/document.pdf"). |
| `mimeType` | `String` | — | Detected MIME type of the file. |
| `result` | `ExtractionResult` | — | Full extraction result for this file. |


---

### ArchiveMetadata

Archive (ZIP/TAR/7Z) metadata.

Extracted from compressed archive files containing file lists and size information.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `format` | `Str` | — | Archive format ("ZIP", "TAR", "7Z", etc.) |
| `fileCount` | `long` | — | Total number of files in the archive |
| `fileList` | `List<String>` | — | List of file paths within the archive |
| `totalSize` | `long` | — | Total uncompressed size in bytes |
| `compressedSize` | `Optional<long>` | `null` | Compressed size in bytes (if available) |


---

### Attributes

Element attributes in Djot.

Represents the attributes attached to elements using {.class #id key="value"} syntax.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `id` | `Optional<String>` | `null` | Element ID (#identifier) |
| `classes` | `List<String>` | `Collections.emptyList()` | CSS classes (.class1 .class2) |
| `keyValues` | `List<StringString>` | `Collections.emptyList()` | Key-value pairs (key="value") |


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

```java
public float width()
```

##### height()

**Signature:**

```java
public float height()
```

##### area()

**Signature:**

```java
public float area()
```

##### center()

**Signature:**

```java
public F32F32 center()
```

##### intersectionArea()

Area of intersection with another bounding box.

**Signature:**

```java
public float intersectionArea(BBox other)
```

##### iou()

Intersection over Union with another bounding box.

**Signature:**

```java
public float iou(BBox other)
```

##### containmentOf()

Fraction of `other` that is contained within `self`.
Returns 0.0..=1.0 where 1.0 means `other` is fully inside `self`.

**Signature:**

```java
public float containmentOf(BBox other)
```

##### pageCoverage()

Fraction of page area this bbox covers.

**Signature:**

```java
public float pageCoverage(float pageWidth, float pageHeight)
```

##### fmt()

**Signature:**

```java
public Unknown fmt(Formatter f)
```


---

### BatchItemResult

Batch item result for processing multiple files

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `filePath` | `String` | — | File path |
| `success` | `boolean` | — | Success |
| `result` | `Optional<OcrExtractionResult>` | `null` | Result (ocr extraction result) |
| `error` | `Optional<String>` | `null` | Error |


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

```java
public static BatchProcessor withConfig(BatchProcessorConfig config)
```

##### withPoolHint()

Create a batch processor with pool sizes optimized for a specific document.

This method uses a `PoolSizeHint` (derived from file size and MIME type)
to create a batch processor with appropriately sized pools. This reduces
memory waste by tailoring pool allocation to actual document complexity.

**Returns:**

A new `BatchProcessor` configured with the hint-based pool sizes

**Signature:**

```java
public static BatchProcessor withPoolHint(PoolSizeHint hint)
```

##### stringPool()

Get a reference to the string buffer pool.

Creates the pool lazily on first access.
Useful for custom pooling implementations that need direct pool access.

**Signature:**

```java
public StringBufferPool stringPool()
```

##### bytePool()

Get a reference to the byte buffer pool.

Creates the pool lazily on first access.
Useful for custom pooling implementations that need direct pool access.

**Signature:**

```java
public ByteBufferPool bytePool()
```

##### config()

Get the current configuration.

**Signature:**

```java
public BatchProcessorConfig config()
```

##### stringPoolSize()

Get the number of pooled string buffers currently available.

**Signature:**

```java
public long stringPoolSize()
```

##### bytePoolSize()

Get the number of pooled byte buffers currently available.

**Signature:**

```java
public long bytePoolSize()
```

##### clearPools()

Clear all pooled objects, forcing new allocations on next acquire.

Useful for memory-constrained environments or to reclaim memory
after processing large batches.

**Signature:**

```java
public void clearPools() throws Error
```

##### defaultOptions()

**Signature:**

```java
public static BatchProcessor defaultOptions()
```


---

### BatchProcessorConfig

Configuration for batch processing with pooling optimizations.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `stringPoolSize` | `long` | `10` | Maximum number of string buffers to maintain in the pool |
| `stringBufferCapacity` | `long` | `8192` | Initial capacity for pooled string buffers in bytes |
| `bytePoolSize` | `long` | `10` | Maximum number of byte buffers to maintain in the pool |
| `byteBufferCapacity` | `long` | `65536` | Initial capacity for pooled byte buffers in bytes |
| `maxConcurrent` | `Optional<long>` | `null` | Maximum concurrent extractions (for concurrency control) |

#### Methods

##### defaultOptions()

**Signature:**

```java
public static BatchProcessorConfig defaultOptions()
```


---

### BibtexExtractor

BibTeX bibliography extractor.

Parses BibTeX files and extracts structured bibliography data including
entries, authors, publication years, and entry type distribution.

#### Methods

##### defaultOptions()

**Signature:**

```java
public static BibtexExtractor defaultOptions()
```

##### name()

**Signature:**

```java
public String name()
```

##### version()

**Signature:**

```java
public String version()
```

##### initialize()

**Signature:**

```java
public void initialize() throws Error
```

##### shutdown()

**Signature:**

```java
public void shutdown() throws Error
```

##### description()

**Signature:**

```java
public String description()
```

##### author()

**Signature:**

```java
public String author()
```

##### extractBytes()

**Signature:**

```java
public InternalDocument extractBytes(byte[] content, String mimeType, ExtractionConfig config) throws Error
```

##### supportedMimeTypes()

**Signature:**

```java
public List<String> supportedMimeTypes()
```

##### priority()

**Signature:**

```java
public int priority()
```


---

### BibtexMetadata

BibTeX bibliography metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `entryCount` | `long` | `null` | Number of entry |
| `citationKeys` | `List<String>` | `Collections.emptyList()` | Citation keys |
| `authors` | `List<String>` | `Collections.emptyList()` | Authors |
| `yearRange` | `Optional<YearRange>` | `null` | Year range (year range) |
| `entryTypes` | `Optional<Map<String, Long>>` | `Collections.emptyMap()` | Entry types |


---

### BorderStyle

A single border specification.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `style` | `String` | — | Style |
| `size` | `Optional<int>` | `null` | Size in bytes |
| `color` | `Optional<String>` | `null` | Color |
| `space` | `Optional<int>` | `null` | Space |


---

### BoundingBox

Bounding box coordinates for element positioning.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `x0` | `double` | — | Left x-coordinate |
| `y0` | `double` | — | Bottom y-coordinate |
| `x1` | `double` | — | Right x-coordinate |
| `y1` | `double` | — | Top y-coordinate |


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
| `totalFiles` | `long` | — | Total number of cached files |
| `totalSizeMb` | `double` | — | Total cache size in megabytes |
| `availableSpaceMb` | `double` | — | Available disk space in megabytes |
| `oldestFileAgeDays` | `double` | — | Age of the oldest cached file in days |
| `newestFileAgeDays` | `double` | — | Age of the newest cached file in days |


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
| `top` | `Optional<BorderStyle>` | `null` | Top (border style) |
| `bottom` | `Optional<BorderStyle>` | `null` | Bottom (border style) |
| `left` | `Optional<BorderStyle>` | `null` | Left (border style) |
| `right` | `Optional<BorderStyle>` | `null` | Right (border style) |


---

### CellMargins

Cell margins (used for both table-level defaults and per-cell overrides).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `top` | `Optional<int>` | `null` | Top |
| `bottom` | `Optional<int>` | `null` | Bottom |
| `left` | `Optional<int>` | `null` | Left |
| `right` | `Optional<int>` | `null` | Right |


---

### CellProperties

Cell-level properties from `<w:tcPr>`.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `width` | `Optional<TableWidth>` | `null` | Width (table width) |
| `gridSpan` | `Optional<int>` | `null` | Grid span |
| `vMerge` | `Optional<VerticalMerge>` | `VerticalMerge.Restart` | V merge (vertical merge) |
| `borders` | `Optional<CellBorders>` | `null` | Borders (cell borders) |
| `shading` | `Optional<CellShading>` | `null` | Shading (cell shading) |
| `margins` | `Optional<CellMargins>` | `null` | Margins (cell margins) |
| `verticalAlign` | `Optional<String>` | `null` | Vertical align |
| `textDirection` | `Optional<String>` | `null` | Text direction |
| `noWrap` | `boolean` | `null` | No wrap |


---

### CellShading

Cell shading/background.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `fill` | `Optional<String>` | `null` | Fill |
| `color` | `Optional<String>` | `null` | Color |
| `val` | `Optional<String>` | `null` | Val |


---

### CfbReader

#### Methods

##### fromBytes()

Open a CFB compound file from raw bytes.

**Signature:**

```java
public static CfbReader fromBytes(byte[] bytes) throws Error
```


---

### Chunk

A text chunk with optional embedding and metadata.

Chunks are created when chunking is enabled in `ExtractionConfig`. Each chunk
contains the text content, optional embedding vector (if embedding generation
is configured), and metadata about its position in the document.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `content` | `String` | — | The text content of this chunk. |
| `chunkType` | `ChunkType` | — | Semantic structural classification of this chunk. Assigned by the heuristic classifier based on content patterns and heading context. Defaults to `ChunkType.Unknown` when no rule matches. |
| `embedding` | `Optional<List<Float>>` | `null` | Optional embedding vector for this chunk. Only populated when `EmbeddingConfig` is provided in chunking configuration. The dimensionality depends on the chosen embedding model. |
| `metadata` | `ChunkMetadata` | — | Metadata about this chunk's position and properties. |


---

### ChunkMetadata

Metadata about a chunk's position in the original document.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `byteStart` | `long` | — | Byte offset where this chunk starts in the original text (UTF-8 valid boundary). |
| `byteEnd` | `long` | — | Byte offset where this chunk ends in the original text (UTF-8 valid boundary). |
| `tokenCount` | `Optional<long>` | `null` | Number of tokens in this chunk (if available). This is calculated by the embedding model's tokenizer if embeddings are enabled. |
| `chunkIndex` | `long` | — | Zero-based index of this chunk in the document. |
| `totalChunks` | `long` | — | Total number of chunks in the document. |
| `firstPage` | `Optional<long>` | `null` | First page number this chunk spans (1-indexed). Only populated when page tracking is enabled in extraction configuration. |
| `lastPage` | `Optional<long>` | `null` | Last page number this chunk spans (1-indexed, equal to first_page for single-page chunks). Only populated when page tracking is enabled in extraction configuration. |
| `headingContext` | `Optional<HeadingContext>` | `null` | Heading context when using Markdown chunker. Contains the heading hierarchy this chunk falls under. Only populated when `ChunkerType.Markdown` is used. |


---

### ChunkingConfig

Chunking configuration.

Configures text chunking for document content, including chunk size,
overlap, trimming behavior, and optional embeddings.

Use `..the default constructor` when constructing to allow for future field additions:

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `maxCharacters` | `long` | `1000` | Maximum size per chunk (in units determined by `sizing`). When `sizing` is `Characters` (default), this is the max character count. When using token-based sizing, this is the max token count. Default: 1000 |
| `overlap` | `long` | `200` | Overlap between chunks (in units determined by `sizing`). Default: 200 |
| `trim` | `boolean` | `true` | Whether to trim whitespace from chunk boundaries. Default: true |
| `chunkerType` | `ChunkerType` | `ChunkerType.Text` | Type of chunker to use (Text or Markdown). Default: Text |
| `embedding` | `Optional<EmbeddingConfig>` | `null` | Optional embedding configuration for chunk embeddings. |
| `preset` | `Optional<String>` | `null` | Use a preset configuration (overrides individual settings if provided). |
| `sizing` | `ChunkSizing` | `ChunkSizing.Characters` | How to measure chunk size. Default: `Characters` (Unicode character count). Enable `chunking-tiktoken` or `chunking-tokenizers` features for token-based sizing. |
| `prependHeadingContext` | `boolean` | `false` | When `True` and `chunker_type` is `Markdown`, prepend the heading hierarchy path (e.g. `"# Title > ## Section\n\n"`) to each chunk's content string. This is useful for RAG pipelines where each chunk needs self-contained context about its position in the document structure. Default: `False` |

#### Methods

##### withChunkerType()

Set the chunker type.

**Signature:**

```java
public ChunkingConfig withChunkerType(ChunkerType chunkerType)
```

##### withSizing()

Set the sizing strategy.

**Signature:**

```java
public ChunkingConfig withSizing(ChunkSizing sizing)
```

##### withPrependHeadingContext()

Enable or disable prepending heading context to chunk content.

**Signature:**

```java
public ChunkingConfig withPrependHeadingContext(boolean prepend)
```

##### defaultOptions()

**Signature:**

```java
public static ChunkingConfig defaultOptions()
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

```java
public String name()
```

##### version()

**Signature:**

```java
public String version()
```

##### initialize()

**Signature:**

```java
public void initialize() throws Error
```

##### shutdown()

**Signature:**

```java
public void shutdown() throws Error
```

##### process()

**Signature:**

```java
public void process(ExtractionResult result, ExtractionConfig config) throws Error
```

##### processingStage()

**Signature:**

```java
public ProcessingStage processingStage()
```

##### shouldProcess()

**Signature:**

```java
public boolean shouldProcess(ExtractionResult result, ExtractionConfig config)
```

##### estimatedDurationMs()

**Signature:**

```java
public long estimatedDurationMs(ExtractionResult result)
```


---

### ChunkingResult

Result of a text chunking operation.

Contains the generated chunks and metadata about the chunking.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `chunks` | `List<Chunk>` | — | List of text chunks |
| `chunkCount` | `long` | — | Total number of chunks generated |


---

### CitationExtractor

Citation format extractor for RIS, PubMed/MEDLINE, and EndNote XML formats.

Parses citation files and extracts structured bibliography data including
entries, authors, publication years, and format-specific metadata.

#### Methods

##### defaultOptions()

**Signature:**

```java
public static CitationExtractor defaultOptions()
```

##### name()

**Signature:**

```java
public String name()
```

##### version()

**Signature:**

```java
public String version()
```

##### initialize()

**Signature:**

```java
public void initialize() throws Error
```

##### shutdown()

**Signature:**

```java
public void shutdown() throws Error
```

##### description()

**Signature:**

```java
public String description()
```

##### author()

**Signature:**

```java
public String author()
```

##### extractBytes()

**Signature:**

```java
public InternalDocument extractBytes(byte[] content, String mimeType, ExtractionConfig config) throws Error
```

##### supportedMimeTypes()

**Signature:**

```java
public List<String> supportedMimeTypes()
```

##### priority()

**Signature:**

```java
public int priority()
```


---

### CitationMetadata

Citation file metadata (RIS, PubMed, EndNote).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `citationCount` | `long` | `null` | Number of citation |
| `format` | `Optional<String>` | `null` | Format |
| `authors` | `List<String>` | `Collections.emptyList()` | Authors |
| `yearRange` | `Optional<YearRange>` | `null` | Year range (year range) |
| `dois` | `List<String>` | `Collections.emptyList()` | Dois |
| `keywords` | `List<String>` | `Collections.emptyList()` | Keywords |


---

### CodeExtractor

Source code extractor using tree-sitter language pack.

Detects the programming language from the file extension or shebang line,
then uses tree-sitter to parse and extract structural information.

#### Methods

##### defaultOptions()

**Signature:**

```java
public static CodeExtractor defaultOptions()
```

##### name()

**Signature:**

```java
public String name()
```

##### version()

**Signature:**

```java
public String version()
```

##### initialize()

**Signature:**

```java
public void initialize() throws Error
```

##### shutdown()

**Signature:**

```java
public void shutdown() throws Error
```

##### description()

**Signature:**

```java
public String description()
```

##### author()

**Signature:**

```java
public String author()
```

##### extractBytes()

**Signature:**

```java
public InternalDocument extractBytes(byte[] content, String mimeType, ExtractionConfig config) throws Error
```

##### extractFile()

**Signature:**

```java
public InternalDocument extractFile(String path, String mimeType, ExtractionConfig config) throws Error
```

##### supportedMimeTypes()

**Signature:**

```java
public List<String> supportedMimeTypes()
```

##### priority()

**Signature:**

```java
public int priority()
```

##### asSyncExtractor()

**Signature:**

```java
public Optional<SyncExtractor> asSyncExtractor()
```

##### extractSync()

**Signature:**

```java
public InternalDocument extractSync(byte[] content, String mimeType, ExtractionConfig config) throws Error
```


---

### ColorScheme

Color scheme containing all 12 standard Office theme colors.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `name` | `String` | `null` | Color scheme name. |
| `dk1` | `Optional<ThemeColor>` | `ThemeColor.Rgb` | Dark 1 (dark background) color. |
| `lt1` | `Optional<ThemeColor>` | `ThemeColor.Rgb` | Light 1 (light background) color. |
| `dk2` | `Optional<ThemeColor>` | `ThemeColor.Rgb` | Dark 2 color. |
| `lt2` | `Optional<ThemeColor>` | `ThemeColor.Rgb` | Light 2 color. |
| `accent1` | `Optional<ThemeColor>` | `ThemeColor.Rgb` | Accent color 1. |
| `accent2` | `Optional<ThemeColor>` | `ThemeColor.Rgb` | Accent color 2. |
| `accent3` | `Optional<ThemeColor>` | `ThemeColor.Rgb` | Accent color 3. |
| `accent4` | `Optional<ThemeColor>` | `ThemeColor.Rgb` | Accent color 4. |
| `accent5` | `Optional<ThemeColor>` | `ThemeColor.Rgb` | Accent color 5. |
| `accent6` | `Optional<ThemeColor>` | `ThemeColor.Rgb` | Accent color 6. |
| `hlink` | `Optional<ThemeColor>` | `ThemeColor.Rgb` | Hyperlink color. |
| `folHlink` | `Optional<ThemeColor>` | `ThemeColor.Rgb` | Followed hyperlink color. |


---

### ColumnLayout

Column layout configuration.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `count` | `Optional<int>` | `null` | Number of columns. |
| `spaceTwips` | `Optional<int>` | `null` | Space between columns in twips. |
| `equalWidth` | `Optional<boolean>` | `null` | Whether columns have equal width. |


---

### CommonPdfMetadata

Common metadata fields extracted from a PDF.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `title` | `Optional<String>` | `null` | Title |
| `subject` | `Optional<String>` | `null` | Subject |
| `authors` | `Optional<List<String>>` | `null` | Authors |
| `keywords` | `Optional<List<String>>` | `null` | Keywords |
| `createdAt` | `Optional<String>` | `null` | Created at |
| `modifiedAt` | `Optional<String>` | `null` | Modified at |
| `createdBy` | `Optional<String>` | `null` | Created by |


---

### ConcurrencyConfig

Controls thread usage for constrained environments.

Set `max_threads` to cap all internal thread pools (Rayon, ONNX Runtime
intra-op) and batch concurrency to a single limit.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `maxThreads` | `Optional<long>` | `null` | Maximum number of threads for all internal thread pools. Caps Rayon global pool size, ONNX Runtime intra-op threads, and (when `max_concurrent_extractions` is unset) the batch concurrency semaphore. When `None`, system defaults are used. |


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
| `includeHeaders` | `boolean` | `false` | Include running headers in extraction output. - PDF: Disables top-margin furniture stripping and prevents the layout model from treating `PageHeader`-classified regions as furniture. - DOCX: Includes document headers in text output. - RTF/ODT: Headers already included; this is a no-op when true. - HTML/EPUB: Keeps `<header>` element content. Default: `False` (headers are stripped or excluded). |
| `includeFooters` | `boolean` | `false` | Include running footers in extraction output. - PDF: Disables bottom-margin furniture stripping and prevents the layout model from treating `PageFooter`-classified regions as furniture. - DOCX: Includes document footers in text output. - RTF/ODT: Footers already included; this is a no-op when true. - HTML/EPUB: Keeps `<footer>` element content. Default: `False` (footers are stripped or excluded). |
| `stripRepeatingText` | `boolean` | `true` | Enable the heuristic cross-page repeating text detector. When `True` (default), text that repeats verbatim across a supermajority of pages is classified as furniture and stripped.  Disable this if brand names or repeated headings are being incorrectly removed by the heuristic. Note: when a layout-detection model is active, the model may independently classify page-header / page-footer regions as furniture on a per-page basis. To preserve those regions, set `include_headers = true` and/or `include_footers = true` in addition to disabling this flag. Primarily affects PDF extraction. Default: `True`. |
| `includeWatermarks` | `boolean` | `false` | Include watermark text in extraction output. - PDF: Keeps watermark artifacts and arXiv identifiers. - Other formats: No effect currently. Default: `False` (watermarks are stripped). |

#### Methods

##### defaultOptions()

**Signature:**

```java
public static ContentFilterConfig defaultOptions()
```


---

### ContributorRole

JATS contributor with role.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `name` | `String` | — | The name |
| `role` | `Optional<String>` | `null` | Role |


---

### CoreProperties

Dublin Core metadata from docProps/core.xml

Contains standard metadata fields defined by the Dublin Core standard
and Office-specific extensions.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `title` | `Optional<String>` | `null` | Document title |
| `subject` | `Optional<String>` | `null` | Document subject/topic |
| `creator` | `Optional<String>` | `null` | Document creator/author |
| `keywords` | `Optional<String>` | `null` | Keywords or tags |
| `description` | `Optional<String>` | `null` | Document description/abstract |
| `lastModifiedBy` | `Optional<String>` | `null` | User who last modified the document |
| `revision` | `Optional<String>` | `null` | Revision number |
| `created` | `Optional<String>` | `null` | Creation timestamp (ISO 8601) |
| `modified` | `Optional<String>` | `null` | Last modification timestamp (ISO 8601) |
| `category` | `Optional<String>` | `null` | Document category |
| `contentStatus` | `Optional<String>` | `null` | Content status (Draft, Final, etc.) |
| `language` | `Optional<String>` | `null` | Document language |
| `identifier` | `Optional<String>` | `null` | Unique identifier |
| `version` | `Optional<String>` | `null` | Document version |
| `lastPrinted` | `Optional<String>` | `null` | Last print timestamp (ISO 8601) |


---

### CsvExtractor

CSV/TSV extractor with proper field parsing.

Replaces raw text passthrough with structured CSV parsing,
producing space-separated text output and populated `tables` field.

#### Methods

##### defaultOptions()

**Signature:**

```java
public static CsvExtractor defaultOptions()
```

##### name()

**Signature:**

```java
public String name()
```

##### version()

**Signature:**

```java
public String version()
```

##### initialize()

**Signature:**

```java
public void initialize() throws Error
```

##### shutdown()

**Signature:**

```java
public void shutdown() throws Error
```

##### description()

**Signature:**

```java
public String description()
```

##### author()

**Signature:**

```java
public String author()
```

##### extractBytes()

**Signature:**

```java
public InternalDocument extractBytes(byte[] content, String mimeType, ExtractionConfig config) throws Error
```

##### supportedMimeTypes()

**Signature:**

```java
public List<String> supportedMimeTypes()
```

##### priority()

**Signature:**

```java
public int priority()
```


---

### CsvMetadata

CSV/TSV file metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `rowCount` | `long` | `null` | Number of row |
| `columnCount` | `long` | `null` | Number of column |
| `delimiter` | `Optional<String>` | `null` | Delimiter |
| `hasHeader` | `boolean` | `null` | Whether header |
| `columnTypes` | `Optional<List<String>>` | `Collections.emptyList()` | Column types |


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

##### defaultOptions()

**Signature:**

```java
public static DbfExtractor defaultOptions()
```

##### name()

**Signature:**

```java
public String name()
```

##### version()

**Signature:**

```java
public String version()
```

##### initialize()

**Signature:**

```java
public void initialize() throws Error
```

##### shutdown()

**Signature:**

```java
public void shutdown() throws Error
```

##### description()

**Signature:**

```java
public String description()
```

##### author()

**Signature:**

```java
public String author()
```

##### extractBytes()

**Signature:**

```java
public InternalDocument extractBytes(byte[] content, String mimeType, ExtractionConfig config) throws Error
```

##### supportedMimeTypes()

**Signature:**

```java
public List<String> supportedMimeTypes()
```

##### priority()

**Signature:**

```java
public int priority()
```


---

### DbfFieldInfo

dBASE field information.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `name` | `String` | — | The name |
| `fieldType` | `String` | — | Field type |


---

### DbfMetadata

dBASE (DBF) file metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `recordCount` | `long` | `null` | Number of record |
| `fieldCount` | `long` | `null` | Number of field |
| `fields` | `List<DbfFieldInfo>` | `Collections.emptyList()` | Fields |


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

```java
public void push() throws SecurityError
```

##### pop()

Pop a level (decrease depth).

**Signature:**

```java
public void pop()
```

##### currentDepth()

Get current depth.

**Signature:**

```java
public long currentDepth()
```


---

### DetectTimings

Granular timing breakdown for a single `detect()` call.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `preprocessMs` | `double` | `null` | Time spent in image preprocessing (resize, letterbox, normalize, tensor allocation). |
| `onnxMs` | `double` | `null` | Time for the ONNX `session.run()` call (actual neural network computation). |
| `modelTotalMs` | `double` | `null` | Total time from start of model call to end of raw output decoding. |
| `postprocessMs` | `double` | `null` | Time spent in postprocessing heuristics (confidence filtering, overlap resolution). |


---

### DetectionResult

Page-level detection result containing all detections and page metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `pageWidth` | `int` | — | Page width |
| `pageHeight` | `int` | — | Page height |
| `detections` | `List<LayoutDetection>` | — | Detections |


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
| `plainText` | `String` | — | Plain text representation for backwards compatibility |
| `blocks` | `List<FormattedBlock>` | — | Structured block-level content |
| `metadata` | `Metadata` | — | Metadata from YAML frontmatter |
| `tables` | `List<Table>` | — | Extracted tables as structured data |
| `images` | `List<DjotImage>` | — | Extracted images with metadata |
| `links` | `List<DjotLink>` | — | Extracted links with URLs |
| `footnotes` | `List<Footnote>` | — | Footnote definitions |
| `attributes` | `List<StringAttributes>` | — | Attributes mapped by element identifier (if present) |


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

```java
public static InternalDocument buildInternalDocument(List<Event> events)
```

##### defaultOptions()

**Signature:**

```java
public static DjotExtractor defaultOptions()
```

##### name()

**Signature:**

```java
public String name()
```

##### version()

**Signature:**

```java
public String version()
```

##### initialize()

**Signature:**

```java
public void initialize() throws Error
```

##### shutdown()

**Signature:**

```java
public void shutdown() throws Error
```

##### description()

**Signature:**

```java
public String description()
```

##### author()

**Signature:**

```java
public String author()
```

##### extractBytes()

**Signature:**

```java
public InternalDocument extractBytes(byte[] content, String mimeType, ExtractionConfig config) throws Error
```

##### extractFile()

**Signature:**

```java
public InternalDocument extractFile(String path, String mimeType, ExtractionConfig config) throws Error
```

##### supportedMimeTypes()

**Signature:**

```java
public List<String> supportedMimeTypes()
```

##### priority()

**Signature:**

```java
public int priority()
```


---

### DjotImage

Image element in Djot.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `src` | `String` | — | Image source URL or path |
| `alt` | `String` | — | Alternative text |
| `title` | `Optional<String>` | `null` | Optional title |
| `attributes` | `Optional<Attributes>` | `null` | Element attributes |


---

### DjotLink

Link element in Djot.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `url` | `String` | — | Link URL |
| `text` | `String` | — | Link text content |
| `title` | `Optional<String>` | `null` | Optional title |
| `attributes` | `Optional<Attributes>` | `null` | Element attributes |


---

### DocExtractionResult

Result of DOC text extraction.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `text` | `String` | — | Extracted text content. |
| `metadata` | `DocMetadata` | — | Document metadata. |


---

### DocExtractor

Native DOC extractor using OLE/CFB parsing.

This extractor handles Word 97-2003 binary (.doc) files without
requiring LibreOffice, providing ~50x faster extraction.

#### Methods

##### defaultOptions()

**Signature:**

```java
public static DocExtractor defaultOptions()
```

##### name()

**Signature:**

```java
public String name()
```

##### version()

**Signature:**

```java
public String version()
```

##### initialize()

**Signature:**

```java
public void initialize() throws Error
```

##### shutdown()

**Signature:**

```java
public void shutdown() throws Error
```

##### description()

**Signature:**

```java
public String description()
```

##### author()

**Signature:**

```java
public String author()
```

##### extractBytes()

**Signature:**

```java
public InternalDocument extractBytes(byte[] content, String mimeType, ExtractionConfig config) throws Error
```

##### supportedMimeTypes()

**Signature:**

```java
public List<String> supportedMimeTypes()
```

##### priority()

**Signature:**

```java
public int priority()
```


---

### DocMetadata

Metadata extracted from DOC files.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `title` | `Optional<String>` | `null` | Title |
| `subject` | `Optional<String>` | `null` | Subject |
| `author` | `Optional<String>` | `null` | Author |
| `lastAuthor` | `Optional<String>` | `null` | Last author |
| `created` | `Optional<String>` | `null` | Created |
| `modified` | `Optional<String>` | `null` | Modified |
| `revisionNumber` | `Optional<String>` | `null` | Revision number |


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

```java
public OrientationResult detect(RgbImage image) throws Error
```


---

### DocProperties

Document properties from `<wp:docPr>`.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `id` | `Optional<String>` | `null` | Unique identifier |
| `name` | `Optional<String>` | `null` | The name |
| `description` | `Optional<String>` | `null` | Human-readable description |


---

### DocbookExtractor

DocBook document extractor.

Supports both DocBook 4.x (no namespace) and 5.x (with namespace) formats.

#### Methods

##### defaultOptions()

**Signature:**

```java
public static DocbookExtractor defaultOptions()
```

##### name()

**Signature:**

```java
public String name()
```

##### version()

**Signature:**

```java
public String version()
```

##### initialize()

**Signature:**

```java
public void initialize() throws Error
```

##### shutdown()

**Signature:**

```java
public void shutdown() throws Error
```

##### extractBytes()

**Signature:**

```java
public InternalDocument extractBytes(byte[] content, String mimeType, ExtractionConfig config) throws Error
```

##### supportedMimeTypes()

**Signature:**

```java
public List<String> supportedMimeTypes()
```

##### priority()

**Signature:**

```java
public int priority()
```


---

### Document

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `paragraphs` | `List<Paragraph>` | `Collections.emptyList()` | Paragraphs |
| `tables` | `List<Table>` | `Collections.emptyList()` | Tables extracted from the document |
| `headers` | `List<HeaderFooter>` | `Collections.emptyList()` | Headers |
| `footers` | `List<HeaderFooter>` | `Collections.emptyList()` | Footers |
| `footnotes` | `List<Note>` | `Collections.emptyList()` | Footnotes |
| `endnotes` | `List<Note>` | `Collections.emptyList()` | Endnotes |
| `numberingDefs` | `AHashMap` | `null` | Numbering defs (a hash map) |
| `elements` | `List<DocumentElement>` | `Collections.emptyList()` | Document elements in their original order. |
| `styleCatalog` | `Optional<StyleCatalog>` | `null` | Parsed style catalog from `word/styles.xml`, if available. |
| `theme` | `Optional<Theme>` | `null` | Parsed theme from `word/theme/theme1.xml`, if available. |
| `sections` | `List<SectionProperties>` | `Collections.emptyList()` | Section properties parsed from `w:sectPr` elements. |
| `drawings` | `List<Drawing>` | `Collections.emptyList()` | Drawing objects parsed from `w:drawing` elements. |
| `imageRelationships` | `AHashMap` | `null` | Image relationships (rId → target path) for image extraction. |

#### Methods

##### resolveHeadingLevel()

Resolve heading level for a paragraph style using the StyleCatalog.

Walks the style inheritance chain to find `outline_level`.
Falls back to string-matching on style name/ID if no StyleCatalog is available.
Returns 1-6 (markdown heading levels).

**Signature:**

```java
public Optional<byte> resolveHeadingLevel(String styleId)
```

##### extractText()

**Signature:**

```java
public String extractText()
```

##### toMarkdown()

Render the document as markdown.

When `inject_placeholders` is `true`, drawings that reference an image
emit `![alt](image)` placeholders. When `false` they are silently
skipped, which is useful when the caller only wants text.

**Signature:**

```java
public String toMarkdown(boolean injectPlaceholders)
```

##### toPlainText()

Render the document as plain text (no markdown formatting).

**Signature:**

```java
public String toPlainText()
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
| `parent` | `Optional<int>` | `null` | Parent node index (`None` = root-level node). |
| `children` | `List<Integer>` | — | Child node indices in reading order. |
| `contentLayer` | `ContentLayer` | — | Content layer classification. |
| `page` | `Optional<int>` | `null` | Page number where this node starts (1-indexed). |
| `pageEnd` | `Optional<int>` | `null` | Page number where this node ends (for multi-page tables/sections). |
| `bbox` | `Optional<BoundingBox>` | `null` | Bounding box in document coordinates. |
| `annotations` | `List<TextAnnotation>` | — | Inline annotations (formatting, links) on this node's text content. Only meaningful for text-carrying nodes; empty for containers. |
| `attributes` | `Optional<Map<String, String>>` | `null` | Format-specific key-value attributes. Extensible bag for data that doesn't warrant a typed field: CSS classes, LaTeX environment names, Excel cell formulas, slide layout names, etc. |


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
| `nodes` | `List<DocumentNode>` | `Collections.emptyList()` | All nodes in document/reading order. |
| `sourceFormat` | `Optional<String>` | `null` | Origin format identifier (e.g. "docx", "pptx", "html", "pdf"). Allows renderers to apply format-aware heuristics when converting the document tree to output formats. |
| `relationships` | `List<DocumentRelationship>` | `Collections.emptyList()` | Resolved relationships between nodes (footnote refs, citations, anchor links, etc.). Populated during derivation from the internal document representation. Empty when no relationships are detected. |

#### Methods

##### withCapacity()

Create a `DocumentStructure` with pre-allocated capacity.

**Signature:**

```java
public static DocumentStructure withCapacity(long capacity)
```

##### pushNode()

Push a node and return its `NodeIndex`.

**Signature:**

```java
public int pushNode(DocumentNode node)
```

##### addChild()

Add a child to an existing parent node.

Updates both the parent's `children` list and the child's `parent` field.

**Panics:**

Panics if either index is out of bounds.

**Signature:**

```java
public void addChild(int parent, int child)
```

##### validate()

Validate all node indices are in bounds and parent-child relationships
are bidirectionally consistent.

**Errors:**

Returns a descriptive error string if validation fails.

**Signature:**

```java
public void validate() throws String
```

##### bodyRoots()

Iterate over root-level body nodes (content_layer == Body, parent == None).

**Signature:**

```java
public Iterator bodyRoots()
```

##### furnitureRoots()

Iterate over root-level furniture nodes (non-Body content_layer, parent == None).

**Signature:**

```java
public Iterator furnitureRoots()
```

##### get()

Get a node by index.

**Signature:**

```java
public Optional<DocumentNode> get(int index)
```

##### len()

Get the total number of nodes.

**Signature:**

```java
public long len()
```

##### isEmpty()

Check if the document structure is empty.

**Signature:**

```java
public boolean isEmpty()
```

##### defaultOptions()

**Signature:**

```java
public static DocumentStructure defaultOptions()
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

```java
public static DocumentStructureBuilder withCapacity(long capacity)
```

##### sourceFormat()

Set the source format identifier (e.g. "docx", "html", "pptx").

**Signature:**

```java
public DocumentStructureBuilder sourceFormat(String format)
```

##### build()

Consume the builder and return the constructed `DocumentStructure`.

**Signature:**

```java
public DocumentStructure build()
```

##### pushHeading()

Push a heading, creating a `Group` container with automatic section nesting.

Headings at the same or deeper level pop existing sections. Content
pushed after this heading will be nested under its `Group` node.

Returns the `NodeIndex` of the `Group` node (not the heading child).

**Signature:**

```java
public int pushHeading(byte level, String text, int page, BoundingBox bbox)
```

##### pushParagraph()

Push a paragraph node. Nested under current section if one exists.

**Signature:**

```java
public int pushParagraph(String text, List<TextAnnotation> annotations, int page, BoundingBox bbox)
```

##### pushList()

Push a list container. Returns the `NodeIndex` to use with `push_list_item`.

**Signature:**

```java
public int pushList(boolean ordered, int page)
```

##### pushListItem()

Push a list item as a child of the given list node.

**Signature:**

```java
public int pushListItem(int list, String text, int page)
```

##### pushTable()

Push a table node with a structured grid.

**Signature:**

```java
public int pushTable(TableGrid grid, int page, BoundingBox bbox)
```

##### pushTableFromCells()

Push a table from a simple cell grid (`Vec<Vec<String>>`).

Assumes the first row is the header row.

**Signature:**

```java
public int pushTableFromCells(List<List<String>> cells, int page)
```

##### pushCode()

Push a code block.

**Signature:**

```java
public int pushCode(String text, String language, int page)
```

##### pushFormula()

Push a math formula node.

**Signature:**

```java
public int pushFormula(String text, int page)
```

##### pushImage()

Push an image reference node.

**Signature:**

```java
public int pushImage(String description, int imageIndex, int page, BoundingBox bbox)
```

##### pushImageWithSrc()

Push an image node with source URL.

**Signature:**

```java
public int pushImageWithSrc(String description, String src, int imageIndex, int page, BoundingBox bbox)
```

##### pushQuote()

Push a block quote container and enter it.

Subsequent body nodes will be parented under this quote until
`exit_container` is called.

**Signature:**

```java
public int pushQuote(int page)
```

##### pushFootnote()

Push a footnote node.

**Signature:**

```java
public int pushFootnote(String text, int page)
```

##### pushPageBreak()

Push a page break marker (always root-level, never nested under sections).

**Signature:**

```java
public int pushPageBreak(int page)
```

##### pushSlide()

Push a slide container (PPTX) and enter it.

Clears the section stack and container stack so the slide starts
fresh. Subsequent body nodes will be parented under this slide
until `exit_container` is called or a new
slide is pushed.

**Signature:**

```java
public int pushSlide(int number, String title)
```

##### pushDefinitionList()

Push a definition list container. Use `push_definition_item` for entries.

**Signature:**

```java
public int pushDefinitionList(int page)
```

##### pushDefinitionItem()

Push a definition item as a child of the given definition list.

**Signature:**

```java
public int pushDefinitionItem(int list, String term, String definition, int page)
```

##### pushCitation()

Push a citation / bibliographic reference.

**Signature:**

```java
public int pushCitation(String key, String text, int page)
```

##### pushAdmonition()

Push an admonition container (note, warning, tip, etc.) and enter it.

Subsequent body nodes will be parented under this admonition until
`exit_container` is called.

**Signature:**

```java
public int pushAdmonition(String kind, String title, int page)
```

##### pushRawBlock()

Push a raw block preserved verbatim from the source format.

**Signature:**

```java
public int pushRawBlock(String format, String content, int page)
```

##### pushMetadataBlock()

Push a metadata block (email headers, frontmatter key-value pairs).

**Signature:**

```java
public int pushMetadataBlock(List<StringString> entries, int page)
```

##### pushHeader()

Push a header paragraph (running page header).

**Signature:**

```java
public int pushHeader(String text, int page)
```

##### pushFooter()

Push a footer paragraph (running page footer).

**Signature:**

```java
public int pushFooter(String text, int page)
```

##### setAttributes()

Set format-specific attributes on an existing node.

**Signature:**

```java
public void setAttributes(int index, AHashMap attrs)
```

##### addChild()

Add a child node to an existing parent (for container nodes like Quote, Slide, Admonition).

**Signature:**

```java
public void addChild(int parent, int child)
```

##### pushRaw()

Push a raw `NodeContent` with full control over content layer and annotations.
Nests under current section unless the content type is a root-level type.

**Signature:**

```java
public int pushRaw(NodeContent content, int page, BoundingBox bbox, ContentLayer layer, List<TextAnnotation> annotations)
```

##### clearSections()

Reset the section stack (e.g. when starting a new page).

**Signature:**

```java
public void clearSections()
```

##### enterContainer()

Manually push a node onto the container stack.

Subsequent body nodes will be parented under this container
until `exit_container` is called.

**Signature:**

```java
public void enterContainer(int container)
```

##### exitContainer()

Pop the most recent container from the container stack.

Body nodes will resume parenting under the next container on the
stack, or under the section stack if the container stack is empty.

**Signature:**

```java
public void exitContainer()
```

##### defaultOptions()

**Signature:**

```java
public static DocumentStructureBuilder defaultOptions()
```


---

### DocxAppProperties

Application properties from docProps/app.xml for DOCX

Contains Word-specific document statistics and metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `application` | `Optional<String>` | `null` | Application name (e.g., "Microsoft Office Word") |
| `appVersion` | `Optional<String>` | `null` | Application version |
| `template` | `Optional<String>` | `null` | Template filename |
| `totalTime` | `Optional<int>` | `null` | Total editing time in minutes |
| `pages` | `Optional<int>` | `null` | Number of pages |
| `words` | `Optional<int>` | `null` | Number of words |
| `characters` | `Optional<int>` | `null` | Number of characters (excluding spaces) |
| `charactersWithSpaces` | `Optional<int>` | `null` | Number of characters (including spaces) |
| `lines` | `Optional<int>` | `null` | Number of lines |
| `paragraphs` | `Optional<int>` | `null` | Number of paragraphs |
| `company` | `Optional<String>` | `null` | Company name |
| `docSecurity` | `Optional<int>` | `null` | Document security level |
| `scaleCrop` | `Optional<boolean>` | `null` | Scale crop flag |
| `linksUpToDate` | `Optional<boolean>` | `null` | Links up to date flag |
| `sharedDoc` | `Optional<boolean>` | `null` | Shared document flag |
| `hyperlinksChanged` | `Optional<boolean>` | `null` | Hyperlinks changed flag |


---

### DocxExtractor

High-performance DOCX extractor.

This extractor provides:
- Fast text extraction via streaming XML parsing
- Comprehensive metadata extraction (core.xml, app.xml, custom.xml)

#### Methods

##### defaultOptions()

**Signature:**

```java
public static DocxExtractor defaultOptions()
```

##### name()

**Signature:**

```java
public String name()
```

##### version()

**Signature:**

```java
public String version()
```

##### initialize()

**Signature:**

```java
public void initialize() throws Error
```

##### shutdown()

**Signature:**

```java
public void shutdown() throws Error
```

##### description()

**Signature:**

```java
public String description()
```

##### author()

**Signature:**

```java
public String author()
```

##### extractBytes()

**Signature:**

```java
public InternalDocument extractBytes(byte[] content, String mimeType, ExtractionConfig config) throws Error
```

##### supportedMimeTypes()

**Signature:**

```java
public List<String> supportedMimeTypes()
```

##### priority()

**Signature:**

```java
public int priority()
```


---

### DocxMetadata

Word document metadata.

Extracted from DOCX files using shared Office Open XML metadata extraction.
Integrates with `office_metadata` module for core/app/custom properties.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `coreProperties` | `Optional<CoreProperties>` | `null` | Core properties from docProps/core.xml (Dublin Core metadata) Contains title, creator, subject, keywords, dates, etc. Shared format across DOCX/PPTX/XLSX documents. |
| `appProperties` | `Optional<DocxAppProperties>` | `null` | Application properties from docProps/app.xml (Word-specific statistics) Contains word count, page count, paragraph count, editing time, etc. DOCX-specific variant of Office application properties. |
| `customProperties` | `Optional<Map<String, Object>>` | `null` | Custom properties from docProps/custom.xml (user-defined properties) Contains key-value pairs defined by users or applications. Values can be strings, numbers, booleans, or dates. |


---

### Drawing

A drawing object extracted from `<w:drawing>`.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `drawingType` | `DrawingType` | — | Drawing type (drawing type) |
| `extent` | `Optional<Extent>` | `null` | Extent (extent) |
| `docProperties` | `Optional<DocProperties>` | `null` | Doc properties (doc properties) |
| `imageRef` | `Optional<String>` | `null` | Image ref |


---

### Element

Semantic element extracted from document.

Represents a logical unit of content with semantic classification,
unique identifier, and metadata for tracking origin and position.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `elementId` | `ElementId` | — | Unique element identifier |
| `elementType` | `ElementType` | — | Semantic type of this element |
| `text` | `String` | — | Text content of the element |
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

```java
public static ElementId new(String hexStr) throws String
```

##### asRef()

**Signature:**

```java
public String asRef()
```

##### fmt()

**Signature:**

```java
public Unknown fmt(Formatter f)
```


---

### ElementMetadata

Metadata for a semantic element.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `pageNumber` | `Optional<long>` | `null` | Page number (1-indexed) |
| `filename` | `Optional<String>` | `null` | Source filename or document name |
| `coordinates` | `Optional<BoundingBox>` | `null` | Bounding box coordinates if available |
| `elementIndex` | `Optional<long>` | `null` | Position index in the element sequence |
| `additional` | `Map<String, String>` | — | Additional custom metadata |


---

### EmailAttachment

Email attachment representation.

Contains metadata and optionally the content of an email attachment.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `name` | `Optional<String>` | `null` | Attachment name (from Content-Disposition header) |
| `filename` | `Optional<String>` | `null` | Filename of the attachment |
| `mimeType` | `Optional<String>` | `null` | MIME type of the attachment |
| `size` | `Optional<long>` | `null` | Size in bytes |
| `isImage` | `boolean` | — | Whether this attachment is an image |
| `data` | `Optional<byte[]>` | `null` | Attachment data (if extracted). Uses `bytes.Bytes` for cheap cloning of large buffers. |


---

### EmailConfig

Configuration for email extraction.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `msgFallbackCodepage` | `Optional<int>` | `null` | Windows codepage number to use when an MSG file contains no codepage property. Defaults to `None`, which falls back to windows-1252. If an unrecognized or invalid codepage number is supplied (including 0), the behavior silently falls back to windows-1252 — the same as when the MSG file itself contains an unrecognized codepage. No error or warning is emitted. Users should verify output when supplying unusual values. Common values: - 1250: Central European (Polish, Czech, Hungarian, etc.) - 1251: Cyrillic (Russian, Ukrainian, Bulgarian, etc.) - 1252: Western European (default) - 1253: Greek - 1254: Turkish - 1255: Hebrew - 1256: Arabic - 932:  Japanese (Shift-JIS) - 936:  Simplified Chinese (GBK) |


---

### EmailExtractionResult

Email extraction result.

Complete representation of an extracted email message (.eml or .msg)
including headers, body content, and attachments.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `subject` | `Optional<String>` | `null` | Email subject line |
| `fromEmail` | `Optional<String>` | `null` | Sender email address |
| `toEmails` | `List<String>` | — | Primary recipient email addresses |
| `ccEmails` | `List<String>` | — | CC recipient email addresses |
| `bccEmails` | `List<String>` | — | BCC recipient email addresses |
| `date` | `Optional<String>` | `null` | Email date/timestamp |
| `messageId` | `Optional<String>` | `null` | Message-ID header value |
| `plainText` | `Optional<String>` | `null` | Plain text version of the email body |
| `htmlContent` | `Optional<String>` | `null` | HTML version of the email body |
| `cleanedText` | `String` | — | Cleaned/processed text content |
| `attachments` | `List<EmailAttachment>` | — | List of email attachments |
| `metadata` | `Map<String, String>` | — | Additional email headers and metadata |


---

### EmailExtractor

Email message extractor.

Supports: .eml, .msg

#### Methods

##### defaultOptions()

**Signature:**

```java
public static EmailExtractor defaultOptions()
```

##### name()

**Signature:**

```java
public String name()
```

##### version()

**Signature:**

```java
public String version()
```

##### initialize()

**Signature:**

```java
public void initialize() throws Error
```

##### shutdown()

**Signature:**

```java
public void shutdown() throws Error
```

##### extractSync()

**Signature:**

```java
public InternalDocument extractSync(byte[] content, String mimeType, ExtractionConfig config) throws Error
```

##### extractBytes()

**Signature:**

```java
public InternalDocument extractBytes(byte[] content, String mimeType, ExtractionConfig config) throws Error
```

##### supportedMimeTypes()

**Signature:**

```java
public List<String> supportedMimeTypes()
```

##### priority()

**Signature:**

```java
public int priority()
```

##### asSyncExtractor()

**Signature:**

```java
public Optional<SyncExtractor> asSyncExtractor()
```


---

### EmailMetadata

Email metadata extracted from .eml and .msg files.

Includes sender/recipient information, message ID, and attachment list.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `fromEmail` | `Optional<String>` | `null` | Sender's email address |
| `fromName` | `Optional<String>` | `null` | Sender's display name |
| `toEmails` | `List<String>` | — | Primary recipients |
| `ccEmails` | `List<String>` | — | CC recipients |
| `bccEmails` | `List<String>` | — | BCC recipients |
| `messageId` | `Optional<String>` | `null` | Message-ID header value |
| `attachments` | `List<String>` | — | List of attachment filenames |


---

### EmbeddedFile

Embedded file descriptor extracted from the PDF name tree.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `name` | `String` | — | The filename as stored in the PDF name tree. |
| `data` | `byte[]` | — | Raw file bytes from the embedded stream. |
| `mimeType` | `Optional<String>` | `null` | MIME type if specified in the filespec, otherwise `None`. |


---

### EmbeddingConfig

Embedding configuration for text chunks.

Configures embedding generation using ONNX models via the vendored embedding engine.
Requires the `embeddings` feature to be enabled.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `model` | `EmbeddingModelType` | `EmbeddingModelType.Preset` | The embedding model to use (defaults to "balanced" preset if not specified) |
| `normalize` | `boolean` | `true` | Whether to normalize embedding vectors (recommended for cosine similarity) |
| `batchSize` | `long` | `32` | Batch size for embedding generation |
| `showDownloadProgress` | `boolean` | `false` | Show model download progress |
| `cacheDir` | `Optional<String>` | `null` | Custom cache directory for model files Defaults to `~/.cache/kreuzberg/embeddings/` if not specified. Allows full customization of model download location. |

#### Methods

##### defaultOptions()

**Signature:**

```java
public static EmbeddingConfig defaultOptions()
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
| `name` | `String` | — | The name |
| `chunkSize` | `long` | — | Chunk size |
| `overlap` | `long` | — | Overlap |
| `modelRepo` | `String` | — | HuggingFace repository name for the model. |
| `pooling` | `String` | — | Pooling strategy: "cls" or "mean". |
| `modelFile` | `String` | — | Path to the ONNX model file within the repo. |
| `dimensions` | `long` | — | Dimensions |
| `description` | `String` | — | Human-readable description |


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

```java
public void validate(String content) throws SecurityError
```


---

### EpubExtractor

EPUB format extractor using permissive-licensed dependencies.

Extracts content and metadata from EPUB files (both EPUB2 and EPUB3)
using native Rust parsing without GPL-licensed dependencies.

#### Methods

##### defaultOptions()

**Signature:**

```java
public static EpubExtractor defaultOptions()
```

##### name()

**Signature:**

```java
public String name()
```

##### version()

**Signature:**

```java
public String version()
```

##### initialize()

**Signature:**

```java
public void initialize() throws Error
```

##### shutdown()

**Signature:**

```java
public void shutdown() throws Error
```

##### description()

**Signature:**

```java
public String description()
```

##### author()

**Signature:**

```java
public String author()
```

##### extractBytes()

**Signature:**

```java
public InternalDocument extractBytes(byte[] content, String mimeType, ExtractionConfig config) throws Error
```

##### supportedMimeTypes()

**Signature:**

```java
public List<String> supportedMimeTypes()
```

##### priority()

**Signature:**

```java
public int priority()
```


---

### EpubMetadata

EPUB metadata (Dublin Core extensions).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `coverage` | `Optional<String>` | `null` | Coverage |
| `dcFormat` | `Optional<String>` | `null` | Dc format |
| `relation` | `Optional<String>` | `null` | Relation |
| `source` | `Optional<String>` | `null` | Source |
| `dcType` | `Optional<String>` | `null` | Dc type |
| `coverImage` | `Optional<String>` | `null` | Cover image |


---

### ErrorMetadata

Error metadata (for batch operations).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `errorType` | `String` | — | Error type |
| `message` | `String` | — | Message |


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

##### defaultOptions()

**Signature:**

```java
public static ExcelExtractor defaultOptions()
```

##### name()

**Signature:**

```java
public String name()
```

##### version()

**Signature:**

```java
public String version()
```

##### initialize()

**Signature:**

```java
public void initialize() throws Error
```

##### shutdown()

**Signature:**

```java
public void shutdown() throws Error
```

##### extractSync()

**Signature:**

```java
public InternalDocument extractSync(byte[] content, String mimeType, ExtractionConfig config) throws Error
```

##### extractBytes()

**Signature:**

```java
public InternalDocument extractBytes(byte[] content, String mimeType, ExtractionConfig config) throws Error
```

##### extractFile()

**Signature:**

```java
public InternalDocument extractFile(String path, String mimeType, ExtractionConfig config) throws Error
```

##### supportedMimeTypes()

**Signature:**

```java
public List<String> supportedMimeTypes()
```

##### priority()

**Signature:**

```java
public int priority()
```

##### asSyncExtractor()

**Signature:**

```java
public Optional<SyncExtractor> asSyncExtractor()
```


---

### ExcelMetadata

Excel/spreadsheet metadata.

Contains information about sheets in Excel, OpenDocument Calc, and other
spreadsheet formats (.xlsx, .xls, .ods, etc.).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `sheetCount` | `long` | — | Total number of sheets in the workbook |
| `sheetNames` | `List<String>` | — | Names of all sheets in order |


---

### ExcelSheet

Single Excel worksheet.

Represents one sheet from an Excel workbook with its content
converted to Markdown format and dimensional statistics.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `name` | `String` | — | Sheet name as it appears in Excel |
| `markdown` | `String` | — | Sheet content converted to Markdown tables |
| `rowCount` | `long` | — | Number of rows |
| `colCount` | `long` | — | Number of columns |
| `cellCount` | `long` | — | Total number of non-empty cells |
| `tableCells` | `Optional<List<List<String>>>` | `null` | Pre-extracted table cells (2D vector of cell values) Populated during markdown generation to avoid re-parsing markdown. None for empty sheets. |


---

### ExcelWorkbook

Excel workbook representation.

Contains all sheets from an Excel file (.xlsx, .xls, etc.) with
extracted content and metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `sheets` | `List<ExcelSheet>` | — | All sheets in the workbook |
| `metadata` | `Map<String, String>` | — | Workbook-level metadata (author, creation date, etc.) |


---

### Extent

Size in EMUs (English Metric Units, 1 inch = 914400 EMU).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `cx` | `long` | `null` | Cx |
| `cy` | `long` | `null` | Cy |

#### Methods

##### widthInches()

Convert width to inches.

**Signature:**

```java
public double widthInches()
```

##### heightInches()

Convert height to inches.

**Signature:**

```java
public double heightInches()
```


---

### ExtractedImage

Extracted image from a document.

Contains raw image data, metadata, and optional nested OCR results.
Raw bytes allow cross-language compatibility - users can convert to
PIL.Image (Python), Sharp (Node.js), or other formats as needed.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `data` | `byte[]` | — | Raw image data (PNG, JPEG, WebP, etc. bytes). Uses `bytes.Bytes` for cheap cloning of large buffers. |
| `format` | `Str` | — | Image format (e.g., "jpeg", "png", "webp") Uses Cow<'static, str> to avoid allocation for static literals. |
| `imageIndex` | `long` | — | Zero-indexed position of this image in the document/page |
| `pageNumber` | `Optional<long>` | `null` | Page/slide number where image was found (1-indexed) |
| `width` | `Optional<int>` | `null` | Image width in pixels |
| `height` | `Optional<int>` | `null` | Image height in pixels |
| `colorspace` | `Optional<String>` | `null` | Colorspace information (e.g., "RGB", "CMYK", "Gray") |
| `bitsPerComponent` | `Optional<int>` | `null` | Bits per color component (e.g., 8, 16) |
| `isMask` | `boolean` | — | Whether this image is a mask image |
| `description` | `Optional<String>` | `null` | Optional description of the image |
| `ocrResult` | `Optional<ExtractionResult>` | `null` | Nested OCR extraction result (if image was OCRed) When OCR is performed on this image, the result is embedded here rather than in a separate collection, making the relationship explicit. |
| `boundingBox` | `Optional<BoundingBox>` | `null` | Bounding box of the image on the page (PDF coordinates: x0=left, y0=bottom, x1=right, y1=top). Only populated for PDF-extracted images when position data is available from pdfium. |
| `sourcePath` | `Optional<String>` | `null` | Original source path of the image within the document archive (e.g., "media/image1.png" in DOCX). Used for rendering image references when the binary data is not extracted. |


---

### ExtractionConfig

Main extraction configuration.

This struct contains all configuration options for the extraction process.
It can be loaded from TOML, YAML, or JSON files, or created programmatically.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `useCache` | `boolean` | `true` | Enable caching of extraction results |
| `enableQualityProcessing` | `boolean` | `true` | Enable quality post-processing |
| `ocr` | `Optional<OcrConfig>` | `null` | OCR configuration (None = OCR disabled) |
| `forceOcr` | `boolean` | `false` | Force OCR even for searchable PDFs |
| `forceOcrPages` | `Optional<List<Long>>` | `Collections.emptyList()` | Force OCR on specific pages only (1-indexed page numbers, must be >= 1). When set, only the listed pages are OCR'd regardless of text layer quality. Unlisted pages use native text extraction. Ignored when `force_ocr` is `True`. Only applies to PDF documents. Duplicates are automatically deduplicated. An `ocr` config is recommended for backend/language selection; defaults are used if absent. |
| `disableOcr` | `boolean` | `false` | Disable OCR entirely, even for images. When `True`, OCR is skipped for all document types. Images return metadata only (dimensions, format, EXIF) without text extraction. PDFs use only native text extraction without OCR fallback. Cannot be `True` simultaneously with `force_ocr`. *Added in v4.7.0.* |
| `chunking` | `Optional<ChunkingConfig>` | `null` | Text chunking configuration (None = chunking disabled) |
| `contentFilter` | `Optional<ContentFilterConfig>` | `null` | Content filtering configuration (None = use extractor defaults). Controls whether document "furniture" (headers, footers, watermarks, repeating text) is included in or stripped from extraction results. See `ContentFilterConfig` for per-field documentation. |
| `images` | `Optional<ImageExtractionConfig>` | `null` | Image extraction configuration (None = no image extraction) |
| `pdfOptions` | `Optional<PdfConfig>` | `null` | PDF-specific options (None = use defaults) |
| `tokenReduction` | `Optional<TokenReductionConfig>` | `null` | Token reduction configuration (None = no token reduction) |
| `languageDetection` | `Optional<LanguageDetectionConfig>` | `null` | Language detection configuration (None = no language detection) |
| `pages` | `Optional<PageConfig>` | `null` | Page extraction configuration (None = no page tracking) |
| `postprocessor` | `Optional<PostProcessorConfig>` | `null` | Post-processor configuration (None = use defaults) |
| `htmlOptions` | `Optional<ConversionOptions>` | `null` | HTML to Markdown conversion options (None = use defaults) Configure how HTML documents are converted to Markdown, including heading styles, list formatting, code block styles, and preprocessing options. |
| `htmlOutput` | `Optional<HtmlOutputConfig>` | `null` | Styled HTML output configuration. When set alongside `output_format = OutputFormat.Html`, the extraction pipeline uses `StyledHtmlRenderer` which emits stable `kb-*` CSS class hooks on every structural element and optionally embeds theme CSS or user-supplied CSS in a `<style>` block. When `None`, the existing plain comrak-based HTML renderer is used. |
| `extractionTimeoutSecs` | `Optional<long>` | `null` | Default per-file timeout in seconds for batch extraction. When set, each file in a batch will be canceled after this duration unless overridden by `FileExtractionConfig.timeout_secs`. `None` means no timeout (unbounded extraction time). |
| `maxConcurrentExtractions` | `Optional<long>` | `null` | Maximum concurrent extractions in batch operations (None = (num_cpus × 1.5).ceil()). Limits parallelism to prevent resource exhaustion when processing large batches. Defaults to (num_cpus × 1.5).ceil() when not set. |
| `resultFormat` | `OutputFormat` | `OutputFormat.Plain` | Result structure format Controls whether results are returned in unified format (default) with all content in the `content` field, or element-based format with semantic elements (for Unstructured-compatible output). |
| `securityLimits` | `Optional<SecurityLimits>` | `null` | Security limits for archive extraction. Controls maximum archive size, compression ratio, file count, and other security thresholds to prevent decompression bomb attacks. When `None`, default limits are used (500MB archive, 100:1 ratio, 10K files). |
| `outputFormat` | `OutputFormat` | `OutputFormat.Plain` | Content text format (default: Plain). Controls the format of the extracted content: - `Plain`: Raw extracted text (default) - `Markdown`: Markdown formatted output - `Djot`: Djot markup format (requires djot feature) - `Html`: HTML formatted output When set to a structured format, extraction results will include formatted output. The `formatted_content` field may be populated when format conversion is applied. |
| `layout` | `Optional<LayoutDetectionConfig>` | `null` | Layout detection configuration (None = layout detection disabled). When set, PDF pages and images are analyzed for document structure (headings, code, formulas, tables, figures, etc.) using RT-DETR models via ONNX Runtime. For PDFs, layout hints override paragraph classification in the markdown pipeline. For images, per-region OCR is performed with markdown formatting based on detected layout classes. Requires the `layout-detection` feature. |
| `includeDocumentStructure` | `boolean` | `false` | Enable structured document tree output. When true, populates the `document` field on `ExtractionResult` with a hierarchical `DocumentStructure` containing heading-driven section nesting, table grids, content layer classification, and inline annotations. Independent of `result_format` — can be combined with Unified or ElementBased. |
| `acceleration` | `Optional<AccelerationConfig>` | `null` | Hardware acceleration configuration for ONNX Runtime models. Controls execution provider selection for layout detection and embedding models. When `None`, uses platform defaults (CoreML on macOS, CUDA on Linux, CPU on Windows). |
| `cacheNamespace` | `Optional<String>` | `null` | Cache namespace for tenant isolation. When set, cache entries are stored under `{cache_dir}/{namespace}/`. Must be alphanumeric, hyphens, or underscores only (max 64 chars). Different namespaces have isolated cache spaces on the same filesystem. |
| `cacheTtlSecs` | `Optional<long>` | `null` | Per-request cache TTL in seconds. Overrides the global `max_age_days` for this specific extraction. When `0`, caching is completely skipped (no read or write). When `None`, the global TTL applies. |
| `email` | `Optional<EmailConfig>` | `null` | Email extraction configuration (None = use defaults). Currently supports configuring the fallback codepage for MSG files that do not specify one. See `crate.core.config.EmailConfig` for details. |
| `concurrency` | `Optional<ConcurrencyConfig>` | `null` | Concurrency limits for constrained environments (None = use defaults). Controls Rayon thread pool size, ONNX Runtime intra-op threads, and (when `max_concurrent_extractions` is unset) the batch concurrency semaphore. See `crate.core.config.ConcurrencyConfig` for details. |
| `maxArchiveDepth` | `long` | `null` | Maximum recursion depth for archive extraction (default: 3). Set to 0 to disable recursive extraction (legacy behavior). |
| `treeSitter` | `Optional<TreeSitterConfig>` | `null` | Tree-sitter language pack configuration (None = tree-sitter disabled). When set, enables code file extraction using tree-sitter parsers. Controls grammar download behavior and code analysis options. |
| `structuredExtraction` | `Optional<StructuredExtractionConfig>` | `null` | Structured extraction via LLM (None = disabled). When set, the extracted document content is sent to an LLM with the provided JSON schema. The structured response is stored in `ExtractionResult.structured_output`. |

#### Methods

##### defaultOptions()

**Signature:**

```java
public static ExtractionConfig defaultOptions()
```

##### withFileOverrides()

Create a new `ExtractionConfig` by applying per-file overrides from a
`FileExtractionConfig`. Fields that are `Some` in the override replace the
corresponding field in `self`; `null` fields keep the original value.

Batch-level fields (`max_concurrent_extractions`, `use_cache`, `acceleration`,
`security_limits`) are never affected by overrides.

**Signature:**

```java
public ExtractionConfig withFileOverrides(FileExtractionConfig overrides)
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

```java
public ExtractionConfig normalized()
```

##### validate()

Validate the configuration, returning an error if any settings are invalid.

Checks:
- OCR backend name is supported (catches typos early)
- VLM backend config is present when backend is "vlm"
- Pipeline stage backends and VLM configs are valid
- Structured extraction schema and LLM model are non-empty

**Signature:**

```java
public void validate() throws KreuzbergError
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

```java
public boolean needsImageProcessing()
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
| `fileOverrides` | `Optional<FileExtractionConfig>` | `null` | Optional per-file overrides (merged on top of `config`). |

#### Methods

##### file()

Create a file-based extraction request.

**Signature:**

```java
public static ExtractionRequest file(String path, ExtractionConfig config)
```

##### fileWithMime()

Create a file-based extraction request with a MIME type hint.

**Signature:**

```java
public static ExtractionRequest fileWithMime(String path, String mimeHint, ExtractionConfig config)
```

##### bytes()

Create a bytes-based extraction request.

**Signature:**

```java
public static ExtractionRequest bytes(byte[] data, String mimeType, ExtractionConfig config)
```

##### withOverrides()

Set per-file overrides on this request.

**Signature:**

```java
public ExtractionRequest withOverrides(FileExtractionConfig overrides)
```


---

### ExtractionResult

General extraction result used by the core extraction API.

This is the main result type returned by all extraction functions.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `content` | `String` | `null` | The extracted text content |
| `mimeType` | `Str` | `null` | The detected MIME type |
| `metadata` | `Metadata` | `null` | Document metadata |
| `tables` | `List<Table>` | `Collections.emptyList()` | Tables extracted from the document |
| `detectedLanguages` | `Optional<List<String>>` | `Collections.emptyList()` | Detected languages |
| `chunks` | `Optional<List<Chunk>>` | `Collections.emptyList()` | Text chunks when chunking is enabled. When chunking configuration is provided, the content is split into overlapping chunks for efficient processing. Each chunk contains the text, optional embeddings (if enabled), and metadata about its position. |
| `images` | `Optional<List<ExtractedImage>>` | `Collections.emptyList()` | Extracted images from the document. When image extraction is enabled via `ImageExtractionConfig`, this field contains all images found in the document with their raw data and metadata. Each image may optionally contain a nested `ocr_result` if OCR was performed. |
| `pages` | `Optional<List<PageContent>>` | `Collections.emptyList()` | Per-page content when page extraction is enabled. When page extraction is configured, the document is split into per-page content with tables and images mapped to their respective pages. |
| `elements` | `Optional<List<Element>>` | `Collections.emptyList()` | Semantic elements when element-based result format is enabled. When result_format is set to ElementBased, this field contains semantic elements with type classification, unique identifiers, and metadata for Unstructured-compatible element-based processing. |
| `djotContent` | `Optional<DjotContent>` | `null` | Rich Djot content structure (when extracting Djot documents). When extracting Djot documents with structured extraction enabled, this field contains the full semantic structure including: - Block-level elements with nesting - Inline formatting with attributes - Links, images, footnotes - Math expressions - Complete attribute information The `content` field still contains plain text for backward compatibility. Always `None` for non-Djot documents. |
| `ocrElements` | `Optional<List<OcrElement>>` | `Collections.emptyList()` | OCR elements with full spatial and confidence metadata. When OCR is performed with element extraction enabled, this field contains the structured representation of detected text including: - Bounding geometry (rectangles or quadrilaterals) - Confidence scores (detection and recognition) - Rotation information - Hierarchical relationships (Tesseract only) This field preserves all metadata that would otherwise be lost when converting to plain text or markdown output formats. Only populated when `OcrElementConfig.include_elements` is true. |
| `document` | `Optional<DocumentStructure>` | `null` | Structured document tree (when document structure extraction is enabled). When `include_document_structure` is true in `ExtractionConfig`, this field contains the full hierarchical representation of the document including: - Heading-driven section nesting - Table grids with cell-level metadata - Content layer classification (body, header, footer, footnote) - Inline text annotations (formatting, links) - Bounding boxes and page numbers Independent of `result_format` — can be combined with Unified or ElementBased. |
| `qualityScore` | `Optional<double>` | `null` | Document quality score from quality analysis. A value between 0.0 and 1.0 indicating the overall text quality. Previously stored in `metadata.additional["quality_score"]`. |
| `processingWarnings` | `List<ProcessingWarning>` | `Collections.emptyList()` | Non-fatal warnings collected during processing pipeline stages. Captures errors from optional pipeline features (embedding, chunking, language detection, output formatting) that don't prevent extraction but may indicate degraded results. Previously stored as individual keys in `metadata.additional`. |
| `annotations` | `Optional<List<PdfAnnotation>>` | `Collections.emptyList()` | PDF annotations extracted from the document. When annotation extraction is enabled via `PdfConfig.extract_annotations`, this field contains text notes, highlights, links, stamps, and other annotations found in PDF documents. |
| `children` | `Optional<List<ArchiveEntry>>` | `Collections.emptyList()` | Nested extraction results from archive contents. When extracting archives, each processable file inside produces its own full extraction result. Set to `None` for non-archive formats. Use `max_archive_depth` in config to control recursion depth. |
| `uris` | `Optional<List<Uri>>` | `Collections.emptyList()` | URIs/links discovered during document extraction. Contains hyperlinks, image references, citations, email addresses, and other URI-like references found in the document. Always extracted when present in the source document. |
| `structuredOutput` | `Optional<Object>` | `null` | Structured extraction output from LLM-based JSON schema extraction. When `structured_extraction` is configured in `ExtractionConfig`, the extracted document content is sent to a VLM with the provided JSON schema. The response is parsed and stored here as a JSON value matching the schema. |
| `codeIntelligence` | `Optional<ProcessResult>` | `null` | Code intelligence results from tree-sitter analysis. Populated when extracting source code files with the `tree-sitter` feature. Contains metrics, structural analysis, imports/exports, comments, docstrings, symbols, diagnostics, and optionally chunked code segments. |
| `llmUsage` | `Optional<List<LlmUsage>>` | `Collections.emptyList()` | LLM token usage and cost data for all LLM calls made during this extraction. Contains one entry per LLM call. Multiple entries are produced when VLM OCR, structured extraction, and/or LLM embeddings all run during the same extraction. `None` when no LLM was used. |
| `formattedContent` | `Optional<String>` | `null` | Pre-rendered content in the requested output format. Populated during `derive_extraction_result` before tree derivation consumes element data. `apply_output_format` swaps this into `content` at the end of the pipeline, after post-processors have operated on plain text. |
| `ocrInternalDocument` | `Optional<InternalDocument>` | `null` | Structured hOCR document for the OCR+layout pipeline. When tesseract produces hOCR output, the parsed `InternalDocument` carries paragraph structure with bounding boxes and confidence scores. The layout classification step enriches these elements before final rendering. |


---

### ExtractionServiceBuilder

Builder for composing an extraction service with Tower middleware layers.

Layers are applied in the order: Tracing → Metrics → Timeout → ConcurrencyLimit → Service.

#### Methods

##### defaultOptions()

**Signature:**

```java
public static ExtractionServiceBuilder defaultOptions()
```

##### withTimeout()

Add a per-request timeout.

**Signature:**

```java
public ExtractionServiceBuilder withTimeout(Duration duration)
```

##### withConcurrencyLimit()

Limit concurrent in-flight extractions.

**Signature:**

```java
public ExtractionServiceBuilder withConcurrencyLimit(long max)
```

##### withTracing()

Add a tracing span to each extraction request.

**Signature:**

```java
public ExtractionServiceBuilder withTracing()
```

##### withMetrics()

Add metrics recording to each extraction request.

Requires the `otel` feature. This is a no-op when `otel` is not enabled.

**Signature:**

```java
public ExtractionServiceBuilder withMetrics()
```

##### build()

Build the service stack, returning a type-erased cloneable service.

Layer order (outermost to innermost):
`Tracing → Metrics → Timeout → ConcurrencyLimit → ExtractionService`

**Signature:**

```java
public BoxCloneService build()
```


---

### FictionBookExtractor

FictionBook document extractor.

Supports FictionBook 2.0 format with proper section hierarchy and inline formatting.

#### Methods

##### defaultOptions()

**Signature:**

```java
public static FictionBookExtractor defaultOptions()
```

##### name()

**Signature:**

```java
public String name()
```

##### version()

**Signature:**

```java
public String version()
```

##### initialize()

**Signature:**

```java
public void initialize() throws Error
```

##### shutdown()

**Signature:**

```java
public void shutdown() throws Error
```

##### description()

**Signature:**

```java
public String description()
```

##### author()

**Signature:**

```java
public String author()
```

##### extractBytes()

**Signature:**

```java
public InternalDocument extractBytes(byte[] content, String mimeType, ExtractionConfig config) throws Error
```

##### supportedMimeTypes()

**Signature:**

```java
public List<String> supportedMimeTypes()
```

##### priority()

**Signature:**

```java
public int priority()
```


---

### FictionBookMetadata

FictionBook (FB2) metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `genres` | `List<String>` | `Collections.emptyList()` | Genres |
| `sequences` | `List<String>` | `Collections.emptyList()` | Sequences |
| `annotation` | `Optional<String>` | `null` | Annotation |


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

```java
public byte[] deref()
```

##### asRef()

**Signature:**

```java
public byte[] asRef()
```


---

### FileExtractionConfig

Per-file extraction configuration overrides for batch processing.

All fields are `Option<T>` — `null` means "use the batch-level default."
This type is used with `crate.batch_extract_file` and
`crate.batch_extract_bytes` to allow heterogeneous
extraction settings within a single batch.

# Excluded Fields

The following `super.ExtractionConfig` fields are batch-level only and
cannot be overridden per file:
- `max_concurrent_extractions` — controls batch parallelism
- `use_cache` — global caching policy
- `acceleration` — shared ONNX execution provider
- `security_limits` — global archive security policy

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `enableQualityProcessing` | `Optional<boolean>` | `null` | Override quality post-processing for this file. |
| `ocr` | `Optional<OcrConfig>` | `null` | Override OCR configuration for this file (None in the Option = use batch default). |
| `forceOcr` | `Optional<boolean>` | `null` | Override force OCR for this file. |
| `forceOcrPages` | `Optional<List<Long>>` | `Collections.emptyList()` | Override force OCR pages for this file (1-indexed page numbers). |
| `disableOcr` | `Optional<boolean>` | `null` | Override disable OCR for this file. |
| `chunking` | `Optional<ChunkingConfig>` | `null` | Override chunking configuration for this file. |
| `contentFilter` | `Optional<ContentFilterConfig>` | `null` | Override content filtering configuration for this file. |
| `images` | `Optional<ImageExtractionConfig>` | `null` | Override image extraction configuration for this file. |
| `pdfOptions` | `Optional<PdfConfig>` | `null` | Override PDF options for this file. |
| `tokenReduction` | `Optional<TokenReductionConfig>` | `null` | Override token reduction for this file. |
| `languageDetection` | `Optional<LanguageDetectionConfig>` | `null` | Override language detection for this file. |
| `pages` | `Optional<PageConfig>` | `null` | Override page extraction for this file. |
| `postprocessor` | `Optional<PostProcessorConfig>` | `null` | Override post-processor for this file. |
| `htmlOptions` | `Optional<ConversionOptions>` | `null` | Override HTML conversion options for this file. |
| `resultFormat` | `Optional<OutputFormat>` | `OutputFormat.Plain` | Override result format for this file. |
| `outputFormat` | `Optional<OutputFormat>` | `OutputFormat.Plain` | Override output content format for this file. |
| `includeDocumentStructure` | `Optional<boolean>` | `null` | Override document structure output for this file. |
| `layout` | `Optional<LayoutDetectionConfig>` | `null` | Override layout detection for this file. |
| `timeoutSecs` | `Optional<long>` | `null` | Override per-file extraction timeout in seconds. When set, the extraction for this file will be canceled after the specified duration. A timed-out file produces an error result without affecting other files in the batch. |
| `treeSitter` | `Optional<TreeSitterConfig>` | `null` | Override tree-sitter configuration for this file. |
| `structuredExtraction` | `Optional<StructuredExtractionConfig>` | `null` | Override structured extraction configuration for this file. When set, enables LLM-based structured extraction with a JSON schema for this specific file. The extracted content is sent to a VLM/LLM and the response is parsed according to the provided schema. |


---

### FileHeader

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `flags` | `int` | — | Flags |

#### Methods

##### parse()

**Signature:**

```java
public static FileHeader parse(byte[] data) throws Error
```

##### isCompressed()

Whether section streams are zlib/deflate-compressed.

**Signature:**

```java
public boolean isCompressed()
```

##### isEncrypted()

Whether the document is password-encrypted.

**Signature:**

```java
public boolean isEncrypted()
```

##### isDistribute()

Whether the document is a distribution document (text in ViewText/).

**Signature:**

```java
public boolean isDistribute()
```


---

### FontScheme

Font scheme containing major (heading) and minor (body) fonts.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `name` | `String` | `null` | Font scheme name. |
| `majorLatin` | `Optional<String>` | `null` | Major (heading) font - Latin script. |
| `majorEastAsian` | `Optional<String>` | `null` | Major (heading) font - East Asian script. |
| `majorComplexScript` | `Optional<String>` | `null` | Major (heading) font - Complex script. |
| `minorLatin` | `Optional<String>` | `null` | Minor (body) font - Latin script. |
| `minorEastAsian` | `Optional<String>` | `null` | Minor (body) font - East Asian script. |
| `minorComplexScript` | `Optional<String>` | `null` | Minor (body) font - Complex script. |


---

### Footnote

Footnote in Djot.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `label` | `String` | — | Footnote label |
| `content` | `List<FormattedBlock>` | — | Footnote content blocks |


---

### FormattedBlock

Block-level element in a Djot document.

Represents structural elements like headings, paragraphs, lists, code blocks, etc.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `blockType` | `BlockType` | — | Type of block element |
| `level` | `Optional<long>` | `null` | Heading level (1-6) for headings, or nesting level for lists |
| `inlineContent` | `List<InlineElement>` | — | Inline content within the block |
| `attributes` | `Optional<Attributes>` | `null` | Element attributes (classes, IDs, key-value pairs) |
| `language` | `Optional<String>` | `null` | Language identifier for code blocks |
| `code` | `Optional<String>` | `null` | Raw code content for code blocks |
| `children` | `List<FormattedBlock>` | — | Nested blocks for containers (blockquotes, list items, divs) |


---

### GenericCache

#### Methods

##### new()

**Signature:**

```java
public static GenericCache new(String cacheType, String cacheDir, double maxAgeDays, double maxCacheSizeMb, double minFreeSpaceMb) throws Error
```

##### get()

**Signature:**

```java
public Optional<byte[]> get(String cacheKey, String sourceFile, String namespace, long ttlOverrideSecs) throws Error
```

##### getDefault()

Backward-compatible get without namespace/TTL.

**Signature:**

```java
public Optional<byte[]> getDefault(String cacheKey, String sourceFile) throws Error
```

##### set()

**Signature:**

```java
public void set(String cacheKey, byte[] data, String sourceFile, String namespace, long ttlSecs) throws Error
```

##### setDefault()

Backward-compatible set without namespace/TTL.

**Signature:**

```java
public void setDefault(String cacheKey, byte[] data, String sourceFile) throws Error
```

##### isProcessing()

**Signature:**

```java
public boolean isProcessing(String cacheKey) throws Error
```

##### markProcessing()

**Signature:**

```java
public void markProcessing(String cacheKey) throws Error
```

##### markComplete()

**Signature:**

```java
public void markComplete(String cacheKey) throws Error
```

##### clear()

**Signature:**

```java
public UsizeF64 clear() throws Error
```

##### deleteNamespace()

Delete all cache entries under a namespace.

Removes the namespace subdirectory and all its contents.
Returns (files_removed, mb_freed).

**Signature:**

```java
public UsizeF64 deleteNamespace(String namespace) throws Error
```

##### getStats()

**Signature:**

```java
public CacheStats getStats() throws Error
```

##### getStatsFiltered()

Get cache stats, optionally filtered to a specific namespace.

**Signature:**

```java
public CacheStats getStatsFiltered(String namespace) throws Error
```

##### cacheDir()

**Signature:**

```java
public String cacheDir()
```

##### cacheType()

**Signature:**

```java
public String cacheType()
```


---

### GridCell

Individual grid cell with position and span metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `content` | `String` | — | Cell text content. |
| `row` | `int` | — | Zero-indexed row position. |
| `col` | `int` | — | Zero-indexed column position. |
| `rowSpan` | `int` | — | Number of rows this cell spans. |
| `colSpan` | `int` | — | Number of columns this cell spans. |
| `isHeader` | `boolean` | — | Whether this is a header cell. |
| `bbox` | `Optional<BoundingBox>` | `null` | Bounding box for this cell (if available). |


---

### GzipExtractor

Gzip archive extractor.

Decompresses gzip files and extracts text content from the compressed data.

#### Methods

##### defaultOptions()

**Signature:**

```java
public static GzipExtractor defaultOptions()
```

##### name()

**Signature:**

```java
public String name()
```

##### version()

**Signature:**

```java
public String version()
```

##### initialize()

**Signature:**

```java
public void initialize() throws Error
```

##### shutdown()

**Signature:**

```java
public void shutdown() throws Error
```

##### description()

**Signature:**

```java
public String description()
```

##### author()

**Signature:**

```java
public String author()
```

##### extractBytes()

**Signature:**

```java
public InternalDocument extractBytes(byte[] content, String mimeType, ExtractionConfig config) throws Error
```

##### supportedMimeTypes()

**Signature:**

```java
public List<String> supportedMimeTypes()
```

##### priority()

**Signature:**

```java
public int priority()
```

##### asSyncExtractor()

**Signature:**

```java
public Optional<SyncExtractor> asSyncExtractor()
```

##### extractSync()

**Signature:**

```java
public InternalDocument extractSync(byte[] content, String mimeType, ExtractionConfig config) throws Error
```


---

### HeaderFooter

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `paragraphs` | `List<Paragraph>` | `Collections.emptyList()` | Paragraphs |
| `tables` | `List<Table>` | `Collections.emptyList()` | Tables extracted from the document |
| `headerType` | `HeaderFooterType` | `HeaderFooterType.Default` | Header type (header footer type) |


---

### HeaderMetadata

Header/heading element metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `level` | `byte` | — | Header level: 1 (h1) through 6 (h6) |
| `text` | `String` | — | Normalized text content of the header |
| `id` | `Optional<String>` | `null` | HTML id attribute if present |
| `depth` | `long` | — | Document tree depth at the header element |
| `htmlOffset` | `long` | — | Byte offset in original HTML document |


---

### HeadingContext

Heading context for a chunk within a Markdown document.

Contains the heading hierarchy from document root to this chunk's section.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `headings` | `List<HeadingLevel>` | — | The heading hierarchy from document root to this chunk's section. Index 0 is the outermost (h1), last element is the most specific. |


---

### HeadingLevel

A single heading in the hierarchy.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `level` | `byte` | — | Heading depth (1 = h1, 2 = h2, etc.) |
| `text` | `String` | — | The text content of the heading. |


---

### HierarchicalBlock

A text block with hierarchy level assignment.

Represents a block of text with semantic heading information extracted from
font size clustering and hierarchical analysis.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `text` | `String` | — | The text content of this block |
| `fontSize` | `float` | — | The font size of the text in this block |
| `level` | `String` | — | The hierarchy level of this block (H1-H6 or Body) Levels correspond to HTML heading tags: - "h1": Top-level heading - "h2": Secondary heading - "h3": Tertiary heading - "h4": Quaternary heading - "h5": Quinary heading - "h6": Senary heading - "body": Body text (no heading level) |
| `bbox` | `Optional<F32F32F32F32>` | `null` | Bounding box information for the block Contains coordinates as (left, top, right, bottom) in PDF units. |


---

### HierarchyConfig

Hierarchy extraction configuration for PDF text structure analysis.

Enables extraction of document hierarchy levels (H1-H6) based on font size
clustering and semantic analysis. When enabled, hierarchical blocks are
included in page content.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `enabled` | `boolean` | `true` | Enable hierarchy extraction |
| `kClusters` | `long` | `3` | Number of font size clusters to use for hierarchy levels (1-7) Default: 6, which provides H1-H6 heading levels with body text. Larger values create more fine-grained hierarchy levels. |
| `includeBbox` | `boolean` | `true` | Include bounding box information in hierarchy blocks |
| `ocrCoverageThreshold` | `Optional<float>` | `null` | OCR coverage threshold for smart OCR triggering (0.0-1.0) Determines when OCR should be triggered based on text block coverage. OCR is triggered when text blocks cover less than this fraction of the page. Default: 0.5 (trigger OCR if less than 50% of page has text) |

#### Methods

##### defaultOptions()

**Signature:**

```java
public static HierarchyConfig defaultOptions()
```


---

### HocrWord

Represents a word extracted from hOCR (or any source) with position and confidence information.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `text` | `String` | — | Text |
| `left` | `int` | — | Left |
| `top` | `int` | — | Top |
| `width` | `int` | — | Width |
| `height` | `int` | — | Height |
| `confidence` | `double` | — | Confidence |

#### Methods

##### right()

Get the right edge position.

**Signature:**

```java
public int right()
```

##### bottom()

Get the bottom edge position.

**Signature:**

```java
public int bottom()
```

##### yCenter()

Get the vertical center position.

**Signature:**

```java
public double yCenter()
```

##### xCenter()

Get the horizontal center position.

**Signature:**

```java
public double xCenter()
```


---

### HtmlExtractor

HTML document extractor using html-to-markdown.

#### Methods

##### defaultOptions()

**Signature:**

```java
public static HtmlExtractor defaultOptions()
```

##### name()

**Signature:**

```java
public String name()
```

##### version()

**Signature:**

```java
public String version()
```

##### initialize()

**Signature:**

```java
public void initialize() throws Error
```

##### shutdown()

**Signature:**

```java
public void shutdown() throws Error
```

##### extractSync()

**Signature:**

```java
public InternalDocument extractSync(byte[] content, String mimeType, ExtractionConfig config) throws Error
```

##### extractBytes()

**Signature:**

```java
public InternalDocument extractBytes(byte[] content, String mimeType, ExtractionConfig config) throws Error
```

##### supportedMimeTypes()

**Signature:**

```java
public List<String> supportedMimeTypes()
```

##### priority()

**Signature:**

```java
public int priority()
```

##### asSyncExtractor()

**Signature:**

```java
public Optional<SyncExtractor> asSyncExtractor()
```


---

### HtmlMetadata

HTML metadata extracted from HTML documents.

Includes document-level metadata, Open Graph data, Twitter Card metadata,
and extracted structural elements (headers, links, images, structured data).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `title` | `Optional<String>` | `null` | Document title from `<title>` tag |
| `description` | `Optional<String>` | `null` | Document description from `<meta name="description">` tag |
| `keywords` | `List<String>` | `Collections.emptyList()` | Document keywords from `<meta name="keywords">` tag, split on commas |
| `author` | `Optional<String>` | `null` | Document author from `<meta name="author">` tag |
| `canonicalUrl` | `Optional<String>` | `null` | Canonical URL from `<link rel="canonical">` tag |
| `baseHref` | `Optional<String>` | `null` | Base URL from `<base href="">` tag for resolving relative URLs |
| `language` | `Optional<String>` | `null` | Document language from `lang` attribute |
| `textDirection` | `Optional<TextDirection>` | `TextDirection.LeftToRight` | Document text direction from `dir` attribute |
| `openGraph` | `Map<String, String>` | `Collections.emptyMap()` | Open Graph metadata (og:* properties) for social media Keys like "title", "description", "image", "url", etc. |
| `twitterCard` | `Map<String, String>` | `Collections.emptyMap()` | Twitter Card metadata (twitter:* properties) Keys like "card", "site", "creator", "title", "description", "image", etc. |
| `metaTags` | `Map<String, String>` | `Collections.emptyMap()` | Additional meta tags not covered by specific fields Keys are meta name/property attributes, values are content |
| `headers` | `List<HeaderMetadata>` | `Collections.emptyList()` | Extracted header elements with hierarchy |
| `links` | `List<LinkMetadata>` | `Collections.emptyList()` | Extracted hyperlinks with type classification |
| `images` | `List<ImageMetadataType>` | `Collections.emptyList()` | Extracted images with source and dimensions |
| `structuredData` | `List<StructuredData>` | `Collections.emptyList()` | Extracted structured data blocks |

#### Methods

##### isEmpty()

Check if metadata is empty (no meaningful content extracted).

**Signature:**

```java
public boolean isEmpty()
```

##### from()

**Signature:**

```java
public static HtmlMetadata from(HtmlMetadata metadata)
```


---

### HtmlOutputConfig

Configuration for styled HTML output.

When set on `ExtractionConfig.html_output` alongside
`output_format = OutputFormat.Html`, the pipeline builds a
`StyledHtmlRenderer` instead of
the plain comrak-based renderer.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `css` | `Optional<String>` | `null` | Inline CSS string injected into the output after the theme stylesheet. Concatenated after `css_file` content when both are set. |
| `cssFile` | `Optional<String>` | `null` | Path to a CSS file loaded once at renderer construction time. Concatenated before `css` when both are set. |
| `theme` | `HtmlTheme` | `HtmlTheme.Unstyled` | Built-in colour/typography theme. Default: `HtmlTheme.Unstyled`. |
| `classPrefix` | `String` | `null` | CSS class prefix applied to every emitted class name. Default: `"kb-"`. Change this if your host application already uses classes that start with `kb-`. |
| `embedCss` | `boolean` | `true` | When `True` (default), write the resolved CSS into a `<style>` block immediately after the opening `<div class="{prefix}doc">`. Set to `False` to emit only the structural markup and wire up your own stylesheet targeting the `kb-*` class names. |

#### Methods

##### defaultOptions()

**Signature:**

```java
public static HtmlOutputConfig defaultOptions()
```


---

### HwpDocument

An extracted HWP document, consisting of one or more body-text sections.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `sections` | `List<Section>` | `Collections.emptyList()` | All sections from all BodyText/SectionN streams. |

#### Methods

##### extractText()

Concatenate the text of every paragraph in every section, separated by
newlines.

**Signature:**

```java
public String extractText()
```


---

### HwpExtractor

Extractor for Hangul Word Processor (.hwp) files.

Supports HWP 5.0 format, the standard document format in South Korea.

#### Methods

##### defaultOptions()

**Signature:**

```java
public static HwpExtractor defaultOptions()
```

##### name()

**Signature:**

```java
public String name()
```

##### version()

**Signature:**

```java
public String version()
```

##### initialize()

**Signature:**

```java
public void initialize() throws Error
```

##### shutdown()

**Signature:**

```java
public void shutdown() throws Error
```

##### description()

**Signature:**

```java
public String description()
```

##### author()

**Signature:**

```java
public String author()
```

##### extractBytes()

**Signature:**

```java
public InternalDocument extractBytes(byte[] content, String mimeType, ExtractionConfig config) throws Error
```

##### supportedMimeTypes()

**Signature:**

```java
public List<String> supportedMimeTypes()
```

##### priority()

**Signature:**

```java
public int priority()
```


---

### ImageDpiConfig

Image extraction DPI configuration (internal use).

**Note:** This is an internal type used for image preprocessing.
For the main extraction configuration, see `crate.core.config.ExtractionConfig`.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `targetDpi` | `int` | `300` | Target DPI for image normalization |
| `maxImageDimension` | `int` | `4096` | Maximum image dimension (width or height) |
| `autoAdjustDpi` | `boolean` | `true` | Whether to auto-adjust DPI based on content |
| `minDpi` | `int` | `72` | Minimum DPI threshold |
| `maxDpi` | `int` | `600` | Maximum DPI threshold |

#### Methods

##### defaultOptions()

**Signature:**

```java
public static ImageDpiConfig defaultOptions()
```


---

### ImageExtractionConfig

Image extraction configuration.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `extractImages` | `boolean` | `null` | Extract images from documents |
| `targetDpi` | `int` | `null` | Target DPI for image normalization |
| `maxImageDimension` | `int` | `null` | Maximum dimension for images (width or height) |
| `injectPlaceholders` | `boolean` | `null` | Whether to inject image reference placeholders into markdown output. When `True` (default), image references like `![Image 1](embedded:p1_i0)` are appended to the markdown. Set to `False` to extract images as data without polluting the markdown output. |
| `autoAdjustDpi` | `boolean` | `null` | Automatically adjust DPI based on image content |
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

##### defaultOptions()

**Signature:**

```java
public static ImageExtractor defaultOptions()
```

##### name()

**Signature:**

```java
public String name()
```

##### version()

**Signature:**

```java
public String version()
```

##### initialize()

**Signature:**

```java
public void initialize() throws Error
```

##### shutdown()

**Signature:**

```java
public void shutdown() throws Error
```

##### description()

**Signature:**

```java
public String description()
```

##### author()

**Signature:**

```java
public String author()
```

##### extractBytes()

**Signature:**

```java
public InternalDocument extractBytes(byte[] content, String mimeType, ExtractionConfig config) throws Error
```

##### supportedMimeTypes()

**Signature:**

```java
public List<String> supportedMimeTypes()
```

##### priority()

**Signature:**

```java
public int priority()
```


---

### ImageMetadata

Image metadata extracted from image files.

Includes dimensions, format, and EXIF data.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `width` | `int` | — | Image width in pixels |
| `height` | `int` | — | Image height in pixels |
| `format` | `String` | — | Image format (e.g., "PNG", "JPEG", "TIFF") |
| `exif` | `Map<String, String>` | — | EXIF metadata tags |


---

### ImageMetadataType

Image element metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `src` | `String` | — | Image source (URL, data URI, or SVG content) |
| `alt` | `Optional<String>` | `null` | Alternative text from alt attribute |
| `title` | `Optional<String>` | `null` | Title attribute |
| `dimensions` | `Optional<U32U32>` | `null` | Image dimensions as (width, height) if available |
| `imageType` | `ImageType` | — | Image type classification |
| `attributes` | `List<StringString>` | — | Additional attributes as key-value pairs |


---

### ImageOcrResult

Result of OCR extraction from an image with optional page tracking.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `content` | `String` | — | Extracted text content |
| `boundaries` | `Optional<List<PageBoundary>>` | `null` | Character byte boundaries per frame (for multi-frame TIFFs) |
| `pageContents` | `Optional<List<PageContent>>` | `null` | Per-frame content information |


---

### ImagePreprocessingConfig

Image preprocessing configuration for OCR.

These settings control how images are preprocessed before OCR to improve
text recognition quality. Different preprocessing strategies work better
for different document types.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `targetDpi` | `int` | `300` | Target DPI for the image (300 is standard, 600 for small text). |
| `autoRotate` | `boolean` | `true` | Auto-detect and correct image rotation. |
| `deskew` | `boolean` | `true` | Correct skew (tilted images). |
| `denoise` | `boolean` | `false` | Remove noise from the image. |
| `contrastEnhance` | `boolean` | `false` | Enhance contrast for better text visibility. |
| `binarizationMethod` | `String` | `"otsu"` | Binarization method: "otsu", "sauvola", "adaptive". |
| `invertColors` | `boolean` | `false` | Invert colors (white text on black → black on white). |

#### Methods

##### defaultOptions()

**Signature:**

```java
public static ImagePreprocessingConfig defaultOptions()
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
| `scaleFactor` | `double` | — | Scaling factor applied to the image |
| `autoAdjusted` | `boolean` | — | Whether DPI was auto-adjusted based on content |
| `finalDpi` | `int` | — | Final DPI after processing |
| `newDimensions` | `Optional<UsizeUsize>` | `null` | New dimensions after resizing (if resized) |
| `resampleMethod` | `String` | — | Resampling algorithm used ("LANCZOS3", "CATMULLROM", etc.) |
| `dimensionClamped` | `boolean` | — | Whether dimensions were clamped to max_image_dimension |
| `calculatedDpi` | `Optional<int>` | `null` | Calculated optimal DPI (if auto_adjust_dpi enabled) |
| `skippedResize` | `boolean` | — | Whether resize was skipped (dimensions already optimal) |
| `resizeError` | `Optional<String>` | `null` | Error message if resize failed |


---

### InlineElement

Inline element within a block.

Represents text with formatting, links, images, etc.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `elementType` | `InlineType` | — | Type of inline element |
| `content` | `String` | — | Text content |
| `attributes` | `Optional<Attributes>` | `null` | Element attributes |
| `metadata` | `Optional<Map<String, String>>` | `null` | Additional metadata (e.g., href for links, src/alt for images) |


---

### Instant

A platform-aware instant for measuring elapsed time.

On native targets this delegates to `std.time.Instant`.
On `wasm32` targets it is a zero-cost no-op to avoid the `unreachable` trap.

#### Methods

##### now()

Capture the current instant.

**Signature:**

```java
public static Instant now()
```

##### elapsedSecsF64()

Seconds elapsed since this instant was captured (as `f64`).

**Signature:**

```java
public double elapsedSecsF64()
```

##### elapsedMs()

Milliseconds elapsed since this instant was captured (as `f64`).

**Signature:**

```java
public double elapsedMs()
```

##### elapsedMillis()

Milliseconds elapsed as `u128` (mirrors `Duration.as_millis`).

**Signature:**

```java
public U128 elapsedMillis()
```


---

### InternalDocument

The internal flat document representation.

All extractors output this structure. It is converted to the public
`ExtractionResult` and
`DocumentStructure` in the pipeline.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `elements` | `List<InternalElement>` | — | All elements in reading order. Append-only during extraction. |
| `relationships` | `List<Relationship>` | — | Relationships between elements (source index → target). Stored separately from elements for cache-friendly iteration. |
| `sourceFormat` | `Str` | — | Source format identifier (e.g., "pdf", "docx", "html", "markdown"). |
| `metadata` | `Metadata` | — | Document-level metadata (title, author, dates, etc.). |
| `images` | `List<ExtractedImage>` | — | Extracted images (binary data). Referenced by index from `ElementKind.Image`. |
| `tables` | `List<Table>` | — | Extracted tables (structured data). Referenced by index from `ElementKind.Table`. |
| `uris` | `List<Uri>` | — | URIs/links discovered during extraction (hyperlinks, image refs, citations, etc.). |
| `children` | `Optional<List<ArchiveEntry>>` | `null` | Archive children: fully-extracted results for files within an archive. Only populated by archive extractors (ZIP, TAR, 7z, GZIP) when recursive extraction is enabled. Each entry contains the full `ExtractionResult` for a child file that was extracted through the public pipeline. |
| `mimeType` | `Str` | — | MIME type of the source document (e.g., "application/pdf", "text/html"). |
| `processingWarnings` | `List<ProcessingWarning>` | — | Non-fatal warnings collected during extraction. |
| `annotations` | `Optional<List<PdfAnnotation>>` | `null` | PDF annotations (links, highlights, notes). |
| `prebuiltPages` | `Optional<List<PageContent>>` | `null` | Pre-built per-page content (set by extractors that track page boundaries natively). When populated, `derive_extraction_result` uses this directly instead of attempting to reconstruct pages from element-level page numbers. |
| `preRenderedContent` | `Optional<String>` | `null` | Pre-rendered formatted content produced by the extractor itself. When an extractor has direct access to high-quality formatted output (e.g., html-to-markdown produces GFM markdown), it can store that here to bypass the lossy InternalDocument → renderer round-trip. `derive_extraction_result` will use this directly when the requested output format matches `metadata.output_format`. |

#### Methods

##### pushElement()

Push an element and return its index.

**Signature:**

```java
public int pushElement(InternalElement element)
```

##### pushRelationship()

Push a relationship.

**Signature:**

```java
public void pushRelationship(Relationship relationship)
```

##### pushTable()

Push a table and return its index (for use in `ElementKind.Table`).

**Signature:**

```java
public int pushTable(Table table)
```

##### pushImage()

Push an image and return its index (for use in `ElementKind.Image`).

**Signature:**

```java
public int pushImage(ExtractedImage image)
```

##### pushUri()

Push a URI discovered during extraction.
Silently drops URIs beyond `MAX_URIS` to prevent unbounded memory growth.

**Signature:**

```java
public void pushUri(Uri uri)
```

##### content()

Concatenate all element text into a single string, separated by newlines.

**Signature:**

```java
public String content()
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

```java
public void sourceFormat(Str format)
```

##### setMetadata()

Set document-level metadata.

**Signature:**

```java
public void setMetadata(Metadata metadata)
```

##### setMimeType()

Set the MIME type of the source document.

**Signature:**

```java
public void setMimeType(Str mimeType)
```

##### addWarning()

Add a non-fatal processing warning.

**Signature:**

```java
public void addWarning(ProcessingWarning warning)
```

##### setPdfAnnotations()

Set document-level PDF annotations (links, highlights, notes).

**Signature:**

```java
public void setPdfAnnotations(List<PdfAnnotation> annotations)
```

##### pushUri()

Push a URI discovered during extraction.

**Signature:**

```java
public void pushUri(Uri uri)
```

##### build()

Consume the builder and return the constructed `InternalDocument`.

**Signature:**

```java
public InternalDocument build()
```

##### pushHeading()

Push a heading element.

Auto-sets depth from the heading level and generates an anchor slug
from the heading text.

**Signature:**

```java
public int pushHeading(byte level, String text, int page, BoundingBox bbox)
```

##### pushParagraph()

Push a paragraph element.

**Signature:**

```java
public int pushParagraph(String text, List<TextAnnotation> annotations, int page, BoundingBox bbox)
```

##### pushList()

Push a `ListStart` marker and increment depth.

**Signature:**

```java
public void pushList(boolean ordered)
```

##### endList()

Push a `ListEnd` marker and decrement depth.

**Signature:**

```java
public void endList()
```

##### pushListItem()

Push a list item element at the current depth.

**Signature:**

```java
public int pushListItem(String text, boolean ordered, List<TextAnnotation> annotations, int page, BoundingBox bbox)
```

##### pushTable()

Push a table element. The table data is stored separately in
`InternalDocument.tables` and referenced by index.

**Signature:**

```java
public int pushTable(Table table, int page, BoundingBox bbox)
```

##### pushTableFromCells()

Push a table element from a 2D cell grid, building a `Table` struct automatically.

**Signature:**

```java
public int pushTableFromCells(List<List<String>> cells, int page, BoundingBox bbox)
```

##### pushImage()

Push an image element. The image data is stored separately in
`InternalDocument.images` and referenced by index.

**Signature:**

```java
public int pushImage(String description, ExtractedImage image, int page, BoundingBox bbox)
```

##### pushCode()

Push a code block element. Language is stored in attributes.

**Signature:**

```java
public int pushCode(String text, String language, int page, BoundingBox bbox)
```

##### pushFormula()

Push a math formula element.

**Signature:**

```java
public int pushFormula(String text, int page, BoundingBox bbox)
```

##### pushFootnoteRef()

Push a footnote reference marker.

Creates a `FootnoteRef` element with `anchor = key` and also records
a `Relationship` with `RelationshipTarget.Key(key)` so the derivation
step can resolve it to the definition.

**Signature:**

```java
public int pushFootnoteRef(String marker, String key, int page)
```

##### pushFootnoteDefinition()

Push a footnote definition element with `anchor = key`.

**Signature:**

```java
public int pushFootnoteDefinition(String text, String key, int page)
```

##### pushCitation()

Push a citation / bibliographic reference element.

**Signature:**

```java
public int pushCitation(String text, String key, int page)
```

##### pushQuoteStart()

Push a `QuoteStart` marker and increment depth.

**Signature:**

```java
public void pushQuoteStart()
```

##### pushQuoteEnd()

Push a `QuoteEnd` marker and decrement depth.

**Signature:**

```java
public void pushQuoteEnd()
```

##### pushPageBreak()

Push a page break marker at depth 0.

**Signature:**

```java
public void pushPageBreak()
```

##### pushSlide()

Push a slide element.

**Signature:**

```java
public int pushSlide(int number, String title, int page)
```

##### pushAdmonition()

Push an admonition / callout element (note, warning, tip, etc.).
Kind and optional title are stored in attributes.

**Signature:**

```java
public int pushAdmonition(String kind, String title, int page)
```

##### pushRawBlock()

Push a raw block preserved verbatim. Format is stored in attributes.

**Signature:**

```java
public int pushRawBlock(String format, String content, int page)
```

##### pushMetadataBlock()

Push a structured metadata block (frontmatter, email headers).
Entries are stored in attributes.

**Signature:**

```java
public int pushMetadataBlock(List<StringString> entries, int page)
```

##### pushTitle()

Push a title element.

**Signature:**

```java
public int pushTitle(String text, int page, BoundingBox bbox)
```

##### pushDefinitionTerm()

Push a definition term element.

**Signature:**

```java
public int pushDefinitionTerm(String text, int page)
```

##### pushDefinitionDescription()

Push a definition description element.

**Signature:**

```java
public int pushDefinitionDescription(String text, int page)
```

##### pushOcrText()

Push an OCR text element with OCR-specific fields populated.

**Signature:**

```java
public int pushOcrText(String text, OcrElementLevel level, OcrBoundingGeometry geometry, OcrConfidence confidence, OcrRotation rotation, int page, BoundingBox bbox)
```

##### pushGroupStart()

Push a `GroupStart` marker and increment depth.

**Signature:**

```java
public void pushGroupStart(String label, int page)
```

##### pushGroupEnd()

Push a `GroupEnd` marker and decrement depth.

**Signature:**

```java
public void pushGroupEnd()
```

##### pushRelationship()

Push a relationship between two elements.

**Signature:**

```java
public void pushRelationship(int source, RelationshipTarget target, RelationshipKind kind)
```

##### setAnchor()

Set the anchor on an already-pushed element.

**Signature:**

```java
public void setAnchor(int index, String anchor)
```

##### setLayer()

Set the content layer on an already-pushed element.

**Signature:**

```java
public void setLayer(int index, ContentLayer layer)
```

##### setAttributes()

Set attributes on an already-pushed element.

**Signature:**

```java
public void setAttributes(int index, AHashMap attributes)
```

##### setAnnotations()

Set annotations on an already-pushed element.

**Signature:**

```java
public void setAnnotations(int index, List<TextAnnotation> annotations)
```

##### setText()

Set the text content of an already-pushed element.

**Signature:**

```java
public void setText(int index, String text)
```

##### pushElement()

Push a pre-constructed `InternalElement` directly.

Useful when the caller needs to construct an element with fields
that the builder's convenience methods don't cover (e.g. an image
element without `ExtractedImage` data).

**Signature:**

```java
public int pushElement(InternalElement element)
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
| `text` | `String` | — | Primary text content. Empty for non-text elements (images, page breaks). |
| `depth` | `short` | — | Nesting depth (0 = root level). Extractors set this based on heading level, list indent, blockquote depth, etc. The tree derivation step uses depth changes to reconstruct parent-child relationships. |
| `page` | `Optional<int>` | `null` | Page number (1-indexed). `None` for non-paginated formats. |
| `bbox` | `Optional<BoundingBox>` | `null` | Bounding box in document coordinates. |
| `layer` | `ContentLayer` | — | Content layer classification (Body, Header, Footer, Footnote). |
| `annotations` | `List<TextAnnotation>` | — | Inline annotations (formatting, links) on this element's text content. Byte-range based, reuses the existing `TextAnnotation` type. |
| `attributes` | `Optional<AHashMap>` | `null` | Format-specific key-value attributes. Used for CSS classes, LaTeX env names, slide layout names, etc. |
| `anchor` | `Optional<String>` | `null` | Optional anchor/key for this element. Used by the relationship resolver to match references to targets. Examples: heading slug `"introduction"`, footnote label `"fn1"`, citation key `"smith2024"`, figure label `"fig:diagram"`. |
| `ocrGeometry` | `Optional<OcrBoundingGeometry>` | `null` | OCR bounding geometry (rectangle or quadrilateral). |
| `ocrConfidence` | `Optional<OcrConfidence>` | `null` | OCR confidence scores (detection + recognition). |
| `ocrRotation` | `Optional<OcrRotation>` | `null` | OCR rotation metadata. |

#### Methods

##### text()

Create a simple text element with minimal fields.

**Signature:**

```java
public static InternalElement text(ElementKind kind, String text, short depth)
```

##### withPage()

Set the page number.

**Signature:**

```java
public InternalElement withPage(int page)
```

##### withBbox()

Set the bounding box.

**Signature:**

```java
public InternalElement withBbox(BoundingBox bbox)
```

##### withLayer()

Set the content layer.

**Signature:**

```java
public InternalElement withLayer(ContentLayer layer)
```

##### withAnchor()

Set the anchor key.

**Signature:**

```java
public InternalElement withAnchor(String anchor)
```

##### withAnnotations()

Set annotations.

**Signature:**

```java
public InternalElement withAnnotations(List<TextAnnotation> annotations)
```

##### withAttributes()

Set attributes.

**Signature:**

```java
public InternalElement withAttributes(AHashMap attributes)
```

##### withIndex()

Regenerate the ID with the correct index (call after pushing to the document).

**Signature:**

```java
public InternalElement withIndex(int index)
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

```java
public static InternalElementId generate(String kindDiscriminant, String text, int page, int index)
```

##### asStr()

Get the ID as a string slice.

**Signature:**

```java
public String asStr()
```

##### fmt()

**Signature:**

```java
public Unknown fmt(Formatter f)
```

##### asRef()

**Signature:**

```java
public String asRef()
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

```java
public void checkIteration() throws SecurityError
```

##### currentCount()

Get current iteration count.

**Signature:**

```java
public long currentCount()
```


---

### JatsExtractor

JATS document extractor.

Supports JATS (Journal Article Tag Suite) XML documents in various versions,
handling both the full article structure and minimal JATS subsets.

#### Methods

##### defaultOptions()

**Signature:**

```java
public static JatsExtractor defaultOptions()
```

##### name()

**Signature:**

```java
public String name()
```

##### version()

**Signature:**

```java
public String version()
```

##### initialize()

**Signature:**

```java
public void initialize() throws Error
```

##### shutdown()

**Signature:**

```java
public void shutdown() throws Error
```

##### extractBytes()

**Signature:**

```java
public InternalDocument extractBytes(byte[] content, String mimeType, ExtractionConfig config) throws Error
```

##### supportedMimeTypes()

**Signature:**

```java
public List<String> supportedMimeTypes()
```

##### priority()

**Signature:**

```java
public int priority()
```


---

### JatsMetadata

JATS (Journal Article Tag Suite) metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `copyright` | `Optional<String>` | `null` | Copyright |
| `license` | `Optional<String>` | `null` | License |
| `historyDates` | `Map<String, String>` | `Collections.emptyMap()` | History dates |
| `contributorRoles` | `List<ContributorRole>` | `Collections.emptyList()` | Contributor roles |


---

### JsonExtractionConfig

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `extractSchema` | `boolean` | `false` | Extract schema |
| `maxDepth` | `long` | `20` | Maximum depth |
| `arrayItemLimit` | `long` | `500` | Array item limit |
| `includeTypeInfo` | `boolean` | `false` | Include type info |
| `flattenNestedObjects` | `boolean` | `true` | Flatten nested objects |
| `customTextFieldPatterns` | `List<String>` | `Collections.emptyList()` | Custom text field patterns |

#### Methods

##### defaultOptions()

**Signature:**

```java
public static JsonExtractionConfig defaultOptions()
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

##### defaultOptions()

**Signature:**

```java
public static JupyterExtractor defaultOptions()
```

##### name()

**Signature:**

```java
public String name()
```

##### version()

**Signature:**

```java
public String version()
```

##### initialize()

**Signature:**

```java
public void initialize() throws Error
```

##### shutdown()

**Signature:**

```java
public void shutdown() throws Error
```

##### description()

**Signature:**

```java
public String description()
```

##### author()

**Signature:**

```java
public String author()
```

##### extractBytes()

**Signature:**

```java
public InternalDocument extractBytes(byte[] content, String mimeType, ExtractionConfig config) throws Error
```

##### supportedMimeTypes()

**Signature:**

```java
public List<String> supportedMimeTypes()
```

##### priority()

**Signature:**

```java
public int priority()
```


---

### KeynoteExtractor

Apple Keynote presentation extractor.

Supports `.key` files (modern iWork format, 2013+).

Extracts slide text and speaker notes from the IWA container:
ZIP → Snappy → protobuf text fields.

#### Methods

##### defaultOptions()

**Signature:**

```java
public static KeynoteExtractor defaultOptions()
```

##### name()

**Signature:**

```java
public String name()
```

##### version()

**Signature:**

```java
public String version()
```

##### initialize()

**Signature:**

```java
public void initialize() throws Error
```

##### shutdown()

**Signature:**

```java
public void shutdown() throws Error
```

##### description()

**Signature:**

```java
public String description()
```

##### author()

**Signature:**

```java
public String author()
```

##### extractBytes()

**Signature:**

```java
public InternalDocument extractBytes(byte[] content, String mimeType, ExtractionConfig config) throws Error
```

##### supportedMimeTypes()

**Signature:**

```java
public List<String> supportedMimeTypes()
```

##### priority()

**Signature:**

```java
public int priority()
```


---

### Keyword

Extracted keyword with metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `text` | `String` | — | The keyword text. |
| `score` | `float` | — | Relevance score (higher is better, algorithm-specific range). |
| `algorithm` | `KeywordAlgorithm` | — | Algorithm that extracted this keyword. |
| `positions` | `Optional<List<Long>>` | `null` | Optional positions where keyword appears in text (character offsets). |

#### Methods

##### withPositions()

Create a new keyword with positions.

**Signature:**

```java
public static Keyword withPositions(String text, float score, KeywordAlgorithm algorithm, List<Long> positions)
```


---

### KeywordConfig

Keyword extraction configuration.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `algorithm` | `KeywordAlgorithm` | `KeywordAlgorithm.Yake` | Algorithm to use for extraction. |
| `maxKeywords` | `long` | `10` | Maximum number of keywords to extract (default: 10). |
| `minScore` | `float` | `0` | Minimum score threshold (0.0-1.0, default: 0.0). Keywords with scores below this threshold are filtered out. Note: Score ranges differ between algorithms. |
| `ngramRange` | `UsizeUsize` | `null` | N-gram range for keyword extraction (min, max). (1, 1) = unigrams only (1, 2) = unigrams and bigrams (1, 3) = unigrams, bigrams, and trigrams (default) |
| `language` | `Optional<String>` | `null` | Language code for stopword filtering (e.g., "en", "de", "fr"). If None, no stopword filtering is applied. |
| `yakeParams` | `Optional<YakeParams>` | `null` | YAKE-specific tuning parameters. |
| `rakeParams` | `Optional<RakeParams>` | `null` | RAKE-specific tuning parameters. |

#### Methods

##### defaultOptions()

**Signature:**

```java
public static KeywordConfig defaultOptions()
```

##### withMaxKeywords()

Set maximum number of keywords to extract.

**Signature:**

```java
public KeywordConfig withMaxKeywords(long max)
```

##### withMinScore()

Set minimum score threshold.

**Signature:**

```java
public KeywordConfig withMinScore(float score)
```

##### withNgramRange()

Set n-gram range.

**Signature:**

```java
public KeywordConfig withNgramRange(long min, long max)
```

##### withLanguage()

Set language for stopword filtering.

**Signature:**

```java
public KeywordConfig withLanguage(String lang)
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

```java
public String name()
```

##### version()

**Signature:**

```java
public String version()
```

##### initialize()

**Signature:**

```java
public void initialize() throws Error
```

##### shutdown()

**Signature:**

```java
public void shutdown() throws Error
```

##### process()

**Signature:**

```java
public void process(ExtractionResult result, ExtractionConfig config) throws Error
```

##### processingStage()

**Signature:**

```java
public ProcessingStage processingStage()
```

##### shouldProcess()

**Signature:**

```java
public boolean shouldProcess(ExtractionResult result, ExtractionConfig config)
```

##### estimatedDurationMs()

**Signature:**

```java
public long estimatedDurationMs(ExtractionResult result)
```


---

### LanguageDetectionConfig

Language detection configuration.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `enabled` | `boolean` | — | Enable language detection |
| `minConfidence` | `double` | — | Minimum confidence threshold (0.0-1.0) |
| `detectMultiple` | `boolean` | — | Detect multiple languages in the document |


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

```java
public String name()
```

##### version()

**Signature:**

```java
public String version()
```

##### initialize()

**Signature:**

```java
public void initialize() throws Error
```

##### shutdown()

**Signature:**

```java
public void shutdown() throws Error
```

##### process()

**Signature:**

```java
public void process(ExtractionResult result, ExtractionConfig config) throws Error
```

##### processingStage()

**Signature:**

```java
public ProcessingStage processingStage()
```

##### shouldProcess()

**Signature:**

```java
public boolean shouldProcess(ExtractionResult result, ExtractionConfig config)
```

##### estimatedDurationMs()

**Signature:**

```java
public long estimatedDurationMs(ExtractionResult result)
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

```java
public static LanguageRegistry global()
```

##### getSupportedLanguages()

Get supported languages for a specific OCR backend.

**Returns:**

`Some(&[String])` if the backend is registered, `null` otherwise.

**Signature:**

```java
public Optional<List<String>> getSupportedLanguages(String backend)
```

##### isLanguageSupported()

Check if a language is supported by a specific backend.

**Returns:**

`true` if the language is supported, `false` otherwise.

**Signature:**

```java
public boolean isLanguageSupported(String backend, String language)
```

##### getBackends()

Get all registered backend names.

**Returns:**

A vector of backend names in the registry.

**Signature:**

```java
public List<String> getBackends()
```

##### getLanguageCount()

Get language count for a specific backend.

**Returns:**

Number of supported languages for the backend, or 0 if backend not found.

**Signature:**

```java
public long getLanguageCount(String backend)
```

##### defaultOptions()

**Signature:**

```java
public static LanguageRegistry defaultOptions()
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

```java
public static InternalDocument buildInternalDocument(String source, boolean injectPlaceholders)
```

##### defaultOptions()

**Signature:**

```java
public static LatexExtractor defaultOptions()
```

##### name()

**Signature:**

```java
public String name()
```

##### version()

**Signature:**

```java
public String version()
```

##### initialize()

**Signature:**

```java
public void initialize() throws Error
```

##### shutdown()

**Signature:**

```java
public void shutdown() throws Error
```

##### description()

**Signature:**

```java
public String description()
```

##### author()

**Signature:**

```java
public String author()
```

##### extractBytes()

**Signature:**

```java
public InternalDocument extractBytes(byte[] content, String mimeType, ExtractionConfig config) throws Error
```

##### extractFile()

**Signature:**

```java
public InternalDocument extractFile(String path, String mimeType, ExtractionConfig config) throws Error
```

##### supportedMimeTypes()

**Signature:**

```java
public List<String> supportedMimeTypes()
```

##### priority()

**Signature:**

```java
public int priority()
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

```java
public static void sortByConfidenceDesc(List<LayoutDetection> detections)
```

##### fmt()

**Signature:**

```java
public Unknown fmt(Formatter f)
```


---

### LayoutDetectionConfig

Layout detection configuration.

Controls layout detection behavior in the extraction pipeline.
When set on `ExtractionConfig`, layout detection
is enabled for PDF extraction.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `confidenceThreshold` | `Optional<float>` | `null` | Confidence threshold override (None = use model default). |
| `applyHeuristics` | `boolean` | `true` | Whether to apply postprocessing heuristics (default: true). |
| `tableModel` | `TableModel` | `TableModel.Tatr` | Table structure recognition model. Controls which model is used for table cell detection within layout-detected table regions. Defaults to `TableModel.Tatr`. |

#### Methods

##### defaultOptions()

**Signature:**

```java
public static LayoutDetectionConfig defaultOptions()
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

```java
public static LayoutEngine fromConfig(LayoutEngineConfig config) throws LayoutError
```

##### detect()

Run layout detection on an image.

Returns a `DetectionResult` with bounding boxes, classes, and confidence scores.
If `apply_heuristics` is enabled in config, postprocessing is applied automatically.

**Signature:**

```java
public DetectionResult detect(RgbImage img) throws LayoutError
```

##### detectTimed()

Run layout detection on an image and return granular timing data.

Identical to `detect` but also returns a `DetectTimings` breakdown.
Use this when you need per-step profiling (preprocess / onnx / postprocess).

**Signature:**

```java
public DetectionResultDetectTimings detectTimed(RgbImage img) throws LayoutError
```

##### detectBatch()

Run layout detection on a batch of images in a single model call.

Returns one `(DetectionResult, DetectTimings)` tuple per input image.
Postprocessing heuristics are applied per image when enabled in config.

Timing note: `preprocess_ms` and `onnx_ms` in each `DetectTimings` are the
amortized per-image share of the batch operation (total / N), not independent
per-image measurements.

**Signature:**

```java
public List<DetectionResultDetectTimings> detectBatch(List<RgbImage> images) throws LayoutError
```

##### modelName()

Get the model name.

**Signature:**

```java
public String modelName()
```

##### config()

Return a reference to the engine's configuration.

Used by callers (e.g. parallel layout runners) that need to create
additional engines with identical settings.

**Signature:**

```java
public LayoutEngineConfig config()
```


---

### LayoutEngineConfig

Full configuration for the layout engine.

Provides fine-grained control over model selection, thresholds, and
postprocessing.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `backend` | `ModelBackend` | `ModelBackend.RtDetr` | Which model backend to use. |
| `confidenceThreshold` | `Optional<float>` | `null` | Confidence threshold override (None = use model default). |
| `applyHeuristics` | `boolean` | `true` | Whether to apply postprocessing heuristics. |
| `cacheDir` | `Optional<String>` | `null` | Custom cache directory for model files (None = default). |

#### Methods

##### defaultOptions()

**Signature:**

```java
public static LayoutEngineConfig defaultOptions()
```


---

### LayoutModel

Common interface for all layout detection model backends.

#### Methods

##### detect()

Run layout detection on an image using the default confidence threshold.

**Signature:**

```java
public List<LayoutDetection> detect(RgbImage img) throws LayoutError
```

##### detectWithThreshold()

Run layout detection with a custom confidence threshold.

**Signature:**

```java
public List<LayoutDetection> detectWithThreshold(RgbImage img, float threshold) throws LayoutError
```

##### detectBatch()

Run layout detection on a batch of images in a single model call.

Returns one `Vec<LayoutDetection>` per input image (same order).
`threshold` overrides the model's default confidence cutoff when `Some`.

The default implementation is a sequential fallback: models that support
true batched inference (e.g. `rtdetr.RtDetrModel`) override this.

**Signature:**

```java
public List<List<LayoutDetection>> detectBatch(List<RgbImage> images, float threshold) throws LayoutError
```

##### name()

Human-readable model name.

**Signature:**

```java
public String name()
```


---

### LayoutTimingReport

Timing breakdown for the entire layout detection run.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `totalMs` | `double` | — | Total ms |
| `perPage` | `List<PageTiming>` | — | Per page |

#### Methods

##### avgRenderMs()

**Signature:**

```java
public double avgRenderMs()
```

##### avgInferenceMs()

**Signature:**

```java
public double avgInferenceMs()
```

##### avgPreprocessMs()

**Signature:**

```java
public double avgPreprocessMs()
```

##### avgOnnxMs()

**Signature:**

```java
public double avgOnnxMs()
```

##### avgPostprocessMs()

**Signature:**

```java
public double avgPostprocessMs()
```

##### totalInferenceMs()

**Signature:**

```java
public double totalInferenceMs()
```

##### totalRenderMs()

**Signature:**

```java
public double totalRenderMs()
```

##### totalPreprocessMs()

**Signature:**

```java
public double totalPreprocessMs()
```

##### totalOnnxMs()

**Signature:**

```java
public double totalOnnxMs()
```

##### totalPostprocessMs()

**Signature:**

```java
public double totalPostprocessMs()
```


---

### LinkMetadata

Link element metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `href` | `String` | — | The href URL value |
| `text` | `String` | — | Link text content (normalized) |
| `title` | `Optional<String>` | `null` | Optional title attribute |
| `linkType` | `LinkType` | — | Link type classification |
| `rel` | `List<String>` | — | Rel attribute values |
| `attributes` | `List<StringString>` | — | Additional attributes as key-value pairs |


---

### LlmConfig

Configuration for an LLM provider/model via liter-llm.

Each feature (VLM OCR, VLM embeddings, structured extraction) carries
its own `LlmConfig`, allowing different providers per feature.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `model` | `String` | — | Provider/model string using liter-llm routing format. Examples: `"openai/gpt-4o"`, `"anthropic/claude-sonnet-4-20250514"`, `"groq/llama-3.1-70b-versatile"`. |
| `apiKey` | `Optional<String>` | `null` | API key for the provider. When `None`, liter-llm falls back to the provider's standard environment variable (e.g., `OPENAI_API_KEY`). |
| `baseUrl` | `Optional<String>` | `null` | Custom base URL override for the provider endpoint. |
| `timeoutSecs` | `Optional<long>` | `null` | Request timeout in seconds (default: 60). |
| `maxRetries` | `Optional<int>` | `null` | Maximum retry attempts (default: 3). |
| `temperature` | `Optional<double>` | `null` | Sampling temperature for generation tasks. |
| `maxTokens` | `Optional<long>` | `null` | Maximum tokens to generate. |


---

### LlmUsage

Token usage and cost data for a single LLM call made during extraction.

Populated when VLM OCR, structured extraction, or LLM-based embeddings
are used. Multiple entries may be present when multiple LLM calls occur
within one extraction (e.g. VLM OCR + structured extraction).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `model` | `String` | `null` | The LLM model identifier (e.g. "openai/gpt-4o", "anthropic/claude-sonnet-4-20250514"). |
| `source` | `String` | `null` | The pipeline stage that triggered this LLM call (e.g. "vlm_ocr", "structured_extraction", "embeddings"). |
| `inputTokens` | `Optional<long>` | `null` | Number of input/prompt tokens consumed. |
| `outputTokens` | `Optional<long>` | `null` | Number of output/completion tokens generated. |
| `totalTokens` | `Optional<long>` | `null` | Total tokens (input + output). |
| `estimatedCost` | `Optional<double>` | `null` | Estimated cost in USD based on the provider's published pricing. |
| `finishReason` | `Optional<String>` | `null` | Why the model stopped generating (e.g. "stop", "length", "content_filter"). |


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

```java
public static InternalDocument buildInternalDocument(List<Event> events, Value yaml)
```

##### defaultOptions()

**Signature:**

```java
public static MarkdownExtractor defaultOptions()
```

##### name()

**Signature:**

```java
public String name()
```

##### version()

**Signature:**

```java
public String version()
```

##### initialize()

**Signature:**

```java
public void initialize() throws Error
```

##### shutdown()

**Signature:**

```java
public void shutdown() throws Error
```

##### description()

**Signature:**

```java
public String description()
```

##### author()

**Signature:**

```java
public String author()
```

##### extractBytes()

**Signature:**

```java
public InternalDocument extractBytes(byte[] content, String mimeType, ExtractionConfig config) throws Error
```

##### extractFile()

**Signature:**

```java
public InternalDocument extractFile(String path, String mimeType, ExtractionConfig config) throws Error
```

##### supportedMimeTypes()

**Signature:**

```java
public List<String> supportedMimeTypes()
```

##### priority()

**Signature:**

```java
public int priority()
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

```java
public static InternalDocument buildInternalDocument(List<Event> events, Value yaml, List<String> rawJsxBlocks)
```

##### defaultOptions()

**Signature:**

```java
public static MdxExtractor defaultOptions()
```

##### name()

**Signature:**

```java
public String name()
```

##### version()

**Signature:**

```java
public String version()
```

##### initialize()

**Signature:**

```java
public void initialize() throws Error
```

##### shutdown()

**Signature:**

```java
public void shutdown() throws Error
```

##### description()

**Signature:**

```java
public String description()
```

##### author()

**Signature:**

```java
public String author()
```

##### extractBytes()

**Signature:**

```java
public InternalDocument extractBytes(byte[] content, String mimeType, ExtractionConfig config) throws Error
```

##### extractFile()

**Signature:**

```java
public InternalDocument extractFile(String path, String mimeType, ExtractionConfig config) throws Error
```

##### supportedMimeTypes()

**Signature:**

```java
public List<String> supportedMimeTypes()
```

##### priority()

**Signature:**

```java
public int priority()
```


---

### Metadata

Extraction result metadata.

Contains common fields applicable to all formats, format-specific metadata
via a discriminated union, and additional custom fields from postprocessors.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `title` | `Optional<String>` | `null` | Document title |
| `subject` | `Optional<String>` | `null` | Document subject or description |
| `authors` | `Optional<List<String>>` | `Collections.emptyList()` | Primary author(s) - always Vec for consistency |
| `keywords` | `Optional<List<String>>` | `Collections.emptyList()` | Keywords/tags - always Vec for consistency |
| `language` | `Optional<String>` | `null` | Primary language (ISO 639 code) |
| `createdAt` | `Optional<String>` | `null` | Creation timestamp (ISO 8601 format) |
| `modifiedAt` | `Optional<String>` | `null` | Last modification timestamp (ISO 8601 format) |
| `createdBy` | `Optional<String>` | `null` | User who created the document |
| `modifiedBy` | `Optional<String>` | `null` | User who last modified the document |
| `pages` | `Optional<PageStructure>` | `null` | Page/slide/sheet structure with boundaries |
| `format` | `Optional<FormatMetadata>` | `FormatMetadata.Pdf` | Format-specific metadata (discriminated union) Contains detailed metadata specific to the document format. Serializes with a `format_type` discriminator field. |
| `imagePreprocessing` | `Optional<ImagePreprocessingMetadata>` | `null` | Image preprocessing metadata (when OCR preprocessing was applied) |
| `jsonSchema` | `Optional<Object>` | `null` | JSON schema (for structured data extraction) |
| `error` | `Optional<ErrorMetadata>` | `null` | Error metadata (for batch operations) |
| `extractionDurationMs` | `Optional<long>` | `null` | Extraction duration in milliseconds (for benchmarking). This field is populated by batch extraction to provide per-file timing information. It's `None` for single-file extraction (which uses external timing). |
| `category` | `Optional<String>` | `null` | Document category (from frontmatter or classification). |
| `tags` | `Optional<List<String>>` | `Collections.emptyList()` | Document tags (from frontmatter). |
| `documentVersion` | `Optional<String>` | `null` | Document version string (from frontmatter). |
| `abstractText` | `Optional<String>` | `null` | Abstract or summary text (from frontmatter). |
| `outputFormat` | `Optional<String>` | `null` | Output format identifier (e.g., "markdown", "html", "text"). Set by the output format pipeline stage when format conversion is applied. Previously stored in `metadata.additional["output_format"]`. |
| `additional` | `AHashMap` | `null` | Additional custom fields from postprocessors. **Deprecated**: Prefer using typed fields on `ExtractionResult` and `Metadata` instead of inserting into this map. Typed fields provide better cross-language compatibility and type safety. This field will be removed in a future major version. This flattened map allows Python/TypeScript postprocessors to add arbitrary fields (entity extraction, keyword extraction, etc.). Fields are merged at the root level during serialization. Uses `Cow<'static, str>` keys so static string keys avoid allocation. |


---

### MetricsLayer

A `tower.Layer` that records service-level extraction metrics.

#### Methods

##### layer()

**Signature:**

```java
public Service layer(S inner)
```


---

### ModelCache

#### Methods

##### put()

Return a model to the cache for reuse.

If the cache already holds a model (e.g. from a concurrent caller),
the returned model is silently dropped.

**Signature:**

```java
public void put(T model)
```

##### take()

Take the cached model if one exists, without creating a new one.

**Signature:**

```java
public Optional<T> take()
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
- `page`: The page number (None becomes u64.MAX for hashing)
- `index`: The position of this node in the document's nodes array

**Signature:**

```java
public static NodeId generate(String nodeType, String text, int page, int index)
```

##### asRef()

**Signature:**

```java
public String asRef()
```

##### fmt()

**Signature:**

```java
public Unknown fmt(Formatter f)
```


---

### NormalizeResult

Result of image normalization

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `rgbData` | `byte[]` | — | Processed RGB image data (height * width * 3 bytes) |
| `dimensions` | `UsizeUsize` | — | Image dimensions (width, height) |
| `metadata` | `ImagePreprocessingMetadata` | — | Preprocessing metadata |


---

### Note

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `id` | `String` | — | Unique identifier |
| `noteType` | `NoteType` | — | Note type (note type) |
| `paragraphs` | `List<Paragraph>` | — | Paragraphs |


---

### NumbersExtractor

Apple Numbers spreadsheet extractor.

Supports `.numbers` files (modern iWork format, 2013+).

Extracts cell string values and sheet names from the IWA container:
ZIP → Snappy → protobuf text fields. Output is formatted as plain text
with one text token per line (representing cell values and labels).

#### Methods

##### defaultOptions()

**Signature:**

```java
public static NumbersExtractor defaultOptions()
```

##### name()

**Signature:**

```java
public String name()
```

##### version()

**Signature:**

```java
public String version()
```

##### initialize()

**Signature:**

```java
public void initialize() throws Error
```

##### shutdown()

**Signature:**

```java
public void shutdown() throws Error
```

##### description()

**Signature:**

```java
public String description()
```

##### author()

**Signature:**

```java
public String author()
```

##### extractBytes()

**Signature:**

```java
public InternalDocument extractBytes(byte[] content, String mimeType, ExtractionConfig config) throws Error
```

##### supportedMimeTypes()

**Signature:**

```java
public List<String> supportedMimeTypes()
```

##### priority()

**Signature:**

```java
public int priority()
```


---

### OcrCache

#### Methods

##### new()

**Signature:**

```java
public static OcrCache new(String cacheDir) throws OcrError
```

##### getCachedResult()

**Signature:**

```java
public Optional<OcrExtractionResult> getCachedResult(String imageHash, String backend, String config) throws OcrError
```

##### setCachedResult()

**Signature:**

```java
public void setCachedResult(String imageHash, String backend, String config, OcrExtractionResult result) throws OcrError
```

##### clear()

**Signature:**

```java
public void clear() throws OcrError
```

##### getStats()

**Signature:**

```java
public OcrCacheStats getStats() throws OcrError
```


---

### OcrCacheStats

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `totalFiles` | `long` | `null` | Total files |
| `totalSizeMb` | `double` | `null` | Total size mb |


---

### OcrConfidence

Confidence scores for an OCR element.

Separates detection confidence (how confident that text exists at this location)
from recognition confidence (how confident about the actual text content).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `detection` | `Optional<double>` | `null` | Detection confidence: how confident the OCR engine is that text exists here. PaddleOCR provides this as `box_score`, Tesseract doesn't have a direct equivalent. Range: 0.0 to 1.0 (or None if not available). |
| `recognition` | `double` | — | Recognition confidence: how confident about the text content. Range: 0.0 to 1.0. |

#### Methods

##### fromTesseract()

Create confidence from Tesseract's single confidence value.

Tesseract provides confidence as 0-100, which we normalize to 0.0-1.0.

**Signature:**

```java
public static OcrConfidence fromTesseract(double confidence)
```

##### fromPaddle()

Create confidence from PaddleOCR scores.

Both scores should be in 0.0-1.0 range, but PaddleOCR may occasionally return
values slightly above 1.0 due to model calibration. This method clamps both
values to ensure they stay within the valid 0.0-1.0 range.

**Signature:**

```java
public static OcrConfidence fromPaddle(float boxScore, float textScore)
```


---

### OcrConfig

OCR configuration.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `backend` | `String` | `null` | OCR backend: tesseract, easyocr, paddleocr |
| `language` | `String` | `null` | Language code (e.g., "eng", "deu") |
| `tesseractConfig` | `Optional<TesseractConfig>` | `null` | Tesseract-specific configuration (optional) |
| `outputFormat` | `Optional<OutputFormat>` | `OutputFormat.Plain` | Output format for OCR results (optional, for format conversion) |
| `paddleOcrConfig` | `Optional<Object>` | `null` | PaddleOCR-specific configuration (optional, JSON passthrough) |
| `elementConfig` | `Optional<OcrElementConfig>` | `null` | OCR element extraction configuration |
| `qualityThresholds` | `Optional<OcrQualityThresholds>` | `null` | Quality thresholds for the native-text-to-OCR fallback decision. When None, uses compiled defaults (matching previous hardcoded behavior). |
| `pipeline` | `Optional<OcrPipelineConfig>` | `null` | Multi-backend OCR pipeline configuration. When set, enables weighted fallback across multiple OCR backends based on output quality. When None, uses the single `backend` field (same as today). |
| `autoRotate` | `boolean` | `false` | Enable automatic page rotation based on orientation detection. When enabled, uses Tesseract's `DetectOrientationScript()` to detect page orientation (0/90/180/270 degrees) before OCR. If the page is rotated with high confidence, the image is corrected before recognition. This is critical for handling rotated scanned documents. |
| `vlmConfig` | `Optional<LlmConfig>` | `null` | VLM (Vision Language Model) OCR configuration. Required when `backend` is `"vlm"`. Uses liter-llm to send page images to a vision model for text extraction. |
| `vlmPrompt` | `Optional<String>` | `null` | Custom Jinja2 prompt template for VLM OCR. When `None`, uses the default template. Available variables: - `{{ language }}` — The document language code (e.g., "eng", "deu"). |

#### Methods

##### defaultOptions()

**Signature:**

```java
public static OcrConfig defaultOptions()
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

```java
public void validate() throws KreuzbergError
```

##### effectiveThresholds()

Returns the effective quality thresholds, using configured values or defaults.

**Signature:**

```java
public OcrQualityThresholds effectiveThresholds()
```

##### effectivePipeline()

Returns the effective pipeline config.

- If `pipeline` is explicitly set, returns it.
- If `paddle-ocr` feature is compiled in and no explicit pipeline is set,
  auto-constructs a default pipeline: primary backend (priority 100) + paddleocr (priority 50).
- Otherwise returns `null` (single-backend mode, same as today).

**Signature:**

```java
public Optional<OcrPipelineConfig> effectivePipeline()
```


---

### OcrElement

A unified OCR element representing detected text with full metadata.

This is the primary type for structured OCR output, preserving all information
from both Tesseract and PaddleOCR backends.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `text` | `String` | — | The recognized text content. |
| `geometry` | `OcrBoundingGeometry` | — | Bounding geometry (rectangle or quadrilateral). |
| `confidence` | `OcrConfidence` | — | Confidence scores for detection and recognition. |
| `level` | `OcrElementLevel` | — | Hierarchical level (word, line, block, page). |
| `rotation` | `Optional<OcrRotation>` | `null` | Rotation information (if detected). |
| `pageNumber` | `long` | — | Page number (1-indexed). |
| `parentId` | `Optional<String>` | `null` | Parent element ID for hierarchical relationships. Only used for Tesseract output which has word -> line -> block hierarchy. |
| `backendMetadata` | `Map<String, Object>` | — | Backend-specific metadata that doesn't fit the unified schema. |

#### Methods

##### withLevel()

Set the hierarchical level.

**Signature:**

```java
public OcrElement withLevel(OcrElementLevel level)
```

##### withRotation()

Set rotation information.

**Signature:**

```java
public OcrElement withRotation(OcrRotation rotation)
```

##### withPageNumber()

Set page number.

**Signature:**

```java
public OcrElement withPageNumber(long pageNumber)
```

##### withParentId()

Set parent element ID.

**Signature:**

```java
public OcrElement withParentId(String parentId)
```

##### withMetadata()

Add backend-specific metadata.

**Signature:**

```java
public OcrElement withMetadata(String key, Object value)
```

##### withRotationOpt()

**Signature:**

```java
public OcrElement withRotationOpt(OcrRotation rotation)
```


---

### OcrElementConfig

Configuration for OCR element extraction.

Controls how OCR elements are extracted and filtered.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `includeElements` | `boolean` | `null` | Whether to include OCR elements in the extraction result. When true, the `ocr_elements` field in `ExtractionResult` will be populated. |
| `minLevel` | `OcrElementLevel` | `OcrElementLevel.Line` | Minimum hierarchical level to include. Elements below this level (e.g., words when min_level is Line) will be excluded. |
| `minConfidence` | `double` | `null` | Minimum recognition confidence threshold (0.0-1.0). Elements with confidence below this threshold will be filtered out. |
| `buildHierarchy` | `boolean` | `null` | Whether to build hierarchical relationships between elements. When true, `parent_id` fields will be populated based on spatial containment. Only meaningful for Tesseract output. |


---

### OcrExtractionResult

OCR extraction result.

Result of performing OCR on an image or scanned document,
including recognized text and detected tables.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `content` | `String` | — | Recognized text content |
| `mimeType` | `String` | — | Original MIME type of the processed image |
| `metadata` | `Map<String, Object>` | — | OCR processing metadata (confidence scores, language, etc.) |
| `tables` | `List<OcrTable>` | — | Tables detected and extracted via OCR |
| `ocrElements` | `Optional<List<OcrElement>>` | `null` | Structured OCR elements with bounding boxes and confidence scores. Available when TSV output is requested or table detection is enabled. |
| `internalDocument` | `Optional<InternalDocument>` | `null` | Structured document produced from hOCR parsing. Carries paragraph structure, bounding boxes, and confidence scores that the flattened `content` string discards. |


---

### OcrMetadata

OCR processing metadata.

Captures information about OCR processing configuration and results.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `language` | `String` | — | OCR language code(s) used |
| `psm` | `int` | — | Tesseract Page Segmentation Mode (PSM) |
| `outputFormat` | `String` | — | Output format (e.g., "text", "hocr") |
| `tableCount` | `long` | — | Number of tables detected |
| `tableRows` | `Optional<long>` | `null` | Table rows |
| `tableCols` | `Optional<long>` | `null` | Table cols |


---

### OcrPipelineConfig

Multi-backend OCR pipeline with quality-based fallback.

Backends are tried in priority order (highest first). After each backend
produces output, quality is evaluated. If it meets `quality_thresholds.pipeline_min_quality`,
the result is accepted. Otherwise the next backend is tried.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `stages` | `List<OcrPipelineStage>` | — | Ordered list of backends to try. Sorted by priority (descending) at runtime. |
| `qualityThresholds` | `OcrQualityThresholds` | — | Quality thresholds for deciding whether to accept a result or try the next backend. |


---

### OcrPipelineStage

A single backend stage in the OCR pipeline.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `backend` | `String` | — | Backend name: "tesseract", "paddleocr", "easyocr", or a custom registered name. |
| `priority` | `int` | — | Priority weight (higher = tried first). Stages are sorted by priority descending. |
| `language` | `Optional<String>` | `null` | Language override for this stage (None = use parent OcrConfig.language). |
| `tesseractConfig` | `Optional<TesseractConfig>` | `null` | Tesseract-specific config override for this stage. |
| `paddleOcrConfig` | `Optional<Object>` | `null` | PaddleOCR-specific config for this stage. |
| `vlmConfig` | `Optional<LlmConfig>` | `null` | VLM config override for this pipeline stage. |


---

### OcrProcessor

#### Methods

##### new()

**Signature:**

```java
public static OcrProcessor new(String cacheDir) throws OcrError
```

##### processImage()

**Signature:**

```java
public OcrExtractionResult processImage(byte[] imageBytes, TesseractConfig config) throws OcrError
```

##### processImageWithFormat()

Process an image with OCR and respect the output format from ExtractionConfig.

This variant allows specifying an output format (Plain, Markdown, Djot) which
affects how the OCR result's mime_type is set when markdown output is requested.

**Signature:**

```java
public OcrExtractionResult processImageWithFormat(byte[] imageBytes, TesseractConfig config, OutputFormat outputFormat) throws OcrError
```

##### clearCache()

**Signature:**

```java
public void clearCache() throws OcrError
```

##### getCacheStats()

**Signature:**

```java
public OcrCacheStats getCacheStats() throws OcrError
```

##### processImageFile()

**Signature:**

```java
public OcrExtractionResult processImageFile(String filePath, TesseractConfig config) throws OcrError
```

##### processImageFileWithFormat()

Process a file with OCR and respect the output format from ExtractionConfig.

This variant allows specifying an output format (Plain, Markdown, Djot) which
affects how the OCR result's mime_type is set when markdown output is requested.

**Signature:**

```java
public OcrExtractionResult processImageFileWithFormat(String filePath, TesseractConfig config, OutputFormat outputFormat) throws OcrError
```

##### processImageFilesBatch()

Process multiple image files in parallel using Rayon.

This method processes OCR operations in parallel across CPU cores for improved throughput.
Results are returned in the same order as the input file paths.

**Signature:**

```java
public List<BatchItemResult> processImageFilesBatch(List<String> filePaths, TesseractConfig config)
```


---

### OcrQualityThresholds

Quality thresholds for OCR fallback decisions and pipeline quality gating.

All fields default to the values that match the previous hardcoded behavior,
so `OcrQualityThresholds.default()` preserves existing semantics exactly.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `minTotalNonWhitespace` | `long` | `64` | Minimum total non-whitespace characters to consider text substantive. |
| `minNonWhitespacePerPage` | `double` | `32` | Minimum non-whitespace characters per page on average. |
| `minMeaningfulWordLen` | `long` | `4` | Minimum character count for a word to be "meaningful". |
| `minMeaningfulWords` | `long` | `3` | Minimum count of meaningful words before text is accepted. |
| `minAlnumRatio` | `double` | `0.3` | Minimum alphanumeric ratio (non-whitespace chars that are alphanumeric). |
| `minGarbageChars` | `long` | `5` | Minimum Unicode replacement characters (U+FFFD) to trigger OCR fallback. |
| `maxFragmentedWordRatio` | `double` | `0.6` | Maximum fraction of short (1-2 char) words before text is considered fragmented. |
| `criticalFragmentedWordRatio` | `double` | `0.8` | Critical fragmentation threshold — triggers OCR regardless of meaningful words. Normal English text has ~20-30% short words. 80%+ is definitive garbage. |
| `minAvgWordLength` | `double` | `2` | Minimum average word length. Below this with enough words indicates garbled extraction. |
| `minWordsForAvgLengthCheck` | `long` | `50` | Minimum word count before average word length check applies. |
| `minConsecutiveRepeatRatio` | `double` | `0.08` | Minimum consecutive word repetition ratio to detect column scrambling. |
| `minWordsForRepeatCheck` | `long` | `50` | Minimum word count before consecutive repetition check is applied. |
| `substantiveMinChars` | `long` | `100` | Minimum character count for "substantive markdown" OCR skip gate. |
| `nonTextMinChars` | `long` | `20` | Minimum character count for "non-text content" OCR skip gate. |
| `alnumWsRatioThreshold` | `double` | `0.4` | Alphanumeric+whitespace ratio threshold for skip decisions. |
| `pipelineMinQuality` | `double` | `0.5` | Minimum quality score (0.0-1.0) for a pipeline stage result to be accepted. If the result from a backend scores below this, try the next backend. |

#### Methods

##### defaultOptions()

**Signature:**

```java
public static OcrQualityThresholds defaultOptions()
```


---

### OcrRotation

Rotation information for an OCR element.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `angleDegrees` | `double` | — | Rotation angle in degrees (0, 90, 180, 270 for PaddleOCR). |
| `confidence` | `Optional<double>` | `null` | Confidence score for the rotation detection. |

#### Methods

##### fromPaddle()

Create rotation from PaddleOCR angle classification.

PaddleOCR uses angle_index (0-3) representing 0, 90, 180, 270 degrees.

**Errors:**

Returns an error if `angle_index` is not in the valid range (0-3).

**Signature:**

```java
public static OcrRotation fromPaddle(int angleIndex, float angleScore) throws String
```


---

### OcrTable

Table detected via OCR.

Represents a table structure recognized during OCR processing.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `cells` | `List<List<String>>` | — | Table cells as a 2D vector (rows × columns) |
| `markdown` | `String` | — | Markdown representation of the table |
| `pageNumber` | `long` | — | Page number where the table was found (1-indexed) |
| `boundingBox` | `Optional<OcrTableBoundingBox>` | `null` | Bounding box of the table in pixel coordinates (from OCR word positions). |


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

##### defaultOptions()

**Signature:**

```java
public static OdtExtractor defaultOptions()
```

##### name()

**Signature:**

```java
public String name()
```

##### version()

**Signature:**

```java
public String version()
```

##### initialize()

**Signature:**

```java
public void initialize() throws Error
```

##### shutdown()

**Signature:**

```java
public void shutdown() throws Error
```

##### description()

**Signature:**

```java
public String description()
```

##### author()

**Signature:**

```java
public String author()
```

##### extractBytes()

**Signature:**

```java
public InternalDocument extractBytes(byte[] content, String mimeType, ExtractionConfig config) throws Error
```

##### supportedMimeTypes()

**Signature:**

```java
public List<String> supportedMimeTypes()
```

##### priority()

**Signature:**

```java
public int priority()
```


---

### OdtProperties

OpenDocument metadata from meta.xml

Contains metadata fields defined by the OASIS OpenDocument Format standard.
Uses Dublin Core elements (dc:) and OpenDocument meta elements (meta:).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `title` | `Optional<String>` | `null` | Document title (dc:title) |
| `subject` | `Optional<String>` | `null` | Document subject/topic (dc:subject) |
| `creator` | `Optional<String>` | `null` | Current document creator/author (dc:creator) |
| `initialCreator` | `Optional<String>` | `null` | Initial creator of the document (meta:initial-creator) |
| `keywords` | `Optional<String>` | `null` | Keywords or tags (meta:keyword) |
| `description` | `Optional<String>` | `null` | Document description (dc:description) |
| `date` | `Optional<String>` | `null` | Current modification date (dc:date) |
| `creationDate` | `Optional<String>` | `null` | Initial creation date (meta:creation-date) |
| `language` | `Optional<String>` | `null` | Document language (dc:language) |
| `generator` | `Optional<String>` | `null` | Generator/application that created the document (meta:generator) |
| `editingDuration` | `Optional<String>` | `null` | Editing duration in ISO 8601 format (meta:editing-duration) |
| `editingCycles` | `Optional<String>` | `null` | Number of edits/revisions (meta:editing-cycles) |
| `pageCount` | `Optional<int>` | `null` | Document statistics - page count (meta:page-count) |
| `wordCount` | `Optional<int>` | `null` | Document statistics - word count (meta:word-count) |
| `characterCount` | `Optional<int>` | `null` | Document statistics - character count (meta:character-count) |
| `paragraphCount` | `Optional<int>` | `null` | Document statistics - paragraph count (meta:paragraph-count) |
| `tableCount` | `Optional<int>` | `null` | Document statistics - table count (meta:table-count) |
| `imageCount` | `Optional<int>` | `null` | Document statistics - image count (meta:image-count) |


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

```java
public static InternalDocument buildInternalDocument(String orgText)
```

##### defaultOptions()

**Signature:**

```java
public static OrgModeExtractor defaultOptions()
```

##### name()

**Signature:**

```java
public String name()
```

##### version()

**Signature:**

```java
public String version()
```

##### initialize()

**Signature:**

```java
public void initialize() throws Error
```

##### shutdown()

**Signature:**

```java
public void shutdown() throws Error
```

##### description()

**Signature:**

```java
public String description()
```

##### author()

**Signature:**

```java
public String author()
```

##### extractBytes()

**Signature:**

```java
public InternalDocument extractBytes(byte[] content, String mimeType, ExtractionConfig config) throws Error
```

##### extractFile()

**Signature:**

```java
public InternalDocument extractFile(String path, String mimeType, ExtractionConfig config) throws Error
```

##### supportedMimeTypes()

**Signature:**

```java
public List<String> supportedMimeTypes()
```

##### priority()

**Signature:**

```java
public int priority()
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
| `byteStart` | `long` | — | Byte offset where this page starts in the content string (UTF-8 valid boundary, inclusive) |
| `byteEnd` | `long` | — | Byte offset where this page ends in the content string (UTF-8 valid boundary, exclusive) |
| `pageNumber` | `long` | — | Page number (1-indexed) |


---

### PageConfig

Page extraction and tracking configuration.

Controls how pages are extracted, tracked, and represented in the extraction results.
When `null`, page tracking is disabled.

Page range tracking in chunk metadata (first_page/last_page) is automatically enabled
when page boundaries are available and chunking is configured.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `extractPages` | `boolean` | `false` | Extract pages as separate array (ExtractionResult.pages) |
| `insertPageMarkers` | `boolean` | `false` | Insert page markers in main content string |
| `markerFormat` | `String` | `"

<!-- PAGE {page_num} -->

"` | Page marker format (use {page_num} placeholder) Default: "\n\n<!-- PAGE {page_num} -->\n\n" |

#### Methods

##### defaultOptions()

**Signature:**

```java
public static PageConfig defaultOptions()
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
| `pageNumber` | `long` | — | Page number (1-indexed) |
| `content` | `String` | — | Text content for this page |
| `tables` | `List<Table>` | — | Tables found on this page (uses Arc for memory efficiency) Serializes as Vec<Table> for JSON compatibility while maintaining Arc semantics in-memory for zero-copy sharing. |
| `images` | `List<ExtractedImage>` | — | Images found on this page (uses Arc for memory efficiency) Serializes as Vec<ExtractedImage> for JSON compatibility while maintaining Arc semantics in-memory for zero-copy sharing. |
| `hierarchy` | `Optional<PageHierarchy>` | `null` | Hierarchy information for the page (when hierarchy extraction is enabled) Contains text hierarchy levels (H1-H6) extracted from the page content. |
| `isBlank` | `Optional<boolean>` | `null` | Whether this page is blank (no meaningful text content) Determined during extraction based on text content analysis. A page is blank if it has fewer than 3 non-whitespace characters and contains no tables or images. |


---

### PageHierarchy

Page hierarchy structure containing heading levels and block information.

Used when PDF text hierarchy extraction is enabled. Contains hierarchical
blocks with heading levels (H1-H6) for semantic document structure.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `blockCount` | `long` | — | Number of hierarchy blocks on this page |
| `blocks` | `List<HierarchicalBlock>` | — | Hierarchical blocks with heading levels |


---

### PageInfo

Metadata for individual page/slide/sheet.

Captures per-page information including dimensions, content counts,
and visibility state (for presentations).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `number` | `long` | — | Page number (1-indexed) |
| `title` | `Optional<String>` | `null` | Page title (usually for presentations) |
| `dimensions` | `Optional<F64F64>` | `null` | Dimensions in points (PDF) or pixels (images): (width, height) |
| `imageCount` | `Optional<long>` | `null` | Number of images on this page |
| `tableCount` | `Optional<long>` | `null` | Number of tables on this page |
| `hidden` | `Optional<boolean>` | `null` | Whether this page is hidden (e.g., in presentations) |
| `isBlank` | `Optional<boolean>` | `null` | Whether this page is blank (no meaningful text, no images, no tables) A page is considered blank if it has fewer than 3 non-whitespace characters and contains no tables or images. This is useful for filtering out empty pages in scanned documents or PDFs with blank separator pages. |


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
| `pageIndex` | `long` | — | Page index |
| `regions` | `List<PageLayoutRegion>` | — | Regions |
| `pageWidthPts` | `float` | — | Page width pts |
| `pageHeightPts` | `float` | — | Page height pts |
| `renderWidthPx` | `int` | — | Width of the rendered image used for layout detection (pixels). |
| `renderHeightPx` | `int` | — | Height of the rendered image used for layout detection (pixels). |


---

### PageMargins

Page margins in twips (twentieths of a point).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `top` | `Optional<int>` | `null` | Top margin in twips. |
| `right` | `Optional<int>` | `null` | Right margin in twips. |
| `bottom` | `Optional<int>` | `null` | Bottom margin in twips. |
| `left` | `Optional<int>` | `null` | Left margin in twips. |
| `header` | `Optional<int>` | `null` | Header offset in twips. |
| `footer` | `Optional<int>` | `null` | Footer offset in twips. |
| `gutter` | `Optional<int>` | `null` | Gutter margin in twips. |

#### Methods

##### toPoints()

Convert all margins from twips to points.

Conversion factor: 1 twip = 1/20 point, or equivalently divide by 20.

**Signature:**

```java
public PageMarginsPoints toPoints()
```


---

### PageMarginsPoints

Page margins converted to points (1/72 inch).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `top` | `Optional<double>` | `null` | Top |
| `right` | `Optional<double>` | `null` | Right |
| `bottom` | `Optional<double>` | `null` | Bottom |
| `left` | `Optional<double>` | `null` | Left |
| `header` | `Optional<double>` | `null` | Header |
| `footer` | `Optional<double>` | `null` | Footer |
| `gutter` | `Optional<double>` | `null` | Gutter |


---

### PageRenderOptions

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `targetDpi` | `int` | `300` | Target dpi |
| `maxImageDimension` | `int` | `65536` | Maximum image dimension |
| `autoAdjustDpi` | `boolean` | `true` | Auto adjust dpi |
| `minDpi` | `int` | `72` | Minimum dpi |
| `maxDpi` | `int` | `600` | Maximum dpi |

#### Methods

##### defaultOptions()

**Signature:**

```java
public static PageRenderOptions defaultOptions()
```


---

### PageStructure

Unified page structure for documents.

Supports different page types (PDF pages, PPTX slides, Excel sheets)
with character offset boundaries for chunk-to-page mapping.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `totalCount` | `long` | — | Total number of pages/slides/sheets |
| `unitType` | `PageUnitType` | — | Type of paginated unit |
| `boundaries` | `Optional<List<PageBoundary>>` | `null` | Character offset boundaries for each page Maps character ranges in the extracted content to page numbers. Used for chunk page range calculation. |
| `pages` | `Optional<List<PageInfo>>` | `null` | Detailed per-page metadata (optional, only when needed) |


---

### PageTiming

Timing breakdown for a single page.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `renderMs` | `double` | — | Time to render the PDF page to a raster image (amortized from batch render). |
| `preprocessMs` | `double` | — | Time spent in image preprocessing (resize, normalize, tensor construction). |
| `onnxMs` | `double` | — | Time for the ONNX model session.run() call (actual neural network inference). |
| `inferenceMs` | `double` | — | Total model inference time (preprocess + onnx), as measured by the engine. |
| `postprocessMs` | `double` | — | Time spent in postprocessing (confidence filtering, overlap resolution). |
| `mappingMs` | `double` | — | Time to map pixel-space bounding boxes to PDF coordinate space. |


---

### PagesExtractor

Apple Pages document extractor.

Supports `.pages` files (modern iWork format, 2013+).

Extracts all text content from the document by parsing the IWA
(iWork Archive) container: ZIP → Snappy → protobuf text fields.

#### Methods

##### defaultOptions()

**Signature:**

```java
public static PagesExtractor defaultOptions()
```

##### name()

**Signature:**

```java
public String name()
```

##### version()

**Signature:**

```java
public String version()
```

##### initialize()

**Signature:**

```java
public void initialize() throws Error
```

##### shutdown()

**Signature:**

```java
public void shutdown() throws Error
```

##### description()

**Signature:**

```java
public String description()
```

##### author()

**Signature:**

```java
public String author()
```

##### extractBytes()

**Signature:**

```java
public InternalDocument extractBytes(byte[] content, String mimeType, ExtractionConfig config) throws Error
```

##### supportedMimeTypes()

**Signature:**

```java
public List<String> supportedMimeTypes()
```

##### priority()

**Signature:**

```java
public int priority()
```


---

### PanicContext

Context information captured when a panic occurs.

This struct stores detailed information about where and when a panic happened,
enabling better error reporting across FFI boundaries.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `file` | `String` | — | Source file where the panic occurred |
| `line` | `int` | — | Line number where the panic occurred |
| `function` | `String` | — | Function name where the panic occurred |
| `message` | `String` | — | Panic message extracted from the panic payload |
| `timestamp` | `SystemTime` | — | Timestamp when the panic was captured |

#### Methods

##### format()

Formats the panic context as a human-readable string.

**Signature:**

```java
public String format()
```


---

### ParaText

Plain text content decoded from a ParaText record (tag 0x43).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `content` | `String` | — | The extracted text content |

#### Methods

##### fromRecord()

Decode a ParaText record from raw bytes.

The data field of a TAG_PARA_TEXT record is a sequence of UTF-16LE code
units.  Control characters < 0x0020 are mapped to whitespace or skipped;
characters in the private-use range 0xF020–0xF07F (HWP internal controls)
are discarded.

**Signature:**

```java
public static ParaText fromRecord(Record record) throws Error
```


---

### Paragraph

A single paragraph; may or may not carry a text payload.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `text` | `Optional<ParaText>` | `null` | Text (para text) |


---

### ParagraphProperties

Paragraph-level formatting properties (alignment, spacing, indentation, etc.).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `alignment` | `Optional<String>` | `null` | `"left"`, `"center"`, `"right"`, `"both"` (justified). |
| `spacingBefore` | `Optional<int>` | `null` | Spacing before paragraph in twips. |
| `spacingAfter` | `Optional<int>` | `null` | Spacing after paragraph in twips. |
| `spacingLine` | `Optional<int>` | `null` | Line spacing in twips or 240ths of a line. |
| `spacingLineRule` | `Optional<String>` | `null` | Line spacing rule: "auto", "exact", or "atLeast". |
| `indentLeft` | `Optional<int>` | `null` | Left indentation in twips. |
| `indentRight` | `Optional<int>` | `null` | Right indentation in twips. |
| `indentFirstLine` | `Optional<int>` | `null` | First-line indentation in twips. |
| `indentHanging` | `Optional<int>` | `null` | Hanging indentation in twips. |
| `outlineLevel` | `Optional<byte>` | `null` | Outline level 0-8 for heading levels. |
| `keepNext` | `Optional<boolean>` | `null` | Keep with next paragraph on same page. |
| `keepLines` | `Optional<boolean>` | `null` | Keep all lines of paragraph on same page. |
| `pageBreakBefore` | `Optional<boolean>` | `null` | Force page break before paragraph. |
| `widowControl` | `Optional<boolean>` | `null` | Prevent widow/orphan lines. |
| `suppressAutoHyphens` | `Optional<boolean>` | `null` | Suppress automatic hyphenation. |
| `bidi` | `Optional<boolean>` | `null` | Right-to-left paragraph direction. |
| `shadingFill` | `Optional<String>` | `null` | Background color hex value (from w:shd w:fill). |
| `shadingVal` | `Optional<String>` | `null` | Shading pattern value (from w:shd w:val). |
| `borderTop` | `Optional<String>` | `null` | Top border style (from w:pBdr/w:top w:val). |
| `borderBottom` | `Optional<String>` | `null` | Bottom border style (from w:pBdr/w:bottom w:val). |
| `borderLeft` | `Optional<String>` | `null` | Left border style (from w:pBdr/w:left w:val). |
| `borderRight` | `Optional<String>` | `null` | Right border style (from w:pBdr/w:right w:val). |


---

### PdfAnnotation

A PDF annotation extracted from a document page.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `annotationType` | `PdfAnnotationType` | — | The type of annotation. |
| `content` | `Optional<String>` | `null` | Text content of the annotation (e.g., comment text, link URL). |
| `pageNumber` | `long` | — | Page number where the annotation appears (1-indexed). |
| `boundingBox` | `Optional<BoundingBox>` | `null` | Bounding box of the annotation on the page. |


---

### PdfConfig

PDF-specific configuration.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `backend` | `PdfBackend` | `PdfBackend.Pdfium` | PDF extraction backend. Default: `Pdfium`. |
| `extractImages` | `boolean` | `false` | Extract images from PDF |
| `passwords` | `Optional<List<String>>` | `Collections.emptyList()` | List of passwords to try when opening encrypted PDFs |
| `extractMetadata` | `boolean` | `true` | Extract PDF metadata |
| `hierarchy` | `Optional<HierarchyConfig>` | `null` | Hierarchy extraction configuration (None = hierarchy extraction disabled) |
| `extractAnnotations` | `boolean` | `false` | Extract PDF annotations (text notes, highlights, links, stamps). Default: false |
| `topMarginFraction` | `Optional<float>` | `null` | Top margin fraction (0.0–1.0) of page height to exclude headers/running heads. Default: 0.06 (6%) |
| `bottomMarginFraction` | `Optional<float>` | `null` | Bottom margin fraction (0.0–1.0) of page height to exclude footers/page numbers. Default: 0.05 (5%) |
| `allowSingleColumnTables` | `boolean` | `false` | Allow single-column pseudo tables in extraction results. By default, tables with fewer than 2 columns (layout-guided) or 3 columns (heuristic) are rejected. When `True`, the minimum column count is relaxed to 1, allowing single-column structured data (glossaries, itemized lists) to be emitted as tables. Other quality filters (density, sparsity, prose detection) still apply. |

#### Methods

##### defaultOptions()

**Signature:**

```java
public static PdfConfig defaultOptions()
```


---

### PdfExtractionMetadata

Complete PDF extraction metadata including common and PDF-specific fields.

This struct combines common document fields (title, authors, dates) with
PDF-specific metadata and optional page structure information. It is returned
by `extract_metadata_from_document()` when page boundaries are provided.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `title` | `Optional<String>` | `null` | Document title |
| `subject` | `Optional<String>` | `null` | Document subject or description |
| `authors` | `Optional<List<String>>` | `null` | Document authors (parsed from PDF Author field) |
| `keywords` | `Optional<List<String>>` | `null` | Document keywords (parsed from PDF Keywords field) |
| `createdAt` | `Optional<String>` | `null` | Creation timestamp (ISO 8601 format) |
| `modifiedAt` | `Optional<String>` | `null` | Last modification timestamp (ISO 8601 format) |
| `createdBy` | `Optional<String>` | `null` | Application or user that created the document |
| `pdfSpecific` | `PdfMetadata` | — | PDF-specific metadata |
| `pageStructure` | `Optional<PageStructure>` | `null` | Page structure with boundaries and optional per-page metadata |


---

### PdfExtractor

PDF document extractor using pypdfium2 and playa-pdf.

#### Methods

##### defaultOptions()

**Signature:**

```java
public static PdfExtractor defaultOptions()
```

##### name()

**Signature:**

```java
public String name()
```

##### version()

**Signature:**

```java
public String version()
```

##### initialize()

**Signature:**

```java
public void initialize() throws Error
```

##### shutdown()

**Signature:**

```java
public void shutdown() throws Error
```

##### extractBytes()

**Signature:**

```java
public InternalDocument extractBytes(byte[] content, String mimeType, ExtractionConfig config) throws Error
```

##### supportedMimeTypes()

**Signature:**

```java
public List<String> supportedMimeTypes()
```


---

### PdfImage

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `pageNumber` | `long` | — | Page number |
| `imageIndex` | `long` | — | Image index |
| `width` | `long` | — | Width |
| `height` | `long` | — | Height |
| `colorSpace` | `Optional<String>` | `null` | Color space |
| `bitsPerComponent` | `Optional<long>` | `null` | Bits per component |
| `filters` | `List<String>` | — | Original PDF stream filters (e.g. `["FlateDecode"]`, `["DCTDecode"]`). |
| `data` | `byte[]` | — | The decoded image bytes in a standard format (JPEG, PNG, etc.). |
| `decodedFormat` | `String` | — | The format of `data` after decoding: `"jpeg"`, `"png"`, `"jpeg2000"`, `"ccitt"`, or `"raw"`. |


---

### PdfImageExtractor

#### Methods

##### new()

**Signature:**

```java
public static PdfImageExtractor new(byte[] pdfBytes) throws Error
```

##### newWithPassword()

**Signature:**

```java
public static PdfImageExtractor newWithPassword(byte[] pdfBytes, String password) throws Error
```

##### extractImages()

**Signature:**

```java
public List<PdfImage> extractImages() throws Error
```

##### extractImagesFromPage()

**Signature:**

```java
public List<PdfImage> extractImagesFromPage(int pageNumber) throws Error
```

##### getImageCount()

**Signature:**

```java
public long getImageCount() throws Error
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

```java
public float width()
```

##### height()

**Signature:**

```java
public float height()
```


---

### PdfMetadata

PDF-specific metadata.

Contains metadata fields specific to PDF documents that are not in the common
`Metadata` structure. Common fields like title, authors, keywords, and dates
are now at the `Metadata` level.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `pdfVersion` | `Optional<String>` | `null` | PDF version (e.g., "1.7", "2.0") |
| `producer` | `Optional<String>` | `null` | PDF producer (application that created the PDF) |
| `isEncrypted` | `Optional<boolean>` | `null` | Whether the PDF is encrypted/password-protected |
| `width` | `Optional<long>` | `null` | First page width in points (1/72 inch) |
| `height` | `Optional<long>` | `null` | First page height in points (1/72 inch) |
| `pageCount` | `Optional<long>` | `null` | Total number of pages in the PDF document |


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

```java
public static PdfPageIterator new(byte[] pdfBytes, int dpi, String password) throws Error
```

##### fromFile()

Create an iterator from a file path.

Reads the file into memory once. Subsequent iterations render from
the owned bytes without re-reading the file.

**Errors:**

Returns an error if the file cannot be read or the PDF is invalid.

**Signature:**

```java
public static PdfPageIterator fromFile(Path path, int dpi, String password) throws Error
```

##### pageCount()

Number of pages in the PDF.

**Signature:**

```java
public long pageCount()
```

##### next()

**Signature:**

```java
public Optional<Item> next()
```

##### sizeHint()

**Signature:**

```java
public UsizeOptionUsize sizeHint()
```


---

### PdfRenderer

#### Methods

##### new()

**Signature:**

```java
public static PdfRenderer new() throws Error
```


---

### PdfTextExtractor

#### Methods

##### new()

**Signature:**

```java
public static PdfTextExtractor new() throws Error
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

##### defaultOptions()

**Signature:**

```java
public static PlainTextExtractor defaultOptions()
```

##### name()

**Signature:**

```java
public String name()
```

##### version()

**Signature:**

```java
public String version()
```

##### initialize()

**Signature:**

```java
public void initialize() throws Error
```

##### shutdown()

**Signature:**

```java
public void shutdown() throws Error
```

##### description()

**Signature:**

```java
public String description()
```

##### author()

**Signature:**

```java
public String author()
```

##### extractBytes()

**Signature:**

```java
public InternalDocument extractBytes(byte[] content, String mimeType, ExtractionConfig config) throws Error
```

##### supportedMimeTypes()

**Signature:**

```java
public List<String> supportedMimeTypes()
```

##### priority()

**Signature:**

```java
public int priority()
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

```java
public String name()
```

##### version()

Returns the semantic version of this plugin.

Should follow semver format: `MAJOR.MINOR.PATCH`

**Signature:**

```java
public String version()
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

```java
public void initialize() throws Error
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

```java
public void shutdown() throws Error
```

##### description()

Optional plugin description for debugging and logging.

Defaults to empty string if not overridden.

**Signature:**

```java
public String description()
```

##### author()

Optional plugin author information.

Defaults to empty string if not overridden.

**Signature:**

```java
public String author()
```


---

### PluginHealthStatus

Plugin health status information.

Contains diagnostic information about registered plugins for each type.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `ocrBackendsCount` | `long` | — | Number of registered OCR backends |
| `ocrBackends` | `List<String>` | — | Names of registered OCR backends |
| `extractorsCount` | `long` | — | Number of registered document extractors |
| `extractors` | `List<String>` | — | Names of registered document extractors |
| `postProcessorsCount` | `long` | — | Number of registered post-processors |
| `postProcessors` | `List<String>` | — | Names of registered post-processors |
| `validatorsCount` | `long` | — | Number of registered validators |
| `validators` | `List<String>` | — | Names of registered validators |

#### Methods

##### check()

Check plugin health and return status.

This function reads all plugin registries and collects information
about registered plugins. It logs warnings if critical plugins are missing.

**Returns:**

`PluginHealthStatus` with counts and names of all registered plugins.

**Signature:**

```java
public static PluginHealthStatus check()
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

```java
public PoolGuard acquire() throws PoolError
```

##### size()

Get the current number of objects in the pool.

**Signature:**

```java
public long size()
```

##### clear()

Clear the pool, discarding all pooled objects.

**Signature:**

```java
public void clear() throws PoolError
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

```java
public double hitRate()
```

##### snapshot()

Get all metrics as a struct for reporting.

**Signature:**

```java
public PoolMetricsSnapshot snapshot()
```

##### reset()

Reset all metrics to zero.

**Signature:**

```java
public void reset()
```

##### defaultOptions()

**Signature:**

```java
public static PoolMetrics defaultOptions()
```


---

### PoolMetricsSnapshot

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `totalAcquires` | `long` | — | Total acquires |
| `totalCacheHits` | `long` | — | Total cache hits |
| `peakItemsStored` | `long` | — | Peak items stored |
| `totalCreations` | `long` | — | Total creations |


---

### PoolSizeHint

Hint for optimal pool sizing based on document characteristics.

This struct contains the estimated sizes for string and byte buffers
that should be allocated in the pool to handle extraction without
excessive reallocation.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `estimatedTotalSize` | `long` | — | Estimated total string buffer pool size in bytes |
| `stringBufferCount` | `long` | — | Recommended number of string buffers |
| `stringBufferCapacity` | `long` | — | Recommended capacity per string buffer in bytes |
| `byteBufferCount` | `long` | — | Recommended number of byte buffers |
| `byteBufferCapacity` | `long` | — | Recommended capacity per byte buffer in bytes |

#### Methods

##### estimatedStringPoolMemory()

Calculate the estimated string pool memory in bytes.

This is the total estimated memory for all string buffers.

**Signature:**

```java
public long estimatedStringPoolMemory()
```

##### estimatedBytePoolMemory()

Calculate the estimated byte pool memory in bytes.

This is the total estimated memory for all byte buffers.

**Signature:**

```java
public long estimatedBytePoolMemory()
```

##### totalPoolMemory()

Calculate the total estimated pool memory in bytes.

This includes both string and byte buffer pools.

**Signature:**

```java
public long totalPoolMemory()
```


---

### Position

Horizontal or vertical position.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `relativeFrom` | `String` | — | Relative from |
| `offset` | `Optional<long>` | `null` | Offset |


---

### PostProcessorConfig

Post-processor configuration.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `enabled` | `boolean` | `true` | Enable post-processors |
| `enabledProcessors` | `Optional<List<String>>` | `Collections.emptyList()` | Whitelist of processor names to run (None = all enabled) |
| `disabledProcessors` | `Optional<List<String>>` | `Collections.emptyList()` | Blacklist of processor names to skip (None = none disabled) |
| `enabledSet` | `Optional<AHashSet>` | `null` | Pre-computed AHashSet for O(1) enabled processor lookup |
| `disabledSet` | `Optional<AHashSet>` | `null` | Pre-computed AHashSet for O(1) disabled processor lookup |

#### Methods

##### buildLookupSets()

Pre-compute HashSets for O(1) processor name lookups.

This method converts the enabled/disabled processor Vec to HashSet
for constant-time lookups in the pipeline.

**Signature:**

```java
public void buildLookupSets()
```

##### defaultOptions()

**Signature:**

```java
public static PostProcessorConfig defaultOptions()
```


---

### PptExtractionResult

Result of PPT text extraction.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `text` | `String` | — | Extracted text content, with slides separated by double newlines. |
| `slideCount` | `long` | — | Number of slides found. |
| `metadata` | `PptMetadata` | — | Document metadata. |
| `speakerNotes` | `List<String>` | — | Speaker notes text per slide (if available). |


---

### PptExtractor

Native PPT extractor using OLE/CFB parsing.

This extractor handles PowerPoint 97-2003 binary (.ppt) files without
requiring LibreOffice, providing ~50x faster extraction.

#### Methods

##### defaultOptions()

**Signature:**

```java
public static PptExtractor defaultOptions()
```

##### name()

**Signature:**

```java
public String name()
```

##### version()

**Signature:**

```java
public String version()
```

##### initialize()

**Signature:**

```java
public void initialize() throws Error
```

##### shutdown()

**Signature:**

```java
public void shutdown() throws Error
```

##### description()

**Signature:**

```java
public String description()
```

##### author()

**Signature:**

```java
public String author()
```

##### extractBytes()

**Signature:**

```java
public InternalDocument extractBytes(byte[] content, String mimeType, ExtractionConfig config) throws Error
```

##### supportedMimeTypes()

**Signature:**

```java
public List<String> supportedMimeTypes()
```

##### priority()

**Signature:**

```java
public int priority()
```


---

### PptMetadata

Metadata extracted from PPT files.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `title` | `Optional<String>` | `null` | Title |
| `subject` | `Optional<String>` | `null` | Subject |
| `author` | `Optional<String>` | `null` | Author |
| `lastAuthor` | `Optional<String>` | `null` | Last author |


---

### PptxAppProperties

Application properties from docProps/app.xml for PPTX

Contains PowerPoint-specific document metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `application` | `Optional<String>` | `null` | Application name (e.g., "Microsoft Office PowerPoint") |
| `appVersion` | `Optional<String>` | `null` | Application version |
| `totalTime` | `Optional<int>` | `null` | Total editing time in minutes |
| `company` | `Optional<String>` | `null` | Company name |
| `docSecurity` | `Optional<int>` | `null` | Document security level |
| `scaleCrop` | `Optional<boolean>` | `null` | Scale crop flag |
| `linksUpToDate` | `Optional<boolean>` | `null` | Links up to date flag |
| `sharedDoc` | `Optional<boolean>` | `null` | Shared document flag |
| `hyperlinksChanged` | `Optional<boolean>` | `null` | Hyperlinks changed flag |
| `slides` | `Optional<int>` | `null` | Number of slides |
| `notes` | `Optional<int>` | `null` | Number of notes |
| `hiddenSlides` | `Optional<int>` | `null` | Number of hidden slides |
| `multimediaClips` | `Optional<int>` | `null` | Number of multimedia clips |
| `presentationFormat` | `Optional<String>` | `null` | Presentation format (e.g., "Widescreen", "Standard") |
| `slideTitles` | `List<String>` | `Collections.emptyList()` | Slide titles |


---

### PptxExtractionOptions

Options for PPTX content extraction.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `extractImages` | `boolean` | `true` | Whether to extract embedded images. |
| `pageConfig` | `Optional<PageConfig>` | `null` | Optional page configuration for boundary tracking. |
| `plain` | `boolean` | `false` | Whether to output plain text (no markdown). |
| `includeStructure` | `boolean` | `false` | Whether to build the `DocumentStructure` tree. |
| `injectPlaceholders` | `boolean` | `true` | Whether to emit `![alt](target)` references in markdown output. |

#### Methods

##### defaultOptions()

**Signature:**

```java
public static PptxExtractionOptions defaultOptions()
```


---

### PptxExtractionResult

PowerPoint (PPTX) extraction result.

Contains extracted slide content, metadata, and embedded images/tables.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `content` | `String` | — | Extracted text content from all slides |
| `metadata` | `PptxMetadata` | — | Presentation metadata |
| `slideCount` | `long` | — | Total number of slides |
| `imageCount` | `long` | — | Total number of embedded images |
| `tableCount` | `long` | — | Total number of tables |
| `images` | `List<ExtractedImage>` | — | Extracted images from the presentation |
| `pageStructure` | `Optional<PageStructure>` | `null` | Slide structure with boundaries (when page tracking is enabled) |
| `pageContents` | `Optional<List<PageContent>>` | `null` | Per-slide content (when page tracking is enabled) |
| `document` | `Optional<DocumentStructure>` | `null` | Structured document representation |
| `hyperlinks` | `List<StringOptionString>` | — | Hyperlinks discovered in slides as (url, optional_label) pairs. |
| `officeMetadata` | `Map<String, String>` | — | Office metadata extracted from docProps/core.xml and docProps/app.xml. Contains keys like "title", "author", "created_by", "subject", "keywords", "modified_by", "created_at", "modified_at", etc. |


---

### PptxExtractor

PowerPoint presentation extractor.

Supports: .pptx, .pptm, .ppsx

#### Methods

##### defaultOptions()

**Signature:**

```java
public static PptxExtractor defaultOptions()
```

##### name()

**Signature:**

```java
public String name()
```

##### version()

**Signature:**

```java
public String version()
```

##### initialize()

**Signature:**

```java
public void initialize() throws Error
```

##### shutdown()

**Signature:**

```java
public void shutdown() throws Error
```

##### extractBytes()

**Signature:**

```java
public InternalDocument extractBytes(byte[] content, String mimeType, ExtractionConfig config) throws Error
```

##### extractFile()

**Signature:**

```java
public InternalDocument extractFile(String path, String mimeType, ExtractionConfig config) throws Error
```

##### supportedMimeTypes()

**Signature:**

```java
public List<String> supportedMimeTypes()
```

##### priority()

**Signature:**

```java
public int priority()
```


---

### PptxMetadata

PowerPoint presentation metadata.

Extracted from PPTX files containing slide counts and presentation details.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `slideCount` | `long` | — | Total number of slides in the presentation |
| `slideNames` | `List<String>` | — | Names of slides (if available) |
| `imageCount` | `Optional<long>` | `null` | Number of embedded images |
| `tableCount` | `Optional<long>` | `null` | Number of tables |


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

##### defaultOptions()

**Signature:**

```java
public static PstExtractor defaultOptions()
```

##### name()

**Signature:**

```java
public String name()
```

##### version()

**Signature:**

```java
public String version()
```

##### initialize()

**Signature:**

```java
public void initialize() throws Error
```

##### shutdown()

**Signature:**

```java
public void shutdown() throws Error
```

##### extractSync()

**Signature:**

```java
public InternalDocument extractSync(byte[] content, String mimeType, ExtractionConfig config) throws Error
```

##### extractBytes()

**Signature:**

```java
public InternalDocument extractBytes(byte[] content, String mimeType, ExtractionConfig config) throws Error
```

##### supportedMimeTypes()

**Signature:**

```java
public List<String> supportedMimeTypes()
```

##### priority()

**Signature:**

```java
public int priority()
```

##### asSyncExtractor()

**Signature:**

```java
public Optional<SyncExtractor> asSyncExtractor()
```


---

### PstMetadata

Outlook PST archive metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `messageCount` | `long` | `null` | Number of message |


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

```java
public String name()
```

##### version()

**Signature:**

```java
public String version()
```

##### initialize()

**Signature:**

```java
public void initialize() throws Error
```

##### shutdown()

**Signature:**

```java
public void shutdown() throws Error
```

##### process()

**Signature:**

```java
public void process(ExtractionResult result, ExtractionConfig config) throws Error
```

##### processingStage()

**Signature:**

```java
public ProcessingStage processingStage()
```

##### shouldProcess()

**Signature:**

```java
public boolean shouldProcess(ExtractionResult result, ExtractionConfig config)
```

##### estimatedDurationMs()

**Signature:**

```java
public long estimatedDurationMs(ExtractionResult result)
```


---

### RakeParams

RAKE-specific parameters.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `minWordLength` | `long` | `1` | Minimum word length to consider (default: 1). |
| `maxWordsPerPhrase` | `long` | `3` | Maximum words in a keyword phrase (default: 3). |

#### Methods

##### defaultOptions()

**Signature:**

```java
public static RakeParams defaultOptions()
```


---

### RecognizedTable

Pre-computed table markdown for a table detection region.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `detectionBbox` | `BBox` | — | Detection bbox that this table corresponds to (for matching). |
| `cells` | `List<List<String>>` | — | Table cells as a 2D vector (rows x columns). |
| `markdown` | `String` | — | Rendered markdown table. |


---

### Record

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `tagId` | `short` | — | Tag id |
| `data` | `byte[]` | — | Data |

#### Methods

##### parse()

**Signature:**

```java
public static Record parse(StreamReader reader) throws Error
```

##### dataReader()

Return a fresh `StreamReader` over this record's data bytes.

**Signature:**

```java
public StreamReader dataReader()
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

```java
public void reset()
```


---

### Relationship

A relationship between two elements in the document.

During extraction, targets may be unresolved keys (`RelationshipTarget.Key`).
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
| `height` | `Optional<int>` | `null` | Height |
| `heightRule` | `Optional<String>` | `null` | Height rule |
| `isHeader` | `boolean` | `null` | Whether header |
| `cantSplit` | `boolean` | `null` | Cant split |


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

```java
public static InternalDocument buildInternalDocument(String content, boolean injectPlaceholders)
```

##### defaultOptions()

**Signature:**

```java
public static RstExtractor defaultOptions()
```

##### name()

**Signature:**

```java
public String name()
```

##### version()

**Signature:**

```java
public String version()
```

##### initialize()

**Signature:**

```java
public void initialize() throws Error
```

##### shutdown()

**Signature:**

```java
public void shutdown() throws Error
```

##### description()

**Signature:**

```java
public String description()
```

##### author()

**Signature:**

```java
public String author()
```

##### extractBytes()

**Signature:**

```java
public InternalDocument extractBytes(byte[] content, String mimeType, ExtractionConfig config) throws Error
```

##### extractFile()

**Signature:**

```java
public InternalDocument extractFile(String path, String mimeType, ExtractionConfig config) throws Error
```

##### supportedMimeTypes()

**Signature:**

```java
public List<String> supportedMimeTypes()
```

##### priority()

**Signature:**

```java
public int priority()
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

```java
public static RtDetrModel fromFile(String path) throws LayoutError
```

##### detect()

**Signature:**

```java
public List<LayoutDetection> detect(RgbImage img) throws LayoutError
```

##### detectWithThreshold()

**Signature:**

```java
public List<LayoutDetection> detectWithThreshold(RgbImage img, float threshold) throws LayoutError
```

##### detectBatch()

**Signature:**

```java
public List<List<LayoutDetection>> detectBatch(List<RgbImage> images, float threshold) throws LayoutError
```

##### name()

**Signature:**

```java
public String name()
```


---

### RtfExtractor

Native Rust RTF extractor.

Extracts text content, metadata, and structure from RTF documents

#### Methods

##### defaultOptions()

**Signature:**

```java
public static RtfExtractor defaultOptions()
```

##### name()

**Signature:**

```java
public String name()
```

##### version()

**Signature:**

```java
public String version()
```

##### initialize()

**Signature:**

```java
public void initialize() throws Error
```

##### shutdown()

**Signature:**

```java
public void shutdown() throws Error
```

##### description()

**Signature:**

```java
public String description()
```

##### author()

**Signature:**

```java
public String author()
```

##### extractBytes()

**Signature:**

```java
public InternalDocument extractBytes(byte[] content, String mimeType, ExtractionConfig config) throws Error
```

##### supportedMimeTypes()

**Signature:**

```java
public List<String> supportedMimeTypes()
```

##### priority()

**Signature:**

```java
public int priority()
```


---

### Run

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `text` | `String` | `null` | Text |
| `bold` | `boolean` | `null` | Bold |
| `italic` | `boolean` | `null` | Italic |
| `underline` | `boolean` | `null` | Underline |
| `strikethrough` | `boolean` | `null` | Strikethrough |
| `subscript` | `boolean` | `null` | Subscript |
| `superscript` | `boolean` | `null` | Superscript |
| `fontSize` | `Optional<int>` | `null` | Font size in half-points (from `w:sz`). |
| `fontColor` | `Optional<String>` | `null` | Font color as "RRGGBB" hex (from `w:color`). |
| `highlight` | `Optional<String>` | `null` | Highlight color name (from `w:highlight`). |
| `hyperlinkUrl` | `Optional<String>` | `null` | Hyperlink url |
| `mathLatex` | `Optional<StringBool>` | `null` | LaTeX math content: (latex_source, is_display_math). When set, this run represents an equation and `text` is ignored. |

#### Methods

##### toMarkdown()

Render this run as markdown with formatting markers.

**Signature:**

```java
public String toMarkdown()
```


---

### RunProperties

Run-level formatting properties (bold, italic, font, size, color, etc.).

All fields are `Option` so that inheritance resolution can distinguish
"not set" (`null`) from "explicitly set" (`Some`).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `bold` | `Optional<boolean>` | `null` | Bold |
| `italic` | `Optional<boolean>` | `null` | Italic |
| `underline` | `Optional<boolean>` | `null` | Underline |
| `strikethrough` | `Optional<boolean>` | `null` | Strikethrough |
| `color` | `Optional<String>` | `null` | Hex RGB color, e.g. `"2F5496"`. |
| `fontSizeHalfPoints` | `Optional<int>` | `null` | Font size in half-points (`w:sz` val). Divide by 2 to get points. |
| `fontAscii` | `Optional<String>` | `null` | ASCII font family (`w:rFonts w:ascii`). |
| `fontAsciiTheme` | `Optional<String>` | `null` | ASCII theme font (`w:rFonts w:asciiTheme`). |
| `vertAlign` | `Optional<String>` | `null` | Vertical alignment: "superscript", "subscript", or "baseline". |
| `fontHAnsi` | `Optional<String>` | `null` | High ANSI font family (w:rFonts w:hAnsi). |
| `fontCs` | `Optional<String>` | `null` | Complex script font family (w:rFonts w:cs). |
| `fontEastAsia` | `Optional<String>` | `null` | East Asian font family (w:rFonts w:eastAsia). |
| `highlight` | `Optional<String>` | `null` | Highlight color name (e.g., "yellow", "green", "cyan"). |
| `caps` | `Optional<boolean>` | `null` | All caps text transformation. |
| `smallCaps` | `Optional<boolean>` | `null` | Small caps text transformation. |
| `shadow` | `Optional<boolean>` | `null` | Text shadow effect. |
| `outline` | `Optional<boolean>` | `null` | Text outline effect. |
| `emboss` | `Optional<boolean>` | `null` | Text emboss effect. |
| `imprint` | `Optional<boolean>` | `null` | Text imprint (engrave) effect. |
| `charSpacing` | `Optional<int>` | `null` | Character spacing in twips (from w:spacing w:val). |
| `position` | `Optional<int>` | `null` | Vertical position offset in half-points (from w:position w:val). |
| `kern` | `Optional<int>` | `null` | Kerning threshold in half-points (from w:kern w:val). |
| `themeColor` | `Optional<String>` | `null` | Theme color reference (e.g., "accent1", "dk1"). |
| `themeTint` | `Optional<String>` | `null` | Theme color tint modification (hex value). |
| `themeShade` | `Optional<String>` | `null` | Theme color shade modification (hex value). |


---

### Section

A body-text section containing a flat list of paragraphs.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `paragraphs` | `List<Paragraph>` | `Collections.emptyList()` | Paragraphs |


---

### SectionProperties

DOCX section properties parsed from `w:sectPr` element.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `pageWidthTwips` | `Optional<int>` | `null` | Page width in twips (from `w:pgSz w:w`). |
| `pageHeightTwips` | `Optional<int>` | `null` | Page height in twips (from `w:pgSz w:h`). |
| `orientation` | `Optional<Orientation>` | `Orientation.Portrait` | Page orientation (from `w:pgSz w:orient`). |
| `margins` | `PageMargins` | `null` | Page margins (from `w:pgMar`). |
| `columns` | `ColumnLayout` | `null` | Column layout (from `w:cols`). |
| `docGridLinePitch` | `Optional<int>` | `null` | Document grid line pitch in twips (from `w:docGrid w:linePitch`). |

#### Methods

##### pageWidthPoints()

Convert page width from twips to points.

**Signature:**

```java
public Optional<double> pageWidthPoints()
```

##### pageHeightPoints()

Convert page height from twips to points.

**Signature:**

```java
public Optional<double> pageHeightPoints()
```


---

### SecurityLimits

Configuration for security limits across extractors.

All limits are intentionally conservative to prevent DoS attacks
while still supporting legitimate documents.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `maxArchiveSize` | `long` | `null` | Maximum uncompressed size for archives (500 MB) |
| `maxCompressionRatio` | `long` | `100` | Maximum compression ratio before flagging as potential bomb (100:1) |
| `maxFilesInArchive` | `long` | `10000` | Maximum number of files in archive (10,000) |
| `maxNestingDepth` | `long` | `100` | Maximum nesting depth for structures (100) |
| `maxEntityLength` | `long` | `32` | Maximum entity/string length (32) |
| `maxContentSize` | `long` | `null` | Maximum string growth per document (100 MB) |
| `maxIterations` | `long` | `10000000` | Maximum iterations per operation |
| `maxXmlDepth` | `long` | `100` | Maximum XML depth (100 levels) |
| `maxTableCells` | `long` | `100000` | Maximum cells per table (100,000) |

#### Methods

##### defaultOptions()

**Signature:**

```java
public static SecurityLimits defaultOptions()
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
| `host` | `String` | `null` | Server host address (e.g., "127.0.0.1", "0.0.0.0") |
| `port` | `short` | `null` | Server port number |
| `corsOrigins` | `List<String>` | `Collections.emptyList()` | CORS allowed origins. Empty vector means allow all origins. If this is an empty vector, the server will accept requests from any origin. If populated with specific origins (e.g., ["https://example.com"]), only those origins will be allowed. |
| `maxRequestBodyBytes` | `long` | `null` | Maximum size of request body in bytes (default: 100 MB) |
| `maxMultipartFieldBytes` | `long` | `null` | Maximum size of multipart fields in bytes (default: 100 MB) |

#### Methods

##### defaultOptions()

**Signature:**

```java
public static ServerConfig defaultOptions()
```

##### listenAddr()

Get the server listen address (host:port).

**Signature:**

```java
public String listenAddr()
```

##### corsAllowsAll()

Check if CORS allows all origins.

Returns `true` if the `cors_origins` vector is empty, meaning all origins
are allowed. Returns `false` if specific origins are configured.

**Signature:**

```java
public boolean corsAllowsAll()
```

##### isOriginAllowed()

Check if a given origin is allowed by CORS configuration.

Returns `true` if:
- CORS allows all origins (empty origins list), or
- The given origin is in the allowed origins list

**Signature:**

```java
public boolean isOriginAllowed(String origin)
```

##### maxRequestBodyMb()

Get maximum request body size in megabytes (rounded up).

**Signature:**

```java
public long maxRequestBodyMb()
```

##### maxMultipartFieldMb()

Get maximum multipart field size in megabytes (rounded up).

**Signature:**

```java
public long maxMultipartFieldMb()
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

Returns `KreuzbergError.Validation` if:
- `KREUZBERG_PORT` cannot be parsed as u16
- `KREUZBERG_MAX_REQUEST_BODY_BYTES` cannot be parsed as usize
- `KREUZBERG_MAX_MULTIPART_FIELD_BYTES` cannot be parsed as usize

**Signature:**

```java
public void applyEnvOverrides() throws Error
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

Returns `KreuzbergError.Validation` if:
- File doesn't exist or cannot be read
- File extension is not recognized
- File content is invalid for the detected format

**Signature:**

```java
public static ServerConfig fromFile(Path path) throws Error
```

##### fromTomlFile()

Load server configuration from a TOML file.

**Errors:**

Returns `KreuzbergError.Validation` if the file doesn't exist or is invalid TOML.

**Signature:**

```java
public static ServerConfig fromTomlFile(Path path) throws Error
```

##### fromYamlFile()

Load server configuration from a YAML file.

**Errors:**

Returns `KreuzbergError.Validation` if the file doesn't exist or is invalid YAML.

**Signature:**

```java
public static ServerConfig fromYamlFile(Path path) throws Error
```

##### fromJsonFile()

Load server configuration from a JSON file.

**Errors:**

Returns `KreuzbergError.Validation` if the file doesn't exist or is invalid JSON.

**Signature:**

```java
public static ServerConfig fromJsonFile(Path path) throws Error
```


---

### SevenZExtractor

7z archive extractor.

Extracts file lists and text content from 7z archives.

#### Methods

##### defaultOptions()

**Signature:**

```java
public static SevenZExtractor defaultOptions()
```

##### name()

**Signature:**

```java
public String name()
```

##### version()

**Signature:**

```java
public String version()
```

##### initialize()

**Signature:**

```java
public void initialize() throws Error
```

##### shutdown()

**Signature:**

```java
public void shutdown() throws Error
```

##### description()

**Signature:**

```java
public String description()
```

##### author()

**Signature:**

```java
public String author()
```

##### extractBytes()

**Signature:**

```java
public InternalDocument extractBytes(byte[] content, String mimeType, ExtractionConfig config) throws Error
```

##### supportedMimeTypes()

**Signature:**

```java
public List<String> supportedMimeTypes()
```

##### priority()

**Signature:**

```java
public int priority()
```

##### asSyncExtractor()

**Signature:**

```java
public Optional<SyncExtractor> asSyncExtractor()
```

##### extractSync()

**Signature:**

```java
public InternalDocument extractSync(byte[] content, String mimeType, ExtractionConfig config) throws Error
```


---

### SlanetCell

A single cell detected by SLANeXT.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `polygon` | `F328` | — | Bounding box polygon in image pixel coordinates. Format: [x1, y1, x2, y2, x3, y3, x4, y4] (4 corners, clockwise from top-left). |
| `bbox` | `F324` | — | Axis-aligned bounding box derived from polygon: [left, top, right, bottom]. |
| `row` | `long` | — | Row index in the table (0-based). |
| `col` | `long` | — | Column index within the row (0-based). |


---

### SlanetModel

SLANeXT table structure recognition model.

Wraps an ORT session for SLANeXT ONNX model and provides preprocessing,
inference, and post-processing in a single `recognize` call.

#### Methods

##### fromFile()

Load a SLANeXT ONNX model from a file path.

**Signature:**

```java
public static SlanetModel fromFile(String path) throws LayoutError
```

##### recognize()

Recognize table structure from a cropped table image.

Returns a `SlanetResult` with detected cells, grid dimensions,
and structure tokens.

**Signature:**

```java
public SlanetResult recognize(RgbImage tableImg) throws LayoutError
```


---

### SlanetResult

SLANeXT recognition result for a single table image.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `cells` | `List<SlanetCell>` | — | Detected cells with bounding boxes and grid positions. |
| `numRows` | `long` | — | Number of rows in the table. |
| `numCols` | `long` | — | Maximum number of columns across all rows. |
| `confidence` | `float` | — | Average structure prediction confidence. |
| `structureTokens` | `List<String>` | — | Raw HTML structure tokens (for debugging). |


---

### StreamReader

#### Methods

##### readU8()

**Signature:**

```java
public byte readU8() throws Error
```

##### readU16()

**Signature:**

```java
public short readU16() throws Error
```

##### readU32()

**Signature:**

```java
public int readU32() throws Error
```

##### readBytes()

**Signature:**

```java
public byte[] readBytes(long len) throws Error
```

##### position()

Current byte position within the stream.

**Signature:**

```java
public long position()
```

##### remaining()

Number of bytes remaining from the current position to the end.

**Signature:**

```java
public long remaining()
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

```java
public void checkAppend(long len) throws SecurityError
```

##### currentSize()

Get current size.

**Signature:**

```java
public long currentSize()
```


---

### StructuredData

Structured data (Schema.org, microdata, RDFa) block.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `dataType` | `StructuredDataType` | — | Type of structured data |
| `rawJson` | `String` | — | Raw JSON string representation |
| `schemaType` | `Optional<String>` | `null` | Schema type if detectable (e.g., "Article", "Event", "Product") |


---

### StructuredDataResult

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `content` | `String` | — | The extracted text content |
| `format` | `Str` | — | Format (str) |
| `metadata` | `Map<String, String>` | — | Document metadata |
| `textFields` | `List<String>` | — | Text fields |


---

### StructuredExtractionConfig

Configuration for LLM-based structured data extraction.

Sends extracted document content to a VLM with a JSON schema,
returning structured data that conforms to the schema.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `schema` | `Object` | — | JSON Schema defining the desired output structure. |
| `schemaName` | `String` | — | Schema name passed to the LLM's structured output mode. |
| `schemaDescription` | `Optional<String>` | `null` | Optional schema description for the LLM. |
| `strict` | `boolean` | — | Enable strict mode — output must exactly match the schema. |
| `prompt` | `Optional<String>` | `null` | Custom Jinja2 extraction prompt template. When `None`, a default template is used. Available template variables: - `{{ content }}` — The extracted document text. - `{{ schema }}` — The JSON schema as a formatted string. - `{{ schema_name }}` — The schema name. - `{{ schema_description }}` — The schema description (may be empty). |
| `llm` | `LlmConfig` | — | LLM configuration for the extraction. |


---

### StructuredExtractor

Structured data extractor supporting JSON, JSONL/NDJSON, YAML, and TOML.

#### Methods

##### defaultOptions()

**Signature:**

```java
public static StructuredExtractor defaultOptions()
```

##### name()

**Signature:**

```java
public String name()
```

##### version()

**Signature:**

```java
public String version()
```

##### initialize()

**Signature:**

```java
public void initialize() throws Error
```

##### shutdown()

**Signature:**

```java
public void shutdown() throws Error
```

##### extractBytes()

**Signature:**

```java
public InternalDocument extractBytes(byte[] content, String mimeType, ExtractionConfig config) throws Error
```

##### supportedMimeTypes()

**Signature:**

```java
public List<String> supportedMimeTypes()
```

##### priority()

**Signature:**

```java
public int priority()
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

```java
public ResolvedStyle resolveStyle(String styleId)
```


---

### StyleDefinition

A single style definition parsed from `<w:style>` in `word/styles.xml`.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `id` | `String` | — | The style ID (`w:styleId` attribute). |
| `name` | `Optional<String>` | `null` | Human-readable name (`<w:name w:val="..."/>`). |
| `styleType` | `StyleType` | — | Style type: paragraph, character, table, or numbering. |
| `basedOn` | `Optional<String>` | `null` | ID of the parent style (`<w:basedOn w:val="..."/>`). |
| `nextStyle` | `Optional<String>` | `null` | ID of the style to apply to the next paragraph (`<w:next w:val="..."/>`). |
| `isDefault` | `boolean` | — | Whether this is the default style for its type. |
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

```java
public static StyledHtmlRenderer new(HtmlOutputConfig config) throws Error
```

##### name()

**Signature:**

```java
public String name()
```

##### render()

**Signature:**

```java
public String render(InternalDocument doc) throws Error
```


---

### SupportedFormat

A supported document format entry.

Represents a file extension and its corresponding MIME type that Kreuzberg can process.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `extension` | `String` | — | File extension (without leading dot), e.g., "pdf", "docx" |
| `mimeType` | `String` | — | MIME type string, e.g., "application/pdf" |


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

```java
public InternalDocument extractSync(byte[] content, String mimeType, ExtractionConfig config) throws Error
```


---

### Table

Extracted table structure.

Represents a table detected and extracted from a document (PDF, image, etc.).
Tables are converted to both structured cell data and Markdown format.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `cells` | `List<List<String>>` | — | Table cells as a 2D vector (rows × columns) |
| `markdown` | `String` | — | Markdown representation of the table |
| `pageNumber` | `long` | — | Page number where the table was found (1-indexed) |
| `boundingBox` | `Optional<BoundingBox>` | `null` | Bounding box of the table on the page (PDF coordinates: x0=left, y0=bottom, x1=right, y1=top). Only populated for PDF-extracted tables when position data is available. |


---

### TableBorders

Borders for a table (6 borders: top, bottom, left, right, insideH, insideV).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `top` | `Optional<BorderStyle>` | `null` | Top (border style) |
| `bottom` | `Optional<BorderStyle>` | `null` | Bottom (border style) |
| `left` | `Optional<BorderStyle>` | `null` | Left (border style) |
| `right` | `Optional<BorderStyle>` | `null` | Right (border style) |
| `insideH` | `Optional<BorderStyle>` | `null` | Inside h (border style) |
| `insideV` | `Optional<BorderStyle>` | `null` | Inside v (border style) |


---

### TableCell

Individual table cell with content and optional styling.

Future extension point for rich table support with cell-level metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `content` | `String` | — | Cell content as text |
| `rowSpan` | `long` | — | Row span (number of rows this cell spans) |
| `colSpan` | `long` | — | Column span (number of columns this cell spans) |
| `isHeader` | `boolean` | — | Whether this is a header cell |


---

### TableClassifier

PP-LCNet table classifier model.

#### Methods

##### fromFile()

Load the table classifier ONNX model from a file path.

**Signature:**

```java
public static TableClassifier fromFile(String path) throws LayoutError
```

##### classify()

Classify a cropped table image as wired or wireless.

**Signature:**

```java
public TableType classify(RgbImage tableImg) throws LayoutError
```


---

### TableGrid

Structured table grid with cell-level metadata.

Stores row/column dimensions and a flat list of cells with position info.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `rows` | `int` | — | Number of rows in the table. |
| `cols` | `int` | — | Number of columns in the table. |
| `cells` | `List<GridCell>` | — | All cells in row-major order. |


---

### TableLook

Table look bitmask/flags controlling conditional formatting bands.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `firstRow` | `boolean` | `null` | First row |
| `lastRow` | `boolean` | `null` | Last row |
| `firstColumn` | `boolean` | `null` | First column |
| `lastColumn` | `boolean` | `null` | Last column |
| `noHBand` | `boolean` | `null` | No h band |
| `noVBand` | `boolean` | `null` | No v band |


---

### TableProperties

Table-level properties from `<w:tblPr>`.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `styleId` | `Optional<String>` | `null` | Style id |
| `width` | `Optional<TableWidth>` | `null` | Width (table width) |
| `alignment` | `Optional<String>` | `null` | Alignment |
| `layout` | `Optional<String>` | `null` | Layout |
| `look` | `Optional<TableLook>` | `null` | Look (table look) |
| `borders` | `Optional<TableBorders>` | `null` | Borders (table borders) |
| `cellMargins` | `Optional<CellMargins>` | `null` | Cell margins (cell margins) |
| `indent` | `Optional<TableWidth>` | `null` | Indent (table width) |
| `caption` | `Optional<String>` | `null` | Caption |


---

### TableRow

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `cells` | `List<TableCell>` | `Collections.emptyList()` | Cells |
| `properties` | `Optional<RowProperties>` | `null` | Properties (row properties) |


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

```java
public void addCells(long count) throws SecurityError
```

##### currentCells()

Get current cell count.

**Signature:**

```java
public long currentCells()
```


---

### TableWidth

Width specification used for tables and cells.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `value` | `int` | — | Value |
| `widthType` | `String` | — | Width type |


---

### TarExtractor

TAR archive extractor.

Extracts file lists and text content from TAR archives.

#### Methods

##### defaultOptions()

**Signature:**

```java
public static TarExtractor defaultOptions()
```

##### name()

**Signature:**

```java
public String name()
```

##### version()

**Signature:**

```java
public String version()
```

##### initialize()

**Signature:**

```java
public void initialize() throws Error
```

##### shutdown()

**Signature:**

```java
public void shutdown() throws Error
```

##### description()

**Signature:**

```java
public String description()
```

##### author()

**Signature:**

```java
public String author()
```

##### extractBytes()

**Signature:**

```java
public InternalDocument extractBytes(byte[] content, String mimeType, ExtractionConfig config) throws Error
```

##### supportedMimeTypes()

**Signature:**

```java
public List<String> supportedMimeTypes()
```

##### priority()

**Signature:**

```java
public int priority()
```

##### asSyncExtractor()

**Signature:**

```java
public Optional<SyncExtractor> asSyncExtractor()
```

##### extractSync()

**Signature:**

```java
public InternalDocument extractSync(byte[] content, String mimeType, ExtractionConfig config) throws Error
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

```java
public static TatrModel fromFile(String path) throws LayoutError
```

##### recognize()

Recognize table structure from a cropped table image.

Returns a `TatrResult` with detected rows, columns, headers, and
spanning cells in the input image's pixel coordinate space.

**Signature:**

```java
public TatrResult recognize(RgbImage tableImg) throws LayoutError
```


---

### TatrResult

Aggregated TATR recognition result with detections separated by class.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `rows` | `List<TatrDetection>` | — | Detected rows, sorted top-to-bottom by `y2`. |
| `columns` | `List<TatrDetection>` | — | Detected columns, sorted left-to-right by `x2`. |
| `headers` | `List<TatrDetection>` | — | Detected headers (ColumnHeader and ProjectedRowHeader). |
| `spanning` | `List<TatrDetection>` | — | Detected spanning cells. |


---

### TessdataManager

Manages tessdata file downloading, caching, and manifest generation.

#### Methods

##### cacheDir()

Get the cache directory path.

**Signature:**

```java
public String cacheDir()
```

##### isLanguageCached()

Check if a specific language traineddata file is cached.

**Signature:**

```java
public boolean isLanguageCached(String lang)
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

```java
public static TesseractBackend new() throws Error
```

##### withCacheDir()

Create a new Tesseract backend with custom cache directory.

**Signature:**

```java
public static TesseractBackend withCacheDir(String cacheDir) throws Error
```

##### defaultOptions()

**Signature:**

```java
public static TesseractBackend defaultOptions()
```

##### name()

**Signature:**

```java
public String name()
```

##### version()

**Signature:**

```java
public String version()
```

##### initialize()

**Signature:**

```java
public void initialize() throws Error
```

##### shutdown()

**Signature:**

```java
public void shutdown() throws Error
```

##### processImage()

**Signature:**

```java
public ExtractionResult processImage(byte[] imageBytes, OcrConfig config) throws Error
```

##### processImageFile()

**Signature:**

```java
public ExtractionResult processImageFile(String path, OcrConfig config) throws Error
```

##### supportsLanguage()

**Signature:**

```java
public boolean supportsLanguage(String lang)
```

##### backendType()

**Signature:**

```java
public OcrBackendType backendType()
```

##### supportedLanguages()

**Signature:**

```java
public List<String> supportedLanguages()
```

##### supportsTableDetection()

**Signature:**

```java
public boolean supportsTableDetection()
```


---

### TesseractConfig

Tesseract OCR configuration.

Provides fine-grained control over Tesseract OCR engine parameters.
Most users can use the defaults, but these settings allow optimization
for specific document types (invoices, handwriting, etc.).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `language` | `String` | `"eng"` | Language code (e.g., "eng", "deu", "fra") |
| `psm` | `int` | `3` | Page Segmentation Mode (0-13). Common values: - 3: Fully automatic page segmentation (default) - 6: Assume a single uniform block of text - 11: Sparse text with no particular order |
| `outputFormat` | `String` | `"markdown"` | Output format ("text" or "markdown") |
| `oem` | `int` | `3` | OCR Engine Mode (0-3). - 0: Legacy engine only - 1: Neural nets (LSTM) only (usually best) - 2: Legacy + LSTM - 3: Default (based on what's available) |
| `minConfidence` | `double` | `0` | Minimum confidence threshold (0.0-100.0). Words with confidence below this threshold may be rejected or flagged. |
| `preprocessing` | `Optional<ImagePreprocessingConfig>` | `null` | Image preprocessing configuration. Controls how images are preprocessed before OCR. Can significantly improve quality for scanned documents or low-quality images. |
| `enableTableDetection` | `boolean` | `true` | Enable automatic table detection and reconstruction |
| `tableMinConfidence` | `double` | `0` | Minimum confidence threshold for table detection (0.0-1.0) |
| `tableColumnThreshold` | `int` | `50` | Column threshold for table detection (pixels) |
| `tableRowThresholdRatio` | `double` | `0.5` | Row threshold ratio for table detection (0.0-1.0) |
| `useCache` | `boolean` | `true` | Enable OCR result caching |
| `classifyUsePreAdaptedTemplates` | `boolean` | `true` | Use pre-adapted templates for character classification |
| `languageModelNgramOn` | `boolean` | `false` | Enable N-gram language model |
| `tesseditDontBlkrejGoodWds` | `boolean` | `true` | Don't reject good words during block-level processing |
| `tesseditDontRowrejGoodWds` | `boolean` | `true` | Don't reject good words during row-level processing |
| `tesseditEnableDictCorrection` | `boolean` | `true` | Enable dictionary correction |
| `tesseditCharWhitelist` | `String` | `""` | Whitelist of allowed characters (empty = all allowed) |
| `tesseditCharBlacklist` | `String` | `""` | Blacklist of forbidden characters (empty = none forbidden) |
| `tesseditUsePrimaryParamsModel` | `boolean` | `true` | Use primary language params model |
| `textordSpaceSizeIsVariable` | `boolean` | `true` | Variable-width space detection |
| `thresholdingMethod` | `boolean` | `false` | Use adaptive thresholding method |

#### Methods

##### defaultOptions()

**Signature:**

```java
public static TesseractConfig defaultOptions()
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
| `content` | `String` | — | Extracted text content |
| `lineCount` | `long` | — | Number of lines |
| `wordCount` | `long` | — | Number of words |
| `characterCount` | `long` | — | Number of characters |
| `headers` | `Optional<List<String>>` | `null` | Markdown headers (text only, Markdown files only) |
| `links` | `Optional<List<StringString>>` | `null` | Markdown links as (text, URL) tuples (Markdown files only) |
| `codeBlocks` | `Optional<List<StringString>>` | `null` | Code blocks as (language, code) tuples (Markdown files only) |


---

### TextMetadata

Text/Markdown metadata.

Extracted from plain text and Markdown files. Includes word counts and,
for Markdown, structural elements like headers and links.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `lineCount` | `long` | — | Number of lines in the document |
| `wordCount` | `long` | — | Number of words |
| `characterCount` | `long` | — | Number of characters |
| `headers` | `Optional<List<String>>` | `null` | Markdown headers (headings text only, for Markdown files) |
| `links` | `Optional<List<StringString>>` | `null` | Markdown links as (text, url) tuples (for Markdown files) |
| `codeBlocks` | `Optional<List<StringString>>` | `null` | Code blocks as (language, code) tuples (for Markdown files) |


---

### Theme

Complete theme with color scheme and font scheme.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `name` | `String` | `null` | Theme name (e.g., "Office Theme"). |
| `colorScheme` | `Optional<ColorScheme>` | `null` | Color scheme (12 standard colors). |
| `fontScheme` | `Optional<FontScheme>` | `null` | Font scheme (major and minor fonts). |


---

### TokenReductionConfig

Token reduction configuration.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `mode` | `String` | — | Reduction mode: "off", "light", "moderate", "aggressive", "maximum" |
| `preserveImportantWords` | `boolean` | — | Preserve important words (capitalized, technical terms) |


---

### TracingLayer

A `tower.Layer` that wraps each extraction in a semantic tracing span.

#### Methods

##### layer()

**Signature:**

```java
public Service layer(S inner)
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
| `enabled` | `boolean` | `true` | Enable code intelligence processing (default: true). When `False`, tree-sitter analysis is completely skipped even if the config section is present. |
| `cacheDir` | `Optional<String>` | `null` | Custom cache directory for downloaded grammars. When `None`, uses the default: `~/.cache/tree-sitter-language-pack/v{version}/libs/`. |
| `languages` | `Optional<List<String>>` | `Collections.emptyList()` | Languages to pre-download on init (e.g., `["python", "rust"]`). |
| `groups` | `Optional<List<String>>` | `Collections.emptyList()` | Language groups to pre-download (e.g., `["web", "systems", "scripting"]`). |
| `process` | `TreeSitterProcessConfig` | `null` | Processing options for code analysis. |

#### Methods

##### defaultOptions()

**Signature:**

```java
public static TreeSitterConfig defaultOptions()
```


---

### TreeSitterProcessConfig

Processing options for tree-sitter code analysis.

Controls which analysis features are enabled when extracting code files.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `structure` | `boolean` | `true` | Extract structural items (functions, classes, structs, etc.). Default: true. |
| `imports` | `boolean` | `true` | Extract import statements. Default: true. |
| `exports` | `boolean` | `true` | Extract export statements. Default: true. |
| `comments` | `boolean` | `false` | Extract comments. Default: false. |
| `docstrings` | `boolean` | `false` | Extract docstrings. Default: false. |
| `symbols` | `boolean` | `false` | Extract symbol definitions. Default: false. |
| `diagnostics` | `boolean` | `false` | Include parse diagnostics. Default: false. |
| `chunkMaxSize` | `Optional<long>` | `null` | Maximum chunk size in bytes. `None` disables chunking. |
| `contentMode` | `CodeContentMode` | `CodeContentMode.Chunks` | Content rendering mode for code extraction. |

#### Methods

##### defaultOptions()

**Signature:**

```java
public static TreeSitterProcessConfig defaultOptions()
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
| `conf` | `double` | — | Confidence score (0-100) |
| `text` | `String` | — | Recognized text |


---

### TypstExtractor

Typst document extractor

#### Methods

##### defaultOptions()

**Signature:**

```java
public static TypstExtractor defaultOptions()
```

##### name()

**Signature:**

```java
public String name()
```

##### version()

**Signature:**

```java
public String version()
```

##### initialize()

**Signature:**

```java
public void initialize() throws Error
```

##### shutdown()

**Signature:**

```java
public void shutdown() throws Error
```

##### description()

**Signature:**

```java
public String description()
```

##### author()

**Signature:**

```java
public String author()
```

##### extractBytes()

**Signature:**

```java
public InternalDocument extractBytes(byte[] content, String mimeType, ExtractionConfig config) throws Error
```

##### extractFile()

**Signature:**

```java
public InternalDocument extractFile(String path, String mimeType, ExtractionConfig config) throws Error
```

##### supportedMimeTypes()

**Signature:**

```java
public List<String> supportedMimeTypes()
```

##### priority()

**Signature:**

```java
public int priority()
```


---

### Uri

A URI extracted from a document.

Represents any link, reference, or resource pointer found during extraction.
The `kind` field classifies the URI semantically, while `label` carries
optional human-readable display text.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `url` | `String` | — | The URL or path string. |
| `label` | `Optional<String>` | `null` | Optional display text / label for the link. |
| `page` | `Optional<int>` | `null` | Optional page number where the URI was found (1-indexed). |
| `kind` | `UriKind` | — | Semantic classification of the URI. |

#### Methods

##### hyperlink()

Create a new hyperlink URI, auto-classifying `mailto:` as Email and `#` as Anchor.

**Signature:**

```java
public static Uri hyperlink(String url, String label)
```

##### image()

Create a new image URI.

**Signature:**

```java
public static Uri image(String url, String label)
```

##### citation()

Create a new citation URI (for DOIs, academic references).

**Signature:**

```java
public static Uri citation(String url, String label)
```

##### anchor()

Create a new anchor/cross-reference URI.

**Signature:**

```java
public static Uri anchor(String url, String label)
```

##### email()

Create a new email URI.

**Signature:**

```java
public static Uri email(String url, String label)
```

##### reference()

Create a new reference URI.

**Signature:**

```java
public static Uri reference(String url, String label)
```

##### withPage()

Set the page number.

**Signature:**

```java
public Uri withPage(int page)
```


---

### VlmOcrBackend

VLM-based OCR backend using liter-llm vision models.

This backend sends images to a vision language model (e.g., GPT-4o, Claude)
for text extraction, as an alternative to traditional OCR backends.

#### Methods

##### name()

**Signature:**

```java
public String name()
```

##### version()

**Signature:**

```java
public String version()
```

##### initialize()

**Signature:**

```java
public void initialize() throws Error
```

##### shutdown()

**Signature:**

```java
public void shutdown() throws Error
```

##### processImage()

**Signature:**

```java
public ExtractionResult processImage(byte[] imageBytes, OcrConfig config) throws Error
```

##### supportsLanguage()

**Signature:**

```java
public boolean supportsLanguage(String lang)
```

##### backendType()

**Signature:**

```java
public OcrBackendType backendType()
```


---

### XlsxAppProperties

Application properties from docProps/app.xml for XLSX

Contains Excel-specific document metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `application` | `Optional<String>` | `null` | Application name (e.g., "Microsoft Excel") |
| `appVersion` | `Optional<String>` | `null` | Application version |
| `docSecurity` | `Optional<int>` | `null` | Document security level |
| `scaleCrop` | `Optional<boolean>` | `null` | Scale crop flag |
| `linksUpToDate` | `Optional<boolean>` | `null` | Links up to date flag |
| `sharedDoc` | `Optional<boolean>` | `null` | Shared document flag |
| `hyperlinksChanged` | `Optional<boolean>` | `null` | Hyperlinks changed flag |
| `company` | `Optional<String>` | `null` | Company name |
| `worksheetNames` | `List<String>` | `Collections.emptyList()` | Worksheet names |


---

### XmlExtractionResult

XML extraction result.

Contains extracted text content from XML files along with
structural statistics about the XML document.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `content` | `String` | — | Extracted text content (XML structure filtered out) |
| `elementCount` | `long` | — | Total number of XML elements processed |
| `uniqueElements` | `List<String>` | — | List of unique element names found (sorted) |


---

### XmlExtractor

XML extractor.

Extracts text content from XML files, preserving element structure information.

#### Methods

##### defaultOptions()

**Signature:**

```java
public static XmlExtractor defaultOptions()
```

##### name()

**Signature:**

```java
public String name()
```

##### version()

**Signature:**

```java
public String version()
```

##### initialize()

**Signature:**

```java
public void initialize() throws Error
```

##### shutdown()

**Signature:**

```java
public void shutdown() throws Error
```

##### description()

**Signature:**

```java
public String description()
```

##### author()

**Signature:**

```java
public String author()
```

##### extractSync()

**Signature:**

```java
public InternalDocument extractSync(byte[] content, String mimeType, ExtractionConfig config) throws Error
```

##### extractBytes()

**Signature:**

```java
public InternalDocument extractBytes(byte[] content, String mimeType, ExtractionConfig config) throws Error
```

##### supportedMimeTypes()

**Signature:**

```java
public List<String> supportedMimeTypes()
```

##### priority()

**Signature:**

```java
public int priority()
```

##### asSyncExtractor()

**Signature:**

```java
public Optional<SyncExtractor> asSyncExtractor()
```


---

### XmlMetadata

XML metadata extracted during XML parsing.

Provides statistics about XML document structure.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `elementCount` | `long` | — | Total number of XML elements processed |
| `uniqueElements` | `List<String>` | — | List of unique element tag names (sorted) |


---

### YakeParams

YAKE-specific parameters.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `windowSize` | `long` | `2` | Window size for co-occurrence analysis (default: 2). Controls the context window for computing co-occurrence statistics. |

#### Methods

##### defaultOptions()

**Signature:**

```java
public static YakeParams defaultOptions()
```


---

### YearRange

Year range for bibliographic metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `min` | `Optional<int>` | `null` | Min |
| `max` | `Optional<int>` | `null` | Max |
| `years` | `List<Integer>` | — | Years |


---

### YoloModel

YOLO-family layout detection model (YOLOv10, DocLayout-YOLO, YOLOX).

#### Methods

##### fromFile()

Load a YOLO ONNX model from a file.

For square-input models (YOLOv10, DocLayout-YOLO), pass the same value for both dimensions.
For YOLOX (unstructuredio), use width=768, height=1024.

**Signature:**

```java
public static YoloModel fromFile(String path, YoloVariant variant, int inputWidth, int inputHeight, String modelName) throws LayoutError
```

##### detect()

**Signature:**

```java
public List<LayoutDetection> detect(RgbImage img) throws LayoutError
```

##### detectWithThreshold()

**Signature:**

```java
public List<LayoutDetection> detectWithThreshold(RgbImage img, float threshold) throws LayoutError
```

##### name()

**Signature:**

```java
public String name()
```


---

### ZipBombValidator

Helper struct for validating ZIP archives for security issues.


---

### ZipExtractor

ZIP archive extractor.

Extracts file lists and text content from ZIP archives.

#### Methods

##### defaultOptions()

**Signature:**

```java
public static ZipExtractor defaultOptions()
```

##### name()

**Signature:**

```java
public String name()
```

##### version()

**Signature:**

```java
public String version()
```

##### initialize()

**Signature:**

```java
public void initialize() throws Error
```

##### shutdown()

**Signature:**

```java
public void shutdown() throws Error
```

##### description()

**Signature:**

```java
public String description()
```

##### author()

**Signature:**

```java
public String author()
```

##### extractBytes()

**Signature:**

```java
public InternalDocument extractBytes(byte[] content, String mimeType, ExtractionConfig config) throws Error
```

##### supportedMimeTypes()

**Signature:**

```java
public List<String> supportedMimeTypes()
```

##### priority()

**Signature:**

```java
public int priority()
```

##### asSyncExtractor()

**Signature:**

```java
public Optional<SyncExtractor> asSyncExtractor()
```

##### extractSync()

**Signature:**

```java
public InternalDocument extractSync(byte[] content, String mimeType, ExtractionConfig config) throws Error
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

Assigned by the heuristic classifier in `chunking.classifier`.
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

