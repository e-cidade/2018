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
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
  function js_AbreJanelaRelatorio() { 
    if(document.form1.matricula.checked == true)
	  ordem = "j01_matric";
    if(document.form1.nome.checked == true)
	  ordem = "z01_nome";
    if(document.form1.sql.checked == true)
	  ordem = "j34_setor,j34_quadra,j34_lote";
     
    if(document.form1.valortipo[0].checked)
      valortipol = document.form1.valortipo[0].value
    else
      valortipol = document.form1.valortipo[1].value
      
     
    window.open('cad2_listaiptucalc_002.php?ordem='+ordem+'&valorm='+document.form1.valorm.value+'&valortipo='+valortipol+'&quantidade='+document.form1.quantidade.value+'&agrupar='+document.form1.agrupar.value+'&ordenar='+document.form1.ordenar.value,'','width=790,height=530,scrollbars=1,location=0'); 
   }
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC"><table width="80%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><form name="form1" method="post" action="cad2_iptucalculado_002.php">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td align="center">
				  <table width="100%" border="1" cellspacing="0" cellpadding="0">
                      <tr align="center"> 
                        <td bgcolor="#0099CC"><strong>Relatorio 
                          por Matr&iacute;cula do IPTU Calculado</strong></td>
                      </tr>
                      <tr align="center"> 
                        <td width="53%" height="125" align="center"> <br>
                          <br>
                          <table width="100%" border="0" cellspacing="0">
                            <tr>
                              <td width="47%" height="22" align="right">Ordem de Impress&atilde;o:</td>
                              <td width="53%"><input name="ordem" type="radio" id="matricula" value="matric" checked> 
                                <label for="matricula">Matr&iacute;cula</label></td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                              <td> 
                                <input type="radio" name="ordem" value="z01_nome" id="nome"> 
                                <label for="nome">Nome</label></td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                              <td><input type="radio" name="ordem" value="sql" id="sql"> 
                                <label for="sql">Setor/Quadra/Lote</label></td>
                            </tr>
                          </table>
                          <br>
                      </tr>
                      <td width="53%" height="125" align="center"> <br>
                          <br>
                          <table>
                        <tr>
                              <td>&nbsp;</td>
                              <td> 
                                <input type="radio" name="valortipo" checked value="maiores" id="valortipom"> 
                                <label for="valortipom">Maiores que</label></td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                              <td><input type="radio" name="valortipo" value="menores" id="valortipon"> 
                                <label for="valortipon">Menores que</label></td>
                            </tr>
    			  <tr>
			  <td><strong>Valor do Filtro:</strong></td>
			  <td><input name='valorm' type='text' value=''></td>
			  </tr>
     			  <tr>
			  <td><strong>Quantidade a Listar:</strong></td>
			  <td><input name='quantidade' type='text' value=''></td>
			  </tr>
      			  <tr>
			  <td><strong>Agrupar:</strong></td>
			  <td><select name='agrupar' >
			      <option value='m'>Matricula</option>
			      <option value='n'>Nome     </option>
			      </select> 
			    </td>
			  </tr>
       			  <tr>
			  <td><strong>Ordenar:</strong></td>
			  <td><select name='ordenar' >
			      <option value='om'>Matricula</option>
			      <option value='on'>Nome     </option>
			      <option value='oa'>Valor Ascendente </option>
			      <option value='od'>Valor Descendente </option>
			      </select> 
			    </td>
			  </tr>
 
                          </table>
                           <br>
                      </tr>
 
                      <tr align="center"> 
                        <td ><input name="exibir_relatorio" type="button" id="exibir_relatorio" value="Exibir relat&oacute;rio" onClick="js_AbreJanelaRelatorio()"></td>
                      </tr>
                    </table></td>
                </tr>
              </table>
            </form></td>
        </tr>
      </table></td>
  </tr>
</table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>