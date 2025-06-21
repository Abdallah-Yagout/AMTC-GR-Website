<x-form-section submit="updateProfileInformation">
    <x-slot name="title">
        {{ __('Additional Info') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Tell us more about your background and what motivates you to join the tournament.') }}
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6 mt-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-label for="heard_about" value="{{ __('How did you hear about the tournament?') }}"/>
                    <x-select id="heard_about" wire:model="state.heard_about">
                        <option value="">{{ __('Select') }}</option>
                        <option value="social_media">{{ __('Social Media') }}</option>
                        <option value="friends">{{ __('Friends & Family') }}</option>
                        <option value="gaming_cafes">{{ __('Gaming Cafes') }}</option>
                        <option value="websites">{{ __('Websites') }}</option>
                    </x-select>
                </div>

                <div>
                    <x-label for="favorite_games" value="{{ __('What motivates you most to participate?') }}"/>

                    <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-2">
                        @php
                            $games = [
                                'prizes' => __('Prizes'),
                                'love_cars' => __('Love of cars/driving'),
                                'challenge' => __('Challenge & Competition'),
                                'toyota_experience' => __('Toyota GR experience'),
                                'skill_development' => __('Skill development')
                            ];
                        @endphp

                        @foreach ($games as $value => $label)
                            <label class="flex items-center space-x-2">
                                <x-checkbox
                                    value="{{ $value }}"
                                    wire:model="state.motivation"
                                    id="motivation_{{ $value }}"
                                />
                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>

                    <x-input-error for="favorite_games" class="mt-2"/>
                </div>

                <div>
                    <x-label for="preferred_time" value="{{ __('Preferred Time to Participate') }}"/>
                    <x-select id="preferred_time" wire:model="state.preferred_time">
                        <option value="">{{ __('Select') }}</option>
                        <option value="afternoon">{{ __('Afternoon') }}</option>
                        <option value="evening">{{ __('Evening') }}</option>
                        <option value="weekend">{{ __('Weekend') }}</option>
                        <option value="flexible">{{ __('Flexible') }}</option>
                    </x-select>
                </div>

                <div class="col-span-2">
                    <x-label for="suggestions" value="{{ __('Suggestions to Improve') }}"/>
                    <x-textarea id="suggestions" rows="3" class="mt-1 block w-full" wire:model="state.suggestions"/>
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
