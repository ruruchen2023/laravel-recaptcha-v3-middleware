<?php

namespace Ruruchen2023\Recaptcha;

use Carbon\Carbon;
use GuzzleHttp\Client;

class RecaptchaService
{
    protected $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function verify(string $token, string $action, ?string $ip = null)
    {
        if ($this->config['enable'] !== true) {
            return [
                'success'      => true,
                'score'        => 1,
                'action'       => $action,
                'challenge_ts' => null,
                'hostname'     => null,
                'error-codes'  => [],
                'raw'          => null,
            ];
        }

        if (empty($this->config['secret']) || empty($token)) {
            return [
                'success'      => false,
                'score'        => null,
                'action'       => null,
                'challenge_ts' => null,
                'hostname'     => null,
                'error-codes'  => ['missing-secret-or-token'],
                'raw'          => null,
            ];
        }

        try {
            $client = new Client();
            $response = $client->post('https://www.google.com/recaptcha/api/siteverify', [
                'form_params' => [
                    'secret' => $this->config['secret'],
                    'response' => $token,
                    'remoteip' => $ip,
                ],
                'timeout'  => 5.0,
            ]);

            $body = $response->getBody()->getContents();
            $json = json_decode($body, true);

            // normalize
            $result = [
                'success'      => isset($json['success']) ? (bool) $json['success'] : false,
                'score'        => isset($json['score']) ? (float) $json['score'] : null,
                'action'       => $json['action'] ?? null,
                'challenge_ts' => isset($json['challenge_ts']) ? Carbon::parse($json['challenge_ts'])->toDateTimeString() : null,
                'hostname'     => $json['hostname'] ?? null,
                'error-codes'  => $json['error-codes'] ?? [],
                'raw'          => $json,
            ];

            // optional extra check: action must match expected
            if ($action && $result['action'] && $result['action'] !== $action) {
                // action mismatch -> mark success false and log
                $result['success'] = false;
                $result['error-codes'] = array_merge($result['error-codes'], ['action-mismatch']);
            }

            return $result;

        } catch (\Throwable $th) {

            // always suceess if server error occur
            return [
                'success'      => true,
                'score'        => 1,
                'action'       => null,
                'challenge_ts' => null,
                'hostname'     => null,
                'error-codes'  => [],
                'raw'          => null,
            ];
        }
    }
}