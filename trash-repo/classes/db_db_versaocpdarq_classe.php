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

//MODULO: configuracoes
//CLASSE DA ENTIDADE db_versaocpdarq
class cl_db_versaocpdarq { 
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
   var $db34_codarq = 0; 
   var $db34_codcpd = 0; 
   var $db34_descr = null; 
   var $db34_obs = null; 
   var $db34_arq = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db34_codarq = int4 = Código do Arquivo 
                 db34_codcpd = int4 = Código da Observação 
                 db34_descr = varchar(15) = Descrição 
                 db34_obs = text = Observações para o CPD 
                 db34_arq = text = Arquivos anexos a versão 
                 ";
   //funcao construtor da classe 
   function cl_db_versaocpdarq() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_versaocpdarq"); 
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
       $this->db34_codarq = ($this->db34_codarq == ""?@$GLOBALS["HTTP_POST_VARS"]["db34_codarq"]:$this->db34_codarq);
       $this->db34_codcpd = ($this->db34_codcpd == ""?@$GLOBALS["HTTP_POST_VARS"]["db34_codcpd"]:$this->db34_codcpd);
       $this->db34_descr = ($this->db34_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["db34_descr"]:$this->db34_descr);
       $this->db34_obs = ($this->db34_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["db34_obs"]:$this->db34_obs);
       $this->db34_arq = ($this->db34_arq == ""?@$GLOBALS["HTTP_POST_VARS"]["db34_arq"]:$this->db34_arq);
     }else{
       $this->db34_codarq = ($this->db34_codarq == ""?@$GLOBALS["HTTP_POST_VARS"]["db34_codarq"]:$this->db34_codarq);
     }
   }
   // funcao para inclusao
   function incluir ($db34_codarq){ 
      $this->atualizacampos();
     if($this->db34_codcpd == null ){ 
       $this->erro_sql = " Campo Código da Observação nao Informado.";
       $this->erro_campo = "db34_codcpd";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db34_descr == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "db34_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db34_obs == null ){ 
       $this->erro_sql = " Campo Observações para o CPD nao Informado.";
       $this->erro_campo = "db34_obs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db34_arq == null ){ 
       $this->erro_sql = " Campo Arquivos anexos a versão nao Informado.";
       $this->erro_campo = "db34_arq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($db34_codarq == "" || $db34_codarq == null ){
       $result = db_query("select nextval('db_versaocpdarq_db34_codarq_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: db_versaocpdarq_db34_codarq_seq do campo: db34_codarq"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db34_codarq = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from db_versaocpdarq_db34_codarq_seq");
       if(($result != false) && (pg_result($result,0,0) < $db34_codarq)){
         $this->erro_sql = " Campo db34_codarq maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db34_codarq = $db34_codarq; 
       }
     }
     if(($this->db34_codarq == null) || ($this->db34_codarq == "") ){ 
       $this->erro_sql = " Campo db34_codarq nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_versaocpdarq(
                                       db34_codarq 
                                      ,db34_codcpd 
                                      ,db34_descr 
                                      ,db34_obs 
                                      ,db34_arq 
                       )
                values (
                                $this->db34_codarq 
                               ,$this->db34_codcpd 
                               ,'$this->db34_descr' 
                               ,'$this->db34_obs' 
                               ,'$this->db34_arq' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Arquivos anexos a versão ($this->db34_codarq) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Arquivos anexos a versão já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Arquivos anexos a versão ($this->db34_codarq) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db34_codarq;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->db34_codarq));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5895,'$this->db34_codarq','I')");
       $resac = db_query("insert into db_acount values($acount,942,5895,'','".AddSlashes(pg_result($resaco,0,'db34_codarq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,942,5884,'','".AddSlashes(pg_result($resaco,0,'db34_codcpd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,942,5896,'','".AddSlashes(pg_result($resaco,0,'db34_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,942,5886,'','".AddSlashes(pg_result($resaco,0,'db34_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,942,5885,'','".AddSlashes(pg_result($resaco,0,'db34_arq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($db34_codarq=null) { 
      $this->atualizacampos();
     $sql = " update db_versaocpdarq set ";
     $virgula = "";
     if(trim($this->db34_codarq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db34_codarq"])){ 
       $sql  .= $virgula." db34_codarq = $this->db34_codarq ";
       $virgula = ",";
       if(trim($this->db34_codarq) == null ){ 
         $this->erro_sql = " Campo Código do Arquivo nao Informado.";
         $this->erro_campo = "db34_codarq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db34_codcpd)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db34_codcpd"])){ 
       $sql  .= $virgula." db34_codcpd = $this->db34_codcpd ";
       $virgula = ",";
       if(trim($this->db34_codcpd) == null ){ 
         $this->erro_sql = " Campo Código da Observação nao Informado.";
         $this->erro_campo = "db34_codcpd";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db34_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db34_descr"])){ 
       $sql  .= $virgula." db34_descr = '$this->db34_descr' ";
       $virgula = ",";
       if(trim($this->db34_descr) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "db34_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db34_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db34_obs"])){ 
       $sql  .= $virgula." db34_obs = '$this->db34_obs' ";
       $virgula = ",";
       if(trim($this->db34_obs) == null ){ 
         $this->erro_sql = " Campo Observações para o CPD nao Informado.";
         $this->erro_campo = "db34_obs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db34_arq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db34_arq"])){ 
       $sql  .= $virgula." db34_arq = '$this->db34_arq' ";
       $virgula = ",";
       if(trim($this->db34_arq) == null ){ 
         $this->erro_sql = " Campo Arquivos anexos a versão nao Informado.";
         $this->erro_campo = "db34_arq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($db34_codarq!=null){
       $sql .= " db34_codarq = $this->db34_codarq";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->db34_codarq));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5895,'$this->db34_codarq','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db34_codarq"]))
           $resac = db_query("insert into db_acount values($acount,942,5895,'".AddSlashes(pg_result($resaco,$conresaco,'db34_codarq'))."','$this->db34_codarq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db34_codcpd"]))
           $resac = db_query("insert into db_acount values($acount,942,5884,'".AddSlashes(pg_result($resaco,$conresaco,'db34_codcpd'))."','$this->db34_codcpd',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db34_descr"]))
           $resac = db_query("insert into db_acount values($acount,942,5896,'".AddSlashes(pg_result($resaco,$conresaco,'db34_descr'))."','$this->db34_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db34_obs"]))
           $resac = db_query("insert into db_acount values($acount,942,5886,'".AddSlashes(pg_result($resaco,$conresaco,'db34_obs'))."','$this->db34_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db34_arq"]))
           $resac = db_query("insert into db_acount values($acount,942,5885,'".AddSlashes(pg_result($resaco,$conresaco,'db34_arq'))."','$this->db34_arq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Arquivos anexos a versão nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db34_codarq;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Arquivos anexos a versão nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db34_codarq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db34_codarq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($db34_codarq=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($db34_codarq));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5895,'$db34_codarq','E')");
         $resac = db_query("insert into db_acount values($acount,942,5895,'','".AddSlashes(pg_result($resaco,$iresaco,'db34_codarq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,942,5884,'','".AddSlashes(pg_result($resaco,$iresaco,'db34_codcpd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,942,5896,'','".AddSlashes(pg_result($resaco,$iresaco,'db34_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,942,5886,'','".AddSlashes(pg_result($resaco,$iresaco,'db34_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,942,5885,'','".AddSlashes(pg_result($resaco,$iresaco,'db34_arq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_versaocpdarq
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db34_codarq != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db34_codarq = $db34_codarq ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Arquivos anexos a versão nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db34_codarq;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Arquivos anexos a versão nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db34_codarq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db34_codarq;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_versaocpdarq";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $db34_codarq=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from db_versaocpdarq ";
     $sql2 = "";
     if($dbwhere==""){
       if($db34_codarq!=null ){
         $sql2 .= " where db_versaocpdarq.db34_codarq = $db34_codarq "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   function sql_query_file ( $db34_codarq=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from db_versaocpdarq ";
     $sql2 = "";
     if($dbwhere==""){
       if($db34_codarq!=null ){
         $sql2 .= " where db_versaocpdarq.db34_codarq = $db34_codarq "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
}
?>