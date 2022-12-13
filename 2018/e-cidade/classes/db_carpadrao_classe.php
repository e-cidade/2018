<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

//MODULO: cadastro
//CLASSE DA ENTIDADE carpadrao
class cl_carpadrao { 
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
   var $j33_codgrupo = 0; 
   var $j33_codcaracter = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 j33_codgrupo = int4 = Grupo 
                 j33_codcaracter = int4 = Característica Principal 
                 ";
   //funcao construtor da classe 
   function cl_carpadrao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("carpadrao"); 
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
       $this->j33_codgrupo = ($this->j33_codgrupo == ""?@$GLOBALS["HTTP_POST_VARS"]["j33_codgrupo"]:$this->j33_codgrupo);
       $this->j33_codcaracter = ($this->j33_codcaracter == ""?@$GLOBALS["HTTP_POST_VARS"]["j33_codcaracter"]:$this->j33_codcaracter);
     }else{
       $this->j33_codgrupo = ($this->j33_codgrupo == ""?@$GLOBALS["HTTP_POST_VARS"]["j33_codgrupo"]:$this->j33_codgrupo);
     }
   }
   // funcao para Inclusão
   function incluir ($j33_codgrupo){ 
      $this->atualizacampos();
     if($this->j33_codcaracter == null ){ 
       $this->erro_sql = " Campo Característica Principal não informado.";
       $this->erro_campo = "j33_codcaracter";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->j33_codgrupo = $j33_codgrupo; 
     if(($this->j33_codgrupo == null) || ($this->j33_codgrupo == "") ){ 
       $this->erro_sql = " Campo j33_codgrupo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into carpadrao(
                                       j33_codgrupo 
                                      ,j33_codcaracter 
                       )
                values (
                                $this->j33_codgrupo 
                               ,$this->j33_codcaracter 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Características Padrões ($this->j33_codgrupo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Características Padrões já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Características Padrões ($this->j33_codgrupo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->j33_codgrupo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->j33_codgrupo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,3585,'$this->j33_codgrupo','I')");
       $resac = db_query("insert into db_acount values($acount,516,3585,'','".AddSlashes(pg_result($resaco,0,'j33_codgrupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,516,3586,'','".AddSlashes(pg_result($resaco,0,'j33_codcaracter'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($j33_codgrupo=null) { 
      $this->atualizacampos();
     $sql = " update carpadrao set ";
     $virgula = "";
     if(trim($this->j33_codgrupo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j33_codgrupo"])){ 
        if(trim($this->j33_codgrupo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["j33_codgrupo"])){ 
           $this->j33_codgrupo = "0" ; 
        } 
       $sql  .= $virgula." j33_codgrupo = $this->j33_codgrupo ";
       $virgula = ",";
       if(trim($this->j33_codgrupo) == null ){ 
         $this->erro_sql = " Campo Grupo não informado.";
         $this->erro_campo = "j33_codgrupo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j33_codcaracter)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j33_codcaracter"])){ 
        if(trim($this->j33_codcaracter)=="" && isset($GLOBALS["HTTP_POST_VARS"]["j33_codcaracter"])){ 
           $this->j33_codcaracter = "0" ; 
        } 
       $sql  .= $virgula." j33_codcaracter = $this->j33_codcaracter ";
       $virgula = ",";
       if(trim($this->j33_codcaracter) == null ){ 
         $this->erro_sql = " Campo Característica Principal não informado.";
         $this->erro_campo = "j33_codcaracter";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($j33_codgrupo!=null){
       $sql .= " j33_codgrupo = $this->j33_codgrupo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->j33_codgrupo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3585,'$this->j33_codgrupo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j33_codgrupo"]))
           $resac = db_query("insert into db_acount values($acount,516,3585,'".AddSlashes(pg_result($resaco,$conresaco,'j33_codgrupo'))."','$this->j33_codgrupo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j33_codcaracter"]))
           $resac = db_query("insert into db_acount values($acount,516,3586,'".AddSlashes(pg_result($resaco,$conresaco,'j33_codcaracter'))."','$this->j33_codcaracter',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Características Padrões não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j33_codgrupo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Características Padrões não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j33_codgrupo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->j33_codgrupo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($j33_codgrupo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($j33_codgrupo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3585,'$j33_codgrupo','E')");
         $resac = db_query("insert into db_acount values($acount,516,3585,'','".AddSlashes(pg_result($resaco,$iresaco,'j33_codgrupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,516,3586,'','".AddSlashes(pg_result($resaco,$iresaco,'j33_codcaracter'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from carpadrao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($j33_codgrupo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j33_codgrupo = $j33_codgrupo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Características Padrões não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j33_codgrupo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Características Padrões não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j33_codgrupo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$j33_codgrupo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   public function sql_record($sql) { 
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
     $this->numrows = pg_num_rows($result);
      if ($this->numrows == 0) {
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:carpadrao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $j33_codgrupo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from carpadrao ";
     $sql .= "      inner join caracter  on  caracter.j31_codigo = carpadrao.j33_codcaracter";
     $sql .= "      inner join cargrup  on  cargrup.j32_grupo = carpadrao.j33_codgrupo";
     $sql .= "      inner join cargrup  as a on   a.j32_grupo = caracter.j31_grupo";
     $sql2 = "";
     if($dbwhere==""){
       if($j33_codgrupo!=null ){
         $sql2 .= " where carpadrao.j33_codgrupo = $j33_codgrupo "; 
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
   function sql_query_file ( $j33_codgrupo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from carpadrao ";
     $sql2 = "";
     if($dbwhere==""){
       if($j33_codgrupo!=null ){
         $sql2 .= " where carpadrao.j33_codgrupo = $j33_codgrupo "; 
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
