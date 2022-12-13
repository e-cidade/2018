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

//MODULO: escola
$clprocavalalternativa->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed41_i_formaavaliacao");
$sql = "SELECT ed44_i_codigo,
               ed44_i_procavaliacao,
               ed09_c_descr,
               case
                when ed44_i_codigo>0 then 'A' end as ed14_c_descr,
               ed44_i_peso,
               ed44_c_minimoaprov,
               ed44_c_obrigatorio,
               ed41_i_sequencia
        FROM avalcompoeres
         inner join procavaliacao on procavaliacao.ed41_i_codigo = avalcompoeres.ed44_i_procavaliacao
         inner join periodoavaliacao on periodoavaliacao.ed09_i_codigo = procavaliacao.ed41_i_periodoavaliacao
        WHERE ed44_i_procresultado = $ed281_i_procresultado
        UNION
        SELECT ed68_i_codigo,
               ed68_i_procresultcomp,
               ed42_c_descr,
               case
                when ed68_i_codigo>0 then 'R' end as ed14_c_descr,
               ed68_i_peso,
               ed68_c_minimoaprov,
               ed43_c_boletim,
               ed43_i_sequencia
        FROM rescompoeres
         inner join procresultado on procresultado.ed43_i_codigo = rescompoeres.ed68_i_procresultcomp
         inner join resultado on resultado.ed42_i_codigo = procresultado.ed43_i_resultado
        WHERE ed68_i_procresultado = $ed281_i_procresultado
        ORDER BY ed41_i_sequencia
       ";
$query = db_query($sql);
$linhas = pg_num_rows($query);
$sql11 = "SELECT max(ed281_i_alternativa) FROM procavalalternativa WHERE ed281_i_procresultado = $ed281_i_procresultado";
$query11 = db_query($sql11);
$novaalternativa = pg_result($query11,0)+1;
?>
<form name="form1" method="post" action="">
<center>
<table border="0" width="100%">
  <tr>
    <td>

    </td>
    <td>

    </td>
  </tr>
  <tr>
    <td>
     <fieldset style="width:95%;"><legend><b>Registros:</b></legend>
     <table width="100%" border="1" cellspacing="0" cellpadding="1" bgcolor="#f3f3f3">
     <?php
     $result_prc = $clprocavalalternativa->sql_record($clprocavalalternativa->sql_query("","ed281_i_codigo,ed281_i_alternativa","ed281_i_alternativa"," ed281_i_procresultado = $ed281_i_procresultado"));
     if($clprocavalalternativa->numrows>0){
      for($rr=0;$rr<$clprocavalalternativa->numrows;$rr++){
       db_fieldsmemory($result_prc,$rr);
       ?>
       <tr>
        <td>
        Alternativa <?=$ed281_i_alternativa?>
        </td>
        <td>
         <table width="100%" border="1" cellspacing="0" cellpadding="1" bgcolor="#f3f3f3">
          <tr>
          <?php
          $result_regra = $clprocavalalternativaregra->sql_record($clprocavalalternativaregra->sql_query("","ed282_i_codigo,case when ed282_i_tipoaval = 'A' then ed09_c_descr else ed42_c_descr end as nomeaval,ed37_i_menorvalor,ed37_i_maiorvalor,ed37_c_descr","ed41_i_sequencia"," ed282_i_procavalalternativa = $ed281_i_codigo"));
          for($tt=0;$tt<$clprocavalalternativaregra->numrows;$tt++){
           db_fieldsmemory($result_regra,$tt);
           ?>
           <td>
            <?=$nomeaval?><br>
            <b><?=$ed37_i_menorvalor!=""?" $ed37_c_descr - <font color=red>$ed37_i_menorvalor à $ed37_i_maiorvalor </font>":"Em Branco"?></b>
           </td>
           <?php
          }
          ?>
          </tr>
         </table>
        </td>
        <td>
        <input type="button" class="btn_gridExcluir" value="Excluir" name="excluir" onclick="return js_exclusao(<?=$ed281_i_codigo?>)">
        </td>
       </tr>
       <?php
      }
     }else{
      echo "<tr><td>Nenhum registro encontrado.</td></tr>";
     }
     ?>
     </table>
     </fieldset>
    </td>
  </tr>
</table>
<input name="novo" type="button" id="novo" value="Nova Alternativa" onclick="js_novo();" <?=$linhas==0?"disabled":""?>>
<input name="clicado" type="hidden" id="clicado" value="">
<input name="procedimento" type="hidden" value="<?=$procedimento?>">
<input name="ed281_i_procresultado" type="hidden" value="<?=$ed281_i_procresultado?>">
<input name="ed42_c_descr" type="hidden" value="<?=$ed42_c_descr?>">
<input name="forma" type="hidden" value="<?=$forma?>">
<div id="div_novo"></div>
</center>
</form>
<script>

