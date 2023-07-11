// Ruta del archivo de imagen original
$imagenOriginal = 'ruta/de/la/imagen/original.jpg';

// Ruta de destino para la imagen redimensionada
$imagenRedimensionada = 'ruta/de/la/imagen/redimensionada.jpg';

// Tamaño deseado para la imagen redimensionada
$nuevoAncho = 800;
$nuevoAlto = 600;

// Cargar la imagen original utilizando la función adecuada según el formato (por ejemplo, imagecreatefromjpeg() para JPEG)
$imagen = imagecreatefromjpeg($imagenOriginal);

// Obtener las dimensiones originales de la imagen
$anchoOriginal = imagesx($imagen);
$altoOriginal = imagesy($imagen);

// Calcular las nuevas dimensiones proporcionales
$ratio = $anchoOriginal / $altoOriginal;
if ($nuevoAncho / $nuevoAlto > $ratio) {
    $nuevoAncho = $nuevoAlto * $ratio;
} else {
    $nuevoAlto = $nuevoAncho / $ratio;
}

// Crear una nueva imagen en blanco con las dimensiones deseadas
$imagenRedimensionada = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

// Redimensionar la imagen original a la nueva imagen con las dimensiones deseadas
imagecopyresampled($imagenRedimensionada, $imagen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $anchoOriginal, $altoOriginal);

// Guardar la imagen redimensionada en el destino especificado
imagejpeg($imagenRedimensionada, $imagenRedimensionada);

// Liberar la memoria utilizada por las imágenes
imagedestroy($imagen);
imagedestroy($imagenRedimensionada);
