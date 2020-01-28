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
include("classes/db_cgm_classe.php");
$clcgm = new cl_cgm;
$aux = new cl_arquivo_auxiliar; 
$clcgm->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("cp05_codlocalidades");
$clrotulo->label("cp05_localidades");

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_relatorio(){
 vir="";
 listacidades="";
 for(x=0;x<document.form1.cidades.length;x++){
  listacidades+=vir+document.form1.cidades.options[x].value;
  vir=",";
 }
  ordenacao 				= document.getElementById('ordenacao').value;
  z01_nome_final 		= document.getElementById('z01_nome_final').value;
  z01_nome_inicial 	= document.getElementById('z01_nome_inicial').value;
  z01_numcgm_inicial= document.getElementById('z01_numcgm_inicial').value;
  z01_numcgm_final  = document.getElementById('z01_numcgm_final').value;
  
  queryString  ='listacidades='+listacidades+'&ordenacao='+ordenacao+'&z01_nome_final='+z01_nome_final;
  queryString +='&z01_nome_inicial='+z01_nome_inicial;
  queryString +='&z01_numcgm_final='+z01_numcgm_final;
  queryString +='&z01_numcgm_inicial='+z01_numcgm_inicial; 
  //alert(queryString); 
	jan = window.open('pro1_relcgm002.php?'+queryString,'','scrollbars=1,location=0 ');
 	jan.moveTo(0,0);

  //jan = window.open('','rel','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  //jan.moveTo(0,0);
  return false;
}
</script>
<script>
//--------------------------------------------------------------------------------------------------------------------------

//Função que passa como parametro o numero do cgm para mostrar as informações referentes ao mesmo.
//Caso não informe um NUMCGM retornará um alert pedindo para informar o NUMCGM.
//function js_abre()
//{
//  if(document.form1.z01_numcgm.value!="")
//  {
//    js_OpenJanelaIframe('top.corpo','func_nome','pro1_002.php?numcgm='+document.form1.z01_numcgm.value,'Pesquisa',true);
//    //alert('NUMCGM:'+document.form1.z01_numcgm.value+' - '+document.form1.z01_nome.value)
//  }
//  else
//  {
//    alert('Informe um número de cgm!');
//  }
//}

//Função que pesquisa caso seja TRUE a pesquisa foi feita atraves da ancora caso seja FALSE a pesquisa foi digitada um numero de CGM 
function js_pesquisaz01_numcgmini(mostra)
{
	
  if(mostra==true)
  {
    js_OpenJanelaIframe('top.corpo','func_nome','func_nome.php?funcao_js=parent.js_mostranumcgm1ini|z01_numcgm|z01_nome','Pesquisa',true);
  }
  else
  {
     if(document.form1.z01_numcgm_inicial.value != '')
     {
        js_OpenJanelaIframe('top.corpo','func_nome','func_nome.php?pesquisa_chave='+document.form1.z01_numcgm_inicial.value+'&funcao_js=parent.js_mostranumcgmini','Pesquisa',false);
     }
     else
     {
       document.form1.z01_nomecgm_inicial.value = "";
     }
  }
}

//Função que retorna a pesquisa para o formulario com os dois campos NUMCGM e NOME.
//Caso a função js_pesquisaz01_numcgm tenha sido FALSE.
//Se a função não encontrar um NUMCGM digitado retorna um erro para o formulario.
function js_mostranumcgmini(erro,chave)
{
  document.form1.z01_nomecgm_inicial.value = chave;
  if(erro==true)
  { 
    document.form1.z01_numcgm_inicial.value = ''; 
    document.form1.z01_numcgm_inicial.focus(); 
  }
}

//Função que retorna a pesquisa para o formulario com os dois campos NUMCGM e NOME
//Caso a função js_pesquisaz01_numcgm tenha sido TRUE.
function js_mostranumcgm1ini(chave1,chave2)
{
	  document.form1.z01_numcgm_inicial.value = chave1;
	  document.form1.z01_nomecgm_inicial.value   = chave2;
 
  func_nome.hide();
}

//---------------------------------------------------------------------------------------------------------------------------
//Função que pesquisa caso seja TRUE a pesquisa foi feita atraves da ancora caso seja FALSE a pesquisa foi digitada um numero de CGM 
function js_pesquisaz01_numcgmfim(mostra)
{
	
  if(mostra==true)
  {
    js_OpenJanelaIframe('top.corpo','func_nome','func_nome.php?funcao_js=parent.js_mostranumcgm1fim|z01_numcgm|z01_nome','Pesquisa',true);
  }
  else
  {
     if(document.form1.z01_numcgm_final.value != '')
     {
        js_OpenJanelaIframe('top.corpo','func_nome','func_nome.php?pesquisa_chave='+document.form1.z01_numcgm_final.value+'&funcao_js=parent.js_mostranumcgmfim','Pesquisa',false);
     }
     else
     {
       document.form1.z01_nomecgm_final.value = "";
     }
  }
}

