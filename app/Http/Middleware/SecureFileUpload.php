<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecureFileUpload
{
    /**
     * List of allowed file extensions
     *
     * @var array
     */
    protected $allowedExtensions = [
        // Images
        'jpg', 'jpeg', 'png', 'gif', 'svg', 'webp',
        // Documents
        'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt',
        // Design files
        'ai', 'psd', 'eps', 'indd',
        // Archives
        'zip', 'rar',
    ];

    /**
     * Maximum file size in bytes (10MB)
     *
     * @var int
     */
    protected $maxFileSize = 10485760;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only check requests with file uploads
        if ($request->hasFile()) {
            foreach ($request->allFiles() as $fileKey => $file) {
                // Handle array of files
                if (is_array($file)) {
                    foreach ($file as $singleFile) {
                        $this->validateFile($singleFile, $fileKey);
                    }
                } else {
                    // Handle single file
                    $this->validateFile($file, $fileKey);
                }
            }
        }

        return $next($request);
    }

    /**
     * Validate a file for security concerns
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $fileKey
     * @return void
     */
    protected function validateFile($file, $fileKey)
    {
        // Check file size
        if ($file->getSize() > $this->maxFileSize) {
            abort(422, "The file {$fileKey} exceeds the maximum allowed size of 10MB.");
        }

        // Check file extension
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, $this->allowedExtensions)) {
            abort(422, "The file type {$extension} is not allowed.");
        }

        // Verify MIME type matches extension
        $this->validateMimeType($file, $extension);

        // Scan file content for malicious code (basic check)
        $this->scanFileContent($file);
    }

    /**
     * Validate that the MIME type matches the extension
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $extension
     * @return void
     */
    protected function validateMimeType($file, $extension)
    {
        $mimeType = $file->getMimeType();
        
        // Define expected MIME types for extensions
        $expectedMimeTypes = [
            // Images
            'jpg' => ['image/jpeg'],
            'jpeg' => ['image/jpeg'],
            'png' => ['image/png'],
            'gif' => ['image/gif'],
            'svg' => ['image/svg+xml'],
            'webp' => ['image/webp'],
            
            // Documents
            'pdf' => ['application/pdf'],
            'doc' => ['application/msword'],
            'docx' => ['application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
            'xls' => ['application/vnd.ms-excel'],
            'xlsx' => ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
            'ppt' => ['application/vnd.ms-powerpoint'],
            'pptx' => ['application/vnd.openxmlformats-officedocument.presentationml.presentation'],
            'txt' => ['text/plain'],
            
            // Design files
            'ai' => ['application/postscript', 'application/pdf'],
            'psd' => ['image/vnd.adobe.photoshop'],
            'eps' => ['application/postscript'],
            'indd' => ['application/x-indesign'],
            
            // Archives
            'zip' => ['application/zip', 'application/x-zip-compressed'],
            'rar' => ['application/x-rar-compressed', 'application/vnd.rar'],
        ];
        
        if (isset($expectedMimeTypes[$extension]) && !in_array($mimeType, $expectedMimeTypes[$extension])) {
            abort(422, "The file's content doesn't match its extension. Expected {$extension} file but got {$mimeType}.");
        }
    }

    /**
     * Scan file content for malicious code
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @return void
     */
    protected function scanFileContent($file)
    {
        // Only scan text-based files
        $mimeType = $file->getMimeType();
        $textBasedMimeTypes = [
            'text/plain', 'text/html', 'text/css', 'text/javascript',
            'application/javascript', 'application/x-javascript',
            'application/json', 'application/xml'
        ];
        
        if (in_array($mimeType, $textBasedMimeTypes)) {
            $content = file_get_contents($file->getPathname());
            
            // Check for PHP code
            if (preg_match('/<\?php/i', $content)) {
                abort(422, 'Potentially malicious PHP code detected in the uploaded file.');
            }
            
            // Check for script tags
            if (preg_match('/<script/i', $content)) {
                abort(422, 'Potentially malicious JavaScript code detected in the uploaded file.');
            }
            
            // Check for iframe tags
            if (preg_match('/<iframe/i', $content)) {
                abort(422, 'Potentially malicious iframe detected in the uploaded file.');
            }
            
            // Check for eval() and other dangerous JavaScript functions
            if (preg_match('/eval\s*\(/i', $content)) {
                abort(422, 'Potentially malicious code detected in the uploaded file.');
            }
        }
    }
}
