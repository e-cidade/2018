<style>
  /**
   * Ajustes para os campos texto do retorno das Lookups
   */
  #z01_nome, #ed47_v_nome, #ov02_nome, #rh70_descr {
    width: calc(100% - 85px);
  }
</style>

<fieldset class='separator'>
  
  <legend>Vínculos do Sistema</legend>

  <table class="form-container">
    <tr>
      <td>
        <label>
          <a id="ancoraCGM" href="#" func-arquivo="func_cgm.php" func-objeto="func_nome">CGM:</a>
        </label>
      </td>
      <td>
        <input name="z01_numcgm" id="z01_numcgm" />
        <input name="z01_nome"   id="z01_nome"   />
      </td>
    </tr>
    
    <tr>
      <td>
        <label>
          <a id="ancoraCGE" href="#" func-arquivo="func_buscacge.php" func-objeto="db_iframe_buscacgm">CGE:</a>
        </label>
      </td>
      <td>
        <input name="ed47_i_codigo" id="ed47_i_codigo" />
        <input name="ed47_v_nome"   id="ed47_v_nome"   />
      </td>
    </tr>

    <tr>
      <td>
        <label>
          <a id="ancoraCidadao" href="#" func-arquivo="func_buscacidadao.php" func-objeto="db_iframe_buscacgm">Cidadão:</a>
        </label>
      </td>
      <td>
        <input name="ov02_sequencial" id="ov02_sequencial" />
        <input name="ov02_nome"       id="ov02_nome"       />
      </td>
    </tr>

    <tr>
      <td>
        <label>
          <a id="ancoraOcupacao" href="#" func-arquivo="func_rhcbo.php" func-objeto="db_iframe_rhcbo">Ocupação:</a>
        </label>
      </td>
      <td>
        <input name="rh70_sequencial"  id="rh70_sequencial" />
        <input name="rh70_descr"       id="rh70_descr"      />
      </td>
    </tr>
  </table>

</fieldset>

<fieldset class='separator'>
  
  <legend>Dados Adicionais</legend>

  <table class="form-container">

    <tr>
      <td>
        <label for="escolaridade">Escolaridade:</label>
      </td>
      <td>
        <select id="escolaridade">
          <option value="">Selecione</option>
          <option value="51">Creche</option>
          <option value="52">Pré-escola (exceto CA)</option>
          <option value="53">Classe Alfabetizada - CA</option>
          <option value="54">Ensino Fundamental 1ª a 4ª séries</option>
          <option value="55">Ensino Fundamental 5ª a 8ª séries</option>
          <option value="56">Ensino Fundamental Completo</option>
          <option value="61">Ensino Fundamental Especial</option>
          <option value="58">Ensino Fundamental EJA - séries iniciais (Supletivo 1ª a 4ª)</option>
          <option value="59">Ensino Fundamental EJA - séries iniciais (Supletivo 5ª a 8ª)</option>
          <option value="60">Ensino Médio, Médio 2º Ciclo (Científico, Técnico e etc)</option>
          <option value="57">Ensino Médio Especial</option>
          <option value="62">Ensino Médio EJA (Supletivo)</option>
          <option value="63">Superior, Aperfeiçoamento, Especialização, Mestrado, Doutorado</option>
          <option value="64">Alfabetização para Adultos (Mobral, etc)</option>
          <option value="65">Nenhum</option>
        </select>
      </td>
    </tr>

    <tr>
      <td>
        <label for="z01_c_bolsafamilia">Bolsa Família:</label>
      </td>
      <td>
        <select name="z01_c_bolsafamilia" id="z01_c_bolsafamilia">
          <option value="N">Não</option>
          <option value="S">Sim</option>
        </select>
      </td>

    <tr>
      <td>
        <label for="z01_i_estciv">Estado Civil:</label>
      </td>
      <td>
        <select name="z01_i_estciv" id="z01_i_estciv">
          <option value="1">Solteiro</option>
          <option value="2">Casado</option>
          <option value="3">Viúvo</option>
          <option value="4">Separado </option>
          <option value="5">União C.</option>
          <option value="9">Ignorado</option>
        </select>    
      </td>
    </tr>

    <tr>
      <td>
        <label for="z01_v_micro">Microárea:</label>
      </td>
      <td>
        <select  id="z01_v_micro" name="z01_v_micro">
          <option value="">Selecione...</option>
        </select>
      </td>
    </tr>

    <tr>
      <td>
        <label for="z01_i_familiamicroarea">Família:</label>
      </td>
      <td>
        <select id="z01_i_familiamicroarea" name="z01_i_familiamicroarea">
          <option value="">Selecione...</option>
        </select>    
      </td>
    </tr>

    <tr>
      <td>
        <label for="z01_c_nomeresp">Responsável:</label>
      </td>
      <td>
        <input class="field-size-max" id="z01_c_nomeresp" name="z01_c_nomeresp" />
      </td>
    </tr>

  </table>
