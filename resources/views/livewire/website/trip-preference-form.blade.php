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

    <div class="form-section">
    <!-- Nav Tabs -->
    <ul class="nav nav-tabs mb-3" id="formTabs" role="tablist">
      <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#step1" type="button">1</button></li>
      <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#step2" type="button">2</button></li>
      <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#step3" type="button">3</button></li>
      <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#step4" type="button">4</button></li>
      <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#step5" type="button">5</button></li>
      <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#step6" type="button">6</button></li>
      <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#step7" type="button">7</button></li>
      <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#step8" type="button">8</button></li>
      <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#step9" type="button">9</button></li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="formTabsContent">
      <!-- Step 1 -->
      <div class="tab-pane fade show active" id="step1">
        <div class="progress-line"></div>
        <h6>Are you looking for a Personal trip or Join other Groups?</h6>
        <button class="btn btn-outline-secondary btn-option">Personal Trip</button>
        <button class="btn btn-outline-secondary btn-option">Joining other Groups</button>
        <div class="nav-controls justify-content-end">
          <button class="btn btn-primary next-btn" data-next="#step2">Next</button>
        </div>
      </div>

      <!-- Step 2 -->
      <div class="tab-pane fade" id="step2">
        <div class="progress-line"></div>
        <h6>Select Duration of Package</h6>
        <button class="btn btn-outline-secondary btn-option">4N 5D</button>
        <button class="btn btn-outline-secondary btn-option">5N 6D</button>
        <button class="btn btn-outline-secondary btn-option">6N 7D</button>
        <div class="nav-controls">
          <button class="btn btn-secondary back-btn" data-back="#step1">Back</button>
          <button class="btn btn-primary next-btn" data-next="#step3">Next</button>
        </div>
      </div>

      <!-- Step 3 -->
      <div class="tab-pane fade" id="step3">
        <div class="progress-line"></div>
        <h6>Select Travelers Count</h6>
        <div class="row row-cols-3 g-2">
          <!-- JS will add 1 to 10 -->
        </div>
        <button class="btn btn-outline-secondary btn-option mt-2">More than 10</button>
        <div class="nav-controls">
          <button class="btn btn-secondary back-btn" data-back="#step2">Back</button>
          <button class="btn btn-primary next-btn" data-next="#step4">Next</button>
        </div>
      </div>

      <!-- Step 4 -->
      <div class="tab-pane fade" id="step4">
        <div class="progress-line"></div>
        <h6>Select a Tentative Month</h6>
        <button class="btn btn-outline-secondary btn-option">JULY 2025</button>
        <button class="btn btn-outline-secondary btn-option">AUGUST 2025</button>
        <button class="btn btn-outline-secondary btn-option">SEPTEMBER 2025</button>
        <button class="btn btn-outline-secondary btn-option">OCTOBER</button>
        <button class="btn btn-outline-secondary btn-option">NOVEMBER</button>
        <button class="btn btn-outline-secondary btn-option">DECEMBER</button>
        <div class="nav-controls">
          <button class="btn btn-secondary back-btn" data-back="#step3">Back</button>
          <button class="btn btn-primary next-btn" data-next="#step5">Next</button>
        </div>
      </div>

      <!-- Step 5 -->
      <div class="tab-pane fade" id="step5">
        <div class="progress-line"></div>
        <h6>Select Per Person Budget</h6>
        <button class="btn btn-outline-secondary btn-option">₹15,500</button>
        <button class="btn btn-outline-secondary btn-option">₹18,500</button>
        <button class="btn btn-outline-secondary btn-option">₹24,500</button>
        <div class="nav-controls">
          <button class="btn btn-secondary back-btn" data-back="#step4">Back</button>
          <button class="btn btn-primary next-btn" data-next="#step6">Next</button>
        </div>
      </div>

      <!-- Step 6 -->
      <div class="tab-pane fade" id="step6">
        <div class="progress-line"></div>
        <h6>Hotel Preference</h6>
        <button class="btn btn-outline-secondary btn-option">Standard</button>
        <button class="btn btn-outline-secondary btn-option">Deluxe</button>
        <button class="btn btn-outline-secondary btn-option">Super Deluxe</button>
        <div class="nav-controls">
          <button class="btn btn-secondary back-btn" data-back="#step5">Back</button>
          <button class="btn btn-primary next-btn" data-next="#step7">Next</button>
        </div>
      </div>

      <!-- Step 7 -->
      <div class="tab-pane fade" id="step7">
        <div class="progress-line"></div>
        <h6>How Soon will You Confirm the Booking?</h6>
        <button class="btn btn-outline-secondary btn-option">Within 3 Days</button>
        <button class="btn btn-outline-secondary btn-option">Within 5 Days</button>
        <button class="btn btn-outline-secondary btn-option">Within 10 Days</button>
        <div class="nav-controls">
          <button class="btn btn-secondary back-btn" data-back="#step6">Back</button>
          <button class="btn btn-primary next-btn" data-next="#step8">Next</button>
        </div>
      </div>

      <!-- Step 8 -->
      <div class="tab-pane fade" id="step8">
        <div class="progress-line"></div>
        <h6>Contact Info</h6>
        <input type="text" class="form-control mb-2" placeholder="Full Name" />
        <input type="email" class="form-control mb-2" placeholder="Email" />
        <input type="text" class="form-control mb-2" placeholder="Phone Number" />
        <input type="text" class="form-control mb-2" placeholder="City" />
        <div class="nav-controls">
          <button class="btn btn-secondary back-btn" data-back="#step7">Back</button>
          <button class="btn btn-primary next-btn" data-next="#step9">Submit</button>
        </div>
        <p class="small mt-2 text-muted">By clicking submit, you agree to send your info to Carnival Trips...</p>
      </div>

      <!-- Step 9 -->
      <div class="tab-pane fade" id="step9">
        <div class="progress-line"></div>
        <h5>Thanks, you're all set.</h5>
        <p>Thank You for Your Interest in Meghalaya!</p>
        <p>We've received your inquiry and will reach out soon with more details.</p>
        <div class="nav-controls justify-content-start">
          <button class="btn btn-secondary back-btn" data-back="#step8">Back</button>
        </div>
        <button class="btn btn-primary w-100 mt-3">View website</button>
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
