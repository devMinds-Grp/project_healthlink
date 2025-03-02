<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Mailer\Exception\TransportException;
use Twilio\Rest\Client;
use Twilio\Exceptions\TwilioException;

class SmsService
{
    private Client $twilioClient;
    private string $twilioPhoneNumber;

    public function __construct(
        #[Autowire('%env(TWILIO_ACCOUNT_SID)%')]
        private string $twilioAccountSid,

        #[Autowire('%env(TWILIO_AUTH_TOKEN)%')]
        private string $twilioAuthToken,

        #[Autowire('%env(TWILIO_PHONE_NUMBER)%')]
        string $twilioPhoneNumber
    ) {
        $this->twilioClient = new Client($this->twilioAccountSid, $this->twilioAuthToken);
        $this->twilioPhoneNumber = $twilioPhoneNumber;
    }

    /**
     * Envoie un SMS à un numéro de téléphone
     *
     * @param string $toNumber Le numéro de téléphone du destinataire
     * @param string $message Le message à envoyer
     * @return bool True si l'envoi est réussi, false sinon
     * @throws TransportException Si une erreur réseau survient
     */
    public function sendSms(string $toNumber, string $message): bool
    {
        // Valider le numéro de téléphone
        if (!$this->isValidPhoneNumber($toNumber)) {
            throw new \InvalidArgumentException("Numéro de téléphone invalide : " . $toNumber);
        }
    
        // Formater le numéro de téléphone
        $formattedNumber = $this->formatPhoneNumber($toNumber);
    
        // Log pour vérifier le numéro formaté
        error_log("Numéro formaté : " . $formattedNumber);
    
        try {
            $this->twilioClient->messages->create($formattedNumber, [
                'from' => $this->twilioPhoneNumber,
                'body' => $message,
            ]);
            return true;
        } catch (TwilioException $e) {
            error_log("Erreur Twilio : " . $e->getMessage());
            throw new TransportException(
                sprintf('Erreur lors de l\'envoi du SMS : %s', $e->getMessage()),
                $e->getCode(),
                $e
            );
        }
    }
    
    public function formatPhoneNumber(string $phoneNumber): string
    {
        // Supprime tous les caractères non numériques
        $phoneNumber = preg_replace('/[^\d]/', '', $phoneNumber);
    
        // Si le numéro commence par 216, ajoute le signe +
        if (strpos($phoneNumber, '216') === 0) {
            return '+' . $phoneNumber;
        }
    
        // Si le numéro ne commence pas par 216, ajoute le code pays +216
        return '+216' . $phoneNumber;
    }
    
    public function isValidPhoneNumber(string $phoneNumber): bool
    {
        // Supprime tous les caractères non numériques
        $phoneNumber = preg_replace('/[^\d]/', '', $phoneNumber);
    
        // Vérifie si le numéro a 8 chiffres (sans le code pays)
        return preg_match('/^\d{8}$/', $phoneNumber) === 1;
    }

}