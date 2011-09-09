
        horadric_cache = new Array();
        horadric_show = function(obj)
        {
            $('#item-dialog .error, #item-dialog .item-info').hide();
            $('#item-dialog .loading').show();
            $('#item-dialog').show();
            url = $(obj).attr('href');
            if (horadric_cache[url])
            {
                horadric_parse(horadric_cache[url]);
            } else {
                horadric_fetch(url);
            }
        }
        horadric_hide = function(obj)
        {
            $('#item-dialog').hide();
        }
        horadric_parse = function(data)
        {
            if (data == -1)
            {
                $('#item-dialog .item-info, #item-dialog .loading').hide();
                $('#item-dialog .error').html('Item Not Found').show();
                return false;
            }

            $('#item-dialog .item-sprite').html(data.sprite == '' ? '' : '<img src="http://horadric.info/assets/items/' + data.sprite + '.png" alt="' + data.name + '" />');
            $('#item-dialog .item-name').html(data.name);
            $('#item-dialog .item-name').removeClass('quality-junk quality-normal quality-magic quality-rare quality-legendary quality-artifact quality-runestone');
            $('#item-dialog .item-name').addClass('quality-' + data.quality);

            $('#item-dialog .item-type var').html(data.type_string);
            $('#item-dialog .item-effects ul').html('');
            if (data.type == 'weapon')
            {
                var dps = ((Number(data.damage_min) + Number(data.damage_max)) / 2 * Number(data.speed)).toFixed(1);
                $('#item-dialog .item-banner var').html(dps);
                $('#item-dialog .item-banner label').html('DPS');
                $('#item-dialog .item-effects ul').append('<li>Attacks Per Second: ' + data.speed + '</li>');
                $('#item-dialog .item-effects ul').append('<li>Damage: ' + data.damage_min + '-' + data.damage_max + '</li>');

                if (dps == 0) { $('#item-dialog .item-banner').hide(); } else { $('#item-dialog .item-banner').show(); }

            } else if (data.type == 'armor') {

                $('#item-dialog .item-banner var').html(data.armor);
                $('#item-dialog .item-banner label').html('Armor');
                if (data.armor == 0) { $('#item-dialog .item-banner').hide(); } else { $('#item-dialog .item-banner').show(); }
            }

            for (i in data.effects)
            {
                effect = data.effects[i];
                $('#item-dialog .item-effects ul').append('<li class="quality-magic">' + effect + '</li>');
            }
            $('#item-dialog .item-value var').html(data.gold);

            $('#item-dialog .loading, #item-dialog .error').hide();
            $('#item-dialog .item-info').show();
        }
        horadric_fetch = function(raw_url)
        {
            $('#item-dialog .item-info, #item-dialog .error').hide();
            // if there's a query string, remove it
            var url = raw_url;
            if (url.search(/\?/) != -1)
            {
                url = url.replace(/\?.+$/, '');
            }
            $.getJSON(url + '?json', function(data){
                horadric_cache[url] = data;
                horadric_parse(data);
            }).error(function(){
                horadric_cache[url] = -1;
                horadric_parse(-1);
            });
        }
        $(document).ready(function(){
            $('head').append('<link rel="stylesheet" type="text/css" href="http://horadric.info/powered/powered.css" />');

            // destroy any existing #item-dialogs dialogs
            $('body').append('<div class="tooltip" id="item-dialog" />');
            $('#item-dialog').hide().wrapInner('<div class="border middle-left"><div class="border middle-right"><div class="corner bottom-left"><div class="corner top-left"><div class="corner top-right"><div class="corner bottom-right"><div class="error"></div><div class="loading">Loading...</div><div class="item-info"></div></div></div></div></div></div></div>');
            i = $('#item-dialog .item-info');
            // top block for icon/name/class
            i.append('<div class="item-basics"><div class="item-sprite"></div><div class="item-name quality-magic"></div><div class="item-type">[<var></var>]</div></div>');
            i.append('<div class="item-banner"><var></var><label></label></div>'); // red banner with important information in it?
            i.append('<div class="item-effects"><ul></ul></div>'); // list of effects 
            i.append('<div class="item-value"><label>Sell Value:</label><var>0</var></div>'); // gold value
            $(document).mousemove(function(e){
                $('.tooltip:visible').css({
                    left : e.pageX + 20,
                    top : e.pageY
                });
                $('.tooltip:not(:visible)').css({
                    left: 0,
                    top: 0
                });
                d = $('#item-dialog');
                i = d.children('.item-basics');
            });

            // if this is a link to horadric.info, trigger the ability to fetch info
            $('a').each(function(){
                var url = $(this).attr('href');
                // asking for an item, npc, etc.
                if (document.location.host.search(/^(www\.)?horadric\.info$/) != -1
                    && url.search(/^\/item\//) != -1
                    || url.search(/^http\:\/\/(www\.)?horadric\.info\/item\//) != -1)
                {
                    $(this).hover(function(){ horadric_show(this) }, function(){ horadric_hide(this) });
                }
            });
        });
