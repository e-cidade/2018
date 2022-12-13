
var 

  /**
   * Pontuacao minima
   * @var integer
   */
  threshold = 1,

  /**
   * Maximo de resultados exibidos
   * @var integer
   */
  limit = 20,

  /**
   * tags para destacar
   * @var string
   */
  highlightPre = '<b>',
  highlightPost = '</b>',

  /**
   * Caracteres para ignorar
   * @var Array
   */
  ignore = [], 

  /**
   * Pontuacao por palavras iguais
   * @var integer
   */
  scoreMatch = 6, 

  /**
   * Pontuacao pela distancia entre matchs
   * @var integer
   */
  scoreDistance = 5, 

  /**
   * Pontuacao por aproximacao
   * @var integer
   */
  scoreThreshold = 4,

  /**
   * Lista com todos os menus
   * @var Array
   */
  menus = [],

  /**
   * timout para executar pesquisa
   * @var integer
   */
  timer = 0,

  /**
   * Url para buscar menus
   * @var integer
   */
  menusUrl 
; 

this.addEventListener('message', function(event) {

  var data = event.data;

  if (timer) {
    clearTimeout(timer);
  }

  if (data == 'abort' || !data.term) {
    return false;
  }

  if (data.url != menusUrl) {
    menusUrl = data.url;
    var json = request(data.url);
    if (json) {
      menus = JSON.parse(json);
    }
  }

  timer = setTimeout(function() {
    search(data.term);
  }, 100);

}, false);

function sort(key, desc) {
  return function(left, right) {
    return desc ? right[key] - left[key] : left[key] - right[key];
  }
} 

function search(term) {

  var matchs = [];

  for (var index = 0, length = menus.length; index < length; index++) {

    var menu = menus[index];
    var haystack = menu.breadcrumb; 
    var needle = term;

    var match = {
      score : 0,
      tokens : [],
      haystack : haystack,
      highlight : haystack,
      context : menu,
    };

    haystack = removeAccents(haystack.toLowerCase()); 
    needle = removeAccents(needle.toLowerCase());

    var haystackWords = haystack.split(' ');
    var needleWords = needle.split(' ');

    var totalhaystackWords = haystackWords.length;
    var haystackOffset = haystack.length - 1;
    var lastFoundWordIndex = 0;

    for (var haystackWordIndex = totalhaystackWords - 1; haystackWordIndex >= 0; haystackWordIndex--) {

      var haystackWord = haystackWords[haystackWordIndex];
      var haystackWordLength = haystackWord.length;
      haystackOffset -= haystackWordLength;

      /**
       * adiciona o tamanho do espaco ' '
       */
      haystackOffset -= 1;
      if (haystackWordIndex == totalhaystackWords - 1) {
        haystackOffset += 2;
      } 

      /**
       * Ignore caracteres ou espacos duplicados
       */
      if (in_array(haystackWord, ignore) || haystackWord == '') {

        /**
         * Mantem distancia
         */
        distance = lastFoundWordIndex - haystackWordIndex;
        if (distance == 1) {
          lastFoundWordIndex = haystackWordIndex;
        }

        continue;
      }

      for (var needleWordIndex = needleWords.length - 1; needleWordIndex >= 0; needleWordIndex--) {

        if (!needleWords[needleWordIndex]) {
          continue;
        }

        var needleWord = needleWords[needleWordIndex];
        var found = false;

        if (haystackWord == needleWord) {
          match.score += scoreMatch;
          found = true;
        } else {

          if (haystackWordLength < 4) {
            continue;
          }

          var wordsDistance = levenshtein(haystackWord, needleWord);

          if (wordsDistance > 0 && wordsDistance < 3) {

            match.score += (scoreThreshold/wordsDistance);
            found = true;
          }
        }                

        if (!found) {
          continue;
        }

        match.tokens.push({value: haystackWord, offset: haystackOffset, length: haystackWordLength}); 
        var distance = lastFoundWordIndex - haystackWordIndex;

        if (distance == 1) {
          match.score += scoreDistance;
        }

        lastFoundWordIndex = haystackWordIndex;
        needleWords.splice(needleWordIndex, 1);
        break;
      }

    }

    if (match.score < threshold) {
      continue;
    }

    matchs.push(match);
  }

  matchs.sort(sort('score', true));                    
  matchs = matchs.slice(0, limit);

  highlighting(matchs);
  postMessage(matchs);
}

