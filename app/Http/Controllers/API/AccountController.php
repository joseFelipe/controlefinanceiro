<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Account;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate as FacadesGate;
// import the Intervention Image Manager Class
use Intervention\Image\ImageManagerStatic as Image;

class AccountController extends Controller
{

  public function __construct()
  {
    $this->middleware('auth:api');
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    if (FacadesGate::allows('isAdmin') || FacadesGate::allows('isAuthor')) {
      return Account::latest()->paginate(10);
    }
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {

    $this->validate($request, [
      'name' => 'required|string|max:100',
    ]);

    return Account::create([
      'name' => $request['name'],
      'type' => $request['type'],
    ]);
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    $account = Account::findOrFail($id);

    $this->validate($request, [
      'name' => 'required|string|max:100',
    ]);

    $account->update($request->all());
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $this->authorize("isAdmin");

    $account = Account::findOrFail($id);

    $account->delete();
  }

  public function search(Request $request)
  {
    if ($search = $request->get('q')) {
      $accounts = Account::where(function ($query) use ($search) {
        $query->where('name', 'LIKE', "%$search%");
      })->paginate(10);
    } else {
      $accounts = Account::latest()->paginate(10);
    }
    return $accounts;
  }
}
