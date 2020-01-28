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
$clcadferia->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td align="right" nowrap title="<?=@$Tr30_regist?>">
      <?
      db_ancora(@$Lr30_regist, "js_pesquisar30_regist(true);", $db_opcao);
      ?>
    </td>
    <td> 
      <?
      db_input('r30_regist', 6, $Ir30_regist, true, 'text', $db_opcao, " onchange='js_pesquisar30_regist(false);'")
      ?>
      <?
      db_input('z01_nome', 40, $Iz01_nome, true, 'text', 3);
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="Período de aquisição" align="right">
      <?
      db_ancora("<b>Período aquisição:</b>", "", 3);
      ?>
    </td>
    <td>
      <?
      db_inputdata('r30_perai', @$r30_perai_dia, @$r30_perai_mes, @$r30_perai_ano, true, 'text', 3);
      ?>
      &nbsp;&nbsp;<b>a</b>&nbsp;&nbsp;
      <?
      db_inputdata('r30_peraf', @$r30_peraf_dia, @$r30_peraf_mes, @$r30_peraf_ano, true, 'text', 3);
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="Período de gozo" align="right">
      <?
      db_ancora("<b>Período de gozo:</b>", "", 3);
      ?>
    </td>
    <td>
      <?
      db_inputdata('r30_per2i', @$r30_per2i_dia, @$r30_per2i_mes, @$r30_per2i_ano, true, 'text', 3);
      ?>
      &nbsp;&nbsp;<b>a</b>&nbsp;&nbsp;
      <?
      db_inputdata('r30_per2f', @$r30_per2f_dia, @$r30_per2f_mes, @$r30_per2f_ano, true, 'text', 3);
      ?>
    </td>
  </tr>
  <tr>
    <td id="mostra_mensagem" colspan="2" align="center"></td>
  </tr>
