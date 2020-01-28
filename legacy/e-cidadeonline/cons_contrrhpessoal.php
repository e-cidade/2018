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

include("libs/db_conecta.php");
include("libs/db_stdlib.php");
include("libs/db_utils.php");
include("dbforms/db_funcoes.php");
include("libs/db_sql.php");

$numcgm   = db_getsession("DB_login");
$anoFolha = db_anofolha();
$mesFolha = db_mesfolha();

?>
<html>
<title></title>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="config/estilos.css" rel="stylesheet" type="text/css">
</head>
<body>
<table width="100%" border="1" bordercolor="#999999" cellpadding="2" cellspacing="0" class="texto">
<tr>
  <td align="center"><b>** SELECIONE A MATRICULA DESEJADA **</b></td>
</tr>
</table>
<table width="100%" border="1" bordercolor="#999999" cellpadding="2" cellspacing="0" class="texto">
<?

  $sqlPermConsServ = " select * 
                         from configdbpref 
                        where w13_permconsservdemit = true ";
  
  //die($sqlPermConsServ);
  $rPermConsServ = db_query($sqlPermConsServ);
  $tPermConsServ = pg_numrows($rPermConsServ); 
  
 if ($tPermConsServ == 0) {
 	   $sDtDemissao = " and rh05_seqpes is not null ";
 } else {
 	   $sDtDemissao = "";
 }

  $sqlRhCgmCont = " select distinct 
                           rh01_regist,
                           rh37_descr,
                           rh01_admiss,
                           rh01_funcao,
                           rh05_recis
                      from rhpessoal 
                           inner join rhpessoalmov  on rh02_regist = rh01_regist
                                                   and rh02_anousu = {$anoFolha}
                                                   and rh02_mesusu = {$mesFolha} 
                           left  join rhpesrescisao on rh05_seqpes = rh02_seqpes 
                           inner join rhfuncao      on rh01_funcao = rh37_funcao
                     where rh01_numcgm = '{$numcgm}' {$sDtDemissao} ";
           
  //die($sqlRhCgmCont);
  $rRhCgmCont = db_query($sqlRhCgmCont);
  $tRhCgmCont = pg_numrows($rRhCgmCont);  

  if($tRhCgmCont == 0){
   ?>
   <tr>
    <td align="center" height="100">
     <img src="imagens/atencao.gif"><br>
       <b>NENHUM REGISTRO ENCONTRADO!</b>
    </td>
   </tr>
   <?
  } else {
   ?>
   <tr bgcolor="#99bbff" class="bold4" align="center">
    <td>N° MATRICULA</td>
    <td>DATA ADMISSÃO</td>
    <td>CARGO</td>
    <td>DATA DEMISSÃO</td>
   </tr>
   <?
		   $corFundo = "#99ccff";
		   $corOver  = "#ffffcc";
		   
		   for ($x = 0; $x < $tRhCgmCont; $x++) {
		   	
		     if ($tRhCgmCont > 0) {
              db_fieldsmemory($rRhCgmCont,0);   
         }
         
		     if ( $corFundo == "#f3f3f3" ) {
		          $corFundo = "#99ccff";
		     } else {
		          $corFundo = "#f3f3f3";
		     }
		          $sTamanhoCell = ($x * 100)/$tRhCgmCont;
    ?>
    <script> document.getElementById('int_perc2').style.width='<?=$sTamanhoCell?>%'; </script>
    <tr bgcolor="<?=$corFundo?>" onmouseover="bgColor='<?=$corOver?>'" onmouseout="bgColor='<?=$corFundo?>'" 
        style="Cursor:hand" title="Ver Detalhes" onclick="">
     <td align="center">&nbsp;<?= $rh01_regist; ?></td>
     <td align="center">&nbsp;<?= $rh01_admiss; ?></td>
     <td align="center">&nbsp;<?= $rh37_descr; ?></td>
     <td align="center">&nbsp;<?= $rh05_recis;  ?></td>  
    </tr>
    <?
     }
    ?>
</table>
<?
}
?>
</body>
</html>
