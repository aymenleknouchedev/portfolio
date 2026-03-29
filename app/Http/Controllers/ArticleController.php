<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleView;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::published()->latest('published_at')->paginate(12);
        return view('articles.index', compact('articles'));
    }

    public function show(Article $article)
    {
        $ipHash = hash('sha256', request()->ip() . config('app.key'));

        $created = ArticleView::firstOrCreate([
            'article_id' => $article->id,
            'ip_hash' => $ipHash,
        ]);

        if ($created->wasRecentlyCreated) {
            $article->increment('views_count');
        }

        $title = $article->title;
        $metaDescription = $article->excerpt ?: Str::limit(strip_tags($article->content ?? ''), 160);
        $ogImage = $article->hero_image ? asset('storage/' . $article->hero_image) : null;

        return view('articles.show', compact('article', 'title', 'metaDescription', 'ogImage'));
    }
}
