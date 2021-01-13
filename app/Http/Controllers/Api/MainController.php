<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ContactRequest;
use App\Models\Article;
use App\Models\BloodType;
use App\Models\Category;
use App\Models\City;
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

    public function getArticles(Request $request) {
        $articles = Article::with('category')->where(function ($query) use ($request){
            if($request->has('category_id')) {
                $query->where('category_id', $request->category_id);
            }
            if($request->has('keyword')) {
                $query->where('title', 'like', '%' . $request->keyword . '%')
                        ->orWhere('body', 'like', '%' . $request->keyword . '%');
            }
        })->latest()->paginate(10);
        return response()->json($articles, 200);
    }

    public function getGovernorates() {
        $governorates = Governorate::latest()->paginate(10);
        return response()->json($governorates, 200);
    }

    public function getCities(Request $request) {
        $cities = City::with('governorate')->where(function ($query) use ($request){
            if($request->has('governorate_id')) {
                $query->where('governorate_id', $request->governorate_id);
            }
            if($request->has('keyword')) {
                $query->where('name', 'like', '%' . $request->keyword . '%');
            }
        })->latest()->paginate(10);
        return response()->json($cities, 200);
    }

    public function getBloodTypes() {
        $bloodTypes = BloodType::latest()->paginate(10);
        return response()->json($bloodTypes, 200);
    }
}
