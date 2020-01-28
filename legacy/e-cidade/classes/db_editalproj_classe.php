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

//MODULO: contrib
//CLASSE DA ENTIDADE editalproj
class cl_editalproj { 
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
   var $d10_codedi = 0; 
   var $d10_codigo = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 d10_codedi = int4 = Codigo Edital 
                 d10_codigo = int4 = Código da lista de projeto 
                 ";
   //funcao construtor da classe 
   function cl_editalproj() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("editalproj"); 
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
       $this->d10_codedi = ($this->d10_codedi == ""?@$GLOBALS["HTTP_POST_VARS"]["d10_codedi"]:$this->d10_codedi);
       $this->d10_codigo = ($this->d10_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["d10_codigo"]:$this->d10_codigo);
     }else{
       $this->d10_codedi = ($this->d10_codedi == ""?@$GLOBALS["HTTP_POST_VARS"]["d10_codedi"]:$this->d10_codedi);
       $this->d10_codigo = ($this->d10_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["d10_codigo"]:$this->d10_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($d10_codedi,$d10_codigo){ 
      $this->atualizacampos();
       $this->d10_codedi = $d10_codedi; 
       $this->d10_codigo = $d10_codigo; 
     if(($this->d10_codedi == null) || ($this->d10_codedi == "") ){ 
       $this->erro_sql = " Campo d10_codedi nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->d10_codigo == null) || ($this->d10_codigo == "") ){ 
       $this->erro_sql = " Campo d10_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into editalproj(
                                       d10_codedi 
                                      ,d10_codigo 
                       )
                values (
                                $this->d10_codedi 
                               ,$this->d10_codigo 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Edital com os Projetos - Lista ($this->d10_codedi."-".$this->d10_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Edital com os Projetos - Lista já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Edital com os Projetos - Lista ($this->d10_codedi."-".$this->d10_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->d10_codedi."-".$this->d10_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->d10_codedi,$this->d10_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,4689,'$this->d10_codedi','I')");
       $resac = db_query("insert into db_acountkey values($acount,4690,'$this->d10_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,619,4689,'','".AddSlashes(pg_result($resaco,0,'d10_codedi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,619,4690,'','".AddSlashes(pg_result($resaco,0,'d10_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($d10_codedi=null,$d10_codigo=null) { 
      $this->atualizacampos();
     $sql = " update editalproj set ";
     $virgula = "";
     if(trim($this->d10_codedi)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d10_codedi"])){ 
       $sql  .= $virgula." d10_codedi = $this->d10_codedi ";
       $virgula = ",";
       if(trim($this->d10_codedi) == null ){ 
         $this->erro_sql = " Campo Codigo Edital nao Informado.";
         $this->erro_campo = "d10_codedi";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d10_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d10_codigo"])){ 
       $sql  .= $virgula." d10_codigo = $this->d10_codigo ";
       $virgula = ",";
       if(trim($this->d10_codigo) == null ){ 
         $this->erro_sql = " Campo Código da lista de projeto nao Informado.";
         $this->erro_campo = "d10_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($d10_codedi!=null){
       $sql .= " d10_codedi = $this->d10_codedi";
     }
     if($d10_codigo!=null){
       $sql .= " and  d10_codigo = $this->d10_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->d10_codedi,$this->d10_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4689,'$this->d10_codedi','A')");
         $resac = db_query("insert into db_acountkey values($acount,4690,'$this->d10_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d10_codedi"]))
           $resac = db_query("insert into db_acount values($acount,619,4689,'".AddSlashes(pg_result($resaco,$conresaco,'d10_codedi'))."','$this->d10_codedi',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d10_codigo"]))
           $resac = db_query("insert into db_acount values($acount,619,4690,'".AddSlashes(pg_result($resaco,$conresaco,'d10_codigo'))."','$this->d10_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Edital com os Projetos - Lista nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->d10_codedi."-".$this->d10_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Edital com os Projetos - Lista nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->d10_codedi."-".$this->d10_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->d10_codedi."-".$this->d10_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($d10_codedi=null,$d10_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($d10_codedi,$d10_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4689,'$d10_codedi','E')");
         $resac = db_query("insert into db_acountkey values($acount,4690,'$d10_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,619,4689,'','".AddSlashes(pg_result($resaco,$iresaco,'d10_codedi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,619,4690,'','".AddSlashes(pg_result($resaco,$iresaco,'d10_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from editalproj
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($d10_codedi != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " d10_codedi = $d10_codedi ";
        }
        if($d10_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " d10_codigo = $d10_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Edital com os Projetos - Lista nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$d10_codedi."-".$d10_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Edital com os Projetos - Lista nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$d10_codedi."-".$d10_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$d10_codedi."-".$d10_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:editalproj";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $d10_codedi=null,$d10_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from editalproj ";
     $sql .= "      inner join edital  on  edital.d01_codedi = editalproj.d10_codedi";
     $sql .= "      inner join projmelhorias  on  projmelhorias.d40_codigo = editalproj.d10_codigo";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = edital.d01_idlog";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = projmelhorias.d40_codlog";
     $sql .= "      inner join db_usuarios  as a on   a.id_usuario = projmelhorias.d40_login";
     $sql2 = "";
     if($dbwhere==""){
       if($d10_codedi!=null ){
         $sql2 .= " where editalproj.d10_codedi = $d10_codedi "; 
       } 
       if($d10_codigo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " editalproj.d10_codigo = $d10_codigo "; 
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
   function sql_query_file ( $d10_codedi=null,$d10_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from editalproj ";
     $sql2 = "";
     if($dbwhere==""){
       if($d10_codedi!=null ){
         $sql2 .= " where editalproj.d10_codedi = $d10_codedi "; 
       } 
       if($d10_codigo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " editalproj.d10_codigo = $d10_codigo "; 
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