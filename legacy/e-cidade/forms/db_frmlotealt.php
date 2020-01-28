<?php
/**
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
?>
<script>
function js_novolote1(){

  idmatricu = document.form1.idmatricu.value;
  setor     = document.form1.j34_setor.value;
  setor1    = document.form1.j30_descr.value;
  quadra    = document.form1.j34_quadra.value;
  bairro    = document.form1.j34_bairro.value;
  bairro1   = document.form1.j13_descr.value;
  loteam    = document.form1.j34_loteam.value;
  loteam1   = document.form1.j34_descr.value;
  zona      = document.form1.j34_zona.value;
  caract    = document.form1.caracteristica.value;
  parent.js_novolote2(idmatricu,setor,quadra,bairro,loteam,zona,caract,setor1,bairro1,loteam1);
}
function js_novolote2(){

  idmatricu = document.form1.idmatricu.value;
  setor     = document.form1.j34_setor.value;
  setor1    = document.form1.j30_descr.value;
  quadra    = document.form1.j34_quadra.value;
  bairro    = document.form1.j34_bairro.value;
  bairro1   = document.form1.j13_descr.value;
  loteam    = document.form1.j34_loteam.value;
  loteam1   = document.form1.j34_descr.value;
  zona      = document.form1.j34_zona.value;
  caract    = document.form1.caracteristica.value;
  parent.location.href = "cad1_iptubase001.php?nov=1&j34_setor="+setor+"&j34_quadra="+quadra+"&j34_bairro="+bairro+"&j34_loteam="+loteam+"&j34_zona="+zona+"&caracteristica="+caract+"&j30_descr="+setor1+"&j13_descr="+bairro1+"&j34_descr="+loteam1;
}
function js_limpatestada(){

  document.form1.cartestada.value="";
  document.form1.cartestpri.value="";
}
function js_checa() {

  if ( empty(document.form1.j34_area.value) || document.form1.j34_area.value <= 0 ) {

    alert("A Área m2 do lote não foi informada!");
    return false;
  }
  if (document.form1.caracteristica.value=="") {

    alert("As caracteristicas do lote não foram informadas!");
    return false;
  }
  if (document.form1.cartestada.value=="") {

    alert("A testada do lote não foi informada!");
    return false;
  }
  if (document.form1.cartestpri.value=="") {

    alert("A rua principal da testada não foi informada!");
    return false;
  }

  if (document.form1.j54_codigo.value!=""||document.form1.j54_distan.value!=""||document.form1.j54_orientacao.value!="0") {

    if (document.form1.j54_codigo.value=="") {

      alert("Informe a rua!");
      return false;
    }
    if (document.form1.j54_distan.value=="") {

      alert("Informe a distancia!");
      return false;
    }
    if (document.form1.j54_orientacao.value=="0") {

      alert("Informe o ponto!");
      return false;
    }
  }

  return true;
}
</script>

<br />

<fieldset>

  <legend><?=$Ltestada; ?></legend>

  <table  width="790" align="center" border="0">
    <tr>
      <td nowrap title="<?=@$Tj34_idbql?>">
        <?=@$Lj34_idbql?>
        <input type="hidden" name="idmatricu" value="<?=@$idmatricu?>" >
        <input type="hidden" name="incluquadra" value="" >
      </td>
      <td>
      <?
      db_input('j34_idbql',10,$Ij34_idbql,true,'text',3,"");
      ?>
    </td>
  </tr>
  <?
  //========================== S E T O R   F I S C A L ==================================================
  if (isset ($mostrasetfiscal) && $mostrasetfiscal == 't'){
  ?>
  <tr>
    <td nowrap title="<?=@$Tj91_codigo?>">
      <input name="oid" type="hidden" value="<?=@$oid?>">
      <?
          db_ancora(@$Lj91_codigo,"js_pesquisaj91_codigo(true);",$db_opcao);
      ?>
    </td>
    <td>
		<?
			db_input('j91_codigo',10,$Ij91_codigo,true,'text',$db_opcao," onchange='js_pesquisaj91_codigo(false);'");
		  db_input('j90_descr',40,$Ij90_descr,true,'text',3,'')
    ?>
    </td>
  </tr>
  <?
  }
 //==========================================================================================================
  ?>
  <tr>
    <td nowrap title="<?=@$Tj34_setor?>">
       <?
       db_ancora(@$Lj34_setor,"js_pesquisaj34_setor(true);",$db_opcao);
       ?>
    </td>
    <td>
			<?
       db_input('j34_setor',10,$Ij34_setor,true,'text',$db_opcao,"onchange='js_pesquisaj34_setor(false);'");
       db_input('j30_descr',70,$Ij30_descr,true,'text',3,'');
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj34_quadra?>">
       <?=@$Lj34_quadra?>
    </td>
    <td>
<?
if (isset($j34_setor)&& $j34_setor!="" && $j34_setor!="0") {

  $resultface = $clface->sql_record($clface->sql_query("","distinct j37_quadra,j37_quadra","j37_quadra","j37_setor='$j34_setor'"));
  $num        = $clface->numrows;

  if ($num!=0) {

    db_selectrecord("j34_quadra",$resultface, false, 1, '', '', '', '', 'js_limpaQuadraTestada()');
    $confere  = false;

    for ($i=0;$i<$num;$i++) {

    	$oQuadra = db_utils::fieldsMemory($resultface,$i);

    	if ($confere==false && $j34_quadra==$oQuadra->j37_quadra) {
			  $confere = true;
			}
    }
  } else {

   	db_msgbox("Setor sem Quadra Cadastrada!!");
   	$db_botao = false;
  }
}else{

  $j34_quadra ="";
  db_input('j34_quadra',5,$Ij34_quadra,true,'text',3,"onclick=\"alert('Informe o Setor!')\"");
}
?>
  <script type="text/javascript">
    function js_limpaQuadraTestada(){
      js_limpatestada();
    }
  </script>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj34_lote?>">
       <?=@$Lj34_lote?>
    </td>
    <td>
<?
$val=$Ij34_lote;
$result_param = $clcfiptu->sql_record($clcfiptu->sql_query(db_getsession("DB_anousu"),"j18_formatlote"));
if ($clcfiptu->numrows>0){
	db_fieldsmemory($result_param,0);
	if ($j18_formatlote==1){
		$val = 3;
	}else{
		$val = 1;
	}
}
db_input('j34_lote',10,$val,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj34_area?>">
       <?=@$Lj34_area?>
    </td>
    <td>
<?
db_input('j34_area',10,4,true,'text',$db_opcao,"");
?>
    </td>
  </tr>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj34_areapreservada?>">
       <?=@$Lj34_areapreservada?>
    </td>
    <td>
<?
db_input('j34_areapreservada',10,4,true,'text',$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj34_bairro?>">
       <?
       db_ancora(@$Lj34_bairro,"js_pesquisaj34_bairro(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('j34_bairro',10,$Ij34_bairro,true,'text',$db_opcao,"onchange='js_pesquisaj34_bairro(false);'")
?>
       <?
db_input('j13_descr',70,$Ij13_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj34_loteam?>">
       <?
       db_ancora(@$Lj34_loteam,"js_pesquisaj34_loteam(true);",$db_opcao);
       ?>
    </td>
    <td>
      <?
        db_input('j34_loteam',10,$Ij34_loteam,true,'text',$db_opcao," onchange='js_pesquisaj34_loteam(false);'");
        db_input('j34_descr',70,$Ij34_descr,true,'text',3,'');
       ?>
    <td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj34_areal?>">
       <?=@$Lj34_areal?>
    </td>
    <td>
<?
db_input('j34_areal',10,4,true,'text',$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj34_totcon?>">
       <?=@$Lj34_totcon?>
    </td>
    <td><? //db_input('j34_totcon',15,$Ij34_totcon,true,'text',$db_opcao,"");
            db_input('j34_totcon',10,$Ij34_totcon,true,'text',3,"");
         ?></td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj34_zona?>">
       <?=@$Lj34_zona?>
    </td>
    <td>
<?
$sqlzona = "select j50_zona, j50_descr from zonas";
$resultzona = db_query($sqlzona);

db_selectrecord('j34_zona',$resultzona,true,$db_opcao);

//db_input('j34_zona',5,$Ij34_zona,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td >
       <?
       db_ancora(@$Lcarlote,"js_mostracaracteristica();",$db_opcao);
       ?>
   </td>
    <td>
			<?
			db_input('caracteristica',10,0,true,'hidden',$db_opcao,"")
			?>
    </td>
  </tr>
  <tr>
    <td>
           <?
         db_ancora($Ltestada,' js_testada(); ',$db_opcao);
         //db_input('testada',2,'',true,'hidden',3);
           ?>
     </td>
    <td>
      <?
        db_input('cartestada','',0,true,'hidden',$db_opcao,"");
        db_input('cartestpri','',0,true,'hidden',$db_opcao,"");
      ?>
    </td>
  </tr>

  <tr>
    <td>
      <?
        db_ancora("<b>Testadas Internas</b>",' js_testadainter(); ',$db_opcao);
      ?>
     </td>
    <td>
      <?
        db_input('testadainter','',0,true,'hidden',$db_opcao,"");
      ?>
    </td>
  </tr>
  <tr>
   <td>
    <?
     db_ancora("<b>Localização:</b>",'js_loteloc(1); ',$db_opcao);
     db_input('j06_setorloc',6,0,true,'hidden',$db_opcao,"");
     db_input('j06_quadraloc',5,0,true,'hidden',$db_opcao,"");
     db_input('j06_lote',5,0,true,'hidden',$db_opcao,"");
    ?>
   </td>
  </tr>
  <tr>
   <td>
    <?=@$Llotedist?>
   </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj54_codigo?>">
       <?
       db_ancora(@$Lj54_codigo,"js_pesquisaj54_codigo(true);",$db_opcao);
       ?>
     </td>
    <td>
<?
db_input('j54_codigo',10,$Ij54_codigo,true,'text',$db_opcao," onchange='js_pesquisaj54_codigo(false);'")
?>
       <?
db_input('j14_nome',70,$Ij14_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj54_distan?>">
       <?=@$Lj54_distan?>
    </td>
    <td>
<?
db_input('j54_distan',10,$Ij54_distan,true,'text',$db_opcao,"")
?>
    </td>
  </tr>

  <tr>
    <td nowrap title="<?=@$Tj54_orientacao?>">
		  <b>Ponto de Orientação</b>
    </td>
    <td>
     <?

     $arrayPonto = array();

		 $sqlPonto   = " select 0 as j64_sequencial, 'Nenhum' as j64_descricao from orientacao ";
     $sqlPonto  .= "  union ";
     $sqlPonto  .= " select j64_sequencial, j64_descricao from orientacao ";
     $rsPonto    = db_query($sqlPonto);
     $intPonto   = pg_numrows($rsPonto);

     for($iPonto=0;$iPonto<$intPonto;$iPonto++){

       db_fieldsmemory($rsPonto,$iPonto);
       $arrayPonto[$j64_sequencial] = $j64_descricao;
     }
     db_select('j54_orientacao',$arrayPonto,true,$db_opcao);

     ?>
     <?
//$matriz = array('0'=>"...",'leste'=>"Leste",'oeste'=>"Oeste",'norte'=>"Norte",'sul'=>"Sul",'nordeste'=>"Nordeste",'Sudoeste'=>"Sudoeste",'noroeste'=>"Noroeste",'sudeste'=>"Sudeste");
//db_select('j54_orientacao',$matriz,true,$db_opcao);

?>

    </td>
  </tr>
  </table>
  </fieldset>
  <br />

<input name="<?=($db_opcao==1?"incluir":"alterar")?>"  type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":"Alterar")?>" <?=($db_botao==false?"disabled":"")?> <?=($db_opcao!=3?"onclick=\"return js_checa()\"":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
<input name="novolote"  type="button" id="novolote" value="Novo lote" <?=($db_opcao==1?"disabled":"")?> onclick="js_novolote1()">
<input name="novolote1"  type="button" id="novolote1" value="Novo lote com nova matrícula" <?=($db_opcao==1?"disabled":"")?> onclick="js_novolote2()">

<script>
function js_pesquisaj34_loteam(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_lote','db_iframe','func_loteam.php?funcao_js=parent.js_mostraloteam1|0|1','Pesquisa',true,0);
  }else{
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_lote','db_iframe','func_loteam.php?pesquisa_chave='+document.form1.j34_loteam.value+'&funcao_js=parent.js_mostraloteam','Pesquisa',false,0);
  }
}
function js_mostraloteam(chave,erro){
  document.form1.j34_descr.value = chave;
  if(erro==true){
    document.form1.j34_loteam.focus();
    document.form1.j34_loteam.value = '';
  }
}
function js_mostraloteam1(chave1,chave2){
  document.form1.j34_loteam.value = chave1;
  document.form1.j34_descr.value = chave2;
  db_iframe.hide();
}




function js_pesquisa(){
  js_OpenJanelaIframe('CurrentWindow.corpo.iframe_lote','db_iframe','func_lote.php?funcao_js=parent.js_preenchepesquisa|j34_idbql','Pesquisa',true,0);
}
function js_preenchepesquisa(chave){
  db_iframe.hide();
  idmatricu=document.form1.idmatricu.value;
  if(idmatricu!=""){
    location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+chave+"&idmatricu="+idmatricu;
  }else{
    location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+chave;
  }

}

function js_mostracaracteristica(){

  caracteristica=document.form1.caracteristica.value;
   if(caracteristica!=""){
     js_OpenJanelaIframe('CurrentWindow.corpo.iframe_lote','db_iframe','cad1_cargeral001.php?nomeiframe=db_iframe&nomeobj=caracteristica&db_opcao=<?=$db_opcao?>&caracteristica='+caracteristica+'&tipogrupo=L&codigo='+document.form1.j34_idbql.value,'Pesquisa',true,0);
   }else{
     js_OpenJanelaIframe('CurrentWindow.corpo.iframe_lote','db_iframe','cad1_cargeral001.php?nomeiframe=db_iframe&nomeobj=caracteristica&db_opcao=<?=$db_opcao?>&tipogrupo=L&codigo='+document.form1.j34_idbql.value,'Pesquisa',true,0);
   }
}

function js_pesquisaj54_codigo(mostra){
  if(mostra==true){
     js_OpenJanelaIframe('CurrentWindow.corpo.iframe_lote','db_iframe','func_ruas.php?funcao_js=parent.js_mostraruas1|0|1','Pesquisa',true,0);
  }else{
     js_OpenJanelaIframe('CurrentWindow.corpo.iframe_lote','db_iframe','func_ruas.php?pesquisa_chave='+document.form1.j54_codigo.value+'&funcao_js=parent.js_mostraruas','Pesquisa',false,0);
  }
}
function js_mostraruas1(chave1,chave2){
  document.form1.j54_codigo.value = chave1;
  document.form1.j14_nome.value = chave2;
  db_iframe.hide();
}
function js_mostraruas(chave,erro){
  document.form1.j14_nome.value = chave;
  if(erro==true){
    document.form1.j54_codigo.focus();
    document.form1.j54_codigo.value = '';
  }
}
/* TESTADAS INTERNAS */
function js_testadainter(){
  j34_idbql   = document.form1.j34_idbql.value;
  matrizvolta = document.form1.testadainter.value;
  j34_setor   = document.form1.j34_setor.value;
  j34_quadra  = encodeURIComponent(document.form1.j34_quadra.value);
  if(j34_setor==""||j34_quadra==""){
    alert("Informe o código do setor e quadra!");
    return;
  }

  if(matrizvolta!=""){
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_lote','db_iframe','cad1_testadainter001.php?j34_setor='+j34_setor+'&j34_quadra='+j34_quadra+'&db_opcao=<?=$db_opcao?>&matrizvolta='+encodeURIComponent(matrizvolta)+'&idbql='+j34_idbql+'&mostranum=<?=$numerotestada?>','Pesquisa',true,0);
  }else{
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_lote','db_iframe','cad1_testadainter001.php?j34_setor='+j34_setor+'&j34_quadra='+j34_quadra+'&db_opcao=1&idbql='+j34_idbql+'&mostranum=<?=$numerotestada?>','Pesquisa',true,0);
  }
}

