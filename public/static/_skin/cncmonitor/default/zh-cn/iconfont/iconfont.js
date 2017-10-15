;(function(window) {

  var svgSprite = '<svg>' +
    '' +
    '<symbol id="icon-renwu" viewBox="0 0 1024 1024">' +
    '' +
    '<path d="M859.682607 176.979784l-93.720521 0 0 153.724127-509.657652 0L256.304434 176.979784 164.295903 176.979784c-19.22383 0-34.808784 15.58393-34.808784 34.808784l0 714.358801c0 19.22383 15.58393 34.808784 34.808784 34.808784l695.387727 0c19.22383 0 34.808784-15.58393 34.808784-34.808784L894.492414 211.788567C894.491391 192.564737 878.907461 176.979784 859.682607 176.979784zM782.415826 475.023854l-301.636898 369.311207c1.376347 4.580319-5.431709 5.266957-10.012028 1.52575l-219.46541-179.713035c-4.580319-3.740184-5.266957-10.549264-1.52575-15.129582l36.612872-44.826951c3.740184-4.580319 10.54824-5.266957 15.128559-1.52575L468.054115 742.492558l257.995959-315.880297c3.740184-6.385431 10.549264-7.071046 15.129582-3.330862l42.541909 34.746362C788.30086 461.768969 790.935873 465.268676 782.415826 475.023854z"  ></path>' +
    '' +
    '<path d="M642.560568 132.182508 642.560568 103.933117c0-28.717042-23.280216-51.997258-51.997258-51.997258L433.162444 51.935859c-28.717042 0-51.997258 23.280216-51.997258 51.997258l0 28.249391-61.132312 0 0 140.192949 382.479111 0 0-140.192949L642.560568 132.182508zM512.118704 220.758869c-31.674397 0-57.352219-25.677822-57.352219-57.352219 0-31.674397 25.677822-57.352219 57.352219-57.352219s57.352219 25.677822 57.352219 57.352219C569.470923 199.720717 543.7931 220.758869 512.118704 220.758869z"  ></path>' +
    '' +
    '</symbol>' +
    '' +
    '<symbol id="icon-jichuang" viewBox="0 0 1202 1024">' +
    '' +
    '<path d="M1007.258791 854.325581V881.116279H851.348837v-26.790698H401.491349V881.116279H245.581395v-26.790698H190.511628V622.508651h881.116279V854.325581h-64.369116z m-596.468093-178.604651H245.581395v18.229582H410.790698V675.72093z m0 53.581396H245.581395v18.229581H410.790698V729.302326z m0 53.950511H245.581395v17.860465H410.790698v-17.860465zM631.069767 675.72093H465.860465v18.229582H631.069767V675.72093z m0 53.581396H465.860465v18.229581H631.069767V729.302326z m0 53.950511H465.860465v17.860465H631.069767v-17.860465zM300.639256 515.012465h183.56986v-35.72093h100.971163s8.561116-27.731349 25.409488-37.090233c15.205209-8.453953 38.852465 1.369302 38.852466 1.369303l9.168372 35.72093h110.151442v-35.72093l64.250046 35.72093h100.971163v35.72093h73.430325v80.384H273.098419s-31.089116-44.008186 27.540837-80.384z m573.594791 53.545675c12.633302 0 22.885209-9.989953 22.885209-22.325582S886.867349 523.906977 874.234047 523.906977c-12.645209 0-22.885209 9.989953-22.88521 22.325581s10.24 22.325581 22.88521 22.325582z m-82.610605 0c12.645209 0 22.885209-9.989953 22.885209-22.325582S804.268651 523.906977 791.623442 523.906977c-12.633302 0-22.885209 9.989953-22.885209 22.325581s10.251907 22.325581 22.885209 22.325582z m-82.598698 0c12.633302 0 22.885209-9.989953 22.885209-22.325582S721.658047 523.906977 709.024744 523.906977c-12.645209 0-22.885209 9.989953-22.885209 22.325581s10.24 22.325581 22.885209 22.325582z m-394.609116 0c7.608558 0 13.776372-6.001116 13.776372-13.395349s-6.167814-13.395349-13.776372-13.395349c-7.596651 0-13.764465 6.001116-13.764465 13.395349s6.167814 13.395349 13.764465 13.395349z m133.215256-267.906977v71.44186h18.229581v116.46214h-73.299349V372.093023H410.790698v-71.44186h36.840186z m458.823442 80.395907l-13.03814-0.107163-22.123163 26.89786-22.277953-27.207441-18.015256-0.107163-22.456558 27.314604-22.516093-27.481302-17.610419-0.047628-22.635163 27.52893-22.563721-27.552744-17.550883 0.023814-22.64707 27.52893-22.444651-27.397953-17.848558 0.083349-22.468465 27.314604-22.123163-27.01693c-8.644465 0.083349-15.12186 0.142884-18.562977 0.190512l-22.063628 26.826418-21.944558-26.790697h-23.813954v-116.10493H374.069581v214.349395c-71.68 0.595349-100.971163 26.790698-100.971162 26.790698L208.848372 345.32614V166.697674h761.856v107.174698H933.983256l-27.52893 107.174698z m-45.80614-116.485954H566.700651v72.180093h293.947535v-72.180093z"  ></path>' +
    '' +
    '</symbol>' +
    '' +
    '</svg>'
  var script = function() {
    var scripts = document.getElementsByTagName('script')
    return scripts[scripts.length - 1]
  }()
  var shouldInjectCss = script.getAttribute("data-injectcss")

  /**
   * document ready
   */
  var ready = function(fn) {
    if (document.addEventListener) {
      if (~["complete", "loaded", "interactive"].indexOf(document.readyState)) {
        setTimeout(fn, 0)
      } else {
        var loadFn = function() {
          document.removeEventListener("DOMContentLoaded", loadFn, false)
          fn()
        }
        document.addEventListener("DOMContentLoaded", loadFn, false)
      }
    } else if (document.attachEvent) {
      IEContentLoaded(window, fn)
    }

    function IEContentLoaded(w, fn) {
      var d = w.document,
        done = false,
        // only fire once
        init = function() {
          if (!done) {
            done = true
            fn()
          }
        }
        // polling for no errors
      var polling = function() {
        try {
          // throws errors until after ondocumentready
          d.documentElement.doScroll('left')
        } catch (e) {
          setTimeout(polling, 50)
          return
        }
        // no errors, fire

        init()
      };

      polling()
        // trying to always fire before onload
      d.onreadystatechange = function() {
        if (d.readyState == 'complete') {
          d.onreadystatechange = null
          init()
        }
      }
    }
  }

  /**
   * Insert el before target
   *
   * @param {Element} el
   * @param {Element} target
   */

  var before = function(el, target) {
    target.parentNode.insertBefore(el, target)
  }

  /**
   * Prepend el to target
   *
   * @param {Element} el
   * @param {Element} target
   */

  var prepend = function(el, target) {
    if (target.firstChild) {
      before(el, target.firstChild)
    } else {
      target.appendChild(el)
    }
  }

  function appendSvg() {
    var div, svg

    div = document.createElement('div')
    div.innerHTML = svgSprite
    svgSprite = null
    svg = div.getElementsByTagName('svg')[0]
    if (svg) {
      svg.setAttribute('aria-hidden', 'true')
      svg.style.position = 'absolute'
      svg.style.width = 0
      svg.style.height = 0
      svg.style.overflow = 'hidden'
      prepend(svg, document.body)
    }
  }

  if (shouldInjectCss && !window.__iconfont__svg__cssinject__) {
    window.__iconfont__svg__cssinject__ = true
    try {
      document.write("<style>.svgfont {display: inline-block;width: 1em;height: 1em;fill: currentColor;vertical-align: -0.1em;font-size:16px;}</style>");
    } catch (e) {
      console && console.log(e)
    }
  }

  ready(appendSvg)


})(window)