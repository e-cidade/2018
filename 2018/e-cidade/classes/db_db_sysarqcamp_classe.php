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

//MODULO: configuracoes
//CLASSE DA ENTIDADE db_sysarqcamp
class cl_db_sysarqcamp { 
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
   var $codarq = 0; 
   var $codcam = 0; 
   var $seqarq = 0; 
   var $codsequencia = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 codarq = int4 = Codigo Arquivo 
                 codcam = int4 = Código 
                 seqarq = int4 = Sequencia 
                 codsequencia = int4 = Código 
                 ";
   //funcao construtor da classe 
   function cl_db_sysarqcamp() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_sysarqcamp"); 
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
       $this->codarq = ($this->codarq == ""?@$GLOBALS["HTTP_POST_VARS"]["codarq"]:$this->codarq);
       $this->codcam = ($this->codcam == ""?@$GLOBALS["HTTP_POST_VARS"]["codcam"]:$this->codcam);
       $this->seqarq = ($this->seqarq == ""?@$GLOBALS["HTTP_POST_VARS"]["seqarq"]:$this->seqarq);
       $this->codsequencia = ($this->codsequencia == ""?@$GLOBALS["HTTP_POST_VARS"]["codsequencia"]:$this->codsequencia);
     }else{
       $this->codarq = ($this->codarq == ""?@$GLOBALS["HTTP_POST_VARS"]["codarq"]:$this->codarq);
       $this->codcam = ($this->codcam == ""?@$GLOBALS["HTTP_POST_VARS"]["codcam"]:$this->codcam);
       $this->seqarq = ($this->seqarq == ""?@$GLOBALS["HTTP_POST_VARS"]["seqarq"]:$this->seqarq);
     }
   }
   // funcao para inclusao
   function incluir ($codarq,$codcam,$seqarq){ 
      $this->atualizacampos();
     if($this->codsequencia == null ){ 
       $this->erro_sql = " Campo Código nao Informado.";
       $this->erro_campo = "codsequencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->codarq = $codarq; 
       $this->codcam = $codcam; 
       $this->seqarq = $seqarq; 
     if(($this->codarq == null) || ($this->codarq == "") ){ 
       $this->erro_sql = " Campo codarq nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->codcam == null) || ($this->codcam == "") ){ 
       $this->erro_sql = " Campo codcam nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->seqarq == null) || ($this->seqarq == "") ){ 
       $this->erro_sql = " Campo seqarq nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_sysarqcamp(
                                       codarq 
                                      ,codcam 
                                      ,seqarq 
                                      ,codsequencia 
                       )
                values (
                                $this->codarq 
                               ,$this->codcam 
                               ,$this->seqarq 
                               ,$this->codsequencia 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Campos e tabelas ($this->codarq."-".$this->codcam."-".$this->seqarq) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Campos e tabelas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Campos e tabelas ($this->codarq."-".$this->codcam."-".$this->seqarq) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->codarq."-".$this->codcam."-".$this->seqarq;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->codarq,$this->codcam,$this->seqarq));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,759,'$this->codarq','I')");
       $resac = db_query("insert into db_acountkey values($acount,752,'$this->codcam','I')");
       $resac = db_query("insert into db_acountkey values($acount,783,'$this->seqarq','I')");
       $resac = db_query("insert into db_acount values($acount,141,759,'','".AddSlashes(pg_result($resaco,0,'codarq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,141,752,'','".AddSlashes(pg_result($resaco,0,'codcam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,141,783,'','".AddSlashes(pg_result($resaco,0,'seqarq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,141,766,'','".AddSlashes(pg_result($resaco,0,'codsequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($codarq=null,$codcam=null,$seqarq=null) { 
      $this->atualizacampos();
     $sql = " update db_sysarqcamp set ";
     $virgula = "";
     if(trim($this->codarq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["codarq"])){ 
       $sql  .= $virgula." codarq = $this->codarq ";
       $virgula = ",";
       if(trim($this->codarq) == null ){ 
         $this->erro_sql = " Campo Codigo Arquivo nao Informado.";
         $this->erro_campo = "codarq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->codcam)!="" || isset($GLOBALS["HTTP_POST_VARS"]["codcam"])){ 
       $sql  .= $virgula." codcam = $this->codcam ";
       $virgula = ",";
       if(trim($this->codcam) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "codcam";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->seqarq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["seqarq"])){ 
       $sql  .= $virgula." seqarq = $this->seqarq ";
       $virgula = ",";
       if(trim($this->seqarq) == null ){ 
         $this->erro_sql = " Campo Sequencia nao Informado.";
         $this->erro_campo = "seqarq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->codsequencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["codsequencia"])){ 
       $sql  .= $virgula." codsequencia = $this->codsequencia ";
       $virgula = ",";
       if(trim($this->codsequencia) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "codsequencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($codarq!=null){
       $sql .= " codarq = $this->codarq";
     }
     if($codcam!=null){
       $sql .= " and  codcam = $this->codcam";
     }
     if($seqarq!=null){
       $sql .= " and  seqarq = $this->seqarq";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->codarq,$this->codcam,$this->seqarq));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,759,'$this->codarq','A')");
         $resac = db_query("insert into db_acountkey values($acount,752,'$this->codcam','A')");
         $resac = db_query("insert into db_acountkey values($acount,783,'$this->seqarq','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["codarq"]))
           $resac = db_query("insert into db_acount values($acount,141,759,'".AddSlashes(pg_result($resaco,$conresaco,'codarq'))."','$this->codarq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["codcam"]))
           $resac = db_query("insert into db_acount values($acount,141,752,'".AddSlashes(pg_result($resaco,$conresaco,'codcam'))."','$this->codcam',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["seqarq"]))
           $resac = db_query("insert into db_acount values($acount,141,783,'".AddSlashes(pg_result($resaco,$conresaco,'seqarq'))."','$this->seqarq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["codsequencia"]))
           $resac = db_query("insert into db_acount values($acount,141,766,'".AddSlashes(pg_result($resaco,$conresaco,'codsequencia'))."','$this->codsequencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Campos e tabelas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->codarq."-".$this->codcam."-".$this->seqarq;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Campos e tabelas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->codarq."-".$this->codcam."-".$this->seqarq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->codarq."-".$this->codcam."-".$this->seqarq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($codarq=null,$codcam=null,$seqarq=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($codarq,$codcam,$seqarq));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,759,'$codarq','E')");
         $resac = db_query("insert into db_acountkey values($acount,752,'$codcam','E')");
         $resac = db_query("insert into db_acountkey values($acount,783,'$seqarq','E')");
         $resac = db_query("insert into db_acount values($acount,141,759,'','".AddSlashes(pg_result($resaco,$iresaco,'codarq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,141,752,'','".AddSlashes(pg_result($resaco,$iresaco,'codcam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,141,783,'','".AddSlashes(pg_result($resaco,$iresaco,'seqarq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,141,766,'','".AddSlashes(pg_result($resaco,$iresaco,'codsequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_sysarqcamp
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($codarq != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " codarq = $codarq ";
        }
        if($codcam != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " codcam = $codcam ";
        }
        if($seqarq != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " seqarq = $seqarq ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Campos e tabelas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$codarq."-".$codcam."-".$seqarq;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Campos e tabelas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$codarq."-".$codcam."-".$seqarq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$codarq."-".$codcam."-".$seqarq;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_sysarqcamp";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $codarq=null,$codcam=null,$seqarq=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_sysarqcamp ";
     $sql .= "      inner join db_sysarquivo  on  db_sysarquivo.codarq = db_sysarqcamp.codarq";
     $sql .= "      inner join db_syscampo  on  db_syscampo.codcam = db_sysarqcamp.codcam";
     $sql2 = "";
     if($dbwhere==""){
       if($codarq!=null ){
         $sql2 .= " where db_sysarqcamp.codarq = $codarq "; 
       } 
       if($codcam!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " db_sysarqcamp.codcam = $codcam "; 
       } 
       if($seqarq!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " db_sysarqcamp.seqarq = $seqarq "; 
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
   function sql_query_file ( $codarq=null,$codcam=null,$seqarq=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_sysarqcamp ";
     $sql2 = "";
     if($dbwhere==""){
       if($codarq!=null ){
         $sql2 .= " where db_sysarqcamp.codarq = $codarq "; 
       } 
       if($codcam!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " db_sysarqcamp.codcam = $codcam "; 
       } 
       if($seqarq!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " db_sysarqcamp.seqarq = $seqarq "; 
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

  public function sql_camposNovo($iTabela, $sFields, $sOrder) {

    $sSql  = "select {$sFields}                                                           ";
    $sSql .= "  from db_sysarqcamp arqcampo                                               ";
    $sSql .= "       inner join db_syscampo    campo on campo.codcam    = arqcampo.codcam ";
    $sSql .= "       left  join db_syscampodep dep   on campo.codcam    = dep.codcam      ";
    $sSql .= "       left  join db_syscampo    pai   on pai.codcam      = dep.codcampai   ";
    $sSql .= " where codarq  = {$iTabela}                                                 ";
    $sSql .= " order by {$sOrder}                                                         ";

    return $sSql;
  }
}
?>