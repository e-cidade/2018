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

//MODULO: empenho
$clretencaotiporec->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("e32_descrição");
$clrotulo->label("e30_codigo");
$clrotulo->label("k02_descr");
$clrotulo->label("z01_nome");
$clrotulo->label("e48_cgm");
?>
<form name="form1" method="post" action="">
<center>
<table>
  <tr>
    <td>
      <fieldset>
        <legend><b>Cadastro de Retenção</b></legend>
			    <table border="0">
					  <tr>
					    <td nowrap title="<?=@$Te21_sequencial?>">
					      <?=@$Le21_sequencial?>
					    </td>
					    <td> 
								<?
								  db_input('e21_sequencial',10,$Ie21_sequencial,true,'text',3,"")
								?>
					    </td>
					  </tr>
					  <tr>
					    <td nowrap title="<?=@$Te21_retencaotipocalc?>">
					       <?=@$Le21_retencaotipocalc?>
					    </td>
					    <td> 
					       <?
					       include("classes/db_retencaotipocalc_classe.php");
					       $clretencaotipocalc = new cl_retencaotipocalc;
					       $result = $clretencaotipocalc->sql_record($clretencaotipocalc->sql_query("","*",""));
					       db_selectrecord("e21_retencaotipocalc",$result,true,$db_opcao,'','','', '',"js_mostraCodigoIRRF(this.value)");
					       ?>
					    </td>
					  </tr>
		        <tr>
		          <td nowrap title="<?=@$Te21_retencaotiporecgrupo?>">
		             <?=@$Le21_retencaotiporecgrupo?>
		          </td>
		          <td> 
		             <?
		             include("classes/db_retencaotiporecgrupo_classe.php");
		             $clretencaotiporecgrupo = new cl_retencaotiporecgrupo;
		             $result = $clretencaotiporecgrupo->sql_record($clretencaotiporecgrupo->sql_query("","*",""));
		             db_selectrecord("e21_retencaotiporecgrupo",$result,true,$db_opcao,'');
		             ?>
		          </td>
		        </tr>			  
					  <tr>
					    <td nowrap title="<?=@$Te21_receita?>">
					       <?
					       db_ancora(@$Le21_receita,"js_pesquisae21_receita(true);",$db_opcao);
					       ?>
					    </td>
					    <td> 
								<?
								  db_input('e21_receita',10,$Ie21_receita,true,'text',$db_opcao," onchange='js_pesquisae21_receita(false);'");
		   		        db_input('k02_descr',30,$Ik02_descr,true,'text',3,'');
					      ?>
					    </td>
			      </tr>
					  <tr>
					    <td nowrap title="<?=@$Te21_descricao?>">
					       <?=@$Le21_descricao?>
					    </td>
					    <td> 
								<?
								  db_input('e21_descricao',43,$Ie21_descricao,true,'text',$db_opcao,"")
								?>
					    </td>
			      </tr>
			      <tr>
			        <td nowrap title="<?=@$Te21_aliquota?>">
			          <?=@$Le21_aliquota?>
			        </td>
			        <td> 
								<?
							  	db_input('e21_aliquota',10,$Ie21_aliquota,true,'text',$db_opcao,"")
								?>
			        </td>
	     		  </tr>
            <tr id='codigoirrf'>
              <td nowrap title="<?=@$Te30_codigo?>">
			          <?
			            db_ancora(@$Le30_codigo,"js_pesquisae21_retencaonatureza(true);",$db_opcao);
		   	        ?>
    			    </td>
              <td>
                <?
                  db_input('e31_retencaonatureza',10,0,true,'hidden',$db_opcao,"");
                  db_input('e30_codigo',10,$Ie30_codigo,true,'text',$db_opcao,"onchange='js_pesquisae21_retencaonatureza(false);'");
                  db_input('e30_descricao',30,$Ie30_codigo,true,'text',3,"");
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$Te30_codigo?>">
                <?
                  db_ancora(@$Le48_cgm,"js_pesquisae48_cgm(true);",$db_opcao);
                ?>
              </td>
              <td>
                <?
                  db_input('e48_cgm' ,10,$Ie48_cgm,true,'text',$db_opcao,"onchange='js_pesquisae48_cgm(false);'");
                  db_input('z01_nome',30,'',true,'text',3,"");
                ?>
              </td>
            </tr>              
			    </table>
        </fieldset>
      </td>
    </tr>
  </table>      
</center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" onclick="return js_validaCadastro()" 
  type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>

