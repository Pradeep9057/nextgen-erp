<?php

namespace App\Http\Controllers\Customization;

use App\Http\Controllers\Controller;
use App\Modules\Customization\Models\CustomField;
use App\Modules\Customization\Models\CustomModule;
use App\Modules\Customization\Models\BusinessRule;
use Illuminate\Http\Request;

class CustomizationStudioController extends Controller
{
    public function index()
    {
        return view('customization.index', [
            'fields' => CustomField::all(),
            'rules' => BusinessRule::all(),
            'modules' => CustomModule::all(),
        ]);
    }

    public function createField(Request $request)
    {
        // Implementation for creating a field via EAV
        $field = CustomField::create($request->all());
        return response()->json(['success' => true, 'field' => $field]);
    }

    public function updateRule(Request $request, $id)
    {
        // Implementation for updating recursive logic
        $rule = BusinessRule::findOrFail($id);
        $rule->update($request->all());
        return response()->json(['success' => true, 'rule' => $rule]);
    }
}
