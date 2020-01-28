<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: educa��o
$oDaoFormaAvaliacao->rotulo->label();
?>
<form name="form1" method="post" action="" onsubmit="return js_validaVariacao()">
<fieldset style="width:95%"><legend><b>Forma de Avalia��o</b></legend>
<center>
<table border="0" width="100%">
 <tr>
  <td valign="top" width="50%">
   <table border="0" width="95%">
    <tr>
     <td nowrap title="<?=@$Ted37_i_codigo?>">
      <?=@$Led37_i_codigo?>
     </td>
     <td>
      <?db_input('ed37_i_codigo',10,$Ied37_i_codigo,true,'text',3,"")?>
     </td>
    </tr>
    <tr>
     <td nowrap title="<?=@$Ted37_c_descr?>">
      <?=@$Led37_c_descr?>
     </td>
     <td>
      <?db_input('ed37_c_descr',30,$Ied37_c_descr,true,'text',$db_opcao,"")?>
     </td>
    </tr>
    <tr>
     <td nowrap title="<?=@$Ted37_c_tipo?>">
      <?=@$Led37_c_tipo?>
     </td>
     <td>
      <?
      $x = array(''=>'','NOTA'=>'NOTA','NIVEL'=>'NIVEL','PARECER'=>'PARECER');
      db_select('ed37_c_tipo',$x,true,$db_opcao1," onchange='js_tiporesultado(this.value)'");
      ?>
     </td>
    </tr>
    <tr>
     <td colspan="2">
      <span name="parecer" id="parecer" style="visibility:hidden;position:absolute;">
      <table border="0">
       <tr>
        <td nowrap title="<?=@$Ted37_c_parecerarmaz?>">
         <?=@$Led37_c_parecerarmaz?>
        </td>
        <td>
         <?
         $x = array(''=>'','S'=>'SIM','N'=>'N�O');
         db_select('ed37_c_parecerarmaz',$x,true,$db_opcao,"");
         ?>
        </td>
       </tr>
      </table>
      </span>
      <span name="conceito" id="conceito" style="visibility:hidden;position:absolute;">
       <table>
        <tr>
         <td>
          <?=@$Led37_c_minimoaprov?>
         </td>
         <td>
          <?
          $sCamposConceito = "ed39_c_conceito,ed37_c_minimoaprov";
          $sWhereConceito  = " ed39_i_formaavaliacao = ".@$ed39_i_formaavaliacao;
          $sSqlConceito    = $oDaoConceito->sql_query("",$sCamposConceito,"ed39_i_sequencia",$sWhereConceito);
          $rsConceito      = $oDaoConceito->sql_record($sSqlConceito);
          ?>
          <select name="ed37_c_minimoaprovconc" <?=$db_opcao==3?"disabled":""?>

                  <?=$oDaoConceito->numrows==0?"onclick=\"alert('Informar o m�nimo para aprova��o somente ap�s'+
                                                                ' cadastrar os n�veis desta forma de avalia��o.\\nAp�s'+
                                                                ' a inclus�o da Descri��o e Tipo de Resultado,'+
                                                                ' aparecer� uma tela para o cadastro dos n�veis.')\"":""?>>
           <option value="0" <?=@trim($ed37_c_minimoaprov)=="0"?"selected":""?>></option>
           <?for ($iCont = 0; $iCont < $oDaoConceito->numrows; $iCont++) {

               db_fieldsmemory($rsConceito,$iCont);?>
               <option value="<?=trim($ed39_c_conceito)?>"
                       <?=@trim($ed37_c_minimoaprov) == trim($ed39_c_conceito)?"selected":""?>>
                       <?=trim($ed39_c_conceito)?>
               </option>

           <?}?>
          </select>
         </td>
        </tr>
       </table>
      </span>
     </td>
    </tr>
   </table>
  </td>
  <td valign="top">
   <span name="nota" id="nota" style="visibility:hidden;">
    <table>
     <tr>
      <td>
       <?=@$Led37_i_menorvalor?>
      </td>
      <td>
       <?
       db_input('ed37_i_menorvalor',10,@$Ied37_i_menorvalor,true,'text',$db_opcao,
                " onchange='js_menor();'")?><br>
      </td>
     </tr>
     <tr>
      <td>
       <?=@$Led37_i_maiorvalor?>
      </td>
      <td>
       <?
       db_input('ed37_i_maiorvalor',10,@$Ied37_i_maiorvalor,true,'text',$db_opcao,
                " onchange='js_menor();'")?><br>
      </td>
     </tr>
     <tr>
      <td>
       <?=@$Led37_i_variacao?>
      </td>
      <td>
       <?
       db_input('ed37_i_variacao',10,@$Ied37_i_variacao,true,'text',$db_opcao,"")?><br>
      </td>
     </tr>
     <tr>
      <td>
       <?=@$Led37_c_minimoaprov?>
      </td>
      <td>
       <input title="M�nimo para Aprova��o Campo:ed37_c_minimoaprov " name="ed37_c_minimoaprovnota"
              type="text" id="ed37_c_minimoaprovnota" value="<?=@$ed37_c_minimoaprovnota?>" size="10" maxlength="10"
              onKeyDown="return js_controla_tecla_enter(this,event);" <?=$db_opcao==3?"disabled bgcolor='#DEB887'":""?>>
      </td>
     </tr>
    </table>
   </span>
  </td>
 </tr>
