<x-layouts.app>

    <div class="grid grid-cols-3 gap-4">
        <div class="flex flex-col justify-between rounded-xl border border-neutral-200 bg-white p-6 shadow-md transition-all hover:shadow-lg dark:border-neutral-700 dark:bg-neutral-800 dark:hover:border-neutral-600">
            <div class="mt-2">
                <div>{!! $chart->container() !!}</div>
            </div>
        </div>


    </div>

    <script src="https://code.highcharts.com/highcharts.js"></script>

    {!! $chart->script() !!}
</x-layouts.app>
