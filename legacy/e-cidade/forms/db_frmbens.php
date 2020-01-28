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
$clbens->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("t42_descr");
$clrotulo->label("t52_descr");
$clrotulo->label("t64_descr");
$clrotulo->label("t64_class");
$clrotulo->label("t64_codcla");
$clrotulo->label("pc01_descrmater");
$clrotulo->label("z01_nome");
$clrotulo->label("descrdepto");
$clrotulo->label("t30_descr");
$clrotulo->label("t33_divisao");
$clrotulo->label("o40_descr");
$clrotulo->label("o41_descr");
$clrotulo->label("t04_sequencial");
$clrotulo->label("z01_nome_convenio");
$clrotulo->label("t52_ident");

     if($db_opcao==1){
 	   $db_action="pat1_bens004.php";
 	   $sLegendaFieldset = "Incluir";
      }else if($db_opcao==2||$db_opcao==22){
 	   $db_action="pat1_bens005.php";
 	   $sLegendaFieldset = "Alteração";
      }else if($db_opcao==3||$db_opcao==33){
 	   $db_action="pat1_bens006.php";
 	   $sLegendaFieldset = "Exclusão";
      }
$res_cfpatriplaca = $clcfpatriplaca->sql_record($clcfpatriplaca->sql_query_file(db_getsession("DB_instit")));
$result           = $clcfpatri->sql_record($clcfpatri->sql_query_file());
$msg_erro         = "";

if($clcfpatri->numrows==0){
  $msg_erro   = "Estrutural não cadastrado no parâmetro.";
  $t06_codcla = 0;
}else{
  db_fieldsmemory($result,0);
}

if ($clcfpatriplaca->numrows==0){
     $msg_erro      = "Parâmetros de Placa não cadastrados.";
     $t07_confplaca = 0;
} else {
     db_fieldsmemory($res_cfpatriplaca,0);

    if (!BensParametroPlaca::controlaPlacaPorInstituicao()) {
      $iParametroPlaca = BensParametroPlaca::buscaSequencial();
      $t07_sequencial  = $iParametroPlaca;
    }
}

if (isset($importar)&&trim($importar)!=""){
     if (!isset($t52_depart)){
          $campos = "bens.t52_codcla,
                     bens.t52_numcgm,
                     bens.t52_valaqu,
                     bens.t52_dtaqu,
		                 bens.t52_descr,
		                 bens.t52_obs,
		                 bens.t52_depart,
		                 clabens.t64_class,
		                 clabens.t64_descr,
		                 cgm.z01_nome,
		                 db_depart.descrdepto,
		                 histbem.t56_situac,
		                 situabens.t70_descr,
		                 bensdiv.t33_divisao";

          $result = $clbens->sql_record($clbens->sql_query($codbem,$campos,"t52_bem",null));

          if ($clbens->numrows > 0){
               db_fieldsmemory($result,0);
	             $result_lote = $clbenslote->sql_record($clbenslote->sql_query(null,"t42_descr","t43_bem","t43_bem = $codbem"));
	             if ($clbenslote->numrows > 0){
	                  db_fieldsmemory($result_lote,0);
	             }
          }
     }
}

$opc = $db_opcao;

$oDataAtual   = new DBDate(date("d/m/Y", db_getsession("DB_datausu")));
$oInstituicao = new Instituicao(db_getsession("DB_instit"));

$lPossuiIntegracaoPatrimonial = ParametroIntegracaoPatrimonial::possuiIntegracaoPatrimonio($oDataAtual, $oInstituicao);

?>
<form name="form1" method="post" action="<?=$db_action?>">
<center>
<fieldset>
<legend><b><?=$sLegendaFieldset; ?> Bem Individual</b></legend>

<table border="0" align="center">
  <tr>
    <td title="<?=$Tt52_bem?>"><?=$Lt52_bem?></td>
    <td>
    <?
      db_input('t52_bem',8,$It52_bem,true,"text",3,"");
    ?>
    </td>
  <tr>
    <td>
<?$fj="";

