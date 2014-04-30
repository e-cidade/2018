<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
include("classes/db_veiculos_classe.php");
require("libs/db_utils.php");
require("libs/db_app.utils.php");
db_app::import("veiculos.*");
db_postmemory($HTTP_POST_VARS);

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clveiculos      = new cl_veiculos;

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<?
  $metodo = strtolower($metodo);
  
  $sWhereA=null; 
  $sWhereM=null;
  $sWhereD=null;
  $sWhereR=null;
  
  /*
   * caso nao seja setado a data e hora pelo usuario, o sistema buscava a data e hora da seção,
  * o que ocorria problema, pois as vezes o veiculo estava devolvido ex:
  * 18/04/2013 10:00 , e o usuario entraria para proxima devolução
  * 18/04/2013 9:45  o sistema buscava a medida errada.
  * @todo validar possivel refatoramento no sql para desconsiderar data e hora e buscar diferente a ultima medida.
  */
  if(!isset($hora)) {
    //$hora = db_hora();
  	$hora = "24:59";
  }

  if(!isset($veiculo)) {
    $veiculo = null;
  }

  if(!isset($data)) {
   // $data = date("Y-m-d", db_getsession("DB_session"));
  	$data = "3000-12-31";
  }

  $ultimamedida  = 0;
  $proximamedida = 0;
  
  /*
   * Criado abaixo as condições de filtros para retornar os registros no caso das alterações em específico foi criada estas condições
   * para o caso da manutenção que não possui cadastro de hora informado pelo usuário, somente a hora de cadastro da manutenção
   * 
   * Os registros das medidas no caso das alterações não podem considerar como ultima medida ou proxima medida a própria medida da data, o mesmo código de registro
   * Nas alterações é passado o código do registro de manutenção,abastecimento,retirada ou devolução.
   */
  if ($metodo == "ultimamedida") {
  	
    if (isset($abastecimento) && $abastecimento <> "") {
  			$sWhereA = " and ve62_codigo not in ( select ve70_codigo from veicabast where ve70_veiculos = $veiculo and ve70_codigo = $abastecimento)";
 	  }
 	
 	  if (isset($manutencao) && $manutencao <> "") {
        $sWhereM = " and ve62_codigo not in ( select ve62_codigo from veicmanut where ve62_veiculos = $veiculo and ve62_codigo >= $manutencao)";
    }
  
    if (isset($devolucao) && $devolucao <> "") {
        $sWhereD = " and ve62_codigo not in ( select ve61_codigo from veicdevolucao where ve61_veicretirada = $veiculo and ve61_codigo = $devolucao)";
    }
  
    if (isset($retirada) && $retirada <> "") {
        $sWhereR = " and ve62_codigo not in ( select ve60_codigo from veicretirada where ve62_veiculo = $veiculo and ve60_codigo = $retirada)";
    }
    
  } else {
  	
    if (isset($abastecimento) && $abastecimento <> "") {
        $sWhereA = " and ve62_codigo not in ( select ve70_codigo from veicabast where ve70_veiculos = $veiculo and ve70_codigo = $abastecimento)";
    }
  
    if (isset($manutencao) && $manutencao <> "") {
        $sWhereM = " and ve62_codigo not in ( select ve62_codigo from veicmanut where ve62_veiculos = $veiculo and ve62_codigo = $manutencao)";
    }
  
    if (isset($devolucao) && $devolucao <> "") {
        $sWhereD = " and ve62_codigo not in ( select ve61_codigo from veicdevolucao where ve61_veicretirada = $veiculo and ve61_codigo = $devolucao)";
    }
  
    if (isset($retirada) && $retirada <> "") {
        $sWhereR = " and ve62_codigo not in ( select ve60_codigo from veicretirada where ve62_veiculo = $veiculo and ve60_codigo = $retirada)";
    }
    
  }
  
  if($metodo=="ultimamedida" or $metodo=="proximamedida") {
    
    if ($metodo == 'ultimamedida') {
      
      $oVeiculo            = new Veiculo($veiculo);
      $ultimamedida        = $oVeiculo->getUltimaMedidaUso($data, $hora);
      $clveiculos->numrows = 1;
    } else {
      
      $sql_metodo = "sql_query_{$metodo}";
      $sql    = $clveiculos->$sql_metodo($veiculo, $data, $hora, $sWhereA, $sWhereM, $sWhereD, $sWhereR);
      $result = $clveiculos->sql_record($sql);
    }
    

    if($clveiculos->numrows > 0) {
      db_fieldsmemory($result, 0);
      if($metodo=="ultimamedida") {
        echo "<script>{$funcao_js}('{$ultimamedida}',true);</script>";
      } else {
        echo "<script>{$funcao_js}('{$proximamedida}',true);</script>";
      }
    } else {
     echo "<script>".$funcao_js."('0',false);</script>";
    }
  }
?>

</body>
</html>