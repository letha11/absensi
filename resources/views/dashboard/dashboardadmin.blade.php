@extends('layouts.admin.tabler')
@section('content')
    <div class="page-header d-print-none">
          <div class="container-xl">
            <div class="row g-2 align-items-center">
              <div class="col">
                <!-- Page pre-title -->
                <div class="page-pretitle">Overview</div>
                <h2 class="page-title">Dashboard</h2>
              </div>
            </div>
          </div>
        </div>
        <div class="page-body">
            <div class="container-xl">
                <div class="row row-deck row-cards">
                    <div class="col-md-6 col-xl-3">
                <div class="card card-sm">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <span class="bg-success text-white avatar"><!-- Download SVG icon from http://tabler.io/icons/icon/currency-dollar -->
                          <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-users"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /><path d="M21 21v-2a4 4 0 0 0 -3 -3.85" /></svg>
                        </span>
                      </div>
                      <div class="col">
                        <div class="font-weight-medium">{{ $rekappresensi->jmlhadir ?? 0 }}</div>
                        <div class="text-secondary">Karyawan Hadir Hari Ini</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-xl-3">
                <div class="card card-sm">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <span class="bg-primary text-white avatar"><!-- Download SVG icon from http://tabler.io/icons/icon/currency-dollar -->
                          <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-file-description"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 17h6" /><path d="M9 13h6" /></svg>
                        </span>
                      </div>
                      <div class="col">
                        <div class="font-weight-medium">{{ $rekapizin->jmlizin ?? 0 }}</div>
                        <div class="text-secondary">Karyawan Izin Hari Ini</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-xl-3">
                <div class="card card-sm">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <span class="bg-warning text-white avatar"><!-- Download SVG icon from http://tabler.io/icons/icon/currency-dollar -->
                          <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-first-aid-kit"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 8v-2a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v2" /><path d="M4 8m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" /><path d="M10 14h4" /><path d="M12 12v4" /></svg>
                        </span>
                      </div>
                      <div class="col">
                        <div class="font-weight-medium">{{ $rekapizin->jmlsakit ?? 0 }}</div>
                        <div class="text-secondary">Karyawan Sakit Hari Ini</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
               <div class="col-md-6 col-xl-3">
                <div class="card card-sm">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <span class="bg-danger text-white avatar"><!-- Download SVG icon from http://tabler.io/icons/icon/currency-dollar -->
                          <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-alarm"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 13m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M12 10l0 3l2 0" /><path d="M7 4l-2.75 2" /><path d="M17 4l2.75 2" /></svg>
                        </span>
                      </div>
                      <div class="col">
                        <div class="font-weight-medium">{{ $rekappresensi->jmlterlambat ?? 0 }}</div>
                        <div class="text-secondary">Karyawan Terlambat Hari Ini</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Point Chart Card -->
              @if(Auth::user()->role === \App\Models\User::ROLE_DIREKTUR)
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">Grafik Poin Presensi Karyawan - {{ $namaBulan[$selectedMonth] }} {{ $selectedYear }}</h3>
                  </div>
                  <div class="card-body">
                    <form method="GET" action="{{ route('admin.dashboard') }}" class="mb-3">
                        <div class="row g-2">
                            <div class="col-md-3">
                                <select name="month" class="form-select">
                                    @foreach ($namaBulan as $index => $nama)
                                        @if ($index > 0)
                                        <option value="{{ $index }}" {{ $index == $selectedMonth ? 'selected' : '' }}>{{ $nama }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="year" class="form-select">
                                    @foreach ($availableYears as $year)
                                        <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">Filter</button>
                            </div>
                        </div>
                    </form>
                    <div style="position: relative; height: 400px; width: 100%;">
                        <canvas id="attendancePointsChart"></canvas>
                    </div>
                  </div>
                </div>
              </div>
              <!-- End Point Chart Card -->
              @endif
              
        </div>

        <!-- Leaderboard Row -->
        @if(Auth::user()->role === \App\Models\User::ROLE_DIREKTUR)
        <div class="row row-deck row-cards mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Leaderboard Poin Karyawan ({{ $namaBulan[$selectedMonth] }} {{ $selectedYear }})</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table table-striped">
                            <thead>
                                <tr>
                                    <th>Peringkat</th>
                                    <th>Foto</th>
                                    <th>NIK</th>
                                    <th>Nama Karyawan</th>
                                    <th>Jabatan</th>
                                    <th class="text-center">Total Poin</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($leaderboardData as $index => $data)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            @if(isset($data['foto']) && $data['foto'])
                                                <img src="{{ Storage::url('uploads/karyawan/' . $data['foto']) }}" alt="{{ $data['nama_lengkap'] }}" class="avatar avatar-sm">
                                            @else
                                                <img src="{{ asset('assets/img/sample/avatar/avatar1.jpg') }}" class="avatar" alt="No Foto">
                                            @endif
                                        </td>
                                        <td>{{ $data['nik'] }}</td>
                                        <td>{{ $data['nama_lengkap'] }}</td>
                                        <td>{{ $data['jabatan'] }}</td>
                                        <td class="text-center fw-bold
                                            @if($data['total_points'] > 0) text-success @elseif($data['total_points'] < 0) text-danger @endif">
                                            {{ $data['total_points'] }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada data poin untuk periode ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Leaderboard Row -->
        @endif

    </div>
</div>            
@endsection

@push('scripts')
<!-- Make sure Chart.js is loaded. If your Tabler layout doesn't include it, add it here or in the main layout -->
<!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --> 
<script>
  document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('attendancePointsChart').getContext('2d');
    const attendancePointsChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: {!! json_encode($chartDatasets) !!}.map(dataset => ({
                ...dataset,
                tension: 0.1,
                fill: false
            }))
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: false,
                    min: {{ $yAxisMin }},
                    max: {{ $yAxisMax }},
                    title: {
                        display: true,
                        text: 'Total Poin Harian'
                    },
                    grid: {
                        color: function(context) {
                            if (context.tick.value === 0) {
                                return 'rgba(255, 0, 0, 0.5)'; // Red color for the zero line
                            } else {
                                return 'rgba(0, 0, 0, 0.1)'; // Default grid line color
                            }
                        },
                        lineWidth: function(context) {
                            if (context.tick.value === 0) {
                                return 2; // Thicker line for zero
                            }
                            return 1; // Default line width
                        }
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Tanggal'
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                }
            }
        }
    });

    // Optional: Handle form submission with JS to update chart without full page reload (requires more advanced setup with AJAX)
    // For now, the form submission will cause a full page reload, which is fine.
  });
</script>
@endpush