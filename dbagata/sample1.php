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

#+-----------------------------------------------------------------+
#| AGATA Report API (http://www.agata.dalloglio.net)               |
#| Licensed under GPL: http://www.fsf.org for further details      |
#+-----------------------------------------------------------------+
#| Started in  2001, August, 10                                    |
#| Author: Pablo Dall'Oglio (pablo@dalloglio.net)                  |
#+-----------------------------------------------------------------+
#| Agata Report: A Database reporting tool written in PHP-GTK      |
#| This file shows how to use AgataAPI to generate simple reports  |
#+-----------------------------------------------------------------+

# Include AgataAPI class
include_once '/usr/local/agata/classes/core/AgataAPI.class';

$api2 = new AgataAPI;
$api2->setReportPath('/usr/local/agata/reports/samples/general/template.agt');
$xml = $api2->getReport();
$header = $xml['Report']['Header']['Body'];

# Instantiate AgataAPI
$api = new AgataAPI;
$api->setLanguage('en'); //'en', 'pt', 'es', 'de', 'fr', 'it', 'se'
$api->setReportPath('/usr/local/agata/reports/samples/general/customers.agt');

$report = $api->getReport();
$report['Report']['Header']['Body'] = $header;
$api->setReport($report);

//$api->setProject('Samples');
$api->setFormat('pdf'); // 'pdf', 'txt', 'xml', 'html', 'csv', 'sxw'
$api->setOutputPath('/tmp/test.pdf');
$api->setLayout('default-PDF');
var_dump($api->GetParameters());
#How to set parameters, if they exist
$api->setParameter('$city', 1);
#$api->setParameter('$personCode', 4);
#$api->setParameter('$personName', "'mary'");
$ok = $api->generateReport();
if (!$ok)
{
    echo $api->getError();
}
else
{
    // opens file dialog
//    $api->fileDialog();
}
?>