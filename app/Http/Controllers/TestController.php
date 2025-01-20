<?php

namespace App\Http\Controllers;

use App\Http\Requests\TestRequest;
use App\Http\Resources\TestResource;
use App\Models\Test;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index(Request $request)
    {
        $tests = TestResource::collection(Test::latest()->when($request->input('search'), function ($query,$search){
            $query->where('name','like',"%{$search}%");
        })->paginate(10)->withQueryString());
        return inertia()->render('Test/Index',['tests' => $tests,'filters' => $request->only(['search'])]);
    }

    public function create()
    {
        return inertia()->render('Test/Add');
    }

    public function store(TestRequest $request)
    {
        $test = Test::create($request->only('name'));

        if (!$test){
            return back()->with('error', 'Test Not Created Successfully');
        }

        return back()->with('success', 'Test Created Successfully');

    }

    public function update($id,TestRequest $request)
    {
        $test = Test::find($id);
        $test->update($request->only('name'));

        return back()->with('success', 'Test Updated Successfully');

    }
    public function destroy($id)
    {
        $test = Test::find($id);
        $test->delete();

        return back()->with('success', 'Test deleted Successfully');

    }
}
