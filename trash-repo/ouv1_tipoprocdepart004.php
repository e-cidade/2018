<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

  require_once("libs/db_stdlib.php");
  require_once("libs/db_conecta.php");
  require_once("libs/db_sessoes.php");
  require_once("libs/db_usuariosonline.php");
  require_once("dbforms/db_classesgenericas.php");
  require_once("dbforms/db_funcoes.php");
  require_once("libs/db_app.utils.php");
  
  $oAux = new cl_arquivo_auxiliar();
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js, prototype.js, strings.js");
  db_app::load("estilos.css");
?>

</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" style="margin-top: 20px;">

  <center>
    <form id="form1" name="form1">
      <fieldset style="width: 450px; padding: 20px;">
      <legend><b>Tipo de Processo</b></legend>
      <table width="100%">
        <tr>
          <td align="center">
            <b>Opções;</b>
            <?
              $aOpcaoTipoProcesso = array(0 => "Com tipos de processo selecionados", 1 => "Sem tipos de processo selecionados");
              db_select("iTiposProcesso", $aOpcaoTipoProcesso, true, 1);
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap width="50%">
            <?
              $oAuxTipoProc = new cl_arquivo_auxiliar();
              $oAuxTipoProc->cabecalho      = "<strong>Tipo Processo Selecionados</strong>";
              $oAuxTipoProc->codigo         = "p51_codigo";
              $oAuxTipoProc->descr          = "p51_descr";
              $oAuxTipoProc->nomeobjeto     = 'listaTipoProcesso';
              $oAuxTipoProc->funcao_js      = 'js_mostra';
              $oAuxTipoProc->funcao_js_hide = 'js_mostra1';
              $oAuxTipoProc->sql_exec       = "";
              $oAuxTipoProc->executa_script_apos_incluir = "js_salvaTipoProcesso();";
              $oAuxTipoProc->func_arquivo   = "func_tipoproc.php";
              $oAuxTipoProc->nomeiframe     = "db_iframe_tipoprocesso";
              $oAuxTipoProc->localjan       = "";
              $oAuxTipoProc->onclick        = "";
              $oAuxTipoProc->db_opcao       = 2;
              $oAuxTipoProc->tipo           = 2;
              $oAuxTipoProc->top            = 0;
              $oAuxTipoProc->linhas         = 5;
              $oAuxTipoProc->vwidth        = 400;
              $oAuxTipoProc->funcao_gera_formulario();
              
              db_input("sTipoProcesso", 10, '', '', 'hidden');
          ?>    
          </td>
        </tr>
      </table>
      </fieldset>
    </form>  
  </center>
<script>


  function js_BuscaDadosArquivolistaTipoProcesso(lMostra) {
    
    var sDepartamentosSelecionados = parent.iframe_abaDepartProcesso.$("sDepartSelecionado").value;
    
    if (lMostra == true) {
      
      var sUrlOpenTrue = "func_tipoproc.php?grupo=2&sDepartamentos="+sDepartamentosSelecionados+"&funcao_js=parent.js_mostra|p51_codigo|p51_descr";
      js_OpenJanelaIframe("", "db_iframe_tipoprocesso", sUrlOpenTrue, "Pesquisa", true);
    } else {
      
      var sUrlOpenFalse = "func_tipoproc.php?grupo=2&sDepartamentos="+sDepartamentosSelecionados+"&pesquisa_chave="+$("p51_codigo").value+"&funcao_js=parent.js_mostra1";
      js_OpenJanelaIframe("", "db_iframe_tipoprocesso", sUrlOpenFalse, "Pesquisa", false);
    }
    
  }
  
  
  /**
   *  Armazena os tipos de processo em um input do tipo hidden para facilitar o resgate dos mesmos
   */
  function js_salvaTipoProcesso() {
  
  
    var oTipoProcSelecionado = $("listaTipoProcesso");
    var sQueryTipoProcesso   = "";
    var sVirgula             = "";
    
    for (var i = 0; i < oTipoProcSelecionado.length; i++) {
    
      sQueryTipoProcesso += sVirgula+oTipoProcSelecionado[i].value;
      sVirgula      = ",";
    }
    
    $("sTipoProcesso").value = sQueryTipoProcesso;
  }
  
  /**
   * Observa se houve evento do tipo dblclick dentro do multi select
   */
  $("listaTipoProcesso").observe('dblclick', function() {
    js_salvaTipoProcesso();
  });
  

</script>
</body>
</html>