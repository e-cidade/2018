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
include("classes/db_cgm_classe.php");
include("classes/db_empempenho_classe.php");
$clcgm = new cl_cgm;
$clempempenho = new cl_empempenho;
parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
db_logs("","",0,"Consulta Fornecedor.");

db_postmemory($_GET);

?>
<html>
<head>
<title>digitafornecedor.php</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="config/estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#FFFFFF" text="#000000" leftmargin="5" topmargin="5" marginwidth="0" marginheight="0">
<div id='int_perc1' align="left" style="position:absolute;top:60%;left:35%; float:left; width:200; background-color:#ECEDF2; padding:5px; margin:0px; border:1px #C2C7CB solid; margin-left:10px; font-size:80%; visibility:hidden">
  <div style="border:1px #ffffff solid; margin:8px 3px 3px 3px;">
   <div id='int_perc2' style="width:0%; background-color:#888888;" align="center">&nbsp;</div>
   </div>
  </div>
</div>
<script>
  document.getElementById('int_perc1').style.visibility='visible';
  document.getElementById('int_perc2').style.width='0%';
</script>
<table width="100%" border="1" bordercolor="#999999" cellpadding="2" cellspacing="0" class="texto">
<?



//verifica se está logado
if (@$numcgm!="") {
  if (@$tipo_consulta=="todos") {
   //ver todos
   $sql_seq = "";
  } 
  
  if (isset($nota_fiscal)) {
  
    $sql_seq = " and empnota.e69_numero = '{$nota_fiscal}'";

  } elseif (@$tipo_consulta=="abertos" || @$tipo_consulta=="") {
   //ver abertos
   $sql_seq = "and empempenho.e60_vlrpag = 0";
  } 
    
  //and empnota.e69_numero = 1788  
  //verifica se tem empenho pelo cgccpf e cgm
  
  $sSqlEmpenhos = $clempempenho->sql_query_notas("", 
                                                  "*",
                                                  "empempenho.e60_numemp DESC",
                                                  "empempenho.e60_numcgm = {$numcgm} {$sql_seq}"
                                                 );
  $rsEmpenhos  = $clempempenho->sql_record($sSqlEmpenhos);
  
  $linhas  = $clempempenho->numrows;
  if ($linhas == 0) {
   ?>
   <tr>
    <td align="center" height="100">
     <img src="imagens/atencao.gif"><br>
     Nenhum Empenho em Aberto para Numcgm <b><?=$numcgm?></b>
    </td>
   </tr>
   <?
  }else{
   ?>
   <tr bgcolor="#99bbff" class="bold4" align="center">
    <td>Empenho</td>
    <td>Dotação</td>
    <td>Emissão</td>
    <td>Ordem</td>
    <td>Nº Lic.</td>
    <td>NF</td>
    <td>Valor Emp.</td>
    <td>Valor Liq.</td>
    <td>Valor Pago</td>
    <td>Valor Anul.</td>
    <td>Instituição</td>
   </tr>
   <?
   $cor2="#99ccff";
   $cor3="#ffffcc";
   for ($x=0; $x<$linhas; $x++) {
     db_fieldsmemory($rsEmpenhos, $x);
     if ( $cor2 == "#f3f3f3" ) {
       $cor2 = "#99ccff";
     } else {
       $cor2 = "#f3f3f3";
     }
     //calcula percent
     $percent = ($x * 100)/$linhas;
     ?>
     <script> document.getElementById('int_perc2').style.width='<?=$percent?>%'; </script>
     <tr bgcolor="<?=$cor2?>" onmouseover="bgColor='<?=$cor3?>'" onmouseout="bgColor='<?=$cor2?>'" style="Cursor:hand" 
         title="Ver Detalhes" onclick="location='func_empempenho001.php?e60_numemp=<?=$e60_numemp?>'" >
       <td align="center">&nbsp;<?=$e60_numemp?></td>
       <td align="center">&nbsp;<?=$e60_coddot?></td>
       <td align="center">&nbsp;<?=db_formatar($e60_emiss,"d")?></td>
       <td align="center">&nbsp;<?=$e50_codord?></td>
       <td align="center">&nbsp;<?=$e60_numerol?></td>
       <td align="center">&nbsp;<?=$e69_numero?></td>
       <td align="right">&nbsp;<?=number_format($e60_vlremp,2,',','.')?></td>
       <td align="right">&nbsp;<?=number_format($e60_vlrliq,2,',','.')?></td>
       <td align="right">&nbsp;<?=number_format($e60_vlrpag,2,',','.')?></td>
       <td align="right">&nbsp;<?=number_format($e60_vlranu,2,',','.')?></td>
       <td align="center"><?=$codigo."-".$nomeinst?></td>
      </tr>
      <?
   }
  }
}else{
  ?>
  <tr height="220">
   <td align="center" class="red">
    <img src="imagens/atencao.gif"><br>
    Para acessar suas informações, efetue login.
   </td>
  </tr>
  <?
 }
?>
</table>
<script>
 document.getElementById('int_perc1').style.visibility='hidden';
 parent.document.getElementById('abertos').disabled = false;
 parent.document.getElementById('todos').disabled = false;
 parent.document.getElementById('imprimir').disabled = false;
</script>
</body>
</html>