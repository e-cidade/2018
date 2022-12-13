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
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_libsys.php");
db_postmemory($HTTP_GET_VARS);
# Include AgataAPI class
include_once 'dbagata/classes/core/AgataAPI.class';

//$objGet  = db_utils::postmemory($_GET);

ini_set("error_reporting","E_ALL & ~NOTICE");


# Instantiate AgataAPI
$clagata = new cl_dbagata("configuracoes/con4_viradadeano003.agt");

$api = $clagata->api;

//exemplo para trocar o tipo de extensão do arquivo
  //$api->setFormat('csv');
//$clagata->arquivo = $clagata->arquivo.".".$clagata->api->format;
  //$api->setOutputPath($clagata->arquivo);

//mudar o padrao de saida
//$api->setFormat('pdf'); // 'pdf', 'txt', 'xml', 'html', 'csv', 'sxw'
$sqlcab = "select c30_sequencial,c30_anoorigem,c30_anodestino,c30_data,c30_hora,nome 
			from db_virada 
			inner join db_usuarios on id_usuario = c30_usuario 
			where c30_sequencial = ".$virada;
$resultcab = pg_query($sqlcab);
db_fieldsmemory($resultcab, 0);

$api->setParameter('$head1', "Lista das Inconsistências Geradas");
$api->setParameter('$head2', "Virada: ".$virada);
$api->setParameter('$head3', "Usuário: ".$nome);
$api->setParameter('$head4', "Data: ".db_formatar($c30_data,"d")." Hora:".$c30_hora);
$api->setParameter('$head5', "Ano exercício: ".$c30_anoorigem);
$api->setParameter('$head6', "Ano destino: ".$c30_anodestino);
$api->setParameter('$virada', "$virada");

$api->setLayout('dbseller');

$ok = $api->generateReport();
if (!$ok)
{
    echo $api->getError();
}
else
{ 
    db_redireciona($clagata->arquivo);
}
?>