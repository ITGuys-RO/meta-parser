<?php
/**
 * Created by Catalin Teodorescu on 22-May-16 01:28.
 */
use Sunra\PhpSimple\HtmlDomParser;

/**
 * Acceseaza continutul si intoarce in format text elementele meta: title, description si keywords
 *
 * @param string $url
 *
 * @return array
 */
function parser($url)
{
    $dom = HtmlDomParser::file_get_html($url); //acceseaza pagina
    $content = trim($dom->plaintext);
    $metaElements = [
        'title'       => '',
        'description' => '',
        'keywords'    => '',
        'content'     => $content,
    ];
    if (!empty($content)) { //verifica faptul ca exista content
        $title = $dom->find('title');
        $description = $dom->find('meta[name=description]');
        $keywords = $dom->find('meta[name=keywords]');
        if (!empty($title)) {
            $metaElements['title'] = $title[0]->plaintext;
        }
        if (!empty($description)) {
            $metaElements['description'] = $description[0]->content;
        }
        if (!empty($keywords)) {
            $metaElements['keywords'] = $keywords[0]->content;
        }
    }

    return $metaElements;
}

/**
 * Proceseaza textul si intoarce valorile statistice (cuvine, litere, relevanta)
 *
 * @param string $tag
 * @param string $content
 *
 * @return string
 */
function stats($tag, $content)
{
    $percent = 0;
    $matches = similar_text($tag, $content, $percent);
    $stats = [
        'wordCount' => str_word_count($tag),
        'charCount' => strlen($tag),
        'relevance' => number_format(($percent * 100), 3),
    ];

    $return = $stats['charCount']." chars".PHP_EOL;
    $return .= $stats['wordCount']." words".PHP_EOL;
    $return .= $stats['relevance']."% relevance".PHP_EOL;

    return nl2br($return);
}

/**
 * Proceseaza textul si intoarce lista cu toate cuvintele,
 * sorata descendent in functie de cele mai folosite cuvinte
 *
 * @param string $content
 *
 * @return array
 */
function weight_words($content)
{
    $decodedContent = html_entity_decode($content, ENT_HTML5);
    $excluded = ['de', 'si', 'in', 'la', 'cu']; //exceptii de cuvinte pe care nu le vrem afisate
    $filtered = str_ireplace($excluded, '', $decodedContent); //stergem cuvintele nedorite din continut
    $words = str_word_count($filtered, 2); //returneaza list de cuvinte din continut
    $lowered = array_map('strtolower', $words); //elimina majusculele
    $weight = array_count_values($lowered); //calculeaza repetitia cuvintelor
    array_multisort($weight, SORT_NUMERIC, SORT_DESC); //sorteaza descendent

    return $weight;
}

function density($wordCount, $totalWords)
{
    $density = ($wordCount / $totalWords) * 100;

    return $density;
}