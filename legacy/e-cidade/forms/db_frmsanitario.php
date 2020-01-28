<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBSeller Servicos de Informatica
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

//MODULO: fiscal
require_once("classes/db_db_cgmruas_classe.php");
require_once("classes/db_db_cgmbairro_classe.php");
require_once("classes/db_cgm_classe.php");
$clruas   = new cl_db_cgmruas;
$clbairro = new cl_db_cgmbairro;
$clcgm    = new cl_cgm;
$clsanitario->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("j14_nome");
$clrotulo->label("j13_descr ");
$clrotulo->label("q02_inscr");

$Iy80_numcgm_outro = "";

if (isset($y80_numcgm) && $y80_numcgm != "" && $db_opcao == 1) {

  $result = $clruas->sql_record($clruas->sql_query("","*",""," db_cgmruas.z01_numcgm = $y80_numcgm "));

  if ($clruas->numrows > 0) {

    db_fieldsmemory($result,0);
    $y80_codrua = $j14_codigo;
  }

  $result = $clbairro->sql_record($clbairro->sql_query("","*",""," db_cgmbairro.z01_numcgm = $y80_numcgm "));

  if ($clbairro->numrows > 0) {

    db_fieldsmemory($result,0);
    $y80_codbairro = $j13_codi;
  }

  $result = $clcgm->sql_record($clcgm->sql_query("","*",""," cgm.z01_numcgm = $y80_numcgm "));

  if($clcgm->numrows > 0){

    db_fieldsmemory($result,0);
    $y80_numero    = $z01_numero;
    $y80_compl     = $z01_compl;
  }
}

if (@$y80_codsani!="") {

	$sqlsani = "select q02_inscr,
	                   q02_numcgm,
					   z01_nome as z01_nome1
 			      from sanitarioinscr
				 inner join issbase on q02_inscr  = y18_inscr
				 inner join cgm     on z01_numcgm = q02_numcgm
				 where y18_codsani=$y80_codsani";
	$resultsani = db_query($sqlsani);
	$linhassani = pg_num_rows($resultsani);

	if($linhassani>0){
		db_fieldsmemory($resultsani,0);
	}
}

?>
<form name="form1" method="post" action="" id ="form1">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ty80_codsani?>">
       <label for='y80_codsani'><?=@$Ly80_codsani?></label>
    </td>
    <td>
      <?
        db_input('y80_codsani',6,$Iy80_codsani,true,'text',3,"");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty80_numbloco?>">
      <label for='y80_numbloco'><?=@$Ly80_numbloco?></label>
    </td>
    <td>
      <?
        db_input('y80_numbloco',6,$Iy80_numbloco,true,'text',$db_opcao,"");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tz01_nome?>">
      <?
        db_ancora(@$Lz01_nome,"js_pesquisay80_numcgm(true);",$db_opcao);
      ?>
    </td>
    <td>
      <?
        db_input('y80_numcgm',6,$Iy80_numcgm,true,'text',$db_opcao," onchange='js_pesquisay80_numcgm(false);'");
        db_input('y80_numcgm_outro',6,$Iy80_numcgm_outro,true,'hidden',3);
		  ?>
      <?
        db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj14_nome?>">
      <?
       db_ancora(@$Lj14_nome,"js_pesquisaruas(true);",($db_opcao == 3 || $db_opcao == 33)?3:1);
      ?>
    </td>
    <td>
      <?
				db_input('y80_codrua',6,$Iy80_codrua,true,'text',$db_opcao," onChange='js_pesquisaruas(false)'")
			?>
      <?
       	db_input('j14_nome',40,$Ij14_nome,true,'text',3,'')
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj13_descr?>">
      <?
       db_ancora(@$Lj13_descr,"js_pesquisabairro(true);",($db_opcao == 3 || $db_opcao == 33)?3:1);
      ?>
    </td>
    <td>
      <?
        db_input('y80_codbairro',6,$Iy80_codbairro,true,'text',$db_opcao," onChange='js_pesquisabairro(false)'")
      ?>
      <?
        db_input('j13_descr',40,$Ij13_descr,true,'text',3,'')
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty80_numero?>">
       <?=@$Ly80_numero?>
    </td>
    <td>
      <table>
        <tr>
          <td>
            <?
              db_input('y80_numero',10,$Iy80_numero,true,'text',$db_opcao,"")
            ?>
          </td>
          <td nowrap title="<?=@$Ty80_compl?>">
             <?=@$Ly80_compl?>
          </td>
          <td>
            <?
              db_input('y80_compl',20,$Iy80_compl,true,'text',$db_opcao,"")
            ?>
          </td>
        </tr>
      </table>
