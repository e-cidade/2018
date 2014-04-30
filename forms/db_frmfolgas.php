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

//MODULO: saude
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clmedicos->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("sd06_i_medico");
$clrotulo->label("sd06_i_unidade");
$result = $clmedicos->sql_record($clmedicos->sql_query("","*",""," sd03_i_codigo = $sd03_i_codigo"));
db_fieldsmemory($result,0);
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
 <tr>
  <td nowrap title="<?=@$Tsd06_i_medico?>">
   <?db_ancora(@$Lsd06_i_medico,"",3);?>
  </td>
  <td>
   <?db_input('sd03_i_codigo',10,$Isd03_i_codigo,true,'text',3,"")?>
   <?db_input('z01_nome',80,@$Iz01_nome,true,'text',3,'')?>
 </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tsd04_d_folgaini?>">
   <?=@$Lsd04_d_folgaini?>
  </td>
  <td>
   <?db_inputdata('sd04_d_folgaini',@$sd04_d_folgaini_dia,@$sd04_d_folgaini_mes,@$sd04_d_folgaini_ano,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tsd04_d_folgafim?>">
   <?=@$Lsd04_d_folgafim?>
  </td>
  <td>
   <?db_inputdata('sd04_d_folgafim',@$sd04_d_folgafim_dia,@$sd04_d_folgafim_mes,@$sd04_d_folgafim_ano,true,'text',$db_opcao,"")?>
  </td>
 </tr>
</table>
</center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
</form>