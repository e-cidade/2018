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

  $arquivo = trim(tempnam("/usr/tmp","rp")).".prn";
  $var = db_getsession("DB_nome_modulo")." sistema $DB_DIRPCB ".db_getsession("DB_anousu")." '".db_getsession("DB_datausu")."' '".$HTTP_POST_VARS["progexe"]."' ".db_getsession("DB_orgaounidade")." ".db_sqlformatar(db_getsession("DB_instit"),2,"0")." ".$arquivo;
  $com = "export DIRTMP=/usr/tmp;export DIRPCB=$DB_DIRPCB;$DB_EXEC $var";
  exec($com,$ret);
  if(($fp = fopen($arquivo,"r")) == false) {
    echo "Deu erro abrindo arquivo<br>\n";
    exit;
  }
  $conta = 0;
  $aux = "";
  while(!feof($fp)) {
    $ch = fgetc($fp);
	if($ch == "\n")
	  $conta++;
	else if($conta <= 1)
	  $aux .= $ch;
	if($conta > 1)
	  break;
  }
  fclose($fp);
  if($conta > 1) {
?>
<script>
  window.open('db_mostrarelatorio.php?arquivo=<?=$arquivo?>','_blank','location=0');
//  history.back();
//  location.href = self;
  //location.href='mostrarelatorio.php?arquivo=<?=$arquivo?>';
</script>
<?
  } else {
    echo "<script>alert('$aux')</script>\n";
  }
?>