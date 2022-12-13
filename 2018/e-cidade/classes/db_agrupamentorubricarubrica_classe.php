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

//MODULO: pessoal
//CLASSE DA ENTIDADE agrupamentorubricarubrica
class cl_agrupamentorubricarubrica { 
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
   var $rh114_sequencial = 0; 
   var $rh114_agrupamentorubrica = 0; 
   var $rh114_rubrica = null; 
   var $rh114_instituicao = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh114_sequencial = int4 = Sequencial 
                 rh114_agrupamentorubrica = int4 = Agrupamento Rubrica 
                 rh114_rubrica = char(4) = Rubrica 
                 rh114_instituicao = int4 = Instituição 
                 ";
   //funcao construtor da classe 
   function cl_agrupamentorubricarubrica() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("agrupamentorubricarubrica"); 
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
       $this->rh114_sequencial = ($this->rh114_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh114_sequencial"]:$this->rh114_sequencial);
       $this->rh114_agrupamentorubrica = ($this->rh114_agrupamentorubrica == ""?@$GLOBALS["HTTP_POST_VARS"]["rh114_agrupamentorubrica"]:$this->rh114_agrupamentorubrica);
       $this->rh114_rubrica = ($this->rh114_rubrica == ""?@$GLOBALS["HTTP_POST_VARS"]["rh114_rubrica"]:$this->rh114_rubrica);
       $this->rh114_instituicao = ($this->rh114_instituicao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh114_instituicao"]:$this->rh114_instituicao);
     }else{
       $this->rh114_sequencial = ($this->rh114_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh114_sequencial"]:$this->rh114_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($rh114_sequencial){ 
      $this->atualizacampos();
     if($this->rh114_agrupamentorubrica == null ){ 
       $this->erro_sql = " Campo Agrupamento Rubrica nao Informado.";
       $this->erro_campo = "rh114_agrupamentorubrica";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh114_rubrica == null ){ 
       $this->erro_sql = " Campo Rubrica nao Informado.";
       $this->erro_campo = "rh114_rubrica";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh114_instituicao == null ){ 
       $this->erro_sql = " Campo Instituição nao Informado.";
       $this->erro_campo = "rh114_instituicao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh114_sequencial == "" || $rh114_sequencial == null ){
       $result = db_query("select nextval('agrupamentorubricarubrica_rh114_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: agrupamentorubricarubrica_rh114_sequencial_seq do campo: rh114_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh114_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from agrupamentorubricarubrica_rh114_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh114_sequencial)){
         $this->erro_sql = " Campo rh114_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh114_sequencial = $rh114_sequencial; 
       }
     }
     if(($this->rh114_sequencial == null) || ($this->rh114_sequencial == "") ){ 
       $this->erro_sql = " Campo rh114_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into agrupamentorubricarubrica(
                                       rh114_sequencial 
                                      ,rh114_agrupamentorubrica 
                                      ,rh114_rubrica 
                                      ,rh114_instituicao 
                       )
                values (
                                $this->rh114_sequencial 
                               ,$this->rh114_agrupamentorubrica 
                               ,'$this->rh114_rubrica' 
                               ,$this->rh114_instituicao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Rubras do Agrupamento ($this->rh114_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Rubras do Agrupamento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Rubras do Agrupamento ($this->rh114_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh114_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->rh114_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,19563,'$this->rh114_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3479,19563,'','".AddSlashes(pg_result($resaco,0,'rh114_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3479,19565,'','".AddSlashes(pg_result($resaco,0,'rh114_agrupamentorubrica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3479,19566,'','".AddSlashes(pg_result($resaco,0,'rh114_rubrica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3479,19567,'','".AddSlashes(pg_result($resaco,0,'rh114_instituicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh114_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update agrupamentorubricarubrica set ";
     $virgula = "";
     if(trim($this->rh114_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh114_sequencial"])){ 
       $sql  .= $virgula." rh114_sequencial = $this->rh114_sequencial ";
       $virgula = ",";
       if(trim($this->rh114_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "rh114_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh114_agrupamentorubrica)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh114_agrupamentorubrica"])){ 
       $sql  .= $virgula." rh114_agrupamentorubrica = $this->rh114_agrupamentorubrica ";
       $virgula = ",";
       if(trim($this->rh114_agrupamentorubrica) == null ){ 
         $this->erro_sql = " Campo Agrupamento Rubrica nao Informado.";
         $this->erro_campo = "rh114_agrupamentorubrica";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh114_rubrica)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh114_rubrica"])){ 
       $sql  .= $virgula." rh114_rubrica = '$this->rh114_rubrica' ";
       $virgula = ",";
       if(trim($this->rh114_rubrica) == null ){ 
         $this->erro_sql = " Campo Rubrica nao Informado.";
         $this->erro_campo = "rh114_rubrica";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh114_instituicao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh114_instituicao"])){ 
       $sql  .= $virgula." rh114_instituicao = $this->rh114_instituicao ";
       $virgula = ",";
       if(trim($this->rh114_instituicao) == null ){ 
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "rh114_instituicao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh114_sequencial!=null){
       $sql .= " rh114_sequencial = $this->rh114_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->rh114_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19563,'$this->rh114_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh114_sequencial"]) || $this->rh114_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3479,19563,'".AddSlashes(pg_result($resaco,$conresaco,'rh114_sequencial'))."','$this->rh114_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh114_agrupamentorubrica"]) || $this->rh114_agrupamentorubrica != "")
           $resac = db_query("insert into db_acount values($acount,3479,19565,'".AddSlashes(pg_result($resaco,$conresaco,'rh114_agrupamentorubrica'))."','$this->rh114_agrupamentorubrica',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh114_rubrica"]) || $this->rh114_rubrica != "")
           $resac = db_query("insert into db_acount values($acount,3479,19566,'".AddSlashes(pg_result($resaco,$conresaco,'rh114_rubrica'))."','$this->rh114_rubrica',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh114_instituicao"]) || $this->rh114_instituicao != "")
           $resac = db_query("insert into db_acount values($acount,3479,19567,'".AddSlashes(pg_result($resaco,$conresaco,'rh114_instituicao'))."','$this->rh114_instituicao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Rubras do Agrupamento nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh114_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Rubras do Agrupamento nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh114_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh114_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh114_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($rh114_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19563,'$rh114_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3479,19563,'','".AddSlashes(pg_result($resaco,$iresaco,'rh114_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3479,19565,'','".AddSlashes(pg_result($resaco,$iresaco,'rh114_agrupamentorubrica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3479,19566,'','".AddSlashes(pg_result($resaco,$iresaco,'rh114_rubrica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3479,19567,'','".AddSlashes(pg_result($resaco,$iresaco,'rh114_instituicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from agrupamentorubricarubrica
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh114_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh114_sequencial = $rh114_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Rubras do Agrupamento nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh114_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Rubras do Agrupamento nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh114_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh114_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:agrupamentorubricarubrica";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $rh114_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from agrupamentorubricarubrica ";
     $sql .= "      inner join rhrubricas  on  rhrubricas.rh27_rubric = agrupamentorubricarubrica.rh114_rubrica and  rhrubricas.rh27_instit = agrupamentorubricarubrica.rh114_instituicao";
     $sql .= "      inner join agrupamentorubrica  on  agrupamentorubrica.rh113_sequencial = agrupamentorubricarubrica.rh114_agrupamentorubrica";
     $sql .= "      inner join db_config  on  db_config.codigo = rhrubricas.rh27_instit";
     $sql .= "      inner join rhtipomedia  on  rhtipomedia.rh29_tipo = rhrubricas.rh27_calc1";
     $sql2 = "";
     if($dbwhere==""){
       if($rh114_sequencial!=null ){
         $sql2 .= " where agrupamentorubricarubrica.rh114_sequencial = $rh114_sequencial "; 
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
   function sql_query_file ( $rh114_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from agrupamentorubricarubrica ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh114_sequencial!=null ){
         $sql2 .= " where agrupamentorubricarubrica.rh114_sequencial = $rh114_sequencial "; 
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
  
  /**
   * Busca o grupo pelo codigo da rubrica
   * @param Rubrica $oRubrica
   * @return Ambigous <stdClass, _db_fields>|NULL
   */
  function getGrupoPelaRubrica( $sCodigoRubrica ) {
  
    $sWhere  = "rh27_rubric = '{$sCodigoRubrica}'";
    $sSql    = $this->sql_query(null, 'rh114_agrupamentorubrica, rh113_codigo, rh113_tipo, rh113_descricao', null, $sWhere);
    $rsGrupo = db_query($sSql);
  
    if ( $rsGrupo && pg_num_rows($rsGrupo) > 0 ) {
      return db_utils::fieldsMemory($rsGrupo, 0);
    }
    return null;
  }
  
}
?>