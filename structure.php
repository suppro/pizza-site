<?php
function getDirectoryTree($dir, $prefix = '', $isLast = false) {
    $files = array_diff(scandir($dir), ['.', '..']);
    $fileCount = count($files);
    $i = 0;
    
    $result = '';
    
    foreach ($files as $file) {
        $i++;
        $path = $dir . '/' . $file;
        $isLastItem = ($i === $fileCount);
        
        if (in_array($file, ['node_modules', 'vendor', 'storage', '.git'])) {
            continue;
        }
        
        $result .= $prefix . ($isLastItem ? '└── ' : '├── ') . $file . "\n";
        
        if (is_dir($path)) {
            $newPrefix = $prefix . ($isLastItem ? '    ' : '│   ');
            $result .= getDirectoryTree($path, $newPrefix, $isLastItem);
        }
    }
    
    return $result;
}

// Получаем структуру
$structure = "pizza-site/\n";
$structure .= getDirectoryTree('.');

// Сохраняем в файл
file_put_contents('project_structure.txt', $structure);

echo "✅ Структура проекта сохранена в project_structure.txt\n";
echo "📁 Содержимое:\n";
echo $structure;