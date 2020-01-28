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

db_postmemory($_POST);
$clcorrente = new cl_corrente;

$strRetorno = "";
$pipe       = "";
$traco      = "-";
$sqlData  = " select distinct k12_data ";
$sqlData .= "   from ( ";
$sqlData .= " select distinct k12_data ";
$sqlData .= "   from corrente ";
$sqlData .= "        left  join conciliacor  on k84_data   = k12_data ";
$sqlData .= "                               and k84_id     = k12_id ";
$sqlData .= "                               and k84_autent = k12_autent ";
$sqlData .= "  where ( k84_data is null and k84_id is null and k84_autent is null )";
//$sqlData .= "    and k12_conta  = $conta ";
$sqlData .= "    and k12_conta  in ( select c61_reduz ";
$sqlData .= "                          from contabancaria ";
$sqlData .= "                               inner join conplanocontabancaria on conplanocontabancaria.c56_contabancaria = contabancaria.db83_sequencial "; 
$sqlData .= "                               inner join conplanoreduz         on conplanoreduz.c61_codcon = conplanocontabancaria.c56_codcon ";
$sqlData .= "                                                               and conplanoreduz.c61_anousu = conplanocontabancaria.c56_anousu ";
$sqlData .= "                                                               and conplanoreduz.c61_anousu = ".db_getsession('DB_anousu'); 
$sqlData .= "                                                               and conplanoreduz.c61_instit = ".db_getsession('DB_instit'); 
$sqlData .= "                         where contabancaria.db83_sequencial = {$conta} )";

$sqlData .= " union ";
$sqlData .= "   select distinct k86_data as k12_data ";
$sqlData .= "     from extratolinha ";
$sqlData .= "          left  join conciliaextrato on k87_extratolinha  = k86_sequencial ";
$sqlData .= "          left  join concilia        on k68_data          = k86_data ";
$sqlData .= "                                    and k68_contabancaria = k86_contabancaria ";
$sqlData .= "    where k87_extratolinha is null ";
$sqlData .= "      and (k68_data is null and k68_contabancaria is null)";
$sqlData .= "      and k86_contabancaria = $conta ";
$sqlData .= " ) as x ";
$sqlData .= " order by k12_data desc ";

//die($sqlData);

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