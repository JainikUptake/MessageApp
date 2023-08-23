<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use Illuminate\Http\Request;

class ChatController extends Controller
{

    public function getChatHistory(Request $request)
    {
        $userId = $request->input('user_id');
        $receiverId = $request->input('receiver_id');

        $chatHistory = ChatMessage::where(function ($query) use ($userId, $receiverId) {
            $query->where('user_id', $userId)
                ->where('receiver_id', $receiverId);
        })
            ->orWhere(function ($query) use ($userId, $receiverId) {
                $query->where('user_id', $receiverId)
                    ->where('receiver_id', $userId);
            })
            ->orderBy('created_at')
            ->get();

        return response()->json($chatHistory);
    }

    public function saveChatMessage(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required',
            'message' => 'required',
            'receiver_id' => 'required',
        ]);

        // Create a new ChatMessage instance and save it
        $chatMessage = new ChatMessage([
            'user_id' => $request->user_id,
            'message' => $request->message,
            'receiver_id' => $request->receiver_id,
        ]);
        $chatMessage->save();

        return response()->json(['message' => 'Chat message saved successfully']);
    }
}
