 @if ($errors->any())
     <div class="mb-6 rounded-2xl border border-red-400/30 bg-red-500/10 p-4">
         <div class="font-bold text-red-200 mb-2">
             Existem erros no formulário:
         </div>

         <ul class="list-disc list-inside text-sm text-red-100 space-y-1">
             @foreach ($errors->all() as $error)
                 <li>{{ $error }}</li>
             @endforeach
         </ul>
     </div>
 @endif
