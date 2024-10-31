<?php
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
?>

<div class="button riuResponsive">Enable responsive</div>
<div class="button riuRetina" style="display: none">Enable retina</div>
<span class="riuInfo">
	Drag and resize yellow box to fit area of interest and position the red dot on the point of interest.
</span>
<div style="clear:both"></div>

<div class="riu" style="display: none">
	<div class="riuAof"><div class="riuPof"></div></div>
</div>

<script>
	jQuery().ready((function ($) { return function() {

		var riu, riuMeta = $("input[name*='riuMeta']"), image, imagePosition, width, height;

		function resetMeta(enabled) {
			var options = {
				enabled: enabled,
				type: 'default',
				retina: false,
				data: {
					aof: [
						0.1,
						0.1,
						0.2,
						0.2
					],
					pof: [0.15, 0.15]
				}
			};

			riuMeta.val(JSON.stringify(options));
			riu = options;
		}

		function riuInit() {
			if (!riuMeta.val())	{
				resetMeta(false);
			} else {
				var val = riuMeta.val();

				while (val.indexOf('\\') > -1)
					val = val.replace('\\','/');

				riu = JSON.parse(val);
			}
		}

		function riuRefreshInterface() {

			image = $('.wp_attachment_image img');
			imagePosition = image.position();
			width = parseInt(image.width());
			height = parseInt(image.height());

			if (riu.enabled) {
				$(".riuAof").show();
				$(".riuPof").show();
				$(".riuResponsive").text("Disable responsive");
				$(".riuInfo").show();
				$(".riuRetina").show();
			} else {
				$(".riuAof").hide();
				$(".riuPof").hide();
				$(".riuResponsive").text("Enable responsive");
				$(".riuInfo").hide();
				$(".riuRetina").hide();
				resetMeta(false);
			}

			if (riu.retina)
				$(".riuRetina").text("Disable retina");
			else
				$(".riuRetina").text("Enable retina");

			var left = width*riu.data.aof[0];
			var top = height*riu.data.aof[1];

			$('.riuAof').css('left',left+'px');
			$('.riuAof').css('top',top+'px');

			$('.riuAof').css('width',width*riu.data.aof[2]-left+'px');
			$('.riuAof').css('height',height*riu.data.aof[3]-top+'px');

			$('.riuPof').css('left',width*riu.data.pof[0]-left-7+'px');
			$('.riuPof').css('top',height*riu.data.pof[1]-top-7+'px');

		}

		$('.riuResponsive').on('click',function(){
			riu.enabled = !riu.enabled;
			if (!riu.enabled) riu.retina = false;

			if (riu.enabled)
				resetMeta(true);

			riuRefreshInterface()
		});

		$('.riuRetina').on('click',function(){
			riu.retina = !riu.retina;
			riuRefreshInterface();
			riuRefresh();
		});

		function riuRefresh() {

			var aofp = $('.riuAof').position();

			var aofx = aofp.left-imagePosition.left;
			var aofy = aofp.top-imagePosition.top+11;

			var pofp = $('.riuPof').position();

			var pofx = aofx+pofp.left;
			var pofy = aofy+pofp.top;

			var options = {
				enabled: riu.enabled,
				type: 'default',
				retina: riu.retina,
				data: {
					aof: [
						aofx / width,
						aofy / height,
						(aofx+parseInt($('.riuAof').css('width'))) / width,
						(aofy+parseInt($('.riuAof').css('height'))) / height
						],
					pof: [(pofx+7) / width, (pofy+7) / height]
				}
			};

			riuMeta.val(JSON.stringify(options));

		}

		riuInit();
		riuRefreshInterface();

		$(".wp_attachment_image img").one("load", function() {

			setTimeout(function(){
			riuRefreshInterface();

				$(".riuAof").draggable({ containment: ".wp_attachment_image img", scroll: false,
					stop: function() {
						riuRefresh()
					}}).resizable({
					stop: function() {
						riuRefresh()
					}});

				$(".riuPof").draggable({ containment: ".riuAof", scroll: false, stop: function() {
					riuRefresh()
				}});

				$('.riu').fadeIn();


			},1000);

		}).each(function() {
			if(this.complete) $(this).load();
		});

		setInterval(function(){
			if ($('.image-editor').css('display')=='block') {
				riu.enabled = false;
				riu.retina = false;

				riuRefreshInterface()
				$('.riuResponsive').fadeOut();
			} else
				$('.riuResponsive').fadeIn();
		},1000);

		$(window).resize(function(e){
			if (e.srcElement instanceof Window)
			riuRefreshInterface();
		});

	};}(jQuery)));
</script>