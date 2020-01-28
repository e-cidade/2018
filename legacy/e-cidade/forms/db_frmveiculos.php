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

//MODULO: veiculos
include(modification("classes/db_cgm_classe.php"));
include(modification("classes/db_veiccadproced_classe.php"));
include(modification("classes/db_veiccadcateg_classe.php"));
include(modification("classes/db_veiccadcomb_classe.php"));
include(modification("classes/db_veiccadcategcnh_classe.php"));
include(modification("classes/db_veiccadpotencia_classe.php"));
include(modification("classes/db_veiccadtipo_classe.php"));
include(modification("classes/db_veiccadmarca_classe.php"));
include(modification("classes/db_veiccadcor_classe.php"));
include(modification("classes/db_veiccadtipocapacidade_classe.php"));
include(modification("classes/db_db_config_classe.php"));
include(modification("classes/db_ceplocalidades_classe.php"));
include_once(modification("classes/db_veictipoabast_classe.php"));
include_once(modification("classes/db_veiculoscomb_classe.php"));

$clcgm                   = new cl_cgm;
$clveiccadproced         = new cl_veiccadproced;
$clveiccadcateg          = new cl_veiccadcateg;
$clveiccadcomb           = new cl_veiccadcomb;
$clveiccadcategcnh       = new cl_veiccadcategcnh;
$clveiccadpotencia       = new cl_veiccadpotencia;
$clveiccadtipocapacidade = new cl_veiccadtipocapacidade;
$clveiccadtipo           = new cl_veiccadtipo;
$clveiccadmarca          = new cl_veiccadmarca;
$clveiccadcor            = new cl_veiccadcor;
$cldb_config             = new cl_db_config;
$clceplocalidades        = new cl_ceplocalidades;
$clveiculoscomb          = new cl_veiculoscomb;
$clveictipoabast         = new cl_veictipoabast;

$clveiculos->rotulo->label();

$clrotulo = new rotulocampo;

