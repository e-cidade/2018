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
$clunidades->rotulo->label();
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
 <tr>
  <td nowrap title="<?=@$Tsd02_i_codigo?>">
   <?=@$Lsd02_i_codigo?>
  </td>
  <td>
   <?db_input('sd02_i_codigo',10,$Isd02_i_codigo,true,'text',3,"")?>
   <?db_input('descrdepto',50,@$Idescrdepto,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tsd02_v_num_alvara?>">
   <?=$Lsd02_v_num_alvara?>
  </td>
  <td>
   <?db_input('sd02_v_num_alvara',60,$Isd02_v_num_alvara,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tsd02_d_data_exped?>">
   <?=$Lsd02_d_data_exped?>
  </td>
  <td>
   <?db_inputdata('sd02_d_data_exped',@$sd02_d_data_exped_dia,@$sd02_d_data_exped_mes,@$sd02_d_data_exped_ano,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tsd02_v_ind_orgexp?>">
   <?=$Lsd02_v_ind_orgexp?>
  </td>
  <td>
   <?
   $x = array(''=>'','1'=>'SES','2'=>'SMS');
   db_select('sd02_v_ind_orgexp',$x,true,$db_opcao,"");
   ?>
  </td>
 </tr>
</table>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
</center>
</form>