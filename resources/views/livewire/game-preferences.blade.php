<x-form-section submit="updateProfileInformation">
    <x-slot name="title">
        {{ __('Game Preferences') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Select your favorite games and rank Gran Turismo 7 among them.') }}
    </x-slot>
    <x-slot name="form">

        <div class="col-span-6 mt-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-label for="favorite_games" value="{{__('Favorite Games')}}" />

                    <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-2">
                        @php
                            $games = [
                                'fifa' => 'FIFA (FC25)',
                                'pes' => 'PES',
                                'gt7' => 'Gran Turismo 7',
                                'cod' => 'Call of Duty',
                                'fortnite' => 'Fortnite',
                                'apex' => 'Apex Legends',
                                'minecraft' => 'Minecraft',
                                'gta' => 'GTA V',
                            ];
                        @endphp

                        @foreach ($games as $value => $label)
                            <label class="flex items-center space-x-2">
                                <x-checkbox
                                    value="{{ __($value) }}"
                                    wire:model="state.favorite_games"
                                    id="favorite_games_{{ $value }}"
                                />
                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ __($label) }}</span>
                            </label>
                        @endforeach
                    </div>

                    <x-input-error for="favorite_games" class="mt-2" />
                </div>


                <div>
                    <x-label for="gt7_ranking" value="{{__('GT7 Ranking Among Favorites')}}" />
                    <x-select id="gt7_ranking" wire:model="state.gt7_ranking">
                        <option value="top1">{{ __('My favorite game') }}</option>
                        <option value="top3">{{ __('Top 3') }}</option>
                        <option value="top5">{{ __('Top 5') }}</option>
                        <option value="lower">{{ __('Lower than that') }}</option>
                    </x-select>
                    <x-input-error for="gt7_ranking" />
                </div>

            </div>
        </div>

    </x-slot>

    <x-slot name="actions">
        <x-action-message class="me-3" on="saved">
            {{ __('Saved.') }}
        </x-action-message>

        <x-button wire:loading.attr="disabled">
            {{ __('Save') }}
        </x-button>
    </x-slot>
</x-form-section>
