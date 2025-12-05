<style>
.container.itinerary-theme {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    max-width: 900px;
    margin: auto;
    padding: 20px;
    background: #fefefe;
    border: 1px solid #ccc;
    border-radius: 8px;
}

.itinerary-header {
    display: flex;
    justify-content: space-between;
    background: #2bb2a5;
    color: white;
    padding: 20px;
    border-radius: 8px 8px 0 0;
}

.itinerary-header h1 {
    margin-top: 0;
    font-size: 28px;
}

.header-text p {
    margin: 4px 0;
}

.header-graphic img {
    height: 100px;
}

.day-box {
    margin-top: 30px;
    border: 1px solid #2bb2a5;
    border-radius: 8px;
    overflow: hidden;
}

.day-label {
    background: #d4f1ef;
    padding: 10px 20px;
    font-weight: bold;
    display: flex;
    align-items: center;
    border-bottom: 1px solid #2bb2a5;
}

.check-icon {
    color: green;
    font-size: 20px;
    margin-right: 10px;
}

.day-title {
    font-size: 18px;
    color: #003d4d;
}

.info-blocks {
    padding: 15px 20px;
    background-color: #f3fafa;
    font-size: 14px;
    border-bottom: 1px solid #ddd;
}

.route-title {
    padding: 10px 20px;
    font-weight: bold;
    font-size: 16px;
    background: #eef9f8;
    color: #004e5a;
}

.activity-table {
    padding: 15px 20px;
}

.activity-table table {
    width: 100%;
    border-collapse: collapse;
    background: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 5px;
    overflow: hidden;
}

