<?php

namespace App\Lib\Api\VideoCloud;

/**
 * VideoCloud Notification Resources
 * 
 * @author Kuniyasu Wada
 */
Trait NotificationsClient
{
    /**
     * Get a list of all notification subscriptions for the account
     * 
     * @see     http://docs.brightcove.com/en/video-cloud/cms-api/references/cms-api/versions/v1/index.html#api-notificationGroup-Get_Subscriptions_List
     * @example https://cms.api.brightcove.com/v1/accounts/:account_id/subscriptions
     * @return  mixed
     */
    public function getSubscriptionsList()
    {
        $url = "{$this->getCmsUrl()}/v1/accounts/{$this->getAccountId()}/subscriptions";
        $header = [
            'Content-type: application/json',
            "Authorization: Bearer {$this->getAccessToken()}",
        ];
        
        return $this->call('GET', $url, $header);
    }
}
