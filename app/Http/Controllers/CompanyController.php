<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Company;

class CompanyController extends Controller
{

    /**
     * Create a new CompanyController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('employer', ['except' => ['index']]);
    }

    protected function validateProfile(array $data) {
        // size is min in KB
        return Validator::make($data, [
            'slogan' => 'max:100',
            'phone' => 'phone_number',
            'description' => 'max:300'
        ]);
    }

    protected function validateImage(array $data) {
        // size is min in KB
        return Validator::make($data, [
            'image' => 'required|image|max:40000',
        ]);
    }

    public function index($id, Company $company) {
        $company['jobs'] = $company->jobs;
        return ['company' => $company];
    }

    public function profile() {
        $user_id = auth()->user()->id;
        $company = Company::where('user_id', $user_id)->firstOrFail();
        return ['company' => $company];
    }

    public function update(Request $request) {

        $this->validateProfile($request->all())->validate();

        $user_id = auth()->user()->id;

        Company::where('user_id', $user_id)->update([
            'address'=>$request['address'],
            'phone'=>$request['phone'],
            'website'=>$request['website'],
            'slogan'=>$request['slogan'],
            'description'=>$request['description'],
        ]);
        return response()->json([
            'message' => 'Profile updated successfully',
        ]);
    }

    public function coverphoto(Request $request) {

        $this->validateImage($request->all())->validate();

        $user_id = auth()->user()->id;

        $coverphoto = Config::get('values.aws_bucket_url');
        $coverphoto .= Storage::disk('s3')->put('company/photos', $request->image);

        Company::where('user_id', $user_id)->update([
            'cover_photo'=>$coverphoto,
        ]);

        return response()->json([
            'message' => 'Cover photo updated successfully',
            'url' => $coverphoto
        ]);
    }

    public function logo(Request $request) {

        $this->validateImage($request->all())->validate();

        $user_id = auth()->user()->id;

        $logo = Config::get('values.aws_bucket_url');
        $logo .= Storage::disk('s3')->put('company/logos', $request->image);

        Company::where('user_id', $user_id)->update([
            'logo'=>$logo,
        ]);

        return response()->json([
            'message' => 'Logo updated successfully',
            'url' => $logo
        ]);
    }
}
