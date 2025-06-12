<?php
echo "<h2>Extensiones cargadas</h2>";
$extensions = ['gd', 'zip', 'mbstring', 'xml', 'SimpleXML'];
foreach($extensions as $ext) {
    echo $ext . ': ' . (extension_loaded($ext) ? '✓' : '✗') . "<br>";
}
?>