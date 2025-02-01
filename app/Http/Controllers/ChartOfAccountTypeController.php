<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccountType;
use App\Models\ChartOfAccountSubType;
use Illuminate\Http\Request;

class ChartOfAccountTypeController extends Controller
{

    public function index()
    {
        if(\Auth::user()->can('manage constant chart of account type'))
        {
            $types = ChartOfAccountType::where('created_by', '=', \Auth::user()->creatorId())->get();
            $account_subtype = [];
            foreach ($types as $type) {
                $typeName=$type->name;
                $typeId=$type->id;
                $accountTypes = ChartOfAccountSubType::where('type', $type->id)->where('created_by',\Auth::user()->creatorId())->get();
                $temp=[];
                foreach($accountTypes as $key=>$accountType)
                {
                    $temp[$key]['id'] = $accountType->id;
                    $temp[$key]['typeId'] = $accountType->type;
                    $temp[$key]['name'] = $accountType->name;
                }

                $account_subtype[$type->name] = $temp;
                // $account_subtype[$type->name]['typeId'] = $typeId;
            }
        // print_r($account_subtype);
        // die;
            return view('chartOfAccountType.index', compact('account_subtype'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {
        return view('chartOfAccountType.create');
    }

    public function createSubType()
    {
        $account_type = ChartOfAccountType::where('created_by',\Auth::user()->creatorId())->get()->pluck('name', 'id');
        return view('chartOfAccountType.accountsubtype', compact('account_type'));  
    }

    public function store(Request $request)
    {
        if(\Auth::user()->can('create constant chart of account type'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $account             = new ChartOfAccountType();
            $account->name       = $request->name;
            $account->created_by = \Auth::user()->creatorId();
            $account->save();

            return redirect()->route('chart-of-account-type.index')->with('success', __('Chart of account type successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function show(ChartOfAccountType $chartOfAccountType)
    {
        //
    }


    public function edit(ChartOfAccountType $chartOfAccountType)
    {
        return view('chartOfAccountType.edit', compact('chartOfAccountType'));
    }

    
    public function subTypeEdit($id)
    {
       $chartOfAccountSubType= chartOfAccountSubType::find($id);
        return view('chartOfAccountType.subTypeEdit', compact('chartOfAccountSubType'));
    }


    public function update(Request $request, ChartOfAccountType $chartOfAccountType)
    {
        if(\Auth::user()->can('edit constant chart of account type'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $chartOfAccountType->name = $request->name;
            $chartOfAccountType->save();

            return redirect()->route('chart-of-account-type.index')->with('success', __('Chart of account type successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function subTypeupdate(Request $request)
    {
        if(\Auth::user()->can('edit constant chart of account type'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $url_pieces = explode( '?', $_SERVER['REQUEST_URI']);
            if($url_pieces[1]>0){
                $SubTypeId=$url_pieces[1];
            }
    
            $chartOfAccountSubType=ChartOfAccountSubType::find($SubTypeId);
      
            $chartOfAccountSubType->name = $request->name;
        
            $chartOfAccountSubType->save();

            return redirect()->route('chart-of-account-type.index')->with('success', __('Chart of account type successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function destroy(ChartOfAccountType $chartOfAccountType)
    {
        if(\Auth::user()->can('delete constant chart of account type'))
        {
            $chartOfAccountType->delete();

            return redirect()->route('chart-of-account-type.index')->with('success', __('Chart of account type successfully deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
