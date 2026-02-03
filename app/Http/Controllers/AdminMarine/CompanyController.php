<?php

namespace App\Http\Controllers\AdminMarine;

use App\Http\Controllers\Controller;   // INI BENAR
use App\Models\Company;
use Illuminate\Http\Request;


class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::all();
        return view('admin_marine.companies.index', compact('companies'));
    }

    public function create()
    {
        return view('admin_marine.companies.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'address' => 'required',
        ]);

        Company::create($request->only('name', 'address'));

        return redirect()->route('companies.index');
    }

    public function edit(Company $company)
    {
        return view('admin_marine.companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $request->validate([
            'name' => 'required',
            'address' => 'required',
        ]);

        $company->update($request->only('name', 'address'));

        return redirect()->route('companies.index');
    }

    public function destroy(Company $company)
    {
        $company->delete();
        return redirect()->route('companies.index');
    }
}
