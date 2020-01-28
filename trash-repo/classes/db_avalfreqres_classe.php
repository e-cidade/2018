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
//CLASSE DA ENTIDADE avalfreqres
class cl_avalfreqres { 
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
   var $ed67_i_codigo = 0; 
   var $ed67_i_procavaliacao = 0; 
   var $ed67_i_procresultado = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed67_i_codigo = int8 = Código 
                 ed67_i_procavaliacao = int8 = Período de Avaliação 
                 ed67_i_procresultado = int8 = Resultado 
                 ";
   //funcao construtor da classe 
   function cl_avalfreqres() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("avalfreqres"); 
          $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]."?ed67_i_procresultado=".@$GLOBALS["HTTP_POST_VARS"]["ed67_i_procresultado"]."&ed42_c_descr=".@$GLOBALS["HTTP_POST_VARS"]["ed42_c_descr"]."&procedimento=".@$GLOBALS["HTTP_POST_VARS"]["procedimento"]."&forma=".@$GLOBALS["HTTP_POST_VARS"]["forma"]);
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
       $this->ed67_i_codigo = ($this->ed67_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed67_i_codigo"]:$this->ed67_i_codigo);
       $this->ed67_i_procavaliacao = ($this->ed67_i_procavaliacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed67_i_procavaliacao"]:$this->ed67_i_procavaliacao);
       $this->ed67_i_procresultado = ($this->ed67_i_procresultado == ""?@$GLOBALS["HTTP_POST_VARS"]["ed67_i_procresultado"]:$this->ed67_i_procresultado);
     }else{
       $this->ed67_i_codigo = ($this->ed67_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed67_i_codigo"]:$this->ed67_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed67_i_codigo){ 
      $this->atualizacampos();
     if($this->ed67_i_procavaliacao == null ){ 
       $this->erro_sql = " Campo Período de Avaliação nao Informado.";
       $this->erro_campo = "ed67_i_procavaliacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed67_i_procresultado == null ){ 
       $this->erro_sql = " Campo Resultado nao Informado.";
       $this->erro_campo = "ed67_i_procresultado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed67_i_codigo == "" || $ed67_i_codigo == null ){
       $result = db_query("select nextval('avalfreqres_ed67_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: avalfreqres_ed67_i_codigo_seq do campo: ed67_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed67_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from avalfreqres_ed67_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed67_i_codigo)){
         $this->erro_sql = " Campo ed67_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed67_i_codigo = $ed67_i_codigo; 
       }
     }
     if(($this->ed67_i_codigo == null) || ($this->ed67_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed67_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into avalfreqres(
                                       ed67_i_codigo 
                                      ,ed67_i_procavaliacao 
                                      ,ed67_i_procresultado 
                       )
                values (
                                $this->ed67_i_codigo 
                               ,$this->ed67_i_procavaliacao 
                               ,$this->ed67_i_procresultado 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Avaliações com Frequência ($this->ed67_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Avaliações com Frequência já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Avaliações com Frequência ($this->ed67_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed67_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed67_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1008480,'$this->ed67_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1010082,1008480,'','".AddSlashes(pg_result($resaco,0,'ed67_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010082,1008481,'','".AddSlashes(pg_result($resaco,0,'ed67_i_procavaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010082,1008482,'','".AddSlashes(pg_result($resaco,0,'ed67_i_procresultado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed67_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update avalfreqres set ";
     $virgula = "";
     if(trim($this->ed67_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed67_i_codigo"])){ 
       $sql  .= $virgula." ed67_i_codigo = $this->ed67_i_codigo ";
       $virgula = ",";
       if(trim($this->ed67_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed67_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed67_i_procavaliacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed67_i_procavaliacao"])){ 
       $sql  .= $virgula." ed67_i_procavaliacao = $this->ed67_i_procavaliacao ";
       $virgula = ",";
       if(trim($this->ed67_i_procavaliacao) == null ){ 
         $this->erro_sql = " Campo Período de Avaliação nao Informado.";
         $this->erro_campo = "ed67_i_procavaliacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed67_i_procresultado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed67_i_procresultado"])){ 
       $sql  .= $virgula." ed67_i_procresultado = $this->ed67_i_procresultado ";
       $virgula = ",";
       if(trim($this->ed67_i_procresultado) == null ){ 
         $this->erro_sql = " Campo Resultado nao Informado.";
         $this->erro_campo = "ed67_i_procresultado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed67_i_codigo!=null){
       $sql .= " ed67_i_codigo = $this->ed67_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed67_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008480,'$this->ed67_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed67_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1010082,1008480,'".AddSlashes(pg_result($resaco,$conresaco,'ed67_i_codigo'))."','$this->ed67_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed67_i_procavaliacao"]))
           $resac = db_query("insert into db_acount values($acount,1010082,1008481,'".AddSlashes(pg_result($resaco,$conresaco,'ed67_i_procavaliacao'))."','$this->ed67_i_procavaliacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed67_i_procresultado"]))
           $resac = db_query("insert into db_acount values($acount,1010082,1008482,'".AddSlashes(pg_result($resaco,$conresaco,'ed67_i_procresultado'))."','$this->ed67_i_procresultado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Avaliações com Frequência nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed67_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Avaliações com Frequência nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed67_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed67_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed67_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed67_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008480,'$ed67_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1010082,1008480,'','".AddSlashes(pg_result($resaco,$iresaco,'ed67_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010082,1008481,'','".AddSlashes(pg_result($resaco,$iresaco,'ed67_i_procavaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010082,1008482,'','".AddSlashes(pg_result($resaco,$iresaco,'ed67_i_procresultado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from avalfreqres
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed67_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed67_i_codigo = $ed67_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Avaliações com Frequência nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed67_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Avaliações com Frequência nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed67_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed67_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:avalfreqres";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ed67_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from avalfreqres ";
     $sql .= "      inner join procavaliacao  on  procavaliacao.ed41_i_codigo = avalfreqres.ed67_i_procavaliacao";
     $sql .= "      inner join procresultado  on  procresultado.ed43_i_codigo = avalfreqres.ed67_i_procresultado";
     $sql .= "      inner join periodoavaliacao  on  periodoavaliacao.ed09_i_codigo = procavaliacao.ed41_i_periodoavaliacao";
     $sql .= "      inner join formaavaliacao  on  formaavaliacao.ed37_i_codigo = procavaliacao.ed41_i_formaavaliacao";
     $sql .= "      inner join procedimento  on  procedimento.ed40_i_codigo = procavaliacao.ed41_i_procedimento";
     $sql .= "      inner join resultado  on  resultado.ed42_i_codigo = procresultado.ed43_i_resultado";
     $sql2 = "";
     if($dbwhere==""){
       if($ed67_i_codigo!=null ){
         $sql2 .= " where avalfreqres.ed67_i_codigo = $ed67_i_codigo "; 
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
   function sql_query_file ( $ed67_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from avalfreqres ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed67_i_codigo!=null ){
         $sql2 .= " where avalfreqres.ed67_i_codigo = $ed67_i_codigo "; 
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