var oGet = js_urlToObject();

if (oGet.possuiTurmasEncerradas && oGet.possuiTurmasEncerradas == 'S') {

  $('novo').setAttribute('disabled','disabled');

  $$('.btn_gridExcluir').forEach( function( oBotaoExcluir ){
    oBotaoExcluir.setAttribute('disabled','disabled');
  });
}

function js_novo(){
 sHtml  = '<table width="95%">';
 sHtml += '<tr>';
 sHtml += ' <td align="center" style="border:1px solid #000000">';
 sHtml += '  Alternativa <b><?=$novaalternativa?></b>';
 sHtml += '  <input type="hidden" size="5 maxlength="5" name="ed281_i_alternativa" id="ed281_i_alternativa" value="<?=$novaalternativa?>">';
 sHtml += ' </td>';
 <?php
  for($ee=0;$ee<$linhas;$ee++){
  db_fieldsmemory($query,$ee)
  ?>
  sHtml += ' <td>';
  sHtml += '  <b><?=$ed09_c_descr?></b> <?=$ed44_c_obrigatorio=="S"?"(Obrigatório)":""?><br>';
  sHtml += '  <a href="javascript:js_formaavaliacao(<?=$ee?>)"><b>Forma de Avaliação:</b></a>';
  sHtml += '  <input type="hidden" size="5 maxlength="5" name="xcodavaliacao[]" id="xcodavaliacao" value="<?=$ed44_i_procavaliacao?>">';
  sHtml += '  <input type="hidden" size="1 maxlength="1" name="xtipoaval[]" id="xtipoaval" value="<?=$ed14_c_descr?>">';
  sHtml += '  <input type="text" size="5 maxlength="5" name="xformaavaliacao[]" id="xformaavaliacao" value="" style="background:#DEB887" readonly>';
  sHtml += '  <input type="text" size="10" maxlength="10" name="xdescr[]" id="xdescr" style="background:#DEB887" readonly>';
  sHtml += '  <input type="hidden" size="1 maxlength="1" name="xobrigatorio[]" id="xobrigatorio" value="<?=$ed44_c_obrigatorio?>" style="background:#DEB887" readonly>';
  sHtml += ' </td>';
 <?php } ?>
 sHtml += '</tr>';
 sHtml += '<tr>';
 sHtml += ' <td>';
 sHtml += '  <input type="submit" name="incluir" value="Incluir" onclick="return js_valida();">';
 sHtml += ' </td>';
 sHtml += '</tr>';
 sHtml += '</table>';
 document.getElementById("div_novo").innerHTML = sHtml;
}
function js_formaavaliacao(campo){

 document.form1.clicado.value = campo;
 js_OpenJanelaIframe( '',
                      'db_iframe_formaavaliacao',
                      'func_formaavaliacao.php?forma=<?=$forma?>&funcao_js=parent.js_mostraformaavaliacao1|ed37_i_codigo|ed37_c_descr',
                      'Pesquisa de Formas de Avaliação',
                      true,
                      0,
                      0,
                      900,
                      200 );
}
function js_mostraformaavaliacao1(chave1,chave2){
 eval('document.form1.xformaavaliacao['+document.form1.clicado.value+'].value = chave1');
 eval('document.form1.xdescr['+document.form1.clicado.value+'].value = chave2');
 db_iframe_formaavaliacao.hide();
}
function js_valida(){
 var tam = document.form1.xformaavaliacao.length;
 erro = false;
 for(t=0;t<tam;t++){
  if(document.form1.xformaavaliacao[t].value=="" && document.form1.xobrigatorio[t].value=="S"){
   erro = true;
  }
 }
 if(erro==true){
  alert("Informe a forma de avaliação para períodos obrigatórios!");
  return false;
 }
 return true;
}
function js_exclusao(codexclusao){
 if(confirm('Confirmar Exclusão?')){
  location.href="edu1_procavalalternativa001.php?procedimento=<?=$procedimento?>&ed281_i_procresultado=<?=$ed281_i_procresultado?>&ed42_c_descr=<?=$ed42_c_descr?>&forma=<?=$forma?>&codexclusao="+codexclusao+"&excluir&possuiTurmasEncerradas="+oGet.possuiTurmasEncerradas;
 }
}
</script>