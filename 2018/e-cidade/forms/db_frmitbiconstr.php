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

//MODULO: itbI
$clitbiconstr->rotulo->label();
$clrotulo = new rotulocampo;

$clrotulo->label("it01_guia");
$clrotulo->label("it09_codigo");
$clrotulo->label("it09_caract");
$clrotulo->label("it10_codigo");
$clrotulo->label("it10_caract");
$clrotulo->label("it01_guia");
$clrotulo->label("it34_caract");

if(isset($opcao) && $opcao == "alterar"){
  echo "<script>parent.iframe_constr.location.href='itb1_itbiconstr002.php?chavepesquisa=$it08_codigo&it08_guia=$it08_guia&tipo=$tipo'</script>";
}

if(isset($opcao) && $opcao == "excluir"){
  echo "<script>parent.iframe_constr.location.href='itb1_itbiconstr003.php?chavepesquisa=$it08_codigo&it08_guia=$it08_guia&tipo=$tipo'</script>";
}

if ( isset($tipo) && $tipo == "urbano" ) {
  $sCampoTipo = "it24_grupotipobenfurbana";
  $sCampoEsp  = "it24_grupoespbenfurbana";
} else {
  $sCampoTipo = "it24_grupotipobenfrural";
  $sCampoEsp  = "it24_grupoespbenfrural";
}

?>
<form name="form1" method="post" action="">
  <center>
    <fieldset>
    <legend>
      <b>Benfeitorias</b>
    </legend>
    <table border="0">
	  <tr>
	    <td nowrap title="<?php echo $Tit10_caract; ?>">
	       <b>Tipo:</b>
	    </td>
	    <td>
	      <?php

	        db_input('tipo',        40, "",            true, 'hidden', 3);
		 	    db_input('it08_guia',   4,  $Iit08_guia,   true, 'hidden', 3);
			    db_input('it08_codigo', 10, $Iit08_codigo, true, 'hidden', 3, "");

			    $rsParamTipo  = $clparitbi->sql_record($clparitbi->sql_query(db_getsession('DB_anousu'),$sCampoTipo));

			    if($clparitbi->numrows > 0){

			      $oParamTipo     = db_utils::fieldsMemory($rsParamTipo, 0);
			      $rsCaracterTipo = $clcaracter->sql_record($clcaracter->sql_query(null, "j31_codigo, j31_descr", null, " j32_grupo = {$oParamTipo->$sCampoTipo}"));
 	          db_selectrecord("it10_codigo",
                            $rsCaracterTipo,
                            true,
                            $db_opcao,
                            "style='width:270px;'",
                            "it10_codigo", "", array('', 'Selecione'), "", 1);
			    }
	      ?>
	    </td>
	    <td nowrap title="<?php echo $Tit09_caract; ?>">
	     <b>Espécie:</b>
	    </td>
	    <td colspan="3">
	      <?php

	        $rsParamEspec = $clparitbi->sql_record($clparitbi->sql_query(db_getsession('DB_anousu'), $sCampoEsp));

			    if($clparitbi->numrows > 0){

			      $oParamEspec     = db_utils::fieldsMemory($rsParamEspec, 0);
			      $rsCaracterEspec = $clcaracter->sql_record($clcaracter->sql_query(null, "j31_codigo, j31_descr", null, " j32_grupo = {$oParamEspec->$sCampoEsp}"));
 	          db_selectrecord("it09_codigo",
                            $rsCaracterEspec,
                            true,
                            $db_opcao,
                            "style='width:270px;'",
                            "it09_codigo", "", array('', 'Selecione'), "", 1);
			    }
	      ?>
	    </td>
	  </tr>
    <tr>
      <td nowrap><label class="bold" for="it34_codigo" id="lbl_it34_codigo">Padrão Construtivo:</label></td>
      <td colspan="5">
        <?php

          $sSql                     = $clparitbi->sql_query(db_getsession('DB_anousu'), 'it24_grupopadraoconstrutivobenurbana');
          $rsParamPadraoConstrutivo = $clparitbi->sql_record($sSql);

          if($clparitbi->numrows > 0){

            $oParamPadraoConstrutivo     = db_utils::fieldsMemory( $rsParamPadraoConstrutivo, 0 );

            if(!empty($oParamPadraoConstrutivo->it24_grupopadraoconstrutivobenurbana)){

              $sSql = $clcaracter->sql_query(null,
                                             "j31_codigo, j31_descr",
                                             null,
                                             " j32_grupo = {$oParamPadraoConstrutivo->it24_grupopadraoconstrutivobenurbana}");

              $rsCaracterPadraoConstrutivo = $clcaracter->sql_record($sSql);
              db_selectrecord("it34_codigo",
                              $rsCaracterPadraoConstrutivo,
                              true,
                              $db_opcao,
                              "style='width:270px;'", "it34_codigo", "", array('', 'Selecione'), "", 1);
            }
          }
        ?>
      </td>
    </tr>
	  <tr>
	    <td>
          <?php echo $Lit08_area; ?>
        </td>
        <td>
    		  <?php
            db_input('it08_area',35,$Iit08_area,true,'text',$db_opcao," onChange='document.form1.it08_areatrans.value = this.value;document.form1.it08_ano.focus()'");
    		  ?>
  	    </td>
  	    <td>
  		    <?php echo $Lit08_areatrans; ?>
  	    </td>
  	    <td>
    		  <?php
            db_input('it08_areatrans',15,$Iit08_areatrans,true,'text',$db_opcao,"");
    		  ?>
  	    </td>
  	    <td>
  		    <b>Ano:</b>
  	    </td>
  	    <td>
    		  <?php
    		    db_input('it08_ano',4,$Iit08_ano,true,'text',$db_opcao,"onChange='js_validaAno(this);'");
    		  ?>
      	</td>
  	  </tr>
    	  <?php if(!empty($oGet->tipo) and $oGet->tipo == "rural"){ ?>
        <tr>
        <td nowrap title="<?php echo $Tit08_coordenadas; ?>">
          <?php echo $Lit08_coordenadas; ?>
        </td>
        <td colspan="5">
         <?php
           db_input('it08_coordenadas', 87,$Iit08_coordenadas,true,'text',$db_opcao,"");
         ?>
       </td>
     </tr>
     <?php } ?>
    <tr>
    	<td nowrap title="<?php echo $Tit08_obs; ?>">
         <?php echo $Lit08_obs; ?>
    	</td>
    	<td colspan="5">
		    <?php
		      db_input('it08_obs', 87,$Iit08_obs,true,'text',$db_opcao,"");
		    ?>
      </td>
    </tr>
  <tr>
    <td colspan="6" align="center">
      <input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
    </td>
  </tr>
  <tr>
    <td align="top" colspan="6">
	  <?php

      if(empty($it08_codigo)){
        $it08_codigo = null;
      }

	    $chavepri = array("it08_codigo" => $it08_codigo);
	    $cliframe_alterar_excluir->chavepri = $chavepri;
	    $cliframe_alterar_excluir->campos   = "it08_guia,it08_codigo,it08_ano,it08_area,it08_areatrans";

      if(empty($it08_guia) or !isset($it08_guia) or trim($it08_guia) == ''){
  			$it08_guia = 'NULL';
  		}

	    $cliframe_alterar_excluir->sql           = $clitbiconstr->sql_query("", "*", "", " it08_guia = $it08_guia");
	    $cliframe_alterar_excluir->legenda       = "Construções";
	    $cliframe_alterar_excluir->msg_vazio     = "<font size='1'>Nenhuma Construção Cadastrada!</font>";
	    $cliframe_alterar_excluir->textocabec    = "darkblue";
	    $cliframe_alterar_excluir->textocorpo    = "black";
	    $cliframe_alterar_excluir->fundocabec    = "#aacccc";
	    $cliframe_alterar_excluir->fundocorpo    = "#ccddcc";
	    $cliframe_alterar_excluir->iframe_height = "170";
	    $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
	  ?>
    </td>
  </tr>
  </table>
  </fieldset>
  </center>
