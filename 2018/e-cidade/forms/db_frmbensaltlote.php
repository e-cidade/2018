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
include_once("classes/db_cfpatriplaca_classe.php");
$clcfpatriplaca = new cl_cfpatriplaca;

$clbens->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("t42_codigo");
$clrotulo->label("t42_descr");
$clrotulo->label("t64_descr");
$clrotulo->label("t64_class");
$clrotulo->label("t64_codcla");
$clrotulo->label("pc01_descrmater");
$clrotulo->label("z01_nome");
$clrotulo->label("descrdepto");
$clrotulo->label("o40_descr");
$clrotulo->label("o41_descr");
$clrotulo->label("t56_situac");
$clrotulo->label("t70_descr");


$clrotulo->label("t54_obs");
$clrotulo->label("t54_idbql");
$clrotulo->label("t53_ntfisc");
$clrotulo->label("t53_empen");
$clrotulo->label("t53_ordem");
$clrotulo->label("t53_garant");



if($db_opcao==1){
//$db_action="pat1_bens004.php";
}else if($db_opcao==2||$db_opcao==22){
$db_action="pat1_bensaltlote001.php";
}else if($db_opcao==3||$db_opcao==33){
//$db_action="pat1_bens006.php";
}
$res_cfpatriplaca = $clcfpatriplaca->sql_record($clcfpatriplaca->sql_query_file(db_getsession("DB_instit")));
$result           = $clcfpatri->sql_record($clcfpatri->sql_query_file());
$msg_erro         = "";

if($clcfpatri->numrows==0){
  $msg_erro       = "Estrutural não cadastrado no parâmetro.";
  $t06_codcla     = 0;
  $t07_confplcaca = 0;
}else{
  db_fieldsmemory($result,0);
}

if ($clcfpatriplaca->numrows==0){
     $msg_erro      = "Parâmetros de Placa não cadastrados.";
     $t07_confplaca = 0;
} else {
     db_fieldsmemory($res_cfpatriplaca,0);
}

$opc = $db_opcao;

$oDataAtual   = new DBDate(date("d/m/Y", db_getsession("DB_datausu")));
$oInstituicao = new Instituicao(db_getsession("DB_instit"));

$lPossuiIntegracaoPatrimonial = ParametroIntegracaoPatrimonial::possuiIntegracaoPatrimonio($oDataAtual, $oInstituicao);
?>


<script>

// CONFIGURAÇÕES FIELDSET IMÓVEL
function js_escondeFieldsetImovel(mostra){
  var tabelaImovel   = $("tabelaImovel");
  var fieldsetImovel = $("idDadosMovel");
  var legendImovel   = $("abreLegend");

  if (mostra == "sim") {
    tabelaImovel.style.display = 'block';
    var oCampo = fieldsetImovel;
    oCampo.style.width = '500px';
    oCampo.style.cursor = 'pointer';

    legendImovel.style.background   = 'url(imagens/seta.gif) no-repeat right';
    legendImovel.style.paddingRight = '15px';
    legendImovel.observe('click', function () {

      if (tabelaImovel.style.display == 'none') {

        tabelaImovel.style.display               = 'block';
        $('tabelaMaterial').style.display        = 'none';
        $("abreLegendMaterial").style.background = 'url(imagens/seta.gif) no-repeat right';
        legendImovel.style.background            = 'url(imagens/setabaixo.gif) no-repeat right';
        legendImovel.style.paddingRight          = '15px';
        $('t53_ntfisc').disable();
        $('t54_idbql').enable();

        $('t53_ntfisc').value = "";
        $('t53_empen').value  = "";
        $('t53_ordem').value  = "";
        $('t53_garant').value = "";

      } else {
        $('t53_ntfisc').enable();
        tabelaImovel.style.display      = 'none';
        legendImovel.style.background   = 'url(imagens/seta.gif) no-repeat right';
        legendImovel.style.paddingRight = '15px';

      }

    });
  } else if (mostra == "nao") {
    tabelaImovel.style.display = 'none';
  } else {
    var oCampo = fieldsetImovel;
    oCampo.style.width = '500px';
    oCampo.style.cursor = 'pointer';

    legendImovel.style.background   = 'url(imagens/seta.gif) no-repeat right';
    legendImovel.style.paddingRight = '15px';
    legendImovel.observe('click', function () {

      if (tabelaImovel.style.display == 'none') {

        tabelaImovel.style.display               = 'block';
        $('tabelaMaterial').style.display        = 'none';
        $("abreLegendMaterial").style.background = 'url(imagens/seta.gif) no-repeat right';
        legendImovel.style.background            = 'url(imagens/setabaixo.gif) no-repeat right';
        legendImovel.style.paddingRight          = '15px';
        $('t53_ntfisc').disable();
        $('t54_idbql').enable();

        $('t53_ntfisc').value = "";
        $('t53_empen').value  = "";
        $('t53_ordem').value  = "";
        $('t53_garant').value = "";

      } else {
        $('t53_ntfisc').enable();
        tabelaImovel.style.display      = 'none';
        legendImovel.style.background   = 'url(imagens/seta.gif) no-repeat right';
        legendImovel.style.paddingRight = '15px';

      }

    });

  }
}

