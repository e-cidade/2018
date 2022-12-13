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

//MODULO: agua
//CLASSE DA ENTIDADE aguahidromarca
class cl_aguahidromarca { 
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
   var $x03_codmarca = 0; 
   var $x03_nomemarca = null; 
   var $x03_sigla = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 x03_codmarca = int4 = Codigo 
                 x03_nomemarca = varchar(50) = Marca 
                 x03_sigla = varchar(15) = Sigla 
                 ";
   //funcao construtor da classe 
   function cl_aguahidromarca() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("aguahidromarca"); 
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
       $this->x03_codmarca = ($this->x03_codmarca == ""?@$GLOBALS["HTTP_POST_VARS"]["x03_codmarca"]:$this->x03_codmarca);
       $this->x03_nomemarca = ($this->x03_nomemarca == ""?@$GLOBALS["HTTP_POST_VARS"]["x03_nomemarca"]:$this->x03_nomemarca);
       $this->x03_sigla = ($this->x03_sigla == ""?@$GLOBALS["HTTP_POST_VARS"]["x03_sigla"]:$this->x03_sigla);
     }else{
       $this->x03_codmarca = ($this->x03_codmarca == ""?@$GLOBALS["HTTP_POST_VARS"]["x03_codmarca"]:$this->x03_codmarca);
     }
   }
   // funcao para inclusao
   function incluir ($x03_codmarca){ 
      $this->atualizacampos();
     if($this->x03_nomemarca == null ){ 
       $this->erro_sql = " Campo Marca nao Informado.";
       $this->erro_campo = "x03_nomemarca";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x03_sigla == null ){ 
       $this->erro_sql = " Campo Sigla nao Informado.";
       $this->erro_campo = "x03_sigla";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($x03_codmarca == "" || $x03_codmarca == null ){
       $result = db_query("select nextval('aguahidro_x03_codmarca_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: aguahidro_x03_codmarca_seq do campo: x03_codmarca"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->x03_codmarca = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from aguahidro_x03_codmarca_seq");
       if(($result != false) && (pg_result($result,0,0) < $x03_codmarca)){
         $this->erro_sql = " Campo x03_codmarca maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->x03_codmarca = $x03_codmarca; 
       }
     }
     if(($this->x03_codmarca == null) || ($this->x03_codmarca == "") ){ 
       $this->erro_sql = " Campo x03_codmarca nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into aguahidromarca(
                                       x03_codmarca 
                                      ,x03_nomemarca 
                                      ,x03_sigla 
                       )
                values (
                                $this->x03_codmarca 
                               ,'$this->x03_nomemarca' 
                               ,'$this->x03_sigla' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "hidrometro ($this->x03_codmarca) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "hidrometro já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "hidrometro ($this->x03_codmarca) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->x03_codmarca;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->x03_codmarca));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8384,'$this->x03_codmarca','I')");
       $resac = db_query("insert into db_acount values($acount,1420,8384,'','".AddSlashes(pg_result($resaco,0,'x03_codmarca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1420,8385,'','".AddSlashes(pg_result($resaco,0,'x03_nomemarca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1420,8574,'','".AddSlashes(pg_result($resaco,0,'x03_sigla'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($x03_codmarca=null) { 
      $this->atualizacampos();
     $sql = " update aguahidromarca set ";
     $virgula = "";
     if(trim($this->x03_codmarca)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x03_codmarca"])){ 
       $sql  .= $virgula." x03_codmarca = $this->x03_codmarca ";
       $virgula = ",";
       if(trim($this->x03_codmarca) == null ){ 
         $this->erro_sql = " Campo Codigo nao Informado.";
         $this->erro_campo = "x03_codmarca";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x03_nomemarca)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x03_nomemarca"])){ 
       $sql  .= $virgula." x03_nomemarca = '$this->x03_nomemarca' ";
       $virgula = ",";
       if(trim($this->x03_nomemarca) == null ){ 
         $this->erro_sql = " Campo Marca nao Informado.";
         $this->erro_campo = "x03_nomemarca";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x03_sigla)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x03_sigla"])){ 
       $sql  .= $virgula." x03_sigla = '$this->x03_sigla' ";
       $virgula = ",";
       if(trim($this->x03_sigla) == null ){ 
         $this->erro_sql = " Campo Sigla nao Informado.";
         $this->erro_campo = "x03_sigla";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($x03_codmarca!=null){
       $sql .= " x03_codmarca = $this->x03_codmarca";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->x03_codmarca));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8384,'$this->x03_codmarca','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x03_codmarca"]))
           $resac = db_query("insert into db_acount values($acount,1420,8384,'".AddSlashes(pg_result($resaco,$conresaco,'x03_codmarca'))."','$this->x03_codmarca',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x03_nomemarca"]))
           $resac = db_query("insert into db_acount values($acount,1420,8385,'".AddSlashes(pg_result($resaco,$conresaco,'x03_nomemarca'))."','$this->x03_nomemarca',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x03_sigla"]))
           $resac = db_query("insert into db_acount values($acount,1420,8574,'".AddSlashes(pg_result($resaco,$conresaco,'x03_sigla'))."','$this->x03_sigla',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "hidrometro nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->x03_codmarca;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "hidrometro nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->x03_codmarca;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->x03_codmarca;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($x03_codmarca=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($x03_codmarca));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8384,'$x03_codmarca','E')");
         $resac = db_query("insert into db_acount values($acount,1420,8384,'','".AddSlashes(pg_result($resaco,$iresaco,'x03_codmarca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1420,8385,'','".AddSlashes(pg_result($resaco,$iresaco,'x03_nomemarca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1420,8574,'','".AddSlashes(pg_result($resaco,$iresaco,'x03_sigla'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from aguahidromarca
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($x03_codmarca != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " x03_codmarca = $x03_codmarca ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "hidrometro nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$x03_codmarca;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "hidrometro nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$x03_codmarca;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$x03_codmarca;
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
        $this->erro_sql   = "Record Vazio na Tabela:aguahidromarca";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $x03_codmarca=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from aguahidromarca ";
     $sql2 = "";
     if($dbwhere==""){
       if($x03_codmarca!=null ){
         $sql2 .= " where aguahidromarca.x03_codmarca = $x03_codmarca "; 
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
   function sql_query_file ( $x03_codmarca=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from aguahidromarca ";
     $sql2 = "";
     if($dbwhere==""){
       if($x03_codmarca!=null ){
         $sql2 .= " where aguahidromarca.x03_codmarca = $x03_codmarca "; 
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