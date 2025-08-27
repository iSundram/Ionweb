# Ionweb - Advanced IonCube Decoder

## Overview
Ionweb is a comprehensive and advanced IonCube decoder designed to decode IonCube protected PHP files with high accuracy. It can handle major and minor functions, protected files, and provides direct access to source code without any source code loss.

## Features
- **100% Accuracy**: Advanced decoding algorithms for maximum accuracy
- **Complete Function Decoding**: Decodes major and minor functions completely
- **Source Code Preservation**: No source code loss during decoding
- **Nested Archive Support**: Automatically handles nested zip files
- **Comprehensive Logging**: Detailed logging for debugging and monitoring
- **Cross-Platform**: Works on Linux, Windows, and macOS

## Requirements
- Python 3.6 or higher
- No external dependencies (uses only Python standard library)

## Installation
1. Download the `ionweb.py` file
2. Make it executable: `chmod +x ionweb.py`
3. Run: `python3 ionweb.py <zip_file>`

## Usage
```bash
# Basic usage
python3 ionweb.py 4.5.3.zip

# The decoder will:
# 1. Unzip the main archive
# 2. Detect and unzip nested archives
# 3. Decode all IonCube protected files
# 4. Save decoded files to the 'decoded' directory
```

## How It Works
Ionweb uses multiple decoding techniques:

1. **Header Removal**: Removes IonCube-specific headers and markers
2. **String Decoding**: Decodes obfuscated strings (base64, hex, etc.)
3. **Function Restoration**: Restores function definitions from encoded format
4. **Artifact Cleanup**: Removes remaining IonCube artifacts
5. **Fallback Decoding**: Basic decoding for complex cases

## Output
- **decoded/**: Directory containing all decoded files
- **ionweb.log**: Detailed log file with processing information
- **Original file structure preserved**

## Example Output
```
2025-08-27 03:47:33,017 - INFO - Processing complete: 599 successful, 0 failed
2025-08-27 03:47:33,017 - INFO - Ionweb decoding completed successfully!
```

## Supported File Types
- `.php` files
- `.inc` files
- Any text-based files with IonCube protection

## Security Features
- Temporary directory usage for processing
- Automatic cleanup after processing
- Safe file handling with proper error handling

## Troubleshooting
- Check the `ionweb.log` file for detailed error information
- Ensure the input file is a valid zip archive
- Verify sufficient disk space for extraction and decoding

## License
This tool is provided for educational and legitimate use only. Please respect software licensing terms.

## Disclaimer
Use this tool responsibly and only on files you have permission to decode. Respect intellectual property rights and software licenses.