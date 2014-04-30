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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
include("classes/db_veiculos_classe.php");
include("classes/db_veiccadcentral_classe.php");

$clveiccadcentral = new cl_veiccadcentral();
$clveiculos  			= new cl_veiculos;
$aux         			= new cl_arquivo_auxiliar;

$clrotulo    			= new rotulocampo;

$clrotulo->label("ve20_descr");
$clrotulo->label("ve21_descr");
$clrotulo->label("ve22_descr");
$clrotulo->label("ve26_descr");
$clrotulo->label("ve06_veiccadcomb");

$clveiculos->rotulo->label("ve01_placa");
$clveiculos->rotulo->label("ve01_veiccadtipo");
$clveiculos->rotulo->label("ve01_veiccadmarca");
$clveiculos->rotulo->label("ve01_veiccadmodelo");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>
function js_emite(){
  var query = "";
  var obj   = document.form1;
  var lista_veic = "";
  var virgula    = "";

  if ((obj.ve70_dataini.value == "" && obj.ve70_datafin.value != "") || 
      (obj.ve70_dataini.value != "" && obj.ve70_datafin.value == "")){
    alert("Periodo inválido. Verifique");
    obj.ve70_dataini.focus();
    obj.ve70_dataini.select();
    return false;
  }

  if (obj.ve70_dataini.value != ""){
    query += "ve70_dataini=" +obj.ve70_dataini_ano.value+"-"+obj.ve70_dataini_mes.value+"-"+obj.ve70_dataini_dia.value;
  }

  if (obj.ve70_datafin.value != ""){
    query += "&ve70_datafin="+obj.ve70_datafin_ano.value+"-"+obj.ve70_datafin_mes.value+"-"+obj.ve70_datafin_dia.value;
  }

  for(i = 0; i < obj.veiculos.length; i++){
    lista_veic += virgula+obj.veiculos.options[i].value;
    virgula     = ",";
  }

  if (query != ""){
    query += "&";
  }

  query += "ve01_codigo="+lista_veic;
  query += "&ve01_veiccadtipo="+obj.ve01_veiccadtipo.value;
  query += "&ve01_veiccadmarca="+obj.ve01_veiccadmarca.value;
  query += "&ve01_veiccadmodelo="+obj.ve01_veiccadmodelo.value;
  query += "&ve06_veiccadcomb="+obj.ve06_veiccadcomb.value;

  if (obj.quebrar_por.value != "0"){
    query += "&quebrar_por="+obj.quebrar_por.value;
  }
  
  query += "&idCentral="+obj.idCentral.value;
  query += "&situacao="+obj.situacao.value;  
  query += "&listar_por="+obj.listar_por.value;

  var jan = window.open('vei2_veicabast002.php?'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}


function js_pesquisave01_veiccadtipo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_veiccadtipo','func_veiccadtipo.php?funcao_js=parent.js_mostraveiccadtipo1|ve20_codigo|ve20_descr','Pesquisa',true);
  }else{
     if(document.form1.ve01_veiccadtipo.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_veiccadtipo','func_veiccadtipo.php?pesquisa_chave='+document.form1.ve01_veiccadtipo.value+'&funcao_js=parent.js_mostraveiccadtipo','Pesquisa',false);
     }else{
       document.form1.ve20_descr.value = ''; 
     }
  }
}
function js_mostraveiccadtipo(chave,erro){
  document.form1.ve20_descr.value = chave; 
  if(erro==true){ 
    document.form1.ve01_veiccadtipo.focus(); 
    document.form1.ve01_veiccadtipo.value = ''; 
  }
}
function js_mostraveiccadtipo1(chave1,chave2){
  document.form1.ve01_veiccadtipo.value = chave1;
  document.form1.ve20_descr.value = chave2;
  db_iframe_veiccadtipo.hide();
}
function js_pesquisave01_veiccadmarca(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_veiccadmarca','func_veiccadmarca.php?funcao_js=parent.js_mostraveiccadmarca1|ve21_codigo|ve21_descr','Pesquisa',true);
  }else{
     if(document.form1.ve01_veiccadmarca.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_veiccadmarca','func_veiccadmarca.php?pesquisa_chave='+document.form1.ve01_veiccadmarca.value+'&funcao_js=parent.js_mostraveiccadmarca','Pesquisa',false);
     }else{
       document.form1.ve21_descr.value = ''; 
     }
  }
}
function js_mostraveiccadmarca(chave,erro){
  document.form1.ve21_descr.value = chave; 
  if(erro==true){ 
    document.form1.ve01_veiccadmarca.focus(); 
    document.form1.ve01_veiccadmarca.value = ''; 
  }
}
function js_mostraveiccadmarca1(chave1,chave2){
  document.form1.ve01_veiccadmarca.value = chave1;
  document.form1.ve21_descr.value        = chave2;
  db_iframe_veiccadmarca.hide();
}
function js_pesquisave01_veiccadmodelo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_veiccadmodelo','func_veiccadmodelo.php?funcao_js=parent.js_mostraveiccadmodelo1|ve22_codigo|ve22_descr','Pesquisa',true);
  }else{
     if(document.form1.ve01_veiccadmodelo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_veiccadmodelo','func_veiccadmodelo.php?pesquisa_chave='+document.form1.ve01_veiccadmodelo.value+'&funcao_js=parent.js_mostraveiccadmodelo','Pesquisa',false);
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
  document.form1.ve22_descr.value         = chave2;
  db_iframe_veiccadmodelo.hide();
}
function js_pesquisave06_veiccadcomb(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_veiccadcomb','func_veiccadcomb.php?funcao_js=parent.js_mostraveiccadcomb1|ve26_codigo|ve26_descr','Pesquisa',true);
  }else{
     if(document.form1.ve06_veiccadcomb.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_veiccadcomb','func_veiccadcomb.php?pesquisa_chave='+document.form1.ve06_veiccadcomb.value+'&funcao_js=parent.js_mostraveiccadcomb','Pesquisa',false);
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


</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>

  <table  align="center" border="0">
    <form name="form1" method="post" action="">
      <tr>
         <td colspan="2">&nbsp;</td> 
      </tr>
      <tr>
         <td nowrap align="right" title="Periodo"><b>Periodo:</b></td>
         <td> 
         <?
            db_inputdata("ve70_dataini",@$ve70_dataini_dia,@$ve70_dataini_mes,@$ve70_dataini_ano,true,"text",4)
         ?>
         <b>&nbsp;a&nbsp;</b><?
            db_inputdata("ve70_datafin",@$ve70_datafin_dia,@$ve70_datafin_mes,@$ve70_datafin_ano,true,"text",4)
         ?>
         </td>
      </tr>
      <tr>
      	<td align="right"><b>Central de Veículo:</b></td>
      	<td>
      		<?php 
      			$rsQueryCentral = pg_query($clveiccadcentral->sql_query(null," ve36_sequencial as id,descrdepto as depto",null,""));
      			$aValores = array();
      			$aValores['0'] = "Todos";
      			if(pg_num_rows($rsQueryCentral)>0){
      				while ($rowQueryCentral = pg_fetch_object($rsQueryCentral)){
      					$aValores[$rowQueryCentral->id] = $rowQueryCentral->depto;
      				}
      			}
      			db_select("idCentral",$aValores,true,4);
      			//db_selectrecord('idCentral',$rsQueryCentral,true,1,"","","","","",1);	
      		?>
      	</td>
      </tr>
      <tr>
        <td colspan=2 ><?
                 $aux->cabecalho      = "<strong>Veiculos</strong>";
                 $aux->codigo         = "ve01_codigo";  //chave de retorno da func
                 $aux->descr          = "ve01_placa";   //chave de retorno
                 $aux->nomeobjeto     = 'veiculos';
                 $aux->funcao_js      = 'js_mostraveiculos';//função javascript que será utilizada quando clicar na âncora
                 $aux->funcao_js_hide = 'js_mostraveiculos1';//função javascript que será utilizada quando colocar um código e sair do campo
                 $aux->sql_exec       = "";
                 $aux->func_arquivo   = "func_veiculos.php";  //func a executar
                 $aux->nomeiframe     = "db_iframe_veiculos";
                 $aux->localjan       = "";
                 $aux->onclick        = "";
                 $aux->db_opcao       = 4;
                 $aux->tipo           = 2;
                 $aux->top            = 0;
                 $aux->linhas         = 10;
                 $aux->vwhidth        = 400;
                 $aux->funcao_gera_formulario();
        	?>
       </td>
      </tr>  
      <tr>
         <td nowrap align="right" title="<?=@$Tve01_veiccadtipo?>"><? db_ancora(@$Lve01_veiccadtipo,"js_pesquisave01_veiccadtipo(true);",4) ?></td>
         <td>
         <? 
            db_input("ve01_veiccadtipo",10,@$Ive01_veiccadtipo,true,"text",4,"onChange='js_pesquisave01_veiccadtipo(false);'");
         ?>
         <?
            db_input("ve20_descr",40,"",true,"text",3);
         ?>
         </td>
      </tr>
      <tr>
         <td nowrap align="right" title="<?=@$Tve01_veiccadmarca?>"><? db_ancora(@$Lve01_veiccadmarca,"js_pesquisave01_veiccadmarca(true);",4) ?></td>
         <td>
         <? 
            db_input("ve01_veiccadmarca",10,@$Ive01_veiccadmarca,true,"text",4,"onChange='js_pesquisave01_veiccadmarca(false);'");
         ?>
         <?
            db_input("ve21_descr",40,"",true,"text",3);
         ?>
         </td>
      </tr>
      <tr>
         <td nowrap align="right" title="<?=@$Tve01_veiccadmodelo?>"><? db_ancora(@$Lve01_veiccadmodelo,"js_pesquisave01_veiccadmodelo(true);",4) ?></td>
         <td>
         <? 
            db_input("ve01_veiccadmodelo",10,@$Ive01_veiccadmodelo,true,"text",4,"onChange='js_pesquisave01_veiccadmodelo(false);'");
         ?>
         <?
            db_input("ve22_descr",40,"",true,"text",3);
         ?>
         </td>
      </tr>
      <tr>
         <td nowrap align="right" title="<?=@$Tve06_veiccadcomb?>"><? db_ancora(@$Lve06_veiccadcomb,"js_pesquisave06_veiccadcomb(true);",4) ?></td>
         <td>
         <? 
            db_input("ve06_veiccadcomb",10,@$Ive06_veiccadcomb,true,"text",4,"onChange='js_pesquisave06_veiccadcomb(false);'");
         ?>
         <?
            db_input("ve26_descr",40,"",true,"text",3);
         ?>
         </td>
      </tr>
      <tr>
         <td nowrap align="right" title="Quebrar página"><b>Quebrar página por:</b></td>
         <td>
         <?
            $x = array("0"=>"Nenhum","V"=>"Veiculo","T"=>"Tipo","M"=>"Marca","O"=>"Modelo","C"=>"Central de Veículo");
            db_select("quebrar_por",$x,true,4);
         ?>
         </td>
      </tr>
      <tr>
         <td nowrap align="right" title="Situação"><b>Situação do Abastecimento:</b></td>
         <td>
         <?
            $y = array("0"=>"Todos os abastecimentos","1"=>"Somente Ativos", "2"=>"Somente Anulados");
            db_select("situacao",$y,true,4);
         ?>
         </td>
      </tr>
      <tr>
         <td nowrap align="right" title="Quebrar página"><b>Listar:</b></td>
         <td>
         <?
            $z = array("0"=>"Todos os abastecimentos","1"=>"Somente totalizadores");
            db_select("listar_por",$z,true,4);
         ?>
         </td>
      </tr>
      <tr>
        <td height="50" colspan="2" align = "center"> 
          <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();">
        </td>
      </tr>
  </form>
    </table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>


<script>
function js_mostraveiculos1(chave,chave1,chave2){
  if (chave==false){
    document.form1.ve01_placa.value  = "";
    document.form1.ve01_codigo.value = "";
    document.form1.ve01_placa.value  = chave2;
    document.form1.ve01_codigo.value = chave1;
     document.form1.db_lanca.onclick = js_insSelectveiculos;
  } else{
    document.form1.ve01_codigo.value = "";
    document.form1.ve01_placa.value  = "";
    alert("Código inexistente");
  }
}

</script>