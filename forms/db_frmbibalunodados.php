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
$claluno->rotulo->label();
$clalunoprimat->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("j14_codigo");
$clrotulo->label("j13_cod");
$clrotulo->label("j13_codi");
?>
<form name="form1" method="post" action="" enctype="multipart/form-data">
<table border="0" cellspacing="0" cellpadding="0" width="100%">
 <tr valign="top">
  <td colspan="2">
   <fieldset><legend><b>Dados Pessoais</b></legend>
    <table border="0" cellspacing="0" cellpadding="0" width="100%">
     <tr>
      <td valign="middle" width="20%" align="center">
       <iframe name="frame_imagem" id="frame_imagem" src="edu4_mostraimagem.php" width="110" height="125" frameborder="1" scrolling="no"></iframe>
       <?
       if((isset($chavepesquisa) || isset($alterar)) && isset($ed47_c_foto)){
        if($ed47_o_oid!=0){
         $arquivo = "tmp/".$ed47_c_foto;
         pg_exec("begin");
         pg_loexport($ed47_o_oid,$arquivo);
         pg_exec("end");
         if($db_botao==true){
          ?>
          <br><input type="button" name="excfoto" value="Excluir Foto" onclick="location.href='bib1_alunodados002.php?excluirfoto&chavepesquisa=<?=$chavepesquisa?>'" style="font-size:9px;height:14px;padding:0px;">
          <?
         }
        }else{
         $arquivo = "imagens/none1.jpeg";
        }
        ?>
        <script>
        frame_imagem.location.href="edu4_mostraimagem.php?imagem_gerada=<?=$arquivo?>";
        </script>
       <?}?>
      </td>
      <td valign="top">
       <table border="0" cellspacing="1" cellpadding="0" width="100%">
        <tr>
         <td>
          <?=$Led47_i_codigo?>
         </td>
         <td>
          <?db_input('ed47_i_codigo',20,$Ied47_i_codigo,true,'text',3);?>
          <?=@$Led47_c_codigoinep?>
          <?db_input('ed47_c_codigoinep',12,$Ied47_c_codigoinep,true,'text',$db_opcao,'')?>
          <?=@$Led47_c_nis?>
          <?db_input('ed47_c_nis',11,$Ied47_c_nis,true,'text',$db_opcao,"")?>
         </td>
        </tr>
        <tr>
         <td>
          <?=@$Led47_v_nome?>
         </td>
         <td>
          <?db_input('ed47_v_nome',70,$Ied47_v_nome,true,'text',$db_opcao," onKeyUp=\"js_ValidaCamposEdu(this,1,'$GLOBALS[Sed47_v_nome]','f','t',event);\"")?>
         </td>
        </tr>
        <tr>
         <td>
          <?=$Led47_d_nasc?>
         </td>
         <td>
          <?db_inputdata('ed47_d_nasc',@$ed47_d_nasc_dia,@$ed47_d_nasc_mes,@$ed47_d_nasc_ano,true,'text',$db_opcao);?>
          <?=$Led47_v_sexo?>
          <?
          $sex = array(""=>"","M"=>"Masculino","F"=>"Feminino");
          db_select('ed47_v_sexo',$sex,true,$db_opcao);
          ?>
          <?=$Led47_i_estciv?>
          <?
          $x = array("1"=>"Solteiro","2"=>"Casado","3"=>"Viúvo","4"=>"Divorciado");
          db_select('ed47_i_estciv',$x,true,$db_opcao);
          ?>
         </td>
        </tr>
        <tr>
         <td>
          <?=@$Led47_i_filiacao?>
         </td>
         <td>
          <?
          $fil = array("0"=>"NÃO DECLARADO / IGNORADO","1"=>"PAI E/OU MÃE");
          db_select('ed47_i_filiacao',$fil,true,$db_opcao," onchange='js_filiacao(this.value)'");
          ?>
          <?=@$Led47_c_raca?>
          <?
          $x = array('NÃO DECLARADA'=>'NÃO DECLARADA','BRANCA'=>'BRANCA','PRETA'=>'PRETA','PARDA'=>'PARDA','AMARELA'=>'AMARELA','INDÍGENA'=>'INDÍGENA');
          db_select('ed47_c_raca',$x,true,$db_opcao,"");
          ?>
         </td>
        </tr>
        <tr>
         <td>
          <?=@$Led47_v_pai?>
         </td>
         <td>
          <?db_input('ed47_v_pai',70,$Ied47_v_pai,true,'text',$db_opcao," onKeyUp=\"js_ValidaCamposEdu(this,1,'$GLOBALS[Sed47_v_pai]','f','t',event);\"")?>
         </td>
        </tr>
        <tr>
         <td>
          <?=@$Led47_v_mae?>
         </td>
         <td>
          <?db_input('ed47_v_mae',70,$Ied47_v_mae,true,'text',$db_opcao," onKeyUp=\"js_ValidaCamposEdu(this,1,'$GLOBALS[Sed47_v_mae]','f','t',event);\"")?>
         </td>
        </tr>
        <tr>
         <td>
          <?=@$Led47_c_nomeresp?>
         </td>
         <td>
          <?db_input('ed47_c_nomeresp',70,$Ied47_c_nomeresp,true,'text',$db_opcao,"")?>
         </td>
        </tr>
        <tr>
         <td>
          <?=@$Led47_c_emailresp?>
         </td>
         <td>
          <?db_input('ed47_c_emailresp',40,$Ied47_c_emailresp,true,'text',$db_opcao,"")?>
         </td>
        </tr>
        <tr>
         <td nowrap title="<?=@$Ted47_c_foto?>">
          <b>Foto:</b>
         </td>
         <td colspan="2">
          <iframe name="frame_file" id="frame_file" src="edu1_framefile.php" width="100%" height="25" frameborder="0" scrolling="no"></iframe>
         </td>
        </tr>
       </table>
      </td>
     </tr>
    </table>
   </fieldset>
  </td>
 </tr>
 <tr valign="top">
  <td width="45%" valign="top">
   <fieldset style="height:210px"><legend><b>Endereço Residencial / Contato</b></legend>
    <table border="0" cellspacing="1" cellpadding="0" width="100%">
     <tr>
      <td colspan="2">
       <b>Libera Endereço:</b>
       <?
       $x = array("N"=>"NÃO","S"=>"SIM");
       db_select('liberaendereco',$x,true,$db_opcao," onchange='LiberaEndereco(this.value);'");
       ?>
      </td>
     </tr>
     <tr>
      <td>
       <?db_ancora(@$Led47_v_ender,"js_ruas();",$db_opcao);?>
      </td>
      <td>
       <?db_input('ed47_v_ender',40,$Ied47_v_ender,true,'text',$db_opcao," onKeyUp=\"js_ValidaCamposEdu(this,3,'$GLOBALS[Sed47_v_ender]','f','t',event);\"")?>
      </td>
     </tr>
     <tr>
      <td>
       <?=@$Led47_c_numero?>
      </td>
      <td>
       <?db_input('ed47_c_numero',10,$Ied47_c_numero,true,'text',$db_opcao," onKeyUp=\"js_ValidaCamposEdu(this,3,'$GLOBALS[Sed47_c_numero]','t','t',event);\"")?>
       &nbsp;
       <?=@$Led47_v_compl?>
       <?db_input('ed47_v_compl',20,$Ied47_v_compl,true,'text',$db_opcao," onKeyUp=\"js_ValidaCamposEdu(this,3,'$GLOBALS[Sed47_v_compl]','t','t',event);\"")?>
      </td>
     </tr>
     <tr>
      <td>
       <?=@$Led47_i_censoufend?>
      </td>
      <td>
       <?
       $result_uf = $clcensouf->sql_record($clcensouf->sql_query_file("","ed260_i_codigo,ed260_c_nome","ed260_c_nome"));
       db_selectrecord("ed47_i_censoufend",$result_uf,"","","","","","  ","iframe_uf.location.href='edu1_aluno004.php?campo=end&censouf='+this.value",1);
       ?>
      </td>
     </tr>
     <tr>
      <td>
       <?=@$Led47_i_censomunicend?>
      </td>
      <td>
       <?
       if(isset($ed47_i_censoufend) && $ed47_i_censoufend!=""){
        $result_munic = $clcensomunic->sql_record($clcensomunic->sql_query_file("","ed261_i_codigo,ed261_c_nome","ed261_c_nome","ed261_i_censouf = $ed47_i_censoufend"));
        if($clcensomunic->numrows==0){
         $x = array(' '=>'Selecione o Estado');
         db_select('ed47_i_censomunicend',$x,true,@$db_opcao,"");
        }else{
         db_selectrecord("ed47_i_censomunicend",$result_munic,"","","","","","  ","",1);
        }
       }else{
        $x = array(' '=>'Selecione o Estado');
        db_select('ed47_i_censomunicend',$x,true,@$db_opcao,"");
       }
       ?>
       <iframe name="iframe_uf" src="" framedorder="0" width="0" height="0" style="visibility:hidden;position:absolute;"></iframe>
      </td>
     </tr>
     <tr>
      <td>
       <?db_ancora(@$Led47_v_bairro,"js_bairro();",$db_opcao);?>
      </td>
      <td>
       <?db_input('j13_codi',10,$Ij13_codi,true,'text',3);?>
       <?db_input('ed47_v_bairro',25,$Ied47_v_bairro,true,'text',3);?>
      </td>
     </tr>
     <tr>
      <td>
       <?=@$Led47_c_zona?>
      </td>
      <td>
       <?
       $x = array('URBANA'=>'Urbana','RURAL'=>'Rural');
       db_select('ed47_c_zona',$x,true,$db_opcao,"");
       ?>
       <?=@$Led47_v_cep?>
       <?db_input('ed47_v_cep',8,$Ied47_v_cep,true,'text',$db_opcao);?>
      </td>
     </tr>
     <tr>
      <td>
       <?=@$Led47_v_telef?>
      </td>
      <td>
       <?db_input('ed47_v_telef',12,$Ied47_v_telef,true,'text',$db_opcao);?>
       <?=@$Led47_v_telcel?>
       <?db_input('ed47_v_telcel',12,$Ied47_v_telcel,true,'text',$db_opcao);?>
      </td>
     </tr>
     <tr>
      <td>
       <?=@$Led47_v_fax?>
      </td>
      <td>
       <?db_input('ed47_v_fax',12,$Ied47_v_fax,true,'text',$db_opcao);?>
       <?=@$Led47_v_cxpostal?>
       <?db_input('ed47_v_cxpostal',10,$Ied47_v_cxpostal,true,'text',$db_opcao);?>
      </td>
     </tr>
     <tr>
      <td>
       <?=@$Led47_v_email?>
      </td>
      <td>
       <?db_input('ed47_v_email',30,$Ied47_v_email,true,'text',$db_opcao);?>
      </td>
     </tr>
    </table>
   </fielset>
  </td>
  <td valign="top">
   <fieldset style="height:210px"><legend><b>Outras Informações</b></legend>
    <table border="0" cellspacing="1" cellpadding="0">
     <tr>
      <td>
       <?=$Led47_i_nacion?>
      </td>
      <td>
       <?
       $x = array("1"=>"Brasileira","2"=>"Brasileira no Exterior ou Naturalizado","3"=>"Estrangeira");
       db_select('ed47_i_nacion',$x,true,$db_opcao," onchange='js_nacionalidade(this.value)'");
       ?>
      </td>
     </tr>
     <tr>
      <td>
       <?=$Led47_i_pais?>
      </td>
      <td>
       <?
       if(!isset($ed47_i_pais)){
        $ed47_i_pais = 10;
       }
       $result_pais = $clpais->sql_record($clpais->sql_query_file("","ed228_i_codigo,ed228_c_descr","ed228_c_descr",""));
       if($clpais->numrows==0){
        $x = array(''=>'NENHUM REGISTRO');
        db_select('ed47_i_pais',$x,true,$db_opcao,"");
       }else{
        db_selectrecord("ed47_i_pais",$result_pais,"",$db_opcao,"","","","  ","","");
       }
       ?>
      </td>
     </tr>
     <tr>
      <td>
       <?=@$Led47_i_censoufnat?>
      </td>
      <td>
       <?
       $result_uf = $clcensouf->sql_record($clcensouf->sql_query_file("","ed260_i_codigo,ed260_c_nome","ed260_c_nome"));
       db_selectrecord("ed47_i_censoufnat",$result_uf,"","","","","","  ","iframe_uf.location.href='edu1_aluno004.php?campo=nat&censouf='+this.value",1);
       ?>
      </td>
     </tr>
     <tr>
      <td>
       <?=@$Led47_i_censomunicnat?>
      </td>
      <td>
       <?
       if(isset($ed47_i_censoufnat) && $ed47_i_censoufnat!=""){
        $result_munic = $clcensomunic->sql_record($clcensomunic->sql_query_file("","ed261_i_codigo,ed261_c_nome","ed261_c_nome","ed261_i_censouf = $ed47_i_censoufnat"));
        if($clcensomunic->numrows==0){
         $x = array(' '=>'Selecione o Estado');
         db_select('ed47_i_censomunicnat',$x,true,@$db_opcao,"");
        }else{
         db_selectrecord("ed47_i_censomunicnat",$result_munic,"","","","","","  ","",1);
        }
       }else{
        $x = array(' '=>'Selecione o Estado');
        db_select('ed47_i_censomunicnat',$x,true,@$db_opcao,"");
       }
       ?>
       <iframe name="iframe_uf" src="" framedorder="0" width="0" height="0" style="visibility:hidden;position:absolute;"></iframe>
      </td>
     </tr>
     <tr>
      <td>
       <?=$Led47_i_transpublico?>
      </td>
      <td>
       <?
       $x = array("0"=>"Não Utiliza","1"=>"Utiliza");
       db_select('ed47_i_transpublico',$x,true,$db_opcao);
       ?>
      </td>
     </tr>
     <tr>
      <td>
       <?=@$Led47_c_transporte?>
      </td>
      <td>
       <?
       $x = array(''=>'','1'=>'Estadual','2'=>'Municipal');
       db_select('ed47_c_transporte',$x,true,$db_opcao,"");
       ?>
      </td>
     </tr>
     <tr>
      <td colspan="2">
       <?=$Led47_c_atenddifer?>
       <?
       $x = array("3"=>"Não Recebe","1"=>"Em Hospital","2"=>"Em Domicílio");
       db_select('ed47_c_atenddifer',$x,true,$db_opcao);
       ?>
      </td>
     </tr>
     <tr>
      <td>
       <?=@$Led47_c_bolsafamilia?>
      </td>
      <td>
       <?
       $x = array('N'=>'NÃO','S'=>'SIM');
       db_select('ed47_c_bolsafamilia',$x,true,$db_opcao,"");
       ?>
       <?db_input('ed47_i_atendespec',10,$Ied47_i_atendespec,true,'hidden',$db_opcao);?>
      </td>
     </tr>
     <tr>
      <td>
       <?=$Led47_v_profis?>
      </td>
      <td>
       <?db_input('ed47_v_profis',40,$Ied47_v_profis,true,'text',$db_opcao);?>
      </td>
     </tr>
    </table>
   </fielset>
  </td>
 </tr>
 <tr valign="top">
  <td colspan="2">
   <fieldset style="visibility:hidden;position:absolute;"><legend><b>Procedência do Aluno</b></legend>
    <table width="100%">
     <tr>
      <td nowrap title="<?=@$Ted76_i_escola?>">
       <?db_ancora(@$Led76_i_escola,"js_pesquisaed76_i_escola(true);",$db_opcao);?>
       <?db_input('ed76_i_escola',20,$Ied76_i_escola,true,'text',3," onchange='js_pesquisaed76_i_escola(false);'")?>
       <?db_input('nomeescola',40,@$Inomeescola,true,'text',3,"")?>
       <?db_input('ed76_c_tipo',10,@$Ied76_c_tipo,true,'hidden',3,"")?>
       <?db_input('ed76_i_codigo',20,@$Ied76_i_codigo,true,'hidden',3,"")?>
       <input type="button" name="limpar" value="Limpar" onclick="document.form1.ed76_i_escola.value='';document.form1.nomeescola.value='';document.form1.ed76_c_tipo.value='';">
       <?=@$Led76_d_data?>
       <?db_inputdata('ed76_d_data',@$ed76_d_data_dia,@$ed76_d_data_mes,@$ed76_d_data_ano,true,'text',$db_opcao);?>
      </td>
     </tr>
    </table>
   </fieldset>
  </td>
 </tr>
 <tr align="center">
  <td height="30">
   <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> onclick="return js_valida();">
   <?if(!isset($leitor)){?>
    <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">
    <input name="novo" type="button" id="novo" value="Novo Registro" onclick="js_novo()" <?=$db_opcao==1?"disabled":""?>>
   <?}else{?>
    <input name="leitor" type="hidden" value="<?=$leitor?>">
    <input type="button" value="Fechar" onclick="js_fechar();">
   <?}?>
   <input name="ed47_o_oid" type="hidden" id="ed47_o_oid" value="<?=@$ed47_c_foto?>" size="30">
  </td>
  <td align="right">
   <?=@$Led47_d_cadast?>
   <?db_inputdata('ed47_d_cadast',@$ed47_d_cadast_dia,@$ed47_d_cadast_mes,@$ed47_d_cadast_ano,true,'text',3);?>
   <?=@$Led47_d_ultalt?>
   <?db_inputdata('ed47_d_ultalt',@$ed47_d_ultalt_dia,@$ed47_d_ultalt_mes,@$ed47_d_ultalt_ano,true,'text',3);?>
  </td>
 </tr>
