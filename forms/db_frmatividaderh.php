<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: educação
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clatividaderh->rotulo->label();
$db_botao1 = false;

if (isset($opcao) && ($opcao == "alterar" || $opcao == "excluir")) {
  
  if (isset($ed01_i_funcaoadmin) && $ed01_i_funcaoadmin == "DIRETOR(A)") {
  	
  	$ed01_i_funcaoadmin = 2;
  	
  }	elseif (isset($ed01_i_funcaoadmin) && $ed01_i_funcaoadmin == "SECRETÁRIO(A)") {
  	
  	$ed01_i_funcaoadmin = 3;
  	
  } else {
  	$ed01_i_funcaoadmin = 1;
  }
	
}

if (isset($opcao) && $opcao=="alterar") {
	
  $db_opcao  = 2;
  $db_botao1 = true;

} elseif (isset($opcao) && $opcao=="excluir" || isset($db_opcao) && $db_opcao==3) {
	
  $db_botao1 = true;
  $db_opcao  = 3;

} else {
	
  if (isset($alterar)) {
  	
    $db_opcao  = 2;
    $db_botao1 = true;
    
  } else {
    $db_opcao = 1;
  }
  
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
 <tr>
  <td nowrap title="<?=@$Ted01_i_codigo?>">
   <?=@$Led01_i_codigo?>
  </td>
  <td>
   <?db_input('ed01_i_codigo',10,$Ied01_i_codigo,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted01_c_descr?>">
   <?=@$Led01_c_descr?>
  </td>
  <td>
   <?db_input('ed01_c_descr',50,$Ied01_c_descr,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted01_c_regencia?>">
   <?=@$Led01_c_regencia?>
  </td>
  <td>
   <?
   $x = array('N'=>'NÃO','S'=>'SIM');
   db_select('ed01_c_regencia',$x,true,$db_opcao,"");
   ?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted01_c_docencia?>">
   <?=@$Led01_c_docencia?>
  </td>
  <td>
   <?
   $x = array('N'=>'NÃO','S'=>'SIM');
   db_select('ed01_c_docencia',$x,true,$db_opcao,"");
   ?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted01_i_funcaoadmin?>">
    <?=@$Led01_i_funcaoadmin?>
  </td>
  <td>
    <?php
      $aFuncao = array(
                       "1" => "NÃO",
                       "2" => "DIRETOR(A)",
                       "3" => "SECRETÁRIO(A)"
                      );
      db_select('ed01_i_funcaoadmin', $aFuncao, true, $db_opcao, "");
    ?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted01_c_exigeato?>">
   <?=@$Led01_c_exigeato?>
  </td>
  <td>
   <?
   $x = array('N'=>'NÃO','S'=>'SIM');
   db_select('ed01_c_exigeato',$x,true,$db_opcao,"");
   ?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted01_c_efetividade?>">
   <?=@$Led01_c_efetividade?>
  </td>
  <td>
   <?
   $x = array('FUNC'=>'FUNCIONÁRIOS','PROF'=>'PROFESSORES');
   db_select('ed01_c_efetividade',$x,true,$db_opcao,"");
   ?>
  </td>
 </tr>
</table>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="cancelar" type="submit" value="Cancelar" <?=($db_botao1==false?"disabled":"")?> >
<table width='100%'>
 <tr>
  <td valign="top">
  <?
  
    $sCampos  = " ed01_i_codigo,ed01_c_descr,ed01_c_regencia,ed01_c_docencia,ed01_c_exigeato,ed01_c_efetividade, ";
    $sCampos .= " case when ed01_i_funcaoadmin = '1' then 'NÃO' ";
    $sCampos .= "      when ed01_i_funcaoadmin = '2' then 'DIRETOR(A)' ";
    $sCampos .= "      when ed01_i_funcaoadmin = '3' then 'SECRETÁRIO(A)' ";
    $sCampos .= "   end as ed01_i_funcaoadmin ";
    
    $aChaves  = array(
                      "ed01_i_codigo"      => @$ed01_i_codigo,
                      "ed01_c_descr"       => @$ed01_c_descr,
                      "ed01_c_regencia"    => @$ed01_c_regencia,
                      "ed01_c_docencia"    => @$ed01_c_docencia,
                      "ed01_c_exigeato"    => @$ed01_c_exigeato,
                      "ed01_c_efetividade" => @$ed01_c_efetividade,
                      "ed01_i_funcaoadmin" => @$ed01_i_funcaoadmin
                     );
    $cliframe_alterar_excluir->chavepri = $aChaves;
    @$cliframe_alterar_excluir->sql = $clatividaderh->sql_query($ed01_i_codigo, $sCampos,"ed01_c_descr");
    @$cliframe_alterar_excluir->sql_disabled = $clatividaderh->sql_query("","*","ed01_c_descr"," ed01_c_atualiz = 'N'");
    $cliframe_alterar_excluir->campos  ="ed01_i_codigo,ed01_c_descr,ed01_c_regencia,ed01_c_docencia,ed01_c_exigeato,ed01_c_efetividade,ed01_i_funcaoadmin";
    $cliframe_alterar_excluir->legenda="Registros";
    $cliframe_alterar_excluir->msg_vazio ="Não foi encontrado nenhum registro.";
    $cliframe_alterar_excluir->textocabec ="#DEB887";
    $cliframe_alterar_excluir->textocorpo ="#444444";
    $cliframe_alterar_excluir->fundocabec ="#444444";
    $cliframe_alterar_excluir->fundocorpo ="#eaeaea";
    $cliframe_alterar_excluir->iframe_height ="200";
    $cliframe_alterar_excluir->iframe_width ="100%";
    $cliframe_alterar_excluir->tamfontecabec = 9;
    $cliframe_alterar_excluir->tamfontecorpo = 9;
    $cliframe_alterar_excluir->formulario = false;
    $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
  
  ?>
  </td>
 </tr>
</table>
</form>
</center>