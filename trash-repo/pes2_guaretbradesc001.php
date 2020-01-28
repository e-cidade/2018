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
include("classes/db_movrel_classe.php");
include("classes/db_convenio_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clmovrel = new cl_movrel;
$clconvenio = new cl_convenio;
$db_opcao = 1;
$db_botao = true;
if(isset($gerar)){

  function testavalor($valor=null){
    if(is_numeric($valor) == true){
      return $valor;
    }else{
      return 0;
    }
  }

  $sqlerro = false;
  $dbcontator = 0; 
  $result_dados = $clconvenio->sql_record($clconvenio->sql_query_relac($r54_codrel));
  if($clconvenio->numrows > 0){
    db_fieldsmemory($result_dados, 0);

    if(trim($r55_rubr01) != ""){

      include("dbforms/db_layouttxt.php");

      // Nome do novo arquivo
      $nomearq = $_FILES["r56_dirarq"]["name"];
         
      // Nome do arquivo tempor�rio gerado no /tmp
      $nometmp = $_FILES["r56_dirarq"]["tmp_name"];
         
      // Seta o nome do arquivo destino do upload
      $arquivoprocessa = "/tmp/retbradename.txt";
      $arquivogeracao  =  "/tmp/retbradeco.txt";
         
      // Faz um upload do arquivo para o local especificado
      move_uploaded_file($nometmp,$arquivoprocessa) or $erro_msg = "ERRO: Problemas com upload, contate o suporte.";

      // Abre o arquivo
      $ponteiro = fopen("$arquivoprocessa","r") or $erro_msg = "ERRO: Arquivo n�o abre.";

      $cldb_layouttxt = new db_layouttxt(30, $arquivogeracao);
      $linhacorrente  = 0;
      $somavalmovrel  = 0;
      $tot_rejeitados = 0;
      while(!feof($ponteiro)){
        $poslinha = fgets($ponteiro,4096);
				if($poslinha=="" || substr($poslinha, 0, 6) == "999999"){
				  continue;
				}
				$linhacorrente ++;

          $matricula      = trim(substr($poslinha,  0,  6)) + 0;
          $z01_nome       = trim(substr($poslinha,  6, 45));
          $valor          = trim(substr($poslinha, 51, 12)) + 0;
          $filler         = trim(substr($poslinha, 74,  5));

          $valor /= 100;

          $total = 0;
//          if($matricula == 274070 ){
//            echo "<br><br>".($clmovrel->sql_query_gerfsal(null," distinct gerfsal.* ",""," r54_anomes = '".$r54_anousu.$r54_mesusu."' and trim(r54_codrel) = '".trim($r54_codrel)."' and r54_regist = ".$matricula,$r54_anousu,$r54_mesusu,$r55_rubr01));
//          }
          $result_testa_folha = $clmovrel->sql_record($clmovrel->sql_query_gerfsal(null," distinct gerfsal.* ",""," r54_anomes = '".$r54_anousu.$r54_mesusu."' and trim(r54_codrel) = '".trim($r54_codrel)."' and r54_regist = ".$matricula,$r54_anousu,$r54_mesusu,$r55_rubr01));
          for($i=0; $i<$clmovrel->numrows; $i++){
            db_fieldsmemory($result_testa_folha, $i);
            $total += $r14_valor;
          }

          $testavalor  = number_format($valor, 2);
          $testavaltot = number_format($total, 2);

          $somavalmovrel += $total;

          $valorefetivado  = $testavaltot;
          $valorrejeitado  = abs($testavalor - $testavaltot); 
          if($testavalor != $testavaltot){
	          $tot_rejeitados ++;
//            $valorrejeitado  = $valor;
          }else{
//            $valorrefetivado = $valor;
          }

          db_setaPropriedadesLayoutTxt(&$cldb_layouttxt,3);
          $dbcontator ++;
      }
    }else{
      $erro_msg = "Rubrica n�o informada. Verifique o relacionamento.";
    }
  }else{
    $erro_msg = "Conv�nio n�o encontrado ou sem relacionamento.";
    $sqlerro = true;
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
        include("forms/db_frmaleretbanri.php");
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
js_tabulacaoforms("form1","r54_anousu",true,1,"r54_anousu",true);
</script>
</html>
<?
if(isset($gerar) || isset($confirma)){
  if($sqlerro == true){
    db_msgbox($erro_msg);
  }else{
    db_msgbox("Registros  : ".$dbcontator."\\nRejeitados : $tot_rejeitados\\nValor total: ".db_formatar($somavalmovrel,"f"));
    //db_msgbox($dbcontator-$tot_rejeitados." registros.\\nAceitos : $aceitos\\nRejeitados : $tot_rejeitados\\nValor total: ".db_formatar($somavalmovrel,"f"));
    echo "
          <script>
            parent.bstatus.document.getElementById('st').innerHTML = '$texto';
            js_arquivo_abrir('$arquivogeracao');
          </script>
         ";
  };
}
?>