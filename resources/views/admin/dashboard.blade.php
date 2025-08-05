@extends('admin.layouts.app')

@section('page-title', 'Dashboard')

@section('content')
<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Total Users</div>
                        <div class="h5 mb-0 font-weight-bold">{{ $totalUsers }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card success">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Total Quiz</div>
                        <div class="h5 mb-0 font-weight-bold">{{ $totalQuizzes }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-question-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card info">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Total Attempts</div>
                        <div class="h5 mb-0 font-weight-bold">{{ $totalAttempts }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-chart-line fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card warning">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Categories</div>
                        <div class="h5 mb-0 font-weight-bold">{{ $totalCategories }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-tags fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-bolt text-primary"></i> Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 col-md-6 mb-3">
                        <a href="{{ route('admin.categories.create') }}" class="btn btn-outline-primary btn-lg w-100">
                            <i class="fas fa-plus-circle"></i><br>
                            <small>Tambah Kategori</small>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <a href="{{ route('admin.quizzes.create') }}" class="btn btn-outline-success btn-lg w-100">
                            <i class="fas fa-plus-circle"></i><br>
                            <small>Tambah Quiz</small>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-info btn-lg w-100">
                            <i class="fas fa-list"></i><br>
                            <small>Kelola Kategori</small>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <a href="{{ route('admin.quizzes.index') }}" class="btn btn-outline-warning btn-lg w-100">
                            <i class="fas fa-cogs"></i><br>
                            <small>Kelola Quiz</small>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities -->
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-clock text-info"></i> Recent Quiz Attempts
                </h5>
            </div>
            <div class="card-body">
                @if($recentAttempts->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>User</th>
                                    <th>Quiz</th>
                                    <th>Score</th>
                                    <th>Percentage</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentAttempts as $attempt)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm me-2">
                                                    <div class="avatar-title bg-primary rounded-circle">
                                                        {{ substr($attempt->user->name, 0, 1) }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ $attempt->user->name }}</div>
                                                    <small class="text-muted">{{ ucfirst($attempt->user->user_type) }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <div class="fw-bold">{{ Str::limit($attempt->quiz->title, 30) }}</div>
                                                <small class="text-muted">{{ $attempt->quiz->category->name }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ $attempt->score }}</span>
                                            <small class="text-muted">/ {{ $attempt->total_questions }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $attempt->percentage >= 70 ? 'success' : ($attempt->percentage >= 50 ? 'warning' : 'danger') }}">
                                                {{ $attempt->percentage }}%
                                            </span>
                                        </td>
                                        <td>
                                            <small>{{ $attempt->created_at->format('d/m/Y') }}</small><br>
                                            <small class="text-muted">{{ $attempt->created_at->format('H:i') }}</small>
                                        </td>
                                        <td>
                                            <a href="{{ route('quiz.result', $attempt) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No recent attempts found.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- System Info -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle text-success"></i> System Info
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>Laravel Version:</span>
                        <span class="badge bg-primary">{{ app()->version() }}</span>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>PHP Version:</span>
                        <span class="badge bg-success">{{ PHP_VERSION }}</span>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>Database:</span>
                        <span class="badge bg-info">{{ config('database.default') }}</span>
                    </div>
                </div>
                <div>
                    <div class="d-flex justify-content-between">
                        <span>Environment:</span>
                        <span class="badge bg-{{ app()->environment('production') ? 'danger' : 'warning' }}">
                            {{ app()->environment() }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-pie text-warning"></i> Quick Stats
                </h5>
            </div>
            <div class="card-body">
                @php
                    $studentUsers = \App\Models\User::where('user_type', 'student')->where('role', 'user')->count();
                    $publicUsers = \App\Models\User::where('user_type', 'public')->where('role', 'user')->count();
                    $studentCategories = \App\Models\Category::where('type', 'student')->count();
                    $publicCategories = \App\Models\Category::where('type', 'public')->count();
                @endphp
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Student Users</span>
                        <span class="fw-bold">{{ $studentUsers }}</span>
                    </div>
                    <div class="progress" style="height: 5px;">
                        <div class="progress-bar bg-primary" style="width: {{ $totalUsers > 0 ? ($studentUsers / $totalUsers) * 100 : 0 }}%"></div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Public Users</span>
                        <span class="fw-bold">{{ $publicUsers }}</span>
                    </div>
                    <div class="progress" style="height: 5px;">
                        <div class="progress-bar bg-success" style="width: {{ $totalUsers > 0 ? ($publicUsers / $totalUsers) * 100 : 0 }}%"></div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Student Categories</span>
                        <span class="fw-bold">{{ $studentCategories }}</span>
                    </div>
                    <div class="progress" style="height: 5px;">
                        <div class="progress-bar bg-info" style="width: {{ $totalCategories > 0 ? ($studentCategories / $totalCategories) * 100 : 0 }}%"></div>
                    </div>
                </div>

                <div>
                    <div class="d-flex justify-content-between mb-1">
                        <span>Public Categories</span>
                        <span class="fw-bold">{{ $publicCategories }}</span>
                    </div>
                    <div class="progress" style="height: 5px;">
                        <div class="progress-bar bg-warning" style="width: {{ $totalCategories > 0 ? ($publicCategories / $totalCategories) * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar {
    width: 2rem;
    height: 2rem;
}
.avatar-title {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    font-size: 0.875rem;
    font-weight: 600;
}
.btn-lg {
    padding: 1rem;
    min-height: 80px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}
.btn-lg i {
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
}
</style>
@endsection