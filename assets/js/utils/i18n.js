import i18n from 'i18next';
import frYaml from 'js-yaml-loader!./../../../translations/messages.fr.yaml';
import enYaml from 'js-yaml-loader!./../../../translations/messages.en.yaml';

const splitPlurals = (object) => {
    const newObject = {};
    Object.keys(object).forEach((key) => {
        let elem = object[key];
        if (typeof elem === 'object') {
            newObject[key] = splitPlurals(elem);
            return;
        }
        // replace all symfony parameters %param% with {{param}} in all strings for js
        elem = String(elem).replace(/%([^%]+(?=%))%/gi, '{{$1}}');

        // splits all plurales like "one apple|many apples" into different keys apple and apple_plural
        if (typeof elem === 'string' && elem.includes('|')) {
            const plural = elem.split('|');
            newObject[key] = plural.shift();
            newObject[`${key}_plural`] = plural.shift();

            return;
        }

        newObject[key] = elem;
    });

    return newObject;
};

i18n
    .init({
        resources: {
            fr: {
                translation: splitPlurals(frYaml),
            },
            en: {
                translation: splitPlurals(enYaml),
            },
        },
        lng: (window && window.locale) || 'fr',
        fallbackLng: 'fr',
    });

export default i18n;
