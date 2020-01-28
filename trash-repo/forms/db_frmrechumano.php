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
$clrechumano->rotulo->label();
$clrhpessoal->rotulo->label();
$clrhpesdoc->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("rh01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("z01_numcgm");
$clrotulo->label("rh06_descr");
$clrotulo->label("rh21_descr");
$clrotulo->label("rh08_descr");
$clrotulo->label("rh18_descr");
$clrotulo->label("rh37_descr");
$clrotulo->label("r70_descr");
$clrotulo->label("r59_descr");
$clrotulo->label("db90_descr");
$clrotulo->label("rh50_oid");
$clrotulo->label("z01_ident");
$clrotulo->label("z01_cgccpf");
$clrotulo->label("z01_ender");
$clrotulo->label("z01_numero");
$clrotulo->label("z01_compl");
$clrotulo->label("z01_bairro");
$clrotulo->label("z01_cep");
?>
<form name="form1" method="post" action="">
<table border="0" width="100%">
 <tr>
  <td nowrap width="200" title="<?=@$Ted20_i_codigo?>">
   <b><?=@$Led20_i_codigo?></b>
  </td>
  <td colspan="2">
    <?db_input('ed20_i_codigo',10,@$ed20_i_codigo,true,'text',3,'')?>
    <?db_input('temregistro',1,@$temregistro,true,'hidden',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted20_i_tiposervidor?>">
   <?=@$Led20_i_tiposervidor?>
  </td>
  <td>
   <?
   $x = array(""=>"","1"=>"SIM","2"=>'NÃO');
   db_select('ed20_i_tiposervidor',$x,true,$db_opcao1,"onChange=\"js_tiposervidor(this.value)\"");
   ?>
   <?=@$Led20_c_efetividade?>
   <?
   $x = array("S"=>"SIM","N"=>'NÃO');
   db_select('ed20_c_efetividade',$x,true,$db_opcao,"");
   ?>
  </td>
 </tr>
 <tr>
  <td colspan="2">
   <span id="div_pessoal" 
         style="width:90%;position:absolute;visibility:<?=isset($ed20_i_tiposervidor) 
                                                               && $ed20_i_tiposervidor==1?"visible":"hidden"?>;">
   <table border="0" width="100%">
    <tr> 
     <td width="200" nowrap title="<?=@$Ted20_i_codigo?>">
      <?db_ancora("<b>Matrícula</b>","js_pesquisaed20_i_codigo(true);",$db_opcao1);?>
     </td>
     <td >
      <?db_input('ed284_i_rhpessoal',10,@$Ied284_i_rhpessoal,true,'text',3)?>
      <?db_input('z01_nome',50,@$z01_nome,true,'text',3,'')?>
     </td>
    </tr>
    <tr> 
     <td width="200">
      <b>Regime:</b>
     </td>
     <td >
      <?db_input('rh30_codreg',10,@$Irh30_codreg,true,'text',3)?>
      <?db_input('rh30_descr',50,@$rh30_descr,true,'text',3,'')?>
     </td>
    </tr>
   </table>
   </span>
   <span id="div_cgm" 
         style="width:90%;position:absolute;visibility:<?=isset($ed20_i_tiposervidor) 
                                                                && $ed20_i_tiposervidor == 2?"visible":"hidden"?>;">
   <table border="0" width="100%">
    <tr> 
     <td width="200" nowrap title="<?=@$Ted20_i_rhregime?>">
      <?db_ancora("<b>CGM:</b>","js_pesquisaz01_numcgm(true);",$db_opcao1);?>
     </td>
     <td>
      <?db_input('ed285_i_cgm',10,@$Ied285_i_cgm,true,'text',3," onchange='js_pesquisaz01_numcgm(false);'")?>
      <?db_input('z01_nome',50,@$z01_nome,true,'text',3,'')?>
     </td>
    </tr>
    <tr> 
     <td width="200" nowrap title="<?=@$Tz01_numcgm?>">
      <?db_ancora("<b>Regime:</b>","js_pesquisaed20_i_rhregime(true);",$db_opcao);?>
     </td>
     <td>
      <?db_input('rh30_codreg',10,@$Irh30_codreg,true,'text',3," onchange='js_pesquisaed20_i_rhregime(false);'")?>
      <?db_input('rh30_descr',50,@$rh30_descr,true,'text',3,'')?>
     </td>
    </tr>
   </table>
   </span>
   </td>
 </tr>
</table>
<br><br><br>
<?if (isset($chavepesquisa)) {?>
<div id="div_dados">
<table border='0' width="100%">
 <tr>
  <td>
   <fieldset>
    <legend align="left"><b>DADOS PESSOAIS</b></legend>
    <table>
     <?if (isset($ed20_i_tiposervidor) && $ed20_i_tiposervidor == 1) {?>
     <tr>
      <td nowrap title="<?=@$Trh01_regist?>">
       <b>Matrícula:</b>
      </td>
      <td nowrap>
       <?db_input('rh01_regist',6,@$Irh01_regist,true,'text',3,"");?>
      </td>
      <td nowrap title="<?=@$Trh01_numcgm?>">
       <b>CGM:</b>
      </td>
      <td nowrap>
       <?db_input('rh01_numcgm',6,$Irh01_numcgm,true,'text',3)?>
       <?db_input('z01_nome',33,$Iz01_nome,true,'text',3,'')?>
      </td>
     </tr>
     <?}?>
     <tr>
      <td nowrap title="<?=@$Trh01_nasc?>">
       <?=@$Lrh01_nasc?>
      </td>
      <td nowrap>
       <?db_inputdata('rh01_nasc',@$rh01_nasc_dia,@$rh01_nasc_mes,@$rh01_nasc_ano,true,'text',3,"")?>
      </td>
      <td>
       <?=@$Lrh01_sexo?>
      </td>
      <td>
       <?
       $arr_sexo = array('M' => 'MASCULINO','F'=>'FEMININO');
       db_select("rh01_sexo",$arr_sexo,true,3,"");
       ?>
       <?db_ancora(@$Lrh01_estciv,"js_pesquisarh01_estciv(true);",3);?>
       <?
       $result_estciv = $clrhestcivil->sql_record($clrhestcivil->sql_query_file());
       db_selectrecord("rh01_estciv",$result_estciv,"",3);
       ?>       
      </td>
     </tr>
     <tr>
      <td nowrap title="<?=@$Ted20_i_codigoinep?>">
       <?=@$Led20_i_codigoinep?>
      </td>
      <td nowrap>
       <?db_input('ed20_i_codigoinep',12,$Ied20_i_codigoinep,true,'text',$db_opcao,"")?>
      </td>
      <td nowrap>
       <?=@$Led20_i_escolaridade?>
      </td>
      <td>
       <?
       $a = array("1"=>"FUNDAMENTAL INCOMPLETO",
                  "2"=>'FUNDAMENTAL COMPLETO',
                  "3"=>"ENSINO MÉDIO - NORMAL/MAGISTÉRIO",
                  "4"=>"ENSINO MÉDIO - NORMAL/MAGISTÉRIO INDÍGENA",
                  "5"=>"ENSINO MÉDIO",
                  "6"=>"SUPERIOR"
                 );
       db_select('ed20_i_escolaridade',$a,true,$db_opcao,"");
       ?>
       <?=@$Led20_i_raca?>
       <?
       $x = array("0"=>"NÃO DECLARADA","1"=>'BRANCA',"2"=>"PRETA","3"=>"PARDA","4"=>"AMARELA","5"=>"INDÍGENA");
       db_select('ed20_i_raca',$x,true,$db_opcao,"");
       ?>
      </td>
     </tr>
    </table>
   </fieldset>
  </td>
 </tr>
 <tr>
  <td>
   <fieldset><legend align="left"><b>DOCUMENTOS</b></legend>
    <center>
    <table border="0" width="100%">
     <tr>
      <td nowrap title="<?=@$Tz01_ident?>" width="20%">
       <?=@$Lz01_ident?>
      </td>
      <td>
       <?db_input('z01_ident',11,@$Iz01_ident,true,'text',3,"")?>
       <b>CPF:</b>
       <?db_input('z01_cgccpf',12,@$Iz01_cgccpf,true,'text',3,"")?>
       <?=@$Lrh16_titele?>
       <?db_input('rh16_titele',11,$Irh16_titele,true,'text',3,"")?>
       <?=@$Lrh16_zonael?>
       <?db_input('rh16_zonael',3,$Irh16_zonael,true,'text',3,"")?>
       <?=@$Lrh16_secaoe?>
       <?db_input('rh16_secaoe',4,$Irh16_secaoe,true,'text',3,"")?>
      </td>
     </tr>
      <tr>
      <td nowrap title="<?=@$Ted20_i_censoorgemiss?>">
       <?=@$Led20_i_censoorgemiss?>
      </td>
      <td>
       <?
       $result_org = $clcensoorgemissrg->sql_record($clcensoorgemissrg->sql_query_file("",
                                                                                       "ed132_i_codigo,ed132_c_descr",
                                                                                       "ed132_c_descr"
                                                                                      )
                                                   );
       db_selectrecord("ed20_i_censoorgemiss",$result_org,"",$db_opcao,"","","","  ","",1);
       ?>
      </td>
     </tr>
     <tr>
      <td nowrap title="<?=@$Ted20_i_censoufident?>">
       <?=@$Led20_i_censoufident?>
      </td>
      <td>
       <?
       $result_uf = $clcensouf->sql_record($clcensouf->sql_query_file("","ed260_i_codigo,ed260_c_nome","ed260_c_nome"));
       db_selectrecord("ed20_i_censoufident",$result_uf,"",$db_opcao,"","","","  ","",1);
       ?>
       <?=@$Led20_d_dataident?>
       <?db_inputdata('ed20_d_dataident',@$ed20_d_dataident_dia,@$ed20_d_dataident_mes,@$ed20_d_dataident_ano,
                      true,'text',$db_opcao);?>
       <?=@$Led20_c_identcompl?>
       <?db_input('ed20_c_identcompl',4,$Ied20_c_identcompl,true,'text',$db_opcao,"")?>
      </td>
     </tr>
     <tr>
      <td nowrap title="<?=@$Trh16_ctps_n?>">
       <?=@$Lrh16_ctps_n?>
      </td>
      <td>
       <?db_input('rh16_ctps_n',7,$Irh16_ctps_n,true,'text',3,"")?>
       <?=@$Lrh16_ctps_s?>
       <?db_input('rh16_ctps_s',4,$Irh16_ctps_s,true,'text',3,"")?>
       <?db_ancora(@$Lrh16_ctps_uf,"",3);?>
       <?
       $result_uf = $cldb_uf->sql_record($cldb_uf->sql_query_file(null,"db12_codigo as rh16_ctps_uf,db12_uf"));
       db_selectrecord("rh16_ctps_uf",$result_uf,true,3,"","","","0-Nenhum...");
       ?>
       <?=@$Lrh16_pis?>
       <?db_input('rh16_pis',11,$Irh16_pis,true,'text',3,"")?>
      </td>
     </tr>
     <tr>
      <td nowrap title="<?=@$Trh16_carth_n?>">
       <?=@$Lrh16_carth_n?>
      </td>
      <td>
       <?db_input('rh16_carth_n',11,$Irh16_carth_n,true,'text',3,"")?>
       <?=@$Lr16_carth_cat?>
       <?db_input('r16_carth_cat',3,$Ir16_carth_cat,true,'text',3,"")?>
       <?=@$Lrh16_carth_val?>
       <?db_inputdata('rh16_carth_val',@$rh16_carth_val_dia,@$rh16_carth_val_mes,@$rh16_carth_val_ano,true,'text',3,"")?>
       <?=@$Led20_c_nis?>
       <?db_input('ed20_c_nis',11,$Ied20_c_nis,true,'text',$db_opcao,"")?>
      </td>
     </tr>
    
     <tr>
      <td>
       <?=@$Led20_i_certidaotipo?>
      </td>
      <td>
       <?
       $x = array(''=>'','1'=>'NASCIMENTO','2'=>'CASAMENTO');
       db_select('ed20_i_certidaotipo',$x,true,$db_opcao,"");
       ?>
       <?=@$Led20_c_certidaonum?>
       <?db_input('ed20_c_certidaonum',8,$Ied20_c_certidaonum,true,'text',$db_opcao,"")?>
       <?=@$Led20_c_certidaofolha?>
       <?db_input('ed20_c_certidaofolha',4,$Ied20_c_certidaofolha,true,'text',$db_opcao,"")?>
       <?=@$Led20_c_certidaolivro?>
       <?db_input('ed20_c_certidaolivro',8,$Ied20_c_certidaolivro,true,'text',$db_opcao,"")?>
       <?=@$Led20_c_certidaodata?>
       <?db_inputdata('ed20_c_certidaodata',@$ed20_c_certidaodata_dia,@$ed20_c_certidaodata_mes,
                      @$ed20_c_certidaodata_ano,true,'text',$db_opcao,"")?>
      </td>
     </tr>
     <tr>
      <td>
       <?=@$Led20_i_censoufcert?>
      </td>
      <td>
       <?
       $result_uf = $clcensouf->sql_record($clcensouf->sql_query_file("","ed260_i_codigo,ed260_c_nome","ed260_c_nome"));
       db_selectrecord("ed20_i_censoufcert",$result_uf,"",$db_opcao,"","","","  ","",1);
       ?>
       <?=@$Led20_c_passaporte?>
       <?db_input('ed20_c_passaporte',20,$Ied20_c_passaporte,true,'text',$db_opcao,"")?>
       </td>
       </tr>
       <tr>
       <td>
       <?=@$Led20_i_censocartorio?>
      </td>
      <td>
       <?
       $resultcartorio = $clcensocartorio->sql_record($clcensocartorio->sql_query_file("","ed291_i_codigo,substr(ed291_c_nome,1,130)","ed291_c_nome"));
       db_selectrecord("ed20_i_censocartorio",$resultcartorio,"",$db_opcao,"","","","  ","",1);
       ?>
       
       
      </td>
     </tr>
     </tr>
    </table>
    </center>
   </fieldset>
   <fieldset>
    <legend align="left"><b>OUTRAS INFORMAÇÕES</b></legend>
    <center>
    <table border="0" width="100%">
     <tr>
      <td>
       <?=$Led20_i_nacionalidade?>
      </td>
      <td>
       <?
       $x = array("1"=>"Brasileira","2"=>"Brasileira no Exterior ou Naturalizado","3"=>"Estrangeira");
       db_select('ed20_i_nacionalidade',$x,true,$db_opcao," onchange='js_nacionalidade1(this.value)'");
       ?>
       <?=$Led20_i_pais?>
       <?
       if (!isset($ed20_i_pais)) {
         $ed20_i_pais = 76;
       }
       $result_pais = $clpais->sql_record($clpais->sql_query_file("","ed228_i_codigo,ed228_c_descr","ed228_c_descr",""));
       if ($clpais->numrows == 0) {
       	
         $x = array(''=>'NENHUM REGISTRO');
         db_select('ed20_i_pais',$x,true,$db_opcao,"");
         
       } else {
         db_selectrecord("ed20_i_pais",$result_pais,"",$db_opcao,"","","","  ","","");
       }
       ?>
      </td>
     </tr>
     <tr>
      <td>
       <?=@$Led20_i_censoufnat?>
      </td>
      <td>
       <?
       $result_uf = $clcensouf->sql_record($clcensouf->sql_query_file("","ed260_i_codigo,ed260_c_nome","ed260_c_nome"));
       db_selectrecord("ed20_i_censoufnat",$result_uf,"","","","","","  ",
                       "iframe_uf.location.href='edu1_rechumano004.php?campo=nat&censouf1='+this.value",1);
       ?>
       <?=@$Led20_i_censomunicnat?>
       <?
       if (isset($ed20_i_censoufnat) && $ed20_i_censoufnat != "") {
       	
         $result_munic = $clcensomunic->sql_record($clcensomunic->sql_query_file("",
                                                                                 "ed261_i_codigo,ed261_c_nome",
                                                                                 "ed261_c_nome",
                                                                                 "ed261_i_censouf = $ed20_i_censoufnat"
                                                                                )
                                                  );
                                                  
         if ($clcensomunic->numrows == 0) {
         	
           $x = array(' '=>'Selecione o Estado');
           db_select('ed20_i_censomunicnat',$x,true,@$db_opcao,"");
           
         } else {
           db_selectrecord("ed20_i_censomunicnat",$result_munic,"","","","","","  ","",1);
         }
         
       } else {
       	
         $x = array(' '=>'Selecione o Estado');
         db_select('ed20_i_censomunicnat',$x,true,@$db_opcao,"");
         
       }
       ?>NÃO deve ter o campo Passaporte informado (Aba Documentos)!
       <iframe name="iframe_uf" src="" framedorder="0" width="0" height="0" style="visibility:hidden;position:absolute;"></iframe>
      </td>
     </tr>
     <tr>
      <td>
       <?=@$Lz01_ender?>
      </td>
      <td>
       <?db_input('z01_ender',30,$Iz01_ender,true,'text',3,"")?>
       <?=@$Lz01_numero?>
       <?db_input('z01_numero',5,$Iz01_numero,true,'text',3,"")?>
       <?=@$Lz01_compl?>
       <?db_input('z01_compl',4,$Iz01_compl,true,'text',3,"")?>
       <?=@$Lz01_bairro?>
       <?db_input('z01_bairro',20,$Iz01_bairro,true,'text',3,"")?>
       <?=@$Lz01_cep?>
       <?db_input('z01_cep',8,$Iz01_cep,true,'text',3,"")?>
      </td>
     </tr>
     <tr>
      <td>
       <?=@$Led20_i_censoufender?>
      </td>
      <td>
       <?
       $result_uf = $clcensouf->sql_record($clcensouf->sql_query_file("","ed260_i_codigo,ed260_c_nome","ed260_c_nome"));
       db_selectrecord("ed20_i_censoufender",$result_uf,"","","","","","  ",
                       "iframe_uf.location.href='edu1_rechumano004.php?campo=ender&censouf1='+this.value",1);
       ?>
       <?=@$Led20_i_censomunicender?>
       <?
       if (isset($ed20_i_censoufender) && $ed20_i_censoufender != "") {
       	
         $result_munic = $clcensomunic->sql_record($clcensomunic->sql_query_file("",
                                                                                 "ed261_i_codigo,ed261_c_nome",
                                                                                 "ed261_c_nome",
                                                                                 "ed261_i_censouf = $ed20_i_censoufender"
                                                                                )
                                                  );
         if ($clcensomunic->numrows == 0) {
         	
           $x = array(' '=>'Selecione o Estado');
           db_select('ed20_i_censomunicender',$x,true,@$db_opcao,"");
           
         } else {
           db_selectrecord("ed20_i_censomunicender",$result_munic,"","","","","","  ","",1);
         }
         
       } else {
       	
         $x = array(' '=>'Selecione o Estado');
         db_select('ed20_i_censomunicender',$x,true,@$db_opcao,"");
         
       }
       ?>
       <iframe name="iframe_uf" src="" framedorder="0" width="0" height="0" style="visibility:hidden;position:absolute;"></iframe>
      </td>
     </tr>
     <tr>
       <td nowrap title="<?=@$Ted20_i_zonaresidencia?>">
         <?=@$Led20_i_zonaresidencia?>
       </td>
       <td>
         <?
           $x = array('0'=>'SELECIONE', '1'=>'URBANA', '2'=>'RURAL');
           db_select('ed20_i_zonaresidencia', $x, true, $db_opcao, '');
         ?>
       </td>
     </tr>
    </table>
    </center>
   </fieldset>
  </td>
 </tr>
</table>
</div>
<?}?>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
       type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
       <?=($db_botao==false?"disabled":"")?> onclick="return js_valida();">
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
<input name="novo" type="button" id="novo" value="Novo Registro" onclick="js_novo()" <?=$db_opcao==1?"disabled":""?>>
</form>
<script>
function js_valida() {
	
  if (document.form1.ed20_i_tiposervidor.value == "") {
    alert("Informe o tipo de Servidor!");
    return false;
  }
  
  if (document.form1.ed20_i_tiposervidor.value == "1" && document.form1.ed284_i_rhpessoal.value == "") {
    alert("Informe a Matrícula do servidor!");
    return false;
  }
  
  if (document.form1.ed20_i_tiposervidor.value == "2" && document.form1.ed285_i_cgm.value == "") {
    alert("Informe o Cgm do servidor!");
    return false;
  }
  
  if (document.form1.ed20_i_tiposervidor.value == "2" && document.form1.rh30_codreg.value == "") {
    alert("Informe o Regime do servidor!");
    return false;
  }
  
  nacionalidade1 = document.form1.ed20_i_nacionalidade.value;
  pais1 = document.form1.ed20_i_pais.value;
  if ((nacionalidade1 == 1 || nacionalidade1 == 2) && pais1 != 10) {
    alert("Campo País deve ser BRASIL quando nacionalidade for Brasileira ou Brasileira no Exterior!");
    return false;
  }
  
  if (nacionalidade1 == 3 && pais1 == 10) {
    alert("Campo País deve ser diferente de BRASIL quando nacionalidade for Estrangeira!");
    return false;
  }
  
  naturalidade1 = document.form1.ed20_i_censomunicnat.value;
  naturalidadeuf1 = document.form1.ed20_i_censoufnat.value;
  if (nacionalidade1 == 1 && (naturalidade1 == " " || naturalidadeuf1 == " ")) {
	  
	alertar  = "Campos UF de Nascimento e Município de Nascimento devem ser";
	alertar += " preenchidos quando nacionalidade for Brasileira!";
    alert(alertar);
    return false;
    
  }
  
  if (nacionalidade1 != 1 && (naturalidade1 != " " || naturalidadeuf1 != " ")) {

    msg  = "Campos UF de Nascimento e Município de Nascimento NÃO devem ser preenchidos quando nacionalidade ";
    msg += "for diferente de Brasileira!";
    alert(msg);
    return false;
    
  }
  
  if (document.form1.ed20_i_nacionalidade.value != 3 && document.form1.ed20_c_passaporte.value != "") {

	pass  = "Aluno com nacionalidade Brasileira ou Brasileira no Exterior ";
	pass += "NÃO deve ter o campo Passaporte informado (Aba Documentos)!";
    alert(pass);
    return false;
    
  }
  
  return true;
}

function js_nacionalidade1(valor) {
	
  if (valor == 3 && document.form1.ed20_i_codigo.value != "") {
    iframe_uf.location.href='edu1_rechumano004.php?nacionalidade1='+document.form1.ed20_i_codigo.value;
  }  
}

function js_pesquisaed20_i_codigo(mostra) {
	
  js_OpenJanelaIframe('','db_iframe_rhpessoal','func_rhpessoaleducacao.php?funcao_js=parent.js_mostrarhpessoal1|'+
		              'rh01_regist|z01_nome','Pesquisa de Funcionários na Prefeitura',true);
  
}

function js_mostrarhpessoal1(chave1,chave2) {
  db_iframe_rhpessoal.hide();
  js_divCarregando("Aguarde, verificando matrícula","msgBox");
  var url     = 'edu1_rechumanoRPC.php';
  var sAction = 'VerificaMatricula';
  var oAjax   = new Ajax.Request(url,{method    : 'post',
                                      parameters: '&matricula='+chave1+'&sAction='+sAction,
                                      onComplete: js_retornaVerificaMatricula
                                     });
  
}

function js_retornaVerificaMatricula(oAjax) {
	
  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
  location.href = "edu1_rechumano001.php?chavepesquisa="+oRetorno[1]+
                  "&ed20_i_tiposervidor="+document.form1.ed20_i_tiposervidor.value+"&temregistro="+oRetorno[0];
   
}

function js_pesquisaz01_numcgm(mostra) {
	
 js_OpenJanelaIframe('','func_nome','func_cgmrechumano.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome',
		             'Pesquisa de Cgm do Município',true);
 
}

function js_mostracgm1(chave1,chave2) {
	
  func_nome.hide();
  js_divCarregando("Aguarde, verificando cgm","msgBox");
  var url     = 'edu1_rechumanoRPC.php';
  var sAction = 'VerificaCGM';
  var oAjax   = new Ajax.Request(url,{method    : 'post',
                                      parameters: '&cgm='+chave1+'&sAction='+sAction,
                                      onComplete: js_retornaVerificaCGM
                                     });

}

function js_retornaVerificaCGM(oAjax) {
	
  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
  location.href = "edu1_rechumano001.php?chavepesquisa="+oRetorno[1]+
                  "&ed20_i_tiposervidor="+document.form1.ed20_i_tiposervidor.value+"&temregistro="+oRetorno[0];
   
}

function js_pesquisa() {
	
 js_OpenJanelaIframe('','db_iframe_rechumano','func_rechumano.php?funcao_js=parent.js_preenchepesquisa|ed20_i_codigo',
		             'Pesquisa de Recursos Humanos da Escola',true);
 
}

function js_preenchepesquisa(chave) {
	
  db_iframe_rechumano.hide();
  <?
  if ($db_opcao != 1) {
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>  
}

function js_novo() {
  parent.location="edu1_rechumanoabas001.php";
}

function js_tiposervidor(tipo) {
	
 if (tipo=="") {

  document.getElementById("div_pessoal").style.visibility = "hidden";
  document.getElementById("div_cgm").style.visibility     = "hidden";

 } else if (tipo == "1") {

  document.getElementById("div_pessoal").style.visibility = "visible";
  document.getElementById("div_cgm").style.visibility     = "hidden";

 } else if (tipo == "2") {

  document.getElementById("div_pessoal").style.visibility = "hidden";
  document.getElementById("div_cgm").style.visibility     = "visible";

 }
 
 if (document.getElementById("div_dados")) {
   document.getElementById("div_dados").innerHTML = "";
 }
 
 document.form1.ed284_i_rhpessoal.value = "";
 document.form1.ed285_i_cgm.value       = "";
 document.form1.z01_nome[0].value       = "";
 document.form1.z01_nome[1].value       = "";
 document.form1.ed20_i_codigo.value     = "";
 document.form1.temregistro.value       = "";
 document.form1.rh30_codreg[0].value    = "";
 document.form1.rh30_codreg[1].value    = "";
 document.form1.rh30_descr[0].value     = "";
 document.form1.rh30_descr[1].value     = "";
 
}

function js_pesquisaed20_i_rhregime(mostra) {
	
   js_OpenJanelaIframe('','db_iframe_rhregime','func_rhregimeedu.php?funcao_js=parent.js_mostrarhregime1|'+
		               'rh30_codreg|rh30_descr','Pesquisa',true,0);
   
}

function js_mostrarhregime1(chave1,chave2) {
	
  document.form1.rh30_codreg[1].value = chave1;
  document.form1.rh30_descr[1].value  = chave2;
  db_iframe_rhregime.hide();
  
}
</script>