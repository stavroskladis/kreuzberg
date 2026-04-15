---
title: "Rust API Reference"
---

# Rust API Reference <span class="version-badge">v4.8.5</span>

## Functions

### is_batch_mode()

Check if we're currently in batch processing mode.

Returns `false` if the task-local is not set (single-file mode).

**Signature:**

```rust
pub fn is_batch_mode() -> bool
```

**Returns:** `bool`


---

### resolve_thread_budget()

Resolve the effective thread budget from config or auto-detection.

User-set `max_threads` takes priority. Otherwise auto-detects from `num_cpus`,
capped at 8 for sane defaults in serverless environments.

**Signature:**

```rust
pub fn resolve_thread_budget(config: Option<ConcurrencyConfig>) -> usize
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `config` | `Option<ConcurrencyConfig>` | No | The configuration options |

**Returns:** `usize`


---

### init_thread_pools()

Initialize the global Rayon thread pool with the given budget.

Safe to call multiple times — only the first call takes effect (subsequent
calls are silently ignored).

**Signature:**

```rust
pub fn init_thread_pools(budget: usize)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `budget` | `usize` | Yes | The budget |

**Returns:** `()`


---

### merge_config_json()

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

```rust
pub fn merge_config_json(base: ExtractionConfig, override_json: serde_json::Value) -> Result<ExtractionConfig, String>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `base` | `ExtractionConfig` | Yes | The extraction config |
| `override_json` | `serde_json::Value` | Yes | The override json |

**Returns:** `ExtractionConfig`

**Errors:** Returns `Err(String)`.


---

### build_config_from_json()

Build extraction config by optionally merging JSON overrides into a base config.

If `override_json` is `None`, returns a clone of `base`. Otherwise delegates
to `merge_config_json`.

**Signature:**

```rust
pub fn build_config_from_json(base: ExtractionConfig, override_json: Option<serde_json::Value>) -> Result<ExtractionConfig, String>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `base` | `ExtractionConfig` | Yes | The extraction config |
| `override_json` | `Option<serde_json::Value>` | No | The override json |

**Returns:** `ExtractionConfig`

**Errors:** Returns `Err(String)`.


---

### is_valid_format_field()

Validates whether a field name is in the known formats registry.

This uses a pre-built hash set for O(1) lookups instead of linear search,
providing significant performance improvements for repeated validations.

**Returns:**

`true` if the field is in KNOWN_FORMATS, `false` otherwise.

**Signature:**

```rust
pub fn is_valid_format_field(field: &str) -> bool
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `field` | `String` | Yes | The field name to validate |

**Returns:** `bool`


---

### open_file_bytes()

Open a file and return its bytes with zero-copy for large files.

On non-WASM targets, files larger than `MMAP_THRESHOLD_BYTES` are
memory-mapped so that the file contents are never copied to the heap.
The mapping is read-only; the file must not be modified while the returned
`FileBytes` is alive, which is safe for document extraction.

On WASM or for small files, falls back to a plain `std.fs.read`.

**Errors:**

Returns `KreuzbergError.Io` for any I/O failure.

**Signature:**

```rust
pub fn open_file_bytes(path: PathBuf) -> Result<FileBytes, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `path` | `PathBuf` | Yes | Path to the file |

**Returns:** `FileBytes`

**Errors:** Returns `Err(Error)`.


---

### read_file_async()

Read a file asynchronously.

**Returns:**

The file contents as bytes.

**Errors:**

Returns `KreuzbergError.Io` for I/O errors (these always bubble up).

**Signature:**

```rust
pub async fn read_file_async(path: Path) -> Result<Vec<u8>, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `path` | `Path` | Yes | Path to the file to read |

**Returns:** `Vec<u8>`

**Errors:** Returns `Err(Error)`.


---

### read_file_sync()

Read a file synchronously.

**Returns:**

The file contents as bytes.

**Errors:**

Returns `KreuzbergError.Io` for I/O errors (these always bubble up).

**Signature:**

```rust
pub fn read_file_sync(path: Path) -> Result<Vec<u8>, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `path` | `Path` | Yes | Path to the file to read |

**Returns:** `Vec<u8>`

**Errors:** Returns `Err(Error)`.


---

### file_exists()

Check if a file exists.

**Returns:**

`true` if the file exists, `false` otherwise.

**Signature:**

```rust
pub fn file_exists(path: Path) -> bool
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `path` | `Path` | Yes | Path to check |

**Returns:** `bool`


---

### validate_file_exists()

Validate that a file exists.

**Errors:**

Returns `KreuzbergError.Io` if file doesn't exist.

**Signature:**

```rust
pub fn validate_file_exists(path: Path) -> Result<(), Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `path` | `Path` | Yes | Path to validate |

**Returns:** `()`

**Errors:** Returns `Err(Error)`.


---

### find_files_by_extension()

Get all files in a directory with a specific extension.

**Returns:**

Vector of file paths with the specified extension.

**Errors:**

Returns `KreuzbergError.Io` for I/O errors.

**Signature:**

```rust
pub fn find_files_by_extension(dir: Path, extension: &str, recursive: bool) -> Result<Vec<PathBuf>, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `dir` | `Path` | Yes | Directory to search |
| `extension` | `String` | Yes | File extension to match (without the dot) |
| `recursive` | `bool` | Yes | Whether to recursively search subdirectories |

**Returns:** `Vec<PathBuf>`

**Errors:** Returns `Err(Error)`.


---

### detect_mime_type()

Detect MIME type from a file path.

Uses file extension to determine MIME type. Falls back to `mime_guess` crate
if extension-based detection fails.

**Returns:**

The detected MIME type string.

**Errors:**

Returns `KreuzbergError.Io` if file doesn't exist (when `check_exists` is true).
Returns `KreuzbergError.UnsupportedFormat` if MIME type cannot be determined.

**Signature:**

```rust
pub fn detect_mime_type(path: Path, check_exists: bool) -> Result<String, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `path` | `Path` | Yes | Path to the file |
| `check_exists` | `bool` | Yes | Whether to verify file existence |

**Returns:** `String`

**Errors:** Returns `Err(Error)`.


---

### validate_mime_type()

Validate that a MIME type is supported.

**Returns:**

The validated MIME type (may be normalized).

**Errors:**

Returns `KreuzbergError.UnsupportedFormat` if not supported.

**Signature:**

```rust
pub fn validate_mime_type(mime_type: &str) -> Result<String, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `mime_type` | `String` | Yes | The MIME type to validate |

**Returns:** `String`

**Errors:** Returns `Err(Error)`.


---

### detect_or_validate()

Detect or validate MIME type.

If `mime_type` is provided, validates it. Otherwise, detects from `path`.

**Returns:**

The validated MIME type string.

**Signature:**

```rust
pub fn detect_or_validate(path: Option<PathBuf>, mime_type: Option<String>) -> Result<String, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `path` | `Option<PathBuf>` | No | Optional path to detect MIME type from |
| `mime_type` | `Option<String>` | No | Optional explicit MIME type to validate |

**Returns:** `String`

**Errors:** Returns `Err(Error)`.


---

### detect_mime_type_from_bytes()

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

```rust
pub fn detect_mime_type_from_bytes(content: &[u8]) -> Result<String, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `content` | `Vec<u8>` | Yes | Raw file bytes |

**Returns:** `String`

**Errors:** Returns `Err(Error)`.


---

### get_extensions_for_mime()

Get file extensions for a given MIME type.

Returns all known file extensions that map to the specified MIME type.

**Returns:**

A vector of file extensions (without leading dot) for the MIME type.

**Signature:**

```rust
pub fn get_extensions_for_mime(mime_type: &str) -> Result<Vec<String>, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `mime_type` | `String` | Yes | The MIME type to look up |

**Returns:** `Vec<String>`

**Errors:** Returns `Err(Error)`.


---

### list_supported_formats()

List all supported document formats.

Returns a list of all file extensions and their corresponding MIME types
that Kreuzberg can process. Derived from the centralized `FORMATS` registry.

The list is sorted alphabetically by file extension.

**Signature:**

```rust
pub fn list_supported_formats() -> Vec<SupportedFormat>
```

**Returns:** `Vec<SupportedFormat>`


---

### run_pipeline()

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

```rust
pub async fn run_pipeline(doc: InternalDocument, config: ExtractionConfig) -> Result<ExtractionResult, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `doc` | `InternalDocument` | Yes | The internal document produced by the extractor |
| `config` | `ExtractionConfig` | Yes | Extraction configuration |

**Returns:** `ExtractionResult`

**Errors:** Returns `Err(Error)`.


---

### run_pipeline_sync()

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

```rust
pub fn run_pipeline_sync(doc: InternalDocument, config: ExtractionConfig) -> Result<ExtractionResult, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `doc` | `InternalDocument` | Yes | The internal document produced by the extractor |
| `config` | `ExtractionConfig` | Yes | Extraction configuration |

**Returns:** `ExtractionResult`

**Errors:** Returns `Err(Error)`.


---

### is_page_text_blank()

Determine if a page's text content indicates a blank page.

A page is blank if it has fewer than `MIN_NON_WHITESPACE_CHARS` non-whitespace characters.

**Returns:**

`true` if the page is considered blank, `false` otherwise

**Signature:**

```rust
pub fn is_page_text_blank(text: &str) -> bool
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `text` | `String` | Yes | The extracted text content of the page |

**Returns:** `bool`


---

### resolve_relationships()

Resolve `RelationshipTarget.Key` entries to `RelationshipTarget.Index`.

Builds an anchor index from elements with non-`None` anchors, then resolves
each key-based relationship target. Unresolvable keys are logged and skipped
(the relationship is left as `Key` — it will be excluded from the final
`DocumentStructure` relationships).

**Signature:**

```rust
pub fn resolve_relationships(doc: InternalDocument)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `doc` | `InternalDocument` | Yes | The internal document |

**Returns:** `()`


---

### derive_document_structure()

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

```rust
pub fn derive_document_structure(doc: InternalDocument) -> DocumentStructure
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `doc` | `InternalDocument` | Yes | The internal document |

**Returns:** `DocumentStructure`


---

### derive_extraction_result()

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

```rust
pub fn derive_extraction_result(doc: InternalDocument, include_document_structure: bool, output_format: OutputFormat) -> ExtractionResult
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `doc` | `InternalDocument` | Yes | The internal document |
| `include_document_structure` | `bool` | Yes | The include document structure |
| `output_format` | `OutputFormat` | Yes | The output format |

**Returns:** `ExtractionResult`


---

### parse_json()

**Signature:**

```rust
pub fn parse_json(data: &[u8], config: Option<JsonExtractionConfig>) -> Result<StructuredDataResult, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `data` | `Vec<u8>` | Yes | The data |
| `config` | `Option<JsonExtractionConfig>` | No | The configuration options |

**Returns:** `StructuredDataResult`

**Errors:** Returns `Err(Error)`.


---

### parse_jsonl()

Parse JSONL (newline-delimited JSON) into a structured data result.

Each non-empty line is parsed as an independent JSON value. Blank lines
and whitespace-only lines are skipped. The output is a pretty-printed
JSON array of all parsed objects.

**Errors:**

Returns an error if any line contains invalid JSON (with 1-based line number)
or if the input is not valid UTF-8.

**Signature:**

```rust
pub fn parse_jsonl(data: &[u8], config: Option<JsonExtractionConfig>) -> Result<StructuredDataResult, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `data` | `Vec<u8>` | Yes | The data |
| `config` | `Option<JsonExtractionConfig>` | No | The configuration options |

**Returns:** `StructuredDataResult`

**Errors:** Returns `Err(Error)`.


---

### parse_yaml()

**Signature:**

```rust
pub fn parse_yaml(data: &[u8]) -> Result<StructuredDataResult, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `data` | `Vec<u8>` | Yes | The data |

**Returns:** `StructuredDataResult`

**Errors:** Returns `Err(Error)`.


---

### parse_toml()

**Signature:**

```rust
pub fn parse_toml(data: &[u8]) -> Result<StructuredDataResult, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `data` | `Vec<u8>` | Yes | The data |

**Returns:** `StructuredDataResult`

**Errors:** Returns `Err(Error)`.


---

### parse_text()

**Signature:**

```rust
pub fn parse_text(text_bytes: &[u8], is_markdown: bool) -> Result<TextExtractionResult, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `text_bytes` | `Vec<u8>` | Yes | The text bytes |
| `is_markdown` | `bool` | Yes | The is markdown |

**Returns:** `TextExtractionResult`

**Errors:** Returns `Err(Error)`.


---

### transform_extraction_result_to_elements()

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

```rust
pub fn transform_extraction_result_to_elements(result: ExtractionResult) -> Vec<Element>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `result` | `ExtractionResult` | Yes | Reference to the ExtractionResult to transform |

**Returns:** `Vec<Element>`


---

### parse_body_text()

Parse a raw (possibly compressed) BodyText/SectionN stream.

Returns the list of sections found. Each section contains zero or more
paragraphs that carry the plain-text content.

**Signature:**

```rust
pub fn parse_body_text(data: &[u8], is_compressed: bool) -> Result<Vec<Section>, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `data` | `Vec<u8>` | Yes | The data |
| `is_compressed` | `bool` | Yes | The is compressed |

**Returns:** `Vec<Section>`

**Errors:** Returns `Err(Error)`.


---

### decompress_stream()

Decompress a raw-deflate stream from an HWP section.

HWP 5.0 compresses sections with raw deflate (no zlib header). Falls back
to zlib if raw deflate fails, and returns the data as-is if both fail.

**Signature:**

```rust
pub fn decompress_stream(data: &[u8]) -> Result<Vec<u8>, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `data` | `Vec<u8>` | Yes | The data |

**Returns:** `Vec<u8>`

**Errors:** Returns `Err(Error)`.


---

### extract_hwp_text()

Extract all plain text from an HWP 5.0 document given its raw bytes.

**Errors:**

Returns `HwpError` if the bytes do not form a valid HWP 5.0 compound file,
if the document is password-encrypted, or if a critical parsing step fails.

**Signature:**

```rust
pub fn extract_hwp_text(bytes: &[u8]) -> Result<String, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `bytes` | `Vec<u8>` | Yes | The bytes |

**Returns:** `String`

**Errors:** Returns `Err(Error)`.


---

### load_image_for_ocr()

Load image bytes for OCR, with JPEG 2000 and JBIG2 fallback support.

The standard `image` crate does not support JPEG 2000 or JBIG2 formats.
This function detects these formats by magic bytes and uses `hayro-jpeg2000`
/ `hayro-jbig2` for decoding, falling back to the standard `image` crate
for all other formats.

**Signature:**

```rust
pub fn load_image_for_ocr(image_bytes: &[u8]) -> Result<DynamicImage, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `image_bytes` | `Vec<u8>` | Yes | The image bytes |

**Returns:** `DynamicImage`

**Errors:** Returns `Err(Error)`.


---

### extract_image_metadata()

Extract metadata from image bytes.

Extracts dimensions, format, and EXIF data from the image.
Attempts to decode using the standard image crate first, then falls back to
pure Rust JP2 box parsing for JPEG 2000 formats if the standard decoder fails.

**Signature:**

```rust
pub fn extract_image_metadata(bytes: &[u8]) -> Result<ImageMetadata, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `bytes` | `Vec<u8>` | Yes | The bytes |

**Returns:** `ImageMetadata`

**Errors:** Returns `Err(Error)`.


---

### extract_text_from_image_with_ocr()

Extract text from image bytes using OCR with optional page tracking for multi-frame TIFFs.

This function:
- Detects if the image is a multi-frame TIFF
- For multi-frame TIFFs with PageConfig enabled, iterates frames and tracks boundaries
- For single-frame images or when page tracking is disabled, runs OCR on the whole image
- Returns (content, boundaries, page_contents) tuple

**Returns:**
ImageOcrResult with content and optional boundaries for pagination

**Signature:**

```rust
pub fn extract_text_from_image_with_ocr(bytes: &[u8], mime_type: &str, ocr_result: &str, page_config: Option<PageConfig>) -> Result<ImageOcrResult, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `bytes` | `Vec<u8>` | Yes | Image file bytes |
| `mime_type` | `String` | Yes | MIME type (e.g., "image/tiff") |
| `ocr_result` | `String` | Yes | OCR backend result containing the text |
| `page_config` | `Option<PageConfig>` | No | Optional page configuration for boundary tracking |

**Returns:** `ImageOcrResult`

**Errors:** Returns `Err(Error)`.


---

### estimate_content_capacity()

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

```rust
pub fn estimate_content_capacity(file_size: u64, format: &str) -> usize
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `file_size` | `u64` | Yes | The size of the original file in bytes |
| `format` | `String` | Yes | The file format/extension (e.g., "txt", "html", "docx", "xlsx", "pptx") |

**Returns:** `usize`


---

### estimate_html_markdown_capacity()

Estimate capacity for HTML to Markdown conversion.

HTML documents typically convert to Markdown with 60-70% of the original size.
This function estimates capacity specifically for HTML→Markdown conversion.

**Returns:**

An estimated capacity for the Markdown output

**Signature:**

```rust
pub fn estimate_html_markdown_capacity(html_size: u64) -> usize
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `html_size` | `u64` | Yes | The size of the HTML file in bytes |

**Returns:** `usize`


---

### estimate_spreadsheet_capacity()

Estimate capacity for cell extraction from spreadsheets.

When extracting cell data from Excel/ODS files, the extracted cells are typically
40% of the compressed file size (since the file is ZIP-compressed).

**Returns:**

An estimated capacity for cell value accumulation

**Signature:**

```rust
pub fn estimate_spreadsheet_capacity(file_size: u64) -> usize
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `file_size` | `u64` | Yes | Size of the spreadsheet file (XLSX, ODS, etc.) |

**Returns:** `usize`


---

### estimate_presentation_capacity()

Estimate capacity for slide content extraction from presentations.

PPTX files when extracted have slide content at approximately 35% of the file size.
This accounts for XML overhead, compression, and embedded assets.

**Returns:**

An estimated capacity for slide content accumulation

**Signature:**

```rust
pub fn estimate_presentation_capacity(file_size: u64) -> usize
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `file_size` | `u64` | Yes | Size of the PPTX file in bytes |

**Returns:** `usize`


---

### estimate_table_markdown_capacity()

Estimate capacity for markdown table generation.

Markdown tables have predictable size: ~12 bytes per cell on average
(accounting for separators, pipes, padding, and cell content).

**Returns:**

An estimated capacity for the markdown table output

**Signature:**

```rust
pub fn estimate_table_markdown_capacity(row_count: usize, col_count: usize) -> usize
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `row_count` | `usize` | Yes | Number of rows in the table |
| `col_count` | `usize` | Yes | Number of columns in the table |

**Returns:** `usize`


---

### parse_eml_content()

Parse .eml file content (RFC822 format)

**Signature:**

```rust
pub fn parse_eml_content(data: &[u8]) -> Result<EmailExtractionResult, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `data` | `Vec<u8>` | Yes | The data |

**Returns:** `EmailExtractionResult`

**Errors:** Returns `Err(Error)`.


---

### parse_msg_content()

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

```rust
pub fn parse_msg_content(data: &[u8], fallback_codepage: Option<u32>) -> Result<EmailExtractionResult, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `data` | `Vec<u8>` | Yes | The data |
| `fallback_codepage` | `Option<u32>` | No | The fallback codepage |

**Returns:** `EmailExtractionResult`

**Errors:** Returns `Err(Error)`.


---

### extract_email_content()

Extract email content from either .eml or .msg format

**Signature:**

```rust
pub fn extract_email_content(data: &[u8], mime_type: &str, fallback_codepage: Option<u32>) -> Result<EmailExtractionResult, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `data` | `Vec<u8>` | Yes | The data |
| `mime_type` | `String` | Yes | The mime type |
| `fallback_codepage` | `Option<u32>` | No | The fallback codepage |

**Returns:** `EmailExtractionResult`

**Errors:** Returns `Err(Error)`.


---

### build_email_text_output()

Build text output from email extraction result

**Signature:**

```rust
pub fn build_email_text_output(result: EmailExtractionResult) -> String
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `result` | `EmailExtractionResult` | Yes | The email extraction result |

**Returns:** `String`


---

### extract_pst_messages()

Extract all email messages from a PST file.

Opens the PST file and traverses the full folder hierarchy, extracting
every message including subject, sender, recipients, and body text.

**Returns:**

A vector of `EmailExtractionResult`, one per message found.

**Errors:**

Returns an error if the PST data cannot be written to a temporary file,
or if the PST format is invalid.

**Signature:**

```rust
pub fn extract_pst_messages(pst_data: &[u8]) -> Result<VecEmailExtractionResultVecProcessingWarning, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pst_data` | `Vec<u8>` | Yes | Raw bytes of the PST file |

**Returns:** `VecEmailExtractionResultVecProcessingWarning`

**Errors:** Returns `Err(Error)`.


---

### read_excel_file()

**Signature:**

```rust
pub fn read_excel_file(file_path: &str) -> Result<ExcelWorkbook, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `file_path` | `String` | Yes | Path to the file |

**Returns:** `ExcelWorkbook`

**Errors:** Returns `Err(Error)`.


---

### read_excel_bytes()

**Signature:**

```rust
pub fn read_excel_bytes(data: &[u8], file_extension: &str) -> Result<ExcelWorkbook, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `data` | `Vec<u8>` | Yes | The data |
| `file_extension` | `String` | Yes | The file extension |

**Returns:** `ExcelWorkbook`

**Errors:** Returns `Err(Error)`.


---

### excel_to_text()

Convert an Excel workbook to plain text (space-separated cells, one row per line).

Each sheet is separated by a blank line. Sheet names are included as headers.
This produces text suitable for quality scoring against ground truth.

**Signature:**

```rust
pub fn excel_to_text(workbook: ExcelWorkbook) -> String
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `workbook` | `ExcelWorkbook` | Yes | The excel workbook |

**Returns:** `String`


---

### excel_to_markdown()

**Signature:**

```rust
pub fn excel_to_markdown(workbook: ExcelWorkbook) -> String
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `workbook` | `ExcelWorkbook` | Yes | The excel workbook |

**Returns:** `String`


---

### extract_doc_text()

Extract text from DOC bytes.

Parses the OLE/CFB compound document, reads the FIB (File Information Block),
and extracts text from the piece table.

**Signature:**

```rust
pub fn extract_doc_text(content: &[u8]) -> Result<DocExtractionResult, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `content` | `Vec<u8>` | Yes | The content to process |

**Returns:** `DocExtractionResult`

**Errors:** Returns `Err(Error)`.


---

### parse_drawing()

Parse a drawing object starting after the `<w:drawing>` Start event.

This function reads events until it encounters the closing `</w:drawing>` tag,
parsing the drawing type (inline or anchored), extent, properties, and image references.

**Signature:**

```rust
pub fn parse_drawing(reader: Reader) -> Drawing
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `reader` | `Reader` | Yes | The reader |

**Returns:** `Drawing`


---

### collect_and_convert_omath_para()

Collect an `m:oMathPara` subtree and convert to LaTeX (display math).
The reader should be positioned right after the `<m:oMathPara>` start tag.

**Signature:**

```rust
pub fn collect_and_convert_omath_para(reader: Reader) -> String
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `reader` | `Reader` | Yes | The reader |

**Returns:** `String`


---

### collect_and_convert_omath()

Collect an `m:oMath` subtree and convert to LaTeX (inline math).
The reader should be positioned right after the `<m:oMath>` start tag.

**Signature:**

```rust
pub fn collect_and_convert_omath(reader: Reader) -> String
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `reader` | `Reader` | Yes | The reader |

**Returns:** `String`


---

### parse_document()

Parse a DOCX document from bytes and return the structured document.

**Signature:**

```rust
pub fn parse_document(bytes: &[u8]) -> Result<Document, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `bytes` | `Vec<u8>` | Yes | The bytes |

**Returns:** `Document`

**Errors:** Returns `Err(Error)`.


---

### extract_text_from_bytes()

Extract text from DOCX bytes.

**Signature:**

```rust
pub fn extract_text_from_bytes(bytes: &[u8]) -> Result<String, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `bytes` | `Vec<u8>` | Yes | The bytes |

**Returns:** `String`

**Errors:** Returns `Err(Error)`.


---

### parse_section_properties()

Parse a `w:sectPr` XML element (roxmltree node) into `SectionProperties`.

**Signature:**

```rust
pub fn parse_section_properties(node: Node) -> SectionProperties
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `node` | `Node` | Yes | The node |

**Returns:** `SectionProperties`


---

### parse_section_properties_streaming()

Parse section properties from a quick_xml event stream.

Reads events from the reader until `</w:sectPr>` is encountered,
extracting the same properties as the roxmltree parser.

**Important:** This function advances the reader past the closing `</w:sectPr>` tag.
The caller must not attempt to process the `w:sectPr` end event again.

**Signature:**

```rust
pub fn parse_section_properties_streaming(reader: Reader) -> SectionProperties
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `reader` | `Reader` | Yes | The reader |

**Returns:** `SectionProperties`


---

### parse_styles_xml()

Parse `word/styles.xml` content into a `StyleCatalog`.

Uses `roxmltree` for tree-based XML parsing, consistent with the
office metadata parsing approach used elsewhere in the codebase.

**Signature:**

```rust
pub fn parse_styles_xml(xml: &str) -> Result<StyleCatalog, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `xml` | `String` | Yes | The xml |

**Returns:** `StyleCatalog`

**Errors:** Returns `Err(Error)`.


---

### parse_table_properties()

Parse table-level properties from streaming XML reader.

Expects the reader to be positioned just after the `<w:tblPr>` start tag.
Reads all child elements until the matching `</w:tblPr>` end tag.

**Signature:**

```rust
pub fn parse_table_properties(reader: Reader) -> TableProperties
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `reader` | `Reader` | Yes | The reader |

**Returns:** `TableProperties`


---

### parse_row_properties()

Parse row-level properties from streaming XML reader.

Expects the reader to be positioned just after the `<w:trPr>` start tag.

**Signature:**

```rust
pub fn parse_row_properties(reader: Reader) -> RowProperties
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `reader` | `Reader` | Yes | The reader |

**Returns:** `RowProperties`


---

### parse_cell_properties()

Parse cell-level properties from streaming XML reader.

Expects the reader to be positioned just after the `<w:tcPr>` start tag.

**Signature:**

```rust
pub fn parse_cell_properties(reader: Reader) -> CellProperties
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `reader` | `Reader` | Yes | The reader |

**Returns:** `CellProperties`


---

### parse_table_grid()

Parse table grid (column widths) from streaming XML reader.

Expects the reader to be positioned just after the `<w:tblGrid>` start tag.

**Signature:**

```rust
pub fn parse_table_grid(reader: Reader) -> TableGrid
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `reader` | `Reader` | Yes | The reader |

**Returns:** `TableGrid`


---

### parse_theme_xml()

Parse `word/theme/theme1.xml` content into a `Theme`.

Uses `roxmltree` for tree-based XML parsing of DrawingML theme elements.

**Returns:**
* `Ok(Theme)` - The parsed theme
* `Err(KreuzbergError)` - If parsing fails

**Signature:**

```rust
pub fn parse_theme_xml(xml: &str) -> Result<Theme, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `xml` | `String` | Yes | The theme XML content as a string |

**Returns:** `Theme`

**Errors:** Returns `Err(Error)`.


---

### extract_text()

Extract text from DOCX bytes.

**Signature:**

```rust
pub fn extract_text(bytes: &[u8]) -> Result<String, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `bytes` | `Vec<u8>` | Yes | The bytes |

**Returns:** `String`

**Errors:** Returns `Err(Error)`.


---

### extract_text_with_page_breaks()

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

```rust
pub fn extract_text_with_page_breaks(bytes: &[u8]) -> Result<StringOptionVecPageBoundary, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `bytes` | `Vec<u8>` | Yes | The DOCX file contents as bytes |

**Returns:** `StringOptionVecPageBoundary`

**Errors:** Returns `Err(Error)`.


---

### detect_page_breaks_from_docx()

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

```rust
pub fn detect_page_breaks_from_docx(bytes: &[u8]) -> Result<Option<Vec<PageBoundary>>, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `bytes` | `Vec<u8>` | Yes | The DOCX file contents (ZIP archive) |

**Returns:** `Option<Vec<PageBoundary>>`

**Errors:** Returns `Err(Error)`.


---

### extract_ooxml_embedded_objects()

Extract embedded objects from an OOXML ZIP archive and recursively process them.

Scans the given `embeddings_prefix` directory (e.g. `word/embeddings/` or
`ppt/embeddings/`) inside the ZIP archive for embedded files. Known formats
(.xlsx, .pdf, .docx, .pptx, etc.) are recursively extracted. OLE compound
files (oleObject*.bin) are skipped with a warning unless their format can be
identified.

Returns `(children, warnings)` suitable for attaching to `InternalDocument`.

**Signature:**

```rust
pub async fn extract_ooxml_embedded_objects(zip_bytes: &[u8], embeddings_prefix: &str, source_label: &str, config: ExtractionConfig) -> VecArchiveEntryVecProcessingWarning
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `zip_bytes` | `Vec<u8>` | Yes | The zip bytes |
| `embeddings_prefix` | `String` | Yes | The embeddings prefix |
| `source_label` | `String` | Yes | The source label |
| `config` | `ExtractionConfig` | Yes | The configuration options |

**Returns:** `VecArchiveEntryVecProcessingWarning`


---

### detect_image_format()

Detect image format from raw bytes using magic byte signatures.

Returns a format string like "jpeg", "png", etc. Used by both DOCX and PPTX extractors.

**Signature:**

```rust
pub fn detect_image_format(data: &[u8]) -> Str
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `data` | `Vec<u8>` | Yes | The data |

**Returns:** `Str`


---

### process_images_with_ocr()

Process extracted images with OCR if configured.

For each image, spawns a blocking OCR task and stores the result
in `image.ocr_result`. If OCR is not configured or fails for an
individual image, that image's `ocr_result` remains `None`.

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

```rust
pub async fn process_images_with_ocr(images: Vec<ExtractedImage>, config: ExtractionConfig) -> Result<Vec<ExtractedImage>, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `images` | `Vec<ExtractedImage>` | Yes | The images |
| `config` | `ExtractionConfig` | Yes | The configuration options |

**Returns:** `Vec<ExtractedImage>`

**Errors:** Returns `Err(Error)`.


---

### extract_ppt_text()

Extract text from PPT bytes.

Parses the OLE/CFB compound document, reads the "PowerPoint Document" stream,
and extracts text from TextCharsAtom and TextBytesAtom records.

When `include_master_slides` is `true`, master slide content (placeholder text
like "Click to edit Master title style") is included instead of being skipped.

**Signature:**

```rust
pub fn extract_ppt_text(content: &[u8]) -> Result<PptExtractionResult, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `content` | `Vec<u8>` | Yes | The content to process |

**Returns:** `PptExtractionResult`

**Errors:** Returns `Err(Error)`.


---

### extract_ppt_text_with_options()

Extract text from PPT bytes with configurable master slide inclusion.

When `include_master_slides` is `true`, `RT_MAIN_MASTER` containers are not
skipped, so master slide placeholder text is included in the output.

**Signature:**

```rust
pub fn extract_ppt_text_with_options(content: &[u8], include_master_slides: bool) -> Result<PptExtractionResult, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `content` | `Vec<u8>` | Yes | The content to process |
| `include_master_slides` | `bool` | Yes | The include master slides |

**Returns:** `PptExtractionResult`

**Errors:** Returns `Err(Error)`.


---

### extract_pptx_from_path()

Extract PPTX content from a file path.

**Returns:**

A `PptxExtractionResult` containing extracted content, metadata, and images.

**Signature:**

```rust
pub fn extract_pptx_from_path(path: &str, options: PptxExtractionOptions) -> Result<PptxExtractionResult, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `path` | `String` | Yes | Path to the PPTX file |
| `options` | `PptxExtractionOptions` | Yes | Extraction options controlling image extraction, formatting, etc. |

**Returns:** `PptxExtractionResult`

**Errors:** Returns `Err(Error)`.


---

### extract_pptx_from_bytes()

Extract PPTX content from a byte buffer.

**Returns:**

A `PptxExtractionResult` containing extracted content, metadata, and images.

**Signature:**

```rust
pub fn extract_pptx_from_bytes(data: &[u8], options: PptxExtractionOptions) -> Result<PptxExtractionResult, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `data` | `Vec<u8>` | Yes | Raw PPTX file bytes |
| `options` | `PptxExtractionOptions` | Yes | Extraction options controlling image extraction, formatting, etc. |

**Returns:** `PptxExtractionResult`

**Errors:** Returns `Err(Error)`.


---

### parse_xml_svg()

Parse XML with optional SVG mode.

In SVG mode, only text from SVG text-bearing elements (`<text>`, `<tspan>`,
`<title>`, `<desc>`, `<textPath>`) is extracted, without element name prefixes.
Attribute values are also omitted in SVG mode.

**Signature:**

```rust
pub fn parse_xml_svg(xml_bytes: &[u8], preserve_whitespace: bool) -> Result<XmlExtractionResult, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `xml_bytes` | `Vec<u8>` | Yes | The xml bytes |
| `preserve_whitespace` | `bool` | Yes | The preserve whitespace |

**Returns:** `XmlExtractionResult`

**Errors:** Returns `Err(Error)`.


---

### parse_xml()

**Signature:**

```rust
pub fn parse_xml(xml_bytes: &[u8], preserve_whitespace: bool) -> Result<XmlExtractionResult, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `xml_bytes` | `Vec<u8>` | Yes | The xml bytes |
| `preserve_whitespace` | `bool` | Yes | The preserve whitespace |

**Returns:** `XmlExtractionResult`

**Errors:** Returns `Err(Error)`.


---

### cells_to_text()

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

```rust
pub fn cells_to_text(cells: Vec<Vec<String>>) -> String
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `cells` | `Vec<Vec<String>>` | Yes | A slice of vectors representing table rows, where each inner vector contains cell values |

**Returns:** `String`


---

### cells_to_markdown()

**Signature:**

```rust
pub fn cells_to_markdown(cells: Vec<Vec<String>>) -> String
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `cells` | `Vec<Vec<String>>` | Yes | The cells |

**Returns:** `String`


---

### parse_jotdown_attributes()

Parse jotdown attributes into our Attributes representation.

Converts jotdown's internal attribute representation to Kreuzberg's
standardized Attributes struct, handling IDs, classes, and key-value pairs.

**Signature:**

```rust
pub fn parse_jotdown_attributes(attrs: Attributes) -> Attributes
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `attrs` | `Attributes` | Yes | The attributes |

**Returns:** `Attributes`


---

### render_attributes()

Render attributes to djot attribute syntax.

Converts Kreuzberg's Attributes struct back to djot attribute syntax:
{.class #id key="value"}

**Signature:**

```rust
pub fn render_attributes(attrs: Attributes) -> String
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `attrs` | `Attributes` | Yes | The attributes |

**Returns:** `String`


---

### djot_content_to_djot()

Convert DjotContent back to djot markup.

This function takes a `DjotContent` structure and generates valid djot markup
from it, preserving:
- Block structure (headings, code blocks, lists, blockquotes, etc.)
- Inline formatting (strong, emphasis, highlight, subscript, superscript, etc.)
- Attributes where present ({.class #id key="value"})

**Returns:**

A String containing valid djot markup

**Signature:**

```rust
pub fn djot_content_to_djot(content: DjotContent) -> String
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `content` | `DjotContent` | Yes | The DjotContent to convert |

**Returns:** `String`


---

### extraction_result_to_djot()

Convert any ExtractionResult to djot format.

This function converts an `ExtractionResult` to djot markup:
- If `djot_content` is `Some`, uses `djot_content_to_djot` for full fidelity conversion
- Otherwise, wraps the plain text content in paragraphs

**Returns:**

A `Result` containing the djot markup string

**Signature:**

```rust
pub fn extraction_result_to_djot(result: ExtractionResult) -> Result<String, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `result` | `ExtractionResult` | Yes | The ExtractionResult to convert |

**Returns:** `String`

**Errors:** Returns `Err(Error)`.


---

### djot_to_html()

Render djot content to HTML.

This function takes djot source text and renders it to HTML using jotdown's
built-in HTML renderer.

**Returns:**

A `Result` containing the rendered HTML string

**Signature:**

```rust
pub fn djot_to_html(djot_source: &str) -> Result<String, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `djot_source` | `String` | Yes | The djot markup text to render |

**Returns:** `String`

**Errors:** Returns `Err(Error)`.


---

### render_block_to_djot()

Render a single block to djot markup.

**Signature:**

```rust
pub fn render_block_to_djot(output: &str, block: FormattedBlock, indent_level: usize)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `output` | `String` | Yes | The output destination |
| `block` | `FormattedBlock` | Yes | The formatted block |
| `indent_level` | `usize` | Yes | The indent level |

**Returns:** `()`


---

### render_list_item()

Render a list item with the given marker.

**Signature:**

```rust
pub fn render_list_item(output: &str, item: FormattedBlock, indent: &str, marker: &str)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `output` | `String` | Yes | The output destination |
| `item` | `FormattedBlock` | Yes | The formatted block |
| `indent` | `String` | Yes | The indent |
| `marker` | `String` | Yes | The marker |

**Returns:** `()`


---

### render_inline_content()

Render inline content to djot markup.

**Signature:**

```rust
pub fn render_inline_content(output: &str, elements: Vec<InlineElement>)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `output` | `String` | Yes | The output destination |
| `elements` | `Vec<InlineElement>` | Yes | The elements |

**Returns:** `()`


---

### extract_frontmatter()

Extract YAML frontmatter from document content.

Frontmatter is expected to be delimited by `---` or `...` at the start of the document.
This implementation properly handles edge cases:
- `---` appearing within YAML strings or arrays
- Both `---` and `...` as end delimiters (YAML spec compliant)
- Multiline YAML values containing dashes

Returns a tuple of (parsed YAML value, remaining content after frontmatter).

**Signature:**

```rust
pub fn extract_frontmatter(content: &str) -> OptionYamlValueString
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `content` | `String` | Yes | The content to process |

**Returns:** `OptionYamlValueString`


---

### extract_metadata_from_yaml()

Extract metadata from YAML frontmatter.

Extracts the following YAML fields into Kreuzberg metadata:
- **Standard fields**: title, author, date, description (as subject)
- **Extended fields**: abstract, subject, category, tags, language, version
- **Array fields** (keywords, tags): stored as `Vec<String>` in typed fields

**Returns:**

A `Metadata` struct populated with extracted fields

**Signature:**

```rust
pub fn extract_metadata_from_yaml(yaml: YamlValue) -> Metadata
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `yaml` | `YamlValue` | Yes | The parsed YAML value from frontmatter |

**Returns:** `Metadata`


---

### extract_title_from_content()

Extract first heading as title from content.

Searches for the first level-1 heading (# Title) in the content
and returns it as a potential title if no title was found in frontmatter.

**Returns:**

Some(title) if a heading is found, None otherwise

**Signature:**

```rust
pub fn extract_title_from_content(content: &str) -> Option<String>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `content` | `String` | Yes | The document content to search |

**Returns:** `Option<String>`


---

### collect_iwa_paths()

Collects all .iwa file paths from a ZIP archive.

Opens the ZIP from `content`, iterates every entry, and returns the names of
all entries whose path ends with `.iwa`. Entries that cannot be read are
silently skipped (consistent with the per-extractor `filter_map` pattern).

**Signature:**

```rust
pub fn collect_iwa_paths(content: &[u8]) -> Result<Vec<String>, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `content` | `Vec<u8>` | Yes | The content to process |

**Returns:** `Vec<String>`

**Errors:** Returns `Err(Error)`.


---

### read_iwa_file()

Read and Snappy-decompress a single `.iwa` file from the ZIP archive.

Apple IWA files use a custom framing format:
Each block in the file is: `[type: u8][length: u24 LE][payload: length bytes]`
- type `0x00`: Snappy-compressed block → decompress payload with raw Snappy
- type `0x01`: Uncompressed block → use payload as-is

Multiple blocks are concatenated to form the decompressed IWA stream.

**Signature:**

```rust
pub fn read_iwa_file(content: &[u8], path: &str) -> Result<Vec<u8>, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `content` | `Vec<u8>` | Yes | The content to process |
| `path` | `String` | Yes | Path to the file |

**Returns:** `Vec<u8>`

**Errors:** Returns `Err(Error)`.


---

### decode_iwa_stream()

Decode an Apple IWA byte stream into the raw protobuf payload.

IWA framing: each block = 1 byte type + 3 bytes LE length + N bytes payload
- type 0x00 → Snappy-compressed, decompress with `snap.raw.Decoder`
- type 0x01 → Uncompressed, use as-is

**Signature:**

```rust
pub fn decode_iwa_stream(data: &[u8]) -> Result<Vec<u8>, String>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `data` | `Vec<u8>` | Yes | The data |

**Returns:** `Vec<u8>`

**Errors:** Returns `Err(String)`.


---

### extract_text_from_proto()

Extract all UTF-8 text strings from a raw protobuf byte slice.

This uses a simple wire-format scanner without a full schema:
- Field type 2 (length-delimited) with a valid UTF-8 payload of ≥3 bytes is
  treated as a text string candidate.
- We skip binary blobs (non-UTF-8) and very short noise strings.

This approach avoids the need for `prost-build` and generated proto code while
still extracting human-readable text reliably from iWork documents.

**Signature:**

```rust
pub fn extract_text_from_proto(data: &[u8]) -> Vec<String>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `data` | `Vec<u8>` | Yes | The data |

**Returns:** `Vec<String>`


---

### extract_text_from_iwa_files()

Extract all text from an iWork ZIP archive by reading specified IWA entries.

`iwa_paths` should list the IWA file paths to read (e.g. `["Index/Document.iwa"]`).
Returns a flat joined string of all text found across all IWA files.

**Signature:**

```rust
pub fn extract_text_from_iwa_files(content: &[u8], iwa_paths: Vec<String>) -> Result<String, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `content` | `Vec<u8>` | Yes | The content to process |
| `iwa_paths` | `Vec<String>` | Yes | The iwa paths |

**Returns:** `String`

**Errors:** Returns `Err(Error)`.


---

### extract_metadata_from_zip()

Extract metadata from an iWork ZIP archive.

Attempts to read `Metadata/Properties.plist` and
`Metadata/BuildVersionHistory.plist` from the ZIP. These files are XML plists
containing authorship and creation information. If the files cannot be read
or parsed, an empty `Metadata` is returned.

**Signature:**

```rust
pub fn extract_metadata_from_zip(content: &[u8]) -> Metadata
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `content` | `Vec<u8>` | Yes | The content to process |

**Returns:** `Metadata`


---

### dedup_text()

Deduplicate a list of text strings while preserving order.
Adjacent duplicates and near-duplicates are removed.

**Signature:**

```rust
pub fn dedup_text(texts: Vec<String>) -> Vec<String>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `texts` | `Vec<String>` | Yes | The texts |

**Returns:** `Vec<String>`


---

### ensure_initialized()

Ensure built-in extractors are registered.

This function is called automatically on first extraction operation.
It's safe to call multiple times - registration only happens once,
unless the registry was cleared, in which case extractors are re-registered.

**Signature:**

```rust
pub fn ensure_initialized() -> Result<(), Error>
```

**Returns:** `()`

**Errors:** Returns `Err(Error)`.


---

### register_default_extractors()

Register all built-in extractors with the global registry.

This function should be called once at application startup to register
the default extractors (PlainText, Markdown, XML, etc.).

**Note:** This is called automatically on first extraction operation.
Explicit calling is optional.

**Signature:**

```rust
pub fn register_default_extractors() -> Result<(), Error>
```

**Returns:** `()`

**Errors:** Returns `Err(Error)`.


---

### extract_panic_message()

Extracts a human-readable message from a panic payload.

Attempts to downcast the panic payload to common types (String, &str)
to extract a meaningful error message.

Message is truncated to 4KB to prevent DoS attacks via extremely large panic messages.

**Returns:**

A string representation of the panic message (truncated if necessary)

**Signature:**

```rust
pub fn extract_panic_message(panic_info: Any) -> String
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `panic_info` | `Any` | Yes | The panic payload from catch_unwind |

**Returns:** `String`


---

### get_ocr_backend_registry()

Get the global OCR backend registry.

**Signature:**

```rust
pub fn get_ocr_backend_registry() -> RwLock
```

**Returns:** `RwLock`


---

### get_document_extractor_registry()

Get the global document extractor registry.

**Signature:**

```rust
pub fn get_document_extractor_registry() -> RwLock
```

**Returns:** `RwLock`


---

### get_post_processor_registry()

Get the global post-processor registry.

**Signature:**

```rust
pub fn get_post_processor_registry() -> RwLock
```

**Returns:** `RwLock`


---

### get_validator_registry()

Get the global validator registry.

**Signature:**

```rust
pub fn get_validator_registry() -> RwLock
```

**Returns:** `RwLock`


---

### get_renderer_registry()

Get the global renderer registry.

**Signature:**

```rust
pub fn get_renderer_registry() -> RwLock
```

**Returns:** `RwLock`


---

### validate_plugins_at_startup()

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

```rust
pub fn validate_plugins_at_startup() -> Result<PluginHealthStatus, Error>
```

**Returns:** `PluginHealthStatus`

**Errors:** Returns `Err(Error)`.


---

### sanitize_filename()

Sanitize a file path to return only the filename (no directory).

Prevents PII from appearing in traces.

**Signature:**

```rust
pub fn sanitize_filename(path: PathBuf) -> String
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `path` | `PathBuf` | Yes | Path to the file |

**Returns:** `String`


---

### get_metrics()

Get the global extraction metrics, initialising on first call.

Uses the global `opentelemetry.global.meter` to create instruments.

**Signature:**

```rust
pub fn get_metrics() -> ExtractionMetrics
```

**Returns:** `ExtractionMetrics`


---

### record_error_on_current_span()

Record an error on the current span using semantic conventions.

Sets `otel.status_code = "ERROR"`, `kreuzberg.error.type`, and `error.message`.

**Signature:**

```rust
pub fn record_error_on_current_span(error: KreuzbergError)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `error` | `KreuzbergError` | Yes | The kreuzberg error |

**Returns:** `()`


---

### record_success_on_current_span()

Record extraction success on the current span.

**Signature:**

```rust
pub fn record_success_on_current_span()
```

**Returns:** `()`


---

### sanitize_path()

Sanitize a file path to return only the filename.

Prevents PII (personally identifiable information) from appearing in
traces by only recording filenames instead of full paths.

**Signature:**

```rust
pub fn sanitize_path(path: PathBuf) -> String
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `path` | `PathBuf` | Yes | Path to the file |

**Returns:** `String`


---

### extractor_span()

Create an extractor-level span with semantic convention fields.

Returns a `tracing.Span` with all `kreuzberg.extractor.*` and
`kreuzberg.document.*` fields pre-allocated (set to `Empty` for
lazy recording).

**Signature:**

```rust
pub fn extractor_span(extractor_name: &str, mime_type: &str, size_bytes: usize) -> Span
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `extractor_name` | `String` | Yes | The extractor name |
| `mime_type` | `String` | Yes | The mime type |
| `size_bytes` | `usize` | Yes | The size bytes |

**Returns:** `Span`


---

### pipeline_stage_span()

Create a pipeline stage span.

**Signature:**

```rust
pub fn pipeline_stage_span(stage: &str) -> Span
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `stage` | `String` | Yes | The stage |

**Returns:** `Span`


---

### pipeline_processor_span()

Create a pipeline processor span.

**Signature:**

```rust
pub fn pipeline_processor_span(stage: &str, processor_name: &str) -> Span
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `stage` | `String` | Yes | The stage |
| `processor_name` | `String` | Yes | The processor name |

**Returns:** `Span`


---

### ocr_span()

Create an OCR operation span.

**Signature:**

```rust
pub fn ocr_span(backend: &str, language: &str) -> Span
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `backend` | `String` | Yes | The backend |
| `language` | `String` | Yes | The language |

**Returns:** `Span`


---

### model_inference_span()

Create a model inference span.

**Signature:**

```rust
pub fn model_inference_span(model_name: &str) -> Span
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `model_name` | `String` | Yes | The model name |

**Returns:** `Span`


---

### from_utf8()

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

```rust
pub fn from_utf8(bytes: &[u8]) -> Result<String, Utf8Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `bytes` | `Vec<u8>` | Yes | The byte slice to validate and convert |

**Returns:** `String`

**Errors:** Returns `Err(Utf8Error)`.


---

### string_from_utf8()

Validates and converts owned bytes to String using SIMD when available.

This function converts bytes to an owned String, validating UTF-8 using SIMD
when available. The caller's bytes are consumed to create the String.

**Returns:**

`Ok(String)` if the bytes are valid UTF-8, `Err(std.string.FromUtf8Error)` otherwise.

# Performance

When enabled, SIMD validation significantly reduces the time spent on validation,
especially for large text documents.

**Signature:**

```rust
pub fn string_from_utf8(bytes: &[u8]) -> Result<String, FromUtf8Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `bytes` | `Vec<u8>` | Yes | The byte vector to validate and convert |

**Returns:** `String`

**Errors:** Returns `Err(FromUtf8Error)`.


---

### is_valid_utf8()

Validates bytes as UTF-8 without conversion to string slice.

Returns `true` if the bytes represent valid UTF-8, `false` otherwise.
This is useful when you only need to check validity without constructing a string.

**Returns:**

`true` if valid UTF-8, `false` otherwise.

# Performance

This function is optimized for early exit on invalid sequences.

**Signature:**

```rust
pub fn is_valid_utf8(bytes: &[u8]) -> bool
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `bytes` | `Vec<u8>` | Yes | The byte slice to validate |

**Returns:** `bool`


---

### calculate_quality_score()

**Signature:**

```rust
pub fn calculate_quality_score(text: &str, metadata: Option<AHashMap>) -> f64
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `text` | `String` | Yes | The text |
| `metadata` | `Option<AHashMap>` | No | The a hash map |

**Returns:** `f64`


---

### clean_extracted_text()

**Signature:**

```rust
pub fn clean_extracted_text(text: &str) -> String
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `text` | `String` | Yes | The text |

**Returns:** `String`


---

### normalize_spaces()

**Signature:**

```rust
pub fn normalize_spaces(text: &str) -> String
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `text` | `String` | Yes | The text |

**Returns:** `String`


---

### reduce_tokens()

Reduces token count in text while preserving meaning and structure.

This function removes stopwords, redundancy, and applies compression techniques
based on the specified reduction level. Supports 64 languages with automatic
stopword removal and optional semantic clustering.

**Returns:**

Returns the reduced text with preserved structure (markdown, code blocks).

**Errors:**

Returns an error if the language hint is invalid or stopwords cannot be loaded.

**Signature:**

```rust
pub fn reduce_tokens(text: &str, config: TokenReductionConfig, language_hint: Option<String>) -> Result<String, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `text` | `String` | Yes | The input text to reduce |
| `config` | `TokenReductionConfig` | Yes | Configuration specifying reduction level and options |
| `language_hint` | `Option<String>` | No | Optional ISO 639-3 language code (e.g., "eng", "spa") |

**Returns:** `String`

**Errors:** Returns `Err(Error)`.


---

### batch_reduce_tokens()

Reduces token count for multiple texts efficiently using parallel processing.

This function processes multiple texts in parallel using Rayon, providing
significant performance improvements for batch operations. All texts use the
same configuration and language hint for consistency.

**Returns:**

Returns a vector of reduced texts in the same order as the input.

**Errors:**

Returns an error if the language hint is invalid or stopwords cannot be loaded.

**Signature:**

```rust
pub fn batch_reduce_tokens(texts: Vec<String>, config: TokenReductionConfig, language_hint: Option<String>) -> Result<Vec<String>, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `texts` | `Vec<String>` | Yes | Slice of text references to reduce |
| `config` | `TokenReductionConfig` | Yes | Configuration specifying reduction level and options |
| `language_hint` | `Option<String>` | No | Optional ISO 639-3 language code (e.g., "eng", "spa") |

**Returns:** `Vec<String>`

**Errors:** Returns `Err(Error)`.


---

### get_reduction_statistics()

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

```rust
pub fn get_reduction_statistics(original: &str, reduced: &str) -> F64F64UsizeUsizeUsizeUsize
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

```rust
pub fn bold(start: u32, end: u32) -> TextAnnotation
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `start` | `u32` | Yes | The start |
| `end` | `u32` | Yes | The end |

**Returns:** `TextAnnotation`


---

### italic()

Create an italic annotation for the given byte range.

**Signature:**

```rust
pub fn italic(start: u32, end: u32) -> TextAnnotation
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `start` | `u32` | Yes | The start |
| `end` | `u32` | Yes | The end |

**Returns:** `TextAnnotation`


---

### underline()

Create an underline annotation for the given byte range.

**Signature:**

```rust
pub fn underline(start: u32, end: u32) -> TextAnnotation
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `start` | `u32` | Yes | The start |
| `end` | `u32` | Yes | The end |

**Returns:** `TextAnnotation`


---

### link()

Create a link annotation for the given byte range.

**Signature:**

```rust
pub fn link(start: u32, end: u32, url: &str, title: Option<String>) -> TextAnnotation
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `start` | `u32` | Yes | The start |
| `end` | `u32` | Yes | The end |
| `url` | `String` | Yes | The URL to fetch |
| `title` | `Option<String>` | No | The title |

**Returns:** `TextAnnotation`


---

### code()

Create a code (inline) annotation for the given byte range.

**Signature:**

```rust
pub fn code(start: u32, end: u32) -> TextAnnotation
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `start` | `u32` | Yes | The start |
| `end` | `u32` | Yes | The end |

**Returns:** `TextAnnotation`


---

### strikethrough()

Create a strikethrough annotation for the given byte range.

**Signature:**

```rust
pub fn strikethrough(start: u32, end: u32) -> TextAnnotation
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `start` | `u32` | Yes | The start |
| `end` | `u32` | Yes | The end |

**Returns:** `TextAnnotation`


---

### subscript()

Create a subscript annotation for the given byte range.

**Signature:**

```rust
pub fn subscript(start: u32, end: u32) -> TextAnnotation
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `start` | `u32` | Yes | The start |
| `end` | `u32` | Yes | The end |

**Returns:** `TextAnnotation`


---

### superscript()

Create a superscript annotation for the given byte range.

**Signature:**

```rust
pub fn superscript(start: u32, end: u32) -> TextAnnotation
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `start` | `u32` | Yes | The start |
| `end` | `u32` | Yes | The end |

**Returns:** `TextAnnotation`


---

### font_size()

Create a font size annotation for the given byte range.

**Signature:**

```rust
pub fn font_size(start: u32, end: u32, value: &str) -> TextAnnotation
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `start` | `u32` | Yes | The start |
| `end` | `u32` | Yes | The end |
| `value` | `String` | Yes | The value |

**Returns:** `TextAnnotation`


---

### color()

Create a color annotation for the given byte range.

**Signature:**

```rust
pub fn color(start: u32, end: u32, value: &str) -> TextAnnotation
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `start` | `u32` | Yes | The start |
| `end` | `u32` | Yes | The end |
| `value` | `String` | Yes | The value |

**Returns:** `TextAnnotation`


---

### highlight()

Create a highlight annotation for the given byte range.

**Signature:**

```rust
pub fn highlight(start: u32, end: u32) -> TextAnnotation
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `start` | `u32` | Yes | The start |
| `end` | `u32` | Yes | The end |

**Returns:** `TextAnnotation`


---

### classify_uri()

Classify a URL string into the appropriate `UriKind`.

- `mailto:` → `Email`
- `#` prefix → `Anchor`
- everything else → `Hyperlink`

**Signature:**

```rust
pub fn classify_uri(url: &str) -> UriKind
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `url` | `String` | Yes | The URL to fetch |

**Returns:** `UriKind`


---

### safe_decode()

Decode raw bytes into UTF-8, using heuristics and fallback encodings when necessary.

The function prefers an explicit `encoding`, falls back to the cached guess, probes
an encoding detector, and finally tries a small curated list before returning a
mojibake-cleaned string.

**Signature:**

```rust
pub fn safe_decode(byte_data: &[u8], encoding: Option<String>) -> String
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `byte_data` | `Vec<u8>` | Yes | The byte data |
| `encoding` | `Option<String>` | No | The encoding |

**Returns:** `String`


---

### calculate_text_confidence()

Estimate how trustworthy a decoded string is on a 0.0–1.0 scale.

Scores close to 1.0 indicate mostly printable characters, whereas lower scores
point to mojibake, control characters, or suspicious character mixes.

**Signature:**

```rust
pub fn calculate_text_confidence(text: &str) -> f64
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `text` | `String` | Yes | The text |

**Returns:** `f64`


---

### fix_mojibake()

Strip control characters and replacement glyphs that typically arise from mojibake.

**Signature:**

```rust
pub fn fix_mojibake(text: &str) -> Str
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `text` | `String` | Yes | The text |

**Returns:** `Str`


---

### snake_to_camel()

Recursively convert snake_case keys in a JSON Value to camelCase.

This is used by language bindings (Node.js, Go, Java, C#, etc.) to provide
a consistent camelCase API for consumers even though the Rust core uses snake_case.

**Signature:**

```rust
pub fn snake_to_camel(val: Value) -> Value
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `val` | `Value` | Yes | The value |

**Returns:** `Value`


---

### camel_to_snake()

Recursively convert camelCase keys in a JSON Value to snake_case.

This is the inverse of `snake_to_camel`. Used by WASM bindings to accept
camelCase config from JavaScript while the Rust core expects snake_case.

**Signature:**

```rust
pub fn camel_to_snake(val: Value) -> Value
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `val` | `Value` | Yes | The value |

**Returns:** `Value`


---

### create_string_buffer_pool()

Create a pre-configured string buffer pool for batch processing.

**Returns:**

A pool configured for text accumulation with reasonable defaults.

**Signature:**

```rust
pub fn create_string_buffer_pool(pool_size: usize, buffer_capacity: usize) -> StringBufferPool
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pool_size` | `usize` | Yes | Maximum number of buffers to keep in the pool |
| `buffer_capacity` | `usize` | Yes | Initial capacity for each buffer in bytes |

**Returns:** `StringBufferPool`


---

### create_byte_buffer_pool()

Create a pre-configured byte buffer pool for batch processing.

**Returns:**

A pool configured for binary data handling with reasonable defaults.

**Signature:**

```rust
pub fn create_byte_buffer_pool(pool_size: usize, buffer_capacity: usize) -> ByteBufferPool
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pool_size` | `usize` | Yes | Maximum number of buffers to keep in the pool |
| `buffer_capacity` | `usize` | Yes | Initial capacity for each buffer in bytes |

**Returns:** `ByteBufferPool`


---

### estimate_pool_size()

Estimate optimal pool sizing based on file size and document type.

This function uses the file size and MIME type to estimate how many
buffers and what capacity they should have. The estimates are conservative
to avoid starving large document processing.

**Returns:**

A `PoolSizeHint` with recommended pool configuration

**Signature:**

```rust
pub fn estimate_pool_size(file_size: u64, mime_type: &str) -> PoolSizeHint
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `file_size` | `u64` | Yes | Size of the file in bytes |
| `mime_type` | `String` | Yes | MIME type of the document (e.g., "application/pdf") |

**Returns:** `PoolSizeHint`


---

### xml_tag_name()

Converts XML tag name bytes to a string, avoiding allocation when possible.

**Signature:**

```rust
pub fn xml_tag_name(name: &[u8]) -> Str
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `name` | `Vec<u8>` | Yes | The name |

**Returns:** `Str`


---

### escape_html_entities()

Escape `&`, `<`, and `>` in text destined for markdown/HTML output.

Underscores are intentionally **not** escaped. In extracted PDF text they are
literal content (e.g. identifiers like `CTC_ARP_01`), not markdown italic
delimiters.

Uses a single-pass scan: if no special characters are found, returns a
borrowed `Cow` with no allocation.

**Signature:**

```rust
pub fn escape_html_entities(text: &str) -> Str
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `text` | `String` | Yes | The text |

**Returns:** `Str`


---

### normalize_whitespace()

Normalizes whitespace by collapsing multiple whitespace characters into single spaces.
Returns Cow.Borrowed if no normalization needed.

**Signature:**

```rust
pub fn normalize_whitespace(s: &str) -> Str
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `s` | `String` | Yes | The s |

**Returns:** `Str`


---

### detect_columns()

Detect column positions from word x-coordinates.

Groups words by approximate x-position (within `column_threshold` pixels)
and returns the median x-position for each detected column, sorted left to right.

**Signature:**

```rust
pub fn detect_columns(words: Vec<HocrWord>, column_threshold: u32) -> Vec<u32>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `words` | `Vec<HocrWord>` | Yes | The words |
| `column_threshold` | `u32` | Yes | The column threshold |

**Returns:** `Vec<u32>`


---

### detect_rows()

Detect row positions from word y-coordinates.

Groups words by their vertical center position and returns the median
y-position for each detected row. The `row_threshold_ratio` is multiplied
by the median word height to determine the grouping threshold.

**Signature:**

```rust
pub fn detect_rows(words: Vec<HocrWord>, row_threshold_ratio: f64) -> Vec<u32>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `words` | `Vec<HocrWord>` | Yes | The words |
| `row_threshold_ratio` | `f64` | Yes | The row threshold ratio |

**Returns:** `Vec<u32>`


---

### reconstruct_table()

Reconstruct a table grid from words with bounding box positions.

Takes detected words and reconstructs a 2D table by:
1. Detecting column positions (grouping by x-coordinate within `column_threshold`)
2. Detecting row positions (grouping by y-center within `row_threshold_ratio` * median height)
3. Assigning words to cells based on closest row/column
4. Combining words within the same cell

Returns a `Vec<Vec<String>>` where each inner `Vec` is a row of cell texts.

**Signature:**

```rust
pub fn reconstruct_table(words: Vec<HocrWord>, column_threshold: u32, row_threshold_ratio: f64) -> Vec<Vec<String>>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `words` | `Vec<HocrWord>` | Yes | The words |
| `column_threshold` | `u32` | Yes | The column threshold |
| `row_threshold_ratio` | `f64` | Yes | The row threshold ratio |

**Returns:** `Vec<Vec<String>>`


---

### table_to_markdown()

Convert a table grid to markdown format.

The first row is treated as the header row, with a separator line added after it.
Pipe characters in cell content are escaped.

**Signature:**

```rust
pub fn table_to_markdown(table: Vec<Vec<String>>) -> String
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `table` | `Vec<Vec<String>>` | Yes | The table |

**Returns:** `String`


---

### openapi_json()

Generate OpenAPI JSON schema.

Returns the complete OpenAPI 3.1 specification as a JSON string.

**Signature:**

```rust
pub fn openapi_json() -> String
```

**Returns:** `String`


---

### validate_page_boundaries()

Validates the consistency and correctness of page boundaries.

# Validation Rules

1. Boundaries must be sorted by byte_start (monotonically increasing)
2. Boundaries must not overlap (byte_end[i] <= byte_start[i+1])
3. Each boundary must have byte_start < byte_end

**Returns:**

Returns `Ok(())` if all boundaries are valid.
Returns `KreuzbergError.Validation` if any boundary is invalid.

**Signature:**

```rust
pub fn validate_page_boundaries(boundaries: Vec<PageBoundary>) -> Result<(), Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `boundaries` | `Vec<PageBoundary>` | Yes | Page boundary markers to validate |

**Returns:** `()`

**Errors:** Returns `Err(Error)`.


---

### calculate_page_range()

Calculate which pages a byte range spans.

**Returns:**

A tuple of (first_page, last_page) where page numbers are 1-indexed.
Returns (None, None) if boundaries are empty or chunk doesn't overlap any page.

**Errors:**

Returns `KreuzbergError.Validation` if boundaries are invalid.

**Signature:**

```rust
pub fn calculate_page_range(byte_start: usize, byte_end: usize, boundaries: Vec<PageBoundary>) -> Result<OptionUsizeOptionUsize, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `byte_start` | `usize` | Yes | Starting byte offset of the chunk |
| `byte_end` | `usize` | Yes | Ending byte offset of the chunk |
| `boundaries` | `Vec<PageBoundary>` | Yes | Page boundary markers from the document |

**Returns:** `OptionUsizeOptionUsize`

**Errors:** Returns `Err(Error)`.


---

### classify_chunk()

Classify a single chunk based on its content and optional heading context.

Rules are evaluated in priority order. The first matching rule determines
the returned `ChunkType`. When no rule matches, `ChunkType.Unknown`
is returned.

  (only available when using `ChunkerType.Markdown`).

**Signature:**

```rust
pub fn classify_chunk(content: &str, heading_context: Option<HeadingContext>) -> ChunkType
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `content` | `String` | Yes | The text content of the chunk (may be trimmed or raw). |
| `heading_context` | `Option<HeadingContext>` | No | Optional heading hierarchy this chunk falls under |

**Returns:** `ChunkType`


---

### chunk_text()

Split text into chunks with optional page boundary tracking.

This is the primary API function for chunking text. It supports both plain text
and Markdown with configurable chunk size, overlap, and page boundary mapping.

**Returns:**

A ChunkingResult containing all chunks and their metadata.

**Signature:**

```rust
pub fn chunk_text(text: &str, config: ChunkingConfig, page_boundaries: Option<Vec<PageBoundary>>) -> Result<ChunkingResult, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `text` | `String` | Yes | The text to split into chunks |
| `config` | `ChunkingConfig` | Yes | Chunking configuration (max size, overlap, type) |
| `page_boundaries` | `Option<Vec<PageBoundary>>` | No | Optional page boundary markers for mapping chunks to pages |

**Returns:** `ChunkingResult`

**Errors:** Returns `Err(Error)`.


---

### chunk_text_with_heading_source()

Chunk text with an optional separate markdown source for heading context resolution.

When `heading_source` is provided, it is used instead of `text` for building the
heading map. This is needed when `text` is plain text (no markdown headings) but
the original document had headings that were stripped during rendering.

**Signature:**

```rust
pub fn chunk_text_with_heading_source(text: &str, config: ChunkingConfig, page_boundaries: Option<Vec<PageBoundary>>, heading_source: Option<String>) -> Result<ChunkingResult, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `text` | `String` | Yes | The text |
| `config` | `ChunkingConfig` | Yes | The configuration options |
| `page_boundaries` | `Option<Vec<PageBoundary>>` | No | The page boundaries |
| `heading_source` | `Option<String>` | No | The heading source |

**Returns:** `ChunkingResult`

**Errors:** Returns `Err(Error)`.


---

### chunk_text_with_type()

Chunk text with explicit type specification.

This is a convenience function that constructs a ChunkingConfig from individual
parameters and calls `chunk_text`.

**Returns:**

A ChunkingResult containing all chunks and their metadata.

**Signature:**

```rust
pub fn chunk_text_with_type(text: &str, max_characters: usize, overlap: usize, trim: bool, chunker_type: ChunkerType) -> Result<ChunkingResult, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `text` | `String` | Yes | The text to split into chunks |
| `max_characters` | `usize` | Yes | Maximum characters per chunk |
| `overlap` | `usize` | Yes | Character overlap between consecutive chunks |
| `trim` | `bool` | Yes | Whether to trim whitespace from boundaries |
| `chunker_type` | `ChunkerType` | Yes | Type of chunker to use (Text or Markdown) |

**Returns:** `ChunkingResult`

**Errors:** Returns `Err(Error)`.


---

### chunk_texts_batch()

Batch process multiple texts with the same configuration.

This convenience function applies the same chunking configuration to multiple
texts in sequence.

**Returns:**

A vector of ChunkingResult objects, one per input text.

**Errors:**

Returns an error if chunking any individual text fails.

**Signature:**

```rust
pub fn chunk_texts_batch(texts: Vec<String>, config: ChunkingConfig) -> Result<Vec<ChunkingResult>, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `texts` | `Vec<String>` | Yes | Slice of text strings to chunk |
| `config` | `ChunkingConfig` | Yes | Chunking configuration to apply to all texts |

**Returns:** `Vec<ChunkingResult>`

**Errors:** Returns `Err(Error)`.


---

### precompute_utf8_boundaries()

Pre-computes valid UTF-8 character boundaries for a text string.

This function performs a single O(n) pass through the text to identify all valid
UTF-8 character boundaries, storing them in a BitVec for O(1) lookups.

**Returns:**

A BitVec where each bit represents whether a byte offset is a valid UTF-8 character boundary.
The BitVec has length `text.len() + 1` (includes the end position).

**Signature:**

```rust
pub fn precompute_utf8_boundaries(text: &str) -> BitVec
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `text` | `String` | Yes | The text to analyze |

**Returns:** `BitVec`


---

### validate_utf8_boundaries()

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

```rust
pub fn validate_utf8_boundaries(text: &str, boundaries: Vec<PageBoundary>) -> Result<(), Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `text` | `String` | Yes | The text being chunked |
| `boundaries` | `Vec<PageBoundary>` | Yes | Page boundary markers to validate |

**Returns:** `()`

**Errors:** Returns `Err(Error)`.


---

### register_chunking_processor()

Register the chunking processor with the global registry.

This function should be called once at application startup to register
the chunking post-processor.

**Note:** This is called automatically on first use.
Explicit calling is optional.

**Signature:**

```rust
pub fn register_chunking_processor() -> Result<(), Error>
```

**Returns:** `()`

**Errors:** Returns `Err(Error)`.


---

### create_client()

Create a liter-llm `DefaultClient` from kreuzberg's `LlmConfig`.

The `model` field from the config is passed as a model hint so that
liter-llm can resolve the correct provider automatically.

When `api_key` is `None`, liter-llm falls back to the provider's standard
environment variable (e.g., `OPENAI_API_KEY`).

**Signature:**

```rust
pub fn create_client(config: LlmConfig) -> Result<DefaultClient, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `config` | `LlmConfig` | Yes | The configuration options |

**Returns:** `DefaultClient`

**Errors:** Returns `Err(Error)`.


---

### render_template()

Render a Jinja2 template with the given context variables.

**Signature:**

```rust
pub fn render_template(template: &str, context: Value) -> Result<String, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `template` | `String` | Yes | The template |
| `context` | `Value` | Yes | The value |

**Returns:** `String`

**Errors:** Returns `Err(Error)`.


---

### extract_structured()

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

```rust
pub async fn extract_structured(content: &str, config: StructuredExtractionConfig) -> Result<LlmUsage, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `content` | `String` | Yes | The extracted document text to send to the LLM. |
| `config` | `StructuredExtractionConfig` | Yes | Structured extraction configuration including schema and LLM settings. |

**Returns:** `LlmUsage`

**Errors:** Returns `Err(Error)`.


---

### vlm_ocr()

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

```rust
pub async fn vlm_ocr(image_bytes: &[u8], image_mime_type: &str, language: &str, config: LlmConfig) -> Result<LlmUsage, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `image_bytes` | `Vec<u8>` | Yes | Raw image data (JPEG, PNG, WebP, etc.) |
| `image_mime_type` | `String` | Yes | MIME type of the image (e.g., `"image/png"`) |
| `language` | `String` | Yes | ISO 639 language code or Tesseract language name |
| `config` | `LlmConfig` | Yes | LLM provider/model configuration |

**Returns:** `LlmUsage`

**Errors:** Returns `Err(Error)`.


---

### normalize()

L2-normalize a vector.

**Signature:**

```rust
pub fn normalize(v: Vec<f32>) -> Vec<f32>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `v` | `Vec<f32>` | Yes | The v |

**Returns:** `Vec<f32>`


---

### get_preset()

Get a preset by name.

**Signature:**

```rust
pub fn get_preset(name: &str) -> Option<EmbeddingPreset>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `name` | `String` | Yes | The name |

**Returns:** `Option<EmbeddingPreset>`


---

### list_presets()

List all available preset names.

**Signature:**

```rust
pub fn list_presets() -> Vec<String>
```

**Returns:** `Vec<String>`


---

### warm_model()

Eagerly download and cache an embedding model without returning the handle.

This triggers the same download and initialization as `get_or_init_engine`
but discards the result, making it suitable for cache-warming scenarios
where the caller doesn't need to use the model immediately.

**Note**: This function downloads AND initializes the ONNX model, which
requires ONNX Runtime and uses significant memory. For download-only
scenarios (e.g., init containers), use `download_model` instead.

**Signature:**

```rust
pub fn warm_model(model_type: EmbeddingModelType, cache_dir: Option<PathBuf>) -> Result<(), Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `model_type` | `EmbeddingModelType` | Yes | The embedding model type |
| `cache_dir` | `Option<PathBuf>` | No | The cache dir |

**Returns:** `()`

**Errors:** Returns `Err(Error)`.


---

### download_model()

Download an embedding model's files without initializing ONNX Runtime.

Downloads the model files (ONNX model, tokenizer, config) from HuggingFace
to the cache directory. Subsequent calls to `warm_model` or
`get_or_init_engine` will find the files cached and skip the download step.

This is ideal for init containers or CI environments where you want to
pre-populate the cache without loading models into memory.

**Signature:**

```rust
pub fn download_model(model_type: EmbeddingModelType, cache_dir: Option<PathBuf>) -> Result<(), Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `model_type` | `EmbeddingModelType` | Yes | The embedding model type |
| `cache_dir` | `Option<PathBuf>` | No | The cache dir |

**Returns:** `()`

**Errors:** Returns `Err(Error)`.


---

### generate_embeddings_for_chunks()

Generate embeddings for text chunks using the specified configuration.

This function modifies chunks in-place, populating their `embedding` field
with generated embedding vectors. It uses batch processing for efficiency.

**Returns:**

Returns `Ok(())` if embeddings were generated successfully, or an error if
model initialization or embedding generation fails.

**Signature:**

```rust
pub fn generate_embeddings_for_chunks(chunks: Vec<Chunk>, config: EmbeddingConfig) -> Result<(), Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `chunks` | `Vec<Chunk>` | Yes | Mutable reference to vector of chunks to generate embeddings for |
| `config` | `EmbeddingConfig` | Yes | Embedding configuration specifying model and parameters |

**Returns:** `()`

**Errors:** Returns `Err(Error)`.


---

### calculate_smart_dpi()

Calculate smart DPI based on page dimensions, memory constraints, and target DPI

**Signature:**

```rust
pub fn calculate_smart_dpi(page_width: f64, page_height: f64, target_dpi: i32, max_dimension: i32, max_memory_mb: f64) -> i32
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `page_width` | `f64` | Yes | The page width |
| `page_height` | `f64` | Yes | The page height |
| `target_dpi` | `i32` | Yes | The target dpi |
| `max_dimension` | `i32` | Yes | The max dimension |
| `max_memory_mb` | `f64` | Yes | The max memory mb |

**Returns:** `i32`


---

### calculate_optimal_dpi()

Calculate optimal DPI with min/max constraints

**Signature:**

```rust
pub fn calculate_optimal_dpi(page_width: f64, page_height: f64, target_dpi: i32, max_dimension: i32, min_dpi: i32, max_dpi: i32) -> i32
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `page_width` | `f64` | Yes | The page width |
| `page_height` | `f64` | Yes | The page height |
| `target_dpi` | `i32` | Yes | The target dpi |
| `max_dimension` | `i32` | Yes | The max dimension |
| `min_dpi` | `i32` | Yes | The min dpi |
| `max_dpi` | `i32` | Yes | The max dpi |

**Returns:** `i32`


---

### normalize_image_dpi()

Normalize image DPI based on extraction configuration

**Returns:**
* `NormalizeResult` containing processed image data and metadata

**Signature:**

```rust
pub fn normalize_image_dpi(rgb_data: &[u8], width: usize, height: usize, config: ExtractionConfig, current_dpi: Option<f64>) -> Result<NormalizeResult, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `rgb_data` | `Vec<u8>` | Yes | RGB image data as a flat `Vec<u8>` (height * width * 3 bytes, row-major) |
| `width` | `usize` | Yes | Image width in pixels |
| `height` | `usize` | Yes | Image height in pixels |
| `config` | `ExtractionConfig` | Yes | Extraction configuration containing DPI settings |
| `current_dpi` | `Option<f64>` | No | Optional current DPI of the image (defaults to 72 if None) |

**Returns:** `NormalizeResult`

**Errors:** Returns `Err(Error)`.


---

### resize_image()

Resize an image using fast_image_resize with appropriate algorithm based on scale factor

**Signature:**

```rust
pub fn resize_image(image: DynamicImage, new_width: u32, new_height: u32, scale_factor: f64) -> Result<DynamicImage, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `image` | `DynamicImage` | Yes | The dynamic image |
| `new_width` | `u32` | Yes | The new width |
| `new_height` | `u32` | Yes | The new height |
| `scale_factor` | `f64` | Yes | The scale factor |

**Returns:** `DynamicImage`

**Errors:** Returns `Err(Error)`.


---

### detect_languages()

Detect languages in text using whatlang.

Returns a list of detected language codes (ISO 639-3 format).
Returns `None` if no languages could be detected with sufficient confidence.

**Signature:**

```rust
pub fn detect_languages(text: &str, config: LanguageDetectionConfig) -> Result<Option<Vec<String>>, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `text` | `String` | Yes | The text to analyze for language detection |
| `config` | `LanguageDetectionConfig` | Yes | Optional configuration for language detection |

**Returns:** `Option<Vec<String>>`

**Errors:** Returns `Err(Error)`.


---

### register_language_detection_processor()

Register the language detection processor with the global registry.

This function should be called once at application startup to register
the language detection post-processor.

**Note:** This is called automatically on first use.
Explicit calling is optional.

**Signature:**

```rust
pub fn register_language_detection_processor() -> Result<(), Error>
```

**Returns:** `()`

**Errors:** Returns `Err(Error)`.


---

### get_stopwords()

Get stopwords for a language with normalization.

This function provides a user-friendly interface to the stopwords registry with:
- **Case-insensitive lookup**: "EN", "en", "En" all work
- **Locale normalization**: "en-US", "en_GB", "es-ES" extract to "en", "es"
- **Consistent behavior**: Returns `None` for unsupported languages

# Language Code Format

Accepts multiple formats:
- ISO 639-1 two-letter codes: `"en"`, `"es"`, `"de"`, etc.
- Uppercase variants: `"EN"`, `"ES"`, `"DE"`
- Locale codes with hyphen: `"en-US"`, `"es-ES"`, `"pt-BR"`
- Locale codes with underscore: `"en_US"`, `"es_ES"`, `"pt_BR"`

All formats are normalized to lowercase two-letter ISO 639-1 codes.

**Returns:**

- `Some(&HashSet<String>)` if the language is supported (64 languages available)
- `None` if the language is not supported

# Performance

This function performs two operations:
1. String normalization (lowercase + truncate) - O(1) for typical language codes
2. HashMap lookup in STOPWORDS - O(1) average case

Total overhead is negligible (~10-50ns on modern CPUs).

**Signature:**

```rust
pub fn get_stopwords(lang: &str) -> Option<AHashSet>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `lang` | `String` | Yes | The lang |

**Returns:** `Option<AHashSet>`


---

### get_stopwords_with_fallback()

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
- `None` if neither language is supported

# Common Patterns


# Performance

This function performs at most two HashMap lookups:
1. Try primary language (O(1) average case)
2. If None, try fallback language (O(1) average case)

Total overhead is negligible (~10-100ns on modern CPUs).

**Signature:**

```rust
pub fn get_stopwords_with_fallback(language: &str, fallback: &str) -> Option<AHashSet>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `language` | `String` | Yes | Primary language code to try first |
| `fallback` | `String` | Yes | Fallback language code to use if primary not available |

**Returns:** `Option<AHashSet>`


---

### extract_keywords()

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

```rust
pub fn extract_keywords(text: &str, config: KeywordConfig) -> Result<Vec<Keyword>, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `text` | `String` | Yes | The text to extract keywords from |
| `config` | `KeywordConfig` | Yes | Keyword extraction configuration |

**Returns:** `Vec<Keyword>`

**Errors:** Returns `Err(Error)`.


---

### register_keyword_processor()

Register the keyword extraction processor with the global registry.

This function should be called once at application startup to register
the keyword extraction post-processor.

**Note:** This is called automatically on first use.
Explicit calling is optional.

**Signature:**

```rust
pub fn register_keyword_processor() -> Result<(), Error>
```

**Returns:** `()`

**Errors:** Returns `Err(Error)`.


---

### text_block_to_element()

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

```rust
pub fn text_block_to_element(block: TextBlock, page_number: usize) -> Result<Option<OcrElement>, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `block` | `TextBlock` | Yes | PaddleOCR TextBlock containing OCR results |
| `page_number` | `usize` | Yes | 1-indexed page number |

**Returns:** `Option<OcrElement>`

**Errors:** Returns `Err(Error)`.


---

### tsv_row_to_element()

Convert a Tesseract TSV row to a unified OcrElement.

Preserves:
- Axis-aligned bounding box
- Recognition confidence (Tesseract doesn't have separate detection confidence)
- Hierarchical level information

**Returns:**

An `OcrElement` with rectangle geometry and Tesseract metadata.

**Signature:**

```rust
pub fn tsv_row_to_element(row: TsvRow) -> OcrElement
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `row` | `TsvRow` | Yes | Parsed TSV row from Tesseract output |

**Returns:** `OcrElement`


---

### iterator_word_to_element()

Convert a Tesseract iterator WordData to a unified OcrElement with rich metadata.

Unlike `tsv_row_to_element` which only has text, bbox, and confidence,
this populates font attributes (bold, italic, monospace, pointsize) and
block/paragraph context from the Tesseract layout analysis.

**Returns:**

An `OcrElement` at `Word` level with all available font and layout metadata.

**Signature:**

```rust
pub fn iterator_word_to_element(word: WordData, block_type: Option<TessPolyBlockType>, para_info: Option<ParaInfo>, page_number: usize) -> OcrElement
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `word` | `WordData` | Yes | WordData from the Tesseract result iterator |
| `block_type` | `Option<TessPolyBlockType>` | No | Optional block type from Tesseract layout analysis |
| `para_info` | `Option<ParaInfo>` | No | Optional paragraph metadata (justification, list item flag) |
| `page_number` | `usize` | Yes | 1-indexed page number |

**Returns:** `OcrElement`


---

### element_to_hocr_word()

Convert an OcrElement to an HocrWord for table reconstruction.

This enables reuse of the existing table detection algorithms from
html-to-markdown-rs with PaddleOCR results.

**Returns:**

An `HocrWord` suitable for table reconstruction algorithms.

**Signature:**

```rust
pub fn element_to_hocr_word(element: OcrElement) -> HocrWord
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `element` | `OcrElement` | Yes | Unified OCR element with geometry and text |

**Returns:** `HocrWord`


---

### elements_to_hocr_words()

Convert a vector of OcrElements to HocrWords for batch table processing.

Filters to word-level elements only, as table reconstruction
works best with word-level granularity.

**Returns:**

A vector of HocrWords filtered by confidence and element level.

**Signature:**

```rust
pub fn elements_to_hocr_words(elements: Vec<OcrElement>, min_confidence: f64) -> Vec<HocrWord>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `elements` | `Vec<OcrElement>` | Yes | Slice of OCR elements to convert |
| `min_confidence` | `f64` | Yes | Minimum recognition confidence threshold (0.0-1.0) |

**Returns:** `Vec<HocrWord>`


---

### parse_hocr_to_internal_document()

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

```rust
pub fn parse_hocr_to_internal_document(hocr_html: &str) -> InternalDocument
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `hocr_html` | `String` | Yes | The hocr html |

**Returns:** `InternalDocument`


---

### assemble_ocr_markdown()

Assemble structured markdown from OCR elements using layout detection results.

Both inputs must be in the same pixel coordinate space (from the same
rendered page image). Returns plain text join when `detection` is `None`.

`recognized_tables` provides pre-computed markdown for Table regions
(from TATR or other table structure recognizer). When empty, Table
regions fall back to heuristic grid reconstruction from OCR elements.

**Signature:**

```rust
pub fn assemble_ocr_markdown(elements: Vec<OcrElement>, detection: Option<DetectionResult>, img_width: u32, img_height: u32, recognized_tables: Vec<RecognizedTable>) -> String
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `elements` | `Vec<OcrElement>` | Yes | The elements |
| `detection` | `Option<DetectionResult>` | No | The detection result |
| `img_width` | `u32` | Yes | The img width |
| `img_height` | `u32` | Yes | The img height |
| `recognized_tables` | `Vec<RecognizedTable>` | Yes | The recognized tables |

**Returns:** `String`


---

### recognize_page_tables()

Run TATR table recognition for all Table regions in a page.

For each Table detection, crops the page image, runs TATR inference,
matches OCR elements to cells, and produces markdown tables.

**Signature:**

```rust
pub fn recognize_page_tables(page_image: RgbImage, detection: DetectionResult, elements: Vec<OcrElement>, tatr_model: TatrModel) -> Vec<RecognizedTable>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `page_image` | `RgbImage` | Yes | The rgb image |
| `detection` | `DetectionResult` | Yes | The detection result |
| `elements` | `Vec<OcrElement>` | Yes | The elements |
| `tatr_model` | `TatrModel` | Yes | The tatr model |

**Returns:** `Vec<RecognizedTable>`


---

### extract_words_from_tsv()

Extract words from Tesseract TSV output and convert to HocrWord format.

This parses Tesseract's TSV format (level, page_num, block_num, ...) and
converts it to the HocrWord format used for table reconstruction.

**Signature:**

```rust
pub fn extract_words_from_tsv(tsv_data: &str, min_confidence: f64) -> Result<Vec<HocrWord>, OcrError>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `tsv_data` | `String` | Yes | The tsv data |
| `min_confidence` | `f64` | Yes | The min confidence |

**Returns:** `Vec<HocrWord>`

**Errors:** Returns `Err(OcrError)`.


---

### compute_hash()

Compute a blake3 hash string from input data.

Returns a 32-character hex string (128 bits of blake3 output).

**Signature:**

```rust
pub fn compute_hash(data: &str) -> String
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `data` | `String` | Yes | The data |

**Returns:** `String`


---

### validate_language_code()

**Signature:**

```rust
pub fn validate_language_code(lang_code: &str) -> Result<(), OcrError>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `lang_code` | `String` | Yes | The lang code |

**Returns:** `()`

**Errors:** Returns `Err(OcrError)`.


---

### validate_tesseract_version()

**Signature:**

```rust
pub fn validate_tesseract_version(version: u32) -> Result<(), OcrError>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `version` | `u32` | Yes | The version |

**Returns:** `()`

**Errors:** Returns `Err(OcrError)`.


---

### ensure_ort_available()

Ensure ONNX Runtime is discoverable. Safe to call multiple times (no-op after first).

When the `ort-bundled` feature is enabled the ORT binaries are embedded via the
official Microsoft release and no system library search is needed.

**Signature:**

```rust
pub fn ensure_ort_available()
```

**Returns:** `()`


---

### is_language_supported()

Check if a language code is supported by PaddleOCR.

**Signature:**

```rust
pub fn is_language_supported(lang: &str) -> bool
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `lang` | `String` | Yes | The lang |

**Returns:** `bool`


---

### language_to_script_family()

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

```rust
pub fn language_to_script_family(paddle_lang: &str) -> String
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `paddle_lang` | `String` | Yes | The paddle lang |

**Returns:** `String`


---

### map_language_code()

Map Kreuzberg language codes to PaddleOCR language codes.

**Signature:**

```rust
pub fn map_language_code(kreuzberg_code: &str) -> Option<String>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `kreuzberg_code` | `String` | Yes | The kreuzberg code |

**Returns:** `Option<String>`


---

### resolve_cache_dir()

Resolve the cache directory for the auto-rotate model.

**Signature:**

```rust
pub fn resolve_cache_dir() -> PathBuf
```

**Returns:** `PathBuf`


---

### detect_and_rotate()

Detect orientation and return a corrected image if rotation is needed.

Returns `Ok(Some(rotated_bytes))` if rotation was applied,
`Ok(None)` if no rotation needed (0° or low confidence).

**Signature:**

```rust
pub fn detect_and_rotate(detector: DocOrientationDetector, image_bytes: &[u8]) -> Result<Option<Vec<u8>>, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `detector` | `DocOrientationDetector` | Yes | The doc orientation detector |
| `image_bytes` | `Vec<u8>` | Yes | The image bytes |

**Returns:** `Option<Vec<u8>>`

**Errors:** Returns `Err(Error)`.


---

### build_cell_grid()

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

```rust
pub fn build_cell_grid(result: TatrResult, table_bbox: Option<F324>) -> Vec<Vec<CellBBox>>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `result` | `TatrResult` | Yes | The tatr result |
| `table_bbox` | `Option<F324>` | No | The [f32;4] |

**Returns:** `Vec<Vec<CellBBox>>`


---

### apply_heuristics()

Apply Docling-style postprocessing heuristics to raw detections.

This implements the key heuristics from `docling/utils/layout_postprocessor.py`:
1. Per-class confidence thresholds
2. Full-page picture removal (>90% page area)
3. Overlap resolution (IoU > 0.8 or containment > 0.8)
4. Cross-type overlap handling (KVR vs Table)

**Signature:**

```rust
pub fn apply_heuristics(detections: Vec<LayoutDetection>, page_width: f32, page_height: f32)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `detections` | `Vec<LayoutDetection>` | Yes | The detections |
| `page_width` | `f32` | Yes | The page width |
| `page_height` | `f32` | Yes | The page height |

**Returns:** `()`


---

### greedy_nms()

Standard greedy Non-Maximum Suppression.

Sorts detections by confidence (descending), then iteratively removes
detections that have IoU > `iou_threshold` with any higher-confidence detection.

This is required for YOLO models. RT-DETR is NMS-free.

**Signature:**

```rust
pub fn greedy_nms(detections: Vec<LayoutDetection>, iou_threshold: f32)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `detections` | `Vec<LayoutDetection>` | Yes | The detections |
| `iou_threshold` | `f32` | Yes | The iou threshold |

**Returns:** `()`


---

### preprocess_imagenet()

Preprocess an image for models using ImageNet normalization (e.g., RT-DETR).

Pipeline: resize to target_size x target_size (bilinear) -> rescale /255 -> ImageNet normalize -> NCHW f32.

Uses a single vectorized pass over contiguous pixel data for maximum throughput.

**Signature:**

```rust
pub fn preprocess_imagenet(img: RgbImage, target_size: u32) -> Array4
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `img` | `RgbImage` | Yes | The rgb image |
| `target_size` | `u32` | Yes | The target size |

**Returns:** `Array4`


---

### preprocess_imagenet_letterbox()

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

```rust
pub fn preprocess_imagenet_letterbox(img: RgbImage, target_size: u32) -> Array4F32F32U32U32
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `img` | `RgbImage` | Yes | The rgb image |
| `target_size` | `u32` | Yes | The target size |

**Returns:** `Array4F32F32U32U32`


---

### preprocess_rescale()

Preprocess with rescale only (no ImageNet normalization).

Pipeline: resize to target_size x target_size -> rescale /255 -> NCHW f32.

**Signature:**

```rust
pub fn preprocess_rescale(img: RgbImage, target_size: u32) -> Array4
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `img` | `RgbImage` | Yes | The rgb image |
| `target_size` | `u32` | Yes | The target size |

**Returns:** `Array4`


---

### preprocess_letterbox()

Letterbox preprocessing for YOLOX-style models.

Resizes the image to fit within (target_width x target_height) while maintaining
aspect ratio, padding the remaining area with value 114.0 (raw pixel value).
No normalization — values are 0-255 as YOLOX expects.

Returns the NCHW tensor and the scale ratio (for rescaling detections back).

**Signature:**

```rust
pub fn preprocess_letterbox(img: RgbImage, target_width: u32, target_height: u32) -> Array4F32F32
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `img` | `RgbImage` | Yes | The rgb image |
| `target_width` | `u32` | Yes | The target width |
| `target_height` | `u32` | Yes | The target height |

**Returns:** `Array4F32F32`


---

### build_session()

Build an optimized ORT session from an ONNX model file.

`thread_budget` controls the number of intra-op threads for this session.
Pass the result of `crate.core.config.concurrency.resolve_thread_budget`
to respect the user's `ConcurrencyConfig`.

When `accel` is `None` or `Auto`, uses platform defaults:
- macOS: CoreML (Neural Engine / GPU)
- Linux: CUDA (GPU)
- Others: CPU only

ORT silently falls back to CPU if the requested EP is unavailable.

**Signature:**

```rust
pub fn build_session(path: &str, accel: Option<AccelerationConfig>, thread_budget: usize) -> Result<Session, LayoutError>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `path` | `String` | Yes | Path to the file |
| `accel` | `Option<AccelerationConfig>` | No | The acceleration config |
| `thread_budget` | `usize` | Yes | The thread budget |

**Returns:** `Session`

**Errors:** Returns `Err(LayoutError)`.


---

### config_from_extraction()

Convert a `LayoutDetectionConfig` into a `LayoutEngineConfig`.

**Signature:**

```rust
pub fn config_from_extraction(layout_config: LayoutDetectionConfig) -> LayoutEngineConfig
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `layout_config` | `LayoutDetectionConfig` | Yes | The layout detection config |

**Returns:** `LayoutEngineConfig`


---

### create_engine()

Create a `LayoutEngine` from a `LayoutDetectionConfig`.

Ensures ORT is available, then creates the engine with model download.

**Signature:**

```rust
pub fn create_engine(layout_config: LayoutDetectionConfig) -> Result<LayoutEngine, LayoutError>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `layout_config` | `LayoutDetectionConfig` | Yes | The layout detection config |

**Returns:** `LayoutEngine`

**Errors:** Returns `Err(LayoutError)`.


---

### take_or_create_engine()

Take the cached layout engine, or create a new one if the cache is empty.

The caller owns the engine for the duration of its work and should
return it via `return_engine` when done. This avoids holding the
global mutex during inference.

**Signature:**

```rust
pub fn take_or_create_engine(layout_config: LayoutDetectionConfig) -> Result<LayoutEngine, LayoutError>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `layout_config` | `LayoutDetectionConfig` | Yes | The layout detection config |

**Returns:** `LayoutEngine`

**Errors:** Returns `Err(LayoutError)`.


---

### return_engine()

Return a layout engine to the global cache for reuse by future extractions.

**Signature:**

```rust
pub fn return_engine(engine: LayoutEngine)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `engine` | `LayoutEngine` | Yes | The layout engine |

**Returns:** `()`


---

### take_or_create_tatr()

Take the cached TATR model, or create a new one if the cache is empty.

Returns `None` if the model cannot be loaded. Once a load attempt fails,
subsequent calls return `None` immediately without retrying, avoiding
repeated download attempts and redundant warning logs.

**Signature:**

```rust
pub fn take_or_create_tatr() -> Option<TatrModel>
```

**Returns:** `Option<TatrModel>`


---

### return_tatr()

Return a TATR model to the global cache for reuse.

**Signature:**

```rust
pub fn return_tatr(model: TatrModel)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `model` | `TatrModel` | Yes | The tatr model |

**Returns:** `()`


---

### take_or_create_slanet()

Take a cached SLANeXT model for the given variant, or create a new one.

**Signature:**

```rust
pub fn take_or_create_slanet(variant: &str) -> Option<SlanetModel>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `variant` | `String` | Yes | The variant |

**Returns:** `Option<SlanetModel>`


---

### return_slanet()

Return a SLANeXT model to the global cache for reuse.

**Signature:**

```rust
pub fn return_slanet(variant: &str, model: SlanetModel)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `variant` | `String` | Yes | The variant |
| `model` | `SlanetModel` | Yes | The slanet model |

**Returns:** `()`


---

### take_or_create_table_classifier()

Take a cached table classifier, or create a new one.

**Signature:**

```rust
pub fn take_or_create_table_classifier() -> Option<TableClassifier>
```

**Returns:** `Option<TableClassifier>`


---

### return_table_classifier()

Return a table classifier to the global cache for reuse.

**Signature:**

```rust
pub fn return_table_classifier(model: TableClassifier)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `model` | `TableClassifier` | Yes | The table classifier |

**Returns:** `()`


---

### extract_annotations_from_document()

Extract annotations from all pages of a PDF document.

Iterates over every page and every annotation on each page, mapping
pdfium annotation subtypes to `PdfAnnotationType` and collecting
content text and bounding boxes where available.

Annotations that cannot be read are silently skipped.

**Returns:**

A `Vec<PdfAnnotation>` containing all successfully extracted annotations.

**Signature:**

```rust
pub fn extract_annotations_from_document(document: PdfDocument) -> Vec<PdfAnnotation>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `document` | `PdfDocument` | Yes | A reference to the loaded pdfium `PdfDocument`. |

**Returns:** `Vec<PdfAnnotation>`


---

### extract_bookmarks()

Extract bookmarks (outlines) from a PDF document loaded via lopdf.

Walks the `/Outlines` tree in the document catalog, collecting each bookmark's
title and destination. Returns an empty `Vec` if the document has no outlines.

**Signature:**

```rust
pub fn extract_bookmarks(document: Document) -> Vec<Uri>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `document` | `Document` | Yes | The document |

**Returns:** `Vec<Uri>`


---

### extract_bundled_pdfium()

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

```rust
pub fn extract_bundled_pdfium() -> Result<PathBuf, Error>
```

**Returns:** `PathBuf`

**Errors:** Returns `Err(Error)`.


---

### extract_embedded_files()

Extract embedded file descriptors from a PDF document loaded via lopdf.

Walks the `/Names` → `/EmbeddedFiles` name tree in the catalog.
Returns an empty `Vec` if the document has no embedded files.

**Signature:**

```rust
pub fn extract_embedded_files(document: Document) -> Vec<EmbeddedFile>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `document` | `Document` | Yes | The document |

**Returns:** `Vec<EmbeddedFile>`


---

### extract_and_process_embedded_files()

Extract embedded files from PDF bytes and recursively process them.

Returns `(children, warnings)`. The children are `ArchiveEntry` values
suitable for attaching to `InternalDocument.children`.

**Signature:**

```rust
pub async fn extract_and_process_embedded_files(pdf_bytes: &[u8], config: ExtractionConfig) -> VecArchiveEntryVecProcessingWarning
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pdf_bytes` | `Vec<u8>` | Yes | The pdf bytes |
| `config` | `ExtractionConfig` | Yes | The configuration options |

**Returns:** `VecArchiveEntryVecProcessingWarning`


---

### initialize_font_cache()

Initialize the global font cache.

On first call, discovers and loads all system fonts. Subsequent calls are no-ops.
Caching is thread-safe via RwLock; concurrent reads during PDF processing are efficient.

**Returns:**

Ok if initialization succeeds or cache is already initialized, or PdfError if font discovery fails.

# Performance

- First call: 50-100ms (system font discovery + loading)
- Subsequent calls: < 1μs (no-op, just checks initialized flag)

**Signature:**

```rust
pub fn initialize_font_cache() -> Result<(), PdfError>
```

**Returns:** `()`

**Errors:** Returns `Err(PdfError)`.


---

### get_font_descriptors()

Get cached font descriptors for Pdfium configuration.

Ensures the font cache is initialized, then returns font descriptors
derived from the cached fonts. This call is fast after the first invocation.

**Returns:**

A Vec of FontDescriptor objects suitable for `PdfiumConfig.set_font_provider()`.

# Performance

- First call: ~50-100ms (includes font discovery)
- Subsequent calls: < 1ms (reads from cache)

**Signature:**

```rust
pub fn get_font_descriptors() -> Result<Vec<FontDescriptor>, PdfError>
```

**Returns:** `Vec<FontDescriptor>`

**Errors:** Returns `Err(PdfError)`.


---

### cached_font_count()

Get the number of cached fonts.

Useful for diagnostics and testing.

**Returns:**

Number of fonts in the cache, or 0 if not initialized.

**Signature:**

```rust
pub fn cached_font_count() -> usize
```

**Returns:** `usize`


---

### clear_font_cache()

Clear the font cache (for testing purposes).

**Panics:**

Panics if the cache lock is poisoned, which should only happen in test scenarios
with deliberate panic injection.

**Signature:**

```rust
pub fn clear_font_cache()
```

**Returns:** `()`


---

### extract_images_from_pdf()

**Signature:**

```rust
pub fn extract_images_from_pdf(pdf_bytes: &[u8]) -> Result<Vec<PdfImage>, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pdf_bytes` | `Vec<u8>` | Yes | The pdf bytes |

**Returns:** `Vec<PdfImage>`

**Errors:** Returns `Err(Error)`.


---

### extract_images_from_pdf_with_password()

**Signature:**

```rust
pub fn extract_images_from_pdf_with_password(pdf_bytes: &[u8], password: &str) -> Result<Vec<PdfImage>, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pdf_bytes` | `Vec<u8>` | Yes | The pdf bytes |
| `password` | `String` | Yes | The password |

**Returns:** `Vec<PdfImage>`

**Errors:** Returns `Err(Error)`.


---

### reextract_raw_images_via_pdfium()

Re-extract images that have unusable formats (`"raw"`, `"ccitt"`, `"jbig2"`) by
rendering them through pdfium's bitmap pipeline, which handles all PDF filter
chains internally.

Returns the number of images successfully re-extracted.

**Signature:**

```rust
pub fn reextract_raw_images_via_pdfium(pdf_bytes: &[u8], images: Vec<PdfImage>) -> Result<u32, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pdf_bytes` | `Vec<u8>` | Yes | The pdf bytes |
| `images` | `Vec<PdfImage>` | Yes | The images |

**Returns:** `u32`

**Errors:** Returns `Err(Error)`.


---

### detect_layout_for_document()

Run layout detection on all pages of a PDF document.

Under the hood, this uses batched layout detection to prevent holding too many
full-resolution page images in memory simultaneously before detection.

**Signature:**

```rust
pub fn detect_layout_for_document(pdf_bytes: &[u8], engine: LayoutEngine) -> Result<DynamicImage, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pdf_bytes` | `Vec<u8>` | Yes | The pdf bytes |
| `engine` | `LayoutEngine` | Yes | The layout engine |

**Returns:** `DynamicImage`

**Errors:** Returns `Err(Error)`.


---

### detect_layout_for_images()

Run layout detection on pre-rendered images.

Returns pixel-space `DetectionResult`s — no PDF coordinate conversion.
Use this when images are already available (e.g., from the OCR rendering
path) to avoid redundant PDF re-rendering.

**Signature:**

```rust
pub fn detect_layout_for_images(images: Vec<DynamicImage>, engine: LayoutEngine) -> Result<Vec<DetectionResult>, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `images` | `Vec<DynamicImage>` | Yes | The images |
| `engine` | `LayoutEngine` | Yes | The layout engine |

**Returns:** `Vec<DetectionResult>`

**Errors:** Returns `Err(Error)`.


---

### extract_metadata()

Extract PDF-specific metadata from raw bytes.

Returns only PDF-specific metadata (version, producer, encryption status, dimensions).

**Signature:**

```rust
pub fn extract_metadata(pdf_bytes: &[u8]) -> Result<PdfMetadata, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pdf_bytes` | `Vec<u8>` | Yes | The pdf bytes |

**Returns:** `PdfMetadata`

**Errors:** Returns `Err(Error)`.


---

### extract_metadata_with_password()

Extract PDF-specific metadata from raw bytes with optional password.

Returns only PDF-specific metadata (version, producer, encryption status, dimensions).

**Signature:**

```rust
pub fn extract_metadata_with_password(pdf_bytes: &[u8], password: Option<String>) -> Result<PdfMetadata, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pdf_bytes` | `Vec<u8>` | Yes | The pdf bytes |
| `password` | `Option<String>` | No | The password |

**Returns:** `PdfMetadata`

**Errors:** Returns `Err(Error)`.


---

### extract_metadata_with_passwords()

**Signature:**

```rust
pub fn extract_metadata_with_passwords(pdf_bytes: &[u8], passwords: Vec<String>) -> Result<PdfMetadata, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pdf_bytes` | `Vec<u8>` | Yes | The pdf bytes |
| `passwords` | `Vec<String>` | Yes | The passwords |

**Returns:** `PdfMetadata`

**Errors:** Returns `Err(Error)`.


---

### extract_metadata_from_document()

Extract complete PDF metadata from a document.

Extracts common fields (title, subject, authors, keywords, dates, creator),
PDF-specific metadata, and optionally builds a PageStructure with boundaries.

  If provided, a PageStructure will be built with these boundaries.
* `content` - Optional extracted text content, used for blank page detection.
  If provided, `PageInfo.is_blank` will be populated based on text content analysis.
  If `None`, `is_blank` will be `None` for all pages.

**Returns:**

Returns a `PdfExtractionMetadata` struct containing all extracted metadata,
including page structure if boundaries were provided.

**Signature:**

```rust
pub fn extract_metadata_from_document(document: PdfDocument, page_boundaries: Option<Vec<PageBoundary>>, content: Option<String>) -> Result<PdfExtractionMetadata, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `document` | `PdfDocument` | Yes | The PDF document to extract metadata from |
| `page_boundaries` | `Option<Vec<PageBoundary>>` | No | Optional vector of PageBoundary entries for building PageStructure. |
| `content` | `Option<String>` | No | Optional extracted text content, used for blank page detection. |

**Returns:** `PdfExtractionMetadata`

**Errors:** Returns `Err(Error)`.


---

### extract_common_metadata_from_document()

Extract common metadata from a PDF document.

Returns common fields (title, authors, keywords, dates) that are now stored
in the base `Metadata` struct instead of format-specific metadata.

This function uses batch fetching with caching to optimize metadata extraction
by reducing repeated dictionary lookups. All metadata tags are fetched once and
cached in a single pass.

**Signature:**

```rust
pub fn extract_common_metadata_from_document(document: PdfDocument) -> Result<CommonPdfMetadata, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `document` | `PdfDocument` | Yes | The pdf document |

**Returns:** `CommonPdfMetadata`

**Errors:** Returns `Err(Error)`.


---

### render_page_to_image()

**Signature:**

```rust
pub fn render_page_to_image(pdf_bytes: &[u8], page_index: usize, options: PageRenderOptions) -> Result<DynamicImage, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pdf_bytes` | `Vec<u8>` | Yes | The pdf bytes |
| `page_index` | `usize` | Yes | The page index |
| `options` | `PageRenderOptions` | Yes | The options to use |

**Returns:** `DynamicImage`

**Errors:** Returns `Err(Error)`.


---

### render_pdf_page_to_png()

Render a single PDF page to a PNG-encoded byte buffer.

**Errors:**

Returns an error if the PDF is invalid, the page index is out of bounds,
or if the page fails to render.

**Signature:**

```rust
pub fn render_pdf_page_to_png(pdf_bytes: &[u8], page_index: usize, dpi: Option<i32>, password: Option<String>) -> Result<Vec<u8>, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pdf_bytes` | `Vec<u8>` | Yes | The pdf bytes |
| `page_index` | `usize` | Yes | The page index |
| `dpi` | `Option<i32>` | No | The dpi |
| `password` | `Option<String>` | No | The password |

**Returns:** `Vec<u8>`

**Errors:** Returns `Err(Error)`.


---

### extract_words_from_page()

Extract words with positions from PDF page for table detection.

Groups adjacent characters into words based on spacing heuristics,
then converts to HocrWord format for table reconstruction.

**Returns:**

Vector of HocrWord objects with text and bounding box information.

**Note:**
This function requires the "ocr" feature to be enabled. Without it, returns an error.

**Signature:**

```rust
pub fn extract_words_from_page(page: PdfPage, min_confidence: f64) -> Result<Vec<HocrWord>, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `page` | `PdfPage` | Yes | PDF page to extract words from |
| `min_confidence` | `f64` | Yes | Minimum confidence threshold (0.0-100.0). PDF text has high confidence (95.0). |

**Returns:** `Vec<HocrWord>`

**Errors:** Returns `Err(Error)`.


---

### segment_to_hocr_word()

Convert a PDF `SegmentData` to an `HocrWord` for table reconstruction.

`SegmentData` uses PDF coordinates (y=0 at bottom, increases upward).
`HocrWord` uses image coordinates (y=0 at top, increases downward).

**Signature:**

```rust
pub fn segment_to_hocr_word(seg: SegmentData, page_height: f32) -> HocrWord
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `seg` | `SegmentData` | Yes | The segment data |
| `page_height` | `f32` | Yes | The page height |

**Returns:** `HocrWord`


---

### split_segment_to_words()

Split a `SegmentData` into word-level `HocrWord`s for table reconstruction.

Pdfium segments can contain multiple whitespace-separated words (merged by
shared baseline + font). For table cell matching, each word needs its own
bounding box so it can be assigned to the correct column/cell.

Single-word segments use `segment_to_hocr_word` directly (fast path).
Multi-word segments get proportional bbox estimation per word based on
byte offset within the segment text.

**Signature:**

```rust
pub fn split_segment_to_words(seg: SegmentData, page_height: f32) -> Vec<HocrWord>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `seg` | `SegmentData` | Yes | The segment data |
| `page_height` | `f32` | Yes | The page height |

**Returns:** `Vec<HocrWord>`


---

### segments_to_words()

Convert a page's segments to word-level `HocrWord`s for table extraction.

Splits multi-word segments into individual words with proportional bounding
boxes, ensuring each word can be independently matched to table cells.

**Signature:**

```rust
pub fn segments_to_words(segments: Vec<SegmentData>, page_height: f32) -> Vec<HocrWord>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `segments` | `Vec<SegmentData>` | Yes | The segments |
| `page_height` | `f32` | Yes | The page height |

**Returns:** `Vec<HocrWord>`


---

### post_process_table()

Post-process a raw table grid to validate structure and clean up.

Returns `None` if the table fails structural validation.

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

```rust
pub fn post_process_table(table: Vec<Vec<String>>, layout_guided: bool, allow_single_column: bool) -> Option<Vec<Vec<String>>>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `table` | `Vec<Vec<String>>` | Yes | The table |
| `layout_guided` | `bool` | Yes | The layout guided |
| `allow_single_column` | `bool` | Yes | The allow single column |

**Returns:** `Option<Vec<Vec<String>>>`


---

### is_well_formed_table()

Validate whether a reconstructed table grid represents a well-formed table
rather than multi-column prose or a repeated page element.

Returns `true` if the grid looks like a real table, `false` if it should be
rejected and its content emitted as paragraph text instead.

The checks catch cases the layout model misidentifies as tables:
- Multi-column prose split into a grid (detected via row coherence and column uniformity)
- Repeated page elements (headers/footers detected as tables on every page)
- Low-vocabulary repetitive content (same few words in every row)

**Signature:**

```rust
pub fn is_well_formed_table(grid: Vec<Vec<String>>) -> bool
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `grid` | `Vec<Vec<String>>` | Yes | The grid |

**Returns:** `bool`


---

### extract_text_from_pdf()

**Signature:**

```rust
pub fn extract_text_from_pdf(pdf_bytes: &[u8]) -> Result<String, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pdf_bytes` | `Vec<u8>` | Yes | The pdf bytes |

**Returns:** `String`

**Errors:** Returns `Err(Error)`.


---

### extract_text_from_pdf_with_password()

**Signature:**

```rust
pub fn extract_text_from_pdf_with_password(pdf_bytes: &[u8], password: &str) -> Result<String, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pdf_bytes` | `Vec<u8>` | Yes | The pdf bytes |
| `password` | `String` | Yes | The password |

**Returns:** `String`

**Errors:** Returns `Err(Error)`.


---

### extract_text_from_pdf_with_passwords()

**Signature:**

```rust
pub fn extract_text_from_pdf_with_passwords(pdf_bytes: &[u8], passwords: Vec<String>) -> Result<String, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pdf_bytes` | `Vec<u8>` | Yes | The pdf bytes |
| `passwords` | `Vec<String>` | Yes | The passwords |

**Returns:** `String`

**Errors:** Returns `Err(Error)`.


---

### extract_text_and_metadata_from_pdf_document()

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

```rust
pub fn extract_text_and_metadata_from_pdf_document(document: PdfDocument, extraction_config: Option<ExtractionConfig>) -> Result<PdfUnifiedExtractionResult, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `document` | `PdfDocument` | Yes | The PDF document to extract from |
| `extraction_config` | `Option<ExtractionConfig>` | No | Optional extraction configuration for hierarchy and page tracking |

**Returns:** `PdfUnifiedExtractionResult`

**Errors:** Returns `Err(Error)`.


---

### extract_text_from_pdf_document()

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

```rust
pub fn extract_text_from_pdf_document(document: PdfDocument, page_config: Option<PageConfig>, extraction_config: Option<ExtractionConfig>) -> Result<PdfTextExtractionResult, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `document` | `PdfDocument` | Yes | The PDF document to extract text from |
| `page_config` | `Option<PageConfig>` | No | Optional page configuration for boundary tracking and page markers |
| `extraction_config` | `Option<ExtractionConfig>` | No | Optional extraction configuration for hierarchy detection |

**Returns:** `PdfTextExtractionResult`

**Errors:** Returns `Err(Error)`.


---

### serialize_to_toon()

Serialize an `ExtractionResult` to TOON (Token-Oriented Object Notation).

TOON is a token-efficient alternative to JSON for LLM prompts.
Losslessly convertible to/from JSON but uses fewer tokens.

**Signature:**

```rust
pub fn serialize_to_toon(result: ExtractionResult) -> Result<String, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `result` | `ExtractionResult` | Yes | The extraction result |

**Returns:** `String`

**Errors:** Returns `Err(Error)`.


---

### serialize_to_json()

Serialize an `ExtractionResult` to pretty-printed JSON.

**Signature:**

```rust
pub fn serialize_to_json(result: ExtractionResult) -> Result<String, Error>
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `result` | `ExtractionResult` | Yes | The extraction result |

**Returns:** `String`

**Errors:** Returns `Err(Error)`.


---

## Types

### AccelerationConfig

Hardware acceleration configuration for ONNX Runtime models.

Controls which execution provider (CPU, CoreML, CUDA, TensorRT) is used
for inference in layout detection and embedding generation.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `provider` | `ExecutionProviderType` | `ExecutionProviderType::Auto` | Execution provider to use for ONNX inference. |
| `device_id` | `u32` | `Default::default()` | GPU device ID (for CUDA/TensorRT). Ignored for CPU/CoreML/Auto. |


---

### AnchorProperties

Properties for anchored drawings.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `behind_doc` | `bool` | `Default::default()` | Behind doc |
| `layout_in_cell` | `bool` | `Default::default()` | Layout in cell |
| `relative_height` | `Option<i64>` | `Default::default()` | Relative height |
| `position_h` | `Option<Position>` | `Default::default()` | Position h (position) |
| `position_v` | `Option<Position>` | `Default::default()` | Position v (position) |
| `wrap_type` | `WrapType` | `WrapType::None` | Wrap type (wrap type) |


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
| `mime_type` | `String` | — | Detected MIME type of the file. |
| `result` | `ExtractionResult` | — | Full extraction result for this file. |


---

### ArchiveMetadata

Archive (ZIP/TAR/7Z) metadata.

Extracted from compressed archive files containing file lists and size information.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `format` | `Str` | — | Archive format ("ZIP", "TAR", "7Z", etc.) |
| `file_count` | `usize` | — | Total number of files in the archive |
| `file_list` | `Vec<String>` | — | List of file paths within the archive |
| `total_size` | `usize` | — | Total uncompressed size in bytes |
| `compressed_size` | `Option<usize>` | `None` | Compressed size in bytes (if available) |


---

### Attributes

Element attributes in Djot.

Represents the attributes attached to elements using {.class #id key="value"} syntax.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `id` | `Option<String>` | `Default::default()` | Element ID (#identifier) |
| `classes` | `Vec<String>` | `vec![]` | CSS classes (.class1 .class2) |
| `key_values` | `Vec<StringString>` | `vec![]` | Key-value pairs (key="value") |


---

### BBox

Bounding box in original image coordinates (x1, y1) top-left, (x2, y2) bottom-right.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `x1` | `f32` | — | X1 |
| `y1` | `f32` | — | Y1 |
| `x2` | `f32` | — | X2 |
| `y2` | `f32` | — | Y2 |

#### Methods

##### width()

**Signature:**

```rust
pub fn width(&self) -> f32
```

##### height()

**Signature:**

```rust
pub fn height(&self) -> f32
```

##### area()

**Signature:**

```rust
pub fn area(&self) -> f32
```

##### center()

**Signature:**

```rust
pub fn center(&self) -> F32F32
```

##### intersection_area()

Area of intersection with another bounding box.

**Signature:**

```rust
pub fn intersection_area(&self, other: BBox) -> f32
```

##### iou()

Intersection over Union with another bounding box.

**Signature:**

```rust
pub fn iou(&self, other: BBox) -> f32
```

##### containment_of()

Fraction of `other` that is contained within `self`.
Returns 0.0..=1.0 where 1.0 means `other` is fully inside `self`.

**Signature:**

```rust
pub fn containment_of(&self, other: BBox) -> f32
```

##### page_coverage()

Fraction of page area this bbox covers.

**Signature:**

```rust
pub fn page_coverage(&self, page_width: f32, page_height: f32) -> f32
```

##### fmt()

**Signature:**

```rust
pub fn fmt(&self, f: Formatter) -> Unknown
```


---

### BatchItemResult

Batch item result for processing multiple files

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `file_path` | `String` | — | File path |
| `success` | `bool` | — | Success |
| `result` | `Option<OcrExtractionResult>` | `None` | Result (ocr extraction result) |
| `error` | `Option<String>` | `None` | Error |


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

##### with_config()

Create a new batch processor with custom pool configuration.

Pools are not created immediately but lazily on first access.

**Returns:**

A new `BatchProcessor` configured with the provided settings.

**Signature:**

```rust
pub fn with_config(config: BatchProcessorConfig) -> BatchProcessor
```

##### with_pool_hint()

Create a batch processor with pool sizes optimized for a specific document.

This method uses a `PoolSizeHint` (derived from file size and MIME type)
to create a batch processor with appropriately sized pools. This reduces
memory waste by tailoring pool allocation to actual document complexity.

**Returns:**

A new `BatchProcessor` configured with the hint-based pool sizes

**Signature:**

```rust
pub fn with_pool_hint(hint: PoolSizeHint) -> BatchProcessor
```

##### string_pool()

Get a reference to the string buffer pool.

Creates the pool lazily on first access.
Useful for custom pooling implementations that need direct pool access.

**Signature:**

```rust
pub fn string_pool(&self) -> StringBufferPool
```

##### byte_pool()

Get a reference to the byte buffer pool.

Creates the pool lazily on first access.
Useful for custom pooling implementations that need direct pool access.

**Signature:**

```rust
pub fn byte_pool(&self) -> ByteBufferPool
```

##### config()

Get the current configuration.

**Signature:**

```rust
pub fn config(&self) -> BatchProcessorConfig
```

##### string_pool_size()

Get the number of pooled string buffers currently available.

**Signature:**

```rust
pub fn string_pool_size(&self) -> usize
```

##### byte_pool_size()

Get the number of pooled byte buffers currently available.

**Signature:**

```rust
pub fn byte_pool_size(&self) -> usize
```

##### clear_pools()

Clear all pooled objects, forcing new allocations on next acquire.

Useful for memory-constrained environments or to reclaim memory
after processing large batches.

**Signature:**

```rust
pub fn clear_pools(&self)
```

##### default()

**Signature:**

```rust
pub fn default() -> BatchProcessor
```


---

### BatchProcessorConfig

Configuration for batch processing with pooling optimizations.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `string_pool_size` | `usize` | `10` | Maximum number of string buffers to maintain in the pool |
| `string_buffer_capacity` | `usize` | `8192` | Initial capacity for pooled string buffers in bytes |
| `byte_pool_size` | `usize` | `10` | Maximum number of byte buffers to maintain in the pool |
| `byte_buffer_capacity` | `usize` | `65536` | Initial capacity for pooled byte buffers in bytes |
| `max_concurrent` | `Option<usize>` | `Default::default()` | Maximum concurrent extractions (for concurrency control) |

#### Methods

##### default()

**Signature:**

```rust
pub fn default() -> BatchProcessorConfig
```


---

### BibtexExtractor

BibTeX bibliography extractor.

Parses BibTeX files and extracts structured bibliography data including
entries, authors, publication years, and entry type distribution.

#### Methods

##### default()

**Signature:**

```rust
pub fn default() -> BibtexExtractor
```

##### name()

**Signature:**

```rust
pub fn name(&self) -> String
```

##### version()

**Signature:**

```rust
pub fn version(&self) -> String
```

##### initialize()

**Signature:**

```rust
pub fn initialize(&self)
```

##### shutdown()

**Signature:**

```rust
pub fn shutdown(&self)
```

##### description()

**Signature:**

```rust
pub fn description(&self) -> String
```

##### author()

**Signature:**

```rust
pub fn author(&self) -> String
```

##### extract_bytes()

**Signature:**

```rust
pub fn extract_bytes(&self, content: Vec<u8>, mime_type: String, config: ExtractionConfig) -> InternalDocument
```

##### supported_mime_types()

**Signature:**

```rust
pub fn supported_mime_types(&self) -> Vec<String>
```

##### priority()

**Signature:**

```rust
pub fn priority(&self) -> i32
```


---

### BibtexMetadata

BibTeX bibliography metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `entry_count` | `usize` | `Default::default()` | Number of entry |
| `citation_keys` | `Vec<String>` | `vec![]` | Citation keys |
| `authors` | `Vec<String>` | `vec![]` | Authors |
| `year_range` | `Option<YearRange>` | `Default::default()` | Year range (year range) |
| `entry_types` | `Option<HashMap<String, usize>>` | `HashMap::new()` | Entry types |


---

### BorderStyle

A single border specification.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `style` | `String` | — | Style |
| `size` | `Option<i32>` | `None` | Size in bytes |
| `color` | `Option<String>` | `None` | Color |
| `space` | `Option<i32>` | `None` | Space |


---

### BoundingBox

Bounding box coordinates for element positioning.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `x0` | `f64` | — | Left x-coordinate |
| `y0` | `f64` | — | Bottom y-coordinate |
| `x1` | `f64` | — | Right x-coordinate |
| `y1` | `f64` | — | Top y-coordinate |


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
| `total_files` | `usize` | — | Total number of cached files |
| `total_size_mb` | `f64` | — | Total cache size in megabytes |
| `available_space_mb` | `f64` | — | Available disk space in megabytes |
| `oldest_file_age_days` | `f64` | — | Age of the oldest cached file in days |
| `newest_file_age_days` | `f64` | — | Age of the newest cached file in days |


---

### CellBBox

A cell bounding box within the reconstructed table grid.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `x1` | `f32` | — | X1 |
| `y1` | `f32` | — | Y1 |
| `x2` | `f32` | — | X2 |
| `y2` | `f32` | — | Y2 |


---

### CellBorders

Per-cell borders (4 sides).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `top` | `Option<BorderStyle>` | `Default::default()` | Top (border style) |
| `bottom` | `Option<BorderStyle>` | `Default::default()` | Bottom (border style) |
| `left` | `Option<BorderStyle>` | `Default::default()` | Left (border style) |
| `right` | `Option<BorderStyle>` | `Default::default()` | Right (border style) |


---

### CellMargins

Cell margins (used for both table-level defaults and per-cell overrides).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `top` | `Option<i32>` | `Default::default()` | Top |
| `bottom` | `Option<i32>` | `Default::default()` | Bottom |
| `left` | `Option<i32>` | `Default::default()` | Left |
| `right` | `Option<i32>` | `Default::default()` | Right |


---

### CellProperties

Cell-level properties from `<w:tcPr>`.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `width` | `Option<TableWidth>` | `Default::default()` | Width (table width) |
| `grid_span` | `Option<u32>` | `Default::default()` | Grid span |
| `v_merge` | `Option<VerticalMerge>` | `VerticalMerge::Restart` | V merge (vertical merge) |
| `borders` | `Option<CellBorders>` | `Default::default()` | Borders (cell borders) |
| `shading` | `Option<CellShading>` | `Default::default()` | Shading (cell shading) |
| `margins` | `Option<CellMargins>` | `Default::default()` | Margins (cell margins) |
| `vertical_align` | `Option<String>` | `Default::default()` | Vertical align |
| `text_direction` | `Option<String>` | `Default::default()` | Text direction |
| `no_wrap` | `bool` | `Default::default()` | No wrap |


---

### CellShading

Cell shading/background.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `fill` | `Option<String>` | `Default::default()` | Fill |
| `color` | `Option<String>` | `Default::default()` | Color |
| `val` | `Option<String>` | `Default::default()` | Val |


---

### CfbReader

#### Methods

##### from_bytes()

Open a CFB compound file from raw bytes.

**Signature:**

```rust
pub fn from_bytes(bytes: Vec<u8>) -> CfbReader
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
| `chunk_type` | `ChunkType` | — | Semantic structural classification of this chunk. Assigned by the heuristic classifier based on content patterns and heading context. Defaults to `ChunkType.Unknown` when no rule matches. |
| `embedding` | `Option<Vec<f32>>` | `None` | Optional embedding vector for this chunk. Only populated when `EmbeddingConfig` is provided in chunking configuration. The dimensionality depends on the chosen embedding model. |
| `metadata` | `ChunkMetadata` | — | Metadata about this chunk's position and properties. |


---

### ChunkMetadata

Metadata about a chunk's position in the original document.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `byte_start` | `usize` | — | Byte offset where this chunk starts in the original text (UTF-8 valid boundary). |
| `byte_end` | `usize` | — | Byte offset where this chunk ends in the original text (UTF-8 valid boundary). |
| `token_count` | `Option<usize>` | `None` | Number of tokens in this chunk (if available). This is calculated by the embedding model's tokenizer if embeddings are enabled. |
| `chunk_index` | `usize` | — | Zero-based index of this chunk in the document. |
| `total_chunks` | `usize` | — | Total number of chunks in the document. |
| `first_page` | `Option<usize>` | `None` | First page number this chunk spans (1-indexed). Only populated when page tracking is enabled in extraction configuration. |
| `last_page` | `Option<usize>` | `None` | Last page number this chunk spans (1-indexed, equal to first_page for single-page chunks). Only populated when page tracking is enabled in extraction configuration. |
| `heading_context` | `Option<HeadingContext>` | `None` | Heading context when using Markdown chunker. Contains the heading hierarchy this chunk falls under. Only populated when `ChunkerType.Markdown` is used. |


---

### ChunkingConfig

Chunking configuration.

Configures text chunking for document content, including chunk size,
overlap, trimming behavior, and optional embeddings.

Use `..the default constructor` when constructing to allow for future field additions:

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `max_characters` | `usize` | `1000` | Maximum size per chunk (in units determined by `sizing`). When `sizing` is `Characters` (default), this is the max character count. When using token-based sizing, this is the max token count. Default: 1000 |
| `overlap` | `usize` | `200` | Overlap between chunks (in units determined by `sizing`). Default: 200 |
| `trim` | `bool` | `true` | Whether to trim whitespace from chunk boundaries. Default: true |
| `chunker_type` | `ChunkerType` | `ChunkerType::Text` | Type of chunker to use (Text or Markdown). Default: Text |
| `embedding` | `Option<EmbeddingConfig>` | `Default::default()` | Optional embedding configuration for chunk embeddings. |
| `preset` | `Option<String>` | `Default::default()` | Use a preset configuration (overrides individual settings if provided). |
| `sizing` | `ChunkSizing` | `ChunkSizing::Characters` | How to measure chunk size. Default: `Characters` (Unicode character count). Enable `chunking-tiktoken` or `chunking-tokenizers` features for token-based sizing. |
| `prepend_heading_context` | `bool` | `false` | When `True` and `chunker_type` is `Markdown`, prepend the heading hierarchy path (e.g. `"# Title > ## Section\n\n"`) to each chunk's content string. This is useful for RAG pipelines where each chunk needs self-contained context about its position in the document structure. Default: `False` |

#### Methods

##### with_chunker_type()

Set the chunker type.

**Signature:**

```rust
pub fn with_chunker_type(&self, chunker_type: ChunkerType) -> ChunkingConfig
```

##### with_sizing()

Set the sizing strategy.

**Signature:**

```rust
pub fn with_sizing(&self, sizing: ChunkSizing) -> ChunkingConfig
```

##### with_prepend_heading_context()

Enable or disable prepending heading context to chunk content.

**Signature:**

```rust
pub fn with_prepend_heading_context(&self, prepend: bool) -> ChunkingConfig
```

##### default()

**Signature:**

```rust
pub fn default() -> ChunkingConfig
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

```rust
pub fn name(&self) -> String
```

##### version()

**Signature:**

```rust
pub fn version(&self) -> String
```

##### initialize()

**Signature:**

```rust
pub fn initialize(&self)
```

##### shutdown()

**Signature:**

```rust
pub fn shutdown(&self)
```

##### process()

**Signature:**

```rust
pub fn process(&self, result: ExtractionResult, config: ExtractionConfig)
```

##### processing_stage()

**Signature:**

```rust
pub fn processing_stage(&self) -> ProcessingStage
```

##### should_process()

**Signature:**

```rust
pub fn should_process(&self, result: ExtractionResult, config: ExtractionConfig) -> bool
```

##### estimated_duration_ms()

**Signature:**

```rust
pub fn estimated_duration_ms(&self, result: ExtractionResult) -> u64
```


---

### ChunkingResult

Result of a text chunking operation.

Contains the generated chunks and metadata about the chunking.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `chunks` | `Vec<Chunk>` | — | List of text chunks |
| `chunk_count` | `usize` | — | Total number of chunks generated |


---

### CitationExtractor

Citation format extractor for RIS, PubMed/MEDLINE, and EndNote XML formats.

Parses citation files and extracts structured bibliography data including
entries, authors, publication years, and format-specific metadata.

#### Methods

##### default()

**Signature:**

```rust
pub fn default() -> CitationExtractor
```

##### name()

**Signature:**

```rust
pub fn name(&self) -> String
```

##### version()

**Signature:**

```rust
pub fn version(&self) -> String
```

##### initialize()

**Signature:**

```rust
pub fn initialize(&self)
```

##### shutdown()

**Signature:**

```rust
pub fn shutdown(&self)
```

##### description()

**Signature:**

```rust
pub fn description(&self) -> String
```

##### author()

**Signature:**

```rust
pub fn author(&self) -> String
```

##### extract_bytes()

**Signature:**

```rust
pub fn extract_bytes(&self, content: Vec<u8>, mime_type: String, config: ExtractionConfig) -> InternalDocument
```

##### supported_mime_types()

**Signature:**

```rust
pub fn supported_mime_types(&self) -> Vec<String>
```

##### priority()

**Signature:**

```rust
pub fn priority(&self) -> i32
```


---

### CitationMetadata

Citation file metadata (RIS, PubMed, EndNote).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `citation_count` | `usize` | `Default::default()` | Number of citation |
| `format` | `Option<String>` | `Default::default()` | Format |
| `authors` | `Vec<String>` | `vec![]` | Authors |
| `year_range` | `Option<YearRange>` | `Default::default()` | Year range (year range) |
| `dois` | `Vec<String>` | `vec![]` | Dois |
| `keywords` | `Vec<String>` | `vec![]` | Keywords |


---

### CodeExtractor

Source code extractor using tree-sitter language pack.

Detects the programming language from the file extension or shebang line,
then uses tree-sitter to parse and extract structural information.

#### Methods

##### default()

**Signature:**

```rust
pub fn default() -> CodeExtractor
```

##### name()

**Signature:**

```rust
pub fn name(&self) -> String
```

##### version()

**Signature:**

```rust
pub fn version(&self) -> String
```

##### initialize()

**Signature:**

```rust
pub fn initialize(&self)
```

##### shutdown()

**Signature:**

```rust
pub fn shutdown(&self)
```

##### description()

**Signature:**

```rust
pub fn description(&self) -> String
```

##### author()

**Signature:**

```rust
pub fn author(&self) -> String
```

##### extract_bytes()

**Signature:**

```rust
pub fn extract_bytes(&self, content: Vec<u8>, mime_type: String, config: ExtractionConfig) -> InternalDocument
```

##### extract_file()

**Signature:**

```rust
pub fn extract_file(&self, path: PathBuf, mime_type: String, config: ExtractionConfig) -> InternalDocument
```

##### supported_mime_types()

**Signature:**

```rust
pub fn supported_mime_types(&self) -> Vec<String>
```

##### priority()

**Signature:**

```rust
pub fn priority(&self) -> i32
```

##### as_sync_extractor()

**Signature:**

```rust
pub fn as_sync_extractor(&self) -> Option<SyncExtractor>
```

##### extract_sync()

**Signature:**

```rust
pub fn extract_sync(&self, content: Vec<u8>, mime_type: String, config: ExtractionConfig) -> InternalDocument
```


---

### ColorScheme

Color scheme containing all 12 standard Office theme colors.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `name` | `String` | `Default::default()` | Color scheme name. |
| `dk1` | `Option<ThemeColor>` | `ThemeColor::Rgb` | Dark 1 (dark background) color. |
| `lt1` | `Option<ThemeColor>` | `ThemeColor::Rgb` | Light 1 (light background) color. |
| `dk2` | `Option<ThemeColor>` | `ThemeColor::Rgb` | Dark 2 color. |
| `lt2` | `Option<ThemeColor>` | `ThemeColor::Rgb` | Light 2 color. |
| `accent1` | `Option<ThemeColor>` | `ThemeColor::Rgb` | Accent color 1. |
| `accent2` | `Option<ThemeColor>` | `ThemeColor::Rgb` | Accent color 2. |
| `accent3` | `Option<ThemeColor>` | `ThemeColor::Rgb` | Accent color 3. |
| `accent4` | `Option<ThemeColor>` | `ThemeColor::Rgb` | Accent color 4. |
| `accent5` | `Option<ThemeColor>` | `ThemeColor::Rgb` | Accent color 5. |
| `accent6` | `Option<ThemeColor>` | `ThemeColor::Rgb` | Accent color 6. |
| `hlink` | `Option<ThemeColor>` | `ThemeColor::Rgb` | Hyperlink color. |
| `fol_hlink` | `Option<ThemeColor>` | `ThemeColor::Rgb` | Followed hyperlink color. |


---

### ColumnLayout

Column layout configuration.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `count` | `Option<i32>` | `Default::default()` | Number of columns. |
| `space_twips` | `Option<i32>` | `Default::default()` | Space between columns in twips. |
| `equal_width` | `Option<bool>` | `Default::default()` | Whether columns have equal width. |


---

### CommonPdfMetadata

Common metadata fields extracted from a PDF.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `title` | `Option<String>` | `None` | Title |
| `subject` | `Option<String>` | `None` | Subject |
| `authors` | `Option<Vec<String>>` | `None` | Authors |
| `keywords` | `Option<Vec<String>>` | `None` | Keywords |
| `created_at` | `Option<String>` | `None` | Created at |
| `modified_at` | `Option<String>` | `None` | Modified at |
| `created_by` | `Option<String>` | `None` | Created by |


---

### ConcurrencyConfig

Controls thread usage for constrained environments.

Set `max_threads` to cap all internal thread pools (Rayon, ONNX Runtime
intra-op) and batch concurrency to a single limit.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `max_threads` | `Option<usize>` | `Default::default()` | Maximum number of threads for all internal thread pools. Caps Rayon global pool size, ONNX Runtime intra-op threads, and (when `max_concurrent_extractions` is unset) the batch concurrency semaphore. When `None`, system defaults are used. |


---

### ContentFilterConfig

Cross-extractor content filtering configuration.

Controls whether "furniture" content (headers, footers, page numbers,
watermarks, repeating text) is included in or stripped from extraction
results. Applies across all extractors (PDF, DOCX, RTF, ODT, HTML, etc.)
with format-specific implementation.

When `None` on `ExtractionConfig`, each extractor uses its current
default behavior unchanged.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `include_headers` | `bool` | `false` | Include running headers in extraction output. - PDF: Disables top-margin furniture stripping and prevents the layout model from treating `PageHeader`-classified regions as furniture. - DOCX: Includes document headers in text output. - RTF/ODT: Headers already included; this is a no-op when true. - HTML/EPUB: Keeps `<header>` element content. Default: `False` (headers are stripped or excluded). |
| `include_footers` | `bool` | `false` | Include running footers in extraction output. - PDF: Disables bottom-margin furniture stripping and prevents the layout model from treating `PageFooter`-classified regions as furniture. - DOCX: Includes document footers in text output. - RTF/ODT: Footers already included; this is a no-op when true. - HTML/EPUB: Keeps `<footer>` element content. Default: `False` (footers are stripped or excluded). |
| `strip_repeating_text` | `bool` | `true` | Enable the heuristic cross-page repeating text detector. When `True` (default), text that repeats verbatim across a supermajority of pages is classified as furniture and stripped.  Disable this if brand names or repeated headings are being incorrectly removed by the heuristic. Note: when a layout-detection model is active, the model may independently classify page-header / page-footer regions as furniture on a per-page basis. To preserve those regions, set `include_headers = true` and/or `include_footers = true` in addition to disabling this flag. Primarily affects PDF extraction. Default: `True`. |
| `include_watermarks` | `bool` | `false` | Include watermark text in extraction output. - PDF: Keeps watermark artifacts and arXiv identifiers. - Other formats: No effect currently. Default: `False` (watermarks are stripped). |

#### Methods

##### default()

**Signature:**

```rust
pub fn default() -> ContentFilterConfig
```


---

### ContributorRole

JATS contributor with role.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `name` | `String` | — | The name |
| `role` | `Option<String>` | `None` | Role |


---

### CoreProperties

Dublin Core metadata from docProps/core.xml

Contains standard metadata fields defined by the Dublin Core standard
and Office-specific extensions.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `title` | `Option<String>` | `Default::default()` | Document title |
| `subject` | `Option<String>` | `Default::default()` | Document subject/topic |
| `creator` | `Option<String>` | `Default::default()` | Document creator/author |
| `keywords` | `Option<String>` | `Default::default()` | Keywords or tags |
| `description` | `Option<String>` | `Default::default()` | Document description/abstract |
| `last_modified_by` | `Option<String>` | `Default::default()` | User who last modified the document |
| `revision` | `Option<String>` | `Default::default()` | Revision number |
| `created` | `Option<String>` | `Default::default()` | Creation timestamp (ISO 8601) |
| `modified` | `Option<String>` | `Default::default()` | Last modification timestamp (ISO 8601) |
| `category` | `Option<String>` | `Default::default()` | Document category |
| `content_status` | `Option<String>` | `Default::default()` | Content status (Draft, Final, etc.) |
| `language` | `Option<String>` | `Default::default()` | Document language |
| `identifier` | `Option<String>` | `Default::default()` | Unique identifier |
| `version` | `Option<String>` | `Default::default()` | Document version |
| `last_printed` | `Option<String>` | `Default::default()` | Last print timestamp (ISO 8601) |


---

### CsvExtractor

CSV/TSV extractor with proper field parsing.

Replaces raw text passthrough with structured CSV parsing,
producing space-separated text output and populated `tables` field.

#### Methods

##### default()

**Signature:**

```rust
pub fn default() -> CsvExtractor
```

##### name()

**Signature:**

```rust
pub fn name(&self) -> String
```

##### version()

**Signature:**

```rust
pub fn version(&self) -> String
```

##### initialize()

**Signature:**

```rust
pub fn initialize(&self)
```

##### shutdown()

**Signature:**

```rust
pub fn shutdown(&self)
```

##### description()

**Signature:**

```rust
pub fn description(&self) -> String
```

##### author()

**Signature:**

```rust
pub fn author(&self) -> String
```

##### extract_bytes()

**Signature:**

```rust
pub fn extract_bytes(&self, content: Vec<u8>, mime_type: String, config: ExtractionConfig) -> InternalDocument
```

##### supported_mime_types()

**Signature:**

```rust
pub fn supported_mime_types(&self) -> Vec<String>
```

##### priority()

**Signature:**

```rust
pub fn priority(&self) -> i32
```


---

### CsvMetadata

CSV/TSV file metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `row_count` | `usize` | `Default::default()` | Number of row |
| `column_count` | `usize` | `Default::default()` | Number of column |
| `delimiter` | `Option<String>` | `Default::default()` | Delimiter |
| `has_header` | `bool` | `Default::default()` | Whether header |
| `column_types` | `Option<Vec<String>>` | `vec![]` | Column types |


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

```rust
pub fn default() -> DbfExtractor
```

##### name()

**Signature:**

```rust
pub fn name(&self) -> String
```

##### version()

**Signature:**

```rust
pub fn version(&self) -> String
```

##### initialize()

**Signature:**

```rust
pub fn initialize(&self)
```

##### shutdown()

**Signature:**

```rust
pub fn shutdown(&self)
```

##### description()

**Signature:**

```rust
pub fn description(&self) -> String
```

##### author()

**Signature:**

```rust
pub fn author(&self) -> String
```

##### extract_bytes()

**Signature:**

```rust
pub fn extract_bytes(&self, content: Vec<u8>, mime_type: String, config: ExtractionConfig) -> InternalDocument
```

##### supported_mime_types()

**Signature:**

```rust
pub fn supported_mime_types(&self) -> Vec<String>
```

##### priority()

**Signature:**

```rust
pub fn priority(&self) -> i32
```


---

### DbfFieldInfo

dBASE field information.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `name` | `String` | — | The name |
| `field_type` | `String` | — | Field type |


---

### DbfMetadata

dBASE (DBF) file metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `record_count` | `usize` | `Default::default()` | Number of record |
| `field_count` | `usize` | `Default::default()` | Number of field |
| `fields` | `Vec<DbfFieldInfo>` | `vec![]` | Fields |


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

```rust
pub fn push(&self)
```

##### pop()

Pop a level (decrease depth).

**Signature:**

```rust
pub fn pop(&self)
```

##### current_depth()

Get current depth.

**Signature:**

```rust
pub fn current_depth(&self) -> usize
```


---

### DetectTimings

Granular timing breakdown for a single `detect()` call.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `preprocess_ms` | `f64` | `Default::default()` | Time spent in image preprocessing (resize, letterbox, normalize, tensor allocation). |
| `onnx_ms` | `f64` | `Default::default()` | Time for the ONNX `session.run()` call (actual neural network computation). |
| `model_total_ms` | `f64` | `Default::default()` | Total time from start of model call to end of raw output decoding. |
| `postprocess_ms` | `f64` | `Default::default()` | Time spent in postprocessing heuristics (confidence filtering, overlap resolution). |


---

### DetectionResult

Page-level detection result containing all detections and page metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `page_width` | `u32` | — | Page width |
| `page_height` | `u32` | — | Page height |
| `detections` | `Vec<LayoutDetection>` | — | Detections |


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
| `plain_text` | `String` | — | Plain text representation for backwards compatibility |
| `blocks` | `Vec<FormattedBlock>` | — | Structured block-level content |
| `metadata` | `Metadata` | — | Metadata from YAML frontmatter |
| `tables` | `Vec<Table>` | — | Extracted tables as structured data |
| `images` | `Vec<DjotImage>` | — | Extracted images with metadata |
| `links` | `Vec<DjotLink>` | — | Extracted links with URLs |
| `footnotes` | `Vec<Footnote>` | — | Footnote definitions |
| `attributes` | `Vec<StringAttributes>` | — | Attributes mapped by element identifier (if present) |


---

### DjotExtractor

Djot markup extractor with metadata and table support.

Parses Djot documents with YAML frontmatter, extracting:
- Metadata from YAML frontmatter
- Plain text content
- Tables as structured data
- Document structure (headings, links, code blocks)

#### Methods

##### build_internal_document()

Build an `InternalDocument` from jotdown events.

**Signature:**

```rust
pub fn build_internal_document(events: Vec<Event>) -> InternalDocument
```

##### default()

**Signature:**

```rust
pub fn default() -> DjotExtractor
```

##### name()

**Signature:**

```rust
pub fn name(&self) -> String
```

##### version()

**Signature:**

```rust
pub fn version(&self) -> String
```

##### initialize()

**Signature:**

```rust
pub fn initialize(&self)
```

##### shutdown()

**Signature:**

```rust
pub fn shutdown(&self)
```

##### description()

**Signature:**

```rust
pub fn description(&self) -> String
```

##### author()

**Signature:**

```rust
pub fn author(&self) -> String
```

##### extract_bytes()

**Signature:**

```rust
pub fn extract_bytes(&self, content: Vec<u8>, mime_type: String, config: ExtractionConfig) -> InternalDocument
```

##### extract_file()

**Signature:**

```rust
pub fn extract_file(&self, path: PathBuf, mime_type: String, config: ExtractionConfig) -> InternalDocument
```

##### supported_mime_types()

**Signature:**

```rust
pub fn supported_mime_types(&self) -> Vec<String>
```

##### priority()

**Signature:**

```rust
pub fn priority(&self) -> i32
```


---

### DjotImage

Image element in Djot.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `src` | `String` | — | Image source URL or path |
| `alt` | `String` | — | Alternative text |
| `title` | `Option<String>` | `None` | Optional title |
| `attributes` | `Option<Attributes>` | `None` | Element attributes |


---

### DjotLink

Link element in Djot.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `url` | `String` | — | Link URL |
| `text` | `String` | — | Link text content |
| `title` | `Option<String>` | `None` | Optional title |
| `attributes` | `Option<Attributes>` | `None` | Element attributes |


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

##### default()

**Signature:**

```rust
pub fn default() -> DocExtractor
```

##### name()

**Signature:**

```rust
pub fn name(&self) -> String
```

##### version()

**Signature:**

```rust
pub fn version(&self) -> String
```

##### initialize()

**Signature:**

```rust
pub fn initialize(&self)
```

##### shutdown()

**Signature:**

```rust
pub fn shutdown(&self)
```

##### description()

**Signature:**

```rust
pub fn description(&self) -> String
```

##### author()

**Signature:**

```rust
pub fn author(&self) -> String
```

##### extract_bytes()

**Signature:**

```rust
pub fn extract_bytes(&self, content: Vec<u8>, mime_type: String, config: ExtractionConfig) -> InternalDocument
```

##### supported_mime_types()

**Signature:**

```rust
pub fn supported_mime_types(&self) -> Vec<String>
```

##### priority()

**Signature:**

```rust
pub fn priority(&self) -> i32
```


---

### DocMetadata

Metadata extracted from DOC files.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `title` | `Option<String>` | `Default::default()` | Title |
| `subject` | `Option<String>` | `Default::default()` | Subject |
| `author` | `Option<String>` | `Default::default()` | Author |
| `last_author` | `Option<String>` | `Default::default()` | Last author |
| `created` | `Option<String>` | `Default::default()` | Created |
| `modified` | `Option<String>` | `Default::default()` | Modified |
| `revision_number` | `Option<String>` | `Default::default()` | Revision number |


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

```rust
pub fn detect(&self, image: RgbImage) -> OrientationResult
```


---

### DocProperties

Document properties from `<wp:docPr>`.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `id` | `Option<String>` | `Default::default()` | Unique identifier |
| `name` | `Option<String>` | `Default::default()` | The name |
| `description` | `Option<String>` | `Default::default()` | Human-readable description |


---

### DocbookExtractor

DocBook document extractor.

Supports both DocBook 4.x (no namespace) and 5.x (with namespace) formats.

#### Methods

##### default()

**Signature:**

```rust
pub fn default() -> DocbookExtractor
```

##### name()

**Signature:**

```rust
pub fn name(&self) -> String
```

##### version()

**Signature:**

```rust
pub fn version(&self) -> String
```

##### initialize()

**Signature:**

```rust
pub fn initialize(&self)
```

##### shutdown()

**Signature:**

```rust
pub fn shutdown(&self)
```

##### extract_bytes()

**Signature:**

```rust
pub fn extract_bytes(&self, content: Vec<u8>, mime_type: String, config: ExtractionConfig) -> InternalDocument
```

##### supported_mime_types()

**Signature:**

```rust
pub fn supported_mime_types(&self) -> Vec<String>
```

##### priority()

**Signature:**

```rust
pub fn priority(&self) -> i32
```


---

### Document

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `paragraphs` | `Vec<Paragraph>` | `vec![]` | Paragraphs |
| `tables` | `Vec<Table>` | `vec![]` | Tables extracted from the document |
| `headers` | `Vec<HeaderFooter>` | `vec![]` | Headers |
| `footers` | `Vec<HeaderFooter>` | `vec![]` | Footers |
| `footnotes` | `Vec<Note>` | `vec![]` | Footnotes |
| `endnotes` | `Vec<Note>` | `vec![]` | Endnotes |
| `numbering_defs` | `AHashMap` | `Default::default()` | Numbering defs (a hash map) |
| `elements` | `Vec<DocumentElement>` | `vec![]` | Document elements in their original order. |
| `style_catalog` | `Option<StyleCatalog>` | `Default::default()` | Parsed style catalog from `word/styles.xml`, if available. |
| `theme` | `Option<Theme>` | `Default::default()` | Parsed theme from `word/theme/theme1.xml`, if available. |
| `sections` | `Vec<SectionProperties>` | `vec![]` | Section properties parsed from `w:sectPr` elements. |
| `drawings` | `Vec<Drawing>` | `vec![]` | Drawing objects parsed from `w:drawing` elements. |
| `image_relationships` | `AHashMap` | `Default::default()` | Image relationships (rId → target path) for image extraction. |

#### Methods

##### resolve_heading_level()

Resolve heading level for a paragraph style using the StyleCatalog.

Walks the style inheritance chain to find `outline_level`.
Falls back to string-matching on style name/ID if no StyleCatalog is available.
Returns 1-6 (markdown heading levels).

**Signature:**

```rust
pub fn resolve_heading_level(&self, style_id: String) -> Option<u8>
```

##### extract_text()

**Signature:**

```rust
pub fn extract_text(&self) -> String
```

##### to_markdown()

Render the document as markdown.

When `inject_placeholders` is `true`, drawings that reference an image
emit `![alt](image)` placeholders. When `false` they are silently
skipped, which is useful when the caller only wants text.

**Signature:**

```rust
pub fn to_markdown(&self, inject_placeholders: bool) -> String
```

##### to_plain_text()

Render the document as plain text (no markdown formatting).

**Signature:**

```rust
pub fn to_plain_text(&self) -> String
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
| `parent` | `Option<u32>` | `None` | Parent node index (`None` = root-level node). |
| `children` | `Vec<u32>` | — | Child node indices in reading order. |
| `content_layer` | `ContentLayer` | — | Content layer classification. |
| `page` | `Option<u32>` | `None` | Page number where this node starts (1-indexed). |
| `page_end` | `Option<u32>` | `None` | Page number where this node ends (for multi-page tables/sections). |
| `bbox` | `Option<BoundingBox>` | `None` | Bounding box in document coordinates. |
| `annotations` | `Vec<TextAnnotation>` | — | Inline annotations (formatting, links) on this node's text content. Only meaningful for text-carrying nodes; empty for containers. |
| `attributes` | `Option<HashMap<String, String>>` | `None` | Format-specific key-value attributes. Extensible bag for data that doesn't warrant a typed field: CSS classes, LaTeX environment names, Excel cell formulas, slide layout names, etc. |


---

### DocumentRelationship

A resolved relationship between two nodes in the document tree.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `source` | `u32` | — | Source node index (the referencing node). |
| `target` | `u32` | — | Target node index (the referenced node). |
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
| `nodes` | `Vec<DocumentNode>` | `vec![]` | All nodes in document/reading order. |
| `source_format` | `Option<String>` | `Default::default()` | Origin format identifier (e.g. "docx", "pptx", "html", "pdf"). Allows renderers to apply format-aware heuristics when converting the document tree to output formats. |
| `relationships` | `Vec<DocumentRelationship>` | `vec![]` | Resolved relationships between nodes (footnote refs, citations, anchor links, etc.). Populated during derivation from the internal document representation. Empty when no relationships are detected. |

#### Methods

##### with_capacity()

Create a `DocumentStructure` with pre-allocated capacity.

**Signature:**

```rust
pub fn with_capacity(capacity: usize) -> DocumentStructure
```

##### push_node()

Push a node and return its `NodeIndex`.

**Signature:**

```rust
pub fn push_node(&self, node: DocumentNode) -> u32
```

##### add_child()

Add a child to an existing parent node.

Updates both the parent's `children` list and the child's `parent` field.

**Panics:**

Panics if either index is out of bounds.

**Signature:**

```rust
pub fn add_child(&self, parent: u32, child: u32)
```

##### validate()

Validate all node indices are in bounds and parent-child relationships
are bidirectionally consistent.

**Errors:**

Returns a descriptive error string if validation fails.

**Signature:**

```rust
pub fn validate(&self)
```

##### body_roots()

Iterate over root-level body nodes (content_layer == Body, parent == None).

**Signature:**

```rust
pub fn body_roots(&self) -> Iterator
```

##### furniture_roots()

Iterate over root-level furniture nodes (non-Body content_layer, parent == None).

**Signature:**

```rust
pub fn furniture_roots(&self) -> Iterator
```

##### get()

Get a node by index.

**Signature:**

```rust
pub fn get(&self, index: u32) -> Option<DocumentNode>
```

##### len()

Get the total number of nodes.

**Signature:**

```rust
pub fn len(&self) -> usize
```

##### is_empty()

Check if the document structure is empty.

**Signature:**

```rust
pub fn is_empty(&self) -> bool
```

##### default()

**Signature:**

```rust
pub fn default() -> DocumentStructure
```


---

### DocumentStructureBuilder

Builder for constructing `DocumentStructure` trees with automatic
heading-driven section nesting.

The builder maintains an internal section stack: when you push a heading,
it automatically creates a `Group` container and nests subsequent content
under it. Higher-level headings pop deeper sections off the stack.

#### Methods

##### with_capacity()

Create a builder with pre-allocated node capacity.

**Signature:**

```rust
pub fn with_capacity(capacity: usize) -> DocumentStructureBuilder
```

##### source_format()

Set the source format identifier (e.g. "docx", "html", "pptx").

**Signature:**

```rust
pub fn source_format(&self, format: String) -> DocumentStructureBuilder
```

##### build()

Consume the builder and return the constructed `DocumentStructure`.

**Signature:**

```rust
pub fn build(&self) -> DocumentStructure
```

##### push_heading()

Push a heading, creating a `Group` container with automatic section nesting.

Headings at the same or deeper level pop existing sections. Content
pushed after this heading will be nested under its `Group` node.

Returns the `NodeIndex` of the `Group` node (not the heading child).

**Signature:**

```rust
pub fn push_heading(&self, level: u8, text: String, page: Option<u32>, bbox: Option<BoundingBox>) -> u32
```

##### push_paragraph()

Push a paragraph node. Nested under current section if one exists.

**Signature:**

```rust
pub fn push_paragraph(&self, text: String, annotations: Vec<TextAnnotation>, page: Option<u32>, bbox: Option<BoundingBox>) -> u32
```

##### push_list()

Push a list container. Returns the `NodeIndex` to use with `push_list_item`.

**Signature:**

```rust
pub fn push_list(&self, ordered: bool, page: Option<u32>) -> u32
```

##### push_list_item()

Push a list item as a child of the given list node.

**Signature:**

```rust
pub fn push_list_item(&self, list: u32, text: String, page: Option<u32>) -> u32
```

##### push_table()

Push a table node with a structured grid.

**Signature:**

```rust
pub fn push_table(&self, grid: TableGrid, page: Option<u32>, bbox: Option<BoundingBox>) -> u32
```

##### push_table_from_cells()

Push a table from a simple cell grid (`Vec<Vec<String>>`).

Assumes the first row is the header row.

**Signature:**

```rust
pub fn push_table_from_cells(&self, cells: Vec<Vec<String>>, page: Option<u32>) -> u32
```

##### push_code()

Push a code block.

**Signature:**

```rust
pub fn push_code(&self, text: String, language: Option<String>, page: Option<u32>) -> u32
```

##### push_formula()

Push a math formula node.

**Signature:**

```rust
pub fn push_formula(&self, text: String, page: Option<u32>) -> u32
```

##### push_image()

Push an image reference node.

**Signature:**

```rust
pub fn push_image(&self, description: Option<String>, image_index: Option<u32>, page: Option<u32>, bbox: Option<BoundingBox>) -> u32
```

##### push_image_with_src()

Push an image node with source URL.

**Signature:**

```rust
pub fn push_image_with_src(&self, description: Option<String>, src: Option<String>, image_index: Option<u32>, page: Option<u32>, bbox: Option<BoundingBox>) -> u32
```

##### push_quote()

Push a block quote container and enter it.

Subsequent body nodes will be parented under this quote until
`exit_container` is called.

**Signature:**

```rust
pub fn push_quote(&self, page: Option<u32>) -> u32
```

##### push_footnote()

Push a footnote node.

**Signature:**

```rust
pub fn push_footnote(&self, text: String, page: Option<u32>) -> u32
```

##### push_page_break()

Push a page break marker (always root-level, never nested under sections).

**Signature:**

```rust
pub fn push_page_break(&self, page: Option<u32>) -> u32
```

##### push_slide()

Push a slide container (PPTX) and enter it.

Clears the section stack and container stack so the slide starts
fresh. Subsequent body nodes will be parented under this slide
until `exit_container` is called or a new
slide is pushed.

**Signature:**

```rust
pub fn push_slide(&self, number: u32, title: Option<String>) -> u32
```

##### push_definition_list()

Push a definition list container. Use `push_definition_item` for entries.

**Signature:**

```rust
pub fn push_definition_list(&self, page: Option<u32>) -> u32
```

##### push_definition_item()

Push a definition item as a child of the given definition list.

**Signature:**

```rust
pub fn push_definition_item(&self, list: u32, term: String, definition: String, page: Option<u32>) -> u32
```

##### push_citation()

Push a citation / bibliographic reference.

**Signature:**

```rust
pub fn push_citation(&self, key: String, text: String, page: Option<u32>) -> u32
```

##### push_admonition()

Push an admonition container (note, warning, tip, etc.) and enter it.

Subsequent body nodes will be parented under this admonition until
`exit_container` is called.

**Signature:**

```rust
pub fn push_admonition(&self, kind: String, title: Option<String>, page: Option<u32>) -> u32
```

##### push_raw_block()

Push a raw block preserved verbatim from the source format.

**Signature:**

```rust
pub fn push_raw_block(&self, format: String, content: String, page: Option<u32>) -> u32
```

##### push_metadata_block()

Push a metadata block (email headers, frontmatter key-value pairs).

**Signature:**

```rust
pub fn push_metadata_block(&self, entries: Vec<StringString>, page: Option<u32>) -> u32
```

##### push_header()

Push a header paragraph (running page header).

**Signature:**

```rust
pub fn push_header(&self, text: String, page: Option<u32>) -> u32
```

##### push_footer()

Push a footer paragraph (running page footer).

**Signature:**

```rust
pub fn push_footer(&self, text: String, page: Option<u32>) -> u32
```

##### set_attributes()

Set format-specific attributes on an existing node.

**Signature:**

```rust
pub fn set_attributes(&self, index: u32, attrs: AHashMap)
```

##### add_child()

Add a child node to an existing parent (for container nodes like Quote, Slide, Admonition).

**Signature:**

```rust
pub fn add_child(&self, parent: u32, child: u32)
```

##### push_raw()

Push a raw `NodeContent` with full control over content layer and annotations.
Nests under current section unless the content type is a root-level type.

**Signature:**

```rust
pub fn push_raw(&self, content: NodeContent, page: Option<u32>, bbox: Option<BoundingBox>, layer: ContentLayer, annotations: Vec<TextAnnotation>) -> u32
```

##### clear_sections()

Reset the section stack (e.g. when starting a new page).

**Signature:**

```rust
pub fn clear_sections(&self)
```

##### enter_container()

Manually push a node onto the container stack.

Subsequent body nodes will be parented under this container
until `exit_container` is called.

**Signature:**

```rust
pub fn enter_container(&self, container: u32)
```

##### exit_container()

Pop the most recent container from the container stack.

Body nodes will resume parenting under the next container on the
stack, or under the section stack if the container stack is empty.

**Signature:**

```rust
pub fn exit_container(&self)
```

##### default()

**Signature:**

```rust
pub fn default() -> DocumentStructureBuilder
```


---

### DocxAppProperties

Application properties from docProps/app.xml for DOCX

Contains Word-specific document statistics and metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `application` | `Option<String>` | `Default::default()` | Application name (e.g., "Microsoft Office Word") |
| `app_version` | `Option<String>` | `Default::default()` | Application version |
| `template` | `Option<String>` | `Default::default()` | Template filename |
| `total_time` | `Option<i32>` | `Default::default()` | Total editing time in minutes |
| `pages` | `Option<i32>` | `Default::default()` | Number of pages |
| `words` | `Option<i32>` | `Default::default()` | Number of words |
| `characters` | `Option<i32>` | `Default::default()` | Number of characters (excluding spaces) |
| `characters_with_spaces` | `Option<i32>` | `Default::default()` | Number of characters (including spaces) |
| `lines` | `Option<i32>` | `Default::default()` | Number of lines |
| `paragraphs` | `Option<i32>` | `Default::default()` | Number of paragraphs |
| `company` | `Option<String>` | `Default::default()` | Company name |
| `doc_security` | `Option<i32>` | `Default::default()` | Document security level |
| `scale_crop` | `Option<bool>` | `Default::default()` | Scale crop flag |
| `links_up_to_date` | `Option<bool>` | `Default::default()` | Links up to date flag |
| `shared_doc` | `Option<bool>` | `Default::default()` | Shared document flag |
| `hyperlinks_changed` | `Option<bool>` | `Default::default()` | Hyperlinks changed flag |


---

### DocxExtractor

High-performance DOCX extractor.

This extractor provides:
- Fast text extraction via streaming XML parsing
- Comprehensive metadata extraction (core.xml, app.xml, custom.xml)

#### Methods

##### default()

**Signature:**

```rust
pub fn default() -> DocxExtractor
```

##### name()

**Signature:**

```rust
pub fn name(&self) -> String
```

##### version()

**Signature:**

```rust
pub fn version(&self) -> String
```

##### initialize()

**Signature:**

```rust
pub fn initialize(&self)
```

##### shutdown()

**Signature:**

```rust
pub fn shutdown(&self)
```

##### description()

**Signature:**

```rust
pub fn description(&self) -> String
```

##### author()

**Signature:**

```rust
pub fn author(&self) -> String
```

##### extract_bytes()

**Signature:**

```rust
pub fn extract_bytes(&self, content: Vec<u8>, mime_type: String, config: ExtractionConfig) -> InternalDocument
```

##### supported_mime_types()

**Signature:**

```rust
pub fn supported_mime_types(&self) -> Vec<String>
```

##### priority()

**Signature:**

```rust
pub fn priority(&self) -> i32
```


---

### DocxMetadata

Word document metadata.

Extracted from DOCX files using shared Office Open XML metadata extraction.
Integrates with `office_metadata` module for core/app/custom properties.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `core_properties` | `Option<CoreProperties>` | `None` | Core properties from docProps/core.xml (Dublin Core metadata) Contains title, creator, subject, keywords, dates, etc. Shared format across DOCX/PPTX/XLSX documents. |
| `app_properties` | `Option<DocxAppProperties>` | `None` | Application properties from docProps/app.xml (Word-specific statistics) Contains word count, page count, paragraph count, editing time, etc. DOCX-specific variant of Office application properties. |
| `custom_properties` | `Option<HashMap<String, serde_json::Value>>` | `None` | Custom properties from docProps/custom.xml (user-defined properties) Contains key-value pairs defined by users or applications. Values can be strings, numbers, booleans, or dates. |


---

### Drawing

A drawing object extracted from `<w:drawing>`.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `drawing_type` | `DrawingType` | — | Drawing type (drawing type) |
| `extent` | `Option<Extent>` | `None` | Extent (extent) |
| `doc_properties` | `Option<DocProperties>` | `None` | Doc properties (doc properties) |
| `image_ref` | `Option<String>` | `None` | Image ref |


---

### Element

Semantic element extracted from document.

Represents a logical unit of content with semantic classification,
unique identifier, and metadata for tracking origin and position.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `element_id` | `ElementId` | — | Unique element identifier |
| `element_type` | `ElementType` | — | Semantic type of this element |
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

```rust
pub fn new(hex_str: String) -> ElementId
```

##### as_ref()

**Signature:**

```rust
pub fn as_ref(&self) -> String
```

##### fmt()

**Signature:**

```rust
pub fn fmt(&self, f: Formatter) -> Unknown
```


---

### ElementMetadata

Metadata for a semantic element.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `page_number` | `Option<usize>` | `None` | Page number (1-indexed) |
| `filename` | `Option<String>` | `None` | Source filename or document name |
| `coordinates` | `Option<BoundingBox>` | `None` | Bounding box coordinates if available |
| `element_index` | `Option<usize>` | `None` | Position index in the element sequence |
| `additional` | `HashMap<String, String>` | — | Additional custom metadata |


---

### EmailAttachment

Email attachment representation.

Contains metadata and optionally the content of an email attachment.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `name` | `Option<String>` | `None` | Attachment name (from Content-Disposition header) |
| `filename` | `Option<String>` | `None` | Filename of the attachment |
| `mime_type` | `Option<String>` | `None` | MIME type of the attachment |
| `size` | `Option<usize>` | `None` | Size in bytes |
| `is_image` | `bool` | — | Whether this attachment is an image |
| `data` | `Option<Vec<u8>>` | `None` | Attachment data (if extracted). Uses `bytes.Bytes` for cheap cloning of large buffers. |


---

### EmailConfig

Configuration for email extraction.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `msg_fallback_codepage` | `Option<u32>` | `Default::default()` | Windows codepage number to use when an MSG file contains no codepage property. Defaults to `None`, which falls back to windows-1252. If an unrecognized or invalid codepage number is supplied (including 0), the behavior silently falls back to windows-1252 — the same as when the MSG file itself contains an unrecognized codepage. No error or warning is emitted. Users should verify output when supplying unusual values. Common values: - 1250: Central European (Polish, Czech, Hungarian, etc.) - 1251: Cyrillic (Russian, Ukrainian, Bulgarian, etc.) - 1252: Western European (default) - 1253: Greek - 1254: Turkish - 1255: Hebrew - 1256: Arabic - 932:  Japanese (Shift-JIS) - 936:  Simplified Chinese (GBK) |


---

### EmailExtractionResult

Email extraction result.

Complete representation of an extracted email message (.eml or .msg)
including headers, body content, and attachments.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `subject` | `Option<String>` | `None` | Email subject line |
| `from_email` | `Option<String>` | `None` | Sender email address |
| `to_emails` | `Vec<String>` | — | Primary recipient email addresses |
| `cc_emails` | `Vec<String>` | — | CC recipient email addresses |
| `bcc_emails` | `Vec<String>` | — | BCC recipient email addresses |
| `date` | `Option<String>` | `None` | Email date/timestamp |
| `message_id` | `Option<String>` | `None` | Message-ID header value |
| `plain_text` | `Option<String>` | `None` | Plain text version of the email body |
| `html_content` | `Option<String>` | `None` | HTML version of the email body |
| `cleaned_text` | `String` | — | Cleaned/processed text content |
| `attachments` | `Vec<EmailAttachment>` | — | List of email attachments |
| `metadata` | `HashMap<String, String>` | — | Additional email headers and metadata |


---

### EmailExtractor

Email message extractor.

Supports: .eml, .msg

#### Methods

##### default()

**Signature:**

```rust
pub fn default() -> EmailExtractor
```

##### name()

**Signature:**

```rust
pub fn name(&self) -> String
```

##### version()

**Signature:**

```rust
pub fn version(&self) -> String
```

##### initialize()

**Signature:**

```rust
pub fn initialize(&self)
```

##### shutdown()

**Signature:**

```rust
pub fn shutdown(&self)
```

##### extract_sync()

**Signature:**

```rust
pub fn extract_sync(&self, content: Vec<u8>, mime_type: String, config: ExtractionConfig) -> InternalDocument
```

##### extract_bytes()

**Signature:**

```rust
pub fn extract_bytes(&self, content: Vec<u8>, mime_type: String, config: ExtractionConfig) -> InternalDocument
```

##### supported_mime_types()

**Signature:**

```rust
pub fn supported_mime_types(&self) -> Vec<String>
```

##### priority()

**Signature:**

```rust
pub fn priority(&self) -> i32
```

##### as_sync_extractor()

**Signature:**

```rust
pub fn as_sync_extractor(&self) -> Option<SyncExtractor>
```


---

### EmailMetadata

Email metadata extracted from .eml and .msg files.

Includes sender/recipient information, message ID, and attachment list.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `from_email` | `Option<String>` | `None` | Sender's email address |
| `from_name` | `Option<String>` | `None` | Sender's display name |
| `to_emails` | `Vec<String>` | — | Primary recipients |
| `cc_emails` | `Vec<String>` | — | CC recipients |
| `bcc_emails` | `Vec<String>` | — | BCC recipients |
| `message_id` | `Option<String>` | `None` | Message-ID header value |
| `attachments` | `Vec<String>` | — | List of attachment filenames |


---

### EmbeddedFile

Embedded file descriptor extracted from the PDF name tree.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `name` | `String` | — | The filename as stored in the PDF name tree. |
| `data` | `Vec<u8>` | — | Raw file bytes from the embedded stream. |
| `mime_type` | `Option<String>` | `None` | MIME type if specified in the filespec, otherwise `None`. |


---

### EmbeddingConfig

Embedding configuration for text chunks.

Configures embedding generation using ONNX models via the vendored embedding engine.
Requires the `embeddings` feature to be enabled.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `model` | `EmbeddingModelType` | `EmbeddingModelType::Preset` | The embedding model to use (defaults to "balanced" preset if not specified) |
| `normalize` | `bool` | `true` | Whether to normalize embedding vectors (recommended for cosine similarity) |
| `batch_size` | `usize` | `32` | Batch size for embedding generation |
| `show_download_progress` | `bool` | `false` | Show model download progress |
| `cache_dir` | `Option<PathBuf>` | `Default::default()` | Custom cache directory for model files Defaults to `~/.cache/kreuzberg/embeddings/` if not specified. Allows full customization of model download location. |

#### Methods

##### default()

**Signature:**

```rust
pub fn default() -> EmbeddingConfig
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
| `chunk_size` | `usize` | — | Chunk size |
| `overlap` | `usize` | — | Overlap |
| `model_repo` | `String` | — | HuggingFace repository name for the model. |
| `pooling` | `String` | — | Pooling strategy: "cls" or "mean". |
| `model_file` | `String` | — | Path to the ONNX model file within the repo. |
| `dimensions` | `usize` | — | Dimensions |
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

```rust
pub fn validate(&self, content: String)
```


---

### EpubExtractor

EPUB format extractor using permissive-licensed dependencies.

Extracts content and metadata from EPUB files (both EPUB2 and EPUB3)
using native Rust parsing without GPL-licensed dependencies.

#### Methods

##### default()

**Signature:**

```rust
pub fn default() -> EpubExtractor
```

##### name()

**Signature:**

```rust
pub fn name(&self) -> String
```

##### version()

**Signature:**

```rust
pub fn version(&self) -> String
```

##### initialize()

**Signature:**

```rust
pub fn initialize(&self)
```

##### shutdown()

**Signature:**

```rust
pub fn shutdown(&self)
```

##### description()

**Signature:**

```rust
pub fn description(&self) -> String
```

##### author()

**Signature:**

```rust
pub fn author(&self) -> String
```

##### extract_bytes()

**Signature:**

```rust
pub fn extract_bytes(&self, content: Vec<u8>, mime_type: String, config: ExtractionConfig) -> InternalDocument
```

##### supported_mime_types()

**Signature:**

```rust
pub fn supported_mime_types(&self) -> Vec<String>
```

##### priority()

**Signature:**

```rust
pub fn priority(&self) -> i32
```


---

### EpubMetadata

EPUB metadata (Dublin Core extensions).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `coverage` | `Option<String>` | `Default::default()` | Coverage |
| `dc_format` | `Option<String>` | `Default::default()` | Dc format |
| `relation` | `Option<String>` | `Default::default()` | Relation |
| `source` | `Option<String>` | `Default::default()` | Source |
| `dc_type` | `Option<String>` | `Default::default()` | Dc type |
| `cover_image` | `Option<String>` | `Default::default()` | Cover image |


---

### ErrorMetadata

Error metadata (for batch operations).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `error_type` | `String` | — | Error type |
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

##### default()

**Signature:**

```rust
pub fn default() -> ExcelExtractor
```

##### name()

**Signature:**

```rust
pub fn name(&self) -> String
```

##### version()

**Signature:**

```rust
pub fn version(&self) -> String
```

##### initialize()

**Signature:**

```rust
pub fn initialize(&self)
```

##### shutdown()

**Signature:**

```rust
pub fn shutdown(&self)
```

##### extract_sync()

**Signature:**

```rust
pub fn extract_sync(&self, content: Vec<u8>, mime_type: String, config: ExtractionConfig) -> InternalDocument
```

##### extract_bytes()

**Signature:**

```rust
pub fn extract_bytes(&self, content: Vec<u8>, mime_type: String, config: ExtractionConfig) -> InternalDocument
```

##### extract_file()

**Signature:**

```rust
pub fn extract_file(&self, path: PathBuf, mime_type: String, config: ExtractionConfig) -> InternalDocument
```

##### supported_mime_types()

**Signature:**

```rust
pub fn supported_mime_types(&self) -> Vec<String>
```

##### priority()

**Signature:**

```rust
pub fn priority(&self) -> i32
```

##### as_sync_extractor()

**Signature:**

```rust
pub fn as_sync_extractor(&self) -> Option<SyncExtractor>
```


---

### ExcelMetadata

Excel/spreadsheet metadata.

Contains information about sheets in Excel, OpenDocument Calc, and other
spreadsheet formats (.xlsx, .xls, .ods, etc.).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `sheet_count` | `usize` | — | Total number of sheets in the workbook |
| `sheet_names` | `Vec<String>` | — | Names of all sheets in order |


---

### ExcelSheet

Single Excel worksheet.

Represents one sheet from an Excel workbook with its content
converted to Markdown format and dimensional statistics.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `name` | `String` | — | Sheet name as it appears in Excel |
| `markdown` | `String` | — | Sheet content converted to Markdown tables |
| `row_count` | `usize` | — | Number of rows |
| `col_count` | `usize` | — | Number of columns |
| `cell_count` | `usize` | — | Total number of non-empty cells |
| `table_cells` | `Option<Vec<Vec<String>>>` | `None` | Pre-extracted table cells (2D vector of cell values) Populated during markdown generation to avoid re-parsing markdown. None for empty sheets. |


---

### ExcelWorkbook

Excel workbook representation.

Contains all sheets from an Excel file (.xlsx, .xls, etc.) with
extracted content and metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `sheets` | `Vec<ExcelSheet>` | — | All sheets in the workbook |
| `metadata` | `HashMap<String, String>` | — | Workbook-level metadata (author, creation date, etc.) |


---

### Extent

Size in EMUs (English Metric Units, 1 inch = 914400 EMU).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `cx` | `i64` | `Default::default()` | Cx |
| `cy` | `i64` | `Default::default()` | Cy |

#### Methods

##### width_inches()

Convert width to inches.

**Signature:**

```rust
pub fn width_inches(&self) -> f64
```

##### height_inches()

Convert height to inches.

**Signature:**

```rust
pub fn height_inches(&self) -> f64
```


---

### ExtractedImage

Extracted image from a document.

Contains raw image data, metadata, and optional nested OCR results.
Raw bytes allow cross-language compatibility - users can convert to
PIL.Image (Python), Sharp (Node.js), or other formats as needed.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `data` | `Vec<u8>` | — | Raw image data (PNG, JPEG, WebP, etc. bytes). Uses `bytes.Bytes` for cheap cloning of large buffers. |
| `format` | `Str` | — | Image format (e.g., "jpeg", "png", "webp") Uses Cow<'static, str> to avoid allocation for static literals. |
| `image_index` | `usize` | — | Zero-indexed position of this image in the document/page |
| `page_number` | `Option<usize>` | `None` | Page/slide number where image was found (1-indexed) |
| `width` | `Option<u32>` | `None` | Image width in pixels |
| `height` | `Option<u32>` | `None` | Image height in pixels |
| `colorspace` | `Option<String>` | `None` | Colorspace information (e.g., "RGB", "CMYK", "Gray") |
| `bits_per_component` | `Option<u32>` | `None` | Bits per color component (e.g., 8, 16) |
| `is_mask` | `bool` | — | Whether this image is a mask image |
| `description` | `Option<String>` | `None` | Optional description of the image |
| `ocr_result` | `Option<ExtractionResult>` | `None` | Nested OCR extraction result (if image was OCRed) When OCR is performed on this image, the result is embedded here rather than in a separate collection, making the relationship explicit. |
| `bounding_box` | `Option<BoundingBox>` | `None` | Bounding box of the image on the page (PDF coordinates: x0=left, y0=bottom, x1=right, y1=top). Only populated for PDF-extracted images when position data is available from pdfium. |
| `source_path` | `Option<String>` | `None` | Original source path of the image within the document archive (e.g., "media/image1.png" in DOCX). Used for rendering image references when the binary data is not extracted. |


---

### ExtractionConfig

Main extraction configuration.

This struct contains all configuration options for the extraction process.
It can be loaded from TOML, YAML, or JSON files, or created programmatically.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `use_cache` | `bool` | `true` | Enable caching of extraction results |
| `enable_quality_processing` | `bool` | `true` | Enable quality post-processing |
| `ocr` | `Option<OcrConfig>` | `Default::default()` | OCR configuration (None = OCR disabled) |
| `force_ocr` | `bool` | `false` | Force OCR even for searchable PDFs |
| `force_ocr_pages` | `Option<Vec<usize>>` | `vec![]` | Force OCR on specific pages only (1-indexed page numbers, must be >= 1). When set, only the listed pages are OCR'd regardless of text layer quality. Unlisted pages use native text extraction. Ignored when `force_ocr` is `True`. Only applies to PDF documents. Duplicates are automatically deduplicated. An `ocr` config is recommended for backend/language selection; defaults are used if absent. |
| `disable_ocr` | `bool` | `false` | Disable OCR entirely, even for images. When `True`, OCR is skipped for all document types. Images return metadata only (dimensions, format, EXIF) without text extraction. PDFs use only native text extraction without OCR fallback. Cannot be `True` simultaneously with `force_ocr`. *Added in v4.7.0.* |
| `chunking` | `Option<ChunkingConfig>` | `Default::default()` | Text chunking configuration (None = chunking disabled) |
| `content_filter` | `Option<ContentFilterConfig>` | `Default::default()` | Content filtering configuration (None = use extractor defaults). Controls whether document "furniture" (headers, footers, watermarks, repeating text) is included in or stripped from extraction results. See `ContentFilterConfig` for per-field documentation. |
| `images` | `Option<ImageExtractionConfig>` | `Default::default()` | Image extraction configuration (None = no image extraction) |
| `pdf_options` | `Option<PdfConfig>` | `Default::default()` | PDF-specific options (None = use defaults) |
| `token_reduction` | `Option<TokenReductionConfig>` | `Default::default()` | Token reduction configuration (None = no token reduction) |
| `language_detection` | `Option<LanguageDetectionConfig>` | `Default::default()` | Language detection configuration (None = no language detection) |
| `pages` | `Option<PageConfig>` | `Default::default()` | Page extraction configuration (None = no page tracking) |
| `postprocessor` | `Option<PostProcessorConfig>` | `Default::default()` | Post-processor configuration (None = use defaults) |
| `html_options` | `Option<ConversionOptions>` | `Default::default()` | HTML to Markdown conversion options (None = use defaults) Configure how HTML documents are converted to Markdown, including heading styles, list formatting, code block styles, and preprocessing options. |
| `html_output` | `Option<HtmlOutputConfig>` | `Default::default()` | Styled HTML output configuration. When set alongside `output_format = OutputFormat.Html`, the extraction pipeline uses `StyledHtmlRenderer` which emits stable `kb-*` CSS class hooks on every structural element and optionally embeds theme CSS or user-supplied CSS in a `<style>` block. When `None`, the existing plain comrak-based HTML renderer is used. |
| `extraction_timeout_secs` | `Option<u64>` | `Default::default()` | Default per-file timeout in seconds for batch extraction. When set, each file in a batch will be canceled after this duration unless overridden by `FileExtractionConfig.timeout_secs`. `None` means no timeout (unbounded extraction time). |
| `max_concurrent_extractions` | `Option<usize>` | `Default::default()` | Maximum concurrent extractions in batch operations (None = (num_cpus × 1.5).ceil()). Limits parallelism to prevent resource exhaustion when processing large batches. Defaults to (num_cpus × 1.5).ceil() when not set. |
| `result_format` | `OutputFormat` | `OutputFormat::Plain` | Result structure format Controls whether results are returned in unified format (default) with all content in the `content` field, or element-based format with semantic elements (for Unstructured-compatible output). |
| `security_limits` | `Option<SecurityLimits>` | `Default::default()` | Security limits for archive extraction. Controls maximum archive size, compression ratio, file count, and other security thresholds to prevent decompression bomb attacks. When `None`, default limits are used (500MB archive, 100:1 ratio, 10K files). |
| `output_format` | `OutputFormat` | `OutputFormat::Plain` | Content text format (default: Plain). Controls the format of the extracted content: - `Plain`: Raw extracted text (default) - `Markdown`: Markdown formatted output - `Djot`: Djot markup format (requires djot feature) - `Html`: HTML formatted output When set to a structured format, extraction results will include formatted output. The `formatted_content` field may be populated when format conversion is applied. |
| `layout` | `Option<LayoutDetectionConfig>` | `Default::default()` | Layout detection configuration (None = layout detection disabled). When set, PDF pages and images are analyzed for document structure (headings, code, formulas, tables, figures, etc.) using RT-DETR models via ONNX Runtime. For PDFs, layout hints override paragraph classification in the markdown pipeline. For images, per-region OCR is performed with markdown formatting based on detected layout classes. Requires the `layout-detection` feature. |
| `include_document_structure` | `bool` | `false` | Enable structured document tree output. When true, populates the `document` field on `ExtractionResult` with a hierarchical `DocumentStructure` containing heading-driven section nesting, table grids, content layer classification, and inline annotations. Independent of `result_format` — can be combined with Unified or ElementBased. |
| `acceleration` | `Option<AccelerationConfig>` | `Default::default()` | Hardware acceleration configuration for ONNX Runtime models. Controls execution provider selection for layout detection and embedding models. When `None`, uses platform defaults (CoreML on macOS, CUDA on Linux, CPU on Windows). |
| `cache_namespace` | `Option<String>` | `Default::default()` | Cache namespace for tenant isolation. When set, cache entries are stored under `{cache_dir}/{namespace}/`. Must be alphanumeric, hyphens, or underscores only (max 64 chars). Different namespaces have isolated cache spaces on the same filesystem. |
| `cache_ttl_secs` | `Option<u64>` | `Default::default()` | Per-request cache TTL in seconds. Overrides the global `max_age_days` for this specific extraction. When `0`, caching is completely skipped (no read or write). When `None`, the global TTL applies. |
| `email` | `Option<EmailConfig>` | `Default::default()` | Email extraction configuration (None = use defaults). Currently supports configuring the fallback codepage for MSG files that do not specify one. See `crate.core.config.EmailConfig` for details. |
| `concurrency` | `Option<ConcurrencyConfig>` | `Default::default()` | Concurrency limits for constrained environments (None = use defaults). Controls Rayon thread pool size, ONNX Runtime intra-op threads, and (when `max_concurrent_extractions` is unset) the batch concurrency semaphore. See `crate.core.config.ConcurrencyConfig` for details. |
| `max_archive_depth` | `usize` | `Default::default()` | Maximum recursion depth for archive extraction (default: 3). Set to 0 to disable recursive extraction (legacy behavior). |
| `tree_sitter` | `Option<TreeSitterConfig>` | `Default::default()` | Tree-sitter language pack configuration (None = tree-sitter disabled). When set, enables code file extraction using tree-sitter parsers. Controls grammar download behavior and code analysis options. |
| `structured_extraction` | `Option<StructuredExtractionConfig>` | `Default::default()` | Structured extraction via LLM (None = disabled). When set, the extracted document content is sent to an LLM with the provided JSON schema. The structured response is stored in `ExtractionResult.structured_output`. |

#### Methods

##### default()

**Signature:**

```rust
pub fn default() -> ExtractionConfig
```

##### with_file_overrides()

Create a new `ExtractionConfig` by applying per-file overrides from a
`FileExtractionConfig`. Fields that are `Some` in the override replace the
corresponding field in `self`; `None` fields keep the original value.

Batch-level fields (`max_concurrent_extractions`, `use_cache`, `acceleration`,
`security_limits`) are never affected by overrides.

**Signature:**

```rust
pub fn with_file_overrides(&self, overrides: FileExtractionConfig) -> ExtractionConfig
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

```rust
pub fn normalized(&self) -> ExtractionConfig
```

##### validate()

Validate the configuration, returning an error if any settings are invalid.

Checks:
- OCR backend name is supported (catches typos early)
- VLM backend config is present when backend is "vlm"
- Pipeline stage backends and VLM configs are valid
- Structured extraction schema and LLM model are non-empty

**Signature:**

```rust
pub fn validate(&self)
```

##### needs_image_processing()

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

```rust
pub fn needs_image_processing(&self) -> bool
```


---

### ExtractionMetrics

Collection of all kreuzberg metric instruments.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `extraction_total` | `Counter` | — | Total extractions (attributes: mime_type, extractor, status). |
| `cache_hits` | `Counter` | — | Cache hits. |
| `cache_misses` | `Counter` | — | Cache misses. |
| `batch_total` | `Counter` | — | Total batch requests (attributes: status). |
| `extraction_duration_ms` | `Histogram` | — | Extraction wall-clock duration in milliseconds (attributes: mime_type, extractor). |
| `extraction_input_bytes` | `Histogram` | — | Input document size in bytes (attributes: mime_type). |
| `extraction_output_bytes` | `Histogram` | — | Output content size in bytes (attributes: mime_type). |
| `pipeline_duration_ms` | `Histogram` | — | Pipeline stage duration in milliseconds (attributes: stage). |
| `ocr_duration_ms` | `Histogram` | — | OCR duration in milliseconds (attributes: backend, language). |
| `batch_duration_ms` | `Histogram` | — | Batch total duration in milliseconds. |
| `concurrent_extractions` | `UpDownCounter` | — | Currently in-flight extractions. |


---

### ExtractionRequest

A request to extract content from a single document.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `source` | `ExtractionSource` | — | Where to read the document from. |
| `config` | `ExtractionConfig` | — | Base extraction configuration. |
| `file_overrides` | `Option<FileExtractionConfig>` | `None` | Optional per-file overrides (merged on top of `config`). |

#### Methods

##### file()

Create a file-based extraction request.

**Signature:**

```rust
pub fn file(path: PathBuf, config: ExtractionConfig) -> ExtractionRequest
```

##### file_with_mime()

Create a file-based extraction request with a MIME type hint.

**Signature:**

```rust
pub fn file_with_mime(path: PathBuf, mime_hint: String, config: ExtractionConfig) -> ExtractionRequest
```

##### bytes()

Create a bytes-based extraction request.

**Signature:**

```rust
pub fn bytes(data: Vec<u8>, mime_type: String, config: ExtractionConfig) -> ExtractionRequest
```

##### with_overrides()

Set per-file overrides on this request.

**Signature:**

```rust
pub fn with_overrides(&self, overrides: FileExtractionConfig) -> ExtractionRequest
```


---

### ExtractionResult

General extraction result used by the core extraction API.

This is the main result type returned by all extraction functions.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `content` | `String` | `Default::default()` | The extracted text content |
| `mime_type` | `Str` | `Default::default()` | The detected MIME type |
| `metadata` | `Metadata` | `Default::default()` | Document metadata |
| `tables` | `Vec<Table>` | `vec![]` | Tables extracted from the document |
| `detected_languages` | `Option<Vec<String>>` | `vec![]` | Detected languages |
| `chunks` | `Option<Vec<Chunk>>` | `vec![]` | Text chunks when chunking is enabled. When chunking configuration is provided, the content is split into overlapping chunks for efficient processing. Each chunk contains the text, optional embeddings (if enabled), and metadata about its position. |
| `images` | `Option<Vec<ExtractedImage>>` | `vec![]` | Extracted images from the document. When image extraction is enabled via `ImageExtractionConfig`, this field contains all images found in the document with their raw data and metadata. Each image may optionally contain a nested `ocr_result` if OCR was performed. |
| `pages` | `Option<Vec<PageContent>>` | `vec![]` | Per-page content when page extraction is enabled. When page extraction is configured, the document is split into per-page content with tables and images mapped to their respective pages. |
| `elements` | `Option<Vec<Element>>` | `vec![]` | Semantic elements when element-based result format is enabled. When result_format is set to ElementBased, this field contains semantic elements with type classification, unique identifiers, and metadata for Unstructured-compatible element-based processing. |
| `djot_content` | `Option<DjotContent>` | `Default::default()` | Rich Djot content structure (when extracting Djot documents). When extracting Djot documents with structured extraction enabled, this field contains the full semantic structure including: - Block-level elements with nesting - Inline formatting with attributes - Links, images, footnotes - Math expressions - Complete attribute information The `content` field still contains plain text for backward compatibility. Always `None` for non-Djot documents. |
| `ocr_elements` | `Option<Vec<OcrElement>>` | `vec![]` | OCR elements with full spatial and confidence metadata. When OCR is performed with element extraction enabled, this field contains the structured representation of detected text including: - Bounding geometry (rectangles or quadrilaterals) - Confidence scores (detection and recognition) - Rotation information - Hierarchical relationships (Tesseract only) This field preserves all metadata that would otherwise be lost when converting to plain text or markdown output formats. Only populated when `OcrElementConfig.include_elements` is true. |
| `document` | `Option<DocumentStructure>` | `Default::default()` | Structured document tree (when document structure extraction is enabled). When `include_document_structure` is true in `ExtractionConfig`, this field contains the full hierarchical representation of the document including: - Heading-driven section nesting - Table grids with cell-level metadata - Content layer classification (body, header, footer, footnote) - Inline text annotations (formatting, links) - Bounding boxes and page numbers Independent of `result_format` — can be combined with Unified or ElementBased. |
| `quality_score` | `Option<f64>` | `Default::default()` | Document quality score from quality analysis. A value between 0.0 and 1.0 indicating the overall text quality. Previously stored in `metadata.additional["quality_score"]`. |
| `processing_warnings` | `Vec<ProcessingWarning>` | `vec![]` | Non-fatal warnings collected during processing pipeline stages. Captures errors from optional pipeline features (embedding, chunking, language detection, output formatting) that don't prevent extraction but may indicate degraded results. Previously stored as individual keys in `metadata.additional`. |
| `annotations` | `Option<Vec<PdfAnnotation>>` | `vec![]` | PDF annotations extracted from the document. When annotation extraction is enabled via `PdfConfig.extract_annotations`, this field contains text notes, highlights, links, stamps, and other annotations found in PDF documents. |
| `children` | `Option<Vec<ArchiveEntry>>` | `vec![]` | Nested extraction results from archive contents. When extracting archives, each processable file inside produces its own full extraction result. Set to `None` for non-archive formats. Use `max_archive_depth` in config to control recursion depth. |
| `uris` | `Option<Vec<Uri>>` | `vec![]` | URIs/links discovered during document extraction. Contains hyperlinks, image references, citations, email addresses, and other URI-like references found in the document. Always extracted when present in the source document. |
| `structured_output` | `Option<serde_json::Value>` | `Default::default()` | Structured extraction output from LLM-based JSON schema extraction. When `structured_extraction` is configured in `ExtractionConfig`, the extracted document content is sent to a VLM with the provided JSON schema. The response is parsed and stored here as a JSON value matching the schema. |
| `code_intelligence` | `Option<ProcessResult>` | `Default::default()` | Code intelligence results from tree-sitter analysis. Populated when extracting source code files with the `tree-sitter` feature. Contains metrics, structural analysis, imports/exports, comments, docstrings, symbols, diagnostics, and optionally chunked code segments. |
| `llm_usage` | `Option<Vec<LlmUsage>>` | `vec![]` | LLM token usage and cost data for all LLM calls made during this extraction. Contains one entry per LLM call. Multiple entries are produced when VLM OCR, structured extraction, and/or LLM embeddings all run during the same extraction. `None` when no LLM was used. |
| `formatted_content` | `Option<String>` | `Default::default()` | Pre-rendered content in the requested output format. Populated during `derive_extraction_result` before tree derivation consumes element data. `apply_output_format` swaps this into `content` at the end of the pipeline, after post-processors have operated on plain text. |
| `ocr_internal_document` | `Option<InternalDocument>` | `Default::default()` | Structured hOCR document for the OCR+layout pipeline. When tesseract produces hOCR output, the parsed `InternalDocument` carries paragraph structure with bounding boxes and confidence scores. The layout classification step enriches these elements before final rendering. |


---

### ExtractionServiceBuilder

Builder for composing an extraction service with Tower middleware layers.

Layers are applied in the order: Tracing → Metrics → Timeout → ConcurrencyLimit → Service.

#### Methods

##### default()

**Signature:**

```rust
pub fn default() -> ExtractionServiceBuilder
```

##### with_timeout()

Add a per-request timeout.

**Signature:**

```rust
pub fn with_timeout(&self, duration: std::time::Duration) -> ExtractionServiceBuilder
```

##### with_concurrency_limit()

Limit concurrent in-flight extractions.

**Signature:**

```rust
pub fn with_concurrency_limit(&self, max: usize) -> ExtractionServiceBuilder
```

##### with_tracing()

Add a tracing span to each extraction request.

**Signature:**

```rust
pub fn with_tracing(&self) -> ExtractionServiceBuilder
```

##### with_metrics()

Add metrics recording to each extraction request.

Requires the `otel` feature. This is a no-op when `otel` is not enabled.

**Signature:**

```rust
pub fn with_metrics(&self) -> ExtractionServiceBuilder
```

##### build()

Build the service stack, returning a type-erased cloneable service.

Layer order (outermost to innermost):
`Tracing → Metrics → Timeout → ConcurrencyLimit → ExtractionService`

**Signature:**

```rust
pub fn build(&self) -> BoxCloneService
```


---

### FictionBookExtractor

FictionBook document extractor.

Supports FictionBook 2.0 format with proper section hierarchy and inline formatting.

#### Methods

##### default()

**Signature:**

```rust
pub fn default() -> FictionBookExtractor
```

##### name()

**Signature:**

```rust
pub fn name(&self) -> String
```

##### version()

**Signature:**

```rust
pub fn version(&self) -> String
```

##### initialize()

**Signature:**

```rust
pub fn initialize(&self)
```

##### shutdown()

**Signature:**

```rust
pub fn shutdown(&self)
```

##### description()

**Signature:**

```rust
pub fn description(&self) -> String
```

##### author()

**Signature:**

```rust
pub fn author(&self) -> String
```

##### extract_bytes()

**Signature:**

```rust
pub fn extract_bytes(&self, content: Vec<u8>, mime_type: String, config: ExtractionConfig) -> InternalDocument
```

##### supported_mime_types()

**Signature:**

```rust
pub fn supported_mime_types(&self) -> Vec<String>
```

##### priority()

**Signature:**

```rust
pub fn priority(&self) -> i32
```


---

### FictionBookMetadata

FictionBook (FB2) metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `genres` | `Vec<String>` | `vec![]` | Genres |
| `sequences` | `Vec<String>` | `vec![]` | Sequences |
| `annotation` | `Option<String>` | `Default::default()` | Annotation |


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

```rust
pub fn deref(&self) -> Vec<u8>
```

##### as_ref()

**Signature:**

```rust
pub fn as_ref(&self) -> Vec<u8>
```


---

### FileExtractionConfig

Per-file extraction configuration overrides for batch processing.

All fields are `Option<T>` — `None` means "use the batch-level default."
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
| `enable_quality_processing` | `Option<bool>` | `Default::default()` | Override quality post-processing for this file. |
| `ocr` | `Option<OcrConfig>` | `Default::default()` | Override OCR configuration for this file (None in the Option = use batch default). |
| `force_ocr` | `Option<bool>` | `Default::default()` | Override force OCR for this file. |
| `force_ocr_pages` | `Option<Vec<usize>>` | `vec![]` | Override force OCR pages for this file (1-indexed page numbers). |
| `disable_ocr` | `Option<bool>` | `Default::default()` | Override disable OCR for this file. |
| `chunking` | `Option<ChunkingConfig>` | `Default::default()` | Override chunking configuration for this file. |
| `content_filter` | `Option<ContentFilterConfig>` | `Default::default()` | Override content filtering configuration for this file. |
| `images` | `Option<ImageExtractionConfig>` | `Default::default()` | Override image extraction configuration for this file. |
| `pdf_options` | `Option<PdfConfig>` | `Default::default()` | Override PDF options for this file. |
| `token_reduction` | `Option<TokenReductionConfig>` | `Default::default()` | Override token reduction for this file. |
| `language_detection` | `Option<LanguageDetectionConfig>` | `Default::default()` | Override language detection for this file. |
| `pages` | `Option<PageConfig>` | `Default::default()` | Override page extraction for this file. |
| `postprocessor` | `Option<PostProcessorConfig>` | `Default::default()` | Override post-processor for this file. |
| `html_options` | `Option<ConversionOptions>` | `Default::default()` | Override HTML conversion options for this file. |
| `result_format` | `Option<OutputFormat>` | `OutputFormat::Plain` | Override result format for this file. |
| `output_format` | `Option<OutputFormat>` | `OutputFormat::Plain` | Override output content format for this file. |
| `include_document_structure` | `Option<bool>` | `Default::default()` | Override document structure output for this file. |
| `layout` | `Option<LayoutDetectionConfig>` | `Default::default()` | Override layout detection for this file. |
| `timeout_secs` | `Option<u64>` | `Default::default()` | Override per-file extraction timeout in seconds. When set, the extraction for this file will be canceled after the specified duration. A timed-out file produces an error result without affecting other files in the batch. |
| `tree_sitter` | `Option<TreeSitterConfig>` | `Default::default()` | Override tree-sitter configuration for this file. |
| `structured_extraction` | `Option<StructuredExtractionConfig>` | `Default::default()` | Override structured extraction configuration for this file. When set, enables LLM-based structured extraction with a JSON schema for this specific file. The extracted content is sent to a VLM/LLM and the response is parsed according to the provided schema. |


---

### FileHeader

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `flags` | `u32` | — | Flags |

#### Methods

##### parse()

**Signature:**

```rust
pub fn parse(data: Vec<u8>) -> FileHeader
```

##### is_compressed()

Whether section streams are zlib/deflate-compressed.

**Signature:**

```rust
pub fn is_compressed(&self) -> bool
```

##### is_encrypted()

Whether the document is password-encrypted.

**Signature:**

```rust
pub fn is_encrypted(&self) -> bool
```

##### is_distribute()

Whether the document is a distribution document (text in ViewText/).

**Signature:**

```rust
pub fn is_distribute(&self) -> bool
```


---

### FontScheme

Font scheme containing major (heading) and minor (body) fonts.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `name` | `String` | `Default::default()` | Font scheme name. |
| `major_latin` | `Option<String>` | `Default::default()` | Major (heading) font - Latin script. |
| `major_east_asian` | `Option<String>` | `Default::default()` | Major (heading) font - East Asian script. |
| `major_complex_script` | `Option<String>` | `Default::default()` | Major (heading) font - Complex script. |
| `minor_latin` | `Option<String>` | `Default::default()` | Minor (body) font - Latin script. |
| `minor_east_asian` | `Option<String>` | `Default::default()` | Minor (body) font - East Asian script. |
| `minor_complex_script` | `Option<String>` | `Default::default()` | Minor (body) font - Complex script. |


---

### Footnote

Footnote in Djot.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `label` | `String` | — | Footnote label |
| `content` | `Vec<FormattedBlock>` | — | Footnote content blocks |


---

### FormattedBlock

Block-level element in a Djot document.

Represents structural elements like headings, paragraphs, lists, code blocks, etc.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `block_type` | `BlockType` | — | Type of block element |
| `level` | `Option<usize>` | `None` | Heading level (1-6) for headings, or nesting level for lists |
| `inline_content` | `Vec<InlineElement>` | — | Inline content within the block |
| `attributes` | `Option<Attributes>` | `None` | Element attributes (classes, IDs, key-value pairs) |
| `language` | `Option<String>` | `None` | Language identifier for code blocks |
| `code` | `Option<String>` | `None` | Raw code content for code blocks |
| `children` | `Vec<FormattedBlock>` | — | Nested blocks for containers (blockquotes, list items, divs) |


---

### GenericCache

#### Methods

##### new()

**Signature:**

```rust
pub fn new(cache_type: String, cache_dir: Option<String>, max_age_days: f64, max_cache_size_mb: f64, min_free_space_mb: f64) -> GenericCache
```

##### get()

**Signature:**

```rust
pub fn get(&self, cache_key: String, source_file: Option<String>, namespace: Option<String>, ttl_override_secs: Option<u64>) -> Option<Vec<u8>>
```

##### get_default()

Backward-compatible get without namespace/TTL.

**Signature:**

```rust
pub fn get_default(&self, cache_key: String, source_file: Option<String>) -> Option<Vec<u8>>
```

##### set()

**Signature:**

```rust
pub fn set(&self, cache_key: String, data: Vec<u8>, source_file: Option<String>, namespace: Option<String>, ttl_secs: Option<u64>)
```

##### set_default()

Backward-compatible set without namespace/TTL.

**Signature:**

```rust
pub fn set_default(&self, cache_key: String, data: Vec<u8>, source_file: Option<String>)
```

##### is_processing()

**Signature:**

```rust
pub fn is_processing(&self, cache_key: String) -> bool
```

##### mark_processing()

**Signature:**

```rust
pub fn mark_processing(&self, cache_key: String)
```

##### mark_complete()

**Signature:**

```rust
pub fn mark_complete(&self, cache_key: String)
```

##### clear()

**Signature:**

```rust
pub fn clear(&self) -> UsizeF64
```

##### delete_namespace()

Delete all cache entries under a namespace.

Removes the namespace subdirectory and all its contents.
Returns (files_removed, mb_freed).

**Signature:**

```rust
pub fn delete_namespace(&self, namespace: String) -> UsizeF64
```

##### get_stats()

**Signature:**

```rust
pub fn get_stats(&self) -> CacheStats
```

##### get_stats_filtered()

Get cache stats, optionally filtered to a specific namespace.

**Signature:**

```rust
pub fn get_stats_filtered(&self, namespace: Option<String>) -> CacheStats
```

##### cache_dir()

**Signature:**

```rust
pub fn cache_dir(&self) -> PathBuf
```

##### cache_type()

**Signature:**

```rust
pub fn cache_type(&self) -> String
```


---

### GridCell

Individual grid cell with position and span metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `content` | `String` | — | Cell text content. |
| `row` | `u32` | — | Zero-indexed row position. |
| `col` | `u32` | — | Zero-indexed column position. |
| `row_span` | `u32` | — | Number of rows this cell spans. |
| `col_span` | `u32` | — | Number of columns this cell spans. |
| `is_header` | `bool` | — | Whether this is a header cell. |
| `bbox` | `Option<BoundingBox>` | `None` | Bounding box for this cell (if available). |


---

### GzipExtractor

Gzip archive extractor.

Decompresses gzip files and extracts text content from the compressed data.

#### Methods

##### default()

**Signature:**

```rust
pub fn default() -> GzipExtractor
```

##### name()

**Signature:**

```rust
pub fn name(&self) -> String
```

##### version()

**Signature:**

```rust
pub fn version(&self) -> String
```

##### initialize()

**Signature:**

```rust
pub fn initialize(&self)
```

##### shutdown()

**Signature:**

```rust
pub fn shutdown(&self)
```

##### description()

**Signature:**

```rust
pub fn description(&self) -> String
```

##### author()

**Signature:**

```rust
pub fn author(&self) -> String
```

##### extract_bytes()

**Signature:**

```rust
pub fn extract_bytes(&self, content: Vec<u8>, mime_type: String, config: ExtractionConfig) -> InternalDocument
```

##### supported_mime_types()

**Signature:**

```rust
pub fn supported_mime_types(&self) -> Vec<String>
```

##### priority()

**Signature:**

```rust
pub fn priority(&self) -> i32
```

##### as_sync_extractor()

**Signature:**

```rust
pub fn as_sync_extractor(&self) -> Option<SyncExtractor>
```

##### extract_sync()

**Signature:**

```rust
pub fn extract_sync(&self, content: Vec<u8>, mime_type: String, config: ExtractionConfig) -> InternalDocument
```


---

### HeaderFooter

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `paragraphs` | `Vec<Paragraph>` | `vec![]` | Paragraphs |
| `tables` | `Vec<Table>` | `vec![]` | Tables extracted from the document |
| `header_type` | `HeaderFooterType` | `HeaderFooterType::Default` | Header type (header footer type) |


---

### HeaderMetadata

Header/heading element metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `level` | `u8` | — | Header level: 1 (h1) through 6 (h6) |
| `text` | `String` | — | Normalized text content of the header |
| `id` | `Option<String>` | `None` | HTML id attribute if present |
| `depth` | `usize` | — | Document tree depth at the header element |
| `html_offset` | `usize` | — | Byte offset in original HTML document |


---

### HeadingContext

Heading context for a chunk within a Markdown document.

Contains the heading hierarchy from document root to this chunk's section.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `headings` | `Vec<HeadingLevel>` | — | The heading hierarchy from document root to this chunk's section. Index 0 is the outermost (h1), last element is the most specific. |


---

### HeadingLevel

A single heading in the hierarchy.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `level` | `u8` | — | Heading depth (1 = h1, 2 = h2, etc.) |
| `text` | `String` | — | The text content of the heading. |


---

### HierarchicalBlock

A text block with hierarchy level assignment.

Represents a block of text with semantic heading information extracted from
font size clustering and hierarchical analysis.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `text` | `String` | — | The text content of this block |
| `font_size` | `f32` | — | The font size of the text in this block |
| `level` | `String` | — | The hierarchy level of this block (H1-H6 or Body) Levels correspond to HTML heading tags: - "h1": Top-level heading - "h2": Secondary heading - "h3": Tertiary heading - "h4": Quaternary heading - "h5": Quinary heading - "h6": Senary heading - "body": Body text (no heading level) |
| `bbox` | `Option<F32F32F32F32>` | `None` | Bounding box information for the block Contains coordinates as (left, top, right, bottom) in PDF units. |


---

### HierarchyConfig

Hierarchy extraction configuration for PDF text structure analysis.

Enables extraction of document hierarchy levels (H1-H6) based on font size
clustering and semantic analysis. When enabled, hierarchical blocks are
included in page content.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `enabled` | `bool` | `true` | Enable hierarchy extraction |
| `k_clusters` | `usize` | `3` | Number of font size clusters to use for hierarchy levels (1-7) Default: 6, which provides H1-H6 heading levels with body text. Larger values create more fine-grained hierarchy levels. |
| `include_bbox` | `bool` | `true` | Include bounding box information in hierarchy blocks |
| `ocr_coverage_threshold` | `Option<f32>` | `Default::default()` | OCR coverage threshold for smart OCR triggering (0.0-1.0) Determines when OCR should be triggered based on text block coverage. OCR is triggered when text blocks cover less than this fraction of the page. Default: 0.5 (trigger OCR if less than 50% of page has text) |

#### Methods

##### default()

**Signature:**

```rust
pub fn default() -> HierarchyConfig
```


---

### HocrWord

Represents a word extracted from hOCR (or any source) with position and confidence information.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `text` | `String` | — | Text |
| `left` | `u32` | — | Left |
| `top` | `u32` | — | Top |
| `width` | `u32` | — | Width |
| `height` | `u32` | — | Height |
| `confidence` | `f64` | — | Confidence |

#### Methods

##### right()

Get the right edge position.

**Signature:**

```rust
pub fn right(&self) -> u32
```

##### bottom()

Get the bottom edge position.

**Signature:**

```rust
pub fn bottom(&self) -> u32
```

##### y_center()

Get the vertical center position.

**Signature:**

```rust
pub fn y_center(&self) -> f64
```

##### x_center()

Get the horizontal center position.

**Signature:**

```rust
pub fn x_center(&self) -> f64
```


---

### HtmlExtractor

HTML document extractor using html-to-markdown.

#### Methods

##### default()

**Signature:**

```rust
pub fn default() -> HtmlExtractor
```

##### name()

**Signature:**

```rust
pub fn name(&self) -> String
```

##### version()

**Signature:**

```rust
pub fn version(&self) -> String
```

##### initialize()

**Signature:**

```rust
pub fn initialize(&self)
```

##### shutdown()

**Signature:**

```rust
pub fn shutdown(&self)
```

##### extract_sync()

**Signature:**

```rust
pub fn extract_sync(&self, content: Vec<u8>, mime_type: String, config: ExtractionConfig) -> InternalDocument
```

##### extract_bytes()

**Signature:**

```rust
pub fn extract_bytes(&self, content: Vec<u8>, mime_type: String, config: ExtractionConfig) -> InternalDocument
```

##### supported_mime_types()

**Signature:**

```rust
pub fn supported_mime_types(&self) -> Vec<String>
```

##### priority()

**Signature:**

```rust
pub fn priority(&self) -> i32
```

##### as_sync_extractor()

**Signature:**

```rust
pub fn as_sync_extractor(&self) -> Option<SyncExtractor>
```


---

### HtmlMetadata

HTML metadata extracted from HTML documents.

Includes document-level metadata, Open Graph data, Twitter Card metadata,
and extracted structural elements (headers, links, images, structured data).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `title` | `Option<String>` | `Default::default()` | Document title from `<title>` tag |
| `description` | `Option<String>` | `Default::default()` | Document description from `<meta name="description">` tag |
| `keywords` | `Vec<String>` | `vec![]` | Document keywords from `<meta name="keywords">` tag, split on commas |
| `author` | `Option<String>` | `Default::default()` | Document author from `<meta name="author">` tag |
| `canonical_url` | `Option<String>` | `Default::default()` | Canonical URL from `<link rel="canonical">` tag |
| `base_href` | `Option<String>` | `Default::default()` | Base URL from `<base href="">` tag for resolving relative URLs |
| `language` | `Option<String>` | `Default::default()` | Document language from `lang` attribute |
| `text_direction` | `Option<TextDirection>` | `TextDirection::LeftToRight` | Document text direction from `dir` attribute |
| `open_graph` | `HashMap<String, String>` | `HashMap::new()` | Open Graph metadata (og:* properties) for social media Keys like "title", "description", "image", "url", etc. |
| `twitter_card` | `HashMap<String, String>` | `HashMap::new()` | Twitter Card metadata (twitter:* properties) Keys like "card", "site", "creator", "title", "description", "image", etc. |
| `meta_tags` | `HashMap<String, String>` | `HashMap::new()` | Additional meta tags not covered by specific fields Keys are meta name/property attributes, values are content |
| `headers` | `Vec<HeaderMetadata>` | `vec![]` | Extracted header elements with hierarchy |
| `links` | `Vec<LinkMetadata>` | `vec![]` | Extracted hyperlinks with type classification |
| `images` | `Vec<ImageMetadataType>` | `vec![]` | Extracted images with source and dimensions |
| `structured_data` | `Vec<StructuredData>` | `vec![]` | Extracted structured data blocks |

#### Methods

##### is_empty()

Check if metadata is empty (no meaningful content extracted).

**Signature:**

```rust
pub fn is_empty(&self) -> bool
```

##### from()

**Signature:**

```rust
pub fn from(metadata: HtmlMetadata) -> HtmlMetadata
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
| `css` | `Option<String>` | `Default::default()` | Inline CSS string injected into the output after the theme stylesheet. Concatenated after `css_file` content when both are set. |
| `css_file` | `Option<PathBuf>` | `Default::default()` | Path to a CSS file loaded once at renderer construction time. Concatenated before `css` when both are set. |
| `theme` | `HtmlTheme` | `HtmlTheme::Unstyled` | Built-in colour/typography theme. Default: `HtmlTheme.Unstyled`. |
| `class_prefix` | `String` | `Default::default()` | CSS class prefix applied to every emitted class name. Default: `"kb-"`. Change this if your host application already uses classes that start with `kb-`. |
| `embed_css` | `bool` | `true` | When `True` (default), write the resolved CSS into a `<style>` block immediately after the opening `<div class="{prefix}doc">`. Set to `False` to emit only the structural markup and wire up your own stylesheet targeting the `kb-*` class names. |

#### Methods

##### default()

**Signature:**

```rust
pub fn default() -> HtmlOutputConfig
```


---

### HwpDocument

An extracted HWP document, consisting of one or more body-text sections.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `sections` | `Vec<Section>` | `vec![]` | All sections from all BodyText/SectionN streams. |

#### Methods

##### extract_text()

Concatenate the text of every paragraph in every section, separated by
newlines.

**Signature:**

```rust
pub fn extract_text(&self) -> String
```


---

### HwpExtractor

Extractor for Hangul Word Processor (.hwp) files.

Supports HWP 5.0 format, the standard document format in South Korea.

#### Methods

##### default()

**Signature:**

```rust
pub fn default() -> HwpExtractor
```

##### name()

**Signature:**

```rust
pub fn name(&self) -> String
```

##### version()

**Signature:**

```rust
pub fn version(&self) -> String
```

##### initialize()

**Signature:**

```rust
pub fn initialize(&self)
```

##### shutdown()

**Signature:**

```rust
pub fn shutdown(&self)
```

##### description()

**Signature:**

```rust
pub fn description(&self) -> String
```

##### author()

**Signature:**

```rust
pub fn author(&self) -> String
```

##### extract_bytes()

**Signature:**

```rust
pub fn extract_bytes(&self, content: Vec<u8>, mime_type: String, config: ExtractionConfig) -> InternalDocument
```

##### supported_mime_types()

**Signature:**

```rust
pub fn supported_mime_types(&self) -> Vec<String>
```

##### priority()

**Signature:**

```rust
pub fn priority(&self) -> i32
```


---

### ImageDpiConfig

Image extraction DPI configuration (internal use).

**Note:** This is an internal type used for image preprocessing.
For the main extraction configuration, see `crate.core.config.ExtractionConfig`.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `target_dpi` | `i32` | `300` | Target DPI for image normalization |
| `max_image_dimension` | `i32` | `4096` | Maximum image dimension (width or height) |
| `auto_adjust_dpi` | `bool` | `true` | Whether to auto-adjust DPI based on content |
| `min_dpi` | `i32` | `72` | Minimum DPI threshold |
| `max_dpi` | `i32` | `600` | Maximum DPI threshold |

#### Methods

##### default()

**Signature:**

```rust
pub fn default() -> ImageDpiConfig
```


---

### ImageExtractionConfig

Image extraction configuration.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `extract_images` | `bool` | `Default::default()` | Extract images from documents |
| `target_dpi` | `i32` | `Default::default()` | Target DPI for image normalization |
| `max_image_dimension` | `i32` | `Default::default()` | Maximum dimension for images (width or height) |
| `inject_placeholders` | `bool` | `Default::default()` | Whether to inject image reference placeholders into markdown output. When `True` (default), image references like `![Image 1](embedded:p1_i0)` are appended to the markdown. Set to `False` to extract images as data without polluting the markdown output. |
| `auto_adjust_dpi` | `bool` | `Default::default()` | Automatically adjust DPI based on image content |
| `min_dpi` | `i32` | `Default::default()` | Minimum DPI threshold |
| `max_dpi` | `i32` | `Default::default()` | Maximum DPI threshold |


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

```rust
pub fn default() -> ImageExtractor
```

##### name()

**Signature:**

```rust
pub fn name(&self) -> String
```

##### version()

**Signature:**

```rust
pub fn version(&self) -> String
```

##### initialize()

**Signature:**

```rust
pub fn initialize(&self)
```

##### shutdown()

**Signature:**

```rust
pub fn shutdown(&self)
```

##### description()

**Signature:**

```rust
pub fn description(&self) -> String
```

##### author()

**Signature:**

```rust
pub fn author(&self) -> String
```

##### extract_bytes()

**Signature:**

```rust
pub fn extract_bytes(&self, content: Vec<u8>, mime_type: String, config: ExtractionConfig) -> InternalDocument
```

##### supported_mime_types()

**Signature:**

```rust
pub fn supported_mime_types(&self) -> Vec<String>
```

##### priority()

**Signature:**

```rust
pub fn priority(&self) -> i32
```


---

### ImageMetadata

Image metadata extracted from image files.

Includes dimensions, format, and EXIF data.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `width` | `u32` | — | Image width in pixels |
| `height` | `u32` | — | Image height in pixels |
| `format` | `String` | — | Image format (e.g., "PNG", "JPEG", "TIFF") |
| `exif` | `HashMap<String, String>` | — | EXIF metadata tags |


---

### ImageMetadataType

Image element metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `src` | `String` | — | Image source (URL, data URI, or SVG content) |
| `alt` | `Option<String>` | `None` | Alternative text from alt attribute |
| `title` | `Option<String>` | `None` | Title attribute |
| `dimensions` | `Option<U32U32>` | `None` | Image dimensions as (width, height) if available |
| `image_type` | `ImageType` | — | Image type classification |
| `attributes` | `Vec<StringString>` | — | Additional attributes as key-value pairs |


---

### ImageOcrResult

Result of OCR extraction from an image with optional page tracking.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `content` | `String` | — | Extracted text content |
| `boundaries` | `Option<Vec<PageBoundary>>` | `None` | Character byte boundaries per frame (for multi-frame TIFFs) |
| `page_contents` | `Option<Vec<PageContent>>` | `None` | Per-frame content information |


---

### ImagePreprocessingConfig

Image preprocessing configuration for OCR.

These settings control how images are preprocessed before OCR to improve
text recognition quality. Different preprocessing strategies work better
for different document types.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `target_dpi` | `i32` | `300` | Target DPI for the image (300 is standard, 600 for small text). |
| `auto_rotate` | `bool` | `true` | Auto-detect and correct image rotation. |
| `deskew` | `bool` | `true` | Correct skew (tilted images). |
| `denoise` | `bool` | `false` | Remove noise from the image. |
| `contrast_enhance` | `bool` | `false` | Enhance contrast for better text visibility. |
| `binarization_method` | `String` | `"otsu"` | Binarization method: "otsu", "sauvola", "adaptive". |
| `invert_colors` | `bool` | `false` | Invert colors (white text on black → black on white). |

#### Methods

##### default()

**Signature:**

```rust
pub fn default() -> ImagePreprocessingConfig
```


---

### ImagePreprocessingMetadata

Image preprocessing metadata.

Tracks the transformations applied to an image during OCR preprocessing,
including DPI normalization, resizing, and resampling.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `original_dimensions` | `UsizeUsize` | — | Original image dimensions (width, height) in pixels |
| `original_dpi` | `F64F64` | — | Original image DPI (horizontal, vertical) |
| `target_dpi` | `i32` | — | Target DPI from configuration |
| `scale_factor` | `f64` | — | Scaling factor applied to the image |
| `auto_adjusted` | `bool` | — | Whether DPI was auto-adjusted based on content |
| `final_dpi` | `i32` | — | Final DPI after processing |
| `new_dimensions` | `Option<UsizeUsize>` | `None` | New dimensions after resizing (if resized) |
| `resample_method` | `String` | — | Resampling algorithm used ("LANCZOS3", "CATMULLROM", etc.) |
| `dimension_clamped` | `bool` | — | Whether dimensions were clamped to max_image_dimension |
| `calculated_dpi` | `Option<i32>` | `None` | Calculated optimal DPI (if auto_adjust_dpi enabled) |
| `skipped_resize` | `bool` | — | Whether resize was skipped (dimensions already optimal) |
| `resize_error` | `Option<String>` | `None` | Error message if resize failed |


---

### InlineElement

Inline element within a block.

Represents text with formatting, links, images, etc.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `element_type` | `InlineType` | — | Type of inline element |
| `content` | `String` | — | Text content |
| `attributes` | `Option<Attributes>` | `None` | Element attributes |
| `metadata` | `Option<HashMap<String, String>>` | `None` | Additional metadata (e.g., href for links, src/alt for images) |


---

### Instant

A platform-aware instant for measuring elapsed time.

On native targets this delegates to `std.time.Instant`.
On `wasm32` targets it is a zero-cost no-op to avoid the `unreachable` trap.

#### Methods

##### now()

Capture the current instant.

**Signature:**

```rust
pub fn now() -> Instant
```

##### elapsed_secs_f64()

Seconds elapsed since this instant was captured (as `f64`).

**Signature:**

```rust
pub fn elapsed_secs_f64(&self) -> f64
```

##### elapsed_ms()

Milliseconds elapsed since this instant was captured (as `f64`).

**Signature:**

```rust
pub fn elapsed_ms(&self) -> f64
```

##### elapsed_millis()

Milliseconds elapsed as `u128` (mirrors `Duration.as_millis`).

**Signature:**

```rust
pub fn elapsed_millis(&self) -> U128
```


---

### InternalDocument

The internal flat document representation.

All extractors output this structure. It is converted to the public
`ExtractionResult` and
`DocumentStructure` in the pipeline.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `elements` | `Vec<InternalElement>` | — | All elements in reading order. Append-only during extraction. |
| `relationships` | `Vec<Relationship>` | — | Relationships between elements (source index → target). Stored separately from elements for cache-friendly iteration. |
| `source_format` | `Str` | — | Source format identifier (e.g., "pdf", "docx", "html", "markdown"). |
| `metadata` | `Metadata` | — | Document-level metadata (title, author, dates, etc.). |
| `images` | `Vec<ExtractedImage>` | — | Extracted images (binary data). Referenced by index from `ElementKind.Image`. |
| `tables` | `Vec<Table>` | — | Extracted tables (structured data). Referenced by index from `ElementKind.Table`. |
| `uris` | `Vec<Uri>` | — | URIs/links discovered during extraction (hyperlinks, image refs, citations, etc.). |
| `children` | `Option<Vec<ArchiveEntry>>` | `None` | Archive children: fully-extracted results for files within an archive. Only populated by archive extractors (ZIP, TAR, 7z, GZIP) when recursive extraction is enabled. Each entry contains the full `ExtractionResult` for a child file that was extracted through the public pipeline. |
| `mime_type` | `Str` | — | MIME type of the source document (e.g., "application/pdf", "text/html"). |
| `processing_warnings` | `Vec<ProcessingWarning>` | — | Non-fatal warnings collected during extraction. |
| `annotations` | `Option<Vec<PdfAnnotation>>` | `None` | PDF annotations (links, highlights, notes). |
| `prebuilt_pages` | `Option<Vec<PageContent>>` | `None` | Pre-built per-page content (set by extractors that track page boundaries natively). When populated, `derive_extraction_result` uses this directly instead of attempting to reconstruct pages from element-level page numbers. |
| `pre_rendered_content` | `Option<String>` | `None` | Pre-rendered formatted content produced by the extractor itself. When an extractor has direct access to high-quality formatted output (e.g., html-to-markdown produces GFM markdown), it can store that here to bypass the lossy InternalDocument → renderer round-trip. `derive_extraction_result` will use this directly when the requested output format matches `metadata.output_format`. |

#### Methods

##### push_element()

Push an element and return its index.

**Signature:**

```rust
pub fn push_element(&self, element: InternalElement) -> u32
```

##### push_relationship()

Push a relationship.

**Signature:**

```rust
pub fn push_relationship(&self, relationship: Relationship)
```

##### push_table()

Push a table and return its index (for use in `ElementKind.Table`).

**Signature:**

```rust
pub fn push_table(&self, table: Table) -> u32
```

##### push_image()

Push an image and return its index (for use in `ElementKind.Image`).

**Signature:**

```rust
pub fn push_image(&self, image: ExtractedImage) -> u32
```

##### push_uri()

Push a URI discovered during extraction.
Silently drops URIs beyond `MAX_URIS` to prevent unbounded memory growth.

**Signature:**

```rust
pub fn push_uri(&self, uri: Uri)
```

##### content()

Concatenate all element text into a single string, separated by newlines.

**Signature:**

```rust
pub fn content(&self) -> String
```


---

### InternalDocumentBuilder

Builder for constructing `InternalDocument` with an ergonomic push-based API.

Tracks nesting depth automatically for list and quote containers,
and generates deterministic element IDs via blake3 hashing.

#### Methods

##### source_format()

Set the source format identifier (e.g. "docx", "html", "pptx").

**Signature:**

```rust
pub fn source_format(&self, format: Str)
```

##### set_metadata()

Set document-level metadata.

**Signature:**

```rust
pub fn set_metadata(&self, metadata: Metadata)
```

##### set_mime_type()

Set the MIME type of the source document.

**Signature:**

```rust
pub fn set_mime_type(&self, mime_type: Str)
```

##### add_warning()

Add a non-fatal processing warning.

**Signature:**

```rust
pub fn add_warning(&self, warning: ProcessingWarning)
```

##### set_pdf_annotations()

Set document-level PDF annotations (links, highlights, notes).

**Signature:**

```rust
pub fn set_pdf_annotations(&self, annotations: Vec<PdfAnnotation>)
```

##### push_uri()

Push a URI discovered during extraction.

**Signature:**

```rust
pub fn push_uri(&self, uri: Uri)
```

##### build()

Consume the builder and return the constructed `InternalDocument`.

**Signature:**

```rust
pub fn build(&self) -> InternalDocument
```

##### push_heading()

Push a heading element.

Auto-sets depth from the heading level and generates an anchor slug
from the heading text.

**Signature:**

```rust
pub fn push_heading(&self, level: u8, text: String, page: Option<u32>, bbox: Option<BoundingBox>) -> u32
```

##### push_paragraph()

Push a paragraph element.

**Signature:**

```rust
pub fn push_paragraph(&self, text: String, annotations: Vec<TextAnnotation>, page: Option<u32>, bbox: Option<BoundingBox>) -> u32
```

##### push_list()

Push a `ListStart` marker and increment depth.

**Signature:**

```rust
pub fn push_list(&self, ordered: bool)
```

##### end_list()

Push a `ListEnd` marker and decrement depth.

**Signature:**

```rust
pub fn end_list(&self)
```

##### push_list_item()

Push a list item element at the current depth.

**Signature:**

```rust
pub fn push_list_item(&self, text: String, ordered: bool, annotations: Vec<TextAnnotation>, page: Option<u32>, bbox: Option<BoundingBox>) -> u32
```

##### push_table()

Push a table element. The table data is stored separately in
`InternalDocument.tables` and referenced by index.

**Signature:**

```rust
pub fn push_table(&self, table: Table, page: Option<u32>, bbox: Option<BoundingBox>) -> u32
```

##### push_table_from_cells()

Push a table element from a 2D cell grid, building a `Table` struct automatically.

**Signature:**

```rust
pub fn push_table_from_cells(&self, cells: Vec<Vec<String>>, page: Option<u32>, bbox: Option<BoundingBox>) -> u32
```

##### push_image()

Push an image element. The image data is stored separately in
`InternalDocument.images` and referenced by index.

**Signature:**

```rust
pub fn push_image(&self, description: Option<String>, image: ExtractedImage, page: Option<u32>, bbox: Option<BoundingBox>) -> u32
```

##### push_code()

Push a code block element. Language is stored in attributes.

**Signature:**

```rust
pub fn push_code(&self, text: String, language: Option<String>, page: Option<u32>, bbox: Option<BoundingBox>) -> u32
```

##### push_formula()

Push a math formula element.

**Signature:**

```rust
pub fn push_formula(&self, text: String, page: Option<u32>, bbox: Option<BoundingBox>) -> u32
```

##### push_footnote_ref()

Push a footnote reference marker.

Creates a `FootnoteRef` element with `anchor = key` and also records
a `Relationship` with `RelationshipTarget.Key(key)` so the derivation
step can resolve it to the definition.

**Signature:**

```rust
pub fn push_footnote_ref(&self, marker: String, key: String, page: Option<u32>) -> u32
```

##### push_footnote_definition()

Push a footnote definition element with `anchor = key`.

**Signature:**

```rust
pub fn push_footnote_definition(&self, text: String, key: String, page: Option<u32>) -> u32
```

##### push_citation()

Push a citation / bibliographic reference element.

**Signature:**

```rust
pub fn push_citation(&self, text: String, key: String, page: Option<u32>) -> u32
```

##### push_quote_start()

Push a `QuoteStart` marker and increment depth.

**Signature:**

```rust
pub fn push_quote_start(&self)
```

##### push_quote_end()

Push a `QuoteEnd` marker and decrement depth.

**Signature:**

```rust
pub fn push_quote_end(&self)
```

##### push_page_break()

Push a page break marker at depth 0.

**Signature:**

```rust
pub fn push_page_break(&self)
```

##### push_slide()

Push a slide element.

**Signature:**

```rust
pub fn push_slide(&self, number: u32, title: Option<String>, page: Option<u32>) -> u32
```

##### push_admonition()

Push an admonition / callout element (note, warning, tip, etc.).
Kind and optional title are stored in attributes.

**Signature:**

```rust
pub fn push_admonition(&self, kind: String, title: Option<String>, page: Option<u32>) -> u32
```

##### push_raw_block()

Push a raw block preserved verbatim. Format is stored in attributes.

**Signature:**

```rust
pub fn push_raw_block(&self, format: String, content: String, page: Option<u32>) -> u32
```

##### push_metadata_block()

Push a structured metadata block (frontmatter, email headers).
Entries are stored in attributes.

**Signature:**

```rust
pub fn push_metadata_block(&self, entries: Vec<StringString>, page: Option<u32>) -> u32
```

##### push_title()

Push a title element.

**Signature:**

```rust
pub fn push_title(&self, text: String, page: Option<u32>, bbox: Option<BoundingBox>) -> u32
```

##### push_definition_term()

Push a definition term element.

**Signature:**

```rust
pub fn push_definition_term(&self, text: String, page: Option<u32>) -> u32
```

##### push_definition_description()

Push a definition description element.

**Signature:**

```rust
pub fn push_definition_description(&self, text: String, page: Option<u32>) -> u32
```

##### push_ocr_text()

Push an OCR text element with OCR-specific fields populated.

**Signature:**

```rust
pub fn push_ocr_text(&self, text: String, level: OcrElementLevel, geometry: OcrBoundingGeometry, confidence: OcrConfidence, rotation: Option<OcrRotation>, page: Option<u32>, bbox: Option<BoundingBox>) -> u32
```

##### push_group_start()

Push a `GroupStart` marker and increment depth.

**Signature:**

```rust
pub fn push_group_start(&self, label: Option<String>, page: Option<u32>)
```

##### push_group_end()

Push a `GroupEnd` marker and decrement depth.

**Signature:**

```rust
pub fn push_group_end(&self)
```

##### push_relationship()

Push a relationship between two elements.

**Signature:**

```rust
pub fn push_relationship(&self, source: u32, target: RelationshipTarget, kind: RelationshipKind)
```

##### set_anchor()

Set the anchor on an already-pushed element.

**Signature:**

```rust
pub fn set_anchor(&self, index: u32, anchor: String)
```

##### set_layer()

Set the content layer on an already-pushed element.

**Signature:**

```rust
pub fn set_layer(&self, index: u32, layer: ContentLayer)
```

##### set_attributes()

Set attributes on an already-pushed element.

**Signature:**

```rust
pub fn set_attributes(&self, index: u32, attributes: AHashMap)
```

##### set_annotations()

Set annotations on an already-pushed element.

**Signature:**

```rust
pub fn set_annotations(&self, index: u32, annotations: Vec<TextAnnotation>)
```

##### set_text()

Set the text content of an already-pushed element.

**Signature:**

```rust
pub fn set_text(&self, index: u32, text: String)
```

##### push_element()

Push a pre-constructed `InternalElement` directly.

Useful when the caller needs to construct an element with fields
that the builder's convenience methods don't cover (e.g. an image
element without `ExtractedImage` data).

**Signature:**

```rust
pub fn push_element(&self, element: InternalElement) -> u32
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
| `depth` | `u16` | — | Nesting depth (0 = root level). Extractors set this based on heading level, list indent, blockquote depth, etc. The tree derivation step uses depth changes to reconstruct parent-child relationships. |
| `page` | `Option<u32>` | `None` | Page number (1-indexed). `None` for non-paginated formats. |
| `bbox` | `Option<BoundingBox>` | `None` | Bounding box in document coordinates. |
| `layer` | `ContentLayer` | — | Content layer classification (Body, Header, Footer, Footnote). |
| `annotations` | `Vec<TextAnnotation>` | — | Inline annotations (formatting, links) on this element's text content. Byte-range based, reuses the existing `TextAnnotation` type. |
| `attributes` | `Option<AHashMap>` | `None` | Format-specific key-value attributes. Used for CSS classes, LaTeX env names, slide layout names, etc. |
| `anchor` | `Option<String>` | `None` | Optional anchor/key for this element. Used by the relationship resolver to match references to targets. Examples: heading slug `"introduction"`, footnote label `"fn1"`, citation key `"smith2024"`, figure label `"fig:diagram"`. |
| `ocr_geometry` | `Option<OcrBoundingGeometry>` | `None` | OCR bounding geometry (rectangle or quadrilateral). |
| `ocr_confidence` | `Option<OcrConfidence>` | `None` | OCR confidence scores (detection + recognition). |
| `ocr_rotation` | `Option<OcrRotation>` | `None` | OCR rotation metadata. |

#### Methods

##### text()

Create a simple text element with minimal fields.

**Signature:**

```rust
pub fn text(kind: ElementKind, text: String, depth: u16) -> InternalElement
```

##### with_page()

Set the page number.

**Signature:**

```rust
pub fn with_page(&self, page: u32) -> InternalElement
```

##### with_bbox()

Set the bounding box.

**Signature:**

```rust
pub fn with_bbox(&self, bbox: BoundingBox) -> InternalElement
```

##### with_layer()

Set the content layer.

**Signature:**

```rust
pub fn with_layer(&self, layer: ContentLayer) -> InternalElement
```

##### with_anchor()

Set the anchor key.

**Signature:**

```rust
pub fn with_anchor(&self, anchor: String) -> InternalElement
```

##### with_annotations()

Set annotations.

**Signature:**

```rust
pub fn with_annotations(&self, annotations: Vec<TextAnnotation>) -> InternalElement
```

##### with_attributes()

Set attributes.

**Signature:**

```rust
pub fn with_attributes(&self, attributes: AHashMap) -> InternalElement
```

##### with_index()

Regenerate the ID with the correct index (call after pushing to the document).

**Signature:**

```rust
pub fn with_index(&self, index: u32) -> InternalElement
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

```rust
pub fn generate(kind_discriminant: String, text: String, page: Option<u32>, index: u32) -> InternalElementId
```

##### as_str()

Get the ID as a string slice.

**Signature:**

```rust
pub fn as_str(&self) -> String
```

##### fmt()

**Signature:**

```rust
pub fn fmt(&self, f: Formatter) -> Unknown
```

##### as_ref()

**Signature:**

```rust
pub fn as_ref(&self) -> String
```


---

### IterationValidator

Helper struct for validating iteration counts.

#### Methods

##### check_iteration()

Validate and increment iteration count.

**Returns:**
* `Ok(())` if count is within limits
* `Err(SecurityError)` if count exceeds limit

**Signature:**

```rust
pub fn check_iteration(&self)
```

##### current_count()

Get current iteration count.

**Signature:**

```rust
pub fn current_count(&self) -> usize
```


---

### JatsExtractor

JATS document extractor.

Supports JATS (Journal Article Tag Suite) XML documents in various versions,
handling both the full article structure and minimal JATS subsets.

#### Methods

##### default()

**Signature:**

```rust
pub fn default() -> JatsExtractor
```

##### name()

**Signature:**

```rust
pub fn name(&self) -> String
```

##### version()

**Signature:**

```rust
pub fn version(&self) -> String
```

##### initialize()

**Signature:**

```rust
pub fn initialize(&self)
```

##### shutdown()

**Signature:**

```rust
pub fn shutdown(&self)
```

##### extract_bytes()

**Signature:**

```rust
pub fn extract_bytes(&self, content: Vec<u8>, mime_type: String, config: ExtractionConfig) -> InternalDocument
```

##### supported_mime_types()

**Signature:**

```rust
pub fn supported_mime_types(&self) -> Vec<String>
```

##### priority()

**Signature:**

```rust
pub fn priority(&self) -> i32
```


---

### JatsMetadata

JATS (Journal Article Tag Suite) metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `copyright` | `Option<String>` | `Default::default()` | Copyright |
| `license` | `Option<String>` | `Default::default()` | License |
| `history_dates` | `HashMap<String, String>` | `HashMap::new()` | History dates |
| `contributor_roles` | `Vec<ContributorRole>` | `vec![]` | Contributor roles |


---

### JsonExtractionConfig

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `extract_schema` | `bool` | `false` | Extract schema |
| `max_depth` | `usize` | `20` | Maximum depth |
| `array_item_limit` | `usize` | `500` | Array item limit |
| `include_type_info` | `bool` | `false` | Include type info |
| `flatten_nested_objects` | `bool` | `true` | Flatten nested objects |
| `custom_text_field_patterns` | `Vec<String>` | `vec![]` | Custom text field patterns |

#### Methods

##### default()

**Signature:**

```rust
pub fn default() -> JsonExtractionConfig
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

```rust
pub fn default() -> JupyterExtractor
```

##### name()

**Signature:**

```rust
pub fn name(&self) -> String
```

##### version()

**Signature:**

```rust
pub fn version(&self) -> String
```

##### initialize()

**Signature:**

```rust
pub fn initialize(&self)
```

##### shutdown()

**Signature:**

```rust
pub fn shutdown(&self)
```

##### description()

**Signature:**

```rust
pub fn description(&self) -> String
```

##### author()

**Signature:**

```rust
pub fn author(&self) -> String
```

##### extract_bytes()

**Signature:**

```rust
pub fn extract_bytes(&self, content: Vec<u8>, mime_type: String, config: ExtractionConfig) -> InternalDocument
```

##### supported_mime_types()

**Signature:**

```rust
pub fn supported_mime_types(&self) -> Vec<String>
```

##### priority()

**Signature:**

```rust
pub fn priority(&self) -> i32
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

```rust
pub fn default() -> KeynoteExtractor
```

##### name()

**Signature:**

```rust
pub fn name(&self) -> String
```

##### version()

**Signature:**

```rust
pub fn version(&self) -> String
```

##### initialize()

**Signature:**

```rust
pub fn initialize(&self)
```

##### shutdown()

**Signature:**

```rust
pub fn shutdown(&self)
```

##### description()

**Signature:**

```rust
pub fn description(&self) -> String
```

##### author()

**Signature:**

```rust
pub fn author(&self) -> String
```

##### extract_bytes()

**Signature:**

```rust
pub fn extract_bytes(&self, content: Vec<u8>, mime_type: String, config: ExtractionConfig) -> InternalDocument
```

##### supported_mime_types()

**Signature:**

```rust
pub fn supported_mime_types(&self) -> Vec<String>
```

##### priority()

**Signature:**

```rust
pub fn priority(&self) -> i32
```


---

### Keyword

Extracted keyword with metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `text` | `String` | — | The keyword text. |
| `score` | `f32` | — | Relevance score (higher is better, algorithm-specific range). |
| `algorithm` | `KeywordAlgorithm` | — | Algorithm that extracted this keyword. |
| `positions` | `Option<Vec<usize>>` | `None` | Optional positions where keyword appears in text (character offsets). |

#### Methods

##### with_positions()

Create a new keyword with positions.

**Signature:**

```rust
pub fn with_positions(text: String, score: f32, algorithm: KeywordAlgorithm, positions: Vec<usize>) -> Keyword
```


---

### KeywordConfig

Keyword extraction configuration.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `algorithm` | `KeywordAlgorithm` | `KeywordAlgorithm::Yake` | Algorithm to use for extraction. |
| `max_keywords` | `usize` | `10` | Maximum number of keywords to extract (default: 10). |
| `min_score` | `f32` | `0` | Minimum score threshold (0.0-1.0, default: 0.0). Keywords with scores below this threshold are filtered out. Note: Score ranges differ between algorithms. |
| `ngram_range` | `UsizeUsize` | `Default::default()` | N-gram range for keyword extraction (min, max). (1, 1) = unigrams only (1, 2) = unigrams and bigrams (1, 3) = unigrams, bigrams, and trigrams (default) |
| `language` | `Option<String>` | `Default::default()` | Language code for stopword filtering (e.g., "en", "de", "fr"). If None, no stopword filtering is applied. |
| `yake_params` | `Option<YakeParams>` | `Default::default()` | YAKE-specific tuning parameters. |
| `rake_params` | `Option<RakeParams>` | `Default::default()` | RAKE-specific tuning parameters. |

#### Methods

##### default()

**Signature:**

```rust
pub fn default() -> KeywordConfig
```

##### with_max_keywords()

Set maximum number of keywords to extract.

**Signature:**

```rust
pub fn with_max_keywords(&self, max: usize) -> KeywordConfig
```

##### with_min_score()

Set minimum score threshold.

**Signature:**

```rust
pub fn with_min_score(&self, score: f32) -> KeywordConfig
```

##### with_ngram_range()

Set n-gram range.

**Signature:**

```rust
pub fn with_ngram_range(&self, min: usize, max: usize) -> KeywordConfig
```

##### with_language()

Set language for stopword filtering.

**Signature:**

```rust
pub fn with_language(&self, lang: String) -> KeywordConfig
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

```rust
pub fn name(&self) -> String
```

##### version()

**Signature:**

```rust
pub fn version(&self) -> String
```

##### initialize()

**Signature:**

```rust
pub fn initialize(&self)
```

##### shutdown()

**Signature:**

```rust
pub fn shutdown(&self)
```

##### process()

**Signature:**

```rust
pub fn process(&self, result: ExtractionResult, config: ExtractionConfig)
```

##### processing_stage()

**Signature:**

```rust
pub fn processing_stage(&self) -> ProcessingStage
```

##### should_process()

**Signature:**

```rust
pub fn should_process(&self, result: ExtractionResult, config: ExtractionConfig) -> bool
```

##### estimated_duration_ms()

**Signature:**

```rust
pub fn estimated_duration_ms(&self, result: ExtractionResult) -> u64
```


---

### LanguageDetectionConfig

Language detection configuration.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `enabled` | `bool` | — | Enable language detection |
| `min_confidence` | `f64` | — | Minimum confidence threshold (0.0-1.0) |
| `detect_multiple` | `bool` | — | Detect multiple languages in the document |


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

```rust
pub fn name(&self) -> String
```

##### version()

**Signature:**

```rust
pub fn version(&self) -> String
```

##### initialize()

**Signature:**

```rust
pub fn initialize(&self)
```

##### shutdown()

**Signature:**

```rust
pub fn shutdown(&self)
```

##### process()

**Signature:**

```rust
pub fn process(&self, result: ExtractionResult, config: ExtractionConfig)
```

##### processing_stage()

**Signature:**

```rust
pub fn processing_stage(&self) -> ProcessingStage
```

##### should_process()

**Signature:**

```rust
pub fn should_process(&self, result: ExtractionResult, config: ExtractionConfig) -> bool
```

##### estimated_duration_ms()

**Signature:**

```rust
pub fn estimated_duration_ms(&self, result: ExtractionResult) -> u64
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

```rust
pub fn global() -> LanguageRegistry
```

##### get_supported_languages()

Get supported languages for a specific OCR backend.

**Returns:**

`Some(&[String])` if the backend is registered, `None` otherwise.

**Signature:**

```rust
pub fn get_supported_languages(&self, backend: String) -> Option<Vec<String>>
```

##### is_language_supported()

Check if a language is supported by a specific backend.

**Returns:**

`true` if the language is supported, `false` otherwise.

**Signature:**

```rust
pub fn is_language_supported(&self, backend: String, language: String) -> bool
```

##### get_backends()

Get all registered backend names.

**Returns:**

A vector of backend names in the registry.

**Signature:**

```rust
pub fn get_backends(&self) -> Vec<String>
```

##### get_language_count()

Get language count for a specific backend.

**Returns:**

Number of supported languages for the backend, or 0 if backend not found.

**Signature:**

```rust
pub fn get_language_count(&self, backend: String) -> usize
```

##### default()

**Signature:**

```rust
pub fn default() -> LanguageRegistry
```


---

### LatexExtractor

LaTeX document extractor

#### Methods

##### build_internal_document()

Build an `InternalDocument` from LaTeX source.

Captures `\label{}` as anchors, `\ref{}` as CrossReference relationships,
`\cite{}` as CitationReference relationships, and footnotes.

**Signature:**

```rust
pub fn build_internal_document(source: String, inject_placeholders: bool) -> InternalDocument
```

##### default()

**Signature:**

```rust
pub fn default() -> LatexExtractor
```

##### name()

**Signature:**

```rust
pub fn name(&self) -> String
```

##### version()

**Signature:**

```rust
pub fn version(&self) -> String
```

##### initialize()

**Signature:**

```rust
pub fn initialize(&self)
```

##### shutdown()

**Signature:**

```rust
pub fn shutdown(&self)
```

##### description()

**Signature:**

```rust
pub fn description(&self) -> String
```

##### author()

**Signature:**

```rust
pub fn author(&self) -> String
```

##### extract_bytes()

**Signature:**

```rust
pub fn extract_bytes(&self, content: Vec<u8>, mime_type: String, config: ExtractionConfig) -> InternalDocument
```

##### extract_file()

**Signature:**

```rust
pub fn extract_file(&self, path: PathBuf, mime_type: String, config: ExtractionConfig) -> InternalDocument
```

##### supported_mime_types()

**Signature:**

```rust
pub fn supported_mime_types(&self) -> Vec<String>
```

##### priority()

**Signature:**

```rust
pub fn priority(&self) -> i32
```


---

### LayoutDetection

A single layout detection result.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `class` | `LayoutClass` | — | Class (layout class) |
| `confidence` | `f32` | — | Confidence |
| `bbox` | `BBox` | — | Bbox (b box) |

#### Methods

##### sort_by_confidence_desc()

Sort detections by confidence in descending order.

**Signature:**

```rust
pub fn sort_by_confidence_desc(detections: Vec<LayoutDetection>)
```

##### fmt()

**Signature:**

```rust
pub fn fmt(&self, f: Formatter) -> Unknown
```


---

### LayoutDetectionConfig

Layout detection configuration.

Controls layout detection behavior in the extraction pipeline.
When set on `ExtractionConfig`, layout detection
is enabled for PDF extraction.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `confidence_threshold` | `Option<f32>` | `Default::default()` | Confidence threshold override (None = use model default). |
| `apply_heuristics` | `bool` | `true` | Whether to apply postprocessing heuristics (default: true). |
| `table_model` | `TableModel` | `TableModel::Tatr` | Table structure recognition model. Controls which model is used for table cell detection within layout-detected table regions. Defaults to `TableModel.Tatr`. |

#### Methods

##### default()

**Signature:**

```rust
pub fn default() -> LayoutDetectionConfig
```


---

### LayoutEngine

High-level layout detection engine.

Wraps model loading, inference, and postprocessing into a single
reusable object. Models are downloaded and cached on first use.

#### Methods

##### from_config()

Create a layout engine from a full config.

**Signature:**

```rust
pub fn from_config(config: LayoutEngineConfig) -> LayoutEngine
```

##### detect()

Run layout detection on an image.

Returns a `DetectionResult` with bounding boxes, classes, and confidence scores.
If `apply_heuristics` is enabled in config, postprocessing is applied automatically.

**Signature:**

```rust
pub fn detect(&self, img: RgbImage) -> DetectionResult
```

##### detect_timed()

Run layout detection on an image and return granular timing data.

Identical to `detect` but also returns a `DetectTimings` breakdown.
Use this when you need per-step profiling (preprocess / onnx / postprocess).

**Signature:**

```rust
pub fn detect_timed(&self, img: RgbImage) -> DetectionResultDetectTimings
```

##### detect_batch()

Run layout detection on a batch of images in a single model call.

Returns one `(DetectionResult, DetectTimings)` tuple per input image.
Postprocessing heuristics are applied per image when enabled in config.

Timing note: `preprocess_ms` and `onnx_ms` in each `DetectTimings` are the
amortized per-image share of the batch operation (total / N), not independent
per-image measurements.

**Signature:**

```rust
pub fn detect_batch(&self, images: Vec<RgbImage>) -> Vec<DetectionResultDetectTimings>
```

##### model_name()

Get the model name.

**Signature:**

```rust
pub fn model_name(&self) -> String
```

##### config()

Return a reference to the engine's configuration.

Used by callers (e.g. parallel layout runners) that need to create
additional engines with identical settings.

**Signature:**

```rust
pub fn config(&self) -> LayoutEngineConfig
```


---

### LayoutEngineConfig

Full configuration for the layout engine.

Provides fine-grained control over model selection, thresholds, and
postprocessing.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `backend` | `ModelBackend` | `ModelBackend::RtDetr` | Which model backend to use. |
| `confidence_threshold` | `Option<f32>` | `Default::default()` | Confidence threshold override (None = use model default). |
| `apply_heuristics` | `bool` | `true` | Whether to apply postprocessing heuristics. |
| `cache_dir` | `Option<PathBuf>` | `Default::default()` | Custom cache directory for model files (None = default). |

#### Methods

##### default()

**Signature:**

```rust
pub fn default() -> LayoutEngineConfig
```


---

### LayoutModel

Common interface for all layout detection model backends.

#### Methods

##### detect()

Run layout detection on an image using the default confidence threshold.

**Signature:**

```rust
pub fn detect(&self, img: RgbImage) -> Vec<LayoutDetection>
```

##### detect_with_threshold()

Run layout detection with a custom confidence threshold.

**Signature:**

```rust
pub fn detect_with_threshold(&self, img: RgbImage, threshold: f32) -> Vec<LayoutDetection>
```

##### detect_batch()

Run layout detection on a batch of images in a single model call.

Returns one `Vec<LayoutDetection>` per input image (same order).
`threshold` overrides the model's default confidence cutoff when `Some`.

The default implementation is a sequential fallback: models that support
true batched inference (e.g. `rtdetr.RtDetrModel`) override this.

**Signature:**

```rust
pub fn detect_batch(&self, images: Vec<RgbImage>, threshold: Option<f32>) -> Vec<Vec<LayoutDetection>>
```

##### name()

Human-readable model name.

**Signature:**

```rust
pub fn name(&self) -> String
```


---

### LayoutTimingReport

Timing breakdown for the entire layout detection run.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `total_ms` | `f64` | — | Total ms |
| `per_page` | `Vec<PageTiming>` | — | Per page |

#### Methods

##### avg_render_ms()

**Signature:**

```rust
pub fn avg_render_ms(&self) -> f64
```

##### avg_inference_ms()

**Signature:**

```rust
pub fn avg_inference_ms(&self) -> f64
```

##### avg_preprocess_ms()

**Signature:**

```rust
pub fn avg_preprocess_ms(&self) -> f64
```

##### avg_onnx_ms()

**Signature:**

```rust
pub fn avg_onnx_ms(&self) -> f64
```

##### avg_postprocess_ms()

**Signature:**

```rust
pub fn avg_postprocess_ms(&self) -> f64
```

##### total_inference_ms()

**Signature:**

```rust
pub fn total_inference_ms(&self) -> f64
```

##### total_render_ms()

**Signature:**

```rust
pub fn total_render_ms(&self) -> f64
```

##### total_preprocess_ms()

**Signature:**

```rust
pub fn total_preprocess_ms(&self) -> f64
```

##### total_onnx_ms()

**Signature:**

```rust
pub fn total_onnx_ms(&self) -> f64
```

##### total_postprocess_ms()

**Signature:**

```rust
pub fn total_postprocess_ms(&self) -> f64
```


---

### LinkMetadata

Link element metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `href` | `String` | — | The href URL value |
| `text` | `String` | — | Link text content (normalized) |
| `title` | `Option<String>` | `None` | Optional title attribute |
| `link_type` | `LinkType` | — | Link type classification |
| `rel` | `Vec<String>` | — | Rel attribute values |
| `attributes` | `Vec<StringString>` | — | Additional attributes as key-value pairs |


---

### LlmConfig

Configuration for an LLM provider/model via liter-llm.

Each feature (VLM OCR, VLM embeddings, structured extraction) carries
its own `LlmConfig`, allowing different providers per feature.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `model` | `String` | — | Provider/model string using liter-llm routing format. Examples: `"openai/gpt-4o"`, `"anthropic/claude-sonnet-4-20250514"`, `"groq/llama-3.1-70b-versatile"`. |
| `api_key` | `Option<String>` | `None` | API key for the provider. When `None`, liter-llm falls back to the provider's standard environment variable (e.g., `OPENAI_API_KEY`). |
| `base_url` | `Option<String>` | `None` | Custom base URL override for the provider endpoint. |
| `timeout_secs` | `Option<u64>` | `None` | Request timeout in seconds (default: 60). |
| `max_retries` | `Option<u32>` | `None` | Maximum retry attempts (default: 3). |
| `temperature` | `Option<f64>` | `None` | Sampling temperature for generation tasks. |
| `max_tokens` | `Option<u64>` | `None` | Maximum tokens to generate. |


---

### LlmUsage

Token usage and cost data for a single LLM call made during extraction.

Populated when VLM OCR, structured extraction, or LLM-based embeddings
are used. Multiple entries may be present when multiple LLM calls occur
within one extraction (e.g. VLM OCR + structured extraction).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `model` | `String` | `Default::default()` | The LLM model identifier (e.g. "openai/gpt-4o", "anthropic/claude-sonnet-4-20250514"). |
| `source` | `String` | `Default::default()` | The pipeline stage that triggered this LLM call (e.g. "vlm_ocr", "structured_extraction", "embeddings"). |
| `input_tokens` | `Option<u64>` | `Default::default()` | Number of input/prompt tokens consumed. |
| `output_tokens` | `Option<u64>` | `Default::default()` | Number of output/completion tokens generated. |
| `total_tokens` | `Option<u64>` | `Default::default()` | Total tokens (input + output). |
| `estimated_cost` | `Option<f64>` | `Default::default()` | Estimated cost in USD based on the provider's published pricing. |
| `finish_reason` | `Option<String>` | `Default::default()` | Why the model stopped generating (e.g. "stop", "length", "content_filter"). |


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

##### build_internal_document()

Build an `InternalDocument` from pulldown-cmark events and optional YAML frontmatter.

**Signature:**

```rust
pub fn build_internal_document(events: Vec<Event>, yaml: Option<Value>) -> InternalDocument
```

##### default()

**Signature:**

```rust
pub fn default() -> MarkdownExtractor
```

##### name()

**Signature:**

```rust
pub fn name(&self) -> String
```

##### version()

**Signature:**

```rust
pub fn version(&self) -> String
```

##### initialize()

**Signature:**

```rust
pub fn initialize(&self)
```

##### shutdown()

**Signature:**

```rust
pub fn shutdown(&self)
```

##### description()

**Signature:**

```rust
pub fn description(&self) -> String
```

##### author()

**Signature:**

```rust
pub fn author(&self) -> String
```

##### extract_bytes()

**Signature:**

```rust
pub fn extract_bytes(&self, content: Vec<u8>, mime_type: String, config: ExtractionConfig) -> InternalDocument
```

##### extract_file()

**Signature:**

```rust
pub fn extract_file(&self, path: PathBuf, mime_type: String, config: ExtractionConfig) -> InternalDocument
```

##### supported_mime_types()

**Signature:**

```rust
pub fn supported_mime_types(&self) -> Vec<String>
```

##### priority()

**Signature:**

```rust
pub fn priority(&self) -> i32
```


---

### MdxExtractor

MDX extractor with JSX stripping and Markdown processing.

Strips MDX-specific syntax (imports, exports, JSX component tags,
inline expressions) and processes the remaining content as Markdown,
extracting metadata from YAML frontmatter and tables.

#### Methods

##### build_internal_document()

Build an `InternalDocument` from pulldown-cmark events after JSX stripping.

JSX blocks that were stripped are recorded as raw blocks in the internal document.

**Signature:**

```rust
pub fn build_internal_document(events: Vec<Event>, yaml: Option<Value>, raw_jsx_blocks: Vec<String>) -> InternalDocument
```

##### default()

**Signature:**

```rust
pub fn default() -> MdxExtractor
```

##### name()

**Signature:**

```rust
pub fn name(&self) -> String
```

##### version()

**Signature:**

```rust
pub fn version(&self) -> String
```

##### initialize()

**Signature:**

```rust
pub fn initialize(&self)
```

##### shutdown()

**Signature:**

```rust
pub fn shutdown(&self)
```

##### description()

**Signature:**

```rust
pub fn description(&self) -> String
```

##### author()

**Signature:**

```rust
pub fn author(&self) -> String
```

##### extract_bytes()

**Signature:**

```rust
pub fn extract_bytes(&self, content: Vec<u8>, mime_type: String, config: ExtractionConfig) -> InternalDocument
```

##### extract_file()

**Signature:**

```rust
pub fn extract_file(&self, path: PathBuf, mime_type: String, config: ExtractionConfig) -> InternalDocument
```

##### supported_mime_types()

**Signature:**

```rust
pub fn supported_mime_types(&self) -> Vec<String>
```

##### priority()

**Signature:**

```rust
pub fn priority(&self) -> i32
```


---

### Metadata

Extraction result metadata.

Contains common fields applicable to all formats, format-specific metadata
via a discriminated union, and additional custom fields from postprocessors.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `title` | `Option<String>` | `Default::default()` | Document title |
| `subject` | `Option<String>` | `Default::default()` | Document subject or description |
| `authors` | `Option<Vec<String>>` | `vec![]` | Primary author(s) - always Vec for consistency |
| `keywords` | `Option<Vec<String>>` | `vec![]` | Keywords/tags - always Vec for consistency |
| `language` | `Option<String>` | `Default::default()` | Primary language (ISO 639 code) |
| `created_at` | `Option<String>` | `Default::default()` | Creation timestamp (ISO 8601 format) |
| `modified_at` | `Option<String>` | `Default::default()` | Last modification timestamp (ISO 8601 format) |
| `created_by` | `Option<String>` | `Default::default()` | User who created the document |
| `modified_by` | `Option<String>` | `Default::default()` | User who last modified the document |
| `pages` | `Option<PageStructure>` | `Default::default()` | Page/slide/sheet structure with boundaries |
| `format` | `Option<FormatMetadata>` | `FormatMetadata::Pdf` | Format-specific metadata (discriminated union) Contains detailed metadata specific to the document format. Serializes with a `format_type` discriminator field. |
| `image_preprocessing` | `Option<ImagePreprocessingMetadata>` | `Default::default()` | Image preprocessing metadata (when OCR preprocessing was applied) |
| `json_schema` | `Option<serde_json::Value>` | `Default::default()` | JSON schema (for structured data extraction) |
| `error` | `Option<ErrorMetadata>` | `Default::default()` | Error metadata (for batch operations) |
| `extraction_duration_ms` | `Option<u64>` | `Default::default()` | Extraction duration in milliseconds (for benchmarking). This field is populated by batch extraction to provide per-file timing information. It's `None` for single-file extraction (which uses external timing). |
| `category` | `Option<String>` | `Default::default()` | Document category (from frontmatter or classification). |
| `tags` | `Option<Vec<String>>` | `vec![]` | Document tags (from frontmatter). |
| `document_version` | `Option<String>` | `Default::default()` | Document version string (from frontmatter). |
| `abstract_text` | `Option<String>` | `Default::default()` | Abstract or summary text (from frontmatter). |
| `output_format` | `Option<String>` | `Default::default()` | Output format identifier (e.g., "markdown", "html", "text"). Set by the output format pipeline stage when format conversion is applied. Previously stored in `metadata.additional["output_format"]`. |
| `additional` | `AHashMap` | `Default::default()` | Additional custom fields from postprocessors. **Deprecated**: Prefer using typed fields on `ExtractionResult` and `Metadata` instead of inserting into this map. Typed fields provide better cross-language compatibility and type safety. This field will be removed in a future major version. This flattened map allows Python/TypeScript postprocessors to add arbitrary fields (entity extraction, keyword extraction, etc.). Fields are merged at the root level during serialization. Uses `Cow<'static, str>` keys so static string keys avoid allocation. |


---

### MetricsLayer

A `tower.Layer` that records service-level extraction metrics.

#### Methods

##### layer()

**Signature:**

```rust
pub fn layer(&self, inner: S) -> Service
```


---

### ModelCache

#### Methods

##### put()

Return a model to the cache for reuse.

If the cache already holds a model (e.g. from a concurrent caller),
the returned model is silently dropped.

**Signature:**

```rust
pub fn put(&self, model: T)
```

##### take()

Take the cached model if one exists, without creating a new one.

**Signature:**

```rust
pub fn take(&self) -> Option<T>
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

```rust
pub fn generate(node_type: String, text: String, page: Option<u32>, index: u32) -> NodeId
```

##### as_ref()

**Signature:**

```rust
pub fn as_ref(&self) -> String
```

##### fmt()

**Signature:**

```rust
pub fn fmt(&self, f: Formatter) -> Unknown
```


---

### NormalizeResult

Result of image normalization

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `rgb_data` | `Vec<u8>` | — | Processed RGB image data (height * width * 3 bytes) |
| `dimensions` | `UsizeUsize` | — | Image dimensions (width, height) |
| `metadata` | `ImagePreprocessingMetadata` | — | Preprocessing metadata |


---

### Note

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `id` | `String` | — | Unique identifier |
| `note_type` | `NoteType` | — | Note type (note type) |
| `paragraphs` | `Vec<Paragraph>` | — | Paragraphs |


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

```rust
pub fn default() -> NumbersExtractor
```

##### name()

**Signature:**

```rust
pub fn name(&self) -> String
```

##### version()

**Signature:**

```rust
pub fn version(&self) -> String
```

##### initialize()

**Signature:**

```rust
pub fn initialize(&self)
```

##### shutdown()

**Signature:**

```rust
pub fn shutdown(&self)
```

##### description()

**Signature:**

```rust
pub fn description(&self) -> String
```

##### author()

**Signature:**

```rust
pub fn author(&self) -> String
```

##### extract_bytes()

**Signature:**

```rust
pub fn extract_bytes(&self, content: Vec<u8>, mime_type: String, config: ExtractionConfig) -> InternalDocument
```

##### supported_mime_types()

**Signature:**

```rust
pub fn supported_mime_types(&self) -> Vec<String>
```

##### priority()

**Signature:**

```rust
pub fn priority(&self) -> i32
```


---

### OcrCache

#### Methods

##### new()

**Signature:**

```rust
pub fn new(cache_dir: Option<PathBuf>) -> OcrCache
```

##### get_cached_result()

**Signature:**

```rust
pub fn get_cached_result(&self, image_hash: String, backend: String, config: String) -> Option<OcrExtractionResult>
```

##### set_cached_result()

**Signature:**

```rust
pub fn set_cached_result(&self, image_hash: String, backend: String, config: String, result: OcrExtractionResult)
```

##### clear()

**Signature:**

```rust
pub fn clear(&self)
```

##### get_stats()

**Signature:**

```rust
pub fn get_stats(&self) -> OcrCacheStats
```


---

### OcrCacheStats

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `total_files` | `usize` | `Default::default()` | Total files |
| `total_size_mb` | `f64` | `Default::default()` | Total size mb |


---

### OcrConfidence

Confidence scores for an OCR element.

Separates detection confidence (how confident that text exists at this location)
from recognition confidence (how confident about the actual text content).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `detection` | `Option<f64>` | `None` | Detection confidence: how confident the OCR engine is that text exists here. PaddleOCR provides this as `box_score`, Tesseract doesn't have a direct equivalent. Range: 0.0 to 1.0 (or None if not available). |
| `recognition` | `f64` | — | Recognition confidence: how confident about the text content. Range: 0.0 to 1.0. |

#### Methods

##### from_tesseract()

Create confidence from Tesseract's single confidence value.

Tesseract provides confidence as 0-100, which we normalize to 0.0-1.0.

**Signature:**

```rust
pub fn from_tesseract(confidence: f64) -> OcrConfidence
```

##### from_paddle()

Create confidence from PaddleOCR scores.

Both scores should be in 0.0-1.0 range, but PaddleOCR may occasionally return
values slightly above 1.0 due to model calibration. This method clamps both
values to ensure they stay within the valid 0.0-1.0 range.

**Signature:**

```rust
pub fn from_paddle(box_score: f32, text_score: f32) -> OcrConfidence
```


---

### OcrConfig

OCR configuration.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `backend` | `String` | `Default::default()` | OCR backend: tesseract, easyocr, paddleocr |
| `language` | `String` | `Default::default()` | Language code (e.g., "eng", "deu") |
| `tesseract_config` | `Option<TesseractConfig>` | `Default::default()` | Tesseract-specific configuration (optional) |
| `output_format` | `Option<OutputFormat>` | `OutputFormat::Plain` | Output format for OCR results (optional, for format conversion) |
| `paddle_ocr_config` | `Option<serde_json::Value>` | `Default::default()` | PaddleOCR-specific configuration (optional, JSON passthrough) |
| `element_config` | `Option<OcrElementConfig>` | `Default::default()` | OCR element extraction configuration |
| `quality_thresholds` | `Option<OcrQualityThresholds>` | `Default::default()` | Quality thresholds for the native-text-to-OCR fallback decision. When None, uses compiled defaults (matching previous hardcoded behavior). |
| `pipeline` | `Option<OcrPipelineConfig>` | `Default::default()` | Multi-backend OCR pipeline configuration. When set, enables weighted fallback across multiple OCR backends based on output quality. When None, uses the single `backend` field (same as today). |
| `auto_rotate` | `bool` | `false` | Enable automatic page rotation based on orientation detection. When enabled, uses Tesseract's `DetectOrientationScript()` to detect page orientation (0/90/180/270 degrees) before OCR. If the page is rotated with high confidence, the image is corrected before recognition. This is critical for handling rotated scanned documents. |
| `vlm_config` | `Option<LlmConfig>` | `Default::default()` | VLM (Vision Language Model) OCR configuration. Required when `backend` is `"vlm"`. Uses liter-llm to send page images to a vision model for text extraction. |
| `vlm_prompt` | `Option<String>` | `Default::default()` | Custom Jinja2 prompt template for VLM OCR. When `None`, uses the default template. Available variables: - `{{ language }}` — The document language code (e.g., "eng", "deu"). |

#### Methods

##### default()

**Signature:**

```rust
pub fn default() -> OcrConfig
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

```rust
pub fn validate(&self)
```

##### effective_thresholds()

Returns the effective quality thresholds, using configured values or defaults.

**Signature:**

```rust
pub fn effective_thresholds(&self) -> OcrQualityThresholds
```

##### effective_pipeline()

Returns the effective pipeline config.

- If `pipeline` is explicitly set, returns it.
- If `paddle-ocr` feature is compiled in and no explicit pipeline is set,
  auto-constructs a default pipeline: primary backend (priority 100) + paddleocr (priority 50).
- Otherwise returns `None` (single-backend mode, same as today).

**Signature:**

```rust
pub fn effective_pipeline(&self) -> Option<OcrPipelineConfig>
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
| `rotation` | `Option<OcrRotation>` | `None` | Rotation information (if detected). |
| `page_number` | `usize` | — | Page number (1-indexed). |
| `parent_id` | `Option<String>` | `None` | Parent element ID for hierarchical relationships. Only used for Tesseract output which has word -> line -> block hierarchy. |
| `backend_metadata` | `HashMap<String, serde_json::Value>` | — | Backend-specific metadata that doesn't fit the unified schema. |

#### Methods

##### with_level()

Set the hierarchical level.

**Signature:**

```rust
pub fn with_level(&self, level: OcrElementLevel) -> OcrElement
```

##### with_rotation()

Set rotation information.

**Signature:**

```rust
pub fn with_rotation(&self, rotation: OcrRotation) -> OcrElement
```

##### with_page_number()

Set page number.

**Signature:**

```rust
pub fn with_page_number(&self, page_number: usize) -> OcrElement
```

##### with_parent_id()

Set parent element ID.

**Signature:**

```rust
pub fn with_parent_id(&self, parent_id: String) -> OcrElement
```

##### with_metadata()

Add backend-specific metadata.

**Signature:**

```rust
pub fn with_metadata(&self, key: String, value: serde_json::Value) -> OcrElement
```

##### with_rotation_opt()

**Signature:**

```rust
pub fn with_rotation_opt(&self, rotation: Option<OcrRotation>) -> OcrElement
```


---

### OcrElementConfig

Configuration for OCR element extraction.

Controls how OCR elements are extracted and filtered.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `include_elements` | `bool` | `Default::default()` | Whether to include OCR elements in the extraction result. When true, the `ocr_elements` field in `ExtractionResult` will be populated. |
| `min_level` | `OcrElementLevel` | `OcrElementLevel::Line` | Minimum hierarchical level to include. Elements below this level (e.g., words when min_level is Line) will be excluded. |
| `min_confidence` | `f64` | `Default::default()` | Minimum recognition confidence threshold (0.0-1.0). Elements with confidence below this threshold will be filtered out. |
| `build_hierarchy` | `bool` | `Default::default()` | Whether to build hierarchical relationships between elements. When true, `parent_id` fields will be populated based on spatial containment. Only meaningful for Tesseract output. |


---

### OcrExtractionResult

OCR extraction result.

Result of performing OCR on an image or scanned document,
including recognized text and detected tables.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `content` | `String` | — | Recognized text content |
| `mime_type` | `String` | — | Original MIME type of the processed image |
| `metadata` | `HashMap<String, serde_json::Value>` | — | OCR processing metadata (confidence scores, language, etc.) |
| `tables` | `Vec<OcrTable>` | — | Tables detected and extracted via OCR |
| `ocr_elements` | `Option<Vec<OcrElement>>` | `None` | Structured OCR elements with bounding boxes and confidence scores. Available when TSV output is requested or table detection is enabled. |
| `internal_document` | `Option<InternalDocument>` | `None` | Structured document produced from hOCR parsing. Carries paragraph structure, bounding boxes, and confidence scores that the flattened `content` string discards. |


---

### OcrMetadata

OCR processing metadata.

Captures information about OCR processing configuration and results.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `language` | `String` | — | OCR language code(s) used |
| `psm` | `i32` | — | Tesseract Page Segmentation Mode (PSM) |
| `output_format` | `String` | — | Output format (e.g., "text", "hocr") |
| `table_count` | `usize` | — | Number of tables detected |
| `table_rows` | `Option<usize>` | `None` | Table rows |
| `table_cols` | `Option<usize>` | `None` | Table cols |


---

### OcrPipelineConfig

Multi-backend OCR pipeline with quality-based fallback.

Backends are tried in priority order (highest first). After each backend
produces output, quality is evaluated. If it meets `quality_thresholds.pipeline_min_quality`,
the result is accepted. Otherwise the next backend is tried.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `stages` | `Vec<OcrPipelineStage>` | — | Ordered list of backends to try. Sorted by priority (descending) at runtime. |
| `quality_thresholds` | `OcrQualityThresholds` | — | Quality thresholds for deciding whether to accept a result or try the next backend. |


---

### OcrPipelineStage

A single backend stage in the OCR pipeline.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `backend` | `String` | — | Backend name: "tesseract", "paddleocr", "easyocr", or a custom registered name. |
| `priority` | `u32` | — | Priority weight (higher = tried first). Stages are sorted by priority descending. |
| `language` | `Option<String>` | `None` | Language override for this stage (None = use parent OcrConfig.language). |
| `tesseract_config` | `Option<TesseractConfig>` | `None` | Tesseract-specific config override for this stage. |
| `paddle_ocr_config` | `Option<serde_json::Value>` | `None` | PaddleOCR-specific config for this stage. |
| `vlm_config` | `Option<LlmConfig>` | `None` | VLM config override for this pipeline stage. |


---

### OcrProcessor

#### Methods

##### new()

**Signature:**

```rust
pub fn new(cache_dir: Option<PathBuf>) -> OcrProcessor
```

##### process_image()

**Signature:**

```rust
pub fn process_image(&self, image_bytes: Vec<u8>, config: TesseractConfig) -> OcrExtractionResult
```

##### process_image_with_format()

Process an image with OCR and respect the output format from ExtractionConfig.

This variant allows specifying an output format (Plain, Markdown, Djot) which
affects how the OCR result's mime_type is set when markdown output is requested.

**Signature:**

```rust
pub fn process_image_with_format(&self, image_bytes: Vec<u8>, config: TesseractConfig, output_format: OutputFormat) -> OcrExtractionResult
```

##### clear_cache()

**Signature:**

```rust
pub fn clear_cache(&self)
```

##### get_cache_stats()

**Signature:**

```rust
pub fn get_cache_stats(&self) -> OcrCacheStats
```

##### process_image_file()

**Signature:**

```rust
pub fn process_image_file(&self, file_path: String, config: TesseractConfig) -> OcrExtractionResult
```

##### process_image_file_with_format()

Process a file with OCR and respect the output format from ExtractionConfig.

This variant allows specifying an output format (Plain, Markdown, Djot) which
affects how the OCR result's mime_type is set when markdown output is requested.

**Signature:**

```rust
pub fn process_image_file_with_format(&self, file_path: String, config: TesseractConfig, output_format: OutputFormat) -> OcrExtractionResult
```

##### process_image_files_batch()

Process multiple image files in parallel using Rayon.

This method processes OCR operations in parallel across CPU cores for improved throughput.
Results are returned in the same order as the input file paths.

**Signature:**

```rust
pub fn process_image_files_batch(&self, file_paths: Vec<String>, config: TesseractConfig) -> Vec<BatchItemResult>
```


---

### OcrQualityThresholds

Quality thresholds for OCR fallback decisions and pipeline quality gating.

All fields default to the values that match the previous hardcoded behavior,
so `OcrQualityThresholds.default()` preserves existing semantics exactly.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `min_total_non_whitespace` | `usize` | `64` | Minimum total non-whitespace characters to consider text substantive. |
| `min_non_whitespace_per_page` | `f64` | `32` | Minimum non-whitespace characters per page on average. |
| `min_meaningful_word_len` | `usize` | `4` | Minimum character count for a word to be "meaningful". |
| `min_meaningful_words` | `usize` | `3` | Minimum count of meaningful words before text is accepted. |
| `min_alnum_ratio` | `f64` | `0.3` | Minimum alphanumeric ratio (non-whitespace chars that are alphanumeric). |
| `min_garbage_chars` | `usize` | `5` | Minimum Unicode replacement characters (U+FFFD) to trigger OCR fallback. |
| `max_fragmented_word_ratio` | `f64` | `0.6` | Maximum fraction of short (1-2 char) words before text is considered fragmented. |
| `critical_fragmented_word_ratio` | `f64` | `0.8` | Critical fragmentation threshold — triggers OCR regardless of meaningful words. Normal English text has ~20-30% short words. 80%+ is definitive garbage. |
| `min_avg_word_length` | `f64` | `2` | Minimum average word length. Below this with enough words indicates garbled extraction. |
| `min_words_for_avg_length_check` | `usize` | `50` | Minimum word count before average word length check applies. |
| `min_consecutive_repeat_ratio` | `f64` | `0.08` | Minimum consecutive word repetition ratio to detect column scrambling. |
| `min_words_for_repeat_check` | `usize` | `50` | Minimum word count before consecutive repetition check is applied. |
| `substantive_min_chars` | `usize` | `100` | Minimum character count for "substantive markdown" OCR skip gate. |
| `non_text_min_chars` | `usize` | `20` | Minimum character count for "non-text content" OCR skip gate. |
| `alnum_ws_ratio_threshold` | `f64` | `0.4` | Alphanumeric+whitespace ratio threshold for skip decisions. |
| `pipeline_min_quality` | `f64` | `0.5` | Minimum quality score (0.0-1.0) for a pipeline stage result to be accepted. If the result from a backend scores below this, try the next backend. |

#### Methods

##### default()

**Signature:**

```rust
pub fn default() -> OcrQualityThresholds
```


---

### OcrRotation

Rotation information for an OCR element.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `angle_degrees` | `f64` | — | Rotation angle in degrees (0, 90, 180, 270 for PaddleOCR). |
| `confidence` | `Option<f64>` | `None` | Confidence score for the rotation detection. |

#### Methods

##### from_paddle()

Create rotation from PaddleOCR angle classification.

PaddleOCR uses angle_index (0-3) representing 0, 90, 180, 270 degrees.

**Errors:**

Returns an error if `angle_index` is not in the valid range (0-3).

**Signature:**

```rust
pub fn from_paddle(angle_index: i32, angle_score: f32) -> OcrRotation
```


---

### OcrTable

Table detected via OCR.

Represents a table structure recognized during OCR processing.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `cells` | `Vec<Vec<String>>` | — | Table cells as a 2D vector (rows × columns) |
| `markdown` | `String` | — | Markdown representation of the table |
| `page_number` | `usize` | — | Page number where the table was found (1-indexed) |
| `bounding_box` | `Option<OcrTableBoundingBox>` | `None` | Bounding box of the table in pixel coordinates (from OCR word positions). |


---

### OcrTableBoundingBox

Bounding box for an OCR-detected table in pixel coordinates.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `left` | `u32` | — | Left x-coordinate (pixels) |
| `top` | `u32` | — | Top y-coordinate (pixels) |
| `right` | `u32` | — | Right x-coordinate (pixels) |
| `bottom` | `u32` | — | Bottom y-coordinate (pixels) |


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

```rust
pub fn default() -> OdtExtractor
```

##### name()

**Signature:**

```rust
pub fn name(&self) -> String
```

##### version()

**Signature:**

```rust
pub fn version(&self) -> String
```

##### initialize()

**Signature:**

```rust
pub fn initialize(&self)
```

##### shutdown()

**Signature:**

```rust
pub fn shutdown(&self)
```

##### description()

**Signature:**

```rust
pub fn description(&self) -> String
```

##### author()

**Signature:**

```rust
pub fn author(&self) -> String
```

##### extract_bytes()

**Signature:**

```rust
pub fn extract_bytes(&self, content: Vec<u8>, mime_type: String, config: ExtractionConfig) -> InternalDocument
```

##### supported_mime_types()

**Signature:**

```rust
pub fn supported_mime_types(&self) -> Vec<String>
```

##### priority()

**Signature:**

```rust
pub fn priority(&self) -> i32
```


---

### OdtProperties

OpenDocument metadata from meta.xml

Contains metadata fields defined by the OASIS OpenDocument Format standard.
Uses Dublin Core elements (dc:) and OpenDocument meta elements (meta:).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `title` | `Option<String>` | `Default::default()` | Document title (dc:title) |
| `subject` | `Option<String>` | `Default::default()` | Document subject/topic (dc:subject) |
| `creator` | `Option<String>` | `Default::default()` | Current document creator/author (dc:creator) |
| `initial_creator` | `Option<String>` | `Default::default()` | Initial creator of the document (meta:initial-creator) |
| `keywords` | `Option<String>` | `Default::default()` | Keywords or tags (meta:keyword) |
| `description` | `Option<String>` | `Default::default()` | Document description (dc:description) |
| `date` | `Option<String>` | `Default::default()` | Current modification date (dc:date) |
| `creation_date` | `Option<String>` | `Default::default()` | Initial creation date (meta:creation-date) |
| `language` | `Option<String>` | `Default::default()` | Document language (dc:language) |
| `generator` | `Option<String>` | `Default::default()` | Generator/application that created the document (meta:generator) |
| `editing_duration` | `Option<String>` | `Default::default()` | Editing duration in ISO 8601 format (meta:editing-duration) |
| `editing_cycles` | `Option<String>` | `Default::default()` | Number of edits/revisions (meta:editing-cycles) |
| `page_count` | `Option<i32>` | `Default::default()` | Document statistics - page count (meta:page-count) |
| `word_count` | `Option<i32>` | `Default::default()` | Document statistics - word count (meta:word-count) |
| `character_count` | `Option<i32>` | `Default::default()` | Document statistics - character count (meta:character-count) |
| `paragraph_count` | `Option<i32>` | `Default::default()` | Document statistics - paragraph count (meta:paragraph-count) |
| `table_count` | `Option<i32>` | `Default::default()` | Document statistics - table count (meta:table-count) |
| `image_count` | `Option<i32>` | `Default::default()` | Document statistics - image count (meta:image-count) |


---

### OrgModeExtractor

Org Mode document extractor.

Provides native Rust-based Org Mode extraction using the `org` library,
extracting structured content and metadata.

#### Methods

##### build_internal_document()

Build an `InternalDocument` from Org Mode source text.

Handles headings, paragraphs, lists, code blocks, tables, inline links,
and footnote references.

**Signature:**

```rust
pub fn build_internal_document(org_text: String) -> InternalDocument
```

##### default()

**Signature:**

```rust
pub fn default() -> OrgModeExtractor
```

##### name()

**Signature:**

```rust
pub fn name(&self) -> String
```

##### version()

**Signature:**

```rust
pub fn version(&self) -> String
```

##### initialize()

**Signature:**

```rust
pub fn initialize(&self)
```

##### shutdown()

**Signature:**

```rust
pub fn shutdown(&self)
```

##### description()

**Signature:**

```rust
pub fn description(&self) -> String
```

##### author()

**Signature:**

```rust
pub fn author(&self) -> String
```

##### extract_bytes()

**Signature:**

```rust
pub fn extract_bytes(&self, content: Vec<u8>, mime_type: String, config: ExtractionConfig) -> InternalDocument
```

##### extract_file()

**Signature:**

```rust
pub fn extract_file(&self, path: PathBuf, mime_type: String, config: ExtractionConfig) -> InternalDocument
```

##### supported_mime_types()

**Signature:**

```rust
pub fn supported_mime_types(&self) -> Vec<String>
```

##### priority()

**Signature:**

```rust
pub fn priority(&self) -> i32
```


---

### OrientationResult

Document orientation detection result.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `degrees` | `u32` | — | Detected orientation in degrees (0, 90, 180, or 270). |
| `confidence` | `f32` | — | Confidence score (0.0-1.0). |


---

### PageBoundary

Byte offset boundary for a page.

Tracks where a specific page's content starts and ends in the main content string,
enabling mapping from byte positions to page numbers. Offsets are guaranteed to be
at valid UTF-8 character boundaries when using standard String methods (push_str, push, etc.).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `byte_start` | `usize` | — | Byte offset where this page starts in the content string (UTF-8 valid boundary, inclusive) |
| `byte_end` | `usize` | — | Byte offset where this page ends in the content string (UTF-8 valid boundary, exclusive) |
| `page_number` | `usize` | — | Page number (1-indexed) |


---

### PageConfig

Page extraction and tracking configuration.

Controls how pages are extracted, tracked, and represented in the extraction results.
When `None`, page tracking is disabled.

Page range tracking in chunk metadata (first_page/last_page) is automatically enabled
when page boundaries are available and chunking is configured.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `extract_pages` | `bool` | `false` | Extract pages as separate array (ExtractionResult.pages) |
| `insert_page_markers` | `bool` | `false` | Insert page markers in main content string |
| `marker_format` | `String` | `"

<!-- PAGE {page_num} -->

"` | Page marker format (use {page_num} placeholder) Default: "\n\n<!-- PAGE {page_num} -->\n\n" |

#### Methods

##### default()

**Signature:**

```rust
pub fn default() -> PageConfig
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
| `page_number` | `usize` | — | Page number (1-indexed) |
| `content` | `String` | — | Text content for this page |
| `tables` | `Vec<Table>` | — | Tables found on this page (uses Arc for memory efficiency) Serializes as Vec<Table> for JSON compatibility while maintaining Arc semantics in-memory for zero-copy sharing. |
| `images` | `Vec<ExtractedImage>` | — | Images found on this page (uses Arc for memory efficiency) Serializes as Vec<ExtractedImage> for JSON compatibility while maintaining Arc semantics in-memory for zero-copy sharing. |
| `hierarchy` | `Option<PageHierarchy>` | `None` | Hierarchy information for the page (when hierarchy extraction is enabled) Contains text hierarchy levels (H1-H6) extracted from the page content. |
| `is_blank` | `Option<bool>` | `None` | Whether this page is blank (no meaningful text content) Determined during extraction based on text content analysis. A page is blank if it has fewer than 3 non-whitespace characters and contains no tables or images. |


---

### PageHierarchy

Page hierarchy structure containing heading levels and block information.

Used when PDF text hierarchy extraction is enabled. Contains hierarchical
blocks with heading levels (H1-H6) for semantic document structure.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `block_count` | `usize` | — | Number of hierarchy blocks on this page |
| `blocks` | `Vec<HierarchicalBlock>` | — | Hierarchical blocks with heading levels |


---

### PageInfo

Metadata for individual page/slide/sheet.

Captures per-page information including dimensions, content counts,
and visibility state (for presentations).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `number` | `usize` | — | Page number (1-indexed) |
| `title` | `Option<String>` | `None` | Page title (usually for presentations) |
| `dimensions` | `Option<F64F64>` | `None` | Dimensions in points (PDF) or pixels (images): (width, height) |
| `image_count` | `Option<usize>` | `None` | Number of images on this page |
| `table_count` | `Option<usize>` | `None` | Number of tables on this page |
| `hidden` | `Option<bool>` | `None` | Whether this page is hidden (e.g., in presentations) |
| `is_blank` | `Option<bool>` | `None` | Whether this page is blank (no meaningful text, no images, no tables) A page is considered blank if it has fewer than 3 non-whitespace characters and contains no tables or images. This is useful for filtering out empty pages in scanned documents or PDFs with blank separator pages. |


---

### PageLayoutRegion

A detected layout region mapped to PDF coordinate space.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `class` | `LayoutClass` | — | Class (layout class) |
| `confidence` | `f32` | — | Confidence |
| `bbox` | `PdfLayoutBBox` | — | Bbox (pdf layout b box) |


---

### PageLayoutResult

Layout detection results for a single page.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `page_index` | `usize` | — | Page index |
| `regions` | `Vec<PageLayoutRegion>` | — | Regions |
| `page_width_pts` | `f32` | — | Page width pts |
| `page_height_pts` | `f32` | — | Page height pts |
| `render_width_px` | `u32` | — | Width of the rendered image used for layout detection (pixels). |
| `render_height_px` | `u32` | — | Height of the rendered image used for layout detection (pixels). |


---

### PageMargins

Page margins in twips (twentieths of a point).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `top` | `Option<i32>` | `Default::default()` | Top margin in twips. |
| `right` | `Option<i32>` | `Default::default()` | Right margin in twips. |
| `bottom` | `Option<i32>` | `Default::default()` | Bottom margin in twips. |
| `left` | `Option<i32>` | `Default::default()` | Left margin in twips. |
| `header` | `Option<i32>` | `Default::default()` | Header offset in twips. |
| `footer` | `Option<i32>` | `Default::default()` | Footer offset in twips. |
| `gutter` | `Option<i32>` | `Default::default()` | Gutter margin in twips. |

#### Methods

##### to_points()

Convert all margins from twips to points.

Conversion factor: 1 twip = 1/20 point, or equivalently divide by 20.

**Signature:**

```rust
pub fn to_points(&self) -> PageMarginsPoints
```


---

### PageMarginsPoints

Page margins converted to points (1/72 inch).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `top` | `Option<f64>` | `Default::default()` | Top |
| `right` | `Option<f64>` | `Default::default()` | Right |
| `bottom` | `Option<f64>` | `Default::default()` | Bottom |
| `left` | `Option<f64>` | `Default::default()` | Left |
| `header` | `Option<f64>` | `Default::default()` | Header |
| `footer` | `Option<f64>` | `Default::default()` | Footer |
| `gutter` | `Option<f64>` | `Default::default()` | Gutter |


---

### PageRenderOptions

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `target_dpi` | `i32` | `300` | Target dpi |
| `max_image_dimension` | `i32` | `65536` | Maximum image dimension |
| `auto_adjust_dpi` | `bool` | `true` | Auto adjust dpi |
| `min_dpi` | `i32` | `72` | Minimum dpi |
| `max_dpi` | `i32` | `600` | Maximum dpi |

#### Methods

##### default()

**Signature:**

```rust
pub fn default() -> PageRenderOptions
```


---

### PageStructure

Unified page structure for documents.

Supports different page types (PDF pages, PPTX slides, Excel sheets)
with character offset boundaries for chunk-to-page mapping.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `total_count` | `usize` | — | Total number of pages/slides/sheets |
| `unit_type` | `PageUnitType` | — | Type of paginated unit |
| `boundaries` | `Option<Vec<PageBoundary>>` | `None` | Character offset boundaries for each page Maps character ranges in the extracted content to page numbers. Used for chunk page range calculation. |
| `pages` | `Option<Vec<PageInfo>>` | `None` | Detailed per-page metadata (optional, only when needed) |


---

### PageTiming

Timing breakdown for a single page.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `render_ms` | `f64` | — | Time to render the PDF page to a raster image (amortized from batch render). |
| `preprocess_ms` | `f64` | — | Time spent in image preprocessing (resize, normalize, tensor construction). |
| `onnx_ms` | `f64` | — | Time for the ONNX model session.run() call (actual neural network inference). |
| `inference_ms` | `f64` | — | Total model inference time (preprocess + onnx), as measured by the engine. |
| `postprocess_ms` | `f64` | — | Time spent in postprocessing (confidence filtering, overlap resolution). |
| `mapping_ms` | `f64` | — | Time to map pixel-space bounding boxes to PDF coordinate space. |


---

### PagesExtractor

Apple Pages document extractor.

Supports `.pages` files (modern iWork format, 2013+).

Extracts all text content from the document by parsing the IWA
(iWork Archive) container: ZIP → Snappy → protobuf text fields.

#### Methods

##### default()

**Signature:**

```rust
pub fn default() -> PagesExtractor
```

##### name()

**Signature:**

```rust
pub fn name(&self) -> String
```

##### version()

**Signature:**

```rust
pub fn version(&self) -> String
```

##### initialize()

**Signature:**

```rust
pub fn initialize(&self)
```

##### shutdown()

**Signature:**

```rust
pub fn shutdown(&self)
```

##### description()

**Signature:**

```rust
pub fn description(&self) -> String
```

##### author()

**Signature:**

```rust
pub fn author(&self) -> String
```

##### extract_bytes()

**Signature:**

```rust
pub fn extract_bytes(&self, content: Vec<u8>, mime_type: String, config: ExtractionConfig) -> InternalDocument
```

##### supported_mime_types()

**Signature:**

```rust
pub fn supported_mime_types(&self) -> Vec<String>
```

##### priority()

**Signature:**

```rust
pub fn priority(&self) -> i32
```


---

### PanicContext

Context information captured when a panic occurs.

This struct stores detailed information about where and when a panic happened,
enabling better error reporting across FFI boundaries.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `file` | `String` | — | Source file where the panic occurred |
| `line` | `u32` | — | Line number where the panic occurred |
| `function` | `String` | — | Function name where the panic occurred |
| `message` | `String` | — | Panic message extracted from the panic payload |
| `timestamp` | `SystemTime` | — | Timestamp when the panic was captured |

#### Methods

##### format()

Formats the panic context as a human-readable string.

**Signature:**

```rust
pub fn format(&self) -> String
```


---

### ParaText

Plain text content decoded from a ParaText record (tag 0x43).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `content` | `String` | — | The extracted text content |

#### Methods

##### from_record()

Decode a ParaText record from raw bytes.

The data field of a TAG_PARA_TEXT record is a sequence of UTF-16LE code
units.  Control characters < 0x0020 are mapped to whitespace or skipped;
characters in the private-use range 0xF020–0xF07F (HWP internal controls)
are discarded.

**Signature:**

```rust
pub fn from_record(record: Record) -> ParaText
```


---

### Paragraph

A single paragraph; may or may not carry a text payload.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `text` | `Option<ParaText>` | `Default::default()` | Text (para text) |


---

### ParagraphProperties

Paragraph-level formatting properties (alignment, spacing, indentation, etc.).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `alignment` | `Option<String>` | `Default::default()` | `"left"`, `"center"`, `"right"`, `"both"` (justified). |
| `spacing_before` | `Option<i32>` | `Default::default()` | Spacing before paragraph in twips. |
| `spacing_after` | `Option<i32>` | `Default::default()` | Spacing after paragraph in twips. |
| `spacing_line` | `Option<i32>` | `Default::default()` | Line spacing in twips or 240ths of a line. |
| `spacing_line_rule` | `Option<String>` | `Default::default()` | Line spacing rule: "auto", "exact", or "atLeast". |
| `indent_left` | `Option<i32>` | `Default::default()` | Left indentation in twips. |
| `indent_right` | `Option<i32>` | `Default::default()` | Right indentation in twips. |
| `indent_first_line` | `Option<i32>` | `Default::default()` | First-line indentation in twips. |
| `indent_hanging` | `Option<i32>` | `Default::default()` | Hanging indentation in twips. |
| `outline_level` | `Option<u8>` | `Default::default()` | Outline level 0-8 for heading levels. |
| `keep_next` | `Option<bool>` | `Default::default()` | Keep with next paragraph on same page. |
| `keep_lines` | `Option<bool>` | `Default::default()` | Keep all lines of paragraph on same page. |
| `page_break_before` | `Option<bool>` | `Default::default()` | Force page break before paragraph. |
| `widow_control` | `Option<bool>` | `Default::default()` | Prevent widow/orphan lines. |
| `suppress_auto_hyphens` | `Option<bool>` | `Default::default()` | Suppress automatic hyphenation. |
| `bidi` | `Option<bool>` | `Default::default()` | Right-to-left paragraph direction. |
| `shading_fill` | `Option<String>` | `Default::default()` | Background color hex value (from w:shd w:fill). |
| `shading_val` | `Option<String>` | `Default::default()` | Shading pattern value (from w:shd w:val). |
| `border_top` | `Option<String>` | `Default::default()` | Top border style (from w:pBdr/w:top w:val). |
| `border_bottom` | `Option<String>` | `Default::default()` | Bottom border style (from w:pBdr/w:bottom w:val). |
| `border_left` | `Option<String>` | `Default::default()` | Left border style (from w:pBdr/w:left w:val). |
| `border_right` | `Option<String>` | `Default::default()` | Right border style (from w:pBdr/w:right w:val). |


---

### PdfAnnotation

A PDF annotation extracted from a document page.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `annotation_type` | `PdfAnnotationType` | — | The type of annotation. |
| `content` | `Option<String>` | `None` | Text content of the annotation (e.g., comment text, link URL). |
| `page_number` | `usize` | — | Page number where the annotation appears (1-indexed). |
| `bounding_box` | `Option<BoundingBox>` | `None` | Bounding box of the annotation on the page. |


---

### PdfConfig

PDF-specific configuration.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `backend` | `PdfBackend` | `PdfBackend::Pdfium` | PDF extraction backend. Default: `Pdfium`. |
| `extract_images` | `bool` | `false` | Extract images from PDF |
| `passwords` | `Option<Vec<String>>` | `vec![]` | List of passwords to try when opening encrypted PDFs |
| `extract_metadata` | `bool` | `true` | Extract PDF metadata |
| `hierarchy` | `Option<HierarchyConfig>` | `Default::default()` | Hierarchy extraction configuration (None = hierarchy extraction disabled) |
| `extract_annotations` | `bool` | `false` | Extract PDF annotations (text notes, highlights, links, stamps). Default: false |
| `top_margin_fraction` | `Option<f32>` | `Default::default()` | Top margin fraction (0.0–1.0) of page height to exclude headers/running heads. Default: 0.06 (6%) |
| `bottom_margin_fraction` | `Option<f32>` | `Default::default()` | Bottom margin fraction (0.0–1.0) of page height to exclude footers/page numbers. Default: 0.05 (5%) |
| `allow_single_column_tables` | `bool` | `false` | Allow single-column pseudo tables in extraction results. By default, tables with fewer than 2 columns (layout-guided) or 3 columns (heuristic) are rejected. When `True`, the minimum column count is relaxed to 1, allowing single-column structured data (glossaries, itemized lists) to be emitted as tables. Other quality filters (density, sparsity, prose detection) still apply. |

#### Methods

##### default()

**Signature:**

```rust
pub fn default() -> PdfConfig
```


---

### PdfExtractionMetadata

Complete PDF extraction metadata including common and PDF-specific fields.

This struct combines common document fields (title, authors, dates) with
PDF-specific metadata and optional page structure information. It is returned
by `extract_metadata_from_document()` when page boundaries are provided.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `title` | `Option<String>` | `None` | Document title |
| `subject` | `Option<String>` | `None` | Document subject or description |
| `authors` | `Option<Vec<String>>` | `None` | Document authors (parsed from PDF Author field) |
| `keywords` | `Option<Vec<String>>` | `None` | Document keywords (parsed from PDF Keywords field) |
| `created_at` | `Option<String>` | `None` | Creation timestamp (ISO 8601 format) |
| `modified_at` | `Option<String>` | `None` | Last modification timestamp (ISO 8601 format) |
| `created_by` | `Option<String>` | `None` | Application or user that created the document |
| `pdf_specific` | `PdfMetadata` | — | PDF-specific metadata |
| `page_structure` | `Option<PageStructure>` | `None` | Page structure with boundaries and optional per-page metadata |


---

### PdfExtractor

PDF document extractor using pypdfium2 and playa-pdf.

#### Methods

##### default()

**Signature:**

```rust
pub fn default() -> PdfExtractor
```

##### name()

**Signature:**

```rust
pub fn name(&self) -> String
```

##### version()

**Signature:**

```rust
pub fn version(&self) -> String
```

##### initialize()

**Signature:**

```rust
pub fn initialize(&self)
```

##### shutdown()

**Signature:**

```rust
pub fn shutdown(&self)
```

##### extract_bytes()

**Signature:**

```rust
pub fn extract_bytes(&self, content: Vec<u8>, mime_type: String, config: ExtractionConfig) -> InternalDocument
```

##### supported_mime_types()

**Signature:**

```rust
pub fn supported_mime_types(&self) -> Vec<String>
```


---

### PdfImage

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `page_number` | `usize` | — | Page number |
| `image_index` | `usize` | — | Image index |
| `width` | `i64` | — | Width |
| `height` | `i64` | — | Height |
| `color_space` | `Option<String>` | `None` | Color space |
| `bits_per_component` | `Option<i64>` | `None` | Bits per component |
| `filters` | `Vec<String>` | — | Original PDF stream filters (e.g. `["FlateDecode"]`, `["DCTDecode"]`). |
| `data` | `Vec<u8>` | — | The decoded image bytes in a standard format (JPEG, PNG, etc.). |
| `decoded_format` | `String` | — | The format of `data` after decoding: `"jpeg"`, `"png"`, `"jpeg2000"`, `"ccitt"`, or `"raw"`. |


---

### PdfImageExtractor

#### Methods

##### new()

**Signature:**

```rust
pub fn new(pdf_bytes: Vec<u8>) -> PdfImageExtractor
```

##### new_with_password()

**Signature:**

```rust
pub fn new_with_password(pdf_bytes: Vec<u8>, password: Option<String>) -> PdfImageExtractor
```

##### extract_images()

**Signature:**

```rust
pub fn extract_images(&self) -> Vec<PdfImage>
```

##### extract_images_from_page()

**Signature:**

```rust
pub fn extract_images_from_page(&self, page_number: u32) -> Vec<PdfImage>
```

##### get_image_count()

**Signature:**

```rust
pub fn get_image_count(&self) -> usize
```


---

### PdfLayoutBBox

Bounding box in PDF coordinate space (points, y=0 at bottom of page).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `left` | `f32` | — | Left |
| `bottom` | `f32` | — | Bottom |
| `right` | `f32` | — | Right |
| `top` | `f32` | — | Top |

#### Methods

##### width()

**Signature:**

```rust
pub fn width(&self) -> f32
```

##### height()

**Signature:**

```rust
pub fn height(&self) -> f32
```


---

### PdfMetadata

PDF-specific metadata.

Contains metadata fields specific to PDF documents that are not in the common
`Metadata` structure. Common fields like title, authors, keywords, and dates
are now at the `Metadata` level.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `pdf_version` | `Option<String>` | `Default::default()` | PDF version (e.g., "1.7", "2.0") |
| `producer` | `Option<String>` | `Default::default()` | PDF producer (application that created the PDF) |
| `is_encrypted` | `Option<bool>` | `Default::default()` | Whether the PDF is encrypted/password-protected |
| `width` | `Option<i64>` | `Default::default()` | First page width in points (1/72 inch) |
| `height` | `Option<i64>` | `Default::default()` | First page height in points (1/72 inch) |
| `page_count` | `Option<usize>` | `Default::default()` | Total number of pages in the PDF document |


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

```rust
pub fn new(pdf_bytes: Vec<u8>, dpi: Option<i32>, password: Option<String>) -> PdfPageIterator
```

##### from_file()

Create an iterator from a file path.

Reads the file into memory once. Subsequent iterations render from
the owned bytes without re-reading the file.

**Errors:**

Returns an error if the file cannot be read or the PDF is invalid.

**Signature:**

```rust
pub fn from_file(path: Path, dpi: Option<i32>, password: Option<String>) -> PdfPageIterator
```

##### page_count()

Number of pages in the PDF.

**Signature:**

```rust
pub fn page_count(&self) -> usize
```

##### next()

**Signature:**

```rust
pub fn next(&self) -> Option<Item>
```

##### size_hint()

**Signature:**

```rust
pub fn size_hint(&self) -> UsizeOptionUsize
```


---

### PdfRenderer

#### Methods

##### new()

**Signature:**

```rust
pub fn new() -> PdfRenderer
```


---

### PdfTextExtractor

#### Methods

##### new()

**Signature:**

```rust
pub fn new() -> PdfTextExtractor
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

```rust
pub fn default() -> PlainTextExtractor
```

##### name()

**Signature:**

```rust
pub fn name(&self) -> String
```

##### version()

**Signature:**

```rust
pub fn version(&self) -> String
```

##### initialize()

**Signature:**

```rust
pub fn initialize(&self)
```

##### shutdown()

**Signature:**

```rust
pub fn shutdown(&self)
```

##### description()

**Signature:**

```rust
pub fn description(&self) -> String
```

##### author()

**Signature:**

```rust
pub fn author(&self) -> String
```

##### extract_bytes()

**Signature:**

```rust
pub fn extract_bytes(&self, content: Vec<u8>, mime_type: String, config: ExtractionConfig) -> InternalDocument
```

##### supported_mime_types()

**Signature:**

```rust
pub fn supported_mime_types(&self) -> Vec<String>
```

##### priority()

**Signature:**

```rust
pub fn priority(&self) -> i32
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

```rust
pub fn name(&self) -> String
```

##### version()

Returns the semantic version of this plugin.

Should follow semver format: `MAJOR.MINOR.PATCH`

**Signature:**

```rust
pub fn version(&self) -> String
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

```rust
pub fn initialize(&self)
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

```rust
pub fn shutdown(&self)
```

##### description()

Optional plugin description for debugging and logging.

Defaults to empty string if not overridden.

**Signature:**

```rust
pub fn description(&self) -> String
```

##### author()

Optional plugin author information.

Defaults to empty string if not overridden.

**Signature:**

```rust
pub fn author(&self) -> String
```


---

### PluginHealthStatus

Plugin health status information.

Contains diagnostic information about registered plugins for each type.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `ocr_backends_count` | `usize` | — | Number of registered OCR backends |
| `ocr_backends` | `Vec<String>` | — | Names of registered OCR backends |
| `extractors_count` | `usize` | — | Number of registered document extractors |
| `extractors` | `Vec<String>` | — | Names of registered document extractors |
| `post_processors_count` | `usize` | — | Number of registered post-processors |
| `post_processors` | `Vec<String>` | — | Names of registered post-processors |
| `validators_count` | `usize` | — | Number of registered validators |
| `validators` | `Vec<String>` | — | Names of registered validators |

#### Methods

##### check()

Check plugin health and return status.

This function reads all plugin registries and collects information
about registered plugins. It logs warnings if critical plugins are missing.

**Returns:**

`PluginHealthStatus` with counts and names of all registered plugins.

**Signature:**

```rust
pub fn check() -> PluginHealthStatus
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

```rust
pub fn acquire(&self) -> PoolGuard
```

##### size()

Get the current number of objects in the pool.

**Signature:**

```rust
pub fn size(&self) -> usize
```

##### clear()

Clear the pool, discarding all pooled objects.

**Signature:**

```rust
pub fn clear(&self)
```


---

### PoolMetrics

Metrics tracking for pool allocations and reuse patterns.

These metrics help identify pool efficiency and allocation patterns.
Only available when the `pool-metrics` feature is enabled.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `total_acquires` | `AtomicUsize` | `Default::default()` | Total number of acquire calls on this pool |
| `total_cache_hits` | `AtomicUsize` | `Default::default()` | Total number of cache hits (reused objects from pool) |
| `peak_items_stored` | `AtomicUsize` | `Default::default()` | Peak number of objects stored simultaneously in this pool |
| `total_creations` | `AtomicUsize` | `Default::default()` | Total number of objects created by the factory function |

#### Methods

##### hit_rate()

Calculate the cache hit rate as a percentage (0.0-100.0).

**Signature:**

```rust
pub fn hit_rate(&self) -> f64
```

##### snapshot()

Get all metrics as a struct for reporting.

**Signature:**

```rust
pub fn snapshot(&self) -> PoolMetricsSnapshot
```

##### reset()

Reset all metrics to zero.

**Signature:**

```rust
pub fn reset(&self)
```

##### default()

**Signature:**

```rust
pub fn default() -> PoolMetrics
```


---

### PoolMetricsSnapshot

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `total_acquires` | `usize` | — | Total acquires |
| `total_cache_hits` | `usize` | — | Total cache hits |
| `peak_items_stored` | `usize` | — | Peak items stored |
| `total_creations` | `usize` | — | Total creations |


---

### PoolSizeHint

Hint for optimal pool sizing based on document characteristics.

This struct contains the estimated sizes for string and byte buffers
that should be allocated in the pool to handle extraction without
excessive reallocation.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `estimated_total_size` | `usize` | — | Estimated total string buffer pool size in bytes |
| `string_buffer_count` | `usize` | — | Recommended number of string buffers |
| `string_buffer_capacity` | `usize` | — | Recommended capacity per string buffer in bytes |
| `byte_buffer_count` | `usize` | — | Recommended number of byte buffers |
| `byte_buffer_capacity` | `usize` | — | Recommended capacity per byte buffer in bytes |

#### Methods

##### estimated_string_pool_memory()

Calculate the estimated string pool memory in bytes.

This is the total estimated memory for all string buffers.

**Signature:**

```rust
pub fn estimated_string_pool_memory(&self) -> usize
```

##### estimated_byte_pool_memory()

Calculate the estimated byte pool memory in bytes.

This is the total estimated memory for all byte buffers.

**Signature:**

```rust
pub fn estimated_byte_pool_memory(&self) -> usize
```

##### total_pool_memory()

Calculate the total estimated pool memory in bytes.

This includes both string and byte buffer pools.

**Signature:**

```rust
pub fn total_pool_memory(&self) -> usize
```


---

### Position

Horizontal or vertical position.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `relative_from` | `String` | — | Relative from |
| `offset` | `Option<i64>` | `None` | Offset |


---

### PostProcessorConfig

Post-processor configuration.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `enabled` | `bool` | `true` | Enable post-processors |
| `enabled_processors` | `Option<Vec<String>>` | `vec![]` | Whitelist of processor names to run (None = all enabled) |
| `disabled_processors` | `Option<Vec<String>>` | `vec![]` | Blacklist of processor names to skip (None = none disabled) |
| `enabled_set` | `Option<AHashSet>` | `Default::default()` | Pre-computed AHashSet for O(1) enabled processor lookup |
| `disabled_set` | `Option<AHashSet>` | `Default::default()` | Pre-computed AHashSet for O(1) disabled processor lookup |

#### Methods

##### build_lookup_sets()

Pre-compute HashSets for O(1) processor name lookups.

This method converts the enabled/disabled processor Vec to HashSet
for constant-time lookups in the pipeline.

**Signature:**

```rust
pub fn build_lookup_sets(&self)
```

##### default()

**Signature:**

```rust
pub fn default() -> PostProcessorConfig
```


---

### PptExtractionResult

Result of PPT text extraction.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `text` | `String` | — | Extracted text content, with slides separated by double newlines. |
| `slide_count` | `usize` | — | Number of slides found. |
| `metadata` | `PptMetadata` | — | Document metadata. |
| `speaker_notes` | `Vec<String>` | — | Speaker notes text per slide (if available). |


---

### PptExtractor

Native PPT extractor using OLE/CFB parsing.

This extractor handles PowerPoint 97-2003 binary (.ppt) files without
requiring LibreOffice, providing ~50x faster extraction.

#### Methods

##### default()

**Signature:**

```rust
pub fn default() -> PptExtractor
```

##### name()

**Signature:**

```rust
pub fn name(&self) -> String
```

##### version()

**Signature:**

```rust
pub fn version(&self) -> String
```

##### initialize()

**Signature:**

```rust
pub fn initialize(&self)
```

##### shutdown()

**Signature:**

```rust
pub fn shutdown(&self)
```

##### description()

**Signature:**

```rust
pub fn description(&self) -> String
```

##### author()

**Signature:**

```rust
pub fn author(&self) -> String
```

##### extract_bytes()

**Signature:**

```rust
pub fn extract_bytes(&self, content: Vec<u8>, mime_type: String, config: ExtractionConfig) -> InternalDocument
```

##### supported_mime_types()

**Signature:**

```rust
pub fn supported_mime_types(&self) -> Vec<String>
```

##### priority()

**Signature:**

```rust
pub fn priority(&self) -> i32
```


---

### PptMetadata

Metadata extracted from PPT files.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `title` | `Option<String>` | `Default::default()` | Title |
| `subject` | `Option<String>` | `Default::default()` | Subject |
| `author` | `Option<String>` | `Default::default()` | Author |
| `last_author` | `Option<String>` | `Default::default()` | Last author |


---

### PptxAppProperties

Application properties from docProps/app.xml for PPTX

Contains PowerPoint-specific document metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `application` | `Option<String>` | `Default::default()` | Application name (e.g., "Microsoft Office PowerPoint") |
| `app_version` | `Option<String>` | `Default::default()` | Application version |
| `total_time` | `Option<i32>` | `Default::default()` | Total editing time in minutes |
| `company` | `Option<String>` | `Default::default()` | Company name |
| `doc_security` | `Option<i32>` | `Default::default()` | Document security level |
| `scale_crop` | `Option<bool>` | `Default::default()` | Scale crop flag |
| `links_up_to_date` | `Option<bool>` | `Default::default()` | Links up to date flag |
| `shared_doc` | `Option<bool>` | `Default::default()` | Shared document flag |
| `hyperlinks_changed` | `Option<bool>` | `Default::default()` | Hyperlinks changed flag |
| `slides` | `Option<i32>` | `Default::default()` | Number of slides |
| `notes` | `Option<i32>` | `Default::default()` | Number of notes |
| `hidden_slides` | `Option<i32>` | `Default::default()` | Number of hidden slides |
| `multimedia_clips` | `Option<i32>` | `Default::default()` | Number of multimedia clips |
| `presentation_format` | `Option<String>` | `Default::default()` | Presentation format (e.g., "Widescreen", "Standard") |
| `slide_titles` | `Vec<String>` | `vec![]` | Slide titles |


---

### PptxExtractionOptions

Options for PPTX content extraction.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `extract_images` | `bool` | `true` | Whether to extract embedded images. |
| `page_config` | `Option<PageConfig>` | `Default::default()` | Optional page configuration for boundary tracking. |
| `plain` | `bool` | `false` | Whether to output plain text (no markdown). |
| `include_structure` | `bool` | `false` | Whether to build the `DocumentStructure` tree. |
| `inject_placeholders` | `bool` | `true` | Whether to emit `![alt](target)` references in markdown output. |

#### Methods

##### default()

**Signature:**

```rust
pub fn default() -> PptxExtractionOptions
```


---

### PptxExtractionResult

PowerPoint (PPTX) extraction result.

Contains extracted slide content, metadata, and embedded images/tables.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `content` | `String` | — | Extracted text content from all slides |
| `metadata` | `PptxMetadata` | — | Presentation metadata |
| `slide_count` | `usize` | — | Total number of slides |
| `image_count` | `usize` | — | Total number of embedded images |
| `table_count` | `usize` | — | Total number of tables |
| `images` | `Vec<ExtractedImage>` | — | Extracted images from the presentation |
| `page_structure` | `Option<PageStructure>` | `None` | Slide structure with boundaries (when page tracking is enabled) |
| `page_contents` | `Option<Vec<PageContent>>` | `None` | Per-slide content (when page tracking is enabled) |
| `document` | `Option<DocumentStructure>` | `None` | Structured document representation |
| `hyperlinks` | `Vec<StringOptionString>` | — | Hyperlinks discovered in slides as (url, optional_label) pairs. |
| `office_metadata` | `HashMap<String, String>` | — | Office metadata extracted from docProps/core.xml and docProps/app.xml. Contains keys like "title", "author", "created_by", "subject", "keywords", "modified_by", "created_at", "modified_at", etc. |


---

### PptxExtractor

PowerPoint presentation extractor.

Supports: .pptx, .pptm, .ppsx

#### Methods

##### default()

**Signature:**

```rust
pub fn default() -> PptxExtractor
```

##### name()

**Signature:**

```rust
pub fn name(&self) -> String
```

##### version()

**Signature:**

```rust
pub fn version(&self) -> String
```

##### initialize()

**Signature:**

```rust
pub fn initialize(&self)
```

##### shutdown()

**Signature:**

```rust
pub fn shutdown(&self)
```

##### extract_bytes()

**Signature:**

```rust
pub fn extract_bytes(&self, content: Vec<u8>, mime_type: String, config: ExtractionConfig) -> InternalDocument
```

##### extract_file()

**Signature:**

```rust
pub fn extract_file(&self, path: PathBuf, mime_type: String, config: ExtractionConfig) -> InternalDocument
```

##### supported_mime_types()

**Signature:**

```rust
pub fn supported_mime_types(&self) -> Vec<String>
```

##### priority()

**Signature:**

```rust
pub fn priority(&self) -> i32
```


---

### PptxMetadata

PowerPoint presentation metadata.

Extracted from PPTX files containing slide counts and presentation details.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `slide_count` | `usize` | — | Total number of slides in the presentation |
| `slide_names` | `Vec<String>` | — | Names of slides (if available) |
| `image_count` | `Option<usize>` | `None` | Number of embedded images |
| `table_count` | `Option<usize>` | `None` | Number of tables |


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

```rust
pub fn default() -> PstExtractor
```

##### name()

**Signature:**

```rust
pub fn name(&self) -> String
```

##### version()

**Signature:**

```rust
pub fn version(&self) -> String
```

##### initialize()

**Signature:**

```rust
pub fn initialize(&self)
```

##### shutdown()

**Signature:**

```rust
pub fn shutdown(&self)
```

##### extract_sync()

**Signature:**

```rust
pub fn extract_sync(&self, content: Vec<u8>, mime_type: String, config: ExtractionConfig) -> InternalDocument
```

##### extract_bytes()

**Signature:**

```rust
pub fn extract_bytes(&self, content: Vec<u8>, mime_type: String, config: ExtractionConfig) -> InternalDocument
```

##### supported_mime_types()

**Signature:**

```rust
pub fn supported_mime_types(&self) -> Vec<String>
```

##### priority()

**Signature:**

```rust
pub fn priority(&self) -> i32
```

##### as_sync_extractor()

**Signature:**

```rust
pub fn as_sync_extractor(&self) -> Option<SyncExtractor>
```


---

### PstMetadata

Outlook PST archive metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `message_count` | `usize` | `Default::default()` | Number of message |


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

```rust
pub fn name(&self) -> String
```

##### version()

**Signature:**

```rust
pub fn version(&self) -> String
```

##### initialize()

**Signature:**

```rust
pub fn initialize(&self)
```

##### shutdown()

**Signature:**

```rust
pub fn shutdown(&self)
```

##### process()

**Signature:**

```rust
pub fn process(&self, result: ExtractionResult, config: ExtractionConfig)
```

##### processing_stage()

**Signature:**

```rust
pub fn processing_stage(&self) -> ProcessingStage
```

##### should_process()

**Signature:**

```rust
pub fn should_process(&self, result: ExtractionResult, config: ExtractionConfig) -> bool
```

##### estimated_duration_ms()

**Signature:**

```rust
pub fn estimated_duration_ms(&self, result: ExtractionResult) -> u64
```


---

### RakeParams

RAKE-specific parameters.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `min_word_length` | `usize` | `1` | Minimum word length to consider (default: 1). |
| `max_words_per_phrase` | `usize` | `3` | Maximum words in a keyword phrase (default: 3). |

#### Methods

##### default()

**Signature:**

```rust
pub fn default() -> RakeParams
```


---

### RecognizedTable

Pre-computed table markdown for a table detection region.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `detection_bbox` | `BBox` | — | Detection bbox that this table corresponds to (for matching). |
| `cells` | `Vec<Vec<String>>` | — | Table cells as a 2D vector (rows x columns). |
| `markdown` | `String` | — | Rendered markdown table. |


---

### Record

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `tag_id` | `u16` | — | Tag id |
| `data` | `Vec<u8>` | — | Data |

#### Methods

##### parse()

**Signature:**

```rust
pub fn parse(reader: StreamReader) -> Record
```

##### data_reader()

Return a fresh `StreamReader` over this record's data bytes.

**Signature:**

```rust
pub fn data_reader(&self) -> StreamReader
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

```rust
pub fn reset(&self)
```


---

### Relationship

A relationship between two elements in the document.

During extraction, targets may be unresolved keys (`RelationshipTarget.Key`).
The derivation step resolves these to indices using the element anchor index.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `source` | `u32` | — | Index of the source element in `InternalDocument.elements`. |
| `target` | `RelationshipTarget` | — | Target of the relationship (resolved index or unresolved key). |
| `kind` | `RelationshipKind` | — | Semantic kind of the relationship. |


---

### ResolvedStyle

Fully resolved (flattened) style after walking the inheritance chain.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `paragraph_properties` | `ParagraphProperties` | `Default::default()` | Paragraph properties (paragraph properties) |
| `run_properties` | `RunProperties` | `Default::default()` | Run properties (run properties) |


---

### RowProperties

Row-level properties from `<w:trPr>`.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `height` | `Option<i32>` | `Default::default()` | Height |
| `height_rule` | `Option<String>` | `Default::default()` | Height rule |
| `is_header` | `bool` | `Default::default()` | Whether header |
| `cant_split` | `bool` | `Default::default()` | Cant split |


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

##### build_internal_document()

Build an `InternalDocument` from RST content.

Handles sections, paragraphs, code blocks, tables, footnotes, citations,
and cross-references.

**Signature:**

```rust
pub fn build_internal_document(content: String, inject_placeholders: bool) -> InternalDocument
```

##### default()

**Signature:**

```rust
pub fn default() -> RstExtractor
```

##### name()

**Signature:**

```rust
pub fn name(&self) -> String
```

##### version()

**Signature:**

```rust
pub fn version(&self) -> String
```

##### initialize()

**Signature:**

```rust
pub fn initialize(&self)
```

##### shutdown()

**Signature:**

```rust
pub fn shutdown(&self)
```

##### description()

**Signature:**

```rust
pub fn description(&self) -> String
```

##### author()

**Signature:**

```rust
pub fn author(&self) -> String
```

##### extract_bytes()

**Signature:**

```rust
pub fn extract_bytes(&self, content: Vec<u8>, mime_type: String, config: ExtractionConfig) -> InternalDocument
```

##### extract_file()

**Signature:**

```rust
pub fn extract_file(&self, path: PathBuf, mime_type: String, config: ExtractionConfig) -> InternalDocument
```

##### supported_mime_types()

**Signature:**

```rust
pub fn supported_mime_types(&self) -> Vec<String>
```

##### priority()

**Signature:**

```rust
pub fn priority(&self) -> i32
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

##### from_file()

Load a Docling RT-DETR ONNX model from a file.

**Signature:**

```rust
pub fn from_file(path: String) -> RtDetrModel
```

##### detect()

**Signature:**

```rust
pub fn detect(&self, img: RgbImage) -> Vec<LayoutDetection>
```

##### detect_with_threshold()

**Signature:**

```rust
pub fn detect_with_threshold(&self, img: RgbImage, threshold: f32) -> Vec<LayoutDetection>
```

##### detect_batch()

**Signature:**

```rust
pub fn detect_batch(&self, images: Vec<RgbImage>, threshold: Option<f32>) -> Vec<Vec<LayoutDetection>>
```

##### name()

**Signature:**

```rust
pub fn name(&self) -> String
```


---

### RtfExtractor

Native Rust RTF extractor.

Extracts text content, metadata, and structure from RTF documents

#### Methods

##### default()

**Signature:**

```rust
pub fn default() -> RtfExtractor
```

##### name()

**Signature:**

```rust
pub fn name(&self) -> String
```

##### version()

**Signature:**

```rust
pub fn version(&self) -> String
```

##### initialize()

**Signature:**

```rust
pub fn initialize(&self)
```

##### shutdown()

**Signature:**

```rust
pub fn shutdown(&self)
```

##### description()

**Signature:**

```rust
pub fn description(&self) -> String
```

##### author()

**Signature:**

```rust
pub fn author(&self) -> String
```

##### extract_bytes()

**Signature:**

```rust
pub fn extract_bytes(&self, content: Vec<u8>, mime_type: String, config: ExtractionConfig) -> InternalDocument
```

##### supported_mime_types()

**Signature:**

```rust
pub fn supported_mime_types(&self) -> Vec<String>
```

##### priority()

**Signature:**

```rust
pub fn priority(&self) -> i32
```


---

### Run

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `text` | `String` | `Default::default()` | Text |
| `bold` | `bool` | `Default::default()` | Bold |
| `italic` | `bool` | `Default::default()` | Italic |
| `underline` | `bool` | `Default::default()` | Underline |
| `strikethrough` | `bool` | `Default::default()` | Strikethrough |
| `subscript` | `bool` | `Default::default()` | Subscript |
| `superscript` | `bool` | `Default::default()` | Superscript |
| `font_size` | `Option<u32>` | `Default::default()` | Font size in half-points (from `w:sz`). |
| `font_color` | `Option<String>` | `Default::default()` | Font color as "RRGGBB" hex (from `w:color`). |
| `highlight` | `Option<String>` | `Default::default()` | Highlight color name (from `w:highlight`). |
| `hyperlink_url` | `Option<String>` | `Default::default()` | Hyperlink url |
| `math_latex` | `Option<StringBool>` | `Default::default()` | LaTeX math content: (latex_source, is_display_math). When set, this run represents an equation and `text` is ignored. |

#### Methods

##### to_markdown()

Render this run as markdown with formatting markers.

**Signature:**

```rust
pub fn to_markdown(&self) -> String
```


---

### RunProperties

Run-level formatting properties (bold, italic, font, size, color, etc.).

All fields are `Option` so that inheritance resolution can distinguish
"not set" (`None`) from "explicitly set" (`Some`).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `bold` | `Option<bool>` | `Default::default()` | Bold |
| `italic` | `Option<bool>` | `Default::default()` | Italic |
| `underline` | `Option<bool>` | `Default::default()` | Underline |
| `strikethrough` | `Option<bool>` | `Default::default()` | Strikethrough |
| `color` | `Option<String>` | `Default::default()` | Hex RGB color, e.g. `"2F5496"`. |
| `font_size_half_points` | `Option<i32>` | `Default::default()` | Font size in half-points (`w:sz` val). Divide by 2 to get points. |
| `font_ascii` | `Option<String>` | `Default::default()` | ASCII font family (`w:rFonts w:ascii`). |
| `font_ascii_theme` | `Option<String>` | `Default::default()` | ASCII theme font (`w:rFonts w:asciiTheme`). |
| `vert_align` | `Option<String>` | `Default::default()` | Vertical alignment: "superscript", "subscript", or "baseline". |
| `font_h_ansi` | `Option<String>` | `Default::default()` | High ANSI font family (w:rFonts w:hAnsi). |
| `font_cs` | `Option<String>` | `Default::default()` | Complex script font family (w:rFonts w:cs). |
| `font_east_asia` | `Option<String>` | `Default::default()` | East Asian font family (w:rFonts w:eastAsia). |
| `highlight` | `Option<String>` | `Default::default()` | Highlight color name (e.g., "yellow", "green", "cyan"). |
| `caps` | `Option<bool>` | `Default::default()` | All caps text transformation. |
| `small_caps` | `Option<bool>` | `Default::default()` | Small caps text transformation. |
| `shadow` | `Option<bool>` | `Default::default()` | Text shadow effect. |
| `outline` | `Option<bool>` | `Default::default()` | Text outline effect. |
| `emboss` | `Option<bool>` | `Default::default()` | Text emboss effect. |
| `imprint` | `Option<bool>` | `Default::default()` | Text imprint (engrave) effect. |
| `char_spacing` | `Option<i32>` | `Default::default()` | Character spacing in twips (from w:spacing w:val). |
| `position` | `Option<i32>` | `Default::default()` | Vertical position offset in half-points (from w:position w:val). |
| `kern` | `Option<i32>` | `Default::default()` | Kerning threshold in half-points (from w:kern w:val). |
| `theme_color` | `Option<String>` | `Default::default()` | Theme color reference (e.g., "accent1", "dk1"). |
| `theme_tint` | `Option<String>` | `Default::default()` | Theme color tint modification (hex value). |
| `theme_shade` | `Option<String>` | `Default::default()` | Theme color shade modification (hex value). |


---

### Section

A body-text section containing a flat list of paragraphs.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `paragraphs` | `Vec<Paragraph>` | `vec![]` | Paragraphs |


---

### SectionProperties

DOCX section properties parsed from `w:sectPr` element.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `page_width_twips` | `Option<i32>` | `Default::default()` | Page width in twips (from `w:pgSz w:w`). |
| `page_height_twips` | `Option<i32>` | `Default::default()` | Page height in twips (from `w:pgSz w:h`). |
| `orientation` | `Option<Orientation>` | `Orientation::Portrait` | Page orientation (from `w:pgSz w:orient`). |
| `margins` | `PageMargins` | `Default::default()` | Page margins (from `w:pgMar`). |
| `columns` | `ColumnLayout` | `Default::default()` | Column layout (from `w:cols`). |
| `doc_grid_line_pitch` | `Option<i32>` | `Default::default()` | Document grid line pitch in twips (from `w:docGrid w:linePitch`). |

#### Methods

##### page_width_points()

Convert page width from twips to points.

**Signature:**

```rust
pub fn page_width_points(&self) -> Option<f64>
```

##### page_height_points()

Convert page height from twips to points.

**Signature:**

```rust
pub fn page_height_points(&self) -> Option<f64>
```


---

### SecurityLimits

Configuration for security limits across extractors.

All limits are intentionally conservative to prevent DoS attacks
while still supporting legitimate documents.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `max_archive_size` | `usize` | `Default::default()` | Maximum uncompressed size for archives (500 MB) |
| `max_compression_ratio` | `usize` | `100` | Maximum compression ratio before flagging as potential bomb (100:1) |
| `max_files_in_archive` | `usize` | `10000` | Maximum number of files in archive (10,000) |
| `max_nesting_depth` | `usize` | `100` | Maximum nesting depth for structures (100) |
| `max_entity_length` | `usize` | `32` | Maximum entity/string length (32) |
| `max_content_size` | `usize` | `Default::default()` | Maximum string growth per document (100 MB) |
| `max_iterations` | `usize` | `10000000` | Maximum iterations per operation |
| `max_xml_depth` | `usize` | `100` | Maximum XML depth (100 levels) |
| `max_table_cells` | `usize` | `100000` | Maximum cells per table (100,000) |

#### Methods

##### default()

**Signature:**

```rust
pub fn default() -> SecurityLimits
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
| `host` | `String` | `Default::default()` | Server host address (e.g., "127.0.0.1", "0.0.0.0") |
| `port` | `u16` | `Default::default()` | Server port number |
| `cors_origins` | `Vec<String>` | `vec![]` | CORS allowed origins. Empty vector means allow all origins. If this is an empty vector, the server will accept requests from any origin. If populated with specific origins (e.g., ["https://example.com"]), only those origins will be allowed. |
| `max_request_body_bytes` | `usize` | `Default::default()` | Maximum size of request body in bytes (default: 100 MB) |
| `max_multipart_field_bytes` | `usize` | `Default::default()` | Maximum size of multipart fields in bytes (default: 100 MB) |

#### Methods

##### default()

**Signature:**

```rust
pub fn default() -> ServerConfig
```

##### listen_addr()

Get the server listen address (host:port).

**Signature:**

```rust
pub fn listen_addr(&self) -> String
```

##### cors_allows_all()

Check if CORS allows all origins.

Returns `true` if the `cors_origins` vector is empty, meaning all origins
are allowed. Returns `false` if specific origins are configured.

**Signature:**

```rust
pub fn cors_allows_all(&self) -> bool
```

##### is_origin_allowed()

Check if a given origin is allowed by CORS configuration.

Returns `true` if:
- CORS allows all origins (empty origins list), or
- The given origin is in the allowed origins list

**Signature:**

```rust
pub fn is_origin_allowed(&self, origin: String) -> bool
```

##### max_request_body_mb()

Get maximum request body size in megabytes (rounded up).

**Signature:**

```rust
pub fn max_request_body_mb(&self) -> usize
```

##### max_multipart_field_mb()

Get maximum multipart field size in megabytes (rounded up).

**Signature:**

```rust
pub fn max_multipart_field_mb(&self) -> usize
```

##### apply_env_overrides()

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

```rust
pub fn apply_env_overrides(&self)
```

##### from_file()

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

```rust
pub fn from_file(path: Path) -> ServerConfig
```

##### from_toml_file()

Load server configuration from a TOML file.

**Errors:**

Returns `KreuzbergError.Validation` if the file doesn't exist or is invalid TOML.

**Signature:**

```rust
pub fn from_toml_file(path: Path) -> ServerConfig
```

##### from_yaml_file()

Load server configuration from a YAML file.

**Errors:**

Returns `KreuzbergError.Validation` if the file doesn't exist or is invalid YAML.

**Signature:**

```rust
pub fn from_yaml_file(path: Path) -> ServerConfig
```

##### from_json_file()

Load server configuration from a JSON file.

**Errors:**

Returns `KreuzbergError.Validation` if the file doesn't exist or is invalid JSON.

**Signature:**

```rust
pub fn from_json_file(path: Path) -> ServerConfig
```


---

### SevenZExtractor

7z archive extractor.

Extracts file lists and text content from 7z archives.

#### Methods

##### default()

**Signature:**

```rust
pub fn default() -> SevenZExtractor
```

##### name()

**Signature:**

```rust
pub fn name(&self) -> String
```

##### version()

**Signature:**

```rust
pub fn version(&self) -> String
```

##### initialize()

**Signature:**

```rust
pub fn initialize(&self)
```

##### shutdown()

**Signature:**

```rust
pub fn shutdown(&self)
```

##### description()

**Signature:**

```rust
pub fn description(&self) -> String
```

##### author()

**Signature:**

```rust
pub fn author(&self) -> String
```

##### extract_bytes()

**Signature:**

```rust
pub fn extract_bytes(&self, content: Vec<u8>, mime_type: String, config: ExtractionConfig) -> InternalDocument
```

##### supported_mime_types()

**Signature:**

```rust
pub fn supported_mime_types(&self) -> Vec<String>
```

##### priority()

**Signature:**

```rust
pub fn priority(&self) -> i32
```

##### as_sync_extractor()

**Signature:**

```rust
pub fn as_sync_extractor(&self) -> Option<SyncExtractor>
```

##### extract_sync()

**Signature:**

```rust
pub fn extract_sync(&self, content: Vec<u8>, mime_type: String, config: ExtractionConfig) -> InternalDocument
```


---

### SlanetCell

A single cell detected by SLANeXT.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `polygon` | `F328` | — | Bounding box polygon in image pixel coordinates. Format: [x1, y1, x2, y2, x3, y3, x4, y4] (4 corners, clockwise from top-left). |
| `bbox` | `F324` | — | Axis-aligned bounding box derived from polygon: [left, top, right, bottom]. |
| `row` | `usize` | — | Row index in the table (0-based). |
| `col` | `usize` | — | Column index within the row (0-based). |


---

### SlanetModel

SLANeXT table structure recognition model.

Wraps an ORT session for SLANeXT ONNX model and provides preprocessing,
inference, and post-processing in a single `recognize` call.

#### Methods

##### from_file()

Load a SLANeXT ONNX model from a file path.

**Signature:**

```rust
pub fn from_file(path: String) -> SlanetModel
```

##### recognize()

Recognize table structure from a cropped table image.

Returns a `SlanetResult` with detected cells, grid dimensions,
and structure tokens.

**Signature:**

```rust
pub fn recognize(&self, table_img: RgbImage) -> SlanetResult
```


---

### SlanetResult

SLANeXT recognition result for a single table image.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `cells` | `Vec<SlanetCell>` | — | Detected cells with bounding boxes and grid positions. |
| `num_rows` | `usize` | — | Number of rows in the table. |
| `num_cols` | `usize` | — | Maximum number of columns across all rows. |
| `confidence` | `f32` | — | Average structure prediction confidence. |
| `structure_tokens` | `Vec<String>` | — | Raw HTML structure tokens (for debugging). |


---

### StreamReader

#### Methods

##### read_u8()

**Signature:**

```rust
pub fn read_u8(&self) -> u8
```

##### read_u16()

**Signature:**

```rust
pub fn read_u16(&self) -> u16
```

##### read_u32()

**Signature:**

```rust
pub fn read_u32(&self) -> u32
```

##### read_bytes()

**Signature:**

```rust
pub fn read_bytes(&self, len: usize) -> Vec<u8>
```

##### position()

Current byte position within the stream.

**Signature:**

```rust
pub fn position(&self) -> u64
```

##### remaining()

Number of bytes remaining from the current position to the end.

**Signature:**

```rust
pub fn remaining(&self) -> usize
```


---

### StringBufferPool

Convenience type alias for a pooled String.


---

### StringGrowthValidator

Helper struct for tracking and validating string growth.

#### Methods

##### check_append()

Validate and update size after appending.

**Returns:**
* `Ok(())` if size is within limits
* `Err(SecurityError)` if size exceeds limit

**Signature:**

```rust
pub fn check_append(&self, len: usize)
```

##### current_size()

Get current size.

**Signature:**

```rust
pub fn current_size(&self) -> usize
```


---

### StructuredData

Structured data (Schema.org, microdata, RDFa) block.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `data_type` | `StructuredDataType` | — | Type of structured data |
| `raw_json` | `String` | — | Raw JSON string representation |
| `schema_type` | `Option<String>` | `None` | Schema type if detectable (e.g., "Article", "Event", "Product") |


---

### StructuredDataResult

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `content` | `String` | — | The extracted text content |
| `format` | `Str` | — | Format (str) |
| `metadata` | `HashMap<String, String>` | — | Document metadata |
| `text_fields` | `Vec<String>` | — | Text fields |


---

### StructuredExtractionConfig

Configuration for LLM-based structured data extraction.

Sends extracted document content to a VLM with a JSON schema,
returning structured data that conforms to the schema.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `schema` | `serde_json::Value` | — | JSON Schema defining the desired output structure. |
| `schema_name` | `String` | — | Schema name passed to the LLM's structured output mode. |
| `schema_description` | `Option<String>` | `None` | Optional schema description for the LLM. |
| `strict` | `bool` | — | Enable strict mode — output must exactly match the schema. |
| `prompt` | `Option<String>` | `None` | Custom Jinja2 extraction prompt template. When `None`, a default template is used. Available template variables: - `{{ content }}` — The extracted document text. - `{{ schema }}` — The JSON schema as a formatted string. - `{{ schema_name }}` — The schema name. - `{{ schema_description }}` — The schema description (may be empty). |
| `llm` | `LlmConfig` | — | LLM configuration for the extraction. |


---

### StructuredExtractor

Structured data extractor supporting JSON, JSONL/NDJSON, YAML, and TOML.

#### Methods

##### default()

**Signature:**

```rust
pub fn default() -> StructuredExtractor
```

##### name()

**Signature:**

```rust
pub fn name(&self) -> String
```

##### version()

**Signature:**

```rust
pub fn version(&self) -> String
```

##### initialize()

**Signature:**

```rust
pub fn initialize(&self)
```

##### shutdown()

**Signature:**

```rust
pub fn shutdown(&self)
```

##### extract_bytes()

**Signature:**

```rust
pub fn extract_bytes(&self, content: Vec<u8>, mime_type: String, config: ExtractionConfig) -> InternalDocument
```

##### supported_mime_types()

**Signature:**

```rust
pub fn supported_mime_types(&self) -> Vec<String>
```

##### priority()

**Signature:**

```rust
pub fn priority(&self) -> i32
```


---

### StyleCatalog

Catalog of all styles parsed from `word/styles.xml`, plus document defaults.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `styles` | `AHashMap` | `Default::default()` | Styles (a hash map) |
| `default_paragraph_properties` | `ParagraphProperties` | `Default::default()` | Default paragraph properties (paragraph properties) |
| `default_run_properties` | `RunProperties` | `Default::default()` | Default run properties (run properties) |

#### Methods

##### resolve_style()

Resolve a style by walking its `basedOn` inheritance chain.

The resolution order is:
1. Document defaults (`<w:docDefaults>`)
2. Base style chain (walking `basedOn` from root to leaf)
3. The style itself

For `Option` fields, a child value of `Some(x)` overrides the parent.
A value of `None` inherits from the parent. For boolean toggle properties,
`Some(false)` explicitly disables the property.

The chain depth is limited to 20 to prevent infinite loops from circular references.

**Signature:**

```rust
pub fn resolve_style(&self, style_id: String) -> ResolvedStyle
```


---

### StyleDefinition

A single style definition parsed from `<w:style>` in `word/styles.xml`.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `id` | `String` | — | The style ID (`w:styleId` attribute). |
| `name` | `Option<String>` | `None` | Human-readable name (`<w:name w:val="..."/>`). |
| `style_type` | `StyleType` | — | Style type: paragraph, character, table, or numbering. |
| `based_on` | `Option<String>` | `None` | ID of the parent style (`<w:basedOn w:val="..."/>`). |
| `next_style` | `Option<String>` | `None` | ID of the style to apply to the next paragraph (`<w:next w:val="..."/>`). |
| `is_default` | `bool` | — | Whether this is the default style for its type. |
| `paragraph_properties` | `ParagraphProperties` | — | Paragraph properties defined directly on this style. |
| `run_properties` | `RunProperties` | — | Run properties defined directly on this style. |


---

### StyledHtmlRenderer

Styled HTML renderer.

Implements the `Renderer` trait; registered as `"html"` when the
`html` feature is active. Configuration is baked in at
construction time — no per-render allocation for CSS resolution.

#### Methods

##### new()

**Signature:**

```rust
pub fn new(config: HtmlOutputConfig) -> StyledHtmlRenderer
```

##### name()

**Signature:**

```rust
pub fn name(&self) -> String
```

##### render()

**Signature:**

```rust
pub fn render(&self, doc: InternalDocument) -> String
```


---

### SupportedFormat

A supported document format entry.

Represents a file extension and its corresponding MIME type that Kreuzberg can process.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `extension` | `String` | — | File extension (without leading dot), e.g., "pdf", "docx" |
| `mime_type` | `String` | — | MIME type string, e.g., "application/pdf" |


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

##### extract_sync()

Extract content from a byte array synchronously.

This method performs extraction without requiring an async runtime.
It is called by `extract_bytes_sync()` when the `tokio-runtime` feature is disabled.

**Returns:**

An `InternalDocument` containing the extracted elements, metadata, and tables.

**Signature:**

```rust
pub fn extract_sync(&self, content: Vec<u8>, mime_type: String, config: ExtractionConfig) -> InternalDocument
```


---

### Table

Extracted table structure.

Represents a table detected and extracted from a document (PDF, image, etc.).
Tables are converted to both structured cell data and Markdown format.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `cells` | `Vec<Vec<String>>` | — | Table cells as a 2D vector (rows × columns) |
| `markdown` | `String` | — | Markdown representation of the table |
| `page_number` | `usize` | — | Page number where the table was found (1-indexed) |
| `bounding_box` | `Option<BoundingBox>` | `None` | Bounding box of the table on the page (PDF coordinates: x0=left, y0=bottom, x1=right, y1=top). Only populated for PDF-extracted tables when position data is available. |


---

### TableBorders

Borders for a table (6 borders: top, bottom, left, right, insideH, insideV).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `top` | `Option<BorderStyle>` | `Default::default()` | Top (border style) |
| `bottom` | `Option<BorderStyle>` | `Default::default()` | Bottom (border style) |
| `left` | `Option<BorderStyle>` | `Default::default()` | Left (border style) |
| `right` | `Option<BorderStyle>` | `Default::default()` | Right (border style) |
| `inside_h` | `Option<BorderStyle>` | `Default::default()` | Inside h (border style) |
| `inside_v` | `Option<BorderStyle>` | `Default::default()` | Inside v (border style) |


---

### TableCell

Individual table cell with content and optional styling.

Future extension point for rich table support with cell-level metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `content` | `String` | — | Cell content as text |
| `row_span` | `usize` | — | Row span (number of rows this cell spans) |
| `col_span` | `usize` | — | Column span (number of columns this cell spans) |
| `is_header` | `bool` | — | Whether this is a header cell |


---

### TableClassifier

PP-LCNet table classifier model.

#### Methods

##### from_file()

Load the table classifier ONNX model from a file path.

**Signature:**

```rust
pub fn from_file(path: String) -> TableClassifier
```

##### classify()

Classify a cropped table image as wired or wireless.

**Signature:**

```rust
pub fn classify(&self, table_img: RgbImage) -> TableType
```


---

### TableGrid

Structured table grid with cell-level metadata.

Stores row/column dimensions and a flat list of cells with position info.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `rows` | `u32` | — | Number of rows in the table. |
| `cols` | `u32` | — | Number of columns in the table. |
| `cells` | `Vec<GridCell>` | — | All cells in row-major order. |


---

### TableLook

Table look bitmask/flags controlling conditional formatting bands.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `first_row` | `bool` | `Default::default()` | First row |
| `last_row` | `bool` | `Default::default()` | Last row |
| `first_column` | `bool` | `Default::default()` | First column |
| `last_column` | `bool` | `Default::default()` | Last column |
| `no_h_band` | `bool` | `Default::default()` | No h band |
| `no_v_band` | `bool` | `Default::default()` | No v band |


---

### TableProperties

Table-level properties from `<w:tblPr>`.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `style_id` | `Option<String>` | `Default::default()` | Style id |
| `width` | `Option<TableWidth>` | `Default::default()` | Width (table width) |
| `alignment` | `Option<String>` | `Default::default()` | Alignment |
| `layout` | `Option<String>` | `Default::default()` | Layout |
| `look` | `Option<TableLook>` | `Default::default()` | Look (table look) |
| `borders` | `Option<TableBorders>` | `Default::default()` | Borders (table borders) |
| `cell_margins` | `Option<CellMargins>` | `Default::default()` | Cell margins (cell margins) |
| `indent` | `Option<TableWidth>` | `Default::default()` | Indent (table width) |
| `caption` | `Option<String>` | `Default::default()` | Caption |


---

### TableRow

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `cells` | `Vec<TableCell>` | `vec![]` | Cells |
| `properties` | `Option<RowProperties>` | `Default::default()` | Properties (row properties) |


---

### TableValidator

Helper struct for validating table cell counts.

#### Methods

##### add_cells()

Add cells to table and validate.

**Returns:**
* `Ok(())` if cell count is within limits
* `Err(SecurityError)` if cell count exceeds limit

**Signature:**

```rust
pub fn add_cells(&self, count: usize)
```

##### current_cells()

Get current cell count.

**Signature:**

```rust
pub fn current_cells(&self) -> usize
```


---

### TableWidth

Width specification used for tables and cells.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `value` | `i32` | — | Value |
| `width_type` | `String` | — | Width type |


---

### TarExtractor

TAR archive extractor.

Extracts file lists and text content from TAR archives.

#### Methods

##### default()

**Signature:**

```rust
pub fn default() -> TarExtractor
```

##### name()

**Signature:**

```rust
pub fn name(&self) -> String
```

##### version()

**Signature:**

```rust
pub fn version(&self) -> String
```

##### initialize()

**Signature:**

```rust
pub fn initialize(&self)
```

##### shutdown()

**Signature:**

```rust
pub fn shutdown(&self)
```

##### description()

**Signature:**

```rust
pub fn description(&self) -> String
```

##### author()

**Signature:**

```rust
pub fn author(&self) -> String
```

##### extract_bytes()

**Signature:**

```rust
pub fn extract_bytes(&self, content: Vec<u8>, mime_type: String, config: ExtractionConfig) -> InternalDocument
```

##### supported_mime_types()

**Signature:**

```rust
pub fn supported_mime_types(&self) -> Vec<String>
```

##### priority()

**Signature:**

```rust
pub fn priority(&self) -> i32
```

##### as_sync_extractor()

**Signature:**

```rust
pub fn as_sync_extractor(&self) -> Option<SyncExtractor>
```

##### extract_sync()

**Signature:**

```rust
pub fn extract_sync(&self, content: Vec<u8>, mime_type: String, config: ExtractionConfig) -> InternalDocument
```


---

### TatrDetection

A single TATR detection result.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `bbox` | `F324` | — | Bounding box in crop-pixel coordinates: `[x1, y1, x2, y2]`. |
| `confidence` | `f32` | — | Detection confidence score (0.0..1.0). |
| `class` | `TatrClass` | — | Detected class. |


---

### TatrModel

TATR (Table Transformer) table structure recognition model.

Wraps an ORT session for the TATR ONNX model and provides preprocessing,
inference, and post-processing in a single `recognize` call.

#### Methods

##### from_file()

Load a TATR ONNX model from a file path.

Uses the default execution provider selection from `build_session`
with a CPU-only fallback if the platform EP fails.

**Signature:**

```rust
pub fn from_file(path: String) -> TatrModel
```

##### recognize()

Recognize table structure from a cropped table image.

Returns a `TatrResult` with detected rows, columns, headers, and
spanning cells in the input image's pixel coordinate space.

**Signature:**

```rust
pub fn recognize(&self, table_img: RgbImage) -> TatrResult
```


---

### TatrResult

Aggregated TATR recognition result with detections separated by class.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `rows` | `Vec<TatrDetection>` | — | Detected rows, sorted top-to-bottom by `y2`. |
| `columns` | `Vec<TatrDetection>` | — | Detected columns, sorted left-to-right by `x2`. |
| `headers` | `Vec<TatrDetection>` | — | Detected headers (ColumnHeader and ProjectedRowHeader). |
| `spanning` | `Vec<TatrDetection>` | — | Detected spanning cells. |


---

### TessdataManager

Manages tessdata file downloading, caching, and manifest generation.

#### Methods

##### cache_dir()

Get the cache directory path.

**Signature:**

```rust
pub fn cache_dir(&self) -> PathBuf
```

##### is_language_cached()

Check if a specific language traineddata file is cached.

**Signature:**

```rust
pub fn is_language_cached(&self, lang: String) -> bool
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

```rust
pub fn new() -> TesseractBackend
```

##### with_cache_dir()

Create a new Tesseract backend with custom cache directory.

**Signature:**

```rust
pub fn with_cache_dir(cache_dir: PathBuf) -> TesseractBackend
```

##### default()

**Signature:**

```rust
pub fn default() -> TesseractBackend
```

##### name()

**Signature:**

```rust
pub fn name(&self) -> String
```

##### version()

**Signature:**

```rust
pub fn version(&self) -> String
```

##### initialize()

**Signature:**

```rust
pub fn initialize(&self)
```

##### shutdown()

**Signature:**

```rust
pub fn shutdown(&self)
```

##### process_image()

**Signature:**

```rust
pub fn process_image(&self, image_bytes: Vec<u8>, config: OcrConfig) -> ExtractionResult
```

##### process_image_file()

**Signature:**

```rust
pub fn process_image_file(&self, path: PathBuf, config: OcrConfig) -> ExtractionResult
```

##### supports_language()

**Signature:**

```rust
pub fn supports_language(&self, lang: String) -> bool
```

##### backend_type()

**Signature:**

```rust
pub fn backend_type(&self) -> OcrBackendType
```

##### supported_languages()

**Signature:**

```rust
pub fn supported_languages(&self) -> Vec<String>
```

##### supports_table_detection()

**Signature:**

```rust
pub fn supports_table_detection(&self) -> bool
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
| `psm` | `i32` | `3` | Page Segmentation Mode (0-13). Common values: - 3: Fully automatic page segmentation (default) - 6: Assume a single uniform block of text - 11: Sparse text with no particular order |
| `output_format` | `String` | `"markdown"` | Output format ("text" or "markdown") |
| `oem` | `i32` | `3` | OCR Engine Mode (0-3). - 0: Legacy engine only - 1: Neural nets (LSTM) only (usually best) - 2: Legacy + LSTM - 3: Default (based on what's available) |
| `min_confidence` | `f64` | `0` | Minimum confidence threshold (0.0-100.0). Words with confidence below this threshold may be rejected or flagged. |
| `preprocessing` | `Option<ImagePreprocessingConfig>` | `Default::default()` | Image preprocessing configuration. Controls how images are preprocessed before OCR. Can significantly improve quality for scanned documents or low-quality images. |
| `enable_table_detection` | `bool` | `true` | Enable automatic table detection and reconstruction |
| `table_min_confidence` | `f64` | `0` | Minimum confidence threshold for table detection (0.0-1.0) |
| `table_column_threshold` | `i32` | `50` | Column threshold for table detection (pixels) |
| `table_row_threshold_ratio` | `f64` | `0.5` | Row threshold ratio for table detection (0.0-1.0) |
| `use_cache` | `bool` | `true` | Enable OCR result caching |
| `classify_use_pre_adapted_templates` | `bool` | `true` | Use pre-adapted templates for character classification |
| `language_model_ngram_on` | `bool` | `false` | Enable N-gram language model |
| `tessedit_dont_blkrej_good_wds` | `bool` | `true` | Don't reject good words during block-level processing |
| `tessedit_dont_rowrej_good_wds` | `bool` | `true` | Don't reject good words during row-level processing |
| `tessedit_enable_dict_correction` | `bool` | `true` | Enable dictionary correction |
| `tessedit_char_whitelist` | `String` | `""` | Whitelist of allowed characters (empty = all allowed) |
| `tessedit_char_blacklist` | `String` | `""` | Blacklist of forbidden characters (empty = none forbidden) |
| `tessedit_use_primary_params_model` | `bool` | `true` | Use primary language params model |
| `textord_space_size_is_variable` | `bool` | `true` | Variable-width space detection |
| `thresholding_method` | `bool` | `false` | Use adaptive thresholding method |

#### Methods

##### default()

**Signature:**

```rust
pub fn default() -> TesseractConfig
```


---

### TextAnnotation

Inline text annotation — byte-range based formatting and links.

Annotations reference byte offsets into the node's text content,
enabling precise identification of formatted regions.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `start` | `u32` | — | Start byte offset in the node's text content (inclusive). |
| `end` | `u32` | — | End byte offset in the node's text content (exclusive). |
| `kind` | `AnnotationKind` | — | Annotation type. |


---

### TextExtractionResult

Plain text and Markdown extraction result.

Contains the extracted text along with statistics and,
for Markdown files, structural elements like headers and links.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `content` | `String` | — | Extracted text content |
| `line_count` | `usize` | — | Number of lines |
| `word_count` | `usize` | — | Number of words |
| `character_count` | `usize` | — | Number of characters |
| `headers` | `Option<Vec<String>>` | `None` | Markdown headers (text only, Markdown files only) |
| `links` | `Option<Vec<StringString>>` | `None` | Markdown links as (text, URL) tuples (Markdown files only) |
| `code_blocks` | `Option<Vec<StringString>>` | `None` | Code blocks as (language, code) tuples (Markdown files only) |


---

### TextMetadata

Text/Markdown metadata.

Extracted from plain text and Markdown files. Includes word counts and,
for Markdown, structural elements like headers and links.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `line_count` | `usize` | — | Number of lines in the document |
| `word_count` | `usize` | — | Number of words |
| `character_count` | `usize` | — | Number of characters |
| `headers` | `Option<Vec<String>>` | `None` | Markdown headers (headings text only, for Markdown files) |
| `links` | `Option<Vec<StringString>>` | `None` | Markdown links as (text, url) tuples (for Markdown files) |
| `code_blocks` | `Option<Vec<StringString>>` | `None` | Code blocks as (language, code) tuples (for Markdown files) |


---

### Theme

Complete theme with color scheme and font scheme.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `name` | `String` | `Default::default()` | Theme name (e.g., "Office Theme"). |
| `color_scheme` | `Option<ColorScheme>` | `Default::default()` | Color scheme (12 standard colors). |
| `font_scheme` | `Option<FontScheme>` | `Default::default()` | Font scheme (major and minor fonts). |


---

### TokenReductionConfig

Token reduction configuration.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `mode` | `String` | — | Reduction mode: "off", "light", "moderate", "aggressive", "maximum" |
| `preserve_important_words` | `bool` | — | Preserve important words (capitalized, technical terms) |


---

### TracingLayer

A `tower.Layer` that wraps each extraction in a semantic tracing span.

#### Methods

##### layer()

**Signature:**

```rust
pub fn layer(&self, inner: S) -> Service
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
| `cache_dir` | `Option<PathBuf>` | `Default::default()` | Custom cache directory for downloaded grammars. When `None`, uses the default: `~/.cache/tree-sitter-language-pack/v{version}/libs/`. |
| `languages` | `Option<Vec<String>>` | `vec![]` | Languages to pre-download on init (e.g., `["python", "rust"]`). |
| `groups` | `Option<Vec<String>>` | `vec![]` | Language groups to pre-download (e.g., `["web", "systems", "scripting"]`). |
| `process` | `TreeSitterProcessConfig` | `Default::default()` | Processing options for code analysis. |

#### Methods

##### default()

**Signature:**

```rust
pub fn default() -> TreeSitterConfig
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
| `chunk_max_size` | `Option<usize>` | `Default::default()` | Maximum chunk size in bytes. `None` disables chunking. |
| `content_mode` | `CodeContentMode` | `CodeContentMode::Chunks` | Content rendering mode for code extraction. |

#### Methods

##### default()

**Signature:**

```rust
pub fn default() -> TreeSitterProcessConfig
```


---

### TsvRow

Tesseract TSV row data for conversion.

This struct represents a single row from Tesseract's TSV output format.
TSV format includes hierarchical information (block, paragraph, line, word)
along with bounding boxes and confidence scores.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `level` | `i32` | — | Hierarchical level (1=block, 2=para, 3=line, 4=word, 5=symbol) |
| `page_num` | `i32` | — | Page number (1-indexed) |
| `block_num` | `i32` | — | Block number within page |
| `par_num` | `i32` | — | Paragraph number within block |
| `line_num` | `i32` | — | Line number within paragraph |
| `word_num` | `i32` | — | Word number within line |
| `left` | `u32` | — | Left x-coordinate in pixels |
| `top` | `u32` | — | Top y-coordinate in pixels |
| `width` | `u32` | — | Width in pixels |
| `height` | `u32` | — | Height in pixels |
| `conf` | `f64` | — | Confidence score (0-100) |
| `text` | `String` | — | Recognized text |


---

### TypstExtractor

Typst document extractor

#### Methods

##### default()

**Signature:**

```rust
pub fn default() -> TypstExtractor
```

##### name()

**Signature:**

```rust
pub fn name(&self) -> String
```

##### version()

**Signature:**

```rust
pub fn version(&self) -> String
```

##### initialize()

**Signature:**

```rust
pub fn initialize(&self)
```

##### shutdown()

**Signature:**

```rust
pub fn shutdown(&self)
```

##### description()

**Signature:**

```rust
pub fn description(&self) -> String
```

##### author()

**Signature:**

```rust
pub fn author(&self) -> String
```

##### extract_bytes()

**Signature:**

```rust
pub fn extract_bytes(&self, content: Vec<u8>, mime_type: String, config: ExtractionConfig) -> InternalDocument
```

##### extract_file()

**Signature:**

```rust
pub fn extract_file(&self, path: PathBuf, mime_type: String, config: ExtractionConfig) -> InternalDocument
```

##### supported_mime_types()

**Signature:**

```rust
pub fn supported_mime_types(&self) -> Vec<String>
```

##### priority()

**Signature:**

```rust
pub fn priority(&self) -> i32
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
| `label` | `Option<String>` | `None` | Optional display text / label for the link. |
| `page` | `Option<u32>` | `None` | Optional page number where the URI was found (1-indexed). |
| `kind` | `UriKind` | — | Semantic classification of the URI. |

#### Methods

##### hyperlink()

Create a new hyperlink URI, auto-classifying `mailto:` as Email and `#` as Anchor.

**Signature:**

```rust
pub fn hyperlink(url: String, label: Option<String>) -> Uri
```

##### image()

Create a new image URI.

**Signature:**

```rust
pub fn image(url: String, label: Option<String>) -> Uri
```

##### citation()

Create a new citation URI (for DOIs, academic references).

**Signature:**

```rust
pub fn citation(url: String, label: Option<String>) -> Uri
```

##### anchor()

Create a new anchor/cross-reference URI.

**Signature:**

```rust
pub fn anchor(url: String, label: Option<String>) -> Uri
```

##### email()

Create a new email URI.

**Signature:**

```rust
pub fn email(url: String, label: Option<String>) -> Uri
```

##### reference()

Create a new reference URI.

**Signature:**

```rust
pub fn reference(url: String, label: Option<String>) -> Uri
```

##### with_page()

Set the page number.

**Signature:**

```rust
pub fn with_page(&self, page: u32) -> Uri
```


---

### VlmOcrBackend

VLM-based OCR backend using liter-llm vision models.

This backend sends images to a vision language model (e.g., GPT-4o, Claude)
for text extraction, as an alternative to traditional OCR backends.

#### Methods

##### name()

**Signature:**

```rust
pub fn name(&self) -> String
```

##### version()

**Signature:**

```rust
pub fn version(&self) -> String
```

##### initialize()

**Signature:**

```rust
pub fn initialize(&self)
```

##### shutdown()

**Signature:**

```rust
pub fn shutdown(&self)
```

##### process_image()

**Signature:**

```rust
pub fn process_image(&self, image_bytes: Vec<u8>, config: OcrConfig) -> ExtractionResult
```

##### supports_language()

**Signature:**

```rust
pub fn supports_language(&self, lang: String) -> bool
```

##### backend_type()

**Signature:**

```rust
pub fn backend_type(&self) -> OcrBackendType
```


---

### XlsxAppProperties

Application properties from docProps/app.xml for XLSX

Contains Excel-specific document metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `application` | `Option<String>` | `Default::default()` | Application name (e.g., "Microsoft Excel") |
| `app_version` | `Option<String>` | `Default::default()` | Application version |
| `doc_security` | `Option<i32>` | `Default::default()` | Document security level |
| `scale_crop` | `Option<bool>` | `Default::default()` | Scale crop flag |
| `links_up_to_date` | `Option<bool>` | `Default::default()` | Links up to date flag |
| `shared_doc` | `Option<bool>` | `Default::default()` | Shared document flag |
| `hyperlinks_changed` | `Option<bool>` | `Default::default()` | Hyperlinks changed flag |
| `company` | `Option<String>` | `Default::default()` | Company name |
| `worksheet_names` | `Vec<String>` | `vec![]` | Worksheet names |


---

### XmlExtractionResult

XML extraction result.

Contains extracted text content from XML files along with
structural statistics about the XML document.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `content` | `String` | — | Extracted text content (XML structure filtered out) |
| `element_count` | `usize` | — | Total number of XML elements processed |
| `unique_elements` | `Vec<String>` | — | List of unique element names found (sorted) |


---

### XmlExtractor

XML extractor.

Extracts text content from XML files, preserving element structure information.

#### Methods

##### default()

**Signature:**

```rust
pub fn default() -> XmlExtractor
```

##### name()

**Signature:**

```rust
pub fn name(&self) -> String
```

##### version()

**Signature:**

```rust
pub fn version(&self) -> String
```

##### initialize()

**Signature:**

```rust
pub fn initialize(&self)
```

##### shutdown()

**Signature:**

```rust
pub fn shutdown(&self)
```

##### description()

**Signature:**

```rust
pub fn description(&self) -> String
```

##### author()

**Signature:**

```rust
pub fn author(&self) -> String
```

##### extract_sync()

**Signature:**

```rust
pub fn extract_sync(&self, content: Vec<u8>, mime_type: String, config: ExtractionConfig) -> InternalDocument
```

##### extract_bytes()

**Signature:**

```rust
pub fn extract_bytes(&self, content: Vec<u8>, mime_type: String, config: ExtractionConfig) -> InternalDocument
```

##### supported_mime_types()

**Signature:**

```rust
pub fn supported_mime_types(&self) -> Vec<String>
```

##### priority()

**Signature:**

```rust
pub fn priority(&self) -> i32
```

##### as_sync_extractor()

**Signature:**

```rust
pub fn as_sync_extractor(&self) -> Option<SyncExtractor>
```


---

### XmlMetadata

XML metadata extracted during XML parsing.

Provides statistics about XML document structure.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `element_count` | `usize` | — | Total number of XML elements processed |
| `unique_elements` | `Vec<String>` | — | List of unique element tag names (sorted) |


---

### YakeParams

YAKE-specific parameters.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `window_size` | `usize` | `2` | Window size for co-occurrence analysis (default: 2). Controls the context window for computing co-occurrence statistics. |

#### Methods

##### default()

**Signature:**

```rust
pub fn default() -> YakeParams
```


---

### YearRange

Year range for bibliographic metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `min` | `Option<u32>` | `None` | Min |
| `max` | `Option<u32>` | `None` | Max |
| `years` | `Vec<u32>` | — | Years |


---

### YoloModel

YOLO-family layout detection model (YOLOv10, DocLayout-YOLO, YOLOX).

#### Methods

##### from_file()

Load a YOLO ONNX model from a file.

For square-input models (YOLOv10, DocLayout-YOLO), pass the same value for both dimensions.
For YOLOX (unstructuredio), use width=768, height=1024.

**Signature:**

```rust
pub fn from_file(path: String, variant: YoloVariant, input_width: u32, input_height: u32, model_name: String) -> YoloModel
```

##### detect()

**Signature:**

```rust
pub fn detect(&self, img: RgbImage) -> Vec<LayoutDetection>
```

##### detect_with_threshold()

**Signature:**

```rust
pub fn detect_with_threshold(&self, img: RgbImage, threshold: f32) -> Vec<LayoutDetection>
```

##### name()

**Signature:**

```rust
pub fn name(&self) -> String
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

```rust
pub fn default() -> ZipExtractor
```

##### name()

**Signature:**

```rust
pub fn name(&self) -> String
```

##### version()

**Signature:**

```rust
pub fn version(&self) -> String
```

##### initialize()

**Signature:**

```rust
pub fn initialize(&self)
```

##### shutdown()

**Signature:**

```rust
pub fn shutdown(&self)
```

##### description()

**Signature:**

```rust
pub fn description(&self) -> String
```

##### author()

**Signature:**

```rust
pub fn author(&self) -> String
```

##### extract_bytes()

**Signature:**

```rust
pub fn extract_bytes(&self, content: Vec<u8>, mime_type: String, config: ExtractionConfig) -> InternalDocument
```

##### supported_mime_types()

**Signature:**

```rust
pub fn supported_mime_types(&self) -> Vec<String>
```

##### priority()

**Signature:**

```rust
pub fn priority(&self) -> i32
```

##### as_sync_extractor()

**Signature:**

```rust
pub fn as_sync_extractor(&self) -> Option<SyncExtractor>
```

##### extract_sync()

**Signature:**

```rust
pub fn extract_sync(&self, content: Vec<u8>, mime_type: String, config: ExtractionConfig) -> InternalDocument
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

