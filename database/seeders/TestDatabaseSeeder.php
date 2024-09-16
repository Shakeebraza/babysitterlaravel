<?php

namespace Database\Seeders;

use App\Models\AcceptedRequest;
use App\Models\Enums\AcceptedRequestStatus;
use App\Models\Enums\RequestStatus;
use App\Models\User;
use App\Models\Kids;
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\UserRequest;
use App\Models\UserRequestGroup;
use App\Models\UserRequestKids;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //User::factory(10)->create();

        $user1 = User::create([
            'first_name' => 'Thomas',
            'surname' => 'Hirschi',
            'email' => 'thomas@hirschi.ch',
            'password' => bcrypt('password'),
            'status' => 'active',
            'role' => 'user',

            'address' => 'Dorfbachstrasse 12, 8805 Richterswil',
            'street' => 'Dorfbachstrasse 12',
            'zip' => '8805',
            'city' => 'Richterswil',
            'latitude' => '47.2064059',
            'longitude' => '8.7065606',
        ]);

        $login = app(UserService::class)->createMultiLogin("password", $user1->id);

        $user2 = User::create([
            'first_name' => 'Marianne',
            'surname' => 'Hunziker',
            'email' => 'marianne@hunziker.ch',
            'password' => bcrypt('password'),
            'status' => 'active',
            'role' => 'user',

            'address' => 'Dorfbachstrasse 44, 8805 Richterswil',
            'street' => 'Dorfbachstrasse 44',
            'zip' => '8805',
            'city' => 'Richterswil',
            'latitude' => '47.2064059',
            'longitude' => '8.7065606',
        ]);

        $login2 = app(UserService::class)->createMultiLogin("password", $user2->id);

        $birthday = Carbon::now()->subYears(6)->subMonth()->toDateString();
        $kid = Kids::create([
            'user_id' => $user1->id,
            'name' => 'Max',
            'date_of_birth' => $birthday,
            'status' => 'active',
        ]);

        $group = Group::create([
            'user_id' => $user1->id,
            'name' => 'Family',
            'status' => 'active',
        ]);

        $groupMember = GroupMember::create([
            'group_id' => $group->id,
            'member_id' => $user2->id,
            'member_email' => '',
            'status' => 1,
        ]);

        $request = $this->createGroupRequest($user1->id, $group, $kid, 14, 1, $user2->id);
        $request2 = $this->createGroupRequest($user1->id, $group, $kid, 16, 0, 0);
        $request3 = $this->createGroupRequest($user1->id, $group, $kid, 18, 0, 0);

        $request = $this->createPublicRequest($user1->id, $kid, 15,1, $user2->id);
        $request2 = $this->createPublicRequest($user1->id, $kid, 17, 0, 0);
        $request3 = $this->createPublicRequest($user1->id, $kid, 19, 0, 0);

        //$this->call(OtherTableSeeder::class);
    }

    /**
     * @param $requestOwnerId
     * @param $group
     * @param $kid
     * @param $days
     * @param $awarded
     * @param $awardedUserId
     * @return UserRequest
     */
    public function createGroupRequest($requestOwnerId, $group, $kid, $days, $awarded, $awardedUserId) : UserRequest
    {
        $request = UserRequest::create([
            'user_id' => $requestOwnerId,
            'title' => "First Request",
            'description' => "Test Description",
            'from_date' => Carbon::now()->addDays($days),
            'to_date' => Carbon::now()->addDays($days)->addHour(),
            'max_amount' => 25,
            'visibility' => 'group',
            'address_type' => 'home',
            'address' => 'Dorfbachstrasse 12, 8805 Richterswil',
            'street' => 'Dorfbachstrasse 12',
            'zip' => '8805',
            'city' => 'Richterswil',
            'latitude' => '47.2064059',
            'longitude' => '8.7065606',
            'status' => 'active',
            'awarded' => $awarded
        ]);

        $requestGroup = UserRequestGroup::create([
            'request_id' => $request->id,
            'group_id' => $group->id
        ]);

        $requestKid = UserRequestKids::create([
            'request_id' => $request->id,
            'kids_id' => $kid->id
        ]);

        if ($awarded == 1) {
            $this->createAcceptedRequest($request, $awardedUserId, $requestOwnerId);
        }

        return $request;
    }

    /**
     * @param $requestOwnerId
     * @param $kid
     * @param $days
     * @param $awarded
     * @return UserRequest
     */
    public function createPublicRequest($requestOwnerId, $kid, $days, $awarded, $awardedUserId): UserRequest
    {
        $request = UserRequest::create([
            'user_id' => $requestOwnerId,
            'title' => "First Request",
            'description' => "Test Description",
            'from_date' => Carbon::now()->addDays($days),
            'to_date' => Carbon::now()->addDays($days)->addHour(),
            'max_amount' => 25,
            'visibility' => 'public',
            'address_type' => 'home',
            'address' => 'Dorfbachstrasse 12, 8805 Richterswil',
            'street' => 'Dorfbachstrasse 12',
            'zip' => '8805',
            'city' => 'Richterswil',
            'latitude' => '47.2064059',
            'longitude' => '8.7065606',
            'status' => 'active',
            'awarded' => $awarded
        ]);

        if ($kid != null) {
            $requestKid = UserRequestKids::create([
                'request_id' => $request->id,
                'kids_id' => $kid->id
            ]);
        }

        if ($awarded == 1) {
            $this->createAcceptedRequest($request, $awardedUserId, $requestOwnerId);
        }

        return $request;
    }

    /**
     * @param $request
     * @param $awardedUserId
     * @param $requestOwnerId
     * @return AcceptedRequest
     */
    public function createAcceptedRequest($request, $awardedUserId, $requestOwnerId): AcceptedRequest
    {
        $acceptedRequest = AcceptedRequest::create([
            'request_id' => $request->id,
            'user_id' => $awardedUserId,
            'request_status' => RequestStatus::APPLIED->value,
            'status' => AcceptedRequestStatus::REWARDED->value,
            'awarded_by' => $requestOwnerId,
            'description' => "No problem",
            'payment_type' => AcceptedRequest::FREE,
            'amount' => 0
        ]);
        return $acceptedRequest;
    }
}
