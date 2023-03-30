/*
 * Astero WordPress Weather Plugin
 * http://archtheme.com/astero
 *
 * A WordPress plugin using openweathermap.org to display
 * current weather conditions and weather forecasts
 */
;(function ( $, window, document, undefined ) {
        
        var pluginName = "astero",
            settings = {
                current: 'https://api.openweathermap.org/data/2.5/weather?',
                forecast: 'https://api.openweathermap.org/data/2.5/onecall?',
                loading_class: 'astero-loading',
                more_class: 'astero-more',
                close_class: 'astero-close',
                closeform_class: 'astero-closeform',
                search_class: 'astero-search',
                more_placeholder_class: 'astero-more-placeholder',
                temp_class: 'astero-temperature',
                icon_class: 'astero-code',
                unit_class: 'astero-unit',
                condition_class: 'astero-condition',
                location_class: 'astero-location',
                hi_temp_class: 'astero-hi-temp',
                lo_temp_class: 'astero-lo-temp',
                humidity_class: 'astero-humidity-text',
                wind_class: 'astero-wind-text',
                cloudiness_class: 'astero-cloud-text',
                sunrise_class: 'astero-sunrise-text',
                sunset_class: 'astero-sunset-text',
                fc_hi_temp_class: 'astero-fc-hi-temp',
                fc_lo_temp_class: 'astero-fc-lo-temp',
                fc_icon_class: 'astero-fc-icon',
                fc_condition_class: 'astero-fc-condition',
                fc_cloud_class: 'astero-fc-cloud',
                fc_humidity_class: 'astero-fc-humidity',
                iframe_ratio: 16/9,
                type: 'geolocation',
            },
            weather_default = {
                appid: astero_vars.api,
		units: 'metric',
                lang: '',
                lat: '',
                lon: ''
            },
            forecast_default = {
                // cnt: '7' // number of forecast days
            },
            default_imgs = {
                clear_d: 'astero-i-sun',
                clear_n: 'astero-i-night',
                clouds: 'astero-i-clouds',
                rain: 'astero-i-rain',
                drizzle: 'astero-i-rain',
                thunderstorm: 'astero-i-thunderstorm',
                fog: 'astero-i-fog',
                mist: 'astero-i-fog',
                haze: 'astero-i-fog',
                snow: 'astero-i-snow'
            },
            codes = {
                '200d': 'P',
                '201d': 'P',
                '202d': '0',
                '210d': 'P',
                '211d': 'P',
                '212d': '0',
                '221d': 'P',
                '230d': 'P',
                '231d': 'P',
                '232d': '0',
                '200n': '6',
                '201n': '6',
                '202n': '&',
                '210n': '6',
                '211n': '6',
                '212n': '&',
                '221n': '6',
                '230n': '6',
                '231n': '6',
                '232n': '&',
                '300d': 'Q',
                '301d': 'Q',
                '302d': 'Q',
                '310d': 'Q',
                '311d': 'Q',
                '312d': 'Q',
                '313d': 'Q',
                '314d': 'Q',
                '321d': 'Q',
                '300n': '7',
                '301n': '7',
                '302n': '7',
                '310n': '7',
                '311n': '7',
                '312n': '7',
                '313n': '7',
                '314n': '7',
                '321n': '7',
                '500d': 'R',
                '501d': 'R',
                '502d': 'R',
                '503d': 'R',
                '504d': 'R',
                '511d': 'R',
                '520d': 'R',
                '521d': 'R',
                '522d': 'R ',
                '531d': 'R',
                '500n': '8',
                '501n': '8',
                '502n': '8',
                '503n': '8',
                '504n': '8',
                '511n': '8',
                '520n': '8',
                '521n': '8',
                '522n': '8',
                '531n': '8',
                '600d': 'U',
                '601d': 'U',
                '602d': 'W',
                '611d': 'X',
                '615d': 'X',
                '616d': 'X',
                '620d': 'X',
                '621d': 'W',
                '622d': 'W',
                '600n': '"',
                '601n': '"',
                '602n': '#',
                '611n': '$',
                '615n': '$',
                '616n': '$',
                '620n': '$',
                '621n': '#',
                '622n': '#',
                '701d': 'J',
                '711d': 'J',
                '721d': 'J',
                '731d': 'M',
                '741d': 'M',
                '751d': 'M',
                '761d': 'M',
                '762d': 'M',
                '771d': 'M',
                '781d': 'M',
                '701n': 'K',
                '711n': 'K',
                '721n': 'K',
                '731n': 'M',
                '741n': 'M',
                '751n': 'M',
                '761n': 'M',
                '762n': 'M',
                '771n': 'M',
                '781n': 'M',
                '800d': 'B',
                '801d': 'N',
                '802d': 'N',
                '803d': 'Y',
                '804d': 'Y',
                '800n': '2',
                '801n': '4',
                '802n': '5',
                '803n': '%',
                '804n': '%',
                '900': 'M',
                '901': '0',
                '902': 'M',
                '903': 'G',
                '904': 'B',
                '905': 'F',
                '906': 'G',
                '951': 'B',
                '952': 'F',
                '953': 'F',
                '954': 'F',
                '955': 'F',
                '956': 'F',
                '957': 'F',
                '958': 'F',
                '959': 'F',
                '960': 'O',
                '961': '0',
                '962': '0'
            };
            
        // The actual plugin constructor
        function Plugin( element, options, objD ) {
                var that = this;
                
                this.element = element;
                this.$element = $(element);
                this.weather_options = $.extend( {}, weather_default, options, objD );
                this.forecast_options = $.extend( {}, this.weather_options, forecast_default );
                this._settings = $.extend( {}, settings, objD );
                this.codes = codes;
                this._default_imgs = default_imgs;
                this._hasVideo = this.$element.find('video').length > 0;
                this._hasIframe = this.$element.find('.astero-yt').length > 0;
                this._hasImage = this.$element.hasClass('astero-img');
                this.$form = this.$element.find('form');
                this._isFull = this.$element.find('.astero-full').length > 0;
                this.$background = this.$element.find('.astero-background');
                this.$large = this.$element.find('.astero-large');
                this._ownTranslation = false;
                this._startInit = false;
                
                // set own translations
                if( this.weather_options.lang == 'own' ) {
                        this.weather_options.lang == '';
                        this.forecast_options = $.extend( {}, that.weather_options, forecast_default );
                        this._ownTranslation = true;
                }

                // if geolocation, use geolocation
                if ( this.weather_options.hasOwnProperty('q') && (this.weather_options.q).length > 0 && this.weather_options.q == 'geolocation' ) {

                	this.weather_options.q = '';

                        if (navigator.geolocation) {
                                // timeout for if user does not respond
                                var location_timeout = setTimeout(ip_geo_fallback, 5000);
                                
                                navigator.geolocation.getCurrentPosition(update_location, ip_geo_fallback, { enableHighAccuracy: false, timeout: 5000, maximumAge: 0 });
                                
                        } else {
                                ip_geo_fallback();
                        }
                } else if ( this.weather_options.hasOwnProperty('q') && (this.weather_options.q).length > 0 && this.weather_options.q == 'ip' ) {
                	
                	this.weather_options.q = '';
                	ip_geo_fallback();

                } else {

                        this._settings.type = 'location';
                        this._startInit = true;
                }
                                                
                function update_location(position) {
                        clearTimeout(location_timeout);
                        
                        that.weather_options.lat = position.coords.latitude;
                        that.weather_options.lon = position.coords.longitude;

                        that.forecast_options = $.extend( {}, that.weather_options, forecast_default );
                        that.init(that, 'first');
                }
                
                function ip_geo_fallback() {
                        clearTimeout(location_timeout);
                        
                        $.ajax({
                                type: 'post',
                                url : astero_vars.ajaxurl,
                                dataType: 'json',
                                data : { 'action': 'astero_owm_geoip' },
                                error: function(jqXHR, textStatus, errorThrown){
                                        that.error();
                                },
                                success: function(response){

                                        if( response.success ) {
                                                that.weather_options = $.extend( {}, that.weather_options, response.success );
                                        } else {
                                                that.error();
                                        }

                                        that.forecast_options = $.extend( {}, that.weather_options, forecast_default );

                                        that.init(that, 'first');
                                }
                        });
                }
                
                this.$element.find('.' + this._settings.more_class).click(function(){
                        that.enlarge(that);
                });
                
                this.$element.find('.' + this._settings.close_class).click(function(){
                        that.close(that);
                });
                
                this.$element.find('.' + this._settings.search_class).click(function() {
                        that.openform(that);
                })
                
                this.$element.find('.' + this._settings.closeform_class).click(function() {
                        that.closeform(that);
                })
                
                if ( this._hasIframe ) {
                        this.resizeVideo(that);
                        
                        var resizeTimer;
                        
                        $(window).resize(function(){
                                clearTimeout(resizeTimer);
                                resizeTimer = setTimeout(function() {
                                        that.resizeVideo(that);
                                }, 200);
                        });
                }
                
                if ( this._isFull ) {
                        that.setEQ(that);
                        
                        var resizeTimer;
                        
                        $(window).resize(function(){
                                clearTimeout(resizeTimer);
                                resizeTimer = setTimeout(function() {
                                        that.setEQ(that);
                                }, 200);
                        });  
                }
                
                this.$form.submit( function(e) {
                        e.preventDefault();
                        
                        var loc = $('input:text[name="location"]', $(this)),
                            units = $('select[name="units"]', $(this));

                        if ( loc.val().length > 0 ) {
                                
                                var geocoder = new google.maps.Geocoder();
                        
                                if (geocoder) {
                                        geocoder.geocode({ 'address': loc.val() }, function (results, status) {
                                                if (status == "OK" && typeof( results[0].geometry.location.lat() ) != 'undefined' &&
                                                    typeof( results[0].geometry.location.lng() ) != 'undefined' ) {
  
                                                        if( results[0].formatted_address.length > 0) {
                                                                that.weather_options.city = results[0].formatted_address;
                                                        }
                                                        that.weather_options.lat = results[0].geometry.location.lat();
                                                        that.weather_options.lon = results[0].geometry.location.lng();
                                                        if( units.length > 0 ) {
			                                	that.weather_options.units = units.val() == 'imperial' ? 'imperial' : 'metric';
			                                }
                                                        that._settings.type = 'search';
                                                        
                                                        that.loading();
                                                        that._newCall = true;
                                                        that.init(that);
                                                } else {
                                                        that.error();
                                                }
                                        });
                                } else {
                                        that.error();
                                }                        }
                        that.closeform(that);
                });
                
                if( this._startInit ) {
                        this.init(that, 'first');
                }
        }
        
        Plugin.prototype = {
                init: function(that, call) {

                        var storage = (typeof(sessionStorage) == undefined) ?
                                (typeof(localStorage) == undefined) ? {
                                    getItem: function(key){
                                        return this.store[key];
                                    },
                                    setItem: function(key, value){
                                        this.store[key] = value;
                                    },
                                    removeItem: function(key){
                                        delete this.store[key];
                                    },
                                    clear: function(){
                                        for (var key in this.store)
                                        {
                                            if (this.store.hasOwnProperty(key)) delete this.store[key];
                                        }
                                    },
                                    store:{}
                        } : localStorage : sessionStorage;
                        
                        //test localStorage for Safari
                        try  {
                                storage.setItem('test', '1');
                                storage.removeItem('test');
                        } catch (error) {
                                storage = false;
                        }
                        
                        $.ajaxPrefilter(function(options, originalOptions, jqXHR){
                                if (!storage || !options.localCache) return;
                             
                                var hourstl = options.cacheTTL || 5;
                                 
                                var cacheKey = options.cacheKey ||
                                options.url.replace( /jQuery.*/,'' ) + options.type + options.data;            

                                // if there's a TTL that's expired, flush this item
                                var ttl = storage.getItem(cacheKey + 'cachettl');

                                if ( ttl && ttl < +new Date() ){
                                        storage.removeItem( cacheKey );
                                        storage.removeItem( cacheKey + 'cachettl' );
                                        ttl = 'expired';
                                }
                                 
                                var value = storage.getItem( cacheKey );

                                if ( !value ){
                                        
                                        // If it not in the cache, we store the data, add success callback - normal callback will proceed
                                        options.success = function( data ) {
                                                var strdata = data;
                                                if ( this.dataType.indexOf( 'json' ) === 0 ) strdata = JSON.stringify( data );
                                                 
                                                // Save the data to storage catching exceptions (possibly QUOTA_EXCEEDED_ERR)
                                                try {
                                                        storage.setItem( cacheKey, strdata );
                                                        
                                                } catch (e) {
                                                        // Remove any incomplete data that may have been saved before the exception was caught
                                                        storage.removeItem( cacheKey );
                                                        storage.removeItem( cacheKey + 'cachettl' );
                                                        console.log('Cache Error:'+e, cacheKey, strdata );
                                                }
                                        };
                                         
                                        // store timestamp
                                        if ( ! ttl || ttl === 'expired' ) {
                                                storage.setItem( cacheKey + 'cachettl', +new Date() + 1000 * 60 * 60 * hourstl );
                                        }
                                }
                        });
                        
                        $.ajaxTransport("json", function(options){
                                if (options.localCache) {
                                        var cacheKey = options.cacheKey || options.url.replace(/jQuery.*/, '') + options.type + options.data,
                                            value;

                                        //test localStorage for Safari
                                        try  {
                                                value = storage.getItem(cacheKey);
                                        } catch (error) {
                                                value = false;
                                        }
                                            
                                        if (value){
                                                // In the cache? Get it, parse it to json, call the completeCallback with the fetched value.
                                                if (options.dataType.indexOf( 'json' ) === 0) value = JSON.parse(value);
                                                return {
                                                        send: function(headers, completeCallback) {
                                                                completeCallback(200, 'success', {json:value})
                                                        },
                                                        abort: function() {
                                                                console.log("Aborted ajax transport for json cache.");
                                                        }
                                                };
                                        }
                                }
                        });

                        $.when(
                                $.ajax({
                                        url: this._settings.current + $.param(this.weather_options),
                                        type: 'GET',
                                        dataType: that._msieversion() == 8 || that._msieversion() == 9 ? 'jsonp' : 'json',
                                        crossDomain: true,
                                        localCache   : true,        // required to use
                                        cacheTTL     : 1,      
                                        error: function(){
                                                that.error();
                                        }
                                }),
                                $.ajax({
                                        url: this._settings.forecast + $.param(this.forecast_options),
                                        type: 'GET',
                                        dataType: that._msieversion() == 8 || that._msieversion() == 9 ? 'jsonp' : 'json',
                                        localCache   : true,        // required to use
                                        cacheTTL     : 1,
                                        crossDomain: true,
                                        error: function(){
                                                that.error();
                                        }
                                })
                        ).done(
                                function(current, forecast){
                                         if ( that._newCall != true || call != 'first' ) {
                                                if ( current[0].cod == '200' && forecast[1] == 'success' ) {
                                                        that.success(current[0], forecast[0], function() {
                                                                if ( that._hasIframe ) {
                                                                        that.resizeVideo(that);       
                                                                }
                                                        }, call);
                                                } else {
                                                        that.error();
                                                }
                                        }
                                        
                        }).fail(
                                function () {
                                        if ( that._newCall != true || call != 'first' ) {
                                                that.error();
                                        }
                        });
                        
                },
                success: function(current, forecast, callback, call) {
                        var that = this,
                            degree = this.weather_options['units'] == 'imperial' ? 'f' : 'c';

                        this._loadCurrent({
                                temp_class: Math.round( current.main.temp ),
                                icon_class: this._getCode(current.weather[0].id, current.weather[0].icon),
                                condition_class: current.weather[0].description,
                                location_class: function() {
                                        if( call == 'first' && that._settings.type == 'location' && typeof( that._settings.city_name ) != 'undefined' ) {
                                                return that._settings.city_name;
                                        } else if ( current.sys.hasOwnProperty('country') && (current.sys.country).length > 0 && (current.name).length > 0 ) {
                                                return current.name+', '+ current.sys.country;
                                        } else if ( (current.name).length > 0 ) {
                                                return current.name;
                                        } else if ( that.weather_options.hasOwnProperty('city') && that.weather_options.city != '' ) {
                                                return that.weather_options.city;
                                        }
                                        return '';
                                },
                                hi_temp_class: Math.round((forecast.daily[0].temp.max + 0.00001)) * 100 / 100,
                                lo_temp_class: Math.round((forecast.daily[0].temp.min + 0.00001)) * 100 / 100,
                                humidity_class: Math.round(current.main.humidity),
                                wind_class: this._getWind(current.wind.deg) + ' ' + Math.round((current.wind.speed + 0.00001) * 100) / 100,
                                cloudiness_class: Math.round(current.clouds.all),
                                sunrise_class: this._getTime(current.sys.sunrise),
                                sunset_class: this._getTime(current.sys.sunset),
                                unit_class: this.weather_options['unit_' + degree]
                        });

                        this._loadForecast(forecast.daily, {
                                fc_hi_temp_class: 'temp.max',
                                fc_lo_temp_class: 'temp.min',
                                fc_condition_class: 'weather.0.description',
                                fc_cloud_class: 'clouds',
                                fc_humidity_class: 'humidity'
                        });

                        this.$element.find('.' + this._settings.loading_class + ', .' + this._settings.more_placeholder_class).hide();
                        this.$element.find('.' + this._settings.more_class).removeClass('hide');
                        this.$element.find('.' + this._settings.icon_class).addClass('asterofont');
                        if ( this._hasImage ){
                                this.$background.removeClass (function (index, css) {
                                        return (css.match (/(^|\s)astero-i-\S+/g) || []).join(' ');
                                }).addClass(this._getImage(current.weather[0].main, current.weather[0].icon));
                        }
                        
                        if (typeof callback == 'function') { // make sure the callback is a function
                                callback.call(this); // brings the scope to the callback
                        }
                        
                },
                error: function() {
                        this._loadCurrent({
                                temp_class: '',
                                icon_class: astero_vars.na,
                                condition_class: '___',
                                location_class: '______',
                                hi_temp_class: '___',
                                lo_temp_class: '___',
                                humidity_class: '',
                                wind_class: '',
                                cloudiness_class: '',
                                sunrise_class: '',
                                sunset_class: ''
                        });
                        this.$element.find('.' + this._settings.loading_class).hide();
                        this.$element.find('.' + this._settings.more_placeholder_class).show();
                        this.$element.find('.' + this._settings.more_class).addClass('hide');
                        this.$element.find('.' + this._settings.icon_class).removeClass('asterofont');
                },
                loading: function() {
                        this._loadCurrent({
                                temp_class: '',
                                icon_class: '',
                                condition_class: '___',
                                location_class: '______',
                                hi_temp_class: '___',
                                lo_temp_class: '___',
                                humidity_class: '',
                                wind_class: '',
                                cloudiness_class: '',
                                sunrise_class: '',
                                sunset_class: '',
                                unit_class: '&deg;'
                        });
                        
                        var fc_classes = {
                                fc_hi_temp_class: '',
                                fc_lo_temp_class: '',
                                fc_condition_class: '',
                                fc_cloud_class: '',
                                fc_humidity_class: '',
                        }
                        for ( var i = 0; i < this.forecast_options.cnt; i++ ) {
                                for ( cl in fc_classes ) {
                                       this.$element.find('.' + this._settings[cl] + i).html(fc_classes[cl]); 
                                }
                        }
                        
                        this.$element.find('.' + this._settings.loading_class).show();
                        this.$element.find('.' + this._settings.more_placeholder_class).show();
                        this.$element.find('.' + this._settings.more_class).addClass('hide');
                        this.$element.find('.' + this._settings.icon_class).removeClass('asterofont');
                },
                enlarge: function(that){
                        that.$element.addClass('open');
                        that.$element.parents().css('z-index', '999');
                        
                        if ( that._hasIframe ) {
                                that.resizeVideo(that);       
                        }
                        that._disableScroll();
                        
                },
                close: function(that) {
                        that.$element.removeClass('open');
                        that.$element.parents().css('z-index', '');
                        
                        if ( that._hasIframe ) {
                                that.resizeVideo(that);       
                        }
                        that._enableScroll();
                },
                openform: function(that) {
                        that.$element.addClass('astero-openform');
                },
                closeform: function(that) {
                        that.$element.removeClass('astero-openform');
                },
                resizeVideo: function (that) {
                        
                        // reset video container first
                        that.$background.css('height','100%');
                        
                        var iframe = that.$element.find('.astero-yt'),
                            width = that.$element.outerWidth(),
                            ratio = parseFloat(that._settings.iframe_ratio);
                            
                        if ( that.$element.hasClass('open') ) {
                                width = that.$large.outerWidth();
                                height = that.$large.outerHeight();
                                
                                 that.$background.css('height',height);

                        } else {
                                var height = that.$element.outerHeight();
                        }

                        if ( width / height <= ratio ) {
                                iframe.css({height: height, width: height * ratio});
                        } else {
                                iframe.css({width: width, height: width / ratio});
                        }
                },
                setEQ: function(that) {
                        var width = that.$element.outerWidth() / parseFloat(that.$element.css('font-size'));
                        that.$large.removeClass('astero-eq-large astero-eq-small astero-eq-medium astero-eq-xsmall');
                        
                        if ( width >= 59.077 ) {
                                that.$large.addClass('astero-eq-large');
                        } else if ( width < 59.077 && width >= 34.615 ) {
                                that.$large.addClass('astero-eq-medium');
                        } else if ( width < 34.615 && width >= 15.385 ) {
                                that.$large.addClass('astero-eq-small');
                        } else if ( width < 15.385 ) {
                                that.$large.addClass('astero-eq-xsmall');
                        }
                },
                _disableScroll: function() {
                        $('html').addClass('astero-open');   
                        if ($(document).height() > $(window).height()) {
                                $('html').css('top',-$( window ).scrollTop()).addClass('astero-noscroll');         
                        }
                },
                _enableScroll: function() {
                        var scrollTop = parseInt($('html').css('top'));
                        $('html').removeClass('astero-noscroll astero-open');
                        $('html,body').scrollTop(-scrollTop);
                },
                _getCode: function(cod, icon) {
                        
                        if (icon.indexOf('n') >= 0) {
                                cod += 'n';
                        }else if (icon.indexOf('d') >= 0) {
                                cod += 'd';
                        }
                        
                        return this.codes[cod];
                },
                _getImage: function(main, icon) {
                        var weather = main.toLowerCase();
                        
                        if ( weather == 'clear' ) {
                                if (icon.indexOf('n') >= 0) {
                                        weather += '_n';
                                }else if (icon.indexOf('d') >= 0) {
                                        weather += '_d';
                                }
                        }
                        
                        if ( this._default_imgs[weather] ) {
                                return this._default_imgs[weather];
                        }
                        
                        return '';
                },
                _loadCurrent: function( data ) {
                        for (var el in data) {
                                this.$element.find('.' + this._settings[el]).html(data[el]);
                        }
                },
                _loadForecast: function( forecasts, data ) {
                        var k = 0;

                        for ( var i = 1; i < 7; i++ ) {
                                for (var el in data) {
                                        var paths = data[el].split('.'),
                                            prop = forecasts[i];

                                        for (var j = 0; j < paths.length; ++j) {
                                                prop = prop[paths[j]];
                                        }
                                        
                                        if ( typeof prop == 'number' ) {
                                                prop = Math.round(prop + 0.00001) * 100 / 100;
                                        }
                                        
                                        if( typeof prop == 'string' && this._ownTranslation ) {
                                                prop = astero_vars[prop];
                                        }

                                        this.$element.find('.' + this._settings[el] + k).html(prop);
                                }
                                
                                //load icons
                                this.$element.find('.' + this._settings.fc_icon_class + k).html(this._getCode(forecasts[i].weather[0].id, forecasts[i].weather[0].icon));
                                k++;
                        }
                        
                },
                _getWind: function(degrees) {
                        var wind = [astero_vars.n, astero_vars.nne, astero_vars.ne, astero_vars.ene, astero_vars.e, astero_vars.ese, astero_vars.se, astero_vars.sse,
                                    astero_vars.s, astero_vars.ssw, astero_vars.sw, astero_vars.wsw, astero_vars.w, astero_vars.wnw, astero_vars.nw, astero_vars.nnw];
                        return wind[ parseInt((degrees/22.5)+.5) % 16 ];
                },
                _getTime: function(unix) {
                        var d = new Date(unix*1000);
                        
                        //d.setTime( d.getTime() + d.getTimezoneOffset()*60000 ); //get time without local timezone
                        
                        var h = d.getHours(),
                            m = ('0' + d.getMinutes()).slice(-2),
                            ampm = astero_vars.am;
                            
                        if (h > 12) {
                                h = h - 12;
                                ampm = astero_vars.pm;
                        } else if (h == 0) {
                                h = 12;
                        }
                        
                        return h + ':' + m + ampm;
                },
                _msieversion: function() {

                        var ua = window.navigator.userAgent,
                            msie = ua.indexOf("MSIE ");
                
                        if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) {      // If Internet Explorer, return version number
                                return (parseInt(ua.substring(msie + 5, ua.indexOf(".", msie))));
                        }
                        return false;
                }
        };
        
        $.fn[pluginName] = function ( options ) {

                return this.each(function () {
                        if (!$.data(this, "plugin_" + pluginName)) {
                                var objD = $(this).data(pluginName);
                                
                                $.data(this, "plugin_" + pluginName,
                                new Plugin( this, options, objD ));
                        }
                });
        };

	$(document).ready(function() {
		$('.astero-owm').astero();
	});
        
})( jQuery, window, document );

var playerlist = document.querySelectorAll(".astero-yt");

if( playerlist.length > 0 ) {
        var tag = document.createElement('script');

        tag.src = "//www.youtube.com/iframe_api";
        var firstScriptTag = document.getElementsByTagName('script')[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
}

function onYouTubeIframeAPIReady() {

        for(var i = 0; i < playerlist.length; i++) {
                var curplayer = createPlayer(playerlist[i].getAttribute('id'), playerlist[i].getAttribute('data-videoid'));
        }   
}

function createPlayer(id, videoid) {
        return new YT.Player(id, {
                playerVars: {
                        autoplay: 1,
                        showinfo: 0,
                        controls: 0,
                        modestbranding: 1,
                        rel: 0,
                        loop: 1,
                        div_load_policy: 3,
                        playlist: videoid,
                        wmode: 'transparent',
                        origin: document.location.origin
                },
                allowfullscreen: 0,
                videoId: videoid,
                events: {
                    'onReady': onPlayerReady
                }
        });
}

function onPlayerReady(event) {
        event.target.mute();
        event.target.playVideo();
}