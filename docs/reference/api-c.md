---
title: "C API Reference"
---

# C API Reference <span class="version-badge">v4.8.5</span>

## Functions

### kreuzberg_is_batch_mode()

Check if we're currently in batch processing mode.

Returns `false` if the task-local is not set (single-file mode).

**Signature:**

```c
bool kreuzberg_is_batch_mode();
```

**Returns:** `bool`


---

### kreuzberg_resolve_thread_budget()

Resolve the effective thread budget from config or auto-detection.

User-set `max_threads` takes priority. Otherwise auto-detects from `num_cpus`,
capped at 8 for sane defaults in serverless environments.

**Signature:**

```c
uintptr_t kreuzberg_resolve_thread_budget(KreuzbergConcurrencyConfig config);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `config` | `KreuzbergConcurrencyConfig*` | No | The configuration options |

**Returns:** `uintptr_t`


---

### kreuzberg_init_thread_pools()

Initialize the global Rayon thread pool with the given budget.

Safe to call multiple times — only the first call takes effect (subsequent
calls are silently ignored).

**Signature:**

```c
void kreuzberg_init_thread_pools(uintptr_t budget);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `budget` | `uintptr_t` | Yes | The budget |

**Returns:** `void`


---

### kreuzberg_merge_config_json()

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

```c
KreuzbergExtractionConfig* kreuzberg_merge_config_json(KreuzbergExtractionConfig base, void* override_json);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `base` | `KreuzbergExtractionConfig` | Yes | The extraction config |
| `override_json` | `void*` | Yes | The override json |

**Returns:** `KreuzbergExtractionConfig`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_build_config_from_json()

Build extraction config by optionally merging JSON overrides into a base config.

If `override_json` is `NULL`, returns a clone of `base`. Otherwise delegates
to `merge_config_json`.

**Signature:**

```c
KreuzbergExtractionConfig* kreuzberg_build_config_from_json(KreuzbergExtractionConfig base, void* override_json);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `base` | `KreuzbergExtractionConfig` | Yes | The extraction config |
| `override_json` | `void**` | No | The override json |

**Returns:** `KreuzbergExtractionConfig`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_is_valid_format_field()

Validates whether a field name is in the known formats registry.

This uses a pre-built hash set for O(1) lookups instead of linear search,
providing significant performance improvements for repeated validations.

**Returns:**

`true` if the field is in KNOWN_FORMATS, `false` otherwise.

**Signature:**

```c
bool kreuzberg_is_valid_format_field(const char* field);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `field` | `const char*` | Yes | The field name to validate |

**Returns:** `bool`


---

### kreuzberg_open_file_bytes()

Open a file and return its bytes with zero-copy for large files.

On non-WASM targets, files larger than `MMAP_THRESHOLD_BYTES` are
memory-mapped so that the file contents are never copied to the heap.
The mapping is read-only; the file must not be modified while the returned
`FileBytes` is alive, which is safe for document extraction.

On WASM or for small files, falls back to a plain `std.fs.read`.

**Errors:**

Returns `KreuzbergError.Io` for any I/O failure.

**Signature:**

```c
KreuzbergFileBytes* kreuzberg_open_file_bytes(const char* path);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `path` | `const char*` | Yes | Path to the file |

**Returns:** `KreuzbergFileBytes`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_read_file_async()

Read a file asynchronously.

**Returns:**

The file contents as bytes.

**Errors:**

Returns `KreuzbergError.Io` for I/O errors (these always bubble up).

**Signature:**

```c
const uint8_t* kreuzberg_read_file_async(KreuzbergPath path);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `path` | `KreuzbergPath` | Yes | Path to the file to read |

**Returns:** `const uint8_t*`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_read_file_sync()

Read a file synchronously.

**Returns:**

The file contents as bytes.

**Errors:**

Returns `KreuzbergError.Io` for I/O errors (these always bubble up).

**Signature:**

```c
const uint8_t* kreuzberg_read_file_sync(KreuzbergPath path);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `path` | `KreuzbergPath` | Yes | Path to the file to read |

**Returns:** `const uint8_t*`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_file_exists()

Check if a file exists.

**Returns:**

`true` if the file exists, `false` otherwise.

**Signature:**

```c
bool kreuzberg_file_exists(KreuzbergPath path);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `path` | `KreuzbergPath` | Yes | Path to check |

**Returns:** `bool`


---

### kreuzberg_validate_file_exists()

Validate that a file exists.

**Errors:**

Returns `KreuzbergError.Io` if file doesn't exist.

**Signature:**

```c
void kreuzberg_validate_file_exists(KreuzbergPath path);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `path` | `KreuzbergPath` | Yes | Path to validate |

**Returns:** `void`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_find_files_by_extension()

Get all files in a directory with a specific extension.

**Returns:**

Vector of file paths with the specified extension.

**Errors:**

Returns `KreuzbergError.Io` for I/O errors.

**Signature:**

```c
const char** kreuzberg_find_files_by_extension(KreuzbergPath dir, const char* extension, bool recursive);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `dir` | `KreuzbergPath` | Yes | Directory to search |
| `extension` | `const char*` | Yes | File extension to match (without the dot) |
| `recursive` | `bool` | Yes | Whether to recursively search subdirectories |

**Returns:** `const char**`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_detect_mime_type()

Detect MIME type from a file path.

Uses file extension to determine MIME type. Falls back to `mime_guess` crate
if extension-based detection fails.

**Returns:**

The detected MIME type string.

**Errors:**

Returns `KreuzbergError.Io` if file doesn't exist (when `check_exists` is true).
Returns `KreuzbergError.UnsupportedFormat` if MIME type cannot be determined.

**Signature:**

```c
const char* kreuzberg_detect_mime_type(KreuzbergPath path, bool check_exists);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `path` | `KreuzbergPath` | Yes | Path to the file |
| `check_exists` | `bool` | Yes | Whether to verify file existence |

**Returns:** `const char*`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_validate_mime_type()

Validate that a MIME type is supported.

**Returns:**

The validated MIME type (may be normalized).

**Errors:**

Returns `KreuzbergError.UnsupportedFormat` if not supported.

**Signature:**

```c
const char* kreuzberg_validate_mime_type(const char* mime_type);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `mime_type` | `const char*` | Yes | The MIME type to validate |

**Returns:** `const char*`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_detect_or_validate()

Detect or validate MIME type.

If `mime_type` is provided, validates it. Otherwise, detects from `path`.

**Returns:**

The validated MIME type string.

**Signature:**

```c
const char* kreuzberg_detect_or_validate(const char* path, const char* mime_type);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `path` | `const char**` | No | Optional path to detect MIME type from |
| `mime_type` | `const char**` | No | Optional explicit MIME type to validate |

**Returns:** `const char*`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_detect_mime_type_from_bytes()

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

```c
const char* kreuzberg_detect_mime_type_from_bytes(const uint8_t* content);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `content` | `const uint8_t*` | Yes | Raw file bytes |

**Returns:** `const char*`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_get_extensions_for_mime()

Get file extensions for a given MIME type.

Returns all known file extensions that map to the specified MIME type.

**Returns:**

A vector of file extensions (without leading dot) for the MIME type.

**Signature:**

```c
const char** kreuzberg_get_extensions_for_mime(const char* mime_type);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `mime_type` | `const char*` | Yes | The MIME type to look up |

**Returns:** `const char**`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_list_supported_formats()

List all supported document formats.

Returns a list of all file extensions and their corresponding MIME types
that Kreuzberg can process. Derived from the centralized `FORMATS` registry.

The list is sorted alphabetically by file extension.

**Signature:**

```c
KreuzbergSupportedFormat* kreuzberg_list_supported_formats();
```

**Returns:** `KreuzbergSupportedFormat*`


---

### kreuzberg_run_pipeline()

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

```c
KreuzbergExtractionResult* kreuzberg_run_pipeline(KreuzbergInternalDocument doc, KreuzbergExtractionConfig config);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `doc` | `KreuzbergInternalDocument` | Yes | The internal document produced by the extractor |
| `config` | `KreuzbergExtractionConfig` | Yes | Extraction configuration |

**Returns:** `KreuzbergExtractionResult`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_run_pipeline_sync()

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

```c
KreuzbergExtractionResult* kreuzberg_run_pipeline_sync(KreuzbergInternalDocument doc, KreuzbergExtractionConfig config);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `doc` | `KreuzbergInternalDocument` | Yes | The internal document produced by the extractor |
| `config` | `KreuzbergExtractionConfig` | Yes | Extraction configuration |

**Returns:** `KreuzbergExtractionResult`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_is_page_text_blank()

Determine if a page's text content indicates a blank page.

A page is blank if it has fewer than `MIN_NON_WHITESPACE_CHARS` non-whitespace characters.

**Returns:**

`true` if the page is considered blank, `false` otherwise

**Signature:**

```c
bool kreuzberg_is_page_text_blank(const char* text);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `text` | `const char*` | Yes | The extracted text content of the page |

**Returns:** `bool`


---

### kreuzberg_resolve_relationships()

Resolve `RelationshipTarget.Key` entries to `RelationshipTarget.Index`.

Builds an anchor index from elements with non-`NULL` anchors, then resolves
each key-based relationship target. Unresolvable keys are logged and skipped
(the relationship is left as `Key` — it will be excluded from the final
`DocumentStructure` relationships).

**Signature:**

```c
void kreuzberg_resolve_relationships(KreuzbergInternalDocument doc);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `doc` | `KreuzbergInternalDocument` | Yes | The internal document |

**Returns:** `void`


---

### kreuzberg_derive_document_structure()

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

```c
KreuzbergDocumentStructure* kreuzberg_derive_document_structure(KreuzbergInternalDocument doc);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `doc` | `KreuzbergInternalDocument` | Yes | The internal document |

**Returns:** `KreuzbergDocumentStructure`


---

### kreuzberg_derive_extraction_result()

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

```c
KreuzbergExtractionResult* kreuzberg_derive_extraction_result(KreuzbergInternalDocument doc, bool include_document_structure, KreuzbergOutputFormat output_format);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `doc` | `KreuzbergInternalDocument` | Yes | The internal document |
| `include_document_structure` | `bool` | Yes | The include document structure |
| `output_format` | `KreuzbergOutputFormat` | Yes | The output format |

**Returns:** `KreuzbergExtractionResult`


---

### kreuzberg_parse_json()

**Signature:**

```c
KreuzbergStructuredDataResult* kreuzberg_parse_json(const uint8_t* data, KreuzbergJsonExtractionConfig config);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `data` | `const uint8_t*` | Yes | The data |
| `config` | `KreuzbergJsonExtractionConfig*` | No | The configuration options |

**Returns:** `KreuzbergStructuredDataResult`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_parse_jsonl()

Parse JSONL (newline-delimited JSON) into a structured data result.

Each non-empty line is parsed as an independent JSON value. Blank lines
and whitespace-only lines are skipped. The output is a pretty-printed
JSON array of all parsed objects.

**Errors:**

Returns an error if any line contains invalid JSON (with 1-based line number)
or if the input is not valid UTF-8.

**Signature:**

```c
KreuzbergStructuredDataResult* kreuzberg_parse_jsonl(const uint8_t* data, KreuzbergJsonExtractionConfig config);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `data` | `const uint8_t*` | Yes | The data |
| `config` | `KreuzbergJsonExtractionConfig*` | No | The configuration options |

**Returns:** `KreuzbergStructuredDataResult`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_parse_yaml()

**Signature:**

```c
KreuzbergStructuredDataResult* kreuzberg_parse_yaml(const uint8_t* data);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `data` | `const uint8_t*` | Yes | The data |

**Returns:** `KreuzbergStructuredDataResult`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_parse_toml()

**Signature:**

```c
KreuzbergStructuredDataResult* kreuzberg_parse_toml(const uint8_t* data);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `data` | `const uint8_t*` | Yes | The data |

**Returns:** `KreuzbergStructuredDataResult`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_parse_text()

**Signature:**

```c
KreuzbergTextExtractionResult* kreuzberg_parse_text(const uint8_t* text_bytes, bool is_markdown);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `text_bytes` | `const uint8_t*` | Yes | The text bytes |
| `is_markdown` | `bool` | Yes | The is markdown |

**Returns:** `KreuzbergTextExtractionResult`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_transform_extraction_result_to_elements()

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

```c
KreuzbergElement* kreuzberg_transform_extraction_result_to_elements(KreuzbergExtractionResult result);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `result` | `KreuzbergExtractionResult` | Yes | Reference to the ExtractionResult to transform |

**Returns:** `KreuzbergElement*`


---

### kreuzberg_parse_body_text()

Parse a raw (possibly compressed) BodyText/SectionN stream.

Returns the list of sections found. Each section contains zero or more
paragraphs that carry the plain-text content.

**Signature:**

```c
KreuzbergSection* kreuzberg_parse_body_text(const uint8_t* data, bool is_compressed);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `data` | `const uint8_t*` | Yes | The data |
| `is_compressed` | `bool` | Yes | The is compressed |

**Returns:** `KreuzbergSection*`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_decompress_stream()

Decompress a raw-deflate stream from an HWP section.

HWP 5.0 compresses sections with raw deflate (no zlib header). Falls back
to zlib if raw deflate fails, and returns the data as-is if both fail.

**Signature:**

```c
const uint8_t* kreuzberg_decompress_stream(const uint8_t* data);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `data` | `const uint8_t*` | Yes | The data |

**Returns:** `const uint8_t*`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_extract_hwp_text()

Extract all plain text from an HWP 5.0 document given its raw bytes.

**Errors:**

Returns `HwpError` if the bytes do not form a valid HWP 5.0 compound file,
if the document is password-encrypted, or if a critical parsing step fails.

**Signature:**

```c
const char* kreuzberg_extract_hwp_text(const uint8_t* bytes);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `bytes` | `const uint8_t*` | Yes | The bytes |

**Returns:** `const char*`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_load_image_for_ocr()

Load image bytes for OCR, with JPEG 2000 and JBIG2 fallback support.

The standard `image` crate does not support JPEG 2000 or JBIG2 formats.
This function detects these formats by magic bytes and uses `hayro-jpeg2000`
/ `hayro-jbig2` for decoding, falling back to the standard `image` crate
for all other formats.

**Signature:**

```c
KreuzbergDynamicImage* kreuzberg_load_image_for_ocr(const uint8_t* image_bytes);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `image_bytes` | `const uint8_t*` | Yes | The image bytes |

**Returns:** `KreuzbergDynamicImage`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_extract_image_metadata()

Extract metadata from image bytes.

Extracts dimensions, format, and EXIF data from the image.
Attempts to decode using the standard image crate first, then falls back to
pure Rust JP2 box parsing for JPEG 2000 formats if the standard decoder fails.

**Signature:**

```c
KreuzbergImageMetadata* kreuzberg_extract_image_metadata(const uint8_t* bytes);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `bytes` | `const uint8_t*` | Yes | The bytes |

**Returns:** `KreuzbergImageMetadata`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_extract_text_from_image_with_ocr()

Extract text from image bytes using OCR with optional page tracking for multi-frame TIFFs.

This function:
- Detects if the image is a multi-frame TIFF
- For multi-frame TIFFs with PageConfig enabled, iterates frames and tracks boundaries
- For single-frame images or when page tracking is disabled, runs OCR on the whole image
- Returns (content, boundaries, page_contents) tuple

**Returns:**
ImageOcrResult with content and optional boundaries for pagination

**Signature:**

```c
KreuzbergImageOcrResult* kreuzberg_extract_text_from_image_with_ocr(const uint8_t* bytes, const char* mime_type, const char* ocr_result, KreuzbergPageConfig page_config);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `bytes` | `const uint8_t*` | Yes | Image file bytes |
| `mime_type` | `const char*` | Yes | MIME type (e.g., "image/tiff") |
| `ocr_result` | `const char*` | Yes | OCR backend result containing the text |
| `page_config` | `KreuzbergPageConfig*` | No | Optional page configuration for boundary tracking |

**Returns:** `KreuzbergImageOcrResult`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_estimate_content_capacity()

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

```c
uintptr_t kreuzberg_estimate_content_capacity(uint64_t file_size, const char* format);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `file_size` | `uint64_t` | Yes | The size of the original file in bytes |
| `format` | `const char*` | Yes | The file format/extension (e.g., "txt", "html", "docx", "xlsx", "pptx") |

**Returns:** `uintptr_t`


---

### kreuzberg_estimate_html_markdown_capacity()

Estimate capacity for HTML to Markdown conversion.

HTML documents typically convert to Markdown with 60-70% of the original size.
This function estimates capacity specifically for HTML→Markdown conversion.

**Returns:**

An estimated capacity for the Markdown output

**Signature:**

```c
uintptr_t kreuzberg_estimate_html_markdown_capacity(uint64_t html_size);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `html_size` | `uint64_t` | Yes | The size of the HTML file in bytes |

**Returns:** `uintptr_t`


---

### kreuzberg_estimate_spreadsheet_capacity()

Estimate capacity for cell extraction from spreadsheets.

When extracting cell data from Excel/ODS files, the extracted cells are typically
40% of the compressed file size (since the file is ZIP-compressed).

**Returns:**

An estimated capacity for cell value accumulation

**Signature:**

```c
uintptr_t kreuzberg_estimate_spreadsheet_capacity(uint64_t file_size);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `file_size` | `uint64_t` | Yes | Size of the spreadsheet file (XLSX, ODS, etc.) |

**Returns:** `uintptr_t`


---

### kreuzberg_estimate_presentation_capacity()

Estimate capacity for slide content extraction from presentations.

PPTX files when extracted have slide content at approximately 35% of the file size.
This accounts for XML overhead, compression, and embedded assets.

**Returns:**

An estimated capacity for slide content accumulation

**Signature:**

```c
uintptr_t kreuzberg_estimate_presentation_capacity(uint64_t file_size);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `file_size` | `uint64_t` | Yes | Size of the PPTX file in bytes |

**Returns:** `uintptr_t`


---

### kreuzberg_estimate_table_markdown_capacity()

Estimate capacity for markdown table generation.

Markdown tables have predictable size: ~12 bytes per cell on average
(accounting for separators, pipes, padding, and cell content).

**Returns:**

An estimated capacity for the markdown table output

**Signature:**

```c
uintptr_t kreuzberg_estimate_table_markdown_capacity(uintptr_t row_count, uintptr_t col_count);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `row_count` | `uintptr_t` | Yes | Number of rows in the table |
| `col_count` | `uintptr_t` | Yes | Number of columns in the table |

**Returns:** `uintptr_t`


---

### kreuzberg_parse_eml_content()

Parse .eml file content (RFC822 format)

**Signature:**

```c
KreuzbergEmailExtractionResult* kreuzberg_parse_eml_content(const uint8_t* data);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `data` | `const uint8_t*` | Yes | The data |

**Returns:** `KreuzbergEmailExtractionResult`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_parse_msg_content()

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

```c
KreuzbergEmailExtractionResult* kreuzberg_parse_msg_content(const uint8_t* data, uint32_t fallback_codepage);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `data` | `const uint8_t*` | Yes | The data |
| `fallback_codepage` | `uint32_t*` | No | The fallback codepage |

**Returns:** `KreuzbergEmailExtractionResult`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_extract_email_content()

Extract email content from either .eml or .msg format

**Signature:**

```c
KreuzbergEmailExtractionResult* kreuzberg_extract_email_content(const uint8_t* data, const char* mime_type, uint32_t fallback_codepage);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `data` | `const uint8_t*` | Yes | The data |
| `mime_type` | `const char*` | Yes | The mime type |
| `fallback_codepage` | `uint32_t*` | No | The fallback codepage |

**Returns:** `KreuzbergEmailExtractionResult`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_build_email_text_output()

Build text output from email extraction result

**Signature:**

```c
const char* kreuzberg_build_email_text_output(KreuzbergEmailExtractionResult result);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `result` | `KreuzbergEmailExtractionResult` | Yes | The email extraction result |

**Returns:** `const char*`


---

### kreuzberg_extract_pst_messages()

Extract all email messages from a PST file.

Opens the PST file and traverses the full folder hierarchy, extracting
every message including subject, sender, recipients, and body text.

**Returns:**

A vector of `EmailExtractionResult`, one per message found.

**Errors:**

Returns an error if the PST data cannot be written to a temporary file,
or if the PST format is invalid.

**Signature:**

```c
KreuzbergVecEmailExtractionResultVecProcessingWarning* kreuzberg_extract_pst_messages(const uint8_t* pst_data);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pst_data` | `const uint8_t*` | Yes | Raw bytes of the PST file |

**Returns:** `KreuzbergVecEmailExtractionResultVecProcessingWarning`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_read_excel_file()

**Signature:**

```c
KreuzbergExcelWorkbook* kreuzberg_read_excel_file(const char* file_path);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `file_path` | `const char*` | Yes | Path to the file |

**Returns:** `KreuzbergExcelWorkbook`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_read_excel_bytes()

**Signature:**

```c
KreuzbergExcelWorkbook* kreuzberg_read_excel_bytes(const uint8_t* data, const char* file_extension);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `data` | `const uint8_t*` | Yes | The data |
| `file_extension` | `const char*` | Yes | The file extension |

**Returns:** `KreuzbergExcelWorkbook`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_excel_to_text()

Convert an Excel workbook to plain text (space-separated cells, one row per line).

Each sheet is separated by a blank line. Sheet names are included as headers.
This produces text suitable for quality scoring against ground truth.

**Signature:**

```c
const char* kreuzberg_excel_to_text(KreuzbergExcelWorkbook workbook);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `workbook` | `KreuzbergExcelWorkbook` | Yes | The excel workbook |

**Returns:** `const char*`


---

### kreuzberg_excel_to_markdown()

**Signature:**

```c
const char* kreuzberg_excel_to_markdown(KreuzbergExcelWorkbook workbook);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `workbook` | `KreuzbergExcelWorkbook` | Yes | The excel workbook |

**Returns:** `const char*`


---

### kreuzberg_extract_doc_text()

Extract text from DOC bytes.

Parses the OLE/CFB compound document, reads the FIB (File Information Block),
and extracts text from the piece table.

**Signature:**

```c
KreuzbergDocExtractionResult* kreuzberg_extract_doc_text(const uint8_t* content);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `content` | `const uint8_t*` | Yes | The content to process |

**Returns:** `KreuzbergDocExtractionResult`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_parse_drawing()

Parse a drawing object starting after the `<w:drawing>` Start event.

This function reads events until it encounters the closing `</w:drawing>` tag,
parsing the drawing type (inline or anchored), extent, properties, and image references.

**Signature:**

```c
KreuzbergDrawing* kreuzberg_parse_drawing(KreuzbergReader reader);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `reader` | `KreuzbergReader` | Yes | The reader |

**Returns:** `KreuzbergDrawing`


---

### kreuzberg_collect_and_convert_omath_para()

Collect an `m:oMathPara` subtree and convert to LaTeX (display math).
The reader should be positioned right after the `<m:oMathPara>` start tag.

**Signature:**

```c
const char* kreuzberg_collect_and_convert_omath_para(KreuzbergReader reader);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `reader` | `KreuzbergReader` | Yes | The reader |

**Returns:** `const char*`


---

### kreuzberg_collect_and_convert_omath()

Collect an `m:oMath` subtree and convert to LaTeX (inline math).
The reader should be positioned right after the `<m:oMath>` start tag.

**Signature:**

```c
const char* kreuzberg_collect_and_convert_omath(KreuzbergReader reader);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `reader` | `KreuzbergReader` | Yes | The reader |

**Returns:** `const char*`


---

### kreuzberg_parse_document()

Parse a DOCX document from bytes and return the structured document.

**Signature:**

```c
KreuzbergDocument* kreuzberg_parse_document(const uint8_t* bytes);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `bytes` | `const uint8_t*` | Yes | The bytes |

**Returns:** `KreuzbergDocument`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_extract_text_from_bytes()

Extract text from DOCX bytes.

**Signature:**

```c
const char* kreuzberg_extract_text_from_bytes(const uint8_t* bytes);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `bytes` | `const uint8_t*` | Yes | The bytes |

**Returns:** `const char*`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_parse_section_properties()

Parse a `w:sectPr` XML element (roxmltree node) into `SectionProperties`.

**Signature:**

```c
KreuzbergSectionProperties* kreuzberg_parse_section_properties(KreuzbergNode node);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `node` | `KreuzbergNode` | Yes | The node |

**Returns:** `KreuzbergSectionProperties`


---

### kreuzberg_parse_section_properties_streaming()

Parse section properties from a quick_xml event stream.

Reads events from the reader until `</w:sectPr>` is encountered,
extracting the same properties as the roxmltree parser.

**Important:** This function advances the reader past the closing `</w:sectPr>` tag.
The caller must not attempt to process the `w:sectPr` end event again.

**Signature:**

```c
KreuzbergSectionProperties* kreuzberg_parse_section_properties_streaming(KreuzbergReader reader);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `reader` | `KreuzbergReader` | Yes | The reader |

**Returns:** `KreuzbergSectionProperties`


---

### kreuzberg_parse_styles_xml()

Parse `word/styles.xml` content into a `StyleCatalog`.

Uses `roxmltree` for tree-based XML parsing, consistent with the
office metadata parsing approach used elsewhere in the codebase.

**Signature:**

```c
KreuzbergStyleCatalog* kreuzberg_parse_styles_xml(const char* xml);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `xml` | `const char*` | Yes | The xml |

**Returns:** `KreuzbergStyleCatalog`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_parse_table_properties()

Parse table-level properties from streaming XML reader.

Expects the reader to be positioned just after the `<w:tblPr>` start tag.
Reads all child elements until the matching `</w:tblPr>` end tag.

**Signature:**

```c
KreuzbergTableProperties* kreuzberg_parse_table_properties(KreuzbergReader reader);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `reader` | `KreuzbergReader` | Yes | The reader |

**Returns:** `KreuzbergTableProperties`


---

### kreuzberg_parse_row_properties()

Parse row-level properties from streaming XML reader.

Expects the reader to be positioned just after the `<w:trPr>` start tag.

**Signature:**

```c
KreuzbergRowProperties* kreuzberg_parse_row_properties(KreuzbergReader reader);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `reader` | `KreuzbergReader` | Yes | The reader |

**Returns:** `KreuzbergRowProperties`


---

### kreuzberg_parse_cell_properties()

Parse cell-level properties from streaming XML reader.

Expects the reader to be positioned just after the `<w:tcPr>` start tag.

**Signature:**

```c
KreuzbergCellProperties* kreuzberg_parse_cell_properties(KreuzbergReader reader);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `reader` | `KreuzbergReader` | Yes | The reader |

**Returns:** `KreuzbergCellProperties`


---

### kreuzberg_parse_table_grid()

Parse table grid (column widths) from streaming XML reader.

Expects the reader to be positioned just after the `<w:tblGrid>` start tag.

**Signature:**

```c
KreuzbergTableGrid* kreuzberg_parse_table_grid(KreuzbergReader reader);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `reader` | `KreuzbergReader` | Yes | The reader |

**Returns:** `KreuzbergTableGrid`


---

### kreuzberg_parse_theme_xml()

Parse `word/theme/theme1.xml` content into a `Theme`.

Uses `roxmltree` for tree-based XML parsing of DrawingML theme elements.

**Returns:**
* `Ok(Theme)` - The parsed theme
* `Err(KreuzbergError)` - If parsing fails

**Signature:**

```c
KreuzbergTheme* kreuzberg_parse_theme_xml(const char* xml);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `xml` | `const char*` | Yes | The theme XML content as a string |

**Returns:** `KreuzbergTheme`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_extract_text()

Extract text from DOCX bytes.

**Signature:**

```c
const char* kreuzberg_extract_text(const uint8_t* bytes);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `bytes` | `const uint8_t*` | Yes | The bytes |

**Returns:** `const char*`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_extract_text_with_page_breaks()

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

```c
KreuzbergStringOptionVecPageBoundary* kreuzberg_extract_text_with_page_breaks(const uint8_t* bytes);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `bytes` | `const uint8_t*` | Yes | The DOCX file contents as bytes |

**Returns:** `KreuzbergStringOptionVecPageBoundary`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_detect_page_breaks_from_docx()

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

```c
KreuzbergPageBoundary** kreuzberg_detect_page_breaks_from_docx(const uint8_t* bytes);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `bytes` | `const uint8_t*` | Yes | The DOCX file contents (ZIP archive) |

**Returns:** `KreuzbergPageBoundary**`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_extract_ooxml_embedded_objects()

Extract embedded objects from an OOXML ZIP archive and recursively process them.

Scans the given `embeddings_prefix` directory (e.g. `word/embeddings/` or
`ppt/embeddings/`) inside the ZIP archive for embedded files. Known formats
(.xlsx, .pdf, .docx, .pptx, etc.) are recursively extracted. OLE compound
files (oleObject*.bin) are skipped with a warning unless their format can be
identified.

Returns `(children, warnings)` suitable for attaching to `InternalDocument`.

**Signature:**

```c
KreuzbergVecArchiveEntryVecProcessingWarning* kreuzberg_extract_ooxml_embedded_objects(const uint8_t* zip_bytes, const char* embeddings_prefix, const char* source_label, KreuzbergExtractionConfig config);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `zip_bytes` | `const uint8_t*` | Yes | The zip bytes |
| `embeddings_prefix` | `const char*` | Yes | The embeddings prefix |
| `source_label` | `const char*` | Yes | The source label |
| `config` | `KreuzbergExtractionConfig` | Yes | The configuration options |

**Returns:** `KreuzbergVecArchiveEntryVecProcessingWarning`


---

### kreuzberg_detect_image_format()

Detect image format from raw bytes using magic byte signatures.

Returns a format string like "jpeg", "png", etc. Used by both DOCX and PPTX extractors.

**Signature:**

```c
KreuzbergStr* kreuzberg_detect_image_format(const uint8_t* data);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `data` | `const uint8_t*` | Yes | The data |

**Returns:** `KreuzbergStr`


---

### kreuzberg_process_images_with_ocr()

Process extracted images with OCR if configured.

For each image, spawns a blocking OCR task and stores the result
in `image.ocr_result`. If OCR is not configured or fails for an
individual image, that image's `ocr_result` remains `NULL`.

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

```c
KreuzbergExtractedImage* kreuzberg_process_images_with_ocr(KreuzbergExtractedImage* images, KreuzbergExtractionConfig config);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `images` | `KreuzbergExtractedImage*` | Yes | The images |
| `config` | `KreuzbergExtractionConfig` | Yes | The configuration options |

**Returns:** `KreuzbergExtractedImage*`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_extract_ppt_text()

Extract text from PPT bytes.

Parses the OLE/CFB compound document, reads the "PowerPoint Document" stream,
and extracts text from TextCharsAtom and TextBytesAtom records.

When `include_master_slides` is `true`, master slide content (placeholder text
like "Click to edit Master title style") is included instead of being skipped.

**Signature:**

```c
KreuzbergPptExtractionResult* kreuzberg_extract_ppt_text(const uint8_t* content);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `content` | `const uint8_t*` | Yes | The content to process |

**Returns:** `KreuzbergPptExtractionResult`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_extract_ppt_text_with_options()

Extract text from PPT bytes with configurable master slide inclusion.

When `include_master_slides` is `true`, `RT_MAIN_MASTER` containers are not
skipped, so master slide placeholder text is included in the output.

**Signature:**

```c
KreuzbergPptExtractionResult* kreuzberg_extract_ppt_text_with_options(const uint8_t* content, bool include_master_slides);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `content` | `const uint8_t*` | Yes | The content to process |
| `include_master_slides` | `bool` | Yes | The include master slides |

**Returns:** `KreuzbergPptExtractionResult`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_extract_pptx_from_path()

Extract PPTX content from a file path.

**Returns:**

A `PptxExtractionResult` containing extracted content, metadata, and images.

**Signature:**

```c
KreuzbergPptxExtractionResult* kreuzberg_extract_pptx_from_path(const char* path, KreuzbergPptxExtractionOptions options);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `path` | `const char*` | Yes | Path to the PPTX file |
| `options` | `KreuzbergPptxExtractionOptions` | Yes | Extraction options controlling image extraction, formatting, etc. |

**Returns:** `KreuzbergPptxExtractionResult`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_extract_pptx_from_bytes()

Extract PPTX content from a byte buffer.

**Returns:**

A `PptxExtractionResult` containing extracted content, metadata, and images.

**Signature:**

```c
KreuzbergPptxExtractionResult* kreuzberg_extract_pptx_from_bytes(const uint8_t* data, KreuzbergPptxExtractionOptions options);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `data` | `const uint8_t*` | Yes | Raw PPTX file bytes |
| `options` | `KreuzbergPptxExtractionOptions` | Yes | Extraction options controlling image extraction, formatting, etc. |

**Returns:** `KreuzbergPptxExtractionResult`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_parse_xml_svg()

Parse XML with optional SVG mode.

In SVG mode, only text from SVG text-bearing elements (`<text>`, `<tspan>`,
`<title>`, `<desc>`, `<textPath>`) is extracted, without element name prefixes.
Attribute values are also omitted in SVG mode.

**Signature:**

```c
KreuzbergXmlExtractionResult* kreuzberg_parse_xml_svg(const uint8_t* xml_bytes, bool preserve_whitespace);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `xml_bytes` | `const uint8_t*` | Yes | The xml bytes |
| `preserve_whitespace` | `bool` | Yes | The preserve whitespace |

**Returns:** `KreuzbergXmlExtractionResult`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_parse_xml()

**Signature:**

```c
KreuzbergXmlExtractionResult* kreuzberg_parse_xml(const uint8_t* xml_bytes, bool preserve_whitespace);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `xml_bytes` | `const uint8_t*` | Yes | The xml bytes |
| `preserve_whitespace` | `bool` | Yes | The preserve whitespace |

**Returns:** `KreuzbergXmlExtractionResult`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_cells_to_text()

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

```c
const char* kreuzberg_cells_to_text(const char*** cells);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `cells` | `const char***` | Yes | A slice of vectors representing table rows, where each inner vector contains cell values |

**Returns:** `const char*`


---

### kreuzberg_cells_to_markdown()

**Signature:**

```c
const char* kreuzberg_cells_to_markdown(const char*** cells);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `cells` | `const char***` | Yes | The cells |

**Returns:** `const char*`


---

### kreuzberg_parse_jotdown_attributes()

Parse jotdown attributes into our Attributes representation.

Converts jotdown's internal attribute representation to Kreuzberg's
standardized Attributes struct, handling IDs, classes, and key-value pairs.

**Signature:**

```c
KreuzbergAttributes* kreuzberg_parse_jotdown_attributes(KreuzbergAttributes attrs);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `attrs` | `KreuzbergAttributes` | Yes | The attributes |

**Returns:** `KreuzbergAttributes`


---

### kreuzberg_render_attributes()

Render attributes to djot attribute syntax.

Converts Kreuzberg's Attributes struct back to djot attribute syntax:
{.class #id key="value"}

**Signature:**

```c
const char* kreuzberg_render_attributes(KreuzbergAttributes attrs);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `attrs` | `KreuzbergAttributes` | Yes | The attributes |

**Returns:** `const char*`


---

### kreuzberg_djot_content_to_djot()

Convert DjotContent back to djot markup.

This function takes a `DjotContent` structure and generates valid djot markup
from it, preserving:
- Block structure (headings, code blocks, lists, blockquotes, etc.)
- Inline formatting (strong, emphasis, highlight, subscript, superscript, etc.)
- Attributes where present ({.class #id key="value"})

**Returns:**

A String containing valid djot markup

**Signature:**

```c
const char* kreuzberg_djot_content_to_djot(KreuzbergDjotContent content);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `content` | `KreuzbergDjotContent` | Yes | The DjotContent to convert |

**Returns:** `const char*`


---

### kreuzberg_extraction_result_to_djot()

Convert any ExtractionResult to djot format.

This function converts an `ExtractionResult` to djot markup:
- If `djot_content` is `Some`, uses `djot_content_to_djot` for full fidelity conversion
- Otherwise, wraps the plain text content in paragraphs

**Returns:**

A `Result` containing the djot markup string

**Signature:**

```c
const char* kreuzberg_extraction_result_to_djot(KreuzbergExtractionResult result);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `result` | `KreuzbergExtractionResult` | Yes | The ExtractionResult to convert |

**Returns:** `const char*`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_djot_to_html()

Render djot content to HTML.

This function takes djot source text and renders it to HTML using jotdown's
built-in HTML renderer.

**Returns:**

A `Result` containing the rendered HTML string

**Signature:**

```c
const char* kreuzberg_djot_to_html(const char* djot_source);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `djot_source` | `const char*` | Yes | The djot markup text to render |

**Returns:** `const char*`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_render_block_to_djot()

Render a single block to djot markup.

**Signature:**

```c
void kreuzberg_render_block_to_djot(const char* output, KreuzbergFormattedBlock block, uintptr_t indent_level);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `output` | `const char*` | Yes | The output destination |
| `block` | `KreuzbergFormattedBlock` | Yes | The formatted block |
| `indent_level` | `uintptr_t` | Yes | The indent level |

**Returns:** `void`


---

### kreuzberg_render_list_item()

Render a list item with the given marker.

**Signature:**

```c
void kreuzberg_render_list_item(const char* output, KreuzbergFormattedBlock item, const char* indent, const char* marker);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `output` | `const char*` | Yes | The output destination |
| `item` | `KreuzbergFormattedBlock` | Yes | The formatted block |
| `indent` | `const char*` | Yes | The indent |
| `marker` | `const char*` | Yes | The marker |

**Returns:** `void`


---

### kreuzberg_render_inline_content()

Render inline content to djot markup.

**Signature:**

```c
void kreuzberg_render_inline_content(const char* output, KreuzbergInlineElement* elements);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `output` | `const char*` | Yes | The output destination |
| `elements` | `KreuzbergInlineElement*` | Yes | The elements |

**Returns:** `void`


---

### kreuzberg_extract_frontmatter()

Extract YAML frontmatter from document content.

Frontmatter is expected to be delimited by `---` or `...` at the start of the document.
This implementation properly handles edge cases:
- `---` appearing within YAML strings or arrays
- Both `---` and `...` as end delimiters (YAML spec compliant)
- Multiline YAML values containing dashes

Returns a tuple of (parsed YAML value, remaining content after frontmatter).

**Signature:**

```c
KreuzbergOptionYamlValueString* kreuzberg_extract_frontmatter(const char* content);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `content` | `const char*` | Yes | The content to process |

**Returns:** `KreuzbergOptionYamlValueString`


---

### kreuzberg_extract_metadata_from_yaml()

Extract metadata from YAML frontmatter.

Extracts the following YAML fields into Kreuzberg metadata:
- **Standard fields**: title, author, date, description (as subject)
- **Extended fields**: abstract, subject, category, tags, language, version
- **Array fields** (keywords, tags): stored as `Vec<String>` in typed fields

**Returns:**

A `Metadata` struct populated with extracted fields

**Signature:**

```c
KreuzbergMetadata* kreuzberg_extract_metadata_from_yaml(KreuzbergYamlValue yaml);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `yaml` | `KreuzbergYamlValue` | Yes | The parsed YAML value from frontmatter |

**Returns:** `KreuzbergMetadata`


---

### kreuzberg_extract_title_from_content()

Extract first heading as title from content.

Searches for the first level-1 heading (# Title) in the content
and returns it as a potential title if no title was found in frontmatter.

**Returns:**

Some(title) if a heading is found, None otherwise

**Signature:**

```c
const char** kreuzberg_extract_title_from_content(const char* content);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `content` | `const char*` | Yes | The document content to search |

**Returns:** `const char**`


---

### kreuzberg_collect_iwa_paths()

Collects all .iwa file paths from a ZIP archive.

Opens the ZIP from `content`, iterates every entry, and returns the names of
all entries whose path ends with `.iwa`. Entries that cannot be read are
silently skipped (consistent with the per-extractor `filter_map` pattern).

**Signature:**

```c
const char** kreuzberg_collect_iwa_paths(const uint8_t* content);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `content` | `const uint8_t*` | Yes | The content to process |

**Returns:** `const char**`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_read_iwa_file()

Read and Snappy-decompress a single `.iwa` file from the ZIP archive.

Apple IWA files use a custom framing format:
Each block in the file is: `[type: u8][length: u24 LE][payload: length bytes]`
- type `0x00`: Snappy-compressed block → decompress payload with raw Snappy
- type `0x01`: Uncompressed block → use payload as-is

Multiple blocks are concatenated to form the decompressed IWA stream.

**Signature:**

```c
const uint8_t* kreuzberg_read_iwa_file(const uint8_t* content, const char* path);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `content` | `const uint8_t*` | Yes | The content to process |
| `path` | `const char*` | Yes | Path to the file |

**Returns:** `const uint8_t*`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_decode_iwa_stream()

Decode an Apple IWA byte stream into the raw protobuf payload.

IWA framing: each block = 1 byte type + 3 bytes LE length + N bytes payload
- type 0x00 → Snappy-compressed, decompress with `snap.raw.Decoder`
- type 0x01 → Uncompressed, use as-is

**Signature:**

```c
const uint8_t* kreuzberg_decode_iwa_stream(const uint8_t* data);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `data` | `const uint8_t*` | Yes | The data |

**Returns:** `const uint8_t*`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_extract_text_from_proto()

Extract all UTF-8 text strings from a raw protobuf byte slice.

This uses a simple wire-format scanner without a full schema:
- Field type 2 (length-delimited) with a valid UTF-8 payload of ≥3 bytes is
  treated as a text string candidate.
- We skip binary blobs (non-UTF-8) and very short noise strings.

This approach avoids the need for `prost-build` and generated proto code while
still extracting human-readable text reliably from iWork documents.

**Signature:**

```c
const char** kreuzberg_extract_text_from_proto(const uint8_t* data);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `data` | `const uint8_t*` | Yes | The data |

**Returns:** `const char**`


---

### kreuzberg_extract_text_from_iwa_files()

Extract all text from an iWork ZIP archive by reading specified IWA entries.

`iwa_paths` should list the IWA file paths to read (e.g. `["Index/Document.iwa"]`).
Returns a flat joined string of all text found across all IWA files.

**Signature:**

```c
const char* kreuzberg_extract_text_from_iwa_files(const uint8_t* content, const char** iwa_paths);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `content` | `const uint8_t*` | Yes | The content to process |
| `iwa_paths` | `const char**` | Yes | The iwa paths |

**Returns:** `const char*`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_extract_metadata_from_zip()

Extract metadata from an iWork ZIP archive.

Attempts to read `Metadata/Properties.plist` and
`Metadata/BuildVersionHistory.plist` from the ZIP. These files are XML plists
containing authorship and creation information. If the files cannot be read
or parsed, an empty `Metadata` is returned.

**Signature:**

```c
KreuzbergMetadata* kreuzberg_extract_metadata_from_zip(const uint8_t* content);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `content` | `const uint8_t*` | Yes | The content to process |

**Returns:** `KreuzbergMetadata`


---

### kreuzberg_dedup_text()

Deduplicate a list of text strings while preserving order.
Adjacent duplicates and near-duplicates are removed.

**Signature:**

```c
const char** kreuzberg_dedup_text(const char** texts);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `texts` | `const char**` | Yes | The texts |

**Returns:** `const char**`


---

### kreuzberg_ensure_initialized()

Ensure built-in extractors are registered.

This function is called automatically on first extraction operation.
It's safe to call multiple times - registration only happens once,
unless the registry was cleared, in which case extractors are re-registered.

**Signature:**

```c
void kreuzberg_ensure_initialized();
```

**Returns:** `void`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_register_default_extractors()

Register all built-in extractors with the global registry.

This function should be called once at application startup to register
the default extractors (PlainText, Markdown, XML, etc.).

**Note:** This is called automatically on first extraction operation.
Explicit calling is optional.

**Signature:**

```c
void kreuzberg_register_default_extractors();
```

**Returns:** `void`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_extract_panic_message()

Extracts a human-readable message from a panic payload.

Attempts to downcast the panic payload to common types (String, &str)
to extract a meaningful error message.

Message is truncated to 4KB to prevent DoS attacks via extremely large panic messages.

**Returns:**

A string representation of the panic message (truncated if necessary)

**Signature:**

```c
const char* kreuzberg_extract_panic_message(KreuzbergAny panic_info);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `panic_info` | `KreuzbergAny` | Yes | The panic payload from catch_unwind |

**Returns:** `const char*`


---

### kreuzberg_get_ocr_backend_registry()

Get the global OCR backend registry.

**Signature:**

```c
KreuzbergRwLock* kreuzberg_get_ocr_backend_registry();
```

**Returns:** `KreuzbergRwLock`


---

### kreuzberg_get_document_extractor_registry()

Get the global document extractor registry.

**Signature:**

```c
KreuzbergRwLock* kreuzberg_get_document_extractor_registry();
```

**Returns:** `KreuzbergRwLock`


---

### kreuzberg_get_post_processor_registry()

Get the global post-processor registry.

**Signature:**

```c
KreuzbergRwLock* kreuzberg_get_post_processor_registry();
```

**Returns:** `KreuzbergRwLock`


---

### kreuzberg_get_validator_registry()

Get the global validator registry.

**Signature:**

```c
KreuzbergRwLock* kreuzberg_get_validator_registry();
```

**Returns:** `KreuzbergRwLock`


---

### kreuzberg_get_renderer_registry()

Get the global renderer registry.

**Signature:**

```c
KreuzbergRwLock* kreuzberg_get_renderer_registry();
```

**Returns:** `KreuzbergRwLock`


---

### kreuzberg_validate_plugins_at_startup()

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

```c
KreuzbergPluginHealthStatus* kreuzberg_validate_plugins_at_startup();
```

**Returns:** `KreuzbergPluginHealthStatus`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_sanitize_filename()

Sanitize a file path to return only the filename (no directory).

Prevents PII from appearing in traces.

**Signature:**

```c
const char* kreuzberg_sanitize_filename(const char* path);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `path` | `const char*` | Yes | Path to the file |

**Returns:** `const char*`


---

### kreuzberg_get_metrics()

Get the global extraction metrics, initialising on first call.

Uses the global `opentelemetry.global.meter` to create instruments.

**Signature:**

```c
KreuzbergExtractionMetrics* kreuzberg_get_metrics();
```

**Returns:** `KreuzbergExtractionMetrics`


---

### kreuzberg_record_error_on_current_span()

Record an error on the current span using semantic conventions.

Sets `otel.status_code = "ERROR"`, `kreuzberg.error.type`, and `error.message`.

**Signature:**

```c
void kreuzberg_record_error_on_current_span(KreuzbergKreuzbergError error);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `error` | `KreuzbergKreuzbergError` | Yes | The kreuzberg error |

**Returns:** `void`


---

### kreuzberg_record_success_on_current_span()

Record extraction success on the current span.

**Signature:**

```c
void kreuzberg_record_success_on_current_span();
```

**Returns:** `void`


---

### kreuzberg_sanitize_path()

Sanitize a file path to return only the filename.

Prevents PII (personally identifiable information) from appearing in
traces by only recording filenames instead of full paths.

**Signature:**

```c
const char* kreuzberg_sanitize_path(const char* path);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `path` | `const char*` | Yes | Path to the file |

**Returns:** `const char*`


---

### kreuzberg_extractor_span()

Create an extractor-level span with semantic convention fields.

Returns a `tracing.Span` with all `kreuzberg.extractor.*` and
`kreuzberg.document.*` fields pre-allocated (set to `Empty` for
lazy recording).

**Signature:**

```c
KreuzbergSpan* kreuzberg_extractor_span(const char* extractor_name, const char* mime_type, uintptr_t size_bytes);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `extractor_name` | `const char*` | Yes | The extractor name |
| `mime_type` | `const char*` | Yes | The mime type |
| `size_bytes` | `uintptr_t` | Yes | The size bytes |

**Returns:** `KreuzbergSpan`


---

### kreuzberg_pipeline_stage_span()

Create a pipeline stage span.

**Signature:**

```c
KreuzbergSpan* kreuzberg_pipeline_stage_span(const char* stage);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `stage` | `const char*` | Yes | The stage |

**Returns:** `KreuzbergSpan`


---

### kreuzberg_pipeline_processor_span()

Create a pipeline processor span.

**Signature:**

```c
KreuzbergSpan* kreuzberg_pipeline_processor_span(const char* stage, const char* processor_name);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `stage` | `const char*` | Yes | The stage |
| `processor_name` | `const char*` | Yes | The processor name |

**Returns:** `KreuzbergSpan`


---

### kreuzberg_ocr_span()

Create an OCR operation span.

**Signature:**

```c
KreuzbergSpan* kreuzberg_ocr_span(const char* backend, const char* language);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `backend` | `const char*` | Yes | The backend |
| `language` | `const char*` | Yes | The language |

**Returns:** `KreuzbergSpan`


---

### kreuzberg_model_inference_span()

Create a model inference span.

**Signature:**

```c
KreuzbergSpan* kreuzberg_model_inference_span(const char* model_name);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `model_name` | `const char*` | Yes | The model name |

**Returns:** `KreuzbergSpan`


---

### kreuzberg_from_utf8()

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

```c
const char* kreuzberg_from_utf8(const uint8_t* bytes);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `bytes` | `const uint8_t*` | Yes | The byte slice to validate and convert |

**Returns:** `const char*`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_string_from_utf8()

Validates and converts owned bytes to String using SIMD when available.

This function converts bytes to an owned String, validating UTF-8 using SIMD
when available. The caller's bytes are consumed to create the String.

**Returns:**

`Ok(String)` if the bytes are valid UTF-8, `Err(std.string.FromUtf8Error)` otherwise.

# Performance

When enabled, SIMD validation significantly reduces the time spent on validation,
especially for large text documents.

**Signature:**

```c
const char* kreuzberg_string_from_utf8(const uint8_t* bytes);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `bytes` | `const uint8_t*` | Yes | The byte vector to validate and convert |

**Returns:** `const char*`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_is_valid_utf8()

Validates bytes as UTF-8 without conversion to string slice.

Returns `true` if the bytes represent valid UTF-8, `false` otherwise.
This is useful when you only need to check validity without constructing a string.

**Returns:**

`true` if valid UTF-8, `false` otherwise.

# Performance

This function is optimized for early exit on invalid sequences.

**Signature:**

```c
bool kreuzberg_is_valid_utf8(const uint8_t* bytes);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `bytes` | `const uint8_t*` | Yes | The byte slice to validate |

**Returns:** `bool`


---

### kreuzberg_calculate_quality_score()

**Signature:**

```c
double kreuzberg_calculate_quality_score(const char* text, KreuzbergAHashMap metadata);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `text` | `const char*` | Yes | The text |
| `metadata` | `KreuzbergAHashMap*` | No | The a hash map |

**Returns:** `double`


---

### kreuzberg_clean_extracted_text()

**Signature:**

```c
const char* kreuzberg_clean_extracted_text(const char* text);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `text` | `const char*` | Yes | The text |

**Returns:** `const char*`


---

### kreuzberg_normalize_spaces()

**Signature:**

```c
const char* kreuzberg_normalize_spaces(const char* text);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `text` | `const char*` | Yes | The text |

**Returns:** `const char*`


---

### kreuzberg_reduce_tokens()

Reduces token count in text while preserving meaning and structure.

This function removes stopwords, redundancy, and applies compression techniques
based on the specified reduction level. Supports 64 languages with automatic
stopword removal and optional semantic clustering.

**Returns:**

Returns the reduced text with preserved structure (markdown, code blocks).

**Errors:**

Returns an error if the language hint is invalid or stopwords cannot be loaded.

**Signature:**

```c
const char* kreuzberg_reduce_tokens(const char* text, KreuzbergTokenReductionConfig config, const char* language_hint);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `text` | `const char*` | Yes | The input text to reduce |
| `config` | `KreuzbergTokenReductionConfig` | Yes | Configuration specifying reduction level and options |
| `language_hint` | `const char**` | No | Optional ISO 639-3 language code (e.g., "eng", "spa") |

**Returns:** `const char*`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_batch_reduce_tokens()

Reduces token count for multiple texts efficiently using parallel processing.

This function processes multiple texts in parallel using Rayon, providing
significant performance improvements for batch operations. All texts use the
same configuration and language hint for consistency.

**Returns:**

Returns a vector of reduced texts in the same order as the input.

**Errors:**

Returns an error if the language hint is invalid or stopwords cannot be loaded.

**Signature:**

```c
const char** kreuzberg_batch_reduce_tokens(const char** texts, KreuzbergTokenReductionConfig config, const char* language_hint);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `texts` | `const char**` | Yes | Slice of text references to reduce |
| `config` | `KreuzbergTokenReductionConfig` | Yes | Configuration specifying reduction level and options |
| `language_hint` | `const char**` | No | Optional ISO 639-3 language code (e.g., "eng", "spa") |

**Returns:** `const char**`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_get_reduction_statistics()

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

```c
KreuzbergF64F64UsizeUsizeUsizeUsize* kreuzberg_get_reduction_statistics(const char* original, const char* reduced);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `original` | `const char*` | Yes | The original text before reduction |
| `reduced` | `const char*` | Yes | The reduced text after applying token reduction |

**Returns:** `KreuzbergF64F64UsizeUsizeUsizeUsize`


---

### kreuzberg_bold()

Create a bold annotation for the given byte range.

**Signature:**

```c
KreuzbergTextAnnotation* kreuzberg_bold(uint32_t start, uint32_t end);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `start` | `uint32_t` | Yes | The start |
| `end` | `uint32_t` | Yes | The end |

**Returns:** `KreuzbergTextAnnotation`


---

### kreuzberg_italic()

Create an italic annotation for the given byte range.

**Signature:**

```c
KreuzbergTextAnnotation* kreuzberg_italic(uint32_t start, uint32_t end);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `start` | `uint32_t` | Yes | The start |
| `end` | `uint32_t` | Yes | The end |

**Returns:** `KreuzbergTextAnnotation`


---

### kreuzberg_underline()

Create an underline annotation for the given byte range.

**Signature:**

```c
KreuzbergTextAnnotation* kreuzberg_underline(uint32_t start, uint32_t end);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `start` | `uint32_t` | Yes | The start |
| `end` | `uint32_t` | Yes | The end |

**Returns:** `KreuzbergTextAnnotation`


---

### kreuzberg_link()

Create a link annotation for the given byte range.

**Signature:**

```c
KreuzbergTextAnnotation* kreuzberg_link(uint32_t start, uint32_t end, const char* url, const char* title);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `start` | `uint32_t` | Yes | The start |
| `end` | `uint32_t` | Yes | The end |
| `url` | `const char*` | Yes | The URL to fetch |
| `title` | `const char**` | No | The title |

**Returns:** `KreuzbergTextAnnotation`


---

### kreuzberg_code()

Create a code (inline) annotation for the given byte range.

**Signature:**

```c
KreuzbergTextAnnotation* kreuzberg_code(uint32_t start, uint32_t end);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `start` | `uint32_t` | Yes | The start |
| `end` | `uint32_t` | Yes | The end |

**Returns:** `KreuzbergTextAnnotation`


---

### kreuzberg_strikethrough()

Create a strikethrough annotation for the given byte range.

**Signature:**

```c
KreuzbergTextAnnotation* kreuzberg_strikethrough(uint32_t start, uint32_t end);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `start` | `uint32_t` | Yes | The start |
| `end` | `uint32_t` | Yes | The end |

**Returns:** `KreuzbergTextAnnotation`


---

### kreuzberg_subscript()

Create a subscript annotation for the given byte range.

**Signature:**

```c
KreuzbergTextAnnotation* kreuzberg_subscript(uint32_t start, uint32_t end);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `start` | `uint32_t` | Yes | The start |
| `end` | `uint32_t` | Yes | The end |

**Returns:** `KreuzbergTextAnnotation`


---

### kreuzberg_superscript()

Create a superscript annotation for the given byte range.

**Signature:**

```c
KreuzbergTextAnnotation* kreuzberg_superscript(uint32_t start, uint32_t end);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `start` | `uint32_t` | Yes | The start |
| `end` | `uint32_t` | Yes | The end |

**Returns:** `KreuzbergTextAnnotation`


---

### kreuzberg_font_size()

Create a font size annotation for the given byte range.

**Signature:**

```c
KreuzbergTextAnnotation* kreuzberg_font_size(uint32_t start, uint32_t end, const char* value);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `start` | `uint32_t` | Yes | The start |
| `end` | `uint32_t` | Yes | The end |
| `value` | `const char*` | Yes | The value |

**Returns:** `KreuzbergTextAnnotation`


---

### kreuzberg_color()

Create a color annotation for the given byte range.

**Signature:**

```c
KreuzbergTextAnnotation* kreuzberg_color(uint32_t start, uint32_t end, const char* value);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `start` | `uint32_t` | Yes | The start |
| `end` | `uint32_t` | Yes | The end |
| `value` | `const char*` | Yes | The value |

**Returns:** `KreuzbergTextAnnotation`


---

### kreuzberg_highlight()

Create a highlight annotation for the given byte range.

**Signature:**

```c
KreuzbergTextAnnotation* kreuzberg_highlight(uint32_t start, uint32_t end);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `start` | `uint32_t` | Yes | The start |
| `end` | `uint32_t` | Yes | The end |

**Returns:** `KreuzbergTextAnnotation`


---

### kreuzberg_classify_uri()

Classify a URL string into the appropriate `UriKind`.

- `mailto:` → `Email`
- `#` prefix → `Anchor`
- everything else → `Hyperlink`

**Signature:**

```c
KreuzbergUriKind* kreuzberg_classify_uri(const char* url);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `url` | `const char*` | Yes | The URL to fetch |

**Returns:** `KreuzbergUriKind`


---

### kreuzberg_safe_decode()

Decode raw bytes into UTF-8, using heuristics and fallback encodings when necessary.

The function prefers an explicit `encoding`, falls back to the cached guess, probes
an encoding detector, and finally tries a small curated list before returning a
mojibake-cleaned string.

**Signature:**

```c
const char* kreuzberg_safe_decode(const uint8_t* byte_data, const char* encoding);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `byte_data` | `const uint8_t*` | Yes | The byte data |
| `encoding` | `const char**` | No | The encoding |

**Returns:** `const char*`


---

### kreuzberg_calculate_text_confidence()

Estimate how trustworthy a decoded string is on a 0.0–1.0 scale.

Scores close to 1.0 indicate mostly printable characters, whereas lower scores
point to mojibake, control characters, or suspicious character mixes.

**Signature:**

```c
double kreuzberg_calculate_text_confidence(const char* text);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `text` | `const char*` | Yes | The text |

**Returns:** `double`


---

### kreuzberg_fix_mojibake()

Strip control characters and replacement glyphs that typically arise from mojibake.

**Signature:**

```c
KreuzbergStr* kreuzberg_fix_mojibake(const char* text);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `text` | `const char*` | Yes | The text |

**Returns:** `KreuzbergStr`


---

### kreuzberg_snake_to_camel()

Recursively convert snake_case keys in a JSON Value to camelCase.

This is used by language bindings (Node.js, Go, Java, C#, etc.) to provide
a consistent camelCase API for consumers even though the Rust core uses snake_case.

**Signature:**

```c
KreuzbergValue* kreuzberg_snake_to_camel(KreuzbergValue val);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `val` | `KreuzbergValue` | Yes | The value |

**Returns:** `KreuzbergValue`


---

### kreuzberg_camel_to_snake()

Recursively convert camelCase keys in a JSON Value to snake_case.

This is the inverse of `snake_to_camel`. Used by WASM bindings to accept
camelCase config from JavaScript while the Rust core expects snake_case.

**Signature:**

```c
KreuzbergValue* kreuzberg_camel_to_snake(KreuzbergValue val);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `val` | `KreuzbergValue` | Yes | The value |

**Returns:** `KreuzbergValue`


---

### kreuzberg_create_string_buffer_pool()

Create a pre-configured string buffer pool for batch processing.

**Returns:**

A pool configured for text accumulation with reasonable defaults.

**Signature:**

```c
KreuzbergStringBufferPool* kreuzberg_create_string_buffer_pool(uintptr_t pool_size, uintptr_t buffer_capacity);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pool_size` | `uintptr_t` | Yes | Maximum number of buffers to keep in the pool |
| `buffer_capacity` | `uintptr_t` | Yes | Initial capacity for each buffer in bytes |

**Returns:** `KreuzbergStringBufferPool`


---

### kreuzberg_create_byte_buffer_pool()

Create a pre-configured byte buffer pool for batch processing.

**Returns:**

A pool configured for binary data handling with reasonable defaults.

**Signature:**

```c
KreuzbergByteBufferPool* kreuzberg_create_byte_buffer_pool(uintptr_t pool_size, uintptr_t buffer_capacity);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pool_size` | `uintptr_t` | Yes | Maximum number of buffers to keep in the pool |
| `buffer_capacity` | `uintptr_t` | Yes | Initial capacity for each buffer in bytes |

**Returns:** `KreuzbergByteBufferPool`


---

### kreuzberg_estimate_pool_size()

Estimate optimal pool sizing based on file size and document type.

This function uses the file size and MIME type to estimate how many
buffers and what capacity they should have. The estimates are conservative
to avoid starving large document processing.

**Returns:**

A `PoolSizeHint` with recommended pool configuration

**Signature:**

```c
KreuzbergPoolSizeHint* kreuzberg_estimate_pool_size(uint64_t file_size, const char* mime_type);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `file_size` | `uint64_t` | Yes | Size of the file in bytes |
| `mime_type` | `const char*` | Yes | MIME type of the document (e.g., "application/pdf") |

**Returns:** `KreuzbergPoolSizeHint`


---

### kreuzberg_xml_tag_name()

Converts XML tag name bytes to a string, avoiding allocation when possible.

**Signature:**

```c
KreuzbergStr* kreuzberg_xml_tag_name(const uint8_t* name);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `name` | `const uint8_t*` | Yes | The name |

**Returns:** `KreuzbergStr`


---

### kreuzberg_escape_html_entities()

Escape `&`, `<`, and `>` in text destined for markdown/HTML output.

Underscores are intentionally **not** escaped. In extracted PDF text they are
literal content (e.g. identifiers like `CTC_ARP_01`), not markdown italic
delimiters.

Uses a single-pass scan: if no special characters are found, returns a
borrowed `Cow` with no allocation.

**Signature:**

```c
KreuzbergStr* kreuzberg_escape_html_entities(const char* text);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `text` | `const char*` | Yes | The text |

**Returns:** `KreuzbergStr`


---

### kreuzberg_normalize_whitespace()

Normalizes whitespace by collapsing multiple whitespace characters into single spaces.
Returns Cow.Borrowed if no normalization needed.

**Signature:**

```c
KreuzbergStr* kreuzberg_normalize_whitespace(const char* s);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `s` | `const char*` | Yes | The s |

**Returns:** `KreuzbergStr`


---

### kreuzberg_detect_columns()

Detect column positions from word x-coordinates.

Groups words by approximate x-position (within `column_threshold` pixels)
and returns the median x-position for each detected column, sorted left to right.

**Signature:**

```c
uint32_t* kreuzberg_detect_columns(KreuzbergHocrWord* words, uint32_t column_threshold);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `words` | `KreuzbergHocrWord*` | Yes | The words |
| `column_threshold` | `uint32_t` | Yes | The column threshold |

**Returns:** `uint32_t*`


---

### kreuzberg_detect_rows()

Detect row positions from word y-coordinates.

Groups words by their vertical center position and returns the median
y-position for each detected row. The `row_threshold_ratio` is multiplied
by the median word height to determine the grouping threshold.

**Signature:**

```c
uint32_t* kreuzberg_detect_rows(KreuzbergHocrWord* words, double row_threshold_ratio);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `words` | `KreuzbergHocrWord*` | Yes | The words |
| `row_threshold_ratio` | `double` | Yes | The row threshold ratio |

**Returns:** `uint32_t*`


---

### kreuzberg_reconstruct_table()

Reconstruct a table grid from words with bounding box positions.

Takes detected words and reconstructs a 2D table by:
1. Detecting column positions (grouping by x-coordinate within `column_threshold`)
2. Detecting row positions (grouping by y-center within `row_threshold_ratio` * median height)
3. Assigning words to cells based on closest row/column
4. Combining words within the same cell

Returns a `Vec<Vec<String>>` where each inner `Vec` is a row of cell texts.

**Signature:**

```c
const char*** kreuzberg_reconstruct_table(KreuzbergHocrWord* words, uint32_t column_threshold, double row_threshold_ratio);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `words` | `KreuzbergHocrWord*` | Yes | The words |
| `column_threshold` | `uint32_t` | Yes | The column threshold |
| `row_threshold_ratio` | `double` | Yes | The row threshold ratio |

**Returns:** `const char***`


---

### kreuzberg_table_to_markdown()

Convert a table grid to markdown format.

The first row is treated as the header row, with a separator line added after it.
Pipe characters in cell content are escaped.

**Signature:**

```c
const char* kreuzberg_table_to_markdown(const char*** table);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `table` | `const char***` | Yes | The table |

**Returns:** `const char*`


---

### kreuzberg_openapi_json()

Generate OpenAPI JSON schema.

Returns the complete OpenAPI 3.1 specification as a JSON string.

**Signature:**

```c
const char* kreuzberg_openapi_json();
```

**Returns:** `const char*`


---

### kreuzberg_validate_page_boundaries()

Validates the consistency and correctness of page boundaries.

# Validation Rules

1. Boundaries must be sorted by byte_start (monotonically increasing)
2. Boundaries must not overlap (byte_end[i] <= byte_start[i+1])
3. Each boundary must have byte_start < byte_end

**Returns:**

Returns `Ok(())` if all boundaries are valid.
Returns `KreuzbergError.Validation` if any boundary is invalid.

**Signature:**

```c
void kreuzberg_validate_page_boundaries(KreuzbergPageBoundary* boundaries);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `boundaries` | `KreuzbergPageBoundary*` | Yes | Page boundary markers to validate |

**Returns:** `void`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_calculate_page_range()

Calculate which pages a byte range spans.

**Returns:**

A tuple of (first_page, last_page) where page numbers are 1-indexed.
Returns (None, None) if boundaries are empty or chunk doesn't overlap any page.

**Errors:**

Returns `KreuzbergError.Validation` if boundaries are invalid.

**Signature:**

```c
KreuzbergOptionUsizeOptionUsize* kreuzberg_calculate_page_range(uintptr_t byte_start, uintptr_t byte_end, KreuzbergPageBoundary* boundaries);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `byte_start` | `uintptr_t` | Yes | Starting byte offset of the chunk |
| `byte_end` | `uintptr_t` | Yes | Ending byte offset of the chunk |
| `boundaries` | `KreuzbergPageBoundary*` | Yes | Page boundary markers from the document |

**Returns:** `KreuzbergOptionUsizeOptionUsize`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_classify_chunk()

Classify a single chunk based on its content and optional heading context.

Rules are evaluated in priority order. The first matching rule determines
the returned `ChunkType`. When no rule matches, `ChunkType.Unknown`
is returned.

  (only available when using `ChunkerType.Markdown`).

**Signature:**

```c
KreuzbergChunkType* kreuzberg_classify_chunk(const char* content, KreuzbergHeadingContext heading_context);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `content` | `const char*` | Yes | The text content of the chunk (may be trimmed or raw). |
| `heading_context` | `KreuzbergHeadingContext*` | No | Optional heading hierarchy this chunk falls under |

**Returns:** `KreuzbergChunkType`


---

### kreuzberg_chunk_text()

Split text into chunks with optional page boundary tracking.

This is the primary API function for chunking text. It supports both plain text
and Markdown with configurable chunk size, overlap, and page boundary mapping.

**Returns:**

A ChunkingResult containing all chunks and their metadata.

**Signature:**

```c
KreuzbergChunkingResult* kreuzberg_chunk_text(const char* text, KreuzbergChunkingConfig config, KreuzbergPageBoundary* page_boundaries);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `text` | `const char*` | Yes | The text to split into chunks |
| `config` | `KreuzbergChunkingConfig` | Yes | Chunking configuration (max size, overlap, type) |
| `page_boundaries` | `KreuzbergPageBoundary**` | No | Optional page boundary markers for mapping chunks to pages |

**Returns:** `KreuzbergChunkingResult`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_chunk_text_with_heading_source()

Chunk text with an optional separate markdown source for heading context resolution.

When `heading_source` is provided, it is used instead of `text` for building the
heading map. This is needed when `text` is plain text (no markdown headings) but
the original document had headings that were stripped during rendering.

**Signature:**

```c
KreuzbergChunkingResult* kreuzberg_chunk_text_with_heading_source(const char* text, KreuzbergChunkingConfig config, KreuzbergPageBoundary* page_boundaries, const char* heading_source);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `text` | `const char*` | Yes | The text |
| `config` | `KreuzbergChunkingConfig` | Yes | The configuration options |
| `page_boundaries` | `KreuzbergPageBoundary**` | No | The page boundaries |
| `heading_source` | `const char**` | No | The heading source |

**Returns:** `KreuzbergChunkingResult`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_chunk_text_with_type()

Chunk text with explicit type specification.

This is a convenience function that constructs a ChunkingConfig from individual
parameters and calls `chunk_text`.

**Returns:**

A ChunkingResult containing all chunks and their metadata.

**Signature:**

```c
KreuzbergChunkingResult* kreuzberg_chunk_text_with_type(const char* text, uintptr_t max_characters, uintptr_t overlap, bool trim, KreuzbergChunkerType chunker_type);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `text` | `const char*` | Yes | The text to split into chunks |
| `max_characters` | `uintptr_t` | Yes | Maximum characters per chunk |
| `overlap` | `uintptr_t` | Yes | Character overlap between consecutive chunks |
| `trim` | `bool` | Yes | Whether to trim whitespace from boundaries |
| `chunker_type` | `KreuzbergChunkerType` | Yes | Type of chunker to use (Text or Markdown) |

**Returns:** `KreuzbergChunkingResult`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_chunk_texts_batch()

Batch process multiple texts with the same configuration.

This convenience function applies the same chunking configuration to multiple
texts in sequence.

**Returns:**

A vector of ChunkingResult objects, one per input text.

**Errors:**

Returns an error if chunking any individual text fails.

**Signature:**

```c
KreuzbergChunkingResult* kreuzberg_chunk_texts_batch(const char** texts, KreuzbergChunkingConfig config);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `texts` | `const char**` | Yes | Slice of text strings to chunk |
| `config` | `KreuzbergChunkingConfig` | Yes | Chunking configuration to apply to all texts |

**Returns:** `KreuzbergChunkingResult*`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_precompute_utf8_boundaries()

Pre-computes valid UTF-8 character boundaries for a text string.

This function performs a single O(n) pass through the text to identify all valid
UTF-8 character boundaries, storing them in a BitVec for O(1) lookups.

**Returns:**

A BitVec where each bit represents whether a byte offset is a valid UTF-8 character boundary.
The BitVec has length `text.len() + 1` (includes the end position).

**Signature:**

```c
KreuzbergBitVec* kreuzberg_precompute_utf8_boundaries(const char* text);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `text` | `const char*` | Yes | The text to analyze |

**Returns:** `KreuzbergBitVec`


---

### kreuzberg_validate_utf8_boundaries()

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

```c
void kreuzberg_validate_utf8_boundaries(const char* text, KreuzbergPageBoundary* boundaries);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `text` | `const char*` | Yes | The text being chunked |
| `boundaries` | `KreuzbergPageBoundary*` | Yes | Page boundary markers to validate |

**Returns:** `void`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_register_chunking_processor()

Register the chunking processor with the global registry.

This function should be called once at application startup to register
the chunking post-processor.

**Note:** This is called automatically on first use.
Explicit calling is optional.

**Signature:**

```c
void kreuzberg_register_chunking_processor();
```

**Returns:** `void`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_create_client()

Create a liter-llm `DefaultClient` from kreuzberg's `LlmConfig`.

The `model` field from the config is passed as a model hint so that
liter-llm can resolve the correct provider automatically.

When `api_key` is `NULL`, liter-llm falls back to the provider's standard
environment variable (e.g., `OPENAI_API_KEY`).

**Signature:**

```c
KreuzbergDefaultClient* kreuzberg_create_client(KreuzbergLlmConfig config);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `config` | `KreuzbergLlmConfig` | Yes | The configuration options |

**Returns:** `KreuzbergDefaultClient`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_render_template()

Render a Jinja2 template with the given context variables.

**Signature:**

```c
const char* kreuzberg_render_template(const char* template, KreuzbergValue context);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `template` | `const char*` | Yes | The template |
| `context` | `KreuzbergValue` | Yes | The value |

**Returns:** `const char*`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_extract_structured()

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

```c
KreuzbergLlmUsage* kreuzberg_extract_structured(const char* content, KreuzbergStructuredExtractionConfig config);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `content` | `const char*` | Yes | The extracted document text to send to the LLM. |
| `config` | `KreuzbergStructuredExtractionConfig` | Yes | Structured extraction configuration including schema and LLM settings. |

**Returns:** `KreuzbergLlmUsage`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_vlm_ocr()

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

```c
KreuzbergLlmUsage* kreuzberg_vlm_ocr(const uint8_t* image_bytes, const char* image_mime_type, const char* language, KreuzbergLlmConfig config);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `image_bytes` | `const uint8_t*` | Yes | Raw image data (JPEG, PNG, WebP, etc.) |
| `image_mime_type` | `const char*` | Yes | MIME type of the image (e.g., `"image/png"`) |
| `language` | `const char*` | Yes | ISO 639 language code or Tesseract language name |
| `config` | `KreuzbergLlmConfig` | Yes | LLM provider/model configuration |

**Returns:** `KreuzbergLlmUsage`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_normalize()

L2-normalize a vector.

**Signature:**

```c
float* kreuzberg_normalize(float* v);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `v` | `float*` | Yes | The v |

**Returns:** `float*`


---

### kreuzberg_get_preset()

Get a preset by name.

**Signature:**

```c
KreuzbergEmbeddingPreset* kreuzberg_get_preset(const char* name);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `name` | `const char*` | Yes | The name |

**Returns:** `KreuzbergEmbeddingPreset*`


---

### kreuzberg_list_presets()

List all available preset names.

**Signature:**

```c
const char** kreuzberg_list_presets();
```

**Returns:** `const char**`


---

### kreuzberg_warm_model()

Eagerly download and cache an embedding model without returning the handle.

This triggers the same download and initialization as `get_or_init_engine`
but discards the result, making it suitable for cache-warming scenarios
where the caller doesn't need to use the model immediately.

**Note**: This function downloads AND initializes the ONNX model, which
requires ONNX Runtime and uses significant memory. For download-only
scenarios (e.g., init containers), use `download_model` instead.

**Signature:**

```c
void kreuzberg_warm_model(KreuzbergEmbeddingModelType model_type, const char* cache_dir);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `model_type` | `KreuzbergEmbeddingModelType` | Yes | The embedding model type |
| `cache_dir` | `const char**` | No | The cache dir |

**Returns:** `void`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_download_model()

Download an embedding model's files without initializing ONNX Runtime.

Downloads the model files (ONNX model, tokenizer, config) from HuggingFace
to the cache directory. Subsequent calls to `warm_model` or
`get_or_init_engine` will find the files cached and skip the download step.

This is ideal for init containers or CI environments where you want to
pre-populate the cache without loading models into memory.

**Signature:**

```c
void kreuzberg_download_model(KreuzbergEmbeddingModelType model_type, const char* cache_dir);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `model_type` | `KreuzbergEmbeddingModelType` | Yes | The embedding model type |
| `cache_dir` | `const char**` | No | The cache dir |

**Returns:** `void`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_generate_embeddings_for_chunks()

Generate embeddings for text chunks using the specified configuration.

This function modifies chunks in-place, populating their `embedding` field
with generated embedding vectors. It uses batch processing for efficiency.

**Returns:**

Returns `Ok(())` if embeddings were generated successfully, or an error if
model initialization or embedding generation fails.

**Signature:**

```c
void kreuzberg_generate_embeddings_for_chunks(KreuzbergChunk* chunks, KreuzbergEmbeddingConfig config);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `chunks` | `KreuzbergChunk*` | Yes | Mutable reference to vector of chunks to generate embeddings for |
| `config` | `KreuzbergEmbeddingConfig` | Yes | Embedding configuration specifying model and parameters |

**Returns:** `void`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_calculate_smart_dpi()

Calculate smart DPI based on page dimensions, memory constraints, and target DPI

**Signature:**

```c
int32_t kreuzberg_calculate_smart_dpi(double page_width, double page_height, int32_t target_dpi, int32_t max_dimension, double max_memory_mb);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `page_width` | `double` | Yes | The page width |
| `page_height` | `double` | Yes | The page height |
| `target_dpi` | `int32_t` | Yes | The target dpi |
| `max_dimension` | `int32_t` | Yes | The max dimension |
| `max_memory_mb` | `double` | Yes | The max memory mb |

**Returns:** `int32_t`


---

### kreuzberg_calculate_optimal_dpi()

Calculate optimal DPI with min/max constraints

**Signature:**

```c
int32_t kreuzberg_calculate_optimal_dpi(double page_width, double page_height, int32_t target_dpi, int32_t max_dimension, int32_t min_dpi, int32_t max_dpi);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `page_width` | `double` | Yes | The page width |
| `page_height` | `double` | Yes | The page height |
| `target_dpi` | `int32_t` | Yes | The target dpi |
| `max_dimension` | `int32_t` | Yes | The max dimension |
| `min_dpi` | `int32_t` | Yes | The min dpi |
| `max_dpi` | `int32_t` | Yes | The max dpi |

**Returns:** `int32_t`


---

### kreuzberg_normalize_image_dpi()

Normalize image DPI based on extraction configuration

**Returns:**
* `NormalizeResult` containing processed image data and metadata

**Signature:**

```c
KreuzbergNormalizeResult* kreuzberg_normalize_image_dpi(const uint8_t* rgb_data, uintptr_t width, uintptr_t height, KreuzbergExtractionConfig config, double current_dpi);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `rgb_data` | `const uint8_t*` | Yes | RGB image data as a flat `Vec<u8>` (height * width * 3 bytes, row-major) |
| `width` | `uintptr_t` | Yes | Image width in pixels |
| `height` | `uintptr_t` | Yes | Image height in pixels |
| `config` | `KreuzbergExtractionConfig` | Yes | Extraction configuration containing DPI settings |
| `current_dpi` | `double*` | No | Optional current DPI of the image (defaults to 72 if None) |

**Returns:** `KreuzbergNormalizeResult`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_resize_image()

Resize an image using fast_image_resize with appropriate algorithm based on scale factor

**Signature:**

```c
KreuzbergDynamicImage* kreuzberg_resize_image(KreuzbergDynamicImage image, uint32_t new_width, uint32_t new_height, double scale_factor);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `image` | `KreuzbergDynamicImage` | Yes | The dynamic image |
| `new_width` | `uint32_t` | Yes | The new width |
| `new_height` | `uint32_t` | Yes | The new height |
| `scale_factor` | `double` | Yes | The scale factor |

**Returns:** `KreuzbergDynamicImage`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_detect_languages()

Detect languages in text using whatlang.

Returns a list of detected language codes (ISO 639-3 format).
Returns `NULL` if no languages could be detected with sufficient confidence.

**Signature:**

```c
const char*** kreuzberg_detect_languages(const char* text, KreuzbergLanguageDetectionConfig config);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `text` | `const char*` | Yes | The text to analyze for language detection |
| `config` | `KreuzbergLanguageDetectionConfig` | Yes | Optional configuration for language detection |

**Returns:** `const char***`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_register_language_detection_processor()

Register the language detection processor with the global registry.

This function should be called once at application startup to register
the language detection post-processor.

**Note:** This is called automatically on first use.
Explicit calling is optional.

**Signature:**

```c
void kreuzberg_register_language_detection_processor();
```

**Returns:** `void`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_get_stopwords()

Get stopwords for a language with normalization.

This function provides a user-friendly interface to the stopwords registry with:
- **Case-insensitive lookup**: "EN", "en", "En" all work
- **Locale normalization**: "en-US", "en_GB", "es-ES" extract to "en", "es"
- **Consistent behavior**: Returns `NULL` for unsupported languages

# Language Code Format

Accepts multiple formats:
- ISO 639-1 two-letter codes: `"en"`, `"es"`, `"de"`, etc.
- Uppercase variants: `"EN"`, `"ES"`, `"DE"`
- Locale codes with hyphen: `"en-US"`, `"es-ES"`, `"pt-BR"`
- Locale codes with underscore: `"en_US"`, `"es_ES"`, `"pt_BR"`

All formats are normalized to lowercase two-letter ISO 639-1 codes.

**Returns:**

- `Some(&HashSet<String>)` if the language is supported (64 languages available)
- `NULL` if the language is not supported

# Performance

This function performs two operations:
1. String normalization (lowercase + truncate) - O(1) for typical language codes
2. HashMap lookup in STOPWORDS - O(1) average case

Total overhead is negligible (~10-50ns on modern CPUs).

**Signature:**

```c
KreuzbergAHashSet* kreuzberg_get_stopwords(const char* lang);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `lang` | `const char*` | Yes | The lang |

**Returns:** `KreuzbergAHashSet*`


---

### kreuzberg_get_stopwords_with_fallback()

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
- `NULL` if neither language is supported

# Common Patterns


# Performance

This function performs at most two HashMap lookups:
1. Try primary language (O(1) average case)
2. If None, try fallback language (O(1) average case)

Total overhead is negligible (~10-100ns on modern CPUs).

**Signature:**

```c
KreuzbergAHashSet* kreuzberg_get_stopwords_with_fallback(const char* language, const char* fallback);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `language` | `const char*` | Yes | Primary language code to try first |
| `fallback` | `const char*` | Yes | Fallback language code to use if primary not available |

**Returns:** `KreuzbergAHashSet*`


---

### kreuzberg_extract_keywords()

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

```c
KreuzbergKeyword* kreuzberg_extract_keywords(const char* text, KreuzbergKeywordConfig config);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `text` | `const char*` | Yes | The text to extract keywords from |
| `config` | `KreuzbergKeywordConfig` | Yes | Keyword extraction configuration |

**Returns:** `KreuzbergKeyword*`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_register_keyword_processor()

Register the keyword extraction processor with the global registry.

This function should be called once at application startup to register
the keyword extraction post-processor.

**Note:** This is called automatically on first use.
Explicit calling is optional.

**Signature:**

```c
void kreuzberg_register_keyword_processor();
```

**Returns:** `void`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_text_block_to_element()

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

```c
KreuzbergOcrElement* kreuzberg_text_block_to_element(KreuzbergTextBlock block, uintptr_t page_number);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `block` | `KreuzbergTextBlock` | Yes | PaddleOCR TextBlock containing OCR results |
| `page_number` | `uintptr_t` | Yes | 1-indexed page number |

**Returns:** `KreuzbergOcrElement*`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_tsv_row_to_element()

Convert a Tesseract TSV row to a unified OcrElement.

Preserves:
- Axis-aligned bounding box
- Recognition confidence (Tesseract doesn't have separate detection confidence)
- Hierarchical level information

**Returns:**

An `OcrElement` with rectangle geometry and Tesseract metadata.

**Signature:**

```c
KreuzbergOcrElement* kreuzberg_tsv_row_to_element(KreuzbergTsvRow row);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `row` | `KreuzbergTsvRow` | Yes | Parsed TSV row from Tesseract output |

**Returns:** `KreuzbergOcrElement`


---

### kreuzberg_iterator_word_to_element()

Convert a Tesseract iterator WordData to a unified OcrElement with rich metadata.

Unlike `tsv_row_to_element` which only has text, bbox, and confidence,
this populates font attributes (bold, italic, monospace, pointsize) and
block/paragraph context from the Tesseract layout analysis.

**Returns:**

An `OcrElement` at `Word` level with all available font and layout metadata.

**Signature:**

```c
KreuzbergOcrElement* kreuzberg_iterator_word_to_element(KreuzbergWordData word, KreuzbergTessPolyBlockType block_type, KreuzbergParaInfo para_info, uintptr_t page_number);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `word` | `KreuzbergWordData` | Yes | WordData from the Tesseract result iterator |
| `block_type` | `KreuzbergTessPolyBlockType*` | No | Optional block type from Tesseract layout analysis |
| `para_info` | `KreuzbergParaInfo*` | No | Optional paragraph metadata (justification, list item flag) |
| `page_number` | `uintptr_t` | Yes | 1-indexed page number |

**Returns:** `KreuzbergOcrElement`


---

### kreuzberg_element_to_hocr_word()

Convert an OcrElement to an HocrWord for table reconstruction.

This enables reuse of the existing table detection algorithms from
html-to-markdown-rs with PaddleOCR results.

**Returns:**

An `HocrWord` suitable for table reconstruction algorithms.

**Signature:**

```c
KreuzbergHocrWord* kreuzberg_element_to_hocr_word(KreuzbergOcrElement element);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `element` | `KreuzbergOcrElement` | Yes | Unified OCR element with geometry and text |

**Returns:** `KreuzbergHocrWord`


---

### kreuzberg_elements_to_hocr_words()

Convert a vector of OcrElements to HocrWords for batch table processing.

Filters to word-level elements only, as table reconstruction
works best with word-level granularity.

**Returns:**

A vector of HocrWords filtered by confidence and element level.

**Signature:**

```c
KreuzbergHocrWord* kreuzberg_elements_to_hocr_words(KreuzbergOcrElement* elements, double min_confidence);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `elements` | `KreuzbergOcrElement*` | Yes | Slice of OCR elements to convert |
| `min_confidence` | `double` | Yes | Minimum recognition confidence threshold (0.0-1.0) |

**Returns:** `KreuzbergHocrWord*`


---

### kreuzberg_parse_hocr_to_internal_document()

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

```c
KreuzbergInternalDocument* kreuzberg_parse_hocr_to_internal_document(const char* hocr_html);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `hocr_html` | `const char*` | Yes | The hocr html |

**Returns:** `KreuzbergInternalDocument`


---

### kreuzberg_assemble_ocr_markdown()

Assemble structured markdown from OCR elements using layout detection results.

Both inputs must be in the same pixel coordinate space (from the same
rendered page image). Returns plain text join when `detection` is `NULL`.

`recognized_tables` provides pre-computed markdown for Table regions
(from TATR or other table structure recognizer). When empty, Table
regions fall back to heuristic grid reconstruction from OCR elements.

**Signature:**

```c
const char* kreuzberg_assemble_ocr_markdown(KreuzbergOcrElement* elements, KreuzbergDetectionResult detection, uint32_t img_width, uint32_t img_height, KreuzbergRecognizedTable* recognized_tables);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `elements` | `KreuzbergOcrElement*` | Yes | The elements |
| `detection` | `KreuzbergDetectionResult*` | No | The detection result |
| `img_width` | `uint32_t` | Yes | The img width |
| `img_height` | `uint32_t` | Yes | The img height |
| `recognized_tables` | `KreuzbergRecognizedTable*` | Yes | The recognized tables |

**Returns:** `const char*`


---

### kreuzberg_recognize_page_tables()

Run TATR table recognition for all Table regions in a page.

For each Table detection, crops the page image, runs TATR inference,
matches OCR elements to cells, and produces markdown tables.

**Signature:**

```c
KreuzbergRecognizedTable* kreuzberg_recognize_page_tables(KreuzbergRgbImage page_image, KreuzbergDetectionResult detection, KreuzbergOcrElement* elements, KreuzbergTatrModel tatr_model);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `page_image` | `KreuzbergRgbImage` | Yes | The rgb image |
| `detection` | `KreuzbergDetectionResult` | Yes | The detection result |
| `elements` | `KreuzbergOcrElement*` | Yes | The elements |
| `tatr_model` | `KreuzbergTatrModel` | Yes | The tatr model |

**Returns:** `KreuzbergRecognizedTable*`


---

### kreuzberg_extract_words_from_tsv()

Extract words from Tesseract TSV output and convert to HocrWord format.

This parses Tesseract's TSV format (level, page_num, block_num, ...) and
converts it to the HocrWord format used for table reconstruction.

**Signature:**

```c
KreuzbergHocrWord* kreuzberg_extract_words_from_tsv(const char* tsv_data, double min_confidence);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `tsv_data` | `const char*` | Yes | The tsv data |
| `min_confidence` | `double` | Yes | The min confidence |

**Returns:** `KreuzbergHocrWord*`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_compute_hash()

Compute a blake3 hash string from input data.

Returns a 32-character hex string (128 bits of blake3 output).

**Signature:**

```c
const char* kreuzberg_compute_hash(const char* data);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `data` | `const char*` | Yes | The data |

**Returns:** `const char*`


---

### kreuzberg_validate_language_code()

**Signature:**

```c
void kreuzberg_validate_language_code(const char* lang_code);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `lang_code` | `const char*` | Yes | The lang code |

**Returns:** `void`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_validate_tesseract_version()

**Signature:**

```c
void kreuzberg_validate_tesseract_version(uint32_t version);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `version` | `uint32_t` | Yes | The version |

**Returns:** `void`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_ensure_ort_available()

Ensure ONNX Runtime is discoverable. Safe to call multiple times (no-op after first).

When the `ort-bundled` feature is enabled the ORT binaries are embedded via the
official Microsoft release and no system library search is needed.

**Signature:**

```c
void kreuzberg_ensure_ort_available();
```

**Returns:** `void`


---

### kreuzberg_is_language_supported()

Check if a language code is supported by PaddleOCR.

**Signature:**

```c
bool kreuzberg_is_language_supported(const char* lang);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `lang` | `const char*` | Yes | The lang |

**Returns:** `bool`


---

### kreuzberg_language_to_script_family()

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

```c
const char* kreuzberg_language_to_script_family(const char* paddle_lang);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `paddle_lang` | `const char*` | Yes | The paddle lang |

**Returns:** `const char*`


---

### kreuzberg_map_language_code()

Map Kreuzberg language codes to PaddleOCR language codes.

**Signature:**

```c
const char** kreuzberg_map_language_code(const char* kreuzberg_code);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `kreuzberg_code` | `const char*` | Yes | The kreuzberg code |

**Returns:** `const char**`


---

### kreuzberg_resolve_cache_dir()

Resolve the cache directory for the auto-rotate model.

**Signature:**

```c
const char* kreuzberg_resolve_cache_dir();
```

**Returns:** `const char*`


---

### kreuzberg_detect_and_rotate()

Detect orientation and return a corrected image if rotation is needed.

Returns `Ok(Some(rotated_bytes))` if rotation was applied,
`Ok(None)` if no rotation needed (0° or low confidence).

**Signature:**

```c
const uint8_t** kreuzberg_detect_and_rotate(KreuzbergDocOrientationDetector detector, const uint8_t* image_bytes);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `detector` | `KreuzbergDocOrientationDetector` | Yes | The doc orientation detector |
| `image_bytes` | `const uint8_t*` | Yes | The image bytes |

**Returns:** `const uint8_t**`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_build_cell_grid()

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

```c
KreuzbergCellBBox** kreuzberg_build_cell_grid(KreuzbergTatrResult result, KreuzbergF324 table_bbox);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `result` | `KreuzbergTatrResult` | Yes | The tatr result |
| `table_bbox` | `KreuzbergF324*` | No | The [f32;4] |

**Returns:** `KreuzbergCellBBox**`


---

### kreuzberg_apply_heuristics()

Apply Docling-style postprocessing heuristics to raw detections.

This implements the key heuristics from `docling/utils/layout_postprocessor.py`:
1. Per-class confidence thresholds
2. Full-page picture removal (>90% page area)
3. Overlap resolution (IoU > 0.8 or containment > 0.8)
4. Cross-type overlap handling (KVR vs Table)

**Signature:**

```c
void kreuzberg_apply_heuristics(KreuzbergLayoutDetection* detections, float page_width, float page_height);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `detections` | `KreuzbergLayoutDetection*` | Yes | The detections |
| `page_width` | `float` | Yes | The page width |
| `page_height` | `float` | Yes | The page height |

**Returns:** `void`


---

### kreuzberg_greedy_nms()

Standard greedy Non-Maximum Suppression.

Sorts detections by confidence (descending), then iteratively removes
detections that have IoU > `iou_threshold` with any higher-confidence detection.

This is required for YOLO models. RT-DETR is NMS-free.

**Signature:**

```c
void kreuzberg_greedy_nms(KreuzbergLayoutDetection* detections, float iou_threshold);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `detections` | `KreuzbergLayoutDetection*` | Yes | The detections |
| `iou_threshold` | `float` | Yes | The iou threshold |

**Returns:** `void`


---

### kreuzberg_preprocess_imagenet()

Preprocess an image for models using ImageNet normalization (e.g., RT-DETR).

Pipeline: resize to target_size x target_size (bilinear) -> rescale /255 -> ImageNet normalize -> NCHW f32.

Uses a single vectorized pass over contiguous pixel data for maximum throughput.

**Signature:**

```c
KreuzbergArray4* kreuzberg_preprocess_imagenet(KreuzbergRgbImage img, uint32_t target_size);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `img` | `KreuzbergRgbImage` | Yes | The rgb image |
| `target_size` | `uint32_t` | Yes | The target size |

**Returns:** `KreuzbergArray4`


---

### kreuzberg_preprocess_imagenet_letterbox()

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

```c
KreuzbergArray4F32F32U32U32* kreuzberg_preprocess_imagenet_letterbox(KreuzbergRgbImage img, uint32_t target_size);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `img` | `KreuzbergRgbImage` | Yes | The rgb image |
| `target_size` | `uint32_t` | Yes | The target size |

**Returns:** `KreuzbergArray4F32F32U32U32`


---

### kreuzberg_preprocess_rescale()

Preprocess with rescale only (no ImageNet normalization).

Pipeline: resize to target_size x target_size -> rescale /255 -> NCHW f32.

**Signature:**

```c
KreuzbergArray4* kreuzberg_preprocess_rescale(KreuzbergRgbImage img, uint32_t target_size);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `img` | `KreuzbergRgbImage` | Yes | The rgb image |
| `target_size` | `uint32_t` | Yes | The target size |

**Returns:** `KreuzbergArray4`


---

### kreuzberg_preprocess_letterbox()

Letterbox preprocessing for YOLOX-style models.

Resizes the image to fit within (target_width x target_height) while maintaining
aspect ratio, padding the remaining area with value 114.0 (raw pixel value).
No normalization — values are 0-255 as YOLOX expects.

Returns the NCHW tensor and the scale ratio (for rescaling detections back).

**Signature:**

```c
KreuzbergArray4F32F32* kreuzberg_preprocess_letterbox(KreuzbergRgbImage img, uint32_t target_width, uint32_t target_height);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `img` | `KreuzbergRgbImage` | Yes | The rgb image |
| `target_width` | `uint32_t` | Yes | The target width |
| `target_height` | `uint32_t` | Yes | The target height |

**Returns:** `KreuzbergArray4F32F32`


---

### kreuzberg_build_session()

Build an optimized ORT session from an ONNX model file.

`thread_budget` controls the number of intra-op threads for this session.
Pass the result of `crate.core.config.concurrency.resolve_thread_budget`
to respect the user's `ConcurrencyConfig`.

When `accel` is `NULL` or `Auto`, uses platform defaults:
- macOS: CoreML (Neural Engine / GPU)
- Linux: CUDA (GPU)
- Others: CPU only

ORT silently falls back to CPU if the requested EP is unavailable.

**Signature:**

```c
KreuzbergSession* kreuzberg_build_session(const char* path, KreuzbergAccelerationConfig accel, uintptr_t thread_budget);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `path` | `const char*` | Yes | Path to the file |
| `accel` | `KreuzbergAccelerationConfig*` | No | The acceleration config |
| `thread_budget` | `uintptr_t` | Yes | The thread budget |

**Returns:** `KreuzbergSession`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_config_from_extraction()

Convert a `LayoutDetectionConfig` into a `LayoutEngineConfig`.

**Signature:**

```c
KreuzbergLayoutEngineConfig* kreuzberg_config_from_extraction(KreuzbergLayoutDetectionConfig layout_config);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `layout_config` | `KreuzbergLayoutDetectionConfig` | Yes | The layout detection config |

**Returns:** `KreuzbergLayoutEngineConfig`


---

### kreuzberg_create_engine()

Create a `LayoutEngine` from a `LayoutDetectionConfig`.

Ensures ORT is available, then creates the engine with model download.

**Signature:**

```c
KreuzbergLayoutEngine* kreuzberg_create_engine(KreuzbergLayoutDetectionConfig layout_config);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `layout_config` | `KreuzbergLayoutDetectionConfig` | Yes | The layout detection config |

**Returns:** `KreuzbergLayoutEngine`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_take_or_create_engine()

Take the cached layout engine, or create a new one if the cache is empty.

The caller owns the engine for the duration of its work and should
return it via `return_engine` when done. This avoids holding the
global mutex during inference.

**Signature:**

```c
KreuzbergLayoutEngine* kreuzberg_take_or_create_engine(KreuzbergLayoutDetectionConfig layout_config);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `layout_config` | `KreuzbergLayoutDetectionConfig` | Yes | The layout detection config |

**Returns:** `KreuzbergLayoutEngine`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_return_engine()

Return a layout engine to the global cache for reuse by future extractions.

**Signature:**

```c
void kreuzberg_return_engine(KreuzbergLayoutEngine engine);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `engine` | `KreuzbergLayoutEngine` | Yes | The layout engine |

**Returns:** `void`


---

### kreuzberg_take_or_create_tatr()

Take the cached TATR model, or create a new one if the cache is empty.

Returns `NULL` if the model cannot be loaded. Once a load attempt fails,
subsequent calls return `NULL` immediately without retrying, avoiding
repeated download attempts and redundant warning logs.

**Signature:**

```c
KreuzbergTatrModel* kreuzberg_take_or_create_tatr();
```

**Returns:** `KreuzbergTatrModel*`


---

### kreuzberg_return_tatr()

Return a TATR model to the global cache for reuse.

**Signature:**

```c
void kreuzberg_return_tatr(KreuzbergTatrModel model);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `model` | `KreuzbergTatrModel` | Yes | The tatr model |

**Returns:** `void`


---

### kreuzberg_take_or_create_slanet()

Take a cached SLANeXT model for the given variant, or create a new one.

**Signature:**

```c
KreuzbergSlanetModel* kreuzberg_take_or_create_slanet(const char* variant);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `variant` | `const char*` | Yes | The variant |

**Returns:** `KreuzbergSlanetModel*`


---

### kreuzberg_return_slanet()

Return a SLANeXT model to the global cache for reuse.

**Signature:**

```c
void kreuzberg_return_slanet(const char* variant, KreuzbergSlanetModel model);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `variant` | `const char*` | Yes | The variant |
| `model` | `KreuzbergSlanetModel` | Yes | The slanet model |

**Returns:** `void`


---

### kreuzberg_take_or_create_table_classifier()

Take a cached table classifier, or create a new one.

**Signature:**

```c
KreuzbergTableClassifier* kreuzberg_take_or_create_table_classifier();
```

**Returns:** `KreuzbergTableClassifier*`


---

### kreuzberg_return_table_classifier()

Return a table classifier to the global cache for reuse.

**Signature:**

```c
void kreuzberg_return_table_classifier(KreuzbergTableClassifier model);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `model` | `KreuzbergTableClassifier` | Yes | The table classifier |

**Returns:** `void`


---

### kreuzberg_extract_annotations_from_document()

Extract annotations from all pages of a PDF document.

Iterates over every page and every annotation on each page, mapping
pdfium annotation subtypes to `PdfAnnotationType` and collecting
content text and bounding boxes where available.

Annotations that cannot be read are silently skipped.

**Returns:**

A `Vec<PdfAnnotation>` containing all successfully extracted annotations.

**Signature:**

```c
KreuzbergPdfAnnotation* kreuzberg_extract_annotations_from_document(KreuzbergPdfDocument document);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `document` | `KreuzbergPdfDocument` | Yes | A reference to the loaded pdfium `PdfDocument`. |

**Returns:** `KreuzbergPdfAnnotation*`


---

### kreuzberg_extract_bookmarks()

Extract bookmarks (outlines) from a PDF document loaded via lopdf.

Walks the `/Outlines` tree in the document catalog, collecting each bookmark's
title and destination. Returns an empty `Vec` if the document has no outlines.

**Signature:**

```c
KreuzbergUri* kreuzberg_extract_bookmarks(KreuzbergDocument document);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `document` | `KreuzbergDocument` | Yes | The document |

**Returns:** `KreuzbergUri*`


---

### kreuzberg_extract_bundled_pdfium()

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

```c
const char* kreuzberg_extract_bundled_pdfium();
```

**Returns:** `const char*`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_extract_embedded_files()

Extract embedded file descriptors from a PDF document loaded via lopdf.

Walks the `/Names` → `/EmbeddedFiles` name tree in the catalog.
Returns an empty `Vec` if the document has no embedded files.

**Signature:**

```c
KreuzbergEmbeddedFile* kreuzberg_extract_embedded_files(KreuzbergDocument document);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `document` | `KreuzbergDocument` | Yes | The document |

**Returns:** `KreuzbergEmbeddedFile*`


---

### kreuzberg_extract_and_process_embedded_files()

Extract embedded files from PDF bytes and recursively process them.

Returns `(children, warnings)`. The children are `ArchiveEntry` values
suitable for attaching to `InternalDocument.children`.

**Signature:**

```c
KreuzbergVecArchiveEntryVecProcessingWarning* kreuzberg_extract_and_process_embedded_files(const uint8_t* pdf_bytes, KreuzbergExtractionConfig config);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pdf_bytes` | `const uint8_t*` | Yes | The pdf bytes |
| `config` | `KreuzbergExtractionConfig` | Yes | The configuration options |

**Returns:** `KreuzbergVecArchiveEntryVecProcessingWarning`


---

### kreuzberg_initialize_font_cache()

Initialize the global font cache.

On first call, discovers and loads all system fonts. Subsequent calls are no-ops.
Caching is thread-safe via RwLock; concurrent reads during PDF processing are efficient.

**Returns:**

Ok if initialization succeeds or cache is already initialized, or PdfError if font discovery fails.

# Performance

- First call: 50-100ms (system font discovery + loading)
- Subsequent calls: < 1μs (no-op, just checks initialized flag)

**Signature:**

```c
void kreuzberg_initialize_font_cache();
```

**Returns:** `void`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_get_font_descriptors()

Get cached font descriptors for Pdfium configuration.

Ensures the font cache is initialized, then returns font descriptors
derived from the cached fonts. This call is fast after the first invocation.

**Returns:**

A Vec of FontDescriptor objects suitable for `PdfiumConfig.set_font_provider()`.

# Performance

- First call: ~50-100ms (includes font discovery)
- Subsequent calls: < 1ms (reads from cache)

**Signature:**

```c
KreuzbergFontDescriptor* kreuzberg_get_font_descriptors();
```

**Returns:** `KreuzbergFontDescriptor*`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_cached_font_count()

Get the number of cached fonts.

Useful for diagnostics and testing.

**Returns:**

Number of fonts in the cache, or 0 if not initialized.

**Signature:**

```c
uintptr_t kreuzberg_cached_font_count();
```

**Returns:** `uintptr_t`


---

### kreuzberg_clear_font_cache()

Clear the font cache (for testing purposes).

**Panics:**

Panics if the cache lock is poisoned, which should only happen in test scenarios
with deliberate panic injection.

**Signature:**

```c
void kreuzberg_clear_font_cache();
```

**Returns:** `void`


---

### kreuzberg_extract_images_from_pdf()

**Signature:**

```c
KreuzbergPdfImage* kreuzberg_extract_images_from_pdf(const uint8_t* pdf_bytes);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pdf_bytes` | `const uint8_t*` | Yes | The pdf bytes |

**Returns:** `KreuzbergPdfImage*`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_extract_images_from_pdf_with_password()

**Signature:**

```c
KreuzbergPdfImage* kreuzberg_extract_images_from_pdf_with_password(const uint8_t* pdf_bytes, const char* password);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pdf_bytes` | `const uint8_t*` | Yes | The pdf bytes |
| `password` | `const char*` | Yes | The password |

**Returns:** `KreuzbergPdfImage*`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_reextract_raw_images_via_pdfium()

Re-extract images that have unusable formats (`"raw"`, `"ccitt"`, `"jbig2"`) by
rendering them through pdfium's bitmap pipeline, which handles all PDF filter
chains internally.

Returns the number of images successfully re-extracted.

**Signature:**

```c
uint32_t kreuzberg_reextract_raw_images_via_pdfium(const uint8_t* pdf_bytes, KreuzbergPdfImage* images);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pdf_bytes` | `const uint8_t*` | Yes | The pdf bytes |
| `images` | `KreuzbergPdfImage*` | Yes | The images |

**Returns:** `uint32_t`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_detect_layout_for_document()

Run layout detection on all pages of a PDF document.

Under the hood, this uses batched layout detection to prevent holding too many
full-resolution page images in memory simultaneously before detection.

**Signature:**

```c
KreuzbergDynamicImage* kreuzberg_detect_layout_for_document(const uint8_t* pdf_bytes, KreuzbergLayoutEngine engine);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pdf_bytes` | `const uint8_t*` | Yes | The pdf bytes |
| `engine` | `KreuzbergLayoutEngine` | Yes | The layout engine |

**Returns:** `KreuzbergDynamicImage`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_detect_layout_for_images()

Run layout detection on pre-rendered images.

Returns pixel-space `DetectionResult`s — no PDF coordinate conversion.
Use this when images are already available (e.g., from the OCR rendering
path) to avoid redundant PDF re-rendering.

**Signature:**

```c
KreuzbergDetectionResult* kreuzberg_detect_layout_for_images(KreuzbergDynamicImage* images, KreuzbergLayoutEngine engine);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `images` | `KreuzbergDynamicImage*` | Yes | The images |
| `engine` | `KreuzbergLayoutEngine` | Yes | The layout engine |

**Returns:** `KreuzbergDetectionResult*`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_extract_metadata()

Extract PDF-specific metadata from raw bytes.

Returns only PDF-specific metadata (version, producer, encryption status, dimensions).

**Signature:**

```c
KreuzbergPdfMetadata* kreuzberg_extract_metadata(const uint8_t* pdf_bytes);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pdf_bytes` | `const uint8_t*` | Yes | The pdf bytes |

**Returns:** `KreuzbergPdfMetadata`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_extract_metadata_with_password()

Extract PDF-specific metadata from raw bytes with optional password.

Returns only PDF-specific metadata (version, producer, encryption status, dimensions).

**Signature:**

```c
KreuzbergPdfMetadata* kreuzberg_extract_metadata_with_password(const uint8_t* pdf_bytes, const char* password);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pdf_bytes` | `const uint8_t*` | Yes | The pdf bytes |
| `password` | `const char**` | No | The password |

**Returns:** `KreuzbergPdfMetadata`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_extract_metadata_with_passwords()

**Signature:**

```c
KreuzbergPdfMetadata* kreuzberg_extract_metadata_with_passwords(const uint8_t* pdf_bytes, const char** passwords);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pdf_bytes` | `const uint8_t*` | Yes | The pdf bytes |
| `passwords` | `const char**` | Yes | The passwords |

**Returns:** `KreuzbergPdfMetadata`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_extract_metadata_from_document()

Extract complete PDF metadata from a document.

Extracts common fields (title, subject, authors, keywords, dates, creator),
PDF-specific metadata, and optionally builds a PageStructure with boundaries.

  If provided, a PageStructure will be built with these boundaries.
* `content` - Optional extracted text content, used for blank page detection.
  If provided, `PageInfo.is_blank` will be populated based on text content analysis.
  If `NULL`, `is_blank` will be `NULL` for all pages.

**Returns:**

Returns a `PdfExtractionMetadata` struct containing all extracted metadata,
including page structure if boundaries were provided.

**Signature:**

```c
KreuzbergPdfExtractionMetadata* kreuzberg_extract_metadata_from_document(KreuzbergPdfDocument document, KreuzbergPageBoundary* page_boundaries, const char* content);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `document` | `KreuzbergPdfDocument` | Yes | The PDF document to extract metadata from |
| `page_boundaries` | `KreuzbergPageBoundary**` | No | Optional vector of PageBoundary entries for building PageStructure. |
| `content` | `const char**` | No | Optional extracted text content, used for blank page detection. |

**Returns:** `KreuzbergPdfExtractionMetadata`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_extract_common_metadata_from_document()

Extract common metadata from a PDF document.

Returns common fields (title, authors, keywords, dates) that are now stored
in the base `Metadata` struct instead of format-specific metadata.

This function uses batch fetching with caching to optimize metadata extraction
by reducing repeated dictionary lookups. All metadata tags are fetched once and
cached in a single pass.

**Signature:**

```c
KreuzbergCommonPdfMetadata* kreuzberg_extract_common_metadata_from_document(KreuzbergPdfDocument document);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `document` | `KreuzbergPdfDocument` | Yes | The pdf document |

**Returns:** `KreuzbergCommonPdfMetadata`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_render_page_to_image()

**Signature:**

```c
KreuzbergDynamicImage* kreuzberg_render_page_to_image(const uint8_t* pdf_bytes, uintptr_t page_index, KreuzbergPageRenderOptions options);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pdf_bytes` | `const uint8_t*` | Yes | The pdf bytes |
| `page_index` | `uintptr_t` | Yes | The page index |
| `options` | `KreuzbergPageRenderOptions` | Yes | The options to use |

**Returns:** `KreuzbergDynamicImage`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_render_pdf_page_to_png()

Render a single PDF page to a PNG-encoded byte buffer.

**Errors:**

Returns an error if the PDF is invalid, the page index is out of bounds,
or if the page fails to render.

**Signature:**

```c
const uint8_t* kreuzberg_render_pdf_page_to_png(const uint8_t* pdf_bytes, uintptr_t page_index, int32_t dpi, const char* password);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pdf_bytes` | `const uint8_t*` | Yes | The pdf bytes |
| `page_index` | `uintptr_t` | Yes | The page index |
| `dpi` | `int32_t*` | No | The dpi |
| `password` | `const char**` | No | The password |

**Returns:** `const uint8_t*`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_extract_words_from_page()

Extract words with positions from PDF page for table detection.

Groups adjacent characters into words based on spacing heuristics,
then converts to HocrWord format for table reconstruction.

**Returns:**

Vector of HocrWord objects with text and bounding box information.

**Note:**
This function requires the "ocr" feature to be enabled. Without it, returns an error.

**Signature:**

```c
KreuzbergHocrWord* kreuzberg_extract_words_from_page(KreuzbergPdfPage page, double min_confidence);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `page` | `KreuzbergPdfPage` | Yes | PDF page to extract words from |
| `min_confidence` | `double` | Yes | Minimum confidence threshold (0.0-100.0). PDF text has high confidence (95.0). |

**Returns:** `KreuzbergHocrWord*`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_segment_to_hocr_word()

Convert a PDF `SegmentData` to an `HocrWord` for table reconstruction.

`SegmentData` uses PDF coordinates (y=0 at bottom, increases upward).
`HocrWord` uses image coordinates (y=0 at top, increases downward).

**Signature:**

```c
KreuzbergHocrWord* kreuzberg_segment_to_hocr_word(KreuzbergSegmentData seg, float page_height);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `seg` | `KreuzbergSegmentData` | Yes | The segment data |
| `page_height` | `float` | Yes | The page height |

**Returns:** `KreuzbergHocrWord`


---

### kreuzberg_split_segment_to_words()

Split a `SegmentData` into word-level `HocrWord`s for table reconstruction.

Pdfium segments can contain multiple whitespace-separated words (merged by
shared baseline + font). For table cell matching, each word needs its own
bounding box so it can be assigned to the correct column/cell.

Single-word segments use `segment_to_hocr_word` directly (fast path).
Multi-word segments get proportional bbox estimation per word based on
byte offset within the segment text.

**Signature:**

```c
KreuzbergHocrWord* kreuzberg_split_segment_to_words(KreuzbergSegmentData seg, float page_height);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `seg` | `KreuzbergSegmentData` | Yes | The segment data |
| `page_height` | `float` | Yes | The page height |

**Returns:** `KreuzbergHocrWord*`


---

### kreuzberg_segments_to_words()

Convert a page's segments to word-level `HocrWord`s for table extraction.

Splits multi-word segments into individual words with proportional bounding
boxes, ensuring each word can be independently matched to table cells.

**Signature:**

```c
KreuzbergHocrWord* kreuzberg_segments_to_words(KreuzbergSegmentData* segments, float page_height);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `segments` | `KreuzbergSegmentData*` | Yes | The segments |
| `page_height` | `float` | Yes | The page height |

**Returns:** `KreuzbergHocrWord*`


---

### kreuzberg_post_process_table()

Post-process a raw table grid to validate structure and clean up.

Returns `NULL` if the table fails structural validation.

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

```c
const char**** kreuzberg_post_process_table(const char*** table, bool layout_guided, bool allow_single_column);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `table` | `const char***` | Yes | The table |
| `layout_guided` | `bool` | Yes | The layout guided |
| `allow_single_column` | `bool` | Yes | The allow single column |

**Returns:** `const char****`


---

### kreuzberg_is_well_formed_table()

Validate whether a reconstructed table grid represents a well-formed table
rather than multi-column prose or a repeated page element.

Returns `true` if the grid looks like a real table, `false` if it should be
rejected and its content emitted as paragraph text instead.

The checks catch cases the layout model misidentifies as tables:
- Multi-column prose split into a grid (detected via row coherence and column uniformity)
- Repeated page elements (headers/footers detected as tables on every page)
- Low-vocabulary repetitive content (same few words in every row)

**Signature:**

```c
bool kreuzberg_is_well_formed_table(const char*** grid);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `grid` | `const char***` | Yes | The grid |

**Returns:** `bool`


---

### kreuzberg_extract_text_from_pdf()

**Signature:**

```c
const char* kreuzberg_extract_text_from_pdf(const uint8_t* pdf_bytes);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pdf_bytes` | `const uint8_t*` | Yes | The pdf bytes |

**Returns:** `const char*`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_extract_text_from_pdf_with_password()

**Signature:**

```c
const char* kreuzberg_extract_text_from_pdf_with_password(const uint8_t* pdf_bytes, const char* password);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pdf_bytes` | `const uint8_t*` | Yes | The pdf bytes |
| `password` | `const char*` | Yes | The password |

**Returns:** `const char*`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_extract_text_from_pdf_with_passwords()

**Signature:**

```c
const char* kreuzberg_extract_text_from_pdf_with_passwords(const uint8_t* pdf_bytes, const char** passwords);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `pdf_bytes` | `const uint8_t*` | Yes | The pdf bytes |
| `passwords` | `const char**` | Yes | The passwords |

**Returns:** `const char*`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_extract_text_and_metadata_from_pdf_document()

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

```c
KreuzbergPdfUnifiedExtractionResult* kreuzberg_extract_text_and_metadata_from_pdf_document(KreuzbergPdfDocument document, KreuzbergExtractionConfig extraction_config);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `document` | `KreuzbergPdfDocument` | Yes | The PDF document to extract from |
| `extraction_config` | `KreuzbergExtractionConfig*` | No | Optional extraction configuration for hierarchy and page tracking |

**Returns:** `KreuzbergPdfUnifiedExtractionResult`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_extract_text_from_pdf_document()

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

```c
KreuzbergPdfTextExtractionResult* kreuzberg_extract_text_from_pdf_document(KreuzbergPdfDocument document, KreuzbergPageConfig page_config, KreuzbergExtractionConfig extraction_config);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `document` | `KreuzbergPdfDocument` | Yes | The PDF document to extract text from |
| `page_config` | `KreuzbergPageConfig*` | No | Optional page configuration for boundary tracking and page markers |
| `extraction_config` | `KreuzbergExtractionConfig*` | No | Optional extraction configuration for hierarchy detection |

**Returns:** `KreuzbergPdfTextExtractionResult`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_serialize_to_toon()

Serialize an `ExtractionResult` to TOON (Token-Oriented Object Notation).

TOON is a token-efficient alternative to JSON for LLM prompts.
Losslessly convertible to/from JSON but uses fewer tokens.

**Signature:**

```c
const char* kreuzberg_serialize_to_toon(KreuzbergExtractionResult result);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `result` | `KreuzbergExtractionResult` | Yes | The extraction result |

**Returns:** `const char*`

**Errors:** Returns `NULL` on error.


---

### kreuzberg_serialize_to_json()

Serialize an `ExtractionResult` to pretty-printed JSON.

**Signature:**

```c
const char* kreuzberg_serialize_to_json(KreuzbergExtractionResult result);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `result` | `KreuzbergExtractionResult` | Yes | The extraction result |

**Returns:** `const char*`

**Errors:** Returns `NULL` on error.


---

## Types

### KreuzbergAccelerationConfig

Hardware acceleration configuration for ONNX Runtime models.

Controls which execution provider (CPU, CoreML, CUDA, TensorRT) is used
for inference in layout detection and embedding generation.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `provider` | `KreuzbergExecutionProviderType` | `KREUZBERG_KREUZBERG_AUTO` | Execution provider to use for ONNX inference. |
| `device_id` | `uint32_t` | `NULL` | GPU device ID (for CUDA/TensorRT). Ignored for CPU/CoreML/Auto. |


---

### KreuzbergAnchorProperties

Properties for anchored drawings.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `behind_doc` | `bool` | `NULL` | Behind doc |
| `layout_in_cell` | `bool` | `NULL` | Layout in cell |
| `relative_height` | `int64_t*` | `NULL` | Relative height |
| `position_h` | `KreuzbergPosition*` | `NULL` | Position h (position) |
| `position_v` | `KreuzbergPosition*` | `NULL` | Position v (position) |
| `wrap_type` | `KreuzbergWrapType` | `KREUZBERG_KREUZBERG_NONE` | Wrap type (wrap type) |


---

### KreuzbergApiDoc

OpenAPI documentation structure.

Defines all endpoints, request/response schemas, and examples
for the Kreuzberg document extraction API.


---

### KreuzbergArchiveEntry

A single file extracted from an archive.

When archives (ZIP, TAR, 7Z, GZIP) are extracted with recursive extraction
enabled, each processable file produces its own full `ExtractionResult`.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `path` | `const char*` | — | Archive-relative file path (e.g. "folder/document.pdf"). |
| `mime_type` | `const char*` | — | Detected MIME type of the file. |
| `result` | `KreuzbergExtractionResult` | — | Full extraction result for this file. |


---

### KreuzbergArchiveMetadata

Archive (ZIP/TAR/7Z) metadata.

Extracted from compressed archive files containing file lists and size information.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `format` | `KreuzbergStr` | — | Archive format ("ZIP", "TAR", "7Z", etc.) |
| `file_count` | `uintptr_t` | — | Total number of files in the archive |
| `file_list` | `const char**` | — | List of file paths within the archive |
| `total_size` | `uintptr_t` | — | Total uncompressed size in bytes |
| `compressed_size` | `uintptr_t*` | `NULL` | Compressed size in bytes (if available) |


---

### KreuzbergAttributes

Element attributes in Djot.

Represents the attributes attached to elements using {.class #id key="value"} syntax.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `id` | `const char**` | `NULL` | Element ID (#identifier) |
| `classes` | `const char**` | `NULL` | CSS classes (.class1 .class2) |
| `key_values` | `KreuzbergStringString*` | `NULL` | Key-value pairs (key="value") |


---

### KreuzbergBBox

Bounding box in original image coordinates (x1, y1) top-left, (x2, y2) bottom-right.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `x1` | `float` | — | X1 |
| `y1` | `float` | — | Y1 |
| `x2` | `float` | — | X2 |
| `y2` | `float` | — | Y2 |

#### Methods

##### kreuzberg_width()

**Signature:**

```c
float kreuzberg_width();
```

##### kreuzberg_height()

**Signature:**

```c
float kreuzberg_height();
```

##### kreuzberg_area()

**Signature:**

```c
float kreuzberg_area();
```

##### kreuzberg_center()

**Signature:**

```c
KreuzbergF32F32 kreuzberg_center();
```

##### kreuzberg_intersection_area()

Area of intersection with another bounding box.

**Signature:**

```c
float kreuzberg_intersection_area(KreuzbergBBox other);
```

##### kreuzberg_iou()

Intersection over Union with another bounding box.

**Signature:**

```c
float kreuzberg_iou(KreuzbergBBox other);
```

##### kreuzberg_containment_of()

Fraction of `other` that is contained within `self`.
Returns 0.0..=1.0 where 1.0 means `other` is fully inside `self`.

**Signature:**

```c
float kreuzberg_containment_of(KreuzbergBBox other);
```

##### kreuzberg_page_coverage()

Fraction of page area this bbox covers.

**Signature:**

```c
float kreuzberg_page_coverage(float page_width, float page_height);
```

##### kreuzberg_fmt()

**Signature:**

```c
KreuzbergUnknown kreuzberg_fmt(KreuzbergFormatter f);
```


---

### KreuzbergBatchItemResult

Batch item result for processing multiple files

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `file_path` | `const char*` | — | File path |
| `success` | `bool` | — | Success |
| `result` | `KreuzbergOcrExtractionResult*` | `NULL` | Result (ocr extraction result) |
| `error` | `const char**` | `NULL` | Error |


---

### KreuzbergBatchProcessor

Batch processor that manages object pools for optimized extraction.

This struct manages the lifecycle of reusable object pools used during
batch extraction. Pools are created lazily on first use and reused across
all documents processed by this batch processor.

# Lazy Initialization

Pools are initialized on demand to reduce memory usage for applications
that may not use batch processing immediately or at all.

#### Methods

##### kreuzberg_with_config()

Create a new batch processor with custom pool configuration.

Pools are not created immediately but lazily on first access.

**Returns:**

A new `BatchProcessor` configured with the provided settings.

**Signature:**

```c
KreuzbergBatchProcessor kreuzberg_with_config(KreuzbergBatchProcessorConfig config);
```

##### kreuzberg_with_pool_hint()

Create a batch processor with pool sizes optimized for a specific document.

This method uses a `PoolSizeHint` (derived from file size and MIME type)
to create a batch processor with appropriately sized pools. This reduces
memory waste by tailoring pool allocation to actual document complexity.

**Returns:**

A new `BatchProcessor` configured with the hint-based pool sizes

**Signature:**

```c
KreuzbergBatchProcessor kreuzberg_with_pool_hint(KreuzbergPoolSizeHint hint);
```

##### kreuzberg_string_pool()

Get a reference to the string buffer pool.

Creates the pool lazily on first access.
Useful for custom pooling implementations that need direct pool access.

**Signature:**

```c
KreuzbergStringBufferPool kreuzberg_string_pool();
```

##### kreuzberg_byte_pool()

Get a reference to the byte buffer pool.

Creates the pool lazily on first access.
Useful for custom pooling implementations that need direct pool access.

**Signature:**

```c
KreuzbergByteBufferPool kreuzberg_byte_pool();
```

##### kreuzberg_config()

Get the current configuration.

**Signature:**

```c
KreuzbergBatchProcessorConfig kreuzberg_config();
```

##### kreuzberg_string_pool_size()

Get the number of pooled string buffers currently available.

**Signature:**

```c
uintptr_t kreuzberg_string_pool_size();
```

##### kreuzberg_byte_pool_size()

Get the number of pooled byte buffers currently available.

**Signature:**

```c
uintptr_t kreuzberg_byte_pool_size();
```

##### kreuzberg_clear_pools()

Clear all pooled objects, forcing new allocations on next acquire.

Useful for memory-constrained environments or to reclaim memory
after processing large batches.

**Signature:**

```c
void kreuzberg_clear_pools();
```

##### kreuzberg_default()

**Signature:**

```c
KreuzbergBatchProcessor kreuzberg_default();
```


---

### KreuzbergBatchProcessorConfig

Configuration for batch processing with pooling optimizations.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `string_pool_size` | `uintptr_t` | `10` | Maximum number of string buffers to maintain in the pool |
| `string_buffer_capacity` | `uintptr_t` | `8192` | Initial capacity for pooled string buffers in bytes |
| `byte_pool_size` | `uintptr_t` | `10` | Maximum number of byte buffers to maintain in the pool |
| `byte_buffer_capacity` | `uintptr_t` | `65536` | Initial capacity for pooled byte buffers in bytes |
| `max_concurrent` | `uintptr_t*` | `NULL` | Maximum concurrent extractions (for concurrency control) |

#### Methods

##### kreuzberg_default()

**Signature:**

```c
KreuzbergBatchProcessorConfig kreuzberg_default();
```


---

### KreuzbergBibtexExtractor

BibTeX bibliography extractor.

Parses BibTeX files and extracts structured bibliography data including
entries, authors, publication years, and entry type distribution.

#### Methods

##### kreuzberg_default()

**Signature:**

```c
KreuzbergBibtexExtractor kreuzberg_default();
```

##### kreuzberg_name()

**Signature:**

```c
const char* kreuzberg_name();
```

##### kreuzberg_version()

**Signature:**

```c
const char* kreuzberg_version();
```

##### kreuzberg_initialize()

**Signature:**

```c
void kreuzberg_initialize();
```

##### kreuzberg_shutdown()

**Signature:**

```c
void kreuzberg_shutdown();
```

##### kreuzberg_description()

**Signature:**

```c
const char* kreuzberg_description();
```

##### kreuzberg_author()

**Signature:**

```c
const char* kreuzberg_author();
```

##### kreuzberg_extract_bytes()

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_extract_bytes(const uint8_t* content, const char* mime_type, KreuzbergExtractionConfig config);
```

##### kreuzberg_supported_mime_types()

**Signature:**

```c
const char** kreuzberg_supported_mime_types();
```

##### kreuzberg_priority()

**Signature:**

```c
int32_t kreuzberg_priority();
```


---

### KreuzbergBibtexMetadata

BibTeX bibliography metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `entry_count` | `uintptr_t` | `NULL` | Number of entry |
| `citation_keys` | `const char**` | `NULL` | Citation keys |
| `authors` | `const char**` | `NULL` | Authors |
| `year_range` | `KreuzbergYearRange*` | `NULL` | Year range (year range) |
| `entry_types` | `void**` | `NULL` | Entry types |


---

### KreuzbergBorderStyle

A single border specification.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `style` | `const char*` | — | Style |
| `size` | `int32_t*` | `NULL` | Size in bytes |
| `color` | `const char**` | `NULL` | Color |
| `space` | `int32_t*` | `NULL` | Space |


---

### KreuzbergBoundingBox

Bounding box coordinates for element positioning.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `x0` | `double` | — | Left x-coordinate |
| `y0` | `double` | — | Bottom y-coordinate |
| `x1` | `double` | — | Right x-coordinate |
| `y1` | `double` | — | Top y-coordinate |


---

### KreuzbergByteBufferPool

Convenience type alias for a pooled Vec<u8>.


---

### KreuzbergCacheStats

Cache statistics.

Provides information about the extraction result cache,
including size, file count, and age distribution.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `total_files` | `uintptr_t` | — | Total number of cached files |
| `total_size_mb` | `double` | — | Total cache size in megabytes |
| `available_space_mb` | `double` | — | Available disk space in megabytes |
| `oldest_file_age_days` | `double` | — | Age of the oldest cached file in days |
| `newest_file_age_days` | `double` | — | Age of the newest cached file in days |


---

### KreuzbergCellBBox

A cell bounding box within the reconstructed table grid.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `x1` | `float` | — | X1 |
| `y1` | `float` | — | Y1 |
| `x2` | `float` | — | X2 |
| `y2` | `float` | — | Y2 |


---

### KreuzbergCellBorders

Per-cell borders (4 sides).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `top` | `KreuzbergBorderStyle*` | `NULL` | Top (border style) |
| `bottom` | `KreuzbergBorderStyle*` | `NULL` | Bottom (border style) |
| `left` | `KreuzbergBorderStyle*` | `NULL` | Left (border style) |
| `right` | `KreuzbergBorderStyle*` | `NULL` | Right (border style) |


---

### KreuzbergCellMargins

Cell margins (used for both table-level defaults and per-cell overrides).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `top` | `int32_t*` | `NULL` | Top |
| `bottom` | `int32_t*` | `NULL` | Bottom |
| `left` | `int32_t*` | `NULL` | Left |
| `right` | `int32_t*` | `NULL` | Right |


---

### KreuzbergCellProperties

Cell-level properties from `<w:tcPr>`.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `width` | `KreuzbergTableWidth*` | `NULL` | Width (table width) |
| `grid_span` | `uint32_t*` | `NULL` | Grid span |
| `v_merge` | `KreuzbergVerticalMerge*` | `KREUZBERG_KREUZBERG_RESTART` | V merge (vertical merge) |
| `borders` | `KreuzbergCellBorders*` | `NULL` | Borders (cell borders) |
| `shading` | `KreuzbergCellShading*` | `NULL` | Shading (cell shading) |
| `margins` | `KreuzbergCellMargins*` | `NULL` | Margins (cell margins) |
| `vertical_align` | `const char**` | `NULL` | Vertical align |
| `text_direction` | `const char**` | `NULL` | Text direction |
| `no_wrap` | `bool` | `NULL` | No wrap |


---

### KreuzbergCellShading

Cell shading/background.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `fill` | `const char**` | `NULL` | Fill |
| `color` | `const char**` | `NULL` | Color |
| `val` | `const char**` | `NULL` | Val |


---

### KreuzbergCfbReader

#### Methods

##### kreuzberg_from_bytes()

Open a CFB compound file from raw bytes.

**Signature:**

```c
KreuzbergCfbReader kreuzberg_from_bytes(const uint8_t* bytes);
```


---

### KreuzbergChunk

A text chunk with optional embedding and metadata.

Chunks are created when chunking is enabled in `ExtractionConfig`. Each chunk
contains the text content, optional embedding vector (if embedding generation
is configured), and metadata about its position in the document.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `content` | `const char*` | — | The text content of this chunk. |
| `chunk_type` | `KreuzbergChunkType` | — | Semantic structural classification of this chunk. Assigned by the heuristic classifier based on content patterns and heading context. Defaults to `ChunkType.Unknown` when no rule matches. |
| `embedding` | `float**` | `NULL` | Optional embedding vector for this chunk. Only populated when `EmbeddingConfig` is provided in chunking configuration. The dimensionality depends on the chosen embedding model. |
| `metadata` | `KreuzbergChunkMetadata` | — | Metadata about this chunk's position and properties. |


---

### KreuzbergChunkMetadata

Metadata about a chunk's position in the original document.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `byte_start` | `uintptr_t` | — | Byte offset where this chunk starts in the original text (UTF-8 valid boundary). |
| `byte_end` | `uintptr_t` | — | Byte offset where this chunk ends in the original text (UTF-8 valid boundary). |
| `token_count` | `uintptr_t*` | `NULL` | Number of tokens in this chunk (if available). This is calculated by the embedding model's tokenizer if embeddings are enabled. |
| `chunk_index` | `uintptr_t` | — | Zero-based index of this chunk in the document. |
| `total_chunks` | `uintptr_t` | — | Total number of chunks in the document. |
| `first_page` | `uintptr_t*` | `NULL` | First page number this chunk spans (1-indexed). Only populated when page tracking is enabled in extraction configuration. |
| `last_page` | `uintptr_t*` | `NULL` | Last page number this chunk spans (1-indexed, equal to first_page for single-page chunks). Only populated when page tracking is enabled in extraction configuration. |
| `heading_context` | `KreuzbergHeadingContext*` | `NULL` | Heading context when using Markdown chunker. Contains the heading hierarchy this chunk falls under. Only populated when `ChunkerType.Markdown` is used. |


---

### KreuzbergChunkingConfig

Chunking configuration.

Configures text chunking for document content, including chunk size,
overlap, trimming behavior, and optional embeddings.

Use `..the default constructor` when constructing to allow for future field additions:

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `max_characters` | `uintptr_t` | `1000` | Maximum size per chunk (in units determined by `sizing`). When `sizing` is `Characters` (default), this is the max character count. When using token-based sizing, this is the max token count. Default: 1000 |
| `overlap` | `uintptr_t` | `200` | Overlap between chunks (in units determined by `sizing`). Default: 200 |
| `trim` | `bool` | `true` | Whether to trim whitespace from chunk boundaries. Default: true |
| `chunker_type` | `KreuzbergChunkerType` | `KREUZBERG_KREUZBERG_TEXT` | Type of chunker to use (Text or Markdown). Default: Text |
| `embedding` | `KreuzbergEmbeddingConfig*` | `NULL` | Optional embedding configuration for chunk embeddings. |
| `preset` | `const char**` | `NULL` | Use a preset configuration (overrides individual settings if provided). |
| `sizing` | `KreuzbergChunkSizing` | `KREUZBERG_KREUZBERG_CHARACTERS` | How to measure chunk size. Default: `Characters` (Unicode character count). Enable `chunking-tiktoken` or `chunking-tokenizers` features for token-based sizing. |
| `prepend_heading_context` | `bool` | `false` | When `True` and `chunker_type` is `Markdown`, prepend the heading hierarchy path (e.g. `"# Title > ## Section\n\n"`) to each chunk's content string. This is useful for RAG pipelines where each chunk needs self-contained context about its position in the document structure. Default: `False` |

#### Methods

##### kreuzberg_with_chunker_type()

Set the chunker type.

**Signature:**

```c
KreuzbergChunkingConfig kreuzberg_with_chunker_type(KreuzbergChunkerType chunker_type);
```

##### kreuzberg_with_sizing()

Set the sizing strategy.

**Signature:**

```c
KreuzbergChunkingConfig kreuzberg_with_sizing(KreuzbergChunkSizing sizing);
```

##### kreuzberg_with_prepend_heading_context()

Enable or disable prepending heading context to chunk content.

**Signature:**

```c
KreuzbergChunkingConfig kreuzberg_with_prepend_heading_context(bool prepend);
```

##### kreuzberg_default()

**Signature:**

```c
KreuzbergChunkingConfig kreuzberg_default();
```


---

### KreuzbergChunkingProcessor

Post-processor that chunks text in document content.

This processor:
- Runs in the Middle processing stage
- Only processes when `config.chunking` is configured
- Stores chunks in `result.chunks`
- Uses configurable chunk size and overlap

#### Methods

##### kreuzberg_name()

**Signature:**

```c
const char* kreuzberg_name();
```

##### kreuzberg_version()

**Signature:**

```c
const char* kreuzberg_version();
```

##### kreuzberg_initialize()

**Signature:**

```c
void kreuzberg_initialize();
```

##### kreuzberg_shutdown()

**Signature:**

```c
void kreuzberg_shutdown();
```

##### kreuzberg_process()

**Signature:**

```c
void kreuzberg_process(KreuzbergExtractionResult result, KreuzbergExtractionConfig config);
```

##### kreuzberg_processing_stage()

**Signature:**

```c
KreuzbergProcessingStage kreuzberg_processing_stage();
```

##### kreuzberg_should_process()

**Signature:**

```c
bool kreuzberg_should_process(KreuzbergExtractionResult result, KreuzbergExtractionConfig config);
```

##### kreuzberg_estimated_duration_ms()

**Signature:**

```c
uint64_t kreuzberg_estimated_duration_ms(KreuzbergExtractionResult result);
```


---

### KreuzbergChunkingResult

Result of a text chunking operation.

Contains the generated chunks and metadata about the chunking.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `chunks` | `KreuzbergChunk*` | — | List of text chunks |
| `chunk_count` | `uintptr_t` | — | Total number of chunks generated |


---

### KreuzbergCitationExtractor

Citation format extractor for RIS, PubMed/MEDLINE, and EndNote XML formats.

Parses citation files and extracts structured bibliography data including
entries, authors, publication years, and format-specific metadata.

#### Methods

##### kreuzberg_default()

**Signature:**

```c
KreuzbergCitationExtractor kreuzberg_default();
```

##### kreuzberg_name()

**Signature:**

```c
const char* kreuzberg_name();
```

##### kreuzberg_version()

**Signature:**

```c
const char* kreuzberg_version();
```

##### kreuzberg_initialize()

**Signature:**

```c
void kreuzberg_initialize();
```

##### kreuzberg_shutdown()

**Signature:**

```c
void kreuzberg_shutdown();
```

##### kreuzberg_description()

**Signature:**

```c
const char* kreuzberg_description();
```

##### kreuzberg_author()

**Signature:**

```c
const char* kreuzberg_author();
```

##### kreuzberg_extract_bytes()

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_extract_bytes(const uint8_t* content, const char* mime_type, KreuzbergExtractionConfig config);
```

##### kreuzberg_supported_mime_types()

**Signature:**

```c
const char** kreuzberg_supported_mime_types();
```

##### kreuzberg_priority()

**Signature:**

```c
int32_t kreuzberg_priority();
```


---

### KreuzbergCitationMetadata

Citation file metadata (RIS, PubMed, EndNote).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `citation_count` | `uintptr_t` | `NULL` | Number of citation |
| `format` | `const char**` | `NULL` | Format |
| `authors` | `const char**` | `NULL` | Authors |
| `year_range` | `KreuzbergYearRange*` | `NULL` | Year range (year range) |
| `dois` | `const char**` | `NULL` | Dois |
| `keywords` | `const char**` | `NULL` | Keywords |


---

### KreuzbergCodeExtractor

Source code extractor using tree-sitter language pack.

Detects the programming language from the file extension or shebang line,
then uses tree-sitter to parse and extract structural information.

#### Methods

##### kreuzberg_default()

**Signature:**

```c
KreuzbergCodeExtractor kreuzberg_default();
```

##### kreuzberg_name()

**Signature:**

```c
const char* kreuzberg_name();
```

##### kreuzberg_version()

**Signature:**

```c
const char* kreuzberg_version();
```

##### kreuzberg_initialize()

**Signature:**

```c
void kreuzberg_initialize();
```

##### kreuzberg_shutdown()

**Signature:**

```c
void kreuzberg_shutdown();
```

##### kreuzberg_description()

**Signature:**

```c
const char* kreuzberg_description();
```

##### kreuzberg_author()

**Signature:**

```c
const char* kreuzberg_author();
```

##### kreuzberg_extract_bytes()

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_extract_bytes(const uint8_t* content, const char* mime_type, KreuzbergExtractionConfig config);
```

##### kreuzberg_extract_file()

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_extract_file(const char* path, const char* mime_type, KreuzbergExtractionConfig config);
```

##### kreuzberg_supported_mime_types()

**Signature:**

```c
const char** kreuzberg_supported_mime_types();
```

##### kreuzberg_priority()

**Signature:**

```c
int32_t kreuzberg_priority();
```

##### kreuzberg_as_sync_extractor()

**Signature:**

```c
KreuzbergSyncExtractor* kreuzberg_as_sync_extractor();
```

##### kreuzberg_extract_sync()

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_extract_sync(const uint8_t* content, const char* mime_type, KreuzbergExtractionConfig config);
```


---

### KreuzbergColorScheme

Color scheme containing all 12 standard Office theme colors.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `name` | `const char*` | `NULL` | Color scheme name. |
| `dk1` | `KreuzbergThemeColor*` | `KREUZBERG_KREUZBERG_RGB` | Dark 1 (dark background) color. |
| `lt1` | `KreuzbergThemeColor*` | `KREUZBERG_KREUZBERG_RGB` | Light 1 (light background) color. |
| `dk2` | `KreuzbergThemeColor*` | `KREUZBERG_KREUZBERG_RGB` | Dark 2 color. |
| `lt2` | `KreuzbergThemeColor*` | `KREUZBERG_KREUZBERG_RGB` | Light 2 color. |
| `accent1` | `KreuzbergThemeColor*` | `KREUZBERG_KREUZBERG_RGB` | Accent color 1. |
| `accent2` | `KreuzbergThemeColor*` | `KREUZBERG_KREUZBERG_RGB` | Accent color 2. |
| `accent3` | `KreuzbergThemeColor*` | `KREUZBERG_KREUZBERG_RGB` | Accent color 3. |
| `accent4` | `KreuzbergThemeColor*` | `KREUZBERG_KREUZBERG_RGB` | Accent color 4. |
| `accent5` | `KreuzbergThemeColor*` | `KREUZBERG_KREUZBERG_RGB` | Accent color 5. |
| `accent6` | `KreuzbergThemeColor*` | `KREUZBERG_KREUZBERG_RGB` | Accent color 6. |
| `hlink` | `KreuzbergThemeColor*` | `KREUZBERG_KREUZBERG_RGB` | Hyperlink color. |
| `fol_hlink` | `KreuzbergThemeColor*` | `KREUZBERG_KREUZBERG_RGB` | Followed hyperlink color. |


---

### KreuzbergColumnLayout

Column layout configuration.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `count` | `int32_t*` | `NULL` | Number of columns. |
| `space_twips` | `int32_t*` | `NULL` | Space between columns in twips. |
| `equal_width` | `bool*` | `NULL` | Whether columns have equal width. |


---

### KreuzbergCommonPdfMetadata

Common metadata fields extracted from a PDF.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `title` | `const char**` | `NULL` | Title |
| `subject` | `const char**` | `NULL` | Subject |
| `authors` | `const char***` | `NULL` | Authors |
| `keywords` | `const char***` | `NULL` | Keywords |
| `created_at` | `const char**` | `NULL` | Created at |
| `modified_at` | `const char**` | `NULL` | Modified at |
| `created_by` | `const char**` | `NULL` | Created by |


---

### KreuzbergConcurrencyConfig

Controls thread usage for constrained environments.

Set `max_threads` to cap all internal thread pools (Rayon, ONNX Runtime
intra-op) and batch concurrency to a single limit.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `max_threads` | `uintptr_t*` | `NULL` | Maximum number of threads for all internal thread pools. Caps Rayon global pool size, ONNX Runtime intra-op threads, and (when `max_concurrent_extractions` is unset) the batch concurrency semaphore. When `None`, system defaults are used. |


---

### KreuzbergContentFilterConfig

Cross-extractor content filtering configuration.

Controls whether "furniture" content (headers, footers, page numbers,
watermarks, repeating text) is included in or stripped from extraction
results. Applies across all extractors (PDF, DOCX, RTF, ODT, HTML, etc.)
with format-specific implementation.

When `NULL` on `ExtractionConfig`, each extractor uses its current
default behavior unchanged.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `include_headers` | `bool` | `false` | Include running headers in extraction output. - PDF: Disables top-margin furniture stripping and prevents the layout model from treating `PageHeader`-classified regions as furniture. - DOCX: Includes document headers in text output. - RTF/ODT: Headers already included; this is a no-op when true. - HTML/EPUB: Keeps `<header>` element content. Default: `False` (headers are stripped or excluded). |
| `include_footers` | `bool` | `false` | Include running footers in extraction output. - PDF: Disables bottom-margin furniture stripping and prevents the layout model from treating `PageFooter`-classified regions as furniture. - DOCX: Includes document footers in text output. - RTF/ODT: Footers already included; this is a no-op when true. - HTML/EPUB: Keeps `<footer>` element content. Default: `False` (footers are stripped or excluded). |
| `strip_repeating_text` | `bool` | `true` | Enable the heuristic cross-page repeating text detector. When `True` (default), text that repeats verbatim across a supermajority of pages is classified as furniture and stripped.  Disable this if brand names or repeated headings are being incorrectly removed by the heuristic. Note: when a layout-detection model is active, the model may independently classify page-header / page-footer regions as furniture on a per-page basis. To preserve those regions, set `include_headers = true` and/or `include_footers = true` in addition to disabling this flag. Primarily affects PDF extraction. Default: `True`. |
| `include_watermarks` | `bool` | `false` | Include watermark text in extraction output. - PDF: Keeps watermark artifacts and arXiv identifiers. - Other formats: No effect currently. Default: `False` (watermarks are stripped). |

#### Methods

##### kreuzberg_default()

**Signature:**

```c
KreuzbergContentFilterConfig kreuzberg_default();
```


---

### KreuzbergContributorRole

JATS contributor with role.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `name` | `const char*` | — | The name |
| `role` | `const char**` | `NULL` | Role |


---

### KreuzbergCoreProperties

Dublin Core metadata from docProps/core.xml

Contains standard metadata fields defined by the Dublin Core standard
and Office-specific extensions.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `title` | `const char**` | `NULL` | Document title |
| `subject` | `const char**` | `NULL` | Document subject/topic |
| `creator` | `const char**` | `NULL` | Document creator/author |
| `keywords` | `const char**` | `NULL` | Keywords or tags |
| `description` | `const char**` | `NULL` | Document description/abstract |
| `last_modified_by` | `const char**` | `NULL` | User who last modified the document |
| `revision` | `const char**` | `NULL` | Revision number |
| `created` | `const char**` | `NULL` | Creation timestamp (ISO 8601) |
| `modified` | `const char**` | `NULL` | Last modification timestamp (ISO 8601) |
| `category` | `const char**` | `NULL` | Document category |
| `content_status` | `const char**` | `NULL` | Content status (Draft, Final, etc.) |
| `language` | `const char**` | `NULL` | Document language |
| `identifier` | `const char**` | `NULL` | Unique identifier |
| `version` | `const char**` | `NULL` | Document version |
| `last_printed` | `const char**` | `NULL` | Last print timestamp (ISO 8601) |


---

### KreuzbergCsvExtractor

CSV/TSV extractor with proper field parsing.

Replaces raw text passthrough with structured CSV parsing,
producing space-separated text output and populated `tables` field.

#### Methods

##### kreuzberg_default()

**Signature:**

```c
KreuzbergCsvExtractor kreuzberg_default();
```

##### kreuzberg_name()

**Signature:**

```c
const char* kreuzberg_name();
```

##### kreuzberg_version()

**Signature:**

```c
const char* kreuzberg_version();
```

##### kreuzberg_initialize()

**Signature:**

```c
void kreuzberg_initialize();
```

##### kreuzberg_shutdown()

**Signature:**

```c
void kreuzberg_shutdown();
```

##### kreuzberg_description()

**Signature:**

```c
const char* kreuzberg_description();
```

##### kreuzberg_author()

**Signature:**

```c
const char* kreuzberg_author();
```

##### kreuzberg_extract_bytes()

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_extract_bytes(const uint8_t* content, const char* mime_type, KreuzbergExtractionConfig config);
```

##### kreuzberg_supported_mime_types()

**Signature:**

```c
const char** kreuzberg_supported_mime_types();
```

##### kreuzberg_priority()

**Signature:**

```c
int32_t kreuzberg_priority();
```


---

### KreuzbergCsvMetadata

CSV/TSV file metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `row_count` | `uintptr_t` | `NULL` | Number of row |
| `column_count` | `uintptr_t` | `NULL` | Number of column |
| `delimiter` | `const char**` | `NULL` | Delimiter |
| `has_header` | `bool` | `NULL` | Whether header |
| `column_types` | `const char***` | `NULL` | Column types |


---

### KreuzbergCustomProperties

Custom properties from docProps/custom.xml

Maps property names to their values. Values are converted to JSON types
based on the VT (Variant Type) specified in the XML.


---

### KreuzbergDbfExtractor

Extractor for dBASE (.dbf) files.

Reads all records and formats them as a markdown table with
column headers derived from field names.

#### Methods

##### kreuzberg_default()

**Signature:**

```c
KreuzbergDbfExtractor kreuzberg_default();
```

##### kreuzberg_name()

**Signature:**

```c
const char* kreuzberg_name();
```

##### kreuzberg_version()

**Signature:**

```c
const char* kreuzberg_version();
```

##### kreuzberg_initialize()

**Signature:**

```c
void kreuzberg_initialize();
```

##### kreuzberg_shutdown()

**Signature:**

```c
void kreuzberg_shutdown();
```

##### kreuzberg_description()

**Signature:**

```c
const char* kreuzberg_description();
```

##### kreuzberg_author()

**Signature:**

```c
const char* kreuzberg_author();
```

##### kreuzberg_extract_bytes()

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_extract_bytes(const uint8_t* content, const char* mime_type, KreuzbergExtractionConfig config);
```

##### kreuzberg_supported_mime_types()

**Signature:**

```c
const char** kreuzberg_supported_mime_types();
```

##### kreuzberg_priority()

**Signature:**

```c
int32_t kreuzberg_priority();
```


---

### KreuzbergDbfFieldInfo

dBASE field information.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `name` | `const char*` | — | The name |
| `field_type` | `const char*` | — | Field type |


---

### KreuzbergDbfMetadata

dBASE (DBF) file metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `record_count` | `uintptr_t` | `NULL` | Number of record |
| `field_count` | `uintptr_t` | `NULL` | Number of field |
| `fields` | `KreuzbergDbfFieldInfo*` | `NULL` | Fields |


---

### KreuzbergDepthValidator

Helper struct for validating nesting depth.

#### Methods

##### kreuzberg_push()

Push a level (increase depth).

**Returns:**
* `Ok(())` if depth is within limits
* `Err(SecurityError)` if depth exceeds limit

**Signature:**

```c
void kreuzberg_push();
```

##### kreuzberg_pop()

Pop a level (decrease depth).

**Signature:**

```c
void kreuzberg_pop();
```

##### kreuzberg_current_depth()

Get current depth.

**Signature:**

```c
uintptr_t kreuzberg_current_depth();
```


---

### KreuzbergDetectTimings

Granular timing breakdown for a single `detect()` call.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `preprocess_ms` | `double` | `NULL` | Time spent in image preprocessing (resize, letterbox, normalize, tensor allocation). |
| `onnx_ms` | `double` | `NULL` | Time for the ONNX `session.run()` call (actual neural network computation). |
| `model_total_ms` | `double` | `NULL` | Total time from start of model call to end of raw output decoding. |
| `postprocess_ms` | `double` | `NULL` | Time spent in postprocessing heuristics (confidence filtering, overlap resolution). |


---

### KreuzbergDetectionResult

Page-level detection result containing all detections and page metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `page_width` | `uint32_t` | — | Page width |
| `page_height` | `uint32_t` | — | Page height |
| `detections` | `KreuzbergLayoutDetection*` | — | Detections |


---

### KreuzbergDjotContent

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
| `plain_text` | `const char*` | — | Plain text representation for backwards compatibility |
| `blocks` | `KreuzbergFormattedBlock*` | — | Structured block-level content |
| `metadata` | `KreuzbergMetadata` | — | Metadata from YAML frontmatter |
| `tables` | `KreuzbergTable*` | — | Extracted tables as structured data |
| `images` | `KreuzbergDjotImage*` | — | Extracted images with metadata |
| `links` | `KreuzbergDjotLink*` | — | Extracted links with URLs |
| `footnotes` | `KreuzbergFootnote*` | — | Footnote definitions |
| `attributes` | `KreuzbergStringAttributes*` | — | Attributes mapped by element identifier (if present) |


---

### KreuzbergDjotExtractor

Djot markup extractor with metadata and table support.

Parses Djot documents with YAML frontmatter, extracting:
- Metadata from YAML frontmatter
- Plain text content
- Tables as structured data
- Document structure (headings, links, code blocks)

#### Methods

##### kreuzberg_build_internal_document()

Build an `InternalDocument` from jotdown events.

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_build_internal_document(KreuzbergEvent* events);
```

##### kreuzberg_default()

**Signature:**

```c
KreuzbergDjotExtractor kreuzberg_default();
```

##### kreuzberg_name()

**Signature:**

```c
const char* kreuzberg_name();
```

##### kreuzberg_version()

**Signature:**

```c
const char* kreuzberg_version();
```

##### kreuzberg_initialize()

**Signature:**

```c
void kreuzberg_initialize();
```

##### kreuzberg_shutdown()

**Signature:**

```c
void kreuzberg_shutdown();
```

##### kreuzberg_description()

**Signature:**

```c
const char* kreuzberg_description();
```

##### kreuzberg_author()

**Signature:**

```c
const char* kreuzberg_author();
```

##### kreuzberg_extract_bytes()

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_extract_bytes(const uint8_t* content, const char* mime_type, KreuzbergExtractionConfig config);
```

##### kreuzberg_extract_file()

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_extract_file(const char* path, const char* mime_type, KreuzbergExtractionConfig config);
```

##### kreuzberg_supported_mime_types()

**Signature:**

```c
const char** kreuzberg_supported_mime_types();
```

##### kreuzberg_priority()

**Signature:**

```c
int32_t kreuzberg_priority();
```


---

### KreuzbergDjotImage

Image element in Djot.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `src` | `const char*` | — | Image source URL or path |
| `alt` | `const char*` | — | Alternative text |
| `title` | `const char**` | `NULL` | Optional title |
| `attributes` | `KreuzbergAttributes*` | `NULL` | Element attributes |


---

### KreuzbergDjotLink

Link element in Djot.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `url` | `const char*` | — | Link URL |
| `text` | `const char*` | — | Link text content |
| `title` | `const char**` | `NULL` | Optional title |
| `attributes` | `KreuzbergAttributes*` | `NULL` | Element attributes |


---

### KreuzbergDocExtractionResult

Result of DOC text extraction.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `text` | `const char*` | — | Extracted text content. |
| `metadata` | `KreuzbergDocMetadata` | — | Document metadata. |


---

### KreuzbergDocExtractor

Native DOC extractor using OLE/CFB parsing.

This extractor handles Word 97-2003 binary (.doc) files without
requiring LibreOffice, providing ~50x faster extraction.

#### Methods

##### kreuzberg_default()

**Signature:**

```c
KreuzbergDocExtractor kreuzberg_default();
```

##### kreuzberg_name()

**Signature:**

```c
const char* kreuzberg_name();
```

##### kreuzberg_version()

**Signature:**

```c
const char* kreuzberg_version();
```

##### kreuzberg_initialize()

**Signature:**

```c
void kreuzberg_initialize();
```

##### kreuzberg_shutdown()

**Signature:**

```c
void kreuzberg_shutdown();
```

##### kreuzberg_description()

**Signature:**

```c
const char* kreuzberg_description();
```

##### kreuzberg_author()

**Signature:**

```c
const char* kreuzberg_author();
```

##### kreuzberg_extract_bytes()

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_extract_bytes(const uint8_t* content, const char* mime_type, KreuzbergExtractionConfig config);
```

##### kreuzberg_supported_mime_types()

**Signature:**

```c
const char** kreuzberg_supported_mime_types();
```

##### kreuzberg_priority()

**Signature:**

```c
int32_t kreuzberg_priority();
```


---

### KreuzbergDocMetadata

Metadata extracted from DOC files.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `title` | `const char**` | `NULL` | Title |
| `subject` | `const char**` | `NULL` | Subject |
| `author` | `const char**` | `NULL` | Author |
| `last_author` | `const char**` | `NULL` | Last author |
| `created` | `const char**` | `NULL` | Created |
| `modified` | `const char**` | `NULL` | Modified |
| `revision_number` | `const char**` | `NULL` | Revision number |


---

### KreuzbergDocOrientationDetector

Detects document page orientation using the PP-LCNet model.

Thread-safe: uses unsafe pointer cast for ONNX session (same pattern as embedding engine).
The model is downloaded from HuggingFace on first use and cached locally.

#### Methods

##### kreuzberg_detect()

Detect document page orientation.

Returns the detected orientation (0°, 90°, 180°, 270°) and confidence.
Thread-safe: can be called concurrently from multiple pages.

**Signature:**

```c
KreuzbergOrientationResult kreuzberg_detect(KreuzbergRgbImage image);
```


---

### KreuzbergDocProperties

Document properties from `<wp:docPr>`.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `id` | `const char**` | `NULL` | Unique identifier |
| `name` | `const char**` | `NULL` | The name |
| `description` | `const char**` | `NULL` | Human-readable description |


---

### KreuzbergDocbookExtractor

DocBook document extractor.

Supports both DocBook 4.x (no namespace) and 5.x (with namespace) formats.

#### Methods

##### kreuzberg_default()

**Signature:**

```c
KreuzbergDocbookExtractor kreuzberg_default();
```

##### kreuzberg_name()

**Signature:**

```c
const char* kreuzberg_name();
```

##### kreuzberg_version()

**Signature:**

```c
const char* kreuzberg_version();
```

##### kreuzberg_initialize()

**Signature:**

```c
void kreuzberg_initialize();
```

##### kreuzberg_shutdown()

**Signature:**

```c
void kreuzberg_shutdown();
```

##### kreuzberg_extract_bytes()

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_extract_bytes(const uint8_t* content, const char* mime_type, KreuzbergExtractionConfig config);
```

##### kreuzberg_supported_mime_types()

**Signature:**

```c
const char** kreuzberg_supported_mime_types();
```

##### kreuzberg_priority()

**Signature:**

```c
int32_t kreuzberg_priority();
```


---

### KreuzbergDocument

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `paragraphs` | `KreuzbergParagraph*` | `NULL` | Paragraphs |
| `tables` | `KreuzbergTable*` | `NULL` | Tables extracted from the document |
| `headers` | `KreuzbergHeaderFooter*` | `NULL` | Headers |
| `footers` | `KreuzbergHeaderFooter*` | `NULL` | Footers |
| `footnotes` | `KreuzbergNote*` | `NULL` | Footnotes |
| `endnotes` | `KreuzbergNote*` | `NULL` | Endnotes |
| `numbering_defs` | `KreuzbergAHashMap` | `NULL` | Numbering defs (a hash map) |
| `elements` | `KreuzbergDocumentElement*` | `NULL` | Document elements in their original order. |
| `style_catalog` | `KreuzbergStyleCatalog*` | `NULL` | Parsed style catalog from `word/styles.xml`, if available. |
| `theme` | `KreuzbergTheme*` | `NULL` | Parsed theme from `word/theme/theme1.xml`, if available. |
| `sections` | `KreuzbergSectionProperties*` | `NULL` | Section properties parsed from `w:sectPr` elements. |
| `drawings` | `KreuzbergDrawing*` | `NULL` | Drawing objects parsed from `w:drawing` elements. |
| `image_relationships` | `KreuzbergAHashMap` | `NULL` | Image relationships (rId → target path) for image extraction. |

#### Methods

##### kreuzberg_resolve_heading_level()

Resolve heading level for a paragraph style using the StyleCatalog.

Walks the style inheritance chain to find `outline_level`.
Falls back to string-matching on style name/ID if no StyleCatalog is available.
Returns 1-6 (markdown heading levels).

**Signature:**

```c
uint8_t* kreuzberg_resolve_heading_level(const char* style_id);
```

##### kreuzberg_extract_text()

**Signature:**

```c
const char* kreuzberg_extract_text();
```

##### kreuzberg_to_markdown()

Render the document as markdown.

When `inject_placeholders` is `true`, drawings that reference an image
emit `![alt](image)` placeholders. When `false` they are silently
skipped, which is useful when the caller only wants text.

**Signature:**

```c
const char* kreuzberg_to_markdown(bool inject_placeholders);
```

##### kreuzberg_to_plain_text()

Render the document as plain text (no markdown formatting).

**Signature:**

```c
const char* kreuzberg_to_plain_text();
```


---

### KreuzbergDocumentNode

A single node in the document tree.

Each node has deterministic `id`, typed `content`, optional `parent`/`children`
for tree structure, and metadata like page number, bounding box, and content layer.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `id` | `KreuzbergNodeId` | — | Deterministic identifier (hash of content + position). |
| `content` | `KreuzbergNodeContent` | — | Node content — tagged enum, type-specific data only. |
| `parent` | `uint32_t*` | `NULL` | Parent node index (`None` = root-level node). |
| `children` | `uint32_t*` | — | Child node indices in reading order. |
| `content_layer` | `KreuzbergContentLayer` | — | Content layer classification. |
| `page` | `uint32_t*` | `NULL` | Page number where this node starts (1-indexed). |
| `page_end` | `uint32_t*` | `NULL` | Page number where this node ends (for multi-page tables/sections). |
| `bbox` | `KreuzbergBoundingBox*` | `NULL` | Bounding box in document coordinates. |
| `annotations` | `KreuzbergTextAnnotation*` | — | Inline annotations (formatting, links) on this node's text content. Only meaningful for text-carrying nodes; empty for containers. |
| `attributes` | `void**` | `NULL` | Format-specific key-value attributes. Extensible bag for data that doesn't warrant a typed field: CSS classes, LaTeX environment names, Excel cell formulas, slide layout names, etc. |


---

### KreuzbergDocumentRelationship

A resolved relationship between two nodes in the document tree.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `source` | `uint32_t` | — | Source node index (the referencing node). |
| `target` | `uint32_t` | — | Target node index (the referenced node). |
| `kind` | `KreuzbergRelationshipKind` | — | Semantic kind of the relationship. |


---

### KreuzbergDocumentStructure

Top-level structured document representation.

A flat array of nodes with index-based parent/child references forming a tree.
Root-level nodes have `parent: None`. Use `body_roots()` and `furniture_roots()`
to iterate over top-level content by layer.

# Validation

Call `validate()` after construction to verify all node indices are in bounds
and parent-child relationships are bidirectionally consistent.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `nodes` | `KreuzbergDocumentNode*` | `NULL` | All nodes in document/reading order. |
| `source_format` | `const char**` | `NULL` | Origin format identifier (e.g. "docx", "pptx", "html", "pdf"). Allows renderers to apply format-aware heuristics when converting the document tree to output formats. |
| `relationships` | `KreuzbergDocumentRelationship*` | `NULL` | Resolved relationships between nodes (footnote refs, citations, anchor links, etc.). Populated during derivation from the internal document representation. Empty when no relationships are detected. |

#### Methods

##### kreuzberg_with_capacity()

Create a `DocumentStructure` with pre-allocated capacity.

**Signature:**

```c
KreuzbergDocumentStructure kreuzberg_with_capacity(uintptr_t capacity);
```

##### kreuzberg_push_node()

Push a node and return its `NodeIndex`.

**Signature:**

```c
uint32_t kreuzberg_push_node(KreuzbergDocumentNode node);
```

##### kreuzberg_add_child()

Add a child to an existing parent node.

Updates both the parent's `children` list and the child's `parent` field.

**Panics:**

Panics if either index is out of bounds.

**Signature:**

```c
void kreuzberg_add_child(uint32_t parent, uint32_t child);
```

##### kreuzberg_validate()

Validate all node indices are in bounds and parent-child relationships
are bidirectionally consistent.

**Errors:**

Returns a descriptive error string if validation fails.

**Signature:**

```c
void kreuzberg_validate();
```

##### kreuzberg_body_roots()

Iterate over root-level body nodes (content_layer == Body, parent == None).

**Signature:**

```c
KreuzbergIterator kreuzberg_body_roots();
```

##### kreuzberg_furniture_roots()

Iterate over root-level furniture nodes (non-Body content_layer, parent == None).

**Signature:**

```c
KreuzbergIterator kreuzberg_furniture_roots();
```

##### kreuzberg_get()

Get a node by index.

**Signature:**

```c
KreuzbergDocumentNode* kreuzberg_get(uint32_t index);
```

##### kreuzberg_len()

Get the total number of nodes.

**Signature:**

```c
uintptr_t kreuzberg_len();
```

##### kreuzberg_is_empty()

Check if the document structure is empty.

**Signature:**

```c
bool kreuzberg_is_empty();
```

##### kreuzberg_default()

**Signature:**

```c
KreuzbergDocumentStructure kreuzberg_default();
```


---

### KreuzbergDocumentStructureBuilder

Builder for constructing `DocumentStructure` trees with automatic
heading-driven section nesting.

The builder maintains an internal section stack: when you push a heading,
it automatically creates a `Group` container and nests subsequent content
under it. Higher-level headings pop deeper sections off the stack.

#### Methods

##### kreuzberg_with_capacity()

Create a builder with pre-allocated node capacity.

**Signature:**

```c
KreuzbergDocumentStructureBuilder kreuzberg_with_capacity(uintptr_t capacity);
```

##### kreuzberg_source_format()

Set the source format identifier (e.g. "docx", "html", "pptx").

**Signature:**

```c
KreuzbergDocumentStructureBuilder kreuzberg_source_format(const char* format);
```

##### kreuzberg_build()

Consume the builder and return the constructed `DocumentStructure`.

**Signature:**

```c
KreuzbergDocumentStructure kreuzberg_build();
```

##### kreuzberg_push_heading()

Push a heading, creating a `Group` container with automatic section nesting.

Headings at the same or deeper level pop existing sections. Content
pushed after this heading will be nested under its `Group` node.

Returns the `NodeIndex` of the `Group` node (not the heading child).

**Signature:**

```c
uint32_t kreuzberg_push_heading(uint8_t level, const char* text, uint32_t page, KreuzbergBoundingBox bbox);
```

##### kreuzberg_push_paragraph()

Push a paragraph node. Nested under current section if one exists.

**Signature:**

```c
uint32_t kreuzberg_push_paragraph(const char* text, KreuzbergTextAnnotation* annotations, uint32_t page, KreuzbergBoundingBox bbox);
```

##### kreuzberg_push_list()

Push a list container. Returns the `NodeIndex` to use with `push_list_item`.

**Signature:**

```c
uint32_t kreuzberg_push_list(bool ordered, uint32_t page);
```

##### kreuzberg_push_list_item()

Push a list item as a child of the given list node.

**Signature:**

```c
uint32_t kreuzberg_push_list_item(uint32_t list, const char* text, uint32_t page);
```

##### kreuzberg_push_table()

Push a table node with a structured grid.

**Signature:**

```c
uint32_t kreuzberg_push_table(KreuzbergTableGrid grid, uint32_t page, KreuzbergBoundingBox bbox);
```

##### kreuzberg_push_table_from_cells()

Push a table from a simple cell grid (`Vec<Vec<String>>`).

Assumes the first row is the header row.

**Signature:**

```c
uint32_t kreuzberg_push_table_from_cells(const char*** cells, uint32_t page);
```

##### kreuzberg_push_code()

Push a code block.

**Signature:**

```c
uint32_t kreuzberg_push_code(const char* text, const char* language, uint32_t page);
```

##### kreuzberg_push_formula()

Push a math formula node.

**Signature:**

```c
uint32_t kreuzberg_push_formula(const char* text, uint32_t page);
```

##### kreuzberg_push_image()

Push an image reference node.

**Signature:**

```c
uint32_t kreuzberg_push_image(const char* description, uint32_t image_index, uint32_t page, KreuzbergBoundingBox bbox);
```

##### kreuzberg_push_image_with_src()

Push an image node with source URL.

**Signature:**

```c
uint32_t kreuzberg_push_image_with_src(const char* description, const char* src, uint32_t image_index, uint32_t page, KreuzbergBoundingBox bbox);
```

##### kreuzberg_push_quote()

Push a block quote container and enter it.

Subsequent body nodes will be parented under this quote until
`exit_container` is called.

**Signature:**

```c
uint32_t kreuzberg_push_quote(uint32_t page);
```

##### kreuzberg_push_footnote()

Push a footnote node.

**Signature:**

```c
uint32_t kreuzberg_push_footnote(const char* text, uint32_t page);
```

##### kreuzberg_push_page_break()

Push a page break marker (always root-level, never nested under sections).

**Signature:**

```c
uint32_t kreuzberg_push_page_break(uint32_t page);
```

##### kreuzberg_push_slide()

Push a slide container (PPTX) and enter it.

Clears the section stack and container stack so the slide starts
fresh. Subsequent body nodes will be parented under this slide
until `exit_container` is called or a new
slide is pushed.

**Signature:**

```c
uint32_t kreuzberg_push_slide(uint32_t number, const char* title);
```

##### kreuzberg_push_definition_list()

Push a definition list container. Use `push_definition_item` for entries.

**Signature:**

```c
uint32_t kreuzberg_push_definition_list(uint32_t page);
```

##### kreuzberg_push_definition_item()

Push a definition item as a child of the given definition list.

**Signature:**

```c
uint32_t kreuzberg_push_definition_item(uint32_t list, const char* term, const char* definition, uint32_t page);
```

##### kreuzberg_push_citation()

Push a citation / bibliographic reference.

**Signature:**

```c
uint32_t kreuzberg_push_citation(const char* key, const char* text, uint32_t page);
```

##### kreuzberg_push_admonition()

Push an admonition container (note, warning, tip, etc.) and enter it.

Subsequent body nodes will be parented under this admonition until
`exit_container` is called.

**Signature:**

```c
uint32_t kreuzberg_push_admonition(const char* kind, const char* title, uint32_t page);
```

##### kreuzberg_push_raw_block()

Push a raw block preserved verbatim from the source format.

**Signature:**

```c
uint32_t kreuzberg_push_raw_block(const char* format, const char* content, uint32_t page);
```

##### kreuzberg_push_metadata_block()

Push a metadata block (email headers, frontmatter key-value pairs).

**Signature:**

```c
uint32_t kreuzberg_push_metadata_block(KreuzbergStringString* entries, uint32_t page);
```

##### kreuzberg_push_header()

Push a header paragraph (running page header).

**Signature:**

```c
uint32_t kreuzberg_push_header(const char* text, uint32_t page);
```

##### kreuzberg_push_footer()

Push a footer paragraph (running page footer).

**Signature:**

```c
uint32_t kreuzberg_push_footer(const char* text, uint32_t page);
```

##### kreuzberg_set_attributes()

Set format-specific attributes on an existing node.

**Signature:**

```c
void kreuzberg_set_attributes(uint32_t index, KreuzbergAHashMap attrs);
```

##### kreuzberg_add_child()

Add a child node to an existing parent (for container nodes like Quote, Slide, Admonition).

**Signature:**

```c
void kreuzberg_add_child(uint32_t parent, uint32_t child);
```

##### kreuzberg_push_raw()

Push a raw `NodeContent` with full control over content layer and annotations.
Nests under current section unless the content type is a root-level type.

**Signature:**

```c
uint32_t kreuzberg_push_raw(KreuzbergNodeContent content, uint32_t page, KreuzbergBoundingBox bbox, KreuzbergContentLayer layer, KreuzbergTextAnnotation* annotations);
```

##### kreuzberg_clear_sections()

Reset the section stack (e.g. when starting a new page).

**Signature:**

```c
void kreuzberg_clear_sections();
```

##### kreuzberg_enter_container()

Manually push a node onto the container stack.

Subsequent body nodes will be parented under this container
until `exit_container` is called.

**Signature:**

```c
void kreuzberg_enter_container(uint32_t container);
```

##### kreuzberg_exit_container()

Pop the most recent container from the container stack.

Body nodes will resume parenting under the next container on the
stack, or under the section stack if the container stack is empty.

**Signature:**

```c
void kreuzberg_exit_container();
```

##### kreuzberg_default()

**Signature:**

```c
KreuzbergDocumentStructureBuilder kreuzberg_default();
```


---

### KreuzbergDocxAppProperties

Application properties from docProps/app.xml for DOCX

Contains Word-specific document statistics and metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `application` | `const char**` | `NULL` | Application name (e.g., "Microsoft Office Word") |
| `app_version` | `const char**` | `NULL` | Application version |
| `template` | `const char**` | `NULL` | Template filename |
| `total_time` | `int32_t*` | `NULL` | Total editing time in minutes |
| `pages` | `int32_t*` | `NULL` | Number of pages |
| `words` | `int32_t*` | `NULL` | Number of words |
| `characters` | `int32_t*` | `NULL` | Number of characters (excluding spaces) |
| `characters_with_spaces` | `int32_t*` | `NULL` | Number of characters (including spaces) |
| `lines` | `int32_t*` | `NULL` | Number of lines |
| `paragraphs` | `int32_t*` | `NULL` | Number of paragraphs |
| `company` | `const char**` | `NULL` | Company name |
| `doc_security` | `int32_t*` | `NULL` | Document security level |
| `scale_crop` | `bool*` | `NULL` | Scale crop flag |
| `links_up_to_date` | `bool*` | `NULL` | Links up to date flag |
| `shared_doc` | `bool*` | `NULL` | Shared document flag |
| `hyperlinks_changed` | `bool*` | `NULL` | Hyperlinks changed flag |


---

### KreuzbergDocxExtractor

High-performance DOCX extractor.

This extractor provides:
- Fast text extraction via streaming XML parsing
- Comprehensive metadata extraction (core.xml, app.xml, custom.xml)

#### Methods

##### kreuzberg_default()

**Signature:**

```c
KreuzbergDocxExtractor kreuzberg_default();
```

##### kreuzberg_name()

**Signature:**

```c
const char* kreuzberg_name();
```

##### kreuzberg_version()

**Signature:**

```c
const char* kreuzberg_version();
```

##### kreuzberg_initialize()

**Signature:**

```c
void kreuzberg_initialize();
```

##### kreuzberg_shutdown()

**Signature:**

```c
void kreuzberg_shutdown();
```

##### kreuzberg_description()

**Signature:**

```c
const char* kreuzberg_description();
```

##### kreuzberg_author()

**Signature:**

```c
const char* kreuzberg_author();
```

##### kreuzberg_extract_bytes()

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_extract_bytes(const uint8_t* content, const char* mime_type, KreuzbergExtractionConfig config);
```

##### kreuzberg_supported_mime_types()

**Signature:**

```c
const char** kreuzberg_supported_mime_types();
```

##### kreuzberg_priority()

**Signature:**

```c
int32_t kreuzberg_priority();
```


---

### KreuzbergDocxMetadata

Word document metadata.

Extracted from DOCX files using shared Office Open XML metadata extraction.
Integrates with `office_metadata` module for core/app/custom properties.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `core_properties` | `KreuzbergCoreProperties*` | `NULL` | Core properties from docProps/core.xml (Dublin Core metadata) Contains title, creator, subject, keywords, dates, etc. Shared format across DOCX/PPTX/XLSX documents. |
| `app_properties` | `KreuzbergDocxAppProperties*` | `NULL` | Application properties from docProps/app.xml (Word-specific statistics) Contains word count, page count, paragraph count, editing time, etc. DOCX-specific variant of Office application properties. |
| `custom_properties` | `void**` | `NULL` | Custom properties from docProps/custom.xml (user-defined properties) Contains key-value pairs defined by users or applications. Values can be strings, numbers, booleans, or dates. |


---

### KreuzbergDrawing

A drawing object extracted from `<w:drawing>`.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `drawing_type` | `KreuzbergDrawingType` | — | Drawing type (drawing type) |
| `extent` | `KreuzbergExtent*` | `NULL` | Extent (extent) |
| `doc_properties` | `KreuzbergDocProperties*` | `NULL` | Doc properties (doc properties) |
| `image_ref` | `const char**` | `NULL` | Image ref |


---

### KreuzbergElement

Semantic element extracted from document.

Represents a logical unit of content with semantic classification,
unique identifier, and metadata for tracking origin and position.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `element_id` | `KreuzbergElementId` | — | Unique element identifier |
| `element_type` | `KreuzbergElementType` | — | Semantic type of this element |
| `text` | `const char*` | — | Text content of the element |
| `metadata` | `KreuzbergElementMetadata` | — | Metadata about the element |


---

### KreuzbergElementId

Unique identifier for semantic elements.

Wraps a string identifier that is deterministically generated
from element type, content, and page number.

#### Methods

##### kreuzberg_new()

Create a new ElementId from a string.

**Errors:**

Returns error if the string is not valid.

**Signature:**

```c
KreuzbergElementId kreuzberg_new(const char* hex_str);
```

##### kreuzberg_as_ref()

**Signature:**

```c
const char* kreuzberg_as_ref();
```

##### kreuzberg_fmt()

**Signature:**

```c
KreuzbergUnknown kreuzberg_fmt(KreuzbergFormatter f);
```


---

### KreuzbergElementMetadata

Metadata for a semantic element.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `page_number` | `uintptr_t*` | `NULL` | Page number (1-indexed) |
| `filename` | `const char**` | `NULL` | Source filename or document name |
| `coordinates` | `KreuzbergBoundingBox*` | `NULL` | Bounding box coordinates if available |
| `element_index` | `uintptr_t*` | `NULL` | Position index in the element sequence |
| `additional` | `void*` | — | Additional custom metadata |


---

### KreuzbergEmailAttachment

Email attachment representation.

Contains metadata and optionally the content of an email attachment.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `name` | `const char**` | `NULL` | Attachment name (from Content-Disposition header) |
| `filename` | `const char**` | `NULL` | Filename of the attachment |
| `mime_type` | `const char**` | `NULL` | MIME type of the attachment |
| `size` | `uintptr_t*` | `NULL` | Size in bytes |
| `is_image` | `bool` | — | Whether this attachment is an image |
| `data` | `const uint8_t**` | `NULL` | Attachment data (if extracted). Uses `bytes.Bytes` for cheap cloning of large buffers. |


---

### KreuzbergEmailConfig

Configuration for email extraction.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `msg_fallback_codepage` | `uint32_t*` | `NULL` | Windows codepage number to use when an MSG file contains no codepage property. Defaults to `None`, which falls back to windows-1252. If an unrecognized or invalid codepage number is supplied (including 0), the behavior silently falls back to windows-1252 — the same as when the MSG file itself contains an unrecognized codepage. No error or warning is emitted. Users should verify output when supplying unusual values. Common values: - 1250: Central European (Polish, Czech, Hungarian, etc.) - 1251: Cyrillic (Russian, Ukrainian, Bulgarian, etc.) - 1252: Western European (default) - 1253: Greek - 1254: Turkish - 1255: Hebrew - 1256: Arabic - 932:  Japanese (Shift-JIS) - 936:  Simplified Chinese (GBK) |


---

### KreuzbergEmailExtractionResult

Email extraction result.

Complete representation of an extracted email message (.eml or .msg)
including headers, body content, and attachments.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `subject` | `const char**` | `NULL` | Email subject line |
| `from_email` | `const char**` | `NULL` | Sender email address |
| `to_emails` | `const char**` | — | Primary recipient email addresses |
| `cc_emails` | `const char**` | — | CC recipient email addresses |
| `bcc_emails` | `const char**` | — | BCC recipient email addresses |
| `date` | `const char**` | `NULL` | Email date/timestamp |
| `message_id` | `const char**` | `NULL` | Message-ID header value |
| `plain_text` | `const char**` | `NULL` | Plain text version of the email body |
| `html_content` | `const char**` | `NULL` | HTML version of the email body |
| `cleaned_text` | `const char*` | — | Cleaned/processed text content |
| `attachments` | `KreuzbergEmailAttachment*` | — | List of email attachments |
| `metadata` | `void*` | — | Additional email headers and metadata |


---

### KreuzbergEmailExtractor

Email message extractor.

Supports: .eml, .msg

#### Methods

##### kreuzberg_default()

**Signature:**

```c
KreuzbergEmailExtractor kreuzberg_default();
```

##### kreuzberg_name()

**Signature:**

```c
const char* kreuzberg_name();
```

##### kreuzberg_version()

**Signature:**

```c
const char* kreuzberg_version();
```

##### kreuzberg_initialize()

**Signature:**

```c
void kreuzberg_initialize();
```

##### kreuzberg_shutdown()

**Signature:**

```c
void kreuzberg_shutdown();
```

##### kreuzberg_extract_sync()

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_extract_sync(const uint8_t* content, const char* mime_type, KreuzbergExtractionConfig config);
```

##### kreuzberg_extract_bytes()

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_extract_bytes(const uint8_t* content, const char* mime_type, KreuzbergExtractionConfig config);
```

##### kreuzberg_supported_mime_types()

**Signature:**

```c
const char** kreuzberg_supported_mime_types();
```

##### kreuzberg_priority()

**Signature:**

```c
int32_t kreuzberg_priority();
```

##### kreuzberg_as_sync_extractor()

**Signature:**

```c
KreuzbergSyncExtractor* kreuzberg_as_sync_extractor();
```


---

### KreuzbergEmailMetadata

Email metadata extracted from .eml and .msg files.

Includes sender/recipient information, message ID, and attachment list.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `from_email` | `const char**` | `NULL` | Sender's email address |
| `from_name` | `const char**` | `NULL` | Sender's display name |
| `to_emails` | `const char**` | — | Primary recipients |
| `cc_emails` | `const char**` | — | CC recipients |
| `bcc_emails` | `const char**` | — | BCC recipients |
| `message_id` | `const char**` | `NULL` | Message-ID header value |
| `attachments` | `const char**` | — | List of attachment filenames |


---

### KreuzbergEmbeddedFile

Embedded file descriptor extracted from the PDF name tree.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `name` | `const char*` | — | The filename as stored in the PDF name tree. |
| `data` | `const uint8_t*` | — | Raw file bytes from the embedded stream. |
| `mime_type` | `const char**` | `NULL` | MIME type if specified in the filespec, otherwise `None`. |


---

### KreuzbergEmbeddingConfig

Embedding configuration for text chunks.

Configures embedding generation using ONNX models via the vendored embedding engine.
Requires the `embeddings` feature to be enabled.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `model` | `KreuzbergEmbeddingModelType` | `KREUZBERG_KREUZBERG_PRESET` | The embedding model to use (defaults to "balanced" preset if not specified) |
| `normalize` | `bool` | `true` | Whether to normalize embedding vectors (recommended for cosine similarity) |
| `batch_size` | `uintptr_t` | `32` | Batch size for embedding generation |
| `show_download_progress` | `bool` | `false` | Show model download progress |
| `cache_dir` | `const char**` | `NULL` | Custom cache directory for model files Defaults to `~/.cache/kreuzberg/embeddings/` if not specified. Allows full customization of model download location. |

#### Methods

##### kreuzberg_default()

**Signature:**

```c
KreuzbergEmbeddingConfig kreuzberg_default();
```


---

### KreuzbergEmbeddingEngine

Text embedding model with thread-safe inference.

The `embed()` method takes `&self` instead of `&mut self`, allowing it to
be shared across threads via `Arc<EmbeddingEngine>` without mutex contention.


---

### KreuzbergEmbeddingPreset

Preset configurations for common RAG use cases.

Each preset combines chunk size, overlap, and embedding model
to provide an optimized configuration for specific scenarios.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `name` | `const char*` | — | The name |
| `chunk_size` | `uintptr_t` | — | Chunk size |
| `overlap` | `uintptr_t` | — | Overlap |
| `model_repo` | `const char*` | — | HuggingFace repository name for the model. |
| `pooling` | `const char*` | — | Pooling strategy: "cls" or "mean". |
| `model_file` | `const char*` | — | Path to the ONNX model file within the repo. |
| `dimensions` | `uintptr_t` | — | Dimensions |
| `description` | `const char*` | — | Human-readable description |


---

### KreuzbergEntityValidator

Helper struct for validating entity/string length.

#### Methods

##### kreuzberg_validate()

Validate entity length.

**Returns:**
* `Ok(())` if length is within limits
* `Err(SecurityError)` if length exceeds limit

**Signature:**

```c
void kreuzberg_validate(const char* content);
```


---

### KreuzbergEpubExtractor

EPUB format extractor using permissive-licensed dependencies.

Extracts content and metadata from EPUB files (both EPUB2 and EPUB3)
using native Rust parsing without GPL-licensed dependencies.

#### Methods

##### kreuzberg_default()

**Signature:**

```c
KreuzbergEpubExtractor kreuzberg_default();
```

##### kreuzberg_name()

**Signature:**

```c
const char* kreuzberg_name();
```

##### kreuzberg_version()

**Signature:**

```c
const char* kreuzberg_version();
```

##### kreuzberg_initialize()

**Signature:**

```c
void kreuzberg_initialize();
```

##### kreuzberg_shutdown()

**Signature:**

```c
void kreuzberg_shutdown();
```

##### kreuzberg_description()

**Signature:**

```c
const char* kreuzberg_description();
```

##### kreuzberg_author()

**Signature:**

```c
const char* kreuzberg_author();
```

##### kreuzberg_extract_bytes()

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_extract_bytes(const uint8_t* content, const char* mime_type, KreuzbergExtractionConfig config);
```

##### kreuzberg_supported_mime_types()

**Signature:**

```c
const char** kreuzberg_supported_mime_types();
```

##### kreuzberg_priority()

**Signature:**

```c
int32_t kreuzberg_priority();
```


---

### KreuzbergEpubMetadata

EPUB metadata (Dublin Core extensions).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `coverage` | `const char**` | `NULL` | Coverage |
| `dc_format` | `const char**` | `NULL` | Dc format |
| `relation` | `const char**` | `NULL` | Relation |
| `source` | `const char**` | `NULL` | Source |
| `dc_type` | `const char**` | `NULL` | Dc type |
| `cover_image` | `const char**` | `NULL` | Cover image |


---

### KreuzbergErrorMetadata

Error metadata (for batch operations).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `error_type` | `const char*` | — | Error type |
| `message` | `const char*` | — | Message |


---

### KreuzbergExcelExtractor

Excel spreadsheet extractor using calamine.

Supports: .xlsx, .xlsm, .xlam, .xltm, .xls, .xla, .xlsb, .ods

# Limitations

- **Hyperlinks**: calamine (v0.34) does not expose cell hyperlink data in its
  public API. Excel files may contain hyperlinks via the `HYPERLINK()` formula
  or via the relationships XML, but neither is accessible through the crate.
  This would require either a calamine upstream change or manual OOXML parsing.

#### Methods

##### kreuzberg_default()

**Signature:**

```c
KreuzbergExcelExtractor kreuzberg_default();
```

##### kreuzberg_name()

**Signature:**

```c
const char* kreuzberg_name();
```

##### kreuzberg_version()

**Signature:**

```c
const char* kreuzberg_version();
```

##### kreuzberg_initialize()

**Signature:**

```c
void kreuzberg_initialize();
```

##### kreuzberg_shutdown()

**Signature:**

```c
void kreuzberg_shutdown();
```

##### kreuzberg_extract_sync()

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_extract_sync(const uint8_t* content, const char* mime_type, KreuzbergExtractionConfig config);
```

##### kreuzberg_extract_bytes()

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_extract_bytes(const uint8_t* content, const char* mime_type, KreuzbergExtractionConfig config);
```

##### kreuzberg_extract_file()

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_extract_file(const char* path, const char* mime_type, KreuzbergExtractionConfig config);
```

##### kreuzberg_supported_mime_types()

**Signature:**

```c
const char** kreuzberg_supported_mime_types();
```

##### kreuzberg_priority()

**Signature:**

```c
int32_t kreuzberg_priority();
```

##### kreuzberg_as_sync_extractor()

**Signature:**

```c
KreuzbergSyncExtractor* kreuzberg_as_sync_extractor();
```


---

### KreuzbergExcelMetadata

Excel/spreadsheet metadata.

Contains information about sheets in Excel, OpenDocument Calc, and other
spreadsheet formats (.xlsx, .xls, .ods, etc.).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `sheet_count` | `uintptr_t` | — | Total number of sheets in the workbook |
| `sheet_names` | `const char**` | — | Names of all sheets in order |


---

### KreuzbergExcelSheet

Single Excel worksheet.

Represents one sheet from an Excel workbook with its content
converted to Markdown format and dimensional statistics.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `name` | `const char*` | — | Sheet name as it appears in Excel |
| `markdown` | `const char*` | — | Sheet content converted to Markdown tables |
| `row_count` | `uintptr_t` | — | Number of rows |
| `col_count` | `uintptr_t` | — | Number of columns |
| `cell_count` | `uintptr_t` | — | Total number of non-empty cells |
| `table_cells` | `const char****` | `NULL` | Pre-extracted table cells (2D vector of cell values) Populated during markdown generation to avoid re-parsing markdown. None for empty sheets. |


---

### KreuzbergExcelWorkbook

Excel workbook representation.

Contains all sheets from an Excel file (.xlsx, .xls, etc.) with
extracted content and metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `sheets` | `KreuzbergExcelSheet*` | — | All sheets in the workbook |
| `metadata` | `void*` | — | Workbook-level metadata (author, creation date, etc.) |


---

### KreuzbergExtent

Size in EMUs (English Metric Units, 1 inch = 914400 EMU).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `cx` | `int64_t` | `NULL` | Cx |
| `cy` | `int64_t` | `NULL` | Cy |

#### Methods

##### kreuzberg_width_inches()

Convert width to inches.

**Signature:**

```c
double kreuzberg_width_inches();
```

##### kreuzberg_height_inches()

Convert height to inches.

**Signature:**

```c
double kreuzberg_height_inches();
```


---

### KreuzbergExtractedImage

Extracted image from a document.

Contains raw image data, metadata, and optional nested OCR results.
Raw bytes allow cross-language compatibility - users can convert to
PIL.Image (Python), Sharp (Node.js), or other formats as needed.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `data` | `const uint8_t*` | — | Raw image data (PNG, JPEG, WebP, etc. bytes). Uses `bytes.Bytes` for cheap cloning of large buffers. |
| `format` | `KreuzbergStr` | — | Image format (e.g., "jpeg", "png", "webp") Uses Cow<'static, str> to avoid allocation for static literals. |
| `image_index` | `uintptr_t` | — | Zero-indexed position of this image in the document/page |
| `page_number` | `uintptr_t*` | `NULL` | Page/slide number where image was found (1-indexed) |
| `width` | `uint32_t*` | `NULL` | Image width in pixels |
| `height` | `uint32_t*` | `NULL` | Image height in pixels |
| `colorspace` | `const char**` | `NULL` | Colorspace information (e.g., "RGB", "CMYK", "Gray") |
| `bits_per_component` | `uint32_t*` | `NULL` | Bits per color component (e.g., 8, 16) |
| `is_mask` | `bool` | — | Whether this image is a mask image |
| `description` | `const char**` | `NULL` | Optional description of the image |
| `ocr_result` | `KreuzbergExtractionResult*` | `NULL` | Nested OCR extraction result (if image was OCRed) When OCR is performed on this image, the result is embedded here rather than in a separate collection, making the relationship explicit. |
| `bounding_box` | `KreuzbergBoundingBox*` | `NULL` | Bounding box of the image on the page (PDF coordinates: x0=left, y0=bottom, x1=right, y1=top). Only populated for PDF-extracted images when position data is available from pdfium. |
| `source_path` | `const char**` | `NULL` | Original source path of the image within the document archive (e.g., "media/image1.png" in DOCX). Used for rendering image references when the binary data is not extracted. |


---

### KreuzbergExtractionConfig

Main extraction configuration.

This struct contains all configuration options for the extraction process.
It can be loaded from TOML, YAML, or JSON files, or created programmatically.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `use_cache` | `bool` | `true` | Enable caching of extraction results |
| `enable_quality_processing` | `bool` | `true` | Enable quality post-processing |
| `ocr` | `KreuzbergOcrConfig*` | `NULL` | OCR configuration (None = OCR disabled) |
| `force_ocr` | `bool` | `false` | Force OCR even for searchable PDFs |
| `force_ocr_pages` | `uintptr_t**` | `NULL` | Force OCR on specific pages only (1-indexed page numbers, must be >= 1). When set, only the listed pages are OCR'd regardless of text layer quality. Unlisted pages use native text extraction. Ignored when `force_ocr` is `True`. Only applies to PDF documents. Duplicates are automatically deduplicated. An `ocr` config is recommended for backend/language selection; defaults are used if absent. |
| `disable_ocr` | `bool` | `false` | Disable OCR entirely, even for images. When `True`, OCR is skipped for all document types. Images return metadata only (dimensions, format, EXIF) without text extraction. PDFs use only native text extraction without OCR fallback. Cannot be `True` simultaneously with `force_ocr`. *Added in v4.7.0.* |
| `chunking` | `KreuzbergChunkingConfig*` | `NULL` | Text chunking configuration (None = chunking disabled) |
| `content_filter` | `KreuzbergContentFilterConfig*` | `NULL` | Content filtering configuration (None = use extractor defaults). Controls whether document "furniture" (headers, footers, watermarks, repeating text) is included in or stripped from extraction results. See `ContentFilterConfig` for per-field documentation. |
| `images` | `KreuzbergImageExtractionConfig*` | `NULL` | Image extraction configuration (None = no image extraction) |
| `pdf_options` | `KreuzbergPdfConfig*` | `NULL` | PDF-specific options (None = use defaults) |
| `token_reduction` | `KreuzbergTokenReductionConfig*` | `NULL` | Token reduction configuration (None = no token reduction) |
| `language_detection` | `KreuzbergLanguageDetectionConfig*` | `NULL` | Language detection configuration (None = no language detection) |
| `pages` | `KreuzbergPageConfig*` | `NULL` | Page extraction configuration (None = no page tracking) |
| `postprocessor` | `KreuzbergPostProcessorConfig*` | `NULL` | Post-processor configuration (None = use defaults) |
| `html_options` | `KreuzbergConversionOptions*` | `NULL` | HTML to Markdown conversion options (None = use defaults) Configure how HTML documents are converted to Markdown, including heading styles, list formatting, code block styles, and preprocessing options. |
| `html_output` | `KreuzbergHtmlOutputConfig*` | `NULL` | Styled HTML output configuration. When set alongside `output_format = OutputFormat.Html`, the extraction pipeline uses `StyledHtmlRenderer` which emits stable `kb-*` CSS class hooks on every structural element and optionally embeds theme CSS or user-supplied CSS in a `<style>` block. When `None`, the existing plain comrak-based HTML renderer is used. |
| `extraction_timeout_secs` | `uint64_t*` | `NULL` | Default per-file timeout in seconds for batch extraction. When set, each file in a batch will be canceled after this duration unless overridden by `FileExtractionConfig.timeout_secs`. `None` means no timeout (unbounded extraction time). |
| `max_concurrent_extractions` | `uintptr_t*` | `NULL` | Maximum concurrent extractions in batch operations (None = (num_cpus × 1.5).ceil()). Limits parallelism to prevent resource exhaustion when processing large batches. Defaults to (num_cpus × 1.5).ceil() when not set. |
| `result_format` | `KreuzbergOutputFormat` | `KREUZBERG_KREUZBERG_PLAIN` | Result structure format Controls whether results are returned in unified format (default) with all content in the `content` field, or element-based format with semantic elements (for Unstructured-compatible output). |
| `security_limits` | `KreuzbergSecurityLimits*` | `NULL` | Security limits for archive extraction. Controls maximum archive size, compression ratio, file count, and other security thresholds to prevent decompression bomb attacks. When `None`, default limits are used (500MB archive, 100:1 ratio, 10K files). |
| `output_format` | `KreuzbergOutputFormat` | `KREUZBERG_KREUZBERG_PLAIN` | Content text format (default: Plain). Controls the format of the extracted content: - `Plain`: Raw extracted text (default) - `Markdown`: Markdown formatted output - `Djot`: Djot markup format (requires djot feature) - `Html`: HTML formatted output When set to a structured format, extraction results will include formatted output. The `formatted_content` field may be populated when format conversion is applied. |
| `layout` | `KreuzbergLayoutDetectionConfig*` | `NULL` | Layout detection configuration (None = layout detection disabled). When set, PDF pages and images are analyzed for document structure (headings, code, formulas, tables, figures, etc.) using RT-DETR models via ONNX Runtime. For PDFs, layout hints override paragraph classification in the markdown pipeline. For images, per-region OCR is performed with markdown formatting based on detected layout classes. Requires the `layout-detection` feature. |
| `include_document_structure` | `bool` | `false` | Enable structured document tree output. When true, populates the `document` field on `ExtractionResult` with a hierarchical `DocumentStructure` containing heading-driven section nesting, table grids, content layer classification, and inline annotations. Independent of `result_format` — can be combined with Unified or ElementBased. |
| `acceleration` | `KreuzbergAccelerationConfig*` | `NULL` | Hardware acceleration configuration for ONNX Runtime models. Controls execution provider selection for layout detection and embedding models. When `None`, uses platform defaults (CoreML on macOS, CUDA on Linux, CPU on Windows). |
| `cache_namespace` | `const char**` | `NULL` | Cache namespace for tenant isolation. When set, cache entries are stored under `{cache_dir}/{namespace}/`. Must be alphanumeric, hyphens, or underscores only (max 64 chars). Different namespaces have isolated cache spaces on the same filesystem. |
| `cache_ttl_secs` | `uint64_t*` | `NULL` | Per-request cache TTL in seconds. Overrides the global `max_age_days` for this specific extraction. When `0`, caching is completely skipped (no read or write). When `None`, the global TTL applies. |
| `email` | `KreuzbergEmailConfig*` | `NULL` | Email extraction configuration (None = use defaults). Currently supports configuring the fallback codepage for MSG files that do not specify one. See `crate.core.config.EmailConfig` for details. |
| `concurrency` | `KreuzbergConcurrencyConfig*` | `NULL` | Concurrency limits for constrained environments (None = use defaults). Controls Rayon thread pool size, ONNX Runtime intra-op threads, and (when `max_concurrent_extractions` is unset) the batch concurrency semaphore. See `crate.core.config.ConcurrencyConfig` for details. |
| `max_archive_depth` | `uintptr_t` | `NULL` | Maximum recursion depth for archive extraction (default: 3). Set to 0 to disable recursive extraction (legacy behavior). |
| `tree_sitter` | `KreuzbergTreeSitterConfig*` | `NULL` | Tree-sitter language pack configuration (None = tree-sitter disabled). When set, enables code file extraction using tree-sitter parsers. Controls grammar download behavior and code analysis options. |
| `structured_extraction` | `KreuzbergStructuredExtractionConfig*` | `NULL` | Structured extraction via LLM (None = disabled). When set, the extracted document content is sent to an LLM with the provided JSON schema. The structured response is stored in `ExtractionResult.structured_output`. |

#### Methods

##### kreuzberg_default()

**Signature:**

```c
KreuzbergExtractionConfig kreuzberg_default();
```

##### kreuzberg_with_file_overrides()

Create a new `ExtractionConfig` by applying per-file overrides from a
`FileExtractionConfig`. Fields that are `Some` in the override replace the
corresponding field in `self`; `NULL` fields keep the original value.

Batch-level fields (`max_concurrent_extractions`, `use_cache`, `acceleration`,
`security_limits`) are never affected by overrides.

**Signature:**

```c
KreuzbergExtractionConfig kreuzberg_with_file_overrides(KreuzbergFileExtractionConfig overrides);
```

##### kreuzberg_normalized()

Normalize configuration for implicit requirements.

Currently handles:
- Auto-enabling `extract_pages` when `result_format` is `ElementBased`, because
  the element transformation requires per-page data to assign correct page numbers.
  Without this, all elements would incorrectly get `page_number=1`.
- Auto-enabling `extract_pages` when chunking is configured, because the chunker
  needs page boundaries to assign correct page numbers to chunks.

**Signature:**

```c
KreuzbergExtractionConfig kreuzberg_normalized();
```

##### kreuzberg_validate()

Validate the configuration, returning an error if any settings are invalid.

Checks:
- OCR backend name is supported (catches typos early)
- VLM backend config is present when backend is "vlm"
- Pipeline stage backends and VLM configs are valid
- Structured extraction schema and LLM model are non-empty

**Signature:**

```c
void kreuzberg_validate();
```

##### kreuzberg_needs_image_processing()

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

```c
bool kreuzberg_needs_image_processing();
```


---

### KreuzbergExtractionMetrics

Collection of all kreuzberg metric instruments.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `extraction_total` | `KreuzbergCounter` | — | Total extractions (attributes: mime_type, extractor, status). |
| `cache_hits` | `KreuzbergCounter` | — | Cache hits. |
| `cache_misses` | `KreuzbergCounter` | — | Cache misses. |
| `batch_total` | `KreuzbergCounter` | — | Total batch requests (attributes: status). |
| `extraction_duration_ms` | `KreuzbergHistogram` | — | Extraction wall-clock duration in milliseconds (attributes: mime_type, extractor). |
| `extraction_input_bytes` | `KreuzbergHistogram` | — | Input document size in bytes (attributes: mime_type). |
| `extraction_output_bytes` | `KreuzbergHistogram` | — | Output content size in bytes (attributes: mime_type). |
| `pipeline_duration_ms` | `KreuzbergHistogram` | — | Pipeline stage duration in milliseconds (attributes: stage). |
| `ocr_duration_ms` | `KreuzbergHistogram` | — | OCR duration in milliseconds (attributes: backend, language). |
| `batch_duration_ms` | `KreuzbergHistogram` | — | Batch total duration in milliseconds. |
| `concurrent_extractions` | `KreuzbergUpDownCounter` | — | Currently in-flight extractions. |


---

### KreuzbergExtractionRequest

A request to extract content from a single document.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `source` | `KreuzbergExtractionSource` | — | Where to read the document from. |
| `config` | `KreuzbergExtractionConfig` | — | Base extraction configuration. |
| `file_overrides` | `KreuzbergFileExtractionConfig*` | `NULL` | Optional per-file overrides (merged on top of `config`). |

#### Methods

##### kreuzberg_file()

Create a file-based extraction request.

**Signature:**

```c
KreuzbergExtractionRequest kreuzberg_file(const char* path, KreuzbergExtractionConfig config);
```

##### kreuzberg_file_with_mime()

Create a file-based extraction request with a MIME type hint.

**Signature:**

```c
KreuzbergExtractionRequest kreuzberg_file_with_mime(const char* path, const char* mime_hint, KreuzbergExtractionConfig config);
```

##### kreuzberg_bytes()

Create a bytes-based extraction request.

**Signature:**

```c
KreuzbergExtractionRequest kreuzberg_bytes(const uint8_t* data, const char* mime_type, KreuzbergExtractionConfig config);
```

##### kreuzberg_with_overrides()

Set per-file overrides on this request.

**Signature:**

```c
KreuzbergExtractionRequest kreuzberg_with_overrides(KreuzbergFileExtractionConfig overrides);
```


---

### KreuzbergExtractionResult

General extraction result used by the core extraction API.

This is the main result type returned by all extraction functions.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `content` | `const char*` | `NULL` | The extracted text content |
| `mime_type` | `KreuzbergStr` | `NULL` | The detected MIME type |
| `metadata` | `KreuzbergMetadata` | `NULL` | Document metadata |
| `tables` | `KreuzbergTable*` | `NULL` | Tables extracted from the document |
| `detected_languages` | `const char***` | `NULL` | Detected languages |
| `chunks` | `KreuzbergChunk**` | `NULL` | Text chunks when chunking is enabled. When chunking configuration is provided, the content is split into overlapping chunks for efficient processing. Each chunk contains the text, optional embeddings (if enabled), and metadata about its position. |
| `images` | `KreuzbergExtractedImage**` | `NULL` | Extracted images from the document. When image extraction is enabled via `ImageExtractionConfig`, this field contains all images found in the document with their raw data and metadata. Each image may optionally contain a nested `ocr_result` if OCR was performed. |
| `pages` | `KreuzbergPageContent**` | `NULL` | Per-page content when page extraction is enabled. When page extraction is configured, the document is split into per-page content with tables and images mapped to their respective pages. |
| `elements` | `KreuzbergElement**` | `NULL` | Semantic elements when element-based result format is enabled. When result_format is set to ElementBased, this field contains semantic elements with type classification, unique identifiers, and metadata for Unstructured-compatible element-based processing. |
| `djot_content` | `KreuzbergDjotContent*` | `NULL` | Rich Djot content structure (when extracting Djot documents). When extracting Djot documents with structured extraction enabled, this field contains the full semantic structure including: - Block-level elements with nesting - Inline formatting with attributes - Links, images, footnotes - Math expressions - Complete attribute information The `content` field still contains plain text for backward compatibility. Always `None` for non-Djot documents. |
| `ocr_elements` | `KreuzbergOcrElement**` | `NULL` | OCR elements with full spatial and confidence metadata. When OCR is performed with element extraction enabled, this field contains the structured representation of detected text including: - Bounding geometry (rectangles or quadrilaterals) - Confidence scores (detection and recognition) - Rotation information - Hierarchical relationships (Tesseract only) This field preserves all metadata that would otherwise be lost when converting to plain text or markdown output formats. Only populated when `OcrElementConfig.include_elements` is true. |
| `document` | `KreuzbergDocumentStructure*` | `NULL` | Structured document tree (when document structure extraction is enabled). When `include_document_structure` is true in `ExtractionConfig`, this field contains the full hierarchical representation of the document including: - Heading-driven section nesting - Table grids with cell-level metadata - Content layer classification (body, header, footer, footnote) - Inline text annotations (formatting, links) - Bounding boxes and page numbers Independent of `result_format` — can be combined with Unified or ElementBased. |
| `quality_score` | `double*` | `NULL` | Document quality score from quality analysis. A value between 0.0 and 1.0 indicating the overall text quality. Previously stored in `metadata.additional["quality_score"]`. |
| `processing_warnings` | `KreuzbergProcessingWarning*` | `NULL` | Non-fatal warnings collected during processing pipeline stages. Captures errors from optional pipeline features (embedding, chunking, language detection, output formatting) that don't prevent extraction but may indicate degraded results. Previously stored as individual keys in `metadata.additional`. |
| `annotations` | `KreuzbergPdfAnnotation**` | `NULL` | PDF annotations extracted from the document. When annotation extraction is enabled via `PdfConfig.extract_annotations`, this field contains text notes, highlights, links, stamps, and other annotations found in PDF documents. |
| `children` | `KreuzbergArchiveEntry**` | `NULL` | Nested extraction results from archive contents. When extracting archives, each processable file inside produces its own full extraction result. Set to `None` for non-archive formats. Use `max_archive_depth` in config to control recursion depth. |
| `uris` | `KreuzbergUri**` | `NULL` | URIs/links discovered during document extraction. Contains hyperlinks, image references, citations, email addresses, and other URI-like references found in the document. Always extracted when present in the source document. |
| `structured_output` | `void**` | `NULL` | Structured extraction output from LLM-based JSON schema extraction. When `structured_extraction` is configured in `ExtractionConfig`, the extracted document content is sent to a VLM with the provided JSON schema. The response is parsed and stored here as a JSON value matching the schema. |
| `code_intelligence` | `KreuzbergProcessResult*` | `NULL` | Code intelligence results from tree-sitter analysis. Populated when extracting source code files with the `tree-sitter` feature. Contains metrics, structural analysis, imports/exports, comments, docstrings, symbols, diagnostics, and optionally chunked code segments. |
| `llm_usage` | `KreuzbergLlmUsage**` | `NULL` | LLM token usage and cost data for all LLM calls made during this extraction. Contains one entry per LLM call. Multiple entries are produced when VLM OCR, structured extraction, and/or LLM embeddings all run during the same extraction. `None` when no LLM was used. |
| `formatted_content` | `const char**` | `NULL` | Pre-rendered content in the requested output format. Populated during `derive_extraction_result` before tree derivation consumes element data. `apply_output_format` swaps this into `content` at the end of the pipeline, after post-processors have operated on plain text. |
| `ocr_internal_document` | `KreuzbergInternalDocument*` | `NULL` | Structured hOCR document for the OCR+layout pipeline. When tesseract produces hOCR output, the parsed `InternalDocument` carries paragraph structure with bounding boxes and confidence scores. The layout classification step enriches these elements before final rendering. |


---

### KreuzbergExtractionServiceBuilder

Builder for composing an extraction service with Tower middleware layers.

Layers are applied in the order: Tracing → Metrics → Timeout → ConcurrencyLimit → Service.

#### Methods

##### kreuzberg_default()

**Signature:**

```c
KreuzbergExtractionServiceBuilder kreuzberg_default();
```

##### kreuzberg_with_timeout()

Add a per-request timeout.

**Signature:**

```c
KreuzbergExtractionServiceBuilder kreuzberg_with_timeout(uint64_t duration);
```

##### kreuzberg_with_concurrency_limit()

Limit concurrent in-flight extractions.

**Signature:**

```c
KreuzbergExtractionServiceBuilder kreuzberg_with_concurrency_limit(uintptr_t max);
```

##### kreuzberg_with_tracing()

Add a tracing span to each extraction request.

**Signature:**

```c
KreuzbergExtractionServiceBuilder kreuzberg_with_tracing();
```

##### kreuzberg_with_metrics()

Add metrics recording to each extraction request.

Requires the `otel` feature. This is a no-op when `otel` is not enabled.

**Signature:**

```c
KreuzbergExtractionServiceBuilder kreuzberg_with_metrics();
```

##### kreuzberg_build()

Build the service stack, returning a type-erased cloneable service.

Layer order (outermost to innermost):
`Tracing → Metrics → Timeout → ConcurrencyLimit → ExtractionService`

**Signature:**

```c
KreuzbergBoxCloneService kreuzberg_build();
```


---

### KreuzbergFictionBookExtractor

FictionBook document extractor.

Supports FictionBook 2.0 format with proper section hierarchy and inline formatting.

#### Methods

##### kreuzberg_default()

**Signature:**

```c
KreuzbergFictionBookExtractor kreuzberg_default();
```

##### kreuzberg_name()

**Signature:**

```c
const char* kreuzberg_name();
```

##### kreuzberg_version()

**Signature:**

```c
const char* kreuzberg_version();
```

##### kreuzberg_initialize()

**Signature:**

```c
void kreuzberg_initialize();
```

##### kreuzberg_shutdown()

**Signature:**

```c
void kreuzberg_shutdown();
```

##### kreuzberg_description()

**Signature:**

```c
const char* kreuzberg_description();
```

##### kreuzberg_author()

**Signature:**

```c
const char* kreuzberg_author();
```

##### kreuzberg_extract_bytes()

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_extract_bytes(const uint8_t* content, const char* mime_type, KreuzbergExtractionConfig config);
```

##### kreuzberg_supported_mime_types()

**Signature:**

```c
const char** kreuzberg_supported_mime_types();
```

##### kreuzberg_priority()

**Signature:**

```c
int32_t kreuzberg_priority();
```


---

### KreuzbergFictionBookMetadata

FictionBook (FB2) metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `genres` | `const char**` | `NULL` | Genres |
| `sequences` | `const char**` | `NULL` | Sequences |
| `annotation` | `const char**` | `NULL` | Annotation |


---

### KreuzbergFileBytes

An owned buffer of file bytes.

On non-WASM platforms this may be backed by a memory-mapped file (zero heap
allocation for the file contents) or by a `Vec<u8>` for small files.
On WASM it is always a `Vec<u8>`.

Implements `Deref<Target = [u8]>` so callers can pass `&FileBytes` as `&[u8]`
without any additional copy.

#### Methods

##### kreuzberg_deref()

**Signature:**

```c
const uint8_t* kreuzberg_deref();
```

##### kreuzberg_as_ref()

**Signature:**

```c
const uint8_t* kreuzberg_as_ref();
```


---

### KreuzbergFileExtractionConfig

Per-file extraction configuration overrides for batch processing.

All fields are `Option<T>` — `NULL` means "use the batch-level default."
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
| `enable_quality_processing` | `bool*` | `NULL` | Override quality post-processing for this file. |
| `ocr` | `KreuzbergOcrConfig*` | `NULL` | Override OCR configuration for this file (None in the Option = use batch default). |
| `force_ocr` | `bool*` | `NULL` | Override force OCR for this file. |
| `force_ocr_pages` | `uintptr_t**` | `NULL` | Override force OCR pages for this file (1-indexed page numbers). |
| `disable_ocr` | `bool*` | `NULL` | Override disable OCR for this file. |
| `chunking` | `KreuzbergChunkingConfig*` | `NULL` | Override chunking configuration for this file. |
| `content_filter` | `KreuzbergContentFilterConfig*` | `NULL` | Override content filtering configuration for this file. |
| `images` | `KreuzbergImageExtractionConfig*` | `NULL` | Override image extraction configuration for this file. |
| `pdf_options` | `KreuzbergPdfConfig*` | `NULL` | Override PDF options for this file. |
| `token_reduction` | `KreuzbergTokenReductionConfig*` | `NULL` | Override token reduction for this file. |
| `language_detection` | `KreuzbergLanguageDetectionConfig*` | `NULL` | Override language detection for this file. |
| `pages` | `KreuzbergPageConfig*` | `NULL` | Override page extraction for this file. |
| `postprocessor` | `KreuzbergPostProcessorConfig*` | `NULL` | Override post-processor for this file. |
| `html_options` | `KreuzbergConversionOptions*` | `NULL` | Override HTML conversion options for this file. |
| `result_format` | `KreuzbergOutputFormat*` | `KREUZBERG_KREUZBERG_PLAIN` | Override result format for this file. |
| `output_format` | `KreuzbergOutputFormat*` | `KREUZBERG_KREUZBERG_PLAIN` | Override output content format for this file. |
| `include_document_structure` | `bool*` | `NULL` | Override document structure output for this file. |
| `layout` | `KreuzbergLayoutDetectionConfig*` | `NULL` | Override layout detection for this file. |
| `timeout_secs` | `uint64_t*` | `NULL` | Override per-file extraction timeout in seconds. When set, the extraction for this file will be canceled after the specified duration. A timed-out file produces an error result without affecting other files in the batch. |
| `tree_sitter` | `KreuzbergTreeSitterConfig*` | `NULL` | Override tree-sitter configuration for this file. |
| `structured_extraction` | `KreuzbergStructuredExtractionConfig*` | `NULL` | Override structured extraction configuration for this file. When set, enables LLM-based structured extraction with a JSON schema for this specific file. The extracted content is sent to a VLM/LLM and the response is parsed according to the provided schema. |


---

### KreuzbergFileHeader

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `flags` | `uint32_t` | — | Flags |

#### Methods

##### kreuzberg_parse()

**Signature:**

```c
KreuzbergFileHeader kreuzberg_parse(const uint8_t* data);
```

##### kreuzberg_is_compressed()

Whether section streams are zlib/deflate-compressed.

**Signature:**

```c
bool kreuzberg_is_compressed();
```

##### kreuzberg_is_encrypted()

Whether the document is password-encrypted.

**Signature:**

```c
bool kreuzberg_is_encrypted();
```

##### kreuzberg_is_distribute()

Whether the document is a distribution document (text in ViewText/).

**Signature:**

```c
bool kreuzberg_is_distribute();
```


---

### KreuzbergFontScheme

Font scheme containing major (heading) and minor (body) fonts.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `name` | `const char*` | `NULL` | Font scheme name. |
| `major_latin` | `const char**` | `NULL` | Major (heading) font - Latin script. |
| `major_east_asian` | `const char**` | `NULL` | Major (heading) font - East Asian script. |
| `major_complex_script` | `const char**` | `NULL` | Major (heading) font - Complex script. |
| `minor_latin` | `const char**` | `NULL` | Minor (body) font - Latin script. |
| `minor_east_asian` | `const char**` | `NULL` | Minor (body) font - East Asian script. |
| `minor_complex_script` | `const char**` | `NULL` | Minor (body) font - Complex script. |


---

### KreuzbergFootnote

Footnote in Djot.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `label` | `const char*` | — | Footnote label |
| `content` | `KreuzbergFormattedBlock*` | — | Footnote content blocks |


---

### KreuzbergFormattedBlock

Block-level element in a Djot document.

Represents structural elements like headings, paragraphs, lists, code blocks, etc.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `block_type` | `KreuzbergBlockType` | — | Type of block element |
| `level` | `uintptr_t*` | `NULL` | Heading level (1-6) for headings, or nesting level for lists |
| `inline_content` | `KreuzbergInlineElement*` | — | Inline content within the block |
| `attributes` | `KreuzbergAttributes*` | `NULL` | Element attributes (classes, IDs, key-value pairs) |
| `language` | `const char**` | `NULL` | Language identifier for code blocks |
| `code` | `const char**` | `NULL` | Raw code content for code blocks |
| `children` | `KreuzbergFormattedBlock*` | — | Nested blocks for containers (blockquotes, list items, divs) |


---

### KreuzbergGenericCache

#### Methods

##### kreuzberg_new()

**Signature:**

```c
KreuzbergGenericCache kreuzberg_new(const char* cache_type, const char* cache_dir, double max_age_days, double max_cache_size_mb, double min_free_space_mb);
```

##### kreuzberg_get()

**Signature:**

```c
const uint8_t** kreuzberg_get(const char* cache_key, const char* source_file, const char* namespace, uint64_t ttl_override_secs);
```

##### kreuzberg_get_default()

Backward-compatible get without namespace/TTL.

**Signature:**

```c
const uint8_t** kreuzberg_get_default(const char* cache_key, const char* source_file);
```

##### kreuzberg_set()

**Signature:**

```c
void kreuzberg_set(const char* cache_key, const uint8_t* data, const char* source_file, const char* namespace, uint64_t ttl_secs);
```

##### kreuzberg_set_default()

Backward-compatible set without namespace/TTL.

**Signature:**

```c
void kreuzberg_set_default(const char* cache_key, const uint8_t* data, const char* source_file);
```

##### kreuzberg_is_processing()

**Signature:**

```c
bool kreuzberg_is_processing(const char* cache_key);
```

##### kreuzberg_mark_processing()

**Signature:**

```c
void kreuzberg_mark_processing(const char* cache_key);
```

##### kreuzberg_mark_complete()

**Signature:**

```c
void kreuzberg_mark_complete(const char* cache_key);
```

##### kreuzberg_clear()

**Signature:**

```c
KreuzbergUsizeF64 kreuzberg_clear();
```

##### kreuzberg_delete_namespace()

Delete all cache entries under a namespace.

Removes the namespace subdirectory and all its contents.
Returns (files_removed, mb_freed).

**Signature:**

```c
KreuzbergUsizeF64 kreuzberg_delete_namespace(const char* namespace);
```

##### kreuzberg_get_stats()

**Signature:**

```c
KreuzbergCacheStats kreuzberg_get_stats();
```

##### kreuzberg_get_stats_filtered()

Get cache stats, optionally filtered to a specific namespace.

**Signature:**

```c
KreuzbergCacheStats kreuzberg_get_stats_filtered(const char* namespace);
```

##### kreuzberg_cache_dir()

**Signature:**

```c
const char* kreuzberg_cache_dir();
```

##### kreuzberg_cache_type()

**Signature:**

```c
const char* kreuzberg_cache_type();
```


---

### KreuzbergGridCell

Individual grid cell with position and span metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `content` | `const char*` | — | Cell text content. |
| `row` | `uint32_t` | — | Zero-indexed row position. |
| `col` | `uint32_t` | — | Zero-indexed column position. |
| `row_span` | `uint32_t` | — | Number of rows this cell spans. |
| `col_span` | `uint32_t` | — | Number of columns this cell spans. |
| `is_header` | `bool` | — | Whether this is a header cell. |
| `bbox` | `KreuzbergBoundingBox*` | `NULL` | Bounding box for this cell (if available). |


---

### KreuzbergGzipExtractor

Gzip archive extractor.

Decompresses gzip files and extracts text content from the compressed data.

#### Methods

##### kreuzberg_default()

**Signature:**

```c
KreuzbergGzipExtractor kreuzberg_default();
```

##### kreuzberg_name()

**Signature:**

```c
const char* kreuzberg_name();
```

##### kreuzberg_version()

**Signature:**

```c
const char* kreuzberg_version();
```

##### kreuzberg_initialize()

**Signature:**

```c
void kreuzberg_initialize();
```

##### kreuzberg_shutdown()

**Signature:**

```c
void kreuzberg_shutdown();
```

##### kreuzberg_description()

**Signature:**

```c
const char* kreuzberg_description();
```

##### kreuzberg_author()

**Signature:**

```c
const char* kreuzberg_author();
```

##### kreuzberg_extract_bytes()

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_extract_bytes(const uint8_t* content, const char* mime_type, KreuzbergExtractionConfig config);
```

##### kreuzberg_supported_mime_types()

**Signature:**

```c
const char** kreuzberg_supported_mime_types();
```

##### kreuzberg_priority()

**Signature:**

```c
int32_t kreuzberg_priority();
```

##### kreuzberg_as_sync_extractor()

**Signature:**

```c
KreuzbergSyncExtractor* kreuzberg_as_sync_extractor();
```

##### kreuzberg_extract_sync()

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_extract_sync(const uint8_t* content, const char* mime_type, KreuzbergExtractionConfig config);
```


---

### KreuzbergHeaderFooter

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `paragraphs` | `KreuzbergParagraph*` | `NULL` | Paragraphs |
| `tables` | `KreuzbergTable*` | `NULL` | Tables extracted from the document |
| `header_type` | `KreuzbergHeaderFooterType` | `KREUZBERG_KREUZBERG_DEFAULT` | Header type (header footer type) |


---

### KreuzbergHeaderMetadata

Header/heading element metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `level` | `uint8_t` | — | Header level: 1 (h1) through 6 (h6) |
| `text` | `const char*` | — | Normalized text content of the header |
| `id` | `const char**` | `NULL` | HTML id attribute if present |
| `depth` | `uintptr_t` | — | Document tree depth at the header element |
| `html_offset` | `uintptr_t` | — | Byte offset in original HTML document |


---

### KreuzbergHeadingContext

Heading context for a chunk within a Markdown document.

Contains the heading hierarchy from document root to this chunk's section.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `headings` | `KreuzbergHeadingLevel*` | — | The heading hierarchy from document root to this chunk's section. Index 0 is the outermost (h1), last element is the most specific. |


---

### KreuzbergHeadingLevel

A single heading in the hierarchy.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `level` | `uint8_t` | — | Heading depth (1 = h1, 2 = h2, etc.) |
| `text` | `const char*` | — | The text content of the heading. |


---

### KreuzbergHierarchicalBlock

A text block with hierarchy level assignment.

Represents a block of text with semantic heading information extracted from
font size clustering and hierarchical analysis.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `text` | `const char*` | — | The text content of this block |
| `font_size` | `float` | — | The font size of the text in this block |
| `level` | `const char*` | — | The hierarchy level of this block (H1-H6 or Body) Levels correspond to HTML heading tags: - "h1": Top-level heading - "h2": Secondary heading - "h3": Tertiary heading - "h4": Quaternary heading - "h5": Quinary heading - "h6": Senary heading - "body": Body text (no heading level) |
| `bbox` | `KreuzbergF32F32F32F32*` | `NULL` | Bounding box information for the block Contains coordinates as (left, top, right, bottom) in PDF units. |


---

### KreuzbergHierarchyConfig

Hierarchy extraction configuration for PDF text structure analysis.

Enables extraction of document hierarchy levels (H1-H6) based on font size
clustering and semantic analysis. When enabled, hierarchical blocks are
included in page content.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `enabled` | `bool` | `true` | Enable hierarchy extraction |
| `k_clusters` | `uintptr_t` | `3` | Number of font size clusters to use for hierarchy levels (1-7) Default: 6, which provides H1-H6 heading levels with body text. Larger values create more fine-grained hierarchy levels. |
| `include_bbox` | `bool` | `true` | Include bounding box information in hierarchy blocks |
| `ocr_coverage_threshold` | `float*` | `NULL` | OCR coverage threshold for smart OCR triggering (0.0-1.0) Determines when OCR should be triggered based on text block coverage. OCR is triggered when text blocks cover less than this fraction of the page. Default: 0.5 (trigger OCR if less than 50% of page has text) |

#### Methods

##### kreuzberg_default()

**Signature:**

```c
KreuzbergHierarchyConfig kreuzberg_default();
```


---

### KreuzbergHocrWord

Represents a word extracted from hOCR (or any source) with position and confidence information.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `text` | `const char*` | — | Text |
| `left` | `uint32_t` | — | Left |
| `top` | `uint32_t` | — | Top |
| `width` | `uint32_t` | — | Width |
| `height` | `uint32_t` | — | Height |
| `confidence` | `double` | — | Confidence |

#### Methods

##### kreuzberg_right()

Get the right edge position.

**Signature:**

```c
uint32_t kreuzberg_right();
```

##### kreuzberg_bottom()

Get the bottom edge position.

**Signature:**

```c
uint32_t kreuzberg_bottom();
```

##### kreuzberg_y_center()

Get the vertical center position.

**Signature:**

```c
double kreuzberg_y_center();
```

##### kreuzberg_x_center()

Get the horizontal center position.

**Signature:**

```c
double kreuzberg_x_center();
```


---

### KreuzbergHtmlExtractor

HTML document extractor using html-to-markdown.

#### Methods

##### kreuzberg_default()

**Signature:**

```c
KreuzbergHtmlExtractor kreuzberg_default();
```

##### kreuzberg_name()

**Signature:**

```c
const char* kreuzberg_name();
```

##### kreuzberg_version()

**Signature:**

```c
const char* kreuzberg_version();
```

##### kreuzberg_initialize()

**Signature:**

```c
void kreuzberg_initialize();
```

##### kreuzberg_shutdown()

**Signature:**

```c
void kreuzberg_shutdown();
```

##### kreuzberg_extract_sync()

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_extract_sync(const uint8_t* content, const char* mime_type, KreuzbergExtractionConfig config);
```

##### kreuzberg_extract_bytes()

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_extract_bytes(const uint8_t* content, const char* mime_type, KreuzbergExtractionConfig config);
```

##### kreuzberg_supported_mime_types()

**Signature:**

```c
const char** kreuzberg_supported_mime_types();
```

##### kreuzberg_priority()

**Signature:**

```c
int32_t kreuzberg_priority();
```

##### kreuzberg_as_sync_extractor()

**Signature:**

```c
KreuzbergSyncExtractor* kreuzberg_as_sync_extractor();
```


---

### KreuzbergHtmlMetadata

HTML metadata extracted from HTML documents.

Includes document-level metadata, Open Graph data, Twitter Card metadata,
and extracted structural elements (headers, links, images, structured data).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `title` | `const char**` | `NULL` | Document title from `<title>` tag |
| `description` | `const char**` | `NULL` | Document description from `<meta name="description">` tag |
| `keywords` | `const char**` | `NULL` | Document keywords from `<meta name="keywords">` tag, split on commas |
| `author` | `const char**` | `NULL` | Document author from `<meta name="author">` tag |
| `canonical_url` | `const char**` | `NULL` | Canonical URL from `<link rel="canonical">` tag |
| `base_href` | `const char**` | `NULL` | Base URL from `<base href="">` tag for resolving relative URLs |
| `language` | `const char**` | `NULL` | Document language from `lang` attribute |
| `text_direction` | `KreuzbergTextDirection*` | `KREUZBERG_KREUZBERG_LEFT_TO_RIGHT` | Document text direction from `dir` attribute |
| `open_graph` | `void*` | `NULL` | Open Graph metadata (og:* properties) for social media Keys like "title", "description", "image", "url", etc. |
| `twitter_card` | `void*` | `NULL` | Twitter Card metadata (twitter:* properties) Keys like "card", "site", "creator", "title", "description", "image", etc. |
| `meta_tags` | `void*` | `NULL` | Additional meta tags not covered by specific fields Keys are meta name/property attributes, values are content |
| `headers` | `KreuzbergHeaderMetadata*` | `NULL` | Extracted header elements with hierarchy |
| `links` | `KreuzbergLinkMetadata*` | `NULL` | Extracted hyperlinks with type classification |
| `images` | `KreuzbergImageMetadataType*` | `NULL` | Extracted images with source and dimensions |
| `structured_data` | `KreuzbergStructuredData*` | `NULL` | Extracted structured data blocks |

#### Methods

##### kreuzberg_is_empty()

Check if metadata is empty (no meaningful content extracted).

**Signature:**

```c
bool kreuzberg_is_empty();
```

##### kreuzberg_from()

**Signature:**

```c
KreuzbergHtmlMetadata kreuzberg_from(KreuzbergHtmlMetadata metadata);
```


---

### KreuzbergHtmlOutputConfig

Configuration for styled HTML output.

When set on `ExtractionConfig.html_output` alongside
`output_format = OutputFormat.Html`, the pipeline builds a
`StyledHtmlRenderer` instead of
the plain comrak-based renderer.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `css` | `const char**` | `NULL` | Inline CSS string injected into the output after the theme stylesheet. Concatenated after `css_file` content when both are set. |
| `css_file` | `const char**` | `NULL` | Path to a CSS file loaded once at renderer construction time. Concatenated before `css` when both are set. |
| `theme` | `KreuzbergHtmlTheme` | `KREUZBERG_KREUZBERG_UNSTYLED` | Built-in colour/typography theme. Default: `HtmlTheme.Unstyled`. |
| `class_prefix` | `const char*` | `NULL` | CSS class prefix applied to every emitted class name. Default: `"kb-"`. Change this if your host application already uses classes that start with `kb-`. |
| `embed_css` | `bool` | `true` | When `True` (default), write the resolved CSS into a `<style>` block immediately after the opening `<div class="{prefix}doc">`. Set to `False` to emit only the structural markup and wire up your own stylesheet targeting the `kb-*` class names. |

#### Methods

##### kreuzberg_default()

**Signature:**

```c
KreuzbergHtmlOutputConfig kreuzberg_default();
```


---

### KreuzbergHwpDocument

An extracted HWP document, consisting of one or more body-text sections.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `sections` | `KreuzbergSection*` | `NULL` | All sections from all BodyText/SectionN streams. |

#### Methods

##### kreuzberg_extract_text()

Concatenate the text of every paragraph in every section, separated by
newlines.

**Signature:**

```c
const char* kreuzberg_extract_text();
```


---

### KreuzbergHwpExtractor

Extractor for Hangul Word Processor (.hwp) files.

Supports HWP 5.0 format, the standard document format in South Korea.

#### Methods

##### kreuzberg_default()

**Signature:**

```c
KreuzbergHwpExtractor kreuzberg_default();
```

##### kreuzberg_name()

**Signature:**

```c
const char* kreuzberg_name();
```

##### kreuzberg_version()

**Signature:**

```c
const char* kreuzberg_version();
```

##### kreuzberg_initialize()

**Signature:**

```c
void kreuzberg_initialize();
```

##### kreuzberg_shutdown()

**Signature:**

```c
void kreuzberg_shutdown();
```

##### kreuzberg_description()

**Signature:**

```c
const char* kreuzberg_description();
```

##### kreuzberg_author()

**Signature:**

```c
const char* kreuzberg_author();
```

##### kreuzberg_extract_bytes()

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_extract_bytes(const uint8_t* content, const char* mime_type, KreuzbergExtractionConfig config);
```

##### kreuzberg_supported_mime_types()

**Signature:**

```c
const char** kreuzberg_supported_mime_types();
```

##### kreuzberg_priority()

**Signature:**

```c
int32_t kreuzberg_priority();
```


---

### KreuzbergImageDpiConfig

Image extraction DPI configuration (internal use).

**Note:** This is an internal type used for image preprocessing.
For the main extraction configuration, see `crate.core.config.ExtractionConfig`.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `target_dpi` | `int32_t` | `300` | Target DPI for image normalization |
| `max_image_dimension` | `int32_t` | `4096` | Maximum image dimension (width or height) |
| `auto_adjust_dpi` | `bool` | `true` | Whether to auto-adjust DPI based on content |
| `min_dpi` | `int32_t` | `72` | Minimum DPI threshold |
| `max_dpi` | `int32_t` | `600` | Maximum DPI threshold |

#### Methods

##### kreuzberg_default()

**Signature:**

```c
KreuzbergImageDpiConfig kreuzberg_default();
```


---

### KreuzbergImageExtractionConfig

Image extraction configuration.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `extract_images` | `bool` | `NULL` | Extract images from documents |
| `target_dpi` | `int32_t` | `NULL` | Target DPI for image normalization |
| `max_image_dimension` | `int32_t` | `NULL` | Maximum dimension for images (width or height) |
| `inject_placeholders` | `bool` | `NULL` | Whether to inject image reference placeholders into markdown output. When `True` (default), image references like `![Image 1](embedded:p1_i0)` are appended to the markdown. Set to `False` to extract images as data without polluting the markdown output. |
| `auto_adjust_dpi` | `bool` | `NULL` | Automatically adjust DPI based on image content |
| `min_dpi` | `int32_t` | `NULL` | Minimum DPI threshold |
| `max_dpi` | `int32_t` | `NULL` | Maximum DPI threshold |


---

### KreuzbergImageExtractor

Image extractor for various image formats.

Supports: PNG, JPEG, WebP, BMP, TIFF, GIF.
Extracts dimensions, format, and EXIF metadata.
Optionally runs OCR when configured.
When layout detection is also enabled, uses per-region OCR with
markdown formatting based on detected layout classes.

#### Methods

##### kreuzberg_default()

**Signature:**

```c
KreuzbergImageExtractor kreuzberg_default();
```

##### kreuzberg_name()

**Signature:**

```c
const char* kreuzberg_name();
```

##### kreuzberg_version()

**Signature:**

```c
const char* kreuzberg_version();
```

##### kreuzberg_initialize()

**Signature:**

```c
void kreuzberg_initialize();
```

##### kreuzberg_shutdown()

**Signature:**

```c
void kreuzberg_shutdown();
```

##### kreuzberg_description()

**Signature:**

```c
const char* kreuzberg_description();
```

##### kreuzberg_author()

**Signature:**

```c
const char* kreuzberg_author();
```

##### kreuzberg_extract_bytes()

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_extract_bytes(const uint8_t* content, const char* mime_type, KreuzbergExtractionConfig config);
```

##### kreuzberg_supported_mime_types()

**Signature:**

```c
const char** kreuzberg_supported_mime_types();
```

##### kreuzberg_priority()

**Signature:**

```c
int32_t kreuzberg_priority();
```


---

### KreuzbergImageMetadata

Image metadata extracted from image files.

Includes dimensions, format, and EXIF data.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `width` | `uint32_t` | — | Image width in pixels |
| `height` | `uint32_t` | — | Image height in pixels |
| `format` | `const char*` | — | Image format (e.g., "PNG", "JPEG", "TIFF") |
| `exif` | `void*` | — | EXIF metadata tags |


---

### KreuzbergImageMetadataType

Image element metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `src` | `const char*` | — | Image source (URL, data URI, or SVG content) |
| `alt` | `const char**` | `NULL` | Alternative text from alt attribute |
| `title` | `const char**` | `NULL` | Title attribute |
| `dimensions` | `KreuzbergU32U32*` | `NULL` | Image dimensions as (width, height) if available |
| `image_type` | `KreuzbergImageType` | — | Image type classification |
| `attributes` | `KreuzbergStringString*` | — | Additional attributes as key-value pairs |


---

### KreuzbergImageOcrResult

Result of OCR extraction from an image with optional page tracking.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `content` | `const char*` | — | Extracted text content |
| `boundaries` | `KreuzbergPageBoundary**` | `NULL` | Character byte boundaries per frame (for multi-frame TIFFs) |
| `page_contents` | `KreuzbergPageContent**` | `NULL` | Per-frame content information |


---

### KreuzbergImagePreprocessingConfig

Image preprocessing configuration for OCR.

These settings control how images are preprocessed before OCR to improve
text recognition quality. Different preprocessing strategies work better
for different document types.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `target_dpi` | `int32_t` | `300` | Target DPI for the image (300 is standard, 600 for small text). |
| `auto_rotate` | `bool` | `true` | Auto-detect and correct image rotation. |
| `deskew` | `bool` | `true` | Correct skew (tilted images). |
| `denoise` | `bool` | `false` | Remove noise from the image. |
| `contrast_enhance` | `bool` | `false` | Enhance contrast for better text visibility. |
| `binarization_method` | `const char*` | `"otsu"` | Binarization method: "otsu", "sauvola", "adaptive". |
| `invert_colors` | `bool` | `false` | Invert colors (white text on black → black on white). |

#### Methods

##### kreuzberg_default()

**Signature:**

```c
KreuzbergImagePreprocessingConfig kreuzberg_default();
```


---

### KreuzbergImagePreprocessingMetadata

Image preprocessing metadata.

Tracks the transformations applied to an image during OCR preprocessing,
including DPI normalization, resizing, and resampling.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `original_dimensions` | `KreuzbergUsizeUsize` | — | Original image dimensions (width, height) in pixels |
| `original_dpi` | `KreuzbergF64F64` | — | Original image DPI (horizontal, vertical) |
| `target_dpi` | `int32_t` | — | Target DPI from configuration |
| `scale_factor` | `double` | — | Scaling factor applied to the image |
| `auto_adjusted` | `bool` | — | Whether DPI was auto-adjusted based on content |
| `final_dpi` | `int32_t` | — | Final DPI after processing |
| `new_dimensions` | `KreuzbergUsizeUsize*` | `NULL` | New dimensions after resizing (if resized) |
| `resample_method` | `const char*` | — | Resampling algorithm used ("LANCZOS3", "CATMULLROM", etc.) |
| `dimension_clamped` | `bool` | — | Whether dimensions were clamped to max_image_dimension |
| `calculated_dpi` | `int32_t*` | `NULL` | Calculated optimal DPI (if auto_adjust_dpi enabled) |
| `skipped_resize` | `bool` | — | Whether resize was skipped (dimensions already optimal) |
| `resize_error` | `const char**` | `NULL` | Error message if resize failed |


---

### KreuzbergInlineElement

Inline element within a block.

Represents text with formatting, links, images, etc.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `element_type` | `KreuzbergInlineType` | — | Type of inline element |
| `content` | `const char*` | — | Text content |
| `attributes` | `KreuzbergAttributes*` | `NULL` | Element attributes |
| `metadata` | `void**` | `NULL` | Additional metadata (e.g., href for links, src/alt for images) |


---

### KreuzbergInstant

A platform-aware instant for measuring elapsed time.

On native targets this delegates to `std.time.Instant`.
On `wasm32` targets it is a zero-cost no-op to avoid the `unreachable` trap.

#### Methods

##### kreuzberg_now()

Capture the current instant.

**Signature:**

```c
KreuzbergInstant kreuzberg_now();
```

##### kreuzberg_elapsed_secs_f64()

Seconds elapsed since this instant was captured (as `f64`).

**Signature:**

```c
double kreuzberg_elapsed_secs_f64();
```

##### kreuzberg_elapsed_ms()

Milliseconds elapsed since this instant was captured (as `f64`).

**Signature:**

```c
double kreuzberg_elapsed_ms();
```

##### kreuzberg_elapsed_millis()

Milliseconds elapsed as `u128` (mirrors `Duration.as_millis`).

**Signature:**

```c
KreuzbergU128 kreuzberg_elapsed_millis();
```


---

### KreuzbergInternalDocument

The internal flat document representation.

All extractors output this structure. It is converted to the public
`ExtractionResult` and
`DocumentStructure` in the pipeline.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `elements` | `KreuzbergInternalElement*` | — | All elements in reading order. Append-only during extraction. |
| `relationships` | `KreuzbergRelationship*` | — | Relationships between elements (source index → target). Stored separately from elements for cache-friendly iteration. |
| `source_format` | `KreuzbergStr` | — | Source format identifier (e.g., "pdf", "docx", "html", "markdown"). |
| `metadata` | `KreuzbergMetadata` | — | Document-level metadata (title, author, dates, etc.). |
| `images` | `KreuzbergExtractedImage*` | — | Extracted images (binary data). Referenced by index from `ElementKind.Image`. |
| `tables` | `KreuzbergTable*` | — | Extracted tables (structured data). Referenced by index from `ElementKind.Table`. |
| `uris` | `KreuzbergUri*` | — | URIs/links discovered during extraction (hyperlinks, image refs, citations, etc.). |
| `children` | `KreuzbergArchiveEntry**` | `NULL` | Archive children: fully-extracted results for files within an archive. Only populated by archive extractors (ZIP, TAR, 7z, GZIP) when recursive extraction is enabled. Each entry contains the full `ExtractionResult` for a child file that was extracted through the public pipeline. |
| `mime_type` | `KreuzbergStr` | — | MIME type of the source document (e.g., "application/pdf", "text/html"). |
| `processing_warnings` | `KreuzbergProcessingWarning*` | — | Non-fatal warnings collected during extraction. |
| `annotations` | `KreuzbergPdfAnnotation**` | `NULL` | PDF annotations (links, highlights, notes). |
| `prebuilt_pages` | `KreuzbergPageContent**` | `NULL` | Pre-built per-page content (set by extractors that track page boundaries natively). When populated, `derive_extraction_result` uses this directly instead of attempting to reconstruct pages from element-level page numbers. |
| `pre_rendered_content` | `const char**` | `NULL` | Pre-rendered formatted content produced by the extractor itself. When an extractor has direct access to high-quality formatted output (e.g., html-to-markdown produces GFM markdown), it can store that here to bypass the lossy InternalDocument → renderer round-trip. `derive_extraction_result` will use this directly when the requested output format matches `metadata.output_format`. |

#### Methods

##### kreuzberg_push_element()

Push an element and return its index.

**Signature:**

```c
uint32_t kreuzberg_push_element(KreuzbergInternalElement element);
```

##### kreuzberg_push_relationship()

Push a relationship.

**Signature:**

```c
void kreuzberg_push_relationship(KreuzbergRelationship relationship);
```

##### kreuzberg_push_table()

Push a table and return its index (for use in `ElementKind.Table`).

**Signature:**

```c
uint32_t kreuzberg_push_table(KreuzbergTable table);
```

##### kreuzberg_push_image()

Push an image and return its index (for use in `ElementKind.Image`).

**Signature:**

```c
uint32_t kreuzberg_push_image(KreuzbergExtractedImage image);
```

##### kreuzberg_push_uri()

Push a URI discovered during extraction.
Silently drops URIs beyond `MAX_URIS` to prevent unbounded memory growth.

**Signature:**

```c
void kreuzberg_push_uri(KreuzbergUri uri);
```

##### kreuzberg_content()

Concatenate all element text into a single string, separated by newlines.

**Signature:**

```c
const char* kreuzberg_content();
```


---

### KreuzbergInternalDocumentBuilder

Builder for constructing `InternalDocument` with an ergonomic push-based API.

Tracks nesting depth automatically for list and quote containers,
and generates deterministic element IDs via blake3 hashing.

#### Methods

##### kreuzberg_source_format()

Set the source format identifier (e.g. "docx", "html", "pptx").

**Signature:**

```c
void kreuzberg_source_format(KreuzbergStr format);
```

##### kreuzberg_set_metadata()

Set document-level metadata.

**Signature:**

```c
void kreuzberg_set_metadata(KreuzbergMetadata metadata);
```

##### kreuzberg_set_mime_type()

Set the MIME type of the source document.

**Signature:**

```c
void kreuzberg_set_mime_type(KreuzbergStr mime_type);
```

##### kreuzberg_add_warning()

Add a non-fatal processing warning.

**Signature:**

```c
void kreuzberg_add_warning(KreuzbergProcessingWarning warning);
```

##### kreuzberg_set_pdf_annotations()

Set document-level PDF annotations (links, highlights, notes).

**Signature:**

```c
void kreuzberg_set_pdf_annotations(KreuzbergPdfAnnotation* annotations);
```

##### kreuzberg_push_uri()

Push a URI discovered during extraction.

**Signature:**

```c
void kreuzberg_push_uri(KreuzbergUri uri);
```

##### kreuzberg_build()

Consume the builder and return the constructed `InternalDocument`.

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_build();
```

##### kreuzberg_push_heading()

Push a heading element.

Auto-sets depth from the heading level and generates an anchor slug
from the heading text.

**Signature:**

```c
uint32_t kreuzberg_push_heading(uint8_t level, const char* text, uint32_t page, KreuzbergBoundingBox bbox);
```

##### kreuzberg_push_paragraph()

Push a paragraph element.

**Signature:**

```c
uint32_t kreuzberg_push_paragraph(const char* text, KreuzbergTextAnnotation* annotations, uint32_t page, KreuzbergBoundingBox bbox);
```

##### kreuzberg_push_list()

Push a `ListStart` marker and increment depth.

**Signature:**

```c
void kreuzberg_push_list(bool ordered);
```

##### kreuzberg_end_list()

Push a `ListEnd` marker and decrement depth.

**Signature:**

```c
void kreuzberg_end_list();
```

##### kreuzberg_push_list_item()

Push a list item element at the current depth.

**Signature:**

```c
uint32_t kreuzberg_push_list_item(const char* text, bool ordered, KreuzbergTextAnnotation* annotations, uint32_t page, KreuzbergBoundingBox bbox);
```

##### kreuzberg_push_table()

Push a table element. The table data is stored separately in
`InternalDocument.tables` and referenced by index.

**Signature:**

```c
uint32_t kreuzberg_push_table(KreuzbergTable table, uint32_t page, KreuzbergBoundingBox bbox);
```

##### kreuzberg_push_table_from_cells()

Push a table element from a 2D cell grid, building a `Table` struct automatically.

**Signature:**

```c
uint32_t kreuzberg_push_table_from_cells(const char*** cells, uint32_t page, KreuzbergBoundingBox bbox);
```

##### kreuzberg_push_image()

Push an image element. The image data is stored separately in
`InternalDocument.images` and referenced by index.

**Signature:**

```c
uint32_t kreuzberg_push_image(const char* description, KreuzbergExtractedImage image, uint32_t page, KreuzbergBoundingBox bbox);
```

##### kreuzberg_push_code()

Push a code block element. Language is stored in attributes.

**Signature:**

```c
uint32_t kreuzberg_push_code(const char* text, const char* language, uint32_t page, KreuzbergBoundingBox bbox);
```

##### kreuzberg_push_formula()

Push a math formula element.

**Signature:**

```c
uint32_t kreuzberg_push_formula(const char* text, uint32_t page, KreuzbergBoundingBox bbox);
```

##### kreuzberg_push_footnote_ref()

Push a footnote reference marker.

Creates a `FootnoteRef` element with `anchor = key` and also records
a `Relationship` with `RelationshipTarget.Key(key)` so the derivation
step can resolve it to the definition.

**Signature:**

```c
uint32_t kreuzberg_push_footnote_ref(const char* marker, const char* key, uint32_t page);
```

##### kreuzberg_push_footnote_definition()

Push a footnote definition element with `anchor = key`.

**Signature:**

```c
uint32_t kreuzberg_push_footnote_definition(const char* text, const char* key, uint32_t page);
```

##### kreuzberg_push_citation()

Push a citation / bibliographic reference element.

**Signature:**

```c
uint32_t kreuzberg_push_citation(const char* text, const char* key, uint32_t page);
```

##### kreuzberg_push_quote_start()

Push a `QuoteStart` marker and increment depth.

**Signature:**

```c
void kreuzberg_push_quote_start();
```

##### kreuzberg_push_quote_end()

Push a `QuoteEnd` marker and decrement depth.

**Signature:**

```c
void kreuzberg_push_quote_end();
```

##### kreuzberg_push_page_break()

Push a page break marker at depth 0.

**Signature:**

```c
void kreuzberg_push_page_break();
```

##### kreuzberg_push_slide()

Push a slide element.

**Signature:**

```c
uint32_t kreuzberg_push_slide(uint32_t number, const char* title, uint32_t page);
```

##### kreuzberg_push_admonition()

Push an admonition / callout element (note, warning, tip, etc.).
Kind and optional title are stored in attributes.

**Signature:**

```c
uint32_t kreuzberg_push_admonition(const char* kind, const char* title, uint32_t page);
```

##### kreuzberg_push_raw_block()

Push a raw block preserved verbatim. Format is stored in attributes.

**Signature:**

```c
uint32_t kreuzberg_push_raw_block(const char* format, const char* content, uint32_t page);
```

##### kreuzberg_push_metadata_block()

Push a structured metadata block (frontmatter, email headers).
Entries are stored in attributes.

**Signature:**

```c
uint32_t kreuzberg_push_metadata_block(KreuzbergStringString* entries, uint32_t page);
```

##### kreuzberg_push_title()

Push a title element.

**Signature:**

```c
uint32_t kreuzberg_push_title(const char* text, uint32_t page, KreuzbergBoundingBox bbox);
```

##### kreuzberg_push_definition_term()

Push a definition term element.

**Signature:**

```c
uint32_t kreuzberg_push_definition_term(const char* text, uint32_t page);
```

##### kreuzberg_push_definition_description()

Push a definition description element.

**Signature:**

```c
uint32_t kreuzberg_push_definition_description(const char* text, uint32_t page);
```

##### kreuzberg_push_ocr_text()

Push an OCR text element with OCR-specific fields populated.

**Signature:**

```c
uint32_t kreuzberg_push_ocr_text(const char* text, KreuzbergOcrElementLevel level, KreuzbergOcrBoundingGeometry geometry, KreuzbergOcrConfidence confidence, KreuzbergOcrRotation rotation, uint32_t page, KreuzbergBoundingBox bbox);
```

##### kreuzberg_push_group_start()

Push a `GroupStart` marker and increment depth.

**Signature:**

```c
void kreuzberg_push_group_start(const char* label, uint32_t page);
```

##### kreuzberg_push_group_end()

Push a `GroupEnd` marker and decrement depth.

**Signature:**

```c
void kreuzberg_push_group_end();
```

##### kreuzberg_push_relationship()

Push a relationship between two elements.

**Signature:**

```c
void kreuzberg_push_relationship(uint32_t source, KreuzbergRelationshipTarget target, KreuzbergRelationshipKind kind);
```

##### kreuzberg_set_anchor()

Set the anchor on an already-pushed element.

**Signature:**

```c
void kreuzberg_set_anchor(uint32_t index, const char* anchor);
```

##### kreuzberg_set_layer()

Set the content layer on an already-pushed element.

**Signature:**

```c
void kreuzberg_set_layer(uint32_t index, KreuzbergContentLayer layer);
```

##### kreuzberg_set_attributes()

Set attributes on an already-pushed element.

**Signature:**

```c
void kreuzberg_set_attributes(uint32_t index, KreuzbergAHashMap attributes);
```

##### kreuzberg_set_annotations()

Set annotations on an already-pushed element.

**Signature:**

```c
void kreuzberg_set_annotations(uint32_t index, KreuzbergTextAnnotation* annotations);
```

##### kreuzberg_set_text()

Set the text content of an already-pushed element.

**Signature:**

```c
void kreuzberg_set_text(uint32_t index, const char* text);
```

##### kreuzberg_push_element()

Push a pre-constructed `InternalElement` directly.

Useful when the caller needs to construct an element with fields
that the builder's convenience methods don't cover (e.g. an image
element without `ExtractedImage` data).

**Signature:**

```c
uint32_t kreuzberg_push_element(KreuzbergInternalElement element);
```


---

### KreuzbergInternalElement

A single element in the internal flat document.

Elements are appended in reading order during extraction. The `depth` field
and optional container markers enable tree reconstruction in the derivation step.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `id` | `KreuzbergInternalElementId` | — | Deterministic identifier. |
| `kind` | `KreuzbergElementKind` | — | What kind of content this element represents. |
| `text` | `const char*` | — | Primary text content. Empty for non-text elements (images, page breaks). |
| `depth` | `uint16_t` | — | Nesting depth (0 = root level). Extractors set this based on heading level, list indent, blockquote depth, etc. The tree derivation step uses depth changes to reconstruct parent-child relationships. |
| `page` | `uint32_t*` | `NULL` | Page number (1-indexed). `None` for non-paginated formats. |
| `bbox` | `KreuzbergBoundingBox*` | `NULL` | Bounding box in document coordinates. |
| `layer` | `KreuzbergContentLayer` | — | Content layer classification (Body, Header, Footer, Footnote). |
| `annotations` | `KreuzbergTextAnnotation*` | — | Inline annotations (formatting, links) on this element's text content. Byte-range based, reuses the existing `TextAnnotation` type. |
| `attributes` | `KreuzbergAHashMap*` | `NULL` | Format-specific key-value attributes. Used for CSS classes, LaTeX env names, slide layout names, etc. |
| `anchor` | `const char**` | `NULL` | Optional anchor/key for this element. Used by the relationship resolver to match references to targets. Examples: heading slug `"introduction"`, footnote label `"fn1"`, citation key `"smith2024"`, figure label `"fig:diagram"`. |
| `ocr_geometry` | `KreuzbergOcrBoundingGeometry*` | `NULL` | OCR bounding geometry (rectangle or quadrilateral). |
| `ocr_confidence` | `KreuzbergOcrConfidence*` | `NULL` | OCR confidence scores (detection + recognition). |
| `ocr_rotation` | `KreuzbergOcrRotation*` | `NULL` | OCR rotation metadata. |

#### Methods

##### kreuzberg_text()

Create a simple text element with minimal fields.

**Signature:**

```c
KreuzbergInternalElement kreuzberg_text(KreuzbergElementKind kind, const char* text, uint16_t depth);
```

##### kreuzberg_with_page()

Set the page number.

**Signature:**

```c
KreuzbergInternalElement kreuzberg_with_page(uint32_t page);
```

##### kreuzberg_with_bbox()

Set the bounding box.

**Signature:**

```c
KreuzbergInternalElement kreuzberg_with_bbox(KreuzbergBoundingBox bbox);
```

##### kreuzberg_with_layer()

Set the content layer.

**Signature:**

```c
KreuzbergInternalElement kreuzberg_with_layer(KreuzbergContentLayer layer);
```

##### kreuzberg_with_anchor()

Set the anchor key.

**Signature:**

```c
KreuzbergInternalElement kreuzberg_with_anchor(const char* anchor);
```

##### kreuzberg_with_annotations()

Set annotations.

**Signature:**

```c
KreuzbergInternalElement kreuzberg_with_annotations(KreuzbergTextAnnotation* annotations);
```

##### kreuzberg_with_attributes()

Set attributes.

**Signature:**

```c
KreuzbergInternalElement kreuzberg_with_attributes(KreuzbergAHashMap attributes);
```

##### kreuzberg_with_index()

Regenerate the ID with the correct index (call after pushing to the document).

**Signature:**

```c
KreuzbergInternalElement kreuzberg_with_index(uint32_t index);
```


---

### KreuzbergInternalElementId

Deterministic element identifier, generated via blake3 hashing.

Format: `"ie-{12 hex chars}"` (48 bits from blake3, ~281 trillion address space).
Same input always produces the same ID, enabling diffing and caching.

#### Methods

##### kreuzberg_generate()

Generate a deterministic ID from element content.

Hashes the element kind discriminant, text content, page number, and
positional index using blake3. Takes 48 bits (6 bytes) of the hash.

**Signature:**

```c
KreuzbergInternalElementId kreuzberg_generate(const char* kind_discriminant, const char* text, uint32_t page, uint32_t index);
```

##### kreuzberg_as_str()

Get the ID as a string slice.

**Signature:**

```c
const char* kreuzberg_as_str();
```

##### kreuzberg_fmt()

**Signature:**

```c
KreuzbergUnknown kreuzberg_fmt(KreuzbergFormatter f);
```

##### kreuzberg_as_ref()

**Signature:**

```c
const char* kreuzberg_as_ref();
```


---

### KreuzbergIterationValidator

Helper struct for validating iteration counts.

#### Methods

##### kreuzberg_check_iteration()

Validate and increment iteration count.

**Returns:**
* `Ok(())` if count is within limits
* `Err(SecurityError)` if count exceeds limit

**Signature:**

```c
void kreuzberg_check_iteration();
```

##### kreuzberg_current_count()

Get current iteration count.

**Signature:**

```c
uintptr_t kreuzberg_current_count();
```


---

### KreuzbergJatsExtractor

JATS document extractor.

Supports JATS (Journal Article Tag Suite) XML documents in various versions,
handling both the full article structure and minimal JATS subsets.

#### Methods

##### kreuzberg_default()

**Signature:**

```c
KreuzbergJatsExtractor kreuzberg_default();
```

##### kreuzberg_name()

**Signature:**

```c
const char* kreuzberg_name();
```

##### kreuzberg_version()

**Signature:**

```c
const char* kreuzberg_version();
```

##### kreuzberg_initialize()

**Signature:**

```c
void kreuzberg_initialize();
```

##### kreuzberg_shutdown()

**Signature:**

```c
void kreuzberg_shutdown();
```

##### kreuzberg_extract_bytes()

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_extract_bytes(const uint8_t* content, const char* mime_type, KreuzbergExtractionConfig config);
```

##### kreuzberg_supported_mime_types()

**Signature:**

```c
const char** kreuzberg_supported_mime_types();
```

##### kreuzberg_priority()

**Signature:**

```c
int32_t kreuzberg_priority();
```


---

### KreuzbergJatsMetadata

JATS (Journal Article Tag Suite) metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `copyright` | `const char**` | `NULL` | Copyright |
| `license` | `const char**` | `NULL` | License |
| `history_dates` | `void*` | `NULL` | History dates |
| `contributor_roles` | `KreuzbergContributorRole*` | `NULL` | Contributor roles |


---

### KreuzbergJsonExtractionConfig

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `extract_schema` | `bool` | `false` | Extract schema |
| `max_depth` | `uintptr_t` | `20` | Maximum depth |
| `array_item_limit` | `uintptr_t` | `500` | Array item limit |
| `include_type_info` | `bool` | `false` | Include type info |
| `flatten_nested_objects` | `bool` | `true` | Flatten nested objects |
| `custom_text_field_patterns` | `const char**` | `NULL` | Custom text field patterns |

#### Methods

##### kreuzberg_default()

**Signature:**

```c
KreuzbergJsonExtractionConfig kreuzberg_default();
```


---

### KreuzbergJupyterExtractor

Jupyter Notebook extractor.

Extracts content from Jupyter notebook JSON files, including:
- Notebook metadata (kernel, language, nbformat version)
- Cell content (code and markdown)
- Cell outputs (text, HTML, etc.)
- Cell-level metadata (tags, execution counts)

#### Methods

##### kreuzberg_default()

**Signature:**

```c
KreuzbergJupyterExtractor kreuzberg_default();
```

##### kreuzberg_name()

**Signature:**

```c
const char* kreuzberg_name();
```

##### kreuzberg_version()

**Signature:**

```c
const char* kreuzberg_version();
```

##### kreuzberg_initialize()

**Signature:**

```c
void kreuzberg_initialize();
```

##### kreuzberg_shutdown()

**Signature:**

```c
void kreuzberg_shutdown();
```

##### kreuzberg_description()

**Signature:**

```c
const char* kreuzberg_description();
```

##### kreuzberg_author()

**Signature:**

```c
const char* kreuzberg_author();
```

##### kreuzberg_extract_bytes()

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_extract_bytes(const uint8_t* content, const char* mime_type, KreuzbergExtractionConfig config);
```

##### kreuzberg_supported_mime_types()

**Signature:**

```c
const char** kreuzberg_supported_mime_types();
```

##### kreuzberg_priority()

**Signature:**

```c
int32_t kreuzberg_priority();
```


---

### KreuzbergKeynoteExtractor

Apple Keynote presentation extractor.

Supports `.key` files (modern iWork format, 2013+).

Extracts slide text and speaker notes from the IWA container:
ZIP → Snappy → protobuf text fields.

#### Methods

##### kreuzberg_default()

**Signature:**

```c
KreuzbergKeynoteExtractor kreuzberg_default();
```

##### kreuzberg_name()

**Signature:**

```c
const char* kreuzberg_name();
```

##### kreuzberg_version()

**Signature:**

```c
const char* kreuzberg_version();
```

##### kreuzberg_initialize()

**Signature:**

```c
void kreuzberg_initialize();
```

##### kreuzberg_shutdown()

**Signature:**

```c
void kreuzberg_shutdown();
```

##### kreuzberg_description()

**Signature:**

```c
const char* kreuzberg_description();
```

##### kreuzberg_author()

**Signature:**

```c
const char* kreuzberg_author();
```

##### kreuzberg_extract_bytes()

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_extract_bytes(const uint8_t* content, const char* mime_type, KreuzbergExtractionConfig config);
```

##### kreuzberg_supported_mime_types()

**Signature:**

```c
const char** kreuzberg_supported_mime_types();
```

##### kreuzberg_priority()

**Signature:**

```c
int32_t kreuzberg_priority();
```


---

### KreuzbergKeyword

Extracted keyword with metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `text` | `const char*` | — | The keyword text. |
| `score` | `float` | — | Relevance score (higher is better, algorithm-specific range). |
| `algorithm` | `KreuzbergKeywordAlgorithm` | — | Algorithm that extracted this keyword. |
| `positions` | `uintptr_t**` | `NULL` | Optional positions where keyword appears in text (character offsets). |

#### Methods

##### kreuzberg_with_positions()

Create a new keyword with positions.

**Signature:**

```c
KreuzbergKeyword kreuzberg_with_positions(const char* text, float score, KreuzbergKeywordAlgorithm algorithm, uintptr_t* positions);
```


---

### KreuzbergKeywordConfig

Keyword extraction configuration.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `algorithm` | `KreuzbergKeywordAlgorithm` | `KREUZBERG_KREUZBERG_YAKE` | Algorithm to use for extraction. |
| `max_keywords` | `uintptr_t` | `10` | Maximum number of keywords to extract (default: 10). |
| `min_score` | `float` | `0` | Minimum score threshold (0.0-1.0, default: 0.0). Keywords with scores below this threshold are filtered out. Note: Score ranges differ between algorithms. |
| `ngram_range` | `KreuzbergUsizeUsize` | `NULL` | N-gram range for keyword extraction (min, max). (1, 1) = unigrams only (1, 2) = unigrams and bigrams (1, 3) = unigrams, bigrams, and trigrams (default) |
| `language` | `const char**` | `NULL` | Language code for stopword filtering (e.g., "en", "de", "fr"). If None, no stopword filtering is applied. |
| `yake_params` | `KreuzbergYakeParams*` | `NULL` | YAKE-specific tuning parameters. |
| `rake_params` | `KreuzbergRakeParams*` | `NULL` | RAKE-specific tuning parameters. |

#### Methods

##### kreuzberg_default()

**Signature:**

```c
KreuzbergKeywordConfig kreuzberg_default();
```

##### kreuzberg_with_max_keywords()

Set maximum number of keywords to extract.

**Signature:**

```c
KreuzbergKeywordConfig kreuzberg_with_max_keywords(uintptr_t max);
```

##### kreuzberg_with_min_score()

Set minimum score threshold.

**Signature:**

```c
KreuzbergKeywordConfig kreuzberg_with_min_score(float score);
```

##### kreuzberg_with_ngram_range()

Set n-gram range.

**Signature:**

```c
KreuzbergKeywordConfig kreuzberg_with_ngram_range(uintptr_t min, uintptr_t max);
```

##### kreuzberg_with_language()

Set language for stopword filtering.

**Signature:**

```c
KreuzbergKeywordConfig kreuzberg_with_language(const char* lang);
```


---

### KreuzbergKeywordExtractor

Post-processor that extracts keywords from document content.

This processor:
- Runs in the Middle processing stage
- Only processes when `config.keywords` is configured
- Stores extracted keywords in `metadata.additional["keywords"]`
- Uses the configured algorithm (YAKE or RAKE)

#### Methods

##### kreuzberg_name()

**Signature:**

```c
const char* kreuzberg_name();
```

##### kreuzberg_version()

**Signature:**

```c
const char* kreuzberg_version();
```

##### kreuzberg_initialize()

**Signature:**

```c
void kreuzberg_initialize();
```

##### kreuzberg_shutdown()

**Signature:**

```c
void kreuzberg_shutdown();
```

##### kreuzberg_process()

**Signature:**

```c
void kreuzberg_process(KreuzbergExtractionResult result, KreuzbergExtractionConfig config);
```

##### kreuzberg_processing_stage()

**Signature:**

```c
KreuzbergProcessingStage kreuzberg_processing_stage();
```

##### kreuzberg_should_process()

**Signature:**

```c
bool kreuzberg_should_process(KreuzbergExtractionResult result, KreuzbergExtractionConfig config);
```

##### kreuzberg_estimated_duration_ms()

**Signature:**

```c
uint64_t kreuzberg_estimated_duration_ms(KreuzbergExtractionResult result);
```


---

### KreuzbergLanguageDetectionConfig

Language detection configuration.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `enabled` | `bool` | — | Enable language detection |
| `min_confidence` | `double` | — | Minimum confidence threshold (0.0-1.0) |
| `detect_multiple` | `bool` | — | Detect multiple languages in the document |


---

### KreuzbergLanguageDetector

Post-processor that detects languages in document content.

This processor:
- Runs in the Early processing stage
- Only processes when `config.language_detection` is configured
- Stores detected languages in `result.detected_languages`
- Uses the whatlang library for detection

#### Methods

##### kreuzberg_name()

**Signature:**

```c
const char* kreuzberg_name();
```

##### kreuzberg_version()

**Signature:**

```c
const char* kreuzberg_version();
```

##### kreuzberg_initialize()

**Signature:**

```c
void kreuzberg_initialize();
```

##### kreuzberg_shutdown()

**Signature:**

```c
void kreuzberg_shutdown();
```

##### kreuzberg_process()

**Signature:**

```c
void kreuzberg_process(KreuzbergExtractionResult result, KreuzbergExtractionConfig config);
```

##### kreuzberg_processing_stage()

**Signature:**

```c
KreuzbergProcessingStage kreuzberg_processing_stage();
```

##### kreuzberg_should_process()

**Signature:**

```c
bool kreuzberg_should_process(KreuzbergExtractionResult result, KreuzbergExtractionConfig config);
```

##### kreuzberg_estimated_duration_ms()

**Signature:**

```c
uint64_t kreuzberg_estimated_duration_ms(KreuzbergExtractionResult result);
```


---

### KreuzbergLanguageRegistry

Language support registry for OCR backends.

Maintains a mapping of OCR backend names to their supported language codes.
This is the single source of truth for language support across all bindings.

#### Methods

##### kreuzberg_global()

Get the default global registry instance.

The registry is created on first access and reused for all subsequent calls.

**Returns:**

A reference to the global `LanguageRegistry` instance.

**Signature:**

```c
KreuzbergLanguageRegistry kreuzberg_global();
```

##### kreuzberg_get_supported_languages()

Get supported languages for a specific OCR backend.

**Returns:**

`Some(&[String])` if the backend is registered, `NULL` otherwise.

**Signature:**

```c
const char*** kreuzberg_get_supported_languages(const char* backend);
```

##### kreuzberg_is_language_supported()

Check if a language is supported by a specific backend.

**Returns:**

`true` if the language is supported, `false` otherwise.

**Signature:**

```c
bool kreuzberg_is_language_supported(const char* backend, const char* language);
```

##### kreuzberg_get_backends()

Get all registered backend names.

**Returns:**

A vector of backend names in the registry.

**Signature:**

```c
const char** kreuzberg_get_backends();
```

##### kreuzberg_get_language_count()

Get language count for a specific backend.

**Returns:**

Number of supported languages for the backend, or 0 if backend not found.

**Signature:**

```c
uintptr_t kreuzberg_get_language_count(const char* backend);
```

##### kreuzberg_default()

**Signature:**

```c
KreuzbergLanguageRegistry kreuzberg_default();
```


---

### KreuzbergLatexExtractor

LaTeX document extractor

#### Methods

##### kreuzberg_build_internal_document()

Build an `InternalDocument` from LaTeX source.

Captures `\label{}` as anchors, `\ref{}` as CrossReference relationships,
`\cite{}` as CitationReference relationships, and footnotes.

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_build_internal_document(const char* source, bool inject_placeholders);
```

##### kreuzberg_default()

**Signature:**

```c
KreuzbergLatexExtractor kreuzberg_default();
```

##### kreuzberg_name()

**Signature:**

```c
const char* kreuzberg_name();
```

##### kreuzberg_version()

**Signature:**

```c
const char* kreuzberg_version();
```

##### kreuzberg_initialize()

**Signature:**

```c
void kreuzberg_initialize();
```

##### kreuzberg_shutdown()

**Signature:**

```c
void kreuzberg_shutdown();
```

##### kreuzberg_description()

**Signature:**

```c
const char* kreuzberg_description();
```

##### kreuzberg_author()

**Signature:**

```c
const char* kreuzberg_author();
```

##### kreuzberg_extract_bytes()

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_extract_bytes(const uint8_t* content, const char* mime_type, KreuzbergExtractionConfig config);
```

##### kreuzberg_extract_file()

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_extract_file(const char* path, const char* mime_type, KreuzbergExtractionConfig config);
```

##### kreuzberg_supported_mime_types()

**Signature:**

```c
const char** kreuzberg_supported_mime_types();
```

##### kreuzberg_priority()

**Signature:**

```c
int32_t kreuzberg_priority();
```


---

### KreuzbergLayoutDetection

A single layout detection result.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `class` | `KreuzbergLayoutClass` | — | Class (layout class) |
| `confidence` | `float` | — | Confidence |
| `bbox` | `KreuzbergBBox` | — | Bbox (b box) |

#### Methods

##### kreuzberg_sort_by_confidence_desc()

Sort detections by confidence in descending order.

**Signature:**

```c
void kreuzberg_sort_by_confidence_desc(KreuzbergLayoutDetection* detections);
```

##### kreuzberg_fmt()

**Signature:**

```c
KreuzbergUnknown kreuzberg_fmt(KreuzbergFormatter f);
```


---

### KreuzbergLayoutDetectionConfig

Layout detection configuration.

Controls layout detection behavior in the extraction pipeline.
When set on `ExtractionConfig`, layout detection
is enabled for PDF extraction.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `confidence_threshold` | `float*` | `NULL` | Confidence threshold override (None = use model default). |
| `apply_heuristics` | `bool` | `true` | Whether to apply postprocessing heuristics (default: true). |
| `table_model` | `KreuzbergTableModel` | `KREUZBERG_KREUZBERG_TATR` | Table structure recognition model. Controls which model is used for table cell detection within layout-detected table regions. Defaults to `TableModel.Tatr`. |

#### Methods

##### kreuzberg_default()

**Signature:**

```c
KreuzbergLayoutDetectionConfig kreuzberg_default();
```


---

### KreuzbergLayoutEngine

High-level layout detection engine.

Wraps model loading, inference, and postprocessing into a single
reusable object. Models are downloaded and cached on first use.

#### Methods

##### kreuzberg_from_config()

Create a layout engine from a full config.

**Signature:**

```c
KreuzbergLayoutEngine kreuzberg_from_config(KreuzbergLayoutEngineConfig config);
```

##### kreuzberg_detect()

Run layout detection on an image.

Returns a `DetectionResult` with bounding boxes, classes, and confidence scores.
If `apply_heuristics` is enabled in config, postprocessing is applied automatically.

**Signature:**

```c
KreuzbergDetectionResult kreuzberg_detect(KreuzbergRgbImage img);
```

##### kreuzberg_detect_timed()

Run layout detection on an image and return granular timing data.

Identical to `detect` but also returns a `DetectTimings` breakdown.
Use this when you need per-step profiling (preprocess / onnx / postprocess).

**Signature:**

```c
KreuzbergDetectionResultDetectTimings kreuzberg_detect_timed(KreuzbergRgbImage img);
```

##### kreuzberg_detect_batch()

Run layout detection on a batch of images in a single model call.

Returns one `(DetectionResult, DetectTimings)` tuple per input image.
Postprocessing heuristics are applied per image when enabled in config.

Timing note: `preprocess_ms` and `onnx_ms` in each `DetectTimings` are the
amortized per-image share of the batch operation (total / N), not independent
per-image measurements.

**Signature:**

```c
KreuzbergDetectionResultDetectTimings* kreuzberg_detect_batch(KreuzbergRgbImage* images);
```

##### kreuzberg_model_name()

Get the model name.

**Signature:**

```c
const char* kreuzberg_model_name();
```

##### kreuzberg_config()

Return a reference to the engine's configuration.

Used by callers (e.g. parallel layout runners) that need to create
additional engines with identical settings.

**Signature:**

```c
KreuzbergLayoutEngineConfig kreuzberg_config();
```


---

### KreuzbergLayoutEngineConfig

Full configuration for the layout engine.

Provides fine-grained control over model selection, thresholds, and
postprocessing.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `backend` | `KreuzbergModelBackend` | `KREUZBERG_KREUZBERG_RT_DETR` | Which model backend to use. |
| `confidence_threshold` | `float*` | `NULL` | Confidence threshold override (None = use model default). |
| `apply_heuristics` | `bool` | `true` | Whether to apply postprocessing heuristics. |
| `cache_dir` | `const char**` | `NULL` | Custom cache directory for model files (None = default). |

#### Methods

##### kreuzberg_default()

**Signature:**

```c
KreuzbergLayoutEngineConfig kreuzberg_default();
```


---

### KreuzbergLayoutModel

Common interface for all layout detection model backends.

#### Methods

##### kreuzberg_detect()

Run layout detection on an image using the default confidence threshold.

**Signature:**

```c
KreuzbergLayoutDetection* kreuzberg_detect(KreuzbergRgbImage img);
```

##### kreuzberg_detect_with_threshold()

Run layout detection with a custom confidence threshold.

**Signature:**

```c
KreuzbergLayoutDetection* kreuzberg_detect_with_threshold(KreuzbergRgbImage img, float threshold);
```

##### kreuzberg_detect_batch()

Run layout detection on a batch of images in a single model call.

Returns one `Vec<LayoutDetection>` per input image (same order).
`threshold` overrides the model's default confidence cutoff when `Some`.

The default implementation is a sequential fallback: models that support
true batched inference (e.g. `rtdetr.RtDetrModel`) override this.

**Signature:**

```c
KreuzbergLayoutDetection** kreuzberg_detect_batch(KreuzbergRgbImage* images, float threshold);
```

##### kreuzberg_name()

Human-readable model name.

**Signature:**

```c
const char* kreuzberg_name();
```


---

### KreuzbergLayoutTimingReport

Timing breakdown for the entire layout detection run.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `total_ms` | `double` | — | Total ms |
| `per_page` | `KreuzbergPageTiming*` | — | Per page |

#### Methods

##### kreuzberg_avg_render_ms()

**Signature:**

```c
double kreuzberg_avg_render_ms();
```

##### kreuzberg_avg_inference_ms()

**Signature:**

```c
double kreuzberg_avg_inference_ms();
```

##### kreuzberg_avg_preprocess_ms()

**Signature:**

```c
double kreuzberg_avg_preprocess_ms();
```

##### kreuzberg_avg_onnx_ms()

**Signature:**

```c
double kreuzberg_avg_onnx_ms();
```

##### kreuzberg_avg_postprocess_ms()

**Signature:**

```c
double kreuzberg_avg_postprocess_ms();
```

##### kreuzberg_total_inference_ms()

**Signature:**

```c
double kreuzberg_total_inference_ms();
```

##### kreuzberg_total_render_ms()

**Signature:**

```c
double kreuzberg_total_render_ms();
```

##### kreuzberg_total_preprocess_ms()

**Signature:**

```c
double kreuzberg_total_preprocess_ms();
```

##### kreuzberg_total_onnx_ms()

**Signature:**

```c
double kreuzberg_total_onnx_ms();
```

##### kreuzberg_total_postprocess_ms()

**Signature:**

```c
double kreuzberg_total_postprocess_ms();
```


---

### KreuzbergLinkMetadata

Link element metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `href` | `const char*` | — | The href URL value |
| `text` | `const char*` | — | Link text content (normalized) |
| `title` | `const char**` | `NULL` | Optional title attribute |
| `link_type` | `KreuzbergLinkType` | — | Link type classification |
| `rel` | `const char**` | — | Rel attribute values |
| `attributes` | `KreuzbergStringString*` | — | Additional attributes as key-value pairs |


---

### KreuzbergLlmConfig

Configuration for an LLM provider/model via liter-llm.

Each feature (VLM OCR, VLM embeddings, structured extraction) carries
its own `LlmConfig`, allowing different providers per feature.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `model` | `const char*` | — | Provider/model string using liter-llm routing format. Examples: `"openai/gpt-4o"`, `"anthropic/claude-sonnet-4-20250514"`, `"groq/llama-3.1-70b-versatile"`. |
| `api_key` | `const char**` | `NULL` | API key for the provider. When `None`, liter-llm falls back to the provider's standard environment variable (e.g., `OPENAI_API_KEY`). |
| `base_url` | `const char**` | `NULL` | Custom base URL override for the provider endpoint. |
| `timeout_secs` | `uint64_t*` | `NULL` | Request timeout in seconds (default: 60). |
| `max_retries` | `uint32_t*` | `NULL` | Maximum retry attempts (default: 3). |
| `temperature` | `double*` | `NULL` | Sampling temperature for generation tasks. |
| `max_tokens` | `uint64_t*` | `NULL` | Maximum tokens to generate. |


---

### KreuzbergLlmUsage

Token usage and cost data for a single LLM call made during extraction.

Populated when VLM OCR, structured extraction, or LLM-based embeddings
are used. Multiple entries may be present when multiple LLM calls occur
within one extraction (e.g. VLM OCR + structured extraction).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `model` | `const char*` | `NULL` | The LLM model identifier (e.g. "openai/gpt-4o", "anthropic/claude-sonnet-4-20250514"). |
| `source` | `const char*` | `NULL` | The pipeline stage that triggered this LLM call (e.g. "vlm_ocr", "structured_extraction", "embeddings"). |
| `input_tokens` | `uint64_t*` | `NULL` | Number of input/prompt tokens consumed. |
| `output_tokens` | `uint64_t*` | `NULL` | Number of output/completion tokens generated. |
| `total_tokens` | `uint64_t*` | `NULL` | Total tokens (input + output). |
| `estimated_cost` | `double*` | `NULL` | Estimated cost in USD based on the provider's published pricing. |
| `finish_reason` | `const char**` | `NULL` | Why the model stopped generating (e.g. "stop", "length", "content_filter"). |


---

### KreuzbergMarkdownExtractor

Markdown extractor with metadata and table support.

Parses markdown documents with YAML frontmatter, extracting:
- Metadata from YAML frontmatter
- Plain text content
- Tables as structured data
- Document structure (headings, links, code blocks)
- Images from data URIs

#### Methods

##### kreuzberg_build_internal_document()

Build an `InternalDocument` from pulldown-cmark events and optional YAML frontmatter.

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_build_internal_document(KreuzbergEvent* events, KreuzbergValue yaml);
```

##### kreuzberg_default()

**Signature:**

```c
KreuzbergMarkdownExtractor kreuzberg_default();
```

##### kreuzberg_name()

**Signature:**

```c
const char* kreuzberg_name();
```

##### kreuzberg_version()

**Signature:**

```c
const char* kreuzberg_version();
```

##### kreuzberg_initialize()

**Signature:**

```c
void kreuzberg_initialize();
```

##### kreuzberg_shutdown()

**Signature:**

```c
void kreuzberg_shutdown();
```

##### kreuzberg_description()

**Signature:**

```c
const char* kreuzberg_description();
```

##### kreuzberg_author()

**Signature:**

```c
const char* kreuzberg_author();
```

##### kreuzberg_extract_bytes()

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_extract_bytes(const uint8_t* content, const char* mime_type, KreuzbergExtractionConfig config);
```

##### kreuzberg_extract_file()

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_extract_file(const char* path, const char* mime_type, KreuzbergExtractionConfig config);
```

##### kreuzberg_supported_mime_types()

**Signature:**

```c
const char** kreuzberg_supported_mime_types();
```

##### kreuzberg_priority()

**Signature:**

```c
int32_t kreuzberg_priority();
```


---

### KreuzbergMdxExtractor

MDX extractor with JSX stripping and Markdown processing.

Strips MDX-specific syntax (imports, exports, JSX component tags,
inline expressions) and processes the remaining content as Markdown,
extracting metadata from YAML frontmatter and tables.

#### Methods

##### kreuzberg_build_internal_document()

Build an `InternalDocument` from pulldown-cmark events after JSX stripping.

JSX blocks that were stripped are recorded as raw blocks in the internal document.

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_build_internal_document(KreuzbergEvent* events, KreuzbergValue yaml, const char** raw_jsx_blocks);
```

##### kreuzberg_default()

**Signature:**

```c
KreuzbergMdxExtractor kreuzberg_default();
```

##### kreuzberg_name()

**Signature:**

```c
const char* kreuzberg_name();
```

##### kreuzberg_version()

**Signature:**

```c
const char* kreuzberg_version();
```

##### kreuzberg_initialize()

**Signature:**

```c
void kreuzberg_initialize();
```

##### kreuzberg_shutdown()

**Signature:**

```c
void kreuzberg_shutdown();
```

##### kreuzberg_description()

**Signature:**

```c
const char* kreuzberg_description();
```

##### kreuzberg_author()

**Signature:**

```c
const char* kreuzberg_author();
```

##### kreuzberg_extract_bytes()

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_extract_bytes(const uint8_t* content, const char* mime_type, KreuzbergExtractionConfig config);
```

##### kreuzberg_extract_file()

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_extract_file(const char* path, const char* mime_type, KreuzbergExtractionConfig config);
```

##### kreuzberg_supported_mime_types()

**Signature:**

```c
const char** kreuzberg_supported_mime_types();
```

##### kreuzberg_priority()

**Signature:**

```c
int32_t kreuzberg_priority();
```


---

### KreuzbergMetadata

Extraction result metadata.

Contains common fields applicable to all formats, format-specific metadata
via a discriminated union, and additional custom fields from postprocessors.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `title` | `const char**` | `NULL` | Document title |
| `subject` | `const char**` | `NULL` | Document subject or description |
| `authors` | `const char***` | `NULL` | Primary author(s) - always Vec for consistency |
| `keywords` | `const char***` | `NULL` | Keywords/tags - always Vec for consistency |
| `language` | `const char**` | `NULL` | Primary language (ISO 639 code) |
| `created_at` | `const char**` | `NULL` | Creation timestamp (ISO 8601 format) |
| `modified_at` | `const char**` | `NULL` | Last modification timestamp (ISO 8601 format) |
| `created_by` | `const char**` | `NULL` | User who created the document |
| `modified_by` | `const char**` | `NULL` | User who last modified the document |
| `pages` | `KreuzbergPageStructure*` | `NULL` | Page/slide/sheet structure with boundaries |
| `format` | `KreuzbergFormatMetadata*` | `KREUZBERG_KREUZBERG_PDF` | Format-specific metadata (discriminated union) Contains detailed metadata specific to the document format. Serializes with a `format_type` discriminator field. |
| `image_preprocessing` | `KreuzbergImagePreprocessingMetadata*` | `NULL` | Image preprocessing metadata (when OCR preprocessing was applied) |
| `json_schema` | `void**` | `NULL` | JSON schema (for structured data extraction) |
| `error` | `KreuzbergErrorMetadata*` | `NULL` | Error metadata (for batch operations) |
| `extraction_duration_ms` | `uint64_t*` | `NULL` | Extraction duration in milliseconds (for benchmarking). This field is populated by batch extraction to provide per-file timing information. It's `None` for single-file extraction (which uses external timing). |
| `category` | `const char**` | `NULL` | Document category (from frontmatter or classification). |
| `tags` | `const char***` | `NULL` | Document tags (from frontmatter). |
| `document_version` | `const char**` | `NULL` | Document version string (from frontmatter). |
| `abstract_text` | `const char**` | `NULL` | Abstract or summary text (from frontmatter). |
| `output_format` | `const char**` | `NULL` | Output format identifier (e.g., "markdown", "html", "text"). Set by the output format pipeline stage when format conversion is applied. Previously stored in `metadata.additional["output_format"]`. |
| `additional` | `KreuzbergAHashMap` | `NULL` | Additional custom fields from postprocessors. **Deprecated**: Prefer using typed fields on `ExtractionResult` and `Metadata` instead of inserting into this map. Typed fields provide better cross-language compatibility and type safety. This field will be removed in a future major version. This flattened map allows Python/TypeScript postprocessors to add arbitrary fields (entity extraction, keyword extraction, etc.). Fields are merged at the root level during serialization. Uses `Cow<'static, str>` keys so static string keys avoid allocation. |


---

### KreuzbergMetricsLayer

A `tower.Layer` that records service-level extraction metrics.

#### Methods

##### kreuzberg_layer()

**Signature:**

```c
KreuzbergService kreuzberg_layer(KreuzbergS inner);
```


---

### KreuzbergModelCache

#### Methods

##### kreuzberg_put()

Return a model to the cache for reuse.

If the cache already holds a model (e.g. from a concurrent caller),
the returned model is silently dropped.

**Signature:**

```c
void kreuzberg_put(KreuzbergT model);
```

##### kreuzberg_take()

Take the cached model if one exists, without creating a new one.

**Signature:**

```c
KreuzbergT* kreuzberg_take();
```


---

### KreuzbergNodeId

Deterministic node identifier.

Generated from a hash of `node_type + text + page`. The same document
always produces the same IDs, making them useful for diffing, caching,
and external references.

#### Methods

##### kreuzberg_generate()

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

```c
KreuzbergNodeId kreuzberg_generate(const char* node_type, const char* text, uint32_t page, uint32_t index);
```

##### kreuzberg_as_ref()

**Signature:**

```c
const char* kreuzberg_as_ref();
```

##### kreuzberg_fmt()

**Signature:**

```c
KreuzbergUnknown kreuzberg_fmt(KreuzbergFormatter f);
```


---

### KreuzbergNormalizeResult

Result of image normalization

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `rgb_data` | `const uint8_t*` | — | Processed RGB image data (height * width * 3 bytes) |
| `dimensions` | `KreuzbergUsizeUsize` | — | Image dimensions (width, height) |
| `metadata` | `KreuzbergImagePreprocessingMetadata` | — | Preprocessing metadata |


---

### KreuzbergNote

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `id` | `const char*` | — | Unique identifier |
| `note_type` | `KreuzbergNoteType` | — | Note type (note type) |
| `paragraphs` | `KreuzbergParagraph*` | — | Paragraphs |


---

### KreuzbergNumbersExtractor

Apple Numbers spreadsheet extractor.

Supports `.numbers` files (modern iWork format, 2013+).

Extracts cell string values and sheet names from the IWA container:
ZIP → Snappy → protobuf text fields. Output is formatted as plain text
with one text token per line (representing cell values and labels).

#### Methods

##### kreuzberg_default()

**Signature:**

```c
KreuzbergNumbersExtractor kreuzberg_default();
```

##### kreuzberg_name()

**Signature:**

```c
const char* kreuzberg_name();
```

##### kreuzberg_version()

**Signature:**

```c
const char* kreuzberg_version();
```

##### kreuzberg_initialize()

**Signature:**

```c
void kreuzberg_initialize();
```

##### kreuzberg_shutdown()

**Signature:**

```c
void kreuzberg_shutdown();
```

##### kreuzberg_description()

**Signature:**

```c
const char* kreuzberg_description();
```

##### kreuzberg_author()

**Signature:**

```c
const char* kreuzberg_author();
```

##### kreuzberg_extract_bytes()

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_extract_bytes(const uint8_t* content, const char* mime_type, KreuzbergExtractionConfig config);
```

##### kreuzberg_supported_mime_types()

**Signature:**

```c
const char** kreuzberg_supported_mime_types();
```

##### kreuzberg_priority()

**Signature:**

```c
int32_t kreuzberg_priority();
```


---

### KreuzbergOcrCache

#### Methods

##### kreuzberg_new()

**Signature:**

```c
KreuzbergOcrCache kreuzberg_new(const char* cache_dir);
```

##### kreuzberg_get_cached_result()

**Signature:**

```c
KreuzbergOcrExtractionResult* kreuzberg_get_cached_result(const char* image_hash, const char* backend, const char* config);
```

##### kreuzberg_set_cached_result()

**Signature:**

```c
void kreuzberg_set_cached_result(const char* image_hash, const char* backend, const char* config, KreuzbergOcrExtractionResult result);
```

##### kreuzberg_clear()

**Signature:**

```c
void kreuzberg_clear();
```

##### kreuzberg_get_stats()

**Signature:**

```c
KreuzbergOcrCacheStats kreuzberg_get_stats();
```


---

### KreuzbergOcrCacheStats

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `total_files` | `uintptr_t` | `NULL` | Total files |
| `total_size_mb` | `double` | `NULL` | Total size mb |


---

### KreuzbergOcrConfidence

Confidence scores for an OCR element.

Separates detection confidence (how confident that text exists at this location)
from recognition confidence (how confident about the actual text content).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `detection` | `double*` | `NULL` | Detection confidence: how confident the OCR engine is that text exists here. PaddleOCR provides this as `box_score`, Tesseract doesn't have a direct equivalent. Range: 0.0 to 1.0 (or None if not available). |
| `recognition` | `double` | — | Recognition confidence: how confident about the text content. Range: 0.0 to 1.0. |

#### Methods

##### kreuzberg_from_tesseract()

Create confidence from Tesseract's single confidence value.

Tesseract provides confidence as 0-100, which we normalize to 0.0-1.0.

**Signature:**

```c
KreuzbergOcrConfidence kreuzberg_from_tesseract(double confidence);
```

##### kreuzberg_from_paddle()

Create confidence from PaddleOCR scores.

Both scores should be in 0.0-1.0 range, but PaddleOCR may occasionally return
values slightly above 1.0 due to model calibration. This method clamps both
values to ensure they stay within the valid 0.0-1.0 range.

**Signature:**

```c
KreuzbergOcrConfidence kreuzberg_from_paddle(float box_score, float text_score);
```


---

### KreuzbergOcrConfig

OCR configuration.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `backend` | `const char*` | `NULL` | OCR backend: tesseract, easyocr, paddleocr |
| `language` | `const char*` | `NULL` | Language code (e.g., "eng", "deu") |
| `tesseract_config` | `KreuzbergTesseractConfig*` | `NULL` | Tesseract-specific configuration (optional) |
| `output_format` | `KreuzbergOutputFormat*` | `KREUZBERG_KREUZBERG_PLAIN` | Output format for OCR results (optional, for format conversion) |
| `paddle_ocr_config` | `void**` | `NULL` | PaddleOCR-specific configuration (optional, JSON passthrough) |
| `element_config` | `KreuzbergOcrElementConfig*` | `NULL` | OCR element extraction configuration |
| `quality_thresholds` | `KreuzbergOcrQualityThresholds*` | `NULL` | Quality thresholds for the native-text-to-OCR fallback decision. When None, uses compiled defaults (matching previous hardcoded behavior). |
| `pipeline` | `KreuzbergOcrPipelineConfig*` | `NULL` | Multi-backend OCR pipeline configuration. When set, enables weighted fallback across multiple OCR backends based on output quality. When None, uses the single `backend` field (same as today). |
| `auto_rotate` | `bool` | `false` | Enable automatic page rotation based on orientation detection. When enabled, uses Tesseract's `DetectOrientationScript()` to detect page orientation (0/90/180/270 degrees) before OCR. If the page is rotated with high confidence, the image is corrected before recognition. This is critical for handling rotated scanned documents. |
| `vlm_config` | `KreuzbergLlmConfig*` | `NULL` | VLM (Vision Language Model) OCR configuration. Required when `backend` is `"vlm"`. Uses liter-llm to send page images to a vision model for text extraction. |
| `vlm_prompt` | `const char**` | `NULL` | Custom Jinja2 prompt template for VLM OCR. When `None`, uses the default template. Available variables: - `{{ language }}` — The document language code (e.g., "eng", "deu"). |

#### Methods

##### kreuzberg_default()

**Signature:**

```c
KreuzbergOcrConfig kreuzberg_default();
```

##### kreuzberg_validate()

Validates that the configured backend is supported.

This method checks that the backend name is one of the supported OCR backends:
- tesseract
- easyocr
- paddleocr

Typos in backend names are caught at configuration validation time, not at runtime.
Also validates pipeline stage backends when a pipeline is configured.

**Signature:**

```c
void kreuzberg_validate();
```

##### kreuzberg_effective_thresholds()

Returns the effective quality thresholds, using configured values or defaults.

**Signature:**

```c
KreuzbergOcrQualityThresholds kreuzberg_effective_thresholds();
```

##### kreuzberg_effective_pipeline()

Returns the effective pipeline config.

- If `pipeline` is explicitly set, returns it.
- If `paddle-ocr` feature is compiled in and no explicit pipeline is set,
  auto-constructs a default pipeline: primary backend (priority 100) + paddleocr (priority 50).
- Otherwise returns `NULL` (single-backend mode, same as today).

**Signature:**

```c
KreuzbergOcrPipelineConfig* kreuzberg_effective_pipeline();
```


---

### KreuzbergOcrElement

A unified OCR element representing detected text with full metadata.

This is the primary type for structured OCR output, preserving all information
from both Tesseract and PaddleOCR backends.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `text` | `const char*` | — | The recognized text content. |
| `geometry` | `KreuzbergOcrBoundingGeometry` | — | Bounding geometry (rectangle or quadrilateral). |
| `confidence` | `KreuzbergOcrConfidence` | — | Confidence scores for detection and recognition. |
| `level` | `KreuzbergOcrElementLevel` | — | Hierarchical level (word, line, block, page). |
| `rotation` | `KreuzbergOcrRotation*` | `NULL` | Rotation information (if detected). |
| `page_number` | `uintptr_t` | — | Page number (1-indexed). |
| `parent_id` | `const char**` | `NULL` | Parent element ID for hierarchical relationships. Only used for Tesseract output which has word -> line -> block hierarchy. |
| `backend_metadata` | `void*` | — | Backend-specific metadata that doesn't fit the unified schema. |

#### Methods

##### kreuzberg_with_level()

Set the hierarchical level.

**Signature:**

```c
KreuzbergOcrElement kreuzberg_with_level(KreuzbergOcrElementLevel level);
```

##### kreuzberg_with_rotation()

Set rotation information.

**Signature:**

```c
KreuzbergOcrElement kreuzberg_with_rotation(KreuzbergOcrRotation rotation);
```

##### kreuzberg_with_page_number()

Set page number.

**Signature:**

```c
KreuzbergOcrElement kreuzberg_with_page_number(uintptr_t page_number);
```

##### kreuzberg_with_parent_id()

Set parent element ID.

**Signature:**

```c
KreuzbergOcrElement kreuzberg_with_parent_id(const char* parent_id);
```

##### kreuzberg_with_metadata()

Add backend-specific metadata.

**Signature:**

```c
KreuzbergOcrElement kreuzberg_with_metadata(const char* key, void* value);
```

##### kreuzberg_with_rotation_opt()

**Signature:**

```c
KreuzbergOcrElement kreuzberg_with_rotation_opt(KreuzbergOcrRotation rotation);
```


---

### KreuzbergOcrElementConfig

Configuration for OCR element extraction.

Controls how OCR elements are extracted and filtered.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `include_elements` | `bool` | `NULL` | Whether to include OCR elements in the extraction result. When true, the `ocr_elements` field in `ExtractionResult` will be populated. |
| `min_level` | `KreuzbergOcrElementLevel` | `KREUZBERG_KREUZBERG_LINE` | Minimum hierarchical level to include. Elements below this level (e.g., words when min_level is Line) will be excluded. |
| `min_confidence` | `double` | `NULL` | Minimum recognition confidence threshold (0.0-1.0). Elements with confidence below this threshold will be filtered out. |
| `build_hierarchy` | `bool` | `NULL` | Whether to build hierarchical relationships between elements. When true, `parent_id` fields will be populated based on spatial containment. Only meaningful for Tesseract output. |


---

### KreuzbergOcrExtractionResult

OCR extraction result.

Result of performing OCR on an image or scanned document,
including recognized text and detected tables.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `content` | `const char*` | — | Recognized text content |
| `mime_type` | `const char*` | — | Original MIME type of the processed image |
| `metadata` | `void*` | — | OCR processing metadata (confidence scores, language, etc.) |
| `tables` | `KreuzbergOcrTable*` | — | Tables detected and extracted via OCR |
| `ocr_elements` | `KreuzbergOcrElement**` | `NULL` | Structured OCR elements with bounding boxes and confidence scores. Available when TSV output is requested or table detection is enabled. |
| `internal_document` | `KreuzbergInternalDocument*` | `NULL` | Structured document produced from hOCR parsing. Carries paragraph structure, bounding boxes, and confidence scores that the flattened `content` string discards. |


---

### KreuzbergOcrMetadata

OCR processing metadata.

Captures information about OCR processing configuration and results.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `language` | `const char*` | — | OCR language code(s) used |
| `psm` | `int32_t` | — | Tesseract Page Segmentation Mode (PSM) |
| `output_format` | `const char*` | — | Output format (e.g., "text", "hocr") |
| `table_count` | `uintptr_t` | — | Number of tables detected |
| `table_rows` | `uintptr_t*` | `NULL` | Table rows |
| `table_cols` | `uintptr_t*` | `NULL` | Table cols |


---

### KreuzbergOcrPipelineConfig

Multi-backend OCR pipeline with quality-based fallback.

Backends are tried in priority order (highest first). After each backend
produces output, quality is evaluated. If it meets `quality_thresholds.pipeline_min_quality`,
the result is accepted. Otherwise the next backend is tried.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `stages` | `KreuzbergOcrPipelineStage*` | — | Ordered list of backends to try. Sorted by priority (descending) at runtime. |
| `quality_thresholds` | `KreuzbergOcrQualityThresholds` | — | Quality thresholds for deciding whether to accept a result or try the next backend. |


---

### KreuzbergOcrPipelineStage

A single backend stage in the OCR pipeline.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `backend` | `const char*` | — | Backend name: "tesseract", "paddleocr", "easyocr", or a custom registered name. |
| `priority` | `uint32_t` | — | Priority weight (higher = tried first). Stages are sorted by priority descending. |
| `language` | `const char**` | `NULL` | Language override for this stage (None = use parent OcrConfig.language). |
| `tesseract_config` | `KreuzbergTesseractConfig*` | `NULL` | Tesseract-specific config override for this stage. |
| `paddle_ocr_config` | `void**` | `NULL` | PaddleOCR-specific config for this stage. |
| `vlm_config` | `KreuzbergLlmConfig*` | `NULL` | VLM config override for this pipeline stage. |


---

### KreuzbergOcrProcessor

#### Methods

##### kreuzberg_new()

**Signature:**

```c
KreuzbergOcrProcessor kreuzberg_new(const char* cache_dir);
```

##### kreuzberg_process_image()

**Signature:**

```c
KreuzbergOcrExtractionResult kreuzberg_process_image(const uint8_t* image_bytes, KreuzbergTesseractConfig config);
```

##### kreuzberg_process_image_with_format()

Process an image with OCR and respect the output format from ExtractionConfig.

This variant allows specifying an output format (Plain, Markdown, Djot) which
affects how the OCR result's mime_type is set when markdown output is requested.

**Signature:**

```c
KreuzbergOcrExtractionResult kreuzberg_process_image_with_format(const uint8_t* image_bytes, KreuzbergTesseractConfig config, KreuzbergOutputFormat output_format);
```

##### kreuzberg_clear_cache()

**Signature:**

```c
void kreuzberg_clear_cache();
```

##### kreuzberg_get_cache_stats()

**Signature:**

```c
KreuzbergOcrCacheStats kreuzberg_get_cache_stats();
```

##### kreuzberg_process_image_file()

**Signature:**

```c
KreuzbergOcrExtractionResult kreuzberg_process_image_file(const char* file_path, KreuzbergTesseractConfig config);
```

##### kreuzberg_process_image_file_with_format()

Process a file with OCR and respect the output format from ExtractionConfig.

This variant allows specifying an output format (Plain, Markdown, Djot) which
affects how the OCR result's mime_type is set when markdown output is requested.

**Signature:**

```c
KreuzbergOcrExtractionResult kreuzberg_process_image_file_with_format(const char* file_path, KreuzbergTesseractConfig config, KreuzbergOutputFormat output_format);
```

##### kreuzberg_process_image_files_batch()

Process multiple image files in parallel using Rayon.

This method processes OCR operations in parallel across CPU cores for improved throughput.
Results are returned in the same order as the input file paths.

**Signature:**

```c
KreuzbergBatchItemResult* kreuzberg_process_image_files_batch(const char** file_paths, KreuzbergTesseractConfig config);
```


---

### KreuzbergOcrQualityThresholds

Quality thresholds for OCR fallback decisions and pipeline quality gating.

All fields default to the values that match the previous hardcoded behavior,
so `OcrQualityThresholds.default()` preserves existing semantics exactly.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `min_total_non_whitespace` | `uintptr_t` | `64` | Minimum total non-whitespace characters to consider text substantive. |
| `min_non_whitespace_per_page` | `double` | `32` | Minimum non-whitespace characters per page on average. |
| `min_meaningful_word_len` | `uintptr_t` | `4` | Minimum character count for a word to be "meaningful". |
| `min_meaningful_words` | `uintptr_t` | `3` | Minimum count of meaningful words before text is accepted. |
| `min_alnum_ratio` | `double` | `0.3` | Minimum alphanumeric ratio (non-whitespace chars that are alphanumeric). |
| `min_garbage_chars` | `uintptr_t` | `5` | Minimum Unicode replacement characters (U+FFFD) to trigger OCR fallback. |
| `max_fragmented_word_ratio` | `double` | `0.6` | Maximum fraction of short (1-2 char) words before text is considered fragmented. |
| `critical_fragmented_word_ratio` | `double` | `0.8` | Critical fragmentation threshold — triggers OCR regardless of meaningful words. Normal English text has ~20-30% short words. 80%+ is definitive garbage. |
| `min_avg_word_length` | `double` | `2` | Minimum average word length. Below this with enough words indicates garbled extraction. |
| `min_words_for_avg_length_check` | `uintptr_t` | `50` | Minimum word count before average word length check applies. |
| `min_consecutive_repeat_ratio` | `double` | `0.08` | Minimum consecutive word repetition ratio to detect column scrambling. |
| `min_words_for_repeat_check` | `uintptr_t` | `50` | Minimum word count before consecutive repetition check is applied. |
| `substantive_min_chars` | `uintptr_t` | `100` | Minimum character count for "substantive markdown" OCR skip gate. |
| `non_text_min_chars` | `uintptr_t` | `20` | Minimum character count for "non-text content" OCR skip gate. |
| `alnum_ws_ratio_threshold` | `double` | `0.4` | Alphanumeric+whitespace ratio threshold for skip decisions. |
| `pipeline_min_quality` | `double` | `0.5` | Minimum quality score (0.0-1.0) for a pipeline stage result to be accepted. If the result from a backend scores below this, try the next backend. |

#### Methods

##### kreuzberg_default()

**Signature:**

```c
KreuzbergOcrQualityThresholds kreuzberg_default();
```


---

### KreuzbergOcrRotation

Rotation information for an OCR element.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `angle_degrees` | `double` | — | Rotation angle in degrees (0, 90, 180, 270 for PaddleOCR). |
| `confidence` | `double*` | `NULL` | Confidence score for the rotation detection. |

#### Methods

##### kreuzberg_from_paddle()

Create rotation from PaddleOCR angle classification.

PaddleOCR uses angle_index (0-3) representing 0, 90, 180, 270 degrees.

**Errors:**

Returns an error if `angle_index` is not in the valid range (0-3).

**Signature:**

```c
KreuzbergOcrRotation kreuzberg_from_paddle(int32_t angle_index, float angle_score);
```


---

### KreuzbergOcrTable

Table detected via OCR.

Represents a table structure recognized during OCR processing.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `cells` | `const char***` | — | Table cells as a 2D vector (rows × columns) |
| `markdown` | `const char*` | — | Markdown representation of the table |
| `page_number` | `uintptr_t` | — | Page number where the table was found (1-indexed) |
| `bounding_box` | `KreuzbergOcrTableBoundingBox*` | `NULL` | Bounding box of the table in pixel coordinates (from OCR word positions). |


---

### KreuzbergOcrTableBoundingBox

Bounding box for an OCR-detected table in pixel coordinates.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `left` | `uint32_t` | — | Left x-coordinate (pixels) |
| `top` | `uint32_t` | — | Top y-coordinate (pixels) |
| `right` | `uint32_t` | — | Right x-coordinate (pixels) |
| `bottom` | `uint32_t` | — | Bottom y-coordinate (pixels) |


---

### KreuzbergOdtExtractor

High-performance ODT extractor using native Rust XML parsing.

This extractor provides:
- Fast text extraction via roxmltree XML parsing
- Comprehensive metadata extraction from meta.xml
- Table extraction with row and cell support
- Formatting preservation (bold, italic, strikeout)
- Support for headings, paragraphs, and special elements

#### Methods

##### kreuzberg_default()

**Signature:**

```c
KreuzbergOdtExtractor kreuzberg_default();
```

##### kreuzberg_name()

**Signature:**

```c
const char* kreuzberg_name();
```

##### kreuzberg_version()

**Signature:**

```c
const char* kreuzberg_version();
```

##### kreuzberg_initialize()

**Signature:**

```c
void kreuzberg_initialize();
```

##### kreuzberg_shutdown()

**Signature:**

```c
void kreuzberg_shutdown();
```

##### kreuzberg_description()

**Signature:**

```c
const char* kreuzberg_description();
```

##### kreuzberg_author()

**Signature:**

```c
const char* kreuzberg_author();
```

##### kreuzberg_extract_bytes()

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_extract_bytes(const uint8_t* content, const char* mime_type, KreuzbergExtractionConfig config);
```

##### kreuzberg_supported_mime_types()

**Signature:**

```c
const char** kreuzberg_supported_mime_types();
```

##### kreuzberg_priority()

**Signature:**

```c
int32_t kreuzberg_priority();
```


---

### KreuzbergOdtProperties

OpenDocument metadata from meta.xml

Contains metadata fields defined by the OASIS OpenDocument Format standard.
Uses Dublin Core elements (dc:) and OpenDocument meta elements (meta:).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `title` | `const char**` | `NULL` | Document title (dc:title) |
| `subject` | `const char**` | `NULL` | Document subject/topic (dc:subject) |
| `creator` | `const char**` | `NULL` | Current document creator/author (dc:creator) |
| `initial_creator` | `const char**` | `NULL` | Initial creator of the document (meta:initial-creator) |
| `keywords` | `const char**` | `NULL` | Keywords or tags (meta:keyword) |
| `description` | `const char**` | `NULL` | Document description (dc:description) |
| `date` | `const char**` | `NULL` | Current modification date (dc:date) |
| `creation_date` | `const char**` | `NULL` | Initial creation date (meta:creation-date) |
| `language` | `const char**` | `NULL` | Document language (dc:language) |
| `generator` | `const char**` | `NULL` | Generator/application that created the document (meta:generator) |
| `editing_duration` | `const char**` | `NULL` | Editing duration in ISO 8601 format (meta:editing-duration) |
| `editing_cycles` | `const char**` | `NULL` | Number of edits/revisions (meta:editing-cycles) |
| `page_count` | `int32_t*` | `NULL` | Document statistics - page count (meta:page-count) |
| `word_count` | `int32_t*` | `NULL` | Document statistics - word count (meta:word-count) |
| `character_count` | `int32_t*` | `NULL` | Document statistics - character count (meta:character-count) |
| `paragraph_count` | `int32_t*` | `NULL` | Document statistics - paragraph count (meta:paragraph-count) |
| `table_count` | `int32_t*` | `NULL` | Document statistics - table count (meta:table-count) |
| `image_count` | `int32_t*` | `NULL` | Document statistics - image count (meta:image-count) |


---

### KreuzbergOrgModeExtractor

Org Mode document extractor.

Provides native Rust-based Org Mode extraction using the `org` library,
extracting structured content and metadata.

#### Methods

##### kreuzberg_build_internal_document()

Build an `InternalDocument` from Org Mode source text.

Handles headings, paragraphs, lists, code blocks, tables, inline links,
and footnote references.

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_build_internal_document(const char* org_text);
```

##### kreuzberg_default()

**Signature:**

```c
KreuzbergOrgModeExtractor kreuzberg_default();
```

##### kreuzberg_name()

**Signature:**

```c
const char* kreuzberg_name();
```

##### kreuzberg_version()

**Signature:**

```c
const char* kreuzberg_version();
```

##### kreuzberg_initialize()

**Signature:**

```c
void kreuzberg_initialize();
```

##### kreuzberg_shutdown()

**Signature:**

```c
void kreuzberg_shutdown();
```

##### kreuzberg_description()

**Signature:**

```c
const char* kreuzberg_description();
```

##### kreuzberg_author()

**Signature:**

```c
const char* kreuzberg_author();
```

##### kreuzberg_extract_bytes()

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_extract_bytes(const uint8_t* content, const char* mime_type, KreuzbergExtractionConfig config);
```

##### kreuzberg_extract_file()

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_extract_file(const char* path, const char* mime_type, KreuzbergExtractionConfig config);
```

##### kreuzberg_supported_mime_types()

**Signature:**

```c
const char** kreuzberg_supported_mime_types();
```

##### kreuzberg_priority()

**Signature:**

```c
int32_t kreuzberg_priority();
```


---

### KreuzbergOrientationResult

Document orientation detection result.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `degrees` | `uint32_t` | — | Detected orientation in degrees (0, 90, 180, or 270). |
| `confidence` | `float` | — | Confidence score (0.0-1.0). |


---

### KreuzbergPageBoundary

Byte offset boundary for a page.

Tracks where a specific page's content starts and ends in the main content string,
enabling mapping from byte positions to page numbers. Offsets are guaranteed to be
at valid UTF-8 character boundaries when using standard String methods (push_str, push, etc.).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `byte_start` | `uintptr_t` | — | Byte offset where this page starts in the content string (UTF-8 valid boundary, inclusive) |
| `byte_end` | `uintptr_t` | — | Byte offset where this page ends in the content string (UTF-8 valid boundary, exclusive) |
| `page_number` | `uintptr_t` | — | Page number (1-indexed) |


---

### KreuzbergPageConfig

Page extraction and tracking configuration.

Controls how pages are extracted, tracked, and represented in the extraction results.
When `NULL`, page tracking is disabled.

Page range tracking in chunk metadata (first_page/last_page) is automatically enabled
when page boundaries are available and chunking is configured.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `extract_pages` | `bool` | `false` | Extract pages as separate array (ExtractionResult.pages) |
| `insert_page_markers` | `bool` | `false` | Insert page markers in main content string |
| `marker_format` | `const char*` | `"

<!-- PAGE {page_num} -->

"` | Page marker format (use {page_num} placeholder) Default: "\n\n<!-- PAGE {page_num} -->\n\n" |

#### Methods

##### kreuzberg_default()

**Signature:**

```c
KreuzbergPageConfig kreuzberg_default();
```


---

### KreuzbergPageContent

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
| `page_number` | `uintptr_t` | — | Page number (1-indexed) |
| `content` | `const char*` | — | Text content for this page |
| `tables` | `KreuzbergTable*` | — | Tables found on this page (uses Arc for memory efficiency) Serializes as Vec<Table> for JSON compatibility while maintaining Arc semantics in-memory for zero-copy sharing. |
| `images` | `KreuzbergExtractedImage*` | — | Images found on this page (uses Arc for memory efficiency) Serializes as Vec<ExtractedImage> for JSON compatibility while maintaining Arc semantics in-memory for zero-copy sharing. |
| `hierarchy` | `KreuzbergPageHierarchy*` | `NULL` | Hierarchy information for the page (when hierarchy extraction is enabled) Contains text hierarchy levels (H1-H6) extracted from the page content. |
| `is_blank` | `bool*` | `NULL` | Whether this page is blank (no meaningful text content) Determined during extraction based on text content analysis. A page is blank if it has fewer than 3 non-whitespace characters and contains no tables or images. |


---

### KreuzbergPageHierarchy

Page hierarchy structure containing heading levels and block information.

Used when PDF text hierarchy extraction is enabled. Contains hierarchical
blocks with heading levels (H1-H6) for semantic document structure.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `block_count` | `uintptr_t` | — | Number of hierarchy blocks on this page |
| `blocks` | `KreuzbergHierarchicalBlock*` | — | Hierarchical blocks with heading levels |


---

### KreuzbergPageInfo

Metadata for individual page/slide/sheet.

Captures per-page information including dimensions, content counts,
and visibility state (for presentations).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `number` | `uintptr_t` | — | Page number (1-indexed) |
| `title` | `const char**` | `NULL` | Page title (usually for presentations) |
| `dimensions` | `KreuzbergF64F64*` | `NULL` | Dimensions in points (PDF) or pixels (images): (width, height) |
| `image_count` | `uintptr_t*` | `NULL` | Number of images on this page |
| `table_count` | `uintptr_t*` | `NULL` | Number of tables on this page |
| `hidden` | `bool*` | `NULL` | Whether this page is hidden (e.g., in presentations) |
| `is_blank` | `bool*` | `NULL` | Whether this page is blank (no meaningful text, no images, no tables) A page is considered blank if it has fewer than 3 non-whitespace characters and contains no tables or images. This is useful for filtering out empty pages in scanned documents or PDFs with blank separator pages. |


---

### KreuzbergPageLayoutRegion

A detected layout region mapped to PDF coordinate space.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `class` | `KreuzbergLayoutClass` | — | Class (layout class) |
| `confidence` | `float` | — | Confidence |
| `bbox` | `KreuzbergPdfLayoutBBox` | — | Bbox (pdf layout b box) |


---

### KreuzbergPageLayoutResult

Layout detection results for a single page.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `page_index` | `uintptr_t` | — | Page index |
| `regions` | `KreuzbergPageLayoutRegion*` | — | Regions |
| `page_width_pts` | `float` | — | Page width pts |
| `page_height_pts` | `float` | — | Page height pts |
| `render_width_px` | `uint32_t` | — | Width of the rendered image used for layout detection (pixels). |
| `render_height_px` | `uint32_t` | — | Height of the rendered image used for layout detection (pixels). |


---

### KreuzbergPageMargins

Page margins in twips (twentieths of a point).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `top` | `int32_t*` | `NULL` | Top margin in twips. |
| `right` | `int32_t*` | `NULL` | Right margin in twips. |
| `bottom` | `int32_t*` | `NULL` | Bottom margin in twips. |
| `left` | `int32_t*` | `NULL` | Left margin in twips. |
| `header` | `int32_t*` | `NULL` | Header offset in twips. |
| `footer` | `int32_t*` | `NULL` | Footer offset in twips. |
| `gutter` | `int32_t*` | `NULL` | Gutter margin in twips. |

#### Methods

##### kreuzberg_to_points()

Convert all margins from twips to points.

Conversion factor: 1 twip = 1/20 point, or equivalently divide by 20.

**Signature:**

```c
KreuzbergPageMarginsPoints kreuzberg_to_points();
```


---

### KreuzbergPageMarginsPoints

Page margins converted to points (1/72 inch).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `top` | `double*` | `NULL` | Top |
| `right` | `double*` | `NULL` | Right |
| `bottom` | `double*` | `NULL` | Bottom |
| `left` | `double*` | `NULL` | Left |
| `header` | `double*` | `NULL` | Header |
| `footer` | `double*` | `NULL` | Footer |
| `gutter` | `double*` | `NULL` | Gutter |


---

### KreuzbergPageRenderOptions

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `target_dpi` | `int32_t` | `300` | Target dpi |
| `max_image_dimension` | `int32_t` | `65536` | Maximum image dimension |
| `auto_adjust_dpi` | `bool` | `true` | Auto adjust dpi |
| `min_dpi` | `int32_t` | `72` | Minimum dpi |
| `max_dpi` | `int32_t` | `600` | Maximum dpi |

#### Methods

##### kreuzberg_default()

**Signature:**

```c
KreuzbergPageRenderOptions kreuzberg_default();
```


---

### KreuzbergPageStructure

Unified page structure for documents.

Supports different page types (PDF pages, PPTX slides, Excel sheets)
with character offset boundaries for chunk-to-page mapping.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `total_count` | `uintptr_t` | — | Total number of pages/slides/sheets |
| `unit_type` | `KreuzbergPageUnitType` | — | Type of paginated unit |
| `boundaries` | `KreuzbergPageBoundary**` | `NULL` | Character offset boundaries for each page Maps character ranges in the extracted content to page numbers. Used for chunk page range calculation. |
| `pages` | `KreuzbergPageInfo**` | `NULL` | Detailed per-page metadata (optional, only when needed) |


---

### KreuzbergPageTiming

Timing breakdown for a single page.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `render_ms` | `double` | — | Time to render the PDF page to a raster image (amortized from batch render). |
| `preprocess_ms` | `double` | — | Time spent in image preprocessing (resize, normalize, tensor construction). |
| `onnx_ms` | `double` | — | Time for the ONNX model session.run() call (actual neural network inference). |
| `inference_ms` | `double` | — | Total model inference time (preprocess + onnx), as measured by the engine. |
| `postprocess_ms` | `double` | — | Time spent in postprocessing (confidence filtering, overlap resolution). |
| `mapping_ms` | `double` | — | Time to map pixel-space bounding boxes to PDF coordinate space. |


---

### KreuzbergPagesExtractor

Apple Pages document extractor.

Supports `.pages` files (modern iWork format, 2013+).

Extracts all text content from the document by parsing the IWA
(iWork Archive) container: ZIP → Snappy → protobuf text fields.

#### Methods

##### kreuzberg_default()

**Signature:**

```c
KreuzbergPagesExtractor kreuzberg_default();
```

##### kreuzberg_name()

**Signature:**

```c
const char* kreuzberg_name();
```

##### kreuzberg_version()

**Signature:**

```c
const char* kreuzberg_version();
```

##### kreuzberg_initialize()

**Signature:**

```c
void kreuzberg_initialize();
```

##### kreuzberg_shutdown()

**Signature:**

```c
void kreuzberg_shutdown();
```

##### kreuzberg_description()

**Signature:**

```c
const char* kreuzberg_description();
```

##### kreuzberg_author()

**Signature:**

```c
const char* kreuzberg_author();
```

##### kreuzberg_extract_bytes()

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_extract_bytes(const uint8_t* content, const char* mime_type, KreuzbergExtractionConfig config);
```

##### kreuzberg_supported_mime_types()

**Signature:**

```c
const char** kreuzberg_supported_mime_types();
```

##### kreuzberg_priority()

**Signature:**

```c
int32_t kreuzberg_priority();
```


---

### KreuzbergPanicContext

Context information captured when a panic occurs.

This struct stores detailed information about where and when a panic happened,
enabling better error reporting across FFI boundaries.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `file` | `const char*` | — | Source file where the panic occurred |
| `line` | `uint32_t` | — | Line number where the panic occurred |
| `function` | `const char*` | — | Function name where the panic occurred |
| `message` | `const char*` | — | Panic message extracted from the panic payload |
| `timestamp` | `KreuzbergSystemTime` | — | Timestamp when the panic was captured |

#### Methods

##### kreuzberg_format()

Formats the panic context as a human-readable string.

**Signature:**

```c
const char* kreuzberg_format();
```


---

### KreuzbergParaText

Plain text content decoded from a ParaText record (tag 0x43).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `content` | `const char*` | — | The extracted text content |

#### Methods

##### kreuzberg_from_record()

Decode a ParaText record from raw bytes.

The data field of a TAG_PARA_TEXT record is a sequence of UTF-16LE code
units.  Control characters < 0x0020 are mapped to whitespace or skipped;
characters in the private-use range 0xF020–0xF07F (HWP internal controls)
are discarded.

**Signature:**

```c
KreuzbergParaText kreuzberg_from_record(KreuzbergRecord record);
```


---

### KreuzbergParagraph

A single paragraph; may or may not carry a text payload.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `text` | `KreuzbergParaText*` | `NULL` | Text (para text) |


---

### KreuzbergParagraphProperties

Paragraph-level formatting properties (alignment, spacing, indentation, etc.).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `alignment` | `const char**` | `NULL` | `"left"`, `"center"`, `"right"`, `"both"` (justified). |
| `spacing_before` | `int32_t*` | `NULL` | Spacing before paragraph in twips. |
| `spacing_after` | `int32_t*` | `NULL` | Spacing after paragraph in twips. |
| `spacing_line` | `int32_t*` | `NULL` | Line spacing in twips or 240ths of a line. |
| `spacing_line_rule` | `const char**` | `NULL` | Line spacing rule: "auto", "exact", or "atLeast". |
| `indent_left` | `int32_t*` | `NULL` | Left indentation in twips. |
| `indent_right` | `int32_t*` | `NULL` | Right indentation in twips. |
| `indent_first_line` | `int32_t*` | `NULL` | First-line indentation in twips. |
| `indent_hanging` | `int32_t*` | `NULL` | Hanging indentation in twips. |
| `outline_level` | `uint8_t*` | `NULL` | Outline level 0-8 for heading levels. |
| `keep_next` | `bool*` | `NULL` | Keep with next paragraph on same page. |
| `keep_lines` | `bool*` | `NULL` | Keep all lines of paragraph on same page. |
| `page_break_before` | `bool*` | `NULL` | Force page break before paragraph. |
| `widow_control` | `bool*` | `NULL` | Prevent widow/orphan lines. |
| `suppress_auto_hyphens` | `bool*` | `NULL` | Suppress automatic hyphenation. |
| `bidi` | `bool*` | `NULL` | Right-to-left paragraph direction. |
| `shading_fill` | `const char**` | `NULL` | Background color hex value (from w:shd w:fill). |
| `shading_val` | `const char**` | `NULL` | Shading pattern value (from w:shd w:val). |
| `border_top` | `const char**` | `NULL` | Top border style (from w:pBdr/w:top w:val). |
| `border_bottom` | `const char**` | `NULL` | Bottom border style (from w:pBdr/w:bottom w:val). |
| `border_left` | `const char**` | `NULL` | Left border style (from w:pBdr/w:left w:val). |
| `border_right` | `const char**` | `NULL` | Right border style (from w:pBdr/w:right w:val). |


---

### KreuzbergPdfAnnotation

A PDF annotation extracted from a document page.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `annotation_type` | `KreuzbergPdfAnnotationType` | — | The type of annotation. |
| `content` | `const char**` | `NULL` | Text content of the annotation (e.g., comment text, link URL). |
| `page_number` | `uintptr_t` | — | Page number where the annotation appears (1-indexed). |
| `bounding_box` | `KreuzbergBoundingBox*` | `NULL` | Bounding box of the annotation on the page. |


---

### KreuzbergPdfConfig

PDF-specific configuration.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `backend` | `KreuzbergPdfBackend` | `KREUZBERG_KREUZBERG_PDFIUM` | PDF extraction backend. Default: `Pdfium`. |
| `extract_images` | `bool` | `false` | Extract images from PDF |
| `passwords` | `const char***` | `NULL` | List of passwords to try when opening encrypted PDFs |
| `extract_metadata` | `bool` | `true` | Extract PDF metadata |
| `hierarchy` | `KreuzbergHierarchyConfig*` | `NULL` | Hierarchy extraction configuration (None = hierarchy extraction disabled) |
| `extract_annotations` | `bool` | `false` | Extract PDF annotations (text notes, highlights, links, stamps). Default: false |
| `top_margin_fraction` | `float*` | `NULL` | Top margin fraction (0.0–1.0) of page height to exclude headers/running heads. Default: 0.06 (6%) |
| `bottom_margin_fraction` | `float*` | `NULL` | Bottom margin fraction (0.0–1.0) of page height to exclude footers/page numbers. Default: 0.05 (5%) |
| `allow_single_column_tables` | `bool` | `false` | Allow single-column pseudo tables in extraction results. By default, tables with fewer than 2 columns (layout-guided) or 3 columns (heuristic) are rejected. When `True`, the minimum column count is relaxed to 1, allowing single-column structured data (glossaries, itemized lists) to be emitted as tables. Other quality filters (density, sparsity, prose detection) still apply. |

#### Methods

##### kreuzberg_default()

**Signature:**

```c
KreuzbergPdfConfig kreuzberg_default();
```


---

### KreuzbergPdfExtractionMetadata

Complete PDF extraction metadata including common and PDF-specific fields.

This struct combines common document fields (title, authors, dates) with
PDF-specific metadata and optional page structure information. It is returned
by `extract_metadata_from_document()` when page boundaries are provided.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `title` | `const char**` | `NULL` | Document title |
| `subject` | `const char**` | `NULL` | Document subject or description |
| `authors` | `const char***` | `NULL` | Document authors (parsed from PDF Author field) |
| `keywords` | `const char***` | `NULL` | Document keywords (parsed from PDF Keywords field) |
| `created_at` | `const char**` | `NULL` | Creation timestamp (ISO 8601 format) |
| `modified_at` | `const char**` | `NULL` | Last modification timestamp (ISO 8601 format) |
| `created_by` | `const char**` | `NULL` | Application or user that created the document |
| `pdf_specific` | `KreuzbergPdfMetadata` | — | PDF-specific metadata |
| `page_structure` | `KreuzbergPageStructure*` | `NULL` | Page structure with boundaries and optional per-page metadata |


---

### KreuzbergPdfExtractor

PDF document extractor using pypdfium2 and playa-pdf.

#### Methods

##### kreuzberg_default()

**Signature:**

```c
KreuzbergPdfExtractor kreuzberg_default();
```

##### kreuzberg_name()

**Signature:**

```c
const char* kreuzberg_name();
```

##### kreuzberg_version()

**Signature:**

```c
const char* kreuzberg_version();
```

##### kreuzberg_initialize()

**Signature:**

```c
void kreuzberg_initialize();
```

##### kreuzberg_shutdown()

**Signature:**

```c
void kreuzberg_shutdown();
```

##### kreuzberg_extract_bytes()

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_extract_bytes(const uint8_t* content, const char* mime_type, KreuzbergExtractionConfig config);
```

##### kreuzberg_supported_mime_types()

**Signature:**

```c
const char** kreuzberg_supported_mime_types();
```


---

### KreuzbergPdfImage

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `page_number` | `uintptr_t` | — | Page number |
| `image_index` | `uintptr_t` | — | Image index |
| `width` | `int64_t` | — | Width |
| `height` | `int64_t` | — | Height |
| `color_space` | `const char**` | `NULL` | Color space |
| `bits_per_component` | `int64_t*` | `NULL` | Bits per component |
| `filters` | `const char**` | — | Original PDF stream filters (e.g. `["FlateDecode"]`, `["DCTDecode"]`). |
| `data` | `const uint8_t*` | — | The decoded image bytes in a standard format (JPEG, PNG, etc.). |
| `decoded_format` | `const char*` | — | The format of `data` after decoding: `"jpeg"`, `"png"`, `"jpeg2000"`, `"ccitt"`, or `"raw"`. |


---

### KreuzbergPdfImageExtractor

#### Methods

##### kreuzberg_new()

**Signature:**

```c
KreuzbergPdfImageExtractor kreuzberg_new(const uint8_t* pdf_bytes);
```

##### kreuzberg_new_with_password()

**Signature:**

```c
KreuzbergPdfImageExtractor kreuzberg_new_with_password(const uint8_t* pdf_bytes, const char* password);
```

##### kreuzberg_extract_images()

**Signature:**

```c
KreuzbergPdfImage* kreuzberg_extract_images();
```

##### kreuzberg_extract_images_from_page()

**Signature:**

```c
KreuzbergPdfImage* kreuzberg_extract_images_from_page(uint32_t page_number);
```

##### kreuzberg_get_image_count()

**Signature:**

```c
uintptr_t kreuzberg_get_image_count();
```


---

### KreuzbergPdfLayoutBBox

Bounding box in PDF coordinate space (points, y=0 at bottom of page).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `left` | `float` | — | Left |
| `bottom` | `float` | — | Bottom |
| `right` | `float` | — | Right |
| `top` | `float` | — | Top |

#### Methods

##### kreuzberg_width()

**Signature:**

```c
float kreuzberg_width();
```

##### kreuzberg_height()

**Signature:**

```c
float kreuzberg_height();
```


---

### KreuzbergPdfMetadata

PDF-specific metadata.

Contains metadata fields specific to PDF documents that are not in the common
`Metadata` structure. Common fields like title, authors, keywords, and dates
are now at the `Metadata` level.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `pdf_version` | `const char**` | `NULL` | PDF version (e.g., "1.7", "2.0") |
| `producer` | `const char**` | `NULL` | PDF producer (application that created the PDF) |
| `is_encrypted` | `bool*` | `NULL` | Whether the PDF is encrypted/password-protected |
| `width` | `int64_t*` | `NULL` | First page width in points (1/72 inch) |
| `height` | `int64_t*` | `NULL` | First page height in points (1/72 inch) |
| `page_count` | `uintptr_t*` | `NULL` | Total number of pages in the PDF document |


---

### KreuzbergPdfPageIterator

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

##### kreuzberg_new()

Create an iterator from raw PDF bytes.

Validates the PDF and determines the page count. The PDF bytes are
owned by the iterator — the file is not re-read from disk.

**Errors:**

Returns an error if the PDF is invalid or password-protected without
the correct password.

**Signature:**

```c
KreuzbergPdfPageIterator kreuzberg_new(const uint8_t* pdf_bytes, int32_t dpi, const char* password);
```

##### kreuzberg_from_file()

Create an iterator from a file path.

Reads the file into memory once. Subsequent iterations render from
the owned bytes without re-reading the file.

**Errors:**

Returns an error if the file cannot be read or the PDF is invalid.

**Signature:**

```c
KreuzbergPdfPageIterator kreuzberg_from_file(KreuzbergPath path, int32_t dpi, const char* password);
```

##### kreuzberg_page_count()

Number of pages in the PDF.

**Signature:**

```c
uintptr_t kreuzberg_page_count();
```

##### kreuzberg_next()

**Signature:**

```c
KreuzbergItem* kreuzberg_next();
```

##### kreuzberg_size_hint()

**Signature:**

```c
KreuzbergUsizeOptionUsize kreuzberg_size_hint();
```


---

### KreuzbergPdfRenderer

#### Methods

##### kreuzberg_new()

**Signature:**

```c
KreuzbergPdfRenderer kreuzberg_new();
```


---

### KreuzbergPdfTextExtractor

#### Methods

##### kreuzberg_new()

**Signature:**

```c
KreuzbergPdfTextExtractor kreuzberg_new();
```


---

### KreuzbergPdfUnifiedExtractionResult

Result type for unified PDF text and metadata extraction.

Contains text, optional page boundaries, optional per-page content, and metadata.


---

### KreuzbergPlainTextExtractor

Plain text extractor.

Extracts content from plain text files (.txt).

#### Methods

##### kreuzberg_default()

**Signature:**

```c
KreuzbergPlainTextExtractor kreuzberg_default();
```

##### kreuzberg_name()

**Signature:**

```c
const char* kreuzberg_name();
```

##### kreuzberg_version()

**Signature:**

```c
const char* kreuzberg_version();
```

##### kreuzberg_initialize()

**Signature:**

```c
void kreuzberg_initialize();
```

##### kreuzberg_shutdown()

**Signature:**

```c
void kreuzberg_shutdown();
```

##### kreuzberg_description()

**Signature:**

```c
const char* kreuzberg_description();
```

##### kreuzberg_author()

**Signature:**

```c
const char* kreuzberg_author();
```

##### kreuzberg_extract_bytes()

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_extract_bytes(const uint8_t* content, const char* mime_type, KreuzbergExtractionConfig config);
```

##### kreuzberg_supported_mime_types()

**Signature:**

```c
const char** kreuzberg_supported_mime_types();
```

##### kreuzberg_priority()

**Signature:**

```c
int32_t kreuzberg_priority();
```


---

### KreuzbergPlugin

Base trait that all plugins must implement.

This trait provides common functionality for plugin lifecycle management,
identification, and metadata.

# Thread Safety

All plugins must be `Send + Sync` to support concurrent usage across threads.

#### Methods

##### kreuzberg_name()

Returns the unique name/identifier for this plugin.

The name should be:
- Unique across all plugins
- Lowercase with hyphens (e.g., "my-custom-plugin")
- URL-safe characters only

**Signature:**

```c
const char* kreuzberg_name();
```

##### kreuzberg_version()

Returns the semantic version of this plugin.

Should follow semver format: `MAJOR.MINOR.PATCH`

**Signature:**

```c
const char* kreuzberg_version();
```

##### kreuzberg_initialize()

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

```c
void kreuzberg_initialize();
```

##### kreuzberg_shutdown()

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

```c
void kreuzberg_shutdown();
```

##### kreuzberg_description()

Optional plugin description for debugging and logging.

Defaults to empty string if not overridden.

**Signature:**

```c
const char* kreuzberg_description();
```

##### kreuzberg_author()

Optional plugin author information.

Defaults to empty string if not overridden.

**Signature:**

```c
const char* kreuzberg_author();
```


---

### KreuzbergPluginHealthStatus

Plugin health status information.

Contains diagnostic information about registered plugins for each type.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `ocr_backends_count` | `uintptr_t` | — | Number of registered OCR backends |
| `ocr_backends` | `const char**` | — | Names of registered OCR backends |
| `extractors_count` | `uintptr_t` | — | Number of registered document extractors |
| `extractors` | `const char**` | — | Names of registered document extractors |
| `post_processors_count` | `uintptr_t` | — | Number of registered post-processors |
| `post_processors` | `const char**` | — | Names of registered post-processors |
| `validators_count` | `uintptr_t` | — | Number of registered validators |
| `validators` | `const char**` | — | Names of registered validators |

#### Methods

##### kreuzberg_check()

Check plugin health and return status.

This function reads all plugin registries and collects information
about registered plugins. It logs warnings if critical plugins are missing.

**Returns:**

`PluginHealthStatus` with counts and names of all registered plugins.

**Signature:**

```c
KreuzbergPluginHealthStatus kreuzberg_check();
```


---

### KreuzbergPool

#### Methods

##### kreuzberg_acquire()

Acquire an object from the pool or create a new one if empty.

**Returns:**

A `PoolGuard<T>` that will return the object to the pool when dropped.

**Panics:**

Panics if the mutex is already locked by the current thread (deadlock).
This is a safety mechanism provided by parking_lot to prevent subtle bugs.

**Signature:**

```c
KreuzbergPoolGuard kreuzberg_acquire();
```

##### kreuzberg_size()

Get the current number of objects in the pool.

**Signature:**

```c
uintptr_t kreuzberg_size();
```

##### kreuzberg_clear()

Clear the pool, discarding all pooled objects.

**Signature:**

```c
void kreuzberg_clear();
```


---

### KreuzbergPoolMetrics

Metrics tracking for pool allocations and reuse patterns.

These metrics help identify pool efficiency and allocation patterns.
Only available when the `pool-metrics` feature is enabled.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `total_acquires` | `KreuzbergAtomicUsize` | `NULL` | Total number of acquire calls on this pool |
| `total_cache_hits` | `KreuzbergAtomicUsize` | `NULL` | Total number of cache hits (reused objects from pool) |
| `peak_items_stored` | `KreuzbergAtomicUsize` | `NULL` | Peak number of objects stored simultaneously in this pool |
| `total_creations` | `KreuzbergAtomicUsize` | `NULL` | Total number of objects created by the factory function |

#### Methods

##### kreuzberg_hit_rate()

Calculate the cache hit rate as a percentage (0.0-100.0).

**Signature:**

```c
double kreuzberg_hit_rate();
```

##### kreuzberg_snapshot()

Get all metrics as a struct for reporting.

**Signature:**

```c
KreuzbergPoolMetricsSnapshot kreuzberg_snapshot();
```

##### kreuzberg_reset()

Reset all metrics to zero.

**Signature:**

```c
void kreuzberg_reset();
```

##### kreuzberg_default()

**Signature:**

```c
KreuzbergPoolMetrics kreuzberg_default();
```


---

### KreuzbergPoolMetricsSnapshot

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `total_acquires` | `uintptr_t` | — | Total acquires |
| `total_cache_hits` | `uintptr_t` | — | Total cache hits |
| `peak_items_stored` | `uintptr_t` | — | Peak items stored |
| `total_creations` | `uintptr_t` | — | Total creations |


---

### KreuzbergPoolSizeHint

Hint for optimal pool sizing based on document characteristics.

This struct contains the estimated sizes for string and byte buffers
that should be allocated in the pool to handle extraction without
excessive reallocation.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `estimated_total_size` | `uintptr_t` | — | Estimated total string buffer pool size in bytes |
| `string_buffer_count` | `uintptr_t` | — | Recommended number of string buffers |
| `string_buffer_capacity` | `uintptr_t` | — | Recommended capacity per string buffer in bytes |
| `byte_buffer_count` | `uintptr_t` | — | Recommended number of byte buffers |
| `byte_buffer_capacity` | `uintptr_t` | — | Recommended capacity per byte buffer in bytes |

#### Methods

##### kreuzberg_estimated_string_pool_memory()

Calculate the estimated string pool memory in bytes.

This is the total estimated memory for all string buffers.

**Signature:**

```c
uintptr_t kreuzberg_estimated_string_pool_memory();
```

##### kreuzberg_estimated_byte_pool_memory()

Calculate the estimated byte pool memory in bytes.

This is the total estimated memory for all byte buffers.

**Signature:**

```c
uintptr_t kreuzberg_estimated_byte_pool_memory();
```

##### kreuzberg_total_pool_memory()

Calculate the total estimated pool memory in bytes.

This includes both string and byte buffer pools.

**Signature:**

```c
uintptr_t kreuzberg_total_pool_memory();
```


---

### KreuzbergPosition

Horizontal or vertical position.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `relative_from` | `const char*` | — | Relative from |
| `offset` | `int64_t*` | `NULL` | Offset |


---

### KreuzbergPostProcessorConfig

Post-processor configuration.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `enabled` | `bool` | `true` | Enable post-processors |
| `enabled_processors` | `const char***` | `NULL` | Whitelist of processor names to run (None = all enabled) |
| `disabled_processors` | `const char***` | `NULL` | Blacklist of processor names to skip (None = none disabled) |
| `enabled_set` | `KreuzbergAHashSet*` | `NULL` | Pre-computed AHashSet for O(1) enabled processor lookup |
| `disabled_set` | `KreuzbergAHashSet*` | `NULL` | Pre-computed AHashSet for O(1) disabled processor lookup |

#### Methods

##### kreuzberg_build_lookup_sets()

Pre-compute HashSets for O(1) processor name lookups.

This method converts the enabled/disabled processor Vec to HashSet
for constant-time lookups in the pipeline.

**Signature:**

```c
void kreuzberg_build_lookup_sets();
```

##### kreuzberg_default()

**Signature:**

```c
KreuzbergPostProcessorConfig kreuzberg_default();
```


---

### KreuzbergPptExtractionResult

Result of PPT text extraction.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `text` | `const char*` | — | Extracted text content, with slides separated by double newlines. |
| `slide_count` | `uintptr_t` | — | Number of slides found. |
| `metadata` | `KreuzbergPptMetadata` | — | Document metadata. |
| `speaker_notes` | `const char**` | — | Speaker notes text per slide (if available). |


---

### KreuzbergPptExtractor

Native PPT extractor using OLE/CFB parsing.

This extractor handles PowerPoint 97-2003 binary (.ppt) files without
requiring LibreOffice, providing ~50x faster extraction.

#### Methods

##### kreuzberg_default()

**Signature:**

```c
KreuzbergPptExtractor kreuzberg_default();
```

##### kreuzberg_name()

**Signature:**

```c
const char* kreuzberg_name();
```

##### kreuzberg_version()

**Signature:**

```c
const char* kreuzberg_version();
```

##### kreuzberg_initialize()

**Signature:**

```c
void kreuzberg_initialize();
```

##### kreuzberg_shutdown()

**Signature:**

```c
void kreuzberg_shutdown();
```

##### kreuzberg_description()

**Signature:**

```c
const char* kreuzberg_description();
```

##### kreuzberg_author()

**Signature:**

```c
const char* kreuzberg_author();
```

##### kreuzberg_extract_bytes()

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_extract_bytes(const uint8_t* content, const char* mime_type, KreuzbergExtractionConfig config);
```

##### kreuzberg_supported_mime_types()

**Signature:**

```c
const char** kreuzberg_supported_mime_types();
```

##### kreuzberg_priority()

**Signature:**

```c
int32_t kreuzberg_priority();
```


---

### KreuzbergPptMetadata

Metadata extracted from PPT files.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `title` | `const char**` | `NULL` | Title |
| `subject` | `const char**` | `NULL` | Subject |
| `author` | `const char**` | `NULL` | Author |
| `last_author` | `const char**` | `NULL` | Last author |


---

### KreuzbergPptxAppProperties

Application properties from docProps/app.xml for PPTX

Contains PowerPoint-specific document metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `application` | `const char**` | `NULL` | Application name (e.g., "Microsoft Office PowerPoint") |
| `app_version` | `const char**` | `NULL` | Application version |
| `total_time` | `int32_t*` | `NULL` | Total editing time in minutes |
| `company` | `const char**` | `NULL` | Company name |
| `doc_security` | `int32_t*` | `NULL` | Document security level |
| `scale_crop` | `bool*` | `NULL` | Scale crop flag |
| `links_up_to_date` | `bool*` | `NULL` | Links up to date flag |
| `shared_doc` | `bool*` | `NULL` | Shared document flag |
| `hyperlinks_changed` | `bool*` | `NULL` | Hyperlinks changed flag |
| `slides` | `int32_t*` | `NULL` | Number of slides |
| `notes` | `int32_t*` | `NULL` | Number of notes |
| `hidden_slides` | `int32_t*` | `NULL` | Number of hidden slides |
| `multimedia_clips` | `int32_t*` | `NULL` | Number of multimedia clips |
| `presentation_format` | `const char**` | `NULL` | Presentation format (e.g., "Widescreen", "Standard") |
| `slide_titles` | `const char**` | `NULL` | Slide titles |


---

### KreuzbergPptxExtractionOptions

Options for PPTX content extraction.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `extract_images` | `bool` | `true` | Whether to extract embedded images. |
| `page_config` | `KreuzbergPageConfig*` | `NULL` | Optional page configuration for boundary tracking. |
| `plain` | `bool` | `false` | Whether to output plain text (no markdown). |
| `include_structure` | `bool` | `false` | Whether to build the `DocumentStructure` tree. |
| `inject_placeholders` | `bool` | `true` | Whether to emit `![alt](target)` references in markdown output. |

#### Methods

##### kreuzberg_default()

**Signature:**

```c
KreuzbergPptxExtractionOptions kreuzberg_default();
```


---

### KreuzbergPptxExtractionResult

PowerPoint (PPTX) extraction result.

Contains extracted slide content, metadata, and embedded images/tables.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `content` | `const char*` | — | Extracted text content from all slides |
| `metadata` | `KreuzbergPptxMetadata` | — | Presentation metadata |
| `slide_count` | `uintptr_t` | — | Total number of slides |
| `image_count` | `uintptr_t` | — | Total number of embedded images |
| `table_count` | `uintptr_t` | — | Total number of tables |
| `images` | `KreuzbergExtractedImage*` | — | Extracted images from the presentation |
| `page_structure` | `KreuzbergPageStructure*` | `NULL` | Slide structure with boundaries (when page tracking is enabled) |
| `page_contents` | `KreuzbergPageContent**` | `NULL` | Per-slide content (when page tracking is enabled) |
| `document` | `KreuzbergDocumentStructure*` | `NULL` | Structured document representation |
| `hyperlinks` | `KreuzbergStringOptionString*` | — | Hyperlinks discovered in slides as (url, optional_label) pairs. |
| `office_metadata` | `void*` | — | Office metadata extracted from docProps/core.xml and docProps/app.xml. Contains keys like "title", "author", "created_by", "subject", "keywords", "modified_by", "created_at", "modified_at", etc. |


---

### KreuzbergPptxExtractor

PowerPoint presentation extractor.

Supports: .pptx, .pptm, .ppsx

#### Methods

##### kreuzberg_default()

**Signature:**

```c
KreuzbergPptxExtractor kreuzberg_default();
```

##### kreuzberg_name()

**Signature:**

```c
const char* kreuzberg_name();
```

##### kreuzberg_version()

**Signature:**

```c
const char* kreuzberg_version();
```

##### kreuzberg_initialize()

**Signature:**

```c
void kreuzberg_initialize();
```

##### kreuzberg_shutdown()

**Signature:**

```c
void kreuzberg_shutdown();
```

##### kreuzberg_extract_bytes()

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_extract_bytes(const uint8_t* content, const char* mime_type, KreuzbergExtractionConfig config);
```

##### kreuzberg_extract_file()

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_extract_file(const char* path, const char* mime_type, KreuzbergExtractionConfig config);
```

##### kreuzberg_supported_mime_types()

**Signature:**

```c
const char** kreuzberg_supported_mime_types();
```

##### kreuzberg_priority()

**Signature:**

```c
int32_t kreuzberg_priority();
```


---

### KreuzbergPptxMetadata

PowerPoint presentation metadata.

Extracted from PPTX files containing slide counts and presentation details.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `slide_count` | `uintptr_t` | — | Total number of slides in the presentation |
| `slide_names` | `const char**` | — | Names of slides (if available) |
| `image_count` | `uintptr_t*` | `NULL` | Number of embedded images |
| `table_count` | `uintptr_t*` | `NULL` | Number of tables |


---

### KreuzbergProcessingWarning

A non-fatal warning from a processing pipeline stage.

Captures errors from optional features that don't prevent extraction
but may indicate degraded results.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `source` | `KreuzbergStr` | — | The pipeline stage or feature that produced this warning (e.g., "embedding", "chunking", "language_detection", "output_format"). |
| `message` | `KreuzbergStr` | — | Human-readable description of what went wrong. |


---

### KreuzbergPstExtractor

PST file extractor.

Supports: .pst (Microsoft Outlook Personal Folders)

#### Methods

##### kreuzberg_default()

**Signature:**

```c
KreuzbergPstExtractor kreuzberg_default();
```

##### kreuzberg_name()

**Signature:**

```c
const char* kreuzberg_name();
```

##### kreuzberg_version()

**Signature:**

```c
const char* kreuzberg_version();
```

##### kreuzberg_initialize()

**Signature:**

```c
void kreuzberg_initialize();
```

##### kreuzberg_shutdown()

**Signature:**

```c
void kreuzberg_shutdown();
```

##### kreuzberg_extract_sync()

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_extract_sync(const uint8_t* content, const char* mime_type, KreuzbergExtractionConfig config);
```

##### kreuzberg_extract_bytes()

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_extract_bytes(const uint8_t* content, const char* mime_type, KreuzbergExtractionConfig config);
```

##### kreuzberg_supported_mime_types()

**Signature:**

```c
const char** kreuzberg_supported_mime_types();
```

##### kreuzberg_priority()

**Signature:**

```c
int32_t kreuzberg_priority();
```

##### kreuzberg_as_sync_extractor()

**Signature:**

```c
KreuzbergSyncExtractor* kreuzberg_as_sync_extractor();
```


---

### KreuzbergPstMetadata

Outlook PST archive metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `message_count` | `uintptr_t` | `NULL` | Number of message |


---

### KreuzbergQualityProcessor

Post-processor that calculates quality score and cleans text.

This processor:
- Runs in the Early processing stage
- Calculates quality score when `config.enable_quality_processing` is true
- Stores quality score in `metadata.additional["quality_score"]`
- Cleans and normalizes extracted text

#### Methods

##### kreuzberg_name()

**Signature:**

```c
const char* kreuzberg_name();
```

##### kreuzberg_version()

**Signature:**

```c
const char* kreuzberg_version();
```

##### kreuzberg_initialize()

**Signature:**

```c
void kreuzberg_initialize();
```

##### kreuzberg_shutdown()

**Signature:**

```c
void kreuzberg_shutdown();
```

##### kreuzberg_process()

**Signature:**

```c
void kreuzberg_process(KreuzbergExtractionResult result, KreuzbergExtractionConfig config);
```

##### kreuzberg_processing_stage()

**Signature:**

```c
KreuzbergProcessingStage kreuzberg_processing_stage();
```

##### kreuzberg_should_process()

**Signature:**

```c
bool kreuzberg_should_process(KreuzbergExtractionResult result, KreuzbergExtractionConfig config);
```

##### kreuzberg_estimated_duration_ms()

**Signature:**

```c
uint64_t kreuzberg_estimated_duration_ms(KreuzbergExtractionResult result);
```


---

### KreuzbergRakeParams

RAKE-specific parameters.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `min_word_length` | `uintptr_t` | `1` | Minimum word length to consider (default: 1). |
| `max_words_per_phrase` | `uintptr_t` | `3` | Maximum words in a keyword phrase (default: 3). |

#### Methods

##### kreuzberg_default()

**Signature:**

```c
KreuzbergRakeParams kreuzberg_default();
```


---

### KreuzbergRecognizedTable

Pre-computed table markdown for a table detection region.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `detection_bbox` | `KreuzbergBBox` | — | Detection bbox that this table corresponds to (for matching). |
| `cells` | `const char***` | — | Table cells as a 2D vector (rows x columns). |
| `markdown` | `const char*` | — | Rendered markdown table. |


---

### KreuzbergRecord

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `tag_id` | `uint16_t` | — | Tag id |
| `data` | `const uint8_t*` | — | Data |

#### Methods

##### kreuzberg_parse()

**Signature:**

```c
KreuzbergRecord kreuzberg_parse(KreuzbergStreamReader reader);
```

##### kreuzberg_data_reader()

Return a fresh `StreamReader` over this record's data bytes.

**Signature:**

```c
KreuzbergStreamReader kreuzberg_data_reader();
```


---

### KreuzbergRecyclable

Trait for types that can be pooled and reused.

Implementing this trait allows a type to be used with `Pool<T>`.
The `reset()` method should clear the object's state for reuse.

#### Methods

##### kreuzberg_reset()

Reset the object to a reusable state.

This is called when returning an object to the pool.
Should clear any internal data while preserving capacity.

**Signature:**

```c
void kreuzberg_reset();
```


---

### KreuzbergRelationship

A relationship between two elements in the document.

During extraction, targets may be unresolved keys (`RelationshipTarget.Key`).
The derivation step resolves these to indices using the element anchor index.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `source` | `uint32_t` | — | Index of the source element in `InternalDocument.elements`. |
| `target` | `KreuzbergRelationshipTarget` | — | Target of the relationship (resolved index or unresolved key). |
| `kind` | `KreuzbergRelationshipKind` | — | Semantic kind of the relationship. |


---

### KreuzbergResolvedStyle

Fully resolved (flattened) style after walking the inheritance chain.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `paragraph_properties` | `KreuzbergParagraphProperties` | `NULL` | Paragraph properties (paragraph properties) |
| `run_properties` | `KreuzbergRunProperties` | `NULL` | Run properties (run properties) |


---

### KreuzbergRowProperties

Row-level properties from `<w:trPr>`.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `height` | `int32_t*` | `NULL` | Height |
| `height_rule` | `const char**` | `NULL` | Height rule |
| `is_header` | `bool` | `NULL` | Whether header |
| `cant_split` | `bool` | `NULL` | Cant split |


---

### KreuzbergRstExtractor

Native Rust reStructuredText extractor.

Parses RST documents using document tree parsing and extracts:
- Metadata from field lists
- Document structure (headings, sections)
- Text content and inline formatting
- Code blocks and directives
- Tables and lists

#### Methods

##### kreuzberg_build_internal_document()

Build an `InternalDocument` from RST content.

Handles sections, paragraphs, code blocks, tables, footnotes, citations,
and cross-references.

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_build_internal_document(const char* content, bool inject_placeholders);
```

##### kreuzberg_default()

**Signature:**

```c
KreuzbergRstExtractor kreuzberg_default();
```

##### kreuzberg_name()

**Signature:**

```c
const char* kreuzberg_name();
```

##### kreuzberg_version()

**Signature:**

```c
const char* kreuzberg_version();
```

##### kreuzberg_initialize()

**Signature:**

```c
void kreuzberg_initialize();
```

##### kreuzberg_shutdown()

**Signature:**

```c
void kreuzberg_shutdown();
```

##### kreuzberg_description()

**Signature:**

```c
const char* kreuzberg_description();
```

##### kreuzberg_author()

**Signature:**

```c
const char* kreuzberg_author();
```

##### kreuzberg_extract_bytes()

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_extract_bytes(const uint8_t* content, const char* mime_type, KreuzbergExtractionConfig config);
```

##### kreuzberg_extract_file()

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_extract_file(const char* path, const char* mime_type, KreuzbergExtractionConfig config);
```

##### kreuzberg_supported_mime_types()

**Signature:**

```c
const char** kreuzberg_supported_mime_types();
```

##### kreuzberg_priority()

**Signature:**

```c
int32_t kreuzberg_priority();
```


---

### KreuzbergRtDetrModel

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

##### kreuzberg_from_file()

Load a Docling RT-DETR ONNX model from a file.

**Signature:**

```c
KreuzbergRtDetrModel kreuzberg_from_file(const char* path);
```

##### kreuzberg_detect()

**Signature:**

```c
KreuzbergLayoutDetection* kreuzberg_detect(KreuzbergRgbImage img);
```

##### kreuzberg_detect_with_threshold()

**Signature:**

```c
KreuzbergLayoutDetection* kreuzberg_detect_with_threshold(KreuzbergRgbImage img, float threshold);
```

##### kreuzberg_detect_batch()

**Signature:**

```c
KreuzbergLayoutDetection** kreuzberg_detect_batch(KreuzbergRgbImage* images, float threshold);
```

##### kreuzberg_name()

**Signature:**

```c
const char* kreuzberg_name();
```


---

### KreuzbergRtfExtractor

Native Rust RTF extractor.

Extracts text content, metadata, and structure from RTF documents

#### Methods

##### kreuzberg_default()

**Signature:**

```c
KreuzbergRtfExtractor kreuzberg_default();
```

##### kreuzberg_name()

**Signature:**

```c
const char* kreuzberg_name();
```

##### kreuzberg_version()

**Signature:**

```c
const char* kreuzberg_version();
```

##### kreuzberg_initialize()

**Signature:**

```c
void kreuzberg_initialize();
```

##### kreuzberg_shutdown()

**Signature:**

```c
void kreuzberg_shutdown();
```

##### kreuzberg_description()

**Signature:**

```c
const char* kreuzberg_description();
```

##### kreuzberg_author()

**Signature:**

```c
const char* kreuzberg_author();
```

##### kreuzberg_extract_bytes()

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_extract_bytes(const uint8_t* content, const char* mime_type, KreuzbergExtractionConfig config);
```

##### kreuzberg_supported_mime_types()

**Signature:**

```c
const char** kreuzberg_supported_mime_types();
```

##### kreuzberg_priority()

**Signature:**

```c
int32_t kreuzberg_priority();
```


---

### KreuzbergRun

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `text` | `const char*` | `NULL` | Text |
| `bold` | `bool` | `NULL` | Bold |
| `italic` | `bool` | `NULL` | Italic |
| `underline` | `bool` | `NULL` | Underline |
| `strikethrough` | `bool` | `NULL` | Strikethrough |
| `subscript` | `bool` | `NULL` | Subscript |
| `superscript` | `bool` | `NULL` | Superscript |
| `font_size` | `uint32_t*` | `NULL` | Font size in half-points (from `w:sz`). |
| `font_color` | `const char**` | `NULL` | Font color as "RRGGBB" hex (from `w:color`). |
| `highlight` | `const char**` | `NULL` | Highlight color name (from `w:highlight`). |
| `hyperlink_url` | `const char**` | `NULL` | Hyperlink url |
| `math_latex` | `KreuzbergStringBool*` | `NULL` | LaTeX math content: (latex_source, is_display_math). When set, this run represents an equation and `text` is ignored. |

#### Methods

##### kreuzberg_to_markdown()

Render this run as markdown with formatting markers.

**Signature:**

```c
const char* kreuzberg_to_markdown();
```


---

### KreuzbergRunProperties

Run-level formatting properties (bold, italic, font, size, color, etc.).

All fields are `Option` so that inheritance resolution can distinguish
"not set" (`NULL`) from "explicitly set" (`Some`).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `bold` | `bool*` | `NULL` | Bold |
| `italic` | `bool*` | `NULL` | Italic |
| `underline` | `bool*` | `NULL` | Underline |
| `strikethrough` | `bool*` | `NULL` | Strikethrough |
| `color` | `const char**` | `NULL` | Hex RGB color, e.g. `"2F5496"`. |
| `font_size_half_points` | `int32_t*` | `NULL` | Font size in half-points (`w:sz` val). Divide by 2 to get points. |
| `font_ascii` | `const char**` | `NULL` | ASCII font family (`w:rFonts w:ascii`). |
| `font_ascii_theme` | `const char**` | `NULL` | ASCII theme font (`w:rFonts w:asciiTheme`). |
| `vert_align` | `const char**` | `NULL` | Vertical alignment: "superscript", "subscript", or "baseline". |
| `font_h_ansi` | `const char**` | `NULL` | High ANSI font family (w:rFonts w:hAnsi). |
| `font_cs` | `const char**` | `NULL` | Complex script font family (w:rFonts w:cs). |
| `font_east_asia` | `const char**` | `NULL` | East Asian font family (w:rFonts w:eastAsia). |
| `highlight` | `const char**` | `NULL` | Highlight color name (e.g., "yellow", "green", "cyan"). |
| `caps` | `bool*` | `NULL` | All caps text transformation. |
| `small_caps` | `bool*` | `NULL` | Small caps text transformation. |
| `shadow` | `bool*` | `NULL` | Text shadow effect. |
| `outline` | `bool*` | `NULL` | Text outline effect. |
| `emboss` | `bool*` | `NULL` | Text emboss effect. |
| `imprint` | `bool*` | `NULL` | Text imprint (engrave) effect. |
| `char_spacing` | `int32_t*` | `NULL` | Character spacing in twips (from w:spacing w:val). |
| `position` | `int32_t*` | `NULL` | Vertical position offset in half-points (from w:position w:val). |
| `kern` | `int32_t*` | `NULL` | Kerning threshold in half-points (from w:kern w:val). |
| `theme_color` | `const char**` | `NULL` | Theme color reference (e.g., "accent1", "dk1"). |
| `theme_tint` | `const char**` | `NULL` | Theme color tint modification (hex value). |
| `theme_shade` | `const char**` | `NULL` | Theme color shade modification (hex value). |


---

### KreuzbergSection

A body-text section containing a flat list of paragraphs.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `paragraphs` | `KreuzbergParagraph*` | `NULL` | Paragraphs |


---

### KreuzbergSectionProperties

DOCX section properties parsed from `w:sectPr` element.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `page_width_twips` | `int32_t*` | `NULL` | Page width in twips (from `w:pgSz w:w`). |
| `page_height_twips` | `int32_t*` | `NULL` | Page height in twips (from `w:pgSz w:h`). |
| `orientation` | `KreuzbergOrientation*` | `KREUZBERG_KREUZBERG_PORTRAIT` | Page orientation (from `w:pgSz w:orient`). |
| `margins` | `KreuzbergPageMargins` | `NULL` | Page margins (from `w:pgMar`). |
| `columns` | `KreuzbergColumnLayout` | `NULL` | Column layout (from `w:cols`). |
| `doc_grid_line_pitch` | `int32_t*` | `NULL` | Document grid line pitch in twips (from `w:docGrid w:linePitch`). |

#### Methods

##### kreuzberg_page_width_points()

Convert page width from twips to points.

**Signature:**

```c
double* kreuzberg_page_width_points();
```

##### kreuzberg_page_height_points()

Convert page height from twips to points.

**Signature:**

```c
double* kreuzberg_page_height_points();
```


---

### KreuzbergSecurityLimits

Configuration for security limits across extractors.

All limits are intentionally conservative to prevent DoS attacks
while still supporting legitimate documents.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `max_archive_size` | `uintptr_t` | `NULL` | Maximum uncompressed size for archives (500 MB) |
| `max_compression_ratio` | `uintptr_t` | `100` | Maximum compression ratio before flagging as potential bomb (100:1) |
| `max_files_in_archive` | `uintptr_t` | `10000` | Maximum number of files in archive (10,000) |
| `max_nesting_depth` | `uintptr_t` | `100` | Maximum nesting depth for structures (100) |
| `max_entity_length` | `uintptr_t` | `32` | Maximum entity/string length (32) |
| `max_content_size` | `uintptr_t` | `NULL` | Maximum string growth per document (100 MB) |
| `max_iterations` | `uintptr_t` | `10000000` | Maximum iterations per operation |
| `max_xml_depth` | `uintptr_t` | `100` | Maximum XML depth (100 levels) |
| `max_table_cells` | `uintptr_t` | `100000` | Maximum cells per table (100,000) |

#### Methods

##### kreuzberg_default()

**Signature:**

```c
KreuzbergSecurityLimits kreuzberg_default();
```


---

### KreuzbergServerConfig

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
| `host` | `const char*` | `NULL` | Server host address (e.g., "127.0.0.1", "0.0.0.0") |
| `port` | `uint16_t` | `NULL` | Server port number |
| `cors_origins` | `const char**` | `NULL` | CORS allowed origins. Empty vector means allow all origins. If this is an empty vector, the server will accept requests from any origin. If populated with specific origins (e.g., ["https://example.com"]), only those origins will be allowed. |
| `max_request_body_bytes` | `uintptr_t` | `NULL` | Maximum size of request body in bytes (default: 100 MB) |
| `max_multipart_field_bytes` | `uintptr_t` | `NULL` | Maximum size of multipart fields in bytes (default: 100 MB) |

#### Methods

##### kreuzberg_default()

**Signature:**

```c
KreuzbergServerConfig kreuzberg_default();
```

##### kreuzberg_listen_addr()

Get the server listen address (host:port).

**Signature:**

```c
const char* kreuzberg_listen_addr();
```

##### kreuzberg_cors_allows_all()

Check if CORS allows all origins.

Returns `true` if the `cors_origins` vector is empty, meaning all origins
are allowed. Returns `false` if specific origins are configured.

**Signature:**

```c
bool kreuzberg_cors_allows_all();
```

##### kreuzberg_is_origin_allowed()

Check if a given origin is allowed by CORS configuration.

Returns `true` if:
- CORS allows all origins (empty origins list), or
- The given origin is in the allowed origins list

**Signature:**

```c
bool kreuzberg_is_origin_allowed(const char* origin);
```

##### kreuzberg_max_request_body_mb()

Get maximum request body size in megabytes (rounded up).

**Signature:**

```c
uintptr_t kreuzberg_max_request_body_mb();
```

##### kreuzberg_max_multipart_field_mb()

Get maximum multipart field size in megabytes (rounded up).

**Signature:**

```c
uintptr_t kreuzberg_max_multipart_field_mb();
```

##### kreuzberg_apply_env_overrides()

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

```c
void kreuzberg_apply_env_overrides();
```

##### kreuzberg_from_file()

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

```c
KreuzbergServerConfig kreuzberg_from_file(KreuzbergPath path);
```

##### kreuzberg_from_toml_file()

Load server configuration from a TOML file.

**Errors:**

Returns `KreuzbergError.Validation` if the file doesn't exist or is invalid TOML.

**Signature:**

```c
KreuzbergServerConfig kreuzberg_from_toml_file(KreuzbergPath path);
```

##### kreuzberg_from_yaml_file()

Load server configuration from a YAML file.

**Errors:**

Returns `KreuzbergError.Validation` if the file doesn't exist or is invalid YAML.

**Signature:**

```c
KreuzbergServerConfig kreuzberg_from_yaml_file(KreuzbergPath path);
```

##### kreuzberg_from_json_file()

Load server configuration from a JSON file.

**Errors:**

Returns `KreuzbergError.Validation` if the file doesn't exist or is invalid JSON.

**Signature:**

```c
KreuzbergServerConfig kreuzberg_from_json_file(KreuzbergPath path);
```


---

### KreuzbergSevenZExtractor

7z archive extractor.

Extracts file lists and text content from 7z archives.

#### Methods

##### kreuzberg_default()

**Signature:**

```c
KreuzbergSevenZExtractor kreuzberg_default();
```

##### kreuzberg_name()

**Signature:**

```c
const char* kreuzberg_name();
```

##### kreuzberg_version()

**Signature:**

```c
const char* kreuzberg_version();
```

##### kreuzberg_initialize()

**Signature:**

```c
void kreuzberg_initialize();
```

##### kreuzberg_shutdown()

**Signature:**

```c
void kreuzberg_shutdown();
```

##### kreuzberg_description()

**Signature:**

```c
const char* kreuzberg_description();
```

##### kreuzberg_author()

**Signature:**

```c
const char* kreuzberg_author();
```

##### kreuzberg_extract_bytes()

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_extract_bytes(const uint8_t* content, const char* mime_type, KreuzbergExtractionConfig config);
```

##### kreuzberg_supported_mime_types()

**Signature:**

```c
const char** kreuzberg_supported_mime_types();
```

##### kreuzberg_priority()

**Signature:**

```c
int32_t kreuzberg_priority();
```

##### kreuzberg_as_sync_extractor()

**Signature:**

```c
KreuzbergSyncExtractor* kreuzberg_as_sync_extractor();
```

##### kreuzberg_extract_sync()

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_extract_sync(const uint8_t* content, const char* mime_type, KreuzbergExtractionConfig config);
```


---

### KreuzbergSlanetCell

A single cell detected by SLANeXT.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `polygon` | `KreuzbergF328` | — | Bounding box polygon in image pixel coordinates. Format: [x1, y1, x2, y2, x3, y3, x4, y4] (4 corners, clockwise from top-left). |
| `bbox` | `KreuzbergF324` | — | Axis-aligned bounding box derived from polygon: [left, top, right, bottom]. |
| `row` | `uintptr_t` | — | Row index in the table (0-based). |
| `col` | `uintptr_t` | — | Column index within the row (0-based). |


---

### KreuzbergSlanetModel

SLANeXT table structure recognition model.

Wraps an ORT session for SLANeXT ONNX model and provides preprocessing,
inference, and post-processing in a single `recognize` call.

#### Methods

##### kreuzberg_from_file()

Load a SLANeXT ONNX model from a file path.

**Signature:**

```c
KreuzbergSlanetModel kreuzberg_from_file(const char* path);
```

##### kreuzberg_recognize()

Recognize table structure from a cropped table image.

Returns a `SlanetResult` with detected cells, grid dimensions,
and structure tokens.

**Signature:**

```c
KreuzbergSlanetResult kreuzberg_recognize(KreuzbergRgbImage table_img);
```


---

### KreuzbergSlanetResult

SLANeXT recognition result for a single table image.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `cells` | `KreuzbergSlanetCell*` | — | Detected cells with bounding boxes and grid positions. |
| `num_rows` | `uintptr_t` | — | Number of rows in the table. |
| `num_cols` | `uintptr_t` | — | Maximum number of columns across all rows. |
| `confidence` | `float` | — | Average structure prediction confidence. |
| `structure_tokens` | `const char**` | — | Raw HTML structure tokens (for debugging). |


---

### KreuzbergStreamReader

#### Methods

##### kreuzberg_read_u8()

**Signature:**

```c
uint8_t kreuzberg_read_u8();
```

##### kreuzberg_read_u16()

**Signature:**

```c
uint16_t kreuzberg_read_u16();
```

##### kreuzberg_read_u32()

**Signature:**

```c
uint32_t kreuzberg_read_u32();
```

##### kreuzberg_read_bytes()

**Signature:**

```c
const uint8_t* kreuzberg_read_bytes(uintptr_t len);
```

##### kreuzberg_position()

Current byte position within the stream.

**Signature:**

```c
uint64_t kreuzberg_position();
```

##### kreuzberg_remaining()

Number of bytes remaining from the current position to the end.

**Signature:**

```c
uintptr_t kreuzberg_remaining();
```


---

### KreuzbergStringBufferPool

Convenience type alias for a pooled String.


---

### KreuzbergStringGrowthValidator

Helper struct for tracking and validating string growth.

#### Methods

##### kreuzberg_check_append()

Validate and update size after appending.

**Returns:**
* `Ok(())` if size is within limits
* `Err(SecurityError)` if size exceeds limit

**Signature:**

```c
void kreuzberg_check_append(uintptr_t len);
```

##### kreuzberg_current_size()

Get current size.

**Signature:**

```c
uintptr_t kreuzberg_current_size();
```


---

### KreuzbergStructuredData

Structured data (Schema.org, microdata, RDFa) block.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `data_type` | `KreuzbergStructuredDataType` | — | Type of structured data |
| `raw_json` | `const char*` | — | Raw JSON string representation |
| `schema_type` | `const char**` | `NULL` | Schema type if detectable (e.g., "Article", "Event", "Product") |


---

### KreuzbergStructuredDataResult

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `content` | `const char*` | — | The extracted text content |
| `format` | `KreuzbergStr` | — | Format (str) |
| `metadata` | `void*` | — | Document metadata |
| `text_fields` | `const char**` | — | Text fields |


---

### KreuzbergStructuredExtractionConfig

Configuration for LLM-based structured data extraction.

Sends extracted document content to a VLM with a JSON schema,
returning structured data that conforms to the schema.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `schema` | `void*` | — | JSON Schema defining the desired output structure. |
| `schema_name` | `const char*` | — | Schema name passed to the LLM's structured output mode. |
| `schema_description` | `const char**` | `NULL` | Optional schema description for the LLM. |
| `strict` | `bool` | — | Enable strict mode — output must exactly match the schema. |
| `prompt` | `const char**` | `NULL` | Custom Jinja2 extraction prompt template. When `None`, a default template is used. Available template variables: - `{{ content }}` — The extracted document text. - `{{ schema }}` — The JSON schema as a formatted string. - `{{ schema_name }}` — The schema name. - `{{ schema_description }}` — The schema description (may be empty). |
| `llm` | `KreuzbergLlmConfig` | — | LLM configuration for the extraction. |


---

### KreuzbergStructuredExtractor

Structured data extractor supporting JSON, JSONL/NDJSON, YAML, and TOML.

#### Methods

##### kreuzberg_default()

**Signature:**

```c
KreuzbergStructuredExtractor kreuzberg_default();
```

##### kreuzberg_name()

**Signature:**

```c
const char* kreuzberg_name();
```

##### kreuzberg_version()

**Signature:**

```c
const char* kreuzberg_version();
```

##### kreuzberg_initialize()

**Signature:**

```c
void kreuzberg_initialize();
```

##### kreuzberg_shutdown()

**Signature:**

```c
void kreuzberg_shutdown();
```

##### kreuzberg_extract_bytes()

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_extract_bytes(const uint8_t* content, const char* mime_type, KreuzbergExtractionConfig config);
```

##### kreuzberg_supported_mime_types()

**Signature:**

```c
const char** kreuzberg_supported_mime_types();
```

##### kreuzberg_priority()

**Signature:**

```c
int32_t kreuzberg_priority();
```


---

### KreuzbergStyleCatalog

Catalog of all styles parsed from `word/styles.xml`, plus document defaults.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `styles` | `KreuzbergAHashMap` | `NULL` | Styles (a hash map) |
| `default_paragraph_properties` | `KreuzbergParagraphProperties` | `NULL` | Default paragraph properties (paragraph properties) |
| `default_run_properties` | `KreuzbergRunProperties` | `NULL` | Default run properties (run properties) |

#### Methods

##### kreuzberg_resolve_style()

Resolve a style by walking its `basedOn` inheritance chain.

The resolution order is:
1. Document defaults (`<w:docDefaults>`)
2. Base style chain (walking `basedOn` from root to leaf)
3. The style itself

For `Option` fields, a child value of `Some(x)` overrides the parent.
A value of `NULL` inherits from the parent. For boolean toggle properties,
`Some(false)` explicitly disables the property.

The chain depth is limited to 20 to prevent infinite loops from circular references.

**Signature:**

```c
KreuzbergResolvedStyle kreuzberg_resolve_style(const char* style_id);
```


---

### KreuzbergStyleDefinition

A single style definition parsed from `<w:style>` in `word/styles.xml`.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `id` | `const char*` | — | The style ID (`w:styleId` attribute). |
| `name` | `const char**` | `NULL` | Human-readable name (`<w:name w:val="..."/>`). |
| `style_type` | `KreuzbergStyleType` | — | Style type: paragraph, character, table, or numbering. |
| `based_on` | `const char**` | `NULL` | ID of the parent style (`<w:basedOn w:val="..."/>`). |
| `next_style` | `const char**` | `NULL` | ID of the style to apply to the next paragraph (`<w:next w:val="..."/>`). |
| `is_default` | `bool` | — | Whether this is the default style for its type. |
| `paragraph_properties` | `KreuzbergParagraphProperties` | — | Paragraph properties defined directly on this style. |
| `run_properties` | `KreuzbergRunProperties` | — | Run properties defined directly on this style. |


---

### KreuzbergStyledHtmlRenderer

Styled HTML renderer.

Implements the `Renderer` trait; registered as `"html"` when the
`html` feature is active. Configuration is baked in at
construction time — no per-render allocation for CSS resolution.

#### Methods

##### kreuzberg_new()

**Signature:**

```c
KreuzbergStyledHtmlRenderer kreuzberg_new(KreuzbergHtmlOutputConfig config);
```

##### kreuzberg_name()

**Signature:**

```c
const char* kreuzberg_name();
```

##### kreuzberg_render()

**Signature:**

```c
const char* kreuzberg_render(KreuzbergInternalDocument doc);
```


---

### KreuzbergSupportedFormat

A supported document format entry.

Represents a file extension and its corresponding MIME type that Kreuzberg can process.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `extension` | `const char*` | — | File extension (without leading dot), e.g., "pdf", "docx" |
| `mime_type` | `const char*` | — | MIME type string, e.g., "application/pdf" |


---

### KreuzbergSyncExtractor

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

##### kreuzberg_extract_sync()

Extract content from a byte array synchronously.

This method performs extraction without requiring an async runtime.
It is called by `extract_bytes_sync()` when the `tokio-runtime` feature is disabled.

**Returns:**

An `InternalDocument` containing the extracted elements, metadata, and tables.

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_extract_sync(const uint8_t* content, const char* mime_type, KreuzbergExtractionConfig config);
```


---

### KreuzbergTable

Extracted table structure.

Represents a table detected and extracted from a document (PDF, image, etc.).
Tables are converted to both structured cell data and Markdown format.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `cells` | `const char***` | — | Table cells as a 2D vector (rows × columns) |
| `markdown` | `const char*` | — | Markdown representation of the table |
| `page_number` | `uintptr_t` | — | Page number where the table was found (1-indexed) |
| `bounding_box` | `KreuzbergBoundingBox*` | `NULL` | Bounding box of the table on the page (PDF coordinates: x0=left, y0=bottom, x1=right, y1=top). Only populated for PDF-extracted tables when position data is available. |


---

### KreuzbergTableBorders

Borders for a table (6 borders: top, bottom, left, right, insideH, insideV).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `top` | `KreuzbergBorderStyle*` | `NULL` | Top (border style) |
| `bottom` | `KreuzbergBorderStyle*` | `NULL` | Bottom (border style) |
| `left` | `KreuzbergBorderStyle*` | `NULL` | Left (border style) |
| `right` | `KreuzbergBorderStyle*` | `NULL` | Right (border style) |
| `inside_h` | `KreuzbergBorderStyle*` | `NULL` | Inside h (border style) |
| `inside_v` | `KreuzbergBorderStyle*` | `NULL` | Inside v (border style) |


---

### KreuzbergTableCell

Individual table cell with content and optional styling.

Future extension point for rich table support with cell-level metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `content` | `const char*` | — | Cell content as text |
| `row_span` | `uintptr_t` | — | Row span (number of rows this cell spans) |
| `col_span` | `uintptr_t` | — | Column span (number of columns this cell spans) |
| `is_header` | `bool` | — | Whether this is a header cell |


---

### KreuzbergTableClassifier

PP-LCNet table classifier model.

#### Methods

##### kreuzberg_from_file()

Load the table classifier ONNX model from a file path.

**Signature:**

```c
KreuzbergTableClassifier kreuzberg_from_file(const char* path);
```

##### kreuzberg_classify()

Classify a cropped table image as wired or wireless.

**Signature:**

```c
KreuzbergTableType kreuzberg_classify(KreuzbergRgbImage table_img);
```


---

### KreuzbergTableGrid

Structured table grid with cell-level metadata.

Stores row/column dimensions and a flat list of cells with position info.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `rows` | `uint32_t` | — | Number of rows in the table. |
| `cols` | `uint32_t` | — | Number of columns in the table. |
| `cells` | `KreuzbergGridCell*` | — | All cells in row-major order. |


---

### KreuzbergTableLook

Table look bitmask/flags controlling conditional formatting bands.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `first_row` | `bool` | `NULL` | First row |
| `last_row` | `bool` | `NULL` | Last row |
| `first_column` | `bool` | `NULL` | First column |
| `last_column` | `bool` | `NULL` | Last column |
| `no_h_band` | `bool` | `NULL` | No h band |
| `no_v_band` | `bool` | `NULL` | No v band |


---

### KreuzbergTableProperties

Table-level properties from `<w:tblPr>`.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `style_id` | `const char**` | `NULL` | Style id |
| `width` | `KreuzbergTableWidth*` | `NULL` | Width (table width) |
| `alignment` | `const char**` | `NULL` | Alignment |
| `layout` | `const char**` | `NULL` | Layout |
| `look` | `KreuzbergTableLook*` | `NULL` | Look (table look) |
| `borders` | `KreuzbergTableBorders*` | `NULL` | Borders (table borders) |
| `cell_margins` | `KreuzbergCellMargins*` | `NULL` | Cell margins (cell margins) |
| `indent` | `KreuzbergTableWidth*` | `NULL` | Indent (table width) |
| `caption` | `const char**` | `NULL` | Caption |


---

### KreuzbergTableRow

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `cells` | `KreuzbergTableCell*` | `NULL` | Cells |
| `properties` | `KreuzbergRowProperties*` | `NULL` | Properties (row properties) |


---

### KreuzbergTableValidator

Helper struct for validating table cell counts.

#### Methods

##### kreuzberg_add_cells()

Add cells to table and validate.

**Returns:**
* `Ok(())` if cell count is within limits
* `Err(SecurityError)` if cell count exceeds limit

**Signature:**

```c
void kreuzberg_add_cells(uintptr_t count);
```

##### kreuzberg_current_cells()

Get current cell count.

**Signature:**

```c
uintptr_t kreuzberg_current_cells();
```


---

### KreuzbergTableWidth

Width specification used for tables and cells.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `value` | `int32_t` | — | Value |
| `width_type` | `const char*` | — | Width type |


---

### KreuzbergTarExtractor

TAR archive extractor.

Extracts file lists and text content from TAR archives.

#### Methods

##### kreuzberg_default()

**Signature:**

```c
KreuzbergTarExtractor kreuzberg_default();
```

##### kreuzberg_name()

**Signature:**

```c
const char* kreuzberg_name();
```

##### kreuzberg_version()

**Signature:**

```c
const char* kreuzberg_version();
```

##### kreuzberg_initialize()

**Signature:**

```c
void kreuzberg_initialize();
```

##### kreuzberg_shutdown()

**Signature:**

```c
void kreuzberg_shutdown();
```

##### kreuzberg_description()

**Signature:**

```c
const char* kreuzberg_description();
```

##### kreuzberg_author()

**Signature:**

```c
const char* kreuzberg_author();
```

##### kreuzberg_extract_bytes()

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_extract_bytes(const uint8_t* content, const char* mime_type, KreuzbergExtractionConfig config);
```

##### kreuzberg_supported_mime_types()

**Signature:**

```c
const char** kreuzberg_supported_mime_types();
```

##### kreuzberg_priority()

**Signature:**

```c
int32_t kreuzberg_priority();
```

##### kreuzberg_as_sync_extractor()

**Signature:**

```c
KreuzbergSyncExtractor* kreuzberg_as_sync_extractor();
```

##### kreuzberg_extract_sync()

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_extract_sync(const uint8_t* content, const char* mime_type, KreuzbergExtractionConfig config);
```


---

### KreuzbergTatrDetection

A single TATR detection result.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `bbox` | `KreuzbergF324` | — | Bounding box in crop-pixel coordinates: `[x1, y1, x2, y2]`. |
| `confidence` | `float` | — | Detection confidence score (0.0..1.0). |
| `class` | `KreuzbergTatrClass` | — | Detected class. |


---

### KreuzbergTatrModel

TATR (Table Transformer) table structure recognition model.

Wraps an ORT session for the TATR ONNX model and provides preprocessing,
inference, and post-processing in a single `recognize` call.

#### Methods

##### kreuzberg_from_file()

Load a TATR ONNX model from a file path.

Uses the default execution provider selection from `build_session`
with a CPU-only fallback if the platform EP fails.

**Signature:**

```c
KreuzbergTatrModel kreuzberg_from_file(const char* path);
```

##### kreuzberg_recognize()

Recognize table structure from a cropped table image.

Returns a `TatrResult` with detected rows, columns, headers, and
spanning cells in the input image's pixel coordinate space.

**Signature:**

```c
KreuzbergTatrResult kreuzberg_recognize(KreuzbergRgbImage table_img);
```


---

### KreuzbergTatrResult

Aggregated TATR recognition result with detections separated by class.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `rows` | `KreuzbergTatrDetection*` | — | Detected rows, sorted top-to-bottom by `y2`. |
| `columns` | `KreuzbergTatrDetection*` | — | Detected columns, sorted left-to-right by `x2`. |
| `headers` | `KreuzbergTatrDetection*` | — | Detected headers (ColumnHeader and ProjectedRowHeader). |
| `spanning` | `KreuzbergTatrDetection*` | — | Detected spanning cells. |


---

### KreuzbergTessdataManager

Manages tessdata file downloading, caching, and manifest generation.

#### Methods

##### kreuzberg_cache_dir()

Get the cache directory path.

**Signature:**

```c
const char* kreuzberg_cache_dir();
```

##### kreuzberg_is_language_cached()

Check if a specific language traineddata file is cached.

**Signature:**

```c
bool kreuzberg_is_language_cached(const char* lang);
```


---

### KreuzbergTesseractBackend

Native Tesseract OCR backend.

This backend wraps the OcrProcessor and implements the OcrBackend trait,
allowing it to be used through the plugin system.

# Thread Safety

Uses Arc for shared ownership and is thread-safe (Send + Sync).

#### Methods

##### kreuzberg_new()

Create a new Tesseract backend with default cache directory.

**Signature:**

```c
KreuzbergTesseractBackend kreuzberg_new();
```

##### kreuzberg_with_cache_dir()

Create a new Tesseract backend with custom cache directory.

**Signature:**

```c
KreuzbergTesseractBackend kreuzberg_with_cache_dir(const char* cache_dir);
```

##### kreuzberg_default()

**Signature:**

```c
KreuzbergTesseractBackend kreuzberg_default();
```

##### kreuzberg_name()

**Signature:**

```c
const char* kreuzberg_name();
```

##### kreuzberg_version()

**Signature:**

```c
const char* kreuzberg_version();
```

##### kreuzberg_initialize()

**Signature:**

```c
void kreuzberg_initialize();
```

##### kreuzberg_shutdown()

**Signature:**

```c
void kreuzberg_shutdown();
```

##### kreuzberg_process_image()

**Signature:**

```c
KreuzbergExtractionResult kreuzberg_process_image(const uint8_t* image_bytes, KreuzbergOcrConfig config);
```

##### kreuzberg_process_image_file()

**Signature:**

```c
KreuzbergExtractionResult kreuzberg_process_image_file(const char* path, KreuzbergOcrConfig config);
```

##### kreuzberg_supports_language()

**Signature:**

```c
bool kreuzberg_supports_language(const char* lang);
```

##### kreuzberg_backend_type()

**Signature:**

```c
KreuzbergOcrBackendType kreuzberg_backend_type();
```

##### kreuzberg_supported_languages()

**Signature:**

```c
const char** kreuzberg_supported_languages();
```

##### kreuzberg_supports_table_detection()

**Signature:**

```c
bool kreuzberg_supports_table_detection();
```


---

### KreuzbergTesseractConfig

Tesseract OCR configuration.

Provides fine-grained control over Tesseract OCR engine parameters.
Most users can use the defaults, but these settings allow optimization
for specific document types (invoices, handwriting, etc.).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `language` | `const char*` | `"eng"` | Language code (e.g., "eng", "deu", "fra") |
| `psm` | `int32_t` | `3` | Page Segmentation Mode (0-13). Common values: - 3: Fully automatic page segmentation (default) - 6: Assume a single uniform block of text - 11: Sparse text with no particular order |
| `output_format` | `const char*` | `"markdown"` | Output format ("text" or "markdown") |
| `oem` | `int32_t` | `3` | OCR Engine Mode (0-3). - 0: Legacy engine only - 1: Neural nets (LSTM) only (usually best) - 2: Legacy + LSTM - 3: Default (based on what's available) |
| `min_confidence` | `double` | `0` | Minimum confidence threshold (0.0-100.0). Words with confidence below this threshold may be rejected or flagged. |
| `preprocessing` | `KreuzbergImagePreprocessingConfig*` | `NULL` | Image preprocessing configuration. Controls how images are preprocessed before OCR. Can significantly improve quality for scanned documents or low-quality images. |
| `enable_table_detection` | `bool` | `true` | Enable automatic table detection and reconstruction |
| `table_min_confidence` | `double` | `0` | Minimum confidence threshold for table detection (0.0-1.0) |
| `table_column_threshold` | `int32_t` | `50` | Column threshold for table detection (pixels) |
| `table_row_threshold_ratio` | `double` | `0.5` | Row threshold ratio for table detection (0.0-1.0) |
| `use_cache` | `bool` | `true` | Enable OCR result caching |
| `classify_use_pre_adapted_templates` | `bool` | `true` | Use pre-adapted templates for character classification |
| `language_model_ngram_on` | `bool` | `false` | Enable N-gram language model |
| `tessedit_dont_blkrej_good_wds` | `bool` | `true` | Don't reject good words during block-level processing |
| `tessedit_dont_rowrej_good_wds` | `bool` | `true` | Don't reject good words during row-level processing |
| `tessedit_enable_dict_correction` | `bool` | `true` | Enable dictionary correction |
| `tessedit_char_whitelist` | `const char*` | `""` | Whitelist of allowed characters (empty = all allowed) |
| `tessedit_char_blacklist` | `const char*` | `""` | Blacklist of forbidden characters (empty = none forbidden) |
| `tessedit_use_primary_params_model` | `bool` | `true` | Use primary language params model |
| `textord_space_size_is_variable` | `bool` | `true` | Variable-width space detection |
| `thresholding_method` | `bool` | `false` | Use adaptive thresholding method |

#### Methods

##### kreuzberg_default()

**Signature:**

```c
KreuzbergTesseractConfig kreuzberg_default();
```


---

### KreuzbergTextAnnotation

Inline text annotation — byte-range based formatting and links.

Annotations reference byte offsets into the node's text content,
enabling precise identification of formatted regions.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `start` | `uint32_t` | — | Start byte offset in the node's text content (inclusive). |
| `end` | `uint32_t` | — | End byte offset in the node's text content (exclusive). |
| `kind` | `KreuzbergAnnotationKind` | — | Annotation type. |


---

### KreuzbergTextExtractionResult

Plain text and Markdown extraction result.

Contains the extracted text along with statistics and,
for Markdown files, structural elements like headers and links.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `content` | `const char*` | — | Extracted text content |
| `line_count` | `uintptr_t` | — | Number of lines |
| `word_count` | `uintptr_t` | — | Number of words |
| `character_count` | `uintptr_t` | — | Number of characters |
| `headers` | `const char***` | `NULL` | Markdown headers (text only, Markdown files only) |
| `links` | `KreuzbergStringString**` | `NULL` | Markdown links as (text, URL) tuples (Markdown files only) |
| `code_blocks` | `KreuzbergStringString**` | `NULL` | Code blocks as (language, code) tuples (Markdown files only) |


---

### KreuzbergTextMetadata

Text/Markdown metadata.

Extracted from plain text and Markdown files. Includes word counts and,
for Markdown, structural elements like headers and links.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `line_count` | `uintptr_t` | — | Number of lines in the document |
| `word_count` | `uintptr_t` | — | Number of words |
| `character_count` | `uintptr_t` | — | Number of characters |
| `headers` | `const char***` | `NULL` | Markdown headers (headings text only, for Markdown files) |
| `links` | `KreuzbergStringString**` | `NULL` | Markdown links as (text, url) tuples (for Markdown files) |
| `code_blocks` | `KreuzbergStringString**` | `NULL` | Code blocks as (language, code) tuples (for Markdown files) |


---

### KreuzbergTheme

Complete theme with color scheme and font scheme.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `name` | `const char*` | `NULL` | Theme name (e.g., "Office Theme"). |
| `color_scheme` | `KreuzbergColorScheme*` | `NULL` | Color scheme (12 standard colors). |
| `font_scheme` | `KreuzbergFontScheme*` | `NULL` | Font scheme (major and minor fonts). |


---

### KreuzbergTokenReductionConfig

Token reduction configuration.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `mode` | `const char*` | — | Reduction mode: "off", "light", "moderate", "aggressive", "maximum" |
| `preserve_important_words` | `bool` | — | Preserve important words (capitalized, technical terms) |


---

### KreuzbergTracingLayer

A `tower.Layer` that wraps each extraction in a semantic tracing span.

#### Methods

##### kreuzberg_layer()

**Signature:**

```c
KreuzbergService kreuzberg_layer(KreuzbergS inner);
```


---

### KreuzbergTreeSitterConfig

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
| `cache_dir` | `const char**` | `NULL` | Custom cache directory for downloaded grammars. When `None`, uses the default: `~/.cache/tree-sitter-language-pack/v{version}/libs/`. |
| `languages` | `const char***` | `NULL` | Languages to pre-download on init (e.g., `["python", "rust"]`). |
| `groups` | `const char***` | `NULL` | Language groups to pre-download (e.g., `["web", "systems", "scripting"]`). |
| `process` | `KreuzbergTreeSitterProcessConfig` | `NULL` | Processing options for code analysis. |

#### Methods

##### kreuzberg_default()

**Signature:**

```c
KreuzbergTreeSitterConfig kreuzberg_default();
```


---

### KreuzbergTreeSitterProcessConfig

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
| `chunk_max_size` | `uintptr_t*` | `NULL` | Maximum chunk size in bytes. `None` disables chunking. |
| `content_mode` | `KreuzbergCodeContentMode` | `KREUZBERG_KREUZBERG_CHUNKS` | Content rendering mode for code extraction. |

#### Methods

##### kreuzberg_default()

**Signature:**

```c
KreuzbergTreeSitterProcessConfig kreuzberg_default();
```


---

### KreuzbergTsvRow

Tesseract TSV row data for conversion.

This struct represents a single row from Tesseract's TSV output format.
TSV format includes hierarchical information (block, paragraph, line, word)
along with bounding boxes and confidence scores.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `level` | `int32_t` | — | Hierarchical level (1=block, 2=para, 3=line, 4=word, 5=symbol) |
| `page_num` | `int32_t` | — | Page number (1-indexed) |
| `block_num` | `int32_t` | — | Block number within page |
| `par_num` | `int32_t` | — | Paragraph number within block |
| `line_num` | `int32_t` | — | Line number within paragraph |
| `word_num` | `int32_t` | — | Word number within line |
| `left` | `uint32_t` | — | Left x-coordinate in pixels |
| `top` | `uint32_t` | — | Top y-coordinate in pixels |
| `width` | `uint32_t` | — | Width in pixels |
| `height` | `uint32_t` | — | Height in pixels |
| `conf` | `double` | — | Confidence score (0-100) |
| `text` | `const char*` | — | Recognized text |


---

### KreuzbergTypstExtractor

Typst document extractor

#### Methods

##### kreuzberg_default()

**Signature:**

```c
KreuzbergTypstExtractor kreuzberg_default();
```

##### kreuzberg_name()

**Signature:**

```c
const char* kreuzberg_name();
```

##### kreuzberg_version()

**Signature:**

```c
const char* kreuzberg_version();
```

##### kreuzberg_initialize()

**Signature:**

```c
void kreuzberg_initialize();
```

##### kreuzberg_shutdown()

**Signature:**

```c
void kreuzberg_shutdown();
```

##### kreuzberg_description()

**Signature:**

```c
const char* kreuzberg_description();
```

##### kreuzberg_author()

**Signature:**

```c
const char* kreuzberg_author();
```

##### kreuzberg_extract_bytes()

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_extract_bytes(const uint8_t* content, const char* mime_type, KreuzbergExtractionConfig config);
```

##### kreuzberg_extract_file()

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_extract_file(const char* path, const char* mime_type, KreuzbergExtractionConfig config);
```

##### kreuzberg_supported_mime_types()

**Signature:**

```c
const char** kreuzberg_supported_mime_types();
```

##### kreuzberg_priority()

**Signature:**

```c
int32_t kreuzberg_priority();
```


---

### KreuzbergUri

A URI extracted from a document.

Represents any link, reference, or resource pointer found during extraction.
The `kind` field classifies the URI semantically, while `label` carries
optional human-readable display text.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `url` | `const char*` | — | The URL or path string. |
| `label` | `const char**` | `NULL` | Optional display text / label for the link. |
| `page` | `uint32_t*` | `NULL` | Optional page number where the URI was found (1-indexed). |
| `kind` | `KreuzbergUriKind` | — | Semantic classification of the URI. |

#### Methods

##### kreuzberg_hyperlink()

Create a new hyperlink URI, auto-classifying `mailto:` as Email and `#` as Anchor.

**Signature:**

```c
KreuzbergUri kreuzberg_hyperlink(const char* url, const char* label);
```

##### kreuzberg_image()

Create a new image URI.

**Signature:**

```c
KreuzbergUri kreuzberg_image(const char* url, const char* label);
```

##### kreuzberg_citation()

Create a new citation URI (for DOIs, academic references).

**Signature:**

```c
KreuzbergUri kreuzberg_citation(const char* url, const char* label);
```

##### kreuzberg_anchor()

Create a new anchor/cross-reference URI.

**Signature:**

```c
KreuzbergUri kreuzberg_anchor(const char* url, const char* label);
```

##### kreuzberg_email()

Create a new email URI.

**Signature:**

```c
KreuzbergUri kreuzberg_email(const char* url, const char* label);
```

##### kreuzberg_reference()

Create a new reference URI.

**Signature:**

```c
KreuzbergUri kreuzberg_reference(const char* url, const char* label);
```

##### kreuzberg_with_page()

Set the page number.

**Signature:**

```c
KreuzbergUri kreuzberg_with_page(uint32_t page);
```


---

### KreuzbergVlmOcrBackend

VLM-based OCR backend using liter-llm vision models.

This backend sends images to a vision language model (e.g., GPT-4o, Claude)
for text extraction, as an alternative to traditional OCR backends.

#### Methods

##### kreuzberg_name()

**Signature:**

```c
const char* kreuzberg_name();
```

##### kreuzberg_version()

**Signature:**

```c
const char* kreuzberg_version();
```

##### kreuzberg_initialize()

**Signature:**

```c
void kreuzberg_initialize();
```

##### kreuzberg_shutdown()

**Signature:**

```c
void kreuzberg_shutdown();
```

##### kreuzberg_process_image()

**Signature:**

```c
KreuzbergExtractionResult kreuzberg_process_image(const uint8_t* image_bytes, KreuzbergOcrConfig config);
```

##### kreuzberg_supports_language()

**Signature:**

```c
bool kreuzberg_supports_language(const char* lang);
```

##### kreuzberg_backend_type()

**Signature:**

```c
KreuzbergOcrBackendType kreuzberg_backend_type();
```


---

### KreuzbergXlsxAppProperties

Application properties from docProps/app.xml for XLSX

Contains Excel-specific document metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `application` | `const char**` | `NULL` | Application name (e.g., "Microsoft Excel") |
| `app_version` | `const char**` | `NULL` | Application version |
| `doc_security` | `int32_t*` | `NULL` | Document security level |
| `scale_crop` | `bool*` | `NULL` | Scale crop flag |
| `links_up_to_date` | `bool*` | `NULL` | Links up to date flag |
| `shared_doc` | `bool*` | `NULL` | Shared document flag |
| `hyperlinks_changed` | `bool*` | `NULL` | Hyperlinks changed flag |
| `company` | `const char**` | `NULL` | Company name |
| `worksheet_names` | `const char**` | `NULL` | Worksheet names |


---

### KreuzbergXmlExtractionResult

XML extraction result.

Contains extracted text content from XML files along with
structural statistics about the XML document.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `content` | `const char*` | — | Extracted text content (XML structure filtered out) |
| `element_count` | `uintptr_t` | — | Total number of XML elements processed |
| `unique_elements` | `const char**` | — | List of unique element names found (sorted) |


---

### KreuzbergXmlExtractor

XML extractor.

Extracts text content from XML files, preserving element structure information.

#### Methods

##### kreuzberg_default()

**Signature:**

```c
KreuzbergXmlExtractor kreuzberg_default();
```

##### kreuzberg_name()

**Signature:**

```c
const char* kreuzberg_name();
```

##### kreuzberg_version()

**Signature:**

```c
const char* kreuzberg_version();
```

##### kreuzberg_initialize()

**Signature:**

```c
void kreuzberg_initialize();
```

##### kreuzberg_shutdown()

**Signature:**

```c
void kreuzberg_shutdown();
```

##### kreuzberg_description()

**Signature:**

```c
const char* kreuzberg_description();
```

##### kreuzberg_author()

**Signature:**

```c
const char* kreuzberg_author();
```

##### kreuzberg_extract_sync()

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_extract_sync(const uint8_t* content, const char* mime_type, KreuzbergExtractionConfig config);
```

##### kreuzberg_extract_bytes()

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_extract_bytes(const uint8_t* content, const char* mime_type, KreuzbergExtractionConfig config);
```

##### kreuzberg_supported_mime_types()

**Signature:**

```c
const char** kreuzberg_supported_mime_types();
```

##### kreuzberg_priority()

**Signature:**

```c
int32_t kreuzberg_priority();
```

##### kreuzberg_as_sync_extractor()

**Signature:**

```c
KreuzbergSyncExtractor* kreuzberg_as_sync_extractor();
```


---

### KreuzbergXmlMetadata

XML metadata extracted during XML parsing.

Provides statistics about XML document structure.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `element_count` | `uintptr_t` | — | Total number of XML elements processed |
| `unique_elements` | `const char**` | — | List of unique element tag names (sorted) |


---

### KreuzbergYakeParams

YAKE-specific parameters.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `window_size` | `uintptr_t` | `2` | Window size for co-occurrence analysis (default: 2). Controls the context window for computing co-occurrence statistics. |

#### Methods

##### kreuzberg_default()

**Signature:**

```c
KreuzbergYakeParams kreuzberg_default();
```


---

### KreuzbergYearRange

Year range for bibliographic metadata.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `min` | `uint32_t*` | `NULL` | Min |
| `max` | `uint32_t*` | `NULL` | Max |
| `years` | `uint32_t*` | — | Years |


---

### KreuzbergYoloModel

YOLO-family layout detection model (YOLOv10, DocLayout-YOLO, YOLOX).

#### Methods

##### kreuzberg_from_file()

Load a YOLO ONNX model from a file.

For square-input models (YOLOv10, DocLayout-YOLO), pass the same value for both dimensions.
For YOLOX (unstructuredio), use width=768, height=1024.

**Signature:**

```c
KreuzbergYoloModel kreuzberg_from_file(const char* path, KreuzbergYoloVariant variant, uint32_t input_width, uint32_t input_height, const char* model_name);
```

##### kreuzberg_detect()

**Signature:**

```c
KreuzbergLayoutDetection* kreuzberg_detect(KreuzbergRgbImage img);
```

##### kreuzberg_detect_with_threshold()

**Signature:**

```c
KreuzbergLayoutDetection* kreuzberg_detect_with_threshold(KreuzbergRgbImage img, float threshold);
```

##### kreuzberg_name()

**Signature:**

```c
const char* kreuzberg_name();
```


---

### KreuzbergZipBombValidator

Helper struct for validating ZIP archives for security issues.


---

### KreuzbergZipExtractor

ZIP archive extractor.

Extracts file lists and text content from ZIP archives.

#### Methods

##### kreuzberg_default()

**Signature:**

```c
KreuzbergZipExtractor kreuzberg_default();
```

##### kreuzberg_name()

**Signature:**

```c
const char* kreuzberg_name();
```

##### kreuzberg_version()

**Signature:**

```c
const char* kreuzberg_version();
```

##### kreuzberg_initialize()

**Signature:**

```c
void kreuzberg_initialize();
```

##### kreuzberg_shutdown()

**Signature:**

```c
void kreuzberg_shutdown();
```

##### kreuzberg_description()

**Signature:**

```c
const char* kreuzberg_description();
```

##### kreuzberg_author()

**Signature:**

```c
const char* kreuzberg_author();
```

##### kreuzberg_extract_bytes()

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_extract_bytes(const uint8_t* content, const char* mime_type, KreuzbergExtractionConfig config);
```

##### kreuzberg_supported_mime_types()

**Signature:**

```c
const char** kreuzberg_supported_mime_types();
```

##### kreuzberg_priority()

**Signature:**

```c
int32_t kreuzberg_priority();
```

##### kreuzberg_as_sync_extractor()

**Signature:**

```c
KreuzbergSyncExtractor* kreuzberg_as_sync_extractor();
```

##### kreuzberg_extract_sync()

**Signature:**

```c
KreuzbergInternalDocument kreuzberg_extract_sync(const uint8_t* content, const char* mime_type, KreuzbergExtractionConfig config);
```


---

## Enums

### KreuzbergExecutionProviderType

ONNX Runtime execution provider type.

Determines which hardware backend is used for model inference.
`Auto` (default) selects the best available provider per platform.

| Value | Description |
|-------|-------------|
| `KREUZBERG_AUTO` | Auto-select: CoreML on macOS, CUDA on Linux, CPU elsewhere. |
| `KREUZBERG_CPU` | CPU execution provider (always available). |
| `KREUZBERG_CORE_ML` | Apple CoreML (macOS/iOS Neural Engine + GPU). |
| `KREUZBERG_CUDA` | NVIDIA CUDA GPU acceleration. |
| `KREUZBERG_TENSOR_RT` | NVIDIA TensorRT (optimized CUDA inference). |


---

### KreuzbergOutputFormat

Output format for extraction results.

Controls the format of the `content` field in `ExtractionResult`.
When set to `Markdown`, `Djot`, or `Html`, the output will be formatted
accordingly. `Plain` returns the raw extracted text.
`Structured` returns JSON with full OCR element data including bounding
boxes and confidence scores.

| Value | Description |
|-------|-------------|
| `KREUZBERG_PLAIN` | Plain text content only (default) |
| `KREUZBERG_MARKDOWN` | Markdown format |
| `KREUZBERG_DJOT` | Djot markup format |
| `KREUZBERG_HTML` | HTML format |
| `KREUZBERG_JSON` | JSON tree format with heading-driven sections. |
| `KREUZBERG_STRUCTURED` | Structured JSON format with full OCR element metadata. |
| `KREUZBERG_CUSTOM` | Custom renderer registered via the RendererRegistry. The string is the renderer name (e.g., "docx", "latex"). |


---

### KreuzbergHtmlTheme

Built-in HTML theme selection.

| Value | Description |
|-------|-------------|
| `KREUZBERG_DEFAULT` | Sensible defaults: system font stack, neutral colours, readable line measure. CSS custom properties (`--kb-*`) are all defined so user CSS can override individual values. |
| `KREUZBERG_GIT_HUB` | GitHub Markdown-inspired palette and spacing. |
| `KREUZBERG_DARK` | Dark background, light text. |
| `KREUZBERG_LIGHT` | Minimal light theme with generous whitespace. |
| `KREUZBERG_UNSTYLED` | No built-in stylesheet emitted. CSS custom properties are still defined on `:root` so user stylesheets can reference `var(--kb-*)` tokens. |


---

### KreuzbergTableModel

Which table structure recognition model to use.

Controls the model used for table cell detection within layout-detected
table regions.

| Value | Description |
|-------|-------------|
| `KREUZBERG_TATR` | TATR (Table Transformer) -- default, 30MB, DETR-based row/column detection. |
| `KREUZBERG_SLANET_WIRED` | SLANeXT wired variant -- 365MB, optimized for bordered tables. |
| `KREUZBERG_SLANET_WIRELESS` | SLANeXT wireless variant -- 365MB, optimized for borderless tables. |
| `KREUZBERG_SLANET_PLUS` | SLANet-plus -- 7.78MB, lightweight general-purpose. |
| `KREUZBERG_SLANET_AUTO` | Classifier-routed SLANeXT: auto-select wired/wireless per table. Uses PP-LCNet classifier (6.78MB) + both SLANeXT variants (730MB total). |
| `KREUZBERG_DISABLED` | Disable table structure model inference entirely; use heuristic path only. |


---

### KreuzbergPdfBackend

PDF extraction backend selection.

Controls which PDF library is used for text extraction:
- `Pdfium`: pdfium-render (default, C++ based, mature)
- `PdfOxide`: pdf_oxide (pure Rust, faster, requires `pdf-oxide` feature)
- `Auto`: automatically select based on available features

| Value | Description |
|-------|-------------|
| `KREUZBERG_PDFIUM` | Use pdfium-render backend (default). |
| `KREUZBERG_PDF_OXIDE` | Use pdf_oxide backend (pure Rust). Requires `pdf-oxide` feature. |
| `KREUZBERG_AUTO` | Automatically select the best available backend. |


---

### KreuzbergChunkerType

Type of text chunker to use.

# Variants

* `Text` - Generic text splitter, splits on whitespace and punctuation
* `Markdown` - Markdown-aware splitter, preserves formatting and structure
* `Yaml` - YAML-aware splitter, creates one chunk per top-level key

| Value | Description |
|-------|-------------|
| `KREUZBERG_TEXT` | Text format |
| `KREUZBERG_MARKDOWN` | Markdown format |
| `KREUZBERG_YAML` | Yaml format |


---

### KreuzbergChunkSizing

How chunk size is measured.

Defaults to `Characters` (Unicode character count). When using token-based sizing,
chunks are sized by token count according to the specified tokenizer.

Token-based sizing uses HuggingFace tokenizers loaded at runtime. Any tokenizer
available on HuggingFace Hub can be used, including OpenAI-compatible tokenizers
(e.g., `Xenova/gpt-4o`, `Xenova/cl100k_base`).

| Value | Description |
|-------|-------------|
| `KREUZBERG_CHARACTERS` | Size measured in Unicode characters (default). |
| `KREUZBERG_TOKENIZER` | Size measured in tokens from a HuggingFace tokenizer. |


---

### KreuzbergEmbeddingModelType

Embedding model types supported by Kreuzberg.

| Value | Description |
|-------|-------------|
| `KREUZBERG_PRESET` | Use a preset model configuration (recommended) |
| `KREUZBERG_CUSTOM` | Use a custom ONNX model from HuggingFace |
| `KREUZBERG_LLM` | Provider-hosted embedding model via liter-llm. Uses the model specified in the nested `LlmConfig` (e.g., `"openai/text-embedding-3-small"`). |


---

### KreuzbergCodeContentMode

Content rendering mode for code extraction.

Controls how extracted code content is represented in the `content` field
of `ExtractionResult`.

| Value | Description |
|-------|-------------|
| `KREUZBERG_CHUNKS` | Use TSLP semantic chunks as content (default). |
| `KREUZBERG_RAW` | Use raw source code as content. |
| `KREUZBERG_STRUCTURE` | Emit function/class headings + docstrings (no code bodies). |


---

### KreuzbergSecurityError

Security validation errors.

| Value | Description |
|-------|-------------|
| `KREUZBERG_ZIP_BOMB_DETECTED` | Potential ZIP bomb detected |
| `KREUZBERG_ARCHIVE_TOO_LARGE` | Archive exceeds maximum size |
| `KREUZBERG_TOO_MANY_FILES` | Archive contains too many files |
| `KREUZBERG_NESTING_TOO_DEEP` | Nesting too deep |
| `KREUZBERG_CONTENT_TOO_LARGE` | Content exceeds maximum size |
| `KREUZBERG_ENTITY_TOO_LONG` | Entity/string too long |
| `KREUZBERG_TOO_MANY_ITERATIONS` | Too many iterations |
| `KREUZBERG_XML_DEPTH_EXCEEDED` | XML depth exceeded |
| `KREUZBERG_TOO_MANY_CELLS` | Too many table cells |


---

### KreuzbergPdfAnnotationType

Type of PDF annotation.

| Value | Description |
|-------|-------------|
| `KREUZBERG_TEXT` | Sticky note / text annotation |
| `KREUZBERG_HIGHLIGHT` | Highlighted text region |
| `KREUZBERG_LINK` | Hyperlink annotation |
| `KREUZBERG_STAMP` | Rubber stamp annotation |
| `KREUZBERG_UNDERLINE` | Underline text markup |
| `KREUZBERG_STRIKE_OUT` | Strikeout text markup |
| `KREUZBERG_OTHER` | Any other annotation type |


---

### KreuzbergBlockType

Types of block-level elements in Djot.

| Value | Description |
|-------|-------------|
| `KREUZBERG_PARAGRAPH` | Paragraph element |
| `KREUZBERG_HEADING` | Heading element |
| `KREUZBERG_BLOCKQUOTE` | Blockquote element |
| `KREUZBERG_CODE_BLOCK` | Code block |
| `KREUZBERG_LIST_ITEM` | List item |
| `KREUZBERG_ORDERED_LIST` | Ordered list |
| `KREUZBERG_BULLET_LIST` | Bullet list |
| `KREUZBERG_TASK_LIST` | Task list |
| `KREUZBERG_DEFINITION_LIST` | Definition list |
| `KREUZBERG_DEFINITION_TERM` | Definition term |
| `KREUZBERG_DEFINITION_DESCRIPTION` | Definition description |
| `KREUZBERG_DIV` | Div |
| `KREUZBERG_SECTION` | Section element |
| `KREUZBERG_THEMATIC_BREAK` | Thematic break |
| `KREUZBERG_RAW_BLOCK` | Raw block |
| `KREUZBERG_MATH_DISPLAY` | Math display |


---

### KreuzbergInlineType

Types of inline elements in Djot.

| Value | Description |
|-------|-------------|
| `KREUZBERG_TEXT` | Text format |
| `KREUZBERG_STRONG` | Strong |
| `KREUZBERG_EMPHASIS` | Emphasis |
| `KREUZBERG_HIGHLIGHT` | Highlight |
| `KREUZBERG_SUBSCRIPT` | Subscript |
| `KREUZBERG_SUPERSCRIPT` | Superscript |
| `KREUZBERG_INSERT` | Insert |
| `KREUZBERG_DELETE` | Delete |
| `KREUZBERG_CODE` | Code |
| `KREUZBERG_LINK` | Link |
| `KREUZBERG_IMAGE` | Image element |
| `KREUZBERG_SPAN` | Span |
| `KREUZBERG_MATH` | Math |
| `KREUZBERG_RAW_INLINE` | Raw inline |
| `KREUZBERG_FOOTNOTE_REF` | Footnote ref |
| `KREUZBERG_SYMBOL` | Symbol |


---

### KreuzbergRelationshipKind

Semantic kind of a relationship between document elements.

| Value | Description |
|-------|-------------|
| `KREUZBERG_FOOTNOTE_REFERENCE` | Footnote marker -> footnote definition. |
| `KREUZBERG_CITATION_REFERENCE` | Citation marker -> bibliography entry. |
| `KREUZBERG_INTERNAL_LINK` | Internal anchor link (`#id`) -> target heading/element. |
| `KREUZBERG_CAPTION` | Caption paragraph -> figure/table it describes. |
| `KREUZBERG_LABEL` | Label -> labeled element (HTML `<label for>`, LaTeX `\label{}`). |
| `KREUZBERG_TOC_ENTRY` | TOC entry -> target section. |
| `KREUZBERG_CROSS_REFERENCE` | Cross-reference (LaTeX `\ref{}`, DOCX cross-reference field). |


---

### KreuzbergContentLayer

Content layer classification for document nodes.

Replaces separate body/furniture arrays with per-node granularity.

| Value | Description |
|-------|-------------|
| `KREUZBERG_BODY` | Main document body content. |
| `KREUZBERG_HEADER` | Page/section header (running header). |
| `KREUZBERG_FOOTER` | Page/section footer (running footer). |
| `KREUZBERG_FOOTNOTE` | Footnote content. |


---

### KreuzbergNodeContent

Tagged enum for node content. Each variant carries only type-specific data.

Uses `#[serde(tag = "node_type")]` to avoid "type" keyword collision in
Go/Java/TypeScript bindings.

| Value | Description |
|-------|-------------|
| `KREUZBERG_TITLE` | Document title. |
| `KREUZBERG_HEADING` | Section heading with level (1-6). |
| `KREUZBERG_PARAGRAPH` | Body text paragraph. |
| `KREUZBERG_LIST` | List container — children are `ListItem` nodes. |
| `KREUZBERG_LIST_ITEM` | Individual list item. |
| `KREUZBERG_TABLE` | Table with structured cell grid. |
| `KREUZBERG_IMAGE` | Image reference. |
| `KREUZBERG_CODE` | Code block. |
| `KREUZBERG_QUOTE` | Block quote — container, children carry the quoted content. |
| `KREUZBERG_FORMULA` | Mathematical formula / equation. |
| `KREUZBERG_FOOTNOTE` | Footnote reference content. |
| `KREUZBERG_GROUP` | Logical grouping container (section, key-value area). `heading_level` + `heading_text` capture the section heading directly rather than relying on a first-child positional convention. |
| `KREUZBERG_PAGE_BREAK` | Page break marker. |
| `KREUZBERG_SLIDE` | Presentation slide container — children are the slide's content nodes. |
| `KREUZBERG_DEFINITION_LIST` | Definition list container — children are `DefinitionItem` nodes. |
| `KREUZBERG_DEFINITION_ITEM` | Individual definition list entry with term and definition. |
| `KREUZBERG_CITATION` | Citation or bibliographic reference. |
| `KREUZBERG_ADMONITION` | Admonition / callout container (note, warning, tip, etc.). Children carry the admonition body content. |
| `KREUZBERG_RAW_BLOCK` | Raw block preserved verbatim from the source format. Used for content that cannot be mapped to a semantic node type (e.g. JSX in MDX, raw LaTeX in markdown, embedded HTML). |
| `KREUZBERG_METADATA_BLOCK` | Structured metadata block (email headers, YAML frontmatter, etc.). |


---

### KreuzbergAnnotationKind

Types of inline text annotations.

| Value | Description |
|-------|-------------|
| `KREUZBERG_BOLD` | Bold |
| `KREUZBERG_ITALIC` | Italic |
| `KREUZBERG_UNDERLINE` | Underline |
| `KREUZBERG_STRIKETHROUGH` | Strikethrough |
| `KREUZBERG_CODE` | Code |
| `KREUZBERG_SUBSCRIPT` | Subscript |
| `KREUZBERG_SUPERSCRIPT` | Superscript |
| `KREUZBERG_LINK` | Link |
| `KREUZBERG_HIGHLIGHT` | Highlighted text (PDF highlights, HTML `<mark>`). |
| `KREUZBERG_COLOR` | Text color (CSS-compatible value, e.g. "#ff0000", "red"). |
| `KREUZBERG_FONT_SIZE` | Font size with units (e.g. "12pt", "1.2em", "16px"). |
| `KREUZBERG_CUSTOM` | Extensible annotation for format-specific styling. |


---

### KreuzbergChunkType

Semantic structural classification of a text chunk.

Assigned by the heuristic classifier in `chunking.classifier`.
Defaults to `Unknown` when no rule matches.
Designed to be extended in future versions without breaking changes.

| Value | Description |
|-------|-------------|
| `KREUZBERG_HEADING` | Section heading or document title. |
| `KREUZBERG_PARTY_LIST` | Party list: names, addresses, and signatories. |
| `KREUZBERG_DEFINITIONS` | Definition clause ("X means…", "X shall mean…"). |
| `KREUZBERG_OPERATIVE_CLAUSE` | Operative clause containing legal/contractual action verbs. |
| `KREUZBERG_SIGNATURE_BLOCK` | Signature block with signatures, names, and dates. |
| `KREUZBERG_SCHEDULE` | Schedule, annex, appendix, or exhibit section. |
| `KREUZBERG_TABLE_LIKE` | Table-like content with aligned columns or repeated patterns. |
| `KREUZBERG_FORMULA` | Mathematical formula or equation. |
| `KREUZBERG_CODE_BLOCK` | Code block or preformatted content. |
| `KREUZBERG_IMAGE` | Embedded or referenced image content. |
| `KREUZBERG_ORG_CHART` | Organizational chart or hierarchy diagram. |
| `KREUZBERG_DIAGRAM` | Diagram, figure, or visual illustration. |
| `KREUZBERG_UNKNOWN` | Unclassified or mixed content. |


---

### KreuzbergElementType

Semantic element type classification.

Categorizes text content into semantic units for downstream processing.
Supports the element types commonly found in Unstructured documents.

| Value | Description |
|-------|-------------|
| `KREUZBERG_TITLE` | Document title |
| `KREUZBERG_NARRATIVE_TEXT` | Main narrative text body |
| `KREUZBERG_HEADING` | Section heading |
| `KREUZBERG_LIST_ITEM` | List item (bullet, numbered, etc.) |
| `KREUZBERG_TABLE` | Table element |
| `KREUZBERG_IMAGE` | Image element |
| `KREUZBERG_PAGE_BREAK` | Page break marker |
| `KREUZBERG_CODE_BLOCK` | Code block |
| `KREUZBERG_BLOCK_QUOTE` | Block quote |
| `KREUZBERG_FOOTER` | Footer text |
| `KREUZBERG_HEADER` | Header text |


---

### KreuzbergElementKind

Semantic role of an internal element.

Superset of `NodeContent` variants
plus OCR and container markers.

| Value | Description |
|-------|-------------|
| `KREUZBERG_TITLE` | Document title. |
| `KREUZBERG_HEADING` | Section heading with level (1-6). |
| `KREUZBERG_PARAGRAPH` | Body text paragraph. |
| `KREUZBERG_LIST_ITEM` | List item. `ordered` indicates numbered vs bulleted. |
| `KREUZBERG_CODE` | Code block. Language stored in element attributes. |
| `KREUZBERG_FORMULA` | Mathematical formula / equation. |
| `KREUZBERG_FOOTNOTE_DEFINITION` | Footnote content (the definition, not the reference marker). |
| `KREUZBERG_FOOTNOTE_REF` | Footnote reference marker in body text. |
| `KREUZBERG_CITATION` | Citation or bibliographic reference. |
| `KREUZBERG_SLIDE` | Presentation slide container. |
| `KREUZBERG_DEFINITION_TERM` | Definition list term. |
| `KREUZBERG_DEFINITION_DESCRIPTION` | Definition list description. |
| `KREUZBERG_ADMONITION` | Admonition / callout (note, warning, tip, etc.). Kind stored in attributes. |
| `KREUZBERG_RAW_BLOCK` | Raw block preserved verbatim. Format stored in attributes. |
| `KREUZBERG_METADATA_BLOCK` | Structured metadata block (frontmatter, email headers). |
| `KREUZBERG_LIST_START` | Start of a list container. |
| `KREUZBERG_LIST_END` | End of a list container. |
| `KREUZBERG_QUOTE_START` | Start of a block quote. |
| `KREUZBERG_QUOTE_END` | End of a block quote. |
| `KREUZBERG_GROUP_START` | Start of a generic group/section. |
| `KREUZBERG_GROUP_END` | End of a generic group/section. |
| `KREUZBERG_TABLE` | Table reference. `table_index` is an index into `InternalDocument.tables`. |
| `KREUZBERG_IMAGE` | Image reference. `image_index` is an index into `InternalDocument.images`. |
| `KREUZBERG_PAGE_BREAK` | Page break marker. |
| `KREUZBERG_OCR_TEXT` | OCR-detected text at a given hierarchical level. |


---

### KreuzbergRelationshipTarget

Target of a relationship — either a resolved element index or an unresolved key.

| Value | Description |
|-------|-------------|
| `KREUZBERG_INDEX` | Resolved: index into `InternalDocument.elements`. |
| `KREUZBERG_KEY` | Unresolved: key to be matched against element anchors during derivation. |


---

### KreuzbergFormatMetadata

Format-specific metadata (discriminated union).

Only one format type can exist per extraction result. This provides
type-safe, clean metadata without nested optionals.

| Value | Description |
|-------|-------------|
| `KREUZBERG_PDF` | Pdf format |
| `KREUZBERG_DOCX` | Docx format |
| `KREUZBERG_EXCEL` | Excel |
| `KREUZBERG_EMAIL` | Email |
| `KREUZBERG_PPTX` | Pptx format |
| `KREUZBERG_ARCHIVE` | Archive |
| `KREUZBERG_IMAGE` | Image element |
| `KREUZBERG_XML` | Xml format |
| `KREUZBERG_TEXT` | Text format |
| `KREUZBERG_HTML` | Html format |
| `KREUZBERG_OCR` | Ocr |
| `KREUZBERG_CSV` | Csv format |
| `KREUZBERG_BIBTEX` | Bibtex |
| `KREUZBERG_CITATION` | Citation |
| `KREUZBERG_FICTION_BOOK` | Fiction book |
| `KREUZBERG_DBF` | Dbf |
| `KREUZBERG_JATS` | Jats |
| `KREUZBERG_EPUB` | Epub format |
| `KREUZBERG_PST` | Pst |
| `KREUZBERG_CODE` | Code |


---

### KreuzbergTextDirection

Text direction enumeration for HTML documents.

| Value | Description |
|-------|-------------|
| `KREUZBERG_LEFT_TO_RIGHT` | Left-to-right text direction |
| `KREUZBERG_RIGHT_TO_LEFT` | Right-to-left text direction |
| `KREUZBERG_AUTO` | Automatic text direction detection |


---

### KreuzbergLinkType

Link type classification.

| Value | Description |
|-------|-------------|
| `KREUZBERG_ANCHOR` | Anchor link (#section) |
| `KREUZBERG_INTERNAL` | Internal link (same domain) |
| `KREUZBERG_EXTERNAL` | External link (different domain) |
| `KREUZBERG_EMAIL` | Email link (mailto:) |
| `KREUZBERG_PHONE` | Phone link (tel:) |
| `KREUZBERG_OTHER` | Other link type |


---

### KreuzbergImageType

Image type classification.

| Value | Description |
|-------|-------------|
| `KREUZBERG_DATA_URI` | Data URI image |
| `KREUZBERG_INLINE_SVG` | Inline SVG |
| `KREUZBERG_EXTERNAL` | External image URL |
| `KREUZBERG_RELATIVE` | Relative path image |


---

### KreuzbergStructuredDataType

Structured data type classification.

| Value | Description |
|-------|-------------|
| `KREUZBERG_JSON_LD` | JSON-LD structured data |
| `KREUZBERG_MICRODATA` | Microdata |
| `KREUZBERG_RD_FA` | RDFa |


---

### KreuzbergOcrBoundingGeometry

Bounding geometry for an OCR element.

Supports both axis-aligned rectangles (from Tesseract) and 4-point quadrilaterals
(from PaddleOCR and rotated text detection).

| Value | Description |
|-------|-------------|
| `KREUZBERG_RECTANGLE` | Axis-aligned bounding box (typical for Tesseract output). |
| `KREUZBERG_QUADRILATERAL` | 4-point quadrilateral for rotated/skewed text (PaddleOCR). Points are in clockwise order starting from top-left: `[top_left, top_right, bottom_right, bottom_left]` |


---

### KreuzbergOcrElementLevel

Hierarchical level of an OCR element.

Maps to Tesseract's page segmentation hierarchy and provides
equivalent semantics for PaddleOCR.

| Value | Description |
|-------|-------------|
| `KREUZBERG_WORD` | Individual word |
| `KREUZBERG_LINE` | Line of text (default for PaddleOCR) |
| `KREUZBERG_BLOCK` | Paragraph or text block |
| `KREUZBERG_PAGE` | Page-level element |


---

### KreuzbergPageUnitType

Type of paginated unit in a document.

Distinguishes between different types of "pages" (PDF pages, presentation slides, spreadsheet sheets).

| Value | Description |
|-------|-------------|
| `KREUZBERG_PAGE` | Standard document pages (PDF, DOCX, images) |
| `KREUZBERG_SLIDE` | Presentation slides (PPTX, ODP) |
| `KREUZBERG_SHEET` | Spreadsheet sheets (XLSX, ODS) |


---

### KreuzbergUriKind

Semantic classification of an extracted URI.

| Value | Description |
|-------|-------------|
| `KREUZBERG_HYPERLINK` | A clickable hyperlink (web URL, file link). |
| `KREUZBERG_IMAGE` | An image or media resource reference. |
| `KREUZBERG_ANCHOR` | An internal anchor or cross-reference target. |
| `KREUZBERG_CITATION` | A citation or bibliographic reference (DOI, academic ref). |
| `KREUZBERG_REFERENCE` | A general reference (e.g. `\ref{}` in LaTeX, `:ref:` in RST). |
| `KREUZBERG_EMAIL` | An email address (`mailto:` link or bare email). |


---

### KreuzbergPoolError

Error type for pool operations.

| Value | Description |
|-------|-------------|
| `KREUZBERG_LOCK_POISONED` | The pool's internal mutex was poisoned. This indicates a panic occurred while holding the lock. The pool is in a locked state and cannot be recovered. |


---

### KreuzbergExtractionSource

The source of a document to extract.

| Value | Description |
|-------|-------------|
| `KREUZBERG_FILE` | Extract from a filesystem path with an optional MIME type hint. |
| `KREUZBERG_BYTES` | Extract from in-memory bytes with a known MIME type. |


---

### KreuzbergKeywordAlgorithm

Keyword algorithm selection.

| Value | Description |
|-------|-------------|
| `KREUZBERG_YAKE` | YAKE (Yet Another Keyword Extractor) - statistical approach |
| `KREUZBERG_RAKE` | RAKE (Rapid Automatic Keyword Extraction) - co-occurrence based |


---

### KreuzbergOcrError

OCR-specific errors (pure Rust, no PyO3)

| Value | Description |
|-------|-------------|
| `KREUZBERG_TESSERACT_INITIALIZATION_FAILED` | Tesseract initialization failed |
| `KREUZBERG_UNSUPPORTED_VERSION` | Unsupported version |
| `KREUZBERG_INVALID_CONFIGURATION` | Invalid configuration |
| `KREUZBERG_INVALID_LANGUAGE_CODE` | Invalid language code |
| `KREUZBERG_IMAGE_PROCESSING_FAILED` | Image processing failed |
| `KREUZBERG_PROCESSING_FAILED` | Processing failed |
| `KREUZBERG_CACHE_ERROR` | Cache error |
| `KREUZBERG_IO_ERROR` | I o error |


---

### KreuzbergPsmMode

Page Segmentation Mode for Tesseract OCR

| Value | Description |
|-------|-------------|
| `KREUZBERG_OSD_ONLY` | Osd only |
| `KREUZBERG_AUTO_OSD` | Auto osd |
| `KREUZBERG_AUTO_ONLY` | Auto only |
| `KREUZBERG_AUTO` | Auto |
| `KREUZBERG_SINGLE_COLUMN` | Single column |
| `KREUZBERG_SINGLE_BLOCK_VERTICAL` | Single block vertical |
| `KREUZBERG_SINGLE_BLOCK` | Single block |
| `KREUZBERG_SINGLE_LINE` | Single line |
| `KREUZBERG_SINGLE_WORD` | Single word |
| `KREUZBERG_CIRCLE_WORD` | Circle word |
| `KREUZBERG_SINGLE_CHAR` | Single char |


---

### KreuzbergLayoutClass

The 17 canonical document layout classes.

All model backends (RT-DETR, YOLO, etc.) map their native class IDs
to this shared set. Models with fewer classes (DocLayNet: 11, PubLayNet: 5)
map to the closest equivalent.

| Value | Description |
|-------|-------------|
| `KREUZBERG_CAPTION` | Caption element |
| `KREUZBERG_FOOTNOTE` | Footnote element |
| `KREUZBERG_FORMULA` | Formula |
| `KREUZBERG_LIST_ITEM` | List item |
| `KREUZBERG_PAGE_FOOTER` | Page footer |
| `KREUZBERG_PAGE_HEADER` | Page header |
| `KREUZBERG_PICTURE` | Picture |
| `KREUZBERG_SECTION_HEADER` | Section header |
| `KREUZBERG_TABLE` | Table element |
| `KREUZBERG_TEXT` | Text format |
| `KREUZBERG_TITLE` | Title element |
| `KREUZBERG_DOCUMENT_INDEX` | Document index |
| `KREUZBERG_CODE` | Code |
| `KREUZBERG_CHECKBOX_SELECTED` | Checkbox selected |
| `KREUZBERG_CHECKBOX_UNSELECTED` | Checkbox unselected |
| `KREUZBERG_FORM` | Form |
| `KREUZBERG_KEY_VALUE_REGION` | Key value region |


---

### KreuzbergPdfError

| Value | Description |
|-------|-------------|
| `KREUZBERG_INVALID_PDF` | Invalid pdf |
| `KREUZBERG_PASSWORD_REQUIRED` | Password required |
| `KREUZBERG_INVALID_PASSWORD` | Invalid password |
| `KREUZBERG_ENCRYPTION_NOT_SUPPORTED` | Encryption not supported |
| `KREUZBERG_PAGE_NOT_FOUND` | Page not found |
| `KREUZBERG_TEXT_EXTRACTION_FAILED` | Text extraction failed |
| `KREUZBERG_RENDERING_FAILED` | Rendering failed |
| `KREUZBERG_METADATA_EXTRACTION_FAILED` | Metadata extraction failed |
| `KREUZBERG_EXTRACTION_FAILED` | Extraction failed |
| `KREUZBERG_FONT_LOADING_FAILED` | Font loading failed |
| `KREUZBERG_IO_ERROR` | I o error |


---

### KreuzbergHwpError

Error type for HWP parsing.

| Value | Description |
|-------|-------------|
| `KREUZBERG_INVALID_FORMAT` | The file does not match the HWP 5.0 format. |
| `KREUZBERG_UNSUPPORTED_VERSION` | The HWP version or a feature is not supported (e.g. password-encrypted docs). |
| `KREUZBERG_IO` | An underlying I/O error occurred. |
| `KREUZBERG_CFB` | A CFB compound-file error (stream not found, corrupt container, etc.). |
| `KREUZBERG_COMPRESSION_ERROR` | Decompression of a zlib/deflate stream failed. |
| `KREUZBERG_PARSE_ERROR` | The binary record stream could not be parsed. |
| `KREUZBERG_ENCODING_ERROR` | A UTF-16LE string contained invalid data. |
| `KREUZBERG_NOT_FOUND` | A requested stream was not present in the compound file. |


---

### KreuzbergDrawingType

Whether the drawing is inline or anchored.

| Value | Description |
|-------|-------------|
| `KREUZBERG_INLINE` | Inline |
| `KREUZBERG_ANCHORED` | Anchored |


---

### KreuzbergWrapType

Text wrapping type.

| Value | Description |
|-------|-------------|
| `KREUZBERG_NONE` | None |
| `KREUZBERG_SQUARE` | Square |
| `KREUZBERG_TIGHT` | Tight |
| `KREUZBERG_TOP_AND_BOTTOM` | Top and bottom |
| `KREUZBERG_THROUGH` | Through |


---

### KreuzbergFracType

| Value | Description |
|-------|-------------|
| `KREUZBERG_BAR` | Bar |
| `KREUZBERG_NO_BAR` | No bar |
| `KREUZBERG_LINEAR` | Linear |
| `KREUZBERG_SKEWED` | Skewed |


---

### KreuzbergMathNode

| Value | Description |
|-------|-------------|
| `KREUZBERG_RUN` | Plain text from m:r/m:t |
| `KREUZBERG_S_SUP` | Superscript: base^{sup} |
| `KREUZBERG_S_SUB` | Subscript: base_{sub} |
| `KREUZBERG_S_SUB_SUP` | Sub-superscript: base_{sub}^{sup} |
| `KREUZBERG_FRAC` | Fraction: \frac{num}{den} |
| `KREUZBERG_RAD` | Radical: \sqrt{body} or \sqrt[deg]{body} |
| `KREUZBERG_NARY` | N-ary operator: \sum_{sub}^{sup}{body} |
| `KREUZBERG_DELIM` | Delimiter: \left( ... \right) |
| `KREUZBERG_FUNC` | Function: \funcname{body} |
| `KREUZBERG_ACC` | Accent: \hat{body} |
| `KREUZBERG_EQ_ARR` | Equation array: \begin{aligned}...\end{aligned} |
| `KREUZBERG_LIM_LOW` | Lower limit: \underset{lim}{body} |
| `KREUZBERG_LIM_UPP` | Upper limit: \overset{lim}{body} |
| `KREUZBERG_BAR` | Bar (overline/underline) |
| `KREUZBERG_BORDER_BOX` | Border box: \boxed{body} |
| `KREUZBERG_MATRIX` | Matrix: \begin{matrix}...\end{matrix} |
| `KREUZBERG_GROUP` | Grouping container (m:box, m:phant, etc.) — passes through children |
| `KREUZBERG_S_PRE` | Pre-sub-superscript: {}_{sub}^{sup}{base} |


---

### KreuzbergDocumentElement

Tracks document element ordering (paragraphs, tables, and drawings interleaved).

| Value | Description |
|-------|-------------|
| `KREUZBERG_PARAGRAPH` | Paragraph element |
| `KREUZBERG_TABLE` | Table element |
| `KREUZBERG_DRAWING` | Drawing |


---

### KreuzbergListType

| Value | Description |
|-------|-------------|
| `KREUZBERG_BULLET` | Bullet |
| `KREUZBERG_NUMBERED` | Numbered |


---

### KreuzbergHeaderFooterType

| Value | Description |
|-------|-------------|
| `KREUZBERG_DEFAULT` | Default |
| `KREUZBERG_FIRST` | First |
| `KREUZBERG_EVEN` | Even |
| `KREUZBERG_ODD` | Odd |


---

### KreuzbergNoteType

| Value | Description |
|-------|-------------|
| `KREUZBERG_FOOTNOTE` | Footnote element |
| `KREUZBERG_ENDNOTE` | Endnote |


---

### KreuzbergOrientation

Page orientation.

| Value | Description |
|-------|-------------|
| `KREUZBERG_PORTRAIT` | Portrait |
| `KREUZBERG_LANDSCAPE` | Landscape |


---

### KreuzbergStyleType

The type of a style definition in DOCX.

| Value | Description |
|-------|-------------|
| `KREUZBERG_PARAGRAPH` | Paragraph element |
| `KREUZBERG_CHARACTER` | Character |
| `KREUZBERG_TABLE` | Table element |
| `KREUZBERG_NUMBERING` | Numbering |


---

### KreuzbergVerticalMerge

Vertical merge state.

| Value | Description |
|-------|-------------|
| `KREUZBERG_RESTART` | Restart |
| `KREUZBERG_CONTINUE` | Continue |


---

### KreuzbergThemeColor

A theme color definition, either direct RGB or a system color with fallback.

| Value | Description |
|-------|-------------|
| `KREUZBERG_RGB` | Direct hex RGB color (e.g., "156082"). |
| `KREUZBERG_SYSTEM` | System color with fallback RGB (e.g., "windowText" with lastClr "000000"). |


---

### KreuzbergPooling

Pooling strategy for extracting a single vector from token embeddings.

| Value | Description |
|-------|-------------|
| `KREUZBERG_CLS` | Use the [CLS] token embedding (first token). |
| `KREUZBERG_MEAN` | Mean of all token embeddings, weighted by attention mask. |


---

### KreuzbergEmbedError

Embedding engine errors.

| Value | Description |
|-------|-------------|
| `KREUZBERG_TOKENIZER` | Tokenizer |
| `KREUZBERG_ORT` | Ort |
| `KREUZBERG_SHAPE` | Shape |
| `KREUZBERG_NO_OUTPUT` | No output |


---

### KreuzbergModelBackend

Which underlying model architecture to use.

| Value | Description |
|-------|-------------|
| `KREUZBERG_YOLO_DOC_LAY_NET` | YOLO trained on DocLayNet (11 classes, 640x640 input). |
| `KREUZBERG_RT_DETR` | RT-DETR v2 (17 classes, 640x640 input, NMS-free). |
| `KREUZBERG_CUSTOM` | Custom model from a local file path. |


---

### KreuzbergCustomModelVariant

Variant selection for custom model paths.

| Value | Description |
|-------|-------------|
| `KREUZBERG_RT_DETR` | Rt detr |
| `KREUZBERG_YOLO_DOC_LAY_NET` | Yolo doc lay net |
| `KREUZBERG_YOLO_DOC_STRUCT_BENCH` | Yolo doc struct bench |
| `KREUZBERG_YOLOX` | Yolox |


---

### KreuzbergTableType

Table type classification result.

| Value | Description |
|-------|-------------|
| `KREUZBERG_WIRED` | Bordered table with visible gridlines. |
| `KREUZBERG_WIRELESS` | Borderless table without visible gridlines. |


---

### KreuzbergTatrClass

TATR object detection class labels.

The 7 classes output by the Table Transformer model. `NoObject` (class 6)
is the background/padding class and is filtered out during post-processing.

| Value | Description |
|-------|-------------|
| `KREUZBERG_TABLE` | Full table bounding box (class 0). |
| `KREUZBERG_COLUMN` | Table column (class 1). |
| `KREUZBERG_ROW` | Table row (class 2). |
| `KREUZBERG_COLUMN_HEADER` | Column header row (class 3). |
| `KREUZBERG_PROJECTED_ROW_HEADER` | Projected row header column (class 4). |
| `KREUZBERG_SPANNING_CELL` | Spanning cell covering multiple rows/columns (class 5). |


---

### KreuzbergYoloVariant

Which YOLO variant this model represents.

| Value | Description |
|-------|-------------|
| `KREUZBERG_DOC_LAY_NET` | YOLOv10/v8 trained on DocLayNet (11 classes). Output: [batch, num_dets, 6] = [x1, y1, x2, y2, score, class_id] |
| `KREUZBERG_DOC_STRUCT_BENCH` | DocLayout-YOLO trained on DocStructBench (10 classes). Output: [batch, num_dets, 4+num_classes] center-format, or [batch, num_dets, 6] decoded. |
| `KREUZBERG_YOLOX` | YOLOX with letterbox preprocessing and grid decoding. Output: [batch, num_anchors, 5+num_classes] — needs grid decoding + NMS. Strides: [8, 16, 32], anchors decoded via (raw + grid_offset) * stride. |


---

## Errors

### KreuzbergKreuzbergError

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
| `KREUZBERG_IO` | IO error: {0} |
| `KREUZBERG_PARSING` | Parsing error: {message} |
| `KREUZBERG_OCR` | OCR error: {message} |
| `KREUZBERG_VALIDATION` | Validation error: {message} |
| `KREUZBERG_CACHE` | Cache error: {message} |
| `KREUZBERG_IMAGE_PROCESSING` | Image processing error: {message} |
| `KREUZBERG_SERIALIZATION` | Serialization error: {message} |
| `KREUZBERG_MISSING_DEPENDENCY` | Missing dependency: {0} |
| `KREUZBERG_PLUGIN` | Plugin error in '{plugin_name}': {message} |
| `KREUZBERG_LOCK_POISONED` | Lock poisoned: {0} |
| `KREUZBERG_UNSUPPORTED_FORMAT` | Unsupported format: {0} |
| `KREUZBERG_EMBEDDING` | Embedding error: {message} |
| `KREUZBERG_TIMEOUT` | Extraction timed out after {elapsed_ms}ms (limit: {limit_ms}ms) |
| `KREUZBERG_OTHER` | {0} |


---

### KreuzbergLayoutError

| Variant | Description |
|---------|-------------|
| `KREUZBERG_ORT` | ORT error: {0} |
| `KREUZBERG_IMAGE` | Image error: {0} |
| `KREUZBERG_SESSION_NOT_INITIALIZED` | Session not initialized |
| `KREUZBERG_INVALID_OUTPUT` | Invalid model output: {0} |
| `KREUZBERG_MODEL_DOWNLOAD` | Model download failed: {0} |


---

