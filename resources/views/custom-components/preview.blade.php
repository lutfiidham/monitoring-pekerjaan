@if (isset($error))
    <div class="file-preview text-red-500">
        <h3>Error: {{ $filename }}</h3>
        <p>{{ $error }}</p>
    </div>
@elseif ($type === 'image')
    <div class="file-preview">
        <h3>{{ $filename }}</h3>
        <img src="{{ $src }}" alt="{{ $filename }}" class="max-w-full h-auto">
    </div>
@elseif ($type === 'pdf')
    <div class="file-preview">
        <h3>{{ $filename }}</h3>
        <iframe src="{{ $src }}" width="100%" height="500px"></iframe>
    </div>
@else
    <div class="file-preview">
        <h3>Cannot preview: {{ $filename }}</h3>
        <p>This file type does not support preview.</p>
    </div>
@endif