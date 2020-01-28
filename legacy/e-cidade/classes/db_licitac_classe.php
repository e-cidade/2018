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

//MODULO: licitação
//CLASSE DA ENTIDADE licitac
class cl_licitac { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $numrows_incluir = 0; 
   var $numrows_alterar = 0; 
   var $numrows_excluir = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $l01_tipo = null; 
   var $l01_dtabre_dia = null; 
   var $l01_dtabre_mes = null; 
   var $l01_dtabre_ano = null; 
   var $l01_dtabre = null; 
   var $l01_numero = null; 
   var $l01_hhabre = null; 
   var $l01_local = null; 
   var $l01_dtpubl_dia = null; 
   var $l01_dtpubl_mes = null; 
   var $l01_dtpubl_ano = null; 
   var $l01_dtpubl = null; 
   var $l01_obs = null; 
   var $l01_dtadju_dia = null; 
   var $l01_dtadju_mes = null; 
   var $l01_dtadju_ano = null; 
   var $l01_dtadju = null; 
   var $l01_dotac = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 l01_tipo = varchar(1) = Tipo 
                 l01_dtabre = date = Data 
                 l01_numero = varchar(8) = Número 
                 l01_hhabre = char(     4) = Hora de Abertura 
                 l01_local = char(    40) = Local de Abertura 
                 l01_dtpubl = date = Data de Publicacao 
                 l01_obs = char(   200) = Observacoes 
                 l01_dtadju = date = Data da Adjudicacao ou Encerra 
                 l01_dotac = int4 = Codigo Reduzido da Dotacao 
                 ";
   //funcao construtor da classe 
   function cl_licitac() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("licitac"); 
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro 
   function erro($mostra,$retorna) { 
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->l01_tipo = ($this->l01_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["l01_tipo"]:$this->l01_tipo);
       if($this->l01_dtabre == ""){
         $this->l01_dtabre_dia = ($this->l01_dtabre_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["l01_dtabre_dia"]:$this->l01_dtabre_dia);
         $this->l01_dtabre_mes = ($this->l01_dtabre_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["l01_dtabre_mes"]:$this->l01_dtabre_mes);
         $this->l01_dtabre_ano = ($this->l01_dtabre_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["l01_dtabre_ano"]:$this->l01_dtabre_ano);
         if($this->l01_dtabre_dia != ""){
            $this->l01_dtabre = $this->l01_dtabre_ano."-".$this->l01_dtabre_mes."-".$this->l01_dtabre_dia;
         }
       }
       $this->l01_numero = ($this->l01_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["l01_numero"]:$this->l01_numero);
       $this->l01_hhabre = ($this->l01_hhabre == ""?@$GLOBALS["HTTP_POST_VARS"]["l01_hhabre"]:$this->l01_hhabre);
       $this->l01_local = ($this->l01_local == ""?@$GLOBALS["HTTP_POST_VARS"]["l01_local"]:$this->l01_local);
       if($this->l01_dtpubl == ""){
         $this->l01_dtpubl_dia = ($this->l01_dtpubl_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["l01_dtpubl_dia"]:$this->l01_dtpubl_dia);
         $this->l01_dtpubl_mes = ($this->l01_dtpubl_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["l01_dtpubl_mes"]:$this->l01_dtpubl_mes);
         $this->l01_dtpubl_ano = ($this->l01_dtpubl_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["l01_dtpubl_ano"]:$this->l01_dtpubl_ano);
         if($this->l01_dtpubl_dia != ""){
            $this->l01_dtpubl = $this->l01_dtpubl_ano."-".$this->l01_dtpubl_mes."-".$this->l01_dtpubl_dia;
         }
       }
       $this->l01_obs = ($this->l01_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["l01_obs"]:$this->l01_obs);
       if($this->l01_dtadju == ""){
         $this->l01_dtadju_dia = ($this->l01_dtadju_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["l01_dtadju_dia"]:$this->l01_dtadju_dia);
         $this->l01_dtadju_mes = ($this->l01_dtadju_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["l01_dtadju_mes"]:$this->l01_dtadju_mes);
         $this->l01_dtadju_ano = ($this->l01_dtadju_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["l01_dtadju_ano"]:$this->l01_dtadju_ano);
         if($this->l01_dtadju_dia != ""){
            $this->l01_dtadju = $this->l01_dtadju_ano."-".$this->l01_dtadju_mes."-".$this->l01_dtadju_dia;
         }
       }
       $this->l01_dotac = ($this->l01_dotac == ""?@$GLOBALS["HTTP_POST_VARS"]["l01_dotac"]:$this->l01_dotac);
     }else{
     }
   }
   // funcao para inclusao
   function incluir (){ 
      $this->atualizacampos();
     if($this->l01_tipo == null ){ 
       $this->erro_sql = " Campo Tipo nao Informado.";
       $this->erro_campo = "l01_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l01_dtabre == null ){ 
       $this->l01_dtabre = "null";
     }
     if($this->l01_numero == null ){ 
       $this->erro_sql = " Campo Número nao Informado.";
       $this->erro_campo = "l01_numero";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l01_hhabre == null ){ 
       $this->erro_sql = " Campo Hora de Abertura nao Informado.";
       $this->erro_campo = "l01_hhabre";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l01_local == null ){ 
       $this->erro_sql = " Campo Local de Abertura nao Informado.";
       $this->erro_campo = "l01_local";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l01_dtpubl == null ){ 
       $this->erro_sql = " Campo Data de Publicacao nao Informado.";
       $this->erro_campo = "l01_dtpubl_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l01_obs == null ){ 
       $this->erro_sql = " Campo Observacoes nao Informado.";
       $this->erro_campo = "l01_obs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l01_dtadju == null ){ 
       $this->erro_sql = " Campo Data da Adjudicacao ou Encerra nao Informado.";
       $this->erro_campo = "l01_dtadju_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l01_dotac == null ){ 
       $this->erro_sql = " Campo Codigo Reduzido da Dotacao nao Informado.";
       $this->erro_campo = "l01_dotac";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into licitac(
                                       l01_tipo 
                                      ,l01_dtabre 
                                      ,l01_numero 
                                      ,l01_hhabre 
                                      ,l01_local 
                                      ,l01_dtpubl 
                                      ,l01_obs 
                                      ,l01_dtadju 
                                      ,l01_dotac 
                       )
                values (
                                '$this->l01_tipo' 
                               ,".($this->l01_dtabre == "null" || $this->l01_dtabre == ""?"null":"'".$this->l01_dtabre."'")." 
                               ,'$this->l01_numero' 
                               ,'$this->l01_hhabre' 
                               ,'$this->l01_local' 
                               ,".($this->l01_dtpubl == "null" || $this->l01_dtpubl == ""?"null":"'".$this->l01_dtpubl."'")." 
                               ,'$this->l01_obs' 
                               ,".($this->l01_dtadju == "null" || $this->l01_dtadju == ""?"null":"'".$this->l01_dtadju."'")." 
                               ,$this->l01_dotac 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Dados Cadastrais da Licitacao                      () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Dados Cadastrais da Licitacao                      já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Dados Cadastrais da Licitacao                      () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     return true;
   } 
   // funcao para alteracao
   function alterar ( $oid=null ) { 
      $this->atualizacampos();
     $sql = " update licitac set ";
     $virgula = "";
     if(trim($this->l01_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l01_tipo"])){ 
       $sql  .= $virgula." l01_tipo = '$this->l01_tipo' ";
       $virgula = ",";
       if(trim($this->l01_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo nao Informado.";
         $this->erro_campo = "l01_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l01_dtabre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l01_dtabre_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["l01_dtabre_dia"] !="") ){ 
       $sql  .= $virgula." l01_dtabre = '$this->l01_dtabre' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["l01_dtabre_dia"])){ 
         $sql  .= $virgula." l01_dtabre = null ";
         $virgula = ",";
       }
     }
     if(trim($this->l01_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l01_numero"])){ 
       $sql  .= $virgula." l01_numero = '$this->l01_numero' ";
       $virgula = ",";
       if(trim($this->l01_numero) == null ){ 
         $this->erro_sql = " Campo Número nao Informado.";
         $this->erro_campo = "l01_numero";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l01_hhabre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l01_hhabre"])){ 
       $sql  .= $virgula." l01_hhabre = '$this->l01_hhabre' ";
       $virgula = ",";
       if(trim($this->l01_hhabre) == null ){ 
         $this->erro_sql = " Campo Hora de Abertura nao Informado.";
         $this->erro_campo = "l01_hhabre";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l01_local)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l01_local"])){ 
       $sql  .= $virgula." l01_local = '$this->l01_local' ";
       $virgula = ",";
       if(trim($this->l01_local) == null ){ 
         $this->erro_sql = " Campo Local de Abertura nao Informado.";
         $this->erro_campo = "l01_local";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l01_dtpubl)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l01_dtpubl_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["l01_dtpubl_dia"] !="") ){ 
       $sql  .= $virgula." l01_dtpubl = '$this->l01_dtpubl' ";
       $virgula = ",";
       if(trim($this->l01_dtpubl) == null ){ 
         $this->erro_sql = " Campo Data de Publicacao nao Informado.";
         $this->erro_campo = "l01_dtpubl_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["l01_dtpubl_dia"])){ 
         $sql  .= $virgula." l01_dtpubl = null ";
         $virgula = ",";
         if(trim($this->l01_dtpubl) == null ){ 
           $this->erro_sql = " Campo Data de Publicacao nao Informado.";
           $this->erro_campo = "l01_dtpubl_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->l01_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l01_obs"])){ 
       $sql  .= $virgula." l01_obs = '$this->l01_obs' ";
       $virgula = ",";
       if(trim($this->l01_obs) == null ){ 
         $this->erro_sql = " Campo Observacoes nao Informado.";
         $this->erro_campo = "l01_obs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l01_dtadju)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l01_dtadju_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["l01_dtadju_dia"] !="") ){ 
       $sql  .= $virgula." l01_dtadju = '$this->l01_dtadju' ";
       $virgula = ",";
       if(trim($this->l01_dtadju) == null ){ 
         $this->erro_sql = " Campo Data da Adjudicacao ou Encerra nao Informado.";
         $this->erro_campo = "l01_dtadju_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["l01_dtadju_dia"])){ 
         $sql  .= $virgula." l01_dtadju = null ";
         $virgula = ",";
         if(trim($this->l01_dtadju) == null ){ 
           $this->erro_sql = " Campo Data da Adjudicacao ou Encerra nao Informado.";
           $this->erro_campo = "l01_dtadju_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->l01_dotac)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l01_dotac"])){ 
       $sql  .= $virgula." l01_dotac = $this->l01_dotac ";
       $virgula = ",";
       if(trim($this->l01_dotac) == null ){ 
         $this->erro_sql = " Campo Codigo Reduzido da Dotacao nao Informado.";
         $this->erro_campo = "l01_dotac";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
$sql .= "oid = '$oid'";     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dados Cadastrais da Licitacao                      nao Alterado. Alteracao Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Dados Cadastrais da Licitacao                      nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ( $oid=null ,$dbwhere=null) { 
     $sql = " delete from licitac
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
       $sql2 = "oid = '$oid'";
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dados Cadastrais da Licitacao                      nao Excluído. Exclusão Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Dados Cadastrais da Licitacao                      nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:licitac";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>