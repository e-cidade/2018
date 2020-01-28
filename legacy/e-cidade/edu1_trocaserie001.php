<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require_once("libs/db_stdlib.php");
require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_conecta.php");
require_once("libs/db_libdocumento.php");
require_once("libs/db_utils.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_trocaserie_classe.php");
require_once("classes/db_turma_classe.php");
require_once("classes/db_turmaserieregimemat_classe.php");
require_once("classes/db_matricula_classe.php");
require_once("classes/db_matriculaserie_classe.php");
require_once("classes/db_matriculamov_classe.php");
require_once("classes/db_regencia_classe.php");
require_once("classes/db_diario_classe.php");
require_once("classes/db_diarioavaliacao_classe.php");
require_once("classes/db_diarioresultado_classe.php");
require_once("classes/db_diariofinal_classe.php");
require_once("classes/db_historico_classe.php");
require_once("classes/db_historicomps_classe.php");
require_once("classes/db_histmpsdisc_classe.php");
require_once("classes/db_regenciaperiodo_classe.php");
require_once("classes/db_procedimento_classe.php");
require_once("classes/db_alunocurso_classe.php");
require_once("classes/db_alunopossib_classe.php");
require_once("classes/db_amparo_classe.php");
require_once("dbforms/db_funcoes.php");

$ed101_d_data_dia = date("d",db_getsession("DB_datausu"));
$ed101_d_data_mes = date("m",db_getsession("DB_datausu"));
$ed101_d_data_ano = date("Y",db_getsession("DB_datausu"));
db_postmemory($HTTP_POST_VARS);
$cltrocaserie          = new cl_trocaserie;
$clalunocurso          = new cl_alunocurso;
$clalunopossib         = new cl_alunopossib;
$clturma               = new cl_turma;
$clturmaserieregimemat = new cl_turmaserieregimemat;
$clmatricula           = new cl_matricula;
$clmatriculaserie      = new cl_matriculaserie;
$clmatriculamov        = new cl_matriculamov;
$clregencia            = new cl_regencia;
$cldiario              = new cl_diario;
$clamparo              = new cl_amparo;
$cldiarioavaliacao     = new cl_diarioavaliacao;
$cldiarioresultado     = new cl_diarioresultado;
$cldiariofinal         = new cl_diariofinal;
$clhistorico           = new cl_historico;
$clhistoricomps        = new cl_historicomps;
$clhistmpsdisc         = new cl_histmpsdisc;
$clregenciaperiodo     = new cl_regenciaperiodo;
$clprocedimento        = new cl_procedimento;

$db_opcao = 1;
$db_botao  = true;
$escola    = db_getsession("DB_coddepto");
$oLibDocumento              = new libdocumento(5008);
$oLibDocumento->mes_extenso = db_mes($ed101_d_data_mes);
$oLibDocumento->dia         = $ed101_d_data_dia;
$oLibDocumento->ano         = $ed101_d_data_ano;
$aParagrafos                = $oLibDocumento->getDocParagrafos();
if (count($aParagrafos) > 0) {
  $ed101_t_obs = $aParagrafos[1]->oParag->db02_texto;
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
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
 <tr>
  <td width="360" height="18">&nbsp;</td>
  <td width="263">&nbsp;</td>
  <td width="25">&nbsp;</td>
  <td width="140">&nbsp;</td>
 </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <?MsgAviso(db_getsession("DB_coddepto"),"escola");?>
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Classificação de Aluno</b></legend>
    <?include("forms/db_frmtrocaserie.php");?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>
<script>
js_tabulacaoforms("form1","ed101_i_aluno",true,1,"ed101_i_aluno",true);
</script>