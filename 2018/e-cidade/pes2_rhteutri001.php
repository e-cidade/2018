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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_rhteutri_classe.php"));
$clrhteutri = new cl_rhteutri;
$clrotulo = new rotulocampo;
$clrotulo->label("rh68_descr");
$clrotulo->label("rh67_rhtipovale");
$clrotulo->label("r07_codigo");
$clrotulo->label("r07_descr");
$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt25');
$clrotulo->label('DBtxt27');
$clrotulo->label('DBtxt28');
db_postmemory($HTTP_POST_VARS);
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>


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

  <table  align="center">
    <form name="form1" method="post" action="" onsubmit="return js_verifica();">
<table border="0" align="center">
  <tr> 
    <td >&nbsp;</td>
  </tr>
  <tr>
  <td>
  <fieldset>
    <Legend align="left">
    <b>Relatório de Vale Transporte Integrado</b>
    </Legend>
  <table border="0" align="center">
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Trh67_rhtipovale?>">
           <?
           if(isset($rh67_rhtipovale)){
             $rh67_rhtipovale = '';
             $rh68_descr = '';
           }
           db_ancora(@$Lrh67_rhtipovale,"js_pesquisarh67_rhtipovale(true);",2);
           ?>
        </td>
        <td> 
        <?
          db_input('rh67_rhtipovale',4,$Irh67_rhtipovale,true,'text',2," onchange='js_pesquisarh67_rhtipovale(false);'")
        ?>
        <?
          db_input('rh68_descr',40,$Irh68_descr,true,'text',3,'')
        ?>
        </td>
      </tr>
      <tr id='camposdiversos' style='display:none'>
        <td nowrap title="Digite o diverso que corresponde ao valor unitário da passagem."><b>
           <?
           db_ancora('Diverso:',"js_pesquisapesdiver(true);",2);
           ?>
           </b>
        </td>
        <td> 
        <?
          db_input('r07_codigo',4,@$r07_codigo,true,'text',2," onchange='js_pesquisapesdiver(false);'");
          db_input('r07_descr',40,@$Ir07_descr,true,'text',3,'');
        ?>
        </td>
      </tr>
      <tr >
        <td align="right" nowrap title="Tipo de emissão" >
        <strong>Tipo :&nbsp;</strong>
        </td>
        <td>
           <?
           $xy = array("a"=>"Ativos","t"=>"Todos","i"=>"Inativos");
           db_select('tipo',$xy,true,1,"");
          ?>
        </td>
      </tr>
      <tr >
        <td align="right" nowrap title="Ordem de emissão do relatório" >
        <strong>Ordem :&nbsp;&nbsp;</strong>
        </td>
        <td>
           <?
           $x = array("n"=>"Numérica","a"=>"Alfabética");
           db_select('ordem',$x,true,1,"");
          ?>
        </td>
      </tr>
      <tr >
        <td>
           <?
           $res = $clrhteutri->sql_record($clrhteutri->sql_query(null,"distinct rh67_grupo",'',''));
           if($clrhteutri->numrows > 0){
	         echo "
	         <tr>
	          <td align='right' title=''><strong>Grupo:&nbsp;</strong></td>
            <td>
	            <select name='grupo'>
                 <option value = 'todos'>Todos</option> ";
	            for($i=0; $i<$clrhteutri->numrows; $i++){
		            db_fieldsmemory($res, $i);
		            echo "<option value = '$rh67_grupo'>$rh67_grupo</option>";
	            }              
	            echo "
	          </td>
	         </tr> ";
           }
          ?>
        </td>
      </tr>
      <tr>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
      </tr>
    </td>
  </tr>
  </table>
  </fieldset>
  </td>
  </tr>
      <tr>
        <td colspan="2" align = "center"> 
          <input  name="gera" id="gera" type="button" value="Processar" onclick="js_emite();"  >
        </td>
      </tr>
  </table>

  </form>
    </table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
  <?
  if(isset($gera)){
  	echo "js_montarlista('".$arq."#Arquivo gerado em: ".$arq."','form1');";
  }
  ?>

