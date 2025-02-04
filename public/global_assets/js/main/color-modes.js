'use strict'

const getStoredTheme = () => localStorage.getItem('theme');
const setStoredTheme = theme => localStorage.setItem('theme', theme);

const getPreferredTheme = () => {
    const storedTheme = getStoredTheme();
    if (storedTheme) {
        return storedTheme;
    }

    return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
}

const updateJSCharts = theme => {
    if (theme === 'auto')
        theme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    if (typeof Chart != 'undefined')
        Chart.helpers.each(Chart.instances, function (instance) {
            instance.options.scales["x"].title.text = $(instance.canvas).data('x_axis_title_text');
            instance.options.scales["y"].title.text = $(instance.canvas).data('y_axis_title_text');

            const scales_id = ['x', 'y', 'r'];

            if (theme == 'dark') {
                const text_color = "#dadce0";
                const border_color = "#262626";
                const bg_color = "#171717";

                scales_id.forEach(
                    function (scale_id) {
                        if (typeof instance.options.scales[scale_id] != 'undefined') {
                            // For all chart types
                            instance.options.scales[scale_id].ticks.color = text_color;
                            instance.options.scales[scale_id].ticks.backdropColor = bg_color;
                            instance.options.scales[scale_id].grid.color = border_color;
                            instance.options.scales[scale_id].title.color = text_color;
                            // For other than radial chart
                            if (scale_id != 'r') {
                                instance.options.scales[scale_id].border.color = border_color;
                                instance.options.scales[scale_id].grid.tickColor = border_color;
                            }
                            // For radial chart only
                            if (scale_id == 'r') {
                                instance.options.scales[scale_id].angleLines.color = text_color;
                                instance.options.scales[scale_id].pointLabels.color = text_color;
                            }
                        }
                    }
                );
            } else {
                scales_id.forEach(
                    function (scale_id) {
                        if (typeof instance.options.scales[scale_id] != 'undefined') {
                            // For all chart types
                            instance.options.scales[scale_id].ticks.color = Chart.defaults.color;
                            instance.options.scales[scale_id].ticks.backdropColor = Chart.defaults.backdropColor;
                            instance.options.scales[scale_id].grid.color = Chart.defaults.borderColor;
                            instance.options.scales[scale_id].title.color = Chart.defaults.color;
                            // For other than radial chart
                            if (scale_id != 'r') {
                                instance.options.scales[scale_id].border.color = Chart.defaults.borderColor;
                                instance.options.scales[scale_id].grid.tickColor = Chart.defaults.borderColor;
                            }
                            // For radial chart only
                            if (scale_id == 'r') {
                                instance.options.scales[scale_id].angleLines.color = Chart.defaults.borderColor;
                                instance.options.scales[scale_id].pointLabels.color = Chart.defaults.color;
                            }
                        }
                    }
                );
            }

            instance.update();
        });
}

const setTheme = theme => {
    if (theme === 'auto') {
        document.documentElement.setAttribute('data-theme', (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'));
    } else {
        document.documentElement.setAttribute('data-theme', theme);
    }
}

setTheme(getPreferredTheme());

const showActiveTheme = (theme, focus = false) => {
    // Select all color modes radio inputs (3 inputs)
    const themeSwitcher = document.querySelectorAll('input[name="color_mode"]');

    if (themeSwitcher.length != 3) {
        return;
    }

    themeSwitcher.forEach(
        function (node, index) {
            if (theme === node.getAttribute('id'))
                node.checked = true;
        }
    );
}

window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
    const storedTheme = getStoredTheme()
    if (storedTheme !== 'light' && storedTheme !== 'dark') {
        setTheme(getPreferredTheme())
    }
    Chart.defaults.borderColor = '#303030';
});

window.addEventListener('DOMContentLoaded', () => {
    showActiveTheme(getPreferredTheme());

    document.querySelectorAll('input[name="color_mode"]')
        .forEach(toggle => {
            toggle.addEventListener('change', () => {
                const theme = toggle.getAttribute('id');
                setStoredTheme(theme);
                setTheme(theme);
                updateJSCharts(theme);
                showActiveTheme(theme, true);
            })
        });
});