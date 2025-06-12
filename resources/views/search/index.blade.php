{{-- resources/views/search/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Tìm kiếm từ vựng')
@section('content')
<div class="card">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">{{ __('contents.label_search_word') }}</h4>
    </div>
    <div class="card-body">
        <div class="search-container">
            <input type="text" id="searchInput" class="search-input"
                    placeholder="Enter search keywords (word, kanji, meaning, etc.)" autofocus>
        </div>

        <div id="searchResults" class="vocabulary-list">
            <div class="text-center text-muted">
                <p>{{ __('contents.text_search_note') }}</p>
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

        searchInput.on('input', function() {
            const query = $(this).val().trim();

            clearTimeout(searchTimeout);

            if (query === '') {
                searchResults.html(`
                    <div class="text-center text-muted py-5">
                        <p>{{ __('contents.text_search_prompt') }}</p>
                    </div>
                `);
                return;
            }

            searchTimeout = setTimeout(function() {
                searchResults.html(`
                    <div class="text-center py-3">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">{{ __('contents.text_search_loading') }}</span>
                        </div>
                        <p class="mt-2">{{ __('contents.text_search_loading') }}</p>
                    </div>
                `);

                $.ajax({
                    url: '{{ route("search.query") }}',
                    type: 'GET',
                    data: { query: query },
                    success: function(response) {
                        renderSearchResults(response.data, query);
                    },
                    error: function(xhr) {
                        searchResults.html(`
                            <div class="alert alert-danger">
                                {{ __('contents.msg_search_error') }}
                            </div>
                        `);
                    }
                });
            }, 300);
        });

        function renderSearchResults(data, query) {
            if (data.length === 0) {
                searchResults.html(`
                    <div class="no-results">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-search text-muted mb-3" viewBox="0 0 16 16">
                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                        </svg>
                        <h5>No results found.</h5>
                        <p class="text-muted">Try a different keyword or <a href="{{ route('vocabularies.create') }}" class="link__add-word">add a new vocabulary word. </a></p>
                    </div>
                `);
                return;
            }

            let resultsHtml = '';

            data.forEach(function(item) {
                let tags = '';
                if (item.part_of_speech) {
                    tags += `<span class="part-of-speech-tag">${item.part_of_speech}</span>`;
                }
                if (item.jlpt_level) {
                    tags += `<span class="jlpt-tag">${item.jlpt_level}</span>`;
                }

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

                const word = highlightMatch(item.word, query);
                const kanji = item.kanji ? highlightMatch(item.kanji, query) : '';
                const meaning = highlightMatch(item.meaning, query);
                const romaji = highlightMatch(item.romaji, query);

                resultsHtml += `
                    <div class="card-mini vocabulary-card mb-3">
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

        function highlightMatch(text, query) {
            if (!text) return '';

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
