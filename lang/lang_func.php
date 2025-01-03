<?php

if (!function_exists('t')) {
    /**
     * Affiche le texte qui correspond à la clé donnée, dans la langue sélectionnée.
     * 
     * @param string $key La clé correspondant au texte à afficher
     * @return string La valeur associée à la clé si elle existe, sinon la clé entre crochets
     * @throws Exception
     */
    function t($key)
    {
        $language = getLanguage();
        $path = __DIR__ . DIRECTORY_SEPARATOR . "dictionaries" . DIRECTORY_SEPARATOR;
        $langFile = $path . "{$language}.php";

        if (!file_exists($langFile)) {
            throw new Exception("Le fichier de langue {$langFile} est introuvable.");
        }

        $dictionary = require $langFile;

        // Si la clé n'existe pas, retourner la clé elle-même comme fallback
        return (array_key_exists($key, $dictionary))
            ? $dictionary[$key]
            : "[{$key}]"; // Ajout de crochets autour de la clé pour mettre en évidence les traductions manquantes
    }
}

if (!function_exists('getLanguage')) {
    /**
     * Détermine la langue à utiliser (par URL, session, ou navigateur)
     * 
     * @param string $defaultLanguage La langue par défaut si aucune n'est détectée ('fr')
     * @return string La langue détectée ou la langue par défaut
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

        if (!isset($language) || !in_array($language, getSupportedLanguages())) {
            $language = $defaultLanguage;
        }

        $_SESSION['LANG'] = $language;

        return $language;
    }
}

if (!function_exists('getLanguageFromBrowser')) {
    /**
     * Détecte la langue préférée du navigateur
     * 
     * @param string $defaultLanguage La langue par défaut si aucune n'est détectée ('fr')
     * @return string La langue détectée ou la langue par défaut
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
     * Retourne la liste des langues supportées par l'application
     * 
     * @return array La liste des codes de langues supportées
     * @throws Exception Si le répertoire des langues est introuvable
     */
    function getSupportedLanguages()
    {
        $path = __DIR__ . DIRECTORY_SEPARATOR . "dictionaries" . DIRECTORY_SEPARATOR;

        if (!is_dir($path)) {
            throw new Exception("Le répertoire des langues est introuvable : {$path}");
        }

        $files = glob($path . "*.php");
        $languages = [];
        foreach ($files as $file) {
            $languages[] = pathinfo($file, PATHINFO_FILENAME);
        }

        return $languages;
    }
}

if (!function_exists('loadLanguage')) {
    /**
     * Charge le dictionnaire de la langue spécifiée
     * 
     * @param string $lang Le code de la langue à charger
     * @return array Le tableau associatif contenant les traductions de la langue spéicifée
     * @throws Exception Si le fichier de langue est introuvable 
     */
    function loadLanguage($lang)
    {
        $path = __DIR__ . DIRECTORY_SEPARATOR . "dictionaries" . DIRECTORY_SEPARATOR;
        $langFile = $path . "{$lang}.php";

        if (!file_exists($langFile)) {
            throw new Exception("Le fichier de langue {$langFile} est introuvable.");
        }

        return require $langFile;
    }
}
