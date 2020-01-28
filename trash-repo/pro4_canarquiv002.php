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
include("classes/db_procandam_classe.php");
include("classes/db_proctransfer_classe.php");
include("classes/db_proctransferproc_classe.php");
include("classes/db_protprocesso_classe.php");
include("classes/db_proctransand_classe.php");
include("classes/db_arqandam_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);
$clprocandam = new cl_procandam;
$clproctransfer = new cl_proctransfer;
$clproctransand = new cl_proctransand;
$clprotprocesso = new cl_protprocesso;
$clarqandam = new cl_arqandam; 

$db_opcao = 1;
$db_botao = true;
if((isset($QUERY_STRING["p58_codproc"]))){
  db_inicio_transacao();
  $sqlerro = false;
  $sql = "select distinct p67_codarquiv,p68_codproc,p67_coddepto 
          from   arqproc inner join procarquiv
                 on p68_codarquiv = p67_codarquiv
          where  p68_codproc = $p58_codproc
          order by p68_codproc";
  $rs = pg_exec($sql);
  $hoje = date('Y-m-d');
  $erro = 0;
  if (pg_num_rows($rs) > 0){
        db_fieldsmemory($rs,0);
        //inclui a transferencia
        $clproctransfer->p62_coddepto    = $p67_coddepto;
        $clproctransfer->p62_dttran      = $hoje;
        $clproctransfer->p62_coddeptorec = $p67_coddepto;
        $clproctransfer->p62_id_usorec   = db_getsession("DB_id_usuario");
        $clproctransfer->p62_id_usuario   = db_getsession("DB_id_usuario");
        $clproctransfer->p62_hora        = db_hora();
        $clproctransfer->incluir(null);
        //$clproctransfer->erro(true,false);
 	    $cod = $clproctransfer->p62_codtran;
        $rsi =  pg_exec("insert into proctransferproc values($cod,$p58_codproc)");
        if ($clproctransfer->erro_status == "1" or !$rsi ){
           $erro += 0;
        }else{
           $clproctransfer->erro(true,false);
	       $sqlerro = true;
        }
        //inclui o andamento
        $clprocandam->p61_despacho       =  "Processo Desarquivado";    
        $clprocandam->p61_codproc        = $p68_codproc;    
        $clprocandam->p61_dtandam        = $hoje;    
        $clprocandam->p61_hora           = db_hora();    
        $clprocandam->p61_id_usuario     = db_getsession("DB_id_usuario");
        $clprocandam->p61_coddepto       = $p67_coddepto;
        $clprocandam->p61_publico        =  "t";    
        $clprocandam->incluir("");
 
        if ($clprocandam->erro_status == "1"){
           $erro += 0;
        }else{
           $clprocandam->erro(true,false);
     	   $sqlerro = true;
           break;
        }
	
    	//inclui  o andamento e o cod. do arquivamento e diz se � arquivamento ou desarquivamento na tabela arqandam
		$clarqandam->p69_codarquiv = $p67_codarquiv;
		$clarqandam->p69_codandam  = $clprocandam->p61_codandam;
		$clarqandam->p69_arquivado = 'false';
		$clarqandam->incluir();
		if ($clarqandam->erro_status == "1"){
           $erro += 0;
        }else{
           $clarqandam->erro(true,false);
     	   $sqlerro = true;
           break;
        }	
    
        //inclui  a transferencia. e o andamento do processo na tabela proctransand;
        $clproctransand->p64_codtran  = $clproctransfer->p62_codtran;
        $clproctransand->p64_codandam = $clprocandam->p61_codandam;
        $clproctransand->incluir();
         if ($clproctransand->erro_status == "1"){
           $erro += 0;
        }else{
           $clproctransand->erro(true,false);
   	       $sqlerro = true;
           break;
        }
 
      //atualiza codandam da tabela protprocesso; 
        $clprotprocesso->p58_codproc = $p58_codproc;
        $clprotprocesso->p58_codandam = $clprocandam->p61_codandam;
        $clprotprocesso->p58_despacho = " ";  
        $clprotprocesso->alterar($p58_codproc);
        if ($clprotprocesso->erro_status == "1"){
           $erro += 0;
        }else{
           $clprotprocesso->erro(true,false);
           $sqlerro=true;	   
           break;
        } 
        if ($sqlerro==false){
          pg_exec("delete from arqproc where p68_codproc = $p58_codproc");
        }
     if ($erro == 0 && $sqlerro==false){
        echo "<script>alert('Processo Desarquivado com Sucesso!! ')
              </script>";
     }else{
       echo "<script>alert('Desarquivametno n�o Efetuado');</script>";
     }
  db_fim_transacao($sqlerro);
  }
}
echo "<script>location.href='pro4_canarquiv001.php'</script>";
?>