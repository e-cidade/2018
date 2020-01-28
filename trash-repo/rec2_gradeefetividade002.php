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

include("fpdf151/pdf.php");
include("libs/db_libpessoal.php");
include("dbforms/db_funcoes.php");
include("libs/db_sql.php");
include("rec2_gradeefetividade003.php");

global $cfpess,$subpes,$db21_codcli,$matric ;


db_postmemory($HTTP_GET_VARS);

$subpes = db_anofolha().'/'.db_mesfolha();

$tipos = split(",", $tipos);
//echo "<BR> tipos --> ".print_r($tipos);

if($pcount == 3){
   imp_gradeefetividade($pcount,$regist,$datacert,$tipos,true,true,true,$certinic);
} else if($pcount == 2){
   imp_gradeefetividade($pcount,$regist,$datacert);
}else{
   imp_gradeefetividade($pcount,$regist,$datacert,$tipos,false,false,$tipo_certd,$certinic,$num_proc);
}

//exit;

?>
