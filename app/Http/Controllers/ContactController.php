<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    public function index()
    {
        return view('Pages.contact.contact');
    }
   
     public function getcontact ($id) // to show customer details
    {
        $contact = Contact::findOrFail($id);
        return response()->json(['data' => [$contact]]);
    }
      public function edit(Request $request)
    {
        
        Contact::findOrFail(1)->update([
            'Facebook'  => $request->input('facebook'),
            'Twitter'   => $request->input('twitter'),
            'Linkedin'  => $request->input('Linkedin'),
            'instagram' => $request->input('Instagram'),
            'whatsapp'  => $request->input('Whatsapp'),
            'email'     => $request->input('Email'),
            'phone1'    => $request->input('phone1'),
            'phon2'     => $request->input('phone2'),
            'address'   => $request->input('address'),
        ]);
    return redirect()->back();
    }
}
