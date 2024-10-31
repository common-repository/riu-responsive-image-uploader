/*
    This file is part of RIU - Responsive Image Uploader.

    RIU is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    RIU is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Foobar.  If not, see <http://www.gnu.org/licenses/>.
*/

function riuRefresh() {

    var elements = document.getElementsByClassName('riu');

    for (var i = 0; i<elements.length; i++) {

        var container;

        if(window.getComputedStyle) {
            container = [
                parseInt(window.getComputedStyle(elements[i]).width),
                parseInt(window.getComputedStyle(elements[i]).height)
            ];
        } else
            container = [
                elements[i].width,
                elements[i].height
            ];

        var  ratio = elements[i].getAttribute('ratio'),
            data = elements[i].getAttribute('data').split(','),
            containerRatio = container[0]/container[1],
            linear = elements[i].getAttribute('linear') ? false : true,
            method = (containerRatio <= ratio),
            background = [0,0,0,0], local = [], delta, k,
            from, to, factor, diff,
            bi = method ? 0 : 1;

        background[2] = method ? container[1]*ratio : container[0];
        background[3] = method ? container[1] : container[0] / ratio;

        for (k=0; k<data.length; k++)
            local.push(k % 2 ? data[k] * background[3] : data[k] * background[2])

        if (local[3]-local[1] <= container[1] && local[2]-local[0] <= container[0]) {
            delta = container[bi]-local[bi+2]-local[bi];
            background[bi] = delta/2;
        } else {

            if (linear) {
                delta = container[bi] - local[bi+2]-local[bi];
                from =  delta/2;
                to = (container[bi]/2)-local[bi+4];
                factor = 1 - (container[bi] / (local[bi+2]-local[bi]));
                diff =(to-from) * factor * 2;

                if (((local[bi+4]-local[bi])/(local[bi+2]-local[bi]) > 0.5) && (diff<to-from))
                    diff = to-from;
                else if (diff>to-from) diff = to-from;

                background[bi] = from + diff;
            } else
                background[bi] = (container[bi]/2)-local[bi+4];
        }

        if (background[bi]+background[bi+2] < container[bi])
            background[bi] += container[bi] - background[bi]+background[bi+2];
        else if (background[bi] > 0) background[bi] = 0;

        elements[i].style.backgroundPosition = background[0]+'px '+background[1]+'px';
        elements[i].style.backgroundSize = background[2]+'px '+background[3]+'px';

    }

}

(function(){

    window.onload = function(event) {
        riuRefresh();
    };

    window.onresize = function(event) {
        riuRefresh()
    };

})();