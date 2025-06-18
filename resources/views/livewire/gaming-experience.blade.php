<x-form-section submit="updateProfileInformation">
    <x-slot name="title">
        {{ __('Gaming Experience') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Update your gaming skills and hardware preferences.') }}
    </x-slot>
    <x-slot name="form">

        <div class="col-span-6 mt-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-label for="skill_level" class="mb-2" value="{{__('Skill Level in Gran Turismo 7')}}" />
                    <x-select id="skill_level" wire:model="state.skill_level">
                        <option value="">{{__('Select')}}</option>
                        <option value="beginner">{{__('Beginner')}}</option>
                        <option value="intermediate">{{__('Intermediate')}}</option>
                        <option value="expert">{{__('Expert')}}</option>
                    </x-select>
                    <x-input-error for="skill_level" />
                </div>

                <div>
                    <x-label for="has_ps5" value="{{__('Do you own a PS5?')}}" />
                    <x-checkbox id="has_ps5" wire:model="state.has_ps5" />
                    <x-input-error for="has_ps5"/>
                </div>

                <div>
                    <x-label for="primary_platform" class="mb-2" value="{{__('Primary Gaming Platform')}}" />
                    <x-select id="primary_platform" wire:model="state.primary_platform">
                        <option value="">{{ __('Select') }}</option>
                        <option value="ps5">{{ __('PlayStation 5') }}</option>
                        <option value="ps4">{{ __('PlayStation 4') }}</option>
                        <option value="pc">{{ __('PC') }}</option>
                        <option value="other">{{ __('Other Platform') }}</option>
                    </x-select>
                    <x-input-error for="primary_platform" />
                </div>

                <div>
                    <x-label for="weekly_hours" class="mb-2" value="{{__('Weekly Play Hours')}}" />
                    <x-select id="weekly_hours" wire:model="state.weekly_hours">
                        <option value="">{{ __('Select') }}</option>
                        <option value="less_than_5">{{ __('Less than 5 hours') }}</option>
                        <option value="5_to_10">{{ __('5 to 10 hours') }}</option>
                        <option value="more_than_10">{{ __('More than 10 hours') }}</option>

                    </x-select>
                    <x-input-error for="weekly_hours" />
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
