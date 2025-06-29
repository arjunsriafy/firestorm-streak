<?php
    if (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || isset($_GET['debug'])) {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    } else {
        // On staging/production: hide errors
        ini_set('display_errors', 0);
        error_reporting(0);
    }
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
        // APIs for app
        case "app-log-a-streak":
            // Log a streak
            $baseUrl = "https://$projectId.supabase.co/rest/v1/streakLog";
            $payloadToInsert = array('appname' => $_GET['appname'], 'userId' => $_GET['userId'], 'streakSku' => $_GET['streakSku']);
            $lang = $_GET['lang'];
            unset($_GET['lang']);
            $baseUrlStreaks =  "https://$projectId.supabase.co/rest/v1/streaks";
            $getStreakData = getStreakData($baseUrlStreaks, $headers, array('appname' => $_GET['appname'], 'sku' => $_GET['streakSku']));
            if ($getStreakData) {
                if(isset($getStreakData[0]['streakType']) && $getStreakData[0]['streakType'] == 'daily'){
                    $existsTodayStreak = getStreakLogData($baseUrl, $headers, array('appname' => $_GET['appname'], 'userId' => $_GET['userId'], 'streakSku' => $_GET['streakSku']));
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
            $checkYesterdayStreakLogged = checkYesterdayStreakLogged($baseUrl, $headers, array('appname' => $_GET['appname'], 'userId' => $_GET['userId'], 'streakSku' => $_GET['streakSku']));
            if ($checkYesterdayStreakLogged) {
                $count = (int)$checkYesterdayStreakLogged[0]['count'] + 1;
            }
            else{
                // Condition for pause
                // $baseUrlPaused = "https://$projectId.supabase.co/rest/v1/streakPause";
                // $count = getUpdatedStreakCount($baseUrl, $baseUrlPaused, $headers, array(
                //     'appname' => $_GET['appname'],
                //     'userId' => $_GET['userId'],
                //     'streakSku' => $_GET['streakSku']
                // ));
                // echo $count;exit;

                $count = 1;
            }
            // echo $count;exit;
            $payloadToInsert['count'] = $count;
            $new = logStreak($baseUrl, $headers, $payloadToInsert);
            $baseUrlMilestones = "https://$projectId.supabase.co/rest/v1/milestones";
            $checkAnyMilestoneExist = checkAnyMilestoneExist($baseUrlMilestones, $headers, array('streakSku' => $_GET['streakSku'], 'streakCount' => $count));
            if ($checkAnyMilestoneExist) {
                $baseUrlUserMilestones = "https://$projectId.supabase.co/rest/v1/userMilestones";
                $insertUserMileStonePayload = array(
                    "appname" => $_GET['appname'],
                    "userId" => $_GET['userId'],
                    "milestoneSku" => $checkAnyMilestoneExist[0]['sku'],
                    "milestoneId" => $checkAnyMilestoneExist[0]['id'],
                );
                $checkAnyMilestoneExistLocalisations = json_decode($checkAnyMilestoneExist[0]['localizations'], true);
                if($lang == 'en'){
                    $checkAnyMilestoneExist[0]["nameLocalised"] = $checkAnyMilestoneExist[0]['name'];
                    $checkAnyMilestoneExist[0]["descriptionLocalised"] = $checkAnyMilestoneExist[0]['description'];
                }
                else{
                    $checkAnyMilestoneExist[0]["nameLocalised"] = $checkAnyMilestoneExistLocalisations[$lang]['name'];
                    $checkAnyMilestoneExist[0]["descriptionLocalised"] = $checkAnyMilestoneExistLocalisations[$lang]['description'];
                }
                $newMilestone = addNewUserMilestones($baseUrlUserMilestones, $headers, $insertUserMileStonePayload);
                echo json_encode(array("status" => "success", "message" => "Streak logged succesfully", "milestone" => $checkAnyMilestoneExist[0]));
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
                $new = updatePauseResumeStreak($baseUrl, $headers, $id, array("is_pause" => "1"));
                echo json_encode(array("status" => "success", "message" => "Streak paused succcesfully", "response" => $new));
            }
            else{
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
                $new = updatePauseResumeStreak($baseUrl, $headers, $id, array("is_pause" => "0"));
                echo json_encode(array("status" => "success", "message" => "Streak resumed succesfully", "response" => $new));
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
            $streakId = $milestones[0]["streakId"];
            $baseUrlStreaks = "https://$projectId.supabase.co/rest/v1/streaks";
            $allStreaksApp = getAllStreaksApp($baseUrlStreaks, $headers, array('id' => $streakId));
            $baseUrlStreakLogs = "https://$projectId.supabase.co/rest/v1/streakLog";
            $allStreakLogsApp = getAllStreakLogsApp($baseUrlStreakLogs, $headers, array('appname' => $_GET['appname'], 'userId' => $_GET['userId'], 'order' => ['created_at' => 'desc']));
            $maxCount = max(array_map('intval', array_column($allStreakLogsApp, 'count')));
            $allStreaksApp[0]["longest_streak"] = $maxCount;
            $allStreaksApp[0]["current_streak"] = $allStreakLogsApp[0]["count"];

            $allStreaks = array_map(function($streak) {
                unset($streak['localizations']);
                return $streak;
            }, $allStreaksApp);
            
            echo json_encode(array("streaks" => $allStreaks[0], "milestones" => $milestones));
            
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
        // echo $queryUrl;exit;

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
?>
