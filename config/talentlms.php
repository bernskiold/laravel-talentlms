<?php

return [

    /**
     * API Connection Details
     */
    'api' => [

        /**
         * Your primary API key for the TalentLMS API.
         *
         * You can find this key in your account settings under Integrations and API.
         */
        'api_key' => env('TALENTLMS_API_KEY'),

        /**
         * The domain name for your TalentLMS instance.
         *
         * This is generally in the format `https://youraccount.talentlms.com`.
         */
        'domain' => env('TALENTLMS_DOMAIN', null),

        /**
         * The version of the API to use.
         *
         * Currently, the only supported version is 1.
         * This setting is here for future compatibility.
         */
        'version' => env('TALENTLMS_API_VERSION', '1'),
    ],

];
