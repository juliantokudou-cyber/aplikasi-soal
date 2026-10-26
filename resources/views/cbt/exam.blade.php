
@extends('layouts.app')

@section('title', 'Lembar Ujian Real-Time CBT')

@section('content')
<style>
    body {
        background-color: #f1f3f4;
        user-select: none;
        -webkit-user-select: none;
    }

    .prometric-header {
        background-color: #202124;
        color: #fff;
        border-bottom: 4px solid #0d6efd;
        z-index: 1020;
    }

    .sidebar-number {
        background-color: #fff;
        border-bottom: 1px solid #dadce0;
    }

    .question-box {
        background-color: #fff;
        border: 1px solid #dadce0;
        border-radius: 8px;
        min-height: 320px;
    }

    .shape-number {
        width: 40px;
        height: 40px;
        line-height: 36px;
        text-align: center;
        border: 2px solid #bdc1c6;
        border-radius: 6px;
        cursor: pointer;
        font-weight: bold;
        font-family: monospace;
        display: inline-block;
        background-color: #f8f9fa;
        color: #202124;
        flex-shrink: 0;
    }

    .shape-number.active {
        background-color: #0d6efd;
        color: #fff;
        border-color: #0d6efd;
    }

    .shape-number.answered {
        background-color: #6c757d;
        color: #fff;
        border-color: #6c757d;
    }

    .shape-number.correct-nav {
        background-color: #198754;
        color: #fff;
        border-color: #198754;
    }

    .shape-number.wrong-nav {
        background-color: #dc3545;
        color: #fff;
        border-color: #dc3545;
    }

    .option-container {
        border: 1px solid #dadce0;
        border-radius: 6px;
        padding: 12px 15px;
        cursor: pointer;
        transition: background-color .15s;
    }

    .option-container:hover {
        background-color: #f8f9fa;
    }

    .option-container label {
        cursor: pointer;
    }

    .option-container.selected-bg {
        background-color: #e8f0fe;
        border-color: #1a73e8;
    }

    .option-container.correct-ans {
        background-color: #d1e7dd !important;
        border-color: #198754 !important;
        color: #0f5132 !important;
        font-weight: bold;
    }

    .option-container.wrong-ans {
        background-color: #f8d7da !important;
        border-color: #dc3545 !important;
        color: #842029 !important;
    }

    .option-container.correct-ans label,
    .option-container.wrong-ans label {
        color: inherit !important;
    }

    /* Timer merah berkedip: 10 menit terakhir */
    #timerWrapper.timer-warning {
        background-color: #FFFF00 !important;
        color: #000000 !important;
        border-color: #ff6b6b !important;
        animation: timerBlink 1s infinite;
    }

    @keyframes timerBlink {
        0%, 100% { opacity: 1; }
        50% { opacity: .35; }
    }

    @media (prefers-reduced-motion: reduce) {
        #timerWrapper.timer-warning {
            animation: none;
            outline: 2px solid #dc3545;
        }
    }

    .exam-navigation {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }

    @media (min-width: 768px) {
        .sidebar-number {
            height: calc(100vh - 75px);
            overflow-y: auto;
            border-right: 1px solid #dadce0;
            border-bottom: none;
        }
    }

    @media (max-width: 767.98px) {
        .question-box {
            padding: 16px !important;
        }

        .prometric-header {
            padding-left: 10px !important;
            padding-right: 10px !important;
        }
    }
</style>

{{-- HEADER --}}
<div class="prometric-header py-3 px-3 d-flex
            justify-content-between align-items-center
            sticky-top shadow-sm">

    <div>
        <span class="fw-bold text-info small">
            <i class="bi bi-laptop me-1"></i>
            PROMETRIC TESTING
        </span>
    </div>

    <div class="d-flex align-items-center gap-2">
        <div id="timerWrapper"
             class="bg-dark border border-secondary text-warning
                    px-2 py-1 rounded fw-bold font-monospace small">

            <i class="bi bi-clock-history me-1"></i>
            <span id="countdownTimer">00:00:00</span>
        </div>

        <button type="button"
                id="btnFinishExam"
                class="btn btn-danger btn-sm rounded fw-bold">
            Selesaikan
        </button>

        <a href="{{ url('/dashboard') }}"
           id="btnBackHome"
           class="btn btn-outline-light btn-sm d-none">
            <i class="bi bi-house-door me-1"></i>
            Dashboard
        </a>
    </div>
