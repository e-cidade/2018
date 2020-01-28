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

//MODULO: educação
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clatoescola->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed05_i_codigo");
$clrotulo->label("ed18_i_codigo");
$db_botao1 = false;
if(isset($opcao) && $opcao=="alterar"){
 $db_opcao = 2;
 $db_botao1 = true;
}elseif(isset($opcao) && $opcao=="excluir" || isset($db_opcao) && $db_opcao==3){
 $db_botao1 = true;
 $db_opcao = 3;
 $color = "#DEB887";
}else{
 if(isset($alterar)){
  $db_opcao = 2;
  $db_botao1 = true;
 }else{
  $db_opcao = 1;
 }
}
$ed19_i_escola = db_getsession("DB_coddepto");
$result = $clescola->sql_record($clescola->sql_query("","ed18_c_nome",""," ed18_i_codigo = $ed19_i_escola"));
if($clescola->numrows>0){
 db_fieldsmemory($result,0);
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
 <tr>
  <td nowrap title="<?=@$Ted19_i_codigo?>">
   <?=@$Led19_i_codigo?>
  </td>
  <td>
   <?db_input('ed19_i_codigo',10,$Ied19_i_codigo,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted19_i_ato?>">
   <?db_ancora(@$Led19_i_ato,"",3);?>
  </td>
  <td>
   <?db_input('ed19_i_ato',10,$Ied19_i_ato,true,'text',3,'')?>
   <?db_input('ed05_c_finalidade',40,@$Ied05_c_finalidade,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted19_i_escola?>">
   <?db_ancora(@$Led19_i_escola,"",3);?>
  </td>
  <td>
   <?db_input('ed19_i_escola',10,$Ied19_i_escola,true,'text',3,'','',$color)?>
   <?db_input('ed18_c_nome',40,@$Ied18_c_nome,true,'text',3,'','',$color)?>
  </td>
 </tr>
</table>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="cancelar" type="submit" value="Cancelar" <?=($db_botao1==false?"disabled":"")?> >
<table>
 <tr>
  <td valign="top">
  <?
   $chavepri= array("ed19_i_codigo"=>@$ed19_i_codigo,
                    "ed19_i_escola"=>@$ed19_i_escola,
                    "ed18_c_nome"=>@$ed18_c_nome,
                    "ed19_i_ato"=>@$ed19_i_ato,
                    "ed05_c_finalidade"=>@$ed05_c_finalidade
                   );
   $cliframe_alterar_excluir->chavepri=$chavepri;
   @$cliframe_alterar_excluir->sql = $clatoescola->sql_query("","*","ed18_c_nome"," ed19_i_ato = $ed19_i_ato");
   @$cliframe_alterar_excluir->sql_disabled = $clatoescola->sql_query("","*","ed18_c_nome"," ed19_i_escola != $ed19_i_escola");
   $cliframe_alterar_excluir->campos  ="ed18_i_codigo,ed18_c_nome";
   $cliframe_alterar_excluir->legenda="Registros";
   $cliframe_alterar_excluir->msg_vazio ="Não foi encontrado nenhum registro.";
   $cliframe_alterar_excluir->textocabec ="#DEB887";
   $cliframe_alterar_excluir->textocorpo ="#444444";
   $cliframe_alterar_excluir->fundocabec ="#444444";
   $cliframe_alterar_excluir->fundocorpo ="#eaeaea";
   $cliframe_alterar_excluir->iframe_height ="150";
   $cliframe_alterar_excluir->iframe_width ="650";
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