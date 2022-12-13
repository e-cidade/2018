<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBSeller Servicos de Informatica             
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
require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_app.utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/exceptions/BusinessException.php");
include("dbforms/db_funcoes.php");

$clrotulo = new rotulocampo;
$clrotulo->label("rh01_regist");
$clrotulo->label("z01_nome");
$clrotulo->label("rh164_datainicio");
$clrotulo->label("rh164_datafim");

try{

  $clrhpessoal                     = new cl_rhpessoal;
  $sSqlPessoal                     = $clrhpessoal->sql_query($rh01_regist);
  $rsPessoalContratosTemporarios   = db_query($sSqlPessoal);
  
  if(!$rsPessoalContratosTemporarios || pg_num_rows($rsPessoalContratosTemporarios) == 0) {
    throw new BusinessException("Erro ao buscar dados do servidor da base de dados.");
  }
  
  if(pg_num_rows($rsPessoalContratosTemporarios) > 0 && $rh01_regist != "") {
    db_fieldsmemory($rsPessoalContratosTemporarios, 0);
  }

} catch (Exception $oErro) {
  $sErro = $oErro->getMessage();
}

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php 
      db_app::load('scripts.js, prototype.js, dbcomboBox.widget.js, dbtextField.widget.js, strings.js, DBHint.widget.js, DBLookUp.widget.js, AjaxRequest.js, datagrid.widget.js, dates.js, strings.js');
    ?>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <style type="text/css">
      fieldset{
        width: 600px;
      }
      #contratos > table {
        margin: 0 auto;
      }
    </style>
  </head>
  <body>
    <form action="" method="POST" class="container">  
      <fieldset id="contratos">
        <legend>Contrato</legend>
        <table class="container- form">
          <tr>
            <td nowrap="" title="<?php echo $Trh01_regist?>">
              <a id="procurarMatricula"><?php echo $Lrh01_regist; ?></a>
            </td>
            <td>
              <?
                db_input('rh01_regist', 10, '', true, 'text', 1, "");
              ?>
            </td>
            <td title="<?php echo $Tz01_nome?>">
              <?
                db_input('z01_nome', 50, '', true, 'text', 3, "");
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <?php echo $Lrh164_datainicio; ?>
            </td>
            <td colspan="2">
              <?
                db_inputdata('rh164_datainicio', @$rh164_datainicio_dia, @$rh164_datainicio_mes, @$rh164_datainicio_ano, true, 'text', 2, "");
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <?php echo $Lrh164_datafim; ?>
            </td>
            <td colspan="2">
              <?
                db_inputdata('rh164_datafim', @$rh164_datafim_dia, @$rh164_datafim_mes, @$rh164_datafim_ano, true, 'text', 2, "");
              ?>
            </td>
          </tr>
        </table>
        <table id="tableGridRenovacoes" class="container- form">
          <tr style="display: none">
            <td colspan="3">
              <?
                db_input('sequencialContrato', 10, '', true, 'hidden', 3, "");
                db_input('sequencialRenovacao', 10, '', true, 'hidden', 3, "");
                db_inputdata('dataFimAnterior', @$dataFimAnterior_dia, @$dataFimAnterior_mes, @$dataFimAnterior_ano, true, 'hidden', 3, "");
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
      <input type="button" id="processar" name="processar" value="Processar" />
      <fieldset>
        <legend>Histórico</legend>
        <div id="gridRenovacoes"></div>
      </fieldset>  
    </form>

    <?
      if(isset($sErro) && $sErro !="") {
        echo "<script type=\"text/javascript\">alert(\"$sErro\");</script>";
      }
    ?>
    <script type="text/javascript">

      require_once('scripts/classes/pessoal/contratosemergenciais/DBViewManutencaoContratosEmergenciais.classe.js');
      var oManutencaoContratosEmergenciais = new DBViewManutencaoContratosEmergenciais();
      oManutencaoContratosEmergenciais.show();

      $("rh01_regist").className = "";

    </script>
    <?php db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit")); ?>
  </body>
</html>
