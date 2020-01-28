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

include("classes/db_concilia_classe.php");
include("classes/db_conciliaitem_classe.php");
include("classes/db_conciliacor_classe.php");
include("classes/db_conciliapendcorrente_classe.php");
include("classes/db_conciliaextrato_classe.php");
include("classes/db_conciliapendextrato_classe.php");

db_postmemory($HTTP_GET_VARS);

$clconcilia             = new cl_concilia;
$clconciliaitem         = new cl_conciliaitem;
$clconciliacor          = new cl_conciliacor;
$clconciliapendcorrente = new cl_conciliapendcorrente;
$clconciliaextrato      = new cl_conciliaextrato;
$clconciliapendextrato  = new cl_conciliapendextrato;

$erromsg = "Processamento concluido com sucesso !";
$sqlerro = false;

$sqlVoltaConciliados  = " select k68_sequencial, ";
$sqlVoltaConciliados .= "        k83_sequencial  ";
$sqlVoltaConciliados .= "   from conciliaitem    ";
$sqlVoltaConciliados .= "        inner join concilia on k68_sequencial = k83_concilia "; 
$sqlVoltaConciliados .= "  where k68_data =  (select k68_data from concilia where k68_contabancaria = $conta order by k68_data limit 1) ";
$sqlVoltaConciliados .= "    and k68_contabancaria = $conta ";
$sqlVoltaConciliados .= "    and k83_conciliatipo = 3 limit 1 ";  
//die($sqlVoltaConciliados);
$rsNovasPendencias   = $clconcilia->sql_record($sqlVoltaConciliados);
$intNumrows = $clconcilia->numrows;
if ($intNumrows > 0 ){
	db_fieldsmemory($rsNovasPendencias,0);
}else{
  db_msgbox('Origem dos registros nao econtrada !');	
	echo " <script> parent.db_iframe_processar.hide(); </script>";
}

/* for excluindo da conciliacor */

  $arrayCorrente = split(',',$dados);
  db_inicio_transacao();
  foreach ($arrayCorrente as $i => $dadoscorrente){

    list ($id, $data, $autent) = split ('_', $dadoscorrente);
		$data = substr($data,6,4)."-".substr($data,3,2)."-".substr($data,0,2);

    $clconciliapendcorrente->excluir(null," k89_id = $id and k89_autent = $autent and k89_data = '".$data."'");
		if ($clconciliapendcorrente->erro_status == 0) {
			$sqlerro = true;
      $erromsg = $clconciliapendcorrente->erro_msg;						
		}
    for ($i = 0; $i < $intNumrows; $i++ ){
      db_fieldsmemory($rsNovasPendencias,$i);
      $clconciliacor->k84_concilia       = $k68_sequencial;
      $clconciliacor->k84_conciliaitem   = $k83_sequencial;
      $clconciliacor->k84_id             = $id;
      $clconciliacor->k84_data           = $data;
      $clconciliacor->k84_autent         = $autent;
      $clconciliacor->k84_conciliaorigem = 1; 
      $clconciliacor->incluir(null);
      if ($clconciliapendcorrente->erro_status == 0) {
        $sqlerro = true;
        $erromsg = $clconciliapendcorrente->erro_msg;           
        break;
      }
    }
		
	}
//	$sqlerro = true;
  db_fim_transacao($sqlerro);
	db_msgbox($erromsg);
	echo " <script> parent.db_iframe_processar.hide(); </script>";
  echo " <script> parent.document.location.href = 'cai4_excluipendenciasant.php';</script>";

?>