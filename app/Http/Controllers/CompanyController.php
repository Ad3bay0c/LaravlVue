<?php

namespace App\Http\Controllers;

use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }
    public function index()
    {
        //
        return response()->json(Company::orderBy('id', 'desc')->paginate(10));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'name' => 'required',
        ]);
        $filename = '';
        if ($request->file()) {
            $request->validate([
                'file' => 'mimes:png,jpeg,jpg,PNG,JPG,JPEG|max:2048'
            ]);
            $filename = time() . '_' . $request->file('file')->getClientOriginalName();
            $request->file('file')->storeAs('uploads', $filename, 'public');
        }

        Company::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'logo' => $filename,
            'website' => $request->input('website')
        ]);

        return response()->json(['message' => 'Created Successfully'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        //
        // $company = Company::where('id', $company->id)->first();

        return new CompanyResource($company);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        //
        $request->validate([
            'name' => 'required'
        ]);

        Company::where('id', $company->id)->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'website' => $request->input('website'),
        ]);
        return response()->json([
            'message' => 'Updated Successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        //
        $company->delete();

        return response()->json([
            'message' => 'Deleted Successfully'
        ]);
    }
}
