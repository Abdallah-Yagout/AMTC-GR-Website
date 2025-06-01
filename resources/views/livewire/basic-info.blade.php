<x-form-section submit="updateProfileInformation">
    <x-slot name="title">
        {{ __('Profile Information') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Update your personal details including your name, email, and date of birth.') }}
    </x-slot>

    <x-slot name="form">

        {{-- 🔸 Section: Basic Information --}}
        <div class="col-span-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <x-label for="name" value="{{__('Full Name')}}" />
                    <x-input id="name" type="text" class="mt-1 block w-full" wire:model="state.name" />
                    <x-input-error for="name" />
                </div>

                <div>
                    <x-label for="email" value="{{__('Email')}}" />
                    <x-input id="email" type="email" class="mt-1 block w-full" wire:model="state.email" />
                    <x-input-error for="email" />
                </div>

                <div>
                    <x-label for="birthdate" value="{{__('Birth Date')}}" />
                    <x-input id="birthdate" type="date" class="mt-1 block w-full" wire:model="state.birthdate" />
                    <x-input-error for="birthdate" />
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
