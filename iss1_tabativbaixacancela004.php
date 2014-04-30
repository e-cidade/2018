<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
require_once("classes/db_tabativbaixa_classe.php");
require_once("classes/db_tabativ_classe.php");
require_once("classes/db_ativprinc_classe.php");
require_once("classes/db_issbase_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_issalvara_classe.php");
require_once("model/issqn/AlvaraMovimentacaoCancelamento.model.php");
db_postmemory($HTTP_POST_VARS);
$cltabativbaixa = new cl_tabativbaixa;
$cltabativ      = new cl_tabativ;
$clativprinc    = new cl_ativprinc;
$clissbase      = new cl_issbase;
$clAlvara       = new cl_issalvara; 
$db_opcao = 1;
$db_botao = true;
if(isset($cancelar)){
  $sqlerro=""; 
  db_inicio_transacao();
  
  $sSqlAlvara = $clAlvara->sql_query_file(null,"q123_sequencial",null, "q123_inscr = {$q07_inscr}");
  $rsAlvara   = $clAlvara->sql_record($sSqlAlvara);
  db_fieldsmemory($rsAlvara,0);
  
  $oAlvara = new AlvaraCancelamento($q123_sequencial);
  $oAlvara->setValidadeAlvara(0);
  $oAlvara->setDataMovimentacao(date('Y-m-d',db_getsession('DB_datausu')));
  $oAlvara->setObservacao("Cancelamento de Baixa de Inscricao");
  $oAlvara->setTipoMovimentacao(7);
  $oAlvara->cancelaUltimaMovimentacao();

  $matriz01=split('#',$chaves);  
  for($q=0; $q<sizeof($matriz01); $q++ ){
     $matriz=split("-",$matriz01[$q]);
     $cltabativbaixa->sql_record($cltabativbaixa->sql_query_file($q07_inscr,"","q11_inscr"));
     if($cltabativbaixa->numrows>0){
       $cltabativbaixa->q11_seq=$matriz[1];
       $cltabativbaixa->q11_inscr=$q07_inscr;
       $cltabativbaixa->excluir($q07_inscr,$matriz[1]);
       //$cltabativbaixa->erro(true,false);
       if($cltabativbaixa->erro_status==0){
         $sqlerro = true;
       }
     }  
     if(!$sqlerro){
       $cltabativ->q07_seq=$matriz[1];
       $cltabativ->q07_inscr=$q07_inscr;
       //$cltabativ->q07_datafi='1';
       //$cltabativ->q07_databx='1';
       $cltabativ->alterar($q07_inscr,$matriz[1]);
       //$cltabativ->erro(true,false);
       if($cltabativ->erro_status==0){
         $sqlerro = true;
       }
     } 
   }
   if(!$sqlerro){
        $cltabativ->sql_record($cltabativ->sql_query_file($q07_inscr,"","q07_inscr","","q07_databx is null and q07_inscr=$q07_inscr"));
        if($cltabativ->numrows>0){
           $clissbase->q02_dtbaix="";
           $clissbase->q02_inscr=$q07_inscr;
           $clissbase->alterar($q07_inscr);
           //$clissbase->erro(true,false);
           if($clissbase->erro_status==0){
             $sqlerro=true; 
           } 
        } 
   }  
   
  $matriz01=split('#',$chaves);  
  if($calculo=="ok"){
    $seqs="";
    $virgu="";
    for($q=0; $q<sizeof($matriz01); $q++ ){
       $matriz=split("-",$matriz01[$q]);
       $seqs.=$virgu.$matriz[1];
       $virgu=","; 
    }
    $data=date('Y-m-d',db_getsession("DB_datausu"));
    $ano=db_getsession("DB_anousu"); 
    $instit=db_getsession('DB_instit');
    $sql02 = "SELECT fc_issqn($q07_inscr,'".$data."',".$ano.",null,'true','false',".$instit.",'".$seqs."') AS RETORNO";
    $result02=@pg_query($sql02);  
    @db_fieldsmemory($result02,0);
    if(isset($retorno) && (substr($retorno,0,2) == "01" or substr($retorno,0,2) == "24")){
      $trans_calculo=true;
    }else{
      $trans_calculo=false;
      $sqlerro=true;
    }
  }
   db_fim_transacao($sqlerro);
}
if(empty($sqlerro) || $sqlerro=false){
  $load="onLoad='document.form1.q07_inscr.focus();'";
}else{
  $load="";
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
<center>
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmtabativbaixacancela.php");
	?>
    </center>
	</td>
  </tr>
</table>
</center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($baixada)){
  db_msgbox("Inscrição $baixada baixada.");
} 
if(isset($cancelar)){
  if(isset($trans_calculo)){
    if($trans_calculo==true){
    $cltabativbaixa->erro(true,false);
    db_msgbox("Mensagem de Retorno do Calculo: {$retorno}");
    }else{
	  db_msgbox("Operação cancelada. \\nOcorreu algum problema durante o calculo!\\n Mensagem retornada:$retorno");
    }
  }else{
    $cltabativ->erro(true,false);
  }
}
?>