<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: patrimonio
//CLASSE DA ENTIDADE benscorlanc
class cl_benscorlanc { 
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
   var $t62_codcor = 0; 
   var $t62_data_dia = null; 
   var $t62_data_mes = null; 
   var $t62_data_ano = null; 
   var $t62_data = null; 
   var $t62_obs = null; 
   var $t62_codcom = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 t62_codcor = int8 = Correção 
                 t62_data = date = Data da correção 
                 t62_obs = text = Observações 
                 t62_codcom = int8 = Código da comissão 
                 ";
   //funcao construtor da classe 
   function cl_benscorlanc() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("benscorlanc"); 
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
       $this->t62_codcor = ($this->t62_codcor == ""?@$GLOBALS["HTTP_POST_VARS"]["t62_codcor"]:$this->t62_codcor);
       if($this->t62_data == ""){
         $this->t62_data_dia = ($this->t62_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["t62_data_dia"]:$this->t62_data_dia);
         $this->t62_data_mes = ($this->t62_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["t62_data_mes"]:$this->t62_data_mes);
         $this->t62_data_ano = ($this->t62_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["t62_data_ano"]:$this->t62_data_ano);
         if($this->t62_data_dia != ""){
            $this->t62_data = $this->t62_data_ano."-".$this->t62_data_mes."-".$this->t62_data_dia;
         }
       }
       $this->t62_obs = ($this->t62_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["t62_obs"]:$this->t62_obs);
       $this->t62_codcom = ($this->t62_codcom == ""?@$GLOBALS["HTTP_POST_VARS"]["t62_codcom"]:$this->t62_codcom);
     }else{
       $this->t62_codcor = ($this->t62_codcor == ""?@$GLOBALS["HTTP_POST_VARS"]["t62_codcor"]:$this->t62_codcor);
     }
   }
   // funcao para inclusao
   function incluir ($t62_codcor){ 
      $this->atualizacampos();
     if($this->t62_data == null ){ 
       $this->erro_sql = " Campo Data da correção nao Informado.";
       $this->erro_campo = "t62_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t62_codcom == null ){ 
       $this->erro_sql = " Campo Código da comissão nao Informado.";
       $this->erro_campo = "t62_codcom";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($t62_codcor == "" || $t62_codcor == null ){
       $result = db_query("select nextval('benscorlanc_t62_codcor_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: benscorlanc_t62_codcor_seq do campo: t62_codcor"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->t62_codcor = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from benscorlanc_t62_codcor_seq");
       if(($result != false) && (pg_result($result,0,0) < $t62_codcor)){
         $this->erro_sql = " Campo t62_codcor maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->t62_codcor = $t62_codcor; 
       }
     }
     if(($this->t62_codcor == null) || ($this->t62_codcor == "") ){ 
       $this->erro_sql = " Campo t62_codcor nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into benscorlanc(
                                       t62_codcor 
                                      ,t62_data 
                                      ,t62_obs 
                                      ,t62_codcom 
                       )
                values (
                                $this->t62_codcor 
                               ,".($this->t62_data == "null" || $this->t62_data == ""?"null":"'".$this->t62_data."'")." 
                               ,'$this->t62_obs' 
                               ,$this->t62_codcom 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Lançamentos de correção de bens ($this->t62_codcor) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Lançamentos de correção de bens já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Lançamentos de correção de bens ($this->t62_codcor) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t62_codcor;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->t62_codcor));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5801,'$this->t62_codcor','I')");
       $resac = db_query("insert into db_acount values($acount,922,5801,'','".AddSlashes(pg_result($resaco,0,'t62_codcor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,922,5802,'','".AddSlashes(pg_result($resaco,0,'t62_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,922,5803,'','".AddSlashes(pg_result($resaco,0,'t62_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,922,5804,'','".AddSlashes(pg_result($resaco,0,'t62_codcom'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($t62_codcor=null) { 
      $this->atualizacampos();
     $sql = " update benscorlanc set ";
     $virgula = "";
     if(trim($this->t62_codcor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t62_codcor"])){ 
       $sql  .= $virgula." t62_codcor = $this->t62_codcor ";
       $virgula = ",";
       if(trim($this->t62_codcor) == null ){ 
         $this->erro_sql = " Campo Correção nao Informado.";
         $this->erro_campo = "t62_codcor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t62_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t62_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["t62_data_dia"] !="") ){ 
       $sql  .= $virgula." t62_data = '$this->t62_data' ";
       $virgula = ",";
       if(trim($this->t62_data) == null ){ 
         $this->erro_sql = " Campo Data da correção nao Informado.";
         $this->erro_campo = "t62_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["t62_data_dia"])){ 
         $sql  .= $virgula." t62_data = null ";
         $virgula = ",";
         if(trim($this->t62_data) == null ){ 
           $this->erro_sql = " Campo Data da correção nao Informado.";
           $this->erro_campo = "t62_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->t62_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t62_obs"])){ 
       $sql  .= $virgula." t62_obs = '$this->t62_obs' ";
       $virgula = ",";
     }
     if(trim($this->t62_codcom)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t62_codcom"])){ 
       $sql  .= $virgula." t62_codcom = $this->t62_codcom ";
       $virgula = ",";
       if(trim($this->t62_codcom) == null ){ 
         $this->erro_sql = " Campo Código da comissão nao Informado.";
         $this->erro_campo = "t62_codcom";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($t62_codcor!=null){
       $sql .= " t62_codcor = $this->t62_codcor";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->t62_codcor));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5801,'$this->t62_codcor','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t62_codcor"]) || $this->t62_codcor != "")
           $resac = db_query("insert into db_acount values($acount,922,5801,'".AddSlashes(pg_result($resaco,$conresaco,'t62_codcor'))."','$this->t62_codcor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t62_data"]) || $this->t62_data != "")
           $resac = db_query("insert into db_acount values($acount,922,5802,'".AddSlashes(pg_result($resaco,$conresaco,'t62_data'))."','$this->t62_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t62_obs"]) || $this->t62_obs != "")
           $resac = db_query("insert into db_acount values($acount,922,5803,'".AddSlashes(pg_result($resaco,$conresaco,'t62_obs'))."','$this->t62_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t62_codcom"]) || $this->t62_codcom != "")
           $resac = db_query("insert into db_acount values($acount,922,5804,'".AddSlashes(pg_result($resaco,$conresaco,'t62_codcom'))."','$this->t62_codcom',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Lançamentos de correção de bens nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->t62_codcor;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Lançamentos de correção de bens nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->t62_codcor;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t62_codcor;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($t62_codcor=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($t62_codcor));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5801,'$t62_codcor','E')");
         $resac = db_query("insert into db_acount values($acount,922,5801,'','".AddSlashes(pg_result($resaco,$iresaco,'t62_codcor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,922,5802,'','".AddSlashes(pg_result($resaco,$iresaco,'t62_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,922,5803,'','".AddSlashes(pg_result($resaco,$iresaco,'t62_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,922,5804,'','".AddSlashes(pg_result($resaco,$iresaco,'t62_codcom'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from benscorlanc
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($t62_codcor != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " t62_codcor = $t62_codcor ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Lançamentos de correção de bens nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$t62_codcor;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Lançamentos de correção de bens nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$t62_codcor;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$t62_codcor;
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
        $this->erro_sql   = "Record Vazio na Tabela:benscorlanc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $t62_codcor=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from benscorlanc ";
     $sql .= "      inner join acordocomissao  on  acordocomissao.ac08_sequencial = benscorlanc.t62_codcom";
     $sql .= "      inner join db_config  on  db_config.codigo = acordocomissao.ac08_instit";
     $sql .= "      inner join acordocomissaotipo  on  acordocomissaotipo.ac43_sequencial = acordocomissao.ac08_acordocomissaotipo";
     $sql2 = "";
     if($dbwhere==""){
       if($t62_codcor!=null ){
         $sql2 .= " where benscorlanc.t62_codcor = $t62_codcor "; 
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
   function sql_query_file ( $t62_codcor=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from benscorlanc ";
     $sql2 = "";
     if($dbwhere==""){
       if($t62_codcor!=null ){
         $sql2 .= " where benscorlanc.t62_codcor = $t62_codcor "; 
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