<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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
include_once("libs/db_sessoes.php");
include_once("libs/db_usuariosonline.php");
include_once("dbforms/db_funcoes.php");
include_once("dbforms/db_classesgenericas.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clveiculoscomb = new cl_veiculoscomb;
$clveiccadcomb  = new cl_veiccadcomb;

$cont_veic_comb = 0;

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="../estilos.css" rel="stylesheet" type="text/css">
<style>
.bordas{
         border: 2px solid    #cccccc;
         border-top-color:    #999999;
         border-right-color:  #999999;
         border-bottom-color: #999999;
         background-color:    #999999;
}
.bordas_corp{
              border: 1px solid    #cccccc;
              border-right-color:  #999999;
              border-left-color:   #999999;
              border-bottom-color: #999999;
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table align="center" border="1" cellspacing="0" cellpadding="0" width="430">
 <form name="form2" action=""> 
 <tr class="bordas_corp">
    <td nowrap align="center" class="bordas" width="80">Selecione</td>
    <td nowrap class="bordas">Descrição</td>
    <td nowrap align="center" class="bordas" width="50">Padrão</td>
    </td>
 </tr>
 <?
if (isset($ve06_veiculos)){
$sSqlVeiculos  = " select (select ve06_sequencial ";
$sSqlVeiculos .= "           from veiculoscomb ";
$sSqlVeiculos .= "          where veiculoscomb.ve06_veiccadcomb = veiccadcomb.ve26_codigo ";
$sSqlVeiculos .= "            and veiculoscomb.ve06_veiculos = {$ve06_veiculos} limit 1) as ve06_sequencial,"; 
$sSqlVeiculos .= "        ve26_codigo, ";
$sSqlVeiculos .= "        ve26_descr , ";
$sSqlVeiculos .= "        (select 'checked' ";
$sSqlVeiculos .= "           from veiculoscomb  ";
$sSqlVeiculos .= "          where veiculoscomb.ve06_veiccadcomb = veiccadcomb.ve26_codigo ";
$sSqlVeiculos .= "            and veiculoscomb.ve06_veiculos = {$ve06_veiculos} limit 1) as marcado, ";
$sSqlVeiculos .= "        (select case ";
$sSqlVeiculos .= "                  when ve06_padrao is true then 'checked' ";
$sSqlVeiculos .= "                  else '' ";
$sSqlVeiculos .= "                end as radiomarcado ";
$sSqlVeiculos .= "           from veiculoscomb ";
$sSqlVeiculos .= "          where veiculoscomb.ve06_veiccadcomb = veiccadcomb.ve26_codigo ";
$sSqlVeiculos .= "            and veiculoscomb.ve06_veiculos = {$ve06_veiculos} limit 1) as radiomarcado ";
$sSqlVeiculos .= "   from veiccadcomb ";

$rsVeiculos = db_query($sSqlVeiculos);
$iNumRows = pg_num_rows($rsVeiculos);
//echo "$sSqlVeiculos";exit;
 for ($i=0; $i < $iNumRows;$i++) {
   db_fieldsmemory($rsVeiculos,$i);
   if ($ve26_codigo != '') { 
     $value = "inc".$ve26_codigo;
   }else{
     $value = "comb".$i;     
   }

?>
   <tr class="bordas_corp">
     <td nowrap align="center" class="bordas_corp">
       <input name='chk_comb' type="checkbox" value="<?=$ve26_codigo?>" <?=$marcado?> > </td>
     <td nowrap class="bordas_corp" id="descr_<?=$ve26_codigo?>">
       <?=$ve26_descr?>
     </td>
     <td nowrap align="center" class="bordas_corp">
       <input  name='rd_comb' type='radio' value="<?=$value?>"  <?=$radiomarcado?>>
     </td>
   </tr>
<? 
}
}
else{
  $sSqlVeiculos  =  "  select * from veiccadcomb order by ve26_descr ";
  $rsVeiculos = db_query($sSqlVeiculos);
  $iNumRows = pg_num_rows($rsVeiculos);

 for ($i=0; $i < $iNumRows;$i++) {
   db_fieldsmemory($rsVeiculos,$i);
   $value = "inc".$ve26_codigo;
?>
<tr class="bordas_corp">
     <td nowrap align="center" class="bordas_corp">
       <input name='chk_comb' type="checkbox" value="<?=$ve26_codigo?>"  > </td>
     <td nowrap class="bordas_corp" id="descr_<?=$ve26_codigo?>">
       <?=$ve26_descr?>
     </td>
     <td nowrap align="center" class="bordas_corp">
       <input  name='rd_comb' type='radio' value="<?=$value?>"  >
     </td>
   </tr>
<?
}
}

?>


</table>
</form>
</body>
</html>