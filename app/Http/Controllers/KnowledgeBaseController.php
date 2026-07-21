<?php

namespace App\Http\Controllers;

use App\Models\KnowledgeBase;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class KnowledgeBaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $isIT = $user->unit && strtolower($user->unit) == 'teknologi dan informasi';
        
        $tab = $request->get('tab', 'published');
        $query = KnowledgeBase::with(['author']);
        
        if ($isIT) {
            if ($tab === 'draft') {
                $query->where('status', 'draft');
            } elseif ($tab === 'mine') {
                $query->where('author_id', $user->id);
            } else {
                $query->where('status', 'published');
            }
        } else {
            $query->where('status', 'published');
            $tab = 'published';
        }
        
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        $knowledgeBases = $query->orderByDesc('updated_at')->paginate(12)->withQueryString();
        $categories = ['Hardware', 'Jaringan', 'Software', 'SIMRS'];
        
        $counts = [];
        if ($isIT) {
            $counts['published'] = KnowledgeBase::where('status', 'published')->count();
            $counts['draft'] = KnowledgeBase::where('status', 'draft')->count();
            $counts['mine'] = KnowledgeBase::where('author_id', $user->id)->count();
        }

        return view('pages.helpdesk.knowledge-base.index', compact('knowledgeBases', 'categories', 'tab', 'counts', 'isIT'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = ['Hardware', 'Jaringan', 'Software', 'SIMRS'];
        return view('pages.helpdesk.knowledge-base.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'required|string',
            'video_url' => 'nullable|url',
            'photo' => 'nullable|image|max:5120',
            'category' => 'nullable|string|in:Hardware,Jaringan,Software,SIMRS',
            'status' => 'required|in:draft,published',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo_path'] = $request->file('photo')->store('knowledge_bases', 'public');
        }
        
        $validated['author_id'] = auth()->id();
        $validated['slug'] = Str::slug($validated['title']) . '-' . time();

        $article = KnowledgeBase::create($validated);

        $msg = $request->status === 'draft'
            ? 'Artikel disimpan sebagai draft.'
            : 'Artikel berhasil dipublikasikan.';

        return $request->status === 'draft'
            ? redirect()->route('knowledge-base.index', ['tab' => 'draft'])->with('success', $msg)
            : redirect()->route('knowledge-base.show', $article)->with('success', $msg);
    }

    /**
     * Display the specified resource.
     */
    public function show(KnowledgeBase $knowledgeBase)
    {
        if ($knowledgeBase->status === 'draft') {
            $user = auth()->user();
            $isIT = $user->unit && strtolower($user->unit) == 'teknologi dan informasi';
            if (!$isIT && $knowledgeBase->author_id !== $user->id) {
                abort(403, 'Artikel ini belum dipublikasikan.');
            }
        }

        $knowledgeBase->increment('views');

        $related = KnowledgeBase::where('category', $knowledgeBase->category)
            ->where('id', '!=', $knowledgeBase->id)
            ->where('status', 'published')
            ->limit(5)
            ->get();

        return view('pages.helpdesk.knowledge-base.show', compact('knowledgeBase', 'related'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KnowledgeBase $knowledgeBase)
    {
        $user = auth()->user();
        $isIT = $user->unit && strtolower($user->unit) == 'teknologi dan informasi';
        if (!$isIT && $knowledgeBase->author_id !== $user->id) {
            abort(403);
        }

        $categories = ['Hardware', 'Jaringan', 'Software', 'SIMRS'];
        return view('pages.helpdesk.knowledge-base.edit', compact('knowledgeBase', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KnowledgeBase $knowledgeBase)
    {
        $user = auth()->user();
        $isIT = $user->unit && strtolower($user->unit) == 'teknologi dan informasi';
        if (!$isIT && $knowledgeBase->author_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'required|string',
            'video_url' => 'nullable|url',
            'photo' => 'nullable|image|max:5120',
            'category' => 'nullable|string|in:Hardware,Jaringan,Software,SIMRS',
            'status' => 'required|in:draft,published',
        ]);

        if ($request->hasFile('photo')) {
            if ($knowledgeBase->photo_path) {
                Storage::disk('public')->delete($knowledgeBase->photo_path);
            }
            $validated['photo_path'] = $request->file('photo')->store('knowledge_bases', 'public');
        }

        $knowledgeBase->update($validated);

        $msg = $request->status === 'draft'
            ? 'Artikel disimpan sebagai draft.'
            : 'Artikel berhasil dipublikasikan.';

        return redirect()->route('knowledge-base.show', $knowledgeBase)->with('success', $msg);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KnowledgeBase $knowledgeBase)
    {
        $user = auth()->user();
        $isIT = $user->unit && strtolower($user->unit) == 'teknologi dan informasi';
        if (!$isIT && $knowledgeBase->author_id !== $user->id) {
            abort(403);
        }

        if ($knowledgeBase->photo_path) {
            Storage::disk('public')->delete($knowledgeBase->photo_path);
        }
        $knowledgeBase->delete();

        return redirect()->route('knowledge-base.index')->with('success', 'Artikel berhasil dihapus.');
    }

    /**
     * Publish a draft article.
     */
    public function publish(KnowledgeBase $knowledgeBase)
    {
        $user = auth()->user();
        $isIT = $user->unit && strtolower($user->unit) == 'teknologi dan informasi';
        if (!$isIT && $knowledgeBase->author_id !== $user->id) {
            abort(403);
        }

        $knowledgeBase->update(['status' => 'published']);
        return back()->with('success', "Artikel \"{$knowledgeBase->title}\" berhasil dipublikasikan.");
    }
}
