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
      <legend><b>Bairro</b></legend>
      <table width="100%" border="0">
        <tr>
          <td align="center">
            <b>Opções;</b>
            <?
              $aOpcaoBairros = array(0 => "Com os bairros selecionados", 1 => "Sem os bairros selecionados");
              db_select("iOpcaoBairro", $aOpcaoBairros, true, 1);
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap width="50%">
            <?
              $oAuxBairro                              = new cl_arquivo_auxiliar();
              $oAuxBairro->cabecalho                   = "<strong>Bairros Selecionados</strong>";
              $oAuxBairro->codigo                      = "j13_codi";
              $oAuxBairro->descr                       = "j13_descr";
              $oAuxBairro->nomeobjeto                  = 'listaBairro';
              $oAuxBairro->funcao_js                   = 'js_mostra';
              $oAuxBairro->funcao_js_hide              = 'js_mostra1';
              $oAuxBairro->sql_exec                    = "";
              $oAuxBairro->executa_script_apos_incluir = "js_salvaBairro();";
              $oAuxBairro->func_arquivo                = "func_bairro.php";
              $oAuxBairro->nomeiframe                  = "db_iframe_bairro";
              $oAuxBairro->localjan                    = "";
              $oAuxBairro->onclick                     = "";
              $oAuxBairro->db_opcao                    = 2;
              $oAuxBairro->tipo                        = 2;
              $oAuxBairro->top                         = 0;
              $oAuxBairro->linhas                      = 5;
              $oAuxBairro->vwidth                      = 400;
              $oAuxBairro->funcao_gera_formulario();
              
              db_input("sBairro", 10, '', '', 'hidden');
          ?>
          </td>
        </tr>
        <tr>
          <td align="center">
            <span style="font-weight: bold; font-size: 11px;">
              * Selecionando um Bairro o filtro Local será desconsiderado
            </span>
          </td>
        </tr>
      </table>
      
      </fieldset>
    </form>  
  </center>


<script>

  /**
   *  Armazena os tipos de processo em um input do tipo hidden para facilitar o resgate dos mesmos
   */
  function js_salvaBairro() {
  
    var oBairro      = $("listaBairro");
    var sQueryBairro = "";
    var sVirgula     = "";
    
    for (var i = 0; i < oBairro.length; i++) {
    
      sQueryBairro += sVirgula + oBairro[i].value;
      sVirgula     = ",";
    }
    
    $("sBairro").value = sQueryBairro;
  }
  
  /**
   * Observa se houve evento do tipo dblclick dentro do multi select
   */
  $("listaBairro").observe('dblclick', function() {
    js_salvaBairro();
  });
</script>
</body>
</html>