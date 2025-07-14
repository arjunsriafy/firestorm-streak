<?php
    if (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || isset($_GET['debug'])) {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    } else {
        // On staging/production: hide error
        ini_set('display_errors', 0);
        error_reporting(0);
    }
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    // echo file_get_contents("php://input");exit;
    if(isset($_POST) && !empty($_POST)){
        $inputPayload = $_POST;
    }
    else{
        $inputPayload = json_decode(file_get_contents("php://input"), true);
        $inputPayloadFiles = array();
    }
    if(isset($_FILES) && !empty($_FILES)){
        $inputPayloadFiles = $_FILES;
    }

    $user = $_GET['user'] ?? '';
    $action = $_GET['action'] ?? '';
    $method = "$user-$action";
    
    $projectId = 'nhvhkhlpxuvnzhorhrnd';
    $apiKey = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6Im5odmhraGxweHV2bnpob3Jocm5kIiwicm9sZSI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc1MTAyMTQ0MywiZXhwIjoyMDY2NTk3NDQzfQ.g-8ClcEkhrykKAWDdEC3La22sJq4M5IzSpgZT9H4OJg';

    // Common headers
    $headers = [
        "apikey: $apiKey",
        "Authorization: Bearer $apiKey",
        "Content-Type: application/json",
        "Accept: application/json",
        "Prefer: return=representation"
    ];
    $langs = array('fr', 'it', 'de', 'es', 'pl');
    switch ($method) {
        case "app-health-check":
            echo json_encode(array("status" => "success", "message" => "Health check running"));
            break;

        // Admin streaks
        case "admin-store-notification":
            echo "test";exit;
            // Store notification

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://us-central1-riafy-public.cloudfunctions.net/genesis?otherFunctions=dexDirect&type=r10-apps-ftw',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>'{
                    "ask-dex": "qvj7MATCj1IIq4DoN0L0RCREve3qwDUyUadyRFx/hH0F8mPQZi8yJVTH+yA4HOno0aUXfvWBO+Pyyg5Ixt33Omtf2AwMJlwOrREfK9h2cFjOalyIIbmqtN54spxf3R9VaKe3RMhJL8/bp5EVIZaBBuEn3ftK5cc6OJCb7K2Y+BckhcuvbMvruUwTZoHIi0MVwmpB8FBFS6j+8uC75Rrxd9Ipy6Y0W5zIDRd2p/d6Y/CTuqnDUZQ5V0aDqZnkXduWoAVU63jBpPixOEd+vZZxW30c683hL+zpZtqv1THC+u84PNsAlpvfoflfE68JHPr3/tyvPytPF+UErgzRA/BDn0U/YcoKtDCLqhRgoJZCpDLUPNamROv6da97NMJD9Ox4RZrV8Jr2dTWJ9gtq81wUtZpFaglaEYAwOZMSZtdKwidVmkEAgakGohDERk9W3PS5bSDoO2Iwa6Hy5qaXEYMiYZkDW8ACGkpjmFlDu1nZjiv6a5Q1LbbRvYnCl/eRnhMFkowK7DirMB4Jvf9fcZzenVDjV5V4y5J4mVHvSIxHgW2yhsZ5R/tNkxLE9Uwit4t8bKQ2U5A5Z6yzMevNyrcJDqeTT9+f42QnxObYycxObRY0wOwK4JjmBArDdkwZZ9RvYHgeLA1Ey8iEKOyRP3jF9DBgvsYS8FTT8olYxwqFY+UJjdVNInahKg5mnc5xH7BTELZi2NXYiXNYjEqmj3qhfSvWbnu2UHVQPlPfwYHBNVCc2dsQXm93hp2uNs8qQ3Su3X8/6FEwCSkT85bnl1EAYqqCu4/O/6mPhTdzHyc9IKvpGFfNyGNBWNlf8C5P+p6FzX4BtDUujI4P8VLyOnuiE/zmFLca9NS3KueO3lLG01YXWEcQjsW2ei0adCzD46bSBnmtNGyDXiMC+whRmpqBNFYQRiq3mAi+UuUzXAnwbs4MDL4Q+Xgoh3QIHnOW/Fq2vyhtxujuBxM7DGhgJClN1W6RSPSCJxYZX2pfbqqjcmrTZS15HEfZmd/Wt8ghpNX1edA1b+G6DLHLnHpZMMb3ek73seK+JEd7XOsj2sZhKYw=",
                    "appname": "acd",
                    "ogQuery": "{\\n  \\"apps\\": [\\n    {\\n      \\"appid\\": \\"keto.weightloss.diet.plan\\",\\n      \\"app_name\\": \\"Keto Diet Tracker: Manage Carb\\"\\n    }\\n  ],\\n  \\"notification count\\": \\"4 for each types\\",\\n  \\"languages\\": \\"en,fr,it,de\\"\\n}",
                    "reply-mode": "json"
                }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));

            $response = curl_exec($curl);
            $resp = json_decode($response, true);
            echo json_encode($resp);
            // exit;

            curl_close($curl);
            foreach ($resp['data']["notifications"] as $appname => $notifications) {
                foreach ($notifications as $notification) {
                    foreach ($notification as $lang => $localisation) {
                        $notificationTableUrl = "https://$projectId.supabase.co/rest/v1/notifications";
                        $notificationData = array(
                            "title" => $localisation['title'],
                            "subtitle" => $localisation['subtitle'],
                            "type" => $localisation['type'],
                            "appname" => $appname,
                            "lang" => $lang
                        );
                        $storeNotification = storeNotificationData($notificationTableUrl, $headers, $notificationData);
                        // echo json_encode($storeNotification);exit;
                    }
                }
            }
        break;
        // // Admin streaks
        // case "admin-get-all-streaks":
        //     // Read all streaks admin
        //     $baseUrl = "https://$projectId.supabase.co/rest/v1/streaks";
        //     $allStreaksAdmin = getAllStreaksAdmin($baseUrl, $headers);
        //     echo json_encode($allStreaksAdmin);
        // break;
        // case "admin-insert-streak":
        //     // Insert streak admin
        //     $baseUrl = "https://$projectId.supabase.co/rest/v1/streaks";
        //     $exists = checkStreakExists($baseUrl, $headers, array('sku' => $inputPayload['sku']));
        //     if ($exists) {
        //         http_response_code(403);
        //         echo json_encode(array("status" => "error", "message" => "Streak already exist with the same sku"));
        //         exit;
        //     } else {
        //         $translatedArr = array();
        //         foreach($langs as $lang){
        //             $translatedArr[$lang]['name'] = translateText($inputPayload['name'], $lang);
        //             $translatedArr[$lang]['description'] = translateText($inputPayload['description'], $lang);
        //         }
        //         $payloadToInsert = $inputPayload;
        //         $payloadToInsert['localizations'] = json_encode($translatedArr);
        //         $new = insertStreakAdmin($baseUrl, $headers, $payloadToInsert);
        //         echo json_encode(array("status" => "success", "message" => "Streak inserted succcesfully", "response" => $new));
        //     }
        // break;
        // case "admin-update-streak":
        //     // Update streak admin
        //     $baseUrl = "https://$projectId.supabase.co/rest/v1/streaks";
        //     $id = $inputPayload['id'];
        //     $exists = checkStreakExists($baseUrl, $headers, array('sku' => $inputPayload['sku'], 'id' => ['not.eq' => $id]));
        //     if ($exists) {
        //         http_response_code(403);
        //         echo json_encode(array("status" => "error", "message" => "Another streak exist with the same sku"));
        //         exit;
        //     } else {
        //         $translatedArr = array();
        //         foreach($langs as $lang){
        //             $translatedArr[$lang]['name'] = translateText($inputPayload['name'], $lang);
        //             $translatedArr[$lang]['description'] = translateText($inputPayload['description'], $lang);
        //         }
        //         $payloadToInsert = $inputPayload;
        //         $payloadToInsert['localizations'] = json_encode($translatedArr);
        //         $new = updateStreakAdmin($baseUrl, $headers, $id, $payloadToInsert);
        //         echo json_encode(array("status" => "success", "message" => "Streak updated succcesfully", "response" => $new));
        //     }
        // break;
        // case "admin-delete-streak":
        //     // Delete streak admin
        //     $baseUrl = "https://$projectId.supabase.co/rest/v1/streaks";
        //     $id = $_GET['id'];
        //     $new = deleteStreakAdmin($baseUrl, $headers, $id);
        //     echo json_encode(array("status" => "success", "message" => "Streak deleted succesfully"));
        // break;

        // // Admin milestones
        // case "admin-get-all-milestones":
        //     // Read all milestones admin
        //     $baseUrl = "https://$projectId.supabase.co/rest/v1/milestones";
        //     $allMilestonesAdmin = getAllMilestonesAdmin($baseUrl, $headers);
        //     echo json_encode($allMilestonesAdmin);
        // break;
        // case "admin-insert-milestone":
        //     // Insert milestone admin
        //     $baseUrl = "https://$projectId.supabase.co/rest/v1/milestones";
        //     $exists = checkMilestoneExists($baseUrl, $headers, array('sku' => $inputPayload['sku'], 'streakSku' => $inputPayload['streakSku'], 'streakId' => $inputPayload['streakId']));
        //     if ($exists) {
        //         http_response_code(403);
        //         echo json_encode(array("status" => "error", "message" => "Milestone already exist with the same sku"));
        //         exit;
        //     } else {
        //         $translatedArr = array();
        //         foreach($langs as $lang){
        //             $translatedArr[$lang]['name'] = translateText($inputPayload['name'], $lang);
        //             $translatedArr[$lang]['description'] = translateText($inputPayload['description'], $lang);
        //         }
        //         $payloadToInsert = $inputPayload;
        //         $payloadToInsert['localizations'] = json_encode($translatedArr);
        //         $baseUrlStorage = "https://$projectId.supabase.co";
        //         $bucket = "firestorm";
        //         $imageUrl = uploadImageToStorage($baseUrlStorage, $apiKey, $bucket, $inputPayload['sku'], $inputPayloadFiles);
        //         $payloadToInsert['imageColoredUrl'] = $imageUrl;
        //         $new = insertMilestoneAdmin($baseUrl, $headers, $payloadToInsert);
        //         echo json_encode(array("status" => "success", "message" => "Milestone inserted succcesfully", "response" => $new));
        //     }
        // break;
        // case "admin-update-milestone":
        //     // Update milestone admin
        //     $baseUrl = "https://$projectId.supabase.co/rest/v1/milestones";
        //     $id = $inputPayload['id'];
        //     $exists = checkMilestoneExists($baseUrl, $headers, array('sku' => $inputPayload['sku'], 'streakSku' => $inputPayload['streakSku'], 'streakId' => $inputPayload['streakId'], 'id' => ['not.eq' => $id]));
        //     if ($exists) {
        //         http_response_code(403);
        //         echo json_encode(array("status" => "error", "message" => "Another milestone exist with the same sku"));
        //         exit;
        //     } else {
        //         $translatedArr = array();
        //         foreach($langs as $lang){
        //             $translatedArr[$lang]['name'] = translateText($inputPayload['name'], $lang);
        //             $translatedArr[$lang]['description'] = translateText($inputPayload['description'], $lang);
        //         }
        //         $payloadToInsert = $inputPayload;
        //         $payloadToInsert['localizations'] = json_encode($translatedArr);
        //         $baseUrlStorage = "https://$projectId.supabase.co";
        //         $bucket = "firestorm";
        //         $imageUrl = uploadImageToStorage($baseUrlStorage, $apiKey, $bucket, $inputPayload['sku'], $inputPayloadFiles);
        //         $payloadToInsert['imageColoredUrl'] = $imageUrl;
        //         $new = updateMilestoneAdmin($baseUrl, $headers, $id, $payloadToInsert);
        //         echo json_encode(array("status" => "success", "message" => "Milestone updated succcesfully", "response" => $new));
        //     }
        // break;
        // case "admin-delete-milestone":
        //     // Delete milestone admin
        //     $baseUrl = "https://$projectId.supabase.co/rest/v1/milestones";
        //     $id = $inputPayload['id'];
        //     $new = deleteMilestoneAdmin($baseUrl, $headers, $id);
        //     echo json_encode(array("status" => "success", "message" => "Milestone deleted succesfully"));
        // break;

        // APIs for app
        case "app-log-a-streak":
            // Log a streak
            $baseUrl = "https://$projectId.supabase.co/rest/v1/streakLog";
            $streakSku = '';
            if(isset($_GET['streakSku'])){
                $streakSku = $_GET['streakSku'];
            }
            else{
                $baseUrlMilestones = "https://$projectId.supabase.co/rest/v1/milestones";
                $milestonesOfApps = getStreakSkuOfAMilestone($baseUrlMilestones, $headers, array("appname" => $_GET['appname']));
                $streakSku = $milestonesOfApps[0]["streakSku"];
            }
            $payloadToInsert = array('appname' => $_GET['appname'], 'userId' => $_GET['userId'], 'streakSku' => $streakSku);
            $lang = $_GET['lang'];
            $baseUrlStreaks =  "https://$projectId.supabase.co/rest/v1/streaks";
            $getStreakData = getStreakData($baseUrlStreaks, $headers, array('sku' => $streakSku));
            if ($getStreakData) {
                if(isset($getStreakData[0]['streakType']) && $getStreakData[0]['streakType'] == 'daily'){
                    $existsTodayStreak = getStreakLogData($baseUrl, $headers, array('appname' => $_GET['appname'], 'userId' => $_GET['userId'], 'streakSku' => $streakSku));
                    if ($existsTodayStreak) {
                        http_response_code(403);
                        echo json_encode(array("status" => "error", "message" => "This streak is already marked today"));
                        exit;
                    }
                }
                elseif(isset($getStreakData[0]['streakType']) && $getStreakData[0]['streakType'] == 'weekly'){
                    // Use date parameter if provided, otherwise use current date
                    $checkDate = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
                    $existsThisWeekStreak = getStreakLogDataThisWeekMock($baseUrl, $headers, array('appname' => $_GET['appname'], 'userId' => $_GET['userId'], 'streakSku' => $streakSku), $checkDate);
                    if ($existsThisWeekStreak) {
                        http_response_code(403);
                        echo json_encode(array("status" => "error", "message" => "This streak is already marked this week"));
                        exit;
                    }
                }
                elseif(isset($getStreakData[0]['streakType']) && $getStreakData[0]['streakType'] == 'monthly'){
                    // Use date parameter if provided, otherwise use current date
                    $checkDate = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
                    $existsThisMonthStreak = getStreakLogDataThisMonthMock($baseUrl, $headers, array('appname' => $_GET['appname'], 'userId' => $_GET['userId'], 'streakSku' => $streakSku), $checkDate);
                    if ($existsThisMonthStreak) {
                        http_response_code(403);
                        echo json_encode(array("status" => "error", "message" => "This streak is already marked this month"));
                        exit;
                    }
                }
            }
            else{
                http_response_code(403);
                echo json_encode(array("status" => "error", "message" => "This streak not exist for this app"));
                exit;
            }
            // Determine streak type and check accordingly
            $streakType = $getStreakData[0]['streakType'] ?? 'daily';
            
            if ($streakType == 'daily') {
                $checkYesterdayStreakLogged = checkYesterdayStreakLogged($baseUrl, $headers, array('appname' => $_GET['appname'], 'userId' => $_GET['userId'], 'streakSku' => $streakSku));
                if ($checkYesterdayStreakLogged) {
                    $count = (int)$checkYesterdayStreakLogged[0]['count'] + 1;
                }
                else{
                    // Check pause logic for streak continuation
                    $baseUrlPaused = "https://$projectId.supabase.co/rest/v1/streakPause";
                    $pauseLogUrl = "https://$projectId.supabase.co/rest/v1/streakPauseLog";
                    $params = array(
                        'appname' => $_GET['appname'],
                        'userId' => $_GET['userId'],
                        'streakSku' => $streakSku
                    );
                    $count = getStreakCountWithPauseLogic($baseUrl, $baseUrlPaused, $pauseLogUrl, $headers, $params);
                }
            }
            elseif ($streakType == 'weekly') {
                // Auto-resume pause if logging a streak while paused
                $baseUrlPaused = "https://$projectId.supabase.co/rest/v1/streakPause";
                $pauseLogUrl = "https://$projectId.supabase.co/rest/v1/streakPauseLog";
                
                // Check if user is currently paused
                $pauseData = checkPauseExist($baseUrlPaused, $headers, array('appname' => $_GET['appname'], 'userId' => $_GET['userId']));
                if ($pauseData && !empty($pauseData) && $pauseData[0]['is_pause'] == "1") {
                    // User is paused, auto-resume
                    $pauseId = $pauseData[0]['id'];
                    $triggered_at = date('c');
                    
                    // Update streakPause table
                    updatePauseResumeStreak($baseUrlPaused, $headers, $pauseId, array("is_pause" => "0", "triggered_at" => $triggered_at));
                    
                    // Update streakPauseLog table
                    $latestPauseLog = getLatestPauseLogWithoutResume($pauseLogUrl, $headers, array('appname' => $_GET['appname'], 'userId' => $_GET['userId']));
                    if ($latestPauseLog) {
                        $pauseLogId = $latestPauseLog[0]['id'];
                        $updatePauseLogData = array(
                            "resumed_at" => $triggered_at
                        );
                        updatePauseLog($pauseLogUrl, $headers, $pauseLogId, $updatePauseLogData);
                    }
                }
                
                $checkLastWeekStreakLogged = checkLastWeekStreakLogged($baseUrl, $headers, array('appname' => $_GET['appname'], 'userId' => $_GET['userId'], 'streakSku' => $streakSku));
                if ($checkLastWeekStreakLogged) {
                    $count = (int)$checkLastWeekStreakLogged[0]['count'] + 1;
                }
                else{
                    // Check pause logic for weekly streak continuation
                    $params = array(
                        'appname' => $_GET['appname'],
                        'userId' => $_GET['userId'],
                        'streakSku' => $streakSku
                    );
                    $count = getWeeklyStreakCountWithPauseLogic($baseUrl, $baseUrlPaused, $pauseLogUrl, $headers, $params);
                }
            }
            elseif ($streakType == 'monthly') {
                // Auto-resume pause if logging a streak while paused
                $baseUrlPaused = "https://$projectId.supabase.co/rest/v1/streakPause";
                $pauseLogUrl = "https://$projectId.supabase.co/rest/v1/streakPauseLog";
                
                // Check if user is currently paused
                $pauseData = checkPauseExist($baseUrlPaused, $headers, array('appname' => $_GET['appname'], 'userId' => $_GET['userId']));
                if ($pauseData && !empty($pauseData) && $pauseData[0]['is_pause'] == "1") {
                    // User is paused, auto-resume
                    $pauseId = $pauseData[0]['id'];
                    $triggered_at = date('c');
                    
                    // Update streakPause table
                    updatePauseResumeStreak($baseUrlPaused, $headers, $pauseId, array("is_pause" => "0", "triggered_at" => $triggered_at));
                    
                    // Update streakPauseLog table
                    $latestPauseLog = getLatestPauseLogWithoutResume($pauseLogUrl, $headers, array('appname' => $_GET['appname'], 'userId' => $_GET['userId']));
                    if ($latestPauseLog) {
                        $pauseLogId = $latestPauseLog[0]['id'];
                        $updatePauseLogData = array(
                            "resumed_at" => $triggered_at
                        );
                        updatePauseLog($pauseLogUrl, $headers, $pauseLogId, $updatePauseLogData);
                    }
                }
                
                $checkLastMonthStreakLogged = checkLastMonthStreakLogged($baseUrl, $headers, array('appname' => $_GET['appname'], 'userId' => $_GET['userId'], 'streakSku' => $streakSku));
                if ($checkLastMonthStreakLogged) {
                    $count = (int)$checkLastMonthStreakLogged[0]['count'] + 1;
                }
                else{
                    // Check pause logic for monthly streak continuation
                    $params = array(
                        'appname' => $_GET['appname'],
                        'userId' => $_GET['userId'],
                        'streakSku' => $streakSku
                    );
                    $count = getMonthlyStreakCountWithPauseLogic($baseUrl, $baseUrlPaused, $pauseLogUrl, $headers, $params);
                }
            }
            // echo $count;exit;
            $payloadToInsert['count'] = $count;
            $new = logStreak($baseUrl, $headers, $payloadToInsert);

            // Store user data (same as app-mark-mock-streak)
            $userData = array(
                "fcmToken" => $_GET['fcmToken'] ?? '',
                "userId" => $_GET['userId'],
                "name" => $_GET['name'] ?? '',
                "appname" => $_GET['appname'],
                "lang" => $lang,
                "timezone" => $_GET['timezone'] ?? '',
                "platform" => $_GET['platform'] ?? 'android'
            );
            // store user data
            $userTable = "https://$projectId.supabase.co/rest/v1/users";
            $user = storeUserData($userTable, $headers, $userData);
            $baseUrlMilestones = "https://$projectId.supabase.co/rest/v1/milestones";
            $checkAnyMilestoneExist = checkAnyMilestoneExist($baseUrlMilestones, $headers, array('streakSku' => $streakSku, 'streakCount' => $count, 'appname' => $_GET['appname']));
            if ($checkAnyMilestoneExist) {
                $baseUrlUserMilestones = "https://$projectId.supabase.co/rest/v1/userMilestones";
                $checkAnyMilestoneExistIsAchieved = checkAnyMilestoneExistIsAchieved($baseUrlUserMilestones, $headers, array('milestoneSku' => $checkAnyMilestoneExist[0]['sku'], 'appname' => $_GET['appname'], "userId" => $_GET['userId']));
                if($checkAnyMilestoneExistIsAchieved == false){
                    $insertUserMileStonePayload = array(
                        "appname" => $_GET['appname'],
                        "userId" => $_GET['userId'],
                        "milestoneSku" => $checkAnyMilestoneExist[0]['sku'],
                        "milestoneId" => $checkAnyMilestoneExist[0]['id'],
                    );
                    $checkAnyMilestoneExistLocalisations = json_decode($checkAnyMilestoneExist[0]['localizations'], true);
                    if(isset($checkAnyMilestoneExistLocalisations[$lang])){
                        $checkAnyMilestoneExist[0]["name"] = $checkAnyMilestoneExistLocalisations[$lang]['name'];
                        $checkAnyMilestoneExist[0]["description"] = $checkAnyMilestoneExistLocalisations[$lang]['description'];
                    }
                    else{
                        $checkAnyMilestoneExist[0]["name"] = $checkAnyMilestoneExist[0]['name'];
                        $checkAnyMilestoneExist[0]["description"] = $checkAnyMilestoneExist[0]['description'];
                    }
                    $newMilestone = addNewUserMilestones($baseUrlUserMilestones, $headers, $insertUserMileStonePayload);
                    echo json_encode(array("status" => "success", "message" => "Streak logged succesfully", "milestone" => $checkAnyMilestoneExist[0]));
                }
                else{
                    echo json_encode(array("status" => "success", "message" => "Streak logged succesfully"));
                }
            }
            else{
                echo json_encode(array("status" => "success", "message" => "Streak logged succesfully"));
            }
        break;
        case "app-pause-streak":
            // Pause streak
            $baseUrl = "https://$projectId.supabase.co/rest/v1/streakPause";
            $pauseLogUrl = "https://$projectId.supabase.co/rest/v1/streakPauseLog";
            $checkPauseExist = checkPauseExist($baseUrl, $headers, array('appname' => $_GET['appname'], 'userId' => $_GET['userId']));
            if ($checkPauseExist) {
                $id = $checkPauseExist[0]['id'];
                if(isset($_GET['date'])){
                    $triggered_at = $_GET['date'];
                }
                else{
                    $triggered_at = date('c');
                }
                $new = updatePauseResumeStreak($baseUrl, $headers, $id, array("is_pause" => "1", "triggered_at" => $triggered_at));
                
                // Create new pause log entry
                $pauseLogData = array(
                    "appname" => $_GET['appname'],
                    "userId" => $_GET['userId'],
                    "paused_at" => $triggered_at
                );
                $pauseLog = pauseResumeStreakLog($pauseLogUrl, $headers, $pauseLogData);
                
                echo json_encode(array("status" => "success", "message" => "Streak paused succcesfully", "response" => $new));
            }
            else{
                // If not exist, create with is_pause = 1
                if(isset($_GET['date'])){
                    $payloadToInsert = array(
                        "appname" => $_GET['appname'],
                        "userId" => $_GET['userId'],
                        "is_pause" => "1",
                        "triggered_at" => $_GET['date']
                    );
                    $triggered_at = $_GET['date'];
                }
                else{
                    $payloadToInsert = array(
                        "appname" => $_GET['appname'],
                        "userId" => $_GET['userId'],
                        "is_pause" => "1",
                        "triggered_at" => date('c')
                    );
                    $triggered_at = date('c');
                }
                $new = pauseResumeStreak($baseUrl, $headers, $payloadToInsert);
                
                // Create new pause log entry
                $pauseLogData = array(
                    "appname" => $_GET['appname'],
                    "userId" => $_GET['userId'],
                    "paused_at" => $triggered_at
                );
                $pauseLog = pauseResumeStreakLog($pauseLogUrl, $headers, $pauseLogData);
                
                echo json_encode(array("status" => "success", "message" => "Streak paused succesfully", "response" => $new));
            }
        break;
        case "app-resume-streak":
            // Resume streak
            $baseUrl = "https://$projectId.supabase.co/rest/v1/streakPause";
            $pauseLogUrl = "https://$projectId.supabase.co/rest/v1/streakPauseLog";
            $checkPauseExist = checkPauseExist($baseUrl, $headers, array('appname' => $_GET['appname'], 'userId' => $_GET['userId']));
            if ($checkPauseExist) {
                $id = $checkPauseExist[0]['id'];
                if(isset($_GET['date'])){
                    $triggered_at = $_GET['date'];
                }
                else{
                    $triggered_at = date('c');
                }
                $new = updatePauseResumeStreak($baseUrl, $headers, $id, array("is_pause" => "0", "triggered_at" => $triggered_at));
                
                // Find the latest pause log entry (without resumed_at) and update it
                $latestPauseLog = getLatestPauseLogWithoutResume($pauseLogUrl, $headers, array('appname' => $_GET['appname'], 'userId' => $_GET['userId']));
                if ($latestPauseLog) {
                    $pauseLogId = $latestPauseLog[0]['id'];
                    $updatePauseLogData = array(
                        "resumed_at" => $triggered_at
                    );
                    $pauseLog = updatePauseLog($pauseLogUrl, $headers, $pauseLogId, $updatePauseLogData);
                }
                
                echo json_encode(array("status" => "success", "message" => "Streak resumed succesfully", "response" => $new));
            }
            else{
                echo json_encode(array("status" => "failed", "message" => "No paused streak found"));
            }
        break;
        case "app-get-all-streaks":
            // Read all milestones of app
            $baseUrlMilestones = "https://$projectId.supabase.co/rest/v1/milestones";
            $allMilestonesApp = getAllMilestonesApp($baseUrlMilestones, $headers, $_GET['appname']);
            
            if(isset($_GET['lang']) && $_GET['lang'] != "en"){
                $i = 0;
                foreach ($allMilestonesApp as $milestone) {
                    if (!empty($milestone['localizations'])) {
                        $localizations = json_decode($milestone['localizations'], true);
                        if (isset($localizations[$_GET['lang']])) {
                            $allMilestonesApp[$i]['name'] = $localizations[$_GET['lang']]['name'];
                            $allMilestonesApp[$i]['description'] = $localizations[$_GET['lang']]['description'];
                        }
                    }
                    $i++;
                }
            }
            usort($allMilestonesApp, function ($a, $b) {
                return (int)$a['streakCount'] - (int)$b['streakCount'];
            });
            $allMilestonesApp = array_map(function($milestone) {
                unset($milestone['localizations']);
                return $milestone;
            }, $allMilestonesApp);

            $baseUrlUserMilestones = "https://$projectId.supabase.co/rest/v1/userMilestones";
            $allAchievedMilestonesOfUser = getAllAchievedMilestonesOfUser($baseUrlUserMilestones, $headers, array('appname' => $_GET['appname'], 'userId' => $_GET['userId']));
            $completedIds = array_column($allAchievedMilestonesOfUser, 'milestoneId');
            $milestones = array_map(function ($item) use ($completedIds) {
                $item['isCompleted'] = in_array((string)$item['id'], $completedIds) ? "1" : "0";
                return $item;
            }, $allMilestonesApp);
            usort($milestones, fn($a, $b) => $b['isCompleted'] <=> $a['isCompleted']);

            // Read all streaks for an app
            
            $streaks = array();
            if(isset($milestones[0])){
                $streakId = $milestones[0]["streakId"];
                $baseUrlStreaks = "https://$projectId.supabase.co/rest/v1/streaks";
                $allStreaksApp = getAllStreaksApp($baseUrlStreaks, $headers, array('id' => $streakId));
                $baseUrlStreakLogs = "https://$projectId.supabase.co/rest/v1/streakLog";
                $allStreakLogsApp = getAllStreakLogsApp($baseUrlStreakLogs, $headers, array('appname' => $_GET['appname'], 'userId' => $_GET['userId'], 'order' => ['created_at' => 'desc']));
    
                $streak_marked = array_column($allStreakLogsApp, 'created_at');
                rsort($streak_marked);
    
                if(empty($allStreakLogsApp)){
                    $allStreaksApp[0]["longest_streak"] = 0;
                    $allStreaksApp[0]["current_streak"] = 0;
                    $allStreaksApp[0]["streak_marked"] = $streak_marked;
                }
                else{
                    $maxCount = max(array_map('intval', array_column($allStreakLogsApp, 'count')));
                    $allStreaksApp[0]["longest_streak"] = $maxCount;
                    $allStreaksApp[0]["current_streak"] = (int)$allStreakLogsApp[0]["count"];
                    $allStreaksApp[0]["streak_marked"] = $streak_marked;
                }

                if(isset($_GET['lang']) && $_GET['lang'] != "en"){
                    $j = 0;
                    if (!empty($allStreaksApp[0]['localizations'])) {
                        $localizations = json_decode($allStreaksApp[0]['localizations'], true);
                        if (isset($localizations[$_GET['lang']])) {
                            $allStreaksApp[0]['name'] = $localizations[$_GET['lang']]['name'];
                            $allStreaksApp[0]['description'] = $localizations[$_GET['lang']]['description'];
                        }
                    }
                }

                // $allStreaksApp[0]["restore_streak_saved"] = 0;

                $allStreaks = array_map(function($streak) {
                    unset($streak['localizations']);
                    return $streak;
                }, $allStreaksApp);

                $streaks = $allStreaks[0];
            }

            $userData = array(
                "fcmToken" => $_GET['fcmToken'],
                "userId" => $_GET['userId'],
                "name" => $_GET['name'],
                "appname" => $_GET['appname'],
                "lang" => $_GET['lang'],
                "timezone" => $_GET['timezone'] ?? '',
                "platform" => $_GET['platform'] ?? 'android'
            );
            // store user data
            $userTable = "https://$projectId.supabase.co/rest/v1/users";
            $user = storeUserData($userTable, $headers, $userData);
            
            // Get pause log data
            $pauseLogUrl = "https://$projectId.supabase.co/rest/v1/streakPauseLog";
            $pauseLogData = getAllStreakLogsApp($pauseLogUrl, $headers, array(
                'appname' => $_GET['appname'],
                'userId' => $_GET['userId'],
                'order' => ['created_at' => 'desc']
            ));
            // echo json_encode($pauseLogData);exit;
            
            // Extract all dates from pause log entries
            $pauseDates = array_reduce($pauseLogData, function($carry, $pauseEntry) {
                if (isset($pauseEntry['paused_at'])) {
                    $pausedAt = new DateTime($pauseEntry['paused_at']);
                    
                    if (isset($pauseEntry['resumed_at']) && $pauseEntry['resumed_at'] !== null) {
                        // Pause has been resumed - add all dates from paused_at to resumed_at (inclusive)
                        $resumedAt = new DateTime($pauseEntry['resumed_at']);
                        $currentDate = clone $pausedAt;
                        while ($currentDate <= $resumedAt) {
                            $carry[] = $currentDate->format('Y-m-d');
                            $currentDate->modify('+1 day');
                        }
                    } else {
                        // Pause is still active - add all dates from paused_at to today (inclusive)
                        $today = new DateTime();
                        $currentDate = clone $pausedAt;
                        while ($currentDate <= $today) {
                            $carry[] = $currentDate->format('Y-m-d');
                            $currentDate->modify('+1 day');
                        }
                    }
                }
                return $carry;
            }, array());
            
            // Remove dates from pause_marked that have streak logs (edge case: streak marked on resume date)
            if (!empty($pauseDates) && !empty($streak_marked)) {
                $streakMarkedDates = array_map(function($date) {
                    return date('Y-m-d', strtotime($date));
                }, $streak_marked);
                
                $pauseDates = array_filter($pauseDates, function($pauseDate) use ($streakMarkedDates) {
                    return !in_array($pauseDate, $streakMarkedDates);
                });
            }
            
            // Remove duplicate dates from pause_marked
            if (!empty($pauseDates)) {
                $pauseDates = array_unique($pauseDates);
                rsort($pauseDates); // Sort in descending order
            }
            
            // Add pause dates to existing streak_marked dates
            if (!empty($pauseDates)) {
                rsort($pauseDates); // Sort in descending order
                $streaks['pause_marked'] = $pauseDates;
            }
            else{
                $streaks['pause_marked'] = [];
            }
            
            // Check if user is currently paused
            $isCurrentlyPaused = 0;
            $pauseStartDate = null;
            if (!empty($pauseLogData)) {
                $latestPauseEntry = $pauseLogData[0]; // Most recent entry
                if (isset($latestPauseEntry['paused_at']) && !isset($latestPauseEntry['resumed_at'])) {
                    // User is currently paused (has paused_at but no resumed_at)
                    $isCurrentlyPaused = 1;
                    $pauseStartDate = $latestPauseEntry['paused_at'];
                }
            }
            
            echo json_encode(array(
                "streaks" => $streaks, 
                "milestones" => $milestones, 
                "restore_streak_saved" => 0, 
                "pauseLog" => $pauseLogData,
                "is_paused" => $isCurrentlyPaused,
                // "pause_start_date" => $pauseStartDate
            ));
            
            exit;





            // Read all streaks for an app
            $baseUrlStreaks = "https://$projectId.supabase.co/rest/v1/streaks";
            $allStreaksApp = getAllStreaksApp($baseUrlStreaks, $headers, array('appname' => $_GET['appname']));

            $baseUrlStreakLogs = "https://$projectId.supabase.co/rest/v1/streakLog";
            $allStreakLogsApp = getAllStreakLogsApp($baseUrlStreakLogs, $headers, array('appname' => $_GET['appname'], 'userId' => $_GET['userId']));

            $maxCounts = [];
            foreach ($allStreakLogsApp as $log) {
                $sku = $log['streakSku'];
                $count = (int)$log['count'];
                if (!isset($maxCounts[$sku]) || $count > $maxCounts[$sku]) {
                    $maxCounts[$sku] = $count;
                }
            }

            $allStreaks = array_map(function ($streak) use ($maxCounts) {
                $streak['longestStreak'] = $maxCounts[$streak['sku']] ?? 0;
                return $streak;
            }, $allStreaksApp);

            $allStreaks = array_map(function($streak) {
                unset($streak['localizations']);
                return $streak;
            }, $allStreaks);

            $baseUrlMilestones = "https://$projectId.supabase.co/rest/v1/milestones";
            $allMilestonesApp = getAllMilestonesApp($baseUrlMilestones, $headers, $_GET['appname']);
            if(isset($_GET['lang']) && $_GET['lang'] != "en"){
                $i = 0;
                foreach ($allMilestonesApp as $milestone) {
                    if (!empty($milestone['localizations'])) {
                        $localizations = json_decode($milestone['localizations'], true);
                        if (isset($localizations[$_GET['lang']])) {
                            $allMilestonesApp[$i]['name'] = $localizations[$_GET['lang']]['name'];
                            $allMilestonesApp[$i]['description'] = $localizations[$_GET['lang']]['description'];
                        }
                    }
                    $i++;
                }
            }
            $allMilestonesApp = array_map(function($milestone) {
                unset($milestone['localizations']);
                return $milestone;
            }, $allMilestonesApp);

            $baseUrlStreaks = "https://$projectId.supabase.co/rest/v1/userMilestones";
            $allAchievedMilestonesOfUser = getAllAchievedMilestonesOfUser($baseUrlStreaks, $headers, array('appname' => $_GET['appname'], 'userId' => $_GET['userId']));
            $completedIds = array_column($allAchievedMilestonesOfUser, 'milestoneId');
            $milestones = array_map(function ($item) use ($completedIds) {
                $item['isCompleted'] = in_array((string)$item['id'], $completedIds) ? "1" : "0";
                return $item;
            }, $allMilestonesApp);
            usort($milestones, fn($a, $b) => $b['isCompleted'] <=> $a['isCompleted']);

            echo json_encode(array("streaks" => $allStreaks, "milestones" => $milestones));
        break;
        case "app-clear-all-data":
            // Clear all streak data of user
            $baseUrlStreakLogs = "https://$projectId.supabase.co/rest/v1/streakLog";
            $deleteAllStreakLogsAppUser = deleteAllStreakLogsAppUser($baseUrlStreakLogs, $headers, array('appname' => $_GET['appname'], 'userId' => $_GET['userId']));
            $baseUrlUserMilestones = "https://$projectId.supabase.co/rest/v1/userMilestones";
            $deleteAllMilestonesAppUser = deleteAllMilestonesAppUser($baseUrlUserMilestones, $headers, array('appname' => $_GET['appname'], 'userId' => $_GET['userId']));
            $baseUrlUserStreakPause = "https://$projectId.supabase.co/rest/v1/streakPause";
            $deleteAllStreakPauseAppUser = deleteAllStreakPause($baseUrlUserStreakPause, $headers, array('appname' => $_GET['appname'], 'userId' => $_GET['userId']));
            $baseUrlUserStreakPauseLogs = "https://$projectId.supabase.co/rest/v1/streakPauseLog";
            $deleteAllStreakPauseLogsAppUser = deleteAllStreakPauseLogs($baseUrlUserStreakPauseLogs, $headers, array('appname' => $_GET['appname'], 'userId' => $_GET['userId']));
            echo json_encode(array("status" => "success", "message" => "Streak log and achieved milestones deleted succesfully", "response" => $deleteAllStreakLogsAppUser));
        break;
        case "app-mark-mock-streak":
            // Mark mock streak
            $baseUrl = "https://$projectId.supabase.co/rest/v1/streakLog";
            $streakSku = '';
            if(isset($_GET['streakSku'])){
                $streakSku = $_GET['streakSku'];
            }
            else{
                $baseUrlMilestones = "https://$projectId.supabase.co/rest/v1/milestones";
                $milestonesOfApps = getStreakSkuOfAMilestone($baseUrlMilestones, $headers, array("appname" => $_GET['appname']));
                $streakSku = $milestonesOfApps[0]["streakSku"];
            }
            $payloadToInsert = array('appname' => $_GET['appname'], 'userId' => $_GET['userId'], 'streakSku' => $streakSku);
            $lang = $_GET['lang'];
            $mock_date = $_GET['date'];
            $baseUrlStreaks =  "https://$projectId.supabase.co/rest/v1/streaks";
            $getStreakData = getStreakData($baseUrlStreaks, $headers, array('sku' => $streakSku));
            if ($getStreakData) {
                if(isset($getStreakData[0]['streakType']) && $getStreakData[0]['streakType'] == 'daily'){
                    $existsTodayStreak = getStreakLogDataMock($baseUrl, $headers, array('appname' => $_GET['appname'], 'userId' => $_GET['userId'], 'streakSku' => $streakSku), $mock_date);
                    if ($existsTodayStreak) {
                        http_response_code(403);
                        echo json_encode(array("status" => "error", "message" => "This streak is already marked today"));
                        exit;
                    }
                }
                elseif(isset($getStreakData[0]['streakType']) && $getStreakData[0]['streakType'] == 'weekly'){
                    $existsThisWeekStreak = getStreakLogDataThisWeekMock($baseUrl, $headers, array('appname' => $_GET['appname'], 'userId' => $_GET['userId'], 'streakSku' => $streakSku), $mock_date);
                    if ($existsThisWeekStreak) {
                        http_response_code(403);
                        echo json_encode(array("status" => "error", "message" => "This streak is already marked this week"));
                        exit;
                    }
                }
                elseif(isset($getStreakData[0]['streakType']) && $getStreakData[0]['streakType'] == 'monthly'){
                    $existsThisMonthStreak = getStreakLogDataThisMonthMock($baseUrl, $headers, array('appname' => $_GET['appname'], 'userId' => $_GET['userId'], 'streakSku' => $streakSku), $mock_date);
                    if ($existsThisMonthStreak) {
                        http_response_code(403);
                        echo json_encode(array("status" => "error", "message" => "This streak is already marked this month"));
                        exit;
                    }
                }
            }
            else{
                http_response_code(403);
                echo json_encode(array("status" => "error", "message" => "This streak not exist for this app"));
                exit;
            }
            // Determine streak type and check accordingly for mock data
            $streakType = $getStreakData[0]['streakType'] ?? 'daily';
            
            if ($streakType == 'daily') {
                $checkYesterdayStreakLogged = checkYesterdayStreakLoggedMock($baseUrl, $headers, array('appname' => $_GET['appname'], 'userId' => $_GET['userId'], 'streakSku' => $streakSku), $mock_date);
                if ($checkYesterdayStreakLogged) {
                    $count = (int)$checkYesterdayStreakLogged[0]['count'] + 1;
                }
                else{
                    // Check pause logic for streak continuation (for mock data, we need to get the last streak before the mock date)
                    $baseUrlPaused = "https://$projectId.supabase.co/rest/v1/streakPause";
                    $pauseLogUrl = "https://$projectId.supabase.co/rest/v1/streakPauseLog";
                    $params = array(
                        'appname' => $_GET['appname'],
                        'userId' => $_GET['userId'],
                        'streakSku' => $streakSku
                    );
                    
                    // Get the last streak log before the mock date
                    $lastStreakLog = getAllStreakLogsApp($baseUrl, $headers, array(
                        'appname' => $_GET['appname'], 
                        'userId' => $_GET['userId'], 
                        'streakSku' => $streakSku,
                        'order' => ['created_at' => 'desc'],
                        'limit' => 1
                    ));
                    
                    $count = getStreakCountWithPauseLogic($baseUrl, $baseUrlPaused, $pauseLogUrl, $headers, $params, $lastStreakLog);
                }
            }
            elseif ($streakType == 'weekly') {
                // Auto-resume pause if logging a streak while paused
                $baseUrlPaused = "https://$projectId.supabase.co/rest/v1/streakPause";
                $pauseLogUrl = "https://$projectId.supabase.co/rest/v1/streakPauseLog";
                
                // Check if user is currently paused
                $pauseData = checkPauseExist($baseUrlPaused, $headers, array('appname' => $_GET['appname'], 'userId' => $_GET['userId']));
                if ($pauseData && !empty($pauseData) && $pauseData[0]['is_pause'] == "1") {
                    // User is paused, auto-resume
                    $pauseId = $pauseData[0]['id'];
                    $triggered_at = $mock_date ? date('c', strtotime($mock_date)) : date('c');
                    
                    // Update streakPause table
                    updatePauseResumeStreak($baseUrlPaused, $headers, $pauseId, array("is_pause" => "0", "triggered_at" => $triggered_at));
                    
                    // Update streakPauseLog table
                    $latestPauseLog = getLatestPauseLogWithoutResume($pauseLogUrl, $headers, array('appname' => $_GET['appname'], 'userId' => $_GET['userId']));
                    if ($latestPauseLog) {
                        $pauseLogId = $latestPauseLog[0]['id'];
                        $updatePauseLogData = array(
                            "resumed_at" => $triggered_at
                        );
                        updatePauseLog($pauseLogUrl, $headers, $pauseLogId, $updatePauseLogData);
                    }
                }
                
                $checkLastWeekStreakLogged = checkLastWeekStreakLoggedMock($baseUrl, $headers, array('appname' => $_GET['appname'], 'userId' => $_GET['userId'], 'streakSku' => $streakSku), $mock_date);
                if ($checkLastWeekStreakLogged) {
                    $count = (int)$checkLastWeekStreakLogged[0]['count'] + 1;
                }
                else{
                    // Check pause logic for weekly streak continuation (for mock data)
                    $params = array(
                        'appname' => $_GET['appname'],
                        'userId' => $_GET['userId'],
                        'streakSku' => $streakSku
                    );
                    
                    // Get the last streak log before the mock date
                    $lastStreakLog = getAllStreakLogsApp($baseUrl, $headers, array(
                        'appname' => $_GET['appname'], 
                        'userId' => $_GET['userId'], 
                        'streakSku' => $streakSku,
                        'order' => ['created_at' => 'desc'],
                        'limit' => 1
                    ));
                    
                    $count = getWeeklyStreakCountWithPauseLogic($baseUrl, $baseUrlPaused, $pauseLogUrl, $headers, $params, $lastStreakLog);
                }
            }
            elseif ($streakType == 'monthly') {
                // Auto-resume pause if logging a streak while paused
                $baseUrlPaused = "https://$projectId.supabase.co/rest/v1/streakPause";
                $pauseLogUrl = "https://$projectId.supabase.co/rest/v1/streakPauseLog";
                
                // Check if user is currently paused
                $pauseData = checkPauseExist($baseUrlPaused, $headers, array('appname' => $_GET['appname'], 'userId' => $_GET['userId']));
                if ($pauseData && !empty($pauseData) && $pauseData[0]['is_pause'] == "1") {
                    // User is paused, auto-resume
                    $pauseId = $pauseData[0]['id'];
                    $triggered_at = $mock_date ? date('c', strtotime($mock_date)) : date('c');
                    
                    // Update streakPause table
                    updatePauseResumeStreak($baseUrlPaused, $headers, $pauseId, array("is_pause" => "0", "triggered_at" => $triggered_at));
                    
                    // Update streakPauseLog table
                    $latestPauseLog = getLatestPauseLogWithoutResume($pauseLogUrl, $headers, array('appname' => $_GET['appname'], 'userId' => $_GET['userId']));
                    if ($latestPauseLog) {
                        $pauseLogId = $latestPauseLog[0]['id'];
                        $updatePauseLogData = array(
                            "resumed_at" => $triggered_at
                        );
                        updatePauseLog($pauseLogUrl, $headers, $pauseLogId, $updatePauseLogData);
                    }
                }
                
                $checkLastMonthStreakLogged = checkLastMonthStreakLoggedMock($baseUrl, $headers, array('appname' => $_GET['appname'], 'userId' => $_GET['userId'], 'streakSku' => $streakSku), $mock_date);
                if ($checkLastMonthStreakLogged) {
                    $count = (int)$checkLastMonthStreakLogged[0]['count'] + 1;
                }
                else{
                    // Check pause logic for monthly streak continuation (for mock data)
                    $params = array(
                        'appname' => $_GET['appname'],
                        'userId' => $_GET['userId'],
                        'streakSku' => $streakSku
                    );
                    
                    // Get the last streak log before the mock date
                    $lastStreakLog = getAllStreakLogsApp($baseUrl, $headers, array(
                        'appname' => $_GET['appname'], 
                        'userId' => $_GET['userId'], 
                        'streakSku' => $streakSku,
                        'order' => ['created_at' => 'desc'],
                        'limit' => 1
                    ));
                    
                    $count = getMonthlyStreakCountWithPauseLogic($baseUrl, $baseUrlPaused, $pauseLogUrl, $headers, $params, $lastStreakLog);
                }
            }
            // echo $count;exit;
            $payloadToInsert['count'] = $count;
            $payloadToInsert['created_at'] = $mock_date;
            // echo json_encode($payloadToInsert);exit;
            $new = logStreak($baseUrl, $headers, $payloadToInsert);

            $userData = array(
                "fcmToken" => $_GET['fcmToken'],
                "userId" => $_GET['userId'],
                "name" => $_GET['name'],
                "appname" => $_GET['appname'],
                "lang" => $lang,
                "timezone" => $_GET['timezone'] ?? '',
                "platform" => $_GET['platform'] ?? 'android'
            );
            // store user data
            $userTable = "https://$projectId.supabase.co/rest/v1/users";
            $user = storeUserData($userTable, $headers, $userData);

            $baseUrlMilestones = "https://$projectId.supabase.co/rest/v1/milestones";
            $checkAnyMilestoneExist = checkAnyMilestoneExist($baseUrlMilestones, $headers, array('streakSku' => $streakSku, 'streakCount' => $count, 'appname' => $_GET['appname']));
            if ($checkAnyMilestoneExist) {
                $baseUrlUserMilestones = "https://$projectId.supabase.co/rest/v1/userMilestones";
                $checkAnyMilestoneExistIsAchieved = checkAnyMilestoneExistIsAchieved($baseUrlUserMilestones, $headers, array('milestoneSku' => $checkAnyMilestoneExist[0]['sku'], 'appname' => $_GET['appname'], "userId" => $_GET['userId']));
                if($checkAnyMilestoneExistIsAchieved == false){
                    $insertUserMileStonePayload = array(
                        "appname" => $_GET['appname'],
                        "userId" => $_GET['userId'],
                        "milestoneSku" => $checkAnyMilestoneExist[0]['sku'],
                        "milestoneId" => $checkAnyMilestoneExist[0]['id'],
                    );
                    $checkAnyMilestoneExistLocalisations = json_decode($checkAnyMilestoneExist[0]['localizations'], true);
                    if(isset($checkAnyMilestoneExistLocalisations[$lang])){
                        $checkAnyMilestoneExist[0]["name"] = $checkAnyMilestoneExistLocalisations[$lang]['name'];
                        $checkAnyMilestoneExist[0]["description"] = $checkAnyMilestoneExistLocalisations[$lang]['description'];
                    }
                    else{
                        $checkAnyMilestoneExist[0]["name"] = $checkAnyMilestoneExist[0]['name'];
                        $checkAnyMilestoneExist[0]["description"] = $checkAnyMilestoneExist[0]['description'];
                    }
                    $newMilestone = addNewUserMilestones($baseUrlUserMilestones, $headers, $insertUserMileStonePayload);
                    // echo json_encode(array("status" => "success", "message" => "Streak logged succesfully", "milestone" => $checkAnyMilestoneExist[0], "user" => $user));
                    echo json_encode(array("status" => "success", "message" => "Streak logged succesfully", "milestone" => $checkAnyMilestoneExist[0]));
                }
                else{
                    echo json_encode(array("status" => "success", "message" => "Streak logged succesfully"));
                }
            }
            else{
                echo json_encode(array("status" => "success", "message" => "Streak logged succesfully"));
            }
        break;
        case "admin-send-notification":
            // Get users who need streak notifications
            $appname = $_GET['appname'] ?? null; // Optional: filter by specific app
            $userTableUrl = "https://$projectId.supabase.co/rest/v1/users";
            $streakLogTableUrl = "https://$projectId.supabase.co/rest/v1/streakLog";
            $usersMissingStreakFunctionUrl = "https://$projectId.supabase.co/rest/v1/rpc/users_missing_streak";
            $baseUrlMilestones = "https://$projectId.supabase.co/rest/v1/milestones";
            $baseUrlUserMilestones = "https://$projectId.supabase.co/rest/v1/userMilestones";
            $usersEligibleForMilestoneTomorrowFunctionUrl = "https://$projectId.supabase.co/rest/v1/rpc/get_eligible_users_for_tomorrow_milestone";
            $notificationLogUrl = "https://$projectId.supabase.co/rest/v1/notificationLog";
            $notificationTableUrl = "https://$projectId.supabase.co/rest/v1/notifications";
            $today = $_GET['mock_date'] ?? date('Y-m-d');
            $usersToNotify = getUsersForStreakNotification($usersMissingStreakFunctionUrl, $userTableUrl, $streakLogTableUrl, $headers, $appname, $today);
            // echo json_encode($usersToNotify);exit;
            sendFCMMessage($usersToNotify, $streakLogTableUrl, $baseUrlMilestones, $baseUrlUserMilestones, $today, $headers, $tomorrow_milestone = false, $notificationLogUrl, $notificationTableUrl);

        break;
        case "admin-send-notification-tomorrow-milestone-eligible-users":
            // Get users who would be eligible for a milestone by logging tomorrow's streak
            $appname = $_GET['appname'] ?? null; // Optional: filter by specific app
            $userTableUrl = "https://$projectId.supabase.co/rest/v1/users";
            $streakLogTableUrl = "https://$projectId.supabase.co/rest/v1/streakLog";
            $usersMissingStreakFunctionUrl = "https://$projectId.supabase.co/rest/v1/rpc/users_missing_streak";
            $baseUrlMilestones = "https://$projectId.supabase.co/rest/v1/milestones";
            $baseUrlUserMilestones = "https://$projectId.supabase.co/rest/v1/userMilestones";
            $usersEligibleForMilestoneTomorrowFunctionUrl = "https://$projectId.supabase.co/rest/v1/rpc/get_eligible_users_for_tomorrow_milestone";
            $notificationLogUrl = "https://$projectId.supabase.co/rest/v1/notificationLog";
            $notificationTableUrl = "https://$projectId.supabase.co/rest/v1/notifications";
            $mock_date = $_GET['mock_date'] ?? null; // Optional: mock date for testing
            $milestoneEligibleUsers = getUsersEligibleForMilestoneTomorrow($usersEligibleForMilestoneTomorrowFunctionUrl, $userTableUrl, $streakLogTableUrl, $baseUrlMilestones, $baseUrlUserMilestones, $headers, $appname, $mock_date);
            // echo json_encode($milestoneEligibleUsers);exit;
            // $today = date('Y-m-d');
            sendFCMMessage($milestoneEligibleUsers, $streakLogTableUrl, $baseUrlMilestones, $baseUrlUserMilestones, $mock_date, $headers, $tomorrow_milestone = true, $notificationLogUrl, $notificationTableUrl);
        break;
        default:
            http_response_code(401);
            echo json_encode(array("status" => "error", "message" => "No method choosed"));
    }

    // Functions for admin streaks

    function getAllStreaksAdmin($url, $headers) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "$url?select=*");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $res = curl_exec($ch);
        curl_close($ch);
        return json_decode($res, true);
    }

    // 🟡 2. INSERT row (POST)
    function insertStreakAdmin($url, $headers, $data) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $res = curl_exec($ch);
        curl_close($ch);
        return json_decode($res, true);
    }

    // 🟠 3. UPDATE row (PATCH by id)
    function updateStreakAdmin($url, $headers, $id, $data) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "$url?id=eq.$id");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $res = curl_exec($ch);
        curl_close($ch);
        return json_decode($res, true);
    }

    // 🔴 4. DELETE row (by id)
    function deleteStreakAdmin($url, $headers, $id) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "$url?id=eq.$id");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }

    function checkStreakExists($url, $headers, $filters) {
        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $operator => $v) {
                    $queryParts[] = "$key=" . $operator . "." . urlencode($v);
                }
            } else {
                $queryParts[] = "$key=eq." . urlencode($value);
            }
        }
        $queryUrl = "$url?" . implode('&', $queryParts);
        $queryUrl = str_replace(" ", "%20", $queryUrl);
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $queryUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        curl_close($ch);
    
        $data = json_decode($response, true);
        return !empty($data) ? $data : false;
    }


    // Function for admin milestones

    function getAllMilestonesAdmin($url, $headers) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "$url?select=*");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $res = curl_exec($ch);
        curl_close($ch);
        return json_decode($res, true);
    }

    // 🟡 2. INSERT row (POST)
    function insertMilestoneAdmin($url, $headers, $data) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $res = curl_exec($ch);
        curl_close($ch);
        return json_decode($res, true);
    }

    // 🟠 3. UPDATE row (PATCH by id)
    function updateMilestoneAdmin($url, $headers, $id, $data) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "$url?id=eq.$id");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $res = curl_exec($ch);
        curl_close($ch);
        return json_decode($res, true);
    }

    // 🔴 4. DELETE row (by id)
    function deleteMilestoneAdmin($url, $headers, $id) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "$url?id=eq.$id");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }

    function checkMilestoneExists($url, $headers, $filters) {
        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $operator => $v) {
                    $queryParts[] = "$key=" . $operator . "." . urlencode($v);
                }
            } else {
                $queryParts[] = "$key=eq." . urlencode($value);
            }
        }
        $queryUrl = "$url?" . implode('&', $queryParts);
        $queryUrl = str_replace(" ", "%20", $queryUrl);
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $queryUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        curl_close($ch);
    
        $data = json_decode($response, true);
        return !empty($data) ? $data : false;
    }

    function translateText($q, $tl){
        $translatedtext = $q;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://us-central1-riafy-public.cloudfunctions.net/genesis?otherFunctions=dexDirect&type=r10-apps-ftw',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "query": "{\\"source_lang\\": \\"en\\", \\"target_lang\\": \\"' . $tl . '\\", \\"source_text\\": \\"' . $q . '\\"}",
                "appname": "translate"
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $responseDecoded = json_decode($response, true);
        if(isset($responseDecoded['data']['translated_text']) && $responseDecoded['data']['translated_text'] != ''){
            $translatedtext = $responseDecoded['data']['translated_text'];
        }
        return $translatedtext;
    }

    // Log streak
    function logStreak($url, $headers, $data) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $res = curl_exec($ch);
        curl_close($ch);
        return json_decode($res, true);
    }

    // Get next streak count
    function getNextStreakLogCount($url, $headers, $filters) {
        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $operator => $v) {
                    $queryParts[] = "$key=" . $operator . "." . urlencode($v);
                }
            } else {
                $queryParts[] = "$key=eq." . urlencode($value);
            }
        }
        $queryUrl = "$url?" . implode('&', $queryParts) . '&order=id.desc&limit=1';
        $queryUrl = str_replace(" ", "%20", $queryUrl);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $queryUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        curl_close($ch);
    
        $data = json_decode($response, true);
        return !empty($data) ? $data : false;
    }

    // Get next streak count
    function getStreakData($url, $headers, $filters) {
        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $operator => $v) {
                    $queryParts[] = "$key=" . $operator . "." . urlencode($v);
                }
            } else {
                $queryParts[] = "$key=eq." . urlencode($value);
            }
        }
        $queryUrl = "$url?" . implode('&', $queryParts);
        $queryUrl = str_replace(" ", "%20", $queryUrl);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $queryUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        curl_close($ch);
    
        $data = json_decode($response, true);
        return !empty($data) ? $data : false;
    }

    // Get next streak count
    function getStreakLogData($url, $headers, $filters) {
        $date = date('Y-m-d');
        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $operator => $v) {
                    $queryParts[] = "$key=" . $operator . "." . urlencode($v);
                }
            } else {
                $queryParts[] = "$key=eq." . urlencode($value);
            }
        }
        $queryUrl = "$url?" . implode('&', $queryParts) . "&created_at=gte.${date}T00:00:00&created_at=lt.${date}T23:59:59";
        $queryUrl = str_replace(" ", "%20", $queryUrl);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $queryUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        curl_close($ch);
    
        $data = json_decode($response, true);
        return !empty($data) ? $data : false;
    }

    // Get next streak count
    function checkYesterdayStreakLogged($url, $headers, $filters) {
        $date = date('Y-m-d', strtotime('-1 day'));
        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $operator => $v) {
                    $queryParts[] = "$key=" . $operator . "." . urlencode($v);
                }
            } else {
                $queryParts[] = "$key=eq." . urlencode($value);
            }
        }
        $queryUrl = "$url?" . implode('&', $queryParts) . "&created_at=gte.${date}T00:00:00&created_at=lt.${date}T23:59:59";
        $queryUrl = str_replace(" ", "%20", $queryUrl);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $queryUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        curl_close($ch);
    
        $data = json_decode($response, true);
        return !empty($data) ? $data : false;
    }

    // Get next streak count
    function checkAnyMilestoneExist($url, $headers, $filters) {
        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $operator => $v) {
                    $queryParts[] = "$key=" . $operator . "." . urlencode($v);
                }
            } else {
                $queryParts[] = "$key=eq." . urlencode($value);
            }
        }
        $queryUrl = "$url?" . implode('&', $queryParts);
        $queryUrl = str_replace(" ", "%20", $queryUrl);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $queryUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        curl_close($ch);
    
        $data = json_decode($response, true);
        return !empty($data) ? $data : false;
    }

    // Get next streak count
    function checkAnyMilestoneExistIsAchieved($url, $headers, $filters) {
        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $operator => $v) {
                    $queryParts[] = "$key=" . $operator . "." . urlencode($v);
                }
            } else {
                $queryParts[] = "$key=eq." . urlencode($value);
            }
        }
        $queryUrl = "$url?" . implode('&', $queryParts);
        $queryUrl = str_replace(" ", "%20", $queryUrl);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $queryUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        curl_close($ch);
    
        $data = json_decode($response, true);
        return !empty($data) ? $data : false;
    }

    // Insert new user milestone
    function addNewUserMilestones($url, $headers, $data) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $res = curl_exec($ch);
        curl_close($ch);
        return json_decode($res, true);
    }

    // Insert new user milestone
    function pauseResumeStreak($url, $headers, $data) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $res = curl_exec($ch);
        curl_close($ch);
        return json_decode($res, true);
    }

    function pauseResumeStreakLog($url, $headers, $data) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $res = curl_exec($ch);
        curl_close($ch);
        return json_decode($res, true);
    }

    // All streaks for an app
    function getAllStreaksApp($url, $headers, $filters) {
        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $operator => $v) {
                    $queryParts[] = "$key=" . $operator . "." . urlencode($v);
                }
            } else {
                $queryParts[] = "$key=eq." . urlencode($value);
            }
        }
        $queryUrl = "$url?" . implode('&', $queryParts);
        $queryUrl = str_replace(" ", "%20", $queryUrl);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $queryUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $res = curl_exec($ch);
        curl_close($ch);
        return json_decode($res, true);
    }

    // All milestones for an app
    function getAllMilestonesApp($url, $headers, $appname) {
        $queryUrl = "$url?appname=eq.$appname";
        $queryUrl = str_replace(" ", "%20", $queryUrl);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $queryUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $res = curl_exec($ch);
        curl_close($ch);
        return json_decode($res, true);
    }

    // All achieved milestones for user
    function getAllAchievedMilestonesOfUser($url, $headers, $filters) {
        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $operator => $v) {
                    $queryParts[] = "$key=" . $operator . "." . urlencode($v);
                }
            } else {
                $queryParts[] = "$key=eq." . urlencode($value);
            }
        }
        $queryUrl = "$url?" . implode('&', $queryParts);
        $queryUrl = str_replace(" ", "%20", $queryUrl);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $queryUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $res = curl_exec($ch);
        curl_close($ch);
        return json_decode($res, true);
    }

    // All streak logs for an app
    function getAllStreakLogsApp($url, $headers, $filters) {
        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $operator => $v) {
                    $queryParts[] = "$key=" . $operator . "." . urlencode($v);
                }
            } else {
                $queryParts[] = "$key=eq." . urlencode($value);
            }
        }
        $queryUrl = "$url?" . implode('&', $queryParts);
        $queryUrl = str_replace(" ", "%20", $queryUrl);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $queryUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $res = curl_exec($ch);
        curl_close($ch);
        return json_decode($res, true);
    }

    // Check pause exist for a user and app
    function checkPauseExist($url, $headers, $filters) {
        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $operator => $v) {
                    $queryParts[] = "$key=" . $operator . "." . urlencode($v);
                }
            } else {
                $queryParts[] = "$key=eq." . urlencode($value);
            }
        }
        $queryUrl = "$url?" . implode('&', $queryParts);
        $queryUrl = str_replace(" ", "%20", $queryUrl);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $queryUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        curl_close($ch);
    
        $data = json_decode($response, true);
        return !empty($data) ? $data : false;
    }

    // 🟠 3. UPDATE row (PATCH by id)
    function updatePauseResumeStreak($url, $headers, $id, $data) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "$url?id=eq.$id");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $res = curl_exec($ch);
        curl_close($ch);
        return json_decode($res, true);
    }

    function uploadImageToStorage($SUPABASE_URL, $SUPABASE_API_KEY, $BUCKET, $milestoneSku, $filesArray){
        $filePath = $filesArray['image']['tmp_name'];
        $fileName = basename($filesArray['image']['name']);
        $uploadPath = "uploads/" . $milestoneSku . "_" . time() . "-" . $fileName;
        $fileContents = file_get_contents($filePath);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "$SUPABASE_URL/storage/v1/object/$BUCKET/milestone-images/$uploadPath");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fileContents);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $SUPABASE_API_KEY",
            "Content-Type: application/octet-stream",
            "x-upsert: false" // Set to true if you want to overwrite existing files
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        if ($httpCode === 200 || $httpCode === 201) {
            return "$SUPABASE_URL/storage/v1/object/public/$BUCKET/milestone-images/$uploadPath";
        } else {
            return "";
        }
    }

    function logStreakWithPauseLogic($supabaseUrl, $headers, $userId, $appname, $streakSku) {
        // Step 1: Get latest streak log
        $logUrl = "$supabaseUrl/rest/v1/streak_log?userId=eq.$userId&appname=eq.$appname&streakSku=eq.$streakSku&order=created_at.desc&limit=1";
        $logRes = json_decode(httpGet($logUrl, $headers), true);
    
        $count = 1;
        $resume = true;
    
        if (!empty($logRes)) {
            $lastLogDate = new DateTime($logRes[0]['created_at']);
            $expectedNextDate = clone $lastLogDate;
            $expectedNextDate->modify('+1 day');
    
            // Step 2: Check if app is paused
            $pausedUrl = "$supabaseUrl/rest/v1/paused?userId=eq.$userId&appname=eq.$appname&order=created_at.desc&limit=1";
            $pausedRes = json_decode(httpGet($pausedUrl, $headers), true);
    
            if (!empty($pausedRes) && $pausedRes[0]['is_pause']) {
                $pauseDate = new DateTime($pausedRes[0]['created_at']);
    
                // Pause made AFTER next expected log → reset
                if ($pauseDate > $expectedNextDate) {
                    $resume = false;
                }
            }
    
            $count = $resume ? ((int)$logRes[0]['count'] + 1) : 1;
        }
    
        // Step 3: Log new streak
        $payload = json_encode([
            'userId' => $userId,
            'appname' => $appname,
            'streakSku' => $streakSku,
            'count' => $count,
            'created_at' => date('c')
        ]);
    
        $insertUrl = "$supabaseUrl/rest/v1/streak_log";
        $insertRes = httpPost($insertUrl, $headers, $payload);
    
        return $insertRes;
    }
    
    // Helper functions
    function httpGet($url, $headers) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
    
    function httpPost($url, $headers, $payload) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    function checkIfPaused($baseUrl, $headers, $params) {
        $appname = urlencode($params['appname']);
        $userId = urlencode($params['userId']);
    
        $url = "$baseUrl?appname=eq.$appname&userId=eq.$userId&select=is_pause,triggered_at";
    
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
        ]);
    
        $response = curl_exec($ch);
        curl_close($ch);
    
        return json_decode($response, true);
    }

    function getUpdatedStreakCount($baseUrl, $baseUrlPaused, $headers, $params) {
        $appname = $params['appname'];
        $userId = $params['userId'];
        $streakSku = $params['streakSku'];

        $lastStreakLog = streakLastLoggedAt($baseUrl, $headers, $params);

        if (!$lastStreakLog || empty($lastStreakLog[0]['created_at'])) {
            return 1;
        }
    
        $lastLoggedDate = new DateTime($lastStreakLog[0]['created_at']);
        $currentCount = (int)$lastStreakLog[0]['count'];
        $pauseData = checkIfPaused($baseUrlPaused, $headers, $params);
        $isPaused = isset($pauseData[0]['is_pause']) && $pauseData[0]['is_pause'] == 1;
        $pausedAt = isset($pauseData[0]['triggered_at']) ? new DateTime($pauseData[0]['triggered_at']) : null;
    
        $today = new DateTime();
        $yesterday = (clone $lastLoggedDate)->modify('+1 day');

        $missedDays = $lastLoggedDate->diff($yesterday)->days;

        if ($isPaused) {
            echo "1<br>";
            if ($pausedAt) {
                echo "2<br>";
                if ($pausedAt->format('Y-m-d') === $lastLoggedDate->format('Y-m-d')) {
                    echo "3<br>";
                    return $currentCount + 1;
                } elseif ($pausedAt <= $yesterday) {
                    echo $pausedAt->format('Y-m-d') . '<= ' . $yesterday->format('Y-m-d');
                    echo "<br>4<br>";
                    return 1;
                } else {
                    echo "5<br>";
                    return $currentCount + 1;
                }
            }
        }
        return $missedDays;
        echo $missedDays;exit;
        return ($missedDays < 1) ? $currentCount + 1 : 1;
    }
    
    function streakLastLoggedAt($baseUrl, $headers, $params) {
        $appname = urlencode($params['appname']);
        $userId = urlencode($params['userId']);
        $streakSku = urlencode($params['streakSku']);
    
        $url = "$baseUrl?appname=eq.$appname&userId=eq.$userId&streakSku=eq.$streakSku&order=created_at.desc&limit=1&select=count,created_at";
    
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
        ]);
    
        $response = curl_exec($ch);
        curl_close($ch);
    
        return json_decode($response, true);
    }

    // Get next streak count
    function getStreakSkuOfAMilestone($url, $headers, $filters) {
        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $operator => $v) {
                    $queryParts[] = "$key=" . $operator . "." . urlencode($v);
                }
            } else {
                $queryParts[] = "$key=eq." . urlencode($value);
            }
        }
        $queryUrl = "$url?" . implode('&', $queryParts);
        $queryUrl = str_replace(" ", "%20", $queryUrl);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $queryUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        curl_close($ch);
    
        $data = json_decode($response, true);
        return !empty($data) ? $data : false;
    }

    // Delete all user streaks
    function deleteAllStreakLogsAppUser($url, $headers, $filters){
        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $operator => $v) {
                    $queryParts[] = "$key=" . $operator . "." . urlencode($v);
                }
            } else {
                $queryParts[] = "$key=eq." . urlencode($value);
            }
        }
        $queryUrl = "$url?" . implode('&', $queryParts);
        $queryUrl = str_replace(" ", "%20", $queryUrl);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $queryUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }

    // Get next streak count mock
    function getStreakLogDataMock($url, $headers, $filters, $mock_date) {
        $date = date('Y-m-d', strtotime($mock_date));
        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $operator => $v) {
                    $queryParts[] = "$key=" . $operator . "." . urlencode($v);
                }
            } else {
                $queryParts[] = "$key=eq." . urlencode($value);
            }
        }
        $queryUrl = "$url?" . implode('&', $queryParts) . "&created_at=gte.${date}T00:00:00&created_at=lt.${date}T23:59:59";
        $queryUrl = str_replace(" ", "%20", $queryUrl);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $queryUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        curl_close($ch);
    
        $data = json_decode($response, true);
        return !empty($data) ? $data : false;
    }

    // Get next streak count
    function checkYesterdayStreakLoggedMock($url, $headers, $filters, $mock_date) {
        $date = date('Y-m-d', strtotime($mock_date . ' -1 day'));
        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $operator => $v) {
                    $queryParts[] = "$key=" . $operator . "." . urlencode($v);
                }
            } else {
                $queryParts[] = "$key=eq." . urlencode($value);
            }
        }
        $queryUrl = "$url?" . implode('&', $queryParts) . "&created_at=gte.${date}T00:00:00&created_at=lt.${date}T23:59:59";
        $queryUrl = str_replace(" ", "%20", $queryUrl);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $queryUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        curl_close($ch);
    
        $data = json_decode($response, true);
        return !empty($data) ? $data : false;
    }

    // Delete all user milestones
    function deleteAllMilestonesAppUser($url, $headers, $filters){
        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $operator => $v) {
                    $queryParts[] = "$key=" . $operator . "." . urlencode($v);
                }
            } else {
                $queryParts[] = "$key=eq." . urlencode($value);
            }
        }
        $queryUrl = "$url?" . implode('&', $queryParts);
        $queryUrl = str_replace(" ", "%20", $queryUrl);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $queryUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }

    // Delete all streak pause
    function deleteAllStreakPause($url, $headers, $filters){
        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $operator => $v) {
                    $queryParts[] = "$key=" . $operator . "." . urlencode($v);
                }
            } else {
                $queryParts[] = "$key=eq." . urlencode($value);
            }
        }
        $queryUrl = "$url?" . implode('&', $queryParts);
        $queryUrl = str_replace(" ", "%20", $queryUrl);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $queryUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }

    // Delete all streak pause logs
    function deleteAllStreakPauseLogs($url, $headers, $filters){
        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $operator => $v) {
                    $queryParts[] = "$key=" . $operator . "." . urlencode($v);
                }
            } else {
                $queryParts[] = "$key=eq." . urlencode($value);
            }
        }
        $queryUrl = "$url?" . implode('&', $queryParts);
        $queryUrl = str_replace(" ", "%20", $queryUrl);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $queryUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }

    // Store user data
    function storeUserData($url, $headers, $data) {
        // Add Prefer header for upsert
        $headers[] = "Prefer: resolution=merge-duplicates,return=representation";
        // Add on_conflict param for upsert using composite key (userId,appname)
        if (strpos($url, '?') === false) {
            $url .= '?on_conflict=userId,appname';
        } else {
            $url .= '&on_conflict=userId,appname';
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $res = curl_exec($ch);
        curl_close($ch);
        return json_decode($res, true);
    }

    // Scenario 1: Check if pause was activated at the right time to continue streak (original logic)
    function checkPauseActivationForStreak_Scenario1($baseUrlPaused, $pauseLogUrl, $headers, $params, $lastStreakDate = null) {
        $appname = $params['appname'];
        $userId = $params['userId'];
        
        // If no last streak date provided, get it from the database
        if (!$lastStreakDate) {
            // Use the existing $baseUrl from the calling context
            global $baseUrl;
            $lastStreakLog = getAllStreakLogsApp($baseUrl, $headers, array(
                'appname' => $appname, 
                'userId' => $userId, 
                'streakSku' => $params['streakSku'],
                'order' => ['created_at' => 'desc'],
                'limit' => 1
            ));
            
            if (empty($lastStreakLog)) {
                return false; // No previous streak found
            }
            
            $lastStreakDate = $lastStreakLog[0]['created_at'];
        }
        
        // Convert last streak date to DateTime object
        $lastStreakDateTime = new DateTime($lastStreakDate);
        
        // Calculate the expected next streak date (next day after last streak)
        $expectedNextStreakDate = clone $lastStreakDateTime;
        $expectedNextStreakDate->modify('+1 day');
        
        // Get the latest pause log entry
        $latestPauseLog = getAllStreakLogsApp($pauseLogUrl, $headers, array(
            'appname' => $appname,
            'userId' => $userId,
            'order' => ['created_at' => 'desc'],
            'limit' => 1
        ));
        
        if (empty($latestPauseLog)) {
            return false; // No pause log entries found
        }
        
        $pauseLogEntry = $latestPauseLog[0];
        $pausedAt = new DateTime($pauseLogEntry['paused_at']);
        $pausedDate = $pausedAt->format('Y-m-d');
        $lastStreakDateOnly = $lastStreakDateTime->format('Y-m-d');
        $expectedNextStreakDateOnly = $expectedNextStreakDate->format('Y-m-d');
        
        // Check if pause was activated on the same day as last streak OR the very next day
        if ($pausedDate === $lastStreakDateOnly || $pausedDate === $expectedNextStreakDateOnly) {
            // Check if this pause was already resumed
            if (isset($pauseLogEntry['resumed_at'])) {
                $resumedAt = new DateTime($pauseLogEntry['resumed_at']);
                $resumedDate = $resumedAt->format('Y-m-d');
                
                // Get current logging date (either from GET parameter or current date)
                $currentLogDate = isset($_GET['date']) ? date('Y-m-d', strtotime($_GET['date'])) : date('Y-m-d');
                
                // Streak can only continue if logging on the same day as resume
                if ($resumedDate === $currentLogDate) {
                    return true; // Continue streak
                } else {
                    return false; // Reset streak - missed the resume day
                }
            } else {
                // Auto-resume the pause
                $pauseLogId = $pauseLogEntry['id'];
                $triggered_at = isset($_GET['date']) ? $_GET['date'] : date('c');
                $updatePauseLogData = array(
                    "resumed_at" => $triggered_at
                );
                updatePauseLog($pauseLogUrl, $headers, $pauseLogId, $updatePauseLogData);
                
                // Also update the streakPause table
                $pauseData = checkPauseExist($baseUrlPaused, $headers, array('appname' => $appname, 'userId' => $userId));
                if ($pauseData && !empty($pauseData)) {
                    $id = $pauseData[0]['id'];
                    updatePauseResumeStreak($baseUrlPaused, $headers, $id, array("is_pause" => "0", "triggered_at" => $triggered_at));
                }
                
                return true; // Continue streak - logging on resume day
            }
        }
        
        return false;
    }

    // Scenario 2: Check if pause was activated and last streak was logged after pause (new logic)
    function checkPauseActivationForStreak_Scenario2($baseUrlPaused, $headers, $params, $lastStreakDate = null) {
        $appname = $params['appname'];
        $userId = $params['userId'];
        
        // Get pause data
        $pauseData = checkPauseExist($baseUrlPaused, $headers, array('appname' => $appname, 'userId' => $userId));
        
        if (!$pauseData || empty($pauseData)) {
            return false; // No pause record found
        }
        
        $pauseRecord = $pauseData[0];
        $isPaused = ($pauseRecord['is_pause'] == "1" || $pauseRecord['is_pause'] == 1);
        $pauseTriggeredAt = $pauseRecord['triggered_at'];
        
        if (!$isPaused) {
            return false; // Not currently paused
        }
        
        // If no last streak date provided, get it from the database
        if (!$lastStreakDate) {
            // Use the existing $baseUrl from the calling context
            global $baseUrl;
            $lastStreakLog = getAllStreakLogsApp($baseUrl, $headers, array(
                'appname' => $appname, 
                'userId' => $userId, 
                'streakSku' => $params['streakSku'],
                'order' => ['created_at' => 'desc'],
                'limit' => 1
            ));
            
            if (empty($lastStreakLog)) {
                return false; // No previous streak found
            }
            
            $lastStreakDate = $lastStreakLog[0]['created_at'];
        }
        
        // Convert dates to DateTime objects
        $lastStreakDateTime = new DateTime($lastStreakDate);
        $pauseTriggeredDateTime = new DateTime($pauseTriggeredAt);
        
        // Check if the last streak was logged after the pause was activated
        if ($lastStreakDateTime > $pauseTriggeredDateTime) {
            // Last streak was logged after pause was activated, so pause is still protecting the streak
            return true;
        }
        
        // Calculate the expected next streak date (next day after last streak)
        $expectedNextStreakDate = clone $lastStreakDateTime;
        $expectedNextStreakDate->modify('+1 day');
        
        // Check if pause was activated on the same day as last streak or the very next day
        $pauseTriggeredDate = $pauseTriggeredDateTime->format('Y-m-d');
        $lastStreakDateOnly = $lastStreakDateTime->format('Y-m-d');
        $expectedNextStreakDateOnly = $expectedNextStreakDate->format('Y-m-d');
        
        // Pause is valid if activated on the same day as last streak OR the very next day
        return ($pauseTriggeredDate === $lastStreakDateOnly || $pauseTriggeredDate === $expectedNextStreakDateOnly);
    }

    // Get the appropriate streak count considering pause logic
    function getStreakCountWithPauseLogic($baseUrl, $baseUrlPaused, $pauseLogUrl, $headers, $params, $lastStreakLog = null) {
        $appname = $params['appname'];
        $userId = $params['userId'];
        $streakSku = $params['streakSku'];
        
        // If no last streak log provided, get it
        if (!$lastStreakLog) {
            $lastStreakLog = getAllStreakLogsApp($baseUrl, $headers, array(
                'appname' => $appname, 
                'userId' => $userId, 
                'streakSku' => $streakSku,
                'order' => ['created_at' => 'desc'],
                'limit' => 1
            ));
        }
        
        if (empty($lastStreakLog)) {
            return 1; // First streak
        }
        
        $lastStreakData = $lastStreakLog[0];
        $lastStreakDate = $lastStreakData['created_at'];
        $lastStreakCount = (int)$lastStreakData['count'];
        
        // Check if pause was activated properly - SCENARIO 1 (Original Logic)
        $pauseActivatedProperly = checkPauseActivationForStreak_Scenario1($baseUrlPaused, $pauseLogUrl, $headers, $params, $lastStreakDate);
        
        // Check if pause was activated properly - SCENARIO 2 (New Logic) - COMMENTED OUT
        // $pauseActivatedProperly = checkPauseActivationForStreak_Scenario2($baseUrlPaused, $headers, $params, $lastStreakDate);
        
        if ($pauseActivatedProperly) {
            // Continue streak: last count + 1
            return $lastStreakCount + 1;
        } else {
            // Reset streak: count = 1 (either no pause record or pause not activated properly)
            return 1;
        }
    }

    function generateAccessToken($serviceAccountPath) {
        $serviceAccount = json_decode(file_get_contents($serviceAccountPath), true);
        $header = ['alg' => 'RS256', 'typ' => 'JWT'];
        $issuedAt = time();
        $expirationTime = $issuedAt + 3600;
        $claims = [
            'iss' => $serviceAccount['client_email'],
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud' => 'https://oauth2.googleapis.com/token',
            'iat' => $issuedAt,
            'exp' => $expirationTime,
        ];
        $base64UrlHeader = base64UrlEncode(json_encode($header));
        $base64UrlClaims = base64UrlEncode(json_encode($claims));
        $unsignedJWT = $base64UrlHeader . '.' . $base64UrlClaims;
        $privateKey = $serviceAccount['private_key'];
        openssl_sign($unsignedJWT, $signature, $privateKey, 'SHA256');
        $base64UrlSignature = base64UrlEncode($signature);
        $signedJWT = $unsignedJWT . '.' . $base64UrlSignature;
        $response = getOAuthToken($signedJWT);
        return $response['access_token'];
    }

    function getOAuthToken($signedJWT) {
        $postFields = [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $signedJWT
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://oauth2.googleapis.com/token');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postFields));
        $result = curl_exec($ch);
        curl_close($ch);
        if ($result === false) {
            throw new \Exception('Failed to obtain OAuth 2.0 token');
        }
        return json_decode($result, true);
    }

    function base64UrlEncode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    function sendFCMMessage($users, $streakLogTableUrl, $baseUrlMilestones, $baseUrlUserMilestones, $today, $headers, $tomorrow_milestone, $notificationLogUrl, $notificationTableUrl) {
        echo json_encode($users);
        exit;
        // echo $today;


        $video_series_1_full_apps = array("african.braids.hairstyle", "com.rstream.beautyvideos", "beauty.skin.care.app", "com.rstream.booksummaries", "com.rstream.calmingmusic", "draw.cartoon.characters", "com.recipes.cookingvideos", "com.rstream.crafts", "com.rstream.crockpotrecipes", "com.rstream.dailywellness", "com.rstream.beautycare", "learn.drawing.tattoos", "easyworkout.workoutforwomen.homegym.beginnerexercise", "com.makeup.eye", "glowing.skin.face.yoga", "girls.pedicure.manicure", "com.rstream.exercisevideos", "com.rstream.haircare", "com.rstream.hairstyles", "short.hairstyles.steps", "rstream.scarf.hijabs", "home.diy.idea", "home.diy.ideas", "ketorecipes.vegetarian", "com.rstream.ketorecipes", "com.rstream.kidsvideos", "com.rstream.kidscrafts", "com.kids.learndrawing", "com.rstream.learndrawing", "learn.instruments.free", "learn.languages.free", "learn.magic.tricks", "com.rstream.piano", "com.rstream.lifehacks", "com.rstream.kidssongs", "draw.glow.mandalas", "guided.meditation.for.work", "com.rstream.mindfulness", "com.rstream.nailart", "com.rstream.nailartdesigns", "com.riatech.beautyvideos", "outfit.planner.ideas.fashion", "rstream.diy.papercrafts", "com.rstream.travel", "com.rstream.yogatraining");
        $video_series_2_full_apps = array("home.abs.workout.six.pack", "aerobics.workout.weightloss", "easy.airfryer.recipes", "learn.all.anime.drawing", "manga.comics.music.toon", "manga.anime.toon", "arm.workout.biceps.exercise", "asmr.videos.slicing.cutting.relaxing", "baby.led.weaning.cookbook", "bead.apps.beading.patterns", "leg.workout.buttocks.exercise", "tasty.cake.recipe.book", "dance.workouts.cardio.aerobic", "christmas.decorations.diy.decorating.decor", "christmas.food.recipes.cookies", "cocktails.mixed.drinks", "coffee.recipes.brew.hot.iced", "comics.toon.superheros.daily", "cottagecore.theme.farm.home", "dance.weightloss.workout", "dance.weight.loss.workout", "dessert.recipes.app.offline", "detox.app.diet.recipes", "dog.training.trainer.tricks", "draw.animals.stepbystep.tutorial", "draw.anime.girl.ideas", "dumbbell.home.workout", "embroidery.design.app.tutorial", "fitness.app.women.female.workout", "food.drawing.tutorial.stepbystep", "funny.animal.videos", "kitty.funny.cat.videos", "watch.memes.funny.videos", "gameplay.guides.reviews.tips", "live.stream.games.esports", "general.knowledge.education.quiz", "healthy.recipes.mealplans", "height.increase.home.exercise", "hiit.timers.workouts", "hiit.timer.workouts.women", "diy.crafts.free", "jewellery.maker.making.tutorial", "jump.rope.training", "kegel.exercises.trainer", "kegel.women.exercises.trainer", "kegel.trainer.exercise", "speak.learn.korean.apps", "easy.lazy.workout.bed.home", "learn.dance.move.step", "learn.draw.princess.step", "learn.drums.beginners", "learn.english.speaking.today", "speak.learn.french.apps", "learning.guitar.chords", "learn.japanese.language.speak", "learn.knitting.Crochet.step", "speak.spanish.learning.apps", "learn.swimming.lessons.app.learning", "love.learn.sex.app", "low.carb.weightloss.plan", "makeup.app.artist.tips.tutorial", "comics.manga.reviews.toon", "anxiety.relief.meditation", "app.guided.meditation.focus", "meditate.relax.sleep", "men.hairstyle.haircut", "muscle.booster.body.building.home.workout", "muscle.booster.workout.home.gym.abs", "nature.sounds.video.relax", "book.summaries.read.novel", "oddly.satisfying.videos.relax", "fitness.workout.plank.challenge.day30", "vegan.meal.planner.plants", "car.racing.videos.bike", "raw.food.diet.recipes", "diy.recycled.craft.ideas", "salad.recipes.weightloss", "summaries.self.help.books", "self.care.help.improvement", "minute7.workout.challenge", "healthy.smoothie.recipes.for.weight", "songs.music.videos.stream", "home.strength.training", "learn.ukulele.beginners", "weapons.drawing.tutorial.stepbystep", "relaxandsleep.sleepsounds.whitenoise", "workout.for.women.female.fitness", "yoga.weightloss.workout", "beginners.weight.loss.workout.women.yoga", "fit.zumba.dance.weightloss");
        $cooking_series_full_apps = array("calorie.calculator.counter.lose.weight", "air.fryer.oven.recipes", "alkaline.diet.recipes.weightLoss.ph", "all.free.recipes.cook", "com.riatech.americanRecipesNew", "com.riatech.arabicRecipesNew", "recipes.for.babies.food", "com.riatech.barbecueRecipesNew", "bodybuilding.diet.plan", "calorie.counter.to.lose.weight", "canning.preserving.recipes", "com.riatech.casserolerecipes", "com.riatech.chineseRecipesNew", "com.riatech.cocktailRecipesNew", "com.riatech.americanrecipes", "com.riatech.arabicrecipes", "com.riatech.brazilianrecipes", "com.riatech.breakfastrecipes", "com.riatech.cakerecipes", "com.riatech.chickenfree", "com.riatech.chineserecipes", "com.riatech.cocktailrecipes", "com.riatech.cookbook", "com.riatech.cookbookfrenchrecipes", "com.riatech.crockpotrecipes", "com.riatech.cubanrecipes", "com.riatech.dessertrecipes", "com.riatech.diabeticrecipes", "com.riatech.dietrecipes", "com.riatech.dinnerrecipes", "com.riatech.easyrecipes", "com.riatech.fitberry", "com.riatech.germanrecipes", "com.riatech.glutenfree", "com.riatech.grillrecipes", "com.riatech.indianrecipes", "com.riatech.Italianrecipes", "com.riatech.japaneserecipes", "com.riatech.koreanrecipes", "com.riatech.mexicanrecipes", "com.riatech.pakistanirecipes", "com.riatech.pizzarecipes", "com.riatech.portugueserecipes", "com.riatech.ricerecipes", "com.riatech.russianrecipes", "com.riatech.salads", "com.riatech.souprecipes", "com.riatech.spanishrecipe", "com.riatech.thairecipes", "com.riatech.vegetarianrecipes", "com.riatech.weightlossrecipes", "dash.diet.meal.plan", "diabetes.apps.sugar.tracker.log", "com.riatech.dinnerRecipesNew", "drink.cocktail.bar.recipes", "riatech.cocktails.drinks", "quick.easyrecipes.mealplan", "easy.chickenrecipes.free", "easy.recipes.beginners", "easy.sandwich.recipes.bread", "diet.fertility.ovulation.pregnancy", "fit.recipes.healthy.food", "free.cooking.allrecipes", "my.fridge.ingredient.recipe.generator", "com.riatech.germanRecipesNew", "grill.sauce.recipes", "gut.health.app.diet.recipes", "healthy.food.recipes", "healthy.recipebook.lunch", "com.riatech.indianRecipesNew", "com.riatech.japaneseRecipesNew", "diet.breakfast.ketorecipes", "keto.weightloss.diet.plan", "slim.keto.diet.plan", "keto.vegetarian.diet.plan", "cookbook.recipes.for.kids", "com.riatech.koreanRecipesNew", "low.carb.recipes.diet", "recipes.low.fat.diet", "low.budget.recipes.app", "meal.planner.calorie.counter", "mediterranean.diet.weightloss", "mediterranean.diet.recipes", "metabolism.booster.diet", "com.riatech.mexicanRecipesNew", "fit.mom.losing.weight.pregnancy", "offline.tasty.recipe.pasta", "oven_recipes.cook.big", "paleo.diet.app", "plant.based.meal.recipes", "com.riatech.pork", "com.riatech.portugueseRecipesNew", "pregnancy.health.tips.nutrition.dietplan", "recipe.keeper.book.organizer", "recipes.chocolate.maker", "seafood.recipes.tasty.shrimp", "slow.cooker.recipes.app", "slow.cooker.recipes", "com.riatech.tastyfeed", "tasty.asian.recipes", "tasty.egg.recipes.offline", "com.riatech.thaiRecipesNew", "vegan.recipes.diet.plan", "com.riatech.veganrecipes", "weightloss.women.diet.lose_weight", "easy.cooking.recipes");
        $cooking_series_full_apps_no_en = array("air.fryer.oven.recipes", "all.free.recipes.cook", "canning.preserving.recipes", "com.riatech.Italianrecipes", "com.riatech.americanRecipesNew", "com.riatech.arabicrecipes", "com.riatech.barbecueRecipesNew", "com.riatech.brazilianrecipes", "com.riatech.breakfastrecipes", "com.riatech.casserolerecipes", "com.riatech.chickenfree", "com.riatech.chineseRecipesNew", "com.riatech.cocktailRecipesNew", "com.riatech.cookbookfrenchrecipes", "com.riatech.crockpotrecipes", "com.riatech.cubanrecipes", "com.riatech.dessertrecipes", "com.riatech.diabeticrecipes", "com.riatech.dietrecipes", "com.riatech.dinnerrecipes", "com.riatech.easyrecipes", "com.riatech.germanRecipesNew", "com.riatech.glutenfree", "com.riatech.indianRecipesNew", "com.riatech.mexicanRecipesNew", "com.riatech.pakistanirecipes", "com.riatech.pizzarecipes", "com.riatech.portugueseRecipesNew", "com.riatech.ricerecipes", "com.riatech.russianrecipes", "com.riatech.salads", "com.riatech.souprecipes", "com.riatech.spanishrecipe", "com.riatech.thaiRecipesNew", "com.riatech.veganrecipes", "com.riatech.vegetarianrecipes", "com.riatech.weightlossrecipes", "cookbook.recipes.for.kids", "diet.breakfast.ketorecipes", "drink.cocktail.bar.recipes", "easy.chickenrecipes.free", "easy.cooking.recipes", "easy.recipes.beginners", "easy.sandwich.recipes.bread", "fit.recipes.healthy.food", "grill.sauce.recipes", "healthy.recipebook.lunch", "healthy.smoothie.recipes", "low.budget.recipes.app", "my.fridge.ingredient.recipe.generator", "oven_recipes.cook.big", "recipe.keeper.book.organizer", "recipes.for.babies.food", "riatech.cocktails.drinks", "seafood.recipes.tasty.shrimp", "slim.keto.diet.plan", "slow.cooker.recipes", "tasty.asian.recipes", "tasty.egg.recipes.offline");
        $walking_series_full_apps = array("cycling.tracker.weightloss", "jogging.workout.weightloss", "running.workout.weightloss", "walking.workout.weightloss");
        $workout_series_full_apps = array("do.split.workout.stretching", "face.yoga.glowing.skin", "fatloss.fatburningworkout.burnfat", "home.workouts.noequipment", "homeworkout.homeworkouts.day30", "jump.rope.workout.counter", "loseweight.loseweightapp.weightlossworkout", "menworkout.workouts.men", "yoga.workout.weightloss");
        $quit_smoking_series_full_apps = array("academy.learn.piano");
        $ios_shell_1_full_apps = array("homeworkout.homeworkouts.day30", "com.rstream.booksummaries", "com.riatech.breakfastrecipes", "com.riatech.cocktailRecipesNew", "Riafy.CookBook", "diy.crafts.ideas.projects", "com.riatech.crockpotrecipes", "dash.diet.meal.plan", "com.riatech.diabeticrecipes", "glowing.skin.face.yoga", "com.riatech.fitberry", "learning.guitar.chords", "com.riatech.hiitapp", "com.riatech.japaneseRecipesNew", "jogging.workout.weightloss", "jump.rope.workout.counter", "keto.weightloss.diet.plan", "com.rstream.ketorecipes", "learn.all.anime.drawing", "learn.dance.move.step", "learn.drums.beginners", "low.carb.recipes.diet", "com.rstream.mindfulness", "com.rstream.piano", "running.workout.weightloss", "com.riatech.salads", "com.riatech.souprecipes", "stretching.exercises.for.flexibility", "walking.workout.weightloss", "com.rstream.yogatraining");
        $ios_shell_2_full_apps = array("dance.weightloss.workout", "air.fryer.oven.recipes", "com.riatech.americanRecipesNew", "com.rstream.beautyvideos", "com.riatech.chickenfree", "cycling.tracker.weightloss", "daily.motivational.quotes.free", "com.riatech.dessertrecipes", "calorie.counter.to.lose.weight", "com.riatech.easyrecipes", "com.makeup.eyes", "weightloss.zero.fastingtracker.ios", "com.rstream.hairstyles", "com.riatech.Italianrecipes", "com.rstream.learndrawing", "learn.magic.tricks", "rstream.diy.papercrafts", "learn.ukulele.beginners", "paleo.diet.app", "girls.pedicure.manicure", "plant.based.meal.recipes", "com.rstream.readoutloud", "cookbook.recipes.for.kids", "com.rstream.codescanner", "beauty.skin.care.app", "com.riatech.vegetarianrecipes", "manga.anime.toon", "weightloss.women.diet.lose-weight", "menworkout.workouts.men", "loseweight.loseweightapp.weightlosswork");
        $ios_shell_3_full_apps = array("animedrawing.tutorial.stepbystep", "com.riatech.cakerecipes", "cocktails.mixed.drinks", "coffee.recipes.brew.hot.iced", "daily.selfcare.affirmations", "dessert.recipes.app.offline", "draw.animals.stepbystep.tutorial", "draw.anime.girl.ideas", "draw.cartoon.characters", "draw.glow.mandalas", "learn.draw.princess.step", "learn.drawing.tattoos", "easy.airfryer.recipes", "fitness.app.women.female.workout", "food.drawing.tutorial.stepbystep", "app.guided.meditation.focus", "com.rstream.kidscrafts", "learn.knitting.Crochet.step", "meditate.relax.sleep", "meditation.sleep.anxiety", "mediterranean.diet.weightloss", "men.hairstyle.haircut", "muscle.booster.workout.home.gym.abs", "com.rstream.pedometer", "healthy.smoothie.recipes.for.weight", "com.rstream.kidssongs", "vegan.meal.planner.plants", "baby.led.weaning.cookbook", "weapons.drawing.tutorial.stepbystep", "yoga.weightloss.workout");
        $ios_shell_4_full_apps = array("read.books.audio.summary", "minute7.workout.challenge", "alkaline.diet.recipes.weightloss.ph", "arm.workout.biceps.exercise", "blood.pressure.tracker.bp.monitor", "bmi.calculator.weight.tracker", "leg.workout.buttocks.exercise", "dance.weight.loss.workout", "diabetic.log.book.blood.sugar.tracker", "dumbbell.home.workout", "metabolism.booster.diet", "diet.fertility.ovulation.pregnancy", "height.increase.home.exercise", "hiit.timer.workouts.women", "jewellery.maker.making.tutorial", "kegel.exercises.trainer", "kegel.trainer.exercise", "kegel.women.exercises.trainer", "love.learn.sex.app", "mirror.camera.app", "muscle.booster.body.building.home.workout", "drink.cocktail.bar.recipes", "fit.mom.losing.weight.pregnancy", "pregnancy.periods.tracker.ovulation", "fitness.workout.plank.challenge.day30", "com.rstream.pomodoro", "pregnancy.health.tips.nutrition.dietplan", "summaries.self.help.books", "home.abs.workout.six.pack", "home.strength.training");
        $ios_shell_5_full_apps = array("aerobics.workout.weightloss", "african.braids.hairstyle", "asmr.videos.slicing.cutting.relaxing", "bead.apps.beading.patterns", "com.rstream.beautycare", "calorie.calculator.counter.lose.weight", "christmas.decorations.diy.decorating.decor", "christmas.food.recipes.cookies", "cottagecore.theme.farm.home", "daily.facts.app.fun.know", "diabetes.apps.sugar.tracker.log", "diabetes.tracker.food.diabetic", "dog.training.trainer.tricks", "embroidery.design.app.tutorial", "general.knowledge.education.quiz", "girls.color.games.book.coloring", "jigsaw.games.puzzles", "keto.vegetarian.diet.plan", "learn.swimming.lessons.app.learning", "recipes.low.fat.diet", "macro.calculator.food.tracker", "makeup.app.artist.tips.tutorial", "nature.sounds.video.relax", "oddly.satisfying.videos.relax", "plant.identifier.app.gardening", "raw.food.diet.recipes", "recipe.keeper.book.organizer", "vocabulary.builder.learn.trainer", "my.fridge.ingredient.recipe.generator", "easy.cooking.recipes");
        $ios_shell_6_full_apps = array("com.rstream.beautycare", "gut.health.app.diet.recipes", "math.solver.learner.learner.maths");
        $simi_full_apps = array("com.example.analyticspoc", "family.photo.album.sharing", "math.solver.scanner.solution", "poster.maker.design.flyer.invitation", "sleep.cycle.timer.tracker", "sleep.tracker.timer.cycle");
        $sarath_full_apps = array("cycling.distance.tracker.apps", "jogging.distance.tracker.apps", "com.rstream.pedometer", "running.weightloss.tracker.app", "walking.tracker.app.pedometer");
        $sarath_2_full_apps = array("plant.identifier.app.gardening", "new.year.resolution.wallpaper", "wildlife.wallpapers.backgrounds.animal");
        $sarath_3_full_apps = array("blood.pressure.tracker.bp.monitor");
        $sarath_4_full_apps = array("read.books.audio.summary");

        $authTokenContentApps1 = generateAccessToken(__DIR__ . '/service-accounts/content-apps-firebase-adminsdk-x2f49-e284d4f61e_9zkYCj9.json');
        $authTokenContentApps2 = generateAccessToken(__DIR__ . '/service-accounts/content-apps-2-firebase-adminsdk-m3fox-fd4c5cb469_bAdPKe0.json');
        $authTokenCooking = generateAccessToken(__DIR__ . '/service-accounts/cookbook-now-145-firebase-adminsdk-6hpb8-7b02f60c98_0rl14nT.json');
        $authTokenDailyQuotes = generateAccessToken(__DIR__ . '/service-accounts/daily-quotes-1a4a9-firebase-adminsdk-7ty0l-fc3888c361_r2uOLfN.json');
        $authTokenWorkout = generateAccessToken(__DIR__ . '/service-accounts/workout-ed0ae-firebase-adminsdk-ut76u-fd94a13f10_hmAOSvM.json');
        $authTokenQuitSmoking = generateAccessToken(__DIR__ . '/service-accounts/fir-d9861-firebase-adminsdk-nkaxk-8d888b6d50_ztiKEFY.json');
        $authTokenSuperShell1 = generateAccessToken(__DIR__ . '/service-accounts/riafy-apps-firebase-adminsdk-yl4wx-c696f42101_LUvUfqx.json');
        $authTokenSuperShell2 = generateAccessToken(__DIR__ . '/service-accounts/super-shell-2-firebase-adminsdk-tl52j-86c8225e0f_VPefZwF.json');
        $authTokenSuperShell3 = generateAccessToken(__DIR__ . '/service-accounts/super-shell-3-firebase-adminsdk-g691q-4409d411cc_4iPFJ0C.json');
        $authTokenSuperShell4 = generateAccessToken(__DIR__ . '/service-accounts/super-shell-4-firebase-adminsdk-zu530-3bc1908823_w81Q4tX.json');
        $authTokenSuperShell5 = generateAccessToken(__DIR__ . '/service-accounts/super-shell-5-firebase-adminsdk-llnye-e75819993e_aIaTsVj.json');
        $authTokenSuperShell6 = generateAccessToken(__DIR__ . '/service-accounts/super-shell-6-firebase-adminsdk-6yvwz-04da96b590_d4LrOyk.json');
        $authTokenSimiShell = generateAccessToken(__DIR__ . '/service-accounts/simi-shell-firebase-adminsdk-7oobc-215579245e_oPiVw3V.json');
        $authTokenSarath1 = generateAccessToken(__DIR__ . '/service-accounts/walking-tracker-pedometer-firebase-adminsdk-ggkr6-6cdbc64f58_V0GGKhZ.json');
        $authTokenSarath2 = generateAccessToken(__DIR__ . '/service-accounts/test-7e604-firebase-adminsdk-ewkbe-9462a4883c_HQKBQ6J.json');
        $authTokenSarath3 = generateAccessToken(__DIR__ . '/service-accounts/health-tracker-series-firebase-adminsdk-drw2m-2b863b4260_zXq480W.json');
        $authTokenSarath4 = generateAccessToken(__DIR__ . '/service-accounts/read-book-series-firebase-adminsdk-nym1x-3d8079bb72_JfRqbzP.json');
        
        $milestone_name = '';
        $streak_count = '';
        foreach ($users as $user) {
            if($tomorrow_milestone == true){
                // $appid = 'low.carb.recipes.diet';
                $appid = $user['appname'];
                $fcmToken = $user['fcmToken'];
                $type = 'milestone_upcoming';
                $lang = $user['lang'] ?? 'en';
                
                // Get notification from table
                $notificationData = getNotificationFromTable($notificationTableUrl, $headers, $user['appname'], $lang, $type);
                if ($notificationData) {
                    $firstName = explode(' ', trim($user['name']))[0];
                    $title = str_replace(['%user_name%', '%milestone_name%', '%streak_count%'], [$firstName, $user['milestone']['name'], ''], $notificationData['title']);
                    $subtitle = str_replace(['%user_name%', '%milestone_name%', '%streak_count%'], [$firstName, $user['milestone']['name'], ''], $notificationData['subtitle']);
                } else {
                    // Fallback to hardcoded values
                    $title = "The Final Page Beckons! 🪶";
                    $firstName = explode(' ', trim($user['name']))[0];
                    $subtitle = str_replace(['%user_name%', '%milestone_name%', '%streak_count%'], [$firstName, $user['milestone']['name'], ''], "%user_name%, the %milestone_name% milestone is just around the corner! Get ready to unlock your reward. 🗝️");
                }
            }
            else{
                // echo $user['appname'] . '<br>';
                $milestonesOfApps = getStreakSkuOfAMilestone($baseUrlMilestones, $headers, array("appname" => $user['appname']));
                $streakSku = $milestonesOfApps[0]["streakSku"];
                // $appid = 'low.carb.recipes.diet';
                $appid = $user['appname'];
                $fcmToken = $user['fcmToken'];
                $lang = $user['lang'] ?? 'en';
                
                $checkYesterdayStreakLogged = checkYesterdayStreakLoggedNotification($streakLogTableUrl, $headers, array('appname' => $user['appname'], 'userId' => $user['userId'], 'streakSku' => $streakSku), $today);
                if ($checkYesterdayStreakLogged) {
                    $count = (int)$checkYesterdayStreakLogged[0]['count'] + 1;
                    $checkAnyMilestoneExist = checkAnyMilestoneExist($baseUrlMilestones, $headers, array('streakSku' => $streakSku, 'streakCount' => $count, 'appname' => $user['appname']));
                    if ($checkAnyMilestoneExist) {
                        $checkAnyMilestoneExistIsAchieved = checkAnyMilestoneExistIsAchieved($baseUrlUserMilestones, $headers, array('milestoneSku' => $checkAnyMilestoneExist[0]['sku'], 'appname' => $user['appname'], "userId" => $user['userId']));
                        if($checkAnyMilestoneExistIsAchieved == false){
                            // Milestone break notification
                            $type = 'milestone_break';
                            $notificationData = getNotificationFromTable($notificationTableUrl, $headers, $user['appname'], $lang, $type);
                            if ($notificationData) {
                                $firstName = explode(' ', trim($user['name']))[0];
                                $title = str_replace(['%user_name%', '%milestone_name%', '%streak_count%'], [$firstName, $checkAnyMilestoneExist[0]['name'], $count], $notificationData['title']);
                                $subtitle = str_replace(['%user_name%', '%milestone_name%', '%streak_count%'], [$firstName, $checkAnyMilestoneExist[0]['name'], $count], $notificationData['subtitle']);
                            } else {
                                // Fallback to hardcoded values
                                $title = "Knowledge Milestone Approaching! 🚀";
                                $firstName = explode(' ', trim($user['name']))[0];
                                $subtitle = str_replace(['%user_name%', '%milestone_name%', '%streak_count%'], [$firstName, $checkAnyMilestoneExist[0]['name'], $count], "%user_name%, %milestone_name% summaries await! One last push to unlock your next reading milestone 💪");
                            }
                        }
                        else{
                            // Streak break notification
                            $type = 'streak_break';
                            $notificationData = getNotificationFromTable($notificationTableUrl, $headers, $user['appname'], $lang, $type);
                            if ($notificationData) {
                                $firstName = explode(' ', trim($user['name']))[0];
                                $title = str_replace(['%user_name%', '%milestone_name%', '%streak_count%'], [$firstName, '', $count], $notificationData['title']);
                                $subtitle = str_replace(['%user_name%', '%milestone_name%', '%streak_count%'], [$firstName, '', $count], $notificationData['subtitle']);
                            } else {
                                // Fallback to hardcoded values
                                $title = "Your streak have been ended";
                                $firstName = explode(' ', trim($user['name']))[0];
                                $subtitle = str_replace(['%user_name%', '%milestone_name%', '%streak_count%'], [$firstName, '', $count], "%user_name%, your mind craves those 15 minutes! Don't break your %streak_count%-day reading habit now 🧠");
                            }
                        }
                    }
                    else{
                        // Streak break notification
                        $type = 'streak_break';
                        $notificationData = getNotificationFromTable($notificationTableUrl, $headers, $user['appname'], $lang, $type);
                        if ($notificationData) {
                            $firstName = explode(' ', trim($user['name']))[0];
                            $title = str_replace(['%user_name%', '%milestone_name%', '%streak_count%'], [$firstName, '', $count], $notificationData['title']);
                            $subtitle = str_replace(['%user_name%', '%milestone_name%', '%streak_count%'], [$firstName, '', $count], $notificationData['subtitle']);
                        } else {
                            // Fallback to hardcoded values
                            $title = "Your streak have been ended";
                            $firstName = explode(' ', trim($user['name']))[0];
                            $subtitle = str_replace(['%user_name%', '%milestone_name%', '%streak_count%'], [$firstName, '', $count], "%user_name%, your mind craves those 15 minutes! Don't break your %streak_count%-day reading habit now 🧠");
                        }
                    }
                }
                else{
                    // Streak break notification
                    $type = 'streak_break';
                    $notificationData = getNotificationFromTable($notificationTableUrl, $headers, $user['appname'], $lang, $type);
                    if ($notificationData) {
                        $firstName = explode(' ', trim($user['name']))[0];
                        $title = str_replace(['%user_name%', '%milestone_name%', '%streak_count%'], [$firstName, '', 1], $notificationData['title']);
                        $subtitle = str_replace(['%user_name%', '%milestone_name%', '%streak_count%'], [$firstName, '', 1], $notificationData['subtitle']);
                    } else {
                        // Fallback to hardcoded values
                        $title = "Your streak have been ended";
                        $subtitle = "Your streak have been ended";
                    }
                }
            }

            if (in_array($appid, $video_series_1_full_apps)) {
                $projectId = 'content-apps';
                $authToken = $authTokenContentApps1;
            }
                         if (in_array($appid, $video_series_2_full_apps)) {
                 $projectId = 'content-apps-2';
                 $authToken = $authTokenContentApps2;
             }
                         if (in_array($appid, $cooking_series_full_apps)) {
                 $projectId = 'cookbook-now-145';
                 $authToken = $authTokenCooking;
             }
                         if (in_array($appid, $walking_series_full_apps)) {
                 $projectId = 'daily-quotes-1a4a9';
                 $authToken = $authTokenDailyQuotes;
             }
            if (in_array($appid, $workout_series_full_apps)) {
                $projectId = 'workout-ed0ae';
                $authToken = $authTokenWorkout;
            }
            if (in_array($appid, $quit_smoking_series_full_apps)) {
                $projectId = 'fir-d9861';
                $authToken = $authTokenQuitSmoking;
            }
            if (in_array($appid, $simi_full_apps)) {
                $projectId = 'simi-shell';
                $authToken = $authTokenSimiShell;
            }
            if (in_array($appid, $sarath_full_apps)) {
                $projectId = 'walking-tracker-pedometer';
                $authToken = $authTokenSarath1;
            }
            if (in_array($appid, $sarath_2_full_apps)) {
                $projectId = 'test-7e604';
                $authToken = $authTokenSarath2;
            }
            if (in_array($appid, $sarath_3_full_apps)) {
                $projectId = 'health-tracker-series';
                $authToken = $authTokenSarath3;
            }
            if (in_array($appid, $sarath_4_full_apps)) {
                $projectId = 'read-book-series';
                $authToken = $authTokenSarath4;
            }
    
            if (in_array($appid, $ios_shell_1_full_apps)) {
                $projectIdIos = 'riafy-apps';
                $authTokenIos = $authTokenSuperShell1;
            }
            if (in_array($appid, $ios_shell_2_full_apps)) {
                $projectIdIos = 'super-shell-2';
                $authTokenIos = $authTokenSuperShell2;
            }
            if (in_array($appid, $ios_shell_3_full_apps)) {
                $projectIdIos = 'super-shell-3';
                $authTokenIos = $authTokenSuperShell3;
            }
            if (in_array($appid, $ios_shell_4_full_apps)) {
                $projectIdIos = 'super-shell-4';
                $authTokenIos = $authTokenSuperShell4;
            }
            if (in_array($appid, $ios_shell_5_full_apps)) {
                $projectIdIos = 'super-shell-5';
                $authTokenIos = $authTokenSuperShell5;
            }
            if (in_array($appid, $ios_shell_6_full_apps)) {
                $projectIdIos = 'super-shell-6';
                $authTokenIos = $authTokenSuperShell6;
            }

            if($user['platform'] == 'ios'){
                $authToken = $authTokenIos;
                $projectId = $projectIdIos;
            }
            
            $notification = array(
                "title" => $title,
                "body" => $subtitle,
            );
    
            $field = array(
                "message" => array(
                    'notification' => $notification,
                    "android" => [
                        "priority" => "high"
                    ],
                    "token" => $fcmToken,
                    "apns" => [
                        "headers" => [
                            "apns-priority" => "10",
                            "apns-push-type" => "alert"
                        ],
                        "payload" => [
                            "aps" => [
                                "interruption-level" => "time-sensitive"
                            ]
                        ]
                    ]
                )
            );
            // echo json_encode($field);exit;
            $url = "https://fcm.googleapis.com/v1/projects/$projectId/messages:send";
            $header = [
                "Authorization: Bearer $authToken",
                "Content-Type: application/json"
            ];
            // echo json_encode($field);exit;
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($field));
            $result = curl_exec($ch);
            if ($result === false) {
                throw new \Exception('cURL Error: ' . curl_error($ch));
            }
            curl_close($ch);
            $response = json_decode($result, true);
            
            // Log notification to notificationLog table
            $deliveryType = isset($response['error']) ? 'error' : 'success';
            
            $notificationLogData = array(
                "appname" => $user['appname'],
                "userId" => $user['userId'],
                "fcmToken" => $user['fcmToken'],
                "title" => $title,
                "subtitle" => $subtitle,
                "deliveryType" => $deliveryType,
                "jsonDump" => $result
            );
            
            // Use the same pattern as other table operations
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $notificationLogUrl);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($notificationLogData));
            $logResult = curl_exec($ch);
            curl_close($ch);
        }
        
        // echo $type;
        // echo '<br>';
        // echo json_encode($users);
        // exit;
        
        // echo $title . '<br>' . $subtitle;
        // exit;
    }

    // Get users who need streak notifications (haven't marked streak today)
    function getUsersForStreakNotification($usersMissingStreakFunctionUrl, $usersUrl, $streakLogsUrl, $headers, $appname = null, $today) {
        $usersMissingStreakFunctionUrl .= "?input_date=" . urlencode($today);
        $ch = curl_init($usersMissingStreakFunctionUrl);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers
        ]);
        $response = curl_exec($ch);
        curl_close($ch);
        
        return json_decode($response, true);
    }

    function checkYesterdayStreakLoggedNotification($url, $headers, $filters, $today) {
        $date = date('Y-m-d', strtotime($today . ' -1 day'));
        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $operator => $v) {
                    $queryParts[] = "$key=" . $operator . "." . urlencode($v);
                }
            } else {
                $queryParts[] = "$key=eq." . urlencode($value);
            }
        }
        $queryUrl = "$url?" . implode('&', $queryParts) . "&created_at=gte.${date}T00:00:00&created_at=lt.${date}T23:59:59";
        $queryUrl = str_replace(" ", "%20", $queryUrl);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $queryUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        curl_close($ch);
    
        $data = json_decode($response, true);
        return !empty($data) ? $data : false;
    }

    // Get users who have a milestone on the date next to input date
    function getUsersWithMilestoneNextDay($milestonesUrl, $headers, $inputDate) {
        $nextDate = date('Y-m-d', strtotime($inputDate . ' +1 day'));
        
        // Build query to get milestones achieved on the next day
        $queryUrl = "$milestonesUrl?achieved_at=gte.${nextDate}T00:00:00&achieved_at=lt.${nextDate}T23:59:59&is_achieved=eq.true";
        $queryUrl = str_replace(" ", "%20", $queryUrl);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $queryUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        curl_close($ch);
    
        $data = json_decode($response, true);
        
        if (empty($data)) {
            return [];
        }
        
        // Extract unique user IDs and appnames from the milestones
        $users = [];
        $seenUsers = [];
        
        foreach ($data as $milestone) {
            $userId = $milestone['userId'];
            $appname = $milestone['appname'];
            $userKey = $userId . '_' . $appname;
            
            // Avoid duplicates
            if (!isset($seenUsers[$userKey])) {
                $seenUsers[$userKey] = true;
                $users[] = [
                    'userId' => $userId,
                    'appname' => $appname,
                    'milestone_achieved_at' => $milestone['achieved_at']
                ];
            }
        }
        
        return $users;
    }

    // Get users who would be eligible for a milestone by logging tomorrow's streak
    function getUsersEligibleForMilestoneTomorrow($usersEligibleForMilestoneTomorrowFunctionUrl, $userTableUrl, $streakLogTableUrl, $baseUrlMilestones, $baseUrlUserMilestones, $headers, $appname = null, $mock_date = null) {
        $today = $mock_date ? date('Y-m-d', strtotime($mock_date)) : date('Y-m-d');
        $usersEligibleForMilestoneTomorrowFunctionUrl .= "?input_date=" . urlencode($today);
        $ch = curl_init($usersEligibleForMilestoneTomorrowFunctionUrl);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers
        ]);
        $response = curl_exec($ch);
        curl_close($ch);
        // echo $response;exit;
        return json_decode($response, true);
        // Get all users (or filter by appname if provided)
        $queryUrl = $userTableUrl;
        if ($appname) {
            $queryUrl .= "?appname=eq." . urlencode($appname);
        }
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $queryUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        curl_close($ch);
        
        $users = json_decode($response, true);
        if (empty($users)) {
            return [];
        }
        
        $eligibleUsers = [];
        $today = $mock_date ? date('Y-m-d', strtotime($mock_date)) : date('Y-m-d');
        
        foreach ($users as $user) {
            $userId = $user['userId'];
            $userAppname = $user['appname'];
            
            // Get the streak SKU for this app
            $milestonesOfApps = getStreakSkuOfAMilestone($baseUrlMilestones, $headers, array("appname" => $userAppname));
            if (empty($milestonesOfApps)) {
                continue; // No milestones for this app
            }
            
            $streakSku = $milestonesOfApps[0]["streakSku"];
            
            // Get the latest streak log for this user and app
            $latestStreakLog = getAllStreakLogsApp($streakLogTableUrl, $headers, array(
                'appname' => $userAppname, 
                'userId' => $userId, 
                'streakSku' => $streakSku,
                'order' => ['created_at' => 'desc'],
                'limit' => 1
            ));
            
            if (empty($latestStreakLog)) {
                continue; // User has no streak logs
            }
            
            $currentStreakCount = (int)$latestStreakLog[0]['count'];
            
            // Check if streak is still valid (not broken)
            $lastStreakDate = $latestStreakLog[0]['created_at'];
            $lastStreakDateOnly = date('Y-m-d', strtotime($lastStreakDate));
            $yesterday = $mock_date ? date('Y-m-d', strtotime($mock_date . ' -1 day')) : date('Y-m-d', strtotime('-1 day'));
            
            // Streak is broken if last streak was more than 1 day ago
            if ($lastStreakDateOnly < $yesterday) {
                continue; // Streak is broken, skip this user
            }
            
            // Check if user has logged today's streak (using mock date if provided)
            if ($mock_date) {
                $todayStreakLogged = getStreakLogDataMock($streakLogTableUrl, $headers, array(
                    'appname' => $userAppname, 
                    'userId' => $userId, 
                    'streakSku' => $streakSku
                ), $mock_date);
            } else {
                $todayStreakLogged = getStreakLogData($streakLogTableUrl, $headers, array(
                    'appname' => $userAppname, 
                    'userId' => $userId, 
                    'streakSku' => $streakSku
                ));
            }
            
            // Determine which streak count to check for milestone eligibility
            if (!empty($todayStreakLogged)) {
                // Option A: Today is marked, check if current+1 = milestone
                $nextStreakCount = $currentStreakCount + 1;
            } else {
                // Option B: Today is NOT marked, check if current+2 = milestone
                $nextStreakCount = $currentStreakCount + 2;
            }
            
            // Check if there's a milestone for the calculated streak count
            $milestoneForNextCount = checkAnyMilestoneExist($baseUrlMilestones, $headers, array(
                'streakSku' => $streakSku, 
                'streakCount' => $nextStreakCount, 
                'appname' => $userAppname
            ));
            
            if ($milestoneForNextCount) {
                // Check if this milestone is already achieved
                $milestoneAlreadyAchieved = checkAnyMilestoneExistIsAchieved($baseUrlUserMilestones, $headers, array(
                    'milestoneSku' => $milestoneForNextCount[0]['sku'], 
                    'appname' => $userAppname, 
                    'userId' => $userId
                ));
                
                if (!$milestoneAlreadyAchieved) {
                    // User is eligible for this milestone
                    $eligibleUsers[] = array(
                        'userId' => $userId,
                        'appname' => $userAppname,
                        'name' => $user['name'] ?? '',
                        'fcmToken' => $user['fcmToken'] ?? '',
                        'platform' => $user['platform'] ?? 'android',
                        'timezone' => $user['timezone'] ?? '',
                        'currentStreakCount' => $currentStreakCount,
                        'nextStreakCount' => $nextStreakCount,
                        'todayMarked' => !empty($todayStreakLogged),
                        'mock_date' => $mock_date,
                        'milestone' => array(
                            'id' => $milestoneForNextCount[0]['id'],
                            'sku' => $milestoneForNextCount[0]['sku'],
                            'name' => $milestoneForNextCount[0]['name'],
                            'description' => $milestoneForNextCount[0]['description'],
                            'streakCount' => $milestoneForNextCount[0]['streakCount']
                        )
                    );
                }
            }
        }
        
        return $eligibleUsers;
    }

    // Get the latest pause log entry that doesn't have a resumed_at value
    function getLatestPauseLogWithoutResume($url, $headers, $filters) {
        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $operator => $v) {
                    $queryParts[] = "$key=" . $operator . "." . urlencode($v);
                }
            } else {
                $queryParts[] = "$key=eq." . urlencode($value);
            }
        }
        $queryUrl = "$url?" . implode('&', $queryParts) . "&resumed_at=is.null&order=created_at.desc&limit=1";
        $queryUrl = str_replace(" ", "%20", $queryUrl);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $queryUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        curl_close($ch);
    
        $data = json_decode($response, true);
        return !empty($data) ? $data : false;
    }

    // Update pause log entry
    function updatePauseLog($url, $headers, $id, $data) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "$url?id=eq.$id");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $res = curl_exec($ch);
        curl_close($ch);
        return json_decode($res, true);
    }

    function storeNotificationData($url, $headers, $data) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $res = curl_exec($ch);
        curl_close($ch);
        return json_decode($res, true);
    }

    function getNotificationFromTable($url, $headers, $appname, $lang, $type) {
        $queryUrl = "$url?appname=eq.$appname&lang=eq.$lang&type=eq.$type";
        $queryUrl = str_replace(" ", "%20", $queryUrl);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $queryUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        curl_close($ch);
        // echo $response;
        // exit;
    
        $data = json_decode($response, true);
        
        // Return a random row from the matching results
        if (!empty($data)) {
            $randomIndex = array_rand($data);
            return $data[$randomIndex];
        }
        
        return false;
    }

    // Weekly streak functions

    // Get streak log data for current week (Sunday to Saturday)
    function getStreakLogDataThisWeek($url, $headers, $filters) {
        $today = new DateTime();
        $weekStart = clone $today;
        $weekStart->modify('this week sunday');
        $weekEnd = clone $weekStart;
        $weekEnd->modify('+6 days');
        
        $weekStartStr = $weekStart->format('Y-m-d') . 'T00:00:00';
        $weekEndStr = $weekEnd->format('Y-m-d') . 'T23:59:59';
        
        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $operator => $v) {
                    $queryParts[] = "$key=" . $operator . "." . urlencode($v);
                }
            } else {
                $queryParts[] = "$key=eq." . urlencode($value);
            }
        }
        $queryUrl = "$url?" . implode('&', $queryParts) . "&created_at=gte.$weekStartStr&created_at=lt.$weekEndStr";
        $queryUrl = str_replace(" ", "%20", $queryUrl);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $queryUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        curl_close($ch);
    
        $data = json_decode($response, true);
        return !empty($data) ? $data : false;
    }

    // Get streak log data for current week (Sunday to Saturday) - Mock version
    function getStreakLogDataThisWeekMock($url, $headers, $filters, $mock_date) {
        $mockDateTime = new DateTime($mock_date);
        
        // Calculate the Sunday of the week containing the mock date
        $dayOfWeek = (int)$mockDateTime->format('w'); // 0 = Sunday, 1 = Monday, etc.
        $weekStart = clone $mockDateTime;
        $weekStart->modify("-{$dayOfWeek} days"); // Go back to Sunday
        
        $weekEnd = clone $weekStart;
        $weekEnd->modify('+6 days'); // Go to Saturday
        
        $weekStartStr = $weekStart->format('Y-m-d') . 'T00:00:00';
        $weekEndStr = $weekEnd->format('Y-m-d') . 'T23:59:59';
        
        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $operator => $v) {
                    $queryParts[] = "$key=" . $operator . "." . urlencode($v);
                }
            } else {
                $queryParts[] = "$key=eq." . urlencode($value);
            }
        }
        $queryUrl = "$url?" . implode('&', $queryParts) . "&created_at=gte.$weekStartStr&created_at=lt.$weekEndStr";
        $queryUrl = str_replace(" ", "%20", $queryUrl);

        // Debug output
        // echo "Mock Date: $mock_date\n";
        // echo "Day of Week: $dayOfWeek\n";
        // echo "Week Start: " . $weekStart->format('Y-m-d') . "\n";
        // echo "Week End: " . $weekEnd->format('Y-m-d') . "\n";
        // echo "Query URL: $queryUrl\n";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $queryUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        curl_close($ch);
    
        $data = json_decode($response, true);
        // echo "Response: " . json_encode($data) . "\n";
        return !empty($data) ? $data : false;
    }

    // Check if streak was logged in the previous week
    function checkLastWeekStreakLogged($url, $headers, $filters) {
        $today = new DateTime();
        $lastWeekStart = clone $today;
        $lastWeekStart->modify('last week sunday');
        $lastWeekEnd = clone $lastWeekStart;
        $lastWeekEnd->modify('+6 days');
        
        $lastWeekStartStr = $lastWeekStart->format('Y-m-d') . 'T00:00:00';
        $lastWeekEndStr = $lastWeekEnd->format('Y-m-d') . 'T23:59:59';
        
        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $operator => $v) {
                    $queryParts[] = "$key=" . $operator . "." . urlencode($v);
                }
            } else {
                $queryParts[] = "$key=eq." . urlencode($value);
            }
        }
        $queryUrl = "$url?" . implode('&', $queryParts) . "&created_at=gte.$lastWeekStartStr&created_at=lt.$lastWeekEndStr";
        $queryUrl = str_replace(" ", "%20", $queryUrl);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $queryUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        curl_close($ch);
    
        $data = json_decode($response, true);
        return !empty($data) ? $data : false;
    }

    // Check if streak was logged in the previous week - Mock version
    function checkLastWeekStreakLoggedMock($url, $headers, $filters, $mock_date) {
        $mockDateTime = new DateTime($mock_date);
        
        // Calculate the Sunday of the week containing the mock date
        $dayOfWeek = (int)$mockDateTime->format('w'); // 0 = Sunday, 1 = Monday, etc.
        $currentWeekStart = clone $mockDateTime;
        $currentWeekStart->modify("-{$dayOfWeek} days"); // Go back to Sunday
        
        // Calculate last week's Sunday (7 days before current week start)
        $lastWeekStart = clone $currentWeekStart;
        $lastWeekStart->modify('-7 days');
        
        $lastWeekEnd = clone $lastWeekStart;
        $lastWeekEnd->modify('+6 days'); // Go to Saturday
        
        $lastWeekStartStr = $lastWeekStart->format('Y-m-d') . 'T00:00:00';
        $lastWeekEndStr = $lastWeekEnd->format('Y-m-d') . 'T23:59:59';
        
        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $operator => $v) {
                    $queryParts[] = "$key=" . $operator . "." . urlencode($v);
                }
            } else {
                $queryParts[] = "$key=eq." . urlencode($value);
            }
        }
        $queryUrl = "$url?" . implode('&', $queryParts) . "&created_at=gte.$lastWeekStartStr&created_at=lt.$lastWeekEndStr";
        $queryUrl = str_replace(" ", "%20", $queryUrl);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $queryUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        curl_close($ch);
    
        $data = json_decode($response, true);
        return !empty($data) ? $data : false;
    }

    // Get the appropriate weekly streak count considering pause logic
    function getWeeklyStreakCountWithPauseLogic($baseUrl, $baseUrlPaused, $pauseLogUrl, $headers, $params, $lastStreakLog = null) {
        $appname = $params['appname'];
        $userId = $params['userId'];
        $streakSku = $params['streakSku'];
        
        // If no last streak log provided, get it
        if (!$lastStreakLog) {
            $lastStreakLog = getAllStreakLogsApp($baseUrl, $headers, array(
                'appname' => $appname, 
                'userId' => $userId, 
                'streakSku' => $streakSku,
                'order' => ['created_at' => 'desc'],
                'limit' => 1
            ));
        }
        
        if (empty($lastStreakLog)) {
            return 1; // First streak
        }
        
        $lastStreakData = $lastStreakLog[0];
        $lastStreakDate = $lastStreakData['created_at'];
        $lastStreakCount = (int)$lastStreakData['count'];
        
        // Check if pause was activated properly for weekly streaks
        $pauseActivatedProperly = checkWeeklyPauseActivationForStreak($baseUrlPaused, $pauseLogUrl, $headers, $params, $lastStreakDate);
        
        // Debug output
        // echo "Last streak date: " . $lastStreakDate . "\n";
        // echo "Last streak count: " . $lastStreakCount . "\n";
        // echo "Pause activated properly: " . ($pauseActivatedProperly ? "true" : "false") . "\n";
        
        if ($pauseActivatedProperly) {
            // Continue streak: last count + 1
            return $lastStreakCount + 1;
        } else {
            // Reset streak: count = 1 (either no pause record or pause not activated properly)
            return 1;
        }
    }

    // Check if pause was activated properly for weekly streaks
    function checkWeeklyPauseActivationForStreak($baseUrlPaused, $pauseLogUrl, $headers, $params, $lastStreakDate = null) {
        $appname = $params['appname'];
        $userId = $params['userId'];
        
        // If no last streak date provided, get it from the database
        if (!$lastStreakDate) {
            global $baseUrl;
            $lastStreakLog = getAllStreakLogsApp($baseUrl, $headers, array(
                'appname' => $appname, 
                'userId' => $userId, 
                'streakSku' => $params['streakSku'],
                'order' => ['created_at' => 'desc'],
                'limit' => 1
            ));
            
            if (empty($lastStreakLog)) {
                return false; // No previous streak found
            }
            
            $lastStreakDate = $lastStreakLog[0]['created_at'];
        }
        
        // Get the latest pause log entry
        $latestPauseLog = getAllStreakLogsApp($pauseLogUrl, $headers, array(
            'appname' => $appname,
            'userId' => $userId,
            'order' => ['created_at' => 'desc'],
            'limit' => 1
        ));
        
        if (empty($latestPauseLog)) {
            // echo "Monthly pause: No pause log entries found\n";
            return false; // No pause log entries found
        }
        
        $pauseLogEntry = $latestPauseLog[0];
        
        // If pause is still active (not resumed), auto-resume it and continue streak
        if (!isset($pauseLogEntry['resumed_at'])) {
            // echo "Pause is still active, auto-resuming...\n";
            // Auto-resume the pause
            $pauseLogId = $pauseLogEntry['id'];
            $triggered_at = isset($_GET['date']) ? date('c', strtotime($_GET['date'])) : date('c');
            $updatePauseLogData = array(
                "resumed_at" => $triggered_at
            );
            updatePauseLog($pauseLogUrl, $headers, $pauseLogId, $updatePauseLogData);
            
            // Also update the streakPause table
            $pauseData = checkPauseExist($baseUrlPaused, $headers, array('appname' => $appname, 'userId' => $userId));
            if ($pauseData && !empty($pauseData)) {
                $id = $pauseData[0]['id'];
                updatePauseResumeStreak($baseUrlPaused, $headers, $id, array("is_pause" => "0", "triggered_at" => $triggered_at));
            }
            
            return true; // Continue streak - pause was active and we're resuming it
        }
        
        // If pause was already resumed, check if we're logging in the same week as the resume
        $resumedAt = new DateTime($pauseLogEntry['resumed_at']);
        
        // Get current logging date (either from GET parameter or current date)
        $currentLogDate = isset($_GET['date']) ? date('Y-m-d', strtotime($_GET['date'])) : date('Y-m-d');
        $currentLogDateTime = new DateTime($currentLogDate);
        
        // Check if current log is in the same week as resume
        // Calculate the week containing the resume date
        $resumeDayOfWeek = (int)$resumedAt->format('w'); // 0 = Sunday, 1 = Monday, etc.
        $resumeWeekStart = clone $resumedAt;
        $resumeWeekStart->modify("-{$resumeDayOfWeek} days"); // Go back to Sunday
        $resumeWeekEnd = clone $resumeWeekStart;
        $resumeWeekEnd->modify('+6 days'); // Go to Saturday
        
        $loggingInResumeWeek = ($currentLogDateTime >= $resumeWeekStart && $currentLogDateTime <= $resumeWeekEnd);
        
        // echo "Pause was already resumed at: " . $pauseLogEntry['resumed_at'] . "\n";
        // echo "Current log date: " . $currentLogDate . "\n";
        // echo "Resume week: " . $resumeWeekStart->format('Y-m-d') . " to " . $resumeWeekEnd->format('Y-m-d') . "\n";
        // echo "Logging in resume week: " . ($loggingInResumeWeek ? "true" : "false") . "\n";
        
        if ($loggingInResumeWeek) {
            return true; // Continue streak
        } else {
            return false; // Reset streak - missed the resume week
        }
    }

    // Monthly streak functions

    // Get streak log data for current month
    function getStreakLogDataThisMonth($url, $headers, $filters) {
        $today = new DateTime();
        $monthStart = new DateTime($today->format('Y-m-01'));
        $monthEnd = new DateTime($today->format('Y-m-t'));
        
        $monthStartStr = $monthStart->format('Y-m-d') . 'T00:00:00';
        $monthEndStr = $monthEnd->format('Y-m-d') . 'T23:59:59';
        
        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $operator => $v) {
                    $queryParts[] = "$key=" . $operator . "." . urlencode($v);
                }
            } else {
                $queryParts[] = "$key=eq." . urlencode($value);
            }
        }
        $queryUrl = "$url?" . implode('&', $queryParts) . "&created_at=gte.$monthStartStr&created_at=lt.$monthEndStr";
        $queryUrl = str_replace(" ", "%20", $queryUrl);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $queryUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        curl_close($ch);
    
        $data = json_decode($response, true);
        return !empty($data) ? $data : false;
    }

    // Get streak log data for current month - Mock version
    function getStreakLogDataThisMonthMock($url, $headers, $filters, $mock_date) {
        $mockDateTime = new DateTime($mock_date);
        $monthStart = new DateTime($mockDateTime->format('Y-m-01'));
        $monthEnd = new DateTime($mockDateTime->format('Y-m-t'));
        
        $monthStartStr = $monthStart->format('Y-m-d') . 'T00:00:00';
        $monthEndStr = $monthEnd->format('Y-m-d') . 'T23:59:59';
        
        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $operator => $v) {
                    $queryParts[] = "$key=" . $operator . "." . urlencode($v);
                }
            } else {
                $queryParts[] = "$key=eq." . urlencode($value);
            }
        }
        $queryUrl = "$url?" . implode('&', $queryParts) . "&created_at=gte.$monthStartStr&created_at=lt.$monthEndStr";
        $queryUrl = str_replace(" ", "%20", $queryUrl);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $queryUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        curl_close($ch);
    
        $data = json_decode($response, true);
        return !empty($data) ? $data : false;
    }

    // Check if streak was logged in the previous month
    function checkLastMonthStreakLogged($url, $headers, $filters) {
        $today = new DateTime();
        $lastMonth = clone $today;
        $lastMonth->modify('-1 month');
        
        $lastMonthStart = new DateTime($lastMonth->format('Y-m-01'));
        $lastMonthEnd = new DateTime($lastMonth->format('Y-m-t'));
        
        $lastMonthStartStr = $lastMonthStart->format('Y-m-d') . 'T00:00:00';
        $lastMonthEndStr = $lastMonthEnd->format('Y-m-d') . 'T23:59:59';
        
        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $operator => $v) {
                    $queryParts[] = "$key=" . $operator . "." . urlencode($v);
                }
            } else {
                $queryParts[] = "$key=eq." . urlencode($value);
            }
        }
        $queryUrl = "$url?" . implode('&', $queryParts) . "&created_at=gte.$lastMonthStartStr&created_at=lt.$lastMonthEndStr";
        $queryUrl = str_replace(" ", "%20", $queryUrl);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $queryUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        curl_close($ch);
    
        $data = json_decode($response, true);
        return !empty($data) ? $data : false;
    }

    // Check if streak was logged in the previous month - Mock version
    function checkLastMonthStreakLoggedMock($url, $headers, $filters, $mock_date) {
        $mockDateTime = new DateTime($mock_date);
        $lastMonth = clone $mockDateTime;
        $lastMonth->modify('-1 month');
        
        $lastMonthStart = new DateTime($lastMonth->format('Y-m-01'));
        $lastMonthEnd = new DateTime($lastMonth->format('Y-m-t'));
        
        $lastMonthStartStr = $lastMonthStart->format('Y-m-d') . 'T00:00:00';
        $lastMonthEndStr = $lastMonthEnd->format('Y-m-d') . 'T23:59:59';
        
        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $operator => $v) {
                    $queryParts[] = "$key=" . $operator . "." . urlencode($v);
                }
            } else {
                $queryParts[] = "$key=eq." . urlencode($value);
            }
        }
        $queryUrl = "$url?" . implode('&', $queryParts) . "&created_at=gte.$lastMonthStartStr&created_at=lt.$lastMonthEndStr";
        $queryUrl = str_replace(" ", "%20", $queryUrl);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $queryUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        curl_close($ch);
    
        $data = json_decode($response, true);
        return !empty($data) ? $data : false;
    }

    // Get the appropriate monthly streak count considering pause logic
    function getMonthlyStreakCountWithPauseLogic($baseUrl, $baseUrlPaused, $pauseLogUrl, $headers, $params, $lastStreakLog = null) {
        $appname = $params['appname'];
        $userId = $params['userId'];
        $streakSku = $params['streakSku'];
        
        // If no last streak log provided, get it
        if (!$lastStreakLog) {
            $lastStreakLog = getAllStreakLogsApp($baseUrl, $headers, array(
                'appname' => $appname, 
                'userId' => $userId, 
                'streakSku' => $streakSku,
                'order' => ['created_at' => 'desc'],
                'limit' => 1
            ));
        }
        
        if (empty($lastStreakLog)) {
            return 1; // First streak
        }
        
        $lastStreakData = $lastStreakLog[0];
        $lastStreakDate = $lastStreakData['created_at'];
        $lastStreakCount = (int)$lastStreakData['count'];
        
        // Check if pause was activated properly for monthly streaks
        $pauseActivatedProperly = checkMonthlyPauseActivationForStreak($baseUrlPaused, $pauseLogUrl, $headers, $params, $lastStreakDate);
        
        // echo "Monthly streak debug:\n";
        // echo "Last streak date: " . $lastStreakDate . "\n";
        // echo "Last streak count: " . $lastStreakCount . "\n";
        // echo "Pause activated properly: " . ($pauseActivatedProperly ? "true" : "false") . "\n";
        
        if ($pauseActivatedProperly) {
            // Continue streak: last count + 1
            return $lastStreakCount + 1;
        } else {
            // Reset streak: count = 1 (either no pause record or pause not activated properly)
            return 1;
        }
    }

    // Check if pause was activated properly for monthly streaks
    function checkMonthlyPauseActivationForStreak($baseUrlPaused, $pauseLogUrl, $headers, $params, $lastStreakDate = null) {
        $appname = $params['appname'];
        $userId = $params['userId'];
        
        // If no last streak date provided, get it from the database
        if (!$lastStreakDate) {
            global $baseUrl;
            $lastStreakLog = getAllStreakLogsApp($baseUrl, $headers, array(
                'appname' => $appname, 
                'userId' => $userId, 
                'streakSku' => $params['streakSku'],
                'order' => ['created_at' => 'desc'],
                'limit' => 1
            ));
            
            if (empty($lastStreakLog)) {
                return false; // No previous streak found
            }
            
            $lastStreakDate = $lastStreakLog[0]['created_at'];
        }
        
        // Get the latest pause log entry
        $latestPauseLog = getAllStreakLogsApp($pauseLogUrl, $headers, array(
            'appname' => $appname,
            'userId' => $userId,
            'order' => ['created_at' => 'desc'],
            'limit' => 1
        ));
        
        if (empty($latestPauseLog)) {
            // echo "Monthly pause: No pause log entries found\n";
            return false; // No pause log entries found
        }
        
        $pauseLogEntry = $latestPauseLog[0];
        
        // Check if pause was activated properly (before the expected next month)
        $lastStreakDateTime = new DateTime($lastStreakDate);
        $lastStreakMonth = $lastStreakDateTime->format('Y-m');
        
        // Calculate the expected next month (the month after the last streak)
        $expectedNextMonth = clone $lastStreakDateTime;
        $expectedNextMonth->modify('+1 month');
        $expectedNextMonthStr = $expectedNextMonth->format('Y-m');
        
        // Get pause activation date
        $pauseActivatedAt = new DateTime($pauseLogEntry['created_at']);
        $pauseActivatedMonth = $pauseActivatedAt->format('Y-m');
        
        // Check if pause was activated in the same month as last streak or in the expected next month
        $pauseActivatedInLastStreakMonth = ($pauseActivatedMonth === $lastStreakMonth);
        $pauseActivatedInExpectedNextMonth = ($pauseActivatedMonth === $expectedNextMonthStr);
        
        // If pause was activated after the expected next month, it should reset the streak
        if (!$pauseActivatedInLastStreakMonth && !$pauseActivatedInExpectedNextMonth) {
            return false; // Reset streak - pause activated too late
        }
        
        // If pause is still active (not resumed), auto-resume it and continue streak
        if (!isset($pauseLogEntry['resumed_at'])) {
            // echo "Monthly pause: Pause is still active, auto-resuming...\n";
            // Auto-resume the pause
            $pauseLogId = $pauseLogEntry['id'];
            $triggered_at = isset($_GET['date']) ? date('c', strtotime($_GET['date'])) : date('c');
            $updatePauseLogData = array(
                "resumed_at" => $triggered_at
            );
            updatePauseLog($pauseLogUrl, $headers, $pauseLogId, $updatePauseLogData);
            
            // Also update the streakPause table
            $pauseData = checkPauseExist($baseUrlPaused, $headers, array('appname' => $appname, 'userId' => $userId));
            if ($pauseData && !empty($pauseData)) {
                $id = $pauseData[0]['id'];
                updatePauseResumeStreak($baseUrlPaused, $headers, $id, array("is_pause" => "0", "triggered_at" => $triggered_at));
            }
            
            return true; // Continue streak - pause was active and we're resuming it
        }
        
        // If pause was already resumed, check if we're logging in the same month as the resume
        $resumedAt = new DateTime($pauseLogEntry['resumed_at']);
        
        // Get current logging date (either from GET parameter or current date)
        $currentLogDate = isset($_GET['date']) ? date('Y-m', strtotime($_GET['date'])) : date('Y-m');
        $resumeMonth = $resumedAt->format('Y-m');
        
        $loggingInResumeMonth = ($currentLogDate === $resumeMonth);
        
        if ($loggingInResumeMonth) {
            return true; // Continue streak
        } else {
            return false; // Reset streak - missed the resume month
        }
    }
?>
