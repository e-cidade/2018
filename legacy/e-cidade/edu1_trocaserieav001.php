<?
/*
 *     E-cidade Software P�blico para Gest�o Municipal                
 *  Copyright (C) 2014  DBseller Servi�os de Inform�tica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa � software livre; voc� pode redistribu�-lo e/ou     
 *  modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a vers�o 2 da      
 *  Licen�a como (a seu crit�rio) qualquer vers�o mais nova.          
 *                                                                    
 *  Este programa e distribu�do na expectativa de ser �til, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia impl�cita de              
 *  COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM           
 *  PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU     
 *  junto com este programa; se n�o, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  C�pia da licen�a no diret�rio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_stdlibwebseller.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_libdocumento.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_app.utils.php");
require_once ("dbforms/db_funcoes.php");

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
$db_botao = true;
$escola   = db_getsession("DB_coddepto");

$oLibDocumento              = new libdocumento(5007);
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
<script type="text/javascript" src="scripts/scripts.js"></script>
<script type="text/javascript" src="scripts/prototype.js"></script>
<script type="text/javascript" src="scripts/classes/educacao/DBViewFormularioEducacao.classe.js"></script>
<script type="text/javascript" src="scripts/classes/educacao/escola/TurmaTurnoReferente.classe.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC">
   <?
   MsgAviso(db_getsession('DB_coddepto'), 'escola');
   ?>
   <?include("forms/db_frmtrocaserieav.php");?>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>
<script>
js_tabulacaoforms("form1","ed101_i_aluno",true,1,"ed101_i_aluno",true);
</script>