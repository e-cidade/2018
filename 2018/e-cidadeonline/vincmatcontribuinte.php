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

$usuario = db_getsession("DB_login");
 
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
  <td></td>
</tr>
<?
  $sqlCgm = "  select rh01_regist,
               rh01_admiss,
               rh01_funcao,
               rh05_recis
          from rhpessoal 
               inner join rhpessoalmov  on rh02_regist = rh01_regist
                                       and rh02_anousu = 2009
                                       and rh02_mesusu = 08 
               left  join rhpesrescisao on rh05_seqpes = rh02_seqpes  
         where rh01_numcgm = '{$usuario}' 
           and rh05_seqpes is null ";
           
  //die($sqlCgm);
  $result = db_query($sqlCgm);
  $linhas = pg_numrows($result); 

  if($linhas == 0){
   ?>
   <tr>
    <td align="center" height="100">
     <img src="imagens/atencao.gif"><br>
       Nenhum Registro para Numcgm <b><?=$numcgm?></b>
    </td>
   </tr>
   <?
  } else {
   ?>
   <tr bgcolor="#99bbff" class="bold4" align="center">
   <td>SELECIONE A MATRICULA DESEJADA:</td>
    <td>N° MATRICULA</td>
    <td>DATA ADMISSÃO</td>
    <td>CARGO</td>
    <td>DEMISSÃO</td>
   </tr>
   <?
   $cor2="#99ccff";
   $cor3="#ffffcc";
   
   for($x=0;$x<$linhas;$x++){
   	
    db_fieldsmemory($result,$x);
    
    if( $cor2 == "#f3f3f3" )
     $cor2 = "#99ccff";
    else
     $cor2 = "#f3f3f3";
    
    $percent = ($x * 100)/$linhas;
    ?>
    <script> document.getElementById('int_perc2').style.width='<?=$percent?>%'; </script>
    <tr bgcolor="<?=$cor2?>" onmouseover="bgColor='<?=$cor3?>'" onmouseout="bgColor='<?=$cor2?>'" 
        style="Cursor:hand" title="Ver Detalhes" onclick="">
     <td align="center">&nbsp;<?= $rh01_regist; ?></td>
     <td align="center">&nbsp;<?= $rh01_admiss; ?></td>
     <td align="center">&nbsp;<?= $rh01_funcao; ?></td>
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