function js_testada() {
   var j34_idbql   = document.form1.j34_idbql.value;
   var matrizvolta = document.form1.cartestada.value;
   var principal   = document.form1.cartestpri.value;
   var j34_setor   = document.form1.j34_setor.value;
   var j34_quadra  = encodeURIComponent(document.form1.j34_quadra.value);

   if(j34_setor==""||j34_quadra==""){
     alert("Informe o código do setor e quadra!");
     return;
   }

   if (matrizvolta != "") {

     var sUrl = 'cad1_testada004.php?j34_setor='+j34_setor+'&j34_quadra='+j34_quadra+'&db_opcao=<?=$db_opcao?>&voltapri='+principal+'&matrizvolta='+encodeURIComponent(matrizvolta)+'&testa='+j34_idbql+'&mostranum=<?=$numerotestada?>';
     js_OpenJanelaIframe('CurrentWindow.corpo.iframe_lote','db_iframe',sUrl,'Pesquisa',true,0);
   } else {
     js_OpenJanelaIframe('CurrentWindow.corpo.iframe_lote','db_iframe','cad1_testada004.php?j34_setor='+j34_setor+'&j34_quadra='+j34_quadra+'&db_opcao=1&testa='+j34_idbql+'&mostranum=<?=$numerotestada?>','Pesquisa',true,0);
   }
}

