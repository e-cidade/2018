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
include("libs/db_usuariosonline.php");
include("classes/db_setor_classe.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
include("classes/db_sanitario_classe.php");
require_once('libs/db_utils.php');
require_once("libs/db_libpostgres.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clpostgresqlutils  = new PostgreSQLUtils;
$clsetor            = new cl_setor;
$cliframe_seleciona = new cl_iframe_seleciona;
$aux                = new cl_arquivo_auxiliar;
$clrotulo           = new rotulocampo;

$clsetor->rotulo->label();
$clrotulo->label("z01_nome");

if (count($clpostgresqlutils->getTableIndexes('debitos')) == 0) {
  
  db_msgbox("Problema nos índices da tabela débitos. Entre em contato com CPD.");
  $db_botao = false; 
  $db_opcao = 3;
} else {
  
  $db_botao = true;
  $db_opcao = 4;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<center>
<table align='center' border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <form name="form1" method="post">
    <table border="0" align='center'>
      <tr nowrap >
        <td nowrap  align="top" align='center' colspan="2">
				<?
				  $cliframe_seleciona->campos        = "j30_codi,j30_descr";
				  $cliframe_seleciona->legenda       = "SETOR";
				  $cliframe_seleciona->sql=$clsetor->sql_query(""," * ","");      
				  $cliframe_seleciona->textocabec    = "darkblue";
				  $cliframe_seleciona->textocorpo    = "black";
				  $cliframe_seleciona->fundocabec    = "#aacccc";
				  $cliframe_seleciona->fundocorpo    = "#ccddcc";
				  $cliframe_seleciona->iframe_height = "250";
				  $cliframe_seleciona->iframe_width  = "400";
				  $cliframe_seleciona->iframe_nome   = "setor";
				  $cliframe_seleciona->chaves        = "j30_codi,j30_descr";
				  $cliframe_seleciona->iframe_seleciona($db_opcao);    
				?>
        </td>
      <?
        db_input('idbql',"",0,true,'hidden',3,"");
      ?>   
        <td nowrap  align='center' colspan="2">
				<table>
	       <tr> 
	           <td colspan=2  align="left">
	            <strong>Opções:</strong>  
			        <?
			          $aVer = array("com" => "Com os Cgm's selecionadas",
			                        "sem" => "Sem os Cgm's selecionadas");
			          db_select("ver",$aVer,true,$db_opcao);        
			        ?>                
	          </td>
	       </tr>
               <?
                 // $aux = new cl_arquivo_auxiliar;
                 $aux->cabecalho      = "<strong>CGM'S</strong>";
                 $aux->codigo         = "z01_numcgm"; //chave de retorno da func
                 $aux->descr          = "z01_nome";   //chave de retorno
                 $aux->nomeobjeto     = 'cgms';
                 $aux->funcao_js      = 'js_mostra';
                 $aux->funcao_js_hide = 'js_mostra1';
                 $aux->sql_exec       = "";
                 $aux->func_arquivo   = "func_nome.php";
                 $aux->nomeiframe     = "db_iframe_cgm";
                 $aux->localjan       = "";
                 $aux->onclick        = "";
                 $aux->db_opcao       = $db_opcao;
                 $aux->tipo           = 2;
                 $aux->top            = 0;
                 $aux->linhas         = 10;
                 $aux->vwhidth        = 400;
                 $aux->funcao_gera_formulario();
              ?>    
							</table>
          </td>
					
       </tr>

      <tr>	    
			  <td colspan=4 align='right' >
			  &nbsp;
			  &nbsp;
			  &nbsp;
        </td>
       </tr>

      <tr>
	      <td colspan=2 align='right' >
     	    <b>Ordenar:</b>
		      <?
		        $tipo_t = array("s"=>"Setor/Quadra/Lote","m"=>"Matrícula","r"=>"Rua");
		        db_select("ordem",$tipo_t,true,$db_opcao); 	      
		      ?>
	      </td>
        <td colspan='2' align='center'>
	        <input type="button" name="relatorio1" value="Gerar relatório" onClick="js_imprime();"
	               <?=($db_botao ? '' : 'disabled')?>>
        </td>          	
      </tr>
    </table>
    </form>    
    </td>
  </tr>
</table>
</center>
</body>
</html>
<script>
function js_mostra1(lErro,sNome){

  if(lErro){
    document.form1.z01_numcgm.value = "";
    document.form1.z01_nome.value   = "";
    document.form1.z01_numcgm.focus();
  }else{
    document.form1.z01_nome.value = sNome;
    document.form1.db_lanca.onclick = js_insSelectcgms;
  }

}

function js_imprime(){
  set = "";
	quadra = "";
	rua = ""
  vir = "";
  x = 0;
  for(i=0;i<setor.document.form1.length;i++){
    if(setor.document.form1.elements[i].type == "checkbox"){
      if(setor.document.form1.elements[i].checked == true){
        valor = setor.document.form1.elements[i].value.split("_")
        set += vir + valor[0];
        vir = ",";
        x += 1; 
      }
    }
  }
	if (x>0){    
		vir = "";
		x = 0;
		for(i=0;i<parent.iframe_g2.quadras.document.form1.length;i++){
		  if(parent.iframe_g2.quadras.document.form1.elements[i].type == "checkbox"){
			  if(parent.iframe_g2.quadras.document.form1.elements[i].checked == true){
				  valor = parent.iframe_g2.quadras.document.form1.elements[i].value.split("_")
				  quadra += vir + valor[0];
				  vir = ",";
		 		  x += 1; 
			  }
		  }
	  }
	  if (x>0){
			vir = "";
			for(i=0;i<parent.iframe_g3.ruas.document.form1.length;i++){
			  if(parent.iframe_g3.ruas.document.form1.elements[i].type == "checkbox"){
				  if(parent.iframe_g3.ruas.document.form1.elements[i].checked == true){
					  valor = parent.iframe_g3.ruas.document.form1.elements[i].value.split("_")
					  rua += vir + valor[0];
					  vir = ",";
				  }
			  }
		  }
	  }
  }
    
  vir   = "";
  lista = "";
  for (x=0;x<document.form1.cgms.length;x++) {
    lista+=vir+document.form1.cgms.options[x].value;
    vir=",";
  }  
  
	query  = "";
	query += "setor="+set;
  query += "&lista="+lista+"&ver="+document.form1.ver.value;
	query += "&quadra="+quadra;
	query += "&ruas="+rua;
	query += "&ordem="+document.form1.ordem.value;

  jan = window.open('cad2_debset002.php?'+query,
                    '',
                    'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
function js_nome(){
  j34_setor = "";
  vir = "";
  x = 0;
  for(i=0;i<setor.document.form1.length;i++){
   if(setor.document.form1.elements[i].type == "checkbox"){
     if(setor.document.form1.elements[i].checked == true){
       valor = setor.document.form1.elements[i].value.split("_")
       j34_setor += vir + valor[0];
       vir = ",";
       x += 1; 
     }
   }
  }
  parent.iframe_g2.location.href = 'cad2_debset004.php?j34_setor='+j34_setor;
  if(j34_setor == ""){
    document.form1.idbql.value = '';    
  }
}
</script>