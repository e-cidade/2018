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

//MODULO: issqn
//CLASSE DA ENTIDADE atividcnae
class cl_atividcnae { 
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
   var $q74_cnaeanalitica = 0; 
   var $q74_ativid = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q74_cnaeanalitica = int4 = Código 
                 q74_ativid = int4 = Atividade 
                 ";
   //funcao construtor da classe 
   function cl_atividcnae() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("atividcnae"); 
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
       $this->q74_cnaeanalitica = ($this->q74_cnaeanalitica == ""?@$GLOBALS["HTTP_POST_VARS"]["q74_cnaeanalitica"]:$this->q74_cnaeanalitica);
       $this->q74_ativid = ($this->q74_ativid == ""?@$GLOBALS["HTTP_POST_VARS"]["q74_ativid"]:$this->q74_ativid);
     }else{
       $this->q74_cnaeanalitica = ($this->q74_cnaeanalitica == ""?@$GLOBALS["HTTP_POST_VARS"]["q74_cnaeanalitica"]:$this->q74_cnaeanalitica);
       $this->q74_ativid = ($this->q74_ativid == ""?@$GLOBALS["HTTP_POST_VARS"]["q74_ativid"]:$this->q74_ativid);
     }
   }
   // funcao para inclusao
   function incluir ($q74_cnaeanalitica,$q74_ativid){ 
      $this->atualizacampos();
       $this->q74_cnaeanalitica = $q74_cnaeanalitica; 
       $this->q74_ativid = $q74_ativid; 
     if(($this->q74_cnaeanalitica == null) || ($this->q74_cnaeanalitica == "") ){ 
       $this->erro_sql = " Campo q74_cnaeanalitica nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->q74_ativid == null) || ($this->q74_ativid == "") ){ 
       $this->erro_sql = " Campo q74_ativid nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into atividcnae(
                                       q74_cnaeanalitica 
                                      ,q74_ativid 
                       )
                values (
                                $this->q74_cnaeanalitica 
                               ,$this->q74_ativid 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "atividcnae ($this->q74_cnaeanalitica."-".$this->q74_ativid) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "atividcnae já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "atividcnae ($this->q74_cnaeanalitica."-".$this->q74_ativid) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q74_cnaeanalitica."-".$this->q74_ativid;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q74_cnaeanalitica,$this->q74_ativid));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10191,'$this->q74_cnaeanalitica','I')");
       $resac = db_query("insert into db_acountkey values($acount,10192,'$this->q74_ativid','I')");
       $resac = db_query("insert into db_acount values($acount,1754,10191,'','".AddSlashes(pg_result($resaco,0,'q74_cnaeanalitica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1754,10192,'','".AddSlashes(pg_result($resaco,0,'q74_ativid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q74_cnaeanalitica=null,$q74_ativid=null) { 
      $this->atualizacampos();
     $sql = " update atividcnae set ";
     $virgula = "";
     if(trim($this->q74_cnaeanalitica)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q74_cnaeanalitica"])){ 
       $sql  .= $virgula." q74_cnaeanalitica = $this->q74_cnaeanalitica ";
       $virgula = ",";
       if(trim($this->q74_cnaeanalitica) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "q74_cnaeanalitica";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q74_ativid)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q74_ativid"])){ 
       $sql  .= $virgula." q74_ativid = $this->q74_ativid ";
       $virgula = ",";
       if(trim($this->q74_ativid) == null ){ 
         $this->erro_sql = " Campo Atividade nao Informado.";
         $this->erro_campo = "q74_ativid";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($q74_cnaeanalitica!=null){
       $sql .= " q74_cnaeanalitica = $this->q74_cnaeanalitica";
     }
     if($q74_ativid!=null){
       $sql .= " and  q74_ativid = $this->q74_ativid";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q74_cnaeanalitica,$this->q74_ativid));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10191,'$this->q74_cnaeanalitica','A')");
         $resac = db_query("insert into db_acountkey values($acount,10192,'$this->q74_ativid','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q74_cnaeanalitica"]))
           $resac = db_query("insert into db_acount values($acount,1754,10191,'".AddSlashes(pg_result($resaco,$conresaco,'q74_cnaeanalitica'))."','$this->q74_cnaeanalitica',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q74_ativid"]))
           $resac = db_query("insert into db_acount values($acount,1754,10192,'".AddSlashes(pg_result($resaco,$conresaco,'q74_ativid'))."','$this->q74_ativid',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "atividcnae nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q74_cnaeanalitica."-".$this->q74_ativid;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "atividcnae nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q74_cnaeanalitica."-".$this->q74_ativid;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q74_cnaeanalitica."-".$this->q74_ativid;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q74_cnaeanalitica=null,$q74_ativid=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q74_cnaeanalitica,$q74_ativid));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10191,'$q74_cnaeanalitica','E')");
         $resac = db_query("insert into db_acountkey values($acount,10192,'$q74_ativid','E')");
         $resac = db_query("insert into db_acount values($acount,1754,10191,'','".AddSlashes(pg_result($resaco,$iresaco,'q74_cnaeanalitica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1754,10192,'','".AddSlashes(pg_result($resaco,$iresaco,'q74_ativid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from atividcnae
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q74_cnaeanalitica != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q74_cnaeanalitica = $q74_cnaeanalitica ";
        }
        if($q74_ativid != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q74_ativid = $q74_ativid ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "atividcnae nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q74_cnaeanalitica."-".$q74_ativid;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "atividcnae nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q74_cnaeanalitica."-".$q74_ativid;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q74_cnaeanalitica."-".$q74_ativid;
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
        $this->erro_sql   = "Record Vazio na Tabela:atividcnae";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $q74_cnaeanalitica=null,$q74_ativid=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from atividcnae ";
     $sql .= "      inner join ativid  on  ativid.q03_ativ = atividcnae.q74_ativid";
     $sql .= "      inner join cnaeanalitica  on  cnaeanalitica.q72_sequencial = atividcnae.q74_cnaeanalitica";
     $sql .= "      inner join cnae  as a on   a.q71_sequencial = cnaeanalitica.q72_cnae";
     $sql2 = "";
     if($dbwhere==""){
       if($q74_cnaeanalitica!=null ){
         $sql2 .= " where atividcnae.q74_cnaeanalitica = $q74_cnaeanalitica "; 
       } 
       if($q74_ativid!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " atividcnae.q74_ativid = $q74_ativid "; 
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
   function sql_query_file ( $q74_cnaeanalitica=null,$q74_ativid=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from atividcnae ";
     $sql2 = "";
     if($dbwhere==""){
       if($q74_cnaeanalitica!=null ){
         $sql2 .= " where atividcnae.q74_cnaeanalitica = $q74_cnaeanalitica "; 
       } 
       if($q74_ativid!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " atividcnae.q74_ativid = $q74_ativid "; 
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