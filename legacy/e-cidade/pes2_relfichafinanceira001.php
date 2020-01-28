<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("dbforms/db_classesgenericas.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php")) ;

$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt25');
$clrotulo->label('DBtxt27');
$clrotulo->label('DBtxt28');
$clrotulo->label('rh27_rubric');
$clrotulo->label('rh01_regist');
$clrotulo->label('r44_selec');
$clrotulo->label('r44_descr');

db_postmemory($_POST);

if(!isset($anof)){
  $anof = db_anofolha();
  $mesf = db_mesfolha();
}
?>
<html>
<head>
  <title>DBSeller Informática Ltda</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?php
    db_app::load('scripts.js, arrays.js, strings.js, estilos.css, prototype.js');
  ?>
</head>

<body bgcolor="#CCCCCC" onLoad="document.form1.anoi.focus();">
<form name="form1" method="post" class="container">

<fieldset>
  <legend>
    <strong>Ficha Financeira</strong>
  </legend>

  <table class="form-container" >
    <?

      if(isset($tfil) && ($tfil == "n" || $tfil == "i")){
        if($filt=="m"){
          $matriculas_selecionadas_text = "";
        }else{
          $rubricas_selecionadas_text = "";
        }
      }
      db_input('rubricas_selecionadas_text',20,0,true,'hidden',3);

      db_input('matriculas_selecionadas_text',20,0,true,'hidden',3);

    ?>

    <tr>
      <td title="Digite o Ano / Mês início" >
        <strong>Ano / Mês início:</strong>
      </td>
      <td>
        <?
          db_input('DBtxt23',4,$IDBtxt23,true,'text',2, "class='field-size1'",'anoi');
        ?>
        /
        <?
          db_input('DBtxt25',2,$IDBtxt25,true,'text',2, "class='field-size1'",'mesi');
        ?>
      </td>
    </tr>

    <tr>
      <td title="Digite o Ano / Mês fim" >
        <strong>Ano / Mês fim:</strong>
      </td>
      <td>
        <?
        db_input('DBtxt23',4,$IDBtxt23,true,'text',2, "class='field-size1'",'anof')
        ?>
        /
        <?
        db_input('DBtxt25',2,$IDBtxt25,true,'text',2, "class='field-size1'",'mesf')
        ?>
      </td>
    </tr>

    <tr>
      <td title="Filtro para listagem dos dados">
        <?php
          db_ancora('Seleção:', 'js_pesquisaSelecao(true)', 1);
        ?>
      </td>
      <td>
        <?php
          db_input('r44_selec', 10, $Ir44_selec, true, 'text', 1," onchange='js_pesquisaSelecao(false)' class='field-size2' ");
          db_input('r44_descr', 40, $Ir44_descr, true, 'text', 3,"class='field-size7'");
        ?>
      </td>
    </tr>

    <tr>
      <td>
        <strong>Ordem:</strong>
      </td>
      <td>
        <?
        if(!isset($orde)){
          $orde = "a";
        }
        ?>
        <select name="orde">
          <option value='a' <?=isset($orde) && $orde=="a"?"selected":""?>> Alfabético
          <option value='n' <?=isset($orde) && $orde=="n"?"selected":""?>> Numérico
        </select>
      </td>
    </tr>

    <tr>
      <td>
        <strong>Filtro:</strong>
      </td>
      <td>
        <?
        if(!isset($filt)){
          $filt = "m";
        }
        ?>
        <select id="filt" name="filt" onchange="js_verifica_selecionadas()" >
          <option value = 'm' <?=$filt=="m"?"selected":""?>>Matrícula
          <option value = 'r' <?=$filt=="r"?"selected":""?>>Rubrica
        </select>
      </td>
    </tr>

    <?
      if(!isset($filt)){
        $filt = "m";
      }
      if(isset($filt)){
        if(!isset($tfil)){
          $tfil = "s";
        }
      ?>
        <tr>
          <td>
            <strong>Tipo de filtro:</strong>
          </td>
          <td>
            <select name="tfil" onchange="js_verifica_selecionadas();" >
              <option value = 'n' <?=$tfil=="n"?"selected":""?>>Nenhum</option>
              <option value = 's' <?=$tfil=="s"?"selected":""?>>Selecionados</option>
              <option value = 'i' <?=$tfil=="i"?"selected":""?>>Intervalo</option>
            </select>
          </td>
        </tr>

      <tr id="quebrapg" style='display:<?php isset($display) ? $display : '';?>'>
        <td>
          <strong>Quebrar por Servidor:</strong>
        </td>
        <td>
          <select name="quebrapg" onchange="" >
            <option value="s">Sim</option>
            <option value="n">Não</option>
          </select>
        </td>
      </tr>

      <tr>
        <td>
          <strong>Dados Cadastrais:</strong>
        </td>
        <td>
          <?
            $xcad = array("a"=>"Atual","p"=>"Período Inicial");
            db_select('mes_dados',$xcad,true,4,"");
          ?>
        </td>
      </tr>

    <?
    }
    ?>
    </table>
    <table>
    <tr>
      <?
        if(isset($filt) && $filt=="r" && isset($tfil) && $tfil=="s"){

          echo '<td align="right" colspan = "2">';
          $aux                                  = new cl_arquivo_auxiliar;
          $aux->obrigarselecao                  = false;
          $aux->cabecalho                       = "<strong>RUBRICAS SELECIONADAS</strong>";
          $aux->codigo                          = "rh27_rubric";
          $aux->descr                           = "rh27_descr";
          $aux->nomeobjeto                      = 'rubricas_selecionadas';
          $aux->funcao_js                       = 'js_mostra';
          $aux->funcao_js_hide                  = 'js_mostra1';
          $aux->func_arquivo                    = "func_rhrubricas.php";
          $aux->nomeiframe                      = "db_iframe_rhrubricas";
          $aux->executa_script_apos_incluir     = "document.form1.rh27_rubric.focus();";
          $aux->mostrar_botao_lancar            = false;
          $aux->executa_script_lost_focus_campo = "js_insSelectrubricas_selecionadas()";
          $aux->completar_com_zeros_codigo      = true;
          $aux->executa_script_change_focus     = "document.form1.rh27_rubric.focus();";
          $aux->passar_query_string_para_func   = "&instit=".db_getsession("DB_instit");
          $aux->localjan                        = "";
          $aux->db_opcao                        = 2;
          $aux->tipo                            = 2;
          $aux->top                             = 20;
          $aux->linhas                          = 10;
          $aux->vwidth                          = "460";
          $aux->funcao_gera_formulario();
          echo "</td>";

        }else if(isset($filt) && $filt=="r" && isset($tfil) && $tfil=="i"){

          echo '<td align="right"><strong>Intervalo de </strong></td>';
          echo '<td align="left">';
          db_input('rh27_rubric',4,$Irh27_rubric,true,'text',2,'','rh27_rubric1');
          echo ' <strong>à</strong> ';
          db_input('rh27_rubric',4,$Irh27_rubric,true,'text',2,'','rh27_rubric2');
          echo "</td>";

        }else if(isset($filt) && $filt=="m" && isset($tfil) && $tfil=="s"){

          echo '<td align="right" colspan = "2">';
          $aux                                  = new cl_arquivo_auxiliar;
          $aux->cabecalho                       = "<strong>MATRÍCULAS SELECIONADAS</strong>";
          $aux->obrigarselecao                  = false;
          $aux->codigo                          = "rh01_regist";
          $aux->descr                           = "z01_nome";
          $aux->nomeobjeto                      = 'matriculas_selecionadas';
          $aux->funcao_js                       = 'js_mostra';
          $aux->funcao_js_hide                  = 'js_mostra1';
          $aux->func_arquivo                    = "func_rhpessoal.php";
          $aux->nomeiframe                      = "db_iframe_rhpessoal";
          $aux->executa_script_apos_incluir     = "document.form1.rh01_regist.focus();";
          $aux->mostrar_botao_lancar            = false;
          $aux->executa_script_lost_focus_campo = "js_insSelectmatriculas_selecionadas()";
          $aux->executa_script_change_focus     = "document.form1.rh01_regist.focus();";
          $aux->passar_query_string_para_func   = "&instit=".db_getsession("DB_instit");
          $aux->localjan                        = "";
          $aux->db_opcao                        = 2;
          $aux->tipo                            = 2;
          $aux->top                             = 20;
          $aux->linhas                          = 10;
          $aux->vwidth                          = "460";
          $aux->funcao_gera_formulario();
          echo "</td>";
        }else if(isset($filt) && $filt=="m" && isset($tfil) && $tfil=="i"){

          echo '<td align="right"><strong>Intervalo de </strong></td>';
          echo '<td align="left">';

          db_input('rh01_regist',6,$Irh01_regist,true,'text',2,'','rh01_regist1');

          echo ' <strong>ao</strong> ';

          db_input('rh01_regist',6,$Irh01_regist,true,'text',2,'','rh01_regist2');

          echo "</td>";
        }

        if(isset($matriculas_selecionadas_text) && $matriculas_selecionadas_text != ''){
          echo"<script>js_insere_matri()</script>";
        }

        if(isset($rubricas_selecionadas_text) && $rubricas_selecionadas_text != ''){
          echo"<script>js_insere_rubri()</script>";
        }

      ?>
    </tr>
  </table>

