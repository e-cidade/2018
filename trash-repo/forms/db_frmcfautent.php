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

//MODULO: caixa
$clcfautent->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nomeinst");
$clcfautentconta->rotulo->label();
$clrotulo->label("k11_local");
$clrotulo->label("k13_descr");
$clrotulo->label("db03_docum");
$clrotulo->label("db03_descr");
$clrotulo->label("k39_documento");

if (@$k11_local == "") {
  $sql = "select nome from db_usuarios where id_usuario = ".db_getsession("DB_id_usuario");
  $result = db_query($sql);
  if (pg_numrows($result) > 0) {
    db_fieldsmemory($result,0);
    $k11_local = substr("MICRO DO USUARIO " . $nome,0,30);
  }
  $sql = "select nomeinst from db_config where codigo = " . db_getsession("DB_instit");
  $result = db_query($sql) or die($sql);
  if (pg_numrows($result) > 0) {
    db_fieldsmemory($result,0);
    $palavras = split(" ",$nomeinst);
    $conta=0;
    for ($i=0; $i < sizeof($palavras); $i++) {
      if ($palavras[$i] == "DE") {
          continue;
      }
      if ($conta == 0) {
        $k11_ident1 = substr($palavras[$i],0,1);
        $conta++;
      } else if ($conta == 1) {
        $k11_ident2 = substr($palavras[$i],0,1);
        $conta++;
      } else if ($conta == 2) {
        $k11_ident3 = substr($palavras[$i],0,1);
        $conta++;
      }
    }
  }
}

if(@$k11_ipterm == "") {

  if (strpos(db_getsession('DB_ip'),',')) {
    $k11_ipterm = substr(db_getsession('DB_ip'),0,strpos(db_getsession('DB_ip'),','));
  } else {
    $k11_ipterm = db_getsession('DB_ip');
  }
}

if(@$k11_ipimpcheque == "") {
  $k11_ipimpcheque=$k11_ipterm;
}

if(@$k11_aut1 == "") {
  $k11_aut1=1;
}
if(@$k11_aut2 == "") {
  $k11_aut2=1;
}
if(@$k11_tesoureiro == "") {
  $k11_tesoureiro="PREENCHA COM O NOME DO TESOUREIRO";
}
if(@$k11_portaimpcheque == "") {
  $k11_portaimpcheque=4444;
}

?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="">
    </td>
    <td> 
