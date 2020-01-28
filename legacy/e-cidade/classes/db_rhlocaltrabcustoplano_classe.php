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
//CLASSE DA ENTIDADE rhlocaltrabcustoplano
class cl_rhlocaltrabcustoplano { 
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
   var $rh86_sequencial = 0; 
   var $rh86_criteriorateio = 0; 
   var $rh86_rhlocaltrab = 0; 
   var $rh86_instit = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh86_sequencial = int4 = Código Sequencial 
                 rh86_criteriorateio = int4 = Criterio Rateio 
                 rh86_rhlocaltrab = int4 = Local de Trabalho 
                 rh86_instit = int4 = Instituicao 
                 ";
   //funcao construtor da classe 
   function cl_rhlocaltrabcustoplano() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhlocaltrabcustoplano"); 
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
       $this->rh86_sequencial = ($this->rh86_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh86_sequencial"]:$this->rh86_sequencial);
       $this->rh86_criteriorateio = ($this->rh86_criteriorateio == ""?@$GLOBALS["HTTP_POST_VARS"]["rh86_criteriorateio"]:$this->rh86_criteriorateio);
       $this->rh86_rhlocaltrab = ($this->rh86_rhlocaltrab == ""?@$GLOBALS["HTTP_POST_VARS"]["rh86_rhlocaltrab"]:$this->rh86_rhlocaltrab);
       $this->rh86_instit = ($this->rh86_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["rh86_instit"]:$this->rh86_instit);
     }else{
       $this->rh86_sequencial = ($this->rh86_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh86_sequencial"]:$this->rh86_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($rh86_sequencial){ 
      $this->atualizacampos();
     if($this->rh86_criteriorateio == null ){ 
       $this->erro_sql = " Campo Criterio Rateio nao Informado.";
       $this->erro_campo = "rh86_criteriorateio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh86_rhlocaltrab == null ){ 
       $this->erro_sql = " Campo Local de Trabalho nao Informado.";
       $this->erro_campo = "rh86_rhlocaltrab";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh86_instit == null ){ 
       $this->erro_sql = " Campo Instituicao nao Informado.";
       $this->erro_campo = "rh86_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh86_sequencial == "" || $rh86_sequencial == null ){
       $result = db_query("select nextval('rhlocaltrabcustoplano_rh86_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhlocaltrabcustoplano_rh86_sequencial_seq do campo: rh86_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh86_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhlocaltrabcustoplano_rh86_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh86_sequencial)){
         $this->erro_sql = " Campo rh86_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh86_sequencial = $rh86_sequencial; 
       }
     }
     if(($this->rh86_sequencial == null) || ($this->rh86_sequencial == "") ){ 
       $this->erro_sql = " Campo rh86_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhlocaltrabcustoplano(
                                       rh86_sequencial 
                                      ,rh86_criteriorateio 
                                      ,rh86_rhlocaltrab 
                                      ,rh86_instit 
                       )
                values (
                                $this->rh86_sequencial 
                               ,$this->rh86_criteriorateio 
                               ,$this->rh86_rhlocaltrab 
                               ,$this->rh86_instit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Centro de Custo dos Locais de Trabalho  ($this->rh86_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Centro de Custo dos Locais de Trabalho  já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Centro de Custo dos Locais de Trabalho  ($this->rh86_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh86_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->rh86_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15043,'$this->rh86_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2646,15043,'','".AddSlashes(pg_result($resaco,0,'rh86_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2646,15045,'','".AddSlashes(pg_result($resaco,0,'rh86_criteriorateio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2646,15044,'','".AddSlashes(pg_result($resaco,0,'rh86_rhlocaltrab'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2646,15052,'','".AddSlashes(pg_result($resaco,0,'rh86_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh86_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhlocaltrabcustoplano set ";
     $virgula = "";
     if(trim($this->rh86_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh86_sequencial"])){ 
       $sql  .= $virgula." rh86_sequencial = $this->rh86_sequencial ";
       $virgula = ",";
       if(trim($this->rh86_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "rh86_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh86_criteriorateio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh86_criteriorateio"])){ 
       $sql  .= $virgula." rh86_criteriorateio = $this->rh86_criteriorateio ";
       $virgula = ",";
       if(trim($this->rh86_criteriorateio) == null ){ 
         $this->erro_sql = " Campo Criterio Rateio nao Informado.";
         $this->erro_campo = "rh86_criteriorateio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh86_rhlocaltrab)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh86_rhlocaltrab"])){ 
       $sql  .= $virgula." rh86_rhlocaltrab = $this->rh86_rhlocaltrab ";
       $virgula = ",";
       if(trim($this->rh86_rhlocaltrab) == null ){ 
         $this->erro_sql = " Campo Local de Trabalho nao Informado.";
         $this->erro_campo = "rh86_rhlocaltrab";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh86_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh86_instit"])){ 
       $sql  .= $virgula." rh86_instit = $this->rh86_instit ";
       $virgula = ",";
       if(trim($this->rh86_instit) == null ){ 
         $this->erro_sql = " Campo Instituicao nao Informado.";
         $this->erro_campo = "rh86_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh86_sequencial!=null){
       $sql .= " rh86_sequencial = $this->rh86_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->rh86_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15043,'$this->rh86_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh86_sequencial"]) || $this->rh86_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2646,15043,'".AddSlashes(pg_result($resaco,$conresaco,'rh86_sequencial'))."','$this->rh86_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh86_criteriorateio"]) || $this->rh86_criteriorateio != "")
           $resac = db_query("insert into db_acount values($acount,2646,15045,'".AddSlashes(pg_result($resaco,$conresaco,'rh86_criteriorateio'))."','$this->rh86_criteriorateio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh86_rhlocaltrab"]) || $this->rh86_rhlocaltrab != "")
           $resac = db_query("insert into db_acount values($acount,2646,15044,'".AddSlashes(pg_result($resaco,$conresaco,'rh86_rhlocaltrab'))."','$this->rh86_rhlocaltrab',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh86_instit"]) || $this->rh86_instit != "")
           $resac = db_query("insert into db_acount values($acount,2646,15052,'".AddSlashes(pg_result($resaco,$conresaco,'rh86_instit'))."','$this->rh86_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Centro de Custo dos Locais de Trabalho  nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh86_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Centro de Custo dos Locais de Trabalho  nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh86_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh86_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh86_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($rh86_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15043,'$rh86_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2646,15043,'','".AddSlashes(pg_result($resaco,$iresaco,'rh86_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2646,15045,'','".AddSlashes(pg_result($resaco,$iresaco,'rh86_criteriorateio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2646,15044,'','".AddSlashes(pg_result($resaco,$iresaco,'rh86_rhlocaltrab'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2646,15052,'','".AddSlashes(pg_result($resaco,$iresaco,'rh86_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rhlocaltrabcustoplano
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh86_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh86_sequencial = $rh86_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Centro de Custo dos Locais de Trabalho  nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh86_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Centro de Custo dos Locais de Trabalho  nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh86_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh86_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhlocaltrabcustoplano";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $rh86_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhlocaltrabcustoplano ";
     $sql .= "      inner join rhlocaltrab  on  rhlocaltrab.rh55_codigo = rhlocaltrabcustoplano.rh86_rhlocaltrab and  rhlocaltrab.rh55_instit = rhlocaltrabcustoplano.rh86_instit";
     $sql .= "      inner join custocriteriorateio  on  custocriteriorateio.cc08_sequencial = rhlocaltrabcustoplano.rh86_criteriorateio";
     $sql .= "      inner join db_config  on  db_config.codigo = rhlocaltrab.rh55_instit";
     $sql .= "      inner join db_config  as a on   a.codigo = custocriteriorateio.cc08_instit";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = custocriteriorateio.cc08_coddepto";
     $sql .= "      inner join matunid  on  matunid.m61_codmatunid = custocriteriorateio.cc08_matunid";
     $sql2 = "";
     if($dbwhere==""){
       if($rh86_sequencial!=null ){
         $sql2 .= " where rhlocaltrabcustoplano.rh86_sequencial = $rh86_sequencial "; 
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
   function sql_query_file ( $rh86_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhlocaltrabcustoplano ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh86_sequencial!=null ){
         $sql2 .= " where rhlocaltrabcustoplano.rh86_sequencial = $rh86_sequencial "; 
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