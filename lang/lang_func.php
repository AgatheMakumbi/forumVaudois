<?php

if (!function_exists('t')) {
    /**
     * Performs the actual translation based on the given key.
     *
     * @param string $key The translation key.
     * @return string The translated message or the key if not found.
     */
    function t($key)
    {
        $language = getLanguage();
        $path = __DIR__ . DIRECTORY_SEPARATOR . "lang" . DIRECTORY_SEPARATOR;
        $langFile = $path . "{$language}.php";

        if (!file_exists($langFile)) {
            throw new Exception("Language file not found: {$langFile}");
        }

        $messages = require $langFile;

        return (array_key_exists($key, $messages))
            ? $messages[$key]
            : $key;
    }
}

if (!function_exists('getLanguage')) {
    /**
     * Determines the language from URL, session, or browser settings.
     *
     * @param string $defaultLanguage The default language to use if none is set.
     * @return string The determined language.
     */
    function getLanguage($defaultLanguage = 'fr')
    {
        $language = null;

        if (isset($_GET['lang'])) {
            $language = $_GET['lang'];
        } elseif (isset($_SESSION['LANG'])) {
            $language = $_SESSION['LANG'];
        } else {
            $language = getLanguageFromBrowser($defaultLanguage);
        }

        // Validate the language against supported languages
        if (!isset($language) || !in_array($language, getSupportedLanguages())) {
            $language = $defaultLanguage;
        }

        // Store the current language in the session for later use
        $_SESSION['LANG'] = $language;

        return $language;
    }
}

if (!function_exists('loadLanguage')) {
    function loadLanguage($lang)
    {
        $path = __DIR__ . DIRECTORY_SEPARATOR;
        $langFile = $path . "{$lang}.php";

        if (!file_exists($langFile)) {
            throw new Exception("Le fichier de langue {$langFile} est introuvable.");
        }

        return require $langFile;
    }
}

if (!function_exists('getLanguageFromBrowser')) {
    /**
     * Retrieves the language from the client's browser.
     *
     * @param string $defaultLanguage The default language to use if none is detected.
     * @return string The browser-determined language or the default.
     */
    function getLanguageFromBrowser($defaultLanguage = 'fr')
    {
        $languages = [];
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            preg_match_all(
                '/([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i',
                $_SERVER['HTTP_ACCEPT_LANGUAGE'],
                $lang_parse
            );

            if (count($lang_parse[1])) {
                $languages = array_combine($lang_parse[1], $lang_parse[4]);

                foreach ($languages as $lang => $val) {
                    if ($val === '') {
                        $languages[$lang] = 1;
                    }
                }

                arsort($languages, SORT_NUMERIC);
            }
        }

        $supportedLanguages = getSupportedLanguages();

        foreach ($languages as $locale => $weighting) {
            $browserLanguage = strpos($locale, '-') !== false ? substr($locale, 0, 2) : $locale;

            if (in_array($browserLanguage, $supportedLanguages)) {
                return $browserLanguage;
            }
        }

        return $defaultLanguage;
    }
}

if (!function_exists('getSupportedLanguages')) {
    /**
     * Returns the list of supported languages.
     *
     * @return array An array of supported language codes.
     */
    function getSupportedLanguages()
    {
        $path = __DIR__ . DIRECTORY_SEPARATOR . "lang" . DIRECTORY_SEPARATOR;

        if (!is_dir($path)) {
            throw new Exception("Language directory not found: {$path}");
        }

        $files = glob($path . "*.php");
        $languages = [];
        foreach ($files as $file) {
            $languages[] = pathinfo($file, PATHINFO_FILENAME);
        }

        return $languages;
    }
}
