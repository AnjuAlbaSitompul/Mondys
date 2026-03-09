                    <!--  BEGIN BREADCRUMBS  -->
                    <div class="secondary-nav">
                        <div class="breadcrumbs-container" data-page-heading="Analytics">
                            <header class="header navbar navbar-expand-sm">
                                <a href="javascript:void(0);" class="btn-toggle sidebarCollapse" data-placement="bottom">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu">
                                        <line x1="3" y1="12" x2="21" y2="12">
                                        </line>
                                        <line x1="3" y1="6" x2="21" y2="6">
                                        </line>
                                        <line x1="3" y1="18" x2="21" y2="18">
                                        </line>
                                    </svg>
                                </a>
                                <div class="d-flex breadcrumb-content">
                                    <div class="page-header">



                                        <nav aria-label="breadcrumb">
                                            <ol class="breadcrumb d-flex align-items-center gap-2">

                                                <li class="fw-semibold">
                                                    <a href="{{ url('/dashboard') }}"
                                                        class="text-decoration-none">Dashboard</a>
                                                </li>

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
                                                        <li class="fw-semibold text-dark">
                                                            {{ $name }}
                                                        </li>
                                                    @else
                                                        <li>
                                                            <a href="{{ url($url) }}" class="text-decoration-none">
                                                                {{ $name }}
                                                            </a>
                                                        </li>
                                                    @endif
                                                @endforeach

                                            </ol>
                                        </nav>

                                    </div>
                                </div>
                            </header>
                        </div>
                    </div>
                    <!--  END BREADCRUMBS  -->
