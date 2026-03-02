<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    /**
     * Display a listing of contact messages
     */
    public function index()
    {
        $messages = ContactMessage::orderBy('created_at', 'desc')->get();
        
        // Count stats
        $stats = [
            'total' => ContactMessage::count(),
            'unread' => ContactMessage::where('status', 'unread')->count(),
            'read' => ContactMessage::where('status', 'read')->count(),
            'replied' => ContactMessage::where('status', 'replied')->count(),
        ];
        
        return view('admin.contact-messages.index', compact('messages', 'stats'));
    }

    /**
     * Display the specified contact message
     */
    public function show($id)
    {
        $message = ContactMessage::findOrFail($id);
        
        // Mark as read if unread
        if ($message->status === 'unread') {
            $message->status = 'read';
            $message->save();
        }
        
        return view('admin.contact-messages.show', compact('message'));
    }

    /**
     * Update the specified contact message status
     */
    public function updateStatus(Request $request, $id)
    {
        $message = ContactMessage::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:unread,read,replied'
        ]);
        
        $message->status = $request->status;
        $message->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully!'
        ]);
    }

    /**
     * Update admin notes
     */
    public function updateNotes(Request $request, $id)
    {
        $message = ContactMessage::findOrFail($id);
        
        $request->validate([
            'admin_notes' => 'nullable|string|max:1000'
        ]);
        
        $message->admin_notes = $request->admin_notes;
        $message->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Notes updated successfully!'
        ]);
    }

    /**
     * Remove the specified contact message
     */
    public function destroy($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Message deleted successfully!'
        ]);
    }

    /**
     * Bulk delete messages
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:contact_messages,id'
        ]);
        
        ContactMessage::whereIn('id', $request->ids)->delete();
        
        return response()->json([
            'success' => true,
            'message' => count($request->ids) . ' messages deleted successfully!'
        ]);
    }

    /**
     * Bulk update status
     */
    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:contact_messages,id',
            'status' => 'required|in:unread,read,replied'
        ]);
        
        ContactMessage::whereIn('id', $request->ids)->update(['status' => $request->status]);
        
        return response()->json([
            'success' => true,
            'message' => count($request->ids) . ' messages updated successfully!'
        ]);
    }

    /**
     * Send reply to contact message
     */
    public function sendReply(Request $request, $id)
    {
        $message = ContactMessage::findOrFail($id);
        
        $request->validate([
            'reply_message' => 'required|string|min:10'
        ]);
        
        try {
            // Send reply email
            \Mail::raw($request->reply_message, function($mail) use ($message, $request) {
                $mail->to($message->email)
                     ->subject('Re: ' . $message->subject)
                     ->from(config('mail.from.address'), config('mail.from.name'));
            });
            
            // Update status to replied
            $message->status = 'replied';
            $message->admin_notes = ($message->admin_notes ? $message->admin_notes . "\n\n" : '') 
                                  . "Replied on " . now()->format('Y-m-d H:i:s') . ":\n" 
                                  . $request->reply_message;
            $message->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Reply sent successfully!'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Failed to send reply: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to send reply. Please try again.'
            ], 500);
        }
    }

    /**
     * Get data for DataTables
     */
    public function getData()
    {
        $messages = ContactMessage::orderBy('created_at', 'desc')->get();
        
        return response()->json([
            'data' => $messages
        ]);
    }
}
