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
include("classes/db_corrente_classe.php");

db_postmemory($HTTP_POST_VARS);
$clcorrente = new cl_corrente;

$strRetorno = "";
$pipe       = "";
$traco      = "-";

$sWhereReduz  = " select c61_reduz ";
$sWhereReduz .= "   from contabancaria ";
$sWhereReduz .= "        inner join conplanocontabancaria on conplanocontabancaria.c56_contabancaria = contabancaria.db83_sequencial ";
$sWhereReduz .= "        inner join conplanoreduz         on conplanoreduz.c61_codcon = conplanocontabancaria.c56_codcon ";
$sWhereReduz .= "                                        and conplanoreduz.c61_anousu = conplanocontabancaria.c56_anousu ";
$sWhereReduz .= "                                        and conplanoreduz.c61_anousu = ".db_getsession('DB_anousu');
$sWhereReduz .= "                                        and conplanoreduz.c61_instit = ".db_getsession('DB_instit');
$sWhereReduz .= "  where contabancaria.db83_sequencial = {$conta} ";


$sqlData  = " select distinct k12_data ";
$sqlData .= "   from corrente ";
$sqlData .= "        inner join conciliapendcorrente on k89_data   = k12_data ";
$sqlData .= "                                       and k89_id     = k12_id ";
$sqlData .= "                                       and k89_autent = k12_autent ";
$sqlData .= "                                       and k89_conciliaorigem = 3 ";
$sqlData .= "  where k12_conta in ( $sWhereReduz ) ";
$sqlData .= " order by k12_data desc ";

//echo $sqlData;

$rsDatas = $clcorrente->sql_record($sqlData);
$numrows = $clcorrente->numrows;

//echo pg_last_error();

if ($numrows > 0 ) {
  for($i=0;$i<$numrows;$i++){
    db_fieldsmemory($rsDatas,$i);
	  $strRetorno .= $pipe.$k12_data.';'.db_formatar($k12_data,'d');
	  $pipe = "|";
  }
}else{
  $strRetorno .= '0;Sem datas disponiveis para a conta selecionada';
}

echo $strRetorno;

?>