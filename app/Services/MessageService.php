<?php

namespace App\Services;

use App\Mail\SingleMessageMail;
use App\Mail\WelcomeBabysitterMail;
use App\Mail\WelcomeParentMail;
use App\Models\DeviceToken;
use App\Models\Enums\NotificationMethod;
use App\Models\Notification;
use App\Models\NotificationSetting;
use App\Models\SentMail;
use App\Models\User;
use App\Models\UserRequest;
use Carbon\Carbon;
use DateInterval;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use InvalidArgumentException;

class MessageService
{

    private $delayMap = ["WELCOME_MAIL" => 'PT1M'];

    public function createNotification($sender, $users, $event, $title_key, $title_replace, $body_key, $body_replace, UserRequest $request = null){
        foreach ($users as $user){
            $title = trans($title_key, $title_replace, $user->language);
            $body = trans($body_key, $body_replace, $user->language);
            try{
                $in['sender'] = $sender;
                $in['receiver'] = $user->id;
                $in['event'] = $event;
                $in['title'] = $title;
                $in['notification'] = $body;
                if ($request) {
                    $in['request_id'] = $request->id;
                }
                Notification::create($in);
            }catch(Exception $e){
                Log::info("Could not create notification ===>".$e->getMessage());
                continue;
            }

            if ($this->isMethodAllowed($user, $event, NotificationMethod::PUSH)) {
                $this->sendPushNotification($user, $title, $body);
            }
            if ($this->isMethodAllowed($user, $event, NotificationMethod::EMAIL)) {
                try {
                    Mail::to($user->email)->send(new SingleMessageMail($user->first_name, $title, $body, $request, $user->language));
                    Log::info("Send mail to " . $user->email . "(" . $user->id . ") - " . $title . ": " . $body);
                } catch (Exception $e) {
                    Log::info("Mail Exception===>" . $e->getMessage());
                    continue;
                }
            }
        }
    }

    public function sendPushNotification(User $user, $title, $body) : int
    {
        $userTokens = DeviceToken::where('user_id', $user->id)->pluck('device_token')->toArray();
        try {
            $this->sendPush($userTokens, $title, $body);
            Log::info("Send push to " . $user->email . "(" . $user->id . ") - " . $title . ": " . $body);
        } catch (Exception $e) {
            Log::info("PushNotification Exception===>" . $e->getMessage());
        }
        return count($userTokens);
    }

