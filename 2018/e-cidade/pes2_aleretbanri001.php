<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("classes/db_movrel_classe.php"));
include(modification("classes/db_convenio_classe.php"));
include(modification("dbforms/db_funcoes.php"));
db_postmemory($HTTP_POST_VARS);
$clmovrel = new cl_movrel();
$clconvenio = new cl_convenio();
$db_opcao = 1;
$db_botao = true;
if (isset($gerar)) {
  
  function testavalor($valor = null) {

    if (is_numeric($valor) == true) {
      return $valor;
    } else {
      return 0;
    }
  }
  
  $sqlerro = false;
  $dbcontator = 0;
  $dbrejeitados = 0;
  $result_dados = $clconvenio->sql_record($clconvenio->sql_query_relac($r54_codrel));
  if ($clconvenio->numrows > 0) {
    db_fieldsmemory($result_dados, 0);
    
    if (trim($r55_rubr01) != "") {
      
      include(modification("dbforms/db_layouttxt.php"));
      
      // Nome do novo arquivo
      $nomearq = $_FILES ["r56_dirarq"] ["name"];
      
      // Nome do arquivo temporário gerado no /tmp
      $nometmp = $_FILES ["r56_dirarq"] ["tmp_name"];
      
      // Seta o nome do arquivo destino do upload
      $arquivoprocessa = "tmp/retbanriname.txt";
      $arquivogeracao = "tmp/retbanri.txt";
   
      // Faz um upload do arquivo para o local especificado
      move_uploaded_file($nometmp, $arquivoprocessa) or $erro_msg = "ERRO: Problemas com upload, contate o suporte.";
      
      // Abre o arquivo
      $ponteiro = fopen("$arquivoprocessa", "r") or $erro_msg = "ERRO: Arquivo não abre.";
      
      $cldb_layouttxt = new db_layouttxt(5, $arquivogeracao);
      $linhacorrente = 0;
      $somavalmovrel = 0;
      while ( ! feof($ponteiro) ) {
        $poslinha = fgets($ponteiro, 4096);
        if ($poslinha == "" || substr($poslinha, 0, 6) == "999999") {
          continue;
        }
        $linhacorrente ++;
        
        if ($linhacorrente == 1) {
          $anoarquivo = db_formatar(trim(substr($poslinha, 22, 4)), "s", "0", 4, "e");
          $mesarquivo = db_formatar(trim(substr($poslinha, 20, 2)), "s", "0", 2, "e");
          
          $r54_anousu = db_formatar($r54_anousu, "s", "0", 4, "e");
          $r54_mesusu = db_formatar($r54_mesusu, "s", "0", 2, "e");
          
          if (($anoarquivo . $mesarquivo) != ($r54_anousu . $r54_mesusu)) {
            $erro_msg = "Período não confere com arquivo de envio.";
            $sqlerro = true;
            break;
          }
          
          $result_testa_folha = $clmovrel->sql_record($clmovrel->sql_query_gerfsal(null, " * ", "", " r54_instit = " . db_getsession('DB_instit') . " and r54_anomes = '" . $r54_anousu . $r54_mesusu . "' and trim(r54_codrel) = '" . $r54_codrel . "' and r54_regist <> 0 limit 1 ", $r54_anousu, $r54_mesusu, $r55_rubr01));
          if ($clmovrel->numrows == 0) {
            $erro_msg = "Sem registro lançado na folha.";
            $sqlerro = true;
            break;
          }
          
          $strCanal = substr($poslinha, 17, 3);
          $strPeriodo = $anoarquivo . "-" . $mesarquivo . "-01";
          db_setaPropriedadesLayoutTxt($cldb_layouttxt, 1);
        
        } else {
          $AO = trim(substr($poslinha, 0, 6)) + 0;
          $cpffuncionario = trim(substr($poslinha, 6, 11)) + 0;
          $z01_nome = trim(substr($poslinha, 17, 35));
          $matricula = trim(substr($poslinha, 52, 14)) + 0; // O 15° CARACTER É O DIGITO DA MATRICULA
          $codigo = trim(substr($poslinha, 67, 5)) + 0;
          $datatermi = trim(substr($poslinha, 72, 6));
          $valorinc = trim(substr($poslinha, 78, 11)) + 0;
          $valorexc = trim(substr($poslinha, 89, 11)) + 0;
          $filler = trim(substr($poslinha, 100, 16));
          $matriculaselect = $matricula;
          $matricula .= db_CalculaDV("$matricula");
          
          $valorinc /= 100;
          $valorexc /= 100;
          
          $anomesterm = substr($datatermi, 2, 4) . "-" . substr($datatermi, 0, 2) . "-01";
          
          $total = 0;
          $result_testa_folha = $clmovrel->sql_record($clmovrel->sql_query_gerfsal(null, " * ", "", " r54_instit = " . DB_getsession('DB_instit') . " and  r54_anomes = '" . $r54_anousu . $r54_mesusu . "' and trim(r54_codrel) = '" . $r54_codrel . "' and r54_regist = " . $matriculaselect, $r54_anousu, $r54_mesusu, $r55_rubr01));
          //echo "<BR> ".$clmovrel->sql_query_gerfsal(null," * ",""," r54_anomes = '".$r54_anousu.$r54_mesusu."' and trim(r54_codrel) = '".$r54_codrel."' and r54_regist = ".$matriculaselect,$r54_anousu,$r54_mesusu,$r55_rubr01);
          for($i = 0; $i < $clmovrel->numrows; $i ++) {
            db_fieldsmemory($result_testa_folha, $i);
            $total += $r54_quant1;
          }
          
          $testavalinc = number_format($valorinc, 2);
          $testavaltot = number_format($total, 2);
          
          $somavalmovrel += $total;
          echo " <br><br> testavalinc --> $testavalinc    testavaltot --> $testavaltot"; 
          $valorinc2 = 0;
          if ($testavalinc != $testavaltot) {
            $total = 0;
            $valorinc2 = $valorinc;
            $dbrejeitados ++;
          } else {
            $dbcontator ++;
          }
          
          db_setaPropriedadesLayoutTxt($cldb_layouttxt, 3);
       
        }
      }
      
      $sNomeArquivo       = "/tmp/teste_sandro";      
      $aArquivosGerados[] = "tmp/retbanri.txt";      
      compactaArquivos($aArquivosGerados, $sNomeArquivo);
      
    } else {
      $erro_msg = "Rubrica não informada. Verifique o relacionamento.";
    }
  } else {
    $erro_msg = "Convênio não encontrado ou sem relacionamento.";
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
<body class="body-default" onLoad="a=1" >
<div class="container">
  <?php
  include(modification("forms/db_frmaleretbanri.php"));
  db_menu();
  ?>
</div>
</body>
<script>
js_tabulacaoforms("form1","r54_anousu",true,1,"r54_anousu",true);
</script>
</html>
<?
if (isset($gerar) || isset($confirma)) {
  if ($sqlerro == true) {
    db_msgbox($erro_msg);
  } else {
    db_msgbox($dbcontator . " registros incluídos.\\nValor total: " . db_formatar($somavalmovrel, "f") . "\\n $dbrejeitados registros Rejeitados.");
    echo "
          <script>
            (window.CurrentWindow || parent.CurrentWindow).bstatus.document.getElementById('st').innerHTML = '$texto';
            js_arquivo_abrir('$arquivogeracao');
          </script>
         ";
  }
  ;
}
?>
