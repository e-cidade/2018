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
//CLASSE DA ENTIDADE db_menuacesso
class cl_db_menuacesso { 
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
   var $db06_m_codigo = 0; 
   var $db06_idtipo = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db06_m_codigo = int4 = Código 
                 db06_idtipo = int4 = Tipo Acesso 
                 ";
   //funcao construtor da classe 
   function cl_db_menuacesso() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_menuacesso"); 
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
       $this->db06_m_codigo = ($this->db06_m_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["db06_m_codigo"]:$this->db06_m_codigo);
       $this->db06_idtipo = ($this->db06_idtipo == ""?@$GLOBALS["HTTP_POST_VARS"]["db06_idtipo"]:$this->db06_idtipo);
     }else{
       $this->db06_m_codigo = ($this->db06_m_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["db06_m_codigo"]:$this->db06_m_codigo);
       $this->db06_idtipo = ($this->db06_idtipo == ""?@$GLOBALS["HTTP_POST_VARS"]["db06_idtipo"]:$this->db06_idtipo);
     }
   }
   // funcao para inclusao
   function incluir ($db06_m_codigo,$db06_idtipo){ 
      $this->atualizacampos();
       $this->db06_m_codigo = $db06_m_codigo; 
       $this->db06_idtipo = $db06_idtipo; 
     if(($this->db06_m_codigo == null) || ($this->db06_m_codigo == "") ){ 
       $this->erro_sql = " Campo db06_m_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->db06_idtipo == null) || ($this->db06_idtipo == "") ){ 
       $this->erro_sql = " Campo db06_idtipo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_menuacesso(
                                       db06_m_codigo 
                                      ,db06_idtipo 
                       )
                values (
                                $this->db06_m_codigo 
                               ,$this->db06_idtipo 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Item por tipo de acesso ($this->db06_m_codigo."-".$this->db06_idtipo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Item por tipo de acesso já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Item por tipo de acesso ($this->db06_m_codigo."-".$this->db06_idtipo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db06_m_codigo."-".$this->db06_idtipo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->db06_m_codigo,$this->db06_idtipo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,3602,'$this->db06_m_codigo','I')");
       $resac = db_query("insert into db_acountkey values($acount,3603,'$this->db06_idtipo','I')");
       $resac = db_query("insert into db_acount values($acount,521,3602,'','".AddSlashes(pg_result($resaco,0,'db06_m_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,521,3603,'','".AddSlashes(pg_result($resaco,0,'db06_idtipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($db06_m_codigo=null,$db06_idtipo=null) { 
      $this->atualizacampos();
     $sql = " update db_menuacesso set ";
     $virgula = "";
     if(trim($this->db06_m_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db06_m_codigo"])){ 
        if(trim($this->db06_m_codigo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["db06_m_codigo"])){ 
           $this->db06_m_codigo = "0" ; 
        } 
       $sql  .= $virgula." db06_m_codigo = $this->db06_m_codigo ";
       $virgula = ",";
       if(trim($this->db06_m_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "db06_m_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db06_idtipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db06_idtipo"])){ 
        if(trim($this->db06_idtipo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["db06_idtipo"])){ 
           $this->db06_idtipo = "0" ; 
        } 
       $sql  .= $virgula." db06_idtipo = $this->db06_idtipo ";
       $virgula = ",";
       if(trim($this->db06_idtipo) == null ){ 
         $this->erro_sql = " Campo Tipo Acesso nao Informado.";
         $this->erro_campo = "db06_idtipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($db06_m_codigo!=null){
       $sql .= " db06_m_codigo = $this->db06_m_codigo";
     }
     if($db06_idtipo!=null){
       $sql .= " and  db06_idtipo = $this->db06_idtipo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->db06_m_codigo,$this->db06_idtipo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3602,'$this->db06_m_codigo','A')");
         $resac = db_query("insert into db_acountkey values($acount,3603,'$this->db06_idtipo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db06_m_codigo"]))
           $resac = db_query("insert into db_acount values($acount,521,3602,'".AddSlashes(pg_result($resaco,$conresaco,'db06_m_codigo'))."','$this->db06_m_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db06_idtipo"]))
           $resac = db_query("insert into db_acount values($acount,521,3603,'".AddSlashes(pg_result($resaco,$conresaco,'db06_idtipo'))."','$this->db06_idtipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Item por tipo de acesso nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db06_m_codigo."-".$this->db06_idtipo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Item por tipo de acesso nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db06_m_codigo."-".$this->db06_idtipo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db06_m_codigo."-".$this->db06_idtipo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($db06_m_codigo=null,$db06_idtipo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($db06_m_codigo,$db06_idtipo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3602,'$db06_m_codigo','E')");
         $resac = db_query("insert into db_acountkey values($acount,3603,'$db06_idtipo','E')");
         $resac = db_query("insert into db_acount values($acount,521,3602,'','".AddSlashes(pg_result($resaco,$iresaco,'db06_m_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,521,3603,'','".AddSlashes(pg_result($resaco,$iresaco,'db06_idtipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_menuacesso
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db06_m_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db06_m_codigo = $db06_m_codigo ";
        }
        if($db06_idtipo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db06_idtipo = $db06_idtipo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Item por tipo de acesso nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db06_m_codigo."-".$db06_idtipo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Item por tipo de acesso nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db06_m_codigo."-".$db06_idtipo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db06_m_codigo."-".$db06_idtipo;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_menuacesso";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $db06_m_codigo=null,$db06_idtipo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_menuacesso ";
     $sql .= "      inner join db_menupref  on  db_menupref.m_codigo = db_menuacesso.db06_m_codigo";
     $sql .= "      inner join db_tipoacesso  on  db_tipoacesso.db05_idtipo = db_menuacesso.db06_idtipo";
     $sql2 = "";
     if($dbwhere==""){
       if($db06_m_codigo!=null ){
         $sql2 .= " where db_menuacesso.db06_m_codigo = $db06_m_codigo "; 
       } 
       if($db06_idtipo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " db_menuacesso.db06_idtipo = $db06_idtipo "; 
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
   function sql_query_file ( $db06_m_codigo=null,$db06_idtipo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_menuacesso ";
     $sql2 = "";
     if($dbwhere==""){
       if($db06_m_codigo!=null ){
         $sql2 .= " where db_menuacesso.db06_m_codigo = $db06_m_codigo "; 
       } 
       if($db06_idtipo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " db_menuacesso.db06_idtipo = $db06_idtipo "; 
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