<?
db_input('k11_id',5,$Ik11_id,true,'hidden',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk11_ident1?>">
       <?=@$Lk11_ident1?>
    </td>
    <td> 
<?
db_input('k11_ident1',1,$Ik11_ident1,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk11_ident2?>">
       <?=@$Lk11_ident2?>
    </td>
    <td> 
<?
db_input('k11_ident2',1,$Ik11_ident2,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk11_ident3?>">
       <?=@$Lk11_ident3?>
    </td>
    <td> 
<?
db_input('k11_ident3',1,$Ik11_ident3,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk11_ipterm?>">
       <?=@$Lk11_ipterm?>
    </td>
    <td> 
<?
db_input('k11_ipterm',20,$Ik11_ipterm,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk11_local?>">
       <?=@$Lk11_local?>
    </td>
    <td> 
<?
db_input('k11_local',30,$Ik11_local,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk16_conta?>">
       <?
       db_ancora(@$Lk16_conta,"js_pesquisak16_conta(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('k16_conta',5,$Ik16_conta,true,'text',$db_opcao," onchange='js_pesquisak16_conta(false);'")
?>
       <?
db_input('k13_descr',40,$Ik13_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk11_aut1?>">
       <?=@$Lk11_aut1?>
    </td>
    <td> 
<?
db_input('k11_aut1',20,$Ik11_aut1,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk11_aut2?>">
       <?=@$Lk11_aut2?>
    </td>
    <td> 
<?
db_input('k11_aut2',20,$Ik11_aut2,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk11_tipautent?>">
       <?=@$Lk11_tipautent?>
    </td>
    <td> 
<?
$x = array('1'=>'Autentica e Imprime','2'=>'Autentica e não Imprime','3'=>'Não Autentica e Não Imprime (Somente Empenho)');
db_select('k11_tipautent',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk11_tesoureiro?>">
       <?=@$Lk11_tesoureiro?>
    </td>
    <td> 
<?
db_input('k11_tesoureiro',40,$Ik11_tesoureiro,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk11_instit?>">
       <?
       //db_ancora(@$Lk11_instit,"js_pesquisak11_instit(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
$k11_instit = db_getsession('DB_instit');
db_input('k11_instit',3,$Ik11_instit,true,'hidden',$db_opcao,"")
?>
       <?
db_input('nomeinst',80,$Inomeinst,true,'hidden',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk11_tipoimp?>">
       <?
       	db_ancora($Lk11_tipoimp,"js_consultaImpressora(true)",$db_opcao);
         
       ?>
    </td>
    <td> 
			<?
				db_input('k11_tipoimp',10,$Ik11_tipoimp,true,'text',$db_opcao,"onChange='js_consultaImpressora(false);'");
				db_input('tipoimpdescr',40,"",true,'text',3,"");
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk11_tipoimpcheque?>">
       <?
       	db_ancora($Lk11_tipoimpcheque,"js_consultaImpressoraCheque(true)",$db_opcao);
       ?>
    </td>
    <td> 
			<?
				db_input('k11_tipoimpcheque',10,$Ik11_tipoimpcheque,true,'text',$db_opcao,"onChange='js_consultaImpressoraCheque(false);'");
			 	db_input('tipoimpchequedescr',40,"",true,'text',3,"");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk11_ipimpcheque?>">
       <?=@$Lk11_ipimpcheque?>
    </td>
    <td> 
<?
db_input('k11_ipimpcheque',20,$Ik11_ipimpcheque,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk11_portaimpcheque?>">
       <?=@$Lk11_portaimpcheque?>
    </td>
    <td> 
<?
db_input('k11_portaimpcheque',5,$Ik11_portaimpcheque,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk11_impassche?>">
       <b>Imprime Assinatura nos Cheques:</b>
    </td>
    <td> 
<?
//echo "k11_impassche = $k11_impassche";
$x = array('2'=>'Não','1'=>'Sim');
db_select('k11_impassche',$x,true,$db_opcao,"onChange = 'js_mostra(this);'");


?>
    </td>
  </tr>



  <tr>
    <td nowrap title="<?=@$Tk11_zeratrocoarrec?>">
       <?=@$Lk11_zeratrocoarrec?>
    </td>
    <td> 
<?
$x = array('1'=>'Zera troco quando autenticar','2'=>'Não zera troco quando autenticar');
db_select('k11_zeratrocoarrec',$x,true,$db_opcao,"");
?>
    </td>
  </tr>




  <tr id='doc' style='visibility:hidden;'>
    <td nowrap >
       <?
       db_ancora(@$Lk39_documento,"js_pesquisadb03_docum(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
if(isset($k11_impassche) && $k11_impassche==1){
  $sqlchq = "select db03_docum,db03_descr 
             from cfautentdocasschq 
             inner join db_documento on k39_documento=db03_docum 
             where k39_cfautent = $k11_id";
  $resultchq = db_query($sqlchq);
  $linhaschq= pg_num_rows($resultchq);
  if($linhaschq>0){
    db_fieldsmemory($resultchq,0);
  }
}
db_input('db03_docum',5,$Idb03_docum,true,'text',$db_opcao," onchange='js_pesquisadb03_docum(false);'");
db_input('db03_descr',40,$Idb03_descr,true,'text',3,'');
       ?>
    </td>
  </tr>
  <?
   if(isset($k11_impassche) && $k11_impassche==1){
     echo "<script> document.getElementById('doc').style.visibility = 'visible'; </script>";
   }
 ?> 
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisak11_instit(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_db_config','func_db_config.php?funcao_js=parent.js_mostradb_config1|codigo|nomeinst','Pesquisa',true);
  }else{
     if(document.form1.k11_instit.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_db_config','func_db_config.php?pesquisa_chave='+document.form1.k11_instit.value+'&funcao_js=parent.js_mostradb_config','Pesquisa',false);
     }else{
       document.form1.nomeinst.value = ''; 
     }
  }
}
function js_mostradb_config(chave,erro){
  document.form1.nomeinst.value = chave; 
  if(erro==true){ 
    document.form1.k11_instit.focus(); 
    document.form1.k11_instit.value = ''; 
  }
}
function js_mostradb_config1(chave1,chave2){
  document.form1.k11_instit.value = chave1;
  document.form1.nomeinst.value = chave2;
  db_iframe_db_config.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_cfautent','func_cfautent.php?funcao_js=parent.js_preenchepesquisa|k11_id','Pesquisa',true,0,0);
}
function js_preenchepesquisa(chave){
  db_iframe_cfautent.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
function js_pesquisak16_conta(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_saltes','func_saltes.php?funcao_js=parent.js_mostrasaltes1|k13_conta|k13_descr','Pesquisa',true);
  }else{
     if(document.form1.k16_conta.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_saltes','func_saltes.php?pesquisa_chave='+document.form1.k16_conta.value+'&funcao_js=parent.js_mostrasaltes','Pesquisa',false);
     }else{
       document.form1.k13_descr.value = ''; 
     }
  }
}
function js_mostrasaltes(chave,erro){
  document.form1.k13_descr.value = chave; 
  if(erro==true){ 
    document.form1.k16_conta.focus(); 
    document.form1.k16_conta.value = ''; 
  }
}
function js_mostrasaltes1(chave1,chave2){
  document.form1.k16_conta.value = chave1;
  document.form1.k13_descr.value = chave2;
  db_iframe_saltes.hide();
}


function js_pesquisadb03_docum(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_db_documento','func_db_documento.php?funcao_js=parent.js_mostradoc|db03_docum|db03_descr','Pesquisa',true);
  }else{
     if(document.form1.k11_instit.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_db_documento','func_db_documento.php?pesquisa_chave='+document.form1.db03_docum.value+'&funcao_js=parent.js_mostradoc1','Pesquisa',false);
     }else{
       document.form1.nomeinst.value = ''; 
     }
  }
}
function js_mostradoc1(chave,erro){
  document.form1.db03_descr.value = chave; 
  if(erro==true){ 
    document.form1.db03_docum.focus(); 
    document.form1.db03_docum.value = ''; 
  }
}
function js_mostradoc(chave1,chave2){
  document.form1.db03_docum.value = chave1;
  document.form1.db03_descr.value = chave2;
  db_iframe_db_documento.hide();
}


function js_mostra(mostra){

  if(mostra.value == '1'){
    document.getElementById('doc').style.visibility = 'visible';
  }else{
    document.getElementById('doc').style.visibility = 'hidden';
  }
}

function js_consultaImpressora(mostra){
  if(mostra == true){
    js_OpenJanelaIframe('','db_iframe_impressora','func_db_impressora.php?verifica_tipo=1,3,4&funcao_js=parent.js_preencheimpressora|db64_sequencial|db64_nome','pesquisa',true,0,0);
  }else{
    js_OpenJanelaIframe('','db_iframe_impressora','func_db_impressora.php?verifica_tipo=1,3,4&funcao_js=parent.js_preencheimpressora1&pesquisa_chave='+document.form1.k11_tipoimp.value,'pesquisa',false,0,0);
  }
}

function js_preencheimpressora(chave,chave1){
  document.form1.k11_tipoimp.value 	= chave;
  document.form1.tipoimpdescr.value = chave1;
  db_iframe_impressora.hide();
}

function js_preencheimpressora1(chave,erro){
  document.form1.tipoimpdescr.value = chave;
  if(erro == true){
    document.form1.k11_tipoimp.focus();
    document.form1.k11_tipoimp.value='';
  }
  db_iframe_impressora.hide();
}

function js_consultaImpressoraCheque(mostra){
  if(mostra == true){
    js_OpenJanelaIframe('','db_iframe_impressoraCheque','func_db_impressora.php?verifica_tipo=2&funcao_js=parent.js_preencheimpressoraCheque|db64_sequencial|db64_nome','pesquisa',true,0,0);
  }else{
    js_OpenJanelaIframe('','db_iframe_impressoraCheque','func_db_impressora.php?verifica_tipo=2&funcao_js=parent.js_preencheimpressoraCheque1&pesquisa_chave='+document.form1.k11_tipoimpcheque.value,'pesquisa',false,0,0);
  }
}

function js_preencheimpressoraCheque(chave,chave1){
  document.form1.k11_tipoimpcheque.value 	= chave;
  document.form1.tipoimpchequedescr.value = chave1;
  db_iframe_impressoraCheque.hide();
}

function js_preencheimpressoraCheque1(chave,erro){
  document.form1.tipoimpchequedescr.value = chave;
  if(erro == true){
    document.form1.k11_tipoimpcheque.focus();
    document.form1.k11_tipoimpcheque.value='';
  }
  db_iframe_impressoraCheque.hide();
}
</script>