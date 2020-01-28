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
$clrotulo->label("rh01_regist");
$clrotulo->label("rh01_admiss");
$clrotulo->label("z01_nome");
$clrotulo->label("r59_descr");
$clrotulo->label("r59_descr1");
$clrotulo->label("r59_menos1");
$clrotulo->label("r59_aviso");
$clrotulo->label("rh02_seqpes");
$clrotulo->label("rh02_codreg");
?>
<form name="form1" method="post" action="pes4_rhpesrescis002.php">
<center>
<table border="0">
  <tr>
    <td align="center">
      <fieldset>
        <legend align="left"><b>FUNCIONÁRIO</b></legend>
        <table width="100%">
          <tr>
            <td nowrap title="<?=@$Trh01_regist?>">
	      <?
              db_ancora(@$Lrh01_regist,"js_pesquisarh01_regist(true);",$db_opcao);
	      ?>
            </td>
            <td>
              <?
              db_input('rh01_regist',6,$Irh01_regist,true,'text',$db_opcao,"onchange='js_pesquisarh01_regist(false);'");
              db_input('rh02_seqpes',6,$Irh02_seqpes,true,'text',3,"");
              db_input('rh02_codreg',6,$Irh02_codreg,true,'text',3,"");
              db_input('r59_aviso',6,$Ir59_aviso,true,'hidden',3,"");
              db_input('pagar_13_salario_na_rescisao',6,0,true,'hidden',3,"");
              ?>
              <?
              db_input('z01_nome',40,$Iz01_nome,true,'text',3,"")
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Trh01_admiss?>">
              <?
              db_ancora(@$Lrh01_admiss,"",3);
              ?>
            </td>
            <td>
              <?
              db_inputdata('rh01_admiss',@$rh01_admiss_dia,@$rh01_admiss_mes,@$rh01_admiss_ano,true,'text',3,"")
              ?>
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
            <td nowrap title="<?=@$Trh05_recis?>">
              <?=@$Lrh05_recis?>
            </td>
            <td>
              <?
              db_inputdata('rh05_recis',@$rh05_recis_dia,@$rh05_recis_mes,@$rh05_recis_ano,true,'text',$db_opcao,"onchange='js_validarecis();'","","","parent.js_validarecis();")
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Trh05_causa?>">
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
            <td nowrap title="<?=@$Trh05_caub?>">
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
              db_input('r59_menos1',4,$Ir59_menos1,true,'hidden',3,"");
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Trh05_taviso?>">
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
          </tr>
          <tr>
            <td nowrap title="<?=@$Trh05_aviso?>">
              <?=@$Lrh05_aviso?>
            </td>
            <td>
              <?php db_inputdata('rh05_aviso',@$rh05_aviso_dia,@$rh05_aviso_mes,@$rh05_aviso_ano,true,'text',$db_opcao,"onchange='js_validaaviso(1);'","","","parent.js_validaaviso(1);","js_validaaviso(2);","js_validaaviso(2);"); ?>
            </td>
          </tr>

          <tr>
            <td nowrap title="<?=@$Trh05_mremun?>">
              <?
              db_ancora(@$Lrh05_mremun,"",3);
              ?>
            </td>
            <td>
              <?
              $rh05_mremun = 0;
              db_input('rh05_mremun', 10,$Irh05_mremun,true,'text',$db_opcao,"")
              ?>
            </td>
            <td colspan="2" id="caixa_de_texto">
            </td>
          </tr>

          <tr>
            <td nowrap title="<?php echo $Trh05_codigoseguranca?>">
              <?php db_ancora(@$Lrh05_codigoseguranca,"",3); ?>
            </td>
            <td>
              <?php db_input('rh05_codigoseguranca',10, $Irh05_codigoseguranca, true, 'text', $db_opcao); ?>
            </td>
            <td colspan="2" id="caixa_de_texto">
            </td>
          </tr>

          <tr>
            <td nowrap title="<?php echo $Trh05_trct?>">
              <?php db_ancora(@$Lrh05_trct,"",3); ?>
            </td>
            <td>
              <?php db_input('rh05_trct',10, $Irh05_trct, true, 'text', $db_opcao); ?>
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
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="button" id="db_opcao" value="<?=($db_opcao==1?"Processar":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>  <?if($db_opcao!=3)echo "onclick='js_verificadados();'";?>>
    </td>
  </tr>
