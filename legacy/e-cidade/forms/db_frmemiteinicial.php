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
?>
<script>
function js_verificacerti(){
  var teste=false;
  var numcert=document.form1.numcert.value;
  for(i=0; i<numcert; i++){
    if(eval('document.form1.certid'+i+'.checked')==true){
       teste=true;
       break;
    }
  }

  if(teste==true){

    var lista_veri_certid = [];
    var checks            = document.getElementsByClassName('chkcert');
    var qtdeChecks        = checks.length;
    var listaChecks       = [];

    for(var i=0; i < qtdeChecks ; i++) {

      var item       = checks.item(i);
      var objItem    = {};

      objItem[item.value] = 0;
      if(item.checked) {
        objItem[item.value] = 1;
      }

      listaChecks.push(item.name);
      lista_veri_certid.push(objItem);
    }

    listaChecks.each(function (item, ind) {

      var itemHidden = document.form1['veri_'+item];
          itemHidden.remove();

      var itemCheck  = document.form1[item];
          itemCheck.remove();
    });

    var elementListaVeriCertid       = new Element('input');
        elementListaVeriCertid.name  = 'lista_veri_certid';
        elementListaVeriCertid.value = JSON.stringify(lista_veri_certid);

    document.form1.appendChild(elementListaVeriCertid);

    return true;
  }else{

    alert("É necessário que uma certidão esteja marcada.");
    return false;
  }
}
</script>
<?
$clrotulo = new rotulocampo;
$clrotulo->label("v50_inicial");
$clrotulo->label("v50_advog");
$clrotulo->label("v54_descr");
$clrotulo->label("v53_descr");
$clrotulo->label("v50_codlocal");
$clrotulo->label("v70_vara");
$clrotulo->label("v51_certidao");
?>

<form class="container" name="form1" method="post" action="">
  <fieldset>
  <legend>Procedimento - Inicial/Alteração</legend>
  <table class="form-container">
    <tr>
			<td>
        <?
					db_input('v50_inicial',10,$Iv50_inicial,true,'hidden',$db_opcao);
        ?>
      </td>
    </tr>
    <tr>
      <td title="<?=@$Tv50_advog?>">
        <?
					db_ancora($Lv50_advog,' js_advog(true); ',$db_opcao);
        ?>
      </td>
      <td>
        <?
					db_input('v50_advog',6,$Iv50_advog,true,'text',$db_opcao,"onchange='js_advog(false)'");
					db_input('z01_nome',40,$Iz01_nome,true,'text',3,"","z01_nomeadvog");
        ?>
      </td>
    </tr>
		<tr>
      <td nowrap title="<?=@$Tv50_codlocal?>">
        <?
					db_ancora(@$Lv50_codlocal,"js_pesquisav50_codlocal(true);",$db_opcao);
        ?>
      </td>
      <td>
				<?
					db_input('v50_codlocal',6,$Iv50_codlocal,true,'text',$db_opcao," onchange='js_pesquisav50_codlocal(false);'")
				?>
				<?
					db_input('v54_descr',40,$Iv54_descr,true,'text',3,'')
				?>
			</td>
		</tr>
		<tr>
			 <td>&nbsp;</td>
		</tr>

	<? if($db_opcao==1||isset($v50_inicial)){ ?>

	 <tr>
       <td align="center" colspan="2" valign="top">
         <fieldset class="separator">
	  	   <legend align="center">
	  	   	 <b>&nbsp;CERTIDÕES EMITIDAS&nbsp;</b>
	  	   </legend>
		   <table>
			 <tr>
			   <td>
				  <?
					 if($db_opcao!=1){
						$resulta = $clinicialcert->sql_record($clinicialcert->sql_query_file($v50_inicial,"","v51_certidao as certidao","",""));
						$numrows = $clinicialcert->numrows;
					 }

					 $coluna = -1;
					 echo "<table id='tableCert'>";
					 echo "<tr>";

					 for($i=0;$i<$numrows;$i++){

					   db_fieldsmemory($resulta,$i);

					   if ($coluna > 10) {
					   	 echo "</tr>";
					   	 echo "<tr>";
						 $coluna = 0;
					   } else {
						 $coluna++;
					   }

					   $x="certid".$i;

             $oCertidao = new Certidao($certidao);

             $sEcho = "<td rel='ignore-css'>
                         <input class='chkcert' type='checkbox' name='certid$i'      value='$certidao' ".(isset($$x)||isset($chavepesquisa)?'checked':'checked')." ".($db_opcao==3?'disabled':'')."  >\n
                         <input type='hidden'   name='veri_certid$i' value='$certidao'> $certidao
                       </td>";

             if ( $oCertidao->isCobrancaExtrajudicial() ) {

               $sEcho = "<td rel='ignore-css'>
                           <input class='chkcert' type='checkbox' name='certid$i'      value='$certidao'  disabled  >\n
                           <input type='hidden'   name='veri_certid$i' value='$certidao'> $certidao(Em Cobrança Extrajudicial)
                         </td>";
             }

             echo $sEcho;
					 }

					 echo "</tr>";
					 echo "</table>";

					 ?>
			  </td>
			</tr>
			<tr>
			  <td>
        <input type="hidden"  name="numcheck" value="<?=@$numrows?>">
			  </td>
			</tr>
		  </table>
        </fieldset>
	  </td>
    </tr>
  <? } ?>
  </table>
  </fieldset>
         <input type="hidden" name="numcert" value="<?=@$numrows?>">
         <input type="hidden" name="nomechave" value="<?=@$nomechave?>">
         <input type="hidden" name="valorchave" value="<?=@$valorchave?>">
        <input name="<?=($db_botao==1?"incluir":($db_botao==2?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_botao==1?"Incluir":($db_botao==2?"Alterar":"Excluir"))?>" <?=($db_opcao==1||$db_opcao==2?'onclick="return js_verificacerti();"':'')?>  <?=($botao==3?'disabled':'')?>>
        <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
        <?
        if ($db_opcao == 2) {
		  echo '<input name="novacert" type="button" id="novacert" value="Nova Certidão" onclick="js_novaCert();" >';
        }
        ?>
