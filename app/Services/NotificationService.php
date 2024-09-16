<?php

namespace App\Services;

use App\Jobs\NotifyUsersJob;
use App\Mail\RecommendationMail;
use App\Mail\SubscriptionMail;
use App\Models\Enums\NotificationMethod;
use App\Models\FeedItem;
use App\Models\User;
use App\Models\UserRequest;
use App\Services\Data\MessageDeliveryStats;
use App\Services\Data\ResultType;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;

class NotificationService
{

    protected MessageService $messageService;

    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    public function triggerNotifications()
    {
        Log::info("Start notifying users");
        $now = Carbon::now();

        $allUserIds = $this->getUsersToNotify($now);
        Log::info("Notifying " .count($allUserIds) . " users");

        if (empty($allUserIds)) {
            return;
        }

        foreach (array_chunk($allUserIds, 100) as $userIdsChunk) {
            //Queue::push(new NotifyUsersJob($userIdsChunk));
            $this->notifyUsers($userIdsChunk);
        }
    }

    public function getUsersToNotify(Carbon $now)
    {
        $subscriptionItems = FeedItem::where('done', false)
            ->where('type', FeedItem::SUBSCRIPTION)
            ->where('created_at', '<', $now->subMinutes(5));
        $subscriptionUserIds = $subscriptionItems->pluck('user_id')->unique()->toArray();

        $recommendationUserIds = User::whereHas('notificationSetting', function ($query) {
                $query->where('recommendation', NotificationMethod::PUSH)
                    ->orWhere('recommendation', NotificationMethod::EMAIL)
                    ->orWhere('recommendation', NotificationMethod::BOTH);
            })
            ->pluck('id')
            ->toArray();

        $recommendationItems = FeedItem::where('done', false)
            ->where('type', FeedItem::RECOMMENDATION)
            ->where('created_at', '<', $now->subMinutes(5)) // TODO think about this time
            ->whereIn('user_id', $recommendationUserIds);
        $recommendationUserIds = $recommendationItems->pluck('user_id')->unique()->toArray();

        return array_unique(array_merge($subscriptionUserIds, $recommendationUserIds));
    }

    public function notifyUsers(array $userIds)
    {
        $now = Carbon::now();
        $stats = new MessageDeliveryStats();

        DB::transaction(function () use ($userIds, $now, $stats) {
            $itemsQuery = FeedItem::where('done', false)
                ->whereIn('user_id', $userIds)
                ->lockForUpdate();

            $itemIds = $itemsQuery->pluck('id')->toArray();

            $itemsQuery->update(['notified' => $now, 'done' => true]);

            $notificationData = $this->collectNotificationData($itemIds, $userIds);

            $pushStats = $this->sendPushNotifications($notificationData[NotificationMethod::PUSH]);
            $stats->add($pushStats);

            $emailStats = $this->sendEmails($notificationData[NotificationMethod::EMAIL]);
            $stats->add($emailStats);
        });

        Log::info("Notifying users. users=" . count($userIds) . " stats=" . json_encode($stats->getMessageDelivery()));
    }

    protected function sendPushNotifications($items) : MessageDeliveryStats
    {
        $pushStats = new MessageDeliveryStats();
        foreach ($items as $item) {
            $userId = $item['user_id'];
            $subscriptionRequestIds = $item['subscription_request_ids'];
            $recommendedRequestIds = $item['recommended_request_ids'];

            // Retrieve the user from the database
            $user = User::find($userId);

            // Ensure user exists
            if ($user && (count($subscriptionRequestIds) > 0 || count($recommendedRequestIds) > 0)) {
                list($title, $body) = $this->composePushNotificationBody($subscriptionRequestIds, $recommendedRequestIds, $user->language);
                $count = $this->messageService->sendPushNotification($user, $title, $body);
                $pushStats->increment(NotificationMethod::PUSH, ResultType::SUCCESS, $count);
            }
        }
        return $pushStats;
    }

    /**
     * Compose the body of the push notification based on request IDs.
     *
     * @param array $subscriptionRequestIds
     * @param array $recommendedRequestIds
     * @return string[]
     */
    private function composePushNotificationBody(array $subscriptionRequestIds, array $recommendedRequestIds, $language): array
    {
        $title = trans('emails.new_subscription_push_title', array(), $language);
        $body = trans('emails.new_subscription_push_title', array('count' => count($subscriptionRequestIds)), $language);
        if (empty($subscriptionRequestIds) && !empty($recommendedRequestIds)) {
            $title = trans('emails.new_recommendation_push_title', array(), $language);
            $body = trans('emails.new_recommendation_push_title', array('count' => count($recommendedRequestIds)), $language);
        }

        return array($title, $body);
    }

