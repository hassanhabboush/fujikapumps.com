<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateContactRequest;
use App\Models\Contact;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(): View
    {
        return view('Pages.contact.contact');
    }

    public function show(Contact $contact): JsonResponse
    {
        return response()->json(['data' => [$contact]]);
    }

    public function update(UpdateContactRequest $request, Contact $contact): RedirectResponse
    {
        $contact->update($request->validated());

        // AppServiceProvider shares this globally; Contact's InvalidatesCache
        // covers it, but forget explicitly so the site never serves stale info.
        Cache::forget('contact');

        return redirect()->back()->with('status', 'Contact details updated.');
    }
}