</table> 
<script>
function js_faltas(registro){
	qry = 'opcao=enviarescis';
  qry+= '&causa='+document.form1.rh05_causa.value;
  qry+= '&regime='+document.form1.rh02_codreg.value;
  qry+= '&regist='+document.form1.rh01_regist.value;
  qry+= '&rh05_recis_ano='+document.form1.rh05_recis_ano.value;
  qry+= '&rh05_recis_mes='+document.form1.rh05_recis_mes.value;
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_faltas','func_scriptsdb.php?'+qry,'Pesquisa',false);
}
function js_verificadados(){
  x = document.form1;
  if(x.rh01_regist.value == ""){
    alert("Informe a matrícula do funcionário.");
    x.rh01_regist.focus();
  }else if(x.rh05_recis_dia.value == "" || x.rh05_recis_mes.value == "" || x.rh05_recis_ano.value == ""){
    alert("Informe a data da rescisão.");
//    x.rh05_recis_dia.focus();
//    x.rh05_recis_dia.select();
    x.rh05_recis.focus();
    x.rh05_recis.select();
  }else if(x.rh05_causa.value == ""){
    alert("Informe a causa da rescisão.");
    x.rh05_causa.focus();
  }else{
    js_faltas(x.rh01_regist.value);
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
	alert("Data de rescisão não pode ser posterior à data de admissão. Verifique.");
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
//    x.rh05_aviso_dia.focus(); 
    x.rh05_aviso.focus(); 
  }
}
function js_validarecis(){
  x = document.form1;
  if(x.rh01_regist.value != ""){
    if(x.rh05_recis_dia.value != ""  && x.rh05_recis_mes.value != "" && x.rh05_recis_ano.value != ""){
      anoatual = "<?=$rh02_anousu?>";
      mesatual = "<?=$rh02_mesusu?>";
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
      if (dtreciss < dtadmiss) {
        alert("Data de rescisão não pode ser posterior à data de admissão. Verifique.");
        x.rh05_recis_dia.value = "";
        x.rh05_recis_mes.value = "";
        x.rh05_recis_ano.value = "";
        x.rh05_aviso_dia.value = "";
        x.rh05_aviso_mes.value = "";
        x.rh05_aviso_ano.value = "";
        x.rh05_recis_dia.focus();
      } else if(anoatual > anorecis) {
        alert("ALERTA: Data da rescisão com ano anterior ao atual.");
      }
    }
  }else{
    alert("Informe a matrícula do funcionário.");
    x.rh05_recis_dia.value = "";
    x.rh05_recis_mes.value = "";
    x.rh05_recis_ano.value = "";
    x.rh05_recis.value = "";
    x.rh01_regist.focus();
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
    js_tabulacaoforms("form1","rh01_regist",false,1,"rh01_regist",false);
    document.form1.rh05_mremun.focus()
  }
}
function js_pesquisarh05_causa(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_rescisao','func_rescisaoaviso.php?testarescisao=raf&funcao_js=parent.js_mostrarescisao1|r59_causa|r59_descr|r59_caub|r59_descr1|r59_aviso|r59_menos1&chave_r59_anousu=<?=$rh02_anousu?>&chave_r59_mesusu=<?=$rh02_mesusu?>&regime='+document.form1.rh02_codreg.value,'Pesquisa',true);
  }else{
    if(document.form1.rh05_causa.value != ''){ 
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_rescisao','func_rescisaoaviso.php?testarescisao=raf&pesquisa_chave='+document.form1.rh05_causa.value+'&funcao_js=parent.js_mostrarescisao&ano=<?=$rh02_anousu?>&mes=<?=$rh02_mesusu?>&regime='+document.form1.rh02_codreg.value,'Pesquisa',false);
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
  document.form1.rh05_causa.value = chave1;
  document.form1.r59_descr.value  = chave2;
  document.form1.rh05_caub.value  = chave3;
  document.form1.r59_descr1.value = chave4;
  document.form1.r59_aviso.value = chave5;
  document.form1.r59_menos1.value = chave6;
  
  db_iframe_rescisao.hide(); // alterado
 
  js_disabdata(document.form1.rh05_taviso.value);
  js_validaaviso(1);
  
 //db_iframe_rescisao.hide(); // original
}
function js_pesquisarh01_regist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_rhpessoal','func_rhpessoaladmiss.php?testarescisao=raf&funcao_js=parent.js_mostrarhpessoal1|rh01_regist|z01_nome|rh01_admiss|rh02_seqpes|r30_proc1|r30_proc2|r30_per1f|r30_per2f|rh02_codreg|rh14_matipe|rh14_dtvinc&instit=<?=db_getsession("DB_instit")?>','Pesquisa',true);
  }else{
     if(document.form1.rh01_regist.value != ''){ 
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_rhpessoal','func_rhpessoaladmiss.php?testarescisao=raf&pesquisa_chave='+document.form1.rh01_regist.value+'&funcao_js=parent.js_mostrarhpessoal&instit=<?=db_getsession("DB_instit")?>','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = '';
       document.form1.rh01_admiss_dia.value = '';
       document.form1.rh01_admiss_mes.value = '';
       document.form1.rh01_admiss_ano.value = '';
       document.form1.rh01_admiss.value = '';
       document.form1.rh05_recis_dia.value  = '';
       document.form1.rh05_recis_mes.value  = '';
       document.form1.rh05_recis_ano.value  = '';
       document.form1.rh05_recis.value  = '';
       document.form1.rh05_causa.value      = '';
       document.form1.r59_descr.value       = '';
       document.form1.rh05_caub.value       = '';
       document.form1.r59_descr1.value      = '';
       document.form1.rh05_aviso_dia.value  = '';
       document.form1.rh05_aviso_mes.value  = '';
       document.form1.rh05_aviso_ano.value  = '';
       document.form1.rh05_aviso.value  = '';
       document.form1.rh05_mremun.value     = ''; 
       document.form1.rh05_taviso.options[0].selected = true;
       js_disabdata(document.form1.rh05_taviso.value);
     }
  }
}
function js_mostrarhpessoal(chave,chave2,chave3,chave4,chave5,chave6,chave7,chave8,chave9,chave10,erro,temresci){
  document.form1.z01_nome.value = chave;
  if(erro==true||temresci=='s'){ 
		if (temresci=='s'){
			document.form1.z01_nome.value = "";
		}
    document.form1.rh01_regist.focus(); 
    document.form1.rh01_regist.value = ''; 
    document.form1.rh01_admiss_ano.value = '';
    document.form1.rh01_admiss_mes.value = '';
    document.form1.rh01_admiss_dia.value = '';
    document.form1.rh01_admiss.value = '';
  }else{
    document.form1.rh01_admiss_ano.value = chave2.substring(0,4);
    document.form1.rh01_admiss_mes.value = chave2.substring(5,7);
    document.form1.rh01_admiss_dia.value = chave2.substring(8,10);
    document.form1.rh01_admiss.value = chave2.substring(8,10)+'/'+chave2.substring(5,7)+'/'+chave2.substring(0,4);
    document.form1.rh02_seqpes.value = chave3;
    document.form1.rh02_codreg.value = chave8;
    per1f = new Date(chave6.substring(0,4),(chave6.substring(5,7) - 1), 1);
    per2f = new Date(chave7.substring(0,4),(chave7.substring(5,7) - 1), 1);
    subps = new Date("<?=db_anofolha()?>","<?=db_mesfolha() - 1?>", 1);
    ultdi = new Date("<?=db_anofolha()?>","<?=db_mesfolha() - 1?>","<?=db_dias_mes(db_anofolha(),db_mesfolha())?>");

    anmes = '<?=db_anofolha()."/".db_mesfolha()?>';

    if(per1f >= ultdi || per2f >= ultdi){
      alert("Gozo de férias integrais neste ou no próximo mês.");
      location.href = "pes4_rhpesrescis001.php";
    }else if(chave4 == anmes || chave5 == anmes || per1f >= subps || per2f >= subps){
//      alert("Funcionário tem pagamento/gozo de férias no mês");
    }
    if(chave9 != "" && chave10 != ""){
      alert("Se funcionário vinculado ao IPE, informe nova situação (Cadastro de IPE).");
    }
  }
}
function js_mostrarhpessoal1(chave1,chave2,chave3,chave4,chave5,chave6,chave7,chave8,chave9,chave10,chave11){
  document.form1.rh01_regist.value = chave1;
  document.form1.z01_nome.value = chave2;
  document.form1.rh01_admiss_ano.value = chave3.substring(0,4);
  document.form1.rh01_admiss_mes.value = chave3.substring(5,7);
  document.form1.rh01_admiss_dia.value = chave3.substring(8,10);
  document.form1.rh01_admiss.value = chave3.substring(8,10)+'/'+chave3.substring(5,7)+'/'+chave3.substring(0,4);
  document.form1.rh02_seqpes.value = chave4;
  document.form1.rh02_codreg.value = chave9;

  per1f = new Date(chave7.substring(0,4),(chave7.substring(5,7) - 1), 1);
  per2f = new Date(chave8.substring(0,4),(chave8.substring(5,7) - 1), 1);
  subps = new Date("<?=db_anofolha()?>","<?=db_mesfolha() - 1?>", 1);
  ultdi = new Date("<?=db_anofolha()?>","<?=db_mesfolha() - 1?>","<?=db_dias_mes(db_anofolha(),db_mesfolha())?>");

  anmes = '<?=db_anofolha()."/".db_mesfolha()?>';

  if(per1f >= ultdi || per2f >= ultdi){
    alert("Gozo de férias integrais neste ou no próximo mês.");
    location.href = "pes4_rhpesrescis001.php";
  }else if(chave5 == anmes || chave6 == anmes || per1f >= subps || per2f >= subps){
 //   alert("Funcionário tem pagamento/gozo de férias no mês");
  }
  if(chave10 != "" && chave11 != ""){
    alert("Se funcionário vinculado ao IPE, informe nova situação (Cadastro de IPE).");
  }
  db_iframe_rhpessoal.hide();
}
js_disabdata("<?=($rh05_taviso)?>");
</script>