// CONFIGURAÇÕES FIELDSET MATERIAL
function js_escondeFieldsetMaterial(mostra){
  var tabelaMaterial   = $("tabelaMaterial");
  var fieldsetMaterial = $("idDadosMaterial");
  var legendMaterial   = $("abreLegendMaterial");

  if (mostra == "sim") {
    tabelaMaterial.style.display = 'block';
    var oCampo = fieldsetMaterial;
    oCampo.style.width = '500px';
    oCampo.style.cursor = 'pointer';

    legendMaterial.style.background   = 'url(imagens/seta.gif) no-repeat right';
    legendMaterial.style.paddingRight = '15px';
    legendMaterial.observe('click', function () {

      if (tabelaMaterial.style.display == 'none') {

        tabelaMaterial.style.display      = 'block';
        $('tabelaImovel').style.display   = 'none';
        $("abreLegend").style.background  = 'url(imagens/seta.gif) no-repeat right';
        legendMaterial.style.background   = 'url(imagens/setabaixo.gif) no-repeat right';
        legendMaterial.style.paddingRight = '15px';
        $('t54_idbql').disable();
        $('t53_ntfisc').enable();
        $('t54_idbql').value = "";
        $('t54_obs').value   = "";

      } else {
        $('t54_idbql').enable();
        tabelaMaterial.style.display      = 'none';
        legendMaterial.style.background   = 'url(imagens/seta.gif) no-repeat right';
        legendMaterial.style.paddingRight = '15px';
      }

    });
  } else if (mostra == "nao") {
    tabelaMaterial.style.display = 'none';
  } else {

    var oCampo = fieldsetMaterial;
    oCampo.style.width = '500px';
    oCampo.style.cursor = 'pointer';

    legendMaterial.style.background   = 'url(imagens/seta.gif) no-repeat right';
    legendMaterial.style.paddingRight = '15px';
    legendMaterial.observe('click', function () {

      if (tabelaMaterial.style.display == 'none') {

        tabelaMaterial.style.display      = 'block';
        $('tabelaImovel').style.display   = 'none';
        $("abreLegend").style.background  = 'url(imagens/seta.gif) no-repeat right';
        legendMaterial.style.background   = 'url(imagens/setabaixo.gif) no-repeat right';
        legendMaterial.style.paddingRight = '15px';
        $('t54_idbql').disable();
        $('t53_ntfisc').enable();
        $('t54_idbql').value = "";
        $('t54_obs').value   = "";

      } else {
        $('t54_idbql').enable();
        tabelaMaterial.style.display      = 'none';
        legendMaterial.style.background   = 'url(imagens/seta.gif) no-repeat right';
        legendMaterial.style.paddingRight = '15px';
      }

    });
  }
}

</script>


