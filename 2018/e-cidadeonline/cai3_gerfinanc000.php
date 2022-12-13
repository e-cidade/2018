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
require("libs/db_stdlib.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

?>
<html>
<head>
<title>Documento sem t&iacute;tulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/scripts.js"></script>
<script type="text/javascript" src="scripts/strings.js"></script>
<script type="text/javascript" src="scripts/prototype.js"></script>
<style type="text/css">
<?
db_estilosite();
?>
</style>
<?
 if(trim(@$numcgm)==''){
 	$numcgm = '';
 }
 if(trim(@$matric)==''){
 	$matric = '';
 }
 if(trim(@$inscr)==''){
 	$inscr = '';
 }
 if(trim(@$tipo)==''){
 	$tipo = '';
 }

 if(trim(@$emrec)==''){
 	$emrec = '';
 }
 if(trim(@$agnum)==''){
 	$agnum = '';
 }
 if(trim(@$agpar)==''){
 	$agpar = '';
 }
 
 if (isset($suspensao)) { 
   ?>
   <iframe id="iframe" name="iframe" src="cai3_gerfinanc008.php?numcgm=<?=@$numcgm?>&matric=<?=@$matric?>&inscr=<?=@$inscr?>&tipo=<?=@$tipo?>&emrec=<?=@$emrec?>&agnum=<?=@$agnum?>&agpar=<?=@$agpar?>&db_datausu=<?=date('Y-m-d',db_getsession('DB_datausu'))?>&id_usuario=<?=@$id_usuario?>&cgccpf=<?=$cgccpf?>" width="100%" height="270"></iframe>
   <? 	
 } else if( isset($tipo) && $tipo == 3){
  ?>	
    <script>alert("Atenção!\n\Informe os valores clicando nas caixas de texto.\n\nApós, clique em Agrupar para selecionar\nas parcelas que deseja emitir o Recibo.");</script>
    <?if(trim($matric)=='')$matric='';?>
    <iframe id="iframe" name="iframe" src="cai3_gerfinanc002.php?numcgm=<?=@$numcgm?>&matric=<?=@$matric?>&inscr=<?=@$inscr?>&tipo=<?=@$tipo?>&emrec=<?=@$emrec?>&agnum=<?=@$agnum?>&agpar=<?=@$agpar?>&db_datausu=<?=date('Y-m-d',db_getsession('DB_datausu'))?>&id_usuario=<?=@$id_usuario?>&cgccpf=<?=$cgccpf?>" width="100%" height="270"></iframe>	
  <?
 } else if( isset($tipo) &&  $tipo == 19){
   ?>
   <iframe id="iframe" name="iframe" src="cai3_gerfinanc040.php?numcgm=<?=@$numcgm?>&matric=<?=@$matric?>&inscr=<?=@$inscr?>&tipo=<?=@$tipo?>&emrec=<?=@$emrec?>&agnum=<?=@$agnum?>&agpar=<?=@$agpar?>&db_datausu=<?=date('Y-m-d',db_getsession('DB_datausu'))?>&id_usuario=<?=@$id_usuario?>&cgccpf=<?=$cgccpf?>" width="100%" height="270"></iframe>
   <?
 } else if( isset($tipo) && $tipo == 34){
   $inicial = true;
   ?>
   <iframe id="iframe" name="iframe" src="cai3_gerfinanc050.php?numcgm=<?=@$numcgm?>&matric=<?=@$matric?>&inscr=<?=@$inscr?>&tipo=<?=@$tipo?>&emrec=<?=@$emrec?>&agnum=<?=@$agnum?>&agpar=<?=@$agpar?>&db_datausu=<?=date('Y-m-d',db_getsession('DB_datausu'))?>&id_usuario=<?=@$id_usuario?>&cgccpf=<?=$cgccpf?>" width="100%" height="270"></iframe>
   <?
 } else {
   ?>
     <iframe id="iframe" name="iframe" src="cai3_gerfinanc002.php?numcgm=<?=@$numcgm?>&matric=<?=@$matric?>&inscr=<?=@$inscr?>&tipo=<?=@$tipo?>&emrec=<?=@$emrec?>&agnum=<?=@$agnum?>&agpar=<?=@$agpar?>&db_datausu=<?=date('Y-m-d',db_getsession('DB_datausu'))?>&id_usuario=<?=@$id_usuario?>&cgccpf=<?=$cgccpf?>" width="100%" height="270"></iframe>
   <?
 }
 
?>
<br>


<?include("cai3_gerfinanc001.php");?>
</body>
</html>