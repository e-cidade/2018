<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

///bgcolor="#E6E6E6" primeira tabela
///coloquei o case e troquei a variavel fa06_f_quant por fa09_f_ quant e ( titulofa06_i_retirada por fa04_i_codigo)///
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_far_retirada_classe.php");
include("classes/db_far_retiradaitens_classe.php");
db_postmemory($HTTP_POST_VARS);

$clrotulo = new rotulocampo;

$clfar_retiradaitens = new cl_far_retiradaitens;
$clfar_retirada = new cl_far_retirada;
//die($clfar_retiradaitens->sql_query_retiradaitens(null,"fa06_i_retirada,m60_descr,m77_lote,m77_dtvalidade,m61_descr,case when fa09_f_quant is null then fa06_f_quant else fa09_f_quant end as fa09_f_quant","fa09_f_quant","fa06_i_retirada=$chavepesquisaconsulta")); 
$result = $clfar_retiradaitens->sql_record($clfar_retiradaitens->sql_query_retiradaitens(null,"fa06_i_retirada,fa06_t_posologia,m60_descr,m77_lote,m77_dtvalidade,m61_descr,case when fa09_f_quant is null then fa06_f_quant else fa09_f_quant end as fa09_f_quant","fa09_f_quant","fa06_i_retirada=$chavepesquisaconsulta"));
db_fieldsmemory($result,0);
?>
<html>
<head>
<title>Retirada</title>
</head>
<body>
<center>
<br><br><br>
<table border="0" width="80%" id="table1" cellspacing="0" cellpadding="0" >
 <tr>
  <td colspan="2"><font size="4"><b> Retirada &nbsp;&nbsp;<?=$fa06_i_retirada?></b></font></td>
 </tr>
 <tr>
  <td colspan="2">&nbsp;</td>
 </tr>
 <tr>
  <td colspan="2">
  <table border="0" width="100%" id="table2" cellspacing="4" cellpadding="0">
   <tr>
    <td bgcolor="#E6E6E6"><b><font size="4">Código</b></font></td>
    <td bgcolor="#E6E6E6"><b><font size="4">Medicamento</b></font></td>
    <td bgcolor="#E6E6E6"><b><font size="4">Lote</b></font></td>
    <td bgcolor="#E6E6E6" width="96"><b><font size="4">Validade</b></font></td>
    <td bgcolor="#E6E6E6" width="98"><b><font size="4">Unidade</b></font></td>
    <td bgcolor="#E6E6E6" width="65"><b><font size="4">Quant.</b></font></td>
    <td bgcolor="#E6E6E6" width="100"><b><font size="4">Posologia</b></font></td>
   </tr>
   <?
   for($i=0;$i<$clfar_retiradaitens->numrows;$i++){
 db_fieldsmemory($result,$i);
?>	
   <tr>
    <td bgcolor="#E6E6E6"><?=$fa06_i_retirada?></td>
    <td bgcolor="#E6E6E6"><?=$m60_descr?></td>
    <td bgcolor="#E6E6E6"><?=$m77_lote?></td>
    <td bgcolor="#E6E6E6" width="96"><?=db_formatar($m77_dtvalidade,'d')?></td>
    <td bgcolor="#E6E6E6" width="98"><?=$m61_descr ?></td>
    <td bgcolor="#E6E6E6" width="65"><?=$fa09_f_quant?></td>
    <td bgcolor="#E6E6E6" width="100"><?=$fa06_t_posologia?></td>  
   </tr>
   <?}
?>
  </table>
  </td>
 </tr>
 <tr>
  <td colspan="2">&nbsp;</td>
 </tr>
 <tr>
  <td width="66%">&nbsp;</td>
  <td width="34%"><input name="ok" type="button" id="ok" value="OK" onclick='parent.mo_camada("a2");parent.document.formaba.a1.disabled = false;parent.document.formaba.a2.disabled = false;parent.document.formaba.a3.disabled = true;parent.iframe_a2.location.href="far3_consultaretirada001.php?chavepesquisaretirada=<?=$chavepesquisaconsulta?>&fa04_i_cgsund=<?=$fa04_i_cgsund?>"'></td>
 </tr>
 <tr>
  <td width="66%">
  <p align="right">&nbsp;</td>
  <td width="34%">&nbsp;</td>
 </tr>
</table>
</center>
</body>
</html>