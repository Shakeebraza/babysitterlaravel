<?php

namespace App\Services;

use App\Models\Enums\NotificationMethod;
use App\Models\FeedItem;
use App\Models\User;
use App\Models\UserRequest;
use Illuminate\Support\Facades\DB;

class RecommendationService
{

    protected FeedItemService $feedItemService;

    public function __construct(FeedItemService $feedItemService)
    {
        $this->feedItemService = $feedItemService;
    }

    public function processRequest(UserRequest $request)
    {
        $recommendationUserIds = $this->getUsersInRangeFromRequest($request);

        return $this->feedItemService->addFeedItems($recommendationUserIds,
            $request->id,
            FeedItem::RECOMMENDATION
        );
    }


    /**
     * Match recommendations to the request, only consider users with enabled recommendation settings.
     * @param UserRequest $userRequest
     * @return mixed
     */
    public function getUsersInRangeFromRequest(UserRequest $userRequest): array
    {
        $lat = $userRequest->latitude;
        $long = $userRequest->longitude;
        $distanceLimit = 30; // distance in km

        $userIds = DB::table('users')
            ->join('notification_settings', 'users.id', '=', 'notification_settings.user_id')
            ->select('users.*')
            ->selectRaw('(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance', [$lat, $long, $lat])
            ->having('distance', '<=', $distanceLimit)
            ->whereIn('profile_type', [User::BOTH, User::BABYSITTER])
            ->where('notification_settings.recommendation', '!=', NotificationMethod::NONE)
            ->pluck('id');

        return $userIds->toArray();
    }
}
