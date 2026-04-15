---
title: "Error Reference"
---

# Error Reference

All error types thrown by the library across all languages.

## KreuzbergError

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

| Variant | Message | Description |
|---------|---------|-------------|
| `Io` | IO error: {0} |  |
| `Parsing` | Parsing error: {message} |  |
| `Ocr` | OCR error: {message} |  |
| `Validation` | Validation error: {message} |  |
| `Cache` | Cache error: {message} |  |
| `ImageProcessing` | Image processing error: {message} |  |
| `Serialization` | Serialization error: {message} |  |
| `MissingDependency` | Missing dependency: {0} |  |
| `Plugin` | Plugin error in '{plugin_name}': {message} |  |
| `LockPoisoned` | Lock poisoned: {0} |  |
| `UnsupportedFormat` | Unsupported format: {0} |  |
| `Embedding` | Embedding error: {message} |  |
| `Timeout` | Extraction timed out after {elapsed_ms}ms (limit: {limit_ms}ms) |  |
| `Other` | {0} |  |

---
