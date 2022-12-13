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

//MODULO: cadastro
$cllote->rotulo->label();
$cllotedist->rotulo->label();
$clcarlote->rotulo->tlabel();
$cltestada->rotulo->tlabel();
$cllotedist->rotulo->tlabel();
$clrotulo = new rotulocampo;
$clrotulo->label("j30_descr");
$clrotulo->label("j13_descr");
$clrotulo->label("j14_nome");
$clrotulo->label("j34_loteam");
$clrotulo->label("j34_descr");

$cllotesetorfiscal->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("j90_descr");
$clrotulo->label("j34_setor");

$cllotedist->rotulo->tlabel();
if(isset($incluquadra) && $incluquadra!="" ){
    $resulta=$clsetor->sql_record($clsetor->sql_query($j34_setor,"j30_descr"));
    db_fieldsmemory($resulta,0);
    $db_opcao = $incluquadra;
    $db_botao=true;
}
?>
<script>
function js_limpatestada(){
  document.form1.cartestada.value="";
  document.form1.cartestpri.value="";
}

function js_checa(){

   if ( empty(document.form1.j34_area.value) || document.form1.j34_area.value <= 0 ) {

    alert("A Área m2 do lote não foi informada!");
    return false;
  }
  if(document.form1.caracteristica.value==""){
    alert("As caracteristicas do lote não foram informadas!");
    return false;
  }
  if(document.form1.cartestada.value==""){
    alert("A testada do lote não foi informada!");
    return false;
  }
  if(document.form1.cartestpri.value==""){
    alert("A rua principal da testada não foi informada!");
    return false;
  }

  if(document.form1.j54_codigo.value!=""||document.form1.j54_distan.value!=""||document.form1.j54_ponto.value!="0"){
    if(document.form1.j54_codigo.value==""){
      alert("Informe a rua!");
      return false;
    }
    if(document.form1.j54_distan.value==""){
      alert("Informe a distância!");
      return false;
    }
    if(document.form1.j54_ponto.value=="0"){
      alert("Informe o ponto!");
      return false;
    }
  }


  return true;
}
</script>

<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tj34_idbql?>">
  <input type="hidden" name="outrolote">
  <input type="hidden" name="incluquadra" value="" >

       <?=@$Lj34_idbql?>
    </td>
    <td>
