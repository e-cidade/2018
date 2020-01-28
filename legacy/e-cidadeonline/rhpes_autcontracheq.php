<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

session_start();

include("libs/db_conecta.php");
include("libs/db_stdlib.php");
include("libs/db_sql.php");
include("libs/db_utils.php");
include("dbforms/db_funcoes.php");

$oPost = db_utils::postMemory($_POST);

if (isset($oPost->autent)    && $oPost->autent    != '' && 
    isset($oPost->matricula) && $oPost->matricula != '') {
    	
    $numMatricula    = $oPost->matricula;
    $codAutenticacao = $oPost->autent;    	
	  
	  $sqlRhEmiteContraCheque = " select rh85_sequencial,
	                                     rh85_regist
	                                from rhemitecontracheque 
	                               where rh85_regist    = '{$numMatricula}' 
	                                 and rh85_codautent = '{$codAutenticacao}'";
	
	  $rsRhEmiteContraCheque  = db_query($sqlRhEmiteContraCheque);
      $iRhEmiteContraCheque   = pg_num_rows($rsRhEmiteContraCheque);

    if($iRhEmiteContraCheque == 0){
       db_logs("","",0,"Solicitação de senha para fornecedor: cgc ou cpf não encontrado. {$numMatricula}");
       $erro = true;
    }
   
    if($iRhEmiteContraCheque > 0){
      $oRhEmiteContraCheque = db_utils::fieldsMemory($rsRhEmiteContraCheque,0);
      $erro = false;   
    }  
	  
	if (isset($erro) && $erro == false) {
		$sValidaCodAutenticacao = 't';
	} else if (isset($erro) && $erro == true) {
		$sValidaCodAutenticacao = 'f';
	}
	
} else {
	  $sValidaCodAutenticacao = '';
}



?>
<html>
  <head>
    <title><?=$w01_titulo?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <link href="config/estilos.css" rel="stylesheet" type="text/css">
    <script language="JavaScript" src="scripts/db_script.js"></script>
  </head>
  <body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
   <br />
    <br />
    <form method="post" action="" class="">
      <table align="center" border="0" cellpadding="2" cellspacing="2" width="50%" class="texto">
        <tr class="">
          <td class="" width="10%" align="left"><b>Matricula:</b></td>
          <td class="" width="2%" align="left"><span><font color='#E9000'> * </font></span></td>
          <td class="" width="20%" align="left">
            <input class="" type="text" id="matricula" name="matricula" size="15" maxlength="10"
                   onChange='js_teclas(event);'
                   onKeyPress='return js_teclas(event);'>
          </td>
          <td class="" width="30%" align="left"></td>
        </tr>      
        <tr class="">
          <td class="" width="10%" align="left"><b>Autencidade:</b></td>
          <td class="" width="2%" align="left"><span><font color='#E9000'> * </font></span></td>
          <td class="" width="20%" align="left">
            <input class="" type="password" id="autent" name="autent" size="50" maxlength="255"
                   onChange='js_teclas(event);'
                   onKeyPress='return js_teclas(event);'>
          </td>
        </tr>
        <br />       
        <tr class="">
         <td class="" width="10%" align="left" colspan="0">
         <td class="" width="2%"  align="left" colspan="0">
         <td align="left" colspan="2">
            <span><font color='#E9000'> PREENCHIMENTO OBRIGATÓRIO(*) </font></span>
         </td>         
        </tr>        
        <tr class="">
          <td class="" width="10%" align="left" colspan="0">
          <td class="" width="2%" align="left" colspan="0">
          <td class="" width="20%" align="left" colspan="0">
            <input class="" type="submit" id="" name="" value="Consultar">
          </td>         
        </tr> 
      </table>
    </form>
<?
 if ($sValidaCodAutenticacao == 'f') {
?>    
 <table width="300" align="center" border="0" bordercolor="#cccccc" cellpadding="2" cellspacing="0" class="texto">
  <tr height="220">
   <td align="center">
    <img src="imagens/atencao.gif"><br>
    Os Dados Digitados são Inconsistentes!
   </td>
  </tr>
 </table>
<?
 } else if ($sValidaCodAutenticacao == 't') {
 	  $codSeq    = $oRhEmiteContraCheque->rh85_sequencial;
 	  $codMatric = $oRhEmiteContraCheque->rh85_regist;
 	  $sUrl      = base64_encode("cod=".$codSeq."&matric=".$codMatric);
 	  db_redireciona('rhpes_conscontracheque.php?'.$sUrl);
 } else {
?>
 <table width="300" align="center" border="0" bordercolor="#cccccc" cellpadding="2" cellspacing="0" class="texto">
  <tr height="220">
   <td align="center">&nbsp;</td>
  </tr>
 </table>
<?
 }
?>    
  </body>
</html>