<form name="form1" method="post" action="<?=$db_action?>" onsubmit="return js_processaFormulario();">
<fieldset>
<legend><b>Alteração de Bens</b></legend>
<center>
<table border="0" align="center">
  <tr>
    <td nowrap title="<?=@$Tt42_codigo?>">
       <?=@$Lt42_codigo?>
    </td>
    <td>
      <?
        db_input('t42_codigo',8,$It42_codigo,true,'text',3,"");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tt42_descr?>">
       <?=@$Lt42_descr?>
    </td>
    <td>
      <?
        db_input('t42_descr',51,$It42_descr,true,'text',3,"");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tt52_descr?>">
       <?=@$Lt52_descr?>
    </td>
    <td>
      <?
        db_input('t52_descr',51,$It52_descr,true,'text',$db_opcao,"");
      ?>
    </td>
  </tr>
    <tr>
    <td nowrap title="<?=@$Tt64_class?>">
       <?
         db_ancora(@$Lt64_class,"js_pesquisat64_class(true);",(($db_opcao == 2 && $lPossuiIntegracaoPatrimonial) ? 3 : $db_opcao));
       ?>
    </td>
    <td>
      <?
        $cldb_estrut->autocompletar = true;
        $cldb_estrut->mascara = false;
        $cldb_estrut->input   = true;
        $cldb_estrut->reload  = false;
        $cldb_estrut->size    = 10;
        $cldb_estrut->funcao_onchange ='js_pesquisat64_class(false);';
        $cldb_estrut->nome    = "t64_class";
        $cldb_estrut->db_opcao= (($db_opcao == 2 && $lPossuiIntegracaoPatrimonial) ? 3 : $db_opcao);
        if(isset($t06_codcla) && $t06_codcla!=""){
          $cldb_estrut->db_mascara(@$t06_codcla);
        }else{
          db_msgbox($msg_erro);
        }
        //db_input('t64_class',8,$It64_class,true,'text',$db_opcao," onchange='js_pesquisat64_class(false);'");
        db_input('t64_descr',38,$It64_descr,true,'text',3,'');
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tt52_numcgm?>">
      <?
        db_ancora(@$Lt52_numcgm,"js_pesquisat52_numcgm(true);",$db_opcao);
      ?>
    </td>
    <td>
      <?
        db_input('t52_numcgm',8,$It52_numcgm,true,'text',$db_opcao," onchange='js_pesquisat52_numcgm(false);'");
        db_input('z01_nome',40,$Iz01_nome,true,'text',3,'');
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tt52_valaqu?>">
       <?=@$Lt52_valaqu?>
    </td>
    <td>
      <?
        db_input('t52_valaqu',15,$It52_valaqu,true,'text',$db_opcao,"");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tt52_dtaqu?>">
       <?=@$Lt52_dtaqu?>
    </td>
    <td>
      <?
        db_inputdata('t52_dtaqu',@$t52_dtaqu_dia,@$t52_dtaqu_mes,@$t52_dtaqu_ano,true,'text',$db_opcao,"");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tt52_obs?>">
       <?=@$Lt52_obs?>
    </td>
    <td>
			<?
			  db_textarea('t52_obs',3,48,$It52_obs,true,'text',$db_opcao,"")
			?>
    </td>
  </tr>
    <?php
  //Verifica se utiliza pesquisa por orgão sim ou não
	$resPesquisaOrgao	= $clcfpatri->sql_record($clcfpatri->sql_query_file(null,'t06_pesqorgao'));
	if($clcfpatri->numrows > 0) {
		db_fieldsmemory($resPesquisaOrgao,0);
		$lImprimeOrgao = $t06_pesqorgao;
	}
  if($lImprimeOrgao == 't'){
  	//$o40_descr = null;
		//$o41_descr = null;
  	if(isset($t52_depart)){

	  	$resPesquisaOrgaoUnidade	= $cldepartorg->sql_record($cldepartorg->sql_query_orgunid($t52_depart,null,'o40_descr,o41_descr'));
	  	//die($clbens->sql_query_file($t52_bem,'o40_descr,o41_descr'));
			if($cldepartorg->numrows > 0) {

				db_fieldsmemory($resPesquisaOrgaoUnidade,0);
			}
  	}

  ?>
  <tr>
  	<td><b>Órgão:</b></td>
  	<td><? db_input('o40_descr',51,$Io40_descr,3,'text',3); ?></td>
  </tr>
  <tr>
  	<td><b>Unidade:</b></td>
  	<td><? db_input('o41_descr',51,$Io41_descr,3,'text',3); ?></td>
  </tr>
  <?php
  }
  ?>
  <tr>
    <td nowrap title="<?=@$Tt52_depart?>">
       <?
       db_ancora(@$Lt52_depart,"js_pesquisat52_depart(true);",$db_opcao);
       ?>
    </td>
    <td>
      <?
        db_input('t52_depart',8,$It52_depart,true,'text',$db_opcao," onchange='js_pesquisat52_depart(false);'");
        db_input('descrdepto',40,$Idescrdepto,true,'text',3,'');
       ?>
    </td>
  </tr>
    <?if (isset($t52_depart)&&$t52_depart!=""){?>
  <tr>
    <td nowrap title="Divisão do Depart.">
    <b> Divisão:</b>
    </td>
    <td>
    <?if($db_opcao != 3){?>
    <select name='t33_divisao'>
	<option value=''>Nenhuma</option>
	<?
	$result=$cldepartdiv->sql_record($cldepartdiv->sql_query_file(null,"t30_codigo,t30_descr",null,"t30_depto=$t52_depart"));
	for($y=0;$y<$cldepartdiv->numrows;$y++){
 	  db_fieldsmemory($result,$y);
 	  ?>
	  <option value=<?=@$t30_codigo?> <?=(isset($t33_divisao)&&$t33_divisao==$t30_codigo?"selected":"")?> > <?=@$t30_descr?></option>
   	<?}?>
     </select>
     </td>

  <?}else{?>
  	<td>
<?
db_input('t33_divisao',6,$It33_divisao,true,'text',$db_opcao,"");
db_input('t30_descr',6,$It30_descr,true,'text',$db_opcao,"");
?>
    </td>
  <?}?>
  </tr>
  	<?  }?>
  <tr>
    <td nowrap title="<?=@$Tt56_situac?>">
       <?
       db_ancora(@$Lt56_situac,"js_pesquisat56_situac(true);",1);
       ?>
    </td>
    <td>
      <?
        db_input('t56_situac',8,$It56_situac,true,'text',1," onchange='js_pesquisat56_situac(false);'")
      ?>
      <?
        db_input('t70_descr',40,$It70_descr,true,'text',3,'');
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="Quantidade de ítens que serão incluídos">
        <b>Quantidade:</b>
    </td>
    <td>
       <?
         db_input('qtd',8,"",true,'text',3,'onchange="js_isnumber(this.value,this.name);"');
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="Descrição do lote">
        <b>Descrição do lote:</b>
    </td>
    <td>
       <?
         db_input('t42_descr',50,$It42_descr,true,'text',1,'');
       ?>
    </td>
  </tr>
  <tr>
   <td><strong>Altera Identificação:</strong></td>
   <td>
     <select name='update_ident'>
       <option value='false' > Não </option>
       <option value='true'  > Sim </option>
     </select>
   </td>
  </tr>
  <tr id="tr_dados_imovel">
    <td colspan='2' align="center">
    <fieldset id="idDadosMovel">
      <legend id="abreLegend"><b>Dados do Imóvel</b></legend>
      <table id="tabelaImovel" style="display:none;">
        <tr>
          <td width="4%" align="right" nowrap title="<?=$Tt54_idbql?>">
            <?
              db_ancora(@$Lt54_idbql,"js_pesquisat54_idbql(true);",$db_opcao);
            ?>
          </td>
          <td>
            <?
              db_input('t54_idbql',10,$It54_idbql,true,'text',$db_opcao," onchange='js_pesquisat54_idbql(false);'");
            ?>
          </td>
        </tr>
        <tr>
          <td width="4%" align="right" nowrap title="<?=$Tt54_obs?>">
            <?=$Lt54_obs?>
          </td>
          <td width="96%" align="left" nowrap>
            <?
              db_textarea("t54_obs", 5, 40, $It54_obs, true, '', $db_opcao);
            ?>
          </td>
        </tr>
      </table>
    </fieldset>
    </td>
  </tr>
  <tr id="tr_dados_material">
    <td colspan='2' align="center">
    <fieldset id="idDadosMaterial">
      <legend id="abreLegendMaterial"><b>Dados do Material</b></legend>
      <table id="tabelaMaterial" style="display:none;" border='0'>
        <tr>
          <td nowrap title="<?=@$Tt53_ntfisc?>">
            <?=@$Lt53_ntfisc?>
          </td>
          <td>
            <?
              db_input('t53_ntfisc',40,$It53_ntfisc,true,'text',$db_opcao,"");
             ?>
          </td>
        </tr>
        <tr>
          <td>
            <b>Empenho do Sistema:</b>
          </td>
          <td>
            <select id="emp_sistema" name="emp_sistema" style="width: 80px;" onChange='js_mudaProc(this.value);'>
              <option value="s">Sim</option>
              <option value="n">Não</option>
            </select>

          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tt53_empen;?>">
            <label style="font-weight: bold;" id='procAdm'>
              <? db_ancora(@$Lt53_empen,"js_pesquisat53_empen(true);",$db_opcao); ?>
            </label>

              <label style="font-weight: bold; display: none;" id='procAdm1'>
                Número do empenho:
              </label>

          </td>
          <td>
            <?
              db_input('t53_empen',6,$It53_empen,true,'text',$db_opcao," onchange='js_pesquisat53_empen(false);'");
            ?>
            <span id="campoDescricao">
              <?db_input('z01_nome_empenho',30,$Iz01_nome,true,'text',3,""); ?>
            </span>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tt53_ordem;?>">
            <?=@$Lt53_ordem;?>
          </td>
          <td>
          <?
            db_input('t53_ordem',6,$It53_ordem,true,'text',$db_opcao,"");
          ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tt53_garant;?>">
            <?=@$Lt53_garant;?>
          </td>
          <td>
          <?
            db_inputdata('t53_garant',@$t53_garant_dia,@$t53_garant_mes,@$t53_garant_ano,true,'text',$db_opcao,"");
          ?>
          </td>
        </tr>
      </table>
    </fieldset>
    </td>
  </tr>
  </table>
  </center>

