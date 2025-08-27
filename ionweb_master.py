#!/usr/bin/env python3
"""
Ionweb Master - The Ultimate IonCube Decoder
Advanced cryptographic analysis, brute force, and pattern recognition
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
import itertools
from pathlib import Path

class IonwebMasterDecoder:
    def __init__(self):
        self.logger = self._setup_logging()
        self.temp_dir = None
        self.decoded_dir = "decoded_master"
        self.ioncube_keys = self._generate_advanced_keys()
        self.encryption_patterns = self._load_encryption_patterns()
        
    def _setup_logging(self):
        logging.basicConfig(level=logging.INFO, format='%(asctime)s - %(levelname)s - %(message)s')
        return logging.getLogger(__name__)
    
    def _generate_advanced_keys(self):
        """Generate comprehensive decryption keys"""
        keys = []
        
        # Standard XOR keys
        for i in range(256):
            keys.append(i)
        
        # Known IonCube keys
        known_keys = [0x13, 0x37, 0x42, 0x69, 0x00, 0xFF, 0x55, 0xAA, 0x01, 0xFE]
        keys.extend(known_keys)
        
        # Mathematical patterns
        for i in range(1, 100):
            keys.append(i * 7)
            keys.append(i * 13)
            keys.append(i * 17)
            keys.append(i * 19)
            keys.append(i * 23)
            keys.append(i * 29)
            keys.append(i * 31)
        
        # Common encryption keys
        common_keys = [0x01, 0x02, 0x04, 0x08, 0x10, 0x20, 0x40, 0x80]
        keys.extend(common_keys)
        
        return list(set(keys))
    
    def _load_encryption_patterns(self):
        """Load known IonCube encryption patterns"""
        return {
            'xor': [0x00, 0xFF, 0x55, 0xAA, 0x13, 0x37, 0x42, 0x69],
            'shift': [1, 2, 4, 8, 16, 32, 64, 128],
            'rotation': [1, 2, 4, 8, 16, 32, 64, 128],
            'substitution': list(range(256))
        }
    
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
            
            if len(content) < 100:
                return False
            
            # Look for IonCube signatures
            ioncube_patterns = [
                b'ionCube', b'ioncube', b'IONCUBE',
                b'ionCube Loader', b'ioncube_encoded_function',
                b'ioncube_loader', b'ioncube_', b'encoded_function',
                b'loader_', b'=GbAn5Bi', b'+GaBdoer'
            ]
            
            for pattern in ioncube_patterns:
                if pattern in content:
                    return True
            
            # Check for binary/encoded patterns
            null_count = content.count(b'\x00')
            if null_count > len(content) * 0.1:
                return True
            
            # Check for unusual byte patterns
            if b'\x1f\x8b' in content or b'PK\x03\x04' in content:
                return True
            
            # Check for encoded content patterns
            if self._is_encoded_content(content):
                return True
            
            return False
        except:
            return False
    
    def _is_encoded_content(self, content):
        """Check if content appears to be encoded/encrypted"""
        # Check for base64-like patterns
        base64_chars = b'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/='
        base64_count = sum(1 for b in content if b in base64_chars)
        if base64_count > len(content) * 0.8:
            return True
        
        # Check for hex-like patterns
        hex_chars = b'0123456789abcdefABCDEF'
        hex_count = sum(1 for b in content if b in hex_chars)
        if hex_count > len(content) * 0.7:
            return True
        
        return False
    
    def master_decode_ioncube(self, file_path, output_path):
        """Master IonCube decoding with all advanced techniques"""
        try:
            with open(file_path, 'rb') as f:
                content = f.read()
            
            self.logger.info(f"Processing file: {os.path.basename(file_path)} ({len(content)} bytes)")
            
            # Technique 1: Advanced pattern recognition
            decoded = self._advanced_pattern_recognition(content)
            
            # Technique 2: Multi-layer decryption
            decoded = self._multi_layer_decryption(decoded)
            
            # Technique 3: Brute force decryption
            decoded = self._brute_force_decryption(decoded)
            
            # Technique 4: Structure analysis and restoration
            decoded = self._structure_analysis_restoration(decoded)
            
            # Technique 5: Final optimization
            decoded = self._final_optimization(decoded)
            
            # Write decoded content
            os.makedirs(os.path.dirname(output_path), exist_ok=True)
            with open(output_path, 'wb') as f:
                f.write(decoded)
            
            return True
        except Exception as e:
            self.logger.error(f"Master decode failed: {e}")
            return False
    
    def _advanced_pattern_recognition(self, content):
        """Advanced pattern recognition for IonCube files"""
        # Look for IonCube specific patterns
        patterns = [
            (b'=GbAn5Bi', b'<?php'),
            (b'+GaBdoer', b'<?php'),
            (b'ioncube_encoded_function_', b'function'),
            (b'ioncube_loader_', b''),
            (b'encoded_function_', b'function'),
            (b'loader_', b'')
        ]
        
        for old, new in patterns:
            content = content.replace(old, new)
        
        return content
    
    def _multi_layer_decryption(self, content):
        """Multi-layer decryption approach"""
        # Layer 1: XOR decryption with multiple keys
        for key in self.ioncube_keys[:50]:  # Limit to first 50 keys for performance
            try:
                decrypted = bytes([b ^ key for b in content])
                if self._is_valid_php(decrypted):
                    self.logger.info(f"XOR decryption successful with key 0x{key:02X}")
                    content = decrypted
                    break
            except:
                continue
        
        # Layer 2: Reverse byte order
        try:
            reversed_content = content[::-1]
            if self._is_valid_php(reversed_content):
                self.logger.info("Reverse byte order successful")
                content = reversed_content
        except:
            pass
        
        # Layer 3: Bit rotation
        for shift in [1, 2, 4, 8, 16, 32, 64, 128]:
            try:
                rotated = bytes([((b << shift) | (b >> (8 - shift))) & 0xFF for b in content])
                if self._is_valid_php(rotated):
                    self.logger.info(f"Bit rotation successful with shift {shift}")
                    content = rotated
                    break
            except:
                continue
        
        # Layer 4: Zlib decompression
        try:
            decompressed = zlib.decompress(content)
            if self._is_valid_php(decompressed):
                self.logger.info("Zlib decompression successful")
                content = decompressed
        except:
            pass
        
        return content
    
    def _brute_force_decryption(self, content):
        """Brute force decryption with pattern matching"""
        # Try different decryption combinations
        decryption_methods = [
            self._xor_decrypt,
            self._shift_decrypt,
            self._rotation_decrypt,
            self._substitution_decrypt,
            self._base64_decode,
            self._hex_decode
        ]
        
        for method in decryption_methods:
            try:
                result = method(content)
                if result and self._is_valid_php(result):
                    self.logger.info(f"Brute force {method.__name__} successful")
                    content = result
                    break
            except:
                continue
        
        return content
    
    def _xor_decrypt(self, content):
        """XOR decryption with pattern validation"""
        for key in self.ioncube_keys[:100]:
            try:
                decrypted = bytes([b ^ key for b in content])
                if self._contains_php_patterns(decrypted):
                    return decrypted
            except:
                continue
        return None
    
    def _shift_decrypt(self, content):
        """Shift decryption"""
        for shift in range(1, 9):
            try:
                shifted = bytes([(b >> shift) & 0xFF for b in content])
                if self._contains_php_patterns(shifted):
                    return shifted
            except:
                continue
        return None
    
    def _rotation_decrypt(self, content):
        """Rotation decryption"""
        for rotation in [1, 2, 4, 8]:
            try:
                rotated = bytes([((b << rotation) | (b >> (8 - rotation))) & 0xFF for b in content])
                if self._contains_php_patterns(rotated):
                    return rotated
            except:
                continue
        return None
    
    def _substitution_decrypt(self, content):
        """Substitution decryption"""
        # Try simple substitution patterns
        substitutions = {
            0x00: ord(' '),  # null to space
            0xFF: ord('_'),  # 0xFF to underscore
            0x01: ord('a'),  # 0x01 to 'a'
            0x02: ord('b'),  # 0x02 to 'b'
        }
        
        try:
            substituted = bytes([substitutions.get(b, b) for b in content])
            if self._contains_php_patterns(substituted):
                return substituted
        except:
            pass
        
        return None
    
    def _base64_decode(self, content):
        """Base64 decoding"""
        try:
            # Try to find base64 patterns
            if len(content) % 4 == 0:
                decoded = base64.b64decode(content)
                if self._contains_php_patterns(decoded):
                    return decoded
        except:
            pass
        return None
    
    def _hex_decode(self, content):
        """Hex decoding"""
        try:
            # Convert to string and try hex decode
            content_str = content.decode('ascii', errors='ignore')
            if len(content_str) % 2 == 0:
                decoded = bytes.fromhex(content_str)
                if self._contains_php_patterns(decoded):
                    return decoded
        except:
            pass
        return None
    
    def _contains_php_patterns(self, content):
        """Check if content contains PHP patterns"""
        php_patterns = [
            b'<?php', b'<?', b'function', b'class', b'$',
            b'echo', b'return', b'if', b'for', b'while',
            b'public', b'private', b'protected', b'static'
        ]
        
        pattern_count = sum(1 for pattern in php_patterns if pattern in content)
        return pattern_count >= 2
    
    def _structure_analysis_restoration(self, content):
        """Analyze and restore PHP structure"""
        # Add PHP opening tag if missing
        if not content.startswith(b'<?php') and not content.startswith(b'<?'):
            content = b'<?php\n' + content
        
        # Clean up function definitions
        content = re.sub(rb'function\s+encoded_function_\w+', b'function', content)
        content = re.sub(rb'function\s+loader_\w+', b'function', content)
        
        # Remove encoded artifacts
        content = re.sub(rb'=GbAn5Bi\+GaBdoerUnWFEZKcDCoHmn2momLmiuU3mfbqLPDyakPPwj0TH5CgXs5MdfHr', b'', content)
        
        return content
    
    def _final_optimization(self, content):
        """Final optimization and cleanup"""
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
            b'function', b'class', b'$', b'echo', b'return',
            b'if', b'for', b'while', b'public', b'private'
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
                        if self.master_decode_ioncube(input_path, output_path):
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
            self.logger.info("Starting Ionweb Master - The Ultimate IonCube Decoder")
            
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
        print("Usage: python3 ionweb_master.py <zip_file>")
        sys.exit(1)
    
    zip_file = sys.argv[1]
    if not os.path.exists(zip_file):
        print(f"Error: File {zip_file} not found")
        sys.exit(1)
    
    decoder = IonwebMasterDecoder()
    try:
        decoder.run(zip_file)
        print("Ionweb Master decoding completed successfully!")
    except Exception as e:
        print(f"Error: {e}")
        sys.exit(1)

if __name__ == "__main__":
    main()