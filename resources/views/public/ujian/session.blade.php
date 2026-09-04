@extends('public.layouts.exam')

@section('title', 'Lembar Ujian: ' . $exam->judul)

@section('content')
<div class="min-h-screen bg-slate-50 pb-16">
    {{-- Sticky Header Bar with Countdown Timer --}}
    <div class="sticky top-14 sm:top-16 z-40 bg-white text-slate-900 border-b border-slate-200 shadow-sm">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 py-3 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse shrink-0"></div>
                <div>
                    <div class="font-extrabold text-xs sm:text-sm text-slate-900 truncate max-w-[150px] sm:max-w-md">{{ $exam->judul }}</div>
                    <div class="text-[11px] text-slate-500 font-medium">{{ $attempt->nama_peserta }} ({{ $attempt->kelas }})</div>
                </div>
            </div>

            {{-- Real-time Timer Badge --}}
            <div class="flex items-center gap-2 bg-amber-50 border border-amber-200 rounded-2xl px-4 py-1.5 shadow-sm">
                <span class="text-xs text-amber-800 font-bold hidden sm:inline">Sisa Waktu:</span>
                <div id="exam-timer" class="font-mono text-sm sm:text-base font-black text-amber-700 tracking-wider">
                    00:00:00
                </div>
            </div>

            <button type="button" onclick="confirmFinishExam()" class="px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 text-xs font-bold transition-all shadow-sm cursor-pointer uppercase tracking-wider">
                Selesai Ujian
            </button>
        </div>
    </div>

    {{-- Main Container --}}
    <main class="mx-auto max-w-7xl px-4 sm:px-6 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            
            {{-- Questions Column --}}
            <div class="lg:col-span-3 space-y-6">
                @forelse($questions as $index => $q)
                    @php
                        $savedAnswer = $answers[$q->id_question] ?? null;
                    @endphp
                    <div id="question-card-{{ $index + 1 }}" class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm space-y-5 scroll-mt-32">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                            <div class="flex items-center gap-2">
                                <span class="w-8 h-8 rounded-xl bg-slate-900 text-white font-extrabold text-xs flex items-center justify-center">
                                    {{ $index + 1 }}
                                </span>
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">
                                    Soal {{ $index + 1 }} dari {{ count($questions) }}
                                </span>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-md bg-slate-100 text-slate-600 text-xs font-bold uppercase">
                                {{ $q->jenis === 'pilihan_ganda' ? 'Pilihan Ganda' : 'Essay' }}
                            </span>
                        </div>

                        <div class="text-base font-bold text-slate-800 leading-relaxed">
                            {!! nl2br(e($q->pertanyaan)) !!}
                        </div>

                        @if($q->gambar)
                            <div class="my-3 rounded-2xl overflow-hidden border border-slate-200 bg-slate-50 p-2 max-w-2xl">
                                <img src="{{ asset($q->gambar) }}" alt="Gambar Soal {{ $index + 1 }}" class="max-h-96 object-contain rounded-xl w-full">
                            </div>
                        @endif

                        @if($q->jenis === 'pilihan_ganda')
                            <div class="space-y-2.5 pt-2">
                                @foreach(['A' => $q->pilihan_a, 'B' => $q->pilihan_b, 'C' => $q->pilihan_c, 'D' => $q->pilihan_d, 'E' => $q->pilihan_e] as $optKey => $optVal)
                                    @if($optVal)
                                        @php $isChecked = (strtoupper(trim($savedAnswer)) === $optKey); @endphp
                                        <label class="flex items-start gap-3 p-3.5 rounded-2xl border transition-all cursor-pointer group {{ $isChecked ? 'bg-emerald-50/80 border-emerald-400 ring-2 ring-emerald-100' : 'bg-slate-50/60 border-slate-200 hover:border-emerald-300 hover:bg-white' }}">
                                            <input type="radio" name="q_{{ $q->id_question }}" value="{{ $optKey }}" {{ $isChecked ? 'checked' : '' }}
                                                   onchange="saveAnswer({{ $q->id_question }}, '{{ $optKey }}', {{ $index + 1 }})"
                                                   class="mt-1 w-4 h-4 text-emerald-600 border-slate-300 focus:ring-emerald-500">
                                            <span class="w-6 h-6 rounded-lg bg-white border border-slate-200 text-slate-700 text-xs font-extrabold flex items-center justify-center shrink-0 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                                                {{ $optKey }}
                                            </span>
                                            <span class="text-sm font-medium text-slate-700 leading-snug pt-0.5">
                                                {{ $optVal }}
                                            </span>
                                        </label>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            {{-- Essay Input --}}
                            <div class="pt-2">
                                <textarea rows="4" placeholder="Tuliskan jawaban essay Anda di sini..."
                                          onblur="saveAnswer({{ $q->id_question }}, this.value, {{ $index + 1 }})"
                                          class="w-full p-4 rounded-2xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 text-sm text-slate-800 outline-none transition-all">{{ $savedAnswer }}</textarea>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="bg-white rounded-3xl border border-slate-200 p-12 text-center text-slate-400 text-sm">
                        Belum ada soal pada ujian ini.
                    </div>
                @endforelse
            </div>

            {{-- Question Navigation Sidebar --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm space-y-4 sticky top-36">
                    <h3 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-3 flex items-center justify-between">
                        <span>Navigasi Soal</span>
                        <span class="text-xs text-slate-400 font-medium"><strong id="answered-count">0</strong> / {{ count($questions) }} Terjawab</span>
                    </h3>

                    <div class="grid grid-cols-5 gap-2 max-h-72 overflow-y-auto p-1">
                        @foreach($questions as $index => $q)
                            @php $isAnswered = isset($answers[$q->id_question]) && filled($answers[$q->id_question]); @endphp
                            <button type="button" id="nav-btn-{{ $index + 1 }}" onclick="scrollToQuestion({{ $index + 1 }})"
                                    class="w-10 h-10 rounded-xl font-bold text-xs flex items-center justify-center transition-all cursor-pointer {{ $isAnswered ? 'bg-emerald-600 text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                                {{ $index + 1 }}
                            </button>
                        @endforeach
                    </div>

                    <div class="pt-4 border-t border-slate-100 space-y-2 text-xs text-slate-500 font-medium">
                        <div class="flex items-center gap-2">
                            <span class="w-3.5 h-3.5 rounded-md bg-emerald-600"></span>
                            <span>Sudah Dijawab</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3.5 h-3.5 rounded-md bg-slate-100 border border-slate-300"></span>
                            <span>Belum Dijawab</span>
                        </div>
                    </div>

                    <button type="button" onclick="confirmFinishExam()" class="w-full py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs transition-colors shadow-md cursor-pointer mt-2">
                        ✓ Kirim Jawaban & Selesai
                    </button>
                </div>
            </div>

        </div>
    </main>
</div>

{{-- Hidden Form for Submit --}}
<form id="finish-exam-form" action="{{ route('public.ujian.finish', $attempt->id_attempt) }}" method="POST" class="hidden">
    @csrf
</form>

{{-- Finish Confirmation Modal --}}
<div id="finish-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/70 backdrop-blur-sm p-4">
    <div class="bg-white rounded-3xl p-8 max-w-md w-full text-center space-y-5 shadow-2xl animate-in zoom-in-95 duration-200">
        <div class="w-14 h-14 rounded-2xl bg-emerald-100 text-emerald-700 font-bold text-2xl flex items-center justify-center mx-auto">
            ✓
        </div>
        <div class="space-y-1">
            <h3 class="text-xl font-bold text-slate-800">Selesaikan Ujian ini?</h3>
            <p class="text-xs text-slate-500">Pastikan Anda telah mengecek semua jawaban sebelum mengakhiri sesi ujian.</p>
        </div>
        <div class="flex gap-3 pt-2">
            <button type="button" onclick="closeFinishModal()" class="flex-1 py-2.5 rounded-xl border border-slate-200 text-slate-700 text-xs font-bold hover:bg-slate-50">
                Batal
            </button>
            <button type="button" onclick="document.getElementById('finish-exam-form').submit()" class="flex-1 py-2.5 rounded-xl bg-emerald-600 text-white text-xs font-bold hover:bg-emerald-700 shadow-md">
                Ya, Selesaikan
            </button>
        </div>
    </div>
</div>

<script>
    var remainingSeconds = {{ $sisaDetik }};
    var timerElement = document.getElementById('exam-timer');
    var finishForm = document.getElementById('finish-exam-form');
    var attemptId = {{ $attempt->id_attempt }};
    var csrfToken = "{{ csrf_token() }}";

    var isSubmitting = false;

    function updateTimerDisplay() {
        if (remainingSeconds <= 0) {
            timerElement.textContent = "00:00:00";
            if (!isSubmitting) {
                isSubmitting = true;
                finishForm.submit();
            }
            return;
        }

        var hours = Math.floor(remainingSeconds / 3600);
        var minutes = Math.floor((remainingSeconds % 3600) / 60);
        var seconds = remainingSeconds % 60;

        var hStr = hours < 10 ? "0" + hours : hours;
        var mStr = minutes < 10 ? "0" + minutes : minutes;
        var sStr = seconds < 10 ? "0" + seconds : seconds;

        timerElement.textContent = hStr + ":" + mStr + ":" + sStr;
        remainingSeconds--;
    }

    setInterval(updateTimerDisplay, 1000);
    updateTimerDisplay();

    function scrollToQuestion(index) {
        var el = document.getElementById('question-card-' + index);
        if (el) el.scrollIntoView({ behavior: 'smooth' });
    }

    function saveAnswer(idQuestion, jawaban, index) {
        fetch('/ujian/session/' + attemptId + '/answer', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                id_question: idQuestion,
                jawaban: jawaban
            })
        }).then(function(res) { return res.json(); })
          .then(function(data) {
              if (data.status === 'expired' && data.redirect) {
                  window.location.href = data.redirect;
                  return;
              }
              if (data.status === 'success') {
                  var navBtn = document.getElementById('nav-btn-' + index);
                  if (navBtn) {
                      navBtn.className = 'w-10 h-10 rounded-xl font-bold text-xs flex items-center justify-center transition-all cursor-pointer bg-emerald-600 text-white shadow-sm';
                  }
                  updateAnsweredCount();
              }
          });
    }

    function updateAnsweredCount() {
        var count = document.querySelectorAll('.bg-emerald-600.text-white').length;
        var el = document.getElementById('answered-count');
        if (el) el.textContent = count;
    }

    document.addEventListener('DOMContentLoaded', updateAnsweredCount);

    function confirmFinishExam() {
        document.getElementById('finish-modal').classList.remove('hidden');
        document.getElementById('finish-modal').classList.add('flex');
    }

    function closeFinishModal() {
        document.getElementById('finish-modal').classList.add('hidden');
        document.getElementById('finish-modal').classList.remove('flex');
    }
</script>
@endsection
