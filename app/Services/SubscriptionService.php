<?php

namespace App\Services;

use App\Models\FeedItem;
use App\Models\UserRequest;

class SubscriptionService
{

    protected FeedItemService $feedItemService;

    public function __construct(FeedItemService $feedItemService)
    {
        $this->feedItemService = $feedItemService;
    }

    public function processRequest(UserRequest $request)
    {
        $subscriptionUserIds = array(); # TODO define user_pks

        return $this->feedItemService->addFeedItems($subscriptionUserIds,
            $request->id,
            FeedItem::SUBSCRIPTION
        );
    }
}
