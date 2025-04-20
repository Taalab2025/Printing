<?php
// Define the directories to search
$directories = [
    'resources/views/layouts',
    'resources/views/pages',
    'resources/views/auth',
    'resources/views/components'
];

// Define route replacements
$replacements = [
    "{{ route('home') }}" => "{{ url('/') }}",
    "{{ route('categories') }}" => "{{ url('/categories') }}",
    "{{ route('categories.index') }}" => "{{ url('/categories') }}",
    "{{ route('vendors') }}" => "{{ url('/vendors') }}",
    "{{ route('vendors.index') }}" => "{{ url('/vendors') }}",
    "{{ route('vendor.register') }}" => "{{ url('/vendor/register') }}",
    "{{ route('vendors.register') }}" => "{{ url('/vendor/register') }}",
    "{{ route('how-it-works') }}" => "{{ url('/how-it-works') }}",
    "{{ route('about') }}" => "{{ url('/about') }}",
    "{{ route('contact') }}" => "{{ url('/contact') }}",
    "{{ route('faqs') }}" => "{{ url('/faqs') }}",
    "{{ route('login') }}" => "{{ url('/login') }}",
    "{{ route('register') }}" => "{{ url('/register') }}"
];

// Function to recursively search directories
function searchDirectory($dir, $replacements) {
    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file == '.' || $file == '..') continue;
        
        $path = $dir . '/' . $file;
        if (is_dir($path)) {
            searchDirectory($path, $replacements);
        } else if (pathinfo($path, PATHINFO_EXTENSION) == 'php') {
            replaceInFile($path, $replacements);
        }
    }
}

// Function to replace content in a file
function replaceInFile($file, $replacements) {
    $content = file_get_contents($file);
    $originalContent = $content;
    
    foreach ($replacements as $search => $replace) {
        $content = str_replace($search, $replace, $content);
    }
    
    if ($content !== $originalContent) {
        file_put_contents($file, $content);
        echo "Updated: $file\n";
    }
}

// Process each directory
foreach ($directories as $dir) {
    if (is_dir($dir)) {
        searchDirectory($dir, $replacements);
    }
}

echo "Route replacement complete!\n";
