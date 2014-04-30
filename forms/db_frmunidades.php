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
$clrotulo = new rotulocampo;
$clrotulo->label("z01_cgccpf");
$clrotulo->label("z01_ender");
$clrotulo->label("z01_numero");
$clrotulo->label("z01_compl");
$clrotulo->label("z01_bairro");
$clrotulo->label("z01_munic");
$clrotulo->label("z01_telef");
?>
<form name="form1" method="post" action="">
<center>
<fieldset style="width: 65%;"><legend><b>Identificação</b></legend>
<table border="0">
 <tr>
  <td>
   <table border="0" width="100%" cellspacing="1">
    <tr>
     <td>
      <?db_ancora(@$Lsd02_i_codigo,"js_pesquisasd02_i_codigo(true);",$db_opcao1);?>
     </td>
     <td colspan="4">
     <?db_input('sd02_i_codigo',10,$Isd02_i_codigo,true,'text',$db_opcao1," onchange='js_pesquisasd02_i_codigo(false);'")?>
     <?db_input('descrdepto',72,@$Idescrdepto,true,'text',3,"")?>
     </td>
    </tr>
    <tr>
     <td nowrap width="21%" title="<?=@$Tsd02_v_cnes?>">
     <?=$Lsd02_v_cnes?>
     </td>
     <td width="15%" >
     <?db_input('sd02_v_cnes',10,$Isd02_v_cnes,true,'text',$db_opcao,"")?>
     </td>
     <td nowrap width="41%" title="<?=@$Tz01_cgccpf?>" align="right">
      <b>CNPJ/CPF - Mantenedora:</b>
      </td>
      <td width="25%" >
      <?db_input('z01_cgccpf',16,@$Iz01_cgccpf,true,'text',$db_opcao,"")?>
     </td>
    </tr>
    
     <tr>
     <td nowrap title="<?=@$Tsd02_i_situacao?>" height="20">
      <?=$Lsd02_i_situacao?>
     </td>
     <td colspan="3">
      <?
      $x = array('1'=>'INDIVIDUAL','2'=>'MANTIDO');
      db_select('sd02_i_situacao',$x,true,$db_opcao,"style='font-size:9px;height:15px;width:80px;'");
      ?>
     </td>
    </tr>
    <tr>
     <td>
      <?db_ancora(@$Lsd02_i_tp_unid_id,"js_pesquisasd02_i_tp_unid_id(true);",$db_opcao);?>
      </td>
      <td colspan="4">
      <?db_input('sd02_i_tp_unid_id',10,$Isd02_i_tp_unid_id,true,'text',3,"onchange='js_pesquisasd02_i_tp_unid_id(false);'")?>
      <?db_input('sd42_v_descricao',72,@$Isd42_v_descricao,true,'text',3,"")?>
     </td>
     </tr>
    <tr>
     <td nowrap title="<?=@$Tsd02_i_numcgm?>" height="20">
      <?db_ancora(@$Lsd02_i_numcgm,"js_pesquisasd02_i_numcgm(true);",$db_opcao);?>
     </td>
     <td colspan="4">
      <?db_input('sd02_i_numcgm',10,$Isd02_i_numcgm,true,'text',3," onchange='js_pesquisasd02_i_numcgm(false);'")?>
      <?db_input('z01_nome',72,@$Iz01_nome,true,'text',3,"")?>
     </td>
    </tr>
    <tr>
    <td nowrap title="">
      <b>Pessoa:</b>
     </td>
     <td>
     <?
      if((isset($z01_cgccpf) && strlen($z01_cgccpf)==11) || (isset($z01_cgccpf) && strlen($z01_cgccpf)=="")){
       $pessoa = "FÍSICA";
      }elseif((isset($z01_cgccpf) && strlen($z01_cgccpf)==14)){
       $pessoa = "JURÍDICA";
      }else{
       $pessoa = "";
      }
      ?>
    <?db_input('pessoa',10,@$Ipessoa,true,'text',3,"")?>
     </td>
     <td nowrap title="<?=@$Tz01_cgccpf?>" align="right">
      <b>CNPJ/CPF - Estabelecimento:</b>
      </td>
      <td>
      <?db_input('z01_cgccpf',16,@$z01_cgccpf,true,'text',3,"")?>
     </td>
    </tr>
    <tr>
     <td nowrap title="<?=@$Tz01_nomefant?>" height="20" >
      <b>Nome Fantasia:</b>
     </td>
     <td colspan="4">
      <?db_input('z01_nomefant',86,@$Iz01_nomefant,true,'text',3)?>
     </td>
    </tr>
    <tr>
     <td nowrap title="<?=@$Tz01_ender?>" height="20">
      <?=$Lz01_ender?>
     </td>
     <td colspan="4">
      <?db_input('z01_ender',86,@$Iz01_ender,true,'text',3,"")?>
     </td>
    </tr>
    
    <tr>
     <td nowrap title="<?=@$Tz01_numero?>" height="20">
      <?=$Lz01_numero?>
     </td>
     <td>
      <?db_input('z01_numero',10,@$Iz01_numero,true,'text',3,"")?>
     </td>
     <td nowrap title="<?=@$Tz01_compl?>" align="right">
      <?=$Lz01_compl?>
      </td>
      <td>
      <?db_input('z01_compl',16,@$Iz01_compl,true,'text',3,"")?>
     </td>
    </tr>
     <tr>
     <td nowrap title="<?=@$Tz01_bairro?>" height="20">
      <?=$Lz01_bairro?>
     </td>
     <td colspan="4">
      <?db_input('z01_bairro',86,@$Iz01_bairro,true,'text',3,"")?>
     </td>
    </tr>
    <tr>
     <td nowrap title="<?=@$Tz01_munic?>" height="20">
      <?=$Lz01_munic?>
     </td>
     <td colspan=3>
      <?db_input('z01_munic',86,@$Iz01_munic,true,'text',3,"")?>
     </td>
    </tr>
    <tr>
     <td nowrap title="<?=@$Tsd02_i_cidade?>" height="20">
      <?=$Lsd02_i_cidade?>
      </td>
      <td colspan="3">
      <?db_input('sd02_i_cidade',10,$Isd02_i_cidade,true,'text',$db_opcao,"")?>
     </td>
     </tr>
     <tr>
     <td nowrap title="<?=@$Tsd02_i_regiao?>" height="20">
      <?=$Lsd02_i_regiao?>
     </td>
     <td>
      <?db_input('sd02_i_regiao',10,$Isd02_i_regiao,true,'text',$db_opcao,"")?>
     </td>
     <td nowrap title="<?=@$Tsd02_v_microreg?>" height="20" align="right">
      <?=$Lsd02_v_microreg?>
      </td>
      <td>
      <?db_input('sd02_v_microreg',16,$Isd02_v_microreg,true,'text',$db_opcao,"")?>
     </td>
    </tr>
     <tr>
     <td nowrap title="<?=@$Tsd02_i_distrito?>" height="20">
      <?db_ancora(@$Lsd02_i_distrito,"js_pesquisasd02_i_distrito(true);",$db_opcao);?>
     </td>
     <td nowrap>
      <?
      db_input('s153_c_codigo',10,$Isd02_i_distrito,true,'text',$db_opcao,"onchange='js_pesquisasd02_i_distrito(false);'");
      db_input('sd02_i_distrito',1,$Isd02_i_distrito,true,'hidden',$db_opcao,'');
      db_input('s153_c_descr',20, '',true,'text',3,'');
      ?>
     </td>
     <td nowrap title="<?=@$Tsd02_v_distadmin?>" height="20" align="right">
      <?=$Lsd02_v_distadmin?>
      </td>
      <td>
      <?db_input('sd02_v_distadmin',16,$Isd02_v_distadmin,true,'text',$db_opcao,"")?>
     </td>
    </tr>
    <tr>
     <td nowrap title="<?=@$Tsd02_c_siasus?>" height="20">
      <?=$Lsd02_c_siasus?>
     </td>
     <td>
      <?db_input('sd02_c_siasus',6,$Isd02_c_siasus,true,'text',$db_opcao,"")?>
     </td>
     <td nowrap title="<?=@$Tz01_telef?>" height="20" align="right">
      <?=$Lz01_telef?>
      </td>
      <td>
      <?db_input('z01_telef',16,@$Iz01_telef,true,'text',$db_opcao,"")?>
     </td>
    </tr>
    <tr>
     <td>
      <?db_ancora(@$Lsd02_i_diretor,"js_pesquisasd02_i_diretor(true);",$db_opcao);?>
      </td>
      <td colspan="4">
      <?db_input('sd02_i_diretor',10,$Isd02_i_diretor,true,'text',$db_opcao,"onchange='js_pesquisasd02_i_diretor(false);'")?>
      <?db_input('diretor',72,@$diretor,true,'text',3,"")?>
     </td>
     </tr>
      <tr>
     <td nowrap title="<?=@$Tsd02_v_diretorreg?>" height="20">
      <?=$Lsd02_v_diretorreg?>
      </td>
      <td>
      <?db_input('sd02_v_diretorreg',10,$Isd02_v_diretorreg,true,'text',$db_opcao,"")?>
     </td>
     
     <td nowrap title="<?=@$Tsd02_c_centralagenda?>" height="20" align="right">
      <?=$Lsd02_c_centralagenda?>
      </td>
      <td>
      <?
      $x = array('N'=>'NÃO','S'=>'SIM');
      db_select('sd02_c_centralagenda',$x,true,$db_opcao,"style='font-size:9px;height:15px;width:80px;'");
      ?>
     </td>
    
     
    </tr>
    <tr>
     <td colspan="4" align="center" valign="top">
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
      <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();"  <?=($db_opcao==1?"disabled":"")?>>
     </td>
    </tr>
   </table>
  </td>
 </tr>
