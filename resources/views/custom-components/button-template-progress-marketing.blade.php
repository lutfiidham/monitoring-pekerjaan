<x-filament::button 
    id="btn-template-menunggu-permohonan" 
    size="xs" 
    x-data="{ 
        handleClick() {
            const progressTemplate = `
Menunggu Permohonan
`;
            
            // Use Alpine.js to set the value of the progress field
            document.getElementById('progress').value = progressTemplate;
            
            // Trigger Livewire/Filament to recognize the change
            window.dispatchEvent(new Event('input'));
        }
    }" 
    @click="handleClick()"
>
    Menunggu Permohonan
</x-filament::button>

<x-filament::button 
    id="btn-template-pembuatan-penawaran" 
    size="xs" 
    x-data="{ 
        handleClick() {
            const progressTemplate = `
Pembuatan RAB/Penawaran
`;
            
            // Use Alpine.js to set the value of the progress field
            document.getElementById('progress').value = progressTemplate;
            
            // Trigger Livewire/Filament to recognize the change
            window.dispatchEvent(new Event('input'));
        }
    }" 
    @click="handleClick()"
>
    Pembuatan RAB/Penawaran
</x-filament::button>