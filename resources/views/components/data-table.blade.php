@props(['headers' => []])

<div class="flex flex-col">
    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
            <div class="overflow-hidden border border-outline-variant rounded-xl">
                <table class="min-w-full divide-y divide-outline-variant">
                    <thead class="bg-surface-container">
                        <tr>
                            @foreach($headers as $header)
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-on-surface-variant uppercase tracking-wider">
                                    {{ $header }}
                                </th>
                            @endforeach
                            @if(isset($actions))
                                <th scope="col" class="relative px-6 py-3">
                                    <span class="sr-only">Aksi</span>
                                </th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="bg-surface-container-high divide-y divide-outline-variant">
                        {{ $slot }}
                    </tbody>
                </table>
            </div>

            @if(isset($pagination))
                <div class="mt-4">
                    {{ $pagination }}
                </div>
            @endif
        </div>
    </div>
</div>
