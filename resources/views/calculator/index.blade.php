<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculadora Laravel</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <h1 class="text-3xl font-bold mb-6 text-center text-gray-800">Calculadora Laravel</h1>

        @if (session('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('message') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Oops!</strong>
                <span class="block sm:inline">Houve alguns problemas com sua entrada.</span>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('calculator.calculate') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="operand1" class="block text-sm font-medium text-gray-700">Operando 1 (Opcional)</label>
                <input type="number" step="any" name="operand1" id="operand1" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Digite um número">
                <p class="mt-1 text-xs text-gray-500">Se deixado em branco, o último valor da pilha será usado (se disponível).</p>
            </div>
            <div>
                <label for="operand2" class="block text-sm font-medium text-gray-700">Operando 2 (Opcional)</label>
                <input type="number" step="any" name="operand2" id="operand2" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Digite um número">
                <p class="mt-1 text-xs text-gray-500">Se deixado em branco, o valor anterior da pilha será usado (se disponível).</p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <button type="submit" name="operation" value="add" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Somar (+)
                </button>
                <button type="submit" name="operation" value="subtract" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                    Subtrair (-)
                </button>
                <button type="submit" name="operation" value="multiply" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2">
                    Multiplicar (*)
                </button>
                <button type="submit" name="operation" value="divide" class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                    Dividir (/)
                </button>
            </div>
            <div class="mt-4">
                <button type="submit" name="operation" value="clear" class="w-full bg-gray-400 hover:bg-gray-500 text-white font-bold py-3 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
                    Limpar Calculadora
                </button>
            </div>
        </form>

        @if (session()->has('result'))
            <div class="mt-6 p-4 bg-indigo-100 border border-indigo-400 text-indigo-700 rounded-md text-center">
                <strong class="text-xl">Resultado:</strong>
                <span class="text-2xl font-semibold ml-2">{{ session('result') }}</span>
            </div>
        @elseif (isset($result) || (string)$result === '0') {{-- Verifica se $result existe ou se é a string '0' --}}
             <div class="mt-6 p-4 bg-indigo-100 border border-indigo-400 text-indigo-700 rounded-md text-center">
                <strong class="text-xl">Último Resultado na Pilha:</strong>
                <span class="text-2xl font-semibold ml-2">{{ $result }}</span>
            </div>
        @endif

        <div class="mt-6 border-t pt-4">
            <h2 class="text-xl font-bold mb-3 text-gray-800">Conteúdo da Pilha (Últimos 5 itens)</h2>
            @if (!empty($stack))
                <ul class="list-disc list-inside text-gray-700">
                    @php
                        $displayStack = array_slice($stack, -5, null, true);
                    @endphp
                    @forelse (array_reverse($displayStack) as $item)
                        <li>{{ $item }}</li>
                    @empty
                        <li>Pilha vazia.</li>
                    @endforelse
                </ul>
            @else
                <p class="text-gray-600">Pilha vazia.</p>
            @endif
        </div>
    </div>
</body>
</html>