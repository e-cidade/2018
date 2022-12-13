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

//MODULO: patrim
include("dbforms/db_classesgenericas.php");

$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clbensguardaitem->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("t21_codigo");
$clrotulo->label("t52_descr");
$clrotulo->label("nome");
if (isset($db_opcaoal)) {
  $db_opcao = 33;
  $db_botao = false;
} else if (isset($opcao) && $opcao == "alterar") {
  $db_botao = true;
  $db_opcao = 2;
  $opcao_imp = true;
} else if (isset($opcao) && $opcao == "excluir") {
  $db_opcao = 3;
  $db_botao = true;
  unset($opcao_imp);
} else {
  $db_opcao = 1;
  $db_botao = true;
  $res_item = $clbensguardaitem->sql_record($clbensguardaitem->sql_query(null, "*", null, "t22_bensguarda=$t22_bensguarda"));
  if ($clbensguardaitem->numrows > 0) {
    $opcao_imp = true;
  } else {
    unset($opcao_imp);
  }
  if (isset($novo) || isset($alterar) || isset($excluir) || (isset($incluir) && $sqlerro == false)) {
    $t22_bem = "";
    $t22_dtini = "";
    $t22_dtfim = "";
    $t22_obs = "";
    $t52_descr = "";
  }
}
$idus = db_getsession("DB_id_usuario");
$iddepart = db_getsession("DB_coddepto");
?>
<form name="form1" method="post" action="" class="container">
<fieldset>
  <legend><b>Itens do Termo de Guarda</b></legend>
  <table border="0">
    <tr>
      <td nowrap title="<?=@$Tt22_bensguarda ?>">
        <?
          db_input('t22_codigo', 10, $It22_codigo, true, 'hidden', 3, "");
          db_ancora(@$Lt22_bensguarda, "js_pesquisat22_bensguarda(true);", 3);
        ?>
      </td>
      <td> 
        <?
          db_input('t22_bensguarda', 10, $It22_bensguarda, true, 'text', 3, " onchange='js_pesquisat22_bensguarda(false);'");
          db_input('t21_codigo', 10, $It21_codigo, true, 'hidden', 3, '')
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Tt22_bem ?>">
        <?
          db_ancora(@$Lt22_bem, "js_pesquisat22_bem(true);", $db_opcao);
        ?>
      </td>
      <td nowrap > 
        <?
          db_input('t22_bem', 10, $It22_bem, true, 'text', $db_opcao, " onchange='js_pesquisat22_bem(false);'");
          db_input('t52_descr', 40, $It52_descr, true, 'text', 3, '')
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Tt22_dtini ?>">
         <?=@$Lt22_dtini ?>
      </td>
      <td> 
        <?
          db_inputdata('t22_dtini', @$t22_dtini_dia, @$t22_dtini_mes, @$t22_dtini_ano, true, 'text', $db_opcao, "")
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Tt22_dtfim ?>">
        <?=@$Lt22_dtfim ?>
      </td>
      <td> 
        <?
          db_inputdata('t22_dtfim', @$t22_dtfim_dia, @$t22_dtfim_mes, @$t22_dtfim_ano, true, 'text', $db_opcao, "")
        ?>
      </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tt22_obs ?>">
           <?=@$Lt22_obs ?>
        </td>
        <td> 
          <?
          db_textarea('t22_obs', 0, 50, $It22_obs, true, 'text', $db_opcao, "")
          ?>
        </td>
      </tr>
      <!--
      <tr>
        <td nowrap title="<?=@$Tt22_usuario ?>">
           <?
           db_ancora(@$Lt22_usuario, "js_pesquisat22_usuario(true);", $db_opcao);
           ?>
        </td>
        <td> 
          <?
            db_input('t22_usuario', 10, $It22_usuario, true, 'text', $db_opcao, " onchange='js_pesquisat22_usuario(false);'");
    
            db_input('nome', 40, $Inome, true, 'text', 3, '');
          ?>
        </td>
      </tr>
      -->
  </table>
  </fieldset>
  <br />
  <input name="<?=($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir")) ?>" type="submit" id="db_opcao" 
         value="<?=($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir")) ?>" 
         <?=($db_botao == false ? "disabled": "") ?>  />
  <input type="button" value="Imprimir" onClick="js_imprimir();" 
         <?=(isset($opcao_imp) && $opcao_imp != "" ? "" : "disabled") ?> />
  <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" 
         <?=($db_opcao == 1 || isset($db_opcaoal) ? "style='visibility:hidden;'" : "") ?> />
  <br />
  <table>
   <tr>
     <td valign="top"  align="center">  
     <?
     $chavepri = array("t22_codigo" => @$t22_codigo);
     $cliframe_alterar_excluir->chavepri = $chavepri;
     $cliframe_alterar_excluir->sql = $clbensguardaitem->sql_query(null, "*", null, "t22_bensguarda=$t22_bensguarda");
     $cliframe_alterar_excluir->sql_disabled = $clbensguardaitem->sql_query_dev(null, "*", null, "t22_bensguarda=$t22_bensguarda and t23_guardaitem is not null");
     $cliframe_alterar_excluir->campos = "t22_bensguarda,t22_bem,t52_descr,t22_dtini,t22_dtfim,t22_obs";
     $cliframe_alterar_excluir->legenda = "ITENS LANÇADOS";
     $cliframe_alterar_excluir->iframe_height = "160";
     $cliframe_alterar_excluir->iframe_width = "700";
     $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
     ?>
     </td>
    </tr>
  </table>
