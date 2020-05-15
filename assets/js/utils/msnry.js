'use strict';

import Masonry from 'masonry-layout';

const msnry = new Masonry('.content', {
    itemSelector: '.user',
    columnWidth: '.grid-sizer',
    percentPosition: true,
    gutter: 50,
});

export default msnry;
