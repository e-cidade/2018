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

//MODULO: agua
include("dbforms/db_classesgenericas.php");
$claguacortematnumpre->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label("x44_vlrhis");
$clrotulo->label("x44_vlrcor");
$clrotulo->label("x44_juros");
$clrotulo->label("x44_multa");
$clrotulo->label("x44_desconto");
$clrotulo->label("x44_total");


?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td valign="top"  align="center">  
    <?

	if(@$tipo=="Analitico" || !isset($tipo)) { 
	  	$tipo = "Sintetico";
		$sql1 =
		    "select
			   x44_numpre,
		       x44_numpar,
		       x44_dtvenc,
		       k00_descr,
		       k02_descr,
		       x44_vlrhis,
		       x44_vlrcor,
		       x44_juros,
		       x44_multa,
		       x44_desconto,
		       (x44_vlrcor+x44_juros+x44_multa-x44_desconto) as x44_total
		     from aguacortematnumpre
		     inner join arretipo on k00_tipo = x44_tipo
		     inner join tabrec on k02_codigo = x44_receit
		     where x44_codcortemat = $x44_codcortemat
			 order by x44_numpar, x44_dtvenc, x44_numpre, k02_codigo";
		
	} else {
	  	$tipo = "Analitico";
		$sql1 =
		    "select
			   x44_numpre,
		       x44_numpar,
		       x44_dtvenc,
		       k00_descr,
		       sum(x44_vlrhis) as x44_vlrhis,
		       sum(x44_vlrcor) as x44_vlrcor,
		       sum(x44_juros) as x44_juros,
		       sum(x44_multa) as x44_multa,
		       sum(x44_desconto) as x44_desconto,
		       sum(x44_vlrcor+x44_juros+x44_multa-x44_desconto) as x44_total
		     from aguacortematnumpre
		     inner join arretipo on k00_tipo = x44_tipo
		     inner join tabrec on k02_codigo = x44_receit
		     where x44_codcortemat = $x44_codcortemat
			 group by x44_numpar, x44_dtvenc, x44_numpre, k00_descr";

	}

	db_lovrot($sql1, 15, "()", "", "");
    ?>
    </td>
   </tr>
 </table>

<table border="1">
  <tr></tr><tr></tr>
  <tr>
    <?

	$sql2 = "
			select 
		       sum(x44_vlrhis) as x44_vlrhis,
		       sum(x44_vlrcor) as x44_vlrcor,
		       sum(x44_juros) as x44_juros,
		       sum(x44_multa) as x44_multa,
		       sum(x44_desconto) as x44_desconto,
		       sum(x44_vlrcor+x44_juros+x44_multa-x44_desconto) as x44_total
		     from aguacortematnumpre
		     inner join arretipo on k00_tipo = x44_tipo
		     inner join tabrec on k02_codigo = x44_receit
		     where x44_codcortemat = $x44_codcortemat";

	//db_lovrot($sql2, 15, "()", "", "");
	$result = pg_exec($sql2);
	$rows   = pg_num_rows($result);
	
	db_fieldsmemory($result, 0);

    ?>
	<td align="right"><?=@$Lx44_vlrhis?></td>
	<td align="right"><?=@$Lx44_vlrcor?></td>
	<td align="right"><?=@$Lx44_juros?></td>
	<td align="right"><?=@$Lx44_multa?></td>
	<td align="right"><?=@$Lx44_total?></td>
   </tr>
   <tr>
	<td align="right"><?=db_formatar(@$x44_vlrhis,'f')?></td>
	<td align="right"><?=db_formatar(@$x44_vlrcor,'f')?></td>
	<td align="right"><?=db_formatar(@$x44_juros,'f')?></td>
	<td align="right"><?=db_formatar(@$x44_multa,'f')?></td>
	<td align="right"><?=db_formatar(@$x44_total,'f')?></td>

   </tr>
  <tr></tr><tr></tr>
 </table>

</center>
<input name="tipo" type="submit" id="tipo" value=<?=isset($tipo)?$tipo:"Sintetico"?>  >

</form>