function js_loteloc(tp){
 if(tp == 2){
  db_iframe_loteloc.hide();
 }else{
  j06_idbql     = document.form1.j34_idbql.value;
  j06_setorloc  = document.form1.j06_setorloc.value;
  j06_quadraloc = document.form1.j06_quadraloc.value;
  j06_lote      = document.form1.j06_lote.value;
  js_OpenJanelaIframe('CurrentWindow.corpo.iframe_lote','db_iframe_loteloc','cad1_loteloc001.php?j06_idbql='+j06_idbql+'&j06_setorloc='+j06_setorloc+'&j06_quadraloc='+j06_quadraloc+'&j06_lote='+j06_lote+'&db_opcao=<?=$db_opcao?>&db_botao=<?=$db_botao?>','Pesquisa',true);;
 }
}

function js_pesquisaj34_setor(mostra){
  js_limpatestada();
  if(mostra==true){
     js_OpenJanelaIframe('CurrentWindow.corpo.iframe_lote','db_iframe','func_setor.php?funcao_js=parent.js_mostrasetor1|0|1','Pesquisa',true,0);
  }else{
     js_OpenJanelaIframe('CurrentWindow.corpo.iframe_lote','db_iframe','func_setor.php?pesquisa_chave='+document.form1.j34_setor.value+'&funcao_js=parent.js_mostrasetor','Pesquisa',false,0);
  }
}
function js_mostrasetor(chave,erro){
  if(erro==true){
    document.form1.j34_setor.focus();
    document.form1.j34_setor.value = '';
  }else{
  document.form1.incluquadra.value="<?=$db_opcao?>";
  document.form1.submit();
  }
  document.form1.j30_descr.value = chave;
}
function js_mostrasetor1(chave1,chave2){
  document.form1.incluquadra.value="<?=$db_opcao?>";
  document.form1.j34_setor.value = chave1;
  document.form1.j30_descr.value = chave2;
  db_iframe.hide();
  document.form1.submit();

}
function js_pesquisaj34_bairro(mostra){
  if(mostra==true){
     js_OpenJanelaIframe('CurrentWindow.corpo.iframe_lote','db_iframe','func_bairro.php?funcao_js=parent.js_mostrabairro1|0|1','Pesquisa',true,0);
  }else{
     js_OpenJanelaIframe('CurrentWindow.corpo.iframe_lote','db_iframe','func_bairro.php?pesquisa_chave='+document.form1.j34_bairro.value+'&funcao_js=parent.js_mostrabairro','Pesquisa',false,0);
  }
}
function js_mostrabairro(chave,erro){
  document.form1.j13_descr.value = chave;
  if(erro==true){
    document.form1.j34_bairro.focus();
    document.form1.j34_bairro.value = '';
  }
}
function js_mostrabairro1(chave1,chave2){
  document.form1.j34_bairro.value = chave1;
  document.form1.j13_descr.value = chave2;
  db_iframe.hide();
}

