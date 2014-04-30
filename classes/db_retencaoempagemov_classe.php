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

//MODULO: empenho
//CLASSE DA ENTIDADE retencaoempagemov
class cl_retencaoempagemov { 
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
   var $e27_sequencial = 0; 
   var $e27_retencaoreceitas = 0; 
   var $e27_empagemov = 0; 
   var $e27_principal = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 e27_sequencial = int4 = Código Sequencial 
                 e27_retencaoreceitas = int4 = Código da Retencao 
                 e27_empagemov = int4 = Código do Movimento 
                 e27_principal = bool = Retenção Principal 
                 ";
   //funcao construtor da classe 
   function cl_retencaoempagemov() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("retencaoempagemov"); 
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
       $this->e27_sequencial = ($this->e27_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["e27_sequencial"]:$this->e27_sequencial);
       $this->e27_retencaoreceitas = ($this->e27_retencaoreceitas == ""?@$GLOBALS["HTTP_POST_VARS"]["e27_retencaoreceitas"]:$this->e27_retencaoreceitas);
       $this->e27_empagemov = ($this->e27_empagemov == ""?@$GLOBALS["HTTP_POST_VARS"]["e27_empagemov"]:$this->e27_empagemov);
       $this->e27_principal = ($this->e27_principal == "f"?@$GLOBALS["HTTP_POST_VARS"]["e27_principal"]:$this->e27_principal);
     }else{
       $this->e27_sequencial = ($this->e27_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["e27_sequencial"]:$this->e27_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($e27_sequencial){ 
      $this->atualizacampos();
     if($this->e27_retencaoreceitas == null ){ 
       $this->erro_sql = " Campo Código da Retencao nao Informado.";
       $this->erro_campo = "e27_retencaoreceitas";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e27_empagemov == null ){ 
       $this->erro_sql = " Campo Código do Movimento nao Informado.";
       $this->erro_campo = "e27_empagemov";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e27_principal == null ){ 
       $this->erro_sql = " Campo Retenção Principal nao Informado.";
       $this->erro_campo = "e27_principal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($e27_sequencial == "" || $e27_sequencial == null ){
       $result = db_query("select nextval('retencaoempagemov_e27_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: retencaoempagemov_e27_sequencial_seq do campo: e27_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->e27_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from retencaoempagemov_e27_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $e27_sequencial)){
         $this->erro_sql = " Campo e27_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->e27_sequencial = $e27_sequencial; 
       }
     }
     if(($this->e27_sequencial == null) || ($this->e27_sequencial == "") ){ 
       $this->erro_sql = " Campo e27_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into retencaoempagemov(
                                       e27_sequencial 
                                      ,e27_retencaoreceitas 
                                      ,e27_empagemov 
                                      ,e27_principal 
                       )
                values (
                                $this->e27_sequencial 
                               ,$this->e27_retencaoreceitas 
                               ,$this->e27_empagemov 
                               ,'$this->e27_principal' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Movimento da retencao ($this->e27_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Movimento da retencao já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Movimento da retencao ($this->e27_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e27_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->e27_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,12376,'$this->e27_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2156,12376,'','".AddSlashes(pg_result($resaco,0,'e27_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2156,12377,'','".AddSlashes(pg_result($resaco,0,'e27_retencaoreceitas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2156,12378,'','".AddSlashes(pg_result($resaco,0,'e27_empagemov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2156,12387,'','".AddSlashes(pg_result($resaco,0,'e27_principal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($e27_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update retencaoempagemov set ";
     $virgula = "";
     if(trim($this->e27_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e27_sequencial"])){ 
       $sql  .= $virgula." e27_sequencial = $this->e27_sequencial ";
       $virgula = ",";
       if(trim($this->e27_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "e27_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e27_retencaoreceitas)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e27_retencaoreceitas"])){ 
       $sql  .= $virgula." e27_retencaoreceitas = $this->e27_retencaoreceitas ";
       $virgula = ",";
       if(trim($this->e27_retencaoreceitas) == null ){ 
         $this->erro_sql = " Campo Código da Retencao nao Informado.";
         $this->erro_campo = "e27_retencaoreceitas";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e27_empagemov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e27_empagemov"])){ 
       $sql  .= $virgula." e27_empagemov = $this->e27_empagemov ";
       $virgula = ",";
       if(trim($this->e27_empagemov) == null ){ 
         $this->erro_sql = " Campo Código do Movimento nao Informado.";
         $this->erro_campo = "e27_empagemov";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e27_principal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e27_principal"])){ 
       $sql  .= $virgula." e27_principal = '$this->e27_principal' ";
       $virgula = ",";
       if(trim($this->e27_principal) == null ){ 
         $this->erro_sql = " Campo Retenção Principal nao Informado.";
         $this->erro_campo = "e27_principal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($e27_sequencial!=null){
       $sql .= " e27_sequencial = $this->e27_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->e27_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12376,'$this->e27_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e27_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,2156,12376,'".AddSlashes(pg_result($resaco,$conresaco,'e27_sequencial'))."','$this->e27_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e27_retencaoreceitas"]))
           $resac = db_query("insert into db_acount values($acount,2156,12377,'".AddSlashes(pg_result($resaco,$conresaco,'e27_retencaoreceitas'))."','$this->e27_retencaoreceitas',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e27_empagemov"]))
           $resac = db_query("insert into db_acount values($acount,2156,12378,'".AddSlashes(pg_result($resaco,$conresaco,'e27_empagemov'))."','$this->e27_empagemov',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e27_principal"]))
           $resac = db_query("insert into db_acount values($acount,2156,12387,'".AddSlashes(pg_result($resaco,$conresaco,'e27_principal'))."','$this->e27_principal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Movimento da retencao nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->e27_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Movimento da retencao nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->e27_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e27_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($e27_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($e27_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12376,'$e27_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2156,12376,'','".AddSlashes(pg_result($resaco,$iresaco,'e27_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2156,12377,'','".AddSlashes(pg_result($resaco,$iresaco,'e27_retencaoreceitas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2156,12378,'','".AddSlashes(pg_result($resaco,$iresaco,'e27_empagemov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2156,12387,'','".AddSlashes(pg_result($resaco,$iresaco,'e27_principal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from retencaoempagemov
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($e27_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " e27_sequencial = $e27_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Movimento da retencao nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$e27_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Movimento da retencao nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$e27_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$e27_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:retencaoempagemov";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $e27_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from retencaoempagemov ";
     $sql .= "      inner join empagemov  on  empagemov.e81_codmov = retencaoempagemov.e27_empagemov";
     $sql .= "      inner join retencaoreceitas  on  retencaoreceitas.e23_sequencial = retencaoempagemov.e27_retencaoreceitas";
     $sql .= "      inner join empage  on  empage.e80_codage = empagemov.e81_codage";
     $sql .= "      inner join retencaotiporec  on  retencaotiporec.e21_sequencial = retencaoreceitas.e23_retencaotiporec";
     $sql .= "      inner join retencaopagordem  on  retencaopagordem.e20_sequencial = retencaoreceitas.e23_retencaopagordem";
     $sql2 = "";
     if($dbwhere==""){
       if($e27_sequencial!=null ){
         $sql2 .= " where retencaoempagemov.e27_sequencial = $e27_sequencial "; 
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
   // funcao do sql 
   function sql_query_file ( $e27_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from retencaoempagemov ";
     $sql2 = "";
     if($dbwhere==""){
       if($e27_sequencial!=null ){
         $sql2 .= " where retencaoempagemov.e27_sequencial = $e27_sequencial "; 
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