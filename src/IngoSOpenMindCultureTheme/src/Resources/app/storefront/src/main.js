import IngoSOpenMindCultureTheme from './ingo-s-open-mind-culture-theme/ingo-s-open-mind-culture-theme';
import CountryStateSelectPlugin from './country-state-select/country-state-select.plugin';

const PluginManager = window.PluginManager;

PluginManager.register(
    'IngoSOpenMindCultureTheme',
    () => import('./ingo-s-open-mind-culture-theme/ingo-s-open-mind-culture-theme')
);

// unregister or replace core plugins
window.PluginManager.override(
    'CountryStateSelect',
    () => import('./country-state-select/country-state-select.plugin'),
    '[data-country-state-select]'
);