</fieldset>

<div style="margin: 10px auto; text-align: center;">
  <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();" >
</div>

</form>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>

<script>

function js_pesquisaSelecao(lMostra) {

  iCodigoInstituicao = '<?=db_getsession('DB_instit')?>';

  if(lMostra){

    sUrl = 'func_selecao.php?funcao_js=parent.js_mostraSelecao|r44_selec|r44_descr&instit='+iCodigoInstituicao;
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_selecao', sUrl, 'Pesquisa', lMostra);
  }else{

    sUrl = 'func_selecao.php?pesquisa_chave='+document.form1.r44_selec.value+'&funcao_js=parent.js_mostraSelecaoHidden&instit='+iCodigoInstituicao;
    js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_selecao', sUrl, 'Pesquisa', lMostra);
  }
}

function js_mostraSelecao(iCodigoSelecao, sDescricao) {

  document.form1.r44_selec.value = iCodigoSelecao;
  document.form1.r44_descr.value = sDescricao;
  db_iframe_selecao.hide();
}

function js_mostraSelecaoHidden(sDescricao, lErro) {

  if (lErro) {
    document.form1.r44_selec.value = '';
  }
  document.form1.r44_descr.value = sDescricao;
}

function js_insere_matri() {

    var valor = document.getElementById('matriculas_selecionadas_text').value.trim();
    if (valor == '') {

       if (st) {
          clearTimeout(st);
       }
      return false;
    }
    var array = valor.split(",");
    for (var i=0; i< array.length; i++) {

      document.getElementById('rh01_regist').value = array[i];
      js_BuscaDadosArquivomatriculas_selecionadas(false);
      document.getElementById('matriculas_selecionadas_text').value = (array.slice(i+1, array.length).implode(',')).trim();
      var st = setTimeout(js_insere_matri, 500);
      break;
    }
}

