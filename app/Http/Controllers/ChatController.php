<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use Illuminate\Http\Request;

class ChatController extends Controller
{

    public function getChatHistory(Request $request)
    {
        $userId = auth()->user()->id; // Get the authenticated user's ID

        $chatHistory = ChatMessage::where('user_id', $userId)
            ->orWhere('receiver_id', $userId) // Add this line to get messages where the user is the sender or receiver
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
