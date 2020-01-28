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
include("libs/db_libpessoal.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
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
<table width="60%" border="0" cellspacing="4" cellpadding="0">
  <tr><td colspan="2">&nbsp;</td></tr>
  <form name="form1" method="post" action="">
      <tr >
        <td align="right" nowrap title="Digite o Ano / Mes de competência" >
        <strong>Ano / Mês :</strong>
        </td>
        <td>
          <?
           $DBtxt23 = db_anofolha();
           db_input('DBtxt23',4,' ',true,'text',2,'')
          ?>
          &nbsp;/&nbsp;
          <?
           $DBtxt25 = db_mesfolha();
           db_input('DBtxt25',2,' ',true,'text',2,'')
          ?>
        </td>
      </tr>
	<?
  if(!isset($opcao_gml)){
    $opcao_gml = "m";
  }
  if(!isset($opcao_filtro)){
    $opcao_filtro = "s";
  }

  include("dbforms/db_classesgenericas.php");
  $geraform = new cl_formulario_rel_pes;

  $geraform->manomes = false;                     // PARA NÃO MOSTRAR ANO E MES DE COMPETÊNCIA DA FOLHA

  $geraform->usaregi = true;                      // PERMITIR SELEÇÃO DE MATRÍCULAS
  $geraform->usalota = true;                      // PERMITIR SELEÇÃO DE LOTAÇÕES

  $geraform->re1nome = "r110_regisi";             // NOME DO CAMPO DA MATRÍCULA INICIAL
  $geraform->re2nome = "r110_regisf";             // NOME DO CAMPO DA MATRÍCULA FINAL
	
  $geraform->lo1nome = "r110_lotaci";             // NOME DO CAMPO DA LOTAÇÃO INICIAL
  $geraform->lo2nome = "r110_lotacf";             // NOME DO CAMPO DA LOTAÇÃO FINAL

  $geraform->trenome = "opcao_gml";               // NOME DO CAMPO TIPO DE RESUMO
  $geraform->tfinome = "opcao_filtro";            // NOME DO CAMPO TIPO DE FILTRO

  $geraform->filtropadrao = "s";                  // TIPO DE FILTRO PADRÃO
  $geraform->resumopadrao = "m";                  // TIPO DE RESUMO PADRÃO

  $geraform->campo_auxilio_regi = "faixa_regis";  // NOME DO DAS MATRÍCULAS SELECIONADAS
  $geraform->campo_auxilio_lota = "faixa_lotac";  // NOME DO DAS LOTAÇÕES SELECIONADAS

  $geraform->strngtipores = "gm";                // OPÇÕES PARA MOSTRAR NO TIPO DE RESUMO g - geral,
                                                  //                                       m - Matrícula,
                                                  //                                       r - Resumo
  $geraform->onchpad      = true;                 // MUDAR AS OPÇÕES AO SELECIONAR OS TIPOS DE FILTRO OU RESUMO
  $geraform->gera_form(null,null);
  ?>
   <tr >
     <td align="right" nowrap title="Local de Trabalho" >
     <strong>Local de Trabalho : </strong>
     </td>
     <td align="left">
       <?
        db_input('local',2,@$local,true,'text',2,'')
       ?>
     </td>
  </tr>
  <tr>
    <td colspan='2' align='center'>
      <input type="button" name="processar" value="Processar" onclick="return js_enviar_dados();">
    </td>
  </tr>
  </form>
</table>
</center>
<? 
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
<script>
function js_enviar_dados(){
  qry = "";
  if(document.form1.selregist){
    matriculas = '';
    virgula = '';
    for(i=0; i < document.form1.selregist.length; i++){
      matriculas += virgula+document.form1.selregist.options[i].value;
      virgula = ',';
    }
    if (matriculas==""){
      alert('Selecione uma matrícula para processar!!');
      return false;
    }
    qry += "matriculas="+matriculas;
  }
  if (document.form1.r110_regisi){
    if (document.form1.r110_regisi.value==""&&document.form1.r110_regisf.value==""){
      alert('Informe uma matrícula para processar!!');
      return false;
    }
    qry += 'mat_ini='+document.form1.r110_regisi.value+'&mat_fin='+document.form1.r110_regisf.value;
  }
  if (document.form1.local){
    qry += '&local='+document.form1.local.value;
  }
//  document.form1.action = 'pes2_araboletim002.php';
//  return true;
  jan = window.open('pes2_araboletim002.php?'+qry+'&ano='+document.form1.DBtxt23.value+'&mes='+document.form1.DBtxt25.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);

}
//js_trocacordeselect();
</script>
</html>