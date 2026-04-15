---
title: "C API Reference"
---

# C API Reference <span class="version-badge">v4.8.5</span>

## Functions

### htm_is_valid_format_field()

Validates whether a field name is in the known formats registry.

This uses a pre-built hash set for O(1) lookups instead of linear search,
providing significant performance improvements for repeated validations.

**Returns:**

`true` if the field is in KNOWN_FORMATS, `false` otherwise.

**Signature:**

```c
bool* htm_is_valid_format_field(const char* field);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `field` | `const char*` | Yes | The field name to validate |

**Returns:** `bool`


---

### htm_detect_mime_type()

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
const char** htm_detect_mime_type(const char* path, bool check_exists);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `path` | `const char*` | Yes | Path to the file |
| `check_exists` | `bool` | Yes | Whether to verify file existence |

**Returns:** `const char*`

**Errors:** Returns `NULL` on error.


---

### htm_validate_mime_type()

Validate that a MIME type is supported.

**Returns:**

The validated MIME type (may be normalized).

**Errors:**

Returns `KreuzbergError.UnsupportedFormat` if not supported.

**Signature:**

```c
const char** htm_validate_mime_type(const char* mime_type);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `mime_type` | `const char*` | Yes | The MIME type to validate |

**Returns:** `const char*`

**Errors:** Returns `NULL` on error.


---

### htm_detect_mime_type_from_bytes()

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
const char** htm_detect_mime_type_from_bytes(const uint8_t* content);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `content` | `const uint8_t*` | Yes | Raw file bytes |

**Returns:** `const char*`

**Errors:** Returns `NULL` on error.


---

### htm_get_extensions_for_mime()

Get file extensions for a given MIME type.

Returns all known file extensions that map to the specified MIME type.

**Returns:**

A vector of file extensions (without leading dot) for the MIME type.

**Signature:**

```c
const char*** htm_get_extensions_for_mime(const char* mime_type);
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `mime_type` | `const char*` | Yes | The MIME type to look up |

**Returns:** `const char**`

**Errors:** Returns `NULL` on error.


---

### htm_list_supported_formats()

List all supported document formats.

Returns a list of all file extensions and their corresponding MIME types
that Kreuzberg can process. Derived from the centralized `FORMATS` registry.

The list is sorted alphabetically by file extension.

**Signature:**

```c
HTMSupportedFormat** htm_list_supported_formats();
```

**Returns:** `HTMSupportedFormat*`


---

## Types

### HTMLlmUsage

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

### HTMSupportedFormat

A supported document format entry.

Represents a file extension and its corresponding MIME type that Kreuzberg can process.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `extension` | `const char*` | — | File extension (without leading dot), e.g., "pdf", "docx" |
| `mime_type` | `const char*` | — | MIME type string, e.g., "application/pdf" |


---

## Errors

### HTMKreuzbergError

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
| `HTM_IO` | IO error: {0} |
| `HTM_PARSING` | Parsing error: {message} |
| `HTM_OCR` | OCR error: {message} |
| `HTM_VALIDATION` | Validation error: {message} |
| `HTM_CACHE` | Cache error: {message} |
| `HTM_IMAGE_PROCESSING` | Image processing error: {message} |
| `HTM_SERIALIZATION` | Serialization error: {message} |
| `HTM_MISSING_DEPENDENCY` | Missing dependency: {0} |
| `HTM_PLUGIN` | Plugin error in '{plugin_name}': {message} |
| `HTM_LOCK_POISONED` | Lock poisoned: {0} |
| `HTM_UNSUPPORTED_FORMAT` | Unsupported format: {0} |
| `HTM_EMBEDDING` | Embedding error: {message} |
| `HTM_TIMEOUT` | Extraction timed out after {elapsed_ms}ms (limit: {limit_ms}ms) |
| `HTM_OTHER` | {0} |


---

