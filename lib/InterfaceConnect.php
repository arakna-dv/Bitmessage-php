<?php

namespace Bitmessage;

use Bitmessage\curl as curlClient;

class InterfaceConnect extends curlClient
{
    private $url;
    private $basefields = array("subject", "message", "label");
    public  $serverResponse = "";
    private $connection;

    function __construct($url, $https = "http") // should only be HTTP for testing, no sensitive data should be passed over HTTP
    {
        parent::__construct();
        $url = ($https == "https") ? "https://{$url}" : "http://{$url}";

        $this->url = $url;
        $this->connection = new curlClient();
    }

    private function call($method, $params = null): string
    {
        $post = xmlrpc_encode_request($method, $params);
        return $this->serverResponse(xmlrpc_decode($this->connection->post($this->url, $post)));
    }

    private function stripHTML($text): string
    {
        return strip_tags(trim(html_entity_decode($text)));
    }

    private function serverResponse($data): string
    {
        if (strpos($data, 'API Error') !== false) {
            $this->serverResponse .= $data;
        } else {
            $this->serverResponse = $data;
        }

        return $this->serverResponse;
    }

    private function cleanArray($array): array
    {
        foreach ($array as $item => $key) {
            $a[$item] = $key;
        }

        if ($this->decode) {
            return $this->basecheck($a);
        }

        return $a;
    }

    private function basecheck($a)
    {
        foreach ($a as $i => $item) {
            foreach ($item as $parm => $value) {
                if (in_array($parm, $this->basefields)) {
                    $item->$parm = base64_decode($value);
                } else {
                    $item->$parm = $value;
                }
            }
        }
        return $a;
    }

    public function newAddress($label, $eighteenByteRipe = false, $totalDifficulty = 1, $smallMessageDifficulty = 1): string
    {
        return $this->call('createRandomAddress', array(base64_encode($label), $eighteenByteRipe, $totalDifficulty, $smallMessageDifficulty));
    }

    public function statusBar($message): bool
    {
        $message = $this->call('statusBar', array($message));
        return (bool)$message;
    }

    public function createDeterministicAddresses($passphrase, $numberOfAddresses, $addressVersionNumber = 0, $streamNumber = 0, $eighteenByteRipe = true, $totalDifficulty = 1, $smallMessageDifficulty = 1): string
    {
        return $this->call('createDeterministicAddresses',
            array(base64_encode($passphrase), $numberOfAddresses, $addressVersionNumber, $streamNumber, $eighteenByteRipe, $totalDifficulty, $smallMessageDifficulty)
        );
    }

    public function listAddresses(): array
    {
        $addresses = json_decode($this->call('listAddresses2'));
        return $this->cleanArray($addresses->addresses);
    }

    public function getDeterministicAddress($passphrase, $addressVersionNumber = 4, $streamNumber = 1): string
    {
        return $this->call('getDeterministicAddress', array(base64_encode($passphrase), $addressVersionNumber, $streamNumber));
    }

    public function getAllInbox(): array
    {
        $messages = json_decode($this->call('getAllInboxMessages'));
        return $this->cleanArray($messages->inboxMessages);
    }

    public function getInboxMessageByID($msgid, $read = 2): array
    {
        if ($read = 2) {
            $bmdata = array($msgid);
        } else {
            $bmdata = array($msgid, $read);
        }

        $messages = json_decode($this->call('getInboxMessageByID', $bmdata));
        return $this->cleanArray($messages->inboxMessages);
    }

    public function getSentMessageByAckData($ackData): array
    {
        $messages = json_decode($this->call('getSentMessageByAckData', array($ackData)));
        return $this->cleanArray($messages->sentMessages);
    }

    public function getAllSentMessages(): array
    {
        $messages = json_decode($this->call('getAllSentMessages'));
        return $this->cleanArray($messages->sentMessages);
    }

    public function getSentMessageByID($msgid): array
    {
        $messages = json_decode($this->call('getSentMessageByID', array($msgid)));
        return $this->cleanArray($messages->sentMessages);
    }

    public function getSentMessagesBySender($fromAddress): array
    {
        $messages = json_decode($this->call('getSentMessagesBySender', array($fromAddress)));
        return $this->cleanArray($messages->sentMessages);
    }

    public function trashMessage($msgid): string
    {
        return $this->call('trashMessage', array($msgid));
    }

    public function sendMessage($toAddress, $fromAddress, $subject, $message, $encodingType = 2): string
    {
        return $this->call('sendMessage', array($toAddress, $fromAddress, base64_encode($subject), base64_encode($message), $encodingType));
    }

    public function broadcast($address, $title, $message, $encodingType = 2): string
    {
        return $this->call('sendBroadcast', array($address, base64_encode($title), base64_encode($this->stripHTML($message)), $encodingType));
    }

    public function getStatus($ackData): string
    {
        return $this->call('sendMessage', array($ackData));
    }

    public function listSubscriptions(): array
    {
        $subscriptions = json_decode($this->call('listSubscriptions'));
        return $this->cleanArray($subscriptions->subscriptions);
    }

    public function addSubscription($address, $label = NULL): string
    {
        if (empty($label)) {
            $data = array($address);
        } else {
            $data = array($address, base64_encode($label));
        }

        return $this->call('addSubscription', $data);
    }

    public function deleteSubscription($address): string
    {
        return $this->call('deleteSubscription', array($address));
    }

    public function listAddressBookEntries(): array
    {
        $AddressBookEntries = json_decode($this->call('listAddressBookEntries'));
        return $this->cleanArray($AddressBookEntries->addresses);
    }

    public function addAddressBookEntry($address, $label): string
    {
        return $this->call('addAddressBookEntry', array($address, base64_encode($label)));
    }

    public function deleteAddressBookEntry($address): string
    {
        return $this->call('deleteAddressBookEntry', array($address));
    }

    public function trashSentMessageByAckData($ackData): string
    {
        return $this->call('trashSentMessageByAckData', array($ackData));
    }

    public function createChan($passphrase): string
    {
        return $this->call('createChan', array(base64_encode($passphrase)));
    }

    public function decodeAddress($address): string
    {
        return $this->call('decodeAddress', array($address));
    }

    public function clientStatus(): string
    {
        return $this->call('clientStatus');
    }
}
