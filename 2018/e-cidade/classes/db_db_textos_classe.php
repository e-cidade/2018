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

//MODULO: Configurações
//CLASSE DA ENTIDADE db_textos
class cl_db_textos { 
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
   var $id_instit = 0; 
   var $descrtexto = null; 
   var $conteudotexto = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 id_instit = int4 = Instituição 
                 descrtexto = varchar(30) = descricao do texto 
                 conteudotexto = text = conteudo do texto 
                 ";
   //funcao construtor da classe 
   function cl_db_textos() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_textos"); 
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
       $this->id_instit = ($this->id_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["id_instit"]:$this->id_instit);
       $this->descrtexto = ($this->descrtexto == ""?@$GLOBALS["HTTP_POST_VARS"]["descrtexto"]:$this->descrtexto);
       $this->conteudotexto = ($this->conteudotexto == ""?@$GLOBALS["HTTP_POST_VARS"]["conteudotexto"]:$this->conteudotexto);
     }else{
       $this->id_instit = ($this->id_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["id_instit"]:$this->id_instit);
       $this->descrtexto = ($this->descrtexto == ""?@$GLOBALS["HTTP_POST_VARS"]["descrtexto"]:$this->descrtexto);
     }
   }
   // funcao para inclusao
   function incluir ($id_instit,$descrtexto){ 
      $this->atualizacampos();
       $this->id_instit = $id_instit; 
       $this->descrtexto = $descrtexto; 
     if(($this->id_instit == null) || ($this->id_instit == "") ){ 
       $this->erro_sql = " Campo id_instit nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->descrtexto == null) || ($this->descrtexto == "") ){ 
       $this->erro_sql = " Campo descrtexto nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_textos(
                                       id_instit 
                                      ,descrtexto 
                                      ,conteudotexto 
                       )
                values (
                                $this->id_instit 
                               ,'$this->descrtexto' 
                               ,'$this->conteudotexto' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "textos utilizados em relatorios ($this->id_instit."-".$this->descrtexto) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "textos utilizados em relatorios já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "textos utilizados em relatorios ($this->id_instit."-".$this->descrtexto) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->id_instit."-".$this->descrtexto;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->id_instit,$this->descrtexto));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1020,'$this->id_instit','I')");
       $resac = db_query("insert into db_acountkey values($acount,2261,'$this->descrtexto','I')");
       $resac = db_query("insert into db_acount values($acount,357,1020,'','".AddSlashes(pg_result($resaco,0,'id_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,357,2261,'','".AddSlashes(pg_result($resaco,0,'descrtexto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,357,2262,'','".AddSlashes(pg_result($resaco,0,'conteudotexto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($id_instit=null,$descrtexto=null) { 
      $this->atualizacampos();
     $sql = " update db_textos set ";
     $virgula = "";
     if(trim($this->id_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["id_instit"])){ 
       $sql  .= $virgula." id_instit = $this->id_instit ";
       $virgula = ",";
       if(trim($this->id_instit) == null ){ 
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "id_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->descrtexto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["descrtexto"])){ 
       $sql  .= $virgula." descrtexto = '$this->descrtexto' ";
       $virgula = ",";
       if(trim($this->descrtexto) == null ){ 
         $this->erro_sql = " Campo descricao do texto nao Informado.";
         $this->erro_campo = "descrtexto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->conteudotexto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["conteudotexto"])){ 
       $sql  .= $virgula." conteudotexto = '$this->conteudotexto' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($id_instit!=null){
       $sql .= " id_instit = $this->id_instit";
     }
     if($descrtexto!=null){
       $sql .= " and  descrtexto = '$this->descrtexto'";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->id_instit,$this->descrtexto));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1020,'$this->id_instit','A')");
         $resac = db_query("insert into db_acountkey values($acount,2261,'$this->descrtexto','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["id_instit"]))
           $resac = db_query("insert into db_acount values($acount,357,1020,'".AddSlashes(pg_result($resaco,$conresaco,'id_instit'))."','$this->id_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["descrtexto"]))
           $resac = db_query("insert into db_acount values($acount,357,2261,'".AddSlashes(pg_result($resaco,$conresaco,'descrtexto'))."','$this->descrtexto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["conteudotexto"]))
           $resac = db_query("insert into db_acount values($acount,357,2262,'".AddSlashes(pg_result($resaco,$conresaco,'conteudotexto'))."','$this->conteudotexto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "textos utilizados em relatorios nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->id_instit."-".$this->descrtexto;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "textos utilizados em relatorios nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->id_instit."-".$this->descrtexto;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->id_instit."-".$this->descrtexto;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($id_instit=null,$descrtexto=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($id_instit,$descrtexto));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1020,'$id_instit','E')");
         $resac = db_query("insert into db_acountkey values($acount,2261,'$descrtexto','E')");
         $resac = db_query("insert into db_acount values($acount,357,1020,'','".AddSlashes(pg_result($resaco,$iresaco,'id_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,357,2261,'','".AddSlashes(pg_result($resaco,$iresaco,'descrtexto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,357,2262,'','".AddSlashes(pg_result($resaco,$iresaco,'conteudotexto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_textos
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($id_instit != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " id_instit = $id_instit ";
        }
        if($descrtexto != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " descrtexto = '$descrtexto' ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "textos utilizados em relatorios nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$id_instit."-".$descrtexto;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "textos utilizados em relatorios nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$id_instit."-".$descrtexto;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$id_instit."-".$descrtexto;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_textos";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $id_instit=null,$descrtexto=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_textos ";
     $sql .= "      inner join db_config  on  db_config.codigo = db_textos.id_instit";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($id_instit!=null ){
         $sql2 .= " where db_textos.id_instit = $id_instit "; 
       } 
       if($descrtexto!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " db_textos.descrtexto = '$descrtexto' "; 
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
   function sql_query_file ( $id_instit=null,$descrtexto=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_textos ";
     $sql2 = "";
     if($dbwhere==""){
       if($id_instit!=null ){
         $sql2 .= " where db_textos.id_instit = $id_instit "; 
       } 
       if($descrtexto!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " db_textos.descrtexto = '$descrtexto' "; 
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