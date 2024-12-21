<?php

namespace App\Http\Controllers;
use Mail;
use App\Mail\SendEmail;
use Illuminate\Http\Request;
use App\Jobs\SendMailJob;
// use Illuminate\Support\Facades\Mail;

class SendEmailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $content = [
        //     'name' => 'Ini Nama Pengirim',
        //     'subject' => 'Ini subject email',
        //     'body' => 'ini adalah isi email yang dikirim dari laravel 10'
        // ];

        // Mail::to('syahlanaiimah19@gmail.com')->send(new SendEmail($content));
        // return "Email berhasil dikirim.";
        return view('emails.kirim-email');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();

        dispatch(new SendMailJob($data));
        return redirect()->route('kirim-email')
        ->with('success', 'Email berhasil dikirim');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
