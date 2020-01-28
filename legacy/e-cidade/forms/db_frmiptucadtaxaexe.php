<?php
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

require_once(modification("dbforms/db_classesgenericas.php"));

$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$cliptucadtaxaexe->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("j07_descr");
$clrotulo->label("k02_descr");
$clrotulo->label("j17_descr");
$clrotulo->label("nomefuncao");
$clrotulo->label("j17_descr");

if (isset($db_opcaoal)) {
  $db_opcao = 33;
  $db_botao = false;

} else if (isset($opcao) && $opcao == "alterar") {
  $db_opcao = 2;
  $db_botao = true;

} else if (isset($opcao) && $opcao == "excluir") {
  $db_opcao = 3;
  $db_botao = true;

} else {  
  $db_opcao = 1;
  $db_botao = true;
  
  if (isset($novo) || isset($alterar) || isset($excluir) || (isset($incluir) && $sqlerro == false)) {
    $j08_tabrec        = "";
    $j08_valor         = "";
    $j08_aliq          = "";
    $j08_anousu        = "";
    $j08_iptucalh      = "";
    $j08_db_sysfuncoes = "";
    $j08_histisen      = "";
    $j17_descr         = "";
    $j17_descr2        = "";
    $k02_descr         = "";
    $nomefuncao        = "";
  }
} 
?>
<div class="container">
  <form name="form1" method="post" action="">
    <fieldset>
      <legend>Configuração da Taxa</legend>

      <table>
        <tr>
          <td> 
          </td>
          <td> 
            <?php
              db_input('j08_iptucadtaxaexe',10,$Ij08_iptucadtaxaexe,true,'hidden',3,"");
            ?>
          </td>
        </tr>
        
        <tr>
          <td nowrap title="<?=$Tj08_iptucadtaxa?>">
            <label for="j08_iptucadtaxa">
              <?php
                db_ancora($Lj08_iptucadtaxa,"js_pesquisaj08_iptucadtaxa(true);",3);
              ?>
            </label>
          </td>
          <td> 
            <?php
              db_input('j08_iptucadtaxa',10,$Ij08_iptucadtaxa,true,'text',3," onchange='js_pesquisaj08_iptucadtaxa(false);'");
              db_input('j07_descr',40,$Ij07_descr,true,'text',3,'');
            ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?=$Tj08_tabrec?>">
            <label for="j08_tabrec">
              <?php
                db_ancora($Lj08_tabrec,"js_pesquisaj08_tabrec(true);",$db_opcao);
              ?>
            </label>
          </td>
          <td> 
            <?php
              db_input('j08_tabrec',10,$Ij08_tabrec,true,'text',$db_opcao," onchange='js_pesquisaj08_tabrec(false);'");
              db_input('k02_descr',40,$Ik02_descr,true,'text',3,'');
            ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?=$Tj08_iptucalh?>">
            <label for="j08_iptucalh">
              <?php
               db_ancora($Lj08_iptucalh,"js_pesquisaj08_iptucalh(true);",$db_opcao);
              ?>
            </label>
          </td>
          <td> 
            <?php
              db_input('j08_iptucalh',10,$Ij08_iptucalh,true,'text',$db_opcao," onchange='js_pesquisaj08_iptucalh(false);'");
              db_input('j17_descr',40,$Ij17_descr,true,'text',3,'');
            ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?=$Tj08_db_sysfuncoes?>">
            <label for="j08_db_sysfuncoes">
              <?php
                db_ancora($Lj08_db_sysfuncoes,"js_pesquisaj08_db_sysfuncoes(true);",$db_opcao);
              ?>
            </label>
          </td>
          <td> 
            <?php
              db_input('j08_db_sysfuncoes',10,$Ij08_db_sysfuncoes,true,'text',$db_opcao," onchange='js_pesquisaj08_db_sysfuncoes(false);'");
              db_input('nomefuncao',40,$Inomefuncao,true,'text',3,'');
            ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?=$Tj08_histisen?>">
            <label for="j08_histisen">
              <?php
               db_ancora("<b>Histórico para Isenção:</b>","js_pesquisaj08_histiseni(true);",$db_opcao);
              ?>
            </label>
          </td>
          <td> 
            <?php
              db_input('j08_histisen',10,$Ij08_histisen,true,'text',$db_opcao," onchange='js_pesquisaj08_histiseni(false);'");
              db_input('j17_descr2',40,$Ij17_descr,true,'text',3,'');
            ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?=$Tj08_valor?>">
            <label for="j08_valor"><?=$Lj08_valor?></label>  
          </td>
          <td> 
            <?php
              db_input('j08_valor',10,$Ij08_valor,true,'text',$db_opcao,"");
            ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?=$Tj08_aliq?>">
            <label for="j08_aliq"><?=$Lj08_aliq?></label>
          </td>
          <td> 
            <?php
              db_input('j08_aliq',10,$Ij08_aliq,true,'text',$db_opcao,"");
            ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?=$Tj08_anousu?>">
            <label for="j08_anousu"><?=$Lj08_anousu?></label>
          </td>
          <td> 
            <?php
              $j08_anousu = db_getsession('DB_anousu');
              db_input('j08_anousu',10,$Ij08_anousu,true,'text',3,"");
            ?>
          </td>
        </tr>
      </table>
    </fieldset>

    <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>  >
    <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
 
  <table>
    <tr>
      <td valign="top"  align="center">
        <?php
          $chavepri= array("j08_iptucadtaxa"=>@$j08_iptucadtaxa,"j08_iptucadtaxaexe"=>@$j08_iptucadtaxaexe,);
          $cliframe_alterar_excluir->chavepri=$chavepri;
          $cliframe_alterar_excluir->sql     = $cliptucadtaxaexe->sql_query_file(null,"*",null," j08_iptucadtaxa = $j08_iptucadtaxa and j08_anousu = $j08_anousu ");
          $cliframe_alterar_excluir->campos  ="j08_iptucadtaxaexe,j08_iptucadtaxa,j08_tabrec,j08_valor,j08_aliq,j08_anousu,j08_iptucalh,j08_db_sysfuncoes,j08_histisen";
          $cliframe_alterar_excluir->legenda="ITENS LANÇADOS";
          $cliframe_alterar_excluir->iframe_height ="160";
          $cliframe_alterar_excluir->iframe_width ="700";
          $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
        ?>
      </td>
    </tr>
  </table>
  </form>
