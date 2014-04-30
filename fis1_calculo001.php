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
require_once("dbforms/db_funcoes.php");
require_once("classes/db_arrecad_classe.php");
require_once("classes/db_arrecant_classe.php");
require_once("classes/db_vistorianumpre_classe.php");
require_once("classes/db_vistorias_classe.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clvistorias = new cl_vistorias;
$clarrecad   = new cl_arrecad;
$clarrecant  = new cl_arrecant;
$clvistorianumpre = new cl_vistorianumpre;
$clrotulo = new rotulocampo;
$clrotulo->label('y70_codvist');

$disable = false;

/**
 * Calcular 
 */
if ( isset($calcular) ) {

  /**
   * Verifica se tem empresa para inscricao 
   * Verifica se empresa esta paralisada 
   */
  try {

    $oDaoVistinscr = db_utils::getDao('vistinscr');
    $sSqlInscricao = $oDaoVistinscr->sql_query($y70_codvist, 'issbase.q02_inscr');
    $rsInscricao   = $oDaoVistinscr->sql_record($sSqlInscricao); 

    /**
     * Encontrou inscricao para vistoria 
     * Verifica se empresa esta paralisada
     */
    if ( $oDaoVistinscr->numrows > 0 ) {

      $iInscricao = db_utils::fieldsMemory($rsInscricao, 0)->q02_inscr;
      $oEmpresa = new Empresa($iInscricao);

      /**
       * Empresa paralisada 
       */
      if ( $oEmpresa->isParalisada() ) {
        throw new Exception(_M(Empresa::MENSAGENS . 'empresa_paralisada'));
      }
    }

    $result = $clvistorias->sql_calculo($y70_codvist);
    db_fieldsmemory($result,0);
    db_msgbox($fc_vistorias);

  } catch (Exception $oErro) {
    db_msgbox($oErro->getMessage());
  }

  db_redireciona("?y70_codvist=".@$y70_codvist);
  exit;

} else if(isset($y70_codvist)){

  $sSqlVist = "select   y69_numpre ,
                                   (select k00_numpre from arrecad        where k00_numpre = y69_numpre limit 1) as arrecad,
                                   (select k00_numpre from arrecant       where k00_numpre = y69_numpre limit 1) as arrecant,
                                   (select k00_numpre from arrepaga       where k00_numpre = y69_numpre limit 1) as arrepaga,
                                   (select k30_numpre from arreprescr     where k30_numpre = y69_numpre and arreprescr.k30_anulado is false limit 1) as arreprescr,
                                   (select k21_numpre from cancdebitosreg where k21_numpre = y69_numpre limit 1) as cancdebitos,
                                   (select arreprescr.k30_numpre 
                                      from arreprescr
                                     inner join divida on divida.v01_numpre = arreprescr.k30_numpre
                                                             and divida.v01_numpar = arreprescr.k30_numpar  
                                     inner join divold on divold.k10_coddiv = divida.v01_coddiv
                                     where divold.k10_numpre = y69_numpre and arreprescr.k30_anulado is false limit 1  ) as divprescr,		
                                   (select arrepaga.k00_numpre 
                                      from arrepaga
                                     inner join divida on divida.v01_numpre = arrepaga.k00_numpre
                                                      and divida.v01_numpar = arrepaga.k00_numpar  
                                     inner join divold on divold.k10_coddiv = divida.v01_coddiv
                                     where divold.k10_numpre = y69_numpre limit 1 ) as divpaga,
                                    (select v01_numpre 
									   from divida 
                                     inner join divold on divold.k10_coddiv = divida.v01_coddiv
                                     where divold.k10_numpre = y69_numpre limit 1 ) as divida									 
                                   from vistorianumpre where vistorianumpre.y69_codvist = $y70_codvist";

 $resultdebtfv= db_query($sSqlVist);
 $linhasdebtfv = pg_num_rows($resultdebtfv);
 $info = ""; 
 if ($linhasdebtfv>0) {
   db_fieldsmemory( $resultdebtfv, 0 );
   if($arrepaga != "") {
      $info= "Vistoria já Paga. Numpre: ".$arrepaga;
	  $disable = true;
   } else if($divpaga != "") {
      $info= "Vistoria paga após importada para a Divida. Numpre da Divida Paga: ".$divpaga;
	  $disable = true;
   } else if($arreprescr != ""){
      $info= "Vistoria Prescrita. Numpre:".$arreprescr; 			 
	  $disable = true;
   } else if($cancdebitos != ""){
      $info= "Calculo da Vistoria Cancelado. Numpre:".$cancdebitos; 			 
	  $disable = true;
   } else if($divprescr != ""){
	  $info= "Calculo da Vistoria em Divida Prescrita. Numpre:".$divprescr;	
	  $disable = true;
   } else if($divida != "") {
      $info= "Calculo da vistoria em Divida Ativa. Numpre:".$divida;
	  $disable = true;
   } else if ($arrecad != "") {
      $info= "Vistoria já Calculada. Numpre: ".$arrecad;
   }	  
 } else{  
      $info= "Vistoria não Calculada";
	  $disable = false; 
 }
}
?>
<html>
<head>
<title>Documento sem t&iacute;tulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
</script>
</head>
<body bgcolor=#CCCCCC bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<center>
<form method="post" name="form1" action="">
<input name="y70_codvist" type="hidden" value="<?=$y70_codvist?>">
<table width="60%">
    <tr>
      <td align="center"><br><br><font face="Arial, Helvetica, sans-serif"><strong>Cálculo da Vistoria</strong></font></td>
    </tr>
    <tr>
      <td><table width="100%">
  <tr>
    <td nowrap title="">
      <fieldset>
      <legend><strong>CÁLCULO: </strong></legend>
	<strong><?=$info?></strong>
      </legend>
    </td>
  </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td align="center"><input name="calcular" type="submit" value="Calcular" <? if($disable == true){ echo "disabled"; } ?> >
      </td>
      </tr>
  </table>
</form>
<script>
</script>
</center>
</body>
</html>
<?
if(@$disabilita == true){
  echo "<script>document.form1.calcular.disabled = true</script>";
}
?>