<?php
/**
 * Copyright (C) Console iT
 * This file is part of MUNPANEL System.
 *
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 *
 * Developed by Adam Yi <xuan@yiad.am>
 */

namespace App\Console;

use App\User;
use App\Delegate;
use App\Nation;
use App\Reg;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        $schedule->call(function() {
            User::where('telVerifications', '>', -1)->update(['telVerifications' => 15]);
        })->daily();
        $schedule->call(function() {
            $date = date_sub(date_create(), new \DateInterval('P3D'));
            $deelegates = Delegate::whereNotNull('nation_id')->where('seat_locked', false)->where('updated_at', '<', date('Y-m-d H:i:s', $date->getTimestamp()))->get()->pluck('reg_id');
            Delegate::whereIn('reg_id', $deelegates)->update(['seat_locked' => true]);
            // TODO: ADD EVENT
            $nations = Delegate::whereNotNull('nation_id')->where('seat_locked', false)->where('updated_at', '<', date('Y-m-d H:i:s', $date->getTimestamp()))->get()->pluck('nation_id');
            Nation::whereIn('id', $nations)->update(['status' => 'locked']);
            $regs = Reg::whereIn('id', $deelegates)->get();
            foreach($regs as $reg) 
            {
                $delegate = $reg->delegate;
                if ($delegate->committee_id != $delegate->nation->committee_id)
                    $reg->addEvent('committee_moved', '{"name":" MUNPANEL 自动","committee":"'.$delegate->nation->committee->display_name.'"}');
                $delegate->committee_id = $delegate->nation->committee_id;
                $delegate->save();
                $reg->addEvent('role_locked', '{"name":" MUNPANEL 自动"}');
            }
        })->hourly();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
