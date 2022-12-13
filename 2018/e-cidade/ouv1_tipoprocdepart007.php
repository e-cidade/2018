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
      <legend><b>Departamentos de Destino</b></legend>
      <table width="100%" border="0">
        <tr>
          <td align="center">
            <b>Opções;</b>
            <?
              $aOpcaoDepartDestino = array(0 => "Com os departamentos selecionados", 1 => "Sem os departamentos selecionados");
              db_select("iOpcaoDepartDestino", $aOpcaoDepartDestino, true, 1);
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap width="50%">
            <?
              $oAuxDepartDestino                              = new cl_arquivo_auxiliar();
              $oAuxDepartDestino->cabecalho                   = "<strong>Departamentos Selecionados</strong>";
              $oAuxDepartDestino->codigo                      = "coddepto"; //chave de retorno da func
              $oAuxDepartDestino->descr                       = "descrdepto";   //chave de retorno
              $oAuxDepartDestino->nomeobjeto                  = 'listaDepartamentoDestino';
              $oAuxDepartDestino->funcao_js                   = 'js_mostra';
              $oAuxDepartDestino->funcao_js_hide              = 'js_mostra1';
              $oAuxDepartDestino->sql_exec                    = "";
              $oAuxDepartDestino->executa_script_apos_incluir = "js_salvaDepartamentosDestino();";
              $oAuxDepartDestino->func_arquivo                = "func_db_depart.php";  //func a executar
              $oAuxDepartDestino->nomeiframe                  = "db_iframe_depart";
              $oAuxDepartDestino->localjan                    = "";
              $oAuxDepartDestino->onclick                     = "";
              $oAuxDepartDestino->db_opcao                    = 2;
              $oAuxDepartDestino->tipo                        = 2;
              $oAuxDepartDestino->top                         = 0;
              $oAuxDepartDestino->linhas                      = 5;
              $oAuxDepartDestino->vwidth                      = 400;
              $oAuxDepartDestino->funcao_gera_formulario();
              
              db_input("sDepartDestino", 10, '', '', 'hidden');
          ?>
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
  function js_salvaDepartamentosDestino() {
  
    var oDepartDestino      = $("listaDepartamentoDestino");
    var sQueryDepartDestino = "";
    var sVirgula            = "";
    
    for (var i = 0; i < oDepartDestino.length; i++) {
    
      sQueryDepartDestino += sVirgula + oDepartDestino[i].value;
      sVirgula     = ",";
    }
    
    $("sDepartDestino").value = sQueryDepartDestino;
  }
  
  /**
   * Observa se houve evento do tipo dblclick dentro do multi select
   */
  $("listaDepartamentoDestino").observe('dblclick', function() {
    js_salvaBairro();
  });
</script>
</body>
</html>