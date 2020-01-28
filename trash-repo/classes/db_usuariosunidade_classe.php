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

//MODULO: saude
//CLASSE DA ENTIDADE usuariosunidade
class cl_usuariosunidade { 
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
   var $sd25_i_usuario = 0; 
   var $sd25_i_unidade = 0; 
   var $sd25_b_ativo = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 sd25_i_usuario = int4 = Usuário 
                 sd25_i_unidade = int4 = Unidade 
                 sd25_b_ativo = bool = Ativo 
                 ";
   //funcao construtor da classe 
   function cl_usuariosunidade() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("usuariosunidade"); 
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
       $this->sd25_i_usuario = ($this->sd25_i_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["sd25_i_usuario"]:$this->sd25_i_usuario);
       $this->sd25_i_unidade = ($this->sd25_i_unidade == ""?@$GLOBALS["HTTP_POST_VARS"]["sd25_i_unidade"]:$this->sd25_i_unidade);
       $this->sd25_b_ativo = ($this->sd25_b_ativo == "f"?@$GLOBALS["HTTP_POST_VARS"]["sd25_b_ativo"]:$this->sd25_b_ativo);
     }else{
       $this->sd25_i_usuario = ($this->sd25_i_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["sd25_i_usuario"]:$this->sd25_i_usuario);
       $this->sd25_i_unidade = ($this->sd25_i_unidade == ""?@$GLOBALS["HTTP_POST_VARS"]["sd25_i_unidade"]:$this->sd25_i_unidade);
     }
   }
   // funcao para inclusao
   function incluir ($sd25_i_usuario,$sd25_i_unidade){ 
      $this->atualizacampos();
     if($this->sd25_b_ativo == null ){ 
       $this->erro_sql = " Campo Ativo nao Informado.";
       $this->erro_campo = "sd25_b_ativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->sd25_i_usuario = $sd25_i_usuario; 
       $this->sd25_i_unidade = $sd25_i_unidade; 
     if(($this->sd25_i_usuario == null) || ($this->sd25_i_usuario == "") ){ 
       $this->erro_sql = " Campo sd25_i_usuario nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->sd25_i_unidade == null) || ($this->sd25_i_unidade == "") ){ 
       $this->erro_sql = " Campo sd25_i_unidade nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into usuariosunidade(
                                       sd25_i_usuario 
                                      ,sd25_i_unidade 
                                      ,sd25_b_ativo 
                       )
                values (
                                $this->sd25_i_usuario 
                               ,$this->sd25_i_unidade 
                               ,'$this->sd25_b_ativo' 
                      )";
     $result = @pg_exec($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Usuários para Unidade ($this->sd25_i_usuario."-".$this->sd25_i_unidade) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Usuários para Unidade já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Usuários para Unidade ($this->sd25_i_usuario."-".$this->sd25_i_unidade) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd25_i_usuario."-".$this->sd25_i_unidade;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->sd25_i_usuario,$this->sd25_i_unidade));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,100111,'$this->sd25_i_usuario','I')");
       $resac = pg_query("insert into db_acountkey values($acount,100110,'$this->sd25_i_unidade','I')");
       $resac = pg_query("insert into db_acount values($acount,100019,100111,'','".AddSlashes(pg_result($resaco,0,'sd25_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,100019,100110,'','".AddSlashes(pg_result($resaco,0,'sd25_i_unidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,100019,1000000,'','".AddSlashes(pg_result($resaco,0,'sd25_b_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($sd25_i_usuario=null,$sd25_i_unidade=null) { 
      $this->atualizacampos();
     $sql = " update usuariosunidade set ";
     $virgula = "";
     if(trim($this->sd25_i_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd25_i_usuario"])){ 
       $sql  .= $virgula." sd25_i_usuario = $this->sd25_i_usuario ";
       $virgula = ",";
       if(trim($this->sd25_i_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "sd25_i_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd25_i_unidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd25_i_unidade"])){ 
       $sql  .= $virgula." sd25_i_unidade = $this->sd25_i_unidade ";
       $virgula = ",";
       if(trim($this->sd25_i_unidade) == null ){ 
         $this->erro_sql = " Campo Unidade nao Informado.";
         $this->erro_campo = "sd25_i_unidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd25_b_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd25_b_ativo"])){ 
       $sql  .= $virgula." sd25_b_ativo = '$this->sd25_b_ativo' ";
       $virgula = ",";
       if(trim($this->sd25_b_ativo) == null ){ 
         $this->erro_sql = " Campo Ativo nao Informado.";
         $this->erro_campo = "sd25_b_ativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($sd25_i_usuario!=null){
       $sql .= " sd25_i_usuario = $this->sd25_i_usuario";
     }
     if($sd25_i_unidade!=null){
       $sql .= " and  sd25_i_unidade = $this->sd25_i_unidade";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->sd25_i_usuario,$this->sd25_i_unidade));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,100111,'$this->sd25_i_usuario','A')");
         $resac = pg_query("insert into db_acountkey values($acount,100110,'$this->sd25_i_unidade','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd25_i_usuario"]))
           $resac = pg_query("insert into db_acount values($acount,100019,100111,'".AddSlashes(pg_result($resaco,$conresaco,'sd25_i_usuario'))."','$this->sd25_i_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd25_i_unidade"]))
           $resac = pg_query("insert into db_acount values($acount,100019,100110,'".AddSlashes(pg_result($resaco,$conresaco,'sd25_i_unidade'))."','$this->sd25_i_unidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd25_b_ativo"]))
           $resac = pg_query("insert into db_acount values($acount,100019,1000000,'".AddSlashes(pg_result($resaco,$conresaco,'sd25_b_ativo'))."','$this->sd25_b_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Usuários para Unidade nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd25_i_usuario."-".$this->sd25_i_unidade;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Usuários para Unidade nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd25_i_usuario."-".$this->sd25_i_unidade;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd25_i_usuario."-".$this->sd25_i_unidade;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($sd25_i_usuario=null,$sd25_i_unidade=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($sd25_i_usuario,$sd25_i_unidade));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,100111,'$this->sd25_i_usuario','E')");
         $resac = pg_query("insert into db_acountkey values($acount,100110,'$this->sd25_i_unidade','E')");
         $resac = pg_query("insert into db_acount values($acount,100019,100111,'','".AddSlashes(pg_result($resaco,$iresaco,'sd25_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,100019,100110,'','".AddSlashes(pg_result($resaco,$iresaco,'sd25_i_unidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,100019,1000000,'','".AddSlashes(pg_result($resaco,$iresaco,'sd25_b_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from usuariosunidade
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($sd25_i_usuario != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " sd25_i_usuario = $sd25_i_usuario ";
        }
        if($sd25_i_unidade != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " sd25_i_unidade = $sd25_i_unidade ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Usuários para Unidade nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$sd25_i_usuario."-".$sd25_i_unidade;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Usuários para Unidade nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$sd25_i_usuario."-".$sd25_i_unidade;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$sd25_i_usuario."-".$sd25_i_unidade;
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
     $result = @pg_query($sql);
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
        $this->erro_sql   = "Record Vazio na Tabela:usuariosunidade";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $sd25_i_usuario=null,$sd25_i_unidade=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from usuariosunidade ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = usuariosunidade.sd25_i_usuario";
     $sql .= "      inner join unidades  on  unidades.sd02_i_codigo = usuariosunidade.sd25_i_unidade";
     $sql2 = "";
     if($dbwhere==""){
       if($sd25_i_usuario!=null ){
         $sql2 .= " where usuariosunidade.sd25_i_usuario = $sd25_i_usuario "; 
       } 
       if($sd25_i_unidade!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " usuariosunidade.sd25_i_unidade = $sd25_i_unidade "; 
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
   function sql_query_file ( $sd25_i_usuario=null,$sd25_i_unidade=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from usuariosunidade ";
     $sql2 = "";
     if($dbwhere==""){
       if($sd25_i_usuario!=null ){
         $sql2 .= " where usuariosunidade.sd25_i_usuario = $sd25_i_usuario "; 
       } 
       if($sd25_i_unidade!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " usuariosunidade.sd25_i_unidade = $sd25_i_unidade "; 
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