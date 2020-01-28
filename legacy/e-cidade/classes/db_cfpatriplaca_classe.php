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

//MODULO: patrim
//CLASSE DA ENTIDADE cfpatriplaca
class cl_cfpatriplaca { 
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
   var $t07_instit = 0; 
   var $t07_confplaca = 0; 
   var $t07_digseqplaca = 0; 
   var $t07_sequencial = 0; 
   var $t07_obrigplaca = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 t07_instit = int4 = Instituição 
                 t07_confplaca = int4 = Tipo de configuração da placa 
                 t07_digseqplaca = int4 = Quant. digitos da seq. da placa 
                 t07_sequencial = int8 = Sequencial da placa 
                 t07_obrigplaca = bool = Obrigar informar placa 
                 ";
   //funcao construtor da classe 
   function cl_cfpatriplaca() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cfpatriplaca"); 
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
       $this->t07_instit = ($this->t07_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["t07_instit"]:$this->t07_instit);
       $this->t07_confplaca = ($this->t07_confplaca == ""?@$GLOBALS["HTTP_POST_VARS"]["t07_confplaca"]:$this->t07_confplaca);
       $this->t07_digseqplaca = ($this->t07_digseqplaca == ""?@$GLOBALS["HTTP_POST_VARS"]["t07_digseqplaca"]:$this->t07_digseqplaca);
       $this->t07_sequencial = ($this->t07_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["t07_sequencial"]:$this->t07_sequencial);
       $this->t07_obrigplaca = ($this->t07_obrigplaca == "f"?@$GLOBALS["HTTP_POST_VARS"]["t07_obrigplaca"]:$this->t07_obrigplaca);
     }else{
       $this->t07_instit = ($this->t07_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["t07_instit"]:$this->t07_instit);
     }
   }
   // funcao para inclusao
   function incluir ($t07_instit){ 
      $this->atualizacampos();
     if($this->t07_confplaca == null ){ 
       $this->erro_sql = " Campo Tipo de configuração da placa nao Informado.";
       $this->erro_campo = "t07_confplaca";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t07_digseqplaca == null ){ 
       $this->erro_sql = " Campo Quant. digitos da seq. da placa nao Informado.";
       $this->erro_campo = "t07_digseqplaca";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t07_sequencial == null ){ 
       $this->erro_sql = " Campo Sequencial da placa nao Informado.";
       $this->erro_campo = "t07_sequencial";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t07_obrigplaca == null ){ 
       $this->t07_obrigplaca = "t";
     }
       $this->t07_instit = $t07_instit; 
     if(($this->t07_instit == null) || ($this->t07_instit == "") ){ 
       $this->erro_sql = " Campo t07_instit nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cfpatriplaca(
                                       t07_instit 
                                      ,t07_confplaca 
                                      ,t07_digseqplaca 
                                      ,t07_sequencial 
                                      ,t07_obrigplaca 
                       )
                values (
                                $this->t07_instit 
                               ,$this->t07_confplaca 
                               ,$this->t07_digseqplaca 
                               ,$this->t07_sequencial 
                               ,'$this->t07_obrigplaca' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Parâmetro de placas ($this->t07_instit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Parâmetro de placas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Parâmetro de placas ($this->t07_instit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t07_instit;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->t07_instit));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9794,'$this->t07_instit','I')");
       $resac = db_query("insert into db_acount values($acount,1681,9794,'','".AddSlashes(pg_result($resaco,0,'t07_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1681,9795,'','".AddSlashes(pg_result($resaco,0,'t07_confplaca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1681,9796,'','".AddSlashes(pg_result($resaco,0,'t07_digseqplaca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1681,9797,'','".AddSlashes(pg_result($resaco,0,'t07_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1681,10154,'','".AddSlashes(pg_result($resaco,0,'t07_obrigplaca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($t07_instit=null) { 
      $this->atualizacampos();
     $sql = " update cfpatriplaca set ";
     $virgula = "";
     if(trim($this->t07_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t07_instit"])){ 
       $sql  .= $virgula." t07_instit = $this->t07_instit ";
       $virgula = ",";
       if(trim($this->t07_instit) == null ){ 
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "t07_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t07_confplaca)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t07_confplaca"])){ 
       $sql  .= $virgula." t07_confplaca = $this->t07_confplaca ";
       $virgula = ",";
       if(trim($this->t07_confplaca) == null ){ 
         $this->erro_sql = " Campo Tipo de configuração da placa nao Informado.";
         $this->erro_campo = "t07_confplaca";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t07_digseqplaca)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t07_digseqplaca"])){ 
       $sql  .= $virgula." t07_digseqplaca = $this->t07_digseqplaca ";
       $virgula = ",";
       if(trim($this->t07_digseqplaca) == null ){ 
         $this->erro_sql = " Campo Quant. digitos da seq. da placa nao Informado.";
         $this->erro_campo = "t07_digseqplaca";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t07_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t07_sequencial"])){ 
       $sql  .= $virgula." t07_sequencial = $this->t07_sequencial ";
       $virgula = ",";
       if(trim($this->t07_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial da placa nao Informado.";
         $this->erro_campo = "t07_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t07_obrigplaca)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t07_obrigplaca"])){ 
       $sql  .= $virgula." t07_obrigplaca = '$this->t07_obrigplaca' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($t07_instit!=null){
       $sql .= " t07_instit = $this->t07_instit";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->t07_instit));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9794,'$this->t07_instit','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t07_instit"]))
           $resac = db_query("insert into db_acount values($acount,1681,9794,'".AddSlashes(pg_result($resaco,$conresaco,'t07_instit'))."','$this->t07_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t07_confplaca"]))
           $resac = db_query("insert into db_acount values($acount,1681,9795,'".AddSlashes(pg_result($resaco,$conresaco,'t07_confplaca'))."','$this->t07_confplaca',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t07_digseqplaca"]))
           $resac = db_query("insert into db_acount values($acount,1681,9796,'".AddSlashes(pg_result($resaco,$conresaco,'t07_digseqplaca'))."','$this->t07_digseqplaca',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t07_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1681,9797,'".AddSlashes(pg_result($resaco,$conresaco,'t07_sequencial'))."','$this->t07_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t07_obrigplaca"]))
           $resac = db_query("insert into db_acount values($acount,1681,10154,'".AddSlashes(pg_result($resaco,$conresaco,'t07_obrigplaca'))."','$this->t07_obrigplaca',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Parâmetro de placas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->t07_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Parâmetro de placas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->t07_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t07_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($t07_instit=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($t07_instit));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9794,'$t07_instit','E')");
         $resac = db_query("insert into db_acount values($acount,1681,9794,'','".AddSlashes(pg_result($resaco,$iresaco,'t07_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1681,9795,'','".AddSlashes(pg_result($resaco,$iresaco,'t07_confplaca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1681,9796,'','".AddSlashes(pg_result($resaco,$iresaco,'t07_digseqplaca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1681,9797,'','".AddSlashes(pg_result($resaco,$iresaco,'t07_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1681,10154,'','".AddSlashes(pg_result($resaco,$iresaco,'t07_obrigplaca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from cfpatriplaca
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($t07_instit != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " t07_instit = $t07_instit ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Parâmetro de placas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$t07_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Parâmetro de placas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$t07_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$t07_instit;
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
        $this->erro_sql   = "Record Vazio na Tabela:cfpatriplaca";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $t07_instit=null,$campos="*",$ordem=null,$dbwhere="") { 
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
     $sql .= " from cfpatriplaca ";
     $sql .= "      inner join bensconfplaca  on  bensconfplaca.t40_codigo = cfpatriplaca.t07_confplaca";
     $sql2 = "";
     if($dbwhere==""){
       if($t07_instit!=null ){
         $sql2 .= " where cfpatriplaca.t07_instit = $t07_instit "; 
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
   function sql_query_file ( $t07_instit=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cfpatriplaca ";
     $sql2 = "";
     if($dbwhere==""){
       if($t07_instit!=null ){
         $sql2 .= " where cfpatriplaca.t07_instit = $t07_instit "; 
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
  /**
   * 
   * Seleciona um item e da um lock na linha onde a instituição for igual a da sessão para novos updates 
   * @return string
   */
  function sql_query_fileLockInLine( $t07_instit=null,$campos="*",$ordem=null,$dbwhere=""){
    $sql = "select ";
    if($campos != "*" ) {
      
      $campos_sql = split("#",$campos);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }else{
      $sql .= $campos;
    }
    $sql .= " from cfpatriplaca ";
    $sql2 = "";
    if($dbwhere=="") {
      
      if($t07_instit!=null ) {
        $sql2 .= " where cfpatriplaca.t07_instit = $t07_instit ";
      }
    }else if($dbwhere != ""){
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if($ordem != null ){
      $sql .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++) {
        
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    $sql .=" for update;";
    
    return $sql;
  }
}
?>