$clrotulo->label("ve32_descr");
$clrotulo->label("ve31_descr");
$clrotulo->label("ve24_descr");
$clrotulo->label("cp05_localidades");
$clrotulo->label("ve20_descr");
$clrotulo->label("ve21_descr");
$clrotulo->label("ve22_descr");
$clrotulo->label("ve23_descr");
$clrotulo->label("ve25_descr");
$clrotulo->label("ve26_descr");
$clrotulo->label("ve30_descr");
$clrotulo->label("ve02_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("ve03_bem");
$clrotulo->label("t52_descr");
$clrotulo->label("ve06_veiccadcomb");
$clrotulo->label("ve07_sigla");
$clrotulo->label("ve40_veiccadcentral");
$clrotulo->label("descrdepto");

$result_param=$clveicparam->sql_record($clveicparam->sql_query_file(null,"*",null," ve50_instit=".db_getsession("DB_instit")));
if ($clveicparam->numrows>0){
	db_fieldsmemory($result_param,0);		
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
 <tr>
    <td nowrap title="<?=@$Tve01_codigo?>">
       <?=@$Lve01_codigo?>
    </td>
    <td> 
<?
db_input('ve01_codigo',10,$Ive01_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tve01_placa?>">
       <?=@$Lve01_placa?>
    </td>
    <td> 
<?
db_input('ve01_placa',7,$Ive01_placa,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tve02_numcgm?>"><?=@$Lve02_numcgm?></td>
    <td> 
<?
$result_motora=$clcgm->sql_record($clcgm->sql_query_veic(null,"distinct z01_numcgm,z01_nome", "z01_nome","ve05_numcgm is not null"));
db_selectrecord("ve02_numcgm",$result_motora,true,$db_opcao,"","","", "0-Nenhum");
?>  
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tve01_veiccadtipo?>"><?=@$Lve01_veiccadtipo?></td>
    <td> 
<?
if ($db_opcao == 1) {
	$ve01_veiccadtipo = $ve50_veiccadtipo;
}
$result_tipo=$clveiccadtipo->sql_record($clveiccadtipo->sql_query(null, "*", "ve20_descr"));
db_selectrecord("ve01_veiccadtipo",$result_tipo,true,"text",$db_opcao);
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tve01_veiccadmarca?>"><?=@$Lve01_veiccadmarca?></td>
    <td> 
<?
$result_marca=$clveiccadmarca->sql_record($clveiccadmarca->sql_query(null, "*", "ve21_descr"));
db_selectrecord("ve01_veiccadmarca",$result_marca,true,$db_opcao,"","","", "0-Nenhum");
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tve01_veiccadmodelo?>">
       <?
       db_ancora(@$Lve01_veiccadmodelo,"js_pesquisave01_veiccadmodelo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ve01_veiccadmodelo',10,$Ive01_veiccadmodelo,true,'text',$db_opcao," onchange='js_pesquisave01_veiccadmodelo(false);'")
?>
       <?
db_input('ve22_descr',40,$Ive22_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tve01_veiccadcor?>"><?=@$Lve01_veiccadcor?></td>
    <td> 
<?
$result_cor=$clveiccadcor->sql_record($clveiccadcor->sql_query(null, "*", "ve23_descr"));
db_selectrecord("ve01_veiccadcor",$result_cor,true,$db_opcao,"","","", "0-Nenhum");
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tve01_veiccadproced?>"><?=@$Lve01_veiccadproced?></td>
    <td> 
<?
$result_proced=$clveiccadproced->sql_record($clveiccadproced->sql_query());
db_selectrecord("ve01_veiccadproced",$result_proced,true,"text",$db_opcao);
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tve01_veiccadcateg?>"><?=@$Lve01_veiccadcateg?></td>
    <td> 
<?
$result_categ=$clveiccadcateg->sql_record($clveiccadcateg->sql_query());
db_selectrecord("ve01_veiccadcateg",$result_categ,true,"text",$db_opcao);
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tve01_chassi?>">
       <?=@$Lve01_chassi?>
    </td>
    <td> 
<?
db_input('ve01_chassi',30,$Ive01_chassi,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tve01_ranavam?>">
       <?=@$Lve01_ranavam?>
    </td>
    <td> 
<?
db_input('ve01_ranavam',10,$Ive01_ranavam,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tve01_placanum?>">
       <?=@$Lve01_placanum?>
    </td>
    <td> 
<?
db_input('ve01_placanum',12,$Ive01_placanum,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tve01_certif?>">
       <?=@$Lve01_certif?>
    </td>
    <td> 
<?
db_input('ve01_certif',20,$Ive01_certif,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tve01_quantpotencia?>"><?=@$Lve01_quantpotencia?></td>
    <td> 
<?
db_input('ve01_quantpotencia',10,$Ive01_quantpotencia,true,'text',$db_opcao,"");
$result_potencia=$clveiccadpotencia->sql_record($clveiccadpotencia->sql_query());
db_selectrecord("ve01_veiccadpotencia",$result_potencia,true,"text",$db_opcao);
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=$Tve01_veictipoabast?>"><?=$Lve01_veictipoabast?></td>
    <td>
    <?
       $result_veictipoabast = $clveictipoabast->sql_record($clveictipoabast->sql_query(null,"ve07_sequencial,ve07_descr"));
       db_selectrecord("ve01_veictipoabast",$result_veictipoabast,true,$db_opcao,"","",""," -(Selecione)","js_mostramedida();");
    ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tve01_medidaini?>">
       <?=@$Lve01_medidaini?>
    </td>
    <td> 
<?
db_input('ve01_medidaini',15,$Ive01_medidaini,true,'text',$db_opcao,"");
if (isset($ve07_sigla) && trim($ve07_sigla) != ""){
  echo " ".db_input("ve07_sigla",3,0,true,"text",3);
}
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tve01_quantcapacidad?>"><?=@$Lve01_quantcapacidad?></td>
    <td> 
<?
db_input('ve01_quantcapacidad',10,$Ive01_quantcapacidad,true,'text',$db_opcao,"");
$result_tipocapacidade=$clveiccadtipocapacidade->sql_record($clveiccadtipocapacidade->sql_query());
db_selectrecord("ve01_veiccadtipocapacidade",$result_tipocapacidade,true,"text",$db_opcao);
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tve01_dtaquis?>">
       <?=@$Lve01_dtaquis?>
    </td>
    <td> 
<?
db_inputdata('ve01_dtaquis',@$ve01_dtaquis_dia,@$ve01_dtaquis_mes,@$ve01_dtaquis_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
    <tr>
    <td nowrap title="<?=@$Tve06_veiccadcomb?>">
       <?
       db_ancora(@$Lve06_veiccadcomb,"js_veiculoscomb($db_opcao);",$db_opcao);
       ?>
    </td>
    <td><b> 
    <?
      db_input("cod_comb",   10,"",true,"hidden",3);
      db_input("comb_padrao",10,"",true,"hidden",3);

      if ($db_opcao != 1 && isset($ve01_codigo)){
        $res_veiculoscomb = $clveiculoscomb->sql_record($clveiculoscomb->sql_query_comb(null,"distinct ve26_descr,ve06_padrao",null,"ve06_veiculos=$ve01_codigo"));

        if ($clveiculoscomb->numrows > 0){
          $virgula   = "";
          $vet_comb  = array(array("descr","padrao"));
          $cont_comb = 0;
          for($x = 0; $x < $clveiculoscomb->numrows; $x++){
            db_fieldsmemory($res_veiculoscomb,$x);

            $vet_comb["descr"][$cont_comb] = $ve26_descr;

            if ($ve06_padrao == "t"){
              $padrao = 1;
            } else {
              $padrao = 0;
            }

            $vet_comb["padrao"][$cont_comb] = $padrao;
            $cont_comb++;
          }
        
          $valor = "";
          for($x = 0; $x < $cont_comb; $x++){
            if ($vet_comb["padrao"][$x] == 1){
              $valor = $vet_comb["descr"][$x];
              break;
            }
          }

          $virgula = ", ";
          for($x = 0; $x < $cont_comb; $x++){
            if ($vet_comb["padrao"][$x] == 0 && $vet_comb["descr"][$x] != ""){
              $valor .= $virgula.$vet_comb["descr"][$x];
            }

            $virgula = ", ";
          }

?>
<input title=" Combústivel Campo:ve06_veiccadcomb " name="ve06_veiccadcomb" type="text" id="ve06_veiccadcomb" value="<?=$valor?>" size="60" readonly style="background-color:#DEB887;" autocomplete="off">
<?
        } else {
         $valor = "Nenhum combústivel cadastrado.";
?>
<input title=" Combústivel Campo:ve06_veiccadcomb " name="ve06_veiccadcomb" type="text" id="ve06_veiccadcomb" value="<?=$valor?>" size="60" readonly style="background-color:#DEB887;" autocomplete="off">
<?
        }
      } else {
       $valor = "Nenhum combústivel cadastrado.";
?>
<input title=" Combústivel Campo:ve06_veiccadcomb " name="ve06_veiccadcomb" type="text" id="ve06_veiccadcomb" value="<?=$valor?>" size="60" readonly style="background-color:#DEB887;" autocomplete="off">
<?
      }
    ?>
    </b></td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tve01_veiccadcategcnh?>"><?=@$Lve01_veiccadcategcnh?></td>
    <td> 
<?
if ($db_opcao == 1) {
	$ve01_veiccadcategcnh = $ve50_veiccadcategcnh;
}
$result_categcnh=$clveiccadcategcnh->sql_record($clveiccadcategcnh->sql_query());
db_selectrecord("ve01_veiccadcategcnh",$result_categcnh,true,"text",$db_opcao);
?>
    </td>
  </tr>  
  <tr>
    <td nowrap title="<?=@$Tve01_anofab?>">
       <?=@$Lve01_anofab?>
    </td>
    <td> 
<?
db_input('ve01_anofab',4,$Ive01_anofab,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tve01_anomod?>">
       <?=@$Lve01_anomod?>
    </td>
    <td> 
<?
db_input('ve01_anomod',4,$Ive01_anomod,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tve01_ceplocalidades?>">
       <?
       db_ancora(@$Lve01_ceplocalidades,"js_pesquisave01_ceplocalidades(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
if (!isset($ve01_ceplocalidades)){
	$result_munic=$cldb_config->sql_record($cldb_config->sql_query_file(db_getsession("DB_instit"),"munic"));
	if ($cldb_config->numrows>0){
		db_fieldsmemory($result_munic,0);		
		$result_localidade=$clceplocalidades->sql_record($clceplocalidades->sql_query_file(null,"cp05_codlocalidades as ve01_ceplocalidades,cp05_localidades",null,"cp05_localidades='$munic'"));
		if ($clceplocalidades->numrows>0){
			db_fieldsmemory($result_localidade,0);
		}
	}
}
db_input('ve01_ceplocalidades',10,$Ive01_ceplocalidades,true,'text',$db_opcao," onchange='js_pesquisave01_ceplocalidades(false);'")
?>
       <?
db_input('cp05_localidades',60,$Icp05_localidades,true,'text',3,'')
       ?>
    </td>
  </tr>
  <!--
  <tr>
    <td nowrap title="<?=@$Tve01_ativo?>">
       <?=@$Lve01_ativo?>
    </td>
    <td> 
<?
$x = array('1'=>'Sim','0'=>'Não');
db_select('ve01_ativo',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  -->
  <?
if(isset($ve50_integrapatri)&&$ve50_integrapatri==1){
?>
  <tr>
    <td nowrap title="<?=@$Tve03_bem?>">
       <?
       db_ancora(@$Lve03_bem,"js_pesquisave03_bem(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ve03_bem',10,$Ive03_bem,true,'text',$db_opcao," onchange='js_pesquisave03_bem(false);'")
?>
       <?
db_input('t52_descr',40,$It52_descr,true,'text',3,'')
       ?>
    </td>
  </tr>  
<?
}
?>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_veiculoscomb(opcao){
<?
  $query = "db_opcao=".$db_opcao;
  if ($db_opcao!=1 && isset($ve01_codigo)){
    $query .= "&ve06_veiculos=".$ve01_codigo;
  }
?>
  js_OpenJanelaIframe('CurrentWindow.corpo.iframe_veiculos','db_iframe_veiculoscomb','vei2_veiculoscomb001.php?<?=$query?>','Combustiveis',true);
}
function js_pesquisave01_veiccadmodelo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_veiculos','db_iframe_veiccadmodelo','func_veiccadmodelo.php?funcao_js=parent.js_mostraveiccadmodelo1|ve22_codigo|ve22_descr','Pesquisa',true);
  }else{
     if(document.form1.ve01_veiccadmodelo.value != ''){ 
        js_OpenJanelaIframe('CurrentWindow.corpo.iframe_veiculos','db_iframe_veiccadmodelo','func_veiccadmodelo.php?pesquisa_chave='+document.form1.ve01_veiccadmodelo.value+'&funcao_js=parent.js_mostraveiccadmodelo','Pesquisa',false);
     }else{
       document.form1.ve22_descr.value = ''; 
     }
  }
}
function js_mostraveiccadmodelo(chave,erro){
  document.form1.ve22_descr.value = chave; 
  if(erro==true){ 
    document.form1.ve01_veiccadmodelo.focus(); 
    document.form1.ve01_veiccadmodelo.value = ''; 
  }
}
function js_mostraveiccadmodelo1(chave1,chave2){
  document.form1.ve01_veiccadmodelo.value = chave1;
  document.form1.ve22_descr.value = chave2;
  db_iframe_veiccadmodelo.hide();
}
function js_pesquisave06_veiccadcomb(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_veiculos','db_iframe_veiccadcomb','func_veiccadcomb.php?funcao_js=parent.js_mostraveiccadcomb1|ve26_codigo|ve26_descr','Pesquisa',true);
  }else{
     if(document.form1.ve06_veiccadcomb.value != ''){ 
        js_OpenJanelaIframe('CurrentWindow.corpo.iframe_veiculos','db_iframe_veiccadcomb','func_veiccadcomb.php?pesquisa_chave='+document.form1.ve06_veiccadcomb.value+'&funcao_js=parent.js_mostraveiccadcomb','Pesquisa',false);
     }else{
       document.form1.ve26_descr.value = ''; 
     }
  }
}
function js_mostraveiccadcomb(chave,erro){
  document.form1.ve26_descr.value = chave; 
  if(erro==true){ 
    document.form1.ve06_veiccadcomb.focus(); 
    document.form1.ve06_veiccadcomb.value = ''; 
  }
}
function js_mostraveiccadcomb1(chave1,chave2){
  document.form1.ve06_veiccadcomb.value = chave1;
  document.form1.ve26_descr.value = chave2;
  db_iframe_veiccadcomb.hide();
}
function js_pesquisave01_ceplocalidades(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_veiculos','db_iframe_ceplocalidades','func_ceplocalidades.php?funcao_js=parent.js_mostraceplocalidades1|cp05_codlocalidades|cp05_localidades','Pesquisa',true);
  }else{
     if(document.form1.ve01_ceplocalidades.value != ''){ 
        js_OpenJanelaIframe('CurrentWindow.corpo.iframe_veiculos','db_iframe_ceplocalidades','func_ceplocalidades.php?pesquisa_chave='+document.form1.ve01_ceplocalidades.value+'&funcao_js=parent.js_mostraceplocalidades','Pesquisa',false);
     }else{
       document.form1.cp05_localidades.value = ''; 
     }
  }
}
function js_mostraceplocalidades(chave,erro){
  document.form1.cp05_localidades.value = chave; 
  if(erro==true){ 
    document.form1.ve01_ceplocalidades.focus(); 
    document.form1.ve01_ceplocalidades.value = ''; 
  }
}
function js_mostraceplocalidades1(chave1,chave2){
  document.form1.ve01_ceplocalidades.value = chave1;
  document.form1.cp05_localidades.value = chave2;
  db_iframe_ceplocalidades.hide();
}
function js_pesquisave40_veiccadcentral(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_veiculos','db_iframe_veiccadcentral','func_veiccadcentral.php?funcao_js=parent.js_mostraveiccadcentral1|ve36_sequencial|descrdepto','Pesquisa',true);
  }else{
     if(document.form1.ve40_veiccadcentral.value != ''){ 
        js_OpenJanelaIframe('CurrentWindow.corpo.iframe_veiculos','db_iframe_veiccadcentral','func_veiccadcentral.php?pesquisa_chave='+document.form1.ve40_veiccadcentral.value+'&funcao_js=parent.js_mostraveiccadcentral','Pesquisa',false);
     }else{
       document.form1.descrdepto.value = ''; 
     }
  }
}
function js_mostraveiccadcentral(chave1,erro,chave2){
  document.form1.descrdepto.value = chave2; 
  if(erro==true){ 
    document.form1.ve40_veiccadcentral.focus(); 
    document.form1.ve40_veiccadcentral.value = ''; 
  }
}
function js_mostraveiccadcentral1(chave1,chave2){
  document.form1.ve40_veiccadcentral.value = chave1;
  document.form1.descrdepto.value          = chave2;
  db_iframe_veiccadcentral.hide();
}
function js_pesquisave03_bem(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_veiculos','db_iframe_bens','func_bens.php?funcao_js=parent.js_mostrabens1|t52_bem|t52_descr','Pesquisa',true);
  }else{
     if(document.form1.ve03_bem.value != ''){ 
        js_OpenJanelaIframe('CurrentWindow.corpo.iframe_veiculos','db_iframe_bens','func_bens.php?pesquisa_chave='+document.form1.ve03_bem.value+'&funcao_js=parent.js_mostrabens','Pesquisa',false);
     }else{
       document.form1.t52_descr.value = ''; 
     }
  }
}
function js_mostrabens(chave,erro){
  document.form1.t52_descr.value = chave; 
  if(erro==true){ 
    document.form1.ve03_bem.focus(); 
    document.form1.ve03_bem.value = ''; 
  }
}
function js_mostrabens1(chave1,chave2){
  document.form1.ve03_bem.value = chave1;
  document.form1.t52_descr.value = chave2;
  db_iframe_bens.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('CurrentWindow.corpo.iframe_veiculos','db_iframe_veiculos','func_veiculos.php?funcao_js=parent.js_preenchepesquisa|ve01_codigo&instit=true&lVeiculosSemCentral=true','Pesquisa',true,'0');
}
function js_preenchepesquisa(chave){
  db_iframe_veiculos.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
function js_mostramedida(){
  var sel1  = document.form1.elements["ve01_veictipoabast"];
  var valor = sel1.options[sel1.selectedIndex].value;

  obj = document.createElement('input');
  obj.setAttribute('name','codveictipoabast');
  obj.setAttribute('type','hidden');
  obj.setAttribute('value',valor);
  document.form1.appendChild(obj);

  document.form1.submit();
}
</script>
<?
if ($db_opcao == 1) {
	echo "<script>js_pesquisa_depart(false)</script>";
}
?>
