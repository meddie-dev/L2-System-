<h1>Python Test Results</h1>
<ul>
    <li>Python found at: <pre>{{ $python_path ?? 'Not found' }}</pre></li>
    <li>Simple command: <pre>{{ $simple_command ?? 'No output' }}</pre></li>
    <li>File test: {{ $file_test }}</li>
    <li>File exists: {{ $file_exists }}</li>
</ul>