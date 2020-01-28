<?php
require_once 'classes/core/AgataAPI.class';

/*
$api2 = new AgataAPI;
$api2->setReportPath('reports/samples/general/template.agt');
$xml = $api2->getReport();
$header = $xml['Report']['Header']['Body'];
*/
$header = 'test';

# Instantiate AgataAPI
$api = new AgataAPI;
$api->setLanguage('en'); //'en', 'pt', 'es', 'de', 'fr', 'it', 'se'
$api->setReportPath('reports/samples/general/customers.agt');

$report = $api->getReport();
$report['Report']['Header']['Body'] = $header;
$api->setReport($report);

//$api->setProject('Samples');
$api->setFormat('pdf'); // 'pdf', 'txt', 'xml', 'html', 'csv', 'sxw'
$api->setOutputPath('/tmp/teste1.pdf');
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
?>
