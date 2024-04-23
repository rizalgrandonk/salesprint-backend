<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {
        $query = auth()->user()->notifications();
        $typeParam = $request->query('type');
        $limitParam = $request->query('limit');
        if ($typeParam) {
            if (strtolower($typeParam) === 'read') {
                $query->read();
            }
            if (strtolower($typeParam) === 'unread') {
                $query->unread();
            }
        }

        $notifications = $query->latest()->paginate($limitParam ?? 5);
        return $this->responseSuccess($notifications);
    }

    public function count(Request $request) {
        $query = auth()->user()->notifications();
        $typeParam = $request->query('type');
        if ($typeParam) {
            if (strtolower($typeParam) === 'read') {
                $query->read();
            }
            if (strtolower($typeParam) === 'unread') {
                $query->unread();
            }
        }
        $count = $query->count();
        return $this->responseSuccess(['count' => $count]);
    }
    public function mark_read(Request $request) {
        $updated_count = count(auth()->user()->unreadNotifications);
        auth()->user()->unreadNotifications->markAsRead();
        return $this->responseSuccess((['updated_count' => $updated_count]));
    }
}
