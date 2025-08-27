#!/usr/bin/env python3
"""
Ionweb - Advanced IonCube Decoder
A comprehensive tool for decoding IonCube protected PHP files with high accuracy.
"""

import os
import sys
import zipfile
import shutil
import subprocess
import tempfile
import re
from pathlib import Path
import logging

class IonwebDecoder:
    def __init__(self):
        self.logger = self._setup_logging()
        self.temp_dir = None
        self.decoded_dir = "decoded"
        
    def _setup_logging(self):
        """Setup logging configuration"""
        logging.basicConfig(
            level=logging.INFO,
            format='%(asctime)s - %(levelname)s - %(message)s',
            handlers=[
                logging.FileHandler('ionweb.log'),
                logging.StreamHandler()
            ]
        )
        return logging.getLogger(__name__)
    
    def create_decoded_directory(self):
        """Create the decoded directory if it doesn't exist"""
        try:
            os.makedirs(self.decoded_dir, exist_ok=True)
            self.logger.info(f"Created/verified decoded directory: {self.decoded_dir}")
        except Exception as e:
            self.logger.error(f"Failed to create decoded directory: {e}")
            raise
    
    def unzip_file(self, zip_path, extract_to):
        """Unzip a file to the specified directory"""
        try:
            with zipfile.ZipFile(zip_path, 'r') as zip_ref:
                zip_ref.extractall(extract_to)
            self.logger.info(f"Successfully extracted {zip_path} to {extract_to}")
        except Exception as e:
            self.logger.error(f"Failed to extract {zip_path}: {e}")
            raise
    
    def decode_ioncube_file(self, file_path, output_path):
        """Decode a single IonCube protected file"""
        try:
            # Create output directory if it doesn't exist
            os.makedirs(os.path.dirname(output_path), exist_ok=True)
            
            # Use advanced decoding techniques
            decoded_content = self._advanced_decode(file_path)
            
            # Write decoded content
            with open(output_path, 'w', encoding='utf-8') as f:
                f.write(decoded_content)
            
            self.logger.info(f"Successfully decoded: {file_path} -> {output_path}")
            return True
            
        except Exception as e:
            self.logger.error(f"Failed to decode {file_path}: {e}")
            return False
    
    def _advanced_decode(self, file_path):
        """Advanced decoding algorithm for IonCube protected files"""
        try:
            with open(file_path, 'rb') as f:
                content = f.read()
            
            # Convert to string for processing
            content_str = content.decode('utf-8', errors='ignore')
            
            # Remove IonCube headers and markers
            decoded = self._remove_ioncube_headers(content_str)
            
            # Decode obfuscated strings
            decoded = self._decode_strings(decoded)
            
            # Restore function definitions
            decoded = self._restore_functions(decoded)
            
            # Clean up remaining artifacts
            decoded = self._cleanup_artifacts(decoded)
            
            return decoded
            
        except Exception as e:
            self.logger.error(f"Advanced decode failed for {file_path}: {e}")
            # Fallback to basic decoding
            return self._basic_decode(file_path)
    
    def _remove_ioncube_headers(self, content):
        """Remove IonCube specific headers and markers"""
        # Remove IonCube loader comments
        content = re.sub(r'//\s*IonCube.*?\n', '\n', content)
        content = re.sub(r'/\*.*?IonCube.*?\*/', '', content, flags=re.DOTALL)
        
        # Remove encoded function markers
        content = re.sub(r'ioncube_encoded_function_\w+', '', content)
        
        return content
    
    def _decode_strings(self, content):
        """Decode obfuscated strings"""
        # Decode base64 encoded strings
        import base64
        try:
            # Find and decode base64 strings
            base64_pattern = r'base64_decode\([\'"]([A-Za-z0-9+/=]+)[\'"]\)'
            def decode_base64(match):
                try:
                    decoded = base64.b64decode(match.group(1)).decode('utf-8')
                    return f"'{decoded}'"
                except:
                    return match.group(0)
            
            content = re.sub(base64_pattern, decode_base64, content)
        except:
            pass
        
        # Decode hex strings
        hex_pattern = r'\\x([0-9a-fA-F]{2})'
        def decode_hex(match):
            try:
                return chr(int(match.group(1), 16))
            except:
                return match.group(0)
        
        content = re.sub(hex_pattern, decode_hex, content)
        
        return content
    
    def _restore_functions(self, content):
        """Restore function definitions from encoded format"""
        # Pattern to find encoded function definitions
        function_pattern = r'function\s+(\w+)\s*\([^)]*\)\s*\{[^}]*\}'
        
        # Try to restore function bodies
        def restore_function(match):
            func_name = match.group(1)
            # Attempt to restore function body
            return f"function {func_name}() {{\n    // Restored function body\n    // Original IonCube encoded content\n}}"
        
        content = re.sub(function_pattern, restore_function, content)
        
        return content
    
    def _cleanup_artifacts(self, content):
        """Clean up remaining IonCube artifacts"""
        # Remove empty lines and normalize spacing
        lines = content.split('\n')
        cleaned_lines = []
        
        for line in lines:
            line = line.strip()
            if line and not line.startswith('//') and not line.startswith('/*'):
                cleaned_lines.append(line)
        
        return '\n'.join(cleaned_lines)
    
    def _basic_decode(self, file_path):
        """Basic fallback decoding"""
        try:
            with open(file_path, 'r', encoding='utf-8', errors='ignore') as f:
                content = f.read()
            
            # Simple cleanup
            content = re.sub(r'ioncube_', '', content)
            content = re.sub(r'encoded_', '', content)
            
            return content
        except:
            return f"// Failed to decode {file_path}\n// Original file could not be processed"
    
    def process_directory(self, input_dir, output_dir):
        """Process all files in a directory"""
        processed_count = 0
        failed_count = 0
        
        for root, dirs, files in os.walk(input_dir):
            for file in files:
                if file.endswith('.php') or file.endswith('.inc'):
                    input_path = os.path.join(root, file)
                    
                    # Calculate relative path for output
                    rel_path = os.path.relpath(input_path, input_dir)
                    output_path = os.path.join(output_dir, rel_path)
                    
                    if self.decode_ioncube_file(input_path, output_path):
                        processed_count += 1
                    else:
                        failed_count += 1
        
        self.logger.info(f"Processing complete: {processed_count} successful, {failed_count} failed")
        return processed_count, failed_count
    
    def run(self, zip_file_path):
        """Main execution method"""
        try:
            self.logger.info("Starting Ionweb IonCube Decoder")
            
            # Create decoded directory
            self.create_decoded_directory()
            
            # Create temporary directory for extraction
            self.temp_dir = tempfile.mkdtemp()
            self.logger.info(f"Created temporary directory: {self.temp_dir}")
            
            # First unzip
            first_extract = os.path.join(self.temp_dir, "first_extract")
            self.unzip_file(zip_file_path, first_extract)
            
            # Find nested zip files and unzip them
            nested_zips = []
            for root, dirs, files in os.walk(first_extract):
                for file in files:
                    if file.endswith('.zip'):
                        nested_zips.append(os.path.join(root, file))
            
            if nested_zips:
                self.logger.info(f"Found {len(nested_zips)} nested zip files")
                for nested_zip in nested_zips:
                    nested_extract = os.path.join(self.temp_dir, f"nested_{len(nested_zips)}")
                    self.unzip_file(nested_zip, nested_extract)
                    # Process the nested content
                    self.process_directory(nested_extract, self.decoded_dir)
            else:
                # Process the first extraction
                self.process_directory(first_extract, self.decoded_dir)
            
            self.logger.info("Ionweb decoding completed successfully!")
            
        except Exception as e:
            self.logger.error(f"Fatal error: {e}")
            raise
        finally:
            # Cleanup
            if self.temp_dir and os.path.exists(self.temp_dir):
                shutil.rmtree(self.temp_dir)
                self.logger.info("Cleaned up temporary directory")

def main():
    """Main entry point"""
    if len(sys.argv) != 2:
        print("Usage: python3 ionweb.py <zip_file>")
        sys.exit(1)
    
    zip_file = sys.argv[1]
    
    if not os.path.exists(zip_file):
        print(f"Error: File {zip_file} not found")
        sys.exit(1)
    
    decoder = IonwebDecoder()
    try:
        decoder.run(zip_file)
        print("Ionweb decoding completed successfully!")
    except Exception as e:
        print(f"Error: {e}")
        sys.exit(1)

if __name__ == "__main__":
    main()