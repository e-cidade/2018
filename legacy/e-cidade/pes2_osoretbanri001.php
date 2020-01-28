<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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
  $dbrejeitados = 0; 
  $result_dados = $clconvenio->sql_record($clconvenio->sql_query_relac($r54_codrel));
  if($clconvenio->numrows > 0){
    db_fieldsmemory($result_dados, 0);

    if(trim($r55_rubr01) != ""){

      include(modification("dbforms/db_layouttxt.php"));

      // Nome do novo arquivo
      $nomearq = $_FILES["r56_dirarq"]["name"];
         
      // Nome do arquivo temporário gerado no /tmp
      $nometmp = $_FILES["r56_dirarq"]["tmp_name"];
         
      // Seta o nome do arquivo destino do upload
      $arquivoprocessa = "/tmp/retbanriname.txt";
      $arquivogeracao = "/tmp/retbanri.txt";
         
      // Faz um upload do arquivo para o local especificado
      move_uploaded_file($nometmp,$arquivoprocessa) or $erro_msg = "ERRO: Problemas com upload, contate o suporte.";

      // Abre o arquivo
      $ponteiro = fopen("$arquivoprocessa","r") or $erro_msg = "ERRO: Arquivo não abre.";

      $cldb_layouttxt = new db_layouttxt(27, $arquivogeracao);
      $linhacorrente = 0;
      $somavalmovrel = 0;
      while(!feof($ponteiro)){
        $poslinha = fgets($ponteiro,4096);
				if($poslinha=="" || substr($poslinha, 0, 6) == "999999"){
				  continue;
				}
				$linhacorrente ++;

        if($linhacorrente == 1){
          $anoarquivo = db_formatar(trim(substr($poslinha, 67, 4)),"s","0",4,"e");
          $mesarquivo = db_formatar(trim(substr($poslinha, 65, 2)),"s","0",2,"e");

          $r54_anousu = db_formatar($r54_anousu,"s","0",4,"e");
          $r54_mesusu = db_formatar($r54_mesusu,"s","0",2,"e");

          if(($anoarquivo.$mesarquivo) != ($r54_anousu.$r54_mesusu)){
            $erro_msg = "Período não confere com arquivo de envio.";
            $sqlerro = true;
            break;
          }

          $result_testa_folha = $clmovrel->sql_record($clmovrel->sql_query_gerfsal(null," * ",""," r54_instit = ".db_getsession('DB_instit')." and r54_anomes = '".$r54_anousu.$r54_mesusu."' and trim(r54_codrel) = '".$r54_codrel."' and r54_regist <> 0 limit 1 ",$r54_anousu,$r54_mesusu,$r55_rubr01));
	        if($clmovrel->numrows == 0){
	          $erro_msg = "Sem registro lançado na folha.";
	          $sqlerro = true;
	          break;
	        }

          $anomes = $anoarquivo."-".$mesarquivo."-01";
          $nomeconvenio   = trim(substr($poslinha,  14, 50)); 
          $codigoconvenio = trim(substr($poslinha,   6,  8));  
          db_setaPropriedadesLayoutTxt($cldb_layouttxt,1);

	      }else{
          $oa                   = trim(substr($poslinha,  6, 11)) + 0;
          $matriculafuncionario = trim(substr($poslinha, 11, 15)) + 0; // O 15° CARACTER É O DIGITO DA MATRICULA
          $cpffuncionario       = trim(substr($poslinha, 26, 11)) + 0;
          $nomefuncionario      = trim(substr($poslinha, 37, 35));
          $codigocanal          = trim(substr($poslinha, 71,  5)) + 0;
          $nrocontrato          = trim(substr($poslinha, 77, 20));
          $valorconsignar       = trim(substr($poslinha,104, 11)) + 0;
          $valorconsignado      = 0;
          $motivorejeicao       = trim(substr($poslinha,134,  2));
          $filler               = trim(substr($poslinha,136, 64));

          $valorconsignar  /= 100;
          $valorconsignado /= 100;

          $anomesterm     = substr($datatermi,2,4)."-".substr($datatermi,0,2)."-01";

          $total = 0;
          $result_testa_folha = $clmovrel->sql_record($clmovrel->sql_query_gerfsal(null," * ",""," r54_instit = ".DB_getsession('DB_instit')." and  r54_anomes = '".$r54_anousu.$r54_mesusu."' and trim(r54_codrel) = '".$r54_codrel."' and r54_regist = ".$matricula,$r54_anousu,$r54_mesusu,$r55_rubr01));
          //echo "<BR> ".$clmovrel->sql_query_gerfsal(null," * ",""," r54_anomes = '".$r54_anousu.$r54_mesusu."' and trim(r54_codrel) = '".$r54_codrel."' and r54_regist = ".$matriculaselect,$r54_anousu,$r54_mesusu,$r55_rubr01);
          for($i=0; $i<$clmovrel->numrows; $i++){
            db_fieldsmemory($result_testa_folha, $i);
            $total += $r54_quant1;
          }

          $testavalinc = number_format($valorconsignar, 2);
          $testavaltot = number_format($total, 2);

          $somavalmovrel += $total;

          $valorconsignado = 0; 
          if($testavalinc != $testavaltot){
            $total = 0;
            $dbrejeitados++;
          }else{
             $dbcontator++;
             $valorconsignado = $testavaltot;
	        }

          db_setaPropriedadesLayoutTxt($cldb_layouttxt,3);
        }
      }
      $valortotal   = $somavalmovrel;
      $qtderegistro = $linhacorrente++;
      db_setaPropriedadesLayoutTxt($cldb_layouttxt,5);

    }else{
      $erro_msg = "Rubrica não informada. Verifique o relacionamento.";
    }
  }else{
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
if(isset($gerar) || isset($confirma)){
  if($sqlerro == true){
    db_msgbox($erro_msg);
  }else{
    db_msgbox($dbcontator." registros incluídos.\\nValor total: ".db_formatar($somavalmovrel,"f")."\\n $dbrejeitados registros Rejeitados.");
    echo "
          <script>
            (window.CurrentWindow || parent.CurrentWindow).bstatus.document.getElementById('st').innerHTML = '$texto';
            js_arquivo_abrir('$arquivogeracao');
          </script>
         ";
  };
}
?>
