<?php

namespace App\Providers;

use Auth;
use View;
use Session;
use Exception;
use Log;
use App\Models\Setting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Modules\CustomStorage\Entities\CustomStorageSetting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        try{

            if(checkModule('CustomStorage')){

                $storage_data = CustomStorageSetting::all();

                $storage_setting = array();

                foreach($storage_data as $data_item){
                    $storage_setting[$data_item->key] = $data_item->value;
                }

                $storage_setting = (object) $storage_setting;

                $FILESYSTEM_DISK = 'local';

                if(($storage_setting->aws_status ?? '') == 'yes'){
                    $FILESYSTEM_DISK = 's3';

                    Config::set('filesystems.disks.s3.key', $storage_setting->aws_access_key_id ?? '');
                    Config::set('filesystems.disks.s3.secret', $storage_setting->aws_secret_access_key ?? '');
                    Config::set('filesystems.disks.s3.region', $storage_setting->aws_region ?? '');
                    Config::set('filesystems.disks.s3.bucket', $storage_setting->aws_bucket ?? '');
                    Config::set('filesystems.disks.s3.url', $storage_setting->aws_url ?? '');

                }elseif(($storage_setting->contabo_status ?? '') == 'yes'){
                    $FILESYSTEM_DISK = 'contabo';

                    Config::set('filesystems.disks.contabo.key', $storage_setting->contabo_access_key_id ?? '');
                    Config::set('filesystems.disks.contabo.secret', $storage_setting->contabo_secret_access_key ?? '');
                    Config::set('filesystems.disks.contabo.region', $storage_setting->contabo_region ?? '');
                    Config::set('filesystems.disks.contabo.bucket', $storage_setting->contabo_bucket ?? '');
                    Config::set('filesystems.disks.contabo.url', $storage_setting->contabo_url ?? '');
                    Config::set('filesystems.disks.contabo.endpoint', $storage_setting->contabo_endpoint ?? '');

                }elseif(($storage_setting->wasabi_status ?? '') == 'yes'){
                    $FILESYSTEM_DISK = 'wasabi';

                    Config::set('filesystems.disks.wasabi.key', $storage_setting->wasabi_access_key ?? '');
                    Config::set('filesystems.disks.wasabi.secret', $storage_setting->wasabi_secret_key ?? '');
                    Config::set('filesystems.disks.wasabi.region', $storage_setting->wasabi_default_region ?? '');
                    Config::set('filesystems.disks.wasabi.bucket', $storage_setting->wasabi_bucket ?? '');
                    Config::set('filesystems.disks.wasabi.endpoint', $storage_setting->wasabi_url ?? '');

                }else{
                    $FILESYSTEM_DISK = 'local';
                }

            }

        }catch(Exception $ex){
            Log::info($ex->getMessage());
        }


        View::composer('*', function($view){
            $setting = Setting::first();
            $view->with('setting', $setting);
            $view->with('default_avatar', $setting->default_avatar?? null);
            $view->with('currency', $setting->currency_icon?? null);
        });
    }
}
