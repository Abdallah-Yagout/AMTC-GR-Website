<x-form-section submit="updateProfileInformation">
    <x-slot name="title">
        {{ __('TOYOTA GR Knowledge') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Update your profile information and preferences.') }}
    </x-slot>
    <x-slot name="form">

        <div class="col-span-6 mt-6">
            <div class="space-y-4">
                <!-- Toyota GR Knowledge (as Select or Text Input) -->
                <div>
                    <x-label for="toyota_gr_knowledge" value="{{__('How familiar are you with Toyota GR cars?')}}" />
                    <x-select id="toyota_gr_knowledge" wire:model="state.toyota_gr_knowledge">
                        <option value="">{{ __('Select') }}</option>
                        <option value="expert">{{ __('I know them well and follow their news') }}</option>
                        <option value="knowledgeable">{{ __('I have some knowledge about them') }}</option>
                        <option value="heard">{{ __('I\'ve only heard of them') }}</option>
                        <option value="unknown">{{ __('I don\'t know them at all') }}</option>

                    </x-select>
                    <x-input-error for="toyota_gr_knowledge" class="mt-1" />
                </div>

                <!-- Toyota GR Knowledge Explanation (as Textarea) -->
                <div>
                    <x-label for="favorite_car" value="{{__('What is your favorite car and why?')}}" />
                    <x-textarea id="favorite_car" class="mt-1 block w-full" wire:model="state.favorite_car" rows="4" />
                    <x-input-error for="favorite_car" class="mt-1" />
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
