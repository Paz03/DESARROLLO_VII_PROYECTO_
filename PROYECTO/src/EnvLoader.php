<?php

class EnvLoader
{
    /**
     * Carga las variables de entorno desde un archivo .env.
     *
     * @param string $path Ruta al archivo .env.
     * @throws Exception Si el archivo no se encuentra.
     */
    public static function load($path)
    {
        if (!file_exists($path)) {
            throw new Exception("El archivo .env no se encuentra en: $path");
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            // Ignorar comentarios
            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            // Dividir clave y valor
            [$key, $value] = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value, " \t\n\r\0\x0B\"'");

            // Establecer la variable de entorno
            putenv("$key=$value");
        }
    }

    /**
     * Obtiene una variable de entorno.
     *
     * @param string $key Nombre de la variable.
     * @param mixed $default Valor predeterminado si no se encuentra la variable.
     * @return mixed Valor de la variable o el valor predeterminado.
     */
    public static function get($key, $default = null)
    {
        $value = getenv($key);
        return $value !== false ? $value : $default;
    }
}
?>