</div>
<script>
function js_cancelar() {

  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}

function js_pesquisaj08_iptucadtaxa(mostra) {
  
  if (mostra == true) {
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_iptucadtaxaexe','db_iframe_iptucadtaxa','func_iptucadtaxa.php?funcao_js=parent.js_mostraiptucadtaxa1|j07_iptucadtaxa|j07_descr','Pesquisa',true,'0');
  } else {

    if (document.form1.j08_iptucadtaxa.value != '') { 
      js_OpenJanelaIframe('CurrentWindow.corpo.iframe_iptucadtaxaexe','db_iframe_iptucadtaxa','func_iptucadtaxa.php?pesquisa_chave='+document.form1.j08_iptucadtaxa.value+'&funcao_js=parent.js_mostraiptucadtaxa','Pesquisa',false);
    } else {
      document.form1.j07_descr.value = ''; 
    }
  }
}

function js_mostraiptucadtaxa(chave,erro) {
  
  document.form1.j07_descr.value = chave; 
  
  if (erro == true) { 
    document.form1.j08_iptucadtaxa.focus(); 
    document.form1.j08_iptucadtaxa.value = ''; 
  }
}

function js_mostraiptucadtaxa1(chave1,chave2) {
  
  document.form1.j08_iptucadtaxa.value = chave1;
  document.form1.j07_descr.value = chave2;
  db_iframe_iptucadtaxa.hide();
}

function js_pesquisaj08_tabrec(mostra) {
  
  if (mostra == true) {
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_iptucadtaxaexe','db_iframe_tabrec','func_tabrec.php?funcao_js=parent.js_mostratabrec1|k02_codigo|k02_descr','Pesquisa',true,'0');
  } else {

    if(document.form1.j08_tabrec.value != '') { 
      js_OpenJanelaIframe('CurrentWindow.corpo.iframe_iptucadtaxaexe','db_iframe_tabrec','func_tabrec.php?pesquisa_chave='+document.form1.j08_tabrec.value+'&funcao_js=parent.js_mostratabrec','Pesquisa',false);
    } else {
      document.form1.k02_descr.value = ''; 
    }
  }
}

