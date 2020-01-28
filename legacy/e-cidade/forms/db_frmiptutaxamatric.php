<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$cliptutaxamatric->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("j01_numcgm");
$clrotulo->label("j07_descr");
if(isset($db_opcaoal)){

  $db_opcao=33;
  $db_botao=false;
}else if(isset($opcao) && $opcao=="alterar"){

  $db_botao=true;
  $db_opcao = 2;
}else if(isset($opcao) && $opcao=="excluir"){

  $db_opcao = 3;
  $db_botao=true;
}else{

  $db_opcao = 1;
  $db_botao=true;
  if(isset($novo) || isset($alterar) ||   isset($excluir) || (isset($incluir) && $sqlerro==false ) ){

   $j09_iptucadtaxa = "";
   $j09_valor = "";
   $j07_descr = "";
 }
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td>
      <?php
        db_input('j09_iptutaxamatric',10,$Ij09_iptutaxamatric,true,'hidden',3,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap >
       <b>Matricula:</b>
    </td>
    <td>
      <?php
        db_input('j09_matric',10,$Ij09_matric,true,'text',3)
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj09_iptucadtaxaexe?>">
       <?php
        db_ancora(@$Lj09_iptucadtaxaexe,"js_pesquisaj09_iptucadtaxaexe(true);",$db_opcao);
       ?>
    </td>
    <td>
      <?php
        db_input('j09_iptucadtaxaexe',10,$Ij09_iptucadtaxaexe,true,'text',$db_opcao," onchange='js_pesquisaj09_iptucadtaxaexe(false);'");
        db_input('j07_descr',40,$Ij07_descr,true,'text',3,'');
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj09_valor?>">
       <?=@$Lj09_valor?>
    </td>
    <td>
      <?php
        db_input('j09_valor',10,$Ij09_valor,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>

  </tr>
    <td colspan="2" align="center">
     <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> onclick="return js_enviar();" />
     <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
    </td>
  </tr>
  </table>
 <table>
  <tr>
    <td valign="top"  align="center">
    <?php
      $sql = "
      select j09_iptutaxamatric,
             j08_iptucadtaxaexe,
             j08_iptucadtaxa,
             j08_anousu,
             j08_tabrec,
             j09_iptucadtaxaexe,
             j09_matric,
             j21_matric,
             j21_anousu as db_ano_origem,
             j21_receit,
             j09_valor,
             j21_valor as db_valor_origem,
             j10_perccorre
        from iptutaxamatric
             inner join iptucalcpadrao        on j10_matric         = j09_matric
             inner join iptucadtaxaexe        on j08_iptucadtaxaexe = j09_iptucadtaxaexe
                                             and j08_anousu         = ".db_getsession('DB_anousu')."
             left  join iptucalcpadraoorigem  on j27_matric         = j09_matric
             left  join iptucalv              on j21_matric         = j09_matric
                                             and j21_anousu         = j27_anousu
                                             and j21_receit         = j08_tabrec
       where j09_matric = $j09_matric  ";

         $chavepri = array("j09_iptucadtaxaexe"=>@$j09_iptucadtaxaexe,"j09_iptutaxamatric"=>@$j09_iptutaxamatric,"j09_valor"=>@$j09_valor);
      	 $cliframe_alterar_excluir->chavepri      =$chavepri;
      	 $cliframe_alterar_excluir->sql           = $sql;
         $cliframe_alterar_excluir->alignlegenda  = "left";
      	 $cliframe_alterar_excluir->campos        = "j09_iptutaxamatric,j09_matric,j09_iptucadtaxaexe,j09_valor,db_valor_origem,db_ano_origem,j10_perccorre";
      	 $cliframe_alterar_excluir->legenda       ="ITENS LANÇADOS";
      	 $cliframe_alterar_excluir->iframe_height ="200";
      	 $cliframe_alterar_excluir->iframe_width  ="800";
      	 $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
    ?>
    </td>
   </tr>
 </table>
  </center>
</form>
<script type="text/javascript">

function js_enviar(){

  if( empty($F('j09_valor')) || $F('j09_valor') <= 0 ){

    alert('Campo Valor é de preenchimento obrigatório e não pode ser nulo.');
    return false;
  }

  return true;
}

function js_cancelar(){
  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}
function js_pesquisaj09_matric(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_iptutaxamatric','db_iframe_iptubase','func_iptubase.php?funcao_js=parent.js_mostraiptubase1|j01_matric|j01_numcgm','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.j09_matric.value != ''){
        js_OpenJanelaIframe('top.corpo.iframe_iptutaxamatric','db_iframe_iptubase','func_iptubase.php?pesquisa_chave='+document.form1.j09_matric.value+'&funcao_js=parent.js_mostraiptubase','Pesquisa',false);
     }else{
       document.form1.j01_numcgm.value = '';
     }
  }
}
function js_mostraiptubase(chave,erro){
  document.form1.j01_numcgm.value = chave;
  if(erro==true){
    document.form1.j09_matric.focus();
    document.form1.j09_matric.value = '';
  }
}
function js_mostraiptubase1(chave1,chave2){
  document.form1.j09_matric.value = chave1;
  document.form1.j01_numcgm.value = chave2;
  db_iframe_iptubase.hide();
}
function js_pesquisaj09_iptucadtaxaexe(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_iptutaxamatric','db_iframe_iptucadtaxaexe','func_iptucadtaxaexealt.php?funcao_js=parent.js_mostraiptucadtaxa1|j08_iptucadtaxaexe|j07_descr','Pesquisa',true,'0','1');
  }else{
     if(document.form1.j09_iptucadtaxa.value != ''){
        js_OpenJanelaIframe('top.corpo.iframe_iptutaxamatric','db_iframe_iptucadtaxaexe','func_iptucadtaxaexealt.php?pesquisa_chave='+document.form1.j09_iptucadtaxaexe.value+'&funcao_js=parent.js_mostraiptucadtaxa','Pesquisa',false);
     }else{
       document.form1.j07_descr.value = '';
     }
  }
}
function js_mostraiptucadtaxa(chave,erro){
  document.form1.j07_descr.value = chave;
  if(erro==true){
    document.form1.j09_iptucadtaxaexe.focus();
    document.form1.j09_iptucadtaxaexe.value = '';
  }
}
function js_mostraiptucadtaxa1(chave1,chave2){
  document.form1.j09_iptucadtaxaexe.value = chave1;
  document.form1.j07_descr.value = chave2;
  db_iframe_iptucadtaxaexe.hide();
}
</script>