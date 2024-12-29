<?php

/**
 * Affiche le texte qui correspond à la clé donnée, dans la langue sélectionnée
 * Méthode utilisée dans le html
 * @param string $key 
 * @return mixed 
 *      retourne la valeur associée à la clé si la clé existe dans le tableau
 *      retourne la clé si la clé n'existe pas dans le tableau
 * @throws Exception
 */
function t($key)
{
    // Détermine la langue active
    $language = getLanguage();

    // Construit le chemin vers les dictionnaires de chaque langue
    $path = realpath(__DIR__);

    // Tableau retourné par le fichier <lang>.php 
    $dictionary = require $path . "/dictionaries/{$language}.php";

    // Retourne soit la valeur associée à la clé (si la clé existe) soit la clé (si la clé n'existe pas)
    return (array_key_exists($key, $dictionary))
        ? $dictionary[$key]
        : $key;
}

/**
 * Rend la langue définie soit par l'URL, soit par la session, soit par le navigateur (dans cet ordre de priorité)
 * Si aucune langue ne peut être déterminée ou que la langue déterminée n'est pas dans la liste des 
 * langues supportées, utilise la langue par défaut
 *
 * @param string $defaultLanguage
 * @return string $language
 */
function getLanguage($defaultLanguage = 'fr')
{
    $language = null;

    if (isset($_GET['lang'])) {
        // Rend la langue définie par l'URL si elle existe
        $language = $_GET['lang'];
    } elseif (isset($_SESSION['LANG'])) {
        // Rend la langue définie par la session si elle existe
        $language = $_SESSION['LANG'];
    } else {
        // Rend la langue définie par le navigateur 
        // Si aucune langue détectée, rend la langue par défaut
        $language = getLanguageFromBrowser($defaultLanguage);
    }

    // Si aucune langue détectée ou qu'elle n'est pas dans la liste des langues supportées, utilise la langue par défaut
    if (!isset($language) || !in_array($language, getSupportedLanguages())) {
        $language = $defaultLanguage;
    }

    // Enregistre la langue dans la session pour les utilisations futures
    $_SESSION['LANG'] = $language;

    return $language;
}

/**
 * Rend la langue définie par le navigateur (client) 
 * Si aucune langue détectée, rend la langue par défaut
 *
 * @param string $defaultLanguage
 * @return int|string 
 */
function getLanguageFromBrowser($defaultLanguage = 'fr')
{
    $languages = [];
    if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
        // séparer la chaîne renvoyée (langues, locales et facteurs q (pondération))
        preg_match_all('/([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $lang_parse);

        if (count($lang_parse[1])) {
            // créer une liset de forme  "langue" => facteur q (par exemple "en" => 0.8)
            $languages = array_combine($lang_parse[1], $lang_parse[4]);

            // pour les langues qui n'ont pas de facteur q, le mettre à 1 par défaut
            foreach ($languages as $lang => $val) {
                if ($val === '') $languages[$lang] = 1;
            }

            // trier la liste selon la valeur du facteur q
            arsort($languages, SORT_NUMERIC);
        }
    }

    $supportedLanguages = getSupportedLanguages();

    foreach ($languages as $locale => $weighting) {

        // Cas d'utilisation des locales (langue + pays) Ex. "en-US"
        if (preg_match("/[a-z]{2}-[A-Z]{2}/", $locale)) {
            $browserLanguage = substr($locale, 0, 2);
        } else {
            // Cas d'utilisation d'une langue uniquement Ex. "en"
            $browserLanguage = $locale;
        }

        if (in_array($browserLanguage, $supportedLanguages)) {
            return $browserLanguage;
        }
    }

    return $defaultLanguage;
}

/**
 * Rend un tableau de toutes les langues supportées par l'application
 *
 * @return array
 */
function getSupportedLanguages()
{
    $path = getcwd() . DIRECTORY_SEPARATOR . "lang" . DIRECTORY_SEPARATOR . "dictionaries" . DIRECTORY_SEPARATOR;
    $files = glob($path . "*.php");
    $languages = [];
    for ($i = 0; $i < count($files); $i++) {
        $languages[$i] = basename($files[$i], ".php");
    }
    return $languages;
}
