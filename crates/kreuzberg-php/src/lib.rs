use ext_php_rs::prelude::*;

#[derive(Clone, serde::Serialize, serde::Deserialize)]
#[php_class]
#[php(name = "Kreuzberg\\SupportedFormat")]
pub struct SupportedFormat {
    /// File extension (without leading dot), e.g., "pdf", "docx"
    #[php(prop, name = "extension")]
    pub extension: String,
    /// MIME type string, e.g., "application/pdf"
    #[php(prop, name = "mime_type")]
    pub mime_type: String,
}

#[php_impl]
impl SupportedFormat {
    pub fn __construct(extension: String, mime_type: String) -> Self {
        Self { extension, mime_type }
    }
}

#[derive(Clone, serde::Serialize, serde::Deserialize, Default)]
#[php_class]
#[php(name = "Kreuzberg\\LlmUsage")]
pub struct LlmUsage {
    /// The LLM model identifier (e.g. "openai/gpt-4o", "anthropic/claude-sonnet-4-20250514").
    #[php(prop, name = "model")]
    pub model: String,
    /// The pipeline stage that triggered this LLM call
    /// (e.g. "vlm_ocr", "structured_extraction", "embeddings").
    #[php(prop, name = "source")]
    pub source: String,
    /// Number of input/prompt tokens consumed.
    #[php(prop, name = "input_tokens")]
    pub input_tokens: Option<i64>,
    /// Number of output/completion tokens generated.
    #[php(prop, name = "output_tokens")]
    pub output_tokens: Option<i64>,
    /// Total tokens (input + output).
    #[php(prop, name = "total_tokens")]
    pub total_tokens: Option<i64>,
    /// Estimated cost in USD based on the provider's published pricing.
    #[php(prop, name = "estimated_cost")]
    pub estimated_cost: Option<f64>,
    /// Why the model stopped generating (e.g. "stop", "length", "content_filter").
    #[php(prop, name = "finish_reason")]
    pub finish_reason: Option<String>,
}

#[php_impl]
impl LlmUsage {
    pub fn __construct(
        model: Option<String>,
        source: Option<String>,
        input_tokens: Option<i64>,
        output_tokens: Option<i64>,
        total_tokens: Option<i64>,
        estimated_cost: Option<f64>,
        finish_reason: Option<String>,
    ) -> Self {
        Self {
            model: model.unwrap_or_default(),
            source: source.unwrap_or_default(),
            input_tokens,
            output_tokens,
            total_tokens,
            estimated_cost,
            finish_reason,
        }
    }
}

#[php_class]
#[php(name = "Kreuzberg\\KreuzbergApi")]
pub struct KreuzbergApi;

#[php_impl]
impl KreuzbergApi {
    pub fn is_valid_format_field(field: String) -> bool {
        kreuzberg::is_valid_format_field(&field)
    }

    pub fn detect_mime_type(_path: String, _check_exists: bool) -> PhpResult<String> {
        Err(ext_php_rs::exception::PhpException::default(
            "Not implemented: detect_mime_type".to_string(),
        ))
    }

    pub fn validate_mime_type(mime_type: String) -> PhpResult<String> {
        let result = kreuzberg::validate_mime_type(&mime_type)
            .map_err(|e| ext_php_rs::exception::PhpException::default(e.to_string()))?;
        Ok(result)
    }

    pub fn detect_mime_type_from_bytes(content: Vec<u8>) -> PhpResult<String> {
        let result = kreuzberg::detect_mime_type_from_bytes(&content)
            .map_err(|e| ext_php_rs::exception::PhpException::default(e.to_string()))?;
        Ok(result)
    }

    pub fn get_extensions_for_mime(mime_type: String) -> PhpResult<Vec<String>> {
        let result = kreuzberg::get_extensions_for_mime(&mime_type)
            .map_err(|e| ext_php_rs::exception::PhpException::default(e.to_string()))?;
        Ok(result)
    }

    pub fn list_supported_formats() -> Vec<SupportedFormat> {
        kreuzberg::list_supported_formats()
            .into_iter()
            .map(Into::into)
            .collect()
    }
}

impl From<SupportedFormat> for kreuzberg::SupportedFormat {
    fn from(val: SupportedFormat) -> Self {
        Self {
            extension: val.extension,
            mime_type: val.mime_type,
        }
    }
}

