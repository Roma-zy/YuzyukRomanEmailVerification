<?php

declare(strict_types=1);

namespace YuzyukRoman\Verification\Email;

class Email
{
    private array $emails;

    public function setEmails(array $emails): void
    {
        $this->emails = $emails;
    }

    public function getEmails(): array
    {
        return $this->emails;
    }

    private function isValidEmail(string $email): bool
    {
        return boolval(preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $email));
    }

    private function verifyMx(string $domain): bool
    {
        return checkdnsrr($domain, 'MX');
    }

    public function verifyEmails(): array
    {
        return array_map(function ($item) {
            $res = ["value" => $item];

            if (gettype($item) !== 'string') {
                $res['isValid'] = false;
            } else {
                $parts = explode('@', $item);
                $domain = array_pop($parts);
                $res['isValid'] = $this->isValidEmail($item) && $this->verifyMx($domain);
            }

            return $res;
        }, $this->emails);
    }
}