function js_validaCadastro() {

  var iCodigoCalculo = document.getElementById('e21_retencaotipocalc').value;
  if (iCodigoCalculo == 1 || iCodigoCalculo == 2) {
  
    if (document.getElementById('e31_retencaonatureza').value == "") {
      
      alert('Informe o Código do IRRF');
      return false;
      
    }
  }
  return true;
}
function js_pesquisae21_retencaotipocalc(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_retencaotipocalc','func_retencaotipocalc.php?funcao_js=parent.js_mostraretencaotipocalc1|e32_sequencial|e32_descrição','Pesquisa',true);
  }else{
     if(document.form1.e21_retencaotipocalc.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_retencaotipocalc','func_retencaotipocalc.php?pesquisa_chave='+document.form1.e21_retencaotipocalc.value+'&funcao_js=parent.js_mostraretencaotipocalc','Pesquisa',false);
     }else{
       document.form1.e32_descrição.value = ''; 
     }
  }
}
function js_mostraretencaotipocalc(chave,erro){
  document.form1.e32_descrição.value = chave; 
  if(erro==true){ 
    document.form1.e21_retencaotipocalc.focus(); 
    document.form1.e21_retencaotipocalc.value = ''; 
  }
}
function js_mostraretencaotipocalc1(chave1,chave2){
  document.form1.e21_retencaotipocalc.value = chave1;
  document.form1.e32_descrição.value = chave2;
  db_iframe_retencaotipocalc.hide();
}

function js_pesquisae21_retencaonatureza(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_retencaonatureza',
                                    'func_retencaonatureza.php?funcao_js=parent.js_mostraretencaonatureza1|e30_sequencial|e30_descricao|e30_codigo', 
                                    'Codigos do IRRF',true);
  }else{
     if(document.form1.e30_codigo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_retencaonatureza',
                            'func_retencaonatureza.php?pesquisa_chave='+document.form1.e30_codigo.value+'&funcao_js=parent.js_mostraretencaonatureza&keyfield=e30_codigo',
                            'Pesquisa',false);
     }else{
     
       document.form1.e30_descricao.value        = ''; 
       document.form1.e31_retencaonatureza.value = '';
     }
  }
}

function js_mostraretencaonatureza(chave,erro,e30_sequencial){
  document.form1.e30_descricao.value = chave; 
  if (erro==true) {
   
    document.form1.e30_codigo.value           = '';
    document.form1.e31_retencaonatureza.value = '';
    document.form1.e30_codigo.focus(); 
     
  } else {
    
    document.form1.e31_retencaonatureza.value = e30_sequencial;
  
  }
}

function js_mostraretencaonatureza1(chave1, chave2, chave3) {

  document.form1.e31_retencaonatureza.value = chave1;
  document.form1.e30_descricao.value        = chave2;
  document.form1.e30_codigo.value           = chave3;
  db_iframe_retencaonatureza.hide();
  
}
function js_pesquisae21_receita(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_tabrec','func_tabrec.php?funcao_js=parent.js_mostratabrec1|k02_codigo|k02_descr','Pesquisa',true);
  }else{
     if(document.form1.e21_receita.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_tabrec','func_tabrec.php?pesquisa_chave='+document.form1.e21_receita.value+'&funcao_js=parent.js_mostratabrec','Pesquisa',false);
     }else{
       document.form1.k02_descr.value = ''; 
     }
  }
}
function js_mostratabrec(chave,erro){
  document.form1.k02_descr.value = chave; 
  if(erro==true){ 
    document.form1.e21_receita.focus(); 
    document.form1.e21_receita.value = ''; 
  }
}
function js_mostratabrec1(chave1,chave2){
  document.form1.e21_receita.value = chave1;
  document.form1.k02_descr.value = chave2;
  db_iframe_tabrec.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_retencaotiporec','func_retencaotiporec.php?funcao_js=parent.js_preenchepesquisa|e21_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_retencaotiporec.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}

function js_mostraCodigoIRRF(iTipoCalc) {

  if (iTipoCalc == 1 || iTipoCalc == 2) {
    document.getElementById('codigoirrf').style.display = "";
  } else {
    document.getElementById('codigoirrf').style.display = "none";
  }
}

function js_pesquisae48_cgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cgm',
                                    'func_cgm.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome', 
                                    'CGM',true);
  }else{
     if(document.form1.e48_cgm.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cgm',
                            'func_cgm.php?pesquisa_chave='+document.form1.e48_cgm.value+'&funcao_js=parent.js_mostracgm',
                            'Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}

function js_mostracgm(erro,chave){
  document.form1.z01_nome.value = chave;
  if (erro==true) {
    document.form1.e48_cgm.value  = '';
    document.form1.e48_cgm.focus(); 
  }
}

function js_mostracgm1(chave1,chave2) {
  document.form1.e48_cgm.value  = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}



<?
if ($db_opcao != 1 && isset($e21_retencaotipocalc)) {
   echo "js_mostraCodigoIRRF({$e21_retencaotipocalc});\n";
}
?>
</script>