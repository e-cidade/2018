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
include("libs/db_sql.php");
//require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("libs/db_utils.php");

db_postmemory($_POST);

$db_opcao = 1;
$db_botao = true;

if(isset($gerar)){
//echo "<br> <br><br><br>entrou 1";
      

      $nometmp = $_FILES["r56_dirarq"]["tmp_name"];
         
      // Seta o nome do arquivo destino do upload
      $arquivoprocessa = "/tmp/retbanriname.txt";
      $arquivogeracao = "/tmp/retbanri.txt";

      pg_query('begin;');
         
      // Faz um upload do arquivo para o local especificado
      move_uploaded_file($nometmp,$arquivoprocessa) or $erro_msg = "ERRO: Problemas com upload, contate o suporte.";

      // Abre o arquivo
      $ponteiro = fopen("$arquivoprocessa","r") or $erro_msg = "ERRO: Arquivo não abre.";

      $linhacorrente = 0;
      $usu_com_ponto = 0;
      $erro          = 0;


      $head3 = "SERVIDORES EM CONFLITO NO PONTO";

      $pdf = new PDF(); 
      $pdf->Open(); 
      $pdf->AliasNbPages(); 
      $tot_func = 0;
      $alt   = 4;
      $pdf->setfillcolor(235);
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(20,$alt,'MATRICULA',1,0,"C",1);
      $pdf->cell(80,$alt,'NOME',1,0,"C",1);
      $pdf->cell(20,$alt,'RUBRICA',1,0,"C",1);
      $pdf->cell(20,$alt,'QUANT',1,0,"C",1);
      $pdf->cell(20,$alt,'VALOR',1,1,"C",1);
//echo "<br> entrou 2";

      while(!feof($ponteiro)){
//echo "<br> entrou 3";

        $poslinha = fgets($ponteiro,4096);


        $matricula      = trim(substr($poslinha,  0,  4)) + 0;
        $rubrica        = trim(substr($poslinha,  4,  3)) + 0;
        $quantidade     = trim(substr($poslinha,  7,  5)) + 0;
 
        if($quantidade == 0){
          continue;
        }
    	  $linhacorrente ++;

        $sql_pessoal    =  "select * 
                            from rhpessoal
                                 inner join cgm          on rh01_numcgm = z01_numcgm
                                 inner join rhpessoalmov on rh02_anousu = $anousu 
                                                        and rh02_mesusu = $mesusu 
                                                        and rh02_regist = rh01_regist
                                                        and rh02_instit = ".db_getsession('DB_instit')."
                                 left join rhpesrescisao on rh02_seqpes = rh05_seqpes
                            where rh01_regist = $matricula
                           ";
        $res_pessoal = pg_query($sql_pessoal);
        $num_pessoal = pg_numrows($res_pessoal);
        if($num_pessoal > 0){
          db_fieldsmemory($res_pessoal,0);
//echo "<br> entrou 4";

          $sql_busca_rub = "select * 
                            from pontofs 
                                 inner join rhpessoal on rh01_regist = r10_regist
                                 inner join cgm       on rh01_numcgm = z01_numcgm
                            where r10_anousu = $anousu 
                              and r10_mesusu = $mesusu 
                              and r10_regist = $matricula 
                              and r10_rubric = lpad($rubrica,4,'0')";
          $res_busca_rub = pg_query($sql_busca_rub);
          if(pg_numrows($res_busca_rub) > 0 ){
//echo "<br> entrou 5";
             db_fieldsmemory($res_busca_rub,0);
//echo "<br> entrou 6";
             $pdf->cell(20,$alt,$rh01_regist,0,0,"C",0);
             $pdf->cell(80,$alt,$z01_nome,0,0,"L",0);
             $pdf->cell(20,$alt,$r10_rubric,0,0,"C",0);
             $pdf->cell(20,$alt,db_formatar($r10_quant,'f'),0,0,"R",0);
             $pdf->cell(20,$alt,db_formatar($r10_valor,'f'),0,1,"R",0);
             $usu_com_ponto ++;
             $erro = 1;
             $erro_msg = "ERRO: Existem servidores com rubricas a serem importadas no ponto.Verifique o relatório!";
          }elseif(trim($rh05_recis) != '' ){
             $pdf->cell(20,$alt,$rh01_regist,0,0,"C",0);
             $pdf->cell(80,$alt,$z01_nome.'  (DEMITIDO)',0,0,"L",0);
             $pdf->cell(20,$alt,$rubrica,0,0,"C",0);
             $pdf->cell(20,$alt,db_formatar(($quantidade / 100),'f'),0,0,"R",0);
             $pdf->cell(20,$alt,db_formatar(0,'f'),0,1,"R",0);
             $usu_com_ponto ++;
             $erro = 2;
             $erro_msg = "ERRO: Existem servidores com rubricas a serem importadas no ponto.Verifique o relatório!";
          }else{
             $sql_insere_ponto = "insert into pontofs (
                                                      r10_anousu,
                                                      r10_mesusu,
                                                      r10_regist,
                                                      r10_rubric,
                                                      r10_valor,
                                                      r10_quant,
                                                      r10_lotac,
                                                      r10_datlim,
                                                      r10_instit
                                                      )values(
                                                      $anousu,
                                                      $mesusu,
                                                      $matricula,
                                                      lpad($rubrica, 4, '0'),
                                                      0,
                                                      ".($quantidade / 100).",
                                                      '$rh02_lota',
                                                      '',
                                                      $rh02_instit
                                                      )";
            $res_insere_ponto = pg_query($sql_insere_ponto) or $erro_msg = "ERRO no SQL : $sql_insere_ponto. Contate Suporte!";
          }

        }else{
          $pdf->cell(20,$alt,$matricula,0,0,"C",0);
          $pdf->cell(80,$alt,'SERVIDOR NAO CADASTRADO',0,0,"L",0);
          $pdf->cell(20,$alt,$rubrica,0,0,"C",0);
          $pdf->cell(20,$alt,db_formatar(($quantidade / 100 ),'f'),0,0,"R",0);
          $pdf->cell(20,$alt,db_formatar($r10_valor,'f'),0,1,"R",0);
             $usu_com_ponto ++;
        }
//        echo "<br> matricula -> $matricula   rubrica -> $rubrica   quantidade -> $quantidade ";
      }
      if($usu_com_ponto > 0){
//echo "<br> entrou 6";
        $pdf->setfont('arial','b',8);
        $pdf->cell(160,$alt,'TOTAL DE REGISTROS :  '.$usu_com_ponto,"T",0,"C",0);

        $pdf->Output();
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
        <?
        include("forms/db_frmcanimportarelogio.php");
        ?>
    </center>
        </td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
<script>
js_tabulacaoforms("form1","anousu",true,1,"anousu",true);
</script>
</html>
<?
if(isset($gerar) || isset($confirma)){
  if($erro == 1){
    db_msgbox($erro_msg);
  }else{
    $linhacorrente;
    db_msgbox($linhacorrente." registros incluídos.");
    pg_query('commit;');
  };
}
?>