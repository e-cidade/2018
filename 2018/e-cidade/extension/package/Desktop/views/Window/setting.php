<!DOCTYPE html>
<html>
<head>
  <title>Configurações</title>
  <?php echo $this->document->renderBase(); ?>

  <link rel="stylesheet" type="text/css" href="<?php echo ECIDADE_CURRENT_EXTENSION_REQUEST_PATH; ?>assets/css/Window/form.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo ECIDADE_CURRENT_EXTENSION_REQUEST_PATH; ?>assets/vendors/select2/css/select2.min.css" />
  <link type="text/css" rel="stylesheet" href="<?php echo ECIDADE_CURRENT_EXTENSION_REQUEST_PATH; ?>assets/vendors/alertify/themes/alertify.core.css" />
  <link type="text/css" rel="stylesheet" href="<?php echo ECIDADE_CURRENT_EXTENSION_REQUEST_PATH; ?>assets/vendors/alertify/themes/alertify.bootstrap.css" />

  <style type="text/css">

    .container {
      width: 90%;
      max-width: 500px;
      margin: 0 auto;
    }

    #IFiframe_data_data {
      width: 200px;
      height: 200px;
    }

    #Janiframe_data_data {
      top: 20px;
      left: 100px;
    }

    .chosen-drop {
      display: none !important;
    }

    .chosen-with-drop .chosen-drop {
      display: block !important;
    }

    #dtjs_data {
      bottom: 0;
      margin: auto 0;
      position: absolute;
      right: 0;
      top: -6px;
      width: 40px;
    }

  </style>

</head>
<body>

  <div class="container">

    <form method="POST" action="">

      <div class="input">

        <label for="departamentos">Departamentos</label>

        <select id="departamentos" name="departamentos">
          <?php foreach ($this->departamentos as $departamento) : ?>
            <option
              <?php if ($departamento['coddepto'] == $this->departamento) { echo 'selected'; } ?>
                value="<?php echo $departamento['coddepto']; ?>"><?php echo $departamento['coddepto'] . ' - ' . \DBString::utf8_encode_all($departamento['descrdepto']); ?></option>
          <?php endforeach; ?>
        </select>

      </div>

      <div class="input">

        <label for="exercicios">Exercício</label>

        <select id="exercicios" name="exercicios" class="chosen-select">
          <?php foreach ($this->exercicios as $exercicio) : ?>
            <option
              <?php if ($exercicio == $this->exercicio && !isset($this->dataSistemaAno) || isset($this->dataSistemaAno) && $this->dataSistemaAno == $exercicio) { echo 'selected'; } ?>
                value="<?php echo $exercicio; ?>">
              <?php echo $exercicio; ?>
            </option>
          <?php endforeach; ?>
        </select>

      </div>

      <?php if (!empty($this->dataSistema)) : ?>

        <div class="input">
          <?php require_once(ECIDADE_PATH . 'dbforms/db_funcoes.php'); ?>
          <script type="text/javascript">var CurrentWindow = window.frameElement.CurrentWindow.parent;</script>
          <script type="text/javascript" src="scripts/scripts.js"></script>
          <label for="data">Data do Sistema</label>
          <?php db_inputdata('data', $this->dataSistemaDia, $this->dataSistemaMes, $this->dataSistemaAno, true, 'text', 1, "",  "", "", "parent.changeDate();"); ?>

          <div class="buttons" style="position:relative;top:5px">
            <button type="button" id="data-servidor" class="btn btn-small btn-link" value="<?php echo $this->dataServidor; ?>">
              Usar data servidor(<?php echo $this->dataServidor; ?>)
            </button>
          </div>
        </div>

      <?php endif; ?>

      <div class="input buttons">
        <button id="cancelar" class="btn btn-link" type="button">Cancelar</button>
        <button id="salvar" class="btn btn-primary" type="submit" >Salvar</button>
      </div>

    </form>

  </div>

  <script src="scripts/jquery-2.1.1.min.js"></script>
  <script src="<?php echo ECIDADE_CURRENT_EXTENSION_REQUEST_PATH; ?>assets/vendors/select2/js/select2.min.js"></script>
  <script src="<?php echo ECIDADE_CURRENT_EXTENSION_REQUEST_PATH; ?>assets/vendors/select2/js/i18n/pt-BR.js"></script>
  <script type="text/javascript" src='<?php echo ECIDADE_CURRENT_EXTENSION_REQUEST_PATH; ?>assets/vendors/alertify/alertify.js'></script>

  <script type="text/javascript">

    ;(function($, global) {

      var alert = alertify.alert, CurrentWindow = parent.CurrentWindow || window.frameElement.CurrentWindow;

      $("select").select2({minimumResultsForSearch: 10, language: 'pt-BR'});

      $('#data-servidor').on('click', function(event) {

        $('#data').val($(this).val());
        global.changeDate();
        event.preventDefault();
        return false;
      });

      $('#cancelar').on('click', function() {
        CurrentWindow.close();
      });

      global.changeDate = function() {

        if ($('#data').val() == '') {
          return false;
        }

        var ano = $('#data').val().split('/').pop();

        if (ano != $('#exercicios').val() && $("#exercicios option[value='"+ ano +"']").length > 0) {
          $('#exercicios').val(ano).select2();
        }

        if ($("#exercicios option[value='"+ ano +"']").length == 0) {
          alert('Sem permissão para ano: ' + ano);
          $('#data').val('');
          return false;
        }

      }

      $('#data').on('change', changeDate);

      $('form').on('submit', function(e) {

        e.preventDefault();

        if ($("#data").val() == '') {
          alert('Data do sistema não informada.');
          return false;
        }

        $.ajax({
          url: $(this).attr('action'),
          data: $(this).serialize(),
          type: $(this).attr('method'),
          dataType: 'JSON'
        }).done(function(data) {

          if (!data) {
            return;
          }

          CurrentWindow.parent.refresh();
          CurrentWindow.close();

        }).fail(function(xhr) {

          var data = JSON.parse(xhr.responseText);
          return alert(data.message);
        });

        return false;
      });

    })(jQuery.noConflict(), this);

  </script>

</body>

</html>
