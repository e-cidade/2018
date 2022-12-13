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

include("fpdf151/pdf.php");
include("libs/db_libpessoal.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);
global $cfpess,$subpes;

$subpes = db_anofolha().'/'.db_mesfolha();

db_selectmax("cfpess"," select * from cfpess ".bb_condicaosubpes("r11_")); 

db_inicio_transacao();

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<br><br><br>
<center>
</center>
</body>
<?

global $subpes;

atualiza_folha_pasep_FPSF960($rh27_rubric);

//flush();
db_fim_transacao();
//flush();
db_redireciona("pes4_rhfopag96001.php");

function atualiza_folha_pasep_FPSF960($rh27_rubric){

global $subpes,$ponto;
       
      global $rhfopag;
      db_selectmax("rhfopag", "select rhfopag.*,
                                      trim(to_char(rh02_lota,'9999')) as r01_lotac,
                                      rh02_regist
                               from rhfopag inner join rhpessoalmov on rh02_anousu = ".db_anofolha()."
                                                                   and rh02_mesusu = ".db_mesfolha()."
                                                                   and rh02_instit = ".db_getsession("DB_instit")."
                                                                   and rh02_regist = rh66_regist
                               where rh66_valor > 0
                               order by rh66_pis");
//echo "<BR> ".$sql;exit;      
      db_criatermometro('calculo_folha','Concluido...','blue',1,'Inserindo rubrica no ponto');
      for($Ipes=0;$Ipes < count($rhfopag);$Ipes++){
          db_atutermometro($Ipes,count($rhfopag),'calculo_folha',1);
          if($ponto == 's'){
              $condicaoaux = " and r10_rubric = ".db_sqlformat($rh27_rubric)." and r10_regist = ".db_sqlformat( $rhfopag[$Ipes]["rh66_regist"] );

              if( !db_selectmax( "pontofs", "select * from pontofs ".bb_condicaosubpes("r10_"). $condicaoaux )){
                   $mtzCampos  = array();
                   $mtzValores = array();

                   $mtzCampos[1]   = "r10_anousu";
                   $mtzCampos[2]   = "r10_mesusu";
                   $mtzCampos[3]   = "r10_regist";
                   $mtzCampos[4]   = "r10_rubric";
                   $mtzCampos[5]   = "r10_valor";
                   $mtzCampos[6]   = "r10_quant";
                   $mtzCampos[7]   = "r10_lotac";
                   $mtzCampos[8]   = "r10_datlim";
                   $mtzCampos[9]   = "r10_instit";
                   $mtzValores[1]  = db_anofolha();
                   $mtzValores[2]  = db_mesfolha();
                   $mtzValores[3]  = $rhfopag[$Ipes]["rh02_regist"];
                   $mtzValores[4]  = $rh27_rubric;
                   $mtzValores[5]  = $rhfopag[$Ipes]["rh66_valor"];
                   $mtzValores[6]  = 0;
                   $mtzValores[7]  = $rhfopag[$Ipes]["r01_lotac"];
                   $mtzValores[8]  = "";
                   $mtzValores[9]  = db_getsession('DB_instit');
                   db_insert( "pontofs", $mtzCampos, $mtzValores );

              }
          }else{
              $condicaoaux = " and r47_rubric = ".db_sqlformat($rh27_rubric)." and r47_regist = ".db_sqlformat( $rhfopag[$Ipes]["rh66_regist"] );
              if( !db_selectmax( "pontocom", "select * from pontocom ".bb_condicaosubpes("r47_"). $condicaoaux)){
                   $mtzCampos  = array();
                   $mtzValores = array();

                   $mtzCampos[1]   = "r47_anousu";
                   $mtzCampos[2]   = "r47_mesusu";
                   $mtzCampos[3]   = "r47_regist";
                   $mtzCampos[4]   = "r47_rubric";
                   $mtzCampos[5]   = "r47_valor";
                   $mtzCampos[6]   = "r47_quant";
                   $mtzCampos[7]   = "r47_lotac";
                   $mtzCampos[8]   = "r47_instit";
                   $mtzValores[1]  = db_anofolha();
                   $mtzValores[2]  = db_mesfolha();
                   $mtzValores[3]  = $rhfopag[$Ipes]["rh02_regist"];
                   $mtzValores[4]  = $rh27_rubric;
                   $mtzValores[5]  = $rhfopag[$Ipes]["rh66_valor"];
                   $mtzValores[6]  = 0;
                   $mtzValores[7]  = $rhfopag[$Ipes]["r01_lotac"];
                   $mtzValores[8]  = db_getsession('DB_instit');
                   db_insert( "pontocom", $mtzCampos, $mtzValores );
              }
          }
      }
}

?>