<x-ui.box title="Counter" class="mt-auto">
    <div class="p-3">
        <div class="p-3 bg-slate-100 text-center font-bold rounded-2xl">
            <p id="counter">0</p>
        </div>
    </div>
    <div class="pb-2 px-3">
        <x-ui.button url="#" variant="primary" class="justify-center text-[13px] w-full rounded-t-0" id="counterIncrease">Counter erhöhen</x-ui.button>
    </div>
    <div class="pb-2 px-3">
        <x-ui.button url="#" variant="danger" class="justify-center text-[13px] w-full rounded-t-0" id="counterDecrease">Counter verringern</x-ui.button>
    </div>
    <div class="pb-3 px-3">
        <x-ui.button url="#" variant="success" class="justify-center text-[13px] w-full rounded-t-0" id="counterReset">Counter zurücksetzen</x-ui.button>
    </div>
</x-ui.box>