function js_insere_rubri() {

    var valor = document.getElementById('rubricas_selecionadas_text').value.trim();
    if (valor == '') {

       if (st) {
          clearTimeout(st);
       }
      return false;
    }
    var array = valor.split(",");
    for (var i=0; i< array.length; i++) {

      document.getElementById('rh27_rubric').value = array[i];
      js_BuscaDadosArquivorubricas_selecionadas(false);
      document.getElementById('rubricas_selecionadas_text').value = (array.slice(i+1, array.length).implode(',')).trim();
      var st = setTimeout(js_insere_rubri, 500);
     break;
    }
}

function js_emite(){

  if(document.form1.anoi.value != "" && document.form1.mesi.value != ""){
    erro = 0;
    msgm = "";

    if(document.form1.anof.value == "" || document.form1.mesf.value == ""){
      if(document.form1.anof.value == ""){
       document.form1.anof.value = "<?=$anof?>";
        anof = "<?=$anof?>";
      }
      if(document.form1.mesf.value == ""){
        document.form1.mesf.value = "<?=$mesf?>";
        mesf = "<?=$mesf?>";
      }
    }

    tanoi = new Number(document.form1.anoi.value);
    tmesi = new Number(document.form1.mesi.value);
    tanof = new Number(document.form1.anof.value);
    tmesf = new Number(document.form1.mesf.value);

    if ((tanof+(tmesf/100)) < (tanoi+(tmesi/100))) {

      erro = 1;
      msgm = "Período de processamento inválido.";
      foco = "document.form1.anoi.select()";
    } else if(tmesi < 1 || tmesi > 12) {

      erro = 1;
      msgm = "Mês início inválido.";
      foco = "document.form1.mesi.select()";
    } else if(tmesf < 1 || tmesf > 12) {

      erro = 1;
      msgm = "Mês final inválido.";
      foco = "document.form1.mesf.select()";
    } else {

      anof = document.form1.anof.value;
      mesf = document.form1.mesf.value;
    }

    if(document.form1.rubricas_selecionadas){
      document.form1.rubricas_selecionadas_text.value = js_campo_recebe_valores();

    }else if(document.form1.matriculas_selecionadas){
      document.form1.matriculas_selecionadas_text.value = js_campo_recebe_valores();
    }

    if (erro == 0) {

      if (document.form1.matriculas_selecionadas_text.value == "" && $F('r44_selec') == "" ) {

        if(!confirm("Nenhuma matrícula informada, isto poderá deixar esta rotina muito lenta. \nDeseja continuar geração de relatório?")){
          erro = 1;
        }
      }

      if (erro == 0) {

    	  var frm = document.form1;
    	  
    	  var sQuery = "";
    	  sQuery  = "r44_selec="+$F('r44_selec');
    	  sQuery += "&rubricas_selecionadas_text="+$F("rubricas_selecionadas_text");
    	  sQuery += "&matriculas_selecionadas_text="+$F("matriculas_selecionadas_text");
    	  if ($('rh27_rubric1')) {
    		  sQuery += "&rh27_rubric1="+$F('rh27_rubric1');  
    	  }	   
    	  if ($('rh27_rubric2')) {
    		  sQuery += "&rh27_rubric2="+$F('rh27_rubric2');  
    	  }
    	  if ($('rh01_regist1')) {
    		  sQuery += "&rh01_regist1="+$F('rh01_regist1');  
    	  }	   
    	  if ($('rh01_regist2')) {
    		  sQuery += "&rh01_regist2="+$F('rh01_regist2');  
    	  }
    	  
    	  sQuery += "&anoi="+$F("anoi");
    		sQuery += "&mesi="+$F("mesi");
    		sQuery += "&anof="+$F("anof");
    		sQuery += "&mesf="+$F("mesf");
    		sQuery += "&r44_selec="+$F("r44_selec");
    		sQuery += "&orde="+frm.orde.value;
    		sQuery += "&filt="+frm.filt.value;
    		sQuery += "&tfil="+frm.tfil.value;
    		sQuery += "&quebrapg="+frm.quebrapg.value;
    		sQuery += "&mes_dados="+frm.mes_dados.value;

    		jan = window.open('pes2_relfichafinanceira002.php?'+sQuery,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
    		jan.moveTo(0,0);  
    	  return true;

      }
      
    }else{

      alert(msgm);
      eval(foco);
    }
  }else{

    alert("Ano / Mês início não informado.");
    document.form1.anoi.select();
  }
}

function js_verifica_selecionadas(){

  if(document.form1.rubricas_selecionadas && document.form1.rubricas_selecionadas.length > 0){
    document.form1.rubricas_selecionadas_text.value = js_campo_recebe_valores();
  }else if(document.form1.matriculas_selecionadas && document.form1.matriculas_selecionadas.length > 0){
    document.form1.matriculas_selecionadas_text.value = js_campo_recebe_valores();
  }

  document.form1.target = '';
  document.form1.action = '';
  document.form1.submit();
}

</script>
</body>
</html>