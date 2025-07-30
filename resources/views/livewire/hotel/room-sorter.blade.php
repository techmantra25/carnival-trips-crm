<div wire:ignore>
    <div class="space-y-1 rounded-md border-2 border-dotted p-2 border-slate-500 transition-all duration-300 ease-in-out"
        x-data
        x-init="Sortable.create($el, {
            animation: 150,
            handle: '.handle',
            ghostClass: 'bg-yellow-100',
            dragClass: 'opacity-1000',
            onEnd: (evt) => {
                const orderedIds = Array.from($el.children).map(el => el.dataset.id);
                @this.call('updateRoomOrder', orderedIds);
            }
        })"
    >
    @forelse ($rooms as $room)
        <div class="grid grid-cols-7 items-center gap-2 bg-white p-2 border rounded relative pr-10" data-id="{{ $room->id }}">
            <div class="hotel_room_details grid grid-cols-7 gap-2 text-sm">
                <div class="room_item_details">{{ $room->room_name }}</div>
                <div class="room_item_details">{{ $room->no_of_rooms }}</div>
                <div class="room_item_details">{{ $room->no_of_beds }}</div>
                <div class="room_item_details">{{ $room->extra_bed }}</div>
                <div class="room_item_details">{{ $room->mattress }}</div>
                <div class="room_item_details">{{ $room->capacity }}</div>
                <div class="room_item_details">
                    <p class="badge rounded-full bg-light text-dark mb-1 ammenity_icon cursor-pointer"
                        data-title="{{ $room->ammenities ?: 'No Data Found' }}"
                        id="amenity-{{ $room->id }}"
                        onclick="showAmenities(this)">
                        <i class="ri-eye-line"></i>
                    </p>
                </div>
                <div class="handle cursor-move text-xl px-2 text-gray-500 hover:text-gray-800">
                    <i class="ri-drag-move-2-fill"></i>
                </div>
            </div>
           
        </div>
    @empty
        <p class="text-sm text-gray-600">No rooms available.</p>
    @endforelse
</div>

</div>
