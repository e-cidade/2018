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
include("classes/db_rhpessoal_classe.php");
include("classes/db_rhpessoalmov_classe.php");
include("classes/db_ipe_classe.php");
include("classes/db_cfpess_classe.php");
include("dbforms/db_classesgenericas.php");
include("libs/db_libgertxtfolha.php");
$clrhpessoal = new cl_rhpessoal;
$clrhpessoalmov = new cl_rhpessoalmov;
$clipe = new cl_ipe;
$clcfpess = new cl_cfpess;
$aux = new cl_arquivo_auxiliar;
$cllayout_IPE= new cl_layout_IPE;
$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt25');
$clrotulo->label('DBtxt27');
$clrotulo->label('DBtxt28');
db_postmemory($HTTP_POST_VARS);

if(!isset($r36_anousu)){
  $r36_anousu = db_anofolha();
}

if(!isset($r36_mesusu)){
  $r36_mesusu = db_mesfolha();
}

$result_cfpess = $clcfpess->sql_record($clcfpess->sql_query_file($r36_anousu,$r36_mesusu,db_getsession('DB_instit')));
db_fieldsmemory($result_cfpess,0);

if(isset($incluir)){
  $where_unifica1 = "ipe.r36_anousu = " . $r36_anousu . " and ipe.r36_mesusu = " . $r36_mesusu. " and ipe.r36_matric = dados.rh14_matipe";
  if($unifica_ipe == 'f'){
     $where_unifica1= "ipe.r36_anousu = " . $r36_anousu . " and ipe.r36_mesusu = " . $r36_mesusu. " and ipe.r36_instit = ".db_getsession("DB_instit") . " and ipe.r36_matric = dados.rh14_matipe";
  }

  $sqlerro = false;
  $erro_msg = "Arquivo gerado com sucesso.";

  $sql_sum = $clipe->sql_query_file(null, 
                                    null, 
                                    null,
																		null,
                                    "sum(ipe.r36_valorc) as r36_valorc", 
                                    "",$where_unifica1);
  
  $cpsum  = $sql_sum;
  $matipe = "distinct on (rh14_matipe) rh14_matipe";
  $dbwhere = " and (rh14_matipe = 0  or rh14_matipe is null)";
  if($identificador == 1){
    $dbwhere = " and rh14_matipe > 0 ";
  }else if($r11_geracontipe == "f" && $identificador == 3){
    $matipe = "0 as rh14_matipe";
    $cpsum  = "r36_valorc";
  }

  $where_unifica  = "r36_anousu = " . $r36_anousu . " and r36_mesusu = " . $r36_mesusu. $dbwhere;
  if($unifica_ipe == 'f'){
     $where_unifica = "r36_anousu = " . $r36_anousu . " and r36_mesusu = " . $r36_mesusu. " and r36_instit = " . db_getsession("DB_instit"). $dbwhere;
  }
  $sql_dados = $clipe->sql_query_arquivo(null, null, null,
                                          $matipe.",
                                          rh14_contrato,
                                          r36_estado as rh14_estado,
                                          z01_nome,
                                          z01_ender,
                                          z01_cep,
                                          r36_dtalt,
                                          r36_dtvinc,
                                          case when rh01_regist is null then z01_sexo else rh01_sexo end as z01_sexo,
                                          case when rh01_regist is null then z01_estciv else rh01_estciv end as z01_estciv,
                                          case when rh01_regist is null then z01_nasc else rh01_nasc end as z01_nasc,
                                          z01_ident,
                                          z01_cgccpf,
					  r36_valorc",
                                         "rh14_matipe,z01_nome desc",$where_unifica);

  $sql = "
          select 
                 rh14_matipe,
                 rh14_contrato,
                 rh14_estado,
                 z01_nome,
                 z01_ender,
                 z01_cep,
                 r36_dtalt,
                 r36_dtvinc,
                 z01_sexo,
                 z01_estciv,
                 z01_nasc,
                 z01_ident,
                 z01_cgccpf,
                 (".$cpsum.") as r36_valorc
          from (".$sql_dados.") dados
          order by z01_nome
         ";
//   die($sql);
  $result_ipedados = $clipe->sql_record($sql);

  /*
  $result_ipedados = $clipe->sql_record($clipe->sql_query_arquivo(null,null,null,"
                                                                                  distinct on (".$matipe.") rh14_matipe,
                                                                                  rh14_contrato,
                                                                                  rh14_estado,
                                                                                  z01_nome,
                                                                                  z01_ender,
                                                                                  z01_cep,
                                                                                  r36_dtalt,
                                                                                  r36_dtvinc,
                                                                                  case when rh01_regist is null then z01_sexo else rh01_sexo end as z01_sexo,
                                                                                  case when rh01_regist is null then z01_estciv else rh01_estciv end as z01_estciv,
                                                                                  case when rh01_regist is null then z01_nasc else rh01_nasc end as z01_nasc,
                                                                                  z01_ident,
                                                                                  z01_cgccpf,
                                                                                  r36_contr1",
                                                                                 "rh14_matipe, z01_nome","r36_anousu = ".$r36_anousu." and r36_mesusu = ".$r36_mesusu.$dbwhere));
  */

  if($clipe->numrows > 0){

    $cllayout_IPE->IPEHeader_001_003 = $codipe;
    $cllayout_IPE->IPEHeader_012_017 = db_formatar($r36_anousu,"s","0",4,"e",0).db_formatar($r36_mesusu,"s","0",2,"e",0);
    $cllayout_IPE->IPEHeader_018_018 = $identificador;
    $cllayout_IPE->geraHeaderIPE();
 
    $qtdtotal = 0;
    $valtotal = 0;
    for($i=0; $i<$clipe->numrows; $i++){
      db_fieldsmemory($result_ipedados, $i);
      $qtdtotal ++;
      $valtotal += $r36_valorc; 
      $cllayout_IPE->IPERegistro_001_003 = $codipe;
      $cllayout_IPE->IPERegistro_004_011 = $rh14_contrato;
      $cllayout_IPE->IPERegistro_012_024 = $rh14_matipe;
      $cllayout_IPE->IPERegistro_025_026 = $rh14_estado;
      $cllayout_IPE->IPERegistro_027_058 = $z01_nome;
      $cllayout_IPE->IPERegistro_059_098 = $z01_ender;
      $cllayout_IPE->IPERegistro_099_106 = $z01_cep;
      $cllayout_IPE->IPERegistro_107_114 = $r36_dtvinc;
      $cllayout_IPE->IPERegistro_115_122 = $r36_dtalt;
      $cllayout_IPE->IPERegistro_123_130 = $z01_nasc;
      $cllayout_IPE->IPERegistro_131_131 = (strtoupper($z01_sexo) == "M" ? 1 : 2);
      $cllayout_IPE->IPERegistro_132_132 = $z01_estciv;
      $cllayout_IPE->IPERegistro_133_142 = $z01_ident;
      $cllayout_IPE->IPERegistro_143_153 = $z01_cgccpf;
      $cllayout_IPE->IPERegistro_154_164 = $r36_valorc;
      $cllayout_IPE->geraRegistIPE();
    }
 
    $cllayout_IPE->IPETrailler_001_003 = $codipe;
    $cllayout_IPE->IPETrailler_012_016 = $qtdtotal;
    $cllayout_IPE->IPETrailler_017_033 = $valtotal;
    $cllayout_IPE->geraTraillerIPE();
  }else{
    $sqlerro = true;
    $erro_msg = "Nenhum registro encontrado para geração do arquivo.";
  }

}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
  <tr>
    <td>
      <?
      include("forms/db_frmipe001.php");
      ?>
    </td>
  </tr>
</table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
js_tabulacaoforms("form1","orgao",true,1,"orgao",true);
</script>
<?
if(isset($incluir)){
  db_msgbox($erro_msg);
  if($sqlerro == false){
    echo "<script>js_arquivo_abrir('".$cllayout_IPE->nomearq."');</script>";
  }
}
?>