$iOpcaoPlaca  = 1;

if ($t07_confplaca==1||$t07_confplaca==2||$db_opcao!=1){
     if (trim(@$t52_ident) == ""){
          $opc = $db_opcao;
     } else {
          if ($t07_confplaca==4){
               $opc = $db_opcao;
          } else {
            	 $opc = 3;
          }
     }
}
  if ($t07_confplaca == 1 && ($db_opcao==1||$db_opcao==11)) {
    $t52_ident    = $t07_sequencial;
  }

  if ($t07_confplaca == 1) {
    $iOpcaoPlaca  = 3;
  }

  if ($t07_confplaca==3) {
    ?>

	<?db_ancora(@$Lt52_ident,"js_pesquisa_texto(true);",$opc); ?>

<?$fj=" ";
  }else{
    ?>
<?=@$Lt52_ident?></td><td>
<?}?>
       <?
db_input('t52_ident',20,$It52_ident,true,'text',$iOpcaoPlaca, " onchange='js_buscplaca(document.form1.t52_ident.value);'");
if ($t07_confplaca==3&&($db_opcao==1||$db_opcao==11)){
     db_input('t52_ident_seq',8,"",true,'text',3,"");
}
       ?>
    </td>
  </tr>
<?

if ($db_opcao == 2 || $db_opcao == 22){
//  $t52_ident_atual = $t52_ident;
  db_input("t52_ident_atual",20,0,true,"hidden",3,"");
}

$t52_instit = db_getsession("DB_instit");
db_input("t52_instit",10,$It52_instit,true,"hidden",3,"");
db_input('db_opcao',8,"",true,'hidden',3,"");
db_input('chavepesquisa',8,"",true,'hidden',3,"");
db_input("importar",4,"",true,"hidden",3,"");
db_input("codbem",8,"",true,"hidden",3,"");
db_input("tipo_inclui",10,"",true,"hidden",3,"");
?>
  <tr>
    <td nowrap title="<?=@$Tt52_descr?>">
       <?=@$Lt52_descr?>
    </td>
   <td>
<?
if (isset($t52_descr)) {
  $t52_descr = htmlspecialchars($t52_descr);
}

db_input('t52_descr',51,$It52_descr,true,'text',$db_opcao,$fj)
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
    $cldb_estrut->size    = 8;
    $cldb_estrut->funcao_onchange ='js_pesquisat64_class(false);';
    $cldb_estrut->nome    = "t64_class";
    $cldb_estrut->db_opcao= (($db_opcao == 2 && $lPossuiIntegracaoPatrimonial) ? 3 : $db_opcao);
    if(isset($t06_codcla) && $t06_codcla!=""){
      $cldb_estrut->db_mascara(@$t06_codcla);
    }else{
      db_msgbox($msg_erro);
    }
   ?>
       <?
        db_input('t64_descr',40,$It64_descr,true,'text',3,'')
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
			db_input('t52_numcgm',8,$It52_numcgm,true,'text',$db_opcao," onchange='js_pesquisat52_numcgm(false);'")
			?>
			       <?
			db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
			       ?>
    </td>
  </tr>
  <tr>
  	<td><b>Medida:</b></td>
  	<td>
  		<?php
  		$rsBensMedida = $clbensmedida->sql_record($clbensmedida->sql_query());
  		db_selectrecord('t67_sequencial',$rsBensMedida,'true',$db_opcao);
  		?>
  	</td>
  </tr>
  <tr>
  	<td><b>Modelo:</b></td>
  	<td>
  		<?php
  		$rsBensModelo = $clbensmodelo->sql_record($clbensmodelo->sql_query());
  		db_selectrecord('t66_sequencial',$rsBensModelo,'true',$db_opcao);
  		?>
  	</td>
  </tr>
  <tr>
  	<td><b>Marca:</b></td>
  	<td>
  		<?php
  		$rsBensMarca = $clbensmarca->sql_record($clbensmarca->sql_query());
  		db_selectrecord('t65_sequencial',$rsBensMarca,'true',$db_opcao);
  		?>
  	</td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tt52_valaqu?>">
       <?=@$Lt52_valaqu?>
    </td>
    <td>
