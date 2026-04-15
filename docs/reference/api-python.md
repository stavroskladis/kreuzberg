---
title: "Python API Reference"
---

# Python API Reference <span class="version-badge">v4.8.5</span>

## Functions

### is_valid_format_field()

Validates whether a field name is in the known formats registry.

This uses a pre-built hash set for O(1) lookups instead of linear search,
providing significant performance improvements for repeated validations.

**Returns:**

`True` if the field is in KNOWN_FORMATS, `False` otherwise.

**Signature:**

```python
def is_valid_format_field(field: str) -> bool
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `field` | `str` | Yes | The field name to validate |

**Returns:** `bool`


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

```python
def detect_mime_type(path: str, check_exists: bool) -> str
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `path` | `str` | Yes | Path to the file |
| `check_exists` | `bool` | Yes | Whether to verify file existence |

**Returns:** `str`

**Errors:** Raises `Error`.


---

### validate_mime_type()

Validate that a MIME type is supported.

**Returns:**

The validated MIME type (may be normalized).

**Errors:**

Returns `KreuzbergError.UnsupportedFormat` if not supported.

**Signature:**

```python
def validate_mime_type(mime_type: str) -> str
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `mime_type` | `str` | Yes | The MIME type to validate |

**Returns:** `str`

**Errors:** Raises `Error`.


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

```python
def detect_mime_type_from_bytes(content: bytes) -> str
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `content` | `bytes` | Yes | Raw file bytes |

**Returns:** `str`

**Errors:** Raises `Error`.


---

### get_extensions_for_mime()

Get file extensions for a given MIME type.

Returns all known file extensions that map to the specified MIME type.

**Returns:**

A vector of file extensions (without leading dot) for the MIME type.

**Signature:**

```python
def get_extensions_for_mime(mime_type: str) -> list[str]
```

**Parameters:**

| Name | Type | Required | Description |
|------|------|----------|-------------|
| `mime_type` | `str` | Yes | The MIME type to look up |

**Returns:** `list[str]`

**Errors:** Raises `Error`.


---

### list_supported_formats()

List all supported document formats.

Returns a list of all file extensions and their corresponding MIME types
that Kreuzberg can process. Derived from the centralized `FORMATS` registry.

The list is sorted alphabetically by file extension.

**Signature:**

```python
def list_supported_formats() -> list[SupportedFormat]
```

**Returns:** `list[SupportedFormat]`


---

## Types

### LlmUsage

Token usage and cost data for a single LLM call made during extraction.

Populated when VLM OCR, structured extraction, or LLM-based embeddings
are used. Multiple entries may be present when multiple LLM calls occur
within one extraction (e.g. VLM OCR + structured extraction).

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `model` | `str` | `None` | The LLM model identifier (e.g. "openai/gpt-4o", "anthropic/claude-sonnet-4-20250514"). |
| `source` | `str` | `None` | The pipeline stage that triggered this LLM call (e.g. "vlm_ocr", "structured_extraction", "embeddings"). |
| `input_tokens` | `int | None` | `None` | Number of input/prompt tokens consumed. |
| `output_tokens` | `int | None` | `None` | Number of output/completion tokens generated. |
| `total_tokens` | `int | None` | `None` | Total tokens (input + output). |
| `estimated_cost` | `float | None` | `None` | Estimated cost in USD based on the provider's published pricing. |
| `finish_reason` | `str | None` | `None` | Why the model stopped generating (e.g. "stop", "length", "content_filter"). |


---

### SupportedFormat

A supported document format entry.

Represents a file extension and its corresponding MIME type that Kreuzberg can process.

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `extension` | `str` | — | File extension (without leading dot), e.g., "pdf", "docx" |
| `mime_type` | `str` | — | MIME type string, e.g., "application/pdf" |


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
| `IO` | IO error: {0} |
| `PARSING` | Parsing error: {message} |
| `OCR` | OCR error: {message} |
| `VALIDATION` | Validation error: {message} |
| `CACHE` | Cache error: {message} |
| `IMAGE_PROCESSING` | Image processing error: {message} |
| `SERIALIZATION` | Serialization error: {message} |
| `MISSING_DEPENDENCY` | Missing dependency: {0} |
| `PLUGIN` | Plugin error in '{plugin_name}': {message} |
| `LOCK_POISONED` | Lock poisoned: {0} |
| `UNSUPPORTED_FORMAT` | Unsupported format: {0} |
| `EMBEDDING` | Embedding error: {message} |
| `TIMEOUT` | Extraction timed out after {elapsed_ms}ms (limit: {limit_ms}ms) |
| `OTHER` | {0} |


---

