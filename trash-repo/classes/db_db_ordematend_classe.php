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
//CLASSE DA ENTIDADE db_ordematend
class cl_db_ordematend { 
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
   var $or10_codordem = 0; 
   var $or10_codatend = 0; 
   var $or10_seq = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 or10_codordem = int4 = Código 
                 or10_codatend = int4 = Código de atendimento 
                 or10_seq = int4 = Sequência 
                 ";
   //funcao construtor da classe 
   function cl_db_ordematend() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_ordematend"); 
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
       $this->or10_codordem = ($this->or10_codordem == ""?@$GLOBALS["HTTP_POST_VARS"]["or10_codordem"]:$this->or10_codordem);
       $this->or10_codatend = ($this->or10_codatend == ""?@$GLOBALS["HTTP_POST_VARS"]["or10_codatend"]:$this->or10_codatend);
       $this->or10_seq = ($this->or10_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["or10_seq"]:$this->or10_seq);
     }else{
       $this->or10_codordem = ($this->or10_codordem == ""?@$GLOBALS["HTTP_POST_VARS"]["or10_codordem"]:$this->or10_codordem);
       $this->or10_codatend = ($this->or10_codatend == ""?@$GLOBALS["HTTP_POST_VARS"]["or10_codatend"]:$this->or10_codatend);
       $this->or10_seq = ($this->or10_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["or10_seq"]:$this->or10_seq);
     }
   }
   // funcao para inclusao
   function incluir ($or10_codordem,$or10_codatend,$or10_seq){ 
      $this->atualizacampos();
       $this->or10_codordem = $or10_codordem; 
       $this->or10_codatend = $or10_codatend; 
       $this->or10_seq = $or10_seq; 
     if(($this->or10_codordem == null) || ($this->or10_codordem == "") ){ 
       $this->erro_sql = " Campo or10_codordem nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->or10_codatend == null) || ($this->or10_codatend == "") ){ 
       $this->erro_sql = " Campo or10_codatend nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->or10_seq == null) || ($this->or10_seq == "") ){ 
       $this->erro_sql = " Campo or10_seq nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_ordematend(
                                       or10_codordem 
                                      ,or10_codatend 
                                      ,or10_seq 
                       )
                values (
                                $this->or10_codordem 
                               ,$this->or10_codatend 
                               ,$this->or10_seq 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Tabela de ligação dos atendimentos com a ordem ($this->or10_codordem."-".$this->or10_codatend."-".$this->or10_seq) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Tabela de ligação dos atendimentos com a ordem já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Tabela de ligação dos atendimentos com a ordem ($this->or10_codordem."-".$this->or10_codatend."-".$this->or10_seq) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->or10_codordem."-".$this->or10_codatend."-".$this->or10_seq;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->or10_codordem,$this->or10_codatend,$this->or10_seq));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5096,'$this->or10_codordem','I')");
       $resac = db_query("insert into db_acountkey values($acount,5097,'$this->or10_codatend','I')");
       $resac = db_query("insert into db_acountkey values($acount,5098,'$this->or10_seq','I')");
       $resac = db_query("insert into db_acount values($acount,725,5096,'','".AddSlashes(pg_result($resaco,0,'or10_codordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,725,5097,'','".AddSlashes(pg_result($resaco,0,'or10_codatend'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,725,5098,'','".AddSlashes(pg_result($resaco,0,'or10_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($or10_codordem=null,$or10_codatend=null,$or10_seq=null) { 
      $this->atualizacampos();
     $sql = " update db_ordematend set ";
     $virgula = "";
     if(trim($this->or10_codordem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["or10_codordem"])){ 
       $sql  .= $virgula." or10_codordem = $this->or10_codordem ";
       $virgula = ",";
       if(trim($this->or10_codordem) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "or10_codordem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->or10_codatend)!="" || isset($GLOBALS["HTTP_POST_VARS"]["or10_codatend"])){ 
       $sql  .= $virgula." or10_codatend = $this->or10_codatend ";
       $virgula = ",";
       if(trim($this->or10_codatend) == null ){ 
         $this->erro_sql = " Campo Código de atendimento nao Informado.";
         $this->erro_campo = "or10_codatend";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->or10_seq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["or10_seq"])){ 
       $sql  .= $virgula." or10_seq = $this->or10_seq ";
       $virgula = ",";
       if(trim($this->or10_seq) == null ){ 
         $this->erro_sql = " Campo Sequência nao Informado.";
         $this->erro_campo = "or10_seq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($or10_codordem!=null){
       $sql .= " or10_codordem = $this->or10_codordem";
     }
     if($or10_codatend!=null){
       $sql .= " and  or10_codatend = $this->or10_codatend";
     }
     if($or10_seq!=null){
       $sql .= " and  or10_seq = $this->or10_seq";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->or10_codordem,$this->or10_codatend,$this->or10_seq));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5096,'$this->or10_codordem','A')");
         $resac = db_query("insert into db_acountkey values($acount,5097,'$this->or10_codatend','A')");
         $resac = db_query("insert into db_acountkey values($acount,5098,'$this->or10_seq','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["or10_codordem"]))
           $resac = db_query("insert into db_acount values($acount,725,5096,'".AddSlashes(pg_result($resaco,$conresaco,'or10_codordem'))."','$this->or10_codordem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["or10_codatend"]))
           $resac = db_query("insert into db_acount values($acount,725,5097,'".AddSlashes(pg_result($resaco,$conresaco,'or10_codatend'))."','$this->or10_codatend',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["or10_seq"]))
           $resac = db_query("insert into db_acount values($acount,725,5098,'".AddSlashes(pg_result($resaco,$conresaco,'or10_seq'))."','$this->or10_seq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tabela de ligação dos atendimentos com a ordem nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->or10_codordem."-".$this->or10_codatend."-".$this->or10_seq;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tabela de ligação dos atendimentos com a ordem nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->or10_codordem."-".$this->or10_codatend."-".$this->or10_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->or10_codordem."-".$this->or10_codatend."-".$this->or10_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($or10_codordem=null,$or10_codatend=null,$or10_seq=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($or10_codordem,$or10_codatend,$or10_seq));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5096,'$or10_codordem','E')");
         $resac = db_query("insert into db_acountkey values($acount,5097,'$or10_codatend','E')");
         $resac = db_query("insert into db_acountkey values($acount,5098,'$or10_seq','E')");
         $resac = db_query("insert into db_acount values($acount,725,5096,'','".AddSlashes(pg_result($resaco,$iresaco,'or10_codordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,725,5097,'','".AddSlashes(pg_result($resaco,$iresaco,'or10_codatend'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,725,5098,'','".AddSlashes(pg_result($resaco,$iresaco,'or10_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_ordematend
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($or10_codordem != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " or10_codordem = $or10_codordem ";
        }
        if($or10_codatend != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " or10_codatend = $or10_codatend ";
        }
        if($or10_seq != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " or10_seq = $or10_seq ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tabela de ligação dos atendimentos com a ordem nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$or10_codordem."-".$or10_codatend."-".$or10_seq;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tabela de ligação dos atendimentos com a ordem nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$or10_codordem."-".$or10_codatend."-".$or10_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$or10_codordem."-".$or10_codatend."-".$or10_seq;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_ordematend";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $or10_codordem=null,$or10_codatend=null,$or10_seq=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_ordematend ";
     $sql .= "      inner join db_ordem  on  db_ordem.codordem = db_ordematend.or10_codordem";
     $sql .= "      inner join atenditem  on  atenditem.at05_seq = db_ordematend.or10_seq and  atenditem.at05_codatend = db_ordematend.or10_codatend";
     $sql .= "      inner join atendimento  on  atendimento.at02_codatend = atenditem.at05_codatend";
     $sql .= "      inner join atendimento  as a on   a.at02_codatend = atenditem.at05_codatend";
     $sql2 = "";
     if($dbwhere==""){
       if($or10_codordem!=null ){
         $sql2 .= " where db_ordematend.or10_codordem = $or10_codordem "; 
       } 
       if($or10_codatend!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " db_ordematend.or10_codatend = $or10_codatend "; 
       } 
       if($or10_seq!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " db_ordematend.or10_seq = $or10_seq "; 
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
   function sql_query_file ( $or10_codordem=null,$or10_codatend=null,$or10_seq=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_ordematend ";
     $sql2 = "";
     if($dbwhere==""){
       if($or10_codordem!=null ){
         $sql2 .= " where db_ordematend.or10_codordem = $or10_codordem "; 
       } 
       if($or10_codatend!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " db_ordematend.or10_codatend = $or10_codatend "; 
       } 
       if($or10_seq!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " db_ordematend.or10_seq = $or10_seq "; 
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