.activity-table th, .activity-table td {
    padding: 10px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

.activity-table th {
    background-color: #f0f8f7;
    font-weight: bold;
    color: #333;
}

.sightseeing-title {
    font-weight: bold;
    margin-top: 20px;
    margin-bottom: 10px;
    color: #004e5a;
}
.itinerary-theme .route-section {
    margin-bottom: 2rem;
}

.itinerary-theme .route-title {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 10px;
    color: #333;
}

.itinerary-theme .tables-row {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}

.itinerary-theme .table-box {
    flex: 1;
    min-width: 200px;
    background: #fff;
    border: 1px solid #ccc;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}

.itinerary-theme table {
    width: 100%;
    border-collapse: collapse;
}

.itinerary-theme th {
    background: #f5f5f5;
    padding: 10px;
    text-align: left;
    border-bottom: 1px solid #ccc;
    font-weight: 600;
}

.itinerary-theme td {
    padding: 10px;
    border-bottom: 1px solid #eee;
}

.activity-table table td{
    padding: 5px;
    font-size: 12px;
}
</style>
<div class="container itinerary-theme">
    <div class="itinerary-header">
        <div class="header-text">
            <h1>Customized Trip Itinerary</h1>
            <p><strong>Start Date:</strong> {{ $itinerary['arrival_date'] }}</p>
            <p><strong>End Date:</strong> {{ $itinerary['departure_date'] }}</p>
            <p><strong>Duration:</strong> {{ $itinerary['travel_duration'] }}</p>
            <p><strong>Meal Plan:</strong> {{ $itinerary['meal_type'] }}</p>
            <p><strong>Travellers:</strong> {{ $itinerary['number_of_travellor'] }} ({{ $itinerary['number_of_adults'] }} Adults, {{ $itinerary['number_of_children'] }} Children)</p>
        </div>
        <div class="header-graphic">
            <img src="https://cdn-icons-png.flaticon.com/512/201/201623.png" alt="Trip Graphic">
        </div>
    </div>

    @foreach($day_itinerary as $day)
        <div class="day-box">
            <div class="day-label">
                <span class="check-icon">✔</span>
                <span class="day-title">Day {{ $day['day'] }} - {{ $day['division'] }}</span>
            </div>

            <div class="info-blocks">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="mb-1"><strong>Hotel:</strong> {{ $day['hotel']['name'] ?? 'N/A' }}</p>
                        <p class="mb-1"><strong>Room Type:</strong> {{ $day['hotel_room']['name'] ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="mb-1"><strong>Meal Plan:</strong> {{ $day['pax_with_adults']['name'] ?? 'N/A' }}</p>
                        <p class="mb-1"><strong>Rooms:</strong> {{ $itinerary['number_of_rooms']?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            @foreach($day['route'] as $route)
                <div class="activity-table">
                    @foreach($day['route'] as $route)
                    <div class="route-section">
                        <div class="route-title"><strong>{{ ucwords($route['name']) }}</strong></div>

                        <div class="tables-row">
                            <!-- Activity Table -->
                            <div class="table-box">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Activity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($route['activitys'] ?? [] as $activity)
                                        <tr>
                                            <td>{{ $activity['name'] }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Sightseeing Table -->
                            <div class="table-box">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Sightseeing</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($route['sightseeings'] ?? [] as $sight)
                                        <tr>
                                            <td>{{ $sight['name'] }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    <div class="tables-row">
                        {{-- Cab Table --}}
                        <div class="table-box">
                            <table>
                                <thead>
                                    <tr>
                                        <th colspan="3">Cab</th>
                                    </tr>
                                    <tr>
                                        <th>Cab Name</th>
                                        <th>Qty</th>
                                        <th>Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($day['cab'] ?? [] as $cab)
                                        <tr>
                                            <td>{{ $cab['name'] }}</td>
                                            <td>{{ $cab['quantity'] }}</td>
                                            <td>{{ $cab['total_price'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Addon Table: CNB + CWM + Extra Mattress --}}
                        <div class="table-box">
                            <table>
                                <thead>
                                    <tr>
                                        <th colspan="3">Addon Items</th>
                                    </tr>
                                    <tr>
                                        <th>Name</th>
                                        <th>Qty</th>
                                        <th>Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- CNB --}}
                                    @foreach($day['cnb'] ?? [] as $addon)
                                        <tr>
                                            <td>{{ $addon['name'] ?? 'CNB' }}</td>
                                            <td>{{ $addon['quantity'] ?? '-' }}</td>
                                            <td>{{ $addon['total_price'] ?? '-' }}</td>
                                        </tr>
                                    @endforeach

                                    {{-- CWM --}}
                                    @foreach($day['cwm'] ?? [] as $addon)
                                        <tr>
                                            <td>{{ $addon['name'] }}</td>
                                            <td>{{ $addon['quantity'] }}</td>
                                            <td>{{ $addon['total_price'] }}</td>
                                        </tr>
                                    @endforeach

                                    {{-- Extra Mattress --}}
                                    @foreach($day['extra_mattress'] ?? [] as $addon)
                                        <tr>
                                            <td>{{ $addon['name'] }}</td>
                                            <td>{{ $addon['quantity'] }}</td>
                                            <td>{{ $addon['total_price'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
         {{-- CAB + Addons Table Row --}}
    
    @endforeach
</div>
@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 1. Track every page click
            document.addEventListener('click', function () {
                 @this.call('incrementClick');
            });

            // 2. Track when user exits the page/tab
            window.addEventListener('beforeunload', function () {
                 @this.call('setExitTime');
            });

           // 1. Start session when visible
            if (document.visibilityState === 'visible') {
                @this.call('startNewClickLog');
            }

            // 2. Handle tab visibility change
            document.addEventListener('visibilitychange', function () {
                if (document.visibilityState === 'visible') {
                    @this.call('startNewClickLog');
                } else {
                    @this.call('closeClickLog');
                }
            });

            // 3. Update exit_time every second only if tab is active
            setInterval(function () {
                if (document.visibilityState === 'visible') {
                    @this.call('updateExitTime');
                }
            }, 1000);

        });
    </script>
@endsection
