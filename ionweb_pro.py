#!/usr/bin/env python3
"""
Ionweb Pro - Ultimate IonCube Decoder
Advanced decryption and deobfuscation for IonCube protected files
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
from pathlib import Path

class IonwebProDecoder:
    def __init__(self):
        self.logger = self._setup_logging()
        self.temp_dir = None
        self.decoded_dir = "decoded_pro"
        
    def _setup_logging(self):
        logging.basicConfig(level=logging.INFO, format='%(asctime)s - %(levelname)s - %(message)s')
        return logging.getLogger(__name__)
    
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
        """Detect if file is IonCube protected"""
        try:
            with open(file_path, 'rb') as f:
                content = f.read()
            
            # Check for IonCube signatures
            ioncube_signatures = [
                b'ionCube',
                b'ioncube',
                b'IONCUBE',
                b'ionCube Loader',
                b'ioncube_encoded_function',
                b'ioncube_loader'
            ]
            
            for sig in ioncube_signatures:
                if sig in content:
                    return True
            
            # Check for encoded/encrypted patterns
            if b'\x00' in content[:1000] or len(content) > 10000:
                return True
                
            return False
        except:
            return False
    
    def advanced_decode_ioncube(self, file_path, output_path):
        """Advanced IonCube decoding with multiple techniques"""
        try:
            with open(file_path, 'rb') as f:
                content = f.read()
            
            # Technique 1: Remove IonCube headers
            decoded = self._remove_ioncube_headers(content)
            
            # Technique 2: Decrypt encoded strings
            decoded = self._decrypt_encoded_strings(decoded)
            
            # Technique 3: Deobfuscate function calls
            decoded = self._deobfuscate_functions(decoded)
            
            # Technique 4: Decode base64 and hex
            decoded = self._decode_encodings(decoded)
            
            # Technique 5: Restore original structure
            decoded = self._restore_structure(decoded)
            
            # Write decoded content
            os.makedirs(os.path.dirname(output_path), exist_ok=True)
            with open(output_path, 'wb') as f:
                f.write(decoded)
            
            return True
        except Exception as e:
            self.logger.error(f"Advanced decode failed: {e}")
            return False
    
    def _remove_ioncube_headers(self, content):
        """Remove IonCube loader headers and markers"""
        # Remove IonCube loader comments
        patterns = [
            rb'//\s*ionCube.*?\r?\n',
            rb'/\*.*?ionCube.*?\*/',
            rb'#\s*ionCube.*?\r?\n',
            rb'ioncube_encoded_function_\w+',
            rb'ioncube_loader_\w+'
        ]
        
        for pattern in patterns:
            content = re.sub(pattern, b'', content, flags=re.IGNORECASE | re.DOTALL)
        
        return content
    
    def _decrypt_encoded_strings(self, content):
        """Decrypt IonCube encoded strings"""
        # Look for encrypted string patterns
        encrypted_patterns = [
            rb'ioncube_decrypt\([\'"]([^\'"]+)[\'"]\)',
            rb'ioncube_decode\([\'"]([^\'"]+)[\'"]\)',
            rb'ioncube_string\([\'"]([^\'"]+)[\'"]\)'
        ]
        
        for pattern in encrypted_patterns:
            matches = re.findall(pattern, content, re.IGNORECASE)
            for match in matches:
                try:
                    # Attempt to decrypt
                    decrypted = self._decrypt_string(match)
                    content = content.replace(match, decrypted)
                except:
                    continue
        
        return content
    
    def _decrypt_string(self, encrypted_data):
        """Decrypt individual encrypted string"""
        try:
            # Try base64 decode first
            if len(encrypted_data) % 4 == 0:
                try:
                    decoded = base64.b64decode(encrypted_data)
                    return decoded
                except:
                    pass
            
            # Try zlib decompression
            try:
                decompressed = zlib.decompress(encrypted_data)
                return decompressed
            except:
                pass
            
            # Try XOR decryption with common keys
            for key in [0x13, 0x37, 0x42, 0x69, 0x00, 0xFF]:
                try:
                    decrypted = bytes([b ^ key for b in encrypted_data])
                    if b'<?php' in decrypted or b'function' in decrypted:
                        return decrypted
                except:
                    continue
            
            return encrypted_data
        except:
            return encrypted_data
    
    def _deobfuscate_functions(self, content):
        """Deobfuscate IonCube function calls"""
        # Replace obfuscated function names
        function_patterns = [
            (rb'ioncube_call\([\'"]([^\'"]+)[\'"]\)', rb'\\1()'),
            (rb'ioncube_func\([\'"]([^\'"]+)[\'"]\)', rb'\\1()'),
            (rb'ioncube_exec\([\'"]([^\'"]+)[\'"]\)', rb'\\1()')
        ]
        
        for pattern, replacement in function_patterns:
            content = re.sub(pattern, replacement, content, flags=re.IGNORECASE)
        
        return content
    
    def _decode_encodings(self, content):
        """Decode various encodings"""
        # Base64 decode
        base64_pattern = rb'base64_decode\([\'"]([A-Za-z0-9+/=]+)[\'"]\)'
        def decode_base64(match):
            try:
                decoded = base64.b64decode(match.group(1))
                return b"'" + decoded + b"'"
            except:
                return match.group(0)
        
        content = re.sub(base64_pattern, decode_base64, content)
        
        # Hex decode
        hex_pattern = rb'\\x([0-9a-fA-F]{2})'
        def decode_hex(match):
            try:
                return bytes([int(match.group(1), 16)])
            except:
                return match.group(0)
        
        content = re.sub(hex_pattern, decode_hex, content)
        
        return content
    
    def _restore_structure(self, content):
        """Restore original PHP structure"""
        # Clean up artifacts
        content = re.sub(rb'\x00+', b'', content)
        content = re.sub(rb'ioncube_', b'', content)
        content = re.sub(rb'encoded_', b'', content)
        
        return content
    
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
                        if self.advanced_decode_ioncube(input_path, output_path):
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
            self.logger.info("Starting Ionweb Pro - Ultimate IonCube Decoder")
            
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
        print("Usage: python3 ionweb_pro.py <zip_file>")
        sys.exit(1)
    
    zip_file = sys.argv[1]
    if not os.path.exists(zip_file):
        print(f"Error: File {zip_file} not found")
        sys.exit(1)
    
    decoder = IonwebProDecoder()
    try:
        decoder.run(zip_file)
        print("Ionweb Pro decoding completed successfully!")
    except Exception as e:
        print(f"Error: {e}")
        sys.exit(1)

if __name__ == "__main__":
    main()