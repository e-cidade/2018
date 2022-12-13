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

//MODULO: merenda
$clmer_tprefeicao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("me03_i_codigo");
$clrotulo->label("ed15_i_codigo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
 <tr>
  <td>
   <table border="0">
    <tr>
      <td nowrap title="<?=@$Tme03_i_codigo?>">
      <?=$Lme03_i_codigo?>
      </td>
      <td>
      <?db_input('me03_i_codigo',10,@$Ime03_i_codigo,true,'text',3,"")?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Tme03_i_escola?>">
      <?db_ancora(@$Lme03_i_escola,"",3);?>
      </td>
      <td>
      <?db_input('me03_i_escola',10,$Ime03_i_escola,true,'text',3,"")?>
      <?db_input('ed18_c_nome',60,@$Ied18_c_nome,true,'text',3,'')?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Tme03_c_tipo?>">
      <?=@$Lme03_c_tipo?>
      </td>
      <td>
      <?db_input('me03_c_tipo',40,@$Ime03_c_tipo,true,'text',$db_opcao,"")?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Tme03_c_inicio?>">
      <?=@$Lme03_c_inicio?>
      </td>
      <td>
      <?db_input('me03_c_inicio',10,@$Ime03_c_inicio,true,'text',$db_opcao,
                 "onchange='js_verifica_hora(this.value,this.name)';"
                )
      ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Tme03_c_fim?>">
      <?=@$Lme03_c_fim?>
      </td>
      <td>
      <?db_input('me03_c_fim',10,@$Ime03_c_fim,true,'text',$db_opcao,
                 "onchange='js_verifica_hora(this.value,this.name)';"
                )
      ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Tme03_i_turno?>">
      <?db_ancora(@$Lme03_i_turno,"js_pesquisame03_i_turno(true);",$db_opcao);?>
      </td>
      <td>
      <?db_input('me03_i_turno',10,$Ime03_i_turno,true,'text',$db_opcao," onchange='js_pesquisame03_i_turno(false);'")?>
      <?db_input('ed15_c_nome',40,@$Ied15_c_nome,true,'text',3,'')?>
      </td>
    </tr>
   </table>
  </td>
  <td align="center" valign="top">
   <table border="0">
    <tr>
     <?
     if ($db_opcao!=3 && $db_opcao!=33) {
       $campos = " me03_i_codigo as codigo,me03_c_tipo as descricao ";
       $resulttp = $clmer_tprefeicao->sql_record(
                                                  $clmer_tprefeicao->sql_query("",
                                                                               $campos,
                                                                               "me03_i_orden",
                                                                               ""
                                                                             )
                                                );
       if ($clmer_tprefeicao->numrows>1) {
	?>
	      <td rowspan="3">
	       <fieldset><legend><b>Ordenar</b></legend>
	        <table border="0">
	         <tr>
	           <td rowspan="3">
	             <select size="5" name="tipos[]" id="tipos">
	             <?
	              for ($ind=0;$ind<$clmer_tprefeicao->numrows;$ind++) {

	                db_fieldsmemory($resulttp,$ind);?>
	                <option value="<?=$codigo?>"> <?=$descricao?> </option>

	            <?}?>
	             </select>
	           </td>
	           <td>
	            <img style="cursor:hand"  onclick="js_cima();" src="skins/img.php?file=Controles/seta_up.png" />
	          </td>
	         </tr>
	         <tr>
	          <td>
	           <input name="atualizar" type="button" value="Atualizar"
	                  onclick="js_atualiza(<?=$db_opcao?>,'<?=@$me03_i_codigo?>');">
	          </td>
	         </tr>
	         <tr>
	          <td>
	            <img style="cursor:hand" onclick="js_baixo();" src="skins/img.php?file=Controles/seta_down.png" />
	          </td>
	         </tr>
	        </table>
	       </fieldset>
	      </td>
      <?
       }
      }
     ?>
    </tr>
   </table>
  </td>
 </tr>
</table>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>"
       type="submit"
       id="db_opcao"
       value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
              <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</center>
</form>
<script>
function js_pesquisame03_i_turno(mostra) {

  if (mostra==true) {

    js_OpenJanelaIframe('top.corpo','db_iframe_turno',
    	                'func_turno.php?funcao_js=parent.js_mostraturno1|ed15_i_codigo|ed15_c_nome','Pesquisa',true
		               );

  } else {

    if (document.form1.me03_i_turno.value != '') {

      js_OpenJanelaIframe('top.corpo','db_iframe_turno',
    	                  'func_turno.php?pesquisa_chave='+document.form1.me03_i_turno.value+
    	                  '&funcao_js=parent.js_mostraturno',
    	                  'Pesquisa',false
		                 );

    } else {
      document.form1.ed15_i_codigo.value = '';
    }
  }
}

function js_mostraturno(chave,erro) {

  document.form1.ed15_c_nome.value = chave;
  if (erro==true) {

    document.form1.me03_i_turno.focus();
    document.form1.me03_i_turno.value = '';

  }
}

function js_mostraturno1(chave1,chave2) {

  document.form1.me03_i_turno.value = chave1;
  document.form1.ed15_c_nome.value  = chave2;
  db_iframe_turno.hide();

}

function js_pesquisa() {

  js_OpenJanelaIframe('top.corpo','db_iframe_mer_tprefeicao',
		              'func_mer_tprefeicao.php?funcao_js=parent.js_preenchepesquisa|me03_i_codigo','Pesquisa',true
		             );

}

function js_preenchepesquisa(chave) {

  db_iframe_mer_tprefeicao.hide();
  <?
  if ($db_opcao!=1) {
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
 ?>
}

function js_cima() {

  var F = document.getElementById("tipos");
  if (F.selectedIndex != -1 && F.selectedIndex > 0) {

    var SI                 = F.selectedIndex - 1;
    var auxText            = F.options[SI].text;
    var auxValue           = F.options[SI].value;
    F.options[SI]          = new Option(F.options[SI + 1].text,F.options[SI + 1].value);
    F.options[SI + 1]      = new Option(auxText,auxValue);
    F.options[SI].selected = true;

  }
}

function js_baixo() {

  var F = document.getElementById("tipos");
  if (F.selectedIndex != -1 && F.selectedIndex < (F.length - 1)) {

    var SI                 = F.selectedIndex + 1;
    var auxText            = F.options[SI].text;
    var auxValue           = F.options[SI].value;
    F.options[SI]          = new Option(F.options[SI - 1].text,F.options[SI - 1].value);
    F.options[SI - 1]      = new Option(auxText,auxValue);
    F.options[SI].selected = true;

  }
}

function js_atualiza(opcao,me03_i_codigo) {

  var F     = document.getElementById("tipos");
  var lista ="";
  var sep   ="";
  for(x=0; x<F.length; x++) {

    lista=lista+sep+F.options[x].value;
    sep=",";

  }
  if (opcao==2) {
    location.href='mer1_mer_tprefeicao002.php?atualizar&lista='+lista+'&chavepesquisa='+me03_i_codigo;
  } else {
    location.href='mer1_mer_tprefeicao001.php?atualizar&lista='+lista;
  }
}

function js_verifica_hora(valor,campo) {

  erro = 0;
  ms   = "";
  hs   = "";
  tam  = "";
  pos  = "";
  tam  = valor.length;
  pos  = valor.indexOf(":");
  if (pos!=-1) {

    if (pos == 0 || pos > 2) {
      erro++;
    } else {

      if (pos == 1) {

        hs = "0"+valor.substr(0,1);
        ms = valor.substr(pos+1,2);

      } else if(pos==2) {

        hs = valor.substr(0,2);
        ms = valor.substr(pos+1,2);

      }
      if (ms == "") {
        ms = "00";
      }
    }
  } else {

    if (tam >= 4) {

      hs = valor.substr(0,2);
      ms = valor.substr(2,2);

    } else if(tam == 3) {

      hs = "0"+valor.substr(0,1);
      ms = valor.substr(1,2);

    } else if(tam == 2) {

      hs = valor;
      ms = "00";

    } else if(tam == 1) {

      hs = "0"+valor;
      ms = "00";

    }
  }
  if (ms!="" && hs!="") {

    if (hs>24 || hs<0 || ms>60 || ms<0) {
      erro++
    } else {

      if (ms == 60) {
        ms = "59";
      }
      if (hs == 24) {
        hs = "00";
      }

      hora = hs;
      minu = ms;
    }
  }
  if (document.form1.me03_c_fim.value != "" && erro == 0) {

    var botao   = document.getElementById("db_opcao");
    var val_ini = document.form1.me03_c_inicio.value;
    var pos_ini = val_ini.indexOf(":");
    var hs_ini  = "";
    if (pos_ini == 1) {
      hs_ini = "0" + val_ini.substr(0,1);
    } else if (pos_ini == 2) {
       hs_ini = val_ini.substr(0,2);
    }
    mn_ini = val_ini.substr(3,2);
    if (valor!="") {
      eval("document.form1."+campo+".value='"+hora+":"+minu+"';");
    }
    var val_fin = document.form1.me03_c_fim.value;
    var pos_fin = val_fin.indexOf(":");
    var ms_fin  = "";
    if (pos_fin == 1){
      hs_fin = "0" + val_fin.substr(0,1);
    } else if (pos_fin == 2){
     hs_fin = val_fin.substr(0,2);
    }
    mn_fin = val_fin.substr(3,2);
    if (hs_ini != "" && hs_fin != "") {

      if (hs_ini > hs_fin || (hs_ini == hs_fin && mn_ini > mn_fin)){

        alert("Hora inicial maior que hora final");
        botao.disabled = true;
        erro           = 99;

      } else {
        botao.disabled = false;
      }
    }
  }
  if (erro>0) {

    if (erro < 99) {
      alert("Informe uma hora válida.");
    }
  }
  if (valor!="") {

    eval("document.form1."+campo+".focus();");
    eval("document.form1."+campo+".value='"+hora+":"+minu+"';");

  }
}
</script>