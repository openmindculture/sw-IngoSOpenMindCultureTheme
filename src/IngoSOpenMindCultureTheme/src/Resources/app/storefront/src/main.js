import IngoSOpenMindCultureTheme from './ingos-open-mind-culture-theme/ingos-open-mind-culture-theme';
import CountryStateSelectPlugin from './country-state-select/country-state-select.plugin';

// Import other plugins if necessary
// Register your plugin via the existing PluginManager
const PluginManager = window.PluginManager;
PluginManager.register('IngoSOpenMindCultureTheme', IngoSOpenMindCultureTheme);

// unregister or replace core plugins
window.PluginManager.override(
    'CountryStateSelect',
    CountryStateSelectPlugin,
    '[data-country-state-select]'
);