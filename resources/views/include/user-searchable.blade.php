@if(env('AMS_BOOTSTRAP') == 'true')
    <div>
        <div class="modal fade" id="SelectUser" wire:ignore.self="" tabindex="-1" role="dialog"
             aria-labelledby="userModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body p-0">
                        <div class="form-group px-2 pt-2 mb-1">
                            <label for="user_query" id="userModalLabel" class="mb-1">Search {{ $user_label }}</label>
                            <input type="text"
                                   wire:model.debounce.500ms="user_query"
                                   wire:keydown.arrow-up="userDecrementHighlight"
                                   wire:keydown.arrow-down="userIncrementHighlight"
                                   wire:keydown.enter="userSelection"
                                   wire:keydown.escape="userReset"
                                   wire:keydown.tab="userReset"
                                   id="user_query"
                                   class="rounded user_query" style="width: 480px"
                                   autocomplete="off">
                        </div>

                        <div wire:loading.block wire:target="user_query" class="px-2 pb-2">
                            <p class="text-muted mb-0">Loading...</p>
                        </div>

                        <div wire:loading.remove wire:target="user_query">
                            @if(!empty($user_data))
                                <table class="table border-0 table-hover">
                                    <thead>
                                    <tr>
                                        @foreach($user_column as $c)
                                            <th scope="col" class="px-2 py-2 text-left">
                                                {{ ucwords(str_replace('_', ' ', $c)) }}
                                            </th>
                                        @endforeach
                                    </tr>
                                    </thead>
                                    <tbody class="bg-white">
                                    @foreach($user_data as $key => $u)
                                        <tr style="cursor: pointer; {{ $user_highlight_index === $key ? 'background-color: #3d40e0; color: white;' : '' }}"
                                            onmouseover="this.style.backgroundColor='#3d40e0';this.style.color='#ffffff';"
                                            onmouseout="this.style.backgroundColor='#ffffff'; this.style.color='#000000';"
                                            wire:click="userSelection('{{ $key }}')">
                                            @foreach($user_column as $c)
                                                <td style="padding: 7px;border-top: none;">{{ $u[$c] }}</td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @else
                                @if(strlen($user_query) < 2)
                                    <p class="pt-0 px-2 text-muted">Please
                                        enter {{ 2 - strlen($user_query) }} or more
                                        {{ (2 - strlen($user_query)) > 1 ? 'characters' : 'character' }}</p>
                                @else
                                    <p class="pt-0 px-2 text-muted">No Record Found</p>
                                @endif
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('js')
        <script>
            window.addEventListener('open-user-modal', function () {
                $("#user_query").blur();
                $('#SelectUser').modal('show');
                setTimeout(function () {
                    $("#user_query").focus();
                }, 500);
            })
            window.addEventListener('close-user-modal', function () {
                $('#SelectUser').modal('hide');
            })
        </script>
    @endpush
@else
    <div x-data="{ open: false }" x-cloak x-show="open"
         @open-user-modal.window="open = true"
         @close-user-modal.window="open = false"
         class="fixed z-40 inset-0 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <!-- This element is to trick the browser into centering the modal contents. -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true"></span>
            <div @click.away="open = false" x-show="open"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                 role="dialog" aria-modal="true" aria-labelledby="user-modal-headline">

                {{--                Loader--}}
                <div wire:loading wire:target="userOpenModal" class="w-full pt-5 pb-5">
                    <div class="flex justify-center items-center w-full">
                        {{-- border-top-color inline: this package ships tailwind v2, which has no border-t-{color} --}}
                        <div style="border-top-color: #2563eb;"
                             class="h-12 w-12 animate-spin rounded-full border-4 border-gray-300"></div>
                    </div>
                </div>

                <div wire:loading.remove wire:target="userOpenModal">
                    <div class="px-2 pt-2 pb-2">
                        <label for="user_query" id="user-modal-headline"
                               class="block text-sm font-medium text-gray-600">Search {{ $user_label }}</label>
                        <input type="text"
                               wire:model.debounce.500ms="user_query"
                               wire:keydown.arrow-up="userDecrementHighlight"
                               wire:keydown.arrow-down="userIncrementHighlight"
                               wire:keydown.enter="userSelection"
                               wire:keydown.escape="userReset"
                               wire:keydown.tab="userReset"
                               id="user_query"
                               class="shadow-sm mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                               autocomplete="off">
                    </div>

                    <div wire:loading.block wire:target="user_query">
                        <p class="text-sm opacity-25 pt-0 p-3">Loading...</p>
                    </div>

                    <div wire:loading.remove wire:target="user_query" class="max-h-96 overflow-y-auto">
                        @if(!empty($user_data))
                            <table class="mt-3 min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                <tr>
                                    @foreach($user_column as $c)
                                        <th scope="col"
                                            class="px-2 py-2 text-left text-xs font-medium text-gray-500 tracking-wider">
                                            {{ ucwords(str_replace('_', ' ', $c)) }}
                                        </th>
                                    @endforeach
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($user_data as $key => $u)
                                    <tr class="hover:bg-indigo-600 hover:text-white cursor-pointer {{ $user_highlight_index === $key ? 'bg-indigo-600 text-white' : 'text-gray-500' }}"
                                        wire:click="userSelection('{{ $key }}')">
                                        @foreach($user_column as $c)
                                            <td class="px-2 py-2 whitespace-nowrap text-sm">{{ $u[$c] }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @else
                            @if(strlen($user_query) < 2)
                                <p class="text-sm opacity-25 pt-0 p-3">Please enter {{ 2 - strlen($user_query) }}
                                    or more
                                    {{ (2 - strlen($user_query)) > 1 ? 'characters' : 'character' }}</p>
                            @else
                                <p class="text-sm opacity-25 pt-0 p-3">No Record Found</p>
                            @endif
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script>
        // userOpenModal() emits this once the list has loaded and the input is on screen
        document.addEventListener('livewire:load', () => {
            Livewire.on('focusUserInput', () => {
                setTimeout(() => {
                    let input = document.getElementById('user_query');
                    if (input) {
                        input.focus();
                    }
                }, 100);
            });
        });
    </script>
@endif
