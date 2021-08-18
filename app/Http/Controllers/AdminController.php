<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ParserLog;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Админ-панель для наблюдения результатов;
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $paginatedArticles = Article::orderByDesc('id')->paginate(10);
        $paginatedLogs = ParserLog::orderByDesc('id')->paginate(10);

        return view('welcome', compact('paginatedArticles', 'paginatedLogs'));
    }
}
