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

//MODULO: merenda
$clmer_desper_und->rotulo->label();
include("dbforms/db_classesgenericas.php");
$clrotulo = new rotulocampo;
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clrotulo->label("m61_descr");
$clrotulo->label("me22_i_codigo");
$clrotulo->label("me22_i_cardapiodiaescola");
$clrotulo->label("me01_c_nome");
$clrotulo->label("me12_d_data");
$clrotulo->label("me03_c_tipo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
 <tr>
  <td nowrap title="<?=@$Tme23_i_codigo?>">
   <?=@$Lme23_i_codigo?>
  </td>
  <td>
   <?db_input('me23_i_codigo',10,$Ime23_i_codigo,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tme22_i_cardapiodiaescola?>">
   <?=@$Lme22_i_cardapiodiaescola?>
  </td>
  <td>
   <?db_input('me01_c_nome',40,$Ime01_c_nome,true,'text',3,"")?>
   <?=@$Lme12_d_data?>
   <?db_inputdata('me12_d_data',@$me12_d_data_dia,@$me12_d_data_mes,@$me12_d_data_ano,true,'text',3,"")?>
   <?=@$Lme03_c_tipo?>
   <?db_input('me03_c_tipo',20,$Ime03_c_tipo,true,'text',3,"")?>
   <?db_input('me23_i_desperdicio',10,$Ime23_i_desperdicio,true,'hidden',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tme23_f_quant?>">
   <?=@$Lme23_f_quant?>
  </td>
  <td>
   <?db_input('me23_f_quant',10,$Ime23_f_quant,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tme23_i_unidade?>">
   <?db_ancora(@$Lme23_i_unidade,"js_pesquisame23_i_unidade(true);",$db_opcao);?>
  </td>
  <td>
   <?db_input('me23_i_unidade',10,$Ime23_i_unidade,true,'text',$db_opcao,
              " onchange='js_pesquisame23_i_unidade(false);'"
             )
   ?>
   <?db_input('m61_descr',40,$Im61_descr,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Tme23_t_obs?>">
   <?=@$Lme23_t_obs?>
  </td>
  <td>
   <?db_textarea('me23_t_obs',5,50,$Ime23_t_obs,true,'text',$db_opcao,"")?>
  </td>
 </tr>
</table>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
       type="submit" 
       id="db_opcao" 
       value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" >
<input name="cancelar" type="button" id="cancelar" value="Cancelar" onclick="js_cancela();">
<br><br>
<?
 $chavepri= array("me23_i_codigo"=>@$me23_i_codigo);
 $cliframe_alterar_excluir->chavepri=$chavepri;
 if (isset($me23_i_desperdicio)&&@$me23_i_desperdicio!="") {
   $cliframe_alterar_excluir->sql = $clmer_desper_und->sql_query(null,
                                                                 '*',
                                                                 null,
                                                                 " me23_i_desperdicio = $me23_i_desperdicio"
                                                                );
 }
 $campoiframe                             = " me23_i_codigo,me23_f_quant,m61_descr,me23_t_obs,me01_c_nome, ";
 $campoiframe                             .= " me12_d_data,me03_c_tipo"; 
 $cliframe_alterar_excluir->campos        = $campoiframe;
 $cliframe_alterar_excluir->legenda       ="Unidades de Desperdicio";
 $cliframe_alterar_excluir->msg_vazio     = "Não foi encontrado nenhum registro.";
 $cliframe_alterar_excluir->textocabec    = "darkblue";
 $cliframe_alterar_excluir->textocorpo    = "black";
 $cliframe_alterar_excluir->fundocabec    = "#aacccc";
 $cliframe_alterar_excluir->fundocorpo    = "#ccddcc";
 $cliframe_alterar_excluir->iframe_width  = "100%";
 $cliframe_alterar_excluir->iframe_height = "130";
 $cliframe_alterar_excluir->opcoes = 1;
 $cliframe_alterar_excluir->iframe_alterar_excluir(1);
?>
</center>
</form>
<script>
function js_pesquisame23_i_unidade(mostra) {
	
  if (mostra==true) {
	  
    js_OpenJanelaIframe('','db_iframe_matunid',
    	                'func_matunid.php?funcao_js=parent.js_mostramatunid1|m61_codmatunid|m61_descr','Pesquisa',true
    	               );
    
  } else {
	  
    if (document.form1.me23_i_unidade.value != '') {
        
      js_OpenJanelaIframe('','db_iframe_matunid',
    	                  'func_matunid.php?pesquisa_chave='+document.form1.me23_i_unidade.value+
    	                  '&funcao_js=parent.js_mostramatunid','Pesquisa',false
    	                 );
      
    } else {
      document.form1.m61_descr.value = '';
    }
  }
}

function js_mostramatunid(chave,erro) {
	
  document.form1.m61_descr.value = chave;
  if (erro==true) {
	  
    document.form1.me23_i_unidade.focus();
    document.form1.me23_i_unidade.value = '';
    
  }
}

function js_mostramatunid1(chave1,chave2) {
	
  document.form1.me23_i_unidade.value = chave1;
  document.form1.m61_descr.value = chave2;
  db_iframe_matunid.hide();
  
}

function js_cancela() {
  location.href="mer1_mer_desper_und001.php?me23_i_desperdicio=<?=$me23_i_desperdicio?>&me01_c_nome=<?=$me01_c_nome?>
	             &me12_d_data=<?=$me12_d_data?>&me03_c_tipo=<?=$me03_c_tipo?>";
}
</script>