function js_pesquisaj91_codigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_setorfiscal','func_setorfiscal.php?funcao_js=parent.js_mostrasetorfiscal1|j90_codigo|j90_descr','Pesquisa',true,0);
  }else{
     if(document.form1.j91_codigo.value != ''){
        js_OpenJanelaIframe('','db_iframe_setorfiscal','func_setorfiscal.php?pesquisa_chave='+document.form1.j91_codigo.value+'&funcao_js=parent.js_mostrasetorfiscal','Pesquisa',false);
     }else{
       document.form1.j90_descr.value = '';
     }
  }
}
function js_mostrasetorfiscal(chave,erro){
  document.form1.j90_descr.value = chave;
  if(erro==true){
    document.form1.j91_codigo.focus();
    document.form1.j91_codigo.value = '';
  }
}
function js_mostrasetorfiscal1(chave1,chave2){
  document.form1.j91_codigo.value = chave1;
  document.form1.j90_descr.value = chave2;
  db_iframe_setorfiscal.hide();
}

document.form1.j34_quadra.style.display= 'none';
</script>
<?
if((isset($j34_idbql) || isset($alterando) || isset($chavepesquisa))&& $db_opcao==2 && !isset($incluquadra) ){
  if($confere==false){
     db_msgbox("Quadra $j34_quadra não cadastrada para o setor $j34_setor!\\n Contate suporte.");
     echo "
        <script>
	         parent.location.href='cad1_iptubase002.php';
        </script>
     ";
  }
}
?>