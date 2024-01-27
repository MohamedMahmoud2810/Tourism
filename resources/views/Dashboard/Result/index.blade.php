@extends('layouts.dashboard.app')
@section('style')
@endsection
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="py-12">
                <div class="mx-auto max-w-12xl sm:px-6 lg:px-8">
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <livewire:results/>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div><!-- end of content wrapper -->
@endsection



