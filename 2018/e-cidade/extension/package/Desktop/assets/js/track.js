
function createXPathFromElement(elm) { 
    var allNodes = document.getElementsByTagName('*'); 
    for (var segs = []; elm && elm.nodeType == 1; elm = elm.parentNode) 
    { 
        if (elm.hasAttribute('id')) { 
                var uniqueIdCount = 0; 
                for (var n=0;n < allNodes.length;n++) { 
                    if (allNodes[n].hasAttribute('id') && allNodes[n].id == elm.id) uniqueIdCount++; 
                    if (uniqueIdCount > 1) break; 
                }; 
                if ( uniqueIdCount == 1) { 
                    segs.unshift('id("' + elm.getAttribute('id') + '")'); 
                    return segs.join('/'); 
                } else { 
                    segs.unshift(elm.localName.toLowerCase() + '[@id="' + elm.getAttribute('id') + '"]'); 
                } 
        } else if (false && elm.hasAttribute('class')) { 
            segs.unshift(elm.localName.toLowerCase() + '[@class="' + elm.getAttribute('class') + '"]'); 
        } else { 
            for (i = 1, sib = elm.previousSibling; sib; sib = sib.previousSibling) { 
                if (sib.localName == elm.localName)  i++; }; 
                segs.unshift(elm.localName.toLowerCase() + '[' + i + ']'); 
        }; 
    }; 
    return segs.length ? '/' + segs.join('/') : null; 
}; 

function getElementByXPath(path) { 
    var evaluator = new XPathEvaluator(); 
    var result = evaluator.evaluate(path, document.documentElement, null,XPathResult.FIRST_ORDERED_NODE_TYPE, null); 
    return  result.singleNodeValue; 
} 

var desktopTrack = [];
var recordTrack = false;
var tracing = false;

(function($) {

  function extend(destination, source) {
    for (var property in source)
      destination[property] = source[property];
    return destination;
  }

  var eventMatchers = {
    'HTMLEvents': /^(?:load|unload|abort|error|select|change|submit|reset|focus|blur|resize|scroll)$/,
    'MouseEvents': /^(?:click|dblclick|mouse(?:down|up|over|move|out))$/
  }
  var defaultOptions = {
    pointerX: 0,
    pointerY: 0,
    button: 0,
    ctrlKey: false,
    altKey: false,
    shiftKey: false,
    metaKey: false,
    bubbles: true,
    cancelable: true
  }


  function simulate(element, eventName) {

    var options = extend(defaultOptions, arguments[2] || {});
    var oEvent, eventType = null;

    for (var name in eventMatchers) {
      if (eventMatchers[name].test(eventName)) { eventType = name; break; }
    }

    if (!eventType)
      throw new SyntaxError('Only HTMLEvents and MouseEvents interfaces are supported');

    if (document.createEvent)
      {
        oEvent = document.createEvent(eventType);
        if (eventType == 'HTMLEvents')
          {
            oEvent.initEvent(eventName, options.bubbles, options.cancelable);
          }
          else
            {
              oEvent.initMouseEvent(eventName, options.bubbles, options.cancelable, document.defaultView,
                                    options.button, options.pointerX, options.pointerY, options.pointerX, options.pointerY,
                                    options.ctrlKey, options.altKey, options.shiftKey, options.metaKey, options.button, element);
            }
            element.dispatchEvent(oEvent);
      }
      else
        {

          options.clientX = options.pointerX;
          options.clientY = options.pointerY;
          var evt = document.createEventObject();
          oEvent = extend(evt, options);
          element.fireEvent('on' + eventName, oEvent);
        }
        return element;
  }

  var click = $('<div />', {id: 'click'}).css({
    width: '10px',
    height : '10px',
    background: 'red',
    position: 'absolute',
    borderRadius: '5px',
    opacity: '0.6',
    top: '0',
    left: '0',
    zIndex: 9000,
  });

  function showTrack() {

    if (tracing || localStorage.getItem('desktopTrack') == null) {
      return;
    }

    var tracks = JSON.parse(localStorage.getItem('desktopTrack'));
    var current = 0;
    var total = tracks.length;

    tracing = true;

    function find() {

      var track = tracks[current];

      function next() {

        current++;

        if (current < total) {
          return find();
        } 

        if (current >= total) {
          tracing = false;
        } 
      }

      function trigger(target, track) {

        var hit = click.clone();

        setTimeout(function() {
          hit.remove();
        }, 500);

        hit.css({top: track.y - 5, left: track.x - 5});

        $('body').append(hit);

        simulate(target, track.type, track.options);
      }

      setTimeout(function() {

        var xpath = track.target;
        var element = getElementByXPath(xpath); 

        if (!element) {
          console.log('!element');
          return find();
        }

        trigger(element, track);

        return next();

      // }, track.timestamp);
      }, 50);
    }

    find();
  }


  $(document).on('keydown', function(event) {

    // f2 - toggle record
    if (event.keyCode == 113) {

      recordTrack = !recordTrack;
      console.log('record click', recordTrack);

      if (!recordTrack) {
        localStorage.setItem('desktopTrack', JSON.stringify(desktopTrack));
      } else {
        desktopTrack = [];
      } 
    }

    // f3
    if (event.keyCode == 114) {

      console.log('trigger click', recordTrack);

      if (recordTrack) {
        return false;
      }

      showTrack();
      event.preventDefault();
      return false;
    }


  });

  var lastEventTime = 0;

  $(document).on('click dblclick', function(event) {

    if (!recordTrack) {
      return;
    }

    var eventTimeStamp = event.timeStamp;

    var track = {
      type: event.type,
      button: event.button,
      x : event.pageX,
      y: event.pageY,
      target : createXPathFromElement(event.target),

      timestamp: (lastEventTime == 0 ? 0 : eventTimeStamp - lastEventTime),

      options : {
        bubbles: event.bubbles,
        button: event.button,
        pointerX : event.pageX,
        pointerY : event.pageY,
        ctrlKey : event.ctrlKey,
        altKey : event.altKey,
        shiftKey : event.shiftKey,
        metaKey : event.metaKey,
      }
    }

    lastEventTime = eventTimeStamp;

    desktopTrack.push(track);
  })

})(jQuery); 
