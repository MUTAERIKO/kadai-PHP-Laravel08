<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Profile;
use App\Profilehistory;
use Carbon\Carbon;

class ProfileController extends Controller
{
    //
    public function add()
    {
        return view('admin.profile.create');
    }

    public function create(Request $request)
    {
        $this->validate($request, profile::$rules);
$profile = new Profile;
      $form = $request->all();
// フォームから送信されてきた_tokenを削除する
      unset($form['_token']);

 $profile->fill($form);
      $profile->save();

return redirect('admin/profile/create');
    }


public function edit(Request $request)
  {
      // News Modelからデータを取得する
      $profile = Profile::find($request->id);
      if (empty($profile)) {
        abort(404);    
      }
      return view('admin.profile.edit', ['profile_form' => $profile]);
  }


  public function update(Request $request)
  {
      //dd($request->id);
      // Validationをかける
      $this->validate($request, Profile::$rules);
      // News Modelからデータを取得する
      $profile = Profile::find($request->id);
      // 送信されてきたフォームデータを格納する
      $profile_form = $request->all();
      unset($profile_form['_token']);

      // 該当するデータを上書きして保存する
      $profile->fill($profile_form)->save();
      
      
      $history = new Profilehistory;
        $history->profile_id = $profile->id;
        $history->edited_at = Carbon::now();
        $history->save();

      return redirect('admin/profile');
  }
  
    public function index(Request $request)
  {
      $cond_name = $request->cond_name;
      if ($cond_name != '') {
          $posts = Profile::where('name', $cond_name)->get();
      } else {
          $posts = Profile::all();
      }
      return view('admin.profile.index', ['posts' => $posts, 'cond_name' => $cond_name]);
  }
  
  public function delete(Request $request)
  {
      // 該当するNews Modelを取得
      $profile = profile::find($request->id);
      // 削除する
      $profile->delete();
      return redirect('admin/profile/');
  }  
    
}
