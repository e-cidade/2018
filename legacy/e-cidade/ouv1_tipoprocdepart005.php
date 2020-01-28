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
      <legend><b>Local</b></legend>
      <table width="100%">
        <tr>
          <td align="center">
            <b>Opções;</b>
            <?
              $aOpcaoLocais = array(0 => "Com os locais selecionados", 1 => "Sem os locais selecionados");
              db_select("iOpcaoLocal", $aOpcaoLocais, true, 1);
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap width="50%">
            <?
              $oAuxLocal                              = new cl_arquivo_auxiliar();
              $oAuxLocal->cabecalho                   = "<strong>Locais Selecionados</strong>";
              $oAuxLocal->codigo                      = "ov25_sequencial";
              $oAuxLocal->descr                       = "ov25_descricao";
              $oAuxLocal->nomeobjeto                  = 'listaLocais';
              $oAuxLocal->funcao_js                   = 'js_mostra';
              $oAuxLocal->funcao_js_hide              = 'js_mostra1';
              $oAuxLocal->sql_exec                    = "";
              $oAuxLocal->executa_script_apos_incluir = "js_salvaLocal();";
              $oAuxLocal->func_arquivo                = "func_ouvidoriacadlocal.php";
              $oAuxLocal->nomeiframe                  = "db_iframe_local";
              $oAuxLocal->localjan                    = "";
              $oAuxLocal->onclick                     = "";
              $oAuxLocal->db_opcao                    = 2;
              $oAuxLocal->tipo                        = 2;
              $oAuxLocal->top                         = 0;
              $oAuxLocal->linhas                      = 5;
              $oAuxLocal->vwidth                      = 400;
              $oAuxLocal->funcao_gera_formulario();
              
              db_input("sLocais", 10, '', '', 'hidden');
          ?>
          </td>
        </tr>
      </table>
      
      </fieldset>
    </form>  
  </center>


<script>

  function js_BuscaDadosArquivolistaLocais(lMostra) {
  
    var sTipoLocalIframe = parent.iframe_abaPeriodo.$("sTipoLocal").value; 
    var sQueryLocais     = "";
    if (lMostra) {
      sQueryLocais = "func_ouvidoriacadlocal.php?sTipoLocal="+sTipoLocalIframe+"&funcao_js=parent.js_mostra|ov25_sequencial|ov25_descricao";
    } else {
      sQueryLocais = "func_ouvidoriacadlocal.php?sTipoLocal="+sTipoLocalIframe+"&pesquisa_chave="+$("ov25_sequencial").value+"&funcao_js=parent.js_mostra1";
    }
    
    js_OpenJanelaIframe("", "db_iframe_local", sQueryLocais, "Pesquisa", lMostra);
  }

  /**
   *  Armazena os tipos de processo em um input do tipo hidden para facilitar o resgate dos mesmos
   */
  function js_salvaLocal() {
  
    var oLocal      = $("listaLocais");
    var sQueryLocal = "";
    var sVirgula    = "";
    
    for (var i = 0; i < oLocal.length; i++) {
    
      sQueryLocal += sVirgula + oLocal[i].value;
      sVirgula     = ",";
    }
    
    $("sLocais").value = sQueryLocal;
  }
  
  /**
   * Observa se houve evento do tipo dblclick dentro do multi select
   */
  $("listaLocais").observe('dblclick', function() {
    js_salvaLocal();
  });
</script>
</body>
</html>