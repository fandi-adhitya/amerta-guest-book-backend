<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
class VisitorController extends Controller {
  
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index(Request $request) {
    $auth = Auth::user()->id;

    $type = $request->query('type');
    $limit = $request->query('limit');

    if(!$limit){
      $visitor = Visitor::where('user_id', $auth)
      ->when($type == 0, function ($q){
        $q->whereNotNull('updated_at');
      })
      ->when($type == 1, function ($q){
        $q->whereNull('updated_at');
      })
      ->when($type == 2, function ($q){
        $q;
      })
      ->orderBy('id', 'desc')
      ->get();
  
      $output = [
        'message' => 'Visitor Loaded',
        'data' => $visitor
      ];
  
      return response()->json($output, 200);
    }

    $visitor = Visitor::where('user_id', $auth)
      ->when($type == 1, function ($q){
        $q->whereNotNull('updated_at');
      })
      ->when($type == 2, function ($q){
        $q->whereNull('updated_at');
      })
      ->when($type == 3, function ($q){
        $q;
      })
      ->orderBy('id', 'desc')
      ->limit($limit)
      ->get();
  
      $output = [
        'message' => 'Visitor Loaded',
        'data' => $visitor
      ];
  
      return response()->json($output, 200);
  }

  public function create(Request $request){
    
    $this->validate($request, [
      'nama' => 'required',
      'alamat' => 'required'
    ]);
    
    $auth = Auth::user()->id;

    $create = Visitor::create([
      'nama' => $request->nama,
      'alamat' => $request->alamat,
      'status' => "Tidak hadir",
      'user_id' => $auth
    ]);

    $output = [
      'message' => "Visitor Created",
      'data' => $create
    ];

    return response()->json($output);
  }

  public function manual(Request $request){
    $this->validate($request, [
      'nama' => 'required',
      'alamat' => 'required'
    ]);
    
    $auth = Auth::user()->id;
    $now = Carbon::parse(Carbon::now())
      ->addSeconds(3)
      ->format('Y-m-d H:i:s');
    
    $date = date('Y-m-d H:i:s', strtotime('+3 sec'));

    $create = Visitor::create([
      'nama' => $request->nama,
      'alamat' => $request->alamat,
      'status' => "Hadir",
      'user_id' => $auth,
      'updated_at' => $now
    ]);

    $output = [
      'message' => "Manual Visitor Created",
      'data' => $create
    ];

    return response()->json($output);
  }

  public function scan($id) {
    $auth = Auth::user()->id;

    $find = Visitor::where('id', $id)->where('user_id', $auth)->first();

    if(!$find) {
      return response()->json([
        'message' => 'Tamu tidak ditemukan'
      ], 422);
    }

    $date = date('Y-m-d H:i:s', strtotime('+3 sec'));

    $find->update([
      'status' => "Hadir",
      'updated_at' => $date
    ]);
    
    return $find;
  }

  public function count(){
    $auth = Auth::user()->id;

    $count = Visitor::where("user_id", $auth)->count();
    
    $output = [
      "message" => 'Loaded',
      "data" => $count
    ];

    return response()->json($output, 200);
  }
}