<?php
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
require_once("classes/db_assenta_classe.php");
require_once("classes/db_tipoasse_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");
require_once("std/DBDate.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$classenta          = new cl_assenta;
$cltipoasse         = new cl_tipoasse;

$db_opcao = 22;
$db_botao = false;

/**
 * Alterar assentamento/afastamento
 */
if ( isset($alterar) ) {

  db_inicio_transacao();

  $db_botao = true;
  $db_opcao = 2;

  try {

    /**
     * h12_assent, no formulario esta como h12_codigo
     */
    $sCodigoAfastamento = trim($h12_codigo);

    /**
     * Campo com codigo do afastamento nao informado
     */
    if ( empty($sCodigoAfastamento) ){
      throw new Exception("Campo assentamento/afastamento não informado.");
    }

    $oDataInicial = new DBDate($h16_dtconc);
    $dDataInicial = $oDataInicial->getDate();

    /**
     * Campo data final no formulario vazia
     * - procura afastamentos com data inicial maior ou igual e com data final menor ou igual
     * - ou com data final vazia(afastamento em aberto)
     * - ou com data inicial do formulario menor ou igual a do banco (afastamento com data posterior ja cadastrado)
     */
    $sWhereDatas  = " (                                                                                      ";
    $sWhereDatas .= "     ('{$dDataInicial}'::date >= h16_dtconc and '{$dDataInicial}'::date <= h16_dtterm ) ";
    $sWhereDatas .= "  or (h16_dtterm is null)                                                               ";
    $sWhereDatas .= "  or ( '{$dDataInicial}'::date <= h16_dtconc )                                          ";
    $sWhereDatas .= " )                                                                                      ";

    /**
     * Caso campo com data final nao estiver vazio procura afastamento entre data inicial e final
     * ou com data final vazia(afastamento em aberto)
     */
    if ( !empty($h16_dtterm) ) {

      $oDataFinal  = new DBDate($h16_dtterm);
      $dDataFinal  = $oDataFinal->getDate();
      $sWhereDatas = " (h16_dtconc, case when h16_dtterm is null then '3000-12-31'::date else h16_dtterm+1 end) overlaps ('{$dDataInicial}'::date, '{$dDataFinal}'::date) ";
    }

    /**
     * Antes de alterar verifica se já nao tem afastamento cadastrado para o servidor no mesmo periodo
     *
     * h16_dtterm - data de termino
     * h16_dtconc - data concessao
     */
    $sWhereValidacao  = " case                                                              ";
    $sWhereValidacao .= "   when exists ( select 1                                          ";
    $sWhereValidacao .= "                   from tipoasse                                   ";
    $sWhereValidacao .= "                  where trim(h12_assent) = '{$sCodigoAfastamento}' ";
    $sWhereValidacao .= "                  and h12_tipo = 'A'                               ";
    $sWhereValidacao .= "               )                                                   ";
    $sWhereValidacao .= "     then (     h16_regist  = {$h16_regist}                        ";
    $sWhereValidacao .= "            and h12_tipo    = 'A'                                  ";
    $sWhereValidacao .= "            and h16_codigo != {$h16_codigo}                        ";
    $sWhereValidacao .= "            and ({$sWhereDatas})                                   ";
    $sWhereValidacao .= "          )                                                        ";
    $sWhereValidacao .= "     else false                                                    ";
    $sWhereValidacao .= " end                                                               ";
    $sSqlValidacao    = $classenta->sql_query(null, '*', 'h16_dtconc', $sWhereValidacao);
    $rsValidacao      = $classenta->sql_record($sSqlValidacao);

    /**
     * Econtrou afastamento para o periodo informado no formulario
     * Lanca excessao
     */
    if ( $classenta->numrows > 0 && $h12_vinculaperiodoaquisitivo == 'f') {

      $sMensagemErro  = "Assentamento já cadastrado para este tipo no mesmo período.";
      $aAssentamentos = db_utils::getCollectionByRecord($rsValidacao);

      /**
       * Percorre assentamentos encontrados para montar mensagem de erro
       */
      foreach( $aAssentamentos as $oAssentamento ) {

        /**
         * Encontrou afastamento em aberto
         */
        if ( $oAssentamento->h16_dtterm == '' ) {
          $sMensagemErro = "Servidor com afastamento em aberto.";
        }

        $oDataInicial = new DBDate($oAssentamento->h16_dtconc);
        $sDataInicial = $oDataInicial->getDate(DBDate::DATA_PTBR);

        $sDataFinal   = null;

        if ( !empty($oAssentamento->h16_dtterm) ) {

          $oDataFinal = new DBDate($oAssentamento->h16_dtterm);
          $sDataFinal = $oDataFinal->getDate(DBDate::DATA_PTBR);
        }

        $sMensagemErro .= "\n\nAfastamento encontrado: {$oAssentamento->h12_assent}";
        $sMensagemErro .= "\nData inicial: {$sDataInicial}";
        $sMensagemErro .= "\nData final  : {$sDataFinal}";
      }

      throw new Exception($sMensagemErro);
    }

    /**
     * Altera Assentamento/afastamento
     */
    $classenta->h16_assent = $h16_assent;
    $classenta->alterar($h16_codigo);

    /**
     * Erro ao alterar assentamento/afastamento
     */
    if ( $classenta->erro_status == "0" ) {
      throw new Exception($classenta->erro_msg);
    }

    if ($h12_vinculaperiodoaquisitivo == 't') {
      $oPeriodoAquisitivoAssentamento = PeriodoAquisitivoAssentamento::getPeriodoAquisitivoAssentamento(
                                            new Assentamento( $classenta->h16_codigo ));

      if ($oPeriodoAquisitivoAssentamento) {
        $oPeriodoAquisitivoAssentamento->excluir();
      } else {
        $oPeriodoAquisitivoAssentamento = new PeriodoAquisitivoAssentamento();
      }

      $oPeriodoAquisitivoAssentamento->setAssentamento(new Assentamento( $classenta->h16_codigo ));
      $oPeriodoAquisitivoAssentamento->setPeriodoAquisitivo(new PeriodoAquisitivoFerias( $iPeriodoAquisitivo ));

      $oPeriodoAquisitivoAssentamento->salvar();
    }else{

      /**
       * Remove vinculo da rhferiasassenta
       */
     $oDaoRhFeriasAssenta = db_utils::getDao("rhferiasassenta");

     $sSqlRhFeriasAssenta = $oDaoRhFeriasAssenta->sql_query(null, 'rh131_sequencial', null, 'rh131_assenta = ' . $h16_codigo );
     $rsRhFeriasAssenta   = $oDaoRhFeriasAssenta->sql_record($sSqlRhFeriasAssenta);

     if( !empty($rsRhFeriasAssenta) ){

       $oRhFeriasAssenta    = db_utils::fieldsMemory($rsRhFeriasAssenta, 0);
       $oDaoRhFeriasAssenta->rh131_assenta  = $oRhFeriasAssenta->rh131_sequencial;
       $oDaoRhFeriasAssenta->excluir( $oRhFeriasAssenta->rh131_sequencial );

        if ($oDaoRhFeriasAssenta->erro_status == "0") {

          throw new DBException( "Erro ao excluir assentamento." );
        }
     }
    }

    /**
     * commit
     */
    db_fim_transacao();

    /**
     * Alert com mensagem de alteracao e atualizado tela
     */
    $classenta->erro(true, true);

  } catch(Exception $oErro) {

    /**
     * Exibe alert com mensagem de erro e da rollback
     */
    db_msgbox(str_replace("\n", '\n', $oErro->getMessage()));
    db_fim_transacao(true);
  }

} else if(isset($chavepesquisa)) {

  $db_opcao = 2;
  $result = $classenta->sql_record($classenta->sql_query($chavepesquisa));
  db_fieldsmemory($result,0);
  $db_botao = true;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
  <?
  include("forms/db_frmassenta.php");
  ?>
    </center>
  </td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?php
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
if(isset($h16_conver) && trim($h16_conver) == "t"){
  db_msgbox("Assentamento gerado a partir de dois assentamentos de meio dia. \\nAlteração não permitida.");
  echo "<script>location.href = 'rec1_assenta002.php'</script>";
}
?>
<script>
js_tabulacaoforms("form1","h16_regist",true,1,"h16_regist",true);
</script>