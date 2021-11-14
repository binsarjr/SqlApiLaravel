<?php

namespace App\Driver\SqlDriver;

use Illuminate\Http\Request;

abstract class MaliciousProperties
{
    public static string $securityMessage;
    protected array $_malicious_schema = ['information_schema', 'sys'];

    protected function _addMaliciousSchema(...$schema)
    {
        $this->_malicious_schema = array_merge($this->_malicious_schema, $schema);
    }

    /**
     * Add new MaliciousSchema.
     */
    abstract public static function addMaliciousSchema(...$schema);

    /**
     * Get List of MaliciousSchema.
     */
    abstract public static function getMaliciousSchema(): array;

    /**
     * Secure all maliciaous.
     */
    abstract public static function security(Request $request): bool;

    /**
     * Secure from maliciaous schema.
     */
    abstract public function SecureFromMaliciousSchema(string $raw);

    /**
     * Secure from maliciaous data binding.
     */
    abstract public function SecureFromMaliciousDataBinding(string $raw);
}