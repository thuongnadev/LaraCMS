<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        darkMode: 'class',
        theme: {
            extend: {}
        },
        variants: {
            extend: {}
        },
        plugins: [],
    }
    document.documentElement.classList.remove('dark');
    document.documentElement.classList.add('light');
</script>
<style>
    :root {
        color-scheme: light !important;
    }

    @media (prefers-color-scheme: dark) {

        html,
        body {
            background-color: #ffffff !important;
            color: #000000 !important;
        }
    }

    .table-of-contents {
        background-color: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 30px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .table-of-contents ul {
        list-style-type: none;
        padding-left: 0;
    }

    .table-of-contents li {
        margin-bottom: 10px;
        padding-left: 20px;
        position: relative;
    }

    .table-of-contents li::before {
        content: '▸';
        position: absolute;
        left: 0;
    }

    .table-of-contents a {
        text-decoration: none;
        color: #495057;
        transition: color 0.3s ease;
    }

    .table-of-contents a:hover {
        color: #2f3439;
    }

    .post-content h2 {
        font-size: 18px !important;
        font-weight: 500;
        margin-top: 10px;
        margin-bottom: 5px;
    }

    .post-content img {
        margin: 0 auto;
        margin-top: 10px;
        border-radius: 10px;
    }

    .post-content p {
        margin: 0;
    }

    .post-content figcaption {
        display: flex;
        justify-content: center;
    }
</style>

<x-filament::page>
    @livewire('theme::post-detail', ['slug' => $record->slug])
</x-filament::page>
