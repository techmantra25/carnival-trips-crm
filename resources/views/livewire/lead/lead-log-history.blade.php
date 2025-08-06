<div>
    <div class="box custom-box">
        <div class="box-body">
            <div class="grid grid-cols-12 gap-3">
                <div class="col-span-12">
                    {{-- Timeline Output --}}
                    <div class="space-y-6">
                        @forelse($logs as $log)
                            @php
                                $msg = json_decode($log->message, true);
                                $action = $msg['action'] ?? 'Unknown Action';
                                $timestamp = $msg['timestamp'] ?? $log->created_at;
                            @endphp

                            <div class="relative pl-6 border-l-2 border-blue-200">
                                <div class="absolute left-[-8px] top-1.5 w-4 h-4 rounded-full bg-blue-500 border-2 border-white shadow"></div>
                                <div class="bg-white p-4 rounded shadow-sm">
                                    <div class="text-sm text-gray-700">
                                        <strong class="text-indigo-700">{{ $action }}</strong><br>

                                        {{-- Assigned Logs --}}
                                        @if(isset($msg['assigned_by']) && isset($msg['assigned_to']))
                                            <span class="text-sm">
                                                <span class="font-medium">Assigned To:</span> {{ $msg['assigned_to'] }}<br>
                                                <span class="font-medium">Team Lead:</span> {{ $msg['team_lead'] ?? 'N/A' }}<br>
                                                <span class="font-medium">Assigned By:</span> {{ $msg['assigned_by'] }}
                                            </span>
                                        
                                        {{-- Shared Logs --}}
                                        @elseif(isset($msg['shared_by']) && isset($msg['shared_link_ids']))
                                            <span class="text-sm">
                                                <span class="font-medium">Shared By:</span> {{ $msg['shared_by'] }}<br>
                                                <span class="font-medium">Link:</span> 
                                                <a href="{{ $msg['shared_link_ids'] }}" class="text-blue-600 underline text-xs" target="_blank">
                                                    {{ $msg['shared_link_ids'] }}
                                                </a>
                                            </span>
                                        
                                        {{-- General Key-Value Log --}}
                                        @else
                                            <ul class="text-sm list-disc list-inside text-gray-600">
                                                @foreach($msg as $key => $value)
                                                    <li><strong>{{ ucwords(str_replace('_', ' ', $key)) }}:</strong> {{ $value }}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>

                                    <div class="text-xs text-gray-500 mt-2">
                                        {{ \Carbon\Carbon::parse($timestamp)->format('d M Y h:i A') }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-gray-500">No logs available for selected date range.</div>
                        @endforelse

                    </div>

                </div>
            </div>
        </div>
    </div>
    <div wire:loading class="loader">
        <div class="spinner">
        <img src="{{asset('build/assets/images/media/loader.svg')}}" alt="">
        </div>
    </div>
</div>
