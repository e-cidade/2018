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

//MODULO: prefeitura
//CLASSE DA ENTIDADE db_daitomadorpaga
class cl_db_daitomadorpaga { 
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
   var $w09_sequencial = 0; 
   var $w09_daitomador = 0; 
   var $w09_dtpaga_dia = null; 
   var $w09_dtpaga_mes = null; 
   var $w09_dtpaga_ano = null; 
   var $w09_dtpaga = null; 
   var $w09_valpago = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 w09_sequencial = int4 = Sequencial 
                 w09_daitomador = int4 = Sequencial 
                 w09_dtpaga = date = Data de pagamento 
                 w09_valpago = float8 = Valor do pagamento 
                 ";
   //funcao construtor da classe 
   function cl_db_daitomadorpaga() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_daitomadorpaga"); 
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
       $this->w09_sequencial = ($this->w09_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["w09_sequencial"]:$this->w09_sequencial);
       $this->w09_daitomador = ($this->w09_daitomador == ""?@$GLOBALS["HTTP_POST_VARS"]["w09_daitomador"]:$this->w09_daitomador);
       if($this->w09_dtpaga == ""){
         $this->w09_dtpaga_dia = ($this->w09_dtpaga_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["w09_dtpaga_dia"]:$this->w09_dtpaga_dia);
         $this->w09_dtpaga_mes = ($this->w09_dtpaga_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["w09_dtpaga_mes"]:$this->w09_dtpaga_mes);
         $this->w09_dtpaga_ano = ($this->w09_dtpaga_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["w09_dtpaga_ano"]:$this->w09_dtpaga_ano);
         if($this->w09_dtpaga_dia != ""){
            $this->w09_dtpaga = $this->w09_dtpaga_ano."-".$this->w09_dtpaga_mes."-".$this->w09_dtpaga_dia;
         }
       }
       $this->w09_valpago = ($this->w09_valpago == ""?@$GLOBALS["HTTP_POST_VARS"]["w09_valpago"]:$this->w09_valpago);
     }else{
       $this->w09_sequencial = ($this->w09_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["w09_sequencial"]:$this->w09_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($w09_sequencial){ 
      $this->atualizacampos();
     if($this->w09_daitomador == null ){ 
       $this->erro_sql = " Campo Sequencial nao Informado.";
       $this->erro_campo = "w09_daitomador";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w09_dtpaga == null ){ 
       $this->erro_sql = " Campo Data de pagamento nao Informado.";
       $this->erro_campo = "w09_dtpaga_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->w09_valpago == null ){ 
       $this->erro_sql = " Campo Valor do pagamento nao Informado.";
       $this->erro_campo = "w09_valpago";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($w09_sequencial == "" || $w09_sequencial == null ){
       $result = db_query("select nextval('db_daitomadorpaga_w09_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: db_daitomadorpaga_w09_sequencial_seq do campo: w09_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->w09_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from db_daitomadorpaga_w09_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $w09_sequencial)){
         $this->erro_sql = " Campo w09_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->w09_sequencial = $w09_sequencial; 
       }
     }
     if(($this->w09_sequencial == null) || ($this->w09_sequencial == "") ){ 
       $this->erro_sql = " Campo w09_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_daitomadorpaga(
                                       w09_sequencial 
                                      ,w09_daitomador 
                                      ,w09_dtpaga 
                                      ,w09_valpago 
                       )
                values (
                                $this->w09_sequencial 
                               ,$this->w09_daitomador 
                               ,".($this->w09_dtpaga == "null" || $this->w09_dtpaga == ""?"null":"'".$this->w09_dtpaga."'")." 
                               ,$this->w09_valpago 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Pagamentos das retencoes efetuadas ($this->w09_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Pagamentos das retencoes efetuadas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Pagamentos das retencoes efetuadas ($this->w09_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->w09_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->w09_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9120,'$this->w09_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1561,9120,'','".AddSlashes(pg_result($resaco,0,'w09_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1561,9121,'','".AddSlashes(pg_result($resaco,0,'w09_daitomador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1561,9122,'','".AddSlashes(pg_result($resaco,0,'w09_dtpaga'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1561,9123,'','".AddSlashes(pg_result($resaco,0,'w09_valpago'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($w09_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update db_daitomadorpaga set ";
     $virgula = "";
     if(trim($this->w09_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w09_sequencial"])){ 
       $sql  .= $virgula." w09_sequencial = $this->w09_sequencial ";
       $virgula = ",";
       if(trim($this->w09_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "w09_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w09_daitomador)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w09_daitomador"])){ 
       $sql  .= $virgula." w09_daitomador = $this->w09_daitomador ";
       $virgula = ",";
       if(trim($this->w09_daitomador) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "w09_daitomador";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->w09_dtpaga)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w09_dtpaga_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["w09_dtpaga_dia"] !="") ){ 
       $sql  .= $virgula." w09_dtpaga = '$this->w09_dtpaga' ";
       $virgula = ",";
       if(trim($this->w09_dtpaga) == null ){ 
         $this->erro_sql = " Campo Data de pagamento nao Informado.";
         $this->erro_campo = "w09_dtpaga_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["w09_dtpaga_dia"])){ 
         $sql  .= $virgula." w09_dtpaga = null ";
         $virgula = ",";
         if(trim($this->w09_dtpaga) == null ){ 
           $this->erro_sql = " Campo Data de pagamento nao Informado.";
           $this->erro_campo = "w09_dtpaga_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->w09_valpago)!="" || isset($GLOBALS["HTTP_POST_VARS"]["w09_valpago"])){ 
       $sql  .= $virgula." w09_valpago = $this->w09_valpago ";
       $virgula = ",";
       if(trim($this->w09_valpago) == null ){ 
         $this->erro_sql = " Campo Valor do pagamento nao Informado.";
         $this->erro_campo = "w09_valpago";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($w09_sequencial!=null){
       $sql .= " w09_sequencial = $this->w09_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->w09_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9120,'$this->w09_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w09_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1561,9120,'".AddSlashes(pg_result($resaco,$conresaco,'w09_sequencial'))."','$this->w09_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w09_daitomador"]))
           $resac = db_query("insert into db_acount values($acount,1561,9121,'".AddSlashes(pg_result($resaco,$conresaco,'w09_daitomador'))."','$this->w09_daitomador',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w09_dtpaga"]))
           $resac = db_query("insert into db_acount values($acount,1561,9122,'".AddSlashes(pg_result($resaco,$conresaco,'w09_dtpaga'))."','$this->w09_dtpaga',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["w09_valpago"]))
           $resac = db_query("insert into db_acount values($acount,1561,9123,'".AddSlashes(pg_result($resaco,$conresaco,'w09_valpago'))."','$this->w09_valpago',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Pagamentos das retencoes efetuadas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->w09_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Pagamentos das retencoes efetuadas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->w09_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->w09_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($w09_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($w09_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9120,'$w09_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1561,9120,'','".AddSlashes(pg_result($resaco,$iresaco,'w09_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1561,9121,'','".AddSlashes(pg_result($resaco,$iresaco,'w09_daitomador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1561,9122,'','".AddSlashes(pg_result($resaco,$iresaco,'w09_dtpaga'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1561,9123,'','".AddSlashes(pg_result($resaco,$iresaco,'w09_valpago'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_daitomadorpaga
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($w09_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " w09_sequencial = $w09_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Pagamentos das retencoes efetuadas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$w09_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Pagamentos das retencoes efetuadas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$w09_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$w09_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_daitomadorpaga";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>