<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBseller Servicos de Informatica
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

include("dbforms/db_classesgenericas.php");
$cliframe_seleciona = new cl_iframe_seleciona;
$cltabativbaixa->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("q07_inscr");
$clrotulo->label("z01_nome");
?>
<script>
function js_verifica(){
  inscr=new Number(document.form1.q07_inscr.value);
  if(inscr=="" || inscr=='0'|| isNaN(inscr)==true){
     alert('Verifique a inscrição');
     return false;
  }
  if(inscr!=document.form1.inscricao.value){
     return false;
  }
  obj=atividades.document.getElementsByTagName("INPUT");
  var marcado=false;
  for(i=0; i<obj.length; i++){
     if(obj[i].type=='checkbox'){
       if(obj[i].checked==true){
          id=obj[i].id.substr(6);
          marcado=true;
       }
     }
  }
  if(!marcado){
    alert('Selecione uma atividade!');
    return false;
  }
  if(confirm("Recalcular as atividades reativadas?")){
    document.form1.calculo.value="ok";
  }else{
    document.form1.calculo.value="no";
  }
  return  js_gera_chaves();
}
</script>
<form name="form1" method="post" action="">
<center>
<fieldset style="margin-top: 20px;">
<legend>Excluir Baixa Inscrição de Alvará</legend>

<table border="0">
  <tr>
    <td align="center">
    <?php
      db_input('calculo',5,0,true,'hidden',1);
      $q02_dtbaix_dia="";
      $q02_dtbaix_mes="";
      $q02_dtbaix_ano="";
      db_inputdata('q07_datafi',@$q07_datafi_dia,@$q07_datafi_mes,@$q07_datafi_ano,true,'hidden',3);
      db_inputdata('q07_databx',@$q07_databx_dia,@$q07_databx_mes,@$q07_databx_ano,true,'hidden',3);
      db_inputdata('q02_dtbaix',@$q02_dtbaix_dia,@$q02_dtbaix_mes,@$q02_dtbaix_ano,true,'hidden',3);
    ?>
    <table border="0">
      <tr>
        <td title="<?=$Tq07_inscr?>" >
        <?
         db_ancora($Lq07_inscr,' js_inscr(true); ',1);
        ?>
        </td>
        <td title="<?=$Tq07_inscr?>" colspan="4">
        <?
         db_input('q07_inscr',5,$Iq07_inscr,true,'text',1,"onchange='js_inscr(false)'");
         isset($q07_inscr)?$inscricao=$q07_inscr:"";
         db_input('inscricao',5,$Iq07_inscr,true,'hidden',1);
         db_input('z01_nome',50,0,true,'text',3);
        ?>
        </td>
      </tr>
      <tr>
        <td colspan="3" align="center">

        </td>
      </tr>
    </table>
</td>
</tr>
<tr>
<td>
  <tr>
    <td align="center" colspan="2">
    <?php
          $cliframe_seleciona->campos  = "q07_inscr,q07_seq,q88_inscr,q03_descr,q07_datain,q07_datafi,q07_databx,q07_perman,q07_quant,q11_tipcalc, q81_descr";
          $cliframe_seleciona->legenda="ATIVIDADES BAIXADAS";
          if(isset($q07_inscr) && $q07_inscr!=""){
             $cliframe_seleciona->sql=$cltabativ->sql_query_atividade_inscr($q07_inscr,"*","q07_seq","q07_inscr = $q07_inscr and q07_databx is  not null");
          }
          $cliframe_seleciona->textocabec ="darkblue";
          $cliframe_seleciona->textocorpo ="black";
          $cliframe_seleciona->fundocabec ="#aacccc";
          $cliframe_seleciona->fundocorpo ="#ccddcc";
          $cliframe_seleciona->iframe_height ="250";
          $cliframe_seleciona->iframe_width ="700";
          $cliframe_seleciona->iframe_nome ="atividades";
          $cliframe_seleciona->chaves ="q07_inscr,q07_seq";
          $cliframe_seleciona->iframe_seleciona($db_opcao);
    ?>
    </td>
  </tr>
  </table>
  </fieldset>
  <input name="cancelar" type="submit" style="margin-top: 10px;" onclick="return js_verifica();" id="db_opcao" value="Cancelar baixa" <?=($db_botao==false?"disabled":"")?> >
  </center>
</form>
<script>
function js_inscr(mostra){
  var inscr=document.form1.q07_inscr.value;
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_inscr','func_issbase.php?funcao_js=parent.js_mostrainscr|q02_inscr|z01_nome','Pesquisa',true);
  }else{
    if(inscr!=""){
      js_OpenJanelaIframe('top.corpo','db_iframe_inscr','func_issbase.php?pesquisa_chave='+inscr+'&funcao_js=parent.js_mostrainscr1','Pesquisa',false);
    }else{
      document.form1.z01_nome.value="";
      document.form1.submit();
    }
  }
}
function js_mostrainscr(chave1,chave2){
  document.form1.q07_inscr.value = chave1;
  document.form1.z01_nome.value = chave2;
  atividades.location.href="iss1_tabativbaixaiframe.php?q07_inscr="+chave1+"&z01_nome="+chave2;
  document.form1.submit();
  db_iframe_inscr.hide();
}
function js_mostrainscr1(chave,erro){
  document.form1.z01_nome.value = chave;
  if(erro==true){
    document.form1.q07_inscr.focus();
    document.form1.q07_inscr.value = '';
  }else{
    document.form1.submit();
  }
}
</script>