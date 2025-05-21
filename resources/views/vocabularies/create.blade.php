{{-- resources/views/vocabulary/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Thêm từ mới')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Thêm từ vựng mới</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('vocabularies.store') }}" method="POST" id="vocabularyForm">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="word" class="form-label">Từ (Hiragana/Katakana) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('word') is-invalid @enderror"
                                       id="word" name="word" value="{{ old('word') }}" required>
                                @error('word')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="kanji" class="form-label">Kanji</label>
                                <input type="text" class="form-control @error('kanji') is-invalid @enderror"
                                       id="kanji" name="kanji" value="{{ old('kanji') }}">
                                @error('kanji')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="meaning" class="form-label">Ý nghĩa <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('meaning') is-invalid @enderror"
                                       id="meaning" name="meaning" value="{{ old('meaning') }}" required>
                                @error('meaning')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="romaji" class="form-label">Romaji <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('romaji') is-invalid @enderror"
                                       id="romaji" name="romaji" value="{{ old('romaji') }}" required>
                                @error('romaji')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="part_of_speech" class="form-label">Loại từ</label>
                                <select class="form-select @error('part_of_speech') is-invalid @enderror"
                                        id="part_of_speech" name="part_of_speech">
                                    <option value="" selected>-- Chọn loại từ --</option>
                                    <option value="Danh từ" {{ old('part_of_speech') == 'Danh từ' ? 'selected' : '' }}>Danh từ</option>
                                    <option value="Động từ" {{ old('part_of_speech') == 'Động từ' ? 'selected' : '' }}>Động từ</option>
                                    <option value="Tính từ" {{ old('part_of_speech') == 'Tính từ' ? 'selected' : '' }}>Tính từ</option>
                                    <option value="Trạng từ" {{ old('part_of_speech') == 'Trạng từ' ? 'selected' : '' }}>Trạng từ</option>
                                    <option value="Trợ từ" {{ old('part_of_speech') == 'Trợ từ' ? 'selected' : '' }}>Trợ từ</option>
                                    <option value="Khác" {{ old('part_of_speech') == 'Khác' ? 'selected' : '' }}>Khác</option>
                                </select>
                                @error('part_of_speech')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="jlpt_level" class="form-label">Cấp độ JLPT</label>
                                <select class="form-select @error('jlpt_level') is-invalid @enderror"
                                        id="jlpt_level" name="jlpt_level">
                                    <option value="" selected>-- Chọn cấp độ --</option>
                                    <option value="N5" {{ old('jlpt_level') == 'N5' ? 'selected' : '' }}>N5</option>
                                    <option value="N4" {{ old('jlpt_level') == 'N4' ? 'selected' : '' }}>N4</option>
                                    <option value="N3" {{ old('jlpt_level') == 'N3' ? 'selected' : '' }}>N3</option>
                                    <option value="N2" {{ old('jlpt_level') == 'N2' ? 'selected' : '' }}>N2</option>
                                    <option value="N1" {{ old('jlpt_level') == 'N1' ? 'selected' : '' }}>N1</option>
                                </select>
                                @error('jlpt_level')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 mb-3">
                        <h5>Câu ví dụ</h5>
                        <div id="example-sentences-container">
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

                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="form-label">Romaji <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="sentence_romaji[]" required>
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
                </form>
            </div>
        </div>
    </div>
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
