<?php

namespace App\Http\Controllers\Company;

use App\Models\Company;
use App\Http\Requests\CompanyStoreRequest;
use App\Http\Requests\CompanyUpdateRequest;
use App\Http\Resources\CompanyCollection;
use App\Http\Resources\CompanyResource;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use Inertia\Response;

class CompanyController extends \App\Http\Controllers\Controller
{
    public function index(): Response
    {
        $companies = Company::query()
            ->withCount('employees')
            ->orderBy('created_at', 'desc')
            ->filter(Request::only('search', 'trashed'))
            ->paginate(Request::input('per_page', 5))
            ->withQueryString();
    
        return Inertia::render('Company/Dashboard', [
            'filters' => Request::all('search', 'trashed'),
            'companies' => new CompanyCollection($companies),
            'totalCompanies' => $companies->total(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cname' => 'required|string|max:255',
            'cphone_number' => 'required|string|max:20',
            'cemail' => 'required|email|unique:company,cemail',
        ]);

        Company::create($validated);

        return redirect()->route('company.index');
    }

    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'cname' => 'required|string|max:255',
            'cphone_number' => 'required|string|max:20',
            'cemail' => 'required|email|unique:company,cemail,' . $company->id,
        ]);

        $company->update($validated);

        return redirect()->route('company.index');
    }

    public function destroy(Company $company)
    {
        $company->update(['deleted_at' => now()]);
        
        return redirect()->route('company.index');
    }
}