</table>
</fieldset>
</center>
</form>
<script>
function js_pesquisasd02_i_diretor(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('','func_nome','func_cgm.php?funcao_js=parent.js_mostrasd02_i_diretor1|z01_numcgm|z01_nome','Pesquisa de CGM',true);
 }else{
  if(document.form1.sd02_i_diretor.value != ''){
   js_OpenJanelaIframe('','func_nome','func_cgm.php?pesquisa_chave='+document.form1.sd02_i_diretor.value+'&funcao_js=parent.js_mostrasd02_i_diretor','Pesquisa',false);
  }
 }
}
function js_mostrasd02_i_diretor(erro,chave){
document.form1.diretor.value = chave;
 if(erro==true){
  document.form1.sd02_i_diretor.focus();
  document.form1.sd02_i_diretor.value = '';
 }
}
function js_mostrasd02_i_diretor1(chave1,chave2){
 document.form1.sd02_i_diretor.value = chave1;
 document.form1.diretor.value = chave2;
 func_nome.hide();
}



function js_pesquisasd02_i_distrito(mostra) {

 if(mostra == true) {

  js_OpenJanelaIframe('', 'db_iframe_sau_distritosanitario', 'func_sau_distritosanitario.php?funcao_js=parent.'+
                      'js_mostrasd02_i_distrito|s153_i_codigo|s153_c_descr|s153_c_codigo',
                      'Pesquisa de Distrito Sanitário', true
                     );

 } else {

  if (document.form1.s153_c_codigo.value != '') {

    js_OpenJanelaIframe('', 'db_iframe_sau_distritosanitario', 'func_sau_distritosanitario.php?chave_s153_c_codigo='+
                        document.form1.s153_c_codigo.value+'&nao_mostra=true'+
                        '&funcao_js=parent.js_mostrasd02_i_distrito|s153_i_codigo|s153_c_descr|s153_c_codigo', 
                        'Pesquisa', false
                       );

  } else {
  
    document.form1.sd02_i_distrito.value = '';
    document.form1.s153_c_descr.value    = '';

  }

 }

}

