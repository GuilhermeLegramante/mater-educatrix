<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequest;
use App\Models\Book;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'type', 'discipline']);

        $books = Book::filter($filters)
            ->orderBy('title')
            ->paginate(15)
            ->withQueryString();

        $types = Book::select('type')->distinct()->pluck('type');
        $disciplines = Book::select('discipline')->whereNotNull('discipline')->distinct()->pluck('discipline');

        return view('books.index', compact('books', 'types', 'disciplines', 'filters'));
    }

    /**
     * Exibe o formulário para cadastrar um novo livro.
     */
    public function create()
    {
        return view('books.create');
    }

    /**
     * Exibe os detalhes de um livro específico.
     */
    public function show(Book $book)
    {
        return view('books.show', compact('book'));
    }

    /**
     * Salva o novo livro no banco de dados.
     */
    public function store(BookRequest $request)
    {
        Book::create($request->validated());

        return redirect()
            ->route('books.index')
            ->with('success', 'Livro cadastrado com sucesso!');
    }

    /**
     * Exibe o formulário de edição de um livro.
     */
    public function edit(Book $book)
    {
        return view('books.edit', compact('book'));
    }

    /**
     * Atualiza os dados do livro no banco.
     */
    public function update(BookRequest $request, Book $book)
    {
        $book->update($request->validated());

        return redirect()
            ->route('books.index')
            ->with('success', 'Livro atualizado com sucesso!');
    }

    /**
     * Remove o livro do acervo.
     */
    public function destroy(Book $book)
    {
        // Impede exclusão se o livro estiver emprestado
        if ($book->status === 'borrowed') {
            return back()->with('error', 'Não é possível excluir um livro que está atualmente emprestado.');
        }

        $book->delete();

        return redirect()
            ->route('books.index')
            ->with('success', 'Livro removido do acervo com sucesso.');
    }

    /**
     * Gera o PDF no tamanho exato de envelope/ficha (102mm x 152mm).
     */
    public function generateCardPdf(Book $book)
    {
        // 102mm = 289.1pt | 152mm = 430.9pt
        $customPaper = [0, 0, 289.1, 430.9];

        $pdf = Pdf::loadView('books.pdf-card', compact('book'))
            ->setPaper($customPaper, 'portrait');

        return $pdf->stream("ficha-livro-{$book->id}.pdf");
    }

    /**
     * Alterna manualmente o status de impressão da ficha do livro.
     */
    public function togglePrinted(Book $book)
    {
        // Inverte o valor booleano atual
        $book->update([
            'is_printed' => !$book->is_printed,
        ]);

        return redirect()->back()->with('success', 'Status de impressão atualizado com sucesso!');
    }
}
