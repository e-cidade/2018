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
$clsau_vinculosus->rotulo->label();
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
 <tr>
  <td nowrap title="<?=@$Tsd50_i_codigo?>">
   <?=@$Lsd50_i_codigo?>
  </td>
  <td>
   <?db_input('sd50_i_codigo',10,$Isd50_i_codigo,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tsd50_i_unidade?>">
   <?=@$Lsd50_i_unidade?>
  </td>
  <td>
   <?db_input('sd50_i_unidade',10,$Isd50_i_unidade,true,'text',3,"")?>
   <?db_input('descrdepto',50,@$Idescrdepto,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tsd50_v_contratosus?>">
   <?=@$Lsd50_v_contratosus?>
  </td>
  <td>
   <?db_input('sd50_v_contratosus',60,$Isd50_v_contratosus,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tsd50_d_publicacao?>">
   <?=@$Lsd50_d_publicacao?>
  </td>
  <td>
   <?db_inputdata('sd50_d_publicacao',@$sd50_d_publicacao_dia,@$sd50_d_publicacao_mes,@$sd50_d_publicacao_ano,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tsd50_v_contratosus2?>">
   <?=@$Lsd50_v_contratosus2?>
  </td>
  <td>
   <?db_input('sd50_v_contratosus2',60,$Isd50_v_contratosus2,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tsd50_d_publicacao2?>">
   <?=@$Lsd50_d_publicacao2?>
  </td>
  <td>
   <?db_inputdata('sd50_d_publicacao2',@$sd50_d_publicacao2_dia,@$sd50_d_publicacao2_mes,@$sd50_d_publicacao2_ano,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tsd50_v_banco?>">
   <?=@$Lsd50_v_banco?>
  </td>
  <td>
   <?db_input('sd50_v_banco',3,$Isd50_v_banco,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tsd50_v_agencia?>">
   <?=@$Lsd50_v_agencia?>
  </td>
  <td>
   <?db_input('sd50_v_agencia',5,$Isd50_v_agencia,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tsd50_v_cc?>">
   <?=@$Lsd50_v_cc?>
  </td>
  <td>
   <?db_input('sd50_v_cc',14,$Isd50_v_cc,true,'text',$db_opcao,"")?>
  </td>
 </tr>
</table>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
</form>
</center>