</td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty80_data?>">
       <?=(@$y80_dtbaixa == ""?@$Ly80_data:'')?>
    </td>
    <td>
      <?
        if(empty($y80_data_dia)){

          $y80_data_dia = date("d",db_getsession("DB_datausu"));
          $y80_data_mes = date("m",db_getsession("DB_datausu"));
          $y80_data_ano = date("Y",db_getsession("DB_datausu"));
        }

        if(isset($y80_dtbaixa) && $y80_dtbaixa != "") {
          echo "<strong>Alvará Sanitário sem atividades em funcionamento <br> Data da Baixa : ".db_formatar($y80_dtbaixa,'d')."</strong> ";
        } else {
          db_inputdata('y80_data',@$y80_data_dia,@$y80_data_mes,@$y80_data_ano,true,'text',$db_opcao,"");
        }
      ?>
    </td>
  </tr>

  <tr>
    <td nowrap title="<?=@$Ty80_area?>">
      <label for='y80_area'><?=@$Ly80_area?><label>
    </td>
    <td>
      <?
        db_input('y80_area',10,$Iy80_area,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq02_inscr?>">
       <?
       db_ancora(@$Lq02_inscr,"js_pesquisainscr(true);",($db_opcao == 3 || $db_opcao == 33)?3:1);
       ?>
    </td>
    <td>
		<?
		db_input('q02_inscr',6,$Iq02_inscr,true,'text',$db_opcao," onChange='js_pesquisainscr(false)'")
		?>
		       <?
		db_input('z01_nome1',40,@$z01_nome1,true,'text',3,'')
		       ?>
    </td>
  </tr>



  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
<input name="novo" type="button" value="Novo Registro" onclick="parent.document.location.href='fis1_sanitario001.php'" >

</form>


<script>


  function js_pesquisaruas(mostra){
  if(mostra == true){
    js_OpenJanelaIframe('','db_iframe_ruas','func_ruas.php?rural=1&funcao_js=parent.js_preencheruas|j14_codigo|j14_nome','Pesquisa',true,0);
  }else{
    js_OpenJanelaIframe('','db_iframe_ruas','func_ruas.php?pesquisa_chave='+document.form1.y80_codrua.value+'&rural=1&funcao_js=parent.js_preencheruas1','Pesquisa',false,0);
  }
  }
function js_preencheruas(chave,chave1){
  document.form1.y80_codrua.value = chave;
  document.form1.j14_nome.value = chave1;
  db_iframe_ruas.hide();
}
function js_preencheruas1(chave,erro){
  document.form1.j14_nome.value = chave;
  if(erro == true){
    document.form1.y80_codrua.focus();
    document.form1.y80_codrua.value = '';
  }
  db_iframe_ruas.hide();
}


function js_pesquisainscr(mostra){

  if(mostra == true){
	js_OpenJanelaIframe('','db_iframe_inscr','func_issbase.php?funcao_js=parent.js_preencheinscr|q02_inscr|z01_nome','Pesquisa',true);
  }else{
	if (document.form1.q02_inscr.value != '') {
    	js_OpenJanelaIframe('','db_iframe_inscr','func_issbase.php?sani=1&pesquisa_chave='+document.form1.q02_inscr.value+'&funcao_js=parent.js_preencheinscr1','Pesquisa',false);
  	}else{
		document.form1.q02_inscr.value = '';
	}
  }
}

function js_preencheinscr(chave,chave1){
  document.form1.q02_inscr.value = chave;
  document.form1.z01_nome1.value = chave1;
  db_iframe_inscr.hide();
}

function js_preencheinscr1(chave,chave1){
  document.form1.z01_nome1.value = chave1;
  if(chave1 == true){
    document.form1.z01_nome1.value = chave;
    document.form1.q02_inscr.value = '';
    document.form1.q02_inscr.focus();
  }
  db_iframe_inscr.hide();
}



function js_pesquisabairro(mostra){
  if(mostra == true){
	js_OpenJanelaIframe('','db_iframe_bairro','func_bairro.php?rural=1&funcao_js=parent.js_preenchebairro|j13_codi|j13_descr','Pesquisa',true,0);
  }else{
    js_OpenJanelaIframe('','db_iframe_bairro','func_bairro.php?pesquisa_chave='+document.form1.y80_codbairro.value+'&rural=1&funcao_js=parent.js_preenchebairro1','Pesquisa',false,0);
  }
}

function js_preenchebairro(chave,chave1){
  document.form1.y80_codbairro.value = chave;
  document.form1.j13_descr.value = chave1;
  db_iframe_bairro.hide();
}
function js_preenchebairro1(chave,erro){
  document.form1.j13_descr.value = chave;
  if(erro == true){
    document.form1.y80_codbairro.value = '';
    document.form1.y80_codbairro.focus();
  }
  db_iframe_bairro.hide();
}
function js_pesquisay80_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_cgm','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true,0);
  }else{

    js_OpenJanelaIframe('','db_iframe_cgm','func_nome.php?pesquisa_chave='+document.form1.y80_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
  }
}
function js_mostracgm(erro,chave){

  document.form1.z01_nome.value = chave;
  if(erro==true){
    document.form1.y80_numcgm.focus();
    document.form1.y80_numcgm.value = '';
  }
  var iNumCgm = $F("y80_numcgm");

  // js_confirmaCgm(iNumCgm);
  <?
  // if($db_opcao == 1){
  ?>
    // if(erro == false){
    //   document.form1.submit();
    // }
  <?
  // }
  ?>
}
function js_mostracgm1(chave1,chave2){

  document.form1.y80_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
  // js_confirmaCgm(chave1);
  <?
  // if($db_opcao == 1){
  ?>

    // if (chave1 != "") {
    //   document.form1.submit();
    // }
  <?
  // }
  ?>

}
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_sanitario',
                      'func_sanitario.php?lMostarTodos=true&funcao_js=parent.js_preenchepesquisa|y80_codsani',
                      'Pesquisa',true,0);
}

