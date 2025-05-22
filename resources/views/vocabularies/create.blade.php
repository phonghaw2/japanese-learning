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
                <input type="text" class="input" id="word" name="word" value="" required>
            </div>

            <div class="mb-3">
                <label for="kanji" class="form-label">Kanji</label>
                <input type="text" class="input" id="kanji" name="kanji" value="" >
            </div>

            <div class="mb-3">
                <label for="meaning" class="form-label">Meaning <span class="text-danger">*</span></label>
                <textarea type="text" class="input is-textarea" id="meaning" name="meaning" value="" required></textarea>
            </div>

            <div class="mb-3">
                <label for="romaji" class="form-label">Romaji <span class="text-danger">*</span></label>
                <input type="text" class="input" id="romaji" name="romaji" value="" required>
            </div>

            <div class="mt-4 mb-3">
                <h5>Example sentence</h5>
                <div id="example-sentences-container">
                    <div class="example-sentence border rounded p-3 mb-3">
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <label class="form-label">Japanese sentence <span class="text-danger">*</span></label>
                                <input type="text" class="input" name="japanese_sentence[]" required>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-12">
                                <label class="form-label">Meaning <span class="text-danger">*</span></label>
                                <input type="text" class="input" name="sentence_meaning[]" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Romaji <span class="text-danger">*</span></label>
                                <input type="text" class="input" name="sentence_romaji[]" required>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="button" id="add-example" class="btn btn-outline-secondary">+ Thêm câu ví dụ</button>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <button type="reset" class="btn btn-outline-secondary">Làm lại</button>
                <button type="submit" class="btn btn-primary">Lưu từ vựng</button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Thêm câu ví dụ mới
        $('#add-example').click(function() {
            const newExample = `
                <div class="example-sentence border rounded p-3 mb-3">
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <label class="form-label">Câu tiếng Nhật <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="japanese_sentence[]" required>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-12">
                            <label class="form-label">Nghĩa <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="sentence_meaning[]" required>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-12">
                            <label class="form-label">Romaji <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="sentence_romaji[]" required>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="button" class="btn btn-sm btn-danger remove-example">Xóa</button>
                    </div>
                </div>
            `;

            $('#example-sentences-container').append(newExample);
        });

        // Xóa câu ví dụ
        $(document).on('click', '.remove-example', function() {
            $(this).closest('.example-sentence').remove();
        });
    });
</script>
@endsection
