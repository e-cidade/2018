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
//CLASSE DA ENTIDADE auladada
class cl_auladada { 
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
   var $ed243_i_codigo = 0; 
   var $ed243_d_data_dia = null; 
   var $ed243_d_data_mes = null; 
   var $ed243_d_data_ano = null; 
   var $ed243_d_data = null; 
   var $ed243_i_procavaliacao = 0; 
   var $ed243_i_regencia = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed243_i_codigo = int4 = Código 
                 ed243_d_data = date = Data 
                 ed243_i_procavaliacao = int4 = Procavaliacao 
                 ed243_i_regencia = int4 = Regencia 
                 ";
   //funcao construtor da classe 
   function cl_auladada() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("auladada"); 
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
       $this->ed243_i_codigo = ($this->ed243_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed243_i_codigo"]:$this->ed243_i_codigo);
       if($this->ed243_d_data == ""){
         $this->ed243_d_data_dia = ($this->ed243_d_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed243_d_data_dia"]:$this->ed243_d_data_dia);
         $this->ed243_d_data_mes = ($this->ed243_d_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed243_d_data_mes"]:$this->ed243_d_data_mes);
         $this->ed243_d_data_ano = ($this->ed243_d_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed243_d_data_ano"]:$this->ed243_d_data_ano);
         if($this->ed243_d_data_dia != ""){
            $this->ed243_d_data = $this->ed243_d_data_ano."-".$this->ed243_d_data_mes."-".$this->ed243_d_data_dia;
         }
       }
       $this->ed243_i_procavaliacao = ($this->ed243_i_procavaliacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed243_i_procavaliacao"]:$this->ed243_i_procavaliacao);
       $this->ed243_i_regencia = ($this->ed243_i_regencia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed243_i_regencia"]:$this->ed243_i_regencia);
     }else{
       $this->ed243_i_codigo = ($this->ed243_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed243_i_codigo"]:$this->ed243_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed243_i_codigo){ 
      $this->atualizacampos();
     if($this->ed243_d_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "ed243_d_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed243_i_procavaliacao == null ){ 
       $this->erro_sql = " Campo Procavaliacao nao Informado.";
       $this->erro_campo = "ed243_i_procavaliacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed243_i_regencia == null ){ 
       $this->erro_sql = " Campo Regencia nao Informado.";
       $this->erro_campo = "ed243_i_regencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed243_i_codigo == "" || $ed243_i_codigo == null ){
       $result = db_query("select nextval('auladada_ed243_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: auladada_ed243_i_codigo_seq do campo: ed243_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed243_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from auladada_ed243_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed243_i_codigo)){
         $this->erro_sql = " Campo ed243_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed243_i_codigo = $ed243_i_codigo; 
       }
     }
     if(($this->ed243_i_codigo == null) || ($this->ed243_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed243_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into auladada(
                                       ed243_i_codigo 
                                      ,ed243_d_data 
                                      ,ed243_i_procavaliacao 
                                      ,ed243_i_regencia 
                       )
                values (
                                $this->ed243_i_codigo 
                               ,".($this->ed243_d_data == "null" || $this->ed243_d_data == ""?"null":"'".$this->ed243_d_data."'")." 
                               ,$this->ed243_i_procavaliacao 
                               ,$this->ed243_i_regencia 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Aula dada ($this->ed243_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Aula dada já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Aula dada ($this->ed243_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed243_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed243_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,11780,'$this->ed243_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2036,11780,'','".AddSlashes(pg_result($resaco,0,'ed243_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2036,11781,'','".AddSlashes(pg_result($resaco,0,'ed243_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2036,11796,'','".AddSlashes(pg_result($resaco,0,'ed243_i_procavaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2036,11795,'','".AddSlashes(pg_result($resaco,0,'ed243_i_regencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed243_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update auladada set ";
     $virgula = "";
     if(trim($this->ed243_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed243_i_codigo"])){ 
       $sql  .= $virgula." ed243_i_codigo = $this->ed243_i_codigo ";
       $virgula = ",";
       if(trim($this->ed243_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed243_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed243_d_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed243_d_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed243_d_data_dia"] !="") ){ 
       $sql  .= $virgula." ed243_d_data = '$this->ed243_d_data' ";
       $virgula = ",";
       if(trim($this->ed243_d_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "ed243_d_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed243_d_data_dia"])){ 
         $sql  .= $virgula." ed243_d_data = null ";
         $virgula = ",";
         if(trim($this->ed243_d_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "ed243_d_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ed243_i_procavaliacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed243_i_procavaliacao"])){ 
       $sql  .= $virgula." ed243_i_procavaliacao = $this->ed243_i_procavaliacao ";
       $virgula = ",";
       if(trim($this->ed243_i_procavaliacao) == null ){ 
         $this->erro_sql = " Campo Procavaliacao nao Informado.";
         $this->erro_campo = "ed243_i_procavaliacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed243_i_regencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed243_i_regencia"])){ 
       $sql  .= $virgula." ed243_i_regencia = $this->ed243_i_regencia ";
       $virgula = ",";
       if(trim($this->ed243_i_regencia) == null ){ 
         $this->erro_sql = " Campo Regencia nao Informado.";
         $this->erro_campo = "ed243_i_regencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed243_i_codigo!=null){
       $sql .= " ed243_i_codigo = $this->ed243_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed243_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11780,'$this->ed243_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed243_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,2036,11780,'".AddSlashes(pg_result($resaco,$conresaco,'ed243_i_codigo'))."','$this->ed243_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed243_d_data"]))
           $resac = db_query("insert into db_acount values($acount,2036,11781,'".AddSlashes(pg_result($resaco,$conresaco,'ed243_d_data'))."','$this->ed243_d_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed243_i_procavaliacao"]))
           $resac = db_query("insert into db_acount values($acount,2036,11796,'".AddSlashes(pg_result($resaco,$conresaco,'ed243_i_procavaliacao'))."','$this->ed243_i_procavaliacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed243_i_regencia"]))
           $resac = db_query("insert into db_acount values($acount,2036,11795,'".AddSlashes(pg_result($resaco,$conresaco,'ed243_i_regencia'))."','$this->ed243_i_regencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Aula dada nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed243_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Aula dada nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed243_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed243_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed243_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed243_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11780,'$ed243_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2036,11780,'','".AddSlashes(pg_result($resaco,$iresaco,'ed243_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2036,11781,'','".AddSlashes(pg_result($resaco,$iresaco,'ed243_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2036,11796,'','".AddSlashes(pg_result($resaco,$iresaco,'ed243_i_procavaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2036,11795,'','".AddSlashes(pg_result($resaco,$iresaco,'ed243_i_regencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from auladada
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed243_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed243_i_codigo = $ed243_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Aula dada nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed243_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Aula dada nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed243_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed243_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:auladada";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>