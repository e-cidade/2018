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

//MODULO: agua
$claguacorte->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nome");
$clrotulo->label("j50_descr");
$clrotulo->label("j14_nome");
$clrotulo->label("j85_ender");
$clrotulo->label("x43_descr");
$clrotulo->label("j50_descr");

if ($db_opcao==1) {
  $db_action="agu1_aguacorte004.php";
} else if ($db_opcao==2||$db_opcao==22) {
  $db_action="agu1_aguacorte005.php";
} else if ($db_opcao==3||$db_opcao==33) {
  $db_action="agu1_aguacorte006.php";
}

?>
<form name="form1" method="post" action="<?=$db_action?>">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tx40_codcorte?>">
       <?=@$Lx40_codcorte?>
    </td>
    <td> 
<?
db_input('x40_codcorte',10,$Ix40_codcorte,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tx40_dtinc?>">
       <?=@$Lx40_dtinc?>
    </td>
    <td> 
<?
db_inputdata('x40_dtinc',@$x40_dtinc_dia,@$x40_dtinc_mes,@$x40_dtinc_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  

  <tr>
    <td nowrap title="<?=@$Tx40_anoini?>">
       <?=@$Lx40_anoini?>
    </td>
    <td> 
<?
db_input('x40_anoini',4,$Ix40_anoini,true,'text',$db_opcao," onblur='js_ValidaAnos();'")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tx40_anofim?>">
       <?=@$Lx40_anofim?>
    </td>
    <td> 
<?
db_input('x40_anofim',4,$Ix40_anofim,true,'text',$db_opcao," onblur='js_ValidaAnos();'")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tx40_entrega?>">
       <?
       //=@$Lx40_entrega
       db_ancora(@$Lx40_entrega,"js_pesquisax40_entrega(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('x40_entrega',3,$Ix40_entrega,true,'text',$db_opcao," onchange='js_pesquisax40_entrega(false);'");

db_input('j85_ender',60,$Ij85_ender,true,'text',3,'');
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tx40_rua?>">
       <?
       db_ancora(@$Lx40_rua,"js_pesquisax40_rua(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('x40_rua',7,$Ix40_rua,true,'text',$db_opcao," onchange='js_pesquisax40_rua(false);'")
?>
       <?
db_input('j14_nome',40,$Ij14_nome,true,'text',3,'')
       ?>
    </td>
  </tr>

  <tr>
    <td nowrap title="<?=@$Tx40_codsituacao?>">
       <?
       db_ancora(@$Lx40_codsituacao,"js_pesquisax40_codsituacao(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('x40_codsituacao',5,$Ix40_codsituacao,true,'text',$db_opcao," onchange='js_pesquisax40_codsituacao(false);'");

db_input('x43_descr',40,$Ix43_descr,true,'text',3,'');
?>
    </td>
  </tr>

  
  <tr>
    <td nowrap title="<?=@$Tx40_vlrminimo?>">
       <?=@$Lx40_vlrminimo?>
    </td>
    <td> 
<?
db_input('x40_vlrminimo',10,$Ix40_vlrminimo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <!--
  <tr>
    <td nowrap title="<?=@$Tx40_sql?>">
       <?//=@$Lx40_sql?>
    </td>
    <td> 
<?
//db_textarea('x40_sql',0,0,$Ix40_sql,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  -->

  <tr>
    <td nowrap title="<?=@$Tx40_zona?>">
       <?
       db_ancora(@$Lx40_zona,"js_pesquisax40_zona(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('x40_zona',4,$Ix40_zona,true,'text',$db_opcao," onchange='js_pesquisax40_zona(false);'")
?>
       <?
db_input('j50_descr',40,$Ij50_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
	<tr>
		<td nowrap title="<?=@$Tx40_tipomatricula?>">
       <?=@$Lx40_tipomatricula?>
		</td>
		<td>
		<?			
			if ($db_opcao == 22 || $db_opcao == 2){
				$dbopcao = 3; 
			}else{
				$dbopcao = $db_opcao;
			}
			
			$aAcoes = array("1"=>"Todos","2"=>"Territoriais","3"=>"Prediais");
			db_select('x40_tipomatricula',$aAcoes,true,$dbopcao);
		?>
		</td>
		
	</tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>

function js_pesquisax40_codsituacao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_aguacorte','db_iframe_aguacortesituacao','func_aguacortesituacao.php?funcao_js=parent.js_mostraaguacortesituacao1|x43_codsituacao|x43_descr','Pesquisa',true,5);
  }else{
     if(document.form1.x40_codsituacao.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_aguacorte','db_iframe_aguacortesituacao','func_aguacortesituacao.php?pesquisa_chave='+document.form1.x40_codsituacao.value+'&funcao_js=parent.js_mostraaguacortesituacao','Pesquisa',false);
     }else{
       document.form1.x43_descr.value = ''; 
     }
  }
}

function js_mostraaguacortesituacao(chave,erro){
  document.form1.x43_descr.value = chave; 
  if(erro==true){ 
    document.form1.x40_codsituacao.focus(); 
    document.form1.x40_codsituacao.value = ''; 
  }
}

function js_mostraaguacortesituacao1(chave1,chave2){
  document.form1.x40_codsituacao.value = chave1;
  document.form1.x43_descr.value = chave2;
  db_iframe_aguacortesituacao.hide();
}

function js_pesquisax40_entrega(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_aguacorte','db_iframe_iptucadzonaentrega','func_iptucadzonaentrega.php?funcao_js=parent.js_mostraiptucadzonaentrega1|j85_codigo|j85_ender','Pesquisa',true);
  }else{
     if(document.form1.x40_entrega.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_aguacorte','db_iframe_iptucadzonaentrega','func_iptucadzonaentrega.php?pesquisa_chave='+document.form1.x40_entrega.value+'&funcao_js=parent.js_mostraiptucadzonaentrega','Pesquisa',false);
     }else{
       document.form1.j85_ender.value = ''; 
     }
  }
}

function js_mostraiptucadzonaentrega(chave,erro){
  document.form1.j85_ender.value = chave; 
  if(erro==true){ 
    document.form1.x40_entrega.focus(); 
    document.form1.x40_entrega.value = ''; 
  }
}
function js_mostraiptucadzonaentrega1(chave1,chave2){
  document.form1.x40_entrega.value = chave1;
  document.form1.j85_ender.value = chave2;
  db_iframe_iptucadzonaentrega.hide();
}


function js_pesquisax40_rua(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_aguacorte','db_iframe_ruas','func_ruas.php?funcao_js=parent.js_mostraruas1|j14_codigo|j14_nome','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.x40_rua.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_aguacorte','db_iframe_ruas','func_ruas.php?pesquisa_chave='+document.form1.x40_rua.value+'&funcao_js=parent.js_mostraruas','Pesquisa',false,'0','1','775','390');
     }else{
       document.form1.j14_nome.value = ''; 
     }
  }
}
function js_mostraruas(chave,erro){
  document.form1.j14_nome.value = chave; 
  if(erro==true){ 
    document.form1.x40_rua.focus(); 
    document.form1.x40_rua.value = ''; 
  }
}
function js_mostraruas1(chave1,chave2){
  document.form1.x40_rua.value = chave1;
  document.form1.j14_nome.value = chave2;
  db_iframe_ruas.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_aguacorte','db_iframe_aguacorte','func_aguacorte.php?funcao_js=parent.js_preenchepesquisa|x40_codcorte','Pesquisa',true,5);
}
function js_ValidaAnos(){
  if(document.form1.x40_anofim.value == '') {
    document.form1.x40_anofim.value = document.form1.x40_anoini.value;
  }

  if(document.form1.x40_anoini.value != '' && document.form1.x40_anofim.value != '') {
    if(document.form1.x40_anoini.value > document.form1.x40_anofim.value) {  
      alert("Ano Inicial nao pode ser maior que Ano Final!");
      document.form1.x40_anoini.focus();
      document.form1.x40_anoini.value = '';
    }
  }
}

function js_pesquisax40_zona(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_aguacorte','db_iframe_zonas','func_zonas.php?funcao_js=parent.js_mostrazonas1|j50_zona|j50_descr','Pesquisa',true);
  }else{
     if(document.form1.x40_zona.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_aguacorte','db_iframe_zonas','func_zonas.php?pesquisa_chave='+document.form1.x40_zona.value+'&funcao_js=parent.js_mostrazonas','Pesquisa',false);
     }else{
       document.form1.j50_descr.value = ''; 
     }
  }
}
function js_mostrazonas(chave,erro){
  document.form1.j50_descr.value = chave; 
  if(erro==true){ 
    document.form1.x40_zona.focus(); 
    document.form1.x40_zona.value = ''; 
  }
}
function js_mostrazonas1(chave1,chave2){
  document.form1.x40_zona.value = chave1;
  document.form1.j50_descr.value = chave2;
  db_iframe_zonas.hide();
}

function js_preenchepesquisa(chave){
  db_iframe_aguacorte.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}

</script>