    public function sendPush($tokens,$title,$body){
        $SERVER_API_KEY = 'AAAAAIsg_Is:APA91bGr_5dl1SxnDTiO1UPRjk48BHEWMCT0NFv1tNLaxKQ3Kaz9HNR2DOZkiQxJ1sS9AE07MYWyClTWCnho-QaGaTIA8Q6pdYaZS8_Af12-_VdefzrxIGOPlW0b13_CtJ4pVoS0VjDM';
        $data = [
            "registration_ids" => $tokens,
            "notification" => [
                "title" => $title,
                "body" => $body,
            ]
        ];

        $dataString = json_encode($data);
        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);
        //Log::info("Notification Response==>".print_r($response));
        //dd($response);
    }

    public function canSendEmail($userId, $emailType, $messageName, $language)
    {
        $lastEmailByType = SentMail::where('user_id', $userId)
            ->where('email_type', $emailType)
            ->latest()
            ->first();

        if (!$lastEmailByType) {
            return true;
        }

        $maxDate = Carbon::now()->sub(new DateInterval($this->delayMap[$emailType]));
        if ($lastEmailByType->created_at < $maxDate){
            $lastEmailPerMessageName = SentMail::where('user_id', $userId)
                ->where('email_type', $emailType)
                ->where('message_name', $messageName)
                ->where('language', $language)
                ->latest()
                ->first();

            if (!$lastEmailPerMessageName) {
                return true;
            }
        }
        return false;
    }

    public function getNotificationTypeForEvent($event): string
    {
        $typeValue = NotificationSetting::EVENT_TYPE_MAPPING[$event] ?? null;

        if ($typeValue === null) {
            throw new InvalidArgumentException("Invalid event.");
        }

        return $typeValue;
    }

    public function isMethodAllowed($user, $notificationEvent, $notificationMethod){
        $notificationType = $this->getNotificationTypeForEvent($notificationEvent);
        $notificationSetting = $user->notificationSetting;

        $currentSetting = $notificationSetting->$notificationType;

        if ($currentSetting === NotificationMethod::NONE) {
            return false;
        }

        if ($currentSetting === NotificationMethod::BOTH) {
            return true;
        }

        return $currentSetting === $notificationMethod;
    }

    public function recordSentEmail($userId, $emailType, $messageName, $language)
    {
        SentMail::create([
            'user_id' => $userId,
            'email_type' => $emailType,
            'message_name' => $messageName,
            'language' => $language,
        ]);
    }

    public function sendWelcomeEmail(User $user) : bool
    {
        $messageName = ($user->profile_type == User::BABYSITTER) ? 'babysitterWelcomeMessage' : 'parentWelcomeMessage';
        return $this->sendSpecificWelcomeMail($user, $messageName, $user->language);
    }

    public function sendSpecificWelcomeMail(User $user, $messageName, $language) : bool
    {
        $emailType = "WELCOME_MAIL";
        if ($this->canSendEmail($user->id, $emailType, $messageName, $language)) {
            if ($messageName == 'babysitterWelcomeMessage') {
                Mail::to($user->email)->send(new WelcomeBabysitterMail($user->first_name, $language));
            } else {
                Mail::to($user->email)->send(new WelcomeParentMail($user->first_name, $language));
            }

            $this->recordSentEmail($user->id, $emailType, $messageName, $language);

            return true;
        }

        return false;
    }

    public function sendToEveryUserWelcomeMail() : int
    {
        $counter = 0;
        try {
            $allUsers = User::select()->get();
            foreach ($allUsers as $user) {
                if ($counter % 5) {
                    sleep(1);
                }
                if ($counter >= 50) {
                    break;
                }
                if ($user->profile_type != null) {
                    $sent = $this->sendWelcomeEmail($user);
                    if ($sent) {
                        $counter++;
                    }
                }
                /*
                else {
                    if ($user->language != null) {
                        $messageName = 'babysitterWelcomeMessage';
                        $sent = $this->sendSpecificWelcomeMail($user, $messageName, $user->language);
                        if (!$sent) {
                            $messageName = 'parentWelcomeMessage';
                            $this->sendSpecificWelcomeMail($user, $messageName, $user->language);
                        }
                        if ($sent) {
                            $counter++;
                        }
                    } else {
                        $language = "en";
                        $messageName = 'babysitterWelcomeMessage';
                        $sent = $this->sendSpecificWelcomeMail($user, $messageName, $language);
                        if (!$sent) {
                            $messageName = 'parentWelcomeMessage';
                            $sent = $this->sendSpecificWelcomeMail($user, $messageName, $language);
                        }
                        $language = "de";
                        if (!$sent) {
                            $messageName = 'babysitterWelcomeMessage';
                            $sent = $this->sendSpecificWelcomeMail($user, $messageName, $language);
                        }
                        if (!$sent) {
                            $messageName = 'parentWelcomeMessage';
                            $sent = $this->sendSpecificWelcomeMail($user, $messageName, $language);
                        }
                        if ($sent) {
                            $counter++;
                        }
                    }
                }
                */
            }
            return $counter;
        } catch (\Exception $e){
            Log::error("Error during mass mail sending: " . $e->getMessage());
            return $counter;
        }
    }

    public function sendWelcomeBabysitterMessage(User $user){
        $language = "en";
        $messageName = 'babysitterWelcomeMessage';
        $sent = $this->sendSpecificWelcomeMail($user, $messageName, $language);
        echo $sent;
    }

    public function sendWelcomeParentMessage(User $user){
        $language = "en";
        $messageName = 'parentWelcomeMessage';
        $sent = $this->sendSpecificWelcomeMail($user, $messageName, $language);
        echo $sent;
    }
}
