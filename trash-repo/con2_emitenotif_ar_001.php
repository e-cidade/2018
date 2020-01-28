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

include("libs/db_sql.php");
//include("fpdf151/fpdf.php");
require_once('fpdf151/PDF_Label.php');
include("classes/db_notificacao_classe.php");
include("classes/db_db_config_classe.php");
$cldb_config     = new cl_db_config;
$clnotificacao   = new cl_notificacao;

db_postmemory($HTTP_SERVER_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
if ( $contribuicao == '' ) {
     db_redireciona('db_erros.php?fechar=true&db_erro=Contribuição não encontrada!');
        exit;
}
$resultinst = $cldb_config->sql_record($cldb_config->sql_query(db_getsession("DB_instit")));
db_fieldsmemory($resultinst,0,true);

$head3 = 'Relação de Notificações';

$contr = '';
if (isset($campo)){
   if ($tipo == 2){
       $contr = " d07_contri = ".$contribuicao." and d07_matric in (".str_replace('-',', ',$campo).") ";
   }elseif ($tipo == 3){
       $contr = " d07_contri = ".$contribuicao." and d07_matric not in (".str_replace('-',', ',$campo).") ";
   }
}


$result = $clnotificacao->sql_record($clnotificacao->sql_noticontri($contribuicao,"","","contrinot.d08_notif,contrib.d07_contri as d08_contri ,contricalc.d09_matric as d08_matric ,contricalc.d09_numpre,contrib.d07_valor,edital.d01_numero,ruas.j14_nome,ruas.j14_tipo,edital.d01_numtot,edital.d01_perunica,d01_privenc, proprietario.z01_nome,proprietario.z01_ender,proprietario.z01_numero,proprietario.z01_munic,proprietario.z01_uf,proprietario.z01_compl,proprietario.z01_numcgm,proprietario.z01_bairro,proprietario.z01_cep","",$contr));
if ($clnotificacao->numrows == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Sem notificações a serem geradas. Verifique!');
}

//name		= nome da etiqueta
//paper-size	= tipo de pagina
//metric	= sistema de medida
//marginLeft	= margem esquerda
//marginTop	= margem de cima
//NX		= numero de etiquetas horizontal
//NY		= numero de etiquetas vertical
//SpaceX	= espaco entre etiquetas(lados)
//SpaceY	= espaco entre etiquetas(altura)
//width		= largura da etiqueta
//height	= altura da etiqueta
//font-size	= tamanho da fonte

$pdf = new PDF_Label (array('name'=>'5161','paper-size'=>'A4','metric'=>'mm','marginLeft'=>9,'marginTop'=>1,'NX'=>1,'NY'=>8,'SpaceX'=>1,'SpaceY'=>3,'width'=>100,'height'=>36,'font-size'=>9),1,1);
$pdf->Open();
for($x=0;$x < $clnotificacao->numrows;$x++){
   db_fieldsmemory($result,$x);
	$pdf->Add_PDF_Label(sprintf("%s\n%s, %s - %s\nCEP : %s %s\n%s - %s", "$z01_nome-$d08_matric", "$z01_ender","$z01_numero","$z01_compl","$z01_cep","$z01_bairro","$z01_munic","$z01_uf"));
}
$pdf->Output();