<main class="status">
    <div class="logo-loader">
        <img src="{{ asset('assets/images/favicon.png') }}" alt="Teamiy Logo">
    </div>
</main>

<style>
.status {position: fixed;top: 0;left: 0;width: 100vw;height: 100vh;margin: 0;padding: 0;background: transparent;z-index: 99999;}
.logo-loader {position: absolute;top: 50%;left: 50%;transform: translate(-50%, -50%);}
.logo-loader img {width: 120px;height: auto;display: block;border-radius: 15px;}
</style>
