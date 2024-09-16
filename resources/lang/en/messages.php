<?php

return [
    /*
     * simple strings
     */
    'month' => 'month|months',
    'year' => 'year|years',

    'hour' => 'hour|hours',
    'day' => 'day|days',

    'general_error' => 'Something went wrong. Please try again',
    'email_password_incorrect' => 'Email address or password is incorrect. Please try again.',
    'account_inactive' => 'Your account has been inactive',
    'login_successfully' => 'Login Successfully',
    'logout_successfully' => 'Logout Successfully',
    'verification_code_success' => 'Verification code send successfully.',
    'registration_success' => 'Registration successfully done',
    'registration_mail_already_used' => 'Email address is already used',
    'registration_fail' => 'Something is wrong. Please try again',
    'success' => 'Success',
    'profile_update_success' => 'Profile updated successfully',
    'user_not_found' => 'Session expired. Please login again.',
    'update_app' => 'Please update the app and login again',
    'document_uploaded_success' => 'Document uploaded successfully.',
    'document_status_updated' => 'Verficiation status updated.',
    'document_status_msg' => 'Verification approved.',
    'document_status_reject_msg' => 'Verification rejected: :reason',
    'forgot_password_reset_success' => 'Password Reset Verification code send successfully.',
    'forgot_password_wrong_verification_code' => 'Your verification code is wrong.',
    'reset_password_user_not_found' => 'user not found',
    'reset_password_wrong_verification_code' => 'Your verification code is wrong.',
    'reset_password_success' => 'Password changed successfully',
    'verification_code_resent' => 'Verification code sent again',
    'change_password_password_invalid' => 'Old password is invalid',
    'change_password_success' => 'Password changed successfully',
    'email_is_successfully_verified' => 'Email is successfully verified',
    'group_is_successfully_created' => 'Group is successfully created',
    'request_is_successfully_created' => 'Request is successfully created',
    'data_not_found' => 'Data not found',
    'feedback_added_successfully' => 'Feedback added successfully',
    'group_not_found' => 'Group not found',
    'group_deleted_successfully' => 'Group deleted successfully',
    'request_is_successfully_updated' => 'Request is successfully updated',
    'kids_added_successfully' => 'Kids added successfully',
    'kids_not_found' => 'Kids not found',
    'kids_is_successfully_updated' => 'Kids is successfully updated',
    'kids_is_successfully_deleted' => 'Kids is successfully deleted',
    'request_deleted_successfully' => 'Request deleted successfully',
    'document_not_found' => 'Document not found',
    'member_not_found' => 'Member not found',
    'you_can_not_add_your_self_to_a_group' => 'You can not add your self to a group.',
    'this_member_is_already_added' => 'This member is already added',
    'this_member_is_already_invited' => 'This member is already invited',
    'member_added_successfully' => 'Member added successfully',
    'member_deleted_successfully' => 'Member deleted successfully',
    'invitation_already_send' => 'Invitation already send',
    'member_already_added' => 'Member already added',
    'invitation_send_successfully' => 'Invitation send successfully',
    'request_is_successfully_accepted' => 'Request is successfully accepted',
    'request_is_successfully_decline' => 'Request is successfully decline',
    'accepted_request_not_found' => 'Accepted Request not found',
    'request_awarded_is_successfully' => 'Request awarded is successfully',
    'request_disapproved_is_successfully' => 'Request disapproved is successfully',
    'request_deleted_is_successfully' => 'Request deleted is successfully',
    'your_account_has_been_deleted_successfully' => 'Your account has been deleted successfully',
    'token_updated_successfully' => 'Token updated successfully',
    'code_is_invalid' => 'Code is invalid',
    'group_is_already_exist' => 'Group is already exist',
    'request_not_found' => 'Request not found',
    'notification_not_found' => 'Notification not found',
    'group_request_not_found' => 'Group request not found',
    'group_request_accepted' => 'Group request accepted',
    'group_request_rejected' => 'Group request rejected',

    /*Notification Message Start*/
    'added_title' => 'New request have been added',
    'added_message' => 'There is a new request for you.',

    'request_accepted_title' => 'Request accepted.',
    'request_accepted_message' => 'Your request have been accepted by :apply_name',

    'request_declined_title' => 'Request declined.',
    'request_declined_message' => 'Your request have been decline by :apply_name',

    'request_awarded_title' => 'Application is rewarded',
    'request_awarded_message' => 'Congratulations. Your application has been rewarded',

    'request_rejected_title' => 'Application is rejected',
    'request_rejected_message' => 'Your application has been rejected',

    'request_deleted_title' => 'Application has been withdrawn',
    'request_deleted_message' => ':invitee_name has withdrawn the application',

    'invitee_title' => 'User has been added to group',
    'invitee_message' => ':invitee_name has accept your invitation',

    'invitee_rejected_title' => 'User has been rejected group invitation',
    'invitee_rejected_message' => ':invitee_name has rejected your group invitation',

    'group_invitation_title' => 'New group invitation.',
    'group_invitation_message' => 'You have received group invitation from :sender_name.',
    /*Notification Message End*/

    /*General Mail Start*/
    'hello' => 'Hello,',
    'helloPersonal' => 'Hello :name,',
    /*General Mail End*/

    /*Invitation Mail Start*/
    'invitationSubject' => 'You were invited by :inviter',
    'para1' => ":inviter would like to extend a warm invitation for you to join our Babysitter app. You're invited to becoming a part of the circle of friends and benefit from its advantages of the app.",
    'para2' => "What makes the Babysitter app so special? It's all about the effortless search for babysitters, including those within your colleagues. It's the perfect platform to find a trusted caregiver for any occasion.",
    'para3' => "If you're curious, simply download the Babysitter app on your smartphone. :inviter and the entire community look forward to welcoming you.",
    /*Invitation Mail End*/

    /*Verification Code Start*/
    'verification_code_title' => 'Email Verification Code',
    'verification_code_preheader' => 'Thank you for registering with Babysitter. Your confirmation code is...',
    'vpara1' => 'Thank you for registering with Babysitter. To complete the verification process and ensure the security of your account, please use the following verification code:',
    'verification_code' => 'Verification Code:',
    'vpara2' => 'Please enter this code on the our mobile application to confirm your email address.',
    /*Verification Code End*/

    /*Password Reset Mail Start*/
    'password_reset_title' => 'Password Reset Verification Code',
    'pr_para1' => 'You have requested to reset your password for your Babysitter account. To complete this process, please use the following verification code:',
    'best_regards' => 'Best regards',
    'babysitter_app_team' => 'Babysitter-App Team',
    /*Password Reset Mail End*/

    /*Website Content Start*/
    'landing_page_description' => 'Your trusted network of caregivers - Find the ideal babysitter, even among your colleagues.',
    'for_what' => 'For what?',
    'about_app' => 'About the App',
    'testimonial' => 'Testimonial',
    'download_app' => 'Download App',
    'contact_us' => 'Contact us',
    'about_us' => 'About us',
    'english' => 'English',
    'german' => 'German',
    'childcare' => 'Childcare',
    'daily_life' => 'For Your Daily Life',
    'discover_the_ideal' => 'Discover the ideal babysitter, even among your colleagues. Join now!',
    'the_right_babysitter' => 'The right babysitter for every occasion',
    'evening_sitters' => 'Evening Sitters',
    'would_you_like' => 'Would you like to treat yourself to a quiet evening?',
    'daytime_help' => 'Daytime Help',
    'are_you_looking' => 'Are you looking for help during the day?',
    'after_school' => 'After-school',
    'should_someone' => 'Should someone catch the children after school until they get home?',
    'health_care' => 'Health Care',
    'are_you_ill' => "Are you ill, need to rest and can't look after your children?",
    'no_problem' => "No Problem - you'll find a solution with",
    'babysitter_app' => "Babysitter-App",
    'find_the_right' => "Find the right babysitter for your needs the modern way",
    'find_a_nanny' => "Finding a nanny can be a tedious task. Of course, you don't want to entrust the care of your child to just anyone. As a parent, you are naturally cautious and want to be sure that the person you hire will take good care of your child.",
    'app_supports' => "Babysitter-App supports you in this:",
    'only_verified' => "Only verified babysitters receive the label",
    'you_decide' => "You decide in which user group your request is visible",
    'can_be_rated' => "Babysitters can be rated and the ratings can be viewed",
    'contact_details' => "Contact details are available at the beginning for further clarification or to get to know each other",
    'hire_a_babysitter' => "You decide in the end if you like and hire a babysitter",
    'usp' => 'What makes Babysitter-App special?',
    'usp_detail' => 'With our platform, we bring together family care by grandparents and friends and professional childcare by babysitters and nannies. Whether you are looking for familiar faces or certified professionals, our app makes it possible to seamlessly combine both worlds. This simplifies the organization of childcare for all parents - safely, easily and in one place.',
    'insights' => 'Insights',
    'create_profile' => 'Create a profile and add kids',
    'create_groups' => 'Create any groups and invite friends',
    'show_requests' => 'Show requests from friends and in your area',
    'manage_request_apply' => 'Manage requests and applications',
    'client_says' => "Client Says",
    'testimonial1' => "I've been using this app for a while now, and it's a game changer! It's free, and I can quickly connect with family and friends for babysitting. It’s such a relief to have easy access to people I trust to watch my kids.",
    'testimonial2' => "Balancing work and parenting is challenging. This app is a lifesaver, offering a network of dependable babysitters. It's streamlined my life, ensuring I always have reliable childcare when I need it.",
    'testimonial3' => "Finding flexible work that fits my class schedule used to be tough. This app has been a lifesaver, helping me find babysitting jobs fast. Its user-friendly interface is a huge plus!",
    'client1' => "Sarah Johnson, a single parent",
    'client2' => "Emily Davis, a working professional and mother",
    'client3' => "David Brown, college student and part-time babysitter",
    'beautiful_and_safe' => 'Beautiful and safe way to find babysitters and make daily life easier for moms and dads who need help at home!',
    'justin' => 'Justin',
    'children_is_always' => "Childcare is always within reach with",
    'quick_view' => "Quickly view the babysitting jobs and available babysitters or nannies in your area via app!",
    'get_in_touch' => "Get in touch",
    'any_question' => "If you have any questions, please do not hesitate to contact us.",
    'full_name' => "Full name",
    'email' => "Email",
    'phone' => "Phone number",
    'message' => "Message",
    'send' => "Send",
    'makes_daily_life' => "makes daily life easier",
    'are_you_unsure' => "Are you unsure? Then just try it out, it costs nothing.",
    'person_behind' => "The person behind",
    'idea' => "idea",
    'profile_para1' => "My name is Raffael Santschi, I am the father of two children myself and I know all the problems described only too well. That's why I've decided to put an end to these problems. We live in an age where technology and networking can make life easier. It's time to use these opportunities to support families.",
    'profile_para2' => "Babysitter-App makes it easier to find a babysitter, no matter where you are, for whatever purpose you need them, and preferably from within your own circle of family or acquaintances. With this app, it becomes easier to support each other in challenging times and ensure the care of our children.",
    'privacy_policy' => "Privacy Policy",
    'terms' => "Terms",
    'copyright' => "Copyright by",
    'all_rights_reserved' => "All rights reserved.",
    'privacy_policy_title' => 'Privacy Policy for Babysitter-App',
    'effective_date_message' => 'Effective Date',
    'effective_date' => 'October 4, 2023',
    'protecting_personal_information'   => 'At Babysitter-App, we value your privacy and are committed to protecting your personal information. This Privacy Policy explains how we collect, use, and disclose information when you use Babysitter-App and your associated privacy rights. By using the app, you agree to the practices described in this Privacy Policy.',
    'information_we_collect' => '1. What Information We Collect',
    'types_of_information' => 'We may collect the following types of information:',
    'personal_data' => 'Personal Data:',
    'personal_information_such_as' => 'We may ask for personal information such as your name, email address, phone number, date of birth, and postal address.',
    'usage_data' => 'Usage Data:',
    'information_about_how_you_use' => 'We collect information about how you use the app, including pages visited, duration of your visit, and other usage statistics.',
    'device_information' => 'Device Information:',
    'collect_information_about_device' => 'We may collect information about your mobile device, including model, operating system version, and unique device identifiers.',
    'use_your_information' => '2. How We Use Your Information',
    'use_your_information_to' => 'We use the information we collect to:',
    'app_and_its_features' => 'Enable your use of our app and its features.',
    'content_and_features' => 'Provide you with relevant content and features.',
    'new_features_of_the_app' => 'Deliver information about updates, changes, or new features of the app.',
    'respond_to_your_requests_and_inquiries' => 'Process and respond to your requests and inquiries.',
    'protect_your_information' => '3. How We Protect Your Information',
    'reasonable_security_measures' => 'We implement reasonable security measures to protect your personal information.',
    'information_sharing' => '4. Information Sharing',
    'share_your_personal_information' => 'We do not share your personal information with third parties without your express consent, unless required by law.',
    'rights_and_control_information' => '5. Your Rights and Control Over Your Information',
    'access_correct_or_delete_information' => 'You have the right to access, correct, or delete your personal information. If you have questions about your rights or the use of your information, please contact us at',
    'changes_privacy_policy' => '6. Changes to This Privacy Policy',
    'update_privacy_policy_periodically' => 'We may update this Privacy Policy periodically to reflect changes in our practices or legal requirements. The current version of this Privacy Policy will be posted on the website.',
    'privacy_policy_contact_us' => '7. Contact Us',
    'questions_privacy_policy' => 'If you have questions about this Privacy Policy or your privacy practices, please contact us at:',
    'privacy_policy_email' => 'Email:',
    'privacy_policy_postal_address' => 'Postal Address:',
    'privacy_policy_address' => 'Dorfstrasse 12, 8805 Richterswil, Switzerland',

    'terms_conditions' => 'Terms and Conditions',
    'last_updated' => 'Last Updated:',
    'read_terms' => 'Please read these Terms and Conditions carefully before using Babysitter-App operated by us.',
    'acceptance_terms' => 'Acceptance of Terms',
    'by_accessing' => 'By accessing or using the Service, you agree to be bound by these Terms. If you disagree with any part of these Terms, then you may not access the Service.',
    'use_of_service' => 'Use of the Service',
    'accounts' => 'Accounts',
    'to_user_certain' => 'To use certain features of the Service, you may be required to register for an account. You agree to provide accurate, current, and complete information during the registration process and to update such information to keep it accurate, current, and complete.',
    'content' => 'Content',
    'you_are_solely' => 'You are solely responsible for any content you post on the Service. You must not post content that is offensive, illegal, or violates the rights of others.',
    'privacy' => 'Privacy',
    'governed_by' => 'Your use of the Service is also governed by our Privacy Policy, which can be found',
    'here' => 'here',
    'termination' => 'Termination',
    'we_may_terminate' => 'We may terminate or suspend your account and access to the Service immediately, without prior notice or liability, for any reason whatsoever, including without limitation if you breach these Terms.',
    'intellectual_property' => 'Intellectual Property',
    'service_original_content' => 'The Service and its original content, features, and functionality are and will remain the exclusive property of Babysitter-App and its licensors. The Service is protected by copyright, trademark, and other laws.',
    'limitation_liability' => 'Limitation of Liability',
    'event_shall_babysitter' => 'In no event shall Babysitter-App, nor its directors, employees, partners, agents, suppliers, or affiliates, be liable for any indirect, incidental, special, consequential, or punitive damages, including without limitation, loss of profits, data, use, goodwill, or other intangible losses, resulting from (i) your use or inability to use the Service; (ii) any unauthorized access to or use of our servers and/or any personal information stored therein; (iii) any interruption or cessation of transmission to or from the Service; (iv) any bugs, viruses, trojan horses, or the like that may be transmitted to or through the Service by any third party; (v) any errors or omissions in any content or for any loss or damage incurred as a result of the use of any content posted, emailed, transmitted, or otherwise made available through the Service; and/or (vi) user content or the defamatory, offensive, or illegal conduct of any third party.',
    'changes_terms' => 'Changes to Terms',
    'reserve_rights' => "We reserve the right, at our sole discretion, to modify or replace these Terms at any time. If a revision is material, we will try to provide at least 30 days' notice prior to any new terms taking effect. What constitutes a material change will be determined at our sole discretion.",
    'any_question_terms' => "If you have any questions about these Terms, please contact us at",

    /*Website Content End*/

    /*Contact Us Form Start*/
    'contact_form_title' => 'Contact from user',
    'new_inquiry' => 'You have received a new inquiry.',
    'new_inquiry_subject' => 'You have received a new inquiry.',
    'inquiry_success' => 'Inquiry send successfully',
    /*Contact Us Form End*/

    /*Validation Message Start*/
    'name_validation' => 'The full name field is required.',
    'email_validation' => 'The email field is required.',
    'phone_validation' => 'The phone field is required.',
    'message_validation' => 'The message field is required.',
    /*Validation Message End*/
];
