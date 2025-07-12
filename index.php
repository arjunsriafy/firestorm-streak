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
            }
            else{
                http_response_code(403);
                echo json_encode(array("status" => "error", "message" => "This streak not exist for this app"));
                exit;
            }
            $checkYesterdayStreakLogged = checkYesterdayStreakLogged($baseUrl, $headers, array('appname' => $_GET['appname'], 'userId' => $_GET['userId'], 'streakSku' => $streakSku));
            if ($checkYesterdayStreakLogged) {
                $count = (int)$checkYesterdayStreakLogged[0]['count'] + 1;
            }
            else{
                // Check pause logic for streak continuation
                $baseUrlPaused = "https://$projectId.supabase.co/rest/v1/streakPause";
                $params = array(
                    'appname' => $_GET['appname'],
                    'userId' => $_GET['userId'],
                    'streakSku' => $streakSku
                );
                $count = getStreakCountWithPauseLogic($baseUrl, $baseUrlPaused, $headers, $params);
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
                "lang" => $lang
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
                }
                else{
                    $payloadToInsert = array(
                        "appname" => $_GET['appname'],
                        "userId" => $_GET['userId'],
                        "is_pause" => "1",
                        "triggered_at" => date('c')
                    );
                }
                $new = pauseResumeStreak($baseUrl, $headers, $payloadToInsert);
                echo json_encode(array("status" => "success", "message" => "Streak paused succesfully", "response" => $new));
            }
        break;
        case "app-resume-streak":
            // Resume streak
            $baseUrl = "https://$projectId.supabase.co/rest/v1/streakPause";
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
            
            echo json_encode(array("streaks" => $streaks, "milestones" => $milestones, "restore_streak_saved" => 0));
            
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
            }
            else{
                http_response_code(403);
                echo json_encode(array("status" => "error", "message" => "This streak not exist for this app"));
                exit;
            }
            $checkYesterdayStreakLogged = checkYesterdayStreakLoggedMock($baseUrl, $headers, array('appname' => $_GET['appname'], 'userId' => $_GET['userId'], 'streakSku' => $streakSku), $mock_date);
            if ($checkYesterdayStreakLogged) {
                $count = (int)$checkYesterdayStreakLogged[0]['count'] + 1;
            }
            else{
                // Check pause logic for streak continuation (for mock data, we need to get the last streak before the mock date)
                $baseUrlPaused = "https://$projectId.supabase.co/rest/v1/streakPause";
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
                
                $count = getStreakCountWithPauseLogic($baseUrl, $baseUrlPaused, $headers, $params, $lastStreakLog);
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
                "lang" => $lang
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
            $today = date('Y-m-d');
            $usersToNotify = getUsersForStreakNotification($usersMissingStreakFunctionUrl, $userTableUrl, $streakLogTableUrl, $headers, $appname, $today);
            sendFCMMessage($usersToNotify, $streakLogTableUrl, $baseUrlMilestones, $baseUrlUserMilestones, $today, $headers);

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
    function checkPauseActivationForStreak_Scenario1($baseUrlPaused, $headers, $params, $lastStreakDate = null) {
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
        
        // Calculate the expected next streak date (next day after last streak)
        $expectedNextStreakDate = clone $lastStreakDateTime;
        $expectedNextStreakDate->modify('+1 day');
        
        // Check if pause was activated on the same day as last streak or the very next day
        $pauseTriggeredDate = $pauseTriggeredDateTime->format('Y-m-d');
        $lastStreakDateOnly = $lastStreakDateTime->format('Y-m-d');
        $expectedNextStreakDateOnly = $expectedNextStreakDate->format('Y-m-d');
        
        // Pause is valid if activated on the same day as last streak OR the very next day
        $pauseIsValid = ($pauseTriggeredDate === $lastStreakDateOnly || $pauseTriggeredDate === $expectedNextStreakDateOnly);
        
        if ($pauseIsValid) {
            // Resume the streak by setting is_pause to 0
            $id = $pauseRecord['id'];
            $triggered_at = isset($_GET['date']) ? $_GET['date'] : date('c');
            updatePauseResumeStreak($baseUrlPaused, $headers, $id, array("is_pause" => "0", "triggered_at" => $triggered_at));
        }
        
        return $pauseIsValid;
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
    function getStreakCountWithPauseLogic($baseUrl, $baseUrlPaused, $headers, $params, $lastStreakLog = null) {
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
        $pauseActivatedProperly = checkPauseActivationForStreak_Scenario1($baseUrlPaused, $headers, $params, $lastStreakDate);
        
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

    function sendFCMMessage($users, $streakLogTableUrl, $baseUrlMilestones, $baseUrlUserMilestones, $today, $headers) {
        // echo json_encode($users);
        // exit;
        foreach ($users as $user) {
            $milestonesOfApps = getStreakSkuOfAMilestone($baseUrlMilestones, $headers, array("appname" => $user['appname']));
            $streakSku = $milestonesOfApps[0]["streakSku"];
            $appid = 'low.carb.recipes.diet';
            $fcmToken = $user['fcmToken'];
            $checkYesterdayStreakLogged = checkYesterdayStreakLoggedNotification($streakLogTableUrl, $headers, array('appname' => $user['appname'], 'userId' => $user['userId'], 'streakSku' => $streakSku), $today);
            if ($checkYesterdayStreakLogged) {
                $count = (int)$checkYesterdayStreakLogged[0]['count'] + 1;
                $checkAnyMilestoneExist = checkAnyMilestoneExist($baseUrlMilestones, $headers, array('streakSku' => $streakSku, 'streakCount' => $count, 'appname' => $user['appname']));
                if ($checkAnyMilestoneExist) {
                    $checkAnyMilestoneExistIsAchieved = checkAnyMilestoneExistIsAchieved($baseUrlUserMilestones, $headers, array('milestoneSku' => $checkAnyMilestoneExist[0]['sku'], 'appname' => $user['appname'], "userId" => $user['userId']));
                    if($checkAnyMilestoneExistIsAchieved == false){
                        $title = "Knowledge Milestone Approaching! 🚀";
                        // Extract first name only
                        $firstName = explode(' ', trim($user['name']))[0];
                        $subtitle = str_replace(['%name%', '%milestone_day%'], [$firstName, $checkAnyMilestoneExist[0]['name']], "%name%, %milestone_day% summaries await! One last push to unlock your next reading milestone 💪");
                    }
                }
                else{
                    $title = "Your streak have been ended";
                    // Extract first name only
                    $firstName = explode(' ', trim($user['name']))[0];
                    $subtitle = str_replace(['%name%', '%streak_day%'], [$firstName, $count], "%name%, your mind craves those 15 minutes! Don't break your %streak_day%-day reading habit now 🧠");
                }
            }
            else{
                $title = "Your streak have been ended";
                $subtitle = "kjn";
            }
            break;
        }
        // echo $title . '<br>' . $subtitle;
        // exit;
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
        
        if (in_array($appid, $video_series_1_full_apps)) {
            $projectId = 'content-apps';
            $authToken = generateAccessToken(__DIR__ . '/service-accounts/content-apps-firebase-adminsdk-x2f49-e284d4f61e_9zkYCj9.json');
            $API_ACCESS_KEY = 'AAAAWS9D6S0:APA91bHZVIolkpGpf0DWGtvGopZm19BHXlJ_lCtgeyGV8t7CxHMENgdjGNNHdtRAVAGFG5vl3OnsTM9WUFDwYQt_Iop_u8IIXYlj7tl3jHjWnIjSgvLhQioTRZU4N_bH1a4cFE8LsGEi';
        }
        if (in_array($appid, $video_series_2_full_apps)) {
            $projectId = 'content-apps-2';
            $authToken = generateAccessToken(__DIR__ . '/service-accounts/content-apps-2-firebase-adminsdk-m3fox-fd4c5cb469_bAdPKe0.json');
            $API_ACCESS_KEY = 'AAAAjCEzAL8:APA91bHh84nJtqVgkyR0ZFyUUd9BbH-9BP5z8273ZjvciAmszxFQ4y4fpuchIx7CQAyZr5-A1BVFiOIyy3lb1Ld0gAeNY06jvIcCdn_vKLiSsdLRoUIFBE1blLQlcLdOh-jMt9bcZ-j2';
        }
        if (in_array($appid, $cooking_series_full_apps)) {
            $projectId = 'cookbook-now-145';
            $authToken = generateAccessToken(__DIR__ . '/service-accounts/cookbook-now-145-firebase-adminsdk-6hpb8-7b02f60c98_0rl14nT.json');
            $API_ACCESS_KEY = 'AAAAAkO_Jxg:APA91bGu4kXBpD5Sw_MdHskgOi0IttWXjf9YJrmgBZn7ONVtPptJmB8IdEcRcAif1Q19SS7sTninuDh1xq2YBx9czXCzGvX-Yu7N4Di74LgOWTIQxlzsUCWq5va9F5yhbsOit0UpkN8V';
        }
        if (in_array($appid, $walking_series_full_apps)) {
            $projectId = 'daily-quotes-1a4a9';
            $authToken = generateAccessToken(__DIR__ . '/service-accounts/daily-quotes-1a4a9-firebase-adminsdk-7ty0l-fc3888c361_r2uOLfN.json');
            $API_ACCESS_KEY = 'AAAAKNh3zfQ:APA91bFo8xcKi9jn1Oqh0Dtk6JxRZkkvbnB-IKdzBVq2YoJLzyQPyW0VIYpXtN1MMI1BONHmqVmONYQnWadW8VG-_vohTW04i40oqKmyd9JrjKfz-WP778FYzn5h_GxcD3TmnOmMoe61';
        }
        if (in_array($appid, $workout_series_full_apps)) {
            $projectId = 'workout-ed0ae';
            $authToken = generateAccessToken(__DIR__ . '/service-accounts/workout-ed0ae-firebase-adminsdk-ut76u-fd94a13f10_hmAOSvM.json');
            $API_ACCESS_KEY = 'AAAADLOR9fE:APA91bGFSLL9qB6CYAiY8mrZmkfRva2UkPDmne_Eu3NQvfx5qpD84PDL--Srnzcd9lAmRnisk_Av6d1DQ1ChNKM7aODovVlY3XGj57j9ce__o0WgKp6inE-jPAFOd-38t0ybA4TBIsEC';
        }
        if (in_array($appid, $quit_smoking_series_full_apps)) {
            $projectId = 'fir-d9861';
            $authToken = generateAccessToken(__DIR__ . '/service-accounts/fir-d9861-firebase-adminsdk-nkaxk-8d888b6d50_ztiKEFY.json');
            $API_ACCESS_KEY = 'AAAA2ghYcGY:APA91bFpnNK4CZcUNAVGkKqBvgcVstFUNdJPxUZhxNpo2tJJgQSK6w6cwv_jG7D1nIlsySjZ05OP3te8lQqIHUcMarfIDuiLP_ZLYzNg5vRceWpY3N1xKjDLgEKV__Fukpan5RaY8zo6';
        }
        if (in_array($appid, $simi_full_apps)) {
            $projectId = 'simi-shell';
            $authToken = generateAccessToken(__DIR__ . '/service-accounts/simi-shell-firebase-adminsdk-7oobc-215579245e_oPiVw3V.json');
            $API_ACCESS_KEY = 'AAAAGegQNNs:APA91bFI3tPe8BXWGsP2yWO8T-9L_muyg4Vt_7Zn5ruKl72JORVBJj505t1VIMTKZp7dqf4ib9tWxvZlgfPIXUoJGKrKKcsrRrp9n3SzMsR2SIP_UTnc8-hs9Vo72HjvTsrEl1K93Vs5';
        }
        if (in_array($appid, $sarath_full_apps)) {
            $projectId = 'walking-tracker-pedometer';
            $authToken = generateAccessToken(__DIR__ . '/service-accounts/walking-tracker-pedometer-firebase-adminsdk-ggkr6-6cdbc64f58_V0GGKhZ.json');
            $API_ACCESS_KEY = 'AAAAMwdxH34:APA91bEpdS0bMI-8qj5lACZBPMsNX-w7oanrqnQyJjwb2xVYqMrxhxbIAxBW31PU8tMFrnXw7SOkezdbIXYdgMPfBobIDGuxOuJf9mGRLcEluK4-tx8Qee42O8AJSPSxy0o0g7WQ-APA';
        }
        if (in_array($appid, $sarath_2_full_apps)) {
            $projectId = 'test-7e604';
            $authToken = generateAccessToken(__DIR__ . '/service-accounts/test-7e604-firebase-adminsdk-ewkbe-9462a4883c_HQKBQ6J.json');
            $API_ACCESS_KEY = 'AAAAzQq-U-4:APA91bGBsYYvBSIQT2Ru-KeXp2iTaiBsiWtDY_YwM7mngevDu0VkMZccy0R2N7cu2d0QdqT6NYIS3gBOWsyt72c4AWZ-PrzQcKzR3kIWPWj3HplLASC5vKOkWynJ8vYlSYAc9MFImuVt';
        }
        if (in_array($appid, $sarath_3_full_apps)) {
            $projectId = 'health-tracker-series';
            $authToken = generateAccessToken(__DIR__ . '/service-accounts/health-tracker-series-firebase-adminsdk-drw2m-2b863b4260_zXq480W.json');
            $API_ACCESS_KEY = 'AAAA7dVJfTI:APA91bFMl4GMNuvLPWIMOtlLQpZStfI_2Y_QCx1y7bUPAcKMYE-cBGH8uq9kUY8kAEdr7CrleOlGiS-LUeipvYI20KIshvJPQMxAClydeliub-l6c30_hYBjKSlAXTvU2EGng1WQ9Cjt';
        }
        if (in_array($appid, $sarath_4_full_apps)) {
            $projectId = 'read-book-series';
            $authToken = generateAccessToken(__DIR__ . '/service-accounts/read-book-series-firebase-adminsdk-nym1x-3d8079bb72_JfRqbzP.json');
            $API_ACCESS_KEY = 'AAAA1w_Ng8M:APA91bGN5y7jAHr-UA5m09aM2bCp1tJa8WFsfukx6_s7NL6BbNXsZIgo3z5EDFw1M5xme6wXXD_Hn57_BDIWmHECRwxfUibA0Y5WmqcPed2gABz6I8KZVVHaiwwn8V4z7DuzAGD2lO-2';
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
        echo $result;
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
        
        // Extdract unique user IDs and appnames from the milestones
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
?>
