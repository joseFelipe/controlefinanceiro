<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate as FacadesGate;

class TransactionController extends Controller
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
      // return Category::latest()->paginate(10);

      $transactions = DB::table('transactions')->select('transactions.*')->get();

      return $transactions;

      // $categories = DB::table('categories')
      //   ->select('categories.*')
      //   ->where('parent_id', '=', 0)
      //   ->orderby('name', 'asc')
      //   ->get();

      // $subCategories = DB::table('categories')
      //   ->select('categories.*')
      //   ->where('parent_id', '<>', 0)
      //   ->orderby('name', 'asc')
      //   ->get();

      // return ["categories" => $categories, "subcategories" => $subCategories];
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

    // $this->validate($request, [
    //   'name' => 'required|string|max:100',
    // ]);

    // $category = new Category;

    // $category->name = $request["name"];
    // $category->color = $request["color"];
    // $category->parent_id = $request["parentCategory"];
    // $category->save();

    // return ["message" => "subcategory success"];
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
    // $category = Category::findOrFail($id);

    // $this->validate($request, [
    //   'name' => 'required|string|max:100',
    // ]);

    // $category->update($request->all());
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    // $this->authorize("isAdmin");

    // $category = Category::findOrFail($id);

    // $category->delete();
  }

  public function search(Request $request)
  {
    // if ($search = $request->get('q')) {
    //   $categories = Category::where(function ($query) use ($search) {
    //     $query->where('name', 'LIKE', "%$search%");
    //   })->paginate(10);
    // } else {
    //   $categories = Category::latest()->paginate(10);
    // }
    // return $categories;
  }
}
