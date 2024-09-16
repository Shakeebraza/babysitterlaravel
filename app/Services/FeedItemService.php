<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FeedItemService
{

    function addFeedItems(array $userIds, int $requestId, string $type): int
    {
        $userIds = collect($userIds);

        // Exclude users who already have a FeedItem for this request.
        $existingUserIds = DB::table('feed_items')
            ->whereIn('user_id', $userIds)
            ->where('request_id', $requestId)
            ->pluck('user_id');

        $userIds = $userIds->diff($existingUserIds);

        $feedItems = $userIds->map(function ($userId) use ($requestId, $type) {
            return [
                'user_id' => $userId,
                'request_id' => $requestId,
                'type' => $type,
                'done' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        });

        Log::info("Insert " . count($feedItems) . " feed items");

        DB::table('feed_items')->insert($feedItems->toArray());

        return $userIds->count();
    }

    function removeFeedItemsByRequest(int $requestId): int
    {
        $now = Carbon::now();
        $updatedRows = DB::table('feed_items')
            ->where('request_id', $requestId)
            ->update([
                'removed' => $now,
                'done' => true
            ]);

        return $updatedRows;
    }

    function removeFeedItemsForUser(int $userId): int
    {
        $deletedRows = DB::table('feed_items')
            ->where('user_id', $userId)
            ->delete();

        return $deletedRows;
    }
}
