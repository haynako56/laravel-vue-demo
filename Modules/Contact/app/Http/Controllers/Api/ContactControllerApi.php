<?php

namespace Modules\Contact\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Modules\Contact\Entities\Contact;
use Illuminate\Support\Facades\Storage;

class ContactControllerApi extends Controller
{
    public function index()
    {
        $contacts = Contact::all();
        return response()->json($contacts);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:contacts,email',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('contacts', 'public');
        }

        return Contact::create($validated);
    }

    public function update(Request $request, Contact $contact)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => "required|email|unique:contacts,email,{$contact->id}",
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($contact->image && Storage::disk('public')->exists($contact->image)) {
                Storage::disk('public')->delete($contact->image);
            }

            $validated['image'] = $request->file('image')->store('contacts', 'public');
        }

        $contact->update($validated);
        return $contact;
    }

    public function destroy(Contact $contact)
    {
        if ($contact->image && Storage::disk('public')->exists($contact->image)) {
            Storage::disk('public')->delete($contact->image);
        }

        $contact->delete();
        return response()->json(['success' => true]);
    }
}
