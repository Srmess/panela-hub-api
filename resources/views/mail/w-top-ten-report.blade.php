<x-mail::message>
<h1 style="text-align: center">🍽️ <bold>Top 10 Receitas Mais Visualizadas da Semana!</bold> 🔥</h1>

<p style="text-align: center">Olá, amante da boa culinária! 👩‍🍳👨‍🍳
Selecionamos para você as **10 receitas mais populares** da nossa plataforma nesta semana.</p>

<h1 style="text-align: center">🏆 Ranking da Semana<h1>
@foreach ($recipes as $index => $recipe)

### 🥇 **{{ $index + 1 . " - " . $recipe->name }}**
📌 **Autor:** {{$recipe->author->name}}
> {{$recipe->description}}
@if (count($recipes) !== $index+1)
---
@endif

@endforeach

</x-mail::message>
