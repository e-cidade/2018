<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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
include(modification("dbforms/db_classesgenericas.php"));
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clrhpesrescisao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("r30_regist");
$clrotulo->label("rh01_admiss");
$clrotulo->label("z01_nome");
$clrotulo->label("r59_descr");
$clrotulo->label("r59_descr1");
$clrotulo->label("r59_menos1");
$clrotulo->label("r59_aviso");
$clrotulo->label("rh02_seqpes");
$clrotulo->label("rh02_codreg");
$db_opcao = 2;
$rh05_causa  = $causa;     
$rh05_caub   = $caub;     
$rh05_recis  = $rescisao;  
$rh05_recis_dia = $recis_dia;
$rh05_recis_mes = $recis_mes;
$rh05_recis_ano = $recis_ano;
$rh05_taviso = $taviso;    
$rh05_aviso  = $aviso;     
$rh05_aviso_dia = $aviso_dia;
$rh05_aviso_mes = $aviso_mes;
$rh05_aviso_ano = $aviso_ano;
$rh05_mremun = $remun;
$r59_descr1 = $descr1;
$r59_descr = $descr;
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td align="center">
      <fieldset>
        <legend align="left"><b>FUNCIONÁRIO</b></legend>
        <table width="100%">
        <tr>
          <td align="center">
            <hr>
            <b><?=$r30_regist." - ".$z01_nome?></b>
            <hr>
          </td>
        </tr>
        </table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td align="center">
      <fieldset>
        <legend align="left"><b>RESCISÃO</b></legend>
        <table width="100%">
          <tr>
            <td nowrap title="<?=@$Trh05_recis?>" align="right">
              <?=@$Lrh05_recis?>
            </td>
            <td>
              <?
              db_inputdata('rh05_recis',@$rh05_recis_dia,@$rh05_recis_mes,@$rh05_recis_ano,true,'text',$db_opcao,"onchange='js_validarecis();'","","","parent.js_validarecis();")
              ?>
            </td>
            <td nowrap title="<?=@$Trh05_causa?>" align="right">
              <?
              db_ancora(@$Lrh05_causa,"js_pesquisarh05_causa(true);",$db_opcao);
              ?>
            </td>
            <td>
              <?
              db_input('rh05_causa',6,$Irh05_causa,true,'text',3,"")
              ?>
              <?
              db_input('r59_descr',40,$Ir59_descr,true,'text',3,"")
              ?>
            </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td nowrap title="<?=@$Trh05_caub?>" align="right">
              <?
              db_ancora(@$Lrh05_caub,"",3);
              ?>
            </td>
            <td>
              <?
              db_input('rh05_caub',6,$Irh05_caub,true,'text',3,"")
              ?>
              <?
              db_input('r59_descr1',40,$Ir59_descr1,true,'text',3,"");
              db_input('r59_menos1',4,0,true,'hidden',3);
              db_input('r30_regist', 7, 0, true, 'hidden', 3);
              db_input('campomatriculas', 4, 0, true, 'hidden', 3);
              db_input('selecao', 4, 0, true, 'hidden', 3);
              db_input('tipo', 4, 0, true, 'hidden', 3);
              db_input('r59_anousu', 4, 0, true, 'hidden', 3);
              db_input('r59_mesusu', 2, 0, true, 'hidden', 3);
              db_input('rh02_codreg', 4, 0, true, 'hidden', 3);
              db_input('rh02_seqpes', 6, 0, true, 'hidden', 3);
              db_input('rh01_admiss', 8, 0, true, 'hidden', 3);
              db_input('pagar_13_salario_na_rescisao', 6, 0, true, 'hidden', 3);
              db_input('causa', 6, 0, true, 'hidden', 3);
              db_input('caub', 6, 0, true, 'hidden', 3);
              db_input('rescisao', 8, 0, true, 'hidden', 3);
              db_input('taviso', 1, 0, true, 'hidden', 3);
              db_input('aviso', 6, 0, true, 'hidden', 3);
              db_input('remun', 6, 0, true, 'hidden', 3);
              db_input('recis_ano', 4, 0, true, 'hidden', 3);
              db_input('recis_mes', 2, 0, true, 'hidden', 3);
              db_input('recis_dia', 2, 0, true, 'hidden', 3);
              db_input('aviso_ano', 4, 0, true, 'hidden', 3);
              db_input('aviso_mes', 2, 0, true, 'hidden', 3);
              db_input('aviso_dia', 2, 0, true, 'hidden', 3);
              db_input('descr',40,0,true,'hidden',3);
              db_input('descr1',40,0,true,'hidden',3);
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Trh05_taviso?>" align="right">
              <?
              db_ancora(@$Lrh05_taviso,"",3);
              ?>
            </td>
            <td>
              <?
              if(!isset($rh05_taviso)){
              	$rh05_taviso = 3;
              }
              $x = array("1"=>"Trabalhado","2"=>"Aviso indenizado","3"=>"Sem aviso");
              db_select('rh05_taviso',$x,true,$db_opcao,"onchange='js_disabdata(this.value);'");
              ?>
            </td>
            <td nowrap title="<?=@$Trh05_aviso?>" align="right">
              <?=@$Lrh05_aviso?>
							
            </td>
            <td>
              <?
              db_inputdata('rh05_aviso',@$rh05_aviso_dia,@$rh05_aviso_mes,@$rh05_aviso_ano,true,'text',$db_opcao,"onchange='js_validaaviso(1);'","","","parent.js_validaaviso(1);","js_validaaviso(2);","js_validaaviso(2);")
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Trh05_mremun?>" align="right">
              <?
              db_ancora(@$Lrh05_mremun,"",3);
              ?>
            </td>
            <td>
              <?
              db_input('rh05_mremun',6,$Irh05_mremun,true,'text',$db_opcao,"")
              ?>
            </td>
            <td colspan="2" id="caixa_de_texto">
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td align="center">
     <input name="enviar" type="button" id="db_opcao" value="Processar dados" <?=($db_botao==false?"disabled":"")?> onclick="js_verificadados();">
     <input name="voltar" type="button" id="voltar" value="Nova Seleção" onclick="location.href = 'pes4_rhpesrescislote001.php';">
     <?if(isset($campomatriculas) && trim($campomatriculas) != ""){?>
     <input name="proximo" type="submit" id="proximo" value="Próximo">
     <?}?>
    </td>
  </tr>
