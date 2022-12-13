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

//MODULO: fiscal
//CLASSE DA ENTIDADE fiscalusuario
class cl_fiscalusuario { 
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
   var $y38_codnoti = 0; 
   var $y38_id_usuario = 0; 
   var $y38_obs = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 y38_codnoti = int8 = Código da Notificação 
                 y38_id_usuario = int4 = Cod. Usuário 
                 y38_obs = text = Observação do fiscal 
                 ";
   //funcao construtor da classe 
   function cl_fiscalusuario() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("fiscalusuario"); 
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
       $this->y38_codnoti = ($this->y38_codnoti == ""?@$GLOBALS["HTTP_POST_VARS"]["y38_codnoti"]:$this->y38_codnoti);
       $this->y38_id_usuario = ($this->y38_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["y38_id_usuario"]:$this->y38_id_usuario);
       $this->y38_obs = ($this->y38_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["y38_obs"]:$this->y38_obs);
     }else{
       $this->y38_codnoti = ($this->y38_codnoti == ""?@$GLOBALS["HTTP_POST_VARS"]["y38_codnoti"]:$this->y38_codnoti);
       $this->y38_id_usuario = ($this->y38_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["y38_id_usuario"]:$this->y38_id_usuario);
     }
   }
   // funcao para inclusao
   function incluir ($y38_codnoti,$y38_id_usuario){ 
      $this->atualizacampos();
       $this->y38_codnoti = $y38_codnoti; 
       $this->y38_id_usuario = $y38_id_usuario; 
     if(($this->y38_codnoti == null) || ($this->y38_codnoti == "") ){ 
       $this->erro_sql = " Campo y38_codnoti nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->y38_id_usuario == null) || ($this->y38_id_usuario == "") ){ 
       $this->erro_sql = " Campo y38_id_usuario nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into fiscalusuario(
                                       y38_codnoti 
                                      ,y38_id_usuario 
                                      ,y38_obs 
                       )
                values (
                                $this->y38_codnoti 
                               ,$this->y38_id_usuario 
                               ,'$this->y38_obs' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "fiscalusuario ($this->y38_codnoti."-".$this->y38_id_usuario) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "fiscalusuario já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "fiscalusuario ($this->y38_codnoti."-".$this->y38_id_usuario) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y38_codnoti."-".$this->y38_id_usuario;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->y38_codnoti,$this->y38_id_usuario));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,4963,'$this->y38_codnoti','I')");
       $resac = db_query("insert into db_acountkey values($acount,4964,'$this->y38_id_usuario','I')");
       $resac = db_query("insert into db_acount values($acount,691,4963,'','".AddSlashes(pg_result($resaco,0,'y38_codnoti'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,691,4964,'','".AddSlashes(pg_result($resaco,0,'y38_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,691,4965,'','".AddSlashes(pg_result($resaco,0,'y38_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($y38_codnoti=null,$y38_id_usuario=null) { 
      $this->atualizacampos();
     $sql = " update fiscalusuario set ";
     $virgula = "";
     if(trim($this->y38_codnoti)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y38_codnoti"])){ 
       $sql  .= $virgula." y38_codnoti = $this->y38_codnoti ";
       $virgula = ",";
       if(trim($this->y38_codnoti) == null ){ 
         $this->erro_sql = " Campo Código da Notificação nao Informado.";
         $this->erro_campo = "y38_codnoti";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y38_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y38_id_usuario"])){ 
       $sql  .= $virgula." y38_id_usuario = $this->y38_id_usuario ";
       $virgula = ",";
       if(trim($this->y38_id_usuario) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "y38_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y38_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y38_obs"])){ 
       $sql  .= $virgula." y38_obs = '$this->y38_obs' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($y38_codnoti!=null){
       $sql .= " y38_codnoti = $this->y38_codnoti";
     }
     if($y38_id_usuario!=null){
       $sql .= " and  y38_id_usuario = $this->y38_id_usuario";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->y38_codnoti,$this->y38_id_usuario));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4963,'$this->y38_codnoti','A')");
         $resac = db_query("insert into db_acountkey values($acount,4964,'$this->y38_id_usuario','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y38_codnoti"]))
           $resac = db_query("insert into db_acount values($acount,691,4963,'".AddSlashes(pg_result($resaco,$conresaco,'y38_codnoti'))."','$this->y38_codnoti',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y38_id_usuario"]))
           $resac = db_query("insert into db_acount values($acount,691,4964,'".AddSlashes(pg_result($resaco,$conresaco,'y38_id_usuario'))."','$this->y38_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y38_obs"]))
           $resac = db_query("insert into db_acount values($acount,691,4965,'".AddSlashes(pg_result($resaco,$conresaco,'y38_obs'))."','$this->y38_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "fiscalusuario nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->y38_codnoti."-".$this->y38_id_usuario;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "fiscalusuario nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->y38_codnoti."-".$this->y38_id_usuario;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y38_codnoti."-".$this->y38_id_usuario;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($y38_codnoti=null,$y38_id_usuario=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($y38_codnoti,$y38_id_usuario));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4963,'$y38_codnoti','E')");
         $resac = db_query("insert into db_acountkey values($acount,4964,'$y38_id_usuario','E')");
         $resac = db_query("insert into db_acount values($acount,691,4963,'','".AddSlashes(pg_result($resaco,$iresaco,'y38_codnoti'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,691,4964,'','".AddSlashes(pg_result($resaco,$iresaco,'y38_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,691,4965,'','".AddSlashes(pg_result($resaco,$iresaco,'y38_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from fiscalusuario
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($y38_codnoti != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " y38_codnoti = $y38_codnoti ";
        }
        if($y38_id_usuario != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " y38_id_usuario = $y38_id_usuario ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "fiscalusuario nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$y38_codnoti."-".$y38_id_usuario;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "fiscalusuario nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$y38_codnoti."-".$y38_id_usuario;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$y38_codnoti."-".$y38_id_usuario;
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
        $this->erro_sql   = "Record Vazio na Tabela:fiscalusuario";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $y38_codnoti=null,$y38_id_usuario=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from fiscalusuario ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = fiscalusuario.y38_id_usuario";
     $sql .= "      inner join fiscal  on  fiscal.y30_codnoti = fiscalusuario.y38_codnoti";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = fiscal.y30_setor";
     $sql2 = "";
     if($dbwhere==""){
       if($y38_codnoti!=null ){
         $sql2 .= " where fiscalusuario.y38_codnoti = $y38_codnoti "; 
       } 
       if($y38_id_usuario!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " fiscalusuario.y38_id_usuario = $y38_id_usuario "; 
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
   function sql_query_file ( $y38_codnoti=null,$y38_id_usuario=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from fiscalusuario ";
     $sql2 = "";
     if($dbwhere==""){
       if($y38_codnoti!=null ){
         $sql2 .= " where fiscalusuario.y38_codnoti = $y38_codnoti "; 
       } 
       if($y38_id_usuario!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " fiscalusuario.y38_id_usuario = $y38_id_usuario "; 
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