function js_confirmaCgm(iNumCgm) {

  var sRPC       = 'fis1_sanitario.RPC.php';
	var oParam     = new Object();
	oParam.exec    = 'getDadosCgm';
	oParam.iNumCgm = iNumCgm;

  var oAjax = new Ajax.Request(
                  							sRPC,
                  							{ parameters: 'json='+Object.toJSON(oParam),
                  								method:     'post',
                  								asynchronous:false,
                  								onComplete : js_retornoNumeroCgm
                                }
                              );
}

function js_retornoNumeroCgm(oAjax) {

  var oRetorno = eval('('+oAjax.responseText+')');
  if (oRetorno.status == 2) {

	  if (!confirm (oRetorno.message.urlDecode())) {

		  var aText = $('form1').getInputs('text');
      aText.each(function (oText, id) {
		    oText.value = "";
		  });
      $('y80_numcgm').focus();
	  }
  }
}

function js_preenchepesquisa(chave){
  db_iframe_sanitario.hide();
  <?
    if($db_opcao == 2 || $db_opcao == 22){
      echo " location.href = 'fis1_sanitario002.php?chavepesquisa='+chave;";
    }elseif($db_opcao == 33 || $db_opcao == 3){
      echo " location.href = 'fis1_sanitario003.php?chavepesquisa='+chave;";
    }
  ?>
}
<?
if($db_opcao != 1 && $db_botao == true){
?>
  if(document.form1.y80_codsani.value == "")
    document.form1.db_opcao.disabled=true;
  else
    document.form1.db_opcao.disabled=false;
<?
}
?>
</script>
<?
if(isset($y80_numcgm) && $z01_nome == ""){
  echo "<script>js_pesquisay80_numcgm(false)</script>";
}
if(isset($y80_numcgm) && $y80_numcgm != "" && $db_opcao == 1){
  echo "<script>document.form1.y80_numero.focus();</script>";
}
?>