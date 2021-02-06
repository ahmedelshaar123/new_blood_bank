<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ContactRequest;
use App\Http\Requests\Api\FavouriteRequest;
use App\Http\Requests\Api\DonateRequest;
use App\Models\Article;
use App\Models\BloodType;
use App\Models\Category;
use App\Models\City;
use App\Models\Contact;
use App\Models\DonationRequest;
use App\Models\Governorate;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Traits\FireBaseTrait;

class MainController extends Controller
{
    use FireBaseTrait;
    public function createContact(ContactRequest $request) {
        $contact = Contact::create($request->all());
        return response()->json($contact, 200);
    }

    public function toggleFavourites(FavouriteRequest $request) {
        $toggle = $request->user()->articles()->with('category')->toggle($request->article_id);
        return response()->json($toggle, 200);
    }

    public function myFavourites(Request $request) {
        $favourites = $request->user()->articles()->with('category')->latest()->paginate(10);
        return response()->json($favourites, 200);
    }

    public function myNotifications(Request $request) {
        $notifications = $request->user()->notifications()->with('donationRequest')->latest()->paginate(10);
        return response()->json($notifications, 200);
    }

    public function notificationsCount(Request $request) {
        $count = $request->user()->notifications()->where('is_read', 0)->count();
        return response()->json($count, 200);
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

    public function getArticle(Request $request) {
        $article = Article::findOrFail($request->article_id);
        if($article) {
            return response()->json($article->load('category'), 200);
        }else{
            return response()->json('المقال غير موجود', 404);
        }
    }

    public function getBloodTypes() {
        $bloodTypes = BloodType::latest()->paginate(10);
        return response()->json($bloodTypes, 200);
    }

    public function createDonationRequest(DonateRequest $request)
    {
        $donationRequest = $request->user()->donationRequests()->create($request->all())->load('city', 'city.governorate', 'bloodType',
                'client');
        $clientsIds = $donationRequest->city->governorate->clients()
            ->whereHas('bloodTypes', function ($q) use ($donationRequest) {
                $q->where('blood_types.id', $donationRequest->blood_type_id);
            })->pluck('clients.id')->toArray();

        if (count($clientsIds)) {
            $notification = $donationRequest->notification()->create([
                'title' => 'يوجد حالة تبرع قريبة منك',
                'body' => $donationRequest->bloodType->type . "أحتاج متبرع لفصيلة",
            ]);

            $notification->clients()->attach($clientsIds);
            $tokens = $request->user()->tokens()->where('token', '!=', null)->wherein('client_id', $clientsIds)->pluck('token')->toArray();

            if (count($tokens)) {
                $title = $notification->title;
                $body = $notification->body;
                $data = [
                    'donation_request_id' => $donationRequest->id
                ];
                $this->notifyByFirebase($title, $body, $tokens, $data);
            }
        }
        return response()->json($donationRequest, 200);

    }

    public function getDonationRequests(Request $request) {
        $donationRequests = DonationRequest::with('city', 'bloodType')->where(function ($query) use ($request){
            if($request->has('city_id')) {
                $query->where('city_id', $request->city_id);
            }
            if($request->has('blood_type_id')) {
                $query->where('blood_type_id', $request->blood_type_id);
            }
        })->latest()->paginate(10);
        return response()->json($donationRequests, 200);
    }

    public function getDonationRequest(Request $request) {
        $dontationRequest = DonationRequest::findOrFail($request->donation_request_id);
        if($dontationRequest) {
            $request->user()->notifications()->update(['is_read' => 1]);
            return response()->json($dontationRequest->load('city', 'bloodType'), 200);
        }else{
            return response()->json('طلب التبرع غير موجود', 404);
        }
    }

    public function getSettings() {
        $settings = Setting::latest()->paginate(10);
        return response()->json($settings, 200);
    }
}
