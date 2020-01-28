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
//CLASSE DA ENTIDADE conceitos
class cl_conceitos { 
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
   var $ed30_i_codigo = 0; 
   var $ed30_c_letra = null; 
   var $ed30_f_valorinicial = 0; 
   var $ed30_f_valorfinal = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed30_i_codigo = int8 = Código 
                 ed30_c_letra = char(1) = Letra 
                 ed30_f_valorinicial = float8 = Valor Inicial 
                 ed30_f_valorfinal = float8 = Valor Final 
                 ";
   //funcao construtor da classe 
   function cl_conceitos() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("conceitos"); 
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
       $this->ed30_i_codigo = ($this->ed30_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed30_i_codigo"]:$this->ed30_i_codigo);
       $this->ed30_c_letra = ($this->ed30_c_letra == ""?@$GLOBALS["HTTP_POST_VARS"]["ed30_c_letra"]:$this->ed30_c_letra);
       $this->ed30_f_valorinicial = ($this->ed30_f_valorinicial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed30_f_valorinicial"]:$this->ed30_f_valorinicial);
       $this->ed30_f_valorfinal = ($this->ed30_f_valorfinal == ""?@$GLOBALS["HTTP_POST_VARS"]["ed30_f_valorfinal"]:$this->ed30_f_valorfinal);
     }else{
       $this->ed30_i_codigo = ($this->ed30_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed30_i_codigo"]:$this->ed30_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed30_i_codigo){ 
      $this->atualizacampos();
     if($this->ed30_c_letra == null ){ 
       $this->erro_sql = " Campo Letra nao Informado.";
       $this->erro_campo = "ed30_c_letra";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed30_f_valorinicial == null ){ 
       $this->erro_sql = " Campo Valor Inicial nao Informado.";
       $this->erro_campo = "ed30_f_valorinicial";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed30_f_valorfinal == null ){ 
       $this->erro_sql = " Campo Valor Final nao Informado.";
       $this->erro_campo = "ed30_f_valorfinal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed30_i_codigo == "" || $ed30_i_codigo == null ){
       $result = @pg_query("select nextval('conceitos_ed30_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: conceitos_ed30_i_codigo_seq do campo: ed30_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed30_i_codigo = pg_result($result,0,0); 
     }else{
       $result = @pg_query("select last_value from conceitos_ed30_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed30_i_codigo)){
         $this->erro_sql = " Campo ed30_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed30_i_codigo = $ed30_i_codigo; 
       }
     }
     if(($this->ed30_i_codigo == null) || ($this->ed30_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed30_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into conceitos(
                                       ed30_i_codigo 
                                      ,ed30_c_letra 
                                      ,ed30_f_valorinicial 
                                      ,ed30_f_valorfinal 
                       )
                values (
                                $this->ed30_i_codigo 
                               ,'$this->ed30_c_letra' 
                               ,$this->ed30_f_valorinicial 
                               ,$this->ed30_f_valorfinal 
                      )";
     $result = @pg_exec($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Conceitos ($this->ed30_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Conceitos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Conceitos ($this->ed30_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed30_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed30_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,1006195,'$this->ed30_i_codigo','I')");
       $resac = pg_query("insert into db_acount values($acount,1006022,1006195,'','".AddSlashes(pg_result($resaco,0,'ed30_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1006022,1006196,'','".AddSlashes(pg_result($resaco,0,'ed30_c_letra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1006022,1006197,'','".AddSlashes(pg_result($resaco,0,'ed30_f_valorinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1006022,1006204,'','".AddSlashes(pg_result($resaco,0,'ed30_f_valorfinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed30_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update conceitos set ";
     $virgula = "";
     if(trim($this->ed30_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed30_i_codigo"])){ 
       $sql  .= $virgula." ed30_i_codigo = $this->ed30_i_codigo ";
       $virgula = ",";
       if(trim($this->ed30_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed30_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed30_c_letra)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed30_c_letra"])){ 
       $sql  .= $virgula." ed30_c_letra = '$this->ed30_c_letra' ";
       $virgula = ",";
       if(trim($this->ed30_c_letra) == null ){ 
         $this->erro_sql = " Campo Letra nao Informado.";
         $this->erro_campo = "ed30_c_letra";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed30_f_valorinicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed30_f_valorinicial"])){ 
       $sql  .= $virgula." ed30_f_valorinicial = $this->ed30_f_valorinicial ";
       $virgula = ",";
       if(trim($this->ed30_f_valorinicial) == null ){ 
         $this->erro_sql = " Campo Valor Inicial nao Informado.";
         $this->erro_campo = "ed30_f_valorinicial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed30_f_valorfinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed30_f_valorfinal"])){ 
       $sql  .= $virgula." ed30_f_valorfinal = $this->ed30_f_valorfinal ";
       $virgula = ",";
       if(trim($this->ed30_f_valorfinal) == null ){ 
         $this->erro_sql = " Campo Valor Final nao Informado.";
         $this->erro_campo = "ed30_f_valorfinal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed30_i_codigo!=null){
       $sql .= " ed30_i_codigo = $this->ed30_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed30_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,1006195,'$this->ed30_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed30_i_codigo"]))
           $resac = pg_query("insert into db_acount values($acount,1006022,1006195,'".AddSlashes(pg_result($resaco,$conresaco,'ed30_i_codigo'))."','$this->ed30_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed30_c_letra"]))
           $resac = pg_query("insert into db_acount values($acount,1006022,1006196,'".AddSlashes(pg_result($resaco,$conresaco,'ed30_c_letra'))."','$this->ed30_c_letra',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed30_f_valorinicial"]))
           $resac = pg_query("insert into db_acount values($acount,1006022,1006197,'".AddSlashes(pg_result($resaco,$conresaco,'ed30_f_valorinicial'))."','$this->ed30_f_valorinicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed30_f_valorfinal"]))
           $resac = pg_query("insert into db_acount values($acount,1006022,1006204,'".AddSlashes(pg_result($resaco,$conresaco,'ed30_f_valorfinal'))."','$this->ed30_f_valorfinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Conceitos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed30_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Conceitos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed30_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed30_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed30_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed30_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,1006195,'$ed30_i_codigo','E')");
         $resac = pg_query("insert into db_acount values($acount,1006022,1006195,'','".AddSlashes(pg_result($resaco,$iresaco,'ed30_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1006022,1006196,'','".AddSlashes(pg_result($resaco,$iresaco,'ed30_c_letra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1006022,1006197,'','".AddSlashes(pg_result($resaco,$iresaco,'ed30_f_valorinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1006022,1006204,'','".AddSlashes(pg_result($resaco,$iresaco,'ed30_f_valorfinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from conceitos
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed30_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed30_i_codigo = $ed30_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Conceitos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed30_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Conceitos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed30_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed30_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:conceitos";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed30_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from conceitos ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed30_i_codigo!=null ){
         $sql2 .= " where conceitos.ed30_i_codigo = $ed30_i_codigo "; 
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
   function sql_query_file ( $ed30_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from conceitos ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed30_i_codigo!=null ){
         $sql2 .= " where conceitos.ed30_i_codigo = $ed30_i_codigo "; 
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