</table>
</center>
<input name="excluir" value="Excluir" type="submit" <?=($db_botao==false?"disabled":"")?> onblur="document.form1.r30_regist.focus();" onclick="return js_testacampos();">
</form>
<script>
function js_testacampos(){
  if(document.form1.r30_regist.value == ""){
    alert("Informe a matrícula do funcionário.");
    document.form1.r30_regist.focus();
    return false;
  }
  return true;
}
function js_pesquisar30_regist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cadferia','func_cadferiaalt.php?testarescisao=ra&funcao_js=parent.js_mostrapessoal1|r30_regist|z01_nome|r30_perai|r30_peraf|r30_proc1|r30_proc2|r30_proc1d|r30_proc2d|r30_per1i|r30_per2i|r30_per1f|r30_per2f|r30_tip1&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',true);
  }else{
    if(document.form1.r30_regist.value != ''){ 
      js_OpenJanelaIframe('top.corpo','db_iframe_cadferia','func_cadferiaalt.php?testarescisao=ra&pesquisa_chave='+document.form1.r30_regist.value+'&funcao_js=parent.js_mostrapessoal&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',false);
    }else{
      document.form1.z01_nome.value = '';
      document.form1.r30_perai_dia.value = '';
      document.form1.r30_perai_mes.value = '';
      document.form1.r30_perai_ano.value = '';

      document.form1.r30_peraf_dia.value = '';
      document.form1.r30_peraf_mes.value = '';
      document.form1.r30_peraf_ano.value = '';

      document.form1.r30_per2i_dia.value = '';
      document.form1.r30_per2i_mes.value = '';
      document.form1.r30_per2i_ano.value = '';

      document.form1.r30_per2f_dia.value = '';
      document.form1.r30_per2f_mes.value = '';
      document.form1.r30_per2f_ano.value = '';
    }
  }
}
function js_mostrapessoal(chave,chave2,chave3,chave4,chave5,chave6,chave7,chave8,chave9,chave10,chave11,chave12,erro){
  document.form1.z01_nome.value = chave;
  if(erro == true){
    document.form1.r30_regist.value = '';

    document.form1.r30_perai_dia.value = '';
    document.form1.r30_perai_mes.value = '';
    document.form1.r30_perai_ano.value = '';

    document.form1.r30_peraf_dia.value = '';
    document.form1.r30_peraf_mes.value = '';
    document.form1.r30_peraf_ano.value = '';
    document.form1.r30_regist.focus(); 
  }else{
    subpes = "<?=(db_anofolha()."/".db_mesfolha())?>";
    setlocationhref = false;
    setmensagemini  = "";
    setmensagemfim  = "";
    setmensagemchv  = false; 
    if(chave5 == subpes){
      setmensagemini = chave9;
      setmensagemfim = chave11;
      if(chave12.indexOf("08") != -1 && chave12.indexOf("10") != -1){
        setmensagemchv  = true;
      }
    }else if(chave4 >= subpes){
      setmensagemini = chave8;
      setmensagemfim = chave10;
      if(chave12.indexOf("08") != -1 && chave12.indexOf("10") != -1){
        setmensagemchv  = true;
      }
    }else if(chave6 == subpes || chave7 == subpes){
      alert("Não existe cadastro de férias, somente pagamento de diferenças.");
      setlocationhref = true;
    }else if(chave4 < subpes){
      alert("Não existe cadastro de férias para este funcionário.");
      setlocationhref = true;
    }
    document.form1.r30_perai_dia.value = chave2.substring(8,10);
    document.form1.r30_perai_mes.value = chave2.substring(5,7);
    document.form1.r30_perai_ano.value = chave2.substring(0,4);
    document.form1.r30_perai.value = chave2.substring(8,10)+'/'+chave2.substring(5,7)+'/'+chave2.substring(0,4);

    document.form1.r30_peraf_dia.value = chave3.substring(8,10);
    document.form1.r30_peraf_mes.value = chave3.substring(5,7);
    document.form1.r30_peraf_ano.value = chave3.substring(0,4);
    document.form1.r30_peraf.value = chave3.substring(8,10)+'/'+chave3.substring(5,7)+'/'+chave3.substring(0,4);

    if(setlocationhref == true){
      document.form1.r30_regist.value = "";
      js_pesquisar30_regist(false);
      document.form1.r30_regist.focus();
    }else if(setmensagemini != ""){
      document.form1.r30_per2i_dia.value = setmensagemini.substring(8,10);
      document.form1.r30_per2i_mes.value = setmensagemini.substring(5,7);
      document.form1.r30_per2i_ano.value = setmensagemini.substring(0,4);
      document.form1.r30_per2i.value = setmensagemini.substring(8,10)+'/'+setmensagemini.substring(5,7)+'/'+setmensagemini.substring(0,4);

      document.form1.r30_per2f_dia.value = setmensagemfim.substring(8,10);
      document.form1.r30_per2f_mes.value = setmensagemfim.substring(5,7);
      document.form1.r30_per2f_ano.value = setmensagemfim.substring(0,4);
      document.form1.r30_per2f.value = setmensagemfim.substring(8,10)+'/'+setmensagemfim.substring(5,7)+'/'+setmensagemfim.substring(0,4);
      if(setmensagemchv == true){
	document.getElementById('mostra_mensagem').innerHTML = "<b>Período em branco - Cadastro refere-se a abono.</b>";
      }
    }
  }
}
function js_mostrapessoal1(chave1,chave2,chave3,chave4,chave5,chave6,chave7,chave8,chave9,chave10,chave11,chave12,chave13){
  db_iframe_cadferia.hide();
  document.form1.r30_regist.value = chave1;
  document.form1.z01_nome.value   = chave2;
  document.form1.r30_perai_dia.value = chave3.substring(8,10);
  document.form1.r30_perai_mes.value = chave3.substring(5,7);
  document.form1.r30_perai_ano.value = chave3.substring(0,4);
  document.form1.r30_perai.value = chave3.substring(8,10)+'/'+chave3.substring(5,7)+'/'+chave3.substring(0,4);

  document.form1.r30_peraf_dia.value = chave4.substring(8,10);
  document.form1.r30_peraf_mes.value = chave4.substring(5,7);
  document.form1.r30_peraf_ano.value = chave4.substring(0,4);
  document.form1.r30_peraf.value = chave4.substring(8,10)+'/'+chave4.substring(5,7)+'/'+chave4.substring(0,4);

  subpes = "<?=(db_anofolha()."/".db_mesfolha())?>";
  setlocationhref = false;
  setmensagemini  = "";
  setmensagemfim  = "";
  setmensagemchv  = false; 
  if(chave6 == subpes){
    setmensagemini = chave10;
    setmensagemfim = chave12;
    if(chave13.indexOf("08") != -1 && chave13.indexOf("10") != -1){
      setmensagemchv  = true;
    }
  }else if(chave5 >= subpes){
    setmensagemini = chave9;
    setmensagemfim = chave11;
    if(chave13.indexOf("08") != -1 && chave13.indexOf("10") != -1){
      setmensagemchv  = true;
    }
  }else if(chave7 == subpes || chave8 == subpes){
    alert("Não existe cadastro de férias, somente pagamento de diferenças.");
    setlocationhref = true;
  }else if(chave5 < subpes){
    alert("Não existe cadastro de férias para este funcionário.");
    setlocationhref = true;
  }

  if(setlocationhref == true){
    document.form1.r30_regist.value = "";
    js_pesquisar30_regist(false);
    document.form1.r30_regist.focus();
  }else if(setmensagemini != ""){
    document.form1.r30_per2i_dia.value = setmensagemini.substring(8,10);
    document.form1.r30_per2i_mes.value = setmensagemini.substring(5,7);
    document.form1.r30_per2i_ano.value = setmensagemini.substring(0,4);

    document.form1.r30_per2f_dia.value = setmensagemfim.substring(8,10);
    document.form1.r30_per2f_mes.value = setmensagemfim.substring(5,7);
    document.form1.r30_per2f_ano.value = setmensagemfim.substring(0,4);
    if(setmensagemchv == true){
      document.getElementById('mostra_mensagem').innerHTML = "<b>Período em branco - Cadastro refere-se a abono.</b>";
    }
  }
}
</script>