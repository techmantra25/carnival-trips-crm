<div class="container py-4">
    <style>
        .tab-pane .btn-option {
      border: 1px solid #ccc;
      border-radius: 20px;
      margin-bottom: 10px;
      width: 100%;
    }
    .nav-tabs .nav-link.active {
      background-color: #d63384;
      color: white;
    }
    .form-section {
      max-width: 500px;
      margin: auto;
      padding-top: 20px;
    }
    .progress-line {
      height: 4px;
      background: linear-gradient(to right, orange, purple);
      margin-bottom: 15px;
    }
    .nav-controls {
      display: flex;
      justify-content: space-between;
      margin-top: 20px;
    }
    </style>

    <div class="container py-4">
      <div class="form-section">
        <!-- NAV TABS (Livewire-controlled) -->
        <ul class="nav nav-tabs mb-3" id="formTabs" role="tablist">
            @foreach($questions as $index => $q)
                @php $step = $index + 1; @endphp
                <li class="nav-item">
                    <button
                        type="button"
                        class="nav-link {{ $currentStep == $step ? 'active' : '' }}"
                        wire:click.prevent="goToStep({{ $step }})"
                    >
                        {{ $step }}
                    </button>
                </li>
            @endforeach
        </ul>

        <!-- TAB CONTENT -->
        <div class="tab-content" id="formTabsContent">
          @foreach($questions as $index => $q)
            @php $step = $index + 1; @endphp
            <div class="tab-pane fade {{ $currentStep == $step ? 'show active' : '' }}">
                <div class="progress-line"></div>

                <h6>{{ $q->title }}</h6>

                @foreach($q->options as $opt)
                    @php $optText = e($opt->option_text); @endphp
                    <button
                        class="btn btn-option w-100 mb-2 {{ ($selected[$step] ?? '') == $opt->option_text ? 'btn-primary text-white' : 'btn-outline-secondary' }}"
                        wire:click="storeAnswer({{ $step }}, '{!! addslashes($opt->option_text) !!}')"
                        type="button"
                    >
                        {!! $optText !!}
                    </button>
                @endforeach

                <div class="nav-controls {{ $step == 1 ? 'justify-content-end' : '' }}">
                    @if($step > 1)
                        <button wire:click.prevent="goToStep({{ $step - 1 }})" class="btn btn-secondary">Back</button>
                    @endif

                    @if($step < $totalSteps)
                        <button wire:click.prevent="goToStep({{ $step + 1 }})" class="btn btn-primary">Next</button>
                    @else
                        <button wire:click.prevent="finish" class="btn btn-success">Submit</button>
                    @endif
                </div>
            </div>
          @endforeach

          {{-- THANK YOU SCREEN --}}
          @if($currentStep > $totalSteps)
            <div class="tab-pane fade show active">
                <div class="progress-line"></div>

                <div class="text-center p-3">
                      <h5>Thanks, you're all set.</h5>
                      <p>Thank You for Your Interest in Meghalaya!</p>
                      <p>We've received your inquiry and will reach out soon with more details.</p>

                    <button class="btn btn-primary w-100 mt-3" wire:click.prevent="goToStep(1)">
                        Start Over  
                    </button>
                </div>
            </div>
          @endif

        </div>
      </div>
    </div>

    {{-- In work, do what you enjoy. --}}
</div>
@section('scripts')
<script>
  // Handle Next
  document.querySelectorAll('.next-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const nextTab = btn.dataset.next;
      const trigger = document.querySelector(`button[data-bs-target="${nextTab}"]`);
      if (trigger) trigger.click();
    });
  });

  // Handle Back
  document.querySelectorAll('.back-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const backTab = btn.dataset.back;
      const trigger = document.querySelector(`button[data-bs-target="${backTab}"]`);
      if (trigger) trigger.click();
    });
  });

  // Add numbers 1–10 to step 3
  const step3Btns = document.querySelector('#step3 .row');
  for (let i = 1; i <= 10; i++) {
    const btn = document.createElement('button');
    btn.className = 'btn btn-outline-secondary btn-option';
    btn.innerText = i;
    const col = document.createElement('div');
    col.className = 'col';
    col.appendChild(btn);
    step3Btns.appendChild(col);
  }
</script>
@endsection