</form>
<script>
function js_pesquisa(){
  db_iframe.jan.location.href = 'func_inicial.php?funcao_js=parent.js_preenchepesquisa|0';
  db_iframe.mostraMsg();
  db_iframe.show();
  db_iframe.focus();
}
function js_preenchepesquisa(chave){
  db_iframe.hide();
  <?if($db_botao!=1){?>
  location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+chave;
  <?}?>
}
function js_advog(mostra){
  var advog=document.form1.v50_advog.value;
  if(mostra==true){
    db_iframe.jan.location.href = 'func_advog.php?funcao_js=parent.js_mostraadvog|0|2';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_advog.php?pesquisa_chave='+advog+'&funcao_js=parent.js_mostraadvog1';
  }
}
function js_mostraadvog(chave1,chave2){
  document.form1.v50_advog.value = chave1;
  document.form1.z01_nomeadvog.value = chave2;
  db_iframe.hide();
}
function js_mostraadvog1(chave,erro){
  document.form1.z01_nomeadvog.value = chave;
  if(erro==true){
    document.form1.v50_advog.focus();
    document.form1.v50_advog.value = '';
  }
}
function js_pesquisav50_codlocal(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_localiza.php?funcao_js=parent.js_mostralocaliza1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_localiza.php?pesquisa_chave='+document.form1.v50_codlocal.value+'&funcao_js=parent.js_mostralocaliza';
  }
}
function js_mostralocaliza(chave,erro){
  document.form1.v54_descr.value = chave;
  if(erro==true){
    document.form1.v50_codlocal.focus();
    document.form1.v50_codlocal.value = '';
  }
}
function js_mostralocaliza1(chave1,chave2){
  document.form1.v50_codlocal.value = chave1;
  document.form1.v54_descr.value = chave2;
  db_iframe.hide();
}
function js_pesquisav70_vara(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_vara.php?funcao_js=parent.js_mostravara1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_vara.php?pesquisa_chave='+document.form1.v70_vara.value+'&funcao_js=parent.js_mostravara';
  }
}
function js_mostravara(chave,erro){
  document.form1.v53_descr.value = chave;
  if(erro==true){
    document.form1.v70_vara.focus();
    document.form1.v70_vara.value = '';
  }
}
function js_mostravara1(chave1,chave2){
  document.form1.v70_vara.value = chave1;
  document.form1.v53_descr.value = chave2;
  db_iframe.hide();
}

function js_novaCert(){
 var sUrl = 'func_certidaltcdas.php?v50_inicial='+document.form1.v50_inicial.value+'&funcao_js=parent.js_mostracert|0';
 js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe',sUrl,'Pesquisa',true);
}

function js_mostracert(iCodCert){

  var objChk     	   = js_getElementbyClass(document.form1 , 'chkcert');
  var iNroObjChk 	   = objChk.length;
  var objTable       = document.getElementById('tableCert');
  var iNroTableRows  = objTable.rows.length;
  var iNroTableCells = objTable.rows[(iNroTableRows-1 )].cells.length;

  var sInputChk  = " <td> ";
	  sInputChk += "  <input class='chkcert' type='checkbox' name='certid"+iNroObjChk+"'	   value='"+iCodCert+"' checked>";
	  sInputChk += "  <input 			     type='hidden'   name='veri_certid"+iNroObjChk+"'  value='"+iCodCert+"'>"+iCodCert;
	  sInputChk += " </td>";

  if ( iNroTableCells < 10  ) {

    objTable.rows[(iNroTableRows-1)].innerHTML += sInputChk;

  } else {

	var sSaida  = "<tr>";
    	sSaida += sInputChk;
      	sSaida += "</tr>";
    objTable.innerHTML += sSaida;

  }

  if ( iNroTableRows == 1 ) {
   var iNroCert = (iNroTableCells + 1);
  } else {
   var iNroCert = ( (iNroTableRows-1) * 10 ) + ( iNroTableCells + 1);
  }

  document.form1.numcert.value  = iNroObjChk+1;
  document.form1.numcheck.value = iNroObjChk+1;

  db_iframe.hide()

}


</script>