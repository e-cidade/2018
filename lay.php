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
include("libs/db_libcaixa_ze.php");
$cllayouts_bb = new cl_layouts_bb;
$modelo = 2;
//echo 'instit '.db_getsession("DB_instit");
$sqlinst = "select * from db_config where codigo = ".db_getsession("DB_instit"); 
$resultinst = pg_query($sqlinst);
db_fieldsmemory($resultinst,0);

echo $sql = "select c63_codcon,
               c63_banco,
	       lpad(e83_convenio,6,0) as e83_convenio,
               lpad(translate(c63_agencia,'-',''),5,'0') as c63_agencia,
	       lpad(translate(translate(c63_conta,'.',''),'-',''),10,'0') as c63_conta
        from empagemod 
	     inner join empagetipo      on e84_codmod = e83_codmod
	     inner join conplanoreduz   on c61_reduz  = e83_conta and c61_anousu=".db_getsession("DB_anousu")."
	     inner join conplanoconta   on c61_codcon = c63_codcon and c63_anousu=".db_getsession("DB_anousu")."
	where e84_codmod = $modelo";
$result = pg_query($sql);
db_fieldsmemory($result,0);

$cllayouts_bb->cabec01 = '0' ; 			// fixo
$cllayouts_bb->cabec02 = '1' ; 			// fixo
$cllayouts_bb->cabec03 = '       ' ; 		// branco
$cllayouts_bb->cabec04 = '03' ; 		// fixo
$cllayouts_bb->cabec05 = ' ' ; 			// branco
$cllayouts_bb->cabec06 = '00000' ;  		// fixo
$cllayouts_bb->cabec07 = '         ';   	// brancos
$cllayouts_bb->cabec08 = substr($c63_agencia,0,4); 	// numero da agencia
$cllayouts_bb->cabec09 = substr($c63_agencia,4,1); 	// digito da agencia
$cllayouts_bb->cabec10 = substr($c63_conta,0,9);  	// conta
$cllayouts_bb->cabec11 = substr($c63_conta,9,1);  	// digito da conta
$cllayouts_bb->cabec12 = '     ' ; 		// brancos
$cllayouts_bb->cabec13 = substr(strtoupper($nomeinst),0,30) ; // nome da empresa
$cllayouts_bb->cabec15 = '001'; 		// codigo do banco
$cllayouts_bb->cabec15 = $e83_convenio ; 	// numero do converio
$cllayouts_bb->cabec16 = '   ' ; 		// brancos
$cllayouts_bb->cabec17 = '          '; 		// campo livre
$cllayouts_bb->cabec18 = '00' ;			// tipo de retorno magnetico
$cllayouts_bb->cabec19 = '000' ;		// para uso do banco
$cllayouts_bb->cabec20 = str_repeat('s',46) ;
$cllayouts_bb->cabec21 = 'eu' ;
$cllayouts_bb->cabec22 = 'eu' ;
$cllayouts_bb->cabec23 = 'eu' ;
$cllayouts_bb->cabec24 = 'eu' ;
$cllayouts_bb->cabec25 = 'eu' ;
$cllayouts_bb->gera_cabecalho();



$cllayouts->gera();
?>