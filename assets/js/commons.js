'use strict';

import Snowstorm from './lib/snowstorm';
import Masonry from 'masonry-layout';

const __svg__ = {
    path: '../svg/sprite/*.svg',
    name: 'svgs.svg',
};

require('webpack-svgstore-plugin/src/helpers/svgxhr')(__svg__);

// Neige
const snow = new Snowstorm({
    'flakesMaxActive': 80000,
    'animationInterval': 25,
    'followMouse': false,
    'targetElement': 'snow',
})
snow.start();

const msnry = new Masonry('.content', {
    itemSelector: '.user',
    columnWidth: '.grid-sizer',
    percentPosition: true,
    gutter: 50,
});

export {
    snow,
    msnry,
}
