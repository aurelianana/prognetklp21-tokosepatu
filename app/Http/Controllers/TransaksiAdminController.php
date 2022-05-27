<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use App\Notifications\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class TransaksiAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transactions = Transaction::with('user', 'courier', 'products')->get();
        return view('contents.admin.transaksi', compact('transactions'));
    }

    /**
     * To accept payment
     *
     * @param Transaction $transaction
     * @return void
     */
    public function acceptPayment(Transaction $transaction)
    {
        $transaction->update([
            'status' => 'verified'
        ]);

        $user = User::find($transaction->user_id);
        $message = "Hallo ".$user->name.", transaksi dengan ID : ".$transaction->id. " sudah dikonfirmasi oleh Pihak Toko";

        Notification::send($user, new UserNotification($message));


        return redirect()->route('admin.transaksi.index')->with('success', 'Transaction has been accepted');
    }

    public function updateShipped(Transaction $transaction)
    {
        $transaction->update([
            'status' => 'delivered'
        ]);

        $user = User::find($transaction->user_id);
        $message = "Hallo ".$user->name.", transaksi dengan ID : ".$transaction->id. " sedang dikirim oleh Pihak Toko";

        Notification::send($user, new UserNotification($message));

        return redirect()->route('admin.transaksi.index')->with('success', 'Transaction has been shipped');
    }


    public function cancelTransaction(Transaction $transaction)
    {
        $transaction->update([
            'status' => 'canceled'
        ]);

        $user = User::find($transaction->user_id);
        $message = "Hallo ".$user->name.", transaksi dengan ID : ".$transaction->id. " telah dibatalkan oleh Pihak Toko";

        Notification::send($user, new UserNotification($message));

        return redirect()->route('admin.transaksi.index')->with('success', 'Transaction has been cancelled');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}