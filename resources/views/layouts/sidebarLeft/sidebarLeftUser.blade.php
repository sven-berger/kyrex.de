<x-ui.box title="Hallo {{ auth()->user()->name }}!" class="mt-auto">
    <div class="space-y-2 p-3 text-sm rounded-b-2xl">
        @role('admin')
        <a href="{{ route('acp') }}"
            class="block w-full border border-amber-400 rounded-xl bg-amber-500 p-2 text-center text-[13px] font-semibold text-white">
            Adminbereich
        </a>
        @endrole

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full border border-red-400 rounded-xl bg-red-600 p-2 text-[13px] font-semibold text-white">
                Ausloggen
            </button>
        </form>
    </div>
</x-ui.box>