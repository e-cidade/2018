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
//db_postmemory($HTTP_POST_VARS,2);
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
<? 
db_criatermometro('calculo_folha','Concluido...','blue',1,'Efetuando Geracao');
?>

</center>
</body>
<?
$db_erro = false;

$erro_msg = ConverteArquivo($AArquivo);

//exit;

if(empty($erro_msg)){
  echo "
  <script>
    parent.js_detectaarquivo('/tmp/bbconsig.txt');
  </script>
  ";
}else{
  echo "
  <script>
    parent.js_erro('$erro_msg');
  </script>
  ";
}
//echo "<BR> antes do fim db_fim_transacao()";
//flush();
db_fim_transacao();
//flush();
db_redireciona("pes2_bbconverte001.php");

function ConverteArquivo($AArquivo){

  $cArquivoSaida = "/tmp/bbconsig.txt";

  $LinhasArquivo = file($AArquivo);

  if(($handle_saida = fopen($cArquivoSaida,'w')) === FALSE){
		return "Erro na criacao do arquivo ".$cArquivoSaida;
	}

	$nRegistro = 0;

  for($cL=0;$cL<count($LinhasArquivo);$cL++) {
    $cLinha = $LinhasArquivo[$cL];     
//echo "<br> cLinhas --> $cLinha";
    $nRegistro++;

//echo "<BR> if(".substr($cLinha,0,8)." == 00100013){"; 
		if(db_substr($cLinha,1,8) == "00100013"){ 
      // Registro Detalhe
			// leitura da Matricula do Funcionário
			$cCampo = db_substr($cLinha, 63, 12); // Id Mutuario na Empresa
//echo "<BR> cCampo --> $cCampo";
			if(!db_empty($cCampo )){
				$nMatricula = db_val($cCampo);
			}else{
				$cCampo = db_substr($cLinha, 52, 11);

				$cSql="select  z01_cgccpf, 
                        rh02_regist, 
                        rh02_anousu, 
                        rh02_mesusu 
 				         from   rhpessoalmov
                 inner join rhpessoal on rh01_regist = rh02_regist
                 inner join cgm       on z01_numcgm = rh01_numcgm  
 			           where   rh02_anousu = ".db_anofolha()." 
                     and rh02_mesusu = ".db_mesfolha()."
                     and rh02_instit = ".db_getsession("Db_instit")."     
 				             and z01_cgccpf='".$cCampo."' 
 				         order by rh02_anousu desc, r01_mesusu desc limit 1";

				db_selectmax("pessoal", $cSql);

				$nMatricula = $pessoal[0]["r01_regist"];
			}

			
			// leitura do valor a ser lancado na folha
			$nValor    = db_val(db_substr($cLinha, 144, 9));

			$nParcela  = db_val(db_substr($cLinha, 106, 2));
			$nParcelas = db_val(db_substr($cLinha, 108, 2));
			
			$cLinhaGrava  =  db_str($nMatricula,5,0,"0");
      $cLinhaGrava .=  valor_10($nValor).valor_8($nParcela*100).valor_6($nParcelas)."\n";
							
//echo "<br>  cLinhaGrava --> $cLinhaGrava";
			fputs($handle_saida,$cLinhaGrava);

		}

	}
	fclose($handle_saida);

//	return " ";

}



function valor_10($numero){
   return db_formatar(str_replace(',','',str_replace('.','',trim(db_formatar($numero,'f')))),'s','0',10,'e',2);
}
function valor_8($numero){
   return db_formatar(str_replace(',','',str_replace('.','',trim(db_formatar($numero,'f')))),'s','0',8,'e',2);
}
function valor_6($numero){
   return db_formatar(str_replace(',','',str_replace('.','',trim(db_formatar($numero,'f')))),'s','0',6,'e',2);
}

?>