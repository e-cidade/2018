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
include("libs/db_utils.php");
include("fpdf151/pdf2.php");
include("libs/db_libdocumento.php");
include("classes/db_notificacao_classe.php");
include("classes/db_db_config_classe.php");
$cldb_config     = new cl_db_config;
$clnotificacao   = new cl_notificacao;
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
if ( $contribuicao == '' ) {
   db_redireciona('db_erros.php?fechar=true&db_erro=Contribuição não encontrada!');
   exit; 
}
$resultinst = $cldb_config->sql_record($cldb_config->sql_query(db_getsession("DB_instit")));
db_fieldsmemory($resultinst,0,true);

//$head1 = 'Departamento de Fazenda';
$pdf = new PDF2(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 

$contr = '';
if (isset($campo)){
   if ($tipo == 2){
       $contr = " d07_contri = ".$contribuicao." and d07_matric in (".str_replace('-',', ',$campo).") ";
   }elseif ($tipo == 3){
       $contr = " d07_contri = ".$contribuicao." and d07_matric not in (".str_replace('-',', ',$campo).") ";
   }
}else{
   $contr = '';
}

//die( $clnotificacao->sql_noticontri($contribuicao,"","","contrinot.d08_notif,contrib.d07_contri as d08_contr, contricalc.d09_matric as d08_matric, contricalc.d09_numpre,contrib.d07_valor,edital.d01_numero,ruas.j14_nome,ruas.j14_tipo,edital.d01_numtot,edital.d01_perunica,d01_privenc, proprietario.z01_nome,proprietario.z01_ender,proprietario.z01_numero,proprietario.z01_munic,proprietario.z01_uf,proprietario.z01_cep,proprietario.z01_compl,proprietario.z01_numcgm","",$contr,"proprietario.z01_nome"));
$sSQlNotificações =            $clnotificacao->sql_noticontri($contribuicao,
                                                                    "",
                                                                    "",
                                                                    "contrinot.d08_notif, 
                                                                     contrib.d07_contri as d08_contr,
                                                                     contricalc.d09_matric as d08_matric,
                                                                     contricalc.d09_numpre,
                                                                     contrib.d07_valor,
                                                                     edital.d01_numero,
                                                                     ruas.j14_nome,
                                                                     ruas.j14_tipo,
                                                                     edital.d01_numtot,
                                                                     edital.d01_perunica,
                                                                     d01_privenc,
                                                                     d01_receit,
                                                                     d01_data,
                                                                     proprietario.z01_nome,
                                                                     proprietario.z01_ender,
                                                                     proprietario.z01_numero,
                                                                     proprietario.z01_munic,
                                                                     proprietario.z01_uf,
                                                                     proprietario.z01_cep,
                                                                     proprietario.z01_compl,
                                                                     proprietario.z01_numcgm",
                                                                     "",
                                                                     $contr,
                                                                     "proprietario.z01_nome"
                                                                   );

$result = $clnotificacao->sql_record($sSQlNotificações);                                                                   

                                                                     
if ($clnotificacao->numrows == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Sem notificações a serem geradas. Verifique!');
}
$oDocumento        = new libdocumento(1706);
$oDocumento->getParagrafos();
$oDocAssinatura    = new libdocumento(1707);
$oDocAssinatura->getParagrafos();
$aCodigoAssinatura = $oDocAssinatura->aParagrafos[1]->db02_texto;
if ( $tiporel == 1 ) {
    
   for($x=0;$x < $clnotificacao->numrows;$x++){
     
      $oNotif = db_utils::fieldsmemory($result,$x);
      $pdf->AddPage();
      $pdf->SetFont('Arial','',13);
      $oNotif->xtipo = "Rua ";
      if($oNotif->j14_tipo == 'A'){
         $oNotif->xtipo = "Av. ";
      }elseif($oNotif->j14_tipo == 'T'){
         $oNotif->xtipo = "Trav. ";
      }	
      $sSqlTtipoCorrecao         =  "SELECT k02_corr,  i01_descr"; 
      $sSqlTtipoCorrecao        .= " from tabrecregrasjm ";
      $sSqlTtipoCorrecao        .= "      inner join tabrec   on       k04_receit  = k02_codigo ";
      $sSqlTtipoCorrecao        .= "      inner join tabrecjm on tabrec.k02_codjm  = tabrecjm.k02_codjm ";
      $sSqlTtipoCorrecao        .= "      inner join inflan   on tabrecjm.k02_corr = inflan.i01_codigo";
      $sSqlTtipoCorrecao        .= " where '{$oNotif->d01_data}' between k04_dtini ";
      $sSqlTtipoCorrecao        .= "   and k04_dtfim and k04_receit = {$oNotif->d01_receit}";
      $rsTipoCorrecao            = db_query($sSqlTtipoCorrecao);
      
      $oDocumento->d01_numero    = $oNotif->d01_numero;
      $oDocumento->j14_nome      = $oNotif->xtipo." ".trim($oNotif->j14_nome);
      $oDocumento->d08_matric    = $oNotif->d08_matric;
      $oDocumento->d07_valor     = trim(db_formatar($oNotif->d07_valor,'f'));
      $oDocumento->valor_extenso = trim(db_extenso($oNotif->d07_valor));
      $oDocumento->d01_perunica  = $oNotif->d01_perunica;
      $oDocumento->d01_numtot    = $oNotif->d01_numtot;
      $oDocumento->d01_privenc   = db_Formatar($oNotif->d01_privenc,"d");
      $oDocumento->strcorrecao   = db_utils::fieldsMemory($rsTipoCorrecao,0)->k02_corr;
      $oDocumento->descrcorrecao = ucwords(strtolower(db_utils::fieldsMemory($rsTipoCorrecao,0)->i01_descr));
      $oDocumento->ender         = $ender;
      $oDocumento->bairro        = $bairro;
      $oDocumento->munic         = $munic;
      $aParagrafosNotificacao = $oDocumento->getDocParagrafos();
      $pdf->multicell(0,4,ucwords(strtolower($munic)).", ".date('d',db_getsession("DB_datausu"))." de ".db_mes(date('m',db_getsession("DB_datausu")))." de ".date('Y',db_getsession("DB_datausu")).".",0,"R",0);
      $pdf->ln(5);
      $pdf->SetFont('Arial','B',13);
      $pdf->multicell(0,6,"Notificação de Contribuição de Melhoria: ".$oNotif->d08_notif,0,"C",0);
      $pdf->SetFont('Arial','',13);
      $pdf->ln(5);
      $pdf->setx(35);
      $pdf->multicell(0,6,"Prezado Senhor(a),",0,"L",0);
      $pdf->ln(5);
      foreach ($aParagrafosNotificacao as $iIndex => $oParagrafo) {
      	$pdf->multicell(0,6,$oParagrafo->oParag->db02_texto,0,
      	               $oParagrafo->oParag->db02_alinhamento,0,$oParagrafo->oParag->db02_inicia);
      }
      $pdf->ln(5);
      $pdf->ln(5);
      $pdf->setx(100);
      $posicaoy = $pdf->gety();
      if ($aCodigoAssinatura != "") {
        eval($aCodigoAssinatura);  
      }
      $pdf->text(10,244,"Ilmo Sr.(a) ");
      $pdf->SetFont('Arial','',10);
      $pdf->text(10,250,$oNotif->z01_nome);
      $pdf->text(10,256,$oNotif->z01_ender.", ".$oNotif->z01_numero." ".$oNotif->z01_compl);
      $pdf->text(10,262,$oNotif->z01_munic." - ".$oNotif->z01_uf);
      $pdf->text(10,268,$oNotif->z01_cep);
      
   }
} elseif( $tiporel == 2 ) {
  
   $pdf->addpage();
   $pdf->setfillcolor(235);
   $pdf->setfont('arial','b',8);
   $pdf->cell(15,05,'Notificação',1,0,"c",1);
   $pdf->cell(15,05,'Matrícula',1,0,"c",1);
   $pdf->cell(15,05,'Numcgm',1,0,"c",1);
   $pdf->cell(80,05,'Nome',1,1,"c",1);
   $pdf->setfont('arial','',8);
   $total = 0;
   for($x=0;$x < $clnotificacao->numrows;$x++){
     
     db_fieldsmemory($result,$x);
     if ($pdf->gety() > $pdf->h - 35) {
       
        $pdf->addpage();
        $pdf->setfont('arial','b',8);
        $pdf->cell(15,05,'Notificação',1,0,"c",1);
        $pdf->cell(15,05,'Matrícula',1,0,"c",1);
        $pdf->cell(15,05,'Numcgm',1,0,"c",1);
        $pdf->cell(80,05,'Nome',1,1,"c",1);
        $pdf->setfont('arial','',8);
        
     }
     
     $pdf->cell(15,05,$d08_notif,0,0,"R",0);
     $pdf->cell(15,5,$d08_matric,0,0,"R",0);
     $pdf->cell(15,5,$z01_numcgm,0,0,"R",0);
     $pdf->cell(80,5,$z01_nome,0,1,"L",0);
     $total += 1;
   }
   $pdf->cell(125,05,'Total de Registros:   '.$total,1,1,"c",1);


}
$pdf->Output();