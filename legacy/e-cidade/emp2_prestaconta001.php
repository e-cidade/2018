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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_liborcamento.php");
require_once("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clrotulo= new rotulocampo;
$clrotulo->label("e60_codemp");
$clrotulo->label("e60_anousu");
$clrotulo->label("z01_nome");
$clrotulo->label("e60_numcgm");
$clrotulo->label("o40_orgao");
$clrotulo->label("o40_descr");
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>
function js_abre(){
  var query = "";

  if (document.form1.db_selinstit.value == ""){
       alert("Selecione alguma instituicao.");
       return false;
  }
  var e60_codemp = document.form1.e60_codemp.value;
  var empenho = e60_codemp.split("/");

  query  = "historico="+document.form1.historico.value;
  query += "&ordem="+document.form1.ordem.value;
  query += "&e60_numcgm="+document.form1.e60_numcgm.value;
  query += "&e60_codemp="+empenho[0];
  if( empenho.length > 1 ) {
    query += "&e60_anousu="+empenho[1];
  } else {
    query += "&e60_anousu=";
  }
  query += "&o40_orgao="+document.form1.o40_orgao.value;
  query += "&quebrarpagorgao="+document.form1.quebrarpagorgao.value;
  query += "&data="+document.form1.data1_ano.value+'-';
  query += document.form1.data1_mes.value+'-';
  query += document.form1.data1_dia.value;
  query += "&data1="+document.form1.data11_ano.value+'-';
  query += document.form1.data11_mes.value+'-';
  query += document.form1.data11_dia.value;
  query += "&data_lanc="+document.form1.data_lanc_ano.value+'-';
  query += document.form1.data_lanc_mes.value+'-';
  query += document.form1.data_lanc_dia.value;
  query += "&data_lanc1="+document.form1.data_lanc1_ano.value+'-';
  query += document.form1.data_lanc1_mes.value+'-';
  query += document.form1.data_lanc1_dia.value;
  query += "&db_selinstit="+document.form1.db_selinstit.value;

  jan = window.open('emp2_prestaconta002.php?'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
function js_limpa(){
   location.href='emp2_prestaconta001.php';
}

</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body style="background-color: #CCCCCC; margin-top: 25px;">
<div class="container">
<form name="form1" method="post" action="">
<fieldset style="width: 550px;">
  <legend class="bold">Prestação de Contas</legend>

  <table border="0" align="center">
      <tr>
        <td align="center" colspan="2">
          <?
          db_selinstit("",300,100);
          ?>
        </td>
      </tr>
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td align="left" nowrap title="<?=$Te60_numcgm?>"><?db_ancora(@$Le60_numcgm,"js_pesquisae60_numcgm(true);",1);?></td>
        <td align="left" nowrap>
          <? db_input("e60_numcgm",6,$Ie60_numcgm,true,"text",4,"onchange='js_pesquisae60_numcgm(false);'");
          db_input("z01_nome",40,"$Iz01_nome",true,"text",3);
          ?></td>
      </tr>

      <tr>
        <td  align="left" nowrap title="<?=$Te60_codemp?>">
          <? db_ancora($Le60_codemp,"js_pesquisae60_codemp(true);",1);  ?>
        </td>

        <td  nowrap>

          <input name="e60_codemp" title='<?=$Te60_codemp?>' size="12" type='text'   >
        </td>
      </tr>

      <tr>
        <td nowrap title='Emissao de Empenho' >
          <b><strong>Período Empenho</strong> </b>
        </td>
        <td>
          <?
          db_inputdata('data1','','','',true,'text',1,"");
          echo " a ";
          db_inputdata('data11','','','',true,'text',1,"");
          ?>
        </td>
      </tr>
      <tr >
        <td align="left" nowrap title="Todos/Pendentes para acerto/Conferidos/Não conferidos" >
          <strong>Filtrar por:&nbsp;&nbsp;</strong>
        </td>
        <td>
          <?
          $tipo_ordem = array("a"=>"Todos","b"=>"Pendentes para acerto","c"=>"Conferidos","d"=>"Não conferidos");
          db_select("ordem",$tipo_ordem,true,2); ?>
        </td>
      </tr>
      <tr>
        <td nowrap title='Data do lançamento (acerto/conferência).' >
          <b><strong> Período Lançamento: </strong> </b>
        </td>
        <td>
          <?
          db_inputdata('data_lanc','','','',true,'text',1,"");
          echo " a ";
          db_inputdata('data_lanc1','','','',true,'text',1,"");
          ?>

        </td>
      </tr>

      <tr>
        <td align="left" nowrap title="" >
          <strong>Histórico do Empenho:&nbsp;&nbsp;</strong>
        </td>
        <td>
          <?
          $xxx = array("n"=>"Não","s"=>"Sim");
          db_select("historico",$xxx,true,2); ?>
        </td>
      </tr>
      <tr>
        <td align="left" nowrap title="<?=$To40_orgao?>"><?db_ancora(@$Lo40_orgao,"js_pesquisao40_orgao(true);",1);?></td>
        <td align="left" nowrap>
          <? db_input("o40_orgao",6,$Io40_orgao,true,"text",4,"onchange='js_pesquisao40_orgao(false);'");
          db_input("o40_descr",40,"$Io40_descr",true,"text",3);
          ?></td>
      </tr>

      <tr>
        <td align="left" nowrap title="" >
          <strong>Quebrar página por órgão:&nbsp;&nbsp;</strong>
        </td>
        <td>
          <?
          $xxx = array("n"=>"Não","s"=>"Sim");
          db_select("quebrarpagorgao",$xxx,true,2); ?>
        </td>
      </tr>
  </table>
</fieldset>
  <p align="center">
    <input name="pesquisa" type="button" onclick='js_abre();'  value="Imprimir">
    <input name="limpa" type="button" onclick='js_limpa();'  value="Limpar campos">
  </p>
</form>
</div>
  <?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
</body>
</html>
  <script>
//--------------------------------
function js_pesquisae60_codemp(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_emppresta','func_emppresta.php?funcao_js=parent.js_mostraempenho1|e60_codemp|e60_anousu','Pesquisa',true);
  }else{
    // js_OpenJanelaIframe('top.corpo','db_iframe_empempenho02','func_empempenho.php?pesquisa_chave='+document.form1.e60_numemp.value+'&funcao_js=parent.js_mostraempempenho','Pesquisa',false);
  }
}

//--------------------------------
function js_pesquisa_empenho(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_emppresta','func_emppresta.php?funcao_js=parent.js_mostraempenho1|e60_numemp','Pesquisa',true);
  }else{
    if(document.form1.e60_numemp.value != ''){
      js_OpenJanelaIframe('top.corpo','db_iframe_emppresta','func_empempenho.php?pesquisa_chave='+document.form1.e60_numemp.value+'&funcao_js=parent.js_mostraempenho','Pesquisa',false);
    }else{
      document.form1.z01_nome1.value = '';
    }
  }
}
function js_mostraempenho(erro,chave){
  if(erro==true){
    document.form1.e60_numemp.focus();
    document.form1.z01_nome1.value = '';
  }
}
function js_mostraempenho1(chave1,chave2){
  document.form1.e60_codemp.value = chave1+'/'+chave2;
  // document.form1.z01_nome1.value = chave2;
  db_iframe_emppresta.hide();
}
//---------------------------------------------------------------
function js_pesquisae60_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
    if(document.form1.e60_numcgm.value != ''){
      js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_nome.php?pesquisa_chave='+document.form1.e60_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
    }else{
      document.form1.z01_nome.value = '';
    }
  }
}

