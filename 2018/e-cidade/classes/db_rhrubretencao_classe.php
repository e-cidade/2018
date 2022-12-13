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

//MODULO: pessoal
//CLASSE DA ENTIDADE rhrubretencao
class cl_rhrubretencao { 
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
   var $rh75_sequencial = 0; 
   var $rh75_rubric = null; 
   var $rh75_instit = 0; 
   var $rh75_retencaotiporec = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh75_sequencial = int4 = Sequencial 
                 rh75_rubric = char(4) = Rubrica 
                 rh75_instit = int4 = Intituição 
                 rh75_retencaotiporec = int4 = Retenção 
                 ";
   //funcao construtor da classe 
   function cl_rhrubretencao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhrubretencao"); 
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
       $this->rh75_sequencial = ($this->rh75_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh75_sequencial"]:$this->rh75_sequencial);
       $this->rh75_rubric = ($this->rh75_rubric == ""?@$GLOBALS["HTTP_POST_VARS"]["rh75_rubric"]:$this->rh75_rubric);
       $this->rh75_instit = ($this->rh75_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["rh75_instit"]:$this->rh75_instit);
       $this->rh75_retencaotiporec = ($this->rh75_retencaotiporec == ""?@$GLOBALS["HTTP_POST_VARS"]["rh75_retencaotiporec"]:$this->rh75_retencaotiporec);
     }else{
       $this->rh75_sequencial = ($this->rh75_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh75_sequencial"]:$this->rh75_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($rh75_sequencial){ 
      $this->atualizacampos();
     if($this->rh75_rubric == null ){ 
       $this->erro_sql = " Campo Rubrica nao Informado.";
       $this->erro_campo = "rh75_rubric";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh75_instit == null ){ 
       $this->erro_sql = " Campo Intituição nao Informado.";
       $this->erro_campo = "rh75_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh75_retencaotiporec == null ){ 
       $this->erro_sql = " Campo Retenção nao Informado.";
       $this->erro_campo = "rh75_retencaotiporec";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh75_sequencial == "" || $rh75_sequencial == null ){
       $result = db_query("select nextval('rhrubretencao_rh75_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhrubretencao_rh75_sequencial_seq do campo: rh75_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh75_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhrubretencao_rh75_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh75_sequencial)){
         $this->erro_sql = " Campo rh75_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh75_sequencial = $rh75_sequencial; 
       }
     }
     if(($this->rh75_sequencial == null) || ($this->rh75_sequencial == "") ){ 
       $this->erro_sql = " Campo rh75_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhrubretencao(
                                       rh75_sequencial 
                                      ,rh75_rubric 
                                      ,rh75_instit 
                                      ,rh75_retencaotiporec 
                       )
                values (
                                $this->rh75_sequencial 
                               ,'$this->rh75_rubric' 
                               ,$this->rh75_instit 
                               ,$this->rh75_retencaotiporec 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "rhrubretencao ($this->rh75_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "rhrubretencao já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "rhrubretencao ($this->rh75_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh75_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->rh75_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,14371,'$this->rh75_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2531,14371,'','".AddSlashes(pg_result($resaco,0,'rh75_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2531,14372,'','".AddSlashes(pg_result($resaco,0,'rh75_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2531,14373,'','".AddSlashes(pg_result($resaco,0,'rh75_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2531,14374,'','".AddSlashes(pg_result($resaco,0,'rh75_retencaotiporec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh75_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhrubretencao set ";
     $virgula = "";
     if(trim($this->rh75_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh75_sequencial"])){ 
       $sql  .= $virgula." rh75_sequencial = $this->rh75_sequencial ";
       $virgula = ",";
       if(trim($this->rh75_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "rh75_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh75_rubric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh75_rubric"])){ 
       $sql  .= $virgula." rh75_rubric = '$this->rh75_rubric' ";
       $virgula = ",";
       if(trim($this->rh75_rubric) == null ){ 
         $this->erro_sql = " Campo Rubrica nao Informado.";
         $this->erro_campo = "rh75_rubric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh75_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh75_instit"])){ 
       $sql  .= $virgula." rh75_instit = $this->rh75_instit ";
       $virgula = ",";
       if(trim($this->rh75_instit) == null ){ 
         $this->erro_sql = " Campo Intituição nao Informado.";
         $this->erro_campo = "rh75_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh75_retencaotiporec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh75_retencaotiporec"])){ 
       $sql  .= $virgula." rh75_retencaotiporec = $this->rh75_retencaotiporec ";
       $virgula = ",";
       if(trim($this->rh75_retencaotiporec) == null ){ 
         $this->erro_sql = " Campo Retenção nao Informado.";
         $this->erro_campo = "rh75_retencaotiporec";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh75_sequencial!=null){
       $sql .= " rh75_sequencial = $this->rh75_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->rh75_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14371,'$this->rh75_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh75_sequencial"]) || $this->rh75_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2531,14371,'".AddSlashes(pg_result($resaco,$conresaco,'rh75_sequencial'))."','$this->rh75_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh75_rubric"]) || $this->rh75_rubric != "")
           $resac = db_query("insert into db_acount values($acount,2531,14372,'".AddSlashes(pg_result($resaco,$conresaco,'rh75_rubric'))."','$this->rh75_rubric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh75_instit"]) || $this->rh75_instit != "")
           $resac = db_query("insert into db_acount values($acount,2531,14373,'".AddSlashes(pg_result($resaco,$conresaco,'rh75_instit'))."','$this->rh75_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh75_retencaotiporec"]) || $this->rh75_retencaotiporec != "")
           $resac = db_query("insert into db_acount values($acount,2531,14374,'".AddSlashes(pg_result($resaco,$conresaco,'rh75_retencaotiporec'))."','$this->rh75_retencaotiporec',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "rhrubretencao nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh75_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "rhrubretencao nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh75_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh75_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh75_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($rh75_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14371,'$rh75_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2531,14371,'','".AddSlashes(pg_result($resaco,$iresaco,'rh75_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2531,14372,'','".AddSlashes(pg_result($resaco,$iresaco,'rh75_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2531,14373,'','".AddSlashes(pg_result($resaco,$iresaco,'rh75_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2531,14374,'','".AddSlashes(pg_result($resaco,$iresaco,'rh75_retencaotiporec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rhrubretencao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh75_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh75_sequencial = $rh75_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "rhrubretencao nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh75_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "rhrubretencao nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh75_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh75_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhrubretencao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $rh75_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhrubretencao ";
     $sql .= "      inner join rhrubricas  on  rhrubricas.rh27_rubric = rhrubretencao.rh75_rubric and  rhrubricas.rh27_instit = rhrubretencao.rh75_instit";
     $sql .= "      inner join retencaotiporec  on  retencaotiporec.e21_sequencial = rhrubretencao.rh75_retencaotiporec";
     $sql .= "      inner join db_config  on  db_config.codigo = rhrubricas.rh27_instit";
     $sql .= "      inner join rhtipomedia  on  rhtipomedia.rh29_tipo = rhrubricas.rh27_calc1";
     $sql .= "      inner join tabrec  on  tabrec.k02_codigo = retencaotiporec.e21_receita";
     $sql .= "      inner join db_config  as a on   a.codigo = retencaotiporec.e21_instit";
     $sql .= "      inner join retencaotipocalc  on  retencaotipocalc.e32_sequencial = retencaotiporec.e21_retencaotipocalc";
     $sql .= "      inner join retencaotiporecgrupo  on  retencaotiporecgrupo.e01_sequencial = retencaotiporec.e21_retencaotiporecgrupo";
     $sql2 = "";
     if($dbwhere==""){
       if($rh75_sequencial!=null ){
         $sql2 .= " where rhrubretencao.rh75_sequencial = $rh75_sequencial "; 
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
   function sql_query_file ( $rh75_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhrubretencao ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh75_sequencial!=null ){
         $sql2 .= " where rhrubretencao.rh75_sequencial = $rh75_sequencial "; 
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