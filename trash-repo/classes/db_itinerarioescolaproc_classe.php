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

//MODULO: educação
//CLASSE DA ENTIDADE itinerarioescolaproc
class cl_itinerarioescolaproc { 
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
   var $ed222_i_codigo = 0; 
   var $ed222_i_itinerario = 0; 
   var $ed222_i_escolaproc = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed222_i_codigo = int4 = Código 
                 ed222_i_itinerario = int4 = Itinerario 
                 ed222_i_escolaproc = int4 = Escola fora rede municipal 
                 ";
   //funcao construtor da classe 
   function cl_itinerarioescolaproc() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("itinerarioescolaproc"); 
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
       $this->ed222_i_codigo = ($this->ed222_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed222_i_codigo"]:$this->ed222_i_codigo);
       $this->ed222_i_itinerario = ($this->ed222_i_itinerario == ""?@$GLOBALS["HTTP_POST_VARS"]["ed222_i_itinerario"]:$this->ed222_i_itinerario);
       $this->ed222_i_escolaproc = ($this->ed222_i_escolaproc == ""?@$GLOBALS["HTTP_POST_VARS"]["ed222_i_escolaproc"]:$this->ed222_i_escolaproc);
     }else{
       $this->ed222_i_codigo = ($this->ed222_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed222_i_codigo"]:$this->ed222_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed222_i_codigo){ 
      $this->atualizacampos();
     if($this->ed222_i_itinerario == null ){ 
       $this->erro_sql = " Campo Itinerario nao Informado.";
       $this->erro_campo = "ed222_i_itinerario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed222_i_escolaproc == null ){ 
       $this->erro_sql = " Campo Escola fora rede municipal nao Informado.";
       $this->erro_campo = "ed222_i_escolaproc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed222_i_codigo == "" || $ed222_i_codigo == null ){
       $result = db_query("select nextval('itinerarioescolaproc_ed222_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: itinerarioescolaproc_ed222_i_codigo_seq do campo: ed222_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed222_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from itinerarioescolaproc_ed222_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed222_i_codigo)){
         $this->erro_sql = " Campo ed222_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed222_i_codigo = $ed222_i_codigo; 
       }
     }
     if(($this->ed222_i_codigo == null) || ($this->ed222_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed222_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into itinerarioescolaproc(
                                       ed222_i_codigo 
                                      ,ed222_i_itinerario 
                                      ,ed222_i_escolaproc 
                       )
                values (
                                $this->ed222_i_codigo 
                               ,$this->ed222_i_itinerario 
                               ,$this->ed222_i_escolaproc 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "itinerarioescolaproc ($this->ed222_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "itinerarioescolaproc já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "itinerarioescolaproc ($this->ed222_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed222_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed222_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,11168,'$this->ed222_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1928,11168,'','".AddSlashes(pg_result($resaco,0,'ed222_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1928,11169,'','".AddSlashes(pg_result($resaco,0,'ed222_i_itinerario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1928,11170,'','".AddSlashes(pg_result($resaco,0,'ed222_i_escolaproc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed222_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update itinerarioescolaproc set ";
     $virgula = "";
     if(trim($this->ed222_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed222_i_codigo"])){ 
       $sql  .= $virgula." ed222_i_codigo = $this->ed222_i_codigo ";
       $virgula = ",";
       if(trim($this->ed222_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed222_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed222_i_itinerario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed222_i_itinerario"])){ 
       $sql  .= $virgula." ed222_i_itinerario = $this->ed222_i_itinerario ";
       $virgula = ",";
       if(trim($this->ed222_i_itinerario) == null ){ 
         $this->erro_sql = " Campo Itinerario nao Informado.";
         $this->erro_campo = "ed222_i_itinerario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed222_i_escolaproc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed222_i_escolaproc"])){ 
       $sql  .= $virgula." ed222_i_escolaproc = $this->ed222_i_escolaproc ";
       $virgula = ",";
       if(trim($this->ed222_i_escolaproc) == null ){ 
         $this->erro_sql = " Campo Escola fora rede municipal nao Informado.";
         $this->erro_campo = "ed222_i_escolaproc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed222_i_codigo!=null){
       $sql .= " ed222_i_codigo = $this->ed222_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed222_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11168,'$this->ed222_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed222_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1928,11168,'".AddSlashes(pg_result($resaco,$conresaco,'ed222_i_codigo'))."','$this->ed222_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed222_i_itinerario"]))
           $resac = db_query("insert into db_acount values($acount,1928,11169,'".AddSlashes(pg_result($resaco,$conresaco,'ed222_i_itinerario'))."','$this->ed222_i_itinerario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed222_i_escolaproc"]))
           $resac = db_query("insert into db_acount values($acount,1928,11170,'".AddSlashes(pg_result($resaco,$conresaco,'ed222_i_escolaproc'))."','$this->ed222_i_escolaproc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "itinerarioescolaproc nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed222_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "itinerarioescolaproc nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed222_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed222_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed222_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed222_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11168,'$ed222_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1928,11168,'','".AddSlashes(pg_result($resaco,$iresaco,'ed222_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1928,11169,'','".AddSlashes(pg_result($resaco,$iresaco,'ed222_i_itinerario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1928,11170,'','".AddSlashes(pg_result($resaco,$iresaco,'ed222_i_escolaproc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from itinerarioescolaproc
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed222_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed222_i_codigo = $ed222_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "itinerarioescolaproc nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed222_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "itinerarioescolaproc nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed222_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed222_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:itinerarioescolaproc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ed222_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from itinerarioescolaproc ";
     $sql .= "      inner join itinerario  on  itinerario.ed218_i_codigo = itinerarioescolaproc.ed222_i_itinerario";
     $sql .= "      inner join escolaproc  on  escolaproc.ed82_i_codigo = itinerarioescolaproc.ed222_i_escolaproc";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = itinerario.ed218_i_usuario";
     $sql .= "      inner join rota  on  rota.ed217_i_codigo = itinerario.ed218_i_rota";
     $sql2 = "";
     if($dbwhere==""){
       if($ed222_i_codigo!=null ){
         $sql2 .= " where itinerarioescolaproc.ed222_i_codigo = $ed222_i_codigo "; 
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
   function sql_query_file ( $ed222_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from itinerarioescolaproc ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed222_i_codigo!=null ){
         $sql2 .= " where itinerarioescolaproc.ed222_i_codigo = $ed222_i_codigo "; 
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