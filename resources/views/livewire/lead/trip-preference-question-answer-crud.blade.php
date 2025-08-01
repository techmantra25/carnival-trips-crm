<div class="mb-4">
    <style>
        .add-new-question-btn{
            background-color: #364372;
            color: #ffffff;
        }
        .change-positions-btn{
            background-color: #c7376b;
            color: #ffffff;
        }
        .header-btn-div{
            margin-bottom: -15px;
        }
        .item-rounded{
            padding: 3px 10px;
            border-radius: 5px;
        }
    </style>
    @if (session()->has('success'))
        <div class="mb-4 text-green-700 bg-green-100 p-2 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="space-y-8">
        @if(count($existingQuestion)>0)
            <div class="flex justify-end items-center mt-4 header-btn-div">
                <a href="javascript:void(0)" wire:click="ChangePositions">
                    <span class="badge gap-2 change-positions-btn">
                        <i class="fa-solid fa-plus text-lg text-white text-sm"></i>
                        Change Positions
                    </span>
                </a>
            </div>
        @endif
        @foreach($questions as $qIndex => $question)
            <div class="sortable-list border-2 border-dashed border-gray-400 p-4 bg-white dark:bg-gray-800 shadow-md rounded-lg">
                <table class="table w-full border border-primary/20">
                    <thead class="bg-primary/20">
                        <tr>
                            <th colspan="2" class="text-center uppercase py-2 text-primary font-semibold">
                                Step {{ $question['step'] ?? $qIndex + 1 }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-gray-200">
                            <td class="w-1/4 font-medium p-2 text-[15px]">Question</td>
                            <td class="p-2">
                                 <input type="text"
                                    class="form-control form-control-sm w-full text-[15px]"
                                    wire:model.lazy="questions.{{ $qIndex }}.title"
                                    wire:keyup="QuestionUpdate({{ $qIndex }})"
                                    placeholder="Enter your question here" />
                            </td>
                        </tr>
                        <tr class="border-b border-gray-200">
                            <td class="font-medium p-2 text-[15px]">Options</td>
                            <td class="p-2 space-y-2">
                                @foreach($question['options'] as $oIndex => $option)
                                    <div class="flex gap-2 items-center">
                                       <input type="text"
                                        class="form-control form-control-sm w-full text-[15px]"
                                        wire:model.lazy="questions.{{ $qIndex }}.options.{{ $oIndex }}.option_text"
                                        wire:keyup="OptionUpdate({{ $qIndex }}, {{ $oIndex }})"
                                        placeholder="Option {{ $oIndex + 1 }}" />
                                        <button type="button"
                                            wire:click="removeOption({{ $qIndex }}, {{ $oIndex }})"
                                            class="text-red-600 hover:text-red-800 text-sm px-2">
                                            ✕
                                        </button>
                                    </div>
                                @endforeach

                                <div class="flex justify-end items-center mt-4">
                                    <a href="javascript:void(0)" wire:click="addOption({{ $qIndex }})" title="Add new option">
                                        <span class="badge gap-2 status-active">
                                            <i class="fa-solid fa-plus text-lg text-black text-sm"></i>
                                            Add New Option
                                        </span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="flex justify-end mt-2">
                    <a href="javascript:void(0)" wire:click="removeQuestion({{ $qIndex }})" title="Click to delete question">
                        <span class="badge gap-2 status-high-intend">
                            <i class="fa-solid fa-trash text-sm"></i>
                            Delete Question
                        </span>
                    </a>
                </div>
            </div>
        @endforeach

        <div class="flex justify-end items-center mt-4">
            <a href="javascript:void(0)" wire:click="addQuestion" title="Click to add new question">
                <span class="badge gap-2 add-new-question-btn">
                    <i class="fa-solid fa-plus text-lg text-white text-sm"></i>
                    Add New Question
                </span>
            </a>
        </div>
    </div>

    <div class="hs-overlay {{$active_modal_change_potisions==0?"hidden":""}} fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="hs-overlay-open:mt-7 ti-modal-box mt-0 ease-out lg:!max-w-4xl lg:w-full m-3 lg:!mx-auto modal_lg_sm_width bg-white rounded-lg">
            <div class="ti-modal-content p-6 sm:p-10">
                <!-- Modal Header -->
                <div class="ti-modal-header flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">Reorder Questions</h2>
                    <a href="{{route('admin.master.trip_preference_form')}}"
                        class="text-gray-400 hover:text-gray-600 focus:outline-none badge gap-2 bg-danger/10 text-danger">
                        <i class="fa-solid fa-xmark text-lg text-dark"></i>
                    </a>
                </div>

                <!-- Modal Body -->
                <div class="ti-modal-body text-start mt-2">
                    <div wire:ignore>
                        <div class="space-y-2 rounded-md border-2 border-dashed p-4 border-slate-400 bg-slate-50 transition-all duration-300 ease-in-out"
                            x-data
                            x-init="Sortable.create($el, {
                                animation: 200,
                                handle: '.handle',
                                ghostClass: 'bg-yellow-100',
                                dragClass: 'opacity-80',
                                onEnd: (evt) => {
                                    const orderedIds = Array.from($el.children).map(el => el.dataset.id);
                                    @this.call('updateQuestionPosition', orderedIds);
                                }
                            })">
                            @forelse ($existingQuestion as $question)
                                <div class="flex items-center justify-between bg-white p-3 item-rounded shadow-sm border group transition hover:shadow-md cursor-move"
                                    data-id="{{ $question->id }}">
                                    <div class="flex-1 text-gray-800 text-sm">{{ $question->title }}</div>
                                    <div class="handle text-lg text-gray-400 group-hover:text-gray-700 px-3 cursor-grab active:cursor-grabbing">
                                        <i class="ri-drag-move-2-fill"></i>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-600">No question available.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
     <div wire:loading class="loader" wire:target="removeOption,addOption,removeQuestion,addQuestion,ChangePositions,ClosePotisionModal">
        <div class="spinner">
            <img src="{{asset('build/assets/images/media/loader.svg')}}" alt="">
        </div>
    </div>
</div>

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
     window.addEventListener('showConfirm', function (event) {
        let itemId = event.detail[0].id;
        let index = event.detail[0].index;
        Swal.fire({
            title: 'Are you sure?',
            text: "This question and its options will be deleted!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                @this.call('deleteConfirmed', itemId, index);
            }
        });
    });
</script>
@endsection
