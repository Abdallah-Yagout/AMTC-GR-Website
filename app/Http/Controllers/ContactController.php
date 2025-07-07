<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;
use Illuminate\Support\Facades\Config;

class ContactController extends Controller
{
    public function show()
    {
        return view('contact');
    }




    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|min:10|max:2000',
        ], [
            'name.required' => __('The name field is required.'),
            'email.required' => __('The email field is required.'),
            'email.email' => __('Please enter a valid email address.'),
            'message.required' => __('The message field is required.'),
            'message.min' => __('The message must be at least :min characters.'),
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {


            // Configure custom SMTP settings
            Config::set('mail.mailers.contact_form.host', 'smtp.hostinger.com'); // Your SMTP host
            Config::set('mail.mailers.contact_form.port', 465); // Your SMTP port
            Config::set('mail.mailers.contact_form.username', 'contact@gryemen.com'); // Your SMTP username
            Config::set('mail.mailers.contact_form.password', 'A-x123123'); // Your SMTP password
            Config::set('mail.contact-from.address', 'contact@gryemen.com'); // From address
            Config::set('mail.contact-from.name', 'Your Website Name'); // From name

            // Send email
            Mail::to('contact@gryemen.com')->send(new ContactFormMail($request->all()));

            return redirect()->back()
                ->with('success', __('Your message has been sent successfully!'));
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()
                ->with('error', __('An error occurred while saving your message. Please try again later.'))
                ->withInput();
        }
    }
}
