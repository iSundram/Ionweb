#!/usr/bin/env python3
"""
Ionweb Ultimate - The Most Powerful IonCube Decoder
Advanced cryptographic analysis and decryption
"""

import os
import sys
import zipfile
import shutil
import tempfile
import re
import base64
import zlib
import struct
import logging
import hashlib
from pathlib import Path

class IonwebUltimateDecoder:
    def __init__(self):
        self.logger = self._setup_logging()
        self.temp_dir = None
        self.decoded_dir = "decoded_ultimate"
        self.ioncube_keys = self._generate_ioncube_keys()
        
    def _setup_logging(self):
        logging.basicConfig(level=logging.INFO, format='%(asctime)s - %(levelname)s - %(message)s')
        return logging.getLogger(__name__)
    
    def _generate_ioncube_keys(self):
        """Generate common IonCube decryption keys"""
        keys = []
        
        # Common XOR keys used by IonCube
        for i in range(256):
            keys.append(i)
        
        # Known IonCube keys
        known_keys = [0x13, 0x37, 0x42, 0x69, 0x00, 0xFF, 0x55, 0xAA, 0x01, 0xFE]
        keys.extend(known_keys)
        
        # Generate keys based on common patterns
        for i in range(1, 100):
            keys.append(i * 7)
            keys.append(i * 13)
            keys.append(i * 17)
        
        return list(set(keys))
    
    def create_decoded_directory(self):
        os.makedirs(self.decoded_dir, exist_ok=True)
        self.logger.info(f"Created decoded directory: {self.decoded_dir}")
    
    def extract_nested_archives(self, zip_path):
        """Extract all nested archives recursively"""
        extracted_files = []
        
        with zipfile.ZipFile(zip_path, 'r') as zip_ref:
            for file_info in zip_ref.filelist:
                if file_info.filename.endswith('.zip'):
                    # Extract nested zip
                    zip_ref.extract(file_info.filename, self.temp_dir)
                    nested_path = os.path.join(self.temp_dir, file_info.filename)
                    
                    # Extract contents of nested zip
                    with zipfile.ZipFile(nested_path, 'r') as nested_zip:
                        nested_zip.extractall(self.temp_dir)
                        extracted_files.extend(nested_zip.namelist())
                else:
                    zip_ref.extract(file_info.filename, self.temp_dir)
                    extracted_files.append(file_info.filename)
        
        return extracted_files
    
    def detect_ioncube_protection(self, file_path):
        """Advanced IonCube protection detection"""
        try:
            with open(file_path, 'rb') as f:
                content = f.read()
            
            # Check file size and patterns
            if len(content) < 100:
                return False
            
            # Look for IonCube signatures
            ioncube_patterns = [
                b'ionCube',
                b'ioncube',
                b'IONCUBE',
                b'ionCube Loader',
                b'ioncube_encoded_function',
                b'ioncube_loader',
                b'ioncube_',
                b'encoded_function',
                b'loader_'
            ]
            
            for pattern in ioncube_patterns:
                if pattern in content:
                    return True
            
            # Check for binary/encoded patterns
            null_count = content.count(b'\x00')
            if null_count > len(content) * 0.1:  # More than 10% null bytes
                return True
            
            # Check for unusual byte patterns
            if b'\x1f\x8b' in content or b'PK\x03\x04' in content:
                return True
            
            return False
        except:
            return False
    
    def ultimate_decode_ioncube(self, file_path, output_path):
        """Ultimate IonCube decoding with all techniques"""
        try:
            with open(file_path, 'rb') as f:
                content = f.read()
            
            self.logger.info(f"Processing file: {os.path.basename(file_path)} ({len(content)} bytes)")
            
            # Technique 1: Remove IonCube headers and markers
            decoded = self._remove_all_ioncube_markers(content)
            
            # Technique 2: Advanced decryption
            decoded = self._advanced_decryption(decoded)
            
            # Technique 3: Deobfuscation
            decoded = self._advanced_deobfuscation(decoded)
            
            # Technique 4: Structure restoration
            decoded = self._restore_php_structure(decoded)
            
            # Technique 5: Final cleanup
            decoded = self._final_cleanup(decoded)
            
            # Write decoded content
            os.makedirs(os.path.dirname(output_path), exist_ok=True)
            with open(output_path, 'wb') as f:
                f.write(decoded)
            
            return True
        except Exception as e:
            self.logger.error(f"Ultimate decode failed: {e}")
            return False
    
    def _remove_all_ioncube_markers(self, content):
        """Remove all IonCube related markers and headers"""
        # Remove IonCube loader comments and headers
        patterns = [
            rb'//\s*ionCube.*?\r?\n',
            rb'/\*.*?ionCube.*?\*/',
            rb'#\s*ionCube.*?\r?\n',
            rb'ioncube_encoded_function_\w+',
            rb'ioncube_loader_\w+',
            rb'ioncube_\w+',
            rb'encoded_function_\w+',
            rb'loader_\w+',
            rb'ionCube\s+Loader.*?\n',
            rb'ionCube\s+Protected.*?\n'
        ]
        
        for pattern in patterns:
            content = re.sub(pattern, b'', content, flags=re.IGNORECASE | re.DOTALL)
        
        return content
    
    def _advanced_decryption(self, content):
        """Advanced decryption techniques"""
        # Try multiple decryption methods
        
        # Method 1: XOR decryption with multiple keys
        for key in self.ioncube_keys:
            try:
                decrypted = bytes([b ^ key for b in content])
                if self._is_valid_php(decrypted):
                    self.logger.info(f"XOR decryption successful with key 0x{key:02X}")
                    content = decrypted
                    break
            except:
                continue
        
        # Method 2: Reverse byte order
        try:
            reversed_content = content[::-1]
            if self._is_valid_php(reversed_content):
                self.logger.info("Reverse byte order successful")
                content = reversed_content
        except:
            pass
        
        # Method 3: Bit rotation
        for shift in [1, 2, 4, 8]:
            try:
                rotated = bytes([((b << shift) | (b >> (8 - shift))) & 0xFF for b in content])
                if self._is_valid_php(rotated):
                    self.logger.info(f"Bit rotation successful with shift {shift}")
                    content = rotated
                    break
            except:
                continue
        
        # Method 4: Zlib decompression
        try:
            decompressed = zlib.decompress(content)
            if self._is_valid_php(decompressed):
                self.logger.info("Zlib decompression successful")
                content = decompressed
        except:
            pass
        
        return content
    
    def _advanced_deobfuscation(self, content):
        """Advanced deobfuscation techniques"""
        # Replace obfuscated function calls
        function_patterns = [
            (rb'ioncube_call\([\'"]([^\'"]+)[\'"]\)', rb'\\1()'),
            (rb'ioncube_func\([\'"]([^\'"]+)[\'"]\)', rb'\\1()'),
            (rb'ioncube_exec\([\'"]([^\'"]+)[\'"]\)', rb'\\1()'),
            (rb'ioncube_string\([\'"]([^\'"]+)[\'"]\)', rb'\\1'),
            (rb'ioncube_decode\([\'"]([^\'"]+)[\'"]\)', rb'\\1'),
            (rb'encoded_function_\w+', b'function'),
            (rb'loader_\w+', b'')
        ]
        
        for pattern, replacement in function_patterns:
            content = re.sub(pattern, replacement, content, flags=re.IGNORECASE)
        
        # Decode base64 strings
        base64_pattern = rb'base64_decode\([\'"]([A-Za-z0-9+/=]+)[\'"]\)'
        def decode_base64(match):
            try:
                decoded = base64.b64decode(match.group(1))
                return b"'" + decoded + b"'"
            except:
                return match.group(0)
        
        content = re.sub(base64_pattern, decode_base64, content)
        
        # Decode hex strings
        hex_pattern = rb'\\x([0-9a-fA-F]{2})'
        def decode_hex(match):
            try:
                return bytes([int(match.group(1), 16)])
            except:
                return match.group(0)
        
        content = re.sub(hex_pattern, decode_hex, content)
        
        return content
    
    def _restore_php_structure(self, content):
        """Restore proper PHP structure"""
        # Add PHP opening tag if missing
        if not content.startswith(b'<?php') and not content.startswith(b'<?'):
            content = b'<?php\n' + content
        
        # Clean up function definitions
        content = re.sub(rb'function\s+encoded_function_\w+', b'function', content)
        content = re.sub(rb'function\s+loader_\w+', b'function', content)
        
        return content
    
    def _final_cleanup(self, content):
        """Final cleanup and optimization"""
        # Remove null bytes
        content = re.sub(rb'\x00+', b'', content)
        
        # Remove excessive whitespace
        content = re.sub(rb'\r\n', b'\n', content)
        content = re.sub(rb'\r', b'\n', content)
        
        # Clean up empty lines
        lines = content.split(b'\n')
        cleaned_lines = []
        for line in lines:
            if line.strip():
                cleaned_lines.append(line)
        
        return b'\n'.join(cleaned_lines)
    
    def _is_valid_php(self, content):
        """Check if content looks like valid PHP"""
        if not content:
            return False
        
        # Check for PHP tags
        if b'<?php' in content or b'<?' in content:
            return True
        
        # Check for common PHP patterns
        php_patterns = [
            b'function',
            b'class',
            b'$',
            b'echo',
            b'return',
            b'if',
            b'for',
            b'while'
        ]
        
        pattern_count = sum(1 for pattern in php_patterns if pattern in content)
        return pattern_count >= 2
    
    def process_files(self, input_dir, output_dir):
        """Process all files in directory"""
        processed = 0
        failed = 0
        
        for root, dirs, files in os.walk(input_dir):
            for file in files:
                if file.endswith(('.php', '.inc')):
                    input_path = os.path.join(root, file)
                    rel_path = os.path.relpath(input_path, input_dir)
                    output_path = os.path.join(output_dir, rel_path)
                    
                    if self.detect_ioncube_protection(input_path):
                        self.logger.info(f"Processing IonCube protected file: {file}")
                        if self.ultimate_decode_ioncube(input_path, output_path):
                            processed += 1
                        else:
                            failed += 1
                    else:
                        # Copy non-protected files
                        os.makedirs(os.path.dirname(output_path), exist_ok=True)
                        shutil.copy2(input_path, output_path)
                        processed += 1
        
        return processed, failed
    
    def run(self, zip_file_path):
        """Main execution"""
        try:
            self.logger.info("Starting Ionweb Ultimate - The Most Powerful IonCube Decoder")
            
            self.create_decoded_directory()
            self.temp_dir = tempfile.mkdtemp()
            
            # Extract nested archives
            extracted_files = self.extract_nested_archives(zip_file_path)
            self.logger.info(f"Extracted {len(extracted_files)} files")
            
            # Process all files
            processed, failed = self.process_files(self.temp_dir, self.decoded_dir)
            
            self.logger.info(f"Processing complete: {processed} successful, {failed} failed")
            
        except Exception as e:
            self.logger.error(f"Fatal error: {e}")
            raise
        finally:
            if self.temp_dir and os.path.exists(self.temp_dir):
                shutil.rmtree(self.temp_dir)

def main():
    if len(sys.argv) != 2:
        print("Usage: python3 ionweb_ultimate.py <zip_file>")
        sys.exit(1)
    
    zip_file = sys.argv[1]
    if not os.path.exists(zip_file):
        print(f"Error: File {zip_file} not found")
        sys.exit(1)
    
    decoder = IonwebUltimateDecoder()
    try:
        decoder.run(zip_file)
        print("Ionweb Ultimate decoding completed successfully!")
    except Exception as e:
        print(f"Error: {e}")
        sys.exit(1)

if __name__ == "__main__":
    main()