<?
db_input('t52_valaqu',15,$It52_valaqu,true,'text',$db_opcao,"")
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
  	if(isset($t52_bem)){

	  	$resPesquisaOrgaoUnidade	= $clbens->sql_record($clbens->sql_query_orgao(null,'o40_descr,o41_descr',null,"db01_coddepto = $t52_depart"));
	  	//die($clbens->sql_query_file($t52_bem,'o40_descr,o41_descr'));
			if($clbens->numrows > 0) {

				db_fieldsmemory($resPesquisaOrgaoUnidade,0);
			}
  	}

  ?>
  <tr>
  	<td><b>Órgão:</b></td>
  	<td><? db_input('o40_descr',50,$Io40_descr,3,'text',3); ?></td>
  </tr>
  <tr>
  	<td><b>Unidade:</b></td>
  	<td><? db_input('o41_descr',50,$Io41_descr,3,'text',3); ?></td>
  </tr>
  <?php
  }
  ?>
   <td nowrap title="<?=@$Tt52_depart?>">
       <?
       db_ancora(@$Lt52_depart,"js_pesquisat52_depart(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('t52_depart',8,$It52_depart,true,'text',$db_opcao," onchange='js_pesquisat52_depart(false);'")
?>
       <?
db_input('descrdepto',40,$Idescrdepto,true,'text',3,'')
       ?>
    </td>
  </tr>
  <?if (isset($t52_depart)&&$t52_depart!=""){?>
  <tr>
    <td nowrap title="Divisão do Depart.">
      <b> Divisão:</b>
    </td>
    <?
      if($db_opcao != 3) {
    ?>
      <td>
      <select name='t33_divisao'>
      <option value=''>Nenhuma</option>
      <?
        $sCamposDepartDivisao = "t30_codigo,t30_descr";
        $sWhereDepartDivisao  = "t30_ativo = true and t30_depto=$t52_depart";
        $sSqlDepartDivisao = $cldepartdiv->sql_query_file(null,$sCamposDepartDivisao ,null,$sWhereDepartDivisao);
        $rsDepartDivisao   = $cldepartdiv->sql_record($sSqlDepartDivisao);

        for ( $y = 0 ; $y < $cldepartdiv->numrows ; $y++ ) {

          db_fieldsmemory($rsDepartDivisao, $y);
          if (isset($t33_divisao) && $t33_divisao == $t30_codigo) {
            echo "<option value='{$t30_codigo}' selected>{$t30_descr}</option>";
          } else {
            echo "<option value='{$t30_codigo}'>{$t30_descr}</option>";
          }
 	      }
 	    ?>
      </select>
    </td>
  <?
    } else {
  ?>
  	<td>
      <?
        db_input('t33_divisao',8,$It33_divisao,true,'text',$db_opcao,"");
        db_input('t30_descr',40,$It30_descr,true,'text',$db_opcao,"");
      ?>
    </td>
  <?
    }
  ?>
  </tr>
  	<?  }?>
  	<tr>
    <td nowrap title="<?="Convênio"?>">
       <?
       db_ancora("<b>Convênio:</b>","js_pesquisat04_sequencial(true);",$db_opcao);
       ?>
    </td>
    <td>
			<?
			if(isset($t52_bem)){
				$result_benscedente = $clbenscedente->sql_record($clbenscedente->sql_query(null,"t09_benscadcedente,a.z01_nome as z01_nome_convenio",null,"t09_bem = $t52_bem"));

	  		  if ($clbenscedente->numrows>0) {
    	      	db_fieldsmemory($result_benscedente,0);
	         	$t04_sequencial=$t09_benscadcedente;
	  	      }else{
				$t04_sequencial = "";
	    	  }
			} else {
  			  $t04_sequencial = "";
			}
			db_input('t04_sequencial',8,$It04_sequencial,true,'text',$db_opcao," onchange='js_pesquisat04_sequencial(false);'")
			?>
	    <?
			db_input('z01_nome_convenio',40,'',true,'text',3,'')
	    ?>
    </td>
  </tr>

  <tr>
<?
if($db_opcao == 1 || (isset($tipo_inclui)&&trim($tipo_inclui)!="")){
?>
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
    }
  ?>
    </td>
  </tr>
<tr>
    <td nowrap title="Placa de Identificação:">
        <b>Placa de Identificação:</b>
    </td>
    <td>
       <?
        if($db_opcao == 1 ){
          $placaidentificacao = "Não";
        }else if($db_opcao > 1){
        	$placaidentificacao = "Não";
        	$sQueryPlaca  = " select t73_sequencial ";
        	$sQueryPlaca .= "   from bensplacaimpressa ";
        	$sQueryPlaca .= "        inner join bensplaca on bensplaca.t41_codigo = t73_bensplaca ";
          $sQueryPlaca .= "  where t41_bem = $t52_bem";

          $rsQueryPlaca = db_query($sQueryPlaca);

          if(pg_num_rows($rsQueryPlaca) > 0){
          	$placaidentificacao = "Sim";
          }

        }

        db_input('placaidentificacao',10,0,true,'text',3,'');
       ?>
    </td>
  </tr>
  </table>

</fieldset>
<br />
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> <?=((isset($tipo_inclui)&&$tipo_inclui=="true"))?"onclick='return js_pesquisa_bens();'":""?>>
<!--"-->
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" <?=($msg_erro==""?"":"disabled")?> >
<?if($db_opcao==2){?>
	<input name="novo" type="button" id="novo" value="Novo" onclick="parent.location.href='pat1_bens001.php';" <?=($msg_erro==""?"":"disabled")?> >
<?}

  if($db_opcao==1||$db_opcao==11){
?>
<input name="importar" type="button" id="importar" value="Importação" onClick="js_importacao();">
<?
  }
?>
</center>
</form>
<script>
function js_importacao(){
<?
   if(isset($tipo_inclui)&&$tipo_inclui==true){
       $funcao = "func_benslotealt.php";
   } else {
       $funcao = "func_bens.php";
   }
?>
  js_OpenJanelaIframe('top.corpo.iframe_bens','db_iframe_bens','<?=$funcao?>?funcao_js=parent.js_mostrarbem|t52_bem&opcao=todos','Pesquisa',true);
}
function js_mostrarbem(chave){
  db_iframe_bens.hide();
<?
    if(isset($tipo_inclui)&&$tipo_inclui==true) {
        $global = "&tipo_inclui=$tipo_inclui";
    } else {
        $global = "";
    }
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?codbem='+chave+'&importar=true<?=$global?>'";
?>
}
function js_pesquisa_texto(){
	js_OpenJanelaIframe('top.corpo.iframe_bens','db_iframe_bensplaca','func_bensplacatext.php?funcao_js=parent.js_mostratext|t41_placa','Pesquisa',true);
}
function js_mostratext(placa){
	db_iframe_bensplaca.hide();
	js_buscplaca(placa);
}
function js_retplaca(placa,seq){
	//alert(placa);
	//alert(seq);
	<?if ($t07_confplaca==2){?>
		document.form1.t52_ident.value = placa+seq;
	<?}else if ($t07_confplaca==3){?>
		document.form1.t52_ident.value = placa;
		document.form1.t52_ident_seq.value = seq;
	<?}?>
}
function js_buscplaca(classif){
	    js_OpenJanelaIframe('top.corpo.iframe_bens','db_iframe_bp','pat1_retseqplaca.php?classif='+classif,'',false);
}
function js_isnumber(campo,nome){
  campo = campo.replace(".",",");
  campo1=new Number(campo);
  if(isNaN(campo1)){
    alert("Campo "+nome+" deve ser preenchido somente com valores inteiros.");
    eval("document.form1."+nome+".focus()");
  }
}
function js_pesquisa_bens(){

  x     = document.form1;
  erro  = 0;
  for(i = 0;i < x.length;i++){
    if(x.elements[i].type == "text"){
      nome=x.elements[i].name;
      if((nome.search("nome")==-1 && nome.search("descr")==-1 || nome=="t52_descr") && nome != "t52_ident" && nome != "t52_bem" && nome != "t04_sequencial"){
         x.elements[i].style.backgroundColor='';
	if(x.elements[i].value == ""){
	  erro++;
	  break;
	}
      }
    }
  }
  if(erro!=0){
    alert("Usuário: \n\n Campo: "+nome+" não informado\n\n Administrador.");
    eval("document.form1."+nome+".style.backgroundColor='#99A9AE'");
    eval("document.form1."+nome+".focus()");
    return false;
  }
  //verificar se foram inseridos valores nos forms bensmater ou bensimoveis
  document.form1.dadimovlot.value="";
  document.form1.dadimovobs.value="";
  document.form1.dadmat.value="";
  erro_abasimov=0;
  erro_abasmat=0;
  if(top.corpo.iframe_bensimoveis.document.form1.t54_idbql.value==""){
    erro_abasimov++;
  }
  x = top.corpo.iframe_bensmater.document.form1;
  for(i = 0;i < x.length;i++){
    if(x.elements[i].type == "text"){
      nome=x.elements[i].name;
      if(nome != "t53_codbem" && nome != "z01_nome"){
	if(x.elements[i].value == "" ){
	  erro_abasmat++;
	}
      }
    }
  }

  if(erro_abasimov==0 && erro_abasmat==0){
    alert("Erro, bem não pode ser cadastrado como material e imóvel. \n\n Escolha apenas uma das abas para preencher.");
    return false;
  }else if(erro_abasimov!=0 && erro_abasmat!=0){
    if (!confirm("Não há informação, ou as informações estão incompletas nas abas Dados do Imóvel e Dados do Material. \n Confirma processo?")){
      return false;
    }
  }else{
    if(erro_abasmat!=0){
      document.form1.dadimovlot.value = top.corpo.iframe_bensimoveis.document.form1.t54_idbql.value;
      document.form1.dadimovobs.value = top.corpo.iframe_bensimoveis.document.form1.t54_obs.value;
    }else if(erro_abasimov!=0){
      t53_garant_dia = top.corpo.iframe_bensmater.document.form1.t53_garant_dia.value;
      t53_garant_mes = top.corpo.iframe_bensmater.document.form1.t53_garant_mes.value;
      t53_garant_ano = top.corpo.iframe_bensmater.document.form1.t53_garant_ano.value;
      t53_garant = t53_garant_ano+'-'+t53_garant_mes+'-'+t53_garant_dia;
      document.form1.dadmat.value = top.corpo.iframe_bensmater.document.form1.t53_ntfisc.value;
      document.form1.dadmat.value += ","+top.corpo.iframe_bensmater.document.form1.t53_empen.value;
      document.form1.dadmat.value += ","+top.corpo.iframe_bensmater.document.form1.t53_ordem.value;
      document.form1.dadmat.value += ","+t53_garant;
    }
  }
}
function js_pesquisat64_class(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_bens','db_iframe_clabens','func_clabens.php?funcao_js=parent.js_mostraclabens1|t64_class|t64_descr&analitica=true','Pesquisa',true);
  }else{

     testa = new String(document.form1.t64_class.value);
     if(testa != '' && testa != 0){
       i = 0;
       for(i = 0;i < document.form1.t64_class.value.length;i++){
         testa = testa.replace('.','');
       }
       js_OpenJanelaIframe('top.corpo.iframe_bens','db_iframe_clabens','func_clabens.php?pesquisa_chave='+testa+'&lClass=true&funcao_js=parent.js_mostraclabens&analitica=true','Pesquisa',false);
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
  <?if ($t07_confplaca==2&&$db_opcao==1){?>
	js_buscplaca(document.form1.t64_class.value);
  <?}?>
  db_iframe_clabens.hide();
}
function js_pesquisat52_codmat(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_bens','db_iframe_pcmater','func_pcmater.php?funcao_js=parent.js_mostrapcmater1|pc01_codmater|pc01_descrmater<?=$db_opcao==1?"&opcao_bloq=3&opcao=f":"&opcao_bloq=1&opcao=i"?>','Pesquisa',true);
  }else{
     if(document.form1.t52_codmat.value != ''){
        js_OpenJanelaIframe('top.corpo.iframe_bens','db_iframe_pcmater','func_pcmater.php?pesquisa_chave='+document.form1.t52_codmat.value+'&funcao_js=parent.js_mostrapcmater<?=$db_opcao==1?"&opcao_bloq=3&opcao=f":"&opcao_bloq=1&opcao=i"?>','Pesquisa',false);
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
    js_OpenJanelaIframe('top.corpo.iframe_bens','db_iframe_forne','func_nome.php?funcao_js=parent.js_mostraforne1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.t52_numcgm.value != ''){
        js_OpenJanelaIframe('top.corpo.iframe_bens','db_iframe_forne','func_nome.php?pesquisa_chave='+document.form1.t52_numcgm.value+'&funcao_js=parent.js_mostraforne','Pesquisa',false);
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
    js_OpenJanelaIframe('top.corpo.iframe_bens','db_iframe_situabens','func_situabens.php?funcao_js=parent.js_mostrasituabens1|t70_situac|t70_descr','Pesquisa',true);
  }else{
     if(document.form1.t56_situac.value != ''){
        js_OpenJanelaIframe('top.corpo.iframe_bens','db_iframe_situabens','func_situabens.php?pesquisa_chave='+document.form1.t56_situac.value+'&funcao_js=parent.js_mostrasituabens','Pesquisa',false);
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
    js_OpenJanelaIframe('top.corpo.iframe_bens','db_iframe_db_depart','func_db_depart.php?funcao_js=parent.js_mostradb_depart1|coddepto|descrdepto','Pesquisa',true);
  }else{
     if(document.form1.t52_depart.value != ''){
        js_OpenJanelaIframe('top.corpo.iframe_bens','db_iframe_db_depart','func_db_depart.php?pesquisa_chave='+document.form1.t52_depart.value+'&funcao_js=parent.js_mostradb_depart','Pesquisa',false);
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
  db_iframe_db_depart.hide();
  document.form1.submit();
}
function js_pesquisa(){
  <?
     if(isset($tipo_inclui)&&$tipo_inclui==true){
         $url = "func_benslotealt.php?funcao_js=parent.js_preenchepesquisa|t42_codigo";
     } else {
         $url = "func_bens.php?funcao_js=parent.js_preenchepesquisa|t52_bem&opcao=todos";
     }
  ?>
  js_OpenJanelaIframe('top.corpo.iframe_bens','db_iframe_bens','<?=$url?>','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_bens.hide();
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

if (isset($importar)&&trim($importar)!=""){
  echo "\njs_pesquisat64_class(false);\n";
}

?>
function js_pesquisat04_sequencial(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_bens','db_iframe_benscadcedente','func_benscadcedente.php?funcao_js=parent.js_mostraconvenio1|t04_sequencial|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.t04_sequencial.value != ''){
        js_OpenJanelaIframe('top.corpo.iframe_bens','db_iframe_benscadcedente','func_benscadcedente.php?pesquisa_chave='+document.form1.t04_sequencial.value+'&funcao_js=parent.js_mostraconvenio','Pesquisa',false);
     }else{
       document.form1.z01_nome_convenio.value = '';
     }
  }
}
function js_mostraconvenio(chave,erro){
	//alert(chave);
	//document.getElementById('z01_nome').value = 'teste';
  document.form1.z01_nome_convenio.value = chave;
  if(erro==true){
    document.form1.t04_sequencial.focus();
    document.form1.t04_sequencial.value = '';
  }
}
function js_mostraconvenio1(chave1,chave2){
	document.form1.t04_sequencial.value = chave1;
  document.form1.z01_nome_convenio.value = chave2;
  db_iframe_benscadcedente.hide();
}
</script>