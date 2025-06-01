<x-form-section submit="updateProfileInformation">
    <x-slot name="title">
        {{ __('Tournament Experience') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Update your tournament participation preferences.') }}
    </x-slot>
    <x-slot name="form">

        <div class="col-span-6 mt-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-label for="participated_before" value="{{ __('Did you participate in AMTC 2024?') }}" />
                    <x-checkbox id="participated_before" wire:model="state.participated_before" />
                    <x-input-error for="participated_before" class="mt-1" />
                </div>

                <div>
                    <x-label for="wants_training" value="{{ __('Interested in Training Before Qualifiers?') }}" />
                    <x-checkbox id="wants_training" wire:model="state.wants_training" />
                    <x-input-error for="wants_training" class="mt-1" />
                </div>

                <div>
                    <x-label for="join_whatsapp" value="{{ __('Join WhatsApp Group?') }}" />
                    <x-checkbox id="join_whatsapp" wire:model="state.join_whatsapp" />
                    <x-input-error for="join_whatsapp" class="mt-1" />
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
