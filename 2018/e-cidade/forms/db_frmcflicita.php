<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

//MODULO: licitação
$clcflicita->rotulo->label();
$clpctipocompratribunal->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("pc50_descr");
$clrotulo->label("nomeinst");

if ($db_opcao == 1) {
 	$db_action="lic1_cflicita004.php";
} else if ($db_opcao == 2 || $db_opcao == 22) {	
 	$db_action="lic1_cflicita005.php";
} else if ($db_opcao == 3 || $db_opcao == 33) {
 	$db_action="lic1_cflicita006.php";
}  
?>
<style>
td {
  white-space: nowrap
}

fieldset table td:first-child {
  width: 150px;
  white-space: nowrap
}
</style>
<form name="form1" method="post" action="<?=$db_action?>">
<fieldset>
<legend><b>Modalidades</b></legend>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tl03_codigo?>">
      <?=@$Ll03_codigo?>
    </td>
    <td> 
			<?
			  db_input('l03_codigo',8,$Il03_codigo,true,'text',3,"")
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tl03_descr?>">
      <?=@$Ll03_descr?>
    </td>
    <td> 
			<?
			  db_input('l03_descr',40,$Il03_descr,true,'text',$db_opcao,"")
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tl03_tipo?>">
      <?=@$Ll03_tipo?>
    </td>
    <td> 
			<?
			  db_input('l03_tipo',8,$Il03_tipo,true,'text',$db_opcao,"")
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tl03_codcom?>">
       <?
         db_ancora(@$Ll03_codcom,"js_pesquisal03_codcom(true);",$db_opcao);
       ?>
    </td>
    <td> 
			<?
			  db_input('l03_codcom',8,$Il03_codcom,true,'text',$db_opcao," onchange='js_pesquisal03_codcom(false);'")
			?>
      <?
        db_input('pc50_descr',40,$Ipc50_descr,true,'text',3,'')
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tl03_instit?>">
       <?
       db_ancora(@$Ll03_instit,"js_pesquisal03_instit(true);",$db_opcao);
       ?>
    </td>
    <td> 
			<?
			  db_input('l03_instit',8,$Il03_instit,true,'text',$db_opcao," onchange='js_pesquisal03_instit(false);'")
			?>
      <?
        db_input('nomeinst',40,$Inomeinst,true,'text',3,'')
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tl03_pctipocompratribunal?>">
       <?=@$Ll03_pctipocompratribunal?>
    </td>
    <td> 
      <?
        $oDaoDbConfig              = db_utils::getDao("db_config");
        $sWhere                    = "codigo = {$iInstit}";
        $sSqlDbConfig              = $oDaoDbConfig->sql_query_file(null, "uf", null, $sWhere);
        $rsSqlDbConfig             = $oDaoDbConfig->sql_record($sSqlDbConfig);
        
        $aPcTipoCompraTribunal[0]  = "Selecione";
        if ($oDaoDbConfig->numrows > 0) {
          
          $oDbConfig                 = db_utils::fieldsMemory($rsSqlDbConfig, 0);
          
          $oDaoPcTipoCompraTribunal  = db_utils::getDao("pctipocompratribunal");
          $sWhere                    = "l44_uf = '{$oDbConfig->uf}'";
          $sSqlPcTipoCompraTribunal  = $oDaoPcTipoCompraTribunal->sql_query_file(null, "*", "l44_sequencial", $sWhere);
          $rsSqlPcTipoCompraTribunal = $oDaoPcTipoCompraTribunal->sql_record($sSqlPcTipoCompraTribunal);
          $aPcTipoCompraTribunal[0]  = "Selecione";
          for($i = 0; $i < $oDaoPcTipoCompraTribunal->numrows; $i ++) {
              
            $oPcTipoCompraTribunal = db_utils::fieldsMemory($rsSqlPcTipoCompraTribunal, $i);
            $aPcTipoCompraTribunal[$oPcTipoCompraTribunal->l44_sequencial] = $oPcTipoCompraTribunal->l44_descricao;
          
          }
        }
        
        db_select('l03_pctipocompratribunal', $aPcTipoCompraTribunal, true, $db_opcao, "onchange='js_desabilitaSelecionar();'");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tl03_usaregistropreco?>">
      <?=@$Ll03_usaregistropreco?>
    </td>
    <td>
      <?
        db_select("l03_usaregistropreco",array("t"=>"Sim", "f"=>"Não"),true,$db_opcao);
      ?>
    </td>
  </tr>
  </table>
</fieldset>
<table>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td>
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
             type="submit" id="db_opcao" 
             value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
             <?=($db_botao==false?"disabled":"")?>>
    </td>
    <td>
      <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">
    </td>
  </tr>
</table>
</form>
<script>
function js_pesquisal03_codcom(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_cflicita','db_iframe_pctipocompra','func_pctipocompra.php?funcao_js=parent.js_mostrapctipocompra1|pc50_codcom|pc50_descr','Pesquisa',true,0);
  }else{
     if(document.form1.l03_codcom.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_cflicita','db_iframe_pctipocompra','func_pctipocompra.php?pesquisa_chave='+document.form1.l03_codcom.value+'&funcao_js=parent.js_mostrapctipocompra','Pesquisa',false);
     }else{
       document.form1.pc50_descr.value = ''; 
     }
  }
}
function js_mostrapctipocompra(chave,erro){
  document.form1.pc50_descr.value = chave; 
  if(erro==true){ 
    document.form1.l03_codcom.focus(); 
    document.form1.l03_codcom.value = ''; 
  }
}
function js_mostrapctipocompra1(chave1,chave2){
  document.form1.l03_codcom.value = chave1;
  document.form1.pc50_descr.value = chave2;
  db_iframe_pctipocompra.hide();
}
function js_pesquisal03_instit(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_cflicita','db_iframe_db_config','func_db_config.php?funcao_js=parent.js_mostradb_config1|codigo|nomeinst','Pesquisa',true,0);
  }else{
     if(document.form1.l03_instit.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_cflicita','db_iframe_db_config','func_db_config.php?pesquisa_chave='+document.form1.l03_instit.value+'&funcao_js=parent.js_mostradb_config','Pesquisa',false);
     }else{
       document.form1.nomeinst.value = ''; 
     }
  }
}
function js_mostradb_config(chave,erro){
  document.form1.nomeinst.value = chave; 
  if(erro==true){ 
    document.form1.l03_instit.focus(); 
    document.form1.l03_instit.value = ''; 
  }
}
function js_mostradb_config1(chave1,chave2){
  document.form1.l03_instit.value = chave1;
  document.form1.nomeinst.value = chave2;
  db_iframe_db_config.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_cflicita','db_iframe_cflicita','func_cflicita.php?funcao_js=parent.js_preenchepesquisa|l03_codigo','Pesquisa',true,0);
}
function js_preenchepesquisa(chave){
  db_iframe_cflicita.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}

document.form1.l03_descr.style.width                          = '100%';
if (document.form1.l03_usaregistropreco != undefined) {

  document.form1.l03_usaregistropreco.style.width               = '20%';
  document.form1.l03_usaregistropreco.style.size                = '8';
}

if (document.form1.l03_usaregistropreco_select_descr != undefined) {

  document.form1.l03_usaregistropreco_select_descr.style.width  = '20%';
  document.form1.l03_usaregistropreco_select_descr.style.size   = '8';
}

function js_desabilitaSelecionar() {

  var iCodigoTipoCompraTribunal = $('l03_pctipocompratribunal').value;
  if (iCodigoTipoCompraTribunal != 0) {
    $('l03_pctipocompratribunal').options[0].disabled = true; 
  }
}
</script>