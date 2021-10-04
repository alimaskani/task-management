<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class TicketController extends Controller
{
    public function sort(Request $request)
    {
        $name = $request->input('title');
        $ticket = Ticket::query()->where('title', 'LIKE', '%' . $name . '%')->get();
        /*** check response ***/
        if ($ticket->isEmpty()) {
            return response()->json(["message" => "not found item"], Response::HTTP_NOT_FOUND);
        }
        return response()->json(['count' => $ticket->count() ,'rows' => $ticket], Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        /**** data from request *****/
        $title = $request->input('title');
        $label_id = $request->input('label_id');
        $color = $request->input('color');
        $description = $request->input('description');

        $request->validate([
            'label_id' => 'required|exists:labels,id',
            'title' => 'required',
            'description' => 'required|min:6',
        ]);


        $validate_duplicate_item = Ticket::query()->where([
            ['title', '=', $title],
            ['label_id', '=', $label_id],])->get();

        if ($validate_duplicate_item->count() > 0) {
            return response()->json(["message" => "item has exist"], Response::HTTP_BAD_REQUEST);
        }


        Ticket::query()->create([
            "title" => $title,
            "description" => $description,
            "color" => $color ? $color : "blue",
            "label_id" => $label_id
        ]);

        return response()->json(['message' => "ticket successfully inserted"], Response::HTTP_CREATED);


    }

    public function update(Request $request, $id)
    {

        /**** data from request *****/
        $title = $request->input('title');
        $label_id = $request->input('label_id');
        $color = $request->input('color');
        $description = $request->input('description');


        $validator = Validator::make(
            [
                'id' => $id,
                'title' => $title,
                'description' => $description,
                'color' => $color,
                'label_id' => $label_id,
            ],
            [
                'id' => 'required|exists:tickets,id',
                'title' => 'required',
                'description' => 'required|min:6',
                'label_id' => 'required|exists:labels,id',
            ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->messages()],
                Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $validate_duplicate_item = Ticket::query()->where([
            ['id', '!=', $id],
            ['title', '=', $title],
            ['label_id', '=', $label_id],])->get();
        if ($validate_duplicate_item->count() > 0) {
            return response()->json(["message" => "item has exist"], Response::HTTP_BAD_REQUEST);
        }


        $ticket = Ticket::query()->find($id);
        $ticket->update([
            "title" => $title,
            "description" => $description,
            "color" => $color ? $color : "blue",
            "label_id" => $label_id,
        ]);
        return response()->json(['message' => "ticket updated successfully"], Response::HTTP_OK);

    }

    public function delete(Request $request, $id)
    {

        $validator = Validator::make(['id' => $id,], ['id' => 'required|exists:tickets,id',]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->messages()],
                Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $ticket = Ticket::query()->find($id);
        $ticket->delete();
        return response()->json([ "ticket" => $id ,"message" => "ticket deleted successfully"], Response::HTTP_OK);

    }

}
