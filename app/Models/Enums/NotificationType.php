<?php

namespace App\Models\Enums;

class NotificationType
{
    const SYSTEM_NOTIFICATION = 'system_notification';
    const APPLICATION_UPDATES = 'application_updates';
    const GROUP_REQUESTS = 'group_requests';
    const SUBSCRIPTION = 'subscription';
    const RECOMMENDATION = 'recommendation';
}
