@extends('layouts.menu')

@section('title', 'Chat Finanzas')

@section('content')
    <div class="rounded-2xl flex flex-col items-center flex-1 p-10">
        <h2 class="text-2xl font-bold text-gray-700 mb-4">Chat Finanzas</h2>
        <div id="chat-box" class="w-full h-full bg-gray-100 p-4 mb-4 bg-gradient-to-r from-green-200 to-green-100 rounded-3xl overflow-auto no-scrollbar">
            @foreach ($chats as $chat)
                @if ($loop->first || $chat->created_at->toDateString() != $chats[$loop->index - 1]->created_at->toDateString())
                    <div class="text-center text-gray-500 mb-2">
                        {{ $chat->created_at->isToday() ? 'Hoy' : ($chat->created_at->isYesterday() ? 'Ayer' : $chat->created_at->toFormattedDateString()) }}
                    </div>
                @endif
                <div class="{{ $chat->sender == 'usuario' ? 'bg-blue-500 text-white ml-auto' : 'bg-gray-300 text-black mr-auto' }} p-2 rounded-lg mb-2 w-5/12 relative">
                    {{ $chat->message }}
                    <div class="{{ $chat->sender == 'usuario' ? 'bg-blue-500 text-white' : 'text-black' }} text-xs absolute bottom-0 right-0 m-1">
                        {{ $chat->created_at->format('H:i') }}
                    </div>
                </div>
            @endforeach
        </div>
        <form id="chat-form" class="flex items-center bg-white p-4 border-t w-full bg-gradient-to-r from-green-200 to-green-100 rounded-3xl">
            @csrf
            <div class="flex-grow relative w-9/10">
                <input type="text" id="message" name="message" placeholder="Escribe tu mensaje..." class="w-full p-2 border rounded-lg outline-none" required>
            </div>
            <button type="submit" class="w-1/10 bg-gray-600 p-2 rounded-lg text-white transition hover:bg-gray-500 flex justify-center items-center ml-2">
                <i class="fa-solid fa-paper-plane"></i>
            </button>
        </form>
    </div>

    <script>
        document.getElementById('chat-form').addEventListener('submit', function(event) {
            event.preventDefault();

            const message = document.getElementById('message').value;

            fetch('{{ route('chat.send') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ message: message })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                const chatBox = document.getElementById('chat-box');
                const userMessage = document.createElement('div');
                userMessage.classList.add('bg-blue-500', 'text-white', 'p-2', 'rounded-lg', 'mb-2', 'w-5/12', 'ml-auto');
                userMessage.textContent = message;
                const date1Message = document.createElement('div');
                date1Message.classList.add('text-xs', 'absolute', 'bottom-0', 'right-0', 'm-1', 'text-white');
                chatBox.appendChild(date1Message);
                chatBox.appendChild(userMessage);

                if (data.message) {
                    const botMessage = document.createElement('div');
                    botMessage.classList.add('bg-gray-300', 'text-black', 'p-2', 'rounded-lg', 'mb-2', 'w-5/12', 'mr-auto');
                    botMessage.textContent = data.message;
                    chatBox.appendChild(botMessage);
                    const date2Message = document.createElement('div');
                    date2Message.classList.add('text-xs', 'absolute', 'bottom-0', 'right-0', 'm-1', 'text-black');
                    chatBox.appendChild(date2Message);
                }

                chatBox.scrollTop = chatBox.scrollHeight;
                document.getElementById('message').value = '';
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Hubo un error al procesar tu solicitud. Intenta nuevamente más tarde.');
            });
        });
    </script>
@endsection