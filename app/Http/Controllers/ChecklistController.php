<?php

namespace App\Http\Controllers;

use App\Models\Checklist;
use App\Models\ChecklistItem;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChecklistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();
        $checklists = Checklist::query()
            ->withCount(['items', 'items as completed_count' => function($query) {
                $query->where('is_completed', 1);
            }])
            ->where('user_id', $user->id)
            ->latest('id')
            ->get();
        return response()->json(['status'=> 'success', 'checklists' => $checklists], 200);
    }

    public function store(Request $request)
    {
        $validated = $this->validate($request, [
            'title'=> 'string|required',
            'is_pinned'=> 'boolean|nullable'
        ]);

        try {
            $validated['user_id'] = auth()->id();
            $checklist = Checklist::create($validated);
            return response()->json(['status'=> 'success', 'message'=> 'Checklist created successfully', 'checklist' => $checklist], 200);
        } catch (Exception $e) {
            return response()->json(['status'=> 'error', 'message'=> 'Checklist create failed'], 409);
        }
    }

    public function show($id)
    {
        try {
            $checklist = Checklist::findOrFail($id);
            $checklist->load('items');
            return response()->json(['status'=> 'success', 'checklist' => $checklist], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['status'=> 'error', 'message'=> 'Not found'], 404);
        }
    }

    public function delete($id)
    {
        try {
            DB::transaction(function () use ($id) {
                ChecklistItem::where('checklist_id', $id)->delete();
                $checklist = Checklist::findOrFail($id);
                $checklist->delete();
            });
            return response()->json(['status'=> 'success', 'message' => 'Checklist deleted successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['status'=> 'error', 'message'=> 'Delete failed'], 200);
        }
    }
}
