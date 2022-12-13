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
require_once ("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_saniatividade_classe.php");
require_once ("classes/db_parfiscal_classe.php");
require_once ("classes/db_cissqn_classe.php");

$clsaniatividade=new cl_saniatividade;
$clparfiscal = new cl_parfiscal();
$clcissqn = new cl_cissqn();

db_postmemory($HTTP_POST_VARS);
$db_opcao = 1;
$db_botao = true;
$load = null;

$rsParfiscal = $clparfiscal->sql_record($clparfiscal->sql_query_file(db_getsession('DB_instit'), "y32_calcvistanosanteriores"));
$oParfiscal = db_utils::fieldsMemory($rsParfiscal, 0);

try {

  if(isset($calcular)){
    
    /**
     * Verifica se foi informado incrisão municipal na aba Sanitário.
     * - Se for informada verifica se a empresa esta paralizada.
     * - Senão realiza o Calculo
     */
     $oDaoSanitarioInscr = db_utils::getDao('sanitarioinscr');
     $sSqlSanitarioInscr = $oDaoSanitarioInscr->sql_query($y80_codsani, null, 'y18_inscr');
     $rsSanitarioInscr   = db_query($sSqlSanitarioInscr);

     if( !$rsSanitarioInscr ) {
       throw new DBException("Ocorreu um erro ao buscar a inscrição municipal. Erro Técnico: " . pg_last_error());
     }

     if( pg_num_rows($rsSanitarioInscr) > 0 ) {

       $oSanitarioInscr = db_utils::fieldsMemory($rsSanitarioInscr, 0);

       $oEmpresa = new Empresa($oSanitarioInscr->y18_inscr);

       /**
        * Se a empresa estiver paralisada. não permite a realização do calculo.
        */
       if ( $oEmpresa->isParalisada() ) {
         throw new Exception(_M(Empresa::MENSAGENS . 'empresa_paralisada'));
       }
     }


    if (!isset($anoini) && $anoini != "") {
      $anoini = db_getsession('DB_anousu');
    }

    //$seqs=str_replace("#",",",$chaves);
    db_query("BEGIN");
    $sql01=" CREATE TEMPORARY TABLE ATIVS AS 
    SELECT DISTINCT Y83_CODSANI, 't'::BOOLEAN AS Q07_PERMAN, '*'::CHAR(1) AS Q07_CALCULA, Y83_ATIV, Q03_DESCR, Y83_DTINI, Y83_DTFIM, Y83_DTFIM AS Y83_DATABX, 1 AS Q07_QUANT FROM SANIATIVIDADE INNER JOIN ATIVTIPO ON Y83_ATIV = Q80_ATIV INNER JOIN TIPCALC ON Q80_TIPCAL = Q81_CODIGO INNER JOIN ATIVID ON Y83_ATIV = Q03_ATIV INNER JOIN CADCALC ON Q81_CADCALC = Q85_CODIGO WHERE Y83_CODSANI  = $y80_codsani";
  // echo $sql01;
     db_query($sql01);
     $data=date('Y-m-d',db_getsession("DB_datausu"));
     //$ano=db_getsession("DB_anousu"); 
     $ano=$anoini;
     $instit=db_getsession('DB_instit');
     $sql02 = "SELECT fc_sanitario($y80_codsani,'".$data."',".$ano.",null,'true','false',".$instit.") AS RETORNO";
  //   echo $sql02;exit;
    $result02=db_query($sql02);  
    db_fieldsmemory($result02,0);
    if($retorno == "ok"){
       $calculo=true;
       db_query("COMMIT");
    }else{
       $calculo=false;
       db_query("ROLLBACK");
    }
  }
  if(empty($sqlerro) || $sqlerro=false){
    $load="onLoad='document.form1.q07_inscr.focus();'";
  }
} catch ( Exception $oException){
  $calculo = false;
  $retorno = $oException->getMessage();
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" <?=$load?> >
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
  <?
  include("forms/db_frmsanicalc.php");
  ?>
    </center>
  </td>
  </tr>
</table>
</body>
</html>
<?
if(isset($calcular)){
  if($calculo==true){
     db_msgbox('Calculo efetuado com sucesso!');
  }else{
    db_msgbox("Ocorreu algum problema durante o calculo!\\n Mensagem retornada:$retorno");

  }
 
}
?>