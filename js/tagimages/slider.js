          var SliderJS = Class.create({
                initialize: function (element, duration, n) {
                        var children, l, dimensions, w = 0, h = 0, wrap, nav;
                        element = $(element);
                        children = element.childElements();
                        l = children.length;
                        if (n == undefined || isNaN(n)) {
                                n = 0
                        }
                        else if (n < 0) {
                                n = l + n;
                                if (n < 0) {
                                        n = 0
                                }
                        }
                        else if (n >= l) {
                                n = 0
                        }
                        children.each(function (child) {
                                dimensions = child.getDimensions();
                                if (dimensions.width > w) {
                                        w = dimensions.width
                                }
                                if (dimensions.height > h) {
                                        h = dimensions.height
                                }
                        });
                        wrap = new Element('div');
                        wrap.setStyle({
                                marginLeft: n * -w + 'px',
                                width: children.length * w + 'px',
                                height: h + 'px'
                        });
                        children.each(function (child) {
                                child.setStyle({
                                        cssFloat: 'left',
                                        width: w + 'px'
                                });
                                wrap.appendChild(child)
                        });
                        element.setStyle({
                                width: w + 'px',
                                overflow: 'hidden'
                        });
                        element.appendChild(wrap);
                        nav = {
                                div: new Element('div',  { 'class': 'navigation-button' }),
                                prev: {
                                        div: new Element('div', { 'class': 'prev'}),
                                        a: new Element('a', { href: '#prev' }).update('\u2190 previous')
                                },
                                next: {
                                        div: new Element('div', { 'class': 'next' }),
                                        a: new Element('a', { href: '#next' }).update('next \u2192')
                                }
                        };
                        nav.prev.a.appendChild(nav.prev.div);
                        nav.next.a.appendChild(nav.next.div);
                        if (n == 0) {
                                nav.prev.div.hide()
                        }
                        if (n == l - 1) {
                                nav.next.div.hide()
                        }
                        nav.div.appendChild(nav.prev.div);
                        nav.div.appendChild(nav.next.div);
                        element.appendChild(nav.div);

                        this.duration = (duration == undefined ? 1.0 : duration);
                        this.wrap = wrap;
                        this.nav = nav;
                        this.w = w; // width of each slide
                        this.h = h; // height of each slide
                        this.l = l; // number of slides in slide show
                        this.n = n; // number of the active slide

                        Event.observe(nav.prev.div, 'click', this.prev.bind(this));
                        Event.observe(nav.next.div, 'click', this.next.bind(this));
                },

                isFirst: function () {
                        return (this.n == 0)
                },

                isLast: function () {
                        return (this.n == this.l - 1)
                },

                slide: function () {
                        new Effect.Morph(this.wrap, {
                                style: 'margin-left: -' + this.n * this.w + 'px;',
                                duration: this.duration
                        })
                },

                prev: function (e) {
                        this.n--;
                        this.slide();
                        this.nav.next.div.show();
                        if (this.isFirst()) {
                                this.nav.prev.div.hide()
                        }
                        e.stop()
                },

                next: function (e) {
                        this.n++;
                        this.slide();
                        this.nav.prev.div.show();
                        if (this.isLast()) {
                                this.nav.next.div.hide()
                        }
                        e.stop()
                }
        });

function setLabelTr(__id){
    if($(__id).checked=='checked'){
        $(__id).checked='';
    }else{
          $(__id).checked='checked';
    }
}

var __store_href__ ;





function ligthBoxImages(__id__){
    __store_href__ = $(__id__).readAttribute('href');
  //  alert(__store_href__);
 
}

function LoadProductImages(url,sku){
        new Ajax.Request(url, {
               method:'post',

               parameters: {'sku':sku},
               onCreate:function(){


               },
               onComplete:function(){

               },
              onSuccess: function(transport) {
                    $('sku_'+sku).update(transport.responseText);
             }

            });
}