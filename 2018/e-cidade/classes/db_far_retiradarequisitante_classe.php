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

//MODULO: Farmacia
//CLASSE DA ENTIDADE far_retiradarequisitante
class cl_far_retiradarequisitante { 
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
   var $fa08_i_codigo = 0; 
   var $fa08_i_retirada = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 fa08_i_codigo = int4 = Código 
                 fa08_i_retirada = int4 = Retirada 
                 ";
   //funcao construtor da classe 
   function cl_far_retiradarequisitante() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("far_retiradarequisitante"); 
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
       $this->fa08_i_codigo = ($this->fa08_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["fa08_i_codigo"]:$this->fa08_i_codigo);
       $this->fa08_i_retirada = ($this->fa08_i_retirada == ""?@$GLOBALS["HTTP_POST_VARS"]["fa08_i_retirada"]:$this->fa08_i_retirada);
     }else{
       $this->fa08_i_codigo = ($this->fa08_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["fa08_i_codigo"]:$this->fa08_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($fa08_i_codigo){ 
      $this->atualizacampos();
     if($this->fa08_i_retirada == null ){ 
       $this->erro_sql = " Campo Retirada nao Informado.";
       $this->erro_campo = "fa08_i_retirada";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($fa08_i_codigo == "" || $fa08_i_codigo == null ){
       $result = db_query("select nextval('farequisitante_fa08_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: farequisitante_fa08_i_codigo_seq do campo: fa08_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->fa08_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from farequisitante_fa08_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $fa08_i_codigo)){
         $this->erro_sql = " Campo fa08_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->fa08_i_codigo = $fa08_i_codigo; 
       }
     }
     if(($this->fa08_i_codigo == null) || ($this->fa08_i_codigo == "") ){ 
       $this->erro_sql = " Campo fa08_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into far_retiradarequisitante(
                                       fa08_i_codigo 
                                      ,fa08_i_retirada 
                       )
                values (
                                $this->fa08_i_codigo 
                               ,$this->fa08_i_retirada 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "far_retiradarequisitante ($this->fa08_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "far_retiradarequisitante já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "far_retiradarequisitante ($this->fa08_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->fa08_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->fa08_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,12220,'$this->fa08_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2124,12220,'','".AddSlashes(pg_result($resaco,0,'fa08_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2124,12222,'','".AddSlashes(pg_result($resaco,0,'fa08_i_retirada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($fa08_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update far_retiradarequisitante set ";
     $virgula = "";
     if(trim($this->fa08_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa08_i_codigo"])){ 
       $sql  .= $virgula." fa08_i_codigo = $this->fa08_i_codigo ";
       $virgula = ",";
       if(trim($this->fa08_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "fa08_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa08_i_retirada)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa08_i_retirada"])){ 
       $sql  .= $virgula." fa08_i_retirada = $this->fa08_i_retirada ";
       $virgula = ",";
       if(trim($this->fa08_i_retirada) == null ){ 
         $this->erro_sql = " Campo Retirada nao Informado.";
         $this->erro_campo = "fa08_i_retirada";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($fa08_i_codigo!=null){
       $sql .= " fa08_i_codigo = $this->fa08_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->fa08_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12220,'$this->fa08_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa08_i_codigo"]) || $this->fa08_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2124,12220,'".AddSlashes(pg_result($resaco,$conresaco,'fa08_i_codigo'))."','$this->fa08_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa08_i_retirada"]) || $this->fa08_i_retirada != "")
           $resac = db_query("insert into db_acount values($acount,2124,12222,'".AddSlashes(pg_result($resaco,$conresaco,'fa08_i_retirada'))."','$this->fa08_i_retirada',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "far_retiradarequisitante nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->fa08_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "far_retiradarequisitante nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->fa08_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->fa08_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($fa08_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($fa08_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12220,'$fa08_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2124,12220,'','".AddSlashes(pg_result($resaco,$iresaco,'fa08_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2124,12222,'','".AddSlashes(pg_result($resaco,$iresaco,'fa08_i_retirada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from far_retiradarequisitante
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($fa08_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " fa08_i_codigo = $fa08_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "far_retiradarequisitante nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$fa08_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "far_retiradarequisitante nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$fa08_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$fa08_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:far_retiradarequisitante";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $fa08_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from far_retiradarequisitante ";
     $sql .= "      inner join far_retirada  on  far_retirada.fa04_i_codigo = far_retiradarequisitante.fa08_i_retirada";
     $sql .= "      inner join far_retiradarequi  on  far_retiradarequi.fa07_i_retirada = far_retirada.fa04_i_codigo"; //coloquei +
	 $sql .= "      inner join matrequi  on  matrequi.m40_codigo = far_retiradarequi.fa07_i_matrequi";//coloquei +
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = far_retirada.fa04_i_dbusuario";
     //$sql .= "      inner join far_tiporeceita  on  far_tiporeceita.fa03_i_codigo = far_retirada.fa04_i_tiporeceita";
     //$sql .= "      inner join unidades  on  unidades.sd02_i_codigo = far_retirada.fa04_i_unidades";
     //$sql .= "      inner join medicos  on  medicos.sd03_i_codigo = far_retirada.fa04_i_profissional";
     $sql .= "      left join far_requisitantecgs  on  fa38_i_requisitante = far_retiradarequisitante.fa08_i_codigo";
     $sql .= "      left join far_requisitanteoutro  on  fa39_i_requisitante = far_retiradarequisitante.fa08_i_codigo";
     $sql .= "      left join cgs_und  on  z01_i_cgsund = far_requisitantecgs.fa38_i_cgs";
     $sql2 = "";
     if($dbwhere==""){
       if($fa08_i_codigo!=null ){
         $sql2 .= " where far_retiradarequisitante.fa08_i_codigo = $fa08_i_codigo "; 
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
   function sql_query_file ( $fa08_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from far_retiradarequisitante ";
     $sql2 = "";
     if($dbwhere==""){
       if($fa08_i_codigo!=null ){
         $sql2 .= " where far_retiradarequisitante.fa08_i_codigo = $fa08_i_codigo "; 
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