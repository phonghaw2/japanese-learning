{{-- resources/views/home.blade.php --}}
@extends('layouts.app')
@section('title', 'Trang chủ')
@section('content')
<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card feature-card">
            <div class="card-body text-center">
                <div class="feature-icon">
                    <i class="bi bi-plus-circle"></i>
                </div>
                <h5 class="card-title">区長定例記者会見の開催 Thêm từ mới 区長定例記者会見の開催 Noto Sans JP: CSS class for a variable style</h5>
                <p class="card-text">Dễ dàng thêm từ vựng mới vào bộ sưu tập của bạn</p>
                <a href="{{ route('vocabularies.create') }}" class="btn btn-primary mt-3">Thêm từ mới</a>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card feature-card">
            <div class="card-body text-center">
                <div class="feature-icon">
                    <i class="bi bi-card-text"></i>
                </div>
                <h5 class="card-title">Flashcard</h5>
                <p class="card-text">Học và ghi nhớ từ vựng với hệ thống flashcard thông minh</p>
                <a href="{{ route('flashcard.index') }}" class="btn btn-primary mt-3">Bắt đầu học</a>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card feature-card">
            <div class="card-body text-center">
                <div class="feature-icon">
                    <i class="bi bi-pencil"></i>
                </div>
                <h5 class="card-title">Luyện nét chữ</h5>
                <p class="card-text">Thực hành viết các ký tự tiếng Nhật để ghi nhớ tốt hơn</p>
                <a href="{{ route('writing.index') }}" class="btn btn-primary mt-3">Luyện tập</a>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card feature-card">
            <div class="card-body text-center">
                <div class="feature-icon">
                    <i class="bi bi-search"></i>
                </div>
                <h5 class="card-title">Tìm kiếm</h5>
                <p class="card-text">Tìm kiếm nhanh chóng từ vựng trong cơ sở dữ liệu của bạn</p>
                <a href="{{ route('search.index') }}" class="btn btn-primary mt-3">Tìm kiếm</a>
            </div>
        </div>
    </div>
</div>

@php
    // Lấy dữ liệu thống kê từ database
    $totalVocabulary = App\Models\Vocabulary::count();
    $totalRemembered = App\Models\LearningSession::where('is_remembered', 1)->count();
    $totalExamples = App\Models\ExampleSentence::count();
@endphp

<div class="stats-section">
    <h3 class="text-center mb-4">Thống kê học tập</h3>

    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="stat-card">
                <div class="stat-number">{{ $totalVocabulary }}</div>
                <div class="stat-label">Tổng số từ vựng</div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="stat-card">
                <div class="stat-number">{{ $totalRemembered }}</div>
                <div class="stat-label">Từ đã ghi nhớ</div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="stat-card">
                <div class="stat-number">{{ $totalExamples }}</div>
                <div class="stat-label">Câu ví dụ</div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Thêm Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
@endsection
