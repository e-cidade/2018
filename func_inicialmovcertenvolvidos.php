<?php
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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");

require_once("classes/db_parjuridico_classe.php");

$oGet = db_utils::postMemory($_GET);

$oDaoParjuridico   = db_utils::getDao('parjuridico');
$sSqlParjuridico   = $oDaoParjuridico->sql_query_file(db_getsession('DB_anousu'), db_getsession('DB_instit'));
$rsParjuridico     = $oDaoParjuridico->sql_record($sSqlParjuridico);

if ($oDaoParjuridico->numrows == 0) {
  die('Erro na configuração de parâmetros do jurídico.');
}

$oDaoArreMatric     = db_utils::getDao('arrematric');
 
$sSqlMatriculaInicial  = " select array_to_string(array_accum(distinct k00_matric),',') as k00_matric            \n";
$sSqlMatriculaInicial .= "   from arrematric                                                                     \n";
$sSqlMatriculaInicial .= "        inner join inicialnumpre on inicialnumpre.v59_numpre = arrematric.k00_numpre   \n";
$sSqlMatriculaInicial .= "  where inicialnumpre.v59_inicial = {$inicial}                                         \n";
$rsMatriculaInicial  = $oDaoArreMatric->sql_record($sSqlMatriculaInicial);
$iMatriculaInicial   = db_utils::fieldsMemory($rsMatriculaInicial, 0, null)->k00_matric;

$sSqlProprietarios  = " select j01_numcgm,                                   \n";
$sSqlProprietarios .= "        j01_matric,                                   \n";
$sSqlProprietarios .= "        q02_inscr,                                    \n";
$sSqlProprietarios .= "        z01_nome,                                     \n";
$sSqlProprietarios .= "        'Proprietario' as pessoa                      \n";
$sSqlProprietarios .= "   from iptubase                                      \n";
$sSqlProprietarios .= "        inner join cgm on z01_numcgm = j01_numcgm     \n";
$sSqlProprietarios .= "        left join issbase on q02_numcgm = z01_numcgm  \n";
$sSqlProprietarios .= "  where j01_matric in({$iMatriculaInicial})           \n";

$sSqlPromitentes  = " select j41_numcgm,                                    \n";
$sSqlPromitentes .= "        j41_matric,                                    \n";
$sSqlPromitentes .= "        q02_inscr,                                     \n";
$sSqlPromitentes .= "        z01_nome,                                      \n";
$sSqlPromitentes .= "        'Promitente' as pessoa                         \n";
$sSqlPromitentes .= "   from promitente                                     \n";
$sSqlPromitentes .= "        inner join cgm on z01_numcgm = j41_numcgm      \n";
$sSqlPromitentes .= "        left join issbase on q02_numcgm = z01_numcgm   \n";
$sSqlPromitentes .= "  where j41_matric in({$iMatriculaInicial})            \n";


$iEnvolInicialIptu      = db_utils::fieldsMemory($rsParjuridico, 0, null)->v19_envolinicialiptu;
$sWhere = "";

if ($iEnvolInicialIptu == 1) {
  $sWhere = " where pessoa = 'Proprietario' ";
} else if ($iEnvolInicialIptu == 2) {
  $sWhere = " where ( case 
                        when exists ( select 1 
                                        from ($sSqlPromitentes) as promitente )  
                             then pessoa = 'Promitente'
                        else true
                      end) "; 
}

$sSql = "select *
           from ( {$sSqlProprietarios} union {$sSqlPromitentes} ) as dados {$sWhere}";
$funcao_js = '';
?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?php db_app::load("estilos.css"); ?>
</head>
<body>
  <?php 
    db_lovrot($sSql,15,"()","",$funcao_js);
  ?>
</body>     
</html>