</div>

<div class="container-fluid">
    <div class="row">

        {{-- NAVIGASI NOMOR --}}
        <div class="col-md-3 col-lg-2 sidebar-number py-3 px-3 shadow-sm">

            <h6 class="fw-bold text-secondary mb-3 text-uppercase small">
                <i class="bi bi-grid-3x3-gap me-1"></i>
                Navigasi Soal
            </h6>

            <div class="d-flex flex-wrap gap-2" id="navigationBox">
                @foreach($soals as $index => $soal)
                    <button type="button"
                            class="shape-number {{ $index === 0 ? 'active' : '' }}"
                            id="nav-{{ $index }}"
                            onclick="bukaSoal({{ $index }})">
                        {{ $index + 1 }}
                    </button>
                @endforeach
            </div>

            <hr>

            <div class="small text-secondary">
                <div class="mb-2">
                    <span class="badge bg-secondary">&nbsp;</span>
                    Sudah dijawab
                </div>

                <div id="legendCorrect" class="mb-2 d-none">
                    <span class="badge bg-success">&nbsp;</span>
                    Jawaban benar
                </div>

                <div id="legendWrong" class="d-none">
                    <span class="badge bg-danger">&nbsp;</span>
                    Jawaban salah
                </div>
            </div>
        </div>

        {{-- AREA UJIAN --}}
        <div class="col-md-9 col-lg-10 py-4 px-3">

            {{-- HASIL --}}
            <div id="scoreAlert"
                 class="alert alert-primary d-none border-0 shadow-sm p-4 mb-4 rounded-3">

                <div class="d-flex align-items-center gap-3">
                    <i class="bi bi-trophy-fill display-5 text-warning"></i>

                    <div>
                        <h4 class="fw-bold mb-2">
                            Ujian Selesai!
                            Skor Anda:
                            <span id="textSkor" class="text-danger">0</span>
                        </h4>

                        <p class="mb-0 small">
                            <span class="badge bg-success">
                                Benar: <span id="totalBenar">0</span>
                            </span>

                            <span class="badge bg-danger">
                                Salah: <span id="totalSalah">0</span>
                            </span>
                        </p>

                        <p class="small mt-2 mb-0">
                            Klik nomor soal untuk meninjau jawaban.
                        </p>
                    </div>
                </div>
            </div>

            <div class="question-box p-4 shadow-sm mb-4">

                <div id="examContainer">

                    @forelse($soals as $index => $soal)

                        <div class="soal-item-wrapper
                                    {{ $index !== 0 ? 'd-none' : '' }}"
                             id="soal-box-{{ $index }}"
                             data-correct="{{ strtoupper(trim((string) $soal->correct_answer)) }}">

                            <div class="d-flex align-items-center flex-wrap
                                        gap-2 mb-3 border-bottom pb-2">

                                <span class="badge bg-secondary py-2 px-3">
                                    Soal Nomor {{ $index + 1 }}
                                </span>

                                <span id="status-correct-{{ $index }}"
                                      class="badge bg-success d-none">
                                    <i class="bi bi-check-lg"></i>
                                    JAWABAN BENAR
                                </span>

                                <span id="status-wrong-{{ $index }}"
                                      class="badge bg-danger d-none">
                                    <i class="bi bi-x-lg"></i>
                                    JAWABAN SALAH
                                </span>
                            </div>

                            <h5 class="text-dark lh-base mb-3 fw-normal"
                                style="font-size:1.15rem;">
                                {{ $soal->question_text }}
                            </h5>

                            {{-- MEDIA --}}
                            @if($soal->media_type && $soal->media_file)
                                <div class="text-center my-3 p-2 bg-light rounded border">

                                    @if($soal->media_type === 'image')
                                        <img
                                            src="{{ asset('uploads/soal/' . $soal->media_file) }}"
                                            alt="Media soal {{ $index + 1 }}"
                                            class="img-fluid rounded shadow-sm"
                                            style="max-height:250px;object-fit:contain;">

                                    @elseif($soal->media_type === 'video')
                                        <video class="w-100 rounded shadow-sm"
                                               style="max-width:450px;"
                                               controls
                                               controlsList="nodownload">
                                            <source
                                                src="{{ asset('uploads/soal/' . $soal->media_file) }}"
                                                type="video/mp4">
                                            Browser Anda tidak mendukung video.
                                        </video>

                                    @elseif($soal->media_type === 'audio')
                                        <audio controls
                                               controlsList="nodownload"
                                               class="w-100"
                                               style="max-width:350px;">
                                            <source
                                                src="{{ asset('uploads/soal/' . $soal->media_file) }}"
                                                type="audio/mpeg">
                                            Browser Anda tidak mendukung audio.
                                        </audio>
                                    @endif

                                </div>
                            @endif

                            {{-- OPSI A, B, C --}}
                            <!-- Kontainer 3 Pilihan Jawaban (Mendukung Teks & Gambar) -->
