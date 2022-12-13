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

//MODULO: pessoal
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clrhpagatra->rotulo->label();
$clrhpagocor->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("rh60_descr");
$clrotulo->label("rh59_descr");
$clrotulo->label("rh59_tipo");
$funcionario_na_justica = false;
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td align="center" colspan="2">
      <fieldset>
        <legend><b>Funcionário</b></legend>
        <table>
          <tr>
            <td nowrap title="<?=@$Trh57_regist?>">
              <?
              db_ancora(@$Lrh57_regist,"js_pesquisarh57_regist(true);",$db_opcao);
              ?>
            </td>
            <td nowrap> 
              <?
              db_input('rh57_regist',6,$Irh57_regist,true,'text',$db_opcao," onchange='js_pesquisarh57_regist(false);'");
              ?>
              <?
              db_input('z01_nome',40,$Iz01_nome,true,'text',3,'');
              if(!isset($rh57_regist)){
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap align="center" colspan="2">
              <b>Selecione a matrícula</b>
              <?
              }
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
  <?if(isset($rh57_seq)){?>
  <tr>
    <td colspan="2" align="center">
      <fieldset>
        <legend><b>Atraso</b></legend>
        <table>
          <tr>
            <td nowrap title="Ano / Mês" align="right">
              <b>Ano / Mês:</b>
            </td>
            <td nowrap> 
              <?
              db_input('rh57_ano',4,$Irh57_ano,true,'text',3,"");
              ?>
              <b>/</b>
              <?
              db_input('rh57_mes',2,$Irh57_mes,true,'text',3,"");
              db_input('rh57_seq',6,$Irh57_seq,true,'hidden',3,"");
              ?>
            </td>
            <td nowrap title="<?=@$Trh57_valorini?>">
              <?=@$Lrh57_valorini?>
            </td>
            <td> 
              <?
              db_input('rh57_valorini',10,$Irh57_valorini,true,'text',3,"")
              ?>
            </td>
            <td nowrap title="<?=@$Trh57_saldo?>">
              <?=@$Lrh57_saldo?>
            </td>
            <td> 
              <?
              db_input('rh57_saldo',10,$Irh57_saldo,true,'text',3,"","valsaldo")
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Trh57_tipoatra?>">
              <?
              db_ancora(@$Lrh57_tipoatra,"js_pesquisarh57_tipoatra(true);",3);
              ?>
            </td>
            <td colspan="5" nowrap> 
              <?
              db_input('rh57_tipoatra',10,$Irh57_tipoatra,true,'text',3," onchange='js_pesquisarh57_tipoatra(false);'")
              ?>
              <?
              db_input('rh60_descr',48,$Irh60_descr,true,'text',3,'')
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <fieldset>
        <legend><b>Ocorrência</b></legend>
        <table>
          <tr>
            <td nowrap title="<?=@$Trh58_tipoocor?>" align="right">
              <?
              db_ancora(@$Lrh58_tipoocor,"js_pesquisarh58_tipoocor(true);",$db_opcao);
              ?>
            </td>
            <td colspan="3" nowrap> 
              <?
              db_input('rh58_tipoocor',8,$Irh58_tipoocor,true,'text',$db_opcao," onchange='js_pesquisarh58_tipoocor(false);'")
              ?>
              <?
              db_input('rh59_descr',44,$Irh59_descr,true,'text',3,'');
              db_input('rh59_tipo',2,$Irh59_tipo,true,'hidden',3,'');
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Trh58_obs?>" align="right" valign="top">
              <?
              db_ancora(@$Lrh58_obs,"",3);
              ?>
            </td>
            <td colspan="3" nowrap> 
              <?
              db_textarea("rh58_obs",3,52,$Irh58_obs,true,'text',$db_opcao);
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Trh58_data?>" align="right">
              <?=@$Lrh58_data?>
            </td>
            <td> 
              <?
              db_inputdata("rh58_data",@$rh58_data_dia,@$rh58_data_mes,@$rh58_data_ano,true,'text',$db_opcao);
              db_input('rh58_codigo',6,$Irh58_codigo,true,'hidden',3,"");
              ?>
            </td>
            <td nowrap title="<?=@$Trh58_valor?>" align="right">
              <?=@$Lrh58_valor?>
            </td>
            <td> 
              <?
              db_input('rh58_valor',10,$Irh58_valor,true,'text',$db_opcao,"onchange='js_verificavalor();'")
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <?
      $dbwhere = " rh58_seq = ".$rh57_seq." and rh58_data >= '".$r11_databaseatra."'";
      if(isset($rh58_codigo) && trim($rh58_codigo) != ""){
        $dbwhere.= " and rh58_codigo <> ".$rh58_codigo;
      }
      $sql = $clrhpagocor->sql_query(null," rh58_codigo, rh58_seq, rh58_tipoocor, rh59_descr, case when rh59_tipo = 'S' then 'Somar' else 'Subtrair' end as rh59_tipo, rh58_valor, rh58_data ","rh58_data",$dbwhere);
      $chavepri= array("rh58_codigo"=>@$rh58_codigo);
      $cliframe_alterar_excluir->chavepri = $chavepri;
      $cliframe_alterar_excluir->sql    = $sql;
      $cliframe_alterar_excluir->campos = "rh59_descr, rh59_tipo, rh58_valor, rh58_data";
      $cliframe_alterar_excluir->legenda= "OCORRÊNCIAS LANÇADAS";
      $cliframe_alterar_excluir->iframe_height = "200";
      $cliframe_alterar_excluir->iframe_width  = "100%";
      $cliframe_alterar_excluir->opcoes = 4;
      $cliframe_alterar_excluir->msg_vazio = "Sem ocorrências para este funcionário";
      $cliframe_alterar_excluir->formulario = false;
      $cliframe_alterar_excluir->iframe_alterar_excluir(1);
      ?>
    </td>
  </tr>
  <?
  }else if(isset($rh57_regist)){
    $result_justica = $clrhpesjustica->sql_record($clrhpesjustica->sql_query_file(null," * ", "", " rh61_regist = ".$rh57_regist." and ('".date("Y-m-d",db_getsession("DB_datausu"))."' between rh61_dataini and rh61_datafim or rh61_datafim is null) "));
    if($clrhpesjustica->numrows > 0){
      $funcionario_na_justica = true;
    }
  ?>
  <tr>
    <td colspan="2">
      <?
      $dbgroupby = "rh57_seq, rh57_ano, rh57_mes, rh57_regist, rh57_valorini, rh57_tipoatra"; 
      $dbhaving = " rh57_regist = ".$rh57_regist;
      if(isset($rh57_seq) && trim($rh57_seq) != ""){
        $dbhaving.= " and rh57_seq <> ".$rh57_seq;
      }
      if(!isset($mostrarsaldo) || (isset($mostrarsaldo) && $mostrarsaldo == "s")){
        $dbhaving.= "
                     and rh57_saldo > 0
                    ";
      }
      $sql = $clrhpagatra->sql_query_tipoatras(null,
                                               "
                                                distinct 
                                                rh57_seq,
                                                rh57_ano,
                                                rh57_mes,
                                                rh57_regist,
                                                rh57_valorini,
                                                rh57_saldo,
                                                rh57_tipoatra
                                               ",
                                               "rh57_ano,rh57_mes",
                                               $dbhaving
                                              );
      // die($sql);
      $chavepri= array("rh57_seq"=>@$rh57_seq,"rh57_regist"=>$rh57_regist,"rh57_ano"=>@$rh57_ano,"rh57_mes"=>@$rh57_mes);
      $cliframe_alterar_excluir->chavepri = $chavepri;
      $cliframe_alterar_excluir->sql    = $sql;
      $cliframe_alterar_excluir->campos = "rh57_ano, rh57_mes, rh57_regist, rh57_valorini, rh57_saldo";
      $cliframe_alterar_excluir->legenda= "ATRASOS LANÇADOS";
      $cliframe_alterar_excluir->iframe_height = "400";
      $cliframe_alterar_excluir->iframe_width  = "100%";
      $cliframe_alterar_excluir->opcoes = 2;
      $cliframe_alterar_excluir->msg_vazio = "Sem atrasos para este funcionário";
      $cliframe_alterar_excluir->formulario = false;
      $cliframe_alterar_excluir->iframe_alterar_excluir(1);
      ?>
    </td>
  </tr>
  <tr>
    <td align="right"><b>Mostrar:</b></td>
    <td>
      <?
      $arr_mostrar = Array("s"=>"Somente com saldo","t"=>"Todos atrasos");
      db_select("mostrarsaldo", $arr_mostrar, true, $db_opcao, "onchange='document.form1.submit();'");
      ?>
    </td>
  </tr>
  <?}?>
  <tr>
    <td align="center">
      <?if(isset($rh57_regist) && isset($rh57_seq)){?>
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>>
      <?}?>
      <?if(isset($opcao) && isset($rh58_codigo) && trim($rh58_codigo) != ""){?>
      <input name="novo" type="button" id="novo" value="Nova movimentação" onclick="location.href='pes1_rhpagatra001.php?rh57_regist=<?=$rh57_regist?>&rh57_seq=<?=$rh57_seq?>'" >
      <?}?>
      <?if(isset($rh57_seq) && trim($rh57_seq) != ""){?>
      <input name="novo" type="button" id="novo" value="Outro atraso" onclick="location.href='pes1_rhpagatra001.php?rh57_regist=<?=$rh57_regist?>'" >
      <?}?>
      <?if($db_opcao == 3){?>
      <input name="outro" type="button" id="novo" value="Outro funcionário" onclick="location.href='pes1_rhpagatra001.php'" >
      <?}?>
    </td>
  </tr>
