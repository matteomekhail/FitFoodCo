<div
    class="{{ $isOpen ? 'fixed inset-0 z-50 transition-all duration-500 ease-in-out transform translate-x-0 overflow-hidden' : 'fixed inset-0 z-50 transition-all duration-500 ease-in-out transform translate-x-full' }}">
    <div class="fixed right-0 top-0 h-full w-full md:w-80 lg:w-[25rem] shadow-2xl p-4 z-50 flex flex-col"
        style="background-image: radial-gradient(at top left, #FACB01 0%, #FAD961 50%, #FACB01 100%);">
        <div class="sticky top-0 z-50 bg-trasparent p-4">
            <h2 class="text-2xl text-black">Your Cart</h2>
            <svg wire:click="$set('isOpen', false)" class="h-6 w-6 absolute top-4 right-4 cursor-pointer"
                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="red">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </div>
        @if (count($cartItems) > 0)
            <div class="flex flex-col gap-4 flex-grow overflow-auto pt-4 pb-4">
                @foreach ($cartItems as $item)
                    @if ($item->product)
                        <div class="relative shadow-sm bg-[#fafafa] flex min-h-32 h-auto rounded-xl"
                            wire:key="{{ $item->product->id }}">
                            <!-- Icona del cestino per cancellare, posizionata assolutamente all'interno del container del prodotto -->
                            <svg wire:click.stop="dispatch('updateQuantity', [{{ $item->product->id }}, {{ $item->quantity > 0 ? -$item->quantity : 0 }}])"
                                class="h-6 w-6 absolute top-4 right-4 cursor-pointer text-red-500"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            <img src="{{ $item->product->image }}" alt="Product image"
                                class="w-24 h-full object-cover rounded-l-xl">
                            <!-- Contenitore per testo del prodotto e pulsanti, con spazio per l'icona del cestino -->
                            <div class="pt-4 p-4 ml-4 flex-grow flex flex-col justify-between">
                                <div class="flex-grow">
                                    <!-- Usa 'max-w-[calc(100%-1.5rem)]' per impedire al testo di sovrapporsi all'icona del cestino -->
                                    <div class="font-bold text-black text-sm max-w-[calc(100%-1.5rem)]">
                                        {{ $item->product->name }}</div>
                                </div>
                                <div class="flex justify-between items-center">
                                    @php
                                        $totalQuantity = collect($cartItems)->reduce(function ($carry, $item) {
                                            return $carry + $item->quantity;
                                        }, 0);
                                    @endphp

                                    <span class="text-lg text-black">
                                        @if (auth()->user() && auth()->user()->{"10Dollars"})
                                            10
                                        @elseif (auth()->user() && auth()->user()->wholesale)
                                            9
                                        @elseif ($totalQuantity > 20)
                                            <del class="text-red-500">{{ $item->product->price }}</del> 10
                                        @else
                                            {{ $item->product->price }}
                                        @endif
                                    </span>
                                    <div class="flex items-center">
                                        <button
                                            wire:click.stop="dispatch('updateQuantity', [{{ $item->product->id }}, -1])"
                                            class="bg-[#FACB01] text-black hover:text-white focus:ring-[#FAD961] py-2 px-4 rounded-full shadow-lg transition-all duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-110 focus:outline-none">-</button>
                                        <span class="text-lg text-black mx-4">{{ $item->quantity }}</span>
                                        <button
                                            wire:click.stop="dispatch('updateQuantity', [{{ $item->product->id }}, 1])"
                                            class="bg-[#FACB01] text-black hover:text-white focus:ring-[#FAD961] py-2 px-4 rounded-full shadow-lg transition-all duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-110 focus:outline-none">+</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

<div class="mt-6 mb-6 bg-white rounded-xl p-6 shadow-sm border border-gray-200">
    <h3 class="text-lg font-semibold text-black mb-4">Pickup Options</h3>
    <div class="mb-4">
        <label for="pickup" class="flex items-center cursor-pointer">
            <input type="checkbox" id="pickup" wire:model.live="isPickup" class="form-checkbox h-5 w-5 text-[#FACB01] rounded border-gray-300 focus:ring-[#FACB01]">
            <span class="ml-3 text-sm text-gray-700">Gym Pickup</span>
        </label>
    </div>

    @if ($isPickup)
        <div class="mb-4">
            <select wire:model.live="selectedGym" class="w-full p-2 text-sm border border-gray-300 rounded-md text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#FACB01] focus:border-transparent">
                <option value="">Select a gym</option>
                @foreach ($gyms as $gym)
                    <option value="{{ $gym->id }}">{{ $gym->name }}</option>
                @endforeach
            </select>
        </div>
        @if ($selectedGym)
            <div class="text-sm text-gray-600 space-y-1">
                <p>{{ $gyms->find($selectedGym)->address }}</p>
                <p>{{ $gyms->find($selectedGym)->city }}, {{ $gyms->find($selectedGym)->postal_code }}</p>
                <p>{{ $gyms->find($selectedGym)->phone }}</p>
            </div>
        @endif
    @endif
</div>

            <div class="sticky bottom-0 z-50 bg-transparent p-4 border-t border-black">
                <div class="flex justify-between mb-4">
                    <span class="text-lg text-black font-bold">Total</span>
                    <span class="text-lg text-black font-bold">
                        @if (auth()->user() && auth()->user()->{"10Dollars"})
                            ${{ number_format(10 * $totalQuantity, 2) }}
                        @elseif (auth()->user() && auth()->user()->wholesale)
                            ${{ number_format(9 * $totalQuantity, 2) }}
                        @elseif ($totalQuantity > 20)
                            ${{ number_format(10 * $totalQuantity, 2) }}
                        @else
                            ${{ number_format(
                                collect($cartItems)->reduce(function ($carry, $item) {
                                    return $carry + ($item->product ? $item->product->price * $item->quantity : 0);
                                }, 0),
                                2,
                            ) }}
                        @endif
                    </span>
                </div>

                @if (!$isPickup && !Auth::user()?->free_shipping)
                    <div class="text-sm text-gray-500">+ $9.99 shipping</div>
                @else
                    <div class="text-sm text-gray-500">Free Shipping</div>
                @endif

                <button wire:click="checkout"
                    class="bg-white text-black w-full px-4 py-2 rounded-lg text-lg flex justify-center items-center transition-colors duration-200 ease-in-out hover:bg-black hover:text-[#FACB01]">
                    <i class="fas fa-shopping-cart mr-2"></i>
                    Checkout
                </button>
            </div>
        @else
            <div class="text-gray-500 text-lg my-4 text-center flex justify-center items-center flex-grow">
                <i class="fas fa-shopping-cart mr-2"></i>
                Your cart is empty
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
    </div>
</div>
