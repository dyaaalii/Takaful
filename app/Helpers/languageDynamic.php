<?php

use App\Models\Setting;

function getAllResourceFiles($dir, &$results = array()) {
    $files = scandir($dir);
    foreach ($files as $key => $value) {
        $path = $dir ."/". $value;
        if (!is_dir($path)) {
            $results[] = $path;
        } else if ($value != "." && $value != "..") {
            getAllResourceFiles($path, $results);
        }
    }
    return $results;
}

function getRegexBetween($content) {

    preg_match_all("%\{{__\(['|\"](.*?)['\"]\)}}%i", $content, $matches1, PREG_PATTERN_ORDER);
    preg_match_all("%\@lang\(['|\"](.*?)['\"]\)%i", $content, $matches2, PREG_PATTERN_ORDER);
    preg_match_all("%trans\(['|\"](.*?)['\"]\)%i", $content, $matches3, PREG_PATTERN_ORDER);
    $Alldata = [$matches1[1], $matches2[1], $matches3[1]];
    $data = [];
    foreach ($Alldata as  $value) {
        if(!empty($value)){
            foreach ($value as $val) {
                $data[$val] = $val;
            }
        }
    }
    return $data;
}

function generateLang($path = ''){

    // user panel
    $paths = getAllResourceFiles(resource_path('views/user'));
    $paths = array_merge($paths, getAllResourceFiles(resource_path('views/errors')));
    $paths = array_merge($paths, getAllResourceFiles(resource_path('views/test')));
    // end user panel

    // user validation
    $paths = getAllResourceFiles(app_path('Http/Controllers/User'));
    $paths = array_merge($paths, getAllResourceFiles(app_path('Http/Controllers/test')));
    $paths = array_merge($paths, getAllResourceFiles(app_path('Http/Controllers/Auth')));
    // end user validation

    // admin panel
     $paths = getAllResourceFiles(resource_path('views/admin'));
    // end admin panel

    // admin validation
    $paths = getAllResourceFiles(app_path('Http/Controllers/Admin'));
    // end validation
    $AllData= [];
    foreach ($paths as $key => $path) {
    $AllData[] = getRegexBetween(file_get_contents($path));
    }
    $modifiedData = [];
    foreach ($AllData as  $value) {
        if(!empty($value)){
            foreach ($value as $val) {
                $modifiedData[$val] = $val;
            }
        }
    }

    $modifiedData = var_export($modifiedData, true);
    file_put_contents('lang/en/admin_validation.php', "<?php\n return {$modifiedData};\n ?>");
    file_put_contents('lang/bn/admin_validation.php', "<?php\n return {$modifiedData};\n ?>");

}


function html_decode($text){
  $after_decode =  htmlspecialchars_decode($text, ENT_QUOTES);
  return $after_decode;
}





function uploadPrivateFile($file, $directory, $old_file = null) {

    $FILESYSTEM_DISK = 'local';

    $extension = $file->getClientOriginalExtension();

    $file_name = 'file-name-' . time() . rand(1000, 9999) . '.' . $extension;

    $file_path = $directory . '/' . $file_name;

    if ($FILESYSTEM_DISK == 's3') {

        Storage::disk('s3')->put($directory . '/' . $file_name, file_get_contents($file));

        if ($old_file) { Storage::disk('s3')->delete($old_file); }

    }elseif($FILESYSTEM_DISK == 'contabo'){

        Storage::disk('contabo')->put($directory . '/' . $file_name, file_get_contents($file));

        if ($old_file) { Storage::disk('contabo')->delete($old_file); }

    }elseif($FILESYSTEM_DISK == 'wasabi'){

        Storage::disk('wasabi')->put($directory . '/' . $file_name, file_get_contents($file));

        if ($old_file) { Storage::disk('wasabi')->delete($old_file); }

    }else {

        $privatePath = 'private/' . $directory;
        $destinationPath = storage_path('app/' . $privatePath);

        if (!file_exists($destinationPath)) { mkdir($destinationPath, 0755, true); }

        $file->move($destinationPath, $file_name);

        if ($old_file && file_exists(storage_path('app/private/' . $old_file))) {
            unlink(storage_path('app/private/' . $old_file));
        }
    }

    return $file_path;
}

function checkFileSystemDisk(){

    $FILESYSTEM_DISK = 'local';

    if(checkModule('CustomStorage')){
        try{

            $storage_data = Modules\CustomStorage\Entities\CustomStorageSetting::all();

            $storage_setting = array();

            foreach($storage_data as $data_item){
                $storage_setting[$data_item->key] = $data_item->value;
            }

            $storage_setting = (object) $storage_setting;



            if(($storage_setting->aws_status ?? '') == 'yes'){
                $FILESYSTEM_DISK = 's3';
            }elseif(($storage_setting->contabo_status ?? '') == 'yes'){
                $FILESYSTEM_DISK = 'contabo';
            }elseif(($storage_setting->wasabi_status ?? '') == 'yes'){
                $FILESYSTEM_DISK = 'wasabi';
            }else{
                $FILESYSTEM_DISK = 'local';
            }


        }catch(Exception $ex){
            Log::info($ex->getMessage());
        }
    }


    return $FILESYSTEM_DISK;

}

