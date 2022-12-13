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
      <legend><b>Departamentos por Tipo de Processo</b></legend>
      <table width="100%">
        <tr>
          <td align="center">
            <b>Opções;</b>
            <?
              $aOpcaoDepartamento = array("Com departamentos selecionados", "Sem departamentos selecionados");
              db_select("iOpcaoDepartamento", $aOpcaoDepartamento, true, 1);
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap width="50%">
            <?
              $oAuxDepart                              = new cl_arquivo_auxiliar();
              $oAuxDepart->cabecalho                   = "<strong>Departamentos Selecionados</strong>";
              $oAuxDepart->codigo                      = "coddepto";
              $oAuxDepart->descr                       = "descrdepto";
              $oAuxDepart->nomeobjeto                  = 'listaDepartamento';
              $oAuxDepart->funcao_js                   = 'js_mostra';
              $oAuxDepart->funcao_js_hide              = 'js_mostra1';
              $oAuxDepart->sql_exec                    = "";
              $oAuxDepart->executa_script_apos_incluir = "js_salvaDepartamentos();";
              $oAuxDepart->func_arquivo                = "func_db_depart.php";
              $oAuxDepart->nomeiframe                  = "db_iframe_depart";
              $oAuxDepart->localjan                    = "";
              $oAuxDepart->onclick                     = "";
              $oAuxDepart->db_opcao                    = 2;
              $oAuxDepart->tipo                        = 2;
              $oAuxDepart->top                         = 0;
              $oAuxDepart->linhas                      = 5;
              $oAuxDepart->vwidth                     = 400;
              $oAuxDepart->funcao_gera_formulario();
              
              db_input("sDepartSelecionado", 10, '', '', 'hidden');
          ?>
          </td>
        </tr>
      </table>
      </fieldset>
    </form>  
  </center>
<script>
  /**
   *  Função que lança os departamentos selecionados em um campo do tipo hidden 
   */
  function js_salvaDepartamentos() {
  
    /**
     * Verifica se existem tipos de processos selecionados. Caso existam será mostrado o aviso configurado abaixo
     */
    var oTipoProcesso = parent.iframe_abaTipoProcesso.$("listaTipoProcesso"); 
    if (oTipoProcesso.length > 0) {
    
      var sMsgConfirm  = "Exitem tipos de processos selecionados. Ao selecionar um departamento, ";
      sMsgConfirm     += "a lista dos tipos de processos selecionados será limpa.\n\nConfirma este procedimento?";
      if (!confirm(sMsgConfirm)){
        
        $("listaDepartamento").length = 0;
        return false;
      } else {
        oTipoProcesso.length = 0;
      }
    }
    
    
    var oDepartSelecionado = $("listaDepartamento");
    var sQueryDepart       = "";
    var sVirgula           = "";
    
    for (var i = 0; i < oDepartSelecionado.length; i++) {
    
      sQueryDepart += sVirgula+oDepartSelecionado[i].value;
      sVirgula      = ",";
    }
    
    $("sDepartSelecionado").value = sQueryDepart;
    
  }
  
  /**
   * Observa se houve evento do tipo dblclick dentro do multi select
   */
  $("listaDepartamento").observe('dblclick', function() {
    js_salvaDepartamentos();
  });
</script>
</body>
</html>