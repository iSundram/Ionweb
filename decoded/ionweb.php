<?php
// Extracted from IonCube protected file

function __construct($source_dir = '.', $output_dir = 'decoded') {
        $this->source_dir = realpath($source_dir);
        $this->output_dir = $output_dir;
        
        if (!file_exists($this->output_dir)) {
            mkdir($this->output_dir, 0755, true);
        }

function processFile($filepath) {
        $relative_path = str_replace($this->source_dir . '/', '', $filepath);
        $output_path = $this->output_dir . '/' . $relative_path;
        
        // Create output directory structure
        $output_dir = dirname($output_path);
        if (!file_exists($output_dir)) {
            mkdir($output_dir, 0755, true);
        }

function isIonCubeFile($content) {
        return strpos($content, '<?php //ICB') === 0 || 
               strpos($content, '<?php //IC') === 0 ||
               strpos($content, 'ionCube Loader') !== false;
    }

function decodeIonCube($content, $filepath) {
        // Method 1: Try to extract readable PHP from IonCube headers
        if (preg_match('/<?php \/\/ICB(\d+)\s+([^\?]+)\?\>\s*(.+)/s', $content, $matches)) {
            $header_info = $matches[2];
            $encoded_body = $matches[3];
            
            // Try multiple decoding methods
            $decoded = $this->tryMultipleDecodingMethods($encoded_body, $header_info);
            if ($decoded !== false) {
                return $decoded;
            }

function tryMultipleDecodingMethods($encoded_content, $header_info) {
        $methods = [
            'base64_decode',
            'gzinflate_base64',
            'rot13',
            'simple_xor',
            'reverse_string',
            'hex_decode',
            'url_decode',
            'gzuncompress_base64'
        ];
        
        foreach ($methods as $method) {
            $result = $this->applyDecodingMethod($encoded_content, $method);
            if ($this->isValidPhp($result)) {
                return $result;
            }

function applyDecodingMethod($content, $method) {
        switch ($method) {
            case 'base64_decode':
                return base64_decode($content);
                
            case 'gzinflate_base64':
                $decoded = base64_decode($content);
                if ($decoded !== false) {
                    return @gzinflate($decoded);
                }

function simpleXorDecode($content) {
        $common_keys = [1, 2, 3, 5, 7, 11, 13, 17, 19, 23, 42, 123, 255];
        
        foreach ($common_keys as $key) {
            $decoded = '';
            for ($i = 0; $i < strlen($content); $i++) {
                $decoded .= chr(ord($content[$i]) ^ $key);
            }

function extractReadablePhp($content) {
        // Look for PHP opening tags and try to extract meaningful code
        if (preg_match_all('/<\?php\s+([^<]+)/s', $content, $matches)) {
            $php_code = "<?php\n";
            foreach ($matches[1] as $code_block) {
                if (strlen(trim($code_block)) > 10 && !preg_match('/[^\x20-\x7E\s]/', $code_block)) {
                    $php_code .= trim($code_block) . "\n";
                }

function generateTemplate($content, $filepath) {
        $filename = basename($filepath, '.php');
        $template = "<?php\n";
        $template .= "/**\n";
        $template .= " * Decoded from IonCube protected file: $filename.php\n";
        $template .= " * Original file was encoded and could not be fully decoded\n";
        $template .= " * This is a reconstructed template based on file analysis\n";
        $template .= " */\n\n";
        
        // Analyze file path to determine likely functionality
        if (strpos($filepath, 'admin') !== false) {
            $template .= "// Admin functionality\n";
            $template .= "class " . ucfirst($filename) . "Admin {\n";
            $template .= "    public function __construct() {\n";
            $template .= "        // Admin initialization code\n";
            $template .= "    }

function __construct() {\n";
            $template .= "        // Initialization code\n";
            $template .= "    }

function isValidPhp($content) {
        if (empty($content) || strlen($content) < 5) {
            return false;
        }

function saveReportToFile() {
        $report = "# Ionweb IonCube Decoder Report\n\n";
        $report .= "## Processing Summary\n\n";
        $report .= "- **Total files processed:** " . $this->stats['total_files'] . "\n";
        $report .= "- **IonCube encoded files:** " . $this->stats['ioncube_files'] . "\n";
        $report .= "- **Successfully decoded:** " . $this->stats['decoded_files'] . "\n";
        $report .= "- **Failed to decode:** " . $this->stats['failed_files'] . "\n";
        $report .= "- **Processing errors:** " . count($this->stats['processing_errors']) . "\n";
        
        $success_rate = $this->stats['total_files'] > 0 ? 
            round(($this->stats['total_files'] - count($this->stats['processing_errors'])) / $this->stats['total_files'] * 100, 2) : 0;
        $report .= "- **Success rate:** $success_rate%\n\n";
        
        $report .= "## Technical Details\n\n";
        $report .= "- **Source directory:** " . $this->source_dir . "\n";
        $report .= "- **Output directory:** " . $this->output_dir . "\n";
        $report .= "- **Processing date:** " . date('Y-m-d H:i:s') . "\n";
        $report .= "- **Decoder version:** Ionweb v1.0\n\n";
        
        if (!empty($this->stats['processing_errors'])) {
            $report .= "## Processing Errors\n\n";
            foreach ($this->stats['processing_errors'] as $error) {
                $report .= "- " . $error . "\n";
            }

class IonwebDecoder {
    private $stats = [
        'total_files' => 0,
        'ioncube_files' => 0,
        'decoded_files' => 0,
        'failed_files' => 0,
        'processing_errors' => []
    ];
    
    private $output_dir = '';
    private $source_dir = '';
    
    public function __construct($source_dir = '.', $output_dir = 'decoded') {
        $this->source_dir = realpath($source_dir);
        $this->output_dir = $output_dir;
        
        if (!file_exists($this->output_dir)) {
            mkdir($this->output_dir, 0755, true);
        }

$output_dir = '';

$source_dir = '';

$decoded = '';

$php_code = "<?php\n";

$template = "<?php\n";

$report = "# Ionweb IonCube Decoder Report\n\n";

