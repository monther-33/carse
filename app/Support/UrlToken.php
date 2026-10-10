<?php

namespace App\Support;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

/**
 * Keeps data out of plain sight in links (owner's request).
 *
 *  - Record ids in paths become a 6-character code: a keyed 32-bit Feistel permutation (HMAC of
 *    APP_KEY, different per model) written in base62. It cannot be reversed or guessed without
 *    the key, consecutive records get unrelated codes, and the record count is not revealed.
 *    Access is still decided by the permissions and policies on every request.
 *  - Filter sets (reports) travel as one encrypted, tamper-proof value (Laravel Crypt).
 */
class UrlToken
{
    private const ALPHABET = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

    private const LENGTH = 6;   // 62^6 > 2^32

    private const ROUNDS = 6;

    public static function encodeId(string $scope, int $id): string
    {
        if ($id < 0 || $id > 0xFFFFFFFF) {
            return (string) $id;
        }

        [$left, $right] = [($id >> 16) & 0xFFFF, $id & 0xFFFF];
        for ($round = 0; $round < self::ROUNDS; $round++) {
            [$left, $right] = [$right, $left ^ self::round($scope, $round, $right)];
        }

        return self::toBase62(($left << 16) | $right);
    }

    public static function decodeId(string $scope, string $token): ?int
    {
        if (preg_match('/^[0-9A-Za-z]{'.self::LENGTH.'}$/', $token) !== 1) {
            return null;
        }

        $value = self::fromBase62($token);
        if ($value > 0xFFFFFFFF) {
            return null;
        }

        [$left, $right] = [($value >> 16) & 0xFFFF, $value & 0xFFFF];
        for ($round = self::ROUNDS - 1; $round >= 0; $round--) {
            [$left, $right] = [$right ^ self::round($scope, $round, $left), $left];
        }

        return ($left << 16) | $right;
    }

    /** @param array<string, mixed> $data */
    public static function encodeState(array $data): string
    {
        $data = array_filter($data, fn ($v) => $v !== null && $v !== '' && $v !== []);

        return $data === [] ? '' : Crypt::encryptString((string) json_encode($data));
    }

    /** @return array<string, mixed> empty when missing, altered or encrypted with another key */
    public static function decodeState(?string $token): array
    {
        if ($token === null || $token === '') {
            return [];
        }

        try {
            $data = json_decode(Crypt::decryptString($token), true);
        } catch (DecryptException) {
            return [];
        }

        return is_array($data) ? $data : [];
    }

    private static function round(string $scope, int $round, int $half): int
    {
        $hash = hash_hmac('sha256', $scope.'|'.$round.'|'.$half, (string) config('app.key'), true);

        return (ord($hash[0]) << 8) | ord($hash[1]);
    }

    private static function toBase62(int $value): string
    {
        $out = '';
        do {
            $out = self::ALPHABET[$value % 62].$out;
            $value = intdiv($value, 62);
        } while ($value > 0);

        return str_pad($out, self::LENGTH, '0', STR_PAD_LEFT);
    }

    private static function fromBase62(string $token): int
    {
        $value = 0;
        foreach (str_split($token) as $char) {
            $value = $value * 62 + strpos(self::ALPHABET, $char);
        }

        return $value;
    }
}
