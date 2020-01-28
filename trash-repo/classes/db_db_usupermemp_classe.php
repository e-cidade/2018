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
//CLASSE DA ENTIDADE db_usupermemp
class cl_db_usupermemp { 
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
   var $db21_codperm = 0; 
   var $db21_id_usuario = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db21_codperm = int4 = C�digo Permiss�o 
                 db21_id_usuario = int4 = Cod. Usu�rio 
                 ";
   //funcao construtor da classe 
   function cl_db_usupermemp() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_usupermemp"); 
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
       $this->db21_codperm = ($this->db21_codperm == ""?@$GLOBALS["HTTP_POST_VARS"]["db21_codperm"]:$this->db21_codperm);
       $this->db21_id_usuario = ($this->db21_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["db21_id_usuario"]:$this->db21_id_usuario);
     }else{
       $this->db21_codperm = ($this->db21_codperm == ""?@$GLOBALS["HTTP_POST_VARS"]["db21_codperm"]:$this->db21_codperm);
       $this->db21_id_usuario = ($this->db21_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["db21_id_usuario"]:$this->db21_id_usuario);
     }
   }
   // funcao para inclusao
   function incluir ($db21_codperm,$db21_id_usuario){ 
      $this->atualizacampos();
       $this->db21_codperm = $db21_codperm; 
       $this->db21_id_usuario = $db21_id_usuario; 
     if(($this->db21_codperm == null) || ($this->db21_codperm == "") ){ 
       $this->erro_sql = " Campo db21_codperm nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->db21_id_usuario == null) || ($this->db21_id_usuario == "") ){ 
       $this->erro_sql = " Campo db21_id_usuario nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_usupermemp(
                                       db21_codperm 
                                      ,db21_id_usuario 
                       )
                values (
                                $this->db21_codperm 
                               ,$this->db21_id_usuario 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Usu�rio e permissao ($this->db21_codperm."-".$this->db21_id_usuario) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Usu�rio e permissao j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Usu�rio e permissao ($this->db21_codperm."-".$this->db21_id_usuario) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db21_codperm."-".$this->db21_id_usuario;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->db21_codperm,$this->db21_id_usuario));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5578,'$this->db21_codperm','I')");
       $resac = db_query("insert into db_acountkey values($acount,5579,'$this->db21_id_usuario','I')");
       $resac = db_query("insert into db_acount values($acount,884,5578,'','".AddSlashes(pg_result($resaco,0,'db21_codperm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,884,5579,'','".AddSlashes(pg_result($resaco,0,'db21_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($db21_codperm=null,$db21_id_usuario=null) { 
      $this->atualizacampos();
     $sql = " update db_usupermemp set ";
     $virgula = "";
     if(trim($this->db21_codperm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db21_codperm"])){ 
       $sql  .= $virgula." db21_codperm = $this->db21_codperm ";
       $virgula = ",";
       if(trim($this->db21_codperm) == null ){ 
         $this->erro_sql = " Campo C�digo Permiss�o nao Informado.";
         $this->erro_campo = "db21_codperm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db21_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db21_id_usuario"])){ 
       $sql  .= $virgula." db21_id_usuario = $this->db21_id_usuario ";
       $virgula = ",";
       if(trim($this->db21_id_usuario) == null ){ 
         $this->erro_sql = " Campo Cod. Usu�rio nao Informado.";
         $this->erro_campo = "db21_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($db21_codperm!=null){
       $sql .= " db21_codperm = $this->db21_codperm";
     }
     if($db21_id_usuario!=null){
       $sql .= " and  db21_id_usuario = $this->db21_id_usuario";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->db21_codperm,$this->db21_id_usuario));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5578,'$this->db21_codperm','A')");
         $resac = db_query("insert into db_acountkey values($acount,5579,'$this->db21_id_usuario','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db21_codperm"]))
           $resac = db_query("insert into db_acount values($acount,884,5578,'".AddSlashes(pg_result($resaco,$conresaco,'db21_codperm'))."','$this->db21_codperm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db21_id_usuario"]))
           $resac = db_query("insert into db_acount values($acount,884,5579,'".AddSlashes(pg_result($resaco,$conresaco,'db21_id_usuario'))."','$this->db21_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Usu�rio e permissao nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db21_codperm."-".$this->db21_id_usuario;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Usu�rio e permissao nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db21_codperm."-".$this->db21_id_usuario;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db21_codperm."-".$this->db21_id_usuario;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($db21_codperm=null,$db21_id_usuario=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($db21_codperm,$db21_id_usuario));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5578,'$db21_codperm','E')");
         $resac = db_query("insert into db_acountkey values($acount,5579,'$db21_id_usuario','E')");
         $resac = db_query("insert into db_acount values($acount,884,5578,'','".AddSlashes(pg_result($resaco,$iresaco,'db21_codperm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,884,5579,'','".AddSlashes(pg_result($resaco,$iresaco,'db21_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_usupermemp
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db21_codperm != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db21_codperm = $db21_codperm ";
        }
        if($db21_id_usuario != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db21_id_usuario = $db21_id_usuario ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Usu�rio e permissao nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db21_codperm."-".$db21_id_usuario;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Usu�rio e permissao nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db21_codperm."-".$db21_id_usuario;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db21_codperm."-".$db21_id_usuario;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:db_usupermemp";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $db21_codperm=null,$db21_id_usuario=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_usupermemp ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = db_usupermemp.db21_id_usuario";
     $sql .= "      inner join db_permemp  on  db_permemp.db20_codperm = db_usupermemp.db21_codperm";
     $sql2 = "";
     if($dbwhere==""){
       if($db21_codperm!=null ){
         $sql2 .= " where db_usupermemp.db21_codperm = $db21_codperm "; 
       } 
       if($db21_id_usuario!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " db_usupermemp.db21_id_usuario = $db21_id_usuario "; 
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
   function sql_query_file ( $db21_codperm=null,$db21_id_usuario=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_usupermemp ";
     $sql2 = "";
     if($dbwhere==""){
       if($db21_codperm!=null ){
         $sql2 .= " where db_usupermemp.db21_codperm = $db21_codperm "; 
       } 
       if($db21_id_usuario!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " db_usupermemp.db21_id_usuario = $db21_id_usuario "; 
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