</table>
</fieldset>
<center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>"
       type="submit" id="db_opcao"
       value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
              <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();"
       <?=$db_opcao==1?"disabled":""?>>
<input name="novo" type="button" id="novo" value="Novo Registro" onclick="js_novo()" >
</center>
</form>
</div> <!-- Termina a Div que inicia nos fontes 001, 002, 003 nao remover -->
<?if (@$ed37_c_tipo == "NIVEL") {?>
    <center>
    <table width="100%">
     <tr>
      <td align="center">
       <iframe src="" name="iframe_conceitos" id="iframe_conceitos" width="100%"
               height="400" frameborder="0" scrolling="no">
       </iframe>
      </td>
     </tr>
    </table>
    </center>
<?}?>
</center>
<script>
function js_formataVariacao() {

  var iCasasDecimais = '<?=ArredondamentoNota::getNumeroCasasDecimais(db_getsession("DB_anousu"));?>';
  js_observeMascaraNota($('ed37_i_variacao'), '<?=ArredondamentoNota::getMascara(db_getsession("DB_anousu"))?>');
	js_observeMascaraNota($('ed37_i_menorvalor'), '<?=ArredondamentoNota::getMascara(db_getsession("DB_anousu"))?>');
	js_observeMascaraNota($('ed37_i_maiorvalor'), '<?=ArredondamentoNota::getMascara(db_getsession("DB_anousu"))?>');
	js_observeMascaraNota($('ed37_c_minimoaprovnota'), '<?=ArredondamentoNota::getMascara(db_getsession("DB_anousu"))?>');
}

function js_tiporesultado(valor) {

  if (valor == "") {

    document.getElementById("nota").style.visibility     = "hidden";
    document.getElementById("parecer").style.visibility  = "hidden";
    document.getElementById("conceito").style.visibility = "hidden";

  } else if (valor == "NOTA") {

    document.getElementById("nota").style.visibility     = "visible";
    document.getElementById("parecer").style.visibility  = "hidden";
    document.getElementById("conceito").style.visibility = "hidden";

  } else if (valor == "PARECER") {

    document.getElementById("parecer").style.visibility  = "visible";
    document.getElementById("nota").style.visibility     = "hidden";
    document.getElementById("conceito").style.visibility = "hidden";

  } else if(valor == "NIVEL") {

    document.getElementById("conceito").style.visibility = "visible";
    document.getElementById("parecer").style.visibility  = "hidden";
    document.getElementById("nota").style.visibility     = "hidden";

  }

}

function js_pesquisa() {

  js_OpenJanelaIframe('','db_iframe_formaavaliacao','func_formaavaliacao.php?'+
		              'funcao_js=parent.js_preenchepesquisa|ed37_i_codigo',
		              'Pesquisa de Formas de Avalia��o',true
		             );

}

function js_preenchepesquisa(chave) {

  db_iframe_formaavaliacao.hide();
  <?
  if ($db_opcao != 1) {
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>

}

function js_cent(amount) {
 //retorna o valor com 2 casas decimais
  return(amount == Math.floor(amount)) ? amount + '.00' : ( (amount*10 == Math.floor(amount*10)) ? amount + '0' : amount);
}

function js_novo() {
  location.href="edu1_formaavaliacao001.php";
}

function js_menor() {

  F = document.form1;
  if (parseFloat(F.ed37_i_menorvalor.value) > parseFloat(F.ed37_i_maiorvalor.value)) {

    alert("Menor Nota deve ser menor que a Maior Nota");
    F.ed37_i_menorvalor.value = "";
    F.ed37_i_menorvalor.focus();

  }

}

function js_hideshowselect(v) {

  if (document.forms.length > 0) {

    for (var i = 0;i < document.forms.length;i++) {

      var tam = document.forms[i].elements.length;

      for (var j = 0;j < tam; j++) {

        try {
          var str = new String(document.forms[i].elements[j].type);
        } catch(e) {
          var str = "";
        }

        if (str.indexOf("select") != -1 && document.forms[i].elements[j].name != "ed37_c_parecerarmaz"
          && document.forms[i].elements[j].name != "ed37_c_minimoaprovconc") {

          document.forms[i].elements[j].style.visibility = v;

        }

      }

    }

  }

  var fram = (frames.length==0)?1:frames.length;

  for (var x = 0;x < fram;x++) {

    var F  = (frames.length > 0)?(frames[x].document.forms):(document.forms);
    var qf = F.length;

    for (var i = 0;i < qf; i++) {

      var tam = F[i].elements.length;

      for (var j = 0;j < tam; j++) {
        try {
          var str = new String(F[i].elements[j].type);
        } catch(e) {
          var str = "";
        }
        if (str.indexOf("select") != -1 && F[i].elements[j].name != "ed37_c_parecerarmaz"
          && F[i].elements[j].name != "ed37_c_minimoaprovconc") {

          F[i].elements[j].style.visibility = v;

        }

      }

    }

  }

}


js_formataVariacao();
function js_validaVariacao() {

	if (document.getElementById("ed37_c_tipo").value == "NOTA") {

		if (document.getElementById("ed37_i_variacao").value == 0) {
		  alert("Valor de Varia��o n�o pode ser 0!");
		  return false;
		}
	}
}
</script>