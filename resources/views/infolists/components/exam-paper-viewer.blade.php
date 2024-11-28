<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
        <object data="{{ asset($getState()) }}" class="w-full h-dvh">
            <p>Unable to display exam paper.</p>
        </object>
</x-dynamic-component>
