<?php namespace Lasso\LegislativeLookup;

use System\Classes\PluginBase;

/**
 * LegislativeLookup Plugin Information File
 */
class Plugin extends PluginBase
{

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'LegislativeLookup',
            'description' => 'Refactored Legislative Lookup plugin',
            'author'      => 'Team Lasso - Dan',
            'icon'        => 'icon-leaf'
        ];
    }
    /**
     * @return array
     */
    public function registerComponents()
    {
        return [
            '\Lasso\LegislativeLookup\Components\Lookup' => 'lookup'
        ];
    }

    /**
     * @return array
     */
    public function registerSettings()
    {
        return [
            'settings' => [
                'label'       => 'LegislativeLookup',
                'description' => 'Manage Lookup API Keys and cache settings.',
                'category'    => 'LegislativeLookup',
                'icon'        => 'oc-icon-smile-o',
                'class'       => 'Lasso\LegislativeLookup\Models\Settings',
                'order'       => 500,
                'keywords'    => 'security location'
            ]
        ];
    }

    /**
     * Stolen shamelessly from dschultz's subscriber plugin
     */
    public function registerSchedule($schedule)
    {
        $schedule->call(function(){
            $now = date('Y-m-d H:i:s');
            $cache_timeout = Settings::get('cache_time')*24*60*60;

            $addresses = Db::select('select * from lasso_legislativelookup_address');
            foreach($addresses as $val){
                $checkIn = $val->created_at;
                if(strtotime($now) - strtotime($checkIn) >  $cache_timeout){
                    Db::delete('delete from lasso_legislativelookup_address where id = "'.$val->id.'"');
                }
            }

            $legislators = Db::select('select * from lasso_legislativelookup_legislators');
            foreach($legislators as $val){
                $checkIn = $val->created_at;
                if(strtotime($now) - strtotime($checkIn) >  $cache_timeout){
                    Db::delete('delete from lasso_legislativelookup_legislators where id = "'.$val->id.'"');
                }
            }
        })->weekly()->sundays()->at('00:01');
    }
}
