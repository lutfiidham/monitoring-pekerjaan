import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
        
        './resources/**/*.blade.php',
        './app/Filament/**/*.php', // Tambahkan path ini
        './app/Http/**/*.php',    // Jika ada logika custom di controller

        './vendor/cmsmaxinc/filament-error-pages/resources/**/*.blade.php',
    ],
}
