  <x-layout.mainTemplate>
    <nav class="tw-flex tw-justify-between | max-md:tw-hidden" aria-label="Breadcrumb">
      <ol class="tw-inline-flex tw-items-center tw-space-x-1 | md:tw-space-x-2 rtl:tw-space-x-reverse max-sm:tw-text-sm">
        <x-partials.breadcrumb class="tw-bg-white" href="{{ route(Auth::user()->hasRole('Super Admin') ? 'superadmin.dashboard' : (Auth::user()->hasRole('Admin') ? 'admin.dashboard' : 'staff.dashboard')) }}" :active="false" :isLast="false">
          <div class="sb-nav-link-icon"><i class="fa-solid fa-table-columns"></i></div>
          Dashboard
        </x-partials.breadcrumb>

        <x-partials.breadcrumb :active="true" :isLast="false">
          Fraud Detection
        </x-partials.breadcrumb>

        <x-partials.breadcrumb :active="true" :isLast="true">
          Fraudulent Activity
        </x-partials.breadcrumb>
      </ol>
    </nav>

    <div class="card-body tw-px-4">
      {{-- Display message if available --}}
      @if(session('message'))
      <div class="alert alert-success">{{ session('message') }}</div>
      @endif

      {{-- Check if we have prediction results --}}
      @if(!empty($predictions))
      <table class="datatable table-striped">
        <thead>
          <tr>
            <th>ID</th>
            <th>User ID</th>
            <th>Event</th>
            <th>IP Address</th>
            <th>Behavior</th>
            <th>Rule-Based</th>
            <th>Machine Learning</th>
            <th>Final Decision</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach($predictions as $prediction)
          <tr>
            <td>{{ $prediction['id'] ?? 'N/A' }}</td>
            <td>{{ $prediction['user_id'] ?? 'N/A' }}</td>
            <td>{{ $prediction['event'] ?? 'N/A' }}</td>
            <td>{{ $prediction['ip_address'] ?? 'N/A' }}</td>
            <td><span class="tw-text-{{ $prediction['behavior'] == 'Normal' ? 'green-500' : 'red-500' }}">{{ ucfirst(strtolower($prediction['behavior'])) }}</span></td>
            <td><span class="tw-text-{{ $prediction['rule_based'] == 'Normal' ? 'green-500' : 'red-500' }}">{{ $prediction['rule_based'] }}</span></td>
            <td><span class="tw-text-{{ $prediction['machine_learning'] == '0%' ? 'green-500' : 'red-500' }}">{{ $prediction['machine_learning'] }}</span></td>
            <td><strong>{{ $prediction['final_decision'] }}</strong></td>
            <td>
              @if($prediction['action'] == 'Block user')
              <span class="badge bg-danger">{{ $prediction['action'] }}</span>
              @else
              <span class="badge bg-success">{{ $prediction['action'] }}</span>
              @endif
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
      @else
      <p>No fraud detection results available.</p>
      @endif
    </div>
    <div>
      <h3 class="tw-text-sm tw-font-semibold tw-text-gray-700 tw-mt-2">Fraud Detection Instructions</h3>
      <div class="tw-text-xs tw-text-gray-600 tw-p-3">
        <ul class="tw-list-disc tw-pl-5 tw-space-y-1">
          <li>CONFIRMED FRAUD: Immediate action required. Block the user immediately.</li>
          <li>SUSPICIOUS: Manual review needed. Inspect user activity details.</li>
          <li>LOW RISK: Monitor the user activity, but no immediate action is required.</li>
          <li>CLEAN: Normal activity detected. No action needed.</li>
        </ul>
        <p class="tw-mt-3">The system performs analysis of activities every hour. Click "Review" to explore suspicious cases further.</p>
      </div>
    </div>

    @push('scripts')
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        let table = $('.datatable').DataTable({
          order: [
            [0, 'desc']
          ],
          responsive: true,
          columnDefs: [{
              targets: [4, 5, 6],
              orderable: false
            },
            {
              targets: 2,
              width: '25%'
            }
          ],
          language: {
            search: "_INPUT_",
            searchPlaceholder: "Search activities..."
          },
          rowCallback: function(row, data, index) {
            // Assuming "Final Decision" is the 8th column (index 7 in zero-based index)
            if (data[7].trim() === 'CONFIRMED FRAUD') {
              $(row).css({
                'background-color': '#dc3545', // Bootstrap red
                'color': '#ffffff' // White text for contrast
              });
            }
          }
        });
      });
    </script>
    @endpush

  </x-layout.mainTemplate>