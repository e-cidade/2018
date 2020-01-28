<?php
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");

$oRotulo = new rotulocampo();
$oRotulo->label("tre06_sequencial");
$oRotulo->label("tre06_nome");

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?php
    db_app::load("scripts.js, strings.js, prototype.js, estilos.css");
  ?>
</head>
<body style="background-color: #ccc; margin-top: 30px">
  <div class='container'>
    <form method="post" action="">
      <fieldset>
        <legend class="bold">Alunos por linha de transporte</legend>
        <table class='form-container'>
          <tr>
            <td>
            <?php
              db_ancora("<b>Linha:</b>", "js_pesquisaLinhas(true);", 1);
            ?>
            <td >
            <?php
              db_input("tre06_sequencial", 10, $Itre06_sequencial, true, 'text', 1, "onChange='js_pesquisaLinhas(false);'");
              db_input("tre06_nome", 41, "", true, 'text', 3);
            ?>
            </td>
          </tr>
          <tr>
            <td><label class="bold">Itinerário:</label></td>
            <td>
              <select id='itinerario' >
                <option value="0" selected="selected">Todos</option>
                <option value="1" >Ida</option>
                <option value="2" >Volta</option>
              </select>
            </td>
          </tr>
          <tr>
            <td>Tipo:</td>
            <td>
              <select id='tipo' >
                <option value="1" selected="selected">Analítico</option>
                <option value="2" >Sintético</option>
              </select>
            </td>
          </tr>
        </table>
      </fieldset>
      
      <input type="button" id="imprimir" value="Imprimir"/>
    </form>
  </div>
</body>
<?php
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</html>
<script type="text/javascript">

/**
 * Pesquisa as linhas cadastradas
 */
function js_pesquisaLinhas(lMostra) {

  var sUrl  = 'func_linhatransporte.php?funcao_js=parent.js_mostraLinhas';

  if (lMostra) {
    sUrl += '|tre06_sequencial|tre06_nome';
  } else {

    if ( !empty( $F('tre06_sequencial') ) ) {
      sUrl += '&pesquisa_chave='+$F('tre06_sequencial');
    } else {
      oInputDescricaoLinha.value = '';
    }
  }
  
  js_OpenJanelaIframe('top.corpo', 'db_iframe_linhatransporte', sUrl, 'Pesquisa Linhas de Transporte', lMostra);
}

/**
 * Retorno das linhas cadastradas
 */
function js_mostraLinhas() {

  db_iframe_linhatransporte.hide();
  $('imprimir').setAttribute('disabled', 'disabled');
  if (arguments[1] !== true && arguments[1] !== false) {
    
    $('tre06_sequencial').value = arguments[0];
    $('tre06_nome').value       = arguments[1];
    $('imprimir').removeAttribute('disabled');
  }

  if (arguments[1] === true) {

    $('tre06_sequencial').value = '';
    $('tre06_nome').value       = arguments[0];
  }

  if (arguments[1] === false) {
    
    $('tre06_nome').value       = arguments[0];
    $('imprimir').removeAttribute('disabled');
  }
}

$('imprimir').observe('click', function () {

  var sUrl  = "tre2_alunolinha002.php";
  sUrl += "?iLinha="+$F('tre06_sequencial');
  sUrl += "&sLinha="+$F('tre06_nome');
  sUrl += "&iItinerario="+$F('itinerario');
  sUrl += "&iTipo="+$F('tipo');

jan = window.open(sUrl,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
jan.moveTo(0,0);
  
});

$('imprimir').setAttribute('disabled', 'disabled');

</script>