function js_recarregar(iTipo){

  if (iTipo == 2) {
    var display = 'none';
  } else {
    var display = 'none';    
    document.form1.r07_codigo.value = '';
    document.form1.r07_descr.value = '';
  }

  document.getElementById('camposdiversos').style.display = display;

  js_ajaxRequest(iTipo);

}

function js_ajaxRequest(obj){

  js_divCarregando("Aguarde, buscando grupos","processando");

  var url       = 'pes4_dadosGrupoRPC.php';
  var parametro = 'tipovale='+obj;
  var objAjax   = new Ajax.Request (url,{method:'post',parameters:parametro, onComplete:carregaDadosSelect});
	document.form1.rh67_rhtipovale.disabled = true;

}


function carregaDadosSelect(resposta){

  js_removeObj('processando');
	document.form1.rh67_rhtipovale.disabled = false;
	js_limpaSelect(document.form1.grupo);  
	js_addSelectFromStr(resposta.responseText,document.form1.grupo);

}

function js_limpaSelect(obj){
  obj.length  = 0;	
}

// @todo - ver este filtro 
function js_addSelectFromStr(str,obj){
  var linhas  = str.split("|");
  obj.options[0] = new Option();
  obj.options[0].value = "todos";
  obj.options[0].text  = "Todos";
  for(i=0;i<linhas.length+1;i++){

    if(linhas[i] != ''){

		  colunas              = linhas[i].split("-");
      obj.options[i+1]       = new Option();
      obj.options[i+1].value = colunas[0];
      obj.options[i+1].text  = colunas[1];
		}
  }	
}




function js_pesquisarh67_rhtipovale(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_rhtipovale','func_rhtipovale.php?funcao_js=parent.js_mostrarhtipovale1|rh68_sequencial|rh68_descr','Pesquisa',true);
  }else{
     if(document.form1.rh67_rhtipovale.value != ''){ 
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_rhtipovale','func_rhtipovale.php?pesquisa_chave='+document.form1.rh67_rhtipovale.value+'&funcao_js=parent.js_mostrarhtipovale','Pesquisa',false);
     }else{
       document.form1.rh68_descr.value = ''; 
     }
  }
  ////document.form1.submit();
}
function js_mostrarhtipovale(chave,erro){
  document.form1.rh68_descr.value = chave; 
  if(erro==true){ 
    document.form1.rh67_rhtipovale.focus(); 
    document.form1.rh67_rhtipovale.value = ''; 
  }else{
    js_recarregar(document.form1.rh67_rhtipovale.value);    
  }
}
function js_mostrarhtipovale1(chave1,chave2){
  document.form1.rh67_rhtipovale.value = chave1;
  document.form1.rh68_descr.value = chave2;
  js_recarregar(chave1);    
  db_iframe_rhtipovale.hide();
}



function js_pesquisapesdiver(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_pesdiver','func_pesdiver.php?funcao_js=parent.js_mostrapesdiver1|r07_codigo|r07_descr','Pesquisa',true);
  }else{
     if(document.form1.r07_codigo.value != ''){ 
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_pesdiver','func_pesdiver.php?pesquisa_chave='+document.form1.r07_codigo.value+'&funcao_js=parent.js_mostrapesdiver','Pesquisa',false);
     }else{
       document.form1.r07_descr.value = ''; 
     }
  }
}
function js_mostrapesdiver(chave,erro){
  document.form1.r07_descr.value = chave; 
  if(erro==true){ 
    document.form1.r07_codigo.focus(); 
    document.form1.r07_codigo.value = ''; 
  }
}
function js_mostrapesdiver1(chave1,chave2){
  document.form1.r07_codigo.value = chave1;
  document.form1.r07_descr.value = chave2;
  db_iframe_pesdiver.hide();
}

function js_emite(){
  if(document.form1.rh67_rhtipovale.value == ''){
    alert('Escolha um Tipo de Vale');
    return false;
  }
  qry = "?ordem="+document.form1.ordem.value;
  qry+= "&rh67_rhtipovale="+document.form1.rh67_rhtipovale.value;
  qry+= "&tipo="+document.form1.tipo.value;
  qry+= "&grupo="+document.form1.grupo.value;
  
  jan = window.open('pes2_rhteutri002.php'+qry,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);

}
</script>
