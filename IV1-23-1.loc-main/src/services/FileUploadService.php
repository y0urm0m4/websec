<?php
namespace src\services;

class FileUploadService
{
    private const ALLOWED_MIME = [
        'image/jpeg',
        'image/png',
        'image/webp',
    ];

    public static function saveImage(array $file, string $uploadDir): string
    {
        // 1. Проверка ошибки загрузки
        if ($file['error'] !== UPLOAD_ERR_OK) {
            // Коды ошибок UPLOAD_ERR_* можно перевести в понятный текст, но пока оставим код
            throw new \InvalidArgumentException('Ошибка загрузки файла: код ' . $file['error']);
        }

        // 2. Проверка размера (макс 2 МБ)
        if ($file['size'] > 2 * 1024 * 1024) {
            throw new \InvalidArgumentException('Файл слишком большой (максимум 2 МБ).');
        }

        // 3. Проверка реального MIME-типа
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        if (!$finfo) {
            throw new \RuntimeException('Не удалось инициализировать проверку типа файла');
        }

        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mime, self::ALLOWED_MIME, true)) {
            throw new \InvalidArgumentException('Недопустимый тип файла. Разрешены только JPG, PNG, WebP.');
        }

        // 4. Безопасное расширение
        $ext = match ($mime) {
            'image/jpeg' => '.jpg',
            'image/png' => '.png',
            'image/webp' => '.webp',
            default => '.bin',
        };

        // 5. Уникальное имя
        $newName = bin2hex(random_bytes(16)) . $ext;

        // 6. Создание папки, если нет
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true)) {
                throw new \RuntimeException('Не удалось создать папку для загрузок. Проверьте права доступа.');
            }
        } elseif (!is_writable($uploadDir)) {
            throw new \RuntimeException('Папка существует, но нет прав на запись в неё.');
        }

        // 7. Перемещение файла
        $destination = rtrim($uploadDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $newName;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            throw new \RuntimeException('Не удалось сохранить файл на диск.');
        }

        return $newName; // Возвращаем только имя файла
    }
}