function highlighting(matchs) {

  for (var index = 0, length = matchs.length; index < length; index++) {

    var match = matchs[index];
    var tokens = match.tokens.sort(sort('offset')); 
    var haystack = match.haystack;
    var currentIndexToken = 0;
    var highlight = '';

    var total = haystack.length;

    for (var indexToken = 0, lengthToken = tokens.length; indexToken < lengthToken; indexToken++) {

      var token = tokens[indexToken];

      highlight += haystack.substr(currentIndexToken, token.offset - currentIndexToken);
      highlight += highlightPre;
      highlight += haystack.substr(token.offset, token.length);
      highlight += highlightPost;

      currentIndexToken = token.offset + token.length;
    }

    highlight += haystack.substr(currentIndexToken, total - currentIndexToken);

    match.highlight = highlight;
  } 

  return matchs;
}

function removeAccents(string) {

  var dict = {
    'á' : 'a', 'à' : 'a', 'ã' : 'a', 'â' : 'a', 'é' : 'e', 'ê' : 'e', 'í' : 'i', 'ó' : 'o', 'ô' : 'o',
    'õ' : 'o', 'ú' : 'u', 'ü' : 'u', 'ç' : 'c', 'Á' : 'A', 'À' : 'A', 'Ã' : 'A', 'Â' : 'A', 'É' : 'E',
    'Ê' : 'E', 'Í' : 'I', 'Ó' : 'O', 'Ô' : 'O', 'Õ' : 'O', 'Ú' : 'U', 'Ü' : 'U', 'Ç' : 'C'
  }

  return string.replace(/[^\w ]/g, function(char) {
    return dict[char] || char;
  });
}

function in_array(needle, haystack) {
  for (var index = 0, length = haystack.length; index < length; index++) {
    if (haystack[index] == needle) {
      return true;
    }
  }
  return false;
}

function levenshtein(s1, s2, cost_ins, cost_rep, cost_del) {
  //       discuss at: http://phpjs.org/functions/levenshtein/
  //      original by: Carlos R. L. Rodrigues (http://www.jsfromhell.com)
  //      bugfixed by: Onno Marsman
  //       revised by: Andrea Giammarchi (http://webreflection.blogspot.com)
  // reimplemented by: Brett Zamir (http://brett-zamir.me)
  // reimplemented by: Alexander M Beedie
  // reimplemented by: Rafa¿ Kukawski
  //        example 1: levenshtein('Kevin van Zonneveld', 'Kevin van Sommeveld');
  //        returns 1: 3
  //        example 2: levenshtein("carrrot", "carrots");
  //        returns 2: 2
  //        example 3: levenshtein("carrrot", "carrots", 2, 3, 4);
  //        returns 3: 6
  
  var LEVENSHTEIN_MAX_LENGTH = 255; // PHP limits the function to max 255 character-long strings
  
  cost_ins = cost_ins == null ? 1 : +cost_ins;
  cost_rep = cost_rep == null ? 1 : +cost_rep;
  cost_del = cost_del == null ? 1 : +cost_del;

  if (s1 == s2) {
    return 0;
  }

  var l1 = s1.length;
  var l2 = s2.length;
  
  if (l1 === 0) {
    return l2 * cost_ins;
  }
  if (l2 === 0) {
    return l1 * cost_del;
  }
  
  // Enable the 3 lines below to set the same limits on string length as PHP does
  /*if (l1 > LEVENSHTEIN_MAX_LENGTH || l2 > LEVENSHTEIN_MAX_LENGTH) {
    return -1;
  }*/

  // BEGIN STATIC
  var split = false;
  try {
    split = !('0')[0];
  } catch (e) {
    // Earlier IE may not support access by string index
    split = true;
  }
  // END STATIC
  if (split) {
    s1 = s1.split('');
    s2 = s2.split('');
  }

  var p1 = new Array(l2 + 1);
  var p2 = new Array(l2 + 1);

  var i1, i2, c0, c1, c2, tmp;
  
  for (i2 = 0; i2 <= l2; i2++) {
    p1[i2] = i2 * cost_ins;
  }
  
  for (i1 = 0; i1 < l1 ; i1++) {
    p2[0] = p1[0] + cost_del;
    
    for (i2 = 0; i2 < l2; i2++) {
      c0 = p1[i2] + ((s1[i1] == s2[i2]) ? 0 : cost_rep);
      c1 = p1[i2 + 1] + cost_del;
      
      if (c1 < c0) {
        c0 = c1;
      }
      
      c2 = p2[i2] + cost_ins;
      
      if (c2 < c0) {
        c0 = c2;
      }
      
      p2[i2 + 1] = c0;
    }
    
    tmp = p1;
    p1 = p2;
    p2 = tmp;
  }
  
  c0 = p1[l2];
  
  return c0;
}

function request(url) {

  try {

    var xhr = new XMLHttpRequest();
    xhr.open('GET', url, false);
    xhr.setRequestHeader("Accept","application/json");
    xhr.send(null);
    return xhr.responseText;

  } catch (e) {
    return false;
  }
}
