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
//CLASSE DA ENTIDADE atividcbo
class cl_atividcbo { 
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
   var $q75_ativid = 0; 
   var $q75_rhcbo = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q75_ativid = int4 = Atividade 
                 q75_rhcbo = int4 = Código CBO 
                 ";
   //funcao construtor da classe 
   function cl_atividcbo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("atividcbo"); 
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
       $this->q75_ativid = ($this->q75_ativid == ""?@$GLOBALS["HTTP_POST_VARS"]["q75_ativid"]:$this->q75_ativid);
       $this->q75_rhcbo = ($this->q75_rhcbo == ""?@$GLOBALS["HTTP_POST_VARS"]["q75_rhcbo"]:$this->q75_rhcbo);
     }else{
       $this->q75_ativid = ($this->q75_ativid == ""?@$GLOBALS["HTTP_POST_VARS"]["q75_ativid"]:$this->q75_ativid);
       $this->q75_rhcbo = ($this->q75_rhcbo == ""?@$GLOBALS["HTTP_POST_VARS"]["q75_rhcbo"]:$this->q75_rhcbo);
     }
   }
   // funcao para inclusao
   function incluir ($q75_ativid,$q75_rhcbo){ 
      $this->atualizacampos();
       $this->q75_ativid = $q75_ativid; 
       $this->q75_rhcbo = $q75_rhcbo; 
     if(($this->q75_ativid == null) || ($this->q75_ativid == "") ){ 
       $this->erro_sql = " Campo q75_ativid nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->q75_rhcbo == null) || ($this->q75_rhcbo == "") ){ 
       $this->erro_sql = " Campo q75_rhcbo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into atividcbo(
                                       q75_ativid 
                                      ,q75_rhcbo 
                       )
                values (
                                $this->q75_ativid 
                               ,$this->q75_rhcbo 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "atividcbo ($this->q75_ativid."-".$this->q75_rhcbo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "atividcbo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "atividcbo ($this->q75_ativid."-".$this->q75_rhcbo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q75_ativid."-".$this->q75_rhcbo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q75_ativid,$this->q75_rhcbo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10193,'$this->q75_ativid','I')");
       $resac = db_query("insert into db_acountkey values($acount,10194,'$this->q75_rhcbo','I')");
       $resac = db_query("insert into db_acount values($acount,1755,10193,'','".AddSlashes(pg_result($resaco,0,'q75_ativid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1755,10194,'','".AddSlashes(pg_result($resaco,0,'q75_rhcbo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q75_ativid=null,$q75_rhcbo=null) { 
      $this->atualizacampos();
     $sql = " update atividcbo set ";
     $virgula = "";
     if(trim($this->q75_ativid)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q75_ativid"])){ 
       $sql  .= $virgula." q75_ativid = $this->q75_ativid ";
       $virgula = ",";
       if(trim($this->q75_ativid) == null ){ 
         $this->erro_sql = " Campo Atividade nao Informado.";
         $this->erro_campo = "q75_ativid";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q75_rhcbo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q75_rhcbo"])){ 
       $sql  .= $virgula." q75_rhcbo = $this->q75_rhcbo ";
       $virgula = ",";
       if(trim($this->q75_rhcbo) == null ){ 
         $this->erro_sql = " Campo Código CBO nao Informado.";
         $this->erro_campo = "q75_rhcbo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($q75_ativid!=null){
       $sql .= " q75_ativid = $this->q75_ativid";
     }
     if($q75_rhcbo!=null){
       $sql .= " and  q75_rhcbo = $this->q75_rhcbo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q75_ativid,$this->q75_rhcbo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10193,'$this->q75_ativid','A')");
         $resac = db_query("insert into db_acountkey values($acount,10194,'$this->q75_rhcbo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q75_ativid"]))
           $resac = db_query("insert into db_acount values($acount,1755,10193,'".AddSlashes(pg_result($resaco,$conresaco,'q75_ativid'))."','$this->q75_ativid',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q75_rhcbo"]))
           $resac = db_query("insert into db_acount values($acount,1755,10194,'".AddSlashes(pg_result($resaco,$conresaco,'q75_rhcbo'))."','$this->q75_rhcbo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "atividcbo nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q75_ativid."-".$this->q75_rhcbo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "atividcbo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q75_ativid."-".$this->q75_rhcbo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q75_ativid."-".$this->q75_rhcbo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q75_ativid=null,$q75_rhcbo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q75_ativid,$q75_rhcbo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10193,'$q75_ativid','E')");
         $resac = db_query("insert into db_acountkey values($acount,10194,'$q75_rhcbo','E')");
         $resac = db_query("insert into db_acount values($acount,1755,10193,'','".AddSlashes(pg_result($resaco,$iresaco,'q75_ativid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1755,10194,'','".AddSlashes(pg_result($resaco,$iresaco,'q75_rhcbo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from atividcbo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q75_ativid != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q75_ativid = $q75_ativid ";
        }
        if($q75_rhcbo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q75_rhcbo = $q75_rhcbo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "atividcbo nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q75_ativid."-".$q75_rhcbo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "atividcbo nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q75_ativid."-".$q75_rhcbo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q75_ativid."-".$q75_rhcbo;
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
        $this->erro_sql   = "Record Vazio na Tabela:atividcbo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $q75_ativid=null,$q75_rhcbo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from atividcbo ";
     $sql .= "      inner join ativid  on  ativid.q03_ativ = atividcbo.q75_ativid";
     $sql .= "      inner join rhcbo  on  rhcbo.rh70_sequencial = atividcbo.q75_rhcbo";
     $sql2 = "";
     if($dbwhere==""){
       if($q75_ativid!=null ){
         $sql2 .= " where atividcbo.q75_ativid = $q75_ativid "; 
       } 
       if($q75_rhcbo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " atividcbo.q75_rhcbo = $q75_rhcbo "; 
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
   function sql_query_file ( $q75_ativid=null,$q75_rhcbo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from atividcbo ";
     $sql2 = "";
     if($dbwhere==""){
       if($q75_ativid!=null ){
         $sql2 .= " where atividcbo.q75_ativid = $q75_ativid "; 
       } 
       if($q75_rhcbo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " atividcbo.q75_rhcbo = $q75_rhcbo "; 
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