<div>
    <div class="box custom-box">
        <div class="box-body">

            <div class="grid grid-cols-12 gap-3">
                <div class="col-span-12">

                    {{-- Timeline --}}
                    <div class="relative border-l-2 border-indigo-300 pl-6 space-y-8">

                        @forelse($logs as $log)
                            @php
                                $msg = json_decode($log->message, true);
                                $action = $msg['action'] ?? 'Unknown Action';
                                $timestamp = $msg['timestamp'] ?? $log->created_at;
                            @endphp

                            {{-- Timeline step --}}
                            <div class="relative">

                                {{-- Dot --}}
                                <span class="absolute -left-3 w-6 h-6 bg-indigo-600 rounded-full border-4 border-white shadow-md flex items-center justify-center">
                                    <i class="ti ti-check text-white text-xs"></i>
                                </span>

                                {{-- Card --}}
                                <div class="bg-white rounded-xl shadow-md p-5 hover:shadow-lg transition duration-200">

                                    {{-- Action Title --}}
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="text-indigo-700 font-semibold text-sm">
                                            {{ $action }}
                                        </span>
                                    </div>

                                    {{-- Assigned Logs --}}
                                    @if(isset($msg['assigned_by']) && isset($msg['assigned_to']))
                                        <div class="text-gray-700 text-sm space-y-1">

                                            <div>
                                                <span class="font-medium">Assigned To:</span>
                                                {{ $msg['assigned_to'] }}
                                            </div>

                                            <div>
                                                <span class="font-medium">Team Lead:</span>
                                                {{ $msg['team_lead'] ?? 'N/A' }}
                                            </div>

                                            <div>
                                                <span class="font-medium">Assigned By:</span>
                                                {{ $msg['assigned_by'] }}
                                            </div>
                                        </div>

                                    {{-- Shared Logs --}}
                                    @elseif(isset($msg['shared_by']) && isset($msg['shared_link_ids']))

                                        <div class="text-gray-700 text-sm space-y-1">
                                            <div>
                                                <span class="font-medium">Shared By:</span>
                                                {{ $msg['shared_by'] }}
                                            </div>

                                            <div>
                                                <span class="font-medium">Link:</span>
                                                <a href="{{ $msg['shared_link_ids'] }}" class="text-blue-600 underline text-xs" target="_blank">
                                                    {{ $msg['shared_link_ids'] }}
                                                </a>
                                            </div>
                                        </div>

                                    {{-- General Key-Value --}}
                                    @else
                                        <ul class="space-y-2 text-gray-700 text-sm">
                                            @foreach($msg as $key => $value)
                                                <li class="border-b border-gray-100 pb-1">
                                                    @if (is_array($value))
                                                        <div class="font-semibold text-gray-800">
                                                            {{ ucwords(str_replace('_', ' ', $key)) }}:
                                                        </div>

                                                        <ul class="ml-4 mt-1 space-y-1 text-gray-600 list-disc list-inside">
                                                            @foreach($value as $subKey => $subValue)
                                                                <li>
                                                                    <span class="font-medium">{{ ucwords(str_replace('_', ' ', $subKey)) }}:</span>

                                                                    @if (filter_var($subValue, FILTER_VALIDATE_URL))
                                                                        <a href="{{ $subValue }}" class="text-blue-600 underline text-xs" target="_blank">
                                                                            {{ $subValue }}
                                                                        </a>
                                                                    @else
                                                                        {{ $subValue }}
                                                                    @endif
                                                                </li>
                                                            @endforeach
                                                        </ul>

                                                    @else
                                                        <div class="flex items-center">
                                                            <span class="font-medium text-gray-800">{{ ucwords(str_replace('_', ' ', $key)) }}:</span>

                                                            @if (filter_var($value, FILTER_VALIDATE_URL))
                                                                <a href="{{ $value }}" class="text-blue-600 underline text-xs ml-1" target="_blank">
                                                                    {{ $value }}
                                                                </a>
                                                            @else
                                                                <span class="ml-1">{{ $value }}</span>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif

                                    {{-- Timestamp --}}
                                    <div class="text-xs text-gray-500 mt-3 text-right">
                                        {{ \Carbon\Carbon::parse($timestamp)->format('d M Y h:i A') }}
                                    </div>
                                </div>
                            </div>

                        @empty
                            <div class="text-center text-gray-500 py-4">
                                No logs available for selected customer.
                            </div>
                        @endforelse

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Loader --}}
    <div wire:loading class="loader">
        <div class="spinner">
            <img src="{{ asset('build/assets/images/media/loader.svg') }}" alt="">
        </div>
    </div>
</div>
