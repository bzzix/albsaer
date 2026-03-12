<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 pb-12">
    <!-- Optional Logo at the top -->
    <div class="mb-8 transform transition-transform hover:scale-105">
        {{ $logo }}
    </div>

    <!-- The Glass Card container -->
    <div class="w-full sm:max-w-md mt-6 px-8 py-10 glass-panel shadow-glass rounded-2xl border border-white/60 backdrop-blur-xl bg-white/80">
        {{ $slot }}
    </div>
</div>
