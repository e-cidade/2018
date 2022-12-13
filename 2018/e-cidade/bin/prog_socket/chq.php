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


include("/var/www/dbportal2/libs/db_stdlib.php");

db_imprimecheque("JOAO DA SILVA", "001", 100, "01/01/2008", 2, "192.168.0.72", "4444", "PORTO ALEGRE");

function db_imprimecheque ($nome, $codbco, $valor, $data, $modelo = 1, $ip_imprime, $porta, $municipio ){
    
  global $prefeito, $tesoureiro, $municipio, $ip_imprime;
  if($municipio == ''){
    $municipio = '............';
  }

  $ip_imprime = "192.168.0.72";

  echo "ip: $ip_imprime\n";
  echo "porta: $porta\n";
  echo "modelo: $modelo\n";

  $valor = trim(db_formatar($valor, 'p', '', 2));
  $nome = str_pad($nome,40," ", STR_PAD_RIGHT);
  $fd = fsockopen($ip_imprime, $porta);
  if(!$fd) {
		//
		echo "Impossivel conectar com impressora em $ip_imprime:$porta!";
		return;
	}
	
  // modelo 1 - sapiranga CHRONOS
  // modelo 2 - guaiba / alegrete BEMATECH (DP 20)
  if($modelo == 2){
    $data = str_replace("-", "/", $data);
    $imprimir  = chr(27).chr(177);
    $imprimir .= chr(27).chr(162).$codbco.chr(13);
    $imprimir .= chr(27).chr(163).$valor.chr(13);
    $imprimir .= chr(27).chr(160).$nome.chr(13);
    $imprimir .= chr(27).chr(161).$municipio.chr(13);
    $imprimir .= chr(27).chr(164).$data.chr(13);
    $imprimir .= chr(27).chr(176);
    
   
    /*
    if(strtoupper($municipio) == "SAPIRANGA"){ 
      $imprimir .= chr(13).chr(10);
      $imprimir .= chr(13).chr(10);
      $imprimir .= chr(13).chr(10);
      $imprimir .= chr(13).chr(10);
      $imprimir .= chr(13).chr(10);
      $imprimir .= chr(13).chr(10);
      $imprimir .= chr(13).chr(10);
      $imprimir .= chr(13).chr(10);
      $imprimir .= "          Prefeito: $prefeito $tesoureiro".chr(10).chr(13);
    }*/
    fputs($fd, $imprimir);
    
  }elseif($modelo == 1){

    fputs($fd, chr(27).chr(160)." $nome\n");
    fputs($fd, chr(27).chr(161)." $municipio\n");
    fputs($fd, chr(27).chr(162)." $codbco\n");
    fputs($fd, chr(27).chr(163)." $valor\n");
    fputs($fd, chr(27).chr(164)." $data\n");
    fputs($fd, chr(27).chr(176));
/*
    fputs($fd, " \n");
    fputs($fd, " \n");
    fputs($fd, " \n");
    fputs($fd, " \n");
    fputs($fd, " \n");
    fputs($fd, " \n");
    fputs($fd, " \n");
    fputs($fd, "          Prefeito: $prefeito $tesoureiro"."\n");
*/
  }elseif($modelo == 3){

    $data=str_replace("-","",$data);
    $valor=db_formatar($valor, 'p', '0', 15);
    $valor=str_replace(".","",$valor);

    fputs($fd, chr(27).chr(66)." $codbco\n");
    fputs($fd, chr(27).chr(70)." $nome\n");
    fputs($fd, chr(27).chr(67)." $municipio\n");
    fputs($fd, chr(27).chr(68)." $data\n");
    fputs($fd, chr(27).chr(86)." $valor\n");

  }elseif($modelo == 4){

    $valor=db_formatar($valor, 'p', '0', 15);
    $valor=str_replace(".","",$valor);

    $fim="0DH";

    fputs($fd, chr(27) . "119" . "0");

    fputs($fd, chr(27) . "A0H" . $nome       . $fim);
    fputs($fd, chr(27) . "A1H" . $municipio  . $fim);
    fputs($fd, chr(27) . "A2H" . $codbco     . $fim);
    fputs($fd, chr(27) . "A3H" . $valor      . $fim);
    fputs($fd, chr(27) . "A4H" . $data       . $fim);

  }
    
  fclose($fd);
      
}

?>