    protected function sendEmails($items) : MessageDeliveryStats
    {
        $mailStats = new MessageDeliveryStats();
        foreach ($items as $item) {
            $userId = $item['user_id'];
            $subscriptionRequestIds = $item['subscription_request_ids'];
            $recommendedRequestIds = $item['recommended_request_ids'];

            // Retrieve the user from the database
            $user = User::find($userId);

            // Ensure user exists
            if ($user) {
                if (!empty($subscriptionRequestIds)) {
                    $subscriptionRequests = UserRequest::where('public_visibility', true)->whereIn('id', $subscriptionRequestIds)->get();
                    if ($subscriptionRequests->isNotEmpty()) {
                        Mail::to($user->email)->send(new SubscriptionMail($user->first_name, $subscriptionRequests, $user->language));
                        $mailStats->increment(NotificationMethod::EMAIL, ResultType::SUCCESS);
                    }
                }

                if (!empty($recommendedRequestIds)) {
                    $recommendedRequests = UserRequest::where('public_visibility', true)->whereIn('id', $recommendedRequestIds)->get();
                    if ($recommendedRequests->isNotEmpty()) {
                        Mail::to($user->email)->send(new RecommendationMail($user->first_name, $recommendedRequests, $user->language));
                        $mailStats->increment(NotificationMethod::EMAIL, ResultType::SUCCESS);
                    }
                }
            }
        }
        return $mailStats;
    }

    protected function collectNotificationData(array $itemIds, array $userIds)
    {
        $notificationData = [
            NotificationMethod::PUSH => [],
            NotificationMethod::EMAIL => [],
        ];

        $items = FeedItem::whereIn('id', $itemIds)->orderBy('user_id')->get();

        $pushEnabledSubscriptionUsers = User::whereIn('id', $userIds)
            ->whereHas('notificationSetting', function ($query) {
                $query->where('subscription', NotificationMethod::PUSH)
                    ->orWhere('subscription', NotificationMethod::BOTH);
            })
            ->pluck('id')
            ->toArray();

        $emailEnabledSubscriptionUsers = User::whereIn('id', $userIds)
            ->whereHas('notificationSetting', function ($query) {
                $query->where('subscription', NotificationMethod::EMAIL)
                    ->orWhere('subscription', NotificationMethod::BOTH);
            })
            ->pluck('id')
            ->toArray();

        $pushEnabledRecommendationUsers = User::whereIn('id', $userIds)
            ->whereHas('notificationSetting', function ($query) {
                $query->where('recommendation', NotificationMethod::PUSH)
                    ->orWhere('recommendation', NotificationMethod::BOTH);
            })
            ->pluck('id')
            ->toArray();

        $emailEnabledRecommendationUsers = User::whereIn('id', $userIds)
            ->whereHas('notificationSetting', function ($query) {
                $query->where('recommendation', NotificationMethod::EMAIL)
                    ->orWhere('recommendation', NotificationMethod::BOTH);
            })
            ->pluck('id')
            ->toArray();

        foreach ($items->groupBy('user_id') as $userId => $userItems) {
            $subscriptionRequestIds = [];
            $recommendedRequestIds = [];

            foreach ($userItems as $item) {
                if ($item->type === FeedItem::SUBSCRIPTION) {
                    $subscriptionRequestIds[] = $item->request_id;
                } elseif ($item->type === FeedItem::RECOMMENDATION) {
                    $recommendedRequestIds[] = $item->request_id;
                }
            }

            if (in_array($userId, $pushEnabledSubscriptionUsers) ||
                in_array($userId, $pushEnabledRecommendationUsers)) {
                $notificationData[NotificationMethod::PUSH][] = [
                    'user_id' => $userId,
                    'subscription_request_ids' => $subscriptionRequestIds,
                    'recommended_request_ids' => $recommendedRequestIds,
                ];
            }

            if (in_array($userId, $emailEnabledSubscriptionUsers) ||
                in_array($userId, $emailEnabledRecommendationUsers)) {
                $notificationData[NotificationMethod::EMAIL][] = [
                    'user_id' => $userId,
                    'subscription_request_ids' => $subscriptionRequestIds,
                    'recommended_request_ids' => $recommendedRequestIds,
                ];
            }
        }

        return $notificationData;
    }
}
