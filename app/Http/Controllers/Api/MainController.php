<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ContactRequest;
use App\Models\BloodType;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Governorate;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function createContact(ContactRequest $request) {
        $contact = Contact::create($request->all());
        return response()->json($contact, 200);
    }

    public function getCategories() {
        $categories = Category::latest()->paginate(10);
        return response()->json($categories, 200);
    }

    public function getGovernorates() {
        $governorates = Governorate::latest()->paginate(10);
        return response()->json($governorates, 200);
    }

    public function getBloodTypes() {
        $bloodTypes = BloodType::latest()->paginate(10);
        return response()->json($bloodTypes, 200);
    }
}
