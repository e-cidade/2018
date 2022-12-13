<html>
<head>
<style type="text/css">
* {
  margin: 0;
  padding: 0;
  border: 0px;
}

#content > * {
  background:#ddd;
  color: #545454;
}

#content {
  font-size: 12px;
  font-family: Arial, Helvetica, serif,sans-serif, verdana;
  font-size: 12px;
  color: #000000;
  height: 20px;
  line-height : 20px;
  position: relative;
}

#content .left {
  float: left;
  margin-left: 10px;
  background:#ddd;
  left: 0;
}

#content .left > div {
  float:left;
}

#content .midle {
  float:left;
  margin-left: 10px;
}

#content .right {
  float: right;
  padding-right: 5px;
  margin-right: 0px;
  position: absolute;
  right: 0;
}

.alert {
  background: #ffffcc !important;
}

</style>
</head>
<body>

  <!-- compatibilidae com v2 -->
  <div style="display:none;" id="st"></div>

  <div id="content">

    <div class="left">
      <div class="instituicao">
        <span style="margin-right:20px;"><b>Instituição:</b> <?php echo \DBString::utf8_encode_all($this->instituicao); ?>
      </div>
      <div class="departamento">
        <span><b>Departamento:</b> <?php echo \DBString::utf8_encode_all($this->departamento); ?>
      </div>
    </div>

    <div class="midle"></div>

    <div class="right">
      <span style="margin-right:5px;padding-left:5px;">
        <b>Data:</b> <?php echo $this->data; ?>
      </span>
      <span>
        <b>Exercício:</b>
        <?php echo $this->exercicio; ?>
      </span>
    </div>

  </div>

</body>
<script type="text/javascript">
(function() {

  var Desktop = parent.Desktop, CurrentWindow = parent.CurrentWindow;

  window.addEventListener('click', function(event) {
    CurrentWindow.eventHandler(event);
  });

})();
</script>