</fieldset>
<br>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> <?=((isset($tipo_inclui)&&$tipo_inclui=="true"))?"onclick=''":""?>>
<!--"-->
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" <?=($msg_erro==""?"":"disabled")?> >
</form>
<script>
function js_mudaProc(sTipoProc){
  $('t53_empen').value = '';
  $('z01_nome_empenho').value = '';
  if ( sTipoProc == 's') {
    $('procAdm1').style.display = 'none';
    $('procAdm').style.display  = '';
  } else {
    $('campoDescricao').style.display  = 'none';
    $('procAdm').style.display  = 'none';
    $('procAdm1').style.display = '';
  }
}

function js_processaFormulario() {
  var campos = "";

  if ( $('t52_descr').value == '' ) {
    campos += 'Descrição - ';
  }
  if ( $('t52_numcgm').value == '' ) {
    campos += 'Fornecedor - ';
  }
  if ( $('t52_valaqu').value == '' ) {
    campos += 'Valor da Aquisição - ';
  }
  if ( $('t52_dtaqu').value == '' ) {
    campos += 'Data da Aquisição - ';
  }
  if ( $('t52_depart').value == '' ) {
    campos += 'Departamento - ';
  }
  if ( $('t56_situac').value == '' ) {
    campos += 'Situação - ';
  }
  if ( $('qtd').value == '' ) {
    campos += 'Quantidade - ';
  }
  if ( $('t42_descr').value == '' ) {
    campos += 'Descrição do Lote - ';
  }

  if ( campos != "" ) {
    alert ("Os seguintes campos não foram preenchidos corretamente: "+campos);
    return false;
  }
  if ( $('qtd').value < 2 ) {
    alert ("Para cadastrar um único bem, utilize o Cadastro de Bem Individual.");
    return false;
  }

  /**
   * Verifica que tipo de bem será cadastrado - Imóvel e Material
   */
  if ( $('tabelaImovel').style.display == 'block' ) {

    if ( $('t54_idbql').value == '' ) {
      alert('Preencha corretamente os dados do Imóvel!');
      return false;
    }
  } else if ( $('tabelaMaterial').style.display == 'block') {

    if ( $('t53_ntfisc').value == '' ) {
      alert ("Preenche o campo Nota Fiscal!");
      $('t53_ntfisc').focus();
      return false;
    }
    if ( $('emp_sistema').value == 's' ) {

      if ( $('t53_empen').value == '' ) {

        alert ("Informe o número do empenho!");
        $('t53_empen').focus();
        return false;
      }
    }
    if ( $('t53_garant') == '' ) {
      alert ("Informe a data de garantia");
      return false;
    }

  } else {

    alert('Preenchimendo dos dados do Imóvel ou Material obrigatório!');
    return false;
  }

  return true;
}


