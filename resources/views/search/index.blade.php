{{-- resources/views/search/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Tìm kiếm từ vựng')

@section('styles')
<style>
    .search-container {
        position: relative;
        margin-bottom: 2rem;
    }

    .search-input {
        font-size: 1.1rem;
        padding: 0.75rem 1rem;
    }

    .vocabulary-list {
        margin-top: 2rem;
    }

    .vocabulary-card {
        border-left: 5px solid #007bff;
        transition: all 0.3s ease;
    }

    .vocabulary-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .vocabulary-header {
        display: flex;
        align-items: baseline;
        gap: 1rem;
    }

    .vocabulary-word {
        font-size: 1.5rem;
        font-weight: bold;
    }

    .vocabulary-kanji {
        font-size: 1.3rem;
    }

    .vocabulary-romaji {
        color: #6c757d;
    }

    .part-of-speech-tag {
        display: inline-block;
        background-color: #6c757d;
        color: white;
        padding: 0.2rem 0.5rem;
        border-radius: 0.25rem;
        font-size: 0.8rem;
        margin-right: 0.5rem;
    }

    .jlpt-tag {
        display: inline-block;
        background-color: #28a745;
        color: white;
        padding: 0.2rem 0.5rem;
        border-radius: 0.25rem;
        font-size: 0.8rem;
    }

    .example-sentence {
        border-left: 3px solid #6c757d;
        padding-left: 1rem;
        margin-top: 1rem;
        font-size: 0.95rem;
    }

    .no-results {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 3rem;
        background-color: #f8f9fa;
        border-radius: 0.5rem;
    }

    .search-highlight {
        background-color: #ffff00;
        padding: 0 2px;
    }
</style>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Tìm kiếm từ vựng</h4>
            </div>
            <div class="card-body">
                <div class="search-container">
                    <input type="text" id="searchInput" class="form-control search-input"
                           placeholder="Nhập từ khóa tìm kiếm (từ, kanji, ý nghĩa,...)" autofocus>
                </div>

                <div id="searchResults" class="vocabulary-list">
                    <div class="text-center text-muted py-5">
                        <p>Nhập từ khóa để tìm kiếm từ vựng</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        const searchInput = $('#searchInput');
        const searchResults = $('#searchResults');
        let searchTimeout;

        // Xử lý sự kiện nhập từ khóa
        searchInput.on('input', function() {
            const query = $(this).val().trim();

            // Xóa timeout cũ để tránh gửi nhiều request
            clearTimeout(searchTimeout);

            // Không tìm kiếm nếu từ khóa trống
            if (query === '') {
                searchResults.html(`
                    <div class="text-center text-muted py-5">
                        <p>Nhập từ khóa để tìm kiếm từ vựng</p>
                    </div>
                `);
                return;
            }

            // Đặt timeout 300ms để tránh gửi quá nhiều request khi người dùng đang gõ
            searchTimeout = setTimeout(function() {
                searchResults.html(`
                    <div class="text-center py-3">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Đang tìm kiếm...</span>
                        </div>
                        <p class="mt-2">Đang tìm kiếm...</p>
                    </div>
                `);

                // Gửi Ajax request
                $.ajax({
                    url: '{{ route("search.query") }}',
                    type: 'GET',
                    data: { query: query },
                    success: function(response) {
                        renderSearchResults(response, query);
                    },
                    error: function(xhr) {
                        searchResults.html(`
                            <div class="alert alert-danger">
                                Đã xảy ra lỗi trong quá trình tìm kiếm. Vui lòng thử lại sau.
                            </div>
                        `);
                    }
                });
            }, 300);
        });

        // Hiển thị kết quả tìm kiếm
        function renderSearchResults(data, query) {
            // Nếu không có kết quả
            if (data.length === 0) {
                searchResults.html(`
                    <div class="no-results">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-search text-muted mb-3" viewBox="0 0 16 16">
                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                        </svg>
                        <h5>Không tìm thấy kết quả nào</h5>
                        <p class="text-muted">Thử tìm với từ khóa khác hoặc <a href="{{ route('vocabularies.create') }}">thêm từ vựng mới</a></p>
                    </div>
                `);
                return;
            }

            // Xây dựng HTML cho kết quả
            let resultsHtml = '';

            data.forEach(function(item) {
                // Các thẻ
                let tags = '';
                if (item.part_of_speech) {
                    tags += `<span class="part-of-speech-tag">${item.part_of_speech}</span>`;
                }
                if (item.jlpt_level) {
                    tags += `<span class="jlpt-tag">${item.jlpt_level}</span>`;
                }

                // Ví dụ câu
                let exampleHtml = '';
                if (item.example_sentences && item.example_sentences.length > 0) {
                    const example = item.example_sentences[0];
                    exampleHtml = `
                        <div class="example-sentence">
                            <div>${example.japanese_sentence}</div>
                            <div>${example.meaning}</div>
                            <div class="text-muted">${example.romaji}</div>
                        </div>
                    `;
                }

                // Highlight từ khóa tìm kiếm
                const word = highlightMatch(item.word, query);
                const kanji = item.kanji ? highlightMatch(item.kanji, query) : '';
                const meaning = highlightMatch(item.meaning, query);
                const romaji = highlightMatch(item.romaji, query);

                resultsHtml += `
                    <div class="card vocabulary-card mb-3">
                        <div class="card-body">
                            <div class="vocabulary-header">
                                <span class="vocabulary-word">${word}</span>
                                ${item.kanji ? `<span class="vocabulary-kanji">${kanji}</span>` : ''}
                            </div>

                            <div class="vocabulary-meaning">${meaning}</div>
                            <div class="vocabulary-romaji">${romaji}</div>

                            <div class="vocabulary-tags mt-2">
                                ${tags}
                            </div>

                            ${exampleHtml}
                        </div>
                    </div>
                `;
            });

            searchResults.html(resultsHtml);
        }

        // Hàm highlight từ khóa trong kết quả
        function highlightMatch(text, query) {
            if (!text) return '';

            // Tạo regex để tìm từ khóa (không phân biệt hoa thường)
            const regex = new RegExp('(' + escapeRegExp(query) + ')', 'gi');
            return text.replace(regex, '<span class="search-highlight">$1</span>');
        }

        // Escape special regex characters
        function escapeRegExp(string) {
            return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        }
    });
</script>
@endsection