</table>
</form>
<script>
function js_ruas(){
 js_OpenJanelaIframe('','db_iframe_ruas','func_ruas.php?rural=1&funcao_js=parent.js_preenchepesquisaruas|j14_codigo|j14_nome','Pesquisa',true);
}
function js_preenchepesquisaruas(chave,chave1){
  document.form1.ed47_v_ender.value = chave1;
  db_iframe_ruas.hide();
}
function js_bairro(){
 js_OpenJanelaIframe('','db_iframe_bairro','func_bairro.php?rural=1&funcao_js=parent.js_preenchebairro|j13_codi|j13_descr','Pesquisa',true);
}
function js_preenchebairro(chave,chave1){
 document.form1.j13_codi.value = chave;
 document.form1.ed47_v_bairro.value = chave1;
 db_iframe_bairro.hide();
}
function js_pesquisaed76_i_escola(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('','db_iframe_escola','func_escolaproced.php?funcao_js=parent.js_mostraescola1|ed18_i_codigo|ed18_c_nome|tipoescola','Pesquisa de Escolas',true);
 }
}
function js_mostraescola1(chave1,chave2,chave3){
 document.form1.ed76_i_escola.value = chave1;
 document.form1.nomeescola.value = chave2;
 document.form1.ed76_c_tipo.value = chave3;
 db_iframe_escola.hide();
}
function js_pesquisa(){
 js_OpenJanelaIframe('','db_iframe_aluno','func_aluno.php?funcao_js=parent.js_preenchepesquisa|ed47_i_codigo','Pesquisa Alunos',true);
}
function LiberaEndereco(valor){
 if(valor=="S"){
  document.form1.ed47_v_ender.readOnly = false;
  document.form1.ed47_v_ender.style.background = "#FFFFFF";
  document.links[0].style.color = "#000000";
  document.links[0].style.textDecoration = "none";
  document.links[0].href = "";
 }else if(valor=="N"){
  document.form1.ed47_v_ender.readOnly = true;
  document.form1.ed47_v_ender.style.background = "#DEB887";
  document.links[0].style.color = "blue";
  document.links[0].style.textDecoration = "underline";
  document.links[0].href = "#";
 }
}
function js_preenchepesquisa(chave){
 db_iframe_aluno.hide();
 <?echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";?>
}
function js_novo(){
 location.href = "edu1_alunodados001.php";
}

