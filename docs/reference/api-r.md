---
title: "R API Reference"
---

# R API Reference <span class="version-badge">v4.8.5</span>

## Functions

### is_valid_format_field()

Validates whether a field name is in the known formats registry.

This uses a pre-built hash set for O(1) lookups instead of linear search,
providing significant performance improvements for repeated validations.

**Returns:**

`true` if the field is in KNOWN_FORMATS, `false` otherwise.

**Signature:**

```r
is_valid_format_field(field)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `field` | `character` | Yes | The field name to validate |

**Returns:** `logical`


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

```r
detect_mime_type(path, check_exists)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `path` | `character` | Yes | Path to the file |
| `check_exists` | `logical` | Yes | Whether to verify file existence |

**Returns:** `character`

**Errors:** Stops with error message.


---

### validate_mime_type()

Validate that a MIME type is supported.

**Returns:**

The validated MIME type (may be normalized).

**Errors:**

Returns `KreuzbergError.UnsupportedFormat` if not supported.

**Signature:**

```r
validate_mime_type(mime_type)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `mime_type` | `character` | Yes | The MIME type to validate |

**Returns:** `character`

**Errors:** Stops with error message.


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

```r
detect_mime_type_from_bytes(content)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `content` | `raw` | Yes | Raw file bytes |

**Returns:** `character`

**Errors:** Stops with error message.


---

### get_extensions_for_mime()

Get file extensions for a given MIME type.

Returns all known file extensions that map to the specified MIME type.

**Returns:**

A vector of file extensions (without leading dot) for the MIME type.

**Signature:**

```r
get_extensions_for_mime(mime_type)
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `mime_type` | `character` | Yes | The MIME type to look up |

**Returns:** `list`

**Errors:** Stops with error message.


---

### list_supported_formats()

List all supported document formats.

Returns a list of all file extensions and their corresponding MIME types
that Kreuzberg can process. Derived from the centralized `FORMATS` registry.

The list is sorted alphabetically by file extension.

**Signature:**

```r
list_supported_formats()
```

**Returns:** `list`


---

## Types

### LlmUsage

Token usage and cost data for a single LLM call made during extraction.

Populated when VLM OCR, structured extraction, or LLM-based embeddings
are used. Multiple entries may be present when multiple LLM calls occur
within one extraction (e.g. VLM OCR + structured extraction).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `model` | `character` | `NULL` | The LLM model identifier (e.g. "openai/gpt-4o", "anthropic/claude-sonnet-4-20250514"). |
| `source` | `character` | `NULL` | The pipeline stage that triggered this LLM call (e.g. "vlm_ocr", "structured_extraction", "embeddings"). |
| `input_tokens` | `integer or NULL` | `NULL` | Number of input/prompt tokens consumed. |
| `output_tokens` | `integer or NULL` | `NULL` | Number of output/completion tokens generated. |
| `total_tokens` | `integer or NULL` | `NULL` | Total tokens (input + output). |
| `estimated_cost` | `numeric or NULL` | `NULL` | Estimated cost in USD based on the provider's published pricing. |
| `finish_reason` | `character or NULL` | `NULL` | Why the model stopped generating (e.g. "stop", "length", "content_filter"). |


---

### SupportedFormat

A supported document format entry.

Represents a file extension and its corresponding MIME type that Kreuzberg can process.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `extension` | `character` | — | File extension (without leading dot), e.g., "pdf", "docx" |
| `mime_type` | `character` | — | MIME type string, e.g., "application/pdf" |


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
| `io` | IO error: {0} |
| `parsing` | Parsing error: {message} |
| `ocr` | OCR error: {message} |
| `validation` | Validation error: {message} |
| `cache` | Cache error: {message} |
| `image_processing` | Image processing error: {message} |
| `serialization` | Serialization error: {message} |
| `missing_dependency` | Missing dependency: {0} |
| `plugin` | Plugin error in '{plugin_name}': {message} |
| `lock_poisoned` | Lock poisoned: {0} |
| `unsupported_format` | Unsupported format: {0} |
| `embedding` | Embedding error: {message} |
| `timeout` | Extraction timed out after {elapsed_ms}ms (limit: {limit_ms}ms) |
| `other` | {0} |


---