<div class="space-options d-flex flex-column gap-2 mt-3" style="max-width: 550px;">
    
    <!-- OPSI A -->
    <div class="option-container d-flex align-items-center gap-3" data-opsi="A" onclick="pilihOpsi({{ $index }}, 'A')">
        <input type="radio" name="jawaban_{{ $index }}" id="opt-{{ $index }}-A" value="A" class="form-check-input my-0">
        <label class="form-check-label text-dark w-100" for="opt-{{ $index }}-A">
            <strong>A.</strong> 
            @if($soal->option_type == 'image')
                <img src="{{ asset('uploads/soal/' . $soal->option_a) }}" class="img-fluid rounded border mt-1" style="max-height: 100px; object-fit: contain; display: block;">
            @else
                {{ $soal->option_a }}
            @endif
        </label>
    </div>

    <!-- OPSI B -->
    <div class="option-container d-flex align-items-center gap-3" data-opsi="B" onclick="pilihOpsi({{ $index }}, 'B')">
        <input type="radio" name="jawaban_{{ $index }}" id="opt-{{ $index }}-B" value="B" class="form-check-input my-0">
        <label class="form-check-label text-dark w-100" for="opt-{{ $index }}-B">
            <strong>B.</strong> 
            @if($soal->option_type == 'image')
                <img src="{{ asset('uploads/soal/' . $soal->option_b) }}" class="img-fluid rounded border mt-1" style="max-height: 100px; object-fit: contain; display: block;">
            @else
                {{ $soal->option_b }}
            @endif
        </label>
    </div>

    <!-- OPSI C -->
    <div class="option-container d-flex align-items-center gap-3" data-opsi="C" onclick="pilihOpsi({{ $index }}, 'C')">
        <input type="radio" name="jawaban_{{ $index }}" id="opt-{{ $index }}-C" value="C" class="form-check-input my-0">
        <label class="form-check-label text-dark w-100" for="opt-{{ $index }}-C">
            <strong>C.</strong> 
            @if($soal->option_type == 'image')
                <img src="{{ asset('uploads/soal/' . $soal->option_c) }}" class="img-fluid rounded border mt-1" style="max-height: 100px; object-fit: contain; display: block;">
            @else
                {{ $soal->option_c }}
            @endif
        </label>
    </div>