</form>

<script type="text/javascript">
/**
 * 
 */
function js_imprimir() {

  var sUrl  = 'pat2_reltermoguarda001.php?';
      sUrl += 'iTermo='+$F('t22_bensguarda');

  js_OpenJanelaIframe('', 'db_iframe_imprime_termo', sUrl, 'Imprime Termo', true);
}
function js_cancelar() {
  
  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}

function js_pesquisat22_bensguarda(mostra){

  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_bensguardaitem','db_iframe_bensguarda','func_bensguarda.php?funcao_js=parent.js_mostrabensguarda1|t21_codigo|t21_codigo','Pesquisa',true,'0');
  }else{
     if(document.form1.t22_bensguarda.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_bensguardaitem','db_iframe_bensguarda','func_bensguarda.php?pesquisa_chave='+document.form1.t22_bensguarda.value+'&funcao_js=parent.js_mostrabensguarda','Pesquisa',false);
     }else{
       document.form1.t21_codigo.value = ''; 
     }
  }
}

function js_mostrabensguarda(chave,erro){

  document.form1.t21_codigo.value = chave; 
  if(erro==true){ 
    document.form1.t22_bensguarda.focus(); 
    document.form1.t22_bensguarda.value = ''; 
  }
}

function js_mostrabensguarda1(chave1,chave2){

  document.form1.t22_bensguarda.value = chave1;
  document.form1.t21_codigo.value = chave2;
  db_iframe_bensguarda.hide();
}

function js_pesquisat22_bem(mostra){

  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_bensguardaitem','db_iframe_bens','func_bensconfirmacao.php?chave_id_usuario=<?=$idus ?>&chave_t93_depart=<?=$iddepart ?>&funcao_js=parent.js_mostrabens1|t52_bem|t52_descr','Pesquisa',true,'0');
  }else{
     if(document.form1.t22_bem.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_bensguardaitem','db_iframe_bens','func_bensconfirmacao.php?pesquisa_chave='+document.form1.t22_bem.value+'&funcao_js=parent.js_mostrabens&chave_id_usuario=<?=$idus ?>&chave_t93_depart=<?=$iddepart ?>','Pesquisa',false);
     }else{
       document.form1.t52_descr.value = ''; 
     }
  }
}

function js_mostrabens(iCodigoBem, sDescricaoBem, sPlacaBem, lErro){

  document.form1.t52_descr.value = sDescricaoBem;
  if (lErro == true) {
    document.form1.t22_bem.focus(); 
    document.form1.t22_bem.value = ''; 
  }
}

function js_mostrabens1(iCodigoBem, sDescricaoBem, sPlacaBem, lErro) {

  document.form1.t22_bem.value   = iCodigoBem;
  document.form1.t52_descr.value = sDescricaoBem;
  db_iframe_bens.hide();
}

function js_pesquisat22_usuario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_bensguardaitem','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true,'0');
  }else{
     if(document.form1.t22_usuario.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_bensguardaitem','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.t22_usuario.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
     }else{
       document.form1.nome.value = ''; 
     }
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.t22_usuario.focus(); 
    document.form1.t22_usuario.value = ''; 
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.t22_usuario.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}
</script>