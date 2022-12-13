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
include("dbforms/db_classesgenericas.php");
include("dbforms/db_funcoes.php");

$aux = new cl_arquivo_auxiliar;

$clrotulo = new rotulocampo;
$clrotulo->label("id_usuario");
$clrotulo->label("nome");

db_postmemory($HTTP_POST_VARS);


?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>
function js_emite(){
  
  vir="";
  listausu="";
  for(x=0;x<document.form1.usuario.length;x++){
    listausu+=vir+document.form1.usuario.options[x].value;
    vir=",";
  }
  ver=document.form1.ver.value;
   
  inc=document.form1.tipoinc.checked;
  alt=document.form1.tipoalt.checked;
  exc=document.form1.tipoexc.checked;
  
  
  data=document.form1.data_ano.value+'-'+document.form1.data_mes.value+'-'+document.form1.data_dia.value;
  data1=document.form1.data1_ano.value+'-'+document.form1.data1_mes.value+'-'+document.form1.data1_dia.value;
  
  if (inc==false && alt==false && exc==false){
    alert("Escolha um tipo de Cgm!!");
  }else{
    jan = window.open('prot2_movcgm002.php?listausu='+listausu+'&ver='+ver+'&data='+data+'&data1='+data1+'&inc='+inc+'&alt='+alt+'&exc='+exc,'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
  }
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

  <table  align="center">
    <form name="form1" method="post" action="">
      </tr>
           <td align="center" colspan=2 >
                <strong>Opções:</strong>
                <select name="ver">
                    <option name="condicao1" value="com">Com os usuários selecionados</option>
                    <option name="condicao1" value="sem">Sem os usuários selecionadas</option>
                </select>
          </td>
       </tr>
       <tr>
          <td nowrap width="50%" colspan=2>
               <?
                 // $aux = new cl_arquivo_auxiliar;
                 $aux->cabecalho = "<strong>Credores</strong>";
                 $aux->codigo = "id_usuario"; //chave de retorno da func
                 $aux->descr  = "nome";   //chave de retorno
                 $aux->nomeobjeto = 'usuario';
                 $aux->funcao_js = 'js_mostra';
                 $aux->funcao_js_hide = 'js_mostra1';
                 $aux->sql_exec  = "";
                 $aux->func_arquivo = "func_db_usuarios.php";  //func a executar
                 $aux->nomeiframe = "db_iframe_usu";
                 $aux->localjan = "";
                 $aux->onclick = "";
                 $aux->db_opcao = 2;
                 $aux->tipo = 0;
                 $aux->top = 0;
                 $aux->linhas = 4;
                 $aux->whidth = 400;
                 $aux->funcao_gera_formulario();
              ?>    
          </td>
       </tr>
      <tr align='center'>
         <td align='right'><b>Cgm's: &nbsp;</b></td>
         <td align='center'>
	    <input type="checkbox" name="tipoinc" checked value="i" ><b>Incluidos</b><br>
            <input type="checkbox" name="tipoalt" checked value="a" ><b>Alterados</b><br>
            <input type="checkbox" name="tipoexc" checked value="e" ><b>Excluidos</b><br>
	 </td>
      <tr>
         <td colspan=2 align='center'><b>Periodo:&nbsp;</b>
	 <?
	 db_inputdata('data',@$dia,@$mes,@$ano,true,'text',1,"");
         echo " a ";
         db_inputdata('data1',@$dia2,@$mes2,@$ano2,true,'text',1,"");
	 ?>
	 &nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" align = "center"> 
          <input  name="emite2" id="emite2" type="button" value="Emitir Relatório" onclick="js_emite();" >
        </td>
      </tr>

  </form>
    </table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>