</fieldset>
<fieldset class="separator">
  <legend>
    <label for="z01_t_obs">Observações:</label>
  </legend>
  <textarea class="field-size-max" id="z01_t_obs" name="z01_t_obs"></textarea>
</fieldset>

<script>

  var outrosDados = {
    'cgm'          : new DBLookUp($('ancoraCGM'),      $('z01_numcgm'),      $('z01_nome')),
    'cge'          : new DBLookUp($('ancoraCGE'),      $('ed47_i_codigo'),   $('ed47_v_nome')),
    'cidadao'      : new DBLookUp($('ancoraCidadao'),  $('ov02_sequencial'), $('ov02_nome')),
    'ocupacao'     : new DBLookUp($('ancoraOcupacao'), $('rh70_sequencial'), $('rh70_descr')),
    'estado_civil' : $('z01_i_estciv'),
    'microarea'    : $('z01_v_micro'),
    'familia'      : $('z01_i_familiamicroarea'),
    'bolsafamilia' : $('z01_c_bolsafamilia'),
    'responsavel'  : new DBInput($('z01_c_nomeresp')),
    'observacoes'  : $('z01_t_obs'),
    'escolaridade' : $('escolaridade'),
  };


  /**
   * Quando a tela for carregada preencherá os dados na tela
   */
  callbackCarregamento.outrosDados = function(outros, dados_padrao) {

    montarComboMicroArea(null, dados_padrao.microareas);
    
    outrosDados.microarea.addEventListener('change', function(){

      outrosDados.familia.length   = 0;
      outrosDados.familia.add(new Option("Selecione...", ""));

      for (var microarea of dados_padrao.microareas) {
      
        if (microarea.codigo_microarea == this.value) {
          montarComboFamilia(microarea.familias);
        }
      }
      
    });

    if(!outros) {
      return;
    }
    
    $('z01_numcgm').setValue(outros.codigo_cgm);
    $('z01_nome').setValue(outros.label_cgm);
    $('ed47_i_codigo').setValue(outros.codigo_aluno);
    $('ed47_v_nome').setValue(outros.label_aluno);
    $('ov02_sequencial').setValue(outros.codigo_cidadao);
    $('ov02_nome').setValue(outros.label_cidadao);
    $('rh70_sequencial').setValue(outros.codigo_ocupacao);
    $('rh70_descr').setValue(outros.label_ocupacao);

    outrosDados.microarea.setValue(outros.microarea);
    outrosDados.microarea.dispatchEvent(new Event("change"));
    outrosDados.familia.setValue(outros.familia);

    outrosDados.responsavel.setValue(outros.responsavel);
    outrosDados.observacoes.setValue(outros.observacoes);
    outrosDados.estado_civil.setValue(outros.estado_civil);
  };


  /**
   * Seta os atributos dos outros dados a serem salvos
   * @param oParametros
   */
  function setValoresOutrosDados( oParametros ) {

    oParametros.outrosDados = {
      'cgm'         : $F('z01_numcgm'),
      'cge'         : $F('ed47_i_codigo'),
      'cidadao'     : $F('ov02_sequencial'),
      'ocupacao'    : $F('rh70_sequencial'),
      'estado_civil': outrosDados.estado_civil.value,
      'microarea'   : outrosDados.microarea.value,
      'familia'     : outrosDados.familia.value,
      'bolsafamilia': outrosDados.bolsafamilia.value,
      'responsavel' : outrosDados.responsavel.getValue(),
      'observacoes' : outrosDados.observacoes.value,
      'escolaridade': outrosDados.escolaridade.value
    }
  };

  var montarComboMicroArea = function(selecionado, dadosMicroareas) {

    selecionado = selecionado || null;
    outrosDados.microarea.length = 0;

    outrosDados.microarea.add(new Option("Selecione...", ""));
    
    for (var microarea of dadosMicroareas) {
      
      outrosDados.microarea.add(new Option(microarea.label_microarea, microarea.codigo_microarea));

      if (microarea.codigo_microarea == selecionado) {
        
        outrosDados.familia.length   = 0;
        outrosDados.familia.add(  new Option("Selecione...", ""));
        montarComboMicroArea(microarea.familias);
      }
    }
  };

  var montarComboFamilia = function(familias) {

    for ( var familia of familias) {
      outrosDados.familia.add(
        new Option(familia.label_familia, familia.codigo_familia)
      );
    }
  }

  validacoes.push(function(){

    if (outrosDados.microarea.getValue() && !outrosDados.familia.getValue()) {
        
      oDBAba.mostraFilho(oAbaOutrosDados);
      alert( _M( MENSAGENS_MANUTENCAO_CGS + 'outros_dados_familia_nao_selecionada' ) );
      outrosDados.familia.focus();
      return false;
    }
    return true;
  });
</script>
