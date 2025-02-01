<x-filament::button 
    id="btn-template-tkdn-gabungan" 
    size="xs" 
    x-data="{ 
        handleClick() {
            const progressTemplate = `
Progress<br>
-user: <br>
-kontrak pekerjaan: <br>
-progress pekerjaan fisik: <br>
-lengkap: lengkap <br>
-target selesai: <br>
-survey lapangan: <br>
`;
            
            // Use Alpine.js to set the value of the progress field
            document.getElementById('progress').value = progressTemplate;
            
            // Trigger Livewire/Filament to recognize the change
            window.dispatchEvent(new Event('input'));
        }
    }" 
    @click="handleClick()"
>
    Template TKDN Gabungan
</x-filament::button>

<x-filament::button 
    id="btn-template-tkdn-gabungan" 
    size="xs" 
    x-data="{ 
        handleClick() {
            const progressTemplate = `
Progress<br>
- Deadline I: <br>
- Deadline II: <br>
- Deadline III: <br>
`;
            
            // Use Alpine.js to set the value of the progress field
            document.getElementById('progress').value = progressTemplate;
            
            // Trigger Livewire/Filament to recognize the change
            window.dispatchEvent(new Event('input'));
        }
    }" 
    @click="handleClick()"
>
    Template TKDN Barang
</x-filament::button>