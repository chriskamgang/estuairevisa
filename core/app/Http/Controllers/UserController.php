<?php

namespace App\Http\Controllers;


use App\Models\CheckoutLog;
use App\Models\Deposit;
use App\Models\Transaction;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Checkout;
use App\Models\Favorite;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function dashboard()
    {
        $data['pageTitle'] = "Dashboard";
        $data['user'] = Auth::user();
        $applaies = CheckoutLog::whereHas('checkout', function ($query) {
            $query->where('user_id', auth()->user()->id);
        });

        $data['total_applies'] = CheckoutLog::whereHas('checkout', function ($query) {
            $query->where('user_id', auth()->user()->id);
        })->count();
        $data['pending_applies'] = CheckoutLog::whereHas('checkout', function ($query) {
            $query->where('user_id', auth()->user()->id);
        })->where('status', 'pending')->count();
        $data['processing_applies'] = CheckoutLog::whereHas('checkout', function ($query) {
            $query->where('user_id', auth()->user()->id);
        })->where('status', 'proccessing')->count();
        $data['complete_applies'] = CheckoutLog::whereHas('checkout', function ($query) {
            $query->where('user_id', auth()->user()->id);
        })->where('status', 'complete')->count();
        $data['shipped_applies'] = CheckoutLog::whereHas('checkout', function ($query) {
            $query->where('user_id', auth()->user()->id);
        })->where('status', 'shipped')->count();

        $tickets = Ticket::where('user_id', auth()->user()->id);
        $data['total_ticket'] = $tickets->count();
        $data['pending_ticket'] = $tickets->where('status', 1)->count();
        $data['closed_ticket'] = $tickets->where('status', 2)->count();
        $pending_payment = Checkout::where('user_id', auth()->id())
            ->where('payment_status', 0)
            ->with('logs')
            ->get()
            ->flatMap(function ($checkout) {
                return $checkout->logs;
            })
            ->sum('price');

        $data['pending_payment'] = $pending_payment;
        $data['balance'] = auth()->user()->balance;
        $data['applies'] = CheckoutLog::whereHas('checkout', function ($query) {
            $query->where('user_id', auth()->user()->id);
        })->latest()->take(10)->get();
        $data['total_deposit'] = Deposit::where('user_id', auth()->user()->id)->where('payment_status', 1)->sum('amount');
        return view('frontend.user.dashboard')->with($data);
    }

    public function profile()
    {
        $pageTitle = 'Profile Edit';

        $user = auth()->user();

        return view('frontend.user.profile', compact('pageTitle', 'user'));
    }

    public function profileUpdate(Request $request)
    {


        $request->validate([
            'fname' => 'required',
            'lname' => 'required',
            'username' => 'required|unique:users,username,' . Auth::id(),
            'image' => 'sometimes|image|mimes:jpg,png,jpeg',
            'email' => 'required|unique:users,email,' . Auth::id(),
            'phone' => 'unique:users,id,' . Auth::id(),

        ], [
            'fname.required' => 'First Name is required',
            'lname.required' => 'Last Name is required',

        ]);

        $user = auth()->user();


        if ($request->hasFile('image')) {
          
            $filename = uploadImage($request->image, filePath('user'), old:$user->image);
            $user->image = $filename;
        }


        $data = [
            'country' => $request->country,
            'city' => $request->city,
            'zip' => $request->zip,
            'state' => $request->state,
        ];

        $user->fname = $request->fname;
        $user->lname = $request->lname;
        $user->username = $request->username;
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->address = $data;
        $user->save();



        $notify[] = ['success', 'Successfully Updated Profile'];

        return back()->withNotify($notify);
    }


    public function changePassword()
    {
        $pageTitle = 'Change Password';
        return view('frontend.user.auth.changepassword', compact('pageTitle'));
    }


    public function updatePassword(Request $request)
    {

        $request->validate([
            'oldpassword' => 'required|min:6',
            'password' => 'min:6|confirmed',

        ]);

        $user = User::find(Auth::id());

        if (!Hash::check($request->oldpassword, $user->password)) {
            return redirect()->back()->with('error', 'Old password do not match');
        } else {
            $user->password = bcrypt($request->password);

            $user->save();

            return redirect()->back()->with('success', 'Password Updated');
        }
    }


    public function transaction()
    {
        $pageTitle = "Transactions";

        $transactions = Transaction::where('user_id', auth()->id())->latest()->with('user')->paginate();

        return view('frontend.user.transaction', compact('pageTitle', 'transactions'));
    }

    public function transactionLog()
    {
        $pageTitle = 'Transaction Log';

        $transactions = Transaction::where('user_id', auth()->id())->where('payment_status', 1)->latest()->paginate();


        return view('frontend.user.transaction', compact('pageTitle', 'transactions'));
    }

    public function referral()
    {

        $parent = User::where('username', auth()->user()->username)->first();
        $user = getUserWithChildren($parent->id);
        return view('frontend.user.referral',compact('user'));
    }

    /**
     * Save FCM token for push notifications
     */
    public function saveFCMToken(Request $request)
    {
        $request->validate([
            'token' => 'required|string'
        ]);

        try {
            $user = auth()->user();
            $user->fcm_token = $request->token;
            $user->push_notification_enabled = 1;
            $user->save();

            \Log::info('FCM token saved successfully', [
                'user_id' => $user->id,
                'token' => substr($request->token, 0, 20) . '...'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Notifications activées avec succès!'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error saving FCM token', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de l\'activation des notifications'
            ], 500);
        }
    }

    // ============================================
    // FAVORITES MANAGEMENT
    // ============================================

    /**
     * Display user's favorites
     */
    public function favorites(Request $request)
    {
        $pageTitle = 'Mes Favoris';
        $user = auth()->user();

        $query = Favorite::where('user_id', $user->id)->with('favorable');

        // Filter by collection if provided
        if ($request->has('collection') && $request->collection) {
            $query->where('collection', $request->collection);
        }

        // Filter by type if provided
        if ($request->has('type') && $request->type) {
            $query->where('favorable_type', $request->type);
        }

        $favorites = $query->latest()->paginate(12);

        // Get unique collections for filtering
        $collections = Favorite::where('user_id', $user->id)
            ->whereNotNull('collection')
            ->distinct()
            ->pluck('collection');

        return view('frontend.user.favorites', compact('pageTitle', 'favorites', 'collections'));
    }

    /**
     * Add item to favorites
     */
    public function addFavorite(Request $request)
    {
        $request->validate([
            'favorable_type' => 'required|string',
            'favorable_id' => 'required|integer',
            'collection' => 'nullable|string|max:255'
        ]);

        try {
            $favorite = Favorite::firstOrCreate(
                [
                    'user_id' => auth()->id(),
                    'favorable_type' => $request->favorable_type,
                    'favorable_id' => $request->favorable_id
                ],
                [
                    'collection' => $request->collection
                ]
            );

            $notify[] = ['success', 'Ajouté aux favoris avec succès'];

            return back()->withNotify($notify);
        } catch (\Exception $e) {
            $notify[] = ['error', 'Erreur lors de l\'ajout aux favoris'];
            return back()->withNotify($notify);
        }
    }

    /**
     * Remove item from favorites
     */
    public function removeFavorite($id)
    {
        try {
            $favorite = Favorite::where('id', $id)
                ->where('user_id', auth()->id())
                ->firstOrFail();

            $favorite->delete();

            $notify[] = ['success', 'Retiré des favoris avec succès'];
            return back()->withNotify($notify);
        } catch (\Exception $e) {
            $notify[] = ['error', 'Favori introuvable'];
            return back()->withNotify($notify);
        }
    }

    /**
     * Remove multiple favorites
     */
    public function removeFavorites(Request $request)
    {
        $request->validate([
            'favorites' => 'required|array',
            'favorites.*' => 'integer'
        ]);

        try {
            Favorite::whereIn('id', $request->favorites)
                ->where('user_id', auth()->id())
                ->delete();

            $notify[] = ['success', count($request->favorites) . ' favoris supprimés'];
            return back()->withNotify($notify);
        } catch (\Exception $e) {
            $notify[] = ['error', 'Erreur lors de la suppression'];
            return back()->withNotify($notify);
        }
    }

    /**
     * Update favorite collection
     */
    public function updateFavoriteCollection(Request $request, $id)
    {
        $request->validate([
            'collection' => 'nullable|string|max:255'
        ]);

        try {
            $favorite = Favorite::where('id', $id)
                ->where('user_id', auth()->id())
                ->firstOrFail();

            $favorite->update(['collection' => $request->collection]);

            $notify[] = ['success', 'Collection mise à jour'];
            return back()->withNotify($notify);
        } catch (\Exception $e) {
            $notify[] = ['error', 'Favori introuvable'];
            return back()->withNotify($notify);
        }
    }

    // ============================================
    // RESERVATIONS (VISA APPLICATIONS) MANAGEMENT
    // ============================================

    /**
     * Display user's reservations/visa applications
     */
    public function reservations(Request $request)
    {
        $pageTitle = 'Mes Réservations';
        $user = auth()->user();

        $status = $request->get('status', 'all');

        $query = CheckoutLog::whereHas('checkout', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->with(['checkout']);

        // Filter by status
        if ($status !== 'all') {
            if ($status === 'active') {
                $query->whereIn('status', ['pending', 'proccessing']);
            } elseif ($status === 'completed') {
                $query->whereIn('status', ['complete', 'shipped']);
            } elseif ($status === 'cancelled') {
                $query->where('status', 'cancelled');
            }
        }

        $reservations = $query->latest()->paginate(15);

        // Statistics
        $stats = [
            'total' => CheckoutLog::whereHas('checkout', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->count(),
            'active' => CheckoutLog::whereHas('checkout', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->whereIn('status', ['pending', 'proccessing'])->count(),
            'completed' => CheckoutLog::whereHas('checkout', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->whereIn('status', ['complete', 'shipped'])->count(),
            'cancelled' => CheckoutLog::whereHas('checkout', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('status', 'cancelled')->count(),
        ];

        return view('frontend.user.reservations', compact('pageTitle', 'reservations', 'stats', 'status'));
    }

    /**
     * Cancel a reservation
     */
    public function cancelReservation($id)
    {
        try {
            $reservation = CheckoutLog::whereHas('checkout', function ($q) {
                $q->where('user_id', auth()->id());
            })->findOrFail($id);

            // Only allow cancellation if pending
            if (!in_array($reservation->status, ['pending', 'proccessing'])) {
                $notify[] = ['error', 'Cette réservation ne peut pas être annulée'];
                return back()->withNotify($notify);
            }

            $reservation->update(['status' => 'cancelled']);

            $notify[] = ['success', 'Réservation annulée avec succès'];
            return back()->withNotify($notify);
        } catch (\Exception $e) {
            $notify[] = ['error', 'Réservation introuvable'];
            return back()->withNotify($notify);
        }
    }

    // ============================================
    // NOTIFICATIONS MANAGEMENT
    // ============================================

    /**
     * Display notification center
     */
    public function notificationCenter(Request $request)
    {
        $pageTitle = 'Notifications';
        $user = auth()->user();

        $filter = $request->get('filter', 'all');
        $type = $request->get('type');

        $query = UserNotification::where('user_id', $user->id);

        // Filter by read/unread status
        if ($filter === 'unread') {
            $query->where('read', false);
        } elseif ($filter === 'read') {
            $query->where('read', true);
        }

        // Filter by type
        if ($type) {
            $query->where('type', $type);
        }

        $notifications = $query->latest()->paginate(20);

        // Get notification counts by type
        $counts = [
            'all' => UserNotification::where('user_id', $user->id)->count(),
            'unread' => UserNotification::where('user_id', $user->id)->where('read', false)->count(),
            'nouveaute' => UserNotification::where('user_id', $user->id)->where('type', 'nouveaute')->count(),
            'rappel' => UserNotification::where('user_id', $user->id)->where('type', 'rappel')->count(),
            'message' => UserNotification::where('user_id', $user->id)->where('type', 'message')->count(),
        ];

        return view('frontend.user.notifications', compact('pageTitle', 'notifications', 'counts', 'filter', 'type'));
    }

    /**
     * Mark notification as read
     */
    public function markNotificationAsRead($id)
    {
        try {
            $notification = UserNotification::where('id', $id)
                ->where('user_id', auth()->id())
                ->firstOrFail();

            $notification->markAsRead();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false], 404);
        }
    }

    /**
     * Mark notification as unread
     */
    public function markNotificationAsUnread($id)
    {
        try {
            $notification = UserNotification::where('id', $id)
                ->where('user_id', auth()->id())
                ->firstOrFail();

            $notification->markAsUnread();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false], 404);
        }
    }

    /**
     * Mark all notifications as read
     */
    public function markAllNotificationsAsRead()
    {
        try {
            UserNotification::where('user_id', auth()->id())
                ->where('read', false)
                ->update([
                    'read' => true,
                    'read_at' => now()
                ]);

            $notify[] = ['success', 'Toutes les notifications ont été marquées comme lues'];
            return back()->withNotify($notify);
        } catch (\Exception $e) {
            $notify[] = ['error', 'Erreur lors de la mise à jour'];
            return back()->withNotify($notify);
        }
    }

    /**
     * Delete notification
     */
    public function deleteNotification($id)
    {
        try {
            $notification = UserNotification::where('id', $id)
                ->where('user_id', auth()->id())
                ->firstOrFail();

            $notification->delete();

            $notify[] = ['success', 'Notification supprimée'];
            return back()->withNotify($notify);
        } catch (\Exception $e) {
            $notify[] = ['error', 'Notification introuvable'];
            return back()->withNotify($notify);
        }
    }

    /**
     * Delete all read notifications
     */
    public function deleteReadNotifications()
    {
        try {
            UserNotification::where('user_id', auth()->id())
                ->where('read', true)
                ->delete();

            $notify[] = ['success', 'Notifications lues supprimées'];
            return back()->withNotify($notify);
        } catch (\Exception $e) {
            $notify[] = ['error', 'Erreur lors de la suppression'];
            return back()->withNotify($notify);
        }
    }

    /**
     * Enhanced profile with statistics
     */
    public function profileWithStats()
    {
        $pageTitle = 'Mon Profil';
        $user = auth()->user();

        // Calculate user statistics
        $stats = [
            'total_visits' => CheckoutLog::whereHas('checkout', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->count(),
            'total_favorites' => Favorite::where('user_id', $user->id)->count(),
            'total_spent' => Checkout::where('user_id', $user->id)
                ->where('payment_status', 1)
                ->sum('total_amount'),
            'pending_payments' => Checkout::where('user_id', $user->id)
                ->where('payment_status', 0)
                ->count(),
            'unread_notifications' => UserNotification::where('user_id', $user->id)
                ->where('read', false)
                ->count(),
            'active_applications' => CheckoutLog::whereHas('checkout', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->whereIn('status', ['pending', 'proccessing'])->count(),
            'completed_applications' => CheckoutLog::whereHas('checkout', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->whereIn('status', ['complete', 'shipped'])->count(),
        ];

        return view('frontend.user.profile-stats', compact('pageTitle', 'user', 'stats'));
    }
}
