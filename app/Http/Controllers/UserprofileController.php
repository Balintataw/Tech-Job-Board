<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Response as Download;
use App\Profile;

class UserprofileController extends Controller
{

    protected function validateProfile(array $data) {
        // size is min in KB
        return Validator::make($data, [
            'bio' => 'min:10',
            'phone_number' => 'phone_number',
            'experience' => 'min:20'
        ]);
    }

    protected function validateImage(array $data) {
        // size is min in KB
        return Validator::make($data, [
            'image' => 'required|image|max:20000',
        ]);
    }

    protected function validateFile(array $data) {
        // size is min in KB
        return Validator::make($data, [
            'file' => 'required|mimes:pdf,doc,docx,txt|max:20000',
        ]);
    }

    public function index(Request $request) {
        $user = $request->user()->load('profile');
        return ['user' => $user];
    }

    public function update(Request $request) {

        $this->validateProfile($request->all())->validate();

        $user_id = auth()->user()->id;

        Profile::where('user_id', $user_id)->update([
            'address'=>$request['address'],
            'phone_number'=>$request['phone_number'],
            'experience'=>$request['experience'],
            'bio'=>$request['bio'],
            'gender'=>$request['gender'],
            'dob'=>$request['dob']
        ]);
        return response()->json([
            'message' => 'Profile updated successfully',
        ]);
    }

    public function avatar(Request $request) {

        $this->validateImage($request->all())->validate();

        $user_id = auth()->user()->id;

        $ext = $request->image->getClientOriginalExtension();
        $filename = time() . '.' . $ext;
        $avatar = Config::get('values.aws_bucket_url');
        // $avatar .= Storage::disk('s3')->put('avatars', $request->image);

        $avatar .= Storage::disk('s3')->putFileAs(
            'avatars', $request->image, $filename
        );

        Profile::where('user_id', $user_id)->update([
            'avatar'=>$avatar,
        ]);

        return response()->json([
            'message' => 'Avatar updated successfully',
            'url' => $avatar
        ]);
    }

    public function resume(Request $request) {

        $this->validateFile($request->all())->validate();

        $user_id = auth()->user()->id;

        $resume = Config::get('values.aws_bucket_url');
        $resume = Storage::disk('s3')->put('resumes', $request->file);

        Profile::where('user_id', $user_id)->update([
            'resume'=>$resume,
        ]);

        return response()->json([
            'message' => 'Resume updated successfully',
            'url' => $resume
        ]);
    }

    public function coverletter(Request $request) {

        $this->validateFile($request->all())->validate();

        $user_id = auth()->user()->id;

        $cover_letter = Config::get('values.aws_bucket_url');
        $cover_letter .= Storage::disk('s3')->put('coverletters', $request->file);

        Profile::where('user_id', $user_id)->update([
            'cover_letter'=>$cover_letter,
        ]);

        return response()->json([
            'message' => 'Cover letter updated successfully',
            'url' => $cover_letter
        ]);
    }

    public function download(Request $request) {
        // $file_path = 'resumes/Jossendal-Resume-June2020-word.docx';
        // $file_name = 'Jossendal-Resume-June2020-word.docx';
        // $file_path = 'resumes/RmLBrwRy8ZfpUpynw7ulpwhES7j4tzR97EqlTdoL.pdf';
        // $file_name = 'RmLBrwRy8ZfpUpynw7ulpwhES7j4tzR97EqlTdoL.pdf';
        $file_path = $request->path;
        if( Storage::disk('s3')->exists($file_path) ) {
            $file = Storage::disk('s3')->get($file_path);
            $url = Storage::disk('s3')->url($file_path);
            // $file = Storage::disk('s3')->temporaryUrl(
            //     $file_path, now()->addMinutes(5)
            // );
            // $info = pathinfo($file_path);
            // $ext = $info['extension'];
            // // $ext = Storage::disk('s3')->getFilename($file_path);
            // $getMimeType = Storage::disk('s3')->getMimetype($file_path);
            // $size = Storage::disk('s3')->getSize($file_path);
            // $newFileName = 'resumes.' . $ext; 

            // $headers = [
            //             'Content-type' => $getMimeType, 
            //             'Content-Disposition' => 'attachment; filename="'. $newFileName .'"',
            //             // 'Content-Disposition'=>sprintf('attachment; filename="%s"', $newFileName),
            //             // 'Content-Length' => $size,
            // ];
            return \Response::make(Storage::disk('s3')->temporaryUrl(
                $file_path,
                now()->addHour(),
                ['ResponseContentDisposition' => 'attachment']
            ));
        }
    }
}
