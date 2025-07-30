<div>
    <style>
        /* Optional shadow & animation improvements */
        .box.custom-box {
            transition: all 0.3s ease-in-out;
        }

        .box.custom-box:hover {
            transform: translateY(-2px);
        }

        .loader .spinner {
            display: flex;
            justify-content: center;
            padding: 2rem 0;
        }

    </style>
    <div class="box custom-box">
        <div class="box-body">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($preferences as $step => $items)
                    <div class="bg-white shadow-md rounded-lg p-4 border border-gray-200">
                        <h2 class="text-lg font-bold text-primary mb-3">Step {{ $step }}</h2>
                        <ul class="space-y-2 text-sm">
                            @foreach($items as $item)
                                <li>
                                    <p><span class="font-semibold">Q:</span> {{ $item->question }}</p>
                                    <p><span class="font-semibold text-gray-600">A:</span> {{ $item->answer }}</p>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div wire:loading class="loader">
        <div class="spinner">
        <img src="{{asset('build/assets/images/media/loader.svg')}}" alt="">
        </div>
    </div>
</div>