function js_mostrasd02_i_distrito(chave1, chave2, chave3) {

  if (chave1  == '') {
    chave3 = '';
  }

 document.form1.sd02_i_distrito.value = chave1;
 document.form1.s153_c_descr.value    = chave2;
 document.form1.s153_c_codigo.value   = chave3;

 db_iframe_sau_distritosanitario.hide();

}


function js_pesquisasd02_i_codigo(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('','db_iframe_db_depart','func_db_depart.php?funcao_js=parent.js_mostradb_depart1|coddepto|descrdepto','Pesquisa',true);
 }else{
  if(document.form1.sd02_i_codigo.value != ''){
   js_OpenJanelaIframe('','db_iframe_db_depart','func_db_depart.php?pesquisa_chave='+document.form1.sd02_i_codigo.value+'&funcao_js=parent.js_mostradb_depart','Pesquisa',false);
  }else{
   document.form1.descrdepto.value = '';
  }
 }
}
function js_mostradb_depart(chave,erro){
 document.form1.descrdepto.value = chave;
 if(erro==true){
  document.form1.sd02_i_codigo.focus();
  document.form1.sd02_i_codigo.value = '';
 }
}
function js_mostradb_depart1(chave1,chave2){
 document.form1.sd02_i_codigo.value = chave1;
 document.form1.descrdepto.value = chave2;
 db_iframe_db_depart.hide();
}
function js_pesquisasd02_i_tp_unid_id(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('','db_iframe_sau_tipounidade','func_sau_tipounidade.php?funcao_js=parent.js_mostrasau_tipounidade1|sd42_i_tp_unid_id|sd42_v_descricao','Pesquisa Tipos de Unidades',true);
 }else{
  if(document.form1.sd02_i_tp_unid_id.value != ''){
   js_OpenJanelaIframe('','db_iframe_sau_tipounidade','func_sau_tipounidade.php?pesquisa_chave='+document.form1.sd02_i_tp_unid_id.value+'&funcao_js=parent.js_mostrasau_tipounidade','Pesquisa',false);
  }else{
   document.form1.sd42_v_descricao.value = '';
  }
 }
}
function js_mostrasau_tipounidade(chave,erro){
 document.form1.sd42_v_descricao.value = chave;
 if(erro==true){
  document.form1.sd02_i_tp_unid_id.focus();
  document.form1.sd02_i_tp_unid_id.value = '';
 }
}
function js_mostrasau_tipounidade1(chave1,chave2){
 document.form1.sd02_i_tp_unid_id.value = chave1;
 document.form1.sd42_v_descricao.value = chave2;
 db_iframe_sau_tipounidade.hide();
}
function js_pesquisasd02_i_numcgm(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('','func_nome',"func_cgm.php?campos=cgm.z01_numcgm, z01_nome,z01_nomefanta, trim(z01_cgccpf) as z01_cgccpf, trim(z01_ender) as z01_ender, z01_numero, z01_compl, z01_bairro, z01_munic, z01_uf, z01_cep&funcao_js=parent.js_mostrasd02_i_numcgm1|z01_numcgm|z01_nome|z01_nomefant|z01_ender|z01_numero|z01_compl|z01_bairro|z01_munic|z01_cgccpf",'Pesquisa de CGM',true);
//cgm.z01_numcgm, z01_nome,z01_nomefant, trim(z01_cgccpf) as z01_cgccpf, case when length(trim(z01_cgccpf)) = 14 then 'JURIDICA' else 'FÍSICA' end as tipo, trim(z01_ender) as z01_ender, z01_numero, z01_compl, z01_bairro, z01_munic, z01_uf, z01_cep
 }else{
  if(document.form1.sd02_i_numcgm.value != ''){
   js_OpenJanelaIframe('','func_nome','func_cgm.php?pesquisa_chave='+document.form1.sd02_i_numcgm.value+'&z01_numcgm='+document.form1.sd02_i_numcgm.value+'&funcao_js=parent.js_mostrasd02_i_numcgm','Pesquisa',false);
  }
 }
}
function js_mostrasd02_i_numcgm(erro,chave){
 if(erro==true){
  document.form1.sd02_i_numcgm.focus();
  document.form1.sd02_i_numcgm.value = '';

 }
}
function js_mostrasd02_i_numcgm1(chave1,chave2,chave3,chave4,chave5,chave6,chave7,chave8,chave9){
 document.form1.sd02_i_numcgm.value = chave1;
 document.form1.z01_nome.value = chave2;
 document.form1.z01_nomefant.value = chave3;
 document.form1.z01_ender.value = chave4;
 document.form1.z01_numero.value = chave5;
 document.form1.z01_compl.value = chave6;
 document.form1.z01_bairro.value = chave7;
 document.form1.z01_munic.value = chave8;
 document.form1.z01_cgccpf.value = chave9;
 if(chave9.length >11){
   document.form1.pessoa.value = 'JURÍDICA';
 }else{
   document.form1.pessoa.value = 'FÍSICA';
 }
func_nome.hide();
}
function js_pesquisa(){
 js_OpenJanelaIframe('','db_iframe_unidades','func_unidades.php?funcao_js=parent.js_preenchepesquisa|sd02_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
 db_iframe_unidades.hide();
 <?
 if($db_opcao!=1){
  echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
 }
 ?>
}
</script>