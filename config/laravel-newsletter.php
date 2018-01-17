<?php

return [

        /*
         * The api key of a MailChimp account. You can find yours here:
         * https://us10.admin.mailchimp.com/account/api-key-popup/
         */
        'apiKey' => env('MAILCHIMP_APIKEY','720e1f73a792a5d1e635cfbe39d48e50-us16'),

        /*
         * When not specifying a listname in the various methods, use the list with this name
         */
        'defaultListName' => 'subscribers',

        /*
         * Here you can define properties of the lists you want to
         * send campaigns.
         */
        'lists' => [

            /*
             * This key is used to identify this list. It can be used
             * in the various methods provided by this package.
             *
             * You can set it to any string you want and you can add
             * as many lists as you want.
             */
            'subscribers' => [

                /*
                 * A mail chimp list id. Check the mailchimp docs if you don't know
                 * how to get this value:
                 * http://kb.mailchimp.com/lists/managing-subscribers/find-your-list-id
                 */
                'id' => env('MAILCHIMP_LIST_ID','f55b109ed5'),
            ],
        ],

        /*
         * If you're having trouble with https connections, set this to false.
         */
        'ssl' => true,
];
