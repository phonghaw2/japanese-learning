{{-- resources/views/vocabulary/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Thêm từ mới')

@section('content')
<div class="card">
    <form action="{{ route('vocabularies.store') }}" method="POST" id="vocabulary-form">
        <div>
            <h4 class="mb-0">Add new vocabulary</h4>
        </div>
        <div class="card-body">
            @csrf
            <div class="mb-3">
                <label for="word" class="form-label">Word (Hiragana/Katakana) <span class="text-danger">*</span></label>
                <input type="text" class="input" id="word" name="word" value="{{ old('word') }}" required>
            </div>
            <div class="mb-3">
                <label for="kanji" class="form-label">Kanji</label>
                <input type="text" class="input" id="kanji" name="kanji" value="{{ old('kanji') }}" >
            </div>
            <div class="mb-3">
                <label for="romaji" class="form-label">Romaji <span class="text-danger">*</span></label>
                <input type="text" class="input" id="romaji" name="romaji" value="{{ old('meaning') }}" required>
            </div>
            <div class="mb-3">
                <label for="meaning" class="form-label">Meaning <span class="text-danger">*</span></label>
                <textarea type="text" class="input is-textarea" id="meaning" name="meaning" value="{{ old('meaning') }}" required></textarea>
            </div>

            <div class="mt-4">
                <h4>Example sentence</h4>
                <div id="example-sentences-container">
                    <div class="mt-3">
                        <div class="col-md-12">
                            <label class="form-label">Japanese sentence <span class="text-danger">*</span></label>
                            <input type="text" class="input" name="example_sentences[0][japanese_sentence]" required>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Meaning <span class="text-danger">*</span></label>
                            <input type="text" class="input" name="example_sentences[0][meaning]" required>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Romaji <span class="text-danger">*</span></label>
                            <input type="text" class="input" name="example_sentences[0][romaji]" required>
                        </div>
                    </div>
                </div>

                <div class="flex">
                    <button type="button" id="add-example" class="form-btn mt-4">+ Add Example Sentence</button>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <button type="submit" class="form-submit-btn">Save</button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        let ex_index = 1;
        $('#add-example').click(function() {
            const newExample = `
                <div class="mt-3">
                    <div class="col-md-12">
                        <label class="form-label">Japanese sentence <span class="text-danger">*</span></label>
                        <input type="text" class="input" name="example_sentences[${ex_index}][japanese_sentence]" required>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Meaning <span class="text-danger">*</span></label>
                        <input type="text" class="input" name="example_sentences[${ex_index}][meaning]" required>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Romaji <span class="text-danger">*</span></label>
                        <input type="text" class="input" name="example_sentences[${ex_index}][romaji]" required>
                    </div>
                </div>
            `;
            ex_index++;
            $('#example-sentences-container').append(newExample);
        });

        // Xóa câu ví dụ
        $(document).on('click', '.remove-example', function() {
            $(this).closest('.example-sentence').remove();
        });
    });
</script>
@endsection
