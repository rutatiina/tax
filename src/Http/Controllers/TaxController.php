<?php

namespace Rutatiina\Tax\Http\Controllers;

use Illuminate\Support\Facades\Request as FacadesRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Rutatiina\FinancialAccounting\Models\Account;
use Rutatiina\Tax\Models\Tax;
use Rutatiina\Item\Traits\ItemsVueSearchSelect;
use Illuminate\Support\Str;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;

class TaxController extends Controller
{
    use ItemsVueSearchSelect; //calls AccountingTrait

    public function __construct()
    {
        $this->middleware('permission:taxes.view');
        $this->middleware('permission:taxes.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:taxes.update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:taxes.delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
	{
        //load the vue version of the app
        if (!FacadesRequest::wantsJson()) {
            return view('ui.limitless::layout_2-ltr-default.appVue');
        }

        $per_page = ($request->per_page) ? $request->per_page : 20;

        $query = Tax::query();

        if ($request->search)
        {
            $query->where(function($q) use ($request) {
                $columns = (new Tax)->getSearchableColumns();
                foreach($columns as $column)
                {
                    $q->orWhere($column, 'like', '%'.Str::replace(' ', '%', $request->search).'%');
                }
            });
        }

        $query->with(['on_sale_account', 'on_bill_account']);
        $taxes = $query->paginate(15);

        return [
            'tableData' => $taxes
        ];

	}

    public function create(Request $request)
	{
        //load the vue version of the app
        if (!FacadesRequest::wantsJson()) {
            return view('ui.limitless::layout_2-ltr-default.appVue');
        }

        $Tax = new Tax;
        $attributes = $Tax->rgGetAttributes();
        $attributes['_method'] = 'POST';

        $data = [
            'pageTitle' => 'Create Tax', #required
            'pageAction' => 'Create', #required
            'urlPost' => '/taxes', #required
            'attributes' => $attributes, #required
            'taxes' => Tax::all(),
            'accounts' => Account::all(),
        ];

        return $data;

    }

    public function store(Request $request)
	{
        //return $request;

        $rules = [
            'name' => ['required', 'string', 'max:100'],
            'display_name' => ['required', 'string', 'max:30'],
            'value' => ['required', 'string'],
            'based_on' => ['in:item,total'],
            'on_sale_effect' => ['required_with:on_sale', 'in:debit,credit', 'nullable'],
            'on_sale_financial_account_code' => ['required_with:on_sale', 'integer', 'nullable'],
            'on_bill_effect' => ['required_with:on_bill', 'in:debit,credit', 'nullable'],
            'on_bill_financial_account_code' => ['required_with:on_bill', 'integer', 'nullable'],
            'enforceable_from' => ['required', 'date', 'nullable'],
            'abolished_on' => ['required', 'date', 'nullable'],
        ];

        $request->validate($rules);

        $tax                    = new Tax;
        $tax->tenant_id         = Auth::user()->tenant->id;
        $tax->name              = $request->name;
        $tax->display_name      = $request->display_name;
        $tax->country           = $request->country;
        $tax->value             = $request->value;
        $tax->based_on          = $request->based_on;
        $tax->inclusive         = ($request->inclusive) ? $request->inclusive : 0;
        $tax->on_sale           = ($request->on_sale) ? $request->on_sale : 0;
        $tax->on_sale_effect    = $request->on_sale_effect;
        $tax->on_sale_financial_account_code   = $request->on_sale_financial_account_code;
        $tax->on_bill           = ($request->on_bill) ? $request->on_bill : 0;
        $tax->on_bill_effect    = $request->on_bill_effect;
        $tax->on_bill_financial_account_code   = $request->on_bill_financial_account_code;
        $tax->enforceable_from  = $request->enforceable_from;
        $tax->abolished_on      = $request->abolished_on;
        $tax->save();

        return [
            'status' => true,
            'messages' => ['Tax successfully saved.'],
            'callback' => '/taxes'
        ];
	}

    public function show($id)
	{}

    public function edit($id)
	{
        //load the vue version of the app
        if (!FacadesRequest::wantsJson()) {
            return view('ui.limitless::layout_2-ltr-default.appVue');
        }

        $Tax = Tax::find($id);
        $attributes = $Tax->toArray();
        $attributes['_method'] = 'PATCH';

        $data = [
            'pageTitle' => 'Update Tax', #required
            'pageAction' => 'Update', #required
            'urlPost' => '/taxes/'.$id, #required
            'attributes' => $attributes, #required
            'accounts' => Account::all(),
            'taxes' => Tax::all(),
        ];

        return $data;

    }

    public function update(Request $request)
	{
	    //return $request->on_bill;

        $rules = [
            'name' => ['required', 'string', 'max:100'],
            'display_name' => ['required', 'string', 'max:30'],
            'value' => ['required', 'string'],
            'based_on' => ['in:item,total'],


            'enforceable_from' => ['required', 'date', 'nullable'],
            'abolished_on' => ['required', 'date', 'nullable'],
        ];

        //this if statement is because the on_sale is save as 0 if column is not checked
        if ($request->on_sale != 0)
        {
            $rules['on_sale_effect'] = ['required_with:on_sale', 'in:debit,credit', 'nullable'];
            $rules['on_sale_financial_account_code'] = ['required_with:on_sale', 'integer', 'nullable'];
        }

        //this if statement is because the on_bill is save as 0 if column is not checked
        if ($request->on_bill != 0)
        {
            $rules['on_bill_effect'] = ['required_with:on_bill', 'in:debit,credit', 'nullable'];
            $rules['on_bill_financial_account_code'] = ['required_with:on_bill', 'integer', 'nullable'];
        }

        $request->validate($rules);

        $tenant = Auth::user()->tenant;

        $tax = Tax::find($request->id);

        if($tax->tenant_id != $tenant->id) {
            return ['status' => false, 'messages' => ['Tax not found or you don\'t has rights to edit this.']];
        }

        $tax->tenant_id         = $tenant->id;
        $tax->name              = $request->name;
        $tax->display_name      = $request->display_name;
        $tax->country           = $request->country;
        $tax->value             = $request->value;
        $tax->based_on          = $request->based_on;
        $tax->inclusive         = $request->inclusive;
        $tax->on_sale           = $request->on_sale;
        $tax->on_sale_effect    = $request->on_sale_effect;
        $tax->on_sale_financial_account_code = $request->on_sale_financial_account_code;
        $tax->on_bill           = $request->on_bill;
        $tax->on_bill_effect    = $request->on_bill_effect;
        $tax->on_bill_financial_account_code = $request->on_bill_financial_account_code;
        $tax->enforceable_from  = $request->enforceable_from;
        $tax->abolished_on      = $request->abolished_on;
        $tax->save();

        return [
            'status' => true,
            'messages' => ['Tax successfully Updates.']
        ];
    }

    public function destroy($id)
	{
		$tax = Tax::find($id);

        if($tax->delete()) {
            return redirect()->back()->with('success', 'Tax successfully deleted');
        }
	}

    public function selectOptions(Request $request)
    {
        $per_page = ($request->per_page) ? $request->per_page : 20;

        return Tax::paginate($per_page);

    }
}
