<x-filament::page>
    <div class="kanban-container">

        @foreach ($this->getStatuses() as $status)
            @include('partials.kanban.status')
        @endforeach

    </div>

    @push('scripts')
        <script src="{{ asset('js/Sortable.js') }}"></script>
        <script>
            (() => {
                let record;
                @foreach ($this->getStatuses() as $status)
                    record = document.querySelector('#status-records-{{ $status['id'] }}');

                    Sortable.create(record, {
                        group: {
                            name: 'status-{{ $status['id'] }}',
                            pull: true,
                            put: true
                        },
                        // handle: '.handle',
                        animation: 100,
                        onEnd: function(evt) {
                            // Mengambil status baru setelah pemindahan
                            let newStatus = +evt.to.dataset.status;
                            let currentStatus = +evt.from.dataset.status; // Status saat ini

                            // Cek jika status yang dipilih adalah "approved"
                            if (newStatus === 4) {
                                // Jika pengguna tidak memiliki role 'ketua' atau 'koordinator', batalkan pemindahan
                                @if (auth()->user()->hasRole('Anggota'))
                                    // Jika user tidak memiliki role yang sesuai, tampilkan notifikasi error
                                    alert('Hanya ketua dan koordinator yang dapat memindahkan ke status approved')
                                    // Filament.notification('warning', __('Hanya ketua dan koordinator yang dapat memindahkan ke status approved.'));
                                    evt.from.appendChild(evt.item); // Kembalikan item ke status sebelumnya
                                    return; // Batalkan pemindahan
                                @endif
                            }

                            if(currentStatus === 4 && newStatus !== 4){
                                @if (auth()->user()->hasRole('Anggota'))
                                    // Jika user tidak memiliki role yang sesuai, tampilkan notifikasi error
                                    alert('Tugas Anda telah diapproved. Jika anda ingin mengubahnya, hubungi Koordinator Subtim dari project Anda')
                                    evt.from.appendChild(evt.item); // Kembalikan item ke status sebelumnya
                                    return; // Batalkan pemindahan
                                @endif
                            }

                            Livewire.emit('recordUpdated',
                                +evt.clone.dataset.id, // id
                                +evt.newIndex, // newIndex
                                +evt.to.dataset.status, // newStatus
                            );
                        },
                    })
                @endforeach
            })();
        </script>
    @endpush

</x-filament::page>