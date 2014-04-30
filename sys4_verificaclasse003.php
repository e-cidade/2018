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
	include("dbforms/db_funcoes.php");
	include("libs/db_usuariosonline.php");
	include("classes/db_db_sysclasseatualizareg_classe.php");
	include("classes/db_db_sysclasses_classe.php");
	$cldb_sysclasses = new cl_db_sysclasses;
	$cldb_sysclassesatualizareg = new cl_db_sysclasseatualizareg;
	db_postmemory($_POST);
	$usu= db_getsession("DB_id_usuario");

	$arr = split("\|", $classes);
	$count = count($arr);
  
  $lErro = false;
	db_inicio_transacao();
	$sqltab ="select * from pg_tables where tablename = 'temp_classeatualiza'";
	$resulttab = pg_query($sqltab);
	$linhatab = pg_num_rows($resulttab);
	if($linhatab>0){
		for($x=0;$x<$count;$x++){
			$sql = "select * from temp_classeatualiza where seq = ".$arr[$x];
			//die($sql);
			$result = pg_query($sql);
			db_fieldsmemory($result,0);

			// tem q gravar na db_sysclasseatualizareg e na db_sysclasses
			$cldb_sysclassesatualizareg->ip            = "0";
			$cldb_sysclassesatualizareg->codusu        = $usu;
			$cldb_sysclassesatualizareg->dataalt       = date("Y-m-d");
			$cldb_sysclassesatualizareg->horaalt       = date("H:i");
			$cldb_sysclassesatualizareg->codarq        = $codarq;
			$cldb_sysclassesatualizareg->nomearq       = $nomearq;
			$cldb_sysclassesatualizareg->metodo        = $metodo;
			$cldb_sysclassesatualizareg->fontenovo     = addslashes($fontenovo);
			$cldb_sysclassesatualizareg->fonteoriginal = addslashes($fonteorig);
			$cldb_sysclassesatualizareg->incluir(null);
      if ($cldb_sysclassesatualizareg->erro_status == 0) {
        $sErroMsg = $cldb_sysclassesatualizareg->erro_msg. " Classe : $nomearq Método : $metodo";
        $lErro    = true;        
        break;
      }
			
			$cldb_sysclasses ->excluir ($codarq,$metodo);
      if ($cldb_sysclasses->erro_status == 0) {
         $sErroMsg = $cldb_sysclasses->erro_msg;
         $lErro    = true;
         break;
      }
			 
			$cldb_sysclasses->codarq      = $codarq;
			$cldb_sysclasses->nomclasse   = $metodo;
			$cldb_sysclasses->descrclasse = 'Atualizado pelo sistema';
			$cldb_sysclasses->codigoclass = addslashes($fontenovo);
			$cldb_sysclasses->incluir($codarq,$metodo);
      if ($cldb_sysclasses->erro_status == 0) {
         $sErroMsg = $cldb_sysclasses->erro_msg;
         $lErro    = true;
         break;
      }

		}
	}
  
  db_fim_transacao($lErro);
  if ($lErro) {
    db_msgbox($sErroMsg);
  }

	// drop a tabela
	$sqldrop = "DROP TABLE temp_classeatualiza";
	$resultdrop = @pg_query($sqldrop);
	//echo "<script>parent.js_apaga()</script>";
?>