</form>
<script>

function js_validaAno(obj){

  var iAno = new Number(obj.value);

  if ( iAno < 1800 ) {
    alert('Ano inválido!');
    obj.value = '';
  } else if ( iAno > <?=date('Y',db_getsession('DB_datausu'))?>) {
    alert("Ano não pode ser maior que <?=date('Y',db_getsession('DB_datausu'))?> !");
    obj.value = '';
  }

}

function js_pesquisait08_guia(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_itbi','func_itbi.php?funcao_js=parent.js_mostraitbi1|it01_guia|it01_guia','Pesquisa',true);
  }else{
     if(document.form1.it08_guia.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_itbi','func_itbi.php?pesquisa_chave='+document.form1.it08_guia.value+'&funcao_js=parent.js_mostraitbi','Pesquisa',false);
     }else{
       document.form1.it01_guia.value = '';
     }
  }
}
function js_mostraitbi(chave,erro){
  document.form1.it01_guia.value = chave;
  if(erro==true){
    document.form1.it08_guia.focus();
    document.form1.it08_guia.value = '';
  }
}
function js_mostraitbi1(chave1,chave2){
  document.form1.it08_guia.value = chave1;
  document.form1.it01_guia.value = chave2;
  db_iframe_itbi.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_itbiconstr','func_itbiconstr.php?funcao_js=parent.js_preenchepesquisa|it08_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_itbiconstr.hide();
  <?php
    if($db_opcao!=1){
      echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
    }
  ?>
}
</script>