impl From<kreuzberg::SupportedFormat> for SupportedFormat {
    fn from(val: kreuzberg::SupportedFormat) -> Self {
        Self {
            extension: val.extension,
            mime_type: val.mime_type,
        }
    }
}

impl From<LlmUsage> for kreuzberg::LlmUsage {
    fn from(val: LlmUsage) -> Self {
        Self {
            model: val.model,
            source: val.source,
            input_tokens: val.input_tokens.map(|v| v as u64),
            output_tokens: val.output_tokens.map(|v| v as u64),
            total_tokens: val.total_tokens.map(|v| v as u64),
            estimated_cost: val.estimated_cost,
            finish_reason: val.finish_reason,
        }
    }
}

impl From<kreuzberg::LlmUsage> for LlmUsage {
    fn from(val: kreuzberg::LlmUsage) -> Self {
        Self {
            model: val.model,
            source: val.source,
            input_tokens: val.input_tokens.map(|v| v as i64),
            output_tokens: val.output_tokens.map(|v| v as i64),
            total_tokens: val.total_tokens.map(|v| v as i64),
            estimated_cost: val.estimated_cost,
            finish_reason: val.finish_reason,
        }
    }
}

/// Convert a `kreuzberg::error::KreuzbergError` error to a PHP exception.
#[allow(dead_code)]
fn kreuzberg_error_to_php_err(e: kreuzberg::error::KreuzbergError) -> ext_php_rs::exception::PhpException {
    let msg = e.to_string();
    #[allow(unreachable_patterns)]
    match &e {
        kreuzberg::error::KreuzbergError::Io(..) => {
            ext_php_rs::exception::PhpException::default(format!("[Io] {}", msg))
        }
        kreuzberg::error::KreuzbergError::Parsing { .. } => {
            ext_php_rs::exception::PhpException::default(format!("[Parsing] {}", msg))
        }
        kreuzberg::error::KreuzbergError::Ocr { .. } => {
            ext_php_rs::exception::PhpException::default(format!("[Ocr] {}", msg))
        }
        kreuzberg::error::KreuzbergError::Validation { .. } => {
            ext_php_rs::exception::PhpException::default(format!("[Validation] {}", msg))
        }
        kreuzberg::error::KreuzbergError::Cache { .. } => {
            ext_php_rs::exception::PhpException::default(format!("[Cache] {}", msg))
        }
        kreuzberg::error::KreuzbergError::ImageProcessing { .. } => {
            ext_php_rs::exception::PhpException::default(format!("[ImageProcessing] {}", msg))
        }
        kreuzberg::error::KreuzbergError::Serialization { .. } => {
            ext_php_rs::exception::PhpException::default(format!("[Serialization] {}", msg))
        }
        kreuzberg::error::KreuzbergError::MissingDependency(..) => {
            ext_php_rs::exception::PhpException::default(format!("[MissingDependency] {}", msg))
        }
        kreuzberg::error::KreuzbergError::Plugin { .. } => {
            ext_php_rs::exception::PhpException::default(format!("[Plugin] {}", msg))
        }
        kreuzberg::error::KreuzbergError::LockPoisoned(..) => {
            ext_php_rs::exception::PhpException::default(format!("[LockPoisoned] {}", msg))
        }
        kreuzberg::error::KreuzbergError::UnsupportedFormat(..) => {
            ext_php_rs::exception::PhpException::default(format!("[UnsupportedFormat] {}", msg))
        }
        kreuzberg::error::KreuzbergError::Embedding { .. } => {
            ext_php_rs::exception::PhpException::default(format!("[Embedding] {}", msg))
        }
        kreuzberg::error::KreuzbergError::Timeout { .. } => {
            ext_php_rs::exception::PhpException::default(format!("[Timeout] {}", msg))
        }
        kreuzberg::error::KreuzbergError::Other(..) => {
            ext_php_rs::exception::PhpException::default(format!("[Other] {}", msg))
        }
        _ => ext_php_rs::exception::PhpException::default(msg),
    }
}

#[php_module]
pub fn get_module(module: ModuleBuilder) -> ModuleBuilder {
    module
        .class::<SupportedFormat>()
        .class::<LlmUsage>()
        .class::<KreuzbergApi>()
}
