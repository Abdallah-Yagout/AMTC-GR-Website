<x-form-section submit="updateProfileInformation">
    <x-slot name="title">
        {{ __('Contact Information') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Please provide your phone and WhatsApp number so we can reach you if needed.') }}
    </x-slot>

    <x-slot name="form">

        <div class="col-span-6 mt-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-label for="phone" value="{{__('Phone Number')}}" />
                    <x-input id="phone" type="number" class="mt-1 block w-full" wire:model="state.phone" />
                    <x-input-error for="phone"/>
                </div>

                <div>
                    <x-label for="whatsapp" value="{{__('WhatsApp Number')}}" />
                    <x-input id="whatsapp" type="number" class="mt-1 block w-full" wire:model="state.whatsapp" />
                    <x-input-error for="whatsapp"/>
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
