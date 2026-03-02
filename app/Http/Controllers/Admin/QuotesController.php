<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminQuote;
use Illuminate\Http\Request;

class QuotesController extends Controller
{
    /**
     * Display a listing of the quotes.
     */
    public function index()
    {
        $quotes = AdminQuote::ordered()->get();
        return view('admin.quotes.index', compact('quotes'));
    }

    /**
     * Show the form for creating a new quote.
     */
    public function create()
    {
        return view('admin.quotes.create');
    }

    /**
     * Store a newly created quote in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'quote' => 'required|string|max:1000',
            'is_active' => 'boolean',
            'sort_order' => 'required|integer|min:0',
        ]);

        AdminQuote::create([
            'title' => $request->title,
            'quote' => $request->quote,
            'is_active' => $request->has('is_active'),
            'sort_order' => $request->sort_order,
        ]);

        return redirect()->route('admin.quotes.index')
            ->with('success', 'Quote created successfully!');
    }

    /**
     * Show the form for editing the specified quote.
     */
    public function edit(AdminQuote $quote)
    {
        return view('admin.quotes.edit', compact('quote'));
    }

    /**
     * Update the specified quote in storage.
     */
    public function update(Request $request, AdminQuote $quote)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'quote' => 'required|string|max:1000',
            'is_active' => 'boolean',
            'sort_order' => 'required|integer|min:0',
        ]);

        $quote->update([
            'title' => $request->title,
            'quote' => $request->quote,
            'is_active' => $request->has('is_active'),
            'sort_order' => $request->sort_order,
        ]);

        return redirect()->route('admin.quotes.index')
            ->with('success', 'Quote updated successfully!');
    }

    /**
     * Remove the specified quote from storage.
     */
    public function destroy(AdminQuote $quote)
    {
        $quote->delete();

        return redirect()->route('admin.quotes.index')
            ->with('success', 'Quote deleted successfully!');
    }

    /**
     * Toggle the active status of a quote.
     */
    public function toggleActive(AdminQuote $quote)
    {
        $quote->update(['is_active' => !$quote->is_active]);

        $status = $quote->is_active ? 'activated' : 'deactivated';
        return redirect()->route('admin.quotes.index')
            ->with('success', "Quote {$status} successfully!");
    }
}
