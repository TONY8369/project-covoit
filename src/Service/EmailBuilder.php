<?php

namespace App\Service;

use App\Entity\EventRequest;
use App\Entity\User;
use GuzzleHttp\Client;
use SendinBlue\Client\Configuration;
use SendinBlue\Client\Model\SendSmtpEmail;
use SendinBlue\Client\Api\TransactionalEmailsApi;
/**
 * Class to import in order to send emails through sendinblue 
 */
class EmailBuilder  
{

    //Import de sendin blue php library
    public function sendEmail(
        User $user, 
        int $templateId = null, 
        string $subject = null,
        array $datas
        )
    {
        //https://developers.sendinblue.com/reference/sendtransacemail
        //Create an account on sendin blue> generate a new API key > copy your key where it's needed 
        $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', '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');


        //https://docs.guzzlephp.org/en/stable/psr7.html
        $apiInstance = new TransactionalEmailsApi(
            new Client(),
            $config
        );

        //check the library 
        $sendSmtpEmail = new SendSmtpEmail([
            'subject' => $subject,
            'sender' => [
                'name' => 'Association-DMJ',
                'email' => 'assoc@covoit-symfo.dmj'
            ],
            'to' => [[
                    'name' => $user->getFirstName(), 
                    'email' => $user->getEmail(),  
                ]],
            'templateId' => $templateId, 
            // contact parameters created in sendinblue 
            'params' => $datas,

        ]);

        try {
            $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
            print_r($result);
        } catch (Exception $e) {
            echo 'Exception when calling ContactsApi->importContacts: ', $e->getMessage(), PHP_EOL;
        }
    }

    public function sendValidationCreation(User $user)
    {
        $templateId = 3; 
        $subject = 'Validez votre compte COVOIT';
        $datas = [
            'PRENOM' => $user->getFirstname(),
            'VALIDATION_LINK' => $validationLink,
        ]; 

        $this->sendEmail($user, $templateId, $subject, $datas);
    }

    public function resetPassword(User $user, $resetpasswordurl)
    {
        $templateId = 11; 
        $subject = 'Réinitialisez votre mot de passe';
        $datas = [
            'RESET_PASSWORD_LINK' => $resetpasswordurl,
        ]; 
        $this->sendEmail($user, $templateId, $subject, $datas);
    }

    public function sendRequestExchangeEmail(
        User $user,
        EventRequest $eventRequest,
        Event $event,
        )
    {
        $templateId = 7; 
        $subject = 'Vous avez reçu une demande';
        $datas = [
            'REQUEST_SHOW_LINK' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'REQUEST_REFUSAL_LINK' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'REQUEST_ACCEPT_LINK' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'EVENT_NAME' => $event->getName($eventRequest->getEvent()),  
            'EVENT_DESCRIPTION' => '',
            'EVENT_IMAGE' => '',
            'EVENT_ADDRESS' => '',
            'EVENT_START' => '',
            'EVENT_END' => '',
            'REQUESTER_FIRSTNAME' => '',
            'REQUESTER_LASTNAME' => '',
            'REQUEST_TYPE' => '',
            'DIRECTION' => '',
            'NB_SEAT' => '',
            'ADDRESS' => '',
            'DEPARTURE_TIME' => '',
        ]; 

        $this->sendEmail($user, $templateId, $subject, $datas);
    }
}
