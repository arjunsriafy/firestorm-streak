<?php
    // ini_set('display_errors', '1');
    // ini_set('display_startup_errors', '1');
    // error_reporting(E_ALL);

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

    $method = '';
    if(isset($inputPayload['method']) && $inputPayload['method'] != ''){
        $method = $inputPayload['method'];
    }
    
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
        // Admin streaks
        case "get-all-streaks-admin":
            // Read all streaks admin
            $baseUrl = "https://$projectId.supabase.co/rest/v1/streaks";
            $allStreaksAdmin = getAllStreaksAdmin($baseUrl, $headers);
            echo json_encode($allStreaksAdmin);
        break;
        case "insert-streak-admin":
            // Insert streak admin
            $baseUrl = "https://$projectId.supabase.co/rest/v1/streaks";
            $exists = checkStreakExists($baseUrl, $headers, array('sku' => $inputPayload['payload']['sku']));
            if ($exists) {
                http_response_code(403);
                echo json_encode(array("status" => "error", "message" => "Streak already exist with the same sku"));
                exit;
            } else {
                $translatedArr = array();
                foreach($langs as $lang){
                    $translatedArr[$lang]['name'] = translateText($inputPayload['payload']['name'], $lang);
                    $translatedArr[$lang]['description'] = translateText($inputPayload['payload']['description'], $lang);
                }
                $payloadToInsert = $inputPayload['payload'];
                $payloadToInsert['localizations'] = json_encode($translatedArr);
                $new = insertStreakAdmin($baseUrl, $headers, $payloadToInsert);
                echo json_encode(array("status" => "success", "message" => "Streak inserted succcesfully", "response" => $new));
            }
        break;
        case "update-streak-admin":
            // Update streak admin
            $baseUrl = "https://$projectId.supabase.co/rest/v1/streaks";
            $id = $inputPayload['id'];
            $exists = checkStreakExists($baseUrl, $headers, array('sku' => $inputPayload['payload']['sku'], 'id' => ['not.eq' => $id]));
            if ($exists) {
                http_response_code(403);
                echo json_encode(array("status" => "error", "message" => "Another streak exist with the same sku"));
                exit;
            } else {
                $translatedArr = array();
                foreach($langs as $lang){
                    $translatedArr[$lang]['name'] = translateText($inputPayload['payload']['name'], $lang);
                    $translatedArr[$lang]['description'] = translateText($inputPayload['payload']['description'], $lang);
                }
                $payloadToInsert = $inputPayload['payload'];
                $payloadToInsert['localizations'] = json_encode($translatedArr);
                $new = updateStreakAdmin($baseUrl, $headers, $id, $payloadToInsert);
                echo json_encode(array("status" => "success", "message" => "Streak updated succcesfully", "response" => $new));
            }
        break;
        case "delete-streak-admin":
            // Delete streak admin
            $baseUrl = "https://$projectId.supabase.co/rest/v1/streaks";
            $id = $inputPayload['id'];
            $new = deleteStreakAdmin($baseUrl, $headers, $id);
            echo json_encode(array("status" => "success", "message" => "Streak deleted succesfully"));
        break;

        // Admin milestones
        case "get-all-milestones-admin":
            // Read all milestones admin
            $baseUrl = "https://$projectId.supabase.co/rest/v1/milestones";
            $allMilestonesAdmin = getAllMilestonesAdmin($baseUrl, $headers);
            echo json_encode($allMilestonesAdmin);
        break;
        case "insert-milestone-admin":
            // Insert milestone admin
            $baseUrl = "https://$projectId.supabase.co/rest/v1/milestones";
            $exists = checkMilestoneExists($baseUrl, $headers, array('sku' => $inputPayload['payload']['sku'], 'streakSku' => $inputPayload['payload']['streakSku'], 'streakId' => $inputPayload['payload']['streakId']));
            if ($exists) {
                http_response_code(403);
                echo json_encode(array("status" => "error", "message" => "Milestone already exist with the same sku"));
                exit;
            } else {
                $translatedArr = array();
                foreach($langs as $lang){
                    $translatedArr[$lang]['name'] = translateText($inputPayload['payload']['name'], $lang);
                    $translatedArr[$lang]['description'] = translateText($inputPayload['payload']['description'], $lang);
                }
                $payloadToInsert = $inputPayload['payload'];
                $payloadToInsert['localizations'] = json_encode($translatedArr);
                $baseUrlStorage = "https://$projectId.supabase.co";
                $bucket = "firestorm";
                $imageUrl = uploadImageToStorage($baseUrlStorage, $apiKey, $bucket, $inputPayload['payload']['sku'], $inputPayloadFiles);
                $payloadToInsert['imageColoredUrl'] = $imageUrl;
                $new = insertMilestoneAdmin($baseUrl, $headers, $payloadToInsert);
                echo json_encode(array("status" => "success", "message" => "Milestone inserted succcesfully", "response" => $new));
            }
        break;
        case "update-milestone-admin":
            // Update milestone admin
            $baseUrl = "https://$projectId.supabase.co/rest/v1/milestones";
            $id = $inputPayload['id'];
            $exists = checkMilestoneExists($baseUrl, $headers, array('sku' => $inputPayload['payload']['sku'], 'streakSku' => $inputPayload['payload']['streakSku'], 'streakId' => $inputPayload['payload']['streakId'], 'id' => ['not.eq' => $id]));
            if ($exists) {
                http_response_code(403);
                echo json_encode(array("status" => "error", "message" => "Another milestone exist with the same sku"));
                exit;
            } else {
                $translatedArr = array();
                foreach($langs as $lang){
                    $translatedArr[$lang]['name'] = translateText($inputPayload['payload']['name'], $lang);
                    $translatedArr[$lang]['description'] = translateText($inputPayload['payload']['description'], $lang);
                }
                $payloadToInsert = $inputPayload['payload'];
                $payloadToInsert['localizations'] = json_encode($translatedArr);
                $baseUrlStorage = "https://$projectId.supabase.co";
                $bucket = "firestorm";
                $imageUrl = uploadImageToStorage($baseUrlStorage, $apiKey, $bucket, $inputPayload['payload']['sku'], $inputPayloadFiles);
                $payloadToInsert['imageColoredUrl'] = $imageUrl;
                $new = updateMilestoneAdmin($baseUrl, $headers, $id, $payloadToInsert);
                echo json_encode(array("status" => "success", "message" => "Milestone updated succcesfully", "response" => $new));
            }
        break;
        case "delete-milestone-admin":
            // Delete milestone admin
            $baseUrl = "https://$projectId.supabase.co/rest/v1/milestones";
            $id = $inputPayload['id'];
            $new = deleteMilestoneAdmin($baseUrl, $headers, $id);
            echo json_encode(array("status" => "success", "message" => "Milestone deleted succesfully"));
        break;

        // APIs for app
        case "log-a-streak":
            // Log a streak
            $baseUrl = "https://$projectId.supabase.co/rest/v1/streakLog";
            $payloadToInsert = $inputPayload['payload'];
            $lang = $payloadToInsert['lang'];
            unset($payloadToInsert['lang']);
            $baseUrlStreaks =  "https://$projectId.supabase.co/rest/v1/streaks";
            $getStreakData = getStreakData($baseUrlStreaks, $headers, array('appname' => $payloadToInsert['appname'], 'sku' => $payloadToInsert['streakSku']));
            if ($getStreakData) {
                if(isset($getStreakData[0]['streakType']) && $getStreakData[0]['streakType'] == 'daily'){
                    $existsTodayStreak = getStreakLogData($baseUrl, $headers, array('appname' => $payloadToInsert['appname'], 'userId' => $payloadToInsert['userId'], 'streakSku' => $payloadToInsert['streakSku']));
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
            $checkYesterdayStreakLogged = checkYesterdayStreakLogged($baseUrl, $headers, array('appname' => $payloadToInsert['appname'], 'userId' => $payloadToInsert['userId'], 'streakSku' => $payloadToInsert['streakSku']));
            if ($checkYesterdayStreakLogged) {
                $count = (int)$checkYesterdayStreakLogged[0]['count'] + 1;
            }
            else{
                $count = 1;
            }
            $payloadToInsert['count'] = $count;
            $new = logStreak($baseUrl, $headers, $payloadToInsert);
            $baseUrlMilestones = "https://$projectId.supabase.co/rest/v1/milestones";
            $checkAnyMilestoneExist = checkAnyMilestoneExist($baseUrlMilestones, $headers, array('streakSku' => $payloadToInsert['streakSku'], 'streakCount' => $count));
            if ($checkAnyMilestoneExist) {
                $baseUrlUserMilestones = "https://$projectId.supabase.co/rest/v1/userMilestones";
                $insertUserMileStonePayload = array(
                    "appname" => $payloadToInsert['appname'],
                    "userId" => $payloadToInsert['userId'],
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
        case "pause-resume-streak":
            // Pause or resume streak
            $baseUrl = "https://$projectId.supabase.co/rest/v1/streakPause";
            $payloadToInsert = $inputPayload['payload'];
            $checkPauseExist = checkPauseExist($baseUrl, $headers, array('appname' => $payloadToInsert['appname'], 'userId' => $payloadToInsert['userId']));
            if ($checkPauseExist) {
                if($checkPauseExist[0]['is_pause'] == "1"){
                    $id = $checkPauseExist[0]['id'];
                    $new = updatePauseResumeStreak($baseUrl, $headers, $id, array("is_pause" => "0"));
                    echo json_encode(array("status" => "success", "message" => "Streak paused succesfully", "response" => $new));
                }
                else{
                    $id = $checkPauseExist[0]['id'];
                    $new = updatePauseResumeStreak($baseUrl, $headers, $id, array("is_pause" => "1"));
                    echo json_encode(array("status" => "success", "message" => "Streak resumed succcesfully", "response" => $new));
                }
            }
            else{
                $new = pauseResumeStreak($baseUrl, $headers, $payloadToInsert);
                echo json_encode(array("status" => "success", "message" => "Streak paused succesfully", "response" => $new));
            }
        break;
        case "get-all-streaks":
            // Read all streaks for an app
            $payloadToInsert = $inputPayload['payload'];

            $baseUrlStreaks = "https://$projectId.supabase.co/rest/v1/streaks";
            $allStreaksApp = getAllStreaksApp($baseUrlStreaks, $headers, array('appname' => $payloadToInsert['appname']));

            $baseUrlStreakLogs = "https://$projectId.supabase.co/rest/v1/streakLog";
            $allStreakLogsApp = getAllStreakLogsApp($baseUrlStreakLogs, $headers, array('appname' => $payloadToInsert['appname'], 'userId' => $payloadToInsert['userId']));

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

            $baseUrlMilestones = "https://$projectId.supabase.co/rest/v1/milestones";
            $allMilestonesApp = getAllMilestonesApp($baseUrlMilestones, $headers, $payloadToInsert['appname']);
            if(isset($payloadToInsert['lang']) && $payloadToInsert['lang'] != "en"){
                $i = 0;
                foreach ($allMilestonesApp as $milestone) {
                    if (!empty($milestone['localizations'])) {
                        $localizations = json_decode($milestone['localizations'], true);
                        if (isset($localizations[$payloadToInsert['lang']])) {
                            $allMilestonesApp[$i]['name'] = $localizations[$payloadToInsert['lang']]['name'];
                            $allMilestonesApp[$i]['description'] = $localizations[$payloadToInsert['lang']]['description'];
                        }
                    }
                    $i++;
                }
            }

            $baseUrlStreaks = "https://$projectId.supabase.co/rest/v1/userMilestones";
            $allAchievedMilestonesOfUser = getAllAchievedMilestonesOfUser($baseUrlStreaks, $headers, array('appname' => $payloadToInsert['appname'], 'userId' => $payloadToInsert['userId']));
            $completedIds = array_column($allAchievedMilestonesOfUser, 'milestoneId');
            $milestones = array_map(function ($item) use ($completedIds) {
                $item['isCompleted'] = in_array((string)$item['id'], $completedIds) ? "1" : "0";
                return $item;
            }, $allMilestonesApp);
            usort($milestones, fn($a, $b) => $b['isCompleted'] <=> $a['isCompleted']);

            echo json_encode(array("streaks" => $allStreaks, "milestones" => $milestones));
        break;
        default:
            echo "No method choosed";
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
        $queryUrl = "$url?select=*,streaks!inner(appname)&streaks.appname=eq.$appname";

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
        $queryUrl = "$url?" . implode('&', $queryParts) . "&select=streakSku,count";

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
?>
