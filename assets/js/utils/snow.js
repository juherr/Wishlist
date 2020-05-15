'use strict';

import Snowstorm from '../lib/snowstorm';

// Neige
const snow = new Snowstorm({
    'flakesMaxActive': 80000,
    'animationInterval': 25,
    'followMouse': false,
    'targetElement': 'snow',
})
snow.start();

export default snow;
