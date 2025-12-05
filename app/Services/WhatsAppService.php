<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WhatsAppService
{
    public function sendTemplate($phone, $templateName, $languageCode = null, $headerParams = [], $bodyParams = [])
    {
        // dd($bodyParams, $headerParams, $templateName, $languageCode, $phone);
        $url = config('whatsapp.domain') .
            "/api/" .
            config('whatsapp.version') .
            "/messages/send-template/" .
            config('whatsapp.channel');

        $template = [
            "Name" => $templateName,
            "Language" => [
                "Code" => $languageCode ?? config('whatsapp.default_language'),
            ],
        ];

        // ------------------------------------------
        // ADD HEADER COMPONENT (IF HEADER VARIABLE EXISTS)
        // ------------------------------------------
        if (!empty($headerParams)) {
            $headerComponent = [
                "type" => "header",
                "parameters" => [],
            ];

            foreach ($headerParams as $param) {
                $headerComponent["parameters"][] = [
                    "type" => "text",
                    "text" => $param,
                ];
            }

            $template["components"][] = $headerComponent;
           
        }

        // ------------------------------------------
        // ADD BODY COMPONENT (FOR {{1}}, {{2}}, etc.)
        // ------------------------------------------
        if (!empty($bodyParams)) {
            $bodyComponent = [
                "type" => "body",
                "parameters" => [],
            ];

            foreach ($bodyParams as $param) {
                $bodyComponent["parameters"][] = [
                    "type" => "text",
                    "text" => $param,
                ];
            }

            $template["components"][] = $bodyComponent;
        }
        // ------------------------------------------
        // FINAL PAYLOAD
        // ------------------------------------------
        $payload = [
            "MessagingProduct" => config('whatsapp.product'),
            "RecipientType"    => config('whatsapp.recipient_type'),
            "to"               => config('whatsapp.mobile_prefix') . $phone,
            "Type"             => "template",
            "Template"         => $template,
        ];

        // SEND REQUEST
        return Http::withToken(config('whatsapp.api_key'))
            ->acceptJson()
            ->post($url, $payload)
            ->json();
    }

}