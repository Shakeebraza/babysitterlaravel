<?php

namespace App\Models\Enums;

class NotificationEvent
{
    const UPDATE_DOCUMENT_STATUS = 'update_document_status';
    const CREATE_A_REQUEST_FOR_A_SPECIFIC_GROUP = 'create_a_request_for_a_specific_group';
    const NEW_GROUP_INVITATION = 'new_group_invitation';
    const REQUEST_ACCEPTED = 'request_accepted';
    const REWARD_A_APPLICATION = 'reward_a_application';
    const REJECT_A_APPLICATION = 'reject_a_application';
    const DELETE_A_REQUEST = 'delete_a_request';
    const GROUP_INVITATION_REJECTED = 'group_invitation_rejected';
    const INVITEE_HAVE_BEEN_ADDED = 'invitee_have_been_added';
    const SUBSCRIPTION = 'subscription';
    const RECOMMENDATION = 'recommendation';

}