</div>

                            {{--<div class="d-flex flex-column gap-2 mt-3"
                                 style="max-width:650px;">

                                @foreach(['A', 'B', 'C'] as $opsi)
                                    @php
                                        $field = 'option_' . strtolower($opsi);
                                    @endphp

                                    <div class="option-container d-flex
                                                align-items-center gap-3"
                                         data-opsi="{{ $opsi }}"
                                         onclick="pilihOpsi({{ $index }}, '{{ $opsi }}')">

                                        <input
                                            type="radio"
                                            name="jawaban_{{ $index }}"
                                            id="opt-{{ $index }}-{{ $opsi }}"
                                            value="{{ $opsi }}"
                                            class="form-check-input my-0 flex-shrink-0">

                                        <label
                                            for="opt-{{ $index }}-{{ $opsi }}"
                                            class="form-check-label text-dark w-100">
                                            <strong>{{ $opsi }}.</strong>
                                            {{ $soal->$field }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>--}}
                        </div>

                    @empty
                        <div class="alert alert-warning mb-0">
                            Belum ada soal yang tersedia untuk ujian ini.
                        </div>
                    @endforelse
                </div>

                {{-- NAVIGASI PREV / NEXT --}}
                <div class="exam-navigation border-top mt-4 pt-3">

                    <button type="button"
                            id="btnPrev"
                            class="btn btn-outline-secondary"
                            onclick="soalSebelumnya()"
                            disabled>
                        <i class="bi bi-chevron-left me-1"></i>
                        PREV
                    </button>

                    <span id="questionCounter" class="text-secondary small">
                        Soal 1 dari {{ $soals->count() }}
                    </span>

                    <button type="button"
                            id="btnNext"
                            class="btn btn-primary"
                            onclick="soalBerikutnya()"
                            {{ $soals->count() <= 1 ? 'disabled' : '' }}>
                        NEXT
                        <i class="bi bi-chevron-right ms-1"></i>
                    </button>
                </div>
            </div>

            {{-- LINK DASHBOARD HANYA SETELAH SELESAI --}}
            <div class="mb-4">
                <a href="{{ url('/dashboard') }}"
                   id="btnBackHomeBottom"
                   class="btn btn-outline-secondary d-none">
                    <i class="bi bi-house-door me-1"></i>
                    Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function ($) {
    'use strict';

    let currentIdx = 0;
    const totalSoal = Number(@json($soals->count()));
    let sisaWaktuDetik = Math.max(
        0,
        Number(@json($durasiMenit)) * 60
    );

    let isReviewMode = false;
    let ujianDigugurkan = false;
    let hasilSudahDihitung = false;
    let timerInterval = null;

    // =========================================
    // 1. TIMER DAN PERINGATAN 10 MENIT
    // =========================================

    function formatWaktu(totalDetik) {
        const jam = Math.floor(totalDetik / 3600);
        const menit = Math.floor((totalDetik % 3600) / 60);
        const detik = totalDetik % 60;

        return [
            String(jam).padStart(2, '0'),
            String(menit).padStart(2, '0'),
            String(detik).padStart(2, '0')
        ].join(':');
    }

    function updateCountdown() {
        $('#countdownTimer').text(formatWaktu(sisaWaktuDetik));

        if (sisaWaktuDetik <= 600 && sisaWaktuDetik > 0) {
            $('#timerWrapper').addClass('timer-warning');
        } else {
            $('#timerWrapper').removeClass('timer-warning');
        }
    }

    function mulaiTimer() {
        updateCountdown();

        if (totalSoal <= 0 || isReviewMode) {
            return;
        }

        if (sisaWaktuDetik <= 0) {
            kalkulasiHasilUjian('waktu');
            return;
        }

        timerInterval = setInterval(function () {
            if (isReviewMode || ujianDigugurkan) {
                clearInterval(timerInterval);
                timerInterval = null;
                return;
            }

            sisaWaktuDetik--;
            updateCountdown();

            if (sisaWaktuDetik <= 0) {
                clearInterval(timerInterval);
                timerInterval = null;
                kalkulasiHasilUjian('waktu');
            }
        }, 1000);
    }

    // =========================================
    // 2. PROTEKSI TOMBOL BACK
    // =========================================

    function aktifkanProteksiBack() {
        if (totalSoal <= 0) {
            return;
        }

        // Tambahkan satu entri history untuk mendeteksi Back.
        history.pushState(
            { halamanUjian: true },
            '',
            location.href
        );

        window.addEventListener('popstate', function () {
            if (isReviewMode || ujianDigugurkan) {
                return;
            }

            // Pulihkan entri history.
            history.pushState(
                { halamanUjian: true },
                '',
                location.href
            );

            gugurkanUjian();
        });
    }

    // Peringatan saat reload, menutup tab, atau meninggalkan halaman.
    // Browser menampilkan dialog bawaannya; website tidak dapat
    // mengubah teks dialog atau menjamin peserta membatalkan navigasi.
    window.addEventListener('beforeunload', function (e) {
        if (!isReviewMode && !ujianDigugurkan && totalSoal > 0) {
            e.preventDefault();
            e.returnValue = '';
        }
    });

    function gugurkanUjian() {
        if (ujianDigugurkan || isReviewMode) {
            return;
        }

        ujianDigugurkan = true;
        isReviewMode = true;

        if (timerInterval !== null) {
            clearInterval(timerInterval);
            timerInterval = null;
        }

        $('#examContainer input[type="radio"]').prop('disabled', true);

        $('#timerWrapper, #btnFinishExam').addClass('d-none');
        $('#examContainer, #navigationBox').addClass('d-none');
        $('#btnPrev, #btnNext').prop('disabled', true);

        // Penting:
        // Ini baru mengunci sisi browser. Status gugur permanen
        // memerlukan penyimpanan dan validasi melalui server Laravel.

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Ujian Dinyatakan Gugur',
                text: 'Anda terdeteksi menekan tombol kembali. Ujian dikunci. Hubungi pengawas atau administrator.',
                icon: 'error',
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false,
                confirmButtonText: 'Kembali ke Dashboard'
            }).then(function () {
                window.location.replace(@json(url('/dashboard')));
            });
        } else {
            alert(
                'Ujian dinyatakan gugur. Hubungi pengawas atau administrator.'
            );

            window.location.replace(@json(url('/dashboard')));
        }
    }

    // =========================================
    // 3. TOMBOL SELESAIKAN
    // =========================================

    $('#btnFinishExam').on('click', function () {
        if (isReviewMode || ujianDigugurkan || totalSoal <= 0) {
            return;
        }

        const jumlahDijawab = $(
            '#examContainer input[type="radio"]:checked'
        ).length;

        if (typeof Swal === 'undefined') {
            if (confirm(
                'Kirim jawaban sekarang? Terisi ' +
                jumlahDijawab + ' dari ' + totalSoal + ' soal.'
            )) {
                kalkulasiHasilUjian('manual');
            }
            return;
        }

        Swal.fire({
            title: 'Kirim Lembar Jawaban?',
            text: 'Jawaban terisi ' + jumlahDijawab +
                  ' dari ' + totalSoal +
                  '. Setelah dikirim, jawaban tidak dapat diubah.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Kirim Sekarang!',
            cancelButtonText: 'Batal'
        }).then(function (result) {
            if (
                result.isConfirmed &&
                !isReviewMode &&
                !ujianDigugurkan
            ) {
                kalkulasiHasilUjian('manual');
            }
        });
    });

    // =========================================
    // 4. KALKULASI NILAI DAN REVIEW
    // =========================================

    function kalkulasiHasilUjian(sumber) {
        if (hasilSudahDihitung || isReviewMode || ujianDigugurkan) {
            return;
        }

        hasilSudahDihitung = true;
        isReviewMode = true;

        if (timerInterval !== null) {
            clearInterval(timerInterval);
            timerInterval = null;
        }

        let benar = 0;
        let salah = 0;

        $('#timerWrapper, #btnFinishExam').addClass('d-none');

        $('#btnBackHome, #btnBackHomeBottom').removeClass('d-none');
        $('#legendCorrect, #legendWrong').removeClass('d-none');

        for (let i = 0; i < totalSoal; i++) {
            const $box = $('#soal-box-' + i);

            const keyCorrect = String(
                $box.attr('data-correct') || ''
            ).trim().toUpperCase();

            const userAns = $(
                'input[name="jawaban_' + i + '"]:checked'
            ).val() || '';

            $('input[name="jawaban_' + i + '"]')
                .prop('disabled', true);

            const $nav = $('#nav-' + i);

            $nav.removeClass('answered correct-nav wrong-nav');

            if (userAns !== '' && userAns === keyCorrect) {
                benar++;

                $nav.addClass('correct-nav');

                $('#status-correct-' + i).removeClass('d-none');
                $('#status-wrong-' + i).addClass('d-none');
            } else {
                salah++;

                $nav.addClass('wrong-nav');

                $('#status-wrong-' + i).removeClass('d-none');
                $('#status-correct-' + i).addClass('d-none');
            }

            $box.find('.option-container').each(function () {
                const opsiHuruf = String(
                    $(this).attr('data-opsi') || ''
                ).toUpperCase();

                $(this).removeClass(
                    'selected-bg correct-ans wrong-ans'
                );

                if (opsiHuruf === keyCorrect) {
                    $(this).addClass('correct-ans');
                }

                if (
                    opsiHuruf === userAns &&
                    userAns !== keyCorrect
                ) {
                    $(this).addClass('wrong-ans');
                }
            });
        }

        const totalSkor = totalSoal > 0
            ? Math.round((benar / totalSoal) * 3.75)
            : 0;

        $('#textSkor').text(totalSkor);
        $('#totalBenar').text(benar);
        $('#totalSalah').text(salah);
        $('#scoreAlert').removeClass('d-none');

        if (totalSoal > 0) {
            bukaSoal(0);
        }

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: sumber === 'waktu'
                    ? 'Waktu Habis!'
                    : 'Ujian Selesai!',
                text: 'Skor Anda: ' + totalSkor +
                      '. Silakan tinjau jawaban Anda.',
                icon: 'success',
                confirmButtonText: 'Mulai Tinjau'
            });
        }
    }

    // =========================================
    // 5. NAVIGASI SOAL
    // =========================================

    window.bukaSoal = function (index) {
        index = Number(index);

        if (
            !Number.isInteger(index) ||
            index < 0 ||
            index >= totalSoal ||
            ujianDigugurkan
        ) {
            return;
        }

        $('.soal-item-wrapper').addClass('d-none');
        $('#soal-box-' + index).removeClass('d-none');

        $('.shape-number').removeClass('active');
        $('#nav-' + index).addClass('active');

        currentIdx = index;

        $('#btnPrev').prop('disabled', currentIdx === 0);
        $('#btnNext').prop(
            'disabled',
            currentIdx >= totalSoal - 1
        );

        $('#questionCounter').text(
            'Soal ' + (currentIdx + 1) + ' dari ' + totalSoal
        );
    };

    window.soalBerikutnya = function () {
        if (!isReviewMode && !ujianDigugurkan) {
            if (currentIdx < totalSoal - 1) {
                bukaSoal(currentIdx + 1);
            }
        } else if (isReviewMode && !ujianDigugurkan) {
            if (currentIdx < totalSoal - 1) {
                bukaSoal(currentIdx + 1);
            }
        }
    };

    window.soalSebelumnya = function () {
        if (currentIdx > 0 && !ujianDigugurkan) {
            bukaSoal(currentIdx - 1);
        }
    };

    // =========================================
    // 6. PILIH JAWABAN
    // =========================================

    window.pilihOpsi = function (soalIndex, opsi) {
        if (isReviewMode || ujianDigugurkan) {
            return;
        }

        const $radio = $('#opt-' + soalIndex + '-' + opsi);

        if ($radio.length === 0 || $radio.prop('disabled')) {
            return;
        }

        $radio.prop('checked', true);

        $('#soal-box-' + soalIndex + ' .option-container')
            .removeClass('selected-bg');

        $radio.closest('.option-container')
            .addClass('selected-bg');

        $('#nav-' + soalIndex)
            .removeClass('correct-nav wrong-nav')
            .addClass('answered');
    };

    // =========================================
    // 7. INISIALISASI
    // =========================================

    $(document).ready(function () {
        if (totalSoal > 0) {
            aktifkanProteksiBack();
            bukaSoal(0);
            mulaiTimer();
        } else {
            $('#btnFinishExam, #btnPrev, #btnNext')
                .prop('disabled', true);

            $('#timerWrapper').addClass('d-none');
        }
    });

})(jQuery);
</script>
@endpush

