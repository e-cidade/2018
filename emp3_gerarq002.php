<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
include("classes/db_empagegera_classe.php");
$clempagegera = new cl_empagegera;
$clrotulo = new rotulocampo;
$clempagegera->rotulo->label();

db_postmemory($HTTP_POST_VARS);
if(isset($e83_codtipo) && $e83_codtipo!="0"){
  $e87_codgera = "select distinct e90_codgera 
                  from empageconfgera 
	               inner join empagepag 
	                 on e85_codmov=e90_codmov 
	          where e85_codtipo = $e83_codtipo";
}

if(isset($e87_codgera) && trim($e87_codgera)!=""){
  
  
  $iInstit = db_getsession("DB_instit") ;
  
  $sWhere  = " where e80_instit = {$iInstit} and empagegera.e87_codgera in (".$e87_codgera.")";
  
  if (isset($lCancelado) && $lCancelado == "0") {
  
  
    $sWhere .= " and empageconfgera.e90_cancelado is false ";
  }
  
  $sql  = " select empagegera.e87_codgera,
		   empagegera.e87_descgera,
                   empagemov.e81_codmov,
                   empagemov.e81_numemp,
                   empagemov.e81_valor,
                   e60_codemp,
                   cgm.z01_nome,
                   pc63_banco,
                   pc63_agencia,
                   pc63_conta
            from empagegera
                 inner join empageconfgera
                       on empageconfgera.e90_codgera = empagegera.e87_codgera
                 inner join empageconf
                       on empageconf.e86_codmov = empageconfgera.e90_codmov
                 inner join empagemov
                       on empagemov.e81_codmov = empageconfgera.e90_codmov
								 inner join empage  on  empage.e80_codage = empagemov.e81_codage
                 inner join empagepag
                       on empagepag.e85_codmov = empagemov.e81_codmov
                 inner join empempenho
                       on empempenho.e60_numemp = empagemov.e81_numemp
                 inner join cgm
                       on cgm.z01_numcgm = empempenho.e60_numcgm
                 left join pcfornecon
                       on cgm.z01_numcgm = pcfornecon.pc63_numcgm";
  $sql .= $sWhere;
//  die($sql);
  $result_empagegera = $clempagegera->sql_record($sql);
  $numrows = $clempagegera->numrows;
  if($numrows == 0){
    echo "Nenhum registro encontrado."; 
    return false;
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
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc">
<center><br><br>
<table border='0'>
  <tr height="20px">
    <td align="center"><strong>DADOS DOS ARQUIVOS</strong></td>
  </tr>
  <tr height="20px">
    <td>
    <center>
      <table bordercolor=\"#000000\" style=\"font-size:12px\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\">
<?

        db_lovrot($sql,15,"","");

/*
  $cor = "";
  for($i=0;$i<$numrows;$i++){
    db_fieldsmemory($result_empagegera,$i,true);
    if($i == 0){
      echo "  <tr bgcolor=\"\">\n";
      echo "    <th>CódMov</th>\n";
      echo "    <th>NumEmp</th>\n";
      echo "    <th>NOME</th>\n";
      echo "    <th>Valor</th>\n";
      echo "  </tr>\n";
     }
      echo "  <tr bgcolor=\"".($cor = ($cor=="#E4F471"?"#EFE029":"#E4F471"))."\">\n";
      echo "    <td align='center'>$e81_codmov</td>\n";
      echo "    <td align='center'>$e81_numemp</td>\n";
      echo "    <td align='left'>$z01_nome</td>\n";
      echo "    <td align='right'>$e81_valor</td>\n";
      echo "  </tr>\n";
  }

  */
?>    
      </table>
      </center>
    </td>    
  </tr>
</table>
</body>
</html>