function js_mostratabrec(chave,erro) {
  
  document.form1.k02_descr.value = chave; 
  
  if (erro == true) { 
    document.form1.j08_tabrec.focus(); 
    document.form1.j08_tabrec.value = ''; 
  }
}

function js_mostratabrec1(chave1,chave2) {
  
  document.form1.j08_tabrec.value = chave1;
  document.form1.k02_descr.value = chave2;
  db_iframe_tabrec.hide();
}

function js_pesquisaj08_iptucalh(mostra) {
  
  if (mostra == true) {
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_iptucadtaxaexe','db_iframe_iptucalh','func_iptucalh.php?funcao_js=parent.js_mostraiptucalh1|j17_codhis|j17_descr','Pesquisa',true,'0');
  } else {

    if (document.form1.j08_iptucalh.value != '') { 
       js_OpenJanelaIframe('CurrentWindow.corpo.iframe_iptucadtaxaexe','db_iframe_iptucalh','func_iptucalh.php?pesquisa_chave='+document.form1.j08_iptucalh.value+'&funcao_js=parent.js_mostraiptucalh','Pesquisa',false);
    } else {
      document.form1.j17_descr.value = ''; 
    }
  }
}

function js_mostraiptucalh(chave,erro) {
  
  document.form1.j17_descr.value = chave; 
  
  if ( erro == true) { 
    document.form1.j08_iptucalh.focus(); 
    document.form1.j08_iptucalh.value = ''; 
  }
}

function js_mostraiptucalh1(chave1,chave2) {

  document.form1.j08_iptucalh.value = chave1;
  document.form1.j17_descr.value = chave2;
  db_iframe_iptucalh.hide();
}

function js_pesquisaj08_db_sysfuncoes(mostra) {
  
  if (mostra == true) {
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_iptucadtaxaexe','db_iframe_db_sysfuncoes','func_db_sysfuncoes.php?funcao_js=parent.js_mostradb_sysfuncoes1|codfuncao|nomefuncao','Pesquisa',true,'0');
  } else {
    if(document.form1.j08_db_sysfuncoes.value != '') { 
      js_OpenJanelaIframe('CurrentWindow.corpo.iframe_iptucadtaxaexe','db_iframe_db_sysfuncoes','func_db_sysfuncoes.php?pesquisa_chave='+document.form1.j08_db_sysfuncoes.value+'&funcao_js=parent.js_mostradb_sysfuncoes','Pesquisa',false);
    } else {
      document.form1.nomefuncao.value = ''; 
    }
  }
}

function js_mostradb_sysfuncoes(chave,erro) {

  document.form1.nomefuncao.value = chave; 
  
  if (erro == true) { 
    document.form1.j08_db_sysfuncoes.focus(); 
    document.form1.j08_db_sysfuncoes.value = ''; 
  }
}

function js_mostradb_sysfuncoes1(chave1,chave2) {

  document.form1.j08_db_sysfuncoes.value = chave1;
  document.form1.nomefuncao.value = chave2;
  db_iframe_db_sysfuncoes.hide();
}
  
function js_pesquisaj08_histiseni(mostra){

  if (mostra == true) {
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_iptucadtaxaexe','db_iframe_iptucalhi','func_iptucalh.php?funcao_js=parent.js_mostraiptucalh1i|j17_codhis|j17_descr','Pesquisa',true,'0');
  } else {
     if (document.form1.j08_histisen.value != '') { 
       js_OpenJanelaIframe('CurrentWindow.corpo.iframe_iptucadtaxaexe','db_iframe_iptucalhi','func_iptucalh.php?pesquisa_chave='+document.form1.j08_histisen.value+'&funcao_js=parent.js_mostraiptucalhi','Pesquisa',false);
    } else {
      document.form1.j17_descr2.value = ''; 
    }
  }
}

function js_mostraiptucalhi(chave,erro) {
  
  document.form1.j17_descr2.value = chave; 
  
  if (erro == true) { 
    document.form1.j08_histisen.focus(); 
    document.form1.j08_histisen.value = ''; 
  }
}

function js_mostraiptucalh1i(chave1,chave2) {
  
  document.form1.j08_histisen.value = chave1;
  document.form1.j17_descr2.value = chave2;
  db_iframe_iptucalhi.hide();
}
</script>