@extends('layouts.app')
@section('title', 'Instructors')
@section('content')

@section('breadcrumb')
<x-breadcrumb title="Instructors" page="Instructors" active="Vendor Category" route="{{ route('vendors_categories.index') }}" />
@endsection
<!-- Content -->
<section class="section">

    <div class="alert alert-danger alert-dismissible mb-3 mt-3 fade show position-relative" role="alert">
        <div class="d-flex">
            <i class="bi-bell-fill text-white fs-1 me-3 flex-shrink-0 align-self-start"></i>
            <div class="text-white mt-0">
                Manage the Vendor Categories available in the system.<br>
                Add, update, or deactivate vendor categories to support instructor and class management.
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center ">
                <h4 class="fw-normal mb-0 text-body">Vendor Category</h4>
                @can('vendors_categories.store')
                <button type="button" class="btn btn-primary btn-md" data-bs-toggle="modal"
                    data-bs-target="#modal-form-add-vendors_categories">
                    <i class="bi bi-plus-lg"></i>
                    Add New
                </button>
                @endcan

            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive text-nowrap mx-2">
                <table class="table table table-bordered" id="table1">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Vendor Category</th>
                            <th>Description</th>
                            <th>Total Vendors</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($vendors_categories as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->description }}</td>
                            <td>{{ $item->vendors_categories_count }}</td>

                            <td>
                                @can('vendors_categories.update')
                                <a data-bs-toggle="modal"
                                    data-bs-target="#modal-form-edit-vendors_categories-{{ $item->uuid }}"
                                    class="btn btn-icon btn-success text-white">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                                @include('pages.vendors_categories.modal-edit')
                                @endcan
                            </td>

                            <td>
                                @can('vendors_categories.destroy')
                                <a onclick="showSweetAlert('{{ $item->uuid }}')" title="Delete"
                                    class="btn btn-icon btn-danger text-white">
                                    <i class="bi bi-x-square"></i> Delete
                                </a>
                                <form id="deleteForm_{{ $item->uuid }}"
                                    action="{{ route('vendors_categories.destroy', $item->uuid) }}" method="POST">
                                    @method('DELETE')
                                    @csrf
                                </form>
                                @endcan
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<!-- / Content -->

<!--/ Basic Bootstrap Table -->
@include('pages.vendors_categories.modal-create')

<script>
    function showSweetAlert(getId) {
        Swal.fire({
            title: 'Confirm Deletion',
            text: 'This data will be permanently deleted and cannot be recovered. Are you sure you want to delete it?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Deleted!'
        }).then((result) => {
            if (result.isConfirmed) {
                // If the user clicks "Yes, delete it!", submit the corresponding form
                document.getElementById('deleteForm_' + getId).submit();
            }
        });
    }
</script>
@endsection