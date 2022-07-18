<?php


namespace App\Helpers;

use Request;

use App\Models\LogActivity as LogActivityModel;


class LogActivity

{


    public static function addToLog($subject, $action, $role)

    {

        $log = [];

        $log['subject'] = $subject;

        $log['action'] = $action;

        $log['role'] = $role;

        $log['url'] = Request::fullUrl();

        $log['method'] = Request::method();

        $log['ip'] = Request::ip();

        $log['agent'] = Request::header('user-agent');

        $log['users_id'] = auth()->check() ? auth()->user()->id : 1;

        LogActivityModel::create($log);

    }


    public static function logActivityLists()

    {

        return LogActivityModel::latest()->get();

    }


}