</table> 
</center>
</form>
<script>
function js_faltas(registro){
	qry = 'opcao=enviarescis';
  qry+= '&causa='+document.form1.rh05_causa.value;
  qry+= '&regime='+document.form1.rh02_codreg.value;
  qry+= '&regist='+document.form1.r30_regist.value;
  qry+= '&rh05_recis_ano='+document.form1.rh05_recis_ano.value;
  qry+= '&rh05_recis_mes='+document.form1.rh05_recis_mes.value;
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_faltas','func_scriptsdb.php?'+qry,'Pesquisa',false);
}
function js_verificadados() {

  if(document.form1.r30_regist.value == ""){
    alert("Informe a matrícula do funcionário.");
    document.form1.r30_regist.focus();
  }else if(document.form1.rh05_recis_dia.value == "" || document.form1.rh05_recis_mes.value == "" || document.form1.rh05_recis_ano.value == ""){
    alert("Informe a data da rescisão.");
    document.form1.rh05_recis.focus();
    document.form1.rh05_recis.select();
  }else if(document.form1.rh05_causa.value == ""){
    alert("Informe a causa da rescisão.");
    document.form1.rh05_causa.focus();
  }else{
    js_faltas(document.form1.r30_regist.value);
    document.form1.action = 'pes4_rhpesrescis002.php';
  }
}
function js_validaaviso(opcao){
  x = document.form1;
  document.getElementById('caixa_de_texto').innerHTML = "";
  if(x.rh05_recis_dia.value != ""  && x.rh05_recis_mes.value != "" && x.rh05_recis_ano.value != "" && x.rh05_causa.value != ""){
    if(x.rh05_aviso_dia.value != ""  && x.rh05_aviso_mes.value != "" && x.rh05_aviso_ano.value != ""){
      dtadmiss = new Date(x.rh01_admiss_ano.value,(x.rh01_admiss_mes.value - 1),x.rh01_admiss_dia.value);
      dtreciss = new Date(x.rh05_recis_ano.value,(x.rh05_recis_mes.value - 1),x.rh05_recis_dia.value);
      dtavisos = new Date(x.rh05_aviso_ano.value,(x.rh05_aviso_mes.value - 1),x.rh05_aviso_dia.value);
      if(dtreciss < dtadmiss){
	alert("Data de rescisão não pode ser posterior a data de admissão. Verifique.");
	x.rh05_recis_dia.value = "";
	x.rh05_recis_mes.value = "";
	x.rh05_recis_ano.value = "";
	x.rh05_recis.value = "";
	//x.rh05_recis_dia.focus();
	x.rh05_recis.focus();
      }else if(x.r59_aviso.value == 't' && dtavisos > dtadmiss && dtavisos <= dtreciss){
	mensagem = "Para Aviso Indenizado - data aviso = rescisao.";
        document.getElementById('caixa_de_texto').innerHTML = "<b><font color='red'>"+mensagem+"</font></b>";
      }
    }
  }else if(opcao == 1){
    if(x.rh05_causa.value == ""){
      alert("Informe a causa da rescisão.");
    }else{
      alert("Informe a data da rescisão.");
    }
    x.rh05_aviso_dia.value = "";
    x.rh05_aviso_mes.value = "";
    x.rh05_aviso_ano.value = "";
    x.rh05_aviso.value = "";
    x.rh05_aviso.focus(); 
  }
}
function js_validarecis(){
  x = document.form1;
  if(x.r30_regist.value != ""){
    if(x.rh05_recis_dia.value != ""  && x.rh05_recis_mes.value != "" && x.rh05_recis_ano.value != ""){
      anoatual = "<?=db_anofolha()?>";
      mesatual = "<?=db_anofolha()?>";
      anorecis = x.rh05_recis_ano.value;
      mesrecis = x.rh05_recis_mes.value;

      if(mesatual.length < 2){
        mesatual = "0"+mesatual;
      }
      if(mesrecis.length < 2){
        mesrecis = "0"+mesrecis;
      }

      anomesatual = new Number(anoatual+mesatual);
      anomesrecis = new Number(anorecis+mesrecis);

      dtadmiss = new Date(x.rh01_admiss_ano.value,(x.rh01_admiss_mes.value - 1),x.rh01_admiss_dia.value);
      dtreciss = new Date(x.rh05_recis_ano.value,(x.rh05_recis_mes.value - 1),x.rh05_recis_dia.value);
      dtatualh = new Date(anoatual,mesatual,1);
      if(dtreciss < dtadmiss){
        alert("Data de rescisão não pode ser posterior a data de admissão. Verifique.");
        x.rh05_recis_dia.value = "";
        x.rh05_recis_mes.value = "";
        x.rh05_recis_ano.value = "";
        x.rh05_aviso_dia.value = "";
        x.rh05_aviso_mes.value = "";
        x.rh05_aviso_ano.value = "";
        x.rh05_recis_dia.focus();
      }else if(anoatual > anorecis){
        alert("ALERTA: Data da rescisão com ano anterior ao atual.");
      }else if(anomesatual < anomesrecis){
        alert("anomesatual :  "+anomesatual+"  anomesrecis : "+anomesrecis);
        alert("ALERTA: Data da rescisão posterior ao ano / mês atual..");
      }
    }
  }else{
    alert("Informe a matrí­cula do funcionário.");
    x.rh05_recis_dia.value = "";
    x.rh05_recis_mes.value = "";
    x.rh05_recis_ano.value = "";
    x.rh05_recis.value = "";
    x.r30_regist.focus();
  }
}
function js_disabdata(valor){
  x = document.form1;
  if(valor == 1 && x.r59_aviso.value == 't'){

    dtadmiss = new Date(x.rh01_admiss_ano.value,(x.rh01_admiss_mes.value - 1),x.rh01_admiss_dia.value);
    dtreciss = new Date(x.rh05_recis_ano.value,(x.rh05_recis_mes.value - 1),(x.rh05_recis_dia.value - 30));
    if(dtreciss < dtadmiss){
      document.form1.rh05_aviso_dia.value = x.rh01_admiss_dia.value;
      document.form1.rh05_aviso_mes.value = x.rh01_admiss_mes.value;
      document.form1.rh05_aviso_ano.value = x.rh01_admiss_ano.value;
      document.form1.rh05_aviso.value = x.rh01_admiss_dia.value+'/'+x.rh01_admiss_mes.value+'/'+x.rh01_admiss_ano.value;
    }else{
      month = (dtreciss.getMonth() + 1);
      document.form1.rh05_aviso_dia.value = (dtreciss.getDate()<10)?"0"+dtreciss.getDate():dtreciss.getDate();
      document.form1.rh05_aviso_mes.value = (month<10)?"0"+month:month;
      document.form1.rh05_aviso_ano.value = dtreciss.getFullYear();
      document.form1.rh05_aviso.value = (dtreciss.getDate()<10)?"0"+dtreciss.getDate():dtreciss.getDate()+'/'+(month<10)?"0"+month:month+'/'+dtreciss.getFullYear();
    }
    document.form1.dtjs_rh05_aviso.disabled = false;
    document.form1.rh05_aviso_dia.readOnly  = false;
    document.form1.rh05_aviso_mes.readOnly  = false;
    document.form1.rh05_aviso_ano.readOnly  = false;

    document.form1.rh05_aviso_dia.style.backgroundColor='';
    document.form1.rh05_aviso_mes.style.backgroundColor='';
    document.form1.rh05_aviso_ano.style.backgroundColor='';
    js_tabulacaoforms("form1","rh05_aviso_dia",true,1,"rh05_aviso_dia",true);
  }else{
    document.form1.dtjs_rh05_aviso.disabled = true;
    document.form1.rh05_aviso_dia.readOnly  = true;
    document.form1.rh05_aviso_mes.readOnly  = true;
    document.form1.rh05_aviso_ano.readOnly  = true;
    document.form1.rh05_aviso.readOnly  = true;

    document.form1.rh05_aviso_dia.style.backgroundColor='#DEB887';
    document.form1.rh05_aviso_mes.style.backgroundColor='#DEB887';
    document.form1.rh05_aviso_ano.style.backgroundColor='#DEB887';
    document.form1.rh05_aviso.style.backgroundColor='#DEB887';

    document.form1.rh05_aviso_dia.value  = "";
    document.form1.rh05_aviso_mes.value  = "";
    document.form1.rh05_aviso_ano.value  = "";
    document.form1.rh05_aviso.value  = "";
    js_tabulacaoforms("form1","r30_regist",false,1,"r30_regist",false);
    document.form1.rh05_mremun.focus()
  }
}
function js_pesquisarh05_causa(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_rescisao','func_rescisaoaviso.php?testarescisao=raf&funcao_js=parent.js_mostrarescisao1|r59_causa|r59_descr|r59_caub|r59_descr1|r59_aviso|r59_menos1&chave_r59_anousu=<?=db_anofolha()?>&chave_r59_mesusu=<?=db_mesfolha()?>&regime='+document.form1.rh02_codreg.value,'Pesquisa',true);
  }else{
    if(document.form1.rh05_causa.value != ''){ 
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_rescisao','func_rescisaoaviso.php?testarescisao=raf&pesquisa_chave='+document.form1.rh05_causa.value+'&funcao_js=parent.js_mostrarescisao&ano=<?=db_mesfolha()?>&mes=<?=db_mesfolha()?>&regime='+document.form1.rh02_codreg.value,'Pesquisa',false);
    }else{
      document.form1.rh05_caub.value  = '';
      document.form1.r59_descr.value  = '';
      document.form1.r59_descr1.value = '';
      document.form1.r59_menos1.value = '';
      document.form1.rh05_aviso_dia.value  = "";
      document.form1.rh05_aviso_mes.value  = "";
      document.form1.rh05_aviso_ano.value  = "";
      document.form1.rh05_aviso.value  = "";
      js_disabdata(document.form1.rh05_taviso.value);
    }
  }
}
function js_mostrarescisao(chave,chave2,chave3,chave4,chave5,erro){
  document.form1.r59_descr.value = chave; 
  if(erro==true){ 
    document.form1.rh05_causa.focus(); 
    document.form1.rh05_causa.value = ''; 
    document.form1.rh05_caub.value  = '';
    document.form1.r59_descr1.value = '';
    document.form1.r59_menos1.value = '';
    document.form1.r59_aviso.value = '';
    document.form1.rh05_aviso_dia.value  = "";
    document.form1.rh05_aviso_mes.value  = "";
    document.form1.rh05_aviso_ano.value  = "";
    document.form1.rh05_aviso.value  = "";
  }else{
    document.form1.rh05_caub.value   = chave2;
    document.form1.r59_descr1.value  = chave3;
    document.form1.r59_aviso.value = chave4;
    document.form1.r59_menos1.value = chave5;
  }
  js_disabdata(document.form1.rh05_taviso.value);
  js_validaaviso(1);
}
function js_mostrarescisao1(chave1,chave2,chave3,chave4,chave5,chave6){
  db_iframe_rescisao.hide();
  document.form1.rh05_causa.value = chave1;
  document.form1.r59_descr.value  = chave2;
  document.form1.rh05_caub.value  = chave3;
  document.form1.r59_descr1.value = chave4;
  document.form1.r59_aviso.value = chave5;
  document.form1.r59_menos1.value = chave6;
  
  db_iframe_rescisao.hide(); // alterado
 
  js_disabdata(document.form1.rh05_taviso.value);
  js_validaaviso(1);
  
}
js_disabdata("<?=($rh05_taviso)?>");
</script>
