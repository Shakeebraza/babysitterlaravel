<?php

namespace App\Models;

use App\Models\Enums\NotificationEvent;
use App\Models\Enums\NotificationType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationSetting extends Model
{
    use HasFactory;

    const EVENT_TYPE_MAPPING = [
        NotificationEvent::UPDATE_DOCUMENT_STATUS => NotificationType::SYSTEM_NOTIFICATION,

        NotificationEvent::NEW_GROUP_INVITATION => NotificationType::SYSTEM_NOTIFICATION,
        NotificationEvent::GROUP_INVITATION_REJECTED => NotificationType::SYSTEM_NOTIFICATION,
        NotificationEvent::INVITEE_HAVE_BEEN_ADDED => NotificationType::SYSTEM_NOTIFICATION,

        NotificationEvent::CREATE_A_REQUEST_FOR_A_SPECIFIC_GROUP => NotificationType::GROUP_REQUESTS,

        NotificationEvent::SUBSCRIPTION => NotificationType::SUBSCRIPTION,
        NotificationEvent::RECOMMENDATION => NotificationType::RECOMMENDATION,

        NotificationEvent::REQUEST_ACCEPTED => NotificationType::APPLICATION_UPDATES,
        NotificationEvent::REWARD_A_APPLICATION => NotificationType::APPLICATION_UPDATES,
        NotificationEvent::REJECT_A_APPLICATION => NotificationType::APPLICATION_UPDATES,
        NotificationEvent::DELETE_A_REQUEST => NotificationType::APPLICATION_UPDATES,
    ];

    protected $fillable = ['user_id',
        'system_notification',
        'application_updates',
        'subscription',
        'recommendation',];

    protected $hidden = ['created_at','updated_at'];

    public function User(){
        return $this->belongsTo('App\Models\User','user_id');
    }

}
