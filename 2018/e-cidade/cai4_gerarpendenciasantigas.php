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


/* for excluindo da conciliacor */

  $arrayCorrente = split(',',$dados);
  db_inicio_transacao();
  foreach ($arrayCorrente as $i => $dadoscorrente){

    list ($id, $data, $autent) = split ('_', $dadoscorrente);
		$data = substr($data,6,4)."-".substr($data,3,2)."-".substr($data,0,2);

    $clconciliacor->excluir(null," k84_id = $id and k84_autent = $autent and k84_data = '".$data."'");
		if ($clconciliacor->erro_status == 0) {
			$sqlerro = true;
      $erromsg = $clconciliacor->erro_msg;						
		}
    
    $rsNovasPendencias = $clconcilia->sql_record($clconcilia->sql_query_file(null,"k68_sequencial","k68_sequencial","k68_contabancaria = $conta "));
    $intNumrows = $clconcilia->numrows;
		for ($i = 0; $i < $intNumrows; $i++ ){
			db_fieldsmemory($rsNovasPendencias,$i);
			$clconciliapendcorrente->k89_concilia       = $k68_sequencial;
      $clconciliapendcorrente->k89_id             = $id;
      $clconciliapendcorrente->k89_data           = $data;
      $clconciliapendcorrente->k89_autent         = $autent;
			$clconciliapendcorrente->k89_conciliaorigem = 3; 
      $clconciliapendcorrente->incluir(null);
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
  echo " <script> parent.document.location.href = 'cai4_gerapendenciasant.php';</script>";

?>