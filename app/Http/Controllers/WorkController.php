<?php

namespace App\Http\Controllers;

use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class WorkController extends Controller
{
    public function sort(Request $request){
        $name = $request->input('name');
        $work = Workspace::query()
            ->where('name', 'LIKE', '%' . $name . '%')->get();
        /*** check response ***/
        if ($work->isEmpty()) {
            return response()->json(["message" => "not found item"], Response::HTTP_NOT_FOUND);
        }
        return response()->json(['count' => $work->count(),'rows' => $work] , Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => "required|unique:workspaces",
        ]);

        Workspace::query()->create([
            'name' => $request->input('name'),
            'background_color' => $request->input('background_color') ? $request->input('background_color') : "#fff"
        ]);

        return response()->json(["message" => "successfully inserted"], Response::HTTP_CREATED);

    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make(
            [
                'id' => $id,
                'name' => $request->input('name')
            ],
            [
                'id' => 'required|exists:workspaces,id',
                'name' => 'required|unique:workspaces,name,' . $id
            ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->messages()],
                Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $work = Workspace::query()->find($id);

        $work->update([
            "name" => $request->input('name'),
            "background_color" => $request->background_color ? $request->background_color : "#fff"
        ]);

        return response()->json(["message" => "workspace updated successfully"], Response::HTTP_OK);
    }

    public function delete(Request $request, $id)
    {
        $validator = Validator::make(['id' => $id,], ['id' => 'required|exists:workspaces,id',]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->messages()],
                Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $work = Workspace::query()->find($id);
        $work->delete();

        return response()->json(["workspace"  => $id ,'message' => "workspace deleted successfully"], Response::HTTP_OK);
    }
}
