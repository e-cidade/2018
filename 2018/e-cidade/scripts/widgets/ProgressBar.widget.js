(function (exports) {

  var ProgressBar = function (bar, logs) {

    var oPercentual = document.createElement('span');
    oPercentual.setAttribute('id','log-percentual');
    oPercentual.textContent = "0%";
    logMessage('Progresso: ', oPercentual);

    function updateProgress(iValue) {

      var nPercentual = new Number(iValue * 100 / bar.max);
      bar.value = iValue;
      oPercentual.textContent = nPercentual.toFixed(2) + "%";

      if (nPercentual == 100) {
        oPercentual.id = '';
      }
    }

    function logMessage(sMessage, oNode) {

      var log = document.createElement('p');
      log.classList.add('item-log');
      log.textContent = '-> ' + sMessage;

      if (oNode) {
        log.appendChild(oNode);
      }

      logs.appendChild(log);
    }

    function getBar() {
      return bar;
    }

    return {
      updateProgress: updateProgress,
      logMessage: logMessage,
      getBar: getBar
    };
  };

  exports.ProgressBar = ProgressBar;
})(this);
