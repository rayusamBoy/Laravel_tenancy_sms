'use strict';

// Storage helpers
const getStoredTheme = () => localStorage.getItem('theme');
const setStoredTheme = theme => localStorage.setItem('theme', theme);

// Available themes
const colorThemes = ['auto', 'light', 'dark'];

// Determine the user’s preferred theme
const getPreferredTheme = () => {
    const storedTheme = getStoredTheme();
    return storedTheme ?? (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
};

// Update charts with the given theme
const updateJSCharts = themeInput => {
    // Resolve 'auto' to the actual theme
    const theme = themeInput === 'auto'
        ? window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'
        : themeInput;

    if (typeof Chart !== 'undefined') {
        Chart.helpers.each(Chart.instances, instance => {
            const yAxisTitle = instance.canvas.getAttribute('data-y_axis_title_text');
            const xAxisTitle = instance.canvas.getAttribute('data-x_axis_title_text');

            if (localStorage.chart_type_check.includes('horizontal')) {
                instance.options.scales.x.title.text = yAxisTitle;
                instance.options.scales.y.title.text = xAxisTitle;
            } else {
                instance.options.scales.x.title.text = xAxisTitle;
                instance.options.scales.y.title.text = yAxisTitle;
            }

            const scalesId = ['x', 'y', 'r'];

            // Define color configurations for dark and default themes.
            const darkConfig = {
                textColor: "#dadce0",
                borderColor: "#262626",
                bgColor: "#171717",
            };

            scalesId.forEach(scaleId => {
                const scale = instance.options.scales[scaleId];

                if (!scale) return;

                if (theme === 'dark') {
                    scale.ticks.color = darkConfig.textColor;
                    scale.ticks.backdropColor = darkConfig.bgColor;
                    scale.grid.color = darkConfig.borderColor;
                    scale.title.color = darkConfig.textColor;
                    instance.options.plugins.legend.labels.color = darkConfig.textColor;
                    if (scaleId !== 'r') {
                        scale.border.color = darkConfig.borderColor;
                        scale.grid.tickColor = darkConfig.borderColor;
                    } else {
                        scale.angleLines.color = darkConfig.textColor;
                        scale.pointLabels.color = darkConfig.textColor;
                    }
                } else {
                    // Use Chart defaults for non-dark themes.
                    scale.ticks.color = Chart.defaults.color;
                    scale.ticks.backdropColor = Chart.defaults.backdropColor;
                    scale.grid.color = Chart.defaults.borderColor;
                    scale.title.color = Chart.defaults.color;
                    instance.options.plugins.legend.labels.color = Chart.defaults.color;
                    if (scaleId !== 'r') {
                        scale.border.color = Chart.defaults.borderColor;
                        scale.grid.tickColor = Chart.defaults.borderColor;
                    } else {
                        scale.angleLines.color = Chart.defaults.borderColor;
                        scale.pointLabels.color = Chart.defaults.color;
                    }
                }
            });

            instance.update();
        });
    }
};

// Apply the theme to the root element
const setTheme = theme => {
    const resolvedTheme = theme === 'auto'
        ? window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'
        : theme;
    document.documentElement.setAttribute('data-theme', resolvedTheme);
};

// Set the initial theme
setTheme(getPreferredTheme());

// Update toggle elements to reflect the next theme state
const showActiveTheme = theme => {
    const navLinkColorModes = document.querySelectorAll('.color-mode-toggle');
    const currentIndex = colorThemes.indexOf(theme);
    const nextTheme = colorThemes[(currentIndex + 1) % colorThemes.length];

    navLinkColorModes.forEach(navLink => {
        navLink.dataset.theme = nextTheme;
        // Update the icon/text in the first child element (if it exists)
        if (navLink.firstElementChild) {
            navLink.firstElementChild.textContent = nextTheme === 'auto'
                ? 'mode_standby'
                : nextTheme === 'light'
                    ? 'light_mode'
                    : 'dark_mode';
            navLink.setAttribute('title', `Switch to ${nextTheme} mode`);
        }
    });
};

// Listen for system color scheme changes
window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
    const storedTheme = getStoredTheme();
    if (!['light', 'dark'].includes(storedTheme)) {
        setTheme(getPreferredTheme());
    }
});

document.addEventListener('DOMContentLoaded', () => {
    const preferredTheme = getPreferredTheme();
    showActiveTheme(preferredTheme);

    document.querySelectorAll('.color-mode-toggle').forEach(toggle => {
        toggle.addEventListener('click', event => {
            const theme = event.currentTarget.dataset.theme;
            setStoredTheme(theme);
            setTheme(theme);
            updateJSCharts(theme);
            showActiveTheme(theme);
        });
    });
});
