<?php
namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class DeepLTranslationService
{
    private $apiKey;
    private $httpClient;

    public function __construct(ParameterBagInterface $params)
    {
        // Accédez à la clé API depuis .env
     
        $this->httpClient = HttpClient::create();
    }

    /**
     * Translate text using DeepL API.
     *
     * @param string $text The text to translate.
     * @param string $targetLanguage The target language code (e.g., 'EN', 'FR').
     * @return string The translated text.
     * @throws \Exception If the translation fails.
     */
    public function translate(string $text, string $targetLanguage = 'EN'): string
    {
        if (empty($text)) {
            return $text;
        }

        try {
            $response = $this->httpClient->request('POST', 'https://api-free.deepl.com/v2/translate', [
                'headers' => [
                    'Authorization' => 'DeepL-Auth-Key ' .'b2c58875-8344-4581-bdf6-2204fa8ec37e:fx',
                ],
                'body' => [
                    'text' => $text,
                    'target_lang' => $targetLanguage,
                ],
            ]);

            $data = $response->toArray();

            // Return the translated text
            return $data['translations'][0]['text'] ?? $text;
        } catch (ExceptionInterface $e) {
            throw new \Exception('Translation failed: ' . $e->getMessage());
        }
    }
}