function js_mostracgm(erro,chave){
  document.form1.z01_nome.value = chave;
  if(erro==true){
    document.form1.e60_numcgm.focus();
    document.form1.e60_numcgm.value = '';
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.e60_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}

function js_pesquisao40_orgao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orgao','func_orcorgao.php?funcao_js=parent.js_mostraorgao1|o40_orgao|o40_descr','Pesquisa',true);
  }else{
    if(document.form1.o40_orgao.value != ''){
      js_OpenJanelaIframe('top.corpo','db_iframe_orgao','func_orcorgao.php?pesquisa_chave='+document.form1.o40_orgao.value+'&funcao_js=parent.js_mostraorgao','Pesquisa',false);
    }else{
      document.form1.o40_descr.value = '';
    }
  }
}

function js_mostraorgao(chave,erro){
  document.form1.o40_descr.value = chave;
  if(erro==true){
    document.form1.o40_orgao.focus();
    document.form1.o40_orgao.value = '';
  }
}
function js_mostraorgao1(chave1,chave2){
  document.form1.o40_orgao.value = chave1;
  document.form1.o40_descr.value = chave2;
  db_iframe_orgao.hide();
}
//----------------------------------------------------------------------
  </script>

<?php
if(isset($ordem)){
  echo "<script>
       js_emite();
       </script>";
}
$func_iframe = new janela('db_iframe','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=780;
$func_iframe->altura=430;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();

?>