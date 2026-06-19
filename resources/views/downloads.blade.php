<x-layout>
    <x-slot name="title">Downloads | Developer Portfolio</x-slot>
    <section class="p-5 glass-panel" style="color:azure;">
        <h1 class="text-center">Downloads</h1>
        <div class="row d-flex justify-content-center align-items-center gap-2">
            @forelse($items as $item)
            <div class="card glass-panel p-2 m-5 col-md-6">
                <div class="row gap-2">
                    <div class="col-md-4"></div>
                    <div class="col-md-8">
                        <h3>{{$item->name}}</h3>
                        <div class="">
                            {{$item->description}}
                        </div>
                    </div>
                </div>
                <button class="mt-2 mb-2 btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#Download{{ $item->id }}">Download</button>
                <div class="modal fade" id="Download{{ $item->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="Download{{ $item->id }}">Modal title</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                ...
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Save changes</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="alert alert-danger col-md-6 text-center">No downloads available</div>
            @endforelse
        </div>

    </section>
</x-layout>