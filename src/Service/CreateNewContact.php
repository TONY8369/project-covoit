<?php

namespace App\Service;

use GuzzleHttp\Client;
use SendinBlue\Client\Configuration;
use SendinBlue\Client\Api\ContactsApi;
use SendinBlue\Client\Model\CreateContact;

class CreateNewContact 
{
    public function NewContact()
    {
        $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', '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');
        $apiInstance = new ContactsApi(
            new Client(),
            $config
        );

        $createContact = new CreateContact([
            'email' => 'jojo@rambo.com',
            'attributes' => [
                'PRENOM' => 'John-J',
                'NOM' => 'Rambo'
            ],
            'listIds' => [2],
            'emailBlacklist' => false,
            'smsBlacklist' => false,
            'updateExistingContacts' => true,
        ]);

        try {
            $result = $apiInstance->createContact($createContact);
            print_r($result);
        } catch (Exception $e) {
            echo 'Exception when calling ContactsApi->createContact: ', $e->getMessage(), PHP_EOL;
        }
    
    }
}
