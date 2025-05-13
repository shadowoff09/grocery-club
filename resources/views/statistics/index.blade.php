<x-layouts.app>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        <!-- Pie Chart - Order Status -->
        <div class="flex flex-col justify-between rounded-xl border border-neutral-200 bg-white p-6 shadow-md transition-all hover:shadow-lg dark:border-neutral-700 dark:bg-neutral-800 dark:hover:border-neutral-600">
            <h2 class="text-lg font-medium mb-2">Orders by Status</h2>
            <div class="chart-container" data-chart="pie" style="height: 400px;"></div>
        </div>

        <!-- Bar Chart - Monthly Orders -->
        <div class="flex flex-col justify-between rounded-xl border border-neutral-200 bg-white p-6 shadow-md transition-all hover:shadow-lg dark:border-neutral-700 dark:bg-neutral-800 dark:hover:border-neutral-600">
            <h2 class="text-lg font-medium mb-2">Monthly Orders</h2>
            <div class="chart-container" data-chart="bar" style="height: 400px;"></div>
        </div>

        <!-- Line Chart - User Growth -->
        <div class="flex flex-col justify-between rounded-xl border border-neutral-200 bg-white p-6 shadow-md transition-all hover:shadow-lg dark:border-neutral-700 dark:bg-neutral-800 dark:hover:border-neutral-600">
            <h2 class="text-lg font-medium mb-2">User Growth</h2>
            <div class="chart-container" data-chart="line" style="height: 400px;"></div>
        </div>
    </div>

    @vite(['resources/js/echarts.js'])
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Generic text style for all charts
            const textStyle = {
                color: '#555',
                fontWeight: 500
            };
            
            // Get data from controller
            const orderLabels = @json($pieChartData['labels'] ?? []);
            const orderValues = @json($pieChartData['values'] ?? []);
            
            const monthlyLabels = @json($barChartData['labels'] ?? []);
            const monthlyValues = @json($barChartData['values'] ?? []);
            
            const userGrowthLabels = @json($lineChartData['labels'] ?? []);
            const userGrowthValues = @json($lineChartData['values'] ?? []);
            
            // Create data arrays for charts
            const orderData = orderLabels.map((label, index) => {
                return {
                    name: label,
                    value: orderValues[index]
                };
            });
            
            // Chart configurations based on type
            const chartConfigs = {
                pie: () => ({
                    backgroundColor: 'transparent',
                    textStyle: textStyle,
                    tooltip: {
                        trigger: 'item',
                        formatter: '{a} <br/>{b}: {c} ({d}%)'
                    },
                    legend: {
                        orient: 'horizontal',
                        bottom: '0',
                        textStyle: textStyle
                    },
                    series: [
                        {
                            name: 'Order Status',
                            type: 'pie',
                            radius: '60%',
                            center: ['50%', '50%'],
                            data: orderData,
                            label: {
                                show: true, 
                                color: '#555',
                                fontWeight: 500,
                                formatter: '{b}: {c} ({d}%)'
                            },
                            labelLine: {
                                lineStyle: {
                                    color: '#666'
                                }
                            },
                            emphasis: {
                                itemStyle: {
                                    shadowBlur: 10,
                                    shadowOffsetX: 0,
                                    shadowColor: 'rgba(0, 0, 0, 0.5)'
                                }
                            }
                        }
                    ]
                }),
                
                bar: () => ({
                    backgroundColor: 'transparent',
                    textStyle: textStyle,
                    tooltip: {
                        trigger: 'axis',
                        formatter: '{b}: {c} orders'
                    },
                    grid: {
                        left: '3%',
                        right: '4%',
                        bottom: '3%',
                        containLabel: true
                    },
                    xAxis: {
                        type: 'category',
                        data: monthlyLabels,
                        axisLabel: {
                            rotate: 45,
                            color: '#555'
                        }
                    },
                    yAxis: {
                        type: 'value',
                        name: 'Orders',
                        nameTextStyle: textStyle
                    },
                    series: [
                        {
                            name: 'Monthly Orders',
                            data: monthlyValues,
                            type: 'bar',
                            showBackground: true,
                            itemStyle: {
                                color: '#5470c6' 
                            },
                            backgroundStyle: {
                                color: 'rgba(180, 180, 180, 0.2)'
                            },
                            label: {
                                show: true,
                                position: 'top',
                                color: '#555'
                            }
                        }
                    ]
                }),
                
                line: () => ({
                    backgroundColor: 'transparent',
                    textStyle: textStyle,
                    tooltip: {
                        trigger: 'axis'
                    },
                    grid: {
                        left: '3%',
                        right: '4%',
                        bottom: '3%',
                        containLabel: true
                    },
                    xAxis: {
                        type: 'category',
                        data: userGrowthLabels,
                        boundaryGap: false
                    },
                    yAxis: {
                        type: 'value',
                        name: 'Total Users',
                        nameTextStyle: textStyle
                    },
                    series: [
                        {
                            name: 'User Growth',
                            data: userGrowthValues,
                            type: 'line',
                            smooth: true,
                            symbol: 'circle',
                            symbolSize: 7,
                            itemStyle: {
                                color: '#91cc75'
                            },
                            lineStyle: {
                                width: 3,
                                shadowColor: 'rgba(0,0,0,0.3)',
                                shadowBlur: 10,
                                shadowOffsetY: 5
                            },
                            areaStyle: {
                                color: {
                                    type: 'linear',
                                    x: 0,
                                    y: 0,
                                    x2: 0,
                                    y2: 1,
                                    colorStops: [{
                                        offset: 0, color: 'rgba(145, 204, 117, 0.5)'
                                    }, {
                                        offset: 1, color: 'rgba(145, 204, 117, 0.1)'
                                    }]
                                }
                            },
                            markPoint: {
                                data: [
                                    { type: 'max', name: 'Maximum' }
                                ]
                            }
                        }
                    ]
                })
            };
            
            // Initialize all charts
            function initializeCharts() {
                const chartContainers = document.querySelectorAll('.chart-container');
                
                chartContainers.forEach(container => {
                    const chartType = container.dataset.chart;
                    
                    if (chartType && chartConfigs[chartType]) {
                        const chart = echarts.init(container);
                        const options = chartConfigs[chartType]();
                        chart.setOption(options);
                        
                        // Store chart instance for resizing
                        container._echarts = chart;
                    }
                });
            }
            
            // Initialize all charts
            initializeCharts();
            
            // Handle window resize - efficiently resize all charts
            let resizeTimeout;
            window.addEventListener('resize', () => {
                // Debounce resize to avoid excessive calls
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(() => {
                    document.querySelectorAll('.chart-container').forEach(container => {
                        if (container._echarts) {
                            container._echarts.resize();
                        }
                    });
                }, 250);
            });
        });
    </script>

</x-layouts.app>