</table>
</center>
</form>
<script>
function js_verificavalor(){
  if(document.form1.rh58_tipoocor.value != ""){
    if(document.form1.rh59_tipo.value.search("D") != -1){
      valordigitado = new Number(document.form1.rh58_valor.value);
      valortotsaldo = new Number("<?=@$rh57_saldo?>");
      if(valordigitado > valortotsaldo){
        alert("Valor digitado maior que o valor de saldo. Verifique.");
        document.form1.rh58_valor.value = "";
        document.form1.rh58_valor.focus();
      }
    }
  }
}
function js_pesquisarh58_tipoocor(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhpagtipoocor','func_rhpagtipoocor.php?funcao_js=parent.js_mostrarhpagtipoocor1|rh59_codigo|rh59_descr|rh59_tipo','Pesquisa',true);
  }else{
     if(document.form1.rh58_tipoocor.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_rhpagtipoocor','func_rhpagtipoocor.php?pesquisa_chave='+document.form1.rh58_tipoocor.value+'&funcao_js=parent.js_mostrarhpagtipoocor','Pesquisa',false);
     }else{
       document.form1.rh59_descr.value = '';
     }
  }
}
function js_mostrarhpagtipoocor(chave,chave2,erro){
  document.form1.rh59_descr.value = chave;
  if(erro==true){
    document.form1.rh58_tipoocor.focus();
    document.form1.rh58_tipoocor.value = '';
    document.form1.rh59_tipo.value = '';
  }else{
    document.form1.rh59_tipo.value = chave2;
  }
  js_verificavalor();
}
function js_mostrarhpagtipoocor1(chave1,chave2,chave3){
  document.form1.rh58_tipoocor.value = chave1;
  document.form1.rh59_descr.value = chave2;
  document.form1.rh59_tipo.value = chave3;
  db_iframe_rhpagtipoocor.hide();
  js_verificavalor();
}
function js_pesquisarh57_regist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoal','func_rhpessoalrecis.php?funcao_js=parent.js_mostrarhpessoal1|rh01_regist|z01_nome|rh05_recis','Pesquisa',true);
  }else{
     if(document.form1.rh57_regist.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoal','func_rhpessoalrecis.php?pesquisa_chave='+document.form1.rh57_regist.value+'&funcao_js=parent.js_mostrarhpessoal','Pesquisa',false);
     }else{
       location.href = "pes1_rhpagatra001.php";
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostrarhpessoal(chave,admiss,seqpes,proc1,proc2,per1f,per2f,codreg,matipe,dtvinc,rescis,erro){
  document.form1.z01_nome.value = chave;
  if(erro==true){ 
    document.form1.rh57_regist.focus(); 
    document.form1.rh57_regist.value = ''; 
  }else if(rescis != ""){
    alert("ALERTA: Funcionário rescindido.");
  }
  location.href = "pes1_rhpagatra001.php?rh57_regist="+document.form1.rh57_regist.value;
}
function js_mostrarhpessoal1(chave1,chave2,chave3){
  document.form1.rh57_regist.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_rhpessoal.hide();
  if(chave3 != ""){
    alert("ALERTA: Funcionário rescindido.");
  }
  location.href = "pes1_rhpagatra001.php?rh57_regist="+chave1;
}
function js_pesquisarh57_tipoatra(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhtipoatras','func_rhtipoatras.php?funcao_js=parent.js_mostrarhtipoatras1|rh60_codigo|rh60_descr','Pesquisa',true);
  }else{
     if(document.form1.rh57_tipoatra.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_rhtipoatras','func_rhtipoatras.php?pesquisa_chave='+document.form1.rh57_tipoatra.value+'&funcao_js=parent.js_mostrarhtipoatras','Pesquisa',false);
     }else{
       document.form1.rh60_descr.value = ''; 
     }
  }
}
function js_mostrarhtipoatras(chave,erro){
  document.form1.rh60_descr.value = chave; 
  if(erro==true){ 
    document.form1.rh57_tipoatra.focus(); 
    document.form1.rh57_tipoatra.value = ''; 
  }
}
function js_mostrarhtipoatras1(chave1,chave2){
  document.form1.rh57_tipoatra.value = chave1;
  document.form1.rh60_descr.value = chave2;
  db_iframe_rhtipoatras.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_rhpagatra','func_rhpagatra.php?funcao_js=parent.js_preenchepesquisa|rh57_seq','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_rhpagatra.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>
<?
if($funcionario_na_justica == true){
  db_msgbox("ALERTA: Funcionário na justiça.");
}
?>