function js_pesquisa_texto(){
  js_OpenJanelaIframe('top.corpo','db_iframe_bensplaca','func_bensplacatext.php?funcao_js=parent.js_mostratext|t41_placa','Pesquisa',true);
}
function js_mostratext(placa){
  db_iframe_bensplaca.hide();
  js_buscplaca(placa);
}
function js_retplaca(placa,seq){
  //alert(placa);
  //alert(seq);
  <?if($t07_confplaca==2){?>
    document.form1.t52_ident.value = placa+seq;
  <?}else if ($t07_confplaca==3){?>
    document.form1.t52_ident.value = placa;
    document.form1.t52_ident_seq.value = seq;
  <?}?>
}
function js_buscplaca(classif){
      js_OpenJanelaIframe('top.corpo','db_iframe_bp','pat1_retseqplaca.php?classif='+classif,'',false);
}
function js_isnumber(campo,nome){
  campo = campo.replace(".",",");
  campo1=new Number(campo);
  if(isNaN(campo1)){
    alert("Campo "+nome+" deve ser preenchido somente com valores inteiros.");
    eval("document.form1."+nome+".focus()");
  }
}

function js_pesquisat64_class(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_clabens','func_clabens.php?funcao_js=parent.js_mostraclabens1|t64_class|t64_descr&analitica=true','Pesquisa',true,'20','1','775','390');
  }else{
     testa = new String(document.form1.t64_class.value);
     if(testa != '' && testa != 0){
       i = 0;
       for(i = 0;i < document.form1.t64_class.value.length;i++){
         testa = testa.replace('.','');
       }
       js_OpenJanelaIframe('top.corpo','db_iframe_clabens','func_clabens.php?pesquisa_chave='+testa+'&funcao_js=parent.js_mostraclabens&analitica=true','Pesquisa',false,'20','1','775','390');
     }else{
      <?if ($t07_confplaca==2&&$db_opcao==1){?>
    document.form1.t52_ident.value ="";
  <?}?>
       document.form1.t64_descr.value = '';
     }
  }
}
function js_mostraclabens(chave,erro){
  document.form1.t64_descr.value = chave;
  if(erro==true){
    document.form1.t64_class.value = '';
    document.form1.t64_class.focus();
      <?if ($t07_confplaca==2&&$db_opcao==1){?>
    document.form1.t52_ident.value ="";
  <?}?>
  }else{
    <?if ($t07_confplaca==2&&$db_opcao==1){?>
  js_buscplaca(document.form1.t64_class.value);
  <?}?>
  }
}

