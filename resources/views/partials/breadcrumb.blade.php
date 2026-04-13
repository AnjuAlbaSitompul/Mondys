<!--  BEGIN BREADCRUMBS  -->
<div class="secondary-nav">
    <div class="breadcrumbs-container" data-page-heading="Analytics">
        <header class="header navbar navbar-expand-sm">

            <!-- Toggle Sidebar -->
            <a href="javascript:void(0);" class="btn-toggle sidebarCollapse" data-placement="bottom">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="feather feather-menu">
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
            </a>

            <!-- Breadcrumb + DateTime -->
            <div class="d-flex justify-content-between align-items-center w-100 breadcrumb-content">

                <!-- LEFT: Breadcrumb -->
                <div class="page-header">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb d-flex align-items-center gap-2 mb-0">

                            @php
                                $segments = request()->segments();
                                $url = '';
                            @endphp

                            @foreach ($segments as $segment)
                                @php
                                    $url .= '/' . $segment;
                                    $name = ucfirst(str_replace('-', ' ', $segment));
                                @endphp

                                <span class="text-muted">/</span>

                                @if ($loop->last)
                                    <li class="fw-semibold text-dark mb-0">
                                        {{ $name }}
                                    </li>
                                @else
                                    <li class="mb-0">
                                        <a href="{{ url($url) }}" class="text-decoration-none">
                                            {{ $name }}
                                        </a>
                                    </li>
                                @endif
                            @endforeach

                        </ol>
                    </nav>
                </div>

                <!-- RIGHT: DateTime -->
                <div id="datetime" class="text-muted small text-end" style="margin-right: 3px"></div>

            </div>

        </header>
    </div>
</div>
<!--  END BREADCRUMBS  -->


<!-- SCRIPT DATETIME WIB -->
<script>
    function updateDateTime() {
        const now = new Date();

        const formatter = new Intl.DateTimeFormat('id-ID', {
            timeZone: 'Asia/Jakarta',
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });

        document.getElementById('datetime').innerText = formatter.format(now);
    }

    updateDateTime();
    setInterval(updateDateTime, 1000);
</script>
