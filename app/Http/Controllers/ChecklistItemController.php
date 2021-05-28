<?php

namespace App\Http\Controllers;

use App\Models\ChecklistItem;
use Exception;
use Illuminate\Http\Request;

class ChecklistItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request, $id)
    {
        $validated = $this->validate($request, [
            'title'=> 'string|required',
        ]);

        try {
            $validated['checklist_id'] = $id;
            $item = ChecklistItem::create($validated);
            return response()->json(['status'=> 'success', 'message'=> 'Checklist item created successfully', 'item' => $item], 200);
        } catch (Exception $e) {
            return response()->json(['status'=> 'error', 'message'=> 'Checklist item create failed'], 409);
        }
    }

    public function delete($id)
    {
        try {
            $checklist_item = ChecklistItem::findOrFail($id);
            $checklist_item->delete();
            return response()->json(['status'=> 'success', 'message' => 'Checklist item deleted successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['status'=> 'error', 'message'=> 'Delete failed'], 200);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $validated = $this->validate($request, [
            'is_completed'=> 'boolean|required',
        ]);

        try {
            ChecklistItem::where('id', $id)->update($validated);
            return response()->json(['status'=> 'success', 'message' => 'Successfully updated'], 200);
        } catch (Exception $e) {
            return response()->json(['status'=> 'error', 'message'=> 'Update failed'], 200);
        }
    }
}