//Função que retorna a pesquisa para o formulario com os dois campos NUMCGM e NOME.
//Caso a função js_pesquisaz01_numcgm tenha sido FALSE.
//Se a função não encontrar um NUMCGM digitado retorna um erro para o formulario.
function js_mostranumcgmfim(erro,chave)
{
  document.form1.z01_nomecgm_final.value = chave;
  if(erro==true)
  { 
    document.form1.z01_numcgm_final.value = ''; 
    document.form1.z01_numcgm_final.focus(); 
  }
}

//Função que retorna a pesquisa para o formulario com os dois campos NUMCGM e NOME
//Caso a função js_pesquisaz01_numcgm tenha sido TRUE.
function js_mostranumcgm1fim(chave1,chave2)
{
	  document.form1.z01_numcgm_final.value = chave1;
	  document.form1.z01_nomecgm_final.value   = chave2;
 
  func_nome.hide();
}

//---------------------------------------------------------------------------------------------------------------------------
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table height="100%" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr><td height="5"></td></tr>
	<tr>
		<td height="430" valign="top">
			<fieldset >
				<legend><b>Relatório CGM</b></legend>
				<form name="form1" method="post" action="" >
				<fieldset>
					<legend><b>Filtrar por:</b></legend>
					<table >
					<tr> 
				    <td  align="right" nowrap title="<?=$Tz01_numcgm?>"> 
				      <? 
				         //Clicando na ancora para buscar o cgm atraves do formulario de pesquisa.
				         db_ancora("<b>CGM Inicial</b>","js_pesquisaz01_numcgmini(true);",1);
				      ?>
				    </td> 
				    <td align="left" nowrap>
				      <?
				         //Digitando um numero de cgm para buscar
							 db_input("z01_numcgm_inicial",10,$Iz01_numcgm,true,"text",1,"onchange='js_pesquisaz01_numcgmini(false);'"); 
							 db_input("z01_nomecgm_inicial",45,$Iz01_nome,true,"text",3);
				      ?>
				    </td>
				  </tr>
					<tr> 
				    <td  align="right" nowrap title="<?=$Tz01_numcgm?>"> 
				      <? 
				         //Clicando na ancora para buscar o cgm atraves do formulario de pesquisa.
				         db_ancora("<b>CGM Final</b>","js_pesquisaz01_numcgmfim(true);",1);
				      ?>
				    </td> 
				    <td align="left" nowrap>
				      <?
				         //Digitando um numero de cgm para buscar
							 db_input("z01_numcgm_final",10,$Iz01_numcgm,true,"text",1,"onchange='js_pesquisaz01_numcgmfim(false);'"); 
							 db_input("z01_nomecgm_final",45,$Iz01_nome,true,"text",3);
				      ?>
				    </td>
				  </tr>	
						<tr>
							<td align="right" width="100"><b>Nome Inicial</b></td>
							<td><input type="text" name="z01_nome_inicial" id="z01_nome_inicial" size="59"></td>
						</tr>
						<tr>
							<td align="right" width="100"><b>Nome Final</b></td>
							<td><input type="text" name="z01_nome_final" id="z01_nome_final" size="59"></td>
						</tr>
						<tr>
							<td align="right" width="100"><b>Ordem</b></td>
							<td>
							<select name="ordenacao" id="ordenacao" style="width: 120px;">
								<option value="z01_numcgm">CGM</option>
								<option value="z01_nome">Nome</option>
								<option value="z01_ender">Endereço</option>
								<option value="z01_munic">Cidade</option>
							</select>
							</td>
						</tr>
						<tr>
							
							<td colspan="2">
							<?
                $aux->cabecalho = "<strong>Cidades</strong>";
                $aux->codigo = "cp05_codlocalidades";
                $aux->descr  = "cp05_localidades";
                $aux->nomeobjeto = 'cidades';
                $aux->funcao_js = 'js_mostra';
								$aux->funcao_js_hide = 'js_mostra1';
								$aux->sql_exec  = "";
								$aux->func_arquivo = "func_ceplocalidades.php";
								$aux->nomeiframe = "db_iframe_localidades";
								$aux->localjan = "";
								$aux->onclick = "";
								$aux->db_opcao = 2;
								$aux->tipo = 2;
								$aux->top = 0;
								$aux->linhas = 10;
								$aux->vwhidth = 400;
								$aux->btn_lanca = "lancatipo";
								$aux->funcao_gera_formulario();
              ?>
							</td>
						</tr>
					</table>
				</fieldset>
				<div align="center" style="margin-top: 5px;">
					<input name="emite" onClick="return js_relatorio()" type="submit" id="emite" value="Emite Relatório">
				</div>
				</form>
			</fieldset>
		</td>
	</tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>