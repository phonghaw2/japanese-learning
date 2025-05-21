{{-- resources/views/writing/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Luyện nét chữ')

@section('styles')
<style>
    .canvas-container {
        position: relative;
        margin: 0 auto;
        max-width: 600px;
    }

    #drawing-canvas {
        border: 1px solid #ccc;
        background-color: #fff;
        cursor: crosshair;
        display: block;
        margin: 0 auto;
        border-radius: 4px;
    }

    .character-display {
        margin-bottom: 2rem;
        text-align: center;
    }

    .character-japanese {
        font-size: 4rem;
        font-weight: bold;
        line-height: 1.2;
    }

    .character-meaning {
        font-size: 1.5rem;
        color: #6c757d;
    }

    .character-romaji {
        font-size: 1.2rem;
        color: #868e96;
    }

    .canvas-controls {
        margin-top: 1.5rem;
        display: flex;
        justify-content: center;
        gap: 1rem;
    }

    .thickness-controls {
        display: flex;
        align-items: center;
        margin-top: 1rem;
    }

    .thickness-label {
        margin-right: 0.5rem;
    }

    .navigation-controls {
        margin-top: 2rem;
        display: flex;
        justify-content: center;
        gap: 1rem;
    }

    .color-picker {
        display: flex;
        gap: 0.5rem;
        margin-top: 1rem;
        justify-content: center;
    }

    .color-option {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        cursor: pointer;
        border: 2px solid transparent;
    }

    .color-option.active {
        border-color: #000;
    }
</style>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Luyện nét chữ</h4>
            </div>
            <div class="card-body">
                @if($vocabulary)
                    <div class="character-display">
                        <div class="character-japanese">
                            @if($vocabulary->kanji)
                                {{ $vocabulary->kanji }}
                            @else
                                {{ $vocabulary->word }}
                            @endif
                        </div>
                        <div class="character-meaning">{{ $vocabulary->meaning }}</div>
                        <div class="character-romaji">{{ $vocabulary->romaji }}</div>
                    </div>

                    <div class="canvas-container">
                        <canvas id="drawing-canvas" width="600" height="400"></canvas>

                        <div class="color-picker">
                            <div class="color-option active" data-color="#000000" style="background-color: #000000;"></div>
                            <div class="color-option" data-color="#ff0000" style="background-color: #ff0000;"></div>
                            <div class="color-option" data-color="#0000ff" style="background-color: #0000ff;"></div>
                            <div class="color-option" data-color="#008000" style="background-color: #008000;"></div>
                        </div>

                        <div class="thickness-controls d-flex justify-content-center">
                            <label for="brush-thickness" class="thickness-label">Độ dày:</label>
                            <input type="range" id="brush-thickness" min="1" max="20" value="5" class="form-range" style="width: 200px;">
                        </div>

                        <div class="canvas-controls">
                            <button id="clear-canvas" class="btn btn-outline-secondary">Xóa</button>
                        </div>

                        <div class="navigation-controls">
                            <a href="{{ route('writing.index') }}" class="btn btn-primary">Từ tiếp theo</a>
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <h5>Chưa có từ vựng nào trong cơ sở dữ liệu</h5>
                        <p>Hãy thêm từ vựng mới để bắt đầu luyện tập!</p>
                        <a href="{{ route('vocabularies.create') }}" class="btn btn-primary mt-3">Thêm từ vựng mới</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        const canvas = document.getElementById('drawing-canvas');
        const ctx = canvas.getContext('2d');
        let isDrawing = false;
        let lastX = 0;
        let lastY = 0;
        let brushColor = '#000000';
        let brushThickness = 5;

        // Thiết lập canvas ban đầu
        ctx.lineJoin = 'round';
        ctx.lineCap = 'round';
        ctx.strokeStyle = brushColor;
        ctx.lineWidth = brushThickness;

        // Điều chỉnh kích thước canvas để phù hợp với container
        function resizeCanvas() {
            const containerWidth = $('.canvas-container').width();
            if (containerWidth < 600) {
                canvas.width = containerWidth;
                canvas.height = containerWidth * (2/3);
            }
        }

        // Thực hiện resize khi trang tải và khi thay đổi kích thước màn hình
        resizeCanvas();
        $(window).resize(resizeCanvas);

        // Sự kiện vẽ
        function startDrawing(e) {
            isDrawing = true;
            [lastX, lastY] = [
                e.type === 'mousedown' ? e.offsetX : e.touches[0].clientX - canvas.getBoundingClientRect().left,
                e.type === 'mousedown' ? e.offsetY : e.touches[0].clientY - canvas.getBoundingClientRect().top
            ];
        }

        function draw(e) {
            if (!isDrawing) return;
            e.preventDefault();

            const currentX = e.type === 'mousemove' ? e.offsetX : e.touches[0].clientX - canvas.getBoundingClientRect().left;
            const currentY = e.type === 'mousemove' ? e.offsetY : e.touches[0].clientY - canvas.getBoundingClientRect().top;

            ctx.beginPath();
            ctx.moveTo(lastX, lastY);
            ctx.lineTo(currentX, currentY);
            ctx.stroke();

            [lastX, lastY] = [currentX, currentY];
        }

        function stopDrawing() {
            isDrawing = false;
        }

        // Đăng ký sự kiện cho chuột
        canvas.addEventListener('mousedown', startDrawing);
        canvas.addEventListener('mousemove', draw);
        canvas.addEventListener('mouseup', stopDrawing);
        canvas.addEventListener('mouseout', stopDrawing);

        // Đăng ký sự kiện cho màn hình cảm ứng
        canvas.addEventListener('touchstart', startDrawing);
        canvas.addEventListener('touchmove', draw);
        canvas.addEventListener('touchend', stopDrawing);

        // Xóa canvas
        $('#clear-canvas').click(function() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        });

        // Thay đổi độ dày nét vẽ
        $('#brush-thickness').on('input', function() {
            brushThickness = $(this).val();
            ctx.lineWidth = brushThickness;
        });

        // Thay đổi màu nét vẽ
        $('.color-option').click(function() {
            $('.color-option').removeClass('active');
            $(this).addClass('active');
            brushColor = $(this).data('color');
            ctx.strokeStyle = brushColor;
        });
    });
</script>
@endsection
