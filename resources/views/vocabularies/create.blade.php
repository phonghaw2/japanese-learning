{{-- resources/views/vocabulary/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Thêm từ mới')

@section('content')
<div class="card">
    <div class="form-tab">
        <span class="active" data-form="vocabulary-form">{{ __('contents.label_old_version') }}</span>
        <span class="" data-form="vocabulary-import">{{ __('contents.label_new_version') }}</span>
    </div>
    <form action="{{ route('vocabularies.store') }}" method="POST" id="vocabulary-form">
        <div>
            <h3 class="mb-3">{{ __('contents.label_add_new_word') }}</h3>
        </div>
        <div class="card-body">
            @csrf
            <div class="mb-3">
                <label for="word" class="form-label">{{ __('contents.label_form__word') }} <span class="text-danger">*</span></label>
                <input type="text" class="input" id="word" name="word" value="{{ old('word') }}" required>
            </div>
            <div class="mb-3">
                <label for="kanji" class="form-label">{{ __('contents.label_form__kanji') }}</label>
                <input type="text" class="input" id="kanji" name="kanji" value="{{ old('kanji') }}" >
            </div>
            <div class="mb-3">
                <label for="romaji" class="form-label">{{ __('contents.label_form__romaji') }} <span class="text-danger">*</span></label>
                <input type="text" class="input" id="romaji" name="romaji" value="{{ old('meaning') }}" required>
            </div>
            <div class="mb-3">
                <label for="meaning" class="form-label">{{ __('contents.label_form__meaning') }} <span class="text-danger">*</span></label>
                <textarea type="text" class="input is-textarea" id="meaning" name="meaning" value="{{ old('meaning') }}" required></textarea>
            </div>

            <div class="mt-4">
                <h4>{{ __('contents.label_ex_sentence') }}</h4>
                <div id="example-sentences-container">
                    <div class="mt-3">
                        <div class="col-md-12">
                            <label class="form-label">{{ __('contents.label_form__jp_sen') }} <span class="text-danger">*</span></label>
                            <input type="text" class="input" name="example_sentences[0][japanese_sentence]" required>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">{{ __('contents.label_form__meaning') }} <span class="text-danger">*</span></label>
                            <input type="text" class="input" name="example_sentences[0][meaning]" required>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">{{ __('contents.label_form__romaji') }} <span class="text-danger">*</span></label>
                            <input type="text" class="input" name="example_sentences[0][romaji]" required>
                        </div>
                    </div>
                </div>

                <div class="flex">
                    <button type="button" id="add-example" class="form-btn mt-4">+ {{ __('button.add') }}</button>
                </div>
            </div>

            <div class="mt-4 text-center">
                <button type="submit" class="form-submit-btn">{{ __('button.save') }}</button>
            </div>
        </div>
    </form>
    <form action="{{ route('vocabularies.store.auto') }}" method="POST" id="vocabulary-import" style="display: none">
        <div>
            <h3 class="mb-3">{{ __('contents.label_import_new_word') }}</h3>
        </div>
        <div class="card-body">
            @csrf
            <div class="mb-3">
                <label for="word" class="form-label">{{ __('contents.label_form__word_s') }}</label>
                <input type="text" class="input" id="word" name="word" value="{{ old('word') }}" required>
            </div>
            <div class="mt-4 text-center">
                <button type="submit" class="form-submit-btn">{{ __('button.import') }}</button>
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
                        <label class="form-label">{{ __('contents.label_form__jp_sen') }} <span class="text-danger">*</span></label>
                        <input type="text" class="input" name="example_sentences[${ex_index}][japanese_sentence]" required>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">{{ __('contents.label_form__meaning') }} <span class="text-danger">*</span></label>
                        <input type="text" class="input" name="example_sentences[${ex_index}][meaning]" required>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">{{ __('contents.label_form__romaji') }} <span class="text-danger">*</span></label>
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

        $('.form-tab span').click(function(){
            var formId = $(this).data('form');

            $('.form-tab span').removeClass('active');
            $(this).addClass('active');
            $('#vocabulary-form, #vocabulary-import').hide();
            $('#' + formId).show();
        });
    });
</script>
@endsection