LiberaEndereco("N");

function js_valida(){
 datanasc = document.form1.ed47_d_nasc.value;
 if(datanasc!=""){
  dianasc = datanasc.substr(0,2);
  mesnasc = datanasc.substr(3,2);
  anonasc = datanasc.substr(6,4);
  data_hj = <?=date("Y").date("m").date("d")?>;
  if(anonasc<1918){
   alert("Ano da Data de Nascimento deve ser maior que 1917!");
   return false;
  }
  data_nasc = anonasc+""+mesnasc+""+dianasc;
  if(parseInt(data_nasc)>=parseInt(data_hj)){
   alert("Data de Nascimento deve ser menor que a data corrente!");
   return false;
  }
 }
 filiacao = document.form1.ed47_i_filiacao.value;
 pai = document.form1.ed47_v_pai.value;
 mae = document.form1.ed47_v_mae.value;
 if(filiacao==0 && ( pai!="" || mae!="" )){
  alert("Campo Filiação definido como Não Declarado / Ignorado!\nPai e Mãe NÃO devem ser preenchidos.");
  return false;
 }
 if(filiacao==1 && pai=="" && mae=="" ){
  alert("Campo Filiação definido como Pai e /ou Mãe!\nPai e/ou Mãe deve ser preenchido.");
  return false;
 }
 if(pai!="" && mae!="" && pai==mae){
  alert("Campos Pai e Mãe devem ser diferentes!");
  return false;
 }
 nacion = document.form1.ed47_i_nacion.value;
 pais = document.form1.ed47_i_pais.value;
 if((nacion==1 || nacion==2) && pais!=10){
  alert("Campo País deve ser BRASIL quando nacionalidade for Brasileira ou Brasileira no Exterior!");
  return false;
 }
 if(nacion==3 && pais==10){
  alert("Campo País deve ser diferente de BRASIL quando nacionalidade for Estrangeira!");
  return false;
 }
 naturalidade = document.form1.ed47_i_censomunicnat.value;
 naturalidadeuf = document.form1.ed47_i_censoufnat.value;
 if(nacion==1 && (naturalidade==" " || naturalidadeuf==" ")){
  alert("Campos UF de Nascimento e Naturalidade devem ser preenchidos quando nacionalidade for Brasileira!");
  return false;
 }
 if(nacion!=1 && (naturalidade!=" " || naturalidadeuf!=" ")){
  alert("Campos UF de Nascimento e Naturalidade NÃO devem ser preenchidos quando nacionalidade for diferente de Brasileira!");
  return false;
 }
 cep = document.form1.ed47_v_cep.value;
 end = document.form1.ed47_v_ender.value;
 num = document.form1.ed47_c_numero.value;
 com = document.form1.ed47_v_compl.value;
 bai  = document.form1.ed47_v_bairro.value;
 uf = document.form1.ed47_i_censoufend.value;
 mun = document.form1.ed47_i_censomunicend.value;
 if(cep=="" && (end!="" || num!="" || com!="" || bai!="" || uf!=" " || mun!=" ") ){
  alert("Campo CEP deve ser informado quando\num dos campos abaixo estiverem informados:\n\nEndereço\nNúmero\nComplemento\nBairro\nUF\nMunicípio");
  return false;
 }
 if(end=="" && (cep!="" || uf!=" " || mun!=" ") ){
  alert("Campo Endereço deve ser informado quando\num dos campos abaixo estiverem informados:\n\nCEP\nUF\nMunicípio");
  return false;
 }
 if(uf==" " && (cep!="" || mun!=" ") ){
  alert("Campo UF deve ser informado quando\num dos campos abaixo estiverem informados:\n\nCEP\nMunicípio");
  return false;
 }
 if(mun==" " && (cep!="" || uf!=" ") ){
  alert("Campo Município deve ser informado quando\num dos campos abaixo estiverem informados:\n\nCEP\nUF");
  return false;
 }
 if(num!="" && cep=="" && end=="" && uf==" " && mun==" "){
  alert("Campo Número só pode ser informado quando\num dos campos abaixo estiverem informados:\n\nCEP\nEndereço\nUF\nMunicípio");
  return false;
 }
 if(com!="" && cep=="" && end=="" && uf==" " && mun==" "){
  alert("Campo Complemento só pode ser informado quando\num dos campos abaixo estiverem informados:\n\nCEP\nEndereço\nUF\nMunicípio");
  return false;
 }
 if(bai!="" && cep=="" && end=="" && uf==" " && mun==" "){
  alert("Campo Bairro só pode ser informado quando\num dos campos abaixo estiverem informados:\n\nCEP\nEndereço\nUF\nMunicípio");
  return false;
 }
 if(cep!="" && cep.length!=8){
  alert("Campo CEP deve conter 8 dígitos!");
  return false;
 }
 if(document.form1.ed47_i_transpublico.value==0 && document.form1.ed47_c_transporte.value!=""){
  alert("Campo Poder Publico Responsável só pode ser informado quando campo Transporte Escolar Público for igual a Utiliza!");
  return false;
 }
 if(document.form1.ed47_i_transpublico.value==1 && document.form1.ed47_c_transporte.value==""){
  alert("Campo Poder Publico Responsável deve ser informado quando campo Transporte Escolar Público for igual a Utiliza!");
  return false;
 }
 if(document.form1.ed47_i_nacion.value!=3 && parent.iframe_a2.document.form1.ed47_c_passaporte.value!=""){
  alert("Aluno com nacionalidade Brasileira ou Brasileira no Exterior NÃO deve ter o campo Passaporte informado (Aba Documentos)!");
  return false;
 }
 return true;
}
function js_filiacao(valor){
 if(valor==0){
  document.form1.ed47_v_pai.readOnly = true;
  document.form1.ed47_v_mae.readOnly = true;
  document.form1.ed47_v_pai.style.background = "#DEB887";
  document.form1.ed47_v_mae.style.background = "#DEB887";
  document.form1.ed47_v_pai.value = "";
  document.form1.ed47_v_mae.value = "";
 }else{
  document.form1.ed47_v_pai.readOnly = false;
  document.form1.ed47_v_mae.readOnly = false;
  document.form1.ed47_v_pai.style.background = "#E6E4F1";
  document.form1.ed47_v_mae.style.background = "#E6E4F1";
 }

}
function js_nacionalidade(valor){
 if(valor==3 && document.form1.ed47_i_codigo.value!=""){
  iframe_uf.location.href='edu1_aluno004.php?nacionalidade='+document.form1.ed47_i_codigo.value;
 }
}
function js_fechar(){
 if(parent.parent.document.form1.codigo.value!=""){
  parent.parent.db_iframe_alteradados.hide();
  parent.parent.dadosleitor.location.href = "bib1_leitor004.php?chavepesquisa=<?=$ed47_i_codigo?>&tipo=ALUNO";
 }else{
  parent.parent.db_iframe_alteradados.hide();
  parent.parent.document.form1.codigo.value = <?=$ed47_i_codigo?>;
  parent.parent.document.form1.nome.value = "<?=$ed47_v_nome?>";
  parent.parent.document.form1.tipo.value = "ALUNO";
 }
}
js_filiacao(document.form1.ed47_i_filiacao.value);
</script>