<?
db_input('j34_idbql',6,$Ij34_idbql,true,'text',3,"")
?>
    <td>
  </tr>
  <?
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
		db_input('j91_codigo',5,$Ij91_codigo,true,'text',$db_opcao," onchange='js_pesquisaj91_codigo(false);'")
		?>
       <?
		db_input('j90_descr',40,$Ij90_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <?}?>


  <tr>
    <td nowrap title="<?=@$Tj34_setor?>">
       <?
       db_ancora(@$Lj34_setor,"js_pesquisaj34_setor(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('j34_setor',5,$Ij34_setor,true,'text',$db_opcao," onchange='js_pesquisaj34_setor(false);'")
?>
       <?
db_input('j30_descr',40,$Ij30_descr,true,'text',3,'')
       ?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tj34_quadra?>">
       <?=@$Lj34_quadra?>
    </td>
    <td>
<?

if(isset($j34_setor)&& $j34_setor!="" && $j34_setor!="0"){
  $resultface = $clface->sql_record($clface->sql_query("","distinct j37_quadra","j37_quadra","j37_setor='$j34_setor'"));
  $num=$clface->numrows;
  if($num!=0){
    echo "<select name='j34_quadra'>";
    $confere=false;
    for($i=0;$i<$num;$i++){
      db_fieldsmemory($resultface,$i);
        echo "<option  value='".$j37_quadra."' ".($j34_quadra==$j37_quadra?"selected":"").">$j37_quadra</option>";
	if($confere==false && $j34_quadra==$j37_quadra){
	  $confere=true;
	}
    }
   echo "</select>";
  }else{
   	db_msgbox("Setor sem Quadra Cadastrada!!");
   	$db_botao=false;
  }
}else{
$j34_quadra ="";
db_input('j34_quadra',5,$Ij34_quadra,true,'text',3,"onclick=\"alert('Informe o Setor!')\"");
}

?>
    </td>
  <tr>
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
db_input('j34_lote',4,$val,true,'text',$db_opcao,"onchange=js_limpatestada()")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tj34_area?>">
       <?=@$Lj34_area?>
    </td>
    <td>
<?
db_input('j34_area',15,4,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tj34_bairro?>">
       <?
       db_ancora(@$Lj34_bairro,"js_pesquisaj34_bairro(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('j34_bairro',4,$Ij34_bairro,true,'text',$db_opcao," onchange='js_pesquisaj34_bairro(false);'")
?>
       <?
db_input('j13_descr',40,$Ij13_descr,true,'text',3,'')
       ?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tj34_loteam?>">
       <?
       db_ancora(@$Lj34_loteam,"js_pesquisaj34_loteam(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('j34_loteam',4,$Ij34_loteam,true,'text',$db_opcao," onchange='js_pesquisaj34_loteam(false);'")
?>
       <?
db_input('j34_descr',40,$Ij34_descr,true,'text',3,'')
       ?>
    <td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj34_areal?>">
       <?=@$Lj34_areal?>
    </td>
    <td>
<?
db_input('j34_areal',15,4,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tj34_totcon?>">
       <?=@$Lj34_totcon?>
    </td>
    <td>
<?
db_input('j34_totcon',15,$Ij34_totcon,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tj34_zona?>">
       <?=@$Lj34_zona?>
    </td>
    <td>
<?
db_input('j34_zona',5,$Ij34_zona,true,'text',$db_opcao,"")
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
db_input('caracteristica',15,0,true,'hidden',$db_opcao,"")
?>
    <td>
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
        db_input('cartestada',15,0,true,'hidden',$db_opcao,"");
        db_input('cartestpri',15,0,true,'hidden',$db_opcao,"");
      ?>
    <td>
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
db_input('j54_codigo',4,$Ij54_codigo,true,'text',$db_opcao," onchange='js_pesquisaj54_codigo(false);'")
?>
       <?
db_input('j14_nome',40,$Ij14_nome,true,'text',3,'')
       ?>
    <td>
  <tr>


  <tr>
    <td nowrap title="<?=@$Tj54_distan?>">
       <?=@$Lj54_distan?>
    </td>
    <td>
<?
db_input('j54_distan',5,$Ij54_distan,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj54_orientacao?>">
		  <b>Ponto de Orientação</b>
    </td>
    <td>
       <?
$matriz = array('0'=>"...",'leste'=>"Leste",'oeste'=>"Oeste",'norte'=>"Norte",'sul'=>"Sul",'nordeste'=>"Nordeste",'Sudoeste'=>"Sudoeste",'noroeste'=>"Noroeste",'sudeste'=>"Sudeste");
db_select('j54_ponto',$matriz,true,$db_opcao);

?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> <?=($db_opcao!=3?"onclick=\"return js_checa()\"":"")?>>
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaj34_loteam(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_loteam.php?funcao_js=parent.js_mostraloteam1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_loteam.php?pesquisa_chave='+document.form1.j34_loteam.value+'&funcao_js=parent.js_mostraloteam';
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

function js_mostracaracteristica(){
  caracteristica=document.form1.caracteristica.value;
   if(caracteristica!=""){
     db_iframe.jan.location.href = 'cad1_cargeral001.php?nomeiframe=db_iframe&nomeobj=caracteristica&db_opcao=<?=$db_opcao?>&caracteristica='+caracteristica+'&tipogrupo=L&codigo='+document.form1.j34_idbql.value;
   }else{
    db_iframe.jan.location.href = 'cad1_cargeral001.php?nomeiframe=db_iframe&nomeobj=caracteristica&db_opcao=<?=$db_opcao?>&tipogrupo=L&codigo='+document.form1.j34_idbql.value;
   }
   db_iframe.setTitulo('Pesquisa Caracteristica');
   db_iframe.mostraMsg();
   db_iframe.show();
    db_iframe.focus();
}

function js_pesquisaj54_codigo(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_ruas.php?funcao_js=parent.js_mostraruas1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_ruas.php?pesquisa_chave='+document.form1.j54_codigo.value+'&funcao_js=parent.js_mostraruas';
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
  j34_quadra  = document.form1.j34_quadra.value;
  if(j34_setor==""||j34_quadra==""){
    alert("Informe o código do setor e quadra!");
    return;
  }
  if(matrizvolta!=""){
    js_OpenJanelaIframe('top.corpo','db_iframe','cad1_testadainter001.php?j34_setor='+j34_setor+'&j34_quadra='+j34_quadra+'&db_opcao=<?=$db_opcao?>&matrizvolta='+matrizvolta+'&testa='+j34_idbql+'&mostranum=<?=$numerotestada?>','Pesquisa',true,0);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe','cad1_testadainter001.php?j34_setor='+j34_setor+'&j34_quadra='+j34_quadra+'&db_opcao=1&testa='+j34_idbql+'&mostranum=<?=$numerotestada?>','Pesquisa',true,0);
  }
}
 function js_testada(){

   j34_idbql=document.form1.j34_idbql.value;
   matrizvolta=document.form1.cartestada.value;
   principal=document.form1.cartestpri.value;
   j34_setor=document.form1.j34_setor.value;
   j34_quadra=document.form1.j34_quadra.value;
  if(j34_setor==""||j34_quadra==""){
     alert("Informe o c?digo do setor e quadra!");
     return;
  }
   if(matrizvolta!=""){
         db_iframe.jan.location.href = 'cad1_testada004.php?j34_setor='+j34_setor+'&j34_quadra='+j34_quadra+'&db_opcao=<?=$db_opcao?>&voltapri='+principal+'&matrizvolta='+matrizvolta+'&testa='+j34_idbql+'&mostranum=<?=$numerotestada?>';
   }else{
         db_iframe.jan.location.href = 'cad1_testada004.php?j34_setor='+j34_setor+'&j34_quadra='+j34_quadra+'&db_opcao=1&testa='+j34_idbql+'&mostranum=<?=$numerotestada?>';
   }

   db_iframe.mostraMsg();
   db_iframe.show();
   db_iframe.focus();
}


function js_loteloc(tp){
 if(tp == 2){
   db_iframe_loteloc.hide();
 }else{
 j34_idbql     = document.form1.j34_idbql.value;
 j06_setorloc  = document.form1.j06_setorloc.value;
 j06_quadraloc = document.form1.j06_quadraloc.value;
 j06_lote      = document.form1.j06_lote.value;
 js_OpenJanelaIframe('top.corpo','db_iframe_loteloc','cad1_loteloc001.php?j06_idbql='+j34_idbql+'&j06_setorloc='+j06_setorloc+'&j06_quadraloc='+j06_quadraloc+'&j06_lote='+j06_lote+'&db_opcao=?>&db_botao=<?=$db_botao?>','Pesquisa',true);
 }
}

function js_lotedist(){
   db_iframe.jan.location.href = 'cad1_lotedist004.php?codigo='+document.form1.j34_idbql.value;
   db_iframe.setLargura(780);
   db_iframe.setAltura(410);
   db_iframe.mostraMsg();
   db_iframe.show();
   db_iframe.focus();
}
function js_pesquisaj34_setor(mostra){
  js_limpatestada()
  if(mostra==true){
    db_iframe.jan.location.href = 'func_setor.php?funcao_js=parent.js_mostrasetor1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe','func_setor.php?pesquisa_chave='+document.form1.j34_setor.value+'&funcao_js=parent.js_mostrasetor','Pesquisa',false);
    //db_iframe.jan.location.href = 'func_setor.php?pesquisa_chave='+document.form1.j34_setor.value+'&funcao_js=parent.js_mostrasetor';
  }
}
function js_mostrasetor(chave,erro){
  document.form1.j30_descr.value = chave;
  if(erro==true){
    document.form1.j34_setor.focus();
    document.form1.j34_setor.value = '';
  }else{
    document.form1.incluquadra.value="<?=$db_opcao?>";
    document.form1.submit();
  }
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
    js_OpenJanelaIframe('','db_iframe_bairro','func_bairro.php?funcao_js=parent.js_mostrabairro1|0|1','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_bairro','func_bairro.php?pesquisa_chave='+document.form1.j34_bairro.value+'&funcao_js=parent.js_mostrabairro','Pesquisa',false);
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
  db_iframe_bairro.hide();
}
function js_pesquisa(){
  db_iframe.jan.location.href = 'func_lote.php?funcao_js=parent.js_preenchepesquisa|j34_idbql';
  db_iframe.mostraMsg();
  db_iframe.show();
  db_iframe.focus();
}
function js_preenchepesquisa(chave){
  db_iframe.hide();
  location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+chave;
}

function js_pesquisaj91_codigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_setorfiscal','func_setorfiscal.php?funcao_js=parent.js_mostrasetorfiscal1|j90_codigo|j90_descr','Pesquisa',true);
  }else{
     if(document.form1.j91_codigo.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_setorfiscal','func_setorfiscal.php?pesquisa_chave='+document.form1.j91_codigo.value+'&funcao_js=parent.js_mostrasetorfiscal','Pesquisa',false);
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

</script>
<?
$func_iframe = new janela('db_iframe','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=780;
$func_iframe->altura=430;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();
if(isset($chavepesquisa)&& $db_opcao==2){
  if($confere==false){
     db_msgbox("Quadra $j34_quadra n?o cadastrada para o setor $j34_setor!\\n Contate suporte.");
     echo "
        <script>
	  parent.location.href='cad1_lote001.php';
        </script>
     ";
  }
}
?>