<?php

namespace App\Http\Controllers;

use App\Models\Label;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class LabelController extends Controller
{
    public function sort(Request $request)
    {
        $name = $request->input('name');
        $label = Label::query()
            ->where('name', 'LIKE', '%' . $name . '%')->get();
        /*** check response ***/
        if ($label->isEmpty()) {
            return response()->json(["message" => "Not Found Item"], Response::HTTP_NOT_FOUND);
        }
        return response()->json(['count' => $label->count(),'rows' => $label], Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $request->validate([
            'workspace_id' => 'required|exists:workspaces,id',
            'name' => 'required',
        ]);

        $name = $request->input('name');
        $workspace_id = $request->input('workspace_id');

        $validate_duplicate_item = Label::query()->where([
            ['name', '=', $name],
            ['workspace_id', '=', $workspace_id],])->get();

        if ($validate_duplicate_item->count() > 0) {
            return response()->json(["message" => "item has exist"], Response::HTTP_BAD_REQUEST);
        }


        Label::query()->create([
            "name" => $name,
            "workspace_id" => $workspace_id
        ]);

        return response()->json(['message' => "Label SuccessFully Inserted"], Response::HTTP_CREATED);

    }

    public function update(Request $request, $id)
    {

        $workspace_id = $request->input('workspace_id');
        $name = $request->input('name');

        $validator = Validator::make(
            [
                'id' => $id,
                'name' => $name,
                'workspace_id' => $workspace_id,
            ],
            [
                'id' => 'required|exists:labels,id',
                'name' => 'required',
                'workspace_id' => 'required|exists:workspaces,id',
            ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->messages()],
                Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $validate_duplicate_item = Label::query()->where([
            ['id', '!=', $id],
            ['name', '=', $name],
            ['workspace_id', '=', $workspace_id],])->get();
        if ($validate_duplicate_item->count() > 0) {
            return response()->json(["message" => "item has exist"], Response::HTTP_BAD_REQUEST);
        }



        $label = Label::query()->find($id);
        $label->update([
            "name" => $name,
            "workspace_id" => $workspace_id
        ]);
        return response()->json(['message' => "label updated successfully"], Response::HTTP_OK);
    }

    public function delete(Request $request, $id)
    {
        $validator = Validator::make(['id' => $id,], ['id' => 'required|exists:labels,id',]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->messages()],
                Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $label = Label::query()->find($id);
        $label->delete();
        return response()->json(["label" => $id ,"message" => "label deleted successfully"], Response::HTTP_OK);
    }

}
