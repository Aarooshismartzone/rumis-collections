@extends('backend.layouts.master')

@section('title', 'Dashboard')

@section('content')

    <style>
        /* small custom polish */
        .stat-card .fa-2x {
            opacity: .95;
        }

        .card-header.fw-bold {
            font-weight: 700;
        }

        .table thead th {
            vertical-align: middle;
        }

        .small-muted {
            font-size: .85rem;
            color: #6c757d;
        }

        .growth-badge {
            font-weight: 600;
        }

        /* responsive small tweak */
        @media (max-width: 575px) {
            .card .me-3 {
                margin-right: .5rem !important;
            }
        }
    </style>

    <div class="container-fluid py-3">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h3 class="mb-0">Dashboard</h3>
            <div class="small-muted">Welcome, {{ $user->name ?? ($user->email ?? 'Admin') }}</div>
        </div>

        <!-- Filter Row -->
        <div class="row g-2 mb-3 align-items-center">
            <div class="col-md-4 col-sm-6">
                <select id="presetRange" class="form-select">
                    <option value="today">Today</option>
                    <option value="last7days" selected>Last 7 Days</option>
                    <option value="thismonth">This Month</option>
                    <option value="custom">Custom Range</option>
                </select>
            </div>

            <div class="col-md-6 col-sm-12 d-none" id="customDateInputs">
                <div class="input-group">
                    <input type="date" id="startDate" class="form-control" />
                    <input type="date" id="endDate" class="form-control" />
                </div>
            </div>

            <div class="col-md-2 col-sm-12">
                <button id="applyFilter" class="btn btn-primary w-100">
                    <i class="fas fa-filter me-1"></i> Apply
                </button>
            </div>
        </div>

        <!-- Stat Cards -->
        <div class="row mb-4">
            @php
                $cards = [
                    [
                        'title' => 'Sales Today',
                        'value' => $salesToday,
                        'growth' => $growthToday,
                        'color' => 'primary',
                        'icon' => 'fa-calendar-day',
                    ],
                    [
                        'title' => 'Last 7 Days',
                        'value' => $salesLast7Days,
                        'growth' => $growthLast7Days,
                        'color' => 'success',
                        'icon' => 'fa-calendar-week',
                    ],
                    [
                        'title' => 'This Month',
                        'value' => $salesThisMonth,
                        'growth' => $growthMonth,
                        'color' => 'warning',
                        'icon' => 'fa-calendar-alt',
                    ],
                ];
            @endphp

            @foreach ($cards as $c)
                <div class="col-lg-4 col-md-6 mb-3">
                    <div class="card stat-card shadow-sm h-100 border-0">
                        <div class="card-body d-flex align-items-center">
                            <div class="me-3">
                                <span class="rounded-circle bg-{{ $c['color'] }} text-white p-3">
                                    <i class="fas {{ $c['icon'] }} fa-2x"></i>
                                </span>
                            </div>

                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="small-muted">{{ $c['title'] }}</div>
                                        <h4 class="mb-0">₹{{ number_format($c['value'], 2) }}</h4>
                                    </div>

                                    <div class="text-end">
                                        <div class="growth-badge {{ $c['growth'] >= 0 ? 'text-success' : 'text-danger' }}">
                                            <i class="fas {{ $c['growth'] >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                                            {{ $c['growth'] }}%
                                        </div>
                                        <div class="small-muted">vs previous</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Chart -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header fw-bold d-flex justify-content-between align-items-center">
                        <div>Sales Trend</div>
                        <div class="small-muted">Showing selected date range</div>
                    </div>
                    <div class="card-body">
                        <canvas id="salesChart" height="90"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Products & Top Buyers -->
        <div class="row g-3">
            <div class="col-lg-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header fw-bold">Top 5 Products</div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" id="topProductsTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Product</th>
                                        <th class="text-center">Qty Sold</th>
                                        <th class="text-end">Total Sales</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($topProducts as $p)
                                        <tr>
                                            <td>{{ $p->product_name ?? ($p->product->product_name ?? 'N/A') }}</td>
                                            <td class="text-center">{{ $p->total_qty }}</td>
                                            <td class="text-end">₹{{ number_format($p->total_sales, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-3">No sales data</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header fw-bold">Top Buyers (Last 30 Days)</div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" id="topBuyersTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Buyer</th>
                                        <th class="text-center">Orders</th>
                                        <th class="text-end">Total Spent</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($topBuyers as $b)
                                        <tr>
                                            <td>{{ $b->name }}</td>
                                            <td class="text-center">{{ $b->orders_count }}</td>
                                            <td class="text-end">₹{{ number_format($b->total_spent, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-3">No buyer data</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loading overlay -->
        <div id="loadingOverlay"
            style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;
         background:rgba(255,255,255,0.8);z-index:9999;align-items:center;justify-content:center;">
            <div class="text-center">
                <div class="spinner-border text-primary" role="status" style="width:3rem;height:3rem"></div>
                <div class="mt-2 small-muted">Loading data…</div>
            </div>
        </div>
    </div>

    <!-- Scripts (Chart.js + Bootstrap JS + helper JS). Remove if present in master. -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Helper: format currency
            function money(n) {
                return '₹' + parseFloat(n || 0).toLocaleString(undefined, {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }

            // Initialize Chart
            const ctx = document.getElementById('salesChart').getContext('2d');
            const salesChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Sales',
                        data: [],
                        fill: true,
                        backgroundColor: 'rgba(54,162,235,0.12)',
                        borderColor: '#36A2EB',
                        tension: 0.25,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '₹' + value;
                                }
                            }
                        }
                    }
                }
            });

            // Restore preset from localStorage if present
            const saved = JSON.parse(localStorage.getItem('dashboard_filter') || 'null');
            if (saved) {
                document.getElementById('presetRange').value = saved.preset || 'last7days';
                if (saved.preset === 'custom') {
                    document.getElementById('customDateInputs').classList.remove('d-none');
                    document.getElementById('startDate').value = saved.start || '';
                    document.getElementById('endDate').value = saved.end || '';
                } else {
                    document.getElementById('customDateInputs').classList.add('d-none');
                }
            }

            document.getElementById('presetRange').addEventListener('change', function() {
                document.getElementById('customDateInputs').classList.toggle('d-none', this.value !==
                    'custom');
            });

            document.getElementById('applyFilter').addEventListener('click', function() {
                fetchData();
            });

            // compute date strings for presets
            function getPresetRange(preset) {
                const today = new Date();
                let s = '',
                    e = '';
                if (preset === 'today') {
                    s = e = today.toISOString().split('T')[0];
                } else if (preset === 'last7days') {
                    const d = new Date();
                    d.setDate(today.getDate() - 6);
                    s = d.toISOString().split('T')[0];
                    e = today.toISOString().split('T')[0];
                } else if (preset === 'thismonth') {
                    const first = new Date(today.getFullYear(), today.getMonth(), 1);
                    s = first.toISOString().split('T')[0];
                    e = today.toISOString().split('T')[0];
                }
                return {
                    start: s,
                    end: e
                };
            }

            function showLoading(show = true) {
                document.getElementById('loadingOverlay').style.display = show ? 'flex' : 'none';
            }

            async function fetchData() {
                const preset = document.getElementById('presetRange').value;
                let startDate = '',
                    endDate = '';

                if (preset === 'custom') {
                    startDate = document.getElementById('startDate').value;
                    endDate = document.getElementById('endDate').value;
                    if (!startDate || !endDate) {
                        alert('Please select both start and end dates for custom range.');
                        return;
                    }
                    if (startDate > endDate) {
                        alert('Start date cannot be after end date.');
                        return;
                    }
                } else {
                    const r = getPresetRange(preset);
                    startDate = r.start;
                    endDate = r.end;
                }

                // save preference
                localStorage.setItem('dashboard_filter', JSON.stringify({
                    preset,
                    start: startDate,
                    end: endDate
                }));

                showLoading(true);

                try {
                    const res = await fetch("{{ route('dashboard.data') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            start_date: startDate,
                            end_date: endDate
                        })
                    });
                    if (!res.ok) throw new Error('Network response was not ok');

                    const data = await res.json();
                    showLoading(false);

                    // Update Chart
                    const labels = data.salesTrend.map(s => s.date);
                    const totals = data.salesTrend.map(s => parseFloat(s.total));
                    salesChart.data.labels = labels;
                    salesChart.data.datasets[0].data = totals;
                    salesChart.update();

                    // Update stat cards (we only update the central aggregate card values if desired)
                    // For simplicity, update the first stat card to show current total in range.
                    // (You can expand this to update multiple cards.)
                    // Replace the first card's number and growth:
                    const firstCardValue = document.querySelectorAll('.stat-card h4')[0];
                    if (firstCardValue) firstCardValue.textContent = money(data.salesTotal);

                    const firstCardGrowth = document.querySelectorAll('.growth-badge')[0];
                    if (firstCardGrowth) {
                        firstCardGrowth.classList.remove('text-success', 'text-danger');
                        firstCardGrowth.classList.add(data.growth >= 0 ? 'text-success' : 'text-danger');
                        firstCardGrowth.innerHTML =
                            `<i class="fas ${data.growth >= 0 ? 'fa-arrow-up' : 'fa-arrow-down'}"></i> ${parseFloat(data.growth).toFixed(2)}%`;
                    }

                    // Top Products table
                    let productsHTML = '';
                    if (data.topProducts && data.topProducts.length) {
                        data.topProducts.forEach(p => {
                            const name = (p.product_name ?? (p.product && p.product.product_name) ??
                                'N/A');
                            productsHTML += `<tr>
                        <td>${name}</td>
                        <td class="text-center">${p.total_qty}</td>
                        <td class="text-end">${money(parseFloat(p.total_sales || 0))}</td>
                    </tr>`;
                        });
                    } else {
                        productsHTML = `<tr><td colspan="3" class="text-center py-3">No sales data</td></tr>`;
                    }
                    document.querySelector('#topProductsTable tbody').innerHTML = productsHTML;

                    // Top Buyers table
                    let buyersHTML = '';
                    if (data.topBuyers && data.topBuyers.length) {
                        data.topBuyers.forEach(b => {
                            const name = b.name || (b.type === 'customer' ? ('Customer ' + b.buyer) : (
                                'Guest (' + b.buyer + ')'));
                            buyersHTML += `<tr>
                        <td>${name}</td>
                        <td class="text-center">${b.orders_count}</td>
                        <td class="text-end">${money(parseFloat(b.total_spent || 0))}</td>
                    </tr>`;
                        });
                    } else {
                        buyersHTML = `<tr><td colspan="3" class="text-center py-3">No buyer data</td></tr>`;
                    }
                    document.querySelector('#topBuyersTable tbody').innerHTML = buyersHTML;

                } catch (err) {
                    showLoading(false);
                    console.error(err);
                    alert('Failed to fetch dashboard data. Check console for details.');
                }
            }

            // Initial load: trigger fetch based on preset (or saved)
            fetchData();
        });
    </script>
@endsection
