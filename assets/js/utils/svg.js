'use strict';

const __svg__ = {
    path: '../svg/sprite/*.svg',
    name: 'svgs.svg',
};

require('webpack-svgstore-plugin/src/helpers/svgxhr')(__svg__);
