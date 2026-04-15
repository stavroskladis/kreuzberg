---
title: "Go API Reference"
---

# Go API Reference <span class="version-badge">v4.8.5</span>

## Functions

### IsBatchMode()

Check if we're currently in batch processing mode.

Returns `false` if the task-local is not set (single-file mode).

**Signature:**

```go
func IsBatchMode() bool
```

**Returns:** `bool`


---

### ResolveThreadBudget()

Resolve the effective thread budget from config or auto-detection.

User-set `max_threads` takes priority. Otherwise auto-detects from `num_cpus`,
capped at 8 for sane defaults in serverless environments.

**Signature:**

```go
func ResolveThreadBudget(config ConcurrencyConfig) int
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Config` | `*ConcurrencyConfig` | No | The configuration options |

**Returns:** `int`


---

### InitThreadPools()

Initialize the global Rayon thread pool with the given budget.

Safe to call multiple times — only the first call takes effect (subsequent
calls are silently ignored).

**Signature:**

```go
func InitThreadPools(budget int)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Budget` | `int` | Yes | The budget |

**Returns:** ``


---

### MergeConfigJson()

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

```go
func MergeConfigJson(base ExtractionConfig, overrideJson interface{}) (ExtractionConfig, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Base` | `ExtractionConfig` | Yes | The extraction config |
| `OverrideJson` | `interface{}` | Yes | The override json |

**Returns:** `ExtractionConfig`

**Errors:** Returns `error`.


---

### BuildConfigFromJson()

Build extraction config by optionally merging JSON overrides into a base config.

If `override_json` is `nil`, returns a clone of `base`. Otherwise delegates
to `merge_config_json`.

**Signature:**

```go
func BuildConfigFromJson(base ExtractionConfig, overrideJson interface{}) (ExtractionConfig, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Base` | `ExtractionConfig` | Yes | The extraction config |
| `OverrideJson` | `*interface{}` | No | The override json |

**Returns:** `ExtractionConfig`

**Errors:** Returns `error`.


---

### IsValidFormatField()

Validates whether a field name is in the known formats registry.

This uses a pre-built hash set for O(1) lookups instead of linear search,
providing significant performance improvements for repeated validations.

**Returns:**

`true` if the field is in KNOWN_FORMATS, `false` otherwise.

**Signature:**

```go
func IsValidFormatField(field string) bool
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Field` | `string` | Yes | The field name to validate |

**Returns:** `bool`


---

### OpenFileBytes()

Open a file and return its bytes with zero-copy for large files.

On non-WASM targets, files larger than `MMAP_THRESHOLD_BYTES` are
memory-mapped so that the file contents are never copied to the heap.
The mapping is read-only; the file must not be modified while the returned
`FileBytes` is alive, which is safe for document extraction.

On WASM or for small files, falls back to a plain `std.fs.read`.

**Errors:**

Returns `KreuzbergError.Io` for any I/O failure.

**Signature:**

```go
func OpenFileBytes(path string) (FileBytes, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Path` | `string` | Yes | Path to the file |

**Returns:** `FileBytes`

**Errors:** Returns `error`.


---

### ReadFileAsync()

Read a file asynchronously.

**Returns:**

The file contents as bytes.

**Errors:**

Returns `KreuzbergError.Io` for I/O errors (these always bubble up).

**Signature:**

```go
func ReadFileAsync(path Path) ([]byte, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Path` | `Path` | Yes | Path to the file to read |

**Returns:** `[]byte`

**Errors:** Returns `error`.


---

### ReadFileSync()

Read a file synchronously.

**Returns:**

The file contents as bytes.

**Errors:**

Returns `KreuzbergError.Io` for I/O errors (these always bubble up).

**Signature:**

```go
func ReadFileSync(path Path) ([]byte, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Path` | `Path` | Yes | Path to the file to read |

**Returns:** `[]byte`

**Errors:** Returns `error`.


---

### FileExists()

Check if a file exists.

**Returns:**

`true` if the file exists, `false` otherwise.

**Signature:**

```go
func FileExists(path Path) bool
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Path` | `Path` | Yes | Path to check |

**Returns:** `bool`


---

### ValidateFileExists()

Validate that a file exists.

**Errors:**

Returns `KreuzbergError.Io` if file doesn't exist.

**Signature:**

```go
func ValidateFileExists(path Path) error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Path` | `Path` | Yes | Path to validate |

**Returns:** ``

**Errors:** Returns `error`.


---

### FindFilesByExtension()

Get all files in a directory with a specific extension.

**Returns:**

Vector of file paths with the specified extension.

**Errors:**

Returns `KreuzbergError.Io` for I/O errors.

**Signature:**

```go
func FindFilesByExtension(dir Path, extension string, recursive bool) ([]string, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Dir` | `Path` | Yes | Directory to search |
| `Extension` | `string` | Yes | File extension to match (without the dot) |
| `Recursive` | `bool` | Yes | Whether to recursively search subdirectories |

**Returns:** `[]string`

**Errors:** Returns `error`.


---

### DetectMimeType()

Detect MIME type from a file path.

Uses file extension to determine MIME type. Falls back to `mime_guess` crate
if extension-based detection fails.

**Returns:**

The detected MIME type string.

**Errors:**

Returns `KreuzbergError.Io` if file doesn't exist (when `check_exists` is true).
Returns `KreuzbergError.UnsupportedFormat` if MIME type cannot be determined.

**Signature:**

```go
func DetectMimeType(path Path, checkExists bool) (string, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Path` | `Path` | Yes | Path to the file |
| `CheckExists` | `bool` | Yes | Whether to verify file existence |

**Returns:** `string`

**Errors:** Returns `error`.


---

### ValidateMimeType()

Validate that a MIME type is supported.

**Returns:**

The validated MIME type (may be normalized).

**Errors:**

Returns `KreuzbergError.UnsupportedFormat` if not supported.

**Signature:**

```go
func ValidateMimeType(mimeType string) (string, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `MimeType` | `string` | Yes | The MIME type to validate |

**Returns:** `string`

**Errors:** Returns `error`.


---

### DetectOrValidate()

Detect or validate MIME type.

If `mime_type` is provided, validates it. Otherwise, detects from `path`.

**Returns:**

The validated MIME type string.

**Signature:**

```go
func DetectOrValidate(path string, mimeType string) (string, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Path` | `*string` | No | Optional path to detect MIME type from |
| `MimeType` | `*string` | No | Optional explicit MIME type to validate |

**Returns:** `string`

**Errors:** Returns `error`.


---

### DetectMimeTypeFromBytes()

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

```go
func DetectMimeTypeFromBytes(content []byte) (string, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Content` | `[]byte` | Yes | Raw file bytes |

**Returns:** `string`

**Errors:** Returns `error`.


---

### GetExtensionsForMime()

Get file extensions for a given MIME type.

Returns all known file extensions that map to the specified MIME type.

**Returns:**

A vector of file extensions (without leading dot) for the MIME type.

**Signature:**

```go
func GetExtensionsForMime(mimeType string) ([]string, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `MimeType` | `string` | Yes | The MIME type to look up |

**Returns:** `[]string`

**Errors:** Returns `error`.


---

### ListSupportedFormats()

List all supported document formats.

Returns a list of all file extensions and their corresponding MIME types
that Kreuzberg can process. Derived from the centralized `FORMATS` registry.

The list is sorted alphabetically by file extension.

**Signature:**

```go
func ListSupportedFormats() []SupportedFormat
```

**Returns:** `[]SupportedFormat`


---

### RunPipeline()

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

```go
func RunPipeline(doc InternalDocument, config ExtractionConfig) (ExtractionResult, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Doc` | `InternalDocument` | Yes | The internal document produced by the extractor |
| `Config` | `ExtractionConfig` | Yes | Extraction configuration |

**Returns:** `ExtractionResult`

**Errors:** Returns `error`.


---

### RunPipelineSync()

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

```go
func RunPipelineSync(doc InternalDocument, config ExtractionConfig) (ExtractionResult, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Doc` | `InternalDocument` | Yes | The internal document produced by the extractor |
| `Config` | `ExtractionConfig` | Yes | Extraction configuration |

**Returns:** `ExtractionResult`

**Errors:** Returns `error`.


---

### IsPageTextBlank()

Determine if a page's text content indicates a blank page.

A page is blank if it has fewer than `MIN_NON_WHITESPACE_CHARS` non-whitespace characters.

**Returns:**

`true` if the page is considered blank, `false` otherwise

**Signature:**

```go
func IsPageTextBlank(text string) bool
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Text` | `string` | Yes | The extracted text content of the page |

**Returns:** `bool`


---

### ResolveRelationships()

Resolve `RelationshipTarget.Key` entries to `RelationshipTarget.Index`.

Builds an anchor index from elements with non-`nil` anchors, then resolves
each key-based relationship target. Unresolvable keys are logged and skipped
(the relationship is left as `Key` — it will be excluded from the final
`DocumentStructure` relationships).

**Signature:**

```go
func ResolveRelationships(doc InternalDocument)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Doc` | `InternalDocument` | Yes | The internal document |

**Returns:** ``


---

### DeriveDocumentStructure()

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

```go
func DeriveDocumentStructure(doc InternalDocument) DocumentStructure
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Doc` | `InternalDocument` | Yes | The internal document |

**Returns:** `DocumentStructure`


---

### DeriveExtractionResult()

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

```go
func DeriveExtractionResult(doc InternalDocument, includeDocumentStructure bool, outputFormat OutputFormat) ExtractionResult
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Doc` | `InternalDocument` | Yes | The internal document |
| `IncludeDocumentStructure` | `bool` | Yes | The include document structure |
| `OutputFormat` | `OutputFormat` | Yes | The output format |

**Returns:** `ExtractionResult`


---

### ParseJson()

**Signature:**

```go
func ParseJson(data []byte, config JsonExtractionConfig) (StructuredDataResult, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Data` | `[]byte` | Yes | The data |
| `Config` | `*JsonExtractionConfig` | No | The configuration options |

**Returns:** `StructuredDataResult`

**Errors:** Returns `error`.


---

### ParseJsonl()

Parse JSONL (newline-delimited JSON) into a structured data result.

Each non-empty line is parsed as an independent JSON value. Blank lines
and whitespace-only lines are skipped. The output is a pretty-printed
JSON array of all parsed objects.

**Errors:**

Returns an error if any line contains invalid JSON (with 1-based line number)
or if the input is not valid UTF-8.

**Signature:**

```go
func ParseJsonl(data []byte, config JsonExtractionConfig) (StructuredDataResult, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Data` | `[]byte` | Yes | The data |
| `Config` | `*JsonExtractionConfig` | No | The configuration options |

**Returns:** `StructuredDataResult`

**Errors:** Returns `error`.


---

### ParseYaml()

**Signature:**

```go
func ParseYaml(data []byte) (StructuredDataResult, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Data` | `[]byte` | Yes | The data |

**Returns:** `StructuredDataResult`

**Errors:** Returns `error`.


---

### ParseToml()

**Signature:**

```go
func ParseToml(data []byte) (StructuredDataResult, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Data` | `[]byte` | Yes | The data |

**Returns:** `StructuredDataResult`

**Errors:** Returns `error`.


---

### ParseText()

**Signature:**

```go
func ParseText(textBytes []byte, isMarkdown bool) (TextExtractionResult, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `TextBytes` | `[]byte` | Yes | The text bytes |
| `IsMarkdown` | `bool` | Yes | The is markdown |

**Returns:** `TextExtractionResult`

**Errors:** Returns `error`.


---

### TransformExtractionResultToElements()

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

```go
func TransformExtractionResultToElements(result ExtractionResult) []Element
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Result` | `ExtractionResult` | Yes | Reference to the ExtractionResult to transform |

**Returns:** `[]Element`


---

### ParseBodyText()

Parse a raw (possibly compressed) BodyText/SectionN stream.

Returns the list of sections found. Each section contains zero or more
paragraphs that carry the plain-text content.

**Signature:**

```go
func ParseBodyText(data []byte, isCompressed bool) ([]Section, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Data` | `[]byte` | Yes | The data |
| `IsCompressed` | `bool` | Yes | The is compressed |

**Returns:** `[]Section`

**Errors:** Returns `error`.


---

### DecompressStream()

Decompress a raw-deflate stream from an HWP section.

HWP 5.0 compresses sections with raw deflate (no zlib header). Falls back
to zlib if raw deflate fails, and returns the data as-is if both fail.

**Signature:**

```go
func DecompressStream(data []byte) ([]byte, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Data` | `[]byte` | Yes | The data |

**Returns:** `[]byte`

**Errors:** Returns `error`.


---

### ExtractHwpText()

Extract all plain text from an HWP 5.0 document given its raw bytes.

**Errors:**

Returns `HwpError` if the bytes do not form a valid HWP 5.0 compound file,
if the document is password-encrypted, or if a critical parsing step fails.

**Signature:**

```go
func ExtractHwpText(bytes []byte) (string, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Bytes` | `[]byte` | Yes | The bytes |

**Returns:** `string`

**Errors:** Returns `error`.


---

### LoadImageForOcr()

Load image bytes for OCR, with JPEG 2000 and JBIG2 fallback support.

The standard `image` crate does not support JPEG 2000 or JBIG2 formats.
This function detects these formats by magic bytes and uses `hayro-jpeg2000`
/ `hayro-jbig2` for decoding, falling back to the standard `image` crate
for all other formats.

**Signature:**

```go
func LoadImageForOcr(imageBytes []byte) (DynamicImage, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `ImageBytes` | `[]byte` | Yes | The image bytes |

**Returns:** `DynamicImage`

**Errors:** Returns `error`.


---

### ExtractImageMetadata()

Extract metadata from image bytes.

Extracts dimensions, format, and EXIF data from the image.
Attempts to decode using the standard image crate first, then falls back to
pure Rust JP2 box parsing for JPEG 2000 formats if the standard decoder fails.

**Signature:**

```go
func ExtractImageMetadata(bytes []byte) (ImageMetadata, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Bytes` | `[]byte` | Yes | The bytes |

**Returns:** `ImageMetadata`

**Errors:** Returns `error`.


---

### ExtractTextFromImageWithOcr()

Extract text from image bytes using OCR with optional page tracking for multi-frame TIFFs.

This function:
- Detects if the image is a multi-frame TIFF
- For multi-frame TIFFs with PageConfig enabled, iterates frames and tracks boundaries
- For single-frame images or when page tracking is disabled, runs OCR on the whole image
- Returns (content, boundaries, page_contents) tuple

**Returns:**
ImageOcrResult with content and optional boundaries for pagination

**Signature:**

```go
func ExtractTextFromImageWithOcr(bytes []byte, mimeType string, ocrResult string, pageConfig PageConfig) (ImageOcrResult, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Bytes` | `[]byte` | Yes | Image file bytes |
| `MimeType` | `string` | Yes | MIME type (e.g., "image/tiff") |
| `OcrResult` | `string` | Yes | OCR backend result containing the text |
| `PageConfig` | `*PageConfig` | No | Optional page configuration for boundary tracking |

**Returns:** `ImageOcrResult`

**Errors:** Returns `error`.


---

### EstimateContentCapacity()

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

```go
func EstimateContentCapacity(fileSize uint64, format string) int
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `FileSize` | `uint64` | Yes | The size of the original file in bytes |
| `Format` | `string` | Yes | The file format/extension (e.g., "txt", "html", "docx", "xlsx", "pptx") |

**Returns:** `int`


---

### EstimateHtmlMarkdownCapacity()

Estimate capacity for HTML to Markdown conversion.

HTML documents typically convert to Markdown with 60-70% of the original size.
This function estimates capacity specifically for HTML→Markdown conversion.

**Returns:**

An estimated capacity for the Markdown output

**Signature:**

```go
func EstimateHtmlMarkdownCapacity(htmlSize uint64) int
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `HtmlSize` | `uint64` | Yes | The size of the HTML file in bytes |

**Returns:** `int`


---

### EstimateSpreadsheetCapacity()

Estimate capacity for cell extraction from spreadsheets.

When extracting cell data from Excel/ODS files, the extracted cells are typically
40% of the compressed file size (since the file is ZIP-compressed).

**Returns:**

An estimated capacity for cell value accumulation

**Signature:**

```go
func EstimateSpreadsheetCapacity(fileSize uint64) int
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `FileSize` | `uint64` | Yes | Size of the spreadsheet file (XLSX, ODS, etc.) |

**Returns:** `int`


---

### EstimatePresentationCapacity()

Estimate capacity for slide content extraction from presentations.

PPTX files when extracted have slide content at approximately 35% of the file size.
This accounts for XML overhead, compression, and embedded assets.

**Returns:**

An estimated capacity for slide content accumulation

**Signature:**

```go
func EstimatePresentationCapacity(fileSize uint64) int
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `FileSize` | `uint64` | Yes | Size of the PPTX file in bytes |

**Returns:** `int`


---

### EstimateTableMarkdownCapacity()

Estimate capacity for markdown table generation.

Markdown tables have predictable size: ~12 bytes per cell on average
(accounting for separators, pipes, padding, and cell content).

**Returns:**

An estimated capacity for the markdown table output

**Signature:**

```go
func EstimateTableMarkdownCapacity(rowCount int, colCount int) int
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `RowCount` | `int` | Yes | Number of rows in the table |
| `ColCount` | `int` | Yes | Number of columns in the table |

**Returns:** `int`


---

### ParseEmlContent()

Parse .eml file content (RFC822 format)

**Signature:**

```go
func ParseEmlContent(data []byte) (EmailExtractionResult, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Data` | `[]byte` | Yes | The data |

**Returns:** `EmailExtractionResult`

**Errors:** Returns `error`.


---

### ParseMsgContent()

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

```go
func ParseMsgContent(data []byte, fallbackCodepage uint32) (EmailExtractionResult, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Data` | `[]byte` | Yes | The data |
| `FallbackCodepage` | `*uint32` | No | The fallback codepage |

**Returns:** `EmailExtractionResult`

**Errors:** Returns `error`.


---

### ExtractEmailContent()

Extract email content from either .eml or .msg format

**Signature:**

```go
func ExtractEmailContent(data []byte, mimeType string, fallbackCodepage uint32) (EmailExtractionResult, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Data` | `[]byte` | Yes | The data |
| `MimeType` | `string` | Yes | The mime type |
| `FallbackCodepage` | `*uint32` | No | The fallback codepage |

**Returns:** `EmailExtractionResult`

**Errors:** Returns `error`.


---

### BuildEmailTextOutput()

Build text output from email extraction result

**Signature:**

```go
func BuildEmailTextOutput(result EmailExtractionResult) string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Result` | `EmailExtractionResult` | Yes | The email extraction result |

**Returns:** `string`


---

### ExtractPstMessages()

Extract all email messages from a PST file.

Opens the PST file and traverses the full folder hierarchy, extracting
every message including subject, sender, recipients, and body text.

**Returns:**

A vector of `EmailExtractionResult`, one per message found.

**Errors:**

Returns an error if the PST data cannot be written to a temporary file,
or if the PST format is invalid.

**Signature:**

```go
func ExtractPstMessages(pstData []byte) (VecEmailExtractionResultVecProcessingWarning, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `PstData` | `[]byte` | Yes | Raw bytes of the PST file |

**Returns:** `VecEmailExtractionResultVecProcessingWarning`

**Errors:** Returns `error`.


---

### ReadExcelFile()

**Signature:**

```go
func ReadExcelFile(filePath string) (ExcelWorkbook, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `FilePath` | `string` | Yes | Path to the file |

**Returns:** `ExcelWorkbook`

**Errors:** Returns `error`.


---

### ReadExcelBytes()

**Signature:**

```go
func ReadExcelBytes(data []byte, fileExtension string) (ExcelWorkbook, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Data` | `[]byte` | Yes | The data |
| `FileExtension` | `string` | Yes | The file extension |

**Returns:** `ExcelWorkbook`

**Errors:** Returns `error`.


---

### ExcelToText()

Convert an Excel workbook to plain text (space-separated cells, one row per line).

Each sheet is separated by a blank line. Sheet names are included as headers.
This produces text suitable for quality scoring against ground truth.

**Signature:**

```go
func ExcelToText(workbook ExcelWorkbook) string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Workbook` | `ExcelWorkbook` | Yes | The excel workbook |

**Returns:** `string`


---

### ExcelToMarkdown()

**Signature:**

```go
func ExcelToMarkdown(workbook ExcelWorkbook) string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Workbook` | `ExcelWorkbook` | Yes | The excel workbook |

**Returns:** `string`


---

### ExtractDocText()

Extract text from DOC bytes.

Parses the OLE/CFB compound document, reads the FIB (File Information Block),
and extracts text from the piece table.

**Signature:**

```go
func ExtractDocText(content []byte) (DocExtractionResult, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Content` | `[]byte` | Yes | The content to process |

**Returns:** `DocExtractionResult`

**Errors:** Returns `error`.


---

### ParseDrawing()

Parse a drawing object starting after the `<w:drawing>` Start event.

This function reads events until it encounters the closing `</w:drawing>` tag,
parsing the drawing type (inline or anchored), extent, properties, and image references.

**Signature:**

```go
func ParseDrawing(reader Reader) Drawing
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Reader` | `Reader` | Yes | The reader |

**Returns:** `Drawing`


---

### CollectAndConvertOmathPara()

Collect an `m:oMathPara` subtree and convert to LaTeX (display math).
The reader should be positioned right after the `<m:oMathPara>` start tag.

**Signature:**

```go
func CollectAndConvertOmathPara(reader Reader) string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Reader` | `Reader` | Yes | The reader |

**Returns:** `string`


---

### CollectAndConvertOmath()

Collect an `m:oMath` subtree and convert to LaTeX (inline math).
The reader should be positioned right after the `<m:oMath>` start tag.

**Signature:**

```go
func CollectAndConvertOmath(reader Reader) string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Reader` | `Reader` | Yes | The reader |

**Returns:** `string`


---

### ParseDocument()

Parse a DOCX document from bytes and return the structured document.

**Signature:**

```go
func ParseDocument(bytes []byte) (Document, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Bytes` | `[]byte` | Yes | The bytes |

**Returns:** `Document`

**Errors:** Returns `error`.


---

### ExtractTextFromBytes()

Extract text from DOCX bytes.

**Signature:**

```go
func ExtractTextFromBytes(bytes []byte) (string, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Bytes` | `[]byte` | Yes | The bytes |

**Returns:** `string`

**Errors:** Returns `error`.


---

### ParseSectionProperties()

Parse a `w:sectPr` XML element (roxmltree node) into `SectionProperties`.

**Signature:**

```go
func ParseSectionProperties(node Node) SectionProperties
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Node` | `Node` | Yes | The node |

**Returns:** `SectionProperties`


---

### ParseSectionPropertiesStreaming()

Parse section properties from a quick_xml event stream.

Reads events from the reader until `</w:sectPr>` is encountered,
extracting the same properties as the roxmltree parser.

**Important:** This function advances the reader past the closing `</w:sectPr>` tag.
The caller must not attempt to process the `w:sectPr` end event again.

**Signature:**

```go
func ParseSectionPropertiesStreaming(reader Reader) SectionProperties
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Reader` | `Reader` | Yes | The reader |

**Returns:** `SectionProperties`


---

### ParseStylesXml()

Parse `word/styles.xml` content into a `StyleCatalog`.

Uses `roxmltree` for tree-based XML parsing, consistent with the
office metadata parsing approach used elsewhere in the codebase.

**Signature:**

```go
func ParseStylesXml(xml string) (StyleCatalog, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Xml` | `string` | Yes | The xml |

**Returns:** `StyleCatalog`

**Errors:** Returns `error`.


---

### ParseTableProperties()

Parse table-level properties from streaming XML reader.

Expects the reader to be positioned just after the `<w:tblPr>` start tag.
Reads all child elements until the matching `</w:tblPr>` end tag.

**Signature:**

```go
func ParseTableProperties(reader Reader) TableProperties
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Reader` | `Reader` | Yes | The reader |

**Returns:** `TableProperties`


---

### ParseRowProperties()

Parse row-level properties from streaming XML reader.

Expects the reader to be positioned just after the `<w:trPr>` start tag.

**Signature:**

```go
func ParseRowProperties(reader Reader) RowProperties
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Reader` | `Reader` | Yes | The reader |

**Returns:** `RowProperties`


---

### ParseCellProperties()

Parse cell-level properties from streaming XML reader.

Expects the reader to be positioned just after the `<w:tcPr>` start tag.

**Signature:**

```go
func ParseCellProperties(reader Reader) CellProperties
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Reader` | `Reader` | Yes | The reader |

**Returns:** `CellProperties`


---

### ParseTableGrid()

Parse table grid (column widths) from streaming XML reader.

Expects the reader to be positioned just after the `<w:tblGrid>` start tag.

**Signature:**

```go
func ParseTableGrid(reader Reader) TableGrid
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Reader` | `Reader` | Yes | The reader |

**Returns:** `TableGrid`


---

### ParseThemeXml()

Parse `word/theme/theme1.xml` content into a `Theme`.

Uses `roxmltree` for tree-based XML parsing of DrawingML theme elements.

**Returns:**
* `Ok(Theme)` - The parsed theme
* `Err(KreuzbergError)` - If parsing fails

**Signature:**

```go
func ParseThemeXml(xml string) (Theme, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Xml` | `string` | Yes | The theme XML content as a string |

**Returns:** `Theme`

**Errors:** Returns `error`.


---

### ExtractText()

Extract text from DOCX bytes.

**Signature:**

```go
func ExtractText(bytes []byte) (string, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Bytes` | `[]byte` | Yes | The bytes |

**Returns:** `string`

**Errors:** Returns `error`.


---

### ExtractTextWithPageBreaks()

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

```go
func ExtractTextWithPageBreaks(bytes []byte) (StringOptionVecPageBoundary, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Bytes` | `[]byte` | Yes | The DOCX file contents as bytes |

**Returns:** `StringOptionVecPageBoundary`

**Errors:** Returns `error`.


---

### DetectPageBreaksFromDocx()

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

```go
func DetectPageBreaksFromDocx(bytes []byte) (*[]PageBoundary, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Bytes` | `[]byte` | Yes | The DOCX file contents (ZIP archive) |

**Returns:** `*[]PageBoundary`

**Errors:** Returns `error`.


---

### ExtractOoxmlEmbeddedObjects()

Extract embedded objects from an OOXML ZIP archive and recursively process them.

Scans the given `embeddings_prefix` directory (e.g. `word/embeddings/` or
`ppt/embeddings/`) inside the ZIP archive for embedded files. Known formats
(.xlsx, .pdf, .docx, .pptx, etc.) are recursively extracted. OLE compound
files (oleObject*.bin) are skipped with a warning unless their format can be
identified.

Returns `(children, warnings)` suitable for attaching to `InternalDocument`.

**Signature:**

```go
func ExtractOoxmlEmbeddedObjects(zipBytes []byte, embeddingsPrefix string, sourceLabel string, config ExtractionConfig) VecArchiveEntryVecProcessingWarning
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `ZipBytes` | `[]byte` | Yes | The zip bytes |
| `EmbeddingsPrefix` | `string` | Yes | The embeddings prefix |
| `SourceLabel` | `string` | Yes | The source label |
| `Config` | `ExtractionConfig` | Yes | The configuration options |

**Returns:** `VecArchiveEntryVecProcessingWarning`


---

### DetectImageFormat()

Detect image format from raw bytes using magic byte signatures.

Returns a format string like "jpeg", "png", etc. Used by both DOCX and PPTX extractors.

**Signature:**

```go
func DetectImageFormat(data []byte) Str
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Data` | `[]byte` | Yes | The data |

**Returns:** `Str`


---

### ProcessImagesWithOcr()

Process extracted images with OCR if configured.

For each image, spawns a blocking OCR task and stores the result
in `image.ocr_result`. If OCR is not configured or fails for an
individual image, that image's `ocr_result` remains `nil`.

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

```go
func ProcessImagesWithOcr(images []ExtractedImage, config ExtractionConfig) ([]ExtractedImage, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Images` | `[]ExtractedImage` | Yes | The images |
| `Config` | `ExtractionConfig` | Yes | The configuration options |

**Returns:** `[]ExtractedImage`

**Errors:** Returns `error`.


---

### ExtractPptText()

Extract text from PPT bytes.

Parses the OLE/CFB compound document, reads the "PowerPoint Document" stream,
and extracts text from TextCharsAtom and TextBytesAtom records.

When `include_master_slides` is `true`, master slide content (placeholder text
like "Click to edit Master title style") is included instead of being skipped.

**Signature:**

```go
func ExtractPptText(content []byte) (PptExtractionResult, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Content` | `[]byte` | Yes | The content to process |

**Returns:** `PptExtractionResult`

**Errors:** Returns `error`.


---

### ExtractPptTextWithOptions()

Extract text from PPT bytes with configurable master slide inclusion.

When `include_master_slides` is `true`, `RT_MAIN_MASTER` containers are not
skipped, so master slide placeholder text is included in the output.

**Signature:**

```go
func ExtractPptTextWithOptions(content []byte, includeMasterSlides bool) (PptExtractionResult, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Content` | `[]byte` | Yes | The content to process |
| `IncludeMasterSlides` | `bool` | Yes | The include master slides |

**Returns:** `PptExtractionResult`

**Errors:** Returns `error`.


---

### ExtractPptxFromPath()

Extract PPTX content from a file path.

**Returns:**

A `PptxExtractionResult` containing extracted content, metadata, and images.

**Signature:**

```go
func ExtractPptxFromPath(path string, options PptxExtractionOptions) (PptxExtractionResult, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Path` | `string` | Yes | Path to the PPTX file |
| `Options` | `PptxExtractionOptions` | Yes | Extraction options controlling image extraction, formatting, etc. |

**Returns:** `PptxExtractionResult`

**Errors:** Returns `error`.


---

### ExtractPptxFromBytes()

Extract PPTX content from a byte buffer.

**Returns:**

A `PptxExtractionResult` containing extracted content, metadata, and images.

**Signature:**

```go
func ExtractPptxFromBytes(data []byte, options PptxExtractionOptions) (PptxExtractionResult, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Data` | `[]byte` | Yes | Raw PPTX file bytes |
| `Options` | `PptxExtractionOptions` | Yes | Extraction options controlling image extraction, formatting, etc. |

**Returns:** `PptxExtractionResult`

**Errors:** Returns `error`.


---

### ParseXmlSvg()

Parse XML with optional SVG mode.

In SVG mode, only text from SVG text-bearing elements (`<text>`, `<tspan>`,
`<title>`, `<desc>`, `<textPath>`) is extracted, without element name prefixes.
Attribute values are also omitted in SVG mode.

**Signature:**

```go
func ParseXmlSvg(xmlBytes []byte, preserveWhitespace bool) (XmlExtractionResult, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `XmlBytes` | `[]byte` | Yes | The xml bytes |
| `PreserveWhitespace` | `bool` | Yes | The preserve whitespace |

**Returns:** `XmlExtractionResult`

**Errors:** Returns `error`.


---

### ParseXml()

**Signature:**

```go
func ParseXml(xmlBytes []byte, preserveWhitespace bool) (XmlExtractionResult, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `XmlBytes` | `[]byte` | Yes | The xml bytes |
| `PreserveWhitespace` | `bool` | Yes | The preserve whitespace |

**Returns:** `XmlExtractionResult`

**Errors:** Returns `error`.


---

### CellsToText()

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

```go
func CellsToText(cells [][]string) string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Cells` | `[][]string` | Yes | A slice of vectors representing table rows, where each inner vector contains cell values |

**Returns:** `string`


---

### CellsToMarkdown()

**Signature:**

```go
func CellsToMarkdown(cells [][]string) string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Cells` | `[][]string` | Yes | The cells |

**Returns:** `string`


---

### ParseJotdownAttributes()

Parse jotdown attributes into our Attributes representation.

Converts jotdown's internal attribute representation to Kreuzberg's
standardized Attributes struct, handling IDs, classes, and key-value pairs.

**Signature:**

```go
func ParseJotdownAttributes(attrs Attributes) Attributes
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Attrs` | `Attributes` | Yes | The attributes |

**Returns:** `Attributes`


---

### RenderAttributes()

Render attributes to djot attribute syntax.

Converts Kreuzberg's Attributes struct back to djot attribute syntax:
{.class #id key="value"}

**Signature:**

```go
func RenderAttributes(attrs Attributes) string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Attrs` | `Attributes` | Yes | The attributes |

**Returns:** `string`


---

### DjotContentToDjot()

Convert DjotContent back to djot markup.

This function takes a `DjotContent` structure and generates valid djot markup
from it, preserving:
- Block structure (headings, code blocks, lists, blockquotes, etc.)
- Inline formatting (strong, emphasis, highlight, subscript, superscript, etc.)
- Attributes where present ({.class #id key="value"})

**Returns:**

A String containing valid djot markup

**Signature:**

```go
func DjotContentToDjot(content DjotContent) string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Content` | `DjotContent` | Yes | The DjotContent to convert |

**Returns:** `string`


---

### ExtractionResultToDjot()

Convert any ExtractionResult to djot format.

This function converts an `ExtractionResult` to djot markup:
- If `djot_content` is `Some`, uses `djot_content_to_djot` for full fidelity conversion
- Otherwise, wraps the plain text content in paragraphs

**Returns:**

A `Result` containing the djot markup string

**Signature:**

```go
func ExtractionResultToDjot(result ExtractionResult) (string, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Result` | `ExtractionResult` | Yes | The ExtractionResult to convert |

**Returns:** `string`

**Errors:** Returns `error`.


---

### DjotToHtml()

Render djot content to HTML.

This function takes djot source text and renders it to HTML using jotdown's
built-in HTML renderer.

**Returns:**

A `Result` containing the rendered HTML string

**Signature:**

```go
func DjotToHtml(djotSource string) (string, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `DjotSource` | `string` | Yes | The djot markup text to render |

**Returns:** `string`

**Errors:** Returns `error`.


---

### RenderBlockToDjot()

Render a single block to djot markup.

**Signature:**

```go
func RenderBlockToDjot(output string, block FormattedBlock, indentLevel int)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Output` | `string` | Yes | The output destination |
| `Block` | `FormattedBlock` | Yes | The formatted block |
| `IndentLevel` | `int` | Yes | The indent level |

**Returns:** ``


---

### RenderListItem()

Render a list item with the given marker.

**Signature:**

```go
func RenderListItem(output string, item FormattedBlock, indent string, marker string)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Output` | `string` | Yes | The output destination |
| `Item` | `FormattedBlock` | Yes | The formatted block |
| `Indent` | `string` | Yes | The indent |
| `Marker` | `string` | Yes | The marker |

**Returns:** ``


---

### RenderInlineContent()

Render inline content to djot markup.

**Signature:**

```go
func RenderInlineContent(output string, elements []InlineElement)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Output` | `string` | Yes | The output destination |
| `Elements` | `[]InlineElement` | Yes | The elements |

**Returns:** ``


---

### ExtractFrontmatter()

Extract YAML frontmatter from document content.

Frontmatter is expected to be delimited by `---` or `...` at the start of the document.
This implementation properly handles edge cases:
- `---` appearing within YAML strings or arrays
- Both `---` and `...` as end delimiters (YAML spec compliant)
- Multiline YAML values containing dashes

Returns a tuple of (parsed YAML value, remaining content after frontmatter).

**Signature:**

```go
func ExtractFrontmatter(content string) OptionYamlValueString
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Content` | `string` | Yes | The content to process |

**Returns:** `OptionYamlValueString`


---

### ExtractMetadataFromYaml()

Extract metadata from YAML frontmatter.

Extracts the following YAML fields into Kreuzberg metadata:
- **Standard fields**: title, author, date, description (as subject)
- **Extended fields**: abstract, subject, category, tags, language, version
- **Array fields** (keywords, tags): stored as `Vec<String>` in typed fields

**Returns:**

A `Metadata` struct populated with extracted fields

**Signature:**

```go
func ExtractMetadataFromYaml(yaml YamlValue) Metadata
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Yaml` | `YamlValue` | Yes | The parsed YAML value from frontmatter |

**Returns:** `Metadata`


---

### ExtractTitleFromContent()

Extract first heading as title from content.

Searches for the first level-1 heading (# Title) in the content
and returns it as a potential title if no title was found in frontmatter.

**Returns:**

Some(title) if a heading is found, None otherwise

**Signature:**

```go
func ExtractTitleFromContent(content string) *string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Content` | `string` | Yes | The document content to search |

**Returns:** `*string`


---

### CollectIwaPaths()

Collects all .iwa file paths from a ZIP archive.

Opens the ZIP from `content`, iterates every entry, and returns the names of
all entries whose path ends with `.iwa`. Entries that cannot be read are
silently skipped (consistent with the per-extractor `filter_map` pattern).

**Signature:**

```go
func CollectIwaPaths(content []byte) ([]string, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Content` | `[]byte` | Yes | The content to process |

**Returns:** `[]string`

**Errors:** Returns `error`.


---

### ReadIwaFile()

Read and Snappy-decompress a single `.iwa` file from the ZIP archive.

Apple IWA files use a custom framing format:
Each block in the file is: `[type: u8][length: u24 LE][payload: length bytes]`
- type `0x00`: Snappy-compressed block → decompress payload with raw Snappy
- type `0x01`: Uncompressed block → use payload as-is

Multiple blocks are concatenated to form the decompressed IWA stream.

**Signature:**

```go
func ReadIwaFile(content []byte, path string) ([]byte, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Content` | `[]byte` | Yes | The content to process |
| `Path` | `string` | Yes | Path to the file |

**Returns:** `[]byte`

**Errors:** Returns `error`.


---

### DecodeIwaStream()

Decode an Apple IWA byte stream into the raw protobuf payload.

IWA framing: each block = 1 byte type + 3 bytes LE length + N bytes payload
- type 0x00 → Snappy-compressed, decompress with `snap.raw.Decoder`
- type 0x01 → Uncompressed, use as-is

**Signature:**

```go
func DecodeIwaStream(data []byte) ([]byte, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Data` | `[]byte` | Yes | The data |

**Returns:** `[]byte`

**Errors:** Returns `error`.


---

### ExtractTextFromProto()

Extract all UTF-8 text strings from a raw protobuf byte slice.

This uses a simple wire-format scanner without a full schema:
- Field type 2 (length-delimited) with a valid UTF-8 payload of ≥3 bytes is
  treated as a text string candidate.
- We skip binary blobs (non-UTF-8) and very short noise strings.

This approach avoids the need for `prost-build` and generated proto code while
still extracting human-readable text reliably from iWork documents.

**Signature:**

```go
func ExtractTextFromProto(data []byte) []string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Data` | `[]byte` | Yes | The data |

**Returns:** `[]string`


---

### ExtractTextFromIwaFiles()

Extract all text from an iWork ZIP archive by reading specified IWA entries.

`iwa_paths` should list the IWA file paths to read (e.g. `["Index/Document.iwa"]`).
Returns a flat joined string of all text found across all IWA files.

**Signature:**

```go
func ExtractTextFromIwaFiles(content []byte, iwaPaths []string) (string, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Content` | `[]byte` | Yes | The content to process |
| `IwaPaths` | `[]string` | Yes | The iwa paths |

**Returns:** `string`

**Errors:** Returns `error`.


---

### ExtractMetadataFromZip()

Extract metadata from an iWork ZIP archive.

Attempts to read `Metadata/Properties.plist` and
`Metadata/BuildVersionHistory.plist` from the ZIP. These files are XML plists
containing authorship and creation information. If the files cannot be read
or parsed, an empty `Metadata` is returned.

**Signature:**

```go
func ExtractMetadataFromZip(content []byte) Metadata
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Content` | `[]byte` | Yes | The content to process |

**Returns:** `Metadata`


---

### DedupText()

Deduplicate a list of text strings while preserving order.
Adjacent duplicates and near-duplicates are removed.

**Signature:**

```go
func DedupText(texts []string) []string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Texts` | `[]string` | Yes | The texts |

**Returns:** `[]string`


---

### EnsureInitialized()

Ensure built-in extractors are registered.

This function is called automatically on first extraction operation.
It's safe to call multiple times - registration only happens once,
unless the registry was cleared, in which case extractors are re-registered.

**Signature:**

```go
func EnsureInitialized() error
```

**Returns:** ``

**Errors:** Returns `error`.


---

### RegisterDefaultExtractors()

Register all built-in extractors with the global registry.

This function should be called once at application startup to register
the default extractors (PlainText, Markdown, XML, etc.).

**Note:** This is called automatically on first extraction operation.
Explicit calling is optional.

**Signature:**

```go
func RegisterDefaultExtractors() error
```

**Returns:** ``

**Errors:** Returns `error`.


---

### ExtractPanicMessage()

Extracts a human-readable message from a panic payload.

Attempts to downcast the panic payload to common types (String, &str)
to extract a meaningful error message.

Message is truncated to 4KB to prevent DoS attacks via extremely large panic messages.

**Returns:**

A string representation of the panic message (truncated if necessary)

**Signature:**

```go
func ExtractPanicMessage(panicInfo Any) string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `PanicInfo` | `Any` | Yes | The panic payload from catch_unwind |

**Returns:** `string`


---

### GetOcrBackendRegistry()

Get the global OCR backend registry.

**Signature:**

```go
func GetOcrBackendRegistry() RwLock
```

**Returns:** `RwLock`


---

### GetDocumentExtractorRegistry()

Get the global document extractor registry.

**Signature:**

```go
func GetDocumentExtractorRegistry() RwLock
```

**Returns:** `RwLock`


---

### GetPostProcessorRegistry()

Get the global post-processor registry.

**Signature:**

```go
func GetPostProcessorRegistry() RwLock
```

**Returns:** `RwLock`


---

### GetValidatorRegistry()

Get the global validator registry.

**Signature:**

```go
func GetValidatorRegistry() RwLock
```

**Returns:** `RwLock`


---

### GetRendererRegistry()

Get the global renderer registry.

**Signature:**

```go
func GetRendererRegistry() RwLock
```

**Returns:** `RwLock`


---

### ValidatePluginsAtStartup()

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

```go
func ValidatePluginsAtStartup() (PluginHealthStatus, error)
```

**Returns:** `PluginHealthStatus`

**Errors:** Returns `error`.


---

### SanitizeFilename()

Sanitize a file path to return only the filename (no directory).

Prevents PII from appearing in traces.

**Signature:**

```go
func SanitizeFilename(path string) string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Path` | `string` | Yes | Path to the file |

**Returns:** `string`


---

### GetMetrics()

Get the global extraction metrics, initialising on first call.

Uses the global `opentelemetry.global.meter` to create instruments.

**Signature:**

```go
func GetMetrics() ExtractionMetrics
```

**Returns:** `ExtractionMetrics`


---

### RecordErrorOnCurrentSpan()

Record an error on the current span using semantic conventions.

Sets `otel.status_code = "ERROR"`, `kreuzberg.error.type`, and `error.message`.

**Signature:**

```go
func RecordErrorOnCurrentSpan(error KreuzbergError)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Error` | `KreuzbergError` | Yes | The kreuzberg error |

**Returns:** ``


---

### RecordSuccessOnCurrentSpan()

Record extraction success on the current span.

**Signature:**

```go
func RecordSuccessOnCurrentSpan()
```

**Returns:** ``


---

### SanitizePath()

Sanitize a file path to return only the filename.

Prevents PII (personally identifiable information) from appearing in
traces by only recording filenames instead of full paths.

**Signature:**

```go
func SanitizePath(path string) string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Path` | `string` | Yes | Path to the file |

**Returns:** `string`


---

### ExtractorSpan()

Create an extractor-level span with semantic convention fields.

Returns a `tracing.Span` with all `kreuzberg.extractor.*` and
`kreuzberg.document.*` fields pre-allocated (set to `Empty` for
lazy recording).

**Signature:**

```go
func ExtractorSpan(extractorName string, mimeType string, sizeBytes int) Span
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `ExtractorName` | `string` | Yes | The extractor name |
| `MimeType` | `string` | Yes | The mime type |
| `SizeBytes` | `int` | Yes | The size bytes |

**Returns:** `Span`


---

### PipelineStageSpan()

Create a pipeline stage span.

**Signature:**

```go
func PipelineStageSpan(stage string) Span
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Stage` | `string` | Yes | The stage |

**Returns:** `Span`


---

### PipelineProcessorSpan()

Create a pipeline processor span.

**Signature:**

```go
func PipelineProcessorSpan(stage string, processorName string) Span
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Stage` | `string` | Yes | The stage |
| `ProcessorName` | `string` | Yes | The processor name |

**Returns:** `Span`


---

### OcrSpan()

Create an OCR operation span.

**Signature:**

```go
func OcrSpan(backend string, language string) Span
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Backend` | `string` | Yes | The backend |
| `Language` | `string` | Yes | The language |

**Returns:** `Span`


---

### ModelInferenceSpan()

Create a model inference span.

**Signature:**

```go
func ModelInferenceSpan(modelName string) Span
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `ModelName` | `string` | Yes | The model name |

**Returns:** `Span`


---

### FromUtf8()

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

```go
func FromUtf8(bytes []byte) (string, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Bytes` | `[]byte` | Yes | The byte slice to validate and convert |

**Returns:** `string`

**Errors:** Returns `error`.


---

### StringFromUtf8()

Validates and converts owned bytes to String using SIMD when available.

This function converts bytes to an owned String, validating UTF-8 using SIMD
when available. The caller's bytes are consumed to create the String.

**Returns:**

`Ok(String)` if the bytes are valid UTF-8, `Err(std.string.FromUtf8Error)` otherwise.

# Performance

When enabled, SIMD validation significantly reduces the time spent on validation,
especially for large text documents.

**Signature:**

```go
func StringFromUtf8(bytes []byte) (string, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Bytes` | `[]byte` | Yes | The byte vector to validate and convert |

**Returns:** `string`

**Errors:** Returns `error`.


---

### IsValidUtf8()

Validates bytes as UTF-8 without conversion to string slice.

Returns `true` if the bytes represent valid UTF-8, `false` otherwise.
This is useful when you only need to check validity without constructing a string.

**Returns:**

`true` if valid UTF-8, `false` otherwise.

# Performance

This function is optimized for early exit on invalid sequences.

**Signature:**

```go
func IsValidUtf8(bytes []byte) bool
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Bytes` | `[]byte` | Yes | The byte slice to validate |

**Returns:** `bool`


---

### CalculateQualityScore()

**Signature:**

```go
func CalculateQualityScore(text string, metadata AHashMap) float64
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Text` | `string` | Yes | The text |
| `Metadata` | `*AHashMap` | No | The a hash map |

**Returns:** `float64`


---

### CleanExtractedText()

**Signature:**

```go
func CleanExtractedText(text string) string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Text` | `string` | Yes | The text |

**Returns:** `string`


---

### NormalizeSpaces()

**Signature:**

```go
func NormalizeSpaces(text string) string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Text` | `string` | Yes | The text |

**Returns:** `string`


---

### ReduceTokens()

Reduces token count in text while preserving meaning and structure.

This function removes stopwords, redundancy, and applies compression techniques
based on the specified reduction level. Supports 64 languages with automatic
stopword removal and optional semantic clustering.

**Returns:**

Returns the reduced text with preserved structure (markdown, code blocks).

**Errors:**

Returns an error if the language hint is invalid or stopwords cannot be loaded.

**Signature:**

```go
func ReduceTokens(text string, config TokenReductionConfig, languageHint string) (string, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Text` | `string` | Yes | The input text to reduce |
| `Config` | `TokenReductionConfig` | Yes | Configuration specifying reduction level and options |
| `LanguageHint` | `*string` | No | Optional ISO 639-3 language code (e.g., "eng", "spa") |

**Returns:** `string`

**Errors:** Returns `error`.


---

### BatchReduceTokens()

Reduces token count for multiple texts efficiently using parallel processing.

This function processes multiple texts in parallel using Rayon, providing
significant performance improvements for batch operations. All texts use the
same configuration and language hint for consistency.

**Returns:**

Returns a vector of reduced texts in the same order as the input.

**Errors:**

Returns an error if the language hint is invalid or stopwords cannot be loaded.

**Signature:**

```go
func BatchReduceTokens(texts []string, config TokenReductionConfig, languageHint string) ([]string, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Texts` | `[]string` | Yes | Slice of text references to reduce |
| `Config` | `TokenReductionConfig` | Yes | Configuration specifying reduction level and options |
| `LanguageHint` | `*string` | No | Optional ISO 639-3 language code (e.g., "eng", "spa") |

**Returns:** `[]string`

**Errors:** Returns `error`.


---

### GetReductionStatistics()

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

```go
func GetReductionStatistics(original string, reduced string) F64F64UsizeUsizeUsizeUsize
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Original` | `string` | Yes | The original text before reduction |
| `Reduced` | `string` | Yes | The reduced text after applying token reduction |

**Returns:** `F64F64UsizeUsizeUsizeUsize`


---

### Bold()

Create a bold annotation for the given byte range.

**Signature:**

```go
func Bold(start uint32, end uint32) TextAnnotation
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Start` | `uint32` | Yes | The start |
| `End` | `uint32` | Yes | The end |

**Returns:** `TextAnnotation`


---

### Italic()

Create an italic annotation for the given byte range.

**Signature:**

```go
func Italic(start uint32, end uint32) TextAnnotation
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Start` | `uint32` | Yes | The start |
| `End` | `uint32` | Yes | The end |

**Returns:** `TextAnnotation`


---

### Underline()

Create an underline annotation for the given byte range.

**Signature:**

```go
func Underline(start uint32, end uint32) TextAnnotation
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Start` | `uint32` | Yes | The start |
| `End` | `uint32` | Yes | The end |

**Returns:** `TextAnnotation`


---

### Link()

Create a link annotation for the given byte range.

**Signature:**

```go
func Link(start uint32, end uint32, url string, title string) TextAnnotation
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Start` | `uint32` | Yes | The start |
| `End` | `uint32` | Yes | The end |
| `Url` | `string` | Yes | The URL to fetch |
| `Title` | `*string` | No | The title |

**Returns:** `TextAnnotation`


---

### Code()

Create a code (inline) annotation for the given byte range.

**Signature:**

```go
func Code(start uint32, end uint32) TextAnnotation
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Start` | `uint32` | Yes | The start |
| `End` | `uint32` | Yes | The end |

**Returns:** `TextAnnotation`


---

### Strikethrough()

Create a strikethrough annotation for the given byte range.

**Signature:**

```go
func Strikethrough(start uint32, end uint32) TextAnnotation
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Start` | `uint32` | Yes | The start |
| `End` | `uint32` | Yes | The end |

**Returns:** `TextAnnotation`


---

### Subscript()

Create a subscript annotation for the given byte range.

**Signature:**

```go
func Subscript(start uint32, end uint32) TextAnnotation
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Start` | `uint32` | Yes | The start |
| `End` | `uint32` | Yes | The end |

**Returns:** `TextAnnotation`


---

### Superscript()

Create a superscript annotation for the given byte range.

**Signature:**

```go
func Superscript(start uint32, end uint32) TextAnnotation
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Start` | `uint32` | Yes | The start |
| `End` | `uint32` | Yes | The end |

**Returns:** `TextAnnotation`


---

### FontSize()

Create a font size annotation for the given byte range.

**Signature:**

```go
func FontSize(start uint32, end uint32, value string) TextAnnotation
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Start` | `uint32` | Yes | The start |
| `End` | `uint32` | Yes | The end |
| `Value` | `string` | Yes | The value |

**Returns:** `TextAnnotation`


---

### Color()

Create a color annotation for the given byte range.

**Signature:**

```go
func Color(start uint32, end uint32, value string) TextAnnotation
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Start` | `uint32` | Yes | The start |
| `End` | `uint32` | Yes | The end |
| `Value` | `string` | Yes | The value |

**Returns:** `TextAnnotation`


---

### Highlight()

Create a highlight annotation for the given byte range.

**Signature:**

```go
func Highlight(start uint32, end uint32) TextAnnotation
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Start` | `uint32` | Yes | The start |
| `End` | `uint32` | Yes | The end |

**Returns:** `TextAnnotation`


---

### ClassifyUri()

Classify a URL string into the appropriate `UriKind`.

- `mailto:` → `Email`
- `#` prefix → `Anchor`
- everything else → `Hyperlink`

**Signature:**

```go
func ClassifyUri(url string) UriKind
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Url` | `string` | Yes | The URL to fetch |

**Returns:** `UriKind`


---

### SafeDecode()

Decode raw bytes into UTF-8, using heuristics and fallback encodings when necessary.

The function prefers an explicit `encoding`, falls back to the cached guess, probes
an encoding detector, and finally tries a small curated list before returning a
mojibake-cleaned string.

**Signature:**

```go
func SafeDecode(byteData []byte, encoding string) string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `ByteData` | `[]byte` | Yes | The byte data |
| `Encoding` | `*string` | No | The encoding |

**Returns:** `string`


---

### CalculateTextConfidence()

Estimate how trustworthy a decoded string is on a 0.0–1.0 scale.

Scores close to 1.0 indicate mostly printable characters, whereas lower scores
point to mojibake, control characters, or suspicious character mixes.

**Signature:**

```go
func CalculateTextConfidence(text string) float64
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Text` | `string` | Yes | The text |

**Returns:** `float64`


---

### FixMojibake()

Strip control characters and replacement glyphs that typically arise from mojibake.

**Signature:**

```go
func FixMojibake(text string) Str
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Text` | `string` | Yes | The text |

**Returns:** `Str`


---

### SnakeToCamel()

Recursively convert snake_case keys in a JSON Value to camelCase.

This is used by language bindings (Node.js, Go, Java, C#, etc.) to provide
a consistent camelCase API for consumers even though the Rust core uses snake_case.

**Signature:**

```go
func SnakeToCamel(val Value) Value
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Val` | `Value` | Yes | The value |

**Returns:** `Value`


---

### CamelToSnake()

Recursively convert camelCase keys in a JSON Value to snake_case.

This is the inverse of `snake_to_camel`. Used by WASM bindings to accept
camelCase config from JavaScript while the Rust core expects snake_case.

**Signature:**

```go
func CamelToSnake(val Value) Value
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Val` | `Value` | Yes | The value |

**Returns:** `Value`


---

### CreateStringBufferPool()

Create a pre-configured string buffer pool for batch processing.

**Returns:**

A pool configured for text accumulation with reasonable defaults.

**Signature:**

```go
func CreateStringBufferPool(poolSize int, bufferCapacity int) StringBufferPool
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `PoolSize` | `int` | Yes | Maximum number of buffers to keep in the pool |
| `BufferCapacity` | `int` | Yes | Initial capacity for each buffer in bytes |

**Returns:** `StringBufferPool`


---

### CreateByteBufferPool()

Create a pre-configured byte buffer pool for batch processing.

**Returns:**

A pool configured for binary data handling with reasonable defaults.

**Signature:**

```go
func CreateByteBufferPool(poolSize int, bufferCapacity int) ByteBufferPool
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `PoolSize` | `int` | Yes | Maximum number of buffers to keep in the pool |
| `BufferCapacity` | `int` | Yes | Initial capacity for each buffer in bytes |

**Returns:** `ByteBufferPool`


---

### EstimatePoolSize()

Estimate optimal pool sizing based on file size and document type.

This function uses the file size and MIME type to estimate how many
buffers and what capacity they should have. The estimates are conservative
to avoid starving large document processing.

**Returns:**

A `PoolSizeHint` with recommended pool configuration

**Signature:**

```go
func EstimatePoolSize(fileSize uint64, mimeType string) PoolSizeHint
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `FileSize` | `uint64` | Yes | Size of the file in bytes |
| `MimeType` | `string` | Yes | MIME type of the document (e.g., "application/pdf") |

**Returns:** `PoolSizeHint`


---

### XmlTagName()

Converts XML tag name bytes to a string, avoiding allocation when possible.

**Signature:**

```go
func XmlTagName(name []byte) Str
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Name` | `[]byte` | Yes | The name |

**Returns:** `Str`


---

### EscapeHtmlEntities()

Escape `&`, `<`, and `>` in text destined for markdown/HTML output.

Underscores are intentionally **not** escaped. In extracted PDF text they are
literal content (e.g. identifiers like `CTC_ARP_01`), not markdown italic
delimiters.

Uses a single-pass scan: if no special characters are found, returns a
borrowed `Cow` with no allocation.

**Signature:**

```go
func EscapeHtmlEntities(text string) Str
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Text` | `string` | Yes | The text |

**Returns:** `Str`


---

### NormalizeWhitespace()

Normalizes whitespace by collapsing multiple whitespace characters into single spaces.
Returns Cow.Borrowed if no normalization needed.

**Signature:**

```go
func NormalizeWhitespace(s string) Str
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `S` | `string` | Yes | The s |

**Returns:** `Str`


---

### DetectColumns()

Detect column positions from word x-coordinates.

Groups words by approximate x-position (within `column_threshold` pixels)
and returns the median x-position for each detected column, sorted left to right.

**Signature:**

```go
func DetectColumns(words []HocrWord, columnThreshold uint32) []uint32
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Words` | `[]HocrWord` | Yes | The words |
| `ColumnThreshold` | `uint32` | Yes | The column threshold |

**Returns:** `[]uint32`


---

### DetectRows()

Detect row positions from word y-coordinates.

Groups words by their vertical center position and returns the median
y-position for each detected row. The `row_threshold_ratio` is multiplied
by the median word height to determine the grouping threshold.

**Signature:**

```go
func DetectRows(words []HocrWord, rowThresholdRatio float64) []uint32
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Words` | `[]HocrWord` | Yes | The words |
| `RowThresholdRatio` | `float64` | Yes | The row threshold ratio |

**Returns:** `[]uint32`


---

### ReconstructTable()

Reconstruct a table grid from words with bounding box positions.

Takes detected words and reconstructs a 2D table by:
1. Detecting column positions (grouping by x-coordinate within `column_threshold`)
2. Detecting row positions (grouping by y-center within `row_threshold_ratio` * median height)
3. Assigning words to cells based on closest row/column
4. Combining words within the same cell

Returns a `Vec<Vec<String>>` where each inner `Vec` is a row of cell texts.

**Signature:**

```go
func ReconstructTable(words []HocrWord, columnThreshold uint32, rowThresholdRatio float64) [][]string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Words` | `[]HocrWord` | Yes | The words |
| `ColumnThreshold` | `uint32` | Yes | The column threshold |
| `RowThresholdRatio` | `float64` | Yes | The row threshold ratio |

**Returns:** `[][]string`


---

### TableToMarkdown()

Convert a table grid to markdown format.

The first row is treated as the header row, with a separator line added after it.
Pipe characters in cell content are escaped.

**Signature:**

```go
func TableToMarkdown(table [][]string) string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Table` | `[][]string` | Yes | The table |

**Returns:** `string`


---

### OpenapiJson()

Generate OpenAPI JSON schema.

Returns the complete OpenAPI 3.1 specification as a JSON string.

**Signature:**

```go
func OpenapiJson() string
```

**Returns:** `string`


---

### ValidatePageBoundaries()

Validates the consistency and correctness of page boundaries.

# Validation Rules

1. Boundaries must be sorted by byte_start (monotonically increasing)
2. Boundaries must not overlap (byte_end[i] <= byte_start[i+1])
3. Each boundary must have byte_start < byte_end

**Returns:**

Returns `Ok(())` if all boundaries are valid.
Returns `KreuzbergError.Validation` if any boundary is invalid.

**Signature:**

```go
func ValidatePageBoundaries(boundaries []PageBoundary) error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Boundaries` | `[]PageBoundary` | Yes | Page boundary markers to validate |

**Returns:** ``

**Errors:** Returns `error`.


---

### CalculatePageRange()

Calculate which pages a byte range spans.

**Returns:**

A tuple of (first_page, last_page) where page numbers are 1-indexed.
Returns (None, None) if boundaries are empty or chunk doesn't overlap any page.

**Errors:**

Returns `KreuzbergError.Validation` if boundaries are invalid.

**Signature:**

```go
func CalculatePageRange(byteStart int, byteEnd int, boundaries []PageBoundary) (OptionUsizeOptionUsize, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `ByteStart` | `int` | Yes | Starting byte offset of the chunk |
| `ByteEnd` | `int` | Yes | Ending byte offset of the chunk |
| `Boundaries` | `[]PageBoundary` | Yes | Page boundary markers from the document |

**Returns:** `OptionUsizeOptionUsize`

**Errors:** Returns `error`.


---

### ClassifyChunk()

Classify a single chunk based on its content and optional heading context.

Rules are evaluated in priority order. The first matching rule determines
the returned `ChunkType`. When no rule matches, `ChunkType.Unknown`
is returned.

  (only available when using `ChunkerType.Markdown`).

**Signature:**

```go
func ClassifyChunk(content string, headingContext HeadingContext) ChunkType
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Content` | `string` | Yes | The text content of the chunk (may be trimmed or raw). |
| `HeadingContext` | `*HeadingContext` | No | Optional heading hierarchy this chunk falls under |

**Returns:** `ChunkType`


---

### ChunkText()

Split text into chunks with optional page boundary tracking.

This is the primary API function for chunking text. It supports both plain text
and Markdown with configurable chunk size, overlap, and page boundary mapping.

**Returns:**

A ChunkingResult containing all chunks and their metadata.

**Signature:**

```go
func ChunkText(text string, config ChunkingConfig, pageBoundaries []PageBoundary) (ChunkingResult, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Text` | `string` | Yes | The text to split into chunks |
| `Config` | `ChunkingConfig` | Yes | Chunking configuration (max size, overlap, type) |
| `PageBoundaries` | `*[]PageBoundary` | No | Optional page boundary markers for mapping chunks to pages |

**Returns:** `ChunkingResult`

**Errors:** Returns `error`.


---

### ChunkTextWithHeadingSource()

Chunk text with an optional separate markdown source for heading context resolution.

When `heading_source` is provided, it is used instead of `text` for building the
heading map. This is needed when `text` is plain text (no markdown headings) but
the original document had headings that were stripped during rendering.

**Signature:**

```go
func ChunkTextWithHeadingSource(text string, config ChunkingConfig, pageBoundaries []PageBoundary, headingSource string) (ChunkingResult, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Text` | `string` | Yes | The text |
| `Config` | `ChunkingConfig` | Yes | The configuration options |
| `PageBoundaries` | `*[]PageBoundary` | No | The page boundaries |
| `HeadingSource` | `*string` | No | The heading source |

**Returns:** `ChunkingResult`

**Errors:** Returns `error`.


---

### ChunkTextWithType()

Chunk text with explicit type specification.

This is a convenience function that constructs a ChunkingConfig from individual
parameters and calls `chunk_text`.

**Returns:**

A ChunkingResult containing all chunks and their metadata.

**Signature:**

```go
func ChunkTextWithType(text string, maxCharacters int, overlap int, trim bool, chunkerType ChunkerType) (ChunkingResult, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Text` | `string` | Yes | The text to split into chunks |
| `MaxCharacters` | `int` | Yes | Maximum characters per chunk |
| `Overlap` | `int` | Yes | Character overlap between consecutive chunks |
| `Trim` | `bool` | Yes | Whether to trim whitespace from boundaries |
| `ChunkerType` | `ChunkerType` | Yes | Type of chunker to use (Text or Markdown) |

**Returns:** `ChunkingResult`

**Errors:** Returns `error`.


---

### ChunkTextsBatch()

Batch process multiple texts with the same configuration.

This convenience function applies the same chunking configuration to multiple
texts in sequence.

**Returns:**

A vector of ChunkingResult objects, one per input text.

**Errors:**

Returns an error if chunking any individual text fails.

**Signature:**

```go
func ChunkTextsBatch(texts []string, config ChunkingConfig) ([]ChunkingResult, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Texts` | `[]string` | Yes | Slice of text strings to chunk |
| `Config` | `ChunkingConfig` | Yes | Chunking configuration to apply to all texts |

**Returns:** `[]ChunkingResult`

**Errors:** Returns `error`.


---

### PrecomputeUtf8Boundaries()

Pre-computes valid UTF-8 character boundaries for a text string.

This function performs a single O(n) pass through the text to identify all valid
UTF-8 character boundaries, storing them in a BitVec for O(1) lookups.

**Returns:**

A BitVec where each bit represents whether a byte offset is a valid UTF-8 character boundary.
The BitVec has length `text.len() + 1` (includes the end position).

**Signature:**

```go
func PrecomputeUtf8Boundaries(text string) BitVec
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Text` | `string` | Yes | The text to analyze |

**Returns:** `BitVec`


---

### ValidateUtf8Boundaries()

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

```go
func ValidateUtf8Boundaries(text string, boundaries []PageBoundary) error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Text` | `string` | Yes | The text being chunked |
| `Boundaries` | `[]PageBoundary` | Yes | Page boundary markers to validate |

**Returns:** ``

**Errors:** Returns `error`.


---

### RegisterChunkingProcessor()

Register the chunking processor with the global registry.

This function should be called once at application startup to register
the chunking post-processor.

**Note:** This is called automatically on first use.
Explicit calling is optional.

**Signature:**

```go
func RegisterChunkingProcessor() error
```

**Returns:** ``

**Errors:** Returns `error`.


---

### CreateClient()

Create a liter-llm `DefaultClient` from kreuzberg's `LlmConfig`.

The `model` field from the config is passed as a model hint so that
liter-llm can resolve the correct provider automatically.

When `api_key` is `nil`, liter-llm falls back to the provider's standard
environment variable (e.g., `OPENAI_API_KEY`).

**Signature:**

```go
func CreateClient(config LlmConfig) (DefaultClient, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Config` | `LlmConfig` | Yes | The configuration options |

**Returns:** `DefaultClient`

**Errors:** Returns `error`.


---

### RenderTemplate()

Render a Jinja2 template with the given context variables.

**Signature:**

```go
func RenderTemplate(template string, context Value) (string, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Template` | `string` | Yes | The template |
| `Context` | `Value` | Yes | The value |

**Returns:** `string`

**Errors:** Returns `error`.


---

### ExtractStructured()

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

```go
func ExtractStructured(content string, config StructuredExtractionConfig) (LlmUsage, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Content` | `string` | Yes | The extracted document text to send to the LLM. |
| `Config` | `StructuredExtractionConfig` | Yes | Structured extraction configuration including schema and LLM settings. |

**Returns:** `LlmUsage`

**Errors:** Returns `error`.


---

### VlmOcr()

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

```go
func VlmOcr(imageBytes []byte, imageMimeType string, language string, config LlmConfig) (LlmUsage, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `ImageBytes` | `[]byte` | Yes | Raw image data (JPEG, PNG, WebP, etc.) |
| `ImageMimeType` | `string` | Yes | MIME type of the image (e.g., `"image/png"`) |
| `Language` | `string` | Yes | ISO 639 language code or Tesseract language name |
| `Config` | `LlmConfig` | Yes | LLM provider/model configuration |

**Returns:** `LlmUsage`

**Errors:** Returns `error`.


---

### Normalize()

L2-normalize a vector.

**Signature:**

```go
func Normalize(v []float32) []float32
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `V` | `[]float32` | Yes | The v |

**Returns:** `[]float32`


---

### GetPreset()

Get a preset by name.

**Signature:**

```go
func GetPreset(name string) *EmbeddingPreset
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Name` | `string` | Yes | The name |

**Returns:** `*EmbeddingPreset`


---

### ListPresets()

List all available preset names.

**Signature:**

```go
func ListPresets() []string
```

**Returns:** `[]string`


---

### WarmModel()

Eagerly download and cache an embedding model without returning the handle.

This triggers the same download and initialization as `get_or_init_engine`
but discards the result, making it suitable for cache-warming scenarios
where the caller doesn't need to use the model immediately.

**Note**: This function downloads AND initializes the ONNX model, which
requires ONNX Runtime and uses significant memory. For download-only
scenarios (e.g., init containers), use `download_model` instead.

**Signature:**

```go
func WarmModel(modelType EmbeddingModelType, cacheDir string) error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `ModelType` | `EmbeddingModelType` | Yes | The embedding model type |
| `CacheDir` | `*string` | No | The cache dir |

**Returns:** ``

**Errors:** Returns `error`.


---

### DownloadModel()

Download an embedding model's files without initializing ONNX Runtime.

Downloads the model files (ONNX model, tokenizer, config) from HuggingFace
to the cache directory. Subsequent calls to `warm_model` or
`get_or_init_engine` will find the files cached and skip the download step.

This is ideal for init containers or CI environments where you want to
pre-populate the cache without loading models into memory.

**Signature:**

```go
func DownloadModel(modelType EmbeddingModelType, cacheDir string) error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `ModelType` | `EmbeddingModelType` | Yes | The embedding model type |
| `CacheDir` | `*string` | No | The cache dir |

**Returns:** ``

**Errors:** Returns `error`.


---

### GenerateEmbeddingsForChunks()

Generate embeddings for text chunks using the specified configuration.

This function modifies chunks in-place, populating their `embedding` field
with generated embedding vectors. It uses batch processing for efficiency.

**Returns:**

Returns `Ok(())` if embeddings were generated successfully, or an error if
model initialization or embedding generation fails.

**Signature:**

```go
func GenerateEmbeddingsForChunks(chunks []Chunk, config EmbeddingConfig) error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Chunks` | `[]Chunk` | Yes | Mutable reference to vector of chunks to generate embeddings for |
| `Config` | `EmbeddingConfig` | Yes | Embedding configuration specifying model and parameters |

**Returns:** ``

**Errors:** Returns `error`.


---

### CalculateSmartDpi()

Calculate smart DPI based on page dimensions, memory constraints, and target DPI

**Signature:**

```go
func CalculateSmartDpi(pageWidth float64, pageHeight float64, targetDpi int32, maxDimension int32, maxMemoryMb float64) int32
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `PageWidth` | `float64` | Yes | The page width |
| `PageHeight` | `float64` | Yes | The page height |
| `TargetDpi` | `int32` | Yes | The target dpi |
| `MaxDimension` | `int32` | Yes | The max dimension |
| `MaxMemoryMb` | `float64` | Yes | The max memory mb |

**Returns:** `int32`


---

### CalculateOptimalDpi()

Calculate optimal DPI with min/max constraints

**Signature:**

```go
func CalculateOptimalDpi(pageWidth float64, pageHeight float64, targetDpi int32, maxDimension int32, minDpi int32, maxDpi int32) int32
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `PageWidth` | `float64` | Yes | The page width |
| `PageHeight` | `float64` | Yes | The page height |
| `TargetDpi` | `int32` | Yes | The target dpi |
| `MaxDimension` | `int32` | Yes | The max dimension |
| `MinDpi` | `int32` | Yes | The min dpi |
| `MaxDpi` | `int32` | Yes | The max dpi |

**Returns:** `int32`


---

### NormalizeImageDpi()

Normalize image DPI based on extraction configuration

**Returns:**
* `NormalizeResult` containing processed image data and metadata

**Signature:**

```go
func NormalizeImageDpi(rgbData []byte, width int, height int, config ExtractionConfig, currentDpi float64) (NormalizeResult, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `RgbData` | `[]byte` | Yes | RGB image data as a flat `Vec<u8>` (height * width * 3 bytes, row-major) |
| `Width` | `int` | Yes | Image width in pixels |
| `Height` | `int` | Yes | Image height in pixels |
| `Config` | `ExtractionConfig` | Yes | Extraction configuration containing DPI settings |
| `CurrentDpi` | `*float64` | No | Optional current DPI of the image (defaults to 72 if None) |

**Returns:** `NormalizeResult`

**Errors:** Returns `error`.


---

### ResizeImage()

Resize an image using fast_image_resize with appropriate algorithm based on scale factor

**Signature:**

```go
func ResizeImage(image DynamicImage, newWidth uint32, newHeight uint32, scaleFactor float64) (DynamicImage, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Image` | `DynamicImage` | Yes | The dynamic image |
| `NewWidth` | `uint32` | Yes | The new width |
| `NewHeight` | `uint32` | Yes | The new height |
| `ScaleFactor` | `float64` | Yes | The scale factor |

**Returns:** `DynamicImage`

**Errors:** Returns `error`.


---

### DetectLanguages()

Detect languages in text using whatlang.

Returns a list of detected language codes (ISO 639-3 format).
Returns `nil` if no languages could be detected with sufficient confidence.

**Signature:**

```go
func DetectLanguages(text string, config LanguageDetectionConfig) (*[]string, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Text` | `string` | Yes | The text to analyze for language detection |
| `Config` | `LanguageDetectionConfig` | Yes | Optional configuration for language detection |

**Returns:** `*[]string`

**Errors:** Returns `error`.


---

### RegisterLanguageDetectionProcessor()

Register the language detection processor with the global registry.

This function should be called once at application startup to register
the language detection post-processor.

**Note:** This is called automatically on first use.
Explicit calling is optional.

**Signature:**

```go
func RegisterLanguageDetectionProcessor() error
```

**Returns:** ``

**Errors:** Returns `error`.


---

### GetStopwords()

Get stopwords for a language with normalization.

This function provides a user-friendly interface to the stopwords registry with:
- **Case-insensitive lookup**: "EN", "en", "En" all work
- **Locale normalization**: "en-US", "en_GB", "es-ES" extract to "en", "es"
- **Consistent behavior**: Returns `nil` for unsupported languages

# Language Code Format

Accepts multiple formats:
- ISO 639-1 two-letter codes: `"en"`, `"es"`, `"de"`, etc.
- Uppercase variants: `"EN"`, `"ES"`, `"DE"`
- Locale codes with hyphen: `"en-US"`, `"es-ES"`, `"pt-BR"`
- Locale codes with underscore: `"en_US"`, `"es_ES"`, `"pt_BR"`

All formats are normalized to lowercase two-letter ISO 639-1 codes.

**Returns:**

- `Some(&HashSet<String>)` if the language is supported (64 languages available)
- `nil` if the language is not supported

# Performance

This function performs two operations:
1. String normalization (lowercase + truncate) - O(1) for typical language codes
2. HashMap lookup in STOPWORDS - O(1) average case

Total overhead is negligible (~10-50ns on modern CPUs).

**Signature:**

```go
func GetStopwords(lang string) *AHashSet
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Lang` | `string` | Yes | The lang |

**Returns:** `*AHashSet`


---

### GetStopwordsWithFallback()

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
- `nil` if neither language is supported

# Common Patterns


# Performance

This function performs at most two HashMap lookups:
1. Try primary language (O(1) average case)
2. If None, try fallback language (O(1) average case)

Total overhead is negligible (~10-100ns on modern CPUs).

**Signature:**

```go
func GetStopwordsWithFallback(language string, fallback string) *AHashSet
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Language` | `string` | Yes | Primary language code to try first |
| `Fallback` | `string` | Yes | Fallback language code to use if primary not available |

**Returns:** `*AHashSet`


---

### ExtractKeywords()

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

```go
func ExtractKeywords(text string, config KeywordConfig) ([]Keyword, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Text` | `string` | Yes | The text to extract keywords from |
| `Config` | `KeywordConfig` | Yes | Keyword extraction configuration |

**Returns:** `[]Keyword`

**Errors:** Returns `error`.


---

### RegisterKeywordProcessor()

Register the keyword extraction processor with the global registry.

This function should be called once at application startup to register
the keyword extraction post-processor.

**Note:** This is called automatically on first use.
Explicit calling is optional.

**Signature:**

```go
func RegisterKeywordProcessor() error
```

**Returns:** ``

**Errors:** Returns `error`.


---

### TextBlockToElement()

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

```go
func TextBlockToElement(block TextBlock, pageNumber int) (*OcrElement, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Block` | `TextBlock` | Yes | PaddleOCR TextBlock containing OCR results |
| `PageNumber` | `int` | Yes | 1-indexed page number |

**Returns:** `*OcrElement`

**Errors:** Returns `error`.


---

### TsvRowToElement()

Convert a Tesseract TSV row to a unified OcrElement.

Preserves:
- Axis-aligned bounding box
- Recognition confidence (Tesseract doesn't have separate detection confidence)
- Hierarchical level information

**Returns:**

An `OcrElement` with rectangle geometry and Tesseract metadata.

**Signature:**

```go
func TsvRowToElement(row TsvRow) OcrElement
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Row` | `TsvRow` | Yes | Parsed TSV row from Tesseract output |

**Returns:** `OcrElement`


---

### IteratorWordToElement()

Convert a Tesseract iterator WordData to a unified OcrElement with rich metadata.

Unlike `tsv_row_to_element` which only has text, bbox, and confidence,
this populates font attributes (bold, italic, monospace, pointsize) and
block/paragraph context from the Tesseract layout analysis.

**Returns:**

An `OcrElement` at `Word` level with all available font and layout metadata.

**Signature:**

```go
func IteratorWordToElement(word WordData, blockType TessPolyBlockType, paraInfo ParaInfo, pageNumber int) OcrElement
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Word` | `WordData` | Yes | WordData from the Tesseract result iterator |
| `BlockType` | `*TessPolyBlockType` | No | Optional block type from Tesseract layout analysis |
| `ParaInfo` | `*ParaInfo` | No | Optional paragraph metadata (justification, list item flag) |
| `PageNumber` | `int` | Yes | 1-indexed page number |

**Returns:** `OcrElement`


---

### ElementToHocrWord()

Convert an OcrElement to an HocrWord for table reconstruction.

This enables reuse of the existing table detection algorithms from
html-to-markdown-rs with PaddleOCR results.

**Returns:**

An `HocrWord` suitable for table reconstruction algorithms.

**Signature:**

```go
func ElementToHocrWord(element OcrElement) HocrWord
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Element` | `OcrElement` | Yes | Unified OCR element with geometry and text |

**Returns:** `HocrWord`


---

### ElementsToHocrWords()

Convert a vector of OcrElements to HocrWords for batch table processing.

Filters to word-level elements only, as table reconstruction
works best with word-level granularity.

**Returns:**

A vector of HocrWords filtered by confidence and element level.

**Signature:**

```go
func ElementsToHocrWords(elements []OcrElement, minConfidence float64) []HocrWord
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Elements` | `[]OcrElement` | Yes | Slice of OCR elements to convert |
| `MinConfidence` | `float64` | Yes | Minimum recognition confidence threshold (0.0-1.0) |

**Returns:** `[]HocrWord`


---

### ParseHocrToInternalDocument()

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

```go
func ParseHocrToInternalDocument(hocrHtml string) InternalDocument
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `HocrHtml` | `string` | Yes | The hocr html |

**Returns:** `InternalDocument`


---

### AssembleOcrMarkdown()

Assemble structured markdown from OCR elements using layout detection results.

Both inputs must be in the same pixel coordinate space (from the same
rendered page image). Returns plain text join when `detection` is `nil`.

`recognized_tables` provides pre-computed markdown for Table regions
(from TATR or other table structure recognizer). When empty, Table
regions fall back to heuristic grid reconstruction from OCR elements.

**Signature:**

```go
func AssembleOcrMarkdown(elements []OcrElement, detection DetectionResult, imgWidth uint32, imgHeight uint32, recognizedTables []RecognizedTable) string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Elements` | `[]OcrElement` | Yes | The elements |
| `Detection` | `*DetectionResult` | No | The detection result |
| `ImgWidth` | `uint32` | Yes | The img width |
| `ImgHeight` | `uint32` | Yes | The img height |
| `RecognizedTables` | `[]RecognizedTable` | Yes | The recognized tables |

**Returns:** `string`


---

### RecognizePageTables()

Run TATR table recognition for all Table regions in a page.

For each Table detection, crops the page image, runs TATR inference,
matches OCR elements to cells, and produces markdown tables.

**Signature:**

```go
func RecognizePageTables(pageImage RgbImage, detection DetectionResult, elements []OcrElement, tatrModel TatrModel) []RecognizedTable
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `PageImage` | `RgbImage` | Yes | The rgb image |
| `Detection` | `DetectionResult` | Yes | The detection result |
| `Elements` | `[]OcrElement` | Yes | The elements |
| `TatrModel` | `TatrModel` | Yes | The tatr model |

**Returns:** `[]RecognizedTable`


---

### ExtractWordsFromTsv()

Extract words from Tesseract TSV output and convert to HocrWord format.

This parses Tesseract's TSV format (level, page_num, block_num, ...) and
converts it to the HocrWord format used for table reconstruction.

**Signature:**

```go
func ExtractWordsFromTsv(tsvData string, minConfidence float64) ([]HocrWord, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `TsvData` | `string` | Yes | The tsv data |
| `MinConfidence` | `float64` | Yes | The min confidence |

**Returns:** `[]HocrWord`

**Errors:** Returns `error`.


---

### ComputeHash()

Compute a blake3 hash string from input data.

Returns a 32-character hex string (128 bits of blake3 output).

**Signature:**

```go
func ComputeHash(data string) string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Data` | `string` | Yes | The data |

**Returns:** `string`


---

### ValidateLanguageCode()

**Signature:**

```go
func ValidateLanguageCode(langCode string) error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `LangCode` | `string` | Yes | The lang code |

**Returns:** ``

**Errors:** Returns `error`.


---

### ValidateTesseractVersion()

**Signature:**

```go
func ValidateTesseractVersion(version uint32) error
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Version` | `uint32` | Yes | The version |

**Returns:** ``

**Errors:** Returns `error`.


---

### EnsureOrtAvailable()

Ensure ONNX Runtime is discoverable. Safe to call multiple times (no-op after first).

When the `ort-bundled` feature is enabled the ORT binaries are embedded via the
official Microsoft release and no system library search is needed.

**Signature:**

```go
func EnsureOrtAvailable()
```

**Returns:** ``


---

### IsLanguageSupported()

Check if a language code is supported by PaddleOCR.

**Signature:**

```go
func IsLanguageSupported(lang string) bool
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Lang` | `string` | Yes | The lang |

**Returns:** `bool`


---

### LanguageToScriptFamily()

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

```go
func LanguageToScriptFamily(paddleLang string) string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `PaddleLang` | `string` | Yes | The paddle lang |

**Returns:** `string`


---

### MapLanguageCode()

Map Kreuzberg language codes to PaddleOCR language codes.

**Signature:**

```go
func MapLanguageCode(kreuzbergCode string) *string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `KreuzbergCode` | `string` | Yes | The kreuzberg code |

**Returns:** `*string`


---

### ResolveCacheDir()

Resolve the cache directory for the auto-rotate model.

**Signature:**

```go
func ResolveCacheDir() string
```

**Returns:** `string`


---

### DetectAndRotate()

Detect orientation and return a corrected image if rotation is needed.

Returns `Ok(Some(rotated_bytes))` if rotation was applied,
`Ok(None)` if no rotation needed (0° or low confidence).

**Signature:**

```go
func DetectAndRotate(detector DocOrientationDetector, imageBytes []byte) (*[]byte, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Detector` | `DocOrientationDetector` | Yes | The doc orientation detector |
| `ImageBytes` | `[]byte` | Yes | The image bytes |

**Returns:** `*[]byte`

**Errors:** Returns `error`.


---

### BuildCellGrid()

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

```go
func BuildCellGrid(result TatrResult, tableBbox F324) [][]CellBBox
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Result` | `TatrResult` | Yes | The tatr result |
| `TableBbox` | `*F324` | No | The [f32;4] |

**Returns:** `[][]CellBBox`


---

### ApplyHeuristics()

Apply Docling-style postprocessing heuristics to raw detections.

This implements the key heuristics from `docling/utils/layout_postprocessor.py`:
1. Per-class confidence thresholds
2. Full-page picture removal (>90% page area)
3. Overlap resolution (IoU > 0.8 or containment > 0.8)
4. Cross-type overlap handling (KVR vs Table)

**Signature:**

```go
func ApplyHeuristics(detections []LayoutDetection, pageWidth float32, pageHeight float32)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Detections` | `[]LayoutDetection` | Yes | The detections |
| `PageWidth` | `float32` | Yes | The page width |
| `PageHeight` | `float32` | Yes | The page height |

**Returns:** ``


---

### GreedyNms()

Standard greedy Non-Maximum Suppression.

Sorts detections by confidence (descending), then iteratively removes
detections that have IoU > `iou_threshold` with any higher-confidence detection.

This is required for YOLO models. RT-DETR is NMS-free.

**Signature:**

```go
func GreedyNms(detections []LayoutDetection, iouThreshold float32)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Detections` | `[]LayoutDetection` | Yes | The detections |
| `IouThreshold` | `float32` | Yes | The iou threshold |

**Returns:** ``


---

### PreprocessImagenet()

Preprocess an image for models using ImageNet normalization (e.g., RT-DETR).

Pipeline: resize to target_size x target_size (bilinear) -> rescale /255 -> ImageNet normalize -> NCHW f32.

Uses a single vectorized pass over contiguous pixel data for maximum throughput.

**Signature:**

```go
func PreprocessImagenet(img RgbImage, targetSize uint32) Array4
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Img` | `RgbImage` | Yes | The rgb image |
| `TargetSize` | `uint32` | Yes | The target size |

**Returns:** `Array4`


---

### PreprocessImagenetLetterbox()

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

```go
func PreprocessImagenetLetterbox(img RgbImage, targetSize uint32) Array4F32F32U32U32
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Img` | `RgbImage` | Yes | The rgb image |
| `TargetSize` | `uint32` | Yes | The target size |

**Returns:** `Array4F32F32U32U32`


---

### PreprocessRescale()

Preprocess with rescale only (no ImageNet normalization).

Pipeline: resize to target_size x target_size -> rescale /255 -> NCHW f32.

**Signature:**

```go
func PreprocessRescale(img RgbImage, targetSize uint32) Array4
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Img` | `RgbImage` | Yes | The rgb image |
| `TargetSize` | `uint32` | Yes | The target size |

**Returns:** `Array4`


---

### PreprocessLetterbox()

Letterbox preprocessing for YOLOX-style models.

Resizes the image to fit within (target_width x target_height) while maintaining
aspect ratio, padding the remaining area with value 114.0 (raw pixel value).
No normalization — values are 0-255 as YOLOX expects.

Returns the NCHW tensor and the scale ratio (for rescaling detections back).

**Signature:**

```go
func PreprocessLetterbox(img RgbImage, targetWidth uint32, targetHeight uint32) Array4F32F32
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Img` | `RgbImage` | Yes | The rgb image |
| `TargetWidth` | `uint32` | Yes | The target width |
| `TargetHeight` | `uint32` | Yes | The target height |

**Returns:** `Array4F32F32`


---

### BuildSession()

Build an optimized ORT session from an ONNX model file.

`thread_budget` controls the number of intra-op threads for this session.
Pass the result of `crate.core.config.concurrency.resolve_thread_budget`
to respect the user's `ConcurrencyConfig`.

When `accel` is `nil` or `Auto`, uses platform defaults:
- macOS: CoreML (Neural Engine / GPU)
- Linux: CUDA (GPU)
- Others: CPU only

ORT silently falls back to CPU if the requested EP is unavailable.

**Signature:**

```go
func BuildSession(path string, accel AccelerationConfig, threadBudget int) (Session, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Path` | `string` | Yes | Path to the file |
| `Accel` | `*AccelerationConfig` | No | The acceleration config |
| `ThreadBudget` | `int` | Yes | The thread budget |

**Returns:** `Session`

**Errors:** Returns `error`.


---

### ConfigFromExtraction()

Convert a `LayoutDetectionConfig` into a `LayoutEngineConfig`.

**Signature:**

```go
func ConfigFromExtraction(layoutConfig LayoutDetectionConfig) LayoutEngineConfig
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `LayoutConfig` | `LayoutDetectionConfig` | Yes | The layout detection config |

**Returns:** `LayoutEngineConfig`


---

### CreateEngine()

Create a `LayoutEngine` from a `LayoutDetectionConfig`.

Ensures ORT is available, then creates the engine with model download.

**Signature:**

```go
func CreateEngine(layoutConfig LayoutDetectionConfig) (LayoutEngine, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `LayoutConfig` | `LayoutDetectionConfig` | Yes | The layout detection config |

**Returns:** `LayoutEngine`

**Errors:** Returns `error`.


---

### TakeOrCreateEngine()

Take the cached layout engine, or create a new one if the cache is empty.

The caller owns the engine for the duration of its work and should
return it via `return_engine` when done. This avoids holding the
global mutex during inference.

**Signature:**

```go
func TakeOrCreateEngine(layoutConfig LayoutDetectionConfig) (LayoutEngine, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `LayoutConfig` | `LayoutDetectionConfig` | Yes | The layout detection config |

**Returns:** `LayoutEngine`

**Errors:** Returns `error`.


---

### ReturnEngine()

Return a layout engine to the global cache for reuse by future extractions.

**Signature:**

```go
func ReturnEngine(engine LayoutEngine)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Engine` | `LayoutEngine` | Yes | The layout engine |

**Returns:** ``


---

### TakeOrCreateTatr()

Take the cached TATR model, or create a new one if the cache is empty.

Returns `nil` if the model cannot be loaded. Once a load attempt fails,
subsequent calls return `nil` immediately without retrying, avoiding
repeated download attempts and redundant warning logs.

**Signature:**

```go
func TakeOrCreateTatr() *TatrModel
```

**Returns:** `*TatrModel`


---

### ReturnTatr()

Return a TATR model to the global cache for reuse.

**Signature:**

```go
func ReturnTatr(model TatrModel)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Model` | `TatrModel` | Yes | The tatr model |

**Returns:** ``


---

### TakeOrCreateSlanet()

Take a cached SLANeXT model for the given variant, or create a new one.

**Signature:**

```go
func TakeOrCreateSlanet(variant string) *SlanetModel
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Variant` | `string` | Yes | The variant |

**Returns:** `*SlanetModel`


---

### ReturnSlanet()

Return a SLANeXT model to the global cache for reuse.

**Signature:**

```go
func ReturnSlanet(variant string, model SlanetModel)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Variant` | `string` | Yes | The variant |
| `Model` | `SlanetModel` | Yes | The slanet model |

**Returns:** ``


---

### TakeOrCreateTableClassifier()

Take a cached table classifier, or create a new one.

**Signature:**

```go
func TakeOrCreateTableClassifier() *TableClassifier
```

**Returns:** `*TableClassifier`


---

### ReturnTableClassifier()

Return a table classifier to the global cache for reuse.

**Signature:**

```go
func ReturnTableClassifier(model TableClassifier)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Model` | `TableClassifier` | Yes | The table classifier |

**Returns:** ``


---

### ExtractAnnotationsFromDocument()

Extract annotations from all pages of a PDF document.

Iterates over every page and every annotation on each page, mapping
pdfium annotation subtypes to `PdfAnnotationType` and collecting
content text and bounding boxes where available.

Annotations that cannot be read are silently skipped.

**Returns:**

A `Vec<PdfAnnotation>` containing all successfully extracted annotations.

**Signature:**

```go
func ExtractAnnotationsFromDocument(document PdfDocument) []PdfAnnotation
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Document` | `PdfDocument` | Yes | A reference to the loaded pdfium `PdfDocument`. |

**Returns:** `[]PdfAnnotation`


---

### ExtractBookmarks()

Extract bookmarks (outlines) from a PDF document loaded via lopdf.

Walks the `/Outlines` tree in the document catalog, collecting each bookmark's
title and destination. Returns an empty `Vec` if the document has no outlines.

**Signature:**

```go
func ExtractBookmarks(document Document) []Uri
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Document` | `Document` | Yes | The document |

**Returns:** `[]Uri`


---

### ExtractBundledPdfium()

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

```go
func ExtractBundledPdfium() (string, error)
```

**Returns:** `string`

**Errors:** Returns `error`.


---

### ExtractEmbeddedFiles()

Extract embedded file descriptors from a PDF document loaded via lopdf.

Walks the `/Names` → `/EmbeddedFiles` name tree in the catalog.
Returns an empty `Vec` if the document has no embedded files.

**Signature:**

```go
func ExtractEmbeddedFiles(document Document) []EmbeddedFile
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Document` | `Document` | Yes | The document |

**Returns:** `[]EmbeddedFile`


---

### ExtractAndProcessEmbeddedFiles()

Extract embedded files from PDF bytes and recursively process them.

Returns `(children, warnings)`. The children are `ArchiveEntry` values
suitable for attaching to `InternalDocument.children`.

**Signature:**

```go
func ExtractAndProcessEmbeddedFiles(pdfBytes []byte, config ExtractionConfig) VecArchiveEntryVecProcessingWarning
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `PdfBytes` | `[]byte` | Yes | The pdf bytes |
| `Config` | `ExtractionConfig` | Yes | The configuration options |

**Returns:** `VecArchiveEntryVecProcessingWarning`


---

### InitializeFontCache()

Initialize the global font cache.

On first call, discovers and loads all system fonts. Subsequent calls are no-ops.
Caching is thread-safe via RwLock; concurrent reads during PDF processing are efficient.

**Returns:**

Ok if initialization succeeds or cache is already initialized, or PdfError if font discovery fails.

# Performance

- First call: 50-100ms (system font discovery + loading)
- Subsequent calls: < 1μs (no-op, just checks initialized flag)

**Signature:**

```go
func InitializeFontCache() error
```

**Returns:** ``

**Errors:** Returns `error`.


---

### GetFontDescriptors()

Get cached font descriptors for Pdfium configuration.

Ensures the font cache is initialized, then returns font descriptors
derived from the cached fonts. This call is fast after the first invocation.

**Returns:**

A Vec of FontDescriptor objects suitable for `PdfiumConfig.set_font_provider()`.

# Performance

- First call: ~50-100ms (includes font discovery)
- Subsequent calls: < 1ms (reads from cache)

**Signature:**

```go
func GetFontDescriptors() ([]FontDescriptor, error)
```

**Returns:** `[]FontDescriptor`

**Errors:** Returns `error`.


---

### CachedFontCount()

Get the number of cached fonts.

Useful for diagnostics and testing.

**Returns:**

Number of fonts in the cache, or 0 if not initialized.

**Signature:**

```go
func CachedFontCount() int
```

**Returns:** `int`


---

### ClearFontCache()

Clear the font cache (for testing purposes).

**Panics:**

Panics if the cache lock is poisoned, which should only happen in test scenarios
with deliberate panic injection.

**Signature:**

```go
func ClearFontCache()
```

**Returns:** ``


---

### ExtractImagesFromPdf()

**Signature:**

```go
func ExtractImagesFromPdf(pdfBytes []byte) ([]PdfImage, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `PdfBytes` | `[]byte` | Yes | The pdf bytes |

**Returns:** `[]PdfImage`

**Errors:** Returns `error`.


---

### ExtractImagesFromPdfWithPassword()

**Signature:**

```go
func ExtractImagesFromPdfWithPassword(pdfBytes []byte, password string) ([]PdfImage, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `PdfBytes` | `[]byte` | Yes | The pdf bytes |
| `Password` | `string` | Yes | The password |

**Returns:** `[]PdfImage`

**Errors:** Returns `error`.


---

### ReextractRawImagesViaPdfium()

Re-extract images that have unusable formats (`"raw"`, `"ccitt"`, `"jbig2"`) by
rendering them through pdfium's bitmap pipeline, which handles all PDF filter
chains internally.

Returns the number of images successfully re-extracted.

**Signature:**

```go
func ReextractRawImagesViaPdfium(pdfBytes []byte, images []PdfImage) (uint32, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `PdfBytes` | `[]byte` | Yes | The pdf bytes |
| `Images` | `[]PdfImage` | Yes | The images |

**Returns:** `uint32`

**Errors:** Returns `error`.


---

### DetectLayoutForDocument()

Run layout detection on all pages of a PDF document.

Under the hood, this uses batched layout detection to prevent holding too many
full-resolution page images in memory simultaneously before detection.

**Signature:**

```go
func DetectLayoutForDocument(pdfBytes []byte, engine LayoutEngine) (DynamicImage, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `PdfBytes` | `[]byte` | Yes | The pdf bytes |
| `Engine` | `LayoutEngine` | Yes | The layout engine |

**Returns:** `DynamicImage`

**Errors:** Returns `error`.


---

### DetectLayoutForImages()

Run layout detection on pre-rendered images.

Returns pixel-space `DetectionResult`s — no PDF coordinate conversion.
Use this when images are already available (e.g., from the OCR rendering
path) to avoid redundant PDF re-rendering.

**Signature:**

```go
func DetectLayoutForImages(images []DynamicImage, engine LayoutEngine) ([]DetectionResult, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Images` | `[]DynamicImage` | Yes | The images |
| `Engine` | `LayoutEngine` | Yes | The layout engine |

**Returns:** `[]DetectionResult`

**Errors:** Returns `error`.


---

### ExtractMetadata()

Extract PDF-specific metadata from raw bytes.

Returns only PDF-specific metadata (version, producer, encryption status, dimensions).

**Signature:**

```go
func ExtractMetadata(pdfBytes []byte) (PdfMetadata, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `PdfBytes` | `[]byte` | Yes | The pdf bytes |

**Returns:** `PdfMetadata`

**Errors:** Returns `error`.


---

### ExtractMetadataWithPassword()

Extract PDF-specific metadata from raw bytes with optional password.

Returns only PDF-specific metadata (version, producer, encryption status, dimensions).

**Signature:**

```go
func ExtractMetadataWithPassword(pdfBytes []byte, password string) (PdfMetadata, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `PdfBytes` | `[]byte` | Yes | The pdf bytes |
| `Password` | `*string` | No | The password |

**Returns:** `PdfMetadata`

**Errors:** Returns `error`.


---

### ExtractMetadataWithPasswords()

**Signature:**

```go
func ExtractMetadataWithPasswords(pdfBytes []byte, passwords []string) (PdfMetadata, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `PdfBytes` | `[]byte` | Yes | The pdf bytes |
| `Passwords` | `[]string` | Yes | The passwords |

**Returns:** `PdfMetadata`

**Errors:** Returns `error`.


---

### ExtractMetadataFromDocument()

Extract complete PDF metadata from a document.

Extracts common fields (title, subject, authors, keywords, dates, creator),
PDF-specific metadata, and optionally builds a PageStructure with boundaries.

  If provided, a PageStructure will be built with these boundaries.
* `content` - Optional extracted text content, used for blank page detection.
  If provided, `PageInfo.is_blank` will be populated based on text content analysis.
  If `nil`, `is_blank` will be `nil` for all pages.

**Returns:**

Returns a `PdfExtractionMetadata` struct containing all extracted metadata,
including page structure if boundaries were provided.

**Signature:**

```go
func ExtractMetadataFromDocument(document PdfDocument, pageBoundaries []PageBoundary, content string) (PdfExtractionMetadata, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Document` | `PdfDocument` | Yes | The PDF document to extract metadata from |
| `PageBoundaries` | `*[]PageBoundary` | No | Optional vector of PageBoundary entries for building PageStructure. |
| `Content` | `*string` | No | Optional extracted text content, used for blank page detection. |

**Returns:** `PdfExtractionMetadata`

**Errors:** Returns `error`.


---

### ExtractCommonMetadataFromDocument()

Extract common metadata from a PDF document.

Returns common fields (title, authors, keywords, dates) that are now stored
in the base `Metadata` struct instead of format-specific metadata.

This function uses batch fetching with caching to optimize metadata extraction
by reducing repeated dictionary lookups. All metadata tags are fetched once and
cached in a single pass.

**Signature:**

```go
func ExtractCommonMetadataFromDocument(document PdfDocument) (CommonPdfMetadata, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Document` | `PdfDocument` | Yes | The pdf document |

**Returns:** `CommonPdfMetadata`

**Errors:** Returns `error`.


---

### RenderPageToImage()

**Signature:**

```go
func RenderPageToImage(pdfBytes []byte, pageIndex int, options PageRenderOptions) (DynamicImage, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `PdfBytes` | `[]byte` | Yes | The pdf bytes |
| `PageIndex` | `int` | Yes | The page index |
| `Options` | `PageRenderOptions` | Yes | The options to use |

**Returns:** `DynamicImage`

**Errors:** Returns `error`.


---

### RenderPdfPageToPng()

Render a single PDF page to a PNG-encoded byte buffer.

**Errors:**

Returns an error if the PDF is invalid, the page index is out of bounds,
or if the page fails to render.

**Signature:**

```go
func RenderPdfPageToPng(pdfBytes []byte, pageIndex int, dpi int32, password string) ([]byte, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `PdfBytes` | `[]byte` | Yes | The pdf bytes |
| `PageIndex` | `int` | Yes | The page index |
| `Dpi` | `*int32` | No | The dpi |
| `Password` | `*string` | No | The password |

**Returns:** `[]byte`

**Errors:** Returns `error`.


---

### ExtractWordsFromPage()

Extract words with positions from PDF page for table detection.

Groups adjacent characters into words based on spacing heuristics,
then converts to HocrWord format for table reconstruction.

**Returns:**

Vector of HocrWord objects with text and bounding box information.

**Note:**
This function requires the "ocr" feature to be enabled. Without it, returns an error.

**Signature:**

```go
func ExtractWordsFromPage(page PdfPage, minConfidence float64) ([]HocrWord, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Page` | `PdfPage` | Yes | PDF page to extract words from |
| `MinConfidence` | `float64` | Yes | Minimum confidence threshold (0.0-100.0). PDF text has high confidence (95.0). |

**Returns:** `[]HocrWord`

**Errors:** Returns `error`.


---

### SegmentToHocrWord()

Convert a PDF `SegmentData` to an `HocrWord` for table reconstruction.

`SegmentData` uses PDF coordinates (y=0 at bottom, increases upward).
`HocrWord` uses image coordinates (y=0 at top, increases downward).

**Signature:**

```go
func SegmentToHocrWord(seg SegmentData, pageHeight float32) HocrWord
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Seg` | `SegmentData` | Yes | The segment data |
| `PageHeight` | `float32` | Yes | The page height |

**Returns:** `HocrWord`


---

### SplitSegmentToWords()

Split a `SegmentData` into word-level `HocrWord`s for table reconstruction.

Pdfium segments can contain multiple whitespace-separated words (merged by
shared baseline + font). For table cell matching, each word needs its own
bounding box so it can be assigned to the correct column/cell.

Single-word segments use `segment_to_hocr_word` directly (fast path).
Multi-word segments get proportional bbox estimation per word based on
byte offset within the segment text.

**Signature:**

```go
func SplitSegmentToWords(seg SegmentData, pageHeight float32) []HocrWord
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Seg` | `SegmentData` | Yes | The segment data |
| `PageHeight` | `float32` | Yes | The page height |

**Returns:** `[]HocrWord`


---

### SegmentsToWords()

Convert a page's segments to word-level `HocrWord`s for table extraction.

Splits multi-word segments into individual words with proportional bounding
boxes, ensuring each word can be independently matched to table cells.

**Signature:**

```go
func SegmentsToWords(segments []SegmentData, pageHeight float32) []HocrWord
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Segments` | `[]SegmentData` | Yes | The segments |
| `PageHeight` | `float32` | Yes | The page height |

**Returns:** `[]HocrWord`


---

### PostProcessTable()

Post-process a raw table grid to validate structure and clean up.

Returns `nil` if the table fails structural validation.

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

```go
func PostProcessTable(table [][]string, layoutGuided bool, allowSingleColumn bool) *[][]string
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Table` | `[][]string` | Yes | The table |
| `LayoutGuided` | `bool` | Yes | The layout guided |
| `AllowSingleColumn` | `bool` | Yes | The allow single column |

**Returns:** `*[][]string`


---

### IsWellFormedTable()

Validate whether a reconstructed table grid represents a well-formed table
rather than multi-column prose or a repeated page element.

Returns `true` if the grid looks like a real table, `false` if it should be
rejected and its content emitted as paragraph text instead.

The checks catch cases the layout model misidentifies as tables:
- Multi-column prose split into a grid (detected via row coherence and column uniformity)
- Repeated page elements (headers/footers detected as tables on every page)
- Low-vocabulary repetitive content (same few words in every row)

**Signature:**

```go
func IsWellFormedTable(grid [][]string) bool
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Grid` | `[][]string` | Yes | The grid |

**Returns:** `bool`


---

### ExtractTextFromPdf()

**Signature:**

```go
func ExtractTextFromPdf(pdfBytes []byte) (string, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `PdfBytes` | `[]byte` | Yes | The pdf bytes |

**Returns:** `string`

**Errors:** Returns `error`.


---

### ExtractTextFromPdfWithPassword()

**Signature:**

```go
func ExtractTextFromPdfWithPassword(pdfBytes []byte, password string) (string, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `PdfBytes` | `[]byte` | Yes | The pdf bytes |
| `Password` | `string` | Yes | The password |

**Returns:** `string`

**Errors:** Returns `error`.


---

### ExtractTextFromPdfWithPasswords()

**Signature:**

```go
func ExtractTextFromPdfWithPasswords(pdfBytes []byte, passwords []string) (string, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `PdfBytes` | `[]byte` | Yes | The pdf bytes |
| `Passwords` | `[]string` | Yes | The passwords |

**Returns:** `string`

**Errors:** Returns `error`.


---

### ExtractTextAndMetadataFromPdfDocument()

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

```go
func ExtractTextAndMetadataFromPdfDocument(document PdfDocument, extractionConfig ExtractionConfig) (PdfUnifiedExtractionResult, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Document` | `PdfDocument` | Yes | The PDF document to extract from |
| `ExtractionConfig` | `*ExtractionConfig` | No | Optional extraction configuration for hierarchy and page tracking |

**Returns:** `PdfUnifiedExtractionResult`

**Errors:** Returns `error`.


---

### ExtractTextFromPdfDocument()

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

```go
func ExtractTextFromPdfDocument(document PdfDocument, pageConfig PageConfig, extractionConfig ExtractionConfig) (PdfTextExtractionResult, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Document` | `PdfDocument` | Yes | The PDF document to extract text from |
| `PageConfig` | `*PageConfig` | No | Optional page configuration for boundary tracking and page markers |
| `ExtractionConfig` | `*ExtractionConfig` | No | Optional extraction configuration for hierarchy detection |

**Returns:** `PdfTextExtractionResult`

**Errors:** Returns `error`.


---

### SerializeToToon()

Serialize an `ExtractionResult` to TOON (Token-Oriented Object Notation).

TOON is a token-efficient alternative to JSON for LLM prompts.
Losslessly convertible to/from JSON but uses fewer tokens.

**Signature:**

```go
func SerializeToToon(result ExtractionResult) (string, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Result` | `ExtractionResult` | Yes | The extraction result |

**Returns:** `string`

**Errors:** Returns `error`.


---

### SerializeToJson()

Serialize an `ExtractionResult` to pretty-printed JSON.

**Signature:**

```go
func SerializeToJson(result ExtractionResult) (string, error)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `Result` | `ExtractionResult` | Yes | The extraction result |

**Returns:** `string`

**Errors:** Returns `error`.


---

## Types

### AccelerationConfig

Hardware acceleration configuration for ONNX Runtime models.

Controls which execution provider (CPU, CoreML, CUDA, TensorRT) is used
for inference in layout detection and embedding generation.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Provider` | `ExecutionProviderType` | `ExecutionProviderType.Auto` | Execution provider to use for ONNX inference. |
| `DeviceId` | `uint32` | `nil` | GPU device ID (for CUDA/TensorRT). Ignored for CPU/CoreML/Auto. |


---

### AnchorProperties

Properties for anchored drawings.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `BehindDoc` | `bool` | `nil` | Behind doc |
| `LayoutInCell` | `bool` | `nil` | Layout in cell |
| `RelativeHeight` | `*int64` | `nil` | Relative height |
| `PositionH` | `*Position` | `nil` | Position h (position) |
| `PositionV` | `*Position` | `nil` | Position v (position) |
| `WrapType` | `WrapType` | `WrapType.None` | Wrap type (wrap type) |


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
| `Path` | `string` | — | Archive-relative file path (e.g. "folder/document.pdf"). |
| `MimeType` | `string` | — | Detected MIME type of the file. |
| `Result` | `ExtractionResult` | — | Full extraction result for this file. |


---

### ArchiveMetadata

Archive (ZIP/TAR/7Z) metadata.

Extracted from compressed archive files containing file lists and size information.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Format` | `Str` | — | Archive format ("ZIP", "TAR", "7Z", etc.) |
| `FileCount` | `int` | — | Total number of files in the archive |
| `FileList` | `[]string` | — | List of file paths within the archive |
| `TotalSize` | `int` | — | Total uncompressed size in bytes |
| `CompressedSize` | `*int` | `nil` | Compressed size in bytes (if available) |


---

### Attributes

Element attributes in Djot.

Represents the attributes attached to elements using {.class #id key="value"} syntax.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Id` | `*string` | `nil` | Element ID (#identifier) |
| `Classes` | `[]string` | `nil` | CSS classes (.class1 .class2) |
| `KeyValues` | `[]StringString` | `nil` | Key-value pairs (key="value") |


---

### BBox

Bounding box in original image coordinates (x1, y1) top-left, (x2, y2) bottom-right.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `X1` | `float32` | — | X1 |
| `Y1` | `float32` | — | Y1 |
| `X2` | `float32` | — | X2 |
| `Y2` | `float32` | — | Y2 |

#### Methods

##### Width()

**Signature:**

```go
func (o *BBox) Width() float32
```

##### Height()

**Signature:**

```go
func (o *BBox) Height() float32
```

##### Area()

**Signature:**

```go
func (o *BBox) Area() float32
```

##### Center()

**Signature:**

```go
func (o *BBox) Center() F32F32
```

##### IntersectionArea()

Area of intersection with another bounding box.

**Signature:**

```go
func (o *BBox) IntersectionArea(other BBox) float32
```

##### Iou()

Intersection over Union with another bounding box.

**Signature:**

```go
func (o *BBox) Iou(other BBox) float32
```

##### ContainmentOf()

Fraction of `other` that is contained within `self`.
Returns 0.0..=1.0 where 1.0 means `other` is fully inside `self`.

**Signature:**

```go
func (o *BBox) ContainmentOf(other BBox) float32
```

##### PageCoverage()

Fraction of page area this bbox covers.

**Signature:**

```go
func (o *BBox) PageCoverage(pageWidth float32, pageHeight float32) float32
```

##### Fmt()

**Signature:**

```go
func (o *BBox) Fmt(f Formatter) Unknown
```


---

### BatchItemResult

Batch item result for processing multiple files

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `FilePath` | `string` | — | File path |
| `Success` | `bool` | — | Success |
| `Result` | `*OcrExtractionResult` | `nil` | Result (ocr extraction result) |
| `Error` | `*string` | `nil` | Error |


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

##### WithConfig()

Create a new batch processor with custom pool configuration.

Pools are not created immediately but lazily on first access.

**Returns:**

A new `BatchProcessor` configured with the provided settings.

**Signature:**

```go
func (o *BatchProcessor) WithConfig(config BatchProcessorConfig) BatchProcessor
```

##### WithPoolHint()

Create a batch processor with pool sizes optimized for a specific document.

This method uses a `PoolSizeHint` (derived from file size and MIME type)
to create a batch processor with appropriately sized pools. This reduces
memory waste by tailoring pool allocation to actual document complexity.

**Returns:**

A new `BatchProcessor` configured with the hint-based pool sizes

**Signature:**

```go
func (o *BatchProcessor) WithPoolHint(hint PoolSizeHint) BatchProcessor
```

##### StringPool()

Get a reference to the string buffer pool.

Creates the pool lazily on first access.
Useful for custom pooling implementations that need direct pool access.

**Signature:**

```go
func (o *BatchProcessor) StringPool() StringBufferPool
```

##### BytePool()

Get a reference to the byte buffer pool.

Creates the pool lazily on first access.
Useful for custom pooling implementations that need direct pool access.

**Signature:**

```go
func (o *BatchProcessor) BytePool() ByteBufferPool
```

##### Config()

Get the current configuration.

**Signature:**

```go
func (o *BatchProcessor) Config() BatchProcessorConfig
```

##### StringPoolSize()

Get the number of pooled string buffers currently available.

**Signature:**

```go
func (o *BatchProcessor) StringPoolSize() int
```

##### BytePoolSize()

Get the number of pooled byte buffers currently available.

**Signature:**

```go
func (o *BatchProcessor) BytePoolSize() int
```

##### ClearPools()

Clear all pooled objects, forcing new allocations on next acquire.

Useful for memory-constrained environments or to reclaim memory
after processing large batches.

**Signature:**

```go
func (o *BatchProcessor) ClearPools() error
```

##### Default()

**Signature:**

```go
func (o *BatchProcessor) Default() BatchProcessor
```


---

### BatchProcessorConfig

Configuration for batch processing with pooling optimizations.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `StringPoolSize` | `int` | `10` | Maximum number of string buffers to maintain in the pool |
| `StringBufferCapacity` | `int` | `8192` | Initial capacity for pooled string buffers in bytes |
| `BytePoolSize` | `int` | `10` | Maximum number of byte buffers to maintain in the pool |
| `ByteBufferCapacity` | `int` | `65536` | Initial capacity for pooled byte buffers in bytes |
| `MaxConcurrent` | `*int` | `nil` | Maximum concurrent extractions (for concurrency control) |

#### Methods

##### Default()

**Signature:**

```go
func (o *BatchProcessorConfig) Default() BatchProcessorConfig
```


---

### BibtexExtractor

BibTeX bibliography extractor.

Parses BibTeX files and extracts structured bibliography data including
entries, authors, publication years, and entry type distribution.

#### Methods

##### Default()

**Signature:**

```go
func (o *BibtexExtractor) Default() BibtexExtractor
```

##### Name()

**Signature:**

```go
func (o *BibtexExtractor) Name() string
```

##### Version()

**Signature:**

```go
func (o *BibtexExtractor) Version() string
```

##### Initialize()

**Signature:**

```go
func (o *BibtexExtractor) Initialize() error
```

##### Shutdown()

**Signature:**

```go
func (o *BibtexExtractor) Shutdown() error
```

##### Description()

**Signature:**

```go
func (o *BibtexExtractor) Description() string
```

##### Author()

**Signature:**

```go
func (o *BibtexExtractor) Author() string
```

##### ExtractBytes()

**Signature:**

```go
func (o *BibtexExtractor) ExtractBytes(content []byte, mimeType string, config ExtractionConfig) (InternalDocument, error)
```

##### SupportedMimeTypes()

**Signature:**

```go
func (o *BibtexExtractor) SupportedMimeTypes() []string
```

##### Priority()

**Signature:**

```go
func (o *BibtexExtractor) Priority() int32
```


---

### BibtexMetadata

BibTeX bibliography metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `EntryCount` | `int` | `nil` | Number of entry |
| `CitationKeys` | `[]string` | `nil` | Citation keys |
| `Authors` | `[]string` | `nil` | Authors |
| `YearRange` | `*YearRange` | `nil` | Year range (year range) |
| `EntryTypes` | `*map[string]int` | `nil` | Entry types |


---

### BorderStyle

A single border specification.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Style` | `string` | — | Style |
| `Size` | `*int32` | `nil` | Size in bytes |
| `Color` | `*string` | `nil` | Color |
| `Space` | `*int32` | `nil` | Space |


---

### BoundingBox

Bounding box coordinates for element positioning.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `X0` | `float64` | — | Left x-coordinate |
| `Y0` | `float64` | — | Bottom y-coordinate |
| `X1` | `float64` | — | Right x-coordinate |
| `Y1` | `float64` | — | Top y-coordinate |


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
| `TotalFiles` | `int` | — | Total number of cached files |
| `TotalSizeMb` | `float64` | — | Total cache size in megabytes |
| `AvailableSpaceMb` | `float64` | — | Available disk space in megabytes |
| `OldestFileAgeDays` | `float64` | — | Age of the oldest cached file in days |
| `NewestFileAgeDays` | `float64` | — | Age of the newest cached file in days |


---

### CellBBox

A cell bounding box within the reconstructed table grid.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `X1` | `float32` | — | X1 |
| `Y1` | `float32` | — | Y1 |
| `X2` | `float32` | — | X2 |
| `Y2` | `float32` | — | Y2 |


---

### CellBorders

Per-cell borders (4 sides).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Top` | `*BorderStyle` | `nil` | Top (border style) |
| `Bottom` | `*BorderStyle` | `nil` | Bottom (border style) |
| `Left` | `*BorderStyle` | `nil` | Left (border style) |
| `Right` | `*BorderStyle` | `nil` | Right (border style) |


---

### CellMargins

Cell margins (used for both table-level defaults and per-cell overrides).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Top` | `*int32` | `nil` | Top |
| `Bottom` | `*int32` | `nil` | Bottom |
| `Left` | `*int32` | `nil` | Left |
| `Right` | `*int32` | `nil` | Right |


---

### CellProperties

Cell-level properties from `<w:tcPr>`.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Width` | `*TableWidth` | `nil` | Width (table width) |
| `GridSpan` | `*uint32` | `nil` | Grid span |
| `VMerge` | `*VerticalMerge` | `VerticalMerge.Restart` | V merge (vertical merge) |
| `Borders` | `*CellBorders` | `nil` | Borders (cell borders) |
| `Shading` | `*CellShading` | `nil` | Shading (cell shading) |
| `Margins` | `*CellMargins` | `nil` | Margins (cell margins) |
| `VerticalAlign` | `*string` | `nil` | Vertical align |
| `TextDirection` | `*string` | `nil` | Text direction |
| `NoWrap` | `bool` | `nil` | No wrap |


---

### CellShading

Cell shading/background.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Fill` | `*string` | `nil` | Fill |
| `Color` | `*string` | `nil` | Color |
| `Val` | `*string` | `nil` | Val |


---

### CfbReader

#### Methods

##### FromBytes()

Open a CFB compound file from raw bytes.

**Signature:**

```go
func (o *CfbReader) FromBytes(bytes []byte) (CfbReader, error)
```


---

### Chunk

A text chunk with optional embedding and metadata.

Chunks are created when chunking is enabled in `ExtractionConfig`. Each chunk
contains the text content, optional embedding vector (if embedding generation
is configured), and metadata about its position in the document.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Content` | `string` | — | The text content of this chunk. |
| `ChunkType` | `ChunkType` | — | Semantic structural classification of this chunk. Assigned by the heuristic classifier based on content patterns and heading context. Defaults to `ChunkType.Unknown` when no rule matches. |
| `Embedding` | `*[]float32` | `nil` | Optional embedding vector for this chunk. Only populated when `EmbeddingConfig` is provided in chunking configuration. The dimensionality depends on the chosen embedding model. |
| `Metadata` | `ChunkMetadata` | — | Metadata about this chunk's position and properties. |


---

### ChunkMetadata

Metadata about a chunk's position in the original document.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `ByteStart` | `int` | — | Byte offset where this chunk starts in the original text (UTF-8 valid boundary). |
| `ByteEnd` | `int` | — | Byte offset where this chunk ends in the original text (UTF-8 valid boundary). |
| `TokenCount` | `*int` | `nil` | Number of tokens in this chunk (if available). This is calculated by the embedding model's tokenizer if embeddings are enabled. |
| `ChunkIndex` | `int` | — | Zero-based index of this chunk in the document. |
| `TotalChunks` | `int` | — | Total number of chunks in the document. |
| `FirstPage` | `*int` | `nil` | First page number this chunk spans (1-indexed). Only populated when page tracking is enabled in extraction configuration. |
| `LastPage` | `*int` | `nil` | Last page number this chunk spans (1-indexed, equal to first_page for single-page chunks). Only populated when page tracking is enabled in extraction configuration. |
| `HeadingContext` | `*HeadingContext` | `nil` | Heading context when using Markdown chunker. Contains the heading hierarchy this chunk falls under. Only populated when `ChunkerType.Markdown` is used. |


---

### ChunkingConfig

Chunking configuration.

Configures text chunking for document content, including chunk size,
overlap, trimming behavior, and optional embeddings.

Use `..the default constructor` when constructing to allow for future field additions:

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `MaxCharacters` | `int` | `1000` | Maximum size per chunk (in units determined by `sizing`). When `sizing` is `Characters` (default), this is the max character count. When using token-based sizing, this is the max token count. Default: 1000 |
| `Overlap` | `int` | `200` | Overlap between chunks (in units determined by `sizing`). Default: 200 |
| `Trim` | `bool` | `true` | Whether to trim whitespace from chunk boundaries. Default: true |
| `ChunkerType` | `ChunkerType` | `ChunkerType.Text` | Type of chunker to use (Text or Markdown). Default: Text |
| `Embedding` | `*EmbeddingConfig` | `nil` | Optional embedding configuration for chunk embeddings. |
| `Preset` | `*string` | `nil` | Use a preset configuration (overrides individual settings if provided). |
| `Sizing` | `ChunkSizing` | `ChunkSizing.Characters` | How to measure chunk size. Default: `Characters` (Unicode character count). Enable `chunking-tiktoken` or `chunking-tokenizers` features for token-based sizing. |
| `PrependHeadingContext` | `bool` | `false` | When `True` and `chunker_type` is `Markdown`, prepend the heading hierarchy path (e.g. `"# Title > ## Section\n\n"`) to each chunk's content string. This is useful for RAG pipelines where each chunk needs self-contained context about its position in the document structure. Default: `False` |

#### Methods

##### WithChunkerType()

Set the chunker type.

**Signature:**

```go
func (o *ChunkingConfig) WithChunkerType(chunkerType ChunkerType) ChunkingConfig
```

##### WithSizing()

Set the sizing strategy.

**Signature:**

```go
func (o *ChunkingConfig) WithSizing(sizing ChunkSizing) ChunkingConfig
```

##### WithPrependHeadingContext()

Enable or disable prepending heading context to chunk content.

**Signature:**

```go
func (o *ChunkingConfig) WithPrependHeadingContext(prepend bool) ChunkingConfig
```

##### Default()

**Signature:**

```go
func (o *ChunkingConfig) Default() ChunkingConfig
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

##### Name()

**Signature:**

```go
func (o *ChunkingProcessor) Name() string
```

##### Version()

**Signature:**

```go
func (o *ChunkingProcessor) Version() string
```

##### Initialize()

**Signature:**

```go
func (o *ChunkingProcessor) Initialize() error
```

##### Shutdown()

**Signature:**

```go
func (o *ChunkingProcessor) Shutdown() error
```

##### Process()

**Signature:**

```go
func (o *ChunkingProcessor) Process(result ExtractionResult, config ExtractionConfig) error
```

##### ProcessingStage()

**Signature:**

```go
func (o *ChunkingProcessor) ProcessingStage() ProcessingStage
```

##### ShouldProcess()

**Signature:**

```go
func (o *ChunkingProcessor) ShouldProcess(result ExtractionResult, config ExtractionConfig) bool
```

##### EstimatedDurationMs()

**Signature:**

```go
func (o *ChunkingProcessor) EstimatedDurationMs(result ExtractionResult) uint64
```


---

### ChunkingResult

Result of a text chunking operation.

Contains the generated chunks and metadata about the chunking.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Chunks` | `[]Chunk` | — | List of text chunks |
| `ChunkCount` | `int` | — | Total number of chunks generated |


---

### CitationExtractor

Citation format extractor for RIS, PubMed/MEDLINE, and EndNote XML formats.

Parses citation files and extracts structured bibliography data including
entries, authors, publication years, and format-specific metadata.

#### Methods

##### Default()

**Signature:**

```go
func (o *CitationExtractor) Default() CitationExtractor
```

##### Name()

**Signature:**

```go
func (o *CitationExtractor) Name() string
```

##### Version()

**Signature:**

```go
func (o *CitationExtractor) Version() string
```

##### Initialize()

**Signature:**

```go
func (o *CitationExtractor) Initialize() error
```

##### Shutdown()

**Signature:**

```go
func (o *CitationExtractor) Shutdown() error
```

##### Description()

**Signature:**

```go
func (o *CitationExtractor) Description() string
```

##### Author()

**Signature:**

```go
func (o *CitationExtractor) Author() string
```

##### ExtractBytes()

**Signature:**

```go
func (o *CitationExtractor) ExtractBytes(content []byte, mimeType string, config ExtractionConfig) (InternalDocument, error)
```

##### SupportedMimeTypes()

**Signature:**

```go
func (o *CitationExtractor) SupportedMimeTypes() []string
```

##### Priority()

**Signature:**

```go
func (o *CitationExtractor) Priority() int32
```


---

### CitationMetadata

Citation file metadata (RIS, PubMed, EndNote).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `CitationCount` | `int` | `nil` | Number of citation |
| `Format` | `*string` | `nil` | Format |
| `Authors` | `[]string` | `nil` | Authors |
| `YearRange` | `*YearRange` | `nil` | Year range (year range) |
| `Dois` | `[]string` | `nil` | Dois |
| `Keywords` | `[]string` | `nil` | Keywords |


---

### CodeExtractor

Source code extractor using tree-sitter language pack.

Detects the programming language from the file extension or shebang line,
then uses tree-sitter to parse and extract structural information.

#### Methods

##### Default()

**Signature:**

```go
func (o *CodeExtractor) Default() CodeExtractor
```

##### Name()

**Signature:**

```go
func (o *CodeExtractor) Name() string
```

##### Version()

**Signature:**

```go
func (o *CodeExtractor) Version() string
```

##### Initialize()

**Signature:**

```go
func (o *CodeExtractor) Initialize() error
```

##### Shutdown()

**Signature:**

```go
func (o *CodeExtractor) Shutdown() error
```

##### Description()

**Signature:**

```go
func (o *CodeExtractor) Description() string
```

##### Author()

**Signature:**

```go
func (o *CodeExtractor) Author() string
```

##### ExtractBytes()

**Signature:**

```go
func (o *CodeExtractor) ExtractBytes(content []byte, mimeType string, config ExtractionConfig) (InternalDocument, error)
```

##### ExtractFile()

**Signature:**

```go
func (o *CodeExtractor) ExtractFile(path string, mimeType string, config ExtractionConfig) (InternalDocument, error)
```

##### SupportedMimeTypes()

**Signature:**

```go
func (o *CodeExtractor) SupportedMimeTypes() []string
```

##### Priority()

**Signature:**

```go
func (o *CodeExtractor) Priority() int32
```

##### AsSyncExtractor()

**Signature:**

```go
func (o *CodeExtractor) AsSyncExtractor() *SyncExtractor
```

##### ExtractSync()

**Signature:**

```go
func (o *CodeExtractor) ExtractSync(content []byte, mimeType string, config ExtractionConfig) (InternalDocument, error)
```


---

### ColorScheme

Color scheme containing all 12 standard Office theme colors.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Name` | `string` | `nil` | Color scheme name. |
| `Dk1` | `*ThemeColor` | `ThemeColor.Rgb` | Dark 1 (dark background) color. |
| `Lt1` | `*ThemeColor` | `ThemeColor.Rgb` | Light 1 (light background) color. |
| `Dk2` | `*ThemeColor` | `ThemeColor.Rgb` | Dark 2 color. |
| `Lt2` | `*ThemeColor` | `ThemeColor.Rgb` | Light 2 color. |
| `Accent1` | `*ThemeColor` | `ThemeColor.Rgb` | Accent color 1. |
| `Accent2` | `*ThemeColor` | `ThemeColor.Rgb` | Accent color 2. |
| `Accent3` | `*ThemeColor` | `ThemeColor.Rgb` | Accent color 3. |
| `Accent4` | `*ThemeColor` | `ThemeColor.Rgb` | Accent color 4. |
| `Accent5` | `*ThemeColor` | `ThemeColor.Rgb` | Accent color 5. |
| `Accent6` | `*ThemeColor` | `ThemeColor.Rgb` | Accent color 6. |
| `Hlink` | `*ThemeColor` | `ThemeColor.Rgb` | Hyperlink color. |
| `FolHlink` | `*ThemeColor` | `ThemeColor.Rgb` | Followed hyperlink color. |


---

### ColumnLayout

Column layout configuration.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Count` | `*int32` | `nil` | Number of columns. |
| `SpaceTwips` | `*int32` | `nil` | Space between columns in twips. |
| `EqualWidth` | `*bool` | `nil` | Whether columns have equal width. |


---

### CommonPdfMetadata

Common metadata fields extracted from a PDF.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Title` | `*string` | `nil` | Title |
| `Subject` | `*string` | `nil` | Subject |
| `Authors` | `*[]string` | `nil` | Authors |
| `Keywords` | `*[]string` | `nil` | Keywords |
| `CreatedAt` | `*string` | `nil` | Created at |
| `ModifiedAt` | `*string` | `nil` | Modified at |
| `CreatedBy` | `*string` | `nil` | Created by |


---

### ConcurrencyConfig

Controls thread usage for constrained environments.

Set `max_threads` to cap all internal thread pools (Rayon, ONNX Runtime
intra-op) and batch concurrency to a single limit.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `MaxThreads` | `*int` | `nil` | Maximum number of threads for all internal thread pools. Caps Rayon global pool size, ONNX Runtime intra-op threads, and (when `max_concurrent_extractions` is unset) the batch concurrency semaphore. When `None`, system defaults are used. |


---

### ContentFilterConfig

Cross-extractor content filtering configuration.

Controls whether "furniture" content (headers, footers, page numbers,
watermarks, repeating text) is included in or stripped from extraction
results. Applies across all extractors (PDF, DOCX, RTF, ODT, HTML, etc.)
with format-specific implementation.

When `nil` on `ExtractionConfig`, each extractor uses its current
default behavior unchanged.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `IncludeHeaders` | `bool` | `false` | Include running headers in extraction output. - PDF: Disables top-margin furniture stripping and prevents the layout model from treating `PageHeader`-classified regions as furniture. - DOCX: Includes document headers in text output. - RTF/ODT: Headers already included; this is a no-op when true. - HTML/EPUB: Keeps `<header>` element content. Default: `False` (headers are stripped or excluded). |
| `IncludeFooters` | `bool` | `false` | Include running footers in extraction output. - PDF: Disables bottom-margin furniture stripping and prevents the layout model from treating `PageFooter`-classified regions as furniture. - DOCX: Includes document footers in text output. - RTF/ODT: Footers already included; this is a no-op when true. - HTML/EPUB: Keeps `<footer>` element content. Default: `False` (footers are stripped or excluded). |
| `StripRepeatingText` | `bool` | `true` | Enable the heuristic cross-page repeating text detector. When `True` (default), text that repeats verbatim across a supermajority of pages is classified as furniture and stripped.  Disable this if brand names or repeated headings are being incorrectly removed by the heuristic. Note: when a layout-detection model is active, the model may independently classify page-header / page-footer regions as furniture on a per-page basis. To preserve those regions, set `include_headers = true` and/or `include_footers = true` in addition to disabling this flag. Primarily affects PDF extraction. Default: `True`. |
| `IncludeWatermarks` | `bool` | `false` | Include watermark text in extraction output. - PDF: Keeps watermark artifacts and arXiv identifiers. - Other formats: No effect currently. Default: `False` (watermarks are stripped). |

#### Methods

##### Default()

**Signature:**

```go
func (o *ContentFilterConfig) Default() ContentFilterConfig
```


---

### ContributorRole

JATS contributor with role.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Name` | `string` | — | The name |
| `Role` | `*string` | `nil` | Role |


---

### CoreProperties

Dublin Core metadata from docProps/core.xml

Contains standard metadata fields defined by the Dublin Core standard
and Office-specific extensions.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Title` | `*string` | `nil` | Document title |
| `Subject` | `*string` | `nil` | Document subject/topic |
| `Creator` | `*string` | `nil` | Document creator/author |
| `Keywords` | `*string` | `nil` | Keywords or tags |
| `Description` | `*string` | `nil` | Document description/abstract |
| `LastModifiedBy` | `*string` | `nil` | User who last modified the document |
| `Revision` | `*string` | `nil` | Revision number |
| `Created` | `*string` | `nil` | Creation timestamp (ISO 8601) |
| `Modified` | `*string` | `nil` | Last modification timestamp (ISO 8601) |
| `Category` | `*string` | `nil` | Document category |
| `ContentStatus` | `*string` | `nil` | Content status (Draft, Final, etc.) |
| `Language` | `*string` | `nil` | Document language |
| `Identifier` | `*string` | `nil` | Unique identifier |
| `Version` | `*string` | `nil` | Document version |
| `LastPrinted` | `*string` | `nil` | Last print timestamp (ISO 8601) |


---

### CsvExtractor

CSV/TSV extractor with proper field parsing.

Replaces raw text passthrough with structured CSV parsing,
producing space-separated text output and populated `tables` field.

#### Methods

##### Default()

**Signature:**

```go
func (o *CsvExtractor) Default() CsvExtractor
```

##### Name()

**Signature:**

```go
func (o *CsvExtractor) Name() string
```

##### Version()

**Signature:**

```go
func (o *CsvExtractor) Version() string
```

##### Initialize()

**Signature:**

```go
func (o *CsvExtractor) Initialize() error
```

##### Shutdown()

**Signature:**

```go
func (o *CsvExtractor) Shutdown() error
```

##### Description()

**Signature:**

```go
func (o *CsvExtractor) Description() string
```

##### Author()

**Signature:**

```go
func (o *CsvExtractor) Author() string
```

##### ExtractBytes()

**Signature:**

```go
func (o *CsvExtractor) ExtractBytes(content []byte, mimeType string, config ExtractionConfig) (InternalDocument, error)
```

##### SupportedMimeTypes()

**Signature:**

```go
func (o *CsvExtractor) SupportedMimeTypes() []string
```

##### Priority()

**Signature:**

```go
func (o *CsvExtractor) Priority() int32
```


---

### CsvMetadata

CSV/TSV file metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `RowCount` | `int` | `nil` | Number of row |
| `ColumnCount` | `int` | `nil` | Number of column |
| `Delimiter` | `*string` | `nil` | Delimiter |
| `HasHeader` | `bool` | `nil` | Whether header |
| `ColumnTypes` | `*[]string` | `nil` | Column types |


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

##### Default()

**Signature:**

```go
func (o *DbfExtractor) Default() DbfExtractor
```

##### Name()

**Signature:**

```go
func (o *DbfExtractor) Name() string
```

##### Version()

**Signature:**

```go
func (o *DbfExtractor) Version() string
```

##### Initialize()

**Signature:**

```go
func (o *DbfExtractor) Initialize() error
```

##### Shutdown()

**Signature:**

```go
func (o *DbfExtractor) Shutdown() error
```

##### Description()

**Signature:**

```go
func (o *DbfExtractor) Description() string
```

##### Author()

**Signature:**

```go
func (o *DbfExtractor) Author() string
```

##### ExtractBytes()

**Signature:**

```go
func (o *DbfExtractor) ExtractBytes(content []byte, mimeType string, config ExtractionConfig) (InternalDocument, error)
```

##### SupportedMimeTypes()

**Signature:**

```go
func (o *DbfExtractor) SupportedMimeTypes() []string
```

##### Priority()

**Signature:**

```go
func (o *DbfExtractor) Priority() int32
```


---

### DbfFieldInfo

dBASE field information.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Name` | `string` | — | The name |
| `FieldType` | `string` | — | Field type |


---

### DbfMetadata

dBASE (DBF) file metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `RecordCount` | `int` | `nil` | Number of record |
| `FieldCount` | `int` | `nil` | Number of field |
| `Fields` | `[]DbfFieldInfo` | `nil` | Fields |


---

### DepthValidator

Helper struct for validating nesting depth.

#### Methods

##### Push()

Push a level (increase depth).

**Returns:**
* `Ok(())` if depth is within limits
* `Err(SecurityError)` if depth exceeds limit

**Signature:**

```go
func (o *DepthValidator) Push() error
```

##### Pop()

Pop a level (decrease depth).

**Signature:**

```go
func (o *DepthValidator) Pop()
```

##### CurrentDepth()

Get current depth.

**Signature:**

```go
func (o *DepthValidator) CurrentDepth() int
```


---

### DetectTimings

Granular timing breakdown for a single `detect()` call.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `PreprocessMs` | `float64` | `nil` | Time spent in image preprocessing (resize, letterbox, normalize, tensor allocation). |
| `OnnxMs` | `float64` | `nil` | Time for the ONNX `session.run()` call (actual neural network computation). |
| `ModelTotalMs` | `float64` | `nil` | Total time from start of model call to end of raw output decoding. |
| `PostprocessMs` | `float64` | `nil` | Time spent in postprocessing heuristics (confidence filtering, overlap resolution). |


---

### DetectionResult

Page-level detection result containing all detections and page metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `PageWidth` | `uint32` | — | Page width |
| `PageHeight` | `uint32` | — | Page height |
| `Detections` | `[]LayoutDetection` | — | Detections |


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
| `PlainText` | `string` | — | Plain text representation for backwards compatibility |
| `Blocks` | `[]FormattedBlock` | — | Structured block-level content |
| `Metadata` | `Metadata` | — | Metadata from YAML frontmatter |
| `Tables` | `[]Table` | — | Extracted tables as structured data |
| `Images` | `[]DjotImage` | — | Extracted images with metadata |
| `Links` | `[]DjotLink` | — | Extracted links with URLs |
| `Footnotes` | `[]Footnote` | — | Footnote definitions |
| `Attributes` | `[]StringAttributes` | — | Attributes mapped by element identifier (if present) |


---

### DjotExtractor

Djot markup extractor with metadata and table support.

Parses Djot documents with YAML frontmatter, extracting:
- Metadata from YAML frontmatter
- Plain text content
- Tables as structured data
- Document structure (headings, links, code blocks)

#### Methods

##### BuildInternalDocument()

Build an `InternalDocument` from jotdown events.

**Signature:**

```go
func (o *DjotExtractor) BuildInternalDocument(events []Event) InternalDocument
```

##### Default()

**Signature:**

```go
func (o *DjotExtractor) Default() DjotExtractor
```

##### Name()

**Signature:**

```go
func (o *DjotExtractor) Name() string
```

##### Version()

**Signature:**

```go
func (o *DjotExtractor) Version() string
```

##### Initialize()

**Signature:**

```go
func (o *DjotExtractor) Initialize() error
```

##### Shutdown()

**Signature:**

```go
func (o *DjotExtractor) Shutdown() error
```

##### Description()

**Signature:**

```go
func (o *DjotExtractor) Description() string
```

##### Author()

**Signature:**

```go
func (o *DjotExtractor) Author() string
```

##### ExtractBytes()

**Signature:**

```go
func (o *DjotExtractor) ExtractBytes(content []byte, mimeType string, config ExtractionConfig) (InternalDocument, error)
```

##### ExtractFile()

**Signature:**

```go
func (o *DjotExtractor) ExtractFile(path string, mimeType string, config ExtractionConfig) (InternalDocument, error)
```

##### SupportedMimeTypes()

**Signature:**

```go
func (o *DjotExtractor) SupportedMimeTypes() []string
```

##### Priority()

**Signature:**

```go
func (o *DjotExtractor) Priority() int32
```


---

### DjotImage

Image element in Djot.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Src` | `string` | — | Image source URL or path |
| `Alt` | `string` | — | Alternative text |
| `Title` | `*string` | `nil` | Optional title |
| `Attributes` | `*Attributes` | `nil` | Element attributes |


---

### DjotLink

Link element in Djot.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Url` | `string` | — | Link URL |
| `Text` | `string` | — | Link text content |
| `Title` | `*string` | `nil` | Optional title |
| `Attributes` | `*Attributes` | `nil` | Element attributes |


---

### DocExtractionResult

Result of DOC text extraction.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Text` | `string` | — | Extracted text content. |
| `Metadata` | `DocMetadata` | — | Document metadata. |


---

### DocExtractor

Native DOC extractor using OLE/CFB parsing.

This extractor handles Word 97-2003 binary (.doc) files without
requiring LibreOffice, providing ~50x faster extraction.

#### Methods

##### Default()

**Signature:**

```go
func (o *DocExtractor) Default() DocExtractor
```

##### Name()

**Signature:**

```go
func (o *DocExtractor) Name() string
```

##### Version()

**Signature:**

```go
func (o *DocExtractor) Version() string
```

##### Initialize()

**Signature:**

```go
func (o *DocExtractor) Initialize() error
```

##### Shutdown()

**Signature:**

```go
func (o *DocExtractor) Shutdown() error
```

##### Description()

**Signature:**

```go
func (o *DocExtractor) Description() string
```

##### Author()

**Signature:**

```go
func (o *DocExtractor) Author() string
```

##### ExtractBytes()

**Signature:**

```go
func (o *DocExtractor) ExtractBytes(content []byte, mimeType string, config ExtractionConfig) (InternalDocument, error)
```

##### SupportedMimeTypes()

**Signature:**

```go
func (o *DocExtractor) SupportedMimeTypes() []string
```

##### Priority()

**Signature:**

```go
func (o *DocExtractor) Priority() int32
```


---

### DocMetadata

Metadata extracted from DOC files.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Title` | `*string` | `nil` | Title |
| `Subject` | `*string` | `nil` | Subject |
| `Author` | `*string` | `nil` | Author |
| `LastAuthor` | `*string` | `nil` | Last author |
| `Created` | `*string` | `nil` | Created |
| `Modified` | `*string` | `nil` | Modified |
| `RevisionNumber` | `*string` | `nil` | Revision number |


---

### DocOrientationDetector

Detects document page orientation using the PP-LCNet model.

Thread-safe: uses unsafe pointer cast for ONNX session (same pattern as embedding engine).
The model is downloaded from HuggingFace on first use and cached locally.

#### Methods

##### Detect()

Detect document page orientation.

Returns the detected orientation (0°, 90°, 180°, 270°) and confidence.
Thread-safe: can be called concurrently from multiple pages.

**Signature:**

```go
func (o *DocOrientationDetector) Detect(image RgbImage) (OrientationResult, error)
```


---

### DocProperties

Document properties from `<wp:docPr>`.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Id` | `*string` | `nil` | Unique identifier |
| `Name` | `*string` | `nil` | The name |
| `Description` | `*string` | `nil` | Human-readable description |


---

### DocbookExtractor

DocBook document extractor.

Supports both DocBook 4.x (no namespace) and 5.x (with namespace) formats.

#### Methods

##### Default()

**Signature:**

```go
func (o *DocbookExtractor) Default() DocbookExtractor
```

##### Name()

**Signature:**

```go
func (o *DocbookExtractor) Name() string
```

##### Version()

**Signature:**

```go
func (o *DocbookExtractor) Version() string
```

##### Initialize()

**Signature:**

```go
func (o *DocbookExtractor) Initialize() error
```

##### Shutdown()

**Signature:**

```go
func (o *DocbookExtractor) Shutdown() error
```

##### ExtractBytes()

**Signature:**

```go
func (o *DocbookExtractor) ExtractBytes(content []byte, mimeType string, config ExtractionConfig) (InternalDocument, error)
```

##### SupportedMimeTypes()

**Signature:**

```go
func (o *DocbookExtractor) SupportedMimeTypes() []string
```

##### Priority()

**Signature:**

```go
func (o *DocbookExtractor) Priority() int32
```


---

### Document

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Paragraphs` | `[]Paragraph` | `nil` | Paragraphs |
| `Tables` | `[]Table` | `nil` | Tables extracted from the document |
| `Headers` | `[]HeaderFooter` | `nil` | Headers |
| `Footers` | `[]HeaderFooter` | `nil` | Footers |
| `Footnotes` | `[]Note` | `nil` | Footnotes |
| `Endnotes` | `[]Note` | `nil` | Endnotes |
| `NumberingDefs` | `AHashMap` | `nil` | Numbering defs (a hash map) |
| `Elements` | `[]DocumentElement` | `nil` | Document elements in their original order. |
| `StyleCatalog` | `*StyleCatalog` | `nil` | Parsed style catalog from `word/styles.xml`, if available. |
| `Theme` | `*Theme` | `nil` | Parsed theme from `word/theme/theme1.xml`, if available. |
| `Sections` | `[]SectionProperties` | `nil` | Section properties parsed from `w:sectPr` elements. |
| `Drawings` | `[]Drawing` | `nil` | Drawing objects parsed from `w:drawing` elements. |
| `ImageRelationships` | `AHashMap` | `nil` | Image relationships (rId → target path) for image extraction. |

#### Methods

##### ResolveHeadingLevel()

Resolve heading level for a paragraph style using the StyleCatalog.

Walks the style inheritance chain to find `outline_level`.
Falls back to string-matching on style name/ID if no StyleCatalog is available.
Returns 1-6 (markdown heading levels).

**Signature:**

```go
func (o *Document) ResolveHeadingLevel(styleId string) *uint8
```

##### ExtractText()

**Signature:**

```go
func (o *Document) ExtractText() string
```

##### ToMarkdown()

Render the document as markdown.

When `inject_placeholders` is `true`, drawings that reference an image
emit `![alt](image)` placeholders. When `false` they are silently
skipped, which is useful when the caller only wants text.

**Signature:**

```go
func (o *Document) ToMarkdown(injectPlaceholders bool) string
```

##### ToPlainText()

Render the document as plain text (no markdown formatting).

**Signature:**

```go
func (o *Document) ToPlainText() string
```


---

### DocumentNode

A single node in the document tree.

Each node has deterministic `id`, typed `content`, optional `parent`/`children`
for tree structure, and metadata like page number, bounding box, and content layer.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Id` | `NodeId` | — | Deterministic identifier (hash of content + position). |
| `Content` | `NodeContent` | — | Node content — tagged enum, type-specific data only. |
| `Parent` | `*uint32` | `nil` | Parent node index (`None` = root-level node). |
| `Children` | `[]uint32` | — | Child node indices in reading order. |
| `ContentLayer` | `ContentLayer` | — | Content layer classification. |
| `Page` | `*uint32` | `nil` | Page number where this node starts (1-indexed). |
| `PageEnd` | `*uint32` | `nil` | Page number where this node ends (for multi-page tables/sections). |
| `Bbox` | `*BoundingBox` | `nil` | Bounding box in document coordinates. |
| `Annotations` | `[]TextAnnotation` | — | Inline annotations (formatting, links) on this node's text content. Only meaningful for text-carrying nodes; empty for containers. |
| `Attributes` | `*map[string]string` | `nil` | Format-specific key-value attributes. Extensible bag for data that doesn't warrant a typed field: CSS classes, LaTeX environment names, Excel cell formulas, slide layout names, etc. |


---

### DocumentRelationship

A resolved relationship between two nodes in the document tree.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Source` | `uint32` | — | Source node index (the referencing node). |
| `Target` | `uint32` | — | Target node index (the referenced node). |
| `Kind` | `RelationshipKind` | — | Semantic kind of the relationship. |


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
| `Nodes` | `[]DocumentNode` | `nil` | All nodes in document/reading order. |
| `SourceFormat` | `*string` | `nil` | Origin format identifier (e.g. "docx", "pptx", "html", "pdf"). Allows renderers to apply format-aware heuristics when converting the document tree to output formats. |
| `Relationships` | `[]DocumentRelationship` | `nil` | Resolved relationships between nodes (footnote refs, citations, anchor links, etc.). Populated during derivation from the internal document representation. Empty when no relationships are detected. |

#### Methods

##### WithCapacity()

Create a `DocumentStructure` with pre-allocated capacity.

**Signature:**

```go
func (o *DocumentStructure) WithCapacity(capacity int) DocumentStructure
```

##### PushNode()

Push a node and return its `NodeIndex`.

**Signature:**

```go
func (o *DocumentStructure) PushNode(node DocumentNode) uint32
```

##### AddChild()

Add a child to an existing parent node.

Updates both the parent's `children` list and the child's `parent` field.

**Panics:**

Panics if either index is out of bounds.

**Signature:**

```go
func (o *DocumentStructure) AddChild(parent uint32, child uint32)
```

##### Validate()

Validate all node indices are in bounds and parent-child relationships
are bidirectionally consistent.

**Errors:**

Returns a descriptive error string if validation fails.

**Signature:**

```go
func (o *DocumentStructure) Validate() error
```

##### BodyRoots()

Iterate over root-level body nodes (content_layer == Body, parent == None).

**Signature:**

```go
func (o *DocumentStructure) BodyRoots() Iterator
```

##### FurnitureRoots()

Iterate over root-level furniture nodes (non-Body content_layer, parent == None).

**Signature:**

```go
func (o *DocumentStructure) FurnitureRoots() Iterator
```

##### Get()

Get a node by index.

**Signature:**

```go
func (o *DocumentStructure) Get(index uint32) *DocumentNode
```

##### Len()

Get the total number of nodes.

**Signature:**

```go
func (o *DocumentStructure) Len() int
```

##### IsEmpty()

Check if the document structure is empty.

**Signature:**

```go
func (o *DocumentStructure) IsEmpty() bool
```

##### Default()

**Signature:**

```go
func (o *DocumentStructure) Default() DocumentStructure
```


---

### DocumentStructureBuilder

Builder for constructing `DocumentStructure` trees with automatic
heading-driven section nesting.

The builder maintains an internal section stack: when you push a heading,
it automatically creates a `Group` container and nests subsequent content
under it. Higher-level headings pop deeper sections off the stack.

#### Methods

##### WithCapacity()

Create a builder with pre-allocated node capacity.

**Signature:**

```go
func (o *DocumentStructureBuilder) WithCapacity(capacity int) DocumentStructureBuilder
```

##### SourceFormat()

Set the source format identifier (e.g. "docx", "html", "pptx").

**Signature:**

```go
func (o *DocumentStructureBuilder) SourceFormat(format string) DocumentStructureBuilder
```

##### Build()

Consume the builder and return the constructed `DocumentStructure`.

**Signature:**

```go
func (o *DocumentStructureBuilder) Build() DocumentStructure
```

##### PushHeading()

Push a heading, creating a `Group` container with automatic section nesting.

Headings at the same or deeper level pop existing sections. Content
pushed after this heading will be nested under its `Group` node.

Returns the `NodeIndex` of the `Group` node (not the heading child).

**Signature:**

```go
func (o *DocumentStructureBuilder) PushHeading(level uint8, text string, page uint32, bbox BoundingBox) uint32
```

##### PushParagraph()

Push a paragraph node. Nested under current section if one exists.

**Signature:**

```go
func (o *DocumentStructureBuilder) PushParagraph(text string, annotations []TextAnnotation, page uint32, bbox BoundingBox) uint32
```

##### PushList()

Push a list container. Returns the `NodeIndex` to use with `push_list_item`.

**Signature:**

```go
func (o *DocumentStructureBuilder) PushList(ordered bool, page uint32) uint32
```

##### PushListItem()

Push a list item as a child of the given list node.

**Signature:**

```go
func (o *DocumentStructureBuilder) PushListItem(list uint32, text string, page uint32) uint32
```

##### PushTable()

Push a table node with a structured grid.

**Signature:**

```go
func (o *DocumentStructureBuilder) PushTable(grid TableGrid, page uint32, bbox BoundingBox) uint32
```

##### PushTableFromCells()

Push a table from a simple cell grid (`Vec<Vec<String>>`).

Assumes the first row is the header row.

**Signature:**

```go
func (o *DocumentStructureBuilder) PushTableFromCells(cells [][]string, page uint32) uint32
```

##### PushCode()

Push a code block.

**Signature:**

```go
func (o *DocumentStructureBuilder) PushCode(text string, language string, page uint32) uint32
```

##### PushFormula()

Push a math formula node.

**Signature:**

```go
func (o *DocumentStructureBuilder) PushFormula(text string, page uint32) uint32
```

##### PushImage()

Push an image reference node.

**Signature:**

```go
func (o *DocumentStructureBuilder) PushImage(description string, imageIndex uint32, page uint32, bbox BoundingBox) uint32
```

##### PushImageWithSrc()

Push an image node with source URL.

**Signature:**

```go
func (o *DocumentStructureBuilder) PushImageWithSrc(description string, src string, imageIndex uint32, page uint32, bbox BoundingBox) uint32
```

##### PushQuote()

Push a block quote container and enter it.

Subsequent body nodes will be parented under this quote until
`exit_container` is called.

**Signature:**

```go
func (o *DocumentStructureBuilder) PushQuote(page uint32) uint32
```

##### PushFootnote()

Push a footnote node.

**Signature:**

```go
func (o *DocumentStructureBuilder) PushFootnote(text string, page uint32) uint32
```

##### PushPageBreak()

Push a page break marker (always root-level, never nested under sections).

**Signature:**

```go
func (o *DocumentStructureBuilder) PushPageBreak(page uint32) uint32
```

##### PushSlide()

Push a slide container (PPTX) and enter it.

Clears the section stack and container stack so the slide starts
fresh. Subsequent body nodes will be parented under this slide
until `exit_container` is called or a new
slide is pushed.

**Signature:**

```go
func (o *DocumentStructureBuilder) PushSlide(number uint32, title string) uint32
```

##### PushDefinitionList()

Push a definition list container. Use `push_definition_item` for entries.

**Signature:**

```go
func (o *DocumentStructureBuilder) PushDefinitionList(page uint32) uint32
```

##### PushDefinitionItem()

Push a definition item as a child of the given definition list.

**Signature:**

```go
func (o *DocumentStructureBuilder) PushDefinitionItem(list uint32, term string, definition string, page uint32) uint32
```

##### PushCitation()

Push a citation / bibliographic reference.

**Signature:**

```go
func (o *DocumentStructureBuilder) PushCitation(key string, text string, page uint32) uint32
```

##### PushAdmonition()

Push an admonition container (note, warning, tip, etc.) and enter it.

Subsequent body nodes will be parented under this admonition until
`exit_container` is called.

**Signature:**

```go
func (o *DocumentStructureBuilder) PushAdmonition(kind string, title string, page uint32) uint32
```

##### PushRawBlock()

Push a raw block preserved verbatim from the source format.

**Signature:**

```go
func (o *DocumentStructureBuilder) PushRawBlock(format string, content string, page uint32) uint32
```

##### PushMetadataBlock()

Push a metadata block (email headers, frontmatter key-value pairs).

**Signature:**

```go
func (o *DocumentStructureBuilder) PushMetadataBlock(entries []StringString, page uint32) uint32
```

##### PushHeader()

Push a header paragraph (running page header).

**Signature:**

```go
func (o *DocumentStructureBuilder) PushHeader(text string, page uint32) uint32
```

##### PushFooter()

Push a footer paragraph (running page footer).

**Signature:**

```go
func (o *DocumentStructureBuilder) PushFooter(text string, page uint32) uint32
```

##### SetAttributes()

Set format-specific attributes on an existing node.

**Signature:**

```go
func (o *DocumentStructureBuilder) SetAttributes(index uint32, attrs AHashMap)
```

##### AddChild()

Add a child node to an existing parent (for container nodes like Quote, Slide, Admonition).

**Signature:**

```go
func (o *DocumentStructureBuilder) AddChild(parent uint32, child uint32)
```

##### PushRaw()

Push a raw `NodeContent` with full control over content layer and annotations.
Nests under current section unless the content type is a root-level type.

**Signature:**

```go
func (o *DocumentStructureBuilder) PushRaw(content NodeContent, page uint32, bbox BoundingBox, layer ContentLayer, annotations []TextAnnotation) uint32
```

##### ClearSections()

Reset the section stack (e.g. when starting a new page).

**Signature:**

```go
func (o *DocumentStructureBuilder) ClearSections()
```

##### EnterContainer()

Manually push a node onto the container stack.

Subsequent body nodes will be parented under this container
until `exit_container` is called.

**Signature:**

```go
func (o *DocumentStructureBuilder) EnterContainer(container uint32)
```

##### ExitContainer()

Pop the most recent container from the container stack.

Body nodes will resume parenting under the next container on the
stack, or under the section stack if the container stack is empty.

**Signature:**

```go
func (o *DocumentStructureBuilder) ExitContainer()
```

##### Default()

**Signature:**

```go
func (o *DocumentStructureBuilder) Default() DocumentStructureBuilder
```


---

### DocxAppProperties

Application properties from docProps/app.xml for DOCX

Contains Word-specific document statistics and metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Application` | `*string` | `nil` | Application name (e.g., "Microsoft Office Word") |
| `AppVersion` | `*string` | `nil` | Application version |
| `Template` | `*string` | `nil` | Template filename |
| `TotalTime` | `*int32` | `nil` | Total editing time in minutes |
| `Pages` | `*int32` | `nil` | Number of pages |
| `Words` | `*int32` | `nil` | Number of words |
| `Characters` | `*int32` | `nil` | Number of characters (excluding spaces) |
| `CharactersWithSpaces` | `*int32` | `nil` | Number of characters (including spaces) |
| `Lines` | `*int32` | `nil` | Number of lines |
| `Paragraphs` | `*int32` | `nil` | Number of paragraphs |
| `Company` | `*string` | `nil` | Company name |
| `DocSecurity` | `*int32` | `nil` | Document security level |
| `ScaleCrop` | `*bool` | `nil` | Scale crop flag |
| `LinksUpToDate` | `*bool` | `nil` | Links up to date flag |
| `SharedDoc` | `*bool` | `nil` | Shared document flag |
| `HyperlinksChanged` | `*bool` | `nil` | Hyperlinks changed flag |


---

### DocxExtractor

High-performance DOCX extractor.

This extractor provides:
- Fast text extraction via streaming XML parsing
- Comprehensive metadata extraction (core.xml, app.xml, custom.xml)

#### Methods

##### Default()

**Signature:**

```go
func (o *DocxExtractor) Default() DocxExtractor
```

##### Name()

**Signature:**

```go
func (o *DocxExtractor) Name() string
```

##### Version()

**Signature:**

```go
func (o *DocxExtractor) Version() string
```

##### Initialize()

**Signature:**

```go
func (o *DocxExtractor) Initialize() error
```

##### Shutdown()

**Signature:**

```go
func (o *DocxExtractor) Shutdown() error
```

##### Description()

**Signature:**

```go
func (o *DocxExtractor) Description() string
```

##### Author()

**Signature:**

```go
func (o *DocxExtractor) Author() string
```

##### ExtractBytes()

**Signature:**

```go
func (o *DocxExtractor) ExtractBytes(content []byte, mimeType string, config ExtractionConfig) (InternalDocument, error)
```

##### SupportedMimeTypes()

**Signature:**

```go
func (o *DocxExtractor) SupportedMimeTypes() []string
```

##### Priority()

**Signature:**

```go
func (o *DocxExtractor) Priority() int32
```


---

### DocxMetadata

Word document metadata.

Extracted from DOCX files using shared Office Open XML metadata extraction.
Integrates with `office_metadata` module for core/app/custom properties.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `CoreProperties` | `*CoreProperties` | `nil` | Core properties from docProps/core.xml (Dublin Core metadata) Contains title, creator, subject, keywords, dates, etc. Shared format across DOCX/PPTX/XLSX documents. |
| `AppProperties` | `*DocxAppProperties` | `nil` | Application properties from docProps/app.xml (Word-specific statistics) Contains word count, page count, paragraph count, editing time, etc. DOCX-specific variant of Office application properties. |
| `CustomProperties` | `*map[string]interface{}` | `nil` | Custom properties from docProps/custom.xml (user-defined properties) Contains key-value pairs defined by users or applications. Values can be strings, numbers, booleans, or dates. |


---

### Drawing

A drawing object extracted from `<w:drawing>`.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `DrawingType` | `DrawingType` | — | Drawing type (drawing type) |
| `Extent` | `*Extent` | `nil` | Extent (extent) |
| `DocProperties` | `*DocProperties` | `nil` | Doc properties (doc properties) |
| `ImageRef` | `*string` | `nil` | Image ref |


---

### Element

Semantic element extracted from document.

Represents a logical unit of content with semantic classification,
unique identifier, and metadata for tracking origin and position.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `ElementId` | `ElementId` | — | Unique element identifier |
| `ElementType` | `ElementType` | — | Semantic type of this element |
| `Text` | `string` | — | Text content of the element |
| `Metadata` | `ElementMetadata` | — | Metadata about the element |


---

### ElementId

Unique identifier for semantic elements.

Wraps a string identifier that is deterministically generated
from element type, content, and page number.

#### Methods

##### New()

Create a new ElementId from a string.

**Errors:**

Returns error if the string is not valid.

**Signature:**

```go
func (o *ElementId) New(hexStr string) (ElementId, error)
```

##### AsRef()

**Signature:**

```go
func (o *ElementId) AsRef() string
```

##### Fmt()

**Signature:**

```go
func (o *ElementId) Fmt(f Formatter) Unknown
```


---

### ElementMetadata

Metadata for a semantic element.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `PageNumber` | `*int` | `nil` | Page number (1-indexed) |
| `Filename` | `*string` | `nil` | Source filename or document name |
| `Coordinates` | `*BoundingBox` | `nil` | Bounding box coordinates if available |
| `ElementIndex` | `*int` | `nil` | Position index in the element sequence |
| `Additional` | `map[string]string` | — | Additional custom metadata |


---

### EmailAttachment

Email attachment representation.

Contains metadata and optionally the content of an email attachment.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Name` | `*string` | `nil` | Attachment name (from Content-Disposition header) |
| `Filename` | `*string` | `nil` | Filename of the attachment |
| `MimeType` | `*string` | `nil` | MIME type of the attachment |
| `Size` | `*int` | `nil` | Size in bytes |
| `IsImage` | `bool` | — | Whether this attachment is an image |
| `Data` | `*[]byte` | `nil` | Attachment data (if extracted). Uses `bytes.Bytes` for cheap cloning of large buffers. |


---

### EmailConfig

Configuration for email extraction.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `MsgFallbackCodepage` | `*uint32` | `nil` | Windows codepage number to use when an MSG file contains no codepage property. Defaults to `None`, which falls back to windows-1252. If an unrecognized or invalid codepage number is supplied (including 0), the behavior silently falls back to windows-1252 — the same as when the MSG file itself contains an unrecognized codepage. No error or warning is emitted. Users should verify output when supplying unusual values. Common values: - 1250: Central European (Polish, Czech, Hungarian, etc.) - 1251: Cyrillic (Russian, Ukrainian, Bulgarian, etc.) - 1252: Western European (default) - 1253: Greek - 1254: Turkish - 1255: Hebrew - 1256: Arabic - 932:  Japanese (Shift-JIS) - 936:  Simplified Chinese (GBK) |


---

### EmailExtractionResult

Email extraction result.

Complete representation of an extracted email message (.eml or .msg)
including headers, body content, and attachments.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Subject` | `*string` | `nil` | Email subject line |
| `FromEmail` | `*string` | `nil` | Sender email address |
| `ToEmails` | `[]string` | — | Primary recipient email addresses |
| `CcEmails` | `[]string` | — | CC recipient email addresses |
| `BccEmails` | `[]string` | — | BCC recipient email addresses |
| `Date` | `*string` | `nil` | Email date/timestamp |
| `MessageId` | `*string` | `nil` | Message-ID header value |
| `PlainText` | `*string` | `nil` | Plain text version of the email body |
| `HtmlContent` | `*string` | `nil` | HTML version of the email body |
| `CleanedText` | `string` | — | Cleaned/processed text content |
| `Attachments` | `[]EmailAttachment` | — | List of email attachments |
| `Metadata` | `map[string]string` | — | Additional email headers and metadata |


---

### EmailExtractor

Email message extractor.

Supports: .eml, .msg

#### Methods

##### Default()

**Signature:**

```go
func (o *EmailExtractor) Default() EmailExtractor
```

##### Name()

**Signature:**

```go
func (o *EmailExtractor) Name() string
```

##### Version()

**Signature:**

```go
func (o *EmailExtractor) Version() string
```

##### Initialize()

**Signature:**

```go
func (o *EmailExtractor) Initialize() error
```

##### Shutdown()

**Signature:**

```go
func (o *EmailExtractor) Shutdown() error
```

##### ExtractSync()

**Signature:**

```go
func (o *EmailExtractor) ExtractSync(content []byte, mimeType string, config ExtractionConfig) (InternalDocument, error)
```

##### ExtractBytes()

**Signature:**

```go
func (o *EmailExtractor) ExtractBytes(content []byte, mimeType string, config ExtractionConfig) (InternalDocument, error)
```

##### SupportedMimeTypes()

**Signature:**

```go
func (o *EmailExtractor) SupportedMimeTypes() []string
```

##### Priority()

**Signature:**

```go
func (o *EmailExtractor) Priority() int32
```

##### AsSyncExtractor()

**Signature:**

```go
func (o *EmailExtractor) AsSyncExtractor() *SyncExtractor
```


---

### EmailMetadata

Email metadata extracted from .eml and .msg files.

Includes sender/recipient information, message ID, and attachment list.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `FromEmail` | `*string` | `nil` | Sender's email address |
| `FromName` | `*string` | `nil` | Sender's display name |
| `ToEmails` | `[]string` | — | Primary recipients |
| `CcEmails` | `[]string` | — | CC recipients |
| `BccEmails` | `[]string` | — | BCC recipients |
| `MessageId` | `*string` | `nil` | Message-ID header value |
| `Attachments` | `[]string` | — | List of attachment filenames |


---

### EmbeddedFile

Embedded file descriptor extracted from the PDF name tree.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Name` | `string` | — | The filename as stored in the PDF name tree. |
| `Data` | `[]byte` | — | Raw file bytes from the embedded stream. |
| `MimeType` | `*string` | `nil` | MIME type if specified in the filespec, otherwise `None`. |


---

### EmbeddingConfig

Embedding configuration for text chunks.

Configures embedding generation using ONNX models via the vendored embedding engine.
Requires the `embeddings` feature to be enabled.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Model` | `EmbeddingModelType` | `EmbeddingModelType.Preset` | The embedding model to use (defaults to "balanced" preset if not specified) |
| `Normalize` | `bool` | `true` | Whether to normalize embedding vectors (recommended for cosine similarity) |
| `BatchSize` | `int` | `32` | Batch size for embedding generation |
| `ShowDownloadProgress` | `bool` | `false` | Show model download progress |
| `CacheDir` | `*string` | `nil` | Custom cache directory for model files Defaults to `~/.cache/kreuzberg/embeddings/` if not specified. Allows full customization of model download location. |

#### Methods

##### Default()

**Signature:**

```go
func (o *EmbeddingConfig) Default() EmbeddingConfig
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
| `Name` | `string` | — | The name |
| `ChunkSize` | `int` | — | Chunk size |
| `Overlap` | `int` | — | Overlap |
| `ModelRepo` | `string` | — | HuggingFace repository name for the model. |
| `Pooling` | `string` | — | Pooling strategy: "cls" or "mean". |
| `ModelFile` | `string` | — | Path to the ONNX model file within the repo. |
| `Dimensions` | `int` | — | Dimensions |
| `Description` | `string` | — | Human-readable description |


---

### EntityValidator

Helper struct for validating entity/string length.

#### Methods

##### Validate()

Validate entity length.

**Returns:**
* `Ok(())` if length is within limits
* `Err(SecurityError)` if length exceeds limit

**Signature:**

```go
func (o *EntityValidator) Validate(content string) error
```


---

### EpubExtractor

EPUB format extractor using permissive-licensed dependencies.

Extracts content and metadata from EPUB files (both EPUB2 and EPUB3)
using native Rust parsing without GPL-licensed dependencies.

#### Methods

##### Default()

**Signature:**

```go
func (o *EpubExtractor) Default() EpubExtractor
```

##### Name()

**Signature:**

```go
func (o *EpubExtractor) Name() string
```

##### Version()

**Signature:**

```go
func (o *EpubExtractor) Version() string
```

##### Initialize()

**Signature:**

```go
func (o *EpubExtractor) Initialize() error
```

##### Shutdown()

**Signature:**

```go
func (o *EpubExtractor) Shutdown() error
```

##### Description()

**Signature:**

```go
func (o *EpubExtractor) Description() string
```

##### Author()

**Signature:**

```go
func (o *EpubExtractor) Author() string
```

##### ExtractBytes()

**Signature:**

```go
func (o *EpubExtractor) ExtractBytes(content []byte, mimeType string, config ExtractionConfig) (InternalDocument, error)
```

##### SupportedMimeTypes()

**Signature:**

```go
func (o *EpubExtractor) SupportedMimeTypes() []string
```

##### Priority()

**Signature:**

```go
func (o *EpubExtractor) Priority() int32
```


---

### EpubMetadata

EPUB metadata (Dublin Core extensions).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Coverage` | `*string` | `nil` | Coverage |
| `DcFormat` | `*string` | `nil` | Dc format |
| `Relation` | `*string` | `nil` | Relation |
| `Source` | `*string` | `nil` | Source |
| `DcType` | `*string` | `nil` | Dc type |
| `CoverImage` | `*string` | `nil` | Cover image |


---

### ErrorMetadata

Error metadata (for batch operations).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `ErrorType` | `string` | — | Error type |
| `Message` | `string` | — | Message |


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

##### Default()

**Signature:**

```go
func (o *ExcelExtractor) Default() ExcelExtractor
```

##### Name()

**Signature:**

```go
func (o *ExcelExtractor) Name() string
```

##### Version()

**Signature:**

```go
func (o *ExcelExtractor) Version() string
```

##### Initialize()

**Signature:**

```go
func (o *ExcelExtractor) Initialize() error
```

##### Shutdown()

**Signature:**

```go
func (o *ExcelExtractor) Shutdown() error
```

##### ExtractSync()

**Signature:**

```go
func (o *ExcelExtractor) ExtractSync(content []byte, mimeType string, config ExtractionConfig) (InternalDocument, error)
```

##### ExtractBytes()

**Signature:**

```go
func (o *ExcelExtractor) ExtractBytes(content []byte, mimeType string, config ExtractionConfig) (InternalDocument, error)
```

##### ExtractFile()

**Signature:**

```go
func (o *ExcelExtractor) ExtractFile(path string, mimeType string, config ExtractionConfig) (InternalDocument, error)
```

##### SupportedMimeTypes()

**Signature:**

```go
func (o *ExcelExtractor) SupportedMimeTypes() []string
```

##### Priority()

**Signature:**

```go
func (o *ExcelExtractor) Priority() int32
```

##### AsSyncExtractor()

**Signature:**

```go
func (o *ExcelExtractor) AsSyncExtractor() *SyncExtractor
```


---

### ExcelMetadata

Excel/spreadsheet metadata.

Contains information about sheets in Excel, OpenDocument Calc, and other
spreadsheet formats (.xlsx, .xls, .ods, etc.).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `SheetCount` | `int` | — | Total number of sheets in the workbook |
| `SheetNames` | `[]string` | — | Names of all sheets in order |


---

### ExcelSheet

Single Excel worksheet.

Represents one sheet from an Excel workbook with its content
converted to Markdown format and dimensional statistics.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Name` | `string` | — | Sheet name as it appears in Excel |
| `Markdown` | `string` | — | Sheet content converted to Markdown tables |
| `RowCount` | `int` | — | Number of rows |
| `ColCount` | `int` | — | Number of columns |
| `CellCount` | `int` | — | Total number of non-empty cells |
| `TableCells` | `*[][]string` | `nil` | Pre-extracted table cells (2D vector of cell values) Populated during markdown generation to avoid re-parsing markdown. None for empty sheets. |


---

### ExcelWorkbook

Excel workbook representation.

Contains all sheets from an Excel file (.xlsx, .xls, etc.) with
extracted content and metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Sheets` | `[]ExcelSheet` | — | All sheets in the workbook |
| `Metadata` | `map[string]string` | — | Workbook-level metadata (author, creation date, etc.) |


---

### Extent

Size in EMUs (English Metric Units, 1 inch = 914400 EMU).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Cx` | `int64` | `nil` | Cx |
| `Cy` | `int64` | `nil` | Cy |

#### Methods

##### WidthInches()

Convert width to inches.

**Signature:**

```go
func (o *Extent) WidthInches() float64
```

##### HeightInches()

Convert height to inches.

**Signature:**

```go
func (o *Extent) HeightInches() float64
```


---

### ExtractedImage

Extracted image from a document.

Contains raw image data, metadata, and optional nested OCR results.
Raw bytes allow cross-language compatibility - users can convert to
PIL.Image (Python), Sharp (Node.js), or other formats as needed.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Data` | `[]byte` | — | Raw image data (PNG, JPEG, WebP, etc. bytes). Uses `bytes.Bytes` for cheap cloning of large buffers. |
| `Format` | `Str` | — | Image format (e.g., "jpeg", "png", "webp") Uses Cow<'static, str> to avoid allocation for static literals. |
| `ImageIndex` | `int` | — | Zero-indexed position of this image in the document/page |
| `PageNumber` | `*int` | `nil` | Page/slide number where image was found (1-indexed) |
| `Width` | `*uint32` | `nil` | Image width in pixels |
| `Height` | `*uint32` | `nil` | Image height in pixels |
| `Colorspace` | `*string` | `nil` | Colorspace information (e.g., "RGB", "CMYK", "Gray") |
| `BitsPerComponent` | `*uint32` | `nil` | Bits per color component (e.g., 8, 16) |
| `IsMask` | `bool` | — | Whether this image is a mask image |
| `Description` | `*string` | `nil` | Optional description of the image |
| `OcrResult` | `*ExtractionResult` | `nil` | Nested OCR extraction result (if image was OCRed) When OCR is performed on this image, the result is embedded here rather than in a separate collection, making the relationship explicit. |
| `BoundingBox` | `*BoundingBox` | `nil` | Bounding box of the image on the page (PDF coordinates: x0=left, y0=bottom, x1=right, y1=top). Only populated for PDF-extracted images when position data is available from pdfium. |
| `SourcePath` | `*string` | `nil` | Original source path of the image within the document archive (e.g., "media/image1.png" in DOCX). Used for rendering image references when the binary data is not extracted. |


---

### ExtractionConfig

Main extraction configuration.

This struct contains all configuration options for the extraction process.
It can be loaded from TOML, YAML, or JSON files, or created programmatically.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `UseCache` | `bool` | `true` | Enable caching of extraction results |
| `EnableQualityProcessing` | `bool` | `true` | Enable quality post-processing |
| `Ocr` | `*OcrConfig` | `nil` | OCR configuration (None = OCR disabled) |
| `ForceOcr` | `bool` | `false` | Force OCR even for searchable PDFs |
| `ForceOcrPages` | `*[]int` | `nil` | Force OCR on specific pages only (1-indexed page numbers, must be >= 1). When set, only the listed pages are OCR'd regardless of text layer quality. Unlisted pages use native text extraction. Ignored when `force_ocr` is `True`. Only applies to PDF documents. Duplicates are automatically deduplicated. An `ocr` config is recommended for backend/language selection; defaults are used if absent. |
| `DisableOcr` | `bool` | `false` | Disable OCR entirely, even for images. When `True`, OCR is skipped for all document types. Images return metadata only (dimensions, format, EXIF) without text extraction. PDFs use only native text extraction without OCR fallback. Cannot be `True` simultaneously with `force_ocr`. *Added in v4.7.0.* |
| `Chunking` | `*ChunkingConfig` | `nil` | Text chunking configuration (None = chunking disabled) |
| `ContentFilter` | `*ContentFilterConfig` | `nil` | Content filtering configuration (None = use extractor defaults). Controls whether document "furniture" (headers, footers, watermarks, repeating text) is included in or stripped from extraction results. See `ContentFilterConfig` for per-field documentation. |
| `Images` | `*ImageExtractionConfig` | `nil` | Image extraction configuration (None = no image extraction) |
| `PdfOptions` | `*PdfConfig` | `nil` | PDF-specific options (None = use defaults) |
| `TokenReduction` | `*TokenReductionConfig` | `nil` | Token reduction configuration (None = no token reduction) |
| `LanguageDetection` | `*LanguageDetectionConfig` | `nil` | Language detection configuration (None = no language detection) |
| `Pages` | `*PageConfig` | `nil` | Page extraction configuration (None = no page tracking) |
| `Postprocessor` | `*PostProcessorConfig` | `nil` | Post-processor configuration (None = use defaults) |
| `HtmlOptions` | `*ConversionOptions` | `nil` | HTML to Markdown conversion options (None = use defaults) Configure how HTML documents are converted to Markdown, including heading styles, list formatting, code block styles, and preprocessing options. |
| `HtmlOutput` | `*HtmlOutputConfig` | `nil` | Styled HTML output configuration. When set alongside `output_format = OutputFormat.Html`, the extraction pipeline uses `StyledHtmlRenderer` which emits stable `kb-*` CSS class hooks on every structural element and optionally embeds theme CSS or user-supplied CSS in a `<style>` block. When `None`, the existing plain comrak-based HTML renderer is used. |
| `ExtractionTimeoutSecs` | `*uint64` | `nil` | Default per-file timeout in seconds for batch extraction. When set, each file in a batch will be canceled after this duration unless overridden by `FileExtractionConfig.timeout_secs`. `None` means no timeout (unbounded extraction time). |
| `MaxConcurrentExtractions` | `*int` | `nil` | Maximum concurrent extractions in batch operations (None = (num_cpus × 1.5).ceil()). Limits parallelism to prevent resource exhaustion when processing large batches. Defaults to (num_cpus × 1.5).ceil() when not set. |
| `ResultFormat` | `OutputFormat` | `OutputFormat.Plain` | Result structure format Controls whether results are returned in unified format (default) with all content in the `content` field, or element-based format with semantic elements (for Unstructured-compatible output). |
| `SecurityLimits` | `*SecurityLimits` | `nil` | Security limits for archive extraction. Controls maximum archive size, compression ratio, file count, and other security thresholds to prevent decompression bomb attacks. When `None`, default limits are used (500MB archive, 100:1 ratio, 10K files). |
| `OutputFormat` | `OutputFormat` | `OutputFormat.Plain` | Content text format (default: Plain). Controls the format of the extracted content: - `Plain`: Raw extracted text (default) - `Markdown`: Markdown formatted output - `Djot`: Djot markup format (requires djot feature) - `Html`: HTML formatted output When set to a structured format, extraction results will include formatted output. The `formatted_content` field may be populated when format conversion is applied. |
| `Layout` | `*LayoutDetectionConfig` | `nil` | Layout detection configuration (None = layout detection disabled). When set, PDF pages and images are analyzed for document structure (headings, code, formulas, tables, figures, etc.) using RT-DETR models via ONNX Runtime. For PDFs, layout hints override paragraph classification in the markdown pipeline. For images, per-region OCR is performed with markdown formatting based on detected layout classes. Requires the `layout-detection` feature. |
| `IncludeDocumentStructure` | `bool` | `false` | Enable structured document tree output. When true, populates the `document` field on `ExtractionResult` with a hierarchical `DocumentStructure` containing heading-driven section nesting, table grids, content layer classification, and inline annotations. Independent of `result_format` — can be combined with Unified or ElementBased. |
| `Acceleration` | `*AccelerationConfig` | `nil` | Hardware acceleration configuration for ONNX Runtime models. Controls execution provider selection for layout detection and embedding models. When `None`, uses platform defaults (CoreML on macOS, CUDA on Linux, CPU on Windows). |
| `CacheNamespace` | `*string` | `nil` | Cache namespace for tenant isolation. When set, cache entries are stored under `{cache_dir}/{namespace}/`. Must be alphanumeric, hyphens, or underscores only (max 64 chars). Different namespaces have isolated cache spaces on the same filesystem. |
| `CacheTtlSecs` | `*uint64` | `nil` | Per-request cache TTL in seconds. Overrides the global `max_age_days` for this specific extraction. When `0`, caching is completely skipped (no read or write). When `None`, the global TTL applies. |
| `Email` | `*EmailConfig` | `nil` | Email extraction configuration (None = use defaults). Currently supports configuring the fallback codepage for MSG files that do not specify one. See `crate.core.config.EmailConfig` for details. |
| `Concurrency` | `*ConcurrencyConfig` | `nil` | Concurrency limits for constrained environments (None = use defaults). Controls Rayon thread pool size, ONNX Runtime intra-op threads, and (when `max_concurrent_extractions` is unset) the batch concurrency semaphore. See `crate.core.config.ConcurrencyConfig` for details. |
| `MaxArchiveDepth` | `int` | `nil` | Maximum recursion depth for archive extraction (default: 3). Set to 0 to disable recursive extraction (legacy behavior). |
| `TreeSitter` | `*TreeSitterConfig` | `nil` | Tree-sitter language pack configuration (None = tree-sitter disabled). When set, enables code file extraction using tree-sitter parsers. Controls grammar download behavior and code analysis options. |
| `StructuredExtraction` | `*StructuredExtractionConfig` | `nil` | Structured extraction via LLM (None = disabled). When set, the extracted document content is sent to an LLM with the provided JSON schema. The structured response is stored in `ExtractionResult.structured_output`. |

#### Methods

##### Default()

**Signature:**

```go
func (o *ExtractionConfig) Default() ExtractionConfig
```

##### WithFileOverrides()

Create a new `ExtractionConfig` by applying per-file overrides from a
`FileExtractionConfig`. Fields that are `Some` in the override replace the
corresponding field in `self`; `nil` fields keep the original value.

Batch-level fields (`max_concurrent_extractions`, `use_cache`, `acceleration`,
`security_limits`) are never affected by overrides.

**Signature:**

```go
func (o *ExtractionConfig) WithFileOverrides(overrides FileExtractionConfig) ExtractionConfig
```

##### Normalized()

Normalize configuration for implicit requirements.

Currently handles:
- Auto-enabling `extract_pages` when `result_format` is `ElementBased`, because
  the element transformation requires per-page data to assign correct page numbers.
  Without this, all elements would incorrectly get `page_number=1`.
- Auto-enabling `extract_pages` when chunking is configured, because the chunker
  needs page boundaries to assign correct page numbers to chunks.

**Signature:**

```go
func (o *ExtractionConfig) Normalized() ExtractionConfig
```

##### Validate()

Validate the configuration, returning an error if any settings are invalid.

Checks:
- OCR backend name is supported (catches typos early)
- VLM backend config is present when backend is "vlm"
- Pipeline stage backends and VLM configs are valid
- Structured extraction schema and LLM model are non-empty

**Signature:**

```go
func (o *ExtractionConfig) Validate() error
```

##### NeedsImageProcessing()

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

```go
func (o *ExtractionConfig) NeedsImageProcessing() bool
```


---

### ExtractionMetrics

Collection of all kreuzberg metric instruments.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `ExtractionTotal` | `Counter` | — | Total extractions (attributes: mime_type, extractor, status). |
| `CacheHits` | `Counter` | — | Cache hits. |
| `CacheMisses` | `Counter` | — | Cache misses. |
| `BatchTotal` | `Counter` | — | Total batch requests (attributes: status). |
| `ExtractionDurationMs` | `Histogram` | — | Extraction wall-clock duration in milliseconds (attributes: mime_type, extractor). |
| `ExtractionInputBytes` | `Histogram` | — | Input document size in bytes (attributes: mime_type). |
| `ExtractionOutputBytes` | `Histogram` | — | Output content size in bytes (attributes: mime_type). |
| `PipelineDurationMs` | `Histogram` | — | Pipeline stage duration in milliseconds (attributes: stage). |
| `OcrDurationMs` | `Histogram` | — | OCR duration in milliseconds (attributes: backend, language). |
| `BatchDurationMs` | `Histogram` | — | Batch total duration in milliseconds. |
| `ConcurrentExtractions` | `UpDownCounter` | — | Currently in-flight extractions. |


---

### ExtractionRequest

A request to extract content from a single document.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Source` | `ExtractionSource` | — | Where to read the document from. |
| `Config` | `ExtractionConfig` | — | Base extraction configuration. |
| `FileOverrides` | `*FileExtractionConfig` | `nil` | Optional per-file overrides (merged on top of `config`). |

#### Methods

##### File()

Create a file-based extraction request.

**Signature:**

```go
func (o *ExtractionRequest) File(path string, config ExtractionConfig) ExtractionRequest
```

##### FileWithMime()

Create a file-based extraction request with a MIME type hint.

**Signature:**

```go
func (o *ExtractionRequest) FileWithMime(path string, mimeHint string, config ExtractionConfig) ExtractionRequest
```

##### Bytes()

Create a bytes-based extraction request.

**Signature:**

```go
func (o *ExtractionRequest) Bytes(data []byte, mimeType string, config ExtractionConfig) ExtractionRequest
```

##### WithOverrides()

Set per-file overrides on this request.

**Signature:**

```go
func (o *ExtractionRequest) WithOverrides(overrides FileExtractionConfig) ExtractionRequest
```


---

### ExtractionResult

General extraction result used by the core extraction API.

This is the main result type returned by all extraction functions.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Content` | `string` | `nil` | The extracted text content |
| `MimeType` | `Str` | `nil` | The detected MIME type |
| `Metadata` | `Metadata` | `nil` | Document metadata |
| `Tables` | `[]Table` | `nil` | Tables extracted from the document |
| `DetectedLanguages` | `*[]string` | `nil` | Detected languages |
| `Chunks` | `*[]Chunk` | `nil` | Text chunks when chunking is enabled. When chunking configuration is provided, the content is split into overlapping chunks for efficient processing. Each chunk contains the text, optional embeddings (if enabled), and metadata about its position. |
| `Images` | `*[]ExtractedImage` | `nil` | Extracted images from the document. When image extraction is enabled via `ImageExtractionConfig`, this field contains all images found in the document with their raw data and metadata. Each image may optionally contain a nested `ocr_result` if OCR was performed. |
| `Pages` | `*[]PageContent` | `nil` | Per-page content when page extraction is enabled. When page extraction is configured, the document is split into per-page content with tables and images mapped to their respective pages. |
| `Elements` | `*[]Element` | `nil` | Semantic elements when element-based result format is enabled. When result_format is set to ElementBased, this field contains semantic elements with type classification, unique identifiers, and metadata for Unstructured-compatible element-based processing. |
| `DjotContent` | `*DjotContent` | `nil` | Rich Djot content structure (when extracting Djot documents). When extracting Djot documents with structured extraction enabled, this field contains the full semantic structure including: - Block-level elements with nesting - Inline formatting with attributes - Links, images, footnotes - Math expressions - Complete attribute information The `content` field still contains plain text for backward compatibility. Always `None` for non-Djot documents. |
| `OcrElements` | `*[]OcrElement` | `nil` | OCR elements with full spatial and confidence metadata. When OCR is performed with element extraction enabled, this field contains the structured representation of detected text including: - Bounding geometry (rectangles or quadrilaterals) - Confidence scores (detection and recognition) - Rotation information - Hierarchical relationships (Tesseract only) This field preserves all metadata that would otherwise be lost when converting to plain text or markdown output formats. Only populated when `OcrElementConfig.include_elements` is true. |
| `Document` | `*DocumentStructure` | `nil` | Structured document tree (when document structure extraction is enabled). When `include_document_structure` is true in `ExtractionConfig`, this field contains the full hierarchical representation of the document including: - Heading-driven section nesting - Table grids with cell-level metadata - Content layer classification (body, header, footer, footnote) - Inline text annotations (formatting, links) - Bounding boxes and page numbers Independent of `result_format` — can be combined with Unified or ElementBased. |
| `QualityScore` | `*float64` | `nil` | Document quality score from quality analysis. A value between 0.0 and 1.0 indicating the overall text quality. Previously stored in `metadata.additional["quality_score"]`. |
| `ProcessingWarnings` | `[]ProcessingWarning` | `nil` | Non-fatal warnings collected during processing pipeline stages. Captures errors from optional pipeline features (embedding, chunking, language detection, output formatting) that don't prevent extraction but may indicate degraded results. Previously stored as individual keys in `metadata.additional`. |
| `Annotations` | `*[]PdfAnnotation` | `nil` | PDF annotations extracted from the document. When annotation extraction is enabled via `PdfConfig.extract_annotations`, this field contains text notes, highlights, links, stamps, and other annotations found in PDF documents. |
| `Children` | `*[]ArchiveEntry` | `nil` | Nested extraction results from archive contents. When extracting archives, each processable file inside produces its own full extraction result. Set to `None` for non-archive formats. Use `max_archive_depth` in config to control recursion depth. |
| `Uris` | `*[]Uri` | `nil` | URIs/links discovered during document extraction. Contains hyperlinks, image references, citations, email addresses, and other URI-like references found in the document. Always extracted when present in the source document. |
| `StructuredOutput` | `*interface{}` | `nil` | Structured extraction output from LLM-based JSON schema extraction. When `structured_extraction` is configured in `ExtractionConfig`, the extracted document content is sent to a VLM with the provided JSON schema. The response is parsed and stored here as a JSON value matching the schema. |
| `CodeIntelligence` | `*ProcessResult` | `nil` | Code intelligence results from tree-sitter analysis. Populated when extracting source code files with the `tree-sitter` feature. Contains metrics, structural analysis, imports/exports, comments, docstrings, symbols, diagnostics, and optionally chunked code segments. |
| `LlmUsage` | `*[]LlmUsage` | `nil` | LLM token usage and cost data for all LLM calls made during this extraction. Contains one entry per LLM call. Multiple entries are produced when VLM OCR, structured extraction, and/or LLM embeddings all run during the same extraction. `None` when no LLM was used. |
| `FormattedContent` | `*string` | `nil` | Pre-rendered content in the requested output format. Populated during `derive_extraction_result` before tree derivation consumes element data. `apply_output_format` swaps this into `content` at the end of the pipeline, after post-processors have operated on plain text. |
| `OcrInternalDocument` | `*InternalDocument` | `nil` | Structured hOCR document for the OCR+layout pipeline. When tesseract produces hOCR output, the parsed `InternalDocument` carries paragraph structure with bounding boxes and confidence scores. The layout classification step enriches these elements before final rendering. |


---

### ExtractionServiceBuilder

Builder for composing an extraction service with Tower middleware layers.

Layers are applied in the order: Tracing → Metrics → Timeout → ConcurrencyLimit → Service.

#### Methods

##### Default()

**Signature:**

```go
func (o *ExtractionServiceBuilder) Default() ExtractionServiceBuilder
```

##### WithTimeout()

Add a per-request timeout.

**Signature:**

```go
func (o *ExtractionServiceBuilder) WithTimeout(duration time.Duration) ExtractionServiceBuilder
```

##### WithConcurrencyLimit()

Limit concurrent in-flight extractions.

**Signature:**

```go
func (o *ExtractionServiceBuilder) WithConcurrencyLimit(max int) ExtractionServiceBuilder
```

##### WithTracing()

Add a tracing span to each extraction request.

**Signature:**

```go
func (o *ExtractionServiceBuilder) WithTracing() ExtractionServiceBuilder
```

##### WithMetrics()

Add metrics recording to each extraction request.

Requires the `otel` feature. This is a no-op when `otel` is not enabled.

**Signature:**

```go
func (o *ExtractionServiceBuilder) WithMetrics() ExtractionServiceBuilder
```

##### Build()

Build the service stack, returning a type-erased cloneable service.

Layer order (outermost to innermost):
`Tracing → Metrics → Timeout → ConcurrencyLimit → ExtractionService`

**Signature:**

```go
func (o *ExtractionServiceBuilder) Build() BoxCloneService
```


---

### FictionBookExtractor

FictionBook document extractor.

Supports FictionBook 2.0 format with proper section hierarchy and inline formatting.

#### Methods

##### Default()

**Signature:**

```go
func (o *FictionBookExtractor) Default() FictionBookExtractor
```

##### Name()

**Signature:**

```go
func (o *FictionBookExtractor) Name() string
```

##### Version()

**Signature:**

```go
func (o *FictionBookExtractor) Version() string
```

##### Initialize()

**Signature:**

```go
func (o *FictionBookExtractor) Initialize() error
```

##### Shutdown()

**Signature:**

```go
func (o *FictionBookExtractor) Shutdown() error
```

##### Description()

**Signature:**

```go
func (o *FictionBookExtractor) Description() string
```

##### Author()

**Signature:**

```go
func (o *FictionBookExtractor) Author() string
```

##### ExtractBytes()

**Signature:**

```go
func (o *FictionBookExtractor) ExtractBytes(content []byte, mimeType string, config ExtractionConfig) (InternalDocument, error)
```

##### SupportedMimeTypes()

**Signature:**

```go
func (o *FictionBookExtractor) SupportedMimeTypes() []string
```

##### Priority()

**Signature:**

```go
func (o *FictionBookExtractor) Priority() int32
```


---

### FictionBookMetadata

FictionBook (FB2) metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Genres` | `[]string` | `nil` | Genres |
| `Sequences` | `[]string` | `nil` | Sequences |
| `Annotation` | `*string` | `nil` | Annotation |


---

### FileBytes

An owned buffer of file bytes.

On non-WASM platforms this may be backed by a memory-mapped file (zero heap
allocation for the file contents) or by a `Vec<u8>` for small files.
On WASM it is always a `Vec<u8>`.

Implements `Deref<Target = [u8]>` so callers can pass `&FileBytes` as `&[u8]`
without any additional copy.

#### Methods

##### Deref()

**Signature:**

```go
func (o *FileBytes) Deref() []byte
```

##### AsRef()

**Signature:**

```go
func (o *FileBytes) AsRef() []byte
```


---

### FileExtractionConfig

Per-file extraction configuration overrides for batch processing.

All fields are `Option<T>` — `nil` means "use the batch-level default."
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
| `EnableQualityProcessing` | `*bool` | `nil` | Override quality post-processing for this file. |
| `Ocr` | `*OcrConfig` | `nil` | Override OCR configuration for this file (None in the Option = use batch default). |
| `ForceOcr` | `*bool` | `nil` | Override force OCR for this file. |
| `ForceOcrPages` | `*[]int` | `nil` | Override force OCR pages for this file (1-indexed page numbers). |
| `DisableOcr` | `*bool` | `nil` | Override disable OCR for this file. |
| `Chunking` | `*ChunkingConfig` | `nil` | Override chunking configuration for this file. |
| `ContentFilter` | `*ContentFilterConfig` | `nil` | Override content filtering configuration for this file. |
| `Images` | `*ImageExtractionConfig` | `nil` | Override image extraction configuration for this file. |
| `PdfOptions` | `*PdfConfig` | `nil` | Override PDF options for this file. |
| `TokenReduction` | `*TokenReductionConfig` | `nil` | Override token reduction for this file. |
| `LanguageDetection` | `*LanguageDetectionConfig` | `nil` | Override language detection for this file. |
| `Pages` | `*PageConfig` | `nil` | Override page extraction for this file. |
| `Postprocessor` | `*PostProcessorConfig` | `nil` | Override post-processor for this file. |
| `HtmlOptions` | `*ConversionOptions` | `nil` | Override HTML conversion options for this file. |
| `ResultFormat` | `*OutputFormat` | `OutputFormat.Plain` | Override result format for this file. |
| `OutputFormat` | `*OutputFormat` | `OutputFormat.Plain` | Override output content format for this file. |
| `IncludeDocumentStructure` | `*bool` | `nil` | Override document structure output for this file. |
| `Layout` | `*LayoutDetectionConfig` | `nil` | Override layout detection for this file. |
| `TimeoutSecs` | `*uint64` | `nil` | Override per-file extraction timeout in seconds. When set, the extraction for this file will be canceled after the specified duration. A timed-out file produces an error result without affecting other files in the batch. |
| `TreeSitter` | `*TreeSitterConfig` | `nil` | Override tree-sitter configuration for this file. |
| `StructuredExtraction` | `*StructuredExtractionConfig` | `nil` | Override structured extraction configuration for this file. When set, enables LLM-based structured extraction with a JSON schema for this specific file. The extracted content is sent to a VLM/LLM and the response is parsed according to the provided schema. |


---

### FileHeader

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Flags` | `uint32` | — | Flags |

#### Methods

##### Parse()

**Signature:**

```go
func (o *FileHeader) Parse(data []byte) (FileHeader, error)
```

##### IsCompressed()

Whether section streams are zlib/deflate-compressed.

**Signature:**

```go
func (o *FileHeader) IsCompressed() bool
```

##### IsEncrypted()

Whether the document is password-encrypted.

**Signature:**

```go
func (o *FileHeader) IsEncrypted() bool
```

##### IsDistribute()

Whether the document is a distribution document (text in ViewText/).

**Signature:**

```go
func (o *FileHeader) IsDistribute() bool
```


---

### FontScheme

Font scheme containing major (heading) and minor (body) fonts.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Name` | `string` | `nil` | Font scheme name. |
| `MajorLatin` | `*string` | `nil` | Major (heading) font - Latin script. |
| `MajorEastAsian` | `*string` | `nil` | Major (heading) font - East Asian script. |
| `MajorComplexScript` | `*string` | `nil` | Major (heading) font - Complex script. |
| `MinorLatin` | `*string` | `nil` | Minor (body) font - Latin script. |
| `MinorEastAsian` | `*string` | `nil` | Minor (body) font - East Asian script. |
| `MinorComplexScript` | `*string` | `nil` | Minor (body) font - Complex script. |


---

### Footnote

Footnote in Djot.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Label` | `string` | — | Footnote label |
| `Content` | `[]FormattedBlock` | — | Footnote content blocks |


---

### FormattedBlock

Block-level element in a Djot document.

Represents structural elements like headings, paragraphs, lists, code blocks, etc.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `BlockType` | `BlockType` | — | Type of block element |
| `Level` | `*int` | `nil` | Heading level (1-6) for headings, or nesting level for lists |
| `InlineContent` | `[]InlineElement` | — | Inline content within the block |
| `Attributes` | `*Attributes` | `nil` | Element attributes (classes, IDs, key-value pairs) |
| `Language` | `*string` | `nil` | Language identifier for code blocks |
| `Code` | `*string` | `nil` | Raw code content for code blocks |
| `Children` | `[]FormattedBlock` | — | Nested blocks for containers (blockquotes, list items, divs) |


---

### GenericCache

#### Methods

##### New()

**Signature:**

```go
func (o *GenericCache) New(cacheType string, cacheDir string, maxAgeDays float64, maxCacheSizeMb float64, minFreeSpaceMb float64) (GenericCache, error)
```

##### Get()

**Signature:**

```go
func (o *GenericCache) Get(cacheKey string, sourceFile string, namespace string, ttlOverrideSecs uint64) (*[]byte, error)
```

##### GetDefault()

Backward-compatible get without namespace/TTL.

**Signature:**

```go
func (o *GenericCache) GetDefault(cacheKey string, sourceFile string) (*[]byte, error)
```

##### Set()

**Signature:**

```go
func (o *GenericCache) Set(cacheKey string, data []byte, sourceFile string, namespace string, ttlSecs uint64) error
```

##### SetDefault()

Backward-compatible set without namespace/TTL.

**Signature:**

```go
func (o *GenericCache) SetDefault(cacheKey string, data []byte, sourceFile string) error
```

##### IsProcessing()

**Signature:**

```go
func (o *GenericCache) IsProcessing(cacheKey string) (bool, error)
```

##### MarkProcessing()

**Signature:**

```go
func (o *GenericCache) MarkProcessing(cacheKey string) error
```

##### MarkComplete()

**Signature:**

```go
func (o *GenericCache) MarkComplete(cacheKey string) error
```

##### Clear()

**Signature:**

```go
func (o *GenericCache) Clear() (UsizeF64, error)
```

##### DeleteNamespace()

Delete all cache entries under a namespace.

Removes the namespace subdirectory and all its contents.
Returns (files_removed, mb_freed).

**Signature:**

```go
func (o *GenericCache) DeleteNamespace(namespace string) (UsizeF64, error)
```

##### GetStats()

**Signature:**

```go
func (o *GenericCache) GetStats() (CacheStats, error)
```

##### GetStatsFiltered()

Get cache stats, optionally filtered to a specific namespace.

**Signature:**

```go
func (o *GenericCache) GetStatsFiltered(namespace string) (CacheStats, error)
```

##### CacheDir()

**Signature:**

```go
func (o *GenericCache) CacheDir() string
```

##### CacheType()

**Signature:**

```go
func (o *GenericCache) CacheType() string
```


---

### GridCell

Individual grid cell with position and span metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Content` | `string` | — | Cell text content. |
| `Row` | `uint32` | — | Zero-indexed row position. |
| `Col` | `uint32` | — | Zero-indexed column position. |
| `RowSpan` | `uint32` | — | Number of rows this cell spans. |
| `ColSpan` | `uint32` | — | Number of columns this cell spans. |
| `IsHeader` | `bool` | — | Whether this is a header cell. |
| `Bbox` | `*BoundingBox` | `nil` | Bounding box for this cell (if available). |


---

### GzipExtractor

Gzip archive extractor.

Decompresses gzip files and extracts text content from the compressed data.

#### Methods

##### Default()

**Signature:**

```go
func (o *GzipExtractor) Default() GzipExtractor
```

##### Name()

**Signature:**

```go
func (o *GzipExtractor) Name() string
```

##### Version()

**Signature:**

```go
func (o *GzipExtractor) Version() string
```

##### Initialize()

**Signature:**

```go
func (o *GzipExtractor) Initialize() error
```

##### Shutdown()

**Signature:**

```go
func (o *GzipExtractor) Shutdown() error
```

##### Description()

**Signature:**

```go
func (o *GzipExtractor) Description() string
```

##### Author()

**Signature:**

```go
func (o *GzipExtractor) Author() string
```

##### ExtractBytes()

**Signature:**

```go
func (o *GzipExtractor) ExtractBytes(content []byte, mimeType string, config ExtractionConfig) (InternalDocument, error)
```

##### SupportedMimeTypes()

**Signature:**

```go
func (o *GzipExtractor) SupportedMimeTypes() []string
```

##### Priority()

**Signature:**

```go
func (o *GzipExtractor) Priority() int32
```

##### AsSyncExtractor()

**Signature:**

```go
func (o *GzipExtractor) AsSyncExtractor() *SyncExtractor
```

##### ExtractSync()

**Signature:**

```go
func (o *GzipExtractor) ExtractSync(content []byte, mimeType string, config ExtractionConfig) (InternalDocument, error)
```


---

### HeaderFooter

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Paragraphs` | `[]Paragraph` | `nil` | Paragraphs |
| `Tables` | `[]Table` | `nil` | Tables extracted from the document |
| `HeaderType` | `HeaderFooterType` | `HeaderFooterType.Default` | Header type (header footer type) |


---

### HeaderMetadata

Header/heading element metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Level` | `uint8` | — | Header level: 1 (h1) through 6 (h6) |
| `Text` | `string` | — | Normalized text content of the header |
| `Id` | `*string` | `nil` | HTML id attribute if present |
| `Depth` | `int` | — | Document tree depth at the header element |
| `HtmlOffset` | `int` | — | Byte offset in original HTML document |


---

### HeadingContext

Heading context for a chunk within a Markdown document.

Contains the heading hierarchy from document root to this chunk's section.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Headings` | `[]HeadingLevel` | — | The heading hierarchy from document root to this chunk's section. Index 0 is the outermost (h1), last element is the most specific. |


---

### HeadingLevel

A single heading in the hierarchy.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Level` | `uint8` | — | Heading depth (1 = h1, 2 = h2, etc.) |
| `Text` | `string` | — | The text content of the heading. |


---

### HierarchicalBlock

A text block with hierarchy level assignment.

Represents a block of text with semantic heading information extracted from
font size clustering and hierarchical analysis.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Text` | `string` | — | The text content of this block |
| `FontSize` | `float32` | — | The font size of the text in this block |
| `Level` | `string` | — | The hierarchy level of this block (H1-H6 or Body) Levels correspond to HTML heading tags: - "h1": Top-level heading - "h2": Secondary heading - "h3": Tertiary heading - "h4": Quaternary heading - "h5": Quinary heading - "h6": Senary heading - "body": Body text (no heading level) |
| `Bbox` | `*F32F32F32F32` | `nil` | Bounding box information for the block Contains coordinates as (left, top, right, bottom) in PDF units. |


---

### HierarchyConfig

Hierarchy extraction configuration for PDF text structure analysis.

Enables extraction of document hierarchy levels (H1-H6) based on font size
clustering and semantic analysis. When enabled, hierarchical blocks are
included in page content.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Enabled` | `bool` | `true` | Enable hierarchy extraction |
| `KClusters` | `int` | `3` | Number of font size clusters to use for hierarchy levels (1-7) Default: 6, which provides H1-H6 heading levels with body text. Larger values create more fine-grained hierarchy levels. |
| `IncludeBbox` | `bool` | `true` | Include bounding box information in hierarchy blocks |
| `OcrCoverageThreshold` | `*float32` | `nil` | OCR coverage threshold for smart OCR triggering (0.0-1.0) Determines when OCR should be triggered based on text block coverage. OCR is triggered when text blocks cover less than this fraction of the page. Default: 0.5 (trigger OCR if less than 50% of page has text) |

#### Methods

##### Default()

**Signature:**

```go
func (o *HierarchyConfig) Default() HierarchyConfig
```


---

### HocrWord

Represents a word extracted from hOCR (or any source) with position and confidence information.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Text` | `string` | — | Text |
| `Left` | `uint32` | — | Left |
| `Top` | `uint32` | — | Top |
| `Width` | `uint32` | — | Width |
| `Height` | `uint32` | — | Height |
| `Confidence` | `float64` | — | Confidence |

#### Methods

##### Right()

Get the right edge position.

**Signature:**

```go
func (o *HocrWord) Right() uint32
```

##### Bottom()

Get the bottom edge position.

**Signature:**

```go
func (o *HocrWord) Bottom() uint32
```

##### YCenter()

Get the vertical center position.

**Signature:**

```go
func (o *HocrWord) YCenter() float64
```

##### XCenter()

Get the horizontal center position.

**Signature:**

```go
func (o *HocrWord) XCenter() float64
```


---

### HtmlExtractor

HTML document extractor using html-to-markdown.

#### Methods

##### Default()

**Signature:**

```go
func (o *HtmlExtractor) Default() HtmlExtractor
```

##### Name()

**Signature:**

```go
func (o *HtmlExtractor) Name() string
```

##### Version()

**Signature:**

```go
func (o *HtmlExtractor) Version() string
```

##### Initialize()

**Signature:**

```go
func (o *HtmlExtractor) Initialize() error
```

##### Shutdown()

**Signature:**

```go
func (o *HtmlExtractor) Shutdown() error
```

##### ExtractSync()

**Signature:**

```go
func (o *HtmlExtractor) ExtractSync(content []byte, mimeType string, config ExtractionConfig) (InternalDocument, error)
```

##### ExtractBytes()

**Signature:**

```go
func (o *HtmlExtractor) ExtractBytes(content []byte, mimeType string, config ExtractionConfig) (InternalDocument, error)
```

##### SupportedMimeTypes()

**Signature:**

```go
func (o *HtmlExtractor) SupportedMimeTypes() []string
```

##### Priority()

**Signature:**

```go
func (o *HtmlExtractor) Priority() int32
```

##### AsSyncExtractor()

**Signature:**

```go
func (o *HtmlExtractor) AsSyncExtractor() *SyncExtractor
```


---

### HtmlMetadata

HTML metadata extracted from HTML documents.

Includes document-level metadata, Open Graph data, Twitter Card metadata,
and extracted structural elements (headers, links, images, structured data).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Title` | `*string` | `nil` | Document title from `<title>` tag |
| `Description` | `*string` | `nil` | Document description from `<meta name="description">` tag |
| `Keywords` | `[]string` | `nil` | Document keywords from `<meta name="keywords">` tag, split on commas |
| `Author` | `*string` | `nil` | Document author from `<meta name="author">` tag |
| `CanonicalUrl` | `*string` | `nil` | Canonical URL from `<link rel="canonical">` tag |
| `BaseHref` | `*string` | `nil` | Base URL from `<base href="">` tag for resolving relative URLs |
| `Language` | `*string` | `nil` | Document language from `lang` attribute |
| `TextDirection` | `*TextDirection` | `TextDirection.LeftToRight` | Document text direction from `dir` attribute |
| `OpenGraph` | `map[string]string` | `nil` | Open Graph metadata (og:* properties) for social media Keys like "title", "description", "image", "url", etc. |
| `TwitterCard` | `map[string]string` | `nil` | Twitter Card metadata (twitter:* properties) Keys like "card", "site", "creator", "title", "description", "image", etc. |
| `MetaTags` | `map[string]string` | `nil` | Additional meta tags not covered by specific fields Keys are meta name/property attributes, values are content |
| `Headers` | `[]HeaderMetadata` | `nil` | Extracted header elements with hierarchy |
| `Links` | `[]LinkMetadata` | `nil` | Extracted hyperlinks with type classification |
| `Images` | `[]ImageMetadataType` | `nil` | Extracted images with source and dimensions |
| `StructuredData` | `[]StructuredData` | `nil` | Extracted structured data blocks |

#### Methods

##### IsEmpty()

Check if metadata is empty (no meaningful content extracted).

**Signature:**

```go
func (o *HtmlMetadata) IsEmpty() bool
```

##### From()

**Signature:**

```go
func (o *HtmlMetadata) From(metadata HtmlMetadata) HtmlMetadata
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
| `Css` | `*string` | `nil` | Inline CSS string injected into the output after the theme stylesheet. Concatenated after `css_file` content when both are set. |
| `CssFile` | `*string` | `nil` | Path to a CSS file loaded once at renderer construction time. Concatenated before `css` when both are set. |
| `Theme` | `HtmlTheme` | `HtmlTheme.Unstyled` | Built-in colour/typography theme. Default: `HtmlTheme.Unstyled`. |
| `ClassPrefix` | `string` | `nil` | CSS class prefix applied to every emitted class name. Default: `"kb-"`. Change this if your host application already uses classes that start with `kb-`. |
| `EmbedCss` | `bool` | `true` | When `True` (default), write the resolved CSS into a `<style>` block immediately after the opening `<div class="{prefix}doc">`. Set to `False` to emit only the structural markup and wire up your own stylesheet targeting the `kb-*` class names. |

#### Methods

##### Default()

**Signature:**

```go
func (o *HtmlOutputConfig) Default() HtmlOutputConfig
```


---

### HwpDocument

An extracted HWP document, consisting of one or more body-text sections.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Sections` | `[]Section` | `nil` | All sections from all BodyText/SectionN streams. |

#### Methods

##### ExtractText()

Concatenate the text of every paragraph in every section, separated by
newlines.

**Signature:**

```go
func (o *HwpDocument) ExtractText() string
```


---

### HwpExtractor

Extractor for Hangul Word Processor (.hwp) files.

Supports HWP 5.0 format, the standard document format in South Korea.

#### Methods

##### Default()

**Signature:**

```go
func (o *HwpExtractor) Default() HwpExtractor
```

##### Name()

**Signature:**

```go
func (o *HwpExtractor) Name() string
```

##### Version()

**Signature:**

```go
func (o *HwpExtractor) Version() string
```

##### Initialize()

**Signature:**

```go
func (o *HwpExtractor) Initialize() error
```

##### Shutdown()

**Signature:**

```go
func (o *HwpExtractor) Shutdown() error
```

##### Description()

**Signature:**

```go
func (o *HwpExtractor) Description() string
```

##### Author()

**Signature:**

```go
func (o *HwpExtractor) Author() string
```

##### ExtractBytes()

**Signature:**

```go
func (o *HwpExtractor) ExtractBytes(content []byte, mimeType string, config ExtractionConfig) (InternalDocument, error)
```

##### SupportedMimeTypes()

**Signature:**

```go
func (o *HwpExtractor) SupportedMimeTypes() []string
```

##### Priority()

**Signature:**

```go
func (o *HwpExtractor) Priority() int32
```


---

### ImageDpiConfig

Image extraction DPI configuration (internal use).

**Note:** This is an internal type used for image preprocessing.
For the main extraction configuration, see `crate.core.config.ExtractionConfig`.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `TargetDpi` | `int32` | `300` | Target DPI for image normalization |
| `MaxImageDimension` | `int32` | `4096` | Maximum image dimension (width or height) |
| `AutoAdjustDpi` | `bool` | `true` | Whether to auto-adjust DPI based on content |
| `MinDpi` | `int32` | `72` | Minimum DPI threshold |
| `MaxDpi` | `int32` | `600` | Maximum DPI threshold |

#### Methods

##### Default()

**Signature:**

```go
func (o *ImageDpiConfig) Default() ImageDpiConfig
```


---

### ImageExtractionConfig

Image extraction configuration.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `ExtractImages` | `bool` | `nil` | Extract images from documents |
| `TargetDpi` | `int32` | `nil` | Target DPI for image normalization |
| `MaxImageDimension` | `int32` | `nil` | Maximum dimension for images (width or height) |
| `InjectPlaceholders` | `bool` | `nil` | Whether to inject image reference placeholders into markdown output. When `True` (default), image references like `![Image 1](embedded:p1_i0)` are appended to the markdown. Set to `False` to extract images as data without polluting the markdown output. |
| `AutoAdjustDpi` | `bool` | `nil` | Automatically adjust DPI based on image content |
| `MinDpi` | `int32` | `nil` | Minimum DPI threshold |
| `MaxDpi` | `int32` | `nil` | Maximum DPI threshold |


---

### ImageExtractor

Image extractor for various image formats.

Supports: PNG, JPEG, WebP, BMP, TIFF, GIF.
Extracts dimensions, format, and EXIF metadata.
Optionally runs OCR when configured.
When layout detection is also enabled, uses per-region OCR with
markdown formatting based on detected layout classes.

#### Methods

##### Default()

**Signature:**

```go
func (o *ImageExtractor) Default() ImageExtractor
```

##### Name()

**Signature:**

```go
func (o *ImageExtractor) Name() string
```

##### Version()

**Signature:**

```go
func (o *ImageExtractor) Version() string
```

##### Initialize()

**Signature:**

```go
func (o *ImageExtractor) Initialize() error
```

##### Shutdown()

**Signature:**

```go
func (o *ImageExtractor) Shutdown() error
```

##### Description()

**Signature:**

```go
func (o *ImageExtractor) Description() string
```

##### Author()

**Signature:**

```go
func (o *ImageExtractor) Author() string
```

##### ExtractBytes()

**Signature:**

```go
func (o *ImageExtractor) ExtractBytes(content []byte, mimeType string, config ExtractionConfig) (InternalDocument, error)
```

##### SupportedMimeTypes()

**Signature:**

```go
func (o *ImageExtractor) SupportedMimeTypes() []string
```

##### Priority()

**Signature:**

```go
func (o *ImageExtractor) Priority() int32
```


---

### ImageMetadata

Image metadata extracted from image files.

Includes dimensions, format, and EXIF data.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Width` | `uint32` | — | Image width in pixels |
| `Height` | `uint32` | — | Image height in pixels |
| `Format` | `string` | — | Image format (e.g., "PNG", "JPEG", "TIFF") |
| `Exif` | `map[string]string` | — | EXIF metadata tags |


---

### ImageMetadataType

Image element metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Src` | `string` | — | Image source (URL, data URI, or SVG content) |
| `Alt` | `*string` | `nil` | Alternative text from alt attribute |
| `Title` | `*string` | `nil` | Title attribute |
| `Dimensions` | `*U32U32` | `nil` | Image dimensions as (width, height) if available |
| `ImageType` | `ImageType` | — | Image type classification |
| `Attributes` | `[]StringString` | — | Additional attributes as key-value pairs |


---

### ImageOcrResult

Result of OCR extraction from an image with optional page tracking.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Content` | `string` | — | Extracted text content |
| `Boundaries` | `*[]PageBoundary` | `nil` | Character byte boundaries per frame (for multi-frame TIFFs) |
| `PageContents` | `*[]PageContent` | `nil` | Per-frame content information |


---

### ImagePreprocessingConfig

Image preprocessing configuration for OCR.

These settings control how images are preprocessed before OCR to improve
text recognition quality. Different preprocessing strategies work better
for different document types.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `TargetDpi` | `int32` | `300` | Target DPI for the image (300 is standard, 600 for small text). |
| `AutoRotate` | `bool` | `true` | Auto-detect and correct image rotation. |
| `Deskew` | `bool` | `true` | Correct skew (tilted images). |
| `Denoise` | `bool` | `false` | Remove noise from the image. |
| `ContrastEnhance` | `bool` | `false` | Enhance contrast for better text visibility. |
| `BinarizationMethod` | `string` | `"otsu"` | Binarization method: "otsu", "sauvola", "adaptive". |
| `InvertColors` | `bool` | `false` | Invert colors (white text on black → black on white). |

#### Methods

##### Default()

**Signature:**

```go
func (o *ImagePreprocessingConfig) Default() ImagePreprocessingConfig
```


---

### ImagePreprocessingMetadata

Image preprocessing metadata.

Tracks the transformations applied to an image during OCR preprocessing,
including DPI normalization, resizing, and resampling.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `OriginalDimensions` | `UsizeUsize` | — | Original image dimensions (width, height) in pixels |
| `OriginalDpi` | `F64F64` | — | Original image DPI (horizontal, vertical) |
| `TargetDpi` | `int32` | — | Target DPI from configuration |
| `ScaleFactor` | `float64` | — | Scaling factor applied to the image |
| `AutoAdjusted` | `bool` | — | Whether DPI was auto-adjusted based on content |
| `FinalDpi` | `int32` | — | Final DPI after processing |
| `NewDimensions` | `*UsizeUsize` | `nil` | New dimensions after resizing (if resized) |
| `ResampleMethod` | `string` | — | Resampling algorithm used ("LANCZOS3", "CATMULLROM", etc.) |
| `DimensionClamped` | `bool` | — | Whether dimensions were clamped to max_image_dimension |
| `CalculatedDpi` | `*int32` | `nil` | Calculated optimal DPI (if auto_adjust_dpi enabled) |
| `SkippedResize` | `bool` | — | Whether resize was skipped (dimensions already optimal) |
| `ResizeError` | `*string` | `nil` | Error message if resize failed |


---

### InlineElement

Inline element within a block.

Represents text with formatting, links, images, etc.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `ElementType` | `InlineType` | — | Type of inline element |
| `Content` | `string` | — | Text content |
| `Attributes` | `*Attributes` | `nil` | Element attributes |
| `Metadata` | `*map[string]string` | `nil` | Additional metadata (e.g., href for links, src/alt for images) |


---

### Instant

A platform-aware instant for measuring elapsed time.

On native targets this delegates to `std.time.Instant`.
On `wasm32` targets it is a zero-cost no-op to avoid the `unreachable` trap.

#### Methods

##### Now()

Capture the current instant.

**Signature:**

```go
func (o *Instant) Now() Instant
```

##### ElapsedSecsF64()

Seconds elapsed since this instant was captured (as `f64`).

**Signature:**

```go
func (o *Instant) ElapsedSecsF64() float64
```

##### ElapsedMs()

Milliseconds elapsed since this instant was captured (as `f64`).

**Signature:**

```go
func (o *Instant) ElapsedMs() float64
```

##### ElapsedMillis()

Milliseconds elapsed as `u128` (mirrors `Duration.as_millis`).

**Signature:**

```go
func (o *Instant) ElapsedMillis() U128
```


---

### InternalDocument

The internal flat document representation.

All extractors output this structure. It is converted to the public
`ExtractionResult` and
`DocumentStructure` in the pipeline.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Elements` | `[]InternalElement` | — | All elements in reading order. Append-only during extraction. |
| `Relationships` | `[]Relationship` | — | Relationships between elements (source index → target). Stored separately from elements for cache-friendly iteration. |
| `SourceFormat` | `Str` | — | Source format identifier (e.g., "pdf", "docx", "html", "markdown"). |
| `Metadata` | `Metadata` | — | Document-level metadata (title, author, dates, etc.). |
| `Images` | `[]ExtractedImage` | — | Extracted images (binary data). Referenced by index from `ElementKind.Image`. |
| `Tables` | `[]Table` | — | Extracted tables (structured data). Referenced by index from `ElementKind.Table`. |
| `Uris` | `[]Uri` | — | URIs/links discovered during extraction (hyperlinks, image refs, citations, etc.). |
| `Children` | `*[]ArchiveEntry` | `nil` | Archive children: fully-extracted results for files within an archive. Only populated by archive extractors (ZIP, TAR, 7z, GZIP) when recursive extraction is enabled. Each entry contains the full `ExtractionResult` for a child file that was extracted through the public pipeline. |
| `MimeType` | `Str` | — | MIME type of the source document (e.g., "application/pdf", "text/html"). |
| `ProcessingWarnings` | `[]ProcessingWarning` | — | Non-fatal warnings collected during extraction. |
| `Annotations` | `*[]PdfAnnotation` | `nil` | PDF annotations (links, highlights, notes). |
| `PrebuiltPages` | `*[]PageContent` | `nil` | Pre-built per-page content (set by extractors that track page boundaries natively). When populated, `derive_extraction_result` uses this directly instead of attempting to reconstruct pages from element-level page numbers. |
| `PreRenderedContent` | `*string` | `nil` | Pre-rendered formatted content produced by the extractor itself. When an extractor has direct access to high-quality formatted output (e.g., html-to-markdown produces GFM markdown), it can store that here to bypass the lossy InternalDocument → renderer round-trip. `derive_extraction_result` will use this directly when the requested output format matches `metadata.output_format`. |

#### Methods

##### PushElement()

Push an element and return its index.

**Signature:**

```go
func (o *InternalDocument) PushElement(element InternalElement) uint32
```

##### PushRelationship()

Push a relationship.

**Signature:**

```go
func (o *InternalDocument) PushRelationship(relationship Relationship)
```

##### PushTable()

Push a table and return its index (for use in `ElementKind.Table`).

**Signature:**

```go
func (o *InternalDocument) PushTable(table Table) uint32
```

##### PushImage()

Push an image and return its index (for use in `ElementKind.Image`).

**Signature:**

```go
func (o *InternalDocument) PushImage(image ExtractedImage) uint32
```

##### PushUri()

Push a URI discovered during extraction.
Silently drops URIs beyond `MAX_URIS` to prevent unbounded memory growth.

**Signature:**

```go
func (o *InternalDocument) PushUri(uri Uri)
```

##### Content()

Concatenate all element text into a single string, separated by newlines.

**Signature:**

```go
func (o *InternalDocument) Content() string
```


---

### InternalDocumentBuilder

Builder for constructing `InternalDocument` with an ergonomic push-based API.

Tracks nesting depth automatically for list and quote containers,
and generates deterministic element IDs via blake3 hashing.

#### Methods

##### SourceFormat()

Set the source format identifier (e.g. "docx", "html", "pptx").

**Signature:**

```go
func (o *InternalDocumentBuilder) SourceFormat(format Str)
```

##### SetMetadata()

Set document-level metadata.

**Signature:**

```go
func (o *InternalDocumentBuilder) SetMetadata(metadata Metadata)
```

##### SetMimeType()

Set the MIME type of the source document.

**Signature:**

```go
func (o *InternalDocumentBuilder) SetMimeType(mimeType Str)
```

##### AddWarning()

Add a non-fatal processing warning.

**Signature:**

```go
func (o *InternalDocumentBuilder) AddWarning(warning ProcessingWarning)
```

##### SetPdfAnnotations()

Set document-level PDF annotations (links, highlights, notes).

**Signature:**

```go
func (o *InternalDocumentBuilder) SetPdfAnnotations(annotations []PdfAnnotation)
```

##### PushUri()

Push a URI discovered during extraction.

**Signature:**

```go
func (o *InternalDocumentBuilder) PushUri(uri Uri)
```

##### Build()

Consume the builder and return the constructed `InternalDocument`.

**Signature:**

```go
func (o *InternalDocumentBuilder) Build() InternalDocument
```

##### PushHeading()

Push a heading element.

Auto-sets depth from the heading level and generates an anchor slug
from the heading text.

**Signature:**

```go
func (o *InternalDocumentBuilder) PushHeading(level uint8, text string, page uint32, bbox BoundingBox) uint32
```

##### PushParagraph()

Push a paragraph element.

**Signature:**

```go
func (o *InternalDocumentBuilder) PushParagraph(text string, annotations []TextAnnotation, page uint32, bbox BoundingBox) uint32
```

##### PushList()

Push a `ListStart` marker and increment depth.

**Signature:**

```go
func (o *InternalDocumentBuilder) PushList(ordered bool)
```

##### EndList()

Push a `ListEnd` marker and decrement depth.

**Signature:**

```go
func (o *InternalDocumentBuilder) EndList()
```

##### PushListItem()

Push a list item element at the current depth.

**Signature:**

```go
func (o *InternalDocumentBuilder) PushListItem(text string, ordered bool, annotations []TextAnnotation, page uint32, bbox BoundingBox) uint32
```

##### PushTable()

Push a table element. The table data is stored separately in
`InternalDocument.tables` and referenced by index.

**Signature:**

```go
func (o *InternalDocumentBuilder) PushTable(table Table, page uint32, bbox BoundingBox) uint32
```

##### PushTableFromCells()

Push a table element from a 2D cell grid, building a `Table` struct automatically.

**Signature:**

```go
func (o *InternalDocumentBuilder) PushTableFromCells(cells [][]string, page uint32, bbox BoundingBox) uint32
```

##### PushImage()

Push an image element. The image data is stored separately in
`InternalDocument.images` and referenced by index.

**Signature:**

```go
func (o *InternalDocumentBuilder) PushImage(description string, image ExtractedImage, page uint32, bbox BoundingBox) uint32
```

##### PushCode()

Push a code block element. Language is stored in attributes.

**Signature:**

```go
func (o *InternalDocumentBuilder) PushCode(text string, language string, page uint32, bbox BoundingBox) uint32
```

##### PushFormula()

Push a math formula element.

**Signature:**

```go
func (o *InternalDocumentBuilder) PushFormula(text string, page uint32, bbox BoundingBox) uint32
```

##### PushFootnoteRef()

Push a footnote reference marker.

Creates a `FootnoteRef` element with `anchor = key` and also records
a `Relationship` with `RelationshipTarget.Key(key)` so the derivation
step can resolve it to the definition.

**Signature:**

```go
func (o *InternalDocumentBuilder) PushFootnoteRef(marker string, key string, page uint32) uint32
```

##### PushFootnoteDefinition()

Push a footnote definition element with `anchor = key`.

**Signature:**

```go
func (o *InternalDocumentBuilder) PushFootnoteDefinition(text string, key string, page uint32) uint32
```

##### PushCitation()

Push a citation / bibliographic reference element.

**Signature:**

```go
func (o *InternalDocumentBuilder) PushCitation(text string, key string, page uint32) uint32
```

##### PushQuoteStart()

Push a `QuoteStart` marker and increment depth.

**Signature:**

```go
func (o *InternalDocumentBuilder) PushQuoteStart()
```

##### PushQuoteEnd()

Push a `QuoteEnd` marker and decrement depth.

**Signature:**

```go
func (o *InternalDocumentBuilder) PushQuoteEnd()
```

##### PushPageBreak()

Push a page break marker at depth 0.

**Signature:**

```go
func (o *InternalDocumentBuilder) PushPageBreak()
```

##### PushSlide()

Push a slide element.

**Signature:**

```go
func (o *InternalDocumentBuilder) PushSlide(number uint32, title string, page uint32) uint32
```

##### PushAdmonition()

Push an admonition / callout element (note, warning, tip, etc.).
Kind and optional title are stored in attributes.

**Signature:**

```go
func (o *InternalDocumentBuilder) PushAdmonition(kind string, title string, page uint32) uint32
```

##### PushRawBlock()

Push a raw block preserved verbatim. Format is stored in attributes.

**Signature:**

```go
func (o *InternalDocumentBuilder) PushRawBlock(format string, content string, page uint32) uint32
```

##### PushMetadataBlock()

Push a structured metadata block (frontmatter, email headers).
Entries are stored in attributes.

**Signature:**

```go
func (o *InternalDocumentBuilder) PushMetadataBlock(entries []StringString, page uint32) uint32
```

##### PushTitle()

Push a title element.

**Signature:**

```go
func (o *InternalDocumentBuilder) PushTitle(text string, page uint32, bbox BoundingBox) uint32
```

##### PushDefinitionTerm()

Push a definition term element.

**Signature:**

```go
func (o *InternalDocumentBuilder) PushDefinitionTerm(text string, page uint32) uint32
```

##### PushDefinitionDescription()

Push a definition description element.

**Signature:**

```go
func (o *InternalDocumentBuilder) PushDefinitionDescription(text string, page uint32) uint32
```

##### PushOcrText()

Push an OCR text element with OCR-specific fields populated.

**Signature:**

```go
func (o *InternalDocumentBuilder) PushOcrText(text string, level OcrElementLevel, geometry OcrBoundingGeometry, confidence OcrConfidence, rotation OcrRotation, page uint32, bbox BoundingBox) uint32
```

##### PushGroupStart()

Push a `GroupStart` marker and increment depth.

**Signature:**

```go
func (o *InternalDocumentBuilder) PushGroupStart(label string, page uint32)
```

##### PushGroupEnd()

Push a `GroupEnd` marker and decrement depth.

**Signature:**

```go
func (o *InternalDocumentBuilder) PushGroupEnd()
```

##### PushRelationship()

Push a relationship between two elements.

**Signature:**

```go
func (o *InternalDocumentBuilder) PushRelationship(source uint32, target RelationshipTarget, kind RelationshipKind)
```

##### SetAnchor()

Set the anchor on an already-pushed element.

**Signature:**

```go
func (o *InternalDocumentBuilder) SetAnchor(index uint32, anchor string)
```

##### SetLayer()

Set the content layer on an already-pushed element.

**Signature:**

```go
func (o *InternalDocumentBuilder) SetLayer(index uint32, layer ContentLayer)
```

##### SetAttributes()

Set attributes on an already-pushed element.

**Signature:**

```go
func (o *InternalDocumentBuilder) SetAttributes(index uint32, attributes AHashMap)
```

##### SetAnnotations()

Set annotations on an already-pushed element.

**Signature:**

```go
func (o *InternalDocumentBuilder) SetAnnotations(index uint32, annotations []TextAnnotation)
```

##### SetText()

Set the text content of an already-pushed element.

**Signature:**

```go
func (o *InternalDocumentBuilder) SetText(index uint32, text string)
```

##### PushElement()

Push a pre-constructed `InternalElement` directly.

Useful when the caller needs to construct an element with fields
that the builder's convenience methods don't cover (e.g. an image
element without `ExtractedImage` data).

**Signature:**

```go
func (o *InternalDocumentBuilder) PushElement(element InternalElement) uint32
```


---

### InternalElement

A single element in the internal flat document.

Elements are appended in reading order during extraction. The `depth` field
and optional container markers enable tree reconstruction in the derivation step.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Id` | `InternalElementId` | — | Deterministic identifier. |
| `Kind` | `ElementKind` | — | What kind of content this element represents. |
| `Text` | `string` | — | Primary text content. Empty for non-text elements (images, page breaks). |
| `Depth` | `uint16` | — | Nesting depth (0 = root level). Extractors set this based on heading level, list indent, blockquote depth, etc. The tree derivation step uses depth changes to reconstruct parent-child relationships. |
| `Page` | `*uint32` | `nil` | Page number (1-indexed). `None` for non-paginated formats. |
| `Bbox` | `*BoundingBox` | `nil` | Bounding box in document coordinates. |
| `Layer` | `ContentLayer` | — | Content layer classification (Body, Header, Footer, Footnote). |
| `Annotations` | `[]TextAnnotation` | — | Inline annotations (formatting, links) on this element's text content. Byte-range based, reuses the existing `TextAnnotation` type. |
| `Attributes` | `*AHashMap` | `nil` | Format-specific key-value attributes. Used for CSS classes, LaTeX env names, slide layout names, etc. |
| `Anchor` | `*string` | `nil` | Optional anchor/key for this element. Used by the relationship resolver to match references to targets. Examples: heading slug `"introduction"`, footnote label `"fn1"`, citation key `"smith2024"`, figure label `"fig:diagram"`. |
| `OcrGeometry` | `*OcrBoundingGeometry` | `nil` | OCR bounding geometry (rectangle or quadrilateral). |
| `OcrConfidence` | `*OcrConfidence` | `nil` | OCR confidence scores (detection + recognition). |
| `OcrRotation` | `*OcrRotation` | `nil` | OCR rotation metadata. |

#### Methods

##### Text()

Create a simple text element with minimal fields.

**Signature:**

```go
func (o *InternalElement) Text(kind ElementKind, text string, depth uint16) InternalElement
```

##### WithPage()

Set the page number.

**Signature:**

```go
func (o *InternalElement) WithPage(page uint32) InternalElement
```

##### WithBbox()

Set the bounding box.

**Signature:**

```go
func (o *InternalElement) WithBbox(bbox BoundingBox) InternalElement
```

##### WithLayer()

Set the content layer.

**Signature:**

```go
func (o *InternalElement) WithLayer(layer ContentLayer) InternalElement
```

##### WithAnchor()

Set the anchor key.

**Signature:**

```go
func (o *InternalElement) WithAnchor(anchor string) InternalElement
```

##### WithAnnotations()

Set annotations.

**Signature:**

```go
func (o *InternalElement) WithAnnotations(annotations []TextAnnotation) InternalElement
```

##### WithAttributes()

Set attributes.

**Signature:**

```go
func (o *InternalElement) WithAttributes(attributes AHashMap) InternalElement
```

##### WithIndex()

Regenerate the ID with the correct index (call after pushing to the document).

**Signature:**

```go
func (o *InternalElement) WithIndex(index uint32) InternalElement
```


---

### InternalElementId

Deterministic element identifier, generated via blake3 hashing.

Format: `"ie-{12 hex chars}"` (48 bits from blake3, ~281 trillion address space).
Same input always produces the same ID, enabling diffing and caching.

#### Methods

##### Generate()

Generate a deterministic ID from element content.

Hashes the element kind discriminant, text content, page number, and
positional index using blake3. Takes 48 bits (6 bytes) of the hash.

**Signature:**

```go
func (o *InternalElementId) Generate(kindDiscriminant string, text string, page uint32, index uint32) InternalElementId
```

##### AsStr()

Get the ID as a string slice.

**Signature:**

```go
func (o *InternalElementId) AsStr() string
```

##### Fmt()

**Signature:**

```go
func (o *InternalElementId) Fmt(f Formatter) Unknown
```

##### AsRef()

**Signature:**

```go
func (o *InternalElementId) AsRef() string
```


---

### IterationValidator

Helper struct for validating iteration counts.

#### Methods

##### CheckIteration()

Validate and increment iteration count.

**Returns:**
* `Ok(())` if count is within limits
* `Err(SecurityError)` if count exceeds limit

**Signature:**

```go
func (o *IterationValidator) CheckIteration() error
```

##### CurrentCount()

Get current iteration count.

**Signature:**

```go
func (o *IterationValidator) CurrentCount() int
```


---

### JatsExtractor

JATS document extractor.

Supports JATS (Journal Article Tag Suite) XML documents in various versions,
handling both the full article structure and minimal JATS subsets.

#### Methods

##### Default()

**Signature:**

```go
func (o *JatsExtractor) Default() JatsExtractor
```

##### Name()

**Signature:**

```go
func (o *JatsExtractor) Name() string
```

##### Version()

**Signature:**

```go
func (o *JatsExtractor) Version() string
```

##### Initialize()

**Signature:**

```go
func (o *JatsExtractor) Initialize() error
```

##### Shutdown()

**Signature:**

```go
func (o *JatsExtractor) Shutdown() error
```

##### ExtractBytes()

**Signature:**

```go
func (o *JatsExtractor) ExtractBytes(content []byte, mimeType string, config ExtractionConfig) (InternalDocument, error)
```

##### SupportedMimeTypes()

**Signature:**

```go
func (o *JatsExtractor) SupportedMimeTypes() []string
```

##### Priority()

**Signature:**

```go
func (o *JatsExtractor) Priority() int32
```


---

### JatsMetadata

JATS (Journal Article Tag Suite) metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Copyright` | `*string` | `nil` | Copyright |
| `License` | `*string` | `nil` | License |
| `HistoryDates` | `map[string]string` | `nil` | History dates |
| `ContributorRoles` | `[]ContributorRole` | `nil` | Contributor roles |


---

### JsonExtractionConfig

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `ExtractSchema` | `bool` | `false` | Extract schema |
| `MaxDepth` | `int` | `20` | Maximum depth |
| `ArrayItemLimit` | `int` | `500` | Array item limit |
| `IncludeTypeInfo` | `bool` | `false` | Include type info |
| `FlattenNestedObjects` | `bool` | `true` | Flatten nested objects |
| `CustomTextFieldPatterns` | `[]string` | `nil` | Custom text field patterns |

#### Methods

##### Default()

**Signature:**

```go
func (o *JsonExtractionConfig) Default() JsonExtractionConfig
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

##### Default()

**Signature:**

```go
func (o *JupyterExtractor) Default() JupyterExtractor
```

##### Name()

**Signature:**

```go
func (o *JupyterExtractor) Name() string
```

##### Version()

**Signature:**

```go
func (o *JupyterExtractor) Version() string
```

##### Initialize()

**Signature:**

```go
func (o *JupyterExtractor) Initialize() error
```

##### Shutdown()

**Signature:**

```go
func (o *JupyterExtractor) Shutdown() error
```

##### Description()

**Signature:**

```go
func (o *JupyterExtractor) Description() string
```

##### Author()

**Signature:**

```go
func (o *JupyterExtractor) Author() string
```

##### ExtractBytes()

**Signature:**

```go
func (o *JupyterExtractor) ExtractBytes(content []byte, mimeType string, config ExtractionConfig) (InternalDocument, error)
```

##### SupportedMimeTypes()

**Signature:**

```go
func (o *JupyterExtractor) SupportedMimeTypes() []string
```

##### Priority()

**Signature:**

```go
func (o *JupyterExtractor) Priority() int32
```


---

### KeynoteExtractor

Apple Keynote presentation extractor.

Supports `.key` files (modern iWork format, 2013+).

Extracts slide text and speaker notes from the IWA container:
ZIP → Snappy → protobuf text fields.

#### Methods

##### Default()

**Signature:**

```go
func (o *KeynoteExtractor) Default() KeynoteExtractor
```

##### Name()

**Signature:**

```go
func (o *KeynoteExtractor) Name() string
```

##### Version()

**Signature:**

```go
func (o *KeynoteExtractor) Version() string
```

##### Initialize()

**Signature:**

```go
func (o *KeynoteExtractor) Initialize() error
```

##### Shutdown()

**Signature:**

```go
func (o *KeynoteExtractor) Shutdown() error
```

##### Description()

**Signature:**

```go
func (o *KeynoteExtractor) Description() string
```

##### Author()

**Signature:**

```go
func (o *KeynoteExtractor) Author() string
```

##### ExtractBytes()

**Signature:**

```go
func (o *KeynoteExtractor) ExtractBytes(content []byte, mimeType string, config ExtractionConfig) (InternalDocument, error)
```

##### SupportedMimeTypes()

**Signature:**

```go
func (o *KeynoteExtractor) SupportedMimeTypes() []string
```

##### Priority()

**Signature:**

```go
func (o *KeynoteExtractor) Priority() int32
```


---

### Keyword

Extracted keyword with metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Text` | `string` | — | The keyword text. |
| `Score` | `float32` | — | Relevance score (higher is better, algorithm-specific range). |
| `Algorithm` | `KeywordAlgorithm` | — | Algorithm that extracted this keyword. |
| `Positions` | `*[]int` | `nil` | Optional positions where keyword appears in text (character offsets). |

#### Methods

##### WithPositions()

Create a new keyword with positions.

**Signature:**

```go
func (o *Keyword) WithPositions(text string, score float32, algorithm KeywordAlgorithm, positions []int) Keyword
```


---

### KeywordConfig

Keyword extraction configuration.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Algorithm` | `KeywordAlgorithm` | `KeywordAlgorithm.Yake` | Algorithm to use for extraction. |
| `MaxKeywords` | `int` | `10` | Maximum number of keywords to extract (default: 10). |
| `MinScore` | `float32` | `0` | Minimum score threshold (0.0-1.0, default: 0.0). Keywords with scores below this threshold are filtered out. Note: Score ranges differ between algorithms. |
| `NgramRange` | `UsizeUsize` | `nil` | N-gram range for keyword extraction (min, max). (1, 1) = unigrams only (1, 2) = unigrams and bigrams (1, 3) = unigrams, bigrams, and trigrams (default) |
| `Language` | `*string` | `nil` | Language code for stopword filtering (e.g., "en", "de", "fr"). If None, no stopword filtering is applied. |
| `YakeParams` | `*YakeParams` | `nil` | YAKE-specific tuning parameters. |
| `RakeParams` | `*RakeParams` | `nil` | RAKE-specific tuning parameters. |

#### Methods

##### Default()

**Signature:**

```go
func (o *KeywordConfig) Default() KeywordConfig
```

##### WithMaxKeywords()

Set maximum number of keywords to extract.

**Signature:**

```go
func (o *KeywordConfig) WithMaxKeywords(max int) KeywordConfig
```

##### WithMinScore()

Set minimum score threshold.

**Signature:**

```go
func (o *KeywordConfig) WithMinScore(score float32) KeywordConfig
```

##### WithNgramRange()

Set n-gram range.

**Signature:**

```go
func (o *KeywordConfig) WithNgramRange(min int, max int) KeywordConfig
```

##### WithLanguage()

Set language for stopword filtering.

**Signature:**

```go
func (o *KeywordConfig) WithLanguage(lang string) KeywordConfig
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

##### Name()

**Signature:**

```go
func (o *KeywordExtractor) Name() string
```

##### Version()

**Signature:**

```go
func (o *KeywordExtractor) Version() string
```

##### Initialize()

**Signature:**

```go
func (o *KeywordExtractor) Initialize() error
```

##### Shutdown()

**Signature:**

```go
func (o *KeywordExtractor) Shutdown() error
```

##### Process()

**Signature:**

```go
func (o *KeywordExtractor) Process(result ExtractionResult, config ExtractionConfig) error
```

##### ProcessingStage()

**Signature:**

```go
func (o *KeywordExtractor) ProcessingStage() ProcessingStage
```

##### ShouldProcess()

**Signature:**

```go
func (o *KeywordExtractor) ShouldProcess(result ExtractionResult, config ExtractionConfig) bool
```

##### EstimatedDurationMs()

**Signature:**

```go
func (o *KeywordExtractor) EstimatedDurationMs(result ExtractionResult) uint64
```


---

### LanguageDetectionConfig

Language detection configuration.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Enabled` | `bool` | — | Enable language detection |
| `MinConfidence` | `float64` | — | Minimum confidence threshold (0.0-1.0) |
| `DetectMultiple` | `bool` | — | Detect multiple languages in the document |


---

### LanguageDetector

Post-processor that detects languages in document content.

This processor:
- Runs in the Early processing stage
- Only processes when `config.language_detection` is configured
- Stores detected languages in `result.detected_languages`
- Uses the whatlang library for detection

#### Methods

##### Name()

**Signature:**

```go
func (o *LanguageDetector) Name() string
```

##### Version()

**Signature:**

```go
func (o *LanguageDetector) Version() string
```

##### Initialize()

**Signature:**

```go
func (o *LanguageDetector) Initialize() error
```

##### Shutdown()

**Signature:**

```go
func (o *LanguageDetector) Shutdown() error
```

##### Process()

**Signature:**

```go
func (o *LanguageDetector) Process(result ExtractionResult, config ExtractionConfig) error
```

##### ProcessingStage()

**Signature:**

```go
func (o *LanguageDetector) ProcessingStage() ProcessingStage
```

##### ShouldProcess()

**Signature:**

```go
func (o *LanguageDetector) ShouldProcess(result ExtractionResult, config ExtractionConfig) bool
```

##### EstimatedDurationMs()

**Signature:**

```go
func (o *LanguageDetector) EstimatedDurationMs(result ExtractionResult) uint64
```


---

### LanguageRegistry

Language support registry for OCR backends.

Maintains a mapping of OCR backend names to their supported language codes.
This is the single source of truth for language support across all bindings.

#### Methods

##### Global()

Get the default global registry instance.

The registry is created on first access and reused for all subsequent calls.

**Returns:**

A reference to the global `LanguageRegistry` instance.

**Signature:**

```go
func (o *LanguageRegistry) Global() LanguageRegistry
```

##### GetSupportedLanguages()

Get supported languages for a specific OCR backend.

**Returns:**

`Some(&[String])` if the backend is registered, `nil` otherwise.

**Signature:**

```go
func (o *LanguageRegistry) GetSupportedLanguages(backend string) *[]string
```

##### IsLanguageSupported()

Check if a language is supported by a specific backend.

**Returns:**

`true` if the language is supported, `false` otherwise.

**Signature:**

```go
func (o *LanguageRegistry) IsLanguageSupported(backend string, language string) bool
```

##### GetBackends()

Get all registered backend names.

**Returns:**

A vector of backend names in the registry.

**Signature:**

```go
func (o *LanguageRegistry) GetBackends() []string
```

##### GetLanguageCount()

Get language count for a specific backend.

**Returns:**

Number of supported languages for the backend, or 0 if backend not found.

**Signature:**

```go
func (o *LanguageRegistry) GetLanguageCount(backend string) int
```

##### Default()

**Signature:**

```go
func (o *LanguageRegistry) Default() LanguageRegistry
```


---

### LatexExtractor

LaTeX document extractor

#### Methods

##### BuildInternalDocument()

Build an `InternalDocument` from LaTeX source.

Captures `\label{}` as anchors, `\ref{}` as CrossReference relationships,
`\cite{}` as CitationReference relationships, and footnotes.

**Signature:**

```go
func (o *LatexExtractor) BuildInternalDocument(source string, injectPlaceholders bool) InternalDocument
```

##### Default()

**Signature:**

```go
func (o *LatexExtractor) Default() LatexExtractor
```

##### Name()

**Signature:**

```go
func (o *LatexExtractor) Name() string
```

##### Version()

**Signature:**

```go
func (o *LatexExtractor) Version() string
```

##### Initialize()

**Signature:**

```go
func (o *LatexExtractor) Initialize() error
```

##### Shutdown()

**Signature:**

```go
func (o *LatexExtractor) Shutdown() error
```

##### Description()

**Signature:**

```go
func (o *LatexExtractor) Description() string
```

##### Author()

**Signature:**

```go
func (o *LatexExtractor) Author() string
```

##### ExtractBytes()

**Signature:**

```go
func (o *LatexExtractor) ExtractBytes(content []byte, mimeType string, config ExtractionConfig) (InternalDocument, error)
```

##### ExtractFile()

**Signature:**

```go
func (o *LatexExtractor) ExtractFile(path string, mimeType string, config ExtractionConfig) (InternalDocument, error)
```

##### SupportedMimeTypes()

**Signature:**

```go
func (o *LatexExtractor) SupportedMimeTypes() []string
```

##### Priority()

**Signature:**

```go
func (o *LatexExtractor) Priority() int32
```


---

### LayoutDetection

A single layout detection result.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Class` | `LayoutClass` | — | Class (layout class) |
| `Confidence` | `float32` | — | Confidence |
| `Bbox` | `BBox` | — | Bbox (b box) |

#### Methods

##### SortByConfidenceDesc()

Sort detections by confidence in descending order.

**Signature:**

```go
func (o *LayoutDetection) SortByConfidenceDesc(detections []LayoutDetection)
```

##### Fmt()

**Signature:**

```go
func (o *LayoutDetection) Fmt(f Formatter) Unknown
```


---

### LayoutDetectionConfig

Layout detection configuration.

Controls layout detection behavior in the extraction pipeline.
When set on `ExtractionConfig`, layout detection
is enabled for PDF extraction.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `ConfidenceThreshold` | `*float32` | `nil` | Confidence threshold override (None = use model default). |
| `ApplyHeuristics` | `bool` | `true` | Whether to apply postprocessing heuristics (default: true). |
| `TableModel` | `TableModel` | `TableModel.Tatr` | Table structure recognition model. Controls which model is used for table cell detection within layout-detected table regions. Defaults to `TableModel.Tatr`. |

#### Methods

##### Default()

**Signature:**

```go
func (o *LayoutDetectionConfig) Default() LayoutDetectionConfig
```


---

### LayoutEngine

High-level layout detection engine.

Wraps model loading, inference, and postprocessing into a single
reusable object. Models are downloaded and cached on first use.

#### Methods

##### FromConfig()

Create a layout engine from a full config.

**Signature:**

```go
func (o *LayoutEngine) FromConfig(config LayoutEngineConfig) (LayoutEngine, error)
```

##### Detect()

Run layout detection on an image.

Returns a `DetectionResult` with bounding boxes, classes, and confidence scores.
If `apply_heuristics` is enabled in config, postprocessing is applied automatically.

**Signature:**

```go
func (o *LayoutEngine) Detect(img RgbImage) (DetectionResult, error)
```

##### DetectTimed()

Run layout detection on an image and return granular timing data.

Identical to `detect` but also returns a `DetectTimings` breakdown.
Use this when you need per-step profiling (preprocess / onnx / postprocess).

**Signature:**

```go
func (o *LayoutEngine) DetectTimed(img RgbImage) (DetectionResultDetectTimings, error)
```

##### DetectBatch()

Run layout detection on a batch of images in a single model call.

Returns one `(DetectionResult, DetectTimings)` tuple per input image.
Postprocessing heuristics are applied per image when enabled in config.

Timing note: `preprocess_ms` and `onnx_ms` in each `DetectTimings` are the
amortized per-image share of the batch operation (total / N), not independent
per-image measurements.

**Signature:**

```go
func (o *LayoutEngine) DetectBatch(images []RgbImage) ([]DetectionResultDetectTimings, error)
```

##### ModelName()

Get the model name.

**Signature:**

```go
func (o *LayoutEngine) ModelName() string
```

##### Config()

Return a reference to the engine's configuration.

Used by callers (e.g. parallel layout runners) that need to create
additional engines with identical settings.

**Signature:**

```go
func (o *LayoutEngine) Config() LayoutEngineConfig
```


---

### LayoutEngineConfig

Full configuration for the layout engine.

Provides fine-grained control over model selection, thresholds, and
postprocessing.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Backend` | `ModelBackend` | `ModelBackend.RtDetr` | Which model backend to use. |
| `ConfidenceThreshold` | `*float32` | `nil` | Confidence threshold override (None = use model default). |
| `ApplyHeuristics` | `bool` | `true` | Whether to apply postprocessing heuristics. |
| `CacheDir` | `*string` | `nil` | Custom cache directory for model files (None = default). |

#### Methods

##### Default()

**Signature:**

```go
func (o *LayoutEngineConfig) Default() LayoutEngineConfig
```


---

### LayoutModel

Common interface for all layout detection model backends.

#### Methods

##### Detect()

Run layout detection on an image using the default confidence threshold.

**Signature:**

```go
func (o *LayoutModel) Detect(img RgbImage) ([]LayoutDetection, error)
```

##### DetectWithThreshold()

Run layout detection with a custom confidence threshold.

**Signature:**

```go
func (o *LayoutModel) DetectWithThreshold(img RgbImage, threshold float32) ([]LayoutDetection, error)
```

##### DetectBatch()

Run layout detection on a batch of images in a single model call.

Returns one `Vec<LayoutDetection>` per input image (same order).
`threshold` overrides the model's default confidence cutoff when `Some`.

The default implementation is a sequential fallback: models that support
true batched inference (e.g. `rtdetr.RtDetrModel`) override this.

**Signature:**

```go
func (o *LayoutModel) DetectBatch(images []RgbImage, threshold float32) ([][]LayoutDetection, error)
```

##### Name()

Human-readable model name.

**Signature:**

```go
func (o *LayoutModel) Name() string
```


---

### LayoutTimingReport

Timing breakdown for the entire layout detection run.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `TotalMs` | `float64` | — | Total ms |
| `PerPage` | `[]PageTiming` | — | Per page |

#### Methods

##### AvgRenderMs()

**Signature:**

```go
func (o *LayoutTimingReport) AvgRenderMs() float64
```

##### AvgInferenceMs()

**Signature:**

```go
func (o *LayoutTimingReport) AvgInferenceMs() float64
```

##### AvgPreprocessMs()

**Signature:**

```go
func (o *LayoutTimingReport) AvgPreprocessMs() float64
```

##### AvgOnnxMs()

**Signature:**

```go
func (o *LayoutTimingReport) AvgOnnxMs() float64
```

##### AvgPostprocessMs()

**Signature:**

```go
func (o *LayoutTimingReport) AvgPostprocessMs() float64
```

##### TotalInferenceMs()

**Signature:**

```go
func (o *LayoutTimingReport) TotalInferenceMs() float64
```

##### TotalRenderMs()

**Signature:**

```go
func (o *LayoutTimingReport) TotalRenderMs() float64
```

##### TotalPreprocessMs()

**Signature:**

```go
func (o *LayoutTimingReport) TotalPreprocessMs() float64
```

##### TotalOnnxMs()

**Signature:**

```go
func (o *LayoutTimingReport) TotalOnnxMs() float64
```

##### TotalPostprocessMs()

**Signature:**

```go
func (o *LayoutTimingReport) TotalPostprocessMs() float64
```


---

### LinkMetadata

Link element metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Href` | `string` | — | The href URL value |
| `Text` | `string` | — | Link text content (normalized) |
| `Title` | `*string` | `nil` | Optional title attribute |
| `LinkType` | `LinkType` | — | Link type classification |
| `Rel` | `[]string` | — | Rel attribute values |
| `Attributes` | `[]StringString` | — | Additional attributes as key-value pairs |


---

### LlmConfig

Configuration for an LLM provider/model via liter-llm.

Each feature (VLM OCR, VLM embeddings, structured extraction) carries
its own `LlmConfig`, allowing different providers per feature.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Model` | `string` | — | Provider/model string using liter-llm routing format. Examples: `"openai/gpt-4o"`, `"anthropic/claude-sonnet-4-20250514"`, `"groq/llama-3.1-70b-versatile"`. |
| `ApiKey` | `*string` | `nil` | API key for the provider. When `None`, liter-llm falls back to the provider's standard environment variable (e.g., `OPENAI_API_KEY`). |
| `BaseUrl` | `*string` | `nil` | Custom base URL override for the provider endpoint. |
| `TimeoutSecs` | `*uint64` | `nil` | Request timeout in seconds (default: 60). |
| `MaxRetries` | `*uint32` | `nil` | Maximum retry attempts (default: 3). |
| `Temperature` | `*float64` | `nil` | Sampling temperature for generation tasks. |
| `MaxTokens` | `*uint64` | `nil` | Maximum tokens to generate. |


---

### LlmUsage

Token usage and cost data for a single LLM call made during extraction.

Populated when VLM OCR, structured extraction, or LLM-based embeddings
are used. Multiple entries may be present when multiple LLM calls occur
within one extraction (e.g. VLM OCR + structured extraction).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Model` | `string` | `nil` | The LLM model identifier (e.g. "openai/gpt-4o", "anthropic/claude-sonnet-4-20250514"). |
| `Source` | `string` | `nil` | The pipeline stage that triggered this LLM call (e.g. "vlm_ocr", "structured_extraction", "embeddings"). |
| `InputTokens` | `*uint64` | `nil` | Number of input/prompt tokens consumed. |
| `OutputTokens` | `*uint64` | `nil` | Number of output/completion tokens generated. |
| `TotalTokens` | `*uint64` | `nil` | Total tokens (input + output). |
| `EstimatedCost` | `*float64` | `nil` | Estimated cost in USD based on the provider's published pricing. |
| `FinishReason` | `*string` | `nil` | Why the model stopped generating (e.g. "stop", "length", "content_filter"). |


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

##### BuildInternalDocument()

Build an `InternalDocument` from pulldown-cmark events and optional YAML frontmatter.

**Signature:**

```go
func (o *MarkdownExtractor) BuildInternalDocument(events []Event, yaml Value) InternalDocument
```

##### Default()

**Signature:**

```go
func (o *MarkdownExtractor) Default() MarkdownExtractor
```

##### Name()

**Signature:**

```go
func (o *MarkdownExtractor) Name() string
```

##### Version()

**Signature:**

```go
func (o *MarkdownExtractor) Version() string
```

##### Initialize()

**Signature:**

```go
func (o *MarkdownExtractor) Initialize() error
```

##### Shutdown()

**Signature:**

```go
func (o *MarkdownExtractor) Shutdown() error
```

##### Description()

**Signature:**

```go
func (o *MarkdownExtractor) Description() string
```

##### Author()

**Signature:**

```go
func (o *MarkdownExtractor) Author() string
```

##### ExtractBytes()

**Signature:**

```go
func (o *MarkdownExtractor) ExtractBytes(content []byte, mimeType string, config ExtractionConfig) (InternalDocument, error)
```

##### ExtractFile()

**Signature:**

```go
func (o *MarkdownExtractor) ExtractFile(path string, mimeType string, config ExtractionConfig) (InternalDocument, error)
```

##### SupportedMimeTypes()

**Signature:**

```go
func (o *MarkdownExtractor) SupportedMimeTypes() []string
```

##### Priority()

**Signature:**

```go
func (o *MarkdownExtractor) Priority() int32
```


---

### MdxExtractor

MDX extractor with JSX stripping and Markdown processing.

Strips MDX-specific syntax (imports, exports, JSX component tags,
inline expressions) and processes the remaining content as Markdown,
extracting metadata from YAML frontmatter and tables.

#### Methods

##### BuildInternalDocument()

Build an `InternalDocument` from pulldown-cmark events after JSX stripping.

JSX blocks that were stripped are recorded as raw blocks in the internal document.

**Signature:**

```go
func (o *MdxExtractor) BuildInternalDocument(events []Event, yaml Value, rawJsxBlocks []string) InternalDocument
```

##### Default()

**Signature:**

```go
func (o *MdxExtractor) Default() MdxExtractor
```

##### Name()

**Signature:**

```go
func (o *MdxExtractor) Name() string
```

##### Version()

**Signature:**

```go
func (o *MdxExtractor) Version() string
```

##### Initialize()

**Signature:**

```go
func (o *MdxExtractor) Initialize() error
```

##### Shutdown()

**Signature:**

```go
func (o *MdxExtractor) Shutdown() error
```

##### Description()

**Signature:**

```go
func (o *MdxExtractor) Description() string
```

##### Author()

**Signature:**

```go
func (o *MdxExtractor) Author() string
```

##### ExtractBytes()

**Signature:**

```go
func (o *MdxExtractor) ExtractBytes(content []byte, mimeType string, config ExtractionConfig) (InternalDocument, error)
```

##### ExtractFile()

**Signature:**

```go
func (o *MdxExtractor) ExtractFile(path string, mimeType string, config ExtractionConfig) (InternalDocument, error)
```

##### SupportedMimeTypes()

**Signature:**

```go
func (o *MdxExtractor) SupportedMimeTypes() []string
```

##### Priority()

**Signature:**

```go
func (o *MdxExtractor) Priority() int32
```


---

### Metadata

Extraction result metadata.

Contains common fields applicable to all formats, format-specific metadata
via a discriminated union, and additional custom fields from postprocessors.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Title` | `*string` | `nil` | Document title |
| `Subject` | `*string` | `nil` | Document subject or description |
| `Authors` | `*[]string` | `nil` | Primary author(s) - always Vec for consistency |
| `Keywords` | `*[]string` | `nil` | Keywords/tags - always Vec for consistency |
| `Language` | `*string` | `nil` | Primary language (ISO 639 code) |
| `CreatedAt` | `*string` | `nil` | Creation timestamp (ISO 8601 format) |
| `ModifiedAt` | `*string` | `nil` | Last modification timestamp (ISO 8601 format) |
| `CreatedBy` | `*string` | `nil` | User who created the document |
| `ModifiedBy` | `*string` | `nil` | User who last modified the document |
| `Pages` | `*PageStructure` | `nil` | Page/slide/sheet structure with boundaries |
| `Format` | `*FormatMetadata` | `FormatMetadata.Pdf` | Format-specific metadata (discriminated union) Contains detailed metadata specific to the document format. Serializes with a `format_type` discriminator field. |
| `ImagePreprocessing` | `*ImagePreprocessingMetadata` | `nil` | Image preprocessing metadata (when OCR preprocessing was applied) |
| `JsonSchema` | `*interface{}` | `nil` | JSON schema (for structured data extraction) |
| `Error` | `*ErrorMetadata` | `nil` | Error metadata (for batch operations) |
| `ExtractionDurationMs` | `*uint64` | `nil` | Extraction duration in milliseconds (for benchmarking). This field is populated by batch extraction to provide per-file timing information. It's `None` for single-file extraction (which uses external timing). |
| `Category` | `*string` | `nil` | Document category (from frontmatter or classification). |
| `Tags` | `*[]string` | `nil` | Document tags (from frontmatter). |
| `DocumentVersion` | `*string` | `nil` | Document version string (from frontmatter). |
| `AbstractText` | `*string` | `nil` | Abstract or summary text (from frontmatter). |
| `OutputFormat` | `*string` | `nil` | Output format identifier (e.g., "markdown", "html", "text"). Set by the output format pipeline stage when format conversion is applied. Previously stored in `metadata.additional["output_format"]`. |
| `Additional` | `AHashMap` | `nil` | Additional custom fields from postprocessors. **Deprecated**: Prefer using typed fields on `ExtractionResult` and `Metadata` instead of inserting into this map. Typed fields provide better cross-language compatibility and type safety. This field will be removed in a future major version. This flattened map allows Python/TypeScript postprocessors to add arbitrary fields (entity extraction, keyword extraction, etc.). Fields are merged at the root level during serialization. Uses `Cow<'static, str>` keys so static string keys avoid allocation. |


---

### MetricsLayer

A `tower.Layer` that records service-level extraction metrics.

#### Methods

##### Layer()

**Signature:**

```go
func (o *MetricsLayer) Layer(inner S) Service
```


---

### ModelCache

#### Methods

##### Put()

Return a model to the cache for reuse.

If the cache already holds a model (e.g. from a concurrent caller),
the returned model is silently dropped.

**Signature:**

```go
func (o *ModelCache) Put(model T)
```

##### Take()

Take the cached model if one exists, without creating a new one.

**Signature:**

```go
func (o *ModelCache) Take() *T
```


---

### NodeId

Deterministic node identifier.

Generated from a hash of `node_type + text + page`. The same document
always produces the same IDs, making them useful for diffing, caching,
and external references.

#### Methods

##### Generate()

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

```go
func (o *NodeId) Generate(nodeType string, text string, page uint32, index uint32) NodeId
```

##### AsRef()

**Signature:**

```go
func (o *NodeId) AsRef() string
```

##### Fmt()

**Signature:**

```go
func (o *NodeId) Fmt(f Formatter) Unknown
```


---

### NormalizeResult

Result of image normalization

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `RgbData` | `[]byte` | — | Processed RGB image data (height * width * 3 bytes) |
| `Dimensions` | `UsizeUsize` | — | Image dimensions (width, height) |
| `Metadata` | `ImagePreprocessingMetadata` | — | Preprocessing metadata |


---

### Note

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Id` | `string` | — | Unique identifier |
| `NoteType` | `NoteType` | — | Note type (note type) |
| `Paragraphs` | `[]Paragraph` | — | Paragraphs |


---

### NumbersExtractor

Apple Numbers spreadsheet extractor.

Supports `.numbers` files (modern iWork format, 2013+).

Extracts cell string values and sheet names from the IWA container:
ZIP → Snappy → protobuf text fields. Output is formatted as plain text
with one text token per line (representing cell values and labels).

#### Methods

##### Default()

**Signature:**

```go
func (o *NumbersExtractor) Default() NumbersExtractor
```

##### Name()

**Signature:**

```go
func (o *NumbersExtractor) Name() string
```

##### Version()

**Signature:**

```go
func (o *NumbersExtractor) Version() string
```

##### Initialize()

**Signature:**

```go
func (o *NumbersExtractor) Initialize() error
```

##### Shutdown()

**Signature:**

```go
func (o *NumbersExtractor) Shutdown() error
```

##### Description()

**Signature:**

```go
func (o *NumbersExtractor) Description() string
```

##### Author()

**Signature:**

```go
func (o *NumbersExtractor) Author() string
```

##### ExtractBytes()

**Signature:**

```go
func (o *NumbersExtractor) ExtractBytes(content []byte, mimeType string, config ExtractionConfig) (InternalDocument, error)
```

##### SupportedMimeTypes()

**Signature:**

```go
func (o *NumbersExtractor) SupportedMimeTypes() []string
```

##### Priority()

**Signature:**

```go
func (o *NumbersExtractor) Priority() int32
```


---

### OcrCache

#### Methods

##### New()

**Signature:**

```go
func (o *OcrCache) New(cacheDir string) (OcrCache, error)
```

##### GetCachedResult()

**Signature:**

```go
func (o *OcrCache) GetCachedResult(imageHash string, backend string, config string) (*OcrExtractionResult, error)
```

##### SetCachedResult()

**Signature:**

```go
func (o *OcrCache) SetCachedResult(imageHash string, backend string, config string, result OcrExtractionResult) error
```

##### Clear()

**Signature:**

```go
func (o *OcrCache) Clear() error
```

##### GetStats()

**Signature:**

```go
func (o *OcrCache) GetStats() (OcrCacheStats, error)
```


---

### OcrCacheStats

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `TotalFiles` | `int` | `nil` | Total files |
| `TotalSizeMb` | `float64` | `nil` | Total size mb |


---

### OcrConfidence

Confidence scores for an OCR element.

Separates detection confidence (how confident that text exists at this location)
from recognition confidence (how confident about the actual text content).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Detection` | `*float64` | `nil` | Detection confidence: how confident the OCR engine is that text exists here. PaddleOCR provides this as `box_score`, Tesseract doesn't have a direct equivalent. Range: 0.0 to 1.0 (or None if not available). |
| `Recognition` | `float64` | — | Recognition confidence: how confident about the text content. Range: 0.0 to 1.0. |

#### Methods

##### FromTesseract()

Create confidence from Tesseract's single confidence value.

Tesseract provides confidence as 0-100, which we normalize to 0.0-1.0.

**Signature:**

```go
func (o *OcrConfidence) FromTesseract(confidence float64) OcrConfidence
```

##### FromPaddle()

Create confidence from PaddleOCR scores.

Both scores should be in 0.0-1.0 range, but PaddleOCR may occasionally return
values slightly above 1.0 due to model calibration. This method clamps both
values to ensure they stay within the valid 0.0-1.0 range.

**Signature:**

```go
func (o *OcrConfidence) FromPaddle(boxScore float32, textScore float32) OcrConfidence
```


---

### OcrConfig

OCR configuration.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Backend` | `string` | `nil` | OCR backend: tesseract, easyocr, paddleocr |
| `Language` | `string` | `nil` | Language code (e.g., "eng", "deu") |
| `TesseractConfig` | `*TesseractConfig` | `nil` | Tesseract-specific configuration (optional) |
| `OutputFormat` | `*OutputFormat` | `OutputFormat.Plain` | Output format for OCR results (optional, for format conversion) |
| `PaddleOcrConfig` | `*interface{}` | `nil` | PaddleOCR-specific configuration (optional, JSON passthrough) |
| `ElementConfig` | `*OcrElementConfig` | `nil` | OCR element extraction configuration |
| `QualityThresholds` | `*OcrQualityThresholds` | `nil` | Quality thresholds for the native-text-to-OCR fallback decision. When None, uses compiled defaults (matching previous hardcoded behavior). |
| `Pipeline` | `*OcrPipelineConfig` | `nil` | Multi-backend OCR pipeline configuration. When set, enables weighted fallback across multiple OCR backends based on output quality. When None, uses the single `backend` field (same as today). |
| `AutoRotate` | `bool` | `false` | Enable automatic page rotation based on orientation detection. When enabled, uses Tesseract's `DetectOrientationScript()` to detect page orientation (0/90/180/270 degrees) before OCR. If the page is rotated with high confidence, the image is corrected before recognition. This is critical for handling rotated scanned documents. |
| `VlmConfig` | `*LlmConfig` | `nil` | VLM (Vision Language Model) OCR configuration. Required when `backend` is `"vlm"`. Uses liter-llm to send page images to a vision model for text extraction. |
| `VlmPrompt` | `*string` | `nil` | Custom Jinja2 prompt template for VLM OCR. When `None`, uses the default template. Available variables: - `{{ language }}` — The document language code (e.g., "eng", "deu"). |

#### Methods

##### Default()

**Signature:**

```go
func (o *OcrConfig) Default() OcrConfig
```

##### Validate()

Validates that the configured backend is supported.

This method checks that the backend name is one of the supported OCR backends:
- tesseract
- easyocr
- paddleocr

Typos in backend names are caught at configuration validation time, not at runtime.
Also validates pipeline stage backends when a pipeline is configured.

**Signature:**

```go
func (o *OcrConfig) Validate() error
```

##### EffectiveThresholds()

Returns the effective quality thresholds, using configured values or defaults.

**Signature:**

```go
func (o *OcrConfig) EffectiveThresholds() OcrQualityThresholds
```

##### EffectivePipeline()

Returns the effective pipeline config.

- If `pipeline` is explicitly set, returns it.
- If `paddle-ocr` feature is compiled in and no explicit pipeline is set,
  auto-constructs a default pipeline: primary backend (priority 100) + paddleocr (priority 50).
- Otherwise returns `nil` (single-backend mode, same as today).

**Signature:**

```go
func (o *OcrConfig) EffectivePipeline() *OcrPipelineConfig
```


---

### OcrElement

A unified OCR element representing detected text with full metadata.

This is the primary type for structured OCR output, preserving all information
from both Tesseract and PaddleOCR backends.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Text` | `string` | — | The recognized text content. |
| `Geometry` | `OcrBoundingGeometry` | — | Bounding geometry (rectangle or quadrilateral). |
| `Confidence` | `OcrConfidence` | — | Confidence scores for detection and recognition. |
| `Level` | `OcrElementLevel` | — | Hierarchical level (word, line, block, page). |
| `Rotation` | `*OcrRotation` | `nil` | Rotation information (if detected). |
| `PageNumber` | `int` | — | Page number (1-indexed). |
| `ParentId` | `*string` | `nil` | Parent element ID for hierarchical relationships. Only used for Tesseract output which has word -> line -> block hierarchy. |
| `BackendMetadata` | `map[string]interface{}` | — | Backend-specific metadata that doesn't fit the unified schema. |

#### Methods

##### WithLevel()

Set the hierarchical level.

**Signature:**

```go
func (o *OcrElement) WithLevel(level OcrElementLevel) OcrElement
```

##### WithRotation()

Set rotation information.

**Signature:**

```go
func (o *OcrElement) WithRotation(rotation OcrRotation) OcrElement
```

##### WithPageNumber()

Set page number.

**Signature:**

```go
func (o *OcrElement) WithPageNumber(pageNumber int) OcrElement
```

##### WithParentId()

Set parent element ID.

**Signature:**

```go
func (o *OcrElement) WithParentId(parentId string) OcrElement
```

##### WithMetadata()

Add backend-specific metadata.

**Signature:**

```go
func (o *OcrElement) WithMetadata(key string, value interface{}) OcrElement
```

##### WithRotationOpt()

**Signature:**

```go
func (o *OcrElement) WithRotationOpt(rotation OcrRotation) OcrElement
```


---

### OcrElementConfig

Configuration for OCR element extraction.

Controls how OCR elements are extracted and filtered.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `IncludeElements` | `bool` | `nil` | Whether to include OCR elements in the extraction result. When true, the `ocr_elements` field in `ExtractionResult` will be populated. |
| `MinLevel` | `OcrElementLevel` | `OcrElementLevel.Line` | Minimum hierarchical level to include. Elements below this level (e.g., words when min_level is Line) will be excluded. |
| `MinConfidence` | `float64` | `nil` | Minimum recognition confidence threshold (0.0-1.0). Elements with confidence below this threshold will be filtered out. |
| `BuildHierarchy` | `bool` | `nil` | Whether to build hierarchical relationships between elements. When true, `parent_id` fields will be populated based on spatial containment. Only meaningful for Tesseract output. |


---

### OcrExtractionResult

OCR extraction result.

Result of performing OCR on an image or scanned document,
including recognized text and detected tables.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Content` | `string` | — | Recognized text content |
| `MimeType` | `string` | — | Original MIME type of the processed image |
| `Metadata` | `map[string]interface{}` | — | OCR processing metadata (confidence scores, language, etc.) |
| `Tables` | `[]OcrTable` | — | Tables detected and extracted via OCR |
| `OcrElements` | `*[]OcrElement` | `nil` | Structured OCR elements with bounding boxes and confidence scores. Available when TSV output is requested or table detection is enabled. |
| `InternalDocument` | `*InternalDocument` | `nil` | Structured document produced from hOCR parsing. Carries paragraph structure, bounding boxes, and confidence scores that the flattened `content` string discards. |


---

### OcrMetadata

OCR processing metadata.

Captures information about OCR processing configuration and results.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Language` | `string` | — | OCR language code(s) used |
| `Psm` | `int32` | — | Tesseract Page Segmentation Mode (PSM) |
| `OutputFormat` | `string` | — | Output format (e.g., "text", "hocr") |
| `TableCount` | `int` | — | Number of tables detected |
| `TableRows` | `*int` | `nil` | Table rows |
| `TableCols` | `*int` | `nil` | Table cols |


---

### OcrPipelineConfig

Multi-backend OCR pipeline with quality-based fallback.

Backends are tried in priority order (highest first). After each backend
produces output, quality is evaluated. If it meets `quality_thresholds.pipeline_min_quality`,
the result is accepted. Otherwise the next backend is tried.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Stages` | `[]OcrPipelineStage` | — | Ordered list of backends to try. Sorted by priority (descending) at runtime. |
| `QualityThresholds` | `OcrQualityThresholds` | — | Quality thresholds for deciding whether to accept a result or try the next backend. |


---

### OcrPipelineStage

A single backend stage in the OCR pipeline.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Backend` | `string` | — | Backend name: "tesseract", "paddleocr", "easyocr", or a custom registered name. |
| `Priority` | `uint32` | — | Priority weight (higher = tried first). Stages are sorted by priority descending. |
| `Language` | `*string` | `nil` | Language override for this stage (None = use parent OcrConfig.language). |
| `TesseractConfig` | `*TesseractConfig` | `nil` | Tesseract-specific config override for this stage. |
| `PaddleOcrConfig` | `*interface{}` | `nil` | PaddleOCR-specific config for this stage. |
| `VlmConfig` | `*LlmConfig` | `nil` | VLM config override for this pipeline stage. |


---

### OcrProcessor

#### Methods

##### New()

**Signature:**

```go
func (o *OcrProcessor) New(cacheDir string) (OcrProcessor, error)
```

##### ProcessImage()

**Signature:**

```go
func (o *OcrProcessor) ProcessImage(imageBytes []byte, config TesseractConfig) (OcrExtractionResult, error)
```

##### ProcessImageWithFormat()

Process an image with OCR and respect the output format from ExtractionConfig.

This variant allows specifying an output format (Plain, Markdown, Djot) which
affects how the OCR result's mime_type is set when markdown output is requested.

**Signature:**

```go
func (o *OcrProcessor) ProcessImageWithFormat(imageBytes []byte, config TesseractConfig, outputFormat OutputFormat) (OcrExtractionResult, error)
```

##### ClearCache()

**Signature:**

```go
func (o *OcrProcessor) ClearCache() error
```

##### GetCacheStats()

**Signature:**

```go
func (o *OcrProcessor) GetCacheStats() (OcrCacheStats, error)
```

##### ProcessImageFile()

**Signature:**

```go
func (o *OcrProcessor) ProcessImageFile(filePath string, config TesseractConfig) (OcrExtractionResult, error)
```

##### ProcessImageFileWithFormat()

Process a file with OCR and respect the output format from ExtractionConfig.

This variant allows specifying an output format (Plain, Markdown, Djot) which
affects how the OCR result's mime_type is set when markdown output is requested.

**Signature:**

```go
func (o *OcrProcessor) ProcessImageFileWithFormat(filePath string, config TesseractConfig, outputFormat OutputFormat) (OcrExtractionResult, error)
```

##### ProcessImageFilesBatch()

Process multiple image files in parallel using Rayon.

This method processes OCR operations in parallel across CPU cores for improved throughput.
Results are returned in the same order as the input file paths.

**Signature:**

```go
func (o *OcrProcessor) ProcessImageFilesBatch(filePaths []string, config TesseractConfig) []BatchItemResult
```


---

### OcrQualityThresholds

Quality thresholds for OCR fallback decisions and pipeline quality gating.

All fields default to the values that match the previous hardcoded behavior,
so `OcrQualityThresholds.default()` preserves existing semantics exactly.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `MinTotalNonWhitespace` | `int` | `64` | Minimum total non-whitespace characters to consider text substantive. |
| `MinNonWhitespacePerPage` | `float64` | `32` | Minimum non-whitespace characters per page on average. |
| `MinMeaningfulWordLen` | `int` | `4` | Minimum character count for a word to be "meaningful". |
| `MinMeaningfulWords` | `int` | `3` | Minimum count of meaningful words before text is accepted. |
| `MinAlnumRatio` | `float64` | `0.3` | Minimum alphanumeric ratio (non-whitespace chars that are alphanumeric). |
| `MinGarbageChars` | `int` | `5` | Minimum Unicode replacement characters (U+FFFD) to trigger OCR fallback. |
| `MaxFragmentedWordRatio` | `float64` | `0.6` | Maximum fraction of short (1-2 char) words before text is considered fragmented. |
| `CriticalFragmentedWordRatio` | `float64` | `0.8` | Critical fragmentation threshold — triggers OCR regardless of meaningful words. Normal English text has ~20-30% short words. 80%+ is definitive garbage. |
| `MinAvgWordLength` | `float64` | `2` | Minimum average word length. Below this with enough words indicates garbled extraction. |
| `MinWordsForAvgLengthCheck` | `int` | `50` | Minimum word count before average word length check applies. |
| `MinConsecutiveRepeatRatio` | `float64` | `0.08` | Minimum consecutive word repetition ratio to detect column scrambling. |
| `MinWordsForRepeatCheck` | `int` | `50` | Minimum word count before consecutive repetition check is applied. |
| `SubstantiveMinChars` | `int` | `100` | Minimum character count for "substantive markdown" OCR skip gate. |
| `NonTextMinChars` | `int` | `20` | Minimum character count for "non-text content" OCR skip gate. |
| `AlnumWsRatioThreshold` | `float64` | `0.4` | Alphanumeric+whitespace ratio threshold for skip decisions. |
| `PipelineMinQuality` | `float64` | `0.5` | Minimum quality score (0.0-1.0) for a pipeline stage result to be accepted. If the result from a backend scores below this, try the next backend. |

#### Methods

##### Default()

**Signature:**

```go
func (o *OcrQualityThresholds) Default() OcrQualityThresholds
```


---

### OcrRotation

Rotation information for an OCR element.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `AngleDegrees` | `float64` | — | Rotation angle in degrees (0, 90, 180, 270 for PaddleOCR). |
| `Confidence` | `*float64` | `nil` | Confidence score for the rotation detection. |

#### Methods

##### FromPaddle()

Create rotation from PaddleOCR angle classification.

PaddleOCR uses angle_index (0-3) representing 0, 90, 180, 270 degrees.

**Errors:**

Returns an error if `angle_index` is not in the valid range (0-3).

**Signature:**

```go
func (o *OcrRotation) FromPaddle(angleIndex int32, angleScore float32) (OcrRotation, error)
```


---

### OcrTable

Table detected via OCR.

Represents a table structure recognized during OCR processing.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Cells` | `[][]string` | — | Table cells as a 2D vector (rows × columns) |
| `Markdown` | `string` | — | Markdown representation of the table |
| `PageNumber` | `int` | — | Page number where the table was found (1-indexed) |
| `BoundingBox` | `*OcrTableBoundingBox` | `nil` | Bounding box of the table in pixel coordinates (from OCR word positions). |


---

### OcrTableBoundingBox

Bounding box for an OCR-detected table in pixel coordinates.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Left` | `uint32` | — | Left x-coordinate (pixels) |
| `Top` | `uint32` | — | Top y-coordinate (pixels) |
| `Right` | `uint32` | — | Right x-coordinate (pixels) |
| `Bottom` | `uint32` | — | Bottom y-coordinate (pixels) |


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

##### Default()

**Signature:**

```go
func (o *OdtExtractor) Default() OdtExtractor
```

##### Name()

**Signature:**

```go
func (o *OdtExtractor) Name() string
```

##### Version()

**Signature:**

```go
func (o *OdtExtractor) Version() string
```

##### Initialize()

**Signature:**

```go
func (o *OdtExtractor) Initialize() error
```

##### Shutdown()

**Signature:**

```go
func (o *OdtExtractor) Shutdown() error
```

##### Description()

**Signature:**

```go
func (o *OdtExtractor) Description() string
```

##### Author()

**Signature:**

```go
func (o *OdtExtractor) Author() string
```

##### ExtractBytes()

**Signature:**

```go
func (o *OdtExtractor) ExtractBytes(content []byte, mimeType string, config ExtractionConfig) (InternalDocument, error)
```

##### SupportedMimeTypes()

**Signature:**

```go
func (o *OdtExtractor) SupportedMimeTypes() []string
```

##### Priority()

**Signature:**

```go
func (o *OdtExtractor) Priority() int32
```


---

### OdtProperties

OpenDocument metadata from meta.xml

Contains metadata fields defined by the OASIS OpenDocument Format standard.
Uses Dublin Core elements (dc:) and OpenDocument meta elements (meta:).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Title` | `*string` | `nil` | Document title (dc:title) |
| `Subject` | `*string` | `nil` | Document subject/topic (dc:subject) |
| `Creator` | `*string` | `nil` | Current document creator/author (dc:creator) |
| `InitialCreator` | `*string` | `nil` | Initial creator of the document (meta:initial-creator) |
| `Keywords` | `*string` | `nil` | Keywords or tags (meta:keyword) |
| `Description` | `*string` | `nil` | Document description (dc:description) |
| `Date` | `*string` | `nil` | Current modification date (dc:date) |
| `CreationDate` | `*string` | `nil` | Initial creation date (meta:creation-date) |
| `Language` | `*string` | `nil` | Document language (dc:language) |
| `Generator` | `*string` | `nil` | Generator/application that created the document (meta:generator) |
| `EditingDuration` | `*string` | `nil` | Editing duration in ISO 8601 format (meta:editing-duration) |
| `EditingCycles` | `*string` | `nil` | Number of edits/revisions (meta:editing-cycles) |
| `PageCount` | `*int32` | `nil` | Document statistics - page count (meta:page-count) |
| `WordCount` | `*int32` | `nil` | Document statistics - word count (meta:word-count) |
| `CharacterCount` | `*int32` | `nil` | Document statistics - character count (meta:character-count) |
| `ParagraphCount` | `*int32` | `nil` | Document statistics - paragraph count (meta:paragraph-count) |
| `TableCount` | `*int32` | `nil` | Document statistics - table count (meta:table-count) |
| `ImageCount` | `*int32` | `nil` | Document statistics - image count (meta:image-count) |


---

### OrgModeExtractor

Org Mode document extractor.

Provides native Rust-based Org Mode extraction using the `org` library,
extracting structured content and metadata.

#### Methods

##### BuildInternalDocument()

Build an `InternalDocument` from Org Mode source text.

Handles headings, paragraphs, lists, code blocks, tables, inline links,
and footnote references.

**Signature:**

```go
func (o *OrgModeExtractor) BuildInternalDocument(orgText string) InternalDocument
```

##### Default()

**Signature:**

```go
func (o *OrgModeExtractor) Default() OrgModeExtractor
```

##### Name()

**Signature:**

```go
func (o *OrgModeExtractor) Name() string
```

##### Version()

**Signature:**

```go
func (o *OrgModeExtractor) Version() string
```

##### Initialize()

**Signature:**

```go
func (o *OrgModeExtractor) Initialize() error
```

##### Shutdown()

**Signature:**

```go
func (o *OrgModeExtractor) Shutdown() error
```

##### Description()

**Signature:**

```go
func (o *OrgModeExtractor) Description() string
```

##### Author()

**Signature:**

```go
func (o *OrgModeExtractor) Author() string
```

##### ExtractBytes()

**Signature:**

```go
func (o *OrgModeExtractor) ExtractBytes(content []byte, mimeType string, config ExtractionConfig) (InternalDocument, error)
```

##### ExtractFile()

**Signature:**

```go
func (o *OrgModeExtractor) ExtractFile(path string, mimeType string, config ExtractionConfig) (InternalDocument, error)
```

##### SupportedMimeTypes()

**Signature:**

```go
func (o *OrgModeExtractor) SupportedMimeTypes() []string
```

##### Priority()

**Signature:**

```go
func (o *OrgModeExtractor) Priority() int32
```


---

### OrientationResult

Document orientation detection result.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Degrees` | `uint32` | — | Detected orientation in degrees (0, 90, 180, or 270). |
| `Confidence` | `float32` | — | Confidence score (0.0-1.0). |


---

### PageBoundary

Byte offset boundary for a page.

Tracks where a specific page's content starts and ends in the main content string,
enabling mapping from byte positions to page numbers. Offsets are guaranteed to be
at valid UTF-8 character boundaries when using standard String methods (push_str, push, etc.).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `ByteStart` | `int` | — | Byte offset where this page starts in the content string (UTF-8 valid boundary, inclusive) |
| `ByteEnd` | `int` | — | Byte offset where this page ends in the content string (UTF-8 valid boundary, exclusive) |
| `PageNumber` | `int` | — | Page number (1-indexed) |


---

### PageConfig

Page extraction and tracking configuration.

Controls how pages are extracted, tracked, and represented in the extraction results.
When `nil`, page tracking is disabled.

Page range tracking in chunk metadata (first_page/last_page) is automatically enabled
when page boundaries are available and chunking is configured.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `ExtractPages` | `bool` | `false` | Extract pages as separate array (ExtractionResult.pages) |
| `InsertPageMarkers` | `bool` | `false` | Insert page markers in main content string |
| `MarkerFormat` | `string` | `"

<!-- PAGE {page_num} -->

"` | Page marker format (use {page_num} placeholder) Default: "\n\n<!-- PAGE {page_num} -->\n\n" |

#### Methods

##### Default()

**Signature:**

```go
func (o *PageConfig) Default() PageConfig
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
| `PageNumber` | `int` | — | Page number (1-indexed) |
| `Content` | `string` | — | Text content for this page |
| `Tables` | `[]Table` | — | Tables found on this page (uses Arc for memory efficiency) Serializes as Vec<Table> for JSON compatibility while maintaining Arc semantics in-memory for zero-copy sharing. |
| `Images` | `[]ExtractedImage` | — | Images found on this page (uses Arc for memory efficiency) Serializes as Vec<ExtractedImage> for JSON compatibility while maintaining Arc semantics in-memory for zero-copy sharing. |
| `Hierarchy` | `*PageHierarchy` | `nil` | Hierarchy information for the page (when hierarchy extraction is enabled) Contains text hierarchy levels (H1-H6) extracted from the page content. |
| `IsBlank` | `*bool` | `nil` | Whether this page is blank (no meaningful text content) Determined during extraction based on text content analysis. A page is blank if it has fewer than 3 non-whitespace characters and contains no tables or images. |


---

### PageHierarchy

Page hierarchy structure containing heading levels and block information.

Used when PDF text hierarchy extraction is enabled. Contains hierarchical
blocks with heading levels (H1-H6) for semantic document structure.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `BlockCount` | `int` | — | Number of hierarchy blocks on this page |
| `Blocks` | `[]HierarchicalBlock` | — | Hierarchical blocks with heading levels |


---

### PageInfo

Metadata for individual page/slide/sheet.

Captures per-page information including dimensions, content counts,
and visibility state (for presentations).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Number` | `int` | — | Page number (1-indexed) |
| `Title` | `*string` | `nil` | Page title (usually for presentations) |
| `Dimensions` | `*F64F64` | `nil` | Dimensions in points (PDF) or pixels (images): (width, height) |
| `ImageCount` | `*int` | `nil` | Number of images on this page |
| `TableCount` | `*int` | `nil` | Number of tables on this page |
| `Hidden` | `*bool` | `nil` | Whether this page is hidden (e.g., in presentations) |
| `IsBlank` | `*bool` | `nil` | Whether this page is blank (no meaningful text, no images, no tables) A page is considered blank if it has fewer than 3 non-whitespace characters and contains no tables or images. This is useful for filtering out empty pages in scanned documents or PDFs with blank separator pages. |


---

### PageLayoutRegion

A detected layout region mapped to PDF coordinate space.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Class` | `LayoutClass` | — | Class (layout class) |
| `Confidence` | `float32` | — | Confidence |
| `Bbox` | `PdfLayoutBBox` | — | Bbox (pdf layout b box) |


---

### PageLayoutResult

Layout detection results for a single page.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `PageIndex` | `int` | — | Page index |
| `Regions` | `[]PageLayoutRegion` | — | Regions |
| `PageWidthPts` | `float32` | — | Page width pts |
| `PageHeightPts` | `float32` | — | Page height pts |
| `RenderWidthPx` | `uint32` | — | Width of the rendered image used for layout detection (pixels). |
| `RenderHeightPx` | `uint32` | — | Height of the rendered image used for layout detection (pixels). |


---

### PageMargins

Page margins in twips (twentieths of a point).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Top` | `*int32` | `nil` | Top margin in twips. |
| `Right` | `*int32` | `nil` | Right margin in twips. |
| `Bottom` | `*int32` | `nil` | Bottom margin in twips. |
| `Left` | `*int32` | `nil` | Left margin in twips. |
| `Header` | `*int32` | `nil` | Header offset in twips. |
| `Footer` | `*int32` | `nil` | Footer offset in twips. |
| `Gutter` | `*int32` | `nil` | Gutter margin in twips. |

#### Methods

##### ToPoints()

Convert all margins from twips to points.

Conversion factor: 1 twip = 1/20 point, or equivalently divide by 20.

**Signature:**

```go
func (o *PageMargins) ToPoints() PageMarginsPoints
```


---

### PageMarginsPoints

Page margins converted to points (1/72 inch).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Top` | `*float64` | `nil` | Top |
| `Right` | `*float64` | `nil` | Right |
| `Bottom` | `*float64` | `nil` | Bottom |
| `Left` | `*float64` | `nil` | Left |
| `Header` | `*float64` | `nil` | Header |
| `Footer` | `*float64` | `nil` | Footer |
| `Gutter` | `*float64` | `nil` | Gutter |


---

### PageRenderOptions

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `TargetDpi` | `int32` | `300` | Target dpi |
| `MaxImageDimension` | `int32` | `65536` | Maximum image dimension |
| `AutoAdjustDpi` | `bool` | `true` | Auto adjust dpi |
| `MinDpi` | `int32` | `72` | Minimum dpi |
| `MaxDpi` | `int32` | `600` | Maximum dpi |

#### Methods

##### Default()

**Signature:**

```go
func (o *PageRenderOptions) Default() PageRenderOptions
```


---

### PageStructure

Unified page structure for documents.

Supports different page types (PDF pages, PPTX slides, Excel sheets)
with character offset boundaries for chunk-to-page mapping.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `TotalCount` | `int` | — | Total number of pages/slides/sheets |
| `UnitType` | `PageUnitType` | — | Type of paginated unit |
| `Boundaries` | `*[]PageBoundary` | `nil` | Character offset boundaries for each page Maps character ranges in the extracted content to page numbers. Used for chunk page range calculation. |
| `Pages` | `*[]PageInfo` | `nil` | Detailed per-page metadata (optional, only when needed) |


---

### PageTiming

Timing breakdown for a single page.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `RenderMs` | `float64` | — | Time to render the PDF page to a raster image (amortized from batch render). |
| `PreprocessMs` | `float64` | — | Time spent in image preprocessing (resize, normalize, tensor construction). |
| `OnnxMs` | `float64` | — | Time for the ONNX model session.run() call (actual neural network inference). |
| `InferenceMs` | `float64` | — | Total model inference time (preprocess + onnx), as measured by the engine. |
| `PostprocessMs` | `float64` | — | Time spent in postprocessing (confidence filtering, overlap resolution). |
| `MappingMs` | `float64` | — | Time to map pixel-space bounding boxes to PDF coordinate space. |


---

### PagesExtractor

Apple Pages document extractor.

Supports `.pages` files (modern iWork format, 2013+).

Extracts all text content from the document by parsing the IWA
(iWork Archive) container: ZIP → Snappy → protobuf text fields.

#### Methods

##### Default()

**Signature:**

```go
func (o *PagesExtractor) Default() PagesExtractor
```

##### Name()

**Signature:**

```go
func (o *PagesExtractor) Name() string
```

##### Version()

**Signature:**

```go
func (o *PagesExtractor) Version() string
```

##### Initialize()

**Signature:**

```go
func (o *PagesExtractor) Initialize() error
```

##### Shutdown()

**Signature:**

```go
func (o *PagesExtractor) Shutdown() error
```

##### Description()

**Signature:**

```go
func (o *PagesExtractor) Description() string
```

##### Author()

**Signature:**

```go
func (o *PagesExtractor) Author() string
```

##### ExtractBytes()

**Signature:**

```go
func (o *PagesExtractor) ExtractBytes(content []byte, mimeType string, config ExtractionConfig) (InternalDocument, error)
```

##### SupportedMimeTypes()

**Signature:**

```go
func (o *PagesExtractor) SupportedMimeTypes() []string
```

##### Priority()

**Signature:**

```go
func (o *PagesExtractor) Priority() int32
```


---

### PanicContext

Context information captured when a panic occurs.

This struct stores detailed information about where and when a panic happened,
enabling better error reporting across FFI boundaries.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `File` | `string` | — | Source file where the panic occurred |
| `Line` | `uint32` | — | Line number where the panic occurred |
| `Function` | `string` | — | Function name where the panic occurred |
| `Message` | `string` | — | Panic message extracted from the panic payload |
| `Timestamp` | `SystemTime` | — | Timestamp when the panic was captured |

#### Methods

##### Format()

Formats the panic context as a human-readable string.

**Signature:**

```go
func (o *PanicContext) Format() string
```


---

### ParaText

Plain text content decoded from a ParaText record (tag 0x43).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Content` | `string` | — | The extracted text content |

#### Methods

##### FromRecord()

Decode a ParaText record from raw bytes.

The data field of a TAG_PARA_TEXT record is a sequence of UTF-16LE code
units.  Control characters < 0x0020 are mapped to whitespace or skipped;
characters in the private-use range 0xF020–0xF07F (HWP internal controls)
are discarded.

**Signature:**

```go
func (o *ParaText) FromRecord(record Record) (ParaText, error)
```


---

### Paragraph

A single paragraph; may or may not carry a text payload.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Text` | `*ParaText` | `nil` | Text (para text) |


---

### ParagraphProperties

Paragraph-level formatting properties (alignment, spacing, indentation, etc.).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Alignment` | `*string` | `nil` | `"left"`, `"center"`, `"right"`, `"both"` (justified). |
| `SpacingBefore` | `*int32` | `nil` | Spacing before paragraph in twips. |
| `SpacingAfter` | `*int32` | `nil` | Spacing after paragraph in twips. |
| `SpacingLine` | `*int32` | `nil` | Line spacing in twips or 240ths of a line. |
| `SpacingLineRule` | `*string` | `nil` | Line spacing rule: "auto", "exact", or "atLeast". |
| `IndentLeft` | `*int32` | `nil` | Left indentation in twips. |
| `IndentRight` | `*int32` | `nil` | Right indentation in twips. |
| `IndentFirstLine` | `*int32` | `nil` | First-line indentation in twips. |
| `IndentHanging` | `*int32` | `nil` | Hanging indentation in twips. |
| `OutlineLevel` | `*uint8` | `nil` | Outline level 0-8 for heading levels. |
| `KeepNext` | `*bool` | `nil` | Keep with next paragraph on same page. |
| `KeepLines` | `*bool` | `nil` | Keep all lines of paragraph on same page. |
| `PageBreakBefore` | `*bool` | `nil` | Force page break before paragraph. |
| `WidowControl` | `*bool` | `nil` | Prevent widow/orphan lines. |
| `SuppressAutoHyphens` | `*bool` | `nil` | Suppress automatic hyphenation. |
| `Bidi` | `*bool` | `nil` | Right-to-left paragraph direction. |
| `ShadingFill` | `*string` | `nil` | Background color hex value (from w:shd w:fill). |
| `ShadingVal` | `*string` | `nil` | Shading pattern value (from w:shd w:val). |
| `BorderTop` | `*string` | `nil` | Top border style (from w:pBdr/w:top w:val). |
| `BorderBottom` | `*string` | `nil` | Bottom border style (from w:pBdr/w:bottom w:val). |
| `BorderLeft` | `*string` | `nil` | Left border style (from w:pBdr/w:left w:val). |
| `BorderRight` | `*string` | `nil` | Right border style (from w:pBdr/w:right w:val). |


---

### PdfAnnotation

A PDF annotation extracted from a document page.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `AnnotationType` | `PdfAnnotationType` | — | The type of annotation. |
| `Content` | `*string` | `nil` | Text content of the annotation (e.g., comment text, link URL). |
| `PageNumber` | `int` | — | Page number where the annotation appears (1-indexed). |
| `BoundingBox` | `*BoundingBox` | `nil` | Bounding box of the annotation on the page. |


---

### PdfConfig

PDF-specific configuration.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Backend` | `PdfBackend` | `PdfBackend.Pdfium` | PDF extraction backend. Default: `Pdfium`. |
| `ExtractImages` | `bool` | `false` | Extract images from PDF |
| `Passwords` | `*[]string` | `nil` | List of passwords to try when opening encrypted PDFs |
| `ExtractMetadata` | `bool` | `true` | Extract PDF metadata |
| `Hierarchy` | `*HierarchyConfig` | `nil` | Hierarchy extraction configuration (None = hierarchy extraction disabled) |
| `ExtractAnnotations` | `bool` | `false` | Extract PDF annotations (text notes, highlights, links, stamps). Default: false |
| `TopMarginFraction` | `*float32` | `nil` | Top margin fraction (0.0–1.0) of page height to exclude headers/running heads. Default: 0.06 (6%) |
| `BottomMarginFraction` | `*float32` | `nil` | Bottom margin fraction (0.0–1.0) of page height to exclude footers/page numbers. Default: 0.05 (5%) |
| `AllowSingleColumnTables` | `bool` | `false` | Allow single-column pseudo tables in extraction results. By default, tables with fewer than 2 columns (layout-guided) or 3 columns (heuristic) are rejected. When `True`, the minimum column count is relaxed to 1, allowing single-column structured data (glossaries, itemized lists) to be emitted as tables. Other quality filters (density, sparsity, prose detection) still apply. |

#### Methods

##### Default()

**Signature:**

```go
func (o *PdfConfig) Default() PdfConfig
```


---

### PdfExtractionMetadata

Complete PDF extraction metadata including common and PDF-specific fields.

This struct combines common document fields (title, authors, dates) with
PDF-specific metadata and optional page structure information. It is returned
by `extract_metadata_from_document()` when page boundaries are provided.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Title` | `*string` | `nil` | Document title |
| `Subject` | `*string` | `nil` | Document subject or description |
| `Authors` | `*[]string` | `nil` | Document authors (parsed from PDF Author field) |
| `Keywords` | `*[]string` | `nil` | Document keywords (parsed from PDF Keywords field) |
| `CreatedAt` | `*string` | `nil` | Creation timestamp (ISO 8601 format) |
| `ModifiedAt` | `*string` | `nil` | Last modification timestamp (ISO 8601 format) |
| `CreatedBy` | `*string` | `nil` | Application or user that created the document |
| `PdfSpecific` | `PdfMetadata` | — | PDF-specific metadata |
| `PageStructure` | `*PageStructure` | `nil` | Page structure with boundaries and optional per-page metadata |


---

### PdfExtractor

PDF document extractor using pypdfium2 and playa-pdf.

#### Methods

##### Default()

**Signature:**

```go
func (o *PdfExtractor) Default() PdfExtractor
```

##### Name()

**Signature:**

```go
func (o *PdfExtractor) Name() string
```

##### Version()

**Signature:**

```go
func (o *PdfExtractor) Version() string
```

##### Initialize()

**Signature:**

```go
func (o *PdfExtractor) Initialize() error
```

##### Shutdown()

**Signature:**

```go
func (o *PdfExtractor) Shutdown() error
```

##### ExtractBytes()

**Signature:**

```go
func (o *PdfExtractor) ExtractBytes(content []byte, mimeType string, config ExtractionConfig) (InternalDocument, error)
```

##### SupportedMimeTypes()

**Signature:**

```go
func (o *PdfExtractor) SupportedMimeTypes() []string
```


---

### PdfImage

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `PageNumber` | `int` | — | Page number |
| `ImageIndex` | `int` | — | Image index |
| `Width` | `int64` | — | Width |
| `Height` | `int64` | — | Height |
| `ColorSpace` | `*string` | `nil` | Color space |
| `BitsPerComponent` | `*int64` | `nil` | Bits per component |
| `Filters` | `[]string` | — | Original PDF stream filters (e.g. `["FlateDecode"]`, `["DCTDecode"]`). |
| `Data` | `[]byte` | — | The decoded image bytes in a standard format (JPEG, PNG, etc.). |
| `DecodedFormat` | `string` | — | The format of `data` after decoding: `"jpeg"`, `"png"`, `"jpeg2000"`, `"ccitt"`, or `"raw"`. |


---

### PdfImageExtractor

#### Methods

##### New()

**Signature:**

```go
func (o *PdfImageExtractor) New(pdfBytes []byte) (PdfImageExtractor, error)
```

##### NewWithPassword()

**Signature:**

```go
func (o *PdfImageExtractor) NewWithPassword(pdfBytes []byte, password string) (PdfImageExtractor, error)
```

##### ExtractImages()

**Signature:**

```go
func (o *PdfImageExtractor) ExtractImages() ([]PdfImage, error)
```

##### ExtractImagesFromPage()

**Signature:**

```go
func (o *PdfImageExtractor) ExtractImagesFromPage(pageNumber uint32) ([]PdfImage, error)
```

##### GetImageCount()

**Signature:**

```go
func (o *PdfImageExtractor) GetImageCount() (int, error)
```


---

### PdfLayoutBBox

Bounding box in PDF coordinate space (points, y=0 at bottom of page).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Left` | `float32` | — | Left |
| `Bottom` | `float32` | — | Bottom |
| `Right` | `float32` | — | Right |
| `Top` | `float32` | — | Top |

#### Methods

##### Width()

**Signature:**

```go
func (o *PdfLayoutBBox) Width() float32
```

##### Height()

**Signature:**

```go
func (o *PdfLayoutBBox) Height() float32
```


---

### PdfMetadata

PDF-specific metadata.

Contains metadata fields specific to PDF documents that are not in the common
`Metadata` structure. Common fields like title, authors, keywords, and dates
are now at the `Metadata` level.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `PdfVersion` | `*string` | `nil` | PDF version (e.g., "1.7", "2.0") |
| `Producer` | `*string` | `nil` | PDF producer (application that created the PDF) |
| `IsEncrypted` | `*bool` | `nil` | Whether the PDF is encrypted/password-protected |
| `Width` | `*int64` | `nil` | First page width in points (1/72 inch) |
| `Height` | `*int64` | `nil` | First page height in points (1/72 inch) |
| `PageCount` | `*int` | `nil` | Total number of pages in the PDF document |


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

##### New()

Create an iterator from raw PDF bytes.

Validates the PDF and determines the page count. The PDF bytes are
owned by the iterator — the file is not re-read from disk.

**Errors:**

Returns an error if the PDF is invalid or password-protected without
the correct password.

**Signature:**

```go
func (o *PdfPageIterator) New(pdfBytes []byte, dpi int32, password string) (PdfPageIterator, error)
```

##### FromFile()

Create an iterator from a file path.

Reads the file into memory once. Subsequent iterations render from
the owned bytes without re-reading the file.

**Errors:**

Returns an error if the file cannot be read or the PDF is invalid.

**Signature:**

```go
func (o *PdfPageIterator) FromFile(path Path, dpi int32, password string) (PdfPageIterator, error)
```

##### PageCount()

Number of pages in the PDF.

**Signature:**

```go
func (o *PdfPageIterator) PageCount() int
```

##### Next()

**Signature:**

```go
func (o *PdfPageIterator) Next() *Item
```

##### SizeHint()

**Signature:**

```go
func (o *PdfPageIterator) SizeHint() UsizeOptionUsize
```


---

### PdfRenderer

#### Methods

##### New()

**Signature:**

```go
func (o *PdfRenderer) New() (PdfRenderer, error)
```


---

### PdfTextExtractor

#### Methods

##### New()

**Signature:**

```go
func (o *PdfTextExtractor) New() (PdfTextExtractor, error)
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

##### Default()

**Signature:**

```go
func (o *PlainTextExtractor) Default() PlainTextExtractor
```

##### Name()

**Signature:**

```go
func (o *PlainTextExtractor) Name() string
```

##### Version()

**Signature:**

```go
func (o *PlainTextExtractor) Version() string
```

##### Initialize()

**Signature:**

```go
func (o *PlainTextExtractor) Initialize() error
```

##### Shutdown()

**Signature:**

```go
func (o *PlainTextExtractor) Shutdown() error
```

##### Description()

**Signature:**

```go
func (o *PlainTextExtractor) Description() string
```

##### Author()

**Signature:**

```go
func (o *PlainTextExtractor) Author() string
```

##### ExtractBytes()

**Signature:**

```go
func (o *PlainTextExtractor) ExtractBytes(content []byte, mimeType string, config ExtractionConfig) (InternalDocument, error)
```

##### SupportedMimeTypes()

**Signature:**

```go
func (o *PlainTextExtractor) SupportedMimeTypes() []string
```

##### Priority()

**Signature:**

```go
func (o *PlainTextExtractor) Priority() int32
```


---

### Plugin

Base trait that all plugins must implement.

This trait provides common functionality for plugin lifecycle management,
identification, and metadata.

# Thread Safety

All plugins must be `Send + Sync` to support concurrent usage across threads.

#### Methods

##### Name()

Returns the unique name/identifier for this plugin.

The name should be:
- Unique across all plugins
- Lowercase with hyphens (e.g., "my-custom-plugin")
- URL-safe characters only

**Signature:**

```go
func (o *Plugin) Name() string
```

##### Version()

Returns the semantic version of this plugin.

Should follow semver format: `MAJOR.MINOR.PATCH`

**Signature:**

```go
func (o *Plugin) Version() string
```

##### Initialize()

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

```go
func (o *Plugin) Initialize() error
```

##### Shutdown()

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

```go
func (o *Plugin) Shutdown() error
```

##### Description()

Optional plugin description for debugging and logging.

Defaults to empty string if not overridden.

**Signature:**

```go
func (o *Plugin) Description() string
```

##### Author()

Optional plugin author information.

Defaults to empty string if not overridden.

**Signature:**

```go
func (o *Plugin) Author() string
```


---

### PluginHealthStatus

Plugin health status information.

Contains diagnostic information about registered plugins for each type.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `OcrBackendsCount` | `int` | — | Number of registered OCR backends |
| `OcrBackends` | `[]string` | — | Names of registered OCR backends |
| `ExtractorsCount` | `int` | — | Number of registered document extractors |
| `Extractors` | `[]string` | — | Names of registered document extractors |
| `PostProcessorsCount` | `int` | — | Number of registered post-processors |
| `PostProcessors` | `[]string` | — | Names of registered post-processors |
| `ValidatorsCount` | `int` | — | Number of registered validators |
| `Validators` | `[]string` | — | Names of registered validators |

#### Methods

##### Check()

Check plugin health and return status.

This function reads all plugin registries and collects information
about registered plugins. It logs warnings if critical plugins are missing.

**Returns:**

`PluginHealthStatus` with counts and names of all registered plugins.

**Signature:**

```go
func (o *PluginHealthStatus) Check() PluginHealthStatus
```


---

### Pool

#### Methods

##### Acquire()

Acquire an object from the pool or create a new one if empty.

**Returns:**

A `PoolGuard<T>` that will return the object to the pool when dropped.

**Panics:**

Panics if the mutex is already locked by the current thread (deadlock).
This is a safety mechanism provided by parking_lot to prevent subtle bugs.

**Signature:**

```go
func (o *Pool) Acquire() (PoolGuard, error)
```

##### Size()

Get the current number of objects in the pool.

**Signature:**

```go
func (o *Pool) Size() int
```

##### Clear()

Clear the pool, discarding all pooled objects.

**Signature:**

```go
func (o *Pool) Clear() error
```


---

### PoolMetrics

Metrics tracking for pool allocations and reuse patterns.

These metrics help identify pool efficiency and allocation patterns.
Only available when the `pool-metrics` feature is enabled.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `TotalAcquires` | `AtomicUsize` | `nil` | Total number of acquire calls on this pool |
| `TotalCacheHits` | `AtomicUsize` | `nil` | Total number of cache hits (reused objects from pool) |
| `PeakItemsStored` | `AtomicUsize` | `nil` | Peak number of objects stored simultaneously in this pool |
| `TotalCreations` | `AtomicUsize` | `nil` | Total number of objects created by the factory function |

#### Methods

##### HitRate()

Calculate the cache hit rate as a percentage (0.0-100.0).

**Signature:**

```go
func (o *PoolMetrics) HitRate() float64
```

##### Snapshot()

Get all metrics as a struct for reporting.

**Signature:**

```go
func (o *PoolMetrics) Snapshot() PoolMetricsSnapshot
```

##### Reset()

Reset all metrics to zero.

**Signature:**

```go
func (o *PoolMetrics) Reset()
```

##### Default()

**Signature:**

```go
func (o *PoolMetrics) Default() PoolMetrics
```


---

### PoolMetricsSnapshot

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `TotalAcquires` | `int` | — | Total acquires |
| `TotalCacheHits` | `int` | — | Total cache hits |
| `PeakItemsStored` | `int` | — | Peak items stored |
| `TotalCreations` | `int` | — | Total creations |


---

### PoolSizeHint

Hint for optimal pool sizing based on document characteristics.

This struct contains the estimated sizes for string and byte buffers
that should be allocated in the pool to handle extraction without
excessive reallocation.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `EstimatedTotalSize` | `int` | — | Estimated total string buffer pool size in bytes |
| `StringBufferCount` | `int` | — | Recommended number of string buffers |
| `StringBufferCapacity` | `int` | — | Recommended capacity per string buffer in bytes |
| `ByteBufferCount` | `int` | — | Recommended number of byte buffers |
| `ByteBufferCapacity` | `int` | — | Recommended capacity per byte buffer in bytes |

#### Methods

##### EstimatedStringPoolMemory()

Calculate the estimated string pool memory in bytes.

This is the total estimated memory for all string buffers.

**Signature:**

```go
func (o *PoolSizeHint) EstimatedStringPoolMemory() int
```

##### EstimatedBytePoolMemory()

Calculate the estimated byte pool memory in bytes.

This is the total estimated memory for all byte buffers.

**Signature:**

```go
func (o *PoolSizeHint) EstimatedBytePoolMemory() int
```

##### TotalPoolMemory()

Calculate the total estimated pool memory in bytes.

This includes both string and byte buffer pools.

**Signature:**

```go
func (o *PoolSizeHint) TotalPoolMemory() int
```


---

### Position

Horizontal or vertical position.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `RelativeFrom` | `string` | — | Relative from |
| `Offset` | `*int64` | `nil` | Offset |


---

### PostProcessorConfig

Post-processor configuration.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Enabled` | `bool` | `true` | Enable post-processors |
| `EnabledProcessors` | `*[]string` | `nil` | Whitelist of processor names to run (None = all enabled) |
| `DisabledProcessors` | `*[]string` | `nil` | Blacklist of processor names to skip (None = none disabled) |
| `EnabledSet` | `*AHashSet` | `nil` | Pre-computed AHashSet for O(1) enabled processor lookup |
| `DisabledSet` | `*AHashSet` | `nil` | Pre-computed AHashSet for O(1) disabled processor lookup |

#### Methods

##### BuildLookupSets()

Pre-compute HashSets for O(1) processor name lookups.

This method converts the enabled/disabled processor Vec to HashSet
for constant-time lookups in the pipeline.

**Signature:**

```go
func (o *PostProcessorConfig) BuildLookupSets()
```

##### Default()

**Signature:**

```go
func (o *PostProcessorConfig) Default() PostProcessorConfig
```


---

### PptExtractionResult

Result of PPT text extraction.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Text` | `string` | — | Extracted text content, with slides separated by double newlines. |
| `SlideCount` | `int` | — | Number of slides found. |
| `Metadata` | `PptMetadata` | — | Document metadata. |
| `SpeakerNotes` | `[]string` | — | Speaker notes text per slide (if available). |


---

### PptExtractor

Native PPT extractor using OLE/CFB parsing.

This extractor handles PowerPoint 97-2003 binary (.ppt) files without
requiring LibreOffice, providing ~50x faster extraction.

#### Methods

##### Default()

**Signature:**

```go
func (o *PptExtractor) Default() PptExtractor
```

##### Name()

**Signature:**

```go
func (o *PptExtractor) Name() string
```

##### Version()

**Signature:**

```go
func (o *PptExtractor) Version() string
```

##### Initialize()

**Signature:**

```go
func (o *PptExtractor) Initialize() error
```

##### Shutdown()

**Signature:**

```go
func (o *PptExtractor) Shutdown() error
```

##### Description()

**Signature:**

```go
func (o *PptExtractor) Description() string
```

##### Author()

**Signature:**

```go
func (o *PptExtractor) Author() string
```

##### ExtractBytes()

**Signature:**

```go
func (o *PptExtractor) ExtractBytes(content []byte, mimeType string, config ExtractionConfig) (InternalDocument, error)
```

##### SupportedMimeTypes()

**Signature:**

```go
func (o *PptExtractor) SupportedMimeTypes() []string
```

##### Priority()

**Signature:**

```go
func (o *PptExtractor) Priority() int32
```


---

### PptMetadata

Metadata extracted from PPT files.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Title` | `*string` | `nil` | Title |
| `Subject` | `*string` | `nil` | Subject |
| `Author` | `*string` | `nil` | Author |
| `LastAuthor` | `*string` | `nil` | Last author |


---

### PptxAppProperties

Application properties from docProps/app.xml for PPTX

Contains PowerPoint-specific document metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Application` | `*string` | `nil` | Application name (e.g., "Microsoft Office PowerPoint") |
| `AppVersion` | `*string` | `nil` | Application version |
| `TotalTime` | `*int32` | `nil` | Total editing time in minutes |
| `Company` | `*string` | `nil` | Company name |
| `DocSecurity` | `*int32` | `nil` | Document security level |
| `ScaleCrop` | `*bool` | `nil` | Scale crop flag |
| `LinksUpToDate` | `*bool` | `nil` | Links up to date flag |
| `SharedDoc` | `*bool` | `nil` | Shared document flag |
| `HyperlinksChanged` | `*bool` | `nil` | Hyperlinks changed flag |
| `Slides` | `*int32` | `nil` | Number of slides |
| `Notes` | `*int32` | `nil` | Number of notes |
| `HiddenSlides` | `*int32` | `nil` | Number of hidden slides |
| `MultimediaClips` | `*int32` | `nil` | Number of multimedia clips |
| `PresentationFormat` | `*string` | `nil` | Presentation format (e.g., "Widescreen", "Standard") |
| `SlideTitles` | `[]string` | `nil` | Slide titles |


---

### PptxExtractionOptions

Options for PPTX content extraction.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `ExtractImages` | `bool` | `true` | Whether to extract embedded images. |
| `PageConfig` | `*PageConfig` | `nil` | Optional page configuration for boundary tracking. |
| `Plain` | `bool` | `false` | Whether to output plain text (no markdown). |
| `IncludeStructure` | `bool` | `false` | Whether to build the `DocumentStructure` tree. |
| `InjectPlaceholders` | `bool` | `true` | Whether to emit `![alt](target)` references in markdown output. |

#### Methods

##### Default()

**Signature:**

```go
func (o *PptxExtractionOptions) Default() PptxExtractionOptions
```


---

### PptxExtractionResult

PowerPoint (PPTX) extraction result.

Contains extracted slide content, metadata, and embedded images/tables.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Content` | `string` | — | Extracted text content from all slides |
| `Metadata` | `PptxMetadata` | — | Presentation metadata |
| `SlideCount` | `int` | — | Total number of slides |
| `ImageCount` | `int` | — | Total number of embedded images |
| `TableCount` | `int` | — | Total number of tables |
| `Images` | `[]ExtractedImage` | — | Extracted images from the presentation |
| `PageStructure` | `*PageStructure` | `nil` | Slide structure with boundaries (when page tracking is enabled) |
| `PageContents` | `*[]PageContent` | `nil` | Per-slide content (when page tracking is enabled) |
| `Document` | `*DocumentStructure` | `nil` | Structured document representation |
| `Hyperlinks` | `[]StringOptionString` | — | Hyperlinks discovered in slides as (url, optional_label) pairs. |
| `OfficeMetadata` | `map[string]string` | — | Office metadata extracted from docProps/core.xml and docProps/app.xml. Contains keys like "title", "author", "created_by", "subject", "keywords", "modified_by", "created_at", "modified_at", etc. |


---

### PptxExtractor

PowerPoint presentation extractor.

Supports: .pptx, .pptm, .ppsx

#### Methods

##### Default()

**Signature:**

```go
func (o *PptxExtractor) Default() PptxExtractor
```

##### Name()

**Signature:**

```go
func (o *PptxExtractor) Name() string
```

##### Version()

**Signature:**

```go
func (o *PptxExtractor) Version() string
```

##### Initialize()

**Signature:**

```go
func (o *PptxExtractor) Initialize() error
```

##### Shutdown()

**Signature:**

```go
func (o *PptxExtractor) Shutdown() error
```

##### ExtractBytes()

**Signature:**

```go
func (o *PptxExtractor) ExtractBytes(content []byte, mimeType string, config ExtractionConfig) (InternalDocument, error)
```

##### ExtractFile()

**Signature:**

```go
func (o *PptxExtractor) ExtractFile(path string, mimeType string, config ExtractionConfig) (InternalDocument, error)
```

##### SupportedMimeTypes()

**Signature:**

```go
func (o *PptxExtractor) SupportedMimeTypes() []string
```

##### Priority()

**Signature:**

```go
func (o *PptxExtractor) Priority() int32
```


---

### PptxMetadata

PowerPoint presentation metadata.

Extracted from PPTX files containing slide counts and presentation details.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `SlideCount` | `int` | — | Total number of slides in the presentation |
| `SlideNames` | `[]string` | — | Names of slides (if available) |
| `ImageCount` | `*int` | `nil` | Number of embedded images |
| `TableCount` | `*int` | `nil` | Number of tables |


---

### ProcessingWarning

A non-fatal warning from a processing pipeline stage.

Captures errors from optional features that don't prevent extraction
but may indicate degraded results.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Source` | `Str` | — | The pipeline stage or feature that produced this warning (e.g., "embedding", "chunking", "language_detection", "output_format"). |
| `Message` | `Str` | — | Human-readable description of what went wrong. |


---

### PstExtractor

PST file extractor.

Supports: .pst (Microsoft Outlook Personal Folders)

#### Methods

##### Default()

**Signature:**

```go
func (o *PstExtractor) Default() PstExtractor
```

##### Name()

**Signature:**

```go
func (o *PstExtractor) Name() string
```

##### Version()

**Signature:**

```go
func (o *PstExtractor) Version() string
```

##### Initialize()

**Signature:**

```go
func (o *PstExtractor) Initialize() error
```

##### Shutdown()

**Signature:**

```go
func (o *PstExtractor) Shutdown() error
```

##### ExtractSync()

**Signature:**

```go
func (o *PstExtractor) ExtractSync(content []byte, mimeType string, config ExtractionConfig) (InternalDocument, error)
```

##### ExtractBytes()

**Signature:**

```go
func (o *PstExtractor) ExtractBytes(content []byte, mimeType string, config ExtractionConfig) (InternalDocument, error)
```

##### SupportedMimeTypes()

**Signature:**

```go
func (o *PstExtractor) SupportedMimeTypes() []string
```

##### Priority()

**Signature:**

```go
func (o *PstExtractor) Priority() int32
```

##### AsSyncExtractor()

**Signature:**

```go
func (o *PstExtractor) AsSyncExtractor() *SyncExtractor
```


---

### PstMetadata

Outlook PST archive metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `MessageCount` | `int` | `nil` | Number of message |


---

### QualityProcessor

Post-processor that calculates quality score and cleans text.

This processor:
- Runs in the Early processing stage
- Calculates quality score when `config.enable_quality_processing` is true
- Stores quality score in `metadata.additional["quality_score"]`
- Cleans and normalizes extracted text

#### Methods

##### Name()

**Signature:**

```go
func (o *QualityProcessor) Name() string
```

##### Version()

**Signature:**

```go
func (o *QualityProcessor) Version() string
```

##### Initialize()

**Signature:**

```go
func (o *QualityProcessor) Initialize() error
```

##### Shutdown()

**Signature:**

```go
func (o *QualityProcessor) Shutdown() error
```

##### Process()

**Signature:**

```go
func (o *QualityProcessor) Process(result ExtractionResult, config ExtractionConfig) error
```

##### ProcessingStage()

**Signature:**

```go
func (o *QualityProcessor) ProcessingStage() ProcessingStage
```

##### ShouldProcess()

**Signature:**

```go
func (o *QualityProcessor) ShouldProcess(result ExtractionResult, config ExtractionConfig) bool
```

##### EstimatedDurationMs()

**Signature:**

```go
func (o *QualityProcessor) EstimatedDurationMs(result ExtractionResult) uint64
```


---

### RakeParams

RAKE-specific parameters.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `MinWordLength` | `int` | `1` | Minimum word length to consider (default: 1). |
| `MaxWordsPerPhrase` | `int` | `3` | Maximum words in a keyword phrase (default: 3). |

#### Methods

##### Default()

**Signature:**

```go
func (o *RakeParams) Default() RakeParams
```


---

### RecognizedTable

Pre-computed table markdown for a table detection region.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `DetectionBbox` | `BBox` | — | Detection bbox that this table corresponds to (for matching). |
| `Cells` | `[][]string` | — | Table cells as a 2D vector (rows x columns). |
| `Markdown` | `string` | — | Rendered markdown table. |


---

### Record

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `TagId` | `uint16` | — | Tag id |
| `Data` | `[]byte` | — | Data |

#### Methods

##### Parse()

**Signature:**

```go
func (o *Record) Parse(reader StreamReader) (Record, error)
```

##### DataReader()

Return a fresh `StreamReader` over this record's data bytes.

**Signature:**

```go
func (o *Record) DataReader() StreamReader
```


---

### Recyclable

Trait for types that can be pooled and reused.

Implementing this trait allows a type to be used with `Pool<T>`.
The `reset()` method should clear the object's state for reuse.

#### Methods

##### Reset()

Reset the object to a reusable state.

This is called when returning an object to the pool.
Should clear any internal data while preserving capacity.

**Signature:**

```go
func (o *Recyclable) Reset()
```


---

### Relationship

A relationship between two elements in the document.

During extraction, targets may be unresolved keys (`RelationshipTarget.Key`).
The derivation step resolves these to indices using the element anchor index.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Source` | `uint32` | — | Index of the source element in `InternalDocument.elements`. |
| `Target` | `RelationshipTarget` | — | Target of the relationship (resolved index or unresolved key). |
| `Kind` | `RelationshipKind` | — | Semantic kind of the relationship. |


---

### ResolvedStyle

Fully resolved (flattened) style after walking the inheritance chain.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `ParagraphProperties` | `ParagraphProperties` | `nil` | Paragraph properties (paragraph properties) |
| `RunProperties` | `RunProperties` | `nil` | Run properties (run properties) |


---

### RowProperties

Row-level properties from `<w:trPr>`.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Height` | `*int32` | `nil` | Height |
| `HeightRule` | `*string` | `nil` | Height rule |
| `IsHeader` | `bool` | `nil` | Whether header |
| `CantSplit` | `bool` | `nil` | Cant split |


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

##### BuildInternalDocument()

Build an `InternalDocument` from RST content.

Handles sections, paragraphs, code blocks, tables, footnotes, citations,
and cross-references.

**Signature:**

```go
func (o *RstExtractor) BuildInternalDocument(content string, injectPlaceholders bool) InternalDocument
```

##### Default()

**Signature:**

```go
func (o *RstExtractor) Default() RstExtractor
```

##### Name()

**Signature:**

```go
func (o *RstExtractor) Name() string
```

##### Version()

**Signature:**

```go
func (o *RstExtractor) Version() string
```

##### Initialize()

**Signature:**

```go
func (o *RstExtractor) Initialize() error
```

##### Shutdown()

**Signature:**

```go
func (o *RstExtractor) Shutdown() error
```

##### Description()

**Signature:**

```go
func (o *RstExtractor) Description() string
```

##### Author()

**Signature:**

```go
func (o *RstExtractor) Author() string
```

##### ExtractBytes()

**Signature:**

```go
func (o *RstExtractor) ExtractBytes(content []byte, mimeType string, config ExtractionConfig) (InternalDocument, error)
```

##### ExtractFile()

**Signature:**

```go
func (o *RstExtractor) ExtractFile(path string, mimeType string, config ExtractionConfig) (InternalDocument, error)
```

##### SupportedMimeTypes()

**Signature:**

```go
func (o *RstExtractor) SupportedMimeTypes() []string
```

##### Priority()

**Signature:**

```go
func (o *RstExtractor) Priority() int32
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

##### FromFile()

Load a Docling RT-DETR ONNX model from a file.

**Signature:**

```go
func (o *RtDetrModel) FromFile(path string) (RtDetrModel, error)
```

##### Detect()

**Signature:**

```go
func (o *RtDetrModel) Detect(img RgbImage) ([]LayoutDetection, error)
```

##### DetectWithThreshold()

**Signature:**

```go
func (o *RtDetrModel) DetectWithThreshold(img RgbImage, threshold float32) ([]LayoutDetection, error)
```

##### DetectBatch()

**Signature:**

```go
func (o *RtDetrModel) DetectBatch(images []RgbImage, threshold float32) ([][]LayoutDetection, error)
```

##### Name()

**Signature:**

```go
func (o *RtDetrModel) Name() string
```


---

### RtfExtractor

Native Rust RTF extractor.

Extracts text content, metadata, and structure from RTF documents

#### Methods

##### Default()

**Signature:**

```go
func (o *RtfExtractor) Default() RtfExtractor
```

##### Name()

**Signature:**

```go
func (o *RtfExtractor) Name() string
```

##### Version()

**Signature:**

```go
func (o *RtfExtractor) Version() string
```

##### Initialize()

**Signature:**

```go
func (o *RtfExtractor) Initialize() error
```

##### Shutdown()

**Signature:**

```go
func (o *RtfExtractor) Shutdown() error
```

##### Description()

**Signature:**

```go
func (o *RtfExtractor) Description() string
```

##### Author()

**Signature:**

```go
func (o *RtfExtractor) Author() string
```

##### ExtractBytes()

**Signature:**

```go
func (o *RtfExtractor) ExtractBytes(content []byte, mimeType string, config ExtractionConfig) (InternalDocument, error)
```

##### SupportedMimeTypes()

**Signature:**

```go
func (o *RtfExtractor) SupportedMimeTypes() []string
```

##### Priority()

**Signature:**

```go
func (o *RtfExtractor) Priority() int32
```


---

### Run

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Text` | `string` | `nil` | Text |
| `Bold` | `bool` | `nil` | Bold |
| `Italic` | `bool` | `nil` | Italic |
| `Underline` | `bool` | `nil` | Underline |
| `Strikethrough` | `bool` | `nil` | Strikethrough |
| `Subscript` | `bool` | `nil` | Subscript |
| `Superscript` | `bool` | `nil` | Superscript |
| `FontSize` | `*uint32` | `nil` | Font size in half-points (from `w:sz`). |
| `FontColor` | `*string` | `nil` | Font color as "RRGGBB" hex (from `w:color`). |
| `Highlight` | `*string` | `nil` | Highlight color name (from `w:highlight`). |
| `HyperlinkUrl` | `*string` | `nil` | Hyperlink url |
| `MathLatex` | `*StringBool` | `nil` | LaTeX math content: (latex_source, is_display_math). When set, this run represents an equation and `text` is ignored. |

#### Methods

##### ToMarkdown()

Render this run as markdown with formatting markers.

**Signature:**

```go
func (o *Run) ToMarkdown() string
```


---

### RunProperties

Run-level formatting properties (bold, italic, font, size, color, etc.).

All fields are `Option` so that inheritance resolution can distinguish
"not set" (`nil`) from "explicitly set" (`Some`).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Bold` | `*bool` | `nil` | Bold |
| `Italic` | `*bool` | `nil` | Italic |
| `Underline` | `*bool` | `nil` | Underline |
| `Strikethrough` | `*bool` | `nil` | Strikethrough |
| `Color` | `*string` | `nil` | Hex RGB color, e.g. `"2F5496"`. |
| `FontSizeHalfPoints` | `*int32` | `nil` | Font size in half-points (`w:sz` val). Divide by 2 to get points. |
| `FontAscii` | `*string` | `nil` | ASCII font family (`w:rFonts w:ascii`). |
| `FontAsciiTheme` | `*string` | `nil` | ASCII theme font (`w:rFonts w:asciiTheme`). |
| `VertAlign` | `*string` | `nil` | Vertical alignment: "superscript", "subscript", or "baseline". |
| `FontHAnsi` | `*string` | `nil` | High ANSI font family (w:rFonts w:hAnsi). |
| `FontCs` | `*string` | `nil` | Complex script font family (w:rFonts w:cs). |
| `FontEastAsia` | `*string` | `nil` | East Asian font family (w:rFonts w:eastAsia). |
| `Highlight` | `*string` | `nil` | Highlight color name (e.g., "yellow", "green", "cyan"). |
| `Caps` | `*bool` | `nil` | All caps text transformation. |
| `SmallCaps` | `*bool` | `nil` | Small caps text transformation. |
| `Shadow` | `*bool` | `nil` | Text shadow effect. |
| `Outline` | `*bool` | `nil` | Text outline effect. |
| `Emboss` | `*bool` | `nil` | Text emboss effect. |
| `Imprint` | `*bool` | `nil` | Text imprint (engrave) effect. |
| `CharSpacing` | `*int32` | `nil` | Character spacing in twips (from w:spacing w:val). |
| `Position` | `*int32` | `nil` | Vertical position offset in half-points (from w:position w:val). |
| `Kern` | `*int32` | `nil` | Kerning threshold in half-points (from w:kern w:val). |
| `ThemeColor` | `*string` | `nil` | Theme color reference (e.g., "accent1", "dk1"). |
| `ThemeTint` | `*string` | `nil` | Theme color tint modification (hex value). |
| `ThemeShade` | `*string` | `nil` | Theme color shade modification (hex value). |


---

### Section

A body-text section containing a flat list of paragraphs.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Paragraphs` | `[]Paragraph` | `nil` | Paragraphs |


---

### SectionProperties

DOCX section properties parsed from `w:sectPr` element.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `PageWidthTwips` | `*int32` | `nil` | Page width in twips (from `w:pgSz w:w`). |
| `PageHeightTwips` | `*int32` | `nil` | Page height in twips (from `w:pgSz w:h`). |
| `Orientation` | `*Orientation` | `Orientation.Portrait` | Page orientation (from `w:pgSz w:orient`). |
| `Margins` | `PageMargins` | `nil` | Page margins (from `w:pgMar`). |
| `Columns` | `ColumnLayout` | `nil` | Column layout (from `w:cols`). |
| `DocGridLinePitch` | `*int32` | `nil` | Document grid line pitch in twips (from `w:docGrid w:linePitch`). |

#### Methods

##### PageWidthPoints()

Convert page width from twips to points.

**Signature:**

```go
func (o *SectionProperties) PageWidthPoints() *float64
```

##### PageHeightPoints()

Convert page height from twips to points.

**Signature:**

```go
func (o *SectionProperties) PageHeightPoints() *float64
```


---

### SecurityLimits

Configuration for security limits across extractors.

All limits are intentionally conservative to prevent DoS attacks
while still supporting legitimate documents.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `MaxArchiveSize` | `int` | `nil` | Maximum uncompressed size for archives (500 MB) |
| `MaxCompressionRatio` | `int` | `100` | Maximum compression ratio before flagging as potential bomb (100:1) |
| `MaxFilesInArchive` | `int` | `10000` | Maximum number of files in archive (10,000) |
| `MaxNestingDepth` | `int` | `100` | Maximum nesting depth for structures (100) |
| `MaxEntityLength` | `int` | `32` | Maximum entity/string length (32) |
| `MaxContentSize` | `int` | `nil` | Maximum string growth per document (100 MB) |
| `MaxIterations` | `int` | `10000000` | Maximum iterations per operation |
| `MaxXmlDepth` | `int` | `100` | Maximum XML depth (100 levels) |
| `MaxTableCells` | `int` | `100000` | Maximum cells per table (100,000) |

#### Methods

##### Default()

**Signature:**

```go
func (o *SecurityLimits) Default() SecurityLimits
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
| `Host` | `string` | `nil` | Server host address (e.g., "127.0.0.1", "0.0.0.0") |
| `Port` | `uint16` | `nil` | Server port number |
| `CorsOrigins` | `[]string` | `nil` | CORS allowed origins. Empty vector means allow all origins. If this is an empty vector, the server will accept requests from any origin. If populated with specific origins (e.g., ["https://example.com"]), only those origins will be allowed. |
| `MaxRequestBodyBytes` | `int` | `nil` | Maximum size of request body in bytes (default: 100 MB) |
| `MaxMultipartFieldBytes` | `int` | `nil` | Maximum size of multipart fields in bytes (default: 100 MB) |

#### Methods

##### Default()

**Signature:**

```go
func (o *ServerConfig) Default() ServerConfig
```

##### ListenAddr()

Get the server listen address (host:port).

**Signature:**

```go
func (o *ServerConfig) ListenAddr() string
```

##### CorsAllowsAll()

Check if CORS allows all origins.

Returns `true` if the `cors_origins` vector is empty, meaning all origins
are allowed. Returns `false` if specific origins are configured.

**Signature:**

```go
func (o *ServerConfig) CorsAllowsAll() bool
```

##### IsOriginAllowed()

Check if a given origin is allowed by CORS configuration.

Returns `true` if:
- CORS allows all origins (empty origins list), or
- The given origin is in the allowed origins list

**Signature:**

```go
func (o *ServerConfig) IsOriginAllowed(origin string) bool
```

##### MaxRequestBodyMb()

Get maximum request body size in megabytes (rounded up).

**Signature:**

```go
func (o *ServerConfig) MaxRequestBodyMb() int
```

##### MaxMultipartFieldMb()

Get maximum multipart field size in megabytes (rounded up).

**Signature:**

```go
func (o *ServerConfig) MaxMultipartFieldMb() int
```

##### ApplyEnvOverrides()

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

```go
func (o *ServerConfig) ApplyEnvOverrides() error
```

##### FromFile()

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

```go
func (o *ServerConfig) FromFile(path Path) (ServerConfig, error)
```

##### FromTomlFile()

Load server configuration from a TOML file.

**Errors:**

Returns `KreuzbergError.Validation` if the file doesn't exist or is invalid TOML.

**Signature:**

```go
func (o *ServerConfig) FromTomlFile(path Path) (ServerConfig, error)
```

##### FromYamlFile()

Load server configuration from a YAML file.

**Errors:**

Returns `KreuzbergError.Validation` if the file doesn't exist or is invalid YAML.

**Signature:**

```go
func (o *ServerConfig) FromYamlFile(path Path) (ServerConfig, error)
```

##### FromJsonFile()

Load server configuration from a JSON file.

**Errors:**

Returns `KreuzbergError.Validation` if the file doesn't exist or is invalid JSON.

**Signature:**

```go
func (o *ServerConfig) FromJsonFile(path Path) (ServerConfig, error)
```


---

### SevenZExtractor

7z archive extractor.

Extracts file lists and text content from 7z archives.

#### Methods

##### Default()

**Signature:**

```go
func (o *SevenZExtractor) Default() SevenZExtractor
```

##### Name()

**Signature:**

```go
func (o *SevenZExtractor) Name() string
```

##### Version()

**Signature:**

```go
func (o *SevenZExtractor) Version() string
```

##### Initialize()

**Signature:**

```go
func (o *SevenZExtractor) Initialize() error
```

##### Shutdown()

**Signature:**

```go
func (o *SevenZExtractor) Shutdown() error
```

##### Description()

**Signature:**

```go
func (o *SevenZExtractor) Description() string
```

##### Author()

**Signature:**

```go
func (o *SevenZExtractor) Author() string
```

##### ExtractBytes()

**Signature:**

```go
func (o *SevenZExtractor) ExtractBytes(content []byte, mimeType string, config ExtractionConfig) (InternalDocument, error)
```

##### SupportedMimeTypes()

**Signature:**

```go
func (o *SevenZExtractor) SupportedMimeTypes() []string
```

##### Priority()

**Signature:**

```go
func (o *SevenZExtractor) Priority() int32
```

##### AsSyncExtractor()

**Signature:**

```go
func (o *SevenZExtractor) AsSyncExtractor() *SyncExtractor
```

##### ExtractSync()

**Signature:**

```go
func (o *SevenZExtractor) ExtractSync(content []byte, mimeType string, config ExtractionConfig) (InternalDocument, error)
```


---

### SlanetCell

A single cell detected by SLANeXT.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Polygon` | `F328` | — | Bounding box polygon in image pixel coordinates. Format: [x1, y1, x2, y2, x3, y3, x4, y4] (4 corners, clockwise from top-left). |
| `Bbox` | `F324` | — | Axis-aligned bounding box derived from polygon: [left, top, right, bottom]. |
| `Row` | `int` | — | Row index in the table (0-based). |
| `Col` | `int` | — | Column index within the row (0-based). |


---

### SlanetModel

SLANeXT table structure recognition model.

Wraps an ORT session for SLANeXT ONNX model and provides preprocessing,
inference, and post-processing in a single `recognize` call.

#### Methods

##### FromFile()

Load a SLANeXT ONNX model from a file path.

**Signature:**

```go
func (o *SlanetModel) FromFile(path string) (SlanetModel, error)
```

##### Recognize()

Recognize table structure from a cropped table image.

Returns a `SlanetResult` with detected cells, grid dimensions,
and structure tokens.

**Signature:**

```go
func (o *SlanetModel) Recognize(tableImg RgbImage) (SlanetResult, error)
```


---

### SlanetResult

SLANeXT recognition result for a single table image.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Cells` | `[]SlanetCell` | — | Detected cells with bounding boxes and grid positions. |
| `NumRows` | `int` | — | Number of rows in the table. |
| `NumCols` | `int` | — | Maximum number of columns across all rows. |
| `Confidence` | `float32` | — | Average structure prediction confidence. |
| `StructureTokens` | `[]string` | — | Raw HTML structure tokens (for debugging). |


---

### StreamReader

#### Methods

##### ReadU8()

**Signature:**

```go
func (o *StreamReader) ReadU8() (uint8, error)
```

##### ReadU16()

**Signature:**

```go
func (o *StreamReader) ReadU16() (uint16, error)
```

##### ReadU32()

**Signature:**

```go
func (o *StreamReader) ReadU32() (uint32, error)
```

##### ReadBytes()

**Signature:**

```go
func (o *StreamReader) ReadBytes(len int) ([]byte, error)
```

##### Position()

Current byte position within the stream.

**Signature:**

```go
func (o *StreamReader) Position() uint64
```

##### Remaining()

Number of bytes remaining from the current position to the end.

**Signature:**

```go
func (o *StreamReader) Remaining() int
```


---

### StringBufferPool

Convenience type alias for a pooled String.


---

### StringGrowthValidator

Helper struct for tracking and validating string growth.

#### Methods

##### CheckAppend()

Validate and update size after appending.

**Returns:**
* `Ok(())` if size is within limits
* `Err(SecurityError)` if size exceeds limit

**Signature:**

```go
func (o *StringGrowthValidator) CheckAppend(len int) error
```

##### CurrentSize()

Get current size.

**Signature:**

```go
func (o *StringGrowthValidator) CurrentSize() int
```


---

### StructuredData

Structured data (Schema.org, microdata, RDFa) block.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `DataType` | `StructuredDataType` | — | Type of structured data |
| `RawJson` | `string` | — | Raw JSON string representation |
| `SchemaType` | `*string` | `nil` | Schema type if detectable (e.g., "Article", "Event", "Product") |


---

### StructuredDataResult

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Content` | `string` | — | The extracted text content |
| `Format` | `Str` | — | Format (str) |
| `Metadata` | `map[string]string` | — | Document metadata |
| `TextFields` | `[]string` | — | Text fields |


---

### StructuredExtractionConfig

Configuration for LLM-based structured data extraction.

Sends extracted document content to a VLM with a JSON schema,
returning structured data that conforms to the schema.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Schema` | `interface{}` | — | JSON Schema defining the desired output structure. |
| `SchemaName` | `string` | — | Schema name passed to the LLM's structured output mode. |
| `SchemaDescription` | `*string` | `nil` | Optional schema description for the LLM. |
| `Strict` | `bool` | — | Enable strict mode — output must exactly match the schema. |
| `Prompt` | `*string` | `nil` | Custom Jinja2 extraction prompt template. When `None`, a default template is used. Available template variables: - `{{ content }}` — The extracted document text. - `{{ schema }}` — The JSON schema as a formatted string. - `{{ schema_name }}` — The schema name. - `{{ schema_description }}` — The schema description (may be empty). |
| `Llm` | `LlmConfig` | — | LLM configuration for the extraction. |


---

### StructuredExtractor

Structured data extractor supporting JSON, JSONL/NDJSON, YAML, and TOML.

#### Methods

##### Default()

**Signature:**

```go
func (o *StructuredExtractor) Default() StructuredExtractor
```

##### Name()

**Signature:**

```go
func (o *StructuredExtractor) Name() string
```

##### Version()

**Signature:**

```go
func (o *StructuredExtractor) Version() string
```

##### Initialize()

**Signature:**

```go
func (o *StructuredExtractor) Initialize() error
```

##### Shutdown()

**Signature:**

```go
func (o *StructuredExtractor) Shutdown() error
```

##### ExtractBytes()

**Signature:**

```go
func (o *StructuredExtractor) ExtractBytes(content []byte, mimeType string, config ExtractionConfig) (InternalDocument, error)
```

##### SupportedMimeTypes()

**Signature:**

```go
func (o *StructuredExtractor) SupportedMimeTypes() []string
```

##### Priority()

**Signature:**

```go
func (o *StructuredExtractor) Priority() int32
```


---

### StyleCatalog

Catalog of all styles parsed from `word/styles.xml`, plus document defaults.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Styles` | `AHashMap` | `nil` | Styles (a hash map) |
| `DefaultParagraphProperties` | `ParagraphProperties` | `nil` | Default paragraph properties (paragraph properties) |
| `DefaultRunProperties` | `RunProperties` | `nil` | Default run properties (run properties) |

#### Methods

##### ResolveStyle()

Resolve a style by walking its `basedOn` inheritance chain.

The resolution order is:
1. Document defaults (`<w:docDefaults>`)
2. Base style chain (walking `basedOn` from root to leaf)
3. The style itself

For `Option` fields, a child value of `Some(x)` overrides the parent.
A value of `nil` inherits from the parent. For boolean toggle properties,
`Some(false)` explicitly disables the property.

The chain depth is limited to 20 to prevent infinite loops from circular references.

**Signature:**

```go
func (o *StyleCatalog) ResolveStyle(styleId string) ResolvedStyle
```


---

### StyleDefinition

A single style definition parsed from `<w:style>` in `word/styles.xml`.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Id` | `string` | — | The style ID (`w:styleId` attribute). |
| `Name` | `*string` | `nil` | Human-readable name (`<w:name w:val="..."/>`). |
| `StyleType` | `StyleType` | — | Style type: paragraph, character, table, or numbering. |
| `BasedOn` | `*string` | `nil` | ID of the parent style (`<w:basedOn w:val="..."/>`). |
| `NextStyle` | `*string` | `nil` | ID of the style to apply to the next paragraph (`<w:next w:val="..."/>`). |
| `IsDefault` | `bool` | — | Whether this is the default style for its type. |
| `ParagraphProperties` | `ParagraphProperties` | — | Paragraph properties defined directly on this style. |
| `RunProperties` | `RunProperties` | — | Run properties defined directly on this style. |


---

### StyledHtmlRenderer

Styled HTML renderer.

Implements the `Renderer` trait; registered as `"html"` when the
`html` feature is active. Configuration is baked in at
construction time — no per-render allocation for CSS resolution.

#### Methods

##### New()

**Signature:**

```go
func (o *StyledHtmlRenderer) New(config HtmlOutputConfig) (StyledHtmlRenderer, error)
```

##### Name()

**Signature:**

```go
func (o *StyledHtmlRenderer) Name() string
```

##### Render()

**Signature:**

```go
func (o *StyledHtmlRenderer) Render(doc InternalDocument) (string, error)
```


---

### SupportedFormat

A supported document format entry.

Represents a file extension and its corresponding MIME type that Kreuzberg can process.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Extension` | `string` | — | File extension (without leading dot), e.g., "pdf", "docx" |
| `MimeType` | `string` | — | MIME type string, e.g., "application/pdf" |


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

##### ExtractSync()

Extract content from a byte array synchronously.

This method performs extraction without requiring an async runtime.
It is called by `extract_bytes_sync()` when the `tokio-runtime` feature is disabled.

**Returns:**

An `InternalDocument` containing the extracted elements, metadata, and tables.

**Signature:**

```go
func (o *SyncExtractor) ExtractSync(content []byte, mimeType string, config ExtractionConfig) (InternalDocument, error)
```


---

### Table

Extracted table structure.

Represents a table detected and extracted from a document (PDF, image, etc.).
Tables are converted to both structured cell data and Markdown format.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Cells` | `[][]string` | — | Table cells as a 2D vector (rows × columns) |
| `Markdown` | `string` | — | Markdown representation of the table |
| `PageNumber` | `int` | — | Page number where the table was found (1-indexed) |
| `BoundingBox` | `*BoundingBox` | `nil` | Bounding box of the table on the page (PDF coordinates: x0=left, y0=bottom, x1=right, y1=top). Only populated for PDF-extracted tables when position data is available. |


---

### TableBorders

Borders for a table (6 borders: top, bottom, left, right, insideH, insideV).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Top` | `*BorderStyle` | `nil` | Top (border style) |
| `Bottom` | `*BorderStyle` | `nil` | Bottom (border style) |
| `Left` | `*BorderStyle` | `nil` | Left (border style) |
| `Right` | `*BorderStyle` | `nil` | Right (border style) |
| `InsideH` | `*BorderStyle` | `nil` | Inside h (border style) |
| `InsideV` | `*BorderStyle` | `nil` | Inside v (border style) |


---

### TableCell

Individual table cell with content and optional styling.

Future extension point for rich table support with cell-level metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Content` | `string` | — | Cell content as text |
| `RowSpan` | `int` | — | Row span (number of rows this cell spans) |
| `ColSpan` | `int` | — | Column span (number of columns this cell spans) |
| `IsHeader` | `bool` | — | Whether this is a header cell |


---

### TableClassifier

PP-LCNet table classifier model.

#### Methods

##### FromFile()

Load the table classifier ONNX model from a file path.

**Signature:**

```go
func (o *TableClassifier) FromFile(path string) (TableClassifier, error)
```

##### Classify()

Classify a cropped table image as wired or wireless.

**Signature:**

```go
func (o *TableClassifier) Classify(tableImg RgbImage) (TableType, error)
```


---

### TableGrid

Structured table grid with cell-level metadata.

Stores row/column dimensions and a flat list of cells with position info.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Rows` | `uint32` | — | Number of rows in the table. |
| `Cols` | `uint32` | — | Number of columns in the table. |
| `Cells` | `[]GridCell` | — | All cells in row-major order. |


---

### TableLook

Table look bitmask/flags controlling conditional formatting bands.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `FirstRow` | `bool` | `nil` | First row |
| `LastRow` | `bool` | `nil` | Last row |
| `FirstColumn` | `bool` | `nil` | First column |
| `LastColumn` | `bool` | `nil` | Last column |
| `NoHBand` | `bool` | `nil` | No h band |
| `NoVBand` | `bool` | `nil` | No v band |


---

### TableProperties

Table-level properties from `<w:tblPr>`.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `StyleId` | `*string` | `nil` | Style id |
| `Width` | `*TableWidth` | `nil` | Width (table width) |
| `Alignment` | `*string` | `nil` | Alignment |
| `Layout` | `*string` | `nil` | Layout |
| `Look` | `*TableLook` | `nil` | Look (table look) |
| `Borders` | `*TableBorders` | `nil` | Borders (table borders) |
| `CellMargins` | `*CellMargins` | `nil` | Cell margins (cell margins) |
| `Indent` | `*TableWidth` | `nil` | Indent (table width) |
| `Caption` | `*string` | `nil` | Caption |


---

### TableRow

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Cells` | `[]TableCell` | `nil` | Cells |
| `Properties` | `*RowProperties` | `nil` | Properties (row properties) |


---

### TableValidator

Helper struct for validating table cell counts.

#### Methods

##### AddCells()

Add cells to table and validate.

**Returns:**
* `Ok(())` if cell count is within limits
* `Err(SecurityError)` if cell count exceeds limit

**Signature:**

```go
func (o *TableValidator) AddCells(count int) error
```

##### CurrentCells()

Get current cell count.

**Signature:**

```go
func (o *TableValidator) CurrentCells() int
```


---

### TableWidth

Width specification used for tables and cells.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Value` | `int32` | — | Value |
| `WidthType` | `string` | — | Width type |


---

### TarExtractor

TAR archive extractor.

Extracts file lists and text content from TAR archives.

#### Methods

##### Default()

**Signature:**

```go
func (o *TarExtractor) Default() TarExtractor
```

##### Name()

**Signature:**

```go
func (o *TarExtractor) Name() string
```

##### Version()

**Signature:**

```go
func (o *TarExtractor) Version() string
```

##### Initialize()

**Signature:**

```go
func (o *TarExtractor) Initialize() error
```

##### Shutdown()

**Signature:**

```go
func (o *TarExtractor) Shutdown() error
```

##### Description()

**Signature:**

```go
func (o *TarExtractor) Description() string
```

##### Author()

**Signature:**

```go
func (o *TarExtractor) Author() string
```

##### ExtractBytes()

**Signature:**

```go
func (o *TarExtractor) ExtractBytes(content []byte, mimeType string, config ExtractionConfig) (InternalDocument, error)
```

##### SupportedMimeTypes()

**Signature:**

```go
func (o *TarExtractor) SupportedMimeTypes() []string
```

##### Priority()

**Signature:**

```go
func (o *TarExtractor) Priority() int32
```

##### AsSyncExtractor()

**Signature:**

```go
func (o *TarExtractor) AsSyncExtractor() *SyncExtractor
```

##### ExtractSync()

**Signature:**

```go
func (o *TarExtractor) ExtractSync(content []byte, mimeType string, config ExtractionConfig) (InternalDocument, error)
```


---

### TatrDetection

A single TATR detection result.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Bbox` | `F324` | — | Bounding box in crop-pixel coordinates: `[x1, y1, x2, y2]`. |
| `Confidence` | `float32` | — | Detection confidence score (0.0..1.0). |
| `Class` | `TatrClass` | — | Detected class. |


---

### TatrModel

TATR (Table Transformer) table structure recognition model.

Wraps an ORT session for the TATR ONNX model and provides preprocessing,
inference, and post-processing in a single `recognize` call.

#### Methods

##### FromFile()

Load a TATR ONNX model from a file path.

Uses the default execution provider selection from `build_session`
with a CPU-only fallback if the platform EP fails.

**Signature:**

```go
func (o *TatrModel) FromFile(path string) (TatrModel, error)
```

##### Recognize()

Recognize table structure from a cropped table image.

Returns a `TatrResult` with detected rows, columns, headers, and
spanning cells in the input image's pixel coordinate space.

**Signature:**

```go
func (o *TatrModel) Recognize(tableImg RgbImage) (TatrResult, error)
```


---

### TatrResult

Aggregated TATR recognition result with detections separated by class.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Rows` | `[]TatrDetection` | — | Detected rows, sorted top-to-bottom by `y2`. |
| `Columns` | `[]TatrDetection` | — | Detected columns, sorted left-to-right by `x2`. |
| `Headers` | `[]TatrDetection` | — | Detected headers (ColumnHeader and ProjectedRowHeader). |
| `Spanning` | `[]TatrDetection` | — | Detected spanning cells. |


---

### TessdataManager

Manages tessdata file downloading, caching, and manifest generation.

#### Methods

##### CacheDir()

Get the cache directory path.

**Signature:**

```go
func (o *TessdataManager) CacheDir() string
```

##### IsLanguageCached()

Check if a specific language traineddata file is cached.

**Signature:**

```go
func (o *TessdataManager) IsLanguageCached(lang string) bool
```


---

### TesseractBackend

Native Tesseract OCR backend.

This backend wraps the OcrProcessor and implements the OcrBackend trait,
allowing it to be used through the plugin system.

# Thread Safety

Uses Arc for shared ownership and is thread-safe (Send + Sync).

#### Methods

##### New()

Create a new Tesseract backend with default cache directory.

**Signature:**

```go
func (o *TesseractBackend) New() (TesseractBackend, error)
```

##### WithCacheDir()

Create a new Tesseract backend with custom cache directory.

**Signature:**

```go
func (o *TesseractBackend) WithCacheDir(cacheDir string) (TesseractBackend, error)
```

##### Default()

**Signature:**

```go
func (o *TesseractBackend) Default() TesseractBackend
```

##### Name()

**Signature:**

```go
func (o *TesseractBackend) Name() string
```

##### Version()

**Signature:**

```go
func (o *TesseractBackend) Version() string
```

##### Initialize()

**Signature:**

```go
func (o *TesseractBackend) Initialize() error
```

##### Shutdown()

**Signature:**

```go
func (o *TesseractBackend) Shutdown() error
```

##### ProcessImage()

**Signature:**

```go
func (o *TesseractBackend) ProcessImage(imageBytes []byte, config OcrConfig) (ExtractionResult, error)
```

##### ProcessImageFile()

**Signature:**

```go
func (o *TesseractBackend) ProcessImageFile(path string, config OcrConfig) (ExtractionResult, error)
```

##### SupportsLanguage()

**Signature:**

```go
func (o *TesseractBackend) SupportsLanguage(lang string) bool
```

##### BackendType()

**Signature:**

```go
func (o *TesseractBackend) BackendType() OcrBackendType
```

##### SupportedLanguages()

**Signature:**

```go
func (o *TesseractBackend) SupportedLanguages() []string
```

##### SupportsTableDetection()

**Signature:**

```go
func (o *TesseractBackend) SupportsTableDetection() bool
```


---

### TesseractConfig

Tesseract OCR configuration.

Provides fine-grained control over Tesseract OCR engine parameters.
Most users can use the defaults, but these settings allow optimization
for specific document types (invoices, handwriting, etc.).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Language` | `string` | `"eng"` | Language code (e.g., "eng", "deu", "fra") |
| `Psm` | `int32` | `3` | Page Segmentation Mode (0-13). Common values: - 3: Fully automatic page segmentation (default) - 6: Assume a single uniform block of text - 11: Sparse text with no particular order |
| `OutputFormat` | `string` | `"markdown"` | Output format ("text" or "markdown") |
| `Oem` | `int32` | `3` | OCR Engine Mode (0-3). - 0: Legacy engine only - 1: Neural nets (LSTM) only (usually best) - 2: Legacy + LSTM - 3: Default (based on what's available) |
| `MinConfidence` | `float64` | `0` | Minimum confidence threshold (0.0-100.0). Words with confidence below this threshold may be rejected or flagged. |
| `Preprocessing` | `*ImagePreprocessingConfig` | `nil` | Image preprocessing configuration. Controls how images are preprocessed before OCR. Can significantly improve quality for scanned documents or low-quality images. |
| `EnableTableDetection` | `bool` | `true` | Enable automatic table detection and reconstruction |
| `TableMinConfidence` | `float64` | `0` | Minimum confidence threshold for table detection (0.0-1.0) |
| `TableColumnThreshold` | `int32` | `50` | Column threshold for table detection (pixels) |
| `TableRowThresholdRatio` | `float64` | `0.5` | Row threshold ratio for table detection (0.0-1.0) |
| `UseCache` | `bool` | `true` | Enable OCR result caching |
| `ClassifyUsePreAdaptedTemplates` | `bool` | `true` | Use pre-adapted templates for character classification |
| `LanguageModelNgramOn` | `bool` | `false` | Enable N-gram language model |
| `TesseditDontBlkrejGoodWds` | `bool` | `true` | Don't reject good words during block-level processing |
| `TesseditDontRowrejGoodWds` | `bool` | `true` | Don't reject good words during row-level processing |
| `TesseditEnableDictCorrection` | `bool` | `true` | Enable dictionary correction |
| `TesseditCharWhitelist` | `string` | `""` | Whitelist of allowed characters (empty = all allowed) |
| `TesseditCharBlacklist` | `string` | `""` | Blacklist of forbidden characters (empty = none forbidden) |
| `TesseditUsePrimaryParamsModel` | `bool` | `true` | Use primary language params model |
| `TextordSpaceSizeIsVariable` | `bool` | `true` | Variable-width space detection |
| `ThresholdingMethod` | `bool` | `false` | Use adaptive thresholding method |

#### Methods

##### Default()

**Signature:**

```go
func (o *TesseractConfig) Default() TesseractConfig
```


---

### TextAnnotation

Inline text annotation — byte-range based formatting and links.

Annotations reference byte offsets into the node's text content,
enabling precise identification of formatted regions.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Start` | `uint32` | — | Start byte offset in the node's text content (inclusive). |
| `End` | `uint32` | — | End byte offset in the node's text content (exclusive). |
| `Kind` | `AnnotationKind` | — | Annotation type. |


---

### TextExtractionResult

Plain text and Markdown extraction result.

Contains the extracted text along with statistics and,
for Markdown files, structural elements like headers and links.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Content` | `string` | — | Extracted text content |
| `LineCount` | `int` | — | Number of lines |
| `WordCount` | `int` | — | Number of words |
| `CharacterCount` | `int` | — | Number of characters |
| `Headers` | `*[]string` | `nil` | Markdown headers (text only, Markdown files only) |
| `Links` | `*[]StringString` | `nil` | Markdown links as (text, URL) tuples (Markdown files only) |
| `CodeBlocks` | `*[]StringString` | `nil` | Code blocks as (language, code) tuples (Markdown files only) |


---

### TextMetadata

Text/Markdown metadata.

Extracted from plain text and Markdown files. Includes word counts and,
for Markdown, structural elements like headers and links.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `LineCount` | `int` | — | Number of lines in the document |
| `WordCount` | `int` | — | Number of words |
| `CharacterCount` | `int` | — | Number of characters |
| `Headers` | `*[]string` | `nil` | Markdown headers (headings text only, for Markdown files) |
| `Links` | `*[]StringString` | `nil` | Markdown links as (text, url) tuples (for Markdown files) |
| `CodeBlocks` | `*[]StringString` | `nil` | Code blocks as (language, code) tuples (for Markdown files) |


---

### Theme

Complete theme with color scheme and font scheme.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Name` | `string` | `nil` | Theme name (e.g., "Office Theme"). |
| `ColorScheme` | `*ColorScheme` | `nil` | Color scheme (12 standard colors). |
| `FontScheme` | `*FontScheme` | `nil` | Font scheme (major and minor fonts). |


---

### TokenReductionConfig

Token reduction configuration.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Mode` | `string` | — | Reduction mode: "off", "light", "moderate", "aggressive", "maximum" |
| `PreserveImportantWords` | `bool` | — | Preserve important words (capitalized, technical terms) |


---

### TracingLayer

A `tower.Layer` that wraps each extraction in a semantic tracing span.

#### Methods

##### Layer()

**Signature:**

```go
func (o *TracingLayer) Layer(inner S) Service
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
| `Enabled` | `bool` | `true` | Enable code intelligence processing (default: true). When `False`, tree-sitter analysis is completely skipped even if the config section is present. |
| `CacheDir` | `*string` | `nil` | Custom cache directory for downloaded grammars. When `None`, uses the default: `~/.cache/tree-sitter-language-pack/v{version}/libs/`. |
| `Languages` | `*[]string` | `nil` | Languages to pre-download on init (e.g., `["python", "rust"]`). |
| `Groups` | `*[]string` | `nil` | Language groups to pre-download (e.g., `["web", "systems", "scripting"]`). |
| `Process` | `TreeSitterProcessConfig` | `nil` | Processing options for code analysis. |

#### Methods

##### Default()

**Signature:**

```go
func (o *TreeSitterConfig) Default() TreeSitterConfig
```


---

### TreeSitterProcessConfig

Processing options for tree-sitter code analysis.

Controls which analysis features are enabled when extracting code files.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Structure` | `bool` | `true` | Extract structural items (functions, classes, structs, etc.). Default: true. |
| `Imports` | `bool` | `true` | Extract import statements. Default: true. |
| `Exports` | `bool` | `true` | Extract export statements. Default: true. |
| `Comments` | `bool` | `false` | Extract comments. Default: false. |
| `Docstrings` | `bool` | `false` | Extract docstrings. Default: false. |
| `Symbols` | `bool` | `false` | Extract symbol definitions. Default: false. |
| `Diagnostics` | `bool` | `false` | Include parse diagnostics. Default: false. |
| `ChunkMaxSize` | `*int` | `nil` | Maximum chunk size in bytes. `None` disables chunking. |
| `ContentMode` | `CodeContentMode` | `CodeContentMode.Chunks` | Content rendering mode for code extraction. |

#### Methods

##### Default()

**Signature:**

```go
func (o *TreeSitterProcessConfig) Default() TreeSitterProcessConfig
```


---

### TsvRow

Tesseract TSV row data for conversion.

This struct represents a single row from Tesseract's TSV output format.
TSV format includes hierarchical information (block, paragraph, line, word)
along with bounding boxes and confidence scores.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Level` | `int32` | — | Hierarchical level (1=block, 2=para, 3=line, 4=word, 5=symbol) |
| `PageNum` | `int32` | — | Page number (1-indexed) |
| `BlockNum` | `int32` | — | Block number within page |
| `ParNum` | `int32` | — | Paragraph number within block |
| `LineNum` | `int32` | — | Line number within paragraph |
| `WordNum` | `int32` | — | Word number within line |
| `Left` | `uint32` | — | Left x-coordinate in pixels |
| `Top` | `uint32` | — | Top y-coordinate in pixels |
| `Width` | `uint32` | — | Width in pixels |
| `Height` | `uint32` | — | Height in pixels |
| `Conf` | `float64` | — | Confidence score (0-100) |
| `Text` | `string` | — | Recognized text |


---

### TypstExtractor

Typst document extractor

#### Methods

##### Default()

**Signature:**

```go
func (o *TypstExtractor) Default() TypstExtractor
```

##### Name()

**Signature:**

```go
func (o *TypstExtractor) Name() string
```

##### Version()

**Signature:**

```go
func (o *TypstExtractor) Version() string
```

##### Initialize()

**Signature:**

```go
func (o *TypstExtractor) Initialize() error
```

##### Shutdown()

**Signature:**

```go
func (o *TypstExtractor) Shutdown() error
```

##### Description()

**Signature:**

```go
func (o *TypstExtractor) Description() string
```

##### Author()

**Signature:**

```go
func (o *TypstExtractor) Author() string
```

##### ExtractBytes()

**Signature:**

```go
func (o *TypstExtractor) ExtractBytes(content []byte, mimeType string, config ExtractionConfig) (InternalDocument, error)
```

##### ExtractFile()

**Signature:**

```go
func (o *TypstExtractor) ExtractFile(path string, mimeType string, config ExtractionConfig) (InternalDocument, error)
```

##### SupportedMimeTypes()

**Signature:**

```go
func (o *TypstExtractor) SupportedMimeTypes() []string
```

##### Priority()

**Signature:**

```go
func (o *TypstExtractor) Priority() int32
```


---

### Uri

A URI extracted from a document.

Represents any link, reference, or resource pointer found during extraction.
The `kind` field classifies the URI semantically, while `label` carries
optional human-readable display text.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Url` | `string` | — | The URL or path string. |
| `Label` | `*string` | `nil` | Optional display text / label for the link. |
| `Page` | `*uint32` | `nil` | Optional page number where the URI was found (1-indexed). |
| `Kind` | `UriKind` | — | Semantic classification of the URI. |

#### Methods

##### Hyperlink()

Create a new hyperlink URI, auto-classifying `mailto:` as Email and `#` as Anchor.

**Signature:**

```go
func (o *Uri) Hyperlink(url string, label string) Uri
```

##### Image()

Create a new image URI.

**Signature:**

```go
func (o *Uri) Image(url string, label string) Uri
```

##### Citation()

Create a new citation URI (for DOIs, academic references).

**Signature:**

```go
func (o *Uri) Citation(url string, label string) Uri
```

##### Anchor()

Create a new anchor/cross-reference URI.

**Signature:**

```go
func (o *Uri) Anchor(url string, label string) Uri
```

##### Email()

Create a new email URI.

**Signature:**

```go
func (o *Uri) Email(url string, label string) Uri
```

##### Reference()

Create a new reference URI.

**Signature:**

```go
func (o *Uri) Reference(url string, label string) Uri
```

##### WithPage()

Set the page number.

**Signature:**

```go
func (o *Uri) WithPage(page uint32) Uri
```


---

### VlmOcrBackend

VLM-based OCR backend using liter-llm vision models.

This backend sends images to a vision language model (e.g., GPT-4o, Claude)
for text extraction, as an alternative to traditional OCR backends.

#### Methods

##### Name()

**Signature:**

```go
func (o *VlmOcrBackend) Name() string
```

##### Version()

**Signature:**

```go
func (o *VlmOcrBackend) Version() string
```

##### Initialize()

**Signature:**

```go
func (o *VlmOcrBackend) Initialize() error
```

##### Shutdown()

**Signature:**

```go
func (o *VlmOcrBackend) Shutdown() error
```

##### ProcessImage()

**Signature:**

```go
func (o *VlmOcrBackend) ProcessImage(imageBytes []byte, config OcrConfig) (ExtractionResult, error)
```

##### SupportsLanguage()

**Signature:**

```go
func (o *VlmOcrBackend) SupportsLanguage(lang string) bool
```

##### BackendType()

**Signature:**

```go
func (o *VlmOcrBackend) BackendType() OcrBackendType
```


---

### XlsxAppProperties

Application properties from docProps/app.xml for XLSX

Contains Excel-specific document metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Application` | `*string` | `nil` | Application name (e.g., "Microsoft Excel") |
| `AppVersion` | `*string` | `nil` | Application version |
| `DocSecurity` | `*int32` | `nil` | Document security level |
| `ScaleCrop` | `*bool` | `nil` | Scale crop flag |
| `LinksUpToDate` | `*bool` | `nil` | Links up to date flag |
| `SharedDoc` | `*bool` | `nil` | Shared document flag |
| `HyperlinksChanged` | `*bool` | `nil` | Hyperlinks changed flag |
| `Company` | `*string` | `nil` | Company name |
| `WorksheetNames` | `[]string` | `nil` | Worksheet names |


---

### XmlExtractionResult

XML extraction result.

Contains extracted text content from XML files along with
structural statistics about the XML document.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Content` | `string` | — | Extracted text content (XML structure filtered out) |
| `ElementCount` | `int` | — | Total number of XML elements processed |
| `UniqueElements` | `[]string` | — | List of unique element names found (sorted) |


---

### XmlExtractor

XML extractor.

Extracts text content from XML files, preserving element structure information.

#### Methods

##### Default()

**Signature:**

```go
func (o *XmlExtractor) Default() XmlExtractor
```

##### Name()

**Signature:**

```go
func (o *XmlExtractor) Name() string
```

##### Version()

**Signature:**

```go
func (o *XmlExtractor) Version() string
```

##### Initialize()

**Signature:**

```go
func (o *XmlExtractor) Initialize() error
```

##### Shutdown()

**Signature:**

```go
func (o *XmlExtractor) Shutdown() error
```

##### Description()

**Signature:**

```go
func (o *XmlExtractor) Description() string
```

##### Author()

**Signature:**

```go
func (o *XmlExtractor) Author() string
```

##### ExtractSync()

**Signature:**

```go
func (o *XmlExtractor) ExtractSync(content []byte, mimeType string, config ExtractionConfig) (InternalDocument, error)
```

##### ExtractBytes()

**Signature:**

```go
func (o *XmlExtractor) ExtractBytes(content []byte, mimeType string, config ExtractionConfig) (InternalDocument, error)
```

##### SupportedMimeTypes()

**Signature:**

```go
func (o *XmlExtractor) SupportedMimeTypes() []string
```

##### Priority()

**Signature:**

```go
func (o *XmlExtractor) Priority() int32
```

##### AsSyncExtractor()

**Signature:**

```go
func (o *XmlExtractor) AsSyncExtractor() *SyncExtractor
```


---

### XmlMetadata

XML metadata extracted during XML parsing.

Provides statistics about XML document structure.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `ElementCount` | `int` | — | Total number of XML elements processed |
| `UniqueElements` | `[]string` | — | List of unique element tag names (sorted) |


---

### YakeParams

YAKE-specific parameters.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `WindowSize` | `int` | `2` | Window size for co-occurrence analysis (default: 2). Controls the context window for computing co-occurrence statistics. |

#### Methods

##### Default()

**Signature:**

```go
func (o *YakeParams) Default() YakeParams
```


---

### YearRange

Year range for bibliographic metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `Min` | `*uint32` | `nil` | Min |
| `Max` | `*uint32` | `nil` | Max |
| `Years` | `[]uint32` | — | Years |


---

### YoloModel

YOLO-family layout detection model (YOLOv10, DocLayout-YOLO, YOLOX).

#### Methods

##### FromFile()

Load a YOLO ONNX model from a file.

For square-input models (YOLOv10, DocLayout-YOLO), pass the same value for both dimensions.
For YOLOX (unstructuredio), use width=768, height=1024.

**Signature:**

```go
func (o *YoloModel) FromFile(path string, variant YoloVariant, inputWidth uint32, inputHeight uint32, modelName string) (YoloModel, error)
```

##### Detect()

**Signature:**

```go
func (o *YoloModel) Detect(img RgbImage) ([]LayoutDetection, error)
```

##### DetectWithThreshold()

**Signature:**

```go
func (o *YoloModel) DetectWithThreshold(img RgbImage, threshold float32) ([]LayoutDetection, error)
```

##### Name()

**Signature:**

```go
func (o *YoloModel) Name() string
```


---

### ZipBombValidator

Helper struct for validating ZIP archives for security issues.


---

### ZipExtractor

ZIP archive extractor.

Extracts file lists and text content from ZIP archives.

#### Methods

##### Default()

**Signature:**

```go
func (o *ZipExtractor) Default() ZipExtractor
```

##### Name()

**Signature:**

```go
func (o *ZipExtractor) Name() string
```

##### Version()

**Signature:**

```go
func (o *ZipExtractor) Version() string
```

##### Initialize()

**Signature:**

```go
func (o *ZipExtractor) Initialize() error
```

##### Shutdown()

**Signature:**

```go
func (o *ZipExtractor) Shutdown() error
```

##### Description()

**Signature:**

```go
func (o *ZipExtractor) Description() string
```

##### Author()

**Signature:**

```go
func (o *ZipExtractor) Author() string
```

##### ExtractBytes()

**Signature:**

```go
func (o *ZipExtractor) ExtractBytes(content []byte, mimeType string, config ExtractionConfig) (InternalDocument, error)
```

##### SupportedMimeTypes()

**Signature:**

```go
func (o *ZipExtractor) SupportedMimeTypes() []string
```

##### Priority()

**Signature:**

```go
func (o *ZipExtractor) Priority() int32
```

##### AsSyncExtractor()

**Signature:**

```go
func (o *ZipExtractor) AsSyncExtractor() *SyncExtractor
```

##### ExtractSync()

**Signature:**

```go
func (o *ZipExtractor) ExtractSync(content []byte, mimeType string, config ExtractionConfig) (InternalDocument, error)
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