function js_mostraclabens1(chave1,chave2){

  document.form1.t64_class.value = chave1;
  document.form1.t64_descr.value = chave2;
  db_iframe_clabens.hide();
}

function js_pesquisat52_codmat(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pcmater','func_pcmater.php?funcao_js=parent.js_mostrapcmater1|pc01_codmater|pc01_descrmater<?=$db_opcao==1?"&opcao_bloq=3&opcao=f":"&opcao_bloq=1&opcao=i"?>','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.t52_codmat.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_pcmater','func_pcmater.php?pesquisa_chave='+document.form1.t52_codmat.value+'&funcao_js=parent.js_mostrapcmater<?=$db_opcao==1?"&opcao_bloq=3&opcao=f":"&opcao_bloq=1&opcao=i"?>','Pesquisa',false,'0','1','775','390');
     }else{
       document.form1.pc01_descrmater.value = '';
     }
  }
}
function js_mostrapcmater(chave,erro){
  document.form1.pc01_descrmater.value = chave;
  if(erro==true){
    document.form1.t52_codmat.focus();
    document.form1.t52_codmat.value = '';
  }
}
function js_mostrapcmater1(chave1,chave2){
  document.form1.t52_codmat.value = chave1;
  document.form1.pc01_descrmater.value = chave2;
  db_iframe_pcmater.hide();
}
function js_pesquisat52_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_forne','func_nome.php?funcao_js=parent.js_mostraforne1|z01_numcgm|z01_nome','Pesquisa',true,'20','1','775','390');
  }else{
     if(document.form1.t52_numcgm.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_forne','func_nome.php?pesquisa_chave='+document.form1.t52_numcgm.value+'&funcao_js=parent.js_mostraforne','Pesquisa',false,'0','1','775','390');
     }else{
       document.form1.z01_nome.value = '';
     }
  }
}
function js_mostraforne(erro,chave){
  document.form1.z01_nome.value = chave;
  if(erro==true){
    document.form1.t52_numcgm.focus();
    document.form1.t52_numcgm.value = '';
  }
}
function js_mostraforne1(chave1,chave2){
  document.form1.t52_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_forne.hide();
}
function js_pesquisat56_situac(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_situabens','func_situabens.php?funcao_js=parent.js_mostrasituabens1|t70_situac|t70_descr','Pesquisa',true,'20','1','775','390');
  }else{
     if(document.form1.t56_situac.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_situabens','func_situabens.php?pesquisa_chave='+document.form1.t56_situac.value+'&funcao_js=parent.js_mostrasituabens','Pesquisa',false,'10','1','775','390');
     }else{
       document.form1.t70_descr.value = '';
     }
  }
}
function js_mostrasituabens(chave,erro){
  document.form1.t70_descr.value = chave;
  if(erro==true){
    document.form1.t56_situac.focus();
    document.form1.t56_situac.value = '';
  }
}
function js_mostrasituabens1(chave1,chave2){
  document.form1.t56_situac.value = chave1;
  document.form1.t70_descr.value = chave2;
  db_iframe_situabens.hide();
}
function js_pesquisat52_depart(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_depart','func_db_depart.php?funcao_js=parent.js_mostradb_depart1|coddepto|descrdepto','Pesquisa',true);
  }else{
     if(document.form1.t52_depart.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_depart','func_db_depart.php?pesquisa_chave='+document.form1.t52_depart.value+'&funcao_js=parent.js_mostradb_depart','Pesquisa',false);
     }else{
       document.form1.descrdepto.value = '';
     }
  }
}
function js_mostradb_depart(chave,erro){
  document.form1.descrdepto.value = chave;
  if(erro==true){
    document.form1.t52_depart.focus();
    document.form1.t52_depart.value = '';
  }else{
    document.form1.submit();
  }
}
function js_mostradb_depart1(chave1,chave2){
  document.form1.t52_depart.value = chave1;
  document.form1.descrdepto.value = chave2;
  db_iframe_depart.hide();
  //document.form1.submit();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_benslote','func_benslotealt.php?funcao_js=parent.js_preenchepesquisa|t42_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_benslote.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
<?
if(isset($chavepesquisa)){
      echo "\njs_mascara03_t64_class(document.form1.t64_class.value);\n";
}
?>

function js_pesquisat54_idbql(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_lote','func_lote.php?funcao_js=parent.js_mostralote1|j34_idbql','Pesquisa',true);
  }else{
     if(document.form1.t54_idbql.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_lote','func_lote.php?pesquisa_chave='+document.form1.t54_idbql.value+'&funcao_js=parent.js_mostralote','Pesquisa',false);
     }
  }
}
function js_mostralote(chave,erro){
  if(erro==true){
    document.form1.t54_idbql.focus();
    document.form1.t54_idbql.value = '';
  }
}
function js_mostralote1(chave1){
  document.form1.t54_idbql.value = chave1;
  db_iframe_lote.hide();
}

function js_pesquisat53_empen(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_empempenho','func_empempenho.php?funcao_js=parent.js_mostraempempenho1|e60_numemp|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.t53_empen.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_empempenho','func_empempenho.php?pesquisa_chave='+document.form1.t53_empen.value+'&funcao_js=parent.js_mostraempempenho','Pesquisa',false);
     }else{
       document.form1.t53_empen.value = '';
     }
  }
}

function js_mostraempempenho(chave,erro){

  document.form1.z01_nome_empenho.value = chave;
  if(erro==true){
    document.form1.t53_empen.focus();
    document.form1.t53_empen.value = '';
  }
}
function js_mostraempempenho1(chave1,chave2){
  document.form1.t53_empen.value = chave1;
  document.form1.z01_nome_empenho.value = chave2;
  db_iframe_empempenho.hide();
}

</script>