function uploadPublicFile($file, $directory, $old_file = null) {

    $FILESYSTEM_DISK = checkFileSystemDisk();

    $file_path = null;

    try{
        $extension = $file->getClientOriginalExtension();

        $file_name = 'file-name-' . time() . rand(1000, 9999) . '.' . $extension;

        $file_path = $directory . '/' . $file_name;

        if ($FILESYSTEM_DISK == 's3') {

            Storage::disk('s3')->put($directory . '/' . $file_name, file_get_contents($file));

            if ($old_file) { Storage::disk('s3')->delete($old_file); }

        }elseif($FILESYSTEM_DISK == 'contabo'){

            Storage::disk('contabo')->put($directory . '/' . $file_name, file_get_contents($file));

            if ($old_file) { Storage::disk('contabo')->delete($old_file); }

        }elseif($FILESYSTEM_DISK == 'wasabi'){

            Storage::disk('wasabi')->put($directory . '/' . $file_name, file_get_contents($file));

            if ($old_file) { Storage::disk('wasabi')->delete($old_file); }

        }else {

            $destinationPath = public_path($directory);

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $file->move($destinationPath, $file_name);

            if ($old_file && file_exists(public_path($old_file))) {
                unlink(public_path($old_file));
            }
        }

    }catch(Exception $ex){
        Log::info($ex->getMessage());
    }

    return $file_path;
}



function downloadFile($download_file, $custom_filename){

    $FILESYSTEM_DISK = checkFileSystemDisk();

    $file_extension = pathinfo($download_file, PATHINFO_EXTENSION);

    try{
        if($FILESYSTEM_DISK == 'local'){

            $file_path= public_path() . "/uploads/custom-images/".$download_file;

            if (File::exists($file_path)) {
                return Response::download($file_path, $custom_filename);
            }else {
                abort(404, 'File not found.');
            }

        }elseif($FILESYSTEM_DISK == 's3'){

            return Storage::disk('s3')->download($download_file, $custom_filename);

        }elseif($FILESYSTEM_DISK == 'contabo'){

            return Storage::disk('contabo')->download($download_file, $custom_filename);
        }elseif($FILESYSTEM_DISK == 'wasabi'){

            return Storage::disk('wasabi')->download($download_file, $custom_filename);
        }
    }catch(Exception $ex){
        Log::info($ex->getMessage());
        abort(404);
    }


}


function downloadPrivateFile($download_file, $custom_filename){
    $FILESYSTEM_DISK = checkFileSystemDisk();
    
    $file_extension = pathinfo($download_file, PATHINFO_EXTENSION);
    
    try{
        if($FILESYSTEM_DISK == 'local'){
            
            $file_path = storage_path('app/private/' . $download_file);
            $file_path_2 = storage_path('app/private/uploads/custom-images/' . $download_file);
            
            if (File::exists($file_path)) {
                return Response::download($file_path, $custom_filename);
            }elseif(File::exists($file_path_2)){
                return Response::download($file_path_2, $custom_filename);
            } else {
                abort(404, 'File not found.');
            }

        }elseif($FILESYSTEM_DISK == 's3'){

            return Storage::disk('s3')->download($download_file, $custom_filename);

        }elseif($FILESYSTEM_DISK == 'contabo'){

            return Storage::disk('contabo')->download($download_file, $custom_filename);
        }elseif($FILESYSTEM_DISK == 'wasabi'){

            return Storage::disk('wasabi')->download($download_file, $custom_filename);
        }

    }catch(Exception $ex){
        Log::info($ex->getMessage());
        abort(404);
    }


}



function deleteFile($file_name){

    $FILESYSTEM_DISK = checkFileSystemDisk();

    try{

        if($FILESYSTEM_DISK == 'local'){

            if ($file_name && file_exists(public_path($file_name))) {
                unlink(public_path($file_name));
            }

            if ($file_name && file_exists(storage_path('app/private/' . $file_name))) {
                unlink(storage_path('app/private/' . $file_name));
            }

        }elseif($FILESYSTEM_DISK == 's3'){

            Storage::disk('s3')->delete($file_name);

        }elseif($FILESYSTEM_DISK == 'contabo'){

            Storage::disk('contabo')->delete($file_name);

        }elseif($FILESYSTEM_DISK == 'wasabi'){

            Storage::disk('wasabi')->delete($file_name);
        }

    }catch(Exception $ex){
        Log::info($ex->getMessage());
        abort(404);
    }

}


function custom_asset($file_path){

    $FILESYSTEM_DISK = checkFileSystemDisk();
    
    $setting = Setting::findOrFail(1);

    $append_file_path = '';

    try{
        if ($FILESYSTEM_DISK == 's3') {
            $append_file_path = Storage::disk('s3')->url($file_path);
        }elseif($FILESYSTEM_DISK == 'contabo'){
            $append_file_path = Storage::disk('contabo')->url($file_path);
        }elseif($FILESYSTEM_DISK == 'wasabi'){
            $append_file_path = Storage::disk('wasabi')->url($file_path);
        }elseif($FILESYSTEM_DISK == 'local'){
            $append_file_path = ($file_path)? asset($file_path) : asset($setting->default_placeholder);
        }else{
            $append_file_path = ($file_path)? asset($file_path) : asset($setting->default_placeholder);
        }

    }catch(Exception $ex){
        Log::info($ex->getMessage());
        $append_file_path = asset($file_path);
    }


    return $append_file_path;
}


function checkModule($module_name){
    $json_module_data = file_get_contents(base_path('modules_statuses.json'));
    $module_status = json_decode($json_module_data);

    if(isset($module_status->$module_name) && $module_status->$module_name && File::exists(base_path('Modules').'/'.$module_name)){
        return true;
    }

    return false;

}
